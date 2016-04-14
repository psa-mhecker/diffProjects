<?php
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local.ini.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/BoForms.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FormInstance.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/XMLHandle.class.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/JiraUtil.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FunctionsUtils.php');
//ajout du fichier de conf administrable en BO
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.admin.ini.php');

// Etat détaillé d'un formulaire
class BoForms_Administration_ReportingStatusForms_Controller extends Pelican_Controller_Back
{
	protected $administration = true;
	
	protected $oXMLOriginal; // objet XMLHandle du formulaire avant modification
    
    protected $oXMLGeneric; // objet XMLHandle du formulaire générique
    protected $get_instance;
   
    protected $bDraft = false;
    protected $bDraftAuto = false;

    protected $form_name = "boforms_etat_forms_pays";

    protected $aOpportuniteExclues = array("'CLAIMS_ABOUT_YOUR_CAR'", "'CLAIMS_ABOUT_YOUR_DEALER'", "'CLAIMS_ABOUT_DOCUMENTATION'", "'CLAIMS_OTHER'"); //JIRA 307
    
    private function setTypeExclude($country_code, $form_site_id)
    {
    	if (FunctionsUtils::isLandingPageSite($form_site_id)) {
    		if ($country_code!='FR')	{
    			$this->aOpportuniteExclues[] = "'SUBSCRIBE_NEWSLETTER'";
    			$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER'";
    		}
    		$this->aOpportuniteExclues[] = "'REQUEST_A_CONTACT_BUSINESS'";
    		$this->aOpportuniteExclues[] = "'CLAIMS'";
    		$this->aOpportuniteExclues[] = "'REQUEST_AN_INFORMATIONS'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_AMEX'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_EMAILING'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_CREDIPAR'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_B2B'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_CNIL'";
    		return;
    	} else {
    		$this->aOpportuniteExclues[] = "'LANDING_PAGE'";
	    	$this->aOpportuniteExclues[] = "'LANDING_PAGE_1'";
	    	$this->aOpportuniteExclues[] = "'LANDING_PAGE_2'";
    	} 
    	
    	if (Pelican::$config['BOFORMS_FORMSITE_ID']['CONFIGURATOR'] == $form_site_id) {
    		$this->aOpportuniteExclues[] = "'SUBSCRIBE_NEWSLETTER'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER'";
    		$this->aOpportuniteExclues[] = "'REQUEST_A_CONTACT_BUSINESS'";
    		$this->aOpportuniteExclues[] = "'CLAIMS'";
    		$this->aOpportuniteExclues[] = "'REQUEST_AN_INFORMATIONS'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_AMEX'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_EMAILING'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_CREDIPAR'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_B2B'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_CNIL'";
    		return;
    	}
    	
    	if ($country_code!='FR')	{
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_AMEX'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_CREDIPAR'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_B2B'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_CNIL'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_EMAILING'";
    	}
    }


 	protected function setListModelScreen1 ()
    {
  		$aSiteExclues = array(Pelican::$config['BOFORMS_FORMSITE_ID']['PERSONAL_SPACE'],
    							Pelican::$config['BOFORMS_FORMSITE_ID']['EDEALER'],
    							Pelican::$config['BOFORMS_FORMSITE_ID']['DERIVED_PRODUCT'],
    							Pelican::$config['BOFORMS_FORMSITE_ID']['STORE']);
    
    	$this->listModel = "SELECT fs.FORMSITE_KEY, fs.FORMSITE_ID 
    						FROM #pref#_boforms_formulaire_site fs
    						INNER JOIN #pref#_boforms_groupe_formulaire gf on fs.FORMSITE_ID = gf.FORMSITE_ID
    						INNER JOIN #pref#_boforms_groupe g on g.GROUPE_ID = gf.GROUPE_ID 
    						where fs.FORMSITE_ID not in (" . implode(',', $aSiteExclues) . ") AND g.SITE_ID = " . $_SESSION[APP]['SITE_ID'] . "
    						order by fs.FORMSITE_ID ";
    }
    
	protected function setListModelScreen2 ($form_site_id)
    {
    	$code_pays=FunctionsUtils::getCodePays();
   		$this->setTypeExclude($code_pays, $form_site_id);
    	
    	$sql = "SELECT OPPORTUNITE_ID, OPPORTUNITE_KEY FROM #pref#_boforms_opportunite";
    	
    	if(!empty($this->aOpportuniteExclues))
    	{
    		$sql .= " where OPPORTUNITE_KEY NOT IN (".implode(", ",$this->aOpportuniteExclues).")";
    	}	
    
    	$this->listModel = $sql;
    	
    }
    
