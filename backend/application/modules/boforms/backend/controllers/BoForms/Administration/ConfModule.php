<?php

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/services.ini.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local.ini.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/BoForms.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FunctionsUtils.php');

include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/JiraUtil.php');

/*** WebServices***/
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/services.ini.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Configuration.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetParametersRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetParametersResponse.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/UpdateParametersRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/UpdateParametersResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetReferentialRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetReferentialResponse.php');

//ajout du fichier de conf administrable en BO
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.admin.ini.php');

// Configuration du module (jira, urls etc).

class BoForms_Administration_ConfModule_Controller extends Pelican_Controller_Back
{
	protected $administration = true;
	
    protected $get_instance;
   
    protected $bDraft = false;
    protected $bDraftAuto = false;

    protected $form_name = "boforms_list_conf";

    protected $parameters = array(); // contains a list of "interfaceSi" values
	protected $parameterInfo = array(); // contains for each "interfaceSi" value, all the parameterInfo values  
    protected $mappingSiName = array(); // contains mapping interfaceSi / interfaceSiName     
	
    // list of string fields for each configuration in boforms_conf_list
    
    protected $conf_key_str = array( 1 => array('JIRA_USERNAME', 'BOFORMS_JIRA0PROJECT_KEY', 'BOFORMS_JIRA0ISSUE_URL','BOFORMS_JIRA0ASSIGNEE_NAME','BOFORMS_JIRA0OTHER_ASSIGNEE'),
    						  2 => array('AC_SERVICE_BOFORMS0PARAMETERS0location','AC_SERVICE_BOFORMS0PARAMETERS0wsdl',
							 			'AP_SERVICE_BOFORMS0PARAMETERS0location','AP_SERVICE_BOFORMS0PARAMETERS0wsdl',
							 			'DS_SERVICE_BOFORMS0PARAMETERS0location','DS_SERVICE_BOFORMS0PARAMETERS0wsdl'),						  
    						  3 => array('CITROEN_SERVICE_I18N0PARAMETERS0location','CITROEN_SERVICE_I18N0PARAMETERS0wsdl'),
    						  4 => array('CITROEN_SERVICE_DEALERSERVICE0PARAMETERS0location','CITROEN_SERVICE_DEALERSERVICE0PARAMETERS0wsdl'),
              				  5 => array('BOFORMS_URL_CLEARCACHE', 'BOFORMS_URL_CLEARCACHE_KEY', 'BOFORMS_URL_RENDERER', 'BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL', 'BOFORMS_BRAND_ID', 'BOFORMS_CONSUMER', 'BOFORMS_FORM_XSD', 'BOFORMS_LOG_PATH'), 
							  6 => array('BOFORMS_USER_SUPER_ADMIN'),
							  7 => array('AC_PROXY0URL', 'AC_PROXY0LOGIN', 'AC_PROXY0CURLPROXY_HTTP', 
							  			 'DS_PROXY0URL', 'DS_PROXY0LOGIN', 'DS_PROXY0CURLPROXY_HTTP', 
							  			 'AP_PROXY0URL', 'AP_PROXY0LOGIN', 'AP_PROXY0CURLPROXY_HTTP'),
							  10 => array('URL_BOLP')
	);	    
	
	protected $conf_key_password = array( 1 => array('JIRA_PASSWORD'),
										  7 => array('AC_PROXY0PWD','DS_PROXY0PWD','AP_PROXY0PWD')
	);	 
	
	protected $conf_gdo_cusco_only_admin = array('CONTENT_TYPE','MAIL_NO_REPLY','RECIPIENT_DEV','RECIPIENT_PREPROD','RECIPIENT_PROD','SENDER_DEV','SENDER_PREPROD','SENDER_PROD', 'POST_URL_PREPROD', 'POST_URL_DEV', 'POST_URL_PROD');
	
    
	protected function setListModel ()
    {
    	if ($_SESSION[APP]['SITE_ID'] == 1) {
    		$this->listModel = "SELECT CONF_ID, CONF_KEY FROM #pref#_boforms_list_conf order by CONF_ORDER";
    	} else {
    		$this->listModel = "SELECT CONF_ID, CONF_KEY FROM #pref#_boforms_list_conf where CONF_ID in (8, 9) order by CONF_ORDER";
    	} 
    }

	public function getReferential($referential_type)
	{
		// call ws
		$serviceParams = array(
			'referentialType' => $referential_type
		);
		$service = \Itkg\Service\Factory::getService(Pelican::$config['BOFORMS_BRAND_ID'] . '_SERVICE_BOFORMS', array());
		$response = $service->call('getReferential', $serviceParams);

		return $response;

	}


