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

/*** PHPExcel ***/
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/phpExcel/Classes/PHPExcel.php'); //excel
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/excel.ini.php');//colonnes excel Pelican::$config['BOFORMS_EXCEL_COLUMNS']


/**
 * Formulaire état des formulaire par pays
 *
 */

class BoForms_Administration_ReportingStatusFormsByCountry_Controller extends Pelican_Controller_Back
{
	protected $administration = true;

    protected $form_name = "boforms_etat_forms_pays";
    protected $zoneid ="";

    protected $aOpportuniteExclues = array("'LANDING_PAGE'","'LANDING_PAGE_1'","'LANDING_PAGE_2'","'CLAIMS_ABOUT_YOUR_CAR'" , "'CLAIMS_ABOUT_YOUR_DEALER'", "'CLAIMS_ABOUT_DOCUMENTATION'", "'CLAIMS_OTHER'"); //JIRA 307
    protected $aProOnly = array('UNSUBSCRIBE_NEWSLETTER_B2B', 'REQUEST_A_CONTACT_BUSINESS'); // tableau des opportunité sans type part
    protected $aPartOnly = array('UNSUBSCRIBE_NEWSLETTER_CNIL', 'UNSUBSCRIBE_NEWSLETTER_CREDIPAR','UNSUBSCRIBE_NEWSLETTER_EMAILING', 
    							'UNSUBSCRIBE_NEWSLETTER_AMEX', 'SUBSCRIBE_NEWSLETTER', 'UNSUBSCRIBE_NEWSLETTER', 
    							'CLAIMS', 'REQUEST_AN_INFORMATIONS'); // tableau des opportunité sans type pro
    
    private function setTypeExclude($country_code)
    {
    	
    	if($country_code!='FR')
    	{
	    	$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_AMEX'";
	    	$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_CREDIPAR'";
	    	$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_B2B'";
	    	$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_CNIL'";
	    	$this->aOpportuniteExclues[] = "'UNSUBSCRIBE_NEWSLETTER_EMAILING'";
    	}
    	
    	
    	
    }
    
    private function getDateFormat()
    {
    	if($_GET['date_selected'])
    	{
    		//2015-01-11 18:36:46
    		
    		$aTab=explode('/', $_GET['date_selected']);
    		return $aTab[2].'-'.$aTab[1].'-'.$aTab[0].' 23:59:59';	
    	}else{
    		return date('Y-m-d 23:59:59');
    	}    	
    	 
    }
    
    public function listAction () {
    	
    	if(!FunctionsUtils::checkBlockEdito())
    	{
    		die ('error, this feature is not available.');
    	}
    	
		$head = $this->getView()->getHead();
		$head->setMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
		$head->setCss(Pelican_Plugin::getMediaPath('boforms') . 'js/qtip/jquery.qtip.css');
    	$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js');
    	$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery-ui.min.js');
    	$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/qtip/jquery.qtip.js');
    	
    	$html = '<br/>';	
    
    	$date = $this->getDateFormat();
       	
    	//parent::listAction();
    	
    	$oConnection = Pelican_Db::getInstance ();
    	/*récupère le bloc BO d'administration des Formulaire*/
    	$sSQL = "SELECT ZONE_ID FROM #pref#_zone where ZONE_BO_PATH = '".Pelican::$config['BOFORMS_BLOC_EDITO_FORMS']."'";
    	$this->zoneid = $oConnection->queryItem($sSQL);
    	
    	
    	$site_code_fr = $oConnection->queryItem("SELECT SITE_ID FROM `psa_site_code` where site_code_pays = 'FR'");
    	
    	
    	$this->oForm = Pelican_Factory::getInstance('Form', false);
    	
    	$form .= $this->oForm->open(Pelican::$config["DB_PATH"]);
    	$form .= $this->beginForm($this->oForm);
    	$this->oForm->bDirectOutput = false;
    	$form .= $this->oForm->beginFormTable();
    	//$form = $this->startStandardForm();
    	
    	$strControl = "shortdate";
    	$form .= $this->oForm->createInput("date_selected", "Date", 10, $strControl, false, ($_GET['date_selected']?$_GET['date_selected']:date('d/m/Y')), false, 10);
    	
    	
    	
    	$form .= $this->oForm->endTab ();
    	$form .= $this->beginForm ( $this->oForm );
    	$form .= $this->oForm->beginFormTable ();
    	$form .= $this->oForm->endFormTable ();
    	$form .= $this->endForm ( $this->oForm );
    	$form .= $this->oForm->close ();
    	// Zend_Form start
    	$form = formToString($this->oForm, $form);
    	

    	
    	// window.parent.$('#frame_right_top').html('" . t('BOFORMS_TRANSLATE_SITE_GROUP') . "');
    	$html .= '<script type="text/javascript">';
    	$html .= "$( document ).ready(function() {
    			// set top title
    			window.parent.$('#frame_right_top').html('" . str_replace("'", "\'", t('BOFORMS_REPORTINGSTATUSFORMSBYCOUNTRY')) . "'); 
    	
				 $('#date_selected').on('change', function()  {
	 				window.location.href = '".Pelican::$config["DOCUMENT_HTTP"].$_SERVER['REQUEST_URI']."&date_selected='+$('#date_selected').val();
	     	     });
	 						
	 			$('#date_selected').on('keydown', function(e)  { 
	 					if(e.keyCode == 13){
				            // loads the datas
				           window.location.href = '".Pelican::$config["DOCUMENT_HTTP"].$_SERVER['REQUEST_URI']."&date_selected='+$('#date_selected').val();
						    
						    // block the event
						    e.preventDefault(); 
				            return false;
				        }
	     	     });			
	 						
    	     });";
    	$html .= '</script>';
    	
    	
    	
    	$fieldLibellePays="nom_fr_fr";
    	if($_SESSION[APP]['LANGUE_ID']!=1)
    	{
    		$fieldLibellePays= "nom_en_gb";
    	}
    	
    	if($_SESSION[APP]['PROFIL_LABEL'] != "ADMINISTRATEUR")
    	{
    		$filtrePays= " WHERE SITE_ID = ".$_SESSION[APP]['SITE_ID']." ";
    	}
    	
    	$SqlPays = "SELECT UPPER(SITE_CODE_PAYS) as SITE_CODE_PAYS,SITE_ID,$fieldLibellePays as name
    				FROM #pref#_site_code sc
    				INNER join #pref#_boforms_country bc on (UPPER(bc.alpha2)=UPPER(sc.SITE_CODE_PAYS))
    				$filtrePays 
    				order by SITE_CODE_PAYS";
    	
    	$aPays = $oConnection->queryTab($SqlPays);

    	    	
    	if(count($aPays)==1)
    	{
    		$this->setTypeExclude($aPays[0]['SITE_CODE_PAYS']);
    	}
    	    	
		$SqlLanguePays = "SELECT CULTURE_KEY, sl.LANGUE_ID
		FROM #pref#_site_language sl
		INNER join #pref#_boforms_culture bc on (sl.LANGUE_ID=bc.LANGUE_ID)
		WHERE SITE_ID=:SITE_ID";

		foreach ($aPays as $pays)
		{
			$aBind[':SITE_ID'] = $pays['SITE_ID'];
			$aLangue[$pays['SITE_ID']] = $oConnection->queryTab($SqlLanguePays,$aBind);
		}
    	
		
    	$SqlFormType = "SELECT *
    					FROM #pref#_boforms_opportunite
						WHERE OPPORTUNITE_KEY NOT IN (".implode(", ",$this->aOpportuniteExclues).")
    					 order by OPPORTUNITE_ID asc"; //JIRA 307
    	
    	$aFormType = $oConnection->queryTab($SqlFormType);
    	
    	$aTarget[1]= "Part";
    	$aTarget[2]= "Pro";
		
    	$table = "<style>
