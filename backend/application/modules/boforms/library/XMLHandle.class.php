<?php
include_once("config.php");
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/XMLParser.class.php'); 
//include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local.ini.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/library/PsaDomElement.php');



class XMLHandle 
{
	
	var $source;
	var $source_type;
	var $dom;
	var $domXpath;
	var $domTemp;
	var $domXpathTemp;
	var $XMLReader;
	var $aPages;
	var $aPageStructure;
	var $aField;
	var $instance;
	var $aGlobalStructure;
	var $aCompoAv;
	var $aListened;//liste des champ écouté
	var $aListening;// liste des champs qui écoute
	var $culture_id;
	var $structureTitleFieldSet;
	var $structureTitleFieldSetToSave;
	
	var $ListCompoAv = array("HTML_selectBrochurePicker","HTML_selectCarPicker","HTML_simpleBrochurePicker","HTML_simpleCarPicker","HTML_brochurePicker","HTML_dealerLocator"/*,"HTML_loginPopup"*/,"HTML_carPicker","HTML_dealerLocatorLight","HTML_dealerLocatorMedium"/*,"HTML_loginPopin"*/);
	
	var $oXMLGeneric;
		
	
	
	function __construct($source,$type='file')
	{
		
		// Instanciation d'un DOMDocument
		$this->source = $source;
		$this->source_type = $type;
		
		if($type=='file')
		{
			if( !file_exists($source) ) return false;
		}
		$this->dom = new DomDocument("1.0",'UTF-8');
		$this->dom->preserveWhiteSpace = true;
		$this->dom->registerNodeClass('DOMElement', 'PsaDomElement');
		
		
		// Charge du XML depuis un fichier
		if($type=='file')
		{
			$this->dom->load($source);
		}else{
			$source=str_replace('xmlns=', 'xmlns2=', $source);//patch bug avec le parcour du dom lorsque le xml contiens "xmlns=".
			$source = str_replace("&lt;", "<", $source);
			$source = str_replace("&gt;", ">", $source);
			$source = str_replace("&quot;", '"', $source);
			$this->dom->loadXML($source);
		}
		
		
		
		//$this->setAllId($this->dom);
		
		$this->domXpath = new DOMXPath($this->dom);
		
		$this->structureTitleFieldSet = false;
		$this->structureTitleFieldSetToSave = false;
		if(Pelican::$config['BOFORMS_BRAND_ID'] == 'AP')
		{
			//vérification de la structure du flux (title au niveau page ou au niveau fieldset ?)	
			$titles = $this->dom->getElementsByTagName('title');
			
			$bPageTitle = false;
			$bFieldsetTitle = false;
			
			foreach ($titles as $title) {
				if ($title->parentNode->tagName == "fieldSet") {
					$bFieldsetTitle = true;
				}
				if ($title->parentNode->tagName == "page") {
					$bPageTitle = true;
				}
			}
			
			if(($bPageTitle == false && $bFieldsetTitle == true))
			{
				$this->structureTitleFieldSet = true;
				
				$this->generateStructureTitle();
			}
			
			
			if(($bPageTitle == true && $bFieldsetTitle == true))
			{
				$this->structureTitleFieldSetToSave = true;
			}
		}
        //
		
		
		$elementid = $this->domXpath->query("//instance/attribute::id")->item(0)->value;
		$this->instance['id']=$elementid;
		
		$element = $this->domXpath->query("//instance/name")->item(0);
		$this->instance['name']=$element->nodeValue;
		
		$element = $this->domXpath->query("//instance/form/name")->item(0);
		$this->form['name']=$element->nodeValue;
		
		$element = $this->domXpath->query("//instance/form/commentary")->item(0);
		$this->form['commentary']=$element->nodeValue;
	}
	
	function generateStructureTitle()
	{
		//var_dump('generateStructuretitle');
		
		$fieldSets = $this->dom->getElementsByTagName('fieldSet');
		$elePage = $this->dom->getElementsByTagName('page')->item(0);
		
		$toRemove = array();
		$aCloneFieldset = array();
		
		// on clone tous les fieldset de la page
		$i = 0;
		foreach ($fieldSets as $fieldSet) {
			//var_dump($fieldSet->getAttribute('id'));
			//var_dump($this->domXpath->query("//fieldSet/title",$fieldSet)->item(0)->nodeValue);
			if ($fieldSet->hasChildNodes()) {
				foreach ($fieldSet->childNodes as $c) {
					if ($c->tagName == 'title')
					{
						$i ++;
						$aCloneFieldset[$i]['title'] = $c->cloneNode(true);
						
					}
				}
			}
			$aCloneFieldset[$i]['fieldSet'][] = $fieldSet->cloneNode(true);
			$toRemove[] = $fieldSet;
			//$fieldSet->parentNode->removeChild($fieldSet);
		
		}

		//on supprime tous les fieldset de la page
		foreach ($toRemove as $fieldSet) {
			$fieldSet->parentNode->removeChild($fieldSet);
		}
		
		// on clone l'element page qui nous servira de modèle pour recréér les pages
		$elePage->removeAttribute('itkg_code');
		for ($n=1;$n<=$i;$n++)
		{
			$aCloneFieldset[$n]['page'] = $elePage->cloneNode(true);
		}
		$elePage->parentNode->removeChild($elePage);
		
		
		//on créé autant de noeud page que de title trouvé dans les fieldset, puis on y importe les fieldsets
		if(!empty($aCloneFieldset))
		{
			$eleForm = $this->dom->getElementsByTagName('form')->item(0);
			foreach ($aCloneFieldset as $numPage => $clone)
			{
				
				
				foreach($clone['page']->childNodes as $child)
				{
					if ($child->tagName == 'order')
					{
						$child->nodeValue = $numPage;
					}
				}
				
				$clone['page']->psaInsertChild($clone['title']);
				foreach ($clone['fieldSet'] as $fieldSet)
				{
					$clone['page']->psaInsertChild($fieldSet);
				}
				
				$eleForm->appendChild($clone['page']);
			}
		}
		
		$this->domXpath = new DOMXPath($this->dom);
		
		//debug($this->dom->saveXML());
		
	}
	
	function revertStructureTitle($xml)
	{
		//var_dump('revertStructuretitle');
		$dom = new DOMDocument("1.0",'UTF-8');
		$dom->preserveWhiteSpace = true;
		$dom->registerNodeClass('DOMElement', 'PsaDomElement');
		$dom->loadXML($xml);
		$domXpath = new DOMXPath($dom);
		
		$elePage = $dom->getElementsByTagName('page');
		
		$titleNodes = $domXpath->query("//instance/form/page/fieldSet/title");
		
		if(!is_null($titleNodes))
		{
			foreach ($titleNodes as $titleNode)
			{
				$titleNode->parentNode->removeChild($titleNode);
			}
		}
		
		foreach ($elePage as $numpage => $page)
		{
			foreach($page->childNodes as $pChild)
			{
				if ($pChild->tagName == 'order')
				{
					$pChild->nodeValue = 1;
				}
				
				if ($pChild->tagName == 'title')
				{
					$cloneFields[$numpage]['title'] = $pChild->nodeValue;
					$toRemove[] = $pChild;
				}
				
				if ($pChild->tagName == 'fieldSet')
				{
					$cloneFields[$numpage]['fieldset'][] = $pChild;
					
					if($numpage == 0)
					{
						$toRemove[] = $pChild;
					}
				}
				
			}
			
			if($numpage > 0)
			{
				$toRemove[] = $page;
			}
		}
		
		//on supprime tous les fieldset de la page
		foreach ($toRemove as $rm) {
			$rm->parentNode->removeChild($rm);
		}
		
		$elePage = $dom->getElementsByTagName('page')->item(0);
		foreach ($cloneFields as $numpage => $fieldSet)
		{
			foreach ($fieldSet['fieldset'] as $k => $clone)
			{
				if($k==0)
				{
					$nodeTitle = $dom->createElement("title");
					$nodeTitle->appendChild($dom->createCDATASection($fieldSet['title']));
					$clone->psaInsertChild($nodeTitle);
				}
				$elePage->psaInsertChild($clone);
			}
			
		}

		return $dom->saveXML();

	}
	
	function ValidationXSD()
	{
		//debug($this->oXMLGeneric->dom->saveXML());		
		$schema = Pelican::$config['BOFORMS_FORM_XSD'];
		
		// Instanciation d’un DOMDocument
		$dom = new DOMDocument("1.0");
		
		// Charge du XML depuis un fichier
		if($this->structureTitleFieldSetToSave) 
		{
			$xml = $this->revertStructureTitle($this->dom->saveXML());
		}else{
			$xml = $this->dom->saveXML();
		}
				
		$xml = $this->cleanXML($xml);
		$dom->loadXML($xml);
		//debug($xml);
		
		// Validation du document XML	
		// Affichage du résultat
		
		libxml_use_internal_errors(true);
		if($dom->schemaValidate($schema))
		{
			
			return true;
		}else{
			
		    $errors = libxml_get_errors(); 
		
		    foreach ($errors as $error) {
		       $log = "[".date('Y-m-d H:i:s').'] '.$error->message; 
		       var_dump($log);//TO DO COMMENT
		       error_log($log, 3, FunctionsUtils::getLogPath() . 'validation_xsd.log');
		    }
		    libxml_clear_errors();
		}
			
		libxml_use_internal_errors(false);
   		 
	}
	
	public function cleanXML($sXml,$flag=false)
	{
		if($flag==true)
		{
			$sXml = str_replace('<?xml version="1.0" encoding="UTF-8"?>',"",$sXml);
		}
		$sXml = preg_replace("/itkg_code=\".*?\"/", "", $sXml);
		$sXml = str_replace("itkg_gtm_data=\"1\"", "", $sXml); // delete gtm tag markup
		$sXml = str_replace('xmlns2=', 'xmlns=', $sXml);
	
		return $sXml;
	}
	
	//Dom temporaire pour le traitement du changement de structure
	function setDomXPathTemp()
	{
		$source = str_replace('xmlns=', 'xmlns2=', $this->source);//patch bug avec le parcour du dom lorsque le xml contiens "xmlns=".
		$source = str_replace("&lt;", "<", $source);
		$source = str_replace("&gt;", ">", $source);
		$source = str_replace("&quot;", '"', $source);
		
		$this->domTemp =new DomDocument("1.0",'UTF-8'); // 
		$this->domTemp->preserveWhiteSpace = false;
		$this->domTemp->loadXML($source);
		$this->domXpathTemp = new DOMXPath($this->domTemp);
	}
	
