<?php

namespace PsaNdp\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class MediaFacade
 */
class MediaFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $src;

    /**
     * @Serializer\Type("string")
     */
    public $alt;
}
