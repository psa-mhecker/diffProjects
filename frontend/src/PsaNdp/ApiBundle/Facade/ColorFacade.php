<?php

namespace PsaNdp\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class ColorFacade
 */
class ColorFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("boolean")
     */
    public $default;

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
    public $label;

    /**
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\TintFacade>")
     */
    public $tint;
}
