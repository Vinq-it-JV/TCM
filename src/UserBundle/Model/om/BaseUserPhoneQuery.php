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
use UserBundle\Model\Phone;
use UserBundle\Model\User;
use UserBundle\Model\UserPhone;
use UserBundle\Model\UserPhonePeer;
use UserBundle\Model\UserPhoneQuery;

/**
 * @method UserPhoneQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method UserPhoneQuery orderByPhoneId($order = Criteria::ASC) Order by the phone_id column
 *
 * @method UserPhoneQuery groupByUserId() Group by the user_id column
 * @method UserPhoneQuery groupByPhoneId() Group by the phone_id column
 *
 * @method UserPhoneQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserPhoneQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserPhoneQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserPhoneQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method UserPhoneQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method UserPhoneQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method UserPhoneQuery leftJoinPhone($relationAlias = null) Adds a LEFT JOIN clause to the query using the Phone relation
 * @method UserPhoneQuery rightJoinPhone($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Phone relation
 * @method UserPhoneQuery innerJoinPhone($relationAlias = null) Adds a INNER JOIN clause to the query using the Phone relation
 *
 * @method UserPhone findOne(PropelPDO $con = null) Return the first UserPhone matching the query
 * @method UserPhone findOneOrCreate(PropelPDO $con = null) Return the first UserPhone matching the query, or a new UserPhone object populated from the query conditions when no match is found
 *
 * @method UserPhone findOneByUserId(int $user_id) Return the first UserPhone filtered by the user_id column
 * @method UserPhone findOneByPhoneId(int $phone_id) Return the first UserPhone filtered by the phone_id column
 *
 * @method array findByUserId(int $user_id) Return UserPhone objects filtered by the user_id column
 * @method array findByPhoneId(int $phone_id) Return UserPhone objects filtered by the phone_id column
 */
abstract class BaseUserPhoneQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserPhoneQuery object.
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
            $modelName = 'UserBundle\\Model\\UserPhone';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UserPhoneQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   UserPhoneQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserPhoneQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserPhoneQuery) {
            return $criteria;
        }
        $query = new UserPhoneQuery(null, null, $modelAlias);

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
                         A Primary key composition: [$user_id, $phone_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   UserPhone|UserPhone[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserPhonePeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserPhonePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 UserPhone A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `user_id`, `phone_id` FROM `user_phone` WHERE `user_id` = :p0 AND `phone_id` = :p1';
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
            $obj = new UserPhone();
            $obj->hydrate($row);
            UserPhonePeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return UserPhone|UserPhone[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|UserPhone[]|mixed the list of results, formatted by the current formatter
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
     * @return UserPhoneQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(UserPhonePeer::USER_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(UserPhonePeer::PHONE_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserPhoneQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(UserPhonePeer::USER_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(UserPhonePeer::PHONE_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id >= 12
     * $query->filterByUserId(array('max' => 12)); // WHERE user_id <= 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserPhoneQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UserPhonePeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UserPhonePeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPhonePeer::USER_ID, $userId, $comparison);
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
     * @return UserPhoneQuery The current query, for fluid interface
     */
    public function filterByPhoneId($phoneId = null, $comparison = null)
    {
        if (is_array($phoneId)) {
            $useMinMax = false;
            if (isset($phoneId['min'])) {
                $this->addUsingAlias(UserPhonePeer::PHONE_ID, $phoneId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($phoneId['max'])) {
                $this->addUsingAlias(UserPhonePeer::PHONE_ID, $phoneId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPhonePeer::PHONE_ID, $phoneId, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserPhoneQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(UserPhonePeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserPhonePeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserPhoneQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\UserBundle\Model\UserQuery');
    }

    /**
     * Filter the query by a related Phone object
     *
     * @param   Phone|PropelObjectCollection $phone The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserPhoneQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPhone($phone, $comparison = null)
    {
        if ($phone instanceof Phone) {
            return $this
                ->addUsingAlias(UserPhonePeer::PHONE_ID, $phone->getId(), $comparison);
        } elseif ($phone instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserPhonePeer::PHONE_ID, $phone->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return UserPhoneQuery The current query, for fluid interface
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
     * @param   UserPhone $userPhone Object to remove from the list of results
     *
     * @return UserPhoneQuery The current query, for fluid interface
     */
    public function prune($userPhone = null)
    {
        if ($userPhone) {
            $this->addCond('pruneCond0', $this->getAliasedColName(UserPhonePeer::USER_ID), $userPhone->getUserId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(UserPhonePeer::PHONE_ID), $userPhone->getPhoneId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
