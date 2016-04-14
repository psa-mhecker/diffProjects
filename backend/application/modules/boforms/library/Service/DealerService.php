<?php
//namespace Plugin;

use Itkg\Service;
use Itkg\Soap\Client;
use Itkg\Helper\DataTransformer;

/**
 *
 */
class Plugin_DealerService extends Service
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

    
    public function geoLocalize($requestModel, $responseClass, $mapping) {
   		$aOptions = $this->configuration->getParameters();
        	
		if ($mapping) {
			$aOptions['classmap'] = $mapping;
		}
				
		$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
	
		$responseModel = $this->client->call(
			'geoLocalize', $requestModel->__toRequest()
		);
		
		if ($responseModel->GeoLocalizeResult) {
			return $responseModel->GeoLocalizeResult;
		} else {
			return false;
		}
    }
    
    public function getDealerList($requestModel, $responseClass, $mapping) {
    	$aOptions = $this->configuration->getParameters();
    
        	
		if ($mapping) {
			$aOptions['classmap'] = $mapping;
		}
				
		$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
	
		$responseModel = $this->client->call(
			'getDealerList', $requestModel->__toRequest()
		);
		
		if ($responseModel->GetDealerListResult) {
			return $responseModel->GetDealerListResult;
		} else {
			return false;
		}
    }
    
}
