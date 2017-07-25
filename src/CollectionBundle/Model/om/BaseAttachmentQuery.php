<?php

namespace CollectionBundle\Model\om;

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
use CollectionBundle\Model\Attachment;
use CollectionBundle\Model\AttachmentPeer;
use CollectionBundle\Model\AttachmentQuery;
use CollectionBundle\Model\Collection;
use CollectionBundle\Model\CollectionAttachment;

/**
 * @method AttachmentQuery orderById($order = Criteria::ASC) Order by the id column
 * @method AttachmentQuery orderByUid($order = Criteria::ASC) Order by the uid column
 * @method AttachmentQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method AttachmentQuery orderByPosition($order = Criteria::ASC) Order by the position column
 * @method AttachmentQuery orderByOriginalName($order = Criteria::ASC) Order by the original_name column
 * @method AttachmentQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method AttachmentQuery orderByLinkUrl($order = Criteria::ASC) Order by the link_url column
 * @method AttachmentQuery orderByFilename($order = Criteria::ASC) Order by the filename column
 * @method AttachmentQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method AttachmentQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method AttachmentQuery groupById() Group by the id column
 * @method AttachmentQuery groupByUid() Group by the uid column
 * @method AttachmentQuery groupByType() Group by the type column
 * @method AttachmentQuery groupByPosition() Group by the position column
 * @method AttachmentQuery groupByOriginalName() Group by the original_name column
 * @method AttachmentQuery groupByName() Group by the name column
 * @method AttachmentQuery groupByLinkUrl() Group by the link_url column
 * @method AttachmentQuery groupByFilename() Group by the filename column
 * @method AttachmentQuery groupByCreatedAt() Group by the created_at column
 * @method AttachmentQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method AttachmentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method AttachmentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method AttachmentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method AttachmentQuery leftJoinCollectionAttachment($relationAlias = null) Adds a LEFT JOIN clause to the query using the CollectionAttachment relation
 * @method AttachmentQuery rightJoinCollectionAttachment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CollectionAttachment relation
 * @method AttachmentQuery innerJoinCollectionAttachment($relationAlias = null) Adds a INNER JOIN clause to the query using the CollectionAttachment relation
 *
 * @method Attachment findOne(PropelPDO $con = null) Return the first Attachment matching the query
 * @method Attachment findOneOrCreate(PropelPDO $con = null) Return the first Attachment matching the query, or a new Attachment object populated from the query conditions when no match is found
 *
 * @method Attachment findOneByUid(string $uid) Return the first Attachment filtered by the uid column
 * @method Attachment findOneByType(int $type) Return the first Attachment filtered by the type column
 * @method Attachment findOneByPosition(int $position) Return the first Attachment filtered by the position column
 * @method Attachment findOneByOriginalName(string $original_name) Return the first Attachment filtered by the original_name column
 * @method Attachment findOneByName(string $name) Return the first Attachment filtered by the name column
 * @method Attachment findOneByLinkUrl(string $link_url) Return the first Attachment filtered by the link_url column
 * @method Attachment findOneByFilename(string $filename) Return the first Attachment filtered by the filename column
 * @method Attachment findOneByCreatedAt(string $created_at) Return the first Attachment filtered by the created_at column
 * @method Attachment findOneByUpdatedAt(string $updated_at) Return the first Attachment filtered by the updated_at column
 *
 * @method array findById(int $id) Return Attachment objects filtered by the id column
 * @method array findByUid(string $uid) Return Attachment objects filtered by the uid column
 * @method array findByType(int $type) Return Attachment objects filtered by the type column
 * @method array findByPosition(int $position) Return Attachment objects filtered by the position column
 * @method array findByOriginalName(string $original_name) Return Attachment objects filtered by the original_name column
 * @method array findByName(string $name) Return Attachment objects filtered by the name column
 * @method array findByLinkUrl(string $link_url) Return Attachment objects filtered by the link_url column
 * @method array findByFilename(string $filename) Return Attachment objects filtered by the filename column
 * @method array findByCreatedAt(string $created_at) Return Attachment objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Attachment objects filtered by the updated_at column
 */
