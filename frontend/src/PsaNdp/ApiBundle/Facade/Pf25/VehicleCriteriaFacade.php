<?php

namespace PsaNdp\ApiBundle\Facade\Pf25;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

class VehicleCriteriaFacade
{

    /**
     * @Serializer\Type("integer")
     */
    public $numSittedPlaces;

    /**
     * @Serializer\Type("string")
     */
    public $coClass;

    /**
     * @Serializer\Type("float")
     */
    public $mixedConsumption;

    /**
     * @Serializer\Type("string")
     */
    public $category;

    /**
     * @Serializer\Type("float")
     */
    public $exteriorLength;

    /**
     * @Serializer\Type("float")
     */
    public $exteriorHeight;

    /**
     * @Serializer\Type("string")
     */
    public $energy;

    /**
     * @Serializer\Type("float")
     */
    public $trunkVolume;

    /**
     * @Serializer\Type("string")
     */
    public $grTypeBoiteVitesse;

}
