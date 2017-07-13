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
use DeviceBundle\Model\CbInputLog;
use DeviceBundle\Model\CbInputLogPeer;
use DeviceBundle\Model\CbInputLogQuery;

/**
 * @method CbInputLogQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CbInputLogQuery orderByInput($order = Criteria::ASC) Order by the input column
 * @method CbInputLogQuery orderBySwitchState($order = Criteria::ASC) Order by the switch_state column
 * @method CbInputLogQuery orderBySwitchWhen($order = Criteria::ASC) Order by the switch_when column
 * @method CbInputLogQuery orderByRawData($order = Criteria::ASC) Order by the raw_data column
 * @method CbInputLogQuery orderByDataCollectedAt($order = Criteria::ASC) Order by the data_collected_at column
 *
 * @method CbInputLogQuery groupById() Group by the id column
 * @method CbInputLogQuery groupByInput() Group by the input column
 * @method CbInputLogQuery groupBySwitchState() Group by the switch_state column
 * @method CbInputLogQuery groupBySwitchWhen() Group by the switch_when column
 * @method CbInputLogQuery groupByRawData() Group by the raw_data column
 * @method CbInputLogQuery groupByDataCollectedAt() Group by the data_collected_at column
 *
 * @method CbInputLogQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CbInputLogQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CbInputLogQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CbInputLogQuery leftJoinCbInput($relationAlias = null) Adds a LEFT JOIN clause to the query using the CbInput relation
 * @method CbInputLogQuery rightJoinCbInput($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CbInput relation
 * @method CbInputLogQuery innerJoinCbInput($relationAlias = null) Adds a INNER JOIN clause to the query using the CbInput relation
 *
 * @method CbInputLog findOne(PropelPDO $con = null) Return the first CbInputLog matching the query
 * @method CbInputLog findOneOrCreate(PropelPDO $con = null) Return the first CbInputLog matching the query, or a new CbInputLog object populated from the query conditions when no match is found
 *
 * @method CbInputLog findOneByInput(int $input) Return the first CbInputLog filtered by the input column
 * @method CbInputLog findOneBySwitchState(boolean $switch_state) Return the first CbInputLog filtered by the switch_state column
 * @method CbInputLog findOneBySwitchWhen(boolean $switch_when) Return the first CbInputLog filtered by the switch_when column
 * @method CbInputLog findOneByRawData(string $raw_data) Return the first CbInputLog filtered by the raw_data column
 * @method CbInputLog findOneByDataCollectedAt(string $data_collected_at) Return the first CbInputLog filtered by the data_collected_at column
 *
 * @method array findById(int $id) Return CbInputLog objects filtered by the id column
 * @method array findByInput(int $input) Return CbInputLog objects filtered by the input column
 * @method array findBySwitchState(boolean $switch_state) Return CbInputLog objects filtered by the switch_state column
 * @method array findBySwitchWhen(boolean $switch_when) Return CbInputLog objects filtered by the switch_when column
 * @method array findByRawData(string $raw_data) Return CbInputLog objects filtered by the raw_data column
 * @method array findByDataCollectedAt(string $data_collected_at) Return CbInputLog objects filtered by the data_collected_at column
 */
