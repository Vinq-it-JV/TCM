<?php

namespace CompanyBundle\Model\om;

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
use CompanyBundle\Model\Company;
use CompanyBundle\Model\CompanyInformant;
use CompanyBundle\Model\CompanyInformantQuery;
use CompanyBundle\Model\CompanyQuery;
use CompanyBundle\Model\Informant;
use CompanyBundle\Model\InformantPeer;
use CompanyBundle\Model\InformantQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserQuery;

abstract class BaseInformant extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'CompanyBundle\\Model\\InformantPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        InformantPeer
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
     * The value for the user_id field.
     * @var        int
     */
    protected $user_id;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        PropelObjectCollection|CompanyInformant[] Collection to store aggregation of CompanyInformant objects.
     */
    protected $collCompanyInformants;
    protected $collCompanyInformantsPartial;

    /**
     * @var        PropelObjectCollection|Company[] Collection to store aggregation of Company objects.
     */
    protected $collCompanies;

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
    protected $companyInformantsScheduledForDeletion = null;

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
     * Get the [user_id] column value.
     *
     * @return int
     */
    public function getUserId()
    {

        return $this->user_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Informant The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = InformantPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [user_id] column.
     *
     * @param  int $v new value
     * @return Informant The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = InformantPeer::USER_ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setUserId()

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
            $this->user_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 2; // 2 = InformantPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Informant object", $e);
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

        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
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
            $con = Propel::getConnection(InformantPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = InformantPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->collCompanyInformants = null;

            $this->collCompanies = null;
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
            $con = Propel::getConnection(InformantPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = InformantQuery::create()
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
            $con = Propel::getConnection(InformantPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                InformantPeer::addInstanceToPool($this);
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

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
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
                    CompanyInformantQuery::create()
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

        $this->modifiedColumns[] = InformantPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . InformantPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(InformantPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(InformantPeer::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`user_id`';
        }

        $sql = sprintf(
            'INSERT INTO `informant` (%s) VALUES (%s)',
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
                    case '`user_id`':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
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

            if ($this->aUser !== null) {
                if (!$this->aUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
                }
            }


            if (($retval = InformantPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCompanyInformants !== null) {
                    foreach ($this->collCompanyInformants as $referrerFK) {
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
        $pos = InformantPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getUserId();
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
        if (isset($alreadyDumpedObjects['Informant'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Informant'][$this->getPrimaryKey()] = true;
        $keys = InformantPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUserId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCompanyInformants) {
                $result['CompanyInformants'] = $this->collCompanyInformants->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = InformantPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setUserId($value);
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
        $keys = InformantPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUserId($arr[$keys[1]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(InformantPeer::DATABASE_NAME);

        if ($this->isColumnModified(InformantPeer::ID)) $criteria->add(InformantPeer::ID, $this->id);
        if ($this->isColumnModified(InformantPeer::USER_ID)) $criteria->add(InformantPeer::USER_ID, $this->user_id);

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
        $criteria = new Criteria(InformantPeer::DATABASE_NAME);
        $criteria->add(InformantPeer::ID, $this->id);

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
     * @param object $copyObj An object of Informant (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUserId($this->getUserId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCompanyInformants() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCompanyInformant($relObj->copy($deepCopy));
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
     * @return Informant Clone of current object.
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
     * @return InformantPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new InformantPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return Informant The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addInformant($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUser(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aUser === null && ($this->user_id !== null) && $doQuery) {
            $this->aUser = UserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addInformants($this);
             */
        }

        return $this->aUser;
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
        if ('CompanyInformant' == $relationName) {
            $this->initCompanyInformants();
        }
    }

    /**
     * Clears out the collCompanyInformants collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Informant The current object (for fluent API support)
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
     * If this Informant is new, it will return
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
     * @return Informant The current object (for fluent API support)
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
     * @return Informant The current object (for fluent API support)
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
     * @return Informant The current object (for fluent API support)
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
     * Otherwise if this Informant is new, it will return
     * an empty collection; or if this Informant has previously
     * been saved, it will retrieve related CompanyInformants from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Informant.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CompanyInformant[] List of CompanyInformant objects
     */
    public function getCompanyInformantsJoinCompany($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CompanyInformantQuery::create(null, $criteria);
        $query->joinWith('Company', $join_behavior);

        return $this->getCompanyInformants($query, $con);
    }

    /**
     * Clears out the collCompanies collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Informant The current object (for fluent API support)
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
     * to the current object by way of the company_informant cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Informant is new, it will return
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
                    ->filterByInformant($this)
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
     * to the current object by way of the company_informant cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $companies A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Informant The current object (for fluent API support)
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
     * to the current object by way of the company_informant cross-reference table.
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
                    ->filterByInformant($this)
                    ->count($con);
            }
        } else {
            return count($this->collCompanies);
        }
    }

    /**
     * Associate a Company object to this object
     * through the company_informant cross reference table.
     *
     * @param  Company $company The CompanyInformant object to relate
     * @return Informant The current object (for fluent API support)
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
        if (!$company->getInformants()->contains($this)) { $companyInformant = new CompanyInformant();
            $companyInformant->setCompany($company);
            $this->addCompanyInformant($companyInformant);

            $foreignCollection = $company->getInformants();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a Company object to this object
     * through the company_informant cross reference table.
     *
     * @param Company $company The CompanyInformant object to relate
     * @return Informant The current object (for fluent API support)
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
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->user_id = null;
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
            if ($this->collCompanyInformants) {
                foreach ($this->collCompanyInformants as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCompanies) {
                foreach ($this->collCompanies as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aUser instanceof Persistent) {
              $this->aUser->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collCompanyInformants instanceof PropelCollection) {
            $this->collCompanyInformants->clearIterator();
        }
        $this->collCompanyInformants = null;
        if ($this->collCompanies instanceof PropelCollection) {
            $this->collCompanies->clearIterator();
        }
        $this->collCompanies = null;
        $this->aUser = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(InformantPeer::DEFAULT_STRING_FORMAT);
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

    /**
     * Catches calls to virtual methods
     */
    public function __call($name, $params)
    {

        // delegate behavior

        if (is_callable(array('UserBundle\Model\User', $name))) {
            if (!$delegate = $this->getUser()) {
                $delegate = new User();
                $this->setUser($delegate);
            }

            return call_user_func_array(array($delegate, $name), $params);
        }

        return parent::__call($name, $params);
    }

}
