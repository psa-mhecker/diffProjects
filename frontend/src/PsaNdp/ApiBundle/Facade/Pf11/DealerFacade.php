<?php

namespace PsaNdp\ApiBundle\Facade\Pf11;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

class DealerFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $id;

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
    public $vehicle_new = false;
    /**
     * @Serializer\Type("boolean")
     */
    public $vehicle_occasion = false;
    /**
     * @Serializer\Type("boolean")
     */
    public $vehicle_location = false;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\AddressFacade")
     */
    public $adress;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\ContactFacade")
     */
    public $contact;

    /**
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\Pf11\ServiceFacade>")
     */
    public $services;

    /**
     * @Serializer\SerializedName("ctaList")
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\Pf11\CtaFacade>")
     */
    public $ctaList;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf11\OffreCollectionFacade")
     */
    public $offres;

    /**
     * @Serializer\Type("string")
     */
    public $schedules;

    /**
     * @Serializer\Type("boolean")
     */
    public $dealer;

    /**
     * @Serializer\Type("boolean")
     */
    public $agent;

    /**
     * @Serializer\Type("boolean")
     */
    public $principalVn;
}
