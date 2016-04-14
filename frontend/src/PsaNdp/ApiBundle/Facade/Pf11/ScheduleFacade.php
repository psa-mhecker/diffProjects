<?php
namespace PsaNdp\ApiBundle\Facade\Pf11;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

class ScheduleFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $day;

    /**
     * @Serializer\Type("string")
     */
    public $state;
}
