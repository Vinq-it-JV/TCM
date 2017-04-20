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
use DeviceBundle\Model\CbInputPeer;
use DeviceBundle\Model\CbInputQuery;
use DeviceBundle\Model\ControllerBox;
use DeviceBundle\Model\DeviceGroup;
use StoreBundle\Model\Store;

/**
 * @method CbInputQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CbInputQuery orderByUid($order = Criteria::ASC) Order by the uid column
 * @method CbInputQuery orderByInputNumber($order = Criteria::ASC) Order by the input_number column
 * @method CbInputQuery orderByGroup($order = Criteria::ASC) Order by the group column
 * @method CbInputQuery orderByController($order = Criteria::ASC) Order by the controller column
 * @method CbInputQuery orderByMainStore($order = Criteria::ASC) Order by the main_store column
 * @method CbInputQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method CbInputQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method CbInputQuery orderByState($order = Criteria::ASC) Order by the state column
 * @method CbInputQuery orderBySwitchWhen($order = Criteria::ASC) Order by the switch_when column
 * @method CbInputQuery orderBySwitchState($order = Criteria::ASC) Order by the switch_state column
 * @method CbInputQuery orderByPosition($order = Criteria::ASC) Order by the position column
 * @method CbInputQuery orderByIsEnabled($order = Criteria::ASC) Order by the is_enabled column
 * @method CbInputQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method CbInputQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method CbInputQuery groupById() Group by the id column
 * @method CbInputQuery groupByUid() Group by the uid column
 * @method CbInputQuery groupByInputNumber() Group by the input_number column
 * @method CbInputQuery groupByGroup() Group by the group column
 * @method CbInputQuery groupByController() Group by the controller column
 * @method CbInputQuery groupByMainStore() Group by the main_store column
 * @method CbInputQuery groupByName() Group by the name column
 * @method CbInputQuery groupByDescription() Group by the description column
 * @method CbInputQuery groupByState() Group by the state column
 * @method CbInputQuery groupBySwitchWhen() Group by the switch_when column
 * @method CbInputQuery groupBySwitchState() Group by the switch_state column
 * @method CbInputQuery groupByPosition() Group by the position column
 * @method CbInputQuery groupByIsEnabled() Group by the is_enabled column
 * @method CbInputQuery groupByCreatedAt() Group by the created_at column
 * @method CbInputQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method CbInputQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CbInputQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CbInputQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CbInputQuery leftJoinStore($relationAlias = null) Adds a LEFT JOIN clause to the query using the Store relation
 * @method CbInputQuery rightJoinStore($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Store relation
 * @method CbInputQuery innerJoinStore($relationAlias = null) Adds a INNER JOIN clause to the query using the Store relation
 *
 * @method CbInputQuery leftJoinControllerBox($relationAlias = null) Adds a LEFT JOIN clause to the query using the ControllerBox relation
 * @method CbInputQuery rightJoinControllerBox($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ControllerBox relation
 * @method CbInputQuery innerJoinControllerBox($relationAlias = null) Adds a INNER JOIN clause to the query using the ControllerBox relation
 *
 * @method CbInputQuery leftJoinDeviceGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the DeviceGroup relation
 * @method CbInputQuery rightJoinDeviceGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DeviceGroup relation
 * @method CbInputQuery innerJoinDeviceGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the DeviceGroup relation
 *
 * @method CbInput findOne(PropelPDO $con = null) Return the first CbInput matching the query
 * @method CbInput findOneOrCreate(PropelPDO $con = null) Return the first CbInput matching the query, or a new CbInput object populated from the query conditions when no match is found
 *
 * @method CbInput findOneByUid(string $uid) Return the first CbInput filtered by the uid column
 * @method CbInput findOneByInputNumber(int $input_number) Return the first CbInput filtered by the input_number column
 * @method CbInput findOneByGroup(int $group) Return the first CbInput filtered by the group column
 * @method CbInput findOneByController(int $controller) Return the first CbInput filtered by the controller column
 * @method CbInput findOneByMainStore(int $main_store) Return the first CbInput filtered by the main_store column
 * @method CbInput findOneByName(string $name) Return the first CbInput filtered by the name column
 * @method CbInput findOneByDescription(string $description) Return the first CbInput filtered by the description column
 * @method CbInput findOneByState(int $state) Return the first CbInput filtered by the state column
 * @method CbInput findOneBySwitchWhen(boolean $switch_when) Return the first CbInput filtered by the switch_when column
 * @method CbInput findOneBySwitchState(boolean $switch_state) Return the first CbInput filtered by the switch_state column
 * @method CbInput findOneByPosition(int $position) Return the first CbInput filtered by the position column
 * @method CbInput findOneByIsEnabled(boolean $is_enabled) Return the first CbInput filtered by the is_enabled column
 * @method CbInput findOneByCreatedAt(string $created_at) Return the first CbInput filtered by the created_at column
 * @method CbInput findOneByUpdatedAt(string $updated_at) Return the first CbInput filtered by the updated_at column
 *
 * @method array findById(int $id) Return CbInput objects filtered by the id column
 * @method array findByUid(string $uid) Return CbInput objects filtered by the uid column
 * @method array findByInputNumber(int $input_number) Return CbInput objects filtered by the input_number column
 * @method array findByGroup(int $group) Return CbInput objects filtered by the group column
 * @method array findByController(int $controller) Return CbInput objects filtered by the controller column
 * @method array findByMainStore(int $main_store) Return CbInput objects filtered by the main_store column
 * @method array findByName(string $name) Return CbInput objects filtered by the name column
 * @method array findByDescription(string $description) Return CbInput objects filtered by the description column
 * @method array findByState(int $state) Return CbInput objects filtered by the state column
 * @method array findBySwitchWhen(boolean $switch_when) Return CbInput objects filtered by the switch_when column
 * @method array findBySwitchState(boolean $switch_state) Return CbInput objects filtered by the switch_state column
 * @method array findByPosition(int $position) Return CbInput objects filtered by the position column
 * @method array findByIsEnabled(boolean $is_enabled) Return CbInput objects filtered by the is_enabled column
 * @method array findByCreatedAt(string $created_at) Return CbInput objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return CbInput objects filtered by the updated_at column
 */
