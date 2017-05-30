<?php

namespace NotificationBundle\Command;

use DeviceBundle\Model\CbInput;
use DeviceBundle\Model\DsTemperatureSensor;
use StoreBundle\Model\StoreQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use DeviceBundle\Model\CbInputQuery;
use DeviceBundle\Model\DsTemperatureSensorQuery;


/**
 * Class ListNotificationCommand
 * @package NotificationBundle\Command
 */
class ListNotificationCommand extends ContainerAwareCommand
{
    private $options;

    /**
     * Function configuration
     */
    protected function configure()
    {
        $this->setName('list-notifications')
            ->setDescription('Command for listing notifications')
            ->addArgument('options', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'store');
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
            $parts = explode('=', $option);
            if (!is_array($parts))
                return $this->listAllNotifications($output);
            switch ($parts[0]) {
                case 'store':
                    if (is_numeric($parts[1])) {
                        $store = StoreQuery::create()->findOneById($parts[1]);
                        if (!empty($store))
                            return $this->listStoreNotifications($store, $output);
                    }
                    break;
                default:
                    break;
            }
        }
        return $this->listAllNotifications($output);
    }

    /**
     * List all notifications
     * @param OutputInterface $output
     */
    protected function listAllNotifications(OutputInterface $output)
    {
        $output->writeln('listing all store notifications.');
        $stores = StoreQuery::create()->find();

        if ($stores->isEmpty())
            return false;

        foreach ($stores as $store) {
            $result = $this->listStoreNotifications($store, $output);
            if ($result)
                $output->writeln('');
        }
        return true;
    }

    protected function listStoreNotifications($store, OutputInterface $output)
    {
        $store = StoreQuery::create()->findOneById($store->getId());

        if (empty($store))
            return false;

        $notifications = $store->getNotificationsArray();

        $output->writeln(sprintf('Listing notifications of store: %d - %s', $store->getId(), $store->getName()));

        if (empty($notifications) || $notifications['Count'] == 0) {
            $output->writeln(' > Store has no notifications.');
            return true;
        }

        foreach ($notifications as $k => $notification) {
            switch ($k) {
                case 'Inputs':
                    $output->writeln(' > Inputs:');
                    foreach ($notification as $inotification) {
                        $output->writeln(sprintf('  > %d - ([%s] - %s) switch when: %d, switch state: %d (notified: %d | handled: %d) on: %s', $inotification['Id'], $inotification['Sensor']->getUid(), $inotification['Sensor']->getName(), $inotification['Sensor']->getSwitchWhen(), $inotification['SwitchState'], $inotification['IsNotified'], $inotification['IsHandled'], $inotification['CreatedAt']->format('d-m-Y H:i:s')));
                    }
                    break;
                case 'Temperatures':
                    $output->writeln(' > Temperatures:');
                    foreach ($notification as $tnotification) {
                        $output->writeln(sprintf('  > %d - ([%s] - %s) low limit: %s, high limit: %s temperature: %s (notified: %d | handled: %d) on: %s', $tnotification['Id'], $tnotification['Sensor']->getUid(), $tnotification['Sensor']->getName(), $tnotification['Sensor']->getLowLimit(), $tnotification['Sensor']->getHighLimit(), $tnotification['Temperature'], $tnotification['IsNotified'], $tnotification['IsHandled'], $tnotification['CreatedAt']->format('d-m-Y H:i:s')));
                    }
                    break;
                case 'Count':
                    $output->writeln(sprintf(' > Total notifications: %d', $notification));
                    break;
            }
        }

        return true;
    }

}