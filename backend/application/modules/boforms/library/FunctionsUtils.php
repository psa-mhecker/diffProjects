<?php 
class FunctionsUtils
{
	public static function getJsTranslations($tbl_keys,$langue_id=false) {
				
		if(!$langue_id)
		{
			$str_translations = 'var lang=new Object();';
		}
		
    	
    	for ($i = 0; $i < count($tbl_keys) ; $i++) {
    		$key = $tbl_keys[$i];
    		if($langue_id)
    		{
    			$str_translations .= "lang['$key'] = '" . str_replace("'","\'", FunctionsUtils::translateEditor($key , $langue_id)) . "';";
    		}else{
    			$str_translations .= "lang['$key'] = '" . str_replace("'","\'", t($key)) . "';";	
    		}
    	}
    	return $str_translations;
    }
    
    public static function includeJsAndCss($head, $tbl_css, $tbl_js) {
    	for ($i = 0; $i < count($tbl_css); $i++) {
    		$head->setCss(Pelican_Plugin::getMediaPath('boforms') . $tbl_css[$i]);
    	}
    	
    	for ($i = 0; $i < count($tbl_js); $i++) {
    		$head->setJs(Pelican_Plugin::getMediaPath('boforms') . $tbl_js[$i]);
    	}
	}
	
	public static function getSiteLabelFromId($site_id) {
   		$oConnection = Pelican_Db::getInstance ();
   		$aBind[':SITE_ID'] = $site_id;
   		return $oConnection->queryItem('SELECT SITE_LABEL FROM `#pref#_site` where site_id = :SITE_ID', $aBind);
   	}
   	
   	public static function getCultureByCodeInstance($codeInstance)
   	{
   		$oConnection = Pelican_Db::getInstance ();
   		  		 
   		$select = 'CULTURE_KEY';
   		
   		$sqlCultureDefaut = "select $select
    							from #pref#_boforms_formulaire bf
    						    INNER JOIN #pref#_boforms_culture bc on (bf.CULTURE_ID=bc.CULTURE_ID)
    				            where FORM_INCE = '".$codeInstance."'";
   		 
   	
   		$langue = $oConnection->queryItem($sqlCultureDefaut);
   		 
   		
   		return $langue;
   		 
   	}
   	
   	public static function getDefaultCulture($format=false)
   	{
   		$oConnection = Pelican_Db::getInstance ();
   		$codePays=self::getCodePays();
   		$brand=Pelican::$config['BOFORMS_BRAND_ID'];
   		
   		$sqlCultureDefaut = "select bf.CULTURE_ID
    							from #pref#_boforms_formulaire bf
    						    INNER JOIN #pref#_boforms_formulaire_site bfs on (bfs.FORMSITE_ID=bf.FORMSITE_ID)
    							INNER JOIN #pref#_boforms_groupe bg on (bg.FORMSITE_ID_MASTER=bfs.FORMSITE_ID AND bg.GROUPE_ID=".$_GET['groupe_id'].")
    				            where FORM_GENERIC = 1
    							AND FORM_BRAND = '".$brand."'
        				        AND PAYS_CODE = '".$codePays."' limit 1";
   		

   		$langue = $oConnection->queryItem($sqlCultureDefaut);
   		
   		if(empty($langue))
   		{
   			echo "No Instance found"; die;
   		}
   		
   		if($format && (int)$langue<10)
   		{
   			$langue = '0'.$langue;
   		}
   		
   		return $langue;
   		
   	}
   	
   	
	public static function cleanXML($sXml,$flag=false)
	{
		if($flag==true)
		{	
			$sXml = str_replace('<?xml version="1.0" encoding="UTF-8"?>',"",$sXml);
		}
		$sXml = preg_replace("/itkg_code=\".*?\"/", "", $sXml);
		$sXml = str_replace("itkg_gtm_data=\"1\"", "", $sXml); // delete gtm tag markup
		$sXml = str_replace('xmlns2=', 'xmlns=', $sXml);
		
		return $sXml;
	}


