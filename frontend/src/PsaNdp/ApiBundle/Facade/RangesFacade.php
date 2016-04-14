<?php

namespace PsaNdp\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class RangesFacade
 */
class RangesFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\RangeFacade>")
     */
    public $ranges = array();

    /**
     * @param FacadeInterface $range
     */
    public function addRanges(FacadeInterface $range)
    {
        $this->ranges[] = $range;
    }
}
