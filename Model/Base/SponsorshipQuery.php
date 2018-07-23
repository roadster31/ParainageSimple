<?php

namespace ParainageSimple\Model\Base;

use \Exception;
use \PDO;
use ParainageSimple\Model\Sponsorship as ChildSponsorship;
use ParainageSimple\Model\SponsorshipQuery as ChildSponsorshipQuery;
use ParainageSimple\Model\Map\SponsorshipTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Thelia\Model\Customer;

/**
 * Base class that represents a query for the 'sponsorship' table.
 *
 * Sponsorship Table
 *
 * @method     ChildSponsorshipQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSponsorshipQuery orderBySponsorId($order = Criteria::ASC) Order by the sponsor_id column
 * @method     ChildSponsorshipQuery orderByBeneficiaryId($order = Criteria::ASC) Order by the beneficiary_id column
 * @method     ChildSponsorshipQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildSponsorshipQuery orderByBeneficiaryEmail($order = Criteria::ASC) Order by the beneficiary_email column
 * @method     ChildSponsorshipQuery orderByBeneficiaryFirstname($order = Criteria::ASC) Order by the beneficiary_firstname column
 * @method     ChildSponsorshipQuery orderByBeneficiaryLastname($order = Criteria::ASC) Order by the beneficiary_lastname column
 * @method     ChildSponsorshipQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildSponsorshipQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildSponsorshipQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildSponsorshipQuery groupById() Group by the id column
 * @method     ChildSponsorshipQuery groupBySponsorId() Group by the sponsor_id column
 * @method     ChildSponsorshipQuery groupByBeneficiaryId() Group by the beneficiary_id column
 * @method     ChildSponsorshipQuery groupByCode() Group by the code column
 * @method     ChildSponsorshipQuery groupByBeneficiaryEmail() Group by the beneficiary_email column
 * @method     ChildSponsorshipQuery groupByBeneficiaryFirstname() Group by the beneficiary_firstname column
 * @method     ChildSponsorshipQuery groupByBeneficiaryLastname() Group by the beneficiary_lastname column
 * @method     ChildSponsorshipQuery groupByStatus() Group by the status column
 * @method     ChildSponsorshipQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildSponsorshipQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildSponsorshipQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSponsorshipQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSponsorshipQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSponsorshipQuery leftJoinCustomerRelatedByBeneficiaryId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CustomerRelatedByBeneficiaryId relation
 * @method     ChildSponsorshipQuery rightJoinCustomerRelatedByBeneficiaryId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CustomerRelatedByBeneficiaryId relation
 * @method     ChildSponsorshipQuery innerJoinCustomerRelatedByBeneficiaryId($relationAlias = null) Adds a INNER JOIN clause to the query using the CustomerRelatedByBeneficiaryId relation
 *
 * @method     ChildSponsorshipQuery leftJoinCustomerRelatedBySponsorId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CustomerRelatedBySponsorId relation
 * @method     ChildSponsorshipQuery rightJoinCustomerRelatedBySponsorId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CustomerRelatedBySponsorId relation
 * @method     ChildSponsorshipQuery innerJoinCustomerRelatedBySponsorId($relationAlias = null) Adds a INNER JOIN clause to the query using the CustomerRelatedBySponsorId relation
 *
 * @method     ChildSponsorshipQuery leftJoinSponsorshipStatus($relationAlias = null) Adds a LEFT JOIN clause to the query using the SponsorshipStatus relation
 * @method     ChildSponsorshipQuery rightJoinSponsorshipStatus($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SponsorshipStatus relation
 * @method     ChildSponsorshipQuery innerJoinSponsorshipStatus($relationAlias = null) Adds a INNER JOIN clause to the query using the SponsorshipStatus relation
 *
 * @method     ChildSponsorship findOne(ConnectionInterface $con = null) Return the first ChildSponsorship matching the query
 * @method     ChildSponsorship findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSponsorship matching the query, or a new ChildSponsorship object populated from the query conditions when no match is found
 *
 * @method     ChildSponsorship findOneById(int $id) Return the first ChildSponsorship filtered by the id column
 * @method     ChildSponsorship findOneBySponsorId(int $sponsor_id) Return the first ChildSponsorship filtered by the sponsor_id column
 * @method     ChildSponsorship findOneByBeneficiaryId(int $beneficiary_id) Return the first ChildSponsorship filtered by the beneficiary_id column
 * @method     ChildSponsorship findOneByCode(string $code) Return the first ChildSponsorship filtered by the code column
 * @method     ChildSponsorship findOneByBeneficiaryEmail(string $beneficiary_email) Return the first ChildSponsorship filtered by the beneficiary_email column
 * @method     ChildSponsorship findOneByBeneficiaryFirstname(string $beneficiary_firstname) Return the first ChildSponsorship filtered by the beneficiary_firstname column
 * @method     ChildSponsorship findOneByBeneficiaryLastname(string $beneficiary_lastname) Return the first ChildSponsorship filtered by the beneficiary_lastname column
 * @method     ChildSponsorship findOneByStatus(int $status) Return the first ChildSponsorship filtered by the status column
 * @method     ChildSponsorship findOneByCreatedAt(string $created_at) Return the first ChildSponsorship filtered by the created_at column
 * @method     ChildSponsorship findOneByUpdatedAt(string $updated_at) Return the first ChildSponsorship filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildSponsorship objects filtered by the id column
 * @method     array findBySponsorId(int $sponsor_id) Return ChildSponsorship objects filtered by the sponsor_id column
 * @method     array findByBeneficiaryId(int $beneficiary_id) Return ChildSponsorship objects filtered by the beneficiary_id column
 * @method     array findByCode(string $code) Return ChildSponsorship objects filtered by the code column
 * @method     array findByBeneficiaryEmail(string $beneficiary_email) Return ChildSponsorship objects filtered by the beneficiary_email column
 * @method     array findByBeneficiaryFirstname(string $beneficiary_firstname) Return ChildSponsorship objects filtered by the beneficiary_firstname column
 * @method     array findByBeneficiaryLastname(string $beneficiary_lastname) Return ChildSponsorship objects filtered by the beneficiary_lastname column
 * @method     array findByStatus(int $status) Return ChildSponsorship objects filtered by the status column
 * @method     array findByCreatedAt(string $created_at) Return ChildSponsorship objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildSponsorship objects filtered by the updated_at column
 *
 */
