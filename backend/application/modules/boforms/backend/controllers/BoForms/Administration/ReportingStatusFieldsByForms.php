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

// etat des champs par formulaire

class BoForms_Administration_ReportingStatusFieldsByForms_Controller extends Pelican_Controller_Back
{
	protected $oXMLOriginal; // objet XMLHandle du formulaire avant modification
    protected $oXMLGeneric; // objet XMLHandle du formulaire générique
	
	protected $administration = true;

    protected $form_name = "boforms_etat_forms_pays";

    protected $aOpportuniteExclues = array();
    
    private function setTypeExclude($country_code, $form_site_id, $customer_type)
    {
    	$this->aOpportuniteExclues = array("'CLAIMS_ABOUT_YOUR_CAR'", "'CLAIMS_ABOUT_YOUR_DEALER'", "'CLAIMS_ABOUT_DOCUMENTATION'", "'CLAIMS_OTHER'");
    	
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
    	
    	// si particulier
    	if ($customer_type == '1') {
    		$this->aOpportuniteExclues[] = "'REQUEST_A_CONTACT_BUSINESS'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_B2B'";
    	} else {
    		$this->aOpportuniteExclues[] = "'SUBSCRIBE_NEWSLETTER'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER'";
    		$this->aOpportuniteExclues[] = "'CLAIMS'";
    		$this->aOpportuniteExclues[] = "'REQUEST_AN_INFORMATIONS'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_AMEX'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_EMAILING'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_CREDIPAR'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_CNIL'";
    	}
    	
    	if ($country_code!='FR')	{
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_AMEX'";
    		$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_CREDIPAR'";
    		// test pour ne pas exclure deux fois UNSUBSCRIBE_NEWSLETTER_B2B
    		if (! in_array("'UNSUBSCRIBE_NEWSLETTER_B2B'", $this->aOpportuniteExclues) ) {
    			$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_B2B'";
    		}
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
        
  	public function listAction() {
    	$html = '<script type="text/javascript">';
		$html .= "$( document ).ready(function() {
				window.parent.$('#frame_right_top').html('" . t('BOFORMS_REPORTINGSTATUSFIELDSBYFORMS') . "'); 
		     });";
		$html .= '</script>';
  		
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
        $this->setResponse($html . $table->getTable());
    }
    
    public function editAction () {
    	$site_id = $this->getParam('id');
		$aBind[':FORM_SITE_ID'] = $site_id;
    	
		$oConnection = Pelican_Db::getInstance ();
		$result = $oConnection->queryRow("select FORMSITE_KEY from #pref#_boforms_formulaire_site where FORMSITE_ID = :FORM_SITE_ID", $aBind);
		$site_name = t('BOFORMS_FORMSITE_LABEL_' . $result['FORMSITE_KEY']);
		
		echo "<html><head><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\" /></head><body>";
		
    	echo "<h2>$site_name</h2>";
    	$tbl_devices = array('0', '1'); // 0 = web, 1 = mobile
    	
    	echo '<script type="text/javascript" src="' . Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js' . '"></script>';
    	echo "<script type='text/javascript' src='".Pelican_Plugin::getMediaPath('boforms')."js/jquery-ui.min.js'></script>";
    	echo '<script type="text/javascript">';
		echo "$( document ).ready(function() {
				window.parent.$('#frame_right_top').html('" . t('BOFORMS_REPORTINGSTATUSFIELDSBYFORMS') . "'); 
				
				
				$( '.div1' ).each(function( index ) {
					the_div1_id = $( this ).attr('id');
					the_table_dashboard_id = the_div1_id + 'table';
					
					// fix width of the top scroll div 
					$(this).css('width', $('#' + the_table_dashboard_id).css('width'));
				
					// get wrapper ids
					var id_wrapper1 = '#' + the_div1_id + '_wrapper1';
					var id_wrapper2 = '#' + the_div1_id + '_wrapper2';
					
					// set scroll listener
					$(id_wrapper1).scroll(function(){
				 		$(id_wrapper2).scrollLeft($(id_wrapper1).scrollLeft());
				 	});
				 	$(id_wrapper2).scroll(function(){
				 		$(id_wrapper1).scrollLeft($(id_wrapper2).scrollLeft());
				 	});
					
				});
				
				
				 
				 
				$('.dialog_detail').on('click', function(e) {
	    			var myid = $(this).attr('id');
	   				var url_to_call = $(this).find('input').val();
	   				
	   				window.open(url_to_call, 'Detail','menubar=no, scrollbars=yes');
	   				
	   				
	   				e.preventDefault();
    				return false;
       			});
		     });";
		echo '</script>';
    	
    	
    	echo "<style>
