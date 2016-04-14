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

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetMastersRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetMastersResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstancesByMasterRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstancesByMasterResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/DuplicateInstanceRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/DuplicateInstanceResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstancesRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstancesResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstanceByIdRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstanceByIdResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/UpdateInstanceRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/UpdateInstanceResponse.php');

include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.admin.ini.php');
include(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/XMLHandle.class.php');

class BoForms_Administration_DuplicationModule_Controller extends Pelican_Controller_Back
{
	protected $administration = true;
	
    protected $get_instance;
   
    protected $bDraft = false;
    protected $bDraftAuto = false;

    protected $defaultAction = "edit";
    
    protected $form_name = "boforms_duplication";

    public function listAction () {
    	if ($this->getParam('step') == 'beforeDuplicate') {
    		$this->_forward('beforeDuplicate');
    		return;
    	} else if ($this->getParam('step') == 'duplicate') {
    		$this->_forward('duplicate');
    		return;
    	} else if ($this->getParam('step') == 'listForms') {
    		$this->_forward('listForms');	
    		return;
    	} else if ($this->getParam('step') == 'beforeDuplicateMulti') {
			$this->_forward('beforeDuplicateMulti');
			return;
		} else if ($this->getParam('step') == 'beforeDuplicateGeneMulti') {
			$this->_forward('beforeDuplicateGeneMulti');
			return;
		} else if ($this->getParam('step') == 'duplicateMulti') {
			$this->_forward('duplicateMulti');
			return;
		}else if ($this->getParam('type') == 'MASTER') {
			$this->_forward('initMaster');
			return;
		}else if ($this->getParam('type') == 'GENE') {
			$this->_forward('initGene');
			return;
		} else if ($this->getParam('step') == 'listFormsGene') {
			$this->_forward('listFormsGene');
			return;
		} else if ($this->getParam('step') == 'duplicateGeneMulti') {
			$this->_forward('duplicateGeneMulti');
			return;
		}else{
			$this->_forward('listType');
			return;
		}


    }

	public function listTypeAction(){
		parent::listAction();
		$table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");

		$table->setCSS(array(
			"tblalt1",
			"tblalt2"
		));
		$table->setValues(array(0 => array('TYPE' => 'Duplication from Master','CODE' => 'MASTER'),1 => array('TYPE' => 'Duplication from Generique','CODE' => 'GENE')), "bg.GROUPE_ID");

		$table->addColumn('Type de duplication', "TYPE", "10", "left", "", "tblheader");


		$table->addInput(t('POPUP_BUTTON_SELECT'), "button", array(
			"type" => "CODE"
		), "center");

		$this->setResponse($table->getTable() );
	}

	public function initGeneAction(){

		$oConnection = Pelican_Db::getInstance ();
		$form = $this->startStandardForm();
		$sql = "SELECT ALPHA2, nom_fr_fr \"lib\" FROM #pref#_boforms_country order by nom_fr_fr";
		$form .= $this->oForm->createComboFromSql($oConnection, "ALPHA2", t('BOFORMS_SUPPORT_COUNTRY'), $sql, null, true, $_GET["readO"]);
		$sql = "SELECT CULTURE_ID,CULTURE_LABEL \"lib\" FROM #pref#_boforms_culture order by CULTURE_LABEL";
		//$form .= $this->oForm->createComboFromSql($oConnection, "CULTURE_ID", t('BOFORMS_CULTURE'), $sql, null, true, $_GET["readO"]);
		$sql = "SELECT BRAND_ID, BRAND_KEY \"lib\" FROM #pref#_boforms_brand order by BRAND_KEY";
		$form .= $this->oForm->createComboFromSql($oConnection, "BRAND_ID", t('BOFORMS_BRAND_ID'), $sql, null, true, $_GET["readO"]);
		$form .= $this->stopStandardForm();
//$this->oForm->form_action = 'list2';
		$templateId = FunctionsUtils::getTemplateId('BoForms_Administration_DuplicationModule');
		$url_display_forms = "/_/Index/child?tid=" . $templateId . "&SCREEN=1";
		$html = '<script type="text/javascript">';
		$html .= "$( document ).ready(function() {
					// set top title
					window.parent.$('#frame_right_top').html('" . t('BOFORMS_DUPLICATIONMODULE') . "');
					$('#ALPHA2, #CULTURE_ID, #BRAND_ID').on('change', function() {
					if ($('#ALPHA2').val() != '' && $('#CULTURE_ID').val() != '' && $('#BRAND_ID').val() != '') {
					window.location = '$url_display_forms&step=listFormsGene&alpha2=' + $('#ALPHA2').val() + '&langueid=' + $('#LANGUE_ID').val() + '&brandid=' + $('#BRAND_ID').val();
					}
					});
					});";
		$html .= '</script>';
		$this->aButton["add"]="";
		$this->aButton["save"]="";
		$this->aButton["back"]="";
		Backoffice_Button_Helper::init($this->aButton);
// Zend_Form stop
		$form = formToString($this->oForm, $form);
		$this->setResponse($html . "<h2>" . t('BOFORMS_SEARCH_FORM_TO_DUPLICATE'). "</h2>" . $form);

	}

	public function initMasterAction(){
		$form = $this->startStandardForm();

		$sql = "SELECT BRAND_ID, BRAND_KEY \"lib\"  FROM #pref#_boforms_brand order by BRAND_KEY";
		$form .= $this->oForm->createComboFromSql($oConnection, "BRAND_ID", t('BOFORMS_BRAND_ID'), $sql, null, true, $_GET["readO"]);

		$form .= '<tr id="ligne_master_choose" style="display:none;"><td class="formlib">Master</td><td class="formval" id="td_master"></td></tr>';
		$form .= '<tr id="ligne_architecture_choose" style="display:none;"><td class="formlib">Architecture</td><td class="formval" id="td_architecture"></td></tr>';

		$form .= $this->stopStandardForm();

		$url_display_forms = "/_/Index/child?tid=" . FunctionsUtils::getTemplateId('BoForms_Administration_DuplicationModule') . "&SCREEN=1";

		$html = '<script type="text/javascript">';

		$html .= "$( document ).ready(function() {
				// set top title
				window.parent.$('#frame_right_top').html('" . t('BOFORMS_DUPLICATIONMODULE') . "');
				var masterCorresp = new Object();

				$('#BRAND_ID').on('change', function() {
					if ($('#BRAND_ID').val() != '') {
						$.post( '/_/module/boforms/BoForms_Administration_DuplicationModule/getMasters', { brand_id: $('#BRAND_ID').val() }).done(function( data ) {
							jsonResult = jQuery.parseJSON(data);
							if (jsonResult.resultat.statut == 'OK') {
								// 	now, creates the select
								var html_tmp = '<select size=\"1\" id=\"master_id\" name=\"master_id\" >';
								html_tmp += '<option value=\"\">-&gt; Choisissez</option>';
								for(var the_key in jsonResult.resultat.datas){
				    				html_tmp += '<option value=\"' + the_key + '\">' + the_key + '</option>';
				    				masterCorresp[the_key] = jsonResult.resultat.datas[the_key];
    							}
    							html_tmp += '</select>';

								$('#td_master').html(html_tmp);
								$('#ligne_master_choose').show();
							}
  						});
					}
				});

				$('#master_id').live('change', function()  {
					if ($('#master_id').val() != '') {
						var tmpArchitecture = masterCorresp[$('#master_id').val()];

						// now, creates the select
						var html_tmp = '<select size=\"1\" id=\"architecture_id\" name=\"architecture_id\" >';
						html_tmp += '<option value=\"\">-&gt; Choisissez</option>';
						for (i = 0; i < tmpArchitecture.length; i++) {
						    html_tmp += '<option value=\"' + tmpArchitecture[i] + '\">' + tmpArchitecture[i] + '</option>';
						}
						html_tmp += '</select>';

						$('#td_architecture').html(html_tmp);
						$('#ligne_architecture_choose').show();
					}
	     	     });

	     	     $('#architecture_id').live('change', function()  {
					if ($('#architecture_id').val() != '' && $('#master_id').val() != '') {
						window.location = '$url_display_forms&step=listForms&arch_id=' + $('#architecture_id').val() + '&master_id=' + $('#master_id').val() + '&brand_id=' + $('#BRAND_ID').val();
	     	      	}
	     	     });

    	     });";
		$html .= '</script>';

		$this->aButton["add"]="";
		$this->aButton["save"]="";
		$this->aButton["back"]="";
		Backoffice_Button_Helper::init($this->aButton);

		// Zend_Form stop
		$form = formToString($this->oForm, $form);
		$this->setResponse($html . "<h2>" . t('BOFORMS_SEARCH_FORM_TO_DUPLICATE'). "</h2>" . $form);
	}


	public function listFormsGeneAction() {

		$_SESSION[APP]['DUPLICATE_GENE']['BRAND'] = $this->getParam('brandid');
		$_SESSION[APP]['DUPLICATE_GENE']['COUNTRY'] = $this->getParam('alpha2');

		$instances = $this->getFormInstances($this->getParam('brandid'), $this->getParam('alpha2'), $this->getParam('filter_opportunite'));

			parent::listAction();
			$table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
			$table->setCSS(array(
				"tblalt1",
				"tblalt2"
			));

			$aRefFormType = $this->getReferential('FORM_TYPE','boforms_opportunite','OPPORTUNITE_KEY','OPPORTUNITE_KEY');

			//var_dump($this->getParams());

			$table->setFilterField("opportunite","<b>".t('BOFORMS_TYPE_FORMULAIRE')."&nbsp;:</b><br/>","FORM_TYPE",$aRefFormType);
			$table->setFilterField('');
			$table->setFilterField('');
			$table->setFilterField('');
			$table->setFilterField('');
			$table->getFilter(5);

			$table->aTableValues = $instances;
			$table->addInput('active', "checkbox", array("_javascript_" => "clickCheckboxEmpty", "_value_field_"=>"FORM_INCE","" => "", "param" => ""), "center");
			$table->addColumn('FORM_INCE', "FORM_INCE", "10", "left", "", "tblheader");
			$table->addColumn('FORM_NAME', "FORM_NAME", "50", "left", "", "tblheader");
			$table->addColumn('FORM_ID', "FORM_ID", "10", "left", "", "tblheader");
			$table->addColumn(t('BOFORMS_TYPE_FORMULAIRE'), "FORM_TYPE", "10", "left", "", "tblheader");

			/*$table->addInput(t('BOFORMS_DUPLICATE_ACTION'), "button", array(
				"id" => "FORM_INCE",
				"FORM_INCE" => "FORM_INCE",
				"FORM_NAME" => "FORM_NAME",
				"INSTANCE_NAME" => "INSTANCE_NAME",
				"FORM_ID" => "FORM_ID",
				"FORM_TYPE" => "FORM_TYPE",
				"CULTURE_ID" => "CULTURE_ID",
				"CULTURE_LABEL" => "CULTURE_LABEL",
				"" => "param=" . $this->getParam('brandid') . '_' . $this->getParam('alpha2')
			), "center");*/
			$this->aButton["add"] = "";
			Backoffice_Button_Helper::init($this->aButton);
			$html = '<script type="text/javascript">';
			$html .= "$( document ).ready(function() {
window.parent.$('#frame_right_top').html('" . t('BOFORMS_DUPLICATIONMODULE') . "');
});";
			$html .= "</script>";
			//$this->setResponse($table->getTable() . $html );

			$url_display_forms = "/_/Index/child?tid=" . FunctionsUtils::getTemplateId('BoForms_Administration_DuplicationModule') . "&SCREEN=1";


			$html = '<script type="text/javascript">';
			$html .= "var architectureId = '$architectureId';";
			$html .= "var masterId = '$masterId';";

			$html .= "function doDuplicateGeneMultiJS(list_ince) {
    				window.location = '$url_display_forms&step=beforeDuplicateGeneMulti&arch_id=' + architectureId + '&master_id=' + masterId +
	    			'&list_ince=' + decodeURI(list_ince);
    		}";

			$html .= "$( document ).ready(function() {
					window.parent.$('#frame_right_top').html('" . t('BOFORMS_DUPLICATIONMODULE') . "');
			     });";
			$html .= "</script>";

			$output = "";
			$output = $this->getView()->fetch( Pelican::$config["PLUGIN_ROOT"] . '/boforms/backend/views/scripts/BoForms/Administration/DuplicationModule/listFormsGene.tpl' , true);

			$this->setResponse($output . $table->getTable() . $html.$output );

	}

	private function getFormInstances($brandid, $alpha2, $filter_type = false) {
		$result = array();
		ini_set('default_socket_timeout', 60);
		$serviceParams = array(
			'country' => $alpha2,
			'brand' => $brandid
		);
		$service = \Itkg\Service\Factory::getService($brandid . '_SERVICE_BOFORMS', array());
		$response = $service->call('getInstances', $serviceParams);
		$tbl_culture = $this->getCultures();
//debug($response->instance);die;
		if(!empty($response->instance) && is_array($response->instance))
		{
			for ($i = 0; $i < count($response->instance); $i++) {

				if(9==substr($response->instance[$i]->instanceId,5,1))
				{
					if($filter_type)
					{
						if($filter_type == $response->instance[$i]->formType) {
							$result[$response->instance[$i]->instanceId] = array(
								'FORM_INCE' => $response->instance[$i]->instanceId,
								'FORM_NAME' => $response->instance[$i]->formName,
								'INSTANCE_NAME' => $response->instance[$i]->instanceName,
								'FORM_ID' => $response->instance[$i]->formId,
								'FORM_TYPE' => $response->instance[$i]->formType,
								'FORM_EDITABLE' => $response->instance[$i]->editable,
								'COMMENTARY' => $response->instance[$i]->instanceCommentary,
								'CULTURE_ID' => $response->instance[$i]->culture,
								'CULTURE_LABEL' => (isset($tbl_culture[0 + $response->instance[$i]->culture]) ? $tbl_culture[0 + $response->instance[$i]->culture] : $response->instance[$i]->culture)
							);
						}
					}else{
						$result[$response->instance[$i]->instanceId] = array(
							'FORM_INCE' => $response->instance[$i]->instanceId,
							'FORM_NAME' => $response->instance[$i]->formName,
							'INSTANCE_NAME' => $response->instance[$i]->instanceName,
							'FORM_ID' => $response->instance[$i]->formId,
							'FORM_TYPE' => $response->instance[$i]->formType,
							'FORM_EDITABLE' => $response->instance[$i]->editable,
							'COMMENTARY' => $response->instance[$i]->instanceCommentary,
							'CULTURE_ID' => $response->instance[$i]->culture,
							'CULTURE_LABEL' => (isset($tbl_culture[0 + $response->instance[$i]->culture]) ? $tbl_culture[0 + $response->instance[$i]->culture] : $response->instance[$i]->culture)
						);
					}


				}

			}
		}
		return $result;
	}

	private function getCultures() {
		$result = array();
		$oConnection = Pelican_Db::getInstance ();
		$tbl = $oConnection->queryTab("SELECT CULTURE_ID,CULTURE_LABEL from #pref#_boforms_culture");
		for ($i = 0; $i < count($tbl); $i++) {
			$result[$tbl[$i]['CULTURE_ID']] = $tbl[$i]['CULTURE_LABEL'];
		}
		return $result;
	}

	public function getReferential($typeRef, $table, $field_id, $field_lib)
	{
		$oConnection = Pelican_Db::getInstance ();
		$sSql="select $field_id id, $field_lib lib FROM #pref#_$table ORDER BY $field_id";
		$aRes = $oConnection->queryTab($sSql);
		/*if(is_array($aRes))
		{
			foreach ($aRes as $k=>$row)
			{
				$aRes[$k]['lib'] = t('BOFORMS_REFERENTIAL_'.$typeRef.'_'.$row['lib']);
			}
		}*/

		return $aRes;
	}

	public function listFormsAction() {
    	$architectureId = $this->getParam('arch_id');
    	$masterId = $this->getParam('master_id');
    	$brandId = $this->getParam('brand_id');
    	
    	$_SESSION[APP]['DUPLICATE_BRAND_ID'] = $brandId;
    	
    	$instances = $this->getInstancesByMasterWS($masterId, $architectureId, $brandId,$this->getParam('filter_opportunite'));


		parent::listAction();

		$table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
		$table->setCSS(array(
			"tblalt1",
			"tblalt2"
		));




		$aRefFormType = $this->getReferential('FORM_TYPE','boforms_opportunite','OPPORTUNITE_KEY','OPPORTUNITE_KEY');

		//var_dump($this->getParams());

		$table->setFilterField("opportunite","<b>".t('BOFORMS_TYPE_FORMULAIRE')."&nbsp;:</b><br/>","FORM_TYPE",$aRefFormType);
		$table->setFilterField('');
		$table->setFilterField('');
		$table->setFilterField('');
		$table->setFilterField('');
		$table->getFilter(5);

		$table->setValues($instances, "FORM_INCE"/*,"OPPORTUNITE_LABEL"*/);
		//$table->aTableValues = $instances;

		$table->addInput('active', "checkbox", array("_javascript_" => "clickCheckboxEmpty", "_value_field_"=>"FORM_INCE","" => "", "param" => ""), "center");


		$table->addColumn('Code', "FORM_INCE", "10", "left", "", "tblheader");
		$table->addColumn('FORM_NAME', "FORM_NAME", "50", "left", "", "tblheader");
		$table->addColumn('INSTANCE_NAME', "INSTANCE_NAME", "50", "left", "", "tblheader");
		$table->addColumn('FORM_ID', "FORM_ID", "10", "left", "", "tblheader");
		$table->addColumn(t('BOFORMS_TYPE_FORMULAIRE'), "FORM_TYPE", "10", "left", "", "tblheader");

		/*$table->addInput(t('BOFORMS_DUPLICATE_ACTION'), "button", array(
								"Code" => "FORM_INCE",
								"FORM_NAME" => "FORM_NAME",
								"INSTANCE_NAME" => "INSTANCE_NAME",
								"FORM_ID" => "FORM_ID",
								"FORM_TYPE" => "FORM_TYPE",
								'_javascript_' => 'doDuplicateJS'
								), "center");*/


		$this->aButton["add"] = "";
		Backoffice_Button_Helper::init($this->aButton);

		$url_display_forms = "/_/Index/child?tid=" . FunctionsUtils::getTemplateId('BoForms_Administration_DuplicationModule') . "&SCREEN=1";


		$html = '<script type="text/javascript">';
		$html .= "var architectureId = '$architectureId';";
		$html .= "var masterId = '$masterId';";
		$html .= "function doDuplicateJS(form_ince, form_name, instance_name, form_id, form_type) {
				window.location = '$url_display_forms&step=beforeDuplicate&arch_id=' + architectureId + '&master_id=' + masterId +
				'&form_ince=' + decodeURI(form_ince) +
				'&form_name=' + decodeURI(form_name) +
				'&instance_name=' + decodeURI(instance_name) +
				'&form_id=' + decodeURI(form_id) +
				'&form_type=' + decodeURI(form_type);
		}";

		$html .= "function doDuplicateMultiJS(list_ince) {
				window.location = '$url_display_forms&step=beforeDuplicateMulti&arch_id=' + architectureId + '&master_id=' + masterId +
				'&list_ince=' + decodeURI(list_ince);
		}";

		$html .= "$( document ).ready(function() {
				window.parent.$('#frame_right_top').html('" . t('BOFORMS_DUPLICATIONMODULE') . "');
			 });";
		$html .= "</script>";

		$output = "";
		$output = $this->getView()->fetch( Pelican::$config["PLUGIN_ROOT"] . '/boforms/backend/views/scripts/BoForms/Administration/DuplicationModule/listForms.tpl' , true);

		$this->setResponse( $output.$table->getTable() . $html .$output );

    }

    public function beforeDuplicateAction() {
    	$arch_id = $this->getParam("arch_id"); 
    	$master_id = $this->getParam("master_id");  
	    $form_ince = $this->getParam("form_ince"); 
	    $form_name = $this->getParam("form_name");
	    $instance_name = $this->getParam("instance_name");
	    $form_id = $this->getParam("form_id");
	    $form_type = $this->getParam("form_type");
    	
	    
	    $form = $this->startStandardForm();
	    
	    $form .= "<tr><td><h2 colspan='2'>" . t('BOFORMS_FORM_TO_DUPLICATE') . "</h2></td></tr>";

	    $form .= $this->oForm->createLabel('Master', $master_id);
	    $form .= $this->oForm->createLabel('Architecture', $arch_id);
	    
	    $form .= $this->oForm->createLabel('Code', $form_ince );
	    $form .= $this->oForm->createLabel('FORM_NAME', $form_name);
	    $form .= $this->oForm->createLabel('INSTANCE_NAME', $instance_name);
	    $form .= $this->oForm->createLabel('FORM_ID', $form_id);
	    $form .= $this->oForm->createLabel(t('BOFORMS_TYPE_FORMULAIRE'), $form_type);
	    
	    $form .= "<tr><td colspan='2'><h2 style=\"margin-top:30px;\">" . t('BOFORMS_LABEL_TARGET_FORM') . "</h2></td></tr>";
	    
	    $oConnection = Pelican_Db::getInstance ();

	    $sql = "SELECT BRAND_ID, BRAND_KEY \"lib\"  FROM #pref#_boforms_brand order by BRAND_KEY";
		$form .= $this->oForm->createComboFromSql($oConnection, "BRAND_ID_CIBLE", t('BOFORMS_BRAND_ID'), $sql, array($_SESSION[APP]['DUPLICATE_BRAND_ID']), true, true);
	    
	    // pays cible
	    /*$sql = "SELECT c.ALPHA2, c.nom_fr_fr \"lib\"  FROM #pref#_boforms_country c 
	    		inner join #pref#_site_code s on s.SITE_CODE_PAYS = c.ALPHA2
	    		where s.SITE_ID != 1 
	    		order by c.nom_fr_fr";*/
	    $sql = "SELECT c.ALPHA2, c.nom_fr_fr \"lib\"  FROM #pref#_boforms_country c
	    		
	    		order by c.nom_fr_fr";
		$form .= $this->oForm->createComboFromSql($oConnection, "ALPHA2", t('BOFORMS_SUPPORT_COUNTRY'), $sql, null, false, $_GET["readO"]);
	        
		$sql = "SELECT LANGUE_CODE id, LANGUE_LABEL lib from #pref#_language ";
		$form .= $this->oForm->createComboFromSql($oConnection, "langue_id", "Language", $sql, null, false, $_GET["readO"]);
		 
		// site cible
 		/*$site_id = substr($form_ince, 6,2);
 		$aBind[':FORMSITE_ID'] = 0 + $site_id;
 		var_dump($site_id);
 		$sql = "SELECT FORMSITE_KEY FROM #pref#_boforms_formulaire_site where formsite_id = :FORMSITE_ID";
 		$site_label = t('BOFORMS_FORMSITE_LABEL_' . $oConnection->queryItem($sql, $aBind));
 		
 		$form .= $this->oForm->createLabel("Site", $site_label);
 		$form .= $this->oForm->createHidden("site_id", $site_id);*/
	    
 		// culture 
 		$form .= '<tr id="ligne_language_choose" ><td class="formlib">Language</td><td class="formval" id="td_language"></td></tr>';
		
 		$form .= '<tr><td><input type="button" name="Dupliquer" value="' . t('BOFORMS_DUPLICATE_ACTION') . '" id="btn_duplicate" class="button"/></td><td>&nbsp;</td></tr>';
 		
	    $form .= $this->stopStandardForm();
			
		$templateId = FunctionsUtils::getTemplateId('BoForms_Administration_DuplicationModule');
		$url_display_forms = "/_/Index/child?tid=" . $templateId . "&SCREEN=1";

		/*$sql = "select distinct sc.SITE_CODE_PAYS, l.LANGUE_CODE, l.LANGUE_LABEL from #pref#_site s 
				inner join #pref#_site_language s2 on s.SITE_ID = s2.SITE_ID
				inner join #pref#_language l on l.LANGUE_ID = s2.LANGUE_ID 
				inner join #pref#_site_code sc on sc.SITE_ID = s.SITE_ID
				where s.SITE_ID != 1 order by SITE_CODE_PAYS, s.SITE_ID";
		$tabResult = $oConnection->queryTab($sql);
		$tabCountry = array();
		for ($i = 0; $i < count($tabResult); $i++) {
			$tabCountry[strtoupper($tabResult[$i]['SITE_CODE_PAYS'])][$tabResult[$i]['LANGUE_CODE']] = $tabResult[$i]['LANGUE_LABEL']; 
		}*/
				
		$html = '<script type="text/javascript">';
    	
		/*$html .= 'var countryCorresp = new Object();';
     	foreach ($tabCountry as $code_pays => $values) {
	    	$html .= "var tmpList = new Object();";
     		foreach($values as $langue_code => $langue_label) {
				$html .= "tmpList['$langue_code'] = \"$langue_label\";";	    		
	    	}
	    	$html .= "countryCorresp['$code_pays'] = tmpList;";
	    }*/

		$html .= "$( document ).ready(function() {
					// 	set top title
					window.parent.$('#frame_right_top').html('" . t('BOFORMS_DUPLICATIONMODULE') . "');
					$('#ligne_language_choose').hide();
					$('#btn_duplicate').hide();

					function showHideDuplicate() {
						if ($('#ALPHA2').val() != '' && $('#site_id').val() != '' && $('#langue_id').val() != '') {
	     	     	 		$('#btn_duplicate').show();
	     	     	 	} else {
	     	     	 		$('#btn_duplicate').hide();
	     	     	 	}
					}

					$('#ALPHA2').on('change', function()  {
						if ($('#ALPHA2').val() != '') {

						}

						showHideDuplicate();
			   	     });

			   	     $('#site_id').live('change', function() {
			   	     	showHideDuplicate();
			   	     });

	     	     	 $('#langue_id').live('change', function() {
	     	     	 	showHideDuplicate();
	     	     	 });

	     	     	 $('#btn_duplicate').live('click', function() {
	     	     	 	if ($('#ALPHA2').val() != '' && $('#site_id').val() != '' && $('#langue_id').val() != '') {
	     	     	  		if (confirm('" . t('BOFORMS_LABEL_CONFIRM_DUPLICATE') . "')) {
	     	     	 			window.location = '$url_display_forms&step=duplicate&form_ince=$form_ince' + '&site_id=' + $('#site_id').val() + '&country=' + $('#ALPHA2').val() + '&language=' + $('#langue_id').val() + '&brand_id_cible=' + $(\"input[name='BRAND_ID_CIBLE']\").val();
	     	     	 		}
	     	     	 	}
	     	     	 });

	    	     });";
		
		$html .= '</script>';
					
		$this->aButton["add"]="";
		$this->aButton["save"]="";
		$this->aButton["back"]="";
		Backoffice_Button_Helper::init($this->aButton);
			
		// Zend_Form stop
		$form = formToString($this->oForm, $form);
		$this->setResponse($html . $form);
	}

	public function beforeDuplicateGeneMultiAction() {


		$listInce = $this->getParam("list_ince");


		if(!empty($listInce)){
			$masters = explode("_", $listInce);
			$listBR = implode("<br />",$masters);
		}

		$form = $this->startStandardForm();

		$form .= "<tr><td><h2 colspan='2'>" . t('BOFORMS_FORM_TO_DUPLICATE') . "</h2></td></tr>";

		$form .= $this->oForm->createLabel('Liste des génériques à dupliquer', $listBR );


		$form .= "<tr><td colspan='2'><h2 style=\"margin-top:30px;\">" . t('BOFORMS_LABEL_TARGET_FORM') . "</h2></td></tr>";

		$oConnection = Pelican_Db::getInstance ();

		$sql = "SELECT BRAND_ID, BRAND_KEY \"lib\"  FROM #pref#_boforms_brand order by BRAND_KEY";
		$form .= $this->oForm->createComboFromSql($oConnection, "BRAND_ID_CIBLE", t('BOFORMS_BRAND_ID'), $sql, array(Pelican::$config['BOFORMS_BRAND_ID']), true, true);

		// pays cible

		$sql = "SELECT c.ALPHA2, c.nom_fr_fr \"lib\"  FROM #pref#_boforms_country c

	    		order by c.nom_fr_fr";
		$form .= $this->oForm->createComboFromSql($oConnection, "ALPHA2", t('BOFORMS_SUPPORT_COUNTRY'), $sql, null, false, $_GET["readO"]);

		$sql = "SELECT LANGUE_CODE id, LANGUE_LABEL lib from #pref#_language ";
		$form .= $this->oForm->createComboFromSql($oConnection, "langue_id", "Language par défaut", $sql, null, false, $_GET["readO"]);


		// culture
		$form .= '<tr id="ligne_language_choose" ><td class="formlib">Language</td><td class="formval" id="td_language"></td></tr>';

		$form .= '<tr><td><input type="button" name="Dupliquer" value="' . t('BOFORMS_DUPLICATE_ACTION') . '" id="btn_duplicate" class="button"/></td><td>&nbsp;</td></tr>';

		$form .= $this->stopStandardForm();

		$templateId = FunctionsUtils::getTemplateId('BoForms_Administration_DuplicationModule');
		$url_display_forms = "/_/Index/child?tid=" . $templateId . "&SCREEN=1";


		$html = '<script type="text/javascript">';



		$html .= "$( document ).ready(function() {
					// 	set top title
					window.parent.$('#frame_right_top').html('" . t('BOFORMS_DUPLICATIONMODULE') . "');
					$('#ligne_language_choose').hide();
					$('#btn_duplicate').hide();

					function showHideDuplicate() {
						if ($('#ALPHA2').val() != '' && $('#site_id').val() != '' && $('#langue_id').val() != '') {
	     	     	 		$('#btn_duplicate').show();
	     	     	 	} else {
	     	     	 		$('#btn_duplicate').hide();
	     	     	 	}
					}

					$('#ALPHA2').on('change', function()  {
						if ($('#ALPHA2').val() != '') {

						}

						showHideDuplicate();
			   	     });

			   	     $('#site_id').live('change', function() {
			   	     	showHideDuplicate();
			   	     });

	     	     	 $('#langue_id').live('change', function() {
	     	     	 	showHideDuplicate();
	     	     	 });

	     	     	 $('#btn_duplicate').live('click', function() {
	     	     	 	if ($('#ALPHA2').val() != '' && $('#site_id').val() != '' && $('#langue_id').val() != '') {
	     	     	  		if (confirm('" . t('BOFORMS_LABEL_CONFIRM_DUPLICATE') . "')) {
	     	     	 			window.location = '$url_display_forms&step=duplicateGeneMulti&list_ince=$listInce' + '&site_id=' + $('#site_id').val() + '&country=' + $('#ALPHA2').val() + '&language=' + $('#langue_id').val() + '&brand_id_cible=' + $(\"input[name='BRAND_ID_CIBLE']\").val();
	     	     	 		}
	     	     	 	}
	     	     	 });

	    	     });";

		$html .= '</script>';

		$this->aButton["add"]="";
		$this->aButton["save"]="";
		$this->aButton["back"]="";
		Backoffice_Button_Helper::init($this->aButton);

		// Zend_Form stop
		$form = formToString($this->oForm, $form);
		$this->setResponse($html . $form);

	}

	public function beforeDuplicateMultiAction() {
		$arch_id = $this->getParam("arch_id");
		$master_id = $this->getParam("master_id");
		$listInce = $this->getParam("list_ince");

		if(!empty($listInce)){
			$masters = explode("_", $listInce);
			$listBR = implode("<br />",$masters);
		}

		$form = $this->startStandardForm();

		$form .= "<tr><td><h2 colspan='2'>" . t('BOFORMS_FORM_TO_DUPLICATE') . "</h2></td></tr>";

		$form .= $this->oForm->createLabel('Master', $master_id);
		$form .= $this->oForm->createLabel('Architecture', $arch_id);

		$form .= $this->oForm->createLabel('Liste des masters à dupliquer', $listBR );


		$form .= "<tr><td colspan='2'><h2 style=\"margin-top:30px;\">" . t('BOFORMS_LABEL_TARGET_FORM') . "</h2></td></tr>";

		$oConnection = Pelican_Db::getInstance ();

		$sql = "SELECT BRAND_ID, BRAND_KEY \"lib\"  FROM #pref#_boforms_brand order by BRAND_KEY";
		$form .= $this->oForm->createComboFromSql($oConnection, "BRAND_ID_CIBLE", t('BOFORMS_BRAND_ID'), $sql, array($_SESSION[APP]['DUPLICATE_BRAND_ID']), true, true);

		// pays cible
		/*$sql = "SELECT c.ALPHA2, c.nom_fr_fr \"lib\"  FROM #pref#_boforms_country c
                inner join #pref#_site_code s on s.SITE_CODE_PAYS = c.ALPHA2
                where s.SITE_ID != 1
                order by c.nom_fr_fr";*/
		$sql = "SELECT c.ALPHA2, c.nom_fr_fr \"lib\"  FROM #pref#_boforms_country c

	    		order by c.nom_fr_fr";
		$form .= $this->oForm->createComboFromSql($oConnection, "ALPHA2", t('BOFORMS_SUPPORT_COUNTRY'), $sql, null, false, $_GET["readO"]);

		$sql = "SELECT LANGUE_CODE id, LANGUE_LABEL lib from #pref#_language ";
		$form .= $this->oForm->createComboFromSql($oConnection, "langue_id", "Language", $sql, null, false, $_GET["readO"]);

		// site cible
		/*$site_id = substr($form_ince, 6,2);
        $aBind[':FORMSITE_ID'] = 0 + $site_id;
        var_dump($site_id);
        $sql = "SELECT FORMSITE_KEY FROM #pref#_boforms_formulaire_site where formsite_id = :FORMSITE_ID";
        $site_label = t('BOFORMS_FORMSITE_LABEL_' . $oConnection->queryItem($sql, $aBind));

        $form .= $this->oForm->createLabel("Site", $site_label);
        $form .= $this->oForm->createHidden("site_id", $site_id);*/

		// culture
		$form .= '<tr id="ligne_language_choose" ><td class="formlib">Language</td><td class="formval" id="td_language"></td></tr>';

		$form .= '<tr><td><input type="button" name="Dupliquer" value="' . t('BOFORMS_DUPLICATE_ACTION') . '" id="btn_duplicate" class="button"/></td><td>&nbsp;</td></tr>';

		$form .= $this->stopStandardForm();

		$templateId = FunctionsUtils::getTemplateId('BoForms_Administration_DuplicationModule');
		$url_display_forms = "/_/Index/child?tid=" . $templateId . "&SCREEN=1";

		/*$sql = "select distinct sc.SITE_CODE_PAYS, l.LANGUE_CODE, l.LANGUE_LABEL from #pref#_site s
				inner join #pref#_site_language s2 on s.SITE_ID = s2.SITE_ID
				inner join #pref#_language l on l.LANGUE_ID = s2.LANGUE_ID
				inner join #pref#_site_code sc on sc.SITE_ID = s.SITE_ID
				where s.SITE_ID != 1 order by SITE_CODE_PAYS, s.SITE_ID";
		$tabResult = $oConnection->queryTab($sql);
		$tabCountry = array();
		for ($i = 0; $i < count($tabResult); $i++) {
			$tabCountry[strtoupper($tabResult[$i]['SITE_CODE_PAYS'])][$tabResult[$i]['LANGUE_CODE']] = $tabResult[$i]['LANGUE_LABEL'];
		}*/

		$html = '<script type="text/javascript">';

		/*$html .= 'var countryCorresp = new Object();';
     	foreach ($tabCountry as $code_pays => $values) {
	    	$html .= "var tmpList = new Object();";
     		foreach($values as $langue_code => $langue_label) {
				$html .= "tmpList['$langue_code'] = \"$langue_label\";";
	    	}
	    	$html .= "countryCorresp['$code_pays'] = tmpList;";
	    }*/

		$html .= "$( document ).ready(function() {
					// 	set top title
					window.parent.$('#frame_right_top').html('" . t('BOFORMS_DUPLICATIONMODULE') . "');
					$('#ligne_language_choose').hide();
					$('#btn_duplicate').hide();

					function showHideDuplicate() {
						if ($('#ALPHA2').val() != '' && $('#site_id').val() != '' && $('#langue_id').val() != '') {
	     	     	 		$('#btn_duplicate').show();
	     	     	 	} else {
	     	     	 		$('#btn_duplicate').hide();
	     	     	 	}
					}

					$('#ALPHA2').on('change', function()  {
						if ($('#ALPHA2').val() != '') {

						}

						showHideDuplicate();
			   	     });

			   	     $('#site_id').live('change', function() {
			   	     	showHideDuplicate();
			   	     });

	     	     	 $('#langue_id').live('change', function() {
	     	     	 	showHideDuplicate();
	     	     	 });

	     	     	 $('#btn_duplicate').live('click', function() {
	     	     	 	if ($('#ALPHA2').val() != '' && $('#site_id').val() != '' && $('#langue_id').val() != '') {
	     	     	  		if (confirm('" . t('BOFORMS_LABEL_CONFIRM_DUPLICATE') . "')) {
	     	     	 			window.location = '$url_display_forms&step=duplicateMulti&list_ince=$listInce' + '&site_id=' + $('#site_id').val() + '&country=' + $('#ALPHA2').val() + '&language=' + $('#langue_id').val() + '&brand_id_cible=' + $(\"input[name='BRAND_ID_CIBLE']\").val();
	     	     	 		}
	     	     	 	}
	     	     	 });

	    	     });";

		$html .= '</script>';

		$this->aButton["add"]="";
		$this->aButton["save"]="";
		$this->aButton["back"]="";
		Backoffice_Button_Helper::init($this->aButton);

		// Zend_Form stop
		$form = formToString($this->oForm, $form);
		$this->setResponse($html . $form);
	}
    
    public function duplicateAction() {
    	// plus appelé à priori
    	$brand_cible = $this->getParam('brand_id_cible');
    	$form_ince = $this->getParam('form_ince');
    	$site_id = $this->getParam('site_id');
    	$country = $this->getParam('country');
    	$language = strtoupper($this->getParam('language'));
    	
    	// gets site title from site_id
    	$oConnection = Pelican_Db::getInstance ();
    	$aBind[':SITE_ID'] = 0 + $site_id;
    	$site_title = t('BOFORMS_FORMSITE_LABEL_' . $oConnection->queryItem('select FORMSITE_KEY from #pref#_boforms_formulaire_site where FORMSITE_ID = :SITE_ID', $aBind));
    	
    	$form = $this->startStandardForm();
	    
 		$form .= "<tr><td colspan='2'><h2>" . t('BOFORMS_FORM_DUPLICATION_PARAMETERS') . "</h2></td></tr>";
    	 
	    $form .= $this->oForm->createLabel('Code', $form_ince );
	    //$form .= $this->oForm->createLabel('SITE_TITLE', $site_title);
	    $form .= $this->oForm->createLabel('COUNTRY', $country);
	    $form .= $this->oForm->createLabel('LANGUAGE', strtolower($language));
	    
	    $form .= "<tr><td colspan='2'><h2 style=\"margin-top:30px;\">" . t('BOFORMS_DUPLICATE_RESULT') . "</h2></td></tr>";
	        	
    	$response = $this->duplicateInstanceWS($form_ince, $country, strtoupper($language), $brand_cible);

    	$templateId = FunctionsUtils::getTemplateId('BoForms_Administration_DuplicationModule');
		$url_display_forms = "/_/Index/child?tid=" . $templateId . "&SCREEN=1";
    	    	
    	if ($response->statusResponse == 'OK') {
			$result =  $response->statusResponse ;   		
    	} else if ($response->statusResponse == 'INSTANCE_NAME_ALREADY_EXIST' || 
	    	$response->statusResponse == 'FORM_NAME_ALREADY_EXIST' ||
			$response->statusResponse == 'XSD_VALIDATION_FAILED' ||
			$response->statusResponse == 'NULL_NOT_PERMITTED' ||
			$response->statusResponse == 'OK_LACK_HIDDEN_DATA' ||
			$response->statusResponse == 'OK_LACK_LABEL_DATA' ||
			$response->statusResponse == 'OK_LACK_REFERENTIAL_DATA' ||
			$response->statusResponse == 'OK_LACK_REF_LABEL_DATA') {
    		$result = t('BOFORMS_DUPL_INSTANCE' . '_' . $response->statusResponse);
    	} else {
    	 	$result = $response->statusResponse;
    	}
    	
    	$form .= $this->oForm->createLabel(t('BOFORMS_LABEL_RESULT'), $result);
    	if ($response->statusResponse == 'OK') {
    		$form .= $this->oForm->createLabel('Code', $response->instanceId);
    	}			
    	
    	$form .= '<tr><td><input type="button" style="margin-top:50px;" name="Back" value="' . t('BOFORMS_DUPLICATE_OTHER_FORM'). '" id="btn_back" class="button"/></td><td>&nbsp;</td></tr>';
 		
    	$html = '<script type="text/javascript">';
    	
    	$html .= "$( document ).ready(function() {
    				$('#btn_back').live('click', function() {
	     	     	 	window.location = '$url_display_forms';     	     	 			
	     	     	 });
		});";
		
		$html .= '</script>';
    	
		$this->aButton["add"]="";
		$this->aButton["save"]="";
		$this->aButton["back"]="";
		Backoffice_Button_Helper::init($this->aButton);

		// Zend_Form stop
		$form = formToString($this->oForm, $form);
		$this->setResponse($html . $form);
    	
    }

	public function duplicateMultiAction()
	{
		
		// appelé quand on choisit duplication from master
		$brand_cible = $this->getParam('brand_id_cible');
		$listInce = $this->getParam('list_ince');
		$site_id = $this->getParam('site_id');
		$country = $this->getParam('country');
		$language = strtoupper($this->getParam('language'));


		if (!empty($listInce)) {
			$masters = explode("_", $listInce);
			$listBR = implode("<br />", $masters);
		} else {
			die('No master selected');
		}

		// gets site title from site_id
		$oConnection = Pelican_Db::getInstance();
		$aBind[':SITE_ID'] = 0 + $site_id;
		$site_title = t('BOFORMS_FORMSITE_LABEL_' . $oConnection->queryItem('select FORMSITE_KEY from #pref#_boforms_formulaire_site where FORMSITE_ID = :SITE_ID', $aBind));

		$form = $this->startStandardForm();

		$form .= "<tr><td colspan='2'><h2>" . t('BOFORMS_FORM_DUPLICATION_PARAMETERS') . "</h2></td></tr>";

		$form .= $this->oForm->createLabel('Brand', $brand_cible);
		//$form .= $this->oForm->createLabel('SITE_TITLE', $site_title);
		$form .= $this->oForm->createLabel('COUNTRY', $country);
		$form .= $this->oForm->createLabel('LANGUAGE', strtolower($language));

		$form .= $this->oForm->createLabel('Masters', $listBR);

		$form .= "<tr><td colspan='2'><h2 style=\"margin-top:30px;\">" . t('BOFORMS_DUPLICATE_RESULT') . "</h2></td></tr>";

		$templateId = FunctionsUtils::getTemplateId('BoForms_Administration_DuplicationModule');
		$url_display_forms = "/_/Index/child?tid=" . $templateId . "&SCREEN=1";
		
		foreach ($masters as $master) {
			$response = $this->duplicateInstanceWS($master, $country, strtoupper($language), $brand_cible);

			if ($response->statusResponse == 'OK') {
				$result = $response->statusResponse;
			} else if ($response->statusResponse == 'INSTANCE_NAME_ALREADY_EXIST' ||
				$response->statusResponse == 'FORM_NAME_ALREADY_EXIST' ||
				$response->statusResponse == 'XSD_VALIDATION_FAILED' ||
				$response->statusResponse == 'NULL_NOT_PERMITTED' ||
				$response->statusResponse == 'OK_LACK_HIDDEN_DATA' ||
				$response->statusResponse == 'OK_LACK_LABEL_DATA' ||
				$response->statusResponse == 'OK_LACK_REFERENTIAL_DATA' ||
				$response->statusResponse == 'OK_LACK_REF_LABEL_DATA'
			) {
				$result = "<span style='color:red'>".t('BOFORMS_DUPL_INSTANCE' . '_' . $response->statusResponse)."</span>";
			} else {
				$result = "<span style='color:red'>".$response->statusResponse."</span>";
			}

			$form .= $this->oForm->createLabel(t('BOFORMS_LABEL_RESULT') ." ".$master ." :" , $result);
			if ($response->statusResponse == 'OK') {
				$form .= $this->oForm->createLabel('Code', $response->instanceId);
			}
		}
		$form .= '<tr><td><input type="button" style="margin-top:50px;" name="Back" value="' . t('BOFORMS_DUPLICATE_OTHER_FORM'). '" id="btn_back" class="button"/></td><td>&nbsp;</td></tr>';

		$html = '<script type="text/javascript">';

		$html .= "$( document ).ready(function() {
    				$('#btn_back').live('click', function() {
	     	     	 	window.location = '$url_display_forms';
	     	     	 });
		});";

		$html .= '</script>';

		$this->aButton["add"]="";
		$this->aButton["save"]="";
		$this->aButton["back"]="";
		Backoffice_Button_Helper::init($this->aButton);

		// Zend_Form stop
		$form = formToString($this->oForm, $form);
		$this->setResponse($html . $form);

	}

	public function duplicateGeneMultiAction()
	{
		// appelé quand on choisit duplication from generique
		
		$brand_source = $_SESSION[APP]['DUPLICATE_GENE']['BRAND'];
		$country_source = $_SESSION[APP]['DUPLICATE_GENE']['COUNTRY'];


		$brand_cible = $this->getParam('brand_id_cible');
		$listInce = $this->getParam('list_ince');
		$site_id = $this->getParam('site_id');
		$country_cible = $this->getParam('country');
		$langue_cible = $this->getParam('language');

		//var_dump($this->getParams());


		if (!empty($listInce)) {
			$masters = explode("_", $listInce);
			$generics = explode("_", $listInce);
			$listBR = implode("<br />", $masters);
		} else {
			die('No generic selected');
		}

		$sourceAllInstances = $this->getFormInstances($brand_source,$country_source);

		$oConnection = Pelican_Db::getInstance();
		$aBind[':SITE_ID'] = 0 + $site_id;
		$site_title = t('BOFORMS_FORMSITE_LABEL_' . $oConnection->queryItem('select FORMSITE_KEY from #pref#_boforms_formulaire_site where FORMSITE_ID = :SITE_ID', $aBind));

		$form = $this->startStandardForm();

		$form .= "<tr><td colspan='2'><h2>" . t('BOFORMS_FORM_DUPLICATION_PARAMETERS') . "</h2></td></tr>";

		$form .= $this->oForm->createLabel('Brand', $brand_cible);
		//$form .= $this->oForm->createLabel('SITE_TITLE', $site_title);
		$form .= $this->oForm->createLabel('COUNTRY', $country_cible);
		$form .= $this->oForm->createLabel('LANGUAGE', strtolower($langue_cible));

		$form .= $this->oForm->createLabel('Generiques', $listBR);

		$form .= "<tr><td colspan='2'><h2 style=\"margin-top:30px;\">" . t('BOFORMS_DUPLICATE_RESULT') . "</h2></td></tr>";

		$templateId = FunctionsUtils::getTemplateId('BoForms_Administration_DuplicationModule');
		$url_display_forms = "/_/Index/child?tid=" . $templateId . "&SCREEN=1";
				
		foreach ($generics as $code_source)
		{
			$response = $this->duplicateInstanceWS($code_source, $country_cible, strtoupper($langue_cible), $brand_cible);
			
			if ($response->statusResponse == 'OK') {
				$result = $response->statusResponse;
			} else if ($response->statusResponse == 'INSTANCE_NAME_ALREADY_EXIST' ||
				$response->statusResponse == 'FORM_NAME_ALREADY_EXIST' ||
				$response->statusResponse == 'XSD_VALIDATION_FAILED' ||
				$response->statusResponse == 'NULL_NOT_PERMITTED'
			) {
				$result = "<span style='color:red'>".t('BOFORMS_DUPL_INSTANCE' . '_' . $response->statusResponse)."</span>";
			} else {
				$result = "<span style='color:red'>".$response->statusResponse."</span>";
			}

			$form .= $this->oForm->createLabel(t('BOFORMS_LABEL_RESULT') ." ".$code_source ." :" , $result);
			if ($response->statusResponse == 'OK') {
				$form .= $this->oForm->createLabel('Code', $response->instanceId);
			}    		
		}

		$form .= '<tr><td><input type="button" style="margin-top:50px;" name="Back" value="' . t('BOFORMS_DUPLICATE_OTHER_FORM'). '" id="btn_back" class="button"/></td><td>&nbsp;</td></tr>';

		$html = '<script type="text/javascript">';

		$html .= "$( document ).ready(function() {
    				$('#btn_back').live('click', function() {
	     	     	 	window.location = '$url_display_forms';
	     	     	 });
		});";

		$html .= '</script>';

		$this->aButton["add"]="";
		$this->aButton["save"]="";
		$this->aButton["back"]="";
		Backoffice_Button_Helper::init($this->aButton);

		// Zend_Form stop
		$form = formToString($this->oForm, $form);
		$this->setResponse($html . $form);
	}
    
     /**
     * Récupere les masters pour une marque
     */
    public function getMastersAction()
    {
    	$brand_id = $this->getParam('brand_id');
    	
    	try {
    		$serviceParams = array(
    			'brand' => $brand_id
    		);
    		
    		$service = \Itkg\Service\Factory::getService($brand_id . '_SERVICE_BOFORMS', array());
    		
    		$response = $service->call('getMasters', $serviceParams);
    		if ($response->statusResponse != 'OK') {
    			echo '{"resultat": {"statut": "' . $response->statusResponse . '"}}';
    			exit(1);
    		}
    		
    		$masters = $response->masters->master;
    		
    		$tblMasters = array();
    		for ($i = 0; $i < count($masters); $i++) {
    			$master = $masters[$i];
    			$tblMasters[$master->language] = $master->architectureList->architecture;
    		}
    		
    		echo '{"resultat": {"statut": "OK", "datas": ' .  json_encode($tblMasters) . ' } }';
    		exit(1);
    	} catch(\Exception $e) {
    		echo $e->getMessage();
    		exit(1);
    	}
    }

    
    /**
    * Récupere les instances de formulaire 
    * @param string $master_id
    * @param string $arhictecture_id
    * 
    */
    private function getInstancesByMasterWS($masterId, $architectureId, $brandId, $filter_type = false)
    {
    	try {
    		$serviceParams = array(
    				'brand' => $brandId,
    				'master' => $masterId,
    				'architecture' => $architectureId
    		);
    		     		
    		$service = \Itkg\Service\Factory::getService($brandId . '_SERVICE_BOFORMS', array());
    		 
    		$response = $service->call('getInstancesByMaster', $serviceParams);
    		
    		if ($response->statusResponse != 'OK') {
    			echo "parameters: <br/>";
    			echo "<pre>";
    			print_r($serviceParams);
    			echo "</pre>";
    			die('error calling web service getInstancesByMaster');
    		}
    		
    		$instances = $response->instanceMasterList->instanceMaster;
    		
    		$tblInstances = array();
    		for ($i = 0; $i < count($instances); $i++) {

				if($filter_type)
				{
					if($filter_type == $instances[$i]->formType)
					{
						$tblInstances[] = array('FORM_INCE' => $instances[$i]->instanceId,
							'INSTANCE_NAME' => $instances[$i]->instanceName,
							'FORM_ID' => $instances[$i]->formId,
							'FORM_NAME' => $instances[$i]->formName,
							'FORM_TYPE' => $instances[$i]->formType
						);
					}

				}else{
					$tblInstances[] = array('FORM_INCE' => $instances[$i]->instanceId,
						'INSTANCE_NAME' => $instances[$i]->instanceName,
						'FORM_ID' => $instances[$i]->formId,
						'FORM_NAME' => $instances[$i]->formName,
						'FORM_TYPE' => $instances[$i]->formType
					);
				}


    		}
    		
    		return $tblInstances;
    	} catch(\Exception $e) {
    		echo $e->getMessage();
    		return -1;
    	}
    }
    
    private function duplicateInstanceWS($masterId, $country, $language, $brand_cible) {
    	try {
    		
    		$aCode['AC'] = 'CPP';
    		$aCode['AP'] = 'NDP';
    		$aCode['DS'] = 'DSPP';
    		
    		$applicationCode = $aCode[$brand_cible];
    		
    		$serviceParams = array(
    				'masterId' => $masterId,
    				'country' => strtoupper($country),
    				'language' => strtolower($language),
    				'siteId' => $siteId,
    				'applicationCode' => $applicationCode
    		);

    		$service = \Itkg\Service\Factory::getService($brand_cible . '_SERVICE_BOFORMS', array());
    		 
    		$response = $service->call('duplicateInstance', $serviceParams);
    		    		
    		return $response;
    	} catch(\Exception $e) {
    		echo $e->getMessage();
    		return -1;
    	}
	
    }

	public function getInstanceWS($brand,$code_instance)
	{
		try {
			$serviceParams = array(
				'instanceId' => $code_instance
			);

			$service = \Itkg\Service\Factory::getService($brand . '_SERVICE_BOFORMS', array());

			$response = $service->call('getInstanceById', $serviceParams);

			if($response)
			{
				$response = str_replace("&lt;", "<", $response);
				$response = str_replace("&gt;", ">", $response);
				$response = str_replace("&quot;", '"', $response);
			}

			return $response;
		} catch(\Exception $e) {
			echo $e->getMessage();
		}
	}

	public function updateWS($brand ,$instanceId, $instanceName, $formId, $formName, $formType, $instanceXML,$editable=false, $comment=false, $errordie = true)
	{

		try {
			//$FormTypeKey=$this->getFormType($formType);
			$FormTypeKey=$formType;


			//prépare la chaine
			$instanceXML = str_replace('<', '&lt;', $instanceXML);
			$instanceXML = str_replace('>', '&gt;', $instanceXML);
			$instanceXML = str_replace('"', '&quot;', $instanceXML);


			$serviceParams = array(
				'instanceId' => $instanceId,
				'instanceName' => $instanceName,
				'formId' => $formId,
				'formName' => $formName,
				'formType' => $FormTypeKey,
				'instanceXML' => $instanceXML,
				'editable' => $editable,
				'comment' => $comment
			);


			//debug($serviceParams);

			ini_set('default_socket_timeout', 60);

			$service = \Itkg\Service\Factory::getService($brand . '_SERVICE_BOFORMS', array());

			$response = $service->call('updateInstance', $serviceParams);
			//$response = 'ERROR BLALBAL';

			return $response;



		} catch(\Exception $e) {

			return $e->getMessage();
		}
	}

   /* 
 	private function clearCacheInstance($instanceId)
   	{   		
   		$url=Pelican::$config['BOFORMS_URL_CLEARCACHE']."/cache/clearinstance?instanceid=$instanceId&key=".Pelican::$config['BOFORMS_URL_CLEARCACHE_KEY'];
   		// Tableau contenant les options de téléchargement
   		$options=array(
   				CURLOPT_URL            => $url, // Url cible 
   				CURLOPT_RETURNTRANSFER => true, // Retourner le contenu téléchargé dans une chaine (au lieu de l'afficher directement)
   				CURLOPT_HEADER         => false // Ne pas inclure l'entête de réponse du serveur dans la chaine retournée
   		);
   		
   		//////////
   		
   		$CURL=curl_init();
   		curl_setopt_array($CURL,$options);
   		// Exécution de la requête
   		$content=curl_exec($CURL); 
   		//var_dump($content);
   		// Fermeture de la session cURL
   		curl_close($CURL);
   	}
	*/   	
}