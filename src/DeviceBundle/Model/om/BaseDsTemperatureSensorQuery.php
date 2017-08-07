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
use DeviceBundle\Model\ControllerBox;
use DeviceBundle\Model\DeviceCopy;
use DeviceBundle\Model\DeviceGroup;
use DeviceBundle\Model\DsTemperatureSensor;
use DeviceBundle\Model\DsTemperatureSensorLog;
use DeviceBundle\Model\DsTemperatureSensorPeer;
use DeviceBundle\Model\DsTemperatureSensorQuery;
use NotificationBundle\Model\DsTemperatureNotification;
use StoreBundle\Model\Store;

/**
 * @method DsTemperatureSensorQuery orderById($order = Criteria::ASC) Order by the id column
 * @method DsTemperatureSensorQuery orderByUid($order = Criteria::ASC) Order by the uid column
 * @method DsTemperatureSensorQuery orderByGroup($order = Criteria::ASC) Order by the group column
 * @method DsTemperatureSensorQuery orderByController($order = Criteria::ASC) Order by the controller column
 * @method DsTemperatureSensorQuery orderByMainStore($order = Criteria::ASC) Order by the main_store column
 * @method DsTemperatureSensorQuery orderByOutputNumber($order = Criteria::ASC) Order by the output_number column
 * @method DsTemperatureSensorQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method DsTemperatureSensorQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method DsTemperatureSensorQuery orderByState($order = Criteria::ASC) Order by the state column
 * @method DsTemperatureSensorQuery orderByLowLimit($order = Criteria::ASC) Order by the low_limit column
 * @method DsTemperatureSensorQuery orderByTemperature($order = Criteria::ASC) Order by the temperature column
 * @method DsTemperatureSensorQuery orderByHighLimit($order = Criteria::ASC) Order by the high_limit column
 * @method DsTemperatureSensorQuery orderByPosition($order = Criteria::ASC) Order by the position column
 * @method DsTemperatureSensorQuery orderByDataCollectedAt($order = Criteria::ASC) Order by the data_collected_at column
 * @method DsTemperatureSensorQuery orderByNotifyAfter($order = Criteria::ASC) Order by the notify_after column
 * @method DsTemperatureSensorQuery orderByNotifyStartedAt($order = Criteria::ASC) Order by the notify_started_at column
 * @method DsTemperatureSensorQuery orderByIsEnabled($order = Criteria::ASC) Order by the is_enabled column
 * @method DsTemperatureSensorQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method DsTemperatureSensorQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method DsTemperatureSensorQuery groupById() Group by the id column
 * @method DsTemperatureSensorQuery groupByUid() Group by the uid column
 * @method DsTemperatureSensorQuery groupByGroup() Group by the group column
 * @method DsTemperatureSensorQuery groupByController() Group by the controller column
 * @method DsTemperatureSensorQuery groupByMainStore() Group by the main_store column
 * @method DsTemperatureSensorQuery groupByOutputNumber() Group by the output_number column
 * @method DsTemperatureSensorQuery groupByName() Group by the name column
 * @method DsTemperatureSensorQuery groupByDescription() Group by the description column
 * @method DsTemperatureSensorQuery groupByState() Group by the state column
 * @method DsTemperatureSensorQuery groupByLowLimit() Group by the low_limit column
 * @method DsTemperatureSensorQuery groupByTemperature() Group by the temperature column
 * @method DsTemperatureSensorQuery groupByHighLimit() Group by the high_limit column
 * @method DsTemperatureSensorQuery groupByPosition() Group by the position column
 * @method DsTemperatureSensorQuery groupByDataCollectedAt() Group by the data_collected_at column
 * @method DsTemperatureSensorQuery groupByNotifyAfter() Group by the notify_after column
 * @method DsTemperatureSensorQuery groupByNotifyStartedAt() Group by the notify_started_at column
 * @method DsTemperatureSensorQuery groupByIsEnabled() Group by the is_enabled column
 * @method DsTemperatureSensorQuery groupByCreatedAt() Group by the created_at column
 * @method DsTemperatureSensorQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method DsTemperatureSensorQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method DsTemperatureSensorQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method DsTemperatureSensorQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method DsTemperatureSensorQuery leftJoinStore($relationAlias = null) Adds a LEFT JOIN clause to the query using the Store relation
 * @method DsTemperatureSensorQuery rightJoinStore($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Store relation
 * @method DsTemperatureSensorQuery innerJoinStore($relationAlias = null) Adds a INNER JOIN clause to the query using the Store relation
 *
 * @method DsTemperatureSensorQuery leftJoinDeviceGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the DeviceGroup relation
 * @method DsTemperatureSensorQuery rightJoinDeviceGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DeviceGroup relation
 * @method DsTemperatureSensorQuery innerJoinDeviceGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the DeviceGroup relation
 *
 * @method DsTemperatureSensorQuery leftJoinControllerBox($relationAlias = null) Adds a LEFT JOIN clause to the query using the ControllerBox relation
 * @method DsTemperatureSensorQuery rightJoinControllerBox($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ControllerBox relation
 * @method DsTemperatureSensorQuery innerJoinControllerBox($relationAlias = null) Adds a INNER JOIN clause to the query using the ControllerBox relation
 *
 * @method DsTemperatureSensorQuery leftJoinDsTemperatureSensorLog($relationAlias = null) Adds a LEFT JOIN clause to the query using the DsTemperatureSensorLog relation
 * @method DsTemperatureSensorQuery rightJoinDsTemperatureSensorLog($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DsTemperatureSensorLog relation
 * @method DsTemperatureSensorQuery innerJoinDsTemperatureSensorLog($relationAlias = null) Adds a INNER JOIN clause to the query using the DsTemperatureSensorLog relation
 *
 * @method DsTemperatureSensorQuery leftJoinDeviceCopy($relationAlias = null) Adds a LEFT JOIN clause to the query using the DeviceCopy relation
 * @method DsTemperatureSensorQuery rightJoinDeviceCopy($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DeviceCopy relation
 * @method DsTemperatureSensorQuery innerJoinDeviceCopy($relationAlias = null) Adds a INNER JOIN clause to the query using the DeviceCopy relation
 *
 * @method DsTemperatureSensorQuery leftJoinDsTemperatureNotification($relationAlias = null) Adds a LEFT JOIN clause to the query using the DsTemperatureNotification relation
 * @method DsTemperatureSensorQuery rightJoinDsTemperatureNotification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DsTemperatureNotification relation
 * @method DsTemperatureSensorQuery innerJoinDsTemperatureNotification($relationAlias = null) Adds a INNER JOIN clause to the query using the DsTemperatureNotification relation
 *
 * @method DsTemperatureSensor findOne(PropelPDO $con = null) Return the first DsTemperatureSensor matching the query
 * @method DsTemperatureSensor findOneOrCreate(PropelPDO $con = null) Return the first DsTemperatureSensor matching the query, or a new DsTemperatureSensor object populated from the query conditions when no match is found
 *
 * @method DsTemperatureSensor findOneByUid(string $uid) Return the first DsTemperatureSensor filtered by the uid column
 * @method DsTemperatureSensor findOneByGroup(int $group) Return the first DsTemperatureSensor filtered by the group column
 * @method DsTemperatureSensor findOneByController(int $controller) Return the first DsTemperatureSensor filtered by the controller column
 * @method DsTemperatureSensor findOneByMainStore(int $main_store) Return the first DsTemperatureSensor filtered by the main_store column
 * @method DsTemperatureSensor findOneByOutputNumber(int $output_number) Return the first DsTemperatureSensor filtered by the output_number column
 * @method DsTemperatureSensor findOneByName(string $name) Return the first DsTemperatureSensor filtered by the name column
 * @method DsTemperatureSensor findOneByDescription(string $description) Return the first DsTemperatureSensor filtered by the description column
 * @method DsTemperatureSensor findOneByState(int $state) Return the first DsTemperatureSensor filtered by the state column
 * @method DsTemperatureSensor findOneByLowLimit(string $low_limit) Return the first DsTemperatureSensor filtered by the low_limit column
 * @method DsTemperatureSensor findOneByTemperature(string $temperature) Return the first DsTemperatureSensor filtered by the temperature column
 * @method DsTemperatureSensor findOneByHighLimit(string $high_limit) Return the first DsTemperatureSensor filtered by the high_limit column
 * @method DsTemperatureSensor findOneByPosition(int $position) Return the first DsTemperatureSensor filtered by the position column
 * @method DsTemperatureSensor findOneByDataCollectedAt(string $data_collected_at) Return the first DsTemperatureSensor filtered by the data_collected_at column
 * @method DsTemperatureSensor findOneByNotifyAfter(int $notify_after) Return the first DsTemperatureSensor filtered by the notify_after column
 * @method DsTemperatureSensor findOneByNotifyStartedAt(string $notify_started_at) Return the first DsTemperatureSensor filtered by the notify_started_at column
 * @method DsTemperatureSensor findOneByIsEnabled(boolean $is_enabled) Return the first DsTemperatureSensor filtered by the is_enabled column
 * @method DsTemperatureSensor findOneByCreatedAt(string $created_at) Return the first DsTemperatureSensor filtered by the created_at column
 * @method DsTemperatureSensor findOneByUpdatedAt(string $updated_at) Return the first DsTemperatureSensor filtered by the updated_at column
 *
 * @method array findById(int $id) Return DsTemperatureSensor objects filtered by the id column
 * @method array findByUid(string $uid) Return DsTemperatureSensor objects filtered by the uid column
 * @method array findByGroup(int $group) Return DsTemperatureSensor objects filtered by the group column
 * @method array findByController(int $controller) Return DsTemperatureSensor objects filtered by the controller column
 * @method array findByMainStore(int $main_store) Return DsTemperatureSensor objects filtered by the main_store column
 * @method array findByOutputNumber(int $output_number) Return DsTemperatureSensor objects filtered by the output_number column
 * @method array findByName(string $name) Return DsTemperatureSensor objects filtered by the name column
 * @method array findByDescription(string $description) Return DsTemperatureSensor objects filtered by the description column
 * @method array findByState(int $state) Return DsTemperatureSensor objects filtered by the state column
 * @method array findByLowLimit(string $low_limit) Return DsTemperatureSensor objects filtered by the low_limit column
 * @method array findByTemperature(string $temperature) Return DsTemperatureSensor objects filtered by the temperature column
 * @method array findByHighLimit(string $high_limit) Return DsTemperatureSensor objects filtered by the high_limit column
 * @method array findByPosition(int $position) Return DsTemperatureSensor objects filtered by the position column
 * @method array findByDataCollectedAt(string $data_collected_at) Return DsTemperatureSensor objects filtered by the data_collected_at column
 * @method array findByNotifyAfter(int $notify_after) Return DsTemperatureSensor objects filtered by the notify_after column
 * @method array findByNotifyStartedAt(string $notify_started_at) Return DsTemperatureSensor objects filtered by the notify_started_at column
 * @method array findByIsEnabled(boolean $is_enabled) Return DsTemperatureSensor objects filtered by the is_enabled column
 * @method array findByCreatedAt(string $created_at) Return DsTemperatureSensor objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return DsTemperatureSensor objects filtered by the updated_at column
 */
abstract class BaseDsTemperatureSensorQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseDsTemperatureSensorQuery object.
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
            $modelName = 'DeviceBundle\\Model\\DsTemperatureSensor';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new DsTemperatureSensorQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   DsTemperatureSensorQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return DsTemperatureSensorQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof DsTemperatureSensorQuery) {
            return $criteria;
        }
        $query = new DsTemperatureSensorQuery(null, null, $modelAlias);

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
     * @return   DsTemperatureSensor|DsTemperatureSensor[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DsTemperatureSensorPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(DsTemperatureSensorPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 DsTemperatureSensor A model object, or null if the key is not found
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
     * @return                 DsTemperatureSensor A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uid`, `group`, `controller`, `main_store`, `output_number`, `name`, `description`, `state`, `low_limit`, `temperature`, `high_limit`, `position`, `data_collected_at`, `notify_after`, `notify_started_at`, `is_enabled`, `created_at`, `updated_at` FROM `ds_temperature_sensor` WHERE `id` = :p0';
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
            $obj = new DsTemperatureSensor();
            $obj->hydrate($row);
            DsTemperatureSensorPeer::addInstanceToPool($obj, (string) $key);
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
     * @return DsTemperatureSensor|DsTemperatureSensor[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|DsTemperatureSensor[]|mixed the list of results, formatted by the current formatter
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DsTemperatureSensorPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DsTemperatureSensorPeer::ID, $keys, Criteria::IN);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorPeer::ID, $id, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DsTemperatureSensorPeer::UID, $uid, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByGroup($group = null, $comparison = null)
    {
        if (is_array($group)) {
            $useMinMax = false;
            if (isset($group['min'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::GROUP, $group['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($group['max'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::GROUP, $group['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorPeer::GROUP, $group, $comparison);
    }

    /**
     * Filter the query on the controller column
     *
     * Example usage:
     * <code>
     * $query->filterByController(1234); // WHERE controller = 1234
     * $query->filterByController(array(12, 34)); // WHERE controller IN (12, 34)
     * $query->filterByController(array('min' => 12)); // WHERE controller >= 12
     * $query->filterByController(array('max' => 12)); // WHERE controller <= 12
     * </code>
     *
     * @see       filterByControllerBox()
     *
     * @param     mixed $controller The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByController($controller = null, $comparison = null)
    {
        if (is_array($controller)) {
            $useMinMax = false;
            if (isset($controller['min'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::CONTROLLER, $controller['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($controller['max'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::CONTROLLER, $controller['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorPeer::CONTROLLER, $controller, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByMainStore($mainStore = null, $comparison = null)
    {
        if (is_array($mainStore)) {
            $useMinMax = false;
            if (isset($mainStore['min'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::MAIN_STORE, $mainStore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($mainStore['max'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::MAIN_STORE, $mainStore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorPeer::MAIN_STORE, $mainStore, $comparison);
    }

    /**
     * Filter the query on the output_number column
     *
     * Example usage:
     * <code>
     * $query->filterByOutputNumber(1234); // WHERE output_number = 1234
     * $query->filterByOutputNumber(array(12, 34)); // WHERE output_number IN (12, 34)
     * $query->filterByOutputNumber(array('min' => 12)); // WHERE output_number >= 12
     * $query->filterByOutputNumber(array('max' => 12)); // WHERE output_number <= 12
     * </code>
     *
     * @param     mixed $outputNumber The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByOutputNumber($outputNumber = null, $comparison = null)
    {
        if (is_array($outputNumber)) {
            $useMinMax = false;
            if (isset($outputNumber['min'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::OUTPUT_NUMBER, $outputNumber['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($outputNumber['max'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::OUTPUT_NUMBER, $outputNumber['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorPeer::OUTPUT_NUMBER, $outputNumber, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DsTemperatureSensorPeer::NAME, $name, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DsTemperatureSensorPeer::DESCRIPTION, $description, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByState($state = null, $comparison = null)
    {
        if (is_array($state)) {
            $useMinMax = false;
            if (isset($state['min'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::STATE, $state['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($state['max'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::STATE, $state['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorPeer::STATE, $state, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DsTemperatureSensorPeer::LOW_LIMIT, $lowLimit, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DsTemperatureSensorPeer::TEMPERATURE, $temperature, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DsTemperatureSensorPeer::HIGH_LIMIT, $highLimit, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (is_array($position)) {
            $useMinMax = false;
            if (isset($position['min'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::POSITION, $position['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($position['max'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::POSITION, $position['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorPeer::POSITION, $position, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByDataCollectedAt($dataCollectedAt = null, $comparison = null)
    {
        if (is_array($dataCollectedAt)) {
            $useMinMax = false;
            if (isset($dataCollectedAt['min'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::DATA_COLLECTED_AT, $dataCollectedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dataCollectedAt['max'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::DATA_COLLECTED_AT, $dataCollectedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorPeer::DATA_COLLECTED_AT, $dataCollectedAt, $comparison);
    }

    /**
     * Filter the query on the notify_after column
     *
     * Example usage:
     * <code>
     * $query->filterByNotifyAfter(1234); // WHERE notify_after = 1234
     * $query->filterByNotifyAfter(array(12, 34)); // WHERE notify_after IN (12, 34)
     * $query->filterByNotifyAfter(array('min' => 12)); // WHERE notify_after >= 12
     * $query->filterByNotifyAfter(array('max' => 12)); // WHERE notify_after <= 12
     * </code>
     *
     * @param     mixed $notifyAfter The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByNotifyAfter($notifyAfter = null, $comparison = null)
    {
        if (is_array($notifyAfter)) {
            $useMinMax = false;
            if (isset($notifyAfter['min'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::NOTIFY_AFTER, $notifyAfter['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($notifyAfter['max'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::NOTIFY_AFTER, $notifyAfter['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorPeer::NOTIFY_AFTER, $notifyAfter, $comparison);
    }

    /**
     * Filter the query on the notify_started_at column
     *
     * Example usage:
     * <code>
     * $query->filterByNotifyStartedAt('2011-03-14'); // WHERE notify_started_at = '2011-03-14'
     * $query->filterByNotifyStartedAt('now'); // WHERE notify_started_at = '2011-03-14'
     * $query->filterByNotifyStartedAt(array('max' => 'yesterday')); // WHERE notify_started_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $notifyStartedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByNotifyStartedAt($notifyStartedAt = null, $comparison = null)
    {
        if (is_array($notifyStartedAt)) {
            $useMinMax = false;
            if (isset($notifyStartedAt['min'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::NOTIFY_STARTED_AT, $notifyStartedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($notifyStartedAt['max'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::NOTIFY_STARTED_AT, $notifyStartedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorPeer::NOTIFY_STARTED_AT, $notifyStartedAt, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByIsEnabled($isEnabled = null, $comparison = null)
    {
        if (is_string($isEnabled)) {
            $isEnabled = in_array(strtolower($isEnabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(DsTemperatureSensorPeer::IS_ENABLED, $isEnabled, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(DsTemperatureSensorPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DsTemperatureSensorPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Store object
     *
     * @param   Store|PropelObjectCollection $store The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DsTemperatureSensorQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStore($store, $comparison = null)
    {
        if ($store instanceof Store) {
            return $this
                ->addUsingAlias(DsTemperatureSensorPeer::MAIN_STORE, $store->getId(), $comparison);
        } elseif ($store instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DsTemperatureSensorPeer::MAIN_STORE, $store->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
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
     * Filter the query by a related DeviceGroup object
     *
     * @param   DeviceGroup|PropelObjectCollection $deviceGroup The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DsTemperatureSensorQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDeviceGroup($deviceGroup, $comparison = null)
    {
        if ($deviceGroup instanceof DeviceGroup) {
            return $this
                ->addUsingAlias(DsTemperatureSensorPeer::GROUP, $deviceGroup->getId(), $comparison);
        } elseif ($deviceGroup instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DsTemperatureSensorPeer::GROUP, $deviceGroup->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
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
     * Filter the query by a related ControllerBox object
     *
     * @param   ControllerBox|PropelObjectCollection $controllerBox The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DsTemperatureSensorQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByControllerBox($controllerBox, $comparison = null)
    {
        if ($controllerBox instanceof ControllerBox) {
            return $this
                ->addUsingAlias(DsTemperatureSensorPeer::CONTROLLER, $controllerBox->getId(), $comparison);
        } elseif ($controllerBox instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DsTemperatureSensorPeer::CONTROLLER, $controllerBox->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
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
     * Filter the query by a related DsTemperatureSensorLog object
     *
     * @param   DsTemperatureSensorLog|PropelObjectCollection $dsTemperatureSensorLog  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DsTemperatureSensorQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDsTemperatureSensorLog($dsTemperatureSensorLog, $comparison = null)
    {
        if ($dsTemperatureSensorLog instanceof DsTemperatureSensorLog) {
            return $this
                ->addUsingAlias(DsTemperatureSensorPeer::ID, $dsTemperatureSensorLog->getSensor(), $comparison);
        } elseif ($dsTemperatureSensorLog instanceof PropelObjectCollection) {
            return $this
                ->useDsTemperatureSensorLogQuery()
                ->filterByPrimaryKeys($dsTemperatureSensorLog->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDsTemperatureSensorLog() only accepts arguments of type DsTemperatureSensorLog or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DsTemperatureSensorLog relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function joinDsTemperatureSensorLog($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DsTemperatureSensorLog');

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
            $this->addJoinObject($join, 'DsTemperatureSensorLog');
        }

        return $this;
    }

    /**
     * Use the DsTemperatureSensorLog relation DsTemperatureSensorLog object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \DeviceBundle\Model\DsTemperatureSensorLogQuery A secondary query class using the current class as primary query
     */
    public function useDsTemperatureSensorLogQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDsTemperatureSensorLog($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DsTemperatureSensorLog', '\DeviceBundle\Model\DsTemperatureSensorLogQuery');
    }

    /**
     * Filter the query by a related DeviceCopy object
     *
     * @param   DeviceCopy|PropelObjectCollection $deviceCopy  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DsTemperatureSensorQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDeviceCopy($deviceCopy, $comparison = null)
    {
        if ($deviceCopy instanceof DeviceCopy) {
            return $this
                ->addUsingAlias(DsTemperatureSensorPeer::ID, $deviceCopy->getCopyOfSensor(), $comparison);
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
     * @return DsTemperatureSensorQuery The current query, for fluid interface
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
     * Filter the query by a related DsTemperatureNotification object
     *
     * @param   DsTemperatureNotification|PropelObjectCollection $dsTemperatureNotification  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DsTemperatureSensorQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDsTemperatureNotification($dsTemperatureNotification, $comparison = null)
    {
        if ($dsTemperatureNotification instanceof DsTemperatureNotification) {
            return $this
                ->addUsingAlias(DsTemperatureSensorPeer::ID, $dsTemperatureNotification->getSensor(), $comparison);
        } elseif ($dsTemperatureNotification instanceof PropelObjectCollection) {
            return $this
                ->useDsTemperatureNotificationQuery()
                ->filterByPrimaryKeys($dsTemperatureNotification->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDsTemperatureNotification() only accepts arguments of type DsTemperatureNotification or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DsTemperatureNotification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function joinDsTemperatureNotification($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DsTemperatureNotification');

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
            $this->addJoinObject($join, 'DsTemperatureNotification');
        }

        return $this;
    }

    /**
     * Use the DsTemperatureNotification relation DsTemperatureNotification object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \NotificationBundle\Model\DsTemperatureNotificationQuery A secondary query class using the current class as primary query
     */
    public function useDsTemperatureNotificationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDsTemperatureNotification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DsTemperatureNotification', '\NotificationBundle\Model\DsTemperatureNotificationQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   DsTemperatureSensor $dsTemperatureSensor Object to remove from the list of results
     *
     * @return DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function prune($dsTemperatureSensor = null)
    {
        if ($dsTemperatureSensor) {
            $this->addUsingAlias(DsTemperatureSensorPeer::ID, $dsTemperatureSensor->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(DsTemperatureSensorPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(DsTemperatureSensorPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(DsTemperatureSensorPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(DsTemperatureSensorPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(DsTemperatureSensorPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     DsTemperatureSensorQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(DsTemperatureSensorPeer::CREATED_AT);
    }
}
