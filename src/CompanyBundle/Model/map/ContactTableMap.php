<?php

namespace CompanyBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'contact' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.CompanyBundle.Model.map
 */
class ContactTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CompanyBundle.Model.map.ContactTableMap';

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
        $this->setName('contact');
        $this->setPhpName('Contact');
        $this->setClassname('CompanyBundle\\Model\\Contact');
        $this->setPackage('src.CompanyBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'user', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'UserBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), null, null);
        $this->addRelation('CompanyContact', 'CompanyBundle\\Model\\CompanyContact', RelationMap::ONE_TO_MANY, array('id' => 'contact_id', ), null, null, 'CompanyContacts');
        $this->addRelation('Company', 'CompanyBundle\\Model\\Company', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Companies');
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
            'delegate' =>  array (
  'to' => 'user',
),
        );
    } // getBehaviors()

} // ContactTableMap
