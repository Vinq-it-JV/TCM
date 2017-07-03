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
use CollectionBundle\Model\Collection;
use CollectionBundle\Model\CollectionQuery;
use CompanyBundle\Model\Company;
use CompanyBundle\Model\CompanyContact;
use CompanyBundle\Model\CompanyContactQuery;
use CompanyBundle\Model\CompanyInformant;
use CompanyBundle\Model\CompanyInformantQuery;
use CompanyBundle\Model\CompanyOwner;
use CompanyBundle\Model\CompanyOwnerQuery;
use CompanyBundle\Model\CompanyQuery;
use NotificationBundle\Model\CbInputNotification;
use NotificationBundle\Model\CbInputNotificationQuery;
use NotificationBundle\Model\DsTemperatureNotification;
use NotificationBundle\Model\DsTemperatureNotificationQuery;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreContact;
use StoreBundle\Model\StoreContactQuery;
use StoreBundle\Model\StoreInformant;
use StoreBundle\Model\StoreInformantQuery;
use StoreBundle\Model\StoreOwner;
use StoreBundle\Model\StoreOwnerQuery;
use StoreBundle\Model\StoreQuery;
use UserBundle\Model\Address;
use UserBundle\Model\AddressQuery;
use UserBundle\Model\Countries;
use UserBundle\Model\CountriesQuery;
use UserBundle\Model\Email;
use UserBundle\Model\EmailQuery;
use UserBundle\Model\Phone;
use UserBundle\Model\PhoneQuery;
use UserBundle\Model\Role;
use UserBundle\Model\RoleQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserAddress;
use UserBundle\Model\UserAddressQuery;
use UserBundle\Model\UserEmail;
use UserBundle\Model\UserEmailQuery;
use UserBundle\Model\UserGender;
use UserBundle\Model\UserGenderQuery;
use UserBundle\Model\UserPeer;
use UserBundle\Model\UserPhone;
use UserBundle\Model\UserPhoneQuery;
use UserBundle\Model\UserQuery;
use UserBundle\Model\UserRole;
use UserBundle\Model\UserRoleQuery;
use UserBundle\Model\UserTitle;
use UserBundle\Model\UserTitleQuery;