	function loadGenericXML(){
						
		$source = $this->getXMLGeneric();
		
		$this->oXMLGeneric = new XMLHandle($source,'xml');
	    $this->oXMLGeneric->Parser_read(false);
	    
	}
	
    
	function set_domXpath()
	{
		$this->domXpath = new DOMXPath($this->dom);
	}
	
	
	
	function setCodeItkg()
    {
   	 global $iteration;	  	
   	 global $iHtml;	 
   	 global $curr_order;
   	 global $ifieldSet;
   	 global $iquestion;
   	 
	 $root = $this->dom->firstChild;
	 $iteration=0;
	 $iHtml=0;
	 $curr_order=0;
	 $ifieldSet=0;
	 $iquestion=0;
	 
	 //The getNodesInfo function call
	 $this->setRecursiveCodeItkg($root,$iteration);
	   
	
    }  
 

// add an attribute itkg_gtm_data to identify the taggtm html
public function setXmlOriginalTagGtm() {
	foreach ($this->domXpath->query('//html') as $element)
	{
		if (strpos($element->nodeValue, 'gtm(data)') !== false) {
			$element->setAttribute('itkg_gtm_data', '1');
			break;
		}
	}	
}

 function setRecursiveCodeItkg($node,$iteration)
   {
   	 global $iteration;
   	 global $iHtml;
   	 global $curr_order;
   	 global $ifieldSet;
   	 global $iquestion;
   	 
   	 if ($node->hasChildNodes())
     {
      $subNodes = $node->childNodes;
      foreach ($subNodes as $subNode)
         {
         	     	
         	         	
         	if($subNode->nodeType == XML_ELEMENT_NODE)
         	{
         		$iteration++ ;
         		$itkg = $subNode->getAttribute('itkg_code');
         		if(empty($itkg))
         		{	
         			$parentNode = $subNode->parentNode;
					if($parentNode->tagName=='field')
					{
						$code_itkg = $subNode->tagName.'_'.$parentNode->getAttribute('code').'_'.$iteration;
					}elseif($parentNode->parentNode->tagName=='field'){
						$code_itkg = $subNode->tagName.'_'.$parentNode->parentNode->getAttribute('code').'_'.$iteration;
					}else{
	         			$code_itkg = $subNode->tagName.'_'.$iteration;
					}
	         		$code_itkg_line = "";
	         		
	         		/* reprise des itkg_code du générique pour les noeuds line et field */
	         		/*if($subNode->tagName=='field' && $this->oXMLGeneric)
	         		{         			         			
	         			$tagCode=$subNode->getAttribute('code');         			
	         			$elementGeneric = $this->oXMLGeneric->domXpath->query("//field[@code='$tagCode']")->item(0);
	         			$code_itkg = $elementGeneric->getAttribute('itkg_code');
	         			         			
	         			$tagCodeLine = "";
	         			$elementLineGeneric = $elementGeneric->parentNode;
	         			$code_itkg_line = $elementLineGeneric->getAttribute('itkg_code');
	         			         			
	         			$subNode->parentNode->setAttribute('itkg_code',  $code_itkg_line );
	         		}*/
	         		
	         		if($subNode->tagName=='page')
	         		{
	         			
	         			//pattern : page_[valeur neoud <order>]
	         			
	         			$children = $subNode->childNodes;
	         			
	         			$curr_order=0;
	         			$ifieldSet=0;
	         				foreach ($children as $child) {
	         					if($child->tagName=='order')
	         					{
	         						$curr_order = $child->nodeValue;
	         					}
	         					
	         				}
	         				
	         				$code_itkg = $subNode->tagName.'_'.$curr_order;
	         			
	         		}
	         		
	         		if($subNode->tagName=='fieldSet')
	         		{
	         			//pattern : fieldSet_[valeur neoud <order> de la page]-[numero de la position du fieldSet dans la page]
	         			
	         			$ifieldSet ++;
	         			$iquestion=0;
	         			
	         			$code_itkg = $subNode->tagName.'_'.$curr_order.'-'.$ifieldSet;
	         		}
	         		if($subNode->tagName=='question')
	         		{
	         			//pattern : question_[valeur neoud <order> de la page]-[numero de la position du fieldSet dans la page]-[numero de la position de laquestion dans le fielSet]
	         			
	         			$iquestion ++;
	         		
	         			$code_itkg = $subNode->tagName.'_'.$curr_order.'-'.$ifieldSet.'-'.$iquestion;
	         		}
					
	         		//connector
	         		
	         		if($subNode->tagName=='mapping' && $subNode->getAttribute('code')=='TYPE')
	         		{
	         			
	         			if($subNode->parentNode->parentNode->tagName=='connector')
	         			{
	         				$code='connector_'.$subNode->getAttribute('key');
	         				$subNode->parentNode->parentNode->setAttribute('itkg_code', $code);
	         				$subNode->parentNode->parentNode->parentNode->setAttribute('itkg_code',  'line_'.$code );
	         			}
	         		}
	         		
	         		if($subNode->tagName=='field')
	         		{         		
	         			//pattern : field_[valeur de l'attribut "code" du noeud]
	         				      
	         			$tagCode=$subNode->getAttribute('code');         			
	         			$elementfield = $this->domXpath->query("//field[@code='$tagCode']")->item(0);
	         			$code_itkg = $tagCode;

	         			//pattern : line_[valeur de l'attribut "code" du dernier noeud field de la line]
	         			$subNode->parentNode->setAttribute('itkg_code',  'line_'.$tagCode );
	         		}

					if($subNode->tagName=='toggle')
					{

						//pattern : field_[valeur de l'attribut "code" du noeud]

						$template=$subNode->parentNode->parentNode->getAttribute('template');
						$code_itkg = 'toggle_' . $template;

						//pattern : line_[valeur de l'attribut "code" du dernier noeud field de la line]
						$subNode->parentNode->setAttribute('itkg_code',  'line_'.$code_itkg );
						$subNode->parentNode->parentNode->setAttribute('itkg_code',  'question_'.$code_itkg );
					}
	         		
	         		if($subNode->tagName=='html')
	         		{
						if (strpos($subNode->nodeValue, "gtm(data)") !== false) {
							$subNode->setAttribute('itkg_gtm_data',  '1');
						} else {

							//pattern : html_[numero position du noeud html]
							$question_template="";
							$iHtml ++;

							$code_itkg = $subNode->tagName.'_'.$iHtml;
							$subNode->parentNode->setAttribute('itkg_code',  'line_'.$code_itkg );

							$question_template = $subNode->parentNode->parentNode->getAttribute('template');

							if(!empty($question_template))
							{
								//pattern : html_[valeur de l'attribut "template" de la question]
								$code_itkg = $subNode->tagName.'_'.$question_template;
								$subNode->parentNode->setAttribute('itkg_code',  'line_'.$question_template );
								$subNode->parentNode->parentNode->setAttribute('itkg_code',  'question_'.$question_template );
							}
						}
	         		}
	         		
	         		/**/
	         		         		
	         		$subNode->setAttribute( 'itkg_code',  $code_itkg );
	         		//var_dump($subNode->tagName.'_'.$iteration);
         		}
         	}
         	         	
        
         $this->setRecursiveCodeItkg($subNode,$iteration);      
         }
         
      }     
   }
   	

	
   function formatArray()
   {
   	 if($this->aField){
   	 	
   	 	foreach ($this->aField as $kpage=>$page){
   	 		$aTab[$kpage]=array();
   	 		
   	 		
   	 		foreach ($page as $kfield=>$field)
   	 		{
   	 			$aTab[$kpage]['fieldSet'][$field['fieldSet']]['question'][$field['question']]['line'][$field['line']]['field'][$kfield] =  $field;
   	 		}
   	 		
   	 	}
   	 
   	 	return $aTab;
   	 }
   	// debug($aTab);
 /*  	 
   	 echo "<pre>";
print_r($aTab);

echo "</pre>";
   	 */
   }
   
