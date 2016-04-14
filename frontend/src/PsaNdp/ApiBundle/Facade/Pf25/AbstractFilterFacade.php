<?php


namespace PsaNdp\ApiBundle\Facade\Pf25;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

abstract class AbstractFilterFacade implements CarSelectorFilterInterface, FacadeInterface {
    /**
     * @Serializer\Type("integer")
     */
    public $order;

    /**
     * @Serializer\Type("string")
     */
    public $label;
}
