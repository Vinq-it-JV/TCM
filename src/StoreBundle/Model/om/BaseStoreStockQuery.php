<?php

namespace StoreBundle\Model\om;

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
use StoreBundle\Model\StockImage;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreStock;
use StoreBundle\Model\StoreStockPeer;
use StoreBundle\Model\StoreStockQuery;

/**
 * @method StoreStockQuery orderById($order = Criteria::ASC) Order by the id column
 * @method StoreStockQuery orderByCollectionType($order = Criteria::ASC) Order by the collection_type column
 * @method StoreStockQuery orderByStoreId($order = Criteria::ASC) Order by the store_id column
 * @method StoreStockQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method StoreStockQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method StoreStockQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method StoreStockQuery orderByImageId($order = Criteria::ASC) Order by the image_id column
 * @method StoreStockQuery orderByStockValue($order = Criteria::ASC) Order by the stock_value column
 * @method StoreStockQuery orderByStockMin($order = Criteria::ASC) Order by the stock_min column
 * @method StoreStockQuery orderByStockMax($order = Criteria::ASC) Order by the stock_max column
 * @method StoreStockQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method StoreStockQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method StoreStockQuery groupById() Group by the id column
 * @method StoreStockQuery groupByCollectionType() Group by the collection_type column
 * @method StoreStockQuery groupByStoreId() Group by the store_id column
 * @method StoreStockQuery groupByCode() Group by the code column
 * @method StoreStockQuery groupByName() Group by the name column
 * @method StoreStockQuery groupByDescription() Group by the description column
 * @method StoreStockQuery groupByImageId() Group by the image_id column
 * @method StoreStockQuery groupByStockValue() Group by the stock_value column
 * @method StoreStockQuery groupByStockMin() Group by the stock_min column
 * @method StoreStockQuery groupByStockMax() Group by the stock_max column
 * @method StoreStockQuery groupByCreatedAt() Group by the created_at column
 * @method StoreStockQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method StoreStockQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method StoreStockQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method StoreStockQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method StoreStockQuery leftJoinStore($relationAlias = null) Adds a LEFT JOIN clause to the query using the Store relation
 * @method StoreStockQuery rightJoinStore($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Store relation
 * @method StoreStockQuery innerJoinStore($relationAlias = null) Adds a INNER JOIN clause to the query using the Store relation
 *
 * @method StoreStockQuery leftJoinStockImage($relationAlias = null) Adds a LEFT JOIN clause to the query using the StockImage relation
 * @method StoreStockQuery rightJoinStockImage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StockImage relation
 * @method StoreStockQuery innerJoinStockImage($relationAlias = null) Adds a INNER JOIN clause to the query using the StockImage relation
 *
 * @method StoreStock findOne(PropelPDO $con = null) Return the first StoreStock matching the query
 * @method StoreStock findOneOrCreate(PropelPDO $con = null) Return the first StoreStock matching the query, or a new StoreStock object populated from the query conditions when no match is found
 *
 * @method StoreStock findOneByCollectionType(int $collection_type) Return the first StoreStock filtered by the collection_type column
 * @method StoreStock findOneByStoreId(int $store_id) Return the first StoreStock filtered by the store_id column
 * @method StoreStock findOneByCode(string $code) Return the first StoreStock filtered by the code column
 * @method StoreStock findOneByName(string $name) Return the first StoreStock filtered by the name column
 * @method StoreStock findOneByDescription(string $description) Return the first StoreStock filtered by the description column
 * @method StoreStock findOneByImageId(int $image_id) Return the first StoreStock filtered by the image_id column
 * @method StoreStock findOneByStockValue(double $stock_value) Return the first StoreStock filtered by the stock_value column
 * @method StoreStock findOneByStockMin(double $stock_min) Return the first StoreStock filtered by the stock_min column
 * @method StoreStock findOneByStockMax(double $stock_max) Return the first StoreStock filtered by the stock_max column
 * @method StoreStock findOneByCreatedAt(string $created_at) Return the first StoreStock filtered by the created_at column
 * @method StoreStock findOneByUpdatedAt(string $updated_at) Return the first StoreStock filtered by the updated_at column
 *
 * @method array findById(int $id) Return StoreStock objects filtered by the id column
 * @method array findByCollectionType(int $collection_type) Return StoreStock objects filtered by the collection_type column
 * @method array findByStoreId(int $store_id) Return StoreStock objects filtered by the store_id column
 * @method array findByCode(string $code) Return StoreStock objects filtered by the code column
 * @method array findByName(string $name) Return StoreStock objects filtered by the name column
 * @method array findByDescription(string $description) Return StoreStock objects filtered by the description column
 * @method array findByImageId(int $image_id) Return StoreStock objects filtered by the image_id column
 * @method array findByStockValue(double $stock_value) Return StoreStock objects filtered by the stock_value column
 * @method array findByStockMin(double $stock_min) Return StoreStock objects filtered by the stock_min column
 * @method array findByStockMax(double $stock_max) Return StoreStock objects filtered by the stock_max column
 * @method array findByCreatedAt(string $created_at) Return StoreStock objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return StoreStock objects filtered by the updated_at column
 */
