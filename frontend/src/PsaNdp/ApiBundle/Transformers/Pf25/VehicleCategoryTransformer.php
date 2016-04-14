<?php

namespace PsaNdp\ApiBundle\Transformers\Pf25;


use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\Pf25\VehicleCategoryFacade;

class VehicleCategoryTransformer extends AbstractTransformer{

    public function transform($vehicleCategory)
    {

        $vehicleCategoryFacade = new VehicleCategoryFacade();

        $vehicleCategoryFacade->id = $vehicleCategory->getCategory()->getId();
        $vehicleCategoryFacade->label = $vehicleCategory->getLabel();
        $vehicleCategoryFacade->media = $vehicleCategory->getCategory()->getMedia()->getMediaPath();
        $vehicleCategoryFacade->hoverMedia = $vehicleCategory->getCategory()->getMedia()->getMediaPath();
        $vehicleCategoryFacade->order = $vehicleCategory->getCategoryOrder();
        $vehicleCategoryFacade->marketingCriteria = $vehicleCategory->getMarketingCriteria();

        return $vehicleCategoryFacade;
    }
    /**
     * @return string
     */
    public function getName()
    {
      return 'pf25_vehicle_category';
    }
}