    private function updateTopTitle() {
    	//$str = '<script type="text/javascript" src="' . Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js' . '"></script>';
    	$str .= '<script type="text/javascript">';
		$str .= "$( document ).ready(function() {
				window.parent.$('#frame_right_top').html('" . str_replace("'", "\'", t('BOFORMS_REPORTINGSTATUSFORMS')) . "');

				// fix width of the top scroll div 
				$('.div1').css('width', $('#table_dashboard').css('width'));
				
				
				// set scroll listener
				$('.wrapper1').scroll(function(){
			 		$('.wrapper2').scrollLeft($('.wrapper1').scrollLeft());
			 	});
			 	$('.wrapper2').scroll(function(){
			 		$('.wrapper1').scrollLeft($('.wrapper2').scrollLeft());
			 	});
				
		     });";
		$str .= '</script>';
		return $str;	
    }
    
    public function listAction() {
    	parent::listAction();
        $this->setListModelScreen1();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setCSS(array(
            "tblalt1",
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "fs.FORMSITE_ID");
                
    	if(is_array($table->aTableValues) && !empty($table->aTableValues))
        {
        	foreach ($table->aTableValues as $k=>$row)
        	{
        		//traduction Referential
        		$table->aTableValues[$k]['FORMSITE_LABEL'] = t('BOFORMS_FORMSITE_LABEL_' . $row['FORMSITE_KEY']);
        	}
        }
        
        $table->addColumn(t('ID'), "FORMSITE_ID", "10", "left", "", "tblheader");
        $table->addColumn(t('BOFORMS_LABEL'), "FORMSITE_LABEL", "50", "left", "", "tblheader");
                        
        $table->addInput(t('BOFORMS_BTN_SELECT'), "button", 
        					array("id" => "FORMSITE_ID", "" => "SCREEN=1"), "center");
               
		$this->aButton["add"] = "";
	    Backoffice_Button_Helper::init($this->aButton);
        $this->setResponse($this->updateTopTitle() . $table->getTable());
    }
    
    public function listActionScreen2 () {
    	$site_id = $this->getParam('id');
    	
    	$oConnection = Pelican_Db::getInstance ();
		$aBind[':FORM_SITE_ID'] = $site_id;

		$result = $oConnection->queryRow("select FORMSITE_KEY from #pref#_boforms_formulaire_site where FORMSITE_ID = :FORM_SITE_ID", $aBind);
		$site_name = t('BOFORMS_FORMSITE_LABEL_' . $result['FORMSITE_KEY']);
		
    	
    	$this->setListModelScreen2($site_id);
    	
		//parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        $table->setCSS(array(
            "tblalt1",
            "tblalt2"
        ));
        
        $table->setValues($this->getListModel(), "OPPORTUNITE_ID");
        
        if(is_array($table->aTableValues) && !empty($table->aTableValues))
        {
        	foreach ($table->aTableValues as $k=>$row)
        	{
        		//echo $table->aTableValues[$k]['OPPORTUNITE_KEY'] . '<br/>';
        		//traduction Referential
        		$table->aTableValues[$k]['OPPORTUNITE_KEY'] = t('BOFORMS_REFERENTIAL_FORM_TYPE_'.$row['OPPORTUNITE_KEY']);
        	}
        }
        
        // $table->addColumn('ID', "OPPORTUNITE_ID", "10", "left", "", "tblheader");
        $table->addColumn(t('BOFORMS_TYPE_FORMULAIRE'), "OPPORTUNITE_KEY", "20", "left", "", "tblheader");
       

        	


        
        
        // displays this buttons for all opportunities except for REQUEST_A_CONTACT_BUSINESS
       	$aShowPart = array('OPPORTUNITE_ID!=6', 'OPPORTUNITE_ID!=19');
        $table->addInput(t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PARTICULAR'), "button", 
        					array("id" => "OPPORTUNITE_ID", "" => "SCREEN=2&CUSTOMER_TYPE=1&SITE_ID=$site_id", "OPPORTUNITE_KEY" => "OPPORTUNITE_KEY"), "center",
        					$aShowPart 	);

		if (FunctionsUtils::isLandingPageSite($site_id) === false) {					
        	$aShowPro = array('OPPORTUNITE_ID!=4', 'OPPORTUNITE_ID!=5', 'OPPORTUNITE_ID!=7', 'OPPORTUNITE_ID!=8','OPPORTUNITE_ID!=13',
        						  'OPPORTUNITE_ID!=14','OPPORTUNITE_ID!=15','OPPORTUNITE_ID!=16',
        						  'OPPORTUNITE_ID!=17','OPPORTUNITE_ID!=18','OPPORTUNITE_ID!=20');
			$table->addInput(t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PROFESSIONAL'), "button", 
        					array("id" => "OPPORTUNITE_ID",  "" => "SCREEN=2&CUSTOMER_TYPE=2&SITE_ID=$site_id", "OPPORTUNITE_KEY" => "OPPORTUNITE_KEY"), "center",
        					$aShowPro	);
		}
		
         $this->aButton["add"] = "";
         //$this->aButton["back"] = "true";
	     Backoffice_Button_Helper::init($this->aButton);
        					
	    $top_str = "<h2 >" . $site_name . "</h2>";
		
	     
        $this->setResponse($this->updateTopTitle() . $top_str . $table->getTable());
    }     
    
    public function editAction() {
    	if ($this->getParam('SCREEN') == '1') {
    		$this->listActionScreen2();
    		return;
    	}
    	    	
    	
    	$site_id = $this->getParam('SITE_ID');
    	
   		$head = $this->getView()->getHead();
		$head->setMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
    	
    	$top_str =  "";
    	
    	$form_type = str_pad($this->getParam('id'), 2, "0", STR_PAD_LEFT);
		$customer_type = $this->getParam('CUSTOMER_TYPE');
		$opportunity_label =  $this->getParam('OPPORTUNITE_KEY');
		
		$customer_label = ($customer_type == 1) ? t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PARTICULAR') : t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PROFESSIONAL');
		$form_site = $site_id; //Pelican::$config['BOFORMS_DEFAULT_SITE_ID']; 
		
		$oConnection = Pelican_Db::getInstance ();
		
		$aBind[':FORM_SITE_ID'] = $site_id;
		$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
		
		$result = $oConnection->queryRow("select FORMSITE_KEY from #pref#_boforms_formulaire_site where FORMSITE_ID = :FORM_SITE_ID", $aBind);
		$site_name = t('BOFORMS_FORMSITE_LABEL_' . $result['FORMSITE_KEY']);
		
		// gets languages for site
    	$sqlLangue = "SELECT c.langue_code, c.langue_label,c.langue_id , a.SITE_CODE_PAYS FROM #pref#_site_code a
					  INNER JOIN #pref#_site_language b ON a.site_id = b.site_id
					  INNER JOIN #pref#_language c ON c.langue_id = b.langue_id
					  WHERE a.site_id = :SITE_ID";
    	
   		$aLangues = $oConnection->queryTab($sqlLangue, $aBind);
		$nb_lang = count($aLangues);
		
		$colspan_header = ($nb_lang * 2);
		
		$top_str .= "<style>
