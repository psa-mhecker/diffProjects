<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetLeadsByTypeRequest
 */
class Plugin_BOForms_Model_GetLeadsByTypeRequest extends BaseModel
{

	protected $country;
	protected $brand;
	protected $dateStart;
	protected $dateEnd;


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
	
	public function __toXML()
	{
		return "<bof:getLeadsByType xmlns:bof=\"http://xml.inetpsa.com/Services/bend/BOFormService\" >
			 <bof1:getLeadsByTypeRequest xmlns:bof1=\"http://com/inetpsa/dcr/xml/services/bend/boformservice\">
			 	<bof1:userId>". $_SESSION[APP]['user']['id'] . "</bof1:userId>   
			 	<bof1:country>".$this->country."</bof1:country>
			    <bof1:brand>".$this->brand."</bof1:brand>
			    <bof1:dateStart>".$this->dateStart."</bof1:dateStart>
			    <bof1:dateEnd>".$this->dateEnd."</bof1:dateEnd>
			 </bof1:getLeadsByTypeRequest>
		      </bof:getLeadsByType>";
		
	}

	/**
	 *
	 */
	public function __toLog()
	{
		return ' Request : ';
	}

}
