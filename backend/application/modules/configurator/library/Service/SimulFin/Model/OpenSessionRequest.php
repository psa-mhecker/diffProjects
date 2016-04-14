<?php
use Itkg\Service\Model as BaseModel;

/** * Classe OpenSessionRequest.
 */class OpenSessionRequest extends BaseModel
{    protected $login;


    protected $password;


    protected $country;


    protected $language;


    protected $financingMake;


    protected $currency;


    protected $flowDate;    /**     *     */    public function __toXML()
    {
        return "            <ser:OpenSession xmlns:ser=\"http://xml.inetpsa.com/Services/SFG/Service_Dealer\">                <ser1:RequestLoad xmlns:ser1=\"http://xml.inetpsa.com/Services/SFG/Service_dealer\">                    <ser1:Site>                    <ser1:Login>".\Itkg::$config['SERVICE_SIMULFIN']['PARAMETERS']['login']."</ser1:Login>                    <ser1:Password>".\Itkg::$config['SERVICE_SIMULFIN']['PARAMETERS']['password']."</ser1:Password>                    </ser1:Site>                    <ser1:Context>                        <ser1:Country>".$this->country."</ser1:Country>                        <ser1:Language>".$this->language."</ser1:Language>                        <ser1:FinancingMake>".$this->financingMake."</ser1:FinancingMake>                        <ser1:Currency>".$this->currency."</ser1:Currency>                        <ser1:FlowDate>".$this->flowDate."</ser1:FlowDate>                        <ser1:Information/>                    </ser1:Context>                </ser1:RequestLoad>            </ser:OpenSession>";

    }    /**     *     */    public function __toLog()
    {
        return ' Request : ';

    }

}
