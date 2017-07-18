<?php

namespace CompanyBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use CompanyBundle\Model\Company;
use CompanyBundle\Model\CompanyAddressPeer;
use CompanyBundle\Model\CompanyContactPeer;
use CompanyBundle\Model\CompanyEmailPeer;
use CompanyBundle\Model\CompanyInformantPeer;
use CompanyBundle\Model\CompanyOwnerPeer;
use CompanyBundle\Model\CompanyPeer;
use CompanyBundle\Model\CompanyPhonePeer;
use CompanyBundle\Model\CompanyTypePeer;
use CompanyBundle\Model\RegionsPeer;
use CompanyBundle\Model\map\CompanyTableMap;

abstract class BaseCompanyPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'company';

    /** the related Propel class for this table */
    const OM_CLASS = 'CompanyBundle\\Model\\Company';

    /** the related TableMap class for this table */
    const TM_CLASS = 'CompanyBundle\\Model\\map\\CompanyTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 17;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 17;

    /** the column name for the id field */
    const ID = 'company.id';

    /** the column name for the uid field */
    const UID = 'company.uid';

    /** the column name for the name field */
    const NAME = 'company.name';

    /** the column name for the description field */
    const DESCRIPTION = 'company.description';

    /** the column name for the type field */
    const TYPE = 'company.type';

    /** the column name for the code field */
    const CODE = 'company.code';

    /** the column name for the website field */
    const WEBSITE = 'company.website';

    /** the column name for the region field */
    const REGION = 'company.region';

    /** the column name for the remarks field */
    const REMARKS = 'company.remarks';

    /** the column name for the payment_method field */
    const PAYMENT_METHOD = 'company.payment_method';

    /** the column name for the bank_account_number field */
    const BANK_ACCOUNT_NUMBER = 'company.bank_account_number';

    /** the column name for the vat_number field */
    const VAT_NUMBER = 'company.vat_number';

    /** the column name for the coc_number field */
    const COC_NUMBER = 'company.coc_number';

    /** the column name for the is_enabled field */
    const IS_ENABLED = 'company.is_enabled';

    /** the column name for the is_deleted field */
    const IS_DELETED = 'company.is_deleted';

    /** the column name for the created_at field */
    const CREATED_AT = 'company.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'company.updated_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of Company objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Company[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. CompanyPeer::$fieldNames[CompanyPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Uid', 'Name', 'Description', 'Type', 'Code', 'Website', 'Region', 'Remarks', 'PaymentMethod', 'BankAccountNumber', 'VatNumber', 'CocNumber', 'IsEnabled', 'IsDeleted', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'uid', 'name', 'description', 'type', 'code', 'website', 'region', 'remarks', 'paymentMethod', 'bankAccountNumber', 'vatNumber', 'cocNumber', 'isEnabled', 'isDeleted', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (CompanyPeer::ID, CompanyPeer::UID, CompanyPeer::NAME, CompanyPeer::DESCRIPTION, CompanyPeer::TYPE, CompanyPeer::CODE, CompanyPeer::WEBSITE, CompanyPeer::REGION, CompanyPeer::REMARKS, CompanyPeer::PAYMENT_METHOD, CompanyPeer::BANK_ACCOUNT_NUMBER, CompanyPeer::VAT_NUMBER, CompanyPeer::COC_NUMBER, CompanyPeer::IS_ENABLED, CompanyPeer::IS_DELETED, CompanyPeer::CREATED_AT, CompanyPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'UID', 'NAME', 'DESCRIPTION', 'TYPE', 'CODE', 'WEBSITE', 'REGION', 'REMARKS', 'PAYMENT_METHOD', 'BANK_ACCOUNT_NUMBER', 'VAT_NUMBER', 'COC_NUMBER', 'IS_ENABLED', 'IS_DELETED', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'uid', 'name', 'description', 'type', 'code', 'website', 'region', 'remarks', 'payment_method', 'bank_account_number', 'vat_number', 'coc_number', 'is_enabled', 'is_deleted', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. CompanyPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Uid' => 1, 'Name' => 2, 'Description' => 3, 'Type' => 4, 'Code' => 5, 'Website' => 6, 'Region' => 7, 'Remarks' => 8, 'PaymentMethod' => 9, 'BankAccountNumber' => 10, 'VatNumber' => 11, 'CocNumber' => 12, 'IsEnabled' => 13, 'IsDeleted' => 14, 'CreatedAt' => 15, 'UpdatedAt' => 16, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'uid' => 1, 'name' => 2, 'description' => 3, 'type' => 4, 'code' => 5, 'website' => 6, 'region' => 7, 'remarks' => 8, 'paymentMethod' => 9, 'bankAccountNumber' => 10, 'vatNumber' => 11, 'cocNumber' => 12, 'isEnabled' => 13, 'isDeleted' => 14, 'createdAt' => 15, 'updatedAt' => 16, ),
        BasePeer::TYPE_COLNAME => array (CompanyPeer::ID => 0, CompanyPeer::UID => 1, CompanyPeer::NAME => 2, CompanyPeer::DESCRIPTION => 3, CompanyPeer::TYPE => 4, CompanyPeer::CODE => 5, CompanyPeer::WEBSITE => 6, CompanyPeer::REGION => 7, CompanyPeer::REMARKS => 8, CompanyPeer::PAYMENT_METHOD => 9, CompanyPeer::BANK_ACCOUNT_NUMBER => 10, CompanyPeer::VAT_NUMBER => 11, CompanyPeer::COC_NUMBER => 12, CompanyPeer::IS_ENABLED => 13, CompanyPeer::IS_DELETED => 14, CompanyPeer::CREATED_AT => 15, CompanyPeer::UPDATED_AT => 16, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'UID' => 1, 'NAME' => 2, 'DESCRIPTION' => 3, 'TYPE' => 4, 'CODE' => 5, 'WEBSITE' => 6, 'REGION' => 7, 'REMARKS' => 8, 'PAYMENT_METHOD' => 9, 'BANK_ACCOUNT_NUMBER' => 10, 'VAT_NUMBER' => 11, 'COC_NUMBER' => 12, 'IS_ENABLED' => 13, 'IS_DELETED' => 14, 'CREATED_AT' => 15, 'UPDATED_AT' => 16, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'uid' => 1, 'name' => 2, 'description' => 3, 'type' => 4, 'code' => 5, 'website' => 6, 'region' => 7, 'remarks' => 8, 'payment_method' => 9, 'bank_account_number' => 10, 'vat_number' => 11, 'coc_number' => 12, 'is_enabled' => 13, 'is_deleted' => 14, 'created_at' => 15, 'updated_at' => 16, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
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
        $toNames = CompanyPeer::getFieldNames($toType);
        $key = isset(CompanyPeer::$fieldKeys[$fromType][$name]) ? CompanyPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(CompanyPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, CompanyPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return CompanyPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. CompanyPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(CompanyPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(CompanyPeer::ID);
            $criteria->addSelectColumn(CompanyPeer::UID);
            $criteria->addSelectColumn(CompanyPeer::NAME);
            $criteria->addSelectColumn(CompanyPeer::DESCRIPTION);
            $criteria->addSelectColumn(CompanyPeer::TYPE);
            $criteria->addSelectColumn(CompanyPeer::CODE);
            $criteria->addSelectColumn(CompanyPeer::WEBSITE);
            $criteria->addSelectColumn(CompanyPeer::REGION);
            $criteria->addSelectColumn(CompanyPeer::REMARKS);
            $criteria->addSelectColumn(CompanyPeer::PAYMENT_METHOD);
            $criteria->addSelectColumn(CompanyPeer::BANK_ACCOUNT_NUMBER);
            $criteria->addSelectColumn(CompanyPeer::VAT_NUMBER);
            $criteria->addSelectColumn(CompanyPeer::COC_NUMBER);
            $criteria->addSelectColumn(CompanyPeer::IS_ENABLED);
            $criteria->addSelectColumn(CompanyPeer::IS_DELETED);
            $criteria->addSelectColumn(CompanyPeer::CREATED_AT);
            $criteria->addSelectColumn(CompanyPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.uid');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.code');
            $criteria->addSelectColumn($alias . '.website');
            $criteria->addSelectColumn($alias . '.region');
            $criteria->addSelectColumn($alias . '.remarks');
            $criteria->addSelectColumn($alias . '.payment_method');
            $criteria->addSelectColumn($alias . '.bank_account_number');
            $criteria->addSelectColumn($alias . '.vat_number');
            $criteria->addSelectColumn($alias . '.coc_number');
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
        $criteria->setPrimaryTableName(CompanyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CompanyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(CompanyPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return Company
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = CompanyPeer::doSelect($critcopy, $con);
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
        return CompanyPeer::populateObjects(CompanyPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            CompanyPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(CompanyPeer::DATABASE_NAME);

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
     * @param Company $obj A Company object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            CompanyPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A Company object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Company) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Company object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(CompanyPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return Company Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(CompanyPeer::$instances[$key])) {
                return CompanyPeer::$instances[$key];
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
        foreach (CompanyPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        CompanyPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to company
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in CompanyAddressPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        CompanyAddressPeer::clearInstancePool();
        // Invalidate objects in CompanyEmailPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        CompanyEmailPeer::clearInstancePool();
        // Invalidate objects in CompanyPhonePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        CompanyPhonePeer::clearInstancePool();
        // Invalidate objects in CompanyContactPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        CompanyContactPeer::clearInstancePool();
        // Invalidate objects in CompanyInformantPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        CompanyInformantPeer::clearInstancePool();
        // Invalidate objects in CompanyOwnerPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        CompanyOwnerPeer::clearInstancePool();
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
        $cls = CompanyPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = CompanyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = CompanyPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CompanyPeer::addInstanceToPool($obj, $key);
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
     * @return array (Company object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = CompanyPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = CompanyPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + CompanyPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CompanyPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            CompanyPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related CompanyType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCompanyType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(CompanyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CompanyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(CompanyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CompanyPeer::TYPE, CompanyTypePeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(CompanyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CompanyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(CompanyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CompanyPeer::REGION, RegionsPeer::ID, $join_behavior);

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
     * Selects a collection of Company objects pre-filled with their CompanyType objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Company objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCompanyType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CompanyPeer::DATABASE_NAME);
        }

        CompanyPeer::addSelectColumns($criteria);
        $startcol = CompanyPeer::NUM_HYDRATE_COLUMNS;
        CompanyTypePeer::addSelectColumns($criteria);

        $criteria->addJoin(CompanyPeer::TYPE, CompanyTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CompanyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CompanyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = CompanyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CompanyPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CompanyTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CompanyTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CompanyTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CompanyTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Company) to $obj2 (CompanyType)
                $obj2->addCompany($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Company objects pre-filled with their Regions objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Company objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinRegions(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CompanyPeer::DATABASE_NAME);
        }

        CompanyPeer::addSelectColumns($criteria);
        $startcol = CompanyPeer::NUM_HYDRATE_COLUMNS;
        RegionsPeer::addSelectColumns($criteria);

        $criteria->addJoin(CompanyPeer::REGION, RegionsPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CompanyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CompanyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = CompanyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CompanyPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Company) to $obj2 (Regions)
                $obj2->addCompany($obj1);

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
        $criteria->setPrimaryTableName(CompanyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CompanyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(CompanyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CompanyPeer::TYPE, CompanyTypePeer::ID, $join_behavior);

        $criteria->addJoin(CompanyPeer::REGION, RegionsPeer::ID, $join_behavior);

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
     * Selects a collection of Company objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Company objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CompanyPeer::DATABASE_NAME);
        }

        CompanyPeer::addSelectColumns($criteria);
        $startcol2 = CompanyPeer::NUM_HYDRATE_COLUMNS;

        CompanyTypePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CompanyTypePeer::NUM_HYDRATE_COLUMNS;

        RegionsPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + RegionsPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(CompanyPeer::TYPE, CompanyTypePeer::ID, $join_behavior);

        $criteria->addJoin(CompanyPeer::REGION, RegionsPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CompanyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CompanyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = CompanyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CompanyPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined CompanyType rows

            $key2 = CompanyTypePeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = CompanyTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CompanyTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CompanyTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (Company) to the collection in $obj2 (CompanyType)
                $obj2->addCompany($obj1);
            } // if joined row not null

            // Add objects for joined Regions rows

            $key3 = RegionsPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = RegionsPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = RegionsPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    RegionsPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (Company) to the collection in $obj3 (Regions)
                $obj3->addCompany($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related CompanyType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCompanyType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(CompanyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CompanyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(CompanyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CompanyPeer::REGION, RegionsPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(CompanyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            CompanyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(CompanyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(CompanyPeer::TYPE, CompanyTypePeer::ID, $join_behavior);

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
     * Selects a collection of Company objects pre-filled with all related objects except CompanyType.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Company objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCompanyType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(CompanyPeer::DATABASE_NAME);
        }

        CompanyPeer::addSelectColumns($criteria);
        $startcol2 = CompanyPeer::NUM_HYDRATE_COLUMNS;

        RegionsPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + RegionsPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(CompanyPeer::REGION, RegionsPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CompanyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CompanyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = CompanyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CompanyPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Regions rows

                $key2 = RegionsPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = RegionsPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = RegionsPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    RegionsPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Company) to the collection in $obj2 (Regions)
                $obj2->addCompany($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Company objects pre-filled with all related objects except Regions.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Company objects.
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
            $criteria->setDbName(CompanyPeer::DATABASE_NAME);
        }

        CompanyPeer::addSelectColumns($criteria);
        $startcol2 = CompanyPeer::NUM_HYDRATE_COLUMNS;

        CompanyTypePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CompanyTypePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(CompanyPeer::TYPE, CompanyTypePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = CompanyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = CompanyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = CompanyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                CompanyPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined CompanyType rows

                $key2 = CompanyTypePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CompanyTypePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CompanyTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CompanyTypePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Company) to the collection in $obj2 (CompanyType)
                $obj2->addCompany($obj1);

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
        return Propel::getDatabaseMap(CompanyPeer::DATABASE_NAME)->getTable(CompanyPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseCompanyPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseCompanyPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \CompanyBundle\Model\map\CompanyTableMap());
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
        return CompanyPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Company or Criteria object.
     *
     * @param      mixed $values Criteria or Company object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Company object
        }

        if ($criteria->containsKey(CompanyPeer::ID) && $criteria->keyContainsValue(CompanyPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CompanyPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(CompanyPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a Company or Criteria object.
     *
     * @param      mixed $values Criteria or Company object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(CompanyPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(CompanyPeer::ID);
            $value = $criteria->remove(CompanyPeer::ID);
            if ($value) {
                $selectCriteria->add(CompanyPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(CompanyPeer::TABLE_NAME);
            }

        } else { // $values is Company object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(CompanyPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the company table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += CompanyPeer::doOnDeleteCascade(new Criteria(CompanyPeer::DATABASE_NAME), $con);
            $affectedRows += BasePeer::doDeleteAll(CompanyPeer::TABLE_NAME, $con, CompanyPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CompanyPeer::clearInstancePool();
            CompanyPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Company or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Company object or primary key or array of primary keys
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
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Company) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CompanyPeer::DATABASE_NAME);
            $criteria->add(CompanyPeer::ID, (array) $values, Criteria::IN);
        }

        // Set the correct dbName
        $criteria->setDbName(CompanyPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            // cloning the Criteria in case it's modified by doSelect() or doSelectStmt()
            $c = clone $criteria;
            $affectedRows += CompanyPeer::doOnDeleteCascade($c, $con);

            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            if ($values instanceof Criteria) {
                CompanyPeer::clearInstancePool();
            } elseif ($values instanceof Company) { // it's a model object
                CompanyPeer::removeInstanceFromPool($values);
            } else { // it's a primary key, or an array of pks
                foreach ((array) $values as $singleval) {
                    CompanyPeer::removeInstanceFromPool($singleval);
                }
            }

            $affectedRows += BasePeer::doDelete($criteria, $con);
            CompanyPeer::clearRelatedInstancePool();
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
        $objects = CompanyPeer::doSelect($criteria, $con);
        foreach ($objects as $obj) {


            // delete related CompanyAddress objects
            $criteria = new Criteria(CompanyAddressPeer::DATABASE_NAME);

            $criteria->add(CompanyAddressPeer::COMPANY_ID, $obj->getId());
            $affectedRows += CompanyAddressPeer::doDelete($criteria, $con);

            // delete related CompanyEmail objects
            $criteria = new Criteria(CompanyEmailPeer::DATABASE_NAME);

            $criteria->add(CompanyEmailPeer::COMPANY_ID, $obj->getId());
            $affectedRows += CompanyEmailPeer::doDelete($criteria, $con);

            // delete related CompanyPhone objects
            $criteria = new Criteria(CompanyPhonePeer::DATABASE_NAME);

            $criteria->add(CompanyPhonePeer::COMPANY_ID, $obj->getId());
            $affectedRows += CompanyPhonePeer::doDelete($criteria, $con);

            // delete related CompanyContact objects
            $criteria = new Criteria(CompanyContactPeer::DATABASE_NAME);

            $criteria->add(CompanyContactPeer::COMPANY_ID, $obj->getId());
            $affectedRows += CompanyContactPeer::doDelete($criteria, $con);

            // delete related CompanyInformant objects
            $criteria = new Criteria(CompanyInformantPeer::DATABASE_NAME);

            $criteria->add(CompanyInformantPeer::COMPANY_ID, $obj->getId());
            $affectedRows += CompanyInformantPeer::doDelete($criteria, $con);

            // delete related CompanyOwner objects
            $criteria = new Criteria(CompanyOwnerPeer::DATABASE_NAME);

            $criteria->add(CompanyOwnerPeer::COMPANY_ID, $obj->getId());
            $affectedRows += CompanyOwnerPeer::doDelete($criteria, $con);
        }

        return $affectedRows;
    }

    /**
     * Validates all modified columns of given Company object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param Company $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(CompanyPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(CompanyPeer::TABLE_NAME);

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

        return BasePeer::doValidate(CompanyPeer::DATABASE_NAME, CompanyPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Company
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = CompanyPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(CompanyPeer::DATABASE_NAME);
        $criteria->add(CompanyPeer::ID, $pk);

        $v = CompanyPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Company[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(CompanyPeer::DATABASE_NAME);
            $criteria->add(CompanyPeer::ID, $pks, Criteria::IN);
            $objs = CompanyPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseCompanyPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseCompanyPeer::buildTableMap();

