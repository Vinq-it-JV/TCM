<?php

namespace StoreBundle\Command;

use DeviceBundle\Model\CbInputQuery;
use DeviceBundle\Model\DsTemperatureSensorQuery;
use NotificationBundle\Model\CbInputNotificationQuery;
use NotificationBundle\Model\DsTemperatureNotificationQuery;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CheckMaintenanceCommand
 * @package StoreBundle\Command
 */
class CheckMaintenanceCommand extends ContainerAwareCommand
{
    /**
     * Function configuration
     */
    protected function configure()
    {
        $this->setName('check-maintenance')
            ->setDescription('Command for checking maintenance (auto off)');
    }

    /**
     * Execute function
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return boolean
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->checkMaintenance($output);
    }

    /**
     * Check stores for notifications
     * @param OutputInterface $output
     */
    protected function checkMaintenance(OutputInterface $output)
    {
        $output->writeln('checking for maintenance (auto off).');

        $currentDate = new \DateTime();
        $autoOffTime = $this->getContainer()->getParameter('maintenance_auto_off');

        $stores = StoreQuery::create()
            ->filterByIsEnabled(true)
            ->filterByIsMaintenance(true)
            ->find();

        /* @var $store \StoreBundle\Model\Store */
        foreach ($stores as $store) {
            $maintenanceDate = $store->getMaintenanceStartedAt();
            if (($currentDate->getTimestamp() - $maintenanceDate->getTimestamp()) > $autoOffTime) {
                $store->setIsMaintenance(false);
                $store->setMaintenanceStartedAt(null);
                $store->save();
                $output->writeln(sprintf("store '%s' released from maintenance (auto off).", $store->getName()));
            }
        }
    }
}