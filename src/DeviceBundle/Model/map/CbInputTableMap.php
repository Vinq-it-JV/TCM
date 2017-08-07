<?php

namespace DeviceBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'cb_input' table.
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
class CbInputTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DeviceBundle.Model.map.CbInputTableMap';

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
        $this->setName('cb_input');
        $this->setPhpName('CbInput');
        $this->setClassname('DeviceBundle\\Model\\CbInput');
        $this->setPackage('src.DeviceBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('uid', 'Uid', 'VARCHAR', false, 32, null);
        $this->addColumn('input_number', 'InputNumber', 'INTEGER', false, null, null);
        $this->addForeignKey('group', 'Group', 'INTEGER', 'device_group', 'id', false, null, null);
        $this->addForeignKey('controller', 'Controller', 'INTEGER', 'controller_box', 'id', false, null, null);
        $this->addForeignKey('main_store', 'MainStore', 'INTEGER', 'store', 'id', false, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, 'Input');
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('state', 'State', 'INTEGER', false, null, 0);
        $this->addColumn('switch_when', 'SwitchWhen', 'BOOLEAN', false, 1, true);
        $this->addColumn('switch_state', 'SwitchState', 'BOOLEAN', false, 1, false);
        $this->addColumn('position', 'Position', 'INTEGER', false, null, 0);
        $this->addColumn('data_collected_at', 'DataCollectedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('notify_after', 'NotifyAfter', 'INTEGER', false, null, 0);
        $this->addColumn('notify_started_at', 'NotifyStartedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('notification', 'Notification', 'INTEGER', false, null, null);
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
        $this->addRelation('ControllerBox', 'DeviceBundle\\Model\\ControllerBox', RelationMap::MANY_TO_ONE, array('controller' => 'id', ), null, null);
        $this->addRelation('DeviceGroup', 'DeviceBundle\\Model\\DeviceGroup', RelationMap::MANY_TO_ONE, array('group' => 'id', ), null, null);
        $this->addRelation('CbInputLog', 'DeviceBundle\\Model\\CbInputLog', RelationMap::ONE_TO_MANY, array('id' => 'input', ), null, null, 'CbInputLogs');
        $this->addRelation('DeviceCopy', 'DeviceBundle\\Model\\DeviceCopy', RelationMap::ONE_TO_MANY, array('id' => 'copy_of_input', ), null, null, 'DeviceCopies');
        $this->addRelation('CbInputNotification', 'NotificationBundle\\Model\\CbInputNotification', RelationMap::ONE_TO_MANY, array('id' => 'sensor', ), null, null, 'CbInputNotifications');
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

} // CbInputTableMap
