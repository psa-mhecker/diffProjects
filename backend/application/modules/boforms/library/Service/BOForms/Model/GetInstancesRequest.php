<?php

//namespace Plugin\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GeoLocalizeRequest
 */
class Plugin_BOForms_Model_GetInstancesRequest extends BaseModel
{
	protected $country;
	protected $brand;

	/**
	 *
	 */
	/*public function __toRequest()
	{
		$aParams = array(
			
					'country' => $this->country,
					'brand' => $this->brand
				
			
		);
		return $aParams;
	}*/
	
	public function __toXML()
	{
		
		return "
			     <bof:getInstances xmlns:bof='http://xml.inetpsa.com/Services/bend/BOFormService'>
			     <bof1:getInstancesRequest xmlns:bof1='http://com/inetpsa/dcr/xml/services/bend/boformservice'>
			     	<bof1:userId>". $_SESSION[APP]['user']['id'] . "</bof1:userId>   
			     	<bof1:country>".$this->country."</bof1:country>
			        <bof1:brand>".$this->brand."</bof1:brand>
			    </bof1:getInstancesRequest>
			</bof:getInstances> 
			  ";
	}

	/**
	 *
	 */
	public function __toLog()
	{
		return ' Request : ';
	}

}
