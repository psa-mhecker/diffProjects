<?php

namespace PsaNdp\ApiBundle\Facade\Pf25;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

class PriceFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("float")
     */
    public $value;
    /**
     * @Serializer\Type("string")
     */
    public $display;


}
