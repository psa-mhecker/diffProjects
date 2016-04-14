<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;

use Itkg\Consumer\Service\Service;
use PsaNdp\MappingBundle\Repository\PsaSiteWebserviceRepository;
use PsaNdp\MappingBundle\Repository\PsaWebserviceRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SoapConsumer
 */
class SoapConsumer
{
    use PsaWebserviceStatusTrait;

    protected $service;

    /**
     * @param Service                     $service
     * @param PsaSiteWebserviceRepository $siteWebserviceRepository
     * @param PsaWebserviceRepository     $psaWebserviceRepository
     */
    public function __construct(Service $service, PsaSiteWebserviceRepository $siteWebserviceRepository, PsaWebserviceRepository $psaWebserviceRepository)
    {
        $this->service = $service;
        $this->siteWebserviceRepository = $siteWebserviceRepository;
        $this->webserviceRepository = $psaWebserviceRepository;
    }

    /**
     * @param       $method
     * @param array $parameters
     *
     * @return mixed
     * @throws \Exception
     */
    public function call($method, $parameters = array())
    {
        return $this->service->sendRequest(
            // on s'assure que le content est null pour évité un conflit dans les appel ajax du BO.
            Request::create($method,Request::METHOD_POST, $parameters,[],[],[],'')
        )
            ->getResponse()
            ->getDeserializedContent();
    }

    /**
     * Get service
     *
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

}
