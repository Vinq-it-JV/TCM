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
use DeviceBundle\Model\DeviceCopy;
use DeviceBundle\Model\DeviceCopyPeer;
use DeviceBundle\Model\DeviceCopyQuery;
use DeviceBundle\Model\DeviceGroup;
use DeviceBundle\Model\DsTemperatureSensor;
use StoreBundle\Model\Store;

/**
 * @method DeviceCopyQuery orderById($order = Criteria::ASC) Order by the id column
 * @method DeviceCopyQuery orderByUid($order = Criteria::ASC) Order by the uid column
 * @method DeviceCopyQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method DeviceCopyQuery orderByPosition($order = Criteria::ASC) Order by the position column
 * @method DeviceCopyQuery orderByCopyOfInput($order = Criteria::ASC) Order by the copy_of_input column
 * @method DeviceCopyQuery orderByCopyOfSensor($order = Criteria::ASC) Order by the copy_of_sensor column
 * @method DeviceCopyQuery orderByGroup($order = Criteria::ASC) Order by the group column
 * @method DeviceCopyQuery orderByMainStore($order = Criteria::ASC) Order by the main_store column
 *
 * @method DeviceCopyQuery groupById() Group by the id column
 * @method DeviceCopyQuery groupByUid() Group by the uid column
 * @method DeviceCopyQuery groupByName() Group by the name column
 * @method DeviceCopyQuery groupByPosition() Group by the position column
 * @method DeviceCopyQuery groupByCopyOfInput() Group by the copy_of_input column
 * @method DeviceCopyQuery groupByCopyOfSensor() Group by the copy_of_sensor column
 * @method DeviceCopyQuery groupByGroup() Group by the group column
 * @method DeviceCopyQuery groupByMainStore() Group by the main_store column
 *
 * @method DeviceCopyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method DeviceCopyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method DeviceCopyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method DeviceCopyQuery leftJoinCbInput($relationAlias = null) Adds a LEFT JOIN clause to the query using the CbInput relation
 * @method DeviceCopyQuery rightJoinCbInput($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CbInput relation
 * @method DeviceCopyQuery innerJoinCbInput($relationAlias = null) Adds a INNER JOIN clause to the query using the CbInput relation
 *
 * @method DeviceCopyQuery leftJoinDsTemperatureSensor($relationAlias = null) Adds a LEFT JOIN clause to the query using the DsTemperatureSensor relation
 * @method DeviceCopyQuery rightJoinDsTemperatureSensor($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DsTemperatureSensor relation
 * @method DeviceCopyQuery innerJoinDsTemperatureSensor($relationAlias = null) Adds a INNER JOIN clause to the query using the DsTemperatureSensor relation
 *
 * @method DeviceCopyQuery leftJoinDeviceGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the DeviceGroup relation
 * @method DeviceCopyQuery rightJoinDeviceGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DeviceGroup relation
 * @method DeviceCopyQuery innerJoinDeviceGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the DeviceGroup relation
 *
 * @method DeviceCopyQuery leftJoinStore($relationAlias = null) Adds a LEFT JOIN clause to the query using the Store relation
 * @method DeviceCopyQuery rightJoinStore($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Store relation
 * @method DeviceCopyQuery innerJoinStore($relationAlias = null) Adds a INNER JOIN clause to the query using the Store relation
 *
 * @method DeviceCopy findOne(PropelPDO $con = null) Return the first DeviceCopy matching the query
 * @method DeviceCopy findOneOrCreate(PropelPDO $con = null) Return the first DeviceCopy matching the query, or a new DeviceCopy object populated from the query conditions when no match is found
 *
 * @method DeviceCopy findOneByUid(string $uid) Return the first DeviceCopy filtered by the uid column
 * @method DeviceCopy findOneByName(string $name) Return the first DeviceCopy filtered by the name column
 * @method DeviceCopy findOneByPosition(int $position) Return the first DeviceCopy filtered by the position column
 * @method DeviceCopy findOneByCopyOfInput(int $copy_of_input) Return the first DeviceCopy filtered by the copy_of_input column
 * @method DeviceCopy findOneByCopyOfSensor(int $copy_of_sensor) Return the first DeviceCopy filtered by the copy_of_sensor column
 * @method DeviceCopy findOneByGroup(int $group) Return the first DeviceCopy filtered by the group column
 * @method DeviceCopy findOneByMainStore(int $main_store) Return the first DeviceCopy filtered by the main_store column
 *
 * @method array findById(int $id) Return DeviceCopy objects filtered by the id column
 * @method array findByUid(string $uid) Return DeviceCopy objects filtered by the uid column
 * @method array findByName(string $name) Return DeviceCopy objects filtered by the name column
 * @method array findByPosition(int $position) Return DeviceCopy objects filtered by the position column
 * @method array findByCopyOfInput(int $copy_of_input) Return DeviceCopy objects filtered by the copy_of_input column
 * @method array findByCopyOfSensor(int $copy_of_sensor) Return DeviceCopy objects filtered by the copy_of_sensor column
 * @method array findByGroup(int $group) Return DeviceCopy objects filtered by the group column
 * @method array findByMainStore(int $main_store) Return DeviceCopy objects filtered by the main_store column
 */
