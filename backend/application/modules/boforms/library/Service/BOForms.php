<?php
//namespace Plugin;

use Itkg\Service;
use Itkg\Soap\Client;
use Itkg\Helper\DataTransformer;

/**
 *
 */
class Plugin_BOForms extends Service
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

    public function getInstancesTest($requestModel, $responseClass, $mapping)
    {
    	return $this->getInstances($requestModel,$responseClass,$mapping,true);
    }
    /*
     *
     */
    public function getInstances($requestModel, $responseClass, $mapping, $test = false)
    {
    	try {
	    	$aOptions = $this->configuration->getParameters();
	    	$aOptions['timeout'] = 60;
    	    	
			if ($mapping) {
				$aOptions['classmap'] = $mapping;
			}
	
			$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
		
			$responseModel = $this->client->call(
				'getInstances', $requestModel->__toRequest()
			);
			
			if($test)
			{
				return array('client' => $this->client, 'options' => $aOptions);
			}
			
			if ($responseModel->getInstancesResponse->instances) {
				return $responseModel->getInstancesResponse->instances;
			} else {
				return false;
			}
    	} catch(\Exception $e) {
    		$log = "[".date('Y-m-d H:i:s').'] '.$e->getMessage().'\r\n';
    		echo $e->getMessage().'<br/>';
    		error_log($log, 3, FunctionsUtils::getLogPath() . 'service.log');
    		//die(t('BOFORMS_ERROR_FORM_UNAVAILABLE'));
    	}
				
    }

    public function getInstanceByIdTest($requestModel, $responseClass, $mapping)
    {
    	return $this->getInstanceById($requestModel,$responseClass,$mapping,true);
    }
    
    public function getInstanceById($requestModel, $responseClass, $mapping, $test = false)
    {
    	$aOptions = $this->configuration->getParameters();
    	$aOptions['timeout'] = 60;
    	
    	if ($mapping) {
    		$aOptions['classmap'] = $mapping;
    	}
        	
    	$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
    	
    	$responseModel = $this->client->call(
    			'getInstanceById', $requestModel->__toRequest()
    	);
    	    	
    	/*
    	$log = "[".date('Y-m-d H:i:s').'][aOptions] '. print_r($aOptions, true) .'\r\n';
		error_log($log, 3, FunctionsUtils::getLogPath() . 'debug_publication.log');
    	
		$log = "[".date('Y-m-d H:i:s').'][xmlSent] '. print_r($requestModel->__toRequest(), true) .'\r\n';
		error_log($log, 3, FunctionsUtils::getLogPath() . 'debug_publication.log');
		
		$source=str_replace('xmlns=', 'xmlns2=', print_r($responseModel, true) );//patch bug avec le parcour du dom lorsque le xml contiens "xmlns=".
		$source = str_replace("&lt;", "<", $source);
		$source = str_replace("&gt;", ">", $source);
		$source = str_replace("&quot;", '"', $source);
		
		$log = "[".date('Y-m-d H:i:s').'][getInstanceByIdResponse] '. $source .'\r\n';
		error_log($log, 3, FunctionsUtils::getLogPath() . 'debug_publication.log');
		*/
		
		//print_r($requestModel->__toRequest());  die('============');
    	
    	if($test)
    	{
    		return array('client' => $this->client, 'options' => $aOptions);
    	}
    	
    	if ($responseModel->getInstanceByIdResponse->instanceXML) {
    		return $responseModel->getInstanceByIdResponse->instanceXML;
    	} else {
    		return false;
    	}
    
    }
    
    /*
     *
     */
    public function updateInstance($requestModel, $responseClass, $mapping)
    {
    	ini_set("soap.wsdl_cache_enabled", 0);
    	
        $aOptions = $this->configuration->getParameters();
        $aOptions['timeout'] = 60;
        
        if ($mapping) {
            $aOptions['classmap'] = $mapping;
        }

        $this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
        $responseModel = $this->client->call(
            'updateInstance',
            $requestModel->__toRequest()
        );

        /*
        $log = "[".date('Y-m-d H:i:s').'][aOptions] '. print_r($aOptions, true) .'\r\n';
		error_log($log, 3, FunctionsUtils::getLogPath() . 'debugUpdateInstance.log');
    	
		$log = "[".date('Y-m-d H:i:s').'][xmlSent] '. print_r($requestModel->__toRequest(), true) .'\r\n';
		error_log($log, 3, FunctionsUtils::getLogPath() . 'debugUpdateInstance.log');
		
		$source=str_replace('xmlns=', 'xmlns2=', print_r($responseModel, true) );//patch bug avec le parcour du dom lorsque le xml contiens "xmlns=".
		$source = str_replace("&lt;", "<", $source);
		$source = str_replace("&gt;", ">", $source);
		$source = str_replace("&quot;", '"', $source);
		
		$log = "[".date('Y-m-d H:i:s').'][updateInstanceResponse] '. $source .'\r\n';
		error_log($log, 3, FunctionsUtils::getLogPath() . 'debugUpdateInstance.log');
        */
        
    	if ($responseModel->updateInstanceResponse->statusResponse) {
    		return $responseModel->updateInstanceResponse->statusResponse;
    	} else {
    		return false;
    	}
    }

	/*
     *
     */
    public function duplicateInstance($requestModel, $responseClass, $mapping)
    {	
    	ini_set("soap.wsdl_cache_enabled", 0);
    	
    	$aOptions = $this->configuration->getParameters();
    	$aOptions['timeout'] = 60;
    	
        if ($mapping) {
            $aOptions['classmap'] = $mapping;
        }
        
        $this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
        
        $responseModel = $this->client->call(
            'duplicateInstance',
            $requestModel->__toRequest()
        );
                
    	if ($responseModel->duplicateInstanceResponse->statusResponse) {
    		return $responseModel->duplicateInstanceResponse;
    	} else {
    		return false;
    	}
    }
    
    public function getReferentialTest($requestModel, $responseClass, $mapping)
    {
    	return $this->getReferential($requestModel,$responseClass,$mapping,true);
    }
    
    public function getReferential($requestModel, $responseClass, $mapping, $test = false)
    {
    	
    	try {
    		$aOptions = $this->configuration->getParameters();
    		$aOptions['timeout'] = 60;
    	    		
    		if ($mapping) {
    			$aOptions['classmap'] = $mapping;
    		}
    		 
    		$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
    		 
    		$responseModel = $this->client->call(
    				'getReferential', $requestModel->__toRequest()
    		);
    		 
    		if($test)
    		{
    			return array('client' => $this->client, 'options' => $aOptions);
    		}
    		
    		if ($responseModel->getReferentialResponse->referentials->referential) {
    			return $responseModel->getReferentialResponse->referentials->referential;
    		} else {
    			return false;
    		}
    		
    	} catch(\Exception $e) {
    		$log = "[".date('Y-m-d H:i:s').'] '.$e->getMessage().'\r\n';
    		error_log($log, 3, FunctionsUtils::getLogPath() . 'service.log');
    		//die(t('BOFORMS_ERROR_FORM_UNAVAILABLE'));
    	}
    	
    }
	
    public function getXMLComponent($requestModel, $responseClass, $mapping)
    {	
    	$aOptions = $this->configuration->getParameters();
    	$aOptions['timeout'] = 60;
    	
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
	
	public function updateXMLComponent($requestModel, $responseClass, $mapping)
    {
    	$aOptions = $this->configuration->getParameters();
    	$aOptions['timeout'] = 60;
    	
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
    
    
    public function getReporting($requestModel, $responseClass, $mapping)
    {
    	ini_set("soap.wsdl_cache_enabled", 0);
    	
    	$aOptions = $this->configuration->getParameters();
    	$aOptions['timeout'] = 60;
    	        	
		if ($mapping) {
			$aOptions['classmap'] = $mapping;
		}
				
		$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
	
		$responseModel = $this->client->call(
			'getReporting', $requestModel->__toRequest()
		);

		
		//print_r($requestModel->__toRequest()); die('=============');
		
		if ($responseModel->getReportingResponse->FormList) {
			return $responseModel->getReportingResponse->FormList;
		} else {
			return false;
		}
				
    }
    
    
    public function getLeadsByType($requestModel, $responseClass, $mapping)
    {
    	ini_set("soap.wsdl_cache_enabled", 0);
    	
    	$aOptions = $this->configuration->getParameters();
    	$aOptions['timeout'] = 60;
    	
    	if ($mapping) {
			$aOptions['classmap'] = $mapping;
		}
		$aOptions['timeout'] = 60;

		$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);

		$responseModel = $this->client->call(
			'getLeadsByType', $requestModel->__toRequest()
		);

		if ($responseModel->getLeadsByTypeResponse) {
			return $responseModel->getLeadsByTypeResponse;
		} else {
			return false;
		}
				
    }
    
    
    // BOFORMS configuration
    
 	public function getParameters($requestModel, $responseClass, $mapping)
    {
    	$aOptions = $this->configuration->getParameters();
    	$aOptions['timeout'] = 60;
    	
    	if ($mapping) {
    		$aOptions['classmap'] = $mapping;
    	}
    	$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
    	$responseModel = $this->client->call(
    			'getParameters',
    			$requestModel->__toRequest()
    	);

		
    	return $responseModel->getParametersResponse;
    }
	
	public function updateParameters($requestModel, $responseClass, $mapping)
    {
    	$aOptions = $this->configuration->getParameters();
    	$aOptions['timeout'] = 60;
    	
    	if ($mapping) {
    		$aOptions['classmap'] = $mapping;
    	}
    	$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
    	
    	$responseModel = $this->client->call(
    			'updateParameters',
    			$requestModel->__toRequest()
    	);
    	return $responseModel->parameters;
    }
    
	// ===============================================================
    // =================== DUPLICATE INSTANCE ========================
    // ===============================================================
    
	public function getMasters($requestModel, $responseClass, $mapping)
    {
    	$aOptions = $this->configuration->getParameters();
    	$aOptions['timeout'] = 60;
    	
    	if ($mapping) {
			$aOptions['classmap'] = $mapping;
		}
		
		$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
	
		$responseModel = $this->client->call(
			'getMasters', $requestModel->__toRequest()
		);
		
		if ($responseModel->getMastersResponse) {
			return $responseModel->getMastersResponse;
		} else {
			return false;
		}
    }
    
    public function getInstancesByMaster($requestModel, $responseClass, $mapping)
    {
    	ini_set("soap.wsdl_cache_enabled", 0);
    	    	
    	$aOptions = $this->configuration->getParameters();
    	$aOptions['timeout'] = 60;
    
    	if ($mapping) {
			$aOptions['classmap'] = $mapping;
		}
				
		$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
	
		$responseModel = $this->client->call(
			'getInstancesByMaster', $requestModel->__toRequest()
		);
		
		if ($responseModel->getInstancesByMasterResponse) {
			return $responseModel->getInstancesByMasterResponse;
		} else {
			return false;
		}
    }
    
    public function deleteABTestingInstance($requestModel, $responseClass, $mapping)
    {
    	ini_set("soap.wsdl_cache_enabled", 0);
    	
    	$aOptions = $this->configuration->getParameters();
    	$aOptions['timeout'] = 60;
    	
    	if ($mapping) {
			$aOptions['classmap'] = $mapping;
		}
				
		$this->client = new Client($this->configuration->getParameter('wsdl'), $aOptions);
	
		$responseModel = $this->client->call(
			'deleteABTestingInstance', $requestModel->__toRequest()
		);
		
		// debug($this->client->__getLastRequest());
		// debug($this->client->__getLastResponse());
		
    	if ($responseModel->deleteABTestingInstanceResponse->statusResponse) {
    		return $responseModel->deleteABTestingInstanceResponse->statusResponse;
    	} else {
    		return false;
    	}
		
    }
    

    
}
