<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetMastersRequest
 */
class Plugin_BOForms_Model_GetMastersRequest extends BaseModel
{

	protected $brand;

	public function __toXML()
	{
		return "<bof:getMasters xmlns:bof=\"http://xml.inetpsa.com/Services/bend/BOFormService\" >
         			<bof1:getMastersRequest xmlns:bof1=\"http://com/inetpsa/dcr/xml/services/bend/boformservice\">
            			<bof1:userId>". $_SESSION[APP]['user']['id'] . "</bof1:userId>	
         				<bof1:brand>" . $this->brand . "</bof1:brand>
			        </bof1:getMastersRequest>
			    </bof:getMasters>";
		
	}

	public function __toLog()
	{
		return ' Request : ';
	}

}
