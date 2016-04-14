<?php

namespace PsaNdp\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class ColorPickerFacade
 */
class ColorPickerFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\RangesFacade")
     */
    public $color_picker;
}
