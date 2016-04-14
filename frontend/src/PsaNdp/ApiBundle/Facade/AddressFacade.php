<?php

namespace PsaNdp\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class AddressFacade
 */
class AddressFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $street;

    /**
     * @Serializer\Type("string")
     */
    public $city;

    /**
     * @Serializer\Type("string")
     */
    public $postalCode;

    /**
     * @Serializer\Type("string")
     */
    public $country;

    /**
     * @Serializer\Type("string")
     */
    public $lat;

    /**
     * @Serializer\Type("string")
     */
    public $lng;

    /**
     * @Serializer\Type("string")
     */
    public $dist;
}
