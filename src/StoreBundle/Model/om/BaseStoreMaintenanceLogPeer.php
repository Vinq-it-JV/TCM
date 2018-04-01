<?php

namespace StoreBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use CollectionBundle\Model\CollectionPeer;
use StoreBundle\Model\MaintenanceTypePeer;
use StoreBundle\Model\StoreMaintenanceLog;
use StoreBundle\Model\StoreMaintenanceLogPeer;
use StoreBundle\Model\StorePeer;
use StoreBundle\Model\map\StoreMaintenanceLogTableMap;
use UserBundle\Model\UserPeer;

abstract class BaseStoreMaintenanceLogPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'store_maintenance_log';

    /** the related Propel class for this table */
    const OM_CLASS = 'StoreBundle\\Model\\StoreMaintenanceLog';

    /** the related TableMap class for this table */
    const TM_CLASS = 'StoreBundle\\Model\\map\\StoreMaintenanceLogTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 7;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 7;

    /** the column name for the id field */
    const ID = 'store_maintenance_log.id';

    /** the column name for the type field */
    const TYPE = 'store_maintenance_log.type';

    /** the column name for the collection_id field */
    const COLLECTION_ID = 'store_maintenance_log.collection_id';

    /** the column name for the maintenance_store field */
    const MAINTENANCE_STORE = 'store_maintenance_log.maintenance_store';

    /** the column name for the maintenance_by field */
    const MAINTENANCE_BY = 'store_maintenance_log.maintenance_by';

    /** the column name for the maintenance_started_at field */
    const MAINTENANCE_STARTED_AT = 'store_maintenance_log.maintenance_started_at';

    /** the column name for the maintenance_stopped_at field */
    const MAINTENANCE_STOPPED_AT = 'store_maintenance_log.maintenance_stopped_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of StoreMaintenanceLog objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array StoreMaintenanceLog[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. StoreMaintenanceLogPeer::$fieldNames[StoreMaintenanceLogPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Type', 'CollectionId', 'MaintenanceStore', 'MaintenanceBy', 'MaintenanceStartedAt', 'MaintenanceStoppedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'type', 'collectionId', 'maintenanceStore', 'maintenanceBy', 'maintenanceStartedAt', 'maintenanceStoppedAt', ),
        BasePeer::TYPE_COLNAME => array (StoreMaintenanceLogPeer::ID, StoreMaintenanceLogPeer::TYPE, StoreMaintenanceLogPeer::COLLECTION_ID, StoreMaintenanceLogPeer::MAINTENANCE_STORE, StoreMaintenanceLogPeer::MAINTENANCE_BY, StoreMaintenanceLogPeer::MAINTENANCE_STARTED_AT, StoreMaintenanceLogPeer::MAINTENANCE_STOPPED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'TYPE', 'COLLECTION_ID', 'MAINTENANCE_STORE', 'MAINTENANCE_BY', 'MAINTENANCE_STARTED_AT', 'MAINTENANCE_STOPPED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'type', 'collection_id', 'maintenance_store', 'maintenance_by', 'maintenance_started_at', 'maintenance_stopped_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. StoreMaintenanceLogPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Type' => 1, 'CollectionId' => 2, 'MaintenanceStore' => 3, 'MaintenanceBy' => 4, 'MaintenanceStartedAt' => 5, 'MaintenanceStoppedAt' => 6, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'type' => 1, 'collectionId' => 2, 'maintenanceStore' => 3, 'maintenanceBy' => 4, 'maintenanceStartedAt' => 5, 'maintenanceStoppedAt' => 6, ),
        BasePeer::TYPE_COLNAME => array (StoreMaintenanceLogPeer::ID => 0, StoreMaintenanceLogPeer::TYPE => 1, StoreMaintenanceLogPeer::COLLECTION_ID => 2, StoreMaintenanceLogPeer::MAINTENANCE_STORE => 3, StoreMaintenanceLogPeer::MAINTENANCE_BY => 4, StoreMaintenanceLogPeer::MAINTENANCE_STARTED_AT => 5, StoreMaintenanceLogPeer::MAINTENANCE_STOPPED_AT => 6, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'TYPE' => 1, 'COLLECTION_ID' => 2, 'MAINTENANCE_STORE' => 3, 'MAINTENANCE_BY' => 4, 'MAINTENANCE_STARTED_AT' => 5, 'MAINTENANCE_STOPPED_AT' => 6, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'type' => 1, 'collection_id' => 2, 'maintenance_store' => 3, 'maintenance_by' => 4, 'maintenance_started_at' => 5, 'maintenance_stopped_at' => 6, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, )
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
        $toNames = StoreMaintenanceLogPeer::getFieldNames($toType);
        $key = isset(StoreMaintenanceLogPeer::$fieldKeys[$fromType][$name]) ? StoreMaintenanceLogPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(StoreMaintenanceLogPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, StoreMaintenanceLogPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return StoreMaintenanceLogPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. StoreMaintenanceLogPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(StoreMaintenanceLogPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(StoreMaintenanceLogPeer::ID);
            $criteria->addSelectColumn(StoreMaintenanceLogPeer::TYPE);
            $criteria->addSelectColumn(StoreMaintenanceLogPeer::COLLECTION_ID);
            $criteria->addSelectColumn(StoreMaintenanceLogPeer::MAINTENANCE_STORE);
            $criteria->addSelectColumn(StoreMaintenanceLogPeer::MAINTENANCE_BY);
            $criteria->addSelectColumn(StoreMaintenanceLogPeer::MAINTENANCE_STARTED_AT);
            $criteria->addSelectColumn(StoreMaintenanceLogPeer::MAINTENANCE_STOPPED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.collection_id');
            $criteria->addSelectColumn($alias . '.maintenance_store');
            $criteria->addSelectColumn($alias . '.maintenance_by');
            $criteria->addSelectColumn($alias . '.maintenance_started_at');
            $criteria->addSelectColumn($alias . '.maintenance_stopped_at');
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
        $criteria->setPrimaryTableName(StoreMaintenanceLogPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StoreMaintenanceLogPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return StoreMaintenanceLog
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = StoreMaintenanceLogPeer::doSelect($critcopy, $con);
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
        return StoreMaintenanceLogPeer::populateObjects(StoreMaintenanceLogPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            StoreMaintenanceLogPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);

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
     * @param StoreMaintenanceLog $obj A StoreMaintenanceLog object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            StoreMaintenanceLogPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A StoreMaintenanceLog object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof StoreMaintenanceLog) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or StoreMaintenanceLog object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(StoreMaintenanceLogPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return StoreMaintenanceLog Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(StoreMaintenanceLogPeer::$instances[$key])) {
                return StoreMaintenanceLogPeer::$instances[$key];
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
        foreach (StoreMaintenanceLogPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        StoreMaintenanceLogPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to store_maintenance_log
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
        $cls = StoreMaintenanceLogPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = StoreMaintenanceLogPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = StoreMaintenanceLogPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                StoreMaintenanceLogPeer::addInstanceToPool($obj, $key);
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
     * @return array (StoreMaintenanceLog object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = StoreMaintenanceLogPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = StoreMaintenanceLogPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + StoreMaintenanceLogPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = StoreMaintenanceLogPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            StoreMaintenanceLogPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related MaintenanceType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinMaintenanceType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StoreMaintenanceLogPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StoreMaintenanceLogPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StoreMaintenanceLogPeer::TYPE, MaintenanceTypePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Collection table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCollection(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StoreMaintenanceLogPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StoreMaintenanceLogPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StoreMaintenanceLogPeer::COLLECTION_ID, CollectionPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(StoreMaintenanceLogPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StoreMaintenanceLogPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_STORE, StorePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related User table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StoreMaintenanceLogPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StoreMaintenanceLogPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_BY, UserPeer::ID, $join_behavior);

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
     * Selects a collection of StoreMaintenanceLog objects pre-filled with their MaintenanceType objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StoreMaintenanceLog objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinMaintenanceType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);
        }

        StoreMaintenanceLogPeer::addSelectColumns($criteria);
        $startcol = StoreMaintenanceLogPeer::NUM_HYDRATE_COLUMNS;
        MaintenanceTypePeer::addSelectColumns($criteria);

        $criteria->addJoin(StoreMaintenanceLogPeer::TYPE, MaintenanceTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StoreMaintenanceLogPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StoreMaintenanceLogPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = StoreMaintenanceLogPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StoreMaintenanceLogPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = MaintenanceTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = MaintenanceTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = MaintenanceTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    MaintenanceTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to $obj2 (MaintenanceType)
                $obj2->addStoreMaintenanceLog($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of StoreMaintenanceLog objects pre-filled with their Collection objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StoreMaintenanceLog objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCollection(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);
        }

        StoreMaintenanceLogPeer::addSelectColumns($criteria);
        $startcol = StoreMaintenanceLogPeer::NUM_HYDRATE_COLUMNS;
        CollectionPeer::addSelectColumns($criteria);

        $criteria->addJoin(StoreMaintenanceLogPeer::COLLECTION_ID, CollectionPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StoreMaintenanceLogPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StoreMaintenanceLogPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = StoreMaintenanceLogPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StoreMaintenanceLogPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CollectionPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CollectionPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CollectionPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CollectionPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to $obj2 (Collection)
                $obj2->addStoreMaintenanceLog($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of StoreMaintenanceLog objects pre-filled with their Store objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StoreMaintenanceLog objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinStore(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);
        }

        StoreMaintenanceLogPeer::addSelectColumns($criteria);
        $startcol = StoreMaintenanceLogPeer::NUM_HYDRATE_COLUMNS;
        StorePeer::addSelectColumns($criteria);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_STORE, StorePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StoreMaintenanceLogPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StoreMaintenanceLogPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = StoreMaintenanceLogPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StoreMaintenanceLogPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (StoreMaintenanceLog) to $obj2 (Store)
                $obj2->addStoreMaintenanceLog($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of StoreMaintenanceLog objects pre-filled with their User objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StoreMaintenanceLog objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);
        }

        StoreMaintenanceLogPeer::addSelectColumns($criteria);
        $startcol = StoreMaintenanceLogPeer::NUM_HYDRATE_COLUMNS;
        UserPeer::addSelectColumns($criteria);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_BY, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StoreMaintenanceLogPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StoreMaintenanceLogPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = StoreMaintenanceLogPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StoreMaintenanceLogPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = UserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to $obj2 (User)
                $obj2->addStoreMaintenanceLog($obj1);

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
        $criteria->setPrimaryTableName(StoreMaintenanceLogPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StoreMaintenanceLogPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StoreMaintenanceLogPeer::TYPE, MaintenanceTypePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::COLLECTION_ID, CollectionPeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_BY, UserPeer::ID, $join_behavior);

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
     * Selects a collection of StoreMaintenanceLog objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StoreMaintenanceLog objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);
        }

        StoreMaintenanceLogPeer::addSelectColumns($criteria);
        $startcol2 = StoreMaintenanceLogPeer::NUM_HYDRATE_COLUMNS;

        MaintenanceTypePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + MaintenanceTypePeer::NUM_HYDRATE_COLUMNS;

        CollectionPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CollectionPeer::NUM_HYDRATE_COLUMNS;

        StorePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + StorePeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + UserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StoreMaintenanceLogPeer::TYPE, MaintenanceTypePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::COLLECTION_ID, CollectionPeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_BY, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StoreMaintenanceLogPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StoreMaintenanceLogPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StoreMaintenanceLogPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StoreMaintenanceLogPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined MaintenanceType rows

            $key2 = MaintenanceTypePeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = MaintenanceTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = MaintenanceTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    MaintenanceTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj2 (MaintenanceType)
                $obj2->addStoreMaintenanceLog($obj1);
            } // if joined row not null

            // Add objects for joined Collection rows

            $key3 = CollectionPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = CollectionPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = CollectionPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    CollectionPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj3 (Collection)
                $obj3->addStoreMaintenanceLog($obj1);
            } // if joined row not null

            // Add objects for joined Store rows

            $key4 = StorePeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = StorePeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = StorePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    StorePeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj4 (Store)
                $obj4->addStoreMaintenanceLog($obj1);
            } // if joined row not null

            // Add objects for joined User rows

            $key5 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = UserPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = UserPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    UserPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj5 (User)
                $obj5->addStoreMaintenanceLog($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related MaintenanceType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptMaintenanceType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StoreMaintenanceLogPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StoreMaintenanceLogPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StoreMaintenanceLogPeer::COLLECTION_ID, CollectionPeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_BY, UserPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Collection table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCollection(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StoreMaintenanceLogPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StoreMaintenanceLogPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StoreMaintenanceLogPeer::TYPE, MaintenanceTypePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_BY, UserPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(StoreMaintenanceLogPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StoreMaintenanceLogPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StoreMaintenanceLogPeer::TYPE, MaintenanceTypePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::COLLECTION_ID, CollectionPeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_BY, UserPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related User table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StoreMaintenanceLogPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StoreMaintenanceLogPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StoreMaintenanceLogPeer::TYPE, MaintenanceTypePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::COLLECTION_ID, CollectionPeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_STORE, StorePeer::ID, $join_behavior);

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
     * Selects a collection of StoreMaintenanceLog objects pre-filled with all related objects except MaintenanceType.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StoreMaintenanceLog objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptMaintenanceType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);
        }

        StoreMaintenanceLogPeer::addSelectColumns($criteria);
        $startcol2 = StoreMaintenanceLogPeer::NUM_HYDRATE_COLUMNS;

        CollectionPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CollectionPeer::NUM_HYDRATE_COLUMNS;

        StorePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + StorePeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + UserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StoreMaintenanceLogPeer::COLLECTION_ID, CollectionPeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_BY, UserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StoreMaintenanceLogPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StoreMaintenanceLogPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StoreMaintenanceLogPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StoreMaintenanceLogPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Collection rows

                $key2 = CollectionPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CollectionPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CollectionPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CollectionPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj2 (Collection)
                $obj2->addStoreMaintenanceLog($obj1);

            } // if joined row is not null

                // Add objects for joined Store rows

                $key3 = StorePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = StorePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = StorePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    StorePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj3 (Store)
                $obj3->addStoreMaintenanceLog($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key4 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = UserPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = UserPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    UserPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj4 (User)
                $obj4->addStoreMaintenanceLog($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of StoreMaintenanceLog objects pre-filled with all related objects except Collection.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StoreMaintenanceLog objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCollection(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);
        }

        StoreMaintenanceLogPeer::addSelectColumns($criteria);
        $startcol2 = StoreMaintenanceLogPeer::NUM_HYDRATE_COLUMNS;

        MaintenanceTypePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + MaintenanceTypePeer::NUM_HYDRATE_COLUMNS;

        StorePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + StorePeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + UserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StoreMaintenanceLogPeer::TYPE, MaintenanceTypePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_BY, UserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StoreMaintenanceLogPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StoreMaintenanceLogPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StoreMaintenanceLogPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StoreMaintenanceLogPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined MaintenanceType rows

                $key2 = MaintenanceTypePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = MaintenanceTypePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = MaintenanceTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    MaintenanceTypePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj2 (MaintenanceType)
                $obj2->addStoreMaintenanceLog($obj1);

            } // if joined row is not null

                // Add objects for joined Store rows

                $key3 = StorePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = StorePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = StorePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    StorePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj3 (Store)
                $obj3->addStoreMaintenanceLog($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key4 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = UserPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = UserPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    UserPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj4 (User)
                $obj4->addStoreMaintenanceLog($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of StoreMaintenanceLog objects pre-filled with all related objects except Store.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StoreMaintenanceLog objects.
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
            $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);
        }

        StoreMaintenanceLogPeer::addSelectColumns($criteria);
        $startcol2 = StoreMaintenanceLogPeer::NUM_HYDRATE_COLUMNS;

        MaintenanceTypePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + MaintenanceTypePeer::NUM_HYDRATE_COLUMNS;

        CollectionPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CollectionPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + UserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StoreMaintenanceLogPeer::TYPE, MaintenanceTypePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::COLLECTION_ID, CollectionPeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_BY, UserPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StoreMaintenanceLogPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StoreMaintenanceLogPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StoreMaintenanceLogPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StoreMaintenanceLogPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined MaintenanceType rows

                $key2 = MaintenanceTypePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = MaintenanceTypePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = MaintenanceTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    MaintenanceTypePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj2 (MaintenanceType)
                $obj2->addStoreMaintenanceLog($obj1);

            } // if joined row is not null

                // Add objects for joined Collection rows

                $key3 = CollectionPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = CollectionPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = CollectionPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    CollectionPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj3 (Collection)
                $obj3->addStoreMaintenanceLog($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key4 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = UserPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = UserPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    UserPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj4 (User)
                $obj4->addStoreMaintenanceLog($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of StoreMaintenanceLog objects pre-filled with all related objects except User.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of StoreMaintenanceLog objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);
        }

        StoreMaintenanceLogPeer::addSelectColumns($criteria);
        $startcol2 = StoreMaintenanceLogPeer::NUM_HYDRATE_COLUMNS;

        MaintenanceTypePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + MaintenanceTypePeer::NUM_HYDRATE_COLUMNS;

        CollectionPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CollectionPeer::NUM_HYDRATE_COLUMNS;

        StorePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + StorePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StoreMaintenanceLogPeer::TYPE, MaintenanceTypePeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::COLLECTION_ID, CollectionPeer::ID, $join_behavior);

        $criteria->addJoin(StoreMaintenanceLogPeer::MAINTENANCE_STORE, StorePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StoreMaintenanceLogPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StoreMaintenanceLogPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StoreMaintenanceLogPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StoreMaintenanceLogPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined MaintenanceType rows

                $key2 = MaintenanceTypePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = MaintenanceTypePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = MaintenanceTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    MaintenanceTypePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj2 (MaintenanceType)
                $obj2->addStoreMaintenanceLog($obj1);

            } // if joined row is not null

                // Add objects for joined Collection rows

                $key3 = CollectionPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = CollectionPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = CollectionPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    CollectionPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj3 (Collection)
                $obj3->addStoreMaintenanceLog($obj1);

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

                // Add the $obj1 (StoreMaintenanceLog) to the collection in $obj4 (Store)
                $obj4->addStoreMaintenanceLog($obj1);

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
        return Propel::getDatabaseMap(StoreMaintenanceLogPeer::DATABASE_NAME)->getTable(StoreMaintenanceLogPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseStoreMaintenanceLogPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseStoreMaintenanceLogPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \StoreBundle\Model\map\StoreMaintenanceLogTableMap());
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
        return StoreMaintenanceLogPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a StoreMaintenanceLog or Criteria object.
     *
     * @param      mixed $values Criteria or StoreMaintenanceLog object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from StoreMaintenanceLog object
        }

        if ($criteria->containsKey(StoreMaintenanceLogPeer::ID) && $criteria->keyContainsValue(StoreMaintenanceLogPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.StoreMaintenanceLogPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a StoreMaintenanceLog or Criteria object.
     *
     * @param      mixed $values Criteria or StoreMaintenanceLog object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(StoreMaintenanceLogPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(StoreMaintenanceLogPeer::ID);
            $value = $criteria->remove(StoreMaintenanceLogPeer::ID);
            if ($value) {
                $selectCriteria->add(StoreMaintenanceLogPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(StoreMaintenanceLogPeer::TABLE_NAME);
            }

        } else { // $values is StoreMaintenanceLog object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the store_maintenance_log table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(StoreMaintenanceLogPeer::TABLE_NAME, $con, StoreMaintenanceLogPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            StoreMaintenanceLogPeer::clearInstancePool();
            StoreMaintenanceLogPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a StoreMaintenanceLog or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or StoreMaintenanceLog object or primary key or array of primary keys
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
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            StoreMaintenanceLogPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof StoreMaintenanceLog) { // it's a model object
            // invalidate the cache for this single object
            StoreMaintenanceLogPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(StoreMaintenanceLogPeer::DATABASE_NAME);
            $criteria->add(StoreMaintenanceLogPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                StoreMaintenanceLogPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(StoreMaintenanceLogPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            StoreMaintenanceLogPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given StoreMaintenanceLog object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param StoreMaintenanceLog $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(StoreMaintenanceLogPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(StoreMaintenanceLogPeer::TABLE_NAME);

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

        return BasePeer::doValidate(StoreMaintenanceLogPeer::DATABASE_NAME, StoreMaintenanceLogPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return StoreMaintenanceLog
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = StoreMaintenanceLogPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(StoreMaintenanceLogPeer::DATABASE_NAME);
        $criteria->add(StoreMaintenanceLogPeer::ID, $pk);

        $v = StoreMaintenanceLogPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return StoreMaintenanceLog[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(StoreMaintenanceLogPeer::DATABASE_NAME);
            $criteria->add(StoreMaintenanceLogPeer::ID, $pks, Criteria::IN);
            $objs = StoreMaintenanceLogPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseStoreMaintenanceLogPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseStoreMaintenanceLogPeer::buildTableMap();

