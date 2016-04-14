<?php

//include(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/XMLHandle.class.php');


/*** WebServices***/
/*include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/services.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Configuration.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstancesRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstancesResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstanceByIdRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/GetInstanceByIdResponse.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/UpdateInstanceRequest.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/Service/BOForms/Model/UpdateInstanceResponse.php');
include (Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/BOForms.admin.ini.php');*/


// fonction utilisée pour trier les tableaux de fieldsets
function usort_fieldset_order($a, $b) {
	if ($a['order_tmp'] > $b['order_tmp']) {
		return 1;
	} else {
		return -1;
	}
}

// fonction utilisée pour trier les questions
function usort_question_order($a, $b) {
	if ($a['order_tmp'] > $b['order_tmp']) {
		return 1;
	} else {
		return -1;
	}
}

class FormInstance {

	public $sCode;
	private $sType;
	private $iNbSteps;
	private $abTesting;

	private $aTab;
	private $aGeneric;
	
	public $aFieldsGeneric = array();
	public $aFieldsStandard = array();
	
	private $sXmlStandard;
	private $sXmlGeneric;
	
	public $oXMLStandard;
	public $oXMLGeneric;
	
	public $hasDraft = false;
	public $hasPublish = false;
	public $hasN1 = false;
	
	public $state_id;
	public $form_version;
	public $form_current_version;
	public $form_draft_version;

	//jira203
	public $date_version;
	
	
	public function __construct ($sCode,$abTesting=false)
	{
	    
		$this->sCode = $sCode;
		$this->abTesting = $abTesting;
		
		if((int)$this->abTesting>0){
			$this->sCode = substr_replace($this->sCode,(int)$this->abTesting,8,1);			
		}
		
		$this->sType = substr($sCode, 9,1);
			
	}

	private function cleanString($str) {
		// trim and special chars replacing
		$result = str_replace("\r", " ", str_replace("\t", " ", str_replace("\n", " ", trim($str))));
		
		// supprime les espaces en doubles
		while (strpos($result, "  ") !== false) {
			$result = str_replace("  ", " ", $result); 
		}
		
		$result = strip_tags($result);
		
		return $result;
	}
	
	public function setXmlStandard($sXml, $type)
	{
				
	    $this->sXmlStandard = $sXml;

	    $this->oXMLStandard = new XMLHandle($this->sXmlStandard, $type);
	    //$this->oXMLStandard->loadGenericXML();
	    $this->oXMLStandard->Parser_read();

	    $this->aTab = $this->format($this->oXMLStandard->aField, false, $this->oXMLStandard->aPages, $this->oXMLStandard->aCompoAv,$this->oXMLStandard->aGlobalStructure,$this->oXMLStandard->aPageStructure);
	  
	    return $this->oXMLStandard;

	}	
	public function setXmlGeneric()
	{
	    $this->aGeneric = $this->format($this->oXMLGeneric->aField, true, $this->oXMLGeneric->aPages, $this->oXMLGeneric->aCompoAv, $this->oXMLGeneric->aGlobalStructure);
	}
	
	public function getTab()
	{
	    return $this->aTab;
	}
	
	public function getGeneric()
	{
	    return $this->aGeneric;
	}
	

