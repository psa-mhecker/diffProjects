<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe DuplicateInstanceRequest
 */
class Plugin_BOForms_Model_DuplicateInstanceRequest extends BaseModel
{
	protected $masterId;
	protected $country;
	protected $language;
	protected $applicationCode;
		
	public function __toXML()
	{
		$return = "<bof:duplicateInstance xmlns:bof='http://xml.inetpsa.com/Services/bend/BOFormService'>
         <bof1:duplicateInstanceRequest xmlns:bof1='http://com/inetpsa/dcr/xml/services/bend/boformservice'>
            <bof1:userId>". $_SESSION[APP]['user']['id'] . "</bof1:userId>
         	<bof1:instanceId>".$this->masterId."</bof1:instanceId>
            <bof1:country>".$this->country."</bof1:country>
            <bof1:language>".$this->language."</bof1:language>
            <bof1:applicationCode>".$this->applicationCode."</bof1:applicationCode>
         </bof1:duplicateInstanceRequest>
      	</bof:duplicateInstance>";
   		
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
