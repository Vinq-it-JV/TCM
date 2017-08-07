<?php

namespace DeviceBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'device_copy' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.DeviceBundle.Model.map
 */
class DeviceCopyTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DeviceBundle.Model.map.DeviceCopyTableMap';

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
        $this->setName('device_copy');
        $this->setPhpName('DeviceCopy');
        $this->setClassname('DeviceBundle\\Model\\DeviceCopy');
        $this->setPackage('src.DeviceBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, '');
        $this->addForeignKey('copy_of_input', 'CopyOfInput', 'INTEGER', 'cb_input', 'id', false, null, null);
        $this->addForeignKey('copy_of_sensor', 'CopyOfSensor', 'INTEGER', 'ds_temperature_sensor', 'id', false, null, null);
        $this->addForeignKey('group', 'Group', 'INTEGER', 'device_group', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CbInput', 'DeviceBundle\\Model\\CbInput', RelationMap::MANY_TO_ONE, array('copy_of_input' => 'id', ), null, null);
        $this->addRelation('DsTemperatureSensor', 'DeviceBundle\\Model\\DsTemperatureSensor', RelationMap::MANY_TO_ONE, array('copy_of_sensor' => 'id', ), null, null);
        $this->addRelation('DeviceGroup', 'DeviceBundle\\Model\\DeviceGroup', RelationMap::MANY_TO_ONE, array('group' => 'id', ), null, null);
    } // buildRelations()

} // DeviceCopyTableMap
