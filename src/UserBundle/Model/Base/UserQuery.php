<?php

namespace UserBundle\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use UserBundle\Model\User as ChildUser;
use UserBundle\Model\UserQuery as ChildUserQuery;
use UserBundle\Model\Map\UserTableMap;

/**
 * Base class that represents a query for the 'user' table.
 *
 *
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildUserQuery orderByFirstname($order = Criteria::ASC) Order by the firstname column
 * @method     ChildUserQuery orderByLastname($order = Criteria::ASC) Order by the lastname column
 * @method     ChildUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildUserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildUserQuery orderBySecret($order = Criteria::ASC) Order by the secret column
 * @method     ChildUserQuery orderByLogins($order = Criteria::ASC) Order by the logins column
 * @method     ChildUserQuery orderByLanguage($order = Criteria::ASC) Order by the language column
 * @method     ChildUserQuery orderByIsEnabled($order = Criteria::ASC) Order by the is_enabled column
 * @method     ChildUserQuery orderByIsExternal($order = Criteria::ASC) Order by the is_external column
 * @method     ChildUserQuery orderByIsLocked($order = Criteria::ASC) Order by the is_locked column
 * @method     ChildUserQuery orderByIsExpired($order = Criteria::ASC) Order by the is_expired column
 * @method     ChildUserQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildUserQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupByUsername() Group by the username column
 * @method     ChildUserQuery groupByFirstname() Group by the firstname column
 * @method     ChildUserQuery groupByLastname() Group by the lastname column
 * @method     ChildUserQuery groupByEmail() Group by the email column
 * @method     ChildUserQuery groupByPassword() Group by the password column
 * @method     ChildUserQuery groupBySecret() Group by the secret column
 * @method     ChildUserQuery groupByLogins() Group by the logins column
 * @method     ChildUserQuery groupByLanguage() Group by the language column
 * @method     ChildUserQuery groupByIsEnabled() Group by the is_enabled column
 * @method     ChildUserQuery groupByIsExternal() Group by the is_external column
 * @method     ChildUserQuery groupByIsLocked() Group by the is_locked column
 * @method     ChildUserQuery groupByIsExpired() Group by the is_expired column
 * @method     ChildUserQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildUserQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserQuery leftJoinUserRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRole relation
 * @method     ChildUserQuery rightJoinUserRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRole relation
 * @method     ChildUserQuery innerJoinUserRole($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRole relation
 *
 * @method     ChildUserQuery joinWithUserRole($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserRole relation
 *
 * @method     ChildUserQuery leftJoinWithUserRole() Adds a LEFT JOIN clause and with to the query using the UserRole relation
 * @method     ChildUserQuery rightJoinWithUserRole() Adds a RIGHT JOIN clause and with to the query using the UserRole relation
 * @method     ChildUserQuery innerJoinWithUserRole() Adds a INNER JOIN clause and with to the query using the UserRole relation
 *
 * @method     \UserBundle\Model\UserRoleQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser findOneByUsername(string $username) Return the first ChildUser filtered by the username column
 * @method     ChildUser findOneByFirstname(string $firstname) Return the first ChildUser filtered by the firstname column
 * @method     ChildUser findOneByLastname(string $lastname) Return the first ChildUser filtered by the lastname column
 * @method     ChildUser findOneByEmail(string $email) Return the first ChildUser filtered by the email column
 * @method     ChildUser findOneByPassword(string $password) Return the first ChildUser filtered by the password column
 * @method     ChildUser findOneBySecret(string $secret) Return the first ChildUser filtered by the secret column
 * @method     ChildUser findOneByLogins(int $logins) Return the first ChildUser filtered by the logins column
 * @method     ChildUser findOneByLanguage(string $language) Return the first ChildUser filtered by the language column
 * @method     ChildUser findOneByIsEnabled(boolean $is_enabled) Return the first ChildUser filtered by the is_enabled column
 * @method     ChildUser findOneByIsExternal(boolean $is_external) Return the first ChildUser filtered by the is_external column
 * @method     ChildUser findOneByIsLocked(boolean $is_locked) Return the first ChildUser filtered by the is_locked column
 * @method     ChildUser findOneByIsExpired(boolean $is_expired) Return the first ChildUser filtered by the is_expired column
 * @method     ChildUser findOneByCreatedAt(string $created_at) Return the first ChildUser filtered by the created_at column
 * @method     ChildUser findOneByUpdatedAt(string $updated_at) Return the first ChildUser filtered by the updated_at column *

 * @method     ChildUser requirePk($key, ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneById(int $id) Return the first ChildUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUsername(string $username) Return the first ChildUser filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByFirstname(string $firstname) Return the first ChildUser filtered by the firstname column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByLastname(string $lastname) Return the first ChildUser filtered by the lastname column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmail(string $email) Return the first ChildUser filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPassword(string $password) Return the first ChildUser filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneBySecret(string $secret) Return the first ChildUser filtered by the secret column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByLogins(int $logins) Return the first ChildUser filtered by the logins column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByLanguage(string $language) Return the first ChildUser filtered by the language column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByIsEnabled(boolean $is_enabled) Return the first ChildUser filtered by the is_enabled column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByIsExternal(boolean $is_external) Return the first ChildUser filtered by the is_external column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByIsLocked(boolean $is_locked) Return the first ChildUser filtered by the is_locked column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByIsExpired(boolean $is_expired) Return the first ChildUser filtered by the is_expired column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCreatedAt(string $created_at) Return the first ChildUser filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUpdatedAt(string $updated_at) Return the first ChildUser filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method     ChildUser[]|ObjectCollection findById(int $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|ObjectCollection findByUsername(string $username) Return ChildUser objects filtered by the username column
 * @method     ChildUser[]|ObjectCollection findByFirstname(string $firstname) Return ChildUser objects filtered by the firstname column
 * @method     ChildUser[]|ObjectCollection findByLastname(string $lastname) Return ChildUser objects filtered by the lastname column
 * @method     ChildUser[]|ObjectCollection findByEmail(string $email) Return ChildUser objects filtered by the email column
 * @method     ChildUser[]|ObjectCollection findByPassword(string $password) Return ChildUser objects filtered by the password column
 * @method     ChildUser[]|ObjectCollection findBySecret(string $secret) Return ChildUser objects filtered by the secret column
 * @method     ChildUser[]|ObjectCollection findByLogins(int $logins) Return ChildUser objects filtered by the logins column
 * @method     ChildUser[]|ObjectCollection findByLanguage(string $language) Return ChildUser objects filtered by the language column
 * @method     ChildUser[]|ObjectCollection findByIsEnabled(boolean $is_enabled) Return ChildUser objects filtered by the is_enabled column
 * @method     ChildUser[]|ObjectCollection findByIsExternal(boolean $is_external) Return ChildUser objects filtered by the is_external column
 * @method     ChildUser[]|ObjectCollection findByIsLocked(boolean $is_locked) Return ChildUser objects filtered by the is_locked column
 * @method     ChildUser[]|ObjectCollection findByIsExpired(boolean $is_expired) Return ChildUser objects filtered by the is_expired column
 * @method     ChildUser[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildUser objects filtered by the created_at column
 * @method     ChildUser[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildUser objects filtered by the updated_at column
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \UserBundle\Model\Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\UserBundle\\Model\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
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
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, username, firstname, lastname, email, password, secret, logins, language, is_enabled, is_external, is_locked, is_expired, created_at, updated_at FROM user WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%', Criteria::LIKE); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the firstname column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstname('fooValue');   // WHERE firstname = 'fooValue'
     * $query->filterByFirstname('%fooValue%', Criteria::LIKE); // WHERE firstname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByFirstname($firstname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_FIRSTNAME, $firstname, $comparison);
    }

    /**
     * Filter the query on the lastname column
     *
     * Example usage:
     * <code>
     * $query->filterByLastname('fooValue');   // WHERE lastname = 'fooValue'
     * $query->filterByLastname('%fooValue%', Criteria::LIKE); // WHERE lastname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByLastname($lastname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_LASTNAME, $lastname, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%', Criteria::LIKE); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the secret column
     *
     * Example usage:
     * <code>
     * $query->filterBySecret('fooValue');   // WHERE secret = 'fooValue'
     * $query->filterBySecret('%fooValue%', Criteria::LIKE); // WHERE secret LIKE '%fooValue%'
     * </code>
     *
     * @param     string $secret The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterBySecret($secret = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($secret)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_SECRET, $secret, $comparison);
    }

    /**
     * Filter the query on the logins column
     *
     * Example usage:
     * <code>
     * $query->filterByLogins(1234); // WHERE logins = 1234
     * $query->filterByLogins(array(12, 34)); // WHERE logins IN (12, 34)
     * $query->filterByLogins(array('min' => 12)); // WHERE logins > 12
     * </code>
     *
     * @param     mixed $logins The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByLogins($logins = null, $comparison = null)
    {
        if (is_array($logins)) {
            $useMinMax = false;
            if (isset($logins['min'])) {
                $this->addUsingAlias(UserTableMap::COL_LOGINS, $logins['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($logins['max'])) {
                $this->addUsingAlias(UserTableMap::COL_LOGINS, $logins['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_LOGINS, $logins, $comparison);
    }

    /**
     * Filter the query on the language column
     *
     * Example usage:
     * <code>
     * $query->filterByLanguage('fooValue');   // WHERE language = 'fooValue'
     * $query->filterByLanguage('%fooValue%', Criteria::LIKE); // WHERE language LIKE '%fooValue%'
     * </code>
     *
     * @param     string $language The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByLanguage($language = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($language)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_LANGUAGE, $language, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByIsEnabled($isEnabled = null, $comparison = null)
    {
        if (is_string($isEnabled)) {
            $isEnabled = in_array(strtolower($isEnabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserTableMap::COL_IS_ENABLED, $isEnabled, $comparison);
    }

    /**
     * Filter the query on the is_external column
     *
     * Example usage:
     * <code>
     * $query->filterByIsExternal(true); // WHERE is_external = true
     * $query->filterByIsExternal('yes'); // WHERE is_external = true
     * </code>
     *
     * @param     boolean|string $isExternal The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByIsExternal($isExternal = null, $comparison = null)
    {
        if (is_string($isExternal)) {
            $isExternal = in_array(strtolower($isExternal), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserTableMap::COL_IS_EXTERNAL, $isExternal, $comparison);
    }

    /**
     * Filter the query on the is_locked column
     *
     * Example usage:
     * <code>
     * $query->filterByIsLocked(true); // WHERE is_locked = true
     * $query->filterByIsLocked('yes'); // WHERE is_locked = true
     * </code>
     *
     * @param     boolean|string $isLocked The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByIsLocked($isLocked = null, $comparison = null)
    {
        if (is_string($isLocked)) {
            $isLocked = in_array(strtolower($isLocked), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserTableMap::COL_IS_LOCKED, $isLocked, $comparison);
    }

    /**
     * Filter the query on the is_expired column
     *
     * Example usage:
     * <code>
     * $query->filterByIsExpired(true); // WHERE is_expired = true
     * $query->filterByIsExpired('yes'); // WHERE is_expired = true
     * </code>
     *
     * @param     boolean|string $isExpired The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByIsExpired($isExpired = null, $comparison = null)
    {
        if (is_string($isExpired)) {
            $isExpired = in_array(strtolower($isExpired), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserTableMap::COL_IS_EXPIRED, $isExpired, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \UserBundle\Model\UserRole object
     *
     * @param \UserBundle\Model\UserRole|ObjectCollection $userRole the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByUserRole($userRole, $comparison = null)
    {
        if ($userRole instanceof \UserBundle\Model\UserRole) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userRole->getUserId(), $comparison);
        } elseif ($userRole instanceof ObjectCollection) {
            return $this
                ->useUserRoleQuery()
                ->filterByPrimaryKeys($userRole->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserRole() only accepts arguments of type \UserBundle\Model\UserRole or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRole relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinUserRole($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRole');

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
            $this->addJoinObject($join, 'UserRole');
        }

        return $this;
    }

    /**
     * Use the UserRole relation UserRole object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserBundle\Model\UserRoleQuery A secondary query class using the current class as primary query
     */
    public function useUserRoleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserRole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRole', '\UserBundle\Model\UserRoleQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTableMap::COL_CREATED_AT);
    }

} // UserQuery
