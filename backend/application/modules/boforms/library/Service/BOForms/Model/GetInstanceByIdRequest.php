<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetInstanceByIdRequest
 */
class Plugin_BOForms_Model_GetInstanceByIdRequest extends BaseModel
{

	protected $instanceId;
	

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
		return "<bof:getInstanceById xmlns:bof='http://xml.inetpsa.com/Services/bend/BOFormService'>
         	<bof1:getInstanceByIdRequest xmlns:bof1='http://com/inetpsa/dcr/xml/services/bend/boformservice'>
           		<bof1:userId>". $_SESSION[APP]['user']['id'] . "</bof1:userId> 	
         		<bof1:instanceId>".$this->instanceId."</bof1:instanceId>
         	</bof1:getInstanceByIdRequest>
     	 </bof:getInstanceById>";
	}

	/**
	 *
	 */
	public function __toLog()
	{
		return ' Request : ';
	}

}