   function Parser_read($setCodeItkg=true){
   	
   		/*if($setCodeItkg)
   		{
   			$this->setCodeItkg();
   		}*/
   		$this->setCodeItkg();
   		
   		$this->XMLReader = new XMLReader();
		$this->XMLReader->XML($this->dom->saveXML());
		
		$aTypeField = array('textbox','checkbox','radio','dropDownList','dropdown','password','textarea','file','richTextEditor','captcha','button','hidden','datepicker','colorpicker','slider');
		//$aTypeFieldExclude = array('button','hidden');
		$aTypeFieldExclude = array();
		
		$page=-1;
		$i=0;
		$n2=0;
		$iFieldSet=-1;
		$iQuestion=-1;
		$iLine=-1;
		
		$iGlobalPage=-1;
		$iGlobFieldSet=-1;
		$iGlobQuestion=-1;
		$iGlobLine=-1;
		
		$cpt_fieldset_order = 1;
		$cpt_question_order = 1;

		$old_local_names = array();
		while($this->XMLReader->read()) {
	        // check si le nodeType est bien un élément et non un attribute ou #Text 
		    if($this->XMLReader->nodeType == XMLReader::ELEMENT) {
		        if($this->XMLReader->localName == 'instance') {
		            $this->instance['id'] = $this->XMLReader->getAttribute('id');
		            $this->instance['culture_id']=substr($this->instance['id'],10,2);
		            $this->instance['opportunite_id']=substr($this->instance['id'],14,2);
		            $this->instance['site_id']=substr($this->instance['id'],6,2);
		            $this->instance['device_id']=substr($this->instance['id'],12,1);
		            /*type d'instance*/
		            $generic=substr($this->instance['id'],5,1);
		            $context=substr($this->instance['id'],9,1);
		            $ABtesting=substr($this->instance['id'],8,1);
		            
		           /* if($generic==9)
		            {
		            	//generic
		            	$this->instance['context']=5;//to do constancte
		            }elseif ($ABtesting!=0)
		            {
		            	//ABtesting
		            	$this->instance['context']=4;//to do constancte
		            }else{
		            	//standant ou contextualisés
		            	$this->instance['context']=(int)$context;
		            }*/
		           $this->instance['context']=(int)$context;
		               
		        } else if($this->XMLReader->localName == 'form') {
		        	$this->instance['form'] = $this->XMLReader->getAttribute('id');
		        	$this->form['id'] = $this->XMLReader->getAttribute('id');
		        	$old_local_names[] = 'form';
		        } else if($this->XMLReader->localName == 'page') {
		        	$page++;
		        	$iGlobalPage++;
		        	$iFieldSet=-1;
		        	$iGlobFieldSet=-1;
		        			        			        	
		            $oXML = new XMLParser($this->XMLReader->readOuterXml());

					$aPage=$oXML->parse();

					if (isset($aPage['page']['configuration']['next']['attributes']['label'])) {
						$this->aPages[$page]['configuration']['next']['attributes']['label'] = $aPage['page']['configuration']['next']['attributes']['label'];
						$this->aPages[$page]['configuration']['next']['attributes']['visible'] = (isset($aPage['page']['configuration']['next']['attributes']['visible'])) ?  $aPage['page']['configuration']['next']['attributes']['visible'] : 'false';
					}
					if (isset($aPage['page']['configuration']['previous']['attributes']['label'])) {
						$this->aPages[$page]['configuration']['previous']['attributes']['label'] =  $aPage['page']['configuration']['previous']['attributes']['label'];
						$this->aPages[$page]['configuration']['previous']['attributes']['visible'] = (isset($aPage['page']['configuration']['previous']['attributes']['visible'])) ?  $aPage['page']['configuration']['previous']['attributes']['visible'] : 'false';
					}			
					
					$this->aPages[$page]['title'] = $aPage['page']['title']['value'];		
					$this->aPages[$page]['itkg_code'] = $aPage['page']['attributes']['itkg_code'];		
										
					$this->aPageStructure[$aPage['page']['attributes']['itkg_code']]['title']=	$aPage['page']['title']['value'];
					$this->aPageStructure[$aPage['page']['attributes']['itkg_code']]['itkg_code']=	$aPage['page']['attributes']['itkg_code'];
					$this->aPageStructure[$aPage['page']['attributes']['itkg_code']]['position']=	$page;
					
					$this->XMLReader->moveToAttribute('itkg_code');
		        	$curr_page = $this->XMLReader->value;
					
					unset($aPage);
					$old_local_names[] = 'page';
		        } else if($this->XMLReader->localName == 'fieldSet') {
		        	$iGlobFieldSet++;
		        	$iQuestion=-1;
		        	$iGlobQuestion=-1;

		        	$this->XMLReader->moveToAttribute('itkg_code');
		        	$curr_fieldSet = $this->XMLReader->value;	 
		        	
		        	$this->aGlobalStructure[$curr_fieldSet]['niveau'] = 'fieldSet';
		        	$this->aGlobalStructure[$curr_fieldSet]['xpath'] = $curr_page."/".$curr_fieldSet;
		        	$this->aGlobalStructure[$curr_fieldSet]['page'] = $curr_page;
		        	$this->aGlobalStructure[$curr_fieldSet]['num_page'] = $page;
		        	$this->aGlobalStructure[$curr_fieldSet]['fieldSet'] = $curr_fieldSet;
		        	$this->aGlobalStructure[$curr_fieldSet]['question'] = "";
		        	$this->aGlobalStructure[$curr_fieldSet]['line'] = "";
		        	$this->aGlobalStructure[$curr_fieldSet]['position'] = $iGlobFieldSet;
		        	$this->aGlobalStructure[$curr_fieldSet]['fullpath'] = $curr_page."/".$curr_fieldSet.'/'.$iGlobFieldSet;
		        	$this->aGlobalStructure[$curr_fieldSet]['order_tmp'] = $cpt_fieldset_order;
		        	
		        	$cpt_fieldset_order++;
		        	$cpt_question_order = 1;
		        	$old_local_names[] = 'fieldSet';
		        } else if ($this->XMLReader->localName == 'requestParameter') {
		        	$node = $this->XMLReader->expand();
		        	
		        	if (isset($old_local_names[count($old_local_names) -1]) && $old_local_names[count($old_local_names) -1] == 'field') {
			        	$nodes = $node->childNodes;
			        	$page_gtm_tag = (isset($this->aPages[$page]['gtm_tags_under_question'])) ? $this->aPages[$page]['gtm_tags_under_question'] : array() ;
			        			
			        	foreach($nodes as $unGtm) {
			        		if ($unGtm->tagName == 'gtm') {
			        			$tag_nodes = $unGtm->childNodes;
			        			foreach($tag_nodes as $unTag) {
				        			if ($unTag->tagName == 'tag') {
					        			$nodeName = $unTag->getElementsByTagName('name')->item(0);
					        			$nodeCategory = $unTag->getElementsByTagName('category')->item(0);
										$nodeAction = $unTag->getElementsByTagName('action')->item(0);
					        			$nodeLabel = $unTag->getElementsByTagName('label')->item(0);
					        			
					        			$page_gtm_tag[] = array('name' => $nodeName->nodeValue, 
					        									'category' => $nodeCategory->nodeValue,
					        									'action' => $nodeAction->nodeValue,
					        									'label' => $nodeLabel->nodeValue);
									}
			        			}
			        		}
			        	}			        	
			        	
			        	$this->aPages[$page]['gtm_tags_under_question'] = $page_gtm_tag;
		        	}
		        } else if ($this->XMLReader->localName == 'question') {
		        	$template ="";
		        	$iGlobQuestion++;
		        	$iLine=-1;
		        	$iGlobLine=-1;
		        	
		        	$this->XMLReader->moveToAttribute('itkg_code');
		        	$curr_question = $this->XMLReader->value;
		        	
		        	$this->XMLReader->moveToAttribute('template');
		        	
		        	if($this->XMLReader->value)
		        	{
		        		
		        		$template = $this->XMLReader->value;
		        				        		
		        		/*Composants avancés*/
			        	if(in_array($template,$this->ListCompoAv)){
			        		$this->aCompoAv[$curr_question]['template'] = $template;
			        		$this->aCompoAv[$curr_question]['page'] = $curr_page;
			        		$this->aCompoAv[$curr_question]['fieldSet'] = $curr_fieldSet;
			        		$this->aCompoAv[$curr_question]['question'] = $curr_question;
			        		$this->aCompoAv[$curr_question]['xpath'] = $curr_page."/".$curr_fieldSet."/".$curr_question;
			        		
			        		/*var_dump($this->instance['id']);
			        		var_dump($this->aCompoAv[$curr_question]);*/
			        	}
			        			        		
		        	}
		        	
		        	$this->aGlobalStructure[$curr_question]['niveau'] = 'question';
		        	$this->aGlobalStructure[$curr_question]['xpath'] = $curr_page."/".$curr_fieldSet."/".$curr_question;
		        	$this->aGlobalStructure[$curr_question]['page'] = $curr_page;
		        	$this->aGlobalStructure[$curr_question]['num_page'] = $page;
		        	$this->aGlobalStructure[$curr_question]['fieldSet'] = $curr_fieldSet;
		        	$this->aGlobalStructure[$curr_question]['question'] = $curr_question;
		        	$this->aGlobalStructure[$curr_question]['line'] = "";
		        	$this->aGlobalStructure[$curr_question]['position'] = $iGlobQuestion;
		        	$this->aGlobalStructure[$curr_question]['fullpath'] = $curr_page."/".$curr_fieldSet."/".$curr_question.'/'.$iGlobQuestion;
		        	$this->aGlobalStructure[$curr_question]['order_tmp'] = $cpt_question_order;
		        	$cpt_question_order++;
		        	$old_local_names[] = 'question';
		        } else if($this->XMLReader->localName == 'line') {
		        	//$iLine++;
		        	$iGlobLine++;
		         	
		        	$this->XMLReader->moveToAttribute('itkg_code');
		        	$curr_line = $this->XMLReader->value;
		        	
		        	$this->aGlobalStructure[$curr_line]['niveau'] = 'line';
		        	$this->aGlobalStructure[$curr_line]['xpath'] = $curr_page."/".$curr_fieldSet."/".$curr_question."/".$curr_line;
		        	$this->aGlobalStructure[$curr_line]['page'] = $curr_page;
		        	$this->aGlobalStructure[$curr_line]['num_page'] = $page;
		        	$this->aGlobalStructure[$curr_line]['fieldSet'] = $curr_fieldSet;
		        	$this->aGlobalStructure[$curr_line]['question'] = $curr_question;
		        	$this->aGlobalStructure[$curr_line]['line'] = $curr_line;
		        	$this->aGlobalStructure[$curr_line]['position'] = $iGlobLine;
		        	$this->aGlobalStructure[$curr_line]['fullpath'] = $curr_page."/".$curr_fieldSet."/".$curr_question."/".$curr_line.'/'.$iGlobLine;
		        		
		        	$iField = 0;
		        	$old_local_names[] = 'line';
		        } else if($this->XMLReader->localName == 'connector') {
		        	 
		        	$this->XMLReader->moveToAttribute('itkg_code');
		        	$n2 = $this->XMLReader->value;
		        	$oXML = new XMLParser($this->XMLReader->readOuterXml());
		        		
		        		
		        	$this->aField[$n2]=$oXML->parse();
		        	$this->aField[$n2]['itkg_code']=$n2;
		        	$this->aField[$n2]['xpath']=$curr_page."/".$curr_fieldSet ."/".$curr_question."/".$curr_line;
		        		
		        	$this->aField[$n2]['page']=$curr_page;
		        	$this->aField[$n2]['num_page']=$page;
		        	$this->aField[$n2]['fieldSet']=$curr_fieldSet ;
		        	$this->aField[$n2]['question']=$curr_question ;
		        	$this->aField[$n2]['line']=$curr_line ;
		        	$this->aField[$n2]['position']=$iField ;
		        	$this->aField[$n2]['fullpath']=$curr_page."/".$curr_fieldSet ."/".$curr_question."/".$curr_line.'/'.$iField ;
		        			
		        	$this->aField[$n2]['type'] = 'connector';
		        	$old_local_names[] = 'connector';	
		        	$iField ++;
		        } else if($this->XMLReader->localName == 'html') {
		        	
		           $this->XMLReader->moveToAttribute('itkg_code');
		           $n2 = $this->XMLReader->value;
		           $oXML = new XMLParser($this->XMLReader->readOuterXml());
					
					
					$this->aField[$n2]=$oXML->parse();
					$this->aField[$n2]['itkg_code']=$n2;
		            $this->aField[$n2]['xpath']=$curr_page."/".$curr_fieldSet ."/".$curr_question."/".$curr_line;
					
					$this->aField[$n2]['page']=$curr_page;
					$this->aField[$n2]['num_page']=$page;
					$this->aField[$n2]['fieldSet']=$curr_fieldSet ;
					$this->aField[$n2]['question']=$curr_question ;
					$this->aField[$n2]['line']=$curr_line ;
					$this->aField[$n2]['position']=$iField ;
					$this->aField[$n2]['fullpath']=$curr_page."/".$curr_fieldSet ."/".$curr_question."/".$curr_line.'/'.$iField ;
					
									
					$this->aField[$n2]['type'] = 'html';
					
					$iField ++;	
					$old_local_names[] = 'html';
		        } else if($this->XMLReader->localName == 'toggle') {

					$this->XMLReader->moveToAttribute('itkg_code');
					$n2 = $this->XMLReader->value;
					$oXML = new XMLParser($this->XMLReader->readOuterXml());


					$this->aField[$n2]=$oXML->parse();
					$this->aField[$n2]['itkg_code']=$n2;
					$this->aField[$n2]['xpath']=$curr_page."/".$curr_fieldSet ."/".$curr_question."/".$curr_line;

					$this->aField[$n2]['page']=$curr_page;
					$this->aField[$n2]['num_page']=$page;
					$this->aField[$n2]['fieldSet']=$curr_fieldSet ;
					$this->aField[$n2]['question']=$curr_question ;
					$this->aField[$n2]['line']=$curr_line ;
					$this->aField[$n2]['position']=$iField ;
					$this->aField[$n2]['fullpath']=$curr_page."/".$curr_fieldSet ."/".$curr_question."/".$curr_line.'/'.$iField ;


					$this->aField[$n2]['type'] = 'toggle';

					$iField ++;
					$old_local_names[] = 'toggle';
				} else if($this->XMLReader->localName == 'field') {
		           // move to its textnode / child
		           $this->XMLReader->moveToAttribute('code');
		           $n2 = $this->XMLReader->value;
		          
		           //$Pages[$i] = $xmlReader->readInnerXML();
		           //$Pages[$i] = xml_parse_into_array($xmlReader->readInnerXML());
		           $oXML = new XMLParser($this->XMLReader->readOuterXml());
					
					$this->aField[$n2]=$oXML->parse();

					if($template=="PHONE_Selector_alt" || $template=="PHONE_Selector_list")
					{
						$this->aField[$n2]['template'] = $template;
					}
					$this->aField[$n2]['itkg_code']=$n2;
					$this->aField[$n2]['xpath']=$curr_page."/".$curr_fieldSet ."/".$curr_question."/".$curr_line;
					
					$this->aField[$n2]['page']=$curr_page;
					$this->aField[$n2]['num_page']=$page;
					$this->aField[$n2]['fieldSet']=$curr_fieldSet ;
					$this->aField[$n2]['question']=$curr_question ;
					$this->aField[$n2]['line']=$curr_line ;
					$this->aField[$n2]['position']=$iField ;
					$this->aField[$n2]['fullpath']=$curr_page."/".$curr_fieldSet ."/".$curr_question."/".$curr_line.'/'.$iField ;
					
					
										
					$aKeys = array_keys($this->aField[$n2]['field']);
		    									
					if(is_array($aKeys) && !empty($aKeys))
					{
						foreach ($aKeys as $key)
						{
							if(in_array($key,$aTypeField)){
																								
								$this->aField[$n2]['type'] = $key;
								break;
							}elseif (in_array($key,$aTypeFieldExclude))
							{
								unset($this->aField[$n2]);
								$iField --;
								//$aField[$page][$n2]['type'] = $key;
								break;
							}
						}
					}
					//var_dump($Field[$n2]);
					$n2++;
					$iField ++;
					$old_local_names[] = 'field';
				}
		        				
		        $i++;
		    }
		}
				
		$this->aListened =array();
		$this->aListening = array();

		//cherche les <listener>
		$listNodeListener = $this->dom->getElementsByTagName('listener');
		 
		if(!empty($listNodeListener))
		{
			foreach($listNodeListener as $nodeListener)
			{
				$Nodeparent=$nodeListener->parentNode;
					
				if($Nodeparent->tagName == "field")
				{
					if($nodeListener->getAttribute('fieldID') != $Nodeparent->getAttribute('id') && $nodeListener->getAttribute('fieldID'))
					{
						//alors c'est un champs qui en écoute un autre
										
						$ele = $this->domXpath->query("//field[@id='".$nodeListener->getAttribute('fieldID')."']")->item(0);
						
						if($ele)
						{
							$FiedlCodeListened = $ele->getAttribute("code");
							
							$FiedlCode = $Nodeparent->getAttribute('code');
							$this->aField[$FiedlCode]['listening'] = true;
							
							$this->aListening[$FiedlCode]=$FiedlCode;
							$this->aListened[$FiedlCodeListened]=$FiedlCodeListened;
						}
					}
				}else
				{
					//alors champs écouté
					if($nodeListener->getAttribute('fieldID'))
					{
					
						$ele=$this->domXpath->query("//field[@id='".$nodeListener->getAttribute('fieldID')."']")->item(0);
						if($ele)
						{
							$FiedlCode = $ele->getAttribute("code");
							$this->aField[$FiedlCode]['listened'] = true;
							
							$this->aListened[$FiedlCode]=$FiedlCode;
						}
						
						
						if($Nodeparent->tagName == "line")
						{
							if($Nodeparent->hasChildNodes()){
								foreach ($Nodeparent->childNodes as $field)
								{
									if($field->tagName=='field')
									{
										$this->aListening[$field->getAttribute('code')]=$field->getAttribute('code');
									}
								}
							}
						}elseif($Nodeparent->tagName == "question")
						{
							if($Nodeparent->hasChildNodes()){
								foreach ($Nodeparent->childNodes as $line)
								{
									if($line->hasChildNodes())
									{
										foreach ($line->childNodes as $field)
										{
											if($field->tagName=='field')
											{
												$this->aListening[$field->getAttribute('code')]=$field->getAttribute('code');
											}
						
										}
									}
								}
							}
						}elseif($Nodeparent->tagName == "fieldSet")
						{
							
							if($Nodeparent->hasChildNodes()){
								foreach ($Nodeparent->childNodes as $question)
								{
									if($question->hasChildNodes()){
										foreach ($question->childNodes as $line)
										{
											if($line->hasChildNodes())
											{
												foreach ($line->childNodes as $field)
												{
													if($field->tagName=='field')
													{
														$this->aListening[$field->getAttribute('code')]=$field->getAttribute('code');
													}
									
												}
											}
										}
									}
								}
							}
							
						}
						
					}
					
					
					
				}
				//	if($nodeListener->getAttribute('fieldID'))
			}
		}
   }
      