abstract class BaseUser extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'UserBundle\\Model\\UserPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        UserPeer
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
     * The value for the username field.
     * @var        string
     */
    protected $username;

    /**
     * The value for the firstname field.
     * @var        string
     */
    protected $firstname;

    /**
     * The value for the middlename field.
     * @var        string
     */
    protected $middlename;

    /**
     * The value for the lastname field.
     * @var        string
     */
    protected $lastname;

    /**
     * The value for the gender field.
     * @var        int
     */
    protected $gender;

    /**
     * The value for the title field.
     * @var        int
     */
    protected $title;

    /**
     * The value for the birth_date field.
     * @var        string
     */
    protected $birth_date;

    /**
     * The value for the password field.
     * @var        string
     */
    protected $password;

    /**
     * The value for the secret field.
     * @var        string
     */
    protected $secret;

    /**
     * The value for the logins field.
     * Note: this column has a database default value of: 3
     * @var        int
     */
    protected $logins;

    /**
     * The value for the country field.
     * @var        int
     */
    protected $country;

    /**
     * The value for the language field.
     * @var        int
     */
    protected $language;

    /**
     * The value for the is_enabled field.
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $is_enabled;

    /**
     * The value for the is_external field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_external;

    /**
     * The value for the is_locked field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_locked;

    /**
     * The value for the is_expired field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_expired;

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
     * @var        UserGender
     */
    protected $aUserGender;

    /**
     * @var        UserTitle
     */
    protected $aUserTitle;

    /**
     * @var        Countries
     */
    protected $aCountriesRelatedByCountry;

    /**
     * @var        Countries
     */
    protected $aCountriesRelatedByLanguage;

    /**
     * @var        PropelObjectCollection|Collection[] Collection to store aggregation of Collection objects.
     */
    protected $collCollectionsRelatedByCreatedBy;
    protected $collCollectionsRelatedByCreatedByPartial;

    /**
     * @var        PropelObjectCollection|Collection[] Collection to store aggregation of Collection objects.
     */
    protected $collCollectionsRelatedByEditedBy;
    protected $collCollectionsRelatedByEditedByPartial;

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
     * @var        PropelObjectCollection|DsTemperatureNotification[] Collection to store aggregation of DsTemperatureNotification objects.
     */
    protected $collDsTemperatureNotifications;
    protected $collDsTemperatureNotificationsPartial;

    /**
     * @var        PropelObjectCollection|CbInputNotification[] Collection to store aggregation of CbInputNotification objects.
     */
    protected $collCbInputNotifications;
    protected $collCbInputNotificationsPartial;

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
     * @var        PropelObjectCollection|UserRole[] Collection to store aggregation of UserRole objects.
     */
    protected $collUserRoles;
    protected $collUserRolesPartial;

    /**
     * @var        PropelObjectCollection|UserAddress[] Collection to store aggregation of UserAddress objects.
     */
    protected $collUserAddresses;
    protected $collUserAddressesPartial;

    /**
     * @var        PropelObjectCollection|UserEmail[] Collection to store aggregation of UserEmail objects.
     */
    protected $collUserEmails;
    protected $collUserEmailsPartial;

    /**
     * @var        PropelObjectCollection|UserPhone[] Collection to store aggregation of UserPhone objects.
     */
    protected $collUserPhones;
    protected $collUserPhonesPartial;

    /**
     * @var        PropelObjectCollection|Company[] Collection to store aggregation of Company objects.
     */
    protected $collContactCompanies;

    /**
     * @var        PropelObjectCollection|Company[] Collection to store aggregation of Company objects.
     */
    protected $collInformantCompanies;

    /**
     * @var        PropelObjectCollection|Company[] Collection to store aggregation of Company objects.
     */
    protected $collOwnerCompanies;

    /**
     * @var        PropelObjectCollection|Store[] Collection to store aggregation of Store objects.
     */
    protected $collContactStores;

    /**
     * @var        PropelObjectCollection|Store[] Collection to store aggregation of Store objects.
     */
    protected $collInformantStores;

    /**
     * @var        PropelObjectCollection|Store[] Collection to store aggregation of Store objects.
     */
    protected $collOwnerStores;

    /**
     * @var        PropelObjectCollection|Role[] Collection to store aggregation of Role objects.
     */
    protected $collRoles;

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
    protected $contactCompaniesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $informantCompaniesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $ownerCompaniesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $contactStoresScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $informantStoresScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $ownerStoresScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $rolesScheduledForDeletion = null;

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
    protected $collectionsRelatedByCreatedByScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $collectionsRelatedByEditedByScheduledForDeletion = null;

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
    protected $dsTemperatureNotificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $cbInputNotificationsScheduledForDeletion = null;

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
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userRolesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userAddressesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userEmailsScheduledForDeletion = null;

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
        $this->logins = 3;
        $this->is_enabled = true;
        $this->is_external = false;
        $this->is_locked = false;
        $this->is_expired = false;
    }

    /**
     * Initializes internal state of BaseUser object.
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
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {

        return $this->username;
    }

    /**
     * Get the [firstname] column value.
     *
     * @return string
     */
    public function getFirstname()
    {

        return $this->firstname;
    }

    /**
     * Get the [middlename] column value.
     *
     * @return string
     */
    public function getMiddlename()
    {

        return $this->middlename;
    }

    /**
     * Get the [lastname] column value.
     *
     * @return string
     */
    public function getLastname()
    {

        return $this->lastname;
    }

    /**
     * Get the [gender] column value.
     *
     * @return int
     */
    public function getGender()
    {

        return $this->gender;
    }

    /**
     * Get the [title] column value.
     *
     * @return int
     */
    public function getTitle()
    {

        return $this->title;
    }

    /**
     * Get the [optionally formatted] temporal [birth_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBirthDate($format = null)
    {
        if ($this->birth_date === null) {
            return null;
        }

        if ($this->birth_date === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->birth_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->birth_date, true), $x);
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
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {

        return $this->password;
    }

    /**
     * Get the [secret] column value.
     *
     * @return string
     */
    public function getSecret()
    {

        return $this->secret;
    }

    /**
     * Get the [logins] column value.
     *
     * @return int
     */
    public function getLogins()
    {

        return $this->logins;
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
     * Get the [language] column value.
     *
     * @return int
     */
    public function getLanguage()
    {

        return $this->language;
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
     * Get the [is_external] column value.
     *
     * @return boolean
     */
    public function getIsExternal()
    {

        return $this->is_external;
    }

    /**
     * Get the [is_locked] column value.
     *
     * @return boolean
     */
    public function getIsLocked()
    {

        return $this->is_locked;
    }

    /**
     * Get the [is_expired] column value.
     *
     * @return boolean
     */
    public function getIsExpired()
    {

        return $this->is_expired;
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
     * @return User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = UserPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [username] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[] = UserPeer::USERNAME;
        }


        return $this;
    } // setUsername()

    /**
     * Set the value of [firstname] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setFirstname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->firstname !== $v) {
            $this->firstname = $v;
            $this->modifiedColumns[] = UserPeer::FIRSTNAME;
        }


        return $this;
    } // setFirstname()

    /**
     * Set the value of [middlename] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setMiddlename($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->middlename !== $v) {
            $this->middlename = $v;
            $this->modifiedColumns[] = UserPeer::MIDDLENAME;
        }


        return $this;
    } // setMiddlename()

    /**
     * Set the value of [lastname] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setLastname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->lastname !== $v) {
            $this->lastname = $v;
            $this->modifiedColumns[] = UserPeer::LASTNAME;
        }


        return $this;
    } // setLastname()

    /**
     * Set the value of [gender] column.
     *
     * @param  int $v new value
     * @return User The current object (for fluent API support)
     */
    public function setGender($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->gender !== $v) {
            $this->gender = $v;
            $this->modifiedColumns[] = UserPeer::GENDER;
        }

        if ($this->aUserGender !== null && $this->aUserGender->getId() !== $v) {
            $this->aUserGender = null;
        }


        return $this;
    } // setGender()

    /**
     * Set the value of [title] column.
     *
     * @param  int $v new value
     * @return User The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = UserPeer::TITLE;
        }

        if ($this->aUserTitle !== null && $this->aUserTitle->getId() !== $v) {
            $this->aUserTitle = null;
        }


        return $this;
    } // setTitle()

    /**
     * Sets the value of [birth_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return User The current object (for fluent API support)
     */
    public function setBirthDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->birth_date !== null || $dt !== null) {
            $currentDateAsString = ($this->birth_date !== null && $tmpDt = new DateTime($this->birth_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->birth_date = $newDateAsString;
                $this->modifiedColumns[] = UserPeer::BIRTH_DATE;
            }
        } // if either are not null


        return $this;
    } // setBirthDate()

    /**
     * Set the value of [password] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[] = UserPeer::PASSWORD;
        }


        return $this;
    } // setPassword()

    /**
     * Set the value of [secret] column.
     *
     * @param  string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setSecret($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->secret !== $v) {
            $this->secret = $v;
            $this->modifiedColumns[] = UserPeer::SECRET;
        }


        return $this;
    } // setSecret()

    /**
     * Set the value of [logins] column.
     *
     * @param  int $v new value
     * @return User The current object (for fluent API support)
     */
    public function setLogins($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->logins !== $v) {
            $this->logins = $v;
            $this->modifiedColumns[] = UserPeer::LOGINS;
        }


        return $this;
    } // setLogins()

    /**
     * Set the value of [country] column.
     *
     * @param  int $v new value
     * @return User The current object (for fluent API support)
     */
    public function setCountry($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->country !== $v) {
            $this->country = $v;
            $this->modifiedColumns[] = UserPeer::COUNTRY;
        }

        if ($this->aCountriesRelatedByCountry !== null && $this->aCountriesRelatedByCountry->getId() !== $v) {
            $this->aCountriesRelatedByCountry = null;
        }


        return $this;
    } // setCountry()

    /**
     * Set the value of [language] column.
     *
     * @param  int $v new value
     * @return User The current object (for fluent API support)
     */
    public function setLanguage($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->language !== $v) {
            $this->language = $v;
            $this->modifiedColumns[] = UserPeer::LANGUAGE;
        }

        if ($this->aCountriesRelatedByLanguage !== null && $this->aCountriesRelatedByLanguage->getId() !== $v) {
            $this->aCountriesRelatedByLanguage = null;
        }


        return $this;
    } // setLanguage()

    /**
     * Sets the value of the [is_enabled] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return User The current object (for fluent API support)
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
            $this->modifiedColumns[] = UserPeer::IS_ENABLED;
        }


        return $this;
    } // setIsEnabled()

    /**
     * Sets the value of the [is_external] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return User The current object (for fluent API support)
     */
    public function setIsExternal($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_external !== $v) {
            $this->is_external = $v;
            $this->modifiedColumns[] = UserPeer::IS_EXTERNAL;
        }


        return $this;
    } // setIsExternal()

    /**
     * Sets the value of the [is_locked] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return User The current object (for fluent API support)
     */
    public function setIsLocked($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_locked !== $v) {
            $this->is_locked = $v;
            $this->modifiedColumns[] = UserPeer::IS_LOCKED;
        }


        return $this;
    } // setIsLocked()

    /**
     * Sets the value of the [is_expired] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return User The current object (for fluent API support)
     */
    public function setIsExpired($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_expired !== $v) {
            $this->is_expired = $v;
            $this->modifiedColumns[] = UserPeer::IS_EXPIRED;
        }


        return $this;
    } // setIsExpired()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return User The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = UserPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return User The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = UserPeer::UPDATED_AT;
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
            if ($this->logins !== 3) {
                return false;
            }

            if ($this->is_enabled !== true) {
                return false;
            }

            if ($this->is_external !== false) {
                return false;
            }

            if ($this->is_locked !== false) {
                return false;
            }

            if ($this->is_expired !== false) {
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
            $this->username = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->firstname = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->middlename = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->lastname = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->gender = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->title = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->birth_date = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->password = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->secret = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->logins = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->country = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->language = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->is_enabled = ($row[$startcol + 13] !== null) ? (boolean) $row[$startcol + 13] : null;
            $this->is_external = ($row[$startcol + 14] !== null) ? (boolean) $row[$startcol + 14] : null;
            $this->is_locked = ($row[$startcol + 15] !== null) ? (boolean) $row[$startcol + 15] : null;
            $this->is_expired = ($row[$startcol + 16] !== null) ? (boolean) $row[$startcol + 16] : null;
            $this->created_at = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->updated_at = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 19; // 19 = UserPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating User object", $e);
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

        if ($this->aUserGender !== null && $this->gender !== $this->aUserGender->getId()) {
            $this->aUserGender = null;
        }
        if ($this->aUserTitle !== null && $this->title !== $this->aUserTitle->getId()) {
            $this->aUserTitle = null;
        }
        if ($this->aCountriesRelatedByCountry !== null && $this->country !== $this->aCountriesRelatedByCountry->getId()) {
            $this->aCountriesRelatedByCountry = null;
        }
        if ($this->aCountriesRelatedByLanguage !== null && $this->language !== $this->aCountriesRelatedByLanguage->getId()) {
            $this->aCountriesRelatedByLanguage = null;
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
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = UserPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUserGender = null;
            $this->aUserTitle = null;
            $this->aCountriesRelatedByCountry = null;
            $this->aCountriesRelatedByLanguage = null;
            $this->collCollectionsRelatedByCreatedBy = null;

            $this->collCollectionsRelatedByEditedBy = null;

            $this->collCompanyContacts = null;

            $this->collCompanyInformants = null;

            $this->collCompanyOwners = null;

            $this->collDsTemperatureNotifications = null;

            $this->collCbInputNotifications = null;

            $this->collStoreContacts = null;

            $this->collStoreInformants = null;

            $this->collStoreOwners = null;

            $this->collUserRoles = null;

            $this->collUserAddresses = null;

            $this->collUserEmails = null;

            $this->collUserPhones = null;

            $this->collContactCompanies = null;
            $this->collInformantCompanies = null;
            $this->collOwnerCompanies = null;
            $this->collContactStores = null;
            $this->collInformantStores = null;
            $this->collOwnerStores = null;
            $this->collRoles = null;
            $this->collAddresses = null;
            $this->collEmails = null;
            $this->collPhones = null;
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
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = UserQuery::create()
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
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(UserPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(UserPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(UserPeer::UPDATED_AT)) {
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
                UserPeer::addInstanceToPool($this);
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

            if ($this->aUserGender !== null) {
                if ($this->aUserGender->isModified() || $this->aUserGender->isNew()) {
                    $affectedRows += $this->aUserGender->save($con);
                }
                $this->setUserGender($this->aUserGender);
            }

            if ($this->aUserTitle !== null) {
                if ($this->aUserTitle->isModified() || $this->aUserTitle->isNew()) {
                    $affectedRows += $this->aUserTitle->save($con);
                }
                $this->setUserTitle($this->aUserTitle);
            }

            if ($this->aCountriesRelatedByCountry !== null) {
                if ($this->aCountriesRelatedByCountry->isModified() || $this->aCountriesRelatedByCountry->isNew()) {
                    $affectedRows += $this->aCountriesRelatedByCountry->save($con);
                }
                $this->setCountriesRelatedByCountry($this->aCountriesRelatedByCountry);
            }

            if ($this->aCountriesRelatedByLanguage !== null) {
                if ($this->aCountriesRelatedByLanguage->isModified() || $this->aCountriesRelatedByLanguage->isNew()) {
                    $affectedRows += $this->aCountriesRelatedByLanguage->save($con);
                }
                $this->setCountriesRelatedByLanguage($this->aCountriesRelatedByLanguage);
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

            if ($this->contactCompaniesScheduledForDeletion !== null) {
                if (!$this->contactCompaniesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->contactCompaniesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    CompanyContactQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->contactCompaniesScheduledForDeletion = null;
                }

                foreach ($this->getContactCompanies() as $contactCompany) {
                    if ($contactCompany->isModified()) {
                        $contactCompany->save($con);
                    }
                }
            } elseif ($this->collContactCompanies) {
                foreach ($this->collContactCompanies as $contactCompany) {
                    if ($contactCompany->isModified()) {
                        $contactCompany->save($con);
                    }
                }
            }

            if ($this->informantCompaniesScheduledForDeletion !== null) {
                if (!$this->informantCompaniesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->informantCompaniesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    CompanyInformantQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->informantCompaniesScheduledForDeletion = null;
                }

                foreach ($this->getInformantCompanies() as $informantCompany) {
                    if ($informantCompany->isModified()) {
                        $informantCompany->save($con);
                    }
                }
            } elseif ($this->collInformantCompanies) {
                foreach ($this->collInformantCompanies as $informantCompany) {
                    if ($informantCompany->isModified()) {
                        $informantCompany->save($con);
                    }
                }
            }

            if ($this->ownerCompaniesScheduledForDeletion !== null) {
                if (!$this->ownerCompaniesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->ownerCompaniesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    CompanyOwnerQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->ownerCompaniesScheduledForDeletion = null;
                }

                foreach ($this->getOwnerCompanies() as $ownerCompany) {
                    if ($ownerCompany->isModified()) {
                        $ownerCompany->save($con);
                    }
                }
            } elseif ($this->collOwnerCompanies) {
                foreach ($this->collOwnerCompanies as $ownerCompany) {
                    if ($ownerCompany->isModified()) {
                        $ownerCompany->save($con);
                    }
                }
            }

            if ($this->contactStoresScheduledForDeletion !== null) {
                if (!$this->contactStoresScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->contactStoresScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    StoreContactQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->contactStoresScheduledForDeletion = null;
                }

                foreach ($this->getContactStores() as $contactStore) {
                    if ($contactStore->isModified()) {
                        $contactStore->save($con);
                    }
                }
            } elseif ($this->collContactStores) {
                foreach ($this->collContactStores as $contactStore) {
                    if ($contactStore->isModified()) {
                        $contactStore->save($con);
                    }
                }
            }

            if ($this->informantStoresScheduledForDeletion !== null) {
                if (!$this->informantStoresScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->informantStoresScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    StoreInformantQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->informantStoresScheduledForDeletion = null;
                }

                foreach ($this->getInformantStores() as $informantStore) {
                    if ($informantStore->isModified()) {
                        $informantStore->save($con);
                    }
                }
            } elseif ($this->collInformantStores) {
                foreach ($this->collInformantStores as $informantStore) {
                    if ($informantStore->isModified()) {
                        $informantStore->save($con);
                    }
                }
            }

            if ($this->ownerStoresScheduledForDeletion !== null) {
                if (!$this->ownerStoresScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->ownerStoresScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    StoreOwnerQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->ownerStoresScheduledForDeletion = null;
                }

                foreach ($this->getOwnerStores() as $ownerStore) {
                    if ($ownerStore->isModified()) {
                        $ownerStore->save($con);
                    }
                }
            } elseif ($this->collOwnerStores) {
                foreach ($this->collOwnerStores as $ownerStore) {
                    if ($ownerStore->isModified()) {
                        $ownerStore->save($con);
                    }
                }
            }

            if ($this->rolesScheduledForDeletion !== null) {
                if (!$this->rolesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->rolesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    UserRoleQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->rolesScheduledForDeletion = null;
                }

                foreach ($this->getRoles() as $role) {
                    if ($role->isModified()) {
                        $role->save($con);
                    }
                }
            } elseif ($this->collRoles) {
                foreach ($this->collRoles as $role) {
                    if ($role->isModified()) {
                        $role->save($con);
                    }
                }
            }

            if ($this->addressesScheduledForDeletion !== null) {
                if (!$this->addressesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->addressesScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    UserAddressQuery::create()
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
                    UserEmailQuery::create()
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
                    UserPhoneQuery::create()
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

            if ($this->collectionsRelatedByCreatedByScheduledForDeletion !== null) {
                if (!$this->collectionsRelatedByCreatedByScheduledForDeletion->isEmpty()) {
                    foreach ($this->collectionsRelatedByCreatedByScheduledForDeletion as $collectionRelatedByCreatedBy) {
                        // need to save related object because we set the relation to null
                        $collectionRelatedByCreatedBy->save($con);
                    }
                    $this->collectionsRelatedByCreatedByScheduledForDeletion = null;
                }
            }

            if ($this->collCollectionsRelatedByCreatedBy !== null) {
                foreach ($this->collCollectionsRelatedByCreatedBy as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->collectionsRelatedByEditedByScheduledForDeletion !== null) {
                if (!$this->collectionsRelatedByEditedByScheduledForDeletion->isEmpty()) {
                    foreach ($this->collectionsRelatedByEditedByScheduledForDeletion as $collectionRelatedByEditedBy) {
                        // need to save related object because we set the relation to null
                        $collectionRelatedByEditedBy->save($con);
                    }
                    $this->collectionsRelatedByEditedByScheduledForDeletion = null;
                }
            }

            if ($this->collCollectionsRelatedByEditedBy !== null) {
                foreach ($this->collCollectionsRelatedByEditedBy as $referrerFK) {
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

            if ($this->dsTemperatureNotificationsScheduledForDeletion !== null) {
                if (!$this->dsTemperatureNotificationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->dsTemperatureNotificationsScheduledForDeletion as $dsTemperatureNotification) {
                        // need to save related object because we set the relation to null
                        $dsTemperatureNotification->save($con);
                    }
                    $this->dsTemperatureNotificationsScheduledForDeletion = null;
                }
            }

            if ($this->collDsTemperatureNotifications !== null) {
                foreach ($this->collDsTemperatureNotifications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->cbInputNotificationsScheduledForDeletion !== null) {
                if (!$this->cbInputNotificationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->cbInputNotificationsScheduledForDeletion as $cbInputNotification) {
                        // need to save related object because we set the relation to null
                        $cbInputNotification->save($con);
                    }
                    $this->cbInputNotificationsScheduledForDeletion = null;
                }
            }

            if ($this->collCbInputNotifications !== null) {
                foreach ($this->collCbInputNotifications as $referrerFK) {
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

            if ($this->userRolesScheduledForDeletion !== null) {
                if (!$this->userRolesScheduledForDeletion->isEmpty()) {
                    UserRoleQuery::create()
                        ->filterByPrimaryKeys($this->userRolesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userRolesScheduledForDeletion = null;
                }
            }

            if ($this->collUserRoles !== null) {
                foreach ($this->collUserRoles as $referrerFK) {
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

        $this->modifiedColumns[] = UserPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(UserPeer::USERNAME)) {
            $modifiedColumns[':p' . $index++]  = '`username`';
        }
        if ($this->isColumnModified(UserPeer::FIRSTNAME)) {
            $modifiedColumns[':p' . $index++]  = '`firstname`';
        }
        if ($this->isColumnModified(UserPeer::MIDDLENAME)) {
            $modifiedColumns[':p' . $index++]  = '`middlename`';
        }
        if ($this->isColumnModified(UserPeer::LASTNAME)) {
            $modifiedColumns[':p' . $index++]  = '`lastname`';
        }
        if ($this->isColumnModified(UserPeer::GENDER)) {
            $modifiedColumns[':p' . $index++]  = '`gender`';
        }
        if ($this->isColumnModified(UserPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(UserPeer::BIRTH_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`birth_date`';
        }
        if ($this->isColumnModified(UserPeer::PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = '`password`';
        }
        if ($this->isColumnModified(UserPeer::SECRET)) {
            $modifiedColumns[':p' . $index++]  = '`secret`';
        }
        if ($this->isColumnModified(UserPeer::LOGINS)) {
            $modifiedColumns[':p' . $index++]  = '`logins`';
        }
        if ($this->isColumnModified(UserPeer::COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = '`country`';
        }
        if ($this->isColumnModified(UserPeer::LANGUAGE)) {
            $modifiedColumns[':p' . $index++]  = '`language`';
        }
        if ($this->isColumnModified(UserPeer::IS_ENABLED)) {
            $modifiedColumns[':p' . $index++]  = '`is_enabled`';
        }
        if ($this->isColumnModified(UserPeer::IS_EXTERNAL)) {
            $modifiedColumns[':p' . $index++]  = '`is_external`';
        }
        if ($this->isColumnModified(UserPeer::IS_LOCKED)) {
            $modifiedColumns[':p' . $index++]  = '`is_locked`';
        }
        if ($this->isColumnModified(UserPeer::IS_EXPIRED)) {
            $modifiedColumns[':p' . $index++]  = '`is_expired`';
        }
        if ($this->isColumnModified(UserPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(UserPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `user` (%s) VALUES (%s)',
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
                    case '`username`':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case '`firstname`':
                        $stmt->bindValue($identifier, $this->firstname, PDO::PARAM_STR);
                        break;
                    case '`middlename`':
                        $stmt->bindValue($identifier, $this->middlename, PDO::PARAM_STR);
                        break;
                    case '`lastname`':
                        $stmt->bindValue($identifier, $this->lastname, PDO::PARAM_STR);
                        break;
                    case '`gender`':
                        $stmt->bindValue($identifier, $this->gender, PDO::PARAM_INT);
                        break;
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_INT);
                        break;
                    case '`birth_date`':
                        $stmt->bindValue($identifier, $this->birth_date, PDO::PARAM_STR);
                        break;
                    case '`password`':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case '`secret`':
                        $stmt->bindValue($identifier, $this->secret, PDO::PARAM_STR);
                        break;
                    case '`logins`':
                        $stmt->bindValue($identifier, $this->logins, PDO::PARAM_INT);
                        break;
                    case '`country`':
                        $stmt->bindValue($identifier, $this->country, PDO::PARAM_INT);
                        break;
                    case '`language`':
                        $stmt->bindValue($identifier, $this->language, PDO::PARAM_INT);
                        break;
                    case '`is_enabled`':
                        $stmt->bindValue($identifier, (int) $this->is_enabled, PDO::PARAM_INT);
                        break;
                    case '`is_external`':
                        $stmt->bindValue($identifier, (int) $this->is_external, PDO::PARAM_INT);
                        break;
                    case '`is_locked`':
                        $stmt->bindValue($identifier, (int) $this->is_locked, PDO::PARAM_INT);
                        break;
                    case '`is_expired`':
                        $stmt->bindValue($identifier, (int) $this->is_expired, PDO::PARAM_INT);
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

            if ($this->aUserGender !== null) {
                if (!$this->aUserGender->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUserGender->getValidationFailures());
                }
            }

            if ($this->aUserTitle !== null) {
                if (!$this->aUserTitle->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUserTitle->getValidationFailures());
                }
            }

            if ($this->aCountriesRelatedByCountry !== null) {
                if (!$this->aCountriesRelatedByCountry->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCountriesRelatedByCountry->getValidationFailures());
                }
            }

            if ($this->aCountriesRelatedByLanguage !== null) {
                if (!$this->aCountriesRelatedByLanguage->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCountriesRelatedByLanguage->getValidationFailures());
                }
            }


            if (($retval = UserPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCollectionsRelatedByCreatedBy !== null) {
                    foreach ($this->collCollectionsRelatedByCreatedBy as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCollectionsRelatedByEditedBy !== null) {
                    foreach ($this->collCollectionsRelatedByEditedBy as $referrerFK) {
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

                if ($this->collDsTemperatureNotifications !== null) {
                    foreach ($this->collDsTemperatureNotifications as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCbInputNotifications !== null) {
                    foreach ($this->collCbInputNotifications as $referrerFK) {
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

                if ($this->collUserRoles !== null) {
                    foreach ($this->collUserRoles as $referrerFK) {
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

                if ($this->collUserEmails !== null) {
                    foreach ($this->collUserEmails as $referrerFK) {
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
        $pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getUsername();
                break;
            case 2:
                return $this->getFirstname();
                break;
            case 3:
                return $this->getMiddlename();
                break;
            case 4:
                return $this->getLastname();
                break;
            case 5:
                return $this->getGender();
                break;
            case 6:
                return $this->getTitle();
                break;
            case 7:
                return $this->getBirthDate();
                break;
            case 8:
                return $this->getPassword();
                break;
            case 9:
                return $this->getSecret();
                break;
            case 10:
                return $this->getLogins();
                break;
            case 11:
                return $this->getCountry();
                break;
            case 12:
                return $this->getLanguage();
                break;
            case 13:
                return $this->getIsEnabled();
                break;
            case 14:
                return $this->getIsExternal();
                break;
            case 15:
                return $this->getIsLocked();
                break;
            case 16:
                return $this->getIsExpired();
                break;
            case 17:
                return $this->getCreatedAt();
                break;
            case 18:
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
        if (isset($alreadyDumpedObjects['User'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->getPrimaryKey()] = true;
        $keys = UserPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUsername(),
            $keys[2] => $this->getFirstname(),
            $keys[3] => $this->getMiddlename(),
            $keys[4] => $this->getLastname(),
            $keys[5] => $this->getGender(),
            $keys[6] => $this->getTitle(),
            $keys[7] => $this->getBirthDate(),
            $keys[8] => $this->getPassword(),
            $keys[9] => $this->getSecret(),
            $keys[10] => $this->getLogins(),
            $keys[11] => $this->getCountry(),
            $keys[12] => $this->getLanguage(),
            $keys[13] => $this->getIsEnabled(),
            $keys[14] => $this->getIsExternal(),
            $keys[15] => $this->getIsLocked(),
            $keys[16] => $this->getIsExpired(),
            $keys[17] => $this->getCreatedAt(),
            $keys[18] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUserGender) {
                $result['UserGender'] = $this->aUserGender->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUserTitle) {
                $result['UserTitle'] = $this->aUserTitle->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCountriesRelatedByCountry) {
                $result['CountriesRelatedByCountry'] = $this->aCountriesRelatedByCountry->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCountriesRelatedByLanguage) {
                $result['CountriesRelatedByLanguage'] = $this->aCountriesRelatedByLanguage->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCollectionsRelatedByCreatedBy) {
                $result['CollectionsRelatedByCreatedBy'] = $this->collCollectionsRelatedByCreatedBy->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCollectionsRelatedByEditedBy) {
                $result['CollectionsRelatedByEditedBy'] = $this->collCollectionsRelatedByEditedBy->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collDsTemperatureNotifications) {
                $result['DsTemperatureNotifications'] = $this->collDsTemperatureNotifications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCbInputNotifications) {
                $result['CbInputNotifications'] = $this->collCbInputNotifications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collUserRoles) {
                $result['UserRoles'] = $this->collUserRoles->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserAddresses) {
                $result['UserAddresses'] = $this->collUserAddresses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserEmails) {
                $result['UserEmails'] = $this->collUserEmails->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setUsername($value);
                break;
            case 2:
                $this->setFirstname($value);
                break;
            case 3:
                $this->setMiddlename($value);
                break;
            case 4:
                $this->setLastname($value);
                break;
            case 5:
                $this->setGender($value);
                break;
            case 6:
                $this->setTitle($value);
                break;
            case 7:
                $this->setBirthDate($value);
                break;
            case 8:
                $this->setPassword($value);
                break;
            case 9:
                $this->setSecret($value);
                break;
            case 10:
                $this->setLogins($value);
                break;
            case 11:
                $this->setCountry($value);
                break;
            case 12:
                $this->setLanguage($value);
                break;
            case 13:
                $this->setIsEnabled($value);
                break;
            case 14:
                $this->setIsExternal($value);
                break;
            case 15:
                $this->setIsLocked($value);
                break;
            case 16:
                $this->setIsExpired($value);
                break;
            case 17:
                $this->setCreatedAt($value);
                break;
            case 18:
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
        $keys = UserPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUsername($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setFirstname($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setMiddlename($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setLastname($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setGender($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setTitle($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setBirthDate($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setPassword($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setSecret($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setLogins($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setCountry($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setLanguage($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setIsEnabled($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setIsExternal($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setIsLocked($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setIsExpired($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setCreatedAt($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setUpdatedAt($arr[$keys[18]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserPeer::DATABASE_NAME);

        if ($this->isColumnModified(UserPeer::ID)) $criteria->add(UserPeer::ID, $this->id);
        if ($this->isColumnModified(UserPeer::USERNAME)) $criteria->add(UserPeer::USERNAME, $this->username);
        if ($this->isColumnModified(UserPeer::FIRSTNAME)) $criteria->add(UserPeer::FIRSTNAME, $this->firstname);
        if ($this->isColumnModified(UserPeer::MIDDLENAME)) $criteria->add(UserPeer::MIDDLENAME, $this->middlename);
        if ($this->isColumnModified(UserPeer::LASTNAME)) $criteria->add(UserPeer::LASTNAME, $this->lastname);
        if ($this->isColumnModified(UserPeer::GENDER)) $criteria->add(UserPeer::GENDER, $this->gender);
        if ($this->isColumnModified(UserPeer::TITLE)) $criteria->add(UserPeer::TITLE, $this->title);
        if ($this->isColumnModified(UserPeer::BIRTH_DATE)) $criteria->add(UserPeer::BIRTH_DATE, $this->birth_date);
        if ($this->isColumnModified(UserPeer::PASSWORD)) $criteria->add(UserPeer::PASSWORD, $this->password);
        if ($this->isColumnModified(UserPeer::SECRET)) $criteria->add(UserPeer::SECRET, $this->secret);
        if ($this->isColumnModified(UserPeer::LOGINS)) $criteria->add(UserPeer::LOGINS, $this->logins);
        if ($this->isColumnModified(UserPeer::COUNTRY)) $criteria->add(UserPeer::COUNTRY, $this->country);
        if ($this->isColumnModified(UserPeer::LANGUAGE)) $criteria->add(UserPeer::LANGUAGE, $this->language);
        if ($this->isColumnModified(UserPeer::IS_ENABLED)) $criteria->add(UserPeer::IS_ENABLED, $this->is_enabled);
        if ($this->isColumnModified(UserPeer::IS_EXTERNAL)) $criteria->add(UserPeer::IS_EXTERNAL, $this->is_external);
        if ($this->isColumnModified(UserPeer::IS_LOCKED)) $criteria->add(UserPeer::IS_LOCKED, $this->is_locked);
        if ($this->isColumnModified(UserPeer::IS_EXPIRED)) $criteria->add(UserPeer::IS_EXPIRED, $this->is_expired);
        if ($this->isColumnModified(UserPeer::CREATED_AT)) $criteria->add(UserPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(UserPeer::UPDATED_AT)) $criteria->add(UserPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(UserPeer::DATABASE_NAME);
        $criteria->add(UserPeer::ID, $this->id);

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
     * @param object $copyObj An object of User (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUsername($this->getUsername());
        $copyObj->setFirstname($this->getFirstname());
        $copyObj->setMiddlename($this->getMiddlename());
        $copyObj->setLastname($this->getLastname());
        $copyObj->setGender($this->getGender());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setBirthDate($this->getBirthDate());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setSecret($this->getSecret());
        $copyObj->setLogins($this->getLogins());
        $copyObj->setCountry($this->getCountry());
        $copyObj->setLanguage($this->getLanguage());
        $copyObj->setIsEnabled($this->getIsEnabled());
        $copyObj->setIsExternal($this->getIsExternal());
        $copyObj->setIsLocked($this->getIsLocked());
        $copyObj->setIsExpired($this->getIsExpired());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCollectionsRelatedByCreatedBy() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCollectionRelatedByCreatedBy($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCollectionsRelatedByEditedBy() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCollectionRelatedByEditedBy($relObj->copy($deepCopy));
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

            foreach ($this->getDsTemperatureNotifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDsTemperatureNotification($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCbInputNotifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCbInputNotification($relObj->copy($deepCopy));
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

            foreach ($this->getUserRoles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserRole($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserAddresses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserAddress($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserEmails() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserEmail($relObj->copy($deepCopy));
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
     * @return User Clone of current object.
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
     * @return UserPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new UserPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a UserGender object.
     *
     * @param                  UserGender $v
     * @return User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserGender(UserGender $v = null)
    {
        if ($v === null) {
            $this->setGender(NULL);
        } else {
            $this->setGender($v->getId());
        }

        $this->aUserGender = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the UserGender object, it will not be re-added.
        if ($v !== null) {
            $v->addUser($this);
        }


        return $this;
    }


    /**
     * Get the associated UserGender object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return UserGender The associated UserGender object.
     * @throws PropelException
     */
    public function getUserGender(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aUserGender === null && ($this->gender !== null) && $doQuery) {
            $this->aUserGender = UserGenderQuery::create()->findPk($this->gender, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserGender->addUsers($this);
             */
        }

        return $this->aUserGender;
    }

    /**
     * Declares an association between this object and a UserTitle object.
     *
     * @param                  UserTitle $v
     * @return User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserTitle(UserTitle $v = null)
    {
        if ($v === null) {
            $this->setTitle(NULL);
        } else {
            $this->setTitle($v->getId());
        }

        $this->aUserTitle = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the UserTitle object, it will not be re-added.
        if ($v !== null) {
            $v->addUser($this);
        }


        return $this;
    }


    /**
     * Get the associated UserTitle object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return UserTitle The associated UserTitle object.
     * @throws PropelException
     */
    public function getUserTitle(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aUserTitle === null && ($this->title !== null) && $doQuery) {
            $this->aUserTitle = UserTitleQuery::create()->findPk($this->title, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserTitle->addUsers($this);
             */
        }

        return $this->aUserTitle;
    }

    /**
     * Declares an association between this object and a Countries object.
     *
     * @param                  Countries $v
     * @return User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCountriesRelatedByCountry(Countries $v = null)
    {
        if ($v === null) {
            $this->setCountry(NULL);
        } else {
            $this->setCountry($v->getId());
        }

        $this->aCountriesRelatedByCountry = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Countries object, it will not be re-added.
        if ($v !== null) {
            $v->addUserRelatedByCountry($this);
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
    public function getCountriesRelatedByCountry(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCountriesRelatedByCountry === null && ($this->country !== null) && $doQuery) {
            $this->aCountriesRelatedByCountry = CountriesQuery::create()->findPk($this->country, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCountriesRelatedByCountry->addUsersRelatedByCountry($this);
             */
        }

        return $this->aCountriesRelatedByCountry;
    }

    /**
     * Declares an association between this object and a Countries object.
     *
     * @param                  Countries $v
     * @return User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCountriesRelatedByLanguage(Countries $v = null)
    {
        if ($v === null) {
            $this->setLanguage(NULL);
        } else {
            $this->setLanguage($v->getId());
        }

        $this->aCountriesRelatedByLanguage = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Countries object, it will not be re-added.
        if ($v !== null) {
            $v->addUserRelatedByLanguage($this);
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
    public function getCountriesRelatedByLanguage(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCountriesRelatedByLanguage === null && ($this->language !== null) && $doQuery) {
            $this->aCountriesRelatedByLanguage = CountriesQuery::create()->findPk($this->language, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCountriesRelatedByLanguage->addUsersRelatedByLanguage($this);
             */
        }

        return $this->aCountriesRelatedByLanguage;
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
        if ('CollectionRelatedByCreatedBy' == $relationName) {
            $this->initCollectionsRelatedByCreatedBy();
        }
        if ('CollectionRelatedByEditedBy' == $relationName) {
            $this->initCollectionsRelatedByEditedBy();
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
        if ('DsTemperatureNotification' == $relationName) {
            $this->initDsTemperatureNotifications();
        }
        if ('CbInputNotification' == $relationName) {
            $this->initCbInputNotifications();
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
        if ('UserRole' == $relationName) {
            $this->initUserRoles();
        }
        if ('UserAddress' == $relationName) {
            $this->initUserAddresses();
        }
        if ('UserEmail' == $relationName) {
            $this->initUserEmails();
        }
        if ('UserPhone' == $relationName) {
            $this->initUserPhones();
        }
    }

    /**
     * Clears out the collCollectionsRelatedByCreatedBy collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addCollectionsRelatedByCreatedBy()
     */
    public function clearCollectionsRelatedByCreatedBy()
    {
        $this->collCollectionsRelatedByCreatedBy = null; // important to set this to null since that means it is uninitialized
        $this->collCollectionsRelatedByCreatedByPartial = null;

        return $this;
    }

    /**
     * reset is the collCollectionsRelatedByCreatedBy collection loaded partially
     *
     * @return void
     */
    public function resetPartialCollectionsRelatedByCreatedBy($v = true)
    {
        $this->collCollectionsRelatedByCreatedByPartial = $v;
    }

    /**
     * Initializes the collCollectionsRelatedByCreatedBy collection.
     *
     * By default this just sets the collCollectionsRelatedByCreatedBy collection to an empty array (like clearcollCollectionsRelatedByCreatedBy());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCollectionsRelatedByCreatedBy($overrideExisting = true)
    {
        if (null !== $this->collCollectionsRelatedByCreatedBy && !$overrideExisting) {
            return;
        }
        $this->collCollectionsRelatedByCreatedBy = new PropelObjectCollection();
        $this->collCollectionsRelatedByCreatedBy->setModel('Collection');
    }

    /**
     * Gets an array of Collection objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Collection[] List of Collection objects
     * @throws PropelException
     */
    public function getCollectionsRelatedByCreatedBy($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCollectionsRelatedByCreatedByPartial && !$this->isNew();
        if (null === $this->collCollectionsRelatedByCreatedBy || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCollectionsRelatedByCreatedBy) {
                // return empty collection
                $this->initCollectionsRelatedByCreatedBy();
            } else {
                $collCollectionsRelatedByCreatedBy = CollectionQuery::create(null, $criteria)
                    ->filterByUserRelatedByCreatedBy($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCollectionsRelatedByCreatedByPartial && count($collCollectionsRelatedByCreatedBy)) {
                      $this->initCollectionsRelatedByCreatedBy(false);

                      foreach ($collCollectionsRelatedByCreatedBy as $obj) {
                        if (false == $this->collCollectionsRelatedByCreatedBy->contains($obj)) {
                          $this->collCollectionsRelatedByCreatedBy->append($obj);
                        }
                      }

                      $this->collCollectionsRelatedByCreatedByPartial = true;
                    }

                    $collCollectionsRelatedByCreatedBy->getInternalIterator()->rewind();

                    return $collCollectionsRelatedByCreatedBy;
                }

                if ($partial && $this->collCollectionsRelatedByCreatedBy) {
                    foreach ($this->collCollectionsRelatedByCreatedBy as $obj) {
                        if ($obj->isNew()) {
                            $collCollectionsRelatedByCreatedBy[] = $obj;
                        }
                    }
                }

                $this->collCollectionsRelatedByCreatedBy = $collCollectionsRelatedByCreatedBy;
                $this->collCollectionsRelatedByCreatedByPartial = false;
            }
        }

        return $this->collCollectionsRelatedByCreatedBy;
    }

    /**
     * Sets a collection of CollectionRelatedByCreatedBy objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $collectionsRelatedByCreatedBy A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setCollectionsRelatedByCreatedBy(PropelCollection $collectionsRelatedByCreatedBy, PropelPDO $con = null)
    {
        $collectionsRelatedByCreatedByToDelete = $this->getCollectionsRelatedByCreatedBy(new Criteria(), $con)->diff($collectionsRelatedByCreatedBy);


        $this->collectionsRelatedByCreatedByScheduledForDeletion = $collectionsRelatedByCreatedByToDelete;

        foreach ($collectionsRelatedByCreatedByToDelete as $collectionRelatedByCreatedByRemoved) {
            $collectionRelatedByCreatedByRemoved->setUserRelatedByCreatedBy(null);
        }

        $this->collCollectionsRelatedByCreatedBy = null;
        foreach ($collectionsRelatedByCreatedBy as $collectionRelatedByCreatedBy) {
            $this->addCollectionRelatedByCreatedBy($collectionRelatedByCreatedBy);
        }

        $this->collCollectionsRelatedByCreatedBy = $collectionsRelatedByCreatedBy;
        $this->collCollectionsRelatedByCreatedByPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Collection objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Collection objects.
     * @throws PropelException
     */
    public function countCollectionsRelatedByCreatedBy(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCollectionsRelatedByCreatedByPartial && !$this->isNew();
        if (null === $this->collCollectionsRelatedByCreatedBy || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCollectionsRelatedByCreatedBy) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCollectionsRelatedByCreatedBy());
            }
            $query = CollectionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByCreatedBy($this)
                ->count($con);
        }

        return count($this->collCollectionsRelatedByCreatedBy);
    }

    /**
     * Method called to associate a Collection object to this object
     * through the Collection foreign key attribute.
     *
     * @param    Collection $l Collection
     * @return User The current object (for fluent API support)
     */
    public function addCollectionRelatedByCreatedBy(Collection $l)
    {
        if ($this->collCollectionsRelatedByCreatedBy === null) {
            $this->initCollectionsRelatedByCreatedBy();
            $this->collCollectionsRelatedByCreatedByPartial = true;
        }

        if (!in_array($l, $this->collCollectionsRelatedByCreatedBy->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCollectionRelatedByCreatedBy($l);

            if ($this->collectionsRelatedByCreatedByScheduledForDeletion and $this->collectionsRelatedByCreatedByScheduledForDeletion->contains($l)) {
                $this->collectionsRelatedByCreatedByScheduledForDeletion->remove($this->collectionsRelatedByCreatedByScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CollectionRelatedByCreatedBy $collectionRelatedByCreatedBy The collectionRelatedByCreatedBy object to add.
     */
    protected function doAddCollectionRelatedByCreatedBy($collectionRelatedByCreatedBy)
    {
        $this->collCollectionsRelatedByCreatedBy[]= $collectionRelatedByCreatedBy;
        $collectionRelatedByCreatedBy->setUserRelatedByCreatedBy($this);
    }

    /**
     * @param	CollectionRelatedByCreatedBy $collectionRelatedByCreatedBy The collectionRelatedByCreatedBy object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeCollectionRelatedByCreatedBy($collectionRelatedByCreatedBy)
    {
        if ($this->getCollectionsRelatedByCreatedBy()->contains($collectionRelatedByCreatedBy)) {
            $this->collCollectionsRelatedByCreatedBy->remove($this->collCollectionsRelatedByCreatedBy->search($collectionRelatedByCreatedBy));
            if (null === $this->collectionsRelatedByCreatedByScheduledForDeletion) {
                $this->collectionsRelatedByCreatedByScheduledForDeletion = clone $this->collCollectionsRelatedByCreatedBy;
                $this->collectionsRelatedByCreatedByScheduledForDeletion->clear();
            }
            $this->collectionsRelatedByCreatedByScheduledForDeletion[]= $collectionRelatedByCreatedBy;
            $collectionRelatedByCreatedBy->setUserRelatedByCreatedBy(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CollectionsRelatedByCreatedBy from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Collection[] List of Collection objects
     */
    public function getCollectionsRelatedByCreatedByJoinCollectionType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CollectionQuery::create(null, $criteria);
        $query->joinWith('CollectionType', $join_behavior);

        return $this->getCollectionsRelatedByCreatedBy($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CollectionsRelatedByCreatedBy from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Collection[] List of Collection objects
     */
    public function getCollectionsRelatedByCreatedByJoinCompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CollectionQuery::create(null, $criteria);
        $query->joinWith('Company', $join_behavior);

        return $this->getCollectionsRelatedByCreatedBy($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CollectionsRelatedByCreatedBy from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Collection[] List of Collection objects
     */
    public function getCollectionsRelatedByCreatedByJoinStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CollectionQuery::create(null, $criteria);
        $query->joinWith('Store', $join_behavior);

        return $this->getCollectionsRelatedByCreatedBy($query, $con);
    }

    /**
     * Clears out the collCollectionsRelatedByEditedBy collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addCollectionsRelatedByEditedBy()
     */
    public function clearCollectionsRelatedByEditedBy()
    {
        $this->collCollectionsRelatedByEditedBy = null; // important to set this to null since that means it is uninitialized
        $this->collCollectionsRelatedByEditedByPartial = null;

        return $this;
    }

    /**
     * reset is the collCollectionsRelatedByEditedBy collection loaded partially
     *
     * @return void
     */
    public function resetPartialCollectionsRelatedByEditedBy($v = true)
    {
        $this->collCollectionsRelatedByEditedByPartial = $v;
    }

    /**
     * Initializes the collCollectionsRelatedByEditedBy collection.
     *
     * By default this just sets the collCollectionsRelatedByEditedBy collection to an empty array (like clearcollCollectionsRelatedByEditedBy());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCollectionsRelatedByEditedBy($overrideExisting = true)
    {
        if (null !== $this->collCollectionsRelatedByEditedBy && !$overrideExisting) {
            return;
        }
        $this->collCollectionsRelatedByEditedBy = new PropelObjectCollection();
        $this->collCollectionsRelatedByEditedBy->setModel('Collection');
    }

    /**
     * Gets an array of Collection objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Collection[] List of Collection objects
     * @throws PropelException
     */
    public function getCollectionsRelatedByEditedBy($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCollectionsRelatedByEditedByPartial && !$this->isNew();
        if (null === $this->collCollectionsRelatedByEditedBy || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCollectionsRelatedByEditedBy) {
                // return empty collection
                $this->initCollectionsRelatedByEditedBy();
            } else {
                $collCollectionsRelatedByEditedBy = CollectionQuery::create(null, $criteria)
                    ->filterByUserRelatedByEditedBy($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCollectionsRelatedByEditedByPartial && count($collCollectionsRelatedByEditedBy)) {
                      $this->initCollectionsRelatedByEditedBy(false);

                      foreach ($collCollectionsRelatedByEditedBy as $obj) {
                        if (false == $this->collCollectionsRelatedByEditedBy->contains($obj)) {
                          $this->collCollectionsRelatedByEditedBy->append($obj);
                        }
                      }

                      $this->collCollectionsRelatedByEditedByPartial = true;
                    }

                    $collCollectionsRelatedByEditedBy->getInternalIterator()->rewind();

                    return $collCollectionsRelatedByEditedBy;
                }

                if ($partial && $this->collCollectionsRelatedByEditedBy) {
                    foreach ($this->collCollectionsRelatedByEditedBy as $obj) {
                        if ($obj->isNew()) {
                            $collCollectionsRelatedByEditedBy[] = $obj;
                        }
                    }
                }

                $this->collCollectionsRelatedByEditedBy = $collCollectionsRelatedByEditedBy;
                $this->collCollectionsRelatedByEditedByPartial = false;
            }
        }

        return $this->collCollectionsRelatedByEditedBy;
    }

    /**
     * Sets a collection of CollectionRelatedByEditedBy objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $collectionsRelatedByEditedBy A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setCollectionsRelatedByEditedBy(PropelCollection $collectionsRelatedByEditedBy, PropelPDO $con = null)
    {
        $collectionsRelatedByEditedByToDelete = $this->getCollectionsRelatedByEditedBy(new Criteria(), $con)->diff($collectionsRelatedByEditedBy);


        $this->collectionsRelatedByEditedByScheduledForDeletion = $collectionsRelatedByEditedByToDelete;

        foreach ($collectionsRelatedByEditedByToDelete as $collectionRelatedByEditedByRemoved) {
            $collectionRelatedByEditedByRemoved->setUserRelatedByEditedBy(null);
        }

        $this->collCollectionsRelatedByEditedBy = null;
        foreach ($collectionsRelatedByEditedBy as $collectionRelatedByEditedBy) {
            $this->addCollectionRelatedByEditedBy($collectionRelatedByEditedBy);
        }

        $this->collCollectionsRelatedByEditedBy = $collectionsRelatedByEditedBy;
        $this->collCollectionsRelatedByEditedByPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Collection objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Collection objects.
     * @throws PropelException
     */
    public function countCollectionsRelatedByEditedBy(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCollectionsRelatedByEditedByPartial && !$this->isNew();
        if (null === $this->collCollectionsRelatedByEditedBy || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCollectionsRelatedByEditedBy) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCollectionsRelatedByEditedBy());
            }
            $query = CollectionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByEditedBy($this)
                ->count($con);
        }

        return count($this->collCollectionsRelatedByEditedBy);
    }

    /**
     * Method called to associate a Collection object to this object
     * through the Collection foreign key attribute.
     *
     * @param    Collection $l Collection
     * @return User The current object (for fluent API support)
     */
    public function addCollectionRelatedByEditedBy(Collection $l)
    {
        if ($this->collCollectionsRelatedByEditedBy === null) {
            $this->initCollectionsRelatedByEditedBy();
            $this->collCollectionsRelatedByEditedByPartial = true;
        }

        if (!in_array($l, $this->collCollectionsRelatedByEditedBy->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCollectionRelatedByEditedBy($l);

            if ($this->collectionsRelatedByEditedByScheduledForDeletion and $this->collectionsRelatedByEditedByScheduledForDeletion->contains($l)) {
                $this->collectionsRelatedByEditedByScheduledForDeletion->remove($this->collectionsRelatedByEditedByScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CollectionRelatedByEditedBy $collectionRelatedByEditedBy The collectionRelatedByEditedBy object to add.
     */
    protected function doAddCollectionRelatedByEditedBy($collectionRelatedByEditedBy)
    {
        $this->collCollectionsRelatedByEditedBy[]= $collectionRelatedByEditedBy;
        $collectionRelatedByEditedBy->setUserRelatedByEditedBy($this);
    }

    /**
     * @param	CollectionRelatedByEditedBy $collectionRelatedByEditedBy The collectionRelatedByEditedBy object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeCollectionRelatedByEditedBy($collectionRelatedByEditedBy)
    {
        if ($this->getCollectionsRelatedByEditedBy()->contains($collectionRelatedByEditedBy)) {
            $this->collCollectionsRelatedByEditedBy->remove($this->collCollectionsRelatedByEditedBy->search($collectionRelatedByEditedBy));
            if (null === $this->collectionsRelatedByEditedByScheduledForDeletion) {
                $this->collectionsRelatedByEditedByScheduledForDeletion = clone $this->collCollectionsRelatedByEditedBy;
                $this->collectionsRelatedByEditedByScheduledForDeletion->clear();
            }
            $this->collectionsRelatedByEditedByScheduledForDeletion[]= $collectionRelatedByEditedBy;
            $collectionRelatedByEditedBy->setUserRelatedByEditedBy(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CollectionsRelatedByEditedBy from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Collection[] List of Collection objects
     */
    public function getCollectionsRelatedByEditedByJoinCollectionType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CollectionQuery::create(null, $criteria);
        $query->joinWith('CollectionType', $join_behavior);

        return $this->getCollectionsRelatedByEditedBy($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CollectionsRelatedByEditedBy from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Collection[] List of Collection objects
     */
    public function getCollectionsRelatedByEditedByJoinCompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CollectionQuery::create(null, $criteria);
        $query->joinWith('Company', $join_behavior);

        return $this->getCollectionsRelatedByEditedBy($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CollectionsRelatedByEditedBy from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Collection[] List of Collection objects
     */
    public function getCollectionsRelatedByEditedByJoinStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CollectionQuery::create(null, $criteria);
        $query->joinWith('Store', $join_behavior);

        return $this->getCollectionsRelatedByEditedBy($query, $con);
    }

    /**
     * Clears out the collCompanyContacts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByContact($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setCompanyContacts(PropelCollection $companyContacts, PropelPDO $con = null)
    {
        $companyContactsToDelete = $this->getCompanyContacts(new Criteria(), $con)->diff($companyContacts);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->companyContactsScheduledForDeletion = clone $companyContactsToDelete;

        foreach ($companyContactsToDelete as $companyContactRemoved) {
            $companyContactRemoved->setContact(null);
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
                ->filterByContact($this)
                ->count($con);
        }

        return count($this->collCompanyContacts);
    }

    /**
     * Method called to associate a CompanyContact object to this object
     * through the CompanyContact foreign key attribute.
     *
     * @param    CompanyContact $l CompanyContact
     * @return User The current object (for fluent API support)
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
        $companyContact->setContact($this);
    }

    /**
     * @param	CompanyContact $companyContact The companyContact object to remove.
     * @return User The current object (for fluent API support)
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
            $companyContact->setContact(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CompanyContacts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CompanyContact[] List of CompanyContact objects
     */
    public function getCompanyContactsJoinContactCompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CompanyContactQuery::create(null, $criteria);
        $query->joinWith('ContactCompany', $join_behavior);

        return $this->getCompanyContacts($query, $con);
    }

    /**
     * Clears out the collCompanyInformants collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByInformant($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setCompanyInformants(PropelCollection $companyInformants, PropelPDO $con = null)
    {
        $companyInformantsToDelete = $this->getCompanyInformants(new Criteria(), $con)->diff($companyInformants);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->companyInformantsScheduledForDeletion = clone $companyInformantsToDelete;

        foreach ($companyInformantsToDelete as $companyInformantRemoved) {
            $companyInformantRemoved->setInformant(null);
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
                ->filterByInformant($this)
                ->count($con);
        }

        return count($this->collCompanyInformants);
    }

    /**
     * Method called to associate a CompanyInformant object to this object
     * through the CompanyInformant foreign key attribute.
     *
     * @param    CompanyInformant $l CompanyInformant
     * @return User The current object (for fluent API support)
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
        $companyInformant->setInformant($this);
    }

    /**
     * @param	CompanyInformant $companyInformant The companyInformant object to remove.
     * @return User The current object (for fluent API support)
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
            $companyInformant->setInformant(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CompanyInformants from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CompanyInformant[] List of CompanyInformant objects
     */
    public function getCompanyInformantsJoinInformantCompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CompanyInformantQuery::create(null, $criteria);
        $query->joinWith('InformantCompany', $join_behavior);

        return $this->getCompanyInformants($query, $con);
    }

    /**
     * Clears out the collCompanyOwners collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByOwner($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setCompanyOwners(PropelCollection $companyOwners, PropelPDO $con = null)
    {
        $companyOwnersToDelete = $this->getCompanyOwners(new Criteria(), $con)->diff($companyOwners);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->companyOwnersScheduledForDeletion = clone $companyOwnersToDelete;

        foreach ($companyOwnersToDelete as $companyOwnerRemoved) {
            $companyOwnerRemoved->setOwner(null);
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
                ->filterByOwner($this)
                ->count($con);
        }

        return count($this->collCompanyOwners);
    }

    /**
     * Method called to associate a CompanyOwner object to this object
     * through the CompanyOwner foreign key attribute.
     *
     * @param    CompanyOwner $l CompanyOwner
     * @return User The current object (for fluent API support)
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
        $companyOwner->setOwner($this);
    }

    /**
     * @param	CompanyOwner $companyOwner The companyOwner object to remove.
     * @return User The current object (for fluent API support)
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
            $companyOwner->setOwner(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CompanyOwners from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CompanyOwner[] List of CompanyOwner objects
     */
    public function getCompanyOwnersJoinOwnerCompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CompanyOwnerQuery::create(null, $criteria);
        $query->joinWith('OwnerCompany', $join_behavior);

        return $this->getCompanyOwners($query, $con);
    }

    /**
     * Clears out the collDsTemperatureNotifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addDsTemperatureNotifications()
     */
    public function clearDsTemperatureNotifications()
    {
        $this->collDsTemperatureNotifications = null; // important to set this to null since that means it is uninitialized
        $this->collDsTemperatureNotificationsPartial = null;

        return $this;
    }

    /**
     * reset is the collDsTemperatureNotifications collection loaded partially
     *
     * @return void
     */
    public function resetPartialDsTemperatureNotifications($v = true)
    {
        $this->collDsTemperatureNotificationsPartial = $v;
    }

    /**
     * Initializes the collDsTemperatureNotifications collection.
     *
     * By default this just sets the collDsTemperatureNotifications collection to an empty array (like clearcollDsTemperatureNotifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDsTemperatureNotifications($overrideExisting = true)
    {
        if (null !== $this->collDsTemperatureNotifications && !$overrideExisting) {
            return;
        }
        $this->collDsTemperatureNotifications = new PropelObjectCollection();
        $this->collDsTemperatureNotifications->setModel('DsTemperatureNotification');
    }

    /**
     * Gets an array of DsTemperatureNotification objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|DsTemperatureNotification[] List of DsTemperatureNotification objects
     * @throws PropelException
     */
    public function getDsTemperatureNotifications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collDsTemperatureNotificationsPartial && !$this->isNew();
        if (null === $this->collDsTemperatureNotifications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDsTemperatureNotifications) {
                // return empty collection
                $this->initDsTemperatureNotifications();
            } else {
                $collDsTemperatureNotifications = DsTemperatureNotificationQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collDsTemperatureNotificationsPartial && count($collDsTemperatureNotifications)) {
                      $this->initDsTemperatureNotifications(false);

                      foreach ($collDsTemperatureNotifications as $obj) {
                        if (false == $this->collDsTemperatureNotifications->contains($obj)) {
                          $this->collDsTemperatureNotifications->append($obj);
                        }
                      }

                      $this->collDsTemperatureNotificationsPartial = true;
                    }

                    $collDsTemperatureNotifications->getInternalIterator()->rewind();

                    return $collDsTemperatureNotifications;
                }

                if ($partial && $this->collDsTemperatureNotifications) {
                    foreach ($this->collDsTemperatureNotifications as $obj) {
                        if ($obj->isNew()) {
                            $collDsTemperatureNotifications[] = $obj;
                        }
                    }
                }

                $this->collDsTemperatureNotifications = $collDsTemperatureNotifications;
                $this->collDsTemperatureNotificationsPartial = false;
            }
        }

        return $this->collDsTemperatureNotifications;
    }

    /**
     * Sets a collection of DsTemperatureNotification objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $dsTemperatureNotifications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setDsTemperatureNotifications(PropelCollection $dsTemperatureNotifications, PropelPDO $con = null)
    {
        $dsTemperatureNotificationsToDelete = $this->getDsTemperatureNotifications(new Criteria(), $con)->diff($dsTemperatureNotifications);


        $this->dsTemperatureNotificationsScheduledForDeletion = $dsTemperatureNotificationsToDelete;

        foreach ($dsTemperatureNotificationsToDelete as $dsTemperatureNotificationRemoved) {
            $dsTemperatureNotificationRemoved->setUser(null);
        }

        $this->collDsTemperatureNotifications = null;
        foreach ($dsTemperatureNotifications as $dsTemperatureNotification) {
            $this->addDsTemperatureNotification($dsTemperatureNotification);
        }

        $this->collDsTemperatureNotifications = $dsTemperatureNotifications;
        $this->collDsTemperatureNotificationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related DsTemperatureNotification objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related DsTemperatureNotification objects.
     * @throws PropelException
     */
    public function countDsTemperatureNotifications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collDsTemperatureNotificationsPartial && !$this->isNew();
        if (null === $this->collDsTemperatureNotifications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDsTemperatureNotifications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDsTemperatureNotifications());
            }
            $query = DsTemperatureNotificationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collDsTemperatureNotifications);
    }

    /**
     * Method called to associate a DsTemperatureNotification object to this object
     * through the DsTemperatureNotification foreign key attribute.
     *
     * @param    DsTemperatureNotification $l DsTemperatureNotification
     * @return User The current object (for fluent API support)
     */
    public function addDsTemperatureNotification(DsTemperatureNotification $l)
    {
        if ($this->collDsTemperatureNotifications === null) {
            $this->initDsTemperatureNotifications();
            $this->collDsTemperatureNotificationsPartial = true;
        }

        if (!in_array($l, $this->collDsTemperatureNotifications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddDsTemperatureNotification($l);

            if ($this->dsTemperatureNotificationsScheduledForDeletion and $this->dsTemperatureNotificationsScheduledForDeletion->contains($l)) {
                $this->dsTemperatureNotificationsScheduledForDeletion->remove($this->dsTemperatureNotificationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	DsTemperatureNotification $dsTemperatureNotification The dsTemperatureNotification object to add.
     */
    protected function doAddDsTemperatureNotification($dsTemperatureNotification)
    {
        $this->collDsTemperatureNotifications[]= $dsTemperatureNotification;
        $dsTemperatureNotification->setUser($this);
    }

    /**
     * @param	DsTemperatureNotification $dsTemperatureNotification The dsTemperatureNotification object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeDsTemperatureNotification($dsTemperatureNotification)
    {
        if ($this->getDsTemperatureNotifications()->contains($dsTemperatureNotification)) {
            $this->collDsTemperatureNotifications->remove($this->collDsTemperatureNotifications->search($dsTemperatureNotification));
            if (null === $this->dsTemperatureNotificationsScheduledForDeletion) {
                $this->dsTemperatureNotificationsScheduledForDeletion = clone $this->collDsTemperatureNotifications;
                $this->dsTemperatureNotificationsScheduledForDeletion->clear();
            }
            $this->dsTemperatureNotificationsScheduledForDeletion[]= $dsTemperatureNotification;
            $dsTemperatureNotification->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related DsTemperatureNotifications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DsTemperatureNotification[] List of DsTemperatureNotification objects
     */
    public function getDsTemperatureNotificationsJoinDsTemperatureSensor($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DsTemperatureNotificationQuery::create(null, $criteria);
        $query->joinWith('DsTemperatureSensor', $join_behavior);

        return $this->getDsTemperatureNotifications($query, $con);
    }

    /**
     * Clears out the collCbInputNotifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addCbInputNotifications()
     */
    public function clearCbInputNotifications()
    {
        $this->collCbInputNotifications = null; // important to set this to null since that means it is uninitialized
        $this->collCbInputNotificationsPartial = null;

        return $this;
    }

    /**
     * reset is the collCbInputNotifications collection loaded partially
     *
     * @return void
     */
    public function resetPartialCbInputNotifications($v = true)
    {
        $this->collCbInputNotificationsPartial = $v;
    }

    /**
     * Initializes the collCbInputNotifications collection.
     *
     * By default this just sets the collCbInputNotifications collection to an empty array (like clearcollCbInputNotifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCbInputNotifications($overrideExisting = true)
    {
        if (null !== $this->collCbInputNotifications && !$overrideExisting) {
            return;
        }
        $this->collCbInputNotifications = new PropelObjectCollection();
        $this->collCbInputNotifications->setModel('CbInputNotification');
    }

    /**
     * Gets an array of CbInputNotification objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CbInputNotification[] List of CbInputNotification objects
     * @throws PropelException
     */
    public function getCbInputNotifications($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCbInputNotificationsPartial && !$this->isNew();
        if (null === $this->collCbInputNotifications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCbInputNotifications) {
                // return empty collection
                $this->initCbInputNotifications();
            } else {
                $collCbInputNotifications = CbInputNotificationQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCbInputNotificationsPartial && count($collCbInputNotifications)) {
                      $this->initCbInputNotifications(false);

                      foreach ($collCbInputNotifications as $obj) {
                        if (false == $this->collCbInputNotifications->contains($obj)) {
                          $this->collCbInputNotifications->append($obj);
                        }
                      }

                      $this->collCbInputNotificationsPartial = true;
                    }

                    $collCbInputNotifications->getInternalIterator()->rewind();

                    return $collCbInputNotifications;
                }

                if ($partial && $this->collCbInputNotifications) {
                    foreach ($this->collCbInputNotifications as $obj) {
                        if ($obj->isNew()) {
                            $collCbInputNotifications[] = $obj;
                        }
                    }
                }

                $this->collCbInputNotifications = $collCbInputNotifications;
                $this->collCbInputNotificationsPartial = false;
            }
        }

        return $this->collCbInputNotifications;
    }

    /**
     * Sets a collection of CbInputNotification objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $cbInputNotifications A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setCbInputNotifications(PropelCollection $cbInputNotifications, PropelPDO $con = null)
    {
        $cbInputNotificationsToDelete = $this->getCbInputNotifications(new Criteria(), $con)->diff($cbInputNotifications);


        $this->cbInputNotificationsScheduledForDeletion = $cbInputNotificationsToDelete;

        foreach ($cbInputNotificationsToDelete as $cbInputNotificationRemoved) {
            $cbInputNotificationRemoved->setUser(null);
        }

        $this->collCbInputNotifications = null;
        foreach ($cbInputNotifications as $cbInputNotification) {
            $this->addCbInputNotification($cbInputNotification);
        }

        $this->collCbInputNotifications = $cbInputNotifications;
        $this->collCbInputNotificationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CbInputNotification objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CbInputNotification objects.
     * @throws PropelException
     */
    public function countCbInputNotifications(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCbInputNotificationsPartial && !$this->isNew();
        if (null === $this->collCbInputNotifications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCbInputNotifications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCbInputNotifications());
            }
            $query = CbInputNotificationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collCbInputNotifications);
    }

    /**
     * Method called to associate a CbInputNotification object to this object
     * through the CbInputNotification foreign key attribute.
     *
     * @param    CbInputNotification $l CbInputNotification
     * @return User The current object (for fluent API support)
     */
    public function addCbInputNotification(CbInputNotification $l)
    {
        if ($this->collCbInputNotifications === null) {
            $this->initCbInputNotifications();
            $this->collCbInputNotificationsPartial = true;
        }

        if (!in_array($l, $this->collCbInputNotifications->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCbInputNotification($l);

            if ($this->cbInputNotificationsScheduledForDeletion and $this->cbInputNotificationsScheduledForDeletion->contains($l)) {
                $this->cbInputNotificationsScheduledForDeletion->remove($this->cbInputNotificationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CbInputNotification $cbInputNotification The cbInputNotification object to add.
     */
    protected function doAddCbInputNotification($cbInputNotification)
    {
        $this->collCbInputNotifications[]= $cbInputNotification;
        $cbInputNotification->setUser($this);
    }

    /**
     * @param	CbInputNotification $cbInputNotification The cbInputNotification object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeCbInputNotification($cbInputNotification)
    {
        if ($this->getCbInputNotifications()->contains($cbInputNotification)) {
            $this->collCbInputNotifications->remove($this->collCbInputNotifications->search($cbInputNotification));
            if (null === $this->cbInputNotificationsScheduledForDeletion) {
                $this->cbInputNotificationsScheduledForDeletion = clone $this->collCbInputNotifications;
                $this->cbInputNotificationsScheduledForDeletion->clear();
            }
            $this->cbInputNotificationsScheduledForDeletion[]= $cbInputNotification;
            $cbInputNotification->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CbInputNotifications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CbInputNotification[] List of CbInputNotification objects
     */
    public function getCbInputNotificationsJoinCbInput($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CbInputNotificationQuery::create(null, $criteria);
        $query->joinWith('CbInput', $join_behavior);

        return $this->getCbInputNotifications($query, $con);
    }

    /**
     * Clears out the collStoreContacts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByContact($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setStoreContacts(PropelCollection $storeContacts, PropelPDO $con = null)
    {
        $storeContactsToDelete = $this->getStoreContacts(new Criteria(), $con)->diff($storeContacts);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->storeContactsScheduledForDeletion = clone $storeContactsToDelete;

        foreach ($storeContactsToDelete as $storeContactRemoved) {
            $storeContactRemoved->setContact(null);
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
                ->filterByContact($this)
                ->count($con);
        }

        return count($this->collStoreContacts);
    }

    /**
     * Method called to associate a StoreContact object to this object
     * through the StoreContact foreign key attribute.
     *
     * @param    StoreContact $l StoreContact
     * @return User The current object (for fluent API support)
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
        $storeContact->setContact($this);
    }

    /**
     * @param	StoreContact $storeContact The storeContact object to remove.
     * @return User The current object (for fluent API support)
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
            $storeContact->setContact(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related StoreContacts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StoreContact[] List of StoreContact objects
     */
    public function getStoreContactsJoinContactStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreContactQuery::create(null, $criteria);
        $query->joinWith('ContactStore', $join_behavior);

        return $this->getStoreContacts($query, $con);
    }

    /**
     * Clears out the collStoreInformants collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByInformant($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setStoreInformants(PropelCollection $storeInformants, PropelPDO $con = null)
    {
        $storeInformantsToDelete = $this->getStoreInformants(new Criteria(), $con)->diff($storeInformants);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->storeInformantsScheduledForDeletion = clone $storeInformantsToDelete;

        foreach ($storeInformantsToDelete as $storeInformantRemoved) {
            $storeInformantRemoved->setInformant(null);
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
                ->filterByInformant($this)
                ->count($con);
        }

        return count($this->collStoreInformants);
    }

    /**
     * Method called to associate a StoreInformant object to this object
     * through the StoreInformant foreign key attribute.
     *
     * @param    StoreInformant $l StoreInformant
     * @return User The current object (for fluent API support)
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
        $storeInformant->setInformant($this);
    }

    /**
     * @param	StoreInformant $storeInformant The storeInformant object to remove.
     * @return User The current object (for fluent API support)
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
            $storeInformant->setInformant(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related StoreInformants from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StoreInformant[] List of StoreInformant objects
     */
    public function getStoreInformantsJoinInformantStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreInformantQuery::create(null, $criteria);
        $query->joinWith('InformantStore', $join_behavior);

        return $this->getStoreInformants($query, $con);
    }

    /**
     * Clears out the collStoreOwners collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByOwner($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setStoreOwners(PropelCollection $storeOwners, PropelPDO $con = null)
    {
        $storeOwnersToDelete = $this->getStoreOwners(new Criteria(), $con)->diff($storeOwners);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->storeOwnersScheduledForDeletion = clone $storeOwnersToDelete;

        foreach ($storeOwnersToDelete as $storeOwnerRemoved) {
            $storeOwnerRemoved->setOwner(null);
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
                ->filterByOwner($this)
                ->count($con);
        }

        return count($this->collStoreOwners);
    }

    /**
     * Method called to associate a StoreOwner object to this object
     * through the StoreOwner foreign key attribute.
     *
     * @param    StoreOwner $l StoreOwner
     * @return User The current object (for fluent API support)
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
        $storeOwner->setOwner($this);
    }

    /**
     * @param	StoreOwner $storeOwner The storeOwner object to remove.
     * @return User The current object (for fluent API support)
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
            $storeOwner->setOwner(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related StoreOwners from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|StoreOwner[] List of StoreOwner objects
     */
    public function getStoreOwnersJoinOwnerStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = StoreOwnerQuery::create(null, $criteria);
        $query->joinWith('OwnerStore', $join_behavior);

        return $this->getStoreOwners($query, $con);
    }

    /**
     * Clears out the collUserRoles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addUserRoles()
     */
    public function clearUserRoles()
    {
        $this->collUserRoles = null; // important to set this to null since that means it is uninitialized
        $this->collUserRolesPartial = null;

        return $this;
    }

    /**
     * reset is the collUserRoles collection loaded partially
     *
     * @return void
     */
    public function resetPartialUserRoles($v = true)
    {
        $this->collUserRolesPartial = $v;
    }

    /**
     * Initializes the collUserRoles collection.
     *
     * By default this just sets the collUserRoles collection to an empty array (like clearcollUserRoles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserRoles($overrideExisting = true)
    {
        if (null !== $this->collUserRoles && !$overrideExisting) {
            return;
        }
        $this->collUserRoles = new PropelObjectCollection();
        $this->collUserRoles->setModel('UserRole');
    }

    /**
     * Gets an array of UserRole objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserRole[] List of UserRole objects
     * @throws PropelException
     */
    public function getUserRoles($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserRolesPartial && !$this->isNew();
        if (null === $this->collUserRoles || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserRoles) {
                // return empty collection
                $this->initUserRoles();
            } else {
                $collUserRoles = UserRoleQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUserRolesPartial && count($collUserRoles)) {
                      $this->initUserRoles(false);

                      foreach ($collUserRoles as $obj) {
                        if (false == $this->collUserRoles->contains($obj)) {
                          $this->collUserRoles->append($obj);
                        }
                      }

                      $this->collUserRolesPartial = true;
                    }

                    $collUserRoles->getInternalIterator()->rewind();

                    return $collUserRoles;
                }

                if ($partial && $this->collUserRoles) {
                    foreach ($this->collUserRoles as $obj) {
                        if ($obj->isNew()) {
                            $collUserRoles[] = $obj;
                        }
                    }
                }

                $this->collUserRoles = $collUserRoles;
                $this->collUserRolesPartial = false;
            }
        }

        return $this->collUserRoles;
    }

    /**
     * Sets a collection of UserRole objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userRoles A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setUserRoles(PropelCollection $userRoles, PropelPDO $con = null)
    {
        $userRolesToDelete = $this->getUserRoles(new Criteria(), $con)->diff($userRoles);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userRolesScheduledForDeletion = clone $userRolesToDelete;

        foreach ($userRolesToDelete as $userRoleRemoved) {
            $userRoleRemoved->setUser(null);
        }

        $this->collUserRoles = null;
        foreach ($userRoles as $userRole) {
            $this->addUserRole($userRole);
        }

        $this->collUserRoles = $userRoles;
        $this->collUserRolesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserRole objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserRole objects.
     * @throws PropelException
     */
    public function countUserRoles(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserRolesPartial && !$this->isNew();
        if (null === $this->collUserRoles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserRoles) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserRoles());
            }
            $query = UserRoleQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserRoles);
    }

    /**
     * Method called to associate a UserRole object to this object
     * through the UserRole foreign key attribute.
     *
     * @param    UserRole $l UserRole
     * @return User The current object (for fluent API support)
     */
    public function addUserRole(UserRole $l)
    {
        if ($this->collUserRoles === null) {
            $this->initUserRoles();
            $this->collUserRolesPartial = true;
        }

        if (!in_array($l, $this->collUserRoles->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserRole($l);

            if ($this->userRolesScheduledForDeletion and $this->userRolesScheduledForDeletion->contains($l)) {
                $this->userRolesScheduledForDeletion->remove($this->userRolesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	UserRole $userRole The userRole object to add.
     */
    protected function doAddUserRole($userRole)
    {
        $this->collUserRoles[]= $userRole;
        $userRole->setUser($this);
    }

    /**
     * @param	UserRole $userRole The userRole object to remove.
     * @return User The current object (for fluent API support)
     */
    public function removeUserRole($userRole)
    {
        if ($this->getUserRoles()->contains($userRole)) {
            $this->collUserRoles->remove($this->collUserRoles->search($userRole));
            if (null === $this->userRolesScheduledForDeletion) {
                $this->userRolesScheduledForDeletion = clone $this->collUserRoles;
                $this->userRolesScheduledForDeletion->clear();
            }
            $this->userRolesScheduledForDeletion[]= clone $userRole;
            $userRole->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserRoles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserRole[] List of UserRole objects
     */
    public function getUserRolesJoinRole($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserRoleQuery::create(null, $criteria);
        $query->joinWith('Role', $join_behavior);

        return $this->getUserRoles($query, $con);
    }

    /**
     * Clears out the collUserAddresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setUserAddresses(PropelCollection $userAddresses, PropelPDO $con = null)
    {
        $userAddressesToDelete = $this->getUserAddresses(new Criteria(), $con)->diff($userAddresses);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userAddressesScheduledForDeletion = clone $userAddressesToDelete;

        foreach ($userAddressesToDelete as $userAddressRemoved) {
            $userAddressRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserAddresses);
    }

    /**
     * Method called to associate a UserAddress object to this object
     * through the UserAddress foreign key attribute.
     *
     * @param    UserAddress $l UserAddress
     * @return User The current object (for fluent API support)
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
        $userAddress->setUser($this);
    }

    /**
     * @param	UserAddress $userAddress The userAddress object to remove.
     * @return User The current object (for fluent API support)
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
            $userAddress->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserAddresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserAddress[] List of UserAddress objects
     */
    public function getUserAddressesJoinAddress($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserAddressQuery::create(null, $criteria);
        $query->joinWith('Address', $join_behavior);

        return $this->getUserAddresses($query, $con);
    }

    /**
     * Clears out the collUserEmails collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setUserEmails(PropelCollection $userEmails, PropelPDO $con = null)
    {
        $userEmailsToDelete = $this->getUserEmails(new Criteria(), $con)->diff($userEmails);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userEmailsScheduledForDeletion = clone $userEmailsToDelete;

        foreach ($userEmailsToDelete as $userEmailRemoved) {
            $userEmailRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserEmails);
    }

    /**
     * Method called to associate a UserEmail object to this object
     * through the UserEmail foreign key attribute.
     *
     * @param    UserEmail $l UserEmail
     * @return User The current object (for fluent API support)
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
        $userEmail->setUser($this);
    }

    /**
     * @param	UserEmail $userEmail The userEmail object to remove.
     * @return User The current object (for fluent API support)
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
            $userEmail->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserEmails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserEmail[] List of UserEmail objects
     */
    public function getUserEmailsJoinEmail($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserEmailQuery::create(null, $criteria);
        $query->joinWith('Email', $join_behavior);

        return $this->getUserEmails($query, $con);
    }

    /**
     * Clears out the collUserPhones collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
     * @return User The current object (for fluent API support)
     */
    public function setUserPhones(PropelCollection $userPhones, PropelPDO $con = null)
    {
        $userPhonesToDelete = $this->getUserPhones(new Criteria(), $con)->diff($userPhones);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userPhonesScheduledForDeletion = clone $userPhonesToDelete;

        foreach ($userPhonesToDelete as $userPhoneRemoved) {
            $userPhoneRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserPhones);
    }

    /**
     * Method called to associate a UserPhone object to this object
     * through the UserPhone foreign key attribute.
     *
     * @param    UserPhone $l UserPhone
     * @return User The current object (for fluent API support)
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
        $userPhone->setUser($this);
    }

    /**
     * @param	UserPhone $userPhone The userPhone object to remove.
     * @return User The current object (for fluent API support)
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
            $userPhone->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserPhones from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserPhone[] List of UserPhone objects
     */
    public function getUserPhonesJoinPhone($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserPhoneQuery::create(null, $criteria);
        $query->joinWith('Phone', $join_behavior);

        return $this->getUserPhones($query, $con);
    }

    /**
     * Clears out the collContactCompanies collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addContactCompanies()
     */
    public function clearContactCompanies()
    {
        $this->collContactCompanies = null; // important to set this to null since that means it is uninitialized
        $this->collContactCompaniesPartial = null;

        return $this;
    }

    /**
     * Initializes the collContactCompanies collection.
     *
     * By default this just sets the collContactCompanies collection to an empty collection (like clearContactCompanies());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initContactCompanies()
    {
        $this->collContactCompanies = new PropelObjectCollection();
        $this->collContactCompanies->setModel('Company');
    }

    /**
     * Gets a collection of Company objects related by a many-to-many relationship
     * to the current object by way of the company_contact cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Company[] List of Company objects
     */
    public function getContactCompanies($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collContactCompanies || null !== $criteria) {
            if ($this->isNew() && null === $this->collContactCompanies) {
                // return empty collection
                $this->initContactCompanies();
            } else {
                $collContactCompanies = CompanyQuery::create(null, $criteria)
                    ->filterByContact($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collContactCompanies;
                }
                $this->collContactCompanies = $collContactCompanies;
            }
        }

        return $this->collContactCompanies;
    }

    /**
     * Sets a collection of Company objects related by a many-to-many relationship
     * to the current object by way of the company_contact cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $contactCompanies A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setContactCompanies(PropelCollection $contactCompanies, PropelPDO $con = null)
    {
        $this->clearContactCompanies();
        $currentContactCompanies = $this->getContactCompanies(null, $con);

        $this->contactCompaniesScheduledForDeletion = $currentContactCompanies->diff($contactCompanies);

        foreach ($contactCompanies as $contactCompany) {
            if (!$currentContactCompanies->contains($contactCompany)) {
                $this->doAddContactCompany($contactCompany);
            }
        }

        $this->collContactCompanies = $contactCompanies;

        return $this;
    }

    /**
     * Gets the number of Company objects related by a many-to-many relationship
     * to the current object by way of the company_contact cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Company objects
     */
    public function countContactCompanies($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collContactCompanies || null !== $criteria) {
            if ($this->isNew() && null === $this->collContactCompanies) {
                return 0;
            } else {
                $query = CompanyQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByContact($this)
                    ->count($con);
            }
        } else {
            return count($this->collContactCompanies);
        }
    }

    /**
     * Associate a Company object to this object
     * through the company_contact cross reference table.
     *
     * @param  Company $company The CompanyContact object to relate
     * @return User The current object (for fluent API support)
     */
    public function addContactCompany(Company $company)
    {
        if ($this->collContactCompanies === null) {
            $this->initContactCompanies();
        }

        if (!$this->collContactCompanies->contains($company)) { // only add it if the **same** object is not already associated
            $this->doAddContactCompany($company);
            $this->collContactCompanies[] = $company;

            if ($this->contactCompaniesScheduledForDeletion and $this->contactCompaniesScheduledForDeletion->contains($company)) {
                $this->contactCompaniesScheduledForDeletion->remove($this->contactCompaniesScheduledForDeletion->search($company));
            }
        }

        return $this;
    }

    /**
     * @param	ContactCompany $contactCompany The contactCompany object to add.
     */
    protected function doAddContactCompany(Company $contactCompany)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$contactCompany->getContacts()->contains($this)) { $companyContact = new CompanyContact();
            $companyContact->setContactCompany($contactCompany);
            $this->addCompanyContact($companyContact);

            $foreignCollection = $contactCompany->getContacts();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Company object to this object
     * through the company_contact cross reference table.
     *
     * @param Company $company The CompanyContact object to relate
     * @return User The current object (for fluent API support)
     */
    public function removeContactCompany(Company $company)
    {
        if ($this->getContactCompanies()->contains($company)) {
            $this->collContactCompanies->remove($this->collContactCompanies->search($company));
            if (null === $this->contactCompaniesScheduledForDeletion) {
                $this->contactCompaniesScheduledForDeletion = clone $this->collContactCompanies;
                $this->contactCompaniesScheduledForDeletion->clear();
            }
            $this->contactCompaniesScheduledForDeletion[]= $company;
        }

        return $this;
    }

    /**
     * Clears out the collInformantCompanies collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addInformantCompanies()
     */
    public function clearInformantCompanies()
    {
        $this->collInformantCompanies = null; // important to set this to null since that means it is uninitialized
        $this->collInformantCompaniesPartial = null;

        return $this;
    }

    /**
     * Initializes the collInformantCompanies collection.
     *
     * By default this just sets the collInformantCompanies collection to an empty collection (like clearInformantCompanies());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initInformantCompanies()
    {
        $this->collInformantCompanies = new PropelObjectCollection();
        $this->collInformantCompanies->setModel('Company');
    }

    /**
     * Gets a collection of Company objects related by a many-to-many relationship
     * to the current object by way of the company_informant cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Company[] List of Company objects
     */
    public function getInformantCompanies($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collInformantCompanies || null !== $criteria) {
            if ($this->isNew() && null === $this->collInformantCompanies) {
                // return empty collection
                $this->initInformantCompanies();
            } else {
                $collInformantCompanies = CompanyQuery::create(null, $criteria)
                    ->filterByInformant($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collInformantCompanies;
                }
                $this->collInformantCompanies = $collInformantCompanies;
            }
        }

        return $this->collInformantCompanies;
    }

    /**
     * Sets a collection of Company objects related by a many-to-many relationship
     * to the current object by way of the company_informant cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $informantCompanies A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setInformantCompanies(PropelCollection $informantCompanies, PropelPDO $con = null)
    {
        $this->clearInformantCompanies();
        $currentInformantCompanies = $this->getInformantCompanies(null, $con);

        $this->informantCompaniesScheduledForDeletion = $currentInformantCompanies->diff($informantCompanies);

        foreach ($informantCompanies as $informantCompany) {
            if (!$currentInformantCompanies->contains($informantCompany)) {
                $this->doAddInformantCompany($informantCompany);
            }
        }

        $this->collInformantCompanies = $informantCompanies;

        return $this;
    }

    /**
     * Gets the number of Company objects related by a many-to-many relationship
     * to the current object by way of the company_informant cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Company objects
     */
    public function countInformantCompanies($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collInformantCompanies || null !== $criteria) {
            if ($this->isNew() && null === $this->collInformantCompanies) {
                return 0;
            } else {
                $query = CompanyQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByInformant($this)
                    ->count($con);
            }
        } else {
            return count($this->collInformantCompanies);
        }
    }

    /**
     * Associate a Company object to this object
     * through the company_informant cross reference table.
     *
     * @param  Company $company The CompanyInformant object to relate
     * @return User The current object (for fluent API support)
     */
    public function addInformantCompany(Company $company)
    {
        if ($this->collInformantCompanies === null) {
            $this->initInformantCompanies();
        }

        if (!$this->collInformantCompanies->contains($company)) { // only add it if the **same** object is not already associated
            $this->doAddInformantCompany($company);
            $this->collInformantCompanies[] = $company;

            if ($this->informantCompaniesScheduledForDeletion and $this->informantCompaniesScheduledForDeletion->contains($company)) {
                $this->informantCompaniesScheduledForDeletion->remove($this->informantCompaniesScheduledForDeletion->search($company));
            }
        }

        return $this;
    }

    /**
     * @param	InformantCompany $informantCompany The informantCompany object to add.
     */
    protected function doAddInformantCompany(Company $informantCompany)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$informantCompany->getInformants()->contains($this)) { $companyInformant = new CompanyInformant();
            $companyInformant->setInformantCompany($informantCompany);
            $this->addCompanyInformant($companyInformant);

            $foreignCollection = $informantCompany->getInformants();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Company object to this object
     * through the company_informant cross reference table.
     *
     * @param Company $company The CompanyInformant object to relate
     * @return User The current object (for fluent API support)
     */
    public function removeInformantCompany(Company $company)
    {
        if ($this->getInformantCompanies()->contains($company)) {
            $this->collInformantCompanies->remove($this->collInformantCompanies->search($company));
            if (null === $this->informantCompaniesScheduledForDeletion) {
                $this->informantCompaniesScheduledForDeletion = clone $this->collInformantCompanies;
                $this->informantCompaniesScheduledForDeletion->clear();
            }
            $this->informantCompaniesScheduledForDeletion[]= $company;
        }

        return $this;
    }

    /**
     * Clears out the collOwnerCompanies collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addOwnerCompanies()
     */
    public function clearOwnerCompanies()
    {
        $this->collOwnerCompanies = null; // important to set this to null since that means it is uninitialized
        $this->collOwnerCompaniesPartial = null;

        return $this;
    }

    /**
     * Initializes the collOwnerCompanies collection.
     *
     * By default this just sets the collOwnerCompanies collection to an empty collection (like clearOwnerCompanies());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initOwnerCompanies()
    {
        $this->collOwnerCompanies = new PropelObjectCollection();
        $this->collOwnerCompanies->setModel('Company');
    }

    /**
     * Gets a collection of Company objects related by a many-to-many relationship
     * to the current object by way of the company_owner cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Company[] List of Company objects
     */
    public function getOwnerCompanies($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collOwnerCompanies || null !== $criteria) {
            if ($this->isNew() && null === $this->collOwnerCompanies) {
                // return empty collection
                $this->initOwnerCompanies();
            } else {
                $collOwnerCompanies = CompanyQuery::create(null, $criteria)
                    ->filterByOwner($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collOwnerCompanies;
                }
                $this->collOwnerCompanies = $collOwnerCompanies;
            }
        }

        return $this->collOwnerCompanies;
    }

    /**
     * Sets a collection of Company objects related by a many-to-many relationship
     * to the current object by way of the company_owner cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $ownerCompanies A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setOwnerCompanies(PropelCollection $ownerCompanies, PropelPDO $con = null)
    {
        $this->clearOwnerCompanies();
        $currentOwnerCompanies = $this->getOwnerCompanies(null, $con);

        $this->ownerCompaniesScheduledForDeletion = $currentOwnerCompanies->diff($ownerCompanies);

        foreach ($ownerCompanies as $ownerCompany) {
            if (!$currentOwnerCompanies->contains($ownerCompany)) {
                $this->doAddOwnerCompany($ownerCompany);
            }
        }

        $this->collOwnerCompanies = $ownerCompanies;

        return $this;
    }

    /**
     * Gets the number of Company objects related by a many-to-many relationship
     * to the current object by way of the company_owner cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Company objects
     */
    public function countOwnerCompanies($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collOwnerCompanies || null !== $criteria) {
            if ($this->isNew() && null === $this->collOwnerCompanies) {
                return 0;
            } else {
                $query = CompanyQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByOwner($this)
                    ->count($con);
            }
        } else {
            return count($this->collOwnerCompanies);
        }
    }

    /**
     * Associate a Company object to this object
     * through the company_owner cross reference table.
     *
     * @param  Company $company The CompanyOwner object to relate
     * @return User The current object (for fluent API support)
     */
    public function addOwnerCompany(Company $company)
    {
        if ($this->collOwnerCompanies === null) {
            $this->initOwnerCompanies();
        }

        if (!$this->collOwnerCompanies->contains($company)) { // only add it if the **same** object is not already associated
            $this->doAddOwnerCompany($company);
            $this->collOwnerCompanies[] = $company;

            if ($this->ownerCompaniesScheduledForDeletion and $this->ownerCompaniesScheduledForDeletion->contains($company)) {
                $this->ownerCompaniesScheduledForDeletion->remove($this->ownerCompaniesScheduledForDeletion->search($company));
            }
        }

        return $this;
    }

    /**
     * @param	OwnerCompany $ownerCompany The ownerCompany object to add.
     */
    protected function doAddOwnerCompany(Company $ownerCompany)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$ownerCompany->getOwners()->contains($this)) { $companyOwner = new CompanyOwner();
            $companyOwner->setOwnerCompany($ownerCompany);
            $this->addCompanyOwner($companyOwner);

            $foreignCollection = $ownerCompany->getOwners();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Company object to this object
     * through the company_owner cross reference table.
     *
     * @param Company $company The CompanyOwner object to relate
     * @return User The current object (for fluent API support)
     */
    public function removeOwnerCompany(Company $company)
    {
        if ($this->getOwnerCompanies()->contains($company)) {
            $this->collOwnerCompanies->remove($this->collOwnerCompanies->search($company));
            if (null === $this->ownerCompaniesScheduledForDeletion) {
                $this->ownerCompaniesScheduledForDeletion = clone $this->collOwnerCompanies;
                $this->ownerCompaniesScheduledForDeletion->clear();
            }
            $this->ownerCompaniesScheduledForDeletion[]= $company;
        }

        return $this;
    }

    /**
     * Clears out the collContactStores collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addContactStores()
     */
    public function clearContactStores()
    {
        $this->collContactStores = null; // important to set this to null since that means it is uninitialized
        $this->collContactStoresPartial = null;

        return $this;
    }

    /**
     * Initializes the collContactStores collection.
     *
     * By default this just sets the collContactStores collection to an empty collection (like clearContactStores());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initContactStores()
    {
        $this->collContactStores = new PropelObjectCollection();
        $this->collContactStores->setModel('Store');
    }

    /**
     * Gets a collection of Store objects related by a many-to-many relationship
     * to the current object by way of the store_contact cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Store[] List of Store objects
     */
    public function getContactStores($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collContactStores || null !== $criteria) {
            if ($this->isNew() && null === $this->collContactStores) {
                // return empty collection
                $this->initContactStores();
            } else {
                $collContactStores = StoreQuery::create(null, $criteria)
                    ->filterByContact($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collContactStores;
                }
                $this->collContactStores = $collContactStores;
            }
        }

        return $this->collContactStores;
    }

    /**
     * Sets a collection of Store objects related by a many-to-many relationship
     * to the current object by way of the store_contact cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $contactStores A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setContactStores(PropelCollection $contactStores, PropelPDO $con = null)
    {
        $this->clearContactStores();
        $currentContactStores = $this->getContactStores(null, $con);

        $this->contactStoresScheduledForDeletion = $currentContactStores->diff($contactStores);

        foreach ($contactStores as $contactStore) {
            if (!$currentContactStores->contains($contactStore)) {
                $this->doAddContactStore($contactStore);
            }
        }

        $this->collContactStores = $contactStores;

        return $this;
    }

    /**
     * Gets the number of Store objects related by a many-to-many relationship
     * to the current object by way of the store_contact cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Store objects
     */
    public function countContactStores($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collContactStores || null !== $criteria) {
            if ($this->isNew() && null === $this->collContactStores) {
                return 0;
            } else {
                $query = StoreQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByContact($this)
                    ->count($con);
            }
        } else {
            return count($this->collContactStores);
        }
    }

    /**
     * Associate a Store object to this object
     * through the store_contact cross reference table.
     *
     * @param  Store $store The StoreContact object to relate
     * @return User The current object (for fluent API support)
     */
    public function addContactStore(Store $store)
    {
        if ($this->collContactStores === null) {
            $this->initContactStores();
        }

        if (!$this->collContactStores->contains($store)) { // only add it if the **same** object is not already associated
            $this->doAddContactStore($store);
            $this->collContactStores[] = $store;

            if ($this->contactStoresScheduledForDeletion and $this->contactStoresScheduledForDeletion->contains($store)) {
                $this->contactStoresScheduledForDeletion->remove($this->contactStoresScheduledForDeletion->search($store));
            }
        }

        return $this;
    }

    /**
     * @param	ContactStore $contactStore The contactStore object to add.
     */
    protected function doAddContactStore(Store $contactStore)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$contactStore->getContacts()->contains($this)) { $storeContact = new StoreContact();
            $storeContact->setContactStore($contactStore);
            $this->addStoreContact($storeContact);

            $foreignCollection = $contactStore->getContacts();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Store object to this object
     * through the store_contact cross reference table.
     *
     * @param Store $store The StoreContact object to relate
     * @return User The current object (for fluent API support)
     */
    public function removeContactStore(Store $store)
    {
        if ($this->getContactStores()->contains($store)) {
            $this->collContactStores->remove($this->collContactStores->search($store));
            if (null === $this->contactStoresScheduledForDeletion) {
                $this->contactStoresScheduledForDeletion = clone $this->collContactStores;
                $this->contactStoresScheduledForDeletion->clear();
            }
            $this->contactStoresScheduledForDeletion[]= $store;
        }

        return $this;
    }

    /**
     * Clears out the collInformantStores collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addInformantStores()
     */
    public function clearInformantStores()
    {
        $this->collInformantStores = null; // important to set this to null since that means it is uninitialized
        $this->collInformantStoresPartial = null;

        return $this;
    }

    /**
     * Initializes the collInformantStores collection.
     *
     * By default this just sets the collInformantStores collection to an empty collection (like clearInformantStores());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initInformantStores()
    {
        $this->collInformantStores = new PropelObjectCollection();
        $this->collInformantStores->setModel('Store');
    }

    /**
     * Gets a collection of Store objects related by a many-to-many relationship
     * to the current object by way of the store_informant cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Store[] List of Store objects
     */
    public function getInformantStores($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collInformantStores || null !== $criteria) {
            if ($this->isNew() && null === $this->collInformantStores) {
                // return empty collection
                $this->initInformantStores();
            } else {
                $collInformantStores = StoreQuery::create(null, $criteria)
                    ->filterByInformant($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collInformantStores;
                }
                $this->collInformantStores = $collInformantStores;
            }
        }

        return $this->collInformantStores;
    }

    /**
     * Sets a collection of Store objects related by a many-to-many relationship
     * to the current object by way of the store_informant cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $informantStores A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setInformantStores(PropelCollection $informantStores, PropelPDO $con = null)
    {
        $this->clearInformantStores();
        $currentInformantStores = $this->getInformantStores(null, $con);

        $this->informantStoresScheduledForDeletion = $currentInformantStores->diff($informantStores);

        foreach ($informantStores as $informantStore) {
            if (!$currentInformantStores->contains($informantStore)) {
                $this->doAddInformantStore($informantStore);
            }
        }

        $this->collInformantStores = $informantStores;

        return $this;
    }

    /**
     * Gets the number of Store objects related by a many-to-many relationship
     * to the current object by way of the store_informant cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Store objects
     */
    public function countInformantStores($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collInformantStores || null !== $criteria) {
            if ($this->isNew() && null === $this->collInformantStores) {
                return 0;
            } else {
                $query = StoreQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByInformant($this)
                    ->count($con);
            }
        } else {
            return count($this->collInformantStores);
        }
    }

    /**
     * Associate a Store object to this object
     * through the store_informant cross reference table.
     *
     * @param  Store $store The StoreInformant object to relate
     * @return User The current object (for fluent API support)
     */
    public function addInformantStore(Store $store)
    {
        if ($this->collInformantStores === null) {
            $this->initInformantStores();
        }

        if (!$this->collInformantStores->contains($store)) { // only add it if the **same** object is not already associated
            $this->doAddInformantStore($store);
            $this->collInformantStores[] = $store;

            if ($this->informantStoresScheduledForDeletion and $this->informantStoresScheduledForDeletion->contains($store)) {
                $this->informantStoresScheduledForDeletion->remove($this->informantStoresScheduledForDeletion->search($store));
            }
        }

        return $this;
    }

    /**
     * @param	InformantStore $informantStore The informantStore object to add.
     */
    protected function doAddInformantStore(Store $informantStore)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$informantStore->getInformants()->contains($this)) { $storeInformant = new StoreInformant();
            $storeInformant->setInformantStore($informantStore);
            $this->addStoreInformant($storeInformant);

            $foreignCollection = $informantStore->getInformants();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Store object to this object
     * through the store_informant cross reference table.
     *
     * @param Store $store The StoreInformant object to relate
     * @return User The current object (for fluent API support)
     */
    public function removeInformantStore(Store $store)
    {
        if ($this->getInformantStores()->contains($store)) {
            $this->collInformantStores->remove($this->collInformantStores->search($store));
            if (null === $this->informantStoresScheduledForDeletion) {
                $this->informantStoresScheduledForDeletion = clone $this->collInformantStores;
                $this->informantStoresScheduledForDeletion->clear();
            }
            $this->informantStoresScheduledForDeletion[]= $store;
        }

        return $this;
    }

    /**
     * Clears out the collOwnerStores collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addOwnerStores()
     */
    public function clearOwnerStores()
    {
        $this->collOwnerStores = null; // important to set this to null since that means it is uninitialized
        $this->collOwnerStoresPartial = null;

        return $this;
    }

    /**
     * Initializes the collOwnerStores collection.
     *
     * By default this just sets the collOwnerStores collection to an empty collection (like clearOwnerStores());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initOwnerStores()
    {
        $this->collOwnerStores = new PropelObjectCollection();
        $this->collOwnerStores->setModel('Store');
    }

    /**
     * Gets a collection of Store objects related by a many-to-many relationship
     * to the current object by way of the store_owner cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Store[] List of Store objects
     */
    public function getOwnerStores($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collOwnerStores || null !== $criteria) {
            if ($this->isNew() && null === $this->collOwnerStores) {
                // return empty collection
                $this->initOwnerStores();
            } else {
                $collOwnerStores = StoreQuery::create(null, $criteria)
                    ->filterByOwner($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collOwnerStores;
                }
                $this->collOwnerStores = $collOwnerStores;
            }
        }

        return $this->collOwnerStores;
    }

    /**
     * Sets a collection of Store objects related by a many-to-many relationship
     * to the current object by way of the store_owner cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $ownerStores A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setOwnerStores(PropelCollection $ownerStores, PropelPDO $con = null)
    {
        $this->clearOwnerStores();
        $currentOwnerStores = $this->getOwnerStores(null, $con);

        $this->ownerStoresScheduledForDeletion = $currentOwnerStores->diff($ownerStores);

        foreach ($ownerStores as $ownerStore) {
            if (!$currentOwnerStores->contains($ownerStore)) {
                $this->doAddOwnerStore($ownerStore);
            }
        }

        $this->collOwnerStores = $ownerStores;

        return $this;
    }

    /**
     * Gets the number of Store objects related by a many-to-many relationship
     * to the current object by way of the store_owner cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Store objects
     */
    public function countOwnerStores($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collOwnerStores || null !== $criteria) {
            if ($this->isNew() && null === $this->collOwnerStores) {
                return 0;
            } else {
                $query = StoreQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByOwner($this)
                    ->count($con);
            }
        } else {
            return count($this->collOwnerStores);
        }
    }

    /**
     * Associate a Store object to this object
     * through the store_owner cross reference table.
     *
     * @param  Store $store The StoreOwner object to relate
     * @return User The current object (for fluent API support)
     */
    public function addOwnerStore(Store $store)
    {
        if ($this->collOwnerStores === null) {
            $this->initOwnerStores();
        }

        if (!$this->collOwnerStores->contains($store)) { // only add it if the **same** object is not already associated
            $this->doAddOwnerStore($store);
            $this->collOwnerStores[] = $store;

            if ($this->ownerStoresScheduledForDeletion and $this->ownerStoresScheduledForDeletion->contains($store)) {
                $this->ownerStoresScheduledForDeletion->remove($this->ownerStoresScheduledForDeletion->search($store));
            }
        }

        return $this;
    }

    /**
     * @param	OwnerStore $ownerStore The ownerStore object to add.
     */
    protected function doAddOwnerStore(Store $ownerStore)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$ownerStore->getOwners()->contains($this)) { $storeOwner = new StoreOwner();
            $storeOwner->setOwnerStore($ownerStore);
            $this->addStoreOwner($storeOwner);

            $foreignCollection = $ownerStore->getOwners();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Store object to this object
     * through the store_owner cross reference table.
     *
     * @param Store $store The StoreOwner object to relate
     * @return User The current object (for fluent API support)
     */
    public function removeOwnerStore(Store $store)
    {
        if ($this->getOwnerStores()->contains($store)) {
            $this->collOwnerStores->remove($this->collOwnerStores->search($store));
            if (null === $this->ownerStoresScheduledForDeletion) {
                $this->ownerStoresScheduledForDeletion = clone $this->collOwnerStores;
                $this->ownerStoresScheduledForDeletion->clear();
            }
            $this->ownerStoresScheduledForDeletion[]= $store;
        }

        return $this;
    }

    /**
     * Clears out the collRoles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
     * @see        addRoles()
     */
    public function clearRoles()
    {
        $this->collRoles = null; // important to set this to null since that means it is uninitialized
        $this->collRolesPartial = null;

        return $this;
    }

    /**
     * Initializes the collRoles collection.
     *
     * By default this just sets the collRoles collection to an empty collection (like clearRoles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initRoles()
    {
        $this->collRoles = new PropelObjectCollection();
        $this->collRoles->setModel('Role');
    }

    /**
     * Gets a collection of Role objects related by a many-to-many relationship
     * to the current object by way of the user_role cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Role[] List of Role objects
     */
    public function getRoles($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collRoles || null !== $criteria) {
            if ($this->isNew() && null === $this->collRoles) {
                // return empty collection
                $this->initRoles();
            } else {
                $collRoles = RoleQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collRoles;
                }
                $this->collRoles = $collRoles;
            }
        }

        return $this->collRoles;
    }

    /**
     * Sets a collection of Role objects related by a many-to-many relationship
     * to the current object by way of the user_role cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $roles A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
     */
    public function setRoles(PropelCollection $roles, PropelPDO $con = null)
    {
        $this->clearRoles();
        $currentRoles = $this->getRoles(null, $con);

        $this->rolesScheduledForDeletion = $currentRoles->diff($roles);

        foreach ($roles as $role) {
            if (!$currentRoles->contains($role)) {
                $this->doAddRole($role);
            }
        }

        $this->collRoles = $roles;

        return $this;
    }

    /**
     * Gets the number of Role objects related by a many-to-many relationship
     * to the current object by way of the user_role cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Role objects
     */
    public function countRoles($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collRoles || null !== $criteria) {
            if ($this->isNew() && null === $this->collRoles) {
                return 0;
            } else {
                $query = RoleQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collRoles);
        }
    }

    /**
     * Associate a Role object to this object
     * through the user_role cross reference table.
     *
     * @param  Role $role The UserRole object to relate
     * @return User The current object (for fluent API support)
     */
    public function addRole(Role $role)
    {
        if ($this->collRoles === null) {
            $this->initRoles();
        }

        if (!$this->collRoles->contains($role)) { // only add it if the **same** object is not already associated
            $this->doAddRole($role);
            $this->collRoles[] = $role;

            if ($this->rolesScheduledForDeletion and $this->rolesScheduledForDeletion->contains($role)) {
                $this->rolesScheduledForDeletion->remove($this->rolesScheduledForDeletion->search($role));
            }
        }

        return $this;
    }

    /**
     * @param	Role $role The role object to add.
     */
    protected function doAddRole(Role $role)
    {
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$role->getUsers()->contains($this)) { $userRole = new UserRole();
            $userRole->setRole($role);
            $this->addUserRole($userRole);

            $foreignCollection = $role->getUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Role object to this object
     * through the user_role cross reference table.
     *
     * @param Role $role The UserRole object to relate
     * @return User The current object (for fluent API support)
     */
    public function removeRole(Role $role)
    {
        if ($this->getRoles()->contains($role)) {
            $this->collRoles->remove($this->collRoles->search($role));
            if (null === $this->rolesScheduledForDeletion) {
                $this->rolesScheduledForDeletion = clone $this->collRoles;
                $this->rolesScheduledForDeletion->clear();
            }
            $this->rolesScheduledForDeletion[]= $role;
        }

        return $this;
    }

    /**
     * Clears out the collAddresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return User The current object (for fluent API support)
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
     * to the current object by way of the user_address cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
     * to the current object by way of the user_address cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $addresses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
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
     * to the current object by way of the user_address cross-reference table.
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
                    ->filterByUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collAddresses);
        }
    }

    /**
     * Associate a Address object to this object
     * through the user_address cross reference table.
     *
     * @param  Address $address The UserAddress object to relate
     * @return User The current object (for fluent API support)
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
        if (!$address->getUsers()->contains($this)) { $userAddress = new UserAddress();
            $userAddress->setAddress($address);
            $this->addUserAddress($userAddress);

            $foreignCollection = $address->getUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Address object to this object
     * through the user_address cross reference table.
     *
     * @param Address $address The UserAddress object to relate
     * @return User The current object (for fluent API support)
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
     * @return User The current object (for fluent API support)
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
     * to the current object by way of the user_email cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
     * to the current object by way of the user_email cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $emails A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
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
     * to the current object by way of the user_email cross-reference table.
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
                    ->filterByUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collEmails);
        }
    }

    /**
     * Associate a Email object to this object
     * through the user_email cross reference table.
     *
     * @param  Email $email The UserEmail object to relate
     * @return User The current object (for fluent API support)
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
        if (!$email->getUsers()->contains($this)) { $userEmail = new UserEmail();
            $userEmail->setEmail($email);
            $this->addUserEmail($userEmail);

            $foreignCollection = $email->getUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Email object to this object
     * through the user_email cross reference table.
     *
     * @param Email $email The UserEmail object to relate
     * @return User The current object (for fluent API support)
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
     * @return User The current object (for fluent API support)
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
     * to the current object by way of the user_phone cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
     * to the current object by way of the user_phone cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $phones A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return User The current object (for fluent API support)
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
     * to the current object by way of the user_phone cross-reference table.
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
                    ->filterByUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collPhones);
        }
    }

    /**
     * Associate a Phone object to this object
     * through the user_phone cross reference table.
     *
     * @param  Phone $phone The UserPhone object to relate
     * @return User The current object (for fluent API support)
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
        if (!$phone->getUsers()->contains($this)) { $userPhone = new UserPhone();
            $userPhone->setPhone($phone);
            $this->addUserPhone($userPhone);

            $foreignCollection = $phone->getUsers();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Phone object to this object
     * through the user_phone cross reference table.
     *
     * @param Phone $phone The UserPhone object to relate
     * @return User The current object (for fluent API support)
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
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->username = null;
        $this->firstname = null;
        $this->middlename = null;
        $this->lastname = null;
        $this->gender = null;
        $this->title = null;
        $this->birth_date = null;
        $this->password = null;
        $this->secret = null;
        $this->logins = null;
        $this->country = null;
        $this->language = null;
        $this->is_enabled = null;
        $this->is_external = null;
        $this->is_locked = null;
        $this->is_expired = null;
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
            if ($this->collCollectionsRelatedByCreatedBy) {
                foreach ($this->collCollectionsRelatedByCreatedBy as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCollectionsRelatedByEditedBy) {
                foreach ($this->collCollectionsRelatedByEditedBy as $o) {
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
            if ($this->collDsTemperatureNotifications) {
                foreach ($this->collDsTemperatureNotifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCbInputNotifications) {
                foreach ($this->collCbInputNotifications as $o) {
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
            if ($this->collUserRoles) {
                foreach ($this->collUserRoles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserAddresses) {
                foreach ($this->collUserAddresses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserEmails) {
                foreach ($this->collUserEmails as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserPhones) {
                foreach ($this->collUserPhones as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collContactCompanies) {
                foreach ($this->collContactCompanies as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInformantCompanies) {
                foreach ($this->collInformantCompanies as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOwnerCompanies) {
                foreach ($this->collOwnerCompanies as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collContactStores) {
                foreach ($this->collContactStores as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInformantStores) {
                foreach ($this->collInformantStores as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOwnerStores) {
                foreach ($this->collOwnerStores as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRoles) {
                foreach ($this->collRoles as $o) {
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
            if ($this->aUserGender instanceof Persistent) {
              $this->aUserGender->clearAllReferences($deep);
            }
            if ($this->aUserTitle instanceof Persistent) {
              $this->aUserTitle->clearAllReferences($deep);
            }
            if ($this->aCountriesRelatedByCountry instanceof Persistent) {
              $this->aCountriesRelatedByCountry->clearAllReferences($deep);
            }
            if ($this->aCountriesRelatedByLanguage instanceof Persistent) {
              $this->aCountriesRelatedByLanguage->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collCollectionsRelatedByCreatedBy instanceof PropelCollection) {
            $this->collCollectionsRelatedByCreatedBy->clearIterator();
        }
        $this->collCollectionsRelatedByCreatedBy = null;
        if ($this->collCollectionsRelatedByEditedBy instanceof PropelCollection) {
            $this->collCollectionsRelatedByEditedBy->clearIterator();
        }
        $this->collCollectionsRelatedByEditedBy = null;
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
        if ($this->collDsTemperatureNotifications instanceof PropelCollection) {
            $this->collDsTemperatureNotifications->clearIterator();
        }
        $this->collDsTemperatureNotifications = null;
        if ($this->collCbInputNotifications instanceof PropelCollection) {
            $this->collCbInputNotifications->clearIterator();
        }
        $this->collCbInputNotifications = null;
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
        if ($this->collUserRoles instanceof PropelCollection) {
            $this->collUserRoles->clearIterator();
        }
        $this->collUserRoles = null;
        if ($this->collUserAddresses instanceof PropelCollection) {
            $this->collUserAddresses->clearIterator();
        }
        $this->collUserAddresses = null;
        if ($this->collUserEmails instanceof PropelCollection) {
            $this->collUserEmails->clearIterator();
        }
        $this->collUserEmails = null;
        if ($this->collUserPhones instanceof PropelCollection) {
            $this->collUserPhones->clearIterator();
        }
        $this->collUserPhones = null;
        if ($this->collContactCompanies instanceof PropelCollection) {
            $this->collContactCompanies->clearIterator();
        }
        $this->collContactCompanies = null;
        if ($this->collInformantCompanies instanceof PropelCollection) {
            $this->collInformantCompanies->clearIterator();
        }
        $this->collInformantCompanies = null;
        if ($this->collOwnerCompanies instanceof PropelCollection) {
            $this->collOwnerCompanies->clearIterator();
        }
        $this->collOwnerCompanies = null;
        if ($this->collContactStores instanceof PropelCollection) {
            $this->collContactStores->clearIterator();
        }
        $this->collContactStores = null;
        if ($this->collInformantStores instanceof PropelCollection) {
            $this->collInformantStores->clearIterator();
        }
        $this->collInformantStores = null;
        if ($this->collOwnerStores instanceof PropelCollection) {
            $this->collOwnerStores->clearIterator();
        }
        $this->collOwnerStores = null;
        if ($this->collRoles instanceof PropelCollection) {
            $this->collRoles->clearIterator();
        }
        $this->collRoles = null;
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
        $this->aUserGender = null;
        $this->aUserTitle = null;
        $this->aCountriesRelatedByCountry = null;
        $this->aCountriesRelatedByLanguage = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserPeer::DEFAULT_STRING_FORMAT);
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
     * @return     User The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = UserPeer::UPDATED_AT;

        return $this;
    }

}
