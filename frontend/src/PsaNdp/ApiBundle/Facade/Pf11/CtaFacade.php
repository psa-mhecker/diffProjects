<?php

namespace PsaNdp\ApiBundle\Facade\Pf11;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class CtaFacade
 * @package PsaNdp\ApiBundle\Facade
 */
class CtaFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $url;
    /**
     * @Serializer\Type("string")
     */
    public $version;
    /**
     * @Serializer\Type("string")
     */
    public $title;
    /**
     * @Serializer\Type("string")
     */
    public $target;

}
