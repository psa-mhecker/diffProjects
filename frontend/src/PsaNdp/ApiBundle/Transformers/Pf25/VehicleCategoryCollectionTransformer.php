<?php

namespace PsaNdp\ApiBundle\Transformers\Pf25;

use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\Pf25\VehicleCategoryCollectionFacade;

class VehicleCategoryCollectionTransformer extends AbstractTransformer {

    public function transform($vehicleCategories)
    {
        $vehicleCategoriesFacade = new VehicleCategoryCollectionFacade();

        foreach($vehicleCategories as $vehicleCategory){
            $vehicleCategoriesFacade->push(
                $this->getTransformer('pf25_vehicle_category')->transform($vehicleCategory));
        }

        return $vehicleCategoriesFacade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pf25_vehicle_category_collection';
    }
}