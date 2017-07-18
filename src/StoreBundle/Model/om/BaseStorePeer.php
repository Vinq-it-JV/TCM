<?php

namespace StoreBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use CompanyBundle\Model\CompanyPeer;
use CompanyBundle\Model\RegionsPeer;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreAddressPeer;
use StoreBundle\Model\StoreContactPeer;
use StoreBundle\Model\StoreEmailPeer;
use StoreBundle\Model\StoreImagePeer;
use StoreBundle\Model\StoreInformantPeer;
use StoreBundle\Model\StoreOwnerPeer;
use StoreBundle\Model\StorePeer;
use StoreBundle\Model\StorePhonePeer;
use StoreBundle\Model\StoreTypePeer;
use StoreBundle\Model\map\StoreTableMap;

abstract class BaseStorePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'store';

    /** the related Propel class for this table */
    const OM_CLASS = 'StoreBundle\\Model\\Store';

    /** the related TableMap class for this table */
    const TM_CLASS = 'StoreBundle\\Model\\map\\StoreTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 21;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 21;

    /** the column name for the id field */
    const ID = 'store.id';

    /** the column name for the uid field */
    const UID = 'store.uid';

    /** the column name for the main_company field */
    const MAIN_COMPANY = 'store.main_company';

    /** the column name for the name field */
    const NAME = 'store.name';

    /** the column name for the description field */
    const DESCRIPTION = 'store.description';

    /** the column name for the image field */
    const IMAGE = 'store.image';

    /** the column name for the type field */
    const TYPE = 'store.type';

    /** the column name for the code field */
    const CODE = 'store.code';

    /** the column name for the website field */
    const WEBSITE = 'store.website';

    /** the column name for the region field */
    const REGION = 'store.region';

    /** the column name for the remarks field */
    const REMARKS = 'store.remarks';

    /** the column name for the payment_method field */
    const PAYMENT_METHOD = 'store.payment_method';

    /** the column name for the bank_account_number field */
    const BANK_ACCOUNT_NUMBER = 'store.bank_account_number';

    /** the column name for the vat_number field */
    const VAT_NUMBER = 'store.vat_number';

    /** the column name for the coc_number field */
    const COC_NUMBER = 'store.coc_number';

    /** the column name for the is_maintenance field */
    const IS_MAINTENANCE = 'store.is_maintenance';

    /** the column name for the is_enabled field */
    const IS_ENABLED = 'store.is_enabled';

    /** the column name for the is_deleted field */
    const IS_DELETED = 'store.is_deleted';

    /** the column name for the maintenance_started_at field */
    const MAINTENANCE_STARTED_AT = 'store.maintenance_started_at';

    /** the column name for the created_at field */
    const CREATED_AT = 'store.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'store.updated_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of Store objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Store[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. StorePeer::$fieldNames[StorePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Uid', 'MainCompany', 'Name', 'Description', 'Image', 'Type', 'Code', 'Website', 'Region', 'Remarks', 'PaymentMethod', 'BankAccountNumber', 'VatNumber', 'CocNumber', 'IsMaintenance', 'IsEnabled', 'IsDeleted', 'MaintenanceStartedAt', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'uid', 'mainCompany', 'name', 'description', 'image', 'type', 'code', 'website', 'region', 'remarks', 'paymentMethod', 'bankAccountNumber', 'vatNumber', 'cocNumber', 'isMaintenance', 'isEnabled', 'isDeleted', 'maintenanceStartedAt', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (StorePeer::ID, StorePeer::UID, StorePeer::MAIN_COMPANY, StorePeer::NAME, StorePeer::DESCRIPTION, StorePeer::IMAGE, StorePeer::TYPE, StorePeer::CODE, StorePeer::WEBSITE, StorePeer::REGION, StorePeer::REMARKS, StorePeer::PAYMENT_METHOD, StorePeer::BANK_ACCOUNT_NUMBER, StorePeer::VAT_NUMBER, StorePeer::COC_NUMBER, StorePeer::IS_MAINTENANCE, StorePeer::IS_ENABLED, StorePeer::IS_DELETED, StorePeer::MAINTENANCE_STARTED_AT, StorePeer::CREATED_AT, StorePeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'UID', 'MAIN_COMPANY', 'NAME', 'DESCRIPTION', 'IMAGE', 'TYPE', 'CODE', 'WEBSITE', 'REGION', 'REMARKS', 'PAYMENT_METHOD', 'BANK_ACCOUNT_NUMBER', 'VAT_NUMBER', 'COC_NUMBER', 'IS_MAINTENANCE', 'IS_ENABLED', 'IS_DELETED', 'MAINTENANCE_STARTED_AT', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'uid', 'main_company', 'name', 'description', 'image', 'type', 'code', 'website', 'region', 'remarks', 'payment_method', 'bank_account_number', 'vat_number', 'coc_number', 'is_maintenance', 'is_enabled', 'is_deleted', 'maintenance_started_at', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. StorePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Uid' => 1, 'MainCompany' => 2, 'Name' => 3, 'Description' => 4, 'Image' => 5, 'Type' => 6, 'Code' => 7, 'Website' => 8, 'Region' => 9, 'Remarks' => 10, 'PaymentMethod' => 11, 'BankAccountNumber' => 12, 'VatNumber' => 13, 'CocNumber' => 14, 'IsMaintenance' => 15, 'IsEnabled' => 16, 'IsDeleted' => 17, 'MaintenanceStartedAt' => 18, 'CreatedAt' => 19, 'UpdatedAt' => 20, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'uid' => 1, 'mainCompany' => 2, 'name' => 3, 'description' => 4, 'image' => 5, 'type' => 6, 'code' => 7, 'website' => 8, 'region' => 9, 'remarks' => 10, 'paymentMethod' => 11, 'bankAccountNumber' => 12, 'vatNumber' => 13, 'cocNumber' => 14, 'isMaintenance' => 15, 'isEnabled' => 16, 'isDeleted' => 17, 'maintenanceStartedAt' => 18, 'createdAt' => 19, 'updatedAt' => 20, ),
        BasePeer::TYPE_COLNAME => array (StorePeer::ID => 0, StorePeer::UID => 1, StorePeer::MAIN_COMPANY => 2, StorePeer::NAME => 3, StorePeer::DESCRIPTION => 4, StorePeer::IMAGE => 5, StorePeer::TYPE => 6, StorePeer::CODE => 7, StorePeer::WEBSITE => 8, StorePeer::REGION => 9, StorePeer::REMARKS => 10, StorePeer::PAYMENT_METHOD => 11, StorePeer::BANK_ACCOUNT_NUMBER => 12, StorePeer::VAT_NUMBER => 13, StorePeer::COC_NUMBER => 14, StorePeer::IS_MAINTENANCE => 15, StorePeer::IS_ENABLED => 16, StorePeer::IS_DELETED => 17, StorePeer::MAINTENANCE_STARTED_AT => 18, StorePeer::CREATED_AT => 19, StorePeer::UPDATED_AT => 20, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'UID' => 1, 'MAIN_COMPANY' => 2, 'NAME' => 3, 'DESCRIPTION' => 4, 'IMAGE' => 5, 'TYPE' => 6, 'CODE' => 7, 'WEBSITE' => 8, 'REGION' => 9, 'REMARKS' => 10, 'PAYMENT_METHOD' => 11, 'BANK_ACCOUNT_NUMBER' => 12, 'VAT_NUMBER' => 13, 'COC_NUMBER' => 14, 'IS_MAINTENANCE' => 15, 'IS_ENABLED' => 16, 'IS_DELETED' => 17, 'MAINTENANCE_STARTED_AT' => 18, 'CREATED_AT' => 19, 'UPDATED_AT' => 20, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'uid' => 1, 'main_company' => 2, 'name' => 3, 'description' => 4, 'image' => 5, 'type' => 6, 'code' => 7, 'website' => 8, 'region' => 9, 'remarks' => 10, 'payment_method' => 11, 'bank_account_number' => 12, 'vat_number' => 13, 'coc_number' => 14, 'is_maintenance' => 15, 'is_enabled' => 16, 'is_deleted' => 17, 'maintenance_started_at' => 18, 'created_at' => 19, 'updated_at' => 20, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
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
        $toNames = StorePeer::getFieldNames($toType);
        $key = isset(StorePeer::$fieldKeys[$fromType][$name]) ? StorePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(StorePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, StorePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return StorePeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. StorePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(StorePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(StorePeer::ID);
            $criteria->addSelectColumn(StorePeer::UID);
            $criteria->addSelectColumn(StorePeer::MAIN_COMPANY);
            $criteria->addSelectColumn(StorePeer::NAME);
            $criteria->addSelectColumn(StorePeer::DESCRIPTION);
            $criteria->addSelectColumn(StorePeer::IMAGE);
            $criteria->addSelectColumn(StorePeer::TYPE);
            $criteria->addSelectColumn(StorePeer::CODE);
            $criteria->addSelectColumn(StorePeer::WEBSITE);
            $criteria->addSelectColumn(StorePeer::REGION);
            $criteria->addSelectColumn(StorePeer::REMARKS);
            $criteria->addSelectColumn(StorePeer::PAYMENT_METHOD);
            $criteria->addSelectColumn(StorePeer::BANK_ACCOUNT_NUMBER);
            $criteria->addSelectColumn(StorePeer::VAT_NUMBER);
            $criteria->addSelectColumn(StorePeer::COC_NUMBER);
            $criteria->addSelectColumn(StorePeer::IS_MAINTENANCE);
            $criteria->addSelectColumn(StorePeer::IS_ENABLED);
            $criteria->addSelectColumn(StorePeer::IS_DELETED);
            $criteria->addSelectColumn(StorePeer::MAINTENANCE_STARTED_AT);
            $criteria->addSelectColumn(StorePeer::CREATED_AT);
            $criteria->addSelectColumn(StorePeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.uid');
            $criteria->addSelectColumn($alias . '.main_company');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.image');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.code');
            $criteria->addSelectColumn($alias . '.website');
            $criteria->addSelectColumn($alias . '.region');
            $criteria->addSelectColumn($alias . '.remarks');
            $criteria->addSelectColumn($alias . '.payment_method');
            $criteria->addSelectColumn($alias . '.bank_account_number');
            $criteria->addSelectColumn($alias . '.vat_number');
            $criteria->addSelectColumn($alias . '.coc_number');
            $criteria->addSelectColumn($alias . '.is_maintenance');
            $criteria->addSelectColumn($alias . '.is_enabled');
            $criteria->addSelectColumn($alias . '.is_deleted');
            $criteria->addSelectColumn($alias . '.maintenance_started_at');
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
        $criteria->setPrimaryTableName(StorePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StorePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(StorePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return Store
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = StorePeer::doSelect($critcopy, $con);
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
        return StorePeer::populateObjects(StorePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            StorePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(StorePeer::DATABASE_NAME);

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
     * @param Store $obj A Store object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            StorePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A Store object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Store) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Store object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(StorePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return Store Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(StorePeer::$instances[$key])) {
                return StorePeer::$instances[$key];
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
        foreach (StorePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        StorePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to store
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in StoreAddressPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        StoreAddressPeer::clearInstancePool();
        // Invalidate objects in StoreEmailPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        StoreEmailPeer::clearInstancePool();
        // Invalidate objects in StorePhonePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        StorePhonePeer::clearInstancePool();
        // Invalidate objects in StoreContactPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        StoreContactPeer::clearInstancePool();
        // Invalidate objects in StoreInformantPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        StoreInformantPeer::clearInstancePool();
        // Invalidate objects in StoreOwnerPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        StoreOwnerPeer::clearInstancePool();
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
        $cls = StorePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = StorePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = StorePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                StorePeer::addInstanceToPool($obj, $key);
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
     * @return array (Store object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = StorePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = StorePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + StorePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = StorePeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            StorePeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Company table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCompany(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StorePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StorePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StorePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StorePeer::MAIN_COMPANY, CompanyPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related StoreType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinStoreType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StorePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StorePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StorePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StorePeer::TYPE, StoreTypePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Regions table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinRegions(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StorePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StorePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StorePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StorePeer::REGION, RegionsPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related StoreImage table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinStoreImage(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StorePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StorePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StorePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StorePeer::IMAGE, StoreImagePeer::ID, $join_behavior);

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
     * Selects a collection of Store objects pre-filled with their Company objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Store objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCompany(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StorePeer::DATABASE_NAME);
        }

        StorePeer::addSelectColumns($criteria);
        $startcol = StorePeer::NUM_HYDRATE_COLUMNS;
        CompanyPeer::addSelectColumns($criteria);

        $criteria->addJoin(StorePeer::MAIN_COMPANY, CompanyPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StorePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StorePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = StorePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StorePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CompanyPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CompanyPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CompanyPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CompanyPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Store) to $obj2 (Company)
                $obj2->addStore($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Store objects pre-filled with their StoreType objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Store objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinStoreType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StorePeer::DATABASE_NAME);
        }

        StorePeer::addSelectColumns($criteria);
        $startcol = StorePeer::NUM_HYDRATE_COLUMNS;
        StoreTypePeer::addSelectColumns($criteria);

        $criteria->addJoin(StorePeer::TYPE, StoreTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StorePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StorePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = StorePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StorePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = StoreTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = StoreTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = StoreTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    StoreTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Store) to $obj2 (StoreType)
                $obj2->addStore($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Store objects pre-filled with their Regions objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Store objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinRegions(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StorePeer::DATABASE_NAME);
        }

        StorePeer::addSelectColumns($criteria);
        $startcol = StorePeer::NUM_HYDRATE_COLUMNS;
        RegionsPeer::addSelectColumns($criteria);

        $criteria->addJoin(StorePeer::REGION, RegionsPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StorePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StorePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = StorePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StorePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = RegionsPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = RegionsPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = RegionsPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    RegionsPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Store) to $obj2 (Regions)
                $obj2->addStore($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Store objects pre-filled with their StoreImage objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Store objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinStoreImage(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StorePeer::DATABASE_NAME);
        }

        StorePeer::addSelectColumns($criteria);
        $startcol = StorePeer::NUM_HYDRATE_COLUMNS;
        StoreImagePeer::addSelectColumns($criteria);

        $criteria->addJoin(StorePeer::IMAGE, StoreImagePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StorePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StorePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = StorePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StorePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = StoreImagePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = StoreImagePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = StoreImagePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    StoreImagePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Store) to $obj2 (StoreImage)
                $obj2->addStore($obj1);

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
        $criteria->setPrimaryTableName(StorePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StorePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(StorePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StorePeer::MAIN_COMPANY, CompanyPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::TYPE, StoreTypePeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::REGION, RegionsPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::IMAGE, StoreImagePeer::ID, $join_behavior);

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
     * Selects a collection of Store objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Store objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StorePeer::DATABASE_NAME);
        }

        StorePeer::addSelectColumns($criteria);
        $startcol2 = StorePeer::NUM_HYDRATE_COLUMNS;

        CompanyPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CompanyPeer::NUM_HYDRATE_COLUMNS;

        StoreTypePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + StoreTypePeer::NUM_HYDRATE_COLUMNS;

        RegionsPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + RegionsPeer::NUM_HYDRATE_COLUMNS;

        StoreImagePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + StoreImagePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StorePeer::MAIN_COMPANY, CompanyPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::TYPE, StoreTypePeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::REGION, RegionsPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::IMAGE, StoreImagePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StorePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StorePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StorePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StorePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Company rows

            $key2 = CompanyPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = CompanyPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CompanyPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CompanyPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (Store) to the collection in $obj2 (Company)
                $obj2->addStore($obj1);
            } // if joined row not null

            // Add objects for joined StoreType rows

            $key3 = StoreTypePeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = StoreTypePeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = StoreTypePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    StoreTypePeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (Store) to the collection in $obj3 (StoreType)
                $obj3->addStore($obj1);
            } // if joined row not null

            // Add objects for joined Regions rows

            $key4 = RegionsPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = RegionsPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = RegionsPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    RegionsPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (Store) to the collection in $obj4 (Regions)
                $obj4->addStore($obj1);
            } // if joined row not null

            // Add objects for joined StoreImage rows

            $key5 = StoreImagePeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = StoreImagePeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = StoreImagePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    StoreImagePeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (Store) to the collection in $obj5 (StoreImage)
                $obj5->addStore($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Company table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCompany(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StorePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StorePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(StorePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StorePeer::TYPE, StoreTypePeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::REGION, RegionsPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::IMAGE, StoreImagePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related StoreType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptStoreType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StorePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StorePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(StorePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StorePeer::MAIN_COMPANY, CompanyPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::REGION, RegionsPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::IMAGE, StoreImagePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Regions table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptRegions(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StorePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StorePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(StorePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StorePeer::MAIN_COMPANY, CompanyPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::TYPE, StoreTypePeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::IMAGE, StoreImagePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related StoreImage table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptStoreImage(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(StorePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            StorePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(StorePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(StorePeer::MAIN_COMPANY, CompanyPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::TYPE, StoreTypePeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::REGION, RegionsPeer::ID, $join_behavior);

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
     * Selects a collection of Store objects pre-filled with all related objects except Company.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Store objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCompany(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StorePeer::DATABASE_NAME);
        }

        StorePeer::addSelectColumns($criteria);
        $startcol2 = StorePeer::NUM_HYDRATE_COLUMNS;

        StoreTypePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + StoreTypePeer::NUM_HYDRATE_COLUMNS;

        RegionsPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + RegionsPeer::NUM_HYDRATE_COLUMNS;

        StoreImagePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + StoreImagePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StorePeer::TYPE, StoreTypePeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::REGION, RegionsPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::IMAGE, StoreImagePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StorePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StorePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StorePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StorePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined StoreType rows

                $key2 = StoreTypePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = StoreTypePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = StoreTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    StoreTypePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Store) to the collection in $obj2 (StoreType)
                $obj2->addStore($obj1);

            } // if joined row is not null

                // Add objects for joined Regions rows

                $key3 = RegionsPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = RegionsPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = RegionsPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    RegionsPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Store) to the collection in $obj3 (Regions)
                $obj3->addStore($obj1);

            } // if joined row is not null

                // Add objects for joined StoreImage rows

                $key4 = StoreImagePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = StoreImagePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = StoreImagePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    StoreImagePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Store) to the collection in $obj4 (StoreImage)
                $obj4->addStore($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Store objects pre-filled with all related objects except StoreType.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Store objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptStoreType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StorePeer::DATABASE_NAME);
        }

        StorePeer::addSelectColumns($criteria);
        $startcol2 = StorePeer::NUM_HYDRATE_COLUMNS;

        CompanyPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CompanyPeer::NUM_HYDRATE_COLUMNS;

        RegionsPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + RegionsPeer::NUM_HYDRATE_COLUMNS;

        StoreImagePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + StoreImagePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StorePeer::MAIN_COMPANY, CompanyPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::REGION, RegionsPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::IMAGE, StoreImagePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StorePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StorePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StorePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StorePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Company rows

                $key2 = CompanyPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CompanyPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CompanyPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CompanyPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Store) to the collection in $obj2 (Company)
                $obj2->addStore($obj1);

            } // if joined row is not null

                // Add objects for joined Regions rows

                $key3 = RegionsPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = RegionsPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = RegionsPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    RegionsPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Store) to the collection in $obj3 (Regions)
                $obj3->addStore($obj1);

            } // if joined row is not null

                // Add objects for joined StoreImage rows

                $key4 = StoreImagePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = StoreImagePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = StoreImagePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    StoreImagePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Store) to the collection in $obj4 (StoreImage)
                $obj4->addStore($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Store objects pre-filled with all related objects except Regions.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Store objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptRegions(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StorePeer::DATABASE_NAME);
        }

        StorePeer::addSelectColumns($criteria);
        $startcol2 = StorePeer::NUM_HYDRATE_COLUMNS;

        CompanyPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CompanyPeer::NUM_HYDRATE_COLUMNS;

        StoreTypePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + StoreTypePeer::NUM_HYDRATE_COLUMNS;

        StoreImagePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + StoreImagePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StorePeer::MAIN_COMPANY, CompanyPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::TYPE, StoreTypePeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::IMAGE, StoreImagePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StorePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StorePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StorePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StorePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Company rows

                $key2 = CompanyPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CompanyPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CompanyPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CompanyPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Store) to the collection in $obj2 (Company)
                $obj2->addStore($obj1);

            } // if joined row is not null

                // Add objects for joined StoreType rows

                $key3 = StoreTypePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = StoreTypePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = StoreTypePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    StoreTypePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Store) to the collection in $obj3 (StoreType)
                $obj3->addStore($obj1);

            } // if joined row is not null

                // Add objects for joined StoreImage rows

                $key4 = StoreImagePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = StoreImagePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = StoreImagePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    StoreImagePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Store) to the collection in $obj4 (StoreImage)
                $obj4->addStore($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Store objects pre-filled with all related objects except StoreImage.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Store objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptStoreImage(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(StorePeer::DATABASE_NAME);
        }

        StorePeer::addSelectColumns($criteria);
        $startcol2 = StorePeer::NUM_HYDRATE_COLUMNS;

        CompanyPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CompanyPeer::NUM_HYDRATE_COLUMNS;

        StoreTypePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + StoreTypePeer::NUM_HYDRATE_COLUMNS;

        RegionsPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + RegionsPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(StorePeer::MAIN_COMPANY, CompanyPeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::TYPE, StoreTypePeer::ID, $join_behavior);

        $criteria->addJoin(StorePeer::REGION, RegionsPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = StorePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = StorePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = StorePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                StorePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Company rows

                $key2 = CompanyPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CompanyPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CompanyPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CompanyPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Store) to the collection in $obj2 (Company)
                $obj2->addStore($obj1);

            } // if joined row is not null

                // Add objects for joined StoreType rows

                $key3 = StoreTypePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = StoreTypePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = StoreTypePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    StoreTypePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Store) to the collection in $obj3 (StoreType)
                $obj3->addStore($obj1);

            } // if joined row is not null

                // Add objects for joined Regions rows

                $key4 = RegionsPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = RegionsPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = RegionsPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    RegionsPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Store) to the collection in $obj4 (Regions)
                $obj4->addStore($obj1);

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
        return Propel::getDatabaseMap(StorePeer::DATABASE_NAME)->getTable(StorePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseStorePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseStorePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \StoreBundle\Model\map\StoreTableMap());
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
        return StorePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Store or Criteria object.
     *
     * @param      mixed $values Criteria or Store object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Store object
        }

        if ($criteria->containsKey(StorePeer::ID) && $criteria->keyContainsValue(StorePeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.StorePeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(StorePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a Store or Criteria object.
     *
     * @param      mixed $values Criteria or Store object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(StorePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(StorePeer::ID);
            $value = $criteria->remove(StorePeer::ID);
            if ($value) {
                $selectCriteria->add(StorePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(StorePeer::TABLE_NAME);
            }

        } else { // $values is Store object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(StorePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the store table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += StorePeer::doOnDeleteCascade(new Criteria(StorePeer::DATABASE_NAME), $con);
            $affectedRows += BasePeer::doDeleteAll(StorePeer::TABLE_NAME, $con, StorePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            StorePeer::clearInstancePool();
            StorePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Store or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Store object or primary key or array of primary keys
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
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Store) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(StorePeer::DATABASE_NAME);
            $criteria->add(StorePeer::ID, (array) $values, Criteria::IN);
        }

        // Set the correct dbName
        $criteria->setDbName(StorePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            // cloning the Criteria in case it's modified by doSelect() or doSelectStmt()
            $c = clone $criteria;
            $affectedRows += StorePeer::doOnDeleteCascade($c, $con);

            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            if ($values instanceof Criteria) {
                StorePeer::clearInstancePool();
            } elseif ($values instanceof Store) { // it's a model object
                StorePeer::removeInstanceFromPool($values);
            } else { // it's a primary key, or an array of pks
                foreach ((array) $values as $singleval) {
                    StorePeer::removeInstanceFromPool($singleval);
                }
            }

            $affectedRows += BasePeer::doDelete($criteria, $con);
            StorePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * This is a method for emulating ON DELETE CASCADE for DBs that don't support this
     * feature (like MySQL or SQLite).
     *
     * This method is not very speedy because it must perform a query first to get
     * the implicated records and then perform the deletes by calling those Peer classes.
     *
     * This method should be used within a transaction if possible.
     *
     * @param      Criteria $criteria
     * @param      PropelPDO $con
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    protected static function doOnDeleteCascade(Criteria $criteria, PropelPDO $con)
    {
        // initialize var to track total num of affected rows
        $affectedRows = 0;

        // first find the objects that are implicated by the $criteria
        $objects = StorePeer::doSelect($criteria, $con);
        foreach ($objects as $obj) {


            // delete related StoreAddress objects
            $criteria = new Criteria(StoreAddressPeer::DATABASE_NAME);

            $criteria->add(StoreAddressPeer::STORE_ID, $obj->getId());
            $affectedRows += StoreAddressPeer::doDelete($criteria, $con);

            // delete related StoreEmail objects
            $criteria = new Criteria(StoreEmailPeer::DATABASE_NAME);

            $criteria->add(StoreEmailPeer::STORE_ID, $obj->getId());
            $affectedRows += StoreEmailPeer::doDelete($criteria, $con);

            // delete related StorePhone objects
            $criteria = new Criteria(StorePhonePeer::DATABASE_NAME);

            $criteria->add(StorePhonePeer::STORE_ID, $obj->getId());
            $affectedRows += StorePhonePeer::doDelete($criteria, $con);

            // delete related StoreContact objects
            $criteria = new Criteria(StoreContactPeer::DATABASE_NAME);

            $criteria->add(StoreContactPeer::STORE_ID, $obj->getId());
            $affectedRows += StoreContactPeer::doDelete($criteria, $con);

            // delete related StoreInformant objects
            $criteria = new Criteria(StoreInformantPeer::DATABASE_NAME);

            $criteria->add(StoreInformantPeer::STORE_ID, $obj->getId());
            $affectedRows += StoreInformantPeer::doDelete($criteria, $con);

            // delete related StoreOwner objects
            $criteria = new Criteria(StoreOwnerPeer::DATABASE_NAME);

            $criteria->add(StoreOwnerPeer::STORE_ID, $obj->getId());
            $affectedRows += StoreOwnerPeer::doDelete($criteria, $con);
        }

        return $affectedRows;
    }

    /**
     * Validates all modified columns of given Store object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param Store $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(StorePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(StorePeer::TABLE_NAME);

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

        return BasePeer::doValidate(StorePeer::DATABASE_NAME, StorePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Store
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = StorePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(StorePeer::DATABASE_NAME);
        $criteria->add(StorePeer::ID, $pk);

        $v = StorePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Store[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(StorePeer::DATABASE_NAME);
            $criteria->add(StorePeer::ID, $pks, Criteria::IN);
            $objs = StorePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseStorePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseStorePeer::buildTableMap();

