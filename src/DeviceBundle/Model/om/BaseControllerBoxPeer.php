<?php

namespace DeviceBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use DeviceBundle\Model\ControllerBox;
use DeviceBundle\Model\ControllerBoxPeer;
use DeviceBundle\Model\DeviceGroupPeer;
use DeviceBundle\Model\map\ControllerBoxTableMap;
use StoreBundle\Model\StorePeer;

abstract class BaseControllerBoxPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'controller_box';

    /** the related Propel class for this table */
    const OM_CLASS = 'DeviceBundle\\Model\\ControllerBox';

    /** the related TableMap class for this table */
    const TM_CLASS = 'DeviceBundle\\Model\\map\\ControllerBoxTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 13;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 13;

    /** the column name for the id field */
    const ID = 'controller_box.id';

    /** the column name for the group field */
    const GROUP = 'controller_box.group';

    /** the column name for the main_store field */
    const MAIN_STORE = 'controller_box.main_store';

    /** the column name for the version field */
    const VERSION = 'controller_box.version';

    /** the column name for the name field */
    const NAME = 'controller_box.name';

    /** the column name for the description field */
    const DESCRIPTION = 'controller_box.description';

    /** the column name for the internal_temperature field */
    const INTERNAL_TEMPERATURE = 'controller_box.internal_temperature';

    /** the column name for the uid field */
    const UID = 'controller_box.uid';

    /** the column name for the position field */
    const POSITION = 'controller_box.position';

    /** the column name for the is_enabled field */
    const IS_ENABLED = 'controller_box.is_enabled';

    /** the column name for the is_deleted field */
    const IS_DELETED = 'controller_box.is_deleted';

    /** the column name for the created_at field */
    const CREATED_AT = 'controller_box.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'controller_box.updated_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of ControllerBox objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array ControllerBox[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. ControllerBoxPeer::$fieldNames[ControllerBoxPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Group', 'MainStore', 'Version', 'Name', 'Description', 'InternalTemperature', 'Uid', 'Position', 'IsEnabled', 'IsDeleted', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'group', 'mainStore', 'version', 'name', 'description', 'internalTemperature', 'uid', 'position', 'isEnabled', 'isDeleted', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (ControllerBoxPeer::ID, ControllerBoxPeer::GROUP, ControllerBoxPeer::MAIN_STORE, ControllerBoxPeer::VERSION, ControllerBoxPeer::NAME, ControllerBoxPeer::DESCRIPTION, ControllerBoxPeer::INTERNAL_TEMPERATURE, ControllerBoxPeer::UID, ControllerBoxPeer::POSITION, ControllerBoxPeer::IS_ENABLED, ControllerBoxPeer::IS_DELETED, ControllerBoxPeer::CREATED_AT, ControllerBoxPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'GROUP', 'MAIN_STORE', 'VERSION', 'NAME', 'DESCRIPTION', 'INTERNAL_TEMPERATURE', 'UID', 'POSITION', 'IS_ENABLED', 'IS_DELETED', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'group', 'main_store', 'version', 'name', 'description', 'internal_temperature', 'uid', 'position', 'is_enabled', 'is_deleted', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. ControllerBoxPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Group' => 1, 'MainStore' => 2, 'Version' => 3, 'Name' => 4, 'Description' => 5, 'InternalTemperature' => 6, 'Uid' => 7, 'Position' => 8, 'IsEnabled' => 9, 'IsDeleted' => 10, 'CreatedAt' => 11, 'UpdatedAt' => 12, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'group' => 1, 'mainStore' => 2, 'version' => 3, 'name' => 4, 'description' => 5, 'internalTemperature' => 6, 'uid' => 7, 'position' => 8, 'isEnabled' => 9, 'isDeleted' => 10, 'createdAt' => 11, 'updatedAt' => 12, ),
        BasePeer::TYPE_COLNAME => array (ControllerBoxPeer::ID => 0, ControllerBoxPeer::GROUP => 1, ControllerBoxPeer::MAIN_STORE => 2, ControllerBoxPeer::VERSION => 3, ControllerBoxPeer::NAME => 4, ControllerBoxPeer::DESCRIPTION => 5, ControllerBoxPeer::INTERNAL_TEMPERATURE => 6, ControllerBoxPeer::UID => 7, ControllerBoxPeer::POSITION => 8, ControllerBoxPeer::IS_ENABLED => 9, ControllerBoxPeer::IS_DELETED => 10, ControllerBoxPeer::CREATED_AT => 11, ControllerBoxPeer::UPDATED_AT => 12, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'GROUP' => 1, 'MAIN_STORE' => 2, 'VERSION' => 3, 'NAME' => 4, 'DESCRIPTION' => 5, 'INTERNAL_TEMPERATURE' => 6, 'UID' => 7, 'POSITION' => 8, 'IS_ENABLED' => 9, 'IS_DELETED' => 10, 'CREATED_AT' => 11, 'UPDATED_AT' => 12, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'group' => 1, 'main_store' => 2, 'version' => 3, 'name' => 4, 'description' => 5, 'internal_temperature' => 6, 'uid' => 7, 'position' => 8, 'is_enabled' => 9, 'is_deleted' => 10, 'created_at' => 11, 'updated_at' => 12, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
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
        $toNames = ControllerBoxPeer::getFieldNames($toType);
        $key = isset(ControllerBoxPeer::$fieldKeys[$fromType][$name]) ? ControllerBoxPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(ControllerBoxPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, ControllerBoxPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return ControllerBoxPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. ControllerBoxPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(ControllerBoxPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(ControllerBoxPeer::ID);
            $criteria->addSelectColumn(ControllerBoxPeer::GROUP);
            $criteria->addSelectColumn(ControllerBoxPeer::MAIN_STORE);
            $criteria->addSelectColumn(ControllerBoxPeer::VERSION);
            $criteria->addSelectColumn(ControllerBoxPeer::NAME);
            $criteria->addSelectColumn(ControllerBoxPeer::DESCRIPTION);
            $criteria->addSelectColumn(ControllerBoxPeer::INTERNAL_TEMPERATURE);
            $criteria->addSelectColumn(ControllerBoxPeer::UID);
            $criteria->addSelectColumn(ControllerBoxPeer::POSITION);
            $criteria->addSelectColumn(ControllerBoxPeer::IS_ENABLED);
            $criteria->addSelectColumn(ControllerBoxPeer::IS_DELETED);
            $criteria->addSelectColumn(ControllerBoxPeer::CREATED_AT);
            $criteria->addSelectColumn(ControllerBoxPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.group');
            $criteria->addSelectColumn($alias . '.main_store');
            $criteria->addSelectColumn($alias . '.version');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.internal_temperature');
            $criteria->addSelectColumn($alias . '.uid');
            $criteria->addSelectColumn($alias . '.position');
            $criteria->addSelectColumn($alias . '.is_enabled');
            $criteria->addSelectColumn($alias . '.is_deleted');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
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
        $criteria->setPrimaryTableName(ControllerBoxPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ControllerBoxPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return ControllerBox
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = ControllerBoxPeer::doSelect($critcopy, $con);
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
        return ControllerBoxPeer::populateObjects(ControllerBoxPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            ControllerBoxPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);

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
     * @param ControllerBox $obj A ControllerBox object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            ControllerBoxPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A ControllerBox object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof ControllerBox) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or ControllerBox object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(ControllerBoxPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return ControllerBox Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(ControllerBoxPeer::$instances[$key])) {
                return ControllerBoxPeer::$instances[$key];
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
        foreach (ControllerBoxPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        ControllerBoxPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to controller_box
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
        $cls = ControllerBoxPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = ControllerBoxPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = ControllerBoxPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ControllerBoxPeer::addInstanceToPool($obj, $key);
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
     * @return array (ControllerBox object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = ControllerBoxPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = ControllerBoxPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + ControllerBoxPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ControllerBoxPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            ControllerBoxPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
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
        $criteria->setPrimaryTableName(ControllerBoxPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ControllerBoxPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ControllerBoxPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(ControllerBoxPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ControllerBoxPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ControllerBoxPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

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
     * Selects a collection of ControllerBox objects pre-filled with their Store objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ControllerBox objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinStore(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);
        }

        ControllerBoxPeer::addSelectColumns($criteria);
        $startcol = ControllerBoxPeer::NUM_HYDRATE_COLUMNS;
        StorePeer::addSelectColumns($criteria);

        $criteria->addJoin(ControllerBoxPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ControllerBoxPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ControllerBoxPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ControllerBoxPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ControllerBoxPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (ControllerBox) to $obj2 (Store)
                $obj2->addControllerBox($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of ControllerBox objects pre-filled with their DeviceGroup objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ControllerBox objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinDeviceGroup(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);
        }

        ControllerBoxPeer::addSelectColumns($criteria);
        $startcol = ControllerBoxPeer::NUM_HYDRATE_COLUMNS;
        DeviceGroupPeer::addSelectColumns($criteria);

        $criteria->addJoin(ControllerBoxPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ControllerBoxPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ControllerBoxPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ControllerBoxPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ControllerBoxPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (ControllerBox) to $obj2 (DeviceGroup)
                $obj2->addControllerBox($obj1);

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
        $criteria->setPrimaryTableName(ControllerBoxPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ControllerBoxPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ControllerBoxPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(ControllerBoxPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

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
     * Selects a collection of ControllerBox objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ControllerBox objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);
        }

        ControllerBoxPeer::addSelectColumns($criteria);
        $startcol2 = ControllerBoxPeer::NUM_HYDRATE_COLUMNS;

        StorePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StorePeer::NUM_HYDRATE_COLUMNS;

        DeviceGroupPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + DeviceGroupPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ControllerBoxPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(ControllerBoxPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ControllerBoxPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ControllerBoxPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ControllerBoxPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ControllerBoxPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Store rows

            $key2 = StorePeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = StorePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = StorePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    StorePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (ControllerBox) to the collection in $obj2 (Store)
                $obj2->addControllerBox($obj1);
            } // if joined row not null

            // Add objects for joined DeviceGroup rows

            $key3 = DeviceGroupPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = DeviceGroupPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = DeviceGroupPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    DeviceGroupPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (ControllerBox) to the collection in $obj3 (DeviceGroup)
                $obj3->addControllerBox($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
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
        $criteria->setPrimaryTableName(ControllerBoxPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ControllerBoxPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ControllerBoxPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(ControllerBoxPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ControllerBoxPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ControllerBoxPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

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
     * Selects a collection of ControllerBox objects pre-filled with all related objects except Store.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ControllerBox objects.
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
            $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);
        }

        ControllerBoxPeer::addSelectColumns($criteria);
        $startcol2 = ControllerBoxPeer::NUM_HYDRATE_COLUMNS;

        DeviceGroupPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + DeviceGroupPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ControllerBoxPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ControllerBoxPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ControllerBoxPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ControllerBoxPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ControllerBoxPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined DeviceGroup rows

                $key2 = DeviceGroupPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = DeviceGroupPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = DeviceGroupPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    DeviceGroupPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (ControllerBox) to the collection in $obj2 (DeviceGroup)
                $obj2->addControllerBox($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of ControllerBox objects pre-filled with all related objects except DeviceGroup.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ControllerBox objects.
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
            $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);
        }

        ControllerBoxPeer::addSelectColumns($criteria);
        $startcol2 = ControllerBoxPeer::NUM_HYDRATE_COLUMNS;

        StorePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StorePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ControllerBoxPeer::MAIN_STORE, StorePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ControllerBoxPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ControllerBoxPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ControllerBoxPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ControllerBoxPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Store rows

                $key2 = StorePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = StorePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = StorePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    StorePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (ControllerBox) to the collection in $obj2 (Store)
                $obj2->addControllerBox($obj1);

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
        return Propel::getDatabaseMap(ControllerBoxPeer::DATABASE_NAME)->getTable(ControllerBoxPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseControllerBoxPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseControllerBoxPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \DeviceBundle\Model\map\ControllerBoxTableMap());
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
        return ControllerBoxPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a ControllerBox or Criteria object.
     *
     * @param      mixed $values Criteria or ControllerBox object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from ControllerBox object
        }

        if ($criteria->containsKey(ControllerBoxPeer::ID) && $criteria->keyContainsValue(ControllerBoxPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ControllerBoxPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a ControllerBox or Criteria object.
     *
     * @param      mixed $values Criteria or ControllerBox object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(ControllerBoxPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(ControllerBoxPeer::ID);
            $value = $criteria->remove(ControllerBoxPeer::ID);
            if ($value) {
                $selectCriteria->add(ControllerBoxPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(ControllerBoxPeer::TABLE_NAME);
            }

        } else { // $values is ControllerBox object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the controller_box table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(ControllerBoxPeer::TABLE_NAME, $con, ControllerBoxPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ControllerBoxPeer::clearInstancePool();
            ControllerBoxPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a ControllerBox or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or ControllerBox object or primary key or array of primary keys
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
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            ControllerBoxPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof ControllerBox) { // it's a model object
            // invalidate the cache for this single object
            ControllerBoxPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ControllerBoxPeer::DATABASE_NAME);
            $criteria->add(ControllerBoxPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                ControllerBoxPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(ControllerBoxPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            ControllerBoxPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given ControllerBox object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param ControllerBox $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(ControllerBoxPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(ControllerBoxPeer::TABLE_NAME);

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

        return BasePeer::doValidate(ControllerBoxPeer::DATABASE_NAME, ControllerBoxPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return ControllerBox
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = ControllerBoxPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(ControllerBoxPeer::DATABASE_NAME);
        $criteria->add(ControllerBoxPeer::ID, $pk);

        $v = ControllerBoxPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return ControllerBox[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(ControllerBoxPeer::DATABASE_NAME);
            $criteria->add(ControllerBoxPeer::ID, $pks, Criteria::IN);
            $objs = ControllerBoxPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseControllerBoxPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseControllerBoxPeer::buildTableMap();

