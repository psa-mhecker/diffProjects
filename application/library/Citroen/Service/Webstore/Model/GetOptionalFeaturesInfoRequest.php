<?php

namespace Citroen\Service\Webstore\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetOptionalFeaturesInfoRequest
 */
class GetOptionalFeaturesInfoRequest extends BaseModel
{

	protected $brand;
	protected $country;
	protected $client;
	protected $languageCode;
	protected $carnum;
	

	/**
	 *
	 */
	public function __toXML()
	{
		
		$sXML = "<ns1:GetOptionalFeaturesInfo>
         <ns1:context>
            <ns1:Brand>" . $this->brand . "</ns1:Brand>
            <ns1:Country>" . $this->country . "</ns1:Country>
            <ns1:LanguageCode>" . $this->languageCode . "</ns1:LanguageCode>
            <ns1:Client>" . $this->client . "</ns1:Client>
         </ns1:context>
         <ns1:CarNum>" . $this->carnum . "</ns1:CarNum>
      </ns1:GetOptionalFeaturesInfo>";
	  
		return $sXML;
	}

	/**
	 *
	 */
	public function __toLog()
	{
		return ' Request : ';
	}

}
