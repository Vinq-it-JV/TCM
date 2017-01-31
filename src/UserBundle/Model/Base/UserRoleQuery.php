<?php

namespace UserBundle\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use UserBundle\Model\UserRole as ChildUserRole;
use UserBundle\Model\UserRoleQuery as ChildUserRoleQuery;
use UserBundle\Model\Map\UserRoleTableMap;

/**
 * Base class that represents a query for the 'user_role' table.
 *
 *
 *
 * @method     ChildUserRoleQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserRoleQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildUserRoleQuery orderByRoleId($order = Criteria::ASC) Order by the role_id column
 *
 * @method     ChildUserRoleQuery groupById() Group by the id column
 * @method     ChildUserRoleQuery groupByUserId() Group by the user_id column
 * @method     ChildUserRoleQuery groupByRoleId() Group by the role_id column
 *
 * @method     ChildUserRoleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserRoleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserRoleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserRoleQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserRoleQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserRoleQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserRoleQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildUserRoleQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildUserRoleQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildUserRoleQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildUserRoleQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildUserRoleQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildUserRoleQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildUserRoleQuery leftJoinRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the Role relation
 * @method     ChildUserRoleQuery rightJoinRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Role relation
 * @method     ChildUserRoleQuery innerJoinRole($relationAlias = null) Adds a INNER JOIN clause to the query using the Role relation
 *
 * @method     ChildUserRoleQuery joinWithRole($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Role relation
 *
 * @method     ChildUserRoleQuery leftJoinWithRole() Adds a LEFT JOIN clause and with to the query using the Role relation
 * @method     ChildUserRoleQuery rightJoinWithRole() Adds a RIGHT JOIN clause and with to the query using the Role relation
 * @method     ChildUserRoleQuery innerJoinWithRole() Adds a INNER JOIN clause and with to the query using the Role relation
 *
 * @method     \UserBundle\Model\UserQuery|\UserBundle\Model\RoleQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUserRole findOne(ConnectionInterface $con = null) Return the first ChildUserRole matching the query
 * @method     ChildUserRole findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUserRole matching the query, or a new ChildUserRole object populated from the query conditions when no match is found
 *
 * @method     ChildUserRole findOneById(int $id) Return the first ChildUserRole filtered by the id column
 * @method     ChildUserRole findOneByUserId(int $user_id) Return the first ChildUserRole filtered by the user_id column
 * @method     ChildUserRole findOneByRoleId(int $role_id) Return the first ChildUserRole filtered by the role_id column *

 * @method     ChildUserRole requirePk($key, ConnectionInterface $con = null) Return the ChildUserRole by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserRole requireOne(ConnectionInterface $con = null) Return the first ChildUserRole matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUserRole requireOneById(int $id) Return the first ChildUserRole filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserRole requireOneByUserId(int $user_id) Return the first ChildUserRole filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserRole requireOneByRoleId(int $role_id) Return the first ChildUserRole filtered by the role_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUserRole[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUserRole objects based on current ModelCriteria
 * @method     ChildUserRole[]|ObjectCollection findById(int $id) Return ChildUserRole objects filtered by the id column
 * @method     ChildUserRole[]|ObjectCollection findByUserId(int $user_id) Return ChildUserRole objects filtered by the user_id column
 * @method     ChildUserRole[]|ObjectCollection findByRoleId(int $role_id) Return ChildUserRole objects filtered by the role_id column
 * @method     ChildUserRole[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserRoleQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \UserBundle\Model\Base\UserRoleQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\UserBundle\\Model\\UserRole', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserRoleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserRoleQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserRoleQuery) {
            return $criteria;
        }
        $query = new ChildUserRoleQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
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
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildUserRole|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserRoleTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserRoleTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserRole A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, user_id, role_id FROM user_role WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildUserRole $obj */
            $obj = new ChildUserRole();
            $obj->hydrate($row);
            UserRoleTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildUserRole|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildUserRoleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserRoleTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserRoleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserRoleTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserRoleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserRoleTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserRoleTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserRoleTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
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
     * @return $this|ChildUserRoleQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UserRoleTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UserRoleTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserRoleTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the role_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRoleId(1234); // WHERE role_id = 1234
     * $query->filterByRoleId(array(12, 34)); // WHERE role_id IN (12, 34)
     * $query->filterByRoleId(array('min' => 12)); // WHERE role_id > 12
     * </code>
     *
     * @see       filterByRole()
     *
     * @param     mixed $roleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserRoleQuery The current query, for fluid interface
     */
    public function filterByRoleId($roleId = null, $comparison = null)
    {
        if (is_array($roleId)) {
            $useMinMax = false;
            if (isset($roleId['min'])) {
                $this->addUsingAlias(UserRoleTableMap::COL_ROLE_ID, $roleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($roleId['max'])) {
                $this->addUsingAlias(UserRoleTableMap::COL_ROLE_ID, $roleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserRoleTableMap::COL_ROLE_ID, $roleId, $comparison);
    }

    /**
     * Filter the query by a related \UserBundle\Model\User object
     *
     * @param \UserBundle\Model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserRoleQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \UserBundle\Model\User) {
            return $this
                ->addUsingAlias(UserRoleTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserRoleTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \UserBundle\Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserRoleQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\UserBundle\Model\UserQuery');
    }

    /**
     * Filter the query by a related \UserBundle\Model\Role object
     *
     * @param \UserBundle\Model\Role|ObjectCollection $role The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserRoleQuery The current query, for fluid interface
     */
    public function filterByRole($role, $comparison = null)
    {
        if ($role instanceof \UserBundle\Model\Role) {
            return $this
                ->addUsingAlias(UserRoleTableMap::COL_ROLE_ID, $role->getId(), $comparison);
        } elseif ($role instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserRoleTableMap::COL_ROLE_ID, $role->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByRole() only accepts arguments of type \UserBundle\Model\Role or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Role relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserRoleQuery The current query, for fluid interface
     */
    public function joinRole($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Role');

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
            $this->addJoinObject($join, 'Role');
        }

        return $this;
    }

    /**
     * Use the Role relation Role object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserBundle\Model\RoleQuery A secondary query class using the current class as primary query
     */
    public function useRoleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Role', '\UserBundle\Model\RoleQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUserRole $userRole Object to remove from the list of results
     *
     * @return $this|ChildUserRoleQuery The current query, for fluid interface
     */
    public function prune($userRole = null)
    {
        if ($userRole) {
            $this->addUsingAlias(UserRoleTableMap::COL_ID, $userRole->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user_role table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserRoleTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserRoleTableMap::clearInstancePool();
            UserRoleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserRoleTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserRoleTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserRoleTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserRoleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UserRoleQuery
