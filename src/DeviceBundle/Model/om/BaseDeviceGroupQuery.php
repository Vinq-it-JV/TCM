<?php

namespace DeviceBundle\Model\om;

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
use DeviceBundle\Model\CbInput;
use DeviceBundle\Model\ControllerBox;
use DeviceBundle\Model\DeviceCopy;
use DeviceBundle\Model\DeviceGroup;
use DeviceBundle\Model\DeviceGroupPeer;
use DeviceBundle\Model\DeviceGroupQuery;
use DeviceBundle\Model\DsTemperatureSensor;
use StoreBundle\Model\Store;

/**
 * @method DeviceGroupQuery orderById($order = Criteria::ASC) Order by the id column
 * @method DeviceGroupQuery orderByMainStore($order = Criteria::ASC) Order by the main_store column
 * @method DeviceGroupQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method DeviceGroupQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method DeviceGroupQuery orderByState($order = Criteria::ASC) Order by the state column
 * @method DeviceGroupQuery orderByPosition($order = Criteria::ASC) Order by the position column
 * @method DeviceGroupQuery orderByIsEnabled($order = Criteria::ASC) Order by the is_enabled column
 * @method DeviceGroupQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method DeviceGroupQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method DeviceGroupQuery groupById() Group by the id column
 * @method DeviceGroupQuery groupByMainStore() Group by the main_store column
 * @method DeviceGroupQuery groupByName() Group by the name column
 * @method DeviceGroupQuery groupByDescription() Group by the description column
 * @method DeviceGroupQuery groupByState() Group by the state column
 * @method DeviceGroupQuery groupByPosition() Group by the position column
 * @method DeviceGroupQuery groupByIsEnabled() Group by the is_enabled column
 * @method DeviceGroupQuery groupByCreatedAt() Group by the created_at column
 * @method DeviceGroupQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method DeviceGroupQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method DeviceGroupQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method DeviceGroupQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method DeviceGroupQuery leftJoinStore($relationAlias = null) Adds a LEFT JOIN clause to the query using the Store relation
 * @method DeviceGroupQuery rightJoinStore($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Store relation
 * @method DeviceGroupQuery innerJoinStore($relationAlias = null) Adds a INNER JOIN clause to the query using the Store relation
 *
 * @method DeviceGroupQuery leftJoinControllerBox($relationAlias = null) Adds a LEFT JOIN clause to the query using the ControllerBox relation
 * @method DeviceGroupQuery rightJoinControllerBox($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ControllerBox relation
 * @method DeviceGroupQuery innerJoinControllerBox($relationAlias = null) Adds a INNER JOIN clause to the query using the ControllerBox relation
 *
 * @method DeviceGroupQuery leftJoinDsTemperatureSensor($relationAlias = null) Adds a LEFT JOIN clause to the query using the DsTemperatureSensor relation
 * @method DeviceGroupQuery rightJoinDsTemperatureSensor($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DsTemperatureSensor relation
 * @method DeviceGroupQuery innerJoinDsTemperatureSensor($relationAlias = null) Adds a INNER JOIN clause to the query using the DsTemperatureSensor relation
 *
 * @method DeviceGroupQuery leftJoinCbInput($relationAlias = null) Adds a LEFT JOIN clause to the query using the CbInput relation
 * @method DeviceGroupQuery rightJoinCbInput($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CbInput relation
 * @method DeviceGroupQuery innerJoinCbInput($relationAlias = null) Adds a INNER JOIN clause to the query using the CbInput relation
 *
 * @method DeviceGroupQuery leftJoinDeviceCopy($relationAlias = null) Adds a LEFT JOIN clause to the query using the DeviceCopy relation
 * @method DeviceGroupQuery rightJoinDeviceCopy($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DeviceCopy relation
 * @method DeviceGroupQuery innerJoinDeviceCopy($relationAlias = null) Adds a INNER JOIN clause to the query using the DeviceCopy relation
 *
 * @method DeviceGroup findOne(PropelPDO $con = null) Return the first DeviceGroup matching the query
 * @method DeviceGroup findOneOrCreate(PropelPDO $con = null) Return the first DeviceGroup matching the query, or a new DeviceGroup object populated from the query conditions when no match is found
 *
 * @method DeviceGroup findOneByMainStore(int $main_store) Return the first DeviceGroup filtered by the main_store column
 * @method DeviceGroup findOneByName(string $name) Return the first DeviceGroup filtered by the name column
 * @method DeviceGroup findOneByDescription(string $description) Return the first DeviceGroup filtered by the description column
 * @method DeviceGroup findOneByState(int $state) Return the first DeviceGroup filtered by the state column
 * @method DeviceGroup findOneByPosition(int $position) Return the first DeviceGroup filtered by the position column
 * @method DeviceGroup findOneByIsEnabled(boolean $is_enabled) Return the first DeviceGroup filtered by the is_enabled column
 * @method DeviceGroup findOneByCreatedAt(string $created_at) Return the first DeviceGroup filtered by the created_at column
 * @method DeviceGroup findOneByUpdatedAt(string $updated_at) Return the first DeviceGroup filtered by the updated_at column
 *
 * @method array findById(int $id) Return DeviceGroup objects filtered by the id column
 * @method array findByMainStore(int $main_store) Return DeviceGroup objects filtered by the main_store column
 * @method array findByName(string $name) Return DeviceGroup objects filtered by the name column
 * @method array findByDescription(string $description) Return DeviceGroup objects filtered by the description column
 * @method array findByState(int $state) Return DeviceGroup objects filtered by the state column
 * @method array findByPosition(int $position) Return DeviceGroup objects filtered by the position column
 * @method array findByIsEnabled(boolean $is_enabled) Return DeviceGroup objects filtered by the is_enabled column
 * @method array findByCreatedAt(string $created_at) Return DeviceGroup objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return DeviceGroup objects filtered by the updated_at column
 */
