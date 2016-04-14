<?php

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local.ini.php');

class JiraUtil {
	public static function check_rpi($webmaster_rpi) {
		if (trim($webmaster_rpi) == '' || $webmaster_rpi == null) {
			die(t('BOFORMS_WARNING_EMPTY_SESSION'));
		}
		
		if ($webmaster_rpi == 'admin' || (! preg_match("#[a-zA-Z]{1}[0-9]{6}#", $webmaster_rpi))) {
   			return Pelican::$config['BOFORMS_JIRA']['ASSIGNEE_NAME'];
   		}
   		return $webmaster_rpi;
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
	
	
	public static function createJiraCreateForm($title, $description, $priority, $country_code, $webmaster_rpi) {
		$url_jira_created = ''; 
   		$key_jira_created = '';     
		$proxy_key = Pelican::$config['BOFORMS_BRAND_ID'] . '_PROXY';
   		
   		$webmaster_rpi = self::check_rpi($webmaster_rpi);		
   		
   		$data = array(
			'fields' => array(
				'project' => array('key' => Pelican::$config['BOFORMS_JIRA']['PROJECT_KEY']),
				'summary' => $title . ' (demande de création) ' . $country_code,
				'assignee' => array('name' => Pelican::$config['BOFORMS_JIRA']['ASSIGNEE_NAME']),
				'reporter' => array('name' => $webmaster_rpi),
				'description' => $description,
				"issuetype" => array(
			    	"id" => Pelican::$config['BOFORMS_JIRA']['ISSUE_TYPE']['DEMANDE_EVOLUTION'],	
			    	"subtask" => false
				),
				"priority" => array(
			    	"id" => "$priority"
				)			        
			)
		);
		 
		// ajout des autres destinataires
		if (count(Pelican::$config['BOFORMS_JIRA']['OTHER_ASSIGNEE']) > 0) {
			$data['fields']['customfield_10170'] = self::getOtherAssignee();
		}
		
		
		$ch = curl_init();
		$headers = array(
		    'Accept: application/json',
		    'Content-Type: application/json'
		);
		  
		$configs = self::getJiraConfiguration();
   		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_URL, Pelican::$config['BOFORMS_JIRA']['ISSUE_URL']);
		
		$configs = self::getJiraConfiguration();
		curl_setopt($ch, CURLOPT_USERPWD, $configs['JIRA_USERNAME'] . ":" . $configs['JIRA_PASSWORD']);
		
		if (Pelican::$config[$proxy_key]['URL'])
		{
			curl_setopt($ch, CURLOPT_PROXY , Pelican::$config[$proxy_key]['URL']);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD , Pelican::$config[$proxy_key]['LOGIN'].':'.Pelican::$config[$proxy_key]['PWD']);
			curl_setopt($ch, CURLOPT_PROXYTYPE , Pelican::$config[$proxy_key]['CURLPROXY_HTTP']);
		}
		
		$result = curl_exec($ch);
		$ch_error = curl_error($ch);
		
		if ($ch_error) {
		    die("cURL Error: $ch_error");
		} else if ($result == '' || $result == null) {
			die("An error occurred. check jira configuration<br/>");
		} else {
			$resultat = json_decode($result);
			if ($resultat->self) {
				$url_jira_created =  $resultat->self;
				$key_jira_created = $resultat->key;
			}else{
				echo "an error has occured";
				print_r($result);
				die('');
			}
		}
		  
		curl_close($ch);
		
