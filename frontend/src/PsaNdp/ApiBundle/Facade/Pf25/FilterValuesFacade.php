<?php

namespace PsaNdp\ApiBundle\Facade\Pf25;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;


class FilterValuesFacade implements FacadeInterface {

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\MultiFilterFacade")
     */
    public $numSittedPlaces;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\MultiFilterFacade")
     */
    public $co2Class;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\EdgedFilterFacade")
     */
    public $mixedConsumption;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\EdgedFilterFacade")
     */
    public $exteriorLength;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\EdgedFilterFacade")
     */
    public $exteriorHeight;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\MultiFilterFacade")
     */
    public $energy;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\MultiFilterFacade")
     */
    public $trunkVolume;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\MultiFilterFacade")
     */
    public $grTransmissionType;

    /**
      * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\EdgedFilterFacade")
     */
    public $price;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\EdgedFilterFacade")
     */
    public $monthlyPrice;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\MultiFilterFacade")
     */
    public $vehicleCategory;


    /**
     * @param string $name
     * @param string $type
     * @param mixed $value
     * @param integer $order
     * @param string $label
     *
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    private function addFilterValue($name, $type, $value, $order, $label){



        if(null === $this->{$name}){
            $this->{$name} = FilterFacadeFactory::create($type);
        }

        $this->{$name}->addValue($value);
        $this->{$name}->order = $order;
        $this->{$name}->label = $label;

    }

    /**
     * @param $groupOfFilters
     */
    public function  addGroupOfFilterValues($groupOfFilters){

        $order = 0;
        foreach($groupOfFilters as $filterName=>$filterDetail){
            if(!empty($filterDetail['value']) && !empty($filterName)){
                $this->addFilterValue($filterName, $filterDetail['type'], $filterDetail['value'],$order,$filterDetail['translation']);
            }
            $order ++;
        }
    }
}
