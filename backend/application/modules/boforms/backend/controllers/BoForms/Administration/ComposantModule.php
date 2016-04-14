<?php
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.ini.php');
include(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/XMLParser.class.php'); 
include(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/WebServicesSoap.class.php'); 
include(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/DraftUtil.class.php');
include(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FunctionsUtils.php');  

/*** WebServices***/
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/services.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/I18N.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/i18N/Configuration.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/i18N/Model/GetXMLComponentRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/i18N/Model/GetXMLComponentResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/i18N/Model/UpdateXMLComponentRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/i18N/Model/UpdateXMLComponentResponse.php');

//ajout du fichier de conf administrable en BO
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.admin.ini.php');

/**
    *
    * Controller Backend
    *library/Pelican/Translate/public/images/flags/fr.png
    */
class BoForms_Administration_ComposantModule_Controller extends Pelican_Controller_Back
{
	protected $administration = true;
    protected $form_name = "boforms_composant_type";
    protected $field_id = "COMPOSANT_TYPE_ID";
    protected $defaultOrder = "COMPOSANT_TYPE_SITE,COMPOSANT_HTML_LABEL";
    
    protected function setListModel ()
    {
    	$sfilter="";
    	if($_GET['filter_search_keyword'])
    	{
    	
    		$aField = array("COMPOSANT_TYPE_LABEL","COMPOSANT_TYPE_SITE");
    	
    		$word = $_GET['filter_search_keyword'];
    		$sfilter = "where ";
    		foreach ($aField as $j=>$field)
    		{
    			if($j>0)
    				$sfilter .= " OR ";
    	
    			$sfilter .= "UPPER($field) like UPPER('%$word%')";
    		}
    	
    	}
    	
        $this->listModel = "SELECT *
   	    					FROM #pref#_boforms_composant_type 
		$sfilter					
        order by ".$this->listOrder;
    }

    protected function setEditModel ()
    {
            $this->editModel = "SELECT * from #pref#_boforms_groupe WHERE GROUPE_ID='" . $this->id . "'";
    }

    public function listAction ()
    {
    	
    	$oConnection = Pelican_Db::getInstance ();
    	 
    	$sqlLangue = "SELECT l.LANGUE_ID, LANGUE_TRANSLATE,LANGUE_CODE
    				  FROM #pref#_site_language sl
    				  INNER JOIN #pref#_language l ON (l.LANGUE_ID=sl.LANGUE_ID)
    				  WHERE SITE_ID = ".$_SESSION[APP]['SITE_ID'];
    	   	
    	$aLangues = $oConnection->queryTab($sqlLangue);
    	
    	parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", array(/*"COMPOSANT_TYPE_LABEL","COMPOSANT_TYPE_SITE"*/));
        $table->setFilterField("site","<b>".t('FORM_SITE')."&nbsp;:</b><br/>","COMPOSANT_TYPE_SITE","select COMPOSANT_TYPE_SITE id, COMPOSANT_TYPE_SITE lib FROM #pref#_boforms_composant_type GROUP BY COMPOSANT_TYPE_SITE");
        $table->setFilterField();
        $table->getFilter(3);
        $table->setCSS(array(
            "tblalt1",
            "tblalt2"
        ));
        
        $table->setValues($this->getListModel(), "COMPOSANT_TYPE_ID");
        $table->addColumn(t('FORM_SITE'), "COMPOSANT_TYPE_SITE", "10", "left", "", "tblheader");
        $table->addColumn(t('LABEL'), "COMPOSANT_TYPE_LABEL", "50", "left", "", "tblheader");
        
        if(is_array($table->aTableValues) && !empty($table->aTableValues))
        {
        	foreach ($table->aTableValues as $k=>$row)
        	{
        		if(is_array($aLangues) && !empty($aLangues))
        		{
        			if($table->aTableValues[$k]['COMPOSANT_TYPE_SITE'] != LP) {
        				$table->aTableValues[$k]['COMPOSANT_TYPE_SITE'] = Pelican::$config['BOFORMS_CONSUMER'];
        			}
        			
        			foreach ($aLangues as $lang)
        			{
        				$table->aTableValues[$k]['LANGUE'] .= $lang['LANGUE_TRANSLATE']."<br/>";
        				
        			}
        		}
        	}
        }
        
        $table->addColumn(t('LANGUAGE'), "LANGUE", "50", "left", "", "tblheader");
       
      	$table->addInput(str_replace(" ", "&nbsp;", t('BOFORMS_I18N_EDIT_WEB')), "button", array(
				            "id" => "COMPOSANT_TYPE_ID",
            				"" => "device=HTML"
				        ), "center");
				        
		$table->addInput(str_replace(" ", "&nbsp;", t('BOFORMS_I18N_EDIT_MOBILE')), "button", array(
				            "id" => "COMPOSANT_TYPE_ID",
            				"" => "device=MOBILE"
				        ), "center",array('COMPOSANT_TYPE_SITE!=LP','COMPOSANT_TYPE_LABEL!=DealerLocatorLight'));
			
		
        
		$this->aButton["add"]="";
		Backoffice_Button_Helper::init($this->aButton);
		
		// exact filter on search_keyword (jira 88)
		//$_GET['filter_search_keyword']
		
		
echo '<script type="text/javascript" src="' . Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js' . '"></script>';
echo '<script type="text/javascript">';
echo "$( document ).ready(function() {
		window.parent.$('#frame_right_top').html('" . t('BOFORMS_TRANSLATE_ADVANCED_COMPONENTS') . "'); 
    	$('#body_child div.form_title').html('" . t('BOFORMS_TRANSLATE_LIST_ADVANCED_COMPONENTS') . "');
     });";
echo '</script>';
		
		
		$this->setResponse($table->getTable());
    }
    
 
    private function doDisplayFormFromDraft($draft, $component) {
	    	$form = "";
	    	
    		$cultures = $draft->CULTURE;
			$langs = explode(',', $cultures);
        	$first_lang = $langs[0];
        	$last_lang = $langs[count($langs) - 1 ];
        	
			foreach($draft as $property => $value)  {
			    if ($property != "") {
			    	$pos = strpos($property, "__Text");
        			if ($pos === false) {
        				$pos = strpos($property, "__");
        				if ($pos !== false) {
        					$form .= $this->oForm->createHidden($property, htmlentities($value, ENT_QUOTES, "UTF-8"));	
        				}
        			} else {
        				// text field
						$tbl_fields = explode('__', $property);
						$tmp_lang = $tbl_fields[0];
						//$groupcode = $tbl_fields[1]; // HTML_brochurePicker
						//$indice = $tbl_fields[2];    // 0
						$code = $tbl_fields[3];
						
						if ($tmp_lang == $first_lang) {
							$form .= '<tr><td style="text-align:left">' . $code . '</td>';
						}
						
	   					$form .='<td class=""><input id="' . $property . '" class="text" type="text" onfocus="activeInput = this;" value="'. htmlentities($value, ENT_QUOTES, "UTF-8") .'" maxlength="255" size="50" name="' . $property . '"></td>';

        				if ($tmp_lang == $last_lang) {
        					$form .='</tr>';		
        				}
        			}
			    }    
			}
			return $form;
    }
			
    private function doDisplayFormFromTable($list_translations, $component) {
			$cpt = 0;	
			$form = '';
			
			foreach ($list_translations as $code => $tbl_culture) {
	   			if ($code != "") {
	   				$form .= '<tr><td style="text-align:left">' . $code . '</td>';
		    			
	    			foreach ($tbl_culture as $tmp_culture => $tbl_datas) {
	   					$prefix = $tmp_culture . "__". $component."__". $cpt."__". $code;
	    					
    					$form .='<td class=""><input id="'. $prefix ."__Text".'" class="text" type="text" onfocus="activeInput = this;" value="'. htmlentities($tbl_datas['text'], ENT_QUOTES, "UTF-8") .'" maxlength="255" size="50" name="'.$prefix."__Text".'"></td>';
	    							
	   					$form .= $this->oForm->createHidden($prefix."__Code", $tbl_datas['code']);		
						$form .= $this->oForm->createHidden($prefix."__GroupCode",$tbl_datas['groupCode']);		
						$form .= $this->oForm->createHidden($prefix."__Order",   $tbl_datas['order']);				
						$form .= $this->oForm->createHidden($prefix."__Language",$tbl_datas['language']);				
						$form .= $this->oForm->createHidden($prefix."__Date", $tbl_datas['date']);				
						$form .= $this->oForm->createHidden($prefix."__Prov",$tbl_datas['prov']);	
							
						$cpt++;
    				}
    				$form .='</tr>';
    			}
   			}
   			return $form;
    }
    
    public function editAction ()
    {
    	// if the user answered the question "use the draft? yes/no"
    	if (isset($_GET['use_draft'])) {
    		$this->_forward('doEdit');
    		return;
    	}    	
    	
    	$brand = Pelican::$config['BOFORMS_BRAND_ID'] ;
    	
    	$component = $this->getComponentNameFromId($_GET['id'], $_GET['device']);
    	
    	// get the languages
    	$tbl_cultures = $this->getCulturesAndCountry();
    	$cultures = $tbl_cultures['cultures'];
    	$country =  $tbl_cultures['country'];
    	
    	$draft_exists = DraftUtil::checkDraft ($brand, $country, $cultures, $component);
    	$tbl_GET = $_GET;
    	    	
    	// if a draft exists, ask the user if he wants to use this draft or not
    	if ($draft_exists == true) {
			$this->oForm = Pelican_Factory::getInstance('Form', true);
	        $form .= 'Un brouillon existe. Voulez-vous utiliser ce brouillon?<br/>';
			$form .= $this->oForm->open(Pelican::$config["DB_PATH"]);
	        $form .= $this->beginForm($this->oForm);
	        $this->oForm->bDirectOutput = false;

	        $url_params = '';
	        foreach ($tbl_GET as $key => $value) {
	        	$url_params .= '&' . $key . '=' . $value;
	        }
    	    $form .= '<a href="' . $_SERVER['REQUEST_URI'] . '&use_draft=1">oui</a>&nbsp;';
	        $form .= '<a href="' . $_SERVER['REQUEST_URI'] . '&use_draft=0">non</a>';
	        
			$form .= $this->oForm->endTab ();
			$form .= $this->beginForm ( $this->oForm );
			$form .= $this->endForm ( $this->oForm );
			$form .= $this->oForm->close ();
			$form = formToString($this->oForm, $form);
				
			$this->aButton["save"]="";
                     Backoffice_Button_Helper::init($this->aButton);
			
		    $this->setResponse($form);
    	} else {
    		$this->_forward('doEdit');
    	}
    	
    		
    	
    }
    
    private function getCulturesAndCountry() {
    	$oConnection = Pelican_Db::getInstance ();
    	
    	$sqlLangue = "SELECT c.langue_code, c.langue_label, a.SITE_CODE_PAYS FROM psa_site_code a
					  INNER JOIN psa_site_language b ON a.site_id = b.site_id
					  INNER JOIN psa_language c ON c.langue_id = b.langue_id
					  WHERE a.site_id = :SITE_ID";
		$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
    	   
    	$aLangues = $oConnection->queryTab($sqlLangue, $aBind);
    	
    	$cultures = '';
    	$country  = '';
    	for($i = 0; $i < count($aLangues); $i++) {
			   $langue_code = $aLangues[$i]['langue_code'];
			   $langue_label = $aLangues[$i]['langue_label'];
			   $country = $aLangues[$i]['SITE_CODE_PAYS'];
			   if ($cultures != '') {
			   		$cultures .= ',';
			   } 
			   $cultures .= $langue_code . '-' . $country;
    	}
    	if ($cultures == '') {
    		die ("an error occured while getting cultures for site " . $_SESSION[APP]['SITE_ID']);
    	}
    	
    	// TODO remove this when configuration ok in database
    	//return array("cultures" => "fr-BE,de-BE,nl-BE", 'country' => "BE"); 
    	
        	
    	 return array("cultures" => $cultures, 'country' => $country);
    	
    }
    
    public function doEditAction ()
    {
		    	
    	$oConnection = Pelican_Db::getInstance ();
    	
    	$brand = Pelican::$config['BOFORMS_BRAND_ID'] ;
    	
    	// trouver le nom du composant ex: cpw_MOBILE_loginPopin
    	$component = $this->getComponentNameFromId($_GET['id'], $_GET['device']);
    	
    	// recherche info en base sur le composant
    	$sqlInfoComponent = 'select COMPOSANT_TYPE_SITE, COMPOSANT_TYPE_LABEL 
    	                     FROM `#pref#_boforms_composant_type` where COMPOSANT_TYPE_ID = :COMPOSANT_TYPE_ID';
    	$aBind[':COMPOSANT_TYPE_ID'] = $_GET['id'];
    	$infoComponent = $oConnection->queryTab($sqlInfoComponent, $aBind);
    	
    	// test si le composant est web et mobile ou seulement web
    	$componentWebAndMobile = true;
    	if ($infoComponent[0]['COMPOSANT_TYPE_SITE'] == 'LP' || $infoComponent[0]['COMPOSANT_TYPE_LABEL'] == 'DealerLocatorLight') {
    		$componentWebAndMobile = false;
    	}
    	unset($infoComponent);
    	    	
    	// get the languages
    	$tbl_cultures = $this->getCulturesAndCountry();
    	$cultures = $tbl_cultures['cultures'];
    	$country =  $tbl_cultures['country'];
    	
    	// checks if a draft exists or not
    	// if a draft exists, we load it
    	$mode = "webservice";
    	
    	if ($_GET['use_draft'] == 1 && DraftUtil::checkDraft ($brand, $country, $cultures, $component)) {
			$mode = "draft";
    		$draft = DraftUtil::getDraft($brand, $country, $cultures, $component);
    	} else {
			try {
				$componentCallWs = (isset($_GET['otherdevice'])) ? $this->getComponentNameFromId($_GET['id'], $_GET['otherdevice']) : $component; 
    			$serviceParams = array(
					'brand' => $brand,
					'country' => $country,
					'culture' => $cultures,
					'component' => $componentCallWs
			);
			
			$service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_I18N', array());
			
			$response = $service->call('getXMLComponent', $serviceParams);
		} catch(\Exception $e) {
			die($e->getMessage());
		}
		
		$list_translations = $this->getXMLComponentResult($response->content[0]);
		
    		// mode = webservice
    		/*$webServicesSoap = new WebServicesSoap();
    		$ws_ok = $webServicesSoap->getXMLComponent($brand, $country, $cultures, $component);
    		if (! $ws_ok) {
    			die('An error occured while calling webservice<br/>' . $webServicesSoap->getErrorMessage());
    		}
    		
    		$list_translations = $webServicesSoap->getXMLComponentResult();
    		debug($list_translations);*/
    		
			$ws_returned_datas = ($list_translations != null && is_array($list_translations));
    		if (!$ws_returned_datas) {
    			die('Web services returned no results<br/>');
    		}else{
				$ws_ok = true;
			}
			
			echo '<script type="text/javascript" src="' . Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js' . '"></script>';
			echo '<script type="text/javascript">';
			echo "$( document ).ready(function() {
					window.parent.$('#frame_right_top').html('" . t('BOFORMS_TRANSLATE_ADVANCED_COMPONENTS') . "'); 
			     });";
			echo '</script>';
    	}
    	
    	// display the start of the form
    	
    	parent::editAction();
        
		$this->oForm = Pelican_Factory::getInstance('Form', true);
        $form .= $this->oForm->open(Pelican::$config["DB_PATH"]);
        $form .= $this->beginForm($this->oForm);
        $this->oForm->bDirectOutput = false;
        $form .= $this->oForm->beginFormTable();
		
        $form .= "<h2>$component</h2>";
        
        // si on a clique sur le bouton pour copier les paramètres de la version mobile ou html
        if (isset($_GET['otherdevice'])) {
        	$form .= '<font color="red">' . t('BOFORMS_MSG_OVERRIDE_TRANSLATION_INFO') . '</font>';
        }
        
        $form .='<table><tr><th>Code</th>';
        
        $langs = explode(',', $cultures);
        for ($ii = 0; $ii < count($langs); $ii++) {
        	$form .= '<th>' . $langs[$ii] . '</th>';	
        }
        $form .= '</tr>';
    	
        // affichage hidden et text
        
    	if ($mode == "draft") {
    		$form .= $this->doDisplayFormFromDraft($draft, $component);
    	} else  {
    		if ($ws_ok) {
    			$form .= $this->doDisplayFormFromTable($list_translations, $component);
    		}
    	}
    		
    	// generates end of form	
    			
		$form .='</table>';
		
			$form .= $this->oForm->createHidden("DEVICE_ID",$_GET['device']);		
   			$form .= $this->oForm->createHidden("CULTURE", $cultures);
   			$form .= $this->oForm->createHidden("BRAND", $brand);
   			$form .= $this->oForm->createHidden("COUNTRY", $country);
   			$form .= $this->oForm->createHidden("COMPONENT", $component);

			// si composant web et mobile, on propose une copie des paramètres
   			if ($componentWebAndMobile == true) {
				// calcul de l'url pour recharger le form
   				$other_device = ($_GET['device'] == 'HTML') ? 'MOBILE' : 'HTML';
	   			$url_reload_form = "/_/Index/child?tid=" . FunctionsUtils::getTemplateId('BoForms_Administration_ComposantModule') . '&SCREEN=1&device=' . $_GET['device'] . '&id=' . $_GET['id'] . '&otherdevice=' . $other_device;
	   			
	   			$form .= $this->oForm->createButton("BtnImportFromSource", t('BOFORMS_BTN_COPY_PASTE_TRANSLATIONS_FROM_' . $other_device),
	"if (confirm('" . t('BOFORMS_MSG_OVERRIDE_TRANSLATION_FROM_' . $other_device) . "')) { window.location='$url_reload_form'}");
   			}
   			
			$form .= $this->oForm->endTab ();
			$form .= $this->beginForm ( $this->oForm );
			$form .= $this->oForm->beginFormTable ();
			$form .= $this->oForm->endFormTable ();
			$form .= $this->endForm ( $this->oForm );
			$form .= $this->oForm->close ();

				
			$form = formToString($this->oForm, $form);
				
			// generate javascript code for autosave
        	$form .= $this->generateAutoSaveScript($brand, $cultures,  $country, $component);
				
		    $this->setResponse($form);		
    }
    
    
    private function getComponentNameFromId($id, $device) {
    	$oConnection = Pelican_Db::getInstance ();
    	
    	$component  = '';
    	
    	$aBind[':COMPOSANT_TYPE_ID'] = $id;
    	if ($device == 'HTML') {
    		$result = $oConnection->queryRow('SELECT COMPOSANT_HTML_LABEL FROM `psa_boforms_composant_type` where composant_type_id = :COMPOSANT_TYPE_ID', $aBind);
    		$component = $result['COMPOSANT_HTML_LABEL'];
    	} else {
	    	$result = $oConnection->queryRow('SELECT COMPOSANT_MOBILE_LABEL FROM `psa_boforms_composant_type` where composant_type_id = :COMPOSANT_TYPE_ID', $aBind);	
    		$component = $result['COMPOSANT_MOBILE_LABEL'];
    	}
    	if ($component == '') {
    		die('an error occurred: no component name found for device: ' . $device . ' and id: ' . $id);
    	}
		return $component;	
    }
    
    public function saveAction ()
    {	
    	if (is_array(Pelican_Db::$values))
		{
			$brand   = Pelican_Db::$values['BRAND'];
			$country = Pelican_Db::$values['COUNTRY'];
			$component = Pelican_Db::$values['COMPONENT'];
			
			$i=0;
			$currGroup="";
			
			foreach (Pelican_Db::$values as $key=>$row) 
			{
				if(strpos($key,'__')>0)
				{
					$aTemp=explode('__',$key);
					
					$aGroupeCode[$aTemp[0]][$aTemp[1]][$aTemp[2]][$aTemp[4]] = $row;
					
				}
			}
			
			
			if (is_array($aGroupeCode) && !empty($aGroupeCode))
			{
				foreach ($aGroupeCode as $kCulture=>$aCulture)
				{
					foreach ($aCulture as $Groupe)
					{
						$FinalGroupes[$kCulture]["ArrayOfLabel"]["Label"] = $Groupe;
						
					}
				}
			}
		
			// construct file structure for the soap call
			$files = array();
									
			if(is_array($FinalGroupes))
			{	
				foreach ($FinalGroupes as $culture => $labels) {
					$file = new stdClass();
					$file->culture = $culture;
					
					$tbl_info = $labels['ArrayOfLabel']['Label'];
					$labels = array();
					
					 foreach ($tbl_info as $indice => $values) {
					 	if ($values['Code'] != '') {
							$label = new stdClass;
							$label->code = $values['Code'];
							$label->groupCode = $values['GroupCode'];
							$label->text = str_replace("<", "&lt;", $values['Text']);
							$label->order = $values['Order'];
							$label->language = $values['Language'];
							$label->date = $values['Date'];
							$label->prov = $values['Prov'];
							$labels[] = $label;
						}
					}
					
					
					$file->labels = $labels;				
					
					$files[] = $file; 
				}
			
			}
			
			try {
				$serviceParams = array(
						'brand' => $brand,
						'country' => $country,
						'component' => $component,
						'files' => $files,
						
				);
				
									
				$service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_I18N', array());
					
				
				
				$response = $service->call('updateXMLComponent', $serviceParams);
			
				if($response->returnCode == 'OK')
				{
					DraftUtil::deleteDraft($brand, $country, Pelican_Db::$values['CULTURE'], $component);
					
					$oConnection = Pelican_Db::getInstance ();
					$oConnection->commit();
				}else{
					die('error WS update : '.$response->errorMessage);
				}
				
			} catch(\Exception $e) {
				die($e->getMessage());
			}
			
			/*
			
			$webServicesSoap = new WebServicesSoap();
			
    		if ($webServicesSoap->updateXMLComponent($brand, $country, $component, $files)) {
	   			DraftUtil::deleteDraft($brand, $country, Pelican_Db::$values['CULTURE'], $component);
    				
    			$oConnection = Pelican_Db::getInstance ();
    			$oConnection->commit();
    		} else {
    			die ('error: ' . $webServicesSoap->getErrorMessage());
    		}*/
		}
	}
	
	public function saveAjaxAction ($params)
    {
    	// gets the parameters
    	$brand = $_POST['brand'];
    	$country = $_POST['country'];
    	$culture = $_POST['culture'];
    	$component = $_POST['component'];
    	
    	// get the json from the post
    	$json = $_POST['sJson'];
    	$json = str_replace("'", "\'", $json);
    	$json = str_replace('"', '\"', $json);
    	
    	// saves the draft
    	if($json != null && $json != '')
    	{
    		DraftUtil::deleteDraft($brand, $country, $culture, $component);
	   		
    		$oConnection = Pelican_Db::getInstance ();
    		$oConnection->commit();
	    		
	   		DraftUtil::createDraft($brand, $country, $culture, $component, $json);
	  	}
    }
	
    private function generateAutoSaveScript($brand, $culture,  $country, $component) {
    	//<a id="testsaveAuto">save auto</a>
    	
    	return '
		
		<script type="text/javascript">
			$( document ).ready(function() {
				$("#loader_save").hide(0);
				
				// $( "#testsaveAuto" ).click(function() {
				//	 saveAuto()
				// });
			
				function saveAuto(){
						var textJson = "{";
						i=0;
											
						$(":input").each(function(){
							if(i>0)
							{
								textJson = textJson + ",";
							}
							the_value = $(this).val();
							the_value = the_value.replace(new RegExp(String.fromCharCode(39), "g"), "" + String.fromCharCode(92) + String.fromCharCode(39));
    						the_value = the_value.replace(new RegExp(String.fromCharCode(34), "g"), "" + String.fromCharCode(92) + String.fromCharCode(34));
							
						    textJson = textJson + "\""+$(this).attr("id")+"\":\""+ the_value +"\"";
						    i=i+1;
						})
					    textJson = textJson + "}"
						
					    $.ajax({
							type : "POST",
							url: "/_/module/boforms/BoForms_Administration_ComposantModule/saveAjax",
                            data:  {brand: "' . $brand . '", country: "' . $country . '", culture: "' . $culture . '", component: "' . $component . '", sJson : textJson},
                                
							async: true,
							success: function(data) {
								
							},
							complete: function(data) {
								
							}
						});
				}
				//setInterval(saveAuto, 30000);
			});
		</script>
		';
    }
	
	
	function getXMLComponentResult($result) {
		$tblText = array();
		if (is_array($result)) {
			for ($i = 0; $i < count($result); $i++) {
	    		$culture = $result[$i]->culture;
	    		$labels  = $result[$i]->labels;
	
	    		for ($zzz = 0; $zzz < count($labels); $zzz++) {
	    			$tblText[$labels[$zzz]->code][$culture] = array('order' => $labels[$zzz]->order,
	    															'text' => $labels[$zzz]->text, 
	    															'language' => $labels[$zzz]->language, 
	    															'prov' => $labels[$zzz]->prov, 
	    															'date' =>  $labels[$zzz]->date,
	    															'code' => $labels[$zzz]->code,
	    															'groupCode' => $labels[$zzz]->groupCode
	    			);    			
	    		}
			}
		}
		
		return $tblText;
	}
}
