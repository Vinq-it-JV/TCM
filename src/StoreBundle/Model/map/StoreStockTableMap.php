<?php

namespace StoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'store_stock' table.
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
class StoreStockTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.StoreBundle.Model.map.StoreStockTableMap';

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
        $this->setName('store_stock');
        $this->setPhpName('StoreStock');
        $this->setClassname('StoreBundle\\Model\\StoreStock');
        $this->setPackage('src.StoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('collection_type', 'CollectionType', 'INTEGER', false, null, null);
        $this->addForeignKey('store_id', 'StoreId', 'INTEGER', 'store', 'id', false, null, null);
        $this->addColumn('code', 'Code', 'VARCHAR', false, 32, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('description', 'Description', 'VARCHAR', false, 255, null);
        $this->addForeignKey('image_id', 'ImageId', 'INTEGER', 'stock_image', 'id', false, null, null);
        $this->addColumn('stock_value', 'StockValue', 'FLOAT', false, null, null);
        $this->addColumn('stock_min', 'StockMin', 'FLOAT', false, null, null);
        $this->addColumn('stock_max', 'StockMax', 'FLOAT', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Store', 'StoreBundle\\Model\\Store', RelationMap::MANY_TO_ONE, array('store_id' => 'id', ), null, null);
        $this->addRelation('StockImage', 'StoreBundle\\Model\\StockImage', RelationMap::MANY_TO_ONE, array('image_id' => 'id', ), null, null);
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

} // StoreStockTableMap
