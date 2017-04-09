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
use CompanyBundle\Model\CompanyAddress;
use CompanyBundle\Model\CompanyAddressQuery;
use CompanyBundle\Model\CompanyQuery;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreAddress;
use StoreBundle\Model\StoreAddressQuery;
use StoreBundle\Model\StoreQuery;
use UserBundle\Model\Address;
use UserBundle\Model\AddressPeer;
use UserBundle\Model\AddressQuery;
use UserBundle\Model\Countries;
use UserBundle\Model\CountriesQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserAddress;
use UserBundle\Model\UserAddressQuery;
use UserBundle\Model\UserQuery;

abstract class BaseAddress extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'UserBundle\\Model\\AddressPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        AddressPeer
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
     * The value for the type field.
     * @var        int
     */
    protected $type;

    /**
     * The value for the street_name field.
     * @var        string
     */
    protected $street_name;

    /**
     * The value for the house_number field.
     * @var        string
     */
    protected $house_number;

    /**
     * The value for the extra_info field.
     * @var        string
     */
    protected $extra_info;

    /**
     * The value for the postal_code field.
     * @var        string
     */
    protected $postal_code;

    /**
     * The value for the city field.
     * @var        string
     */
    protected $city;

    /**
     * The value for the country field.
     * @var        int
     */
    protected $country;

    /**
     * The value for the map_url field.
     * @var        string
     */
    protected $map_url;

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
     * @var        Countries
     */
    protected $aCountries;

    /**
     * @var        PropelObjectCollection|CompanyAddress[] Collection to store aggregation of CompanyAddress objects.
     */
    protected $collCompanyAddresses;
    protected $collCompanyAddressesPartial;

    /**
     * @var        PropelObjectCollection|StoreAddress[] Collection to store aggregation of StoreAddress objects.
     */
    protected $collStoreAddresses;
    protected $collStoreAddressesPartial;

    /**
     * @var        PropelObjectCollection|UserAddress[] Collection to store aggregation of UserAddress objects.
     */
    protected $collUserAddresses;
    protected $collUserAddressesPartial;

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
    protected $companyAddressesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $storeAddressesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userAddressesScheduledForDeletion = null;

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
     * Get the [type] column value.
     *
     * @return int
     */
    public function getType()
    {

        return $this->type;
    }

    /**
     * Get the [street_name] column value.
     *
     * @return string
     */
    public function getStreetName()
    {

        return $this->street_name;
    }

    /**
     * Get the [house_number] column value.
     *
     * @return string
     */
    public function getHouseNumber()
    {

        return $this->house_number;
    }

    /**
     * Get the [extra_info] column value.
     *
     * @return string
     */
    public function getExtraInfo()
    {

        return $this->extra_info;
    }

    /**
     * Get the [postal_code] column value.
     *
     * @return string
     */
    public function getPostalCode()
    {

        return $this->postal_code;
    }

    /**
     * Get the [city] column value.
     *
     * @return string
     */
    public function getCity()
    {

        return $this->city;
    }

    /**
     * Get the [country] column value.
     *
     * @return int
     */
    public function getCountry()
    {

        return $this->country;
    }

    /**
     * Get the [map_url] column value.
     *
     * @return string
     */
    public function getMapUrl()
    {

        return $this->map_url;
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
     * @return Address The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = AddressPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [type] column.
     *
     * @param  int $v new value
     * @return Address The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[] = AddressPeer::TYPE;
        }


        return $this;
    } // setType()

    /**
     * Set the value of [street_name] column.
     *
     * @param  string $v new value
     * @return Address The current object (for fluent API support)
     */
    public function setStreetName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->street_name !== $v) {
            $this->street_name = $v;
            $this->modifiedColumns[] = AddressPeer::STREET_NAME;
        }


        return $this;
    } // setStreetName()

    /**
     * Set the value of [house_number] column.
     *
     * @param  string $v new value
     * @return Address The current object (for fluent API support)
     */
    public function setHouseNumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->house_number !== $v) {
            $this->house_number = $v;
            $this->modifiedColumns[] = AddressPeer::HOUSE_NUMBER;
        }


        return $this;
    } // setHouseNumber()

    /**
     * Set the value of [extra_info] column.
     *
     * @param  string $v new value
     * @return Address The current object (for fluent API support)
     */
    public function setExtraInfo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->extra_info !== $v) {
            $this->extra_info = $v;
            $this->modifiedColumns[] = AddressPeer::EXTRA_INFO;
        }


        return $this;
    } // setExtraInfo()

    /**
     * Set the value of [postal_code] column.
     *
     * @param  string $v new value
     * @return Address The current object (for fluent API support)
     */
    public function setPostalCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->postal_code !== $v) {
            $this->postal_code = $v;
            $this->modifiedColumns[] = AddressPeer::POSTAL_CODE;
        }


        return $this;
    } // setPostalCode()

    /**
     * Set the value of [city] column.
     *
     * @param  string $v new value
     * @return Address The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[] = AddressPeer::CITY;
        }


        return $this;
    } // setCity()

    /**
     * Set the value of [country] column.
     *
     * @param  int $v new value
     * @return Address The current object (for fluent API support)
     */
    public function setCountry($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->country !== $v) {
            $this->country = $v;
            $this->modifiedColumns[] = AddressPeer::COUNTRY;
        }

        if ($this->aCountries !== null && $this->aCountries->getId() !== $v) {
            $this->aCountries = null;
        }


        return $this;
    } // setCountry()

    /**
     * Set the value of [map_url] column.
     *
     * @param  string $v new value
     * @return Address The current object (for fluent API support)
     */
    public function setMapUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->map_url !== $v) {
            $this->map_url = $v;
            $this->modifiedColumns[] = AddressPeer::MAP_URL;
        }


        return $this;
    } // setMapUrl()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Address The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = AddressPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Address The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = AddressPeer::UPDATED_AT;
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
            $this->type = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->street_name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->house_number = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->extra_info = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->postal_code = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->city = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->country = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->map_url = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->created_at = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->updated_at = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 11; // 11 = AddressPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Address object", $e);
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

        if ($this->aCountries !== null && $this->country !== $this->aCountries->getId()) {
            $this->aCountries = null;
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
            $con = Propel::getConnection(AddressPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = AddressPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCountries = null;
            $this->collCompanyAddresses = null;

            $this->collStoreAddresses = null;

            $this->collUserAddresses = null;

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
            $con = Propel::getConnection(AddressPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = AddressQuery::create()
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
            $con = Propel::getConnection(AddressPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(AddressPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(AddressPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(AddressPeer::UPDATED_AT)) {
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
                AddressPeer::addInstanceToPool($this);
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

            if ($this->aCountries !== null) {
                if ($this->aCountries->isModified() || $this->aCountries->isNew()) {
                    $affectedRows += $this->aCountries->save($con);
                }
                $this->setCountries($this->aCountries);
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

            if ($this->companiesScheduledForDeletion !== null) {
                if (!$this->companiesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->companiesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    CompanyAddressQuery::create()
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
                    StoreAddressQuery::create()
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
                    UserAddressQuery::create()
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

            if ($this->companyAddressesScheduledForDeletion !== null) {
                if (!$this->companyAddressesScheduledForDeletion->isEmpty()) {
                    CompanyAddressQuery::create()
                        ->filterByPrimaryKeys($this->companyAddressesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->companyAddressesScheduledForDeletion = null;
                }
            }

            if ($this->collCompanyAddresses !== null) {
                foreach ($this->collCompanyAddresses as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->storeAddressesScheduledForDeletion !== null) {
                if (!$this->storeAddressesScheduledForDeletion->isEmpty()) {
                    StoreAddressQuery::create()
                        ->filterByPrimaryKeys($this->storeAddressesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->storeAddressesScheduledForDeletion = null;
                }
            }

            if ($this->collStoreAddresses !== null) {
                foreach ($this->collStoreAddresses as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userAddressesScheduledForDeletion !== null) {
                if (!$this->userAddressesScheduledForDeletion->isEmpty()) {
                    UserAddressQuery::create()
                        ->filterByPrimaryKeys($this->userAddressesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userAddressesScheduledForDeletion = null;
                }
            }

            if ($this->collUserAddresses !== null) {
                foreach ($this->collUserAddresses as $referrerFK) {
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

        $this->modifiedColumns[] = AddressPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . AddressPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(AddressPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(AddressPeer::TYPE)) {
            $modifiedColumns[':p' . $index++]  = '`type`';
        }
        if ($this->isColumnModified(AddressPeer::STREET_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`street_name`';
        }
        if ($this->isColumnModified(AddressPeer::HOUSE_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = '`house_number`';
        }
        if ($this->isColumnModified(AddressPeer::EXTRA_INFO)) {
            $modifiedColumns[':p' . $index++]  = '`extra_info`';
        }
        if ($this->isColumnModified(AddressPeer::POSTAL_CODE)) {
            $modifiedColumns[':p' . $index++]  = '`postal_code`';
        }
        if ($this->isColumnModified(AddressPeer::CITY)) {
            $modifiedColumns[':p' . $index++]  = '`city`';
        }
        if ($this->isColumnModified(AddressPeer::COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = '`country`';
        }
        if ($this->isColumnModified(AddressPeer::MAP_URL)) {
            $modifiedColumns[':p' . $index++]  = '`map_url`';
        }
        if ($this->isColumnModified(AddressPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(AddressPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `address` (%s) VALUES (%s)',
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
                    case '`type`':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_INT);
                        break;
                    case '`street_name`':
                        $stmt->bindValue($identifier, $this->street_name, PDO::PARAM_STR);
                        break;
                    case '`house_number`':
                        $stmt->bindValue($identifier, $this->house_number, PDO::PARAM_STR);
                        break;
                    case '`extra_info`':
                        $stmt->bindValue($identifier, $this->extra_info, PDO::PARAM_STR);
                        break;
                    case '`postal_code`':
                        $stmt->bindValue($identifier, $this->postal_code, PDO::PARAM_STR);
                        break;
                    case '`city`':
                        $stmt->bindValue($identifier, $this->city, PDO::PARAM_STR);
                        break;
                    case '`country`':
                        $stmt->bindValue($identifier, $this->country, PDO::PARAM_INT);
                        break;
                    case '`map_url`':
                        $stmt->bindValue($identifier, $this->map_url, PDO::PARAM_STR);
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

            if ($this->aCountries !== null) {
                if (!$this->aCountries->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCountries->getValidationFailures());
                }
            }


            if (($retval = AddressPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCompanyAddresses !== null) {
                    foreach ($this->collCompanyAddresses as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStoreAddresses !== null) {
                    foreach ($this->collStoreAddresses as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUserAddresses !== null) {
                    foreach ($this->collUserAddresses as $referrerFK) {
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
        $pos = AddressPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getType();
                break;
            case 2:
                return $this->getStreetName();
                break;
            case 3:
                return $this->getHouseNumber();
                break;
            case 4:
                return $this->getExtraInfo();
                break;
            case 5:
                return $this->getPostalCode();
                break;
            case 6:
                return $this->getCity();
                break;
            case 7:
                return $this->getCountry();
                break;
            case 8:
                return $this->getMapUrl();
                break;
            case 9:
                return $this->getCreatedAt();
                break;
            case 10:
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
        if (isset($alreadyDumpedObjects['Address'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Address'][$this->getPrimaryKey()] = true;
        $keys = AddressPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getType(),
            $keys[2] => $this->getStreetName(),
            $keys[3] => $this->getHouseNumber(),
            $keys[4] => $this->getExtraInfo(),
            $keys[5] => $this->getPostalCode(),
            $keys[6] => $this->getCity(),
            $keys[7] => $this->getCountry(),
            $keys[8] => $this->getMapUrl(),
            $keys[9] => $this->getCreatedAt(),
            $keys[10] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCountries) {
                $result['Countries'] = $this->aCountries->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCompanyAddresses) {
                $result['CompanyAddresses'] = $this->collCompanyAddresses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStoreAddresses) {
                $result['StoreAddresses'] = $this->collStoreAddresses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserAddresses) {
                $result['UserAddresses'] = $this->collUserAddresses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = AddressPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setType($value);
                break;
            case 2:
                $this->setStreetName($value);
                break;
            case 3:
                $this->setHouseNumber($value);
                break;
            case 4:
                $this->setExtraInfo($value);
                break;
            case 5:
                $this->setPostalCode($value);
                break;
            case 6:
                $this->setCity($value);
                break;
            case 7:
                $this->setCountry($value);
                break;
            case 8:
                $this->setMapUrl($value);
                break;
            case 9:
                $this->setCreatedAt($value);
                break;
            case 10:
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
        $keys = AddressPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setType($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setStreetName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setHouseNumber($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setExtraInfo($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setPostalCode($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setCity($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setCountry($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setMapUrl($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setCreatedAt($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setUpdatedAt($arr[$keys[10]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(AddressPeer::DATABASE_NAME);

        if ($this->isColumnModified(AddressPeer::ID)) $criteria->add(AddressPeer::ID, $this->id);
        if ($this->isColumnModified(AddressPeer::TYPE)) $criteria->add(AddressPeer::TYPE, $this->type);
        if ($this->isColumnModified(AddressPeer::STREET_NAME)) $criteria->add(AddressPeer::STREET_NAME, $this->street_name);
        if ($this->isColumnModified(AddressPeer::HOUSE_NUMBER)) $criteria->add(AddressPeer::HOUSE_NUMBER, $this->house_number);
        if ($this->isColumnModified(AddressPeer::EXTRA_INFO)) $criteria->add(AddressPeer::EXTRA_INFO, $this->extra_info);
        if ($this->isColumnModified(AddressPeer::POSTAL_CODE)) $criteria->add(AddressPeer::POSTAL_CODE, $this->postal_code);
        if ($this->isColumnModified(AddressPeer::CITY)) $criteria->add(AddressPeer::CITY, $this->city);
        if ($this->isColumnModified(AddressPeer::COUNTRY)) $criteria->add(AddressPeer::COUNTRY, $this->country);
        if ($this->isColumnModified(AddressPeer::MAP_URL)) $criteria->add(AddressPeer::MAP_URL, $this->map_url);
        if ($this->isColumnModified(AddressPeer::CREATED_AT)) $criteria->add(AddressPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(AddressPeer::UPDATED_AT)) $criteria->add(AddressPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(AddressPeer::DATABASE_NAME);
        $criteria->add(AddressPeer::ID, $this->id);

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
     * @param object $copyObj An object of Address (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setType($this->getType());
        $copyObj->setStreetName($this->getStreetName());
        $copyObj->setHouseNumber($this->getHouseNumber());
        $copyObj->setExtraInfo($this->getExtraInfo());
        $copyObj->setPostalCode($this->getPostalCode());
        $copyObj->setCity($this->getCity());
        $copyObj->setCountry($this->getCountry());
        $copyObj->setMapUrl($this->getMapUrl());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCompanyAddresses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCompanyAddress($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStoreAddresses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStoreAddress($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserAddresses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserAddress($relObj->copy($deepCopy));
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
     * @return Address Clone of current object.
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
     * @return AddressPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new AddressPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Countries object.
     *
     * @param                  Countries $v
     * @return Address The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCountries(Countries $v = null)
    {
        if ($v === null) {
            $this->setCountry(NULL);
        } else {
            $this->setCountry($v->getId());
        }

        $this->aCountries = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Countries object, it will not be re-added.
        if ($v !== null) {
            $v->addAddress($this);
        }


        return $this;
    }


    /**
     * Get the associated Countries object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Countries The associated Countries object.
     * @throws PropelException
     */
    public function getCountries(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCountries === null && ($this->country !== null) && $doQuery) {
            $this->aCountries = CountriesQuery::create()->findPk($this->country, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCountries->addAddresses($this);
             */
        }

        return $this->aCountries;
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
        if ('CompanyAddress' == $relationName) {
            $this->initCompanyAddresses();
        }
        if ('StoreAddress' == $relationName) {
            $this->initStoreAddresses();
        }
        if ('UserAddress' == $relationName) {
            $this->initUserAddresses();
        }
    }

    /**
     * Clears out the collCompanyAddresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Address The current object (for fluent API support)
     * @see        addCompanyAddresses()
     */
    public function clearCompanyAddresses()
    {
        $this->collCompanyAddresses = null; // important to set this to null since that means it is uninitialized
        $this->collCompanyAddressesPartial = null;

        return $this;
    }

    /**
     * reset is the collCompanyAddresses collection loaded partially
     *
     * @return void
     */
    public function resetPartialCompanyAddresses($v = true)
    {
        $this->collCompanyAddressesPartial = $v;
    }

    /**
     * Initializes the collCompanyAddresses collection.
     *
     * By default this just sets the collCompanyAddresses collection to an empty array (like clearcollCompanyAddresses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCompanyAddresses($overrideExisting = true)
    {
        if (null !== $this->collCompanyAddresses && !$overrideExisting) {
            return;
        }
        $this->collCompanyAddresses = new PropelObjectCollection();
        $this->collCompanyAddresses->setModel('CompanyAddress');
    }

    /**
     * Gets an array of CompanyAddress objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Address is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CompanyAddress[] List of CompanyAddress objects
     * @throws PropelException
     */
    public function getCompanyAddresses($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCompanyAddressesPartial && !$this->isNew();
        if (null === $this->collCompanyAddresses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCompanyAddresses) {
                // return empty collection
                $this->initCompanyAddresses();
            } else {
                $collCompanyAddresses = CompanyAddressQuery::create(null, $criteria)
                    ->filterByAddress($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCompanyAddressesPartial && count($collCompanyAddresses)) {
                      $this->initCompanyAddresses(false);

                      foreach ($collCompanyAddresses as $obj) {
                        if (false == $this->collCompanyAddresses->contains($obj)) {
                          $this->collCompanyAddresses->append($obj);
                        }
                      }

                      $this->collCompanyAddressesPartial = true;
                    }

                    $collCompanyAddresses->getInternalIterator()->rewind();

                    return $collCompanyAddresses;
                }

                if ($partial && $this->collCompanyAddresses) {
                    foreach ($this->collCompanyAddresses as $obj) {
                        if ($obj->isNew()) {
                            $collCompanyAddresses[] = $obj;
                        }
                    }
                }

                $this->collCompanyAddresses = $collCompanyAddresses;
                $this->collCompanyAddressesPartial = false;
            }
        }

        return $this->collCompanyAddresses;
    }

    /**
     * Sets a collection of CompanyAddress objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $companyAddresses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Address The current object (for fluent API support)
     */
    public function setCompanyAddresses(PropelCollection $companyAddresses, PropelPDO $con = null)
    {
        $companyAddressesToDelete = $this->getCompanyAddresses(new Criteria(), $con)->diff($companyAddresses);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->companyAddressesScheduledForDeletion = clone $companyAddressesToDelete;

        foreach ($companyAddressesToDelete as $companyAddressRemoved) {
            $companyAddressRemoved->setAddress(null);
        }

        $this->collCompanyAddresses = null;
        foreach ($companyAddresses as $companyAddress) {
            $this->addCompanyAddress($companyAddress);
        }

        $this->collCompanyAddresses = $companyAddresses;
        $this->collCompanyAddressesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CompanyAddress objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CompanyAddress objects.
     * @throws PropelException
     */
    public function countCompanyAddresses(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCompanyAddressesPartial && !$this->isNew();
        if (null === $this->collCompanyAddresses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCompanyAddresses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCompanyAddresses());
            }
            $query = CompanyAddressQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAddress($this)
                ->count($con);
        }

        return count($this->collCompanyAddresses);
    }

    /**
     * Method called to associate a CompanyAddress object to this object
     * through the CompanyAddress foreign key attribute.
     *
     * @param    CompanyAddress $l CompanyAddress
     * @return Address The current object (for fluent API support)
     */
    public function addCompanyAddress(CompanyAddress $l)
    {
        if ($this->collCompanyAddresses === null) {
            $this->initCompanyAddresses();
            $this->collCompanyAddressesPartial = true;
        }

        if (!in_array($l, $this->collCompanyAddresses->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCompanyAddress($l);

            if ($this->companyAddressesScheduledForDeletion and $this->companyAddressesScheduledForDeletion->contains($l)) {
                $this->companyAddressesScheduledForDeletion->remove($this->companyAddressesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CompanyAddress $companyAddress The companyAddress object to add.
     */
    protected function doAddCompanyAddress($companyAddress)
    {
        $this->collCompanyAddresses[]= $companyAddress;
        $companyAddress->setAddress($this);
    }

    /**
     * @param	CompanyAddress $companyAddress The companyAddress object to remove.
     * @return Address The current object (for fluent API support)
     */
    public function removeCompanyAddress($companyAddress)
    {
        if ($this->getCompanyAddresses()->contains($companyAddress)) {
            $this->collCompanyAddresses->remove($this->collCompanyAddresses->search($companyAddress));
            if (null === $this->companyAddressesScheduledForDeletion) {
                $this->companyAddressesScheduledForDeletion = clone $this->collCompanyAddresses;
                $this->companyAddressesScheduledForDeletion->clear();
            }
            $this->companyAddressesScheduledForDeletion[]= clone $companyAddress;
            $companyAddress->setAddress(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Address is new, it will return
     * an empty collection; or if this Address has previously
     * been saved, it will retrieve related CompanyAddresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Address.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CompanyAddress[] List of CompanyAddress objects
     */
    public function getCompanyAddressesJoinCompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CompanyAddressQuery::create(null, $criteria);
        $query->joinWith('Company', $join_behavior);

        return $this->getCompanyAddresses($query, $con);
    }

    /**
     * Clears out the collStoreAddresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Address The current object (for fluent API support)
     * @see        addStoreAddresses()
     */
    public function clearStoreAddresses()
    {
        $this->collStoreAddresses = null; // important to set this to null since that means it is uninitialized
        $this->collStoreAddressesPartial = null;

        return $this;
    }

    /**
     * reset is the collStoreAddresses collection loaded partially
     *
     * @return void
     */
    public function resetPartialStoreAddresses($v = true)
    {
        $this->collStoreAddressesPartial = $v;
    }

    /**
     * Initializes the collStoreAddresses collection.
     *
     * By default this just sets the collStoreAddresses collection to an empty array (like clearcollStoreAddresses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStoreAddresses($overrideExisting = true)
    {
        if (null !== $this->collStoreAddresses && !$overrideExisting) {
            return;
        }
        $this->collStoreAddresses = new PropelObjectCollection();
        $this->collStoreAddresses->setModel('StoreAddress');
    }

    /**
     * Gets an array of StoreAddress objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Address is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|StoreAddress[] List of StoreAddress objects
     * @throws PropelException
     */
    public function getStoreAddresses($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStoreAddressesPartial && !$this->isNew();
        if (null === $this->collStoreAddresses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStoreAddresses) {
                // return empty collection
                $this->initStoreAddresses();
            } else {
                $collStoreAddresses = StoreAddressQuery::create(null, $criteria)
                    ->filterByAddress($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStoreAddressesPartial && count($collStoreAddresses)) {
                      $this->initStoreAddresses(false);

                      foreach ($collStoreAddresses as $obj) {
                        if (false == $this->collStoreAddresses->contains($obj)) {
                          $this->collStoreAddresses->append($obj);
                        }
                      }

                      $this->collStoreAddressesPartial = true;
                    }

                    $collStoreAddresses->getInternalIterator()->rewind();

                    return $collStoreAddresses;
                }

                if ($partial && $this->collStoreAddresses) {
                    foreach ($this->collStoreAddresses as $obj) {
                        if ($obj->isNew()) {
                            $collStoreAddresses[] = $obj;
                        }
                    }
                }

                $this->collStoreAddresses = $collStoreAddresses;
                $this->collStoreAddressesPartial = false;
            }
        }

        return $this->collStoreAddresses;
    }

    /**
     * Sets a collection of StoreAddress objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $storeAddresses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Address The current object (for fluent API support)
     */
    public function setStoreAddresses(PropelCollection $storeAddresses, PropelPDO $con = null)
    {
        $storeAddressesToDelete = $this->getStoreAddresses(new Criteria(), $con)->diff($storeAddresses);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->storeAddressesScheduledForDeletion = clone $storeAddressesToDelete;

        foreach ($storeAddressesToDelete as $storeAddressRemoved) {
            $storeAddressRemoved->setAddress(null);
        }

        $this->collStoreAddresses = null;
        foreach ($storeAddresses as $storeAddress) {
            $this->addStoreAddress($storeAddress);
        }

        $this->collStoreAddresses = $storeAddresses;
        $this->collStoreAddressesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StoreAddress objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related StoreAddress objects.
     * @throws PropelException
     */
    public function countStoreAddresses(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStoreAddressesPartial && !$this->isNew();
        if (null === $this->collStoreAddresses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStoreAddresses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStoreAddresses());
            }
            $query = StoreAddressQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAddress($this)
                ->count($con);
        }

        return count($this->collStoreAddresses);
    }

    /**
     * Method called to associate a StoreAddress object to this object
     * through the StoreAddress foreign key attribute.
     *
     * @param    StoreAddress $l StoreAddress
     * @return Address The current object (for fluent API support)
     */
    public function addStoreAddress(StoreAddress $l)
    {
        if ($this->collStoreAddresses === null) {
            $this->initStoreAddresses();
            $this->collStoreAddressesPartial = true;
        }

        if (!in_array($l, $this->collStoreAddresses->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStoreAddress($l);

            if ($this->storeAddressesScheduledForDeletion and $this->storeAddressesScheduledForDeletion->contains($l)) {
                $this->storeAddressesScheduledForDeletion->remove($this->storeAddressesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	StoreAddress $storeAddress The storeAddress object to add.
     */
    protected function doAddStoreAddress($storeAddress)
    {
        $this->collStoreAddresses[]= $storeAddress;
        $storeAddress->setAddress($this);
    }

    /**
     * @param	StoreAddress $storeAddress The storeAddress object to remove.
     * @return Address The current object (for fluent API support)
     */
    public function removeStoreAddress($storeAddress)
    {
        if ($this->getStoreAddresses()->contains($storeAddress)) {
            $this->collStoreAddresses->remove($this->collStoreAddresses->search($storeAddress));
            if (null === $this->storeAddressesScheduledForDeletion) {
                $this->storeAddressesScheduledForDeletion = clone $this->collStoreAddresses;
                $this->storeAddressesScheduledForDeletion->clear();
            }
            $this->storeAddressesScheduledForDeletion[]= clone $storeAddress;
            $storeAddress->setAddress(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Address is new, it will return
     * an empty collection; or if this Address has previously
     * been saved, it will retrieve related StoreAddresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Address.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StoreAddress[] List of StoreAddress objects
     */
    public function getStoreAddressesJoinStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreAddressQuery::create(null, $criteria);
        $query->joinWith('Store', $join_behavior);

        return $this->getStoreAddresses($query, $con);
    }

    /**
     * Clears out the collUserAddresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Address The current object (for fluent API support)
     * @see        addUserAddresses()
     */
    public function clearUserAddresses()
    {
        $this->collUserAddresses = null; // important to set this to null since that means it is uninitialized
        $this->collUserAddressesPartial = null;

        return $this;
    }

    /**
     * reset is the collUserAddresses collection loaded partially
     *
     * @return void
     */
    public function resetPartialUserAddresses($v = true)
    {
        $this->collUserAddressesPartial = $v;
    }

    /**
     * Initializes the collUserAddresses collection.
     *
     * By default this just sets the collUserAddresses collection to an empty array (like clearcollUserAddresses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserAddresses($overrideExisting = true)
    {
        if (null !== $this->collUserAddresses && !$overrideExisting) {
            return;
        }
        $this->collUserAddresses = new PropelObjectCollection();
        $this->collUserAddresses->setModel('UserAddress');
    }

    /**
     * Gets an array of UserAddress objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Address is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserAddress[] List of UserAddress objects
     * @throws PropelException
     */
    public function getUserAddresses($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserAddressesPartial && !$this->isNew();
        if (null === $this->collUserAddresses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserAddresses) {
                // return empty collection
                $this->initUserAddresses();
            } else {
                $collUserAddresses = UserAddressQuery::create(null, $criteria)
                    ->filterByAddress($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUserAddressesPartial && count($collUserAddresses)) {
                      $this->initUserAddresses(false);

                      foreach ($collUserAddresses as $obj) {
                        if (false == $this->collUserAddresses->contains($obj)) {
                          $this->collUserAddresses->append($obj);
                        }
                      }

                      $this->collUserAddressesPartial = true;
                    }

                    $collUserAddresses->getInternalIterator()->rewind();

                    return $collUserAddresses;
                }

                if ($partial && $this->collUserAddresses) {
                    foreach ($this->collUserAddresses as $obj) {
                        if ($obj->isNew()) {
                            $collUserAddresses[] = $obj;
                        }
                    }
                }

                $this->collUserAddresses = $collUserAddresses;
                $this->collUserAddressesPartial = false;
            }
        }

        return $this->collUserAddresses;
    }

    /**
     * Sets a collection of UserAddress objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userAddresses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Address The current object (for fluent API support)
     */
    public function setUserAddresses(PropelCollection $userAddresses, PropelPDO $con = null)
    {
        $userAddressesToDelete = $this->getUserAddresses(new Criteria(), $con)->diff($userAddresses);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userAddressesScheduledForDeletion = clone $userAddressesToDelete;

        foreach ($userAddressesToDelete as $userAddressRemoved) {
            $userAddressRemoved->setAddress(null);
        }

        $this->collUserAddresses = null;
        foreach ($userAddresses as $userAddress) {
            $this->addUserAddress($userAddress);
        }

        $this->collUserAddresses = $userAddresses;
        $this->collUserAddressesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserAddress objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserAddress objects.
     * @throws PropelException
     */
    public function countUserAddresses(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserAddressesPartial && !$this->isNew();
        if (null === $this->collUserAddresses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserAddresses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserAddresses());
            }
            $query = UserAddressQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAddress($this)
                ->count($con);
        }

        return count($this->collUserAddresses);
    }

    /**
     * Method called to associate a UserAddress object to this object
     * through the UserAddress foreign key attribute.
     *
     * @param    UserAddress $l UserAddress
     * @return Address The current object (for fluent API support)
     */
    public function addUserAddress(UserAddress $l)
    {
        if ($this->collUserAddresses === null) {
            $this->initUserAddresses();
            $this->collUserAddressesPartial = true;
        }

        if (!in_array($l, $this->collUserAddresses->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserAddress($l);

            if ($this->userAddressesScheduledForDeletion and $this->userAddressesScheduledForDeletion->contains($l)) {
                $this->userAddressesScheduledForDeletion->remove($this->userAddressesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	UserAddress $userAddress The userAddress object to add.
     */
    protected function doAddUserAddress($userAddress)
    {
        $this->collUserAddresses[]= $userAddress;
        $userAddress->setAddress($this);
    }

    /**
     * @param	UserAddress $userAddress The userAddress object to remove.
     * @return Address The current object (for fluent API support)
     */
    public function removeUserAddress($userAddress)
    {
        if ($this->getUserAddresses()->contains($userAddress)) {
            $this->collUserAddresses->remove($this->collUserAddresses->search($userAddress));
            if (null === $this->userAddressesScheduledForDeletion) {
                $this->userAddressesScheduledForDeletion = clone $this->collUserAddresses;
                $this->userAddressesScheduledForDeletion->clear();
            }
            $this->userAddressesScheduledForDeletion[]= clone $userAddress;
            $userAddress->setAddress(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Address is new, it will return
     * an empty collection; or if this Address has previously
     * been saved, it will retrieve related UserAddresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Address.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserAddress[] List of UserAddress objects
     */
    public function getUserAddressesJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserAddressQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getUserAddresses($query, $con);
    }

    /**
     * Clears out the collCompanies collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Address The current object (for fluent API support)
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
     * to the current object by way of the company_address cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Address is new, it will return
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
                    ->filterByAddress($this)
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
     * to the current object by way of the company_address cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $companies A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Address The current object (for fluent API support)
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
     * to the current object by way of the company_address cross-reference table.
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
                    ->filterByAddress($this)
                    ->count($con);
            }
        } else {
            return count($this->collCompanies);
        }
    }

    /**
     * Associate a Company object to this object
     * through the company_address cross reference table.
     *
     * @param  Company $company The CompanyAddress object to relate
     * @return Address The current object (for fluent API support)
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
        if (!$company->getAddresses()->contains($this)) { $companyAddress = new CompanyAddress();
            $companyAddress->setCompany($company);
            $this->addCompanyAddress($companyAddress);

            $foreignCollection = $company->getAddresses();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Company object to this object
     * through the company_address cross reference table.
     *
     * @param Company $company The CompanyAddress object to relate
     * @return Address The current object (for fluent API support)
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
     * @return Address The current object (for fluent API support)
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
     * to the current object by way of the store_address cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Address is new, it will return
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
                    ->filterByAddress($this)
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
     * to the current object by way of the store_address cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $stores A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Address The current object (for fluent API support)
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
     * to the current object by way of the store_address cross-reference table.
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
                    ->filterByAddress($this)
                    ->count($con);
            }
        } else {
            return count($this->collStores);
        }
    }

    /**
     * Associate a Store object to this object
     * through the store_address cross reference table.
     *
     * @param  Store $store The StoreAddress object to relate
     * @return Address The current object (for fluent API support)
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
        if (!$store->getAddresses()->contains($this)) { $storeAddress = new StoreAddress();
            $storeAddress->setStore($store);
            $this->addStoreAddress($storeAddress);

            $foreignCollection = $store->getAddresses();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Store object to this object
     * through the store_address cross reference table.
     *
     * @param Store $store The StoreAddress object to relate
     * @return Address The current object (for fluent API support)
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
     * @return Address The current object (for fluent API support)
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
     * to the current object by way of the user_address cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Address is new, it will return
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
                    ->filterByAddress($this)
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
     * to the current object by way of the user_address cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $users A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Address The current object (for fluent API support)
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
     * to the current object by way of the user_address cross-reference table.
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
                    ->filterByAddress($this)
                    ->count($con);
            }
        } else {
            return count($this->collUsers);
        }
    }

    /**
     * Associate a User object to this object
     * through the user_address cross reference table.
     *
     * @param  User $user The UserAddress object to relate
     * @return Address The current object (for fluent API support)
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
        if (!$user->getAddresses()->contains($this)) { $userAddress = new UserAddress();
            $userAddress->setUser($user);
            $this->addUserAddress($userAddress);

            $foreignCollection = $user->getAddresses();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a User object to this object
     * through the user_address cross reference table.
     *
     * @param User $user The UserAddress object to relate
     * @return Address The current object (for fluent API support)
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
        $this->type = null;
        $this->street_name = null;
        $this->house_number = null;
        $this->extra_info = null;
        $this->postal_code = null;
        $this->city = null;
        $this->country = null;
        $this->map_url = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
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
            if ($this->collCompanyAddresses) {
                foreach ($this->collCompanyAddresses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStoreAddresses) {
                foreach ($this->collStoreAddresses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserAddresses) {
                foreach ($this->collUserAddresses as $o) {
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
            if ($this->aCountries instanceof Persistent) {
              $this->aCountries->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collCompanyAddresses instanceof PropelCollection) {
            $this->collCompanyAddresses->clearIterator();
        }
        $this->collCompanyAddresses = null;
        if ($this->collStoreAddresses instanceof PropelCollection) {
            $this->collStoreAddresses->clearIterator();
        }
        $this->collStoreAddresses = null;
        if ($this->collUserAddresses instanceof PropelCollection) {
            $this->collUserAddresses->clearIterator();
        }
        $this->collUserAddresses = null;
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
        $this->aCountries = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(AddressPeer::DEFAULT_STRING_FORMAT);
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
     * @return     Address The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = AddressPeer::UPDATED_AT;

        return $this;
    }

}
