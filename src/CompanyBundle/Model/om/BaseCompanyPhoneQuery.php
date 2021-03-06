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
use CompanyBundle\Model\CompanyPhone;
use CompanyBundle\Model\CompanyPhonePeer;
use CompanyBundle\Model\CompanyPhoneQuery;
use UserBundle\Model\Phone;

/**
 * @method CompanyPhoneQuery orderByCompanyId($order = Criteria::ASC) Order by the company_id column
 * @method CompanyPhoneQuery orderByPhoneId($order = Criteria::ASC) Order by the phone_id column
 *
 * @method CompanyPhoneQuery groupByCompanyId() Group by the company_id column
 * @method CompanyPhoneQuery groupByPhoneId() Group by the phone_id column
 *
 * @method CompanyPhoneQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CompanyPhoneQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CompanyPhoneQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CompanyPhoneQuery leftJoinCompany($relationAlias = null) Adds a LEFT JOIN clause to the query using the Company relation
 * @method CompanyPhoneQuery rightJoinCompany($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Company relation
 * @method CompanyPhoneQuery innerJoinCompany($relationAlias = null) Adds a INNER JOIN clause to the query using the Company relation
 *
 * @method CompanyPhoneQuery leftJoinPhone($relationAlias = null) Adds a LEFT JOIN clause to the query using the Phone relation
 * @method CompanyPhoneQuery rightJoinPhone($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Phone relation
 * @method CompanyPhoneQuery innerJoinPhone($relationAlias = null) Adds a INNER JOIN clause to the query using the Phone relation
 *
 * @method CompanyPhone findOne(PropelPDO $con = null) Return the first CompanyPhone matching the query
 * @method CompanyPhone findOneOrCreate(PropelPDO $con = null) Return the first CompanyPhone matching the query, or a new CompanyPhone object populated from the query conditions when no match is found
 *
 * @method CompanyPhone findOneByCompanyId(int $company_id) Return the first CompanyPhone filtered by the company_id column
 * @method CompanyPhone findOneByPhoneId(int $phone_id) Return the first CompanyPhone filtered by the phone_id column
 *
 * @method array findByCompanyId(int $company_id) Return CompanyPhone objects filtered by the company_id column
 * @method array findByPhoneId(int $phone_id) Return CompanyPhone objects filtered by the phone_id column
 */
abstract class BaseCompanyPhoneQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCompanyPhoneQuery object.
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
            $modelName = 'CompanyBundle\\Model\\CompanyPhone';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CompanyPhoneQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CompanyPhoneQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CompanyPhoneQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CompanyPhoneQuery) {
            return $criteria;
        }
        $query = new CompanyPhoneQuery(null, null, $modelAlias);

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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$company_id, $phone_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   CompanyPhone|CompanyPhone[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CompanyPhonePeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CompanyPhonePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 CompanyPhone A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `company_id`, `phone_id` FROM `company_phone` WHERE `company_id` = :p0 AND `phone_id` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new CompanyPhone();
            $obj->hydrate($row);
            CompanyPhonePeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return CompanyPhone|CompanyPhone[]|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|CompanyPhone[]|mixed the list of results, formatted by the current formatter
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
     * @return CompanyPhoneQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(CompanyPhonePeer::COMPANY_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(CompanyPhonePeer::PHONE_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CompanyPhoneQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(CompanyPhonePeer::COMPANY_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(CompanyPhonePeer::PHONE_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the company_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCompanyId(1234); // WHERE company_id = 1234
     * $query->filterByCompanyId(array(12, 34)); // WHERE company_id IN (12, 34)
     * $query->filterByCompanyId(array('min' => 12)); // WHERE company_id >= 12
     * $query->filterByCompanyId(array('max' => 12)); // WHERE company_id <= 12
     * </code>
     *
     * @see       filterByCompany()
     *
     * @param     mixed $companyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CompanyPhoneQuery The current query, for fluid interface
     */
    public function filterByCompanyId($companyId = null, $comparison = null)
    {
        if (is_array($companyId)) {
            $useMinMax = false;
            if (isset($companyId['min'])) {
                $this->addUsingAlias(CompanyPhonePeer::COMPANY_ID, $companyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($companyId['max'])) {
                $this->addUsingAlias(CompanyPhonePeer::COMPANY_ID, $companyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CompanyPhonePeer::COMPANY_ID, $companyId, $comparison);
    }

    /**
     * Filter the query on the phone_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPhoneId(1234); // WHERE phone_id = 1234
     * $query->filterByPhoneId(array(12, 34)); // WHERE phone_id IN (12, 34)
     * $query->filterByPhoneId(array('min' => 12)); // WHERE phone_id >= 12
     * $query->filterByPhoneId(array('max' => 12)); // WHERE phone_id <= 12
     * </code>
     *
     * @see       filterByPhone()
     *
     * @param     mixed $phoneId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CompanyPhoneQuery The current query, for fluid interface
     */
    public function filterByPhoneId($phoneId = null, $comparison = null)
    {
        if (is_array($phoneId)) {
            $useMinMax = false;
            if (isset($phoneId['min'])) {
                $this->addUsingAlias(CompanyPhonePeer::PHONE_ID, $phoneId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($phoneId['max'])) {
                $this->addUsingAlias(CompanyPhonePeer::PHONE_ID, $phoneId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CompanyPhonePeer::PHONE_ID, $phoneId, $comparison);
    }

    /**
     * Filter the query by a related Company object
     *
     * @param   Company|PropelObjectCollection $company The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CompanyPhoneQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompany($company, $comparison = null)
    {
        if ($company instanceof Company) {
            return $this
                ->addUsingAlias(CompanyPhonePeer::COMPANY_ID, $company->getId(), $comparison);
        } elseif ($company instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CompanyPhonePeer::COMPANY_ID, $company->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return CompanyPhoneQuery The current query, for fluid interface
     */
    public function joinCompany($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useCompanyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompany($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Company', '\CompanyBundle\Model\CompanyQuery');
    }

    /**
     * Filter the query by a related Phone object
     *
     * @param   Phone|PropelObjectCollection $phone The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CompanyPhoneQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPhone($phone, $comparison = null)
    {
        if ($phone instanceof Phone) {
            return $this
                ->addUsingAlias(CompanyPhonePeer::PHONE_ID, $phone->getId(), $comparison);
        } elseif ($phone instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CompanyPhonePeer::PHONE_ID, $phone->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPhone() only accepts arguments of type Phone or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Phone relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CompanyPhoneQuery The current query, for fluid interface
     */
    public function joinPhone($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Phone');

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
            $this->addJoinObject($join, 'Phone');
        }

        return $this;
    }

    /**
     * Use the Phone relation Phone object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\PhoneQuery A secondary query class using the current class as primary query
     */
    public function usePhoneQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPhone($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Phone', '\UserBundle\Model\PhoneQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   CompanyPhone $companyPhone Object to remove from the list of results
     *
     * @return CompanyPhoneQuery The current query, for fluid interface
     */
    public function prune($companyPhone = null)
    {
        if ($companyPhone) {
            $this->addCond('pruneCond0', $this->getAliasedColName(CompanyPhonePeer::COMPANY_ID), $companyPhone->getCompanyId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(CompanyPhonePeer::PHONE_ID), $companyPhone->getPhoneId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