#table_dashboard td {border:1px solid black;font-size:12px;padding: 4;}
ul {list-style-type:none;}
.h2_legend {margin-bottom: 3px;margin-top:0;;font-size:12px;}
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
    
    width:800px;
    margin-left: 190px;
	
	position:static;
}

.wrapper1 { 
	background-color: white;
	overflow-x:scroll;
    overflow-y:visible;
    
    width:800px;
    margin-left: 190px;
	
	position:static;
	height: 20px;
}

.div1 {
   width:1000px;
   height: 20px;
}


#table_dashboard {
	background-color: white;
	width:100%;border-collapse:collapse;
}

td {
    padding: 5px 20px;
    width: 150px;
}

th:first-child {
	position: absolute;
    left: 2px;
    width:180px;
    font-size:11px;
    
    
    overflow: hidden;
    text-overflow: ellipsis;
	white-space: nowrap;
	
	padding: 4;
	
	
	border:none;
	border-top: 1px solid black;
}


#th_noborder {
	
}


#table_dashboard_culture td {border:0px solid black;border-collapse:collapse;}
				</style>
    			<form target='iframeExcel'  action='/_/module/boforms/BoForms_Administration_ReportingStatusFormsByCountry/export'>
    				<input style='margin-bottom:10px;' class='button' type='submit' value='Exporter au format Excel'/>
    			</form>
    			
    			<div class='".($_SESSION[APP]['PROFIL_LABEL'] == "ADMINISTRATEUR"?'wrapper1':'')."'><div class=\"div1\"></div></div>
    			<div class='calendar'><div class='wrapper2'>
    			<table id='table_dashboard' style='text-align:center;border-collapse:collapse;'>
    				<tr>
    					<th style='font-weight:bold; font-size:12px;'>".t('BOFORMS_SUPPORT_COUNTRY')."</th> ";

    		foreach ($aFormType as $type)
    		{
    			$table .= "<td  colspan='2' style='width:130px; font-weight:bold;font-size:12px;padding:5px;padding:5px;'>".t('BOFORMS_REFERENTIAL_FORM_TYPE_'.$type['OPPORTUNITE_KEY'])."</td>";
    		}
    		   		   	
    	$table .= "</tr>";
    	
    	$table .="<tr><th id='th_noborder'>&nbsp;</th>";
    	
    	foreach ($aFormType as $type)
    	{
    		if(in_array($type['OPPORTUNITE_KEY'], $this->aProOnly))
	    	{
	    		$table .= "<td style='font-weight:bold;'  colspan='2'>Pro </td>";
	    	}
	    	elseif(in_array($type['OPPORTUNITE_KEY'], $this->aPartOnly))
	    	{
	    		$table .= "<td style='font-weight:bold;' colspan='2'>Part </td>";
	    	}
	    	else
	    	{
	    		$table .= "<td style='font-weight:bold;'>Part</td>";
    			$table .= "<td style='font-weight:bold;'>Pro</td>";
    		}
    	}
    	    	
    	$table .="</tr>";

    	
    	$n=0;
    	foreach ($aPays as $k=>$pays)
    	{
    		
    		
    		if(count($aLangue[$pays['SITE_ID']])>0)
    		{
    			$n++;

    			$title_text = '';
    			if (strlen($pays['name']) >= 31 ) {
    				$title_text = " title='" . $pays['name'] . "' ";
    			}
    			
	    		$table .= "<tr ".(($n%2)==1?"style='background-color:#E4EEF5;'":'').">";
	    			$table .= "<th style='font-weight:bold; font-size:12px;padding:3px;'><a id='".$pays['SITE_ID']."' href='#' $title_text class='opener'>";
	    				$table .= $pays['name'];
	    			$table .= "</a>
	    					<input type='hidden' value='".$pays['name']."' id='country_name_".$pays['SITE_ID']."' />
	    					<input type='hidden' value='".$pays['SITE_CODE_PAYS']."' id='country_code_".$pays['SITE_ID']."' />
	    					</th>";
	    			
	    			foreach ($aFormType as $type)
	    			{
	    				
	    				$tableTarget="";
	    				
	    					foreach($aTarget as $k=>$target)
	    					{
	    						    //cas particuliers des formulaires uniques - JIRA 307	    							
	    							if(
	    								!(in_array($type['OPPORTUNITE_KEY'], $this->aPartOnly) && $target == 'Pro'  )
	    								&&
	    								!(in_array($type['OPPORTUNITE_KEY'], $this->aProOnly) && $target == 'Part'  )
	    							)
	    							{
	    								if((in_array($type['OPPORTUNITE_KEY'], $this->aProOnly) && $target == 'Pro' )|| (in_array($type['OPPORTUNITE_KEY'], $this->aPartOnly) && $target == 'Part'  )){$colspan="colspan='2'";}
	    								else{ $colspan ="";}
					    				$tableTarget = "<table id='table_dashboard_culture' style='text-align:center; width:100%;border-collapse:collapse;'>
					    							<tr>";
					    				
					    				if(count($aLangue[$pays['SITE_ID']])>1)
					    				{
						    				foreach($aLangue[$pays['SITE_ID']] as $klangue=>$langue)
						    				{
						    					$tableTarget .= "<td style='font-size:10px;padding:4px;border-bottom:1px dotted black;".($klangue>0?'border-left:1px dotted black;':'')."'>".$langue['CULTURE_KEY']."-".$pays['SITE_CODE_PAYS']."</td>";
						    				}
					    				}
					    				$tableTarget .= "</tr>
					    							<tr>";
					    				 
					    				foreach($aLangue[$pays['SITE_ID']] as $klangue=>$langue)
						    			{
						    					$target_id = $k;
						    						
						    					$bPublishedFO=$this->setFormsPublishedFO($date,$type['OPPORTUNITE_ID'],$pays['SITE_ID'],$langue['LANGUE_ID'],$k);

						    					if($bPublishedFO)
						    					{
						    						$tableTarget .= "<td style='font-size:12px;color:green; font-weight:bold;".($klangue>0?'border-left:1px dotted black;':'')."'>V</td>";
						    					}else{
						    						$tableTarget .= "<td style='font-size:12px;color:red; font-weight:bold;".($klangue>0?'border-left:1px dotted black;':'')."'>X</td>";
						    					}
						    				
					    				}
					    				
					    				$tableTarget .="</tr>
					    						</table>";

					    				$table .= "<td $colspan  >$tableTarget</td>";
					    			}
			    					
			    				
	    					}
		    		    				
	    			}
	    			
	    		$table .= "</tr>";
    		}
    	}
    	
    	$table .= "
    			</table></div></div>
    			";

    	
    
    	
    	
  
	
    	$JS .= "<script type='text/javascript'>";
    	
    	$JS .= " $( document ).ready(function() {";
    	
    	$JS .= "
			// fix width of the top scroll div 
			$('.div1').css('width', $('#table_dashboard').css('width'));
						
			// set scroll listener
			$('.wrapper1').scroll(function(){
				$('.wrapper2').scrollLeft($('.wrapper1').scrollLeft());
			});
    			
			$('.wrapper2').scroll(function(){
				$('.wrapper1').scrollLeft($('.wrapper2').scrollLeft());
			});
    	    	
	    	$( '#dialog' ).dialog({
	    		autoOpen: false,
	    		width:700, height: 450,
	    		modal: false, dialogClass: 'dialogContactSupport',
	    		open: function (event, ui) {
	    			
	    		},
	    		close: function (event, ui) {
	    		// empty the iframe
	    		$('#dialog').html('/_/module/boforms/BoForms_Administration_SupportRequest/emptyTask');
	    		},
	    		buttons: {
	    		'close': function() {
	    			$(this).dialog('close');
	    		}
	    		}
	    		});
    			
    		$( '#dialog2' ).dialog({
	    		autoOpen: false,
	    		width:100, height: 100,
	    		modal: false, dialogClass: 'dialogContactSupport',
	    		open: function (event, ui) {
	    			
	    		},
	    		close: function (event, ui) {
	    		// empty the iframe
	    		$('#dialog2').html('');
	    		}
	    		});
	    		
			$('.opener' ).click(function() {
	    					
    			$('#selected_country_id').val(this.id);
    			var country_id= $('#selected_country_id').val();
    			var country_code = $('#country_code_'+$('#selected_country_id').val()).val();
    			var country_name = $('#country_name_'+$('#selected_country_id').val()).val();
    			var timestamp = ".strtotime($date).";
	    					
	    		var datas = { 'country_id' : country_id , 'country_code': country_code, 'country_name': country_name, 'timestamp': timestamp};	

    				
    					
    			$('#dialog').html('' ); 
    			loaderAjax('dialog');	
    			$('#dialog' ).dialog('open');		
    					
	    		$.ajax({
						type: 'GET',
						url: '/_/module/boforms/BoForms_Administration_ReportingStatusFormsByCountry/popinCountry',
						data: datas,
						success: function( data ) { 
							$('#dialog').html(data ); 
    						
    					},
						dataType: 'html'
					});			 			
	    		    				
    			    			
						    	
	    		});
    					
    		 	
    					
			";
    	
    	$JS .= " });
    			
    			";
    	
    	$JS .= '</script>
    	<input type="hidden" value="" id="selected_country_id" />			
    	<div id="dialog" title="'.t('BOFORMS_TITLE_POPIN_COUNTRY').'"></div>
    	    		
    			';
    	/*echo $JS;
    	
    	echo "<h2>".t('BOFORMS_ETATFORMSPARPAYS')."</h2>";
    	echo $table;*/
    	
    	$form .= $JS;
    	//$form .= "<h2>".t('BOFORMS_ETATFORMSPARPAYS')."</h2>";
    	$form .= $table;
    	
    	$form .=  "<iframe width='100%' style='visibility:hidden;display:none;'  height='500' id='iframeExcel' name='iframeExcel'></iframe> ";
    	
    	$this->aButton["save"]="";
    	$this->aButton["back"]="";
    	Backoffice_Button_Helper::init($this->aButton);
    	
    	// Zend_Form stop
    	$this->setResponse($html . $form);
    }
        
    public function popinCountryAction()
    {
    	
    	$oConnection = Pelican_Db::getInstance ();

    	$site_code_fr = $oConnection->queryItem("SELECT SITE_ID FROM `psa_site_code` where site_code_pays = 'FR'");
    	$site_id = $_GET['country_id'];
    	
    		$form .= "<form target='iframeExcelPopin'  action='/_/module/boforms/BoForms_Administration_ReportingStatusFormsByCountry/exportPopin'>
    				<input style='margin-bottom:10px;' class='button' type='submit' value='Exporter au format Excel'/>
    				<input type='hidden' name='country_name' value='".$_GET['country_name']."'/>
    				<input type='hidden' name='timestamp' value='".$_GET['timestamp']."'/>
    				<input type='hidden' name='country_id' value='".$_GET['country_id']."'/>
   				<input type='hidden' name='country_code' value='".$_GET['country_code']."'/>
    			</form>";
		$form .= "<table border=1 id='table_dashboard' style='text-align:center;border-collapse:collapse;'>";

			$form .= "<tr>";
			$form .= "<td>Pays=".$_GET['country_name']."</td>";
		
		for($j=11;$j>=0;$j--)
		{
			$dateCurrent  = mktime(0, 0, 0, date("m",$_GET['timestamp'])-$j,   01,   date("Y",$_GET['timestamp']));
		    $form .= "<td colspan='2' style='padding:3px;font-weight:bold;'>".date("Y",$dateCurrent)."</td>";	      
		}
		
		$form .= "</tr>";
		$form .= "<tr>";
		
			$form .= "<td rowspan='2' style='font-weight:bold;'>Formulaires</td>";
			
			for($j=11;$j>=0;$j--)
			{
				$dateCurrent  = mktime(0, 0, 0, date("m",$_GET['timestamp'])-$j,   01,   date("Y",$_GET['timestamp']));
				$form .= "<td colspan='2' style='font-weight:bold;'>".Pelican::$config['BOFORMS_DATE_PREF'][$_SESSION[APP]['LANGUE_ID']][(int)date("m",$dateCurrent)]."</td>";
			}
			
		$form .= "</tr>";
			
		$form .= "<tr>";
					
			for($j=11;$j>=0;$j--)
			{
				$form .= "<td style='font-weight:bold;border-right:1px dotted black;color:green'>V</td>";
				$form .= "<td style='font-weight:bold;border-left:1px dotted black;color:red'>X</td>";
			}
		
		$form .= "</tr>";
		
		
		$SqlLanguePays = "SELECT CULTURE_KEY, sl.LANGUE_ID
		FROM #pref#_site_language sl
		INNER join #pref#_boforms_culture bc on (sl.LANGUE_ID=bc.LANGUE_ID)
		WHERE SITE_ID=:SITE_ID";
		
		$aBind[':SITE_ID'] = $site_id;
		$aLangue = $oConnection->queryTab($SqlLanguePays,$aBind);
		
		
		$this->setTypeExclude($_GET['country_code']);
		
		$sSqlType = "SELECT OPPORTUNITE_ID,OPPORTUNITE_KEY FROM #pref#_boforms_opportunite WHERE OPPORTUNITE_KEY NOT IN (".implode(", ",$this->aOpportuniteExclues).")";//JIRA 307
		$aFormType = $oConnection->queryTab($sSqlType);
		
		foreach ($aFormType as $type)
		{
			foreach ($aLangue as $langue)
			{
				if(!in_array($type['OPPORTUNITE_KEY'], $this->aProOnly)){
					$form .= "<tr style='background-color:#E4EEF5;'>";
					$form .="<td style='font-weight:bold;'>".t('BOFORMS_REFERENTIAL_FORM_TYPE_'.$type['OPPORTUNITE_KEY'])." Part".(count($aLangue)>1?" (".$langue['CULTURE_KEY'].'-'.$_GET['country_code'].")":'')."</td>";
					
					for($j=11;$j>=0;$j--)
					{
						$dateCurrent  = mktime(0, 0, 0, date("m",$_GET['timestamp'])-$j,   01,   date("Y",$_GET['timestamp']));
						$aRes = $this->calculeCompteur($dateCurrent,$type['OPPORTUNITE_ID'],$langue['LANGUE_ID'],$site_id,'IND');
												
						$form .="<td style='border-right:1px dotted black;'>".($aRes['iEnabled']>0?"<a href='#' class='tooltip' title='".$this->setListDate($aRes['listDateEnabled'])."'  >".$aRes['iEnabled']."</a>":$aRes['iEnabled'])."</td>
								 <td style='border-left:1px dotted black;'>".($aRes['iDisabled']>0?"<a href='#' class='tooltip' title='".$this->setListDate($aRes['listDateDisabled'])."' >".$aRes['iDisabled']."</a>":$aRes['iDisabled'])."</td>";
					}
					$form .= "</tr>";
				}
			}
			foreach ($aLangue as $langue)
			{		
				if(!in_array($type['OPPORTUNITE_KEY'], $this->aPartOnly)){
					$form .= "<tr>";
					$form .= 	"<td style='font-weight:bold;'>".t('BOFORMS_REFERENTIAL_FORM_TYPE_'.$type['OPPORTUNITE_KEY'])." Pro".(count($aLangue)>1?" (".$langue['CULTURE_KEY'].'-'.$_GET['country_code'].")":'')."</td>";
					
					for($j=11;$j>=0;$j--)
					{
						$dateCurrent  = mktime(0, 0, 0, date("m",$_GET['timestamp'])-$j,   01,   date("Y",$_GET['timestamp']));
						$aRes = $this->calculeCompteur($dateCurrent,$type['OPPORTUNITE_ID'],$langue['LANGUE_ID'],$site_id,'PRO');
						$form .="<td style='border-right:1px dotted black;'>".($aRes['iEnabled']>0?"<a href='#' class='tooltip'  title='".$this->setListDate($aRes['listDateEnabled'])."'>".$aRes['iEnabled']."</a>":$aRes['iEnabled'])."</td>
								 <td style='border-left:1px dotted black;'>".($aRes['iDisabled']>0?"<a href='#' class='tooltip' title='".$this->setListDate($aRes['listDateDisabled'])."'>".$aRes['iDisabled']."</a>":$aRes['iDisabled'])."</td>";
					}
					
					$form .= "</tr>";
				}
			}
			
		}
					
		$form .= "<table>";
		
		
		$form .=  "<iframe width='100%' style='visibility:hidden;display:none;'  height='500' id='iframeExcelPopin' name='iframeExcelPopin'></iframe> ";
		echo $form;
		
		
		echo "
				<style>
				
				.sizeInit{
					
					max-height:165px;
					overflow-y:scroll;
    			}
				</style>
				
				<script type='text/javascript'>
				
				$('.tooltip').qtip({ // Grab some elements to apply the tooltip to
					    content: {
					        text: $(this).attr('title')
					    },
						hide: {
		                fixed: true,
		                delay: 300
		            },
					style: {
				        classes: 'qtip-light qtip-shadow sizeInit'
						
				    }
					})	
				
				</script>
				";
				
    }
    
    private function setListDate($aListDate)
    {
    	if(is_array($aListDate) && !empty($aListDate))
    	{
    		
    		return implode('<br />', $aListDate);
    		 		
    	}else{
    		return false;
    	}
    }
    

    private function calculeCompteur($dateCurrent,$formType_id,$langue_id,$site_id,$target)
    {
    	
    	$formType_id = FunctionsUtils::getFormTypeCPPByFormOpportunite($formType_id);
	     	
    	$iEnabled=0;
    	$iDisabled=0;
    	
    	$oConnection = Pelican_Db::getInstance ();
    	
    	$month = (int)date('m',$dateCurrent);
    	$year = (int)date('Y',$dateCurrent);
    	    
    	$zoneid=$this->zoneid;

    	if(!$zoneid)
    	{
    		$sSQL = "SELECT ZONE_ID FROM #pref#_zone where ZONE_BO_PATH = '".Pelican::$config['BOFORMS_BLOC_EDITO_FORMS']."'";
    		$this->zoneid = $oConnection->queryItem($sSQL);
    		$zoneid=$this->zoneid;
    	}
    	
    	$aTargetMapping = array('CHOIX'=>'#Tar#', 'PRO'=>2, 'IND'=>1);
    	$aDeviceMapping = array('1'=>'0', '2'=>'1', '3'=>'#Dev#');//1 ou 3 == web tablette activé
    	
    	

    	if(Pelican::$config['BOFORMS_STATE_HISTORY'])
    	{
    		$sSQL = "SELECT PAGE_ID,PAGE_VERSION,HISTORY_DEVICE as DEVICE, HISTORY_TYPE as OPPORTUNINTE, HISTORY_TARGET as TARGET, HISTORY_DATE as PAGE_VERSION_UPDATE_DATE
	    			 FROM #pref#_boforms_state_history 
	    			 WHERE  SITE_ID = ".$site_id."
	    			 AND ZONE_ID = $zoneid
	    			 AND LANGUE_ID = $langue_id
	    			 AND STATE_ID = 4
					 AND PAGE_ID IN (SELECT PAGE_ID from #pref#_boforms_state_history  WHERE ZONE_ID = $zoneid AND LANGUE_ID = $langue_id AND HISTORY_TYPE=$formType_id)
	    			 AND MONTH(HISTORY_DATE) = $month  
	    			 AND YEAR(HISTORY_DATE) = $year  
	    			 ORDER BY PAGE_ID,HISTORY_DATE asc
	    			";
    		
    		$sSQLInitVersion = "SELECT PAGE_ID,PAGE_VERSION,HISTORY_DEVICE as DEVICE, HISTORY_TYPE as OPPORTUNINTE, HISTORY_TARGET as TARGET,HISTORY_DATE as PAGE_VERSION_UPDATE_DATE
	    			 FROM #pref#_boforms_state_history 
	    			 WHERE  SITE_ID = ".$site_id."
    			    			 AND ZONE_ID = $zoneid
    			    			 AND LANGUE_ID = $langue_id
    			    			 AND STATE_ID = 4
    			    			 AND PAGE_ID = :PAGE_CURRENT
    			    			 AND PAGE_VERSION < :PAGE_VERSION_CURRENT
    			    			 ORDER BY PAGE_ID,HISTORY_DATE asc
    			    			 LIMIT 1
    			    			 ";
    	}else{
    	
	    	$sSQL = "SELECT pv.PAGE_ID,pv.PAGE_VERSION,ZONE_ATTRIBUT as DEVICE, ZONE_TITRE3 as OPPORTUNINTE, ZONE_TITRE4 as TARGET, PAGE_VERSION_UPDATE_DATE
	    			 FROM #pref#_page_multi_zone pmz
	    			 INNER JOIN  #pref#_page_version pv ON (pmz.PAGE_ID=pv.PAGE_ID AND pmz.LANGUE_ID=pv.LANGUE_ID AND pmz.PAGE_VERSION=pv.PAGE_VERSION)
	    			 INNER JOIN #pref#_page p ON (pv.PAGE_ID=p.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID)
	    			
	    			 WHERE  p.SITE_ID = ".$site_id."
	    			 AND ZONE_ID = $zoneid
	    			 AND pmz.LANGUE_ID = $langue_id
	    			 AND STATE_ID = 4
					 AND pv.PAGE_ID IN (SELECT PAGE_ID from #pref#_page_multi_zone WHERE ZONE_ID = $zoneid AND pmz.LANGUE_ID = $langue_id AND ZONE_TITRE3=$formType_id)
	    			 AND MONTH(PAGE_VERSION_UPDATE_DATE) = $month  
	    			 AND YEAR(PAGE_VERSION_UPDATE_DATE) = $year  
	    			 ORDER BY p.PAGE_ID,PAGE_VERSION_UPDATE_DATE asc
	    			";
	    	
	    	$sSQLInitVersion = "SELECT pv.PAGE_ID,pv.PAGE_VERSION,ZONE_ATTRIBUT as DEVICE, ZONE_TITRE3 as OPPORTUNINTE, ZONE_TITRE4 as TARGET, PAGE_VERSION_UPDATE_DATE
	    			 FROM #pref#_page_multi_zone pmz
	    			 INNER JOIN  #pref#_page_version pv ON (pmz.PAGE_ID=pv.PAGE_ID AND pmz.LANGUE_ID=pv.LANGUE_ID AND pmz.PAGE_VERSION=pv.PAGE_VERSION)
	    			 INNER JOIN #pref#_page p ON (pv.PAGE_ID=p.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID)
	    
	    			 WHERE  p.SITE_ID = ".$site_id."
	    	    			 AND ZONE_ID = $zoneid
	    	    			 AND pmz.LANGUE_ID = $langue_id
	    	    			 AND STATE_ID = 4
	    	    			 AND pv.PAGE_ID = :PAGE_CURRENT
	    	    			 AND pv.PAGE_VERSION < :PAGE_VERSION_CURRENT
	    	    			 ORDER BY p.PAGE_ID,PAGE_VERSION_UPDATE_DATE asc
	    	    			 LIMIT 1
	    	    			 ";
    	}
    	
    	/*
    	 * AND MONTH(PAGE_VERSION_UPDATE_DATE) = $month  
    			 AND YEAR(PAGE_VERSION_UPDATE_DATE) = $year  
    	 * 
    	 * 
    	AND ZONE_ATTRIBUT > 0 
    	AND ZONE_TITRE3 = $formType_id
    	AND (ZONE_TITRE4 = \"CHOIX\" OR ZONE_TITRE4 = \"$target\")
    	 * */
    	
    	$aPages = $oConnection->queryTab($sSQL);
    	
    	

    	if(is_array($aPages) && !empty($aPages))
    	{
    		foreach($aPages as $k=>$page)
    		{
    			$aPagesByID[$page['PAGE_ID']][] = $page;
    		}
    		
    		$i=0;
    		
    		    		
    		foreach ($aPagesByID as $aPage)
    		{
    			
	    		foreach ($aPage as $k=>$page)
	    		{
	    			
	    			if($k==0)
	    			{
	    				$aBind[':PAGE_CURRENT'] = $page['PAGE_ID'];
	    				$aBind[':PAGE_VERSION_CURRENT'] = $page['PAGE_VERSION'];
	    				$Tab = $oConnection->queryRow($sSQLInitVersion,$aBind);
	    				
	    				
	    				if(!empty($Tab))
	    				{
	    					$aPage[$k-1]=$Tab;
	    				}else{
	    					
	    					if((int)$page['OPPORTUNINTE']==$formType_id && !empty($page['TARGET']) && ((int)$page['DEVICE']>0))
	    					{
	    						
	    						$iEnabled ++;
	    						$aDate['iEnabled'][]=$page['PAGE_VERSION_UPDATE_DATE'];
	    					}
	    					
	    				}
	    			}
	    			    			
	    			
	    			if(!empty($aPage[$k-1]))
	    			{
	    				
	    				if(($page['OPPORTUNINTE']==$formType_id && ($page['TARGET']==$target || $page['TARGET']=="CHOIX") && $page['DEVICE']>0) && 
	    				   ($aPage[$k-1]['OPPORTUNINTE']!=$formType_id || ($aPage[$k-1]['TARGET']!=$target && $aPage[$k-1]['TARGET']!="CHOIX") || $aPage[$k-1]['DEVICE']==0 ))
	    				{
	    					$iEnabled ++;
	    					$aDate['iEnabled'][]=$page['PAGE_VERSION_UPDATE_DATE'];
	    				}
    					    				
	    				if(($page['OPPORTUNINTE']!=$formType_id || ($page['TARGET']!=$target && $page['TARGET']!="CHOIX") || $page['DEVICE']==0) &&
	    					($aPage[$k-1]['OPPORTUNINTE']==$formType_id && ($aPage[$k-1]['TARGET']==$target || $aPage[$k-1]['TARGET']=="CHOIX") && $aPage[$k-1]['DEVICE']>0))
	    				{
	    					$iDisabled ++;
	    					$aDate['iDisabled'][]=$page['PAGE_VERSION_UPDATE_DATE'];
	    				}
	    			}	
	    			$i++;
	    		}
    		}
    	}
    	    	
    	return array('iEnabled'=>$iEnabled,'iDisabled'=>$iDisabled, 'listDateEnabled'=> $aDate['iEnabled'], 'listDateDisabled'=> $aDate['iDisabled']);
       	
	    				   	
    }
    
    private function setFormsPublishedFO($date,$FormType_id,$site_id, $langue_id,$target_id)
    {
    	    	
    	if($FormType_id == '13' || $FormType_id == '14' || $FormType_id == '15')
    		return true;
    	
    	$oConnection = Pelican_Db::getInstance ();
    	/*** vérifie les formualire publié en FO ***/
    	  
    	/*récupère le bloc BO d'administration des Formulaire*/
    	$zoneid=$this->zoneid;
    	    
    	 
    	/*récupère les données saisi dans le bloc Formulaire*/
    	$Today=date("Y-m-d 23:59:59");

    	if(Pelican::$config['BOFORMS_STATE_HISTORY'] && $Today != $date)
    	{
    		$sSQL = "
    		SELECT * FROM (
	    	SELECT PAGE_ID,HISTORY_DEVICE as DEVICE, HISTORY_TYPE as OPPRTUNINTE, HISTORY_TARGET as TARGET
	    	FROM #pref#_boforms_state_history sh	
	    	WHERE SITE_ID = ".$site_id."
    			    	AND ZONE_ID = $zoneid
    			    	AND LANGUE_ID = $langue_id
    			    	AND STATE_ID = 4
    			    	AND HISTORY_DATE <=  \"$date\"
    			    	ORDER BY PAGE_ID,PAGE_VERSION DESC
    			    	) as temp
    		GROUP BY PAGE_ID
    			    	";
    	}else{
    	
	    	$sSQL = "
	    	SELECT * FROM (		
		    	SELECT p.PAGE_ID,ZONE_TITRE ,ZONE_ATTRIBUT as DEVICE, ZONE_TITRE3 as OPPRTUNINTE, ZONE_TITRE4 as TARGET
		    	FROM #pref#_page_multi_zone pmz 
				INNER JOIN #pref#_page_version pv ON (pmz.PAGE_ID=pv.PAGE_ID AND pmz.LANGUE_ID=pv.LANGUE_ID AND pmz.PAGE_VERSION=pv.PAGE_VERSION)
				INNER JOIN #pref#_page p ON (pv.PAGE_ID=p.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID)
		    	
		    	WHERE p.SITE_ID = ".$site_id."
		    	AND ZONE_ID = $zoneid
	    		AND pmz.LANGUE_ID = $langue_id
		    	AND STATE_ID = 4
		    	AND PAGE_VERSION_UPDATE_DATE <=  \"$date\"
		    	ORDER BY p.PAGE_ID,pv.PAGE_VERSION DESC
		    	) as temp
		    GROUP BY PAGE_ID
	    	";
    	
    	}
    			 
    			$aPageZone = $oConnection->queryTab($sSQL);
    		
    			 
    			/*constuction du code instance*/
    			$codePays=FunctionsUtils::getCodePays();
    			$brand=Pelican::$config['BOFORMS_BRAND_ID'];
    			 
    			$aTargetMapping = array('CHOIX'=>'#Tar#', 'PRO'=>2, 'IND'=>1);
    			$aDeviceMapping = array('1'=>'0', '2'=>'1', '3'=>'#Dev#');
    			 
    			if(is_array($aPageZone) && !empty($aPageZone))
    			{
    					foreach ($aPageZone as $k=>$pagezone)
    					{
    						
    						$pagezone['OPPRTUNINTE'] =  FunctionsUtils::getOpportuniteByFormTypeCPP($pagezone['OPPRTUNINTE']);
    						
    						
    						if($FormType_id == $pagezone['OPPRTUNINTE'] && ($aTargetMapping[$pagezone['TARGET']]==$target_id || $pagezone['TARGET']=='CHOIX') && ($pagezone['DEVICE']))
    						{
    							return true;
    						}
    							    						
    					}
    			}
    
    			return false;
    		
    }
    
    public function editAction ()
    { 	
    	 parent::editAction();
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        
        $form .= $this->oForm->open(Pelican::$config["DB_PATH"]);
        $form .= $this->beginForm($this->oForm);
        $this->oForm->bDirectOutput = false;
        $form .= $this->oForm->beginFormTable();
        //$form = $this->startStandardForm();
		
        $strControl = "shortdate";
        $form .= $this->oForm->createInput("date_selected", "Date", 10, $strControl, false, date('d/m/Y'), false, 10);
        
        $form .= $this->oForm->endTab ();
		$form .= $this->beginForm ( $this->oForm );
		$form .= $this->oForm->beginFormTable ();
		$form .= $this->oForm->endFormTable ();
		$form .= $this->endForm ( $this->oForm );
		$form .= $this->oForm->close ();

		// Zend_Form start
		$form = formToString($this->oForm, $form);
		
		$form .=  "<div id=\"synthese_result\">ici, on a la synthèse</div>";
		
        // Zend_Form stop
		$this->setResponse($form);
    	
    	// window.parent.$('#frame_right_top').html('" . t('BOFORMS_TRANSLATE_SITE_GROUP') . "');
		echo '<script type="text/javascript" src="' . Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js' . '"></script>';
		echo '<script type="text/javascript">';
		echo "$( document ).ready(function() {
				 $('#date_selected').on('change', function()  { 
	 				$.get( '/_/module/boforms/BoForms_Administration_ReportingSynthese/getSynthese?date=' + $('#date_selected').val(),
	 					   function( data ) {
								$('#synthese_result').html( data );
						   });	
	     	     });
    	     });";
		echo '</script>';
    	
		
    }
    
    public function exportAction(){
    	
    	$oConnection = Pelican_Db::getInstance ();
    	/*récupère le bloc BO d'administration des Formulaire*/
    	$sSQL = "SELECT ZONE_ID FROM #pref#_zone where ZONE_BO_PATH = '".Pelican::$config['BOFORMS_BLOC_EDITO_FORMS']."'";
    	$this->zoneid = $oConnection->queryItem($sSQL);
    	
    	// gets site code for Francia
		$site_code_fr = $oConnection->queryItem("SELECT SITE_ID FROM `psa_site_code` where site_code_pays = 'FR'");
    	    	
		$aTarget = array( 'part'=>1, 'pro'=>2);
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()	->setCreator("BOForms")
						->setLastModifiedBy("BOForms")
						->setTitle("Office 2007 XLSX BOForms ReportingStatusFormsByCountry")
						->setSubject("Office 2007 XLSX BOForms ReportingStatusFormsByCountry")
						->setDescription("BOForms ReportingStatusFormsByCountry")
						->setCategory("BOForms ReportingStatusFormsByCountry");

		
    								


	    	$fieldLibellePays="nom_fr_fr";
	    	if($_SESSION[APP]['LANGUE_ID']!=1)
	    	{
	    		$fieldLibellePays= "nom_en_gb";
	    	}
    	
	    	if($_SESSION[APP]['PROFIL_LABEL'] != "ADMINISTRATEUR")
	    	{
	    		$filtrePays= " WHERE SITE_ID = ".$_SESSION[APP]['SITE_ID']." ";
	    	}
	    	
	    	$SqlPays = "SELECT UPPER(SITE_CODE_PAYS) as SITE_CODE_PAYS,SITE_ID,$fieldLibellePays as name
	    				FROM #pref#_site_code sc
	    				INNER join #pref#_boforms_country bc on (UPPER(bc.alpha2)=UPPER(sc.SITE_CODE_PAYS)) 
	    				$filtrePays
	    				order by SITE_CODE_PAYS";
	    	
	    	$aPays = $oConnection->queryTab($SqlPays);


			$SqlLanguePays = "SELECT CULTURE_KEY, sl.LANGUE_ID
			FROM #pref#_site_language sl
			INNER join #pref#_boforms_culture bc on (sl.LANGUE_ID=bc.LANGUE_ID)
			WHERE SITE_ID=:SITE_ID";

			foreach ($aPays as $pays)
			{
				$aBind[':SITE_ID'] = $pays['SITE_ID'];
				$aLangue[$pays['SITE_ID']] = $oConnection->queryTab($SqlLanguePays,$aBind);
			}
	    	
			if(count($aPays)==1)
			{
				$this->setTypeExclude($aPays[0]['SITE_CODE_PAYS']);
			}			
		
	    	$SqlFormType = "SELECT *
	    					FROM #pref#_boforms_opportunite WHERE OPPORTUNITE_KEY NOT IN (".implode(", ",$this->aOpportuniteExclues).") order by OPPORTUNITE_ID asc";//JIRA 307
	    	
	    	$aFormType = $oConnection->queryTab($SqlFormType);
		
    		$objPHPExcel	->createSheet();
		$objPHPExcel	->setActiveSheetIndex(0)
				->setTitle("Status Forms By Country")
				->setCellValue('A1', 'PAYS')
				->setCellValue('B1', 'LANGUE-CULTURE');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		//création des entêtes de fichiers 
		$index=3;
		$aColonne = Pelican::$config['BOFORMS_EXCEL_COLUMNS'];
		foreach($aFormType as $formType)
		{
			foreach ($aTarget as $key => $target)
			{
				if( !(in_array($formType['OPPORTUNITE_KEY'], $this->aPartOnly) && $target == 2  )
					&&
					!(in_array($formType['OPPORTUNITE_KEY'], $this->aProOnly) && $target == 1  )
	    		){
					$objPHPExcel	->getActiveSheet()
							->setCellValue($aColonne[$index].'1', t('BOFORMS_REFERENTIAL_FORM_TYPE_'.$formType['OPPORTUNITE_KEY']). " ". $key);
					$objPHPExcel->getActiveSheet()->getColumnDimension($aColonne[$index])->setAutoSize(true);
					$index++;
				}
			}
		}
		
		//ajout des données 
		$index=1;
		$ligne=2;

		foreach($aPays as $pays)
		{
			foreach($aLangue[$pays['SITE_ID']] as $langue)
			{	$objPHPExcel	->getActiveSheet()
						->setCellValue($aColonne[$index].$ligne,$pays['name']);
				$index++;
				$objPHPExcel	->getActiveSheet()
						->setCellValue($aColonne[$index].$ligne,$langue['CULTURE_KEY']);
				$index++;
				foreach($aFormType as $formType)
				{		
					foreach ($aTarget as $target)
					{
						if(!(in_array($formType['OPPORTUNITE_KEY'], $this->aPartOnly) && $target == 2  )
							&&
							!(in_array($formType['OPPORTUNITE_KEY'], $this->aProOnly) && $target == 1  )
	    				){
	    					$etatInstance = $this->setFormsPublishedFO($this->getDateFormat(),$formType['OPPORTUNITE_ID'],$pays['SITE_ID'], $langue['LANGUE_ID'],$target);
							$status = ($etatInstance)?'V':'X';
							
							$objPHPExcel	->getActiveSheet()
									->setCellValue($aColonne[$index].$ligne, $status);
							
							if($etatInstance=='V')
							{
								$styleArray = array(
									'font'  => array(
										'color' => array('rgb' => '458B00')
								));
							} else {
								$styleArray = array(
									'font'  => array(
										'color' => array('rgb' => 'FF0000')
								));
							}

							$objPHPExcel->getActiveSheet()->getStyle($aColonne[$index].$ligne)->applyFromArray($styleArray);
							$index++;
						}
					}
				}
				$index=1;
				$ligne++;
			}
			
		}
				
    		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="BOForms ReportingStatusFormsByCountry.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;	
    }
    
    public function exportPopinAction()
    {
    	
    	$oConnection = Pelican_Db::getInstance ();
    	/*récupère le bloc BO d'administration des Formulaire*/
    	$sSQL = "SELECT ZONE_ID FROM #pref#_zone where ZONE_BO_PATH = '".Pelican::$config['BOFORMS_BLOC_EDITO_FORMS']."'";
    	$this->zoneid = $oConnection->queryItem($sSQL);
    	
    	// gets site code for Francia
		$site_code_fr = $oConnection->queryItem("SELECT SITE_ID FROM `psa_site_code` where site_code_pays = 'FR'");
    	
    	
    	$aColonne = Pelican::$config['BOFORMS_EXCEL_COLUMNS'];
    	
    	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()	->setCreator("BOForms")
					->setLastModifiedBy("BOForms")
					->setTitle("Office 2007 XLSX BOForms ReportingStatusFormsByCountry")
					->setSubject("Office 2007 XLSX BOForms ReportingStatusFormsByCountry")
					->setDescription("BOForms ReportingStatusFormsByCountry")
					->setCategory("BOForms ReportingStatusFormsByCountry");
	$objPHPExcel	->createSheet();
	
	//en-tetes
	$objPHPExcel	->setActiveSheetIndex(0)
			->setTitle("Status Forms By Country")
			->setCellValue('A1', "Pays=".$_GET['country_name']);

	$index=2;
	$ligne=1;

	for($k=11;$k>=0;$k--) // mois années
	{	$dateCurrent  = mktime(0, 0, 0, date("m",$_GET['timestamp'])-$k,   01,   date("Y",$_GET['timestamp']));	
		$objPHPExcel	->getActiveSheet()
				->setCellValue($aColonne[$index].$ligne , 
						Pelican::$config['BOFORMS_DATE_PREF'][$_SESSION[APP]['LANGUE_ID']][(int)date("m",$dateCurrent)]. " " 
						.date("Y",$dateCurrent)
						." - ".t('BOFORMS_INFO_ACTIVATED')) ;
		$objPHPExcel->getActiveSheet()->getColumnDimension($aColonne[$index])->setAutoSize(true);
		
		$index++;
		$objPHPExcel	->getActiveSheet()
				->setCellValue($aColonne[$index].$ligne , 
						Pelican::$config['BOFORMS_DATE_PREF'][$_SESSION[APP]['LANGUE_ID']][(int)date("m",$dateCurrent)]. " " 
						.date("Y",$dateCurrent)
						." - ".t('BOFORMS_INFO_DISABLED'));
		
		$objPHPExcel->getActiveSheet()->getColumnDimension($aColonne[$index])->setAutoSize(true);
		
		$index++;
	}

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	//données
	
	$site_id = $_GET['country_id'];

	$SqlLanguePays = "SELECT CULTURE_KEY, sl.LANGUE_ID
	FROM #pref#_site_language sl
	INNER join #pref#_boforms_culture bc on (sl.LANGUE_ID=bc.LANGUE_ID)
	WHERE SITE_ID=:SITE_ID";
	
	$aBind[':SITE_ID'] = $site_id;

	$aLangue = $oConnection->queryTab($SqlLanguePays,$aBind);
	
	$this->setTypeExclude($_GET['country_code']);
	
	$sSqlType = "SELECT OPPORTUNITE_ID,OPPORTUNITE_KEY FROM #pref#_boforms_opportunite WHERE OPPORTUNITE_KEY NOT IN (".implode(", ",$this->aOpportuniteExclues).")";//JIRA 307
	$aFormType = $oConnection->queryTab($sSqlType);

	
	$ligne=2;
	foreach ($aFormType as $type)
	{
		$index=1;
		foreach ($aLangue as $langue)
		{
			if(!in_array($type['OPPORTUNITE_KEY'], $this->aProOnly)){
				$objPHPExcel	->getActiveSheet()
						->setCellValue($aColonne[$index].$ligne , t('BOFORMS_REFERENTIAL_FORM_TYPE_'.$type['OPPORTUNITE_KEY'])." Part".(count($aLangue)>1?" (".$langue['CULTURE_KEY'].'-'.$_GET['country_code'].")":''));
				$index++;

				for($j=11;$j>=0;$j--)
				{								
					$dateCurrent  = mktime(0, 0, 0, date("m",$_GET['timestamp'])-$j,   01,   date("Y",$_GET['timestamp']));
					$aTab=$this->calculeCompteur($dateCurrent,$type['OPPORTUNITE_ID'],$langue['LANGUE_ID'],$site_id,'IND');
					
					$objPHPExcel	->getActiveSheet()
							->setCellValue($aColonne[$index].$ligne ,$aTab['iEnabled']);
					$index++;
					$objPHPExcel	->getActiveSheet()
							->setCellValue($aColonne[$index].$ligne ,$aTab['iDisabled']);
					$index++;
				}
				$ligne++;	
				$index=1;
			}
		}
		foreach ($aLangue as $langue)
		{
			if(!in_array($type['OPPORTUNITE_KEY'], $this->aPartOnly)){
				$objPHPExcel	->getActiveSheet()
						->setCellValue($aColonne[$index].$ligne , t('BOFORMS_REFERENTIAL_FORM_TYPE_'.$type['OPPORTUNITE_KEY'])." Pro".(count($aLangue)>1?" (".$langue['CULTURE_KEY'].'-'.$_GET['country_code'].")":''));
				$index++;
				
				for($j=11;$j>=0;$j--)
				{
					$dateCurrent  = mktime(0, 0, 0, date("m",$_GET['timestamp'])-$j,   01,   date("Y",$_GET['timestamp']));
					$aTab=$this->calculeCompteur($dateCurrent,$type['OPPORTUNITE_ID'],$langue['LANGUE_ID'],$site_id,'PRO');
					
					$objPHPExcel	->getActiveSheet()
						->setCellValue($aColonne[$index].$ligne ,$aTab['iEnabled']);
					$index++;	
					$objPHPExcel	->getActiveSheet()
						->setCellValue($aColonne[$index].$ligne , $aTab['iDisabled']);
					
					$index++;
				}
				$ligne++;
				$index=1;
			}
		}
		
	}
//die();
	/**/	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	//$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="BOForms ReportingStatusForms_'.$_GET['country_name'].'.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
    }

}

