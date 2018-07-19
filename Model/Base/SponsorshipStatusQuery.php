<?php

namespace ParainageSimple\Model\Base;

use \Exception;
use \PDO;
use ParainageSimple\Model\SponsorshipStatus as ChildSponsorshipStatus;
use ParainageSimple\Model\SponsorshipStatusI18nQuery as ChildSponsorshipStatusI18nQuery;
use ParainageSimple\Model\SponsorshipStatusQuery as ChildSponsorshipStatusQuery;
use ParainageSimple\Model\Map\SponsorshipStatusTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'sponsorship_status' table.
 *
 * Sponsorship Status
 *
 * @method     ChildSponsorshipStatusQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSponsorshipStatusQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildSponsorshipStatusQuery orderByColor($order = Criteria::ASC) Order by the color column
 * @method     ChildSponsorshipStatusQuery orderByPosition($order = Criteria::ASC) Order by the position column
 * @method     ChildSponsorshipStatusQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildSponsorshipStatusQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildSponsorshipStatusQuery groupById() Group by the id column
 * @method     ChildSponsorshipStatusQuery groupByCode() Group by the code column
 * @method     ChildSponsorshipStatusQuery groupByColor() Group by the color column
 * @method     ChildSponsorshipStatusQuery groupByPosition() Group by the position column
 * @method     ChildSponsorshipStatusQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildSponsorshipStatusQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildSponsorshipStatusQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSponsorshipStatusQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSponsorshipStatusQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSponsorshipStatusQuery leftJoinSponsorship($relationAlias = null) Adds a LEFT JOIN clause to the query using the Sponsorship relation
 * @method     ChildSponsorshipStatusQuery rightJoinSponsorship($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Sponsorship relation
 * @method     ChildSponsorshipStatusQuery innerJoinSponsorship($relationAlias = null) Adds a INNER JOIN clause to the query using the Sponsorship relation
 *
 * @method     ChildSponsorshipStatusQuery leftJoinSponsorshipStatusI18n($relationAlias = null) Adds a LEFT JOIN clause to the query using the SponsorshipStatusI18n relation
 * @method     ChildSponsorshipStatusQuery rightJoinSponsorshipStatusI18n($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SponsorshipStatusI18n relation
 * @method     ChildSponsorshipStatusQuery innerJoinSponsorshipStatusI18n($relationAlias = null) Adds a INNER JOIN clause to the query using the SponsorshipStatusI18n relation
 *
 * @method     ChildSponsorshipStatus findOne(ConnectionInterface $con = null) Return the first ChildSponsorshipStatus matching the query
 * @method     ChildSponsorshipStatus findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSponsorshipStatus matching the query, or a new ChildSponsorshipStatus object populated from the query conditions when no match is found
 *
 * @method     ChildSponsorshipStatus findOneById(int $id) Return the first ChildSponsorshipStatus filtered by the id column
 * @method     ChildSponsorshipStatus findOneByCode(string $code) Return the first ChildSponsorshipStatus filtered by the code column
 * @method     ChildSponsorshipStatus findOneByColor(string $color) Return the first ChildSponsorshipStatus filtered by the color column
 * @method     ChildSponsorshipStatus findOneByPosition(int $position) Return the first ChildSponsorshipStatus filtered by the position column
 * @method     ChildSponsorshipStatus findOneByCreatedAt(string $created_at) Return the first ChildSponsorshipStatus filtered by the created_at column
 * @method     ChildSponsorshipStatus findOneByUpdatedAt(string $updated_at) Return the first ChildSponsorshipStatus filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildSponsorshipStatus objects filtered by the id column
 * @method     array findByCode(string $code) Return ChildSponsorshipStatus objects filtered by the code column
 * @method     array findByColor(string $color) Return ChildSponsorshipStatus objects filtered by the color column
 * @method     array findByPosition(int $position) Return ChildSponsorshipStatus objects filtered by the position column
 * @method     array findByCreatedAt(string $created_at) Return ChildSponsorshipStatus objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildSponsorshipStatus objects filtered by the updated_at column
 *
 */
abstract class SponsorshipStatusQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \ParainageSimple\Model\Base\SponsorshipStatusQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\ParainageSimple\\Model\\SponsorshipStatus', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSponsorshipStatusQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSponsorshipStatusQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \ParainageSimple\Model\SponsorshipStatusQuery) {
            return $criteria;
        }
        $query = new \ParainageSimple\Model\SponsorshipStatusQuery();
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
     * @return ChildSponsorshipStatus|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SponsorshipStatusTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SponsorshipStatusTableMap::DATABASE_NAME);
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
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildSponsorshipStatus A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, CODE, COLOR, POSITION, CREATED_AT, UPDATED_AT FROM sponsorship_status WHERE ID = :p0';
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
            $obj = new ChildSponsorshipStatus();
            $obj->hydrate($row);
            SponsorshipStatusTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSponsorshipStatus|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
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
    public function findPks($keys, $con = null)
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
     * @return ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SponsorshipStatusTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SponsorshipStatusTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SponsorshipStatusTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SponsorshipStatusTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SponsorshipStatusTableMap::ID, $id, $comparison);
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
     * @return ChildSponsorshipStatusQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SponsorshipStatusTableMap::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the color column
     *
     * Example usage:
     * <code>
     * $query->filterByColor('fooValue');   // WHERE color = 'fooValue'
     * $query->filterByColor('%fooValue%'); // WHERE color LIKE '%fooValue%'
     * </code>
     *
     * @param     string $color The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function filterByColor($color = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($color)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $color)) {
                $color = str_replace('*', '%', $color);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SponsorshipStatusTableMap::COLOR, $color, $comparison);
    }

    /**
     * Filter the query on the position column
     *
     * Example usage:
     * <code>
     * $query->filterByPosition(1234); // WHERE position = 1234
     * $query->filterByPosition(array(12, 34)); // WHERE position IN (12, 34)
     * $query->filterByPosition(array('min' => 12)); // WHERE position > 12
     * </code>
     *
     * @param     mixed $position The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (is_array($position)) {
            $useMinMax = false;
            if (isset($position['min'])) {
                $this->addUsingAlias(SponsorshipStatusTableMap::POSITION, $position['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($position['max'])) {
                $this->addUsingAlias(SponsorshipStatusTableMap::POSITION, $position['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SponsorshipStatusTableMap::POSITION, $position, $comparison);
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
     * @return ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SponsorshipStatusTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SponsorshipStatusTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SponsorshipStatusTableMap::CREATED_AT, $createdAt, $comparison);
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
     * @return ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SponsorshipStatusTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SponsorshipStatusTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SponsorshipStatusTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \ParainageSimple\Model\Sponsorship object
     *
     * @param \ParainageSimple\Model\Sponsorship|ObjectCollection $sponsorship  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function filterBySponsorship($sponsorship, $comparison = null)
    {
        if ($sponsorship instanceof \ParainageSimple\Model\Sponsorship) {
            return $this
                ->addUsingAlias(SponsorshipStatusTableMap::ID, $sponsorship->getStatus(), $comparison);
        } elseif ($sponsorship instanceof ObjectCollection) {
            return $this
                ->useSponsorshipQuery()
                ->filterByPrimaryKeys($sponsorship->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySponsorship() only accepts arguments of type \ParainageSimple\Model\Sponsorship or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Sponsorship relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function joinSponsorship($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Sponsorship');

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
            $this->addJoinObject($join, 'Sponsorship');
        }

        return $this;
    }

    /**
     * Use the Sponsorship relation Sponsorship object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \ParainageSimple\Model\SponsorshipQuery A secondary query class using the current class as primary query
     */
    public function useSponsorshipQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSponsorship($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Sponsorship', '\ParainageSimple\Model\SponsorshipQuery');
    }

    /**
     * Filter the query by a related \ParainageSimple\Model\SponsorshipStatusI18n object
     *
     * @param \ParainageSimple\Model\SponsorshipStatusI18n|ObjectCollection $sponsorshipStatusI18n  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function filterBySponsorshipStatusI18n($sponsorshipStatusI18n, $comparison = null)
    {
        if ($sponsorshipStatusI18n instanceof \ParainageSimple\Model\SponsorshipStatusI18n) {
            return $this
                ->addUsingAlias(SponsorshipStatusTableMap::ID, $sponsorshipStatusI18n->getId(), $comparison);
        } elseif ($sponsorshipStatusI18n instanceof ObjectCollection) {
            return $this
                ->useSponsorshipStatusI18nQuery()
                ->filterByPrimaryKeys($sponsorshipStatusI18n->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySponsorshipStatusI18n() only accepts arguments of type \ParainageSimple\Model\SponsorshipStatusI18n or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SponsorshipStatusI18n relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function joinSponsorshipStatusI18n($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SponsorshipStatusI18n');

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
            $this->addJoinObject($join, 'SponsorshipStatusI18n');
        }

        return $this;
    }

    /**
     * Use the SponsorshipStatusI18n relation SponsorshipStatusI18n object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \ParainageSimple\Model\SponsorshipStatusI18nQuery A secondary query class using the current class as primary query
     */
    public function useSponsorshipStatusI18nQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinSponsorshipStatusI18n($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SponsorshipStatusI18n', '\ParainageSimple\Model\SponsorshipStatusI18nQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSponsorshipStatus $sponsorshipStatus Object to remove from the list of results
     *
     * @return ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function prune($sponsorshipStatus = null)
    {
        if ($sponsorshipStatus) {
            $this->addUsingAlias(SponsorshipStatusTableMap::ID, $sponsorshipStatus->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the sponsorship_status table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SponsorshipStatusTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SponsorshipStatusTableMap::clearInstancePool();
            SponsorshipStatusTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildSponsorshipStatus or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildSponsorshipStatus object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SponsorshipStatusTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SponsorshipStatusTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        SponsorshipStatusTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SponsorshipStatusTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SponsorshipStatusTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SponsorshipStatusTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SponsorshipStatusTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SponsorshipStatusTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SponsorshipStatusTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SponsorshipStatusTableMap::CREATED_AT);
    }

    // i18n behavior

    /**
     * Adds a JOIN clause to the query using the i18n relation
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function joinI18n($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $relationName = $relationAlias ? $relationAlias : 'SponsorshipStatusI18n';

        return $this
            ->joinSponsorshipStatusI18n($relationAlias, $joinType)
            ->addJoinCondition($relationName, $relationName . '.Locale = ?', $locale);
    }

    /**
     * Adds a JOIN clause to the query and hydrates the related I18n object.
     * Shortcut for $c->joinI18n($locale)->with()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildSponsorshipStatusQuery The current query, for fluid interface
     */
    public function joinWithI18n($locale = 'en_US', $joinType = Criteria::LEFT_JOIN)
    {
        $this
            ->joinI18n($locale, null, $joinType)
            ->with('SponsorshipStatusI18n');
        $this->with['SponsorshipStatusI18n']->setIsWithOneToMany(false);

        return $this;
    }

    /**
     * Use the I18n relation query object
     *
     * @see       useQuery()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildSponsorshipStatusI18nQuery A secondary query class using the current class as primary query
     */
    public function useI18nQuery($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinI18n($locale, $relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SponsorshipStatusI18n', '\ParainageSimple\Model\SponsorshipStatusI18nQuery');
    }

} // SponsorshipStatusQuery
