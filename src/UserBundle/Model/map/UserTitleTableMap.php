<?php

namespace UserBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'user_title' table.
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
class UserTitleTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.UserBundle.Model.map.UserTitleTableMap';

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
        $this->setName('user_title');
        $this->setPhpName('UserTitle');
        $this->setClassname('UserBundle\\Model\\UserTitle');
        $this->setPackage('src.UserBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name_short', 'NameShort', 'VARCHAR', false, 25, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'UserBundle\\Model\\User', RelationMap::ONE_TO_MANY, array('id' => 'title', ), null, null, 'Users');
    } // buildRelations()

} // UserTitleTableMap
