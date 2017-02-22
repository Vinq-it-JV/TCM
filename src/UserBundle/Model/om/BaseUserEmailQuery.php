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
use UserBundle\Model\Email;
use UserBundle\Model\User;
use UserBundle\Model\UserEmail;
use UserBundle\Model\UserEmailPeer;
use UserBundle\Model\UserEmailQuery;

/**
 * @method UserEmailQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method UserEmailQuery orderByEmailId($order = Criteria::ASC) Order by the email_id column
 *
 * @method UserEmailQuery groupByUserId() Group by the user_id column
 * @method UserEmailQuery groupByEmailId() Group by the email_id column
 *
 * @method UserEmailQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserEmailQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserEmailQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserEmailQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method UserEmailQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method UserEmailQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method UserEmailQuery leftJoinEmail($relationAlias = null) Adds a LEFT JOIN clause to the query using the Email relation
 * @method UserEmailQuery rightJoinEmail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Email relation
 * @method UserEmailQuery innerJoinEmail($relationAlias = null) Adds a INNER JOIN clause to the query using the Email relation
 *
 * @method UserEmail findOne(PropelPDO $con = null) Return the first UserEmail matching the query
 * @method UserEmail findOneOrCreate(PropelPDO $con = null) Return the first UserEmail matching the query, or a new UserEmail object populated from the query conditions when no match is found
 *
 * @method UserEmail findOneByUserId(int $user_id) Return the first UserEmail filtered by the user_id column
 * @method UserEmail findOneByEmailId(int $email_id) Return the first UserEmail filtered by the email_id column
 *
 * @method array findByUserId(int $user_id) Return UserEmail objects filtered by the user_id column
 * @method array findByEmailId(int $email_id) Return UserEmail objects filtered by the email_id column
 */
abstract class BaseUserEmailQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserEmailQuery object.
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
            $modelName = 'UserBundle\\Model\\UserEmail';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UserEmailQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   UserEmailQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserEmailQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserEmailQuery) {
            return $criteria;
        }
        $query = new UserEmailQuery(null, null, $modelAlias);

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
                         A Primary key composition: [$user_id, $email_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   UserEmail|UserEmail[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserEmailPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserEmailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 UserEmail A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `user_id`, `email_id` FROM `user_email` WHERE `user_id` = :p0 AND `email_id` = :p1';
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
            $obj = new UserEmail();
            $obj->hydrate($row);
            UserEmailPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return UserEmail|UserEmail[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|UserEmail[]|mixed the list of results, formatted by the current formatter
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
     * @return UserEmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(UserEmailPeer::USER_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(UserEmailPeer::EMAIL_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserEmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(UserEmailPeer::USER_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(UserEmailPeer::EMAIL_ID, $key[1], Criteria::EQUAL);
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
     * @return UserEmailQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UserEmailPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UserEmailPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEmailPeer::USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the email_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailId(1234); // WHERE email_id = 1234
     * $query->filterByEmailId(array(12, 34)); // WHERE email_id IN (12, 34)
     * $query->filterByEmailId(array('min' => 12)); // WHERE email_id >= 12
     * $query->filterByEmailId(array('max' => 12)); // WHERE email_id <= 12
     * </code>
     *
     * @see       filterByEmail()
     *
     * @param     mixed $emailId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserEmailQuery The current query, for fluid interface
     */
    public function filterByEmailId($emailId = null, $comparison = null)
    {
        if (is_array($emailId)) {
            $useMinMax = false;
            if (isset($emailId['min'])) {
                $this->addUsingAlias(UserEmailPeer::EMAIL_ID, $emailId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($emailId['max'])) {
                $this->addUsingAlias(UserEmailPeer::EMAIL_ID, $emailId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEmailPeer::EMAIL_ID, $emailId, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserEmailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(UserEmailPeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserEmailPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return UserEmailQuery The current query, for fluid interface
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
     * Filter the query by a related Email object
     *
     * @param   Email|PropelObjectCollection $email The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserEmailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByEmail($email, $comparison = null)
    {
        if ($email instanceof Email) {
            return $this
                ->addUsingAlias(UserEmailPeer::EMAIL_ID, $email->getId(), $comparison);
        } elseif ($email instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserEmailPeer::EMAIL_ID, $email->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEmail() only accepts arguments of type Email or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Email relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserEmailQuery The current query, for fluid interface
     */
    public function joinEmail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Email');

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
            $this->addJoinObject($join, 'Email');
        }

        return $this;
    }

    /**
     * Use the Email relation Email object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\EmailQuery A secondary query class using the current class as primary query
     */
    public function useEmailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEmail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Email', '\UserBundle\Model\EmailQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   UserEmail $userEmail Object to remove from the list of results
     *
     * @return UserEmailQuery The current query, for fluid interface
     */
    public function prune($userEmail = null)
    {
        if ($userEmail) {
            $this->addCond('pruneCond0', $this->getAliasedColName(UserEmailPeer::USER_ID), $userEmail->getUserId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(UserEmailPeer::EMAIL_ID), $userEmail->getEmailId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
