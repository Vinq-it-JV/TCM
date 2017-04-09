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
use CompanyBundle\Model\CompanyAddress;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreAddress;
use UserBundle\Model\Address;
use UserBundle\Model\AddressPeer;
use UserBundle\Model\AddressQuery;
use UserBundle\Model\Countries;
use UserBundle\Model\User;
use UserBundle\Model\UserAddress;

/**
 * @method AddressQuery orderById($order = Criteria::ASC) Order by the id column
 * @method AddressQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method AddressQuery orderByStreetName($order = Criteria::ASC) Order by the street_name column
 * @method AddressQuery orderByHouseNumber($order = Criteria::ASC) Order by the house_number column
 * @method AddressQuery orderByExtraInfo($order = Criteria::ASC) Order by the extra_info column
 * @method AddressQuery orderByPostalCode($order = Criteria::ASC) Order by the postal_code column
 * @method AddressQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method AddressQuery orderByCountry($order = Criteria::ASC) Order by the country column
 * @method AddressQuery orderByMapUrl($order = Criteria::ASC) Order by the map_url column
 * @method AddressQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method AddressQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method AddressQuery groupById() Group by the id column
 * @method AddressQuery groupByType() Group by the type column
 * @method AddressQuery groupByStreetName() Group by the street_name column
 * @method AddressQuery groupByHouseNumber() Group by the house_number column
 * @method AddressQuery groupByExtraInfo() Group by the extra_info column
 * @method AddressQuery groupByPostalCode() Group by the postal_code column
 * @method AddressQuery groupByCity() Group by the city column
 * @method AddressQuery groupByCountry() Group by the country column
 * @method AddressQuery groupByMapUrl() Group by the map_url column
 * @method AddressQuery groupByCreatedAt() Group by the created_at column
 * @method AddressQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method AddressQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method AddressQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method AddressQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method AddressQuery leftJoinCountries($relationAlias = null) Adds a LEFT JOIN clause to the query using the Countries relation
 * @method AddressQuery rightJoinCountries($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Countries relation
 * @method AddressQuery innerJoinCountries($relationAlias = null) Adds a INNER JOIN clause to the query using the Countries relation
 *
 * @method AddressQuery leftJoinCompanyAddress($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompanyAddress relation
 * @method AddressQuery rightJoinCompanyAddress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompanyAddress relation
 * @method AddressQuery innerJoinCompanyAddress($relationAlias = null) Adds a INNER JOIN clause to the query using the CompanyAddress relation
 *
 * @method AddressQuery leftJoinStoreAddress($relationAlias = null) Adds a LEFT JOIN clause to the query using the StoreAddress relation
 * @method AddressQuery rightJoinStoreAddress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StoreAddress relation
 * @method AddressQuery innerJoinStoreAddress($relationAlias = null) Adds a INNER JOIN clause to the query using the StoreAddress relation
 *
 * @method AddressQuery leftJoinUserAddress($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserAddress relation
 * @method AddressQuery rightJoinUserAddress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserAddress relation
 * @method AddressQuery innerJoinUserAddress($relationAlias = null) Adds a INNER JOIN clause to the query using the UserAddress relation
 *
 * @method Address findOne(PropelPDO $con = null) Return the first Address matching the query
 * @method Address findOneOrCreate(PropelPDO $con = null) Return the first Address matching the query, or a new Address object populated from the query conditions when no match is found
 *
 * @method Address findOneByType(int $type) Return the first Address filtered by the type column
 * @method Address findOneByStreetName(string $street_name) Return the first Address filtered by the street_name column
 * @method Address findOneByHouseNumber(string $house_number) Return the first Address filtered by the house_number column
 * @method Address findOneByExtraInfo(string $extra_info) Return the first Address filtered by the extra_info column
 * @method Address findOneByPostalCode(string $postal_code) Return the first Address filtered by the postal_code column
 * @method Address findOneByCity(string $city) Return the first Address filtered by the city column
 * @method Address findOneByCountry(int $country) Return the first Address filtered by the country column
 * @method Address findOneByMapUrl(string $map_url) Return the first Address filtered by the map_url column
 * @method Address findOneByCreatedAt(string $created_at) Return the first Address filtered by the created_at column
 * @method Address findOneByUpdatedAt(string $updated_at) Return the first Address filtered by the updated_at column
 *
 * @method array findById(int $id) Return Address objects filtered by the id column
 * @method array findByType(int $type) Return Address objects filtered by the type column
 * @method array findByStreetName(string $street_name) Return Address objects filtered by the street_name column
 * @method array findByHouseNumber(string $house_number) Return Address objects filtered by the house_number column
 * @method array findByExtraInfo(string $extra_info) Return Address objects filtered by the extra_info column
 * @method array findByPostalCode(string $postal_code) Return Address objects filtered by the postal_code column
 * @method array findByCity(string $city) Return Address objects filtered by the city column
 * @method array findByCountry(int $country) Return Address objects filtered by the country column
 * @method array findByMapUrl(string $map_url) Return Address objects filtered by the map_url column
 * @method array findByCreatedAt(string $created_at) Return Address objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Address objects filtered by the updated_at column
 */
