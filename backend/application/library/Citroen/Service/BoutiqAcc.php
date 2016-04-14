<?php
namespace Citroen\Service;

use Itkg\Service;
use Itkg\Soap\Client;

/**
 *
 */
class BoutiqAcc extends Service
{
    /**
     * Client SOAP.
     */
    protected $client;

    /*
     *
     */
    public function init()
    {
    }

    /*
     *
     */
    public function monitor()
    {
    }

    /*
     *
     */
    public function getAccessories($requestModel, $responseClass, $mapping)
    {
        $aOptions = $this->configuration->getParameters();
        if ($mapping) {
            $aOptions['classmap'] = $mapping;
        }
        $this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
        $responseModel = $this->client->call(
            'getAccessories',
            $requestModel->__toRequest()
        );

        return $responseModel;
    }

    /*
     *
     */
    public function getCriteriaValues($requestModel, $responseClass, $mapping)
    {
        $aOptions = $this->configuration->getParameters();
        if ($mapping) {
            $aOptions['classmap'] = $mapping;
        }
        $this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
        $responseModel = $this->client->call(
            'getCriteriaValues',
            $requestModel->__toRequest()
        );

        return $responseModel;
    }
}
