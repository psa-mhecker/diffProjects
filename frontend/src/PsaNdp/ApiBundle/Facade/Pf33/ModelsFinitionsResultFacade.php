<?php
namespace PsaNdp\ApiBundle\Facade\Pf33;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class ModelsFinitionsResultFacade
 * @package PsaNdp\ApiBundle\Facade\Pf33
 */
class ModelsFinitionsResultFacade
{
    /**
     * @Serializer\SerializedName("listDealer")
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\Pf33\ModelsFinitionsResultDealerFacade>")
     */
    public $listDealer;

    /**
     * @param modelsFinitionsResultDealerFacade $listDealer
     */
    public function addDealerItem(ModelsFinitionsResultDealerFacade $listDealer)
    {
        $this->listDealer[] = $listDealer;
    }
}
