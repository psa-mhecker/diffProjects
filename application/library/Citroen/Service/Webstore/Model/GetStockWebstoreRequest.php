<?php

namespace Citroen\Service\Webstore\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetStockWebstoreRequest
 */
class GetStockWebstoreRequest extends BaseModel
{

	protected $brand;
	protected $country;
	protected $client;
	protected $languageCode;
	protected $modelCode;
	protected $regBodyStyleCode;
	protected $bodyStyleCode;
	protected $dealerCode1;
	protected $idSiteGeo1;
        protected $typeSite;

	/**
	 *
	 */
	public function __toXML()
	{
		$sXML = "
        <ns1:GetStockWebstore>
            <ns1:storeRequest>
                <ns1:Info>
                    <ns1:Brand>" . $this->brand . "</ns1:Brand>
                    <ns1:Country>" . $this->country . "</ns1:Country>
                    <ns1:Client>" . $this->client . "</ns1:Client>
                    <ns1:LanguageCode>" . $this->languageCode . "</ns1:LanguageCode>
                </ns1:Info>
                <ns1:VehiculeRequest>
					<ns1:DealerCode1>" . $this->dealerCode1 . "</ns1:DealerCode1>
					<ns1:IdSiteGeo1>" . $this->idSiteGeo1 . "</ns1:IdSiteGeo1>";
		if ($this->modelCode) {
			$sXML .= "<ns1:ModelCode>" . $this->modelCode . "</ns1:ModelCode>";
		}
		if ($this->regBodyStyleCode) {
			$sXML .= "<ns1:RegBodyStyleCode>" . $this->regBodyStyleCode . "</ns1:RegBodyStyleCode>";
		}
		if ($this->bodyStyleCode) {
			$sXML .= "<ns1:BodyStyleCode>" . $this->bodyStyleCode . "</ns1:BodyStyleCode>";
		}
                if ($this->typeSite) {
                        $sXML .= "<ns1:TypeSite>" . $this->typeSite . "</ns1:TypeSite>";
                }
		$sXML .= "
                </ns1:VehiculeRequest>
            </ns1:storeRequest>
        </ns1:GetStockWebstore>";
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
