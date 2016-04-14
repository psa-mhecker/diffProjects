<?php

use Itkg\Service\Model as BaseModel;

/**
 * Classe TestRequest.
 */
class Plugin_MotCfgCompareGrade_Model_Request extends BaseModel
{
    protected $client;
    protected $brand;
    protected $country;
    protected $date;
    protected $languageID;
    protected $network;
    protected $tariffZone;
    protected $localCurrency;
    protected $showAllVersions;

    protected $vehicleUse;
    protected $model;
    protected $bodyStyle;
    protected $grBodyStyle;
    protected $grCommercialName;

    public function __toXML()
    {
        $return = '<com:CompareGrades xmlns:com="http://xml.inetpsa.com/Services/Cfg/CompareGrade">
            <cfg:CompareGrades xmlns:cfg="http://inetpsa.com/cfg">
                <cfg:ContextRequest>
                    <cfg:Client>'.$this->getClient().'</cfg:Client>
                    <cfg:Brand>'.$this->getBrand().'</cfg:Brand>
                    <cfg:Country>'.$this->getCountry().'</cfg:Country>
                    <cfg:Date>'.$this->getDate().'</cfg:Date>
                    <!--Optional -->
                    <cfg:LanguageID>'.$this->getLanguageID().'</cfg:LanguageID>
                    <cfg:Network>'.$this->getNetwork().'</cfg:Network>
                    <cfg:TariffZone>'.$this->getTariffZone().'</cfg:TariffZone>
                    <cfg:LocalCurrency>'.$this->getLocalCurrency().'</cfg:LocalCurrency>
                    <cfg:ShowAllVersions>'.$this->getShowAllVersions().'</cfg:ShowAllVersions>
                </cfg:ContextRequest>
                <cfg:CompareGradesCriteria>
                   <cfg:VehicleUse>'.$this->getVehicleUse().'</cfg:VehicleUse>
                   <cfg:Model>'.$this->getModel().'</cfg:Model>
                   <!--You have a CHOICE of the next 2 items at this level-->
                   <cfg:BodyStyle>'.$this->getBodyStyle().'</cfg:BodyStyle>
                   <cfg:GrBodyStyle>'.$this->getGrBodyStyle().'</cfg:GrBodyStyle>
                   <!--Optional:-->
                   <cfg:GrCommercialName>'.$this->getGrCommercialName().'</cfg:GrCommercialName>
                </cfg:CompareGradesCriteria>
            </cfg:CompareGrades>
        </com:CompareGrades>';

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

    protected function getVehicleUse()
    {
        return $this->vehiculeUse;
    }

    protected function getModel()
    {
        return $this->model;
    }

    protected function getBodyStyle()
    {
        return $this->bodyStyle;
    }

    protected function getGrBodyStyle()
    {
        return $this->grBodyStyle;
    }

    protected function getGrCommercialName()
    {
        return $this->grCommercialName;
    }

    /**
     *
     */
    public function __toLog()
    {
        return ' Request : ';
    }
}