	function setAllId($DOMNode){// patch pour valider le xml, pour utiliser getelementbyid
		
	  if($DOMNode->hasChildNodes()){
	    foreach ($DOMNode->childNodes as $DOMElement) {
	      if($DOMElement->hasAttributes()){
	        $id=$DOMElement->getAttribute("id");
	        if($id){
	          $DOMElement->setIdAttribute("id",$id);
	        }
	      }
	      $this->setAllId($DOMElement);
	    }
	  }
	  
	}
			
	function modifyTextNode($tagCode,$tagName,$value,$sousTag="")
	{
		
		$element = $this->domXpath->query("//field[@code='$tagCode']".(!empty($sousTag)?"/$sousTag":""))->item(0);
		
		$elementCible = $element->getElementsByTagName($tagName)->item(0)->firstChild;
		
		
		$elementCible->replaceData(0,strlen($elementCible->nodeValue),$value);
	
		
	}
	
	function modifyAttributeNode($tagCode,$AttributeName,$AttributeValue)
	{	
		$element = $this->domXpath->query("//field[@code='$tagCode']")->item(0);
		$element->setAttribute( $AttributeName,  $AttributeValue );
	}
			
	function DeleteField($tagCode,$codeQuestionGen=false)
	{
		//var_dump('delete field : '.$tagCode);
		
		
    	if(preg_match("/^html_/i",$tagCode))
    	{
    		$element = $this->domXpath->query("//html[@itkg_code='$tagCode']")->item(0);
    	}else{
    		$element = $this->domXpath->query("//field[@code='$tagCode']")->item(0);
    	}
		
    	if ($element == null) {
    		$element = $this->domXpath->query("//connector[@itkg_code='$tagCode']")->item(0);
    	}
    	
		$parent = $element->parentNode;
		
		$parent->removeChild($element);
		
		/*on replace la line vide a sont emplacement d'origine, pour que le prochain ajout du composant ce face au bon endroit, JIRA BOFORMS-117*/
		$bHasField = true;
		if($parent->hasChildNodes())
		{
			foreach ($parent->childNodes as $ichild=>$child)
			{	
				if($child->tagName=='field' OR $child->tagName=='html')
				{
					$bHasField = false;
				}
			}
		}
					
		if($bHasField && $codeQuestionGen)//si line est vide (pas de field ou de html) et que la quesion d'origine est différente
		{
			$QuestionElement = $this->domXpath->query("//question[@itkg_code='$codeQuestionGen']")->item(0);

			if(!empty($QuestionElement))
			{
			
				$lineClone=$parent->cloneNode(true);
				$nodeToDelete= $parent;
				$parent->parentNode->removeChild($nodeToDelete);
				
				$QuestionElement->psaInsertChild($lineClone);
			}
			
		}
		/**/
					
	}
	
	function deleteNode($tagCode,$tag)
	{
		//var_dump('delete node : '.$tagCode);
		$element = $this->domXpath->query("//".$tag."[@itkg_code='$tagCode']")->item(0);
		$parent = $element->parentNode;
		
		$parent->removeChild($element);
		
		
		if($parent->tagName=='field')
		{
			$logCode = $parent->getAttribute('itkg_code');
		}else{
			$logCode = $parent->parentNode->getAttribute('itkg_code');
		}
			
	}
	
