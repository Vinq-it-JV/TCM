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
use DeviceBundle\Model\ControllerBoxPeer;
use DeviceBundle\Model\ControllerBoxQuery;
use DeviceBundle\Model\DeviceGroup;
use DeviceBundle\Model\DsTemperatureSensor;
use StoreBundle\Model\Store;

/**
 * @method ControllerBoxQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ControllerBoxQuery orderByGroup($order = Criteria::ASC) Order by the group column
 * @method ControllerBoxQuery orderByMainStore($order = Criteria::ASC) Order by the main_store column
 * @method ControllerBoxQuery orderByVersion($order = Criteria::ASC) Order by the version column
 * @method ControllerBoxQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method ControllerBoxQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method ControllerBoxQuery orderByInputs($order = Criteria::ASC) Order by the inputs column
 * @method ControllerBoxQuery orderByInternalTemperature($order = Criteria::ASC) Order by the internal_temperature column
 * @method ControllerBoxQuery orderByUid($order = Criteria::ASC) Order by the uid column
 * @method ControllerBoxQuery orderByPosition($order = Criteria::ASC) Order by the position column
 * @method ControllerBoxQuery orderByIsEnabled($order = Criteria::ASC) Order by the is_enabled column
 * @method ControllerBoxQuery orderByIsDeleted($order = Criteria::ASC) Order by the is_deleted column
 * @method ControllerBoxQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method ControllerBoxQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method ControllerBoxQuery groupById() Group by the id column
 * @method ControllerBoxQuery groupByGroup() Group by the group column
 * @method ControllerBoxQuery groupByMainStore() Group by the main_store column
 * @method ControllerBoxQuery groupByVersion() Group by the version column
 * @method ControllerBoxQuery groupByName() Group by the name column
 * @method ControllerBoxQuery groupByDescription() Group by the description column
 * @method ControllerBoxQuery groupByInputs() Group by the inputs column
 * @method ControllerBoxQuery groupByInternalTemperature() Group by the internal_temperature column
 * @method ControllerBoxQuery groupByUid() Group by the uid column
 * @method ControllerBoxQuery groupByPosition() Group by the position column
 * @method ControllerBoxQuery groupByIsEnabled() Group by the is_enabled column
 * @method ControllerBoxQuery groupByIsDeleted() Group by the is_deleted column
 * @method ControllerBoxQuery groupByCreatedAt() Group by the created_at column
 * @method ControllerBoxQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method ControllerBoxQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ControllerBoxQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ControllerBoxQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ControllerBoxQuery leftJoinStore($relationAlias = null) Adds a LEFT JOIN clause to the query using the Store relation
 * @method ControllerBoxQuery rightJoinStore($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Store relation
 * @method ControllerBoxQuery innerJoinStore($relationAlias = null) Adds a INNER JOIN clause to the query using the Store relation
 *
 * @method ControllerBoxQuery leftJoinDeviceGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the DeviceGroup relation
 * @method ControllerBoxQuery rightJoinDeviceGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DeviceGroup relation
 * @method ControllerBoxQuery innerJoinDeviceGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the DeviceGroup relation
 *
 * @method ControllerBoxQuery leftJoinDsTemperatureSensor($relationAlias = null) Adds a LEFT JOIN clause to the query using the DsTemperatureSensor relation
 * @method ControllerBoxQuery rightJoinDsTemperatureSensor($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DsTemperatureSensor relation
 * @method ControllerBoxQuery innerJoinDsTemperatureSensor($relationAlias = null) Adds a INNER JOIN clause to the query using the DsTemperatureSensor relation
 *
 * @method ControllerBoxQuery leftJoinCbInput($relationAlias = null) Adds a LEFT JOIN clause to the query using the CbInput relation
 * @method ControllerBoxQuery rightJoinCbInput($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CbInput relation
 * @method ControllerBoxQuery innerJoinCbInput($relationAlias = null) Adds a INNER JOIN clause to the query using the CbInput relation
 *
 * @method ControllerBox findOne(PropelPDO $con = null) Return the first ControllerBox matching the query
 * @method ControllerBox findOneOrCreate(PropelPDO $con = null) Return the first ControllerBox matching the query, or a new ControllerBox object populated from the query conditions when no match is found
 *
 * @method ControllerBox findOneByGroup(int $group) Return the first ControllerBox filtered by the group column
 * @method ControllerBox findOneByMainStore(int $main_store) Return the first ControllerBox filtered by the main_store column
 * @method ControllerBox findOneByVersion(int $version) Return the first ControllerBox filtered by the version column
 * @method ControllerBox findOneByName(string $name) Return the first ControllerBox filtered by the name column
 * @method ControllerBox findOneByDescription(string $description) Return the first ControllerBox filtered by the description column
 * @method ControllerBox findOneByInputs(int $inputs) Return the first ControllerBox filtered by the inputs column
 * @method ControllerBox findOneByInternalTemperature(string $internal_temperature) Return the first ControllerBox filtered by the internal_temperature column
 * @method ControllerBox findOneByUid(string $uid) Return the first ControllerBox filtered by the uid column
 * @method ControllerBox findOneByPosition(int $position) Return the first ControllerBox filtered by the position column
 * @method ControllerBox findOneByIsEnabled(boolean $is_enabled) Return the first ControllerBox filtered by the is_enabled column
 * @method ControllerBox findOneByIsDeleted(boolean $is_deleted) Return the first ControllerBox filtered by the is_deleted column
 * @method ControllerBox findOneByCreatedAt(string $created_at) Return the first ControllerBox filtered by the created_at column
 * @method ControllerBox findOneByUpdatedAt(string $updated_at) Return the first ControllerBox filtered by the updated_at column
 *
 * @method array findById(int $id) Return ControllerBox objects filtered by the id column
 * @method array findByGroup(int $group) Return ControllerBox objects filtered by the group column
 * @method array findByMainStore(int $main_store) Return ControllerBox objects filtered by the main_store column
 * @method array findByVersion(int $version) Return ControllerBox objects filtered by the version column
 * @method array findByName(string $name) Return ControllerBox objects filtered by the name column
 * @method array findByDescription(string $description) Return ControllerBox objects filtered by the description column
 * @method array findByInputs(int $inputs) Return ControllerBox objects filtered by the inputs column
 * @method array findByInternalTemperature(string $internal_temperature) Return ControllerBox objects filtered by the internal_temperature column
 * @method array findByUid(string $uid) Return ControllerBox objects filtered by the uid column
 * @method array findByPosition(int $position) Return ControllerBox objects filtered by the position column
 * @method array findByIsEnabled(boolean $is_enabled) Return ControllerBox objects filtered by the is_enabled column
 * @method array findByIsDeleted(boolean $is_deleted) Return ControllerBox objects filtered by the is_deleted column
 * @method array findByCreatedAt(string $created_at) Return ControllerBox objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return ControllerBox objects filtered by the updated_at column
 */