   /**
     * récupère les paramètres
     * @param string $culture
     * @param string $conf_key
     */
    public function getParametersWS($profileType, $culture, $informationSystem)
    {	
    	try {
    		$serviceParams = array(
    				'profileType' => $profileType,
    				'culture' => $culture,
    				'informationSystem' => $informationSystem
    		);

    		$service = \Itkg\Service\Factory::getService(Pelican::$config['BOFORMS_BRAND_ID'] . '_SERVICE_BOFORMS', array());

    		$response = $service->call('getParameters', $serviceParams);


    		if($response->statusResponse == 'OK')
    		{

    			for ($i = 0; $i < count($response->parameters->parameter); $i++) {
    				$interfaceSi = $response->parameters->parameter[$i]->interfaceSi;
    				$interfaceSiName = $response->parameters->parameter[$i]->interfaceSiName;
    				
    				$this->parameters[] = $interfaceSi;
    				$this->mappingSiName[$interfaceSi] = $interfaceSiName;
    				$params = array();
    				if (count($response->parameters->parameter[$i]->parametersInfo->parameterInfo) > 0) {
    					for ($z = 0; $z < count($response->parameters->parameter[$i]->parametersInfo->parameterInfo); $z++) {
    						$name = $response->parameters->parameter[$i]->parametersInfo->parameterInfo[$z]->parameterName;
							$value = $response->parameters->parameter[$i]->parametersInfo->parameterInfo[$z]->parameterValue;
							$params[] = array('name' => $name, 'value' => $value);

    					}
    					if (count($params) > 0) {
    						$this->parameterInfo[$interfaceSi] = $params;
    					}
    				}
    				
    			}
    		} else {
    			echo "error service : ". $response->statusResponse . '<br/>';
    			//die('No response ');
    		} 
    	} catch(\Exception $e) {
    		echo $e->getMessage();
    	}
    }
    
	/**
     * met à jour les paramètres
     * @param string 
     * @param string 
     */
    public function updateParametersWS($tblInterfaces ,$parametersInfo)
    {	
    	try {
    		$serviceParams = array(
    				'parameters' => $tblInterfaces,
    				'parameterInfo' =>  $parametersInfo,
    				'mappingSiName' => $this->mappingSiName
    		);

    		$service = \Itkg\Service\Factory::getService(Pelican::$config['BOFORMS_BRAND_ID'] . '_SERVICE_BOFORMS', array());
			$response = $service->call('updateParameters', $serviceParams);
    	} catch(Exception $e) {
    		echo $e->getMessage();
    	}
    }
    
    public function listAction () {
    	parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setCSS(array(
            "tblalt1",
            "tblalt2"
        ));
        
        $table->setValues($this->getListModel(), "CONF_ID");
        
        if(is_array($table->aTableValues) && !empty($table->aTableValues))
        {
        	foreach ($table->aTableValues as $k=>$row)
        	{
        		$table->aTableValues[$k]['CONF_KEY'] = t('BOFORMS_REFERENTIAL_CONF_TYPE_'.$row['CONF_KEY']);
        	}
        }
                       
        $table->addColumn(t('BOFORMS_TYPE_CONF'), "CONF_KEY", "20", "left", "", "tblheader");
       
        $table->addInput(t('BOFORMS_EDIT'), "button", array("id" => "CONF_ID", "" => "", "CONF_KEY" => "CONF_KEY"), "center");
        
         $this->aButton["add"] = "";
	     Backoffice_Button_Helper::init($this->aButton);

	    // link to reset the configuration (only for admin users)
	    $admin_plus = ''; 
	    if ($_SESSION[APP]['SITE_ID'] == 1) {
		    if (in_array(strtoupper($_SESSION[APP]['backoffice']['USER_LOGIN']), Pelican::$config['BOFORMS_USER_SUPER_ADMIN']) || in_array($_SESSION[APP]['backoffice']['USER_LOGIN'], Pelican::$config['BOFORMS_USER_SUPER_ADMIN'])) {
		   		 $admin_plus = '<br/><a href="#" style="padding-left:5px;" id="init_conf_module">' . t('BOFORMS_CONF_MODULE_LINK_REINIT'). '</a><br/><div id="results_ws"></div><script type="text/javascript">	    
				    $( document ).ready(function() {
					    $("#init_conf_module").on("click", function() {
						    if (confirm("' . t('BOFORMS_CONF_MODULE_CONFIRM_REINIT') . '")) {
						    	$.ajax({
								   type: "GET",
											url: "/_/module/boforms/BoForms_Administration_ConfModule/resetConfModule",
											success: function( data ) { 
					    						alert("' . t('BOFORMS_CONF_MODULE_CONF_LOADED') . '");	
												window.location.reload();
					    					},
					    					error: function (xhr, ajaxOptions, thrownError) {
					    						$("#results_ws").append("<div style=\"color: red;border-top: 1px solid black; margin-top: 5px;\">" + xhr.status + " - " + thrownError + " </div>");
										    },
											dataType: "html"
									});
						    }
					    });
				    });
				    </script>';
		   	} 
	    }
    	 
	    $this->setResponse($table->getTable() . $admin_plus);
    }     
    