	function createRequiredNode($tagCodeField,$val)
	{
		//var_dump('create required noeud on field : '.$tagCodeField);
		$elementField = $this->domXpath->query("//field[@itkg_code='$tagCodeField']")->item(0);
	
		$nodeRequired = $this->dom->createElement("required");
	
		$nodeError = $this->dom->createElement("errorMessage");
		$nodeError->appendChild($this->dom->createCDATASection($val));
				
		$nodeRequired->appendChild($nodeError);
		$elementField->psaInsertChild($nodeRequired);
		
	}
	
	function createDefaultValueNode($tagCodeFieldType,$type,$val)
	{
		//var_dump('create DefaultValue noeud on field : '.$tagCodeFieldType);
		$elementFieldType = $this->domXpath->query("//".$type."[@itkg_code='$tagCodeFieldType']")->item(0);
			
		$node = $this->dom->createElement("defaultValue");
		$node->appendChild($this->dom->createCDATASection($val));
				
		
		//$elementFieldType->appendChild($node);
		$elementFieldType->psaInsertChild($node);
				
	}
	
	function createTextNode($tagName,$tagCodeField,$val, $key_type = 'field')
	{
		
		$elementField = $this->domXpath->query("//" . $key_type . "[@itkg_code='$tagCodeField']")->item(0);
		
		$node = $this->dom->createElement($tagName);
		$node->appendChild($this->dom->createCDATASection($val));
		
		//$elementField->appendChild($node);
		$elementField->psaInsertChild($node);

	}


	// replace the type of a field (ie for civility change type from dropdown to radio)
	function replaceNodeType($tagCodeField, $old_type, $new_type) {
		if ($tagCodeField != '' && $tagCodeField != null) {
			$old_node = $this->domXpath->query("//" . $old_type . "[@itkg_code='$tagCodeField']")->item(0);
			$new_node = $this->dom->createElement($new_type);
			
			if($old_node->hasChildNodes())
			{
				$childnodes = array();
		    	foreach ($old_node->childNodes as $child){
		        	$childnodes[] = $child;
		    	}
				
				foreach ($childnodes as $child){
			        $child2 = $this->dom->importNode($child, true);
			        $new_node->appendChild($child2);
			    }
			}
			
	        if ($old_node->hasAttributes()) {
	           	foreach ($old_node->attributes as $attrName => $attrNode) {
			        $new_node->setAttribute($attrName, $old_node->getAttribute($attrName));
		    	}
	        }
		    $old_node->parentNode->replaceChild($new_node, $old_node);
		}
	}
		
	// creates a date picker node (ie: openingStart, openingEnd, hourlabel)
	function createPickerNode($tagName, $tagCodeField, $val) {
		//var_dump('create ' . $tagName . ' noeud on field : '.$tagCodeField);
		$elementField = $this->domXpath->query("//datepicker[@itkg_code='$tagCodeField']")->item(0);
		
		$node = $this->dom->createElement($tagName);
		if ($tagName == 'openingStart' || $tagName == 'openingEnd' || $tagName == 'dateEnd ') {
			$node->nodeValue = $val;
		} else {
			$node->psaInsertChild($this->dom->createCDATASection($val));//appendChild
		}
		
		$elementField->psaInsertChild($node); // appendChild
		
	}

	function clearEmptyLineStructure() {
		$elementLineTmpList = $this->domXpath->query("//line");				

		$to_remove = array();
		foreach ($elementLineTmpList as $lineNode) 
		{
			// checks if this line contains at least one field
			$nb_child = $lineNode->childNodes->length;
			$nb_order = 0;

			if($nb_child > 0)
			{
				foreach ($lineNode->childNodes as $tag) {
					if($tag->tagName == 'order') {
						$nb_order ++;
					}
				}
			}

			if ($nb_child == 0 || ($nb_order == 1 && $nb_child == 1)) {
				$to_remove[count($to_remove)] = $lineNode;
			}
    		}	

		for ($i = 0; $i < count($to_remove); $i++) {
			$parent = $to_remove[$i]->parentNode;
			$parent->removeChild($to_remove[$i]);
		}
	}	
	
	function clearTmpStructure()
	{
		
		
		//$elementLineTmp = $this->domXpath->query("//line[@itkg_code='$TagCode']")->item(0)->parentNode;
		$elementQuestionTmpList = $this->domXpath->query("//question[starts-with(@itkg_code, 'tmp_question_')]");
						
		foreach ($elementQuestionTmpList as $questionNode) 
		{
		
			$bclear = true;
			if($questionNode->hasChildNodes())
			{
				foreach ($questionNode->childNodes as $ichild=>$LineNode)
				{
					
					if($LineNode->hasChildNodes())
					{
						$bclear = false;
					}
					
				}
			}
			
			if($bclear)
			{
				//var_dump("Clean TMP ");
				$nodeFieldSet = $questionNode->parentNode;
				$nodeFieldSet->parentNode->removeChild($nodeFieldSet);
			}
			
		}
		
	}
	
	function MoveNode($niveau,$elementParent,$aNewStructure,$LineOri=false)
	{
		
		//var_dump("Move $niveau ");
		if($elementParent->hasChildNodes())//on clonne tous les sous éléments et on les supprime du noeud, puis on regénére les éléments dans le nouvelle ordre.
		{
			/*** On clone les éléments ***/
				foreach ($elementParent->childNodes as $ichild=>$child){
					
					if($niveau == 'field')
					{
						
						if($child->tagName=='field' || $child->tagName=='html')
						{
							
							if($child->tagName=='field')
							{
								$aElementClone[$child->getAttribute('code')] = $child->cloneNode(true);
								$nodesToDelete[] = $child;
							}else {
								$aElementClone[$child->getAttribute('itkg_code')] = $child->cloneNode(true);
								$nodesToDelete[] = $child;
							}
						}
						
					}elseif ($child->tagName==$niveau)
					{
						
						$aElementClone[$child->getAttribute('itkg_code')] = $child->cloneNode(true);
						$nodesToDelete[] = $child;
						
					}
					
				}
		}		
		
				
			/******/
								
			/*** on supprime les éléments du noeud  ***/
				if(!empty($nodesToDelete))
				{
					foreach($nodesToDelete as $node)
					{
						$elementParent->removeChild($node);
					} 
				}
				unset($nodesToDelete);	
				
				if($LineOri)
				{
					$elementLineOri = $this->domXpath->query("//line[@itkg_code='$LineOri']")->item(0);
					if($elementLineOri)
						$elementLineOri->parentNode->removeChild($elementLineOri);
				}
			/******/
				
			/*** pour le changement d'etape ****/
				
			/*if($codeTagChangeEtape)
			{			
				var_dump('toto');		
				$element = $this->domXpath->query("//".$niveau."[@itkg_code='$codeTagChangeEtape']")->item(0);
				$aElementClone[$element->getAttribute('itkg_code')] = $element->cloneNode(true);
				$element->parentNode->removeChild($element);	
			}*/
			/******/
			
				
		
			/*** on recréé les éléménents, à partir des clonnes, dans le bon ordre ***/
				
				if(!empty($aNewStructure) && is_array($aNewStructure))
				{
					$i=0;
					foreach ($aNewStructure as $kstructure=>$aStructure)
					{
						$bflag=true;
																		
						if(!isset($aElementClone[$aStructure['itkg_code']]))
						{

							if (empty($this->domXpathTemp) || !($this->domXpathTemp instanceof DOMXPath)) {
								$this->setDomXPathTemp();
							}
							
							$elementBase = $this->domXpathTemp->query("//".$niveau."[@itkg_code='".$aStructure['itkg_code']."']");
							if ($elementBase) {
								$element = $elementBase->item(0);
								if($element)
								{
									$node = $this->dom->importNode($element, true);
									$aElementClone[$aStructure['itkg_code']]=$node;
								}else{
									//break;
									$bflag=false;
								}
							}
						}
						
						if($bflag)
						{
							$node_curr=$aElementClone[$aStructure['itkg_code']];
							
							if($niveau == 'page')
							{
								//on regénére l'attribut open pour les pages -> la premiére page doit avoir open en true les autre en false	
								
								if($i==0){
									$node_curr->setAttribute('open',  "true" );
								}else{
									$node_curr->setAttribute('open',  "false" );
								}
								$i++;
							}	
							
							//$elementParent->appendChild($node_curr);
							
							$elementParent->psaInsertChild($node_curr);
							
						}
					}
				}
			/******/
		 
	
	}
	
	
	function moveEtape($TagCode,$aNewStructure)
	{
		
		$niveau = 'page';
		
		//form de la page
		$elementParent = $this->domXpath->query("//page[@itkg_code='$TagCode']")->item(0)->parentNode;
		
		$this->MoveNode($niveau,$elementParent,$aNewStructure);
				
	}
	
	function moveFieldSet($TagCode,$aNewStructure)
	{
		$niveau = 'fieldSet';
		
		//page du fieldset 
		$elementPage = $this->domXpath->query("//fieldSet[@itkg_code='$TagCode']")->item(0)->parentNode;
		
		$this->MoveNode($niveau,$elementPage,$aNewStructure);
	}
	
	
	function moveQuestion($TagCode,$aNewStructure)
	{
		
		$niveau = 'question';
		
		//Fieldset de la question
		$elementParent = $this->domXpath->query("//fieldSet[@itkg_code='$TagCode']")->item(0);

		$this->MoveNode($niveau,$elementParent,$aNewStructure);
		
	}	
	
	function moveLine($TagCode,$aNewStructure,$questionOri,$lineCode)
	{
		
		$niveau = 'line';
		
		//question de la line
		
		$elementParent = $this->domXpath->query("//question[@itkg_code='$TagCode']")->item(0);

		$lineCodeOri=false;
		
		if($questionOri!=$TagCode)
		{
			$lineCodeOri = $lineCode;
		}		
		
		$this->MoveNode($niveau,$elementParent,$aNewStructure,$lineCodeOri);
	}	
	
	function moveField($TagCode,$aNewStructure,$codeTagChangeEtape=false, $niveau)
	{
		
		if ($niveau != 'connector') {
			$niveau = 'field';
		}
		
		//line du field
		
		$elementParent = $this->domXpath->query("//line[@itkg_code='$TagCode']")->item(0);
		
		$this->MoveNode($niveau,$elementParent,$aNewStructure,$codeTagChangeEtape);
		
	}	
	