abstract class BaseCbInputLogQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCbInputLogQuery object.
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
            $modelName = 'DeviceBundle\\Model\\CbInputLog';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CbInputLogQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CbInputLogQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CbInputLogQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CbInputLogQuery) {
            return $criteria;
        }
        $query = new CbInputLogQuery(null, null, $modelAlias);

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
     * @return   CbInputLog|CbInputLog[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CbInputLogPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CbInputLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 CbInputLog A model object, or null if the key is not found
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
     * @return                 CbInputLog A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `input`, `switch_state`, `switch_when`, `raw_data`, `data_collected_at` FROM `cb_input_log` WHERE `id` = :p0';
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
            $obj = new CbInputLog();
            $obj->hydrate($row);
            CbInputLogPeer::addInstanceToPool($obj, (string) $key);
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
     * @return CbInputLog|CbInputLog[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|CbInputLog[]|mixed the list of results, formatted by the current formatter
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
     * @return CbInputLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CbInputLogPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CbInputLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CbInputLogPeer::ID, $keys, Criteria::IN);
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
     * @return CbInputLogQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CbInputLogPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CbInputLogPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputLogPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the input column
     *
     * Example usage:
     * <code>
     * $query->filterByInput(1234); // WHERE input = 1234
     * $query->filterByInput(array(12, 34)); // WHERE input IN (12, 34)
     * $query->filterByInput(array('min' => 12)); // WHERE input >= 12
     * $query->filterByInput(array('max' => 12)); // WHERE input <= 12
     * </code>
     *
     * @see       filterByCbInput()
     *
     * @param     mixed $input The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CbInputLogQuery The current query, for fluid interface
     */
    public function filterByInput($input = null, $comparison = null)
    {
        if (is_array($input)) {
            $useMinMax = false;
            if (isset($input['min'])) {
                $this->addUsingAlias(CbInputLogPeer::INPUT, $input['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($input['max'])) {
                $this->addUsingAlias(CbInputLogPeer::INPUT, $input['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputLogPeer::INPUT, $input, $comparison);
    }

    /**
     * Filter the query on the switch_state column
     *
     * Example usage:
     * <code>
     * $query->filterBySwitchState(true); // WHERE switch_state = true
     * $query->filterBySwitchState('yes'); // WHERE switch_state = true
     * </code>
     *
     * @param     boolean|string $switchState The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CbInputLogQuery The current query, for fluid interface
     */
    public function filterBySwitchState($switchState = null, $comparison = null)
    {
        if (is_string($switchState)) {
            $switchState = in_array(strtolower($switchState), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CbInputLogPeer::SWITCH_STATE, $switchState, $comparison);
    }

    /**
     * Filter the query on the switch_when column
     *
     * Example usage:
     * <code>
     * $query->filterBySwitchWhen(true); // WHERE switch_when = true
     * $query->filterBySwitchWhen('yes'); // WHERE switch_when = true
     * </code>
     *
     * @param     boolean|string $switchWhen The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CbInputLogQuery The current query, for fluid interface
     */
    public function filterBySwitchWhen($switchWhen = null, $comparison = null)
    {
        if (is_string($switchWhen)) {
            $switchWhen = in_array(strtolower($switchWhen), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CbInputLogPeer::SWITCH_WHEN, $switchWhen, $comparison);
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
     * @return CbInputLogQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CbInputLogPeer::RAW_DATA, $rawData, $comparison);
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
     * @return CbInputLogQuery The current query, for fluid interface
     */
    public function filterByDataCollectedAt($dataCollectedAt = null, $comparison = null)
    {
        if (is_array($dataCollectedAt)) {
            $useMinMax = false;
            if (isset($dataCollectedAt['min'])) {
                $this->addUsingAlias(CbInputLogPeer::DATA_COLLECTED_AT, $dataCollectedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dataCollectedAt['max'])) {
                $this->addUsingAlias(CbInputLogPeer::DATA_COLLECTED_AT, $dataCollectedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputLogPeer::DATA_COLLECTED_AT, $dataCollectedAt, $comparison);
    }

    /**
     * Filter the query by a related CbInput object
     *
     * @param   CbInput|PropelObjectCollection $cbInput The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CbInputLogQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCbInput($cbInput, $comparison = null)
    {
        if ($cbInput instanceof CbInput) {
            return $this
                ->addUsingAlias(CbInputLogPeer::INPUT, $cbInput->getId(), $comparison);
        } elseif ($cbInput instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CbInputLogPeer::INPUT, $cbInput->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return CbInputLogQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   CbInputLog $cbInputLog Object to remove from the list of results
     *
     * @return CbInputLogQuery The current query, for fluid interface
     */
    public function prune($cbInputLog = null)
    {
        if ($cbInputLog) {
            $this->addUsingAlias(CbInputLogPeer::ID, $cbInputLog->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
