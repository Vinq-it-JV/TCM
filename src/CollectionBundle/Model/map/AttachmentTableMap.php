<?php

namespace CollectionBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'attachment' table.
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
class AttachmentTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.CollectionBundle.Model.map.AttachmentTableMap';

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
        $this->setName('attachment');
        $this->setPhpName('Attachment');
        $this->setClassname('CollectionBundle\\Model\\Attachment');
        $this->setPackage('src.CollectionBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('uid', 'Uid', 'VARCHAR', false, 64, null);
        $this->addColumn('type', 'Type', 'INTEGER', false, null, null);
        $this->addColumn('position', 'Position', 'INTEGER', false, null, 0);
        $this->addColumn('original_name', 'OriginalName', 'VARCHAR', false, 255, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('link_url', 'LinkUrl', 'VARCHAR', false, 255, null);
        $this->addColumn('filename', 'Filename', 'VARCHAR', false, 255, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CollectionAttachment', 'CollectionBundle\\Model\\CollectionAttachment', RelationMap::ONE_TO_MANY, array('id' => 'attachment_id', ), null, null, 'CollectionAttachments');
        $this->addRelation('Collection', 'CollectionBundle\\Model\\Collection', RelationMap::MANY_TO_MANY, array(), null, null, 'Collections');
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

} // AttachmentTableMap
