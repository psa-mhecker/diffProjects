<?php
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local.ini.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/BoForms.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FormInstance.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/XMLHandle.class.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/JiraUtil.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/FunctionsUtils.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/LogXML.class.php');

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

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetReferentialRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetReferentialResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/DeleteABTestingInstanceRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/DeleteABTestingInstanceResponse.php');


//ajout du fichier de conf administrable en BO
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.admin.ini.php');

/**
 * Formulaire de gestion des Formulaires
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author RaphaÃƒÂ«l Carles <rcarles@businessdecision.com>
 * @since 15/01/2014
 */

class BoForms_Administration_Module_Controller extends Pelican_Controller_Back
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
	protected $aStepConfiguration = array(); // structure page/configuration
	protected $aTagsUnderQuestion = array();    
    
    protected $aPerso = array(); //structure niveau field
   
    protected $aPersoKey = array(); // structure niveau field sur un niveau
    
    protected $oXMLOriginal; // objet XMLHandle du formulaire avant modification
    
    protected $oXMLGeneric; // objet XMLHandle du formulaire générique
    protected $get_instance;
   
    protected $bDraft = false;
    protected $bDraftAuto = false;
    
    protected $aTypeDefaultValue = array('textbox','textarea','datepicker'); // 'date','richTextEditor','colorpicker' 
    
    var $collLog;//collection d'obet logXML
    
    protected $langue_default;

   /* protected $processus = array(
        "#pref#_boforms",
        array(
            "method",
            "BoForms_Administration_Module_Controller::saveMail"
        )
    );*/

    /*protected $decacheBack = array(
        array(
            "BoForms",
            "BOFORMS_ID"
        ),
        array(
            "BoForms/Mail",
            "BOFORMS_ID"
        )
    );*/

    protected function setListModel ()
    {
    	if(empty($_GET['groupe_id']))
    	{
    		$this->listOrder="GROUPE_ID";
        	$this->listModel = "
        					SELECT g.GROUPE_ID, GROUPE_LABEL
        				    FROM #pref#_boforms_groupe g
        				    INNER JOIN #pref#_boforms_formulaire_site fs on (g.FORMSITE_ID_MASTER = fs.FORMSITE_ID) 
        				    where g.SITE_ID = " . $_SESSION[APP]['SITE_ID'];
    	}else{   		
    		$oConnection = Pelican_Db::getInstance ();
    	 
    		$sqlGroupe = "SELECT fs.FORMSITE_KEY
    				  FROM #pref#_boforms_formulaire_site fs
    				  INNER JOIN #pref#_boforms_groupe_formulaire gf on gf.FORMSITE_ID = fs.FORMSITE_ID 
    				  WHERE gf.GROUPE_ID = :GROUPE_ID
    				  ORDER BY fs.FORMSITE_ID
    				  ";
    		
    		$codePays=FunctionsUtils::getCodePays();
    		$brand=Pelican::$config['BOFORMS_BRAND_ID'];
    		
    		$langue= FunctionsUtils::getDefaultCulture();

    		
    		if($_GET['order'])
    		{
    			switch ($_GET['order'])
    			{
    				case "DEVICE_ID":
    				case "DEVICE_ID DESC":
    					$order = ' ,OPPORTUNITE_ID,TARGET_ID, bf.FORM_CONTEXT';
    				break;	
    				case "TARGET_ID":
    				case "TARGET_ID DESC":
    					$order = ' ,OPPORTUNITE_ID,DEVICE_ID, bf.FORM_CONTEXT';
    				break;
    				case "CONTEXT_ID":
    				case "CONTEXT_ID DESC":
    					$order = ' ,OPPORTUNITE_ID,DEVICE_ID,TARGET_ID, bf.FORM_CONTEXT';
    				break;
    				case "FORM_ACTIVATED":
    				case "FORM_ACTIVATED DESC":
    					$order = ",OPPORTUNITE_ID,DEVICE_ID,TARGET_ID,bf.FORM_CONTEXT";
    				break;
    				case "OPPORTUNITE_ID":
    				case "OPPORTUNITE_ID DESC":
    					$order = ' ,DEVICE_ID,TARGET_ID, bf.FORM_CONTEXT';
    					break;
    				
    			}
    		}
    		
    	    		
    		$this->listModel = " SELECT bf.FORM_INCE,
    									bf.FORM_NAME,
    									bf.FORM_CONTEXT,
    									bf.DEVICE_ID,
    									bf.TARGET_ID,
    									bf.CULTURE_ID,
    									bf.FORMSITE_ID,
    									bf.PAYS_CODE,
    									bf.FORM_BRAND,
    									bf.OPPORTUNITE_ID,
    									bf.FORM_EDITABLE,
    									bf.FORM_COMMENTARY,
    									bc.CONTEXT_KEY,
    									bc.CONTEXT_KEY,
    									bt.TARGET_KEY,
    									bd.DEVICE_KEY,
    									FORM_EDITABLE,
    									FORM_COMMENTARY,
    									OPPORTUNITE_KEY,
    									FORMSITE_KEY,
    									FORM_PARENT_INCE,
    									FORM_ACTIVATED,
    									FORM_GENERIC
        				    FROM #pref#_boforms_formulaire bf
        				    INNER JOIN #pref#_boforms_opportunite bo on (bo.OPPORTUNITE_ID=bf.OPPORTUNITE_ID) 
        				    INNER JOIN #pref#_boforms_target bt on (bf.TARGET_ID=bt.TARGET_ID) 
        				    INNER JOIN #pref#_boforms_device bd on (bd.DEVICE_ID=bf.DEVICE_ID) 
        				    INNER JOIN #pref#_boforms_context bc on (bc.CONTEXT_ID=bf.FORM_CONTEXT) 
        				    INNER JOIN #pref#_boforms_formulaire_site bfs on (bfs.FORMSITE_ID=bf.FORMSITE_ID) 
        				    INNER JOIN #pref#_boforms_groupe bg on (bg.FORMSITE_ID_MASTER=bfs.FORMSITE_ID AND bg.GROUPE_ID=".$_GET['groupe_id'].") 
        				    WHERE ((FORM_GENERIC = 1) AND (FORM_AB_TESTING <= 0 OR FORM_AB_TESTING is null))
        				    AND FORM_BRAND = '".$brand."'		
        				    AND PAYS_CODE = '".$codePays."'	  
        				   	AND CULTURE_ID=$langue  
        				    order by ".$this->listOrder."".$order;
    	}
    }
    
    protected function setEditModel ()
    {
        if (empty($_GET['idvalues'])) {
            $this->editModel = "SELECT * from #pref#_boforms WHERE BOFORMS_ID='" . $this->id . "'";
        } else {
            $this->editModel = "SELECT * from #pref#_boforms_value WHERE BOFORMS_VALUE_ID='" . $_GET['idvalues'] . "'";
        }
    }
    
    public function getReferential($typeRef, $table, $field_id, $field_lib)
    {
    	$oConnection = Pelican_Db::getInstance ();
    	$sSql="select $field_id id, $field_lib lib FROM #pref#_$table ORDER BY $field_id";
    	$aRes = $oConnection->queryTab($sSql);
    	if(is_array($aRes))
    	{
    		foreach ($aRes as $k=>$row)
    		{
    			$aRes[$k]['lib'] = t('BOFORMS_REFERENTIAL_'.$typeRef.'_'.$row['lib']);
    		}
    	}
    	
    	return $aRes;
    }
    
    public function setFormsPublishedFO()
    {
    	$oConnection = Pelican_Db::getInstance ();
    	/*** Calcule de la colonne Info : vérifie les formualire publié en FO ***/
    	
    	/*récupère le bloc BO d'administration des Formulaire*/
    	$sSQL = "SELECT ZONE_ID FROM #pref#_zone where ZONE_BO_PATH = '".Pelican::$config['BOFORMS_BLOC_EDITO_FORMS']."'";
    	$zoneid = $oConnection->queryItem($sSQL);
    	
    	/*culture par defaut*/
    	$culture= FunctionsUtils::getDefaultCulture();
    	if(empty($culture))
    	{
    		echo "No Instance found"; die;
    	}
    	if((int)$culture<10){ $culture='0'.$culture; }
    	 
    	
    	/*langue par defaut*/
    	$sSql = "SELECT LANGUE_ID FROM #pref#_boforms_culture WHERE CULTURE_ID=".(int)$culture;
    	$langue_id=$oConnection->queryItem($sSql);
    	
    	/*site id*/
    	$sSQL = "SELECT FORMSITE_ID_MASTER FROM #pref#_boforms_groupe where GROUPE_ID=".$_GET['groupe_id'];
    	$formsiteid = $oConnection->queryItem($sSQL);
    	if((int)$formsiteid<10){ $formsiteid = '0'.$formsiteid; }
    	 
    	
    	/*récupère les données saisi dans le bloc Formulaire*/
    	$sSQL = "SELECT ZONE_TITRE ,ZONE_ATTRIBUT as DEVICE, ZONE_TITRE3 as OPPRTUNINTE, ZONE_TITRE4 as TARGET
    	FROM #pref#_page_multi_zone pmz
    	INNER JOIN #pref#_page p ON (p.PAGE_ID=pmz.PAGE_ID AND PAGE_VERSION=PAGE_CURRENT_VERSION AND pmz.LANGUE_ID=$langue_id)
    	WHERE
    	p.SITE_ID = ".$_SESSION[APP]['SITE_ID']."
    		AND ZONE_ID = $zoneid
			AND PAGE_STATUS=1";
    	
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
    	    				
    	    				$pagezone['OPPRTUNINTE'] = FunctionsUtils::getOpportuniteByFormTypeCPP($pagezone['OPPRTUNINTE']);
    	    				
    	    						$codeInstance ='';
    	    						$codeInstancePart ='';
    	    						$codeInstancePro ='';
    	    						$codeInstancePartWeb ='';
    	    						$codeInstancePartMobile ='';
    	    						$codeInstanceProtWeb ='';
    	    						$codeInstanceProMobile ='';
    	
    	    						
    	    						if(!empty($aDeviceMapping[$pagezone['DEVICE']]) || $aDeviceMapping[$pagezone['DEVICE']]==='0')
    	    						{
    	    							
    	    							if((int)$pagezone['OPPRTUNINTE']<10){ $pagezone['OPPRTUNINTE'] = '0'.$pagezone['OPPRTUNINTE']; }
    	
    	    									
	    	    						$codeInstance = $brand.$codePays.$aTargetMapping[$pagezone['TARGET']].'9'.$formsiteid.'0000'.$aDeviceMapping[$pagezone['DEVICE']].'0'.$pagezone['OPPRTUNINTE'];
	    	
	    	    						
	    	    						
    	    							if(strpos($codeInstance, '#Tar#')!==false || strpos($codeInstance, '#Dev#')!==false)
    	    							{
    	    								if(strpos($codeInstance, '#Tar#')!==false)
    	    								{
    	    									$codeInstancePart = str_replace('#Tar#', 1, $codeInstance);
    	    									$codeInstancePro = str_replace('#Tar#', 2, $codeInstance);
    	    								}
    	
    	    								if(!$codeInstancePart)
    	    									$codeInstancePart=$codeInstance;
    	    										
    	    									if(!$codeInstancePro)
    	    										$codeInstancePro=$codeInstance;
    	
    	    										if(strpos($codeInstance, '#Dev#')!==false)
    	    										{
	    	    										$codeInstancePartWeb = str_replace('#Dev#', 0, $codeInstancePart);
	    	    										$codeInstancePartMobile = str_replace('#Dev#', 1, $codeInstancePart);
	    	    											
	    	    										$codeInstanceProtWeb = str_replace('#Dev#',0, $codeInstancePro);
	    	    										$codeInstanceProMobile = str_replace('#Dev#', 1, $codeInstancePro);
    	    										}
    	    										//$codeInstancePart = str
    	    										if(strpos($codeInstance, '#Dev#')===false){
    	    											$aCodeinstance[$codeInstancePart]=$codeInstancePart;
    	    											$aCodeinstance[$codeInstancePro]=$codeInstancePro;
    	    										}
    	
    	    										$aCodeinstance[$codeInstancePartWeb]=$codeInstancePartWeb;
    	    										$aCodeinstance[$codeInstancePartMobile]=$codeInstancePartMobile;
    	    										$aCodeinstance[$codeInstanceProtWeb]=$codeInstanceProtWeb;
    	    										$aCodeinstance[$codeInstanceProMobile]=$codeInstanceProMobile;
    	    							}else{
    	
    	    									$aCodeinstance[$codeInstance]=$codeInstance;
    	    							}
    	    									
    	    						}
    	
    	    		}
    	    	}
    	    	
    	    	if(is_array($aCodeinstance) && !empty($aCodeinstance))
    	    	{
    	    		
    	    		/**réitialise le flag à 0 en base**/
    	    	 
    	    		$aBind=array();
    	    		$aBind[':PAYS_CODE']=$oConnection->strToBind($codePays);
    	    		$aBind[':FORM_BRAND']=$oConnection->strToBind($brand);
    	    		$aBind[':FORMSITE_ID']=(int)$formsiteid;
    	    		
    	    		
    	    		$sSql = "Update #pref#_boforms_formulaire
    	    				 set FORM_ACTIVATED = 0
    	    				 where PAYS_CODE = :PAYS_CODE
    	    				 and FORM_BRAND = :FORM_BRAND
    	    				 and FORMSITE_ID = :FORMSITE_ID
    	    				 ";
    	    		$oConnection->query($sSql,$aBind);
    	    		/**/
    	    		
    	    		    	    		
    	    		if((int)$culture<10)
    	    			$culture='0'.$culture;
    	    		
    	    		$aBind=array();
    	    		
    	    		$sSql = "Update #pref#_boforms_formulaire
    	    				 set FORM_ACTIVATED = :FORM_ACTIVATED
    	    				 where FORM_INCE = :FORM_INCE";
    	    		
    	    		foreach ($aCodeinstance as $k=>$codeInstanceGene)
    	    		{
    	    			$aList = array();
    	    			$aList[]=$codeInstanceGene;
    	    			
    	    			$sCodeInstanceStand = substr_replace($codeInstanceGene, '0', 5, 1);
    	    			$sCodeInstanceStand = substr_replace($sCodeInstanceStand, $culture, 10, 2);

    	    			$aList[]=$sCodeInstanceStand;
    	    			
    	    			$sCodeInstancePDV = substr_replace($sCodeInstanceStand, '1', 9, 1);
    	    			$sCodeInstanceCAR = substr_replace($sCodeInstanceStand, '2', 9, 1);
    	    			
    	    			$aList[]=$sCodeInstancePDV;
    	    			$aList[]=$sCodeInstanceCAR;
    	    			
						foreach($aList as $code)
						{
							
							$aBind[':FORM_ACTIVATED'] = 1;
							$aBind[':FORM_INCE'] = $oConnection->strToBind($code);
							
							//var_dump($aBind);
							
							$oConnection->query($sSql,$aBind);
						}
    	    			    	    			
    	    		}
    	    	}
    	    		
    	    		
    	    		/*** ***/
    }
    
    
	
    
	
	
	

	
	/** vérifie le tag editable de l'instance **/
	public function getTagEditable($codeInstance)
	{
		
		$oConnection = Pelican_Db::getInstance ();
		$langueDefault= FunctionsUtils::getDefaultCulture();

		$sSQL = "select FORM_INCE, FORM_EDITABLE from #pref#_boforms_formulaire 
					where FORM_PARENT_INCE = '".$codeInstance."' and CULTURE_ID=".$langueDefault;
		$editable = $oConnection->queryRow($sSQL);
		
		if($editable['FORM_EDITABLE']==1)
		{
			$editable = '1';
		}else
		{
			if(empty($editable['FORM_INCE']))
			{
				$editable = '1';
			}else{
				$editable = '0';
			}
			
		}
		
		return $editable;
				
	}
	
    public function listAction ()
    { 	
    	//debug($_GET);
    	$oConnection = Pelican_Db::getInstance ();
    	
        if(!$_GET['groupe_id'])
    	{
    		$this->_forward('listGroupe');
    	} else {
    				parent::listAction();
    		
    				//$head = $this->getView()->getHead();
					//$head->setMeta('http-equiv', 'X-UA-Compatible', 'IE=edge,chrome=1');
			    	//$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js');
			    	//$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery-ui.min.js');
    				$hasBlocEdito = FunctionsUtils::checkBlockEdito();
			    	if($hasBlocEdito) {
    				   $this->setFormsPublishedFO();//calcule du flag activé/désactivé dans l'editorial
			    	}
			    	
			        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
			        
			       // $table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", "BOFORMS_LABEL");

			       $aRefFormType = $this->getReferential('FORM_TYPE','boforms_opportunite','OPPORTUNITE_ID','OPPORTUNITE_KEY');
			       $aRefTarget = $this->getReferential('CUSTOMER_TYPE','boforms_target','TARGET_ID','TARGET_KEY');
			       unset($aRefTarget[2]); // do not display interstitiel (jira 85)
			       
			       $aRefDevice = $this->getReferential('DEVICE','boforms_device','DEVICE_ID','DEVICE_KEY');
			       $aRefContext = $this->getReferential('FORM_CONTEXT','boforms_context','CONTEXT_ID','CONTEXT_KEY');
			        
			        $table->setFilterField("opportunite","<b>".t('BOFORMS_TYPE_FORMULAIRE')."&nbsp;:</b><br/>","bf.OPPORTUNITE_ID",$aRefFormType);
			        $table->setFilterField("target","<b>".t('BOFORMS_TARGET')."&nbsp;:</b><br/>","bf.TARGET_ID",$aRefTarget);
			        $table->setFilterField("device","<b>".t('BOFORMS_DEVICE')."&nbsp;:</b><br/>","bf.DEVICE_ID",$aRefDevice);
			        $table->setFilterField("type","<b>".t('BOFORMS_CONTEXT')."&nbsp;:</b><br/>","bf.FORM_CONTEXT",$aRefContext);
			        if($hasBlocEdito)
			        {
					   $table->setFilterField("info","<b>".t('BOFORMS_INFO')."&nbsp;:</b><br/>","FORM_ACTIVATED",array(array("id"=>'1', "lib"=>t('BOFORMS_INFO_ACTIVATED')),array("id"=>'0', "lib"=>t('BOFORMS_INFO_DISABLED'))));
			        }					
			        $table->getFilter(5);
			        
			     	$aTabValues = $oConnection->queryTab($this->getListModel());

			        $table->setCSS(array("form_standard", "form_context"), "FORM_CONTEXT");
			        
			        $table->setValues($this->getListModel(), "FORM_INCE"/*,"OPPORTUNITE_LABEL"*/);
			        
			        $bicon=false;

			        $sqlCulture = "SELECT CULTURE_ID,CULTURE_LABEL,CULTURE_KEY,LANGUE_TRANSLATE
	    				  FROM #pref#_site_language sl
	    				  INNER JOIN #pref#_language l ON (l.LANGUE_ID=sl.LANGUE_ID)
	    				  INNER JOIN #pref#_boforms_culture bc ON (l.LANGUE_ID = bc.LANGUE_ID)
	    				  WHERE SITE_ID = ".$_SESSION[APP]['SITE_ID']."
	    				  ORDER BY l.LANGUE_ID
	    				  ";
			        
			        $aCulture = $oConnection->queryTab($sqlCulture);
			        
			        $js_desactivate_btns = 'var desactivate_btns = [];';
			        if(is_array($table->aTableValues) && !empty($table->aTableValues))
			        {
			        	foreach ($table->aTableValues as $k=>$row)
			        	{
			        		//traduction Referential
			        		$table->aTableValues[$k]['OPPORTUNITE_LABEL'] = t('BOFORMS_REFERENTIAL_FORM_TYPE_'.$row['OPPORTUNITE_KEY']);
			        		$table->aTableValues[$k]['TARGET_LABEL'] = t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_'.$row['TARGET_KEY']);
			        		$table->aTableValues[$k]['DEVICE_LABEL'] = t('BOFORMS_REFERENTIAL_DEVICE_'.$row['DEVICE_KEY']);
			        		$table->aTableValues[$k]['CONTEXT_LABEL'] = t('BOFORMS_REFERENTIAL_FORM_CONTEXT_'.$row['CONTEXT_KEY']);
			        		
			        		// test si le formulaire est bloque
			        		$cpt_culture = 8; 
			        		$is_full_line_editable = true;
			        		
			        		foreach ($aCulture as $culture) {
			        		 	$sCulture = str_pad($culture['CULTURE_ID'], 2, '0', STR_PAD_LEFT);
			        		 				        		 	
			        		 	$standard_ince = $row['FORM_INCE'];
			        		 	$standard_ince  =  substr_replace ( $standard_ince, "0", 5, 1 );
	    						$standard_ince  =  substr_replace ( $standard_ince, $sCulture, 10, 2 );
			        		 		
	    						$tmpBind = array();
	    						$tmpBind[':CODE_INCE'] = $oConnection->strToBind($standard_ince);
	    						$isLockedForm = $oConnection->queryItem("select FORM_EDITABLE from #pref#_boforms_formulaire where FORM_INCE = :CODE_INCE", $tmpBind);
			        			
	    						// si standard n'existe pas
	    						if (is_array($isLockedForm)) {
			        				// on verifie si le generic est bloque ou pas
			        				$tmpBindGeneric[':CODE_INCE_GENERIC'] = $oConnection->strToBind($row['FORM_INCE']);			        				
			        				$isLockedForm = $oConnection->queryItem("select FORM_EDITABLE from #pref#_boforms_formulaire where FORM_INCE = :CODE_INCE_GENERIC", $tmpBindGeneric);
			        			}
	    						
	    						
	    						if ($isLockedForm == 1) {   							
	    							$table->aTableValues[$k]['FORM_INCE_EDITABLE_' . $sCulture] = 1;
			        			} else {
		    						// tableau js avec les id des boutons a désactiver
		    						$indiceTmp = $k + 1;
			        				$js_desactivate_btns .= "desactivate_btns.push('$indiceTmp" . '_' . "$cpt_culture');";
	    							$table->aTableValues[$k]['FORM_INCE_EDITABLE_' . $sCulture] = 0;
	    							$is_full_line_editable = false;
			        			}
			        			$cpt_culture++;	    						
			        		}
			        		
			        		//clacul le code générique pour le bouton
			        		/*if(empty($row['FORM_PARENT_INCE']))
			        		{
			        			//cas : générique
			        			$table->aTableValues[$k]['PARAM_INCE']= $row['FORM_INCE'];
			        		}elseif(!empty($row['FORM_PARENT_INCE']) && $row['FORM_CONTEXT']>0){
			        			//cas contextualisé
			        			$sCodeInstance = substr_replace($row['FORM_PARENT_INCE'], '9', 5, 1);
			        			$sCodeInstance = substr_replace($sCodeInstance, '0', 9, 1);
			        			$sCodeInstance = substr_replace($sCodeInstance, '00', 10, 2);
			        			
			        			$table->aTableValues[$k]['PARAM_INCE']= $sCodeInstance;
			        		}else{
			        			//standard
			        			$table->aTableValues[$k]['PARAM_INCE']= $row['FORM_PARENT_INCE'];
			        		}*/
			        		/*****/
			        		
			        		/*vérifie si le formulaire est publié en FO*/

			        			if($row['FORM_ACTIVATED']==1 || FunctionsUtils::isLandingPageSite((int)$row['FORMSITE_ID']) )
			        			{
			        				$table->aTableValues[$k]['INFO'] = "<div style='background-color:#8FF096; width:100%;padding-top: 4px; padding-bottom: 4px;'>".t('BOFORMS_INFO_ACTIVATED')."</div>";
			        			}else{
			        				$table->aTableValues[$k]['INFO'] = "<div style='background-color:#DCDCDC; width:100%;padding-top: 4px; padding-bottom: 4px;'>".t('BOFORMS_INFO_DISABLED')."</div>";
			        			}
			        		/* */
			        		
			        		if(empty($row['FORM_COMMENTARY']))
			        		{
			        			$table->aTableValues[$k]['FORM_COMMENTARY']= t('BOFORMS_COMMENTARY_DEFAULT');
			        		}
			        		
			        		
			        		/*** TAG EDITABLE : Jira BOFORMS-61 ***/
			        			        		
			        		if(is_null($table->aTableValues[$k]['FORM_EDITABLE']))
			        		{
			        			$table->aTableValues[$k]['FORM_EDITABLE']= '0';
			        		}
			        		
			        		
			        		if((int)$row['FORM_GENERIC']==1)
			        		{
			        			$table->aTableValues[$k]['FORM_EDITABLE'] = $this->getTagEditable($row['FORM_INCE']);
			        		}
			        		/******/
			        		
			        						        		
			        		if((int)$row['FORM_CONTEXT']>0)
			        		{
			        			$table->aTableValues[$k]['FORMSITE_LABEL']= "<span style='font-style: italic;background-image:url(/library/Pelican/Hierarchy/Tree/public/images/joinbottom.gif);background-repeat: no-repeat;padding-left: 22px;'> ".t('BOFORMS_FORMSITE_LABEL_' . $table->aTableValues[$k]['FORMSITE_KEY'])."</span>";
			        			$table->aTableValues[$k]['OPPORTUNITE_LABEL']= "<span style='font-style: italic;'> ".$table->aTableValues[$k]['OPPORTUNITE_LABEL']."</span>";
			        			$table->aTableValues[$k]['TARGET_LABEL']= "<span style='font-style: italic;'> ".$table->aTableValues[$k]['TARGET_LABEL']."</span>";
			        			$table->aTableValues[$k]['DEVICE_LABEL']= "<span style='font-style: italic;'> ".$table->aTableValues[$k]['DEVICE_LABEL']."</span>";
			        			$table->aTableValues[$k]['CONTEXT_LABEL']= "<span style='font-style: italic;'> ".$table->aTableValues[$k]['CONTEXT_LABEL']."</span>";
			        		} else {
			        			$table->aTableValues[$k]['FORMSITE_LABEL'] = t('BOFORMS_FORMSITE_LABEL_' . $table->aTableValues[$k]['FORMSITE_KEY']);
			        		}
			        		
			        		if ($is_full_line_editable == false) 
			        		{
			        			$table->aTableValues[$k]['tag_editable'] = '<img style="cursor:help;" title="'.$table->aTableValues[$k]['FORM_COMMENTARY'].'" src="'.Pelican_Plugin::getMediaPath('boforms').'/images/exclamation.png">';
			        			$bicon=true;
			        		}
			        	}
			        	
			        	
			        	
			        }
			      
			        
			       //$table->addColumn(t('ID'), "FORM_INCE", "10", "left", "", "tblheader","FORM_INCE");
			       // $table->addColumn(t('BOFORMS_TYPE_FORMULAIRE'), "OPPORTUNITE_LABEL", "50", "left", "", "tblheader","OPPORTUNITE_LABEL");

			     //  $table->addColumn(t('ID'), "FORM_INCE", "50", "left", "", "tblheader","FORM_INCE"); 
			        /*$table->addColumn('id', "FORM_INCE", "30", "left", "", "tblheader","PARAM_INCE");
			       $table->addColumn('id', "PARAM_INCE", "30", "left", "", "tblheader","PARAM_INCE"); */
			     
			       if (in_array($_SESSION[APP]['user']['id'],Pelican::$config['BOFORMS_USER_SUPER_ADMIN']) || in_array(strtoupper($_SESSION[APP]['user']['id']),Pelican::$config['BOFORMS_USER_SUPER_ADMIN']) ) {
			      	 $table->addInput('active', "checkbox", array("_javascript_" => "clickCheckboxEmpty", "_value_field_"=>"FORM_INCE","" => "", "param" => ""), "center");
			      	 			      	 
			       }
			       if($bicon)
			       {
			       	$table->addColumn('', "tag_editable", "0", "left", "", "tblheader","FORM_EDITABLE");
			       }
			      // $table->addImage("Cat.", "/chemin/categorie/", "categorie_id", "1", "center", "", "tblheader", "categorie_libelle");
			      // var_dump(Pelican_Plugin::getMediaPath('boforms'));
			       $table->addColumn('Site', "FORMSITE_LABEL", "30", "left", "", "tblheader",""); 
			       $table->addColumn(t('BOFORMS_TYPE_FORMULAIRE'), "OPPORTUNITE_LABEL", "30", "left", "", "tblheader","OPPORTUNITE_ID"); 
			       $table->addColumn(t('BOFORMS_TARGET'), "TARGET_LABEL", "40", "left", "", "tblheader","TARGET_ID");
			       $table->addColumn(t('BOFORMS_DEVICE'), "DEVICE_LABEL", "30", "left", "", "tblheader","DEVICE_ID");
			       $table->addColumn(t('BOFORMS_CONTEXT'), "CONTEXT_LABEL", "50", "left", "", "tblheader","CONTEXT_ID"); 

			       
			       //$table->addColumn(t('BOFORMS_INFO'), "INFO", "10", "center", "", "tblheader","");

			       
			        /*
			         * @TODO $table->addColumn(t('BOFORMS_MODE'), "BOFORMS_MODE", "30", "left", "", "tblheader"); $table->addInput(t('BOFORMS_VALUES'), "button", array( "id" => "BOFORMS_ID", "" => "values=true" ), "center");
			         */
			       			        
			        
			       // unset($aCulture[1]);
			        
			        	$this->assign('aCulture', $aCulture);
			        
				        if(sizeof($aCulture)>1)
				        {	
					        $i=0;
					         
					        foreach ($aCulture as $culture)
					        {
					        	
					        	$culture['CULTURE_ID']=(strlen($culture['CULTURE_ID'])<2?'0'.$culture['CULTURE_ID']:$culture['CULTURE_ID']);
					        	
					        	$sCulture = str_pad($culture['CULTURE_ID'], 2, '0', STR_PAD_LEFT);
			        		 	
					        	
					        	if($i==0)
					        	{
					        		$nbCult = sizeof($aCulture);
					        							        		
						        	$table->addInput($culture['LANGUE_TRANSLATE'], "button", array(
						        			"_javascript_"=>"OpenForm",
						        			"form_id" => "FORM_INCE",
						        			"isEditable" => "FORM_INCE_EDITABLE_" . $sCulture,
						        			"commentaire" => "FORM_COMMENTARY",
						        			"" => "langue_id=".$culture['CULTURE_ID'].""
						        	), "center","","tblheader",0,$nbCult,1,t('FORM_BUTTON_EDIT'));
					        	}else{
					        		$table->addInput($culture['LANGUE_TRANSLATE'], "button", array(
					        				"_javascript_"=>"OpenForm",
					        				"form_id" => "FORM_INCE",
					        				"isEditable" => "FORM_INCE_EDITABLE_" . $sCulture, 
					        				"commentaire" => "FORM_COMMENTARY",
					        				"" => "langue_id=".$culture['CULTURE_ID'].""
					        		), "center");
					        		
					        	}
					        	
					        	$i++;
					        }	
				        }else{
				        	$table->addInput(t('FORM_BUTTON_EDIT'), "button", array(
				        			"_javascript_"=>"OpenForm",
			        				"form_id" => "FORM_INCE",
			        				"isEditable" => "FORM_INCE_EDITABLE_" . str_pad($aCulture[0]['CULTURE_ID'], 2, '0', STR_PAD_LEFT), 
			        				"commentaire" => "FORM_COMMENTARY",
			        				"" => "langue_id=". $aCulture[0]['CULTURE_ID'] .""
				        			
				        	), "center","","tblheader",0,1,1,"Action");
				        	
				        }    
		       
				       if($hasBlocEdito) {
					       if(sizeof($aCulture)>1){
					        	$table->addColumn(t('BOFORMS_INFO'), "", "10", "left", "", "tblheader","FORM_ACTIVATED");
					       }
					        if(sizeof($aCulture)>1)
					        {
					        	$table->addColumn('', "INFO", "10", "center", "", "tblheader","");
					        }else{
					       
					        	$table->addColumn(t('BOFORMS_INFO'), "INFO", "10", "center", "", "tblheader","FORM_ACTIVATED");
					               	
					        }
				       }

			        /// /_/module/boforms/BoForms_Administration_Module/editor 
			        
			        $this->aButton["add"]="";
	     			Backoffice_Button_Helper::init($this->aButton);
			        	     			
			        $this->assign('table_list', $table->getTable(), false);
			       // $this->assign('popup_title', t('BOFORMS_BUTTON_PERSO_LP'));
			       
			        // affichage de l'url BO LP
			        $url_form_lp = '';
			        if(FunctionsUtils::isLandingPageSite((int)$table->aTableValues[0]['FORMSITE_ID']))
			        {
			        	$url_form_lp = (isset(\Itkg::$config['URL_BOLP_' . FunctionsUtils::getCodePays()])) ? \Itkg::$config['URL_BOLP_' . FunctionsUtils::getCodePays()] : ''; 
			        	if ($url_form_lp != '') {
			        		$this->assign('LP_link',  t('BOFORMS_BUTTON_PERSO_LP'));
			        		$this->assign('LP_href',  $url_form_lp);
			        	}
			        }
			        
			        $this->assign('popup_title', t('BOFORMS_POPUP_CREATE_NEW_FORM_TITLE'));
			        $this->assign('popup_link',  t('BOFORMS_POPUP_CREATE_NEW_FORM_LINK_TEXT'));
			        if (in_array($_SESSION[APP]['user']['id'],Pelican::$config['BOFORMS_USER_SUPER_ADMIN']) || in_array(strtoupper($_SESSION[APP]['user']['id']),Pelican::$config['BOFORMS_USER_SUPER_ADMIN']) ) {
			       		$this->assign('isAdmin', true);
			        }
			        $this->assign('media_path_boform', Pelican_Plugin::getMediaPath('boforms'));
					
			        
			       
			  	$script_plus =  '<script type="text/javascript">';
				$script_plus .= "$js_desactivate_btns";
								
				$script_plus .=  "function clickCheckboxEmpty()
						{
						return false;
						}
						";

    			if ($url_form_lp == '') {
					$script_plus .= "function RedirectLP() { alert('cannot redirect to LP URL: no LP URL for this site'); }";
				} else {
					$script_plus .= "function RedirectLP() { window.open('$url_form_lp'); return false; } ";	
				}
			
				$script_plus .=  "$( document ).ready(function() {
						for	(index = 0; index < desactivate_btns.length; index++) {
							$('button[id=_' + desactivate_btns[index] + ']').css('background-image', 'none').css('background-color', '#dcdcdc').css('cursor', 'not-allowed');
						}
						
						window.parent.$('#frame_right_top').html('" . t('BOFORMS_TRANSLATE_MANAGE_FORMS') . "'); 
				    	$('#body_child div.form_title').html('" . t('BOFORMS_TRANSLATE_LIST_MANAGE_FORMS') . "');
				     });";
				$script_plus .=  '</script>';
				$output = $this->getView()->fetch( Pelican::$config["PLUGIN_ROOT"] . '/boforms/backend/views/scripts/BoForms/Administration/Module/list.tpl' , true);
				$this->setResponse($output . $script_plus);
    	}

    }

    public function listGroupeAction ()
    {

		/* update reference */
		FunctionsUtils::updateReferences();
		/**/

    	parent::listAction();
        $table = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
        //$table->setFilterField("search_keyword", "<b>" . t('RECHERCHER') . " :</b>", array("FORMSITE_LABEL"));
        $table->getFilter(1);
        $table->setCSS(array(
            "tblalt1",
            "tblalt2"
        ));
        $table->setValues($this->getListModel(), "GROUPE_ID");


        $oConnection = Pelican_Db::getInstance ();
    
        $sqlGroupe = "SELECT fs.FORMSITE_KEY         			 
        			  FROM #pref#_boforms_groupe_formulaire gf 
        			  INNER JOIN #pref#_boforms_formulaire_site fs on gf.FORMSITE_ID = fs.FORMSITE_ID 
        			  WHERE gf.GROUPE_ID = :GROUPE_ID
        			  ORDER BY fs.FORMSITE_ID";
        
        
        if(is_array($table->aTableValues) && !empty($table->aTableValues))
        {

			/*** Appel Webservices, mise Ã  jour de la table formulaire ***/
			$this->loadListInstanceWS(FunctionsUtils::getCodePays(),Pelican::$config['BOFORMS_BRAND_ID']);
			/*****/

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
		
		echo " ";
		
		echo "$( document ).ready(function() {
				window.parent.$('#frame_right_top').html('" . t('BOFORMS_TRANSLATE_MANAGE_FORMS') . "'); 
				$('#body_child div.form_title').html('" . t('BOFORMS_TRANSLATE_LIST_SITES') . "');
				
				var all_cultures = [];
				" . $js_all_cultures;

		if ($_SESSION[APP]['PROFIL_LABEL'] == "ADMINISTRATEUR") {
				echo " 
				$('#reload_instances').on('click', function() {
					if (confirm('" . t('BOFORMS_RELOAD_INSTANCES_CONFIRM') . "')) {
						$('#reload_instances').attr('disabled', 'disabled');
						$('#reload_instances').css('background-image', 'none');
						$('#reload_instances').css('background-color', '#dcdcdc');
						
						$.ajax({
								type: 'GET',
								url: '/_/module/boforms/BoForms_Administration_Module/reloadInstances',
								success: function( data ) {
								 	alert('" . t('BOFORMS_RELOAD_INSTANCES_DONE') . "');
		    					},
		    					error: function (xhr, ajaxOptions, thrownError) {
		    						$('#results_ws').append('<div style=\"color: red;border-top: 1px solid black; margin-top: 5px;\">' + xhr.status + ' - ' + thrownError +  ' (' + this.culture + ') </div>');
							    },
								dataType: 'html'
						});
					}
				});

				$('#clear_instances').on('click', function() {
					if (confirm('" . addslashes(t('BOFORMS_CLEAR_INSTANCES_CONFIRM')) . "')) {
						$('#clear_instances').attr('disabled', 'disabled');
						$('#clear_instances').css('background-image', 'none');
						$('#clear_instances').css('background-color', '#dcdcdc');

						$.ajax({
								type: 'GET',
								url: '/_/module/boforms/BoForms_Administration_Module/clearInstances',
								success: function( data ) {
								 	alert('" . addslashes(t('BOFORMS_CLEAR_INSTANCES_DONE')) . "');
								 	location.reload();
		    					},
		    					error: function (xhr, ajaxOptions, thrownError) {
		    						$('#results_ws').append('<div style=\"color: red;border-top: 1px solid black; margin-top: 5px;\">' + xhr.status + ' - ' + thrownError +  ' (' + this.culture + ') </div>');
							    },
								dataType: 'html'
						});
					}
				});
				
				$('#reload_referential').on('click', function() {
					if (confirm('" . t('BOFORMS_RELOAD_REFERENTIAL_CONFIRM') . "')) {
						$('#reload_referential').attr('disabled', 'disabled');
						$('#reload_referential').css('background-image', 'none');
						$('#reload_referential').css('background-color', '#dcdcdc');
						
						$.ajax({
								type: 'GET',
								url: '/_/module/boforms/BoForms_Administration_Module/updateReferences',
								success: function( data ) {
								 	alert('" . t('BOFORMS_RELOAD_REFERENTIAL_DONE') . "');
		    					},
		    					error: function (xhr, ajaxOptions, thrownError) {
		    						$('#results_ws').append('<div style=\"color: red;border-top: 1px solid black; margin-top: 5px;\">' + xhr.status + ' - ' + thrownError +  ' (' + this.culture + ') </div>');
							    },
								dataType: 'html'
						});
					}
				});
				
				";
		}		

		echo " $('#clear_cache').on('click', function() {
				for (i = 0; i < all_cultures.length; i++) {
					key = '" . Pelican::$config['BOFORMS_URL_CLEARCACHE_KEY'] . "';	
					//url = '" . Pelican::$config['BOFORMS_URL_CLEARCACHE'] . '/cache/reset?' . "'
					$.ajax({
							culture: all_cultures[i],
							type: 'GET',

							url: '/_/module/boforms/BoForms_Administration_Module/clearCacheAjax?culture=' + all_cultures[i] + '&key=' + key,
							success: function( data ) { 

								if (data.CacheStatusResult.CacheStatusCode == 'OK') {
									$('#results_ws').append('<div style=\"color: green;border-top: 1px solid black; margin-top: 5px;\">' + data.CacheStatusResult.CacheStatusMessage + ' (' + this.culture + ')</div>');
								} else {
									$('#results_ws').append('<div style=\"color: red;border-top: 1px solid black; margin-top: 5px;\">' + data.CacheStatusResult.CacheStatusCode + ' (' + this.culture + ')</div>');
								} 
	    					},
	    					error: function (xhr, ajaxOptions, thrownError) {
	    						$('#results_ws').append('<div style=\"color: red;border-top: 1px solid black; margin-top: 5px;\">' + xhr.status + ' - ' + thrownError +  ' (' + this.culture + ') </div>');
						    },
							
							dataType: 'json'
					});
				}			 
			});
		
    	});";
		echo '</script>';
        
		// les boutons
		if($_SESSION[APP]['PROFIL_LABEL'] == "ADMINISTRATEUR") {
			$btn1 = "<br/><input style=\"margin-bottom: 5px;\" type=\"button\" id=\"clear_cache\" value=\"" . t('BOFORMS_BTN_CLEAR_CACHE') . "\" class=\"button\" />";
			$btn_div1  = "<div id=\"results_ws\"></div>";
			$btn2 = "&nbsp;<input style=\"margin-bottom: 5px;\" type=\"button\" id=\"reload_instances\" value=\"" . t('BOFORMS_BTN_RELOAD_INSTANCES') . "\" class=\"button\" />";
			$btn4 = "&nbsp;<input style=\"margin-bottom: 5px;\" type=\"button\" id=\"clear_instances\" value=\"" . t('BOFORMS_BTN_CLEAR_INSTANCES') . "\" class=\"button\" />";
			$btn3 = "&nbsp;<input style=\"margin-bottom: 5px;\" type=\"button\" id=\"reload_referential\" value=\"" . t('BOFORMS_BTN_RELOAD_REFERENTIAL') . "\" class=\"button\" />";		
		}
    	
		
        $this->setResponse($table->getTable() . $btn1 . '&nbsp;' . $btn2 . '&nbsp;' . $btn4. '&nbsp;' . $btn3 . '<br/>' . $btn_div1);
    }


	public function clearInstancesAction()
	{
		$oConnection = Pelican_Db::getInstance();

		$country = FunctionsUtils::getCodePays();

		if(empty($country)){
			die('no country');
			return false;
		}

		$brand = Pelican::$config['BOFORMS_BRAND_ID'];

		$sql = "DELETE FROM #pref#_boforms_formulaire WHERE FORM_INCE LIKE '".$brand.$country."%'";
		$oConnection->query($sql);

		$sql = "DELETE FROM #pref#_boforms_formulaire_version WHERE FORM_INCE LIKE '".$brand.$country."%'";
		$oConnection->query($sql);

		$sql = "DELETE FROM #pref#_boforms_trace WHERE FORM_INCE LIKE '".$brand.$country."%'";
		$oConnection->query($sql);

		$sql = "DELETE FROM #pref#_boforms_groupe_formulaire WHERE GROUPE_ID IN (select GROUPE_ID from #pref#_boforms_groupe where SITE_ID =  ".$_SESSION[APP]['SITE_ID'].")";
		$oConnection->query($sql);

		$sql = "DELETE FROM #pref#_boforms_groupe WHERE SITE_ID =  ".$_SESSION[APP]['SITE_ID'];
		$oConnection->query($sql);


		exit(0);
	}

    // btn recharger les instances dans la liste des formulaires
    public function reloadInstancesAction() {
    	$oConnection = Pelican_Db::getInstance();

    	$aBind[':FORM_DATE'] = $oConnection->strToBind(date("Y-m-d H:i:s"));
    	$aBind[':FORM_BRAND'] = $oConnection->strToBind(Pelican::$config['BOFORMS_BRAND_ID']);
    	$aBind[':PAYS_CODE'] = $oConnection->strToBind( FunctionsUtils::getCodePays());

    	// gets the list of form instances
    	$instances = $oConnection->queryTab('SELECT FORM_INCE, FORM_CURRENT_VERSION, FORM_DRAFT_VERSION, FORM_GENERIC
    										 FROM #pref#_boforms_formulaire
    										 where PAYS_CODE = :PAYS_CODE and FORM_BRAND = :FORM_BRAND', $aBind);

    	if (count($instances) > 0) {
    		foreach ($instances as $key => $values) {
    			$form_ince = $values['FORM_INCE'];
    			$form_current_version = $values['FORM_CURRENT_VERSION'];
    			$form_draft_version = $values['FORM_DRAFT_VERSION'];


    			$aBind[':FORM_INCE'] = $oConnection->strToBind($form_ince);

    			if (1 == (int)$values['FORM_GENERIC']) {
					$form_new_version = $form_draft_version;
				} else {
					$form_new_version = $form_draft_version + 1;
				}
				$aBind[':FORM_NEW_VERSION'] = $form_new_version;

				// get form's xml content
				$sXML=$this->getInstanceWS($form_ince); //DONE DEBOUCHE
				if($sXML)
				{
					// adds the line in boforms_formulaire_version
					$sql_insert = "replace into #pref#_boforms_formulaire_version (FORM_INCE,FORM_VERSION,FORM_XML_CONTENT,FORM_DATE,FORM_LOG,USER_LOGIN,STATE_ID)
					 	values (:FORM_INCE, :FORM_NEW_VERSION, '".addslashes($sXML)."',:FORM_DATE,NULL,NULL,".Pelican::$config['BOFORMS_STATE']['PUBLISH'].")";

					// updates the version in boforms_formulaire
    				$sql_update = "update  #pref#_boforms_formulaire
				               set FORM_CURRENT_VERSION = :FORM_NEW_VERSION, FORM_DRAFT_VERSION = :FORM_NEW_VERSION
							   where FORM_INCE = :FORM_INCE";

					$sql_delete = "delete from #pref#_boforms_formulaire_version where FORM_INCE =:FORM_INCE AND FORM_VERSION > :FORM_NEW_VERSION";

    				// TODO uncomment this line for testing purpose
    				if(!Pelican::$config['BOUCHON_ON'])
    				{
    					$oConnection->query($sql_delete, $aBind);
    					$oConnection->query($sql_insert, $aBind);
    					$oConnection->query($sql_update, $aBind);
    				}
				}
    		}
    	}

    	exit(0);
    }
    
    
    public function clearCacheAjaxAction() {
    	  	
    	$url=Pelican::$config['BOFORMS_URL_CLEARCACHE']."/cache/reset?culture=".$_GET['culture']."&key=".Pelican::$config['BOFORMS_URL_CLEARCACHE_KEY'];
    	    	
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
    	echo($content);
    	// Fermeture de la session cURL
    	curl_close($CURL);
    	    	
    }
    
    public function testJsonAction() {
    	if (rand(0,1) == 0) {
    		echo '{"CacheStatusResult":{"CacheStatusCode":"OK","CacheStatusMessage":"Cache is cleared successfully for the country ..."}}';
    	} else {
    		echo '{"CacheStatusResult":{"CacheStatusCode":"ERROR Cache is ko for the country ..."}}';
    	}
    	exit(0);
    }
    
    public function editAction ()
    {
		//voir editor action
    }

	public function displayXmlAction()
	{
		$oInstance = new FormInstance($_GET['code_instance']);
		$aVersions = $oInstance->getVersions();
		$sXml = $aVersions[strtolower($_GET['version'])][0]['FORM_XML_CONTENT'];
		$sXml=FunctionsUtils::cleanXML($sXml);
		
		$dom = new DomDocument("1.0",'UTF-8');
		
		$sXml=str_replace('xmlns=', 'xmlns2=', $sXml);
		
		
		
		$dom->loadXML($sXml);
	
		$domXpath = new DOMXPath($dom);
		
		$button = $domXpath->query("//page/fieldSet/question/line/field[@code='TECHNICAL_SEND_REQUEST']")->item(0);
		if (! empty($button)) {
			$button->parentNode->removeChild($button);
		}

		$sXml=str_replace('xmlns2=', 'xmlns=', $dom->saveXML());
		
		header("Content-type: text/xml; charset=utf-8");
		echo $sXml;
		exit;
	}
	
	public function ipreviewAction()
	{
		$head = $this->getView()->getHead();
		$oInstance = new FormInstance($_GET['code_instance']);
		
		$aCodeContext = $oInstance->HasContextualises('CURRENT',true);

		if(count($aCodeContext)>1)
		{
			if($aCodeContext[0]['FORM_INCE'])
			{
				$this->assign("sSource1", "/_/module/boforms/BoForms_Administration_Module/preview?code_instance=".$aCodeContext[0]['FORM_INCE']."&display=current&version=".$_GET['version']);
			}
			if($aCodeContext[1]['FORM_INCE'])
			{
				$this->assign("sSource2", "/_/module/boforms/BoForms_Administration_Module/preview?code_instance=".$aCodeContext[1]['FORM_INCE']."&display=current&version=".$_GET['version']);
			}
			
			$this->assign('header', $head->getHeader(false), false);
			$this->fetch();
		}else{
			
			$_GET['code_instance']=$aCodeContext[0]['FORM_INCE'];
			$_GET['display']='current';
			$this->_forward ('preview');
		}
	}
	
	public function previewAction()
	{
				
		$head = $this->getView()->getHead();
		//$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery.min.js');
		//$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/jquery-ui.min.js');
		//$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'js/bootstrap-tabs.js');
		//$head->setCss(Pelican_Plugin::getMediaPath('boforms') . 'css/bootstrap.min.css');
		//$head->setCss(Pelican_Plugin::getMediaPath('boforms') . 'css/jquery-ui.css');
		
		$this->assign('media_path', Pelican_Plugin::getMediaPath('boforms'));
		$this->assign('JS_CSS_PREFIX', Pelican::$config['BOFORMS_PREVIEW_JS_CSS_PREFIX']);
		$this->assign('PATH_GET_FLUX', Pelican::$config['BOFORMS_PREVIEW_PATH_GET_FLUX']);
		$this->assign('BRAND_ID', strtolower(Pelican::$config['BOFORMS_BRAND_ID']));		
		
		if($_GET['display'] == 'current')
		{
			$oInstance = new FormInstance($_GET['code_instance']);
			$aVersions = $oInstance->getVersions();
			$sSource1 = "/_/module/boforms/BoForms_Administration_Module/displayXml?code_instance=".$_GET['code_instance']."&version=".$_GET['version'];
		} else {
			
			$sCodeInstance1 = substr_replace($_GET['code_instance'], 1, 9, 1);
			$oInstance = new FormInstance($sCodeInstance1);
			$sSource1 = "/_/module/boforms/BoForms_Administration_Module/displayXml?code_instance=".$sCodeInstance1."&version=".$_GET['version'];
		//	$this->assign("context1", $oInstance->getContextLabel());
			
			$sCodeInstance2 = substr_replace($_GET['code_instance'], 2, 9, 1);			
			$sSource2 = "/_/module/boforms/BoForms_Administration_Module/displayXml?code_instance=".$sCodeInstance2."&version=".$_GET['version'];
			$oInstance = new FormInstance($sCodeInstance2);
		//	$this->assign("context2", $oInstance->getContextLabel());
		}
		
		$this->assign("sSource1", $sSource1);
		
		if($sSource2)
			$this->assign("sSource2", $sSource2);
		
		/*$aLanguages = array(
		"10"=>array("id"=>"10", "label"=>"FranÃ Â§ais"),
		"7"=>array("id"=>"7", "label"=>"English")
		);*/
		
		/*$iDefaultLangID = "10";
		$this->assign("aLanguages", $aLanguages);*/
		
		$aCulture=$oInstance->getCulture();
		
		$bMobile = (int)substr($_GET['code_instance'],12,1);

		$this->assign("iDefaultLangID", $iDefaultLangID);
		$this->assign("pays", $aCulture['pays']);
		$this->assign("lang", $aCulture['lang']);
		$this->assign("culture",$aCulture['lang'].'-'.$aCulture['pays']);
		$this->assign("email", $aData['email']);
		$this->assign('header', $head->getHeader(false), false);
		$this->assign("sCode", $_GET['code_instance']);
		$this->assign("sVersion", $_GET['version']);
		$this->assign("bMobile", $bMobile);
		
		if($bMobile)
		{
			$this->assign("context", 'mobile');
			$this->assign("connector", 'mobile');
		}else{
			$this->assign("context", 'desktop');
			$this->assign("connector", 'pc');
		}
		
		if(Pelican::$config['BOFORMS_BRAND_ID'] == 'AC')
		{
			$this->assign("gammeSource", 'CPP');
		}else{
			$this->assign("gammeSource", 'GDG');
		}
		
		
		
		$brands['AC'] = 'citroen';
		$brands['AP'] = 'peugeot';
		$brands['DS'] = 'ds';
		
		$this->assign("brand", $brands[Pelican::$config['BOFORMS_BRAND_ID']]);
		$this->fetch();
	}

	private function editorSetScript($bLP, $form_commentary, $form_commentary_visible)
	{
		$oConnection = Pelican_Db::getInstance();
	
		$head = $this->getView()->getHead();
		
		// css and js
		$tbl_css = array('css/bootstrap.min.css', 'css/jquery-ui.css', 'css/jquery.loader.css', 'editor.css', 'knockout.contextmenu/dist/css/knockout.contextmenu.min.css');
    	$tbl_js = array('js/jquery.min.js', 'js/jquery-ui.min.js', 'js/json2.js', 'js/knockout-latest.debug.js', 'js/knockout-sortable.js',
    					'js/knockout.mapping-latest.js','js/bootstrap-tabs.js','js/jquery.hotkeys.js',
    					'js/jquery.loader.js','js/knockout-jqueryui.min.js', 'knockout.contextmenu/dist/js/knockout.contextmenu.min.js'); 
    	FunctionsUtils::includeJsAndCss($head, $tbl_css, $tbl_js);

    	// javascript lang array
		$tbl_lang = array('BOFORMS_TITLE_IBAN','BOFORMS_LABEL_MME','BOFORMS_LABEL_MLLE','BOFORMS_LABEL_MR','BOFORMS_TITLE_FIRSTFIELD','BOFORMS_TITLE_TEXT',
		'BOFORMS_TITLE_TEXTAREA','BOFORMS_TITLE_SUBMIT','BOFORMS_TITLE_NUMBER','BOFORMS_TITLE_CHECK','BOFORMS_TITLE_FIRSTCHOICE','BOFORMS_TITLE_SECONDCHOICE',
		'BOFORMS_TITLE_THIRDCHOICE','BOFORMS_TITLE_SELECT','BOFORMS_TITLE_RADIO','BOFORMS_TITLE_SECTION','BOFORMS_TITLE_SECTIONDESC','BOFORMS_TITLE_NAME',
		'BOFORMS_TITLE_PHONE','BOFORMS_TITLE_RIB','BOFORMS_TITLE_IBAN','BOFORMS_LISTENER_LISTENED','BOFORMS_LISTENER_LISTENING','BOFORMS_TITLE_CIVILITY','BOFORMS_LABEL_TYPE_RADIO', 'BOFORMS_LABEL_TYPE_DROPDOWN',
		'BOFORMS_LABEL_connector_brandid', 'BOFORMS_LABEL_connector_facebook', 'BOFORMS_LABEL_SBS_NWL_NEWS','BOFORMS_LABEL_SBS_USR_OFFER_2_LP','BOFORMS_LABEL_LEGAL_MENTION_ANSWER', 'BOFORMS_LABEL_LEGAL_MENTION_CPP_ANSWER'
		);
    	
		$tbl_lang2 = array('BOFORMS_LABEL_SBS_COM_OFFER','BOFORMS_LABEL_SBS_USR_OFFER','BOFORMS_LABEL_REQUEST_INTEREST_FINANCING',
		'BOFORMS_LABEL_REQUEST_INTEREST_INSURANCE','BOFORMS_LABEL_REQUEST_INTEREST_SERVICE','BOFORMS_LABEL_UNS_NWS_CPP_MOTIF',
		'BOFORMS_LABEL_GET_MYCITROEN', 'BOFORMS_LABEL_REQUEST_CALLBACK','BOFORMS_LABEL_SBS_USER_OFFER','BOFORMS_LABEL_MULTIFORMS_CHOICE',
		'BOFORMS_LABEL_SBS_USR_OFFER_2','BOFORMS_LABEL_SBS_COM_OFFER_2','BOFORMS_LABEL_USR_PHONE_TYPE','BOFORMS_LABEL_USR_PHONE_HOME','BOFORMS_LABEL_USR_PHONE_MOBILE','BOFORMS_LABEL_USR_PHONE_MOBILE_HOME');
		 
		
		$sCodeInstance = $_GET['code_instance'];
		$langue_id = substr($sCodeInstance,10,2);
		
		
		// 	test if user is super admin
	   	if (in_array($_SESSION[APP]['backoffice']['USER_LOGIN'], Pelican::$config['BOFORMS_USER_SUPER_ADMIN'])) {
	   		$this->assign('is_sup_adm_bo','1'); 
	   		$script_adm  = 'var is_dde = 1;';
	   	} else {
	   		$this->assign('is_sup_adm_bo','0');
	   		$script_adm  = 'var is_dde = 0;';	   		
	   	}
	   	
	   	// is landing page
	   	if ($bLP) {
	   		$script_lp = 'var is_lp = 1;';
	   	} else {
	   		$script_lp = 'var is_lp = 0;';
	   	}
	   	$form_commentary = str_replace("'", "\'", trim($form_commentary));
		$form_commentary = str_replace("\r\n", '<!--backspace-->', $form_commentary);
		$form_commentary = str_replace("\n\r", '<!--backspace-->', $form_commentary);
		$form_commentary = str_replace("\n", '<!--backspace-->', $form_commentary);
		$form_commentary = str_replace("\r", '<!--backspace-->', $form_commentary);


	   	
	   	if ($form_commentary_visible == false) {
	   		$form_commentary_visible = 'false';
	   	}
	   	$script_form_commentary = "var form_commentary = '" . $form_commentary . "';var form_commentary_visible = " . $form_commentary_visible . ';'; 
	   	
		$head->setScript(FunctionsUtils::getJsTranslations($tbl_lang) . FunctionsUtils::getJsTranslations($tbl_lang2,$langue_id) . ' var session_lang_id = "' . $_SESSION[APP]['LANGUE_ID'] . '";' . $script_adm . $script_lp . $script_form_commentary );
    	
		$this->assign('imgpath', Pelican_Plugin::getMediaPath('boforms') . 'images/');
		$this->assign('label_phone', FunctionsUtils::translateEditor('BOFORMS_LABEL_PHONE',$langue_id));
		$head->setJs(Pelican_Plugin::getMediaPath('boforms') . 'editor.js');
		$this->assign('header', $head->getHeader(false), false);
		 
	}
	
	public function checkFormEditableAction() {
		if($_GET['abtesting']=='new')
		{
			$form_detail = FunctionsUtils::getFormulaireFromCode($_GET['code_instance']);
		}elseif($_GET['bNewInstance']){
			$form_detail = FunctionsUtils::getFormulaireFromCode($_GET['bNewInstance']);
		}else{
			$form_detail = FunctionsUtils::getFormulaireFromCode($_GET['code_instance']);
		}
		
        if ($form_detail['FORM_EDITABLE'] == 0) {
        	echo $form_detail['FORM_COMMENTARY'];
        } else {
        	echo "1";
        }
        exit(0);
	}
	
    public function editorAction ()
    {
    	/*** construction du code instance ***/
        $sCodeInstance = $_GET['code_instance'];

        if(empty($sCodeInstance))
        	die('ERROR, BO Forms can\'t load');
        	
        $sCodeInstancGen = substr_replace($sCodeInstance, '9', 5, 1);
        $sCodeInstancGen = substr_replace($sCodeInstancGen, '00', 10, 2);
        $sCodeInstancGen = substr_replace($sCodeInstancGen, '0', 9, 1);
        $sCodeInstancGen = substr_replace($sCodeInstancGen, '0', 8, 1);

        if(!$this->checkInstance($sCodeInstance)){
        	if(!$this->checkInstance($sCodeInstancGen)){
        		die('ERROR, BO Forms can\'t load');
        	}else{
        		$this->bNewInstance = true;// instance pas encore personnalisé
        	}
        }     
        /*** ***/    

        /*** Objet instance, chargement du générique et personnalisé ***/
      
        if($_GET['version']!='CURRENT' && $_GET['version']!='N1')
        {
        	$version = 'DRAFT';
        }else{
        	$version = $_GET['version'];
        }

        
        $oInstance = new FormInstance($sCodeInstance,$_GET['abtesting']);
             
      	$sXMLGeneric = $oInstance->prepareGenericXML($sCodeInstancGen);
      	
      	$oInstance->loadPersoXML($version);
		/*** ***/		
      	//debug($oInstance->oXMLStandard->aField);
    	/*** hidden xmlPerso ***/   
    	
    	$this->assign('form_editable', '1');
    	
	// debug($oInstance->oXMLStandard->dom->saveXML()); die('==========================');

    	$this->assign('xmlPerso', urlencode($oInstance->oXMLStandard->dom->saveXML()), false);
    	/****/
    	
    	$sTypeInstance = $oInstance->getType();
    	    	    	
	    // Récupération des versions draft, current, et draft -1
	    $oInstance->getVersions();
	 	
	    
	    /*** Assignation Smarty ***/
	    $comment_group = $oInstance->getCommentGroup();
	   	if($comment_group)//commentaire du groupe du site
	   	{
	   		$this->assign('comment_group', $comment_group, false);
	   	}

	   	if((int)$sTypeInstance==0)
	   	{
	   		$this->assign('aABtesting',$oInstance->getABTesting(), false);
	   	}
	   	   	
	   	
	   	$this->assign('instance_id',$sCodeInstance, false);
	   	$this->assign('device_id',$oInstance->oXMLStandard->instance['device_id'], false);
		$this->assign('URI', $_SERVER['REQUEST_URI'], false);
	    $this->assign('REDIRECT_URL', $_SERVER['REDIRECT_URL'], false);
	    $this->assign('Location', $_SERVER['REQUEST_URI'], false);
	    $this->assign('hasDraft', $oInstance->hasDraft, false);
	    $this->assign('hasPublish', $oInstance->hasPublish, false);
	    $this->assign('hasN1', $oInstance->hasN1, false);
	   // $this->assign('culture_id',$_GET['culture_id'], false);   
	    $this->assign('aSteps', json_encode($oInstance->getTab()), false);
	    $this->assign('aStepsGeneric', json_encode($oInstance->getFielsdEnabled()), false);
	    $this->assign('aListened', json_encode($oInstance->oXMLStandard->aListened), false);
	    $this->assign('aListening', json_encode($oInstance->oXMLStandard->aListening), false);
    	$this->assign('iDefaultLangID', $iDefaultLangID);
    	$this->assign('aLanguages', $aLanguages);
    	$this->assign('sTypeInstance', $sTypeInstance);
    	$this->assign('sCode', $sCodeInstance);    	
    	$this->assign('get_code', $sCodeInstance);
    	$this->assign('code_parent', $oInstance->getCodeParent());
    	$this->assign('load_tab', $_GET['tab']);
    	$this->assign('state_id', $oInstance->state_id);
    	$this->assign('get_version', $version);
		$this->assign('brand_id', Pelican::$config['BOFORMS_BRAND_ID']);
    	
    	if($this->bNewInstance){
    		$this->assign('bNewInstance', $sCodeInstancGen);
    	}
    	
    	$bLP=false;//site landing page
    	
    	if(FunctionsUtils::isLandingPageSite((int)$oInstance->oXMLStandard->instance['site_id']))
    	{
    		$bLP=true;
    	}
    	$this->assign('bLP',$bLP, false);
    	
    	$commentary = $oInstance->oXMLStandard->domXpath->query("//form/commentary")->item(0);
    	if (empty($commentary)) {
    		$form_commentary = '';
    		$form_commentary_visible = false;	
    	} else {
    		$form_commentary = $oInstance->oXMLStandard->form['commentary'];
    		$form_commentary_visible = $commentary->getAttribute('visible');
    	}
    	$this->editorSetScript($bLP, $form_commentary, $form_commentary_visible);//head
        
    	// get form details
    	
        if($_GET['abtesting']=='new')
    	{
    		$form_detail = FunctionsUtils::getFormulaireFromCode($_GET['code_instance']);
    	}elseif($this->bNewInstance){
    		$form_detail = FunctionsUtils::getFormulaireFromCode($sCodeInstancGen);
    	}else{
    		$form_detail = FunctionsUtils::getFormulaireFromCode($oInstance->oXMLStandard->instance['id']);
    	}


    	if ($form_detail['FORM_EDITABLE'] == 0) {
			$form_title = t('BOFORMS_REFERENTIAL_FORM_TYPE_' . $form_detail['OPPORTUNITE_KEY']) . ' - ' . 
								t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_' . $form_detail['TARGET_KEY']) . ' ' . 
								Pelican::$config['BOFORMS_JIRA']['ENV2'][$_ENV["TYPE_ENVIRONNEMENT"]] . ' ' .
								t('BOFORMS_REFERENTIAL_FORM_CONTEXT_' . $form_detail['CONTEXT_KEY']) . ' - ' .
								t('BOFORMS_REFERENTIAL_DEVICE_' . $form_detail['DEVICE_KEY']);
								
			echo "<div style=\"text-align:center;\"><p>$form_title</p>";    		
    		echo '<p style="color:red;font-weigth:bold;">' . $form_detail['FORM_COMMENTARY'] . "</p></div>";
    		die('');
    	}
    	
    	$this->assign('form_opportunity', $form_detail['OPPORTUNITE_KEY']);
		$this->assign('form_title', t('BOFORMS_REFERENTIAL_FORM_TYPE_' . $form_detail['OPPORTUNITE_KEY']));
		$this->assign('form_contexte', t('BOFORMS_REFERENTIAL_FORM_CONTEXT_' . $form_detail['CONTEXT_KEY'])); 
		$this->assign('form_type_plateforme', $_ENV["TYPE_ENVIRONNEMENT"]);
		$this->assign('form_plateforme', Pelican::$config['BOFORMS_JIRA']['ENV2'][$_ENV["TYPE_ENVIRONNEMENT"]] );
    	$this->assign('form_part_pro', t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_' . $form_detail['TARGET_KEY']));
		$this->assign('form_device',  t('BOFORMS_REFERENTIAL_DEVICE_' . $form_detail['DEVICE_KEY']));
    	
    	if($_GET['abtesting'])
    	{
    		$this->assign('isABtesting',true);
    		
    		if($_GET['abtesting']=='new')
    		{
    			$this->assign('ABtestingName', 'New AB testing' , false);
    			$this->assign('isNewABTesting', 1, false);
    		}else{
    			$this->assign('ABtestingName', 'AB Testing : '. $form_detail['FORM_NAME'] , false);
    		}
    	}
    	    	
    	$oConnection = Pelican_Db::getInstance ();
    	$Ssql = 'select OPPORTUNITE_KEY FROM #pref#_boforms_opportunite where OPPORTUNITE_ID = '.(int)$oInstance->oXMLStandard->instance['opportunite_id'];
    	$sTypeForm=$oConnection->queryItem($Ssql);
    	
    	
    	$this->assign('instance_name', t('BOFORMS_REFERENTIAL_FORM_TYPE_' . $sTypeForm), false);
    	
    	if($_GET['abtesting']!='new' || empty($_GET['abtesting']))
    	{
    		$this->assign('form_name',$form_detail['FORM_NAME'], false);
    	}
    	
    	$this->assign('form_id',$oInstance->oXMLStandard->instance['id'], false);
    	
    	/*** ***/


        //jira 203
        if($_GET['version']=='N1'){
            $status_form = "N-1";
        }elseif($this->bNewInstance){
    		$status_form = t('BOFORMS_DRAFT_VERSION');
    	}else{
            switch ($oInstance->state_id) {
                case Pelican::$config['BOFORMS_STATE']["PUBLISH"]:
                    $status_form = t('BOFORMS_PUBLISHED_VERSION');
                    break;
                case Pelican::$config['BOFORMS_STATE']["DRAFT"]:
                case Pelican::$config['BOFORMS_STATE']["AUTO"]:
                    $status_form = t('BOFORMS_DRAFT_VERSION');
                    break;
                default:
                    $status_form = "UNKNOWN";
                    break;
            }
        }
        
        if($this->bNewInstance){
    		$dateVersion = date("Y-m-d H:i:s");
    	}else{
        	$dateVersion = $oInstance->date_version;
    	}

        $this->assign('messageVersion', $status_form .' ('. FunctionsUtils::getDateFormat($dateVersion, $_SESSION[APP]['LANG']) .')' );

        $this->fetch();
    }

	
	public function overwriteAction()
	{
	    $oInstance = new FormInstance($_GET['code_instance']);
	    $sXml = $oInstance->overwriteVersion($_GET['version']);
	    $oInstance->setXmlStandard($sXml, 'xml');
	     
	    $this->_forward ('editor');
	}
	
	
	public function deleteDraftAjaxAction() {
		$oConnection = Pelican_Db::getInstance ();
    	$aBind[':SCODE'] = $oConnection->strToBind($this->getParam('scode')); 
		$results = $oConnection->queryTab('SELECT FORM_VERSION, STATE_ID FROM #pref#_boforms_formulaire_version 
											WHERE FORM_INCE = :SCODE 
											ORDER BY FORM_VERSION desc', $aBind);

		if (count($results) > 0) {
			// update form_current_version in boforms_formulaire
			if ($results[0]['STATE_ID'] == '2') {
				$oConnection->query('UPDATE #pref#_boforms_formulaire 
									 SET form_draft_version = form_current_version 
									 WHERE FORM_INCE = :SCODE', $aBind);
			}
			
			// search for draft version
			for ($i = 0; $i < count($results); $i++) {
				if ($results[$i]['STATE_ID'] == '2') {
					$aBind[':TMP_FORM_VERSION'] = $results[$i]['FORM_VERSION'];
					$oConnection->query('delete FROM #pref#_boforms_trace 
								 	 where form_ince = :SCODE and form_version = :TMP_FORM_VERSION', $aBind);
					break;
				}
			}
			
			// delete version with state_id = 2 (draft)
			$oConnection->query('DELETE FROM #pref#_boforms_formulaire_version 
								 WHERE FORM_INCE = :SCODE and STATE_ID = 2', $aBind);
		}
		
		exit(0);
	}
	
	public function restorePreviousVersionAjaxAction() {
		// restaurer la version N-1
		$this->restorePreviousVersionFromScode($this->getParam('scode'));
				
		// restaurer la version N-1 pour les formulaires contextualisés
		$oConnection = Pelican_Db::getInstance ();
    	$aBind[':FORM_PARENT_INCE'] = $oConnection->strToBind($this->getParam('scode'));
		$contextualized = $oConnection->queryTab('SELECT FORM_INCE FROM #pref#_boforms_formulaire 
								WHERE FORM_CONTEXT > 0 AND FORM_PARENT_INCE = :FORM_PARENT_INCE', 	$aBind);
		if (! empty($contextualized)) {
			for ($i = 0 ; $i < count($contextualized); $i++) {
				$this->restorePreviousVersionFromScode($contextualized[$i]['FORM_INCE']);
			}
		}					
		exit(0);
	}
	
	private function restorePreviousVersionFromScode($scode) {
		$oConnection = Pelican_Db::getInstance ();
    	$aBind[':SCODE'] = $oConnection->strToBind($scode);
    	
    	$row = $oConnection->queryRow('SELECT FORM_CURRENT_VERSION,FORM_DRAFT_VERSION, FORM_NAME, FORM_ID, FORM_INSTANCE_NAME, FORM_TYPE
    							FROM #pref#_boforms_formulaire 
    							WHERE FORM_INCE = :SCODE', $aBind);

    	$aBind[':NEW_VERSION'] = ($row['FORM_CURRENT_VERSION'] > $row['FORM_DRAFT_VERSION']) ? $row['FORM_CURRENT_VERSION'] + 1 : $row['FORM_DRAFT_VERSION'] + 1;	
    	$aBind[':STATE_ID_PUBLISH'] = Pelican::$config['BOFORMS_STATE']['PUBLISH'];
    	
    	$results = $oConnection->queryTab('SELECT FORM_VERSION, STATE_ID, FORM_XML_CONTENT FROM #pref#_boforms_formulaire_version 
											WHERE FORM_INCE = :SCODE and STATE_ID = :STATE_ID_PUBLISH
											ORDER BY FORM_VERSION desc', $aBind);

		if (count($results) > 1) {
			$aBind[':FORM_TOP_VERSION'] = $results[0]['FORM_VERSION']; 				// version N to delete
			$aBind[':FORM_PREVIOUS_VERSION'] = $results[1]['FORM_VERSION']; 	// version N-1 to restore
			$xml_restored = $results[1]['FORM_XML_CONTENT'];
			
			$aBind[':UPDATE_DATE'] = $oConnection->strToBind(date('Y-m-d H:i:s'));
			
			$oConnection->query('UPDATE #pref#_boforms_formulaire_version 
								 SET FORM_VERSION = :NEW_VERSION, FORM_DATE = :UPDATE_DATE 
								 WHERE FORM_INCE = :SCODE and FORM_VERSION = :FORM_PREVIOUS_VERSION', $aBind);
					
			$oConnection->query('UPDATE #pref#_boforms_formulaire 
								 SET form_current_version = :NEW_VERSION , form_draft_version = :NEW_VERSION
								 WHERE FORM_INCE = :SCODE', $aBind);

			// delete version with state_id = 2 (draft)
			$aBind[':STATE_ID_DRAFT'] = Pelican::$config['BOFORMS_STATE']['DRAFT'];
			$oConnection->query('DELETE FROM #pref#_boforms_formulaire_version 
								 WHERE FORM_INCE = :SCODE and STATE_ID = :STATE_ID_DRAFT', $aBind);  

			// delete old top version (old version N)
			//$oConnection->query('DELETE FROM #pref#_boforms_formulaire_version 
			//					 WHERE FORM_INCE = :SCODE and FORM_VERSION = :FORM_TOP_VERSION and STATE_ID = 1', $aBind);  
		
			/**** Webservice update xml ***/
			$this->udpdateWS($scode, $row['FORM_INSTANCE_NAME'], $row['FORM_ID'], $row['FORM_NAME'], $row['FORM_TYPE'], $xml_restored);
			/******/
		}
	}
	
	public function saveAutoAjaxAction(){
				
		$this->bDraft=true;
		$this->bDraftAuto=true;
				
	
		$this->_forward('save');
		
		
	}
	
	public function beforeSaveAction()
	{
		
		if(isset($_POST['draft']))
		{
			$this->_forward('draft');
		}elseif (isset($_POST['publier'])){
			$this->_forward('save');
		}

	}
	
	public function draftAction()
	{
		$this->bDraft = true;
		$this->_forward('save');
	}
	

    public function saveAction ()
    {
    	
    	if(!$_POST['xmlPerso'])
    		throw new Exception('saveAction : la variable de POST xmlPerso est vide');
    	
    	    	
    	$oConnection = Pelican_Db::getInstance ();
    	    	
    	//$code_instance = "ACBE100100100003";
    	    	
    	/*** DOM du personnalisé avant modification ***/
	    	/*$sqlCulture = "SELECT CULTURE_ID,CULTURE_LABEL,CULTURE_KEY
	    				  FROM #pref#_site_language sl
	    				  INNER JOIN #pref#_language l ON (l.LANGUE_ID=sl.LANGUE_ID)
	    				  INNER JOIN #pref#_boforms_culture bc ON (l.LANGUE_ID = bc.LANGUE_ID)
	    				  WHERE SITE_ID = ".$_SESSION[APP]['SITE_ID']."
	    				  ";
	    	   	
	    	$aCulture = $oConnection->queryTab($sqlCulture);
	    	
	    	$aXMLStandard=$this->getXMLStandard($code_instance,"CURRENT",$aCulture);*/

    		$sXMLStandard=urldecode($_POST['xmlPerso']);

    		$this->oXMLOriginal = new XMLHandle($sXMLStandard,'xml');
    		$this->oXMLOriginal->loadGenericXML();
    		$this->oXMLOriginal->Parser_read();
			$this->oXMLOriginal->setXmlOriginalTagGtm(); // marks gtm_tag html with attribute "itkg_gtm_data"
			$this->oXMLOriginal->setDomXPathTemp();	
    	
    	    
    	/*** Tableau de structure du xml ***/
    	$json = $_POST['result'];
    	
		if($json)
		{
    		$aTab=json_decode($_POST['result'],true);
    		
    		$this->setStructure($aTab);
    	}  

    	/******/
	
    	$bABTesting=false;
    	
    	if((int)substr($this->oXMLOriginal->instance['id'],8,1)>0)
    	{
    		$bABTesting=true;
    	}
    	
    	$bLP=false;//site landing page
    	
    	if(FunctionsUtils::isLandingPageSite((int)$this->oXMLOriginal->instance['site_id']))
    	{
    		$bLP=true;
    	}
    	
    	$bcontext=false;
    	
		if($this->oXMLOriginal->instance['context']==0)//to do constante
		{   
			//standard

			/*** suppression field ***/
	    	$this->checkForDelete();	  	    	
	    	/******/
	    		
	    	/*** ajout field ***/
			$this->checkForAdd();
	    	/******/   		
				
			/*** Move Field ***/
			$this->checkForMoveField();
			/******/
				
	    	/*** Move structure ***/
	    	$this->checkForMoveStructure();
	    	/******/

	    	/*** ajout des listeners manquants ***/
	    	$this->checkForMissingListeners();
	    	
			// PATCH JIRA 710
			$this->checkForReferentialValues();
	    	
	    	/*** modification propriétés  ***/
	    	$this->checkForEditField();	    	

	    	/*** save the configuration node for a step ***/
			$this->checkStepConfiguration();
			/******/
				    	
	    	$this->checkForFormCommentary($_POST['formCommentary'], $_POST['formCommentaryVisible']);
	    	
	    	/*** recherche des champs cachés présents dans le générique et pas dans le personalisé ***/
	    	$this->checkForMissingHiddenFields();
			
	    	// modification du html contenant gtm(data)
			$this->updateTagGtm($this->oXMLOriginal->instance['id']);	    	

			// ecrasement des tags gtm du generique sur le perso
			$this->updateGtmTagsFromGenerique();
						
			// update des tags gtm qui se trouvent sous une question
			$this->checkStepTagsUnderQuestion(); 
			
	    	// die($this->oXMLOriginal->dom->saveXML());

	    	/******/
	    		
    		/*** supprime les structure temporaire vide (conséquence du change étape d'un field)  ***/
    		$this->checkForClearTmpStructure();

		/*** supprime les lines vides ***/
    		$this->checkForClearLineStructure();

    		/******/
    					
			/*** Move Etape ***/
			$this->checkForChangeEtape();
			/******/
			
			$this->deleteEmptyQuestions();
						
			//print_r($this->oXMLOriginal->dom->saveXML());die('la____________');
			
			/*debug($this->oXMLOriginal->dom->saveXML());
			die;*/
		}elseif ($this->oXMLOriginal->instance['context']==1 || $this->oXMLOriginal->instance['context']==2)
		{
			$bcontext=true;
			//contextualisé
			
			/*** Move Etape ***/
	    	 	$this->checkForChangeEtape();
	    	/******/
		}
		
		/**VALIDATE XSD*/
			
		$isValid = $this->oXMLOriginal->ValidationXSD();
			
		if(!$isValid)
		{//xml invalid on stop l'enregistrement
			echo "Save failed : invalid XML";
			
			$log = "[".date('Y-m-d H:i:s').'][invalid xml] '. print_r($this->oXMLOriginal->dom->saveXML(), true) .'\r\n';
			error_log($log, 3, FunctionsUtils::getLogPath() . 'debugXmlError.log');
    	
			die;
		}
		
		/*** appliquer les modifs aux contextualisÃ©s ***/
		//les contextualisé ne sont plus a créer cf BOFORMS-571
		if(!$this->bDraft) {
		//	$this->saveContextualises();
		}
		/******/
		
		
		/*** Save de la version standard ***/
    		$aBind = array();
	    	$aBind[':FORM_INCE'] = $oConnection->strToBind($this->oXMLOriginal->instance['id']);
			$sql = "select FORM_CURRENT_VERSION,FORM_DRAFT_VERSION, FORM_NAME, FORM_INSTANCE_NAME,FORM_ID,FORM_TYPE from #pref#_boforms_formulaire where FORM_INCE= :FORM_INCE";
			$aRes = $oConnection->queryRow($sql,$aBind);

			
			/*calcule des versions*/
			if($this->bDraft)
			{
				if($this->bDraftAuto)
				{//sauvegarde auto
			
					$sSqlAuto = "select STATE_ID from #pref#_boforms_formulaire_version where FORM_INCE=:FORM_INCE order by FORM_VERSION desc limit 1";
					$LastStateVersion = $oConnection->queryItem($sSqlAuto,$aBind);
				
					if($aRes['FORM_CURRENT_VERSION']==$aRes['FORM_DRAFT_VERSION'] || (int)$LastStateVersion != Pelican::$config['BOFORMS_STATE']['AUTO'])
					{
						$icurrVersion = $aRes['FORM_CURRENT_VERSION'];
						$idraftVersion = $aRes['FORM_DRAFT_VERSION']+1;
					}else{
						
						$icurrVersion = $aRes['FORM_CURRENT_VERSION'];
						$idraftVersion = $aRes['FORM_DRAFT_VERSION'];
						
					}
				}else{//enregistrer
					$icurrVersion = $aRes['FORM_CURRENT_VERSION'];
					$idraftVersion = $aRes['FORM_DRAFT_VERSION']+1;
				}
					
			}else{//Publier
				$icurrVersion = $aRes['FORM_DRAFT_VERSION']+1;
				$idraftVersion = $aRes['FORM_DRAFT_VERSION']+1;
			}
			/**/
			
			if(empty($icurrVersion))
			{
				$icurrVersion = 'NULL';
			}
			if(empty($idraftVersion))
			{
				$idraftVersion='NULL';
			}
			
			
			if($aRes)
			{
				$bNew = false;
				
				if($bABTesting && $_POST['ABTestingTitle'])
				{
					$aBind[':ABTESTING_NAME'] = $oConnection->strToBind($_POST['ABTestingTitle']);
					
					$sqlUpdateForm = "update #pref#_boforms_formulaire
						  set FORM_CURRENT_VERSION =".$icurrVersion.",
						  	  FORM_DRAFT_VERSION = ".$idraftVersion.",
						  	  FORM_NAME = :ABTESTING_NAME
						  where FORM_INCE = :FORM_INCE
						  ";
				}else{
				
					$sqlUpdateForm = "update #pref#_boforms_formulaire
						  set FORM_CURRENT_VERSION =".$icurrVersion.",
						  	  FORM_DRAFT_VERSION = ".$idraftVersion."
						  where FORM_INCE = :FORM_INCE
						  ";
				}
				
				if($bABTesting && $_POST['ABTestingTitle'])
				{
					$formName = $_POST['ABTestingTitle'];
				}else{
					$formName = $aRes['FORM_NAME'];
				}
				$instanceName = $aRes['FORM_INSTANCE_NAME'];
				//$formName = $aRes['FORM_NAME'];
				$formId = $aRes['FORM_ID'];
				$formType = $aRes['FORM_TYPE'];
				
				$xml = $this->oXMLOriginal->dom->saveXML();
			}else{//cas oÃƒÂ¹ il n'y a pas encore eu de version personalisÃ©
				$bNew = true;
				
				$codeGene = substr_replace($this->oXMLOriginal->instance['id'],'9',5,1);
				$codeGene = substr_replace($codeGene,'00',10,2);
				$codeGene = substr_replace($codeGene,'0',9,1);
				$codeGene = substr_replace($codeGene,'0',8,1);
			
				$aBind[':FORM_INCE'] = $oConnection->strToBind($codeGene);
				$aResGeneric = $oConnection->queryRow($sql,$aBind);
			
				
				
				$context=substr($this->oXMLOriginal->instance['id'],9,1);
				$num_ABtesting = (int)substr($this->oXMLOriginal->instance['id'],8,1);
												
				$instanceName = $this->generateInstanceName($codeGene,$this->oXMLOriginal->instance['id'],$aResGeneric['FORM_INSTANCE_NAME'],$context,$num_ABtesting);
				//$formName = $this->generateFormName($instanceName);
				$formId = $this->generateFormid($aResGeneric['FORM_ID'],$context,$num_ABtesting,$this->oXMLOriginal->instance['culture_id']);
				$formType = $aResGeneric['FORM_TYPE'];

				if($bABTesting && $_POST['ABTestingTitle'])
				{
					$formName = $_POST['ABTestingTitle'];
				}else{
					$formName = $this->generateFormName($instanceName);
				}
				
				/**
				$icurrVersion = 1;
				$idraftVersion = 1;
				*/
												
				$parent = $this->oXMLOriginal->oXMLGeneric->instance['id'];
				if($num_ABtesting>0)
				{
					$parent = substr_replace($this->oXMLOriginal->instance['id'],0,8,1);
				}
				

				$aBind[':FORM_INCE'] = $oConnection->strToBind($this->oXMLOriginal->instance['id']);
				
				if($bABTesting && $_POST['ABTestingTitle'])
				{
					$aBind[':FORM_NAME'] = $oConnection->strToBind($_POST['ABTestingTitle']);
				}else{
					$aBind[':FORM_NAME'] = $oConnection->strToBind($formName);
				}
				
				$aBind[':FORM_INSTANCE_NAME'] = $oConnection->strToBind($instanceName);
				$aBind[':FORM_ID'] = $oConnection->strToBind($formId);
				$aBind[':FORM_TYPE'] = $oConnection->strToBind($formType);
				
				$aBind[':FORM_CONTEXT'] = (int)substr($this->oXMLOriginal->instance['id'],9,1);
				$aBind[':FORM_CURRENT_VERSION'] = 1;
				$aBind[':FORM_DRAFT_VERSION'] = 1;
				$aBind[':FORM_PARENT_INCE'] = $oConnection->strToBind($parent);
				$aBind[':DEVICE_ID'] = (int)substr($this->oXMLOriginal->instance['id'],12,1);
				$aBind[':TARGET_ID'] = (int)substr($this->oXMLOriginal->instance['id'],4,1);
				$aBind[':CULTURE_ID'] = (int)substr($this->oXMLOriginal->instance['id'],10,2);
				$aBind[':FORMSITE_ID'] = (int)substr($this->oXMLOriginal->instance['id'],6,2);
				$aBind[':PAYS_CODE'] = $oConnection->strToBind(substr($this->oXMLOriginal->instance['id'],2,2));
				$aBind[':FORM_BRAND'] = $oConnection->strToBind(substr($this->oXMLOriginal->instance['id'],0,2));
				$aBind[':FORM_AB_TESTING'] = ($num_ABtesting>0?$num_ABtesting:null);
				$aBind[':OPPORTUNITE_ID'] = (int)substr($this->oXMLOriginal->instance['id'],14,2);
				//$aBind[':FORM_GENERIC'] = (int)substr($this->oXMLOriginal->instance['id'],9,1);
				
				$sqlUpdateForm = "insert into #pref#_boforms_formulaire
							  (FORM_INCE,FORM_NAME,FORM_CONTEXT,FORM_CURRENT_VERSION,FORM_DRAFT_VERSION,FORM_PARENT_INCE,DEVICE_ID,TARGET_ID,CULTURE_ID,FORMSITE_ID,PAYS_CODE,FORM_BRAND,FORM_AB_TESTING,OPPORTUNITE_ID,FORM_EDITABLE,FORM_INSTANCE_NAME,FORM_ID,FORM_TYPE)
							   values (:FORM_INCE,:FORM_NAME,:FORM_CONTEXT,:FORM_CURRENT_VERSION,:FORM_DRAFT_VERSION,:FORM_PARENT_INCE,:DEVICE_ID,:TARGET_ID,:CULTURE_ID,:FORMSITE_ID,:PAYS_CODE,:FORM_BRAND,:FORM_AB_TESTING,:OPPORTUNITE_ID,1,:FORM_INSTANCE_NAME,:FORM_ID,:FORM_TYPE)
						 	 ";
				
				$xml = $this->oXMLOriginal->dom->saveXML();
				
				$xml = str_replace($this->oXMLOriginal->oXMLGeneric->instance['name'],$instanceName,$xml);
				$xml = str_replace($this->oXMLOriginal->oXMLGeneric->form['id'],$formId,$xml);
			}
		
			if($this->oXMLOriginal->structureTitleFieldSetToSave)
			{
				$xml = $this->oXMLOriginal->revertStructureTitle($xml);
			}
			
			//$aBind[':FORM_XML_CONTENT'] = $oConnection->strToBind($this->oXMLOriginal->dom->saveXML());
			$aBind[':FORM_DATE'] = $oConnection->strToBind(date("Y-m-d H:i:s"));
			$aBind[':USER_LOGIN'] = $oConnection->strToBind($_SESSION[APP]['backoffice']['USER_LOGIN']);
			
			if($this->bDraftAuto)
			{
				if($aRes['FORM_CURRENT_VERSION']==$aRes['FORM_DRAFT_VERSION'] || (int)$LastStateVersion != Pelican::$config['BOFORMS_STATE']['AUTO'])
				{
					
					$sqlUpdate = "insert into #pref#_boforms_formulaire_version (FORM_INCE,FORM_VERSION,FORM_XML_CONTENT,FORM_DATE,FORM_LOG,USER_LOGIN,STATE_ID)
							  values (:FORM_INCE,".$idraftVersion.",'".addslashes($xml)."',:FORM_DATE,NULL,:USER_LOGIN,".Pelican::$config['BOFORMS_STATE']['AUTO'].")
							  ";
															
				}else{
					$sqlUpdate = "update #pref#_boforms_formulaire_version
							  set FORM_XML_CONTENT ='".addslashes($xml)."',
							  	  FORM_DATE = :FORM_DATE,
							  	  USER_LOGIN = :USER_LOGIN,
							  	  STATE_ID = ".Pelican::$config['BOFORMS_STATE']['AUTO']."
							  where FORM_INCE = :FORM_INCE
							  AND FORM_VERSION = $idraftVersion
							  ";
				}
				
			}else{
				if($this->bDraft)
				{
					$state = Pelican::$config['BOFORMS_STATE']['DRAFT'];
				}else{
					$state = Pelican::$config['BOFORMS_STATE']['PUBLISH'];
				}
				
				$sqlUpdate = "replace into #pref#_boforms_formulaire_version (FORM_INCE,FORM_VERSION,FORM_XML_CONTENT,FORM_DATE,FORM_LOG,USER_LOGIN,STATE_ID)
							  values (:FORM_INCE,".$idraftVersion.",'".addslashes($xml)."',:FORM_DATE,NULL,:USER_LOGIN,$state)
							  ";
			}
			
		if(!$this->bDraft)
		{
			/**** Webservice update xml ***/
			$this->udpdateWS($this->oXMLOriginal->instance['id'],$instanceName,$formId,$formName,$formType,$xml);
			/******/
		}
			$oConnection->query($sqlUpdateForm,$aBind);
			$oConnection->query($sqlUpdate,$aBind);		
	    /****/		
	
		/*** enregistrement du tracage des modifications ***/

			if(!$this->bDraftAuto && !empty($this->collLog))
			{	
				$aBind[':USER_LOGIN'] = $oConnection->strToBind($_SESSION[APP]['backoffice']['USER_LOGIN']);
				$aBind[':TRACE_CONTENT'] = $oConnection->strToBind($this->getLogJSON());

				$sqlUpdate = "replace into #pref#_boforms_trace (FORM_INCE,FORM_VERSION,USER_LOGIN,TRACE_CONTENT)
						  values (:FORM_INCE,".$idraftVersion.",:USER_LOGIN,'".addslashes($this->getLogJSON())."')
						  ";
										  	
				$oConnection->query($sqlUpdate,$aBind);
			}
		/*** ***/	
			
			
		/**** on supprime les versions antérieurs ****/
		if(!$this->bDraftAuto && !$bNew)
		{
			$this->CleanVersions($this->oXMLOriginal->instance['id'],$this->bDraft);
		}
		/***/
		
		/**** standard site du groupe ****/
		$num_ABtesting=(int)substr($this->oXMLOriginal->instance['id'],8,1);
		if(!$this->bDraft && empty($num_ABtesting))
		{
			$this->saveStandardGroupe($bcontext);
		}
		/****/
		
		if($this->bDraft)
		{
			echo t('BOFORMS_CONFIRM_DRAFT_SAVED');
		}else{
			echo t('BOFORMS_CONFIRM_PUBLISH_SAVED');
		}

    //debug($this->oXMLOriginal->dom->saveXML(),'original dom aprés modif');   	
  
    }
    
    public function updateReferencesAction() {
    	FunctionsUtils::updateReferences();
    }
    
    //supprime les version autre que dernière draft, plublié, N-1 et la version initial
    public function CleanVersions($codeInstance,$bDraft=false)
    {
    	$oConnection = Pelican_Db::getInstance();
    	$aBind[':FORM_INCE'] = $oConnection->strToBind($codeInstance);
    	
    	//save auto -> on supprime la sauvegarde auto
    	if(Pelican::$config['BOFORMS_STATE']['AUTO'] != Pelican::$config['BOFORMS_STATE']['DRAFT'])
    	{
	    	$sqlCleanSaveAuto = "delete from #pref#_boforms_formulaire_version 
	    			             where FORM_INCE=:FORM_INCE 
	    			             AND STATE_ID=".Pelican::$config['BOFORMS_STATE']['AUTO'];
	    	
	    	$oConnection->query($sqlCleanSaveAuto,$aBind);
    	}
      
    	if($bDraft)
    	{
	    	//draft -> on supprime la précédente version draft
	    	$sSQL = "select max(FORM_VERSION) 	 
	    			 from #pref#_boforms_formulaire_version
	    			 where FORM_INCE=:FORM_INCE
	    			 and STATE_ID=".Pelican::$config['BOFORMS_STATE']['DRAFT'];
	    	$iDraft = $oConnection->queryItem($sSQL,$aBind);
	    	
	    	if((int)$iDraft>0)
	    	{
		    	$sqlCleanDraft = "delete from #pref#_boforms_formulaire_version 
		    			          where FORM_INCE=:FORM_INCE 
		    			          AND STATE_ID=".Pelican::$config['BOFORMS_STATE']['DRAFT']." 
		    			          AND FORM_VERSION <> $iDraft
		    			          AND FORM_VERSION > 1";
		    	
		    	$oConnection->query($sqlCleanDraft,$aBind);
	    	}
    	}else{
      	
	    	//publier -> on supprimer les versions publiés antérieur à N-1 tout en gardant la première version utlise pour le reset
	    	
	    	$sSQL = "select FORM_VERSION
	    			 from #pref#_boforms_formulaire_version
	    			 where FORM_INCE=:FORM_INCE
	    			 and STATE_ID=".Pelican::$config['BOFORMS_STATE']['PUBLISH']."
	    			 order by FORM_VERSION desc limit 2";
	    	$aPub = $oConnection->queryTab($sSQL,$aBind);
	    	
	    	if(!empty($aPub) && sizeof($aPub)==2)
	    	{
	    	  		
	    		
		    	$sqlCleanPub = "delete from #pref#_boforms_formulaire_version
		    			          where FORM_INCE=:FORM_INCE
		    			          AND STATE_ID=".Pelican::$config['BOFORMS_STATE']['PUBLISH']."
		    			          AND FORM_VERSION not in (".$aPub[0]['FORM_VERSION'].",".$aPub[1]['FORM_VERSION'].")
		    			          AND FORM_VERSION > 1";
		    	
		    	$oConnection->query($sqlCleanPub,$aBind);
	    	}
    	
    	}
    }
    
       
    public function getXMLStandard($code_instance,$version="CURRENT",$aCulture){
    	/*récup des xml personnalisé avant modification*/
    	$oConnection = Pelican_Db::getInstance();
    	
    	if(is_array($aCulture)){
    		
    		foreach ($aCulture as $culture)
    		{
    			if(strlen($culture['CULTURE_ID']==1)){
    				$culture['CULTURE_ID']="0".$culture['CULTURE_ID'];
    			}
    			//var_dump($culture['CULTURE_ID']);    	
    			$code_instance_culture = substr_replace($code_instance,$culture['CULTURE_ID'],10,2); 			
    					
		    	$sqlXML = 'select FORM_XML_CONTENT 
		    					   from #pref#_boforms_formulaire_version bfv
		    					   INNER JOIN #pref#_boforms_formulaire bf ON (bf.FORM_INCE=bfv.FORM_INCE and bfv.FORM_VERSION=bf.FORM_'.$version.'_VERSION )
		    					   where bfv.FORM_INCE = "'.$code_instance_culture.'"
		    					   ';
		    	$xml[$culture['CULTURE_ID']]=$oConnection->queryItem($sqlXML);
    		}
    	}
    	   	
    	if($xml)
    	{
    		return $xml;
    	}
    	
    	return false;
    	   	   	
    }
    
    /*récup du xml generique*/
    /*public function getXMLGeneric($code_instance,$version="CURRENT"){
    	
    	
    	$code_instance_generic = substr_replace($code_instance,9,5,1);
    	
    	    	
    	$oConnection = Pelican_Db::getInstance();
    	$aBind[':CODE_INSTANCE'] = $oConnection->strToBind($code_instance_generic);
    	
    	$sqlXML = "select bfv.FORM_XML_CONTENT 
    					   from #pref#_boforms_formulaire_version bfv
    					   INNER JOIN #pref#_boforms_formulaire bf ON (bf.FORM_INCE=bfv.FORM_INCE and bfv.FORM_VERSION=bf.FORM_".$version."_VERSION )
    					   where bfv.FORM_INCE = :CODE_INSTANCE
    					   ";
    	
    	
    	$xml=$oConnection->queryItem($sqlXML,$aBind);
    	
    	if($xml)
    	{
    		return $xml;
    	}
    	
    	return false;
    }*/
    
    
    public function getXMLContextualise($code_instance,$version="CURRENT")
    {
    	$code_instance_contextualise = substr_replace($code_instance,9,5,1);
    	
    	$sqlXML = "select FORM_XML_CONTENT 
    					   from #pref#_boforms_formulaire_version bfv
    					   INNER JOIN #pref#_boforms_formulaire ON bf (bf.FORM_INCE=bfv.FORM_INCE and bfv.FORM_VERSION=bf.FORM_".$version."_VERSION )
    					   where FORM_INCE = $code_instance
    					   ";
    	
    	
    }
    
     public function getXMLCulture($code_instance,$version="CURRENT",$aCulture)
    {
    	
    	if(is_array($aCulture)){
    		
    		$sqlXML = "select FORM_XML_CONTENT 
    					   from #pref#_boforms_formulaire_version bfv
    					   INNER JOIN #pref#_boforms_formulaire ON bf (bf.FORM_INCE=bfv.FORM_INCE and bfv.FORM_VERSION=bf.FORM_".$version."_VERSION )
    					   where FORM_INCE = $code_instance
    					   ";
    		
    		foreach ($aCulture as $culture)
    		{
    			
    		}
    		
    	}
    
    }
    
    
    protected function setStructure($aTab)
    {
    	    	
    	if ($aTab)
    	{
    	
    		$ifielSet = 0;
    		$iquestion = 0;
    		$iline = 0;
    		
    		$iGlobalPage=0;
			$iGlobFieldSet=0;
			$iGlobQuestion=0;
			$iGlobLine=0;
    		    		
			foreach ($aTab as $kPage=>$aPage)
    		{
    			$this->aPages[$kPage]['title'] = $aPage['title'];
    			$this->aPages[$kPage]['itkg_code'] = $aPage['itkg_code'];
    			$this->aPages[$kPage]['position'] = $kPage;
    			
    			$this->aPageStructure[$aPage['itkg_code']]['title'] = $aPage['title'];
				$this->aPageStructure[$aPage['itkg_code']]['itkg_code'] = $aPage['itkg_code'];
				$this->aPageStructure[$aPage['itkg_code']]['position'] = $kPage;

				if (isset($aPage['stepConfiguration'])) {
					$this->aStepConfiguration[$kPage] = $aPage['stepConfiguration']; 	
				}				

				$this->aTagsUnderQuestion[$kPage] = $aPage['tagsUnderQuestion'];
				
    			$iGlobFieldSet=0;
    			
    			foreach ($aPage['fieldsets'] as $kfieldSet=>$fieldSet)
    			{	
	    			$this->aPersoStructure[$kPage]['fieldSet'][$ifielSet]['itkg_code']=$fieldSet['name'];
	    			
	    			
	    			$iGlobQuestion=0;
	    			$this->aGlobalPersoStructure[$fieldSet['name']]['niveau'] = 'fieldSet';
	    			$this->aGlobalPersoStructure[$fieldSet['name']]['xpath'] = $aPage['itkg_code']."/".$fieldSet['name'];
		        	$this->aGlobalPersoStructure[$fieldSet['name']]['page'] = $aPage['itkg_code'];
		        	$this->aGlobalPersoStructure[$fieldSet['name']]['num_page'] = $kPage;
		        	$this->aGlobalPersoStructure[$fieldSet['name']]['fieldSet'] = $fieldSet['name'];
		        	$this->aGlobalPersoStructure[$fieldSet['name']]['question'] = "";
		        	$this->aGlobalPersoStructure[$fieldSet['name']]['line'] = "";
		        	$this->aGlobalPersoStructure[$fieldSet['name']]['position'] = $iGlobFieldSet;
		        	$this->aGlobalPersoStructure[$fieldSet['name']]['fullpath'] = $aPage['itkg_code']."/".$fieldSet['name'].'/'.$iGlobFieldSet;
	    			
		        	foreach ($fieldSet['questions'] as $kquestions=>$questions)
	    			{
	    				$this->aPersoStructure[$kPage]['fieldSet'][$ifielSet]['question'][$iquestion]['itkg_code']=$questions['name'];
	    				
	    				
	    				$iGlobLine=0;
	    				$this->aGlobalPersoStructure[$questions['name']]['niveau'] = 'question';
	    				$this->aGlobalPersoStructure[$questions['name']]['xpath'] = $aPage['itkg_code']."/".$fieldSet['name']."/".$questions['name'];
			        	$this->aGlobalPersoStructure[$questions['name']]['page'] = $aPage['itkg_code'];
			        	$this->aGlobalPersoStructure[$questions['name']]['num_page'] = $kPage;
			        	$this->aGlobalPersoStructure[$questions['name']]['fieldSet'] = $fieldSet['name'];
			        	$this->aGlobalPersoStructure[$questions['name']]['question'] = $questions['name'];
			        	$this->aGlobalPersoStructure[$questions['name']]['line'] = "";
			        	$this->aGlobalPersoStructure[$questions['name']]['position'] = $iGlobQuestion;
			        	$this->aGlobalPersoStructure[$questions['name']]['fullpath'] = $aPage['itkg_code']."/".$fieldSet['name']."/".$questions['name'].'/'.$iGlobQuestion;
    					if ($questions['isNewQuestion'] == 1) {
    						$questionIsEmpty = count($questions['lines']) == 0;
    						$this->createNewQuestion($this->aGlobalPersoStructure[$questions['name']], $questionIsEmpty, $fieldSet['questions']);
    					}
	    				
	    				foreach ($questions['lines'] as $kline=>$line)
	    				{
	    					
	    					$this->aPersoStructure[$kPage]['fieldSet'][$ifielSet]['question'][$iquestion]['line'][$iline]['itkg_code']=$line['name'];
	    						    					
	    					
	    					$this->aGlobalPersoStructure[$line['name']]['niveau'] = 'line';
		    				$this->aGlobalPersoStructure[$line['name']]['xpath'] = $aPage['itkg_code']."/".$fieldSet['name']."/".$questions['name']."/".$line['name'];
				        	$this->aGlobalPersoStructure[$line['name']]['page'] = $aPage['itkg_code'];
				        	$this->aGlobalPersoStructure[$line['name']]['num_page'] = $kPage;
				        	$this->aGlobalPersoStructure[$line['name']]['fieldSet'] = $fieldSet['name'];
				        	$this->aGlobalPersoStructure[$line['name']]['question'] = $questions['name'];
				        	$this->aGlobalPersoStructure[$line['name']]['line'] = $line['name'];
				        	$this->aGlobalPersoStructure[$line['name']]['position'] = $iGlobLine;
				        	$this->aGlobalPersoStructure[$line['name']]['fullpath'] = $aPage['itkg_code']."/".$fieldSet['name']."/".$questions['name']."/".$line['name'].'/'.$iGlobLine;
	    					
	    					
	    					$iField = 0;
	    					
	    					
	    					foreach ($line['fieldsStandard'] as $kfield=>$field)
	    					{
	    						
	    						$this->aPersoStructure[$kPage]['fieldSet'][$ifielSet]['question'][$iquestion]['line'][$iline]['field'][$iField]['itkg_code']=$field['name'];
	    						
	    						$this->aPerso[$field['name']]=$field;
	    						$this->aPerso[$field['name']]['itkg_code']=$field['name'];
	    						$this->aPerso[$field['name']]['xpath']=$aPage['itkg_code'].'/'.$fieldSet['name'].'/'.$questions['name'].'/'.$line['name'];
	    						$this->aPerso[$field['name']]['page']=$aPage['itkg_code'];
	    						$this->aPerso[$field['name']]['num_page']=$kPage;
	    						$this->aPerso[$field['name']]['fieldSet']=$fieldSet['name'];
	    						$this->aPerso[$field['name']]['question']=$questions['name'];
	    						$this->aPerso[$field['name']]['line']=$line['name'];
	    						$this->aPerso[$field['name']]['position']=$iField;
	    						$this->aPerso[$field['name']]['fullpath']=$aPage['itkg_code'].'/'.$fieldSet['name'].'/'.$questions['name'].'/'.$line['name'].'/'.$iField;

	    						// PATCH JIRA 710
	    						if ($field['type'] == 'dropdown' || $field['type'] == 'radio') {
	    							if (isset($field['choicesRadios'])) {
	    								$this->aPerso[$field['name']]['choicesRadios'] = $field['choicesRadios']['choicesRadios']; 
	    							}	
	    						}
	    						// FIN PATCH JIRA 710	    						
	    						
	    						// renseigner pour le js
	    						if (in_array($this->aPerso[$field['name']]['type'], $this->aTypeDefaultValue)) {
	    							$this->aPerso[$field['name']]['default_value'] = $field['default_value']; 
	    						}
	    						
	    						$iField++;
	    						
	    						
	    						
	    					}
	    					$iline++;
	    					$iGlobLine++;
	    				}
	    				$iline = 0;
	    				$iquestion++;
	    				$iGlobQuestion++;
	    			}
	    			$iquestion=0;
	    			$ifielSet++;
	    			$iGlobFieldSet++;
    			}
    			$iGlobalPage++;
    			$ifielSet=0;
    		}
    		
    		unset($aPage);
    	}
    	
    }
    
/**
 * checkForDelete : Vérifie si un champs a été supprimé
 */        
    protected function checkForDelete()
    {
    	if(!$this->oXMLOriginal->aField)
    		throw new Exception('checkForDelete : la propriété aField est vide');

    	if(!$this->aPerso)
    		throw new Exception('checkForDelete : la propriété aPerso est vide');
    		
	   
    	foreach ($this->oXMLOriginal->aField as $k=>$kval)
		{
			// cas particulier: il faut supprimer les champs html si vides
			if (substr($k, 0, 10) == 'html_HTML_' && isset($this->aPerso[$k]) && $this->aPerso[$k]['titre'] == '') {
				unset($this->aPerso[$k]);
			}
    		
			if(!array_key_exists($k,$this->aPerso) )
			{
				$CodeQuestionGen=false;
				
				if($this->oXMLOriginal->aField[$k]['question']!=$this->oXMLOriginal->oXMLGeneric->aField[$k]['question'])	
					$CodeQuestionGen=$this->oXMLOriginal->oXMLGeneric->aField[$k]['question']; 	
			
				$this->oXMLOriginal->DeleteField($k,$CodeQuestionGen);
				
				/** affichage du bouton de validation de l'etape **/
				$this->oXMLOriginal->displayNextStepIfNecessary($this->oXMLOriginal->aField[$k], null, true);
								
				$oLog = new LogXML();
				$oLog->setLogRemoveComponent($this->oXMLOriginal->aField[$k]['itkg_code'],$this->oXMLOriginal->aField[$k]['field']['label']['value']);
				$this->collLog[] = $oLog;
				
				
			}
		}
		
		
    }
    
/**
 * checkForAdd : Vérifie si un champs a été ajouté
 */     
    protected function checkForAdd()
    {
  
    	if(!$this->oXMLOriginal->aField)
    		throw new Exception('checkForAdd : la propriété aField est vide');
    	if(!$this->aPerso)
    		throw new Exception('checkForAdd : la propriété aPerso est vide');
    	
    	$bAdd=false;    	
    	
		foreach (array_keys($this->aPerso) as $kPerso)
		{
			if(!array_key_exists($kPerso,$this->oXMLOriginal->aField))
			{
				$bAdd=true;
				$this->oXMLOriginal->addNode($kPerso,$this->aPerso[$kPerso]['line']);
					
				$oLog = new LogXML();
				$oLog->setLogAddComponent($this->aPerso[$kPerso]['itkg_code'],$this->aPerso[$kPerso]['titre']);
				$this->collLog[] = $oLog;
				
			}
		}
		
		if($bAdd)
		{
			$xml=$this->oXMLOriginal->dom->saveXML();
			
			$this->oXMLOriginal = new XMLHandle($xml,'xml');
			$this->oXMLOriginal->loadGenericXML();
			$this->oXMLOriginal->Parser_read();
		}
    }
    

    /**
     * checkForClearLineStructure : Vérifie si des lignes sont vides et les supprime si c le cas
     */
    protected function checkForClearLineStructure()
    {    	 
    	$this->oXMLOriginal->clearEmptyLineStructure();
    }
    


    /**
     * checkForClearTmpStructure : Vérifie si des structure temporaire vide existe et les supprimes
     */
    protected function checkForClearTmpStructure()
    {
    	 
    	$this->oXMLOriginal->clearTmpStructure();
    
    }
    
    
/**
* checkForChangeEtape : Vérifie si une étape a été déplacée ou renomée
*/
    protected function checkForChangeEtape()
    {
    	
    	if(!$this->aPages)
    		throw new Exception('checkForChangeEtape : la propriété aPages est vide');
   
    	if(!$this->oXMLOriginal->aPageStructure)
    		throw new Exception('checkForChangeEtape : la propriété aPageStructure est vide');
    	
    
    	$bmoved = false;
    	    	
    	
		foreach ($this->aPages as $kpage => $page)
		{
			/* vérifie si le titre a changé*/
			if($page['title']!=$this->oXMLOriginal->aPageStructure[$page['itkg_code']]['title'])
			{
				$this->oXMLOriginal->editTitleEtape($page['itkg_code'],$page['title']);
												
				$oLog = new LogXML();
				$oLog->setLogEditStep($page['itkg_code'], $this->oXMLOriginal->aPageStructure[$page['itkg_code']]['title'], $page['title']);
				$this->collLog[] = $oLog;
				
				
			}
			
			/* vérifie si la position a changé*/
			if($page['position']!=$this->oXMLOriginal->aPageStructure[$page['itkg_code']]['position'] && $bmoved===false)
			{			
								
				$this->oXMLOriginal->moveEtape($page['itkg_code'],$this->aPages);
				$bmoved = true;    						
				
				//LOG modif
				foreach ($this->oXMLOriginal->aPageStructure as $row)
				{
					$tempOri[]=$row['title'];
				}
				
				foreach ($this->aPages as $row)
				{
					$tempPerso[]=$row['title'];
				}
													
				$oLog = new LogXML();
				$oLog->setLogMoveStep( $tempOri, $tempPerso);
				$this->collLog[] = $oLog;
				
			}
		}  		
		
    }
  	  	
    protected function createNewQuestion($questionData, $isEmpty, $questions) {
    	if (! $isEmpty) {
    		$this->oXMLOriginal->createNewQuestion($questionData, $questions);
    	}
    }
    
    protected function deleteEmptyQuestions() {
    	$this->oXMLOriginal->deleteEmptyQuestions();
    }
    
/**
* checkForMoveStructure : Vérifie si une structure FieldSet / question / line a été déplacée
*/   
    protected function checkForMoveStructure()
    {
  	  	 	   
    	if(!$this->aGlobalPersoStructure)
    		throw new Exception('checkForMoveStructure : la propriété aGlobalPersoStructure est vide');    	
    		
    	if(!$this->oXMLOriginal->aGlobalStructure)
    		throw new Exception('checkForMoveStructure : la propriété aGlobalStructure est vide');	
    	 	
    		
    	$FieldSet_moved = array();
    	$Question_moved = array();
    	$Line_moved = array();
    	
    	$bmove=false;
    	 	 
		foreach ($this->aGlobalPersoStructure as $itkg_code=>$aStruct)
		{	 
			if(($aStruct['fullpath'] != $this->oXMLOriginal->aGlobalStructure[$itkg_code]['fullpath']) && !is_null($this->oXMLOriginal->aGlobalStructure[$itkg_code]['fullpath']))
			{	
				
			
				
				
				switch ($aStruct['niveau'])
				{
					case 'fieldSet' :
						if(!in_array($aStruct['page'],$FieldSet_moved))
						{
							$this->oXMLOriginal->moveFieldSet($itkg_code,$this->aPersoStructure[$this->aPageStructure[$aStruct['page']]['position']]['fieldSet']);
							$bmove=true;
						}
						$FieldSet_moved[]= $aStruct['page'];
					break;
					
					case 'question' :
						if(!in_array($aStruct['fieldSet'],$Question_moved))
						{
							$this->oXMLOriginal->moveQuestion($aStruct['fieldSet'],$this->aPersoStructure[$this->aPageStructure[$aStruct['page']]['position']]['fieldSet'][$this->aGlobalPersoStructure[$aStruct['fieldSet']]['position']]['question']);
							$bmove=true;
						}
						$Question_moved[]=$aStruct['fieldSet'];
					break;
					
					case 'line' :
						 	   						
						if(!in_array($aStruct['question'],$Line_moved))
						{	
							$this->oXMLOriginal->moveLine($aStruct['question'],$this->aPersoStructure[$this->aPageStructure[$aStruct['page']]['position']]['fieldSet'][$this->aGlobalPersoStructure[$aStruct['fieldSet']]['position']]['question'][$this->aGlobalPersoStructure[$aStruct['question']]['position']]['line'],$this->oXMLOriginal->aGlobalStructure[$itkg_code]['question'],$aStruct['line']);
							$bmove=true;
						}
						
						$Line_moved[]=$aStruct['question'];
					break;	 
				}
				
				
			}
			
		
    	}
    	
    	if($bmove)
    	{
    		
    		foreach($this->oXMLOriginal->aField as $k=>$row)
    		{
    			   		
    			$html = 0;
    			if($row['type']=='html')
    			{
    				$html = ((int)substr($row['itkg_code'], 5));
    				$label= $row['html']['value'];
    			}else{
    				$label= $row['field']['label']['value'];
    			}
    			
    			if($label){
    				$label=trim(strip_tags(trim($label)));
    			}else{
    				$label = FunctionsUtils::translateEditor('BOFORMS_LABEL_'.$k , $this->oXMLOriginal->instance['culture_id']);
    			}
    		
    		
    			if($row['type']!='hidden' && $row['type']!='button' && $html==0)
    				$orderComponent_old[] = array('code' => $k, 'label' => $label);
    		}
    		
    		foreach($this->aPerso as $k=>$row)
    		{
    			
    			$html = 0;
    			if($row['type']=='html')
    			{
    				$html = ((int)substr($row['itkg_code'], 5));
    				$label= $row['titre'];
    			}else{
    				$label= $row['titre'];
    			}
    			
    			if($label){
    				$label=trim(strip_tags(trim($label)));
    			}else{
    				$label = FunctionsUtils::translateEditor('BOFORMS_LABEL_'.$k , $this->oXMLOriginal->instance['culture_id']);
    			}
    			
    			if($row['type']!='hidden' && $row['type']!='button'  && $html==0)
    				$orderComponent_new[] = array('code' => $k, 'label' => $label);
    		}
    		
    		
    		$oLog = new LogXML();
    		$oLog->setLogMoveComponent($orderComponent_old, $orderComponent_new);
    		
    		$this->collLog[] = $oLog;
    		    		    		
    	}	
    	
    
    }
    
    
/**
* checkForMoveField : Vérifie si un champ a été déplacé
*/   
    protected function checkForMoveField()
    {
    	if(!$this->aPerso)
    		throw new Exception('checkForMoveField : la propriété aPerso est vide');
    		
    	if(!$this->oXMLOriginal->aField)
    		throw new Exception('checkForMoveField : la propriété aField est vide');
    	    	
    	$field_moved = array();
    	    	
    	foreach ($this->aPerso as $kfield=>$currField)
    	{
    		if(($currField['fullpath'] != $this->oXMLOriginal->aField[$kfield]['fullpath']) && isset($this->oXMLOriginal->aField[$kfield]))
			{
				if(!in_array($currField['line'],$field_moved))
				{	
					
					if($currField['page'] != $this->oXMLOriginal->aField[$kfield]['page'])
					{//change étape du field
					
					
						$this->oXMLOriginal->moveFieldStep($currField,$this->aPersoStructure[$this->aPageStructure[$currField['page']]['position']]['fieldSet'][$this->aGlobalPersoStructure[$currField['fieldSet']]['position']]['question'][$this->aGlobalPersoStructure[$currField['question']]['position']]['line'][$this->aGlobalPersoStructure[$currField['line']]['position']]['field'],$kfield,$this->oXMLOriginal->aField[$kfield]['page']);
					
						$oLog = new LogXML();
						$oLog->setLogMoveFieldStep($this->oXMLOriginal->aField[$kfield]['itkg_code'],$this->aPageStructure[$this->oXMLOriginal->aField[$kfield]['page']]['title'],$this->aPageStructure[$currField['page']]['title'],$this->oXMLOriginal->aField[$kfield]['field']['label']['value']);
						
						$this->collLog[] = $oLog;
					
					}else{
						//change ordre dans la line
						$this->oXMLOriginal->moveField($currField['line'],$this->aPersoStructure[$this->aPageStructure[$currField['page']]['position']]['fieldSet'][$this->aGlobalPersoStructure[$currField['fieldSet']]['position']]['question'][$this->aGlobalPersoStructure[$currField['question']]['position']]['line'][$this->aGlobalPersoStructure[$currField['line']]['position']]['field'], false, $currField['type']);
					}
					
				}
				$field_moved[]=$currField['line'];
			}
    	}
    	
    }
    
    protected function updateTagGtm($codeInstance) {
	// gets the generic gtm tags
	$elementGeneric = $this->oXMLOriginal->oXMLGeneric->domXpath->query("//html[@itkg_gtm_data='1']")->item(0);

	// gets the personnalised gtm tags
	$elementPerso = $this->oXMLOriginal->domXpath->query("//html[@itkg_gtm_data='1']")->item(0);

	if ($elementGeneric != null && $elementPerso != null) {
		// get form name
    		$oConnection = Pelican_Db::getInstance();
		$aBind[':FORM_INCE'] = $oConnection->strToBind($codeInstance);
    		$sSql = "select FORM_NAME from #pref#_boforms_formulaire where FORM_INCE=:FORM_INCE ";
		$formName = $oConnection->queryItem($sSql, $aBind);

		// update gtm tag using generic one
		$nodeValueGeneric = $elementGeneric->nodeValue;
		if (! empty($formName)) {
			$nodeValueGeneric = preg_replace("/pageName\":\".*?\"/", "pageName\":\"$formName\"", $nodeValueGeneric);
		}
		$nodeValueGeneric = str_replace("Generic ", "", $nodeValueGeneric);

		// ici un cdata et parfois des noeud de type domtext
		$domnodelist = $elementPerso->childNodes;
		foreach ($domnodelist as $item) {
			// on ne modifie que le cdata
			if (get_class($item) == 'DOMCdataSection') {
				$item->replaceData(0,strlen($item->nodeValue), $nodeValueGeneric);
			}			
		}
	}
    }
    
    // jira 800
    // lors de la publication, écraser toute les balises GTM du personnalisé par celle du générique,
    // et si toutefois un label GTM du personnalisé est différent du générique, on garde celui du personnalisé. 
    protected function updateGtmTagsFromGenerique() {
    	// gtm tags unders page
    	$this->updateGtmPageTagsFromGenerique();
    		
    	// gtm tags under connectorRequestParameters
    	$this->updateGtmConnectorRequestParametersTagsFromGenerique();
    }
    
    protected function updateGtmConnectorRequestParametersTagsFromGenerique() {
    	$request_parameter_node_list = $this->oXMLOriginal->oXMLGeneric->domXpath->query('//connector/requestParameter');

    	$tbl_datas = array();
    	
    	// parcours des request parameters
    	foreach ($request_parameter_node_list as $request_parameter_item) {
			$itkg_code_parent = $request_parameter_item->getAttribute('itkg_code');
			
			$gtm_mapping_nodes = $request_parameter_item->getElementsByTagName('mapping');
			
			$type_connector = '';
			foreach ($gtm_mapping_nodes as $item_mapping) {
				if ($item_mapping->getAttribute('code') == 'TYPE') {
					$type_connector = $item_mapping->getAttribute('key');
					break;
				}	
			}
    		
			// gets this connector in the personnalised
			if ($type_connector != '') {
				$gtm_standard_mapping_nodes = $this->oXMLOriginal->domXpath->query("//connector/requestParameter/mapping");
				foreach ($gtm_standard_mapping_nodes as $item_mapping_node) {
					if ($item_mapping_node->hasAttribute('code') && $item_mapping_node->hasAttribute('key') &&
						$item_mapping_node->getAttribute('code') == 'TYPE' && $item_mapping_node->getAttribute('key') == $type_connector) {
							
						// le noeud request parameter	
						$parent_perso = $item_mapping_node->parentNode;
						
						$tags = $parent_perso->getElementsByTagName('tag');
							
						$tbl_tags = array();
						foreach ($tags as $item_tag) {
							$tbl_tag = array();
							
							if ($item_tag->hasChildNodes()) {
								$children = $item_tag->childNodes;
    							foreach ($children as $child) { 
									$tbl_tag[$child->tagName] = $child->nodeValue;
    							}
								$tbl_tags[] = $tbl_tag;
							}							
						}
						
						$tbl_datas[] = array(
								'parent_generic' => $request_parameter_item,
								'parent_perso' => $parent_perso,
								'tags' => $tbl_tags
						);
						break;
					}	
				}
			}
		}
		
		// parcours du tableau pour mettre à jour les tags gtm
		for ($i = 0; $i < count($tbl_datas); $i++) {
			//echo $tbl_datas[$i]['parent_generic']->tagName . " et " . $tbl_datas[$i]['parent_perso']->tagName . '<br/>'; 
			
			// suppression du tag gtm du standard
			$tbl_datas[$i]['parent_perso']->removeChild($tbl_datas[$i]['parent_perso']->getElementsByTagName('gtm')->item(0));
			
			// importation du tag gtm du generic dans le standard
			$gtm_node = $tbl_datas[$i]['parent_generic']->getElementsByTagName('gtm')->item(0);
			$gtm_node_imported = $this->oXMLOriginal->dom->importNode($gtm_node, true);
			$tbl_datas[$i]['parent_perso']->psaInsertChild($gtm_node_imported);
			
			// TODO remettre l'ancien label pour les tags repris depuis le générique
			$tag_nodes = $tbl_datas[$i]['parent_perso']->getElementsByTagName('tag');
			foreach ($tag_nodes as $item_tag) {
				if ($item_tag->hasChildNodes()) {
					$children = $item_tag->childNodes;
    				$tbl_tag = array();
							
    				$child_label = null;
					foreach ($children as $child) { 
    					$tbl_tag[$child->tagName] = $child->nodeValue;
    					if ($child->tagName == 'label') {
    						$child_label = $child;
    					}	
    				}
    				    				
    				if ($child_label != null) {
	    				for ($z = 0; $z < count($tbl_datas[$i]['tags']); $z++) {
	    					if (isset($tbl_datas[$i]['tags'][$z]['name']) && isset($tbl_tag['name']) && $tbl_datas[$i]['tags'][$z]['name'] == $tbl_tag['name'] &&
	    						isset($tbl_datas[$i]['tags'][$z]['category']) && isset($tbl_tag['category']) && $tbl_datas[$i]['tags'][$z]['category'] == $tbl_tag['category'] &&
	    						isset($tbl_datas[$i]['tags'][$z]['action']) && isset($tbl_tag['action']) && $tbl_datas[$i]['tags'][$z]['action'] == $tbl_tag['action'] ) {
		    						if (isset($tbl_datas[$i]['tags'][$z]['label'])) {
		    							$child_label->nodeValue =  $tbl_datas[$i]['tags'][$z]['label'];     							
		    						}	
	    					}
	    				}
    				}
				}
			}
			
		}
    }
    
    protected function updateGtmPageTagsFromGenerique() {    	
    	$gtms_node_list = $this->oXMLOriginal->oXMLGeneric->domXpath->query('//page/gtm');
		
    	foreach ($gtms_node_list as $item_gtm) {
			$itkg_code_parent = $item_gtm->parentNode->getAttribute('itkg_code');
			$itkg_code = $item_gtm->getAttribute('itkg_code');
			$tags_node_list = $item_gtm->getElementsByTagName('tag');
	
			$gtm_standard = $this->oXMLOriginal->domXpath->query("//page[@itkg_code='" . $itkg_code_parent . "']/gtm[@itkg_code='" . $itkg_code . "']")->item(0);
			
			if ($gtm_standard) {
				// now we check for gtm tags with labels
				$tab_tags_label_values = array();
				if ($tags_node_list->length > 0) {
					foreach ($tags_node_list as $item_tag) {
						$element_label = $item_tag->getElementsByTagName('label')->item(0);
						if ($element_label) {
							$tab_tags_label_values[$item_tag->getAttribute('itkg_code')] = $element_label->nodeValue;
						}
					}
				} 
				
				// we replace gtm tag with generic ones value
				$parent_node = $gtm_standard->parentNode;
				$gtm_standard->parentNode->removeChild($gtm_standard);			

				$item_gtm_imported = $this->oXMLOriginal->dom->importNode($item_gtm, true);
				$parent_node->psaInsertChild($item_gtm_imported);				
								
				if (count($tab_tags_label_values) > 0) {
					// supprimer les labels si besoin	
				}
			}	
		}
		
    }
        
    protected function checkForMissingListeners() {
		if(!$this->oXMLOriginal->oXMLGeneric->aField) {
			throw new Exception('checkForMissingHiddenFields : la propriété aField est vide');
		}
			
		// pour chaque champ de perso
		foreach ($this->oXMLOriginal->aField as $key => $values) {
			if (isset($this->oXMLOriginal->oXMLGeneric->aField[$key])) {
				$code_itkg = $values['itkg_code'];
				
				$element_perso = $this->oXMLOriginal->domXpath->query("//field[@itkg_code='$code_itkg']")->item(0);
				
				// suppression des listeners du perso
				$listenerElement = $this->oXMLOriginal->domXpath->query("//field[@itkg_code='$code_itkg']/listener")->item(0);
				while ($listenerElement  != null) {
					$listenerParent = $listenerElement->parentNode;
					$listenerParent -> removeChild($listenerElement);
					
					$listenerElement = $this->oXMLOriginal->domXpath->query("//field[@itkg_code='$code_itkg']/listener")->item(0);
				}
								
				// recherche des listeners associes dans le generique et ajout au perso
				$listeners_generique = $this->oXMLOriginal->oXMLGeneric->domXpath->query("//field[@itkg_code='$code_itkg']/listener");
				foreach ($listeners_generique as $item) {
					$listener_generique = $this->oXMLOriginal->dom->importNode($item, true);
					$element_perso -> psaInsertChild($listener_generique);
				}			
	    	}		
		}	    
    }		
    
    protected function checkForMissingHiddenFields() {
		// checks all hidden fields in the generic
		if(!$this->oXMLOriginal->oXMLGeneric->aField)
			throw new Exception('checkForMissingHiddenFields : la propriété aField est vide');
	    
		// hidden fields to remove
		foreach ($this->oXMLOriginal->aField as $key => $values) {
			if (!isset($this->oXMLOriginal->oXMLGeneric->aField[$key])) {
				$code_itkg = $values['itkg_code'];
				$element = $this->oXMLOriginal->domXpath->query("//field[@itkg_code='$code_itkg']")->item(0);
				
				if ($element != null) {
					$id_hidden_field = $element->getAttribute('id');
					
					// removes the hidden field
					$parent = $element->parentNode;
					$parent->removeChild($element);
					
					// removes associated listener if exists
					$listenerElement = $this->oXMLOriginal->domXpath->query("//listener[@fieldID='$id_hidden_field']")->item(0);
					if ($listenerElement != null) {
						$listenerParent = $listenerElement->parentNode;
						$listenerParent -> removeChild($listenerElement);
					}
					
				}
			}
		}
			
		// hidden fields to add (in the generic but not in the personalised)
		foreach ($this->oXMLOriginal->oXMLGeneric->aField as $key => $values) {
		  if ($values['type'] == 'hidden') {
		    	$code_itkg = $values['itkg_code'];
		    	$page = $values['page'];
	    		$num_page = $values['num_page'];
	    		$fieldset = $values['fieldSet'];
			$question = $values['question'];
			$line = $values['line'];
	
			// if generic field not found in personalised
	    		if (!isset($this->oXMLOriginal->aField[$key])) {
	    			$element = $this->oXMLOriginal->domXpath->query("//page[@itkg_code='$page']/fieldSet[@itkg_code='$fieldset']/question[@itkg_code='$question']/line[@itkg_code='$line']")->item(0);
					if ($element == null) {
						$element = $this->oXMLOriginal->domXpath->query("//page[@itkg_code='$page']/fieldSet[@itkg_code='$fieldset']/question[@itkg_code='$question']/line")->item(0);
					}
					if ($element == null) {
						$element = $this->oXMLOriginal->domXpath->query("//page[@itkg_code='$page']/fieldSet[@itkg_code='$fieldset']/question/line")->item(0);
					}
					if ($element == null) {
						$element = $this->oXMLOriginal->domXpath->query("//page[@itkg_code='$page']/fieldSet/question/line")->item(0);
					}
		    		
					if ($element != null) {
						// for each element we create a line container
		
						$nodeLine = $this->oXMLOriginal->dom->createElement("line");
						$nodeOrder = $this->oXMLOriginal->dom->createElement("order");
						$nodeOrder->nodeValue = 1;
						$nodeLine->psaInsertChild($nodeOrder);
						$hidden_field = $this->oXMLOriginal->oXMLGeneric->domXpath->query("//field[@itkg_code='$code_itkg']")->item(0);
					        $hidden_child = $this->oXMLOriginal->dom->importNode($hidden_field, true);
						$nodeLine->psaInsertChild($hidden_child);
						$element->parentNode->psaInsertChild($nodeLine);
					}
	    			
	    		}  		
		    }	
		}
	//print_r($this->oXMLOriginal->dom->saveXML());die('===============');
    }
    
	/**
	* checkForFormCommentary : Vérifie si le commentaire du formulaire a été modifié
	*/  
    protected function checkForFormCommentary($commentary_modified, $commentary_visible) {
    	$old_comment = $this->oXMLOriginal->form['commentary'];
    	$this->oXMLOriginal->updateFormCommentary($old_comment, $commentary_modified, $commentary_visible);
    }
    
    
/**
* checkStepConfiguration : Enregistre la configuration pour une étape
*/  
    protected function checkStepConfiguration() {
    	for ($i = 0; $i < count($this->oXMLOriginal->aPages); $i++) {
    		$page_itkg = $this->oXMLOriginal->aPages[$i]['itkg_code'];

    		$this->oXMLOriginal->updateConfigurationButtonLabel($page_itkg, 'next', $this->aStepConfiguration[$i]['next_label']);
    		$this->oXMLOriginal->updateConfigurationButtonLabel($page_itkg, 'previous', $this->aStepConfiguration[$i]['previous_label']);
    	}   	
    }

    protected function checkStepTagsUnderQuestion() {
    	for ($i = 0; $i < count($this->oXMLOriginal->aPages); $i++) {
    		$page_itkg = $this->oXMLOriginal->aPages[$i]['itkg_code'];
    		$this->oXMLOriginal->updateTagsUnderQuestionLabel($page_itkg, $this->aTagsUnderQuestion[$i]);
    	}
    }
    
    // PATCH JIRA 710
    protected function checkForReferentialValues() {
    	foreach ($this->aPerso as $key=>$aField)
		{
			if (isset($aField['choicesRadios'])) {
				$items = array();
				
				for ($i = 0; $i < count($aField['choicesRadios']); $i++) {
					for ($j = 0; $j < count($aField['choicesRadios'][$i]); $j++) {
						$selected_values = $aField['choicesRadios'][$i][$j]['selectedValue'];
						if (strlen($selected_values) > 0) {
							$items[] = array(
								'id' => $aField['choicesRadios'][$i][$j]['id'],
								'label' => $aField['choicesRadios'][$i][$j]['choiceLabel']
							);						
						}
					}
				}
				
				// updates the referential items for this field 
				if (count($items) > 0) {
					$this->oXMLOriginal->updateReferentialValues($aField['itkg_code'], $items, $aField['type']);	
				}
			}							
		}
    }
    
/**
* checkForEditField : Vérifie si un champ a été édité
*/  
    protected function checkForEditField(){
       	
    	if(!$this->aPerso)
    		throw new Exception('checkForMoveField : la propriété aPerso est vide');
    		
    	if(!$this->oXMLOriginal->aField)
    		throw new Exception('checkForMoveField : la propriété aField est vide');
    		
    		
    	
    	/*$aTypeField = array('datepicker', 'textbox','checkbox','radio','dropDownList','dropdown','password','textarea','file','richTextEditor','captcha');
		$aTypeFieldExclude = array('button','hidden');*/
    	
		$bLP=false;//site landing page
		
		if(FunctionsUtils::isLandingPageSite((int)$this->oXMLOriginal->instance['site_id']))
		{
			$bLP=true;
		}
		//debug($this->aPerso);die;
		foreach ($this->aPerso as $key=>$aField)
		{
			if(isset($this->oXMLOriginal->aField[$key]) || true)
			{

				$bLog=false;
				
				if ($aField['type'] == 'textbox') {
					// gestion du inputmask pour les textbox
					if (isset($this->oXMLOriginal->aField[$key]['field']['textbox']['inputmask']['value'])) {
						if (empty($aField['inputmask'])) {
							$this->oXMLOriginal->deleteInputMask($aField['itkg_code']);
						} else if ($aField['inputmask'] != $this->oXMLOriginal->aField[$key]['field']['textbox']['inputmask']['value']) {
							$this->oXMLOriginal->updateInputMask($aField['itkg_code'], $aField['inputmask']);
						}
					} else if (! empty($aField['inputmask'])) {
						$this->oXMLOriginal->addInputMask($aField['itkg_code'], $aField['inputmask']);
					}
					
					if ($aField['name'] == 'USR_EMAIL') {
						$email_param_message_itkg = '';
						$email_param_message_value = '';
						for ($iil = 0; $iil < count($this->oXMLOriginal->aField[$key]['field']['listener']); $iil++) {
				    		if (isset($this->oXMLOriginal->aField[$key]['field']['listener'][$iil]['behavior']['attributes']['type']) && $this->oXMLOriginal->aField[$key]['field']['listener'][$iil]['behavior']['attributes']['type'] == 'showMessage') {
				    			$email_param_message_itkg = $this->oXMLOriginal->aField[$key]['field']['listener'][$iil]['behavior']['requestParameter']['parameter']['attributes']['itkg_code'];
				    			$email_param_message_value = $this->oXMLOriginal->aField[$key]['field']['listener'][$iil]['behavior']['requestParameter']['parameter']['value'];
				    		}
				    	}
				    	
				    	if ($email_param_message_itkg != '' && $email_param_message_value != $aField['emailParamMessageValue']) {
				    		$this->oXMLOriginal->updateEmailListenerParamMessage($aField['itkg_code'], $email_param_message_itkg, $aField['emailParamMessageValue']);
				    	}
					}
				}
				
				/*** connector field ***/
				if ($aField['type']=='connector') {
					$itkg_code_connector = '';
					$key_code_connector = '';
					
					for ($ia = 0; $ia < count($this->oXMLOriginal->aField[$key]['connector']['requestParameter']['mapping']); $ia++) {
						if ($this->oXMLOriginal->aField[$key]['connector']['requestParameter']['mapping'][$ia]['attributes']['code'] == 'text') {
							$itkg_code_connector = $this->oXMLOriginal->aField[$key]['connector']['requestParameter']['mapping'][$ia]['attributes']['itkg_code'];
							$key_code_connector = $this->oXMLOriginal->aField[$key]['connector']['requestParameter']['mapping'][$ia]['attributes']['key'];
						}	
					}
					
					if ($aField['buttonName'] == '') {
						if ($itkg_code_connector != '') {
							$this->oXMLOriginal->deleteButtonNameForConnector($itkg_code_connector, $aField['itkg_code']);	
						} else {
							// ok
						}
					} else {
						if ($itkg_code_connector != '') {
							if ($key_code_connector != $aField['buttonName']) {
								$this->oXMLOriginal->updateButtonNameForConnector($itkg_code_connector, $aField['itkg_code'], $aField['buttonName']);
							} else {
								// ok
							}	
						} else {
							$this->oXMLOriginal->addButtonNameForConnector($aField['itkg_code'], $aField['buttonName']);
						}
					}
					
					// gestion du label pour le tag gtm du connector
					if ($aField['labelTagGtm'] != $this->oXMLOriginal->aField[$key]['connector']['requestParameter']['gtm']['tag']['label']['value']) {
						$this->oXMLOriginal->updateTagGtmLabelForConnector($this->oXMLOriginal->aField[$key]['connector']['requestParameter']['gtm']['tag']['label']['attributes']['itkg_code'], $aField['labelTagGtm']);
					}
				}
				
				/*** label ***/


				if($aField['type']!='html' && $aField['type']!='connector' && $aField['type']!='toggle')
				{
					$bDeleteLabel = false;	
					
					if($aField['titre'] && !isset($this->oXMLOriginal->aField[$key]['field']['label']))
					{
						$this->oXMLOriginal->createTextNode('label',$this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'],$aField['titre']);
						$bLog=true;
					}elseif (empty($aField['titre']) && isset($this->oXMLOriginal->aField[$key]['field']['label']))
					{
						$bDeleteLabel = true;
						$this->oXMLOriginal->deleteNode($this->oXMLOriginal->aField[$key]['field']['label']['attributes']['itkg_code'],'label');
						//$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['label']['attributes']['itkg_code'],'label',$aField['titre']);
						$bLog=true;
					}elseif($aField['titre']!=$this->oXMLOriginal->aField[$key]['field']['label']['value'])
					{
						$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['label']['attributes']['itkg_code'],'label',$aField['titre']);
						$bLog=true;
					}
					
					if($bLog)
					{
						if(!empty($this->oXMLOriginal->aField[$key]['field']['label']['value']) || !empty($aField['titre']))
						{
							$oLog = new LogXML();
							$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'label', $this->oXMLOriginal->aField[$key]['field']['label']['value'], $aField['titre'],$this->oXMLOriginal->aField[$key]['field']['label']['value']);						
							$this->collLog[] = $oLog;
						}
					}	

					
					//align
					
					if(!$bLP && !$bDeleteLabel && $aField['titre'] && isset($this->oXMLOriginal->aField[$key]['field']['label']) && $aField['align']!=$this->oXMLOriginal->aField[$key]['field']['label']['attributes']['align'])
					{
						$this->oXMLOriginal->editAttribute($this->oXMLOriginal->aField[$key]['field']['label']['attributes']['itkg_code'],'label',$aField['align'], 'align');
						
						if(!(empty($this->oXMLOriginal->aField[$key]['field']['label']['attributes']['align']) && $aField['align']=='left'))
						{
							$oLog = new LogXML();
							$oLog->setLogAttributeComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'label', 'align' ,$this->oXMLOriginal->aField[$key]['field']['label']['attributes']['align'], $aField['align'],$this->oXMLOriginal->aField[$key]['field']['label']['value']);
							$this->collLog[] = $oLog;
							
						}
					}
				}	
				/******/

				/*** toggle ***/
				if($aField['type'] == 'toggle'){
					if($aField['titre'] != $this->oXMLOriginal->aField[$key]['toggle']['title']['value'])
					{
						$this->oXMLOriginal->editToggle($this->oXMLOriginal->aField[$key]['itkg_code'],$aField['titre'],"title");

						$oLog = new LogXML();
						$oLog->setLogEditHTML($this->oXMLOriginal->aField[$key]['itkg_code'], 'toggle',$this->oXMLOriginal->aField[$key]['toggle']['title']['value'], $aField['titre']);
						$this->collLog[] = $oLog;

					}

					if($aField['content'] != $this->oXMLOriginal->aField[$key]['toggle']['content']['value'])
					{
						$this->oXMLOriginal->editToggle($this->oXMLOriginal->aField[$key]['itkg_code'],$aField['content'],"content");

						$oLog = new LogXML();
						$oLog->setLogEditHTML($this->oXMLOriginal->aField[$key]['itkg_code'], 'toggle',$this->oXMLOriginal->aField[$key]['toggle']['title']['value'], $aField['titre']);
						$this->collLog[] = $oLog;

					}
				}

				/**** ****/

				/*** html ***/	
					if($aField['type']=='html' && ($aField['titre'] != $this->oXMLOriginal->aField[$key]['html']['value']))
					{
						$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['html']['attributes']['itkg_code'],'html',$aField['titre']);
							
						$oLog = new LogXML();
						$oLog->setLogEditHTML($this->oXMLOriginal->aField[$key]['html']['attributes']['itkg_code'], 'html', $this->oXMLOriginal->aField[$key]['html']['value'], $aField['titre']);
						$this->collLog[] = $oLog;
						
					}
					
				/**** ****/	
					
				/*** help ***/
					$bLog=false;
					
					if ($aField['type'] == 'connector') {
						$key_type = 'connector';
					} else {
						$key_type = 'field';
					}
					
						if($aField['instructions'] && !isset($this->oXMLOriginal->aField[$key][$key_type]['help']))
						{
							$this->oXMLOriginal->createTextNode('help',$this->oXMLOriginal->aField[$key][$key_type]['attributes']['itkg_code'],$aField['instructions'], $key_type);					
							$bLog=true;
						}elseif (empty($aField['instructions']) && isset($this->oXMLOriginal->aField[$key][$key_type]['help']))
						{
							$this->oXMLOriginal->deleteNode($this->oXMLOriginal->aField[$key][$key_type]['help']['attributes']['itkg_code'],'help');
							$bLog=true;
						}elseif($aField['instructions']!=$this->oXMLOriginal->aField[$key][$key_type]['help']['value'])
						{
							$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key][$key_type]['help']['attributes']['itkg_code'],'help',$aField['instructions']);
							$bLog=true;
						}
					
						if($bLog)
						{
							$oLog = new LogXML();
							$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key][$key_type]['attributes']['itkg_code'], 'help', $this->oXMLOriginal->aField[$key][$key_type]['help']['value'], $aField['instructions'],$this->oXMLOriginal->aField[$key][$key_type]['label']['value']);
							$this->collLog[] = $oLog;
							
						}
					
						
				/******/
				
				/*** valeur par defaut ***/		
					if(in_array($aField['type'],$this->aTypeDefaultValue))
					{
						$bLog=false;
						if($aField['default_value'] && !isset($this->oXMLOriginal->aField[$key]['field'][$aField['type']]['defaultValue']))
						{				
							$bLog=true;
							$this->oXMLOriginal->createDefaultValueNode($this->oXMLOriginal->aField[$key]['field'][$aField['type']]['attributes']['itkg_code'],$aField['type'],$aField['default_value']);
																							
						}elseif (empty($aField['default_value']) && isset($this->oXMLOriginal->aField[$key]['field'][$aField['type']]['defaultValue']))
						{
							$bLog=true;
							$this->oXMLOriginal->deleteNode($this->oXMLOriginal->aField[$key]['field'][$aField['type']]['defaultValue']['attributes']['itkg_code'],'defaultValue');	
						}elseif($aField['default_value']!=$this->oXMLOriginal->aField[$key]['field'][$aField['type']]['defaultValue']['value'])
						{
							$bLog=true;
							$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field'][$aField['type']]['defaultValue']['attributes']['itkg_code'],'defaultValue',$aField['default_value']);
						}
						
						if($bLog)
						{
							$oLog = new LogXML();
							$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'default_value', $this->oXMLOriginal->aField[$key]['field'][$aField['type']]['defaultValue']['value'], $aField['default_value'],$this->oXMLOriginal->aField[$key][$key_type]['label']['value']);
							$this->collLog[] = $oLog;
						}
						
						
					}
				/******/
					
				/* valeurs par defaut pour les choices */
				$this->updateSelectedChoices($aField);
				
				/*** rule ***/
					if($aField['regexp']!=$this->oXMLOriginal->aField[$key]['field']['rule']['pattern']['value'])
					{
						$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['rule']['pattern']['attributes']['itkg_code'],'pattern',$aField['regexp']);
					
						$oLog = new LogXML();
						$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'regexp', $this->oXMLOriginal->aField[$key]['field']['rule']['pattern']['value'], $aField['regexp'],$this->oXMLOriginal->aField[$key]['field']['label']['value']);
						$this->collLog[] = $oLog;
						
					}
													
					//rule message error
					//uniquement pour les Landing page jira BOFORMS-210
					if($aField['regexp_msg']!=$this->oXMLOriginal->aField[$key]['field']['rule']['errorMessage']['value'])
					{
						$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['rule']['errorMessage']['attributes']['itkg_code'],'errorMessage',$aField['regexp_msg']);

						$oLog = new LogXML();
						$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'regexp_errorMessage', $this->oXMLOriginal->aField[$key]['field']['rule']['errorMessage']['value'], $aField['regexp_msg'],$this->oXMLOriginal->aField[$key]['field']['label']['value']);
						$this->collLog[] = $oLog;
					}
				/******/
							
				/*** required ***/
					if($aField['is_required']!=1 && is_array($this->oXMLOriginal->aField[$key]['field']['required']))
					{
						//si le champs devient non obligatoire, on supprime le noeud <required>			
						$this->oXMLOriginal->deleteNode($this->oXMLOriginal->aField[$key]['field']['required']['attributes']['itkg_code'],'required');	
						
						$oLog = new LogXML();
						$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'is_required', 'required', 'not required',$this->oXMLOriginal->aField[$key]['field']['label']['value']);
						$this->collLog[] = $oLog;
						
						
					}elseif($aField['is_required']==1 && !is_array($this->oXMLOriginal->aField[$key]['field']['required'])){
						
						//si le champs devient obligatoire, on créé le noeud <required>				
						$this->oXMLOriginal->createRequiredNode($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'],$aField['required_msg']);				
					
						$oLog = new LogXML();
						$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'is_required', 'not required', 'required',$this->oXMLOriginal->aField[$key]['field']['label']['value']);
						$this->collLog[] = $oLog;
						
						
					}else{
						//required message error
						//uniquement pour les Landing page jira BOFORMS-210
						if($aField['required_msg']!=$this->oXMLOriginal->aField[$key]['field']['required']['errorMessage']['value'])
						{
							$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['required']['errorMessage']['attributes']['itkg_code'],'errorMessage',$aField['required_msg']);
							
							$oLog = new LogXML();
							$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'required_errorMessage', $this->oXMLOriginal->aField[$key]['field']['required']['errorMessage']['value'], $aField['required_msg'],$this->oXMLOriginal->aField[$key]['field']['label']['value']);
							$this->collLog[] = $oLog;
						}
					}
				/******/
					
				/*** item ***/
					
					if (is_array($aField['choices']) && !empty($aField['choices']) && count($aField['choices']) == 1)  
					{
						//if(sizeof($aField['choices'])!=sizeof($this->oXMLOriginal->aField[$key]['field'][$aField['type']]['referential']['item']))						{
							//$this->oXMLOriginal->itemChange($this->oXMLOriginal->aField[$key]['field'][$aField['type']]['referential']['attributes']['itkg_code'],$aField['choices']);
						//}else{
							foreach ($aField['choices'] as $kchoice=>$choice)
							{
								
								if ($this->oXMLOriginal->aField[$key]['field'][$aField['type']]['referential']['item']['value'] !=  $choice['choice']) {
									$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field'][$aField['type']]['referential']['item']['attributes']['itkg_code'],'item',$choice['choice']);
									
									$oLog = new LogXML();
									$oLog->setLogEditComponent(
										$this->oXMLOriginal->aField[$key]['field'][$aField['type']]['referential']['item']['attributes']['itkg_code'], 
										'optin', 
										$this->oXMLOriginal->aField[$key]['field'][$aField['type']]['referential']['item']['value'] , 
										$choice['choice'],
										$this->oXMLOriginal->aField[$key]['field'][$aField['type']]['referential']['item']['value'] 
									);
									
									/*
									$oLog->setLogEditComponent(
										$this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 
										'label', 
										$this->oXMLOriginal->aField[$key]['field']['label']['value'], 
										$aField['titre'],
										$this->oXMLOriginal->aField[$key]['field']['label']['value']);
									*/
									$this->collLog[] = $oLog;
								}
							}
						//}
					}
					
				/******/
					
				if ($aField['type'] == 'button' && $aField['name'] == 'TECHNICAL_SEND_REQUEST') {
					$this->oXMLOriginal->updatePageErrorLabel($aField['pageErrorLabel'], $bLP);					
				}
					
				// update type for civility, DPR and phone fields (update done in settings tab)	
				if ($aField['type'] == 'dropdown' || $aField['type'] == 'checkbox' || $aField['type'] == 'radio') {
					if (! isset($this->oXMLOriginal->aField[$key]['field'][$aField['type']])) {
						if (isset($this->oXMLOriginal->aField[$key]['field']['dropdown'])) {
							$old_type = 'dropdown';
						} else if (isset($this->oXMLOriginal->aField[$key]['field']['radio'])) {
							$old_type = 'radio';	
						} else if (isset($this->oXMLOriginal->aField[$key]['field']['checkbox'])) {
							$old_type = 'checkbox';
						}	
						$this->oXMLOriginal->replaceNodeType($this->oXMLOriginal->aField[$key]['field'][$old_type]['attributes']['itkg_code'], $old_type, $aField['type']);
					}
				}	
					
				// DatePicker
				if(is_array($aField['datePicker']))
				{	
					// dateStart
					$bLog=false;
					if(isset($this->oXMLOriginal->aField[$key]['field']['datepicker']['dateStart']) && empty($aField['datePicker']['dateStart']))
					{
						$bLog=true;
						$this->oXMLOriginal->deleteNode($this->oXMLOriginal->aField[$key]['field']['datepicker']['dateStart']['attributes']['itkg_code'],'dateStart');
					}elseif($aField['datePicker']['dateStart'] && !isset($this->oXMLOriginal->aField[$key]['field']['datepicker']['dateStart']))
					{
						$this->oXMLOriginal->createPickerNode('dateStart', $this->oXMLOriginal->aField[$key]['field']['datepicker']['attributes']['itkg_code'], $aField['datePicker']['dateStart']);
						$bLog=true;
					} else if($aField['datePicker']['dateStart'] != $this->oXMLOriginal->aField[$key]['field']['datepicker']['dateStart']['value'])
					{
						$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['datepicker']['dateStart']['attributes']['itkg_code'],'dateEnd',$aField['datePicker']['dateStart']);
						$bLog=true;
					}
					
					if($bLog)
					{
						$oLog = new LogXML();
						$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'dateStart',$this->oXMLOriginal->aField[$key]['field']['datepicker']['dateStart']['value'], $aField['datePicker']['dateStart'],$this->oXMLOriginal->aField[$key]['field']['label']['value']);
						$this->collLog[] = $oLog;
						
					}
					
					// dateEnd
					$bLog=false;
					if(isset($this->oXMLOriginal->aField[$key]['field']['datepicker']['dateEnd']) && empty($aField['datePicker']['dateEnd']))
					{
						$this->oXMLOriginal->deleteNode($this->oXMLOriginal->aField[$key]['field']['datepicker']['dateEnd']['attributes']['itkg_code'],'dateEnd');
						$bLog=true;
					}elseif($aField['datePicker']['dateEnd'] && !isset($this->oXMLOriginal->aField[$key]['field']['datepicker']['dateEnd']))
					{
						$this->oXMLOriginal->createPickerNode('dateEnd', $this->oXMLOriginal->aField[$key]['field']['datepicker']['attributes']['itkg_code'], $aField['datePicker']['dateEnd']);
						$bLog=true;
					} else if($aField['datePicker']['dateEnd'] != $this->oXMLOriginal->aField[$key]['field']['datepicker']['dateEnd']['value'])
					{
						$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['datepicker']['dateEnd']['attributes']['itkg_code'],'dateEnd',$aField['datePicker']['dateEnd']);
						$bLog=true;
					}
					
					if($bLog)
					{
						$oLog = new LogXML();
						$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'dateEnd',$this->oXMLOriginal->aField[$key]['field']['datepicker']['dateEnd']['value'], $aField['datePicker']['dateEnd'],$this->oXMLOriginal->aField[$key]['field']['label']['value']);
						$this->collLog[] = $oLog;
						
					}
					
					// openingStart
					
					$bLog=false;
					if(isset($this->oXMLOriginal->aField[$key]['field']['datepicker']['openingStart']) && empty($aField['datePicker']['openingStart']))
					{
						$this->oXMLOriginal->deleteNode($this->oXMLOriginal->aField[$key]['field']['datepicker']['openingStart']['attributes']['itkg_code'],'openingStart');
						$bLog=true;
					}elseif($aField['datePicker']['openingStart'] && !isset($this->oXMLOriginal->aField[$key]['field']['datepicker']['openingStart']))
					{
						$this->oXMLOriginal->createPickerNode('openingStart', $this->oXMLOriginal->aField[$key]['field']['datepicker']['attributes']['itkg_code'], $aField['datePicker']['openingStart']);					
						$bLog=true;
					} elseif($aField['datePicker']['openingStart'] != $this->oXMLOriginal->aField[$key]['field']['datepicker']['openingStart']['value'])
					{	
						$bLog=true;
						$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['datepicker']['openingStart']['attributes']['itkg_code'],'openingStart',$aField['datePicker']['openingStart']);
					}
					
					if($bLog)
					{
						$oLog = new LogXML();
						$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'openingStart',$this->oXMLOriginal->aField[$key]['field']['datepicker']['openingStart']['value'], $aField['datePicker']['openingStart'],$this->oXMLOriginal->aField[$key]['field']['label']['value']);
						$this->collLog[] = $oLog;
						
					}
					
					// openingEnd
					if(isset($this->oXMLOriginal->aField[$key]['field']['datepicker']['openingEnd']) && empty($aField['datePicker']['openingEnd']))
					{
						$this->oXMLOriginal->deleteNode($this->oXMLOriginal->aField[$key]['field']['datepicker']['openingEnd']['attributes']['itkg_code'],'openingEnd');	
					}elseif($aField['datePicker']['openingEnd'] && !isset($this->oXMLOriginal->aField[$key]['field']['datepicker']['openingEnd']))
					{
						$this->oXMLOriginal->createPickerNode('openingEnd', $this->oXMLOriginal->aField[$key]['field']['datepicker']['attributes']['itkg_code'], $aField['datePicker']['openingEnd']);					
					} else if($aField['datePicker']['openingEnd']!=$this->oXMLOriginal->aField[$key]['field']['datepicker']['openingEnd']['value'])
					{														   
						$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['datepicker']['openingEnd']['attributes']['itkg_code'],'openingEnd',$aField['datePicker']['openingEnd']);
					}
					
					if($bLog)
					{
						$oLog = new LogXML();
						$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'openingEnd',$this->oXMLOriginal->aField[$key]['field']['datepicker']['openingEnd']['value'], $aField['datePicker']['openingEnd'],$this->oXMLOriginal->aField[$key]['field']['label']['value']);
						$this->collLog[] = $oLog;
						
					}

					// libeletEnumeration
					
					/* référential non modifiable 
					$tblLibelle = $this->oXMLOriginal->aField[$key]['field']['datepicker']['libeletEnumeration']['referential']['item'];
					if($this->checkForEditReferential($tblLibelle,$aField['datePicker']['libeletEnumeration']['items']))
					{	
						for ($iii = 0; $iii < count($tblLibelle); $iii++) {
							$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['datepicker']['libeletEnumeration']['referential']['item'][$iii]['attributes']['itkg_code'],'item',$aField['datePicker']['libeletEnumeration']['items'][$iii]['value']);
						}
					}
														
					// dayEnumeration
					$tblDay = $this->oXMLOriginal->aField[$key]['field']['datepicker']['dayEnumeration']['referential']['item'];
					if($this->checkForEditReferential($tblDay,$aField['datePicker']['dayEnumeration']['items']))
					{
						for ($iii = 0; $iii < count($tblDay); $iii++) {
							$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['datepicker']['dayEnumeration']['referential']['item'][$iii]['attributes']['itkg_code'],'item',$aField['datePicker']['dayEnumeration']['items'][$iii]['value']);
						}
					}
					
					// monthEnumeration
					$tblMonth = $this->oXMLOriginal->aField[$key]['field']['datepicker']['monthEnumeration']['referential']['item'];
					if($this->checkForEditReferential($tblMonth,$aField['datePicker']['monthEnumeration']['items']))
					{
						for ($iii = 0; $iii < count($tblMonth); $iii++) {
							$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['datepicker']['monthEnumeration']['referential']['item'][$iii]['attributes']['itkg_code'],'item',$aField['datePicker']['monthEnumeration']['items'][$iii]['value']);
						}
					}*/
					
					// forbiddenDays
									
					if(is_array($aField['datePicker']['forbiddenDays']) && !empty($aField['datePicker']['forbiddenDays']))
					{
						$this->oXMLOriginal->itemChangeForbidden($this->oXMLOriginal->aField[$key]['field']['datepicker']['forbiddenDays']['attributes']['itkg_code'], $aField['datePicker']['forbiddenDays']);
						
						/*$oLog = new LogXML();
						$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'forbiddenDays',$this->oXMLOriginal->aField[$key]['field']['datepicker']['openingEnd']['value'], $aField['datePicker']['openingEnd']);
						$this->collLog[] = $oLog;*/
					}
					
					// hourlabel
					/*if($aField['datePicker'] && !isset($this->oXMLOriginal->aField[$key]['field']['datepicker']['hourlabel']))
					{
						$this->oXMLOriginal->createPickerNode('hourlabel', $this->oXMLOriginal->aField[$key]['field']['datepicker']['attributes']['itkg_code'], $aField['datePicker']['hourlabel']);					
					} else if($aField['datePicker']['hourlabel']!=$this->oXMLOriginal->aField[$key]['field']['datepicker']['hourlabel']['value'])
					{														   
						$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['datepicker']['hourlabel']['attributes']['itkg_code'],'hourlabel',$aField['datePicker']['hourlabel']);
					}*/
					$bLog=false;
					if(isset($this->oXMLOriginal->aField[$key]['field']['datepicker']['hourlabel']) && empty($aField['datePicker']['hourlabel']))
					{
						$this->oXMLOriginal->deleteNode($this->oXMLOriginal->aField[$key]['field']['datepicker']['hourlabel']['attributes']['itkg_code'],'hourlabel');
						$bLog=true;
					}elseif($aField['datePicker']['hourlabel'] && !isset($this->oXMLOriginal->aField[$key]['field']['datepicker']['hourlabel']))
					{
						$this->oXMLOriginal->createPickerNode('hourlabel', $this->oXMLOriginal->aField[$key]['field']['datepicker']['attributes']['itkg_code'], $aField['datePicker']['hourlabel']);
						$bLog=true;
					} else if($aField['datePicker']['hourlabel'] != $this->oXMLOriginal->aField[$key]['field']['datepicker']['hourlabel']['value'])
					{
						$this->oXMLOriginal->editField($this->oXMLOriginal->aField[$key]['field']['datepicker']['hourlabel']['attributes']['itkg_code'],'hourlabel',$aField['datePicker']['hourlabel']);
						$bLog=true;
					}
					
					if($bLog)
					{
						$oLog = new LogXML();
						$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'hourlabel',$this->oXMLOriginal->aField[$key]['field']['datepicker']['hourlabel']['value'], $aField['datePicker']['hourlabel'],$this->oXMLOriginal->aField[$key]['field']['label']['value']);
						$this->collLog[] = $oLog;
						
					}
					
					// format
					if($aField['datePicker']['format']!=$this->oXMLOriginal->aField[$key]['field']['datepicker']['format']['attributes']['pattern'])
					{														   
						$this->oXMLOriginal->editAttribute($this->oXMLOriginal->aField[$key]['field']['datepicker']['format']['attributes']['itkg_code'],'format',$aField['datePicker']['format'], 'pattern');
						
						$oLog = new LogXML();
						$oLog->setLogEditComponent($this->oXMLOriginal->aField[$key]['field']['attributes']['itkg_code'], 'format',$this->oXMLOriginal->aField[$key]['field']['datepicker']['format']['attributes']['pattern'], $aField['datePicker']['format'],$this->oXMLOriginal->aField[$key]['field']['label']['value']);
						$this->collLog[] = $oLog;
						
					}
					
				}	
				
				if ($aField['type'] == 'hidden' && $aField['name'] != 'INSTANCE_ID') {
					if(isset($this->oXMLOriginal->oXMLGeneric->aField[$key]['field']['hidden']) && isset($this->oXMLOriginal->aField[$key]['field']['hidden'])) {
						
						if($this->oXMLOriginal->aField[$key]['field']['hidden']['value']['value'] != $this->oXMLOriginal->oXMLGeneric->aField[$key]['field']['hidden']['value']['value'] || 
							$this->oXMLOriginal->aField[$key]['field']['hidden']['value']['attributes']['max'] != $this->oXMLOriginal->oXMLGeneric->aField[$key]['field']['hidden']['value']['attributes']['max'] ||
							$this->oXMLOriginal->aField[$key]['field']['hidden']['value']['attributes']['min'] != $this->oXMLOriginal->oXMLGeneric->aField[$key]['field']['hidden']['value']['attributes']['min']) {
														
							$this->oXMLOriginal->editHiddenField($this->oXMLOriginal->aField[$key]['field']['attributes']['code']);
						}
						
					}
				}
			}	
				
		}
    }
    
    public function checkForEditReferential($aItemOri,$aItemPerso)
    {
 
    	if((is_array($aItemOri) && !empty($aItemOri)) && (is_array($aItemPerso) && !empty($aItemPerso)))
    	{
    		if(sizeof($aItemOri)!=sizeof($aItemPerso))
    		{
    			return true;
    		}
    		   		
    		foreach ($aItemOri as $kOri=>$itemOri)
    		{
    			if($itemOri['value']!=$aItemPerso[$kOri]['value'])
    			{
    				return true;
    			}
    			
    		}
    		
    	}else{
    		return true;
    	}
    	
    	return false;
       	    	
    }

   	 /**
     * supprime un ABTesting via le webService
     * @param string $code_instance
     * 
     */
    protected function deleteABTestingInstance($code_instance)
    {
    	try {
    		$serviceParams = array(
    				'instanceId' => $code_instance
    		);
    		 
    		$service = \Itkg\Service\Factory::getService(Pelican::$config['BOFORMS_BRAND_ID'] . '_SERVICE_BOFORMS', array());
    		 
    		$response = $service->call('deleteABTestingInstance', $serviceParams);
    		
    		return $response;
    	} catch(\Exception $e) {
    		echo $e->getMessage();
    	}
    }
  
    public function RemoveABtestingAction(){
    	
    	if($_POST['code_instance'])
    	{
    		// suppression en appelant le web service
    		$strResultWS = $this->deleteABTestingInstance($_POST['code_instance']);
    		
    		if ($strResultWS == 'OK') {
				// suppression en base
    			$oConnection = Pelican_Db::getInstance();
    			$aBind[':FORM_INCE'] =$oConnection->strToBind($_POST['code_instance']);
	    		
	    		$sSql = "delete from #pref#_boforms_formulaire
	    				 where FORM_INCE=:FORM_INCE ";
	    		$oConnection->query($sSql,$aBind);
	    		
    			$sSql = "delete from #pref#_boforms_formulaire_version
    				 where FORM_INCE=:FORM_INCE ";
    			$oConnection->query($sSql,$aBind);
    		    			
    			echo $strResultWS;
    		} else {
    			echo 'Remove AB testing ' . $_POST['code_instance'] . ': ' . $strResultWS;
    		}			
    	}
    	   	
    }
    
    public function resetAction() {
    	
   		if ($_POST['code_parent'] && $_POST['scode']) {
    		$sCode = $_POST['scode']; // personnalise
    		$code_parent = $_POST['code_parent']; // parent
    		
    		// reset xml value using the generic one
			$this->doResetForm($code_parent, $sCode);    		
    	}
    }
    
    private function doResetForm($parent, $personalized) {
		$oConnection = Pelican_Db::getInstance ();
		
		$aBind[':PERSONALIZED'] = $oConnection->strToBind($personalized);
    	//$aBind[':PARENT'] = $oConnection->strToBind($parent);
    	

    	$sqlGetMaxVersion = 'SELECT fv.FORM_XML_CONTENT
    						 FROM #pref#_boforms_formulaire f 
    			 			 inner join #pref#_boforms_formulaire_version fv on (f.FORM_INCE = fv.FORM_INCE and fv.FORM_VERSION=1)
    						 WHERE f.FORM_INCE = :PERSONALIZED 
    						 '; 
    	
    	$result = $oConnection->queryItem($sqlGetMaxVersion, $aBind);
		   	
    	
    	//$result = str_replace($generic,$personalized,$result);
    	
    	
    	
    	$sSql = "select FORM_DRAFT_VERSION
    			 from #pref#_boforms_formulaire f 
    			 inner join #pref#_boforms_formulaire_version fv on (f.FORM_INCE = fv.FORM_INCE and f.FORM_DRAFT_VERSION=fv.FORM_VERSION)
    			 WHERE f.FORM_INCE = :PERSONALIZED";
    	
    	$perso_version = $oConnection->queryItem($sSql, $aBind);
    
    	
    	//$aBind[':XML_FIRST'] = $oConnection->strToBind($result);
    	$sqlUpdate = "update #pref#_boforms_formulaire_version s
   					set s.FORM_XML_CONTENT = '".addslashes($result)."'
    				where s.FORM_INCE = :PERSONALIZED
    				and FORM_VERSION=".$perso_version;
   		$oConnection->query($sqlUpdate, $aBind);
	}
   		
    
    /**
     * Chargement des instances depuis le Webservice vers la BDD
     * @param string $country
     * @param string $brand
     */
    public function loadListInstanceWS($country,$brand)
    {
    	    	
	    try {
	    	ini_set('default_socket_timeout', 60);
	    	
	    	$serviceParams = array(
	    			'country' => $country,
	    			'brand' => $brand
	    	);
	    	 
	    	$service = \Itkg\Service\Factory::getService(Pelican::$config['BOFORMS_BRAND_ID'] . '_SERVICE_BOFORMS', array());
	    	 
	    	$response = $service->call('getInstances', $serviceParams);
			
	    	if($_GET['test'] == "getInstances")
	    	{
	    		print_r ($response);
	    	}
	    	if($_GET['test'] == "env")
	    	{
	    		var_dump ($_ENV["TYPE_ENVIRONNEMENT"]);
	    	}
	    	
	    	//debug($response->instance);die;
	    	if(!empty($response->instance) && is_array($response->instance))
	    	{
	    		$oConnection = Pelican_Db::getInstance ();
	    		$aBind=array();
	    		$aBind[':PAYS_CODE'] = $oConnection->strToBind($country);
	    		$aBind[':FORM_BRAND'] = $oConnection->strToBind($brand);
	    		
	    		$sSql ="SELECT FORM_INCE,FORM_CURRENT_VERSION,FORM_DRAFT_VERSION
	    				FROM #pref#_boforms_formulaire
	    				WHERE PAYS_CODE =:PAYS_CODE
	    				AND FORM_BRAND = :FORM_BRAND";
	    		$aInstances=$oConnection->queryTab($sSql,$aBind);
	    		
	    		if(!empty($aInstances) and is_array($aInstances))
	    		{
	    			foreach($aInstances as $instance)
	    			{
	    				$aInstanceBDD[$instance['FORM_INCE']] = $instance;
	    			}
	    		}
	    		unset($aInstances);
		    		
	    		//suppression des instances
	    		$sSql = "DELETE From #pref#_boforms_formulaire
	    				 WHERE PAYS_CODE =:PAYS_CODE
	    				 AND FORM_BRAND = :FORM_BRAND
	    				 and FORM_CURRENT_VERSION>=1
	    				 ";
	    		
	    		//DONE DEBOUCHE
	    		if(!Pelican::$config['BOUCHON_ON'])
	    		{
	    			$oConnection->query($sSql,$aBind);
	    		}
	    		
	    		if($_GET['test'] == "response")
	    		{
	    			print_r ($response);
	    		}
	    		    		
	    		foreach ($response->instance as $instanceWS)
	    		{
	    				//test
	    				//$instanceWS->instanceId = 'ACBE100100101001';
	    			
	    			if($_GET['test'] == "instance")
	    			{
	    				print_r ($instanceWS);
	    			}
	    		
	    				$parent = 'NULL';
	    				$isGene = 'NULL';
	    				$abtesting_num = 'NULL';
	    				
	    				if((int)substr($instanceWS->instanceId,5,1)==9)
    					{
    						
    						$parent = 'NULL';
    						    						
    						$isGene=1;
    						
    					}elseif((int)substr($instanceWS->instanceId,5,1)!=9 && (int)substr($instanceWS->instanceId,8,1)==0)
    					{ 
    						
    						if((int)substr($instanceWS->instanceId,9,1)>0)
    						{//contextualisés
    							$parent = substr_replace($instanceWS->instanceId, '0', 9, 1);
    						}else{//standards
    							$parent = substr_replace($instanceWS->instanceId, '9', 5, 1);
    							$parent = substr_replace($parent, '00', 10, 2);
    						}
    						
    						
    					}elseif((int)substr($instanceWS->instanceId,8,1)>0)
    					{//AB Testing	
    						$parent = substr_replace($instanceWS->instanceId, '0', 8, 1);
    						$abtesting_num=(int)substr($instanceWS->instanceId,8,1);
    					}
    							    					
    					$curr_version = 1 ;
    					$curr_draft = 1;
    					
    					if(!empty($aInstanceBDD[$instanceWS->instanceId]))
    					{
    						$curr_version = $aInstanceBDD[$instanceWS->instanceId]['FORM_CURRENT_VERSION'];
    						$curr_draft = $aInstanceBDD[$instanceWS->instanceId]['FORM_DRAFT_VERSION'];
    					}
    					
						$cult = $instanceWS->culture;
    					
    					$aBind=array();
    					$aBind[':FORM_INCE'] = $oConnection->strToBind($instanceWS->instanceId);
    						
    					$aBind[':FORM_NAME'] = $oConnection->strToBind($instanceWS->formName);
    					$aBind[':FORM_INSTANCE_NAME'] = $oConnection->strToBind($instanceWS->instanceName);
    					$aBind[':FORM_ID'] = $oConnection->strToBind($instanceWS->formId);
    					$aBind[':FORM_TYPE'] = $oConnection->strToBind($instanceWS->formType);
    					 
    					$aBind[':FORM_CONTEXT'] = (int)substr($instanceWS->instanceId,9,1);
    					$aBind[':FORM_CURRENT_VERSION'] = $curr_version;
    					$aBind[':FORM_DRAFT_VERSION'] = $curr_draft;
    					$aBind[':FORM_PARENT_INCE'] =  ($parent=='NULL'?$parent:$oConnection->strToBind($parent));
    					$aBind[':DEVICE_ID'] = (int)substr($instanceWS->instanceId,12,1);
    					$aBind[':TARGET_ID'] = (int)substr($instanceWS->instanceId,4,1);
    					$aBind[':CULTURE_ID'] = (int)$cult;
    					$aBind[':FORMSITE_ID'] = (int)substr($instanceWS->instanceId,6,2);
    					$aBind[':PAYS_CODE'] = $oConnection->strToBind(substr($instanceWS->instanceId,2,2));
    					$aBind[':FORM_BRAND'] = $oConnection->strToBind(substr($instanceWS->instanceId,0,2));
    					$aBind[':FORM_AB_TESTING'] = $abtesting_num;
    					$aBind[':OPPORTUNITE_ID'] = (int)substr($instanceWS->instanceId,14,2);
    					$aBind[':FORM_GENERIC'] = $isGene;
    					$aBind[':FORM_EDITABLE'] = ($instanceWS->editable == '1' || $instanceWS->editable == 1 ?$instanceWS->editable:'0');
    					$aBind[':FORM_COMMENTARY'] = ($instanceWS->instanceCommentary?$oConnection->strToBind($instanceWS->instanceCommentary):'NULL');
    					$aBind[':FORM_ACTIVATED'] = 0;
    					
    					 
    					$sqlInsert = "insert into #pref#_boforms_formulaire
						  (FORM_INCE,FORM_NAME,FORM_CONTEXT,FORM_CURRENT_VERSION,FORM_DRAFT_VERSION,FORM_PARENT_INCE,DEVICE_ID,TARGET_ID,CULTURE_ID,FORMSITE_ID,PAYS_CODE,FORM_BRAND,FORM_AB_TESTING,OPPORTUNITE_ID,FORM_GENERIC,FORM_EDITABLE,FORM_COMMENTARY,FORM_ACTIVATED,FORM_INSTANCE_NAME,FORM_ID,FORM_TYPE)
						   values (:FORM_INCE,:FORM_NAME,:FORM_CONTEXT,:FORM_CURRENT_VERSION,:FORM_DRAFT_VERSION,:FORM_PARENT_INCE,:DEVICE_ID,:TARGET_ID,:CULTURE_ID,:FORMSITE_ID,:PAYS_CODE,:FORM_BRAND,:FORM_AB_TESTING,:OPPORTUNITE_ID,:FORM_GENERIC,:FORM_EDITABLE,:FORM_COMMENTARY,:FORM_ACTIVATED,:FORM_INSTANCE_NAME,:FORM_ID,:FORM_TYPE)
					 	 ";
    					 
    					 
    					//DONE DEBOUCHE
	    				if(!Pelican::$config['BOUCHON_ON'])
	    				{
	    					$oConnection->query($sqlInsert,$aBind);
	    					
	    					if($_GET['test'] == "insert_formulaire")
	    					{
	    						print_r ($sqlInsert);
	    						print_r ($aBind);
	    					}
	    				}
	    				
	    				
	    				
	    				/** on insert la premiere version pour les instances inconus **/
	    				
	    					if(empty($aInstanceBDD[$instanceWS->instanceId]))
	    					{
	    						
	    						$sXML=$this->getInstanceWS($instanceWS->instanceId);//DONE DEBOUCHE
	    						
	    						if($_GET['test'] == "getInstanceWS")
	    						{
	    							print_r ($sXML);
	    						}
	    						    						
	    						if($sXML)
	    						{
		    						$aBind[':FORM_INCE'] = $oConnection->strToBind($instanceWS->instanceId);
									//$aBind[':FORM_XML_CONTENT'] = $oConnection->strToBind($sXML);
									$aBind[':FORM_DATE'] = $oConnection->strToBind(date("Y-m-d H:i:s"));
									//$aBind[':USER_LOGIN'] = $oConnection->strToBind($_SESSION[APP]['backoffice']['USER_LOGIN']);
																
							
									$sqlInsert = "replace into #pref#_boforms_formulaire_version (FORM_INCE,FORM_VERSION,FORM_XML_CONTENT,FORM_DATE,FORM_LOG,USER_LOGIN,STATE_ID)
												  values (:FORM_INCE,".$curr_draft.",'".addslashes($sXML)."',:FORM_DATE,NULL,NULL,".Pelican::$config['BOFORMS_STATE']['PUBLISH'].")
												  ";
							
									//DONE DEBOUCHE
									if(!Pelican::$config['BOUCHON_ON'])
									{
										$oConnection->query($sqlInsert,$aBind);
										if($_GET['test'] == "insert_version")
										{
											print_r ($sqlInsert);
											print_r ($aBind);
										}
									}
			    				}
	    					}
	    				/*****/
	    					
	    		}
	    		
	    	}else{
	    		die("Webservice return no instance");
	    	}
	    	 
	    } catch(\Exception $e) {
	    	echo $e->getMessage();
	    }
    }
    
    /**
     * RécurpÃ¨re le xml d'une instance via le webService
     * @param string $code_instance
     * 
     */
    public function getInstanceWS($code_instance)
    {
    	try {
    		$serviceParams = array(
    				'instanceId' => $code_instance
    		);
    		 
    		$service = \Itkg\Service\Factory::getService(Pelican::$config['BOFORMS_BRAND_ID'] . '_SERVICE_BOFORMS', array());
    		 
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
   
    /**
     * envoi le nouveau XML au Webservice
     * @param string $instanceId
     * @param string $instanceName
     * @param string $formId
     * @param string $formName
     * @param string $formType
     * @param string $instanceXML
     *  
     */
    public function udpdateWS($instanceId, $instanceName, $formId, $formName, $formType, $instanceXML,$editable=false, $comment=false, $errordie = true)
    {
      
    	try {
			//$FormTypeKey=$this->getFormType($formType);
    		$FormTypeKey=$formType;
			
    		//suppression des code_itkg
    		$instanceXML=FunctionsUtils::cleanXML($instanceXML);
    		
    		//prépare la chaine
    		$instanceXML = str_replace('<', '&lt;', $instanceXML);
    		$instanceXML = str_replace('>', '&gt;', $instanceXML);
    		$instanceXML = str_replace('"', '&quot;', $instanceXML);
    		
			//$instanceXML = "<![CDATA[".$instanceXML."]]>";
			
		
    		$serviceParams = array(
    				'instanceId' => $instanceId,
    				'instanceName' => $instanceName,
    				'formId' => $formId,
    				'formName' => $formName,
    				'formType' => $FormTypeKey,
    				'instanceXML' => $instanceXML
    		);
    		
    		if($editable!==false)
    		{
    			$serviceParams['editable'] = $editable;
    		}
    		if($comment!==false)
    		{
    			$serviceParams['comment'] = $comment;
    		}
			
			//debug($serviceParams);
			
    		ini_set('default_socket_timeout', 60);
			
			$service = \Itkg\Service\Factory::getService(Pelican::$config['BOFORMS_BRAND_ID'] . '_SERVICE_BOFORMS', array());
			
			$response = $service->call('updateInstance', $serviceParams);
			
			if($response!="OK")
			{
				$log = "[".date('Y-m-d H:i:s').']['.$instanceId.'] '.$response.'\r\n';
				error_log($log, 3, FunctionsUtils::getLogPath() . 'updateInstance.log');
				
				if($errordie) {
					if ($response == 'INSTANCE_NAME_ALREADY_EXIST' || $response == 'FORM_NAME_ALREADY_EXIST') {
						die(t('BOFORMS_DUPL_INSTANCE_' . $response));
					} else {
						die('update XML to Webservice failed : '. $response);
					}
				}
				return false;
			}else{
				//vide le cache instance
				$this->clearCacheInstance($instanceId);
				return true;
			}
			
    		
    	} catch(\Exception $e) {
    		$log = "[".date('Y-m-d H:i:s').']['.$instanceId.'] '.$e->getMessage().'\r\n';
    		error_log($log, 3, FunctionsUtils::getLogPath() . 'updateInstance.log');
    		
    		if($errordie) {
    			die($e->getMessage());
    		}
    		return false;
    	}
   	}
 
   	protected function clearCacheInstance($instanceId)
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
   	
	public function saveContextualises()
	{
		$oConnection = Pelican_Db::getInstance ();
		$aGroupe = $this->getSiteGroupe();
		
		 
		foreach ($aGroupe as $Groupe)
		{
			$aXMLContext=array();
			$aINCEContextGen=array();
		
			$codeParent="";
			//$codeGen = substr_replace($this->oXMLOriginal->instance['id'],9,5,1);
			//$codeGen = substr_replace($codeGen,'00',10,2);
		
			$site_id = (strlen($Groupe['FORMSITE_ID'])>1?$Groupe['FORMSITE_ID']:'0'.$Groupe['FORMSITE_ID']);
			
			
			$codeParent = substr_replace($this->oXMLOriginal->instance['id'],'0',9,1);
			$codeParent = substr_replace($codeParent,$site_id,6,2);//site du groupe en courrant
			
		
			$aBind[':FORM_INCE'] = $oConnection->strToBind($codeParent);
			$sqlContextualise = "SELECT f.FORM_INCE, FORM_XML_CONTENT, f.FORM_CURRENT_VERSION,f.FORM_DRAFT_VERSION,f.FORM_NAME, f.FORM_INSTANCE_NAME,f.FORM_ID,f.FORM_TYPE,fp.FORM_INCE as FORM_INCE_PARENT,fp.FORM_NAME as FORM_NAME_PARENT
		    						   FROM #pref#_boforms_formulaire f
		    						   INNER JOIN #pref#_boforms_formulaire_version fv ON (fv.FORM_INCE=f.FORM_INCE AND fv.FORM_VERSION=f.FORM_DRAFT_VERSION)
									   INNER JOIN #pref#_boforms_formulaire fp ON (fp.FORM_INCE=:FORM_INCE)
		    						   WHERE f.FORM_PARENT_INCE = :FORM_INCE
									   AND f.FORM_CONTEXT > 0
		    						   ";
			$aXMLContext = $oConnection->queryTab($sqlContextualise,$aBind);
						
			$sXMLori = $this->oXMLOriginal->dom->saveXML();
			
			if (!empty($aXMLContext) && is_array($aXMLContext))
			{
												
				
				foreach ($aXMLContext as $k=>$context)
				{
										
					$aBind=array();
		
					$oXMLContext = new XMLHandle($context['FORM_XML_CONTENT'],'xml');
					$oXMLContext->Parser_read(false);
		
		
					/*** Le xml standard écrase le XML du contextualisé pour récupérer toute les modifications du webmaset, puis on redonne les bon id / nom / et ordre des étapes du contextualisé qui ne doivent pas changer***/
					$sXML = $sXMLori;
					$sXML = str_replace($this->oXMLOriginal->instance['id'],$oXMLContext->instance['id'],$sXML);
					$sXML = str_replace($this->oXMLOriginal->instance['name'],$oXMLContext->instance['name'],$sXML);
					$sXML = str_replace($this->oXMLOriginal->instance['form'],$oXMLContext->instance['form'],$sXML);
					$sXML = str_replace($this->oXMLOriginal->form['name'],$oXMLContext->form['name'],$sXML);
					$sXML = str_replace($context['FORM_NAME_PARENT'],$context['FORM_NAME'],$sXML);
		
					$aStructSave=$oXMLContext->aPages;
					
					$oXMLContext = new XMLHandle($sXML,'xml');
					$oXMLContext->Parser_read(false);
					
					/*** rétablissement des étapes ***/
								
					if(sizeof($oXMLContext->aPages)>1)
					{
						$oXMLContext->moveEtape($oXMLContext->aPages[0]['itkg_code'],$aStructSave);
						
						foreach ($aStructSave as $kr=>$row)
						{
							
							$oXMLContext->editTitleEtape($row['itkg_code'],$row['title']);
						}
						
						$sXML = $oXMLContext->dom->saveXML();
					}
		
					if($oXMLContext->structureTitleFieldSetToSave)
					{
						$sXML = $oXMLContext->revertStructureTitle($sXML);
					}
					
					$aXMLContext[$k]['FORM_XML_CONTENT']=$sXML;
		
					$aBind[':FORM_INCE'] = $oConnection->strToBind($oXMLContext->instance['id']);
					$sqlContext = "select FORM_CURRENT_VERSION,FORM_DRAFT_VERSION from #pref#_boforms_formulaire where FORM_INCE= :FORM_INCE";
					$aRes = $oConnection->queryRow($sqlContext,$aBind);
		
					$aXMLContext[$k]['FORM_CURRENT_VERSION'] = $aRes['FORM_DRAFT_VERSION']+1;
					$aXMLContext[$k]['FORM_DRAFT_VERSION'] =$aRes['FORM_DRAFT_VERSION']+1;
		
					//updateTable
					$aBind[':FORM_INCE'] = $oConnection->strToBind($oXMLContext->instance['id']);
						
					$sqlUpdate = "update #pref#_boforms_formulaire
			    						  set FORM_CURRENT_VERSION =".$aXMLContext[$k]['FORM_CURRENT_VERSION'].",
			    						  	  FORM_DRAFT_VERSION = ".$aXMLContext[$k]['FORM_DRAFT_VERSION']."
			    						  where FORM_INCE = :FORM_INCE
			    						  ";
					$oConnection->query($sqlUpdate,$aBind);
						
					//$aBind[':FORM_XML_CONTENT'] = $oConnection->strToBind($aXMLContext[$k]['FORM_XML_CONTENT']);
					$aBind[':FORM_DATE'] = $oConnection->strToBind(date("Y-m-d H:i:s"));
					$aBind[':USER_LOGIN'] = $oConnection->strToBind($_SESSION[APP]['backoffice']['USER_LOGIN']);
					$sqlUpdate = "insert into #pref#_boforms_formulaire_version (FORM_INCE,FORM_VERSION,FORM_XML_CONTENT,FORM_DATE,FORM_LOG,USER_LOGIN,STATE_ID)
			    						  values (:FORM_INCE,".$aXMLContext[$k]['FORM_CURRENT_VERSION'].",'".addslashes($aXMLContext[$k]['FORM_XML_CONTENT'])."',:FORM_DATE,NULL,:USER_LOGIN,".Pelican::$config['BOFORMS_STATE']['PUBLISH'].")
			    						  ";
					$oConnection->query($sqlUpdate,$aBind);
					
					$this->CleanVersions($oXMLContext->instance['id'],false);

					/*mise a jour webservice*/
					 	
					/**** Webservice update xml ***/
					$this->udpdateWS($oXMLContext->instance['id'],$context['FORM_INSTANCE_NAME'],$context['FORM_ID'],$context['FORM_NAME'],$context['FORM_TYPE'],$aXMLContext[$k]['FORM_XML_CONTENT']);
					/******/
								
					
				}
				 
				unset($oXMLContext);
				 
			}/*else{

				$oConnection = Pelican_Db::getInstance();
				$aBind[':CODE_INSTANCE'] = $oConnection->strToBind($codeParent);


				$sqlXMLInstanceparent = "select f.FORM_INCE,FORM_NAME,FORM_INSTANCE_NAME,FORM_ID,FORM_TYPE
    					   from #pref#_boforms_formulaire f
    					   where f.FORM_INCE = :CODE_INSTANCE
    					   ";
								
				$xmlParent=$oConnection->queryRow($sqlXMLInstanceparent,$aBind);
				
				if(empty($xmlParent))
				{//premiÃ¨re version du standard personnalisÃ© => on crÃ©Ã© par defaut les 2 contextualisÃ©s

					$codeGene = substr_replace($codeParent,'9',5,1);
					$codeGene = substr_replace($codeGene,'00',10,2);
					
					$aBind[':CODE_INSTANCE'] = $oConnection->strToBind($codeGene);
					$xmlGene=$oConnection->queryRow($sqlXMLInstanceparent,$aBind);
					
					if(!empty($xmlGene))
					{
					
						for ($i = 1; $i <= 2; $i++) 
						{//2 contexte
						 				
							/// Le xml standard Ã©crase le XML du contextualisÃ© pour rÃ©cupÃ©rer toute les modifications du webmaset, puis on redonne les bon id / nom / et ordre des Ã©tapes du contextualisÃ© qui ne doivent pas changer
							$sXML = $sXMLori;
												
							$sContextid = substr_replace($codeParent,$i,9,1);
							
							
							//génération du form name instance name form id
							$isSiteRef = false;
							if((int)$this->oXMLOriginal->instance['site_id']==$Groupe['FORMSITE_ID'])//le site rÃ©fÃ©rent a dÃ©jÃƒÂ  Ã©tÃ© enregistrÃ©
							{
								$isSiteRef = true;
							}
							
							$instanceName = $this->generateInstanceName($codeGene,$this->oXMLOriginal->instance['id'],$xmlGene['FORM_INSTANCE_NAME'],$i);
							$formName = $this->generateFormName($instanceName);
							$formid = $this->generateFormid($xmlGene['FORM_ID'],$i,0,$this->oXMLOriginal->instance['culture_id']);
							$formType = $xmlGene['FORM_TYPE'];
							
														
							$sXML = str_replace($this->oXMLOriginal->instance['id'],$sContextid,$sXML);
							$sXML = str_replace($this->oXMLOriginal->instance['name'],$instanceName,$sXML);
							$sXML = str_replace($this->oXMLOriginal->form['id'],$formid,$sXML);
							$sXML = str_replace($xmlParent['FORM_NAME'],$formName,$sXML);
							$sXML = preg_replace("/pageName\":\".*?\"/", "pageName\":\"$formName\"", $sXML);
											

							$aBind=array();
							$aBind[':FORM_INCE'] = $oConnection->strToBind($sContextid);
							
							$aBind[':FORM_NAME'] = $oConnection->strToBind($formName);
							$aBind[':FORM_INSTANCE_NAME'] = $oConnection->strToBind($instanceName);
							$aBind[':FORM_ID'] = $oConnection->strToBind($formid);
							$aBind[':FORM_TYPE'] = $oConnection->strToBind($formType);
							
							$aBind[':FORM_CONTEXT'] = (int)substr($sContextid,9,1);
							$aBind[':FORM_CURRENT_VERSION'] = 1;
							$aBind[':FORM_DRAFT_VERSION'] = 1;
							$aBind[':FORM_PARENT_INCE'] =  $oConnection->strToBind($codeParent);
							$aBind[':DEVICE_ID'] = (int)substr($sContextid,12,1);
							$aBind[':TARGET_ID'] = (int)substr($sContextid,4,1);
							$aBind[':CULTURE_ID'] = (int)substr($sContextid,10,2);
							$aBind[':FORMSITE_ID'] = (int)substr($sContextid,6,2);
							$aBind[':PAYS_CODE'] = $oConnection->strToBind(substr($sContextid,2,2));
							$aBind[':FORM_BRAND'] = $oConnection->strToBind(substr($sContextid,0,2));
							$aBind[':FORM_AB_TESTING'] = $abtesting_num;
							$aBind[':OPPORTUNITE_ID'] = (int)substr($sContextid,14,2);
							$aBind[':FORM_GENERIC'] = $isGene;
							$aBind[':FORM_EDITABLE'] = 1;
							$aBind[':FORM_COMMENTARY'] = 'NULL';
																					
													 
							$sqlInsert = "insert into #pref#_boforms_formulaire
								  (FORM_INCE,FORM_NAME,FORM_CONTEXT,FORM_CURRENT_VERSION,FORM_DRAFT_VERSION,FORM_PARENT_INCE,DEVICE_ID,TARGET_ID,CULTURE_ID,FORMSITE_ID,PAYS_CODE,FORM_BRAND,FORM_AB_TESTING,OPPORTUNITE_ID,FORM_GENERIC,FORM_EDITABLE,FORM_COMMENTARY,FORM_INSTANCE_NAME,FORM_ID,FORM_TYPE)
								   values (:FORM_INCE,:FORM_NAME,:FORM_CONTEXT,:FORM_CURRENT_VERSION,:FORM_DRAFT_VERSION,:FORM_PARENT_INCE,:DEVICE_ID,:TARGET_ID,:CULTURE_ID,:FORMSITE_ID,:PAYS_CODE,:FORM_BRAND,:FORM_AB_TESTING,:OPPORTUNITE_ID,:FORM_GENERIC,:FORM_EDITABLE,:FORM_COMMENTARY,:FORM_INSTANCE_NAME,:FORM_ID,:FORM_TYPE)
							 	 ";
							$oConnection->query($sqlInsert,$aBind);
							
							if($this->oXMLOriginal->structureTitleFieldSetToSave)
							{
								$sXML = $oXMLContext->revertStructureTitle($sXML);
							}
							
							//$aBind[':FORM_XML_CONTENT'] = $oConnection->strToBind($sXML);
							$aBind[':FORM_DATE'] = $oConnection->strToBind(date("Y-m-d H:i:s"));
							$aBind[':USER_LOGIN'] = $oConnection->strToBind($_SESSION[APP]['backoffice']['USER_LOGIN']);
							$sqlUpdate = "insert into #pref#_boforms_formulaire_version (FORM_INCE,FORM_VERSION,FORM_XML_CONTENT,FORM_DATE,FORM_LOG,USER_LOGIN,STATE_ID)
					    						  values (:FORM_INCE,1,'".addslashes($sXML)."',:FORM_DATE,NULL,:USER_LOGIN,".Pelican::$config['BOFORMS_STATE']['PUBLISH'].")
					    						  ";
							$oConnection->query($sqlUpdate,$aBind);
														
							
							// Webservice update xml
							$this->udpdateWS($sContextid,$instanceName,$formid,$formName,$formType,$sXML);
							//
						}
					}
				
				}
				
			}*/
			
			
		}
	

	}
	
	
	/** génère le nom de l'instance à partir du nom de l'instance generic
	 * exemple : 
	 * generic = CPPV2 - Generic Book A Test Drive Mobile PART CPPv2 (fr-BE)
	 * 
	 * devient si standard => CPPV2 - Book A Test Drive Mobile PART CPPv2 (fr-BE)
	 * devient si contextualisé => CPPV2 - Book A Test Drive Mobile PART CPPv2 (fr-BE) CAR/PDV
	 */
	public function generateInstanceName($codeGene, $codePerso,$InstanceNameGeneric,$context=0,$ABTesting=0)
	{
		if(empty($InstanceNameGeneric))
			return false;
				
		$sCultureGen=FunctionsUtils::getCultureByCodeInstance($codeGene).'-'.substr($codeGene,2,2);
		
		$oInstance = new FormInstance($codePerso);
		$aCulturePerso = $oInstance->getCulture();
		$sCulurePerso = $aCulturePerso['lang'].'-'.$aCulturePerso['pays'];
				
		$newName = str_replace ('- Generic', '-',$InstanceNameGeneric);
		$newName = str_replace ('Generic', '',$newName);
		$newName = str_replace ('  ', ' ',$newName);
		$newName = str_replace ('('.$sCultureGen.')','('.$sCulurePerso.')',$newName);
		
		if((int)$context == 1)
		{
			$newName = $newName . ' PDV';
		}elseif((int)$context == 2){
			$newName = $newName . ' CAR';
		}
		
		if($ABTesting > 0)
		{
			$newName = $newName.' ABTesting v'.$ABTesting; 
		}
		
		return $newName . ' BOFORMS-' . date('Y-m-d-H:i:s');
		
	}
	
	/** génère le nom du formulaire à partir du nom de l'instance
	 * exemple :
	 * instance name = CPPV2 - Book A Test Drive Mobile PART CPPv2 (fr-BE)
	 *
	 * devient => Book A Test Drive Mobile PART CPPv2 (fr-BE)
	 */
	public function generateFormName($InstanceName)
	{
		if(empty($InstanceName))
			return false;
		
		
		$aTab = explode(' - ',$InstanceName);
		$formName = end($aTab) ;
		
		return $formName;
	}
	
	/** génère l'id formulaire à partir de l'id formid du généric, on y ajoute le context
	 * exemple :
	 * formid generic = FORM300000035804
	 *
	 * devient si standard => FORM3000000358040
	 * devient si context PDV => FORM3000000358041
	 */
	public function generateFormid($formIdGeneric, $context=0, $ABTesting=0, $culture_id)
	{
		if(empty($formIdGeneric))
			return false;
		
		$cara = substr($formIdGeneric,4,1);
						
		$formIdGeneric = substr($formIdGeneric,9,8);
				
		$formid = 'FORM'.$cara.$formIdGeneric.$context.$ABTesting.$culture_id;
		
		return $formid;
		
	}
	
	public function saveStandardGroupe($bcontext=false)
	{
			$oConnection = Pelican_Db::getInstance ();
		$aGroupe = $this->getSiteGroupe();
		
		$sSQL = "select f.FORM_INCE, FORM_DRAFT_VERSION, FORM_CURRENT_VERSION, FORM_XML_CONTENT,FORM_NAME, FORM_INSTANCE_NAME,FORM_ID,FORM_TYPE
						 from #pref#_boforms_formulaire f
						 inner join #pref#_boforms_formulaire_version fv ON (f.FORM_INCE=fv.FORM_INCE AND f.FORM_CURRENT_VERSION=fv.FORM_VERSION)
						 WHERE f.FORM_INCE=:FORM_INCE" ;
	
		
		foreach($aGroupe as $Groupe)
		{
			
			$oXML=false;
			
			if((int)$this->oXMLOriginal->instance['site_id']!=$Groupe['FORMSITE_ID'])//le site rÃ©fÃ©rent a dÃ©jÃƒÂ  Ã©tÃ© enregistrÃ©
			{
				/*** rÃ©cupÃƒÂ¨re le contenu publiÃ© du standard ***/
				
				$codeStand = $this->oXMLOriginal->instance['id'];		
				$site_id = (strlen($Groupe['FORMSITE_ID'])>1?$Groupe['FORMSITE_ID']:'0'.$Groupe['FORMSITE_ID']);
				$codeStand = substr_replace($codeStand,$site_id,6,2);//site du groupe en courrant
			
				$aBind[':FORM_INCE']=$oConnection->strToBind($codeStand);
				
				$Standard = $oConnection->queryRow($sSQL,$aBind);
				
												
				if(empty($Standard))
				{// pas encore de personnalisÃ© (ou contextualisé)
										
					if($bcontext)
					{
						$codeGene = substr_replace($codeStand,'0',9,1);
					}else
					{
						$codeGene = substr_replace($codeStand,'9',5,1);
						$codeGene = substr_replace($codeGene,'00',10,2);
					}
					
					
					$aBind[':FORM_INCE']=$oConnection->strToBind($codeGene);
					$generic = $oConnection->queryRow($sSQL,$aBind);
					
					
					if($generic)
					{
						$bnew=true;
						
						$oXML = new XMLHandle($generic['FORM_XML_CONTENT'],'xml');
						$oXML->Parser_read(false);
						
						$context=substr($codeStand,9,1);
						
						$instanceName = $this->generateInstanceName($codeGene,$codeStand,$generic['FORM_INSTANCE_NAME'],$context);
						$formName = $this->generateFormName($instanceName);
						$formId = $this->generateFormid($generic['FORM_ID'],$context,0,$this->oXMLOriginal->instance['culture_id']);
						$formType = $generic['FORM_TYPE'];
						
						$icurrVersion = 1;
						$idraftVersion = 1;
						
						//TO DO generate formname et formid						
						$aBind[':FORM_INCE'] = $oConnection->strToBind($codeStand);
						
						$aBind[':FORM_NAME'] = $oConnection->strToBind($formName);
						$aBind[':FORM_INSTANCE_NAME'] = $oConnection->strToBind($instanceName);
						$aBind[':FORM_ID'] = $oConnection->strToBind($formId);
						$aBind[':FORM_TYPE'] = $oConnection->strToBind($formType);
						
						$aBind[':FORM_CONTEXT'] = (int)substr($codeStand,9,1);
						$aBind[':FORM_CURRENT_VERSION'] = $icurrVersion;
						$aBind[':FORM_DRAFT_VERSION'] = $idraftVersion;
						$aBind[':FORM_PARENT_INCE'] = $oConnection->strToBind($codeGene);
						$aBind[':DEVICE_ID'] = (int)substr($codeStand,12,1);
						$aBind[':TARGET_ID'] = (int)substr($codeStand,4,1);
						$aBind[':CULTURE_ID'] = (int)substr($codeStand,10,2);
						$aBind[':FORMSITE_ID'] = (int)substr($codeStand,6,2);
						$aBind[':PAYS_CODE'] = $oConnection->strToBind(substr($codeStand,2,2));
						$aBind[':FORM_BRAND'] = $oConnection->strToBind(substr($codeStand,0,2));
						$aBind[':FORM_AB_TESTING'] = 'NULL';
						$aBind[':OPPORTUNITE_ID'] = (int)substr($codeStand,14,2);
						//$aBind[':FORM_GENERIC'] = (int)substr($codeStand,9,1);
						
						$sqlUpdate = "insert into #pref#_boforms_formulaire
							  (FORM_INCE,FORM_NAME,FORM_CONTEXT,FORM_CURRENT_VERSION,FORM_DRAFT_VERSION,FORM_PARENT_INCE,DEVICE_ID,TARGET_ID,CULTURE_ID,FORMSITE_ID,PAYS_CODE,FORM_BRAND,FORM_AB_TESTING,OPPORTUNITE_ID,FORM_INSTANCE_NAME,FORM_ID,FORM_TYPE)
							   values (:FORM_INCE,:FORM_NAME,:FORM_CONTEXT,:FORM_CURRENT_VERSION,:FORM_DRAFT_VERSION,:FORM_PARENT_INCE,:DEVICE_ID,:TARGET_ID,:CULTURE_ID,:FORMSITE_ID,:PAYS_CODE,:FORM_BRAND,:FORM_AB_TESTING,:OPPORTUNITE_ID,:FORM_INSTANCE_NAME,:FORM_ID,:FORM_TYPE)
						 	 ";
						
					}
					
				}else{
					$bnew=false;
					
					$oXML = new XMLHandle($Standard['FORM_XML_CONTENT'],'xml');
					$oXML->Parser_read(false);
					
					$icurrVersion = $Standard['FORM_DRAFT_VERSION']+1;
					$idraftVersion = $Standard['FORM_DRAFT_VERSION']+1;
					
					$aBind[':FORM_INCE']=$oConnection->strToBind($codeStand);
					$sqlUpdate = "update #pref#_boforms_formulaire
						  set FORM_CURRENT_VERSION =".$icurrVersion.",
						  	  FORM_DRAFT_VERSION = ".$idraftVersion."
						  where FORM_INCE = :FORM_INCE
						  ";
					
					$formId = $Standard['FORM_ID'];
					$formName = $Standard['FORM_NAME'];
					$instanceName = $Standard['FORM_INSTANCE_NAME'];
					$formType = $Standard['FORM_TYPE'];
				}
					
				
				if($oXML && !$bnew)
				{
					$oConnection->query($sqlUpdate,$aBind);
					
					
					/*** Le xml standard écrase le XML du site courant pour récupérer les modifications du webmaster, puis on redonne les bon id / nom / ***/
					$sXML = $this->oXMLOriginal->dom->saveXML();
					$sXML = str_replace($this->oXMLOriginal->instance['id'],$codeStand,$sXML);
					$sXML = str_replace($this->oXMLOriginal->instance['name'],$instanceName,$sXML);
					$sXML = str_replace($this->oXMLOriginal->instance['form'],$formId,$sXML);
					$aTab = explode(' - ',$this->oXMLOriginal->instance['name']);
					$formNameOri = end($aTab) ;
					$sXML = str_replace($formNameOri,$formName,$sXML);
					
					$aBind[':FORM_INCE'] = $oConnection->strToBind($codeStand);
					//$aBind[':FORM_XML_CONTENT'] = $oConnection->strToBind($sXML);
					$aBind[':FORM_DATE'] = $oConnection->strToBind(date("Y-m-d H:i:s"));
					$aBind[':USER_LOGIN'] = $oConnection->strToBind($_SESSION[APP]['backoffice']['USER_LOGIN']);
					
					
					if($this->oXMLOriginal->structureTitleFieldSetToSave)
					{
						$sXML = $oXMLContext->revertStructureTitle($sXML);
					}
					
					$sqlUpdate = "replace into #pref#_boforms_formulaire_version (FORM_INCE,FORM_VERSION,FORM_XML_CONTENT,FORM_DATE,FORM_LOG,USER_LOGIN,STATE_ID)
								  values (:FORM_INCE,".$idraftVersion.",'".addslashes($sXML)."',:FORM_DATE,NULL,:USER_LOGIN,".Pelican::$config['BOFORMS_STATE']['PUBLISH'].")
								  ";
					
				
					$oConnection->query($sqlUpdate,$aBind);
					/****/
		
					
					
					/**** Webservice update xml ***/
						$this->udpdateWS($codeStand,$instanceName,$formId,$formName,$formType,$sXML);
					/******/
						
					$this->CleanVersions($codeStand,false);
				}								
			}
		}
	}
	
	public function checkInstance($code)
	{
		$oConnection = Pelican_Db::getInstance ();
		
		if($code)
		{
			$aBind[':FORM_INCE'] = $oConnection->strToBind($code);
			
			
			$sSQL = "select FORM_INCE
					 FROM #pref#_boforms_formulaire
					 Where FORM_INCE=:FORM_INCE
					 ";
			$res = $oConnection->queryItem($sSQL,$aBind);
						
			
			if(empty($res))
			{
				return false;
			}else{
				return true;
			}
									
		}else{
			return false;
		}
		
	}

	public function getFormType($id)
	{
		$oConnection = Pelican_Db::getInstance ();
		
		$aBind[':OPPORTUNITE_ID'] = $id;
		$sSQL = "select OPPORTUNITE_KEY from #pref#_boforms_opportunite where OPPORTUNITE_ID = :OPPORTUNITE_ID";
		$res=$oConnection->queryItem($sSQL,$aBind);
		if(empty($res))
		{
			die('No formType');
		}
		else{
			return $res;
		}
		
	}
	   	
	public function getSiteGroupe()
	{
		
		$oConnection = Pelican_Db::getInstance ();
		
		$aBind[':FORMSITE_ID']=(int)$this->oXMLOriginal->instance['site_id'];
		
		/*
		$sSQL = "SELECT FORMSITE_ID , FORMSITE_KEY
				 FROM #pref#_boforms_formulaire_site
					  WHERE GROUPE_ID = (select GROUPE_ID from #pref#_boforms_formulaire_site where FORMSITE_ID=:FORMSITE_ID)";
		*/
		
		$sSQL = "SELECT fs.FORMSITE_ID , fs.FORMSITE_KEY
				 FROM #pref#_boforms_formulaire_site fs
				 INNER JOIN #pref#_boforms_groupe_formulaire gf on fs.FORMSITE_ID = gf.FORMSITE_ID
				 INNER JOIN #pref#_boforms_groupe g on g.GROUPE_ID = gf.GROUPE_ID
				 WHERE fs.FORMSITE_ID = :FORMSITE_ID and g.SITE_ID = " . $_SESSION[APP]['SITE_ID'];
		
		
		 
		$result =  $oConnection->queryTab($sSQL, $aBind);
		
		if(empty($result))
		{
			die('No site id');
		}else{
			return $result;
		}
		
	}

    private function updateSelectedChoices($aField) {
    	if (is_array($aField['choices']) && count($aField['choices']) > 0) {
			$set_choices_to_false = false;
			if (isset($aField['selected_choice'])) {
				if (is_array($aField['selected_choice'])) {
					if ( count($aField['selected_choice']) > 0 ) {
						foreach ($aField['choices'] as $choice_key => $choice_val) {
							if (in_array($aField['choices'][$choice_key]['id'], $aField['selected_choice'])) {
								$this->oXMLOriginal->editChoiceAttribute($aField['itkg_code'], $aField['choices'][$choice_key]['id'], 'item', 'true', 'selected');
							} else {
								$this->oXMLOriginal->editChoiceAttribute($aField['itkg_code'], $aField['choices'][$choice_key]['id'], 'item', 'false', 'selected');
							}
						}
					} else {
						$set_choices_to_false = true;
					}
				} else {
					if ($aField['selected_choice'] != '') {
						foreach ($aField['choices'] as $choice_key => $choice_val) {
							if ($aField['choices'][$choice_key]['id'] == $aField['selected_choice']) {
								$this->oXMLOriginal->editChoiceAttribute($aField['itkg_code'], $aField['choices'][$choice_key]['id'], 'item', 'true', 'selected');
							} else {
								$this->oXMLOriginal->editChoiceAttribute($aField['itkg_code'], $aField['choices'][$choice_key]['id'], 'item', 'false', 'selected');
							}
						}
					} else {
						$set_choices_to_false = true;
					}
				}
			} else {
				$set_choices_to_false = true;
			}
			
			if ($set_choices_to_false == true) {
				foreach ($aField['choices'] as $choice_key => $choice_val) {
					$this->oXMLOriginal->editChoiceAttribute($aField['itkg_code'], $aField['choices'][$choice_key]['id'], 'item', 'false', 'selected');
				}
			}
		}
    }
    
    //vérifie si le titre de l'abtesting existe
    public function CheckABTestingTitleExistAction ()
    {
    	
    	if($_POST['title'])
    	{
    		$oConnection = Pelican_Db::getInstance ();
    		 
    		$aBind[':FORM_NAME']=$oConnection->strToBind($_POST['title']);
    		$aBind[':FORM_INCE']=$oConnection->strToBind($_POST['id']);
    		 
    		$sSQL = "SELECT FORM_NAME
				 FROM #pref#_boforms_formulaire
					  WHERE FORM_NAME = :FORM_NAME
    				  AND FORM_INCE != :FORM_INCE ";
    		
    		$result =  $oConnection->queryItem($sSQL, $aBind);
    		
    		if(empty($result))
    		{
    			echo 'false';
    		}else{
    			echo 'true';
    		}    			
    		
    	}
    	
    	
    }
    
    //envoie par mail à DIG la liste des abtesting d'une instance
    public function ABTestingSendDIGAction ()
    { 
    	if($_POST['code_instance'])
    	{
    		$oConnection = Pelican_Db::getInstance ();
    		 
    		$sqlLangue = "SELECT c.langue_code, c.langue_label, a.SITE_CODE_PAYS FROM #pref#_site_code a
					  INNER JOIN #pref#_site_language b ON a.site_id = b.site_id
					  INNER JOIN #pref#_language c ON c.langue_id = b.langue_id
					  WHERE a.site_id = :SITE_ID";
    		$aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
    		
    		
    		$aLangues = $oConnection->queryTab($sqlLangue, $aBind);
    		
    		$oConnection = Pelican_Db::getInstance ();
    		$Ssql = "select FORM_NAME,PAYS_CODE FROM #pref#_boforms_formulaire where FORM_INCE = '".$_POST['code_instance']."'";
    		$aFormInfo=$oConnection->queryRow($Ssql);
    		
    		$oInstance = new FormInstance($_POST['code_instance']);
    		$aABTesting = $oInstance->getABTesting(true);
    	
    		if(!empty($aABTesting))
    		{
    			
    		    			
    			$strBody = "
    						<div>Bonjour,</div>
    						<p>Veuillez trouver ci-dessous une demande de soumission de variantes A/B Testing pour l'instance : <b>".$aFormInfo['FORM_NAME']."</b> (".$_POST['code_instance'].")</p>
    						
    						<ul>
    							<li>Pays : ".$aFormInfo['PAYS_CODE']."</li>	
	    						<li>Nom du webmaster : ".$_SESSION[APP]['user']['name']."</li>		
	    						<li>rpi : ".$_SESSION[APP]['user']['id']."</li>		
	    						<li>Environnement : ".$_ENV["TYPE_ENVIRONNEMENT"]."</li>										
	    					</ul>	
	    				
	    					<div><b>Liste de variante A/B Testing</b></div>			
	    					<table border='1' style='border: 1px solid black;'>
	    						<tr style='border: 1px solid black;'>
	    							<td style='border: 1px solid black;'><b>code instance</b></td>
	    							<td style='border: 1px solid black;'><b>Titre</b></td>
	    							<td style='border: 1px solid black;'><b>URL</b></td>
	    						</tr>
	    							
    					";
    			
	    		foreach ($aABTesting as $k=>$ab)
	    		{
	    			
	    			//on publie les abtestings
	    			/**** Webservice update xml ***/
	    			$this->udpdateWS($ab['FORM_INCE'],$ab['FORM_INSTANCE_NAME'],$ab['FORM_ID'],$ab['FORM_NAME'],$ab['FORM_TYPE'],$ab['FORM_XML_CONTENT']);
	    			/******/
	    			$url = Pelican::$config['BOFORMS_URL_RENDERER']."?instanceid".$ab['FORM_INCE']."&culture=".$aLangues[0]['langue_code']."-".$aFormInfo['PAYS_CODE'];
	    			$strBody .= "<tr style='border: 1px solid black;'>
	    							<td style='border: 1px solid black;'>".$ab['FORM_INCE']."</td>
	    							<td style='border: 1px solid black;'>".$ab['FORM_NAME']."</td>
	    							<td style='border: 1px solid black;'><a href='$url'>".$url."</a></td>
	    						</tr>";
	    			
	    			
	    		}
	    		
	    		$strBody.="</table>";
	    		
	    		$oMail = new Zend_Mail('UTF-8');
	    		
	    		$oMail->setBodyHtml($strBody)
	    		->setFrom($_SESSION[APP]['user']['email']);
	    		
	    		$tbl_mail = Pelican::$config['BOFORMS_FORM_CENTRAL_VALIDATION_EMAIL'];
	    		for ($i = 0; $i < count($tbl_mail); $i++) {
	    			$oMail->addTo($tbl_mail[$i]);
	    		}
	    		
	    		$oMail->setSubject("Demande de soumission de variantes A/B Testing")
	    		->send();
	    		
	    		echo "mail envoyé";
	    		
    		}
    		
    	}
    	
    }
	
    function getLogJSON()
    {
    	if(empty($this->collLog))
    	{
    		return false;
    	}else{
    		return json_encode($this->collLog);
    	}
    
    }
    
    function changeStatusFormsGetData($bMultilangue) {
    	if ($bMultilangue) {
    		
    	} else {
    		
    	}
    	
    	return ;
    }
    
    function ChangeStatusFormsAction()
    {
    	if(empty($_POST['listForms']) || !is_array($_POST['listForms']))
    		return false;
    	 
    	$editable = $_POST['editable'];
    	$comment = $_POST['comment'];
    	$listForms = $_POST['listForms'];
    	$sCultures = $_POST['cultures'];
    	
    	$oConnection = Pelican_Db::getInstance ();
    	    	    	 
    	$aBind=array();
    	$aBind[':CODE_PAYS'] = $oConnection->strToBind(FunctionsUtils::getCodePays());
    	$aBind[':BRAND'] = $oConnection->strToBind(Pelican::$config['BOFORMS_BRAND_ID']);
    	$aBind[':EDITABLE'] =  (int)$editable;
    	$aBind[':COMMENT'] =  $oConnection->strToBind($comment);
    	 
    	//$aBind[':LIST'] = $oConnection->strToBind($sListInstance);
    	 
    	/*$sql = "UPDATE #pref#_boforms_formulaire SET FORM_EDITABLE=1 WHERE PAYS_CODE=:CODE_PAYS AND FORM_BRAND=:BRAND AND (FORM_INCE IN ('$sListInstance') OR FORM_PARENT_INCE IN ('$sListInstance'))";
    	 $oConnection->query($sql,$aBind);*/
    	 
    	$sListInstance = implode("','", $listForms);
    	
    	// on doit avoir une culture
    	if (trim($sCultures) == '') {
    	    $aFail[] = 'ERROR NO CULTURE FOUND';
    	} else {	
	    	$tblCultures = explode(',', $sCultures);
	    		
	    	// on calcule les codes standard a partir des generiques et des cultures choisies
	    	$sStandardCodes = '';
	    	for ($ii = 0; $ii < count($listForms); $ii++) {
	    		$code_generique = $listForms[$ii];
	    		for ($ij = 0; $ij < count($tblCultures); $ij++) {
	    			$sCulture = str_pad($tblCultures[$ij], 2, '0', STR_PAD_LEFT);	
	    				
		    		$code_standard =  substr_replace ( $code_generique , "0" , 5 , 1 );
		    		$code_standard =  substr_replace ( $code_standard , $sCulture , 10 , 2 );
		    			
		    		if ($sStandardCodes == '') {
		    			$sStandardCodes = $code_standard;
		    		} else {
		    			$sStandardCodes = $sStandardCodes . "','" . $code_standard;
		    		}
		    	}
	    	}    		
	    	
	    	// ajout des generiques a traiter	
	    	$sGeneriqueCodes = "";    	
	    	for ($ii = 0; $ii < count($listForms); $ii++) {
	    		 $code_generique = $listForms[$ii];
				 $sqlCountGeneriqueFils = "select count(*) as nb from #pref#_boforms_formulaire where FORM_PARENT_INCE = :FORM_INCE";
	    		 $aBindGenerique[':FORM_INCE'] = $oConnection->strToBind($code_generique);
				 $nbFils = $oConnection->queryItem($sqlCountGeneriqueFils, $aBindGenerique);
	    		 
				 // on permet de desactiver le generique que si 0 formulaires fils trouves
				 if ($nbFils == 0 || ((int)$editable == 1)) {
	    		 	if ($sGeneriqueCodes == '') {
		    			$sGeneriqueCodes = $code_generique;
		    		} else {
		    			$sGeneriqueCodes = $sGeneriqueCodes . "','" . $code_generique;
		    		}
	    		 }
	    	}
	    	
	    	// gestion des personnalises	    	
	    	$sql = "SELECT distinct f.FORM_INCE,FORM_NAME,FORM_INSTANCE_NAME,FORM_ID,FORM_TYPE, FORM_XML_CONTENT,FORM_GENERIC
	    	FROM #pref#_boforms_formulaire f
	    	INNER JOIN #pref#_boforms_formulaire_version fv ON f.FORM_INCE=fv.FORM_INCE 
	    	WHERE PAYS_CODE=:CODE_PAYS AND FORM_BRAND=:BRAND AND ( f.FORM_INCE IN ('$sStandardCodes') and f.FORM_PARENT_INCE in ('$sListInstance') ) ";   	
	    	$aTab=$oConnection->queryTab($sql,$aBind);
	    	$aFail = array();
	    	$aSuccess = array();
	    	
	    	foreach($aTab as $row)
	    	{
	    		$bupdate = $this->udpdateWS($row['FORM_INCE'],$row['FORM_INSTANCE_NAME'],$row['FORM_ID'],$row['FORM_NAME'],$row['FORM_TYPE'],$row['FORM_XML_CONTENT'],(int)$editable,$comment,false);
		    		
	    		if($bupdate == true) {
			    	$aBind[':CODE'] = $oConnection->strToBind($row['FORM_INCE']);    			
		    		$sql = "UPDATE #pref#_boforms_formulaire SET FORM_EDITABLE=:EDITABLE,FORM_COMMENTARY =:COMMENT  WHERE PAYS_CODE=:CODE_PAYS AND FORM_BRAND=:BRAND AND FORM_INCE = :CODE";
			    			
			    	$oConnection->query($sql,$aBind);
			    	$aSuccess[] = $row['FORM_INCE'];
			    }else{
			    	$aFail[] = $row['FORM_INCE'];
			    }	    		
	    	}
	    	
    		// gestion des generiques    	
	    	$sql = "SELECT distinct f.FORM_INCE,FORM_NAME,FORM_INSTANCE_NAME,FORM_ID,FORM_TYPE, FORM_XML_CONTENT,FORM_GENERIC
	    	FROM #pref#_boforms_formulaire f
	    	INNER JOIN #pref#_boforms_formulaire_version fv ON f.FORM_INCE=fv.FORM_INCE
	    	WHERE PAYS_CODE=:CODE_PAYS AND FORM_BRAND=:BRAND AND ( f.FORM_INCE IN ('$sGeneriqueCodes') and f.FORM_PARENT_INCE is null ) ";   	
	    	$aTab=$oConnection->queryTab($sql,$aBind);
	    		    	
	    	foreach($aTab as $row)
	    	{
	    		$bupdate = $this->udpdateWS($row['FORM_INCE'],$row['FORM_INSTANCE_NAME'],$row['FORM_ID'],$row['FORM_NAME'],$row['FORM_TYPE'],$row['FORM_XML_CONTENT'],(int)$editable,$comment,false);
		    		
	    		if($bupdate == true) {
			    	$aBind[':CODE'] = $oConnection->strToBind($row['FORM_INCE']);    			
		    		$sql = "UPDATE #pref#_boforms_formulaire SET FORM_EDITABLE=:EDITABLE,FORM_COMMENTARY =:COMMENT  WHERE PAYS_CODE=:CODE_PAYS AND FORM_BRAND=:BRAND AND FORM_INCE = :CODE";
			    			
			    	$oConnection->query($sql,$aBind);
			    	$aSuccess[] = $row['FORM_INCE'];
			    }else{
			    	$aFail[] = $row['FORM_INCE'];
			    }	    		
	    	}
    	}
    	// affichage du resultat
    	
    	if((int)$editable == 1)
    	{
    		$msg_confirm = t('BOFORMS_CHANGESTATUS_CONFIRM_ENABLE');
    	}else{
    		$msg_confirm = t('BOFORMS_CHANGESTATUS_CONFIRM_DISABLED');
    	}
    	
    	if(!empty($aFail)){
    		$sFail = implode(', ', $aFail);

    		if(empty($aSuccess))
    		{
    			echo t('BOFORMS_SAVE_FAILED');
    		}else{
    			$sSuccess = implode(', ', $aSuccess);
    			echo $msg_confirm . ' ('.$sSuccess.')<br /><br />';
    		    echo t('BOFORMS_SAVE_FAILED') . ' ('. $sFail .')';
    			
    		}
    	}else{
    		echo $msg_confirm;
    	}
    }
}
