<?php

use Itkg\Service\Model as BaseModel;

/**
 * Classe TestRequest.
 */
class Plugin_MotCfgEngineCriteria_Model_Request extends BaseModel
{
    protected $client;
    protected $brand;
    protected $country;
    protected $date;
    protected $languageID;

    protected $version;

    public function __toXML()
    {
        $return = '<eng:EngineCriteria xmlns:eng="http://xml.inetpsa.com/Services/Cfg/EngineCriteria">
            <cfg:EngineCriteria xmlns:cfg="http://inetpsa.com/cfg">
                <cfg:ContextRequest>
                    <cfg:Client>'.$this->getClient().'</cfg:Client>
                    <cfg:Brand>'.$this->getBrand().'</cfg:Brand>
                    <cfg:Country>'.$this->getCountry().'</cfg:Country>
                    <cfg:Date>'.$this->getDate().'</cfg:Date>
                    <!--Optional -->
                    <cfg:LanguageID>'.$this->getLanguageID().'</cfg:LanguageID>
                </cfg:ContextRequest>
                <cfg:EngineCriteriaParameter>
                    <cfg:Version>'.$this->getVersion().'</cfg:Version>
                </cfg:EngineCriteriaParameter>
            </cfg:EngineCriteria>
        </eng:EngineCriteria>';

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

    protected function getVersion()
    {
        return $this->version;
    }
    /**
     *
     */
    public function __toLog()
    {
        return ' Request : ';
    }
}
