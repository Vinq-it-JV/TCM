<?php

namespace CompanyBundle\Model\om;

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
use CompanyBundle\Model\Company;
use CompanyBundle\Model\CompanyAddress;
use CompanyBundle\Model\CompanyContact;
use CompanyBundle\Model\CompanyEmail;
use CompanyBundle\Model\CompanyInformant;
use CompanyBundle\Model\CompanyOwner;
use CompanyBundle\Model\CompanyPeer;
use CompanyBundle\Model\CompanyPhone;
use CompanyBundle\Model\CompanyQuery;
use CompanyBundle\Model\CompanyType;
use CompanyBundle\Model\Regions;
use StoreBundle\Model\Store;
use UserBundle\Model\Address;
use UserBundle\Model\Email;
use UserBundle\Model\Phone;
use UserBundle\Model\User;

/**
 * @method CompanyQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CompanyQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method CompanyQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method CompanyQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method CompanyQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method CompanyQuery orderByWebsite($order = Criteria::ASC) Order by the website column
 * @method CompanyQuery orderByRegion($order = Criteria::ASC) Order by the region column
 * @method CompanyQuery orderByRemarks($order = Criteria::ASC) Order by the remarks column
 * @method CompanyQuery orderByPaymentMethod($order = Criteria::ASC) Order by the payment_method column
 * @method CompanyQuery orderByBankAccountNumber($order = Criteria::ASC) Order by the bank_account_number column
 * @method CompanyQuery orderByVatNumber($order = Criteria::ASC) Order by the vat_number column
 * @method CompanyQuery orderByCocNumber($order = Criteria::ASC) Order by the coc_number column
 * @method CompanyQuery orderByIsEnabled($order = Criteria::ASC) Order by the is_enabled column
 * @method CompanyQuery orderByIsDeleted($order = Criteria::ASC) Order by the is_deleted column
 * @method CompanyQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method CompanyQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method CompanyQuery groupById() Group by the id column
 * @method CompanyQuery groupByName() Group by the name column
 * @method CompanyQuery groupByDescription() Group by the description column
 * @method CompanyQuery groupByType() Group by the type column
 * @method CompanyQuery groupByCode() Group by the code column
 * @method CompanyQuery groupByWebsite() Group by the website column
 * @method CompanyQuery groupByRegion() Group by the region column
 * @method CompanyQuery groupByRemarks() Group by the remarks column
 * @method CompanyQuery groupByPaymentMethod() Group by the payment_method column
 * @method CompanyQuery groupByBankAccountNumber() Group by the bank_account_number column
 * @method CompanyQuery groupByVatNumber() Group by the vat_number column
 * @method CompanyQuery groupByCocNumber() Group by the coc_number column
 * @method CompanyQuery groupByIsEnabled() Group by the is_enabled column
 * @method CompanyQuery groupByIsDeleted() Group by the is_deleted column
 * @method CompanyQuery groupByCreatedAt() Group by the created_at column
 * @method CompanyQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method CompanyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CompanyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CompanyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CompanyQuery leftJoinCompanyType($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompanyType relation
 * @method CompanyQuery rightJoinCompanyType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompanyType relation
 * @method CompanyQuery innerJoinCompanyType($relationAlias = null) Adds a INNER JOIN clause to the query using the CompanyType relation
 *
 * @method CompanyQuery leftJoinRegions($relationAlias = null) Adds a LEFT JOIN clause to the query using the Regions relation
 * @method CompanyQuery rightJoinRegions($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Regions relation
 * @method CompanyQuery innerJoinRegions($relationAlias = null) Adds a INNER JOIN clause to the query using the Regions relation
 *
 * @method CompanyQuery leftJoinCompanyAddress($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompanyAddress relation
 * @method CompanyQuery rightJoinCompanyAddress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompanyAddress relation
 * @method CompanyQuery innerJoinCompanyAddress($relationAlias = null) Adds a INNER JOIN clause to the query using the CompanyAddress relation
 *
 * @method CompanyQuery leftJoinCompanyEmail($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompanyEmail relation
 * @method CompanyQuery rightJoinCompanyEmail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompanyEmail relation
 * @method CompanyQuery innerJoinCompanyEmail($relationAlias = null) Adds a INNER JOIN clause to the query using the CompanyEmail relation
 *
 * @method CompanyQuery leftJoinCompanyPhone($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompanyPhone relation
 * @method CompanyQuery rightJoinCompanyPhone($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompanyPhone relation
 * @method CompanyQuery innerJoinCompanyPhone($relationAlias = null) Adds a INNER JOIN clause to the query using the CompanyPhone relation
 *
 * @method CompanyQuery leftJoinCompanyContact($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompanyContact relation
 * @method CompanyQuery rightJoinCompanyContact($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompanyContact relation
 * @method CompanyQuery innerJoinCompanyContact($relationAlias = null) Adds a INNER JOIN clause to the query using the CompanyContact relation
 *
 * @method CompanyQuery leftJoinCompanyInformant($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompanyInformant relation
 * @method CompanyQuery rightJoinCompanyInformant($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompanyInformant relation
 * @method CompanyQuery innerJoinCompanyInformant($relationAlias = null) Adds a INNER JOIN clause to the query using the CompanyInformant relation
 *
 * @method CompanyQuery leftJoinCompanyOwner($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompanyOwner relation
 * @method CompanyQuery rightJoinCompanyOwner($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompanyOwner relation
 * @method CompanyQuery innerJoinCompanyOwner($relationAlias = null) Adds a INNER JOIN clause to the query using the CompanyOwner relation
 *
 * @method CompanyQuery leftJoinStore($relationAlias = null) Adds a LEFT JOIN clause to the query using the Store relation
 * @method CompanyQuery rightJoinStore($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Store relation
 * @method CompanyQuery innerJoinStore($relationAlias = null) Adds a INNER JOIN clause to the query using the Store relation
 *
 * @method Company findOne(PropelPDO $con = null) Return the first Company matching the query
 * @method Company findOneOrCreate(PropelPDO $con = null) Return the first Company matching the query, or a new Company object populated from the query conditions when no match is found
 *
 * @method Company findOneByName(string $name) Return the first Company filtered by the name column
 * @method Company findOneByDescription(string $description) Return the first Company filtered by the description column
 * @method Company findOneByType(int $type) Return the first Company filtered by the type column
 * @method Company findOneByCode(string $code) Return the first Company filtered by the code column
 * @method Company findOneByWebsite(string $website) Return the first Company filtered by the website column
 * @method Company findOneByRegion(int $region) Return the first Company filtered by the region column
 * @method Company findOneByRemarks(string $remarks) Return the first Company filtered by the remarks column
 * @method Company findOneByPaymentMethod(int $payment_method) Return the first Company filtered by the payment_method column
 * @method Company findOneByBankAccountNumber(string $bank_account_number) Return the first Company filtered by the bank_account_number column
 * @method Company findOneByVatNumber(string $vat_number) Return the first Company filtered by the vat_number column
 * @method Company findOneByCocNumber(string $coc_number) Return the first Company filtered by the coc_number column
 * @method Company findOneByIsEnabled(boolean $is_enabled) Return the first Company filtered by the is_enabled column
 * @method Company findOneByIsDeleted(boolean $is_deleted) Return the first Company filtered by the is_deleted column
 * @method Company findOneByCreatedAt(string $created_at) Return the first Company filtered by the created_at column
 * @method Company findOneByUpdatedAt(string $updated_at) Return the first Company filtered by the updated_at column
 *
 * @method array findById(int $id) Return Company objects filtered by the id column
 * @method array findByName(string $name) Return Company objects filtered by the name column
 * @method array findByDescription(string $description) Return Company objects filtered by the description column
 * @method array findByType(int $type) Return Company objects filtered by the type column
 * @method array findByCode(string $code) Return Company objects filtered by the code column
 * @method array findByWebsite(string $website) Return Company objects filtered by the website column
 * @method array findByRegion(int $region) Return Company objects filtered by the region column
 * @method array findByRemarks(string $remarks) Return Company objects filtered by the remarks column
 * @method array findByPaymentMethod(int $payment_method) Return Company objects filtered by the payment_method column
 * @method array findByBankAccountNumber(string $bank_account_number) Return Company objects filtered by the bank_account_number column
 * @method array findByVatNumber(string $vat_number) Return Company objects filtered by the vat_number column
 * @method array findByCocNumber(string $coc_number) Return Company objects filtered by the coc_number column
 * @method array findByIsEnabled(boolean $is_enabled) Return Company objects filtered by the is_enabled column
 * @method array findByIsDeleted(boolean $is_deleted) Return Company objects filtered by the is_deleted column
 * @method array findByCreatedAt(string $created_at) Return Company objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Company objects filtered by the updated_at column
 */
