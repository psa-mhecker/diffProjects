<?php

use Itkg\Service\Model as BaseModel;

/**
 * Classe TestRequest.
 */
class Plugin_MotCfgConfig_Model_Request extends BaseModel
{
    protected $client;
    protected $brand;
    protected $country;
    protected $date;
    protected $tariffCode;
    protected $taxIncluded;
    protected $professionalUse;
    protected $network;
    protected $languageID;
    protected $tariffZone;
    protected $localCurrency;
    protected $showUnavailableLooks;
    protected $showUnavailableOptionalFeatures;
    protected $version;

    public function __toXML()
    {
        $return = '<con:Config xmlns:con="http://xml.inetpsa.com/Services/Cfg/Config">
            <cfg:Config xmlns:cfg="http://inetpsa.com/cfg">
                 <cfg:ContextRequest>
                   <cfg:Client>'.$this->getClient().'</cfg:Client>
                   <cfg:Brand>'.$this->getBrand().'</cfg:Brand>
                   <cfg:Country>'.$this->getCountry().'</cfg:Country>
                   <cfg:Date>'.$this->getDate().'</cfg:Date>
                   <cfg:Network>'.$this->getNetwork().'</cfg:Network>
                   <cfg:TaxIncluded>'.$this->getTaxIncluded().'</cfg:TaxIncluded>
                   <cfg:LocalCurrency>'.$this->getLocalCurrency().'</cfg:LocalCurrency>
                   <cfg:ShowUnavailableLooks>'.$this->getShowUnavailableLooks().'</cfg:ShowUnavailableLooks>
                   <cfg:ShowUnavailableOptionalFeatures>'.$this->getShowUnavailableOptionalFeatures().'</cfg:ShowUnavailableOptionalFeatures>
                </cfg:ContextRequest>
                <cfg:ConfigCriteria>
                  <cfg:Version>'.$this->getVersion().'</cfg:Version>
                </cfg:ConfigCriteria>
           </cfg:Config>
        </con:Config>';

        return $return;
    }

    protected function getVersion()
    {
        return $this->version;
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

    protected function getNetwork()
    {
        return $this->network;
    }

    protected function getLanguageID()
    {
        return $this->languageID;
    }

    protected function getTariffZone()
    {
        return $this->tariffZone;
    }

    protected function getLocalCurrency()
    {
        return $this->localCurrency;
    }

    protected function getShowUnavailableLooks()
    {
        return $this->showUnavailableLooks;
    }

    protected function getShowUnavailableOptionalFeatures()
    {
        return $this->showUnavailableOptionalFeatures;
    }

    /**
     *
     */
    public function __toLog()
    {
        return ' Request : ';
    }
}
