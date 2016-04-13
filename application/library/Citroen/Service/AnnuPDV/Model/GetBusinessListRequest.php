<?php

namespace Citroen\Service\AnnuPDV\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetBusinessListRequest
 */
class GetBusinessListRequest extends BaseModel
{

	protected $sitegeo;
	protected $country;
	protected $culture;
	protected $consumer;
	protected $brand;
	protected $ViewActivities;
    protected $ViewLicences;
    protected $ViewIndicators;
    protected $ViewServices;

	/**
	 *
	 */
	public function __toRequest()
	{
		$aParams = array(
			'parameters' => urlencode(json_encode(
					array(
						'country' => $this->country,
						'culture' => $this->culture,
						'consumer' => $this->consumer,
						'brand' => $this->brand,
						'ViewActivities'=> $this->ViewActivities,
						'ViewLicences'=> $this->ViewLicences,
						'ViewIndicators'=> $this->ViewIndicators,
						'ViewServices'=> $this->ViewServices
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
