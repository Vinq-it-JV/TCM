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
use StoreBundle\Model\StockImagePeer;
use StoreBundle\Model\StockImageQuery;
use StoreBundle\Model\StoreStock;

/**
 * @method StockImageQuery orderById($order = Criteria::ASC) Order by the id column
 * @method StockImageQuery orderByUid($order = Criteria::ASC) Order by the uid column
 * @method StockImageQuery orderByOriginalName($order = Criteria::ASC) Order by the original_name column
 * @method StockImageQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method StockImageQuery orderByLinkUrl($order = Criteria::ASC) Order by the link_url column
 * @method StockImageQuery orderByFilename($order = Criteria::ASC) Order by the filename column
 * @method StockImageQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method StockImageQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method StockImageQuery groupById() Group by the id column
 * @method StockImageQuery groupByUid() Group by the uid column
 * @method StockImageQuery groupByOriginalName() Group by the original_name column
 * @method StockImageQuery groupByName() Group by the name column
 * @method StockImageQuery groupByLinkUrl() Group by the link_url column
 * @method StockImageQuery groupByFilename() Group by the filename column
 * @method StockImageQuery groupByCreatedAt() Group by the created_at column
 * @method StockImageQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method StockImageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method StockImageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method StockImageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method StockImageQuery leftJoinStoreStock($relationAlias = null) Adds a LEFT JOIN clause to the query using the StoreStock relation
 * @method StockImageQuery rightJoinStoreStock($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StoreStock relation
 * @method StockImageQuery innerJoinStoreStock($relationAlias = null) Adds a INNER JOIN clause to the query using the StoreStock relation
 *
 * @method StockImage findOne(PropelPDO $con = null) Return the first StockImage matching the query
 * @method StockImage findOneOrCreate(PropelPDO $con = null) Return the first StockImage matching the query, or a new StockImage object populated from the query conditions when no match is found
 *
 * @method StockImage findOneByUid(string $uid) Return the first StockImage filtered by the uid column
 * @method StockImage findOneByOriginalName(string $original_name) Return the first StockImage filtered by the original_name column
 * @method StockImage findOneByName(string $name) Return the first StockImage filtered by the name column
 * @method StockImage findOneByLinkUrl(string $link_url) Return the first StockImage filtered by the link_url column
 * @method StockImage findOneByFilename(string $filename) Return the first StockImage filtered by the filename column
 * @method StockImage findOneByCreatedAt(string $created_at) Return the first StockImage filtered by the created_at column
 * @method StockImage findOneByUpdatedAt(string $updated_at) Return the first StockImage filtered by the updated_at column
 *
 * @method array findById(int $id) Return StockImage objects filtered by the id column
 * @method array findByUid(string $uid) Return StockImage objects filtered by the uid column
 * @method array findByOriginalName(string $original_name) Return StockImage objects filtered by the original_name column
 * @method array findByName(string $name) Return StockImage objects filtered by the name column
 * @method array findByLinkUrl(string $link_url) Return StockImage objects filtered by the link_url column
 * @method array findByFilename(string $filename) Return StockImage objects filtered by the filename column
 * @method array findByCreatedAt(string $created_at) Return StockImage objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return StockImage objects filtered by the updated_at column
 */
