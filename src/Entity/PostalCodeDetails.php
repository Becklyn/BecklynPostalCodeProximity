<?php

namespace Becklyn\PostalCodeProximity\Entity;

/**
 *
 */
class PostalCodeDetails
{
    private $postalCode;
    private $latitude;
    private $longitude;



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
    public function getLatitude ()
    {
        return $this->latitude;
    }



    /**
     * @param mixed $latitude
     */
    public function setLatitude ($latitude)
    {
        $this->latitude = $latitude;
    }



    /**
     * @return mixed
     */
    public function getLongitude ()
    {
        return $this->longitude;
    }



    /**
     * @param mixed $longitude
     */
    public function setLongitude ($longitude)
    {
        $this->longitude = $longitude;
    }
}
