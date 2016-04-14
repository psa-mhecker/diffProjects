<?php
//namespace Plugin;

use Itkg\Service;
use Itkg\Soap\Client;
use Itkg\Helper\DataTransformer;

/**
 *
 */
class Plugin_I18N extends Service
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
 

	
    public function getXMLComponent($requestModel, $responseClass, $mapping)
    {
	
    	$aOptions = $this->configuration->getParameters();
    	if ($mapping) {
    		$aOptions['classmap'] = $mapping;
    	}
		
    	$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
		
		
		$responseModel=$this->client->getXMLComponent($requestModel->getBrand(),
													  $requestModel->getCountry(),
													  $requestModel->getCulture(),
													  $requestModel->getComponent());
		
		/*
    	$responseModel = $this->client->call(
    			'getXMLComponent',
    			$requestModel->__toRequest()
    	);*/
		
		
		if ($responseModel != null && $responseModel->returnCode == 'OK') {
	    	
		} else { // ERROR
			$this->error_message = 'Error Code: ' . $responseModel->errorCode . 
			                    ' / Error message: ' . $responseModel->errorMessage;
			return false;
		}
		
		
    	return $responseModel;
    }
	
	public function updateXMLComponent($requestModel, $responseClass, $mapping)
    {
    	$aOptions = $this->configuration->getParameters();
    	if ($mapping) {
    		$aOptions['classmap'] = $mapping;
    	}
    	$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
    	
    	$responseModel=$this->client->updateXMLComponent($requestModel->getBrand(),
										    	 		 $requestModel->getCountry(),
										    	 		 $requestModel->getComponent(),
										    	 		 $requestModel->getFiles());
    	
    	
    	/*$responseModel = $this->client->call(
    			'updateXMLComponent',
    			$requestModel->__toRequest()
    	);*/
    	return $responseModel;
    }
}