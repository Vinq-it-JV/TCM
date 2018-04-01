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
use StoreBundle\Model\MaintenanceType;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreMaintenanceLog;
use StoreBundle\Model\StoreMaintenanceLogPeer;
use StoreBundle\Model\StoreMaintenanceLogQuery;
use UserBundle\Model\User;

/**
 * @method StoreMaintenanceLogQuery orderById($order = Criteria::ASC) Order by the id column
 * @method StoreMaintenanceLogQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method StoreMaintenanceLogQuery orderByCollectionId($order = Criteria::ASC) Order by the collection_id column
 * @method StoreMaintenanceLogQuery orderByMaintenanceStore($order = Criteria::ASC) Order by the maintenance_store column
 * @method StoreMaintenanceLogQuery orderByMaintenanceBy($order = Criteria::ASC) Order by the maintenance_by column
 * @method StoreMaintenanceLogQuery orderByMaintenanceStartedAt($order = Criteria::ASC) Order by the maintenance_started_at column
 * @method StoreMaintenanceLogQuery orderByMaintenanceStoppedAt($order = Criteria::ASC) Order by the maintenance_stopped_at column
 *
 * @method StoreMaintenanceLogQuery groupById() Group by the id column
 * @method StoreMaintenanceLogQuery groupByType() Group by the type column
 * @method StoreMaintenanceLogQuery groupByCollectionId() Group by the collection_id column
 * @method StoreMaintenanceLogQuery groupByMaintenanceStore() Group by the maintenance_store column
 * @method StoreMaintenanceLogQuery groupByMaintenanceBy() Group by the maintenance_by column
 * @method StoreMaintenanceLogQuery groupByMaintenanceStartedAt() Group by the maintenance_started_at column
 * @method StoreMaintenanceLogQuery groupByMaintenanceStoppedAt() Group by the maintenance_stopped_at column
 *
 * @method StoreMaintenanceLogQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method StoreMaintenanceLogQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method StoreMaintenanceLogQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method StoreMaintenanceLogQuery leftJoinMaintenanceType($relationAlias = null) Adds a LEFT JOIN clause to the query using the MaintenanceType relation
 * @method StoreMaintenanceLogQuery rightJoinMaintenanceType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MaintenanceType relation
 * @method StoreMaintenanceLogQuery innerJoinMaintenanceType($relationAlias = null) Adds a INNER JOIN clause to the query using the MaintenanceType relation
 *
 * @method StoreMaintenanceLogQuery leftJoinCollection($relationAlias = null) Adds a LEFT JOIN clause to the query using the Collection relation
 * @method StoreMaintenanceLogQuery rightJoinCollection($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Collection relation
 * @method StoreMaintenanceLogQuery innerJoinCollection($relationAlias = null) Adds a INNER JOIN clause to the query using the Collection relation
 *
 * @method StoreMaintenanceLogQuery leftJoinStore($relationAlias = null) Adds a LEFT JOIN clause to the query using the Store relation
 * @method StoreMaintenanceLogQuery rightJoinStore($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Store relation
 * @method StoreMaintenanceLogQuery innerJoinStore($relationAlias = null) Adds a INNER JOIN clause to the query using the Store relation
 *
 * @method StoreMaintenanceLogQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method StoreMaintenanceLogQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method StoreMaintenanceLogQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method StoreMaintenanceLog findOne(PropelPDO $con = null) Return the first StoreMaintenanceLog matching the query
 * @method StoreMaintenanceLog findOneOrCreate(PropelPDO $con = null) Return the first StoreMaintenanceLog matching the query, or a new StoreMaintenanceLog object populated from the query conditions when no match is found
 *
 * @method StoreMaintenanceLog findOneByType(int $type) Return the first StoreMaintenanceLog filtered by the type column
 * @method StoreMaintenanceLog findOneByCollectionId(int $collection_id) Return the first StoreMaintenanceLog filtered by the collection_id column
 * @method StoreMaintenanceLog findOneByMaintenanceStore(int $maintenance_store) Return the first StoreMaintenanceLog filtered by the maintenance_store column
 * @method StoreMaintenanceLog findOneByMaintenanceBy(int $maintenance_by) Return the first StoreMaintenanceLog filtered by the maintenance_by column
 * @method StoreMaintenanceLog findOneByMaintenanceStartedAt(string $maintenance_started_at) Return the first StoreMaintenanceLog filtered by the maintenance_started_at column
 * @method StoreMaintenanceLog findOneByMaintenanceStoppedAt(string $maintenance_stopped_at) Return the first StoreMaintenanceLog filtered by the maintenance_stopped_at column
 *
 * @method array findById(int $id) Return StoreMaintenanceLog objects filtered by the id column
 * @method array findByType(int $type) Return StoreMaintenanceLog objects filtered by the type column
 * @method array findByCollectionId(int $collection_id) Return StoreMaintenanceLog objects filtered by the collection_id column
 * @method array findByMaintenanceStore(int $maintenance_store) Return StoreMaintenanceLog objects filtered by the maintenance_store column
 * @method array findByMaintenanceBy(int $maintenance_by) Return StoreMaintenanceLog objects filtered by the maintenance_by column
 * @method array findByMaintenanceStartedAt(string $maintenance_started_at) Return StoreMaintenanceLog objects filtered by the maintenance_started_at column
 * @method array findByMaintenanceStoppedAt(string $maintenance_stopped_at) Return StoreMaintenanceLog objects filtered by the maintenance_stopped_at column
 */
abstract class BaseStoreMaintenanceLogQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseStoreMaintenanceLogQuery object.
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
            $modelName = 'StoreBundle\\Model\\StoreMaintenanceLog';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new StoreMaintenanceLogQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   StoreMaintenanceLogQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return StoreMaintenanceLogQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof StoreMaintenanceLogQuery) {
            return $criteria;
        }
        $query = new StoreMaintenanceLogQuery(null, null, $modelAlias);

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
     * @return   StoreMaintenanceLog|StoreMaintenanceLog[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = StoreMaintenanceLogPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(StoreMaintenanceLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 StoreMaintenanceLog A model object, or null if the key is not found
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
     * @return                 StoreMaintenanceLog A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `type`, `collection_id`, `maintenance_store`, `maintenance_by`, `maintenance_started_at`, `maintenance_stopped_at` FROM `store_maintenance_log` WHERE `id` = :p0';
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
            $obj = new StoreMaintenanceLog();
            $obj->hydrate($row);
            StoreMaintenanceLogPeer::addInstanceToPool($obj, (string) $key);
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
     * @return StoreMaintenanceLog|StoreMaintenanceLog[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|StoreMaintenanceLog[]|mixed the list of results, formatted by the current formatter
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
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(StoreMaintenanceLogPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(StoreMaintenanceLogPeer::ID, $keys, Criteria::IN);
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
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreMaintenanceLogPeer::ID, $id, $comparison);
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
     * @see       filterByMaintenanceType()
     *
     * @param     mixed $type The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (is_array($type)) {
            $useMinMax = false;
            if (isset($type['min'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::TYPE, $type['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($type['max'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::TYPE, $type['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreMaintenanceLogPeer::TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the collection_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCollectionId(1234); // WHERE collection_id = 1234
     * $query->filterByCollectionId(array(12, 34)); // WHERE collection_id IN (12, 34)
     * $query->filterByCollectionId(array('min' => 12)); // WHERE collection_id >= 12
     * $query->filterByCollectionId(array('max' => 12)); // WHERE collection_id <= 12
     * </code>
     *
     * @see       filterByCollection()
     *
     * @param     mixed $collectionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
     */
    public function filterByCollectionId($collectionId = null, $comparison = null)
    {
        if (is_array($collectionId)) {
            $useMinMax = false;
            if (isset($collectionId['min'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::COLLECTION_ID, $collectionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($collectionId['max'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::COLLECTION_ID, $collectionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreMaintenanceLogPeer::COLLECTION_ID, $collectionId, $comparison);
    }

    /**
     * Filter the query on the maintenance_store column
     *
     * Example usage:
     * <code>
     * $query->filterByMaintenanceStore(1234); // WHERE maintenance_store = 1234
     * $query->filterByMaintenanceStore(array(12, 34)); // WHERE maintenance_store IN (12, 34)
     * $query->filterByMaintenanceStore(array('min' => 12)); // WHERE maintenance_store >= 12
     * $query->filterByMaintenanceStore(array('max' => 12)); // WHERE maintenance_store <= 12
     * </code>
     *
     * @see       filterByStore()
     *
     * @param     mixed $maintenanceStore The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
     */
    public function filterByMaintenanceStore($maintenanceStore = null, $comparison = null)
    {
        if (is_array($maintenanceStore)) {
            $useMinMax = false;
            if (isset($maintenanceStore['min'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_STORE, $maintenanceStore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maintenanceStore['max'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_STORE, $maintenanceStore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_STORE, $maintenanceStore, $comparison);
    }

    /**
     * Filter the query on the maintenance_by column
     *
     * Example usage:
     * <code>
     * $query->filterByMaintenanceBy(1234); // WHERE maintenance_by = 1234
     * $query->filterByMaintenanceBy(array(12, 34)); // WHERE maintenance_by IN (12, 34)
     * $query->filterByMaintenanceBy(array('min' => 12)); // WHERE maintenance_by >= 12
     * $query->filterByMaintenanceBy(array('max' => 12)); // WHERE maintenance_by <= 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $maintenanceBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
     */
    public function filterByMaintenanceBy($maintenanceBy = null, $comparison = null)
    {
        if (is_array($maintenanceBy)) {
            $useMinMax = false;
            if (isset($maintenanceBy['min'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_BY, $maintenanceBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maintenanceBy['max'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_BY, $maintenanceBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_BY, $maintenanceBy, $comparison);
    }

    /**
     * Filter the query on the maintenance_started_at column
     *
     * Example usage:
     * <code>
     * $query->filterByMaintenanceStartedAt('2011-03-14'); // WHERE maintenance_started_at = '2011-03-14'
     * $query->filterByMaintenanceStartedAt('now'); // WHERE maintenance_started_at = '2011-03-14'
     * $query->filterByMaintenanceStartedAt(array('max' => 'yesterday')); // WHERE maintenance_started_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $maintenanceStartedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
     */
    public function filterByMaintenanceStartedAt($maintenanceStartedAt = null, $comparison = null)
    {
        if (is_array($maintenanceStartedAt)) {
            $useMinMax = false;
            if (isset($maintenanceStartedAt['min'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_STARTED_AT, $maintenanceStartedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maintenanceStartedAt['max'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_STARTED_AT, $maintenanceStartedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_STARTED_AT, $maintenanceStartedAt, $comparison);
    }

    /**
     * Filter the query on the maintenance_stopped_at column
     *
     * Example usage:
     * <code>
     * $query->filterByMaintenanceStoppedAt('2011-03-14'); // WHERE maintenance_stopped_at = '2011-03-14'
     * $query->filterByMaintenanceStoppedAt('now'); // WHERE maintenance_stopped_at = '2011-03-14'
     * $query->filterByMaintenanceStoppedAt(array('max' => 'yesterday')); // WHERE maintenance_stopped_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $maintenanceStoppedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
     */
    public function filterByMaintenanceStoppedAt($maintenanceStoppedAt = null, $comparison = null)
    {
        if (is_array($maintenanceStoppedAt)) {
            $useMinMax = false;
            if (isset($maintenanceStoppedAt['min'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_STOPPED_AT, $maintenanceStoppedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maintenanceStoppedAt['max'])) {
                $this->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_STOPPED_AT, $maintenanceStoppedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_STOPPED_AT, $maintenanceStoppedAt, $comparison);
    }

    /**
     * Filter the query by a related MaintenanceType object
     *
     * @param   MaintenanceType|PropelObjectCollection $maintenanceType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreMaintenanceLogQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMaintenanceType($maintenanceType, $comparison = null)
    {
        if ($maintenanceType instanceof MaintenanceType) {
            return $this
                ->addUsingAlias(StoreMaintenanceLogPeer::TYPE, $maintenanceType->getId(), $comparison);
        } elseif ($maintenanceType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StoreMaintenanceLogPeer::TYPE, $maintenanceType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByMaintenanceType() only accepts arguments of type MaintenanceType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MaintenanceType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
     */
    public function joinMaintenanceType($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MaintenanceType');

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
            $this->addJoinObject($join, 'MaintenanceType');
        }

        return $this;
    }

    /**
     * Use the MaintenanceType relation MaintenanceType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\MaintenanceTypeQuery A secondary query class using the current class as primary query
     */
    public function useMaintenanceTypeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMaintenanceType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MaintenanceType', '\StoreBundle\Model\MaintenanceTypeQuery');
    }

    /**
     * Filter the query by a related Collection object
     *
     * @param   Collection|PropelObjectCollection $collection The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreMaintenanceLogQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCollection($collection, $comparison = null)
    {
        if ($collection instanceof Collection) {
            return $this
                ->addUsingAlias(StoreMaintenanceLogPeer::COLLECTION_ID, $collection->getId(), $comparison);
        } elseif ($collection instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StoreMaintenanceLogPeer::COLLECTION_ID, $collection->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
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
     * Filter the query by a related Store object
     *
     * @param   Store|PropelObjectCollection $store The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreMaintenanceLogQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStore($store, $comparison = null)
    {
        if ($store instanceof Store) {
            return $this
                ->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_STORE, $store->getId(), $comparison);
        } elseif ($store instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_STORE, $store->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
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
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreMaintenanceLogQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_BY, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StoreMaintenanceLogPeer::MAINTENANCE_BY, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
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
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\UserBundle\Model\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   StoreMaintenanceLog $storeMaintenanceLog Object to remove from the list of results
     *
     * @return StoreMaintenanceLogQuery The current query, for fluid interface
     */
    public function prune($storeMaintenanceLog = null)
    {
        if ($storeMaintenanceLog) {
            $this->addUsingAlias(StoreMaintenanceLogPeer::ID, $storeMaintenanceLog->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
