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
use CollectionBundle\Model\Collection;
use CollectionBundle\Model\CollectionAttachment;
use CollectionBundle\Model\CollectionAttachmentPeer;
use CollectionBundle\Model\CollectionAttachmentQuery;

/**
 * @method CollectionAttachmentQuery orderByCollectionId($order = Criteria::ASC) Order by the collection_id column
 * @method CollectionAttachmentQuery orderByAttachmentId($order = Criteria::ASC) Order by the attachment_id column
 *
 * @method CollectionAttachmentQuery groupByCollectionId() Group by the collection_id column
 * @method CollectionAttachmentQuery groupByAttachmentId() Group by the attachment_id column
 *
 * @method CollectionAttachmentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CollectionAttachmentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CollectionAttachmentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CollectionAttachmentQuery leftJoinCollection($relationAlias = null) Adds a LEFT JOIN clause to the query using the Collection relation
 * @method CollectionAttachmentQuery rightJoinCollection($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Collection relation
 * @method CollectionAttachmentQuery innerJoinCollection($relationAlias = null) Adds a INNER JOIN clause to the query using the Collection relation
 *
 * @method CollectionAttachmentQuery leftJoinAttachment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Attachment relation
 * @method CollectionAttachmentQuery rightJoinAttachment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Attachment relation
 * @method CollectionAttachmentQuery innerJoinAttachment($relationAlias = null) Adds a INNER JOIN clause to the query using the Attachment relation
 *
 * @method CollectionAttachment findOne(PropelPDO $con = null) Return the first CollectionAttachment matching the query
 * @method CollectionAttachment findOneOrCreate(PropelPDO $con = null) Return the first CollectionAttachment matching the query, or a new CollectionAttachment object populated from the query conditions when no match is found
 *
 * @method CollectionAttachment findOneByCollectionId(int $collection_id) Return the first CollectionAttachment filtered by the collection_id column
 * @method CollectionAttachment findOneByAttachmentId(int $attachment_id) Return the first CollectionAttachment filtered by the attachment_id column
 *
 * @method array findByCollectionId(int $collection_id) Return CollectionAttachment objects filtered by the collection_id column
 * @method array findByAttachmentId(int $attachment_id) Return CollectionAttachment objects filtered by the attachment_id column
 */
abstract class BaseCollectionAttachmentQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCollectionAttachmentQuery object.
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
            $modelName = 'CollectionBundle\\Model\\CollectionAttachment';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CollectionAttachmentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CollectionAttachmentQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CollectionAttachmentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CollectionAttachmentQuery) {
            return $criteria;
        }
        $query = new CollectionAttachmentQuery(null, null, $modelAlias);

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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$collection_id, $attachment_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   CollectionAttachment|CollectionAttachment[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CollectionAttachmentPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CollectionAttachmentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 CollectionAttachment A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `collection_id`, `attachment_id` FROM `collection_attachment` WHERE `collection_id` = :p0 AND `attachment_id` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new CollectionAttachment();
            $obj->hydrate($row);
            CollectionAttachmentPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return CollectionAttachment|CollectionAttachment[]|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|CollectionAttachment[]|mixed the list of results, formatted by the current formatter
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
     * @return CollectionAttachmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(CollectionAttachmentPeer::COLLECTION_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(CollectionAttachmentPeer::ATTACHMENT_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CollectionAttachmentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(CollectionAttachmentPeer::COLLECTION_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(CollectionAttachmentPeer::ATTACHMENT_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the collection_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCollectionId(1234); // WHERE collection_id = 1234
     * $query->filterByCollectionId(array(12, 34)); // WHERE collection_id IN (12, 34)
     * $query->filterByCollectionId(array('min' => 12)); // WHERE collection_id >= 12
     * $query->filterByCollectionId(array('max' => 12)); // WHERE collection_id <= 12
     * </code>
     *
     * @see       filterByCollection()
     *
     * @param     mixed $collectionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CollectionAttachmentQuery The current query, for fluid interface
     */
    public function filterByCollectionId($collectionId = null, $comparison = null)
    {
        if (is_array($collectionId)) {
            $useMinMax = false;
            if (isset($collectionId['min'])) {
                $this->addUsingAlias(CollectionAttachmentPeer::COLLECTION_ID, $collectionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($collectionId['max'])) {
                $this->addUsingAlias(CollectionAttachmentPeer::COLLECTION_ID, $collectionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CollectionAttachmentPeer::COLLECTION_ID, $collectionId, $comparison);
    }

    /**
     * Filter the query on the attachment_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAttachmentId(1234); // WHERE attachment_id = 1234
     * $query->filterByAttachmentId(array(12, 34)); // WHERE attachment_id IN (12, 34)
     * $query->filterByAttachmentId(array('min' => 12)); // WHERE attachment_id >= 12
     * $query->filterByAttachmentId(array('max' => 12)); // WHERE attachment_id <= 12
     * </code>
     *
     * @see       filterByAttachment()
     *
     * @param     mixed $attachmentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CollectionAttachmentQuery The current query, for fluid interface
     */
    public function filterByAttachmentId($attachmentId = null, $comparison = null)
    {
        if (is_array($attachmentId)) {
            $useMinMax = false;
            if (isset($attachmentId['min'])) {
                $this->addUsingAlias(CollectionAttachmentPeer::ATTACHMENT_ID, $attachmentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($attachmentId['max'])) {
                $this->addUsingAlias(CollectionAttachmentPeer::ATTACHMENT_ID, $attachmentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CollectionAttachmentPeer::ATTACHMENT_ID, $attachmentId, $comparison);
    }

    /**
     * Filter the query by a related Collection object
     *
     * @param   Collection|PropelObjectCollection $collection The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CollectionAttachmentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCollection($collection, $comparison = null)
    {
        if ($collection instanceof Collection) {
            return $this
                ->addUsingAlias(CollectionAttachmentPeer::COLLECTION_ID, $collection->getId(), $comparison);
        } elseif ($collection instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CollectionAttachmentPeer::COLLECTION_ID, $collection->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCollection() only accepts arguments of type Collection or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Collection relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CollectionAttachmentQuery The current query, for fluid interface
     */
    public function joinCollection($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Collection');

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
            $this->addJoinObject($join, 'Collection');
        }

        return $this;
    }

    /**
     * Use the Collection relation Collection object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CollectionBundle\Model\CollectionQuery A secondary query class using the current class as primary query
     */
    public function useCollectionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCollection($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Collection', '\CollectionBundle\Model\CollectionQuery');
    }

    /**
     * Filter the query by a related Attachment object
     *
     * @param   Attachment|PropelObjectCollection $attachment The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CollectionAttachmentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAttachment($attachment, $comparison = null)
    {
        if ($attachment instanceof Attachment) {
            return $this
                ->addUsingAlias(CollectionAttachmentPeer::ATTACHMENT_ID, $attachment->getId(), $comparison);
        } elseif ($attachment instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CollectionAttachmentPeer::ATTACHMENT_ID, $attachment->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAttachment() only accepts arguments of type Attachment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Attachment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CollectionAttachmentQuery The current query, for fluid interface
     */
    public function joinAttachment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Attachment');

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
            $this->addJoinObject($join, 'Attachment');
        }

        return $this;
    }

    /**
     * Use the Attachment relation Attachment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CollectionBundle\Model\AttachmentQuery A secondary query class using the current class as primary query
     */
    public function useAttachmentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAttachment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Attachment', '\CollectionBundle\Model\AttachmentQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   CollectionAttachment $collectionAttachment Object to remove from the list of results
     *
     * @return CollectionAttachmentQuery The current query, for fluid interface
     */
    public function prune($collectionAttachment = null)
    {
        if ($collectionAttachment) {
            $this->addCond('pruneCond0', $this->getAliasedColName(CollectionAttachmentPeer::COLLECTION_ID), $collectionAttachment->getCollectionId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(CollectionAttachmentPeer::ATTACHMENT_ID), $collectionAttachment->getAttachmentId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
