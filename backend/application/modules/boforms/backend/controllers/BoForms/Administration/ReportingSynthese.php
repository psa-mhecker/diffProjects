<?php
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local.ini.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/BoForms.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FormInstance.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/XMLHandle.class.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/JiraUtil.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FunctionsUtils.php');

/*** WebServices***/
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/services.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Configuration.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetLeadsByTypeRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetLeadsByTypeResponse.php');

//ajout du fichier de conf administrable en BO
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.admin.ini.php');

// Synthese des leads par mois et par formulaire

/**
 * Formulaire de gestion des Formulaires
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author RaphaÃƒÂ«l Carles <rcarles@businessdecision.com>
 * @since 15/01/2014
 */

class BoForms_Administration_ReportingSynthese_Controller extends Pelican_Controller_Back
{
	protected $administration = true;

    protected $form_name = "boforms_reporting_synthese";
	
    // clé de traduction pour les mois
    protected $tbl_month_keys = array(
		'01' => 'BOFORMS_MONTHS_JANUARY',
		'02' => 'BOFORMS_MONTHS_FEBRUARY',
		'03' => 'BOFORMS_MONTHS_MARCH', 
		'04' => 'BOFORMS_MONTHS_APRIL',
		'05' => 'BOFORMS_MONTHS_MAY', 
		'06' => 'BOFORMS_MONTHS_JUNE', 
		'07' => 'BOFORMS_MONTHS_JULY', 
		'08' => 'BOFORMS_MONTHS_AUGUST', 
		'09' => 'BOFORMS_MONTHS_SEPTEMBER',
		'10' => 'BOFORMS_MONTHS_OCTOBER', 
		'11' => 'BOFORMS_MONTHS_NOVEMBER',
		'12' => 'BOFORMS_MONTHS_DECEMBER'
    );
    
    public function listAction () {
    	$this->_forward ('edit');
    }
    
    public function getSyntheseAction() {
    	   	
    	// format date
    	$date_start = $this->getParam('dateStart');
    	$date_end = $this->getParam('dateEnd');
    	$tbl_date_start = explode('/', $date_start);
    	$tbl_date_end = explode('/', $date_end);
    	$date_start = $tbl_date_start[2] . '-' . $tbl_date_start[1] . '-' . $tbl_date_start[0] . 'T00:00:00';
    	$date_end   = $tbl_date_end[2] . '-' . $tbl_date_end[1] . '-' . $tbl_date_end[0] . 'T23:59:59';
    	
    	// calls leads ws
    	$resultat = $this->getLeadsByType($date_start, $date_end);
    	
    	// libelle pour les mois
    	$libelle_start = t($this->tbl_month_keys[$tbl_date_start[1]]) . ' ' . $tbl_date_start[2];
    	$libelle_end   = t($this->tbl_month_keys[$tbl_date_end[1]]) . ' ' . $tbl_date_end[2];;
    	
    	$tblSectionM =  array();
    	for ($zz = 0; $zz < count($resultat->leads->leadInfoType); $zz++) {
    		// exemple de cle: BOOK_A_TEST_DRIVE_WEB_PRO
    		$tblSectionM[$resultat->leads->leadInfoType[$zz]->formType . '_' . 
    		$resultat->leads->leadInfoType[$zz]->device . '_' .
    		$resultat->leads->leadInfoType[$zz]->type . '_' . $resultat->leads->leadInfoType[$zz]->siteCode] = $resultat->leads->leadInfoType[$zz]->total;
    		
    		$tbl_site_code[$resultat->leads->leadInfoType[$zz]->siteCode] = 1;
    	}
    	
    		$year  = $tbl_datas[2];
    		$month = $tbl_datas[1];
    	
    		$form_type_list = FunctionsUtils::getOpportunitiesList();
    		$device_list = FunctionsUtils::getDeviceList();
    		$form_site_label = FunctionsUtils::getAllFormulaireSite();
    		
    		////////////////// headers /////////////////////////
    		
    		echo "<style>
#table_dashboard td {border:1px solid black;
					border-collapse:collapse;
					font-size:12px;
					padding: 4px;}
</style>";

    		$supertotal_M = 0;
    		
