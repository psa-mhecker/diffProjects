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

// UC 603: Journal d'activité

/**
 * Formulaire de gestion des Formulaires
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author RaphaÃƒÂ«l Carles <rcarles@businessdecision.com>
 * @since 15/01/2014
 */

class BoForms_Administration_ReportingActivity_Controller extends Pelican_Controller_Back
{
	protected $administration = true;

    protected $form_name = "boforms_reporting_synthese";
	
    protected $tbl_month_keys = array(
		'01' => 'BOFORMS_SHORT_MONTHS_JANUARY',
		'02' => 'BOFORMS_SHORT_MONTHS_FEBRUARY',
		'03' => 'BOFORMS_SHORT_MONTHS_MARCH', 
		'04' => 'BOFORMS_SHORT_MONTHS_APRIL',
		'05' => 'BOFORMS_SHORT_MONTHS_MAY', 
		'06' => 'BOFORMS_SHORT_MONTHS_JUNE', 
		'07' => 'BOFORMS_SHORT_MONTHS_JULY', 
		'08' => 'BOFORMS_SHORT_MONTHS_AUGUST', 
		'09' => 'BOFORMS_SHORT_MONTHS_SEPTEMBER',
		'10' => 'BOFORMS_SHORT_MONTHS_OCTOBER', 
		'11' => 'BOFORMS_SHORT_MONTHS_NOVEMBER',
		'12' => 'BOFORMS_SHORT_MONTHS_DECEMBER'
    );
    
