<?php

namespace DeviceBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use DeviceBundle\Model\CbInput;
use DeviceBundle\Model\CbInputQuery;
use DeviceBundle\Model\DeviceCopy;
use DeviceBundle\Model\DeviceCopyPeer;
use DeviceBundle\Model\DeviceCopyQuery;
use DeviceBundle\Model\DeviceGroup;
use DeviceBundle\Model\DeviceGroupQuery;
use DeviceBundle\Model\DsTemperatureSensor;
use DeviceBundle\Model\DsTemperatureSensorQuery;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreQuery;

abstract class BaseDeviceCopy extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'DeviceBundle\\Model\\DeviceCopyPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        DeviceCopyPeer
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
     * The value for the name field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $name;

    /**
     * The value for the position field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $position;

    /**
     * The value for the copy_of_input field.
     * @var        int
     */
    protected $copy_of_input;

    /**
     * The value for the copy_of_sensor field.
     * @var        int
     */
    protected $copy_of_sensor;

    /**
     * The value for the group field.
     * @var        int
     */
    protected $group;

    /**
     * The value for the main_store field.
     * @var        int
     */
    protected $main_store;

    /**
     * @var        CbInput
     */
    protected $aCbInput;

    /**
     * @var        DsTemperatureSensor
     */
    protected $aDsTemperatureSensor;

    /**
     * @var        DeviceGroup
     */
    protected $aDeviceGroup;

    /**
     * @var        Store
     */
    protected $aStore;

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
        $this->name = '';
        $this->position = 0;
    }

    /**
     * Initializes internal state of BaseDeviceCopy object.
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
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
     * Get the [copy_of_input] column value.
     *
     * @return int
     */
    public function getCopyOfInput()
    {

        return $this->copy_of_input;
    }

    /**
     * Get the [copy_of_sensor] column value.
     *
     * @return int
     */
    public function getCopyOfSensor()
    {

        return $this->copy_of_sensor;
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
     * Get the [main_store] column value.
     *
     * @return int
     */
    public function getMainStore()
    {

        return $this->main_store;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return DeviceCopy The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = DeviceCopyPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [uid] column.
     *
     * @param  string $v new value
     * @return DeviceCopy The current object (for fluent API support)
     */
    public function setUid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uid !== $v) {
            $this->uid = $v;
            $this->modifiedColumns[] = DeviceCopyPeer::UID;
        }


        return $this;
    } // setUid()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return DeviceCopy The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = DeviceCopyPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [position] column.
     *
     * @param  int $v new value
     * @return DeviceCopy The current object (for fluent API support)
     */
    public function setPosition($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->position !== $v) {
            $this->position = $v;
            $this->modifiedColumns[] = DeviceCopyPeer::POSITION;
        }


        return $this;
    } // setPosition()

    /**
     * Set the value of [copy_of_input] column.
     *
     * @param  int $v new value
     * @return DeviceCopy The current object (for fluent API support)
     */
    public function setCopyOfInput($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->copy_of_input !== $v) {
            $this->copy_of_input = $v;
            $this->modifiedColumns[] = DeviceCopyPeer::COPY_OF_INPUT;
        }

        if ($this->aCbInput !== null && $this->aCbInput->getId() !== $v) {
            $this->aCbInput = null;
        }


        return $this;
    } // setCopyOfInput()

    /**
     * Set the value of [copy_of_sensor] column.
     *
     * @param  int $v new value
     * @return DeviceCopy The current object (for fluent API support)
     */
    public function setCopyOfSensor($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->copy_of_sensor !== $v) {
            $this->copy_of_sensor = $v;
            $this->modifiedColumns[] = DeviceCopyPeer::COPY_OF_SENSOR;
        }

        if ($this->aDsTemperatureSensor !== null && $this->aDsTemperatureSensor->getId() !== $v) {
            $this->aDsTemperatureSensor = null;
        }


        return $this;
    } // setCopyOfSensor()

    /**
     * Set the value of [group] column.
     *
     * @param  int $v new value
     * @return DeviceCopy The current object (for fluent API support)
     */
    public function setGroup($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->group !== $v) {
            $this->group = $v;
            $this->modifiedColumns[] = DeviceCopyPeer::GROUP;
        }

        if ($this->aDeviceGroup !== null && $this->aDeviceGroup->getId() !== $v) {
            $this->aDeviceGroup = null;
        }


        return $this;
    } // setGroup()

    /**
     * Set the value of [main_store] column.
     *
     * @param  int $v new value
     * @return DeviceCopy The current object (for fluent API support)
     */
    public function setMainStore($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->main_store !== $v) {
            $this->main_store = $v;
            $this->modifiedColumns[] = DeviceCopyPeer::MAIN_STORE;
        }

        if ($this->aStore !== null && $this->aStore->getId() !== $v) {
            $this->aStore = null;
        }


        return $this;
    } // setMainStore()

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
            if ($this->name !== '') {
                return false;
            }

            if ($this->position !== 0) {
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
            $this->name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->position = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->copy_of_input = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->copy_of_sensor = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->group = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->main_store = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 8; // 8 = DeviceCopyPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating DeviceCopy object", $e);
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

        if ($this->aCbInput !== null && $this->copy_of_input !== $this->aCbInput->getId()) {
            $this->aCbInput = null;
        }
        if ($this->aDsTemperatureSensor !== null && $this->copy_of_sensor !== $this->aDsTemperatureSensor->getId()) {
            $this->aDsTemperatureSensor = null;
        }
        if ($this->aDeviceGroup !== null && $this->group !== $this->aDeviceGroup->getId()) {
            $this->aDeviceGroup = null;
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
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = DeviceCopyPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCbInput = null;
            $this->aDsTemperatureSensor = null;
            $this->aDeviceGroup = null;
            $this->aStore = null;
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
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = DeviceCopyQuery::create()
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
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                DeviceCopyPeer::addInstanceToPool($this);
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

            if ($this->aCbInput !== null) {
                if ($this->aCbInput->isModified() || $this->aCbInput->isNew()) {
                    $affectedRows += $this->aCbInput->save($con);
                }
                $this->setCbInput($this->aCbInput);
            }

            if ($this->aDsTemperatureSensor !== null) {
                if ($this->aDsTemperatureSensor->isModified() || $this->aDsTemperatureSensor->isNew()) {
                    $affectedRows += $this->aDsTemperatureSensor->save($con);
                }
                $this->setDsTemperatureSensor($this->aDsTemperatureSensor);
            }

            if ($this->aDeviceGroup !== null) {
                if ($this->aDeviceGroup->isModified() || $this->aDeviceGroup->isNew()) {
                    $affectedRows += $this->aDeviceGroup->save($con);
                }
                $this->setDeviceGroup($this->aDeviceGroup);
            }

            if ($this->aStore !== null) {
                if ($this->aStore->isModified() || $this->aStore->isNew()) {
                    $affectedRows += $this->aStore->save($con);
                }
                $this->setStore($this->aStore);
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

        $this->modifiedColumns[] = DeviceCopyPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DeviceCopyPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DeviceCopyPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(DeviceCopyPeer::UID)) {
            $modifiedColumns[':p' . $index++]  = '`uid`';
        }
        if ($this->isColumnModified(DeviceCopyPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(DeviceCopyPeer::POSITION)) {
            $modifiedColumns[':p' . $index++]  = '`position`';
        }
        if ($this->isColumnModified(DeviceCopyPeer::COPY_OF_INPUT)) {
            $modifiedColumns[':p' . $index++]  = '`copy_of_input`';
        }
        if ($this->isColumnModified(DeviceCopyPeer::COPY_OF_SENSOR)) {
            $modifiedColumns[':p' . $index++]  = '`copy_of_sensor`';
        }
        if ($this->isColumnModified(DeviceCopyPeer::GROUP)) {
            $modifiedColumns[':p' . $index++]  = '`group`';
        }
        if ($this->isColumnModified(DeviceCopyPeer::MAIN_STORE)) {
            $modifiedColumns[':p' . $index++]  = '`main_store`';
        }

        $sql = sprintf(
            'INSERT INTO `device_copy` (%s) VALUES (%s)',
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
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`position`':
                        $stmt->bindValue($identifier, $this->position, PDO::PARAM_INT);
                        break;
                    case '`copy_of_input`':
                        $stmt->bindValue($identifier, $this->copy_of_input, PDO::PARAM_INT);
                        break;
                    case '`copy_of_sensor`':
                        $stmt->bindValue($identifier, $this->copy_of_sensor, PDO::PARAM_INT);
                        break;
                    case '`group`':
                        $stmt->bindValue($identifier, $this->group, PDO::PARAM_INT);
                        break;
                    case '`main_store`':
                        $stmt->bindValue($identifier, $this->main_store, PDO::PARAM_INT);
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

            if ($this->aCbInput !== null) {
                if (!$this->aCbInput->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCbInput->getValidationFailures());
                }
            }

            if ($this->aDsTemperatureSensor !== null) {
                if (!$this->aDsTemperatureSensor->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDsTemperatureSensor->getValidationFailures());
                }
            }

            if ($this->aDeviceGroup !== null) {
                if (!$this->aDeviceGroup->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDeviceGroup->getValidationFailures());
                }
            }

            if ($this->aStore !== null) {
                if (!$this->aStore->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aStore->getValidationFailures());
                }
            }


            if (($retval = DeviceCopyPeer::doValidate($this, $columns)) !== true) {
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
        $pos = DeviceCopyPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getName();
                break;
            case 3:
                return $this->getPosition();
                break;
            case 4:
                return $this->getCopyOfInput();
                break;
            case 5:
                return $this->getCopyOfSensor();
                break;
            case 6:
                return $this->getGroup();
                break;
            case 7:
                return $this->getMainStore();
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
        if (isset($alreadyDumpedObjects['DeviceCopy'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['DeviceCopy'][$this->getPrimaryKey()] = true;
        $keys = DeviceCopyPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUid(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getPosition(),
            $keys[4] => $this->getCopyOfInput(),
            $keys[5] => $this->getCopyOfSensor(),
            $keys[6] => $this->getGroup(),
            $keys[7] => $this->getMainStore(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCbInput) {
                $result['CbInput'] = $this->aCbInput->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDsTemperatureSensor) {
                $result['DsTemperatureSensor'] = $this->aDsTemperatureSensor->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDeviceGroup) {
                $result['DeviceGroup'] = $this->aDeviceGroup->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aStore) {
                $result['Store'] = $this->aStore->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = DeviceCopyPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setName($value);
                break;
            case 3:
                $this->setPosition($value);
                break;
            case 4:
                $this->setCopyOfInput($value);
                break;
            case 5:
                $this->setCopyOfSensor($value);
                break;
            case 6:
                $this->setGroup($value);
                break;
            case 7:
                $this->setMainStore($value);
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
        $keys = DeviceCopyPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUid($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPosition($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setCopyOfInput($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setCopyOfSensor($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setGroup($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setMainStore($arr[$keys[7]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(DeviceCopyPeer::DATABASE_NAME);

        if ($this->isColumnModified(DeviceCopyPeer::ID)) $criteria->add(DeviceCopyPeer::ID, $this->id);
        if ($this->isColumnModified(DeviceCopyPeer::UID)) $criteria->add(DeviceCopyPeer::UID, $this->uid);
        if ($this->isColumnModified(DeviceCopyPeer::NAME)) $criteria->add(DeviceCopyPeer::NAME, $this->name);
        if ($this->isColumnModified(DeviceCopyPeer::POSITION)) $criteria->add(DeviceCopyPeer::POSITION, $this->position);
        if ($this->isColumnModified(DeviceCopyPeer::COPY_OF_INPUT)) $criteria->add(DeviceCopyPeer::COPY_OF_INPUT, $this->copy_of_input);
        if ($this->isColumnModified(DeviceCopyPeer::COPY_OF_SENSOR)) $criteria->add(DeviceCopyPeer::COPY_OF_SENSOR, $this->copy_of_sensor);
        if ($this->isColumnModified(DeviceCopyPeer::GROUP)) $criteria->add(DeviceCopyPeer::GROUP, $this->group);
        if ($this->isColumnModified(DeviceCopyPeer::MAIN_STORE)) $criteria->add(DeviceCopyPeer::MAIN_STORE, $this->main_store);

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
        $criteria = new Criteria(DeviceCopyPeer::DATABASE_NAME);
        $criteria->add(DeviceCopyPeer::ID, $this->id);

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
     * @param object $copyObj An object of DeviceCopy (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUid($this->getUid());
        $copyObj->setName($this->getName());
        $copyObj->setPosition($this->getPosition());
        $copyObj->setCopyOfInput($this->getCopyOfInput());
        $copyObj->setCopyOfSensor($this->getCopyOfSensor());
        $copyObj->setGroup($this->getGroup());
        $copyObj->setMainStore($this->getMainStore());

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
     * @return DeviceCopy Clone of current object.
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
     * @return DeviceCopyPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new DeviceCopyPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a CbInput object.
     *
     * @param                  CbInput $v
     * @return DeviceCopy The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCbInput(CbInput $v = null)
    {
        if ($v === null) {
            $this->setCopyOfInput(NULL);
        } else {
            $this->setCopyOfInput($v->getId());
        }

        $this->aCbInput = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the CbInput object, it will not be re-added.
        if ($v !== null) {
            $v->addDeviceCopy($this);
        }


        return $this;
    }


    /**
     * Get the associated CbInput object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return CbInput The associated CbInput object.
     * @throws PropelException
     */
    public function getCbInput(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCbInput === null && ($this->copy_of_input !== null) && $doQuery) {
            $this->aCbInput = CbInputQuery::create()->findPk($this->copy_of_input, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCbInput->addDeviceCopies($this);
             */
        }

        return $this->aCbInput;
    }

    /**
     * Declares an association between this object and a DsTemperatureSensor object.
     *
     * @param                  DsTemperatureSensor $v
     * @return DeviceCopy The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDsTemperatureSensor(DsTemperatureSensor $v = null)
    {
        if ($v === null) {
            $this->setCopyOfSensor(NULL);
        } else {
            $this->setCopyOfSensor($v->getId());
        }

        $this->aDsTemperatureSensor = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the DsTemperatureSensor object, it will not be re-added.
        if ($v !== null) {
            $v->addDeviceCopy($this);
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
        if ($this->aDsTemperatureSensor === null && ($this->copy_of_sensor !== null) && $doQuery) {
            $this->aDsTemperatureSensor = DsTemperatureSensorQuery::create()->findPk($this->copy_of_sensor, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDsTemperatureSensor->addDeviceCopies($this);
             */
        }

        return $this->aDsTemperatureSensor;
    }

    /**
     * Declares an association between this object and a DeviceGroup object.
     *
     * @param                  DeviceGroup $v
     * @return DeviceCopy The current object (for fluent API support)
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
            $v->addDeviceCopy($this);
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
                $this->aDeviceGroup->addDeviceCopies($this);
             */
        }

        return $this->aDeviceGroup;
    }

    /**
     * Declares an association between this object and a Store object.
     *
     * @param                  Store $v
     * @return DeviceCopy The current object (for fluent API support)
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
            $v->addDeviceCopy($this);
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
                $this->aStore->addDeviceCopies($this);
             */
        }

        return $this->aStore;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->uid = null;
        $this->name = null;
        $this->position = null;
        $this->copy_of_input = null;
        $this->copy_of_sensor = null;
        $this->group = null;
        $this->main_store = null;
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
            if ($this->aCbInput instanceof Persistent) {
              $this->aCbInput->clearAllReferences($deep);
            }
            if ($this->aDsTemperatureSensor instanceof Persistent) {
              $this->aDsTemperatureSensor->clearAllReferences($deep);
            }
            if ($this->aDeviceGroup instanceof Persistent) {
              $this->aDeviceGroup->clearAllReferences($deep);
            }
            if ($this->aStore instanceof Persistent) {
              $this->aStore->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aCbInput = null;
        $this->aDsTemperatureSensor = null;
        $this->aDeviceGroup = null;
        $this->aStore = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(DeviceCopyPeer::DEFAULT_STRING_FORMAT);
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
