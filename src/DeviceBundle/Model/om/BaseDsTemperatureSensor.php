<?php

namespace DeviceBundle\Model\om;

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
use DeviceBundle\Model\ControllerBox;
use DeviceBundle\Model\ControllerBoxQuery;
use DeviceBundle\Model\DeviceCopy;
use DeviceBundle\Model\DeviceCopyQuery;
use DeviceBundle\Model\DeviceGroup;
use DeviceBundle\Model\DeviceGroupQuery;
use DeviceBundle\Model\DsTemperatureSensor;
use DeviceBundle\Model\DsTemperatureSensorLog;
use DeviceBundle\Model\DsTemperatureSensorLogQuery;
use DeviceBundle\Model\DsTemperatureSensorPeer;
use DeviceBundle\Model\DsTemperatureSensorQuery;
use NotificationBundle\Model\DsTemperatureNotification;
use NotificationBundle\Model\DsTemperatureNotificationQuery;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreQuery;

abstract class BaseDsTemperatureSensor extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DeviceBundle\\Model\\DsTemperatureSensorPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        DsTemperatureSensorPeer
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
     * The value for the group field.
     * @var        int
     */
    protected $group;

    /**
     * The value for the controller field.
     * @var        int
     */
    protected $controller;

    /**
     * The value for the main_store field.
     * @var        int
     */
    protected $main_store;

    /**
     * The value for the output_number field.
     * @var        int
     */
    protected $output_number;

    /**
     * The value for the name field.
     * Note: this column has a database default value of: 'Temperature'
     * @var        string
     */
    protected $name;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the state field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $state;

    /**
     * The value for the low_limit field.
     * Note: this column has a database default value of: '0'
     * @var        string
     */
    protected $low_limit;

    /**
     * The value for the temperature field.
     * Note: this column has a database default value of: '0'
     * @var        string
     */
    protected $temperature;

    /**
     * The value for the high_limit field.
     * Note: this column has a database default value of: '30'
     * @var        string
     */
    protected $high_limit;

    /**
     * The value for the position field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $position;

    /**
     * The value for the data_collected_at field.
     * @var        string
     */
    protected $data_collected_at;

    /**
     * The value for the notify_after field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $notify_after;

    /**
     * The value for the notify_started_at field.
     * @var        string
     */
    protected $notify_started_at;

    /**
     * The value for the is_enabled field.
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $is_enabled;

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
     * @var        Store
     */
    protected $aStore;

    /**
     * @var        DeviceGroup
     */
    protected $aDeviceGroup;

    /**
     * @var        ControllerBox
     */
    protected $aControllerBox;

    /**
     * @var        PropelObjectCollection|DsTemperatureSensorLog[] Collection to store aggregation of DsTemperatureSensorLog objects.
     */
    protected $collDsTemperatureSensorLogs;
    protected $collDsTemperatureSensorLogsPartial;

    /**
     * @var        PropelObjectCollection|DeviceCopy[] Collection to store aggregation of DeviceCopy objects.
     */
    protected $collDeviceCopies;
    protected $collDeviceCopiesPartial;

    /**
     * @var        PropelObjectCollection|DsTemperatureNotification[] Collection to store aggregation of DsTemperatureNotification objects.
     */
    protected $collDsTemperatureNotifications;
    protected $collDsTemperatureNotificationsPartial;

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
    protected $dsTemperatureSensorLogsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $deviceCopiesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $dsTemperatureNotificationsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->name = 'Temperature';
        $this->state = 0;
        $this->low_limit = '0';
        $this->temperature = '0';
        $this->high_limit = '30';
        $this->position = 0;
        $this->notify_after = 0;
        $this->is_enabled = true;
    }

    /**
     * Initializes internal state of BaseDsTemperatureSensor object.
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
     * Get the [group] column value.
     *
     * @return int
     */
    public function getGroup()
    {

        return $this->group;
    }

    /**
     * Get the [controller] column value.
     *
     * @return int
     */
    public function getController()
    {

        return $this->controller;
    }

    /**
     * Get the [main_store] column value.
     *
     * @return int
     */
    public function getMainStore()
    {

        return $this->main_store;
    }

    /**
     * Get the [output_number] column value.
     *
     * @return int
     */
    public function getOutputNumber()
    {

        return $this->output_number;
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
     * Get the [state] column value.
     *
     * @return int
     */
    public function getState()
    {

        return $this->state;
    }

    /**
     * Get the [low_limit] column value.
     *
     * @return string
     */
    public function getLowLimit()
    {

        return $this->low_limit;
    }

    /**
     * Get the [temperature] column value.
     *
     * @return string
     */
    public function getTemperature()
    {

        return $this->temperature;
    }

    /**
     * Get the [high_limit] column value.
     *
     * @return string
     */
    public function getHighLimit()
    {

        return $this->high_limit;
    }

    /**
     * Get the [position] column value.
     *
     * @return int
     */
    public function getPosition()
    {

        return $this->position;
    }

    /**
     * Get the [optionally formatted] temporal [data_collected_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDataCollectedAt($format = null)
    {
        if ($this->data_collected_at === null) {
            return null;
        }

        if ($this->data_collected_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->data_collected_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->data_collected_at, true), $x);
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
     * Get the [notify_after] column value.
     *
     * @return int
     */
    public function getNotifyAfter()
    {

        return $this->notify_after;
    }

    /**
     * Get the [optionally formatted] temporal [notify_started_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getNotifyStartedAt($format = null)
    {
        if ($this->notify_started_at === null) {
            return null;
        }

        if ($this->notify_started_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->notify_started_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->notify_started_at, true), $x);
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
     * Get the [is_enabled] column value.
     *
     * @return boolean
     */
    public function getIsEnabled()
    {

        return $this->is_enabled;
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
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [uid] column.
     *
     * @param  string $v new value
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setUid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uid !== $v) {
            $this->uid = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::UID;
        }


        return $this;
    } // setUid()

    /**
     * Set the value of [group] column.
     *
     * @param  int $v new value
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setGroup($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->group !== $v) {
            $this->group = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::GROUP;
        }

        if ($this->aDeviceGroup !== null && $this->aDeviceGroup->getId() !== $v) {
            $this->aDeviceGroup = null;
        }


        return $this;
    } // setGroup()

    /**
     * Set the value of [controller] column.
     *
     * @param  int $v new value
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setController($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->controller !== $v) {
            $this->controller = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::CONTROLLER;
        }

        if ($this->aControllerBox !== null && $this->aControllerBox->getId() !== $v) {
            $this->aControllerBox = null;
        }


        return $this;
    } // setController()

    /**
     * Set the value of [main_store] column.
     *
     * @param  int $v new value
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setMainStore($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->main_store !== $v) {
            $this->main_store = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::MAIN_STORE;
        }

        if ($this->aStore !== null && $this->aStore->getId() !== $v) {
            $this->aStore = null;
        }


        return $this;
    } // setMainStore()

    /**
     * Set the value of [output_number] column.
     *
     * @param  int $v new value
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setOutputNumber($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->output_number !== $v) {
            $this->output_number = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::OUTPUT_NUMBER;
        }


        return $this;
    } // setOutputNumber()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [state] column.
     *
     * @param  int $v new value
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setState($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->state !== $v) {
            $this->state = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::STATE;
        }


        return $this;
    } // setState()

    /**
     * Set the value of [low_limit] column.
     *
     * @param  string $v new value
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setLowLimit($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->low_limit !== $v) {
            $this->low_limit = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::LOW_LIMIT;
        }


        return $this;
    } // setLowLimit()

    /**
     * Set the value of [temperature] column.
     *
     * @param  string $v new value
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setTemperature($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->temperature !== $v) {
            $this->temperature = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::TEMPERATURE;
        }


        return $this;
    } // setTemperature()

    /**
     * Set the value of [high_limit] column.
     *
     * @param  string $v new value
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setHighLimit($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->high_limit !== $v) {
            $this->high_limit = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::HIGH_LIMIT;
        }


        return $this;
    } // setHighLimit()

    /**
     * Set the value of [position] column.
     *
     * @param  int $v new value
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setPosition($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->position !== $v) {
            $this->position = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::POSITION;
        }


        return $this;
    } // setPosition()

    /**
     * Sets the value of [data_collected_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setDataCollectedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->data_collected_at !== null || $dt !== null) {
            $currentDateAsString = ($this->data_collected_at !== null && $tmpDt = new DateTime($this->data_collected_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->data_collected_at = $newDateAsString;
                $this->modifiedColumns[] = DsTemperatureSensorPeer::DATA_COLLECTED_AT;
            }
        } // if either are not null


        return $this;
    } // setDataCollectedAt()

    /**
     * Set the value of [notify_after] column.
     *
     * @param  int $v new value
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setNotifyAfter($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->notify_after !== $v) {
            $this->notify_after = $v;
            $this->modifiedColumns[] = DsTemperatureSensorPeer::NOTIFY_AFTER;
        }


        return $this;
    } // setNotifyAfter()

    /**
     * Sets the value of [notify_started_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setNotifyStartedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->notify_started_at !== null || $dt !== null) {
            $currentDateAsString = ($this->notify_started_at !== null && $tmpDt = new DateTime($this->notify_started_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->notify_started_at = $newDateAsString;
                $this->modifiedColumns[] = DsTemperatureSensorPeer::NOTIFY_STARTED_AT;
            }
        } // if either are not null


        return $this;
    } // setNotifyStartedAt()

    /**
     * Sets the value of the [is_enabled] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return DsTemperatureSensor The current object (for fluent API support)
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
            $this->modifiedColumns[] = DsTemperatureSensorPeer::IS_ENABLED;
        }


        return $this;
    } // setIsEnabled()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = DsTemperatureSensorPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = DsTemperatureSensorPeer::UPDATED_AT;
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
            if ($this->name !== 'Temperature') {
                return false;
            }

            if ($this->state !== 0) {
                return false;
            }

            if ($this->low_limit !== '0') {
                return false;
            }

            if ($this->temperature !== '0') {
                return false;
            }

            if ($this->high_limit !== '30') {
                return false;
            }

            if ($this->position !== 0) {
                return false;
            }

            if ($this->notify_after !== 0) {
                return false;
            }

            if ($this->is_enabled !== true) {
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
            $this->group = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->controller = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->main_store = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->output_number = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->name = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->description = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->state = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->low_limit = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->temperature = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->high_limit = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->position = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->data_collected_at = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->notify_after = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->notify_started_at = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->is_enabled = ($row[$startcol + 16] !== null) ? (boolean) $row[$startcol + 16] : null;
            $this->created_at = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->updated_at = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 19; // 19 = DsTemperatureSensorPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating DsTemperatureSensor object", $e);
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

        if ($this->aDeviceGroup !== null && $this->group !== $this->aDeviceGroup->getId()) {
            $this->aDeviceGroup = null;
        }
        if ($this->aControllerBox !== null && $this->controller !== $this->aControllerBox->getId()) {
            $this->aControllerBox = null;
        }
        if ($this->aStore !== null && $this->main_store !== $this->aStore->getId()) {
            $this->aStore = null;
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
            $con = Propel::getConnection(DsTemperatureSensorPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = DsTemperatureSensorPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aStore = null;
            $this->aDeviceGroup = null;
            $this->aControllerBox = null;
            $this->collDsTemperatureSensorLogs = null;

            $this->collDeviceCopies = null;

            $this->collDsTemperatureNotifications = null;

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
            $con = Propel::getConnection(DsTemperatureSensorPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = DsTemperatureSensorQuery::create()
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
            $con = Propel::getConnection(DsTemperatureSensorPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(DsTemperatureSensorPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(DsTemperatureSensorPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(DsTemperatureSensorPeer::UPDATED_AT)) {
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
                DsTemperatureSensorPeer::addInstanceToPool($this);
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

            if ($this->aStore !== null) {
                if ($this->aStore->isModified() || $this->aStore->isNew()) {
                    $affectedRows += $this->aStore->save($con);
                }
                $this->setStore($this->aStore);
            }

            if ($this->aDeviceGroup !== null) {
                if ($this->aDeviceGroup->isModified() || $this->aDeviceGroup->isNew()) {
                    $affectedRows += $this->aDeviceGroup->save($con);
                }
                $this->setDeviceGroup($this->aDeviceGroup);
            }

            if ($this->aControllerBox !== null) {
                if ($this->aControllerBox->isModified() || $this->aControllerBox->isNew()) {
                    $affectedRows += $this->aControllerBox->save($con);
                }
                $this->setControllerBox($this->aControllerBox);
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

            if ($this->dsTemperatureSensorLogsScheduledForDeletion !== null) {
                if (!$this->dsTemperatureSensorLogsScheduledForDeletion->isEmpty()) {
                    foreach ($this->dsTemperatureSensorLogsScheduledForDeletion as $dsTemperatureSensorLog) {
                        // need to save related object because we set the relation to null
                        $dsTemperatureSensorLog->save($con);
                    }
                    $this->dsTemperatureSensorLogsScheduledForDeletion = null;
                }
            }

            if ($this->collDsTemperatureSensorLogs !== null) {
                foreach ($this->collDsTemperatureSensorLogs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->deviceCopiesScheduledForDeletion !== null) {
                if (!$this->deviceCopiesScheduledForDeletion->isEmpty()) {
                    foreach ($this->deviceCopiesScheduledForDeletion as $deviceCopy) {
                        // need to save related object because we set the relation to null
                        $deviceCopy->save($con);
                    }
                    $this->deviceCopiesScheduledForDeletion = null;
                }
            }

            if ($this->collDeviceCopies !== null) {
                foreach ($this->collDeviceCopies as $referrerFK) {
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

        $this->modifiedColumns[] = DsTemperatureSensorPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DsTemperatureSensorPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DsTemperatureSensorPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::UID)) {
            $modifiedColumns[':p' . $index++]  = '`uid`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::GROUP)) {
            $modifiedColumns[':p' . $index++]  = '`group`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::CONTROLLER)) {
            $modifiedColumns[':p' . $index++]  = '`controller`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::MAIN_STORE)) {
            $modifiedColumns[':p' . $index++]  = '`main_store`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::OUTPUT_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = '`output_number`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::STATE)) {
            $modifiedColumns[':p' . $index++]  = '`state`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::LOW_LIMIT)) {
            $modifiedColumns[':p' . $index++]  = '`low_limit`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::TEMPERATURE)) {
            $modifiedColumns[':p' . $index++]  = '`temperature`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::HIGH_LIMIT)) {
            $modifiedColumns[':p' . $index++]  = '`high_limit`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::POSITION)) {
            $modifiedColumns[':p' . $index++]  = '`position`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::DATA_COLLECTED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`data_collected_at`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::NOTIFY_AFTER)) {
            $modifiedColumns[':p' . $index++]  = '`notify_after`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::NOTIFY_STARTED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`notify_started_at`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::IS_ENABLED)) {
            $modifiedColumns[':p' . $index++]  = '`is_enabled`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(DsTemperatureSensorPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `ds_temperature_sensor` (%s) VALUES (%s)',
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
                    case '`group`':
                        $stmt->bindValue($identifier, $this->group, PDO::PARAM_INT);
                        break;
                    case '`controller`':
                        $stmt->bindValue($identifier, $this->controller, PDO::PARAM_INT);
                        break;
                    case '`main_store`':
                        $stmt->bindValue($identifier, $this->main_store, PDO::PARAM_INT);
                        break;
                    case '`output_number`':
                        $stmt->bindValue($identifier, $this->output_number, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`description`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`state`':
                        $stmt->bindValue($identifier, $this->state, PDO::PARAM_INT);
                        break;
                    case '`low_limit`':
                        $stmt->bindValue($identifier, $this->low_limit, PDO::PARAM_STR);
                        break;
                    case '`temperature`':
                        $stmt->bindValue($identifier, $this->temperature, PDO::PARAM_STR);
                        break;
                    case '`high_limit`':
                        $stmt->bindValue($identifier, $this->high_limit, PDO::PARAM_STR);
                        break;
                    case '`position`':
                        $stmt->bindValue($identifier, $this->position, PDO::PARAM_INT);
                        break;
                    case '`data_collected_at`':
                        $stmt->bindValue($identifier, $this->data_collected_at, PDO::PARAM_STR);
                        break;
                    case '`notify_after`':
                        $stmt->bindValue($identifier, $this->notify_after, PDO::PARAM_INT);
                        break;
                    case '`notify_started_at`':
                        $stmt->bindValue($identifier, $this->notify_started_at, PDO::PARAM_STR);
                        break;
                    case '`is_enabled`':
                        $stmt->bindValue($identifier, (int) $this->is_enabled, PDO::PARAM_INT);
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

            if ($this->aStore !== null) {
                if (!$this->aStore->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aStore->getValidationFailures());
                }
            }

            if ($this->aDeviceGroup !== null) {
                if (!$this->aDeviceGroup->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDeviceGroup->getValidationFailures());
                }
            }

            if ($this->aControllerBox !== null) {
                if (!$this->aControllerBox->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aControllerBox->getValidationFailures());
                }
            }


            if (($retval = DsTemperatureSensorPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collDsTemperatureSensorLogs !== null) {
                    foreach ($this->collDsTemperatureSensorLogs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collDeviceCopies !== null) {
                    foreach ($this->collDeviceCopies as $referrerFK) {
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
        $pos = DsTemperatureSensorPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getGroup();
                break;
            case 3:
                return $this->getController();
                break;
            case 4:
                return $this->getMainStore();
                break;
            case 5:
                return $this->getOutputNumber();
                break;
            case 6:
                return $this->getName();
                break;
            case 7:
                return $this->getDescription();
                break;
            case 8:
                return $this->getState();
                break;
            case 9:
                return $this->getLowLimit();
                break;
            case 10:
                return $this->getTemperature();
                break;
            case 11:
                return $this->getHighLimit();
                break;
            case 12:
                return $this->getPosition();
                break;
            case 13:
                return $this->getDataCollectedAt();
                break;
            case 14:
                return $this->getNotifyAfter();
                break;
            case 15:
                return $this->getNotifyStartedAt();
                break;
            case 16:
                return $this->getIsEnabled();
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
        if (isset($alreadyDumpedObjects['DsTemperatureSensor'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['DsTemperatureSensor'][$this->getPrimaryKey()] = true;
        $keys = DsTemperatureSensorPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUid(),
            $keys[2] => $this->getGroup(),
            $keys[3] => $this->getController(),
            $keys[4] => $this->getMainStore(),
            $keys[5] => $this->getOutputNumber(),
            $keys[6] => $this->getName(),
            $keys[7] => $this->getDescription(),
            $keys[8] => $this->getState(),
            $keys[9] => $this->getLowLimit(),
            $keys[10] => $this->getTemperature(),
            $keys[11] => $this->getHighLimit(),
            $keys[12] => $this->getPosition(),
            $keys[13] => $this->getDataCollectedAt(),
            $keys[14] => $this->getNotifyAfter(),
            $keys[15] => $this->getNotifyStartedAt(),
            $keys[16] => $this->getIsEnabled(),
            $keys[17] => $this->getCreatedAt(),
            $keys[18] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aStore) {
                $result['Store'] = $this->aStore->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDeviceGroup) {
                $result['DeviceGroup'] = $this->aDeviceGroup->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aControllerBox) {
                $result['ControllerBox'] = $this->aControllerBox->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collDsTemperatureSensorLogs) {
                $result['DsTemperatureSensorLogs'] = $this->collDsTemperatureSensorLogs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDeviceCopies) {
                $result['DeviceCopies'] = $this->collDeviceCopies->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDsTemperatureNotifications) {
                $result['DsTemperatureNotifications'] = $this->collDsTemperatureNotifications->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = DsTemperatureSensorPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setGroup($value);
                break;
            case 3:
                $this->setController($value);
                break;
            case 4:
                $this->setMainStore($value);
                break;
            case 5:
                $this->setOutputNumber($value);
                break;
            case 6:
                $this->setName($value);
                break;
            case 7:
                $this->setDescription($value);
                break;
            case 8:
                $this->setState($value);
                break;
            case 9:
                $this->setLowLimit($value);
                break;
            case 10:
                $this->setTemperature($value);
                break;
            case 11:
                $this->setHighLimit($value);
                break;
            case 12:
                $this->setPosition($value);
                break;
            case 13:
                $this->setDataCollectedAt($value);
                break;
            case 14:
                $this->setNotifyAfter($value);
                break;
            case 15:
                $this->setNotifyStartedAt($value);
                break;
            case 16:
                $this->setIsEnabled($value);
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
        $keys = DsTemperatureSensorPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setGroup($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setController($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setMainStore($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setOutputNumber($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setName($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setDescription($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setState($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setLowLimit($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setTemperature($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setHighLimit($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setPosition($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setDataCollectedAt($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setNotifyAfter($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setNotifyStartedAt($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setIsEnabled($arr[$keys[16]]);
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
        $criteria = new Criteria(DsTemperatureSensorPeer::DATABASE_NAME);

        if ($this->isColumnModified(DsTemperatureSensorPeer::ID)) $criteria->add(DsTemperatureSensorPeer::ID, $this->id);
        if ($this->isColumnModified(DsTemperatureSensorPeer::UID)) $criteria->add(DsTemperatureSensorPeer::UID, $this->uid);
        if ($this->isColumnModified(DsTemperatureSensorPeer::GROUP)) $criteria->add(DsTemperatureSensorPeer::GROUP, $this->group);
        if ($this->isColumnModified(DsTemperatureSensorPeer::CONTROLLER)) $criteria->add(DsTemperatureSensorPeer::CONTROLLER, $this->controller);
        if ($this->isColumnModified(DsTemperatureSensorPeer::MAIN_STORE)) $criteria->add(DsTemperatureSensorPeer::MAIN_STORE, $this->main_store);
        if ($this->isColumnModified(DsTemperatureSensorPeer::OUTPUT_NUMBER)) $criteria->add(DsTemperatureSensorPeer::OUTPUT_NUMBER, $this->output_number);
        if ($this->isColumnModified(DsTemperatureSensorPeer::NAME)) $criteria->add(DsTemperatureSensorPeer::NAME, $this->name);
        if ($this->isColumnModified(DsTemperatureSensorPeer::DESCRIPTION)) $criteria->add(DsTemperatureSensorPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(DsTemperatureSensorPeer::STATE)) $criteria->add(DsTemperatureSensorPeer::STATE, $this->state);
        if ($this->isColumnModified(DsTemperatureSensorPeer::LOW_LIMIT)) $criteria->add(DsTemperatureSensorPeer::LOW_LIMIT, $this->low_limit);
        if ($this->isColumnModified(DsTemperatureSensorPeer::TEMPERATURE)) $criteria->add(DsTemperatureSensorPeer::TEMPERATURE, $this->temperature);
        if ($this->isColumnModified(DsTemperatureSensorPeer::HIGH_LIMIT)) $criteria->add(DsTemperatureSensorPeer::HIGH_LIMIT, $this->high_limit);
        if ($this->isColumnModified(DsTemperatureSensorPeer::POSITION)) $criteria->add(DsTemperatureSensorPeer::POSITION, $this->position);
        if ($this->isColumnModified(DsTemperatureSensorPeer::DATA_COLLECTED_AT)) $criteria->add(DsTemperatureSensorPeer::DATA_COLLECTED_AT, $this->data_collected_at);
        if ($this->isColumnModified(DsTemperatureSensorPeer::NOTIFY_AFTER)) $criteria->add(DsTemperatureSensorPeer::NOTIFY_AFTER, $this->notify_after);
        if ($this->isColumnModified(DsTemperatureSensorPeer::NOTIFY_STARTED_AT)) $criteria->add(DsTemperatureSensorPeer::NOTIFY_STARTED_AT, $this->notify_started_at);
        if ($this->isColumnModified(DsTemperatureSensorPeer::IS_ENABLED)) $criteria->add(DsTemperatureSensorPeer::IS_ENABLED, $this->is_enabled);
        if ($this->isColumnModified(DsTemperatureSensorPeer::CREATED_AT)) $criteria->add(DsTemperatureSensorPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(DsTemperatureSensorPeer::UPDATED_AT)) $criteria->add(DsTemperatureSensorPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(DsTemperatureSensorPeer::DATABASE_NAME);
        $criteria->add(DsTemperatureSensorPeer::ID, $this->id);

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
     * @param object $copyObj An object of DsTemperatureSensor (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUid($this->getUid());
        $copyObj->setGroup($this->getGroup());
        $copyObj->setController($this->getController());
        $copyObj->setMainStore($this->getMainStore());
        $copyObj->setOutputNumber($this->getOutputNumber());
        $copyObj->setName($this->getName());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setState($this->getState());
        $copyObj->setLowLimit($this->getLowLimit());
        $copyObj->setTemperature($this->getTemperature());
        $copyObj->setHighLimit($this->getHighLimit());
        $copyObj->setPosition($this->getPosition());
        $copyObj->setDataCollectedAt($this->getDataCollectedAt());
        $copyObj->setNotifyAfter($this->getNotifyAfter());
        $copyObj->setNotifyStartedAt($this->getNotifyStartedAt());
        $copyObj->setIsEnabled($this->getIsEnabled());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getDsTemperatureSensorLogs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDsTemperatureSensorLog($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDeviceCopies() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDeviceCopy($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDsTemperatureNotifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDsTemperatureNotification($relObj->copy($deepCopy));
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
     * @return DsTemperatureSensor Clone of current object.
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
     * @return DsTemperatureSensorPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new DsTemperatureSensorPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Store object.
     *
     * @param                  Store $v
     * @return DsTemperatureSensor The current object (for fluent API support)
     * @throws PropelException
     */
    public function setStore(Store $v = null)
    {
        if ($v === null) {
            $this->setMainStore(NULL);
        } else {
            $this->setMainStore($v->getId());
        }

        $this->aStore = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Store object, it will not be re-added.
        if ($v !== null) {
            $v->addDsTemperatureSensor($this);
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
        if ($this->aStore === null && ($this->main_store !== null) && $doQuery) {
            $this->aStore = StoreQuery::create()->findPk($this->main_store, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aStore->addDsTemperatureSensors($this);
             */
        }

        return $this->aStore;
    }

    /**
     * Declares an association between this object and a DeviceGroup object.
     *
     * @param                  DeviceGroup $v
     * @return DsTemperatureSensor The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDeviceGroup(DeviceGroup $v = null)
    {
        if ($v === null) {
            $this->setGroup(NULL);
        } else {
            $this->setGroup($v->getId());
        }

        $this->aDeviceGroup = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the DeviceGroup object, it will not be re-added.
        if ($v !== null) {
            $v->addDsTemperatureSensor($this);
        }


        return $this;
    }


    /**
     * Get the associated DeviceGroup object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return DeviceGroup The associated DeviceGroup object.
     * @throws PropelException
     */
    public function getDeviceGroup(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDeviceGroup === null && ($this->group !== null) && $doQuery) {
            $this->aDeviceGroup = DeviceGroupQuery::create()->findPk($this->group, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDeviceGroup->addDsTemperatureSensors($this);
             */
        }

        return $this->aDeviceGroup;
    }

    /**
     * Declares an association between this object and a ControllerBox object.
     *
     * @param                  ControllerBox $v
     * @return DsTemperatureSensor The current object (for fluent API support)
     * @throws PropelException
     */
    public function setControllerBox(ControllerBox $v = null)
    {
        if ($v === null) {
            $this->setController(NULL);
        } else {
            $this->setController($v->getId());
        }

        $this->aControllerBox = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ControllerBox object, it will not be re-added.
        if ($v !== null) {
            $v->addDsTemperatureSensor($this);
        }


        return $this;
    }


    /**
     * Get the associated ControllerBox object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return ControllerBox The associated ControllerBox object.
     * @throws PropelException
     */
    public function getControllerBox(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aControllerBox === null && ($this->controller !== null) && $doQuery) {
            $this->aControllerBox = ControllerBoxQuery::create()->findPk($this->controller, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aControllerBox->addDsTemperatureSensors($this);
             */
        }

        return $this->aControllerBox;
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
        if ('DsTemperatureSensorLog' == $relationName) {
            $this->initDsTemperatureSensorLogs();
        }
        if ('DeviceCopy' == $relationName) {
            $this->initDeviceCopies();
        }
        if ('DsTemperatureNotification' == $relationName) {
            $this->initDsTemperatureNotifications();
        }
    }

    /**
     * Clears out the collDsTemperatureSensorLogs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return DsTemperatureSensor The current object (for fluent API support)
     * @see        addDsTemperatureSensorLogs()
     */
    public function clearDsTemperatureSensorLogs()
    {
        $this->collDsTemperatureSensorLogs = null; // important to set this to null since that means it is uninitialized
        $this->collDsTemperatureSensorLogsPartial = null;

        return $this;
    }

    /**
     * reset is the collDsTemperatureSensorLogs collection loaded partially
     *
     * @return void
     */
    public function resetPartialDsTemperatureSensorLogs($v = true)
    {
        $this->collDsTemperatureSensorLogsPartial = $v;
    }

    /**
     * Initializes the collDsTemperatureSensorLogs collection.
     *
     * By default this just sets the collDsTemperatureSensorLogs collection to an empty array (like clearcollDsTemperatureSensorLogs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDsTemperatureSensorLogs($overrideExisting = true)
    {
        if (null !== $this->collDsTemperatureSensorLogs && !$overrideExisting) {
            return;
        }
        $this->collDsTemperatureSensorLogs = new PropelObjectCollection();
        $this->collDsTemperatureSensorLogs->setModel('DsTemperatureSensorLog');
    }

    /**
     * Gets an array of DsTemperatureSensorLog objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this DsTemperatureSensor is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|DsTemperatureSensorLog[] List of DsTemperatureSensorLog objects
     * @throws PropelException
     */
    public function getDsTemperatureSensorLogs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collDsTemperatureSensorLogsPartial && !$this->isNew();
        if (null === $this->collDsTemperatureSensorLogs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDsTemperatureSensorLogs) {
                // return empty collection
                $this->initDsTemperatureSensorLogs();
            } else {
                $collDsTemperatureSensorLogs = DsTemperatureSensorLogQuery::create(null, $criteria)
                    ->filterByDsTemperatureSensor($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collDsTemperatureSensorLogsPartial && count($collDsTemperatureSensorLogs)) {
                      $this->initDsTemperatureSensorLogs(false);

                      foreach ($collDsTemperatureSensorLogs as $obj) {
                        if (false == $this->collDsTemperatureSensorLogs->contains($obj)) {
                          $this->collDsTemperatureSensorLogs->append($obj);
                        }
                      }

                      $this->collDsTemperatureSensorLogsPartial = true;
                    }

                    $collDsTemperatureSensorLogs->getInternalIterator()->rewind();

                    return $collDsTemperatureSensorLogs;
                }

                if ($partial && $this->collDsTemperatureSensorLogs) {
                    foreach ($this->collDsTemperatureSensorLogs as $obj) {
                        if ($obj->isNew()) {
                            $collDsTemperatureSensorLogs[] = $obj;
                        }
                    }
                }

                $this->collDsTemperatureSensorLogs = $collDsTemperatureSensorLogs;
                $this->collDsTemperatureSensorLogsPartial = false;
            }
        }

        return $this->collDsTemperatureSensorLogs;
    }

    /**
     * Sets a collection of DsTemperatureSensorLog objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $dsTemperatureSensorLogs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setDsTemperatureSensorLogs(PropelCollection $dsTemperatureSensorLogs, PropelPDO $con = null)
    {
        $dsTemperatureSensorLogsToDelete = $this->getDsTemperatureSensorLogs(new Criteria(), $con)->diff($dsTemperatureSensorLogs);


        $this->dsTemperatureSensorLogsScheduledForDeletion = $dsTemperatureSensorLogsToDelete;

        foreach ($dsTemperatureSensorLogsToDelete as $dsTemperatureSensorLogRemoved) {
            $dsTemperatureSensorLogRemoved->setDsTemperatureSensor(null);
        }

        $this->collDsTemperatureSensorLogs = null;
        foreach ($dsTemperatureSensorLogs as $dsTemperatureSensorLog) {
            $this->addDsTemperatureSensorLog($dsTemperatureSensorLog);
        }

        $this->collDsTemperatureSensorLogs = $dsTemperatureSensorLogs;
        $this->collDsTemperatureSensorLogsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related DsTemperatureSensorLog objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related DsTemperatureSensorLog objects.
     * @throws PropelException
     */
    public function countDsTemperatureSensorLogs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collDsTemperatureSensorLogsPartial && !$this->isNew();
        if (null === $this->collDsTemperatureSensorLogs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDsTemperatureSensorLogs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDsTemperatureSensorLogs());
            }
            $query = DsTemperatureSensorLogQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDsTemperatureSensor($this)
                ->count($con);
        }

        return count($this->collDsTemperatureSensorLogs);
    }

    /**
     * Method called to associate a DsTemperatureSensorLog object to this object
     * through the DsTemperatureSensorLog foreign key attribute.
     *
     * @param    DsTemperatureSensorLog $l DsTemperatureSensorLog
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function addDsTemperatureSensorLog(DsTemperatureSensorLog $l)
    {
        if ($this->collDsTemperatureSensorLogs === null) {
            $this->initDsTemperatureSensorLogs();
            $this->collDsTemperatureSensorLogsPartial = true;
        }

        if (!in_array($l, $this->collDsTemperatureSensorLogs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddDsTemperatureSensorLog($l);

            if ($this->dsTemperatureSensorLogsScheduledForDeletion and $this->dsTemperatureSensorLogsScheduledForDeletion->contains($l)) {
                $this->dsTemperatureSensorLogsScheduledForDeletion->remove($this->dsTemperatureSensorLogsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	DsTemperatureSensorLog $dsTemperatureSensorLog The dsTemperatureSensorLog object to add.
     */
    protected function doAddDsTemperatureSensorLog($dsTemperatureSensorLog)
    {
        $this->collDsTemperatureSensorLogs[]= $dsTemperatureSensorLog;
        $dsTemperatureSensorLog->setDsTemperatureSensor($this);
    }

    /**
     * @param	DsTemperatureSensorLog $dsTemperatureSensorLog The dsTemperatureSensorLog object to remove.
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function removeDsTemperatureSensorLog($dsTemperatureSensorLog)
    {
        if ($this->getDsTemperatureSensorLogs()->contains($dsTemperatureSensorLog)) {
            $this->collDsTemperatureSensorLogs->remove($this->collDsTemperatureSensorLogs->search($dsTemperatureSensorLog));
            if (null === $this->dsTemperatureSensorLogsScheduledForDeletion) {
                $this->dsTemperatureSensorLogsScheduledForDeletion = clone $this->collDsTemperatureSensorLogs;
                $this->dsTemperatureSensorLogsScheduledForDeletion->clear();
            }
            $this->dsTemperatureSensorLogsScheduledForDeletion[]= $dsTemperatureSensorLog;
            $dsTemperatureSensorLog->setDsTemperatureSensor(null);
        }

        return $this;
    }

    /**
     * Clears out the collDeviceCopies collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return DsTemperatureSensor The current object (for fluent API support)
     * @see        addDeviceCopies()
     */
    public function clearDeviceCopies()
    {
        $this->collDeviceCopies = null; // important to set this to null since that means it is uninitialized
        $this->collDeviceCopiesPartial = null;

        return $this;
    }

    /**
     * reset is the collDeviceCopies collection loaded partially
     *
     * @return void
     */
    public function resetPartialDeviceCopies($v = true)
    {
        $this->collDeviceCopiesPartial = $v;
    }

    /**
     * Initializes the collDeviceCopies collection.
     *
     * By default this just sets the collDeviceCopies collection to an empty array (like clearcollDeviceCopies());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDeviceCopies($overrideExisting = true)
    {
        if (null !== $this->collDeviceCopies && !$overrideExisting) {
            return;
        }
        $this->collDeviceCopies = new PropelObjectCollection();
        $this->collDeviceCopies->setModel('DeviceCopy');
    }

    /**
     * Gets an array of DeviceCopy objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this DsTemperatureSensor is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|DeviceCopy[] List of DeviceCopy objects
     * @throws PropelException
     */
    public function getDeviceCopies($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collDeviceCopiesPartial && !$this->isNew();
        if (null === $this->collDeviceCopies || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDeviceCopies) {
                // return empty collection
                $this->initDeviceCopies();
            } else {
                $collDeviceCopies = DeviceCopyQuery::create(null, $criteria)
                    ->filterByDsTemperatureSensor($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collDeviceCopiesPartial && count($collDeviceCopies)) {
                      $this->initDeviceCopies(false);

                      foreach ($collDeviceCopies as $obj) {
                        if (false == $this->collDeviceCopies->contains($obj)) {
                          $this->collDeviceCopies->append($obj);
                        }
                      }

                      $this->collDeviceCopiesPartial = true;
                    }

                    $collDeviceCopies->getInternalIterator()->rewind();

                    return $collDeviceCopies;
                }

                if ($partial && $this->collDeviceCopies) {
                    foreach ($this->collDeviceCopies as $obj) {
                        if ($obj->isNew()) {
                            $collDeviceCopies[] = $obj;
                        }
                    }
                }

                $this->collDeviceCopies = $collDeviceCopies;
                $this->collDeviceCopiesPartial = false;
            }
        }

        return $this->collDeviceCopies;
    }

    /**
     * Sets a collection of DeviceCopy objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $deviceCopies A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setDeviceCopies(PropelCollection $deviceCopies, PropelPDO $con = null)
    {
        $deviceCopiesToDelete = $this->getDeviceCopies(new Criteria(), $con)->diff($deviceCopies);


        $this->deviceCopiesScheduledForDeletion = $deviceCopiesToDelete;

        foreach ($deviceCopiesToDelete as $deviceCopyRemoved) {
            $deviceCopyRemoved->setDsTemperatureSensor(null);
        }

        $this->collDeviceCopies = null;
        foreach ($deviceCopies as $deviceCopy) {
            $this->addDeviceCopy($deviceCopy);
        }

        $this->collDeviceCopies = $deviceCopies;
        $this->collDeviceCopiesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related DeviceCopy objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related DeviceCopy objects.
     * @throws PropelException
     */
    public function countDeviceCopies(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collDeviceCopiesPartial && !$this->isNew();
        if (null === $this->collDeviceCopies || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDeviceCopies) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDeviceCopies());
            }
            $query = DeviceCopyQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDsTemperatureSensor($this)
                ->count($con);
        }

        return count($this->collDeviceCopies);
    }

    /**
     * Method called to associate a DeviceCopy object to this object
     * through the DeviceCopy foreign key attribute.
     *
     * @param    DeviceCopy $l DeviceCopy
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function addDeviceCopy(DeviceCopy $l)
    {
        if ($this->collDeviceCopies === null) {
            $this->initDeviceCopies();
            $this->collDeviceCopiesPartial = true;
        }

        if (!in_array($l, $this->collDeviceCopies->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddDeviceCopy($l);

            if ($this->deviceCopiesScheduledForDeletion and $this->deviceCopiesScheduledForDeletion->contains($l)) {
                $this->deviceCopiesScheduledForDeletion->remove($this->deviceCopiesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	DeviceCopy $deviceCopy The deviceCopy object to add.
     */
    protected function doAddDeviceCopy($deviceCopy)
    {
        $this->collDeviceCopies[]= $deviceCopy;
        $deviceCopy->setDsTemperatureSensor($this);
    }

    /**
     * @param	DeviceCopy $deviceCopy The deviceCopy object to remove.
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function removeDeviceCopy($deviceCopy)
    {
        if ($this->getDeviceCopies()->contains($deviceCopy)) {
            $this->collDeviceCopies->remove($this->collDeviceCopies->search($deviceCopy));
            if (null === $this->deviceCopiesScheduledForDeletion) {
                $this->deviceCopiesScheduledForDeletion = clone $this->collDeviceCopies;
                $this->deviceCopiesScheduledForDeletion->clear();
            }
            $this->deviceCopiesScheduledForDeletion[]= $deviceCopy;
            $deviceCopy->setDsTemperatureSensor(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DsTemperatureSensor is new, it will return
     * an empty collection; or if this DsTemperatureSensor has previously
     * been saved, it will retrieve related DeviceCopies from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DsTemperatureSensor.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DeviceCopy[] List of DeviceCopy objects
     */
    public function getDeviceCopiesJoinCbInput($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DeviceCopyQuery::create(null, $criteria);
        $query->joinWith('CbInput', $join_behavior);

        return $this->getDeviceCopies($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DsTemperatureSensor is new, it will return
     * an empty collection; or if this DsTemperatureSensor has previously
     * been saved, it will retrieve related DeviceCopies from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DsTemperatureSensor.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DeviceCopy[] List of DeviceCopy objects
     */
    public function getDeviceCopiesJoinDeviceGroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DeviceCopyQuery::create(null, $criteria);
        $query->joinWith('DeviceGroup', $join_behavior);

        return $this->getDeviceCopies($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DsTemperatureSensor is new, it will return
     * an empty collection; or if this DsTemperatureSensor has previously
     * been saved, it will retrieve related DeviceCopies from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DsTemperatureSensor.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DeviceCopy[] List of DeviceCopy objects
     */
    public function getDeviceCopiesJoinStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DeviceCopyQuery::create(null, $criteria);
        $query->joinWith('Store', $join_behavior);

        return $this->getDeviceCopies($query, $con);
    }

    /**
     * Clears out the collDsTemperatureNotifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return DsTemperatureSensor The current object (for fluent API support)
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
     * If this DsTemperatureSensor is new, it will return
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
                    ->filterByDsTemperatureSensor($this)
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
     * @return DsTemperatureSensor The current object (for fluent API support)
     */
    public function setDsTemperatureNotifications(PropelCollection $dsTemperatureNotifications, PropelPDO $con = null)
    {
        $dsTemperatureNotificationsToDelete = $this->getDsTemperatureNotifications(new Criteria(), $con)->diff($dsTemperatureNotifications);


        $this->dsTemperatureNotificationsScheduledForDeletion = $dsTemperatureNotificationsToDelete;

        foreach ($dsTemperatureNotificationsToDelete as $dsTemperatureNotificationRemoved) {
            $dsTemperatureNotificationRemoved->setDsTemperatureSensor(null);
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
                ->filterByDsTemperatureSensor($this)
                ->count($con);
        }

        return count($this->collDsTemperatureNotifications);
    }

    /**
     * Method called to associate a DsTemperatureNotification object to this object
     * through the DsTemperatureNotification foreign key attribute.
     *
     * @param    DsTemperatureNotification $l DsTemperatureNotification
     * @return DsTemperatureSensor The current object (for fluent API support)
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
        $dsTemperatureNotification->setDsTemperatureSensor($this);
    }

    /**
     * @param	DsTemperatureNotification $dsTemperatureNotification The dsTemperatureNotification object to remove.
     * @return DsTemperatureSensor The current object (for fluent API support)
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
            $dsTemperatureNotification->setDsTemperatureSensor(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DsTemperatureSensor is new, it will return
     * an empty collection; or if this DsTemperatureSensor has previously
     * been saved, it will retrieve related DsTemperatureNotifications from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DsTemperatureSensor.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DsTemperatureNotification[] List of DsTemperatureNotification objects
     */
    public function getDsTemperatureNotificationsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DsTemperatureNotificationQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getDsTemperatureNotifications($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->uid = null;
        $this->group = null;
        $this->controller = null;
        $this->main_store = null;
        $this->output_number = null;
        $this->name = null;
        $this->description = null;
        $this->state = null;
        $this->low_limit = null;
        $this->temperature = null;
        $this->high_limit = null;
        $this->position = null;
        $this->data_collected_at = null;
        $this->notify_after = null;
        $this->notify_started_at = null;
        $this->is_enabled = null;
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
            if ($this->collDsTemperatureSensorLogs) {
                foreach ($this->collDsTemperatureSensorLogs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDeviceCopies) {
                foreach ($this->collDeviceCopies as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDsTemperatureNotifications) {
                foreach ($this->collDsTemperatureNotifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aStore instanceof Persistent) {
              $this->aStore->clearAllReferences($deep);
            }
            if ($this->aDeviceGroup instanceof Persistent) {
              $this->aDeviceGroup->clearAllReferences($deep);
            }
            if ($this->aControllerBox instanceof Persistent) {
              $this->aControllerBox->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collDsTemperatureSensorLogs instanceof PropelCollection) {
            $this->collDsTemperatureSensorLogs->clearIterator();
        }
        $this->collDsTemperatureSensorLogs = null;
        if ($this->collDeviceCopies instanceof PropelCollection) {
            $this->collDeviceCopies->clearIterator();
        }
        $this->collDeviceCopies = null;
        if ($this->collDsTemperatureNotifications instanceof PropelCollection) {
            $this->collDsTemperatureNotifications->clearIterator();
        }
        $this->collDsTemperatureNotifications = null;
        $this->aStore = null;
        $this->aDeviceGroup = null;
        $this->aControllerBox = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(DsTemperatureSensorPeer::DEFAULT_STRING_FORMAT);
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
     * @return     DsTemperatureSensor The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = DsTemperatureSensorPeer::UPDATED_AT;

        return $this;
    }

}