	public function format($aXml, $bIsGeneric, $aSteps, $aCompoAv,$aGlobalStructure,$aPageStructure=false)
	{
		$aTab = array();

		if(is_array($aGlobalStructure) && !empty($aGlobalStructure))
	    {
            foreach ($aGlobalStructure as $kStruct=>$Struct)
            {
                switch ($Struct['niveau'])
                {   
                	case 'fieldSet':
                    	$aTab[$Struct['num_page']]['fieldSets'][$kStruct]=array();
                    	$aTab[$Struct['num_page']]['fieldSets'][$kStruct]['order_tmp'] = $Struct['order_tmp'];
                    break;      
                    case 'question':
                        $aTab[$Struct['num_page']]['fieldSets'][$Struct['fieldSet']]['questions'][$kStruct]['lines']=array();
                        $aTab[$Struct['num_page']]['fieldSets'][$Struct['fieldSet']]['questions'][$kStruct]['order_tmp']= $Struct['order_tmp'];
                    break;
                    case 'line':
                        $aTab[$Struct['num_page']]['fieldSets'][$Struct['fieldSet']]['questions'][$Struct['question']]['lines'][$Struct['line']]['field']=array();
                    break;        
                }                    
            }    
	    }
	    
	    if(!$bIsGeneric)
		{
			//patch structure, lorsque le générique possède plus de composant que le standard à la base
			
			if(!empty($this->oXMLGeneric->aGlobalStructure))
			{
				foreach($this->oXMLGeneric->aGlobalStructure as $kgen=>$StructGen)
				{					
					if(!array_key_exists($kgen,$aGlobalStructure))
					{
						$aTab[$StructGen['num_page']]['fieldSets'][$StructGen['fieldSet']]['questions'][$StructGen['question']]['lines'][$StructGen['line']]['field']=array();
							
					}
				}
			}
		}
		
        if(!is_array($aXml) || empty($aXml))
        {
            Throw new Exception("aXml n'est pas un tableau ou est vide - function format");
        }
	    

	    foreach ($aXml as $iField=>$field)
        {
           $aTab[$field['num_page']]['fieldSets'][$field['fieldSet']]['questions'][$field['question']]['lines'][$field['line']]['field'][$iField] =  $field;
        }
	     

	    if(!is_array($aTab) || empty($aTab))
	    {
	        Throw new Exception("aTab n'est pas un tableau ou est vide - function format");
	    }
	   
	    $aReturn = array();
	   
        foreach($aTab as $kTab=>$tab)
        {
           
        	
            if(!is_array($tab['fieldSets']) || empty($tab['fieldSets']))
            {
                Throw new Exception("tab['fieldSets'] n'est pas un tableau ou est vide - function format");
            }
            
            $aFieldsets = array();
            foreach($tab['fieldSets'] as $kFieldset=>$fieldset)
            {
                $k = 0;

                if(!is_array($fieldset['questions']) || empty($fieldset['questions']))
                {
                    Throw new Exception("fieldset['questions'] n'est pas un tableau ou est vide - function format");
                }
                
                $aQuestions = array();
                foreach($fieldset['questions'] as $kQuestion=>$question)
                {
                    
                    
                    /*if(!is_array($question['lines']) || empty($question['lines']))
                    {
                        Throw new Exception("question['lines'] n'est pas un tableau ou est vide - function format");
                    }*/
                   
                    $aLines = array();
                    if(!empty($question['lines']) && is_array($question['lines']))
                    {
	                    foreach($question['lines'] as $kLine=>$line)
	                    {
	                    	
	                        if($bIsGeneric)
	                        {
	                        	
	                            $aLines[] = array('name'=>$kLine, 'hasMoved'=>false, 'aFieldsGeneric'=>$this->getFieldInfos($line, true), 'aFieldsStandard' => array());
	
	                        } else {
	                            
	                            $aLines[] = array('name'=>$kLine, 'hasMoved'=>false, 'aFieldsGeneric'=>array(), 'aFieldsStandard' => $this->getFieldInfos($line, false));
	                            
	                        }
	                        
	                        
	                        // manage alternative fields (for phone number)
	                        $firstAlternativeSeen = false;
	                        for ($ll = 0; $ll < count($aLines); $ll++) {
	                        	for ($lll = 0; $lll < count($aLines[$ll]['aFieldsStandard']); $lll++) {
		                        	if ($aLines[$ll]['aFieldsStandard'][$lll]['isAlternativ'] == 1) {
		                        		if ($firstAlternativeSeen == false) {
		                        			$firstAlternativeSeen = true;
		                        			
		                        			// hides other alternative fields for the current question
		                        			$aLines[$ll]['aFieldsStandard'][$lll]['showAlternative'] = 1;
		                        		} else {
		                        			$aLines[$ll]['aFieldsStandard'][$lll]['showAlternative'] = 0;
		                        		}
		                        	}
		                    	}
	                        }
	                        
	                        
	                        // manage alternative fields (for phone number)
	                        $firstAlternativeSeen = false;
	                        for ($ll = 0; $ll < count($aLines); $ll++) {
	                        	for ($lll = 0; $lll < count($aLines[$ll]['aFieldsGeneric']); $lll++) {
		                        	if ($aLines[$ll]['aFieldsGeneric'][$lll]['isAlternativ'] == 1) {
		                        		if ($firstAlternativeSeen == false) {
		                        			$firstAlternativeSeen = true;
		                        			
		                        			// hides other alternative fields for the current question
		                        			$aLines[$ll]['aFieldsGeneric'][$lll]['showAlternative'] = 1;
		                        		} else {
		                        			$aLines[$ll]['aFieldsGeneric'][$lll]['showAlternative'] = 0;
		                        		}
		                        	}
	                        	}
	                        }
	                        
	                        // ******************************************* //
	                        
	                    }
                    }
                    $aQuestions[] = array('name'=>$kQuestion, 'hasMoved'=>false, 'template'=> $aCompoAv[$kQuestion]['template'], 'lines'=>$aLines, 'order_tmp' => $question['order_tmp']);
                }
                
                $k++;

                usort($aQuestions, 'usort_question_order');
                $aFieldsets[] = array('name'=>$kFieldset, 'hasMoved'=>false, 'classid'=> $kFieldset.'_fieldset', 'questions'=>$aQuestions, 'order_tmp' => $fieldset['order_tmp']);
            }
            
            usort($aFieldsets, 'usort_fieldset_order');

            $aReturn[$kTab] = array('id'=>$kTab, 'itkg_code'=>$aSteps[$kTab]['itkg_code'], 'hasMoved'=>false, 'title'=>$aSteps[$kTab]['title'], 'fieldsets' => $aFieldsets);

            
            // si l'attribut visible vaut false alors on n'affiche pas next_label et previous_label
                        
            if (isset($aSteps[$kTab]['configuration']['next']['attributes']['label']) && 
            	($aSteps[$kTab]['configuration']['next']['attributes']['visible'] == 'true')) {
            		$aReturn[$kTab]['configuration']['next_label'] = $aSteps[$kTab]['configuration']['next']['attributes']['label']; 
            } 
        	if (isset($aSteps[$kTab]['configuration']['previous']['attributes']['label']) && 
        		($aSteps[$kTab]['configuration']['previous']['attributes']['visible'] == 'true')) {
        			$aReturn[$kTab]['configuration']['previous_label'] = $aSteps[$kTab]['configuration']['previous']['attributes']['label']; 
            }
            
            if (isset($aSteps[$kTab]['gtm_tags_under_question'])) {
            	$aReturn[$kTab]['gtm_tags_under_question'] = $aSteps[$kTab]['gtm_tags_under_question'];
            } else {
            	$aReturn[$kTab]['gtm_tags_under_question'] = array();
            }

        }
	   return $aReturn;
	    
	}
		
