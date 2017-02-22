<?php

namespace UserBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use UserBundle\Model\Address;
use UserBundle\Model\AddressQuery;
use UserBundle\Model\Countries;
use UserBundle\Model\CountriesPeer;
use UserBundle\Model\CountriesQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserQuery;

abstract class BaseCountries extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'UserBundle\\Model\\CountriesPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CountriesPeer
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
     * The value for the country_code field.
     * @var        string
     */
    protected $country_code;

    /**
     * The value for the language_code field.
     * @var        string
     */
    protected $language_code;

    /**
     * The value for the flag field.
     * @var        string
     */
    protected $flag;

    /**
     * @var        PropelObjectCollection|User[] Collection to store aggregation of User objects.
     */
    protected $collUsersRelatedByCountry;
    protected $collUsersRelatedByCountryPartial;

    /**
     * @var        PropelObjectCollection|User[] Collection to store aggregation of User objects.
     */
    protected $collUsersRelatedByLanguage;
    protected $collUsersRelatedByLanguagePartial;

    /**
     * @var        PropelObjectCollection|Address[] Collection to store aggregation of Address objects.
     */
    protected $collAddresses;
    protected $collAddressesPartial;

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
    protected $usersRelatedByCountryScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $usersRelatedByLanguageScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $addressesScheduledForDeletion = null;

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
     * Get the [country_code] column value.
     *
     * @return string
     */
    public function getCountryCode()
    {

        return $this->country_code;
    }

    /**
     * Get the [language_code] column value.
     *
     * @return string
     */
    public function getLanguageCode()
    {

        return $this->language_code;
    }

    /**
     * Get the [flag] column value.
     *
     * @return string
     */
    public function getFlag()
    {

        return $this->flag;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Countries The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = CountriesPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Countries The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = CountriesPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [country_code] column.
     *
     * @param  string $v new value
     * @return Countries The current object (for fluent API support)
     */
    public function setCountryCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->country_code !== $v) {
            $this->country_code = $v;
            $this->modifiedColumns[] = CountriesPeer::COUNTRY_CODE;
        }


        return $this;
    } // setCountryCode()

    /**
     * Set the value of [language_code] column.
     *
     * @param  string $v new value
     * @return Countries The current object (for fluent API support)
     */
    public function setLanguageCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->language_code !== $v) {
            $this->language_code = $v;
            $this->modifiedColumns[] = CountriesPeer::LANGUAGE_CODE;
        }


        return $this;
    } // setLanguageCode()

    /**
     * Set the value of [flag] column.
     *
     * @param  string $v new value
     * @return Countries The current object (for fluent API support)
     */
    public function setFlag($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->flag !== $v) {
            $this->flag = $v;
            $this->modifiedColumns[] = CountriesPeer::FLAG;
        }


        return $this;
    } // setFlag()

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
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->country_code = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->language_code = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->flag = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 5; // 5 = CountriesPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Countries object", $e);
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
            $con = Propel::getConnection(CountriesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CountriesPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collUsersRelatedByCountry = null;

            $this->collUsersRelatedByLanguage = null;

            $this->collAddresses = null;

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
            $con = Propel::getConnection(CountriesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = CountriesQuery::create()
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
            $con = Propel::getConnection(CountriesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                CountriesPeer::addInstanceToPool($this);
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

            if ($this->usersRelatedByCountryScheduledForDeletion !== null) {
                if (!$this->usersRelatedByCountryScheduledForDeletion->isEmpty()) {
                    foreach ($this->usersRelatedByCountryScheduledForDeletion as $userRelatedByCountry) {
                        // need to save related object because we set the relation to null
                        $userRelatedByCountry->save($con);
                    }
                    $this->usersRelatedByCountryScheduledForDeletion = null;
                }
            }

            if ($this->collUsersRelatedByCountry !== null) {
                foreach ($this->collUsersRelatedByCountry as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->usersRelatedByLanguageScheduledForDeletion !== null) {
                if (!$this->usersRelatedByLanguageScheduledForDeletion->isEmpty()) {
                    foreach ($this->usersRelatedByLanguageScheduledForDeletion as $userRelatedByLanguage) {
                        // need to save related object because we set the relation to null
                        $userRelatedByLanguage->save($con);
                    }
                    $this->usersRelatedByLanguageScheduledForDeletion = null;
                }
            }

            if ($this->collUsersRelatedByLanguage !== null) {
                foreach ($this->collUsersRelatedByLanguage as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->addressesScheduledForDeletion !== null) {
                if (!$this->addressesScheduledForDeletion->isEmpty()) {
                    foreach ($this->addressesScheduledForDeletion as $address) {
                        // need to save related object because we set the relation to null
                        $address->save($con);
                    }
                    $this->addressesScheduledForDeletion = null;
                }
            }

            if ($this->collAddresses !== null) {
                foreach ($this->collAddresses as $referrerFK) {
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

        $this->modifiedColumns[] = CountriesPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CountriesPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CountriesPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CountriesPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(CountriesPeer::COUNTRY_CODE)) {
            $modifiedColumns[':p' . $index++]  = '`country_code`';
        }
        if ($this->isColumnModified(CountriesPeer::LANGUAGE_CODE)) {
            $modifiedColumns[':p' . $index++]  = '`language_code`';
        }
        if ($this->isColumnModified(CountriesPeer::FLAG)) {
            $modifiedColumns[':p' . $index++]  = '`flag`';
        }

        $sql = sprintf(
            'INSERT INTO `countries` (%s) VALUES (%s)',
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
                    case '`country_code`':
                        $stmt->bindValue($identifier, $this->country_code, PDO::PARAM_STR);
                        break;
                    case '`language_code`':
                        $stmt->bindValue($identifier, $this->language_code, PDO::PARAM_STR);
                        break;
                    case '`flag`':
                        $stmt->bindValue($identifier, $this->flag, PDO::PARAM_STR);
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


            if (($retval = CountriesPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collUsersRelatedByCountry !== null) {
                    foreach ($this->collUsersRelatedByCountry as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUsersRelatedByLanguage !== null) {
                    foreach ($this->collUsersRelatedByLanguage as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collAddresses !== null) {
                    foreach ($this->collAddresses as $referrerFK) {
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
        $pos = CountriesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getCountryCode();
                break;
            case 3:
                return $this->getLanguageCode();
                break;
            case 4:
                return $this->getFlag();
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
        if (isset($alreadyDumpedObjects['Countries'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Countries'][$this->getPrimaryKey()] = true;
        $keys = CountriesPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getCountryCode(),
            $keys[3] => $this->getLanguageCode(),
            $keys[4] => $this->getFlag(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collUsersRelatedByCountry) {
                $result['UsersRelatedByCountry'] = $this->collUsersRelatedByCountry->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUsersRelatedByLanguage) {
                $result['UsersRelatedByLanguage'] = $this->collUsersRelatedByLanguage->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAddresses) {
                $result['Addresses'] = $this->collAddresses->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = CountriesPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setCountryCode($value);
                break;
            case 3:
                $this->setLanguageCode($value);
                break;
            case 4:
                $this->setFlag($value);
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
        $keys = CountriesPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setCountryCode($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setLanguageCode($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setFlag($arr[$keys[4]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CountriesPeer::DATABASE_NAME);

        if ($this->isColumnModified(CountriesPeer::ID)) $criteria->add(CountriesPeer::ID, $this->id);
        if ($this->isColumnModified(CountriesPeer::NAME)) $criteria->add(CountriesPeer::NAME, $this->name);
        if ($this->isColumnModified(CountriesPeer::COUNTRY_CODE)) $criteria->add(CountriesPeer::COUNTRY_CODE, $this->country_code);
        if ($this->isColumnModified(CountriesPeer::LANGUAGE_CODE)) $criteria->add(CountriesPeer::LANGUAGE_CODE, $this->language_code);
        if ($this->isColumnModified(CountriesPeer::FLAG)) $criteria->add(CountriesPeer::FLAG, $this->flag);

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
        $criteria = new Criteria(CountriesPeer::DATABASE_NAME);
        $criteria->add(CountriesPeer::ID, $this->id);

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
     * @param object $copyObj An object of Countries (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setCountryCode($this->getCountryCode());
        $copyObj->setLanguageCode($this->getLanguageCode());
        $copyObj->setFlag($this->getFlag());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getUsersRelatedByCountry() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserRelatedByCountry($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUsersRelatedByLanguage() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserRelatedByLanguage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAddresses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAddress($relObj->copy($deepCopy));
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
     * @return Countries Clone of current object.
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
     * @return CountriesPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CountriesPeer();
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
        if ('UserRelatedByCountry' == $relationName) {
            $this->initUsersRelatedByCountry();
        }
        if ('UserRelatedByLanguage' == $relationName) {
            $this->initUsersRelatedByLanguage();
        }
        if ('Address' == $relationName) {
            $this->initAddresses();
        }
    }

    /**
     * Clears out the collUsersRelatedByCountry collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Countries The current object (for fluent API support)
     * @see        addUsersRelatedByCountry()
     */
    public function clearUsersRelatedByCountry()
    {
        $this->collUsersRelatedByCountry = null; // important to set this to null since that means it is uninitialized
        $this->collUsersRelatedByCountryPartial = null;

        return $this;
    }

    /**
     * reset is the collUsersRelatedByCountry collection loaded partially
     *
     * @return void
     */
    public function resetPartialUsersRelatedByCountry($v = true)
    {
        $this->collUsersRelatedByCountryPartial = $v;
    }

    /**
     * Initializes the collUsersRelatedByCountry collection.
     *
     * By default this just sets the collUsersRelatedByCountry collection to an empty array (like clearcollUsersRelatedByCountry());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUsersRelatedByCountry($overrideExisting = true)
    {
        if (null !== $this->collUsersRelatedByCountry && !$overrideExisting) {
            return;
        }
        $this->collUsersRelatedByCountry = new PropelObjectCollection();
        $this->collUsersRelatedByCountry->setModel('User');
    }

    /**
     * Gets an array of User objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Countries is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|User[] List of User objects
     * @throws PropelException
     */
    public function getUsersRelatedByCountry($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUsersRelatedByCountryPartial && !$this->isNew();
        if (null === $this->collUsersRelatedByCountry || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUsersRelatedByCountry) {
                // return empty collection
                $this->initUsersRelatedByCountry();
            } else {
                $collUsersRelatedByCountry = UserQuery::create(null, $criteria)
                    ->filterByCountriesRelatedByCountry($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUsersRelatedByCountryPartial && count($collUsersRelatedByCountry)) {
                      $this->initUsersRelatedByCountry(false);

                      foreach ($collUsersRelatedByCountry as $obj) {
                        if (false == $this->collUsersRelatedByCountry->contains($obj)) {
                          $this->collUsersRelatedByCountry->append($obj);
                        }
                      }

                      $this->collUsersRelatedByCountryPartial = true;
                    }

                    $collUsersRelatedByCountry->getInternalIterator()->rewind();

                    return $collUsersRelatedByCountry;
                }

                if ($partial && $this->collUsersRelatedByCountry) {
                    foreach ($this->collUsersRelatedByCountry as $obj) {
                        if ($obj->isNew()) {
                            $collUsersRelatedByCountry[] = $obj;
                        }
                    }
                }

                $this->collUsersRelatedByCountry = $collUsersRelatedByCountry;
                $this->collUsersRelatedByCountryPartial = false;
            }
        }

        return $this->collUsersRelatedByCountry;
    }

    /**
     * Sets a collection of UserRelatedByCountry objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $usersRelatedByCountry A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Countries The current object (for fluent API support)
     */
    public function setUsersRelatedByCountry(PropelCollection $usersRelatedByCountry, PropelPDO $con = null)
    {
        $usersRelatedByCountryToDelete = $this->getUsersRelatedByCountry(new Criteria(), $con)->diff($usersRelatedByCountry);


        $this->usersRelatedByCountryScheduledForDeletion = $usersRelatedByCountryToDelete;

        foreach ($usersRelatedByCountryToDelete as $userRelatedByCountryRemoved) {
            $userRelatedByCountryRemoved->setCountriesRelatedByCountry(null);
        }

        $this->collUsersRelatedByCountry = null;
        foreach ($usersRelatedByCountry as $userRelatedByCountry) {
            $this->addUserRelatedByCountry($userRelatedByCountry);
        }

        $this->collUsersRelatedByCountry = $usersRelatedByCountry;
        $this->collUsersRelatedByCountryPartial = false;

        return $this;
    }

    /**
     * Returns the number of related User objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related User objects.
     * @throws PropelException
     */
    public function countUsersRelatedByCountry(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUsersRelatedByCountryPartial && !$this->isNew();
        if (null === $this->collUsersRelatedByCountry || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsersRelatedByCountry) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUsersRelatedByCountry());
            }
            $query = UserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCountriesRelatedByCountry($this)
                ->count($con);
        }

        return count($this->collUsersRelatedByCountry);
    }

    /**
     * Method called to associate a User object to this object
     * through the User foreign key attribute.
     *
     * @param    User $l User
     * @return Countries The current object (for fluent API support)
     */
    public function addUserRelatedByCountry(User $l)
    {
        if ($this->collUsersRelatedByCountry === null) {
            $this->initUsersRelatedByCountry();
            $this->collUsersRelatedByCountryPartial = true;
        }

        if (!in_array($l, $this->collUsersRelatedByCountry->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserRelatedByCountry($l);

            if ($this->usersRelatedByCountryScheduledForDeletion and $this->usersRelatedByCountryScheduledForDeletion->contains($l)) {
                $this->usersRelatedByCountryScheduledForDeletion->remove($this->usersRelatedByCountryScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	UserRelatedByCountry $userRelatedByCountry The userRelatedByCountry object to add.
     */
    protected function doAddUserRelatedByCountry($userRelatedByCountry)
    {
        $this->collUsersRelatedByCountry[]= $userRelatedByCountry;
        $userRelatedByCountry->setCountriesRelatedByCountry($this);
    }

    /**
     * @param	UserRelatedByCountry $userRelatedByCountry The userRelatedByCountry object to remove.
     * @return Countries The current object (for fluent API support)
     */
    public function removeUserRelatedByCountry($userRelatedByCountry)
    {
        if ($this->getUsersRelatedByCountry()->contains($userRelatedByCountry)) {
            $this->collUsersRelatedByCountry->remove($this->collUsersRelatedByCountry->search($userRelatedByCountry));
            if (null === $this->usersRelatedByCountryScheduledForDeletion) {
                $this->usersRelatedByCountryScheduledForDeletion = clone $this->collUsersRelatedByCountry;
                $this->usersRelatedByCountryScheduledForDeletion->clear();
            }
            $this->usersRelatedByCountryScheduledForDeletion[]= $userRelatedByCountry;
            $userRelatedByCountry->setCountriesRelatedByCountry(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Countries is new, it will return
     * an empty collection; or if this Countries has previously
     * been saved, it will retrieve related UsersRelatedByCountry from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Countries.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|User[] List of User objects
     */
    public function getUsersRelatedByCountryJoinUserGender($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserQuery::create(null, $criteria);
        $query->joinWith('UserGender', $join_behavior);

        return $this->getUsersRelatedByCountry($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Countries is new, it will return
     * an empty collection; or if this Countries has previously
     * been saved, it will retrieve related UsersRelatedByCountry from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Countries.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|User[] List of User objects
     */
    public function getUsersRelatedByCountryJoinUserTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserQuery::create(null, $criteria);
        $query->joinWith('UserTitle', $join_behavior);

        return $this->getUsersRelatedByCountry($query, $con);
    }

    /**
     * Clears out the collUsersRelatedByLanguage collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Countries The current object (for fluent API support)
     * @see        addUsersRelatedByLanguage()
     */
    public function clearUsersRelatedByLanguage()
    {
        $this->collUsersRelatedByLanguage = null; // important to set this to null since that means it is uninitialized
        $this->collUsersRelatedByLanguagePartial = null;

        return $this;
    }

    /**
     * reset is the collUsersRelatedByLanguage collection loaded partially
     *
     * @return void
     */
    public function resetPartialUsersRelatedByLanguage($v = true)
    {
        $this->collUsersRelatedByLanguagePartial = $v;
    }

    /**
     * Initializes the collUsersRelatedByLanguage collection.
     *
     * By default this just sets the collUsersRelatedByLanguage collection to an empty array (like clearcollUsersRelatedByLanguage());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUsersRelatedByLanguage($overrideExisting = true)
    {
        if (null !== $this->collUsersRelatedByLanguage && !$overrideExisting) {
            return;
        }
        $this->collUsersRelatedByLanguage = new PropelObjectCollection();
        $this->collUsersRelatedByLanguage->setModel('User');
    }

    /**
     * Gets an array of User objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Countries is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|User[] List of User objects
     * @throws PropelException
     */
    public function getUsersRelatedByLanguage($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUsersRelatedByLanguagePartial && !$this->isNew();
        if (null === $this->collUsersRelatedByLanguage || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUsersRelatedByLanguage) {
                // return empty collection
                $this->initUsersRelatedByLanguage();
            } else {
                $collUsersRelatedByLanguage = UserQuery::create(null, $criteria)
                    ->filterByCountriesRelatedByLanguage($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUsersRelatedByLanguagePartial && count($collUsersRelatedByLanguage)) {
                      $this->initUsersRelatedByLanguage(false);

                      foreach ($collUsersRelatedByLanguage as $obj) {
                        if (false == $this->collUsersRelatedByLanguage->contains($obj)) {
                          $this->collUsersRelatedByLanguage->append($obj);
                        }
                      }

                      $this->collUsersRelatedByLanguagePartial = true;
                    }

                    $collUsersRelatedByLanguage->getInternalIterator()->rewind();

                    return $collUsersRelatedByLanguage;
                }

                if ($partial && $this->collUsersRelatedByLanguage) {
                    foreach ($this->collUsersRelatedByLanguage as $obj) {
                        if ($obj->isNew()) {
                            $collUsersRelatedByLanguage[] = $obj;
                        }
                    }
                }

                $this->collUsersRelatedByLanguage = $collUsersRelatedByLanguage;
                $this->collUsersRelatedByLanguagePartial = false;
            }
        }

        return $this->collUsersRelatedByLanguage;
    }

    /**
     * Sets a collection of UserRelatedByLanguage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $usersRelatedByLanguage A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Countries The current object (for fluent API support)
     */
    public function setUsersRelatedByLanguage(PropelCollection $usersRelatedByLanguage, PropelPDO $con = null)
    {
        $usersRelatedByLanguageToDelete = $this->getUsersRelatedByLanguage(new Criteria(), $con)->diff($usersRelatedByLanguage);


        $this->usersRelatedByLanguageScheduledForDeletion = $usersRelatedByLanguageToDelete;

        foreach ($usersRelatedByLanguageToDelete as $userRelatedByLanguageRemoved) {
            $userRelatedByLanguageRemoved->setCountriesRelatedByLanguage(null);
        }

        $this->collUsersRelatedByLanguage = null;
        foreach ($usersRelatedByLanguage as $userRelatedByLanguage) {
            $this->addUserRelatedByLanguage($userRelatedByLanguage);
        }

        $this->collUsersRelatedByLanguage = $usersRelatedByLanguage;
        $this->collUsersRelatedByLanguagePartial = false;

        return $this;
    }

    /**
     * Returns the number of related User objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related User objects.
     * @throws PropelException
     */
    public function countUsersRelatedByLanguage(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUsersRelatedByLanguagePartial && !$this->isNew();
        if (null === $this->collUsersRelatedByLanguage || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsersRelatedByLanguage) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUsersRelatedByLanguage());
            }
            $query = UserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCountriesRelatedByLanguage($this)
                ->count($con);
        }

        return count($this->collUsersRelatedByLanguage);
    }

    /**
     * Method called to associate a User object to this object
     * through the User foreign key attribute.
     *
     * @param    User $l User
     * @return Countries The current object (for fluent API support)
     */
    public function addUserRelatedByLanguage(User $l)
    {
        if ($this->collUsersRelatedByLanguage === null) {
            $this->initUsersRelatedByLanguage();
            $this->collUsersRelatedByLanguagePartial = true;
        }

        if (!in_array($l, $this->collUsersRelatedByLanguage->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserRelatedByLanguage($l);

            if ($this->usersRelatedByLanguageScheduledForDeletion and $this->usersRelatedByLanguageScheduledForDeletion->contains($l)) {
                $this->usersRelatedByLanguageScheduledForDeletion->remove($this->usersRelatedByLanguageScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	UserRelatedByLanguage $userRelatedByLanguage The userRelatedByLanguage object to add.
     */
    protected function doAddUserRelatedByLanguage($userRelatedByLanguage)
    {
        $this->collUsersRelatedByLanguage[]= $userRelatedByLanguage;
        $userRelatedByLanguage->setCountriesRelatedByLanguage($this);
    }

    /**
     * @param	UserRelatedByLanguage $userRelatedByLanguage The userRelatedByLanguage object to remove.
     * @return Countries The current object (for fluent API support)
     */
    public function removeUserRelatedByLanguage($userRelatedByLanguage)
    {
        if ($this->getUsersRelatedByLanguage()->contains($userRelatedByLanguage)) {
            $this->collUsersRelatedByLanguage->remove($this->collUsersRelatedByLanguage->search($userRelatedByLanguage));
            if (null === $this->usersRelatedByLanguageScheduledForDeletion) {
                $this->usersRelatedByLanguageScheduledForDeletion = clone $this->collUsersRelatedByLanguage;
                $this->usersRelatedByLanguageScheduledForDeletion->clear();
            }
            $this->usersRelatedByLanguageScheduledForDeletion[]= $userRelatedByLanguage;
            $userRelatedByLanguage->setCountriesRelatedByLanguage(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Countries is new, it will return
     * an empty collection; or if this Countries has previously
     * been saved, it will retrieve related UsersRelatedByLanguage from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Countries.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|User[] List of User objects
     */
    public function getUsersRelatedByLanguageJoinUserGender($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserQuery::create(null, $criteria);
        $query->joinWith('UserGender', $join_behavior);

        return $this->getUsersRelatedByLanguage($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Countries is new, it will return
     * an empty collection; or if this Countries has previously
     * been saved, it will retrieve related UsersRelatedByLanguage from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Countries.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|User[] List of User objects
     */
    public function getUsersRelatedByLanguageJoinUserTitle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserQuery::create(null, $criteria);
        $query->joinWith('UserTitle', $join_behavior);

        return $this->getUsersRelatedByLanguage($query, $con);
    }

    /**
     * Clears out the collAddresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Countries The current object (for fluent API support)
     * @see        addAddresses()
     */
    public function clearAddresses()
    {
        $this->collAddresses = null; // important to set this to null since that means it is uninitialized
        $this->collAddressesPartial = null;

        return $this;
    }

    /**
     * reset is the collAddresses collection loaded partially
     *
     * @return void
     */
    public function resetPartialAddresses($v = true)
    {
        $this->collAddressesPartial = $v;
    }

    /**
     * Initializes the collAddresses collection.
     *
     * By default this just sets the collAddresses collection to an empty array (like clearcollAddresses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAddresses($overrideExisting = true)
    {
        if (null !== $this->collAddresses && !$overrideExisting) {
            return;
        }
        $this->collAddresses = new PropelObjectCollection();
        $this->collAddresses->setModel('Address');
    }

    /**
     * Gets an array of Address objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Countries is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Address[] List of Address objects
     * @throws PropelException
     */
    public function getAddresses($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collAddressesPartial && !$this->isNew();
        if (null === $this->collAddresses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAddresses) {
                // return empty collection
                $this->initAddresses();
            } else {
                $collAddresses = AddressQuery::create(null, $criteria)
                    ->filterByCountries($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collAddressesPartial && count($collAddresses)) {
                      $this->initAddresses(false);

                      foreach ($collAddresses as $obj) {
                        if (false == $this->collAddresses->contains($obj)) {
                          $this->collAddresses->append($obj);
                        }
                      }

                      $this->collAddressesPartial = true;
                    }

                    $collAddresses->getInternalIterator()->rewind();

                    return $collAddresses;
                }

                if ($partial && $this->collAddresses) {
                    foreach ($this->collAddresses as $obj) {
                        if ($obj->isNew()) {
                            $collAddresses[] = $obj;
                        }
                    }
                }

                $this->collAddresses = $collAddresses;
                $this->collAddressesPartial = false;
            }
        }

        return $this->collAddresses;
    }

    /**
     * Sets a collection of Address objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $addresses A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Countries The current object (for fluent API support)
     */
    public function setAddresses(PropelCollection $addresses, PropelPDO $con = null)
    {
        $addressesToDelete = $this->getAddresses(new Criteria(), $con)->diff($addresses);


        $this->addressesScheduledForDeletion = $addressesToDelete;

        foreach ($addressesToDelete as $addressRemoved) {
            $addressRemoved->setCountries(null);
        }

        $this->collAddresses = null;
        foreach ($addresses as $address) {
            $this->addAddress($address);
        }

        $this->collAddresses = $addresses;
        $this->collAddressesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Address objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Address objects.
     * @throws PropelException
     */
    public function countAddresses(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collAddressesPartial && !$this->isNew();
        if (null === $this->collAddresses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAddresses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAddresses());
            }
            $query = AddressQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCountries($this)
                ->count($con);
        }

        return count($this->collAddresses);
    }

    /**
     * Method called to associate a Address object to this object
     * through the Address foreign key attribute.
     *
     * @param    Address $l Address
     * @return Countries The current object (for fluent API support)
     */
    public function addAddress(Address $l)
    {
        if ($this->collAddresses === null) {
            $this->initAddresses();
            $this->collAddressesPartial = true;
        }

        if (!in_array($l, $this->collAddresses->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddAddress($l);

            if ($this->addressesScheduledForDeletion and $this->addressesScheduledForDeletion->contains($l)) {
                $this->addressesScheduledForDeletion->remove($this->addressesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Address $address The address object to add.
     */
    protected function doAddAddress($address)
    {
        $this->collAddresses[]= $address;
        $address->setCountries($this);
    }

    /**
     * @param	Address $address The address object to remove.
     * @return Countries The current object (for fluent API support)
     */
    public function removeAddress($address)
    {
        if ($this->getAddresses()->contains($address)) {
            $this->collAddresses->remove($this->collAddresses->search($address));
            if (null === $this->addressesScheduledForDeletion) {
                $this->addressesScheduledForDeletion = clone $this->collAddresses;
                $this->addressesScheduledForDeletion->clear();
            }
            $this->addressesScheduledForDeletion[]= $address;
            $address->setCountries(null);
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
        $this->country_code = null;
        $this->language_code = null;
        $this->flag = null;
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
            if ($this->collUsersRelatedByCountry) {
                foreach ($this->collUsersRelatedByCountry as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsersRelatedByLanguage) {
                foreach ($this->collUsersRelatedByLanguage as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAddresses) {
                foreach ($this->collAddresses as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collUsersRelatedByCountry instanceof PropelCollection) {
            $this->collUsersRelatedByCountry->clearIterator();
        }
        $this->collUsersRelatedByCountry = null;
        if ($this->collUsersRelatedByLanguage instanceof PropelCollection) {
            $this->collUsersRelatedByLanguage->clearIterator();
        }
        $this->collUsersRelatedByLanguage = null;
        if ($this->collAddresses instanceof PropelCollection) {
            $this->collAddresses->clearIterator();
        }
        $this->collAddresses = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CountriesPeer::DEFAULT_STRING_FORMAT);
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
