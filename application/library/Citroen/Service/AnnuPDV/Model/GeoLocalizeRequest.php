<?php

namespace Citroen\Service\AnnuPDV\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GeoLocalizeRequest
 */
class GeoLocalizeRequest extends BaseModel
{

	protected $place;
	protected $country;
	protected $culture;
	protected $consumer;
	protected $brand;

	/**
	 *
	 */
	public function __toRequest()
	{
		$aParams = array(
			'parameters' => urlencode(json_encode(
				array(
					'place' => $this->place,
					'country' => $this->country,
					'culture' => $this->culture,
					'consumer' => $this->consumer,
					'brand' => $this->brand
				))
			)
		);
		return $aParams;
	}

	/**
	 *
	 */
	public function __toLog()
	{
		return ' Request : ';
	}

}
