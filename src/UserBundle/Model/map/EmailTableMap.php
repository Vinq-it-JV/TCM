<?php

namespace UserBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'email' table.
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
class EmailTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.UserBundle.Model.map.EmailTableMap';

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
        $this->setName('email');
        $this->setPhpName('Email');
        $this->setClassname('UserBundle\\Model\\Email');
        $this->setPackage('src.UserBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('primary', 'Primary', 'BOOLEAN', false, 1, false);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('description', 'Description', 'VARCHAR', false, 255, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CompanyEmail', 'CompanyBundle\\Model\\CompanyEmail', RelationMap::ONE_TO_MANY, array('id' => 'email_id', ), 'CASCADE', null, 'CompanyEmails');
        $this->addRelation('StoreEmail', 'StoreBundle\\Model\\StoreEmail', RelationMap::ONE_TO_MANY, array('id' => 'email_id', ), 'CASCADE', null, 'StoreEmails');
        $this->addRelation('UserEmail', 'UserBundle\\Model\\UserEmail', RelationMap::ONE_TO_MANY, array('id' => 'email_id', ), 'CASCADE', null, 'UserEmails');
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

} // EmailTableMap
