<?php

namespace PsaNdp\ApiBundle\Facade\Pf8;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class DisponibilityFacade
 * @package PsaNdp\ApiBundle\Facade\Pf8
 */
class DisponibilityFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $text;

    /**
     * @Serializer\Type("integer")
     */
    public $nbDays;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf8\DaysFacade")
     */
    public $days;
}
