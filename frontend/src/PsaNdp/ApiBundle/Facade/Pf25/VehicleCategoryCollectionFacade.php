<?php

namespace PsaNdp\ApiBundle\Facade\Pf25;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

class VehicleCategoryCollectionFacade implements FacadeInterface
{

    /**
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\Pf25\VehicleCategoryFacade>")
     */
    public $vehicleCategories = array();

    /**
     * @param VehicleCategoryFacade $vehicleCategoryFacade
     */
    public function push(VehicleCategoryFacade $vehicleCategoryFacade)
    {
        $this->vehicleCategories[] = $vehicleCategoryFacade;
    }

    /**
     * Get vehicleCategories
     *
     * @return mixed
     */
    public function getVehicleCategories()
    {
        return $this->vehicleCategories;
    }
}