    public static function getCodePays(){
    	    	
    	$oConnection = Pelican_Db::getInstance ();
    	 
    	$sqlCodeLangue = "SELECT SITE_CODE_PAYS FROM #pref#_site_code
					  WHERE site_id = :SITE_ID";
    	$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
    	
    	return $oConnection->queryItem($sqlCodeLangue, $aBind);
    }	

    public static function getCountryLabelFromCode() {
    	$oConnection = Pelican_Db::getInstance ();
    	 
    	$sqlCodeLangue = "SELECT c.nom_fr_fr  FROM #pref#_site_code sc inner join #pref#_boforms_country c
				on c.alpha2  = sc.SITE_CODE_PAYS	  
    			WHERE sc.site_id = :SITE_ID";
    	$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
    	    	
    	return $oConnection->queryItem($sqlCodeLangue, $aBind);
    }
    
    public static function getFormulaireFromCode($sCode, $draft = 'DRAFT') {
		$oConnection = Pelican_Db::getInstance ();
    	$aBind[':SCODE'] = $oConnection->strToBind( $sCode );
    	$sqlForm = 'select f.FORM_INCE,f.FORM_NAME,f.FORM_CONTEXT,f.FORM_CURRENT_VERSION,f.FORM_DRAFT_VERSION,f.FORM_PARENT_INCE,f.DEVICE_ID,f.FORM_EDITABLE, f.FORM_COMMENTARY ,
    					   v.FORM_XML_CONTENT, t.TARGET_KEY, o.OPPORTUNITE_KEY, d.DEVICE_KEY, c.CONTEXT_KEY
    				from #pref#_boforms_formulaire f 
    				inner join #pref#_boforms_opportunite o on o.OPPORTUNITE_ID = f.OPPORTUNITE_ID
    				inner join #pref#_boforms_device d on d.DEVICE_ID = f.DEVICE_ID
    				inner join #pref#_boforms_target t on t.target_id = f.TARGET_ID
    				inner join #pref#_boforms_context c on c.CONTEXT_ID = f.FORM_CONTEXT
    				inner join #pref#_boforms_formulaire_version v on v.FORM_INCE = f.FORM_INCE  and v.FORM_VERSION = f.FORM_' .
    				 $draft . '_VERSION where f.FORM_INCE = :SCODE';
    	return $oConnection->queryRow($sqlForm, $aBind);    		
   	}
   	
   	// retourne la liste des devices
   	public static function getDeviceList() {
   		$oConnection = Pelican_Db::getInstance ();
    	$sql = "SELECT DEVICE_ID, DEVICE_KEY FROM `#pref#_boforms_device`";
    	
    	return $oConnection->queryTab($sql);
   	}
   	
   	
    // returns all the form types
   	public static function getOpportunitiesList($aExclude=false) { 
   		$oConnection = Pelican_Db::getInstance ();
   		
   		if(is_array($aExclude) && !empty($aExclude))
   		{
   			$tab = $oConnection->queryTab("SELECT OPPORTUNITE_ID, OPPORTUNITE_KEY FROM `#pref#_boforms_opportunite` WHERE OPPORTUNITE_KEY NOT IN (".implode(", ",$aExclude).")");
   		}else{
   			$tab = $oConnection->queryTab('SELECT OPPORTUNITE_ID, OPPORTUNITE_KEY FROM `#pref#_boforms_opportunite`');
   		}
   		
   		for($i = 0; $i < count($tab); $i++) {
   			$tab[$i]['OPPORTUNITE_LABEL'] = t('BOFORMS_REFERENTIAL_FORM_TYPE_' . $tab[$i]['OPPORTUNITE_KEY']); 
   		}
   		return $tab;
   	}
   	