	private function getFieldInfos($line, $isGeneric)
	{
		$aFields = array();
	    $aNames = array();
	    $aChoices = array();
	    $aRules = array();
	    $aTitles = array();
	    $i=0;
	    $bIsDisplayed = 0;
	    $bIsEnabled = true;
	    $bRequiredCentral = true;
	    $bChangeStep = true;
	    $bPreventSort = false;
	    $classlistener = "";
	    $isAlternativ = 0;   
	    
	    foreach($line['field'] as $field)
	    {
	    	if ($field['type'] == 'html') {
	    		$sCode = $field['html']['attributes']['itkg_code'];
	    		$sTitle = $field['html']['value'];
			} else if ($field['type'] == 'toggle') {
				$sCode = $field['toggle']['attributes']['itkg_code'];
				$sTitle = $field['toggle']['title']['value'];
				$content = $field['toggle']['content']['value'];
	    	} else if ($field['type'] == 'connector') {
	    		$sCode = $field['connector']['attributes']['itkg_code'];
	    		$instructions = $field['connector']['help']['value'];
	    		$button_name = '';
	    		for ($cpt = 0; $cpt < count($field['connector']['requestParameter']['mapping']); $cpt++) {
	    			if ($field['connector']['requestParameter']['mapping'][$cpt]['attributes']['code'] == 'text') {
	    				$button_name = $field['connector']['requestParameter']['mapping'][$cpt]['attributes']['key'];
	    				break;
	    			}
	    		}
	    		
	    		if (isset($field['connector']['requestParameter']['gtm']['tag']['label']['value'])) {
	    			$label_tag_gtm = $field['connector']['requestParameter']['gtm']['tag']['label']['value'];	
	    		}
	    		
	    		//$sTitle = 'TODO test title';
	    	} else {
	    		// field
	    		$sCode = $field['field']['attributes']['code'];
	    		$sTitle = $field['field']['label']['value'];
	    		$instructions = $field['field']['help']['value'];		
	    	}
	    	
	    	$sAlign = (isset($field['field']['label']['attributes']['align']))?$field['field']['label']['attributes']['align']:'left';
	        $sType = $field['type'];
	        	        
	        if($sTitle){
		        if(strlen($sTitle) > 200)
		        {
		            $sTitleStripped = substr(strip_tags(trim($sTitle)), 0, 200)."...";
		        } else {
		            $sTitleStripped = strip_tags(trim($sTitle));
		        }
		        $sTitleStripped = strip_tags(html_entity_decode($sTitleStripped, ENT_QUOTES, "UTF-8"));
		        $sTitleStripped  = str_replace('&apos;', "'", $sTitleStripped );
		        $sTitleStripped  = str_replace('&amp;', "&", $sTitleStripped );
	        }elseif($sType != 'hidden'){
	        	//si pas de titre on met le code en titre de boutton
	        	$sTitleStripped=$sCode;
	        }
	        
	        $sTitleStripped = $this->cleanString($sTitleStripped);
	        
	        $aTitles = array(   0 => array('title'=>$sTitle, 'titleStripTagged'=> $sTitleStripped, 'id'=>10, 'isDefaultLanguage'=>true),
	            1 => array('title'=>$sTitle." [ENG-GB]", 'titleStripTagged'=> $sTitleStripped, 'id'=>7, 'isDefaultLanguage'=>false)
	        );
	        
	       /* $aTitles['title'] = $sTitle;
	        $aTitles['titleStripTagged'] = $sTitleStripped;
	        $aTitles['isDefaultLanguage'] = true;
	        */
	         
	        if(isset($field['field']['rule']))
	        {
	            $aRules = array(
	                'pattern'=>$field['field']['rule']['pattern']['value'],
	                'errorMessage' => $field['field']['rule']['errorMessage']['value']);
	        } else {
	            $aRules = array();
	        }
	         
	        $aChoices = $this->getChoicesField($field['field'][$field['type']]['referential']['item']);
	        
	        
	        // Pour n'avoir qu'un seul field affiché lorsqu'il est alternative
	       
	        if(($field['template']))
	        {
	        	
	            if($i==0){
	                $bIsDisplayed = 1;
	            } else {
	                $bIsDisplayed = 0;
	            }
	            $isAlternativ=1;
	        	if(strpos($field['field']['attributes']['code'],'PHONE_')===false)
	        	{	
	        		
	        		$bIsDisplayed = 0;
	        		$isAlternativ=0;
	        		
	        	}	
	        		
	            $i++;
	        }else{
	        	$isAlternativ=0;
	        }
	       
	        $bHtmlLock=false;
	        if($sType == 'html')
	        {
	        	$bRequiredCentral = true;
	        	$bChangeStep = false;
	        	$bIsEnabled = false;
	        	
	        	if(preg_match("/^html_[0-9]/i",$sCode))//champs <html> ayanty un code itkg du type "html_3" sont non modifiable
	        	{
	        		$bHtmlLock=true;
	        		$bRequiredCentral = false;
	        	}
	        
	        		
	        }elseif($sType == 'toggle') {
				$bRequiredCentral = true;
				$bChangeStep = false;
				$bIsEnabled = false;
			}elseif($sType == 'hidden')
	        {
	            $bRequiredCentral = false;
	            $bChangeStep = false;
	            $bIsEnabled = false;
	            
	        } else {
	        	
	        	
	        	
	            if($field['field']['attributes']['required_central'] == 'true')
	            {
	                $bRequiredCentral = false;
	            }
	            else 
	            {
	                $bRequiredCentral = true;
	            }
	            
	            
	            if(strpos($field['field']['attributes']['code'],'PHONE_')){
	            	//$bRequiredCentral = true;
	            	//var_dump($bRequiredCentral);
	            	
	            }
	            
	            if($field['field']['attributes']['change_etape'] == 'true')
	                $bChangeStep = true;
	            else
	                $bChangeStep = false;
	        }
	        	        
	      
	        if(in_array($sCode, $this->oXMLStandard->aListened) || in_array($sCode, $this->oXMLStandard->aListening))
	        {
	        	$classlistener = 'listener';
	        }else{
	        	$classlistener = "";
	        }
	        
	        $bListened=false;
	        if($field['listened']===true)
	        {
	        	$bListened = true;
	        }
	       
	        // Le prevent va bloquer la fonctionnalitÃ© de sortable pour le champ voulu
	        // exemple sur le champ USR_PHONE_HOME
	        /*if($sCode == 'USR_CIVILITY') $bPreventSort = true;
	        else  $bPreventSort = false;*/
	        
	        $tmp_tbl_field_info = array(
	            'type'=> $sType,
	            'isEnabled'=>($isGeneric)?false:true,
	            'title'=> $aTitles,
	        	'titre'=> $sTitle,
	            'TitleStripped'=> $sTitleStripped,
	            'instructions'=> $instructions,
	            'regexp'=> $aRules['pattern'],
	            'regexp_msg'=> $aRules['errorMessage'],
	            'is_required'=>($field['field']['required'] != '')?true:false,
	            'required_msg'=>$field['field']['required']['errorMessage']['value'],
	            'name'=> $sCode,
	            'choices' => $aChoices,
	            'hasMoved' => false,
	            'required_central' => $bRequiredCentral,
	            'change_etape' => $bChangeStep,
	            'default_value'=> $field['field'][$sType]['defaultValue']['value'],
	            'isAlternativ'=> $isAlternativ,
	            'isDisplayed' => $bIsDisplayed,
	        	'preventSort' => $bPreventSort,
	        	'datePicker' => $this->formatDatePickerDatas($field, $sType),
	        	'listener' => $classlistener,
	        	'align' => $sAlign,
	        	'bHtmlLock' => $bHtmlLock,
	        	'bListened' => $bListened,
	        	'content' => $content
	        );
	        
	        // JIRA 794
	        if ($sCode == 'TECHNICAL_SEND_REQUEST') {
				$tmp_tbl_field_info['pageErrorLabel'] = $this->oXMLStandard->getPageErrorTag(FunctionsUtils::isLandingPageSite((int)$this->oXMLStandard->instance['site_id']));
	        }        
	        
		    // PATCH TEMPORAIRE JIRA 710
	        if ($isGeneric == false && ($sType == 'dropdown' || $sType == 'radio')) {
	        	
	        	$itkg_code = $field['field']['attributes']['itkg_code'];
	        	$choicesFromGeneric = $this->oXMLGeneric->getReferentialChoiceValuesForItkgCode($itkg_code, $sType);
	        	
	        	// overrides defaultChoices with choices from Generic
	        	if (count($choicesFromGeneric) > 0) {
	        		$tblRef = array();
	        		$tblListeChoiceRadio = array();
	        		for ($cptChoice = 0; $cptChoice < count($choicesFromGeneric); $cptChoice++) {
	        			if (isset($tblRef[$choicesFromGeneric[$cptChoice]['id']])) {
	        				// on récupère la position pour cette clé
	        				$indice = $tblRef[$choicesFromGeneric[$cptChoice]['id']];
	        			} else {
	        				// on stocke les positions pour chaque clé rencontrée
	        				$indice = count($tblRef);
	        				$tblRef[$choicesFromGeneric[$cptChoice]['id']] = $indice;	        				 
	        			}
	        			
	        			// checks the actual label for this item id in the standard
	        			$infoNode = $this->oXMLStandard->getInfosFromReferentialFromId($itkg_code, $sType, $choicesFromGeneric[$cptChoice]['id']);
	        			$choicesFromGeneric[$cptChoice]['selected'] = 'false';
	        			$choicesFromGeneric[$cptChoice]['actual'] = 'false';

	        			if ($choicesFromGeneric[$cptChoice]['choiceLabel'] == $infoNode['label']) {
	        				$choicesFromGeneric[$cptChoice]['actual'] = 'true';
	        				if ($infoNode['selected'] == 'true') {
	        					$choicesFromGeneric[$cptChoice]['selected'] = 'true';
	        				}
	        			}
	        			
	        			$tblListeChoiceRadio[$indice][] = $choicesFromGeneric[$cptChoice];
	        		}
					$tmp_tbl_field_info['choices_radios'] = $tblListeChoiceRadio;
	        	}
	        }
	        // FIN PATCH TEMPORAIRE
	        
	        if ($field['type'] == 'textbox') {
	        	$tmp_tbl_field_info['inputmask'] = (isset($field['field']['textbox']['inputmask']['value'])) ? $field['field']['textbox']['inputmask']['value'] : '';   	
	        }
	        
	        if ($field['type'] == 'connector') {
	        	$tmp_tbl_field_info['button_name'] = $button_name;
	        	$tmp_tbl_field_info['label_tag_gtm'] = $label_tag_gtm;
	        	
		        if (in_array($_SESSION[APP]['backoffice']['USER_LOGIN'], Pelican::$config['BOFORMS_USER_SUPER_ADMIN'])) {
		        		$tmp_tbl_field_info['display'] = '1';
		        } else {
		        		$tmp_tbl_field_info['display'] = '0';
		        }
	        }

	        
	    
	        
	    	if ($field['field']['attributes']['itkg_code'] == 'USR_EMAIL') {
	    		for ($iil = 0; $iil < count($field['field']['listener']); $iil++) {
	    			if (isset($field['field']['listener'][$iil]['behavior']['attributes']['type']) && $field['field']['listener'][$iil]['behavior']['attributes']['type'] == 'showMessage') {
	    				// $field['field']['listener'][$iil]['behavior']['requestParameter']['parameter']['attributes']['itkg_code'];
	    				$tmp_tbl_field_info['email_param_message_value'] = $field['field']['listener'][$iil]['behavior']['requestParameter']['parameter']['value'];
	    			}
	    		}
	    	}
	        
	    	$aFields[] = $tmp_tbl_field_info;
	    	
	        $aNames[] = $sCode;
	        
	        if($isGeneric){
	               $this->aFieldsGeneric[] = $sCode;
	        } else {
	            
	            $this->aFieldsStandard[] = $sCode;
	        }
	        
	        	        
	    }

		//debug($aFields);
		
	    return $aFields;
	}
	
