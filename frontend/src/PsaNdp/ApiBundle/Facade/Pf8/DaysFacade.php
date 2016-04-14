<?php
namespace PsaNdp\ApiBundle\Facade\Pf8;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class DaysFacade
 * @package PsaNdp\ApiBundle\Facade\Pf8
 */
class DaysFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $jour;

    /**
     * @Serializer\Type("string")
     */
    public $jours;
}
