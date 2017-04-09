<?php

namespace UserBundle\Model\om;

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
use CompanyBundle\Model\CompanyPhone;
use StoreBundle\Model\Store;
use StoreBundle\Model\StorePhone;
use UserBundle\Model\Phone;
use UserBundle\Model\PhonePeer;
use UserBundle\Model\PhoneQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserPhone;

/**
 * @method PhoneQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PhoneQuery orderByPrimary($order = Criteria::ASC) Order by the primary column
 * @method PhoneQuery orderByPhoneNumber($order = Criteria::ASC) Order by the phone_number column
 * @method PhoneQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PhoneQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PhoneQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PhoneQuery groupById() Group by the id column
 * @method PhoneQuery groupByPrimary() Group by the primary column
 * @method PhoneQuery groupByPhoneNumber() Group by the phone_number column
 * @method PhoneQuery groupByDescription() Group by the description column
 * @method PhoneQuery groupByCreatedAt() Group by the created_at column
 * @method PhoneQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PhoneQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PhoneQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PhoneQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PhoneQuery leftJoinCompanyPhone($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompanyPhone relation
 * @method PhoneQuery rightJoinCompanyPhone($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompanyPhone relation
 * @method PhoneQuery innerJoinCompanyPhone($relationAlias = null) Adds a INNER JOIN clause to the query using the CompanyPhone relation
 *
 * @method PhoneQuery leftJoinStorePhone($relationAlias = null) Adds a LEFT JOIN clause to the query using the StorePhone relation
 * @method PhoneQuery rightJoinStorePhone($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StorePhone relation
 * @method PhoneQuery innerJoinStorePhone($relationAlias = null) Adds a INNER JOIN clause to the query using the StorePhone relation
 *
 * @method PhoneQuery leftJoinUserPhone($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserPhone relation
 * @method PhoneQuery rightJoinUserPhone($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserPhone relation
 * @method PhoneQuery innerJoinUserPhone($relationAlias = null) Adds a INNER JOIN clause to the query using the UserPhone relation
 *
 * @method Phone findOne(PropelPDO $con = null) Return the first Phone matching the query
 * @method Phone findOneOrCreate(PropelPDO $con = null) Return the first Phone matching the query, or a new Phone object populated from the query conditions when no match is found
 *
 * @method Phone findOneByPrimary(boolean $primary) Return the first Phone filtered by the primary column
 * @method Phone findOneByPhoneNumber(string $phone_number) Return the first Phone filtered by the phone_number column
 * @method Phone findOneByDescription(string $description) Return the first Phone filtered by the description column
 * @method Phone findOneByCreatedAt(string $created_at) Return the first Phone filtered by the created_at column
 * @method Phone findOneByUpdatedAt(string $updated_at) Return the first Phone filtered by the updated_at column
 *
 * @method array findById(int $id) Return Phone objects filtered by the id column
 * @method array findByPrimary(boolean $primary) Return Phone objects filtered by the primary column
 * @method array findByPhoneNumber(string $phone_number) Return Phone objects filtered by the phone_number column
 * @method array findByDescription(string $description) Return Phone objects filtered by the description column
 * @method array findByCreatedAt(string $created_at) Return Phone objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Phone objects filtered by the updated_at column
 */
