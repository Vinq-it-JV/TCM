<?php

namespace UserBundle\Model\om;

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
use CompanyBundle\Model\Company;
use CompanyBundle\Model\CompanyPhone;
use CompanyBundle\Model\CompanyPhoneQuery;
use CompanyBundle\Model\CompanyQuery;
use StoreBundle\Model\Store;
use StoreBundle\Model\StorePhone;
use StoreBundle\Model\StorePhoneQuery;
use StoreBundle\Model\StoreQuery;
use UserBundle\Model\Phone;
use UserBundle\Model\PhonePeer;
use UserBundle\Model\PhoneQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserPhone;
use UserBundle\Model\UserPhoneQuery;
use UserBundle\Model\UserQuery;

abstract class BasePhone extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'UserBundle\\Model\\PhonePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PhonePeer
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
     * The value for the primary field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $primary;

    /**
     * The value for the phone_number field.
     * @var        string
     */
    protected $phone_number;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

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
     * @var        PropelObjectCollection|CompanyPhone[] Collection to store aggregation of CompanyPhone objects.
     */
    protected $collCompanyPhones;
    protected $collCompanyPhonesPartial;

    /**
     * @var        PropelObjectCollection|StorePhone[] Collection to store aggregation of StorePhone objects.
     */
    protected $collStorePhones;
    protected $collStorePhonesPartial;

    /**
     * @var        PropelObjectCollection|UserPhone[] Collection to store aggregation of UserPhone objects.
     */
    protected $collUserPhones;
    protected $collUserPhonesPartial;

    /**
     * @var        PropelObjectCollection|Company[] Collection to store aggregation of Company objects.
     */
    protected $collCompanies;

    /**
     * @var        PropelObjectCollection|Store[] Collection to store aggregation of Store objects.
     */
    protected $collStores;

    /**
     * @var        PropelObjectCollection|User[] Collection to store aggregation of User objects.
     */
    protected $collUsers;

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
    protected $companiesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $storesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $usersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $companyPhonesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $storePhonesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userPhonesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->primary = false;
    }

    /**
     * Initializes internal state of BasePhone object.
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
     * Get the [primary] column value.
     *
     * @return boolean
     */
    public function getPrimary()
    {

        return $this->primary;
    }

    /**
     * Get the [phone_number] column value.
     *
     * @return string
     */
    public function getPhoneNumber()
    {

        return $this->phone_number;
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
     * @return Phone The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PhonePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Sets the value of the [primary] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Phone The current object (for fluent API support)
     */
    public function setPrimary($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->primary !== $v) {
            $this->primary = $v;
            $this->modifiedColumns[] = PhonePeer::PRIMARY;
        }


        return $this;
    } // setPrimary()

    /**
     * Set the value of [phone_number] column.
     *
     * @param  string $v new value
     * @return Phone The current object (for fluent API support)
     */
    public function setPhoneNumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone_number !== $v) {
            $this->phone_number = $v;
            $this->modifiedColumns[] = PhonePeer::PHONE_NUMBER;
        }


        return $this;
    } // setPhoneNumber()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return Phone The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = PhonePeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Phone The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = PhonePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Phone The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = PhonePeer::UPDATED_AT;
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
            if ($this->primary !== false) {
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
            $this->primary = ($row[$startcol + 1] !== null) ? (boolean) $row[$startcol + 1] : null;
            $this->phone_number = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->description = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->created_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->updated_at = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 6; // 6 = PhonePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Phone object", $e);
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
            $con = Propel::getConnection(PhonePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PhonePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCompanyPhones = null;

            $this->collStorePhones = null;

            $this->collUserPhones = null;

            $this->collCompanies = null;
            $this->collStores = null;
            $this->collUsers = null;
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
            $con = Propel::getConnection(PhonePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PhoneQuery::create()
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
            $con = Propel::getConnection(PhonePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PhonePeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PhonePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PhonePeer::UPDATED_AT)) {
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
                PhonePeer::addInstanceToPool($this);
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

            if ($this->companiesScheduledForDeletion !== null) {
                if (!$this->companiesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->companiesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    CompanyPhoneQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->companiesScheduledForDeletion = null;
                }

                foreach ($this->getCompanies() as $company) {
                    if ($company->isModified()) {
                        $company->save($con);
                    }
                }
            } elseif ($this->collCompanies) {
                foreach ($this->collCompanies as $company) {
                    if ($company->isModified()) {
                        $company->save($con);
                    }
                }
            }

            if ($this->storesScheduledForDeletion !== null) {
                if (!$this->storesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->storesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    StorePhoneQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->storesScheduledForDeletion = null;
                }

                foreach ($this->getStores() as $store) {
                    if ($store->isModified()) {
                        $store->save($con);
                    }
                }
            } elseif ($this->collStores) {
                foreach ($this->collStores as $store) {
                    if ($store->isModified()) {
                        $store->save($con);
                    }
                }
            }

            if ($this->usersScheduledForDeletion !== null) {
                if (!$this->usersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->usersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    UserPhoneQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->usersScheduledForDeletion = null;
                }

                foreach ($this->getUsers() as $user) {
                    if ($user->isModified()) {
                        $user->save($con);
                    }
                }
            } elseif ($this->collUsers) {
                foreach ($this->collUsers as $user) {
                    if ($user->isModified()) {
                        $user->save($con);
                    }
                }
            }

            if ($this->companyPhonesScheduledForDeletion !== null) {
                if (!$this->companyPhonesScheduledForDeletion->isEmpty()) {
                    CompanyPhoneQuery::create()
                        ->filterByPrimaryKeys($this->companyPhonesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->companyPhonesScheduledForDeletion = null;
                }
            }

            if ($this->collCompanyPhones !== null) {
                foreach ($this->collCompanyPhones as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->storePhonesScheduledForDeletion !== null) {
                if (!$this->storePhonesScheduledForDeletion->isEmpty()) {
                    StorePhoneQuery::create()
                        ->filterByPrimaryKeys($this->storePhonesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->storePhonesScheduledForDeletion = null;
                }
            }

            if ($this->collStorePhones !== null) {
                foreach ($this->collStorePhones as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userPhonesScheduledForDeletion !== null) {
                if (!$this->userPhonesScheduledForDeletion->isEmpty()) {
                    UserPhoneQuery::create()
                        ->filterByPrimaryKeys($this->userPhonesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userPhonesScheduledForDeletion = null;
                }
            }

            if ($this->collUserPhones !== null) {
                foreach ($this->collUserPhones as $referrerFK) {
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

        $this->modifiedColumns[] = PhonePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PhonePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PhonePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PhonePeer::PRIMARY)) {
            $modifiedColumns[':p' . $index++]  = '`primary`';
        }
        if ($this->isColumnModified(PhonePeer::PHONE_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = '`phone_number`';
        }
        if ($this->isColumnModified(PhonePeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(PhonePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(PhonePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `phone` (%s) VALUES (%s)',
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
                    case '`primary`':
                        $stmt->bindValue($identifier, (int) $this->primary, PDO::PARAM_INT);
                        break;
                    case '`phone_number`':
                        $stmt->bindValue($identifier, $this->phone_number, PDO::PARAM_STR);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
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


            if (($retval = PhonePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCompanyPhones !== null) {
                    foreach ($this->collCompanyPhones as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStorePhones !== null) {
                    foreach ($this->collStorePhones as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUserPhones !== null) {
                    foreach ($this->collUserPhones as $referrerFK) {
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
        $pos = PhonePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getPrimary();
                break;
            case 2:
                return $this->getPhoneNumber();
                break;
            case 3:
                return $this->getDescription();
                break;
            case 4:
                return $this->getCreatedAt();
                break;
            case 5:
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
        if (isset($alreadyDumpedObjects['Phone'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Phone'][$this->getPrimaryKey()] = true;
        $keys = PhonePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPrimary(),
            $keys[2] => $this->getPhoneNumber(),
            $keys[3] => $this->getDescription(),
            $keys[4] => $this->getCreatedAt(),
            $keys[5] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collCompanyPhones) {
                $result['CompanyPhones'] = $this->collCompanyPhones->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStorePhones) {
                $result['StorePhones'] = $this->collStorePhones->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserPhones) {
                $result['UserPhones'] = $this->collUserPhones->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PhonePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setPrimary($value);
                break;
            case 2:
                $this->setPhoneNumber($value);
                break;
            case 3:
                $this->setDescription($value);
                break;
            case 4:
                $this->setCreatedAt($value);
                break;
            case 5:
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
        $keys = PhonePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setPrimary($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPhoneNumber($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDescription($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setCreatedAt($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setUpdatedAt($arr[$keys[5]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PhonePeer::DATABASE_NAME);

        if ($this->isColumnModified(PhonePeer::ID)) $criteria->add(PhonePeer::ID, $this->id);
        if ($this->isColumnModified(PhonePeer::PRIMARY)) $criteria->add(PhonePeer::PRIMARY, $this->primary);
        if ($this->isColumnModified(PhonePeer::PHONE_NUMBER)) $criteria->add(PhonePeer::PHONE_NUMBER, $this->phone_number);
        if ($this->isColumnModified(PhonePeer::DESCRIPTION)) $criteria->add(PhonePeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(PhonePeer::CREATED_AT)) $criteria->add(PhonePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PhonePeer::UPDATED_AT)) $criteria->add(PhonePeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(PhonePeer::DATABASE_NAME);
        $criteria->add(PhonePeer::ID, $this->id);

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
     * @param object $copyObj An object of Phone (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPrimary($this->getPrimary());
        $copyObj->setPhoneNumber($this->getPhoneNumber());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCompanyPhones() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCompanyPhone($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStorePhones() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStorePhone($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserPhones() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserPhone($relObj->copy($deepCopy));
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
     * @return Phone Clone of current object.
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
     * @return PhonePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PhonePeer();
        }

        return self::$peer;
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
        if ('CompanyPhone' == $relationName) {
            $this->initCompanyPhones();
        }
        if ('StorePhone' == $relationName) {
            $this->initStorePhones();
        }
        if ('UserPhone' == $relationName) {
            $this->initUserPhones();
        }
    }

    /**
     * Clears out the collCompanyPhones collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Phone The current object (for fluent API support)
     * @see        addCompanyPhones()
     */
    public function clearCompanyPhones()
    {
        $this->collCompanyPhones = null; // important to set this to null since that means it is uninitialized
        $this->collCompanyPhonesPartial = null;

        return $this;
    }

    /**
     * reset is the collCompanyPhones collection loaded partially
     *
     * @return void
     */
    public function resetPartialCompanyPhones($v = true)
    {
        $this->collCompanyPhonesPartial = $v;
    }

    /**
     * Initializes the collCompanyPhones collection.
     *
     * By default this just sets the collCompanyPhones collection to an empty array (like clearcollCompanyPhones());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCompanyPhones($overrideExisting = true)
    {
        if (null !== $this->collCompanyPhones && !$overrideExisting) {
            return;
        }
        $this->collCompanyPhones = new PropelObjectCollection();
        $this->collCompanyPhones->setModel('CompanyPhone');
    }

    /**
     * Gets an array of CompanyPhone objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Phone is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CompanyPhone[] List of CompanyPhone objects
     * @throws PropelException
     */
    public function getCompanyPhones($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCompanyPhonesPartial && !$this->isNew();
        if (null === $this->collCompanyPhones || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCompanyPhones) {
                // return empty collection
                $this->initCompanyPhones();
            } else {
                $collCompanyPhones = CompanyPhoneQuery::create(null, $criteria)
                    ->filterByPhone($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCompanyPhonesPartial && count($collCompanyPhones)) {
                      $this->initCompanyPhones(false);

                      foreach ($collCompanyPhones as $obj) {
                        if (false == $this->collCompanyPhones->contains($obj)) {
                          $this->collCompanyPhones->append($obj);
                        }
                      }

                      $this->collCompanyPhonesPartial = true;
                    }

                    $collCompanyPhones->getInternalIterator()->rewind();

                    return $collCompanyPhones;
                }

                if ($partial && $this->collCompanyPhones) {
                    foreach ($this->collCompanyPhones as $obj) {
                        if ($obj->isNew()) {
                            $collCompanyPhones[] = $obj;
                        }
                    }
                }

                $this->collCompanyPhones = $collCompanyPhones;
                $this->collCompanyPhonesPartial = false;
            }
        }

        return $this->collCompanyPhones;
    }

    /**
     * Sets a collection of CompanyPhone objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $companyPhones A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Phone The current object (for fluent API support)
     */
    public function setCompanyPhones(PropelCollection $companyPhones, PropelPDO $con = null)
    {
        $companyPhonesToDelete = $this->getCompanyPhones(new Criteria(), $con)->diff($companyPhones);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->companyPhonesScheduledForDeletion = clone $companyPhonesToDelete;

        foreach ($companyPhonesToDelete as $companyPhoneRemoved) {
            $companyPhoneRemoved->setPhone(null);
        }

        $this->collCompanyPhones = null;
        foreach ($companyPhones as $companyPhone) {
            $this->addCompanyPhone($companyPhone);
        }

        $this->collCompanyPhones = $companyPhones;
        $this->collCompanyPhonesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CompanyPhone objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CompanyPhone objects.
     * @throws PropelException
     */
    public function countCompanyPhones(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCompanyPhonesPartial && !$this->isNew();
        if (null === $this->collCompanyPhones || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCompanyPhones) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCompanyPhones());
            }
            $query = CompanyPhoneQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPhone($this)
                ->count($con);
        }

        return count($this->collCompanyPhones);
    }

    /**
     * Method called to associate a CompanyPhone object to this object
     * through the CompanyPhone foreign key attribute.
     *
     * @param    CompanyPhone $l CompanyPhone
     * @return Phone The current object (for fluent API support)
     */
    public function addCompanyPhone(CompanyPhone $l)
    {
        if ($this->collCompanyPhones === null) {
            $this->initCompanyPhones();
            $this->collCompanyPhonesPartial = true;
        }

        if (!in_array($l, $this->collCompanyPhones->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCompanyPhone($l);

            if ($this->companyPhonesScheduledForDeletion and $this->companyPhonesScheduledForDeletion->contains($l)) {
                $this->companyPhonesScheduledForDeletion->remove($this->companyPhonesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CompanyPhone $companyPhone The companyPhone object to add.
     */
    protected function doAddCompanyPhone($companyPhone)
    {
        $this->collCompanyPhones[]= $companyPhone;
        $companyPhone->setPhone($this);
    }

    /**
     * @param	CompanyPhone $companyPhone The companyPhone object to remove.
     * @return Phone The current object (for fluent API support)
     */
    public function removeCompanyPhone($companyPhone)
    {
        if ($this->getCompanyPhones()->contains($companyPhone)) {
            $this->collCompanyPhones->remove($this->collCompanyPhones->search($companyPhone));
            if (null === $this->companyPhonesScheduledForDeletion) {
                $this->companyPhonesScheduledForDeletion = clone $this->collCompanyPhones;
                $this->companyPhonesScheduledForDeletion->clear();
            }
            $this->companyPhonesScheduledForDeletion[]= clone $companyPhone;
            $companyPhone->setPhone(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Phone is new, it will return
     * an empty collection; or if this Phone has previously
     * been saved, it will retrieve related CompanyPhones from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Phone.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CompanyPhone[] List of CompanyPhone objects
     */
    public function getCompanyPhonesJoinCompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CompanyPhoneQuery::create(null, $criteria);
        $query->joinWith('Company', $join_behavior);

        return $this->getCompanyPhones($query, $con);
    }

    /**
     * Clears out the collStorePhones collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Phone The current object (for fluent API support)
     * @see        addStorePhones()
     */
    public function clearStorePhones()
    {
        $this->collStorePhones = null; // important to set this to null since that means it is uninitialized
        $this->collStorePhonesPartial = null;

        return $this;
    }

    /**
     * reset is the collStorePhones collection loaded partially
     *
     * @return void
     */
    public function resetPartialStorePhones($v = true)
    {
        $this->collStorePhonesPartial = $v;
    }

    /**
     * Initializes the collStorePhones collection.
     *
     * By default this just sets the collStorePhones collection to an empty array (like clearcollStorePhones());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStorePhones($overrideExisting = true)
    {
        if (null !== $this->collStorePhones && !$overrideExisting) {
            return;
        }
        $this->collStorePhones = new PropelObjectCollection();
        $this->collStorePhones->setModel('StorePhone');
    }

    /**
     * Gets an array of StorePhone objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Phone is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|StorePhone[] List of StorePhone objects
     * @throws PropelException
     */
    public function getStorePhones($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStorePhonesPartial && !$this->isNew();
        if (null === $this->collStorePhones || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStorePhones) {
                // return empty collection
                $this->initStorePhones();
            } else {
                $collStorePhones = StorePhoneQuery::create(null, $criteria)
                    ->filterByPhone($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStorePhonesPartial && count($collStorePhones)) {
                      $this->initStorePhones(false);

                      foreach ($collStorePhones as $obj) {
                        if (false == $this->collStorePhones->contains($obj)) {
                          $this->collStorePhones->append($obj);
                        }
                      }

                      $this->collStorePhonesPartial = true;
                    }

                    $collStorePhones->getInternalIterator()->rewind();

                    return $collStorePhones;
                }

                if ($partial && $this->collStorePhones) {
                    foreach ($this->collStorePhones as $obj) {
                        if ($obj->isNew()) {
                            $collStorePhones[] = $obj;
                        }
                    }
                }

                $this->collStorePhones = $collStorePhones;
                $this->collStorePhonesPartial = false;
            }
        }

        return $this->collStorePhones;
    }

    /**
     * Sets a collection of StorePhone objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $storePhones A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Phone The current object (for fluent API support)
     */
    public function setStorePhones(PropelCollection $storePhones, PropelPDO $con = null)
    {
        $storePhonesToDelete = $this->getStorePhones(new Criteria(), $con)->diff($storePhones);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->storePhonesScheduledForDeletion = clone $storePhonesToDelete;

        foreach ($storePhonesToDelete as $storePhoneRemoved) {
            $storePhoneRemoved->setPhone(null);
        }

        $this->collStorePhones = null;
        foreach ($storePhones as $storePhone) {
            $this->addStorePhone($storePhone);
        }

        $this->collStorePhones = $storePhones;
        $this->collStorePhonesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StorePhone objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related StorePhone objects.
     * @throws PropelException
     */
    public function countStorePhones(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStorePhonesPartial && !$this->isNew();
        if (null === $this->collStorePhones || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStorePhones) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStorePhones());
            }
            $query = StorePhoneQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPhone($this)
                ->count($con);
        }

        return count($this->collStorePhones);
    }

    /**
     * Method called to associate a StorePhone object to this object
     * through the StorePhone foreign key attribute.
     *
     * @param    StorePhone $l StorePhone
     * @return Phone The current object (for fluent API support)
     */
    public function addStorePhone(StorePhone $l)
    {
        if ($this->collStorePhones === null) {
            $this->initStorePhones();
            $this->collStorePhonesPartial = true;
        }

        if (!in_array($l, $this->collStorePhones->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStorePhone($l);

            if ($this->storePhonesScheduledForDeletion and $this->storePhonesScheduledForDeletion->contains($l)) {
                $this->storePhonesScheduledForDeletion->remove($this->storePhonesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	StorePhone $storePhone The storePhone object to add.
     */
    protected function doAddStorePhone($storePhone)
    {
        $this->collStorePhones[]= $storePhone;
        $storePhone->setPhone($this);
    }

    /**
     * @param	StorePhone $storePhone The storePhone object to remove.
     * @return Phone The current object (for fluent API support)
     */
    public function removeStorePhone($storePhone)
    {
        if ($this->getStorePhones()->contains($storePhone)) {
            $this->collStorePhones->remove($this->collStorePhones->search($storePhone));
            if (null === $this->storePhonesScheduledForDeletion) {
                $this->storePhonesScheduledForDeletion = clone $this->collStorePhones;
                $this->storePhonesScheduledForDeletion->clear();
            }
            $this->storePhonesScheduledForDeletion[]= clone $storePhone;
            $storePhone->setPhone(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Phone is new, it will return
     * an empty collection; or if this Phone has previously
     * been saved, it will retrieve related StorePhones from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Phone.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StorePhone[] List of StorePhone objects
     */
    public function getStorePhonesJoinStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StorePhoneQuery::create(null, $criteria);
        $query->joinWith('Store', $join_behavior);

        return $this->getStorePhones($query, $con);
    }

    /**
     * Clears out the collUserPhones collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Phone The current object (for fluent API support)
     * @see        addUserPhones()
     */
    public function clearUserPhones()
    {
        $this->collUserPhones = null; // important to set this to null since that means it is uninitialized
        $this->collUserPhonesPartial = null;

        return $this;
    }

    /**
     * reset is the collUserPhones collection loaded partially
     *
     * @return void
     */
    public function resetPartialUserPhones($v = true)
    {
        $this->collUserPhonesPartial = $v;
    }

    /**
     * Initializes the collUserPhones collection.
     *
     * By default this just sets the collUserPhones collection to an empty array (like clearcollUserPhones());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserPhones($overrideExisting = true)
    {
        if (null !== $this->collUserPhones && !$overrideExisting) {
            return;
        }
        $this->collUserPhones = new PropelObjectCollection();
        $this->collUserPhones->setModel('UserPhone');
    }

    /**
     * Gets an array of UserPhone objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Phone is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserPhone[] List of UserPhone objects
     * @throws PropelException
     */
    public function getUserPhones($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserPhonesPartial && !$this->isNew();
        if (null === $this->collUserPhones || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserPhones) {
                // return empty collection
                $this->initUserPhones();
            } else {
                $collUserPhones = UserPhoneQuery::create(null, $criteria)
                    ->filterByPhone($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUserPhonesPartial && count($collUserPhones)) {
                      $this->initUserPhones(false);

                      foreach ($collUserPhones as $obj) {
                        if (false == $this->collUserPhones->contains($obj)) {
                          $this->collUserPhones->append($obj);
                        }
                      }

                      $this->collUserPhonesPartial = true;
                    }

                    $collUserPhones->getInternalIterator()->rewind();

                    return $collUserPhones;
                }

                if ($partial && $this->collUserPhones) {
                    foreach ($this->collUserPhones as $obj) {
                        if ($obj->isNew()) {
                            $collUserPhones[] = $obj;
                        }
                    }
                }

                $this->collUserPhones = $collUserPhones;
                $this->collUserPhonesPartial = false;
            }
        }

        return $this->collUserPhones;
    }

    /**
     * Sets a collection of UserPhone objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userPhones A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Phone The current object (for fluent API support)
     */
    public function setUserPhones(PropelCollection $userPhones, PropelPDO $con = null)
    {
        $userPhonesToDelete = $this->getUserPhones(new Criteria(), $con)->diff($userPhones);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userPhonesScheduledForDeletion = clone $userPhonesToDelete;

        foreach ($userPhonesToDelete as $userPhoneRemoved) {
            $userPhoneRemoved->setPhone(null);
        }

        $this->collUserPhones = null;
        foreach ($userPhones as $userPhone) {
            $this->addUserPhone($userPhone);
        }

        $this->collUserPhones = $userPhones;
        $this->collUserPhonesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserPhone objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserPhone objects.
     * @throws PropelException
     */
    public function countUserPhones(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserPhonesPartial && !$this->isNew();
        if (null === $this->collUserPhones || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserPhones) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserPhones());
            }
            $query = UserPhoneQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPhone($this)
                ->count($con);
        }

        return count($this->collUserPhones);
    }

    /**
     * Method called to associate a UserPhone object to this object
     * through the UserPhone foreign key attribute.
     *
     * @param    UserPhone $l UserPhone
     * @return Phone The current object (for fluent API support)
     */
    public function addUserPhone(UserPhone $l)
    {
        if ($this->collUserPhones === null) {
            $this->initUserPhones();
            $this->collUserPhonesPartial = true;
        }

        if (!in_array($l, $this->collUserPhones->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserPhone($l);

            if ($this->userPhonesScheduledForDeletion and $this->userPhonesScheduledForDeletion->contains($l)) {
                $this->userPhonesScheduledForDeletion->remove($this->userPhonesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	UserPhone $userPhone The userPhone object to add.
     */
    protected function doAddUserPhone($userPhone)
    {
        $this->collUserPhones[]= $userPhone;
        $userPhone->setPhone($this);
    }

    /**
     * @param	UserPhone $userPhone The userPhone object to remove.
     * @return Phone The current object (for fluent API support)
     */
    public function removeUserPhone($userPhone)
    {
        if ($this->getUserPhones()->contains($userPhone)) {
            $this->collUserPhones->remove($this->collUserPhones->search($userPhone));
            if (null === $this->userPhonesScheduledForDeletion) {
                $this->userPhonesScheduledForDeletion = clone $this->collUserPhones;
                $this->userPhonesScheduledForDeletion->clear();
            }
            $this->userPhonesScheduledForDeletion[]= clone $userPhone;
            $userPhone->setPhone(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Phone is new, it will return
     * an empty collection; or if this Phone has previously
     * been saved, it will retrieve related UserPhones from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Phone.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserPhone[] List of UserPhone objects
     */
    public function getUserPhonesJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserPhoneQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getUserPhones($query, $con);
    }

    /**
     * Clears out the collCompanies collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Phone The current object (for fluent API support)
     * @see        addCompanies()
     */
    public function clearCompanies()
    {
        $this->collCompanies = null; // important to set this to null since that means it is uninitialized
        $this->collCompaniesPartial = null;

        return $this;
    }

    /**
     * Initializes the collCompanies collection.
     *
     * By default this just sets the collCompanies collection to an empty collection (like clearCompanies());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initCompanies()
    {
        $this->collCompanies = new PropelObjectCollection();
        $this->collCompanies->setModel('Company');
    }

    /**
     * Gets a collection of Company objects related by a many-to-many relationship
     * to the current object by way of the company_phone cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Phone is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Company[] List of Company objects
     */
    public function getCompanies($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collCompanies || null !== $criteria) {
            if ($this->isNew() && null === $this->collCompanies) {
                // return empty collection
                $this->initCompanies();
            } else {
                $collCompanies = CompanyQuery::create(null, $criteria)
                    ->filterByPhone($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collCompanies;
                }
                $this->collCompanies = $collCompanies;
            }
        }

        return $this->collCompanies;
    }

    /**
     * Sets a collection of Company objects related by a many-to-many relationship
     * to the current object by way of the company_phone cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $companies A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Phone The current object (for fluent API support)
     */
    public function setCompanies(PropelCollection $companies, PropelPDO $con = null)
    {
        $this->clearCompanies();
        $currentCompanies = $this->getCompanies(null, $con);

        $this->companiesScheduledForDeletion = $currentCompanies->diff($companies);

        foreach ($companies as $company) {
            if (!$currentCompanies->contains($company)) {
                $this->doAddCompany($company);
            }
        }

        $this->collCompanies = $companies;

        return $this;
    }

    /**
     * Gets the number of Company objects related by a many-to-many relationship
     * to the current object by way of the company_phone cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Company objects
     */
    public function countCompanies($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collCompanies || null !== $criteria) {
            if ($this->isNew() && null === $this->collCompanies) {
                return 0;
            } else {
                $query = CompanyQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPhone($this)
                    ->count($con);
            }
        } else {
            return count($this->collCompanies);
        }
    }

    /**
     * Associate a Company object to this object
     * through the company_phone cross reference table.
     *
     * @param  Company $company The CompanyPhone object to relate
     * @return Phone The current object (for fluent API support)
     */
    public function addCompany(Company $company)
    {
        if ($this->collCompanies === null) {
            $this->initCompanies();
        }

        if (!$this->collCompanies->contains($company)) { // only add it if the **same** object is not already associated
            $this->doAddCompany($company);
            $this->collCompanies[] = $company;

            if ($this->companiesScheduledForDeletion and $this->companiesScheduledForDeletion->contains($company)) {
                $this->companiesScheduledForDeletion->remove($this->companiesScheduledForDeletion->search($company));
            }
        }

        return $this;
    }

    /**
     * @param	Company $company The company object to add.
     */
    protected function doAddCompany(Company $company)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$company->getPhones()->contains($this)) { $companyPhone = new CompanyPhone();
            $companyPhone->setCompany($company);
            $this->addCompanyPhone($companyPhone);

            $foreignCollection = $company->getPhones();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Company object to this object
     * through the company_phone cross reference table.
     *
     * @param Company $company The CompanyPhone object to relate
     * @return Phone The current object (for fluent API support)
     */
    public function removeCompany(Company $company)
    {
        if ($this->getCompanies()->contains($company)) {
            $this->collCompanies->remove($this->collCompanies->search($company));
            if (null === $this->companiesScheduledForDeletion) {
                $this->companiesScheduledForDeletion = clone $this->collCompanies;
                $this->companiesScheduledForDeletion->clear();
            }
            $this->companiesScheduledForDeletion[]= $company;
        }

        return $this;
    }

    /**
     * Clears out the collStores collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Phone The current object (for fluent API support)
     * @see        addStores()
     */
    public function clearStores()
    {
        $this->collStores = null; // important to set this to null since that means it is uninitialized
        $this->collStoresPartial = null;

        return $this;
    }

    /**
     * Initializes the collStores collection.
     *
     * By default this just sets the collStores collection to an empty collection (like clearStores());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initStores()
    {
        $this->collStores = new PropelObjectCollection();
        $this->collStores->setModel('Store');
    }

    /**
     * Gets a collection of Store objects related by a many-to-many relationship
     * to the current object by way of the store_phone cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Phone is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Store[] List of Store objects
     */
    public function getStores($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collStores || null !== $criteria) {
            if ($this->isNew() && null === $this->collStores) {
                // return empty collection
                $this->initStores();
            } else {
                $collStores = StoreQuery::create(null, $criteria)
                    ->filterByPhone($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collStores;
                }
                $this->collStores = $collStores;
            }
        }

        return $this->collStores;
    }

    /**
     * Sets a collection of Store objects related by a many-to-many relationship
     * to the current object by way of the store_phone cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $stores A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Phone The current object (for fluent API support)
     */
    public function setStores(PropelCollection $stores, PropelPDO $con = null)
    {
        $this->clearStores();
        $currentStores = $this->getStores(null, $con);

        $this->storesScheduledForDeletion = $currentStores->diff($stores);

        foreach ($stores as $store) {
            if (!$currentStores->contains($store)) {
                $this->doAddStore($store);
            }
        }

        $this->collStores = $stores;

        return $this;
    }

    /**
     * Gets the number of Store objects related by a many-to-many relationship
     * to the current object by way of the store_phone cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Store objects
     */
    public function countStores($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collStores || null !== $criteria) {
            if ($this->isNew() && null === $this->collStores) {
                return 0;
            } else {
                $query = StoreQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPhone($this)
                    ->count($con);
            }
        } else {
            return count($this->collStores);
        }
    }

    /**
     * Associate a Store object to this object
     * through the store_phone cross reference table.
     *
     * @param  Store $store The StorePhone object to relate
     * @return Phone The current object (for fluent API support)
     */
    public function addStore(Store $store)
    {
        if ($this->collStores === null) {
            $this->initStores();
        }

        if (!$this->collStores->contains($store)) { // only add it if the **same** object is not already associated
            $this->doAddStore($store);
            $this->collStores[] = $store;

            if ($this->storesScheduledForDeletion and $this->storesScheduledForDeletion->contains($store)) {
                $this->storesScheduledForDeletion->remove($this->storesScheduledForDeletion->search($store));
            }
        }

        return $this;
    }

    /**
     * @param	Store $store The store object to add.
     */
    protected function doAddStore(Store $store)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$store->getPhones()->contains($this)) { $storePhone = new StorePhone();
            $storePhone->setStore($store);
            $this->addStorePhone($storePhone);

            $foreignCollection = $store->getPhones();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Store object to this object
     * through the store_phone cross reference table.
     *
     * @param Store $store The StorePhone object to relate
     * @return Phone The current object (for fluent API support)
     */
    public function removeStore(Store $store)
    {
        if ($this->getStores()->contains($store)) {
            $this->collStores->remove($this->collStores->search($store));
            if (null === $this->storesScheduledForDeletion) {
                $this->storesScheduledForDeletion = clone $this->collStores;
                $this->storesScheduledForDeletion->clear();
            }
            $this->storesScheduledForDeletion[]= $store;
        }

        return $this;
    }

    /**
     * Clears out the collUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Phone The current object (for fluent API support)
     * @see        addUsers()
     */
    public function clearUsers()
    {
        $this->collUsers = null; // important to set this to null since that means it is uninitialized
        $this->collUsersPartial = null;

        return $this;
    }

    /**
     * Initializes the collUsers collection.
     *
     * By default this just sets the collUsers collection to an empty collection (like clearUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initUsers()
    {
        $this->collUsers = new PropelObjectCollection();
        $this->collUsers->setModel('User');
    }

    /**
     * Gets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the user_phone cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Phone is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|User[] List of User objects
     */
    public function getUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collUsers) {
                // return empty collection
                $this->initUsers();
            } else {
                $collUsers = UserQuery::create(null, $criteria)
                    ->filterByPhone($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collUsers;
                }
                $this->collUsers = $collUsers;
            }
        }

        return $this->collUsers;
    }

    /**
     * Sets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the user_phone cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $users A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Phone The current object (for fluent API support)
     */
    public function setUsers(PropelCollection $users, PropelPDO $con = null)
    {
        $this->clearUsers();
        $currentUsers = $this->getUsers(null, $con);

        $this->usersScheduledForDeletion = $currentUsers->diff($users);

        foreach ($users as $user) {
            if (!$currentUsers->contains($user)) {
                $this->doAddUser($user);
            }
        }

        $this->collUsers = $users;

        return $this;
    }

    /**
     * Gets the number of User objects related by a many-to-many relationship
     * to the current object by way of the user_phone cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related User objects
     */
    public function countUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collUsers) {
                return 0;
            } else {
                $query = UserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPhone($this)
                    ->count($con);
            }
        } else {
            return count($this->collUsers);
        }
    }

    /**
     * Associate a User object to this object
     * through the user_phone cross reference table.
     *
     * @param  User $user The UserPhone object to relate
     * @return Phone The current object (for fluent API support)
     */
    public function addUser(User $user)
    {
        if ($this->collUsers === null) {
            $this->initUsers();
        }

        if (!$this->collUsers->contains($user)) { // only add it if the **same** object is not already associated
            $this->doAddUser($user);
            $this->collUsers[] = $user;

            if ($this->usersScheduledForDeletion and $this->usersScheduledForDeletion->contains($user)) {
                $this->usersScheduledForDeletion->remove($this->usersScheduledForDeletion->search($user));
            }
        }

        return $this;
    }

    /**
     * @param	User $user The user object to add.
     */
    protected function doAddUser(User $user)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$user->getPhones()->contains($this)) { $userPhone = new UserPhone();
            $userPhone->setUser($user);
            $this->addUserPhone($userPhone);

            $foreignCollection = $user->getPhones();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a User object to this object
     * through the user_phone cross reference table.
     *
     * @param User $user The UserPhone object to relate
     * @return Phone The current object (for fluent API support)
     */
    public function removeUser(User $user)
    {
        if ($this->getUsers()->contains($user)) {
            $this->collUsers->remove($this->collUsers->search($user));
            if (null === $this->usersScheduledForDeletion) {
                $this->usersScheduledForDeletion = clone $this->collUsers;
                $this->usersScheduledForDeletion->clear();
            }
            $this->usersScheduledForDeletion[]= $user;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->primary = null;
        $this->phone_number = null;
        $this->description = null;
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
            if ($this->collCompanyPhones) {
                foreach ($this->collCompanyPhones as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStorePhones) {
                foreach ($this->collStorePhones as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserPhones) {
                foreach ($this->collUserPhones as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCompanies) {
                foreach ($this->collCompanies as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStores) {
                foreach ($this->collStores as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsers) {
                foreach ($this->collUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collCompanyPhones instanceof PropelCollection) {
            $this->collCompanyPhones->clearIterator();
        }
        $this->collCompanyPhones = null;
        if ($this->collStorePhones instanceof PropelCollection) {
            $this->collStorePhones->clearIterator();
        }
        $this->collStorePhones = null;
        if ($this->collUserPhones instanceof PropelCollection) {
            $this->collUserPhones->clearIterator();
        }
        $this->collUserPhones = null;
        if ($this->collCompanies instanceof PropelCollection) {
            $this->collCompanies->clearIterator();
        }
        $this->collCompanies = null;
        if ($this->collStores instanceof PropelCollection) {
            $this->collStores->clearIterator();
        }
        $this->collStores = null;
        if ($this->collUsers instanceof PropelCollection) {
            $this->collUsers->clearIterator();
        }
        $this->collUsers = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PhonePeer::DEFAULT_STRING_FORMAT);
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
     * @return     Phone The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = PhonePeer::UPDATED_AT;

        return $this;
    }

}
