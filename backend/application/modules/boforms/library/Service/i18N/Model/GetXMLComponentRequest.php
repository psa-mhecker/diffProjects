<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetInstanceByIdRequest
 */
class Plugin_I18N_Model_GetXMLComponentRequest extends BaseModel
{
				protected $brand;
				protected $country;
				protected $culture;
				protected $component;
	
	

	/**
	 *
	 */
	 
	 public function getBrand()
	 {
		return $this->brand;
	 }
	 
	 public function getCountry()
	 {
		return $this->country;
	 }
	 
	 public function getCulture()
	 {
		return $this->culture;
	 }
	 
	 public function getComponent()
	 {
		return $this->component;
	 }
	 
	 
	 /*
	public function __toRequest()
	{
		$aParams = array(
					'brand' => $this->brand,
					'country' => $this->country,
					'culture' => $this->culture,
					'component' => $this->component
			);
		
		return $aParams;
	}*/
	
	public function __toXML()
	{
		return '
		
		<bof:getXMLComponent xmlns:bof="http://dpdcr.citroen.integ.inetpsa.com/boforms">
		<bof1:getXMLComponentRequest xmlns:bof1="http://dpdcr.citroen.integ.inetpsa.com/boforms">
         <bof1:brand>'.$this->brand.'</bof1:brand>
         <bof1:country>'.$this->country.'</bof1:country>
         <bof1:culture>'.$this->culture.'</bof1:culture>
         <bof1:component>'.$this->component.'</bof1:component>
		 </bof1:getXMLComponentRequest>
       </bof:getXMLComponent>


			  ';
	}

	/**
	 *
	 */
	public function __toLog()
	{
		return ' Request : ';
	}

}
