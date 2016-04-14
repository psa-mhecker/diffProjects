<?php

namespace PsaNdp\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class AvailabilityFacade
 */
class AvailabilityFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("boolean")
     */
    public $checked;

    /**
     * @Serializer\Type("string")
     */
    public $checkVisu;

    /**
     * @Serializer\Type("string")
     */
    public $option;
}
