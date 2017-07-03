<?php

namespace StoreBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use CollectionBundle\Model\Collection;
use CompanyBundle\Model\Company;
use CompanyBundle\Model\Regions;
use DeviceBundle\Model\CbInput;
use DeviceBundle\Model\ControllerBox;
use DeviceBundle\Model\DeviceGroup;
use DeviceBundle\Model\DsTemperatureSensor;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreAddress;
use StoreBundle\Model\StoreContact;
use StoreBundle\Model\StoreEmail;
use StoreBundle\Model\StoreInformant;
use StoreBundle\Model\StoreOwner;
use StoreBundle\Model\StorePeer;
use StoreBundle\Model\StorePhone;
use StoreBundle\Model\StoreQuery;
use StoreBundle\Model\StoreType;
use UserBundle\Model\Address;
use UserBundle\Model\Email;
use UserBundle\Model\Phone;
use UserBundle\Model\User;

/**
 * @method StoreQuery orderById($order = Criteria::ASC) Order by the id column
 * @method StoreQuery orderByMainCompany($order = Criteria::ASC) Order by the main_company column
 * @method StoreQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method StoreQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method StoreQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method StoreQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method StoreQuery orderByWebsite($order = Criteria::ASC) Order by the website column
 * @method StoreQuery orderByRegion($order = Criteria::ASC) Order by the region column
 * @method StoreQuery orderByRemarks($order = Criteria::ASC) Order by the remarks column
 * @method StoreQuery orderByPaymentMethod($order = Criteria::ASC) Order by the payment_method column
 * @method StoreQuery orderByBankAccountNumber($order = Criteria::ASC) Order by the bank_account_number column
 * @method StoreQuery orderByVatNumber($order = Criteria::ASC) Order by the vat_number column
 * @method StoreQuery orderByCocNumber($order = Criteria::ASC) Order by the coc_number column
 * @method StoreQuery orderByIsMaintenance($order = Criteria::ASC) Order by the is_maintenance column
 * @method StoreQuery orderByIsEnabled($order = Criteria::ASC) Order by the is_enabled column
 * @method StoreQuery orderByIsDeleted($order = Criteria::ASC) Order by the is_deleted column
 * @method StoreQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method StoreQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method StoreQuery groupById() Group by the id column
 * @method StoreQuery groupByMainCompany() Group by the main_company column
 * @method StoreQuery groupByName() Group by the name column
 * @method StoreQuery groupByDescription() Group by the description column
 * @method StoreQuery groupByType() Group by the type column
 * @method StoreQuery groupByCode() Group by the code column
 * @method StoreQuery groupByWebsite() Group by the website column
 * @method StoreQuery groupByRegion() Group by the region column
 * @method StoreQuery groupByRemarks() Group by the remarks column
 * @method StoreQuery groupByPaymentMethod() Group by the payment_method column
 * @method StoreQuery groupByBankAccountNumber() Group by the bank_account_number column
 * @method StoreQuery groupByVatNumber() Group by the vat_number column
 * @method StoreQuery groupByCocNumber() Group by the coc_number column
 * @method StoreQuery groupByIsMaintenance() Group by the is_maintenance column
 * @method StoreQuery groupByIsEnabled() Group by the is_enabled column
 * @method StoreQuery groupByIsDeleted() Group by the is_deleted column
 * @method StoreQuery groupByCreatedAt() Group by the created_at column
 * @method StoreQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method StoreQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method StoreQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method StoreQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method StoreQuery leftJoinCompany($relationAlias = null) Adds a LEFT JOIN clause to the query using the Company relation
 * @method StoreQuery rightJoinCompany($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Company relation
 * @method StoreQuery innerJoinCompany($relationAlias = null) Adds a INNER JOIN clause to the query using the Company relation
 *
 * @method StoreQuery leftJoinStoreType($relationAlias = null) Adds a LEFT JOIN clause to the query using the StoreType relation
 * @method StoreQuery rightJoinStoreType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StoreType relation
 * @method StoreQuery innerJoinStoreType($relationAlias = null) Adds a INNER JOIN clause to the query using the StoreType relation
 *
 * @method StoreQuery leftJoinRegions($relationAlias = null) Adds a LEFT JOIN clause to the query using the Regions relation
 * @method StoreQuery rightJoinRegions($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Regions relation
 * @method StoreQuery innerJoinRegions($relationAlias = null) Adds a INNER JOIN clause to the query using the Regions relation
 *
 * @method StoreQuery leftJoinCollection($relationAlias = null) Adds a LEFT JOIN clause to the query using the Collection relation
 * @method StoreQuery rightJoinCollection($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Collection relation
 * @method StoreQuery innerJoinCollection($relationAlias = null) Adds a INNER JOIN clause to the query using the Collection relation
 *
 * @method StoreQuery leftJoinControllerBox($relationAlias = null) Adds a LEFT JOIN clause to the query using the ControllerBox relation
 * @method StoreQuery rightJoinControllerBox($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ControllerBox relation
 * @method StoreQuery innerJoinControllerBox($relationAlias = null) Adds a INNER JOIN clause to the query using the ControllerBox relation
 *
 * @method StoreQuery leftJoinDeviceGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the DeviceGroup relation
 * @method StoreQuery rightJoinDeviceGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DeviceGroup relation
 * @method StoreQuery innerJoinDeviceGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the DeviceGroup relation
 *
 * @method StoreQuery leftJoinDsTemperatureSensor($relationAlias = null) Adds a LEFT JOIN clause to the query using the DsTemperatureSensor relation
 * @method StoreQuery rightJoinDsTemperatureSensor($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DsTemperatureSensor relation
 * @method StoreQuery innerJoinDsTemperatureSensor($relationAlias = null) Adds a INNER JOIN clause to the query using the DsTemperatureSensor relation
 *
 * @method StoreQuery leftJoinCbInput($relationAlias = null) Adds a LEFT JOIN clause to the query using the CbInput relation
 * @method StoreQuery rightJoinCbInput($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CbInput relation
 * @method StoreQuery innerJoinCbInput($relationAlias = null) Adds a INNER JOIN clause to the query using the CbInput relation
 *
 * @method StoreQuery leftJoinStoreAddress($relationAlias = null) Adds a LEFT JOIN clause to the query using the StoreAddress relation
 * @method StoreQuery rightJoinStoreAddress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StoreAddress relation
 * @method StoreQuery innerJoinStoreAddress($relationAlias = null) Adds a INNER JOIN clause to the query using the StoreAddress relation
 *
 * @method StoreQuery leftJoinStoreEmail($relationAlias = null) Adds a LEFT JOIN clause to the query using the StoreEmail relation
 * @method StoreQuery rightJoinStoreEmail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StoreEmail relation
 * @method StoreQuery innerJoinStoreEmail($relationAlias = null) Adds a INNER JOIN clause to the query using the StoreEmail relation
 *
 * @method StoreQuery leftJoinStorePhone($relationAlias = null) Adds a LEFT JOIN clause to the query using the StorePhone relation
 * @method StoreQuery rightJoinStorePhone($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StorePhone relation
 * @method StoreQuery innerJoinStorePhone($relationAlias = null) Adds a INNER JOIN clause to the query using the StorePhone relation
 *
 * @method StoreQuery leftJoinStoreContact($relationAlias = null) Adds a LEFT JOIN clause to the query using the StoreContact relation
 * @method StoreQuery rightJoinStoreContact($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StoreContact relation
 * @method StoreQuery innerJoinStoreContact($relationAlias = null) Adds a INNER JOIN clause to the query using the StoreContact relation
 *
 * @method StoreQuery leftJoinStoreInformant($relationAlias = null) Adds a LEFT JOIN clause to the query using the StoreInformant relation
 * @method StoreQuery rightJoinStoreInformant($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StoreInformant relation
 * @method StoreQuery innerJoinStoreInformant($relationAlias = null) Adds a INNER JOIN clause to the query using the StoreInformant relation
 *
 * @method StoreQuery leftJoinStoreOwner($relationAlias = null) Adds a LEFT JOIN clause to the query using the StoreOwner relation
 * @method StoreQuery rightJoinStoreOwner($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StoreOwner relation
 * @method StoreQuery innerJoinStoreOwner($relationAlias = null) Adds a INNER JOIN clause to the query using the StoreOwner relation
 *
 * @method Store findOne(PropelPDO $con = null) Return the first Store matching the query
 * @method Store findOneOrCreate(PropelPDO $con = null) Return the first Store matching the query, or a new Store object populated from the query conditions when no match is found
 *
 * @method Store findOneByMainCompany(int $main_company) Return the first Store filtered by the main_company column
 * @method Store findOneByName(string $name) Return the first Store filtered by the name column
 * @method Store findOneByDescription(string $description) Return the first Store filtered by the description column
 * @method Store findOneByType(int $type) Return the first Store filtered by the type column
 * @method Store findOneByCode(string $code) Return the first Store filtered by the code column
 * @method Store findOneByWebsite(string $website) Return the first Store filtered by the website column
 * @method Store findOneByRegion(int $region) Return the first Store filtered by the region column
 * @method Store findOneByRemarks(string $remarks) Return the first Store filtered by the remarks column
 * @method Store findOneByPaymentMethod(int $payment_method) Return the first Store filtered by the payment_method column
 * @method Store findOneByBankAccountNumber(string $bank_account_number) Return the first Store filtered by the bank_account_number column
 * @method Store findOneByVatNumber(string $vat_number) Return the first Store filtered by the vat_number column
 * @method Store findOneByCocNumber(string $coc_number) Return the first Store filtered by the coc_number column
 * @method Store findOneByIsMaintenance(boolean $is_maintenance) Return the first Store filtered by the is_maintenance column
 * @method Store findOneByIsEnabled(boolean $is_enabled) Return the first Store filtered by the is_enabled column
 * @method Store findOneByIsDeleted(boolean $is_deleted) Return the first Store filtered by the is_deleted column
 * @method Store findOneByCreatedAt(string $created_at) Return the first Store filtered by the created_at column
 * @method Store findOneByUpdatedAt(string $updated_at) Return the first Store filtered by the updated_at column
 *
 * @method array findById(int $id) Return Store objects filtered by the id column
 * @method array findByMainCompany(int $main_company) Return Store objects filtered by the main_company column
 * @method array findByName(string $name) Return Store objects filtered by the name column
 * @method array findByDescription(string $description) Return Store objects filtered by the description column
 * @method array findByType(int $type) Return Store objects filtered by the type column
 * @method array findByCode(string $code) Return Store objects filtered by the code column
 * @method array findByWebsite(string $website) Return Store objects filtered by the website column
 * @method array findByRegion(int $region) Return Store objects filtered by the region column
 * @method array findByRemarks(string $remarks) Return Store objects filtered by the remarks column
 * @method array findByPaymentMethod(int $payment_method) Return Store objects filtered by the payment_method column
 * @method array findByBankAccountNumber(string $bank_account_number) Return Store objects filtered by the bank_account_number column
 * @method array findByVatNumber(string $vat_number) Return Store objects filtered by the vat_number column
 * @method array findByCocNumber(string $coc_number) Return Store objects filtered by the coc_number column
 * @method array findByIsMaintenance(boolean $is_maintenance) Return Store objects filtered by the is_maintenance column
 * @method array findByIsEnabled(boolean $is_enabled) Return Store objects filtered by the is_enabled column
 * @method array findByIsDeleted(boolean $is_deleted) Return Store objects filtered by the is_deleted column
 * @method array findByCreatedAt(string $created_at) Return Store objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Store objects filtered by the updated_at column
 */