abstract class BaseControllerBoxQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseControllerBoxQuery object.
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
            $modelName = 'DeviceBundle\\Model\\ControllerBox';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ControllerBoxQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ControllerBoxQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ControllerBoxQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ControllerBoxQuery) {
            return $criteria;
        }
        $query = new ControllerBoxQuery(null, null, $modelAlias);

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
     * @return   ControllerBox|ControllerBox[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ControllerBoxPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ControllerBoxPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 ControllerBox A model object, or null if the key is not found
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
     * @return                 ControllerBox A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `group`, `main_store`, `version`, `name`, `description`, `inputs`, `internal_temperature`, `uid`, `position`, `is_enabled`, `is_deleted`, `created_at`, `updated_at` FROM `controller_box` WHERE `id` = :p0';
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
            $obj = new ControllerBox();
            $obj->hydrate($row);
            ControllerBoxPeer::addInstanceToPool($obj, (string) $key);
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
     * @return ControllerBox|ControllerBox[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|ControllerBox[]|mixed the list of results, formatted by the current formatter
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
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ControllerBoxPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ControllerBoxPeer::ID, $keys, Criteria::IN);
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
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ControllerBoxPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ControllerBoxPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ControllerBoxPeer::ID, $id, $comparison);
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
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function filterByGroup($group = null, $comparison = null)
    {
        if (is_array($group)) {
            $useMinMax = false;
            if (isset($group['min'])) {
                $this->addUsingAlias(ControllerBoxPeer::GROUP, $group['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($group['max'])) {
                $this->addUsingAlias(ControllerBoxPeer::GROUP, $group['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ControllerBoxPeer::GROUP, $group, $comparison);
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
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function filterByMainStore($mainStore = null, $comparison = null)
    {
        if (is_array($mainStore)) {
            $useMinMax = false;
            if (isset($mainStore['min'])) {
                $this->addUsingAlias(ControllerBoxPeer::MAIN_STORE, $mainStore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($mainStore['max'])) {
                $this->addUsingAlias(ControllerBoxPeer::MAIN_STORE, $mainStore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ControllerBoxPeer::MAIN_STORE, $mainStore, $comparison);
    }

    /**
     * Filter the query on the version column
     *
     * Example usage:
     * <code>
     * $query->filterByVersion(1234); // WHERE version = 1234
     * $query->filterByVersion(array(12, 34)); // WHERE version IN (12, 34)
     * $query->filterByVersion(array('min' => 12)); // WHERE version >= 12
     * $query->filterByVersion(array('max' => 12)); // WHERE version <= 12
     * </code>
     *
     * @param     mixed $version The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (is_array($version)) {
            $useMinMax = false;
            if (isset($version['min'])) {
                $this->addUsingAlias(ControllerBoxPeer::VERSION, $version['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($version['max'])) {
                $this->addUsingAlias(ControllerBoxPeer::VERSION, $version['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ControllerBoxPeer::VERSION, $version, $comparison);
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
     * @return ControllerBoxQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ControllerBoxPeer::NAME, $name, $comparison);
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
     * @return ControllerBoxQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ControllerBoxPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the inputs column
     *
     * Example usage:
     * <code>
     * $query->filterByInputs(1234); // WHERE inputs = 1234
     * $query->filterByInputs(array(12, 34)); // WHERE inputs IN (12, 34)
     * $query->filterByInputs(array('min' => 12)); // WHERE inputs >= 12
     * $query->filterByInputs(array('max' => 12)); // WHERE inputs <= 12
     * </code>
     *
     * @param     mixed $inputs The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function filterByInputs($inputs = null, $comparison = null)
    {
        if (is_array($inputs)) {
            $useMinMax = false;
            if (isset($inputs['min'])) {
                $this->addUsingAlias(ControllerBoxPeer::INPUTS, $inputs['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($inputs['max'])) {
                $this->addUsingAlias(ControllerBoxPeer::INPUTS, $inputs['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ControllerBoxPeer::INPUTS, $inputs, $comparison);
    }

    /**
     * Filter the query on the internal_temperature column
     *
     * Example usage:
     * <code>
     * $query->filterByInternalTemperature('fooValue');   // WHERE internal_temperature = 'fooValue'
     * $query->filterByInternalTemperature('%fooValue%'); // WHERE internal_temperature LIKE '%fooValue%'
     * </code>
     *
     * @param     string $internalTemperature The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function filterByInternalTemperature($internalTemperature = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($internalTemperature)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $internalTemperature)) {
                $internalTemperature = str_replace('*', '%', $internalTemperature);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ControllerBoxPeer::INTERNAL_TEMPERATURE, $internalTemperature, $comparison);
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
     * @return ControllerBoxQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ControllerBoxPeer::UID, $uid, $comparison);
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
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (is_array($position)) {
            $useMinMax = false;
            if (isset($position['min'])) {
                $this->addUsingAlias(ControllerBoxPeer::POSITION, $position['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($position['max'])) {
                $this->addUsingAlias(ControllerBoxPeer::POSITION, $position['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ControllerBoxPeer::POSITION, $position, $comparison);
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
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function filterByIsEnabled($isEnabled = null, $comparison = null)
    {
        if (is_string($isEnabled)) {
            $isEnabled = in_array(strtolower($isEnabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ControllerBoxPeer::IS_ENABLED, $isEnabled, $comparison);
    }

    /**
     * Filter the query on the is_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByIsDeleted(true); // WHERE is_deleted = true
     * $query->filterByIsDeleted('yes'); // WHERE is_deleted = true
     * </code>
     *
     * @param     boolean|string $isDeleted The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function filterByIsDeleted($isDeleted = null, $comparison = null)
    {
        if (is_string($isDeleted)) {
            $isDeleted = in_array(strtolower($isDeleted), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ControllerBoxPeer::IS_DELETED, $isDeleted, $comparison);
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
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ControllerBoxPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ControllerBoxPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ControllerBoxPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ControllerBoxPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ControllerBoxPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ControllerBoxPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Store object
     *
     * @param   Store|PropelObjectCollection $store The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ControllerBoxQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStore($store, $comparison = null)
    {
        if ($store instanceof Store) {
            return $this
                ->addUsingAlias(ControllerBoxPeer::MAIN_STORE, $store->getId(), $comparison);
        } elseif ($store instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ControllerBoxPeer::MAIN_STORE, $store->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ControllerBoxQuery The current query, for fluid interface
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
     * @return                 ControllerBoxQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDeviceGroup($deviceGroup, $comparison = null)
    {
        if ($deviceGroup instanceof DeviceGroup) {
            return $this
                ->addUsingAlias(ControllerBoxPeer::GROUP, $deviceGroup->getId(), $comparison);
        } elseif ($deviceGroup instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ControllerBoxPeer::GROUP, $deviceGroup->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ControllerBoxQuery The current query, for fluid interface
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
     * Filter the query by a related DsTemperatureSensor object
     *
     * @param   DsTemperatureSensor|PropelObjectCollection $dsTemperatureSensor  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ControllerBoxQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDsTemperatureSensor($dsTemperatureSensor, $comparison = null)
    {
        if ($dsTemperatureSensor instanceof DsTemperatureSensor) {
            return $this
                ->addUsingAlias(ControllerBoxPeer::ID, $dsTemperatureSensor->getController(), $comparison);
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
     * @return ControllerBoxQuery The current query, for fluid interface
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
     * @return                 ControllerBoxQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCbInput($cbInput, $comparison = null)
    {
        if ($cbInput instanceof CbInput) {
            return $this
                ->addUsingAlias(ControllerBoxPeer::ID, $cbInput->getController(), $comparison);
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
     * @return ControllerBoxQuery The current query, for fluid interface
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
     * @param   ControllerBox $controllerBox Object to remove from the list of results
     *
     * @return ControllerBoxQuery The current query, for fluid interface
     */
    public function prune($controllerBox = null)
    {
        if ($controllerBox) {
            $this->addUsingAlias(ControllerBoxPeer::ID, $controllerBox->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ControllerBoxQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ControllerBoxPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ControllerBoxQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ControllerBoxPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ControllerBoxQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ControllerBoxPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ControllerBoxQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ControllerBoxPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     ControllerBoxQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ControllerBoxPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ControllerBoxQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ControllerBoxPeer::CREATED_AT);
    }
}
