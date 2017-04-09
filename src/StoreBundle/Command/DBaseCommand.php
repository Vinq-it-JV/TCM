<?php

namespace StoreBundle\Command;

use CompanyBundle\Model\CompanyType;
use CompanyBundle\Model\CompanyTypeQuery;
use CompanyBundle\Model\PaymentMethod;
use CompanyBundle\Model\PaymentMethodQuery;
use StoreBundle\Model\StoreType;
use StoreBundle\Model\StoreTypeQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DBaseCommand
 * @package StoreBundle\Command
 */
class DBaseCommand extends ContainerAwareCommand
{

    protected $storeTypeArr = [];

    protected function configure()
    {
        $this->setName('dbase:store:initialize')
            ->setDescription('Initialize standard lists for the store database');
    }

    protected function configStoreTypeList()
    {
        $this->storeTypeArr['BAR']['Name'] = StoreType::BAR_NAME;
        $this->storeTypeArr['BAR']['Description'] = StoreType::BAR_DESCRIPTION;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start database init of store.");
        $this->configStoreTypeList();
        $this->generateStoreTypeList($output);
        $output->writeln("Ready.");
    }

    protected function generateStoreTypeList(OutputInterface $output)
    {
        foreach ($this->storeTypeArr as $k => $type) {
            $_type = StoreTypeQuery::create()
                ->findOneByName($type['Name']);
            if (empty($_type)) {
                $_type = new StoreType();
                $_type
                    ->setName($type['Name'])
                    ->setDescription($type['Description'])
                    ->save();
                $output->writeln(sprintf('Created store type: %s', $k));
            }
        }
    }
}

