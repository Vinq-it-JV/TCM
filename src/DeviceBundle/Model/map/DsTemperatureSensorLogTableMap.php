<?php

namespace DeviceBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'ds_temperature_sensor_log' table.
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
class DsTemperatureSensorLogTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DeviceBundle.Model.map.DsTemperatureSensorLogTableMap';

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
        $this->setName('ds_temperature_sensor_log');
        $this->setPhpName('DsTemperatureSensorLog');
        $this->setClassname('DeviceBundle\\Model\\DsTemperatureSensorLog');
        $this->setPackage('src.DeviceBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('sensor', 'Sensor', 'INTEGER', 'ds_temperature_sensor', 'id', false, null, null);
        $this->addColumn('low_limit', 'LowLimit', 'VARCHAR', false, 10, '0');
        $this->addColumn('temperature', 'Temperature', 'VARCHAR', false, 10, '0');
        $this->addColumn('high_limit', 'HighLimit', 'VARCHAR', false, 10, '30');
        $this->addColumn('raw_data', 'RawData', 'VARCHAR', false, 10, '0');
        $this->addColumn('data_collected_at', 'DataCollectedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('DsTemperatureSensor', 'DeviceBundle\\Model\\DsTemperatureSensor', RelationMap::MANY_TO_ONE, array('sensor' => 'id', ), null, null);
    } // buildRelations()

} // DsTemperatureSensorLogTableMap