abstract class SponsorshipQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \ParainageSimple\Model\Base\SponsorshipQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\ParainageSimple\\Model\\Sponsorship', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSponsorshipQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSponsorshipQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \ParainageSimple\Model\SponsorshipQuery) {
            return $criteria;
        }
        $query = new \ParainageSimple\Model\SponsorshipQuery();
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
     * @return ChildSponsorship|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SponsorshipTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SponsorshipTableMap::DATABASE_NAME);
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
     * @return   ChildSponsorship A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, SPONSOR_ID, BENEFICIARY_ID, CODE, BENEFICIARY_EMAIL, BENEFICIARY_FIRSTNAME, BENEFICIARY_LASTNAME, STATUS, CREATED_AT, UPDATED_AT FROM sponsorship WHERE ID = :p0';
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
            $obj = new ChildSponsorship();
            $obj->hydrate($row);
            SponsorshipTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSponsorship|array|mixed the result, formatted by the current formatter
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
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SponsorshipTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SponsorshipTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SponsorshipTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SponsorshipTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SponsorshipTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the sponsor_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySponsorId(1234); // WHERE sponsor_id = 1234
     * $query->filterBySponsorId(array(12, 34)); // WHERE sponsor_id IN (12, 34)
     * $query->filterBySponsorId(array('min' => 12)); // WHERE sponsor_id > 12
     * </code>
     *
     * @see       filterByCustomerRelatedBySponsorId()
     *
     * @param     mixed $sponsorId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterBySponsorId($sponsorId = null, $comparison = null)
    {
        if (is_array($sponsorId)) {
            $useMinMax = false;
            if (isset($sponsorId['min'])) {
                $this->addUsingAlias(SponsorshipTableMap::SPONSOR_ID, $sponsorId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sponsorId['max'])) {
                $this->addUsingAlias(SponsorshipTableMap::SPONSOR_ID, $sponsorId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SponsorshipTableMap::SPONSOR_ID, $sponsorId, $comparison);
    }

    /**
     * Filter the query on the beneficiary_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBeneficiaryId(1234); // WHERE beneficiary_id = 1234
     * $query->filterByBeneficiaryId(array(12, 34)); // WHERE beneficiary_id IN (12, 34)
     * $query->filterByBeneficiaryId(array('min' => 12)); // WHERE beneficiary_id > 12
     * </code>
     *
     * @see       filterByCustomerRelatedByBeneficiaryId()
     *
     * @param     mixed $beneficiaryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterByBeneficiaryId($beneficiaryId = null, $comparison = null)
    {
        if (is_array($beneficiaryId)) {
            $useMinMax = false;
            if (isset($beneficiaryId['min'])) {
                $this->addUsingAlias(SponsorshipTableMap::BENEFICIARY_ID, $beneficiaryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($beneficiaryId['max'])) {
                $this->addUsingAlias(SponsorshipTableMap::BENEFICIARY_ID, $beneficiaryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SponsorshipTableMap::BENEFICIARY_ID, $beneficiaryId, $comparison);
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
     * @return ChildSponsorshipQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SponsorshipTableMap::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the beneficiary_email column
     *
     * Example usage:
     * <code>
     * $query->filterByBeneficiaryEmail('fooValue');   // WHERE beneficiary_email = 'fooValue'
     * $query->filterByBeneficiaryEmail('%fooValue%'); // WHERE beneficiary_email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $beneficiaryEmail The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterByBeneficiaryEmail($beneficiaryEmail = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($beneficiaryEmail)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $beneficiaryEmail)) {
                $beneficiaryEmail = str_replace('*', '%', $beneficiaryEmail);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SponsorshipTableMap::BENEFICIARY_EMAIL, $beneficiaryEmail, $comparison);
    }

    /**
     * Filter the query on the beneficiary_firstname column
     *
     * Example usage:
     * <code>
     * $query->filterByBeneficiaryFirstname('fooValue');   // WHERE beneficiary_firstname = 'fooValue'
     * $query->filterByBeneficiaryFirstname('%fooValue%'); // WHERE beneficiary_firstname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $beneficiaryFirstname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterByBeneficiaryFirstname($beneficiaryFirstname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($beneficiaryFirstname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $beneficiaryFirstname)) {
                $beneficiaryFirstname = str_replace('*', '%', $beneficiaryFirstname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SponsorshipTableMap::BENEFICIARY_FIRSTNAME, $beneficiaryFirstname, $comparison);
    }

    /**
     * Filter the query on the beneficiary_lastname column
     *
     * Example usage:
     * <code>
     * $query->filterByBeneficiaryLastname('fooValue');   // WHERE beneficiary_lastname = 'fooValue'
     * $query->filterByBeneficiaryLastname('%fooValue%'); // WHERE beneficiary_lastname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $beneficiaryLastname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterByBeneficiaryLastname($beneficiaryLastname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($beneficiaryLastname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $beneficiaryLastname)) {
                $beneficiaryLastname = str_replace('*', '%', $beneficiaryLastname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SponsorshipTableMap::BENEFICIARY_LASTNAME, $beneficiaryLastname, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(1234); // WHERE status = 1234
     * $query->filterByStatus(array(12, 34)); // WHERE status IN (12, 34)
     * $query->filterByStatus(array('min' => 12)); // WHERE status > 12
     * </code>
     *
     * @see       filterBySponsorshipStatus()
     *
     * @param     mixed $status The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(SponsorshipTableMap::STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(SponsorshipTableMap::STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SponsorshipTableMap::STATUS, $status, $comparison);
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
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SponsorshipTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SponsorshipTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SponsorshipTableMap::CREATED_AT, $createdAt, $comparison);
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
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SponsorshipTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SponsorshipTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SponsorshipTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Thelia\Model\Customer object
     *
     * @param \Thelia\Model\Customer|ObjectCollection $customer The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterByCustomerRelatedByBeneficiaryId($customer, $comparison = null)
    {
        if ($customer instanceof \Thelia\Model\Customer) {
            return $this
                ->addUsingAlias(SponsorshipTableMap::BENEFICIARY_ID, $customer->getId(), $comparison);
        } elseif ($customer instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SponsorshipTableMap::BENEFICIARY_ID, $customer->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCustomerRelatedByBeneficiaryId() only accepts arguments of type \Thelia\Model\Customer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CustomerRelatedByBeneficiaryId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function joinCustomerRelatedByBeneficiaryId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CustomerRelatedByBeneficiaryId');

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
            $this->addJoinObject($join, 'CustomerRelatedByBeneficiaryId');
        }

        return $this;
    }

    /**
     * Use the CustomerRelatedByBeneficiaryId relation Customer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\CustomerQuery A secondary query class using the current class as primary query
     */
    public function useCustomerRelatedByBeneficiaryIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCustomerRelatedByBeneficiaryId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CustomerRelatedByBeneficiaryId', '\Thelia\Model\CustomerQuery');
    }

    /**
     * Filter the query by a related \Thelia\Model\Customer object
     *
     * @param \Thelia\Model\Customer|ObjectCollection $customer The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterByCustomerRelatedBySponsorId($customer, $comparison = null)
    {
        if ($customer instanceof \Thelia\Model\Customer) {
            return $this
                ->addUsingAlias(SponsorshipTableMap::SPONSOR_ID, $customer->getId(), $comparison);
        } elseif ($customer instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SponsorshipTableMap::SPONSOR_ID, $customer->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCustomerRelatedBySponsorId() only accepts arguments of type \Thelia\Model\Customer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CustomerRelatedBySponsorId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function joinCustomerRelatedBySponsorId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CustomerRelatedBySponsorId');

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
            $this->addJoinObject($join, 'CustomerRelatedBySponsorId');
        }

        return $this;
    }

    /**
     * Use the CustomerRelatedBySponsorId relation Customer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\CustomerQuery A secondary query class using the current class as primary query
     */
    public function useCustomerRelatedBySponsorIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCustomerRelatedBySponsorId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CustomerRelatedBySponsorId', '\Thelia\Model\CustomerQuery');
    }

    /**
     * Filter the query by a related \ParainageSimple\Model\SponsorshipStatus object
     *
     * @param \ParainageSimple\Model\SponsorshipStatus|ObjectCollection $sponsorshipStatus The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function filterBySponsorshipStatus($sponsorshipStatus, $comparison = null)
    {
        if ($sponsorshipStatus instanceof \ParainageSimple\Model\SponsorshipStatus) {
            return $this
                ->addUsingAlias(SponsorshipTableMap::STATUS, $sponsorshipStatus->getId(), $comparison);
        } elseif ($sponsorshipStatus instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SponsorshipTableMap::STATUS, $sponsorshipStatus->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySponsorshipStatus() only accepts arguments of type \ParainageSimple\Model\SponsorshipStatus or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SponsorshipStatus relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function joinSponsorshipStatus($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SponsorshipStatus');

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
            $this->addJoinObject($join, 'SponsorshipStatus');
        }

        return $this;
    }

    /**
     * Use the SponsorshipStatus relation SponsorshipStatus object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \ParainageSimple\Model\SponsorshipStatusQuery A secondary query class using the current class as primary query
     */
    public function useSponsorshipStatusQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSponsorshipStatus($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SponsorshipStatus', '\ParainageSimple\Model\SponsorshipStatusQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSponsorship $sponsorship Object to remove from the list of results
     *
     * @return ChildSponsorshipQuery The current query, for fluid interface
     */
    public function prune($sponsorship = null)
    {
        if ($sponsorship) {
            $this->addUsingAlias(SponsorshipTableMap::ID, $sponsorship->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the sponsorship table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SponsorshipTableMap::DATABASE_NAME);
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
            SponsorshipTableMap::clearInstancePool();
            SponsorshipTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildSponsorship or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildSponsorship object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SponsorshipTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SponsorshipTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        SponsorshipTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SponsorshipTableMap::clearRelatedInstancePool();
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
     * @return     ChildSponsorshipQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SponsorshipTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildSponsorshipQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SponsorshipTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildSponsorshipQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SponsorshipTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildSponsorshipQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SponsorshipTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildSponsorshipQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SponsorshipTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildSponsorshipQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SponsorshipTableMap::CREATED_AT);
    }

} // SponsorshipQuery
