<?php 

class PsaDomElement extends DOMElement {
    protected $sequences = array(
	    "datepicker" => array(
	    	"defaultValue", "value",    "dateStart","dateEnd","openingStart",  "openingEnd",    "libeletEnumeration", "dayEnumeration", "monthEnumeration","forbiddenDays","hourlabel","format"),
	    
	    "page" => array(
	    	"gtm", "configuration",  "order", "title", "explanation", "listener", "fieldSet", "pageError" 
	     ),
	
	    "configuration" => array(
	     	"next", "previous", "synthesis" 
	     ), 
	     
	    "fieldSet" => array(
	    	"title",  "explanation", "order", "listener", "question"
	    ),
	    	
	    "question" => array(
	    	"label",  "explanation", "order", "pageError", "line", "requestParameter", "listener", "gtm"
	    ),
	    
	    		
	    "line" => array(
	    	"order",  "explanation", "help", "listener","field","connector","link","html","toggle", "gtmonload", "gtmonvalidate", "pageError","popin"
	    ),
	    		
	    "field" => array(
	    	"order","help","textbox","checkbox","radio","dropDownList","dropdown","hidden","password","textarea","file","datepicker","richTextEditor","slider","captcha","colorpicker","panelPicker","button","date","label","listener","rule","requestParameter","required","alternative"
	    ),
	    
	    "textbox" => array(
	    	"defaultValue",  "value", "keyboard", "inputmask"
	    ),
	    "date" => array(
	    	"defaultValue",  "value", "format"
	    ),
	    "textarea" => array(
	    	"defaultValue",  "value", "keyboard"
	    ),
	    "richTextEditor" => array(
	    	"defaultValue",  "value"
	    ),
	  	"richTextEditor" => array(
	    	"defaultValue",  "value"
	    ),
	    "connector" => array(
	    	"help", "requestParameter"
	    ),
	    "form" => array(
	    	"name","commentary", "page"
	    ),
	    "requestParameter" => array(
	    	"mapping", "gtm"
	    ),
	    "tag" => array(
	    	'name', 'category', 'action', 'label'
	    )
    );
    
	
    function psaInsertChild($node) {
    	$child_tag_name = $node->tagName;
    	if (isset($this->sequences[$this->tagName])) {
			// decoupe du tableau
			$tbl_after = array();
			$before = true;
			for ($i = 0; $i < count($this->sequences[$this->tagName]); $i++ ) {
				if ($this->sequences[$this->tagName][$i] == $child_tag_name) {
					$before = false;
				} else {
					if ($before == false) {
						$tbl_after[] = $this->sequences[$this->tagName][$i];	
					}
				}
			}	
		
			if (count($tbl_after) == 0 || $this->hasChildNodes() == false) {
				$this->appendChild($node)	;
			} else {
				$node_after = $this->getNextChildFromTagName($tbl_after);
				if ($node_after == null) {
					$this->appendChild($node);
				} else {
					$this->insertBefore($node, $node_after);
				}
			}
			
    	} else {
    		$this->appendChild($node);
    	}
    }
    
    
	
	function getNextChildFromTagName($tbl_after) { 
      if ($this->hasChildNodes ()) {
      	  	$children = $this->childNodes;
      	  	foreach ($children as $child) {	
      	  		if ($child->tagName != '') {
      	  			if (in_array($child->tagName, $tbl_after)) {
		        		return $child;
	        		}
      	  		}
        	}
        	return null;
      }
      
      return null;
   }
   
}

?>