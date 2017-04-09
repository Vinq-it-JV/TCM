<?php

namespace UserBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'user' table.
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
class UserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.UserBundle.Model.map.UserTableMap';

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
        $this->setName('user');
        $this->setPhpName('User');
        $this->setClassname('UserBundle\\Model\\User');
        $this->setPackage('src.UserBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('username', 'Username', 'VARCHAR', false, 255, null);
        $this->addColumn('firstname', 'Firstname', 'VARCHAR', false, 255, null);
        $this->addColumn('middlename', 'Middlename', 'VARCHAR', false, 255, null);
        $this->addColumn('lastname', 'Lastname', 'VARCHAR', false, 255, null);
        $this->addForeignKey('gender', 'Gender', 'INTEGER', 'user_gender', 'id', false, null, null);
        $this->addForeignKey('title', 'Title', 'INTEGER', 'user_title', 'id', false, null, null);
        $this->addColumn('birth_date', 'BirthDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('password', 'Password', 'VARCHAR', false, 255, null);
        $this->addColumn('secret', 'Secret', 'VARCHAR', false, 255, null);
        $this->addColumn('logins', 'Logins', 'INTEGER', false, null, 3);
        $this->addForeignKey('country', 'Country', 'INTEGER', 'countries', 'id', false, null, null);
        $this->addForeignKey('language', 'Language', 'INTEGER', 'countries', 'id', false, null, null);
        $this->addColumn('is_enabled', 'IsEnabled', 'BOOLEAN', false, 1, true);
        $this->addColumn('is_external', 'IsExternal', 'BOOLEAN', false, 1, false);
        $this->addColumn('is_locked', 'IsLocked', 'BOOLEAN', false, 1, false);
        $this->addColumn('is_expired', 'IsExpired', 'BOOLEAN', false, 1, false);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('UserGender', 'UserBundle\\Model\\UserGender', RelationMap::MANY_TO_ONE, array('gender' => 'id', ), null, null);
        $this->addRelation('UserTitle', 'UserBundle\\Model\\UserTitle', RelationMap::MANY_TO_ONE, array('title' => 'id', ), null, null);
        $this->addRelation('CountriesRelatedByCountry', 'UserBundle\\Model\\Countries', RelationMap::MANY_TO_ONE, array('country' => 'id', ), null, null);
        $this->addRelation('CountriesRelatedByLanguage', 'UserBundle\\Model\\Countries', RelationMap::MANY_TO_ONE, array('language' => 'id', ), null, null);
        $this->addRelation('CompanyContact', 'CompanyBundle\\Model\\CompanyContact', RelationMap::ONE_TO_MANY, array('id' => 'contact_id', ), null, null, 'CompanyContacts');
        $this->addRelation('CompanyInformant', 'CompanyBundle\\Model\\CompanyInformant', RelationMap::ONE_TO_MANY, array('id' => 'informant_id', ), null, null, 'CompanyInformants');
        $this->addRelation('CompanyOwner', 'CompanyBundle\\Model\\CompanyOwner', RelationMap::ONE_TO_MANY, array('id' => 'owner_id', ), null, null, 'CompanyOwners');
        $this->addRelation('StoreContact', 'StoreBundle\\Model\\StoreContact', RelationMap::ONE_TO_MANY, array('id' => 'contact_id', ), null, null, 'StoreContacts');
        $this->addRelation('StoreInformant', 'StoreBundle\\Model\\StoreInformant', RelationMap::ONE_TO_MANY, array('id' => 'informant_id', ), null, null, 'StoreInformants');
        $this->addRelation('StoreOwner', 'StoreBundle\\Model\\StoreOwner', RelationMap::ONE_TO_MANY, array('id' => 'owner_id', ), null, null, 'StoreOwners');
        $this->addRelation('UserRole', 'UserBundle\\Model\\UserRole', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', null, 'UserRoles');
        $this->addRelation('UserAddress', 'UserBundle\\Model\\UserAddress', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', null, 'UserAddresses');
        $this->addRelation('UserEmail', 'UserBundle\\Model\\UserEmail', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', null, 'UserEmails');
        $this->addRelation('UserPhone', 'UserBundle\\Model\\UserPhone', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', null, 'UserPhones');
        $this->addRelation('ContactCompany', 'CompanyBundle\\Model\\Company', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'ContactCompanies');
        $this->addRelation('InformantCompany', 'CompanyBundle\\Model\\Company', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'InformantCompanies');
        $this->addRelation('OwnerCompany', 'CompanyBundle\\Model\\Company', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'OwnerCompanies');
        $this->addRelation('ContactStore', 'StoreBundle\\Model\\Store', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'ContactStores');
        $this->addRelation('InformantStore', 'StoreBundle\\Model\\Store', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'InformantStores');
        $this->addRelation('OwnerStore', 'StoreBundle\\Model\\Store', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'OwnerStores');
        $this->addRelation('Role', 'UserBundle\\Model\\Role', RelationMap::MANY_TO_MANY, array(), null, null, 'Roles');
        $this->addRelation('Address', 'UserBundle\\Model\\Address', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Addresses');
        $this->addRelation('Email', 'UserBundle\\Model\\Email', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Emails');
        $this->addRelation('Phone', 'UserBundle\\Model\\Phone', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Phones');
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

} // UserTableMap