	private function formatDatePickerDatas($field, $sType) {
		if ($sType != 'datepicker') {
			return array();
		}
		
	    $libeletEnumeration = $this->formatTextEnumeration($field, 'libeletEnumeration');
        $dayEnumeration = $this->formatTextEnumeration($field, 'dayEnumeration');
        $monthEnumeration = $this->formatTextEnumeration($field, 'monthEnumeration');
        	
    		
	    $enumeration_day = $this->formatDateEnumeration($field, 'day');
	    $enumeration_date = $this->formatDateEnumeration($field, 'date');
	    $enumeration_weekday = $this->formatDateEnumeration($field, 'weekday');
	    $enumeration_period =  $this->formatDateEnumeration($field, 'period');
	    $enumeration_month = $this->formatDateEnumeration($field, 'month');
 		$enumeration_year = $this->formatDateEnumeration($field, 'year');
		$enumeration_recursive = $this->formatDateEnumeration($field, 'recursiveDay');

	    
       	$forbiddenDays = array('day' => $enumeration_day,
							   'date' => $enumeration_date,
  							   'weekday' => $enumeration_weekday,
       						   'period' => $enumeration_period,
       						   'month' => $enumeration_month,
       						   'year' => $enumeration_year,
       						   'recursiveDay' => $enumeration_recursive
       						   );
        
	    return array('dateStart'    =>  $field['field']['datepicker']['dateStart']['value'],
	        		  'dateEnd'      => $field['field']['datepicker']['dateEnd']['value'],
	        		  'openingStart' => $field['field']['datepicker']['openingStart']['value'],
	        	      'openingEnd'   => $field['field']['datepicker']['openingEnd']['value'],
	        		  'libeletEnumeration' => $libeletEnumeration,
	        		  'dayEnumeration' => $dayEnumeration,
	        		  'monthEnumeration' => $monthEnumeration,
	        		  'forbiddenDays' => $forbiddenDays,
        			  'hourlabel' => $field['field']['datepicker']['hourlabel']['value'],
        			  'format' => $field['field']['datepicker']['format']['attributes']['pattern'],
        			  'attributes' => $field['field']['datepicker']['attributes']['type']
		);
	}
	
