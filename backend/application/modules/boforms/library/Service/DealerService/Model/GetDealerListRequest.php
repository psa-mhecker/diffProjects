<?php

//namespace Plugin\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetDealerListRequest
 */
class Plugin_BOForms_Model_GetDealerListRequest extends BaseModel
{
	protected $brand;
    protected $consumer;
    protected $country;
    protected $culture;
    protected $latitude;
	protected $longitude;
	protected $sort;
	
	public function __toXML()
	{
		return "<tem:GetDealerList xmlns:tem=\"http://tempuri.org/\" xmlns:psa=\"http://schemas.datacontract.org/2004/07/Psa.Dsw.Wcf\">
         <tem:parameters>
            <psa:Brand>" . $this->brand . "</psa:Brand>
            <psa:Consumer>" . $this->consumer . "</psa:Consumer>
            <psa:Country>" . $this->country . "</psa:Country>
            <psa:Culture>" . $this->culture . "</psa:Culture>
            <psa:Latitude>" . $this->latitude . "</psa:Latitude>
            <psa:Longitude>" . $this->longitude . "</psa:Longitude>          
            <psa:ResultMax>7</psa:ResultMax>
            <psa:Sort>" . $this->sort . "</psa:Sort>     
         </tem:parameters>
      </tem:GetDealerList>";
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
			
					'country' => $this->country,
					'brand' => $this->brand
				
			
		);
		return $aParams;
	}*/
	
}
