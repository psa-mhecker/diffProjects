<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GeoLocalizeRequest
 */
class Plugin_BOForms_Model_GeoLocalizeRequest extends BaseModel
{

	protected $brand;
    protected $consumer;
    protected $country;
    protected $culture;
    protected $place;
	
	public function __toXML()
	{
		return "<tem:GeoLocalize xmlns:tem=\"http://tempuri.org/\" xmlns:psa=\"http://schemas.datacontract.org/2004/07/Psa.Dsw.Wcf\">
         	<tem:parameters>
            <psa:Brand>" . $this->brand . "</psa:Brand>
            <psa:Consumer>" . $this->consumer . "</psa:Consumer>
            <psa:Country>" . $this->country . "</psa:Country>
            <psa:Culture>" . $this->culture . "</psa:Culture>
            <psa:Place>" . $this->place . "</psa:Place>
         </tem:parameters>
      </tem:GeoLocalize>";
		
	}

	/**
	 *
	 */
	public function __toLog()
	{
		return ' Request : ';
	}

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
	
}
