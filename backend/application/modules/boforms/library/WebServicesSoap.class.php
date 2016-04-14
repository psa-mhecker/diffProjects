<?php

include_once("config.php");
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/services.ini.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.admin.ini.php');

class WebServicesSoap
{

	//CONST URL_BOFORMS_I18N = \Itkg::$config['URL_BOFORMS_I18N'];
	//CONST URL_BOFORMS_I18N = 'http://re7.dpdcr.citroen.com/boforms?wsdl';
	//CONST URL_URL_BOFORMS='http://yfarcy-backend.psa-boforms.com/modules/boforms/BOFormService.wsdl';
	//CONST URL_URL_BOFORMS='http://webservices.canal.dev:8081/BOForms?wsdl';
	
	var $error_message = null;
	var $result = null;
	
	/*
	function getInstanceById($instanceId ) {
	
		$client = new SoapClient(self::URL_URL_BOFORMS, array("trace" => 1, "exception" => 0));
		$this->result = null;
	
	
		try {
			// Call wsdl function
			$result = $client->__soapCall("getInstanceById", array(
					"instanceId" => $instanceId
						
			), NULL, null);
				
			
				
			echo "<pre>";
			print_r($result);
			echo "<pre>";
			die('stop4');
		} catch (Exception $e) {
			$this->error_message = $e->getMessage();
			return false;
		}
	
		// process result
	
		if ($result != null && $result->returnCode == 'OK') {
			$this->result = $result;
		} else { // ERROR
			$this->error_message = 'Error Code: ' . $result->errorCode .
			' / Error message: ' . $result->errorMessage;
			return false;
		}
	
		return true;
	}*/
	/*
	function getInstances($country,$brand ) {
		
		$client = new SoapClient(self::URL_URL_BOFORMS, array("trace" => 1, "exception" => 0));
		$this->result = null;
		

		try {
			// Call wsdl function
			$result = $client->__soapCall("getInstances", array(
					"country" => $country,
					"brand" => $brand
					
			), NULL, null);
			
			if($result->getInstancesResponse->instances)
			{	
				foreach ($result->getInstancesResponse->instances as $res)
				{
					var_dump($res);
				}
			}
			
			echo "<pre>";
			print_r($result);
			echo "<pre>";
			die('stop4');
		} catch (Exception $e) {
			$this->error_message = $e->getMessage();
			return false;
		}
		
		// process result
		
		if ($result != null && $result->returnCode == 'OK') {
			$this->result = $result;
		} else { // ERROR
			$this->error_message = 'Error Code: ' . $result->errorCode .
			' / Error message: ' . $result->errorMessage;
			return false;
		}
		
		return true;
	}*/
	
	
	///////////////////////// updateXMLComponent ///////////////////////////
	
	function updateXMLComponent($brand, $country, $component, $files) {
    	$client = new SoapClient(\Itkg::$config['CITROEN_SERVICE_I18N']['PARAMETERS']['wsdl'], array("trace" => 1, "exception" => 0));
		$this->result = null;
		
		try {
			// Call wsdl function
			$result = $client->__soapCall("updateXMLComponent", array(
			    "brand" => $brand,
				"country" => $country,
				"component" => $component,
				"files" => $files
			), NULL, null);
		} catch (Exception $e) {
			$this->error_message = $e->getMessage();
			return false;
		}	

		// process result
		
		if ($result != null && $result->returnCode == 'OK') {
	    	$this->result = $result;
	    } else { // ERROR
			$this->error_message = 'Error Code: ' . $result->errorCode . 
			                    ' / Error message: ' . $result->errorMessage;
			return false;
		}
		
		return true;
    }
	
    function getUpdateXMLComponentResult() {
    	return $this->value;
    }
    
	///////////////////////// getXMLComponent //////////////////////////////
	
    function getXMLComponent($brand, $country, $culture, $component) {
    	$client = new SoapClient(\Itkg::$config['CITROEN_SERVICE_I18N']['PARAMETERS']['wsdl'], array("trace" => 1, "exception" => 0));
		//$this->result = null;
		
		try {
			// Call wsdl function
			$result = $client->__soapCall("getXMLComponent", array(
			    "brand" => $brand,
				"country" => $country,
				"culture" => $culture,
				"component" => $component
			), NULL, null);
		} catch (Exception $e) {
			$this->error_message = $e->getMessage();
			return false;
		}	

		// process result
		/*
		if ($result != null && $result->returnCode == 'OK') {
	    	$nb_results = count($result->content[0]); 
	    	if ($nb_results > 0) {
	    		//$this->result = $result->content[0];
	    	}
		} else { // ERROR
			$this->error_message = 'Error Code: ' . $result->errorCode . 
			                    ' / Error message: ' . $result->errorMessage;
			return false;
		}
		
		return true;*/
    }
	
	function getXMLComponentResult() {
		
		$tblText = array();
		if (is_array($this->result)) {
			for ($i = 0; $i < count($this->result); $i++) {
	    		$culture = $this->result[$i]->culture;
	    		$labels  = $this->result[$i]->labels;
	
	    		for ($zzz = 0; $zzz < count($labels); $zzz++) {
	    			$tblText[$labels[$zzz]->code][$culture] = array('order' => $labels[$zzz]->order,
	    															'text' => $labels[$zzz]->text, 
	    															'language' => $labels[$zzz]->language, 
	    															'prov' => $labels[$zzz]->prov, 
	    															'date' => $labels[$zzz]->date);    			
	    		}
			}
		}
		return $tblText;
	}
	function getErrorMessage() {
		return $this->error_message;
	}
	
}