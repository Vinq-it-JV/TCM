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
use CompanyBundle\Model\CompanyEmail;
use CompanyBundle\Model\CompanyEmailQuery;
use CompanyBundle\Model\CompanyQuery;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreEmail;
use StoreBundle\Model\StoreEmailQuery;
use StoreBundle\Model\StoreQuery;
use UserBundle\Model\Email;
use UserBundle\Model\EmailPeer;
use UserBundle\Model\EmailQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserEmail;
use UserBundle\Model\UserEmailQuery;
use UserBundle\Model\UserQuery;

abstract class BaseEmail extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'UserBundle\\Model\\EmailPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        EmailPeer
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
     * The value for the email field.
     * @var        string
     */
    protected $email;

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
     * @var        PropelObjectCollection|CompanyEmail[] Collection to store aggregation of CompanyEmail objects.
     */
    protected $collCompanyEmails;
    protected $collCompanyEmailsPartial;

    /**
     * @var        PropelObjectCollection|StoreEmail[] Collection to store aggregation of StoreEmail objects.
     */
    protected $collStoreEmails;
    protected $collStoreEmailsPartial;

    /**
     * @var        PropelObjectCollection|UserEmail[] Collection to store aggregation of UserEmail objects.
     */
    protected $collUserEmails;
    protected $collUserEmailsPartial;

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
    protected $companyEmailsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $storeEmailsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userEmailsScheduledForDeletion = null;

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
     * Initializes internal state of BaseEmail object.
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
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {

        return $this->email;
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
     * @return Email The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = EmailPeer::ID;
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
     * @return Email The current object (for fluent API support)
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
            $this->modifiedColumns[] = EmailPeer::PRIMARY;
        }


        return $this;
    } // setPrimary()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return Email The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[] = EmailPeer::EMAIL;
        }


        return $this;
    } // setEmail()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return Email The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = EmailPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Email The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = EmailPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Email The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = EmailPeer::UPDATED_AT;
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
            $this->email = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->description = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->created_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->updated_at = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 6; // 6 = EmailPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Email object", $e);
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
            $con = Propel::getConnection(EmailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = EmailPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCompanyEmails = null;

            $this->collStoreEmails = null;

            $this->collUserEmails = null;

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
            $con = Propel::getConnection(EmailPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = EmailQuery::create()
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
            $con = Propel::getConnection(EmailPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(EmailPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(EmailPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(EmailPeer::UPDATED_AT)) {
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
                EmailPeer::addInstanceToPool($this);
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
                    CompanyEmailQuery::create()
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
                    StoreEmailQuery::create()
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
                    UserEmailQuery::create()
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

            if ($this->companyEmailsScheduledForDeletion !== null) {
                if (!$this->companyEmailsScheduledForDeletion->isEmpty()) {
                    CompanyEmailQuery::create()
                        ->filterByPrimaryKeys($this->companyEmailsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->companyEmailsScheduledForDeletion = null;
                }
            }

            if ($this->collCompanyEmails !== null) {
                foreach ($this->collCompanyEmails as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->storeEmailsScheduledForDeletion !== null) {
                if (!$this->storeEmailsScheduledForDeletion->isEmpty()) {
                    StoreEmailQuery::create()
                        ->filterByPrimaryKeys($this->storeEmailsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->storeEmailsScheduledForDeletion = null;
                }
            }

            if ($this->collStoreEmails !== null) {
                foreach ($this->collStoreEmails as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userEmailsScheduledForDeletion !== null) {
                if (!$this->userEmailsScheduledForDeletion->isEmpty()) {
                    UserEmailQuery::create()
                        ->filterByPrimaryKeys($this->userEmailsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userEmailsScheduledForDeletion = null;
                }
            }

            if ($this->collUserEmails !== null) {
                foreach ($this->collUserEmails as $referrerFK) {
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

        $this->modifiedColumns[] = EmailPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EmailPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EmailPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(EmailPeer::PRIMARY)) {
            $modifiedColumns[':p' . $index++]  = '`primary`';
        }
        if ($this->isColumnModified(EmailPeer::EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }
        if ($this->isColumnModified(EmailPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(EmailPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(EmailPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `email` (%s) VALUES (%s)',
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
                    case '`email`':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
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


            if (($retval = EmailPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCompanyEmails !== null) {
                    foreach ($this->collCompanyEmails as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStoreEmails !== null) {
                    foreach ($this->collStoreEmails as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUserEmails !== null) {
                    foreach ($this->collUserEmails as $referrerFK) {
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
        $pos = EmailPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getEmail();
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
        if (isset($alreadyDumpedObjects['Email'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Email'][$this->getPrimaryKey()] = true;
        $keys = EmailPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPrimary(),
            $keys[2] => $this->getEmail(),
            $keys[3] => $this->getDescription(),
            $keys[4] => $this->getCreatedAt(),
            $keys[5] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collCompanyEmails) {
                $result['CompanyEmails'] = $this->collCompanyEmails->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStoreEmails) {
                $result['StoreEmails'] = $this->collStoreEmails->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserEmails) {
                $result['UserEmails'] = $this->collUserEmails->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = EmailPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setEmail($value);
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
        $keys = EmailPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setPrimary($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setEmail($arr[$keys[2]]);
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
        $criteria = new Criteria(EmailPeer::DATABASE_NAME);

        if ($this->isColumnModified(EmailPeer::ID)) $criteria->add(EmailPeer::ID, $this->id);
        if ($this->isColumnModified(EmailPeer::PRIMARY)) $criteria->add(EmailPeer::PRIMARY, $this->primary);
        if ($this->isColumnModified(EmailPeer::EMAIL)) $criteria->add(EmailPeer::EMAIL, $this->email);
        if ($this->isColumnModified(EmailPeer::DESCRIPTION)) $criteria->add(EmailPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(EmailPeer::CREATED_AT)) $criteria->add(EmailPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(EmailPeer::UPDATED_AT)) $criteria->add(EmailPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(EmailPeer::DATABASE_NAME);
        $criteria->add(EmailPeer::ID, $this->id);

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
     * @param object $copyObj An object of Email (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPrimary($this->getPrimary());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCompanyEmails() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCompanyEmail($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStoreEmails() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStoreEmail($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserEmails() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserEmail($relObj->copy($deepCopy));
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
     * @return Email Clone of current object.
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
     * @return EmailPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new EmailPeer();
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
        if ('CompanyEmail' == $relationName) {
            $this->initCompanyEmails();
        }
        if ('StoreEmail' == $relationName) {
            $this->initStoreEmails();
        }
        if ('UserEmail' == $relationName) {
            $this->initUserEmails();
        }
    }

    /**
     * Clears out the collCompanyEmails collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Email The current object (for fluent API support)
     * @see        addCompanyEmails()
     */
    public function clearCompanyEmails()
    {
        $this->collCompanyEmails = null; // important to set this to null since that means it is uninitialized
        $this->collCompanyEmailsPartial = null;

        return $this;
    }

    /**
     * reset is the collCompanyEmails collection loaded partially
     *
     * @return void
     */
    public function resetPartialCompanyEmails($v = true)
    {
        $this->collCompanyEmailsPartial = $v;
    }

    /**
     * Initializes the collCompanyEmails collection.
     *
     * By default this just sets the collCompanyEmails collection to an empty array (like clearcollCompanyEmails());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCompanyEmails($overrideExisting = true)
    {
        if (null !== $this->collCompanyEmails && !$overrideExisting) {
            return;
        }
        $this->collCompanyEmails = new PropelObjectCollection();
        $this->collCompanyEmails->setModel('CompanyEmail');
    }

    /**
     * Gets an array of CompanyEmail objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Email is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CompanyEmail[] List of CompanyEmail objects
     * @throws PropelException
     */
    public function getCompanyEmails($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCompanyEmailsPartial && !$this->isNew();
        if (null === $this->collCompanyEmails || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCompanyEmails) {
                // return empty collection
                $this->initCompanyEmails();
            } else {
                $collCompanyEmails = CompanyEmailQuery::create(null, $criteria)
                    ->filterByEmail($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCompanyEmailsPartial && count($collCompanyEmails)) {
                      $this->initCompanyEmails(false);

                      foreach ($collCompanyEmails as $obj) {
                        if (false == $this->collCompanyEmails->contains($obj)) {
                          $this->collCompanyEmails->append($obj);
                        }
                      }

                      $this->collCompanyEmailsPartial = true;
                    }

                    $collCompanyEmails->getInternalIterator()->rewind();

                    return $collCompanyEmails;
                }

                if ($partial && $this->collCompanyEmails) {
                    foreach ($this->collCompanyEmails as $obj) {
                        if ($obj->isNew()) {
                            $collCompanyEmails[] = $obj;
                        }
                    }
                }

                $this->collCompanyEmails = $collCompanyEmails;
                $this->collCompanyEmailsPartial = false;
            }
        }

        return $this->collCompanyEmails;
    }

    /**
     * Sets a collection of CompanyEmail objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $companyEmails A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Email The current object (for fluent API support)
     */
    public function setCompanyEmails(PropelCollection $companyEmails, PropelPDO $con = null)
    {
        $companyEmailsToDelete = $this->getCompanyEmails(new Criteria(), $con)->diff($companyEmails);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->companyEmailsScheduledForDeletion = clone $companyEmailsToDelete;

        foreach ($companyEmailsToDelete as $companyEmailRemoved) {
            $companyEmailRemoved->setEmail(null);
        }

        $this->collCompanyEmails = null;
        foreach ($companyEmails as $companyEmail) {
            $this->addCompanyEmail($companyEmail);
        }

        $this->collCompanyEmails = $companyEmails;
        $this->collCompanyEmailsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CompanyEmail objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CompanyEmail objects.
     * @throws PropelException
     */
    public function countCompanyEmails(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCompanyEmailsPartial && !$this->isNew();
        if (null === $this->collCompanyEmails || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCompanyEmails) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCompanyEmails());
            }
            $query = CompanyEmailQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmail($this)
                ->count($con);
        }

        return count($this->collCompanyEmails);
    }

    /**
     * Method called to associate a CompanyEmail object to this object
     * through the CompanyEmail foreign key attribute.
     *
     * @param    CompanyEmail $l CompanyEmail
     * @return Email The current object (for fluent API support)
     */
    public function addCompanyEmail(CompanyEmail $l)
    {
        if ($this->collCompanyEmails === null) {
            $this->initCompanyEmails();
            $this->collCompanyEmailsPartial = true;
        }

        if (!in_array($l, $this->collCompanyEmails->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCompanyEmail($l);

            if ($this->companyEmailsScheduledForDeletion and $this->companyEmailsScheduledForDeletion->contains($l)) {
                $this->companyEmailsScheduledForDeletion->remove($this->companyEmailsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CompanyEmail $companyEmail The companyEmail object to add.
     */
    protected function doAddCompanyEmail($companyEmail)
    {
        $this->collCompanyEmails[]= $companyEmail;
        $companyEmail->setEmail($this);
    }

    /**
     * @param	CompanyEmail $companyEmail The companyEmail object to remove.
     * @return Email The current object (for fluent API support)
     */
    public function removeCompanyEmail($companyEmail)
    {
        if ($this->getCompanyEmails()->contains($companyEmail)) {
            $this->collCompanyEmails->remove($this->collCompanyEmails->search($companyEmail));
            if (null === $this->companyEmailsScheduledForDeletion) {
                $this->companyEmailsScheduledForDeletion = clone $this->collCompanyEmails;
                $this->companyEmailsScheduledForDeletion->clear();
            }
            $this->companyEmailsScheduledForDeletion[]= clone $companyEmail;
            $companyEmail->setEmail(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Email is new, it will return
     * an empty collection; or if this Email has previously
     * been saved, it will retrieve related CompanyEmails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Email.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CompanyEmail[] List of CompanyEmail objects
     */
    public function getCompanyEmailsJoinCompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CompanyEmailQuery::create(null, $criteria);
        $query->joinWith('Company', $join_behavior);

        return $this->getCompanyEmails($query, $con);
    }

    /**
     * Clears out the collStoreEmails collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Email The current object (for fluent API support)
     * @see        addStoreEmails()
     */
    public function clearStoreEmails()
    {
        $this->collStoreEmails = null; // important to set this to null since that means it is uninitialized
        $this->collStoreEmailsPartial = null;

        return $this;
    }

    /**
     * reset is the collStoreEmails collection loaded partially
     *
     * @return void
     */
    public function resetPartialStoreEmails($v = true)
    {
        $this->collStoreEmailsPartial = $v;
    }

    /**
     * Initializes the collStoreEmails collection.
     *
     * By default this just sets the collStoreEmails collection to an empty array (like clearcollStoreEmails());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStoreEmails($overrideExisting = true)
    {
        if (null !== $this->collStoreEmails && !$overrideExisting) {
            return;
        }
        $this->collStoreEmails = new PropelObjectCollection();
        $this->collStoreEmails->setModel('StoreEmail');
    }

    /**
     * Gets an array of StoreEmail objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Email is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|StoreEmail[] List of StoreEmail objects
     * @throws PropelException
     */
    public function getStoreEmails($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStoreEmailsPartial && !$this->isNew();
        if (null === $this->collStoreEmails || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStoreEmails) {
                // return empty collection
                $this->initStoreEmails();
            } else {
                $collStoreEmails = StoreEmailQuery::create(null, $criteria)
                    ->filterByEmail($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStoreEmailsPartial && count($collStoreEmails)) {
                      $this->initStoreEmails(false);

                      foreach ($collStoreEmails as $obj) {
                        if (false == $this->collStoreEmails->contains($obj)) {
                          $this->collStoreEmails->append($obj);
                        }
                      }

                      $this->collStoreEmailsPartial = true;
                    }

                    $collStoreEmails->getInternalIterator()->rewind();

                    return $collStoreEmails;
                }

                if ($partial && $this->collStoreEmails) {
                    foreach ($this->collStoreEmails as $obj) {
                        if ($obj->isNew()) {
                            $collStoreEmails[] = $obj;
                        }
                    }
                }

                $this->collStoreEmails = $collStoreEmails;
                $this->collStoreEmailsPartial = false;
            }
        }

        return $this->collStoreEmails;
    }

    /**
     * Sets a collection of StoreEmail objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $storeEmails A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Email The current object (for fluent API support)
     */
    public function setStoreEmails(PropelCollection $storeEmails, PropelPDO $con = null)
    {
        $storeEmailsToDelete = $this->getStoreEmails(new Criteria(), $con)->diff($storeEmails);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->storeEmailsScheduledForDeletion = clone $storeEmailsToDelete;

        foreach ($storeEmailsToDelete as $storeEmailRemoved) {
            $storeEmailRemoved->setEmail(null);
        }

        $this->collStoreEmails = null;
        foreach ($storeEmails as $storeEmail) {
            $this->addStoreEmail($storeEmail);
        }

        $this->collStoreEmails = $storeEmails;
        $this->collStoreEmailsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StoreEmail objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related StoreEmail objects.
     * @throws PropelException
     */
    public function countStoreEmails(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStoreEmailsPartial && !$this->isNew();
        if (null === $this->collStoreEmails || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStoreEmails) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStoreEmails());
            }
            $query = StoreEmailQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmail($this)
                ->count($con);
        }

        return count($this->collStoreEmails);
    }

    /**
     * Method called to associate a StoreEmail object to this object
     * through the StoreEmail foreign key attribute.
     *
     * @param    StoreEmail $l StoreEmail
     * @return Email The current object (for fluent API support)
     */
    public function addStoreEmail(StoreEmail $l)
    {
        if ($this->collStoreEmails === null) {
            $this->initStoreEmails();
            $this->collStoreEmailsPartial = true;
        }

        if (!in_array($l, $this->collStoreEmails->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStoreEmail($l);

            if ($this->storeEmailsScheduledForDeletion and $this->storeEmailsScheduledForDeletion->contains($l)) {
                $this->storeEmailsScheduledForDeletion->remove($this->storeEmailsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	StoreEmail $storeEmail The storeEmail object to add.
     */
    protected function doAddStoreEmail($storeEmail)
    {
        $this->collStoreEmails[]= $storeEmail;
        $storeEmail->setEmail($this);
    }

    /**
     * @param	StoreEmail $storeEmail The storeEmail object to remove.
     * @return Email The current object (for fluent API support)
     */
    public function removeStoreEmail($storeEmail)
    {
        if ($this->getStoreEmails()->contains($storeEmail)) {
            $this->collStoreEmails->remove($this->collStoreEmails->search($storeEmail));
            if (null === $this->storeEmailsScheduledForDeletion) {
                $this->storeEmailsScheduledForDeletion = clone $this->collStoreEmails;
                $this->storeEmailsScheduledForDeletion->clear();
            }
            $this->storeEmailsScheduledForDeletion[]= clone $storeEmail;
            $storeEmail->setEmail(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Email is new, it will return
     * an empty collection; or if this Email has previously
     * been saved, it will retrieve related StoreEmails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Email.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StoreEmail[] List of StoreEmail objects
     */
    public function getStoreEmailsJoinStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreEmailQuery::create(null, $criteria);
        $query->joinWith('Store', $join_behavior);

        return $this->getStoreEmails($query, $con);
    }

    /**
     * Clears out the collUserEmails collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Email The current object (for fluent API support)
     * @see        addUserEmails()
     */
    public function clearUserEmails()
    {
        $this->collUserEmails = null; // important to set this to null since that means it is uninitialized
        $this->collUserEmailsPartial = null;

        return $this;
    }

    /**
     * reset is the collUserEmails collection loaded partially
     *
     * @return void
     */
    public function resetPartialUserEmails($v = true)
    {
        $this->collUserEmailsPartial = $v;
    }

    /**
     * Initializes the collUserEmails collection.
     *
     * By default this just sets the collUserEmails collection to an empty array (like clearcollUserEmails());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserEmails($overrideExisting = true)
    {
        if (null !== $this->collUserEmails && !$overrideExisting) {
            return;
        }
        $this->collUserEmails = new PropelObjectCollection();
        $this->collUserEmails->setModel('UserEmail');
    }

    /**
     * Gets an array of UserEmail objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Email is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserEmail[] List of UserEmail objects
     * @throws PropelException
     */
    public function getUserEmails($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserEmailsPartial && !$this->isNew();
        if (null === $this->collUserEmails || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserEmails) {
                // return empty collection
                $this->initUserEmails();
            } else {
                $collUserEmails = UserEmailQuery::create(null, $criteria)
                    ->filterByEmail($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUserEmailsPartial && count($collUserEmails)) {
                      $this->initUserEmails(false);

                      foreach ($collUserEmails as $obj) {
                        if (false == $this->collUserEmails->contains($obj)) {
                          $this->collUserEmails->append($obj);
                        }
                      }

                      $this->collUserEmailsPartial = true;
                    }

                    $collUserEmails->getInternalIterator()->rewind();

                    return $collUserEmails;
                }

                if ($partial && $this->collUserEmails) {
                    foreach ($this->collUserEmails as $obj) {
                        if ($obj->isNew()) {
                            $collUserEmails[] = $obj;
                        }
                    }
                }

                $this->collUserEmails = $collUserEmails;
                $this->collUserEmailsPartial = false;
            }
        }

        return $this->collUserEmails;
    }

    /**
     * Sets a collection of UserEmail objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userEmails A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Email The current object (for fluent API support)
     */
    public function setUserEmails(PropelCollection $userEmails, PropelPDO $con = null)
    {
        $userEmailsToDelete = $this->getUserEmails(new Criteria(), $con)->diff($userEmails);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userEmailsScheduledForDeletion = clone $userEmailsToDelete;

        foreach ($userEmailsToDelete as $userEmailRemoved) {
            $userEmailRemoved->setEmail(null);
        }

        $this->collUserEmails = null;
        foreach ($userEmails as $userEmail) {
            $this->addUserEmail($userEmail);
        }

        $this->collUserEmails = $userEmails;
        $this->collUserEmailsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserEmail objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserEmail objects.
     * @throws PropelException
     */
    public function countUserEmails(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserEmailsPartial && !$this->isNew();
        if (null === $this->collUserEmails || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserEmails) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserEmails());
            }
            $query = UserEmailQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEmail($this)
                ->count($con);
        }

        return count($this->collUserEmails);
    }

    /**
     * Method called to associate a UserEmail object to this object
     * through the UserEmail foreign key attribute.
     *
     * @param    UserEmail $l UserEmail
     * @return Email The current object (for fluent API support)
     */
    public function addUserEmail(UserEmail $l)
    {
        if ($this->collUserEmails === null) {
            $this->initUserEmails();
            $this->collUserEmailsPartial = true;
        }

        if (!in_array($l, $this->collUserEmails->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserEmail($l);

            if ($this->userEmailsScheduledForDeletion and $this->userEmailsScheduledForDeletion->contains($l)) {
                $this->userEmailsScheduledForDeletion->remove($this->userEmailsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	UserEmail $userEmail The userEmail object to add.
     */
    protected function doAddUserEmail($userEmail)
    {
        $this->collUserEmails[]= $userEmail;
        $userEmail->setEmail($this);
    }

    /**
     * @param	UserEmail $userEmail The userEmail object to remove.
     * @return Email The current object (for fluent API support)
     */
    public function removeUserEmail($userEmail)
    {
        if ($this->getUserEmails()->contains($userEmail)) {
            $this->collUserEmails->remove($this->collUserEmails->search($userEmail));
            if (null === $this->userEmailsScheduledForDeletion) {
                $this->userEmailsScheduledForDeletion = clone $this->collUserEmails;
                $this->userEmailsScheduledForDeletion->clear();
            }
            $this->userEmailsScheduledForDeletion[]= clone $userEmail;
            $userEmail->setEmail(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Email is new, it will return
     * an empty collection; or if this Email has previously
     * been saved, it will retrieve related UserEmails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Email.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserEmail[] List of UserEmail objects
     */
    public function getUserEmailsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserEmailQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getUserEmails($query, $con);
    }

    /**
     * Clears out the collCompanies collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Email The current object (for fluent API support)
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
     * to the current object by way of the company_email cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Email is new, it will return
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
                    ->filterByEmail($this)
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
     * to the current object by way of the company_email cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $companies A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Email The current object (for fluent API support)
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
     * to the current object by way of the company_email cross-reference table.
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
                    ->filterByEmail($this)
                    ->count($con);
            }
        } else {
            return count($this->collCompanies);
        }
    }

    /**
     * Associate a Company object to this object
     * through the company_email cross reference table.
     *
     * @param  Company $company The CompanyEmail object to relate
     * @return Email The current object (for fluent API support)
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
        if (!$company->getEmails()->contains($this)) { $companyEmail = new CompanyEmail();
            $companyEmail->setCompany($company);
            $this->addCompanyEmail($companyEmail);

            $foreignCollection = $company->getEmails();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Company object to this object
     * through the company_email cross reference table.
     *
     * @param Company $company The CompanyEmail object to relate
     * @return Email The current object (for fluent API support)
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
     * @return Email The current object (for fluent API support)
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
     * to the current object by way of the store_email cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Email is new, it will return
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
                    ->filterByEmail($this)
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
     * to the current object by way of the store_email cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $stores A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Email The current object (for fluent API support)
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
     * to the current object by way of the store_email cross-reference table.
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
                    ->filterByEmail($this)
                    ->count($con);
            }
        } else {
            return count($this->collStores);
        }
    }

    /**
     * Associate a Store object to this object
     * through the store_email cross reference table.
     *
     * @param  Store $store The StoreEmail object to relate
     * @return Email The current object (for fluent API support)
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
        if (!$store->getEmails()->contains($this)) { $storeEmail = new StoreEmail();
            $storeEmail->setStore($store);
            $this->addStoreEmail($storeEmail);

            $foreignCollection = $store->getEmails();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Store object to this object
     * through the store_email cross reference table.
     *
     * @param Store $store The StoreEmail object to relate
     * @return Email The current object (for fluent API support)
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
     * @return Email The current object (for fluent API support)
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
     * to the current object by way of the user_email cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Email is new, it will return
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
                    ->filterByEmail($this)
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
     * to the current object by way of the user_email cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $users A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Email The current object (for fluent API support)
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
     * to the current object by way of the user_email cross-reference table.
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
                    ->filterByEmail($this)
                    ->count($con);
            }
        } else {
            return count($this->collUsers);
        }
    }

    /**
     * Associate a User object to this object
     * through the user_email cross reference table.
     *
     * @param  User $user The UserEmail object to relate
     * @return Email The current object (for fluent API support)
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
        if (!$user->getEmails()->contains($this)) { $userEmail = new UserEmail();
            $userEmail->setUser($user);
            $this->addUserEmail($userEmail);

            $foreignCollection = $user->getEmails();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a User object to this object
     * through the user_email cross reference table.
     *
     * @param User $user The UserEmail object to relate
     * @return Email The current object (for fluent API support)
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
        $this->email = null;
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
            if ($this->collCompanyEmails) {
                foreach ($this->collCompanyEmails as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStoreEmails) {
                foreach ($this->collStoreEmails as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserEmails) {
                foreach ($this->collUserEmails as $o) {
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

        if ($this->collCompanyEmails instanceof PropelCollection) {
            $this->collCompanyEmails->clearIterator();
        }
        $this->collCompanyEmails = null;
        if ($this->collStoreEmails instanceof PropelCollection) {
            $this->collStoreEmails->clearIterator();
        }
        $this->collStoreEmails = null;
        if ($this->collUserEmails instanceof PropelCollection) {
            $this->collUserEmails->clearIterator();
        }
        $this->collUserEmails = null;
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
        return (string) $this->exportTo(EmailPeer::DEFAULT_STRING_FORMAT);
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
     * @return     Email The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = EmailPeer::UPDATED_AT;

        return $this;
    }

}