abstract class BaseAddressQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseAddressQuery object.
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
            $modelName = 'UserBundle\\Model\\Address';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new AddressQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   AddressQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return AddressQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof AddressQuery) {
            return $criteria;
        }
        $query = new AddressQuery(null, null, $modelAlias);

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
     * @return   Address|Address[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AddressPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(AddressPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Address A model object, or null if the key is not found
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
     * @return                 Address A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `type`, `street_name`, `house_number`, `extra_info`, `postal_code`, `city`, `country`, `map_url`, `created_at`, `updated_at` FROM `address` WHERE `id` = :p0';
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
            $obj = new Address();
            $obj->hydrate($row);
            AddressPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Address|Address[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Address[]|mixed the list of results, formatted by the current formatter
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
     * @return AddressQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AddressPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return AddressQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AddressPeer::ID, $keys, Criteria::IN);
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
     * @return AddressQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AddressPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AddressPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AddressPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType(1234); // WHERE type = 1234
     * $query->filterByType(array(12, 34)); // WHERE type IN (12, 34)
     * $query->filterByType(array('min' => 12)); // WHERE type >= 12
     * $query->filterByType(array('max' => 12)); // WHERE type <= 12
     * </code>
     *
     * @param     mixed $type The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AddressQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (is_array($type)) {
            $useMinMax = false;
            if (isset($type['min'])) {
                $this->addUsingAlias(AddressPeer::TYPE, $type['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($type['max'])) {
                $this->addUsingAlias(AddressPeer::TYPE, $type['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AddressPeer::TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the street_name column
     *
     * Example usage:
     * <code>
     * $query->filterByStreetName('fooValue');   // WHERE street_name = 'fooValue'
     * $query->filterByStreetName('%fooValue%'); // WHERE street_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $streetName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AddressQuery The current query, for fluid interface
     */
    public function filterByStreetName($streetName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($streetName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $streetName)) {
                $streetName = str_replace('*', '%', $streetName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AddressPeer::STREET_NAME, $streetName, $comparison);
    }

    /**
     * Filter the query on the house_number column
     *
     * Example usage:
     * <code>
     * $query->filterByHouseNumber('fooValue');   // WHERE house_number = 'fooValue'
     * $query->filterByHouseNumber('%fooValue%'); // WHERE house_number LIKE '%fooValue%'
     * </code>
     *
     * @param     string $houseNumber The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AddressQuery The current query, for fluid interface
     */
    public function filterByHouseNumber($houseNumber = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($houseNumber)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $houseNumber)) {
                $houseNumber = str_replace('*', '%', $houseNumber);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AddressPeer::HOUSE_NUMBER, $houseNumber, $comparison);
    }

    /**
     * Filter the query on the extra_info column
     *
     * Example usage:
     * <code>
     * $query->filterByExtraInfo('fooValue');   // WHERE extra_info = 'fooValue'
     * $query->filterByExtraInfo('%fooValue%'); // WHERE extra_info LIKE '%fooValue%'
     * </code>
     *
     * @param     string $extraInfo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AddressQuery The current query, for fluid interface
     */
    public function filterByExtraInfo($extraInfo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($extraInfo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $extraInfo)) {
                $extraInfo = str_replace('*', '%', $extraInfo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AddressPeer::EXTRA_INFO, $extraInfo, $comparison);
    }

    /**
     * Filter the query on the postal_code column
     *
     * Example usage:
     * <code>
     * $query->filterByPostalCode('fooValue');   // WHERE postal_code = 'fooValue'
     * $query->filterByPostalCode('%fooValue%'); // WHERE postal_code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $postalCode The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AddressQuery The current query, for fluid interface
     */
    public function filterByPostalCode($postalCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postalCode)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $postalCode)) {
                $postalCode = str_replace('*', '%', $postalCode);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AddressPeer::POSTAL_CODE, $postalCode, $comparison);
    }

    /**
     * Filter the query on the city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE city = 'fooValue'
     * $query->filterByCity('%fooValue%'); // WHERE city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AddressQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $city)) {
                $city = str_replace('*', '%', $city);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AddressPeer::CITY, $city, $comparison);
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
     * @see       filterByCountries()
     *
     * @param     mixed $country The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AddressQuery The current query, for fluid interface
     */
    public function filterByCountry($country = null, $comparison = null)
    {
        if (is_array($country)) {
            $useMinMax = false;
            if (isset($country['min'])) {
                $this->addUsingAlias(AddressPeer::COUNTRY, $country['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($country['max'])) {
                $this->addUsingAlias(AddressPeer::COUNTRY, $country['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AddressPeer::COUNTRY, $country, $comparison);
    }

    /**
     * Filter the query on the map_url column
     *
     * Example usage:
     * <code>
     * $query->filterByMapUrl('fooValue');   // WHERE map_url = 'fooValue'
     * $query->filterByMapUrl('%fooValue%'); // WHERE map_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mapUrl The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AddressQuery The current query, for fluid interface
     */
    public function filterByMapUrl($mapUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mapUrl)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $mapUrl)) {
                $mapUrl = str_replace('*', '%', $mapUrl);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AddressPeer::MAP_URL, $mapUrl, $comparison);
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
     * @return AddressQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(AddressPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(AddressPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AddressPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return AddressQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(AddressPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(AddressPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AddressPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Countries object
     *
     * @param   Countries|PropelObjectCollection $countries The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AddressQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCountries($countries, $comparison = null)
    {
        if ($countries instanceof Countries) {
            return $this
                ->addUsingAlias(AddressPeer::COUNTRY, $countries->getId(), $comparison);
        } elseif ($countries instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AddressPeer::COUNTRY, $countries->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCountries() only accepts arguments of type Countries or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Countries relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AddressQuery The current query, for fluid interface
     */
    public function joinCountries($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Countries');

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
            $this->addJoinObject($join, 'Countries');
        }

        return $this;
    }

    /**
     * Use the Countries relation Countries object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\CountriesQuery A secondary query class using the current class as primary query
     */
    public function useCountriesQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCountries($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Countries', '\UserBundle\Model\CountriesQuery');
    }

    /**
     * Filter the query by a related CompanyAddress object
     *
     * @param   CompanyAddress|PropelObjectCollection $companyAddress  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AddressQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompanyAddress($companyAddress, $comparison = null)
    {
        if ($companyAddress instanceof CompanyAddress) {
            return $this
                ->addUsingAlias(AddressPeer::ID, $companyAddress->getAddressId(), $comparison);
        } elseif ($companyAddress instanceof PropelObjectCollection) {
            return $this
                ->useCompanyAddressQuery()
                ->filterByPrimaryKeys($companyAddress->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCompanyAddress() only accepts arguments of type CompanyAddress or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CompanyAddress relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AddressQuery The current query, for fluid interface
     */
    public function joinCompanyAddress($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CompanyAddress');

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
            $this->addJoinObject($join, 'CompanyAddress');
        }

        return $this;
    }

    /**
     * Use the CompanyAddress relation CompanyAddress object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\CompanyAddressQuery A secondary query class using the current class as primary query
     */
    public function useCompanyAddressQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompanyAddress($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CompanyAddress', '\CompanyBundle\Model\CompanyAddressQuery');
    }

    /**
     * Filter the query by a related StoreAddress object
     *
     * @param   StoreAddress|PropelObjectCollection $storeAddress  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AddressQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStoreAddress($storeAddress, $comparison = null)
    {
        if ($storeAddress instanceof StoreAddress) {
            return $this
                ->addUsingAlias(AddressPeer::ID, $storeAddress->getAddressId(), $comparison);
        } elseif ($storeAddress instanceof PropelObjectCollection) {
            return $this
                ->useStoreAddressQuery()
                ->filterByPrimaryKeys($storeAddress->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStoreAddress() only accepts arguments of type StoreAddress or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StoreAddress relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AddressQuery The current query, for fluid interface
     */
    public function joinStoreAddress($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StoreAddress');

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
            $this->addJoinObject($join, 'StoreAddress');
        }

        return $this;
    }

    /**
     * Use the StoreAddress relation StoreAddress object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreAddressQuery A secondary query class using the current class as primary query
     */
    public function useStoreAddressQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStoreAddress($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StoreAddress', '\StoreBundle\Model\StoreAddressQuery');
    }

    /**
     * Filter the query by a related UserAddress object
     *
     * @param   UserAddress|PropelObjectCollection $userAddress  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AddressQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUserAddress($userAddress, $comparison = null)
    {
        if ($userAddress instanceof UserAddress) {
            return $this
                ->addUsingAlias(AddressPeer::ID, $userAddress->getAddressId(), $comparison);
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
     * @return AddressQuery The current query, for fluid interface
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
     * Filter the query by a related Company object
     * using the company_address table as cross reference
     *
     * @param   Company $company the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AddressQuery The current query, for fluid interface
     */
    public function filterByCompany($company, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCompanyAddressQuery()
            ->filterByCompany($company, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Store object
     * using the store_address table as cross reference
     *
     * @param   Store $store the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AddressQuery The current query, for fluid interface
     */
    public function filterByStore($store, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useStoreAddressQuery()
            ->filterByStore($store, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related User object
     * using the user_address table as cross reference
     *
     * @param   User $user the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AddressQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserAddressQuery()
            ->filterByUser($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   Address $address Object to remove from the list of results
     *
     * @return AddressQuery The current query, for fluid interface
     */
    public function prune($address = null)
    {
        if ($address) {
            $this->addUsingAlias(AddressPeer::ID, $address->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     AddressQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(AddressPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     AddressQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(AddressPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     AddressQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(AddressPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     AddressQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(AddressPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     AddressQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(AddressPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     AddressQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(AddressPeer::CREATED_AT);
    }
}
