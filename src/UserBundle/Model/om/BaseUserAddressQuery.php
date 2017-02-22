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
use UserBundle\Model\Address;
use UserBundle\Model\User;
use UserBundle\Model\UserAddress;
use UserBundle\Model\UserAddressPeer;
use UserBundle\Model\UserAddressQuery;

/**
 * @method UserAddressQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method UserAddressQuery orderByAddressId($order = Criteria::ASC) Order by the address_id column
 *
 * @method UserAddressQuery groupByUserId() Group by the user_id column
 * @method UserAddressQuery groupByAddressId() Group by the address_id column
 *
 * @method UserAddressQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserAddressQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserAddressQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserAddressQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method UserAddressQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method UserAddressQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method UserAddressQuery leftJoinAddress($relationAlias = null) Adds a LEFT JOIN clause to the query using the Address relation
 * @method UserAddressQuery rightJoinAddress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Address relation
 * @method UserAddressQuery innerJoinAddress($relationAlias = null) Adds a INNER JOIN clause to the query using the Address relation
 *
 * @method UserAddress findOne(PropelPDO $con = null) Return the first UserAddress matching the query
 * @method UserAddress findOneOrCreate(PropelPDO $con = null) Return the first UserAddress matching the query, or a new UserAddress object populated from the query conditions when no match is found
 *
 * @method UserAddress findOneByUserId(int $user_id) Return the first UserAddress filtered by the user_id column
 * @method UserAddress findOneByAddressId(int $address_id) Return the first UserAddress filtered by the address_id column
 *
 * @method array findByUserId(int $user_id) Return UserAddress objects filtered by the user_id column
 * @method array findByAddressId(int $address_id) Return UserAddress objects filtered by the address_id column
 */
abstract class BaseUserAddressQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserAddressQuery object.
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
            $modelName = 'UserBundle\\Model\\UserAddress';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UserAddressQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   UserAddressQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserAddressQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserAddressQuery) {
            return $criteria;
        }
        $query = new UserAddressQuery(null, null, $modelAlias);

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
                         A Primary key composition: [$user_id, $address_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   UserAddress|UserAddress[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserAddressPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserAddressPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 UserAddress A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `user_id`, `address_id` FROM `user_address` WHERE `user_id` = :p0 AND `address_id` = :p1';
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
            $obj = new UserAddress();
            $obj->hydrate($row);
            UserAddressPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return UserAddress|UserAddress[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|UserAddress[]|mixed the list of results, formatted by the current formatter
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
     * @return UserAddressQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(UserAddressPeer::USER_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(UserAddressPeer::ADDRESS_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserAddressQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(UserAddressPeer::USER_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(UserAddressPeer::ADDRESS_ID, $key[1], Criteria::EQUAL);
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
     * @return UserAddressQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UserAddressPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UserAddressPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserAddressPeer::USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the address_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAddressId(1234); // WHERE address_id = 1234
     * $query->filterByAddressId(array(12, 34)); // WHERE address_id IN (12, 34)
     * $query->filterByAddressId(array('min' => 12)); // WHERE address_id >= 12
     * $query->filterByAddressId(array('max' => 12)); // WHERE address_id <= 12
     * </code>
     *
     * @see       filterByAddress()
     *
     * @param     mixed $addressId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserAddressQuery The current query, for fluid interface
     */
    public function filterByAddressId($addressId = null, $comparison = null)
    {
        if (is_array($addressId)) {
            $useMinMax = false;
            if (isset($addressId['min'])) {
                $this->addUsingAlias(UserAddressPeer::ADDRESS_ID, $addressId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($addressId['max'])) {
                $this->addUsingAlias(UserAddressPeer::ADDRESS_ID, $addressId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserAddressPeer::ADDRESS_ID, $addressId, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserAddressQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(UserAddressPeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserAddressPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return UserAddressQuery The current query, for fluid interface
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
     * Filter the query by a related Address object
     *
     * @param   Address|PropelObjectCollection $address The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserAddressQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAddress($address, $comparison = null)
    {
        if ($address instanceof Address) {
            return $this
                ->addUsingAlias(UserAddressPeer::ADDRESS_ID, $address->getId(), $comparison);
        } elseif ($address instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserAddressPeer::ADDRESS_ID, $address->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAddress() only accepts arguments of type Address or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Address relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserAddressQuery The current query, for fluid interface
     */
    public function joinAddress($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Address');

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
            $this->addJoinObject($join, 'Address');
        }

        return $this;
    }

    /**
     * Use the Address relation Address object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\AddressQuery A secondary query class using the current class as primary query
     */
    public function useAddressQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAddress($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Address', '\UserBundle\Model\AddressQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   UserAddress $userAddress Object to remove from the list of results
     *
     * @return UserAddressQuery The current query, for fluid interface
     */
    public function prune($userAddress = null)
    {
        if ($userAddress) {
            $this->addCond('pruneCond0', $this->getAliasedColName(UserAddressPeer::USER_ID), $userAddress->getUserId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(UserAddressPeer::ADDRESS_ID), $userAddress->getAddressId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
