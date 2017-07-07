<?php

namespace DataCollectorBundle\Command;

use DataCollectorBundle\Model\CollectorLogQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use DeviceBundle\Model\CbInput;
use DeviceBundle\Model\DsTemperatureSensor;
use DeviceBundle\Model\CbInputQuery;
use DeviceBundle\Model\DsTemperatureSensorQuery;


/**
 * Class CheckLogCommand
 * @package DataCollectorBundle\Command
 */
class CheckLogCommand extends ContainerAwareCommand
{
    private $options;

    /**
     * Function configuration
     */
    protected function configure()
    {
        $this->setName('check-collector-log')
            ->setDescription('Command for checking collector log');
    }

    /**
     * Execute function
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return boolean
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->backupLog($output);
        return true;
    }

    /**
     * Backup old log files
     * @param OutputInterface $output
     */
    protected function backupLog(OutputInterface $output)
    {
        $output->writeln('checking for log data backup.');

        $date = new \DateTime('today midnight');
        $date->modify('-5 days');

        $logs = CollectorLogQuery::create()
            ->where('collector_log.created_at < ?', $date)
            ->delete();

        // Instead of using delete, we need to use find, and store all data into a file.
        // For now we just delete all logs older than 5 days.
    }
}