	private function formatTextEnumeration($field, $name) {
		$enumeration = array();
      	$enumeration_items = array();
	    for ($iii = 0; $iii < count($field['field']['datepicker'][$name]['referential']['item']); $iii++) {
			$enumeration_items[] = array('id' => $field['field']['datepicker'][$name]['referential']['item'][$iii]['attributes']['id'], 
	      								 'value' => $field['field']['datepicker'][$name]['referential']['item'][$iii]['value']);
	        			
	    }
	    $enumeration = array('items' => $enumeration_items, 'id' => $field['field']['datepicker'][$name]['referential']['attributes']['id']);
	    return $enumeration;		
	}
	
	private function formatDateEnumeration($field, $name) {
		$enumeration = array();
        if (isset($field['field']['datepicker']['forbiddenDays'][$name]['value'])) {
       		$enumeration[] = $field['field']['datepicker']['forbiddenDays'][$name]['value'];	
	    } else {
	     	for ($iii = 0; $iii < count($field['field']['datepicker']['forbiddenDays'][$name]); $iii++) {
		    	$enumeration[] = $field['field']['datepicker']['forbiddenDays'][$name][$iii]['value'];	
		    }
	    }
	    return $enumeration;		
	}
	

	public function getFielsdEnabled()
	{
	    $aNotInStandard = array();
	    foreach($this->aFieldsGeneric as $generic)
	    {
	         
	        if(!in_array($generic, $this->aFieldsStandard))
	        {
	             
	            $aNotInStandard[] = $generic;
	        }
	         
	    }
	    foreach($this->aGeneric as $kstep=>$step)
	    {
	    	foreach($step['fieldsets'] as $kfieldset=>$fieldset)
	        {
	            foreach($fieldset['questions'] as $kquestion=>$question)
	            {
	                foreach($question['lines'] as $kline=>$line)
	                {
	                    foreach($line['aFieldsGeneric'] as $kfield=>$field)
	                    {
	                        foreach($aNotInStandard as $fieldEnabled)
	                        {
	                            if($fieldEnabled == $field['name'] && ( $field['type'] != 'hidden'))
	                            {	
	                            	$this->aGeneric[$kstep]['fieldsets'][$kfieldset]['questions'][$kquestion]['lines'][$kline]['aFieldsGeneric'][$kfield]['isEnabled'] = true;
	                            }
	                            
	                        }
	                    }
	                }
	            }
	        }
	    }
	    
	    return $this->aGeneric;
	}
	
	public function getCode()
	{	
		return $this->sCode;
	}
	public function getType()
	{
		return $this->sType;
	}
	public function getNbSteps()
	{
		return $this->iNbSteps;
	}
	
	public function getSteps()
	{
	    $aReturn = array();
	    $aReturn = array($this->getStepInfos(0), $this->getStepInfos(1), $this->getStepInfos(2));
	    return $aReturn;
	}
	
	private function getStepInfos($iStep)
	{
	    $aReturn = array();
	    $iNumEtape = $iStep+1;
	    $aReturn = array('id'=>($iStep == 0)?'0':$iStep, 'title'=> 'Etape nÃ‚Â°'.$iNumEtape);
	    return $aReturn;
	}
	

	
	private function getChoicesField($aItems)
	{
	    $aChoices = array();
	    
	    if(isset($aItems))
	    {
    	    $aChoicesTemp = $aItems;
    	    
    	    if(!is_array($aChoicesTemp) || count($aChoicesTemp) <=0)
    	    {
    	        Throw new Exception("Le tableau d'items est vide - function getChoicesField");
    	    }
    	    
    	    if($aChoicesTemp[0]['attributes'])
    	    {
    	        foreach($aChoicesTemp as $choice)
    	        {
    	           	$aChoices[] = array('choiceLabel' => $this->cleanString($choice['value']), 'choice'=> $choice['value'], 'id' => $choice['attributes']['id'], 'selected' => $choice['attributes']['selected']);
    	        }
    	    } else {
    	    	$aChoices[] = array('choiceLabel' => $this->cleanString($aChoicesTemp['value']), 'choice'=> $aChoicesTemp['value'], 'id' => $aChoicesTemp['attributes']['id'], 'selected' => $aChoicesTemp['attributes']['selected']);
    	    }
	    }
	    return $aChoices;
	}
	
