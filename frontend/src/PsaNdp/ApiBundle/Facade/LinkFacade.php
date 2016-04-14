<?php
namespace PsaNdp\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class LinkFacade
 */
class LinkFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $url;

    /**
     * @Serializer\Type("string")
     */
    public $title;

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
