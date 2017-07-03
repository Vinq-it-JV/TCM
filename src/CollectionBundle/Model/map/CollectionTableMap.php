<?php

namespace CollectionBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'collection' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.CollectionBundle.Model.map
 */
class CollectionTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CollectionBundle.Model.map.CollectionTableMap';

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
        $this->setName('collection');
        $this->setPhpName('Collection');
        $this->setClassname('CollectionBundle\\Model\\Collection');
        $this->setPackage('src.CollectionBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('uid', 'Uid', 'VARCHAR', false, 64, null);
        $this->addForeignKey('type', 'Type', 'INTEGER', 'collection_type', 'id', false, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('date', 'Date', 'TIMESTAMP', false, null, null);
        $this->addColumn('is_published', 'IsPublished', 'BOOLEAN', false, 1, true);
        $this->addColumn('is_deleted', 'IsDeleted', 'BOOLEAN', false, 1, false);
        $this->addForeignKey('collection_company', 'CollectionCompany', 'INTEGER', 'company', 'id', false, null, null);
        $this->addForeignKey('collection_store', 'CollectionStore', 'INTEGER', 'store', 'id', false, null, null);
        $this->addForeignKey('created_by', 'CreatedBy', 'INTEGER', 'user', 'id', false, null, null);
        $this->addForeignKey('edited_by', 'EditedBy', 'INTEGER', 'user', 'id', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CollectionType', 'CollectionBundle\\Model\\CollectionType', RelationMap::MANY_TO_ONE, array('type' => 'id', ), null, null);
        $this->addRelation('Company', 'CompanyBundle\\Model\\Company', RelationMap::MANY_TO_ONE, array('collection_company' => 'id', ), null, null);
        $this->addRelation('Store', 'StoreBundle\\Model\\Store', RelationMap::MANY_TO_ONE, array('collection_store' => 'id', ), null, null);
        $this->addRelation('UserRelatedByCreatedBy', 'UserBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('created_by' => 'id', ), null, null);
        $this->addRelation('UserRelatedByEditedBy', 'UserBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('edited_by' => 'id', ), null, null);
        $this->addRelation('CollectionAttachment', 'CollectionBundle\\Model\\CollectionAttachment', RelationMap::ONE_TO_MANY, array('id' => 'collection_id', ), null, null, 'CollectionAttachments');
        $this->addRelation('Attachment', 'CollectionBundle\\Model\\Attachment', RelationMap::MANY_TO_MANY, array(), null, null, 'Attachments');
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

} // CollectionTableMap
