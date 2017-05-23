<?php

namespace NotificationBundle\Model\om;

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
use NotificationBundle\Model\CbInputNotification;
use NotificationBundle\Model\CbInputNotificationPeer;
use NotificationBundle\Model\CbInputNotificationQuery;
use UserBundle\Model\User;

/**
 * @method CbInputNotificationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CbInputNotificationQuery orderBySensor($order = Criteria::ASC) Order by the sensor column
 * @method CbInputNotificationQuery orderBySwitchState($order = Criteria::ASC) Order by the switch_state column
 * @method CbInputNotificationQuery orderByReason($order = Criteria::ASC) Order by the reason column
 * @method CbInputNotificationQuery orderByIsHandled($order = Criteria::ASC) Order by the is_handled column
 * @method CbInputNotificationQuery orderByHandledBy($order = Criteria::ASC) Order by the handled_by column
 * @method CbInputNotificationQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method CbInputNotificationQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method CbInputNotificationQuery groupById() Group by the id column
 * @method CbInputNotificationQuery groupBySensor() Group by the sensor column
 * @method CbInputNotificationQuery groupBySwitchState() Group by the switch_state column
 * @method CbInputNotificationQuery groupByReason() Group by the reason column
 * @method CbInputNotificationQuery groupByIsHandled() Group by the is_handled column
 * @method CbInputNotificationQuery groupByHandledBy() Group by the handled_by column
 * @method CbInputNotificationQuery groupByCreatedAt() Group by the created_at column
 * @method CbInputNotificationQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method CbInputNotificationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CbInputNotificationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CbInputNotificationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CbInputNotificationQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method CbInputNotificationQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method CbInputNotificationQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method CbInputNotificationQuery leftJoinCbInput($relationAlias = null) Adds a LEFT JOIN clause to the query using the CbInput relation
 * @method CbInputNotificationQuery rightJoinCbInput($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CbInput relation
 * @method CbInputNotificationQuery innerJoinCbInput($relationAlias = null) Adds a INNER JOIN clause to the query using the CbInput relation
 *
 * @method CbInputNotification findOne(PropelPDO $con = null) Return the first CbInputNotification matching the query
 * @method CbInputNotification findOneOrCreate(PropelPDO $con = null) Return the first CbInputNotification matching the query, or a new CbInputNotification object populated from the query conditions when no match is found
 *
 * @method CbInputNotification findOneBySensor(int $sensor) Return the first CbInputNotification filtered by the sensor column
 * @method CbInputNotification findOneBySwitchState(boolean $switch_state) Return the first CbInputNotification filtered by the switch_state column
 * @method CbInputNotification findOneByReason(int $reason) Return the first CbInputNotification filtered by the reason column
 * @method CbInputNotification findOneByIsHandled(boolean $is_handled) Return the first CbInputNotification filtered by the is_handled column
 * @method CbInputNotification findOneByHandledBy(int $handled_by) Return the first CbInputNotification filtered by the handled_by column
 * @method CbInputNotification findOneByCreatedAt(string $created_at) Return the first CbInputNotification filtered by the created_at column
 * @method CbInputNotification findOneByUpdatedAt(string $updated_at) Return the first CbInputNotification filtered by the updated_at column
 *
 * @method array findById(int $id) Return CbInputNotification objects filtered by the id column
 * @method array findBySensor(int $sensor) Return CbInputNotification objects filtered by the sensor column
 * @method array findBySwitchState(boolean $switch_state) Return CbInputNotification objects filtered by the switch_state column
 * @method array findByReason(int $reason) Return CbInputNotification objects filtered by the reason column
 * @method array findByIsHandled(boolean $is_handled) Return CbInputNotification objects filtered by the is_handled column
 * @method array findByHandledBy(int $handled_by) Return CbInputNotification objects filtered by the handled_by column
 * @method array findByCreatedAt(string $created_at) Return CbInputNotification objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return CbInputNotification objects filtered by the updated_at column
 */
