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
        $this->addColumn('lastname', 'Lastname', 'VARCHAR', false, 255, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('password', 'Password', 'VARCHAR', false, 255, null);
        $this->addColumn('secret', 'Secret', 'VARCHAR', false, 255, null);
        $this->addColumn('logins', 'Logins', 'INTEGER', false, null, 3);
        $this->addColumn('language', 'Language', 'VARCHAR', false, 5, 'en');
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
        $this->addRelation('UserRole', 'UserBundle\\Model\\UserRole', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', null, 'UserRoles');
        $this->addRelation('Role', 'UserBundle\\Model\\Role', RelationMap::MANY_TO_MANY, array(), null, null, 'Roles');
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
