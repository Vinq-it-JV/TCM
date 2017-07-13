<?php

namespace DeviceBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'cb_input_log' table.
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
class CbInputLogTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.DeviceBundle.Model.map.CbInputLogTableMap';

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
        $this->setName('cb_input_log');
        $this->setPhpName('CbInputLog');
        $this->setClassname('DeviceBundle\\Model\\CbInputLog');
        $this->setPackage('src.DeviceBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('input', 'Input', 'INTEGER', 'cb_input', 'id', false, null, null);
        $this->addColumn('switch_state', 'SwitchState', 'BOOLEAN', false, 1, false);
        $this->addColumn('switch_when', 'SwitchWhen', 'BOOLEAN', false, 1, true);
        $this->addColumn('raw_data', 'RawData', 'VARCHAR', false, 10, '0');
        $this->addColumn('data_collected_at', 'DataCollectedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CbInput', 'DeviceBundle\\Model\\CbInput', RelationMap::MANY_TO_ONE, array('input' => 'id', ), null, null);
    } // buildRelations()

} // CbInputLogTableMap