#table_dashboard td {border:1px solid black;border-collapse:collapse;}
#table_dashboard_culture td {font-size:12px;padding: 2;border:0px solid black;border-collapse:collapse;}
ul {list-style-type:none;}
.h2_legend {margin-bottom: 3px;margin-top:0;font-size:12px;}
.span_legend {width:25px;display:inline-block;}

.oldcalendar {
	position:relative;
	left:0px;
	float:left;
	z-index: 1;
	
}

.wrapper2 { 
	background-color: white;
	overflow-x:scroll;
    overflow-y:visible;
    
    width:750px;
    margin-left: 302px;
	
	position:static;
}

.wrapper1 { 
	background-color: white;
	overflow-x:scroll;
    overflow-y:visible;
    
    width:750px;
    margin-left: 302px;
	
	position:static;
	height: 20px;
}

.div1 {
   width:100px;
   height: 20px;
}


td {
    padding: 5px 20px;
    width: 150px;
}

th:first-child {
	position: absolute;
    left: 2px;
     width:291px;
    font-size:11px;
    
    overflow: hidden;
    text-overflow: ellipsis;
	white-space: nowrap;
	
	padding: 4;
	border:none;
	border-top: 1px solid black;
}

</style>";
		
		$top_str .= "<h2>" . $site_name . "</h2>";
		
		$tbl_all_datas = array();
		$aTradForce=false;
		for ($i = 0; $i < count($aLangues); $i++) {
			// get culture
			$aBind[':LANGUE_ID'] = $oConnection->strToBind($aLangues[$i]['langue_id']);
	     	$default_culture = $oConnection->queryItem("select CULTURE_ID from #pref#_boforms_culture where LANGUE_ID  = :LANGUE_ID", $aBind);
	   		
	     	$default_culture = str_pad($default_culture, 2, '0',  STR_PAD_LEFT);

	     	
	     	if($_SESSION[APP]['LANGUE_CODE']!='FR' && (int)$default_culture==10)
	     	{
	     		$sqlForceTrad = "select LABEL_ID, LABEL_TRANSLATE FROM #pref#_label_langue WHERE LABEL_ID LIKE 'BOFORMS_LABEL_%' AND LANGUE_ID = ".$aLangues[$i]['langue_id'];	
	     		$atempTrad=$oConnection->queryTab($sqlForceTrad);
	     		if(is_array($atempTrad))
	     		{
	     			foreach($atempTrad as $row){
	     				$aTradForce[$row['LABEL_ID']]=$row['LABEL_TRANSLATE'];
	     			}
	     			
	     		}
	     	}
	     	
	     	
	     	// generic web
			if (0 == $i) {
		     	$device = '0';
		     	$tab_info = $this->getFormFieldInfo($device, $aLangues[$i]['SITE_CODE_PAYS'], $customer_type, $form_site, '00', $form_type, '9'); 
		   		foreach($tab_info as $code => $value) {
		   			$tbl_all_datas[$code]['generic-0' ] = $value['value'];
		   			$tbl_all_datas[$code]['label'] = $value['label']; 
		   		}
		   		
				// generic mobile
				$device = '1';
				$tab_info = $this->getFormFieldInfo($device, $aLangues[$i]['SITE_CODE_PAYS'], $customer_type, $form_site, '00', $form_type, '9'); 
		   		foreach($tab_info as $code => $value) {
		   			$tbl_all_datas[$code]['generic-1'] = $value['value'];
		   			$tbl_all_datas[$code]['label'] = $value['label']; 
		   		}
			}
			
	   		// device = web
	   		$device = '0';
	   		$tab_info = $this->getFormFieldInfo($device, $aLangues[$i]['SITE_CODE_PAYS'], $customer_type, $form_site, $default_culture, $form_type, '0'); 
	   		foreach($tab_info as $code => $value) {
	   			$tbl_all_datas[$code][$aLangues[$i]['langue_id'] . '--0'] = $value['value'];
	   			//$tbl_all_datas[$code]['label'] = $value['label']; 
	   		}
	   		
	   		// device = mobile
	   		$device = '1';
	   		$tab_info = $this->getFormFieldInfo($device, $aLangues[$i]['SITE_CODE_PAYS'], $customer_type, $form_site, $default_culture, $form_type, '0');
			foreach($tab_info as $code => $value) {
	   			$tbl_all_datas[$code][$aLangues[$i]['langue_id'] . '--1'] = $value['value'];
	   			//$tbl_all_datas[$code]['label'] = $value['label']; 
	   		}
	   		
	   		
		}
		
    	// legend
		if (count($tbl_all_datas) > 0) {
	   		$top_str .= "<br/><h2 class=\"h2_legend\">" . t('BOFORMS_LEGEND') . "</h2> 
	   		<ul>
			  <li><span class=\"span_legend\">Oc</span><span style=\"font-weight:bold;\">=</span> " . t('BOFORMS_LEGEND_OC'). "</li>
			  <li><span class=\"span_legend\">Op</span><span style=\"font-weight:bold;\">=</span> " . t('BOFORMS_LEGEND_OP'). "</li>
			  <li><span class=\"span_legend\">F&nbsp;</span><span style=\"font-weight:bold;\">=</span> " . t('BOFORMS_LEGEND_F'). "</li>
			  <li><span class=\"span_legend\" style=\"font-weight:bold;color:red;\">X&nbsp;</span><span style=\"font-weight:bold;\">=</span> " . t('BOFORMS_LEGEND_X'). "</li>
			</ul>";
		}
		
		if (count($tbl_all_datas) > 0) {
			$top_str .= '<div class="wrapper1" id="wrapper1"><div id="div1" class="div1"></div></div><div class="oldcalendar"><div class="wrapper2">';
			$top_str .= "<table id=\"table_dashboard\" style=\"text-align:center;border-collapse:collapse;\"><tr><th>&nbsp;</th><td colspan=\"$colspan_header\" style=\"text-align:center;font-weight:bold;font-size:14px;padding:4\">$opportunity_label - $customer_label - " .  t('BOFORMS_ALL_COUNTRY'). "</td></tr>";
		
			// header line 2
			$top_str .= "<tr><th>&nbsp;</th>";
			for ($i = 0; $i < count($aLangues); $i++) {
				$top_str .= "<td colspan=\"2\" style=\"text-align:center;font-weight:bold;\">" . strtoupper($aLangues[$i]['langue_code']) . "</td>";
			}
			$top_str .= "</tr>";
			
			// header line 3
			$top_str .= "<tr><th>&nbsp;</th>";
			for ($i = 0; $i < count($aLangues); $i++) {
				$top_str .= "<td style=\"text-align:center;font-weight:bold;\">" . t('BOFORMS_REFERENTIAL_DEVICE_WEB') . "</td>";
				$top_str .= "<td style=\"text-align:center;font-weight:bold;\">" . t('BOFORMS_REFERENTIAL_DEVICE_MOBILE') . "</td>";
			}
			$top_str .= "</tr>";
			
			$num_ligne = 0;
			
			foreach ($tbl_all_datas as $code => $values) {
				$css = '';
				if ($num_ligne % 2 == 0) {
					$css = "background-color:#E4EEF5;";
				}

				if($code == 'connector_brandid'){

					$tmp_label = t('BOFORMS_LABEL_' . $code.'_'.Pelican::$config['BOFORMS_BRAND_ID']);

				}
				
				if ($code=='USR_PHONE_HOME' || $code=='USR_PHONE_MOBILE' || $code == 'USR_PHONE_MOBILE_HOME' || $code == 'connector_facebook' || $code == 'GET_MYDS')
				{
					$tmp_label = t('BOFORMS_LABEL_' . $code);
					
					if($aTradForce)
					{
						$tmp_label=$aTradForce['BOFORMS_LABEL_'.$code];
					}
					
				}elseif($code != 'connector_brandid'){
									
					if($values['label'] != '')
					{
						$tmp_label=strip_tags(str_replace('<BR/>', ' ',str_replace('<br/>', ' ', $values['label'])));
					}else{
						$tmp_label=$this->replaceCode($code);
						
						if($aTradForce)
						{
							$tmp_label=$aTradForce['BOFORMS_LABEL_'.$code];
						}
					}
					
					//$tmp_label = ($values['label'] != '') ? strip_tags(str_replace('<BR/>', ' ',str_replace('<br/>', ' ', $values['label']))) : $this->replaceCode($code);
				}
				
				
				
    			if (strlen($tmp_label) >= 40 ) {
    				$title_text = "<a href=\"#\" title=\"" . $tmp_label . "\" style=\"text-decoration:none;color:black;font-weight:bold;\">$tmp_label</a>";
    			} else {
    				$title_text = $tmp_label;
    			}
				
				$top_str .= "<tr style=\"$css\"><th style=\"font-weight:bold;text-align:left;\">" . $title_text . '</th>';
				for ($i = 0; $i < count($aLangues); $i++) {
					$device = 0; // web
					$tmp_key = $aLangues[$i]['langue_id'] . '--0';
					if (isset($values[$tmp_key])) {
						$top_str .= "<td>" . $values[$tmp_key] . "</td>";
					} else {
						$top_str .= "<td style=\"font-weight:bold;color:red;\">X</td>";
					}
					
					$device = 1; // mobile
					$tmp_key = $aLangues[$i]['langue_id'] . '--1';
					if (isset($values[$tmp_key])) {
						$top_str .= "<td>" . $values[$tmp_key] . "</td>";
					} else {
						$top_str .= "<td style=\"font-weight:bold;color:red;\">X</td>";
					}
				}
				$top_str .= "</tr>";
				$num_ligne++;
			}
			$top_str .= "</table></div></div>";
		} else {
			///////////// NO DATAS ////////////
			
			$top_str .= "<div style=\"text-align:center;\"><table id=\"table_dashboard\" style=\"text-align:center;border-collapse:collapse;\"><tr><td colspan=\"$colspan_header\" style=\"text-align:center;font-weight:bold;font-size:14px;padding:4\">$opportunity_label - $customer_label - " .  t('BOFORMS_ALL_COUNTRY'). "</td></tr>";
		
			// header line 2
			$top_str .= "<tr>";
			for ($i = 0; $i < count($aLangues); $i++) {
				$top_str .= "<td colspan=\"2\" style=\"text-align:center;font-weight:bold;\">" . strtoupper($aLangues[$i]['langue_code']) . "</td>";
			}
			$top_str .= "</tr>";
			
			// header line 3
			$top_str .= "<tr>";
			for ($i = 0; $i < count($aLangues); $i++) {
				$top_str .= "<td style=\"text-align:center;font-weight:bold;\">" . t('BOFORMS_REFERENTIAL_DEVICE_WEB') . "</td>";
				$top_str .= "<td style=\"text-align:center;font-weight:bold;\">" . t('BOFORMS_REFERENTIAL_DEVICE_MOBILE') . "</td>";
			}
			$top_str .= "</tr>";
			
			$top_str .= "<tr><td colspan=\"$colspan_header\" style=\"text-align:center;font-weight:bold;font-size:14px;color:red;padding:4\">" . t('BOFORMS_REPORTING_NODATAS') . "</td></tr>";
		
			$top_str .= "</table></div>";
		}
			
		$this->setResponse($this->updateTopTitle() . $top_str);
		
		
    }
    
	private function replaceCode($the_code) {
		if (
		$the_code == 'SBS_NWL_NEWS' || $the_code == 'MULTIFORMS_CHOICE' || $the_code == 'SBS_USR_OFFER_2' || $the_code == 'REQUEST_CALLBACK' ||		
		$the_code == 'GET_MYCITROEN' || $the_code == 'SBS_COM_OFFER' || $the_code == 'SBS_USR_OFFER' || $the_code == 'SBS_COM_OFFER_2' || 
		$the_code == 'REQUEST_INTEREST_FINANCING' || $the_code == 'REQUEST_INTEREST_INSURANCE' || $the_code == 'SBS_USER_OFFER' || 
		$the_code == 'BOFORMS_LABEL_REQUEST_INTEREST_SERVICE' || $the_code == 'BOFORMS_LABEL_UNS_NWS_CPP_MOTIF' || 
		$the_code == 'REQUEST_INTEREST_SERVICE' || $the_code == 'UNS_NWS_CPP_MOTIF' || $the_code == 'LEGAL_MENTION_CPP_ANSWER' || $the_code == 'LEGAL_MENTION_ANSWER') {
	   			$the_code = t('BOFORMS_LABEL_' . $the_code);
	   	}
  		return $the_code;
	}
				
	private function getFormFieldInfo($device, $code_pays, $customer_type, $form_site, $default_culture, $form_type, $standard) {
    	$form_site = str_pad($form_site, 2, '0',  STR_PAD_LEFT);
    	
    	$sCode = Pelican::$config['BOFORMS_BRAND_ID'] . $code_pays . $customer_type . $standard . 
	   					$form_site . '00' . $default_culture . $device . '0' . $form_type;
   		$form_detail = FunctionsUtils::getFormulaireFromCode($sCode, 'CURRENT');
		
		$tblFieldsData = array();
		if (isset($form_detail['FORM_XML_CONTENT'])) {
			$this->oXMLOriginal = new XMLHandle($form_detail['FORM_XML_CONTENT'], 'xml');
			$this->oXMLOriginal->Parser_read();
			
			if (isset($this->oXMLOriginal->aField)) {
				foreach ($this->oXMLOriginal->aField as $key => $values) {
					if (isset($values['field']['hidden']) || isset($values['field']['button']) || isset($values['html']) ) {
					} else {
						$label = str_replace("\n",'' ,$values['field']['label']['value']);
						
						if (isset($values['field']['attributes']['required_central'])) {
							$value = 'Oc';				
						} else if (isset($values['field']['required'])) {
							$value = 'Op';
						} else {
							$value = 'f';
						}
						
						$tblFieldsData[$key] = array('label' => $label, 'value' => $value);
					}
				}
			}
		}	
		return $tblFieldsData;
    }
    
}