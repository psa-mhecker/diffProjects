<?php

namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\RangesFacade;

/**
 * Class RangesTransformer
 */
class RangesTransformer extends AbstractTransformer
{
    /**
     * @param mixed $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $ranges = new RangesFacade();

        $ranges->addRanges($this->getTransformer('range')->transform($mixed));

        return $ranges;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ranges';
    }
}
