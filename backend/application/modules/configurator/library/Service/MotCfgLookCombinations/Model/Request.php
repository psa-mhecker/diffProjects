<?php

use Itkg\Service\Model as BaseModel;

/**
 * Classe TestRequest.
 */
class Plugin_MotCfgLookCombinations_Model_Request extends BaseModel
{
    protected $client;
    protected $brand;
    protected $country;
    protected $date;
    protected $languageID;
    protected $showAllVersions;

    protected $vehicleUse;
    protected $model;
    protected $bodyStyle;
    protected $grBodyStyle;
    protected $grade;
    protected $grCommercialName;
    protected $transmissionType;
    protected $grTransmissionType;
    protected $engine;
    protected $grEngine;
    protected $energy;
    protected $ecolabel;

    public function __toXML()
    {
        $return = '<look:LookCombinations xmlns:look="http://xml.inetpsa.com/Services/Cfg/LookCombinations">
            <cfg:LookCombinations xmlns:cfg="http://inetpsa.com/cfg">
                <cfg:ContextRequest>
                    <cfg:Client>'.$this->getClient().'</cfg:Client>
                    <cfg:Brand>'.$this->getBrand().'</cfg:Brand>
                    <cfg:Country>'.$this->getCountry().'</cfg:Country>
                    <cfg:Date>'.$this->getDate().'</cfg:Date>
                    <!--Optional -->
                    <cfg:Network>'.$this->getNetwork().'</cfg:Network>
                    <cfg:ShowAllVersions>'.$this->getShowAllVersions().'</cfg:ShowAllVersions>
                </cfg:ContextRequest>
                <cfg:LookCombinationsCriteria>
                   <!--Optional:-->
                   <cfg:VehicleUse>'.$this->getVehicleUse().'</cfg:VehicleUse>
                   <!--Optional:-->
                   <cfg:Model>'.$this->getModel().'</cfg:Model>
                   <!--Optional:-->
                   <cfg:BodyStyle>'.$this->getBodyStyle().'</cfg:BodyStyle>
                   <!--Optional:-->
                   <cfg:GrBodyStyle>'.$this->getGrBodyStyle().'</cfg:GrBodyStyle>
                   <!--Optional:-->
                   <cfg:Grade>'.$this->getGrade().'</cfg:Grade>
                   <!--Optional:-->
                   <cfg:GrCommercialName>'.$this->getGrCommercialName().'</cfg:GrCommercialName>
                   <!--Optional:-->
                   <cfg:TransmissionType>'.$this->getTransmissionType().'</cfg:TransmissionType>
                   <!--Optional:-->
                   <cfg:GrTransmissionType>'.$this->getGrTransmissionType().'</cfg:GrTransmissionType>
                   <!--Optional:-->
                   <cfg:Engine>'.$this->getEngine().'</cfg:Engine>
                   <!--Optional:-->
                   <cfg:GrEngine>'.$this->getGrEngine().'</cfg:GrEngine>
                   <!--Optional:-->
                   <cfg:Energy>'.$this->getEnergy().'</cfg:Energy>
                   <!--Optional:-->
                   <cfg:EcoLabel>'.$this->getEcolabel().'</cfg:EcoLabel>
                </cfg:LookCombinationsCriteria>
            </cfg:LookCombinations>
         </look:LookCombinations>';

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

    protected function getNetwork()
    {
        return $this->network;
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

    protected function getGrade()
    {
        return $this->grade;
    }

    protected function getGrCommercialName()
    {
        return $this->grCommercialName;
    }

    protected function getTransmissionType()
    {
        return $this->transmissionType;
    }

    protected function getGrTransmissionType()
    {
        return $this->grTransmissionType;
    }

    protected function getEngine()
    {
        return $this->engine;
    }

    protected function getGrEngine()
    {
        return $this->grEngine;
    }

    protected function getEnergy()
    {
        return $this->energy;
    }

    protected function getEcolabel()
    {
        return $this->ecolabel;
    }

    /**
     *
     */
    public function __toLog()
    {
        return ' Request : ';
    }
}
