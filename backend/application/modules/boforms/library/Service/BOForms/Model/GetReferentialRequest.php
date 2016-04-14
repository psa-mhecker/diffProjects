<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetReferentialRequest
 */
class Plugin_BOForms_Model_GetReferentialRequest extends BaseModel
{

	protected $referentialType;
	

	/**
	 *
	 */

	
	public function __toXML()
	{
		
		
		return '<bof:getReferential xmlns:bof="http://xml.inetpsa.com/Services/bend/BOFormService">
					 <bof1:getReferentialRequest xmlns:bof1="http://com/inetpsa/dcr/xml/services/bend/boformservice">
						<bof1:userId>'. $_SESSION[APP]['user']['id'] . '</bof1:userId>	
					 	<bof1:referentialType>'.$this->referentialType.'</bof1:referentialType>
					 </bof1:getReferentialRequest>
				  </bof:getReferential>';
	}

	/**
	 *
	 */
	public function __toLog()
	{
		return ' Request : ';
	}

}
