<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetReportingRequest
 */
class Plugin_BOForms_Model_GetReportingRequest extends BaseModel
{

	protected $brand;
	protected $siteCode;
	protected $country;
	protected $opportunityType;
	protected $contexte;
	protected $culture;
	protected $customerType;
	protected $salePoint;
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

		$return = "	<bof:getReporting xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:bof='http://xml.inetpsa.com/Services/bend/BOFormService' xmlns:bof1='http://com/inetpsa/dcr/xml/services/bend/boformservice'>
				 <bof1:getReportingRequest>
				 	<bof1:userId>". $_SESSION[APP]['user']['id'] . "</bof1:userId>   
				 	<bof1:brand>".$this->brand."</bof1:brand>
				    <bof1:siteCode>".$this->siteCode."</bof1:siteCode>
				    <bof1:country>".$this->country."</bof1:country>
				    <bof1:opportunityType>".$this->opportunityType."</bof1:opportunityType>
				    <bof1:contexte>".$this->contexte."</bof1:contexte>
				    <bof1:culture>".$this->culture."</bof1:culture>
				    <bof1:customerType>".$this->customerType."</bof1:customerType>
				    <bof1:salePoint>".$this->salePoint."</bof1:salePoint>
				    <bof1:dateStart>".$this->dateStart."</bof1:dateStart>
				    <bof1:dateEnd>".$this->dateEnd."</bof1:dateEnd>
				 </bof1:getReportingRequest>
			      </bof:getReporting>";

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