	public function overwriteVersion($sVersion)
	{

		if($sVersion=="overwriteCURRENT")
		{
			$sSQL = "
			select fv.FORM_XML_CONTENT
			from #pref#_boforms_formulaire f
			INNER JOIN #pref#_boforms_formulaire_version fv ON (f.FORM_INCE = fv.FORM_INCE AND fv.FORM_VERSION = FORM_CURRENT_VERSION)
			where f.FORM_INCE = :FORM_INCE ";
			
		}elseif($sVersion=="overwriteN1")
		{
			$sSQL = "
			select fv.FORM_XML_CONTENT
			from #pref#_boforms_formulaire f
			INNER JOIN #pref#_boforms_formulaire_version fv ON (f.FORM_INCE = fv.FORM_INCE)
			where f.FORM_INCE = :FORM_INCE
			AND fv.FORM_VERSION!=FORM_CURRENT_VERSION
	    	AND STATE_ID = ".Pelican::$config['BOFORMS_STATE']['PUBLISH']."
	    	ORDER BY fv.FORM_VERSION DESC
	    	LIMIT 1";
		}
		
	    $oConnection = Pelican_Db::getInstance ();
	    
	    $aBind[':FORM_INCE'] = $oConnection->strToBind($this->sCode);
	    	    
	    $sXml = $oConnection->queryItem($sSQL,$aBind);
	    
	    if(!empty($sXml))
	    {
	    	//$aBind[':FORM_XML_CONTENT'] =  $oConnection->strToBind($sXml);
	    	
	    	$sSQL = "
	        UPDATE #pref#_boforms_formulaire_version fv
            SET FORM_XML_CONTENT = '".addslashes($sXml)."'
            WHERE FORM_VERSION = (select FORM_DRAFT_VERSION from #pref#_boforms_formulaire where FORM_INCE = :FORM_INCE) 
	    	AND FORM_INCE = :FORM_INCE";
	    	
	    	$oConnection->query($sSQL, $aBind);
	    }
	     	die();   
	    
	    return $sXml;
	}
	
	public function HasContextualises($version,$returnCodeInce=false)
	{
		$oConnection = Pelican_Db::getInstance ();
		
		$sCodeInstance1 = substr_replace($this->sCode, 1, 9, 1);
		$sCodeInstance2 = substr_replace($this->sCode, 2, 9, 1);
		
		$aBind[':FORM_INCE1'] = $oConnection->strToBind($sCodeInstance1);
		$aBind[':FORM_INCE2'] = $oConnection->strToBind($sCodeInstance2);
		
		$sql = "f.FORM_CURRENT_VERSION";

		if($version == "N1")
		{
			$sql = "(f.FORM_CURRENT_VERSION - 1)";
		}
		
		$sSQL = "
            select fv.FORM_INCE
            from #pref#_boforms_formulaire f
            INNER JOIN #pref#_boforms_formulaire_version fv ON (f.FORM_INCE = fv.FORM_INCE AND fv.FORM_VERSION = $sql)
            where f.FORM_INCE = :FORM_INCE1
			or f.FORM_INCE = :FORM_INCE2";
		$aRes = $oConnection->queryTab($sSQL, $aBind);
		
		if(!empty($aRes))
		{
			if($returnCodeInce)
			{
				return $aRes;
			}
			
			return true;
		}else{
			return false;
		}
		
		
	}
	
	public function getVersions()
	{
	  
	    $oConnection = Pelican_Db::getInstance ();
	    $aBind[':FORM_INCE'] = $oConnection->strToBind($this->sCode);
	   
	    $sSQL = "
            select fv.FORM_XML_CONTENT, fv.FORM_DATE, fv.FORM_VERSION
            from #pref#_boforms_formulaire f
            INNER JOIN #pref#_boforms_formulaire_version fv ON (f.FORM_INCE = fv.FORM_INCE AND fv.FORM_VERSION = f.FORM_DRAFT_VERSION)
            where f.FORM_INCE = :FORM_INCE
	    	AND STATE_ID = ".Pelican::$config['BOFORMS_STATE']['DRAFT'];
	
	    $aResult['draft'] = $oConnection->queryTab($sSQL, $aBind);
	    
	    if(!empty($aResult['draft']))
	    {
	    	$this->hasDraft=true;	
	    }
	    
	    
	    $sSQL = "
            select fv.FORM_XML_CONTENT, fv.FORM_DATE, fv.FORM_VERSION
            from #pref#_boforms_formulaire f
            INNER JOIN #pref#_boforms_formulaire_version fv ON (f.FORM_INCE = fv.FORM_INCE AND fv.FORM_VERSION = f.FORM_CURRENT_VERSION)
            where f.FORM_INCE = :FORM_INCE ";
	     
	    $aResult['current'] = $oConnection->queryTab($sSQL, $aBind);
	    
	    if(!empty($aResult['current']))
	    {
	    	$this->hasPublish=true;
	    }
	    
	    $sSQL = "
            select fv.FORM_XML_CONTENT, fv.FORM_DATE, fv.FORM_VERSION
            from #pref#_boforms_formulaire f
            INNER JOIN #pref#_boforms_formulaire_version fv ON (f.FORM_INCE = fv.FORM_INCE)
            where f.FORM_INCE = :FORM_INCE 
	    	AND fv.FORM_VERSION!=FORM_CURRENT_VERSION
	    	AND STATE_ID = ".Pelican::$config['BOFORMS_STATE']['PUBLISH']."
	    	ORDER BY fv.FORM_VERSION DESC
	    	LIMIT 1";
	     
	    $aResult['n-1'] = $oConnection->queryTab($sSQL, $aBind);
	    
	    if(!empty($aResult['n-1']))
	    {
	    	$this->hasN1=true;
	    }
	  
	    return $aResult;
	    
	}
	
	
	public function getABTesting($moreInfo=false)
	{
		
		$oConnection = Pelican_Db::getInstance ();
		$aBind[':FORM_INCE'] = $oConnection->strToBind($this->sCode);
		
		if($moreInfo)
		{
			$select = "fv.FORM_INCE,FORM_AB_TESTING,FORM_NAME,FORM_INSTANCE_NAME,FORM_ID,FORM_TYPE,FORM_VERSION,FORM_XML_CONTENT";
		}else{
			$select = "fv.FORM_INCE,FORM_AB_TESTING,FORM_NAME";
		}
		
		
		$sSQL = "
            Select $select
            from #pref#_boforms_formulaire f
            INNER JOIN #pref#_boforms_formulaire_version fv ON (f.FORM_INCE = fv.FORM_INCE AND fv.FORM_VERSION = f.FORM_CURRENT_VERSION)
            where f.FORM_PARENT_INCE = :FORM_INCE
			and FORM_AB_TESTING>0";
	
		$res = $oConnection->queryTab($sSQL, $aBind);
		
		return $res;
	}
	
	public function getCommentGroup()
	{
		
		$oConnection = Pelican_Db::getInstance ();
		$aBind[':FORMSITE_ID'] = (int)$this->oXMLStandard->instance['site_id'];
		
		$sSql = "Select GROUPE_TEXT 
				 from #pref#_boforms_groupe g
				 inner join #pref#_boforms_groupe_formulaire gf on gf.groupe_id = g.groupe_id
				 inner join #pref#_boforms_formulaire_site fs on fs.formsite_id = gf.formsite_id
				 where gf.FORMSITE_ID=:FORMSITE_ID and g.SITE_ID = " . $_SESSION[APP]['SITE_ID'];
		
		return $oConnection->queryItem($sSql, $aBind);
		
	}


	public function prepareGenericXML($get_code)
	{
		
		$oConnection = Pelican_Db::getInstance();
		/*** Verification du gÃ©nÃ©rique ***/
	
		//requete WS pour rÃ©cupÃ©rer le XML gÃ©nÃ©rique
			
		if(!Pelican::$config['BOUCHON_ON'])
		{
			$ws_xml = $this->getInstanceWS($get_code);
		}else{
			$ws_xml = Pelican::$config["PLUGIN_ROOT"] . '/boforms/public/XML/'.$get_code.'.xml';//TODO DEBOUCHE
		}
			
		if(empty($ws_xml))
		{
			die('No xml return');
		}
	
		if(!Pelican::$config['BOUCHON_ON'])
		{
			$oGenerique = new XMLHandle($ws_xml,'xml');
		}else{
			$oGenerique = new XMLHandle(Pelican::$config["PLUGIN_ROOT"] . '/boforms/public/XML/'.$get_code.'.xml');//TODO DEBOUCHE
		}
				
		$oGenerique->Parser_read();
		$WSxml = $oGenerique->dom->saveXML();
	
		
		//gÃ©nÃ©rique en BDD
		$aBind[':CODE_INSTANCE'] = $oConnection->strToBind($get_code);
		$sqlXMLGeneric = "select bfv.FORM_XML_CONTENT
    					   from #pref#_boforms_formulaire_version bfv
    					   INNER JOIN #pref#_boforms_formulaire bf ON (bf.FORM_INCE=bfv.FORM_INCE and bfv.FORM_VERSION=bf.FORM_CURRENT_VERSION )
    					   where bfv.FORM_INCE = :CODE_INSTANCE
    					   ";
		$BDDxml=$oConnection->queryItem($sqlXMLGeneric,$aBind);
		 
		 
		$sXMLGeneric=$BDDxml;
			
	
		if(empty($BDDxml))
		{
			//insertion du gÃ©nÃ©rique en BDD
	
			$aBind = array();
			$aBind[':FORM_INCE'] =$oConnection->strToBind($oGenerique->instance['id']) ;
			$aBind[':FORM_VERSION'] =1;
			$aBind[':FORM_CONTEXT'] = substr($get_code,9,1);
			//$aBind[':FORM_XML_CONTENT'] = $oConnection->strToBind($WSxml);
			$aBind[':FORM_DATE'] = $oConnection->strToBind(date("Y-m-d H:i:s"));
	
			$sql = "insert into #pref#_boforms_formulaire_version (FORM_INCE,FORM_VERSION,FORM_XML_CONTENT,FORM_DATE,FORM_LOG,USER_LOGIN,STATE_ID)
					  values (:FORM_INCE,:FORM_VERSION,'".addslashes($WSxml)."',:FORM_DATE,NULL,NULL,".Pelican::$config['BOFORMS_STATE']['PUBLISH'].")
					  ";
			$oConnection->query($sql,$aBind);
	
			$sqlUpdate = "update #pref#_boforms_formulaire
						  set FORM_CURRENT_VERSION =1
						  where FORM_INCE = :FORM_INCE
						  ";
			$oConnection->query($sqlUpdate,$aBind);
	
	
			$sXMLGeneric = $WSxml;
	
		}elseif ($BDDxml!=$WSxml)
		{
			
			//mise à jour du xml en BDD
			$aBind = array();
			$aBind[':FORM_INCE'] =$oConnection->strToBind($oGenerique->instance['id']);
			//$aBind[':FORM_XML_CONTENT'] = addslashes($WSxml);
			$aBind[':FORM_DATE'] = $oConnection->strToBind(date("Y-m-d H:i:s"));
			$aBind[':FORM_VERSION'] =1;
			$sqlUpdate = "update #pref#_boforms_formulaire_version
						  set FORM_XML_CONTENT ='". addslashes($WSxml)."',
						  	  FORM_DATE = :FORM_DATE
						  where FORM_INCE = :FORM_INCE
						  AND FORM_VERSION = :FORM_VERSION
						  ";
			$oConnection->query($sqlUpdate,$aBind);
			
			$sXMLGeneric = $WSxml;
		}
		/*** ***/
		
		$this->oXMLGeneric = $oGenerique;
		 
		return $sXMLGeneric;
	}
	
	public function getBDDXMLInstance($code_instance,$version = 'DRAFT',$infoVersion=false)
	{
		$oConnection = Pelican_Db::getInstance();
		$aBind[':CODE_INSTANCE'] = $oConnection->strToBind($code_instance);
		 		
		if($infoVersion){$select = ',FORM_VERSION,FORM_CURRENT_VERSION,FORM_DRAFT_VERSION,STATE_ID,FORM_DATE';}
		
		
		if($version == "N1")
		{
			$sqlXMLInstance="
			select fv.FORM_XML_CONTENT $select
			from #pref#_boforms_formulaire f
			INNER JOIN #pref#_boforms_formulaire_version fv ON (f.FORM_INCE = fv.FORM_INCE)
			where f.FORM_INCE = :CODE_INSTANCE
			AND fv.FORM_VERSION!=FORM_CURRENT_VERSION
			AND STATE_ID = ".Pelican::$config['BOFORMS_STATE']['PUBLISH']."
			ORDER BY fv.FORM_VERSION DESC
			LIMIT 1";
		}else{
			$sqlXMLInstance = "select bfv.FORM_XML_CONTENT $select
			from #pref#_boforms_formulaire_version bfv
			INNER JOIN #pref#_boforms_formulaire bf ON (bf.FORM_INCE=bfv.FORM_INCE and bfv.FORM_VERSION=bf.FORM_".$version."_VERSION )
			where bfv.FORM_INCE = :CODE_INSTANCE
    					   ";
		}
		
		
		if($infoVersion)
		{
			return $oConnection->queryRow($sqlXMLInstance,$aBind);
		}else{
			return $oConnection->queryItem($sqlXMLInstance,$aBind);
		}
	}
	
	// remplace le formId du xml en base par le bon formId  
	public function replaceFormId($codeInstance, $xml) {
		// chercher le form id 
		$oConnection = Pelican_Db::getInstance();
		$sql = "SELECT FORM_ID FROM #pref#_boforms_formulaire where FORM_INCE = :CODE_INSTANCE";
		$aBind[':CODE_INSTANCE'] = $oConnection->strToBind($codeInstance);
		$form_id_ok = $oConnection->queryItem($sql, $aBind);

		// charger le xml remplacer la valeur
		$dom = new DomDocument("1.0",'UTF-8');
		$dom->preserveWhiteSpace = false;
		
		if ($dom->loadXML($xml)) {
			$dom->getElementsByTagName("instance")->item(0)->getElementsByTagName("form")->item(0)->setAttribute('id', $form_id_ok);
			$xml = $dom->saveXML();
		}
		return $xml;
	}
	
	public function loadPersoXML($version = 'DRAFT')
	{
		$aInstanceInfo = $this->getBDDXMLInstance($this->sCode,$version,true);

		if($aInstanceInfo['FORM_VERSION'] >= 1 && $aInstanceInfo['FORM_VERSION'] == $aInstanceInfo['FORM_CURRENT_VERSION'])
		{
			//$version = 'CURRENT';
		}

		if ($version == 'DRAFT' || Pelican::$config['BOUCHON_ON']) {
			$xml = $aInstanceInfo["FORM_XML_CONTENT"];
		} else {
			// charger depuis le ws pour les versions publiées
			$xml = $this->getInstanceWS($this->sCode);
		}

		
		if (! empty($xml)) {
			$xml = $this->replaceFormId($this->sCode, $xml);
		}

		if(empty($xml))// si il n'y a pas d'instance liÃ© en BDD, on rÃ©cupÃ¨re le xml par WebService
		{
			//requÃ¨te WebService pour rÃ©cupÃ¨rer le xml de la langue courante

			if(!Pelican::$config['BOUCHON_ON'])
			{
				$xml=$this->getInstanceWS($this->sCode);//TODO DEBOUCHE
			}
			
			if(empty($xml))
			{	//si toujours rien, le gÃ©nÃ©rique est chargÃƒÂ© dans le personalisÃ©
				$xml = str_replace($this->oXMLGeneric->instance['id'],$this->sCode,$this->oXMLGeneric->dom->saveXML());
			}
		}
		
		if (is_array($aInstanceInfo) && count($aInstanceInfo)) {
			$this->state_id = $aInstanceInfo['STATE_ID'];
			$this->form_version = $aInstanceInfo['FORM_VERSION'];
			$this->form_current_version = $aInstanceInfo['FORM_CURRENT_VERSION'];
			$this->form_draft_version = $aInstanceInfo['FORM_DRAFT_VERSION'];
			$this->date_version = $aInstanceInfo['FORM_DATE'];
		}
		
				
		/*** New ABTesting ***/
		if($this->abTesting=='new')
		{//on prend le xml standard et on remplace le code instance par celui de l'ABtesting
			$sStandardcode = $this->sCode;
			
			$num_AB = $this->getNumABtesting();//numÃ©ro libre
			
			if(!$num_AB)
			{
				die("Nombre limite d'ABtesting Atteint");
			}
			
			$this->sCode = substr_replace($this->sCode,$num_AB,8,1);
			
			$xml = str_replace($sStandardcode,$this->sCode,$xml);
			
		}
			
		/****/
		
		$this->setXmlStandard($xml, 'xml');
		$this->setXmlGeneric();
						
	}
	
	/**** calcule le numero d'ABtesting disponible ****/
	public function getNumABtesting()
	{
		 
		$oConnection = Pelican_Db::getInstance();
		 
		 
		$aBind[':FORM_INCE'] =$oConnection->strToBind($this->sCode);
		$sSql = "SELECT FORM_INCE, FORM_AB_TESTING
    			 FROM #pref#_boforms_formulaire
    			 WHERE FORM_PARENT_INCE = :FORM_INCE
    			 AND FORM_AB_TESTING>0
    			 ORDER BY FORM_AB_TESTING";
		 
		$aAB=$oConnection->queryTab($sSql,$aBind);
		 
		 
		if(!empty($aAB))
		{
	
			$i=1;
			foreach ($aAB as $ab)
			{
				 
				if((int)$ab['FORM_AB_TESTING']!=$i)
				{
					return $i;
				}
				 
				if($i>8)
				{// max 9 abtesting
					return 0;
				}else{
					$i++;
				}
				 
			}
	
			return $i;
	
		}else{
			return 1;
		}
		 
		 
	}
	
	
	public function getCodeParent()
	{
		$oConnection = Pelican_Db::getInstance();
			
			
		$aBind[':FORM_INCE'] =$oConnection->strToBind($this->sCode);
		$sSql = "SELECT FORM_PARENT_INCE
    			 FROM #pref#_boforms_formulaire
    			 WHERE FORM_INCE = :FORM_INCE
    			 ";
			
		return $oConnection->queryItem($sSql,$aBind);
	}

	/**
	 * RécurpÃ¨re le xml d'une instance via le webService
	 * @param string $code_instance
	 *
	 */
	public function getInstanceWS($codeInstance)
	{
		try {
			$serviceParams = array(
					'instanceId' => $codeInstance
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

	public function getCulture()
	{
		
		$culture_id=substr($this->sCode,10,2);
		$Pays=substr($this->sCode,2,2);
		
		$oConnection = Pelican_Db::getInstance();
			
			
		$aBind[':CULTURE_ID'] =(int)$culture_id;
		$sSql = "SELECT CULTURE_KEY
    			 FROM #pref#_boforms_culture
    			 WHERE CULTURE_ID = :CULTURE_ID
    			 ";
			
		$culture_clef = $oConnection->queryItem($sSql,$aBind);
		
		return array('lang'=>$culture_clef,'pays'=>$Pays);
		
	}
	
	public function getContextLabel()
	{
		$oConnection = Pelican_Db::getInstance();
		$context=substr($this->sCode,9,1);
			
		//$aBind[':FORM_INCE'] = $oConnection->strToBind($this->sCode);
		$sSql = "SELECT CONTEXT_CLEF
    			 FROM #pref#_boforms_context
    			 WHERE CONTEXT_ID = $context
    			 ";
			
		$clef = $oConnection->queryItem($sSql,$aBind);
		
		return $clef;
	}
	
}

?>