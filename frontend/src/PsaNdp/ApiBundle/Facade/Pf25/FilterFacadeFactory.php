<?php

namespace PsaNdp\ApiBundle\Facade\Pf25;


use Doctrine\DBAL\Exception\InvalidArgumentException;

class FilterFacadeFactory {



    public static function create($filterType)
    {
        $filter = null;
        switch($filterType){
            case CarSelectorFilterInterface::EDGED_FILTER:
                $filter = new EdgedFilterFacade();
                break;
            case CarSelectorFilterInterface::MULTI_FILTER:
                $filter = new MultiFilterFacade();
                break;
            default:
                throw new InvalidArgumentException(sprintf('Unknown filter type: %s',$filterType));
                break;
        }

        return $filter;
    }
}
