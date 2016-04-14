<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;

use Itkg\Consumer\Service\Service;
use PsaNdp\MappingBundle\Repository\PsaSiteWebserviceRepository;
use PsaNdp\MappingBundle\Repository\PsaWebserviceRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RestConsumer
 */
class RestConsumer
{
    use PsaWebserviceStatusTrait;

    /**
     * @var Service
     */
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
     * @param string $uri
     * @param array $parameters
     *
     * @return mixed
     * @throws \Exception
     */
    public function call($uri,$parameters){

        return $this->service
            ->sendRequest(
                Request::create(
                    $uri,
                    Request::METHOD_GET,
                    $parameters
                )
            )
            ->getResponse()
            ->getDeserializedContent();
            ;
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