   	public static function getFormulaireSite() {
   		$oConnection = Pelican_Db::getInstance ();
   		$tab = $oConnection->queryTab('SELECT fs.FORMSITE_ID, fs.FORMSITE_KEY FROM #pref#_boforms_formulaire_site fs
   				                       INNER JOIN #pref#_boforms_groupe_formulaire gf ON fs.FORMSITE_ID=gf.FORMSITE_ID
   				                       INNER JOIN #pref#_boforms_groupe g ON g.GROUPE_ID=gf.GROUPE_ID
   				                       WHERE g.SITE_ID = ' .  $_SESSION[APP]['SITE_ID']);
   		$result = array();
   		for($i = 0; $i < count($tab); $i++) {
   			$result[str_pad($tab[$i]['FORMSITE_ID'], 2 , "0", STR_PAD_LEFT)] = t('BOFORMS_FORMSITE_LABEL_' . $tab[$i]['FORMSITE_KEY']);   
   		}
   		return $result;
   	}
   	
	public static function getAllFormulaireSite() {
   		$oConnection = Pelican_Db::getInstance ();
   		$tab = $oConnection->queryTab('SELECT FORMSITE_ID, FORMSITE_KEY FROM #pref#_boforms_formulaire_site ');
   		$result = array();
   		for($i = 0; $i < count($tab); $i++) {
   			$result[str_pad($tab[$i]['FORMSITE_ID'], 2 , "0", STR_PAD_LEFT)] = t('BOFORMS_FORMSITE_LABEL_' . $tab[$i]['FORMSITE_KEY']);   
   		}
   		return $result;
   	}
   	
   	// return all targets
   	public static function getTargetListForRadio() {
   		$tbl_result = array();
   		$oConnection = Pelican_Db::getInstance ();
   		$tab = $oConnection->queryTab('select TARGET_ID, TARGET_KEY from #pref#_boforms_target where target_id in (1,2)');
   		for($i = 0; $i < count($tab); $i++) {
   			$tbl_result[$tab[$i]['TARGET_ID']] = t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_' . $tab[$i]['TARGET_KEY']); 
   		}
   		return $tbl_result;
   	}

 	public static function getTemplateId($path) {
 		$oConnection = Pelican_Db::getInstance ();
 		$aBind[':PATH'] = $oConnection->strToBind($path);
   		return $oConnection->queryItem("SELECT TEMPLATE_ID FROM #pref#_template WHERE TEMPLATE_PATH = :PATH", $aBind);
 		
 	}  

 	
 	//patch Jira BOFORMS-310
 	public static function setLabelTranslate($langue_id) {
 		
 			$oConnection = Pelican_Db::getInstance ();
	 		$aBind[':LANGUE_ID'] = $langue_id;
	 		
	 		$aTab = $oConnection->queryTab("SELECT LABEL_ID, LABEL_TRANSLATE FROM #pref#_label_langue WHERE LANGUE_ID = :LANGUE_ID AND LABEL_ID LIKE 'BOFORMS_LABEL_%'", $aBind);
		
	 		$_SESSION[APP]['BOFORMS_LABEL']['LANGUE_ID']=$langue_id;
	 		$_SESSION[APP]['BOFORMS_LABEL']['LIST']=array();
	 		
	 		if(is_array($aTab) && !empty($aTab))
	 		{
	 			foreach($aTab as $row)
	 			{
	 				$_SESSION[APP]['BOFORMS_LABEL']['LIST'][$row['LABEL_ID']]=$row['LABEL_TRANSLATE'];
	 			}
	 		}
 		
 	}
 	
 	public static function translateEditor($label,$langue_id=1)
 	{
 	
 		$langue_id = (int)$langue_id;
 		
 		if($langue_id==10)// 10 culture FR
 		{
 			$langue_id=1;//langue francais
 		}else{
 			$langue_id=2;//langue anglais
 		}
 		 		
 		if($_SESSION[APP]['BOFORMS_LABEL']['LANGUE_ID']!=$langue_id)
 		{
 			FunctionsUtils::setLabelTranslate($langue_id);
 		}		
 		
 		return $_SESSION[APP]['BOFORMS_LABEL']['LIST'][$label];
 	}
 //fin jira 310
   	
 	public static function getOpportuniteByFormTypeCPP($formTypeCPP)
 	{
 		$oConnection = Pelican_Db::getInstance();
 		
 		$sql = "select FORM_INCE_CODE 
 				from #pref#_form 
 				where FORM_TYPE_ID=$formTypeCPP 
 				AND SITE_ID =".$_SESSION[APP]['SITE_ID']."
 				LIMIT 1";
 		$code = $oConnection->queryItem($sql);
 		
 		if($code)
 		{
 			$code = substr($code,14,2);
 			return (int)$code;
 		}else{
 			return $formTypeCPP;
 		}
 		
 	}
 	
 	public static function getFormTypeCPPByFormOpportunite($opport_id)
 	{
 		$oConnection = Pelican_Db::getInstance();
 			
 		$sql = "select FORM_TYPE_ID
 		from #pref#_form
 		where FORM_INCE_CODE LIKE '%$opport_id'
 		AND SITE_ID =".$_SESSION[APP]['SITE_ID']."
 		LIMIT 1";
 		$code = $oConnection->queryItem($sql);
 	 			
 	 		if($code)
 	 		{
 	 		$code = $code;
 	 		return (int)$code;
 	 		}else{
 	 		return $opport_id;
 	 		}
 	 			
 	 }

  public static function getDateFormat($date,$lang)
  {
    if($lang==1) //fr
    {
      $aDate = explode(" ", $date);

      $heure = $aDate[1];

      $aDate = explode('-', $aDate[0]);
      
      return $aDate[2]."/".$aDate[1]."/".$aDate[0] ." ". $heure ;
    }else{
      return $date;
    }
  }
  

  public function f_crypt($private_key, $str_to_crypt) {
  	$private_key = md5($private_key);
  	$letter = -1;
  	$new_str = '';
  	$strlen = strlen($str_to_crypt);
  
  	for ($i = 0; $i < $strlen; $i++) {
  		$letter++;
  		if ($letter > 31) {
  			$letter = 0;
  		}
  		$neword = ord($str_to_crypt{$i}) + ord($private_key{$letter});
  		if ($neword > 255) {
  			$neword -= 256;
  		}
  		$new_str .= chr($neword);
  	}
  	return base64_encode($new_str);
  }
  
  public function f_decrypt($private_key, $str_to_decrypt) {
  	$private_key = md5($private_key);
  	$letter = -1;
  	$new_str = '';
  	$str_to_decrypt = base64_decode($str_to_decrypt);
  	$strlen = strlen($str_to_decrypt);
  	for ($i = 0; $i < $strlen; $i++) {
  		$letter++;
  		if ($letter > 31) {
  			$letter = 0;
  		}
  		$neword = ord($str_to_decrypt{$i}) - ord($private_key{$letter});
  		if ($neword < 1) {
  			$neword += 256;
  		}
  		$new_str .= chr($neword);
  	}
  	return $new_str;
  }
  
  
  public function isLandingPageSite($siteId) {
      if(in_array($siteId, Pelican::$config['LANDING_PAGE_SITE_ID'])) {
          return true;
      }
      return false;
  }

  public function checkBlockEdito() {
  	if(empty(Pelican::$config['BOFORMS_BLOC_EDITO_FORMS'])) {
  		return false;
  	}
  	
  	$oConnection = Pelican_Db::getInstance();
  	$sSQL = "SELECT ZONE_ID FROM #pref#_zone where ZONE_BO_PATH = '".Pelican::$config['BOFORMS_BLOC_EDITO_FORMS']."'";
  	$zoneid = $oConnection->queryItem($sSQL);
  	
  	if(!empty($zoneid)) {
  		return true;
  	}
  	
  	return false;
  }
  
	public function isMultilingualSite($site_id) {
    	$oConnection = Pelican_Db::getInstance ();
    	$aBind[':SITE_ID'] = $site_id;
    	$the_number = $oConnection->queryItem("SELECT count(*) as nb
													FROM #pref#_site_code ps
													INNER JOIN #pref#_site_language psl ON psl.SITE_ID = ps.SITE_ID
													INNER JOIN #pref#_language pl ON pl.LANGUE_ID = psl.LANGUE_ID
													inner join #pref#_boforms_country c on SITE_CODE_PAYS = c.alpha2
													where ps.site_id = :SITE_ID", $aBind);
    	return $the_number > 1;													
	}

	public function getCountryFieldForLanguage($langue_id) {
    	if ($langue_id == 1) {
    		return "nom_fr_fr";
    	} else {
    		return "nom_en_gb";
    	}
    }
    
	public function getSiteLabelFromGroupeID($groupe_id) {
    	$oConnection = Pelican_Db::getInstance ();
    	$aBind[':GROUPE_ID'] = $groupe_id;
    	$tab_form_site = $oConnection->queryTab('select fs.FORMSITE_KEY 
    											from #pref#_boforms_formulaire_site fs
    											inner join #pref#_boforms_groupe_formulaire gf on fs.FORMSITE_ID = gf.FORMSITE_ID 
    											where gf.GROUPE_ID = :GROUPE_ID', $aBind); 

    	$str_label = '';
    	for ($i = 0; $i < count($tab_form_site); $i++) {
    		$form_site_key = $tab_form_site[$i]['FORMSITE_KEY'];
    		if ($str_label != '') {
    			$str_label .= ', ';
    		}
    		$str_label  .= t('BOFORMS_FORMSITE_LABEL_' . $form_site_key);
    	}
    	
    	return $str_label;
    }
    
    
    public function objectToArray($d) {
    	if (is_object($d)) {
    		$d = get_object_vars($d);
    	}
    	
    	if (is_array($d)) {
    		return array_map(__FUNCTION__, $d);
    	}
    	else {
    		return $d;
    	}
    }
    
    // gets the path for the log files
    public function getLogPath() {
    	if(empty(Pelican::$config['BOFORMS_LOG_PATH']))
    	{
    		return Pelican::$config["PLUGIN_ROOT"] . '/boforms/var/log/';
    	}
    	
    	$path = Pelican::$config['BOFORMS_LOG_PATH'];
    	if (substr(Pelican::$config['BOFORMS_LOG_PATH'], -1) != '/') {
    		$path .= '/';
    	}
    	return $path;
    }
    
    public function updateReferences()
    {
    	$oConnection = Pelican_Db::getInstance();

    	foreach(Pelican::$config['BOFORMS_REFERENTIAL_TYPE_UPDATE'] as $referential_type => $values) {
    		$table = '#pref#_' . $values['table'];
    		$prefix = $values['prefix'];
    		$field_id = $prefix . 'ID';
    		$field_key = $prefix . 'KEY';
    
    		// call ws
    		$serviceParams = array(
    				'referentialType' => $referential_type
    		);
    		$service = \Itkg\Service\Factory::getService(Pelican::$config['BOFORMS_BRAND_ID'] . '_SERVICE_BOFORMS', array());
    		$response = $service->call('getReferential', $serviceParams);
    
    		// update the referential
    		if($response[0]->type == $referential_type)
    		{
    			$oConnection->query("DELETE FROM $table");
    			$sSqlRef = "REPLACE INTO $table ( $field_id , $field_key ) VALUES (:refCode,:label)";
    
    			//ticket 541, on force les types LANDING PAGE
    			if ($referential_type == 'FORM_TYPE'){
    				
    				if(!empty(Pelican::$config['TYPE_LANDING_PAGE']) && is_array(Pelican::$config['TYPE_LANDING_PAGE'])){
    					foreach (Pelican::$config['TYPE_LANDING_PAGE'] as $LP)
    					{
    						$aBind[':label'] = $oConnection->strToBind($LP['label']);
    						$aBind[':refCode'] = (int)$LP['refCode'];
    						$oConnection->query($sSqlRef,$aBind);
    					}
    				}
    			}
    			//
    	   
    			foreach($response as $k=>$ref)
    			{
    				$aBind=array();
    
    				if ($referential_type == 'BRAND') {
    					$aBind[':refCode'] = $oConnection->strToBind($ref->refCode);
    				} else {
    					$aBind[':refCode'] = (int)$ref->refCode;
    				}
    				
    				$aBind[':label'] = $oConnection->strToBind($ref->label);
    
    		    	if($sSqlRef)
    		    	{
    		    		$oConnection->query($sSqlRef,$aBind);
    				}
    			}
    
    		}
    
    	}
    }
}
