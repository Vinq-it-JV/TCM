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
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreInformant;
use StoreBundle\Model\StoreInformantPeer;
use StoreBundle\Model\StoreInformantQuery;
use UserBundle\Model\User;

/**
 * @method StoreInformantQuery orderByStoreId($order = Criteria::ASC) Order by the store_id column
 * @method StoreInformantQuery orderByInformantId($order = Criteria::ASC) Order by the informant_id column
 *
 * @method StoreInformantQuery groupByStoreId() Group by the store_id column
 * @method StoreInformantQuery groupByInformantId() Group by the informant_id column
 *
 * @method StoreInformantQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method StoreInformantQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method StoreInformantQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method StoreInformantQuery leftJoinInformantStore($relationAlias = null) Adds a LEFT JOIN clause to the query using the InformantStore relation
 * @method StoreInformantQuery rightJoinInformantStore($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InformantStore relation
 * @method StoreInformantQuery innerJoinInformantStore($relationAlias = null) Adds a INNER JOIN clause to the query using the InformantStore relation
 *
 * @method StoreInformantQuery leftJoinInformant($relationAlias = null) Adds a LEFT JOIN clause to the query using the Informant relation
 * @method StoreInformantQuery rightJoinInformant($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Informant relation
 * @method StoreInformantQuery innerJoinInformant($relationAlias = null) Adds a INNER JOIN clause to the query using the Informant relation
 *
 * @method StoreInformant findOne(PropelPDO $con = null) Return the first StoreInformant matching the query
 * @method StoreInformant findOneOrCreate(PropelPDO $con = null) Return the first StoreInformant matching the query, or a new StoreInformant object populated from the query conditions when no match is found
 *
 * @method StoreInformant findOneByStoreId(int $store_id) Return the first StoreInformant filtered by the store_id column
 * @method StoreInformant findOneByInformantId(int $informant_id) Return the first StoreInformant filtered by the informant_id column
 *
 * @method array findByStoreId(int $store_id) Return StoreInformant objects filtered by the store_id column
 * @method array findByInformantId(int $informant_id) Return StoreInformant objects filtered by the informant_id column
 */
abstract class BaseStoreInformantQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseStoreInformantQuery object.
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
            $modelName = 'StoreBundle\\Model\\StoreInformant';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new StoreInformantQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   StoreInformantQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return StoreInformantQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof StoreInformantQuery) {
            return $criteria;
        }
        $query = new StoreInformantQuery(null, null, $modelAlias);

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
                         A Primary key composition: [$store_id, $informant_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   StoreInformant|StoreInformant[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = StoreInformantPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(StoreInformantPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 StoreInformant A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `store_id`, `informant_id` FROM `store_informant` WHERE `store_id` = :p0 AND `informant_id` = :p1';
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
            $obj = new StoreInformant();
            $obj->hydrate($row);
            StoreInformantPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return StoreInformant|StoreInformant[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|StoreInformant[]|mixed the list of results, formatted by the current formatter
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
     * @return StoreInformantQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(StoreInformantPeer::STORE_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(StoreInformantPeer::INFORMANT_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return StoreInformantQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(StoreInformantPeer::STORE_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(StoreInformantPeer::INFORMANT_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the store_id column
     *
     * Example usage:
     * <code>
     * $query->filterByStoreId(1234); // WHERE store_id = 1234
     * $query->filterByStoreId(array(12, 34)); // WHERE store_id IN (12, 34)
     * $query->filterByStoreId(array('min' => 12)); // WHERE store_id >= 12
     * $query->filterByStoreId(array('max' => 12)); // WHERE store_id <= 12
     * </code>
     *
     * @see       filterByInformantStore()
     *
     * @param     mixed $storeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreInformantQuery The current query, for fluid interface
     */
    public function filterByStoreId($storeId = null, $comparison = null)
    {
        if (is_array($storeId)) {
            $useMinMax = false;
            if (isset($storeId['min'])) {
                $this->addUsingAlias(StoreInformantPeer::STORE_ID, $storeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($storeId['max'])) {
                $this->addUsingAlias(StoreInformantPeer::STORE_ID, $storeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreInformantPeer::STORE_ID, $storeId, $comparison);
    }

    /**
     * Filter the query on the informant_id column
     *
     * Example usage:
     * <code>
     * $query->filterByInformantId(1234); // WHERE informant_id = 1234
     * $query->filterByInformantId(array(12, 34)); // WHERE informant_id IN (12, 34)
     * $query->filterByInformantId(array('min' => 12)); // WHERE informant_id >= 12
     * $query->filterByInformantId(array('max' => 12)); // WHERE informant_id <= 12
     * </code>
     *
     * @see       filterByInformant()
     *
     * @param     mixed $informantId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreInformantQuery The current query, for fluid interface
     */
    public function filterByInformantId($informantId = null, $comparison = null)
    {
        if (is_array($informantId)) {
            $useMinMax = false;
            if (isset($informantId['min'])) {
                $this->addUsingAlias(StoreInformantPeer::INFORMANT_ID, $informantId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($informantId['max'])) {
                $this->addUsingAlias(StoreInformantPeer::INFORMANT_ID, $informantId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreInformantPeer::INFORMANT_ID, $informantId, $comparison);
    }

    /**
     * Filter the query by a related Store object
     *
     * @param   Store|PropelObjectCollection $store The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreInformantQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByInformantStore($store, $comparison = null)
    {
        if ($store instanceof Store) {
            return $this
                ->addUsingAlias(StoreInformantPeer::STORE_ID, $store->getId(), $comparison);
        } elseif ($store instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StoreInformantPeer::STORE_ID, $store->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByInformantStore() only accepts arguments of type Store or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InformantStore relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreInformantQuery The current query, for fluid interface
     */
    public function joinInformantStore($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InformantStore');

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
            $this->addJoinObject($join, 'InformantStore');
        }

        return $this;
    }

    /**
     * Use the InformantStore relation Store object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreQuery A secondary query class using the current class as primary query
     */
    public function useInformantStoreQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInformantStore($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InformantStore', '\StoreBundle\Model\StoreQuery');
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreInformantQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByInformant($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(StoreInformantPeer::INFORMANT_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StoreInformantPeer::INFORMANT_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByInformant() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Informant relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreInformantQuery The current query, for fluid interface
     */
    public function joinInformant($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Informant');

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
            $this->addJoinObject($join, 'Informant');
        }

        return $this;
    }

    /**
     * Use the Informant relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useInformantQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInformant($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Informant', '\UserBundle\Model\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   StoreInformant $storeInformant Object to remove from the list of results
     *
     * @return StoreInformantQuery The current query, for fluid interface
     */
    public function prune($storeInformant = null)
    {
        if ($storeInformant) {
            $this->addCond('pruneCond0', $this->getAliasedColName(StoreInformantPeer::STORE_ID), $storeInformant->getStoreId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(StoreInformantPeer::INFORMANT_ID), $storeInformant->getInformantId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
