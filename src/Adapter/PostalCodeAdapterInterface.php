<?php

namespace Becklyn\PostalCodeProximity\Adapter;

use Becklyn\PostalCodeProximity\Entity\NearPostalCode;
use Becklyn\PostalCodeProximity\Entity\PostalCodeDetails;
use Becklyn\PostalCodeProximity\Exception\QueryException;


/**
 *
 */
interface PostalCodeAdapterInterface
{
    /**
     * Returns the list of postal code details
     *
     * @param string $postalCode
     *
     * @return PostalCodeDetails[]
     * @throws QueryException
     */
    public function loadPostalCodeDetails ($postalCode);



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
    public function loadNearPostalCodesByRadius (PostalCodeDetails $postalCodeDetails, $radius, $limit = null);
}
