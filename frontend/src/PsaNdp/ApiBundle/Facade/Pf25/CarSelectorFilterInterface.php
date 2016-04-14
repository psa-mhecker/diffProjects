<?php

namespace PsaNdp\ApiBundle\Facade\Pf25;


interface CarSelectorFilterInterface {

    const EDGED_FILTER = 'edged';
    const MULTI_FILTER = 'multi';

    /**
     * @param mixed $value
     *
     */
    public function addValue($value);
}

