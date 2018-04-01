<?php

namespace StoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'store_maintenance_log' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.StoreBundle.Model.map
 */
class StoreMaintenanceLogTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.StoreBundle.Model.map.StoreMaintenanceLogTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('store_maintenance_log');
        $this->setPhpName('StoreMaintenanceLog');
        $this->setClassname('StoreBundle\\Model\\StoreMaintenanceLog');
        $this->setPackage('src.StoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('type', 'Type', 'INTEGER', 'maintenance_type', 'id', false, null, null);
        $this->addForeignKey('collection_id', 'CollectionId', 'INTEGER', 'collection', 'id', false, null, null);
        $this->addForeignKey('maintenance_store', 'MaintenanceStore', 'INTEGER', 'store', 'id', false, null, null);
        $this->addForeignKey('maintenance_by', 'MaintenanceBy', 'INTEGER', 'user', 'id', false, null, null);
        $this->addColumn('maintenance_started_at', 'MaintenanceStartedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('maintenance_stopped_at', 'MaintenanceStoppedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('MaintenanceType', 'StoreBundle\\Model\\MaintenanceType', RelationMap::MANY_TO_ONE, array('type' => 'id', ), null, null);
        $this->addRelation('Collection', 'CollectionBundle\\Model\\Collection', RelationMap::MANY_TO_ONE, array('collection_id' => 'id', ), null, null);
        $this->addRelation('Store', 'StoreBundle\\Model\\Store', RelationMap::MANY_TO_ONE, array('maintenance_store' => 'id', ), null, null);
        $this->addRelation('User', 'UserBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('maintenance_by' => 'id', ), null, null);
    } // buildRelations()

} // StoreMaintenanceLogTableMap
