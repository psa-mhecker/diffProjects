<?php

namespace PsaNdp\ApiBundle\Facade\Pf11;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class DealerCollection
 */
class DealerCollectionFacade implements FacadeInterface
{
    const REGULAR = 'regular';
    const NEW_VEHICLE_DEALER = 'new_vehicle_dealer';

    /**
     * @Serializer\SerializedName("listDealer")
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\Pf11\DealerFacade>")
     */
    protected $dealers =[];

    protected $newVehicleDealers = [];

    /**
     * @param FacadeInterface $dealer
     */
    public function add(FacadeInterface $dealer,$dvnFirst = false)
    {

        if(!$dvnFirst){
            array_push($this->dealers,$dealer);
        }elseif($dvnFirst && $dealer->principalVn){
            array_push($this->newVehicleDealers,$dealer);
        }else{
            array_push($this->dealers,$dealer);
        }
    }

    public function newVehicleDealersFirst()
    {
        $this->dealers= array_merge($this->newVehicleDealers,$this->dealers);
    }

    public function defaultDealersOrder(){
      $this->dealers = array_merge($this->dealers, $this->newVehicleDealers);
    }


}
