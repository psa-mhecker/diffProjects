<?php

namespace PsaNdp\ApiBundle\Service;

use PsaNdp\MappingBundle\Repository\PsaCarSelectorFilterRepository;

/**
 * Class CarSelectorFilterSettings
 * @package PsaNdp\ApiBundle\Service
 */
class CarSelectorFilterSettings
{
    /**
     * @var PsaCarSelectorFilterRepository
     */
    protected $carSelectorFilterRepository;

    /**
     * @param PsaCarSelectorFilterRepository $carSelectorFilterRepository
     */
    public function __construct(PsaCarSelectorFilterRepository $carSelectorFilterRepository)
    {
        $this->carSelectorFilterRepository = $carSelectorFilterRepository;
    }

    /**
     * @param $siteId
     *
     * @return null|object
     */
    public function get($siteId){

        return $this->carSelectorFilterRepository->findOneBy(array('site'=>$siteId));
    }
}