	function moveFieldStep($aField,$aNewStructure,$codeTagChangeEtape,$ori_page)
	{
		$niveau = 'field';
					
		//clone du champs
		$elementField = $this->domXpath->query("//field[@itkg_code='$codeTagChangeEtape']")->item(0);
		$elementFieldClone = $elementField->cloneNode(true);
		
		//on supprime le field de la line d'origine
		$elementField->parentNode->removeChild($elementField);
				
		
		/*** nouvelle structure ***/
		
		$elementPage = $this->domXpath->query("//page[@itkg_code='".$aField['page']."']")->item(0);
		
				
		//nouveau fieldSet
		$domFieldSet = $this->dom->createElement('fieldSet');
		$domAttribute = $this->dom->createAttribute('id');
		$domAttribute->value = 'FIET200000000004';
		$domFieldSet->appendChild($domAttribute);
		$domAttribute = $this->dom->createAttribute('itkg_code');
		$domAttribute->value = $aField['fieldSet'];
		$domFieldSet->appendChild($domAttribute);
		
		//nouveau fieldSet
		$domQuestion = $this->dom->createElement('question');
		$domAttribute = $this->dom->createAttribute('id');
		$domAttribute->value = 'QUON000000000051';
		$domQuestion->appendChild($domAttribute);
		$domAttribute = $this->dom->createAttribute('itkg_code');
		$domAttribute->value = $aField['question'];
		$domQuestion->appendChild($domAttribute);
		
		$domFieldSet->appendChild($domQuestion);
		
		//nouveau line
		$domLine = $this->dom->createElement('line');
		$domAttribute = $this->dom->createAttribute('itkg_code');
		$domAttribute->value = $aField['line'];
		$domLine->appendChild($domAttribute);
		$domQuestion->appendChild($domLine);
		
		//déplacement du champ
		$domLine->appendChild($elementFieldClone);
		
		
		//$elementPage->appendChild($domFieldSet);
		$elementPage->psaInsertChild($domFieldSet);
		
		
		/** affichage du bouton de validation de l'etape **/
		$this->displayNextStepIfNecessary($aField, $ori_page, false);
		
		
		
		/****/
	
	}
	
	/** affichage du bouton de validation de l'etape si nécéssaire (après déplacement/suppression dun champ **/
	function displayNextStepIfNecessary($aField, $ori_page, $is_removal) {
		// rechercher les champs non hidden dans l'etape cible
		$aTabCurrent=array();
        $cpt = 0;
		foreach ($this->aField as $field)
        {
            if($field['page']==$aField['page'] && $field['type']!='hidden' && $field['type']!='button')
            {
                $aTabCurrent=$field['itkg_code'];
                $cpt++;
            }
        }
                
		// si $behavior enableDisableBehavior... dans page d'origine remplacer enableDisableBehavior par checkandclose et visible à false 
		$page_name = null;
        if ($is_removal) {
        	// test count = 1 car champ pas encore supprimé
        	
        	if ($cpt == 1) {
        		$page_name = $aField['page'];
        	}
        } else {
        	// si plusieurs champs deplacables, prévoir d'implementer ici ce test: 
        	// il faut aucun champ restant dans l'etape precedente pour supprimer le bouton etape suivante  
        	$page_name = $ori_page;	
        }

        if ($page_name != null && $this->domXpath->query("//page[@itkg_code='".$page_name."']/fieldSet/question/line/field[@code='TECHNICAL_VALID_END_PDV' or @code='TECHNICAL_VALID_END_CAR']/listener/behavior[@type='enableDisableBehavior']")->item(0) != null) {
        	$elementCheckAndClose = $this->domXpath->query("//page[@itkg_code='". $page_name ."']/fieldSet/question/line/field[@code='TECHNICAL_VALID_END_PDV' or @code='TECHNICAL_VALID_END_CAR']/listener/behavior[@type='enableDisableBehavior']")->item(0)->parentNode;
        	$behavior = $this->domXpath->query("//page[@itkg_code='".$page_name."']/fieldSet/question/line/field[@code='TECHNICAL_VALID_END_CAR' or @code='TECHNICAL_VALID_END_PDV']/listener/behavior[@type='enableDisableBehavior']")->item(0);
        	if ($elementCheckAndClose != null) {
        		$field_id = $elementCheckAndClose->getAttribute('fieldID');
        		//print_r($field_id);
        		if ($behavior!=null) {
        			$elementNext = $this->domXpath->query("//page[@itkg_code='". $page_name ."']/configuration/next")->item(0);
        			if ($elementNext != null) {
        				// revert attribute visible to false for configuration/next (hides the button)
        				$elementNext->setAttribute('visible', 'false');
        
        				//$elementCheckAndClose->setAttribute('fieldID', str_replace('ITKG', 'FILD', $field_id));
        				$behavior->setAttribute('type','checkAndClose');
        			}
        		}
        	}
        }
        	
        // si pas encore de champs deplaces dans cette page
        if(empty($aTabCurrent)) {
        	// tester si bouton sur la page cible
        	$target_page_has_no_buttons = true;
        	$elementButton = $this->domXpath->query("//page[@itkg_code='".$aField['page']."']/fieldSet/question/line/field/button")->item(0);
        
        	if ($elementButton != null) {
        		$target_page_has_no_buttons = false;
        	}
        		
        	// si la page cible n'a pas de bouton
        	if ($target_page_has_no_buttons == true) {
        		$elementNext = $this->domXpath->query("//page[@itkg_code='".$aField['page']."']/configuration/next")->item(0);
        		if ($elementNext != null) {
        			// get check and close listener
        			$elementCheckAndClose = $this->domXpath->query("//page[@itkg_code='".$aField['page']."']/fieldSet/question/line/field[@code='TECHNICAL_VALID_END_CAR' or @code='TECHNICAL_VALID_END_PDV']/listener/behavior[@type='checkAndClose']")->item(0)->parentNode;
        			$behavior = $this->domXpath->query("//page[@itkg_code='".$aField['page']."']/fieldSet/question/line/field[@code='TECHNICAL_VALID_END_CAR' or @code='TECHNICAL_VALID_END_PDV']/listener/behavior[@type='checkAndClose']")->item(0);
        			if ($elementCheckAndClose != null) {
        				// set attribute visible to true for configuration/next (display the button)
        				$elementNext->setAttribute('visible', 'true');
        
        				// desactivate the checkAndClose listener (for the fieldID attribute we replace FILD with ITKG)
        				$field_id = $elementCheckAndClose->getAttribute('fieldID');
        				//$elementCheckAndClose->setAttribute('fieldID', str_replace('FILD', 'ITKG', $field_id));
        				$behavior->setAttribute('type','enableDisableBehavior');
        			}
        		}
        			
        	}
        }
	}
	
	function addNode($TagCode,$code_line)
	{
		//var_dump("ajout du field : $TagCode");	

		//on récupére le field du générique		
		if(preg_match("/^html_/i",$TagCode))
		{
			$elementGeneric = $this->oXMLGeneric->domXpath->query("//html[@itkg_code='$TagCode']")->item(0);
		}else{
			$elementGeneric = $this->oXMLGeneric->domXpath->query("//field[@code='$TagCode']")->item(0);
		}

		if ($elementGeneric == null && preg_match("/^toggle_/i",$TagCode)) {
			$elementGeneric = $this->oXMLGeneric->domXpath->query("//toggle[@itkg_code='$TagCode']")->item(0);
		}
		
		
		if ($elementGeneric == null && preg_match("/^toggle_/i",$TagCode)) {
			$elementGeneric = $this->oXMLGeneric->domXpath->query("//toggle[@itkg_code='$TagCode']")->item(0);
		}
		
		if ($elementGeneric == null && substr($TagCode,0,9) == 'connector') {
			$elementGeneric = $this->oXMLGeneric->domXpath->query("//connector[@itkg_code='$TagCode']")->item(0);
		}
				
		//on importe le field du générique dans le dom de personnalisé
		$node = $this->dom->importNode($elementGeneric, true);
		
		$elementlinePerso = $this->domXpath->query("//line[@itkg_code='$code_line']")->item(0);
		
		if(is_null($elementlinePerso))
		{
			$elementLineGeneric = $elementGeneric->parentNode;
			$elementQuestionGenericItkgCode = $elementLineGeneric->parentNode->getAttribute('itkg_code');
			
			$elementQuestionStandard = $this->domXpath->query("//question[@itkg_code='$elementQuestionGenericItkgCode']")->item(0);
			
					
			$nodeLine = $this->dom->importNode($elementLineGeneric);
			$nodeLine->psaInsertChild($node);
				
				
			if(is_null($elementQuestionStandard))//si la question n'existe pas dans le standard, on l'importe du generic
			{
				$elementQuestionGeneric = $this->oXMLGeneric->domXpath->query("//question[@itkg_code='$elementQuestionGenericItkgCode']")->item(0);
				$elementFieldSetGenericCodeItkg = $elementQuestionGeneric->parentNode->getAttribute('itkg_code');
									
				$elementFieldSetStandard = $this->domXpath->query("//fieldSet[@itkg_code='$elementFieldSetGenericCodeItkg']")->item(0);
									
				$elementQuestionGenericImport = $this->dom->importNode($elementQuestionGeneric, false);
				$elementQuestionGenericImport->psaInsertChild($nodeLine);
				$elementFieldSetStandard->psaInsertChild($elementQuestionGenericImport);
				
			}else{
				$elementQuestionStandard->psaInsertChild($nodeLine);
			}
		
			
		}else{
			
			$elementlinePerso->psaInsertChild($node);
		}
		
	}
	
