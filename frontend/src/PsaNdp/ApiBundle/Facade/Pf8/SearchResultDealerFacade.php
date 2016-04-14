<?php

namespace PsaNdp\ApiBundle\Facade\Pf8;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class SearchResultDealerFacade
 * @package PsaNdp\ApiBundle\Facade\Pf8
 */
class SearchResultDealerFacade implements FacadeInterface
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

    /**
     * @Serializer\Type("string")
     */
    public $reference;

    /**
     * @Serializer\SerializedName("listCars")
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\Pf8\SearchResultItemFacade>")
     */
    public $listCars;

    /**
     * @param SearchResultItemFacade $listCars
     */
    public function addVehicleItem(SearchResultItemFacade $listCars)
    {
        $this->listCars[] = $listCars;
    }
}