abstract class BaseAttachmentQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseAttachmentQuery object.
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
            $modelName = 'CollectionBundle\\Model\\Attachment';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new AttachmentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   AttachmentQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return AttachmentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof AttachmentQuery) {
            return $criteria;
        }
        $query = new AttachmentQuery(null, null, $modelAlias);

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
     * @return   Attachment|Attachment[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AttachmentPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(AttachmentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Attachment A model object, or null if the key is not found
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
     * @return                 Attachment A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uid`, `type`, `position`, `original_name`, `name`, `link_url`, `filename`, `created_at`, `updated_at` FROM `attachment` WHERE `id` = :p0';
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
            $obj = new Attachment();
            $obj->hydrate($row);
            AttachmentPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Attachment|Attachment[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Attachment[]|mixed the list of results, formatted by the current formatter
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
     * @return AttachmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AttachmentPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return AttachmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AttachmentPeer::ID, $keys, Criteria::IN);
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
     * @return AttachmentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AttachmentPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AttachmentPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AttachmentPeer::ID, $id, $comparison);
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
     * @return AttachmentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AttachmentPeer::UID, $uid, $comparison);
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
     * @return AttachmentQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (is_array($type)) {
            $useMinMax = false;
            if (isset($type['min'])) {
                $this->addUsingAlias(AttachmentPeer::TYPE, $type['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($type['max'])) {
                $this->addUsingAlias(AttachmentPeer::TYPE, $type['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AttachmentPeer::TYPE, $type, $comparison);
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
     * @return AttachmentQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (is_array($position)) {
            $useMinMax = false;
            if (isset($position['min'])) {
                $this->addUsingAlias(AttachmentPeer::POSITION, $position['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($position['max'])) {
                $this->addUsingAlias(AttachmentPeer::POSITION, $position['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AttachmentPeer::POSITION, $position, $comparison);
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
     * @return AttachmentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AttachmentPeer::ORIGINAL_NAME, $originalName, $comparison);
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
     * @return AttachmentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AttachmentPeer::NAME, $name, $comparison);
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
     * @return AttachmentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AttachmentPeer::LINK_URL, $linkUrl, $comparison);
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
     * @return AttachmentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AttachmentPeer::FILENAME, $filename, $comparison);
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
     * @return AttachmentQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(AttachmentPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(AttachmentPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AttachmentPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return AttachmentQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(AttachmentPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(AttachmentPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AttachmentPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related CollectionAttachment object
     *
     * @param   CollectionAttachment|PropelObjectCollection $collectionAttachment  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AttachmentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCollectionAttachment($collectionAttachment, $comparison = null)
    {
        if ($collectionAttachment instanceof CollectionAttachment) {
            return $this
                ->addUsingAlias(AttachmentPeer::ID, $collectionAttachment->getAttachmentId(), $comparison);
        } elseif ($collectionAttachment instanceof PropelObjectCollection) {
            return $this
                ->useCollectionAttachmentQuery()
                ->filterByPrimaryKeys($collectionAttachment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCollectionAttachment() only accepts arguments of type CollectionAttachment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CollectionAttachment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AttachmentQuery The current query, for fluid interface
     */
    public function joinCollectionAttachment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CollectionAttachment');

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
            $this->addJoinObject($join, 'CollectionAttachment');
        }

        return $this;
    }

    /**
     * Use the CollectionAttachment relation CollectionAttachment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CollectionBundle\Model\CollectionAttachmentQuery A secondary query class using the current class as primary query
     */
    public function useCollectionAttachmentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCollectionAttachment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CollectionAttachment', '\CollectionBundle\Model\CollectionAttachmentQuery');
    }

    /**
     * Filter the query by a related Collection object
     * using the collection_attachment table as cross reference
     *
     * @param   Collection $collection the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AttachmentQuery The current query, for fluid interface
     */
    public function filterByCollection($collection, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCollectionAttachmentQuery()
            ->filterByCollection($collection, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   Attachment $attachment Object to remove from the list of results
     *
     * @return AttachmentQuery The current query, for fluid interface
     */
    public function prune($attachment = null)
    {
        if ($attachment) {
            $this->addUsingAlias(AttachmentPeer::ID, $attachment->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     AttachmentQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(AttachmentPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     AttachmentQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(AttachmentPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     AttachmentQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(AttachmentPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     AttachmentQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(AttachmentPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     AttachmentQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(AttachmentPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     AttachmentQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(AttachmentPeer::CREATED_AT);
    }
}