    		if(is_array($tbl_site_code) && !empty($tbl_site_code))
    		{
    			// line 1
	    		echo "<table border=\"1\"  id=\"table_dashboard\" style=\"text-align:center;border-collapse:collapse;width:80%;\">
	    		<tr><td>Leads</td>";
	    		
	    		echo "<td style=\"font-weight:bold;\">Total</td>";
	    		for ($j = 0; $j < count($device_list); $j++) {
	    			echo "<td colspan=\"2\" style=\"text-align:center;font-weight:bold;\">" . $device_list[$j]['DEVICE_KEY'] . "</td>";
	    		}
	    		echo "</tr>";
	    		
	    		//////////////// middle array //////////////////
    			ksort($tbl_site_code);
	    		foreach ($tbl_site_code as $current_site_code => $value) {
	    			$tabHtml="";
	    			$Sitetotal_M = 0;
	    			
	    			for($i = 0; $i < count($form_type_list); $i++) {
	    				
						// alternates row colors
		    			$css = "";
						if (($i % 2) == 0) {
							$css = "background-color:#E4EEF5";
						}
						
			    		// month M
			    		$str_tmp_td_M = '';
		    			$total_M = 0;
			    		
		    			if ($form_type_list[$i]['OPPORTUNITE_KEY'] != 'CLAIMS_ABOUT_YOUR_CAR' && $form_type_list[$i]['OPPORTUNITE_KEY'] != 'CLAIMS_ABOUT_YOUR_DEALER' &&
			    			$form_type_list[$i]['OPPORTUNITE_KEY'] != 'CLAIMS_ABOUT_DOCUMENTATION' && $form_type_list[$i]['OPPORTUNITE_KEY'] != 'CLAIMS_OTHER' && !(($form_type_list[$i]['OPPORTUNITE_KEY'] == 'SUBSCRIBE_NEWSLETTER' || $form_type_list[$i]['OPPORTUNITE_KEY']=='UNSUBSCRIBE_NEWSLETTER') && FunctionsUtils::isLandingPageSite($current_site_code) && FunctionsUtils::getCodePays() != 'FR')) {
		    				
			    			$nb_found = 0;	

			    			for ($j = 0; $j < count($device_list); $j++) {
								$part_total = 0;
			    				if (isset($tblSectionM[$form_type_list[$i]['OPPORTUNITE_KEY'] . '_' . $device_list[$j]['DEVICE_KEY']. '_PART_' . $current_site_code])) {
			    					$part_total =  $tblSectionM[$form_type_list[$i]['OPPORTUNITE_KEY'] . '_' . $device_list[$j]['DEVICE_KEY']. '_PART_' . $current_site_code];
			    					$nb_found++;
				    			}
				    			$pro_total = 0;
				    			if (isset($tblSectionM[$form_type_list[$i]['OPPORTUNITE_KEY'] . '_' . $device_list[$j]['DEVICE_KEY']. '_PRO_' . $current_site_code])) {
				    				$pro_total = $tblSectionM[$form_type_list[$i]['OPPORTUNITE_KEY'] . '_' . $device_list[$j]['DEVICE_KEY']. '_PRO_' . $current_site_code];
				    				$nb_found++;	
				    			}
				    			
				    			$str_tmp_td_M = $str_tmp_td_M . "<td>$part_total</td><td>$pro_total</td>";
				    			$total_M = $total_M + $part_total + $pro_total;
				    			$device_list[$j]['Total_M_pro']  = $device_list[$j]['Total_M_pro'] + $pro_total;
				    			$device_list[$j]['Total_M_part'] = $device_list[$j]['Total_M_part'] + $part_total;
				    		}
				    		
				    		$supertotal_M += $total_M;
				    		$Sitetotal_M += $total_M;

				    		if ($nb_found > 0) {
					    		$tabHtml.= "<tr style=\"$css\"><td style=\"text-align:left;font-weight:bold;\">" . $form_type_list[$i]['OPPORTUNITE_LABEL'] . '</td><td style="background-color:white;">' . $total_M . '</td>';
					    		$tabHtml.= $str_tmp_td_M;			    		
					    		$tabHtml.= "</tr>";
					    		
				    		}
				    		
			    		}
		    		}
		    		
		    		$current_site_label = (isset($form_site_label[$current_site_code])) ? $form_site_label[$current_site_code] : $current_site_code;
		    		 
		    		echo "<tr  style=\"background-color:#4169E1;\"><td style=\"color:#fff;font-weight:bold;\">$current_site_label</td><td style=\"color:#fff;font-weight:bold;\">$Sitetotal_M</td>";
		    		for ($j = 0; $j < count($device_list); $j++) {
		    			echo "<td style=\"color:#fff;font-weight:bold;\">Part</td><td style=\"color:#fff;font-weight:bold;\">Pro</td>";
		    		}
		    		$html .= "</tr>";
		    		
		    		echo $tabHtml;
	    		}

    		
	    		//////////// end line with totals //////////////////////
	    		echo "<tr><td>&nbsp;</td><td style=\"font-weight:bold;\">$supertotal_M</td>";
	    		for ($j = 0; $j < count($device_list); $j++) {
	    			echo "<td style=\"font-weight:bold;\">" . $device_list[$j]['Total_M_part'] . "</td>";
	    			echo "<td style=\"font-weight:bold;\">" . $device_list[$j]['Total_M_pro'] . "</td>";
	    		}
	    		echo "</tr></table>";
    		}else{
    			echo "<div with='100%' style='margin:20 0 20 0; text-align:center; border:solid thin red; background-color:pink; color:red;'>".t('BOFORMS_AUCUN_RESULTAT')." </div>";
    		}
    		    		
