<?php

namespace PsaNdp\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class RangeFacade
 */
class RangeFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\ColorFacade>")
     */
    public $range = array();

    /**
     * @param FacadeInterface $range
     */
    public function addRange(FacadeInterface $range)
    {
        $this->range[] = $range;
    }
}
