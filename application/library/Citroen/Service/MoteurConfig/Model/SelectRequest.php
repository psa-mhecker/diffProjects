<?php

namespace Citroen\Service\MoteurConfig\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe SelectRequest
 */
class SelectRequest extends BaseModel
{

	protected $client;
	protected $brand;
	protected $country;
	protected $dateconfig;
	protected $languageid;
	protected $taxincluded;
	protected $responsetype;
	

	/**
	 *
	 */
	public function __toXML()
	{
		
	  
	   $sXML = "<ns1:Select>
			<Select xmlns=\"http://inetpsa.com/cfg\">
			<ContextRequest>
			<Client>". $this->client."</Client>
			<Brand>". $this->brand."</Brand>
			<Country>". $this->country."</Country>
			<Date>". $this->dateconfig."</Date>
			<LanguageID>". $this->languageid."</LanguageID>
			<TaxIncluded>". $this->taxincluded."</TaxIncluded>
			<ResponseType>". $this->responsetype."</ResponseType>
			</ContextRequest>
			<SelectCriteria>
			<VehicleUse>
			</VehicleUse>
			</SelectCriteria>
			</Select>
			</ns1:Select>";
	
	  
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
