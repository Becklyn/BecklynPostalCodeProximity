<?php

namespace Becklyn\PostalCodeProximity;

use Becklyn\PostalCodeProximity\Entity\PostalCodeDetails;


/**
 *
 */
class QueryGenerator
{
    private $tableName;
    private $latitudeColumn;
    private $longitudeColumn;
    private $postalCodeColumn;

    const KM_PER_DEGREE_LATITUDE = 111.045;


    /**
     * QueryGenerator constructor.
     *
     * @param string $tableName
     * @param string $latitudeColumn
     * @param string $longitudeColumn
     * @param string $postalCodeColumn
     */
    public function __construct ($tableName, $latitudeColumn = "lat", $longitudeColumn = "lng", $postalCodeColumn = "zip")
    {
        $this->tableName        = $tableName;
        $this->latitudeColumn   = $latitudeColumn;
        $this->longitudeColumn  = $longitudeColumn;
        $this->postalCodeColumn = $postalCodeColumn;
    }


    public function getNearPostalCodesByRadiusQuery (PostalCodeDetails $postalCodeDetails, $radius, $limit = null)
    {
        $latitudeMin  = $postalCodeDetails->getLatitude() - ($radius / self::KM_PER_DEGREE_LATITUDE);
        $latitudeMax  = $postalCodeDetails->getLatitude() + ($radius / self::KM_PER_DEGREE_LATITUDE);
        $longitudeMin = $postalCodeDetails->getLongitude() - ($radius / (self::KM_PER_DEGREE_LATITUDE * cos(deg2rad($postalCodeDetails->getLatitude()))));
        $longitudeMax = $postalCodeDetails->getLongitude() + ($radius / (self::KM_PER_DEGREE_LATITUDE * cos(deg2rad($postalCodeDetails->getLatitude()))));

        $kmPerDegreeLatitude = self::KM_PER_DEGREE_LATITUDE;

        $select = <<<SELECT
SELECT `{$this->postalCodeColumn}` AS `postalCode`,
{$kmPerDegreeLatitude} * DEGREES(ACOS(
    COS(RADIANS({$postalCodeDetails->getLatitude()})) * COS(RADIANS(pc.`{$this->latitudeColumn}`)) * COS(RADIANS({$postalCodeDetails->getLongitude()}) - RADIANS(pc.`{$this->longitudeColumn}`))
    + SIN(RADIANS({$postalCodeDetails->getLatitude()})) * SIN(RADIANS(pc.`{$this->latitudeColumn}`))
)) AS `distanceInKm`
SELECT;

        $from = "FROM `{$this->tableName}` pc";

        $where = <<<WHERE_CLAUSE
WHERE
    pc.`{$this->latitudeColumn}` BETWEEN {$latitudeMin} AND {$latitudeMax}
AND
    pc.`{$this->longitudeColumn}` BETWEEN {$longitudeMin} AND {$longitudeMax}
WHERE_CLAUSE;

        $order = "ORDER BY `distanceInKm` ASC";

        $limit = ((null !== $limit) && (ctype_digit($limit) || is_int($limit)))
            ? "LIMIT {$limit}"
            : "";


        return <<<SELECT_QUERY
SELECT `inner`.`postalCode`, `inner`.`distanceInKm`
FROM(
    {$select} {$from} {$where} {$order}
) AS `inner`
WHERE `inner`.`distanceInKm` <= {$radius}
ORDER BY `inner`.`distanceInKm`
{$limit}
SELECT_QUERY;
    }



    /**
     * Returns the query to fetch the postal code details
     *
     * @param string $postalCode
     *
     * @return string
     */
    public function getPostalCodeDetailsQuery ($postalCode)
    {
        if (!ctype_digit($postalCode) || empty($postalCode))
        {
            throw new \InvalidArgumentException("Invalid postal code given.");
        }

        return "SELECT `{$this->postalCodeColumn}` AS `postalCode`,  `{$this->latitudeColumn}` AS `latitude`, `{$this->longitudeColumn}` AS `longitude` FROM `{$this->tableName}` WHERE `{$this->postalCodeColumn}` = '{$postalCode}'";
    }
}
