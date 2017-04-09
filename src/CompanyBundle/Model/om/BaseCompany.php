<?php

namespace CompanyBundle\Model\om;

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
use CompanyBundle\Model\CompanyContact;
use CompanyBundle\Model\CompanyContactQuery;
use CompanyBundle\Model\CompanyEmail;
use CompanyBundle\Model\CompanyEmailQuery;
use CompanyBundle\Model\CompanyInformant;
use CompanyBundle\Model\CompanyInformantQuery;
use CompanyBundle\Model\CompanyOwner;
use CompanyBundle\Model\CompanyOwnerQuery;
use CompanyBundle\Model\CompanyPeer;
use CompanyBundle\Model\CompanyPhone;
use CompanyBundle\Model\CompanyPhoneQuery;
use CompanyBundle\Model\CompanyQuery;
use CompanyBundle\Model\CompanyType;
use CompanyBundle\Model\CompanyTypeQuery;
use CompanyBundle\Model\Regions;
use CompanyBundle\Model\RegionsQuery;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreQuery;
use UserBundle\Model\Address;
use UserBundle\Model\AddressQuery;
use UserBundle\Model\Email;
use UserBundle\Model\EmailQuery;
use UserBundle\Model\Phone;
use UserBundle\Model\PhoneQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserQuery;

