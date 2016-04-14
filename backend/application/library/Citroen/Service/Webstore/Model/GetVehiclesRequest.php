<?php

namespace Citroen\Service\Webstore\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetVehiclesRequest.
 */
class GetVehiclesRequest extends BaseModel
{
    protected $brand;
    protected $country;
    protected $client;
    protected $languageCode;
    protected $modelCode;
    protected $bodyStyleCode;
    protected $lat;
    protected $lng;
    protected $currentPage;
    protected $nbElements;
    protected $maxDistance;

    /**
     *
     */
    public function __toXML()
    {
        $sXML = "
		  <ns1:GetVehicles>
			 <ns1:context>
				<ns1:Brand>".$this->brand."</ns1:Brand>
				<ns1:Country>".$this->country."</ns1:Country>
				<ns1:LanguageCode>".$this->languageCode."</ns1:LanguageCode>
				<ns1:Client>".$this->client."</ns1:Client>
			 </ns1:context>
			 <ns1:filter>
				<ns1:GetStoreDetailUrl>1</ns1:GetStoreDetailUrl>
				<ns1:GetNearestDealer>1</ns1:GetNearestDealer>";
        if ($this->modelCode) {
            $sXML .= "<ns1:ModelCode>".$this->modelCode."</ns1:ModelCode>";
        }
        if ($this->bodyStyleCode) {
            $sXML .= "<ns1:BodyStyleCode>".$this->bodyStyleCode."</ns1:BodyStyleCode>";
        }
        if ($this->lat && $this->lng) {
            $sXML .= "
				<ns1:Latitude>".$this->lat."</ns1:Latitude>
				<ns1:Longitude>".$this->lng."</ns1:Longitude>";
        }
        if ($this->maxDistance) {
            $sXML .= "<ns1:MaxDistance>".$this->maxDistance."</ns1:MaxDistance>";
        }
        $sXML .= "
			 </ns1:filter>
			 <ns1:paging>
				<ns1:CurrentPageNumber>".$this->currentPage."</ns1:CurrentPageNumber>
				<ns1:NumberElementByPage>".$this->nbElements."</ns1:NumberElementByPage>
			 </ns1:paging>
			 <ns1:sort>
				<ns1:Type>DISTANCE</ns1:Type>
				<ns1:Mode>ASC</ns1:Mode>
			 </ns1:sort>
		  </ns1:GetVehicles>";

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
