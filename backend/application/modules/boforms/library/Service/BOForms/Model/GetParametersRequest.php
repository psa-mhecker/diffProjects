<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetParametersRequest
 */
class Plugin_BOForms_Model_GetParametersRequest extends BaseModel
{
	protected $profileType;
	protected $culture;
	//protected $si;
	protected $informationSystem;
	
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
		$return = "<bof:getParameters xmlns:bof=\"http://xml.inetpsa.com/Services/bend/BOFormService\">
         			<bof1:getParametersRequest xmlns:bof1=\"http://com/inetpsa/dcr/xml/services/bend/boformservice\">
            			<bof1:userId>". $_SESSION[APP]['user']['id'] . "</bof1:userId>	
         				<bof1:profileType>" . $this->profileType . "</bof1:profileType>
           				<bof1:culture>" . $this->culture . "</bof1:culture>
           				<bof1:informationSystem>" . $this->informationSystem . "</bof1:informationSystem>
					</bof1:getParametersRequest>
      			</bof:getParameters>";

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
