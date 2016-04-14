<?php

namespace PsaNdp\ApiBundle\Facade\Pf25;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class VehicleCategoryFacade
 * @package PsaNdp\ApiBundle\Facade\Pf25
 */
class VehicleCategoryFacade implements FacadeInterface {


    /**
     * @Serializer\Type("integer")
     */
    public $id;
    /**
     * @Serializer\Type("string")
     */
    public $label;
    /**
     * @Serializer\Type("string")
     */
    public $media;
    /**
     * @Serializer\Type("string")
     */
    public $hoverMedia;

    /**
     * @Serializer\Type("array<string>")
     */
    public $marketingCriteria;

    /**
     * @Serializer\Type("integer")
     */
    public $order;
}
