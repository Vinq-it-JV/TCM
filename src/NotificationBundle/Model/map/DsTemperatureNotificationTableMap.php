<?php

namespace NotificationBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'ds_temperature_notification' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.NotificationBundle.Model.map
 */
class DsTemperatureNotificationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.NotificationBundle.Model.map.DsTemperatureNotificationTableMap';

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
        $this->setName('ds_temperature_notification');
        $this->setPhpName('DsTemperatureNotification');
        $this->setClassname('NotificationBundle\\Model\\DsTemperatureNotification');
        $this->setPackage('src.NotificationBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('sensor', 'Sensor', 'INTEGER', 'ds_temperature_sensor', 'id', false, null, null);
        $this->addColumn('temperature', 'Temperature', 'VARCHAR', false, 10, '0');
        $this->addColumn('reason', 'Reason', 'INTEGER', false, null, 0);
        $this->addColumn('is_notified', 'IsNotified', 'BOOLEAN', false, 1, false);
        $this->addColumn('is_handled', 'IsHandled', 'BOOLEAN', false, 1, false);
        $this->addForeignKey('handled_by', 'HandledBy', 'INTEGER', 'user', 'id', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'UserBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('handled_by' => 'id', ), null, null);
        $this->addRelation('DsTemperatureSensor', 'DeviceBundle\\Model\\DsTemperatureSensor', RelationMap::MANY_TO_ONE, array('sensor' => 'id', ), null, null);
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

} // DsTemperatureNotificationTableMap
