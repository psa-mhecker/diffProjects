<?php
use Itkg\Service\Model as BaseModel;

/** * Classe SaveCalculationDisplayRequest.
 */class SaveCalculationDisplayRequest extends BaseModel
{    protected $idSession;


    protected $login;


    protected $password;


    protected $country;


    protected $language;


    protected $financingMake;


    protected $currency;


    protected $flowDate;


    protected $vehicleBrandCode;


    protected $vehicleBrandLabel;


    protected $vehicleType;


    protected $vehicleIdentification;


    protected $vehicleModel;


    protected $vehicleDescription;


    protected $vehicleCategory;


    protected $vehicleEngine;


    protected $vehiclePriceHT;


    protected $vehiclePriceTTC;


    protected $clientType;


    protected $financingSpecialFlag;    /**	 *	 */    public function __toXML()
    {
        $sXML = "            <ser:SaveCalculationDisplay xmlns:ser=\"http://xml.inetpsa.com/Services/SFG/Service_Dealer\">             <ser1:RequestCalculationDisplay xmlns:ser1=\"http://xml.inetpsa.com/Services/SFG/Service_dealer\">                <ser1:IdSession>".$this->idSession."</ser1:IdSession>                <ser1:Site>                    <ser1:Login>".\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['PARAMETERS']['login']."</ser1:Login>                    <ser1:Password>".\Itkg::$config['CITROEN_SERVICE_SIMULFIN']['PARAMETERS']['password']."</ser1:Password>                </ser1:Site>                <ser1:Context>                    <ser1:Country>".$this->country."</ser1:Country>                    <ser1:Language>".$this->language."</ser1:Language>                    <ser1:FinancingMake>".$this->financingMake."</ser1:FinancingMake>                    <ser1:Currency>".$this->currency."</ser1:Currency>                    <ser1:FlowDate>".$this->flowDate."</ser1:FlowDate>                    <ser1:Information/>                </ser1:Context>                <ser1:Vehicle>                   <ser1:VehicleGeneral>                       <ser1:VehicleBrandCode>".$this->vehicleBrandCode."</ser1:VehicleBrandCode>                       <ser1:VehicleBrandLabel>".$this->vehicleBrandLabel."</ser1:VehicleBrandLabel>                       <ser1:VehicleType>".$this->vehicleType."</ser1:VehicleType>                       <ser1:VehicleIdentification>".$this->vehicleIdentification."</ser1:VehicleIdentification>                       <ser1:VehicleCategory>".$this->vehicleCategory."</ser1:VehicleCategory>                   </ser1:VehicleGeneral>					<ser1:VehiclePrices>						<ser1:VehiclePriceHT>".$this->vehiclePriceHT."</ser1:VehiclePriceHT>						<ser1:VehiclePriceTTC>".$this->vehiclePriceTTC."</ser1:VehiclePriceTTC>						<ser1:VehicleOTRHT>0</ser1:VehicleOTRHT>						<ser1:VehicleOTRTTC>0</ser1:VehicleOTRTTC>						<ser1:VehicleBrandRebateTTC>0</ser1:VehicleBrandRebateTTC>						<ser1:AddedOptionPrice>0</ser1:AddedOptionPrice>						<ser1:IncludedOptionPrice>0</ser1:IncludedOptionPrice>					</ser1:VehiclePrices>                </ser1:Vehicle>                <ser1:Client>                    <ser1:ClientPersonalData>                        <ser1:ClientType>".$this->clientType."</ser1:ClientType>                    </ser1:ClientPersonalData>                </ser1:Client>                <ser1:Financing>                     <ser1:FinancingSpecialFlag>".$this->financingSpecialFlag."</ser1:FinancingSpecialFlag>                </ser1:Financing>				<ser1:DisplayNameList>					<ser1:DisplayName>APARTIRDE</ser1:DisplayName>				</ser1:DisplayNameList>             </ser1:RequestCalculationDisplay>            </ser:SaveCalculationDisplay>";


        return $sXML;

    }    /**	 *	 */    public function __toLog()
    {
        return ' Request : ';

    }

}
