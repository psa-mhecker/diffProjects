<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetInstancesByMasterRequest
 */
class Plugin_BOForms_Model_GetInstancesByMasterRequest extends BaseModel
{

	protected $brand;
	protected $master;
	protected $architecture;

	public function __toXML()
	{
		return "
		      <bof:getInstancesByMaster xmlns:bof=\"http://xml.inetpsa.com/Services/bend/BOFormService\">
		         <bof1:getInstancesByMasterRequest xmlns:bof1=\"http://com/inetpsa/dcr/xml/services/bend/boformservice\">
		         	<bof1:userId>". $_SESSION[APP]['user']['id'] . "</bof1:userId>   
		         	<bof1:brand>" . $this->brand ."</bof1:brand>
		            <bof1:language>".$this->master."</bof1:language>
		            <bof1:architecture>".$this->architecture."</bof1:architecture>
		         </bof1:getInstancesByMasterRequest>
		      </bof:getInstancesByMaster>
		
		";
		
	}

	public function __toLog()
	{
		return ' Request : ';
	}

}
