<?php

namespace CollectionBundle\Command;

use CollectionBundle\Model\Collection;
use CollectionBundle\Model\CollectionType;
use CollectionBundle\Model\CollectionTypeQuery;
use StoreBundle\Model\StoreQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DBaseCommand
 * @package CollectionBundle\Command
 */
class DBaseCommand extends ContainerAwareCommand
{

    protected $collectionTypeArr = [];

    protected function configure()
    {
        $this->setName('dbase:collection:initialize')
            ->setDescription('Initialize standard lists for the collection database');
    }

    protected function configCollectionStyleList()
    {
        $this->collectionTypeArr[CollectionType::TYPE_MAINTENANCE_ID]['Name'] = CollectionType::TYPE_MAINTENANCE_NAME;
        $this->collectionTypeArr[CollectionType::TYPE_MAINTENANCE_ID]['Icon'] = CollectionType::TYPE_MAINTENANCE_ICON;
        $this->collectionTypeArr[CollectionType::TYPE_MAINTENANCE_ID]['Style'] = CollectionType::TYPE_MAINTENANCE_STYLE;
        $this->collectionTypeArr[CollectionType::TYPE_INVENTORY_ID]['Name'] = CollectionType::TYPE_INVENTORY_NAME;
        $this->collectionTypeArr[CollectionType::TYPE_INVENTORY_ID]['Icon'] = CollectionType::TYPE_INVENTORY_ICON;
        $this->collectionTypeArr[CollectionType::TYPE_INVENTORY_ID]['Style'] = CollectionType::TYPE_INVENTORY_STYLE;
        $this->collectionTypeArr[CollectionType::TYPE_BEER_TECH_ID]['Name'] = CollectionType::TYPE_BEER_TECH_NAME;
        $this->collectionTypeArr[CollectionType::TYPE_BEER_TECH_ID]['Icon'] = CollectionType::TYPE_BEER_TECH_ICON;
        $this->collectionTypeArr[CollectionType::TYPE_BEER_TECH_ID]['Style'] = CollectionType::TYPE_BEER_TECH_STYLE;
        $this->collectionTypeArr[CollectionType::TYPE_WINE_TECH_ID]['Name'] = CollectionType::TYPE_WINE_TECH_NAME;
        $this->collectionTypeArr[CollectionType::TYPE_WINE_TECH_ID]['Icon'] = CollectionType::TYPE_WINE_TECH_ICON;
        $this->collectionTypeArr[CollectionType::TYPE_WINE_TECH_ID]['Style'] = CollectionType::TYPE_WINE_TECH_STYLE;
        $this->collectionTypeArr[CollectionType::TYPE_POSTMIX_TECH_ID]['Name'] = CollectionType::TYPE_POSTMIX_TECH_NAME;
        $this->collectionTypeArr[CollectionType::TYPE_POSTMIX_TECH_ID]['Icon'] = CollectionType::TYPE_POSTMIX_TECH_ICON;
        $this->collectionTypeArr[CollectionType::TYPE_POSTMIX_TECH_ID]['Style'] = CollectionType::TYPE_POSTMIX_TECH_STYLE;
        $this->collectionTypeArr[CollectionType::TYPE_COOL_TECH_ID]['Name'] = CollectionType::TYPE_COOL_TECH_NAME;
        $this->collectionTypeArr[CollectionType::TYPE_COOL_TECH_ID]['Icon'] = CollectionType::TYPE_COOL_TECH_ICON;
        $this->collectionTypeArr[CollectionType::TYPE_COOL_TECH_ID]['Style'] = CollectionType::TYPE_COOL_TECH_STYLE;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start database init of collection.");
        $this->configCollectionStyleList();
        $this->generateCollectionTypeList($output);
        $output->writeln("Ready.");
    }

    protected function generateCollectionTypeList(OutputInterface $output)
    {
        foreach ($this->collectionTypeArr as $k => $type) {
            $_type = CollectionTypeQuery::create()
                ->findOneById($k);
            if (empty($_type)) {
                $_type = new CollectionType();
                $_type
                    ->setName($type['Name'])
                    ->setIcon($type['Icon'])
                    ->setStyle($type['Style'])
                    ->save();
                $output->writeln(sprintf('Created collection type: %s', $type['Name']));
            }
        }
    }
}