abstract class BaseDeviceGroupQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseDeviceGroupQuery object.
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
            $modelName = 'DeviceBundle\\Model\\DeviceGroup';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new DeviceGroupQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   DeviceGroupQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return DeviceGroupQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof DeviceGroupQuery) {
            return $criteria;
        }
        $query = new DeviceGroupQuery(null, null, $modelAlias);

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
     * @return   DeviceGroup|DeviceGroup[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DeviceGroupPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(DeviceGroupPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 DeviceGroup A model object, or null if the key is not found
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
     * @return                 DeviceGroup A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `main_store`, `name`, `description`, `state`, `position`, `is_enabled`, `created_at`, `updated_at` FROM `device_group` WHERE `id` = :p0';
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
            $obj = new DeviceGroup();
            $obj->hydrate($row);
            DeviceGroupPeer::addInstanceToPool($obj, (string) $key);
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
     * @return DeviceGroup|DeviceGroup[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|DeviceGroup[]|mixed the list of results, formatted by the current formatter
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
     * @return DeviceGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DeviceGroupPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return DeviceGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DeviceGroupPeer::ID, $keys, Criteria::IN);
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
     * @return DeviceGroupQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DeviceGroupPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DeviceGroupPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DeviceGroupPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the main_store column
     *
     * Example usage:
     * <code>
     * $query->filterByMainStore(1234); // WHERE main_store = 1234
     * $query->filterByMainStore(array(12, 34)); // WHERE main_store IN (12, 34)
     * $query->filterByMainStore(array('min' => 12)); // WHERE main_store >= 12
     * $query->filterByMainStore(array('max' => 12)); // WHERE main_store <= 12
     * </code>
     *
     * @see       filterByStore()
     *
     * @param     mixed $mainStore The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DeviceGroupQuery The current query, for fluid interface
     */
    public function filterByMainStore($mainStore = null, $comparison = null)
    {
        if (is_array($mainStore)) {
            $useMinMax = false;
            if (isset($mainStore['min'])) {
                $this->addUsingAlias(DeviceGroupPeer::MAIN_STORE, $mainStore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($mainStore['max'])) {
                $this->addUsingAlias(DeviceGroupPeer::MAIN_STORE, $mainStore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DeviceGroupPeer::MAIN_STORE, $mainStore, $comparison);
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
     * @return DeviceGroupQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DeviceGroupPeer::NAME, $name, $comparison);
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
     * @return DeviceGroupQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DeviceGroupPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the state column
     *
     * Example usage:
     * <code>
     * $query->filterByState(1234); // WHERE state = 1234
     * $query->filterByState(array(12, 34)); // WHERE state IN (12, 34)
     * $query->filterByState(array('min' => 12)); // WHERE state >= 12
     * $query->filterByState(array('max' => 12)); // WHERE state <= 12
     * </code>
     *
     * @param     mixed $state The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DeviceGroupQuery The current query, for fluid interface
     */
    public function filterByState($state = null, $comparison = null)
    {
        if (is_array($state)) {
            $useMinMax = false;
            if (isset($state['min'])) {
                $this->addUsingAlias(DeviceGroupPeer::STATE, $state['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($state['max'])) {
                $this->addUsingAlias(DeviceGroupPeer::STATE, $state['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DeviceGroupPeer::STATE, $state, $comparison);
    }

    /**
     * Filter the query on the position column
     *
     * Example usage:
     * <code>
     * $query->filterByPosition(1234); // WHERE position = 1234
     * $query->filterByPosition(array(12, 34)); // WHERE position IN (12, 34)
     * $query->filterByPosition(array('min' => 12)); // WHERE position >= 12
     * $query->filterByPosition(array('max' => 12)); // WHERE position <= 12
     * </code>
     *
     * @param     mixed $position The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DeviceGroupQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (is_array($position)) {
            $useMinMax = false;
            if (isset($position['min'])) {
                $this->addUsingAlias(DeviceGroupPeer::POSITION, $position['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($position['max'])) {
                $this->addUsingAlias(DeviceGroupPeer::POSITION, $position['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DeviceGroupPeer::POSITION, $position, $comparison);
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
     * @return DeviceGroupQuery The current query, for fluid interface
     */
    public function filterByIsEnabled($isEnabled = null, $comparison = null)
    {
        if (is_string($isEnabled)) {
            $isEnabled = in_array(strtolower($isEnabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(DeviceGroupPeer::IS_ENABLED, $isEnabled, $comparison);
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
     * @return DeviceGroupQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(DeviceGroupPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(DeviceGroupPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DeviceGroupPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return DeviceGroupQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(DeviceGroupPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(DeviceGroupPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DeviceGroupPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Store object
     *
     * @param   Store|PropelObjectCollection $store The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DeviceGroupQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStore($store, $comparison = null)
    {
        if ($store instanceof Store) {
            return $this
                ->addUsingAlias(DeviceGroupPeer::MAIN_STORE, $store->getId(), $comparison);
        } elseif ($store instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DeviceGroupPeer::MAIN_STORE, $store->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return DeviceGroupQuery The current query, for fluid interface
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
     * Filter the query by a related ControllerBox object
     *
     * @param   ControllerBox|PropelObjectCollection $controllerBox  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DeviceGroupQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByControllerBox($controllerBox, $comparison = null)
    {
        if ($controllerBox instanceof ControllerBox) {
            return $this
                ->addUsingAlias(DeviceGroupPeer::ID, $controllerBox->getGroup(), $comparison);
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
     * @return DeviceGroupQuery The current query, for fluid interface
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
     * Filter the query by a related DsTemperatureSensor object
     *
     * @param   DsTemperatureSensor|PropelObjectCollection $dsTemperatureSensor  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DeviceGroupQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDsTemperatureSensor($dsTemperatureSensor, $comparison = null)
    {
        if ($dsTemperatureSensor instanceof DsTemperatureSensor) {
            return $this
                ->addUsingAlias(DeviceGroupPeer::ID, $dsTemperatureSensor->getGroup(), $comparison);
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
     * @return DeviceGroupQuery The current query, for fluid interface
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
     * @return                 DeviceGroupQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCbInput($cbInput, $comparison = null)
    {
        if ($cbInput instanceof CbInput) {
            return $this
                ->addUsingAlias(DeviceGroupPeer::ID, $cbInput->getGroup(), $comparison);
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
     * @return DeviceGroupQuery The current query, for fluid interface
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
     * Filter the query by a related DeviceCopy object
     *
     * @param   DeviceCopy|PropelObjectCollection $deviceCopy  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DeviceGroupQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDeviceCopy($deviceCopy, $comparison = null)
    {
        if ($deviceCopy instanceof DeviceCopy) {
            return $this
                ->addUsingAlias(DeviceGroupPeer::ID, $deviceCopy->getGroup(), $comparison);
        } elseif ($deviceCopy instanceof PropelObjectCollection) {
            return $this
                ->useDeviceCopyQuery()
                ->filterByPrimaryKeys($deviceCopy->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDeviceCopy() only accepts arguments of type DeviceCopy or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DeviceCopy relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DeviceGroupQuery The current query, for fluid interface
     */
    public function joinDeviceCopy($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DeviceCopy');

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
            $this->addJoinObject($join, 'DeviceCopy');
        }

        return $this;
    }

    /**
     * Use the DeviceCopy relation DeviceCopy object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DeviceBundle\Model\DeviceCopyQuery A secondary query class using the current class as primary query
     */
    public function useDeviceCopyQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDeviceCopy($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DeviceCopy', '\DeviceBundle\Model\DeviceCopyQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   DeviceGroup $deviceGroup Object to remove from the list of results
     *
     * @return DeviceGroupQuery The current query, for fluid interface
     */
    public function prune($deviceGroup = null)
    {
        if ($deviceGroup) {
            $this->addUsingAlias(DeviceGroupPeer::ID, $deviceGroup->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     DeviceGroupQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(DeviceGroupPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     DeviceGroupQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(DeviceGroupPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     DeviceGroupQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(DeviceGroupPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     DeviceGroupQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(DeviceGroupPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     DeviceGroupQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(DeviceGroupPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     DeviceGroupQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(DeviceGroupPeer::CREATED_AT);
    }
}
