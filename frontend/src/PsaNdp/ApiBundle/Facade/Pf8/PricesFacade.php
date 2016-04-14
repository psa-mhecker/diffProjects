<?php

namespace PsaNdp\ApiBundle\Facade\Pf8;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class PricesFacade
 * @package PsaNdp\ApiBundle\Facade\Pf8
 */
class PricesFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $price;

    /**
     * @Serializer\Type("string")
     */
    public $advice;

    /**
     * @Serializer\Type("string")
     */
    public $saving;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf8\DisponibilityFacade")
     */
    public $disponibility;
}
