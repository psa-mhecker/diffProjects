<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe UpdateParametersRequest
 */
class Plugin_BOForms_Model_UpdateParametersRequest extends BaseModel
{

	protected $parameters; // contains a list of "interfaceSi" values
	protected $parameterInfo; // contains for each "interfaceSi" value, all the parameterInfo values  
	protected $mappingSiName; // mapping interfaceSi / interfaceSiName
	
	/**
	 *
	 */
	/*public function __toRequest()
	{
		$aParams = array(
			'parameters' => urlencode(json_encode(
				array(
					'instanceId' => $this->instanceId,
				))
			)
		);
		return $aParams;
	}*/
	
	// &gt is not managed (could be replaced if necessary)
	public function replaceSpecialXmlChars($s) {
		return str_replace(array('&','<','"'), array('&amp;','&lt;','&quot;'), $s);
	}
	
	public function __toXML()
	{
		$result = "<bof:updateParameters xmlns:bof=\"http://xml.inetpsa.com/Services/bend/BOFormService\">
         <bof1:updateParametersRequest xmlns:bof1=\"http://com/inetpsa/dcr/xml/services/bend/boformservice\">
         	<bof1:userId>". $_SESSION[APP]['user']['id'] . "</bof1:userId>   
         	<bof1:parameters>";
            
        for ($i = 0; $i < count($this->parameters); $i++) {
        	$interfaceSi = $this->parameters[$i];
        	
           	$result .= "<bof1:parameter><bof1:interfaceSi>" . $this->replaceSpecialXmlChars($interfaceSi). "</bof1:interfaceSi>";
           	$result .= '<bof1:interfaceSiName>' . $this->replaceSpecialXmlChars($this->mappingSiName[$interfaceSi]) . '</bof1:interfaceSiName>';

           	$result .= "<bof1:parametersInfo>";
           	if (isset($this->parameterInfo[$interfaceSi])) {
           	   for ($z = 0; $z < count($this->parameterInfo[$interfaceSi]); $z++) {
			   		$result .= "<bof1:parameterInfo>
	            		            <bof1:parameterName>" . $this->parameterInfo[$interfaceSi][$z]['name'] . "</bof1:parameterName>
	                        		<bof1:parameterValue>" . $this->replaceSpecialXmlChars($this->parameterInfo[$interfaceSi][$z]['value']) . "</bof1:parameterValue>
	                     		 	</bof1:parameterInfo>";
			   }
           }
		   $result .= "</bof1:parametersInfo></bof1:parameter>";	
        }    
      	$result .= "</bof1:parameters></bof1:updateParametersRequest></bof:updateParameters>";

      	return $result;
	}

	/**
	 *
	 */
	public function __toLog()
	{
		return ' Request : ';
	}

}
