<?php

namespace PsaNdp\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class ContentFacade
 */
class ContentFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $title;

    /**
     * @Serializer\Type("string")
     */
    public $text;
}
