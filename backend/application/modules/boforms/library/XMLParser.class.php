<?php
class XMLParser
{
    var $rawXML;
    var $keyArray = array();
    var $parsed = array();
    var $index = 0;
    var $attribKey = 'attributes';
    var $valueKey = 'value';
    var $cdataKey = 'cdata';
    var $isError = false;
    var $error = '';

    function XMLParser($xml = NULL)
    {
        $this->rawXML = $xml;
       	
    }

    function parse($xml = NULL)
    {
        if (!is_null($xml))
        {
            $this->rawXML = $xml;
        }

        $this->isError = false;
           
        if (!$this->parse_init())
        {
            return false;
        }
              
		
        $this->index = 0;
        $this->parsed = $this->parse_recurse();
        $this->status = 'parsing complete';

        return $this->parsed;
    }

    function parse_recurse()
    {       
        $found = array();
        $tagCount = array();
        $ite=0;

        while (isset($this->valueArray[$this->index]))
        {
            $tag = $this->valueArray[$this->index];
            $this->index++;

            
            if ($tag['tag'] == 'field' && $tag['type']=='open')
            {
             // var_dump($tag);
            }
            
            if ($tag['type'] == 'close')
            {
                return $found;
            }

            if ($tag['type'] == 'cdata')
            {
                $tag['tag'] = $this->cdataKey;
                $tag['type'] = 'complete';
            }

            $tagName = $tag['tag'];

            if (isset($tagCount[$tagName]))
            {       
                if ($tagCount[$tagName] == 1)
                {
                    $found[$tagName] = array($found[$tagName]);
                }
                   
                $tagRef =& $found[$tagName][$tagCount[$tagName]];
                $tagCount[$tagName]++;
            }
            else   
            {
                $tagCount[$tagName] = 1;
                $tagRef =& $found[$tagName];
            }

            switch ($tag['type'])
            {
                case 'open':
                    $tagRef = $this->parse_recurse();
                                       
                    if (isset($tag['attributes']))
                    {
                        $tagRef[$this->attribKey] = $tag['attributes'];
                    }
                       
                    if (isset($tag['value']))
                    {
                        if (isset($tagRef[$this->cdataKey]))   
                        {
                            $tagRef[$this->cdataKey] = (array)$tagRef[$this->cdataKey];   
                            array_unshift($tagRef[$this->cdataKey], $tag['value']);
                        }
                        else
                        {
                            $tagRef[$this->cdataKey] = $tag['value'];
                        }
                    }
                    
                    if(!empty($tagRef['field']))
                    {
                           /*  echo "<pre>";
							print_r($tagRef);
							echo "</pre>";*/
							
						/*	$aTemp[$ite]=$tagRef['field'];
							  echo "<pre>";
							print_r($aTemp);
							echo "</pre>";
							$ite++;*/
                    }
                    
                    break;

                case 'complete':
                    if (isset($tag['attributes']))
                    {
                        $tagRef[$this->attribKey] = $tag['attributes'];
                        $tagRef =& $tagRef[$this->valueKey];
                    }

                    if (isset($tag['value']))
                    {
                        $tagRef = $tag['value'];
                    }
                    break;
            }           
        }
              
        
        return $found;
    }

    function parse_init()
    {
        $this->parser = xml_parser_create();

        $parser = $this->parser;
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);    
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);       
        if (!$res = (bool)xml_parse_into_struct($parser, $this->rawXML, $this->valueArray, $this->keyArray))
        {
            $this->isError = true;
            $this->error = 'error: '.xml_error_string(xml_get_error_code($parser)).' at line '.xml_get_current_line_number($parser);
        }
        xml_parser_free($parser);

        return $res;
    }
    
    
    
   
      
        
    
}


   	  
   


function ArrayToFile($aTab,$aTagCDATA) {
     	/*echo "<pre>";
		print_r($aTab);
		echo "</pre>";*/
	    
		// Création d'un nouvel objet document
	    $dom = new DomDocument('1.0', 'utf-8');
	 
	    // Création de l'élément racine
	    $root = $dom->createElement(key($aTab));
	    $dom->appendChild($root);
	 
      	
	    // appel d'une fonction récursive qui construit l'élément XML
	    // à partir de l'objet, en parcourant tout l'arbre de l'objet.
	    setElement($dom, $aTab[key($aTab)], $root, $aTagCDATA);
	 
	    // Mise à jour du fichier source original
	    
	    debug($dom->saveXML());
	    /* echo "<pre>";
		print_r($dom->saveXML());
		echo "</pre>";*/
	    //$dom->save($xmlObject->source);
	   // echo $xmlObject->source;
  	}
  	
function setElement($dom_document, $object_element, $dom_element, $aTagCDATA) {  	
	
	//var_dump($object_element);
	
	foreach ($object_element as $tagName => $tagValue)
	{
		
				
		if($tagName==='attributes'){
			
			foreach ($tagValue as $attName=>$attValue){
				$dom_element->setAttribute($attName, $attValue);
			}
		
		}/*elseif (is_int($tagName)){	
				
			
			foreach ($tagValue as $soustagName=>$soustagValue){

				$souschild = $dom_document->createElement($soustagName);
	    		$souschild = $dom_element->appendChild($souschild);
							
			}
			
		}*/else{
			    	
	    	if(is_array($tagValue))
	    	{
	    		
	    		if (is_int(key($tagValue))){
	    			foreach ($tagValue as $fooTag=>$aTag){
	    					
	    				unset($child);	
	    				$child = $dom_document->createElement($tagName);
	    				$child = $dom_element->appendChild($child);	
						/*$souschild = $dom_document->createElement($soustagName);
				    	$souschild = $child->appendChild($souschild);*/
				    	
						setElement($dom_document,$aTag,$child,$aTagCDATA);
						
				    	/*if(is_array($aTag))
				    	{
				    		setElement($dom_document,$aTag,$child,$aTagCDATA);
				    	}else{
				    		var_dump($tagName);
				    		$text = $dom_document->createTextNode($sousTagValue);
							$souschild->appendChild($text);
				    	}*/
						
					}
	    		}else{
	    			
	    			$child = $dom_document->createElement($tagName);
	    			$child = $dom_element->appendChild($child);
	    			setElement($dom_document,$tagValue,$child,$aTagCDATA);
	    		}
	    		
	    		
	    	}elseif (is_string($tagValue) || is_int($tagValue)){
	    		
	    		$child = $dom_document->createElement($tagName);
		    	$child = $dom_element->appendChild($child);		    	
	    		
	    		if(in_array($tagName,$aTagCDATA)){
	    			$ct = $dom_document->createCDATASection($tagValue);
       				$child->appendChild($ct);
	    		}else {
	    				
		    		$text = $dom_document->createTextNode($tagValue);
					$child->appendChild($text);
	    		}
	    		
	    		
	    	}
		}
	}
	
}	
?>