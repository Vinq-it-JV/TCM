<?php

namespace CompanyBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'company_phone' table.
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
class CompanyPhoneTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CompanyBundle.Model.map.CompanyPhoneTableMap';

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
        $this->setName('company_phone');
        $this->setPhpName('CompanyPhone');
        $this->setClassname('CompanyBundle\\Model\\CompanyPhone');
        $this->setPackage('src.CompanyBundle.Model');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('company_id', 'CompanyId', 'INTEGER' , 'company', 'id', true, null, null);
        $this->addForeignPrimaryKey('phone_id', 'PhoneId', 'INTEGER' , 'phone', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Company', 'CompanyBundle\\Model\\Company', RelationMap::MANY_TO_ONE, array('company_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Phone', 'UserBundle\\Model\\Phone', RelationMap::MANY_TO_ONE, array('phone_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // CompanyPhoneTableMap
