<?php

namespace UserBundle\Model\om;

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
use CompanyBundle\Model\Company;
use CompanyBundle\Model\CompanyContact;
use CompanyBundle\Model\CompanyInformant;
use CompanyBundle\Model\CompanyOwner;
use NotificationBundle\Model\CbInputNotification;
use NotificationBundle\Model\DsTemperatureNotification;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreContact;
use StoreBundle\Model\StoreInformant;
use StoreBundle\Model\StoreOwner;
use UserBundle\Model\Address;
use UserBundle\Model\Countries;
use UserBundle\Model\Email;
use UserBundle\Model\Phone;
use UserBundle\Model\Role;
use UserBundle\Model\User;
use UserBundle\Model\UserAddress;
use UserBundle\Model\UserEmail;
use UserBundle\Model\UserGender;
use UserBundle\Model\UserPeer;
use UserBundle\Model\UserPhone;
use UserBundle\Model\UserQuery;
use UserBundle\Model\UserRole;
use UserBundle\Model\UserTitle;

/**
 * @method UserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method UserQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method UserQuery orderByFirstname($order = Criteria::ASC) Order by the firstname column
 * @method UserQuery orderByMiddlename($order = Criteria::ASC) Order by the middlename column
 * @method UserQuery orderByLastname($order = Criteria::ASC) Order by the lastname column
 * @method UserQuery orderByGender($order = Criteria::ASC) Order by the gender column
 * @method UserQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method UserQuery orderByBirthDate($order = Criteria::ASC) Order by the birth_date column
 * @method UserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method UserQuery orderBySecret($order = Criteria::ASC) Order by the secret column
 * @method UserQuery orderByLogins($order = Criteria::ASC) Order by the logins column
 * @method UserQuery orderByCountry($order = Criteria::ASC) Order by the country column
 * @method UserQuery orderByLanguage($order = Criteria::ASC) Order by the language column
 * @method UserQuery orderByIsEnabled($order = Criteria::ASC) Order by the is_enabled column
 * @method UserQuery orderByIsExternal($order = Criteria::ASC) Order by the is_external column
 * @method UserQuery orderByIsLocked($order = Criteria::ASC) Order by the is_locked column
 * @method UserQuery orderByIsExpired($order = Criteria::ASC) Order by the is_expired column
 * @method UserQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method UserQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method UserQuery groupById() Group by the id column
 * @method UserQuery groupByUsername() Group by the username column
 * @method UserQuery groupByFirstname() Group by the firstname column
 * @method UserQuery groupByMiddlename() Group by the middlename column
 * @method UserQuery groupByLastname() Group by the lastname column
 * @method UserQuery groupByGender() Group by the gender column
 * @method UserQuery groupByTitle() Group by the title column
 * @method UserQuery groupByBirthDate() Group by the birth_date column
 * @method UserQuery groupByPassword() Group by the password column
 * @method UserQuery groupBySecret() Group by the secret column
 * @method UserQuery groupByLogins() Group by the logins column
 * @method UserQuery groupByCountry() Group by the country column
 * @method UserQuery groupByLanguage() Group by the language column
 * @method UserQuery groupByIsEnabled() Group by the is_enabled column
 * @method UserQuery groupByIsExternal() Group by the is_external column
 * @method UserQuery groupByIsLocked() Group by the is_locked column
 * @method UserQuery groupByIsExpired() Group by the is_expired column
 * @method UserQuery groupByCreatedAt() Group by the created_at column
 * @method UserQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method UserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserQuery leftJoinUserGender($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserGender relation
 * @method UserQuery rightJoinUserGender($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserGender relation
 * @method UserQuery innerJoinUserGender($relationAlias = null) Adds a INNER JOIN clause to the query using the UserGender relation
 *
 * @method UserQuery leftJoinUserTitle($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserTitle relation
 * @method UserQuery rightJoinUserTitle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserTitle relation
 * @method UserQuery innerJoinUserTitle($relationAlias = null) Adds a INNER JOIN clause to the query using the UserTitle relation
 *
 * @method UserQuery leftJoinCountriesRelatedByCountry($relationAlias = null) Adds a LEFT JOIN clause to the query using the CountriesRelatedByCountry relation
 * @method UserQuery rightJoinCountriesRelatedByCountry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CountriesRelatedByCountry relation
 * @method UserQuery innerJoinCountriesRelatedByCountry($relationAlias = null) Adds a INNER JOIN clause to the query using the CountriesRelatedByCountry relation
 *
 * @method UserQuery leftJoinCountriesRelatedByLanguage($relationAlias = null) Adds a LEFT JOIN clause to the query using the CountriesRelatedByLanguage relation
 * @method UserQuery rightJoinCountriesRelatedByLanguage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CountriesRelatedByLanguage relation
 * @method UserQuery innerJoinCountriesRelatedByLanguage($relationAlias = null) Adds a INNER JOIN clause to the query using the CountriesRelatedByLanguage relation
 *
 * @method UserQuery leftJoinCompanyContact($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompanyContact relation
 * @method UserQuery rightJoinCompanyContact($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompanyContact relation
 * @method UserQuery innerJoinCompanyContact($relationAlias = null) Adds a INNER JOIN clause to the query using the CompanyContact relation
 *
 * @method UserQuery leftJoinCompanyInformant($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompanyInformant relation
 * @method UserQuery rightJoinCompanyInformant($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompanyInformant relation
 * @method UserQuery innerJoinCompanyInformant($relationAlias = null) Adds a INNER JOIN clause to the query using the CompanyInformant relation
 *
 * @method UserQuery leftJoinCompanyOwner($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompanyOwner relation
 * @method UserQuery rightJoinCompanyOwner($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompanyOwner relation
 * @method UserQuery innerJoinCompanyOwner($relationAlias = null) Adds a INNER JOIN clause to the query using the CompanyOwner relation
 *
 * @method UserQuery leftJoinDsTemperatureNotification($relationAlias = null) Adds a LEFT JOIN clause to the query using the DsTemperatureNotification relation
 * @method UserQuery rightJoinDsTemperatureNotification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DsTemperatureNotification relation
 * @method UserQuery innerJoinDsTemperatureNotification($relationAlias = null) Adds a INNER JOIN clause to the query using the DsTemperatureNotification relation
 *
 * @method UserQuery leftJoinCbInputNotification($relationAlias = null) Adds a LEFT JOIN clause to the query using the CbInputNotification relation
 * @method UserQuery rightJoinCbInputNotification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CbInputNotification relation
 * @method UserQuery innerJoinCbInputNotification($relationAlias = null) Adds a INNER JOIN clause to the query using the CbInputNotification relation
 *
 * @method UserQuery leftJoinStoreContact($relationAlias = null) Adds a LEFT JOIN clause to the query using the StoreContact relation
 * @method UserQuery rightJoinStoreContact($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StoreContact relation
 * @method UserQuery innerJoinStoreContact($relationAlias = null) Adds a INNER JOIN clause to the query using the StoreContact relation
 *
 * @method UserQuery leftJoinStoreInformant($relationAlias = null) Adds a LEFT JOIN clause to the query using the StoreInformant relation
 * @method UserQuery rightJoinStoreInformant($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StoreInformant relation
 * @method UserQuery innerJoinStoreInformant($relationAlias = null) Adds a INNER JOIN clause to the query using the StoreInformant relation
 *
 * @method UserQuery leftJoinStoreOwner($relationAlias = null) Adds a LEFT JOIN clause to the query using the StoreOwner relation
 * @method UserQuery rightJoinStoreOwner($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StoreOwner relation
 * @method UserQuery innerJoinStoreOwner($relationAlias = null) Adds a INNER JOIN clause to the query using the StoreOwner relation
 *
 * @method UserQuery leftJoinUserRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRole relation
 * @method UserQuery rightJoinUserRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRole relation
 * @method UserQuery innerJoinUserRole($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRole relation
 *
 * @method UserQuery leftJoinUserAddress($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserAddress relation
 * @method UserQuery rightJoinUserAddress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserAddress relation
 * @method UserQuery innerJoinUserAddress($relationAlias = null) Adds a INNER JOIN clause to the query using the UserAddress relation
 *
 * @method UserQuery leftJoinUserEmail($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserEmail relation
 * @method UserQuery rightJoinUserEmail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserEmail relation
 * @method UserQuery innerJoinUserEmail($relationAlias = null) Adds a INNER JOIN clause to the query using the UserEmail relation
 *
 * @method UserQuery leftJoinUserPhone($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserPhone relation
 * @method UserQuery rightJoinUserPhone($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserPhone relation
 * @method UserQuery innerJoinUserPhone($relationAlias = null) Adds a INNER JOIN clause to the query using the UserPhone relation
 *
 * @method User findOne(PropelPDO $con = null) Return the first User matching the query
 * @method User findOneOrCreate(PropelPDO $con = null) Return the first User matching the query, or a new User object populated from the query conditions when no match is found
 *
 * @method User findOneByUsername(string $username) Return the first User filtered by the username column
 * @method User findOneByFirstname(string $firstname) Return the first User filtered by the firstname column
 * @method User findOneByMiddlename(string $middlename) Return the first User filtered by the middlename column
 * @method User findOneByLastname(string $lastname) Return the first User filtered by the lastname column
 * @method User findOneByGender(int $gender) Return the first User filtered by the gender column
 * @method User findOneByTitle(int $title) Return the first User filtered by the title column
 * @method User findOneByBirthDate(string $birth_date) Return the first User filtered by the birth_date column
 * @method User findOneByPassword(string $password) Return the first User filtered by the password column
 * @method User findOneBySecret(string $secret) Return the first User filtered by the secret column
 * @method User findOneByLogins(int $logins) Return the first User filtered by the logins column
 * @method User findOneByCountry(int $country) Return the first User filtered by the country column
 * @method User findOneByLanguage(int $language) Return the first User filtered by the language column
 * @method User findOneByIsEnabled(boolean $is_enabled) Return the first User filtered by the is_enabled column
 * @method User findOneByIsExternal(boolean $is_external) Return the first User filtered by the is_external column
 * @method User findOneByIsLocked(boolean $is_locked) Return the first User filtered by the is_locked column
 * @method User findOneByIsExpired(boolean $is_expired) Return the first User filtered by the is_expired column
 * @method User findOneByCreatedAt(string $created_at) Return the first User filtered by the created_at column
 * @method User findOneByUpdatedAt(string $updated_at) Return the first User filtered by the updated_at column
 *
 * @method array findById(int $id) Return User objects filtered by the id column
 * @method array findByUsername(string $username) Return User objects filtered by the username column
 * @method array findByFirstname(string $firstname) Return User objects filtered by the firstname column
 * @method array findByMiddlename(string $middlename) Return User objects filtered by the middlename column
 * @method array findByLastname(string $lastname) Return User objects filtered by the lastname column
 * @method array findByGender(int $gender) Return User objects filtered by the gender column
 * @method array findByTitle(int $title) Return User objects filtered by the title column
 * @method array findByBirthDate(string $birth_date) Return User objects filtered by the birth_date column
 * @method array findByPassword(string $password) Return User objects filtered by the password column
 * @method array findBySecret(string $secret) Return User objects filtered by the secret column
 * @method array findByLogins(int $logins) Return User objects filtered by the logins column
 * @method array findByCountry(int $country) Return User objects filtered by the country column
 * @method array findByLanguage(int $language) Return User objects filtered by the language column
 * @method array findByIsEnabled(boolean $is_enabled) Return User objects filtered by the is_enabled column
 * @method array findByIsExternal(boolean $is_external) Return User objects filtered by the is_external column
 * @method array findByIsLocked(boolean $is_locked) Return User objects filtered by the is_locked column
 * @method array findByIsExpired(boolean $is_expired) Return User objects filtered by the is_expired column
 * @method array findByCreatedAt(string $created_at) Return User objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return User objects filtered by the updated_at column
 */