    	exit(0);
    }
    
    public function editAction ()
    { 	
    	$head = $this->getView()->getHead();
		$head->setMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
		$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js');
    	$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery-ui.min.js');
		
    	parent::editAction();
    	$this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form .= $this->oForm->open(Pelican::$config["DB_PATH"]);
 		$form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        //$form = $this->startStandardForm();
		
        $strControl = "shortdate";
        $form .= $this->oForm->createInput("date_start", t('BOFORMS_DATEPICKER_START_DATE'), 10, $strControl, false, '', false, 10);
        $form .= $this->oForm->createInput("date_end", t('BOFORMS_DATEPICKER_END_DATE'), 10, $strControl, false, '', false, 10);
        
        $form .= $this->oForm->endTab ();
		$form .= $this->beginForm ( $this->oForm );
		$form .= $this->oForm->beginFormTable ();
		$form .= $this->oForm->endFormTable ();
		$form .= $this->endForm ( $this->oForm );
		$form .= $this->oForm->close ();

		// Zend_Form start
		$form = formToString($this->oForm, $form);
		
		$form .=  "<div id=\"synthese_result\"></div>";
		
        
    	
    	// window.parent.$('#frame_right_top').html('" . t('BOFORMS_TRANSLATE_SITE_GROUP') . "');
		
		$html = '<script type="text/javascript">';
		
		$html .= "function checkDateValid() {
			dateStart = $('#date_start').val();
			dateEnd = $('#date_end').val();
			
			// checks date format
			// format attendu: 04/01/2006
			var reg1 = /^[0-9]{2}[/]{1}[0-9]{2}[/]{1}[0-9]{4}$/g;
			var reg2 = /^[0-9]{2}[/]{1}[0-9]{2}[/]{1}[0-9]{4}$/g;
			
			var res1 = reg1.test(dateStart);
			var res2 = reg2.test(dateEnd);
			
			if (! res1) {
				alert('invalid date start');
				return false;
			}
			if (! res2) {
				alert('invalid date end');
				return false;
			}
			
			if (res1 && res2) {
				var tbl_start = dateStart.split('/');
				var tbl_end = dateEnd.split('/');
				
				var dateStart = new Date(tbl_start[2] + '-' + tbl_start[1] + '-' + tbl_start[0]);
				var dateEnd = new Date(tbl_end[2] + '-' + tbl_end[1] + '-' + tbl_end[0]);
				
				// checks if datestart is before dateend
				if (dateStart <= dateEnd) {
				 	return true;
				} else {
					alert('date end before date start');
					return false;
				}
			}
			
			alert('invalid date');
			return false;
		} ";
		
		$html .= "$( document ).ready(function() {

				// set top title
				window.parent.$('#frame_right_top').html('" . t('BOFORMS_REPORTINGSYNTHESE') . "');
				 
				$('#date_start, #date_end').on('change', function()  { 
	 				if ($('#date_start').val() != '' && $('#date_end').val() != '' && checkDateValid()) {
	 				$('#synthese_result').html('' );
					loaderAjax('synthese_result');
					$('#synthese_result' ).dialog('open');
						$.get( '/_/module/boforms/BoForms_Administration_ReportingSynthese/getSynthese?dateStart=' + $('#date_start').val() + '&dateEnd=' + $('#date_end').val(),
 					   		function( data ) {
								$('#synthese_result').html( data );
					   	});
					}	
	     	     });
	     	     $('#date_start, #date_end').on('keydown', function(e)  { 
	 					if(e.keyCode == 13 && $('#date_start').val() != '' && $('#date_end').val() != '' && checkDateValid()){
				            // loads the datas
				            $('#synthese_result').html('');
							loaderAjax('synthese_result');
							$('#synthese_result' ).dialog('open');
				            $.get( '/_/module/boforms/BoForms_Administration_ReportingSynthese/getSynthese?dateStart=' + $('#date_start').val() + '&dateEnd=' + $('#date_end').val(),
	 					    function( data ) {
								$('#synthese_result').html( data );
						    });	
						    
						    // block the event
						    e.preventDefault(); 
				            return false;
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
    
   /**
     * RécurpÃ¨re le xml d'une instance via le webService
     * @param string $code_instance
     * 
     */
    private function getLeadsByType($dateStart, $dateEnd)
    {
    	try {
    		$serviceParams = array(
    				'country' => FunctionsUtils::getCodePays(),
					'brand' => Pelican::$config['BOFORMS_BRAND_ID'],
					'dateStart' => $dateStart,
    				'dateEnd' => $dateEnd
    		);
    		 
    		$service = \Itkg\Service\Factory::getService(Pelican::$config['BOFORMS_BRAND_ID'] . '_SERVICE_BOFORMS', array());
    		
    		$response = $service->call('getLeadsByType', $serviceParams);
    		
    		return $response;
    	} catch(\Exception $e) {
    		echo $e->getMessage();
    	}
    }
    
}