abstract class BaseCbInputNotificationQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCbInputNotificationQuery object.
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
            $modelName = 'NotificationBundle\\Model\\CbInputNotification';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CbInputNotificationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CbInputNotificationQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CbInputNotificationQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CbInputNotificationQuery) {
            return $criteria;
        }
        $query = new CbInputNotificationQuery(null, null, $modelAlias);

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
     * @return   CbInputNotification|CbInputNotification[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CbInputNotificationPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CbInputNotificationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 CbInputNotification A model object, or null if the key is not found
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
     * @return                 CbInputNotification A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `sensor`, `switch_state`, `reason`, `is_handled`, `handled_by`, `created_at`, `updated_at` FROM `cb_input_notification` WHERE `id` = :p0';
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
            $obj = new CbInputNotification();
            $obj->hydrate($row);
            CbInputNotificationPeer::addInstanceToPool($obj, (string) $key);
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
     * @return CbInputNotification|CbInputNotification[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|CbInputNotification[]|mixed the list of results, formatted by the current formatter
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
     * @return CbInputNotificationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CbInputNotificationPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CbInputNotificationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CbInputNotificationPeer::ID, $keys, Criteria::IN);
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
     * @return CbInputNotificationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CbInputNotificationPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CbInputNotificationPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputNotificationPeer::ID, $id, $comparison);
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
     * @see       filterByCbInput()
     *
     * @param     mixed $sensor The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CbInputNotificationQuery The current query, for fluid interface
     */
    public function filterBySensor($sensor = null, $comparison = null)
    {
        if (is_array($sensor)) {
            $useMinMax = false;
            if (isset($sensor['min'])) {
                $this->addUsingAlias(CbInputNotificationPeer::SENSOR, $sensor['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sensor['max'])) {
                $this->addUsingAlias(CbInputNotificationPeer::SENSOR, $sensor['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputNotificationPeer::SENSOR, $sensor, $comparison);
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
     * @return CbInputNotificationQuery The current query, for fluid interface
     */
    public function filterBySwitchState($switchState = null, $comparison = null)
    {
        if (is_string($switchState)) {
            $switchState = in_array(strtolower($switchState), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CbInputNotificationPeer::SWITCH_STATE, $switchState, $comparison);
    }

    /**
     * Filter the query on the reason column
     *
     * Example usage:
     * <code>
     * $query->filterByReason(1234); // WHERE reason = 1234
     * $query->filterByReason(array(12, 34)); // WHERE reason IN (12, 34)
     * $query->filterByReason(array('min' => 12)); // WHERE reason >= 12
     * $query->filterByReason(array('max' => 12)); // WHERE reason <= 12
     * </code>
     *
     * @param     mixed $reason The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CbInputNotificationQuery The current query, for fluid interface
     */
    public function filterByReason($reason = null, $comparison = null)
    {
        if (is_array($reason)) {
            $useMinMax = false;
            if (isset($reason['min'])) {
                $this->addUsingAlias(CbInputNotificationPeer::REASON, $reason['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($reason['max'])) {
                $this->addUsingAlias(CbInputNotificationPeer::REASON, $reason['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputNotificationPeer::REASON, $reason, $comparison);
    }

    /**
     * Filter the query on the is_handled column
     *
     * Example usage:
     * <code>
     * $query->filterByIsHandled(true); // WHERE is_handled = true
     * $query->filterByIsHandled('yes'); // WHERE is_handled = true
     * </code>
     *
     * @param     boolean|string $isHandled The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CbInputNotificationQuery The current query, for fluid interface
     */
    public function filterByIsHandled($isHandled = null, $comparison = null)
    {
        if (is_string($isHandled)) {
            $isHandled = in_array(strtolower($isHandled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CbInputNotificationPeer::IS_HANDLED, $isHandled, $comparison);
    }

    /**
     * Filter the query on the handled_by column
     *
     * Example usage:
     * <code>
     * $query->filterByHandledBy(1234); // WHERE handled_by = 1234
     * $query->filterByHandledBy(array(12, 34)); // WHERE handled_by IN (12, 34)
     * $query->filterByHandledBy(array('min' => 12)); // WHERE handled_by >= 12
     * $query->filterByHandledBy(array('max' => 12)); // WHERE handled_by <= 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $handledBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CbInputNotificationQuery The current query, for fluid interface
     */
    public function filterByHandledBy($handledBy = null, $comparison = null)
    {
        if (is_array($handledBy)) {
            $useMinMax = false;
            if (isset($handledBy['min'])) {
                $this->addUsingAlias(CbInputNotificationPeer::HANDLED_BY, $handledBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($handledBy['max'])) {
                $this->addUsingAlias(CbInputNotificationPeer::HANDLED_BY, $handledBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputNotificationPeer::HANDLED_BY, $handledBy, $comparison);
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
     * @return CbInputNotificationQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CbInputNotificationPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CbInputNotificationPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputNotificationPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return CbInputNotificationQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CbInputNotificationPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CbInputNotificationPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CbInputNotificationPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CbInputNotificationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(CbInputNotificationPeer::HANDLED_BY, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CbInputNotificationPeer::HANDLED_BY, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return CbInputNotificationQuery The current query, for fluid interface
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
     * Filter the query by a related CbInput object
     *
     * @param   CbInput|PropelObjectCollection $cbInput The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CbInputNotificationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCbInput($cbInput, $comparison = null)
    {
        if ($cbInput instanceof CbInput) {
            return $this
                ->addUsingAlias(CbInputNotificationPeer::SENSOR, $cbInput->getId(), $comparison);
        } elseif ($cbInput instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CbInputNotificationPeer::SENSOR, $cbInput->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return CbInputNotificationQuery The current query, for fluid interface
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
     * @param   CbInputNotification $cbInputNotification Object to remove from the list of results
     *
     * @return CbInputNotificationQuery The current query, for fluid interface
     */
    public function prune($cbInputNotification = null)
    {
        if ($cbInputNotification) {
            $this->addUsingAlias(CbInputNotificationPeer::ID, $cbInputNotification->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     CbInputNotificationQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CbInputNotificationPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     CbInputNotificationQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CbInputNotificationPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     CbInputNotificationQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CbInputNotificationPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     CbInputNotificationQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CbInputNotificationPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     CbInputNotificationQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CbInputNotificationPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     CbInputNotificationQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CbInputNotificationPeer::CREATED_AT);
    }
}
