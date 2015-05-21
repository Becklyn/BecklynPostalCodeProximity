<?php

namespace Becklyn\PostalCodeProximity;

use Becklyn\PostalCodeProximity\Adapter\PostalCodeAdapterInterface;
use Becklyn\PostalCodeProximity\Entity\PostalCodeDetails;
use Becklyn\PostalCodeProximity\Exception\AmbiguousPostalCodeException;


/**
 *
 */
class PostalCodeLocator
{
    /**
     * @var PostalCodeAdapterInterface
     */
    private $adapter;



    /**
     * @param PostalCodeAdapterInterface $adapter
     */
    public function __construct (PostalCodeAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }



    /**
     * Returns a list of near postal codes
     *
     * @param string    $postalCode
     * @param int|float $radius
     * @param int|null  $limit
     *
     * @return Entity\NearPostalCode[]
     * @throws AmbiguousPostalCodeException
     */
    public function loadNearPostalCodes ($postalCode, $radius, $limit = null)
    {
        $postalCodeDetails = $this->loadPostalCodeDetails($postalCode);

        if (null === $postalCodeDetails)
        {
            return [];
        }

        return $this->adapter->loadNearPostalCodesByRadius($postalCodeDetails, $radius, $limit);
    }



    /**
     * Returns the postal code details
     *
     * @param $postalCode
     *
     * @return PostalCodeDetails|null
     * @throws AmbiguousPostalCodeException
     */
    private function loadPostalCodeDetails ($postalCode)
    {
        $details = $this->adapter->loadPostalCodeDetails($postalCode);

        if (1 < count($details))
        {
            throw new AmbiguousPostalCodeException("Multiple postal code entries with value '{$postalCode}' found.");
        }
        else if (0 === count($details))
        {
            return null;
        }

        return reset($details);
    }
}
