<?php

namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\RangeFacade;

/**
 * Class RangesTransformer
 */
class RangeTransformer extends AbstractTransformer
{
    /**
     * @param mixed $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $range = new RangeFacade();

        foreach ($mixed as $orderColorType) {
            $range->addRange($this->getTransformer('color')->transform($orderColorType));
        }

        return $range;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'range';
    }
}