h2 {
    color: #014ea2;
    font-size: 18px;
    font-variant: normal;
}    	
.table_dashboard td {border:1px solid black;font-size:12px;padding: 4;}
ul {list-style-type:none;}
.h2_legend {margin-bottom: 3px;margin-top:0;;font-size:12px;}
.span_legend {width:25px;display:inline-block;}

.calendar2 {
	position:relative;
	left:0px;
	float:left;
}

.div1 {
   width:1000px;
   height: 20px;
}

.table_dashboard {
	background-color: white;
	width:100%;border-collapse:collapse;
}

.table_dashboard td {
    padding: 5px 20px;
    width: 150px;
}

.table_dashboard th:first-child {
	position: absolute;
    left: 2px;
    width:291px;
    font-size:11px;padding: 4;
    border-top:1px solid black;
    border-collapse:collapse;
    overflow: hidden;
    text-overflow: ellipsis;
	white-space: nowrap;
}

</style>";
    	
    	
echo "<style type=\"text/css\">
.wrapper2 { 
	background-color: white;
	overflow-x:scroll;
    overflow-y:visible;
    
    width:700px;
    margin-left: 302px;
	
	position:static;
}

.wrapper1 { 
	background-color: white;
	overflow-x:scroll;
    overflow-y:visible;
    
    width:700px;
    margin-left: 302px;
	
	position:static;
	height: 20px;
}
</style>";    	


    	$templateId = FunctionsUtils::getTemplateId('BoForms_Administration_ReportingStatusForms');
    	
    	// legend
   		echo "<h2 class=\"h2_legend\">" . t('BOFORMS_LEGEND') . "</h2> 
   		<ul>
		  <li><span class=\"span_legend\">Oc</span><span style=\"font-weight:bold;\">=</span> " . t('BOFORMS_LEGEND_OC'). "</li>
		  <li><span class=\"span_legend\">Op</span><span style=\"font-weight:bold;\">=</span> " . t('BOFORMS_LEGEND_OP'). "</li>
		  <li><span class=\"span_legend\">F&nbsp;</span><span style=\"font-weight:bold;\">=</span> " . t('BOFORMS_LEGEND_F'). "</li>
		  <li><span class=\"span_legend\" style=\"font-weight:bold;color:red;\">X&nbsp;</span><span style=\"font-weight:bold;\">=</span> " . t('BOFORMS_LEGEND_X'). "</li>
		</ul>";
    	
   		
    	$css_part = "background-color:lightgrey;";
    	$css_pro = "background-color:#E4EEF5;";
    	
		// gets languages for site
    	
    	$sqlLangue = "SELECT c.langue_code, c.langue_label,c.langue_id , a.SITE_CODE_PAYS FROM #pref#_site_code a
					  INNER JOIN #pref#_site_language b ON a.site_id = b.site_id
					  INNER JOIN #pref#_language c ON c.langue_id = b.langue_id
					  WHERE a.site_id = :SITE_ID";
    	$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];

   		$aLangues = $oConnection->queryTab($sqlLangue, $aBind);

   		$tbl_customers = array('CUSTOMER_TYPE_PARTICULAR' => '1', 'CUSTOMER_TYPE_PROFESSIONAL' => '2');

   		// displays one table per language
   		
   
   		for ($zzz = 0; $zzz < count($aLangues); $zzz++) {
   			foreach ( $tbl_customers as $customer_type_str => $customer_type) {
   				if ($customer_type == '2') {
   					if (FunctionsUtils::isLandingPageSite($site_id)) {
   						continue; // no professional for landing page 
   					}
   					
   					$css_curr = $css_pro;
   				} else {
   					$css_curr = $css_part;
   				}
	   			// OPPORTUNITE_KEY OPPORTUNITE_ID

   				$this->setTypeExclude($aLangues[0]['SITE_CODE_PAYS'], $site_id, $customer_type);
   				$opportunities = FunctionsUtils::getOpportunitiesList($this->aOpportuniteExclues);
   				$nb_cols_opport = count($opportunities) * count($tbl_devices);
		    	
		    	
		    	///////////////////// calculates data to display ////////////////////
		    	
		    	$aBind[':LANGUE_ID'] = $oConnection->strToBind($aLangues[$zzz]['langue_id']);
		     	$default_culture = $oConnection->queryItem("select CULTURE_ID from #pref#_boforms_culture where LANGUE_ID  = :LANGUE_ID", $aBind);
		   		$default_culture = str_pad($default_culture, 2, '0',  STR_PAD_LEFT);
		   		
		   		$aTradForce=false;
		   		if($_SESSION[APP]['LANGUE_CODE']!='FR' && (int)$default_culture==10)
		   		{
		   			$sqlForceTrad = "select LABEL_ID, LABEL_TRANSLATE FROM #pref#_label_langue WHERE LABEL_ID LIKE 'BOFORMS_LABEL_%' AND LANGUE_ID = ".$aLangues[$zzz]['langue_id'];
		   			$atempTrad=$oConnection->queryTab($sqlForceTrad);
		   			if(is_array($atempTrad))
		   			{
		   				foreach($atempTrad as $row){
		   					$aTradForce[$row['LABEL_ID']]=$row['LABEL_TRANSLATE'];
		   				}
		   				 
		   			}
		   		}
		   		
		   		$tbl_all_datas = array();
		   		for ($ii = 0; $ii < count($opportunities); $ii++) {
		    		$form_type = str_pad($opportunities[$ii]['OPPORTUNITE_ID'], 2, "0", STR_PAD_LEFT);
		    		$form_site = $site_id; // Pelican::$config['BOFORMS_DEFAULT_SITE_ID'];
					$opportunity_key = $opportunities[$ii]['OPPORTUNITE_KEY'];
					$site_code_pays = $aLangues[$zzz]['SITE_CODE_PAYS'];
					
	    			
	    			for ($a = 0; $a < count($tbl_devices); $a++) {
	    				$device = $tbl_devices[$a];
	   					
	    				$tab_info = $this->getFormFieldInfo($device, $site_code_pays, $customer_type, $form_site, '00', $form_type, '9'); 
					   	foreach($tab_info as $code => $value) {
					   		$tbl_all_datas[$code]['generic'] = $value['value'];
					   		$tbl_all_datas[$code]['label'] = $value['label']; 
					   	}
					   	unset($tab_info);
						   	
					   	$tab_info = $this->getFormFieldInfo($device, $site_code_pays, $customer_type, $form_site, $default_culture, $form_type, '0'); 
					   	foreach($tab_info as $code => $value) {
					   		$tbl_all_datas[$code][$opportunity_key . '--' . $device . '--' . $customer_type] = $value['value'];
					   		$tbl_all_datas[$code]['label'] = $value['label']; 
					   	}
					   	unset($tab_info);
	    			}   			
		   	   		
					//break; // TODO remove this line
		   		}
		    	
		   		///////////////// displays tab content ////////////////////
		   		
		   		$num_ligne = 0;
		   		
		   		if (count($tbl_all_datas) == 0) {
		   			echo "<table style=\"text-align:center;border:1px solid black;\"><tr>
		    		<th style=\"width:295px;color:#fff;font-weight:bold;background-color:#4169E1;\">" . $aLangues[$zzz]['langue_code'] . '_' . $aLangues[$zzz]['SITE_CODE_PAYS'] . "</th>";
			    	echo "<th style=\"font-weight:bold;text-align:center;padding-left:10px;$css_curr\">" . strtoupper(t('BOFORMS_REFERENTIAL_' . $customer_type_str)) . "</th></tr>";
			    	echo "<tr><td colspan=\"2\">" . t('BOFORMS_AUCUN_RESULTAT') . "</td></tr>";
			    	echo "</table><br/>";
		   		} else {
		   			echo "<div class='wrapper1' id=\"div1_{$zzz}_{$customer_type}_wrapper1\">
		    		  <div id=\"div1_{$zzz}_{$customer_type}\" class=\"div1\"></div></div>
		    		  <div class=\"calendar2\"><div class=\"wrapper2\" id=\"div1_{$zzz}_{$customer_type}_wrapper2\">
		    		<table class=\"table_dashboard\" id=\"div1_{$zzz}_{$customer_type}table\" style=\"text-align:center;border-collapse:collapse;\"><tr>
		    		<th style=\"color:#fff;font-weight:bold;background-color:#4169E1;\">" . $aLangues[$zzz]['langue_code'] . '_' . $aLangues[$zzz]['SITE_CODE_PAYS'] . "</th>";
		    	
			    	echo "<td colspan=\"$nb_cols_opport\" style=\"font-weight:bold;text-align:left;padding-left:10px;$css_curr\">" . strtoupper(t('BOFORMS_REFERENTIAL_' . $customer_type_str)) . "</td></tr>";
			    	
			    	// header line 2
			    	$str_line1 = "<tr><th style=\"height:50px;\">&nbsp;</th>";
			    	$str_line1_footer = "<tr><th style=\"height:50px;\">&nbsp;</th>";
				    for ($i = 0; $i < count($opportunities); $i++) {
				    	$url_detail = "/_/Index/child?tid=" . $templateId . "&SITE_ID=" . $site_id . "&SCREEN=2&id=" . $opportunities[$i]['OPPORTUNITE_ID'] . "&CUSTOMER_TYPE=" . $customer_type . 
		    									"&OPPORTUNITE_KEY=" . urlencode(t('BOFORMS_REFERENTIAL_FORM_TYPE_' . $opportunities[$i]['OPPORTUNITE_KEY']));
		    				
				    	$str_line1 .= "<td colspan=\"" . count($tbl_devices) . "\"><a id=\"dialog_detail_" . $i . '_' . $zzz . "\" href=\"#\" class=\"dialog_detail\">" . 
		    			"<input id=\"dialog_detail_" . $i . '_' . $zzz . "_url\"  name=\"dialog_detail_" . $i . '_' . $zzz . "_url\" type=\"hidden\" value=\"$url_detail\"/>" .    				
		    			$opportunities[$i]['OPPORTUNITE_LABEL'] . "</a></td>";
		    				
		    			$str_line1_footer .= "<td colspan=\"" . count($tbl_devices) . "\"><a id=\"dialog_detail2_" . $i . '_' . $zzz . "\" href=\"#\" class=\"dialog_detail\">" . 
		    			"<input id=\"dialog_detail2_" . $i . '_' . $zzz . "_url\"  name=\"dialog_detail2_" . $i . '_' . $zzz . "_url\" type=\"hidden\" value=\"$url_detail\"/>" .    				
		    			$opportunities[$i]['OPPORTUNITE_LABEL'] . "</a></td>";
				    }
			    		
			    	$str_line1 .= "</tr>";
			    	$str_line1_footer .= "</tr>";
			    	
			    	// header line 3	    	
			    	$str_line2 = "<tr><th>&nbsp;</th>";
		    		for ($i = 0; $i < count($opportunities); $i++) {
		    			for ($bb = 0; $bb < count($tbl_devices); $bb++) {
		    				if ($tbl_devices[$bb] == '0') {
		    					$str_line2 .= "<td style=\"text-align:center;font-weight:bold;\">Web</td>";
			    			} else {
								$str_line2 .= "<td style=\"text-align:center;font-weight:bold;\">" . t('BOFORMS_REFERENTIAL_DEVICE_MOBILE') . "</td>";
			    			}
		    			}
			    	}
			    	
			    	$str_line2 .= "</tr>";
			    	echo $str_line1 . $str_line2;
		   			

			   		foreach ($tbl_all_datas as $code => $values) {

			   			$the_code = strip_tags(str_replace('<BR/>', ' ',str_replace('<br/>', ' ', $values['label'])));


						if($code == 'connector_brandid')
						{
							$the_code = t('BOFORMS_LABEL_' . $code.'_'.Pelican::$config['BOFORMS_BRAND_ID']);
						}


			   			if($code=='USR_PHONE_HOME' || $code=='USR_PHONE_MOBILE' || $code == 'USR_PHONE_MOBILE_HOME' || $code == 'connector_facebook' || $code == 'GET_MYDS')
			   			{
			   				$the_code = t('BOFORMS_LABEL_' . $code);

			   				if($aTradForce)
			   				{
			   					$the_code=$aTradForce['BOFORMS_LABEL_'.$code];
			   				}
			   			}
			   			
			   			if ($the_code == '') {
			   				$the_code = $code;
			   				
			   				if ($the_code == 'SBS_NWL_NEWS' || $the_code == 'MULTIFORMS_CHOICE' || $the_code == 'SBS_USR_OFFER_2' || $the_code == 'REQUEST_CALLBACK' ||
			   				$the_code == 'GET_MYCITROEN' || $the_code == 'SBS_COM_OFFER' || $the_code == 'SBS_COM_OFFER_2' || $the_code == 'SBS_USR_OFFER' || $the_code == 'REQUEST_INTEREST_FINANCING' ||  $the_code == 'SBS_USER_OFFER' ||
			   					$the_code == 'REQUEST_INTEREST_INSURANCE' || $the_code == 'REQUEST_INTEREST_SERVICE' || $the_code == 'UNS_NWS_CPP_MOTIF' || $the_code == 'LEGAL_MENTION_CPP_ANSWER' || $the_code == 'LEGAL_MENTION_ANSWER') {
			   					$the_code = t('BOFORMS_LABEL_' . $the_code);
			   					
			   					if($aTradForce)
			   					{
			   						$the_code=$aTradForce['BOFORMS_LABEL_'.$code];
			   					}
			   				}
			   			}
			   			
			   			if ($code == 'TESTDRIVE_COMMENT') {
			   				$the_code = t('BOFORMS_PREFIX_TESTDRIVE') . $the_code;
			   			} else if ($code == 'OFFER_COMMENT') {
			   				$the_code = t('BOFORMS_PREFIX_OFFER') . $the_code;
			   			}
			   			
			   			if (strlen($the_code) >= 40) {
			   				$the_code = "<a href=\"#\" title=\"$the_code\" style=\"text-decoration:none;color:black;font-weight:bold;\">" . $the_code . "</a>";
			   			}
			   			
			   			
			   			echo "<tr><th style=\"text-align:left;font-weight:bold;\">" . $the_code . "</th>";
			   			
			   			
		    			// alternates row colors
			    		$css = "";
						if (($num_ligne % 2) == 0) {
							if ($customer_type == '2') {
								$css = $css_pro;	
							} else {
							 	$css = $css_part;
							}
						}
		    				
		    			for ($iii = 0; $iii < count($opportunities); $iii++) {
			    			$opportunity_key = $opportunities[$iii]['OPPORTUNITE_KEY'];
		    				for ($a = 0; $a < count($tbl_devices); $a++) {
			    				$device = $tbl_devices[$a];
			    					
			    				if (isset($tbl_all_datas[$code][$opportunity_key . '--' . $device . '--' . $customer_type])) {
			    					echo "<td style=\"$css\">" . $tbl_all_datas[$code][$opportunity_key . '--' . $device . '--' . $customer_type] . "</td>";
			    				} else {
			    					echo "<td style=\"font-weight:bold;color:red;$css\">X</td>";
			    				}
				   			}
		    			}
			   			
			   			echo "</tr>";
			   			$num_ligne++;
			   		}
			   		echo $str_line2 . $str_line1_footer;
			   		echo "</table></div></div>";
			   		
			    	unset($tbl_all_datas);
	    			echo "<div style=\"clear:both;\"></div> <br/><br/>";
		   		}

   			}
   		}

   		echo "</body></html>";
   		
    }
   
    
	private function getFormFieldInfo($device, $code_pays, $customer_type, $form_site, $default_culture, $form_type, $standard) {
    	$form_site = str_pad($form_site, 2, '0',  STR_PAD_LEFT);
    	
    	$sCode = Pelican::$config['BOFORMS_BRAND_ID'] . $code_pays . $customer_type . $standard . 
	   					$form_site . '00' . $default_culture . $device . '0' . $form_type;
		
	   	$form_detail = FunctionsUtils::getFormulaireFromCode($sCode, 'CURRENT');
		
		$tblFieldsData = array();
		if (isset($form_detail['FORM_XML_CONTENT'])) {
			$this->oXMLOriginal = new XMLHandle($form_detail['FORM_XML_CONTENT'], 'xml');
			$this->oXMLOriginal ->Parser_read();
			
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