	function editField($TagCode,$Tag,$val)
	{
		//debug($this->dom->saveXML());
		//var_dump("edition du field : $TagCode");	
		$element = $this->domXpath->query("//".$Tag."[@itkg_code='$TagCode']")->item(0)->firstChild;

		if ($element == null) {
			$element = $this->domXpath->query("//".$Tag."[@itkg_code='$TagCode']")->item(0);
			$element->nodeValue = $val;
		} else {
			// ici un cdata et parfois des noeud de type domtext
			$domnodelist = $this->domXpath->query("//".$Tag."[@itkg_code='$TagCode']")->item(0)->childNodes;
			foreach ($domnodelist as $item) {
				// on ne modifie que le cdata
				if (get_class($item) == 'DOMCdataSection') {
					$item->replaceData(0,strlen($item->nodeValue),$val);
				} else {
					if ($Tag == 'html') {
						$child_cdata = $this->dom->createCDATASection($val);
						$this->domXpath->query("//".$Tag."[@itkg_code='$TagCode']")->item(0)->appendChild($child_cdata);
						$this->domXpath->query("//".$Tag."[@itkg_code='$TagCode']")->item(0)->removeChild($element);
					}
				}				
			}
		}
	}

	function editToggle($TagCode,$val,$element)
	{
		$node = $this->domXpath->query("//toggle[@itkg_code='$TagCode']/$element")->item(0);
		if($node && get_class($node->firstChild) == 'DOMCdataSection'){
			$node->firstChild->replaceData(0,strlen($node->firstChild->nodeValue),$val);
		}
	}
	
	function editTitleEtape($TagCode,$val)
	{
		//var_dump("Renomage de l'étape : $TagCode");	
		$elementEtape = $this->domXpath->query("//page[@itkg_code='$TagCode']")->item(0);
		$elementTitle = $this->domXpath->query("//page[@itkg_code='$TagCode']/title")->item(0);
		

		if($elementTitle)
		{
			if(empty($val))
			{
				$elementEtape->removeChild($elementTitle);
			}else{
				$element = $elementTitle->firstChild;
				$element->replaceData(0,strlen($element->nodeValue),$val);
			}
		}else{
			// etape existe
			if (! empty($elementEtape)) {
				$nodeTitle = $this->dom->createElement("title");
				$nodeTitle->appendChild($this->dom->createCDATASection($val));
			
				$elementEtape->psaInsertChild($nodeTitle);
			}
		}
	}
	
	function itemChange($TagCode,$aNewStructure)
	{
		//var_dump("referential change : $TagCode");	
		
		$elementRef = $this->domXpath->query("//referential[@itkg_code='$TagCode']")->item(0);
				
		if($elementRef->hasChildNodes())//on clonne tous les sous éléments et on les supprime du noeud, puis on regénére les éléments dans le nouvelle ordre.			
		{
			
			foreach ($elementRef->childNodes as $ichild=>$child){
				
				if ($child->tagName=='item')
				{
					$elementRef->removeChild($child);
				}
									
			}
		
			/*** on recréé les éléménents d'aprés la nouvelle structure ***/
				if(!empty($aNewStructure) && is_array($aNewStructure))
				{
					foreach ($aNewStructure as $kstructure=>$aStructure)
					{
																	
						$nodeItem = $this->dom->createElement("item");
	
						$nodeError = $this->dom->createElement("errorMessage");
						$nodeError->appendChild($this->dom->createCDATASection($val));
								
						$nodeRequired->appendChild($nodeError);
						$elementField->appendChild($nodeRequired);
						
					}
				}
			/******/
		}
		
		
	}
	
	function itemChangeForbidden($TagCode, $aNewStructure)
	{
		//var_dump("referential change : $TagCode");	
		
		$elementRef = $this->domXpath->query("//forbiddenDays[@itkg_code='$TagCode']")->item(0);

		// on supprime les noeuds existants
		if($elementRef->hasChildNodes())			
		{
			while ($elementRef->hasChildNodes()) {
			    $elementRef->removeChild($elementRef->firstChild);
			}
		}
		
		// on recréé les éléménents d'aprés la nouvelle structure
		if(!empty($aNewStructure) && is_array($aNewStructure))
		{
			foreach ($aNewStructure as $kstructure=>$aStructure)
			{		
				for ($zzz = 0; $zzz < count($aStructure['items']); $zzz++) {
					$nodeItem = $this->dom->createElement($kstructure);
					$nodeItem->appendChild($this->dom->createCDATASection($aStructure['items'][$zzz]['value']));
					
					$elementRef->appendChild($nodeItem);
				}
			
			}
		}
	}
	
	function editChoiceAttribute($field_itkg_code, $id, $tag, $val, $attribute_name) 
	{	
		// get the field
		$field   = $this->domXpath->query("//field[@itkg_code='$field_itkg_code']")->item(0);
		// get the item under this field
		$element = $this->domXpath->query(".//".$tag."[@id='$id']", $field)->item(0);			
		if($element)
		{
			$element->setAttribute($attribute_name, $val);
		}
		//echo "$field_itkg_code, $id, $tag, $val, $attribute_name\n<br/>";
		//var_dump($element->nodeValue);
		//var_dump($element->tagName . ' ' . $element->getAttribute('id') . ' resultat: ' . $element->getAttribute($attribute_name));
	}
	
	function editAttribute($tagCode,$tag,$val, $attribute_name) 
	{
		//var_dump("edition de l'attribut : ".$tagCode);
		
		$element = $this->domXpath->query("//".$tag."[@itkg_code='$tagCode']")->item(0);				
		$element->setAttribute($attribute_name, $val);
		
		if($element->parentNode->tagName == 'field')
		{
			$log_Code = $element->parentNode->getAttribute('itkg_code');
		}else{
			$element->parentNode->getAttribute('itkg_code');
		}
		
	}
	
	
	public function getXMLGeneric(){
    	/*récup du xml generique*/
    	
		if(substr($this->instance['id'],9,1)>0)
		{
			$code_instance_generic = substr_replace($this->instance['id'],'0',9,1);
		}else{
			$code_instance_generic = substr_replace($this->instance['id'],9,5,1);
			$code_instance_generic = substr_replace($code_instance_generic,'00',10,2);
			$code_instance_generic = substr_replace($code_instance_generic,'0',8,1);
			$code_instance_generic = substr_replace($code_instance_generic,'0',9,1);
		}
			
			
		
    	
    	
    	$oConnection = Pelican_Db::getInstance();
    	$aBind[':CODE_INSTANCE'] = $oConnection->strToBind($code_instance_generic);
    	
    	$sqlXML = "select bfv.FORM_XML_CONTENT 
    					   from #pref#_boforms_formulaire_version bfv
    					   INNER JOIN #pref#_boforms_formulaire bf ON (bf.FORM_INCE=bfv.FORM_INCE and bfv.FORM_VERSION=bf.FORM_CURRENT_VERSION )
    					   where bfv.FORM_INCE = :CODE_INSTANCE
    					   ";
    	
    	
    	$xml=$oConnection->queryItem($sqlXML,$aBind);
    	    	
    	if($xml)
    	{
    		return $xml;
    	}
    	
    	 
    	return false;
    }


    public function createNewQuestion($questionData, $questions) {
    	$fieldsetElement = $this->domXpath->query("//fieldSet[@itkg_code='" . $questionData['fieldSet'] . "']")->item(0);
    	
    	// creates new question
    	$newQuestionElement = $this->dom->createElement('question');
    	$newQuestionElement -> setAttribute('id', 'ITKG000000011111');
    	$newQuestionElement -> setAttribute('itkg_code', $questionData['question']);
    	
    	// search itkg_code of question after
    	$itkg_after = '';
    	$itkg_seen = false;
    	
    	foreach ($questions as $kquestions => $question) {
    		if ($itkg_seen) {
    			$itkg_after = $question['name'];
    			break;
    		} else if ($question['name'] == $questionData['question']) {
    			$itkg_seen = true;
    		} 
    	}
    	
		if ($itkg_after == '') {
			$fieldsetElement->appendChild($newQuestionElement );	
		} else {
			$questionAfterElement = $this->domXpath->query("//fieldSet[@itkg_code='" . $questionData['fieldSet'] . "']/question[@itkg_code='" . $itkg_after . "']")->item(0);
			$fieldsetElement->insertBefore($newQuestionElement, $questionAfterElement);
		}  	
		
    }
    
    public function deleteEmptyQuestions() {
    	$questions = $this->domXpath->query("//question[@id='ITKG000000011111']");
    	foreach ($questions as $quest => $question){ 
    		if (! $question->hasChildNodes()) {
				$parent = $question->parentNode;
				$parent->removeChild($question);
    		}
    	}
    }
    

    
	/*** connector node methods ***/
    
    function updateButtonNameForConnector($itkg_code_connector, $itkg_connector, $button_name) {
		$element = $this->domXpath->query("//connector[@itkg_code='$itkg_connector']/requestParameter/mapping[@itkg_code='$itkg_code_connector']")->item(0);
		$element->setAttribute('key', $button_name);
	}
	
	function addButtonNameForConnector($itkg_connector, $button_name) {
		$element = $this->domXpath->query("//connector[@itkg_code='$itkg_connector']/requestParameter")->item(0);
		
		$nodeMapping = $this->dom->createElement("mapping");
		$nodeMapping->setAttribute('code', 'text');
		$nodeMapping->setAttribute('key', $button_name);
		
		$element->psaInsertChild($nodeMapping);
	}

	// mets à jour le label d'un tag gtm 
	function updateTagGtmLabelForConnector($itkg_code_gtm_label, $str_new_label) {
		$element = $this->domXpath->query("//connector/requestParameter/gtm/tag/label[@itkg_code='$itkg_code_gtm_label']")->item(0);
		if ($element) {
			$element->nodeValue = $str_new_label;
		}
	}
	
	function deleteButtonNameForConnector($itkg_code_connector, $itkg_connector) {
		$element = $this->domXpath->query("//connector[@itkg_code='$itkg_connector']/requestParameter/mapping[@itkg_code='$itkg_code_connector']")->item(0);
		$parent = $element->parentNode;
		$parent->removeChild($element);
	}

	/*** connector email message ***/
	
	function updateEmailListenerParamMessage($itkg_email, $param_itkg, $param_value) {
		$element = $this->domXpath->query("//field[@itkg_code='$itkg_email']/listener/behavior/requestParameter/parameter[@itkg_code='$param_itkg']")->item(0);
		if ($element != null) {
			// fils a supprimer
			$domElemsToRemove = array(); 
			foreach($element->childNodes as $child) {
 			 	$domElemsToRemove[] = $child; 
			}
			
			// on supprime les fils
			foreach( $domElemsToRemove as $domElement ){
				$element->removeChild($domElement);
			}
						
			$element->appendChild($this->dom->createCDATASection($param_value));
		}
		//die($this->dom->saveXML());
	}

	
	/*** GESTION DU MASQUE POUR LES CHAMPS DE TYPE TEXTBOX ***/
	
