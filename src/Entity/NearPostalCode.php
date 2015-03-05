<?php

namespace Becklyn\PostalCodeProximity\Entity;


/**
 *
 */
class NearPostalCode
{
    private $postalCode;
    private $distanceInKm;



    /**
     * @return mixed
     */
    public function getPostalCode ()
    {
        return $this->postalCode;
    }



    /**
     * @param mixed $postalCode
     */
    public function setPostalCode ($postalCode)
    {
        $this->postalCode = $postalCode;
    }



    /**
     * @return mixed
     */
    public function getDistanceInKm ()
    {
        return $this->distanceInKm;
    }



    /**
     * @param mixed $distanceInKm
     */
    public function setDistanceInKm ($distanceInKm)
    {
        $this->distanceInKm = $distanceInKm;
    }
}
