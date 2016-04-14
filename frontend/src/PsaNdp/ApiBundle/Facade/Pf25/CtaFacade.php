<?php

namespace PsaNdp\ApiBundle\Facade\Pf25;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

class CtaFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $text;

    /**
     * @Serializer\Type("string")
     */
    public $url;

    /**
     * @Serializer\Type("string")
     */
    public $target;

    /**
     * @Serializer\Type("integer")
     */
    public $class;

    /**
     * @Serializer\Type("integer")
     */
    public $order;
}
