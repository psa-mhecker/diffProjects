<?php
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local.ini.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/BoForms.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FormInstance.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/XMLHandle.class.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FunctionsUtils.php');


/*** WebServices***/
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/services.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Configuration.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetReportingRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetReportingResponse.php');


// DEALERSERVICE
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/DealerService.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/DealerService/Configuration.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/DealerService/Model/GeoLocalizeRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/DealerService/Model/GeoLocalizeResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/DealerService/Model/GetDealerListRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/DealerService/Model/GetDealerListResponse.php');


/** Include path **/
//ini_set('include_path', ini_get('include_path').';' . Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/phpExcel/Classes/');


/*** PHPExcel ***/
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/phpExcel/Classes/PHPExcel/IOFactory.php'); //csv

//include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/phpExcel/Classes/PHPExcel.php'); //excel

/** PHPExcel_Writer_Excel2007 */
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/phpExcel/Classes/PHPExcel/Writer/Excel2007.php'); //excel




//ajout du fichier de conf administrable en BO
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.admin.ini.php');


include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/excel.ini.php');

class BoForms_Administration_ReportingExport_Controller extends Pelican_Controller_Back
{
    protected $administration = true;

    protected $form_name = "boforms_formulaire";

    protected $bNewInstance = false;
    protected $field_id = " FORM_INCE";
    
    protected $defaultOrder = "";
    protected $aOpportuniteExclues = array(
    	"CLAIMS_ABOUT_YOUR_CAR", 
    	"CLAIMS_ABOUT_YOUR_DEALER", 
    	"CLAIMS_ABOUT_DOCUMENTATION", 
    	"CLAIMS_OTHER",
    	"LANDING_PAGE_1"
    ); //JIRA 307. on ajoute "LANDING_PAGE" et "LANDING_PAGE_2"

    private function setTypeExclude($country_code)
    {
    	if($country_code!='FR')
    	{
    		$this->aOpportuniteExclues[] = "UNSUBSCRIBE_NEWSLETTER_AMEX";
    		$this->aOpportuniteExclues[] = "UNSUBSCRIBE_NEWSLETTER_CREDIPAR";
    		$this->aOpportuniteExclues[] = "UNSUBSCRIBE_NEWSLETTER_B2B";
    		$this->aOpportuniteExclues[] = "UNSUBSCRIBE_NEWSLETTER_CNIL";
    		$this->aOpportuniteExclues[] = "UNSUBSCRIBE_NEWSLETTER_EMAILING";
    	}
    }

    protected function setListModel ()
    {   	
    	$this->listModel = "SELECT `OPPORTUNITE_ID`, `OPPORTUNITE_KEY`  FROM `#pref#_boforms_opportunite` WHERE `OPPORTUNITE_ID` in (". implode("," , $this->getParam('type')) .")";
    }
    
    protected function setEditModel ()
    {

    }
    
    public function getDateFormat($strDate,$end=false)
    {
    	$aDate = explode('/',$strDate);
    	
   		$date=$aDate[2].'-'.$aDate[1].'-'.$aDate[0];
   		
   		if($end)
   		{
   			$date .="T23:59:59";
   		}else{
   			$date .="T00:00:00";
   		}
   		
    	return $date;
    }
    
    
    public function getTitle($type, $target) {
    	
    	$title = 'BO FORMS reporting ' . t('BOFORMS_BRAND_' . Pelican::$config['BOFORMS_BRAND_ID']);
    		
    	$title .=  '_' . $type;
    		
    	if($type == 'REQUEST_A_BROCHURE' || $type == 'BOOK_A_TEST_DRIVE' || $type == 'REQUEST_AN_OFFER')
    	{
    		$title .= "_".$target;
    	}
    	
    	return $title;
    }
    