abstract class BaseCompanyQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCompanyQuery object.
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
            $modelName = 'CompanyBundle\\Model\\Company';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CompanyQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CompanyQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CompanyQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CompanyQuery) {
            return $criteria;
        }
        $query = new CompanyQuery(null, null, $modelAlias);

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
     * @return   Company|Company[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CompanyPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CompanyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Company A model object, or null if the key is not found
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
     * @return                 Company A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `description`, `type`, `code`, `website`, `region`, `remarks`, `payment_method`, `bank_account_number`, `vat_number`, `coc_number`, `is_enabled`, `is_deleted`, `created_at`, `updated_at` FROM `company` WHERE `id` = :p0';
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
            $obj = new Company();
            $obj->hydrate($row);
            CompanyPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Company|Company[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Company[]|mixed the list of results, formatted by the current formatter
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
     * @return CompanyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CompanyPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CompanyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CompanyPeer::ID, $keys, Criteria::IN);
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
     * @return CompanyQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CompanyPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CompanyPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CompanyPeer::ID, $id, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CompanyPeer::NAME, $name, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CompanyPeer::DESCRIPTION, $description, $comparison);
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
     * @see       filterByCompanyType()
     *
     * @param     mixed $type The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CompanyQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (is_array($type)) {
            $useMinMax = false;
            if (isset($type['min'])) {
                $this->addUsingAlias(CompanyPeer::TYPE, $type['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($type['max'])) {
                $this->addUsingAlias(CompanyPeer::TYPE, $type['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CompanyPeer::TYPE, $type, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CompanyPeer::CODE, $code, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CompanyPeer::WEBSITE, $website, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
     */
    public function filterByRegion($region = null, $comparison = null)
    {
        if (is_array($region)) {
            $useMinMax = false;
            if (isset($region['min'])) {
                $this->addUsingAlias(CompanyPeer::REGION, $region['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($region['max'])) {
                $this->addUsingAlias(CompanyPeer::REGION, $region['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CompanyPeer::REGION, $region, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CompanyPeer::REMARKS, $remarks, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
     */
    public function filterByPaymentMethod($paymentMethod = null, $comparison = null)
    {
        if (is_array($paymentMethod)) {
            $useMinMax = false;
            if (isset($paymentMethod['min'])) {
                $this->addUsingAlias(CompanyPeer::PAYMENT_METHOD, $paymentMethod['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentMethod['max'])) {
                $this->addUsingAlias(CompanyPeer::PAYMENT_METHOD, $paymentMethod['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CompanyPeer::PAYMENT_METHOD, $paymentMethod, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CompanyPeer::BANK_ACCOUNT_NUMBER, $bankAccountNumber, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CompanyPeer::VAT_NUMBER, $vatNumber, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CompanyPeer::COC_NUMBER, $cocNumber, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
     */
    public function filterByIsEnabled($isEnabled = null, $comparison = null)
    {
        if (is_string($isEnabled)) {
            $isEnabled = in_array(strtolower($isEnabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CompanyPeer::IS_ENABLED, $isEnabled, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
     */
    public function filterByIsDeleted($isDeleted = null, $comparison = null)
    {
        if (is_string($isDeleted)) {
            $isDeleted = in_array(strtolower($isDeleted), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CompanyPeer::IS_DELETED, $isDeleted, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CompanyPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CompanyPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CompanyPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CompanyPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CompanyPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CompanyPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related CompanyType object
     *
     * @param   CompanyType|PropelObjectCollection $companyType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CompanyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompanyType($companyType, $comparison = null)
    {
        if ($companyType instanceof CompanyType) {
            return $this
                ->addUsingAlias(CompanyPeer::TYPE, $companyType->getId(), $comparison);
        } elseif ($companyType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CompanyPeer::TYPE, $companyType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCompanyType() only accepts arguments of type CompanyType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CompanyType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CompanyQuery The current query, for fluid interface
     */
    public function joinCompanyType($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CompanyType');

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
            $this->addJoinObject($join, 'CompanyType');
        }

        return $this;
    }

    /**
     * Use the CompanyType relation CompanyType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\CompanyTypeQuery A secondary query class using the current class as primary query
     */
    public function useCompanyTypeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCompanyType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CompanyType', '\CompanyBundle\Model\CompanyTypeQuery');
    }

    /**
     * Filter the query by a related Regions object
     *
     * @param   Regions|PropelObjectCollection $regions The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CompanyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByRegions($regions, $comparison = null)
    {
        if ($regions instanceof Regions) {
            return $this
                ->addUsingAlias(CompanyPeer::REGION, $regions->getId(), $comparison);
        } elseif ($regions instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CompanyPeer::REGION, $regions->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return CompanyQuery The current query, for fluid interface
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
     * Filter the query by a related CompanyAddress object
     *
     * @param   CompanyAddress|PropelObjectCollection $companyAddress  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CompanyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompanyAddress($companyAddress, $comparison = null)
    {
        if ($companyAddress instanceof CompanyAddress) {
            return $this
                ->addUsingAlias(CompanyPeer::ID, $companyAddress->getCompanyId(), $comparison);
        } elseif ($companyAddress instanceof PropelObjectCollection) {
            return $this
                ->useCompanyAddressQuery()
                ->filterByPrimaryKeys($companyAddress->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCompanyAddress() only accepts arguments of type CompanyAddress or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CompanyAddress relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CompanyQuery The current query, for fluid interface
     */
    public function joinCompanyAddress($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CompanyAddress');

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
            $this->addJoinObject($join, 'CompanyAddress');
        }

        return $this;
    }

    /**
     * Use the CompanyAddress relation CompanyAddress object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\CompanyAddressQuery A secondary query class using the current class as primary query
     */
    public function useCompanyAddressQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompanyAddress($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CompanyAddress', '\CompanyBundle\Model\CompanyAddressQuery');
    }

    /**
     * Filter the query by a related CompanyEmail object
     *
     * @param   CompanyEmail|PropelObjectCollection $companyEmail  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CompanyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompanyEmail($companyEmail, $comparison = null)
    {
        if ($companyEmail instanceof CompanyEmail) {
            return $this
                ->addUsingAlias(CompanyPeer::ID, $companyEmail->getCompanyId(), $comparison);
        } elseif ($companyEmail instanceof PropelObjectCollection) {
            return $this
                ->useCompanyEmailQuery()
                ->filterByPrimaryKeys($companyEmail->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCompanyEmail() only accepts arguments of type CompanyEmail or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CompanyEmail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CompanyQuery The current query, for fluid interface
     */
    public function joinCompanyEmail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CompanyEmail');

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
            $this->addJoinObject($join, 'CompanyEmail');
        }

        return $this;
    }

    /**
     * Use the CompanyEmail relation CompanyEmail object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\CompanyEmailQuery A secondary query class using the current class as primary query
     */
    public function useCompanyEmailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompanyEmail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CompanyEmail', '\CompanyBundle\Model\CompanyEmailQuery');
    }

    /**
     * Filter the query by a related CompanyPhone object
     *
     * @param   CompanyPhone|PropelObjectCollection $companyPhone  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CompanyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompanyPhone($companyPhone, $comparison = null)
    {
        if ($companyPhone instanceof CompanyPhone) {
            return $this
                ->addUsingAlias(CompanyPeer::ID, $companyPhone->getCompanyId(), $comparison);
        } elseif ($companyPhone instanceof PropelObjectCollection) {
            return $this
                ->useCompanyPhoneQuery()
                ->filterByPrimaryKeys($companyPhone->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCompanyPhone() only accepts arguments of type CompanyPhone or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CompanyPhone relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CompanyQuery The current query, for fluid interface
     */
    public function joinCompanyPhone($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CompanyPhone');

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
            $this->addJoinObject($join, 'CompanyPhone');
        }

        return $this;
    }

    /**
     * Use the CompanyPhone relation CompanyPhone object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\CompanyPhoneQuery A secondary query class using the current class as primary query
     */
    public function useCompanyPhoneQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompanyPhone($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CompanyPhone', '\CompanyBundle\Model\CompanyPhoneQuery');
    }

    /**
     * Filter the query by a related CompanyContact object
     *
     * @param   CompanyContact|PropelObjectCollection $companyContact  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CompanyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompanyContact($companyContact, $comparison = null)
    {
        if ($companyContact instanceof CompanyContact) {
            return $this
                ->addUsingAlias(CompanyPeer::ID, $companyContact->getCompanyId(), $comparison);
        } elseif ($companyContact instanceof PropelObjectCollection) {
            return $this
                ->useCompanyContactQuery()
                ->filterByPrimaryKeys($companyContact->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCompanyContact() only accepts arguments of type CompanyContact or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CompanyContact relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CompanyQuery The current query, for fluid interface
     */
    public function joinCompanyContact($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CompanyContact');

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
            $this->addJoinObject($join, 'CompanyContact');
        }

        return $this;
    }

    /**
     * Use the CompanyContact relation CompanyContact object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\CompanyContactQuery A secondary query class using the current class as primary query
     */
    public function useCompanyContactQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompanyContact($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CompanyContact', '\CompanyBundle\Model\CompanyContactQuery');
    }

    /**
     * Filter the query by a related CompanyInformant object
     *
     * @param   CompanyInformant|PropelObjectCollection $companyInformant  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CompanyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompanyInformant($companyInformant, $comparison = null)
    {
        if ($companyInformant instanceof CompanyInformant) {
            return $this
                ->addUsingAlias(CompanyPeer::ID, $companyInformant->getCompanyId(), $comparison);
        } elseif ($companyInformant instanceof PropelObjectCollection) {
            return $this
                ->useCompanyInformantQuery()
                ->filterByPrimaryKeys($companyInformant->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCompanyInformant() only accepts arguments of type CompanyInformant or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CompanyInformant relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CompanyQuery The current query, for fluid interface
     */
    public function joinCompanyInformant($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CompanyInformant');

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
            $this->addJoinObject($join, 'CompanyInformant');
        }

        return $this;
    }

    /**
     * Use the CompanyInformant relation CompanyInformant object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\CompanyInformantQuery A secondary query class using the current class as primary query
     */
    public function useCompanyInformantQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompanyInformant($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CompanyInformant', '\CompanyBundle\Model\CompanyInformantQuery');
    }

    /**
     * Filter the query by a related CompanyOwner object
     *
     * @param   CompanyOwner|PropelObjectCollection $companyOwner  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CompanyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompanyOwner($companyOwner, $comparison = null)
    {
        if ($companyOwner instanceof CompanyOwner) {
            return $this
                ->addUsingAlias(CompanyPeer::ID, $companyOwner->getCompanyId(), $comparison);
        } elseif ($companyOwner instanceof PropelObjectCollection) {
            return $this
                ->useCompanyOwnerQuery()
                ->filterByPrimaryKeys($companyOwner->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCompanyOwner() only accepts arguments of type CompanyOwner or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CompanyOwner relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CompanyQuery The current query, for fluid interface
     */
    public function joinCompanyOwner($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CompanyOwner');

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
            $this->addJoinObject($join, 'CompanyOwner');
        }

        return $this;
    }

    /**
     * Use the CompanyOwner relation CompanyOwner object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\CompanyOwnerQuery A secondary query class using the current class as primary query
     */
    public function useCompanyOwnerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompanyOwner($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CompanyOwner', '\CompanyBundle\Model\CompanyOwnerQuery');
    }

    /**
     * Filter the query by a related Store object
     *
     * @param   Store|PropelObjectCollection $store  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CompanyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStore($store, $comparison = null)
    {
        if ($store instanceof Store) {
            return $this
                ->addUsingAlias(CompanyPeer::ID, $store->getMainCompany(), $comparison);
        } elseif ($store instanceof PropelObjectCollection) {
            return $this
                ->useStoreQuery()
                ->filterByPrimaryKeys($store->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStore() only accepts arguments of type Store or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Store relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CompanyQuery The current query, for fluid interface
     */
    public function joinStore($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Store');

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
            $this->addJoinObject($join, 'Store');
        }

        return $this;
    }

    /**
     * Use the Store relation Store object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreQuery A secondary query class using the current class as primary query
     */
    public function useStoreQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStore($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Store', '\StoreBundle\Model\StoreQuery');
    }

    /**
     * Filter the query by a related Address object
     * using the company_address table as cross reference
     *
     * @param   Address $address the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CompanyQuery The current query, for fluid interface
     */
    public function filterByAddress($address, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCompanyAddressQuery()
            ->filterByAddress($address, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Email object
     * using the company_email table as cross reference
     *
     * @param   Email $email the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CompanyQuery The current query, for fluid interface
     */
    public function filterByEmail($email, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCompanyEmailQuery()
            ->filterByEmail($email, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Phone object
     * using the company_phone table as cross reference
     *
     * @param   Phone $phone the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CompanyQuery The current query, for fluid interface
     */
    public function filterByPhone($phone, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCompanyPhoneQuery()
            ->filterByPhone($phone, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related User object
     * using the company_contact table as cross reference
     *
     * @param   User $user the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CompanyQuery The current query, for fluid interface
     */
    public function filterByContact($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCompanyContactQuery()
            ->filterByContact($user, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related User object
     * using the company_informant table as cross reference
     *
     * @param   User $user the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CompanyQuery The current query, for fluid interface
     */
    public function filterByInformant($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCompanyInformantQuery()
            ->filterByInformant($user, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related User object
     * using the company_owner table as cross reference
     *
     * @param   User $user the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   CompanyQuery The current query, for fluid interface
     */
    public function filterByOwner($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCompanyOwnerQuery()
            ->filterByOwner($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   Company $company Object to remove from the list of results
     *
     * @return CompanyQuery The current query, for fluid interface
     */
    public function prune($company = null)
    {
        if ($company) {
            $this->addUsingAlias(CompanyPeer::ID, $company->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     CompanyQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CompanyPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     CompanyQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CompanyPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     CompanyQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CompanyPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     CompanyQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CompanyPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     CompanyQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CompanyPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     CompanyQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CompanyPeer::CREATED_AT);
    }
}