		return array('url_jira_created' => $url_jira_created, 'key_jira_created' => $key_jira_created);
	}
	
	public static function createJiraAnomaly($title, $description, $file, $file_path, $priority, $country_code, $webmaster_rpi) {
		$url_jira_created = ''; 
   		$key_jira_created = '';     
		$proxy_key = Pelican::$config['BOFORMS_BRAND_ID'] . '_PROXY';
   		
   		$webmaster_rpi = self::check_rpi($webmaster_rpi);
   		
   		$data = array(
			'fields' => array(
				'project' => array('key' => Pelican::$config['BOFORMS_JIRA']['PROJECT_KEY']),
				'summary' => $title . ' (notification d’anomalie) ' . $country_code,
				'customfield_10300' => array('id' => Pelican::$config['BOFORMS_JIRA']['ENV'][Pelican::$config['BOFORMS_JIRA']['ENV2'][$_ENV["TYPE_ENVIRONNEMENT"]]]),
				'assignee' => array('name' => Pelican::$config['BOFORMS_JIRA']['ASSIGNEE_NAME']),
				'reporter' => array('name' => $webmaster_rpi),
				'description' => $description,
				"issuetype" => array(
				    'id' => Pelican::$config['BOFORMS_JIRA']['ISSUE_TYPE']['ANOMALIE'],
					"subtask" => false
				),
				"priority" => array(
			    	"id" =>  "$priority"
				)			   
			)
		);
		 
		// ajout des autres destinataires
		if (count(Pelican::$config['BOFORMS_JIRA']['OTHER_ASSIGNEE']) > 0) {
			$data['fields']['customfield_10170'] = self::getOtherAssignee();
		}
		
		
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
		curl_setopt($ch, CURLOPT_URL, Pelican::$config['BOFORMS_JIRA']['ISSUE_URL']);
		
		$configs = self::getJiraConfiguration();
		curl_setopt($ch, CURLOPT_USERPWD, $configs['JIRA_USERNAME'] . ":" . $configs['JIRA_PASSWORD']);
		
		if (Pelican::$config[$proxy_key]['URL'])
		{
			curl_setopt($ch, CURLOPT_PROXY , Pelican::$config[$proxy_key]['URL']);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD , Pelican::$config[$proxy_key]['LOGIN'].':'.Pelican::$config[$proxy_key]['PWD']);
			curl_setopt($ch, CURLOPT_PROXYTYPE , Pelican::$config[$proxy_key]['CURLPROXY_HTTP']);
		}
		
		
		$result = curl_exec($ch);
		$ch_error = curl_error($ch);
		  
		if ($ch_error) {
		    die("cURL Error: $ch_error");
		} else if ($result == '' || $result == null) {
			die("An error occurred. check jira configuration<br/>");
		} else {
			$resultat = json_decode($result);
			if ($resultat->self) {
				$url_jira_created =  $resultat->self;
				$key_jira_created = $resultat->key;
			}else{
				echo "an error has occured";
				print_r($result);
				die('');
			}
		}
		  
		curl_close($ch);
		
		if (file_exists($file_path) && $url_jira_created != '' && $file != '') {
			self::addAttachmentForJira($url_jira_created, $file, $file_path);
		}

		return array('url_jira_created' => $url_jira_created, 'key_jira_created' => $key_jira_created);
	}

	// ex $jira_url = 'https://jira-projets.mpsa.com/SCDV/rest/api/2/issue/436439'
	public static function updateJira($jira_url) {
		$proxy_key = Pelican::$config['BOFORMS_BRAND_ID'] . '_PROXY';
   		
		$data = array(
			'fields' => array(
				'description' => $description
			)
		);
		
		$ch = curl_init();
		$headers = array('Accept: application/json', 'Content-Type: application/json');
		  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_URL, $jira_url);
		
		$configs = self::getJiraConfiguration();
		curl_setopt($ch, CURLOPT_USERPWD, $configs['JIRA_USERNAME'] . ":" . $configs['JIRA_PASSWORD']);
		  
		if (Pelican::$config[$proxy_key]['URL'])
		{
			curl_setopt($ch, CURLOPT_PROXY , Pelican::$config[$proxy_key]['URL']);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD , Pelican::$config[$proxy_key]['LOGIN'].':'.Pelican::$config[$proxy_key]['PWD']);
			curl_setopt($ch, CURLOPT_PROXYTYPE , Pelican::$config[$proxy_key]['CURLPROXY_HTTP']);
		}
		
		$result = curl_exec($ch);
		$ch_error = curl_error($ch);
		  
		if ($ch_error) {
		    echo "cURL Error: $ch_error";
		} else {
			$resultat = json_decode($result);
		}
		curl_close($ch);		  
	}
	
   	public static function createJiraDemandeEvolution($title, $description, $file, $file_path, $priority, $code_pays, $webmaster_rpi) {
		$url_jira_created = ''; 
   		$key_jira_created = '';     
		$proxy_key = Pelican::$config['BOFORMS_BRAND_ID'] . '_PROXY';
   		
   		$webmaster_rpi = self::check_rpi($webmaster_rpi);
   		
   		$data = array(
			'fields' => array(
				'project' => array('key' => Pelican::$config['BOFORMS_JIRA']['PROJECT_KEY']),
				'summary' => $title . ' (demande d’évolution) ' . $code_pays,
				'assignee' => array('name' => Pelican::$config['BOFORMS_JIRA']['ASSIGNEE_NAME']),
				'reporter' => array('name' => $webmaster_rpi),
				'description' => $description,
				"issuetype" => array(
			    	"id" => Pelican::$config['BOFORMS_JIRA']['ISSUE_TYPE']['DEMANDE_EVOLUTION'],	
			    	"subtask" => false
				),
				"priority" => array(
			    	"id" => "$priority"
				)			        
			)
		);
		
		// ajout des autres destinataires
		if (count(Pelican::$config['BOFORMS_JIRA']['OTHER_ASSIGNEE']) > 0) {
			$data['fields']['customfield_10170'] = self::getOtherAssignee();
		}
		 
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
		curl_setopt($ch, CURLOPT_URL, Pelican::$config['BOFORMS_JIRA']['ISSUE_URL']);
		
		$configs = self::getJiraConfiguration();
		curl_setopt($ch, CURLOPT_USERPWD, $configs['JIRA_USERNAME'] . ":" . $configs['JIRA_PASSWORD']);
		  
		
		if (Pelican::$config[$proxy_key]['URL'])
		{
			curl_setopt($ch, CURLOPT_PROXY , Pelican::$config[$proxy_key]['URL']);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD , Pelican::$config[$proxy_key]['LOGIN'].':'.Pelican::$config[$proxy_key]['PWD']);
			curl_setopt($ch, CURLOPT_PROXYTYPE , Pelican::$config[$proxy_key]['CURLPROXY_HTTP']);
		}
		
		$result = curl_exec($ch);
		$ch_error = curl_error($ch);
		  
		if ($ch_error) {
		    die("cURL Error: $ch_error");
		} else if ($result == '' || $result == null) {
			die("An error occurred. check jira configuration<br/>");
		} else {
			// jira créée
			// exemple de json retourné:
			// {"id":"436439","key":"BOFPTEST-4","self":"https://jira-projets.mpsa.com/SCDV/rest/api/2/issue/436439"}
			
			$resultat = json_decode($result);
			if ($resultat->self) {
				$url_jira_created =  $resultat->self;
				$key_jira_created = $resultat->key;
			}else{
				echo "an error has occured";
				print_r($result);
				die('');
			}
		}
		curl_close($ch);
		  
		if (file_exists($file_path) && $url_jira_created != '' && $file != '') {
			self::addAttachmentForJira($url_jira_created, $file, $file_path);
		}		
		
		return array('url_jira_created' => $url_jira_created, 'key_jira_created' => $key_jira_created);
	}
	
	// autres destinataires
	private function getOtherAssignee() {
		$tmp_tbl = array();
		for ($cpt = 0; $cpt < count(Pelican::$config['BOFORMS_JIRA']['OTHER_ASSIGNEE']); $cpt++) {
			$tmp_tbl[$cpt] = array('name' => Pelican::$config['BOFORMS_JIRA']['OTHER_ASSIGNEE'][$cpt]); 
		}
		return $tmp_tbl;
	}
   	
	private function addAttachmentForJira($url_jira_created, $file, $file_path) {
			$proxy_key = Pelican::$config['BOFORMS_BRAND_ID'] . '_PROXY';
   		  	
			$url = $url_jira_created . '/attachments';
		    $data = array('file'=>"@{$file_path};filename={$file}");
		    
		    $headers = array('X-Atlassian-Token: nocheck');
		  
		    $curl = curl_init();
		    
			$configs = self::getJiraConfiguration();
			curl_setopt($curl, CURLOPT_USERPWD, $configs['JIRA_USERNAME'] . ":" . $configs['JIRA_PASSWORD']);
			
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		    curl_setopt($curl, CURLOPT_VERBOSE, 1);
		    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		  
		    if (Pelican::$config[$proxy_key]['URL'])
		    {
		    	curl_setopt($curl, CURLOPT_PROXY , Pelican::$config[$proxy_key]['URL']);
		    	curl_setopt($curl, CURLOPT_PROXYUSERPWD , Pelican::$config[$proxy_key]['LOGIN'].':'.Pelican::$config[$proxy_key]['PWD']);
		    	curl_setopt($curl, CURLOPT_PROXYTYPE , Pelican::$config[$proxy_key]['CURLPROXY_HTTP']);
		    }
		    
		    $result = curl_exec($curl);
		    $ch_error = curl_error($curl);
		       
		    /* if ($ch_error) {echo "cURL Error: $ch_error";} else {echo $result;} */
		    curl_close($curl);
	}	
	
}

// exemple de requête pour trouver les metadatas (informations sur les champs)
// pour une jira: https://jira-projets.mpsa.com/SCDV/rest/api/2/issue/436439/editmeta