    public function resetConfModuleAction() {
		$oConnection = Pelican_Db::getInstance ();
		$oConnection->query("delete from #pref#_boforms_list_conf");
    	$oConnection->query("delete from #pref#_boforms_conf");
		
    	$oConnection->query("INSERT INTO #pref#_boforms_list_conf (CONF_ID, CONF_KEY, CONF_ORDER) VALUES
			(1, 'JIRA', 1),
			(2, 'WEBSERVICE_BOFORMS', 2),
			(3, 'WEBSERVICE_I18N', 3),
			(4, 'WEBSERVICE_DEALERSERVICE', 4),
			(5, 'CONFIGURATION_GENERALE', 5),
			(6, 'ADMINISTRATION_SUPER_ADMIN', 6),
			(7, 'PROXY', 7),
			(8, 'CUSCO', 8),
			(10, 'ADMINISTRATION_URL_BOLP', 10)");
 
    	
    	$oConnection->query("INSERT INTO `#pref#_boforms_conf` (`CONF_VALUE_ID`, `CONF_VALUE_KEY`, `CONF_VALUE`, `CONF_ID`) VALUES
					(1, 'JIRA_USERNAME', 'E464305', 1),
					(2, 'JIRA_PASSWORD', '1tOapKyvbp4=', 1),
					(3, 'AC_SERVICE_BOFORMS0PARAMETERS0location', '". \Itkg::$config['AC_SERVICE_BOFORMS']['PARAMETERS']['location'] ."', 2),
					(4, 'AC_SERVICE_BOFORMS0PARAMETERS0wsdl', '". \Itkg::$config['AC_SERVICE_BOFORMS']['PARAMETERS']['wsdl'] ."', 2),
					(5, 'CITROEN_SERVICE_I18N0PARAMETERS0location', '". \Itkg::$config['CITROEN_SERVICE_I18N']['PARAMETERS']['location'] ."', 3),
					(6, 'CITROEN_SERVICE_I18N0PARAMETERS0wsdl', '". \Itkg::$config['CITROEN_SERVICE_I18N']['PARAMETERS']['wsdl'] ."', 3),
					(7, 'CITROEN_SERVICE_DEALERSERVICE0PARAMETERS0location', '". \Itkg::$config['CITROEN_SERVICE_DEALERSERVICE']['PARAMETERS']['location'] ."', 4),
					(8, 'CITROEN_SERVICE_DEALERSERVICE0PARAMETERS0wsdl', '". \Itkg::$config['CITROEN_SERVICE_DEALERSERVICE']['PARAMETERS']['wsdl'] ."', 4),
					(9, 'BOFORMS_JIRA0PROJECT_KEY', '".Pelican::$config['BOFORMS_JIRA']['PROJECT_KEY']."', 1),
					(10, 'BOFORMS_JIRA0ISSUE_URL', '".Pelican::$config['BOFORMS_JIRA']['ISSUE_URL']."', 1),
					(11, 'BOFORMS_JIRA0ASSIGNEE_NAME', '".Pelican::$config['BOFORMS_JIRA']['ASSIGNEE_NAME']."', 1),
					(12, 'BOFORMS_JIRA0OTHER_ASSIGNEE', '". implode(",", Pelican::$config['BOFORMS_JIRA']['OTHER_ASSIGNEE']) ."', 1),
					(13, 'BOFORMS_URL_CLEARCACHE', '".Pelican::$config['BOFORMS_URL_CLEARCACHE']."', 5),
					(14, 'BOFORMS_URL_CLEARCACHE_KEY', '".Pelican::$config['BOFORMS_URL_CLEARCACHE_KEY']."', 5),
					(16, 'BOFORMS_URL_RENDERER', '".Pelican::$config['BOFORMS_URL_RENDERER']."', 5),
					(17, 'BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL', '". implode(",", Pelican::$config['BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL']) ."', 5),
					(18, 'BOFORMS_USER_SUPER_ADMIN', '". implode(",", Pelican::$config['BOFORMS_USER_SUPER_ADMIN'] )."', 6),
					(19, 'BOFORMS_BRAND_ID', '".Pelican::$config['BOFORMS_BRAND_ID']."', 5),
					(20, 'BOFORMS_CONSUMER', '".Pelican::$config['BOFORMS_CONSUMER']."', 5),
					(21, 'AC_PROXY0URL', '" . Pelican::$config['AC_PROXY']['URL'] . "', 7),
					(22, 'AC_PROXY0LOGIN', '" . Pelican::$config['AC_PROXY']['LOGIN'] . "', 7),
					(23, 'AC_PROXY0PWD', '" . FunctionsUtils::f_crypt(Pelican::$config['BOFORMS_PRIVATE_KEY'],Pelican::$config['AC_PROXY']['PWD']) . "', 7),
					(24, 'AC_PROXY0CURLPROXY_HTTP', '" . Pelican::$config['AC_PROXY']['CURLPROXY_HTTP'] . "', 7),
					(25, 'BOFORMS_FORM_XSD', '" . Pelican::$config['BOFORMS_FORM_XSD'] . "', 5),
					(26, 'DS_SERVICE_BOFORMS0PARAMETERS0location', '". \Itkg::$config['DS_SERVICE_BOFORMS']['PARAMETERS']['location'] ."', 2),
					(27, 'DS_SERVICE_BOFORMS0PARAMETERS0wsdl', '". \Itkg::$config['DS_SERVICE_BOFORMS']['PARAMETERS']['wsdl'] ."', 2),
					(28, 'AP_SERVICE_BOFORMS0PARAMETERS0location', '". \Itkg::$config['AP_SERVICE_BOFORMS']['PARAMETERS']['location'] ."', 2),
					(29, 'AP_SERVICE_BOFORMS0PARAMETERS0wsdl', '". \Itkg::$config['AP_SERVICE_BOFORMS']['PARAMETERS']['wsdl'] ."', 2),
					(30, 'DS_PROXY0URL', '" . Pelican::$config['DS_PROXY']['URL'] . "', 7),
					(31, 'DS_PROXY0LOGIN', '" . Pelican::$config['DS_PROXY']['LOGIN'] . "', 7),
					(32, 'DS_PROXY0PWD', '" . FunctionsUtils::f_crypt(Pelican::$config['BOFORMS_PRIVATE_KEY'],Pelican::$config['DS_PROXY']['PWD']) . "', 7),
					(33, 'DS_PROXY0CURLPROXY_HTTP', '" . Pelican::$config['DS_PROXY']['CURLPROXY_HTTP'] . "', 7),
					(34, 'AP_PROXY0URL', '" . Pelican::$config['AP_PROXY']['URL'] . "', 7),
					(35, 'AP_PROXY0LOGIN', '" . Pelican::$config['AP_PROXY']['LOGIN'] . "', 7),
					(36, 'AP_PROXY0PWD', '" . FunctionsUtils::f_crypt(Pelican::$config['BOFORMS_PRIVATE_KEY'],Pelican::$config['AP_PROXY']['PWD']) . "', 7),
					(37, 'AP_PROXY0CURLPROXY_HTTP', '" . Pelican::$config['AP_PROXY']['CURLPROXY_HTTP'] . "', 7),
					(38, 'BOFORMS_LOG_PATH', '" . Pelican::$config['BOFORMS_LOG_PATH'] . "', 5),
					(200, 'URL_BOLP_FR', '', 10),
					(201, 'URL_BOLP_BE', '', 10)
					;");
    
    	$this->createConfigurationFile();
		
    	exit(0);
    }

	public function editParametersAction(){

		$culture = $this->getParam('culture');
		$si_id = $this->getParam('si_id');


		if(in_array($_SESSION[APP]['user']['id'],Pelican::$config['BOFORMS_USER_SUPER_ADMIN']))
		{
			$profileType = 'SUPERADMIN_BOFORMS';
		}elseif($_SESSION[APP]['SITE_ID'] == 1){
			$profileType = 'DSIN';
		}else{
			$profileType = 'WM_PAYS';
		}

		// displays parameters

		parent::editAction();
		$head = $this->getView()->getHead();
		$head->setMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');


		$conf_id  = $this->getParam('id');
		$conf_name = t('BOFORMS_REFERENTIAL_CONF_TYPE_CUSCO');

		// call the getParameter WS
		//$this->getParametersWS($current_locale, $conf_id);
		$this->getParametersWS($profileType, $culture, $si_id);

		$form = '<font style="font-weight:bold;">' . $conf_name . '</font>&nbsp;' . ' - ' .$si_id . ' - ' .  $culture;
		$this->oForm = Pelican_Factory::getInstance('Form', true);

		$form .= $this->oForm->open(Pelican::$config["DB_PATH"]);
		$form .= $this->beginForm($this->oForm);
		$this->oForm->bDirectOutput = false;
		$form .= $this->oForm->beginFormTable();


		$interfaces = array();

		for ($i = 0; $i < count($this->parameters);$i++) {
			$interfaceSi = $this->parameters[$i];
			if (isset($this->parameterInfo[$interfaceSi]) && count($this->parameterInfo[$interfaceSi]) > 0) {
				$form_tmp = $this->oForm->createLabel( "",'<span style="font-size:110%;padding:4px;font-weight:bold;background-color:#e4eef5;border:1px solid black;">' . $interfaceSi . ' - ' . $this->mappingSiName[$interfaceSi] . "</span>");
				$form_tmp2 = '';
				$interfaces[] = $interfaceSi;

				for ($zx = 0; $zx < count($this->parameterInfo[$interfaceSi]); $zx++) {
					//$is_admin_param = in_array($this->parameterInfo[$interfaceSi][$zx]['name'], $this->conf_gdo_cusco_only_admin);
					$form_tmp2 .= $this->oForm->createTextArea($interfaceSi . '____' . $this->parameterInfo[$interfaceSi][$zx]['name'],$this->parameterInfo[$interfaceSi][$zx]['name'], false, $this->parameterInfo[$interfaceSi][$zx]['value'], null, $this->readO, 5, 50);

				}
				if ($form_tmp2 != '') {
					$form = $form . $form_tmp . $form_tmp2;
				}

			}
		}

		$form .= $this->oForm->createHidden("CONF_ID", $conf_id);
		$form .= $this->oForm->createHidden("INTERFACES", implode(",", $interfaces));
		$form .= $this->oForm->createHidden('MAPPING_SI_NAME', base64_encode(serialize($this->mappingSiName)));

		$form .= $this->oForm->endTab ();
		$form .= $this->beginForm ( $this->oForm );
		$form .= $this->oForm->beginFormTable ();
		$form .= $this->oForm->endFormTable ();
		$form .= $this->endForm ( $this->oForm );
		$form .= $this->oForm->close ();

		$form = formToString($this->oForm, $form);

		$this->setResponse($form);

	}


	public function selectInterfaceAction()
	{
		$form = $this->startStandardForm();

		$interfaceTypeWS = $this->getReferential('INTERFACE_TYPE_REF');

		if(!empty($interfaceTypeWS) && is_array($interfaceTypeWS))
		{
			foreach($interfaceTypeWS as $row)
			{
				$typeInterfaces[$row->refCode] = $row->label;

				$siWS[$row->refCode] = $this->getReferential($row->refCode);


			}
			unset($interfaceTypeWS);
		}else{
			die('No referential "INTERFACE_TYPE_REF" found');
		}


		$oConnection = Pelican_Db::getInstance ();

		$sqlGetCultures = "SELECT concat( concat( pl.LANGUE_CODE, '-' ) , SITE_CODE_PAYS ) AS id, concat( concat( pl.LANGUE_CODE, '-' ) , SITE_CODE_PAYS )  as lib, pl.LANGUE_ID
													FROM #pref#_site_code ps
													INNER JOIN #pref#_site_language psl ON psl.SITE_ID = ps.SITE_ID
													INNER JOIN #pref#_language pl ON pl.LANGUE_ID = psl.LANGUE_ID
													inner join #pref#_boforms_country c on SITE_CODE_PAYS = c.alpha2 ";

		if ($_SESSION[APP]['SITE_ID'] != 1) {
			$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
			$datas = $oConnection->queryItem($sqlGetCultures . " where ps.site_id = :SITE_ID", $aBind);
			$form .= $this->oForm->createInput("CULTURE_ID", 'Culture', 255, "", true, $datas, true, 50);
		} else {
			// site admin
			$sqlGetCultures .= " where ps.SITE_ID != 1 order by lib";
			$form .= $this->oForm->createComboFromSql($oConnection, "CULTURE_ID", 'Culture', $sqlGetCultures, null, true, $_GET["readO"]);
		}

		$sql = "SELECT BRAND_ID, BRAND_KEY \"lib\"  FROM #pref#_boforms_brand order by BRAND_KEY";
		//$form .= $this->oForm->createComboFromList($oConnection, "BRAND_ID", t('BOFORMS_BRAND_ID'), $typeInterfaces, null, true, $_GET["readO"]);
		$form .= $this->oForm->createComboFromList("INTERFACE_ID", 'Type d\'interface', $typeInterfaces,'', true, $this->readO);


		$form .= '<tr id="ligne_master_choose" style="display:none;"><td class="formlib">Choix du SI *</td><td class="formval" id="td_master"></td></tr>';
		$button = $this->oForm->createButton('submit_interface', 'ok',  "", false);

		$url_display_forms = "/_/Index/child?id=".Pelican::$config['BOFORMS_CONFIG_INTERFACE']."&tid=" . FunctionsUtils::getTemplateId('BoForms_Administration_ConfModule') . "&SCREEN=editParameters";
		$html = '<script type="text/javascript">';

		$html .= "$( document ).ready(function() {

				// set top title
				window.parent.$('#frame_right_top').html('" . t('BOFORMS_DUPLICATIONMODULE') . "');
				var masterCorresp = new Object();

				var SI = new Object();
				";

				if(is_array($siWS)){
					foreach ($siWS as $k=>$si)
					{
						$html .= "SI['".$k."'] = [";
						$i = 0;
						foreach($si as $row)
						{
							if($i>0)
							{
								$html .= ",";
							}
							$html .= "'".$row->refCode."'";
							$i++;
						}
						$html .= "];
						";
					}
				}


		$html .="


				$('#INTERFACE_ID').on('change', function() {

						var html_tmp = '<select size=\"1\" id=\"SI_ID\" name=\"SI_ID\" >';
						html_tmp += '<option value=\"\">-&gt; Choisissez</option>';

						for(data in SI[$('#INTERFACE_ID').val()]){
							html_tmp += '<option value=\"' + SI[$('#INTERFACE_ID').val()][data] + '\">' + SI[$('#INTERFACE_ID').val()][data] + '</option>';
						}
						html_tmp += '</select>';

						$('#td_master').html(html_tmp);
						$('#ligne_master_choose').show();

				});

				$('#submit_interface').on('click', function() {
					if($('#INTERFACE_ID').val() && $('#CULTURE_ID').val() && $('#SI_ID').val())
					{
						window.location = '$url_display_forms&step=editInterface&interface_id=' + $('#INTERFACE_ID').val() + '&culture=' + $('#CULTURE_ID').val() + '&si_id=' + $('#SI_ID').val();
					}else{
						alert('formulaire incomplet');
					}
				});



    	     });";
		$html .= '</script>';

		$form .= $this->stopStandardForm();

		$this->aButton["add"]="";
		$this->aButton["save"]="";
		$this->aButton["back"]="";
		Backoffice_Button_Helper::init($this->aButton);

		// Zend_Form stop
		$form = formToString($this->oForm, $form);
		$this->setResponse($html . "<h2>Choix de l'interfaces</h2>" . $form.$button);
	}



    
    public function editAction() {
    	$conf_id  = $this->getParam('id');


    	// WS configuration
		if($conf_id == Pelican::$config['BOFORMS_CONFIG_INTERFACE'])
		{

			if($this->getParam('SCREEN') == "editParameters")
			{
				$this->_forward('editParameters');
				return;
			}else {
				$this->_forward('selectInterface');
				return;
			}
		}

        
        parent::editAction(); 		
    	$head = $this->getView()->getHead();
		$head->setMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
    	        
		$aBind[':CONF_ID'] = $conf_id;
		$oConnection = Pelican_Db::getInstance ();
		$conf_name = t('BOFORMS_REFERENTIAL_CONF_TYPE_'. $oConnection->queryItem('select CONF_KEY from #pref#_boforms_list_conf where CONF_ID = :CONF_ID', $aBind));
		$form .= '<font style="font-weight:bold;">' . $conf_name . '</font>';
    	$this->oForm = Pelican_Factory::getInstance('Form', true);
        
        $form .= $this->oForm->open(Pelican::$config["DB_PATH"]);
        $form .= $this->beginForm($this->oForm);
        $this->oForm->bDirectOutput = false;
        $form .= $this->oForm->beginFormTable();
       
        $form .= $this->oForm->createHidden("CONF_ID", $conf_id);
        $form .= $this->oForm->createHidden("CONF_NAME", $conf_name);
    	
        switch ($conf_id) 
        {
            case 1: //JIRA
                $configs = JiraUtil::getJiraConfiguration(true);

                $form .= $this->oForm->createHidden("OLD_JIRA_PASSWORD",  $configs['JIRA_PASSWORD']);
                $form .= $this->oForm->createInput("JIRA_USERNAME", t('BOFORMS_JIRA_USERNAME'), 255, "", true, $configs['JIRA_USERNAME'], $this->readO, 50);
                $form .= $this->oForm->createPassword("JIRA_PASSWORD", t('BOFORMS_JIRA_PASSWORD'), 255, true, $configs['JIRA_PASSWORD'], $this->readO, 50);
                   
                $form .= $this->oForm->createInput("BOFORMS_JIRA0PROJECT_KEY", t('BOFORMS_JIRA0PROJECT_KEY'), 255, "", true, $configs['BOFORMS_JIRA0PROJECT_KEY'], $this->readO, 50);
                $form .= $this->oForm->createInput("BOFORMS_JIRA0ISSUE_URL", t('BOFORMS_JIRA0ISSUE_URL'), 255, "", true, $configs['BOFORMS_JIRA0ISSUE_URL'], $this->readO, 50);
                $form .= $this->oForm->createInput("BOFORMS_JIRA0ASSIGNEE_NAME", t('BOFORMS_JIRA0ASSIGNEE_NAME'), 255, "", true, $configs['BOFORMS_JIRA0ASSIGNEE_NAME'], $this->readO, 50);
                    
                $form .= $this->oForm->createTextArea("BOFORMS_JIRA0OTHER_ASSIGNEE",t('BOFORMS_JIRA0OTHER_ASSIGNEE'), true, $configs['BOFORMS_JIRA0OTHER_ASSIGNEE'], 255, $this->readO, 5, 50);
                $form .=$this->oForm->createLabel( "",t("BOFORMS_AIDE_CONF_ADMIN"));
                break;
            case 2: //WEBSERVICE_BOFORMS
            case 3: //Webservice Traduction Composants avancés 
            case 10: //Configuration par site des URL BOLP	
            	// creation des config manquantes si besoin
        		
        		// pour chaque site on recupere la cle correspondante
				$listeSitePays = $oConnection->queryTab('SELECT distinct SITE_CODE_PAYS FROM `#pref#_site_code` where SITE_ID != 1');
        		foreach($listeSitePays as $pays) {
        			$country_key = 'URL_BOLP_' . $pays['SITE_CODE_PAYS'];
					$aBind[":URL_BOLP"] = $oConnection->strToBind($country_key);
        			$nb_elem = $oConnection->queryItem("select count(*) as nb from #pref#_boforms_conf where CONF_ID = 10 and CONF_VALUE_KEY = :URL_BOLP", $aBind);
					if ($nb_elem == 0) {					
						$oConnection->query("INSERT INTO `#pref#_boforms_conf` (`CONF_VALUE_KEY`, `CONF_VALUE`, `CONF_ID`) VALUES (:URL_BOLP, '', 10)", $aBind);
					}	
        		}
            case 4: //Webservice DEALERSERVICE
                $results = $this->getConfById($conf_id);
	            foreach ($results as $key => $result) 
	            {
					if ($conf_id == 10) {
						$country = str_replace('URL_BOLP_', '', $result['CONF_VALUE_KEY']);					
						$form .= $this->oForm->createInput($result['CONF_VALUE_KEY'], t('BOFORMS_URL_BOLP') . ' ' . $country, 255, "", false, $result['CONF_VALUE'], $this->readO, 50);
					} else {
						$form .= $this->oForm->createInput($result['CONF_VALUE_KEY'], t($result['CONF_VALUE_KEY']), 255, "", true, $result['CONF_VALUE'], $this->readO, 50);	
					}            		            	
	            }
	            if ($conf_id == 2) {
	              	$form .= '<tr><td colspan="2">' . t('BOFORMS_WS_DEFAULT_BRAND_IS') . ': ' . Pelican::$config['BOFORMS_BRAND_ID'] . '</td></tr>';
	            } 
                break;
            case 5: //Configuration Générale
            	$results = $this->getConfById($conf_id);
                foreach ($results as $key => $result) 
                {
                    if ($result['CONF_VALUE_KEY'] == 'BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL')
                    {
                        $form .= $this->oForm->createTextArea($result['CONF_VALUE_KEY'], t($result['CONF_VALUE_KEY']), true, $result['CONF_VALUE'], 255, $this->readO, 5, 50);
                        $form .=$this->oForm->createLabel( "",t("BOFORMS_AIDE_CONF_ADMIN"));
                    }
                    else
                    {
                        $form .= $this->oForm->createInput($result['CONF_VALUE_KEY'], t($result['CONF_VALUE_KEY']), 255, "", true, $result['CONF_VALUE'], $this->readO, 50);
                    }
                }
                break;
            case 6: // administration des super users
                $results = $this->getConfById($conf_id);
                foreach ($results as $key => $result) 
                {   
                    $form .= $this->oForm->createTextArea($result['CONF_VALUE_KEY'], t($result['CONF_VALUE_KEY']), true, $result['CONF_VALUE'], 255, $this->readO, 5, 50);
                    $form .=$this->oForm->createLabel( "",t("BOFORMS_AIDE_CONF_ADMIN"));
                }
                break;
            case 7: // PROXY
        		$results = $this->getConfById($conf_id);

        		
        		foreach ($results as $key => $result) 
                {   
                	if (in_array($result['CONF_VALUE_KEY'], array("AP_PROXY0PWD", "AC_PROXY0PWD", "DS_PROXY0PWD"))) {
        				$form .= $this->oForm->createHidden("OLD_" . $result['CONF_VALUE_KEY'],  $result['CONF_VALUE']);
                		$form .= $this->oForm->createPassword($result['CONF_VALUE_KEY'], t('BOFORMS_' . $result['CONF_VALUE_KEY']), 255, false, $result['CONF_VALUE'], $this->readO, 50);
                	} else {
						$form .= $this->oForm->createInput($result['CONF_VALUE_KEY'], t('BOFORMS_' . $result['CONF_VALUE_KEY']), 255, "", false, $result['CONF_VALUE'], $this->readO, 50);                
                	}      

                	if (in_array($result['CONF_VALUE_KEY'], array('AP_PROXY0CURLPROXY_HTTP', 'AC_PROXY0CURLPROXY_HTTP', 'DS_PROXY0CURLPROXY_HTTP'))) {
                		$form .= '<tr><td colspan="2"><hr/></td></tr>';
                	}
                	
                }
        		if ($conf_id == 7) {
	              	$form .= '<tr><td colspan="2">' . t('BOFORMS_WS_DEFAULT_BRAND_IS') . ': ' . Pelican::$config['BOFORMS_BRAND_ID'] . '</td></tr>';
	            }
            	break;
            default:
                $form .= "Config not found";
                break;
        }

    	$form .= $this->oForm->endTab ();
		$form .= $this->beginForm ( $this->oForm );
		$form .= $this->oForm->beginFormTable ();
		$form .= $this->oForm->endFormTable ();
		$form .= $this->endForm ( $this->oForm );
		$form .= $this->oForm->close ();

		$form = formToString($this->oForm, $form);
    	
		$this->setResponse($form);
    }
    
