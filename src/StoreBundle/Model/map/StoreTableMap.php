<?php

namespace StoreBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'store' table.
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
class StoreTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.StoreBundle.Model.map.StoreTableMap';

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
        $this->setName('store');
        $this->setPhpName('Store');
        $this->setClassname('StoreBundle\\Model\\Store');
        $this->setPackage('src.StoreBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('main_company', 'MainCompany', 'INTEGER', 'company', 'id', false, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('type', 'Type', 'INTEGER', 'store_type', 'id', false, null, null);
        $this->addColumn('code', 'Code', 'VARCHAR', false, 25, null);
        $this->addColumn('website', 'Website', 'VARCHAR', false, 255, null);
        $this->addForeignKey('region', 'Region', 'INTEGER', 'regions', 'id', false, null, null);
        $this->addColumn('remarks', 'Remarks', 'LONGVARCHAR', false, null, null);
        $this->addColumn('payment_method', 'PaymentMethod', 'INTEGER', false, null, null);
        $this->addColumn('bank_account_number', 'BankAccountNumber', 'VARCHAR', false, 50, null);
        $this->addColumn('vat_number', 'VatNumber', 'VARCHAR', false, 255, null);
        $this->addColumn('coc_number', 'CocNumber', 'VARCHAR', false, 255, null);
        $this->addColumn('is_enabled', 'IsEnabled', 'BOOLEAN', false, 1, true);
        $this->addColumn('is_deleted', 'IsDeleted', 'BOOLEAN', false, 1, false);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Company', 'CompanyBundle\\Model\\Company', RelationMap::MANY_TO_ONE, array('main_company' => 'id', ), null, null);
        $this->addRelation('StoreType', 'StoreBundle\\Model\\StoreType', RelationMap::MANY_TO_ONE, array('type' => 'id', ), null, null);
        $this->addRelation('Regions', 'CompanyBundle\\Model\\Regions', RelationMap::MANY_TO_ONE, array('region' => 'id', ), null, null);
        $this->addRelation('StoreAddress', 'StoreBundle\\Model\\StoreAddress', RelationMap::ONE_TO_MANY, array('id' => 'store_id', ), 'CASCADE', null, 'StoreAddresses');
        $this->addRelation('StoreEmail', 'StoreBundle\\Model\\StoreEmail', RelationMap::ONE_TO_MANY, array('id' => 'store_id', ), 'CASCADE', null, 'StoreEmails');
        $this->addRelation('StorePhone', 'StoreBundle\\Model\\StorePhone', RelationMap::ONE_TO_MANY, array('id' => 'store_id', ), 'CASCADE', null, 'StorePhones');
        $this->addRelation('StoreContact', 'StoreBundle\\Model\\StoreContact', RelationMap::ONE_TO_MANY, array('id' => 'store_id', ), 'CASCADE', null, 'StoreContacts');
        $this->addRelation('StoreInformant', 'StoreBundle\\Model\\StoreInformant', RelationMap::ONE_TO_MANY, array('id' => 'store_id', ), 'CASCADE', null, 'StoreInformants');
        $this->addRelation('StoreOwner', 'StoreBundle\\Model\\StoreOwner', RelationMap::ONE_TO_MANY, array('id' => 'store_id', ), 'CASCADE', null, 'StoreOwners');
        $this->addRelation('Address', 'UserBundle\\Model\\Address', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Addresses');
        $this->addRelation('Email', 'UserBundle\\Model\\Email', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Emails');
        $this->addRelation('Phone', 'UserBundle\\Model\\Phone', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Phones');
        $this->addRelation('Contact', 'UserBundle\\Model\\User', RelationMap::MANY_TO_MANY, array(), null, null, 'Contacts');
        $this->addRelation('Informant', 'UserBundle\\Model\\User', RelationMap::MANY_TO_MANY, array(), null, null, 'Informants');
        $this->addRelation('Owner', 'UserBundle\\Model\\User', RelationMap::MANY_TO_MANY, array(), null, null, 'Owners');
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

} // StoreTableMap
