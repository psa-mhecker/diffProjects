<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe UpdateInstanceByIdRequest
 */
class Plugin_I18N_Model_UpdateXMLComponentRequest extends BaseModel
{
				protected $brand;
				protected $country;
				protected $component;
				protected $files;

	public function getBrand()
	{
		return $this->brand;
	}
	
	public function getCountry()
	{
		return $this->country;
	}
	
	public function getFiles()
	{
		return $this->files;
	}
	
	public function getComponent()
	{
		return $this->component;
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
	
	public function __toXML()
	{
		return "<bof:updateXMLComponent>
					 <brand xsi:type='xsd:string'>".$this->brand."</brand>
					 <country xsi:type='xsd:string'>".$this->country."</country>
					 <component xsi:type='xsd:string'>".$this->component."</component>
					 <files xsi:type='soapenc:Array' xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/'>".$this->files."</files>
				  </bof:updateXMLComponent>";
	}

	/**
	 *
	 */
	public function __toLog()
	{
		return ' Request : ';
	}

}