abstract class BaseCbInputQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCbInputQuery object.
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
            $modelName = 'DeviceBundle\\Model\\CbInput';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CbInputQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CbInputQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CbInputQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CbInputQuery) {
            return $criteria;
        }
        $query = new CbInputQuery(null, null, $modelAlias);

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
     * @return   CbInput|CbInput[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CbInputPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CbInputPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 CbInput A model object, or null if the key is not found
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
     * @return                 CbInput A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uid`, `input_number`, `group`, `controller`, `main_store`, `name`, `description`, `state`, `switch_when`, `switch_state`, `position`, `is_enabled`, `created_at`, `updated_at` FROM `cb_input` WHERE `id` = :p0';
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
            $obj = new CbInput();
            $obj->hydrate($row);
            CbInputPeer::addInstanceToPool($obj, (string) $key);
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
     * @return CbInput|CbInput[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|CbInput[]|mixed the list of results, formatted by the current formatter
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
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CbInputPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CbInputPeer::ID, $keys, Criteria::IN);
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
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CbInputPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CbInputPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputPeer::ID, $id, $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CbInputPeer::UID, $uid, $comparison);
    }

    /**
     * Filter the query on the input_number column
     *
     * Example usage:
     * <code>
     * $query->filterByInputNumber(1234); // WHERE input_number = 1234
     * $query->filterByInputNumber(array(12, 34)); // WHERE input_number IN (12, 34)
     * $query->filterByInputNumber(array('min' => 12)); // WHERE input_number >= 12
     * $query->filterByInputNumber(array('max' => 12)); // WHERE input_number <= 12
     * </code>
     *
     * @param     mixed $inputNumber The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterByInputNumber($inputNumber = null, $comparison = null)
    {
        if (is_array($inputNumber)) {
            $useMinMax = false;
            if (isset($inputNumber['min'])) {
                $this->addUsingAlias(CbInputPeer::INPUT_NUMBER, $inputNumber['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($inputNumber['max'])) {
                $this->addUsingAlias(CbInputPeer::INPUT_NUMBER, $inputNumber['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputPeer::INPUT_NUMBER, $inputNumber, $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterByGroup($group = null, $comparison = null)
    {
        if (is_array($group)) {
            $useMinMax = false;
            if (isset($group['min'])) {
                $this->addUsingAlias(CbInputPeer::GROUP, $group['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($group['max'])) {
                $this->addUsingAlias(CbInputPeer::GROUP, $group['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputPeer::GROUP, $group, $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterByController($controller = null, $comparison = null)
    {
        if (is_array($controller)) {
            $useMinMax = false;
            if (isset($controller['min'])) {
                $this->addUsingAlias(CbInputPeer::CONTROLLER, $controller['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($controller['max'])) {
                $this->addUsingAlias(CbInputPeer::CONTROLLER, $controller['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputPeer::CONTROLLER, $controller, $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterByMainStore($mainStore = null, $comparison = null)
    {
        if (is_array($mainStore)) {
            $useMinMax = false;
            if (isset($mainStore['min'])) {
                $this->addUsingAlias(CbInputPeer::MAIN_STORE, $mainStore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($mainStore['max'])) {
                $this->addUsingAlias(CbInputPeer::MAIN_STORE, $mainStore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputPeer::MAIN_STORE, $mainStore, $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CbInputPeer::NAME, $name, $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CbInputPeer::DESCRIPTION, $description, $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterByState($state = null, $comparison = null)
    {
        if (is_array($state)) {
            $useMinMax = false;
            if (isset($state['min'])) {
                $this->addUsingAlias(CbInputPeer::STATE, $state['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($state['max'])) {
                $this->addUsingAlias(CbInputPeer::STATE, $state['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputPeer::STATE, $state, $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterBySwitchWhen($switchWhen = null, $comparison = null)
    {
        if (is_string($switchWhen)) {
            $switchWhen = in_array(strtolower($switchWhen), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CbInputPeer::SWITCH_WHEN, $switchWhen, $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterBySwitchState($switchState = null, $comparison = null)
    {
        if (is_string($switchState)) {
            $switchState = in_array(strtolower($switchState), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CbInputPeer::SWITCH_STATE, $switchState, $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (is_array($position)) {
            $useMinMax = false;
            if (isset($position['min'])) {
                $this->addUsingAlias(CbInputPeer::POSITION, $position['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($position['max'])) {
                $this->addUsingAlias(CbInputPeer::POSITION, $position['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputPeer::POSITION, $position, $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterByIsEnabled($isEnabled = null, $comparison = null)
    {
        if (is_string($isEnabled)) {
            $isEnabled = in_array(strtolower($isEnabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CbInputPeer::IS_ENABLED, $isEnabled, $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CbInputPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CbInputPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CbInputPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CbInputPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Store object
     *
     * @param   Store|PropelObjectCollection $store The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CbInputQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStore($store, $comparison = null)
    {
        if ($store instanceof Store) {
            return $this
                ->addUsingAlias(CbInputPeer::MAIN_STORE, $store->getId(), $comparison);
        } elseif ($store instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CbInputPeer::MAIN_STORE, $store->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
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
     * @param   ControllerBox|PropelObjectCollection $controllerBox The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CbInputQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByControllerBox($controllerBox, $comparison = null)
    {
        if ($controllerBox instanceof ControllerBox) {
            return $this
                ->addUsingAlias(CbInputPeer::CONTROLLER, $controllerBox->getId(), $comparison);
        } elseif ($controllerBox instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CbInputPeer::CONTROLLER, $controllerBox->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
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
     * Filter the query by a related DeviceGroup object
     *
     * @param   DeviceGroup|PropelObjectCollection $deviceGroup The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CbInputQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDeviceGroup($deviceGroup, $comparison = null)
    {
        if ($deviceGroup instanceof DeviceGroup) {
            return $this
                ->addUsingAlias(CbInputPeer::GROUP, $deviceGroup->getId(), $comparison);
        } elseif ($deviceGroup instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CbInputPeer::GROUP, $deviceGroup->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return CbInputQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   CbInput $cbInput Object to remove from the list of results
     *
     * @return CbInputQuery The current query, for fluid interface
     */
    public function prune($cbInput = null)
    {
        if ($cbInput) {
            $this->addUsingAlias(CbInputPeer::ID, $cbInput->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     CbInputQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CbInputPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     CbInputQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CbInputPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     CbInputQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CbInputPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     CbInputQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CbInputPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     CbInputQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CbInputPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     CbInputQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CbInputPeer::CREATED_AT);
    }
}
