<?php
namespace Citroen\Service;

use Itkg\Service;
use Itkg\Soap\Client;
use Itkg\Helper\DataTransformer;

/**
 *
 */
class MoteurConfig extends Service
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
	
	public function Select($requestModel, $responseClass, $mapping)
    {
	
        $aOptions = $this->configuration->getParameters();
		
        if ($mapping) {
            $aOptions['classmap'] = $mapping;
        }

        $this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
		
        $responseModel = $this->client->call(
            'Select',
            $requestModel->__toRequest()
        );
		
		
        return $responseModel;
    }


}