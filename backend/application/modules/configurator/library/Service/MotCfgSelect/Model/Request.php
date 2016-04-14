<?php

use Itkg\Service\Model as BaseModel;

/**
 * Classe TestRequest.
 */
class Plugin_MotCfgSelect_Model_Request extends BaseModel
{
    protected $client;
    protected $brand;
    protected $country;
    protected $date;
    protected $tariffCode;
    protected $taxIncluded;
    protected $professionalUse;

    protected $languageID;
    protected $network;
    protected $tariffZone;
    protected $localCurrency;
    protected $showAllVersions;
    protected $responseType;
    protected $criteria;

    public function __toXML()
    {
        if(!empty($this->criteria)) {
            $requestCriteria = '<cfg:SelectCriteria>';
            foreach ($this->criteria as $criterion => $value) {
                $requestCriteria .= '<cfg:'.$criterion.'>'.$value.'</cfg:'.$criterion.'>';
            }
            $requestCriteria .= '</cfg:SelectCriteria>';
        }
        $return = '<sel:Select xmlns:sel="http://xml.inetpsa.com/Services/Cfg/Select">
            <cfg:Select xmlns:cfg="http://inetpsa.com/cfg">
                <cfg:ContextRequest>
                   <cfg:Client>'.$this->getClient().'</cfg:Client>
                   <cfg:Brand>'.$this->getBrand().'</cfg:Brand>
                   <cfg:Country>'.$this->getCountry().'</cfg:Country>
                   <cfg:Date>'.$this->getDate().'</cfg:Date>
                   <cfg:Network>'.$this->getNetwork().'</cfg:Network>
                   <cfg:TaxIncluded>'.$this->getTaxIncluded().'</cfg:TaxIncluded>
                   <cfg:LocalCurrency>'.$this->getLocalCurrency().'</cfg:LocalCurrency>
                </cfg:ContextRequest>
                '.$requestCriteria.'
             </cfg:Select>
          </sel:Select>';

        return $return;
    }

    protected function getClient()
    {
        return $this->client;
    }

    protected function getBrand()
    {
        return $this->brand;
    }

    protected function getCountry()
    {
        return $this->country;
    }

    protected function getDate()
    {
        return $this->date;
    }

    protected function getTariffCode()
    {
        return $this->tariffCode;
    }

    protected function getTaxIncluded()
    {
        return $this->taxIncluded;
    }

    protected function getProfessionalUse()
    {
        return $this->professionalUse;
    }

    protected function getLanguageID()
    {
        return $this->languageID;
    }

    protected function getNetwork()
    {
        return $this->network;
    }

    protected function getTariffZone()
    {
        return $this->tariffZone;
    }

    protected function getLocalCurrency()
    {
        return $this->localCurrency;
    }

    protected function getShowAllVersions()
    {
        return $this->showAllVersions;
    }

    protected function getResponseType()
    {
        return $this->responseType;
    }
    /**
     *
     */
    public function __toLog()
    {
        return ' Request : ';
    }
}
