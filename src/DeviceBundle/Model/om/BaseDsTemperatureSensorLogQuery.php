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
use DeviceBundle\Model\DsTemperatureSensor;
use DeviceBundle\Model\DsTemperatureSensorLog;
use DeviceBundle\Model\DsTemperatureSensorLogPeer;
use DeviceBundle\Model\DsTemperatureSensorLogQuery;

/**
 * @method DsTemperatureSensorLogQuery orderById($order = Criteria::ASC) Order by the id column
 * @method DsTemperatureSensorLogQuery orderBySensor($order = Criteria::ASC) Order by the sensor column
 * @method DsTemperatureSensorLogQuery orderByLowLimit($order = Criteria::ASC) Order by the low_limit column
 * @method DsTemperatureSensorLogQuery orderByTemperature($order = Criteria::ASC) Order by the temperature column
 * @method DsTemperatureSensorLogQuery orderByHighLimit($order = Criteria::ASC) Order by the high_limit column
 * @method DsTemperatureSensorLogQuery orderByRawData($order = Criteria::ASC) Order by the raw_data column
 * @method DsTemperatureSensorLogQuery orderByDataCollectedAt($order = Criteria::ASC) Order by the data_collected_at column
 *
 * @method DsTemperatureSensorLogQuery groupById() Group by the id column
 * @method DsTemperatureSensorLogQuery groupBySensor() Group by the sensor column
 * @method DsTemperatureSensorLogQuery groupByLowLimit() Group by the low_limit column
 * @method DsTemperatureSensorLogQuery groupByTemperature() Group by the temperature column
 * @method DsTemperatureSensorLogQuery groupByHighLimit() Group by the high_limit column
 * @method DsTemperatureSensorLogQuery groupByRawData() Group by the raw_data column
 * @method DsTemperatureSensorLogQuery groupByDataCollectedAt() Group by the data_collected_at column
 *
 * @method DsTemperatureSensorLogQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method DsTemperatureSensorLogQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method DsTemperatureSensorLogQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method DsTemperatureSensorLogQuery leftJoinDsTemperatureSensor($relationAlias = null) Adds a LEFT JOIN clause to the query using the DsTemperatureSensor relation
 * @method DsTemperatureSensorLogQuery rightJoinDsTemperatureSensor($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DsTemperatureSensor relation
 * @method DsTemperatureSensorLogQuery innerJoinDsTemperatureSensor($relationAlias = null) Adds a INNER JOIN clause to the query using the DsTemperatureSensor relation
 *
 * @method DsTemperatureSensorLog findOne(PropelPDO $con = null) Return the first DsTemperatureSensorLog matching the query
 * @method DsTemperatureSensorLog findOneOrCreate(PropelPDO $con = null) Return the first DsTemperatureSensorLog matching the query, or a new DsTemperatureSensorLog object populated from the query conditions when no match is found
 *
 * @method DsTemperatureSensorLog findOneBySensor(int $sensor) Return the first DsTemperatureSensorLog filtered by the sensor column
 * @method DsTemperatureSensorLog findOneByLowLimit(string $low_limit) Return the first DsTemperatureSensorLog filtered by the low_limit column
 * @method DsTemperatureSensorLog findOneByTemperature(string $temperature) Return the first DsTemperatureSensorLog filtered by the temperature column
 * @method DsTemperatureSensorLog findOneByHighLimit(string $high_limit) Return the first DsTemperatureSensorLog filtered by the high_limit column
 * @method DsTemperatureSensorLog findOneByRawData(string $raw_data) Return the first DsTemperatureSensorLog filtered by the raw_data column
 * @method DsTemperatureSensorLog findOneByDataCollectedAt(string $data_collected_at) Return the first DsTemperatureSensorLog filtered by the data_collected_at column
 *
 * @method array findById(int $id) Return DsTemperatureSensorLog objects filtered by the id column
 * @method array findBySensor(int $sensor) Return DsTemperatureSensorLog objects filtered by the sensor column
 * @method array findByLowLimit(string $low_limit) Return DsTemperatureSensorLog objects filtered by the low_limit column
 * @method array findByTemperature(string $temperature) Return DsTemperatureSensorLog objects filtered by the temperature column
 * @method array findByHighLimit(string $high_limit) Return DsTemperatureSensorLog objects filtered by the high_limit column
 * @method array findByRawData(string $raw_data) Return DsTemperatureSensorLog objects filtered by the raw_data column
 * @method array findByDataCollectedAt(string $data_collected_at) Return DsTemperatureSensorLog objects filtered by the data_collected_at column
 */
abstract class BaseDsTemperatureSensorLogQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseDsTemperatureSensorLogQuery object.
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
            $modelName = 'DeviceBundle\\Model\\DsTemperatureSensorLog';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new DsTemperatureSensorLogQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   DsTemperatureSensorLogQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return DsTemperatureSensorLogQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof DsTemperatureSensorLogQuery) {
            return $criteria;
        }
        $query = new DsTemperatureSensorLogQuery(null, null, $modelAlias);

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
     * @return   DsTemperatureSensorLog|DsTemperatureSensorLog[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DsTemperatureSensorLogPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(DsTemperatureSensorLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 DsTemperatureSensorLog A model object, or null if the key is not found
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
     * @return                 DsTemperatureSensorLog A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `sensor`, `low_limit`, `temperature`, `high_limit`, `raw_data`, `data_collected_at` FROM `ds_temperature_sensor_log` WHERE `id` = :p0';
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
            $obj = new DsTemperatureSensorLog();
            $obj->hydrate($row);
            DsTemperatureSensorLogPeer::addInstanceToPool($obj, (string) $key);
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
     * @return DsTemperatureSensorLog|DsTemperatureSensorLog[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|DsTemperatureSensorLog[]|mixed the list of results, formatted by the current formatter
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
     * @return DsTemperatureSensorLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DsTemperatureSensorLogPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return DsTemperatureSensorLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DsTemperatureSensorLogPeer::ID, $keys, Criteria::IN);
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
     * @return DsTemperatureSensorLogQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DsTemperatureSensorLogPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DsTemperatureSensorLogPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorLogPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the sensor column
     *
     * Example usage:
     * <code>
     * $query->filterBySensor(1234); // WHERE sensor = 1234
     * $query->filterBySensor(array(12, 34)); // WHERE sensor IN (12, 34)
     * $query->filterBySensor(array('min' => 12)); // WHERE sensor >= 12
     * $query->filterBySensor(array('max' => 12)); // WHERE sensor <= 12
     * </code>
     *
     * @see       filterByDsTemperatureSensor()
     *
     * @param     mixed $sensor The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DsTemperatureSensorLogQuery The current query, for fluid interface
     */
    public function filterBySensor($sensor = null, $comparison = null)
    {
        if (is_array($sensor)) {
            $useMinMax = false;
            if (isset($sensor['min'])) {
                $this->addUsingAlias(DsTemperatureSensorLogPeer::SENSOR, $sensor['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sensor['max'])) {
                $this->addUsingAlias(DsTemperatureSensorLogPeer::SENSOR, $sensor['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorLogPeer::SENSOR, $sensor, $comparison);
    }

    /**
     * Filter the query on the low_limit column
     *
     * Example usage:
     * <code>
     * $query->filterByLowLimit('fooValue');   // WHERE low_limit = 'fooValue'
     * $query->filterByLowLimit('%fooValue%'); // WHERE low_limit LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lowLimit The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DsTemperatureSensorLogQuery The current query, for fluid interface
     */
    public function filterByLowLimit($lowLimit = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lowLimit)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lowLimit)) {
                $lowLimit = str_replace('*', '%', $lowLimit);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorLogPeer::LOW_LIMIT, $lowLimit, $comparison);
    }

    /**
     * Filter the query on the temperature column
     *
     * Example usage:
     * <code>
     * $query->filterByTemperature('fooValue');   // WHERE temperature = 'fooValue'
     * $query->filterByTemperature('%fooValue%'); // WHERE temperature LIKE '%fooValue%'
     * </code>
     *
     * @param     string $temperature The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DsTemperatureSensorLogQuery The current query, for fluid interface
     */
    public function filterByTemperature($temperature = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($temperature)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $temperature)) {
                $temperature = str_replace('*', '%', $temperature);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorLogPeer::TEMPERATURE, $temperature, $comparison);
    }

    /**
     * Filter the query on the high_limit column
     *
     * Example usage:
     * <code>
     * $query->filterByHighLimit('fooValue');   // WHERE high_limit = 'fooValue'
     * $query->filterByHighLimit('%fooValue%'); // WHERE high_limit LIKE '%fooValue%'
     * </code>
     *
     * @param     string $highLimit The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DsTemperatureSensorLogQuery The current query, for fluid interface
     */
    public function filterByHighLimit($highLimit = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($highLimit)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $highLimit)) {
                $highLimit = str_replace('*', '%', $highLimit);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorLogPeer::HIGH_LIMIT, $highLimit, $comparison);
    }

    /**
     * Filter the query on the raw_data column
     *
     * Example usage:
     * <code>
     * $query->filterByRawData('fooValue');   // WHERE raw_data = 'fooValue'
     * $query->filterByRawData('%fooValue%'); // WHERE raw_data LIKE '%fooValue%'
     * </code>
     *
     * @param     string $rawData The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DsTemperatureSensorLogQuery The current query, for fluid interface
     */
    public function filterByRawData($rawData = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($rawData)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $rawData)) {
                $rawData = str_replace('*', '%', $rawData);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorLogPeer::RAW_DATA, $rawData, $comparison);
    }

    /**
     * Filter the query on the data_collected_at column
     *
     * Example usage:
     * <code>
     * $query->filterByDataCollectedAt('2011-03-14'); // WHERE data_collected_at = '2011-03-14'
     * $query->filterByDataCollectedAt('now'); // WHERE data_collected_at = '2011-03-14'
     * $query->filterByDataCollectedAt(array('max' => 'yesterday')); // WHERE data_collected_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $dataCollectedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DsTemperatureSensorLogQuery The current query, for fluid interface
     */
    public function filterByDataCollectedAt($dataCollectedAt = null, $comparison = null)
    {
        if (is_array($dataCollectedAt)) {
            $useMinMax = false;
            if (isset($dataCollectedAt['min'])) {
                $this->addUsingAlias(DsTemperatureSensorLogPeer::DATA_COLLECTED_AT, $dataCollectedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dataCollectedAt['max'])) {
                $this->addUsingAlias(DsTemperatureSensorLogPeer::DATA_COLLECTED_AT, $dataCollectedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorLogPeer::DATA_COLLECTED_AT, $dataCollectedAt, $comparison);
    }

    /**
     * Filter the query by a related DsTemperatureSensor object
     *
     * @param   DsTemperatureSensor|PropelObjectCollection $dsTemperatureSensor The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DsTemperatureSensorLogQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDsTemperatureSensor($dsTemperatureSensor, $comparison = null)
    {
        if ($dsTemperatureSensor instanceof DsTemperatureSensor) {
            return $this
                ->addUsingAlias(DsTemperatureSensorLogPeer::SENSOR, $dsTemperatureSensor->getId(), $comparison);
        } elseif ($dsTemperatureSensor instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DsTemperatureSensorLogPeer::SENSOR, $dsTemperatureSensor->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return DsTemperatureSensorLogQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   DsTemperatureSensorLog $dsTemperatureSensorLog Object to remove from the list of results
     *
     * @return DsTemperatureSensorLogQuery The current query, for fluid interface
     */
    public function prune($dsTemperatureSensorLog = null)
    {
        if ($dsTemperatureSensorLog) {
            $this->addUsingAlias(DsTemperatureSensorLogPeer::ID, $dsTemperatureSensorLog->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
