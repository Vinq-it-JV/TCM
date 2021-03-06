<?php

namespace DeviceBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use DeviceBundle\Model\CbInputPeer;
use DeviceBundle\Model\DeviceCopy;
use DeviceBundle\Model\DeviceCopyPeer;
use DeviceBundle\Model\DeviceGroupPeer;
use DeviceBundle\Model\DsTemperatureSensorPeer;
use DeviceBundle\Model\map\DeviceCopyTableMap;
use StoreBundle\Model\StorePeer;

abstract class BaseDeviceCopyPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'device_copy';

    /** the related Propel class for this table */
    const OM_CLASS = 'DeviceBundle\\Model\\DeviceCopy';

    /** the related TableMap class for this table */
    const TM_CLASS = 'DeviceBundle\\Model\\map\\DeviceCopyTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 8;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 8;

    /** the column name for the id field */
    const ID = 'device_copy.id';

    /** the column name for the uid field */
    const UID = 'device_copy.uid';

    /** the column name for the name field */
    const NAME = 'device_copy.name';

    /** the column name for the position field */
    const POSITION = 'device_copy.position';

    /** the column name for the copy_of_input field */
    const COPY_OF_INPUT = 'device_copy.copy_of_input';

    /** the column name for the copy_of_sensor field */
    const COPY_OF_SENSOR = 'device_copy.copy_of_sensor';

    /** the column name for the group field */
    const GROUP = 'device_copy.group';

    /** the column name for the main_store field */
    const MAIN_STORE = 'device_copy.main_store';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of DeviceCopy objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array DeviceCopy[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. DeviceCopyPeer::$fieldNames[DeviceCopyPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Uid', 'Name', 'Position', 'CopyOfInput', 'CopyOfSensor', 'Group', 'MainStore', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'uid', 'name', 'position', 'copyOfInput', 'copyOfSensor', 'group', 'mainStore', ),
        BasePeer::TYPE_COLNAME => array (DeviceCopyPeer::ID, DeviceCopyPeer::UID, DeviceCopyPeer::NAME, DeviceCopyPeer::POSITION, DeviceCopyPeer::COPY_OF_INPUT, DeviceCopyPeer::COPY_OF_SENSOR, DeviceCopyPeer::GROUP, DeviceCopyPeer::MAIN_STORE, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'UID', 'NAME', 'POSITION', 'COPY_OF_INPUT', 'COPY_OF_SENSOR', 'GROUP', 'MAIN_STORE', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'uid', 'name', 'position', 'copy_of_input', 'copy_of_sensor', 'group', 'main_store', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. DeviceCopyPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Uid' => 1, 'Name' => 2, 'Position' => 3, 'CopyOfInput' => 4, 'CopyOfSensor' => 5, 'Group' => 6, 'MainStore' => 7, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'uid' => 1, 'name' => 2, 'position' => 3, 'copyOfInput' => 4, 'copyOfSensor' => 5, 'group' => 6, 'mainStore' => 7, ),
        BasePeer::TYPE_COLNAME => array (DeviceCopyPeer::ID => 0, DeviceCopyPeer::UID => 1, DeviceCopyPeer::NAME => 2, DeviceCopyPeer::POSITION => 3, DeviceCopyPeer::COPY_OF_INPUT => 4, DeviceCopyPeer::COPY_OF_SENSOR => 5, DeviceCopyPeer::GROUP => 6, DeviceCopyPeer::MAIN_STORE => 7, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'UID' => 1, 'NAME' => 2, 'POSITION' => 3, 'COPY_OF_INPUT' => 4, 'COPY_OF_SENSOR' => 5, 'GROUP' => 6, 'MAIN_STORE' => 7, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'uid' => 1, 'name' => 2, 'position' => 3, 'copy_of_input' => 4, 'copy_of_sensor' => 5, 'group' => 6, 'main_store' => 7, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = DeviceCopyPeer::getFieldNames($toType);
        $key = isset(DeviceCopyPeer::$fieldKeys[$fromType][$name]) ? DeviceCopyPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(DeviceCopyPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, DeviceCopyPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return DeviceCopyPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. DeviceCopyPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(DeviceCopyPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(DeviceCopyPeer::ID);
            $criteria->addSelectColumn(DeviceCopyPeer::UID);
            $criteria->addSelectColumn(DeviceCopyPeer::NAME);
            $criteria->addSelectColumn(DeviceCopyPeer::POSITION);
            $criteria->addSelectColumn(DeviceCopyPeer::COPY_OF_INPUT);
            $criteria->addSelectColumn(DeviceCopyPeer::COPY_OF_SENSOR);
            $criteria->addSelectColumn(DeviceCopyPeer::GROUP);
            $criteria->addSelectColumn(DeviceCopyPeer::MAIN_STORE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.uid');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.position');
            $criteria->addSelectColumn($alias . '.copy_of_input');
            $criteria->addSelectColumn($alias . '.copy_of_sensor');
            $criteria->addSelectColumn($alias . '.group');
            $criteria->addSelectColumn($alias . '.main_store');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DeviceCopyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DeviceCopyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return DeviceCopy
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = DeviceCopyPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return DeviceCopyPeer::populateObjects(DeviceCopyPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            DeviceCopyPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param DeviceCopy $obj A DeviceCopy object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            DeviceCopyPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A DeviceCopy object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof DeviceCopy) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or DeviceCopy object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(DeviceCopyPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return DeviceCopy Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(DeviceCopyPeer::$instances[$key])) {
                return DeviceCopyPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (DeviceCopyPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        DeviceCopyPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to device_copy
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = DeviceCopyPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = DeviceCopyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = DeviceCopyPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                DeviceCopyPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (DeviceCopy object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = DeviceCopyPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = DeviceCopyPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + DeviceCopyPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = DeviceCopyPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            DeviceCopyPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related CbInput table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCbInput(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DeviceCopyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DeviceCopyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_INPUT, CbInputPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related DsTemperatureSensor table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinDsTemperatureSensor(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DeviceCopyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DeviceCopyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_SENSOR, DsTemperatureSensorPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related DeviceGroup table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinDeviceGroup(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DeviceCopyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DeviceCopyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DeviceCopyPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Store table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinStore(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DeviceCopyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DeviceCopyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DeviceCopyPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of DeviceCopy objects pre-filled with their CbInput objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DeviceCopy objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCbInput(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);
        }

        DeviceCopyPeer::addSelectColumns($criteria);
        $startcol = DeviceCopyPeer::NUM_HYDRATE_COLUMNS;
        CbInputPeer::addSelectColumns($criteria);

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_INPUT, CbInputPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DeviceCopyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DeviceCopyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = DeviceCopyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DeviceCopyPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CbInputPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CbInputPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CbInputPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CbInputPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (DeviceCopy) to $obj2 (CbInput)
                $obj2->addDeviceCopy($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DeviceCopy objects pre-filled with their DsTemperatureSensor objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DeviceCopy objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinDsTemperatureSensor(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);
        }

        DeviceCopyPeer::addSelectColumns($criteria);
        $startcol = DeviceCopyPeer::NUM_HYDRATE_COLUMNS;
        DsTemperatureSensorPeer::addSelectColumns($criteria);

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_SENSOR, DsTemperatureSensorPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DeviceCopyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DeviceCopyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = DeviceCopyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DeviceCopyPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = DsTemperatureSensorPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = DsTemperatureSensorPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = DsTemperatureSensorPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    DsTemperatureSensorPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (DeviceCopy) to $obj2 (DsTemperatureSensor)
                $obj2->addDeviceCopy($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DeviceCopy objects pre-filled with their DeviceGroup objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DeviceCopy objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinDeviceGroup(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);
        }

        DeviceCopyPeer::addSelectColumns($criteria);
        $startcol = DeviceCopyPeer::NUM_HYDRATE_COLUMNS;
        DeviceGroupPeer::addSelectColumns($criteria);

        $criteria->addJoin(DeviceCopyPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DeviceCopyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DeviceCopyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = DeviceCopyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DeviceCopyPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = DeviceGroupPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = DeviceGroupPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = DeviceGroupPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    DeviceGroupPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (DeviceCopy) to $obj2 (DeviceGroup)
                $obj2->addDeviceCopy($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DeviceCopy objects pre-filled with their Store objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DeviceCopy objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinStore(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);
        }

        DeviceCopyPeer::addSelectColumns($criteria);
        $startcol = DeviceCopyPeer::NUM_HYDRATE_COLUMNS;
        StorePeer::addSelectColumns($criteria);

        $criteria->addJoin(DeviceCopyPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DeviceCopyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DeviceCopyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = DeviceCopyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DeviceCopyPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = StorePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = StorePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = StorePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    StorePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (DeviceCopy) to $obj2 (Store)
                $obj2->addDeviceCopy($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DeviceCopyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DeviceCopyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_INPUT, CbInputPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_SENSOR, DsTemperatureSensorPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of DeviceCopy objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DeviceCopy objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);
        }

        DeviceCopyPeer::addSelectColumns($criteria);
        $startcol2 = DeviceCopyPeer::NUM_HYDRATE_COLUMNS;

        CbInputPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CbInputPeer::NUM_HYDRATE_COLUMNS;

        DsTemperatureSensorPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + DsTemperatureSensorPeer::NUM_HYDRATE_COLUMNS;

        DeviceGroupPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + DeviceGroupPeer::NUM_HYDRATE_COLUMNS;

        StorePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + StorePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_INPUT, CbInputPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_SENSOR, DsTemperatureSensorPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DeviceCopyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DeviceCopyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DeviceCopyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DeviceCopyPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined CbInput rows

            $key2 = CbInputPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = CbInputPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CbInputPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CbInputPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj2 (CbInput)
                $obj2->addDeviceCopy($obj1);
            } // if joined row not null

            // Add objects for joined DsTemperatureSensor rows

            $key3 = DsTemperatureSensorPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = DsTemperatureSensorPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = DsTemperatureSensorPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    DsTemperatureSensorPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj3 (DsTemperatureSensor)
                $obj3->addDeviceCopy($obj1);
            } // if joined row not null

            // Add objects for joined DeviceGroup rows

            $key4 = DeviceGroupPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = DeviceGroupPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = DeviceGroupPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    DeviceGroupPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj4 (DeviceGroup)
                $obj4->addDeviceCopy($obj1);
            } // if joined row not null

            // Add objects for joined Store rows

            $key5 = StorePeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = StorePeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = StorePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    StorePeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj5 (Store)
                $obj5->addDeviceCopy($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related CbInput table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCbInput(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DeviceCopyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DeviceCopyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_SENSOR, DsTemperatureSensorPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related DsTemperatureSensor table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptDsTemperatureSensor(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DeviceCopyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DeviceCopyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_INPUT, CbInputPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related DeviceGroup table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptDeviceGroup(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DeviceCopyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DeviceCopyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_INPUT, CbInputPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_SENSOR, DsTemperatureSensorPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Store table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptStore(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DeviceCopyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DeviceCopyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_INPUT, CbInputPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_SENSOR, DsTemperatureSensorPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of DeviceCopy objects pre-filled with all related objects except CbInput.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DeviceCopy objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCbInput(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);
        }

        DeviceCopyPeer::addSelectColumns($criteria);
        $startcol2 = DeviceCopyPeer::NUM_HYDRATE_COLUMNS;

        DsTemperatureSensorPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + DsTemperatureSensorPeer::NUM_HYDRATE_COLUMNS;

        DeviceGroupPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + DeviceGroupPeer::NUM_HYDRATE_COLUMNS;

        StorePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + StorePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_SENSOR, DsTemperatureSensorPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::MAIN_STORE, StorePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DeviceCopyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DeviceCopyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DeviceCopyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DeviceCopyPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined DsTemperatureSensor rows

                $key2 = DsTemperatureSensorPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = DsTemperatureSensorPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = DsTemperatureSensorPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    DsTemperatureSensorPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj2 (DsTemperatureSensor)
                $obj2->addDeviceCopy($obj1);

            } // if joined row is not null

                // Add objects for joined DeviceGroup rows

                $key3 = DeviceGroupPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = DeviceGroupPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = DeviceGroupPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    DeviceGroupPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj3 (DeviceGroup)
                $obj3->addDeviceCopy($obj1);

            } // if joined row is not null

                // Add objects for joined Store rows

                $key4 = StorePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = StorePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = StorePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    StorePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj4 (Store)
                $obj4->addDeviceCopy($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DeviceCopy objects pre-filled with all related objects except DsTemperatureSensor.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DeviceCopy objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptDsTemperatureSensor(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);
        }

        DeviceCopyPeer::addSelectColumns($criteria);
        $startcol2 = DeviceCopyPeer::NUM_HYDRATE_COLUMNS;

        CbInputPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CbInputPeer::NUM_HYDRATE_COLUMNS;

        DeviceGroupPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + DeviceGroupPeer::NUM_HYDRATE_COLUMNS;

        StorePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + StorePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_INPUT, CbInputPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::MAIN_STORE, StorePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DeviceCopyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DeviceCopyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DeviceCopyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DeviceCopyPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined CbInput rows

                $key2 = CbInputPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CbInputPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CbInputPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CbInputPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj2 (CbInput)
                $obj2->addDeviceCopy($obj1);

            } // if joined row is not null

                // Add objects for joined DeviceGroup rows

                $key3 = DeviceGroupPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = DeviceGroupPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = DeviceGroupPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    DeviceGroupPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj3 (DeviceGroup)
                $obj3->addDeviceCopy($obj1);

            } // if joined row is not null

                // Add objects for joined Store rows

                $key4 = StorePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = StorePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = StorePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    StorePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj4 (Store)
                $obj4->addDeviceCopy($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DeviceCopy objects pre-filled with all related objects except DeviceGroup.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DeviceCopy objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptDeviceGroup(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);
        }

        DeviceCopyPeer::addSelectColumns($criteria);
        $startcol2 = DeviceCopyPeer::NUM_HYDRATE_COLUMNS;

        CbInputPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CbInputPeer::NUM_HYDRATE_COLUMNS;

        DsTemperatureSensorPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + DsTemperatureSensorPeer::NUM_HYDRATE_COLUMNS;

        StorePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + StorePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_INPUT, CbInputPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_SENSOR, DsTemperatureSensorPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::MAIN_STORE, StorePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DeviceCopyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DeviceCopyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DeviceCopyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DeviceCopyPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined CbInput rows

                $key2 = CbInputPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CbInputPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CbInputPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CbInputPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj2 (CbInput)
                $obj2->addDeviceCopy($obj1);

            } // if joined row is not null

                // Add objects for joined DsTemperatureSensor rows

                $key3 = DsTemperatureSensorPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = DsTemperatureSensorPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = DsTemperatureSensorPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    DsTemperatureSensorPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj3 (DsTemperatureSensor)
                $obj3->addDeviceCopy($obj1);

            } // if joined row is not null

                // Add objects for joined Store rows

                $key4 = StorePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = StorePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = StorePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    StorePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj4 (Store)
                $obj4->addDeviceCopy($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DeviceCopy objects pre-filled with all related objects except Store.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DeviceCopy objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptStore(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);
        }

        DeviceCopyPeer::addSelectColumns($criteria);
        $startcol2 = DeviceCopyPeer::NUM_HYDRATE_COLUMNS;

        CbInputPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CbInputPeer::NUM_HYDRATE_COLUMNS;

        DsTemperatureSensorPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + DsTemperatureSensorPeer::NUM_HYDRATE_COLUMNS;

        DeviceGroupPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + DeviceGroupPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_INPUT, CbInputPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::COPY_OF_SENSOR, DsTemperatureSensorPeer::ID, $join_behavior);

        $criteria->addJoin(DeviceCopyPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DeviceCopyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DeviceCopyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DeviceCopyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DeviceCopyPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined CbInput rows

                $key2 = CbInputPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CbInputPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CbInputPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CbInputPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj2 (CbInput)
                $obj2->addDeviceCopy($obj1);

            } // if joined row is not null

                // Add objects for joined DsTemperatureSensor rows

                $key3 = DsTemperatureSensorPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = DsTemperatureSensorPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = DsTemperatureSensorPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    DsTemperatureSensorPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj3 (DsTemperatureSensor)
                $obj3->addDeviceCopy($obj1);

            } // if joined row is not null

                // Add objects for joined DeviceGroup rows

                $key4 = DeviceGroupPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = DeviceGroupPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = DeviceGroupPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    DeviceGroupPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (DeviceCopy) to the collection in $obj4 (DeviceGroup)
                $obj4->addDeviceCopy($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(DeviceCopyPeer::DATABASE_NAME)->getTable(DeviceCopyPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseDeviceCopyPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseDeviceCopyPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \DeviceBundle\Model\map\DeviceCopyTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return DeviceCopyPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a DeviceCopy or Criteria object.
     *
     * @param      mixed $values Criteria or DeviceCopy object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from DeviceCopy object
        }

        if ($criteria->containsKey(DeviceCopyPeer::ID) && $criteria->keyContainsValue(DeviceCopyPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.DeviceCopyPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a DeviceCopy or Criteria object.
     *
     * @param      mixed $values Criteria or DeviceCopy object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(DeviceCopyPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(DeviceCopyPeer::ID);
            $value = $criteria->remove(DeviceCopyPeer::ID);
            if ($value) {
                $selectCriteria->add(DeviceCopyPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(DeviceCopyPeer::TABLE_NAME);
            }

        } else { // $values is DeviceCopy object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the device_copy table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(DeviceCopyPeer::TABLE_NAME, $con, DeviceCopyPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DeviceCopyPeer::clearInstancePool();
            DeviceCopyPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a DeviceCopy or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or DeviceCopy object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            DeviceCopyPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof DeviceCopy) { // it's a model object
            // invalidate the cache for this single object
            DeviceCopyPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(DeviceCopyPeer::DATABASE_NAME);
            $criteria->add(DeviceCopyPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                DeviceCopyPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(DeviceCopyPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            DeviceCopyPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given DeviceCopy object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param DeviceCopy $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(DeviceCopyPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(DeviceCopyPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(DeviceCopyPeer::DATABASE_NAME, DeviceCopyPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return DeviceCopy
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = DeviceCopyPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(DeviceCopyPeer::DATABASE_NAME);
        $criteria->add(DeviceCopyPeer::ID, $pk);

        $v = DeviceCopyPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return DeviceCopy[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(DeviceCopyPeer::DATABASE_NAME);
            $criteria->add(DeviceCopyPeer::ID, $pks, Criteria::IN);
            $objs = DeviceCopyPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseDeviceCopyPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseDeviceCopyPeer::buildTableMap();