abstract class BaseStoreQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseStoreQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'StoreBundle\\Model\\Store';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new StoreQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   StoreQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return StoreQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof StoreQuery) {
            return $criteria;
        }
        $query = new StoreQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Store|Store[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = StorePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(StorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Store A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Store A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `main_company`, `name`, `description`, `type`, `code`, `website`, `region`, `remarks`, `payment_method`, `bank_account_number`, `vat_number`, `coc_number`, `is_maintenance`, `is_enabled`, `is_deleted`, `created_at`, `updated_at` FROM `store` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Store();
            $obj->hydrate($row);
            StorePeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Store|Store[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Store[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(StorePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(StorePeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(StorePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(StorePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StorePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the main_company column
     *
     * Example usage:
     * <code>
     * $query->filterByMainCompany(1234); // WHERE main_company = 1234
     * $query->filterByMainCompany(array(12, 34)); // WHERE main_company IN (12, 34)
     * $query->filterByMainCompany(array('min' => 12)); // WHERE main_company >= 12
     * $query->filterByMainCompany(array('max' => 12)); // WHERE main_company <= 12
     * </code>
     *
     * @see       filterByCompany()
     *
     * @param     mixed $mainCompany The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByMainCompany($mainCompany = null, $comparison = null)
    {
        if (is_array($mainCompany)) {
            $useMinMax = false;
            if (isset($mainCompany['min'])) {
                $this->addUsingAlias(StorePeer::MAIN_COMPANY, $mainCompany['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($mainCompany['max'])) {
                $this->addUsingAlias(StorePeer::MAIN_COMPANY, $mainCompany['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StorePeer::MAIN_COMPANY, $mainCompany, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StorePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StorePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType(1234); // WHERE type = 1234
     * $query->filterByType(array(12, 34)); // WHERE type IN (12, 34)
     * $query->filterByType(array('min' => 12)); // WHERE type >= 12
     * $query->filterByType(array('max' => 12)); // WHERE type <= 12
     * </code>
     *
     * @see       filterByStoreType()
     *
     * @param     mixed $type The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (is_array($type)) {
            $useMinMax = false;
            if (isset($type['min'])) {
                $this->addUsingAlias(StorePeer::TYPE, $type['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($type['max'])) {
                $this->addUsingAlias(StorePeer::TYPE, $type['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StorePeer::TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StorePeer::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the website column
     *
     * Example usage:
     * <code>
     * $query->filterByWebsite('fooValue');   // WHERE website = 'fooValue'
     * $query->filterByWebsite('%fooValue%'); // WHERE website LIKE '%fooValue%'
     * </code>
     *
     * @param     string $website The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByWebsite($website = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($website)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $website)) {
                $website = str_replace('*', '%', $website);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StorePeer::WEBSITE, $website, $comparison);
    }

    /**
     * Filter the query on the region column
     *
     * Example usage:
     * <code>
     * $query->filterByRegion(1234); // WHERE region = 1234
     * $query->filterByRegion(array(12, 34)); // WHERE region IN (12, 34)
     * $query->filterByRegion(array('min' => 12)); // WHERE region >= 12
     * $query->filterByRegion(array('max' => 12)); // WHERE region <= 12
     * </code>
     *
     * @see       filterByRegions()
     *
     * @param     mixed $region The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByRegion($region = null, $comparison = null)
    {
        if (is_array($region)) {
            $useMinMax = false;
            if (isset($region['min'])) {
                $this->addUsingAlias(StorePeer::REGION, $region['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($region['max'])) {
                $this->addUsingAlias(StorePeer::REGION, $region['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StorePeer::REGION, $region, $comparison);
    }

    /**
     * Filter the query on the remarks column
     *
     * Example usage:
     * <code>
     * $query->filterByRemarks('fooValue');   // WHERE remarks = 'fooValue'
     * $query->filterByRemarks('%fooValue%'); // WHERE remarks LIKE '%fooValue%'
     * </code>
     *
     * @param     string $remarks The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByRemarks($remarks = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($remarks)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $remarks)) {
                $remarks = str_replace('*', '%', $remarks);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StorePeer::REMARKS, $remarks, $comparison);
    }

    /**
     * Filter the query on the payment_method column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentMethod(1234); // WHERE payment_method = 1234
     * $query->filterByPaymentMethod(array(12, 34)); // WHERE payment_method IN (12, 34)
     * $query->filterByPaymentMethod(array('min' => 12)); // WHERE payment_method >= 12
     * $query->filterByPaymentMethod(array('max' => 12)); // WHERE payment_method <= 12
     * </code>
     *
     * @param     mixed $paymentMethod The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByPaymentMethod($paymentMethod = null, $comparison = null)
    {
        if (is_array($paymentMethod)) {
            $useMinMax = false;
            if (isset($paymentMethod['min'])) {
                $this->addUsingAlias(StorePeer::PAYMENT_METHOD, $paymentMethod['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentMethod['max'])) {
                $this->addUsingAlias(StorePeer::PAYMENT_METHOD, $paymentMethod['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StorePeer::PAYMENT_METHOD, $paymentMethod, $comparison);
    }

    /**
     * Filter the query on the bank_account_number column
     *
     * Example usage:
     * <code>
     * $query->filterByBankAccountNumber('fooValue');   // WHERE bank_account_number = 'fooValue'
     * $query->filterByBankAccountNumber('%fooValue%'); // WHERE bank_account_number LIKE '%fooValue%'
     * </code>
     *
     * @param     string $bankAccountNumber The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByBankAccountNumber($bankAccountNumber = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bankAccountNumber)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $bankAccountNumber)) {
                $bankAccountNumber = str_replace('*', '%', $bankAccountNumber);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StorePeer::BANK_ACCOUNT_NUMBER, $bankAccountNumber, $comparison);
    }

    /**
     * Filter the query on the vat_number column
     *
     * Example usage:
     * <code>
     * $query->filterByVatNumber('fooValue');   // WHERE vat_number = 'fooValue'
     * $query->filterByVatNumber('%fooValue%'); // WHERE vat_number LIKE '%fooValue%'
     * </code>
     *
     * @param     string $vatNumber The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByVatNumber($vatNumber = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($vatNumber)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $vatNumber)) {
                $vatNumber = str_replace('*', '%', $vatNumber);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StorePeer::VAT_NUMBER, $vatNumber, $comparison);
    }

    /**
     * Filter the query on the coc_number column
     *
     * Example usage:
     * <code>
     * $query->filterByCocNumber('fooValue');   // WHERE coc_number = 'fooValue'
     * $query->filterByCocNumber('%fooValue%'); // WHERE coc_number LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cocNumber The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByCocNumber($cocNumber = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cocNumber)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $cocNumber)) {
                $cocNumber = str_replace('*', '%', $cocNumber);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StorePeer::COC_NUMBER, $cocNumber, $comparison);
    }

    /**
     * Filter the query on the is_maintenance column
     *
     * Example usage:
     * <code>
     * $query->filterByIsMaintenance(true); // WHERE is_maintenance = true
     * $query->filterByIsMaintenance('yes'); // WHERE is_maintenance = true
     * </code>
     *
     * @param     boolean|string $isMaintenance The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByIsMaintenance($isMaintenance = null, $comparison = null)
    {
        if (is_string($isMaintenance)) {
            $isMaintenance = in_array(strtolower($isMaintenance), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(StorePeer::IS_MAINTENANCE, $isMaintenance, $comparison);
    }

    /**
     * Filter the query on the is_enabled column
     *
     * Example usage:
     * <code>
     * $query->filterByIsEnabled(true); // WHERE is_enabled = true
     * $query->filterByIsEnabled('yes'); // WHERE is_enabled = true
     * </code>
     *
     * @param     boolean|string $isEnabled The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByIsEnabled($isEnabled = null, $comparison = null)
    {
        if (is_string($isEnabled)) {
            $isEnabled = in_array(strtolower($isEnabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(StorePeer::IS_ENABLED, $isEnabled, $comparison);
    }

    /**
     * Filter the query on the is_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByIsDeleted(true); // WHERE is_deleted = true
     * $query->filterByIsDeleted('yes'); // WHERE is_deleted = true
     * </code>
     *
     * @param     boolean|string $isDeleted The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByIsDeleted($isDeleted = null, $comparison = null)
    {
        if (is_string($isDeleted)) {
            $isDeleted = in_array(strtolower($isDeleted), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(StorePeer::IS_DELETED, $isDeleted, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(StorePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(StorePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StorePeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(StorePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(StorePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StorePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Company object
     *
     * @param   Company|PropelObjectCollection $company The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompany($company, $comparison = null)
    {
        if ($company instanceof Company) {
            return $this
                ->addUsingAlias(StorePeer::MAIN_COMPANY, $company->getId(), $comparison);
        } elseif ($company instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StorePeer::MAIN_COMPANY, $company->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCompany() only accepts arguments of type Company or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Company relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinCompany($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Company');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Company');
        }

        return $this;
    }

    /**
     * Use the Company relation Company object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\CompanyQuery A secondary query class using the current class as primary query
     */
    public function useCompanyQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCompany($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Company', '\CompanyBundle\Model\CompanyQuery');
    }

    /**
     * Filter the query by a related StoreType object
     *
     * @param   StoreType|PropelObjectCollection $storeType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStoreType($storeType, $comparison = null)
    {
        if ($storeType instanceof StoreType) {
            return $this
                ->addUsingAlias(StorePeer::TYPE, $storeType->getId(), $comparison);
        } elseif ($storeType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StorePeer::TYPE, $storeType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByStoreType() only accepts arguments of type StoreType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StoreType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinStoreType($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StoreType');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'StoreType');
        }

        return $this;
    }

    /**
     * Use the StoreType relation StoreType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreTypeQuery A secondary query class using the current class as primary query
     */
    public function useStoreTypeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStoreType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StoreType', '\StoreBundle\Model\StoreTypeQuery');
    }

    /**
     * Filter the query by a related Regions object
     *
     * @param   Regions|PropelObjectCollection $regions The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRegions($regions, $comparison = null)
    {
        if ($regions instanceof Regions) {
            return $this
                ->addUsingAlias(StorePeer::REGION, $regions->getId(), $comparison);
        } elseif ($regions instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StorePeer::REGION, $regions->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByRegions() only accepts arguments of type Regions or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Regions relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinRegions($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Regions');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Regions');
        }

        return $this;
    }

    /**
     * Use the Regions relation Regions object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\RegionsQuery A secondary query class using the current class as primary query
     */
    public function useRegionsQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRegions($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Regions', '\CompanyBundle\Model\RegionsQuery');
    }

    /**
     * Filter the query by a related Collection object
     *
     * @param   Collection|PropelObjectCollection $collection  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCollection($collection, $comparison = null)
    {
        if ($collection instanceof Collection) {
            return $this
                ->addUsingAlias(StorePeer::ID, $collection->getCollectionStore(), $comparison);
        } elseif ($collection instanceof PropelObjectCollection) {
            return $this
                ->useCollectionQuery()
                ->filterByPrimaryKeys($collection->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCollection() only accepts arguments of type Collection or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Collection relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinCollection($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Collection');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Collection');
        }

        return $this;
    }

    /**
     * Use the Collection relation Collection object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CollectionBundle\Model\CollectionQuery A secondary query class using the current class as primary query
     */
    public function useCollectionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCollection($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Collection', '\CollectionBundle\Model\CollectionQuery');
    }

    /**
     * Filter the query by a related ControllerBox object
     *
     * @param   ControllerBox|PropelObjectCollection $controllerBox  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByControllerBox($controllerBox, $comparison = null)
    {
        if ($controllerBox instanceof ControllerBox) {
            return $this
                ->addUsingAlias(StorePeer::ID, $controllerBox->getMainStore(), $comparison);
        } elseif ($controllerBox instanceof PropelObjectCollection) {
            return $this
                ->useControllerBoxQuery()
                ->filterByPrimaryKeys($controllerBox->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByControllerBox() only accepts arguments of type ControllerBox or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ControllerBox relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinControllerBox($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ControllerBox');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ControllerBox');
        }

        return $this;
    }

    /**
     * Use the ControllerBox relation ControllerBox object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DeviceBundle\Model\ControllerBoxQuery A secondary query class using the current class as primary query
     */
    public function useControllerBoxQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinControllerBox($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ControllerBox', '\DeviceBundle\Model\ControllerBoxQuery');
    }

    /**
     * Filter the query by a related DeviceGroup object
     *
     * @param   DeviceGroup|PropelObjectCollection $deviceGroup  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDeviceGroup($deviceGroup, $comparison = null)
    {
        if ($deviceGroup instanceof DeviceGroup) {
            return $this
                ->addUsingAlias(StorePeer::ID, $deviceGroup->getMainStore(), $comparison);
        } elseif ($deviceGroup instanceof PropelObjectCollection) {
            return $this
                ->useDeviceGroupQuery()
                ->filterByPrimaryKeys($deviceGroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDeviceGroup() only accepts arguments of type DeviceGroup or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DeviceGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinDeviceGroup($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DeviceGroup');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'DeviceGroup');
        }

        return $this;
    }

    /**
     * Use the DeviceGroup relation DeviceGroup object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DeviceBundle\Model\DeviceGroupQuery A secondary query class using the current class as primary query
     */
    public function useDeviceGroupQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDeviceGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DeviceGroup', '\DeviceBundle\Model\DeviceGroupQuery');
    }

    /**
     * Filter the query by a related DsTemperatureSensor object
     *
     * @param   DsTemperatureSensor|PropelObjectCollection $dsTemperatureSensor  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDsTemperatureSensor($dsTemperatureSensor, $comparison = null)
    {
        if ($dsTemperatureSensor instanceof DsTemperatureSensor) {
            return $this
                ->addUsingAlias(StorePeer::ID, $dsTemperatureSensor->getMainStore(), $comparison);
        } elseif ($dsTemperatureSensor instanceof PropelObjectCollection) {
            return $this
                ->useDsTemperatureSensorQuery()
                ->filterByPrimaryKeys($dsTemperatureSensor->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDsTemperatureSensor() only accepts arguments of type DsTemperatureSensor or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DsTemperatureSensor relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinDsTemperatureSensor($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DsTemperatureSensor');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'DsTemperatureSensor');
        }

        return $this;
    }

    /**
     * Use the DsTemperatureSensor relation DsTemperatureSensor object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DeviceBundle\Model\DsTemperatureSensorQuery A secondary query class using the current class as primary query
     */
    public function useDsTemperatureSensorQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDsTemperatureSensor($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DsTemperatureSensor', '\DeviceBundle\Model\DsTemperatureSensorQuery');
    }

    /**
     * Filter the query by a related CbInput object
     *
     * @param   CbInput|PropelObjectCollection $cbInput  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCbInput($cbInput, $comparison = null)
    {
        if ($cbInput instanceof CbInput) {
            return $this
                ->addUsingAlias(StorePeer::ID, $cbInput->getMainStore(), $comparison);
        } elseif ($cbInput instanceof PropelObjectCollection) {
            return $this
                ->useCbInputQuery()
                ->filterByPrimaryKeys($cbInput->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCbInput() only accepts arguments of type CbInput or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CbInput relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinCbInput($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CbInput');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CbInput');
        }

        return $this;
    }

    /**
     * Use the CbInput relation CbInput object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DeviceBundle\Model\CbInputQuery A secondary query class using the current class as primary query
     */
    public function useCbInputQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCbInput($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CbInput', '\DeviceBundle\Model\CbInputQuery');
    }

    /**
     * Filter the query by a related StoreAddress object
     *
     * @param   StoreAddress|PropelObjectCollection $storeAddress  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStoreAddress($storeAddress, $comparison = null)
    {
        if ($storeAddress instanceof StoreAddress) {
            return $this
                ->addUsingAlias(StorePeer::ID, $storeAddress->getStoreId(), $comparison);
        } elseif ($storeAddress instanceof PropelObjectCollection) {
            return $this
                ->useStoreAddressQuery()
                ->filterByPrimaryKeys($storeAddress->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStoreAddress() only accepts arguments of type StoreAddress or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StoreAddress relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinStoreAddress($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StoreAddress');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'StoreAddress');
        }

        return $this;
    }

    /**
     * Use the StoreAddress relation StoreAddress object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreAddressQuery A secondary query class using the current class as primary query
     */
    public function useStoreAddressQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStoreAddress($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StoreAddress', '\StoreBundle\Model\StoreAddressQuery');
    }

    /**
     * Filter the query by a related StoreEmail object
     *
     * @param   StoreEmail|PropelObjectCollection $storeEmail  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStoreEmail($storeEmail, $comparison = null)
    {
        if ($storeEmail instanceof StoreEmail) {
            return $this
                ->addUsingAlias(StorePeer::ID, $storeEmail->getStoreId(), $comparison);
        } elseif ($storeEmail instanceof PropelObjectCollection) {
            return $this
                ->useStoreEmailQuery()
                ->filterByPrimaryKeys($storeEmail->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStoreEmail() only accepts arguments of type StoreEmail or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StoreEmail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinStoreEmail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StoreEmail');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'StoreEmail');
        }

        return $this;
    }

    /**
     * Use the StoreEmail relation StoreEmail object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreEmailQuery A secondary query class using the current class as primary query
     */
    public function useStoreEmailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStoreEmail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StoreEmail', '\StoreBundle\Model\StoreEmailQuery');
    }

    /**
     * Filter the query by a related StorePhone object
     *
     * @param   StorePhone|PropelObjectCollection $storePhone  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStorePhone($storePhone, $comparison = null)
    {
        if ($storePhone instanceof StorePhone) {
            return $this
                ->addUsingAlias(StorePeer::ID, $storePhone->getStoreId(), $comparison);
        } elseif ($storePhone instanceof PropelObjectCollection) {
            return $this
                ->useStorePhoneQuery()
                ->filterByPrimaryKeys($storePhone->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStorePhone() only accepts arguments of type StorePhone or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StorePhone relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinStorePhone($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StorePhone');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'StorePhone');
        }

        return $this;
    }

    /**
     * Use the StorePhone relation StorePhone object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StorePhoneQuery A secondary query class using the current class as primary query
     */
    public function useStorePhoneQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStorePhone($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StorePhone', '\StoreBundle\Model\StorePhoneQuery');
    }

    /**
     * Filter the query by a related StoreContact object
     *
     * @param   StoreContact|PropelObjectCollection $storeContact  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStoreContact($storeContact, $comparison = null)
    {
        if ($storeContact instanceof StoreContact) {
            return $this
                ->addUsingAlias(StorePeer::ID, $storeContact->getStoreId(), $comparison);
        } elseif ($storeContact instanceof PropelObjectCollection) {
            return $this
                ->useStoreContactQuery()
                ->filterByPrimaryKeys($storeContact->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStoreContact() only accepts arguments of type StoreContact or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StoreContact relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinStoreContact($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StoreContact');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'StoreContact');
        }

        return $this;
    }

    /**
     * Use the StoreContact relation StoreContact object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreContactQuery A secondary query class using the current class as primary query
     */
    public function useStoreContactQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStoreContact($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StoreContact', '\StoreBundle\Model\StoreContactQuery');
    }

    /**
     * Filter the query by a related StoreInformant object
     *
     * @param   StoreInformant|PropelObjectCollection $storeInformant  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStoreInformant($storeInformant, $comparison = null)
    {
        if ($storeInformant instanceof StoreInformant) {
            return $this
                ->addUsingAlias(StorePeer::ID, $storeInformant->getStoreId(), $comparison);
        } elseif ($storeInformant instanceof PropelObjectCollection) {
            return $this
                ->useStoreInformantQuery()
                ->filterByPrimaryKeys($storeInformant->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStoreInformant() only accepts arguments of type StoreInformant or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StoreInformant relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinStoreInformant($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StoreInformant');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'StoreInformant');
        }

        return $this;
    }

    /**
     * Use the StoreInformant relation StoreInformant object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreInformantQuery A secondary query class using the current class as primary query
     */
    public function useStoreInformantQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStoreInformant($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StoreInformant', '\StoreBundle\Model\StoreInformantQuery');
    }

    /**
     * Filter the query by a related StoreOwner object
     *
     * @param   StoreOwner|PropelObjectCollection $storeOwner  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStoreOwner($storeOwner, $comparison = null)
    {
        if ($storeOwner instanceof StoreOwner) {
            return $this
                ->addUsingAlias(StorePeer::ID, $storeOwner->getStoreId(), $comparison);
        } elseif ($storeOwner instanceof PropelObjectCollection) {
            return $this
                ->useStoreOwnerQuery()
                ->filterByPrimaryKeys($storeOwner->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStoreOwner() only accepts arguments of type StoreOwner or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StoreOwner relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function joinStoreOwner($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StoreOwner');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'StoreOwner');
        }

        return $this;
    }

    /**
     * Use the StoreOwner relation StoreOwner object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreOwnerQuery A secondary query class using the current class as primary query
     */
    public function useStoreOwnerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStoreOwner($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StoreOwner', '\StoreBundle\Model\StoreOwnerQuery');
    }

    /**
     * Filter the query by a related Address object
     * using the store_address table as cross reference
     *
     * @param   Address $address the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   StoreQuery The current query, for fluid interface
     */
    public function filterByAddress($address, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useStoreAddressQuery()
            ->filterByAddress($address, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Email object
     * using the store_email table as cross reference
     *
     * @param   Email $email the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   StoreQuery The current query, for fluid interface
     */
    public function filterByEmail($email, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useStoreEmailQuery()
            ->filterByEmail($email, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Phone object
     * using the store_phone table as cross reference
     *
     * @param   Phone $phone the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   StoreQuery The current query, for fluid interface
     */
    public function filterByPhone($phone, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useStorePhoneQuery()
            ->filterByPhone($phone, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related User object
     * using the store_contact table as cross reference
     *
     * @param   User $user the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   StoreQuery The current query, for fluid interface
     */
    public function filterByContact($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useStoreContactQuery()
            ->filterByContact($user, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related User object
     * using the store_informant table as cross reference
     *
     * @param   User $user the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   StoreQuery The current query, for fluid interface
     */
    public function filterByInformant($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useStoreInformantQuery()
            ->filterByInformant($user, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related User object
     * using the store_owner table as cross reference
     *
     * @param   User $user the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   StoreQuery The current query, for fluid interface
     */
    public function filterByOwner($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useStoreOwnerQuery()
            ->filterByOwner($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   Store $store Object to remove from the list of results
     *
     * @return StoreQuery The current query, for fluid interface
     */
    public function prune($store = null)
    {
        if ($store) {
            $this->addUsingAlias(StorePeer::ID, $store->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     StoreQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(StorePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     StoreQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(StorePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     StoreQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(StorePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     StoreQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(StorePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     StoreQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(StorePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     StoreQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(StorePeer::CREATED_AT);
    }
}
