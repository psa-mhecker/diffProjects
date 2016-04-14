<?php

namespace PsaNdp\ApiBundle\Facade\Pf25;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

class VehicleFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $version;

    /**
     * @Serializer\Type("string")
     */
    public $title;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\PriceFacade")
     */
    public $price;

    /**
     * @Serializer\Type("string")
     */
    public $thumbnail;

    /**
     * @Serializer\Type("string")
     */
    public $generalLegalText;
}