abstract class BaseStoreStockQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseStoreStockQuery object.
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
            $modelName = 'StoreBundle\\Model\\StoreStock';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new StoreStockQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   StoreStockQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return StoreStockQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof StoreStockQuery) {
            return $criteria;
        }
        $query = new StoreStockQuery(null, null, $modelAlias);

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
     * @return   StoreStock|StoreStock[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = StoreStockPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(StoreStockPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 StoreStock A model object, or null if the key is not found
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
     * @return                 StoreStock A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `collection_type`, `store_id`, `code`, `name`, `description`, `image_id`, `stock_value`, `stock_min`, `stock_max`, `created_at`, `updated_at` FROM `store_stock` WHERE `id` = :p0';
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
            $obj = new StoreStock();
            $obj->hydrate($row);
            StoreStockPeer::addInstanceToPool($obj, (string) $key);
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
     * @return StoreStock|StoreStock[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|StoreStock[]|mixed the list of results, formatted by the current formatter
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
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(StoreStockPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(StoreStockPeer::ID, $keys, Criteria::IN);
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
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(StoreStockPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(StoreStockPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreStockPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the collection_type column
     *
     * Example usage:
     * <code>
     * $query->filterByCollectionType(1234); // WHERE collection_type = 1234
     * $query->filterByCollectionType(array(12, 34)); // WHERE collection_type IN (12, 34)
     * $query->filterByCollectionType(array('min' => 12)); // WHERE collection_type >= 12
     * $query->filterByCollectionType(array('max' => 12)); // WHERE collection_type <= 12
     * </code>
     *
     * @param     mixed $collectionType The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function filterByCollectionType($collectionType = null, $comparison = null)
    {
        if (is_array($collectionType)) {
            $useMinMax = false;
            if (isset($collectionType['min'])) {
                $this->addUsingAlias(StoreStockPeer::COLLECTION_TYPE, $collectionType['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($collectionType['max'])) {
                $this->addUsingAlias(StoreStockPeer::COLLECTION_TYPE, $collectionType['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreStockPeer::COLLECTION_TYPE, $collectionType, $comparison);
    }

    /**
     * Filter the query on the store_id column
     *
     * Example usage:
     * <code>
     * $query->filterByStoreId(1234); // WHERE store_id = 1234
     * $query->filterByStoreId(array(12, 34)); // WHERE store_id IN (12, 34)
     * $query->filterByStoreId(array('min' => 12)); // WHERE store_id >= 12
     * $query->filterByStoreId(array('max' => 12)); // WHERE store_id <= 12
     * </code>
     *
     * @see       filterByStore()
     *
     * @param     mixed $storeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function filterByStoreId($storeId = null, $comparison = null)
    {
        if (is_array($storeId)) {
            $useMinMax = false;
            if (isset($storeId['min'])) {
                $this->addUsingAlias(StoreStockPeer::STORE_ID, $storeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($storeId['max'])) {
                $this->addUsingAlias(StoreStockPeer::STORE_ID, $storeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreStockPeer::STORE_ID, $storeId, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StoreStockPeer::CODE, $code, $comparison);
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
     * @return StoreStockQuery The current query, for fluid interface
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

        return $this->addUsingAlias(StoreStockPeer::NAME, $name, $comparison);
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
     * @return StoreStockQuery The current query, for fluid interface
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

        return $this->addUsingAlias(StoreStockPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the image_id column
     *
     * Example usage:
     * <code>
     * $query->filterByImageId(1234); // WHERE image_id = 1234
     * $query->filterByImageId(array(12, 34)); // WHERE image_id IN (12, 34)
     * $query->filterByImageId(array('min' => 12)); // WHERE image_id >= 12
     * $query->filterByImageId(array('max' => 12)); // WHERE image_id <= 12
     * </code>
     *
     * @see       filterByStockImage()
     *
     * @param     mixed $imageId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function filterByImageId($imageId = null, $comparison = null)
    {
        if (is_array($imageId)) {
            $useMinMax = false;
            if (isset($imageId['min'])) {
                $this->addUsingAlias(StoreStockPeer::IMAGE_ID, $imageId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($imageId['max'])) {
                $this->addUsingAlias(StoreStockPeer::IMAGE_ID, $imageId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreStockPeer::IMAGE_ID, $imageId, $comparison);
    }

    /**
     * Filter the query on the stock_value column
     *
     * Example usage:
     * <code>
     * $query->filterByStockValue(1234); // WHERE stock_value = 1234
     * $query->filterByStockValue(array(12, 34)); // WHERE stock_value IN (12, 34)
     * $query->filterByStockValue(array('min' => 12)); // WHERE stock_value >= 12
     * $query->filterByStockValue(array('max' => 12)); // WHERE stock_value <= 12
     * </code>
     *
     * @param     mixed $stockValue The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function filterByStockValue($stockValue = null, $comparison = null)
    {
        if (is_array($stockValue)) {
            $useMinMax = false;
            if (isset($stockValue['min'])) {
                $this->addUsingAlias(StoreStockPeer::STOCK_VALUE, $stockValue['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stockValue['max'])) {
                $this->addUsingAlias(StoreStockPeer::STOCK_VALUE, $stockValue['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreStockPeer::STOCK_VALUE, $stockValue, $comparison);
    }

    /**
     * Filter the query on the stock_min column
     *
     * Example usage:
     * <code>
     * $query->filterByStockMin(1234); // WHERE stock_min = 1234
     * $query->filterByStockMin(array(12, 34)); // WHERE stock_min IN (12, 34)
     * $query->filterByStockMin(array('min' => 12)); // WHERE stock_min >= 12
     * $query->filterByStockMin(array('max' => 12)); // WHERE stock_min <= 12
     * </code>
     *
     * @param     mixed $stockMin The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function filterByStockMin($stockMin = null, $comparison = null)
    {
        if (is_array($stockMin)) {
            $useMinMax = false;
            if (isset($stockMin['min'])) {
                $this->addUsingAlias(StoreStockPeer::STOCK_MIN, $stockMin['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stockMin['max'])) {
                $this->addUsingAlias(StoreStockPeer::STOCK_MIN, $stockMin['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreStockPeer::STOCK_MIN, $stockMin, $comparison);
    }

    /**
     * Filter the query on the stock_max column
     *
     * Example usage:
     * <code>
     * $query->filterByStockMax(1234); // WHERE stock_max = 1234
     * $query->filterByStockMax(array(12, 34)); // WHERE stock_max IN (12, 34)
     * $query->filterByStockMax(array('min' => 12)); // WHERE stock_max >= 12
     * $query->filterByStockMax(array('max' => 12)); // WHERE stock_max <= 12
     * </code>
     *
     * @param     mixed $stockMax The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function filterByStockMax($stockMax = null, $comparison = null)
    {
        if (is_array($stockMax)) {
            $useMinMax = false;
            if (isset($stockMax['min'])) {
                $this->addUsingAlias(StoreStockPeer::STOCK_MAX, $stockMax['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stockMax['max'])) {
                $this->addUsingAlias(StoreStockPeer::STOCK_MAX, $stockMax['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreStockPeer::STOCK_MAX, $stockMax, $comparison);
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
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(StoreStockPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(StoreStockPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreStockPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(StoreStockPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(StoreStockPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreStockPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Store object
     *
     * @param   Store|PropelObjectCollection $store The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreStockQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStore($store, $comparison = null)
    {
        if ($store instanceof Store) {
            return $this
                ->addUsingAlias(StoreStockPeer::STORE_ID, $store->getId(), $comparison);
        } elseif ($store instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StoreStockPeer::STORE_ID, $store->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return StoreStockQuery The current query, for fluid interface
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
     * Filter the query by a related StockImage object
     *
     * @param   StockImage|PropelObjectCollection $stockImage The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StoreStockQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStockImage($stockImage, $comparison = null)
    {
        if ($stockImage instanceof StockImage) {
            return $this
                ->addUsingAlias(StoreStockPeer::IMAGE_ID, $stockImage->getId(), $comparison);
        } elseif ($stockImage instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(StoreStockPeer::IMAGE_ID, $stockImage->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByStockImage() only accepts arguments of type StockImage or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StockImage relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function joinStockImage($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StockImage');

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
            $this->addJoinObject($join, 'StockImage');
        }

        return $this;
    }

    /**
     * Use the StockImage relation StockImage object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StockImageQuery A secondary query class using the current class as primary query
     */
    public function useStockImageQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStockImage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StockImage', '\StoreBundle\Model\StockImageQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   StoreStock $storeStock Object to remove from the list of results
     *
     * @return StoreStockQuery The current query, for fluid interface
     */
    public function prune($storeStock = null)
    {
        if ($storeStock) {
            $this->addUsingAlias(StoreStockPeer::ID, $storeStock->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     StoreStockQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(StoreStockPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     StoreStockQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(StoreStockPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     StoreStockQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(StoreStockPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     StoreStockQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(StoreStockPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     StoreStockQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(StoreStockPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     StoreStockQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(StoreStockPeer::CREATED_AT);
    }
}