abstract class BaseCompany extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'CompanyBundle\\Model\\CompanyPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CompanyPeer
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
     * @var        CompanyType
     */
    protected $aCompanyType;

    /**
     * @var        Regions
     */
    protected $aRegions;

    /**
     * @var        PropelObjectCollection|CompanyAddress[] Collection to store aggregation of CompanyAddress objects.
     */
    protected $collCompanyAddresses;
    protected $collCompanyAddressesPartial;

    /**
     * @var        PropelObjectCollection|CompanyEmail[] Collection to store aggregation of CompanyEmail objects.
     */
    protected $collCompanyEmails;
    protected $collCompanyEmailsPartial;

    /**
     * @var        PropelObjectCollection|CompanyPhone[] Collection to store aggregation of CompanyPhone objects.
     */
    protected $collCompanyPhones;
    protected $collCompanyPhonesPartial;

    /**
     * @var        PropelObjectCollection|CompanyContact[] Collection to store aggregation of CompanyContact objects.
     */
    protected $collCompanyContacts;
    protected $collCompanyContactsPartial;

    /**
     * @var        PropelObjectCollection|CompanyInformant[] Collection to store aggregation of CompanyInformant objects.
     */
    protected $collCompanyInformants;
    protected $collCompanyInformantsPartial;

    /**
     * @var        PropelObjectCollection|CompanyOwner[] Collection to store aggregation of CompanyOwner objects.
     */
    protected $collCompanyOwners;
    protected $collCompanyOwnersPartial;

    /**
     * @var        PropelObjectCollection|Store[] Collection to store aggregation of Store objects.
     */
    protected $collStores;
    protected $collStoresPartial;

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
    protected $companyAddressesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $companyEmailsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $companyPhonesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $companyContactsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $companyInformantsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $companyOwnersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $storesScheduledForDeletion = null;

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
     * Initializes internal state of BaseCompany object.
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
     * @return Company The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = CompanyPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Company The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = CompanyPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return Company The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = CompanyPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [type] column.
     *
     * @param  int $v new value
     * @return Company The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[] = CompanyPeer::TYPE;
        }

        if ($this->aCompanyType !== null && $this->aCompanyType->getId() !== $v) {
            $this->aCompanyType = null;
        }


        return $this;
    } // setType()

    /**
     * Set the value of [code] column.
     *
     * @param  string $v new value
     * @return Company The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[] = CompanyPeer::CODE;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [website] column.
     *
     * @param  string $v new value
     * @return Company The current object (for fluent API support)
     */
    public function setWebsite($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->website !== $v) {
            $this->website = $v;
            $this->modifiedColumns[] = CompanyPeer::WEBSITE;
        }


        return $this;
    } // setWebsite()

    /**
     * Set the value of [region] column.
     *
     * @param  int $v new value
     * @return Company The current object (for fluent API support)
     */
    public function setRegion($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->region !== $v) {
            $this->region = $v;
            $this->modifiedColumns[] = CompanyPeer::REGION;
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
     * @return Company The current object (for fluent API support)
     */
    public function setRemarks($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->remarks !== $v) {
            $this->remarks = $v;
            $this->modifiedColumns[] = CompanyPeer::REMARKS;
        }


        return $this;
    } // setRemarks()

    /**
     * Set the value of [payment_method] column.
     *
     * @param  int $v new value
     * @return Company The current object (for fluent API support)
     */
    public function setPaymentMethod($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->payment_method !== $v) {
            $this->payment_method = $v;
            $this->modifiedColumns[] = CompanyPeer::PAYMENT_METHOD;
        }


        return $this;
    } // setPaymentMethod()

    /**
     * Set the value of [bank_account_number] column.
     *
     * @param  string $v new value
     * @return Company The current object (for fluent API support)
     */
    public function setBankAccountNumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bank_account_number !== $v) {
            $this->bank_account_number = $v;
            $this->modifiedColumns[] = CompanyPeer::BANK_ACCOUNT_NUMBER;
        }


        return $this;
    } // setBankAccountNumber()

    /**
     * Set the value of [vat_number] column.
     *
     * @param  string $v new value
     * @return Company The current object (for fluent API support)
     */
    public function setVatNumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->vat_number !== $v) {
            $this->vat_number = $v;
            $this->modifiedColumns[] = CompanyPeer::VAT_NUMBER;
        }


        return $this;
    } // setVatNumber()

    /**
     * Set the value of [coc_number] column.
     *
     * @param  string $v new value
     * @return Company The current object (for fluent API support)
     */
    public function setCocNumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->coc_number !== $v) {
            $this->coc_number = $v;
            $this->modifiedColumns[] = CompanyPeer::COC_NUMBER;
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
     * @return Company The current object (for fluent API support)
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
            $this->modifiedColumns[] = CompanyPeer::IS_ENABLED;
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
     * @return Company The current object (for fluent API support)
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
            $this->modifiedColumns[] = CompanyPeer::IS_DELETED;
        }


        return $this;
    } // setIsDeleted()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Company The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = CompanyPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Company The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = CompanyPeer::UPDATED_AT;
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
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->description = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->type = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->code = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->website = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->region = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->remarks = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->payment_method = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->bank_account_number = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->vat_number = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->coc_number = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->is_enabled = ($row[$startcol + 12] !== null) ? (boolean) $row[$startcol + 12] : null;
            $this->is_deleted = ($row[$startcol + 13] !== null) ? (boolean) $row[$startcol + 13] : null;
            $this->created_at = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            $this->updated_at = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 16; // 16 = CompanyPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Company object", $e);
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

        if ($this->aCompanyType !== null && $this->type !== $this->aCompanyType->getId()) {
            $this->aCompanyType = null;
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
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CompanyPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCompanyType = null;
            $this->aRegions = null;
            $this->collCompanyAddresses = null;

            $this->collCompanyEmails = null;

            $this->collCompanyPhones = null;

            $this->collCompanyContacts = null;

            $this->collCompanyInformants = null;

            $this->collCompanyOwners = null;

            $this->collStores = null;

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
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = CompanyQuery::create()
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
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(CompanyPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(CompanyPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(CompanyPeer::UPDATED_AT)) {
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
                CompanyPeer::addInstanceToPool($this);
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

            if ($this->aCompanyType !== null) {
                if ($this->aCompanyType->isModified() || $this->aCompanyType->isNew()) {
                    $affectedRows += $this->aCompanyType->save($con);
                }
                $this->setCompanyType($this->aCompanyType);
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
                    CompanyAddressQuery::create()
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
                    CompanyEmailQuery::create()
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
                    CompanyPhoneQuery::create()
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
                    CompanyContactQuery::create()
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
                    CompanyInformantQuery::create()
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
                    CompanyOwnerQuery::create()
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

            if ($this->companyContactsScheduledForDeletion !== null) {
                if (!$this->companyContactsScheduledForDeletion->isEmpty()) {
                    CompanyContactQuery::create()
                        ->filterByPrimaryKeys($this->companyContactsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->companyContactsScheduledForDeletion = null;
                }
            }

            if ($this->collCompanyContacts !== null) {
                foreach ($this->collCompanyContacts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->companyInformantsScheduledForDeletion !== null) {
                if (!$this->companyInformantsScheduledForDeletion->isEmpty()) {
                    CompanyInformantQuery::create()
                        ->filterByPrimaryKeys($this->companyInformantsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->companyInformantsScheduledForDeletion = null;
                }
            }

            if ($this->collCompanyInformants !== null) {
                foreach ($this->collCompanyInformants as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->companyOwnersScheduledForDeletion !== null) {
                if (!$this->companyOwnersScheduledForDeletion->isEmpty()) {
                    CompanyOwnerQuery::create()
                        ->filterByPrimaryKeys($this->companyOwnersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->companyOwnersScheduledForDeletion = null;
                }
            }

            if ($this->collCompanyOwners !== null) {
                foreach ($this->collCompanyOwners as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->storesScheduledForDeletion !== null) {
                if (!$this->storesScheduledForDeletion->isEmpty()) {
                    foreach ($this->storesScheduledForDeletion as $store) {
                        // need to save related object because we set the relation to null
                        $store->save($con);
                    }
                    $this->storesScheduledForDeletion = null;
                }
            }

            if ($this->collStores !== null) {
                foreach ($this->collStores as $referrerFK) {
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

        $this->modifiedColumns[] = CompanyPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CompanyPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CompanyPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CompanyPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(CompanyPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(CompanyPeer::TYPE)) {
            $modifiedColumns[':p' . $index++]  = '`type`';
        }
        if ($this->isColumnModified(CompanyPeer::CODE)) {
            $modifiedColumns[':p' . $index++]  = '`code`';
        }
        if ($this->isColumnModified(CompanyPeer::WEBSITE)) {
            $modifiedColumns[':p' . $index++]  = '`website`';
        }
        if ($this->isColumnModified(CompanyPeer::REGION)) {
            $modifiedColumns[':p' . $index++]  = '`region`';
        }
        if ($this->isColumnModified(CompanyPeer::REMARKS)) {
            $modifiedColumns[':p' . $index++]  = '`remarks`';
        }
        if ($this->isColumnModified(CompanyPeer::PAYMENT_METHOD)) {
            $modifiedColumns[':p' . $index++]  = '`payment_method`';
        }
        if ($this->isColumnModified(CompanyPeer::BANK_ACCOUNT_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = '`bank_account_number`';
        }
        if ($this->isColumnModified(CompanyPeer::VAT_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = '`vat_number`';
        }
        if ($this->isColumnModified(CompanyPeer::COC_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = '`coc_number`';
        }
        if ($this->isColumnModified(CompanyPeer::IS_ENABLED)) {
            $modifiedColumns[':p' . $index++]  = '`is_enabled`';
        }
        if ($this->isColumnModified(CompanyPeer::IS_DELETED)) {
            $modifiedColumns[':p' . $index++]  = '`is_deleted`';
        }
        if ($this->isColumnModified(CompanyPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(CompanyPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `company` (%s) VALUES (%s)',
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

            if ($this->aCompanyType !== null) {
                if (!$this->aCompanyType->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCompanyType->getValidationFailures());
                }
            }

            if ($this->aRegions !== null) {
                if (!$this->aRegions->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aRegions->getValidationFailures());
                }
            }


            if (($retval = CompanyPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCompanyAddresses !== null) {
                    foreach ($this->collCompanyAddresses as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCompanyEmails !== null) {
                    foreach ($this->collCompanyEmails as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCompanyPhones !== null) {
                    foreach ($this->collCompanyPhones as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCompanyContacts !== null) {
                    foreach ($this->collCompanyContacts as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCompanyInformants !== null) {
                    foreach ($this->collCompanyInformants as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCompanyOwners !== null) {
                    foreach ($this->collCompanyOwners as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collStores !== null) {
                    foreach ($this->collStores as $referrerFK) {
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
        $pos = CompanyPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getName();
                break;
            case 2:
                return $this->getDescription();
                break;
            case 3:
                return $this->getType();
                break;
            case 4:
                return $this->getCode();
                break;
            case 5:
                return $this->getWebsite();
                break;
            case 6:
                return $this->getRegion();
                break;
            case 7:
                return $this->getRemarks();
                break;
            case 8:
                return $this->getPaymentMethod();
                break;
            case 9:
                return $this->getBankAccountNumber();
                break;
            case 10:
                return $this->getVatNumber();
                break;
            case 11:
                return $this->getCocNumber();
                break;
            case 12:
                return $this->getIsEnabled();
                break;
            case 13:
                return $this->getIsDeleted();
                break;
            case 14:
                return $this->getCreatedAt();
                break;
            case 15:
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
        if (isset($alreadyDumpedObjects['Company'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Company'][$this->getPrimaryKey()] = true;
        $keys = CompanyPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getDescription(),
            $keys[3] => $this->getType(),
            $keys[4] => $this->getCode(),
            $keys[5] => $this->getWebsite(),
            $keys[6] => $this->getRegion(),
            $keys[7] => $this->getRemarks(),
            $keys[8] => $this->getPaymentMethod(),
            $keys[9] => $this->getBankAccountNumber(),
            $keys[10] => $this->getVatNumber(),
            $keys[11] => $this->getCocNumber(),
            $keys[12] => $this->getIsEnabled(),
            $keys[13] => $this->getIsDeleted(),
            $keys[14] => $this->getCreatedAt(),
            $keys[15] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCompanyType) {
                $result['CompanyType'] = $this->aCompanyType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aRegions) {
                $result['Regions'] = $this->aRegions->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCompanyAddresses) {
                $result['CompanyAddresses'] = $this->collCompanyAddresses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCompanyEmails) {
                $result['CompanyEmails'] = $this->collCompanyEmails->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCompanyPhones) {
                $result['CompanyPhones'] = $this->collCompanyPhones->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCompanyContacts) {
                $result['CompanyContacts'] = $this->collCompanyContacts->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCompanyInformants) {
                $result['CompanyInformants'] = $this->collCompanyInformants->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCompanyOwners) {
                $result['CompanyOwners'] = $this->collCompanyOwners->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStores) {
                $result['Stores'] = $this->collStores->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = CompanyPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setName($value);
                break;
            case 2:
                $this->setDescription($value);
                break;
            case 3:
                $this->setType($value);
                break;
            case 4:
                $this->setCode($value);
                break;
            case 5:
                $this->setWebsite($value);
                break;
            case 6:
                $this->setRegion($value);
                break;
            case 7:
                $this->setRemarks($value);
                break;
            case 8:
                $this->setPaymentMethod($value);
                break;
            case 9:
                $this->setBankAccountNumber($value);
                break;
            case 10:
                $this->setVatNumber($value);
                break;
            case 11:
                $this->setCocNumber($value);
                break;
            case 12:
                $this->setIsEnabled($value);
                break;
            case 13:
                $this->setIsDeleted($value);
                break;
            case 14:
                $this->setCreatedAt($value);
                break;
            case 15:
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
        $keys = CompanyPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDescription($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setType($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setCode($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setWebsite($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setRegion($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setRemarks($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setPaymentMethod($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setBankAccountNumber($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setVatNumber($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setCocNumber($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setIsEnabled($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setIsDeleted($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setCreatedAt($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setUpdatedAt($arr[$keys[15]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CompanyPeer::DATABASE_NAME);

        if ($this->isColumnModified(CompanyPeer::ID)) $criteria->add(CompanyPeer::ID, $this->id);
        if ($this->isColumnModified(CompanyPeer::NAME)) $criteria->add(CompanyPeer::NAME, $this->name);
        if ($this->isColumnModified(CompanyPeer::DESCRIPTION)) $criteria->add(CompanyPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(CompanyPeer::TYPE)) $criteria->add(CompanyPeer::TYPE, $this->type);
        if ($this->isColumnModified(CompanyPeer::CODE)) $criteria->add(CompanyPeer::CODE, $this->code);
        if ($this->isColumnModified(CompanyPeer::WEBSITE)) $criteria->add(CompanyPeer::WEBSITE, $this->website);
        if ($this->isColumnModified(CompanyPeer::REGION)) $criteria->add(CompanyPeer::REGION, $this->region);
        if ($this->isColumnModified(CompanyPeer::REMARKS)) $criteria->add(CompanyPeer::REMARKS, $this->remarks);
        if ($this->isColumnModified(CompanyPeer::PAYMENT_METHOD)) $criteria->add(CompanyPeer::PAYMENT_METHOD, $this->payment_method);
        if ($this->isColumnModified(CompanyPeer::BANK_ACCOUNT_NUMBER)) $criteria->add(CompanyPeer::BANK_ACCOUNT_NUMBER, $this->bank_account_number);
        if ($this->isColumnModified(CompanyPeer::VAT_NUMBER)) $criteria->add(CompanyPeer::VAT_NUMBER, $this->vat_number);
        if ($this->isColumnModified(CompanyPeer::COC_NUMBER)) $criteria->add(CompanyPeer::COC_NUMBER, $this->coc_number);
        if ($this->isColumnModified(CompanyPeer::IS_ENABLED)) $criteria->add(CompanyPeer::IS_ENABLED, $this->is_enabled);
        if ($this->isColumnModified(CompanyPeer::IS_DELETED)) $criteria->add(CompanyPeer::IS_DELETED, $this->is_deleted);
        if ($this->isColumnModified(CompanyPeer::CREATED_AT)) $criteria->add(CompanyPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(CompanyPeer::UPDATED_AT)) $criteria->add(CompanyPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(CompanyPeer::DATABASE_NAME);
        $criteria->add(CompanyPeer::ID, $this->id);

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
     * @param object $copyObj An object of Company (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
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

            foreach ($this->getCompanyAddresses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCompanyAddress($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCompanyEmails() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCompanyEmail($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCompanyPhones() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCompanyPhone($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCompanyContacts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCompanyContact($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCompanyInformants() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCompanyInformant($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCompanyOwners() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCompanyOwner($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStores() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStore($relObj->copy($deepCopy));
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
     * @return Company Clone of current object.
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
     * @return CompanyPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CompanyPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a CompanyType object.
     *
     * @param                  CompanyType $v
     * @return Company The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCompanyType(CompanyType $v = null)
    {
        if ($v === null) {
            $this->setType(NULL);
        } else {
            $this->setType($v->getId());
        }

        $this->aCompanyType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the CompanyType object, it will not be re-added.
        if ($v !== null) {
            $v->addCompany($this);
        }


        return $this;
    }


    /**
     * Get the associated CompanyType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return CompanyType The associated CompanyType object.
     * @throws PropelException
     */
    public function getCompanyType(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCompanyType === null && ($this->type !== null) && $doQuery) {
            $this->aCompanyType = CompanyTypeQuery::create()->findPk($this->type, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCompanyType->addCompanies($this);
             */
        }

        return $this->aCompanyType;
    }

    /**
     * Declares an association between this object and a Regions object.
     *
     * @param                  Regions $v
     * @return Company The current object (for fluent API support)
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
            $v->addCompany($this);
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
                $this->aRegions->addCompanies($this);
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
        if ('CompanyAddress' == $relationName) {
            $this->initCompanyAddresses();
        }
        if ('CompanyEmail' == $relationName) {
            $this->initCompanyEmails();
        }
        if ('CompanyPhone' == $relationName) {
            $this->initCompanyPhones();
        }
        if ('CompanyContact' == $relationName) {
            $this->initCompanyContacts();
        }
        if ('CompanyInformant' == $relationName) {
            $this->initCompanyInformants();
        }
        if ('CompanyOwner' == $relationName) {
            $this->initCompanyOwners();
        }
        if ('Store' == $relationName) {
            $this->initStores();
        }
    }

    /**
     * Clears out the collCompanyAddresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Company The current object (for fluent API support)
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
     * If this Company is new, it will return
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
                    ->filterByCompany($this)
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
     * @return Company The current object (for fluent API support)
     */
    public function setCompanyAddresses(PropelCollection $companyAddresses, PropelPDO $con = null)
    {
        $companyAddressesToDelete = $this->getCompanyAddresses(new Criteria(), $con)->diff($companyAddresses);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->companyAddressesScheduledForDeletion = clone $companyAddressesToDelete;

        foreach ($companyAddressesToDelete as $companyAddressRemoved) {
            $companyAddressRemoved->setCompany(null);
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
                ->filterByCompany($this)
                ->count($con);
        }

        return count($this->collCompanyAddresses);
    }

    /**
     * Method called to associate a CompanyAddress object to this object
     * through the CompanyAddress foreign key attribute.
     *
     * @param    CompanyAddress $l CompanyAddress
     * @return Company The current object (for fluent API support)
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
        $companyAddress->setCompany($this);
    }

    /**
     * @param	CompanyAddress $companyAddress The companyAddress object to remove.
     * @return Company The current object (for fluent API support)
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
            $companyAddress->setCompany(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Company is new, it will return
     * an empty collection; or if this Company has previously
     * been saved, it will retrieve related CompanyAddresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Company.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CompanyAddress[] List of CompanyAddress objects
     */
    public function getCompanyAddressesJoinAddress($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CompanyAddressQuery::create(null, $criteria);
        $query->joinWith('Address', $join_behavior);

        return $this->getCompanyAddresses($query, $con);
    }

    /**
     * Clears out the collCompanyEmails collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Company The current object (for fluent API support)
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
     * If this Company is new, it will return
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
                    ->filterByCompany($this)
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
     * @return Company The current object (for fluent API support)
     */
    public function setCompanyEmails(PropelCollection $companyEmails, PropelPDO $con = null)
    {
        $companyEmailsToDelete = $this->getCompanyEmails(new Criteria(), $con)->diff($companyEmails);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->companyEmailsScheduledForDeletion = clone $companyEmailsToDelete;

        foreach ($companyEmailsToDelete as $companyEmailRemoved) {
            $companyEmailRemoved->setCompany(null);
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
                ->filterByCompany($this)
                ->count($con);
        }

        return count($this->collCompanyEmails);
    }

    /**
     * Method called to associate a CompanyEmail object to this object
     * through the CompanyEmail foreign key attribute.
     *
     * @param    CompanyEmail $l CompanyEmail
     * @return Company The current object (for fluent API support)
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
        $companyEmail->setCompany($this);
    }

    /**
     * @param	CompanyEmail $companyEmail The companyEmail object to remove.
     * @return Company The current object (for fluent API support)
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
            $companyEmail->setCompany(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Company is new, it will return
     * an empty collection; or if this Company has previously
     * been saved, it will retrieve related CompanyEmails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Company.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CompanyEmail[] List of CompanyEmail objects
     */
    public function getCompanyEmailsJoinEmail($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CompanyEmailQuery::create(null, $criteria);
        $query->joinWith('Email', $join_behavior);

        return $this->getCompanyEmails($query, $con);
    }

    /**
     * Clears out the collCompanyPhones collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Company The current object (for fluent API support)
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
     * If this Company is new, it will return
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
                    ->filterByCompany($this)
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
     * @return Company The current object (for fluent API support)
     */
    public function setCompanyPhones(PropelCollection $companyPhones, PropelPDO $con = null)
    {
        $companyPhonesToDelete = $this->getCompanyPhones(new Criteria(), $con)->diff($companyPhones);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->companyPhonesScheduledForDeletion = clone $companyPhonesToDelete;

        foreach ($companyPhonesToDelete as $companyPhoneRemoved) {
            $companyPhoneRemoved->setCompany(null);
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
                ->filterByCompany($this)
                ->count($con);
        }

        return count($this->collCompanyPhones);
    }

    /**
     * Method called to associate a CompanyPhone object to this object
     * through the CompanyPhone foreign key attribute.
     *
     * @param    CompanyPhone $l CompanyPhone
     * @return Company The current object (for fluent API support)
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
        $companyPhone->setCompany($this);
    }

    /**
     * @param	CompanyPhone $companyPhone The companyPhone object to remove.
     * @return Company The current object (for fluent API support)
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
            $companyPhone->setCompany(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Company is new, it will return
     * an empty collection; or if this Company has previously
     * been saved, it will retrieve related CompanyPhones from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Company.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CompanyPhone[] List of CompanyPhone objects
     */
    public function getCompanyPhonesJoinPhone($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CompanyPhoneQuery::create(null, $criteria);
        $query->joinWith('Phone', $join_behavior);

        return $this->getCompanyPhones($query, $con);
    }

    /**
     * Clears out the collCompanyContacts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Company The current object (for fluent API support)
     * @see        addCompanyContacts()
     */
    public function clearCompanyContacts()
    {
        $this->collCompanyContacts = null; // important to set this to null since that means it is uninitialized
        $this->collCompanyContactsPartial = null;

        return $this;
    }

    /**
     * reset is the collCompanyContacts collection loaded partially
     *
     * @return void
     */
    public function resetPartialCompanyContacts($v = true)
    {
        $this->collCompanyContactsPartial = $v;
    }

    /**
     * Initializes the collCompanyContacts collection.
     *
     * By default this just sets the collCompanyContacts collection to an empty array (like clearcollCompanyContacts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCompanyContacts($overrideExisting = true)
    {
        if (null !== $this->collCompanyContacts && !$overrideExisting) {
            return;
        }
        $this->collCompanyContacts = new PropelObjectCollection();
        $this->collCompanyContacts->setModel('CompanyContact');
    }

    /**
     * Gets an array of CompanyContact objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Company is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CompanyContact[] List of CompanyContact objects
     * @throws PropelException
     */
    public function getCompanyContacts($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCompanyContactsPartial && !$this->isNew();
        if (null === $this->collCompanyContacts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCompanyContacts) {
                // return empty collection
                $this->initCompanyContacts();
            } else {
                $collCompanyContacts = CompanyContactQuery::create(null, $criteria)
                    ->filterByContactCompany($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCompanyContactsPartial && count($collCompanyContacts)) {
                      $this->initCompanyContacts(false);

                      foreach ($collCompanyContacts as $obj) {
                        if (false == $this->collCompanyContacts->contains($obj)) {
                          $this->collCompanyContacts->append($obj);
                        }
                      }

                      $this->collCompanyContactsPartial = true;
                    }

                    $collCompanyContacts->getInternalIterator()->rewind();

                    return $collCompanyContacts;
                }

                if ($partial && $this->collCompanyContacts) {
                    foreach ($this->collCompanyContacts as $obj) {
                        if ($obj->isNew()) {
                            $collCompanyContacts[] = $obj;
                        }
                    }
                }

                $this->collCompanyContacts = $collCompanyContacts;
                $this->collCompanyContactsPartial = false;
            }
        }

        return $this->collCompanyContacts;
    }

    /**
     * Sets a collection of CompanyContact objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $companyContacts A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Company The current object (for fluent API support)
     */
    public function setCompanyContacts(PropelCollection $companyContacts, PropelPDO $con = null)
    {
        $companyContactsToDelete = $this->getCompanyContacts(new Criteria(), $con)->diff($companyContacts);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->companyContactsScheduledForDeletion = clone $companyContactsToDelete;

        foreach ($companyContactsToDelete as $companyContactRemoved) {
            $companyContactRemoved->setContactCompany(null);
        }

        $this->collCompanyContacts = null;
        foreach ($companyContacts as $companyContact) {
            $this->addCompanyContact($companyContact);
        }

        $this->collCompanyContacts = $companyContacts;
        $this->collCompanyContactsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CompanyContact objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CompanyContact objects.
     * @throws PropelException
     */
    public function countCompanyContacts(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCompanyContactsPartial && !$this->isNew();
        if (null === $this->collCompanyContacts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCompanyContacts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCompanyContacts());
            }
            $query = CompanyContactQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByContactCompany($this)
                ->count($con);
        }

        return count($this->collCompanyContacts);
    }

    /**
     * Method called to associate a CompanyContact object to this object
     * through the CompanyContact foreign key attribute.
     *
     * @param    CompanyContact $l CompanyContact
     * @return Company The current object (for fluent API support)
     */
    public function addCompanyContact(CompanyContact $l)
    {
        if ($this->collCompanyContacts === null) {
            $this->initCompanyContacts();
            $this->collCompanyContactsPartial = true;
        }

        if (!in_array($l, $this->collCompanyContacts->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCompanyContact($l);

            if ($this->companyContactsScheduledForDeletion and $this->companyContactsScheduledForDeletion->contains($l)) {
                $this->companyContactsScheduledForDeletion->remove($this->companyContactsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CompanyContact $companyContact The companyContact object to add.
     */
    protected function doAddCompanyContact($companyContact)
    {
        $this->collCompanyContacts[]= $companyContact;
        $companyContact->setContactCompany($this);
    }

    /**
     * @param	CompanyContact $companyContact The companyContact object to remove.
     * @return Company The current object (for fluent API support)
     */
    public function removeCompanyContact($companyContact)
    {
        if ($this->getCompanyContacts()->contains($companyContact)) {
            $this->collCompanyContacts->remove($this->collCompanyContacts->search($companyContact));
            if (null === $this->companyContactsScheduledForDeletion) {
                $this->companyContactsScheduledForDeletion = clone $this->collCompanyContacts;
                $this->companyContactsScheduledForDeletion->clear();
            }
            $this->companyContactsScheduledForDeletion[]= clone $companyContact;
            $companyContact->setContactCompany(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Company is new, it will return
     * an empty collection; or if this Company has previously
     * been saved, it will retrieve related CompanyContacts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Company.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CompanyContact[] List of CompanyContact objects
     */
    public function getCompanyContactsJoinContact($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CompanyContactQuery::create(null, $criteria);
        $query->joinWith('Contact', $join_behavior);

        return $this->getCompanyContacts($query, $con);
    }

    /**
     * Clears out the collCompanyInformants collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Company The current object (for fluent API support)
     * @see        addCompanyInformants()
     */
    public function clearCompanyInformants()
    {
        $this->collCompanyInformants = null; // important to set this to null since that means it is uninitialized
        $this->collCompanyInformantsPartial = null;

        return $this;
    }

    /**
     * reset is the collCompanyInformants collection loaded partially
     *
     * @return void
     */
    public function resetPartialCompanyInformants($v = true)
    {
        $this->collCompanyInformantsPartial = $v;
    }

    /**
     * Initializes the collCompanyInformants collection.
     *
     * By default this just sets the collCompanyInformants collection to an empty array (like clearcollCompanyInformants());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCompanyInformants($overrideExisting = true)
    {
        if (null !== $this->collCompanyInformants && !$overrideExisting) {
            return;
        }
        $this->collCompanyInformants = new PropelObjectCollection();
        $this->collCompanyInformants->setModel('CompanyInformant');
    }

    /**
     * Gets an array of CompanyInformant objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Company is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CompanyInformant[] List of CompanyInformant objects
     * @throws PropelException
     */
    public function getCompanyInformants($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCompanyInformantsPartial && !$this->isNew();
        if (null === $this->collCompanyInformants || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCompanyInformants) {
                // return empty collection
                $this->initCompanyInformants();
            } else {
                $collCompanyInformants = CompanyInformantQuery::create(null, $criteria)
                    ->filterByInformantCompany($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCompanyInformantsPartial && count($collCompanyInformants)) {
                      $this->initCompanyInformants(false);

                      foreach ($collCompanyInformants as $obj) {
                        if (false == $this->collCompanyInformants->contains($obj)) {
                          $this->collCompanyInformants->append($obj);
                        }
                      }

                      $this->collCompanyInformantsPartial = true;
                    }

                    $collCompanyInformants->getInternalIterator()->rewind();

                    return $collCompanyInformants;
                }

                if ($partial && $this->collCompanyInformants) {
                    foreach ($this->collCompanyInformants as $obj) {
                        if ($obj->isNew()) {
                            $collCompanyInformants[] = $obj;
                        }
                    }
                }

                $this->collCompanyInformants = $collCompanyInformants;
                $this->collCompanyInformantsPartial = false;
            }
        }

        return $this->collCompanyInformants;
    }

    /**
     * Sets a collection of CompanyInformant objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $companyInformants A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Company The current object (for fluent API support)
     */
    public function setCompanyInformants(PropelCollection $companyInformants, PropelPDO $con = null)
    {
        $companyInformantsToDelete = $this->getCompanyInformants(new Criteria(), $con)->diff($companyInformants);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->companyInformantsScheduledForDeletion = clone $companyInformantsToDelete;

        foreach ($companyInformantsToDelete as $companyInformantRemoved) {
            $companyInformantRemoved->setInformantCompany(null);
        }

        $this->collCompanyInformants = null;
        foreach ($companyInformants as $companyInformant) {
            $this->addCompanyInformant($companyInformant);
        }

        $this->collCompanyInformants = $companyInformants;
        $this->collCompanyInformantsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CompanyInformant objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CompanyInformant objects.
     * @throws PropelException
     */
    public function countCompanyInformants(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCompanyInformantsPartial && !$this->isNew();
        if (null === $this->collCompanyInformants || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCompanyInformants) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCompanyInformants());
            }
            $query = CompanyInformantQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInformantCompany($this)
                ->count($con);
        }

        return count($this->collCompanyInformants);
    }

    /**
     * Method called to associate a CompanyInformant object to this object
     * through the CompanyInformant foreign key attribute.
     *
     * @param    CompanyInformant $l CompanyInformant
     * @return Company The current object (for fluent API support)
     */
    public function addCompanyInformant(CompanyInformant $l)
    {
        if ($this->collCompanyInformants === null) {
            $this->initCompanyInformants();
            $this->collCompanyInformantsPartial = true;
        }

        if (!in_array($l, $this->collCompanyInformants->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCompanyInformant($l);

            if ($this->companyInformantsScheduledForDeletion and $this->companyInformantsScheduledForDeletion->contains($l)) {
                $this->companyInformantsScheduledForDeletion->remove($this->companyInformantsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CompanyInformant $companyInformant The companyInformant object to add.
     */
    protected function doAddCompanyInformant($companyInformant)
    {
        $this->collCompanyInformants[]= $companyInformant;
        $companyInformant->setInformantCompany($this);
    }

    /**
     * @param	CompanyInformant $companyInformant The companyInformant object to remove.
     * @return Company The current object (for fluent API support)
     */
    public function removeCompanyInformant($companyInformant)
    {
        if ($this->getCompanyInformants()->contains($companyInformant)) {
            $this->collCompanyInformants->remove($this->collCompanyInformants->search($companyInformant));
            if (null === $this->companyInformantsScheduledForDeletion) {
                $this->companyInformantsScheduledForDeletion = clone $this->collCompanyInformants;
                $this->companyInformantsScheduledForDeletion->clear();
            }
            $this->companyInformantsScheduledForDeletion[]= clone $companyInformant;
            $companyInformant->setInformantCompany(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Company is new, it will return
     * an empty collection; or if this Company has previously
     * been saved, it will retrieve related CompanyInformants from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Company.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CompanyInformant[] List of CompanyInformant objects
     */
    public function getCompanyInformantsJoinInformant($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CompanyInformantQuery::create(null, $criteria);
        $query->joinWith('Informant', $join_behavior);

        return $this->getCompanyInformants($query, $con);
    }

    /**
     * Clears out the collCompanyOwners collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Company The current object (for fluent API support)
     * @see        addCompanyOwners()
     */
    public function clearCompanyOwners()
    {
        $this->collCompanyOwners = null; // important to set this to null since that means it is uninitialized
        $this->collCompanyOwnersPartial = null;

        return $this;
    }

    /**
     * reset is the collCompanyOwners collection loaded partially
     *
     * @return void
     */
    public function resetPartialCompanyOwners($v = true)
    {
        $this->collCompanyOwnersPartial = $v;
    }

    /**
     * Initializes the collCompanyOwners collection.
     *
     * By default this just sets the collCompanyOwners collection to an empty array (like clearcollCompanyOwners());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCompanyOwners($overrideExisting = true)
    {
        if (null !== $this->collCompanyOwners && !$overrideExisting) {
            return;
        }
        $this->collCompanyOwners = new PropelObjectCollection();
        $this->collCompanyOwners->setModel('CompanyOwner');
    }

    /**
     * Gets an array of CompanyOwner objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Company is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CompanyOwner[] List of CompanyOwner objects
     * @throws PropelException
     */
    public function getCompanyOwners($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCompanyOwnersPartial && !$this->isNew();
        if (null === $this->collCompanyOwners || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCompanyOwners) {
                // return empty collection
                $this->initCompanyOwners();
            } else {
                $collCompanyOwners = CompanyOwnerQuery::create(null, $criteria)
                    ->filterByOwnerCompany($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCompanyOwnersPartial && count($collCompanyOwners)) {
                      $this->initCompanyOwners(false);

                      foreach ($collCompanyOwners as $obj) {
                        if (false == $this->collCompanyOwners->contains($obj)) {
                          $this->collCompanyOwners->append($obj);
                        }
                      }

                      $this->collCompanyOwnersPartial = true;
                    }

                    $collCompanyOwners->getInternalIterator()->rewind();

                    return $collCompanyOwners;
                }

                if ($partial && $this->collCompanyOwners) {
                    foreach ($this->collCompanyOwners as $obj) {
                        if ($obj->isNew()) {
                            $collCompanyOwners[] = $obj;
                        }
                    }
                }

                $this->collCompanyOwners = $collCompanyOwners;
                $this->collCompanyOwnersPartial = false;
            }
        }

        return $this->collCompanyOwners;
    }

    /**
     * Sets a collection of CompanyOwner objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $companyOwners A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Company The current object (for fluent API support)
     */
    public function setCompanyOwners(PropelCollection $companyOwners, PropelPDO $con = null)
    {
        $companyOwnersToDelete = $this->getCompanyOwners(new Criteria(), $con)->diff($companyOwners);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->companyOwnersScheduledForDeletion = clone $companyOwnersToDelete;

        foreach ($companyOwnersToDelete as $companyOwnerRemoved) {
            $companyOwnerRemoved->setOwnerCompany(null);
        }

        $this->collCompanyOwners = null;
        foreach ($companyOwners as $companyOwner) {
            $this->addCompanyOwner($companyOwner);
        }

        $this->collCompanyOwners = $companyOwners;
        $this->collCompanyOwnersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CompanyOwner objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CompanyOwner objects.
     * @throws PropelException
     */
    public function countCompanyOwners(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCompanyOwnersPartial && !$this->isNew();
        if (null === $this->collCompanyOwners || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCompanyOwners) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCompanyOwners());
            }
            $query = CompanyOwnerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOwnerCompany($this)
                ->count($con);
        }

        return count($this->collCompanyOwners);
    }

    /**
     * Method called to associate a CompanyOwner object to this object
     * through the CompanyOwner foreign key attribute.
     *
     * @param    CompanyOwner $l CompanyOwner
     * @return Company The current object (for fluent API support)
     */
    public function addCompanyOwner(CompanyOwner $l)
    {
        if ($this->collCompanyOwners === null) {
            $this->initCompanyOwners();
            $this->collCompanyOwnersPartial = true;
        }

        if (!in_array($l, $this->collCompanyOwners->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCompanyOwner($l);

            if ($this->companyOwnersScheduledForDeletion and $this->companyOwnersScheduledForDeletion->contains($l)) {
                $this->companyOwnersScheduledForDeletion->remove($this->companyOwnersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CompanyOwner $companyOwner The companyOwner object to add.
     */
    protected function doAddCompanyOwner($companyOwner)
    {
        $this->collCompanyOwners[]= $companyOwner;
        $companyOwner->setOwnerCompany($this);
    }

    /**
     * @param	CompanyOwner $companyOwner The companyOwner object to remove.
     * @return Company The current object (for fluent API support)
     */
    public function removeCompanyOwner($companyOwner)
    {
        if ($this->getCompanyOwners()->contains($companyOwner)) {
            $this->collCompanyOwners->remove($this->collCompanyOwners->search($companyOwner));
            if (null === $this->companyOwnersScheduledForDeletion) {
                $this->companyOwnersScheduledForDeletion = clone $this->collCompanyOwners;
                $this->companyOwnersScheduledForDeletion->clear();
            }
            $this->companyOwnersScheduledForDeletion[]= clone $companyOwner;
            $companyOwner->setOwnerCompany(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Company is new, it will return
     * an empty collection; or if this Company has previously
     * been saved, it will retrieve related CompanyOwners from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Company.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CompanyOwner[] List of CompanyOwner objects
     */
    public function getCompanyOwnersJoinOwner($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CompanyOwnerQuery::create(null, $criteria);
        $query->joinWith('Owner', $join_behavior);

        return $this->getCompanyOwners($query, $con);
    }

    /**
     * Clears out the collStores collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Company The current object (for fluent API support)
     * @see        addStores()
     */
    public function clearStores()
    {
        $this->collStores = null; // important to set this to null since that means it is uninitialized
        $this->collStoresPartial = null;

        return $this;
    }

    /**
     * reset is the collStores collection loaded partially
     *
     * @return void
     */
    public function resetPartialStores($v = true)
    {
        $this->collStoresPartial = $v;
    }

    /**
     * Initializes the collStores collection.
     *
     * By default this just sets the collStores collection to an empty array (like clearcollStores());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStores($overrideExisting = true)
    {
        if (null !== $this->collStores && !$overrideExisting) {
            return;
        }
        $this->collStores = new PropelObjectCollection();
        $this->collStores->setModel('Store');
    }

    /**
     * Gets an array of Store objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Company is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Store[] List of Store objects
     * @throws PropelException
     */
    public function getStores($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collStoresPartial && !$this->isNew();
        if (null === $this->collStores || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collStores) {
                // return empty collection
                $this->initStores();
            } else {
                $collStores = StoreQuery::create(null, $criteria)
                    ->filterByCompany($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collStoresPartial && count($collStores)) {
                      $this->initStores(false);

                      foreach ($collStores as $obj) {
                        if (false == $this->collStores->contains($obj)) {
                          $this->collStores->append($obj);
                        }
                      }

                      $this->collStoresPartial = true;
                    }

                    $collStores->getInternalIterator()->rewind();

                    return $collStores;
                }

                if ($partial && $this->collStores) {
                    foreach ($this->collStores as $obj) {
                        if ($obj->isNew()) {
                            $collStores[] = $obj;
                        }
                    }
                }

                $this->collStores = $collStores;
                $this->collStoresPartial = false;
            }
        }

        return $this->collStores;
    }

    /**
     * Sets a collection of Store objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $stores A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Company The current object (for fluent API support)
     */
    public function setStores(PropelCollection $stores, PropelPDO $con = null)
    {
        $storesToDelete = $this->getStores(new Criteria(), $con)->diff($stores);


        $this->storesScheduledForDeletion = $storesToDelete;

        foreach ($storesToDelete as $storeRemoved) {
            $storeRemoved->setCompany(null);
        }

        $this->collStores = null;
        foreach ($stores as $store) {
            $this->addStore($store);
        }

        $this->collStores = $stores;
        $this->collStoresPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Store objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Store objects.
     * @throws PropelException
     */
    public function countStores(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collStoresPartial && !$this->isNew();
        if (null === $this->collStores || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStores) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStores());
            }
            $query = StoreQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCompany($this)
                ->count($con);
        }

        return count($this->collStores);
    }

    /**
     * Method called to associate a Store object to this object
     * through the Store foreign key attribute.
     *
     * @param    Store $l Store
     * @return Company The current object (for fluent API support)
     */
    public function addStore(Store $l)
    {
        if ($this->collStores === null) {
            $this->initStores();
            $this->collStoresPartial = true;
        }

        if (!in_array($l, $this->collStores->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddStore($l);

            if ($this->storesScheduledForDeletion and $this->storesScheduledForDeletion->contains($l)) {
                $this->storesScheduledForDeletion->remove($this->storesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Store $store The store object to add.
     */
    protected function doAddStore($store)
    {
        $this->collStores[]= $store;
        $store->setCompany($this);
    }

    /**
     * @param	Store $store The store object to remove.
     * @return Company The current object (for fluent API support)
     */
    public function removeStore($store)
    {
        if ($this->getStores()->contains($store)) {
            $this->collStores->remove($this->collStores->search($store));
            if (null === $this->storesScheduledForDeletion) {
                $this->storesScheduledForDeletion = clone $this->collStores;
                $this->storesScheduledForDeletion->clear();
            }
            $this->storesScheduledForDeletion[]= $store;
            $store->setCompany(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Company is new, it will return
     * an empty collection; or if this Company has previously
     * been saved, it will retrieve related Stores from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Company.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Store[] List of Store objects
     */
    public function getStoresJoinStoreType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreQuery::create(null, $criteria);
        $query->joinWith('StoreType', $join_behavior);

        return $this->getStores($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Company is new, it will return
     * an empty collection; or if this Company has previously
     * been saved, it will retrieve related Stores from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Company.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Store[] List of Store objects
     */
    public function getStoresJoinRegions($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreQuery::create(null, $criteria);
        $query->joinWith('Regions', $join_behavior);

        return $this->getStores($query, $con);
    }

    /**
     * Clears out the collAddresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Company The current object (for fluent API support)
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
     * to the current object by way of the company_address cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Company is new, it will return
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
                    ->filterByCompany($this)
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
     * to the current object by way of the company_address cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $addresses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Company The current object (for fluent API support)
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
     * to the current object by way of the company_address cross-reference table.
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
                    ->filterByCompany($this)
                    ->count($con);
            }
        } else {
            return count($this->collAddresses);
        }
    }

    /**
     * Associate a Address object to this object
     * through the company_address cross reference table.
     *
     * @param  Address $address The CompanyAddress object to relate
     * @return Company The current object (for fluent API support)
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
        if (!$address->getCompanies()->contains($this)) { $companyAddress = new CompanyAddress();
            $companyAddress->setAddress($address);
            $this->addCompanyAddress($companyAddress);

            $foreignCollection = $address->getCompanies();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Address object to this object
     * through the company_address cross reference table.
     *
     * @param Address $address The CompanyAddress object to relate
     * @return Company The current object (for fluent API support)
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
     * @return Company The current object (for fluent API support)
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
     * to the current object by way of the company_email cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Company is new, it will return
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
                    ->filterByCompany($this)
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
     * to the current object by way of the company_email cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $emails A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Company The current object (for fluent API support)
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
     * to the current object by way of the company_email cross-reference table.
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
                    ->filterByCompany($this)
                    ->count($con);
            }
        } else {
            return count($this->collEmails);
        }
    }

    /**
     * Associate a Email object to this object
     * through the company_email cross reference table.
     *
     * @param  Email $email The CompanyEmail object to relate
     * @return Company The current object (for fluent API support)
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
        if (!$email->getCompanies()->contains($this)) { $companyEmail = new CompanyEmail();
            $companyEmail->setEmail($email);
            $this->addCompanyEmail($companyEmail);

            $foreignCollection = $email->getCompanies();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Email object to this object
     * through the company_email cross reference table.
     *
     * @param Email $email The CompanyEmail object to relate
     * @return Company The current object (for fluent API support)
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
     * @return Company The current object (for fluent API support)
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
     * to the current object by way of the company_phone cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Company is new, it will return
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
                    ->filterByCompany($this)
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
     * to the current object by way of the company_phone cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $phones A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Company The current object (for fluent API support)
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
     * to the current object by way of the company_phone cross-reference table.
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
                    ->filterByCompany($this)
                    ->count($con);
            }
        } else {
            return count($this->collPhones);
        }
    }

    /**
     * Associate a Phone object to this object
     * through the company_phone cross reference table.
     *
     * @param  Phone $phone The CompanyPhone object to relate
     * @return Company The current object (for fluent API support)
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
        if (!$phone->getCompanies()->contains($this)) { $companyPhone = new CompanyPhone();
            $companyPhone->setPhone($phone);
            $this->addCompanyPhone($companyPhone);

            $foreignCollection = $phone->getCompanies();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Phone object to this object
     * through the company_phone cross reference table.
     *
     * @param Phone $phone The CompanyPhone object to relate
     * @return Company The current object (for fluent API support)
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
     * @return Company The current object (for fluent API support)
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
     * to the current object by way of the company_contact cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Company is new, it will return
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
                    ->filterByContactCompany($this)
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
     * to the current object by way of the company_contact cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $contacts A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Company The current object (for fluent API support)
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
     * to the current object by way of the company_contact cross-reference table.
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
                    ->filterByContactCompany($this)
                    ->count($con);
            }
        } else {
            return count($this->collContacts);
        }
    }

    /**
     * Associate a User object to this object
     * through the company_contact cross reference table.
     *
     * @param  User $user The CompanyContact object to relate
     * @return Company The current object (for fluent API support)
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
        if (!$contact->getContactCompanies()->contains($this)) { $companyContact = new CompanyContact();
            $companyContact->setContact($contact);
            $this->addCompanyContact($companyContact);

            $foreignCollection = $contact->getContactCompanies();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a User object to this object
     * through the company_contact cross reference table.
     *
     * @param User $user The CompanyContact object to relate
     * @return Company The current object (for fluent API support)
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
     * @return Company The current object (for fluent API support)
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
     * to the current object by way of the company_informant cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Company is new, it will return
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
                    ->filterByInformantCompany($this)
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
     * to the current object by way of the company_informant cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $informants A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Company The current object (for fluent API support)
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
     * to the current object by way of the company_informant cross-reference table.
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
                    ->filterByInformantCompany($this)
                    ->count($con);
            }
        } else {
            return count($this->collInformants);
        }
    }

    /**
     * Associate a User object to this object
     * through the company_informant cross reference table.
     *
     * @param  User $user The CompanyInformant object to relate
     * @return Company The current object (for fluent API support)
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
        if (!$informant->getInformantCompanies()->contains($this)) { $companyInformant = new CompanyInformant();
            $companyInformant->setInformant($informant);
            $this->addCompanyInformant($companyInformant);

            $foreignCollection = $informant->getInformantCompanies();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a User object to this object
     * through the company_informant cross reference table.
     *
     * @param User $user The CompanyInformant object to relate
     * @return Company The current object (for fluent API support)
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
     * @return Company The current object (for fluent API support)
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
     * to the current object by way of the company_owner cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Company is new, it will return
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
                    ->filterByOwnerCompany($this)
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
     * to the current object by way of the company_owner cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $owners A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Company The current object (for fluent API support)
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
     * to the current object by way of the company_owner cross-reference table.
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
                    ->filterByOwnerCompany($this)
                    ->count($con);
            }
        } else {
            return count($this->collOwners);
        }
    }

    /**
     * Associate a User object to this object
     * through the company_owner cross reference table.
     *
     * @param  User $user The CompanyOwner object to relate
     * @return Company The current object (for fluent API support)
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
        if (!$owner->getOwnerCompanies()->contains($this)) { $companyOwner = new CompanyOwner();
            $companyOwner->setOwner($owner);
            $this->addCompanyOwner($companyOwner);

            $foreignCollection = $owner->getOwnerCompanies();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a User object to this object
     * through the company_owner cross reference table.
     *
     * @param User $user The CompanyOwner object to relate
     * @return Company The current object (for fluent API support)
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
            if ($this->collCompanyAddresses) {
                foreach ($this->collCompanyAddresses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCompanyEmails) {
                foreach ($this->collCompanyEmails as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCompanyPhones) {
                foreach ($this->collCompanyPhones as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCompanyContacts) {
                foreach ($this->collCompanyContacts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCompanyInformants) {
                foreach ($this->collCompanyInformants as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCompanyOwners) {
                foreach ($this->collCompanyOwners as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStores) {
                foreach ($this->collStores as $o) {
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
            if ($this->aCompanyType instanceof Persistent) {
              $this->aCompanyType->clearAllReferences($deep);
            }
            if ($this->aRegions instanceof Persistent) {
              $this->aRegions->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collCompanyAddresses instanceof PropelCollection) {
            $this->collCompanyAddresses->clearIterator();
        }
        $this->collCompanyAddresses = null;
        if ($this->collCompanyEmails instanceof PropelCollection) {
            $this->collCompanyEmails->clearIterator();
        }
        $this->collCompanyEmails = null;
        if ($this->collCompanyPhones instanceof PropelCollection) {
            $this->collCompanyPhones->clearIterator();
        }
        $this->collCompanyPhones = null;
        if ($this->collCompanyContacts instanceof PropelCollection) {
            $this->collCompanyContacts->clearIterator();
        }
        $this->collCompanyContacts = null;
        if ($this->collCompanyInformants instanceof PropelCollection) {
            $this->collCompanyInformants->clearIterator();
        }
        $this->collCompanyInformants = null;
        if ($this->collCompanyOwners instanceof PropelCollection) {
            $this->collCompanyOwners->clearIterator();
        }
        $this->collCompanyOwners = null;
        if ($this->collStores instanceof PropelCollection) {
            $this->collStores->clearIterator();
        }
        $this->collStores = null;
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
        $this->aCompanyType = null;
        $this->aRegions = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CompanyPeer::DEFAULT_STRING_FORMAT);
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
     * @return     Company The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = CompanyPeer::UPDATED_AT;

        return $this;
    }

}
