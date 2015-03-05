<?php

namespace Becklyn\PostalCodeProximity\Adapter;

use Becklyn\PostalCodeProximity\Entity\NearPostalCode;
use Becklyn\PostalCodeProximity\Entity\PostalCodeDetails;
use Becklyn\PostalCodeProximity\Exception\QueryException;
use Becklyn\PostalCodeProximity\QueryGenerator;


/**
 *
 */
class PdoAdapter implements PostalCodeAdapterInterface
{
    /**
     * @var \PDO
     */
    private $pdo;


    /**
     * @var QueryGenerator
     */
    private $queryGenerator;



    public function __construct (\PDO $pdo, QueryGenerator $queryGenerator)
    {
        $this->pdo            = $pdo;
        $this->queryGenerator = $queryGenerator;
    }



    /**
     * Returns the list of postal code details
     *
     * @param string $postalCode
     *
     * @return PostalCodeDetails[]
     * @throws QueryException
     */
    public function loadPostalCodeDetails ($postalCode)
    {
        $query  = $this->queryGenerator->getPostalCodeDetailsQuery($postalCode);
        $result = $this->pdo->query($query);

        if ("00000" !== $this->pdo->errorCode())
        {
            throw new QueryException("Database error: {$this->pdo->errorInfo()}");
        }

        if (!$result instanceof \PDOStatement)
        {
            throw new QueryException("Unknown database error.");
        }

        return $result->fetchAll(\PDO::FETCH_CLASS, "Becklyn\\PostalCodeProximity\\Entity\\PostalCodeDetails");
    }



    /**
     * Loads the near postal codes around a given postal code by radius
     *
     * @param PostalCodeDetails $postalCodeDetails
     * @param int|float         $radius the maximum radius in km
     * @param null|int          $limit  the maximum number of returned elements
     *
     * @return NearPostalCode[]
     * @throws QueryException
     */
    public function loadNearPostalCodesByRadius (PostalCodeDetails $postalCodeDetails, $radius, $limit = null)
    {
        $query  = $this->queryGenerator->getNearPostalCodesByRadiusQuery($postalCodeDetails, $radius, $limit);
        $result = $this->pdo->query($query);


        if ("00000" !== $this->pdo->errorCode())
        {
            throw new QueryException("Database error: {$this->pdo->errorInfo()}");
        }


        return $result->fetchAll(\PDO::FETCH_CLASS, "Becklyn\\PostalCodeProximity\\Entity\\NearPostalCode");
    }
}
