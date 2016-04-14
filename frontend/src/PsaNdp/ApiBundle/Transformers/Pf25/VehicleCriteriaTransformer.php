<?php

namespace PsaNdp\ApiBundle\Transformers\Pf25;

use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\Pf25\VehicleCriteriaFacade;
use PsaNdp\ApiBundle\Facade\Pf25\VehicleFacade;

/**
 * Class VehicleCriteriaTransformer
 * @package PsaNdp\ApiBundle\Transformers\Pf25
 */
class VehicleCriteriaTransformer extends AbstractTransformer {

    /**
     * @param mixed $vehicle
     *
     * @return VehicleCriteriaFacade
     */
    public function transform($vehicle)
    {
        $vehicleCriteriaFacade = new VehicleCriteriaFacade();
        $vehicleCriteriaFacade->coClass = $vehicle->Co2Class;
        $vehicleCriteriaFacade->energy = $vehicle->Energy->id;
        $vehicleCriteriaFacade->exteriorHeight = $vehicle->ExteriorHeight;
        $vehicleCriteriaFacade->exteriorLength = $vehicle->ExteriorLength;
        $vehicleCriteriaFacade->grTypeBoiteVitesse = $vehicle->GrTransmissionType->id;
        $vehicleCriteriaFacade->mixedConsumption = $vehicle->MixedConsumption;
        if(!empty($vehicle->NumSittedPlaces)){
            $vehicleCriteriaFacade->numSittedPlaces = $vehicle->NumSittedPlaces;
        }

        if(!empty($vehicle->TrunkVolume)){
            $vehicleCriteriaFacade->trunkVolume = $vehicle->TrunkVolume;
        }

        $vehicleCriteriaFacade->category = $vehicle->VersionsCriterion->VersionCriterion[0]->id;

        return $vehicleCriteriaFacade;
    }

    /**
     * @return string
     */
    public function getName()
    {
       return 'pf25_vehicle_criteria_transformer';
    }
}
