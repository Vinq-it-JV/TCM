<?php

namespace StoreBundle\Model\om;

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
use CompanyBundle\Model\CompanyQuery;
use CompanyBundle\Model\Regions;
use CompanyBundle\Model\RegionsQuery;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreAddress;
use StoreBundle\Model\StoreAddressQuery;
use StoreBundle\Model\StoreContact;
use StoreBundle\Model\StoreContactQuery;
use StoreBundle\Model\StoreEmail;
use StoreBundle\Model\StoreEmailQuery;
use StoreBundle\Model\StoreInformant;
use StoreBundle\Model\StoreInformantQuery;
use StoreBundle\Model\StoreOwner;
use StoreBundle\Model\StoreOwnerQuery;
use StoreBundle\Model\StorePeer;
use StoreBundle\Model\StorePhone;
use StoreBundle\Model\StorePhoneQuery;
use StoreBundle\Model\StoreQuery;
use StoreBundle\Model\StoreType;
use StoreBundle\Model\StoreTypeQuery;
use UserBundle\Model\Address;
use UserBundle\Model\AddressQuery;
use UserBundle\Model\Email;
use UserBundle\Model\EmailQuery;
use UserBundle\Model\Phone;
use UserBundle\Model\PhoneQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserQuery;

abstract class BaseStore extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'StoreBundle\\Model\\StorePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        StorePeer
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
     * The value for the main_company field.
     * @var        int
     */
    protected $main_company;

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
     * The value for the type field.
     * @var        int
     */
    protected $type;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the website field.
     * @var        string
     */
    protected $website;

    /**
     * The value for the region field.
     * @var        int
     */
    protected $region;

    /**
     * The value for the remarks field.
     * @var        string
     */
    protected $remarks;

    /**
     * The value for the payment_method field.
     * @var        int
     */
    protected $payment_method;

    /**
     * The value for the bank_account_number field.
     * @var        string
     */
    protected $bank_account_number;

    /**
     * The value for the vat_number field.
     * @var        string
     */
    protected $vat_number;

    /**
     * The value for the coc_number field.
     * @var        string
     */
    protected $coc_number;

    /**
     * The value for the is_enabled field.
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $is_enabled;

    /**
     * The value for the is_deleted field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_deleted;

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
     * @var        Company
     */
    protected $aCompany;

    /**
     * @var        StoreType
     */
    protected $aStoreType;

    /**
     * @var        Regions
     */
    protected $aRegions;

    /**
     * @var        PropelObjectCollection|StoreAddress[] Collection to store aggregation of StoreAddress objects.
     */
    protected $collStoreAddresses;
    protected $collStoreAddressesPartial;

    /**
     * @var        PropelObjectCollection|StoreEmail[] Collection to store aggregation of StoreEmail objects.
     */
    protected $collStoreEmails;
    protected $collStoreEmailsPartial;

    /**
     * @var        PropelObjectCollection|StorePhone[] Collection to store aggregation of StorePhone objects.
     */
    protected $collStorePhones;
    protected $collStorePhonesPartial;

    /**
     * @var        PropelObjectCollection|StoreContact[] Collection to store aggregation of StoreContact objects.
     */
    protected $collStoreContacts;
    protected $collStoreContactsPartial;

    /**
     * @var        PropelObjectCollection|StoreInformant[] Collection to store aggregation of StoreInformant objects.
     */
    protected $collStoreInformants;
    protected $collStoreInformantsPartial;

    /**
     * @var        PropelObjectCollection|StoreOwner[] Collection to store aggregation of StoreOwner objects.
     */
    protected $collStoreOwners;
    protected $collStoreOwnersPartial;

    /**
     * @var        PropelObjectCollection|Address[] Collection to store aggregation of Address objects.
     */
    protected $collAddresses;

    /**
     * @var        PropelObjectCollection|Email[] Collection to store aggregation of Email objects.
     */
    protected $collEmails;

    /**
     * @var        PropelObjectCollection|Phone[] Collection to store aggregation of Phone objects.
     */
    protected $collPhones;

    /**
     * @var        PropelObjectCollection|User[] Collection to store aggregation of User objects.
     */
    protected $collContacts;

    /**
     * @var        PropelObjectCollection|User[] Collection to store aggregation of User objects.
     */
    protected $collInformants;

    /**
     * @var        PropelObjectCollection|User[] Collection to store aggregation of User objects.
     */
    protected $collOwners;

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
    protected $addressesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $emailsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $phonesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $contactsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $informantsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $ownersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $storeAddressesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $storeEmailsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $storePhonesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $storeContactsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $storeInformantsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $storeOwnersScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->is_enabled = true;
        $this->is_deleted = false;
    }

    /**
     * Initializes internal state of BaseStore object.
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
     * Get the [main_company] column value.
     *
     * @return int
     */
    public function getMainCompany()
    {

        return $this->main_company;
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
     * Get the [type] column value.
     *
     * @return int
     */
    public function getType()
    {

        return $this->type;
    }

    /**
     * Get the [code] column value.
     *
     * @return string
     */
    public function getCode()
    {

        return $this->code;
    }

    /**
     * Get the [website] column value.
     *
     * @return string
     */
    public function getWebsite()
    {

        return $this->website;
    }

    /**
     * Get the [region] column value.
     *
     * @return int
     */
    public function getRegion()
    {

        return $this->region;
    }

    /**
     * Get the [remarks] column value.
     *
     * @return string
     */
    public function getRemarks()
    {

        return $this->remarks;
    }

    /**
     * Get the [payment_method] column value.
     *
     * @return int
     */
    public function getPaymentMethod()
    {

        return $this->payment_method;
    }

    /**
     * Get the [bank_account_number] column value.
     *
     * @return string
     */
    public function getBankAccountNumber()
    {

        return $this->bank_account_number;
    }

    /**
     * Get the [vat_number] column value.
     *
     * @return string
     */
    public function getVatNumber()
    {

        return $this->vat_number;
    }

    /**
     * Get the [coc_number] column value.
     *
     * @return string
     */
    public function getCocNumber()
    {

        return $this->coc_number;
    }

    /**
     * Get the [is_enabled] column value.
     *
     * @return boolean
     */
    public function getIsEnabled()
    {

        return $this->is_enabled;
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
     * @return Store The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = StorePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [main_company] column.
     *
     * @param  int $v new value
     * @return Store The current object (for fluent API support)
     */
    public function setMainCompany($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->main_company !== $v) {
            $this->main_company = $v;
            $this->modifiedColumns[] = StorePeer::MAIN_COMPANY;
        }

        if ($this->aCompany !== null && $this->aCompany->getId() !== $v) {
            $this->aCompany = null;
        }


        return $this;
    } // setMainCompany()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Store The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = StorePeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return Store The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = StorePeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [type] column.
     *
     * @param  int $v new value
     * @return Store The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[] = StorePeer::TYPE;
        }

        if ($this->aStoreType !== null && $this->aStoreType->getId() !== $v) {
            $this->aStoreType = null;
        }


        return $this;
    } // setType()

    /**
     * Set the value of [code] column.
     *
     * @param  string $v new value
     * @return Store The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[] = StorePeer::CODE;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [website] column.
     *
     * @param  string $v new value
     * @return Store The current object (for fluent API support)
     */
    public function setWebsite($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->website !== $v) {
            $this->website = $v;
            $this->modifiedColumns[] = StorePeer::WEBSITE;
        }


        return $this;
    } // setWebsite()

    /**
     * Set the value of [region] column.
     *
     * @param  int $v new value
     * @return Store The current object (for fluent API support)
     */
    public function setRegion($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->region !== $v) {
            $this->region = $v;
            $this->modifiedColumns[] = StorePeer::REGION;
        }

        if ($this->aRegions !== null && $this->aRegions->getId() !== $v) {
            $this->aRegions = null;
        }


        return $this;
    } // setRegion()

    /**
     * Set the value of [remarks] column.
     *
     * @param  string $v new value
     * @return Store The current object (for fluent API support)
     */
    public function setRemarks($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->remarks !== $v) {
            $this->remarks = $v;
            $this->modifiedColumns[] = StorePeer::REMARKS;
        }


        return $this;
    } // setRemarks()

    /**
     * Set the value of [payment_method] column.
     *
     * @param  int $v new value
     * @return Store The current object (for fluent API support)
     */
    public function setPaymentMethod($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->payment_method !== $v) {
            $this->payment_method = $v;
            $this->modifiedColumns[] = StorePeer::PAYMENT_METHOD;
        }


        return $this;
    } // setPaymentMethod()

    /**
     * Set the value of [bank_account_number] column.
     *
     * @param  string $v new value
     * @return Store The current object (for fluent API support)
     */
    public function setBankAccountNumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bank_account_number !== $v) {
            $this->bank_account_number = $v;
            $this->modifiedColumns[] = StorePeer::BANK_ACCOUNT_NUMBER;
        }


        return $this;
    } // setBankAccountNumber()

    /**
     * Set the value of [vat_number] column.
     *
     * @param  string $v new value
     * @return Store The current object (for fluent API support)
     */
    public function setVatNumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->vat_number !== $v) {
            $this->vat_number = $v;
            $this->modifiedColumns[] = StorePeer::VAT_NUMBER;
        }


        return $this;
    } // setVatNumber()

    /**
     * Set the value of [coc_number] column.
     *
     * @param  string $v new value
     * @return Store The current object (for fluent API support)
     */
    public function setCocNumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->coc_number !== $v) {
            $this->coc_number = $v;
            $this->modifiedColumns[] = StorePeer::COC_NUMBER;
        }


        return $this;
    } // setCocNumber()

    /**
     * Sets the value of the [is_enabled] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Store The current object (for fluent API support)
     */
    public function setIsEnabled($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_enabled !== $v) {
            $this->is_enabled = $v;
            $this->modifiedColumns[] = StorePeer::IS_ENABLED;
        }


        return $this;
    } // setIsEnabled()

    /**
     * Sets the value of the [is_deleted] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Store The current object (for fluent API support)
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
            $this->modifiedColumns[] = StorePeer::IS_DELETED;
        }


        return $this;
    } // setIsDeleted()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Store The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = StorePeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Store The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = StorePeer::UPDATED_AT;
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
            if ($this->is_enabled !== true) {
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
            $this->main_company = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->description = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->type = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->code = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->website = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->region = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->remarks = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->payment_method = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->bank_account_number = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->vat_number = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->coc_number = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->is_enabled = ($row[$startcol + 13] !== null) ? (boolean) $row[$startcol + 13] : null;
            $this->is_deleted = ($row[$startcol + 14] !== null) ? (boolean) $row[$startcol + 14] : null;
            $this->created_at = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->updated_at = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 17; // 17 = StorePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Store object", $e);
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

        if ($this->aCompany !== null && $this->main_company !== $this->aCompany->getId()) {
            $this->aCompany = null;
        }
        if ($this->aStoreType !== null && $this->type !== $this->aStoreType->getId()) {
            $this->aStoreType = null;
        }
        if ($this->aRegions !== null && $this->region !== $this->aRegions->getId()) {
            $this->aRegions = null;
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
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = StorePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCompany = null;
            $this->aStoreType = null;
            $this->aRegions = null;
            $this->collStoreAddresses = null;

            $this->collStoreEmails = null;

            $this->collStorePhones = null;

            $this->collStoreContacts = null;

            $this->collStoreInformants = null;

            $this->collStoreOwners = null;

            $this->collAddresses = null;
            $this->collEmails = null;
            $this->collPhones = null;
            $this->collContacts = null;
            $this->collInformants = null;
            $this->collOwners = null;
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
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = StoreQuery::create()
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
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(StorePeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(StorePeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(StorePeer::UPDATED_AT)) {
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
                StorePeer::addInstanceToPool($this);
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

            if ($this->aCompany !== null) {
                if ($this->aCompany->isModified() || $this->aCompany->isNew()) {
                    $affectedRows += $this->aCompany->save($con);
                }
                $this->setCompany($this->aCompany);
            }

            if ($this->aStoreType !== null) {
                if ($this->aStoreType->isModified() || $this->aStoreType->isNew()) {
                    $affectedRows += $this->aStoreType->save($con);
                }
                $this->setStoreType($this->aStoreType);
            }

            if ($this->aRegions !== null) {
                if ($this->aRegions->isModified() || $this->aRegions->isNew()) {
                    $affectedRows += $this->aRegions->save($con);
                }
                $this->setRegions($this->aRegions);
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

            if ($this->addressesScheduledForDeletion !== null) {
                if (!$this->addressesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->addressesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    StoreAddressQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->addressesScheduledForDeletion = null;
                }

                foreach ($this->getAddresses() as $address) {
                    if ($address->isModified()) {
                        $address->save($con);
                    }
                }
            } elseif ($this->collAddresses) {
                foreach ($this->collAddresses as $address) {
                    if ($address->isModified()) {
                        $address->save($con);
                    }
                }
            }

            if ($this->emailsScheduledForDeletion !== null) {
                if (!$this->emailsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->emailsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    StoreEmailQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->emailsScheduledForDeletion = null;
                }

                foreach ($this->getEmails() as $email) {
                    if ($email->isModified()) {
                        $email->save($con);
                    }
                }
            } elseif ($this->collEmails) {
                foreach ($this->collEmails as $email) {
                    if ($email->isModified()) {
                        $email->save($con);
                    }
                }
            }

            if ($this->phonesScheduledForDeletion !== null) {
                if (!$this->phonesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->phonesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    StorePhoneQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->phonesScheduledForDeletion = null;
                }

                foreach ($this->getPhones() as $phone) {
                    if ($phone->isModified()) {
                        $phone->save($con);
                    }
                }
            } elseif ($this->collPhones) {
                foreach ($this->collPhones as $phone) {
                    if ($phone->isModified()) {
                        $phone->save($con);
                    }
                }
            }

            if ($this->contactsScheduledForDeletion !== null) {
                if (!$this->contactsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->contactsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    StoreContactQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->contactsScheduledForDeletion = null;
                }

                foreach ($this->getContacts() as $contact) {
                    if ($contact->isModified()) {
                        $contact->save($con);
                    }
                }
            } elseif ($this->collContacts) {
                foreach ($this->collContacts as $contact) {
                    if ($contact->isModified()) {
                        $contact->save($con);
                    }
                }
            }

            if ($this->informantsScheduledForDeletion !== null) {
                if (!$this->informantsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->informantsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    StoreInformantQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->informantsScheduledForDeletion = null;
                }

                foreach ($this->getInformants() as $informant) {
                    if ($informant->isModified()) {
                        $informant->save($con);
                    }
                }
            } elseif ($this->collInformants) {
                foreach ($this->collInformants as $informant) {
                    if ($informant->isModified()) {
                        $informant->save($con);
                    }
                }
            }

            if ($this->ownersScheduledForDeletion !== null) {
                if (!$this->ownersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->ownersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    StoreOwnerQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->ownersScheduledForDeletion = null;
                }

                foreach ($this->getOwners() as $owner) {
                    if ($owner->isModified()) {
                        $owner->save($con);
                    }
                }
            } elseif ($this->collOwners) {
                foreach ($this->collOwners as $owner) {
                    if ($owner->isModified()) {
                        $owner->save($con);
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

            if ($this->storeContactsScheduledForDeletion !== null) {
                if (!$this->storeContactsScheduledForDeletion->isEmpty()) {
                    StoreContactQuery::create()
                        ->filterByPrimaryKeys($this->storeContactsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->storeContactsScheduledForDeletion = null;
                }
            }

            if ($this->collStoreContacts !== null) {
                foreach ($this->collStoreContacts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->storeInformantsScheduledForDeletion !== null) {
                if (!$this->storeInformantsScheduledForDeletion->isEmpty()) {
                    StoreInformantQuery::create()
                        ->filterByPrimaryKeys($this->storeInformantsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->storeInformantsScheduledForDeletion = null;
                }
            }

            if ($this->collStoreInformants !== null) {
                foreach ($this->collStoreInformants as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->storeOwnersScheduledForDeletion !== null) {
                if (!$this->storeOwnersScheduledForDeletion->isEmpty()) {
                    StoreOwnerQuery::create()
                        ->filterByPrimaryKeys($this->storeOwnersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->storeOwnersScheduledForDeletion = null;
                }
            }

            if ($this->collStoreOwners !== null) {
                foreach ($this->collStoreOwners as $referrerFK) {
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

        $this->modifiedColumns[] = StorePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . StorePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(StorePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(StorePeer::MAIN_COMPANY)) {
            $modifiedColumns[':p' . $index++]  = '`main_company`';
        }
        if ($this->isColumnModified(StorePeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(StorePeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(StorePeer::TYPE)) {
            $modifiedColumns[':p' . $index++]  = '`type`';
        }
        if ($this->isColumnModified(StorePeer::CODE)) {
            $modifiedColumns[':p' . $index++]  = '`code`';
        }
        if ($this->isColumnModified(StorePeer::WEBSITE)) {
            $modifiedColumns[':p' . $index++]  = '`website`';
        }
        if ($this->isColumnModified(StorePeer::REGION)) {
            $modifiedColumns[':p' . $index++]  = '`region`';
        }
        if ($this->isColumnModified(StorePeer::REMARKS)) {
            $modifiedColumns[':p' . $index++]  = '`remarks`';
        }
        if ($this->isColumnModified(StorePeer::PAYMENT_METHOD)) {
            $modifiedColumns[':p' . $index++]  = '`payment_method`';
        }
        if ($this->isColumnModified(StorePeer::BANK_ACCOUNT_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = '`bank_account_number`';
        }
        if ($this->isColumnModified(StorePeer::VAT_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = '`vat_number`';
        }
        if ($this->isColumnModified(StorePeer::COC_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = '`coc_number`';
        }
        if ($this->isColumnModified(StorePeer::IS_ENABLED)) {
            $modifiedColumns[':p' . $index++]  = '`is_enabled`';
        }
        if ($this->isColumnModified(StorePeer::IS_DELETED)) {
            $modifiedColumns[':p' . $index++]  = '`is_deleted`';
        }
        if ($this->isColumnModified(StorePeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(StorePeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `store` (%s) VALUES (%s)',
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
                    case '`main_company`':
                        $stmt->bindValue($identifier, $this->main_company, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`type`':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_INT);
                        break;
                    case '`code`':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case '`website`':
                        $stmt->bindValue($identifier, $this->website, PDO::PARAM_STR);
                        break;
                    case '`region`':
                        $stmt->bindValue($identifier, $this->region, PDO::PARAM_INT);
                        break;
                    case '`remarks`':
                        $stmt->bindValue($identifier, $this->remarks, PDO::PARAM_STR);
                        break;
                    case '`payment_method`':
                        $stmt->bindValue($identifier, $this->payment_method, PDO::PARAM_INT);
                        break;
                    case '`bank_account_number`':
                        $stmt->bindValue($identifier, $this->bank_account_number, PDO::PARAM_STR);
                        break;
                    case '`vat_number`':
                        $stmt->bindValue($identifier, $this->vat_number, PDO::PARAM_STR);
                        break;
                    case '`coc_number`':
                        $stmt->bindValue($identifier, $this->coc_number, PDO::PARAM_STR);
                        break;
                    case '`is_enabled`':
                        $stmt->bindValue($identifier, (int) $this->is_enabled, PDO::PARAM_INT);
                        break;
                    case '`is_deleted`':
                        $stmt->bindValue($identifier, (int) $this->is_deleted, PDO::PARAM_INT);
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

            if ($this->aCompany !== null) {
                if (!$this->aCompany->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCompany->getValidationFailures());
                }
            }

            if ($this->aStoreType !== null) {
                if (!$this->aStoreType->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aStoreType->getValidationFailures());
                }
            }

            if ($this->aRegions !== null) {
                if (!$this->aRegions->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aRegions->getValidationFailures());
                }
            }


            if (($retval = StorePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collStoreAddresses !== null) {
                    foreach ($this->collStoreAddresses as $referrerFK) {
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

                if ($this->collStorePhones !== null) {
                    foreach ($this->collStorePhones as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStoreContacts !== null) {
                    foreach ($this->collStoreContacts as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStoreInformants !== null) {
                    foreach ($this->collStoreInformants as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStoreOwners !== null) {
                    foreach ($this->collStoreOwners as $referrerFK) {
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
        $pos = StorePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getMainCompany();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getDescription();
                break;
            case 4:
                return $this->getType();
                break;
            case 5:
                return $this->getCode();
                break;
            case 6:
                return $this->getWebsite();
                break;
            case 7:
                return $this->getRegion();
                break;
            case 8:
                return $this->getRemarks();
                break;
            case 9:
                return $this->getPaymentMethod();
                break;
            case 10:
                return $this->getBankAccountNumber();
                break;
            case 11:
                return $this->getVatNumber();
                break;
            case 12:
                return $this->getCocNumber();
                break;
            case 13:
                return $this->getIsEnabled();
                break;
            case 14:
                return $this->getIsDeleted();
                break;
            case 15:
                return $this->getCreatedAt();
                break;
            case 16:
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
        if (isset($alreadyDumpedObjects['Store'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Store'][$this->getPrimaryKey()] = true;
        $keys = StorePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getMainCompany(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getDescription(),
            $keys[4] => $this->getType(),
            $keys[5] => $this->getCode(),
            $keys[6] => $this->getWebsite(),
            $keys[7] => $this->getRegion(),
            $keys[8] => $this->getRemarks(),
            $keys[9] => $this->getPaymentMethod(),
            $keys[10] => $this->getBankAccountNumber(),
            $keys[11] => $this->getVatNumber(),
            $keys[12] => $this->getCocNumber(),
            $keys[13] => $this->getIsEnabled(),
            $keys[14] => $this->getIsDeleted(),
            $keys[15] => $this->getCreatedAt(),
            $keys[16] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCompany) {
                $result['Company'] = $this->aCompany->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aStoreType) {
                $result['StoreType'] = $this->aStoreType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aRegions) {
                $result['Regions'] = $this->aRegions->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collStoreAddresses) {
                $result['StoreAddresses'] = $this->collStoreAddresses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStoreEmails) {
                $result['StoreEmails'] = $this->collStoreEmails->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStorePhones) {
                $result['StorePhones'] = $this->collStorePhones->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStoreContacts) {
                $result['StoreContacts'] = $this->collStoreContacts->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStoreInformants) {
                $result['StoreInformants'] = $this->collStoreInformants->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStoreOwners) {
                $result['StoreOwners'] = $this->collStoreOwners->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = StorePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setMainCompany($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setDescription($value);
                break;
            case 4:
                $this->setType($value);
                break;
            case 5:
                $this->setCode($value);
                break;
            case 6:
                $this->setWebsite($value);
                break;
            case 7:
                $this->setRegion($value);
                break;
            case 8:
                $this->setRemarks($value);
                break;
            case 9:
                $this->setPaymentMethod($value);
                break;
            case 10:
                $this->setBankAccountNumber($value);
                break;
            case 11:
                $this->setVatNumber($value);
                break;
            case 12:
                $this->setCocNumber($value);
                break;
            case 13:
                $this->setIsEnabled($value);
                break;
            case 14:
                $this->setIsDeleted($value);
                break;
            case 15:
                $this->setCreatedAt($value);
                break;
            case 16:
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
        $keys = StorePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setMainCompany($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDescription($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setType($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setCode($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setWebsite($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setRegion($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setRemarks($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setPaymentMethod($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setBankAccountNumber($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setVatNumber($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setCocNumber($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setIsEnabled($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setIsDeleted($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setCreatedAt($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setUpdatedAt($arr[$keys[16]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(StorePeer::DATABASE_NAME);

        if ($this->isColumnModified(StorePeer::ID)) $criteria->add(StorePeer::ID, $this->id);
        if ($this->isColumnModified(StorePeer::MAIN_COMPANY)) $criteria->add(StorePeer::MAIN_COMPANY, $this->main_company);
        if ($this->isColumnModified(StorePeer::NAME)) $criteria->add(StorePeer::NAME, $this->name);
        if ($this->isColumnModified(StorePeer::DESCRIPTION)) $criteria->add(StorePeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(StorePeer::TYPE)) $criteria->add(StorePeer::TYPE, $this->type);
        if ($this->isColumnModified(StorePeer::CODE)) $criteria->add(StorePeer::CODE, $this->code);
        if ($this->isColumnModified(StorePeer::WEBSITE)) $criteria->add(StorePeer::WEBSITE, $this->website);
        if ($this->isColumnModified(StorePeer::REGION)) $criteria->add(StorePeer::REGION, $this->region);
        if ($this->isColumnModified(StorePeer::REMARKS)) $criteria->add(StorePeer::REMARKS, $this->remarks);
        if ($this->isColumnModified(StorePeer::PAYMENT_METHOD)) $criteria->add(StorePeer::PAYMENT_METHOD, $this->payment_method);
        if ($this->isColumnModified(StorePeer::BANK_ACCOUNT_NUMBER)) $criteria->add(StorePeer::BANK_ACCOUNT_NUMBER, $this->bank_account_number);
        if ($this->isColumnModified(StorePeer::VAT_NUMBER)) $criteria->add(StorePeer::VAT_NUMBER, $this->vat_number);
        if ($this->isColumnModified(StorePeer::COC_NUMBER)) $criteria->add(StorePeer::COC_NUMBER, $this->coc_number);
        if ($this->isColumnModified(StorePeer::IS_ENABLED)) $criteria->add(StorePeer::IS_ENABLED, $this->is_enabled);
        if ($this->isColumnModified(StorePeer::IS_DELETED)) $criteria->add(StorePeer::IS_DELETED, $this->is_deleted);
        if ($this->isColumnModified(StorePeer::CREATED_AT)) $criteria->add(StorePeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(StorePeer::UPDATED_AT)) $criteria->add(StorePeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(StorePeer::DATABASE_NAME);
        $criteria->add(StorePeer::ID, $this->id);

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
     * @param object $copyObj An object of Store (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setMainCompany($this->getMainCompany());
        $copyObj->setName($this->getName());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setType($this->getType());
        $copyObj->setCode($this->getCode());
        $copyObj->setWebsite($this->getWebsite());
        $copyObj->setRegion($this->getRegion());
        $copyObj->setRemarks($this->getRemarks());
        $copyObj->setPaymentMethod($this->getPaymentMethod());
        $copyObj->setBankAccountNumber($this->getBankAccountNumber());
        $copyObj->setVatNumber($this->getVatNumber());
        $copyObj->setCocNumber($this->getCocNumber());
        $copyObj->setIsEnabled($this->getIsEnabled());
        $copyObj->setIsDeleted($this->getIsDeleted());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getStoreAddresses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStoreAddress($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStoreEmails() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStoreEmail($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStorePhones() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStorePhone($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStoreContacts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStoreContact($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStoreInformants() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStoreInformant($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStoreOwners() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStoreOwner($relObj->copy($deepCopy));
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
     * @return Store Clone of current object.
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
     * @return StorePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new StorePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Company object.
     *
     * @param                  Company $v
     * @return Store The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCompany(Company $v = null)
    {
        if ($v === null) {
            $this->setMainCompany(NULL);
        } else {
            $this->setMainCompany($v->getId());
        }

        $this->aCompany = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Company object, it will not be re-added.
        if ($v !== null) {
            $v->addStore($this);
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
        if ($this->aCompany === null && ($this->main_company !== null) && $doQuery) {
            $this->aCompany = CompanyQuery::create()->findPk($this->main_company, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCompany->addStores($this);
             */
        }

        return $this->aCompany;
    }

    /**
     * Declares an association between this object and a StoreType object.
     *
     * @param                  StoreType $v
     * @return Store The current object (for fluent API support)
     * @throws PropelException
     */
    public function setStoreType(StoreType $v = null)
    {
        if ($v === null) {
            $this->setType(NULL);
        } else {
            $this->setType($v->getId());
        }

        $this->aStoreType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the StoreType object, it will not be re-added.
        if ($v !== null) {
            $v->addStore($this);
        }


        return $this;
    }


    /**
     * Get the associated StoreType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return StoreType The associated StoreType object.
     * @throws PropelException
     */
    public function getStoreType(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aStoreType === null && ($this->type !== null) && $doQuery) {
            $this->aStoreType = StoreTypeQuery::create()->findPk($this->type, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aStoreType->addStores($this);
             */
        }

        return $this->aStoreType;
    }

    /**
     * Declares an association between this object and a Regions object.
     *
     * @param                  Regions $v
     * @return Store The current object (for fluent API support)
     * @throws PropelException
     */
    public function setRegions(Regions $v = null)
    {
        if ($v === null) {
            $this->setRegion(NULL);
        } else {
            $this->setRegion($v->getId());
        }

        $this->aRegions = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Regions object, it will not be re-added.
        if ($v !== null) {
            $v->addStore($this);
        }


        return $this;
    }


    /**
     * Get the associated Regions object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Regions The associated Regions object.
     * @throws PropelException
     */
    public function getRegions(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aRegions === null && ($this->region !== null) && $doQuery) {
            $this->aRegions = RegionsQuery::create()->findPk($this->region, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aRegions->addStores($this);
             */
        }

        return $this->aRegions;
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
        if ('StoreAddress' == $relationName) {
            $this->initStoreAddresses();
        }
        if ('StoreEmail' == $relationName) {
            $this->initStoreEmails();
        }
        if ('StorePhone' == $relationName) {
            $this->initStorePhones();
        }
        if ('StoreContact' == $relationName) {
            $this->initStoreContacts();
        }
        if ('StoreInformant' == $relationName) {
            $this->initStoreInformants();
        }
        if ('StoreOwner' == $relationName) {
            $this->initStoreOwners();
        }
    }

    /**
     * Clears out the collStoreAddresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Store The current object (for fluent API support)
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
     * If this Store is new, it will return
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
                    ->filterByStore($this)
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
     * @return Store The current object (for fluent API support)
     */
    public function setStoreAddresses(PropelCollection $storeAddresses, PropelPDO $con = null)
    {
        $storeAddressesToDelete = $this->getStoreAddresses(new Criteria(), $con)->diff($storeAddresses);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->storeAddressesScheduledForDeletion = clone $storeAddressesToDelete;

        foreach ($storeAddressesToDelete as $storeAddressRemoved) {
            $storeAddressRemoved->setStore(null);
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
                ->filterByStore($this)
                ->count($con);
        }

        return count($this->collStoreAddresses);
    }

    /**
     * Method called to associate a StoreAddress object to this object
     * through the StoreAddress foreign key attribute.
     *
     * @param    StoreAddress $l StoreAddress
     * @return Store The current object (for fluent API support)
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
        $storeAddress->setStore($this);
    }

    /**
     * @param	StoreAddress $storeAddress The storeAddress object to remove.
     * @return Store The current object (for fluent API support)
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
            $storeAddress->setStore(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Store is new, it will return
     * an empty collection; or if this Store has previously
     * been saved, it will retrieve related StoreAddresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Store.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StoreAddress[] List of StoreAddress objects
     */
    public function getStoreAddressesJoinAddress($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreAddressQuery::create(null, $criteria);
        $query->joinWith('Address', $join_behavior);

        return $this->getStoreAddresses($query, $con);
    }

    /**
     * Clears out the collStoreEmails collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Store The current object (for fluent API support)
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
     * If this Store is new, it will return
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
                    ->filterByStore($this)
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
     * @return Store The current object (for fluent API support)
     */
    public function setStoreEmails(PropelCollection $storeEmails, PropelPDO $con = null)
    {
        $storeEmailsToDelete = $this->getStoreEmails(new Criteria(), $con)->diff($storeEmails);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->storeEmailsScheduledForDeletion = clone $storeEmailsToDelete;

        foreach ($storeEmailsToDelete as $storeEmailRemoved) {
            $storeEmailRemoved->setStore(null);
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
                ->filterByStore($this)
                ->count($con);
        }

        return count($this->collStoreEmails);
    }

    /**
     * Method called to associate a StoreEmail object to this object
     * through the StoreEmail foreign key attribute.
     *
     * @param    StoreEmail $l StoreEmail
     * @return Store The current object (for fluent API support)
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
        $storeEmail->setStore($this);
    }

    /**
     * @param	StoreEmail $storeEmail The storeEmail object to remove.
     * @return Store The current object (for fluent API support)
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
            $storeEmail->setStore(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Store is new, it will return
     * an empty collection; or if this Store has previously
     * been saved, it will retrieve related StoreEmails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Store.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StoreEmail[] List of StoreEmail objects
     */
    public function getStoreEmailsJoinEmail($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreEmailQuery::create(null, $criteria);
        $query->joinWith('Email', $join_behavior);

        return $this->getStoreEmails($query, $con);
    }

    /**
     * Clears out the collStorePhones collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Store The current object (for fluent API support)
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
     * If this Store is new, it will return
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
                    ->filterByStore($this)
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
     * @return Store The current object (for fluent API support)
     */
    public function setStorePhones(PropelCollection $storePhones, PropelPDO $con = null)
    {
        $storePhonesToDelete = $this->getStorePhones(new Criteria(), $con)->diff($storePhones);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->storePhonesScheduledForDeletion = clone $storePhonesToDelete;

        foreach ($storePhonesToDelete as $storePhoneRemoved) {
            $storePhoneRemoved->setStore(null);
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
                ->filterByStore($this)
                ->count($con);
        }

        return count($this->collStorePhones);
    }

    /**
     * Method called to associate a StorePhone object to this object
     * through the StorePhone foreign key attribute.
     *
     * @param    StorePhone $l StorePhone
     * @return Store The current object (for fluent API support)
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
        $storePhone->setStore($this);
    }

    /**
     * @param	StorePhone $storePhone The storePhone object to remove.
     * @return Store The current object (for fluent API support)
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
            $storePhone->setStore(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Store is new, it will return
     * an empty collection; or if this Store has previously
     * been saved, it will retrieve related StorePhones from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Store.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StorePhone[] List of StorePhone objects
     */
    public function getStorePhonesJoinPhone($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StorePhoneQuery::create(null, $criteria);
        $query->joinWith('Phone', $join_behavior);

        return $this->getStorePhones($query, $con);
    }

    /**
     * Clears out the collStoreContacts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Store The current object (for fluent API support)
     * @see        addStoreContacts()
     */
    public function clearStoreContacts()
    {
        $this->collStoreContacts = null; // important to set this to null since that means it is uninitialized
        $this->collStoreContactsPartial = null;

        return $this;
    }

    /**
     * reset is the collStoreContacts collection loaded partially
     *
     * @return void
     */
    public function resetPartialStoreContacts($v = true)
    {
        $this->collStoreContactsPartial = $v;
    }

    /**
     * Initializes the collStoreContacts collection.
     *
     * By default this just sets the collStoreContacts collection to an empty array (like clearcollStoreContacts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStoreContacts($overrideExisting = true)
    {
        if (null !== $this->collStoreContacts && !$overrideExisting) {
            return;
        }
        $this->collStoreContacts = new PropelObjectCollection();
        $this->collStoreContacts->setModel('StoreContact');
    }

    /**
     * Gets an array of StoreContact objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Store is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|StoreContact[] List of StoreContact objects
     * @throws PropelException
     */
    public function getStoreContacts($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStoreContactsPartial && !$this->isNew();
        if (null === $this->collStoreContacts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStoreContacts) {
                // return empty collection
                $this->initStoreContacts();
            } else {
                $collStoreContacts = StoreContactQuery::create(null, $criteria)
                    ->filterByContactStore($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStoreContactsPartial && count($collStoreContacts)) {
                      $this->initStoreContacts(false);

                      foreach ($collStoreContacts as $obj) {
                        if (false == $this->collStoreContacts->contains($obj)) {
                          $this->collStoreContacts->append($obj);
                        }
                      }

                      $this->collStoreContactsPartial = true;
                    }

                    $collStoreContacts->getInternalIterator()->rewind();

                    return $collStoreContacts;
                }

                if ($partial && $this->collStoreContacts) {
                    foreach ($this->collStoreContacts as $obj) {
                        if ($obj->isNew()) {
                            $collStoreContacts[] = $obj;
                        }
                    }
                }

                $this->collStoreContacts = $collStoreContacts;
                $this->collStoreContactsPartial = false;
            }
        }

        return $this->collStoreContacts;
    }

    /**
     * Sets a collection of StoreContact objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $storeContacts A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Store The current object (for fluent API support)
     */
    public function setStoreContacts(PropelCollection $storeContacts, PropelPDO $con = null)
    {
        $storeContactsToDelete = $this->getStoreContacts(new Criteria(), $con)->diff($storeContacts);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->storeContactsScheduledForDeletion = clone $storeContactsToDelete;

        foreach ($storeContactsToDelete as $storeContactRemoved) {
            $storeContactRemoved->setContactStore(null);
        }

        $this->collStoreContacts = null;
        foreach ($storeContacts as $storeContact) {
            $this->addStoreContact($storeContact);
        }

        $this->collStoreContacts = $storeContacts;
        $this->collStoreContactsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StoreContact objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related StoreContact objects.
     * @throws PropelException
     */
    public function countStoreContacts(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStoreContactsPartial && !$this->isNew();
        if (null === $this->collStoreContacts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStoreContacts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStoreContacts());
            }
            $query = StoreContactQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByContactStore($this)
                ->count($con);
        }

        return count($this->collStoreContacts);
    }

    /**
     * Method called to associate a StoreContact object to this object
     * through the StoreContact foreign key attribute.
     *
     * @param    StoreContact $l StoreContact
     * @return Store The current object (for fluent API support)
     */
    public function addStoreContact(StoreContact $l)
    {
        if ($this->collStoreContacts === null) {
            $this->initStoreContacts();
            $this->collStoreContactsPartial = true;
        }

        if (!in_array($l, $this->collStoreContacts->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStoreContact($l);

            if ($this->storeContactsScheduledForDeletion and $this->storeContactsScheduledForDeletion->contains($l)) {
                $this->storeContactsScheduledForDeletion->remove($this->storeContactsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	StoreContact $storeContact The storeContact object to add.
     */
    protected function doAddStoreContact($storeContact)
    {
        $this->collStoreContacts[]= $storeContact;
        $storeContact->setContactStore($this);
    }

    /**
     * @param	StoreContact $storeContact The storeContact object to remove.
     * @return Store The current object (for fluent API support)
     */
    public function removeStoreContact($storeContact)
    {
        if ($this->getStoreContacts()->contains($storeContact)) {
            $this->collStoreContacts->remove($this->collStoreContacts->search($storeContact));
            if (null === $this->storeContactsScheduledForDeletion) {
                $this->storeContactsScheduledForDeletion = clone $this->collStoreContacts;
                $this->storeContactsScheduledForDeletion->clear();
            }
            $this->storeContactsScheduledForDeletion[]= clone $storeContact;
            $storeContact->setContactStore(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Store is new, it will return
     * an empty collection; or if this Store has previously
     * been saved, it will retrieve related StoreContacts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Store.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StoreContact[] List of StoreContact objects
     */
    public function getStoreContactsJoinContact($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreContactQuery::create(null, $criteria);
        $query->joinWith('Contact', $join_behavior);

        return $this->getStoreContacts($query, $con);
    }

    /**
     * Clears out the collStoreInformants collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Store The current object (for fluent API support)
     * @see        addStoreInformants()
     */
    public function clearStoreInformants()
    {
        $this->collStoreInformants = null; // important to set this to null since that means it is uninitialized
        $this->collStoreInformantsPartial = null;

        return $this;
    }

    /**
     * reset is the collStoreInformants collection loaded partially
     *
     * @return void
     */
    public function resetPartialStoreInformants($v = true)
    {
        $this->collStoreInformantsPartial = $v;
    }

    /**
     * Initializes the collStoreInformants collection.
     *
     * By default this just sets the collStoreInformants collection to an empty array (like clearcollStoreInformants());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStoreInformants($overrideExisting = true)
    {
        if (null !== $this->collStoreInformants && !$overrideExisting) {
            return;
        }
        $this->collStoreInformants = new PropelObjectCollection();
        $this->collStoreInformants->setModel('StoreInformant');
    }

    /**
     * Gets an array of StoreInformant objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Store is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|StoreInformant[] List of StoreInformant objects
     * @throws PropelException
     */
    public function getStoreInformants($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStoreInformantsPartial && !$this->isNew();
        if (null === $this->collStoreInformants || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStoreInformants) {
                // return empty collection
                $this->initStoreInformants();
            } else {
                $collStoreInformants = StoreInformantQuery::create(null, $criteria)
                    ->filterByInformantStore($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStoreInformantsPartial && count($collStoreInformants)) {
                      $this->initStoreInformants(false);

                      foreach ($collStoreInformants as $obj) {
                        if (false == $this->collStoreInformants->contains($obj)) {
                          $this->collStoreInformants->append($obj);
                        }
                      }

                      $this->collStoreInformantsPartial = true;
                    }

                    $collStoreInformants->getInternalIterator()->rewind();

                    return $collStoreInformants;
                }

                if ($partial && $this->collStoreInformants) {
                    foreach ($this->collStoreInformants as $obj) {
                        if ($obj->isNew()) {
                            $collStoreInformants[] = $obj;
                        }
                    }
                }

                $this->collStoreInformants = $collStoreInformants;
                $this->collStoreInformantsPartial = false;
            }
        }

        return $this->collStoreInformants;
    }

    /**
     * Sets a collection of StoreInformant objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $storeInformants A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Store The current object (for fluent API support)
     */
    public function setStoreInformants(PropelCollection $storeInformants, PropelPDO $con = null)
    {
        $storeInformantsToDelete = $this->getStoreInformants(new Criteria(), $con)->diff($storeInformants);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->storeInformantsScheduledForDeletion = clone $storeInformantsToDelete;

        foreach ($storeInformantsToDelete as $storeInformantRemoved) {
            $storeInformantRemoved->setInformantStore(null);
        }

        $this->collStoreInformants = null;
        foreach ($storeInformants as $storeInformant) {
            $this->addStoreInformant($storeInformant);
        }

        $this->collStoreInformants = $storeInformants;
        $this->collStoreInformantsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StoreInformant objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related StoreInformant objects.
     * @throws PropelException
     */
    public function countStoreInformants(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStoreInformantsPartial && !$this->isNew();
        if (null === $this->collStoreInformants || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStoreInformants) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStoreInformants());
            }
            $query = StoreInformantQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInformantStore($this)
                ->count($con);
        }

        return count($this->collStoreInformants);
    }

    /**
     * Method called to associate a StoreInformant object to this object
     * through the StoreInformant foreign key attribute.
     *
     * @param    StoreInformant $l StoreInformant
     * @return Store The current object (for fluent API support)
     */
    public function addStoreInformant(StoreInformant $l)
    {
        if ($this->collStoreInformants === null) {
            $this->initStoreInformants();
            $this->collStoreInformantsPartial = true;
        }

        if (!in_array($l, $this->collStoreInformants->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStoreInformant($l);

            if ($this->storeInformantsScheduledForDeletion and $this->storeInformantsScheduledForDeletion->contains($l)) {
                $this->storeInformantsScheduledForDeletion->remove($this->storeInformantsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	StoreInformant $storeInformant The storeInformant object to add.
     */
    protected function doAddStoreInformant($storeInformant)
    {
        $this->collStoreInformants[]= $storeInformant;
        $storeInformant->setInformantStore($this);
    }

    /**
     * @param	StoreInformant $storeInformant The storeInformant object to remove.
     * @return Store The current object (for fluent API support)
     */
    public function removeStoreInformant($storeInformant)
    {
        if ($this->getStoreInformants()->contains($storeInformant)) {
            $this->collStoreInformants->remove($this->collStoreInformants->search($storeInformant));
            if (null === $this->storeInformantsScheduledForDeletion) {
                $this->storeInformantsScheduledForDeletion = clone $this->collStoreInformants;
                $this->storeInformantsScheduledForDeletion->clear();
            }
            $this->storeInformantsScheduledForDeletion[]= clone $storeInformant;
            $storeInformant->setInformantStore(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Store is new, it will return
     * an empty collection; or if this Store has previously
     * been saved, it will retrieve related StoreInformants from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Store.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StoreInformant[] List of StoreInformant objects
     */
    public function getStoreInformantsJoinInformant($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreInformantQuery::create(null, $criteria);
        $query->joinWith('Informant', $join_behavior);

        return $this->getStoreInformants($query, $con);
    }

    /**
     * Clears out the collStoreOwners collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Store The current object (for fluent API support)
     * @see        addStoreOwners()
     */
    public function clearStoreOwners()
    {
        $this->collStoreOwners = null; // important to set this to null since that means it is uninitialized
        $this->collStoreOwnersPartial = null;

        return $this;
    }

    /**
     * reset is the collStoreOwners collection loaded partially
     *
     * @return void
     */
    public function resetPartialStoreOwners($v = true)
    {
        $this->collStoreOwnersPartial = $v;
    }

    /**
     * Initializes the collStoreOwners collection.
     *
     * By default this just sets the collStoreOwners collection to an empty array (like clearcollStoreOwners());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStoreOwners($overrideExisting = true)
    {
        if (null !== $this->collStoreOwners && !$overrideExisting) {
            return;
        }
        $this->collStoreOwners = new PropelObjectCollection();
        $this->collStoreOwners->setModel('StoreOwner');
    }

    /**
     * Gets an array of StoreOwner objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Store is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|StoreOwner[] List of StoreOwner objects
     * @throws PropelException
     */
    public function getStoreOwners($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStoreOwnersPartial && !$this->isNew();
        if (null === $this->collStoreOwners || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStoreOwners) {
                // return empty collection
                $this->initStoreOwners();
            } else {
                $collStoreOwners = StoreOwnerQuery::create(null, $criteria)
                    ->filterByOwnerStore($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStoreOwnersPartial && count($collStoreOwners)) {
                      $this->initStoreOwners(false);

                      foreach ($collStoreOwners as $obj) {
                        if (false == $this->collStoreOwners->contains($obj)) {
                          $this->collStoreOwners->append($obj);
                        }
                      }

                      $this->collStoreOwnersPartial = true;
                    }

                    $collStoreOwners->getInternalIterator()->rewind();

                    return $collStoreOwners;
                }

                if ($partial && $this->collStoreOwners) {
                    foreach ($this->collStoreOwners as $obj) {
                        if ($obj->isNew()) {
                            $collStoreOwners[] = $obj;
                        }
                    }
                }

                $this->collStoreOwners = $collStoreOwners;
                $this->collStoreOwnersPartial = false;
            }
        }

        return $this->collStoreOwners;
    }

    /**
     * Sets a collection of StoreOwner objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $storeOwners A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Store The current object (for fluent API support)
     */
    public function setStoreOwners(PropelCollection $storeOwners, PropelPDO $con = null)
    {
        $storeOwnersToDelete = $this->getStoreOwners(new Criteria(), $con)->diff($storeOwners);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->storeOwnersScheduledForDeletion = clone $storeOwnersToDelete;

        foreach ($storeOwnersToDelete as $storeOwnerRemoved) {
            $storeOwnerRemoved->setOwnerStore(null);
        }

        $this->collStoreOwners = null;
        foreach ($storeOwners as $storeOwner) {
            $this->addStoreOwner($storeOwner);
        }

        $this->collStoreOwners = $storeOwners;
        $this->collStoreOwnersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StoreOwner objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related StoreOwner objects.
     * @throws PropelException
     */
    public function countStoreOwners(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStoreOwnersPartial && !$this->isNew();
        if (null === $this->collStoreOwners || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStoreOwners) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStoreOwners());
            }
            $query = StoreOwnerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOwnerStore($this)
                ->count($con);
        }

        return count($this->collStoreOwners);
    }

    /**
     * Method called to associate a StoreOwner object to this object
     * through the StoreOwner foreign key attribute.
     *
     * @param    StoreOwner $l StoreOwner
     * @return Store The current object (for fluent API support)
     */
    public function addStoreOwner(StoreOwner $l)
    {
        if ($this->collStoreOwners === null) {
            $this->initStoreOwners();
            $this->collStoreOwnersPartial = true;
        }

        if (!in_array($l, $this->collStoreOwners->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStoreOwner($l);

            if ($this->storeOwnersScheduledForDeletion and $this->storeOwnersScheduledForDeletion->contains($l)) {
                $this->storeOwnersScheduledForDeletion->remove($this->storeOwnersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	StoreOwner $storeOwner The storeOwner object to add.
     */
    protected function doAddStoreOwner($storeOwner)
    {
        $this->collStoreOwners[]= $storeOwner;
        $storeOwner->setOwnerStore($this);
    }

    /**
     * @param	StoreOwner $storeOwner The storeOwner object to remove.
     * @return Store The current object (for fluent API support)
     */
    public function removeStoreOwner($storeOwner)
    {
        if ($this->getStoreOwners()->contains($storeOwner)) {
            $this->collStoreOwners->remove($this->collStoreOwners->search($storeOwner));
            if (null === $this->storeOwnersScheduledForDeletion) {
                $this->storeOwnersScheduledForDeletion = clone $this->collStoreOwners;
                $this->storeOwnersScheduledForDeletion->clear();
            }
            $this->storeOwnersScheduledForDeletion[]= clone $storeOwner;
            $storeOwner->setOwnerStore(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Store is new, it will return
     * an empty collection; or if this Store has previously
     * been saved, it will retrieve related StoreOwners from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Store.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StoreOwner[] List of StoreOwner objects
     */
    public function getStoreOwnersJoinOwner($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreOwnerQuery::create(null, $criteria);
        $query->joinWith('Owner', $join_behavior);

        return $this->getStoreOwners($query, $con);
    }

    /**
     * Clears out the collAddresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Store The current object (for fluent API support)
     * @see        addAddresses()
     */
    public function clearAddresses()
    {
        $this->collAddresses = null; // important to set this to null since that means it is uninitialized
        $this->collAddressesPartial = null;

        return $this;
    }

    /**
     * Initializes the collAddresses collection.
     *
     * By default this just sets the collAddresses collection to an empty collection (like clearAddresses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initAddresses()
    {
        $this->collAddresses = new PropelObjectCollection();
        $this->collAddresses->setModel('Address');
    }

    /**
     * Gets a collection of Address objects related by a many-to-many relationship
     * to the current object by way of the store_address cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Store is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Address[] List of Address objects
     */
    public function getAddresses($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collAddresses || null !== $criteria) {
            if ($this->isNew() && null === $this->collAddresses) {
                // return empty collection
                $this->initAddresses();
            } else {
                $collAddresses = AddressQuery::create(null, $criteria)
                    ->filterByStore($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collAddresses;
                }
                $this->collAddresses = $collAddresses;
            }
        }

        return $this->collAddresses;
    }

    /**
     * Sets a collection of Address objects related by a many-to-many relationship
     * to the current object by way of the store_address cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $addresses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Store The current object (for fluent API support)
     */
    public function setAddresses(PropelCollection $addresses, PropelPDO $con = null)
    {
        $this->clearAddresses();
        $currentAddresses = $this->getAddresses(null, $con);

        $this->addressesScheduledForDeletion = $currentAddresses->diff($addresses);

        foreach ($addresses as $address) {
            if (!$currentAddresses->contains($address)) {
                $this->doAddAddress($address);
            }
        }

        $this->collAddresses = $addresses;

        return $this;
    }

    /**
     * Gets the number of Address objects related by a many-to-many relationship
     * to the current object by way of the store_address cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Address objects
     */
    public function countAddresses($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collAddresses || null !== $criteria) {
            if ($this->isNew() && null === $this->collAddresses) {
                return 0;
            } else {
                $query = AddressQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByStore($this)
                    ->count($con);
            }
        } else {
            return count($this->collAddresses);
        }
    }

    /**
     * Associate a Address object to this object
     * through the store_address cross reference table.
     *
     * @param  Address $address The StoreAddress object to relate
     * @return Store The current object (for fluent API support)
     */
    public function addAddress(Address $address)
    {
        if ($this->collAddresses === null) {
            $this->initAddresses();
        }

        if (!$this->collAddresses->contains($address)) { // only add it if the **same** object is not already associated
            $this->doAddAddress($address);
            $this->collAddresses[] = $address;

            if ($this->addressesScheduledForDeletion and $this->addressesScheduledForDeletion->contains($address)) {
                $this->addressesScheduledForDeletion->remove($this->addressesScheduledForDeletion->search($address));
            }
        }

        return $this;
    }

    /**
     * @param	Address $address The address object to add.
     */
    protected function doAddAddress(Address $address)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$address->getStores()->contains($this)) { $storeAddress = new StoreAddress();
            $storeAddress->setAddress($address);
            $this->addStoreAddress($storeAddress);

            $foreignCollection = $address->getStores();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Address object to this object
     * through the store_address cross reference table.
     *
     * @param Address $address The StoreAddress object to relate
     * @return Store The current object (for fluent API support)
     */
    public function removeAddress(Address $address)
    {
        if ($this->getAddresses()->contains($address)) {
            $this->collAddresses->remove($this->collAddresses->search($address));
            if (null === $this->addressesScheduledForDeletion) {
                $this->addressesScheduledForDeletion = clone $this->collAddresses;
                $this->addressesScheduledForDeletion->clear();
            }
            $this->addressesScheduledForDeletion[]= $address;
        }

        return $this;
    }

    /**
     * Clears out the collEmails collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Store The current object (for fluent API support)
     * @see        addEmails()
     */
    public function clearEmails()
    {
        $this->collEmails = null; // important to set this to null since that means it is uninitialized
        $this->collEmailsPartial = null;

        return $this;
    }

    /**
     * Initializes the collEmails collection.
     *
     * By default this just sets the collEmails collection to an empty collection (like clearEmails());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initEmails()
    {
        $this->collEmails = new PropelObjectCollection();
        $this->collEmails->setModel('Email');
    }

    /**
     * Gets a collection of Email objects related by a many-to-many relationship
     * to the current object by way of the store_email cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Store is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Email[] List of Email objects
     */
    public function getEmails($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collEmails || null !== $criteria) {
            if ($this->isNew() && null === $this->collEmails) {
                // return empty collection
                $this->initEmails();
            } else {
                $collEmails = EmailQuery::create(null, $criteria)
                    ->filterByStore($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collEmails;
                }
                $this->collEmails = $collEmails;
            }
        }

        return $this->collEmails;
    }

    /**
     * Sets a collection of Email objects related by a many-to-many relationship
     * to the current object by way of the store_email cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $emails A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Store The current object (for fluent API support)
     */
    public function setEmails(PropelCollection $emails, PropelPDO $con = null)
    {
        $this->clearEmails();
        $currentEmails = $this->getEmails(null, $con);

        $this->emailsScheduledForDeletion = $currentEmails->diff($emails);

        foreach ($emails as $email) {
            if (!$currentEmails->contains($email)) {
                $this->doAddEmail($email);
            }
        }

        $this->collEmails = $emails;

        return $this;
    }

    /**
     * Gets the number of Email objects related by a many-to-many relationship
     * to the current object by way of the store_email cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Email objects
     */
    public function countEmails($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collEmails || null !== $criteria) {
            if ($this->isNew() && null === $this->collEmails) {
                return 0;
            } else {
                $query = EmailQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByStore($this)
                    ->count($con);
            }
        } else {
            return count($this->collEmails);
        }
    }

    /**
     * Associate a Email object to this object
     * through the store_email cross reference table.
     *
     * @param  Email $email The StoreEmail object to relate
     * @return Store The current object (for fluent API support)
     */
    public function addEmail(Email $email)
    {
        if ($this->collEmails === null) {
            $this->initEmails();
        }

        if (!$this->collEmails->contains($email)) { // only add it if the **same** object is not already associated
            $this->doAddEmail($email);
            $this->collEmails[] = $email;

            if ($this->emailsScheduledForDeletion and $this->emailsScheduledForDeletion->contains($email)) {
                $this->emailsScheduledForDeletion->remove($this->emailsScheduledForDeletion->search($email));
            }
        }

        return $this;
    }

    /**
     * @param	Email $email The email object to add.
     */
    protected function doAddEmail(Email $email)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$email->getStores()->contains($this)) { $storeEmail = new StoreEmail();
            $storeEmail->setEmail($email);
            $this->addStoreEmail($storeEmail);

            $foreignCollection = $email->getStores();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Email object to this object
     * through the store_email cross reference table.
     *
     * @param Email $email The StoreEmail object to relate
     * @return Store The current object (for fluent API support)
     */
    public function removeEmail(Email $email)
    {
        if ($this->getEmails()->contains($email)) {
            $this->collEmails->remove($this->collEmails->search($email));
            if (null === $this->emailsScheduledForDeletion) {
                $this->emailsScheduledForDeletion = clone $this->collEmails;
                $this->emailsScheduledForDeletion->clear();
            }
            $this->emailsScheduledForDeletion[]= $email;
        }

        return $this;
    }

    /**
     * Clears out the collPhones collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Store The current object (for fluent API support)
     * @see        addPhones()
     */
    public function clearPhones()
    {
        $this->collPhones = null; // important to set this to null since that means it is uninitialized
        $this->collPhonesPartial = null;

        return $this;
    }

    /**
     * Initializes the collPhones collection.
     *
     * By default this just sets the collPhones collection to an empty collection (like clearPhones());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPhones()
    {
        $this->collPhones = new PropelObjectCollection();
        $this->collPhones->setModel('Phone');
    }

    /**
     * Gets a collection of Phone objects related by a many-to-many relationship
     * to the current object by way of the store_phone cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Store is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Phone[] List of Phone objects
     */
    public function getPhones($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collPhones || null !== $criteria) {
            if ($this->isNew() && null === $this->collPhones) {
                // return empty collection
                $this->initPhones();
            } else {
                $collPhones = PhoneQuery::create(null, $criteria)
                    ->filterByStore($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collPhones;
                }
                $this->collPhones = $collPhones;
            }
        }

        return $this->collPhones;
    }

    /**
     * Sets a collection of Phone objects related by a many-to-many relationship
     * to the current object by way of the store_phone cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $phones A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Store The current object (for fluent API support)
     */
    public function setPhones(PropelCollection $phones, PropelPDO $con = null)
    {
        $this->clearPhones();
        $currentPhones = $this->getPhones(null, $con);

        $this->phonesScheduledForDeletion = $currentPhones->diff($phones);

        foreach ($phones as $phone) {
            if (!$currentPhones->contains($phone)) {
                $this->doAddPhone($phone);
            }
        }

        $this->collPhones = $phones;

        return $this;
    }

    /**
     * Gets the number of Phone objects related by a many-to-many relationship
     * to the current object by way of the store_phone cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Phone objects
     */
    public function countPhones($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collPhones || null !== $criteria) {
            if ($this->isNew() && null === $this->collPhones) {
                return 0;
            } else {
                $query = PhoneQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByStore($this)
                    ->count($con);
            }
        } else {
            return count($this->collPhones);
        }
    }

    /**
     * Associate a Phone object to this object
     * through the store_phone cross reference table.
     *
     * @param  Phone $phone The StorePhone object to relate
     * @return Store The current object (for fluent API support)
     */
    public function addPhone(Phone $phone)
    {
        if ($this->collPhones === null) {
            $this->initPhones();
        }

        if (!$this->collPhones->contains($phone)) { // only add it if the **same** object is not already associated
            $this->doAddPhone($phone);
            $this->collPhones[] = $phone;

            if ($this->phonesScheduledForDeletion and $this->phonesScheduledForDeletion->contains($phone)) {
                $this->phonesScheduledForDeletion->remove($this->phonesScheduledForDeletion->search($phone));
            }
        }

        return $this;
    }

    /**
     * @param	Phone $phone The phone object to add.
     */
    protected function doAddPhone(Phone $phone)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$phone->getStores()->contains($this)) { $storePhone = new StorePhone();
            $storePhone->setPhone($phone);
            $this->addStorePhone($storePhone);

            $foreignCollection = $phone->getStores();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Phone object to this object
     * through the store_phone cross reference table.
     *
     * @param Phone $phone The StorePhone object to relate
     * @return Store The current object (for fluent API support)
     */
    public function removePhone(Phone $phone)
    {
        if ($this->getPhones()->contains($phone)) {
            $this->collPhones->remove($this->collPhones->search($phone));
            if (null === $this->phonesScheduledForDeletion) {
                $this->phonesScheduledForDeletion = clone $this->collPhones;
                $this->phonesScheduledForDeletion->clear();
            }
            $this->phonesScheduledForDeletion[]= $phone;
        }

        return $this;
    }

    /**
     * Clears out the collContacts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Store The current object (for fluent API support)
     * @see        addContacts()
     */
    public function clearContacts()
    {
        $this->collContacts = null; // important to set this to null since that means it is uninitialized
        $this->collContactsPartial = null;

        return $this;
    }

    /**
     * Initializes the collContacts collection.
     *
     * By default this just sets the collContacts collection to an empty collection (like clearContacts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initContacts()
    {
        $this->collContacts = new PropelObjectCollection();
        $this->collContacts->setModel('User');
    }

    /**
     * Gets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the store_contact cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Store is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|User[] List of User objects
     */
    public function getContacts($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collContacts || null !== $criteria) {
            if ($this->isNew() && null === $this->collContacts) {
                // return empty collection
                $this->initContacts();
            } else {
                $collContacts = UserQuery::create(null, $criteria)
                    ->filterByContactStore($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collContacts;
                }
                $this->collContacts = $collContacts;
            }
        }

        return $this->collContacts;
    }

    /**
     * Sets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the store_contact cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $contacts A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Store The current object (for fluent API support)
     */
    public function setContacts(PropelCollection $contacts, PropelPDO $con = null)
    {
        $this->clearContacts();
        $currentContacts = $this->getContacts(null, $con);

        $this->contactsScheduledForDeletion = $currentContacts->diff($contacts);

        foreach ($contacts as $contact) {
            if (!$currentContacts->contains($contact)) {
                $this->doAddContact($contact);
            }
        }

        $this->collContacts = $contacts;

        return $this;
    }

    /**
     * Gets the number of User objects related by a many-to-many relationship
     * to the current object by way of the store_contact cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related User objects
     */
    public function countContacts($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collContacts || null !== $criteria) {
            if ($this->isNew() && null === $this->collContacts) {
                return 0;
            } else {
                $query = UserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByContactStore($this)
                    ->count($con);
            }
        } else {
            return count($this->collContacts);
        }
    }

    /**
     * Associate a User object to this object
     * through the store_contact cross reference table.
     *
     * @param  User $user The StoreContact object to relate
     * @return Store The current object (for fluent API support)
     */
    public function addContact(User $user)
    {
        if ($this->collContacts === null) {
            $this->initContacts();
        }

        if (!$this->collContacts->contains($user)) { // only add it if the **same** object is not already associated
            $this->doAddContact($user);
            $this->collContacts[] = $user;

            if ($this->contactsScheduledForDeletion and $this->contactsScheduledForDeletion->contains($user)) {
                $this->contactsScheduledForDeletion->remove($this->contactsScheduledForDeletion->search($user));
            }
        }

        return $this;
    }

    /**
     * @param	Contact $contact The contact object to add.
     */
    protected function doAddContact(User $contact)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$contact->getContactStores()->contains($this)) { $storeContact = new StoreContact();
            $storeContact->setContact($contact);
            $this->addStoreContact($storeContact);

            $foreignCollection = $contact->getContactStores();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a User object to this object
     * through the store_contact cross reference table.
     *
     * @param User $user The StoreContact object to relate
     * @return Store The current object (for fluent API support)
     */
    public function removeContact(User $user)
    {
        if ($this->getContacts()->contains($user)) {
            $this->collContacts->remove($this->collContacts->search($user));
            if (null === $this->contactsScheduledForDeletion) {
                $this->contactsScheduledForDeletion = clone $this->collContacts;
                $this->contactsScheduledForDeletion->clear();
            }
            $this->contactsScheduledForDeletion[]= $user;
        }

        return $this;
    }

    /**
     * Clears out the collInformants collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Store The current object (for fluent API support)
     * @see        addInformants()
     */
    public function clearInformants()
    {
        $this->collInformants = null; // important to set this to null since that means it is uninitialized
        $this->collInformantsPartial = null;

        return $this;
    }

    /**
     * Initializes the collInformants collection.
     *
     * By default this just sets the collInformants collection to an empty collection (like clearInformants());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initInformants()
    {
        $this->collInformants = new PropelObjectCollection();
        $this->collInformants->setModel('User');
    }

    /**
     * Gets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the store_informant cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Store is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|User[] List of User objects
     */
    public function getInformants($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collInformants || null !== $criteria) {
            if ($this->isNew() && null === $this->collInformants) {
                // return empty collection
                $this->initInformants();
            } else {
                $collInformants = UserQuery::create(null, $criteria)
                    ->filterByInformantStore($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collInformants;
                }
                $this->collInformants = $collInformants;
            }
        }

        return $this->collInformants;
    }

    /**
     * Sets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the store_informant cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $informants A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Store The current object (for fluent API support)
     */
    public function setInformants(PropelCollection $informants, PropelPDO $con = null)
    {
        $this->clearInformants();
        $currentInformants = $this->getInformants(null, $con);

        $this->informantsScheduledForDeletion = $currentInformants->diff($informants);

        foreach ($informants as $informant) {
            if (!$currentInformants->contains($informant)) {
                $this->doAddInformant($informant);
            }
        }

        $this->collInformants = $informants;

        return $this;
    }

    /**
     * Gets the number of User objects related by a many-to-many relationship
     * to the current object by way of the store_informant cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related User objects
     */
    public function countInformants($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collInformants || null !== $criteria) {
            if ($this->isNew() && null === $this->collInformants) {
                return 0;
            } else {
                $query = UserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByInformantStore($this)
                    ->count($con);
            }
        } else {
            return count($this->collInformants);
        }
    }

    /**
     * Associate a User object to this object
     * through the store_informant cross reference table.
     *
     * @param  User $user The StoreInformant object to relate
     * @return Store The current object (for fluent API support)
     */
    public function addInformant(User $user)
    {
        if ($this->collInformants === null) {
            $this->initInformants();
        }

        if (!$this->collInformants->contains($user)) { // only add it if the **same** object is not already associated
            $this->doAddInformant($user);
            $this->collInformants[] = $user;

            if ($this->informantsScheduledForDeletion and $this->informantsScheduledForDeletion->contains($user)) {
                $this->informantsScheduledForDeletion->remove($this->informantsScheduledForDeletion->search($user));
            }
        }

        return $this;
    }

    /**
     * @param	Informant $informant The informant object to add.
     */
    protected function doAddInformant(User $informant)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$informant->getInformantStores()->contains($this)) { $storeInformant = new StoreInformant();
            $storeInformant->setInformant($informant);
            $this->addStoreInformant($storeInformant);

            $foreignCollection = $informant->getInformantStores();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a User object to this object
     * through the store_informant cross reference table.
     *
     * @param User $user The StoreInformant object to relate
     * @return Store The current object (for fluent API support)
     */
    public function removeInformant(User $user)
    {
        if ($this->getInformants()->contains($user)) {
            $this->collInformants->remove($this->collInformants->search($user));
            if (null === $this->informantsScheduledForDeletion) {
                $this->informantsScheduledForDeletion = clone $this->collInformants;
                $this->informantsScheduledForDeletion->clear();
            }
            $this->informantsScheduledForDeletion[]= $user;
        }

        return $this;
    }

    /**
     * Clears out the collOwners collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Store The current object (for fluent API support)
     * @see        addOwners()
     */
    public function clearOwners()
    {
        $this->collOwners = null; // important to set this to null since that means it is uninitialized
        $this->collOwnersPartial = null;

        return $this;
    }

    /**
     * Initializes the collOwners collection.
     *
     * By default this just sets the collOwners collection to an empty collection (like clearOwners());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initOwners()
    {
        $this->collOwners = new PropelObjectCollection();
        $this->collOwners->setModel('User');
    }

    /**
     * Gets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the store_owner cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Store is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|User[] List of User objects
     */
    public function getOwners($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collOwners || null !== $criteria) {
            if ($this->isNew() && null === $this->collOwners) {
                // return empty collection
                $this->initOwners();
            } else {
                $collOwners = UserQuery::create(null, $criteria)
                    ->filterByOwnerStore($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collOwners;
                }
                $this->collOwners = $collOwners;
            }
        }

        return $this->collOwners;
    }

    /**
     * Sets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the store_owner cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $owners A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Store The current object (for fluent API support)
     */
    public function setOwners(PropelCollection $owners, PropelPDO $con = null)
    {
        $this->clearOwners();
        $currentOwners = $this->getOwners(null, $con);

        $this->ownersScheduledForDeletion = $currentOwners->diff($owners);

        foreach ($owners as $owner) {
            if (!$currentOwners->contains($owner)) {
                $this->doAddOwner($owner);
            }
        }

        $this->collOwners = $owners;

        return $this;
    }

    /**
     * Gets the number of User objects related by a many-to-many relationship
     * to the current object by way of the store_owner cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related User objects
     */
    public function countOwners($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collOwners || null !== $criteria) {
            if ($this->isNew() && null === $this->collOwners) {
                return 0;
            } else {
                $query = UserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByOwnerStore($this)
                    ->count($con);
            }
        } else {
            return count($this->collOwners);
        }
    }

    /**
     * Associate a User object to this object
     * through the store_owner cross reference table.
     *
     * @param  User $user The StoreOwner object to relate
     * @return Store The current object (for fluent API support)
     */
    public function addOwner(User $user)
    {
        if ($this->collOwners === null) {
            $this->initOwners();
        }

        if (!$this->collOwners->contains($user)) { // only add it if the **same** object is not already associated
            $this->doAddOwner($user);
            $this->collOwners[] = $user;

            if ($this->ownersScheduledForDeletion and $this->ownersScheduledForDeletion->contains($user)) {
                $this->ownersScheduledForDeletion->remove($this->ownersScheduledForDeletion->search($user));
            }
        }

        return $this;
    }

    /**
     * @param	Owner $owner The owner object to add.
     */
    protected function doAddOwner(User $owner)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$owner->getOwnerStores()->contains($this)) { $storeOwner = new StoreOwner();
            $storeOwner->setOwner($owner);
            $this->addStoreOwner($storeOwner);

            $foreignCollection = $owner->getOwnerStores();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a User object to this object
     * through the store_owner cross reference table.
     *
     * @param User $user The StoreOwner object to relate
     * @return Store The current object (for fluent API support)
     */
    public function removeOwner(User $user)
    {
        if ($this->getOwners()->contains($user)) {
            $this->collOwners->remove($this->collOwners->search($user));
            if (null === $this->ownersScheduledForDeletion) {
                $this->ownersScheduledForDeletion = clone $this->collOwners;
                $this->ownersScheduledForDeletion->clear();
            }
            $this->ownersScheduledForDeletion[]= $user;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->main_company = null;
        $this->name = null;
        $this->description = null;
        $this->type = null;
        $this->code = null;
        $this->website = null;
        $this->region = null;
        $this->remarks = null;
        $this->payment_method = null;
        $this->bank_account_number = null;
        $this->vat_number = null;
        $this->coc_number = null;
        $this->is_enabled = null;
        $this->is_deleted = null;
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
            if ($this->collStoreAddresses) {
                foreach ($this->collStoreAddresses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStoreEmails) {
                foreach ($this->collStoreEmails as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStorePhones) {
                foreach ($this->collStorePhones as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStoreContacts) {
                foreach ($this->collStoreContacts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStoreInformants) {
                foreach ($this->collStoreInformants as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStoreOwners) {
                foreach ($this->collStoreOwners as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAddresses) {
                foreach ($this->collAddresses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEmails) {
                foreach ($this->collEmails as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPhones) {
                foreach ($this->collPhones as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collContacts) {
                foreach ($this->collContacts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInformants) {
                foreach ($this->collInformants as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOwners) {
                foreach ($this->collOwners as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aCompany instanceof Persistent) {
              $this->aCompany->clearAllReferences($deep);
            }
            if ($this->aStoreType instanceof Persistent) {
              $this->aStoreType->clearAllReferences($deep);
            }
            if ($this->aRegions instanceof Persistent) {
              $this->aRegions->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collStoreAddresses instanceof PropelCollection) {
            $this->collStoreAddresses->clearIterator();
        }
        $this->collStoreAddresses = null;
        if ($this->collStoreEmails instanceof PropelCollection) {
            $this->collStoreEmails->clearIterator();
        }
        $this->collStoreEmails = null;
        if ($this->collStorePhones instanceof PropelCollection) {
            $this->collStorePhones->clearIterator();
        }
        $this->collStorePhones = null;
        if ($this->collStoreContacts instanceof PropelCollection) {
            $this->collStoreContacts->clearIterator();
        }
        $this->collStoreContacts = null;
        if ($this->collStoreInformants instanceof PropelCollection) {
            $this->collStoreInformants->clearIterator();
        }
        $this->collStoreInformants = null;
        if ($this->collStoreOwners instanceof PropelCollection) {
            $this->collStoreOwners->clearIterator();
        }
        $this->collStoreOwners = null;
        if ($this->collAddresses instanceof PropelCollection) {
            $this->collAddresses->clearIterator();
        }
        $this->collAddresses = null;
        if ($this->collEmails instanceof PropelCollection) {
            $this->collEmails->clearIterator();
        }
        $this->collEmails = null;
        if ($this->collPhones instanceof PropelCollection) {
            $this->collPhones->clearIterator();
        }
        $this->collPhones = null;
        if ($this->collContacts instanceof PropelCollection) {
            $this->collContacts->clearIterator();
        }
        $this->collContacts = null;
        if ($this->collInformants instanceof PropelCollection) {
            $this->collInformants->clearIterator();
        }
        $this->collInformants = null;
        if ($this->collOwners instanceof PropelCollection) {
            $this->collOwners->clearIterator();
        }
        $this->collOwners = null;
        $this->aCompany = null;
        $this->aStoreType = null;
        $this->aRegions = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(StorePeer::DEFAULT_STRING_FORMAT);
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
     * @return     Store The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = StorePeer::UPDATED_AT;

        return $this;
    }

}