abstract class BaseUserQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserQuery object.
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
            $modelName = 'UserBundle\\Model\\User';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   UserQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserQuery) {
            return $criteria;
        }
        $query = new UserQuery(null, null, $modelAlias);

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
     * @return   User|User[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 User A model object, or null if the key is not found
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
     * @return                 User A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `username`, `firstname`, `middlename`, `lastname`, `gender`, `title`, `birth_date`, `password`, `secret`, `logins`, `country`, `language`, `is_enabled`, `is_external`, `is_locked`, `is_expired`, `created_at`, `updated_at` FROM `user` WHERE `id` = :p0';
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
            $obj = new User();
            $obj->hydrate($row);
            UserPeer::addInstanceToPool($obj, (string) $key);
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
     * @return User|User[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|User[]|mixed the list of results, formatted by the current formatter
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
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserPeer::ID, $keys, Criteria::IN);
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
     * @return UserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%'); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $username)) {
                $username = str_replace('*', '%', $username);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the firstname column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstname('fooValue');   // WHERE firstname = 'fooValue'
     * $query->filterByFirstname('%fooValue%'); // WHERE firstname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByFirstname($firstname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstname)) {
                $firstname = str_replace('*', '%', $firstname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::FIRSTNAME, $firstname, $comparison);
    }

    /**
     * Filter the query on the middlename column
     *
     * Example usage:
     * <code>
     * $query->filterByMiddlename('fooValue');   // WHERE middlename = 'fooValue'
     * $query->filterByMiddlename('%fooValue%'); // WHERE middlename LIKE '%fooValue%'
     * </code>
     *
     * @param     string $middlename The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByMiddlename($middlename = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($middlename)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $middlename)) {
                $middlename = str_replace('*', '%', $middlename);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::MIDDLENAME, $middlename, $comparison);
    }

    /**
     * Filter the query on the lastname column
     *
     * Example usage:
     * <code>
     * $query->filterByLastname('fooValue');   // WHERE lastname = 'fooValue'
     * $query->filterByLastname('%fooValue%'); // WHERE lastname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByLastname($lastname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastname)) {
                $lastname = str_replace('*', '%', $lastname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::LASTNAME, $lastname, $comparison);
    }

    /**
     * Filter the query on the gender column
     *
     * Example usage:
     * <code>
     * $query->filterByGender(1234); // WHERE gender = 1234
     * $query->filterByGender(array(12, 34)); // WHERE gender IN (12, 34)
     * $query->filterByGender(array('min' => 12)); // WHERE gender >= 12
     * $query->filterByGender(array('max' => 12)); // WHERE gender <= 12
     * </code>
     *
     * @see       filterByUserGender()
     *
     * @param     mixed $gender The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByGender($gender = null, $comparison = null)
    {
        if (is_array($gender)) {
            $useMinMax = false;
            if (isset($gender['min'])) {
                $this->addUsingAlias(UserPeer::GENDER, $gender['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gender['max'])) {
                $this->addUsingAlias(UserPeer::GENDER, $gender['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::GENDER, $gender, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle(1234); // WHERE title = 1234
     * $query->filterByTitle(array(12, 34)); // WHERE title IN (12, 34)
     * $query->filterByTitle(array('min' => 12)); // WHERE title >= 12
     * $query->filterByTitle(array('max' => 12)); // WHERE title <= 12
     * </code>
     *
     * @see       filterByUserTitle()
     *
     * @param     mixed $title The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (is_array($title)) {
            $useMinMax = false;
            if (isset($title['min'])) {
                $this->addUsingAlias(UserPeer::TITLE, $title['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($title['max'])) {
                $this->addUsingAlias(UserPeer::TITLE, $title['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the birth_date column
     *
     * Example usage:
     * <code>
     * $query->filterByBirthDate('2011-03-14'); // WHERE birth_date = '2011-03-14'
     * $query->filterByBirthDate('now'); // WHERE birth_date = '2011-03-14'
     * $query->filterByBirthDate(array('max' => 'yesterday')); // WHERE birth_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $birthDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByBirthDate($birthDate = null, $comparison = null)
    {
        if (is_array($birthDate)) {
            $useMinMax = false;
            if (isset($birthDate['min'])) {
                $this->addUsingAlias(UserPeer::BIRTH_DATE, $birthDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($birthDate['max'])) {
                $this->addUsingAlias(UserPeer::BIRTH_DATE, $birthDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::BIRTH_DATE, $birthDate, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the secret column
     *
     * Example usage:
     * <code>
     * $query->filterBySecret('fooValue');   // WHERE secret = 'fooValue'
     * $query->filterBySecret('%fooValue%'); // WHERE secret LIKE '%fooValue%'
     * </code>
     *
     * @param     string $secret The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterBySecret($secret = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($secret)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $secret)) {
                $secret = str_replace('*', '%', $secret);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::SECRET, $secret, $comparison);
    }

    /**
     * Filter the query on the logins column
     *
     * Example usage:
     * <code>
     * $query->filterByLogins(1234); // WHERE logins = 1234
     * $query->filterByLogins(array(12, 34)); // WHERE logins IN (12, 34)
     * $query->filterByLogins(array('min' => 12)); // WHERE logins >= 12
     * $query->filterByLogins(array('max' => 12)); // WHERE logins <= 12
     * </code>
     *
     * @param     mixed $logins The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByLogins($logins = null, $comparison = null)
    {
        if (is_array($logins)) {
            $useMinMax = false;
            if (isset($logins['min'])) {
                $this->addUsingAlias(UserPeer::LOGINS, $logins['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($logins['max'])) {
                $this->addUsingAlias(UserPeer::LOGINS, $logins['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::LOGINS, $logins, $comparison);
    }

    /**
     * Filter the query on the country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry(1234); // WHERE country = 1234
     * $query->filterByCountry(array(12, 34)); // WHERE country IN (12, 34)
     * $query->filterByCountry(array('min' => 12)); // WHERE country >= 12
     * $query->filterByCountry(array('max' => 12)); // WHERE country <= 12
     * </code>
     *
     * @see       filterByCountriesRelatedByCountry()
     *
     * @param     mixed $country The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByCountry($country = null, $comparison = null)
    {
        if (is_array($country)) {
            $useMinMax = false;
            if (isset($country['min'])) {
                $this->addUsingAlias(UserPeer::COUNTRY, $country['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($country['max'])) {
                $this->addUsingAlias(UserPeer::COUNTRY, $country['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::COUNTRY, $country, $comparison);
    }

    /**
     * Filter the query on the language column
     *
     * Example usage:
     * <code>
     * $query->filterByLanguage(1234); // WHERE language = 1234
     * $query->filterByLanguage(array(12, 34)); // WHERE language IN (12, 34)
     * $query->filterByLanguage(array('min' => 12)); // WHERE language >= 12
     * $query->filterByLanguage(array('max' => 12)); // WHERE language <= 12
     * </code>
     *
     * @see       filterByCountriesRelatedByLanguage()
     *
     * @param     mixed $language The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByLanguage($language = null, $comparison = null)
    {
        if (is_array($language)) {
            $useMinMax = false;
            if (isset($language['min'])) {
                $this->addUsingAlias(UserPeer::LANGUAGE, $language['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($language['max'])) {
                $this->addUsingAlias(UserPeer::LANGUAGE, $language['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::LANGUAGE, $language, $comparison);
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
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByIsEnabled($isEnabled = null, $comparison = null)
    {
        if (is_string($isEnabled)) {
            $isEnabled = in_array(strtolower($isEnabled), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserPeer::IS_ENABLED, $isEnabled, $comparison);
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
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByIsExternal($isExternal = null, $comparison = null)
    {
        if (is_string($isExternal)) {
            $isExternal = in_array(strtolower($isExternal), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserPeer::IS_EXTERNAL, $isExternal, $comparison);
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
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByIsLocked($isLocked = null, $comparison = null)
    {
        if (is_string($isLocked)) {
            $isLocked = in_array(strtolower($isLocked), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserPeer::IS_LOCKED, $isLocked, $comparison);
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
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByIsExpired($isExpired = null, $comparison = null)
    {
        if (is_string($isExpired)) {
            $isExpired = in_array(strtolower($isExpired), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserPeer::IS_EXPIRED, $isExpired, $comparison);
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
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(UserPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related UserGender object
     *
     * @param   UserGender|PropelObjectCollection $userGender The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUserGender($userGender, $comparison = null)
    {
        if ($userGender instanceof UserGender) {
            return $this
                ->addUsingAlias(UserPeer::GENDER, $userGender->getId(), $comparison);
        } elseif ($userGender instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserPeer::GENDER, $userGender->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUserGender() only accepts arguments of type UserGender or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserGender relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinUserGender($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserGender');

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
            $this->addJoinObject($join, 'UserGender');
        }

        return $this;
    }

    /**
     * Use the UserGender relation UserGender object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\UserGenderQuery A secondary query class using the current class as primary query
     */
    public function useUserGenderQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserGender($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserGender', '\UserBundle\Model\UserGenderQuery');
    }

    /**
     * Filter the query by a related UserTitle object
     *
     * @param   UserTitle|PropelObjectCollection $userTitle The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUserTitle($userTitle, $comparison = null)
    {
        if ($userTitle instanceof UserTitle) {
            return $this
                ->addUsingAlias(UserPeer::TITLE, $userTitle->getId(), $comparison);
        } elseif ($userTitle instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserPeer::TITLE, $userTitle->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUserTitle() only accepts arguments of type UserTitle or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserTitle relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinUserTitle($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserTitle');

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
            $this->addJoinObject($join, 'UserTitle');
        }

        return $this;
    }

    /**
     * Use the UserTitle relation UserTitle object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\UserTitleQuery A secondary query class using the current class as primary query
     */
    public function useUserTitleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserTitle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserTitle', '\UserBundle\Model\UserTitleQuery');
    }

    /**
     * Filter the query by a related Countries object
     *
     * @param   Countries|PropelObjectCollection $countries The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCountriesRelatedByCountry($countries, $comparison = null)
    {
        if ($countries instanceof Countries) {
            return $this
                ->addUsingAlias(UserPeer::COUNTRY, $countries->getId(), $comparison);
        } elseif ($countries instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserPeer::COUNTRY, $countries->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCountriesRelatedByCountry() only accepts arguments of type Countries or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CountriesRelatedByCountry relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinCountriesRelatedByCountry($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CountriesRelatedByCountry');

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
            $this->addJoinObject($join, 'CountriesRelatedByCountry');
        }

        return $this;
    }

    /**
     * Use the CountriesRelatedByCountry relation Countries object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\CountriesQuery A secondary query class using the current class as primary query
     */
    public function useCountriesRelatedByCountryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCountriesRelatedByCountry($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CountriesRelatedByCountry', '\UserBundle\Model\CountriesQuery');
    }

    /**
     * Filter the query by a related Countries object
     *
     * @param   Countries|PropelObjectCollection $countries The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCountriesRelatedByLanguage($countries, $comparison = null)
    {
        if ($countries instanceof Countries) {
            return $this
                ->addUsingAlias(UserPeer::LANGUAGE, $countries->getId(), $comparison);
        } elseif ($countries instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserPeer::LANGUAGE, $countries->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCountriesRelatedByLanguage() only accepts arguments of type Countries or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CountriesRelatedByLanguage relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinCountriesRelatedByLanguage($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CountriesRelatedByLanguage');

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
            $this->addJoinObject($join, 'CountriesRelatedByLanguage');
        }

        return $this;
    }

    /**
     * Use the CountriesRelatedByLanguage relation Countries object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\CountriesQuery A secondary query class using the current class as primary query
     */
    public function useCountriesRelatedByLanguageQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCountriesRelatedByLanguage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CountriesRelatedByLanguage', '\UserBundle\Model\CountriesQuery');
    }

    /**
     * Filter the query by a related CompanyContact object
     *
     * @param   CompanyContact|PropelObjectCollection $companyContact  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompanyContact($companyContact, $comparison = null)
    {
        if ($companyContact instanceof CompanyContact) {
            return $this
                ->addUsingAlias(UserPeer::ID, $companyContact->getContactId(), $comparison);
        } elseif ($companyContact instanceof PropelObjectCollection) {
            return $this
                ->useCompanyContactQuery()
                ->filterByPrimaryKeys($companyContact->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCompanyContact() only accepts arguments of type CompanyContact or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CompanyContact relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinCompanyContact($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CompanyContact');

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
            $this->addJoinObject($join, 'CompanyContact');
        }

        return $this;
    }

    /**
     * Use the CompanyContact relation CompanyContact object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\CompanyContactQuery A secondary query class using the current class as primary query
     */
    public function useCompanyContactQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompanyContact($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CompanyContact', '\CompanyBundle\Model\CompanyContactQuery');
    }

    /**
     * Filter the query by a related CompanyInformant object
     *
     * @param   CompanyInformant|PropelObjectCollection $companyInformant  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompanyInformant($companyInformant, $comparison = null)
    {
        if ($companyInformant instanceof CompanyInformant) {
            return $this
                ->addUsingAlias(UserPeer::ID, $companyInformant->getInformantId(), $comparison);
        } elseif ($companyInformant instanceof PropelObjectCollection) {
            return $this
                ->useCompanyInformantQuery()
                ->filterByPrimaryKeys($companyInformant->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCompanyInformant() only accepts arguments of type CompanyInformant or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CompanyInformant relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinCompanyInformant($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CompanyInformant');

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
            $this->addJoinObject($join, 'CompanyInformant');
        }

        return $this;
    }

    /**
     * Use the CompanyInformant relation CompanyInformant object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\CompanyInformantQuery A secondary query class using the current class as primary query
     */
    public function useCompanyInformantQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompanyInformant($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CompanyInformant', '\CompanyBundle\Model\CompanyInformantQuery');
    }

    /**
     * Filter the query by a related CompanyOwner object
     *
     * @param   CompanyOwner|PropelObjectCollection $companyOwner  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompanyOwner($companyOwner, $comparison = null)
    {
        if ($companyOwner instanceof CompanyOwner) {
            return $this
                ->addUsingAlias(UserPeer::ID, $companyOwner->getOwnerId(), $comparison);
        } elseif ($companyOwner instanceof PropelObjectCollection) {
            return $this
                ->useCompanyOwnerQuery()
                ->filterByPrimaryKeys($companyOwner->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCompanyOwner() only accepts arguments of type CompanyOwner or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CompanyOwner relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinCompanyOwner($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CompanyOwner');

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
            $this->addJoinObject($join, 'CompanyOwner');
        }

        return $this;
    }

    /**
     * Use the CompanyOwner relation CompanyOwner object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\CompanyOwnerQuery A secondary query class using the current class as primary query
     */
    public function useCompanyOwnerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompanyOwner($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CompanyOwner', '\CompanyBundle\Model\CompanyOwnerQuery');
    }

    /**
     * Filter the query by a related DsTemperatureNotification object
     *
     * @param   DsTemperatureNotification|PropelObjectCollection $dsTemperatureNotification  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDsTemperatureNotification($dsTemperatureNotification, $comparison = null)
    {
        if ($dsTemperatureNotification instanceof DsTemperatureNotification) {
            return $this
                ->addUsingAlias(UserPeer::ID, $dsTemperatureNotification->getHandledBy(), $comparison);
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
     * @return UserQuery The current query, for fluid interface
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
     * Filter the query by a related CbInputNotification object
     *
     * @param   CbInputNotification|PropelObjectCollection $cbInputNotification  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCbInputNotification($cbInputNotification, $comparison = null)
    {
        if ($cbInputNotification instanceof CbInputNotification) {
            return $this
                ->addUsingAlias(UserPeer::ID, $cbInputNotification->getHandledBy(), $comparison);
        } elseif ($cbInputNotification instanceof PropelObjectCollection) {
            return $this
                ->useCbInputNotificationQuery()
                ->filterByPrimaryKeys($cbInputNotification->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCbInputNotification() only accepts arguments of type CbInputNotification or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CbInputNotification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinCbInputNotification($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CbInputNotification');

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
            $this->addJoinObject($join, 'CbInputNotification');
        }

        return $this;
    }

    /**
     * Use the CbInputNotification relation CbInputNotification object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \NotificationBundle\Model\CbInputNotificationQuery A secondary query class using the current class as primary query
     */
    public function useCbInputNotificationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCbInputNotification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CbInputNotification', '\NotificationBundle\Model\CbInputNotificationQuery');
    }

    /**
     * Filter the query by a related StoreContact object
     *
     * @param   StoreContact|PropelObjectCollection $storeContact  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStoreContact($storeContact, $comparison = null)
    {
        if ($storeContact instanceof StoreContact) {
            return $this
                ->addUsingAlias(UserPeer::ID, $storeContact->getContactId(), $comparison);
        } elseif ($storeContact instanceof PropelObjectCollection) {
            return $this
                ->useStoreContactQuery()
                ->filterByPrimaryKeys($storeContact->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStoreContact() only accepts arguments of type StoreContact or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StoreContact relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinStoreContact($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StoreContact');

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
            $this->addJoinObject($join, 'StoreContact');
        }

        return $this;
    }

    /**
     * Use the StoreContact relation StoreContact object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreContactQuery A secondary query class using the current class as primary query
     */
    public function useStoreContactQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStoreContact($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StoreContact', '\StoreBundle\Model\StoreContactQuery');
    }

    /**
     * Filter the query by a related StoreInformant object
     *
     * @param   StoreInformant|PropelObjectCollection $storeInformant  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStoreInformant($storeInformant, $comparison = null)
    {
        if ($storeInformant instanceof StoreInformant) {
            return $this
                ->addUsingAlias(UserPeer::ID, $storeInformant->getInformantId(), $comparison);
        } elseif ($storeInformant instanceof PropelObjectCollection) {
            return $this
                ->useStoreInformantQuery()
                ->filterByPrimaryKeys($storeInformant->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStoreInformant() only accepts arguments of type StoreInformant or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StoreInformant relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinStoreInformant($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StoreInformant');

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
            $this->addJoinObject($join, 'StoreInformant');
        }

        return $this;
    }

    /**
     * Use the StoreInformant relation StoreInformant object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreInformantQuery A secondary query class using the current class as primary query
     */
    public function useStoreInformantQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStoreInformant($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StoreInformant', '\StoreBundle\Model\StoreInformantQuery');
    }

    /**
     * Filter the query by a related StoreOwner object
     *
     * @param   StoreOwner|PropelObjectCollection $storeOwner  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStoreOwner($storeOwner, $comparison = null)
    {
        if ($storeOwner instanceof StoreOwner) {
            return $this
                ->addUsingAlias(UserPeer::ID, $storeOwner->getOwnerId(), $comparison);
        } elseif ($storeOwner instanceof PropelObjectCollection) {
            return $this
                ->useStoreOwnerQuery()
                ->filterByPrimaryKeys($storeOwner->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStoreOwner() only accepts arguments of type StoreOwner or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StoreOwner relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinStoreOwner($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StoreOwner');

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
            $this->addJoinObject($join, 'StoreOwner');
        }

        return $this;
    }

    /**
     * Use the StoreOwner relation StoreOwner object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreOwnerQuery A secondary query class using the current class as primary query
     */
    public function useStoreOwnerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStoreOwner($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StoreOwner', '\StoreBundle\Model\StoreOwnerQuery');
    }

    /**
     * Filter the query by a related UserRole object
     *
     * @param   UserRole|PropelObjectCollection $userRole  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUserRole($userRole, $comparison = null)
    {
        if ($userRole instanceof UserRole) {
            return $this
                ->addUsingAlias(UserPeer::ID, $userRole->getUserId(), $comparison);
        } elseif ($userRole instanceof PropelObjectCollection) {
            return $this
                ->useUserRoleQuery()
                ->filterByPrimaryKeys($userRole->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserRole() only accepts arguments of type UserRole or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRole relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinUserRole($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\UserRoleQuery A secondary query class using the current class as primary query
     */
    public function useUserRoleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserRole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRole', '\UserBundle\Model\UserRoleQuery');
    }

    /**
     * Filter the query by a related UserAddress object
     *
     * @param   UserAddress|PropelObjectCollection $userAddress  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUserAddress($userAddress, $comparison = null)
    {
        if ($userAddress instanceof UserAddress) {
            return $this
                ->addUsingAlias(UserPeer::ID, $userAddress->getUserId(), $comparison);
        } elseif ($userAddress instanceof PropelObjectCollection) {
            return $this
                ->useUserAddressQuery()
                ->filterByPrimaryKeys($userAddress->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserAddress() only accepts arguments of type UserAddress or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserAddress relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinUserAddress($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserAddress');

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
            $this->addJoinObject($join, 'UserAddress');
        }

        return $this;
    }

    /**
     * Use the UserAddress relation UserAddress object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\UserAddressQuery A secondary query class using the current class as primary query
     */
    public function useUserAddressQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserAddress($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserAddress', '\UserBundle\Model\UserAddressQuery');
    }

    /**
     * Filter the query by a related UserEmail object
     *
     * @param   UserEmail|PropelObjectCollection $userEmail  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUserEmail($userEmail, $comparison = null)
    {
        if ($userEmail instanceof UserEmail) {
            return $this
                ->addUsingAlias(UserPeer::ID, $userEmail->getUserId(), $comparison);
        } elseif ($userEmail instanceof PropelObjectCollection) {
            return $this
                ->useUserEmailQuery()
                ->filterByPrimaryKeys($userEmail->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserEmail() only accepts arguments of type UserEmail or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserEmail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinUserEmail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserEmail');

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
            $this->addJoinObject($join, 'UserEmail');
        }

        return $this;
    }

    /**
     * Use the UserEmail relation UserEmail object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\UserEmailQuery A secondary query class using the current class as primary query
     */
    public function useUserEmailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserEmail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserEmail', '\UserBundle\Model\UserEmailQuery');
    }

    /**
     * Filter the query by a related UserPhone object
     *
     * @param   UserPhone|PropelObjectCollection $userPhone  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUserPhone($userPhone, $comparison = null)
    {
        if ($userPhone instanceof UserPhone) {
            return $this
                ->addUsingAlias(UserPeer::ID, $userPhone->getUserId(), $comparison);
        } elseif ($userPhone instanceof PropelObjectCollection) {
            return $this
                ->useUserPhoneQuery()
                ->filterByPrimaryKeys($userPhone->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserPhone() only accepts arguments of type UserPhone or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserPhone relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinUserPhone($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserPhone');

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
            $this->addJoinObject($join, 'UserPhone');
        }

        return $this;
    }

    /**
     * Use the UserPhone relation UserPhone object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\UserPhoneQuery A secondary query class using the current class as primary query
     */
    public function useUserPhoneQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserPhone($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserPhone', '\UserBundle\Model\UserPhoneQuery');
    }

    /**
     * Filter the query by a related Company object
     * using the company_contact table as cross reference
     *
     * @param   Company $company the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuery The current query, for fluid interface
     */
    public function filterByContactCompany($company, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCompanyContactQuery()
            ->filterByContactCompany($company, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Company object
     * using the company_informant table as cross reference
     *
     * @param   Company $company the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuery The current query, for fluid interface
     */
    public function filterByInformantCompany($company, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCompanyInformantQuery()
            ->filterByInformantCompany($company, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Company object
     * using the company_owner table as cross reference
     *
     * @param   Company $company the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuery The current query, for fluid interface
     */
    public function filterByOwnerCompany($company, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCompanyOwnerQuery()
            ->filterByOwnerCompany($company, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Store object
     * using the store_contact table as cross reference
     *
     * @param   Store $store the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuery The current query, for fluid interface
     */
    public function filterByContactStore($store, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useStoreContactQuery()
            ->filterByContactStore($store, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Store object
     * using the store_informant table as cross reference
     *
     * @param   Store $store the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuery The current query, for fluid interface
     */
    public function filterByInformantStore($store, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useStoreInformantQuery()
            ->filterByInformantStore($store, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Store object
     * using the store_owner table as cross reference
     *
     * @param   Store $store the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuery The current query, for fluid interface
     */
    public function filterByOwnerStore($store, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useStoreOwnerQuery()
            ->filterByOwnerStore($store, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Role object
     * using the user_role table as cross reference
     *
     * @param   Role $role the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuery The current query, for fluid interface
     */
    public function filterByRole($role, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserRoleQuery()
            ->filterByRole($role, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Address object
     * using the user_address table as cross reference
     *
     * @param   Address $address the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuery The current query, for fluid interface
     */
    public function filterByAddress($address, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserAddressQuery()
            ->filterByAddress($address, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Email object
     * using the user_email table as cross reference
     *
     * @param   Email $email the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuery The current query, for fluid interface
     */
    public function filterByEmail($email, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserEmailQuery()
            ->filterByEmail($email, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Phone object
     * using the user_phone table as cross reference
     *
     * @param   Phone $phone the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuery The current query, for fluid interface
     */
    public function filterByPhone($phone, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPhoneQuery()
            ->filterByPhone($phone, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   User $user Object to remove from the list of results
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserPeer::ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     UserQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     UserQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     UserQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     UserQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     UserQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     UserQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserPeer::CREATED_AT);
    }
}
