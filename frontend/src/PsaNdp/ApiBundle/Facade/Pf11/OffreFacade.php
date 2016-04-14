<?php
namespace PsaNdp\ApiBundle\Facade\Pf11;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class OffreFacade
 * @package PsaNdp\ApiBundle\Facade\Pf11
 */
class OffreFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $title;
}
