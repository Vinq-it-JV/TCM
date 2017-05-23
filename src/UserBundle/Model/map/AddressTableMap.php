<?php

namespace UserBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'address' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.UserBundle.Model.map
 */
class AddressTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.UserBundle.Model.map.AddressTableMap';

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
        $this->setName('address');
        $this->setPhpName('Address');
        $this->setClassname('UserBundle\\Model\\Address');
        $this->setPackage('src.UserBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('type', 'Type', 'INTEGER', false, null, null);
        $this->addColumn('street_name', 'StreetName', 'VARCHAR', false, 255, null);
        $this->addColumn('house_number', 'HouseNumber', 'VARCHAR', false, 25, null);
        $this->addColumn('extra_info', 'ExtraInfo', 'VARCHAR', false, 255, null);
        $this->addColumn('postal_code', 'PostalCode', 'VARCHAR', false, 25, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 255, null);
        $this->addForeignKey('country', 'Country', 'INTEGER', 'countries', 'id', false, null, null);
        $this->addColumn('map_coordinates', 'MapCoordinates', 'VARCHAR', false, 255, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Countries', 'UserBundle\\Model\\Countries', RelationMap::MANY_TO_ONE, array('country' => 'id', ), null, null);
        $this->addRelation('CompanyAddress', 'CompanyBundle\\Model\\CompanyAddress', RelationMap::ONE_TO_MANY, array('id' => 'address_id', ), 'CASCADE', null, 'CompanyAddresses');
        $this->addRelation('StoreAddress', 'StoreBundle\\Model\\StoreAddress', RelationMap::ONE_TO_MANY, array('id' => 'address_id', ), 'CASCADE', null, 'StoreAddresses');
        $this->addRelation('UserAddress', 'UserBundle\\Model\\UserAddress', RelationMap::ONE_TO_MANY, array('id' => 'address_id', ), 'CASCADE', null, 'UserAddresses');
        $this->addRelation('Company', 'CompanyBundle\\Model\\Company', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Companies');
        $this->addRelation('Store', 'StoreBundle\\Model\\Store', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Stores');
        $this->addRelation('User', 'UserBundle\\Model\\User', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Users');
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

} // AddressTableMap
