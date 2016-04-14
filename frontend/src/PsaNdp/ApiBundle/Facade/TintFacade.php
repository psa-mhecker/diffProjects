<?php

namespace PsaNdp\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class TintFacade
 */
class TintFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $src;

    /**
     * @Serializer\Type("string")
     */
    public $alt;

    /**
     * @Serializer\Type("string")
     */
    public $label;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\SequenceFacade")
     */
    public $sequence;
}
