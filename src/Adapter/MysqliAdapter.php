<?php

namespace Becklyn\PostalCodeProximity\Adapter;

use Becklyn\PostalCodeProximity\Entity\NearPostalCode;
use Becklyn\PostalCodeProximity\Entity\PostalCodeDetails;
use Becklyn\PostalCodeProximity\Exception\QueryException;
use Becklyn\PostalCodeProximity\QueryGenerator;


/**
 *
 */
class MysqliAdapter implements PostalCodeAdapterInterface
{
    /**
     * @var \mysqli
     */
    private $mysqli;


    /**
     * @var QueryGenerator
     */
    private $queryGenerator;



    /**
     * @param \mysqli        $mysqli
     * @param QueryGenerator $queryGenerator
     */
    public function __construct (\mysqli $mysqli, QueryGenerator $queryGenerator)
    {
        $this->mysqli            = $mysqli;
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
        $result = $this->mysqli->query($query);

        if (false === $result)
        {
            throw new QueryException("Database error: {$this->mysqli->error}");
        }

        return array_map(
            function ($row)
            {
                $details = new PostalCodeDetails();
                $details->setPostalCode($row["postalCode"]);
                $details->setLatitude($row["latitude"]);
                $details->setLongitude($row["longitude"]);
                return $details;
            },
            $result->fetch_all(MYSQLI_ASSOC)
        );
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
        $result = $this->mysqli->query($query);

        if (false === $result)
        {
            throw new QueryException("Database error: {$this->mysqli->error}");
        }

        return array_map(
            function ($row)
            {
                $nearPostalCode = new NearPostalCode();
                $nearPostalCode->setPostalCode($row["postalCode"]);
                $nearPostalCode->setDistanceInKm($row["distanceInKm"]);
                return $nearPostalCode;
            },
            $result->fetch_all(MYSQLI_ASSOC)
        );
    }
}