    public function saveWSAction() {

    	$tblInterfaces = explode(',', $this->getParam('INTERFACES'));
    	$this->mappingSiName = unserialize(base64_decode($this->getParam('MAPPING_SI_NAME')));
    	
    	$parametersInfo = array();
    	
    	$params = $this->getParams();
    	    	
    	for ($i = 0; $i < count($tblInterfaces); $i++) {
    		$interfaceSi = $tblInterfaces[$i];
    		$tbl_resultat = array(); 
    		
	    	foreach($params as $key => $value) {
	    		$pos = strpos($key, $interfaceSi . '____');
	    		if ($pos !== false) {
	    			$tbl_infos = explode('____', $key);
	    			if (count($tbl_infos) == 2) {
	    				$tbl_resultat[] = array('name' => $tbl_infos[1], 'value' => $value);
	    			}
	    		}
	    	}
	    	
	    	$parametersInfo[$interfaceSi] = $tbl_resultat;
    	}


    	
    	$this->updateParametersWS($tblInterfaces ,$parametersInfo);
    }
        
    public function saveAction() {
		$conf_id  = $this->getParam('CONF_ID');

    	// cas particulier des ws
		if (Pelican::$config['BOFORMS_CONFIG_INTERFACE'] == $conf_id) {
			$this->_forward('saveWS');
    		return;
    	}    	

    	// enregistrement en base de données
    	$oConnection = Pelican_Db::getInstance ();		
		$aBind[':CONF_ID'] = $conf_id;

		// updates password fields
        if (isset($this->conf_key_password[$conf_id])) {
        	for($jj = 0; $jj < count($this->conf_key_password[$conf_id]); $jj++) {
        		$the_key = $this->conf_key_password[$conf_id][$jj]; 
        		$the_old_key = 'OLD_' . $the_key;
        		
				if($this->getParam($the_key) == $this->getParam($the_old_key) && $this->getParam($the_old_key))
            	{
            		$pass = $this->getParam($the_key);
            	}else{
            		$pass = FunctionsUtils::f_crypt(Pelican::$config['BOFORMS_PRIVATE_KEY'],$this->getParam($the_key));
            	}  
            	$aBind[':CONF_VALUE_PASSWORD'] = $oConnection->strToBind($pass);
            	
                $oConnection->query("update #pref#_boforms_conf 
                					 set CONF_VALUE = :CONF_VALUE_PASSWORD 
                					 where CONF_ID = :CONF_ID and CONF_VALUE_KEY = '$the_key'", $aBind);        		
        	}
        }

        // ================ updates text fields ===============
        
        if (isset($this->conf_key_str[$conf_id])) {
        	for($jj = 0; $jj < count($this->conf_key_str[$conf_id]); $jj++) {
        		$the_key = $this->conf_key_str[$conf_id][$jj];
        		
        		// cas particulier gestion par site des url bo_lp
        		if ($conf_id == 10 && $the_key == 'URL_BOLP') {
					// pour chaque site on recupere la cle correspondante
					$listeSitePays = $oConnection->queryTab('SELECT distinct SITE_CODE_PAYS FROM `#pref#_site_code` where SITE_ID != 1');
        			foreach($listeSitePays as $pays) {
        				$site_pays = $pays['SITE_CODE_PAYS'];
        				
        				$aBind2[':CONF_ID'] = $conf_id;
        				$country_key = $the_key . '_' . $site_pays;
						$aBind2[":$the_key"] = $oConnection->strToBind($this->getParam($country_key));
        				$oConnection->query("update #pref#_boforms_conf set CONF_VALUE = :$the_key where CONF_ID = :CONF_ID and CONF_VALUE_KEY = '$country_key'", $aBind2);
        			}
        		} else {
        			$aBind[":$the_key"] = $oConnection->strToBind($this->getParam($the_key));
       				$oConnection->query("update #pref#_boforms_conf set CONF_VALUE = :$the_key where CONF_ID = :CONF_ID and CONF_VALUE_KEY = '$the_key'", $aBind);
        		}               
        	}
        }

        $this->createConfigurationFile();
    }
    
    
    public function createConfigurationFile() {
     	//ouverture du fichier de conf 
        $handle = fopen(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local/BOForms.admin.ini.'.$_ENV["TYPE_ENVIRONNEMENT"].'.php', 'w+') or die ("Impossible d'écrire dans le fichier de configuration ".Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local/BOForms.admin.ini'.$_ENV["TYPE_ENVIRONNEMENT"].'.php');
        // initialisation de la str à écrire 
        $str="<?php 
//Fichier de config généré automatiquement depuis le Back-Office
//Configuration file automatically generated from the Back-Office\n\n";
        // écriture dans le fichier 
        if ($handle != false) {
            //récupération de l'intégralité des données de conf
            $oConnection = Pelican_Db::getInstance ();		
		
        	$aConf = $oConnection->queryTab('select CONF_ID, CONF_VALUE_ID, CONF_VALUE_KEY, CONF_VALUE from #pref#_boforms_conf ', $aBind);

            foreach ($aConf as $key => $conf) {
                if($conf['CONF_VALUE_KEY'] != 'JIRA_USERNAME' && $conf['CONF_VALUE_KEY'] != 'JIRA_PASSWORD')
                {
                    if($conf['CONF_ID'] == 2 || $conf['CONF_ID'] == 3 || $conf['CONF_ID'] == 4 || $conf['CONF_ID'] == 10)
                    {
                        $prefix = "\\Itkg::\$config";
                    }else{
                        $prefix = "Pelican::\$config";
                    }
                    
                    
                    if ( $conf['CONF_VALUE_KEY'] == 'AC_PROXY0PWD' || $conf['CONF_VALUE_KEY'] == 'AP_PROXY0PWD' || $conf['CONF_VALUE_KEY'] == 'DS_PROXY0PWD') {
                    	$field_value = FunctionsUtils::f_decrypt(Pelican::$config['BOFORMS_PRIVATE_KEY'], $conf['CONF_VALUE']);
                	} else {
                    	$field_value = $conf['CONF_VALUE'];
                    }
                    
                    $str .= $prefix.$this->getArrayPathFromString($conf['CONF_VALUE_KEY'])." = ".$this->getStrArray($conf['CONF_VALUE_KEY'], $field_value).";\n";
                }                
            }
            fwrite($handle, $str);
            fclose($handle);
        }
        else
        {
            die ("Impossible d'écrire dans le fichier de configuration ".Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local/BOForms.admin.ini'.$_ENV["TYPE_ENVIRONNEMENT"].'.php');
        }
    }

    public function getConfById($idConf)
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':CONF_ID'] = $idConf;
        return $oConnection->queryTab('select CONF_VALUE_ID, CONF_VALUE_KEY, CONF_VALUE from #pref#_boforms_conf where CONF_ID = :CONF_ID', $aBind);
    }

    /*
    parse un string de type BOFORMS_JIRA0OTHER_ASSIGNEE en string ['BOFORMS_JIRA']['OTHER_ASSIGNEE']
    */
    public function getArrayPathFromString($str)
    {
        if (strpos($str,'0') != false) 
        {
            $aParams = explode("0", $str);
            $newStr ="";
            foreach ($aParams as $key => $param) {
                $newStr .= "['".$param."']";
            }
            return $newStr;
        }
        else
        {
            return "['".$str."']";
        }
    }

    /*
    transforme une string de type E452238,E462944,E458302 en string array('E452238','E462944','E458302')
    dans le cas des key suivantes : 
    BOFORMS_USER_SUPER_ADMIN
    BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL
    BOFORMS_JIRA0OTHER_ASSIGNEE
    */
    public function getStrArray($key, $str)
    {
        $newStr = "";
        $aExceptions = array("BOFORMS_USER_SUPER_ADMIN","BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL","BOFORMS_JIRA0OTHER_ASSIGNEE");
        if(in_array($key, $aExceptions))
        {
            //traitement
            $aVals = explode(',', str_replace(" ", "", $str));
            if (count($aVals) == 0) {
            	$newStr = "array()";
            } else {
            	$newStr = "array('" . implode("','", $aVals) . "')";
            }
        }else{
            $newStr = "'".$str."'";
        }

        return $newStr;
    }
 
 
}

