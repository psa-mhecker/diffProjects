<?php

namespace PsaNdp\ApiBundle\Facade\Pf8;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class SearchResultDealerVehicleFacade
 * @package PsaNdp\ApiBundle\Facade\Pf8
 */
class SearchResultDealerVehicleFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $id;

    /**
     * @Serializer\SerializedName("distanceKm")
     * @Serializer\Type("string")
     */
    public $distanceKm;

    /**
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * @Serializer\Type("string")
     */
    public $type;

    /**
     * @Serializer\Type("boolean")
     */
    public $vehicleNew;

    /**
     * @Serializer\Type("boolean")
     */
    public $vehicleOccasion;

    /**
     * @Serializer\Type("boolean")
     */
    public $vehicleLocation;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\AddressFacade")
     */
    public $address;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\ContactFacade")
     */
    public $contact;

}
