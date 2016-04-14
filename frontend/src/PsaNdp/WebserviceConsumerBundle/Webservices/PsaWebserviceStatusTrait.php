<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;

use PsaNdp\MappingBundle\Repository\PsaSiteWebserviceRepository;
use PsaNdp\MappingBundle\Repository\PsaWebserviceRepository;

/**
 * Class AbstractPsaWebservice
 */
trait PsaWebserviceStatusTrait
{
    /**
     * @var PsaSiteWebserviceRepository
     */
    protected $siteWebserviceRepository;

    /**
     * @var PsaWebserviceRepository
     */
    protected $webserviceRepository;

    /**
     * @param $siteId
     * @param $serviceName
     *
     * @return bool
     */
    public function getWebserviceStatus($siteId, $serviceName)
    {
        $webservice = $this->webserviceRepository->findOneByServiceKey($serviceName);

        $siteWebservice = $this->siteWebserviceRepository->findOneBySiteIdAndWebserviceId($siteId, $webservice->getId());

        return ($siteWebservice != null) ? !$siteWebservice->isDisabled() : false;
    }
}