    public function getHead($arrayData) {
    	 
        if(!empty($arrayData) && is_array($arrayData)) {
            foreach ($arrayData as $key => $value) {
            	$camel=preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace('/(?!^)[[:upper:]]+/', '_'.'$0', $key));
            	$head[$key] = strtoupper($camel);
            }		
        }
    	return $head;
    }
    
    public function clearColumnN_A($leadData) {
    	$args = get_object_vars($leadData);
    	if (array($args)) {
    		foreach ($args as $key => $value) {
    			if ($value == 'N_A') {
    				unset($args[$key]);
    			}
    		}
    	}
    
    	return $args;
    }
    

    public function exportCSVAction()
    { 	
    	// memoire allouee pour l'execution
    	ini_set('memory_limit', '256M');
    	
    	// temps maximum d'execution 360 sec: 6 minutes
    	set_time_limit(360);
    	
		// patch
		if($this->getParam('customerType')=="PART")
		{
			$target = "PARTICULAR";
		}elseif($this->getParam('customerType')=="PRO")
		{
			$target = "PROFESSIONAL";
		}else if ($this->getParam('customerType')=="PART,PRO") {
			$target = "PARTICULAR,PROFESSIONAL";
		}
		
    	//récupération du site 
    	$oConnection = Pelican_Db::getInstance ();
		
    	$salePoint = ($this->getParam('salePoint') && $this->getParam('salePoint')!='null')?implode(',',json_decode($this->getParam('salePoint'))):'';
		
		$cultureJsonDecode = json_decode($this->getParam('culture'));
		
		if(is_array($cultureJsonDecode))
		{
			$culture = str_replace("all,","",implode(",",json_decode($this->getParam('culture'))));
		}else{
			$culture = json_decode($this->getParam('culture'));
		}
		
		$xml = 	$this->getReportingWS(	
				Pelican::$config['BOFORMS_BRAND_ID'], 
				(is_array(json_decode($this->getParam('siteCode'))))?implode(",",json_decode(str_replace('"all",',"",$this->getParam('siteCode')))):null, 
				$this->getParam('opportunityType'),//(is_array(json_decode($this->getParam('opportunityType'))))?str_replace("all,","",implode(",",json_decode($this->getParam('opportunityType')))):null,
				(is_array(json_decode($this->getParam('contexte'))))?str_replace("all,","",implode(",",json_decode($this->getParam('contexte')))):null,
				$culture,
				$target,
				$salePoint,
				$this->getDateFormat($this->getParam('dateStart')),
				$this->getDateFormat($this->getParam('dateEnd'),true)
				);
		
		$i = 0;
		
		if(is_array($xml->FormData) && !empty($xml->FormData))
		{
			foreach ($xml->FormData as $formData) {
				if ($formData->formType == $this->getParam('opportunityType')) {
					
					
					if(is_array($formData->leadsData->leadData) && !empty($formData->leadsData->leadData)) {
						
						foreach ($formData->leadsData->leadData as $leadData) {
							if ($this->getParam('customerType') == "PART,PRO" || $leadData->cible == $this->getParam('customerType')) {
								//$arrayData = json_decode(json_encode($leadData),true);
								$arrayData = $this->clearColumnN_A($leadData);
								
								if ($i == 0) {
									$title = $this->getTitle($this->getParam('opportunityType'),$this->getParam('TARGET'));
									header("Content-type: text charset=utf-8");
									header('Content-Disposition: attachment;filename="' . $title . '.csv"');
									header("Pragma: no-cache");
									header("Expires: 0");
									$out = fopen('php://output', 'w');
									$i = 0;
									fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
									
									fputcsv($out, $this->getHead($arrayData));
								}

								if ( (!empty($leadData->source)) && (!empty($leadData->country))) {
									fputcsv($out, $arrayData);
								}	
								$i++;							
							}
						}
						
						if(isset($out)) {
							fclose($out);
						}
						break;
					}
					
				}
			}
		}
		
		if($i==0)
		{
		    echo '<script>alert("' . t('BOFORMS_REPORTING_NODATAS') . '");</script>';
		}
		
		ini_set('memory_limit', '128M');
    }
    
    
    public function exportXLSAction()
    {
		// memoire allouee pour l'execution
    	ini_set('memory_limit', '128M');
    	ini_set('max_execution_time', '700');
    	
    	// temps maximum d'execution 720 sec: 12 minutes
    	set_time_limit(720);
    	
    	
    	$oConnection = Pelican_Db::getInstance ();
    	//$salePoint = ($this->getParam('salePoint'))?$this->getParam('salePoint'):null;
		$salePoint = ($this->getParam('salePoint') && $this->getParam('salePoint')!='null')?implode(',',json_decode($this->getParam('salePoint'))):'';
		
		$cultureJsonDecode = json_decode($this->getParam('culture'));
		
		if(is_array($cultureJsonDecode))
		{
			$culture = str_replace("all,","",implode(",",json_decode($this->getParam('culture'))));
		}else{
			$culture = json_decode($this->getParam('culture'));
		}

		$xml = 	$this->getReportingWS(	
						Pelican::$config['BOFORMS_BRAND_ID'], 
						(is_array(json_decode($this->getParam('siteCode'))))?implode(",",json_decode(str_replace('"all",',"",$this->getParam('siteCode')))):null, 
						implode(',',json_decode($this->getParam('opportunityType'))),
						(is_array(json_decode($this->getParam('contexte'))))?str_replace("all,","",implode(",",json_decode($this->getParam('contexte')))):null,
						$culture,
						(is_array(json_decode($this->getParam('customerType'))))?str_replace("all,","",implode(",",json_decode($this->getParam('customerType')))):null,
						$salePoint,
						$this->getDateFormat($this->getParam('dateStart')),
						$this->getDateFormat($this->getParam('dateEnd'),true)
					);

		$i = 0;
		
		if(is_array($xml->FormData) && !empty($xml->FormData))
		{
			$sheetIndex=0;
				
			$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
			$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
			PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
			
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();
				
			// Set document properties
			$objPHPExcel->getProperties()->setCreator("BOForms")
			->setLastModifiedBy("BOForms")
			->setTitle("Office 2007 XLSX BOForms Reporting")
			->setSubject("Office 2007 XLSX BOForms Reporting")
			->setDescription("BOForms Reporting")
			->setCategory("BOForms Reporting");
				
			$opportunityType = json_decode($this->getParam('opportunityType'));
			$target = json_decode($this->getParam('target'));
			
			$i = 0;
			foreach ($xml->FormData as $formData) {
				
				$typeForm = $formData->formType;

				if(is_array($formData->leadsData->leadData) && !empty($formData->leadsData->leadData)) {
	
					foreach ($formData->leadsData->leadData as $leadData) {
						
						//$arrayData = $this->clearColumnN_A($leadData);

						// clear column N_A
						$arrayData = get_object_vars($leadData);
				    	if (array($arrayData)) {
				    		foreach ($arrayData as $key => $value) {
				    			if ($value == 'N_A') {
				    				unset($arrayData[$key]);
				    			}
				    		}
				    	}
						
						
						if(!isset($indexSheet[$typeForm][$leadData->cible]['index'])) {
							$indexSheet[$typeForm][$leadData->cible]['index']=$sheetIndex;
							$indexSheet[$typeForm][$leadData->cible]['iteration']=1;
							
							$sheet_title = substr(t('BOFORMS_REFERENTIAL_FORM_TYPE_'.$typeForm).' '.$leadData->cible,0,31);
							$objPHPExcel	-> createSheet();
							$objPHPExcel	-> setActiveSheetIndex($sheetIndex) ->setTitle($sheet_title);
							
							//$objPHPExcel = $this->addXlsRow($objPHPExcel, $this->getHead($arrayData), $sheetIndex);
							
							// add xls row
							/*
							$lettre=0; $row=1;
					    	foreach ($this->getHead($arrayData) as $key => $colonne) {
					    		$objPHPExcel->setActiveSheetIndex($sheetIndex)
					    				->setCellValueByColumnAndRow($lettre,$row, $colonne);
					    		$lettre++;
					    	}
					    	*/

					    	$objPHPExcel->setActiveSheetIndex($sheetIndex)->fromArray(array_values($this->getHead($arrayData)), NULL, 'A1' );
					    	
							$sheetIndex++;
						}else{
							$objPHPExcel->setActiveSheetIndex($indexSheet[$typeForm][$leadData->cible]['index']);
						}
						
						if ( (!empty($leadData->source)) && (!empty($leadData->country))) {
							$indexSheet[$typeForm][$leadData->cible]['iteration']++;
							// $objPHPExcel = $this->addXlsRow($objPHPExcel, $arrayData, $indexSheet[$typeForm][$leadData->cible]['index'],$indexSheet[$typeForm][$leadData->cible]['iteration']);
							
							// 	add xls row
							//$lettre=0; 
							$row = $indexSheet[$typeForm][$leadData->cible]['iteration'];
													
							/*
							foreach ($arrayData as $key => $colonne) {
					    		$objPHPExcel->setActiveSheetIndex($indexSheet[$typeForm][$leadData->cible]['index'])
					    				->setCellValueByColumnAndRow($lettre,$row, $colonne);
					    		$lettre++;
					    	}
					    	*/
							$objPHPExcel->setActiveSheetIndex($indexSheet[$typeForm][$leadData->cible]['index'])->fromArray(array_values($arrayData), NULL, 'A' . $row);
							
							$i++;
						}
					}
					
				}
			}
		}

		if($i==0)
		{
			echo '<script>alert("' . t('BOFORMS_REPORTING_NODATAS') . '");</script>';
			exit;
		}

		$worksheet = $objPHPExcel->getSheetByName('Worksheet');
		if(is_object($worksheet)){
			$worksheet->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
		}


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		$filename = 'BO FORMS reporting ' . t('BOFORMS_BRAND_' . Pelican::$config['BOFORMS_BRAND_ID']) . '.xlsx';
		
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		
		exit;
		
    }
   // $this->addXlsRow($objPHPExcel, $arrayData, $sheetIndex);
    public function addXlsRow($objPHPExcel, $aRow, $activeSheet, $row=1)
    {
    	$lettre=0;
    	foreach ($aRow as $key => $colonne) {
    		$objPHPExcel->setActiveSheetIndex($activeSheet)
    				->setCellValueByColumnAndRow($lettre,$row, $colonne);
    		$lettre++;
    	}
    	return $objPHPExcel;
    	
    }
    
    public function validationAction()
    {
    	$html = '';

    	// tableau des cas particuliers != formulaire  sans template part ou pro (template commun)
    	$aFormTypeParticuliers = array('BOOK_A_TEST_DRIVE', 'REQUEST_A_BROCHURE','REQUEST_AN_OFFER') ;

		parent::listAction();
	    $oConnection = Pelican_Db::getInstance ();
		if(is_array($this->getParam("type")))
		{
			//type
			$aTypesTmp = array();
			foreach($this->getParam("type") as $type)
			{
				if($type != 'all')
					$aTypesTmp[] = $type;
			}

			//target
			$aTargetTmp = json_decode(str_replace('"all",', "", json_encode($this->getParam("target"))));
			
			$aDevice= array(1=>"WEB", 2 => "Mobile");

			$html .= "	<style type='text/css'> 
					.tab{
						width:100%;
						border-collapse: collapse;
						font:11px Verdana,Helvetica,Arial,sans-serif;
					}
					.tab th{
						border:solid thin #808080;
						background-color : #EFEFEF;
					}
					.tab tr, .tab td, .tab tbody {
						border:solid thin #527CAB;
						background-color : #CFD6E7;
					}
					#table_dashboard td {border:1px solid black;border-collapse:collapse;}
					#table_dashboard_culture td {border:0px solid black;border-collapse:collapse;}
				</style>";
			
			foreach ($this->getParam("sites") as $key => $site) {
				$sSitesSelected .= "'".$site. "', ";
			};
			
			$aMultiSites = $oConnection->queryTab("SELECT FORMSITE_ID,FORMSITE_KEY,FORMSITE_KEY FROM `#pref#_boforms_formulaire_site` WHERE FORMSITE_KEY IN (".$sSitesSelected." 'NULL')");
			$sSitesSelected = "";
			foreach($aMultiSites as $aSites){
				$vir = ($sSitesSelected == "")? "" : ", ";
				$sSitesSelected .=  $vir . t('BOFORMS_FORMSITE_LABEL_' . $aSites['FORMSITE_KEY']);
			}

			


			$html .=  "<br/>
			<div class='tab'><b>".t('BOFORMS_REPORTING_SELECTED_SITES').":</b> $sSitesSelected </div>
			<br><div class='tab'>
				<table class=tab id=table_dashboard_culture >
					<tr>
						<th>".t('BOFORMS_REPORTINGEXPORT_TYPES_FORM')."</th>
						<th>".t('BOFORMS_REPORTINGEXPORT_TYPE_CLIENT')."</th>
						<th>csv</th>
						<th>xls</th>
					</tr>";

				$i=0;

				foreach($aTypesTmp as $type)
					//foreach($aDevice as $device)


						foreach($aTargetTmp as $target){
							$targetLabel = $target;
							if($target == 'PARTICULAR'){
								$target = 'PART';
							}elseif ($target == 'PROFESSIONAL') {
								$target = 'PRO';
							}

							//test sur les opportunitées : cas particulier des opportunité sans templates part/pro
							$break = false;
							if(	!in_array($type, $aFormTypeParticuliers) )
							{
								$target = 'PART,PRO';
								$break = true;
							}

							$html .=  "	<tr>
									<td>".t('BOFORMS_REFERENTIAL_FORM_TYPE_'.$type)."</td>
									
									<td>". (($break) ? t('TABLE_FILTER_ALL') : t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_'.$targetLabel) )."</td>
									<td align=center>
										<form target='iframeExcel' action='". str_replace('list','exportCSV',Pelican::$config["DB_PATH"]) ."' method='post' name='formFiltre' id='formFiltre' >
											<input type=hidden name='siteCode' value= '".json_encode($this->getParam("sites"))."' />
											<input type=hidden name='opportunityType' value= '".$type."' />
											<input type=hidden name='contexte' value= '".json_encode($this->getParam("context"))."' />
											<input type=hidden name='culture' value= '".json_encode($this->getParam("culture"))."' />
											<input type=hidden name='customerType' value= '".$target."' />
											<input type=hidden name='salePoint' value= '".json_encode($this->getParam('site_geo'))."' />
											<input type=hidden name='dateStart' value= '".($this->getParam("date_from"))."' />
											<input type=hidden name='dateEnd' value= '".($this->getParam("date_to"))."' />
											<input type=hidden name='LANGUE_ID' value= '".$this->getParam("LANGUE_ID")."' />
											
											<input type=hidden name='TARGET' value= '".$target."' />

											<input style='font-weight:bold;' type='submit' value=csv />		
										</form>
									</td>";
							if($i==0){
								$i++;
								$html .= 	"<td rowspan='1000' align='center' >
										<form target='iframeExcel' action='". str_replace('list','exportXLS',Pelican::$config["DB_PATH"]) ."' method='post' name='formFiltre' id='formFiltre'>
							<input type=hidden name='siteCode' value= '".json_encode($this->getParam("sites"))."' />
							<input type=hidden name='country' value= 'country' />
							<input type=hidden name='opportunityType' value= '".json_encode($aTypesTmp)."' />
							<input type=hidden name='contexte' value= '".json_encode($this->getParam("context"))."' />
							<input type=hidden name='culture' value= '".json_encode($this->getParam("culture"))."' />
							<input type=hidden name='customerType' value= '".json_encode($aTargetTmp)."' />
							<input type=hidden name='salePoint' value= '".json_encode($this->getParam('site_geo'))."' />
							<input type=hidden name='dateStart' value= '".($this->getParam("date_from"))."' />
							<input type=hidden name='dateEnd' value= '".($this->getParam("date_to"))."' />
							<input type=hidden name='target' value= '".json_encode($this->getParam("target"))."' />
							<input type=hidden name='LANGUE_ID' value= '".$this->getParam("LANGUE_ID")."' />
							<input style='font-weight:bold;' type=submit value=xls class=\"button\"/>		
						</form>		
									</td>";	
								
							}
							$html .= 	"<tr>";

							if($break) 
								break;
						}

			$html .=  "	</table><br/>";
			$html .=  " <iframe width='100%'  height='500' id='iframeExcel' name='iframeExcel' style='display:none; visibility:hidden;'></iframe> ";
			
			$this->setResponse($html);
		}	
    }
    
    public function listAction ()
    {	
    	$valid = $this->getParam('valider');

    	if(!empty($valid))
    	{
	    	$this->_forward('validation'); 
		}
		else
		{
			parent::listAction();
			
			$site_code_pays = FunctionsUtils::getCodePays();
			
			$cOrientation = "h";
			$oConnection = Pelican_Db::getInstance ();

			echo "
			<style type='text/css'> 
				.floatLeft{
					padding:10px;
					position:relative;
					float:left;
				}
				.form_title{
					visibility:hidden;
					display:none;
				}
			</style>";
			$this->oForm = Pelican_Factory::getInstance('Form', true);

			$form .= $this->oForm->open(Pelican::$config['DB_PATH']."/list");
			$form .= $this->beginForm($this->oForm);
			$this->oForm->bDirectOutput = false;
			$form .= $this->oForm->beginFormTable();

			//Choix multiple case à cocher « Langue » pour les pays multi-langue (exemple Suisse)
			$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];

			$aMultiCulture = $oConnection->queryTab("SELECT CULTURE_ID, CULTURE_KEY, CULTURE_LABEL FROM `#pref#_site_language` AS sl INNER JOIN `#pref#_boforms_culture` AS c ON c.LANGUE_ID = sl.LANGUE_ID AND sl.SITE_ID = :SITE_ID", $aBind); 
			if(count($aMultiCulture)>1)
			{	$read=false;
				$aMultiCultureCheckBox['all']  = "<b style='font-weight:bold;'>".t('BOFORMS_REPORTINGEXPORT_SELECTIONNER_TOUT')."</b>";
				foreach($aMultiCulture as $aCulture){
					$aMultiCultureCheckBox[$aCulture['CULTURE_KEY']] = $aCulture['CULTURE_LABEL'];
				}
			}else{
				$read=true;
				foreach($aMultiCulture as $aCulture){
					$aMultiCultureCheckBox[$aCulture['CULTURE_KEY']] = $aCulture['CULTURE_LABEL'];
					$checkedValueCulture = $aCulture['CULTURE_KEY'];
				}
			}

			echo "<div class='floatLeft'><div><H2>".t('BOFORMS_REPORTINGEXPORT_LANGUES')."</H2></div>";
			foreach ($aMultiCultureCheckBox as $key => $culture) {
				$checked = ($key == $checkedValueCulture)? "checked readonly" : "";
				echo "<div><label><input type='checkbox' class='culture' value='".$key."' name='culture[]' $checked >".$culture."</label></div>";
			}
			echo "</div>";

			
			//Choix multiple case à cocher  « Site » 
			$aMultiSites = $oConnection->queryTab("SELECT fs.FORMSITE_ID, fs.FORMSITE_KEY FROM `#pref#_boforms_formulaire_site` fs 
											 	   INNER JOIN #pref#_boforms_groupe_formulaire gf on gf.FORMSITE_ID = fs.FORMSITE_ID 
											 	   INNER JOIN #pref#_boforms_groupe g on g.GROUPE_ID = gf.GROUPE_ID 
											 	   WHERE g.SITE_ID = " . $_SESSION[APP]['SITE_ID'] );
			$aMultiSitesCheckBox['all']  = "<b style='font-weight:bold;'>".t('BOFORMS_REPORTINGEXPORT_SELECTIONNER_TOUT')."</b>";
			foreach($aMultiSites as $aSites){
				$aMultiSitesCheckBox[$aSites['FORMSITE_KEY']] = t('BOFORMS_FORMSITE_LABEL_' . $aSites['FORMSITE_KEY']);
			}
			$aCheckedValuesSites = array();

			echo "<div class='floatLeft'><div><H2>".t('BOFORMS_REPORTINGEXPORT_SITES')."</H2></div>";
			foreach ($aMultiSitesCheckBox as $key => $site) {
				echo "<div><label><input type='checkbox' class='sites' value='".$key."' name='sites[]'>".$site."</label></div>";
			}
			echo "</div>";

			//Choix multiple case à cocher « Type de formulaire »
			//types de formulaires exclus de la gestion - jira 267
			
			$this->setTypeExclude($site_code_pays);
			

			$aMultiOpportunite = $oConnection->queryTab("SELECT OPPORTUNITE_ID, OPPORTUNITE_KEY FROM `#pref#_boforms_opportunite`"); 
			$aMultiOpportuniteCheckBox['all']  = "<b style='font-weight:bold;'>".t('BOFORMS_REPORTINGEXPORT_SELECTIONNER_TOUT')."</b>";
			foreach($aMultiOpportunite as $aOpportunite){
				if(!in_array($aOpportunite['OPPORTUNITE_KEY'], $this->aOpportuniteExclues))
				{
					$aMultiOpportuniteCheckBox[$aOpportunite['OPPORTUNITE_KEY']] = t('BOFORMS_REFERENTIAL_FORM_TYPE_'.$aOpportunite['OPPORTUNITE_KEY']);
				}
			}
			$aCheckedValues = array();

			echo "<div class='floatLeft'><div><H2>".t('BOFORMS_REPORTINGEXPORT_TYPES_FORM')."</H2></div>";
			foreach ($aMultiOpportuniteCheckBox as $key => $opportunity) {
				echo "<div><label><input type='checkbox' class='type' value='".$key."' name='type[]' disabled='disabled'>".$opportunity."</label></div>";
			}
			echo "</div>";

			//Choix multiple case à cocher  « Les contextes des formulaires »        
			$aMultiContexte = $oConnection->queryTab("SELECT CONTEXT_ID, CONTEXT_KEY FROM `#pref#_boforms_context`");
			$aMultiContexteCheckBox['all']  = "<b style='font-weight:bold;'>".t('BOFORMS_REPORTINGEXPORT_SELECTIONNER_TOUT')."</b>";
			foreach($aMultiContexte as $aContexte){
				$aMultiContexteCheckBox[$aContexte['CONTEXT_KEY']] = t('BOFORMS_REFERENTIAL_FORM_CONTEXT_'.$aContexte['CONTEXT_KEY']);
			}
			$aCheckedValues = array();

			//$form .= "<div class='floatLeft'>".$this->oForm->createCheckBoxFromList("context", "Les contexte(s) des formulaires", $aMultiContexteCheckBox, $aCheckedValues, true, $this->readO,$cOrientation)."</div>";
			/*echo "<div class='floatLeft'><div><H2>".t('BOFORMS_REPORTINGEXPORT_CONTEXTS_FORM')."</H2></div>";
			var_dump($aMultiContexteCheckBox);
			foreach ($aMultiContexteCheckBox as $key => $context) {
				echo "<div><label><input type='checkbox' class='context' value='".$key."' name='context[]'>".$context."</label></div>";
			}
			echo "</div>";
			*/
			echo "<input type='hidden' class='context' value='STANDARD' name='context[]'>";

		
			//Choix multiple case à cocher  « Type de client »
			$aMultiTarget = $oConnection->queryTab("SELECT TARGET_ID, TARGET_KEY FROM `#pref#_boforms_target` WHERE TARGET_KEY != 'INTERSTITIAL'"); 
			$aMultiTargetCheckBox['all']  = "<b style='font-weight:bold;'>".t('BOFORMS_REPORTINGEXPORT_SELECTIONNER_TOUT')."</b>";
			foreach($aMultiTarget as $aTarget){
				$aMultiTargetCheckBox[$aTarget['TARGET_KEY']] = t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_'.$aTarget['TARGET_KEY']);
			}
			//$form .= "<div class='floatLeft'>".$this->oForm->createCheckBoxFromList("target", "Type(s) de client", $aMultiTargetCheckBox, $aCheckedValues, true, $this->readO,$cOrientation)."</div>";	
			echo "<div class='floatLeft'><div><H2>".t('BOFORMS_REPORTINGEXPORT_TYPE_CLIENT')."</H2></div>";
			foreach ($aMultiTargetCheckBox as $key => $context) {
				echo "<div><label><input type='checkbox' class='target' value='".$key."' name='target[]'>".$context."</label></div>";
			}
			echo "</div>";
			//Choix de la période d’extraction 
			$strControl = "date";
			$form .= $this->oForm->createInput("date_from", t('BOFORMS_REPORTINGEXPORT_DATE_DEBUT_EXTRACT'), 10, $strControl, true, $this->values["etat_libelle"], $this->readO, 10);
			$form .= $this->oForm->createInput("date_to", t('BOFORMS_REPORTINGEXPORT_DATE_FIN_EXTRACT'), 10, $strControl, true, $this->values["etat_libelle"], $this->readO, 10);

			//Choix du point de vente
			$form .= $this->oForm->createInput("search_pos_2", t('BOFORMS_SEARCH_POS'), 90, "", false, "", false, 75);
			$form .= "<tr><td colspan='2'>
			<div id=\"pos_div\" style=\"display:none;\">
				<div id=\"choose_pos_title\" style=\"float:left;width:45%;text-align:center;\">" . t('BOFORMS_SEARCH_POS_CHOOSE')  . "</div>
				<div id=\"choose_pos_title2\" style=\"float:right;width:45%;text-align:center;\">" . t('BOFORMS_SEARCH_POS_CHOOSE')  . "</div>
				
				<div id=\"pos_div_left\" style=\"clear:both;float:left;overflow:auto;height:150px;width:45%;border:1px solid black;\"></div>
				<div id=\"pos_div_right\" style=\"float:right;overflow:auto;height:150px;width:45%;border:1px solid black;\"></div>
			</div>
			</td></tr>";

			$form .= $this->oForm->endTab ();
			$form .= $this->beginForm ( $this->oForm );
			$form .= $this->oForm->beginFormTable ();
			$form .= $this->oForm->endFormTable ();
			$form .= $this->oForm->createJS("if(selfCheck() == 0){ return false; }");
			echo '<script type="text/javascript"> 
				function selfCheck()
				{ 
					if($(".culture:checkbox:checked").length == 0){
					       alert("'.t('BOFORMS_REPORTING_FILL_CULTURE').'");
					       return 0;
					}					
					if($(".sites:checkbox:checked").length == 0){
					       alert("'.t('BOFORMS_REPORTING_FILL_SITE').'");
					       return 0;
					}

					// bloquer si aucune case cochée ou toutes désactivées
					if($(".type:checkbox:checked").length == 0 || 
					  ($(".type:checkbox:checked:disabled").length == $(".type:checkbox:checked").length )) {
					       alert("'.t('BOFORMS_REPORTING_FILL_TYPE').'");
					       return 0;
					}

					if($(".target:checkbox:checked").length == 0){
					       alert("'.t('BOFORMS_REPORTING_FILL_TARGET').'");
					       return 0;
					}
					return 1;
				}
				</script>';
			$form .= $this->endForm ( $this->oForm );

			$form .= $this->oForm->createSubmit("valider", "Valider", "", 65, 30);

			$form .= $this->oForm->close ();

			// Zend_Form start
			$form = formToString($this->oForm, $form);
			// Zend_Form stop
			$this->setResponse($form);

			
			$short_culture = (isset($aMultiCulture[0]['CULTURE_KEY'])) ? $aMultiCulture[0]['CULTURE_KEY'] : 'fr'; 
			$long_culture = (isset($aMultiCulture[0]['CULTURE_KEY'])) ? $aMultiCulture[0]['CULTURE_KEY'] . '-' . $site_code_pays : 'fr-FR';
			
			// jquery checkboxes
			echo '	<script type="text/javascript" src="' . Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js' . '"></script>';
			echo '	<script type="text/javascript">
				$( document ).ready(function() {
					var opt= {
				        select : function(event, ui){
				            var place_name = ui.item.id;
							
						     $.ajax({
				                url: "/_/module/boforms/BoForms_Administration_ReportingExport/geoLocalize",
				                dataType: "json",
				                type: "post",
				                data: { "brand" : "' . Pelican::$config['BOFORMS_BRAND_ID'] . '" , "consumer": "' . Pelican::$config['BOFORMS_CONSUMER'] . '", "country": "' . $site_code_pays . '", "culture": "' . $short_culture .'", "place" : place_name},
				                success: function(data1) {
				            
						    		if (data1.result.nb > 0) {
						    			latitude = data1.result.datas[0].latitude;
						    			longitude = data1.result.datas[0].longitude;
					                 
							               $.ajax({
							                url: "/_/module/boforms/BoForms_Administration_ReportingExport/getDealerList",
							                dataType: "json",
							                type: "post",
							                data: { "brand" : "' . Pelican::$config['BOFORMS_BRAND_ID'] . '" , "consumer": "' . Pelican::$config['BOFORMS_CONSUMER'] . '", "country": "' . $site_code_pays . '", "culture": "' . $short_culture .'", "latitude": latitude, "longitude": longitude, "sort": "distance"},
							                
							                success: function(data) {
							                	var the_html = "";
							                	if (data.result.nb > 0) {
							                		for (z = 0; z < data.result.datas.length; z++) {
							                			the_html = the_html + "<div class=\"site_geo_div\" style=\"border:1px solid lightblue;padding:4px;\" id=\"sitegeo_" + data.result.datas[z].sitegeo + "\"><div style=\"width:94%;display:inline-block;\">";
							                			the_html = the_html + "<div style=\"font-weight:bold;\">" + data.result.datas[z].name + "</div>";
							                			the_html = the_html + "<div style=\"color:red;font-weight:bold;\">" + data.result.datas[z].distance + " km(s)</div>";
														if (data.result.datas[z].line1 != "") {
															the_html = the_html + "<div>" + data.result.datas[z].line1 + "</div>";
														}
														if (data.result.datas[z].line2 != "") {
															the_html = the_html + "<div>" + data.result.datas[z].line2 + "</div>";
														}
														if (data.result.datas[z].line3 != "") {
															the_html = the_html + "<div>" + data.result.datas[z].line3 + "</div>";
														}
														if (data.result.datas[z].zipcode != "") {
															the_html = the_html + "<div>" + data.result.datas[z].zipcode + "</div>";
														}
							                			//	data.result.datas[z].department data.result.datas[z].region data.result.datas[z].RRDI data.result.datas[z].sitegeo
													
														the_html = the_html + "</div><div class=\"site_geo_delete\" style=\"width:5%;font-size:20px;font-weight:bold;color:green;display:inline-block;cursor: pointer;\">+</div></div>";
													}
													
													$("#pos_div").css("display", "block");
								                }
								                
							                	$("#pos_div_left").html(the_html);
							                	
							                }
							            });
				            		}
				            	}
							});
					    
					}, 

					source: function(request, response) {
							$.ajax({
								url: "/services/getflux?service=getDealerSuggestService&country=' . $site_code_pays . '&culture=' . $long_culture .'&input=" +  $("#search_pos_2").val(),
				                dataType: "xml",
				                type: "get",
				                success: function(data) {
				                	datas = data.getElementsByTagName("item");
				               		tbl_datas = [];
				                	for (zzz = 0; zzz < datas.length; zzz++) {
				               			val_item = datas[zzz].childNodes[0].nodeValue;
				               			tbl_datas.push({"id": val_item , "value": val_item });
				               		}
				                	response(tbl_datas);
				                	
				               		/*
				                	if (data.result.nb > 0) {
					                    response($.map(data.result.datas, function(item) {
						                    return {
										"id": item.latitude + " " + item.longitude, "value": item.formatted_name
											}   
					                    }));
					                } */
					                
				                }
				            });
			        },
			        minLength: 2,
			        delay: 500
					};	

					$( "#search_pos_2" ).autocomplete(opt);	
					
					$("#pos_div_right .site_geo_delete").live("click", function() {
						$(this).parent().remove();
					});
					
					$("#pos_div_left .site_geo_delete").live("click", function() {
						$(this).html("x");		
						$(this).css("color", "red");
							
						var the_html = $(this).parent().html();
						the_id = $(this).parent().attr("id");
						$(this).parent().remove();
							
						// append the block only if not already added
						if ($("#pos_div_right input[value=" + the_id + "]").length == 0) {
							the_html = "<div class=\"site_geo_div\" style=\"border:1px solid lightblue;padding:4px;\">" + 
							"<input type=\"hidden\" name=\"site_geo[]\" value=\"" + the_id.replace("sitegeo_", "") + "\"/>" + the_html + "</div>";	
							$("#pos_div_right").append(the_html);
						}
							
					});
					
					$("#search_pos_2").on("keydown", function(e)  { 
	 					if(e.keyCode == 13){
				            // block the event
						    e.preventDefault(); 
				            return false;
				        }
	     	     	});
					
						$( "input[name=\'sites[]\']" ).first().click(function(){
							if($( "input[name=\'sites[]\']" ).first().is(\':checked\')){
								$( "input[name=\'sites[]\']" ).prop(\'checked\', true);
							}else{
								$( "input[name=\'sites[]\']" ).prop(\'checked\', false);
							}			
						});
				
						$( "input[name=\'sites[]\']" ).slice(1).click(function(){
							if($( "input[name=\'sites[]\']" ).first().is(\':checked\')){
								$( "input[name=\'sites[]\']" ).first().prop(\'checked\', false);
							}
						});

				

						$( "input[name=\'context[]\']" ).first().click(function(){
							if($( "input[name=\'context[]\']" ).first().is(\':checked\')){
								$( "input[name=\'context[]\']" ).prop(\'checked\', true);
							}else{
								$( "input[name=\'context[]\']" ).prop(\'checked\', false);
							}
						});
						$( "input[name=\'context[]\']" ).slice(1).click(function(){
							if($( "input[name=\'context[]\']" ).first().is(\':checked\')){
								$( "input[name=\'context[]\']" ).first().prop(\'checked\', false);
							}
						});
				
						$( "input[name=\'type[]\']" ).first().click(function(){
							if($( "input[name=\'type[]\']" ).first().is(\':checked\')){
								$( "input[name=\'type[]\']" ).prop(\'checked\', true);
							}else{
								$( "input[name=\'type[]\']" ).prop(\'checked\', false);
							}
						});
						$( "input[name=\'type[]\']" ).slice(1).click(function(){
							if($( "input[name=\'type[]\']" ).first().is(\':checked\')){
								$( "input[name=\'type[]\']" ).first().prop(\'checked\', false);
							}
						});
				
						$( "input[name=\'target[]\']" ).first().click(function(){
							if($( "input[name=\'target[]\']" ).first().is(\':checked\')){
								$( "input[name=\'target[]\']" ).prop(\'checked\', true);
							}else{
								$( "input[name=\'target[]\']" ).prop(\'checked\', false);
							}
						});
						$( "input[name=\'target[]\']" ).slice(1).click(function(){
							if($( "input[name=\'target[]\']" ).first().is(\':checked\')){
								$( "input[name=\'target[]\']" ).first().prop(\'checked\', false);
							}
						});
				
						$( "input[name=\'culture[]\']" ).first().click(function(){
							if($( "input[name=\'culture[]\']" ).first().is(\':checked\')){
								$( "input[name=\'culture[]\']" ).prop(\'checked\', true);
							}else{
								$( "input[name=\'culture[]\']" ).prop(\'checked\', false);
							}
						});
						$( "input[name=\'culture[]\']" ).slice(1).click(function(){
							if($( "input[name=\'culture[]\']" ).first().is(\':checked\')){
								$( "input[name=\'culture[]\']" ).first().prop(\'checked\', false);
							}
						});
					});
				</script>';
		
			$this->aButton["add"]="";
			$this->aButton["save"]="";
			$this->aButton["back"]="";
			Backoffice_Button_Helper::init($this->aButton);
		}
		

		echo '<script type="text/javascript">';
		echo "$( document ).ready(function() {
				window.parent.$('#frame_right_top').html('" . t('BOFORMS_REPORTINGEXPORT') . "');
		    	$('#body_child div.form_title').html('');

			function showCheckboxLP() {
				$('input.type[value=\"BOOK_A_TEST_DRIVE\"]').attr('disabled',false);
				$('input.type[value=\"REQUEST_A_BROCHURE\"]').attr('disabled',false);
				$('input.type[value=\"REQUEST_AN_OFFER\"]').attr('disabled',false);
				$('input.type[value=\"SUBSCRIBE_NEWSLETTER\"]').attr('disabled',false);
				$('input.type[value=\"UNSUBSCRIBE_NEWSLETTER\"]').attr('disabled',false);
				$('input.type[value=\"LANDING_PAGE\"]').attr('disabled',false);
				$('input.type[value=\"LANDING_PAGE_1\"]').attr('disabled',false);
				$('input.type[value=\"LANDING_PAGE_2\"]').attr('disabled',false);
			}
			function showCheckboxCPPv2() {
				$('input.type[value=\"BOOK_A_TEST_DRIVE\"]').attr('disabled',false);
				$('input.type[value=\"REQUEST_A_BROCHURE\"]').attr('disabled',false);
				$('input.type[value=\"REQUEST_AN_OFFER\"]').attr('disabled',false);
				$('input.type[value=\"SUBSCRIBE_NEWSLETTER\"]').attr('disabled',false);
				$('input.type[value=\"UNSUBSCRIBE_NEWSLETTER\"]').attr('disabled',false);
				$('input.type[value=\"REQUEST_A_CONTACT_BUSINESS\"]').attr('disabled',false);
				$('input.type[value=\"CLAIMS\"]').attr('disabled',false);
				$('input.type[value=\"REQUEST_AN_INFORMATIONS\"]').attr('disabled',false);
				$('input.type[value=\"CLAIMS_ABOUT_YOUR_CAR\"]').attr('disabled',false);
				$('input.type[value=\"CLAIMS_ABOUT_YOUR_DEALER\"]').attr('disabled',false);
				$('input.type[value=\"CLAIMS_ABOUT_DOCUMENTATION\"]').attr('disabled',false);
				$('input.type[value=\"CLAIMS_OTHER\"]').attr('disabled',false);
				$('input.type[value=\"UNSUBSCRIBE_NEWSLETTER_AMEX\"]').attr('disabled',false);
				$('input.type[value=\"UNSUBSCRIBE_NEWSLETTER_EMAILING\"]').attr('disabled',false);
				$('input.type[value=\"UNSUBSCRIBE_NEWSLETTER_CREDIPAR\"]').attr('disabled',false);
				$('input.type[value=\"UNSUBSCRIBE_NEWSLETTER_B2B\"]').attr('disabled',false);
				$('input.type[value=\"UNSUBSCRIBE_NEWSLETTER_CNIL\"]').attr('disabled',false);
			}
			
			function hideAllFormTypes() {
				// enables all forms
				$('input.type').attr('disabled',false);
				
				// disable only known forms
				$('input.type[value=\"LANDING_PAGE\"]').attr('disabled',true);
				$('input.type[value=\"LANDING_PAGE_1\"]').attr('disabled',true);
				$('input.type[value=\"LANDING_PAGE_2\"]').attr('disabled',true);
				$('input.type[value=\"BOOK_A_TEST_DRIVE\"]').attr('disabled',true);
				$('input.type[value=\"REQUEST_A_BROCHURE\"]').attr('disabled',true);
				$('input.type[value=\"REQUEST_AN_OFFER\"]').attr('disabled',true);
				$('input.type[value=\"SUBSCRIBE_NEWSLETTER\"]').attr('disabled',true);
				$('input.type[value=\"UNSUBSCRIBE_NEWSLETTER\"]').attr('disabled',true);
				$('input.type[value=\"REQUEST_A_CONTACT_BUSINESS\"]').attr('disabled',true);
				$('input.type[value=\"CLAIMS\"]').attr('disabled',true);
				$('input.type[value=\"REQUEST_AN_INFORMATIONS\"]').attr('disabled',true);
				$('input.type[value=\"CLAIMS_ABOUT_YOUR_CAR\"]').attr('disabled',true);
				$('input.type[value=\"CLAIMS_ABOUT_YOUR_DEALER\"]').attr('disabled',true);
				$('input.type[value=\"CLAIMS_ABOUT_DOCUMENTATION\"]').attr('disabled',true);
				$('input.type[value=\"CLAIMS_OTHER\"]').attr('disabled',true);
				$('input.type[value=\"UNSUBSCRIBE_NEWSLETTER_AMEX\"]').attr('disabled',true);
				$('input.type[value=\"UNSUBSCRIBE_NEWSLETTER_EMAILING\"]').attr('disabled',true);
				$('input.type[value=\"UNSUBSCRIBE_NEWSLETTER_CREDIPAR\"]').attr('disabled',true);
				$('input.type[value=\"UNSUBSCRIBE_NEWSLETTER_B2B\"]').attr('disabled',true);
				$('input.type[value=\"UNSUBSCRIBE_NEWSLETTER_CNIL\"]').attr('disabled',true);
			}
			
			$('input.sites').on('change', function() {
				$('input.type[value=\"all\"]').attr('disabled',false);
				hideAllFormTypes();		
				
				if ($('input.sites[value=\"BRAND_SITE\"]').prop('checked') == true || 
				    $('input.sites[value=\"PERSONAL_SPACE\"]').prop('checked') == true ||
			 	    $('input.sites[value=\"CONFIGURATOR\"]').prop('checked') == true ||
			 	    $('input.sites[value=\"EDEALER\"]').prop('checked') == true ||
			 	    $('input.sites[value=\"STORE\"]').prop('checked') == true ||
			 	    $('input.sites[value=\"DERIVED_PRODUCT\"]').prop('checked') == true) {
			 	    showCheckboxCPPv2();
				}				

				if ($('input.sites[value=\"LANDING_PAGE\"]').prop('checked')) {
					chkLP = true;
					showCheckboxLP();
				}
				if ($('input.sites[value=\"LANDING_PAGE_V2\"]').prop('checked')) {
					chkLP = true;
					showCheckboxLP();
				}
				
				// gets number of lp and cppv2 sites checked
				var cpt_lp_cppv2_checked = 0;
				$('input.sites').each(function( index ) {
					if ($(this).prop('checked') == true) {
						if ($(this).val() == 'LANDING_PAGE') {
							cpt_lp_cppv2_checked++;		
    					}
						if ($(this).val() == 'LANDING_PAGE_V2') {
							cpt_lp_cppv2_checked++;		
    					}
    					if ($(this).val() == 'BRAND_SITE') {
							cpt_lp_cppv2_checked++;		
    					}
    					if ($(this).val() == 'PERSONAL_SPACE') {
							cpt_lp_cppv2_checked++;		
    					}
    					if ($(this).val() == 'CONFIGURATOR') {
							cpt_lp_cppv2_checked++;		
    					}
    					if ($(this).val() == 'EDEALER') {
							cpt_lp_cppv2_checked++;		
    					}
    					if ($(this).val() == 'STORE') {
							cpt_lp_cppv2_checked++;		
    					}
    					if ($(this).val() == 'DERIVED_PRODUCT') {
							cpt_lp_cppv2_checked++;		
    					}
					}
				});
				
				// if unknown site then we display all the available 
				if ($('input.sites:checked').length > 0 && ($('input.sites:checked').length > cpt_lp_cppv2_checked)) {
						$('input.type').attr('disabled',false);
				}

			});

		     });";
		echo '</script>';
    }

    public function editAction ()
    {
		//voir editor action
    }

	
    public function saveAction ()
    {
    	    	
    }
    
    public function getReportingWS($brand, $aSiteCode = array(), $aOpportunityType= array(), $aContexte= array(), $aCulture= array(), $aCustomerType= array(), $aSalePoint= array(), $dateStart, $dateEnd)
    {	
    	// code pays 
    	$country = FunctionsUtils::getCodePays();

    	try {
    		$serviceParams = array(
    				'brand' => $brand,
    				'siteCode' => $aSiteCode,
    				'country' => $country,
    				'opportunityType' => $aOpportunityType,
    				'contexte' => $aContexte,
    				'culture' => $aCulture,
    				'customerType' => $aCustomerType,
    				'salePoint' => $aSalePoint,
    				'dateStart' => $dateStart,
    				'dateEnd' => $dateEnd    				
    		);
			
    		$service = \Itkg\Service\Factory::getService(Pelican::$config['BOFORMS_BRAND_ID'] . '_SERVICE_BOFORMS', array());
    		
    		$response = $service->call('getReporting', $serviceParams);
			
    		return $response;
    	} catch(\Exception $e) {
    		echo $e->getMessage();
    	}
    }

   
	/**
     * Appelle le ws geolocalize
     * 
     */
    private function geoLocalize($brand,$consumer,$country,$culture, $place)
    {
    	try {
    		$serviceParams = array(
    				'brand' => $brand,
					'consumer' => $consumer,
					'country' => $country,
    				'culture' => $culture,
    				'place' => $place
    		);
    		 
    		$service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_DEALERSERVICE', array());
    		 
    		$response = $service->call('geoLocalize', $serviceParams);

    		return $response;
    	} catch(\Exception $e) {
    		echo $e->getMessage();
    	}
    }
    
    public function geoLocalizeAction() {
    	$result = $this->geoLocalize( $this->getParam('brand'),
    										$this->getParam('consumer'),
    										$this->getParam('country'),
    										$this->getParam('culture'), 
    										$this->getParam('place'));
    	$tblDatas = array();
		if ($result->Count > 0) {
			for ($i = 0; $i < count($result->Places->Place); $i++) {
				$tblDatas[] = array('country' => $result->Places->Place[$i]->Country,
                	  'department' => $result->Places->Place[$i]->Department,
					  'formatted_name' => $result->Places->Place[$i]->Formatted_Name,
                	  'latitude' => $result->Places->Place[$i]->Latitude,
					  'longitude' => $result->Places->Place[$i]->Longitude,
					  'name' => $result->Places->Place[$i]->Name,
                	  'region' => $result->Places->Place[$i]->Region);
			}
		}

		echo json_encode(array('result' => array('nb' => count($tblDatas), 'datas' => $tblDatas))); 
    	exit(0);
    }
    
    /**
     * Appelle le ws getDealerList
     * 
     */
    private function getDealerList($brand,$consumer,$country,$culture, $latitude, $longitude, $sort)
    {
    	try {
    		$serviceParams = array(
    				'brand' => $brand,
					'consumer' => $consumer,
					'country' => $country,
    				'culture' => $culture,
    				'latitude' => $latitude,
    				'longitude' => $longitude,
    				'sort' => $sort
    		);
    		 
    		$service = \Itkg\Service\Factory::getService('CITROEN_SERVICE_DEALERSERVICE', array());
    		 
    		$response = $service->call('getDealerList', $serviceParams);

    		return $response;
    	} catch(\Exception $e) {
    		echo $e->getMessage();
    	}
    }
    
	public function getDealerListAction() {
    	$result = $this->getDealerList( $this->getParam('brand'),
    										$this->getParam('consumer'),
    										$this->getParam('country'),
    										$this->getParam('culture'), 
    										$this->getParam('latitude'),
    										$this->getParam('longitude'),
    										$this->getParam('sort'));
    	 
 
    										
    										
    	$tblDatas = array();
		if ($result->Count > 0) {
			for ($i = 0; $i < count($result->DealersSimplified->DealerSimplified); $i++) {
				$tblDatas[] = array('country' => $result->DealersSimplified->DealerSimplified[$i]->Address->Country,
                	  'department' => $result->DealersSimplified->DealerSimplified[$i]->Address->Department,
					  'line1' => $result->DealersSimplified->DealerSimplified[$i]->Address->Line1,
                	  'line2' => $result->DealersSimplified->DealerSimplified[$i]->Address->Line2,
					  'line3' => $result->DealersSimplified->DealerSimplified[$i]->Address->Line3,
					  'zipcode' => $result->DealersSimplified->DealerSimplified[$i]->Address->ZipCode,
                	  'region' => $result->DealersSimplified->DealerSimplified[$i]->Address->Region,
					'distance' => $result->DealersSimplified->DealerSimplified[$i]->DistanceFromPoint,
					'name' => $result->DealersSimplified->DealerSimplified[$i]->Name,
					'RRDI' => $result->DealersSimplified->DealerSimplified[$i]->RRDI,
					'sitegeo' => $result->DealersSimplified->DealerSimplified[$i]->SiteGeo
				);
			}			
		}

		echo json_encode(array('result' => array('nb' => count($tblDatas), 'datas' => $tblDatas))); 
    	exit(0);
    	
    }
}
