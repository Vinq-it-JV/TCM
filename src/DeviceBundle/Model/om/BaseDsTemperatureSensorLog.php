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
use \PropelDateTime;
use \PropelException;
use \PropelPDO;
use DeviceBundle\Model\DsTemperatureSensor;
use DeviceBundle\Model\DsTemperatureSensorLog;
use DeviceBundle\Model\DsTemperatureSensorLogPeer;
use DeviceBundle\Model\DsTemperatureSensorLogQuery;
use DeviceBundle\Model\DsTemperatureSensorQuery;

abstract class BaseDsTemperatureSensorLog extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DeviceBundle\\Model\\DsTemperatureSensorLogPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        DsTemperatureSensorLogPeer
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
     * The value for the sensor field.
     * @var        int
     */
    protected $sensor;

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
     * The value for the raw_data field.
     * Note: this column has a database default value of: '0'
     * @var        string
     */
    protected $raw_data;

    /**
     * The value for the data_collected_at field.
     * @var        string
     */
    protected $data_collected_at;

    /**
     * @var        DsTemperatureSensor
     */
    protected $aDsTemperatureSensor;

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
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->low_limit = '0';
        $this->temperature = '0';
        $this->high_limit = '30';
        $this->raw_data = '0';
    }

    /**
     * Initializes internal state of BaseDsTemperatureSensorLog object.
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
     * Get the [sensor] column value.
     *
     * @return int
     */
    public function getSensor()
    {

        return $this->sensor;
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
     * Get the [raw_data] column value.
     *
     * @return string
     */
    public function getRawData()
    {

        return $this->raw_data;
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
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return DsTemperatureSensorLog The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = DsTemperatureSensorLogPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [sensor] column.
     *
     * @param  int $v new value
     * @return DsTemperatureSensorLog The current object (for fluent API support)
     */
    public function setSensor($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sensor !== $v) {
            $this->sensor = $v;
            $this->modifiedColumns[] = DsTemperatureSensorLogPeer::SENSOR;
        }

        if ($this->aDsTemperatureSensor !== null && $this->aDsTemperatureSensor->getId() !== $v) {
            $this->aDsTemperatureSensor = null;
        }


        return $this;
    } // setSensor()

    /**
     * Set the value of [low_limit] column.
     *
     * @param  string $v new value
     * @return DsTemperatureSensorLog The current object (for fluent API support)
     */
    public function setLowLimit($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->low_limit !== $v) {
            $this->low_limit = $v;
            $this->modifiedColumns[] = DsTemperatureSensorLogPeer::LOW_LIMIT;
        }


        return $this;
    } // setLowLimit()

    /**
     * Set the value of [temperature] column.
     *
     * @param  string $v new value
     * @return DsTemperatureSensorLog The current object (for fluent API support)
     */
    public function setTemperature($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->temperature !== $v) {
            $this->temperature = $v;
            $this->modifiedColumns[] = DsTemperatureSensorLogPeer::TEMPERATURE;
        }


        return $this;
    } // setTemperature()

    /**
     * Set the value of [high_limit] column.
     *
     * @param  string $v new value
     * @return DsTemperatureSensorLog The current object (for fluent API support)
     */
    public function setHighLimit($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->high_limit !== $v) {
            $this->high_limit = $v;
            $this->modifiedColumns[] = DsTemperatureSensorLogPeer::HIGH_LIMIT;
        }


        return $this;
    } // setHighLimit()

    /**
     * Set the value of [raw_data] column.
     *
     * @param  string $v new value
     * @return DsTemperatureSensorLog The current object (for fluent API support)
     */
    public function setRawData($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->raw_data !== $v) {
            $this->raw_data = $v;
            $this->modifiedColumns[] = DsTemperatureSensorLogPeer::RAW_DATA;
        }


        return $this;
    } // setRawData()

    /**
     * Sets the value of [data_collected_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return DsTemperatureSensorLog The current object (for fluent API support)
     */
    public function setDataCollectedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->data_collected_at !== null || $dt !== null) {
            $currentDateAsString = ($this->data_collected_at !== null && $tmpDt = new DateTime($this->data_collected_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->data_collected_at = $newDateAsString;
                $this->modifiedColumns[] = DsTemperatureSensorLogPeer::DATA_COLLECTED_AT;
            }
        } // if either are not null


        return $this;
    } // setDataCollectedAt()

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
            if ($this->low_limit !== '0') {
                return false;
            }

            if ($this->temperature !== '0') {
                return false;
            }

            if ($this->high_limit !== '30') {
                return false;
            }

            if ($this->raw_data !== '0') {
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
            $this->sensor = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->low_limit = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->temperature = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->high_limit = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->raw_data = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->data_collected_at = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 7; // 7 = DsTemperatureSensorLogPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating DsTemperatureSensorLog object", $e);
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

        if ($this->aDsTemperatureSensor !== null && $this->sensor !== $this->aDsTemperatureSensor->getId()) {
            $this->aDsTemperatureSensor = null;
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
            $con = Propel::getConnection(DsTemperatureSensorLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = DsTemperatureSensorLogPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aDsTemperatureSensor = null;
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
            $con = Propel::getConnection(DsTemperatureSensorLogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = DsTemperatureSensorLogQuery::create()
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
            $con = Propel::getConnection(DsTemperatureSensorLogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                DsTemperatureSensorLogPeer::addInstanceToPool($this);
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

            if ($this->aDsTemperatureSensor !== null) {
                if ($this->aDsTemperatureSensor->isModified() || $this->aDsTemperatureSensor->isNew()) {
                    $affectedRows += $this->aDsTemperatureSensor->save($con);
                }
                $this->setDsTemperatureSensor($this->aDsTemperatureSensor);
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

        $this->modifiedColumns[] = DsTemperatureSensorLogPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DsTemperatureSensorLogPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DsTemperatureSensorLogPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(DsTemperatureSensorLogPeer::SENSOR)) {
            $modifiedColumns[':p' . $index++]  = '`sensor`';
        }
        if ($this->isColumnModified(DsTemperatureSensorLogPeer::LOW_LIMIT)) {
            $modifiedColumns[':p' . $index++]  = '`low_limit`';
        }
        if ($this->isColumnModified(DsTemperatureSensorLogPeer::TEMPERATURE)) {
            $modifiedColumns[':p' . $index++]  = '`temperature`';
        }
        if ($this->isColumnModified(DsTemperatureSensorLogPeer::HIGH_LIMIT)) {
            $modifiedColumns[':p' . $index++]  = '`high_limit`';
        }
        if ($this->isColumnModified(DsTemperatureSensorLogPeer::RAW_DATA)) {
            $modifiedColumns[':p' . $index++]  = '`raw_data`';
        }
        if ($this->isColumnModified(DsTemperatureSensorLogPeer::DATA_COLLECTED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`data_collected_at`';
        }

        $sql = sprintf(
            'INSERT INTO `ds_temperature_sensor_log` (%s) VALUES (%s)',
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
                    case '`sensor`':
                        $stmt->bindValue($identifier, $this->sensor, PDO::PARAM_INT);
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
                    case '`raw_data`':
                        $stmt->bindValue($identifier, $this->raw_data, PDO::PARAM_STR);
                        break;
                    case '`data_collected_at`':
                        $stmt->bindValue($identifier, $this->data_collected_at, PDO::PARAM_STR);
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

            if ($this->aDsTemperatureSensor !== null) {
                if (!$this->aDsTemperatureSensor->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDsTemperatureSensor->getValidationFailures());
                }
            }


            if (($retval = DsTemperatureSensorLogPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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
        $pos = DsTemperatureSensorLogPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getSensor();
                break;
            case 2:
                return $this->getLowLimit();
                break;
            case 3:
                return $this->getTemperature();
                break;
            case 4:
                return $this->getHighLimit();
                break;
            case 5:
                return $this->getRawData();
                break;
            case 6:
                return $this->getDataCollectedAt();
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
        if (isset($alreadyDumpedObjects['DsTemperatureSensorLog'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['DsTemperatureSensorLog'][$this->getPrimaryKey()] = true;
        $keys = DsTemperatureSensorLogPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSensor(),
            $keys[2] => $this->getLowLimit(),
            $keys[3] => $this->getTemperature(),
            $keys[4] => $this->getHighLimit(),
            $keys[5] => $this->getRawData(),
            $keys[6] => $this->getDataCollectedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aDsTemperatureSensor) {
                $result['DsTemperatureSensor'] = $this->aDsTemperatureSensor->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = DsTemperatureSensorLogPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setSensor($value);
                break;
            case 2:
                $this->setLowLimit($value);
                break;
            case 3:
                $this->setTemperature($value);
                break;
            case 4:
                $this->setHighLimit($value);
                break;
            case 5:
                $this->setRawData($value);
                break;
            case 6:
                $this->setDataCollectedAt($value);
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
        $keys = DsTemperatureSensorLogPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setSensor($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setLowLimit($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTemperature($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setHighLimit($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setRawData($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setDataCollectedAt($arr[$keys[6]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(DsTemperatureSensorLogPeer::DATABASE_NAME);

        if ($this->isColumnModified(DsTemperatureSensorLogPeer::ID)) $criteria->add(DsTemperatureSensorLogPeer::ID, $this->id);
        if ($this->isColumnModified(DsTemperatureSensorLogPeer::SENSOR)) $criteria->add(DsTemperatureSensorLogPeer::SENSOR, $this->sensor);
        if ($this->isColumnModified(DsTemperatureSensorLogPeer::LOW_LIMIT)) $criteria->add(DsTemperatureSensorLogPeer::LOW_LIMIT, $this->low_limit);
        if ($this->isColumnModified(DsTemperatureSensorLogPeer::TEMPERATURE)) $criteria->add(DsTemperatureSensorLogPeer::TEMPERATURE, $this->temperature);
        if ($this->isColumnModified(DsTemperatureSensorLogPeer::HIGH_LIMIT)) $criteria->add(DsTemperatureSensorLogPeer::HIGH_LIMIT, $this->high_limit);
        if ($this->isColumnModified(DsTemperatureSensorLogPeer::RAW_DATA)) $criteria->add(DsTemperatureSensorLogPeer::RAW_DATA, $this->raw_data);
        if ($this->isColumnModified(DsTemperatureSensorLogPeer::DATA_COLLECTED_AT)) $criteria->add(DsTemperatureSensorLogPeer::DATA_COLLECTED_AT, $this->data_collected_at);

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
        $criteria = new Criteria(DsTemperatureSensorLogPeer::DATABASE_NAME);
        $criteria->add(DsTemperatureSensorLogPeer::ID, $this->id);

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
     * @param object $copyObj An object of DsTemperatureSensorLog (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setSensor($this->getSensor());
        $copyObj->setLowLimit($this->getLowLimit());
        $copyObj->setTemperature($this->getTemperature());
        $copyObj->setHighLimit($this->getHighLimit());
        $copyObj->setRawData($this->getRawData());
        $copyObj->setDataCollectedAt($this->getDataCollectedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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
     * @return DsTemperatureSensorLog Clone of current object.
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
     * @return DsTemperatureSensorLogPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new DsTemperatureSensorLogPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a DsTemperatureSensor object.
     *
     * @param                  DsTemperatureSensor $v
     * @return DsTemperatureSensorLog The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDsTemperatureSensor(DsTemperatureSensor $v = null)
    {
        if ($v === null) {
            $this->setSensor(NULL);
        } else {
            $this->setSensor($v->getId());
        }

        $this->aDsTemperatureSensor = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the DsTemperatureSensor object, it will not be re-added.
        if ($v !== null) {
            $v->addDsTemperatureSensorLog($this);
        }


        return $this;
    }


    /**
     * Get the associated DsTemperatureSensor object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return DsTemperatureSensor The associated DsTemperatureSensor object.
     * @throws PropelException
     */
    public function getDsTemperatureSensor(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDsTemperatureSensor === null && ($this->sensor !== null) && $doQuery) {
            $this->aDsTemperatureSensor = DsTemperatureSensorQuery::create()->findPk($this->sensor, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDsTemperatureSensor->addDsTemperatureSensorLogs($this);
             */
        }

        return $this->aDsTemperatureSensor;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->sensor = null;
        $this->low_limit = null;
        $this->temperature = null;
        $this->high_limit = null;
        $this->raw_data = null;
        $this->data_collected_at = null;
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
            if ($this->aDsTemperatureSensor instanceof Persistent) {
              $this->aDsTemperatureSensor->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aDsTemperatureSensor = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(DsTemperatureSensorLogPeer::DEFAULT_STRING_FORMAT);
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

}
