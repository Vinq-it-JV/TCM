<?php

namespace NotificationBundle\Command;

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
 * Class CheckNotificationCommand
 * @package DataCollectorBundle\Command
 */
class CheckNotificationCommand extends ContainerAwareCommand
{
    private $options;
    private $storeNotifications;

    /**
     * Function configuration
     */
    protected function configure()
    {
        $this->setName('check-notify')
            ->setDescription('Command for checking to send notifications')
            ->addArgument('options', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'notify');

    }

    /**
     * Execute function
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return boolean
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->options = $input->getArgument('options');
        foreach ($this->options as $option) {
            switch ($option) {
                case 'notify':
                    $this->checkNotifications($output);
                    break;
                default:
                    break;
            }
        }
        return true;
    }

    /**
     * Check stores for notifications
     * @param OutputInterface $output
     */
    protected function checkNotifications(OutputInterface $output)
    {
        $output->writeln('checking for sending notifications.');

        $stores = StoreQuery::create()
            ->filterByIsEnabled(true)
            ->filterByIsMaintenance(false)
            ->find();

        $helper = $this->getContainer()->get('notification_helper');

        foreach ($stores as $store) {
            $result = 0;
            $output->write('Checking ' . $store->getName());
            if ($this->checkUnnotified($store)) {
                $result = $helper->sendNotificationsEmail($store);
                $this->setNotificationsSent($store);
            }
            $output->writeln(' - Sent: ' . $result . ' emails.');
        }
    }

    protected function setNotificationsSent(Store $store)
    {
        foreach ($this->storeNotifications as $k => $notification) {
            switch ($k) {
                case 'Inputs':
                    foreach ($notification as $inotification) {
                        $_inotification = CbInputNotificationQuery::create()->findOneById($inotification['Id']);
                        if (!empty($_inotification)) {
                            $_inotification->setIsNotified(true);
                            $_inotification->save();
                        }
                    }
                    break;
                case 'Temperatures':
                    foreach ($notification as $tnotification) {
                        $_tnotification = DsTemperatureNotificationQuery::create()->findOneById($tnotification['Id']);
                        if (!empty($_tnotification)) {
                            $_tnotification->setIsNotified(true);
                            $_tnotification->save();
                        }
                    }
                    break;
                default:
                    break;
            }
        }
    }

    protected function checkUnnotified(Store $store)
    {
        $this->storeNotifications = $store->getNotificationsArray();

        foreach ($this->storeNotifications as $k => $notification) {
            switch ($k) {
                case 'Inputs':
                    foreach ($notification as $inotification) {
                        if (!$inotification['IsNotified'])
                            return true;
                    }
                    break;
                case 'Temperatures':
                    foreach ($notification as $tnotification) {
                        if (!$tnotification['IsNotified'])
                            return true;
                    }
                    break;
                default:
                    break;
            }
        }
        return false;
    }

}