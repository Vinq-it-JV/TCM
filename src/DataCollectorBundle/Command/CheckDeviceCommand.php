<?php

namespace DataCollectorBundle\Command;

use DeviceBundle\Model\CbInput;
use DeviceBundle\Model\DsTemperatureSensor;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use DeviceBundle\Model\CbInputQuery;
use DeviceBundle\Model\DsTemperatureSensorQuery;


/**
 * Class CheckDeviceCommand
 * @package DataCollectorBundle\Command
 */
class CheckDeviceCommand extends ContainerAwareCommand
{
    private $options;

    /**
     * Function configuration
     */
    protected function configure()
    {
        $this->setName('check-device')
            ->setDescription('Command for checking device status')
            ->addArgument('options', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'inactive, notify, all.');

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
                case 'inactive':
                    $this->checkInactiveSensors($output);
                    break;
                case 'notify':
                    $this->checkNotifySensors($output);
                    break;
                case 'all':
                    $this->checkAllSensors($output);
                    break;
                default:
                    break;
            }
        }
        return true;
    }

    /**
     * Check sensors for inactive
     * @param OutputInterface $output
     */
    protected function checkInactiveSensors(OutputInterface $output)
    {
        $output->writeln('checking for sensor inactive.');

        $inputs = CbInputQuery::create()
            ->filterByIsEnabled(true)
            ->filterByState(CbInput::STATE_ACTIVE)
            ->find();

        foreach ($inputs as $input) {
            $input->checkSensorInactive();
        }

        $temperatures = DsTemperatureSensorQuery::create()
            ->filterByIsEnabled(true)
            ->filterByState(DsTemperatureSensor::STATE_ACTIVE)
            ->find();

        foreach ($temperatures as $temperature) {
            $temperature->checkSensorInactive();
        }
    }

    /**
     * Check sensors for: notification
     * @param OutputInterface $output
     */
    protected function checkNotifySensors(OutputInterface $output)
    {
        $output->writeln('checking for sensor notify.');

        $inputs = CbInputQuery::create()
            ->filterByIsEnabled(true)
            ->filterByState(CbInput::STATE_ACTIVE)
            ->find();

        foreach ($inputs as $input)
            $input->checkSensorNotify();

        $temperatures = DsTemperatureSensorQuery::create()
            ->filterByIsEnabled(true)
            ->filterByState(DsTemperatureSensor::STATE_ACTIVE)
            ->find();

        foreach ($temperatures as $temperature)
            $temperature->checkSensorNotify();
    }

    /**
     * Check sensors for: inactive and notification
     * @param OutputInterface $output
     */
    protected function checkAllSensors(OutputInterface $output)
    {
        $output->writeln('checking for sensor inactive and notify.');

        $this->checkInactiveSensors($output);
        $this->checkNotifySensors($output);
    }
}