<?php
/**
 * User: Ayoub Hidri <ayoub.hidri@businessdecision.com>
 * Date: 28/08/15
 * Time: 16:47
 */

namespace PsaNdp\ApiBundle\Facade\Pf25;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

class MultiFilterFacade extends AbstractFilterFacade {

    /**
     * @Serializer\Type("array")
     */
    public $availableValues=array();


    /**
     * @param mixed $value
     */
    public function addValue($value){

        if(!is_array($value) && is_object($value)){
            $value = $this->stdClassObjectToArray($value);
        }

        if(!in_array($value,$this->availableValues)){
            array_push($this->availableValues,$value);
            sort($this->availableValues);
        }
    }

    /**
     * Convert an object returned from the WS
     * to an array
     * @param stdClass $stdClassObject
     *
     * @return array
     */
    public function stdClassObjectToArray($stdClassObject){
        return (array)$stdClassObject;
    }
}