abstract class BaseDeviceCopyQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseDeviceCopyQuery object.
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
            $modelName = 'DeviceBundle\\Model\\DeviceCopy';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new DeviceCopyQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   DeviceCopyQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return DeviceCopyQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof DeviceCopyQuery) {
            return $criteria;
        }
        $query = new DeviceCopyQuery(null, null, $modelAlias);

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
     * @return   DeviceCopy|DeviceCopy[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DeviceCopyPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(DeviceCopyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 DeviceCopy A model object, or null if the key is not found
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
     * @return                 DeviceCopy A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uid`, `name`, `position`, `copy_of_input`, `copy_of_sensor`, `group`, `main_store` FROM `device_copy` WHERE `id` = :p0';
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
            $obj = new DeviceCopy();
            $obj->hydrate($row);
            DeviceCopyPeer::addInstanceToPool($obj, (string) $key);
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
     * @return DeviceCopy|DeviceCopy[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|DeviceCopy[]|mixed the list of results, formatted by the current formatter
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
     * @return DeviceCopyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DeviceCopyPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return DeviceCopyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DeviceCopyPeer::ID, $keys, Criteria::IN);
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
     * @return DeviceCopyQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DeviceCopyPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DeviceCopyPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DeviceCopyPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the uid column
     *
     * Example usage:
     * <code>
     * $query->filterByUid('fooValue');   // WHERE uid = 'fooValue'
     * $query->filterByUid('%fooValue%'); // WHERE uid LIKE '%fooValue%'
     * </code>
     *
     * @param     string $uid The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DeviceCopyQuery The current query, for fluid interface
     */
    public function filterByUid($uid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($uid)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $uid)) {
                $uid = str_replace('*', '%', $uid);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DeviceCopyPeer::UID, $uid, $comparison);
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
     * @return DeviceCopyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DeviceCopyPeer::NAME, $name, $comparison);
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
     * @return DeviceCopyQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (is_array($position)) {
            $useMinMax = false;
            if (isset($position['min'])) {
                $this->addUsingAlias(DeviceCopyPeer::POSITION, $position['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($position['max'])) {
                $this->addUsingAlias(DeviceCopyPeer::POSITION, $position['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DeviceCopyPeer::POSITION, $position, $comparison);
    }

    /**
     * Filter the query on the copy_of_input column
     *
     * Example usage:
     * <code>
     * $query->filterByCopyOfInput(1234); // WHERE copy_of_input = 1234
     * $query->filterByCopyOfInput(array(12, 34)); // WHERE copy_of_input IN (12, 34)
     * $query->filterByCopyOfInput(array('min' => 12)); // WHERE copy_of_input >= 12
     * $query->filterByCopyOfInput(array('max' => 12)); // WHERE copy_of_input <= 12
     * </code>
     *
     * @see       filterByCbInput()
     *
     * @param     mixed $copyOfInput The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DeviceCopyQuery The current query, for fluid interface
     */
    public function filterByCopyOfInput($copyOfInput = null, $comparison = null)
    {
        if (is_array($copyOfInput)) {
            $useMinMax = false;
            if (isset($copyOfInput['min'])) {
                $this->addUsingAlias(DeviceCopyPeer::COPY_OF_INPUT, $copyOfInput['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($copyOfInput['max'])) {
                $this->addUsingAlias(DeviceCopyPeer::COPY_OF_INPUT, $copyOfInput['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DeviceCopyPeer::COPY_OF_INPUT, $copyOfInput, $comparison);
    }

    /**
     * Filter the query on the copy_of_sensor column
     *
     * Example usage:
     * <code>
     * $query->filterByCopyOfSensor(1234); // WHERE copy_of_sensor = 1234
     * $query->filterByCopyOfSensor(array(12, 34)); // WHERE copy_of_sensor IN (12, 34)
     * $query->filterByCopyOfSensor(array('min' => 12)); // WHERE copy_of_sensor >= 12
     * $query->filterByCopyOfSensor(array('max' => 12)); // WHERE copy_of_sensor <= 12
     * </code>
     *
     * @see       filterByDsTemperatureSensor()
     *
     * @param     mixed $copyOfSensor The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DeviceCopyQuery The current query, for fluid interface
     */
    public function filterByCopyOfSensor($copyOfSensor = null, $comparison = null)
    {
        if (is_array($copyOfSensor)) {
            $useMinMax = false;
            if (isset($copyOfSensor['min'])) {
                $this->addUsingAlias(DeviceCopyPeer::COPY_OF_SENSOR, $copyOfSensor['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($copyOfSensor['max'])) {
                $this->addUsingAlias(DeviceCopyPeer::COPY_OF_SENSOR, $copyOfSensor['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DeviceCopyPeer::COPY_OF_SENSOR, $copyOfSensor, $comparison);
    }

    /**
     * Filter the query on the group column
     *
     * Example usage:
     * <code>
     * $query->filterByGroup(1234); // WHERE group = 1234
     * $query->filterByGroup(array(12, 34)); // WHERE group IN (12, 34)
     * $query->filterByGroup(array('min' => 12)); // WHERE group >= 12
     * $query->filterByGroup(array('max' => 12)); // WHERE group <= 12
     * </code>
     *
     * @see       filterByDeviceGroup()
     *
     * @param     mixed $group The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DeviceCopyQuery The current query, for fluid interface
     */
    public function filterByGroup($group = null, $comparison = null)
    {
        if (is_array($group)) {
            $useMinMax = false;
            if (isset($group['min'])) {
                $this->addUsingAlias(DeviceCopyPeer::GROUP, $group['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($group['max'])) {
                $this->addUsingAlias(DeviceCopyPeer::GROUP, $group['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DeviceCopyPeer::GROUP, $group, $comparison);
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
     * @return DeviceCopyQuery The current query, for fluid interface
     */
    public function filterByMainStore($mainStore = null, $comparison = null)
    {
        if (is_array($mainStore)) {
            $useMinMax = false;
            if (isset($mainStore['min'])) {
                $this->addUsingAlias(DeviceCopyPeer::MAIN_STORE, $mainStore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($mainStore['max'])) {
                $this->addUsingAlias(DeviceCopyPeer::MAIN_STORE, $mainStore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DeviceCopyPeer::MAIN_STORE, $mainStore, $comparison);
    }

    /**
     * Filter the query by a related CbInput object
     *
     * @param   CbInput|PropelObjectCollection $cbInput The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DeviceCopyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCbInput($cbInput, $comparison = null)
    {
        if ($cbInput instanceof CbInput) {
            return $this
                ->addUsingAlias(DeviceCopyPeer::COPY_OF_INPUT, $cbInput->getId(), $comparison);
        } elseif ($cbInput instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DeviceCopyPeer::COPY_OF_INPUT, $cbInput->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return DeviceCopyQuery The current query, for fluid interface
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
     * Filter the query by a related DsTemperatureSensor object
     *
     * @param   DsTemperatureSensor|PropelObjectCollection $dsTemperatureSensor The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DeviceCopyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDsTemperatureSensor($dsTemperatureSensor, $comparison = null)
    {
        if ($dsTemperatureSensor instanceof DsTemperatureSensor) {
            return $this
                ->addUsingAlias(DeviceCopyPeer::COPY_OF_SENSOR, $dsTemperatureSensor->getId(), $comparison);
        } elseif ($dsTemperatureSensor instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DeviceCopyPeer::COPY_OF_SENSOR, $dsTemperatureSensor->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return DeviceCopyQuery The current query, for fluid interface
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
     * Filter the query by a related DeviceGroup object
     *
     * @param   DeviceGroup|PropelObjectCollection $deviceGroup The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DeviceCopyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDeviceGroup($deviceGroup, $comparison = null)
    {
        if ($deviceGroup instanceof DeviceGroup) {
            return $this
                ->addUsingAlias(DeviceCopyPeer::GROUP, $deviceGroup->getId(), $comparison);
        } elseif ($deviceGroup instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DeviceCopyPeer::GROUP, $deviceGroup->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDeviceGroup() only accepts arguments of type DeviceGroup or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DeviceGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DeviceCopyQuery The current query, for fluid interface
     */
    public function joinDeviceGroup($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DeviceGroup');

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
            $this->addJoinObject($join, 'DeviceGroup');
        }

        return $this;
    }

    /**
     * Use the DeviceGroup relation DeviceGroup object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DeviceBundle\Model\DeviceGroupQuery A secondary query class using the current class as primary query
     */
    public function useDeviceGroupQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDeviceGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DeviceGroup', '\DeviceBundle\Model\DeviceGroupQuery');
    }

    /**
     * Filter the query by a related Store object
     *
     * @param   Store|PropelObjectCollection $store The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DeviceCopyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStore($store, $comparison = null)
    {
        if ($store instanceof Store) {
            return $this
                ->addUsingAlias(DeviceCopyPeer::MAIN_STORE, $store->getId(), $comparison);
        } elseif ($store instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DeviceCopyPeer::MAIN_STORE, $store->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return DeviceCopyQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   DeviceCopy $deviceCopy Object to remove from the list of results
     *
     * @return DeviceCopyQuery The current query, for fluid interface
     */
    public function prune($deviceCopy = null)
    {
        if ($deviceCopy) {
            $this->addUsingAlias(DeviceCopyPeer::ID, $deviceCopy->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