    public function listGroupeAction ()
    {
    	$this->listOrder="GROUPE_ID";
        $this->listModel = "
        					SELECT bg.GROUPE_ID, GROUPE_LABEL
        				    FROM #pref#_boforms_groupe bg
        				    INNER JOIN #pref#_boforms_formulaire_site bfs on (FORMSITE_ID_MASTER=FORMSITE_ID) 
        				    WHERE bg.SITE_ID = " . $_SESSION[APP]['SITE_ID'];
        	
    	parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        //$table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", array("FORMSITE_LABEL"));
        $table->getFilter(1);
        $table->setCSS(array(
            "tblalt1",
            "tblalt2"
        ));
        
        $table->setValues($this->getListModel(), "bg.GROUPE_ID");
        
       
        $oConnection = Pelican_Db::getInstance ();
    	 
    	$sqlGroupe = "SELECT FORMSITE_KEY
    				  FROM #pref#_boforms_formulaire_site fs
    				  INNER JOIN #pref#_boforms_groupe_formulaire gf on gf.FORMSITE_ID = fs.FORMSITE_ID
    				  INNER JOIN #pref#_boforms_groupe g on g.GROUPE_ID = gf.GROUPE_ID
    				  WHERE g.GROUPE_ID = :GROUPE_ID AND SITE_ID = " . $_SESSION[APP]['SITE_ID'] . "
    				  ORDER BY fs.FORMSITE_ID
    				  ";
    	   	
    	
        
        if(is_array($table->aTableValues) && !empty($table->aTableValues))
        {
        	foreach ($table->aTableValues as $k=>$row)
        	{
        		$sSitegroupe="";
        		$aSitegroupe=array();
        		$aBind[':GROUPE_ID']=$row['GROUPE_ID'];
        		$aTab=$oConnection->queryTab($sqlGroupe,$aBind);
        		
        		if(is_array($aTab) && !empty($aTab))
        		{ 
        			foreach ($aTab as $groupe_site)
        			{
        				$aSitegroupe[] = t('BOFORMS_FORMSITE_LABEL_' . $groupe_site['FORMSITE_KEY']); 
        			}
        			
        			$sSitegroupe = implode(", ",$aSitegroupe);
        			$table->aTableValues[$k]['GROUPE_LISTE_SITE']=$sSitegroupe;
        		}
        		
        	}
        }
        
        
        $table->addColumn(t('ID'), "GROUPE_ID", "10", "left", "", "tblheader","GROUPE_ID");
        $table->addColumn(t('LABEL'), "GROUPE_LABEL", "50", "left", "", "tblheader");
        $table->addColumn(t('BOFORMS_SITE_S'), "GROUPE_LISTE_SITE", "50", "left", "", "tblheader");
        
        $table->addInput(t('POPUP_BUTTON_SELECT'), "button", array(
            "groupe_id" => "GROUPE_ID"
        ), "center");
        
        $this->aButton["add"]="";
        Backoffice_Button_Helper::init($this->aButton);

        $site_code_pays = FunctionsUtils::getCodePays(); 
        
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
		$aMultiCulture = $oConnection->queryTab("SELECT CULTURE_ID, CULTURE_KEY, CULTURE_LABEL FROM `#pref#_site_language` AS sl INNER JOIN `#pref#_boforms_culture` AS c ON c.LANGUE_ID = sl.LANGUE_ID AND sl.SITE_ID = :SITE_ID", $aBind); 

		$js_all_cultures = '';
		foreach ($aMultiCulture as $key => $multi) { 
			$js_all_cultures .= "all_cultures.push('" . $multi['CULTURE_KEY'] . '-' . $site_code_pays . "');";
		}
        
        echo '<script type="text/javascript" src="' . Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js' . '"></script>';
		echo '<script type="text/javascript">';
		echo "$( document ).ready(function() {
				window.parent.$('#frame_right_top').html('" . t('BOFORMS_REPORTINGACTIVITY') . "');
			  });"; 

		echo '</script>';
        
	    $this->setResponse($table->getTable() . $btn1 . '&nbsp;' . $btn2 . '<br/>' . $btn_div1);
    }
    
    
    public function listAction () {
    	if(!$_GET['groupe_id'])
    	{
    		$this->_forward('listGroupe');
    	} else {
    		$url_display_forms = "/_/Index/child?tid=" . FunctionsUtils::getTemplateId('BoForms_Administration_ReportingActivity') . "&SCREEN=1&groupe_id=" . $this->getParam('groupe_id');
    		
	    	$head = $this->getView()->getHead();
			$head->setMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
			$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js');
	    	$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery-ui.min.js');
		
	    	$html = "<br/>";
	    	$html .= "<style>#table_dashboard td {border:1px solid black;font-size:12px;padding: 4px;border-collapse:collapse;}</style>";
	    	
	    	$html .= "<div id='dialog' title='".t('BOFORMS_TITLE_POPIN_REPORTINGACTIVITY')."'></div>";
	    	
	    	$date_courante = date('Y-m-d');
    		$le_mois = date('m');
			$le_annee = date('Y');
			
	    	if ($this->getParam('dateSearch') != '') {
	    		if (strlen($this->getParam('dateSearch')) == 10) {
		    		$date_courante = $this->getParam('dateSearch');
		    		$le_mois = substr($date_courante, 5, 2); 
					$le_annee = substr($date_courante, 0, 4);
	    		} else {
	    			echo '<font color="red">Date invalide: ' . $this->getParam('dateSearch') . '</font><br/>'; 
	    		}
	    	}
				    	
	    	$html .= '<input type="text" id="dateSearch" name="dateSearch" value="' . $date_courante . '" /><input type="button" name="Search" value="Search" id="searchBtn" /><br />';
	    	
	    	$html .= "<script type=\"text/javascript\">
	    			jQuery( document ).ready( function() {
		    			window.parent.$('#frame_right_top').html('" . t('BOFORMS_REPORTINGACTIVITY') . "'); 
	    			
		    			$('#popinactivitydiv input[type= \"text\"], #popinactivitydiv textarea').live('keydown', function(event) {
		    			 	var keyCode = event.keyCode || event.which; 
		    				
		    			 	if (event.ctrlKey && keyCode == 97) {
								// ctrl a
		    				} else if (event.ctrlKey && keyCode == 99 ) {
		    					// ctrl c
		    			 	} else {
		    					event.preventDefault();
		    				}
		    			});
		    			
	    				$( '#dialog' ).dialog({
				    		autoOpen: false,
				    		width:800, height: 450,
				    		modal: false, dialogClass: 'dialogContactSupport',
				    		open: function (event, ui) {
								
				    		},
				    		close: function (event, ui) {
					    		$('#dialog').html('');
				    		},
				    		buttons: {
					    		'close': function() {
				    				$(this).dialog('close');
				    			}
				    		}
			    		});
			    	
		    			$('.link_element').on('click', function() {
		    				var myid = $(this).attr('id');
		    				var opportunity_key = $('#' + myid + '_opportunity_key').val();
		    				var opportunity_id = $('#' + myid + '_opportunity_id').val();
		    				var month = $('#' + myid + '_month').val();
		    				var year = $('#' + myid + '_year').val();
		    				var target= $('#' + myid + '_target').val();
		    				var code_pays = $('#' + myid + '_code_pays').val();
		    				var langue_code = $('#' + myid + '_langue_code').val();
		    				var culture_id = $('#' + myid + '_culture_id').val();
		    				var device_key = $('#' + myid + '_device_key').val();
		    				var formsiteid = $('#formsiteid').val();
		    				var datas = { 'opportunity_key' : opportunity_key , 'opportunity_id': opportunity_id, 'month': month, 'year': year, 'formsiteid': formsiteid, 'device_key': device_key,
	    								'target': target, 'code_pays': code_pays, 'culture_id': culture_id, 'langue_code': langue_code, 'timestamp': '" .strtotime($date) . "' };			 
		    				
	    					$.ajax({
								type: 'POST',
								url: '/_/module/boforms/BoForms_Administration_ReportingActivity/popinReporting',
								data: datas,
								success: function( data ) { 
									$('#dialog').html(data ); 
		    						$('#dialog' ).dialog('open');
		    					},
								dataType: 'html'
							});			 
		    			});
		    			
		    			$('#searchBtn').on('click', function() {
							window.location = '$url_display_forms&step=listAction&dateSearch=' + $('#dateSearch').val();	    			
		    			});
	    		});";
	    	$html .= "</script>";
	    	
	    	
	    	$css_odd_lines = "background-color:#E4EEF5;";
	    	
	    	$oConnection = Pelican_Db::getInstance ();
	    	
	    	$sSQL = "SELECT t.FORM_INCE, count(*) AS nb, d.DEVICE_KEY, o.OPPORTUNITE_KEY, f.TARGET_ID, f.OPPORTUNITE_ID
	    	FROM #pref#_boforms_trace t
	    	INNER JOIN #pref#_boforms_formulaire f ON t.FORM_INCE = f.FORM_INCE
	    	INNER JOIN #pref#_boforms_opportunite o ON o.OPPORTUNITE_ID = f.OPPORTUNITE_ID
	    	INNER JOIN #pref#_boforms_device d ON d.DEVICE_ID = f.DEVICE_ID
	    	WHERE MONTH( t.TRACE_DATE ) = :MOIS
	    	AND YEAR( t.TRACE_DATE ) = :ANNEE and f.PAYS_CODE = :CODE_PAYS and f.FORM_AB_TESTING is null and  f.FORMSITE_ID = :FORMSITE_ID and
	    	f.FORM_CONTEXT = 0 and f.CULTURE_ID = :CULTURE_ID and f.FORM_BRAND = '" . Pelican::$config['BOFORMS_BRAND_ID'] . "'
	    	GROUP BY t.FORM_INCE ORDER BY t.FORM_INCE";
	    	
	    	
	    	$sqlLangue = "SELECT c.langue_code, c.langue_label,c.langue_id , a.SITE_CODE_PAYS FROM #pref#_site_code a
						  INNER JOIN #pref#_site_language b ON a.site_id = b.site_id
						  INNER JOIN #pref#_language c ON c.langue_id = b.langue_id
						  WHERE a.site_id = :SITE_ID";
	    	$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
	    	$code_pays = FunctionsUtils::getCodePays();
	   		$aBind[':CODE_PAYS'] = $oConnection->strToBind($code_pays);

	   		// gest formsite_id from groupe_id
	   		$aBind[':GROUPE_ID'] = $_GET['groupe_id'];
	   		$aBind[':FORMSITE_ID'] = $oConnection->queryItem('SELECT FORMSITE_ID_MASTER FROM psa_boforms_groupe where groupe_id = :GROUPE_ID', $aBind);
			
			$aLangues = $oConnection->queryTab($sqlLangue, $aBind);
			
			$tbl_keys = array();
	   		for ($zzz = 0; $zzz < count($aLangues); $zzz++) {
	    	 	$aBind[':LANGUE_ID'] = $oConnection->strToBind($aLangues[$zzz]['langue_id']);
		   		$aBind[':CULTURE_ID'] = $oConnection->queryItem("select CULTURE_ID from #pref#_boforms_culture where LANGUE_ID  = :LANGUE_ID", $aBind);
		   		$tbl_datas = array();
				
				// calcul d'une annee glissante
				for ($iii = 12; $iii >= 0; $iii--) {
					$the_month = date('m', mktime(0,0,0,$le_mois - $iii, 1, $le_annee));
					$the_year  = date('Y', mktime(0,0,0,$le_mois - $iii, 1, $le_annee));
					
					$aBind[':MOIS'] = $oConnection->strToBind($the_month);
					$aBind[':ANNEE'] = $oConnection->strToBind($the_year);
					
					$result = $oConnection->queryTab($sSQL, $aBind);
					
					for ($i = 0; $i < count($result); $i++) {
						if ($result[$i]['TARGET_ID'] == '1') {
							$target = 'Part';
						} else {
							$target = 'Pro';
						}
						
						$tmp_key = $result[$i]['DEVICE_KEY'] . '--' . $result[$i]['OPPORTUNITE_KEY'] . '--' . $result[$i]['TARGET_ID'];
						$tbl_keys[$tmp_key]['label'] =  t('BOFORMS_REFERENTIAL_FORM_TYPE_' . $result[$i]['OPPORTUNITE_KEY']) . ' ' . $result[$i]['DEVICE_KEY'] . ' ' . $target;
						$key = $the_month . '--' . $the_year . '--' . $tmp_key;
						
						if (isset($tbl_datas[$key])) {
							$tbl_datas[$key]['value'] = $tbl_datas[$key]['value'] + $result[$i]['nb'];
						} else {
							$tbl_datas[$key]['value'] = $result[$i]['nb'];
						}
						$tbl_datas[$key]['opportunity_key'] = $result[$i]['OPPORTUNITE_KEY'];
						$tbl_datas[$key]['opportunity_id'] = $result[$i]['OPPORTUNITE_ID'];
						$tbl_datas[$key]['target'] = $result[$i]['TARGET_ID'];
						$tbl_datas[$key]['culture_id'] = $aBind[':CULTURE_ID'];
						$tbl_datas[$key]['device_key'] = $result[$i]['DEVICE_KEY'];
					}
					
				}
				
				// headers
				$html .= "<input type=\"hidden\" id=\"formsiteid\" name=\"formsiteid\" value=\"" . $aBind[':FORMSITE_ID'] . "\" />";
				$html .= "<table id=\"table_dashboard\" style=\"width:98%;text-align:center;border-collapse:collapse;\"><tr><td style=\"background-color:#4169E1;color:#fff;font-weight:bold;\" rowspan=\"2\">" . $code_pays . '_' . $aLangues[$zzz]['langue_code'] . "</td>";
				$year1 = '';
				$year2 = '';
				$cpt_span = 0;
				for ($iii = 12; $iii >= 0; $iii--) {
					$the_year  = date('Y', mktime(0,0,0,$le_mois - $iii, 1, $le_annee));
					if ($year1 != '' && $the_year != $year1) {
						$colspan_year1 = $cpt_span;
						$colspan_year2 = 13 - $cpt_span;
						$year2 = $the_year;
						break;
					}
					$year1 = $the_year;
					$cpt_span++;
				}
				if ($year2 == '') {
					$html .= "<td style=\"font-weight:bold;background-color:#CCC;\" colspan=\"13\">$year1</td>";
				} else {
					$html .= "<td style=\"font-weight:bold;background-color:#CCC;\" colspan=\"$colspan_year1\">$year1</td>";
					$html .= "<td style=\"font-weight:bold;background-color:#CCC;\" colspan=\"$colspan_year2\">$year2</td>";
				}
				$html .= "</tr><tr>";
				for ($iii = 12; $iii >= 0; $iii--) {
					$the_month  = date('m', mktime(0,0,0,$le_mois - $iii, 1, $le_annee));
					$html .= "<td style=\"font-weight:bold;\">" . t($this->tbl_month_keys[$the_month]) . "</td>";
				}
				$html .= "</tr>";
				
				$num_line = 0;
							
				if (count($tbl_datas) == 0) {
					$html .= "<tr><td colspan=\"14\">" . t('BOFORMS_REPORTING_NODATAS') . "</td></tr>";
				} else {
					foreach ($tbl_keys as $key => $value) {
						$css = '';
						if ($num_line % 2 != 0) {
							$css = $css_odd_lines;
						}
						
						$html .= "<tr style=\"$css\"><td style=\"font-weight:bold;\">{$value['label']}</td>"; 
						// calcul d'une annee glissante
						for ($iii = 12; $iii >= 0; $iii--) {
							$the_month = date('m', mktime(0,0,0,$le_mois - $iii, 1, $le_annee));
							$the_year  = date('Y', mktime(0,0,0,$le_mois - $iii, 1, $le_annee));
							$the_key = $the_month . '--' . $the_year . '--' . $key;
							if (isset($tbl_datas[$the_key])) {
								$html .= "<td><a href='#' class='link_element' id='link{$iii}_" . $num_line . $aBind[':CULTURE_ID'] . "'>{$tbl_datas[$the_key]['value']}</a>
								 	<input type='hidden' id='link{$iii}_" . $num_line . $aBind[':CULTURE_ID'] . "_opportunity_key' value='" . $tbl_datas[$the_key]['opportunity_key'] . "'/>
								 	<input type='hidden' id='link{$iii}_" . $num_line . $aBind[':CULTURE_ID'] . "_opportunity_id' value='" . $tbl_datas[$the_key]['opportunity_id'] . "'/>
									<input type='hidden' id='link{$iii}_" . $num_line . $aBind[':CULTURE_ID'] . "_month' value='" . $the_month ."'/>
									<input type='hidden' id='link{$iii}_" . $num_line . $aBind[':CULTURE_ID'] . "_year' value='" . $the_year ."'/>
									<input type='hidden' id='link{$iii}_" . $num_line . $aBind[':CULTURE_ID'] . "_target' value='" .  $tbl_datas[$the_key]['target'] ."'/>
									<input type='hidden' id='link{$iii}_" . $num_line . $aBind[':CULTURE_ID'] . "_code_pays' value='" . $code_pays ."'/>
									<input type='hidden' id='link{$iii}_" . $num_line . $aBind[':CULTURE_ID'] . "_langue_code' value='" . $aLangues[$zzz]['langue_code'] ."'/>
									<input type='hidden' id='link{$iii}_" . $num_line . $aBind[':CULTURE_ID'] . "_culture_id' value='" . $tbl_datas[$the_key]['culture_id'] ."'/>
									<input type='hidden' id='link{$iii}_" . $num_line . $aBind[':CULTURE_ID'] . "_device_key' value='" . $tbl_datas[$the_key]['device_key'] ."'/>
								</td>";
							} else {
								$html .= "<td>&nbsp;</td>";
							}
						}
						$html .= "</tr>";
						$num_line++;
					}
				}
				$html .= "</table><br/><br/>";
				
				
	   		}
	   		$this->setResponse($html);
    	}
    }
    
    public function editAction ()
    { 	
    
    }
    
    public function popinReportingAction() {
    	$css_odd_lines = "background-color:#E4EEF5;";
    	
    	
    	echo "<style>
    		#table_dashboard {text-align:center;border-collapse:collapse;width:98%;}
			#table_dashboard td {border-collapse:collapse;
								font-size:12px;padding:4px;}
			#popinactivitydiv input[type=\"text\"], #popinactivitydiv textarea { background-color:#ece9e9; }
			</style>";
    	
    	//echo "<script type='text/javascript' src='".Pelican_Plugin::getMediaPath('boforms')."js/jquery.min.js'></script>";
    	echo "<script type=\"text/javascript\">
    			jQuery( document ).ready( function() {
	    			
    			});
    		  </script>";
    	
    	$opportunity_key = $this->getParam('opportunity_key'); // BOOK_A_TEST_DRIVE
    	$target_id = $this->getParam('target'); // Part
    	$code_pays = $this->getParam('code_pays'); // 'FR'
    	$langue_code = $this->getParam('langue_code'); // 'fr'
    	$device_key = $this->getParam('device_key');
    	
    	if ($target_id == '1') {
			$target = 'Part';
		} else {
			$target = 'Pro';
		}
    	
    	$oConnection = Pelican_Db::getInstance ();
    	
    	$sSQL = "SELECT t.FORM_INCE, t.FORM_VERSION, t.TRACE_DATE, t.USER_LOGIN, t.TRACE_CONTENT, u.USER_NAME
    	FROM #pref#_boforms_trace t
    	INNER JOIN #pref#_boforms_formulaire f ON t.FORM_INCE = f.FORM_INCE
    	INNER JOIN #pref#_boforms_opportunite o ON o.OPPORTUNITE_ID = f.OPPORTUNITE_ID
    	INNER JOIN #pref#_boforms_device d ON d.DEVICE_ID = f.DEVICE_ID
    	inner join #pref#_user u on u.USER_LOGIN = t.USER_LOGIN
    	WHERE MONTH( t.TRACE_DATE ) = :MOIS
    	AND YEAR( t.TRACE_DATE ) = :ANNEE and f.PAYS_CODE = :CODE_PAYS and f.FORM_AB_TESTING is null and f.FORMSITE_ID = :FORMSITE_ID and f.TARGET_ID = :TARGET_ID and
    	f.FORM_CONTEXT = 0 and f.CULTURE_ID = :CULTURE_ID and f.FORM_BRAND = '" . Pelican::$config['BOFORMS_BRAND_ID'] . "' and f.OPPORTUNITE_ID = :OPPORTUNITY_ID and d.DEVICE_KEY = :DEVICE_KEY
    	ORDER BY t.TRACE_DATE desc";
    	
    	$aBind[':FORMSITE_ID'] = $this->getParam('formsiteid');
    	$aBind[':MOIS'] = $oConnection->strToBind($this->getParam('month'));
		$aBind[':ANNEE'] = $oConnection->strToBind($this->getParam('year'));
		$aBind[':CULTURE_ID'] = $this->getParam('culture_id');
		$aBind[':CODE_PAYS'] = $oConnection->strToBind($code_pays);
		$aBind[':TARGET_ID'] = $target_id;
		$aBind[':OPPORTUNITY_ID'] = $this->getParam('opportunity_id');
		$aBind[':DEVICE_KEY'] = $oConnection->strToBind($device_key);
		
		$result = $oConnection->queryTab($sSQL, $aBind);
		
		echo "<div id=\"popinactivitydiv\"><table id=\"table_dashboard\"><tr><td style=\"color:#fff;font-weight:bold;background-color:#4169E1;\">&nbsp;</td><td colspan=\"3\" style=\"color:#fff;font-weight:bold;background-color:#4169E1;\">{$code_pays}_{$langue_code} / " . t('BOFORMS_REFERENTIAL_FORM_TYPE_' . $opportunity_key) . ' ' . $device_key . ' ' . $target . "</td></tr>";
		echo "<tr><td style=\"font-weight:bold;background-color:#CCC;\">&nbsp;</td><td style=\"text-align:left;font-weight:bold;background-color:#CCC;\">Date</td><td style=\"text-align:left;font-weight:bold;background-color:#CCC;\">User</td><td style=\"width:90%;font-weight:bold;background-color:#CCC;text-align:left;\">" . t('BOFORMS_POPIN_REPORTINGACTIVITY_MODIFICATION') . "</td></tr>";
		$num_line = 1;
		for ($zzz = 0; $zzz < count($result); $zzz++) {
			if ($zzz % 2 != 0) {
				$css = $css_odd_lines;
			} else {
				$css = '';
			}
			
			$tbl_date = explode('-', substr($result[$zzz]['TRACE_DATE'], 0, 10));
			
			$json = json_decode($result[$zzz]['TRACE_CONTENT']);
						
			$str = '';
		    for ($i = 0; $i < count($json); $i++) {
		    	if ($json[$i]->action == 'BOFORMS_TRACE_CHANGE_STEP_COMPONENT') {
		    		$str = $str . '<div style="text-align:left;margin-bottom:5px;"><span style="font-weight:bold">' . t($json[$i]->action) . '</span> ' . $json[$i]->label . ': ' . $json[$i]->orderStep_old . ' / ' . $json[$i]->orderStep_new . '</div>';
		    	} else if ($json[$i]->action == 'BOFORMS_TRACE_MOVE_COMPONENT') {	
		    		$table_datas = '<table style="margin-left:25px;margin-top:5px;margin-bottom:5px;border-collapse:collapse;border: 1px solid black;"><tr style="background-color:#6495ED;"><td style="font-weight:bold;">' . t('BOFORMS_BEFORE') . '</td><td style="font-weight:bold;">' . t('BOFORMS_AFTER') . '</td></tr>';
					for( $cpt = 0; $cpt < count($json[$i]->orderComponent_old); $cpt++) {
						$str_label_old = $json[$i]->orderComponent_old[$cpt]->label;
						$str_label_new = $json[$i]->orderComponent_new[$cpt]->label;
						if (strlen($str_label_old) > 100) {
							$str_label_old  = substr($str_label_old, 0, 100) . '...';
						}
						if (strlen($str_label_new) > 100) {
							$str_label_new  = substr($str_label_new, 0, 100) . '...';
						}
						$css_bold_plus = '';
						if ($json[$i]->orderComponent_old[$cpt]->code != $json[$i]->orderComponent_new[$cpt]->code) {
							$css_bold_plus = ' style="font-weight:bold;"';
						}
						$table_datas .= '<tr><td>' . $str_label_old . "</td><td $css_bold_plus>" . $str_label_new . '</td></tr>'; 
					}
		    		$table_datas .= '</table>';
		    		$str = $str . '<div style="text-align:left;margin-bottom:5px;"><span style="font-weight:bold">' . t($json[$i]->action) . '</span> ' . $json[$i]->label . ': ' . $table_datas . '</div>';
		    	} else if ($json[$i]->action == 'BOFORMS_TRACE_EDIT_STEP_COMPONENT') {
		    		$str = $str . '<div style="text-align:left;margin-bottom:5px;"><span style="font-weight:bold">' . t($json[$i]->action) . '</span> : ' . $json[$i]->value_old . ' / ' . $json[$i]->value_new . '</div>';
		    	} else if ($json[$i]->action == 'BOFORMS_TRACE_REMOVE_COMPONENT') {
		    		$str = $str . '<div style="text-align:left;margin-bottom:5px;"><span style="font-weight:bold">' . t($json[$i]->action) . '</span> ' . $json[$i]->label . '</div>';
		    	} else if ($json[$i]->action == 'BOFORMS_TRACE_EDIT_COMPONENT') { 
					$value_old = $json[$i]->value_old;
					$value_new = $json[$i]->value_new;
					$attribute = $json[$i]->attribute;
					
					if (strlen($json[$i]->label) > 70) {
						$label  = substr(trim($str_label_old), 0, 70) . '...';
					}else{
						$label = $json[$i]->label;
					}
					
										
		    		if ($json[$i]->attribute == 'align') {
		    			$label2=$this->getLabel('align');
		    			
		    			$str = $str . '<div><div style="text-align:left;margin-bottom:5px;"><span style="font-weight:bold">' . t($json[$i]->action) . '</span> ' .  
		    							$label . ' -&gt; ' . $label2 . ': </div>
		    							<span><input type="text"  style="width:45%;color:black;" value="' . $value_old . '"/></span> / <span><input type="text"  style="width:45%;color:black;" value="' . $value_new . '"/></span></div>';
		    		} else if (substr($json[$i]->component, 0, 10) == 'html_HTML_') {		    		
		    			$str = $str . '<div><div style="text-align:left;margin-bottom:5px;"><span style="font-weight:bold">' . t($json[$i]->action) . '</span> ' .  
		    							substr ($json[$i]->component,10) . ' : </div>
		    							<span><textarea  style="width:45%;color:black;">' . $value_old .  '</textarea></span> / <span><textarea  style="color:black;width:45%;">' . $value_new . '</textarea></span></div>';
		    		} else {
		    			$label2=$this->getLabel($json[$i]->field.'');
		    			$str = $str . '<div><div style="text-align:left;margin-bottom:5px;"><span style="font-weight:bold">' . t($json[$i]->action) . '</span> ' .  
		    							$label . ' -&gt; ' . $label2 . ': </div>
		    							<span><input type="text"  style="width:45%;color:black;" value="' . $value_old . '"/></span> / <span><input type="text"  style="width:45%;color:black;" value="' . $value_new . '"/></span></div>';
		    		
		    		} 
		    	} else if ($json[$i]->action  == 'BOFORMS_TRACE_ADD_COMPONENT') {
		    		$str = $str . '<div style="text-align:left;margin-bottom:5px;"><span style="font-weight:bold">' . t($json[$i]->action) . '</span> ' . $json[$i]->label . '</div>';
		    	}
		    }
		    if ($str == '') {
		    	$str = print_r($json, true);
		    }
					
			echo  "<tr style=\"$css\"><td style=\"text-align:left;margin-bottom:5px;\">$num_line</td><td>" . $tbl_date[2] . '/' . $tbl_date[1] . '/' . $tbl_date[0] . "</td><td><a href=\"#\" style=\"cursor: help;\" title=\"" . $result[$zzz]['USER_NAME'] . "\">" . $result[$zzz]['USER_LOGIN'] . "</a></td><td>$str</td></tr>";
			$num_line++;
		}
		echo "</table></div>";
    
    }
    
	function getLabel($val)
	{
		
		$label=t('BOFORMS_NODE_'.$val);
		
		if(strpos($label,'[cle1')===false)
		{
			$label = $label;
		}else{
			$label = $val;
		}
		return $label;
		
	}
       
}