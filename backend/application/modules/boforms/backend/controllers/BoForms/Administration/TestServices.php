<?php
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local.ini.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/BoForms.php');

include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FunctionsUtils.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/JiraUtil.php');
/*** WebServices***/
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/services.ini.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Configuration.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstancesRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstancesResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstanceByIdRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstanceByIdResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/UpdateInstanceRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/UpdateInstanceResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetReferentialRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetReferentialResponse.php');


//ajout du fichier de conf administrable en BO
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.admin.ini.php');



/**
    /_/module/boforms/BoForms_Administration_TestServices?service=getInstances
    /_/module/boforms/BoForms_Administration_TestServices?service=getInstanceById&code_instance=ACFR100100100001
    */
class BoForms_Administration_TestServices_Controller extends Pelican_Controller_Back
{
	protected $serviceSupported = array('getInstances','getInstanceById','getReferential');
	
	public function JiraAction()
	{
		$proxy_key = Pelican::$config['BOFORMS_BRAND_ID'] . '_PROXY';
		$configs = JiraUtil::getJiraConfiguration();
		
		echo "<form action='#' method='post'>
	    	URL Jira :<input size='50' type='text' name='JIRA_URL' value = '".($_POST['JIRA_URL']?$_POST['JIRA_URL']:Pelican::$config['BOFORMS_JIRA']['ISSUE_URL'])."' />
	    	<br/>
	    	Jira username : <input size='50' type='text' name='JIRA_USERNAME' value = '".($_POST['JIRA_USERNAME']?$_POST['JIRA_USERNAME']:$configs['JIRA_USERNAME'])."' />
	    	<br/>
	    	Jira password : <input size='50' type='text' name='JIRA_PASSWORD' value = '".($_POST['JIRA_PASSWORD']?$_POST['JIRA_PASSWORD']:$configs['JIRA_PASSWORD'])."' />		
	    	<br/>
	    	Clé projet : <input size='50' type='text' name='JIRA_PROJECT' value = '".($_POST['JIRA_PROJECT']?$_POST['JIRA_PROJECT']:Pelican::$config['BOFORMS_JIRA']['PROJECT_KEY'])."' />
	    	<br/>
	    	Proxy URL : <input size='50' type='text' name='PROXY_URL' value = '".($_POST['PROXY_URL']?$_POST['PROXY_URL']:Pelican::$config[$proxy_key]['URL'])."' />
	    	<br/>
	    	Proxy username : <input size='50' type='text' name='PROXY_USERNAME' value = '".($_POST['PROXY_USERNAME']?$_POST['PROXY_USERNAME']:Pelican::$config[$proxy_key]['LOGIN'])."' />
	    	<br/>
	    	Proxy password : <input size='50' type='text' name='PROXY_PASSWORD' value = '".($_POST['PROXY_PASSWORD']?$_POST['PROXY_PASSWORD']:Pelican::$config[$proxy_key]['PWD'])."' />
	    	<br/>
	    	<br/>
	    	rpi reporter task : <input size='50' type='text' name='WEBMASTER_RPI' value = '".($_POST['WEBMASTER_RPI']?$_POST['WEBMASTER_RPI']:Pelican::$config['BOFORMS_JIRA']['ASSIGNEE_NAME'])."' />
	    	<br/>
	    	rpi assign task  : <input size='50' type='text' name='ASSIGNEE_NAME' value = '".($_POST['ASSIGNEE_NAME']?$_POST['ASSIGNEE_NAME']:Pelican::$config['BOFORMS_JIRA']['ASSIGNEE_NAME'])."' />
	    	<br/>
	    	
	    	<input type='submit' name='submit' value='GO' />
	    		</form>
	    		";
		if($_POST['submit']){
					
			$url_jira_created = '';
			$key_jira_created = '';
			$proxy_key = Pelican::$config['BOFORMS_BRAND_ID'] . '_PROXY';
			 
			$webmaster_rpi = $_POST['WEBMASTER_RPI'];
			
		
			$data = array(
					'fields' => array(
							'project' => array('key' => $_POST['JIRA_PROJECT']),
							'summary' => $title . ' (demande de création) ' . $country_code,
							'assignee' => array('name' => $_POST['ASSIGNEE_NAME']),
							'reporter' => array('name' => $webmaster_rpi),
							'description' => 'test',
							"issuetype" => array(
									"id" => Pelican::$config['BOFORMS_JIRA']['ISSUE_TYPE']['DEMANDE_EVOLUTION'],
									"subtask" => false
							),
							"priority" => array(
									"id" => "1"
							)
					)
			);
				
			
			$ch = curl_init();
			$headers = array(
			    'Accept: application/json',
			    'Content-Type: application/json'
			);
			  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_URL, $_POST['JIRA_URL']);
			
					
			
			curl_setopt($ch, CURLOPT_USERPWD, $_POST['JIRA_USERNAME'] . ":" . $_POST['JIRA_PASSWORD']);
			
			if ($_POST['PROXY_URL'])
			{
				curl_setopt($ch, CURLOPT_PROXY , $_POST['PROXY_URL']);
				curl_setopt($ch, CURLOPT_PROXYUSERPWD , $_POST['PROXY_USERNAME'].':'.$_POST['PROXY_PASSWORD']);
				curl_setopt($ch, CURLOPT_PROXYTYPE , Pelican::$config[$proxy_key]['CURLPROXY_HTTP']);
			}
			
			
			$result = curl_exec($ch);
			$ch_error = curl_error($ch);
			  
			if ($ch_error) {
			    die("cURL Error: $ch_error");
			} else if ($result == '' || $result == null) {
				die("An error occurred. check jira configuration<br/>");
			} else {
				print_r($result);
				
			}
			  
			curl_close($ch);
					}
	}
	
	// gets username and password in the table psa_boforms_conf
	public static function getJiraConfiguration($crypt = false) {
		$oConnection = Pelican_Db::getInstance ();
		$aBind[':CONF_ID'] = 1; // jiras config
		$results = $oConnection->queryTab('select CONF_VALUE_ID, CONF_VALUE_KEY, CONF_VALUE from #pref#_boforms_conf where CONF_ID = :CONF_ID', $aBind);
		 
		// JIRA CONFIG
		$configs = array();
		for ($i = 0; $i < count($results); $i++) {
			if($results[$i]['CONF_VALUE_KEY']=='JIRA_PASSWORD')
			{
				if(!$crypt)
				{
					$results[$i]['CONF_VALUE'] = FunctionsUtils::f_decrypt(Pelican::$config['BOFORMS_PRIVATE_KEY'],$results[$i]['CONF_VALUE']);
				}
			}
			$configs[$results[$i]['CONF_VALUE_KEY']] = $results[$i]['CONF_VALUE'];
		}
		 
		return $configs;
	}
	
	public function BDDAction ()
	{
		
	    echo "<form action='#' method='post'>
	    	<textarea rows='15' cols='150' name='request'>".$_POST['request']."</textarea>
	    	<input type='submit' name='submit' value='GO' />
	    		</form>
	    		";
	    if($_POST['submit']){
	    	$oConnection = Pelican_Db::getInstance ();
	    	print_r($oConnection->queryTab($_POST['request']));
	    }
	
	}
	
	public function indexAction ()
	{
		$serviceGet = $_GET['service'];
		$code_instance = $_GET['code_instance'];
		$refType = $_GET['type'];
		
		$this->checkServiceParam($serviceGet,$code_instance,$refType);


		$country = FunctionsUtils::getCodePays();
		$brand = Pelican::$config['BOFORMS_BRAND_ID'];
		if(!empty($_GET['country']))
		{
			$country = $_GET['country'];
		}
		if(!empty($_GET['brand']))
		{
			$brand = $_GET['brand'];
		}

		$serviceParams = array(
				'country' => $country,
				'brand' => $brand,
				'instanceId' => $code_instance,
				'referentialType' => $refType
		);
		 
		$service = \Itkg\Service\Factory::getService(Pelican::$config['BOFORMS_BRAND_ID'] . '_SERVICE_BOFORMS', array());

		$client = $service->call($serviceGet.'Test', $serviceParams);

		if(is_null($client))
		{
			die('<br/><br/> Script Arrété, impossible de se connecter au Webservice');
		}
		
		$this->displayResult($client);
		
	}

	public function wsdlAction()
	{
		header("Content-type: text/xml");
		$page = file_get_contents(\Itkg::$config[Pelican::$config['BOFORMS_BRAND_ID'].'_SERVICE_BOFORMS']['PARAMETERS']['wsdl']);
		echo $page;
	}

	public function ShowFunctionsAction()
	{
		$option['wsdl'] = \Itkg::$config[Pelican::$config['BOFORMS_BRAND_ID'].'_SERVICE_BOFORMS']['PARAMETERS']['wsdl'];
		$option['location'] = \Itkg::$config[Pelican::$config['BOFORMS_BRAND_ID'].'_SERVICE_BOFORMS']['PARAMETERS']['location'];
		$option['http_auth_login'] = \Itkg::$config[Pelican::$config['BOFORMS_BRAND_ID'].'_SERVICE_BOFORMS']['PARAMETERS']['location'];
		$option['http_auth_password'] = \Itkg::$config[Pelican::$config['BOFORMS_BRAND_ID'].'_SERVICE_BOFORMS']['PARAMETERS']['location'];

		$client = new SoapClient($option['wsdl'], $option);
		var_dump($client->__getFunctions());
	}
	
	public function checkServiceParam($service, $code_instance = false,$refType = false)
	{
		
		if(empty($service))
		{
			die('erreur : paramètre "service" attendu<br/><br/>Service(s) supporté(s) :<br> - '. implode('<br> - ',$this->serviceSupported));
		}elseif (!in_array($service,$this->serviceSupported)) {
			die('erreur : le service "'.$service.'" n\'est pas supporté par le module de test.<br/><br/>Service(s) supporté(s) :<br> - '. implode('<br> - ',$this->serviceSupported));
		}
		
		if($service == "getInstanceById")
		{
			if(empty($code_instance))
			{
				die('erreur : paramètre "code_instance" attendu');
			}
		}
		
		if($service == "getReferential")
		{
			if(empty($refType))
			{
				die('erreur : paramètre "type" attendu (BRAND, CULTURE, FORM_TYPE, CUSTOMER_TYPE, DEVICE, FORM_CONTEXT, SITE)');
			}
		}
		
	}

	public function displayResult($client)
	{
		
		$html = "<fieldset>";
		$html .= "<legend><b> Site </b></legend>";
		$html .= "Site session actif : ".$this->getSiteSession();
		$html .= "</fieldset><br />";
		
		$html .= "<fieldset>";
		$html .= "<legend><b> Paramètre webservice </b></legend>";
		$html .= "<ul><li>location : ".$client['options']['location']."</li><li>wsdl : ".$client['options']['wsdl']."</li></ul>";
		$html .= "</fieldset><br />";
		
		$html .= "<fieldset>";
		$html .= "<legend><b> Requête </b></legend>";
		$html .= "requête envoyée :<br> <textarea readonly rows='15' cols='100'>".$client['client']->__getLastRequest()."</textarea><br/><br/>";
		$html .= "header de la requête :<br> <textarea readonly rows='8' cols='45'>".$client['client']->__getLastRequestHeaders()."</textarea>";
		$html .= "</fieldset><br />";
		
		$html .= "<fieldset>";
		$html .= "<legend><b> Réponse </b></legend>";
		$html .= "réponse reçu :<br> <textarea readonly rows='15' cols='100'>".$client['client']->__getLastResponse()."</textarea><br/><br/>";
		$html .= "header de la réponse :<br> <textarea readonly rows='8' cols='45'>".$client['client']->__getLastResponseHeaders()."</textarea>";
		$html .= "</fieldset>";
		
		echo $html;

	}
	
	public function getSiteSession()
	{
		$oConnection = Pelican_Db::getInstance ();
		$sqlSite = "select SITE_LABEL from #pref#_site where SITE_ID = ".$_SESSION[APP]['SITE_ID'];
		
		return $oConnection->queryItem($sqlSite);
		
	}
	
}