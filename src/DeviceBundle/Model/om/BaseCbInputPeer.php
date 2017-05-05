<?php

namespace DeviceBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use DeviceBundle\Model\CbInput;
use DeviceBundle\Model\CbInputPeer;
use DeviceBundle\Model\ControllerBoxPeer;
use DeviceBundle\Model\DeviceGroupPeer;
use DeviceBundle\Model\map\CbInputTableMap;
use StoreBundle\Model\StorePeer;

abstract class BaseCbInputPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'cb_input';

    /** the related Propel class for this table */
    const OM_CLASS = 'DeviceBundle\\Model\\CbInput';

    /** the related TableMap class for this table */
    const TM_CLASS = 'DeviceBundle\\Model\\map\\CbInputTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 19;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 19;

    /** the column name for the id field */
    const ID = 'cb_input.id';

    /** the column name for the uid field */
    const UID = 'cb_input.uid';

    /** the column name for the input_number field */
    const INPUT_NUMBER = 'cb_input.input_number';

    /** the column name for the group field */
    const GROUP = 'cb_input.group';

    /** the column name for the controller field */
    const CONTROLLER = 'cb_input.controller';

    /** the column name for the main_store field */
    const MAIN_STORE = 'cb_input.main_store';

    /** the column name for the name field */
    const NAME = 'cb_input.name';

    /** the column name for the description field */
    const DESCRIPTION = 'cb_input.description';

    /** the column name for the state field */
    const STATE = 'cb_input.state';

    /** the column name for the switch_when field */
    const SWITCH_WHEN = 'cb_input.switch_when';

    /** the column name for the switch_state field */
    const SWITCH_STATE = 'cb_input.switch_state';

    /** the column name for the position field */
    const POSITION = 'cb_input.position';

    /** the column name for the data_collected_at field */
    const DATA_COLLECTED_AT = 'cb_input.data_collected_at';

    /** the column name for the notify_after field */
    const NOTIFY_AFTER = 'cb_input.notify_after';

    /** the column name for the notify_started_at field */
    const NOTIFY_STARTED_AT = 'cb_input.notify_started_at';

    /** the column name for the notification field */
    const NOTIFICATION = 'cb_input.notification';

    /** the column name for the is_enabled field */
    const IS_ENABLED = 'cb_input.is_enabled';

    /** the column name for the created_at field */
    const CREATED_AT = 'cb_input.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'cb_input.updated_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of CbInput objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array CbInput[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. CbInputPeer::$fieldNames[CbInputPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Uid', 'InputNumber', 'Group', 'Controller', 'MainStore', 'Name', 'Description', 'State', 'SwitchWhen', 'SwitchState', 'Position', 'DataCollectedAt', 'NotifyAfter', 'NotifyStartedAt', 'Notification', 'IsEnabled', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'uid', 'inputNumber', 'group', 'controller', 'mainStore', 'name', 'description', 'state', 'switchWhen', 'switchState', 'position', 'dataCollectedAt', 'notifyAfter', 'notifyStartedAt', 'notification', 'isEnabled', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (CbInputPeer::ID, CbInputPeer::UID, CbInputPeer::INPUT_NUMBER, CbInputPeer::GROUP, CbInputPeer::CONTROLLER, CbInputPeer::MAIN_STORE, CbInputPeer::NAME, CbInputPeer::DESCRIPTION, CbInputPeer::STATE, CbInputPeer::SWITCH_WHEN, CbInputPeer::SWITCH_STATE, CbInputPeer::POSITION, CbInputPeer::DATA_COLLECTED_AT, CbInputPeer::NOTIFY_AFTER, CbInputPeer::NOTIFY_STARTED_AT, CbInputPeer::NOTIFICATION, CbInputPeer::IS_ENABLED, CbInputPeer::CREATED_AT, CbInputPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'UID', 'INPUT_NUMBER', 'GROUP', 'CONTROLLER', 'MAIN_STORE', 'NAME', 'DESCRIPTION', 'STATE', 'SWITCH_WHEN', 'SWITCH_STATE', 'POSITION', 'DATA_COLLECTED_AT', 'NOTIFY_AFTER', 'NOTIFY_STARTED_AT', 'NOTIFICATION', 'IS_ENABLED', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'uid', 'input_number', 'group', 'controller', 'main_store', 'name', 'description', 'state', 'switch_when', 'switch_state', 'position', 'data_collected_at', 'notify_after', 'notify_started_at', 'notification', 'is_enabled', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. CbInputPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Uid' => 1, 'InputNumber' => 2, 'Group' => 3, 'Controller' => 4, 'MainStore' => 5, 'Name' => 6, 'Description' => 7, 'State' => 8, 'SwitchWhen' => 9, 'SwitchState' => 10, 'Position' => 11, 'DataCollectedAt' => 12, 'NotifyAfter' => 13, 'NotifyStartedAt' => 14, 'Notification' => 15, 'IsEnabled' => 16, 'CreatedAt' => 17, 'UpdatedAt' => 18, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'uid' => 1, 'inputNumber' => 2, 'group' => 3, 'controller' => 4, 'mainStore' => 5, 'name' => 6, 'description' => 7, 'state' => 8, 'switchWhen' => 9, 'switchState' => 10, 'position' => 11, 'dataCollectedAt' => 12, 'notifyAfter' => 13, 'notifyStartedAt' => 14, 'notification' => 15, 'isEnabled' => 16, 'createdAt' => 17, 'updatedAt' => 18, ),
        BasePeer::TYPE_COLNAME => array (CbInputPeer::ID => 0, CbInputPeer::UID => 1, CbInputPeer::INPUT_NUMBER => 2, CbInputPeer::GROUP => 3, CbInputPeer::CONTROLLER => 4, CbInputPeer::MAIN_STORE => 5, CbInputPeer::NAME => 6, CbInputPeer::DESCRIPTION => 7, CbInputPeer::STATE => 8, CbInputPeer::SWITCH_WHEN => 9, CbInputPeer::SWITCH_STATE => 10, CbInputPeer::POSITION => 11, CbInputPeer::DATA_COLLECTED_AT => 12, CbInputPeer::NOTIFY_AFTER => 13, CbInputPeer::NOTIFY_STARTED_AT => 14, CbInputPeer::NOTIFICATION => 15, CbInputPeer::IS_ENABLED => 16, CbInputPeer::CREATED_AT => 17, CbInputPeer::UPDATED_AT => 18, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'UID' => 1, 'INPUT_NUMBER' => 2, 'GROUP' => 3, 'CONTROLLER' => 4, 'MAIN_STORE' => 5, 'NAME' => 6, 'DESCRIPTION' => 7, 'STATE' => 8, 'SWITCH_WHEN' => 9, 'SWITCH_STATE' => 10, 'POSITION' => 11, 'DATA_COLLECTED_AT' => 12, 'NOTIFY_AFTER' => 13, 'NOTIFY_STARTED_AT' => 14, 'NOTIFICATION' => 15, 'IS_ENABLED' => 16, 'CREATED_AT' => 17, 'UPDATED_AT' => 18, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'uid' => 1, 'input_number' => 2, 'group' => 3, 'controller' => 4, 'main_store' => 5, 'name' => 6, 'description' => 7, 'state' => 8, 'switch_when' => 9, 'switch_state' => 10, 'position' => 11, 'data_collected_at' => 12, 'notify_after' => 13, 'notify_started_at' => 14, 'notification' => 15, 'is_enabled' => 16, 'created_at' => 17, 'updated_at' => 18, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
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
        $toNames = CbInputPeer::getFieldNames($toType);
        $key = isset(CbInputPeer::$fieldKeys[$fromType][$name]) ? CbInputPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(CbInputPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, CbInputPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return CbInputPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. CbInputPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(CbInputPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(CbInputPeer::ID);
            $criteria->addSelectColumn(CbInputPeer::UID);
            $criteria->addSelectColumn(CbInputPeer::INPUT_NUMBER);
            $criteria->addSelectColumn(CbInputPeer::GROUP);
            $criteria->addSelectColumn(CbInputPeer::CONTROLLER);
            $criteria->addSelectColumn(CbInputPeer::MAIN_STORE);
            $criteria->addSelectColumn(CbInputPeer::NAME);
            $criteria->addSelectColumn(CbInputPeer::DESCRIPTION);
            $criteria->addSelectColumn(CbInputPeer::STATE);
            $criteria->addSelectColumn(CbInputPeer::SWITCH_WHEN);
            $criteria->addSelectColumn(CbInputPeer::SWITCH_STATE);
            $criteria->addSelectColumn(CbInputPeer::POSITION);
            $criteria->addSelectColumn(CbInputPeer::DATA_COLLECTED_AT);
            $criteria->addSelectColumn(CbInputPeer::NOTIFY_AFTER);
            $criteria->addSelectColumn(CbInputPeer::NOTIFY_STARTED_AT);
            $criteria->addSelectColumn(CbInputPeer::NOTIFICATION);
            $criteria->addSelectColumn(CbInputPeer::IS_ENABLED);
            $criteria->addSelectColumn(CbInputPeer::CREATED_AT);
            $criteria->addSelectColumn(CbInputPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.uid');
            $criteria->addSelectColumn($alias . '.input_number');
            $criteria->addSelectColumn($alias . '.group');
            $criteria->addSelectColumn($alias . '.controller');
            $criteria->addSelectColumn($alias . '.main_store');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.state');
            $criteria->addSelectColumn($alias . '.switch_when');
            $criteria->addSelectColumn($alias . '.switch_state');
            $criteria->addSelectColumn($alias . '.position');
            $criteria->addSelectColumn($alias . '.data_collected_at');
            $criteria->addSelectColumn($alias . '.notify_after');
            $criteria->addSelectColumn($alias . '.notify_started_at');
            $criteria->addSelectColumn($alias . '.notification');
            $criteria->addSelectColumn($alias . '.is_enabled');
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
        $criteria->setPrimaryTableName(CbInputPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CbInputPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(CbInputPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return CbInput
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = CbInputPeer::doSelect($critcopy, $con);
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
        return CbInputPeer::populateObjects(CbInputPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            CbInputPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(CbInputPeer::DATABASE_NAME);

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
     * @param CbInput $obj A CbInput object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            CbInputPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A CbInput object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof CbInput) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or CbInput object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(CbInputPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return CbInput Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(CbInputPeer::$instances[$key])) {
                return CbInputPeer::$instances[$key];
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
        foreach (CbInputPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        CbInputPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to cb_input
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
        $cls = CbInputPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = CbInputPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = CbInputPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CbInputPeer::addInstanceToPool($obj, $key);
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
     * @return array (CbInput object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = CbInputPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = CbInputPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + CbInputPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CbInputPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            CbInputPeer::addInstanceToPool($obj, $key);
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
        $criteria->setPrimaryTableName(CbInputPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CbInputPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(CbInputPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CbInputPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related ControllerBox table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinControllerBox(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(CbInputPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CbInputPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(CbInputPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CbInputPeer::CONTROLLER, ControllerBoxPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(CbInputPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CbInputPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(CbInputPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CbInputPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

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
     * Selects a collection of CbInput objects pre-filled with their Store objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of CbInput objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinStore(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CbInputPeer::DATABASE_NAME);
        }

        CbInputPeer::addSelectColumns($criteria);
        $startcol = CbInputPeer::NUM_HYDRATE_COLUMNS;
        StorePeer::addSelectColumns($criteria);

        $criteria->addJoin(CbInputPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CbInputPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CbInputPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = CbInputPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CbInputPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (CbInput) to $obj2 (Store)
                $obj2->addCbInput($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of CbInput objects pre-filled with their ControllerBox objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of CbInput objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinControllerBox(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CbInputPeer::DATABASE_NAME);
        }

        CbInputPeer::addSelectColumns($criteria);
        $startcol = CbInputPeer::NUM_HYDRATE_COLUMNS;
        ControllerBoxPeer::addSelectColumns($criteria);

        $criteria->addJoin(CbInputPeer::CONTROLLER, ControllerBoxPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CbInputPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CbInputPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = CbInputPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CbInputPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = ControllerBoxPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = ControllerBoxPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ControllerBoxPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    ControllerBoxPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (CbInput) to $obj2 (ControllerBox)
                $obj2->addCbInput($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of CbInput objects pre-filled with their DeviceGroup objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of CbInput objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinDeviceGroup(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CbInputPeer::DATABASE_NAME);
        }

        CbInputPeer::addSelectColumns($criteria);
        $startcol = CbInputPeer::NUM_HYDRATE_COLUMNS;
        DeviceGroupPeer::addSelectColumns($criteria);

        $criteria->addJoin(CbInputPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CbInputPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CbInputPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = CbInputPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CbInputPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (CbInput) to $obj2 (DeviceGroup)
                $obj2->addCbInput($obj1);

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
        $criteria->setPrimaryTableName(CbInputPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CbInputPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(CbInputPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CbInputPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(CbInputPeer::CONTROLLER, ControllerBoxPeer::ID, $join_behavior);

        $criteria->addJoin(CbInputPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

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
     * Selects a collection of CbInput objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of CbInput objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CbInputPeer::DATABASE_NAME);
        }

        CbInputPeer::addSelectColumns($criteria);
        $startcol2 = CbInputPeer::NUM_HYDRATE_COLUMNS;

        StorePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StorePeer::NUM_HYDRATE_COLUMNS;

        ControllerBoxPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + ControllerBoxPeer::NUM_HYDRATE_COLUMNS;

        DeviceGroupPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + DeviceGroupPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(CbInputPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(CbInputPeer::CONTROLLER, ControllerBoxPeer::ID, $join_behavior);

        $criteria->addJoin(CbInputPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CbInputPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CbInputPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = CbInputPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CbInputPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (CbInput) to the collection in $obj2 (Store)
                $obj2->addCbInput($obj1);
            } // if joined row not null

            // Add objects for joined ControllerBox rows

            $key3 = ControllerBoxPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = ControllerBoxPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = ControllerBoxPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    ControllerBoxPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (CbInput) to the collection in $obj3 (ControllerBox)
                $obj3->addCbInput($obj1);
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

                // Add the $obj1 (CbInput) to the collection in $obj4 (DeviceGroup)
                $obj4->addCbInput($obj1);
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
        $criteria->setPrimaryTableName(CbInputPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CbInputPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(CbInputPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CbInputPeer::CONTROLLER, ControllerBoxPeer::ID, $join_behavior);

        $criteria->addJoin(CbInputPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related ControllerBox table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptControllerBox(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(CbInputPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CbInputPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(CbInputPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CbInputPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(CbInputPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(CbInputPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CbInputPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(CbInputPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CbInputPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(CbInputPeer::CONTROLLER, ControllerBoxPeer::ID, $join_behavior);

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
     * Selects a collection of CbInput objects pre-filled with all related objects except Store.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of CbInput objects.
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
            $criteria->setDbName(CbInputPeer::DATABASE_NAME);
        }

        CbInputPeer::addSelectColumns($criteria);
        $startcol2 = CbInputPeer::NUM_HYDRATE_COLUMNS;

        ControllerBoxPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ControllerBoxPeer::NUM_HYDRATE_COLUMNS;

        DeviceGroupPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + DeviceGroupPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(CbInputPeer::CONTROLLER, ControllerBoxPeer::ID, $join_behavior);

        $criteria->addJoin(CbInputPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CbInputPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CbInputPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = CbInputPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CbInputPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined ControllerBox rows

                $key2 = ControllerBoxPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = ControllerBoxPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = ControllerBoxPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ControllerBoxPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (CbInput) to the collection in $obj2 (ControllerBox)
                $obj2->addCbInput($obj1);

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

                // Add the $obj1 (CbInput) to the collection in $obj3 (DeviceGroup)
                $obj3->addCbInput($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of CbInput objects pre-filled with all related objects except ControllerBox.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of CbInput objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptControllerBox(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CbInputPeer::DATABASE_NAME);
        }

        CbInputPeer::addSelectColumns($criteria);
        $startcol2 = CbInputPeer::NUM_HYDRATE_COLUMNS;

        StorePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StorePeer::NUM_HYDRATE_COLUMNS;

        DeviceGroupPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + DeviceGroupPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(CbInputPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(CbInputPeer::GROUP, DeviceGroupPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CbInputPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CbInputPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = CbInputPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CbInputPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (CbInput) to the collection in $obj2 (Store)
                $obj2->addCbInput($obj1);

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

                // Add the $obj1 (CbInput) to the collection in $obj3 (DeviceGroup)
                $obj3->addCbInput($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of CbInput objects pre-filled with all related objects except DeviceGroup.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of CbInput objects.
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
            $criteria->setDbName(CbInputPeer::DATABASE_NAME);
        }

        CbInputPeer::addSelectColumns($criteria);
        $startcol2 = CbInputPeer::NUM_HYDRATE_COLUMNS;

        StorePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StorePeer::NUM_HYDRATE_COLUMNS;

        ControllerBoxPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + ControllerBoxPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(CbInputPeer::MAIN_STORE, StorePeer::ID, $join_behavior);

        $criteria->addJoin(CbInputPeer::CONTROLLER, ControllerBoxPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CbInputPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CbInputPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = CbInputPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CbInputPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (CbInput) to the collection in $obj2 (Store)
                $obj2->addCbInput($obj1);

            } // if joined row is not null

                // Add objects for joined ControllerBox rows

                $key3 = ControllerBoxPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = ControllerBoxPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = ControllerBoxPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    ControllerBoxPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (CbInput) to the collection in $obj3 (ControllerBox)
                $obj3->addCbInput($obj1);

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
        return Propel::getDatabaseMap(CbInputPeer::DATABASE_NAME)->getTable(CbInputPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseCbInputPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseCbInputPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \DeviceBundle\Model\map\CbInputTableMap());
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
        return CbInputPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a CbInput or Criteria object.
     *
     * @param      mixed $values Criteria or CbInput object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from CbInput object
        }

        if ($criteria->containsKey(CbInputPeer::ID) && $criteria->keyContainsValue(CbInputPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CbInputPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(CbInputPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a CbInput or Criteria object.
     *
     * @param      mixed $values Criteria or CbInput object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(CbInputPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(CbInputPeer::ID);
            $value = $criteria->remove(CbInputPeer::ID);
            if ($value) {
                $selectCriteria->add(CbInputPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(CbInputPeer::TABLE_NAME);
            }

        } else { // $values is CbInput object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(CbInputPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the cb_input table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(CbInputPeer::TABLE_NAME, $con, CbInputPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CbInputPeer::clearInstancePool();
            CbInputPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a CbInput or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or CbInput object or primary key or array of primary keys
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
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            CbInputPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof CbInput) { // it's a model object
            // invalidate the cache for this single object
            CbInputPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CbInputPeer::DATABASE_NAME);
            $criteria->add(CbInputPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                CbInputPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(CbInputPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            CbInputPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given CbInput object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param CbInput $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(CbInputPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(CbInputPeer::TABLE_NAME);

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

        return BasePeer::doValidate(CbInputPeer::DATABASE_NAME, CbInputPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return CbInput
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = CbInputPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(CbInputPeer::DATABASE_NAME);
        $criteria->add(CbInputPeer::ID, $pk);

        $v = CbInputPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return CbInput[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(CbInputPeer::DATABASE_NAME);
            $criteria->add(CbInputPeer::ID, $pks, Criteria::IN);
            $objs = CbInputPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseCbInputPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseCbInputPeer::buildTableMap();