	function deleteInputMask($itkg_code) {
		$parent = $this->domXpath->query("//field[@itkg_code='$itkg_code']/textbox")->item(0);
		if ($parent) {
			$child = $this->domXpath->query("//field[@itkg_code='$itkg_code']/textbox/inputmask")->item(0);
			if ($child) {
				$parent->removeChild($child);
			}
		}
	}
	
	function updateInputMask($itkg_code, $inputmask) {
		$element = $this->domXpath->query("//field[@itkg_code='$itkg_code']/textbox/inputmask")->item(0);
		if ($element != null) {
			$domnodelist = $element->childNodes;
			foreach ($domnodelist as $item) {
				// on ne modifie que le cdata
				if (get_class($item) == 'DOMCdataSection') {
					$item->replaceData(0,strlen($item->nodeValue), $inputmask);
				}
			}
		}		
	}
	
	function addInputMask($itkg_code, $inputmask) {
		$element = $this->domXpath->query("//field[@itkg_code='$itkg_code']/textbox")->item(0);
		if ($element != null) {
			// creates input mask and cdata
			$nodeInputMask = $this->dom->createElement('inputmask');
			$child_cdata = $this->dom->createCDATASection($inputmask);
			$nodeInputMask->appendChild($child_cdata);
			
			$element->psaInsertChild($nodeInputMask);
		}
	}
					
	function editHiddenField($code) {
		
		$elementPerso = $this->domXpath->query("//field[@code='$code']")->item(0);
		
		$elementGene = $this->oXMLGeneric->domXpath->query("//field[@code='$code']")->item(0);
		$node = $this->dom->importNode($elementGene, true);

		$elementPerso->parentNode->replaceChild($node,$elementPerso);

	}

	function updateConfigurationButtonLabel($page_itkg, $config_name, $new_value) {
    	if (trim($new_value) == '') {
    		return;	
    	}
    	
		$elementConfiguration = $this->domXpath->query("//page[@itkg_code='$page_itkg']/configuration")->item(0);
		if ($elementConfiguration) {    	
			$element = $this->domXpath->query("//page[@itkg_code='$page_itkg']/configuration/$config_name")->item(0);
			if ($element) {
				$element->setAttribute('label', $new_value);
			} else {
				// create new node
				$nodeElement = $this->dom->createElement($config_name);
				$nodeElement->setAttribute('label', $new_value);
				$nodeElement->setAttribute('visible', 'false'); 
				 
				$elementConfiguration-> psaInsertChild($nodeElement);
			}
		}
    }
	
	
	function updateFormCommentary($old_comment, $new_comment, $commentary_visible) {
		$elementForm = $this->domXpath->query("//form")->item(0);
		$elementCommentary = $this->domXpath->query("//form/commentary")->item(0);

		if($elementCommentary)
		{
			if ($old_comment != $new_comment) {
				// remove no cdata child nodes
				$domnodelist = $elementCommentary->childNodes;
				$domElemsToRemove = array(); 
				foreach ($domnodelist as $item) {
					if (get_class($item) != 'DOMCdataSection') {
						 $domElemsToRemove[] = $item; 
					}
				}
				
				foreach( $domElemsToRemove as $domElement ){
					$domElement->parentNode->removeChild($domElement);
				} 
								
				// gets first child
				$element = $elementCommentary->firstChild;
								
				if (empty($element)) {
					if (! empty($new_comment)) {
						$elementCommentary -> appendChild($this->dom->createCDATASection($new_comment));
					}
				} else {	
					if(empty($new_comment))
					{
						$elementCommentary->removeChild($element);
					}else{
						$element->replaceData(0,strlen($element->nodeValue), $new_comment);			
					}
				}
			}
		} else {
			// create the commentary element under form
			if (! empty($elementForm)) {
				$nodeCommentary = $this->dom->createElement("commentary");
				$elementForm -> psaInsertChild($nodeCommentary);
				
				$elementCommentary = $this->domXpath->query("//form/commentary")->item(0);
				if (! empty($new_comment)) {
					$elementCommentary -> appendChild($this->dom->createCDATASection($new_comment));
				}				
			}			
		}
		
		if ($elementCommentary) {
			if ($commentary_visible == '') {
				$commentary_visible = 'false';
			}
			$elementCommentary->setAttribute('visible', $commentary_visible);
		}
		
	}

	// PATCH FOR JIRA 710
	public function getReferentialChoiceValuesForItkgCode($itkg_code, $field_type = 'dropdown') {
		$domnodelist = $this->domXpath->query("//field[@itkg_code='$itkg_code']/$field_type/referential/item");

		// because we can switch between radio and dropdown ! 
		if ($domnodelist->length == 0) {
			$other_type = ($field_type == 'dropdown') ? 'radio' : 'dropdown';			
			$domnodelist = $this->domXpath->query("//field[@itkg_code='$itkg_code']/$other_type/referential/item");
		}
		
		$tbl_choices = array();
		foreach ($domnodelist as $item) {
			if ($item->hasAttribute('id') && $item->hasAttribute('selected')) {
				$tbl_choices[] = array(
		            'choiceLabel' => $item->nodeValue,
		            'choice' => $item->nodeValue,
		            'id' => $item->getAttribute('id'),
		            'selected' => $item->getAttribute('selected')
				);
			}
		}
		
		return $tbl_choices;			
	}

	public function updateReferentialValues($itkg_code, $items, $field_type = 'dropdown') {
		$referential_element = $this->domXpath->query("//field[@itkg_code='$itkg_code']/$fieldtype/referential")->item(0);
		if ($referential_element) {
			// removes child nodes
			while($referential_element->hasChildNodes()) {
  				$referential_element->removeChild($referential_element->childNodes->item(0));
			}
			
			// add new ones
			// <item id="REEY100000003277" selected="false">Mr</item>
			for ($i = 0; $i < count($items); $i++) {
				$nodeItem = $this->dom->createElement('item');
				$nodeItem->setAttribute('id', $items[$i]['id']);
				$nodeItem->setAttribute('selected', 'false');
				$nodeItem->psaInsertChild($this->dom->createCDATASection($items[$i]['label']));//appendChild
				
				$referential_element->psaInsertChild($nodeItem); // appendChild
			}
		}
	}
	
	public function getInfosFromReferentialFromId($itkg_code, $field_type, $choice_id) {
		$element = $this->domXpath->query("//field[@itkg_code='$itkg_code']/$fieldtype/referential/item[@id='$choice_id']")->item(0);
		if ($element) {
			return array('label' => $element->nodeValue, 'selected' => $element->getAttribute('selected'));
		} 
		return array('label' => '', 'selected' => 'false');		
	}

	// gets global page error
	public function getPageErrorTag($bLP) {
		// pageError path is different for LP
		$path_plus = ($bLP) ? '/fieldSet/question' : '';
			
		$element = $this->domXpath->query("//form/page[@itkg_code='page_1']" . $path_plus ."/pageError/label")->item(0);
		if ($element) {
			return $element->nodeValue; 
		}
		return '';
	}

	public function updatePageErrorLabel($pageErrorLabel, $bLP) {
		// pageError path is different for LP
		$path_plus = ($bLP) ? '/fieldSet/question' : '';
		
		if ($pageErrorLabel == '') {
			// removes
			$element = $this->domXpath->query("//form/page[@itkg_code='page_1']" . $path_plus ."/pageError")->item(0);
			if ($element) {
				$element->parentNode->removeChild($element);
			}
		} else {
			// updates
			$elementParent = $this->domXpath->query("//form/page[@itkg_code='page_1']" . $path_plus)->item(0);
			$elementParentError = $this->domXpath->query("//form/page[@itkg_code='page_1']" . $path_plus ."/pageError")->item(0);
			$elementParentErrorLabel = $this->domXpath->query("//form/page[@itkg_code='page_1']" . $path_plus ."/pageError/label")->item(0);
			
			if ($elementParentErrorLabel) {
				$elementParentErrorLabel->nodeValue = $pageErrorLabel;
			} else {	
				if ($elementParentError) {
					$elementParent->removeChild($elementParentError);
				}
				
				// recreates the tag pageError
				$nodeItemLabel = $this->dom->createElement('label');
				$nodeItemLabel->nodeValue = $pageErrorLabel;
				$nodeItemPage = $this->dom->createElement('pageError');
				$nodeItemPage->psaInsertChild($nodeItemLabel);
				$elementParent->psaInsertChild($nodeItemPage);
			}
		}
	}
	
	function updateTagsUnderQuestionLabel($page_itkg, $tags) {
		$gtm_nodes =  $this->domXpath->query("//form/page[@itkg_code='" . $page_itkg . "']/fieldSet/question/requestParameter/gtm"); 

		foreach ($gtm_nodes as $unGtm) {
			$tag_nodes = $unGtm->childNodes;
			foreach($tag_nodes as $unTag) {
				if ($unTag->tagName == 'tag') {
        			$nodeName = $unTag->getElementsByTagName('name')->item(0);
        			$nodeCategory = $unTag->getElementsByTagName('category')->item(0);
					$nodeAction = $unTag->getElementsByTagName('action')->item(0);
        			$nodeLabel = $unTag->getElementsByTagName('label')->item(0);
        			
					        				
					for ($j = 0; $j < count($tags); $j++) {
						// tag found !
						if ($tags[$j]['name']     == $nodeName->nodeValue && 
							$tags[$j]['category'] == $nodeCategory->nodeValue && 
							$tags[$j]['action']   == $nodeAction->nodeValue) {
							
							// we update the label
							if ($tags[$j]['label'] == '') {
								if ($nodeLabel) {
									// on supprime le label
									$unTag->removeChild($nodeLabel);		
								}
							} else {
								// on modifie le label
								if ($nodeLabel) {
									$nodeLabel->nodeValue = $tags[$j]['label'];
								} else {
									// on ajoute le label
									$nodeItemLabel = $this->dom->createElement('label');
									$nodeItemLabel->nodeValue = $tags[$j]['label'];
									$unTag->psaInsertChild($nodeItemLabel);
								}
							}
						}		
					}
				}
			}
		}
	}
	
}


?>
