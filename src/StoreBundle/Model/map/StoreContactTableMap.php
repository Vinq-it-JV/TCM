<?php

namespace StoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'store_contact' table.
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
class StoreContactTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.StoreBundle.Model.map.StoreContactTableMap';

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
        $this->setName('store_contact');
        $this->setPhpName('StoreContact');
        $this->setClassname('StoreBundle\\Model\\StoreContact');
        $this->setPackage('src.StoreBundle.Model');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('store_id', 'StoreId', 'INTEGER' , 'store', 'id', true, null, null);
        $this->addForeignPrimaryKey('contact_id', 'ContactId', 'INTEGER' , 'user', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ContactStore', 'StoreBundle\\Model\\Store', RelationMap::MANY_TO_ONE, array('store_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Contact', 'UserBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('contact_id' => 'id', ), null, null);
    } // buildRelations()

} // StoreContactTableMap
