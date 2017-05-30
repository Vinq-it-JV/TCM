<?php

namespace DeviceBundle\Command;

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
 * Class ListDeviceCommand
 * @package DeviceBundle\Command
 */
class ListDeviceCommand extends ContainerAwareCommand
{
    private $options;

    /**
     * Function configuration
     */
    protected function configure()
    {
        $this->setName('list-devices')
            ->setDescription('Command for listing devices')
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
                return $this->listAllDevices($output);
            switch ($parts[0]) {
                case 'store':
                    if (is_numeric($parts[1])) {
                        $store = StoreQuery::create()->findOneById($parts[1]);
                        if (!empty($store))
                            return $this->listStoreDevices($store, $output);
                    }
                    break;
                default:
                    break;
            }
        }
        return $this->listAllDevices($output);
    }

    /**
     * Check sensors for inactive
     * @param OutputInterface $output
     */
    protected function listAllDevices(OutputInterface $output)
    {
        $output->writeln('listing all store devices.');
        $stores = StoreQuery::create()->find();

        if ($stores->isEmpty())
            return false;

        foreach ($stores as $store) {
            $result = $this->listStoreDevices($store, $output);
            if ($result)
                $output->writeln('');
        }
        return true;
    }

    protected function listStoreDevices($store, OutputInterface $output)
    {
        $store = StoreQuery::create()->findOneById($store->getId());

        if (empty($store))
            return false;

        $inputs = $store->getCbInputs();
        $temperatures = $store->getDsTemperatureSensors();

        $output->writeln(sprintf('Listing devices of store: %d - %s', $store->getId(), $store->getName()));

        if ($inputs->isEmpty() && $temperatures->isEmpty())
            $output->writeln(' > Store has no devices.');

        if (!$inputs->isEmpty()) {
            $output->writeln(' > Inputs:');
            foreach ($inputs as $input) {
                $output->writeln(sprintf('  %d > [%s] - %s, state: %d', $input->getId(), $input->getUid(), $input->getName(), $input->getSwitchState()));
            }
        }
        if (!$temperatures->isEmpty()) {
            $output->writeln(' > Temperatures:');
            foreach ($temperatures as $temperature) {
                $output->writeln(sprintf('  %d > [%s] - %s, temp: %d', $temperature->getId(), $temperature->getUid(), $temperature->getName(), $temperature->getTemperature()));
            }
        }
        return true;
    }

}