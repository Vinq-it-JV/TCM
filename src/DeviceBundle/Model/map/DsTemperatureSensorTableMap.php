<?php

namespace DeviceBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'ds_temperature_sensor' table.
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
class DsTemperatureSensorTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DeviceBundle.Model.map.DsTemperatureSensorTableMap';

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
        $this->setName('ds_temperature_sensor');
        $this->setPhpName('DsTemperatureSensor');
        $this->setClassname('DeviceBundle\\Model\\DsTemperatureSensor');
        $this->setPackage('src.DeviceBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('uid', 'Uid', 'VARCHAR', false, 32, null);
        $this->addForeignKey('group', 'Group', 'INTEGER', 'device_group', 'id', false, null, null);
        $this->addForeignKey('controller', 'Controller', 'INTEGER', 'controller_box', 'id', false, null, null);
        $this->addForeignKey('main_store', 'MainStore', 'INTEGER', 'store', 'id', false, null, null);
        $this->addColumn('output_number', 'OutputNumber', 'INTEGER', false, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, 'Temperature');
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('state', 'State', 'INTEGER', false, null, 0);
        $this->addColumn('low_limit', 'LowLimit', 'VARCHAR', false, 10, '0');
        $this->addColumn('temperature', 'Temperature', 'VARCHAR', false, 10, '0');
        $this->addColumn('high_limit', 'HighLimit', 'VARCHAR', false, 10, '30');
        $this->addColumn('position', 'Position', 'INTEGER', false, null, 0);
        $this->addColumn('data_collected_at', 'DataCollectedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('notify_after', 'NotifyAfter', 'INTEGER', false, null, 0);
        $this->addColumn('notify_started_at', 'NotifyStartedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('is_enabled', 'IsEnabled', 'BOOLEAN', false, 1, true);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Store', 'StoreBundle\\Model\\Store', RelationMap::MANY_TO_ONE, array('main_store' => 'id', ), null, null);
        $this->addRelation('DeviceGroup', 'DeviceBundle\\Model\\DeviceGroup', RelationMap::MANY_TO_ONE, array('group' => 'id', ), null, null);
        $this->addRelation('ControllerBox', 'DeviceBundle\\Model\\ControllerBox', RelationMap::MANY_TO_ONE, array('controller' => 'id', ), null, null);
        $this->addRelation('DsTemperatureSensorLog', 'DeviceBundle\\Model\\DsTemperatureSensorLog', RelationMap::ONE_TO_MANY, array('id' => 'sensor', ), null, null, 'DsTemperatureSensorLogs');
        $this->addRelation('DeviceCopy', 'DeviceBundle\\Model\\DeviceCopy', RelationMap::ONE_TO_MANY, array('id' => 'copy_of_sensor', ), null, null, 'DeviceCopies');
        $this->addRelation('DsTemperatureNotification', 'NotificationBundle\\Model\\DsTemperatureNotification', RelationMap::ONE_TO_MANY, array('id' => 'sensor', ), null, null, 'DsTemperatureNotifications');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
        );
    } // getBehaviors()

} // DsTemperatureSensorTableMap