abstract class BaseStockImageQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseStockImageQuery object.
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
            $modelName = 'StoreBundle\\Model\\StockImage';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new StockImageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   StockImageQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return StockImageQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof StockImageQuery) {
            return $criteria;
        }
        $query = new StockImageQuery(null, null, $modelAlias);

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
     * @return   StockImage|StockImage[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = StockImagePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(StockImagePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 StockImage A model object, or null if the key is not found
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
     * @return                 StockImage A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uid`, `original_name`, `name`, `link_url`, `filename`, `created_at`, `updated_at` FROM `stock_image` WHERE `id` = :p0';
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
            $obj = new StockImage();
            $obj->hydrate($row);
            StockImagePeer::addInstanceToPool($obj, (string) $key);
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
     * @return StockImage|StockImage[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|StockImage[]|mixed the list of results, formatted by the current formatter
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
     * @return StockImageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(StockImagePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return StockImageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(StockImagePeer::ID, $keys, Criteria::IN);
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
     * @return StockImageQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(StockImagePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(StockImagePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockImagePeer::ID, $id, $comparison);
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
     * @return StockImageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(StockImagePeer::UID, $uid, $comparison);
    }

    /**
     * Filter the query on the original_name column
     *
     * Example usage:
     * <code>
     * $query->filterByOriginalName('fooValue');   // WHERE original_name = 'fooValue'
     * $query->filterByOriginalName('%fooValue%'); // WHERE original_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $originalName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StockImageQuery The current query, for fluid interface
     */
    public function filterByOriginalName($originalName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($originalName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $originalName)) {
                $originalName = str_replace('*', '%', $originalName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StockImagePeer::ORIGINAL_NAME, $originalName, $comparison);
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
     * @return StockImageQuery The current query, for fluid interface
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

        return $this->addUsingAlias(StockImagePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the link_url column
     *
     * Example usage:
     * <code>
     * $query->filterByLinkUrl('fooValue');   // WHERE link_url = 'fooValue'
     * $query->filterByLinkUrl('%fooValue%'); // WHERE link_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $linkUrl The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StockImageQuery The current query, for fluid interface
     */
    public function filterByLinkUrl($linkUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($linkUrl)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $linkUrl)) {
                $linkUrl = str_replace('*', '%', $linkUrl);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StockImagePeer::LINK_URL, $linkUrl, $comparison);
    }

    /**
     * Filter the query on the filename column
     *
     * Example usage:
     * <code>
     * $query->filterByFilename('fooValue');   // WHERE filename = 'fooValue'
     * $query->filterByFilename('%fooValue%'); // WHERE filename LIKE '%fooValue%'
     * </code>
     *
     * @param     string $filename The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return StockImageQuery The current query, for fluid interface
     */
    public function filterByFilename($filename = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($filename)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $filename)) {
                $filename = str_replace('*', '%', $filename);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StockImagePeer::FILENAME, $filename, $comparison);
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
     * @return StockImageQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(StockImagePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(StockImagePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockImagePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return StockImageQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(StockImagePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(StockImagePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockImagePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related StoreStock object
     *
     * @param   StoreStock|PropelObjectCollection $storeStock  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 StockImageQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStoreStock($storeStock, $comparison = null)
    {
        if ($storeStock instanceof StoreStock) {
            return $this
                ->addUsingAlias(StockImagePeer::ID, $storeStock->getImageId(), $comparison);
        } elseif ($storeStock instanceof PropelObjectCollection) {
            return $this
                ->useStoreStockQuery()
                ->filterByPrimaryKeys($storeStock->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStoreStock() only accepts arguments of type StoreStock or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StoreStock relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return StockImageQuery The current query, for fluid interface
     */
    public function joinStoreStock($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StoreStock');

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
            $this->addJoinObject($join, 'StoreStock');
        }

        return $this;
    }

    /**
     * Use the StoreStock relation StoreStock object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreStockQuery A secondary query class using the current class as primary query
     */
    public function useStoreStockQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStoreStock($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StoreStock', '\StoreBundle\Model\StoreStockQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   StockImage $stockImage Object to remove from the list of results
     *
     * @return StockImageQuery The current query, for fluid interface
     */
    public function prune($stockImage = null)
    {
        if ($stockImage) {
            $this->addUsingAlias(StockImagePeer::ID, $stockImage->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     StockImageQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(StockImagePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     StockImageQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(StockImagePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     StockImageQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(StockImagePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     StockImageQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(StockImagePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     StockImageQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(StockImagePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     StockImageQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(StockImagePeer::CREATED_AT);
    }
}
