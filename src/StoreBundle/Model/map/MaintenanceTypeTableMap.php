<?php

namespace StoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'maintenance_type' table.
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
class MaintenanceTypeTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.StoreBundle.Model.map.MaintenanceTypeTableMap';

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
        $this->setName('maintenance_type');
        $this->setPhpName('MaintenanceType');
        $this->setClassname('StoreBundle\\Model\\MaintenanceType');
        $this->setPackage('src.StoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('description', 'Description', 'VARCHAR', false, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('StoreMaintenanceLog', 'StoreBundle\\Model\\StoreMaintenanceLog', RelationMap::ONE_TO_MANY, array('id' => 'type', ), null, null, 'StoreMaintenanceLogs');
    } // buildRelations()

} // MaintenanceTypeTableMap
