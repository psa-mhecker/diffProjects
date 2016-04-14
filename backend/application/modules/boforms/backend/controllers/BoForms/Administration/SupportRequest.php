<?php
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local.ini.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/BoForms.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FormInstance.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/XMLHandle.class.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/JiraUtil.php');

pelican_import('Mail', 'Zend');

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

include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FunctionsUtils.php');

//ajout du fichier de conf administrable en BO
include(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.admin.ini.php');


/**
 * controleur form support request and jiras
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Hervé <herve.lechevallier@businessdecision.com>
 * @since 15/01/2014
 */

class BoForms_Administration_SupportRequest_Controller extends Pelican_Controller_Back
{
	protected $administration = true;

    protected $form_name = "boforms_formulaire";

    protected $bNewInstance = false;
    protected $field_id = " FORM_INCE";

    protected $defaultOrder = "OPPORTUNITE_ID,DEVICE_ID,TARGET_ID,bf.FORM_CONTEXT";

    protected $aPersoStructure = array(); //structure page/fieldSet/question/line du fomulaire aprés modification
    
    protected $aGlobalPersoStructure = array(); //structure sur un niveau
    
    protected $aPages = array(); //info etape
    protected $aPageStructure = array(); //structure niveau etape
    
    protected $aPerso = array(); //structure niveau field
    
    protected $aPersoKey = array(); // structure niveau field sur un niveau
    
    protected $oXMLOriginal; // objet XMLHandle du formulaire avant modification
    
    protected $oXMLGeneric; // objet XMLHandle du formulaire générique
    protected $get_instance;
   
    protected $bDraft = false;
    protected $bDraftAuto = false;
    
    protected $langue_default;
    
	public function supportDialogNewFormAction() {
		$head = $this->getView()->getHead();

		$groupe_id = $this->getParam('groupe_id');
		
		/*site id*/
    	$oConnection = Pelican_Db::getInstance ();
    	$sSQL = "SELECT FORMSITE_ID_MASTER FROM #pref#_boforms_groupe where GROUPE_ID=".$groupe_id;
    	$formsiteid = $oConnection->queryItem($sSQL);
    	
    	/* liste des cultures separees par des virgules ex: FR, NL */
    	$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
    	$culture_tab = $oConnection->queryTab('SELECT CULTURE_KEY FROM #pref#_boforms_culture c inner join #pref#_site_language l on l.langue_id = c.langue_id
where l.site_id = :SITE_ID', $aBind);
    	
    	$culture_str = '';
    	for ($i = 0 ; $i < count($culture_tab); $i++) {
    		if ($culture_str != '') {
    			$culture_str .= ', ';
    		}
    		$culture_str .= strtoupper($culture_tab[$i]['CULTURE_KEY']);
    	}
    	
		// css and js
		$tbl_css = array('css/bootstrap.min.css', 'css/jquery-ui.css', 'css/jquery.loader.css', 'editor.css');
		$tbl_js = array('js/jquery.min.js', 'js/jquery-ui.min.js', 'js/json2.js', 'js/knockout-latest.debug.js', 'js/knockout.mapping-latest.js', 
						'js/bootstrap-tabs.js', 'js/jquery.hotkeys.js', 'js/jquery.loader.js', 'js/knockout-jqueryui.min.js', 'supportDialogNewForm.js?t=' . time()); 
    	FunctionsUtils::includeJsAndCss($head, $tbl_css, $tbl_js);
    	
    	// javascript lang array
		$tbl_lang = array('BOFORMS_POPUP_CREATE_NEW_FORM_FORM_NOT_FOUND', 'BOFORMS_SUPPORT_CHOOSE_PRIORITY','BOFORMS_REQUEST_BLOCKING',
						  'BOFORMS_REQUEST_MAJOR', 'BOFORMS_REQUEST_MINOR','BOFORMS_POPUP_CREATE_NEW_FORM_CHOOSE_FORM_TYPE',
						  'BOFORMS_LABEL_LEGAL_MENTION_CPP_ANSWER','BOFORMS_LABEL_SBS_COM_OFFER','BOFORMS_LABEL_SBS_USR_OFFER',
						  'BOFORMS_LABEL_SBS_COM_OFFER_2','BOFORMS_LABEL_SBS_USR_OFFER_2', 'BOFORMS_LABEL_SBS_USR_OFFER_2_LP',
						  'BOFORMS_LABEL_REQUEST_INTEREST_FINANCING',
				          'BOFORMS_LABEL_REQUEST_INTEREST_INSURANCE','BOFORMS_LABEL_REQUEST_INTEREST_SERVICE',
				          'BOFORMS_LABEL_UNS_NWS_CPP_MOTIF', 'BOFORMS_LABEL_GET_MYCITROEN','BOFORMS_LABEL_REQUEST_CALLBACK', 
				          'BOFORMS_LABEL_SBS_USER_OFFER','BOFORMS_LABEL_MULTIFORMS_CHOICE', 'BOFORMS_LABEL_SBS_NWL_NEWS'
		);
		$list_translations_js = FunctionsUtils::getJsTranslations($tbl_lang);
		$list_oportunities_js = $this->createOpportunitiesJs(FunctionsUtils::isLandingPageSite($formsiteid));
		
		// on ajoute un choix autre
		$list_oportunities_js = $list_oportunities_js . "tmpOpp = new Object();tmpOpp.id = '-1';tmpOpp.name = '" . 
									str_replace("'","\'", t('BOFORMS_REFERENTIAL_FORM_TYPE_OTHER')) . "';listOpportunities.push(tmpOpp);";
   		
		
		$head->setScript($list_translations_js . $list_oportunities_js . 
			" var sr_country_code = '" . FunctionsUtils::getCountryLabelFromCode() . "';" . 
			" var groupe_id = '" . $groupe_id . "';" .
			" var sr_culture_str = '" . $culture_str . "';" .
			" var sr_site = '" . $_SESSION[APP]['SITE_ID']  . "';" .
			" var sr_rpi = '" . $_SESSION[APP]['user']['id'] . "';" .
			" var sr_webmaster_name = '" . $_SESSION[APP]['user']['name'] . "';" .
			" var sr_environnement = '" . $_ENV["TYPE_ENVIRONNEMENT"] . "';" 
		);
		
		$this->assign('header', $head->getHeader(false), false);
		
		$this->fetch();
	}
	
	// utilise par supportDialogNewForm pour trouver les champs pour un nouveau formulaire
	public function getFormComponentsAgregateAction() {
		$cible_part = $this->getParam('cible_part');
		$cible_pro = $this->getParam('cible_pro');
		$device_mobile = $this->getParam('device_mobile');
		$device_web = $this->getParam('device_web');
		$formulaire = $this->getParam('formulaire');
		$groupe_id = $this->getParam('groupe_id');
		
		$cible = array();
		if ($cible_part == '1') {
			$cible[] = 1;
		}
		if ($cible_pro == '1') {
			$cible[] = 2;
		}
		
		$device = array();
		if ($device_web == '1') {
			$device[] = 0;
		}
		if ($device_mobile == '1') {
			$device[] = 1;
		}
				
		//$sCode = "ACBE100100101001"; // TODO modify
		
		/*site id*/
    	$oConnection = Pelican_Db::getInstance ();
    	$sSQL = "SELECT FORMSITE_ID_MASTER FROM #pref#_boforms_groupe where GROUPE_ID=".$groupe_id;
    	$formsiteid = $oConnection->queryItem($sSQL);
    	if((int)$formsiteid<10){ $formsiteid = '0'.$formsiteid; }
    	
    	$code_pays = FunctionsUtils::getCodePays();
   		$default_culture = FunctionsUtils::getDefaultCulture();
    	
   		$tbl_interm = array();
   		for ($i = 0; $i < count($device); $i++) {
   			$device_tmp = $device[$i];
   			for ($j = 0; $j < count($cible); $j++) {
   				$cible_tmp = $cible[$j];
   				$tbl_datas = $this->getFormComponentsForDevice($device_tmp, $code_pays, $cible_tmp, $formsiteid, $default_culture, $formulaire);

   				for ($z = 0; $z < count($tbl_datas); $z++) {
   					$tbl_interm[$tbl_datas[$z]['label']] = $tbl_datas[$z]['required_central']; 
   				}
   			}   		
   		}
   		
   		$tbl_result = array();
   		foreach ($tbl_interm as $key => $value) {
   			$tbl_result[] = array('label' => $key, 'required_central' => $value); 
   		}
   		
		echo json_encode(array("result" => $tbl_result));
		exit(0);
	}
	
   	// displays the support dialog windows (specs: 5.4.2.4 UC303  Contacter Support)
   	public function supportDialogAction() {
   		// headers
        $head = $this->getView()->getHead();
  	
        // css and js
        $tbl_css = array('css/bootstrap.min.css', 'css/jquery-ui.css', 'css/jquery.loader.css');	
    	$tbl_js = array('js/jquery.min.js', 'js/json2.js', 'js/knockout-latest.debug.js', 'js/knockout.mapping-latest.js', 'supportDialog.js?t=' . time()); 
    	FunctionsUtils::includeJsAndCss($head, $tbl_css, $tbl_js);
    	
        $rpi = $_SESSION[APP]['user']['id'];
		$form_detail = FunctionsUtils::getFormulaireFromCode($_GET['sCode']);
		
		$xml_content = $form_detail['FORM_XML_CONTENT'];
		$xml_file_name = $form_detail['FORM_INCE'] . '.xml';
		$type_formulaire = t('BOFORMS_REFERENTIAL_FORM_TYPE_' . $form_detail['OPPORTUNITE_KEY']); 
		$device_key = t('BOFORMS_REFERENTIAL_DEVICE_' . $form_detail['DEVICE_KEY']);
		$form_context = t('BOFORMS_REFERENTIAL_FORM_CONTEXT_' . $form_detail['CONTEXT_KEY']);
		$form_customer_type = t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_' . $form_detail['TARGET_KEY']);
		
		$this->oXMLOriginal = new XMLHandle($xml_content, 'xml');
		$this->oXMLOriginal->Parser_read();

		// list all the required fields
		$tblRequired = array();
		$tblFieldsAll = array();
		
		$formsite_id = (int)$this->oXMLOriginal->instance['site_id'];
		$culture_id = $this->oXMLOriginal->instance['culture_id'];
    	
		$oConnection = Pelican_Db::getInstance();
		$aBind[':CULTURE_ID'] = $culture_id;
		$culture_str = strtoupper($oConnection->queryItem('select culture_key from #pref#_boforms_culture where culture_id = :CULTURE_ID', $aBind));
		
   		$tbl_result_compos = array();
   		if (count($this->oXMLOriginal->aCompoAv) > 0) {
			foreach($this->oXMLOriginal->aCompoAv as $key => $value) {
				$tbl_result_compos[] = 'objTmp = new Object();objTmp.label = "' . strip_tags($value['template']) . '";tbl_result_compos.push(objTmp);';
			}
   		}
   		   				
		foreach ($this->oXMLOriginal->aField as $key => $values) {
			if (isset($values['field']['hidden']) || isset($values['html']) || isset($values['connector']) || isset($values['field']['button'])) {
				// non traite
			} else {
				$label = strip_tags(str_replace("\n",'' ,$values['field']['label']['value']));
				
				$code = $values['field']['attributes']['code'];
				if ($label == '') {
					$label = $code;
				}
				
				$id = $values['field']['attributes']['id'];
							
				
				if (isset($values['field']['attributes']['required_central']) || $this->oXMLOriginal->aListened[$code] || $this->oXMLOriginal->aListening[$code]) {
					
					$tblRequired[] = 'objTmp = new Object();objTmp.label = "' . $label . '";objTmp.id = "' . $id . 
									 '";tbl_required_fields.push(objTmp);';
				}

				$tblFieldsAll[] = 'objTmp = new Object();objTmp.label = "' . $label . '";objTmp.id = "' . $id . 
									 '";objTmp.code= "' . $code . '";tbl_all_fields.push(objTmp);';
			}
		}
		
		$str_tbl_required_fields =  implode("\n", $tblRequired) ;
		$str_tbl_all_fields = implode("\n", $tblFieldsAll);
		$str_tbl_resultCompos = implode("\n", $tbl_result_compos);
		
		$tbl_lang = array('BOFORMS_LABEL_LEGAL_MENTION_CPP_ANSWER', 'BOFORMS_SUPPORT_CHOOSE_REQUEST_TYPE','BOFORMS_SUPPORT_CHOOSE_MODIFICATION_TYPE',
		 'BOFORMS_SUPPORT_CHOOSE_PRIORITY','BOFORMS_REQUEST_TYPE_CENTRAL_VALIDATION','BOFORMS_REQUEST_TYPE_FORM_EVOLUTION',
		'BOFORMS_REQUEST_TYPE_NOTIFY_ANOMALY','BOFORMS_NOTIFICATION_NEW_FIELDS','BOFORMS_NOTIFICATION_DEL_MANDATORY_FIELD','BOFORMS_NOTIFICATION_MODIFY_IMPRINT',
		'BOFORMS_NOTIFICATION_UPD_BUSINESS_COMPONENT','BOFORMS_NOTIFICATION_UPD_USER_INTERFACE','BOFORMS_NOTIFICATION_MODIFY_OPT_IN',
		'BOFORMS_NOTIFICATION_OTHER_REQUEST','BOFORMS_REQUEST_BLOCKING','BOFORMS_REQUEST_MAJOR','BOFORMS_REQUEST_MINOR', 'BOFORMS_NOTIFICATION_MODIFY_STEP_ORDER',
		'BOFORMS_LABEL_SBS_COM_OFFER','BOFORMS_LABEL_SBS_USR_OFFER','BOFORMS_LABEL_REQUEST_INTEREST_FINANCING',
		'BOFORMS_LABEL_REQUEST_INTEREST_INSURANCE','BOFORMS_LABEL_REQUEST_INTEREST_SERVICE','BOFORMS_LABEL_UNS_NWS_CPP_MOTIF',
		'BOFORMS_LABEL_GET_MYCITROEN', 'BOFORMS_LABEL_REQUEST_CALLBACK',
		'BOFORMS_LABEL_SBS_COM_OFFER_2','BOFORMS_LABEL_SBS_USR_OFFER_2', 'BOFORMS_LABEL_SBS_USR_OFFER_2_LP',
		'BOFORMS_LABEL_SBS_USER_OFFER','BOFORMS_LABEL_MULTIFORMS_CHOICE','BOFORMS_LABEL_SBS_NWL_NEWS'
		);
	
		$head->setScript(FunctionsUtils::getJsTranslations($tbl_lang) . 
			"var sr_device = '" . $device_key  . "'; 
			var sr_from_site_id_landing = '" . Pelican::$config['BOFORMS_FORMSITE_ID']['LANDING_PAGE'] . "';
			var sr_form_site_id = '" . $formsite_id . "';
			var sr_form_type  = '" . str_replace("'", "\'", $type_formulaire)  . "';  
			var sr_site = '" . $_SESSION[APP]['SITE_ID']  . "';
			var sr_webmaster_name = '" . $_SESSION[APP]['user']['name'] . "';
			var sr_rpi = '" . $rpi . "';
			var sr_xml_saved_version  = '" . $xml_file_name  . "';	
			var sr_type_environnement = '" . $_ENV["TYPE_ENVIRONNEMENT"] . "'; 
			var sr_environnement = '" . Pelican::$config['BOFORMS_JIRA']['ENV2'][$_ENV["TYPE_ENVIRONNEMENT"]] . "';
        	var sr_country_code = '" . FunctionsUtils::getCountryLabelFromCode() . "';
        	var sr_scode = '" . $_GET['sCode'] . "';
        	var sr_culture_str = '" . $culture_str . "';
        	var sr_form_context = '" . $form_context . "';
        	var sr_form_customer_type = '" . $form_customer_type . "';
			var tbl_all_fields = [];" . str_replace('""',"''",$str_tbl_all_fields) . ";
		    var tbl_required_fields = [];" . $str_tbl_required_fields . ";
		    var tbl_result_compos = [];" . $str_tbl_resultCompos . ";");

		$this->assign('header', $head->getHeader(false), false);
	    $this->fetch();
	}
	
	private function getPriorityStringFromId($priority_id) {
		if ($priority_id == Pelican::$config['BOFORMS_JIRA']['PRIORITY']['BLOQUANTE']) {
			return t('BOFORMS_REQUEST_BLOCKING');
		} else if ($priority_id == Pelican::$config['BOFORMS_JIRA']['PRIORITY']['MAJEURE']) {
			return t('BOFORMS_REQUEST_MAJOR');
		} else if ($priority_id == Pelican::$config['BOFORMS_JIRA']['PRIORITY']['MINEURE']) {
			return t('BOFORMS_REQUEST_MINOR');
		}
		return "ERROR: Unknown priority";
	}
	
	public function postSupportRequestAction() {
		$json = json_decode($this->getParam("datas"));
				
		// get form_site
		if ($json->type_demande != Pelican::$config['BOFORMS_REQUEST_TYPE']['NEW_FORM']) {
			$oConnection = Pelican_Db::getInstance ();
			$aBind[':FORM_INCE'] = $oConnection->strToBind($json->scode);
			$form_site = $oConnection->queryItem('SELECT FORMSITE_ID FROM `psa_boforms_formulaire` where `FORM_INCE` = :FORM_INCE', $aBind);
			if (FunctionsUtils::isLandingPageSite($form_site)) {
				$form_site_label = 'LP';			
			} else {
				$form_site_label = Pelican::$config['BOFORMS_CONSUMER'];
			}
		}

		// file uploaded ?
		$file = '';
		$file_path = '';
   		if (isset($_FILES['fileToUpload']['name']) && $_FILES['fileToUpload']['name'] != '') {
   			$file = $_FILES["fileToUpload"]["name"];
   			$file_path  = Pelican::$config["PLUGIN_ROOT"] . '/boforms/public/support/' . $file;
   			rename($_FILES["fileToUpload"]["tmp_name"], $file_path);
   		}
		
		// default assignation
   		$this->assign('priority', $this->getPriorityStringFromId($json->priorite));
   		$this->assign('pays', $json->countrycode, false);
		$this->assign('rpi', $json->rpi, false);
		$this->assign('environnement', $json->environnement, false);
		$this->assign('site', FunctionsUtils::getSiteLabelFromId($json->site), false);
		$this->assign('form_type', $json->form_type, false);
		
		if ($json->type_demande != Pelican::$config['BOFORMS_REQUEST_TYPE']['NEW_FORM']) {
			$this->assign('device', $json->device, false);
		}
		
		$this->assign('countrycode', $json->countrycode, false  );
		$this->assign('webmaster_name', $json->webmaster_name, false);
		
		if ($json->type_demande != Pelican::$config['BOFORMS_REQUEST_TYPE']['NEW_FORM']) {
			$this->assign('form_context', $json->formcontext);
			$this->assign('form_customer_type', $json->formcustomertype);
		}
		
		if ($json->type_demande == Pelican::$config['BOFORMS_REQUEST_TYPE']['VALIDATION_CENTRAL']) {
    		$this->sendCentralValidationSupportRequest($json, $file, $file_path, $form_site_label);
    	} else if ($json->type_demande == Pelican::$config['BOFORMS_REQUEST_TYPE']['EVOLUTION_FORMULAIRE']) {
    		$this->sendEvolutionSupportRequest($json, $file, $file_path, $form_site_label);
    	} else if ($json->type_demande == Pelican::$config['BOFORMS_REQUEST_TYPE']['NOTIFICATION_ANOMALIE']) {
    		$this->sendNotificationSupportRequest($json, $file, $file_path, $form_site_label);	
    	} else if ($json->type_demande == Pelican::$config['BOFORMS_REQUEST_TYPE']['NEW_FORM']) {
    		$this->sendCreateFormSupportRequest($json);
    	}
    
    	// deletes attached file if specified
		if (file_exists($file_path)) {
			unlink($file_path);	
		}
	}
	
   	private function assignArrayFromJson($key, $tbl, $add_description) {
   		$tbl_tmp = array();
   		for ($i = 0; $i < count($tbl); $i++) {
   			$obj = $tbl[$i];	
  			if ($obj->ischecked == '1') {
  				if ($add_description) {
  					if ($key == 'tbl_result_compos') {
   						$tbl_tmp[] = array('label' => $obj->label, 'description' => $obj->description);
  					} else {
  						$tbl_tmp[] = array('label' => $obj->label, 'id' => $obj->identifier,  'description' => $obj->description);
  					}
  				} else {
   					$tbl_tmp[] = array('label' => $obj->label, 'id' => $obj->identifier);
  				} 
   			}
  		}
  		$this->assign($key, $tbl_tmp);
   	}
   	
   	// envoyer une demande d'évolution
   	private function sendEvolutionSupportRequest($json, $file, $file_path, $form_site_label) {
   		// creates the xml file to send via email
   		$scode = $json->scode;
   		
   		$this->assign('request_title', $json->request_title);
		$this->assign('form_site_label', $form_site_label);   		
		$this->assign('form_context', $json->formcontext);
		$this->assign('form_customer_type', $json->formcustomertype);
		$this->assign('culture_str', $json->culture_str);
		
   		// loop over the notification types
   		foreach ($json->tblTypeNotification as $key => $object) {
				if ($object->is_displayed == '1') {
					$type_notification = $object->id;
					
					if ($type_notification == Pelican::$config['BOFORMS']['CONTACT_SUPPORT']['NOTIFICATION_TYPE']['NEW_FIELDS']) {
			   			$this->assign('description_0',  $json->request_more_description_0);	
			   		} else if ($type_notification ==  Pelican::$config['BOFORMS']['CONTACT_SUPPORT']['NOTIFICATION_TYPE']['REMOVE_MANDATORY_FIELDS']) {
			   			$this->assignArrayFromJson('tbl_required', $json->tbl_required_fields, true);
			  		} else if ($type_notification ==  Pelican::$config['BOFORMS']['CONTACT_SUPPORT']['NOTIFICATION_TYPE']['MODIFY_IMPRINT_DISPLAY']) {
			  			$this->assign('description_2',  $json->request_more_description_2);	
			   		} else if ($type_notification ==  Pelican::$config['BOFORMS']['CONTACT_SUPPORT']['NOTIFICATION_TYPE']['MODIFY_COMPONENT']) {
			   			$this->assignArrayFromJson('tbl_result_compos', $json->tbl_result_compos, true); 
			   			$this->assign('description_3', stripslashes( $json->request_more_description_3));	
			   		} else if ($type_notification ==  Pelican::$config['BOFORMS']['CONTACT_SUPPORT']['NOTIFICATION_TYPE']['MODIFY_GRAPHICAL_INTERFACE']) {
			   			$this->assign('description_4',  $json->request_more_description_4);	
			   		} else if ($type_notification ==  Pelican::$config['BOFORMS']['CONTACT_SUPPORT']['NOTIFICATION_TYPE']['MODIFY_OPT_IN']) {
			   			$this->assign('description_5',  $json->request_more_description_5);	
			   		} else if ($type_notification ==  Pelican::$config['BOFORMS']['CONTACT_SUPPORT']['NOTIFICATION_TYPE']['OTHER_REQUEST']) {
			   			$this->assign('description_7',  $json->request_more_description_7);	
			   		} else if ($type_notification ==  Pelican::$config['BOFORMS']['CONTACT_SUPPORT']['NOTIFICATION_TYPE']['MODIFY_STEP_ORDER']) {	
			   			$this->assign('description_6',  $json->request_more_description_6);	
			   		}   
				}
   		}
   		
	    // creates the jira
	    $description = $this->getView()->fetch(Pelican::$config["PLUGIN_ROOT"] . '/boforms/backend/views/scripts/BoForms/Administration/SupportRequest/demandeEvolutionJira.tpl');	    
	    $datas = JiraUtil::createJiraDemandeEvolution($json->request_title, $description, $file, $file_path, $json->priorite, $json->countrycode, $json->rpi);
	    
	    // display result
	   	$this->assign('key_jira_created', $datas['key_jira_created']);
	    $this->assign('url_jira_created', $datas['url_jira_created']);
	   	$this->setTemplate(Pelican::$config["PLUGIN_ROOT"] . '/boforms/backend/views/scripts/BoForms/Administration/SupportRequest/demandeEvolution.tpl');
	    $this->fetch();
   	}
   	
   	/////////////////////////////////////////////////////////////
   	private function sendCreateFormSupportRequest($json) {

		$oConnection = Pelican_Db::getInstance ();
   		// smarty assignations
   		$this->assign('priorite', $json->priorite );
		$this->assign('form_type', $json->form_type );
		$this->assign('form_description', $json->form_description );
		$this->assign('formaddfields', $json->formaddfields );
		$this->assign('formexample', $json-> formexample);
		$this->assign('request_title', $json->request_title);

		$aBind[':GROUPE_ID'] = $json->groupe_id;
		$form_site = $oConnection->queryItem('select fs.FORMSITE_KEY
    											from #pref#_boforms_formulaire_site fs
    											inner join #pref#_boforms_groupe_formulaire gf on fs.FORMSITE_ID = gf.FORMSITE_ID
    											where gf.GROUPE_ID = :GROUPE_ID', $aBind);
		if (FunctionsUtils::isLandingPageSite($form_site)) {
			$form_site_label = 'LP';
		} else {
			$form_site_label = Pelican::$config['BOFORMS_CONSUMER'];
		}

   		$this->assign('formsitelabel', $form_site_label);
   		$this->assign('culture_str', $json->culture_str);


   		if ($json-> opportunity == '-1') {
   			$this->assign('opportunity', t('BOFORMS_REFERENTIAL_FORM_TYPE_OTHER'));
   		} else {
	   		$aBind[':OPPORT'] = $json-> opportunity;
	   		$opportunity_key = $oConnection->queryItem('SELECT OPPORTUNITE_KEY FROM `#pref#_boforms_opportunite` where OPPORTUNITE_ID = :OPPORT', $aBind);
	   		$this->assign('opportunity', t('BOFORMS_REFERENTIAL_FORM_TYPE_' . $opportunity_key ));
   		}	
   	
		$this->assign('workflow_standard', $json->workflow_standard);
		$this->assign('workflow_context_pos', $json->workflow_context_pos);
		$this->assign('workflow_context_vehicle', $json-> workflow_context_vehicle);
		
		// gestion de la cible
		$cible = '';
		if ($json->form_target_selected_part == '1') {
			$cible = t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PARTICULAR');
		}
		if ($json->form_target_selected_pro == '1') {
			if ($cible != '') {
				$cible .= ', ';
			}
			$cible .= t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PROFESSIONAL');
		}
		$this->assign('form_target_selected', $cible);
				
		// gestion du device
		$device = '';
		if ($json->device_web == '1') {
			$device = t('BOFORMS_REFERENTIAL_DEVICE_WEB');
		}
		if ($json->device_mobile == '1') {
			if ($device != '') {
				$device .= ', ';
			}
			$device .= t('BOFORMS_REFERENTIAL_DEVICE_MOBILE');
		}
		$this->assign('device', $device);
			
		
		$this->assignArrayFromJson('tbl_all_fields', $json->tblListeFields, false);
   		
   		
   		// creates the jira
	    $description = $this->getView()->fetch(Pelican::$config["PLUGIN_ROOT"] . '/boforms/backend/views/scripts/BoForms/Administration/SupportRequest/newformJira.tpl');
	    $datas = JiraUtil::createJiraCreateForm($json->request_title, $description, $json->priorite, $json->countrycode, $json->rpi);
	    
	    // display result
	    $this->assign('key_jira_created', $datas['key_jira_created']);
	    $this->assign('url_jira_created', $datas['url_jira_created']);
	    $this->setTemplate(Pelican::$config["PLUGIN_ROOT"] . '/boforms/backend/views/scripts/BoForms/Administration/SupportRequest/newform.tpl');
	    $this->fetch();
   	}
   	
   	
   	private function getPrioriteLabelFromId($priorite_id) {
   		$priorite = t('BOFORMS_REQUEST_MINOR');
		if ($priorite_id ==  Pelican::$config['BOFORMS_JIRA']['PRIORITY']['BLOQUANTE']) {
   			$priorite = t('BOFORMS_REQUEST_BLOCKING');
   		} else if ($priorite_id ==  Pelican::$config['BOFORMS_JIRA']['PRIORITY']['MAJEURE']) {
			$priorite = t('BOFORMS_REQUEST_MAJOR');
   		}
   		return $priorite; 
   	}
   	
   	// envoyer une demande de validation au Central
   	private function sendCentralValidationSupportRequest($json, $file, $file_path, $form_site_label) {
   		// creates the xml file to send via email
   		$scode = $json->scode;
   		
   		$form_detail = FunctionsUtils::getFormulaireFromCode($scode);
   		$file_path2 = $this->writeXmlFile($form_detail['FORM_INCE'] , FunctionsUtils::cleanXML($form_detail['FORM_XML_CONTENT']));
		
		$this->assign('request_description', $json->request_description, false);
		$this->assign('form_site_label', $form_site_label);
		$this->assign('form_context', $json->formcontext);
		$this->assign('form_customer_type', $json->formcustomertype);
		$this->assign('culture_str', $json->culture_str);
		
	    // sends mail
	    $strBody = $this->getView()->fetch(Pelican::$config["PLUGIN_ROOT"] . '/boforms/backend/views/scripts/BoForms/Administration/SupportRequest/centralValidationMail.tpl');
		$strSubject = 'Validation de formulaire boforms';
   		
   		$content = file_get_contents($file_path2);
		
   		$attachment = new Zend_Mime_Part($content);
		$attachment->type = 'application/xml';
		$attachment->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
		$attachment->encoding = Zend_Mime::ENCODING_BASE64;
		$attachment->filename = $form_detail['FORM_INCE'] . '.xml'; // name of file
		
		$oMail = new Zend_Mail('UTF-8');
   		$oMail->addAttachment($attachment);
   		       
		
		if (file_exists($file_path)) {
			$content2 = file_get_contents($file_path);
			
			$mime_type = $this->get_mime_type($file);
			
	   		$attachment2 = new Zend_Mime_Part($content2);
	   		$attachment2->type = $mime_type;
			$attachment2->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
			$attachment2->encoding = Zend_Mime::ENCODING_BASE64;
			$attachment2->filename = $file; // name of file
			$oMail->addAttachment( $attachment2 ) ;
		}
		
		          
		$oMail->setBodyHtml($strBody)->setFrom($_SESSION[APP]['user']['email']);
			  
   		
		$tbl_mail = Pelican::$config['BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL'];
		for ($i = 0; $i < count($tbl_mail); $i++) {
			$oMail->addTo($tbl_mail[$i]);
		}  
			 
		$oMail->setSubject($strSubject)
			  ->send(); 
			  
		unlink($file_path2);	
		if (file_exists($file_path)) {
			unlink($file_path);
		}  
			  
	    // display result
	    $this->setTemplate(Pelican::$config["PLUGIN_ROOT"] . '/boforms/backend/views/scripts/BoForms/Administration/SupportRequest/centralValidation.tpl');
	    $this->fetch();
   	}
   	
	private function get_mime_type($file)
	{
        // our list of mime types
        $mime_types = array(
                "pdf"=>"application/pdf"
                ,"exe"=>"application/octet-stream"
                ,"zip"=>"application/zip"
                ,"docx"=>"application/msword"
                ,"doc"=>"application/msword"
                ,"xls"=>"application/vnd.ms-excel"
                ,"ppt"=>"application/vnd.ms-powerpoint"
                ,"gif"=>"image/gif"
                ,"png"=>"image/png"
                ,"jpeg"=>"image/jpg"
                ,"jpg"=>"image/jpg"
                ,"mp3"=>"audio/mpeg"
                ,"wav"=>"audio/x-wav"
                ,"mpeg"=>"video/mpeg"
                ,"mpg"=>"video/mpeg"
                ,"mpe"=>"video/mpeg"
                ,"mov"=>"video/quicktime"
                ,"avi"=>"video/x-msvideo"
                ,"3gp"=>"video/3gpp"
                ,"css"=>"text/css"
                ,"jsc"=>"application/javascript"
                ,"js"=>"application/javascript"
                ,"php"=>"text/html"
                ,"htm"=>"text/html"
                ,"html"=>"text/html"
        );

        $extension = strtolower(end(explode('.',$file)));

        return $mime_types[$extension];
	}
   	
   	// envoyer une notification d'anomalie
   	private function sendNotificationSupportRequest($json, $file, $file_path, $form_site_label) {
   		// assignation smarty
   		$this->assign('request_title', $json->request_title);
   		$this->assignArrayFromJson('tbl_all_fields', $json->tbl_all_fields, true);
   		$this->assign('anomalie_description', $json->anomalie_description);
		$this->assign('form_site_label', $form_site_label);
		$this->assign('form_context', $json->formcontext);   		
		$this->assign('form_customer_type', $json->formcustomertype);
		$this->assign('culture_str', $json->culture_str);
		
	    // creates the jira
	    $description = $this->getView()->fetch(Pelican::$config["PLUGIN_ROOT"] . '/boforms/backend/views/scripts/BoForms/Administration/SupportRequest/anomalieJira.tpl');
	    $datas = JiraUtil::createJiraAnomaly($json->request_title, $description, $file, $file_path, $json->priorite, $json->countrycode, $json->rpi);
	    
	    // display result
	    $this->assign('key_jira_created', $datas['key_jira_created']);
	    $this->assign('url_jira_created', $datas['url_jira_created']);
	    $this->setTemplate(Pelican::$config["PLUGIN_ROOT"] . '/boforms/backend/views/scripts/BoForms/Administration/SupportRequest/anomalie.tpl');
	    $this->fetch();	
   	}	

    // returns all the form types
   	private function createOpportunitiesJs($isLP) { 
   		$oConnection = Pelican_Db::getInstance ();
   		
   		if ($isLP) {
   			$sqlplus = " and OPPORTUNITE_KEY in ('BOOK_A_TEST_DRIVE','REQUEST_A_BROCHURE','REQUEST_AN_OFFER',
								'LANDING_PAGE','LANDING_PAGE_1','LANDING_PAGE_2',
								'SUBSCRIBE_NEWSLETTER','UNSUBSCRIBE_NEWSLETTER')";
   		} else {
   			$sqlplus = " and OPPORTUNITE_KEY in ('BOOK_A_TEST_DRIVE','REQUEST_A_BROCHURE','REQUEST_AN_OFFER','SUBSCRIBE_NEWSLETTER','UNSUBSCRIBE_NEWSLETTER',
'REQUEST_A_CONTACT_BUSINESS','REQUEST_AN_INFORMATIONS','CLAIMS',
'UNSUBSCRIBE_NEWSLETTER_AMEX','UNSUBSCRIBE_NEWSLETTER_CREDIPAR', 'UNSUBSCRIBE_NEWSLETTER_B2B','UNSUBSCRIBE_NEWSLETTER_CNIL', 'UNSUBSCRIBE_NEWSLETTER_EMAILING')";
   		}
   		$tab = $oConnection->queryTab("SELECT OPPORTUNITE_ID, OPPORTUNITE_KEY FROM #pref#_boforms_opportunite 
   									   where OPPORTUNITE_KEY not like 'CLAIMS_%' $sqlplus");
   		   		
   		$result = 'var listOpportunities = [];'; 		

   		// default value is empty, name is defined in the template
   		$result .= "tmpOpp = new Object();tmpOpp.id = '';tmpOpp.name = '';listOpportunities.push(tmpOpp);";
   		for($i = 0; $i < count($tab); $i++) {
   			$result .= "tmpOpp = new Object();tmpOpp.id = '" . $tab[$i]['OPPORTUNITE_ID'] . 
   					   "';tmpOpp.name = '" . str_replace("'","\'", t('BOFORMS_REFERENTIAL_FORM_TYPE_' . $tab[$i]['OPPORTUNITE_KEY'])) . "';listOpportunities.push(tmpOpp);";
   		}
   		
   		// smarty assignation 
   		for($i = 0; $i < count($tab); $i++) {
   			$result2[$tab[$i]['OPPORTUNITE_ID']] = t('BOFORMS_REFERENTIAL_FORM_TYPE_' . $tab[$i]['OPPORTUNITE_KEY']);
   		}
   		$this->assign('theOpportunities', $result2);
   		
   		return $result;
   	}
   	
	public function emptyTaskAction() {
		// does nothing but useful to empty the frame content for the supportDialog called in editor.tpl
		echo " ";
		exit(0);
	}


	private function getFormComponentsForDevice($device, $code_pays, $cible, $formsiteid, $default_culture, $formulaire) {
    		$sCode = Pelican::$config['BOFORMS_BRAND_ID'] . $code_pays . $cible . '9' . $formsiteid . '00' . 
					"00" . $device . '0' . str_pad($formulaire, 2, "0", STR_PAD_LEFT);

    		// search for draft version
    		$form_detail = FunctionsUtils::getFormulaireFromCode($sCode);
    		
    		// if not found, search for current version
    		if (! isset($form_detail['FORM_XML_CONTENT'])) {
				$form_detail = FunctionsUtils::getFormulaireFromCode($sCode, 'CURRENT');
			}
    		
    		if (isset($form_detail['FORM_XML_CONTENT'])) {
    			$this->oXMLOriginal = new XMLHandle($form_detail['FORM_XML_CONTENT'], 'xml');
				$this->oXMLOriginal->Parser_read();
				
				$tbl_result = array();
				if (isset($this->oXMLOriginal->aCompoAv)) {
					foreach($this->oXMLOriginal->aCompoAv as $key => $value) {
						$tbl_result[] = array('label' => $value['template'],  'required_central' => '0');
					}
				}	

				if (isset($this->oXMLOriginal->aField)) {
					foreach ($this->oXMLOriginal->aField as $key => $values) {
						if (isset($values['field']['hidden']) || isset($values['connector']) || isset($values['field']['button']) || isset($values['html'])) {
						} else {
							$label = str_replace("\n",' ' ,$values['field']['label']['value']);
							$label = str_replace("<br/>",' ' ,$values['field']['label']['value']);
							$label = str_replace("<BR/>",' ' ,$values['field']['label']['value']);
							$label = str_replace("<BR />",' ' ,$values['field']['label']['value']);
							$label = strip_tags($label);
							if ($label == '') {
								// cas particulier pour le landing page jira 393
								if ($values['field']['attributes']['code'] == 'SBS_USR_OFFER_2' && FunctionsUtils::isLandingPageSite($formsiteid)) {
									$label = t('BOFORMS_LABEL_' . $values['field']['attributes']['code'] . '_LP');
								} else {
									$label = $values['field']['attributes']['code'];
								}
							}
							if (isset($values['field']['attributes']['required_central']) && $values['field']['attributes']['required_central'] == 'true') {
								$required_central = '1';	
							} else {
								$required_central = '0';
							}
							$tbl_result[] = array('label' => $label, 'required_central' => $required_central);
						}	
					}
				}
				
				return $tbl_result;
			} else {
				return array();
			}
	}
	
   	
   	private function writeXmlFile($form_ince, $form_xml_content) {
  		$file_path = Pelican::$config["PLUGIN_ROOT"] . '/boforms/public/support/' . $form_ince . '.xml';
		$fd = fopen($file_path, 'w');
		fwrite($fd, $form_xml_content);
		fclose($fd);
		return $file_path;
   	}   
	
}
