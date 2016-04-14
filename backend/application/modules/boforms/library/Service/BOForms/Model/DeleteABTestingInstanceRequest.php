<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe DeleteABTestingInstanceRequest
 */
class Plugin_BOForms_Model_DeleteABTestingInstanceRequest extends BaseModel
{
	protected $instanceId;
		
	public function __toXML()
	{
		$return = "<bof:deleteABTestingInstance xmlns:bof='http://xml.inetpsa.com/Services/bend/BOFormService'>
	         <bof1:deleteABTestingInstanceRequest  xmlns:bof1='http://com/inetpsa/dcr/xml/services/bend/boformservice'>
	         	<bof1:userId>". $_SESSION[APP]['user']['id'] . "</bof1:userId>   
	         	<bof1:instanceId>" . $this->instanceId . "</bof1:instanceId>
	         </bof1:deleteABTestingInstanceRequest>
	     </bof:deleteABTestingInstance>";
   	
		return $return;
	}

	/**
	 *
	 */
	public function __toLog()
	{
		return ' Request : ';
	}

}