abstract class BasePhoneQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePhoneQuery object.
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
            $modelName = 'UserBundle\\Model\\Phone';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PhoneQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PhoneQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PhoneQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PhoneQuery) {
            return $criteria;
        }
        $query = new PhoneQuery(null, null, $modelAlias);

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
     * @return   Phone|Phone[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PhonePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PhonePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Phone A model object, or null if the key is not found
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
     * @return                 Phone A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `primary`, `phone_number`, `description`, `created_at`, `updated_at` FROM `phone` WHERE `id` = :p0';
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
            $obj = new Phone();
            $obj->hydrate($row);
            PhonePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Phone|Phone[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Phone[]|mixed the list of results, formatted by the current formatter
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
     * @return PhoneQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PhonePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PhoneQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PhonePeer::ID, $keys, Criteria::IN);
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
     * @return PhoneQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PhonePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PhonePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PhonePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the primary column
     *
     * Example usage:
     * <code>
     * $query->filterByPrimary(true); // WHERE primary = true
     * $query->filterByPrimary('yes'); // WHERE primary = true
     * </code>
     *
     * @param     boolean|string $primary The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PhoneQuery The current query, for fluid interface
     */
    public function filterByPrimary($primary = null, $comparison = null)
    {
        if (is_string($primary)) {
            $primary = in_array(strtolower($primary), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PhonePeer::PRIMARY, $primary, $comparison);
    }

    /**
     * Filter the query on the phone_number column
     *
     * Example usage:
     * <code>
     * $query->filterByPhoneNumber('fooValue');   // WHERE phone_number = 'fooValue'
     * $query->filterByPhoneNumber('%fooValue%'); // WHERE phone_number LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phoneNumber The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PhoneQuery The current query, for fluid interface
     */
    public function filterByPhoneNumber($phoneNumber = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phoneNumber)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phoneNumber)) {
                $phoneNumber = str_replace('*', '%', $phoneNumber);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PhonePeer::PHONE_NUMBER, $phoneNumber, $comparison);
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
     * @return PhoneQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PhonePeer::DESCRIPTION, $description, $comparison);
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
     * @return PhoneQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PhonePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PhonePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PhonePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PhoneQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PhonePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PhonePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PhonePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related CompanyPhone object
     *
     * @param   CompanyPhone|PropelObjectCollection $companyPhone  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PhoneQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompanyPhone($companyPhone, $comparison = null)
    {
        if ($companyPhone instanceof CompanyPhone) {
            return $this
                ->addUsingAlias(PhonePeer::ID, $companyPhone->getPhoneId(), $comparison);
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
     * @return PhoneQuery The current query, for fluid interface
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
     * Filter the query by a related StorePhone object
     *
     * @param   StorePhone|PropelObjectCollection $storePhone  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PhoneQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStorePhone($storePhone, $comparison = null)
    {
        if ($storePhone instanceof StorePhone) {
            return $this
                ->addUsingAlias(PhonePeer::ID, $storePhone->getPhoneId(), $comparison);
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
     * @return PhoneQuery The current query, for fluid interface
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
     * Filter the query by a related UserPhone object
     *
     * @param   UserPhone|PropelObjectCollection $userPhone  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PhoneQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUserPhone($userPhone, $comparison = null)
    {
        if ($userPhone instanceof UserPhone) {
            return $this
                ->addUsingAlias(PhonePeer::ID, $userPhone->getPhoneId(), $comparison);
        } elseif ($userPhone instanceof PropelObjectCollection) {
            return $this
                ->useUserPhoneQuery()
                ->filterByPrimaryKeys($userPhone->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserPhone() only accepts arguments of type UserPhone or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserPhone relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PhoneQuery The current query, for fluid interface
     */
    public function joinUserPhone($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserPhone');

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
            $this->addJoinObject($join, 'UserPhone');
        }

        return $this;
    }

    /**
     * Use the UserPhone relation UserPhone object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\UserPhoneQuery A secondary query class using the current class as primary query
     */
    public function useUserPhoneQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserPhone($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserPhone', '\UserBundle\Model\UserPhoneQuery');
    }

    /**
     * Filter the query by a related Company object
     * using the company_phone table as cross reference
     *
     * @param   Company $company the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PhoneQuery The current query, for fluid interface
     */
    public function filterByCompany($company, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCompanyPhoneQuery()
            ->filterByCompany($company, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Store object
     * using the store_phone table as cross reference
     *
     * @param   Store $store the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PhoneQuery The current query, for fluid interface
     */
    public function filterByStore($store, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useStorePhoneQuery()
            ->filterByStore($store, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related User object
     * using the user_phone table as cross reference
     *
     * @param   User $user the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PhoneQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPhoneQuery()
            ->filterByUser($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   Phone $phone Object to remove from the list of results
     *
     * @return PhoneQuery The current query, for fluid interface
     */
    public function prune($phone = null)
    {
        if ($phone) {
            $this->addUsingAlias(PhonePeer::ID, $phone->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PhoneQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PhonePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PhoneQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PhonePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PhoneQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PhonePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PhoneQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PhonePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PhoneQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PhonePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PhoneQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PhonePeer::CREATED_AT);
    }
}
