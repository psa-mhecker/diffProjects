<?php
namespace Citroen\Service;

use Itkg\Service;
use Itkg\Soap\Client;
use Itkg\Helper\DataTransformer;

/**
 *
 */
class Webstore extends Service
{

    /**
     * Client SOAP
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
    public function getStockWebstore($requestModel, $responseClass, $mapping)
    {
        $aOptions = $this->configuration->getParameters();
        if ($mapping) {
            $aOptions['classmap'] = $mapping;
        }
        $this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
        $responseModel = $this->client->call(
            'getStockWebstore',
            $requestModel->__toRequest()
        );
        return $responseModel;
    }
	
	 /*
     *
     */
    public function getVehicles($requestModel, $responseClass, $mapping)
    {
        $aOptions = $this->configuration->getParameters();
        if ($mapping) {
            $aOptions['classmap'] = $mapping;
        }
        $this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
        $responseModel = $this->client->call(
            'getVehicles',
            $requestModel->__toRequest()
        );
        return $responseModel;
    }
	
	public function getOptionalFeaturesInfo($requestModel, $responseClass, $mapping)
    {
        $aOptions = $this->configuration->getParameters();
        if ($mapping) {
            $aOptions['classmap'] = $mapping;
        }

        $this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
        $responseModel = $this->client->call(
            'getOptionalFeaturesInfo',
            $requestModel->__toRequest()
        );
        return $responseModel;
    }


}