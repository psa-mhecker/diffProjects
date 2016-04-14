<?php

namespace PsaNdp\ApiBundle\Facade\Pf25;

use JMS\Serializer\Annotation as Serializer;


class EdgedFilterFacade extends AbstractFilterFacade {

    /**
     * @Serializer\Type("float")
     */
    public $min = 0;

    /**
     * @Serializer\Type("float")
     */
    public $max = 0;


    /**
     * Redefine edges
     * @param $value
     */
    public function addValue($value)
    {
        $this->max = ceil(max($this->max, $value));

        if($this->min === 0 && $value !== null){
            $this->min = floor($value);
        }

        $this->min = floor(min($this->min, $value));
    }
}
