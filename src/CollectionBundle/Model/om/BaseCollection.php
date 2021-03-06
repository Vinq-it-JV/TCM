<?php

namespace CollectionBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use CollectionBundle\Model\Attachment;
use CollectionBundle\Model\AttachmentQuery;
use CollectionBundle\Model\Collection;
use CollectionBundle\Model\CollectionAttachment;
use CollectionBundle\Model\CollectionAttachmentQuery;
use CollectionBundle\Model\CollectionPeer;
use CollectionBundle\Model\CollectionQuery;
use CollectionBundle\Model\CollectionType;
use CollectionBundle\Model\CollectionTypeQuery;
use CompanyBundle\Model\Company;
use CompanyBundle\Model\CompanyQuery;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreMaintenanceLog;
use StoreBundle\Model\StoreMaintenanceLogQuery;
use StoreBundle\Model\StoreQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserQuery;

abstract class BaseCollection extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'CollectionBundle\\Model\\CollectionPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CollectionPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the uid field.
     * @var        string
     */
    protected $uid;

    /**
     * The value for the type field.
     * @var        int
     */
    protected $type;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the date field.
     * @var        string
     */
    protected $date;

    /**
     * The value for the is_published field.
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $is_published;

    /**
     * The value for the is_deleted field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_deleted;

    /**
     * The value for the collection_company field.
     * @var        int
     */
    protected $collection_company;

    /**
     * The value for the collection_store field.
     * @var        int
     */
    protected $collection_store;

    /**
     * The value for the created_by field.
     * @var        int
     */
    protected $created_by;

    /**
     * The value for the edited_by field.
     * @var        int
     */
    protected $edited_by;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        CollectionType
     */
    protected $aCollectionType;

    /**
     * @var        Company
     */
    protected $aCompany;

    /**
     * @var        Store
     */
    protected $aStore;

    /**
     * @var        User
     */
    protected $aUserRelatedByCreatedBy;

    /**
     * @var        User
     */
    protected $aUserRelatedByEditedBy;

    /**
     * @var        PropelObjectCollection|CollectionAttachment[] Collection to store aggregation of CollectionAttachment objects.
     */
    protected $collCollectionAttachments;
    protected $collCollectionAttachmentsPartial;

    /**
     * @var        PropelObjectCollection|StoreMaintenanceLog[] Collection to store aggregation of StoreMaintenanceLog objects.
     */
    protected $collStoreMaintenanceLogs;
    protected $collStoreMaintenanceLogsPartial;

    /**
     * @var        PropelObjectCollection|Attachment[] Collection to store aggregation of Attachment objects.
     */
    protected $collAttachments;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $attachmentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $collectionAttachmentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $storeMaintenanceLogsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->is_published = true;
        $this->is_deleted = false;
    }

    /**
     * Initializes internal state of BaseCollection object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [uid] column value.
     *
     * @return string
     */
    public function getUid()
    {

        return $this->uid;
    }

    /**
     * Get the [type] column value.
     *
     * @return int
     */
    public function getType()
    {

        return $this->type;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {

        return $this->description;
    }

    /**
     * Get the [optionally formatted] temporal [date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDate($format = null)
    {
        if ($this->date === null) {
            return null;
        }

        if ($this->date === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->date, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [is_published] column value.
     *
     * @return boolean
     */
    public function getIsPublished()
    {

        return $this->is_published;
    }

    /**
     * Get the [is_deleted] column value.
     *
     * @return boolean
     */
    public function getIsDeleted()
    {

        return $this->is_deleted;
    }

    /**
     * Get the [collection_company] column value.
     *
     * @return int
     */
    public function getCollectionCompany()
    {

        return $this->collection_company;
    }

    /**
     * Get the [collection_store] column value.
     *
     * @return int
     */
    public function getCollectionStore()
    {

        return $this->collection_store;
    }

    /**
     * Get the [created_by] column value.
     *
     * @return int
     */
    public function getCreatedBy()
    {

        return $this->created_by;
    }

    /**
     * Get the [edited_by] column value.
     *
     * @return int
     */
    public function getEditedBy()
    {

        return $this->edited_by;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->created_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->updated_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Collection The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = CollectionPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [uid] column.
     *
     * @param  string $v new value
     * @return Collection The current object (for fluent API support)
     */
    public function setUid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uid !== $v) {
            $this->uid = $v;
            $this->modifiedColumns[] = CollectionPeer::UID;
        }


        return $this;
    } // setUid()

    /**
     * Set the value of [type] column.
     *
     * @param  int $v new value
     * @return Collection The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[] = CollectionPeer::TYPE;
        }

        if ($this->aCollectionType !== null && $this->aCollectionType->getId() !== $v) {
            $this->aCollectionType = null;
        }


        return $this;
    } // setType()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Collection The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = CollectionPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return Collection The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = CollectionPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Sets the value of [date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Collection The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date !== null || $dt !== null) {
            $currentDateAsString = ($this->date !== null && $tmpDt = new DateTime($this->date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->date = $newDateAsString;
                $this->modifiedColumns[] = CollectionPeer::DATE;
            }
        } // if either are not null


        return $this;
    } // setDate()

    /**
     * Sets the value of the [is_published] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Collection The current object (for fluent API support)
     */
    public function setIsPublished($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_published !== $v) {
            $this->is_published = $v;
            $this->modifiedColumns[] = CollectionPeer::IS_PUBLISHED;
        }


        return $this;
    } // setIsPublished()

    /**
     * Sets the value of the [is_deleted] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Collection The current object (for fluent API support)
     */
    public function setIsDeleted($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_deleted !== $v) {
            $this->is_deleted = $v;
            $this->modifiedColumns[] = CollectionPeer::IS_DELETED;
        }


        return $this;
    } // setIsDeleted()

    /**
     * Set the value of [collection_company] column.
     *
     * @param  int $v new value
     * @return Collection The current object (for fluent API support)
     */
    public function setCollectionCompany($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->collection_company !== $v) {
            $this->collection_company = $v;
            $this->modifiedColumns[] = CollectionPeer::COLLECTION_COMPANY;
        }

        if ($this->aCompany !== null && $this->aCompany->getId() !== $v) {
            $this->aCompany = null;
        }


        return $this;
    } // setCollectionCompany()

    /**
     * Set the value of [collection_store] column.
     *
     * @param  int $v new value
     * @return Collection The current object (for fluent API support)
     */
    public function setCollectionStore($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->collection_store !== $v) {
            $this->collection_store = $v;
            $this->modifiedColumns[] = CollectionPeer::COLLECTION_STORE;
        }

        if ($this->aStore !== null && $this->aStore->getId() !== $v) {
            $this->aStore = null;
        }


        return $this;
    } // setCollectionStore()

    /**
     * Set the value of [created_by] column.
     *
     * @param  int $v new value
     * @return Collection The current object (for fluent API support)
     */
    public function setCreatedBy($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->created_by !== $v) {
            $this->created_by = $v;
            $this->modifiedColumns[] = CollectionPeer::CREATED_BY;
        }

        if ($this->aUserRelatedByCreatedBy !== null && $this->aUserRelatedByCreatedBy->getId() !== $v) {
            $this->aUserRelatedByCreatedBy = null;
        }


        return $this;
    } // setCreatedBy()

    /**
     * Set the value of [edited_by] column.
     *
     * @param  int $v new value
     * @return Collection The current object (for fluent API support)
     */
    public function setEditedBy($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->edited_by !== $v) {
            $this->edited_by = $v;
            $this->modifiedColumns[] = CollectionPeer::EDITED_BY;
        }

        if ($this->aUserRelatedByEditedBy !== null && $this->aUserRelatedByEditedBy->getId() !== $v) {
            $this->aUserRelatedByEditedBy = null;
        }


        return $this;
    } // setEditedBy()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Collection The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = CollectionPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Collection The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = CollectionPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->is_published !== true) {
                return false;
            }

            if ($this->is_deleted !== false) {
                return false;
            }

        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->uid = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->type = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->name = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->description = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->date = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->is_published = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->is_deleted = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
            $this->collection_company = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->collection_store = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->created_by = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->edited_by = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->created_at = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->updated_at = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 14; // 14 = CollectionPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Collection object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aCollectionType !== null && $this->type !== $this->aCollectionType->getId()) {
            $this->aCollectionType = null;
        }
        if ($this->aCompany !== null && $this->collection_company !== $this->aCompany->getId()) {
            $this->aCompany = null;
        }
        if ($this->aStore !== null && $this->collection_store !== $this->aStore->getId()) {
            $this->aStore = null;
        }
        if ($this->aUserRelatedByCreatedBy !== null && $this->created_by !== $this->aUserRelatedByCreatedBy->getId()) {
            $this->aUserRelatedByCreatedBy = null;
        }
        if ($this->aUserRelatedByEditedBy !== null && $this->edited_by !== $this->aUserRelatedByEditedBy->getId()) {
            $this->aUserRelatedByEditedBy = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CollectionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CollectionPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCollectionType = null;
            $this->aCompany = null;
            $this->aStore = null;
            $this->aUserRelatedByCreatedBy = null;
            $this->aUserRelatedByEditedBy = null;
            $this->collCollectionAttachments = null;

            $this->collStoreMaintenanceLogs = null;

            $this->collAttachments = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CollectionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = CollectionQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CollectionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(CollectionPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(CollectionPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(CollectionPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                CollectionPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCollectionType !== null) {
                if ($this->aCollectionType->isModified() || $this->aCollectionType->isNew()) {
                    $affectedRows += $this->aCollectionType->save($con);
                }
                $this->setCollectionType($this->aCollectionType);
            }

            if ($this->aCompany !== null) {
                if ($this->aCompany->isModified() || $this->aCompany->isNew()) {
                    $affectedRows += $this->aCompany->save($con);
                }
                $this->setCompany($this->aCompany);
            }

            if ($this->aStore !== null) {
                if ($this->aStore->isModified() || $this->aStore->isNew()) {
                    $affectedRows += $this->aStore->save($con);
                }
                $this->setStore($this->aStore);
            }

            if ($this->aUserRelatedByCreatedBy !== null) {
                if ($this->aUserRelatedByCreatedBy->isModified() || $this->aUserRelatedByCreatedBy->isNew()) {
                    $affectedRows += $this->aUserRelatedByCreatedBy->save($con);
                }
                $this->setUserRelatedByCreatedBy($this->aUserRelatedByCreatedBy);
            }

            if ($this->aUserRelatedByEditedBy !== null) {
                if ($this->aUserRelatedByEditedBy->isModified() || $this->aUserRelatedByEditedBy->isNew()) {
                    $affectedRows += $this->aUserRelatedByEditedBy->save($con);
                }
                $this->setUserRelatedByEditedBy($this->aUserRelatedByEditedBy);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->attachmentsScheduledForDeletion !== null) {
                if (!$this->attachmentsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->attachmentsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    CollectionAttachmentQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->attachmentsScheduledForDeletion = null;
                }

                foreach ($this->getAttachments() as $attachment) {
                    if ($attachment->isModified()) {
                        $attachment->save($con);
                    }
                }
            } elseif ($this->collAttachments) {
                foreach ($this->collAttachments as $attachment) {
                    if ($attachment->isModified()) {
                        $attachment->save($con);
                    }
                }
            }

            if ($this->collectionAttachmentsScheduledForDeletion !== null) {
                if (!$this->collectionAttachmentsScheduledForDeletion->isEmpty()) {
                    CollectionAttachmentQuery::create()
                        ->filterByPrimaryKeys($this->collectionAttachmentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->collectionAttachmentsScheduledForDeletion = null;
                }
            }

            if ($this->collCollectionAttachments !== null) {
                foreach ($this->collCollectionAttachments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->storeMaintenanceLogsScheduledForDeletion !== null) {
                if (!$this->storeMaintenanceLogsScheduledForDeletion->isEmpty()) {
                    foreach ($this->storeMaintenanceLogsScheduledForDeletion as $storeMaintenanceLog) {
                        // need to save related object because we set the relation to null
                        $storeMaintenanceLog->save($con);
                    }
                    $this->storeMaintenanceLogsScheduledForDeletion = null;
                }
            }

            if ($this->collStoreMaintenanceLogs !== null) {
                foreach ($this->collStoreMaintenanceLogs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = CollectionPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CollectionPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CollectionPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CollectionPeer::UID)) {
            $modifiedColumns[':p' . $index++]  = '`uid`';
        }
        if ($this->isColumnModified(CollectionPeer::TYPE)) {
            $modifiedColumns[':p' . $index++]  = '`type`';
        }
        if ($this->isColumnModified(CollectionPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(CollectionPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(CollectionPeer::DATE)) {
            $modifiedColumns[':p' . $index++]  = '`date`';
        }
        if ($this->isColumnModified(CollectionPeer::IS_PUBLISHED)) {
            $modifiedColumns[':p' . $index++]  = '`is_published`';
        }
        if ($this->isColumnModified(CollectionPeer::IS_DELETED)) {
            $modifiedColumns[':p' . $index++]  = '`is_deleted`';
        }
        if ($this->isColumnModified(CollectionPeer::COLLECTION_COMPANY)) {
            $modifiedColumns[':p' . $index++]  = '`collection_company`';
        }
        if ($this->isColumnModified(CollectionPeer::COLLECTION_STORE)) {
            $modifiedColumns[':p' . $index++]  = '`collection_store`';
        }
        if ($this->isColumnModified(CollectionPeer::CREATED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`created_by`';
        }
        if ($this->isColumnModified(CollectionPeer::EDITED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`edited_by`';
        }
        if ($this->isColumnModified(CollectionPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(CollectionPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `collection` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`uid`':
                        $stmt->bindValue($identifier, $this->uid, PDO::PARAM_STR);
                        break;
                    case '`type`':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`date`':
                        $stmt->bindValue($identifier, $this->date, PDO::PARAM_STR);
                        break;
                    case '`is_published`':
                        $stmt->bindValue($identifier, (int) $this->is_published, PDO::PARAM_INT);
                        break;
                    case '`is_deleted`':
                        $stmt->bindValue($identifier, (int) $this->is_deleted, PDO::PARAM_INT);
                        break;
                    case '`collection_company`':
                        $stmt->bindValue($identifier, $this->collection_company, PDO::PARAM_INT);
                        break;
                    case '`collection_store`':
                        $stmt->bindValue($identifier, $this->collection_store, PDO::PARAM_INT);
                        break;
                    case '`created_by`':
                        $stmt->bindValue($identifier, $this->created_by, PDO::PARAM_INT);
                        break;
                    case '`edited_by`':
                        $stmt->bindValue($identifier, $this->edited_by, PDO::PARAM_INT);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCollectionType !== null) {
                if (!$this->aCollectionType->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCollectionType->getValidationFailures());
                }
            }

            if ($this->aCompany !== null) {
                if (!$this->aCompany->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCompany->getValidationFailures());
                }
            }

            if ($this->aStore !== null) {
                if (!$this->aStore->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aStore->getValidationFailures());
                }
            }

            if ($this->aUserRelatedByCreatedBy !== null) {
                if (!$this->aUserRelatedByCreatedBy->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUserRelatedByCreatedBy->getValidationFailures());
                }
            }

            if ($this->aUserRelatedByEditedBy !== null) {
                if (!$this->aUserRelatedByEditedBy->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUserRelatedByEditedBy->getValidationFailures());
                }
            }


            if (($retval = CollectionPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCollectionAttachments !== null) {
                    foreach ($this->collCollectionAttachments as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStoreMaintenanceLogs !== null) {
                    foreach ($this->collStoreMaintenanceLogs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CollectionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getUid();
                break;
            case 2:
                return $this->getType();
                break;
            case 3:
                return $this->getName();
                break;
            case 4:
                return $this->getDescription();
                break;
            case 5:
                return $this->getDate();
                break;
            case 6:
                return $this->getIsPublished();
                break;
            case 7:
                return $this->getIsDeleted();
                break;
            case 8:
                return $this->getCollectionCompany();
                break;
            case 9:
                return $this->getCollectionStore();
                break;
            case 10:
                return $this->getCreatedBy();
                break;
            case 11:
                return $this->getEditedBy();
                break;
            case 12:
                return $this->getCreatedAt();
                break;
            case 13:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Collection'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Collection'][$this->getPrimaryKey()] = true;
        $keys = CollectionPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUid(),
            $keys[2] => $this->getType(),
            $keys[3] => $this->getName(),
            $keys[4] => $this->getDescription(),
            $keys[5] => $this->getDate(),
            $keys[6] => $this->getIsPublished(),
            $keys[7] => $this->getIsDeleted(),
            $keys[8] => $this->getCollectionCompany(),
            $keys[9] => $this->getCollectionStore(),
            $keys[10] => $this->getCreatedBy(),
            $keys[11] => $this->getEditedBy(),
            $keys[12] => $this->getCreatedAt(),
            $keys[13] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCollectionType) {
                $result['CollectionType'] = $this->aCollectionType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCompany) {
                $result['Company'] = $this->aCompany->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aStore) {
                $result['Store'] = $this->aStore->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUserRelatedByCreatedBy) {
                $result['UserRelatedByCreatedBy'] = $this->aUserRelatedByCreatedBy->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUserRelatedByEditedBy) {
                $result['UserRelatedByEditedBy'] = $this->aUserRelatedByEditedBy->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCollectionAttachments) {
                $result['CollectionAttachments'] = $this->collCollectionAttachments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStoreMaintenanceLogs) {
                $result['StoreMaintenanceLogs'] = $this->collStoreMaintenanceLogs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CollectionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setUid($value);
                break;
            case 2:
                $this->setType($value);
                break;
            case 3:
                $this->setName($value);
                break;
            case 4:
                $this->setDescription($value);
                break;
            case 5:
                $this->setDate($value);
                break;
            case 6:
                $this->setIsPublished($value);
                break;
            case 7:
                $this->setIsDeleted($value);
                break;
            case 8:
                $this->setCollectionCompany($value);
                break;
            case 9:
                $this->setCollectionStore($value);
                break;
            case 10:
                $this->setCreatedBy($value);
                break;
            case 11:
                $this->setEditedBy($value);
                break;
            case 12:
                $this->setCreatedAt($value);
                break;
            case 13:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = CollectionPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setType($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setName($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDescription($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setDate($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setIsPublished($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setIsDeleted($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setCollectionCompany($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setCollectionStore($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setCreatedBy($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setEditedBy($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setCreatedAt($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setUpdatedAt($arr[$keys[13]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CollectionPeer::DATABASE_NAME);

        if ($this->isColumnModified(CollectionPeer::ID)) $criteria->add(CollectionPeer::ID, $this->id);
        if ($this->isColumnModified(CollectionPeer::UID)) $criteria->add(CollectionPeer::UID, $this->uid);
        if ($this->isColumnModified(CollectionPeer::TYPE)) $criteria->add(CollectionPeer::TYPE, $this->type);
        if ($this->isColumnModified(CollectionPeer::NAME)) $criteria->add(CollectionPeer::NAME, $this->name);
        if ($this->isColumnModified(CollectionPeer::DESCRIPTION)) $criteria->add(CollectionPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(CollectionPeer::DATE)) $criteria->add(CollectionPeer::DATE, $this->date);
        if ($this->isColumnModified(CollectionPeer::IS_PUBLISHED)) $criteria->add(CollectionPeer::IS_PUBLISHED, $this->is_published);
        if ($this->isColumnModified(CollectionPeer::IS_DELETED)) $criteria->add(CollectionPeer::IS_DELETED, $this->is_deleted);
        if ($this->isColumnModified(CollectionPeer::COLLECTION_COMPANY)) $criteria->add(CollectionPeer::COLLECTION_COMPANY, $this->collection_company);
        if ($this->isColumnModified(CollectionPeer::COLLECTION_STORE)) $criteria->add(CollectionPeer::COLLECTION_STORE, $this->collection_store);
        if ($this->isColumnModified(CollectionPeer::CREATED_BY)) $criteria->add(CollectionPeer::CREATED_BY, $this->created_by);
        if ($this->isColumnModified(CollectionPeer::EDITED_BY)) $criteria->add(CollectionPeer::EDITED_BY, $this->edited_by);
        if ($this->isColumnModified(CollectionPeer::CREATED_AT)) $criteria->add(CollectionPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(CollectionPeer::UPDATED_AT)) $criteria->add(CollectionPeer::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(CollectionPeer::DATABASE_NAME);
        $criteria->add(CollectionPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Collection (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUid($this->getUid());
        $copyObj->setType($this->getType());
        $copyObj->setName($this->getName());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setDate($this->getDate());
        $copyObj->setIsPublished($this->getIsPublished());
        $copyObj->setIsDeleted($this->getIsDeleted());
        $copyObj->setCollectionCompany($this->getCollectionCompany());
        $copyObj->setCollectionStore($this->getCollectionStore());
        $copyObj->setCreatedBy($this->getCreatedBy());
        $copyObj->setEditedBy($this->getEditedBy());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCollectionAttachments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCollectionAttachment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStoreMaintenanceLogs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStoreMaintenanceLog($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Collection Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return CollectionPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CollectionPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a CollectionType object.
     *
     * @param                  CollectionType $v
     * @return Collection The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCollectionType(CollectionType $v = null)
    {
        if ($v === null) {
            $this->setType(NULL);
        } else {
            $this->setType($v->getId());
        }

        $this->aCollectionType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the CollectionType object, it will not be re-added.
        if ($v !== null) {
            $v->addCollection($this);
        }


        return $this;
    }


    /**
     * Get the associated CollectionType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return CollectionType The associated CollectionType object.
     * @throws PropelException
     */
    public function getCollectionType(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCollectionType === null && ($this->type !== null) && $doQuery) {
            $this->aCollectionType = CollectionTypeQuery::create()->findPk($this->type, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCollectionType->addCollections($this);
             */
        }

        return $this->aCollectionType;
    }

    /**
     * Declares an association between this object and a Company object.
     *
     * @param                  Company $v
     * @return Collection The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCompany(Company $v = null)
    {
        if ($v === null) {
            $this->setCollectionCompany(NULL);
        } else {
            $this->setCollectionCompany($v->getId());
        }

        $this->aCompany = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Company object, it will not be re-added.
        if ($v !== null) {
            $v->addCollection($this);
        }


        return $this;
    }


    /**
     * Get the associated Company object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Company The associated Company object.
     * @throws PropelException
     */
    public function getCompany(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCompany === null && ($this->collection_company !== null) && $doQuery) {
            $this->aCompany = CompanyQuery::create()->findPk($this->collection_company, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCompany->addCollections($this);
             */
        }

        return $this->aCompany;
    }

    /**
     * Declares an association between this object and a Store object.
     *
     * @param                  Store $v
     * @return Collection The current object (for fluent API support)
     * @throws PropelException
     */
    public function setStore(Store $v = null)
    {
        if ($v === null) {
            $this->setCollectionStore(NULL);
        } else {
            $this->setCollectionStore($v->getId());
        }

        $this->aStore = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Store object, it will not be re-added.
        if ($v !== null) {
            $v->addCollection($this);
        }


        return $this;
    }


    /**
     * Get the associated Store object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Store The associated Store object.
     * @throws PropelException
     */
    public function getStore(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aStore === null && ($this->collection_store !== null) && $doQuery) {
            $this->aStore = StoreQuery::create()->findPk($this->collection_store, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aStore->addCollections($this);
             */
        }

        return $this->aStore;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return Collection The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserRelatedByCreatedBy(User $v = null)
    {
        if ($v === null) {
            $this->setCreatedBy(NULL);
        } else {
            $this->setCreatedBy($v->getId());
        }

        $this->aUserRelatedByCreatedBy = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addCollectionRelatedByCreatedBy($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUserRelatedByCreatedBy(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aUserRelatedByCreatedBy === null && ($this->created_by !== null) && $doQuery) {
            $this->aUserRelatedByCreatedBy = UserQuery::create()->findPk($this->created_by, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserRelatedByCreatedBy->addCollectionsRelatedByCreatedBy($this);
             */
        }

        return $this->aUserRelatedByCreatedBy;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return Collection The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserRelatedByEditedBy(User $v = null)
    {
        if ($v === null) {
            $this->setEditedBy(NULL);
        } else {
            $this->setEditedBy($v->getId());
        }

        $this->aUserRelatedByEditedBy = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addCollectionRelatedByEditedBy($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUserRelatedByEditedBy(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aUserRelatedByEditedBy === null && ($this->edited_by !== null) && $doQuery) {
            $this->aUserRelatedByEditedBy = UserQuery::create()->findPk($this->edited_by, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserRelatedByEditedBy->addCollectionsRelatedByEditedBy($this);
             */
        }

        return $this->aUserRelatedByEditedBy;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('CollectionAttachment' == $relationName) {
            $this->initCollectionAttachments();
        }
        if ('StoreMaintenanceLog' == $relationName) {
            $this->initStoreMaintenanceLogs();
        }
    }

    /**
     * Clears out the collCollectionAttachments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Collection The current object (for fluent API support)
     * @see        addCollectionAttachments()
     */
    public function clearCollectionAttachments()
    {
        $this->collCollectionAttachments = null; // important to set this to null since that means it is uninitialized
        $this->collCollectionAttachmentsPartial = null;

        return $this;
    }

    /**
     * reset is the collCollectionAttachments collection loaded partially
     *
     * @return void
     */
    public function resetPartialCollectionAttachments($v = true)
    {
        $this->collCollectionAttachmentsPartial = $v;
    }

    /**
     * Initializes the collCollectionAttachments collection.
     *
     * By default this just sets the collCollectionAttachments collection to an empty array (like clearcollCollectionAttachments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCollectionAttachments($overrideExisting = true)
    {
        if (null !== $this->collCollectionAttachments && !$overrideExisting) {
            return;
        }
        $this->collCollectionAttachments = new PropelObjectCollection();
        $this->collCollectionAttachments->setModel('CollectionAttachment');
    }

    /**
     * Gets an array of CollectionAttachment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Collection is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CollectionAttachment[] List of CollectionAttachment objects
     * @throws PropelException
     */
    public function getCollectionAttachments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCollectionAttachmentsPartial && !$this->isNew();
        if (null === $this->collCollectionAttachments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCollectionAttachments) {
                // return empty collection
                $this->initCollectionAttachments();
            } else {
                $collCollectionAttachments = CollectionAttachmentQuery::create(null, $criteria)
                    ->filterByCollection($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCollectionAttachmentsPartial && count($collCollectionAttachments)) {
                      $this->initCollectionAttachments(false);

                      foreach ($collCollectionAttachments as $obj) {
                        if (false == $this->collCollectionAttachments->contains($obj)) {
                          $this->collCollectionAttachments->append($obj);
                        }
                      }

                      $this->collCollectionAttachmentsPartial = true;
                    }

                    $collCollectionAttachments->getInternalIterator()->rewind();

                    return $collCollectionAttachments;
                }

                if ($partial && $this->collCollectionAttachments) {
                    foreach ($this->collCollectionAttachments as $obj) {
                        if ($obj->isNew()) {
                            $collCollectionAttachments[] = $obj;
                        }
                    }
                }

                $this->collCollectionAttachments = $collCollectionAttachments;
                $this->collCollectionAttachmentsPartial = false;
            }
        }

        return $this->collCollectionAttachments;
    }

    /**
     * Sets a collection of CollectionAttachment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $collectionAttachments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Collection The current object (for fluent API support)
     */
    public function setCollectionAttachments(PropelCollection $collectionAttachments, PropelPDO $con = null)
    {
        $collectionAttachmentsToDelete = $this->getCollectionAttachments(new Criteria(), $con)->diff($collectionAttachments);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->collectionAttachmentsScheduledForDeletion = clone $collectionAttachmentsToDelete;

        foreach ($collectionAttachmentsToDelete as $collectionAttachmentRemoved) {
            $collectionAttachmentRemoved->setCollection(null);
        }

        $this->collCollectionAttachments = null;
        foreach ($collectionAttachments as $collectionAttachment) {
            $this->addCollectionAttachment($collectionAttachment);
        }

        $this->collCollectionAttachments = $collectionAttachments;
        $this->collCollectionAttachmentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CollectionAttachment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CollectionAttachment objects.
     * @throws PropelException
     */
    public function countCollectionAttachments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCollectionAttachmentsPartial && !$this->isNew();
        if (null === $this->collCollectionAttachments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCollectionAttachments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCollectionAttachments());
            }
            $query = CollectionAttachmentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCollection($this)
                ->count($con);
        }

        return count($this->collCollectionAttachments);
    }

    /**
     * Method called to associate a CollectionAttachment object to this object
     * through the CollectionAttachment foreign key attribute.
     *
     * @param    CollectionAttachment $l CollectionAttachment
     * @return Collection The current object (for fluent API support)
     */
    public function addCollectionAttachment(CollectionAttachment $l)
    {
        if ($this->collCollectionAttachments === null) {
            $this->initCollectionAttachments();
            $this->collCollectionAttachmentsPartial = true;
        }

        if (!in_array($l, $this->collCollectionAttachments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCollectionAttachment($l);

            if ($this->collectionAttachmentsScheduledForDeletion and $this->collectionAttachmentsScheduledForDeletion->contains($l)) {
                $this->collectionAttachmentsScheduledForDeletion->remove($this->collectionAttachmentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CollectionAttachment $collectionAttachment The collectionAttachment object to add.
     */
    protected function doAddCollectionAttachment($collectionAttachment)
    {
        $this->collCollectionAttachments[]= $collectionAttachment;
        $collectionAttachment->setCollection($this);
    }

    /**
     * @param	CollectionAttachment $collectionAttachment The collectionAttachment object to remove.
     * @return Collection The current object (for fluent API support)
     */
    public function removeCollectionAttachment($collectionAttachment)
    {
        if ($this->getCollectionAttachments()->contains($collectionAttachment)) {
            $this->collCollectionAttachments->remove($this->collCollectionAttachments->search($collectionAttachment));
            if (null === $this->collectionAttachmentsScheduledForDeletion) {
                $this->collectionAttachmentsScheduledForDeletion = clone $this->collCollectionAttachments;
                $this->collectionAttachmentsScheduledForDeletion->clear();
            }
            $this->collectionAttachmentsScheduledForDeletion[]= clone $collectionAttachment;
            $collectionAttachment->setCollection(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Collection is new, it will return
     * an empty collection; or if this Collection has previously
     * been saved, it will retrieve related CollectionAttachments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Collection.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CollectionAttachment[] List of CollectionAttachment objects
     */
    public function getCollectionAttachmentsJoinAttachment($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CollectionAttachmentQuery::create(null, $criteria);
        $query->joinWith('Attachment', $join_behavior);

        return $this->getCollectionAttachments($query, $con);
    }

    /**
     * Clears out the collStoreMaintenanceLogs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Collection The current object (for fluent API support)
     * @see        addStoreMaintenanceLogs()
     */
    public function clearStoreMaintenanceLogs()
    {
        $this->collStoreMaintenanceLogs = null; // important to set this to null since that means it is uninitialized
        $this->collStoreMaintenanceLogsPartial = null;

        return $this;
    }

    /**
     * reset is the collStoreMaintenanceLogs collection loaded partially
     *
     * @return void
     */
    public function resetPartialStoreMaintenanceLogs($v = true)
    {
        $this->collStoreMaintenanceLogsPartial = $v;
    }

    /**
     * Initializes the collStoreMaintenanceLogs collection.
     *
     * By default this just sets the collStoreMaintenanceLogs collection to an empty array (like clearcollStoreMaintenanceLogs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStoreMaintenanceLogs($overrideExisting = true)
    {
        if (null !== $this->collStoreMaintenanceLogs && !$overrideExisting) {
            return;
        }
        $this->collStoreMaintenanceLogs = new PropelObjectCollection();
        $this->collStoreMaintenanceLogs->setModel('StoreMaintenanceLog');
    }

    /**
     * Gets an array of StoreMaintenanceLog objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Collection is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|StoreMaintenanceLog[] List of StoreMaintenanceLog objects
     * @throws PropelException
     */
    public function getStoreMaintenanceLogs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStoreMaintenanceLogsPartial && !$this->isNew();
        if (null === $this->collStoreMaintenanceLogs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStoreMaintenanceLogs) {
                // return empty collection
                $this->initStoreMaintenanceLogs();
            } else {
                $collStoreMaintenanceLogs = StoreMaintenanceLogQuery::create(null, $criteria)
                    ->filterByCollection($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStoreMaintenanceLogsPartial && count($collStoreMaintenanceLogs)) {
                      $this->initStoreMaintenanceLogs(false);

                      foreach ($collStoreMaintenanceLogs as $obj) {
                        if (false == $this->collStoreMaintenanceLogs->contains($obj)) {
                          $this->collStoreMaintenanceLogs->append($obj);
                        }
                      }

                      $this->collStoreMaintenanceLogsPartial = true;
                    }

                    $collStoreMaintenanceLogs->getInternalIterator()->rewind();

                    return $collStoreMaintenanceLogs;
                }

                if ($partial && $this->collStoreMaintenanceLogs) {
                    foreach ($this->collStoreMaintenanceLogs as $obj) {
                        if ($obj->isNew()) {
                            $collStoreMaintenanceLogs[] = $obj;
                        }
                    }
                }

                $this->collStoreMaintenanceLogs = $collStoreMaintenanceLogs;
                $this->collStoreMaintenanceLogsPartial = false;
            }
        }

        return $this->collStoreMaintenanceLogs;
    }

    /**
     * Sets a collection of StoreMaintenanceLog objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $storeMaintenanceLogs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Collection The current object (for fluent API support)
     */
    public function setStoreMaintenanceLogs(PropelCollection $storeMaintenanceLogs, PropelPDO $con = null)
    {
        $storeMaintenanceLogsToDelete = $this->getStoreMaintenanceLogs(new Criteria(), $con)->diff($storeMaintenanceLogs);


        $this->storeMaintenanceLogsScheduledForDeletion = $storeMaintenanceLogsToDelete;

        foreach ($storeMaintenanceLogsToDelete as $storeMaintenanceLogRemoved) {
            $storeMaintenanceLogRemoved->setCollection(null);
        }

        $this->collStoreMaintenanceLogs = null;
        foreach ($storeMaintenanceLogs as $storeMaintenanceLog) {
            $this->addStoreMaintenanceLog($storeMaintenanceLog);
        }

        $this->collStoreMaintenanceLogs = $storeMaintenanceLogs;
        $this->collStoreMaintenanceLogsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StoreMaintenanceLog objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related StoreMaintenanceLog objects.
     * @throws PropelException
     */
    public function countStoreMaintenanceLogs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStoreMaintenanceLogsPartial && !$this->isNew();
        if (null === $this->collStoreMaintenanceLogs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStoreMaintenanceLogs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStoreMaintenanceLogs());
            }
            $query = StoreMaintenanceLogQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCollection($this)
                ->count($con);
        }

        return count($this->collStoreMaintenanceLogs);
    }

    /**
     * Method called to associate a StoreMaintenanceLog object to this object
     * through the StoreMaintenanceLog foreign key attribute.
     *
     * @param    StoreMaintenanceLog $l StoreMaintenanceLog
     * @return Collection The current object (for fluent API support)
     */
    public function addStoreMaintenanceLog(StoreMaintenanceLog $l)
    {
        if ($this->collStoreMaintenanceLogs === null) {
            $this->initStoreMaintenanceLogs();
            $this->collStoreMaintenanceLogsPartial = true;
        }

        if (!in_array($l, $this->collStoreMaintenanceLogs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStoreMaintenanceLog($l);

            if ($this->storeMaintenanceLogsScheduledForDeletion and $this->storeMaintenanceLogsScheduledForDeletion->contains($l)) {
                $this->storeMaintenanceLogsScheduledForDeletion->remove($this->storeMaintenanceLogsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	StoreMaintenanceLog $storeMaintenanceLog The storeMaintenanceLog object to add.
     */
    protected function doAddStoreMaintenanceLog($storeMaintenanceLog)
    {
        $this->collStoreMaintenanceLogs[]= $storeMaintenanceLog;
        $storeMaintenanceLog->setCollection($this);
    }

    /**
     * @param	StoreMaintenanceLog $storeMaintenanceLog The storeMaintenanceLog object to remove.
     * @return Collection The current object (for fluent API support)
     */
    public function removeStoreMaintenanceLog($storeMaintenanceLog)
    {
        if ($this->getStoreMaintenanceLogs()->contains($storeMaintenanceLog)) {
            $this->collStoreMaintenanceLogs->remove($this->collStoreMaintenanceLogs->search($storeMaintenanceLog));
            if (null === $this->storeMaintenanceLogsScheduledForDeletion) {
                $this->storeMaintenanceLogsScheduledForDeletion = clone $this->collStoreMaintenanceLogs;
                $this->storeMaintenanceLogsScheduledForDeletion->clear();
            }
            $this->storeMaintenanceLogsScheduledForDeletion[]= $storeMaintenanceLog;
            $storeMaintenanceLog->setCollection(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Collection is new, it will return
     * an empty collection; or if this Collection has previously
     * been saved, it will retrieve related StoreMaintenanceLogs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Collection.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StoreMaintenanceLog[] List of StoreMaintenanceLog objects
     */
    public function getStoreMaintenanceLogsJoinMaintenanceType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreMaintenanceLogQuery::create(null, $criteria);
        $query->joinWith('MaintenanceType', $join_behavior);

        return $this->getStoreMaintenanceLogs($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Collection is new, it will return
     * an empty collection; or if this Collection has previously
     * been saved, it will retrieve related StoreMaintenanceLogs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Collection.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StoreMaintenanceLog[] List of StoreMaintenanceLog objects
     */
    public function getStoreMaintenanceLogsJoinStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreMaintenanceLogQuery::create(null, $criteria);
        $query->joinWith('Store', $join_behavior);

        return $this->getStoreMaintenanceLogs($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Collection is new, it will return
     * an empty collection; or if this Collection has previously
     * been saved, it will retrieve related StoreMaintenanceLogs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Collection.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StoreMaintenanceLog[] List of StoreMaintenanceLog objects
     */
    public function getStoreMaintenanceLogsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreMaintenanceLogQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getStoreMaintenanceLogs($query, $con);
    }

    /**
     * Clears out the collAttachments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Collection The current object (for fluent API support)
     * @see        addAttachments()
     */
    public function clearAttachments()
    {
        $this->collAttachments = null; // important to set this to null since that means it is uninitialized
        $this->collAttachmentsPartial = null;

        return $this;
    }

    /**
     * Initializes the collAttachments collection.
     *
     * By default this just sets the collAttachments collection to an empty collection (like clearAttachments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initAttachments()
    {
        $this->collAttachments = new PropelObjectCollection();
        $this->collAttachments->setModel('Attachment');
    }

    /**
     * Gets a collection of Attachment objects related by a many-to-many relationship
     * to the current object by way of the collection_attachment cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Collection is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Attachment[] List of Attachment objects
     */
    public function getAttachments($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collAttachments || null !== $criteria) {
            if ($this->isNew() && null === $this->collAttachments) {
                // return empty collection
                $this->initAttachments();
            } else {
                $collAttachments = AttachmentQuery::create(null, $criteria)
                    ->filterByCollection($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collAttachments;
                }
                $this->collAttachments = $collAttachments;
            }
        }

        return $this->collAttachments;
    }

    /**
     * Sets a collection of Attachment objects related by a many-to-many relationship
     * to the current object by way of the collection_attachment cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $attachments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Collection The current object (for fluent API support)
     */
    public function setAttachments(PropelCollection $attachments, PropelPDO $con = null)
    {
        $this->clearAttachments();
        $currentAttachments = $this->getAttachments(null, $con);

        $this->attachmentsScheduledForDeletion = $currentAttachments->diff($attachments);

        foreach ($attachments as $attachment) {
            if (!$currentAttachments->contains($attachment)) {
                $this->doAddAttachment($attachment);
            }
        }

        $this->collAttachments = $attachments;

        return $this;
    }

    /**
     * Gets the number of Attachment objects related by a many-to-many relationship
     * to the current object by way of the collection_attachment cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Attachment objects
     */
    public function countAttachments($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collAttachments || null !== $criteria) {
            if ($this->isNew() && null === $this->collAttachments) {
                return 0;
            } else {
                $query = AttachmentQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByCollection($this)
                    ->count($con);
            }
        } else {
            return count($this->collAttachments);
        }
    }

    /**
     * Associate a Attachment object to this object
     * through the collection_attachment cross reference table.
     *
     * @param  Attachment $attachment The CollectionAttachment object to relate
     * @return Collection The current object (for fluent API support)
     */
    public function addAttachment(Attachment $attachment)
    {
        if ($this->collAttachments === null) {
            $this->initAttachments();
        }

        if (!$this->collAttachments->contains($attachment)) { // only add it if the **same** object is not already associated
            $this->doAddAttachment($attachment);
            $this->collAttachments[] = $attachment;

            if ($this->attachmentsScheduledForDeletion and $this->attachmentsScheduledForDeletion->contains($attachment)) {
                $this->attachmentsScheduledForDeletion->remove($this->attachmentsScheduledForDeletion->search($attachment));
            }
        }

        return $this;
    }

    /**
     * @param	Attachment $attachment The attachment object to add.
     */
    protected function doAddAttachment(Attachment $attachment)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$attachment->getCollections()->contains($this)) { $collectionAttachment = new CollectionAttachment();
            $collectionAttachment->setAttachment($attachment);
            $this->addCollectionAttachment($collectionAttachment);

            $foreignCollection = $attachment->getCollections();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Attachment object to this object
     * through the collection_attachment cross reference table.
     *
     * @param Attachment $attachment The CollectionAttachment object to relate
     * @return Collection The current object (for fluent API support)
     */
    public function removeAttachment(Attachment $attachment)
    {
        if ($this->getAttachments()->contains($attachment)) {
            $this->collAttachments->remove($this->collAttachments->search($attachment));
            if (null === $this->attachmentsScheduledForDeletion) {
                $this->attachmentsScheduledForDeletion = clone $this->collAttachments;
                $this->attachmentsScheduledForDeletion->clear();
            }
            $this->attachmentsScheduledForDeletion[]= $attachment;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->uid = null;
        $this->type = null;
        $this->name = null;
        $this->description = null;
        $this->date = null;
        $this->is_published = null;
        $this->is_deleted = null;
        $this->collection_company = null;
        $this->collection_store = null;
        $this->created_by = null;
        $this->edited_by = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collCollectionAttachments) {
                foreach ($this->collCollectionAttachments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStoreMaintenanceLogs) {
                foreach ($this->collStoreMaintenanceLogs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAttachments) {
                foreach ($this->collAttachments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aCollectionType instanceof Persistent) {
              $this->aCollectionType->clearAllReferences($deep);
            }
            if ($this->aCompany instanceof Persistent) {
              $this->aCompany->clearAllReferences($deep);
            }
            if ($this->aStore instanceof Persistent) {
              $this->aStore->clearAllReferences($deep);
            }
            if ($this->aUserRelatedByCreatedBy instanceof Persistent) {
              $this->aUserRelatedByCreatedBy->clearAllReferences($deep);
            }
            if ($this->aUserRelatedByEditedBy instanceof Persistent) {
              $this->aUserRelatedByEditedBy->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collCollectionAttachments instanceof PropelCollection) {
            $this->collCollectionAttachments->clearIterator();
        }
        $this->collCollectionAttachments = null;
        if ($this->collStoreMaintenanceLogs instanceof PropelCollection) {
            $this->collStoreMaintenanceLogs->clearIterator();
        }
        $this->collStoreMaintenanceLogs = null;
        if ($this->collAttachments instanceof PropelCollection) {
            $this->collAttachments->clearIterator();
        }
        $this->collAttachments = null;
        $this->aCollectionType = null;
        $this->aCompany = null;
        $this->aStore = null;
        $this->aUserRelatedByCreatedBy = null;
        $this->aUserRelatedByEditedBy = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CollectionPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     Collection The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = CollectionPeer::UPDATED_AT;

        return $this;
    }

}
