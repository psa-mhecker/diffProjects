<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;

use Doctrine\DBAL\Exception\InvalidArgumentException;

/**
 * Class Webstore
 * @package PsaNdp\WebserviceConsumerBundle\Webservices
 */
class Webstore extends AbstractPsaSoapWebservice
{
    const RESPONSE_OK = 'OK';
    const RESPONSE_KO = 'KO';

    const BRAND = 'AP';
    const CLIENT = 'NDP';
    const TYPE_SITE = 'PARTICULIER';
    const UNIT_OF_MEASURE = 'KM';
    const MAX_DISTANCE = 150;
    const GROUP_BY_VERSION = 0;
    const GET_NEAREST_DEALER = 0;
    const GET_STORE_DETAIL_URL = 0;
    const LATITUDE = 'NaN';
    const LONGITUDE = 'NaN';
    const MIN_PRICE = -1;
    const MAX_PRICE = -1;
    const CURRENT_PAGE_NUMBER = 0;
    const NUMBER_VEHICLE_BY_PAGING = 0;
    const SORT_TYPE = 'PRICE';
    const SORT_MODE = 'ASC';
    const INLUDE_AGENTS = 1;

    protected $dealersResponse;

    protected $vehiclesResponse;

    protected $legalMentions;

    /**
     * @var array $filter
     */
    protected $filter = array();

    /**
     * @var array $paging
     */
    protected $paging = array();

    /**
     * @var array $sort
     */
    protected $sort = array();

    /**
     * @var array
     */
    protected $allowedArguments = array(
        'context' => array(
            'Brand',
            'Country',
            'LanguageCode',
            'Client',
        ),
        'filter' => array(
            'ModelCode',
            'BodyStyleCode',
            'EnergyCode',
            'DealerCode',
            'DealerIdSiteGeo',
            'TypeSite',
            'GroupByVersion',
            'GetNearestDealer',
            'GetStoreDetailUrl',
            'UnitOfMeasure',
            'Latitude',
            'Longitude',
            'MinPrice',
            'MaxPrice',
            'RegColorID',
            'MaxDistance',
            'CarNum',
            'VersionCode',
            'ColorExt',
            'ColorInt',
            'OptionCode',
            'RegGradeCode',
            'RegEngineCode',
            'RegTransmissionCode',
            'RegBodystyleCode',
            'LegalMentionType',
            'IncludeRAC',
            'IncludeAgents',
        ),
        'paging' => array(
            'CurrentPageNumber',
            'NumberElementByPage',
        ),
        'sort' => array(
            'Type',
            'Mode',
        ),
    );

    /**
     * @param string $name
     * @param mixed  $value
     * @throws InvalidArgumentException
     * @return $this
     */
    public function addFilter($name, $value)
    {
        return $this->addArgumentValue('filter', $name, $value);
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @throws InvalidArgumentException
     * @return $this
     */
    public function addPaging($name, $value)
    {
        return $this->addArgumentValue('paging', $name, $value);
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @throws InvalidArgumentException
     * @return $this
     */
    public function addSort($name, $value)
    {
        return $this->addArgumentValue('sort', $name, $value);
    }

    /**
     * set the Default required parameters for context
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setDefaultContext()
    {
        parent::setDefaultContext()
            ->addContext('Brand', self::BRAND)
            ->addContext('Client', self::CLIENT);

        $this->addFilter('TypeSite', self::TYPE_SITE)
            ->addFilter('UnitOfMeasure', self::UNIT_OF_MEASURE)
            ->addFilter('MaxDistance', self::MAX_DISTANCE)
            ->addFilter('GroupByVersion', self::GROUP_BY_VERSION)
            ->addFilter('GetNearestDealer', self::GET_NEAREST_DEALER)
            ->addFilter('GetStoreDetailUrl', self::GET_STORE_DETAIL_URL)
            ->addFilter('Latitude', self::LATITUDE)
            ->addFilter('Longitude', self::LONGITUDE)
            ->addFilter('MinPrice', self::MIN_PRICE)
            ->addFilter('MaxPrice', self::MAX_PRICE)
            ->addFilter('IncludeAgents', self::INLUDE_AGENTS);

        $this->addPaging('CurrentPageNumber', self::CURRENT_PAGE_NUMBER)
            ->addPaging('NumberElementByPage', self::NUMBER_VEHICLE_BY_PAGING);

        $this->addSort('Type', self::SORT_TYPE)
            ->addSort('Mode', self::SORT_MODE);
    }

    /**
     * @return mixed
     */
    public function getDealers()
    {
        /* Exemple d'appel sur le DataSource
         *
         * $dealers = $serviceWebstore->addContext('LanguageCode', 'fr-FR')
         *      ->addFilter('ModelCode', '1PIA')
         *      ->addFilter('BodyStyleCode', 'S0000029')
         *      ->addFilter('Latitude', 48.862586)
         *      ->addFilter('Longitude', 2.350493)
         *      ->addFilter('GetStoreDetailUrl', 1) //True si l'on souhaite récupérer les liens vers la fiche détaillé
         *      ->addPaging('CurrentPageNumber', 1)
         *      ->addPaging('NumberElementByPage', 2)
         *      ->getDealers();
         */

        $parameters = array(
            'context' => $this->context,
            'filter' => $this->filter,
            'paging' => $this->paging,
        );

        $this->dealersResponse = $this->call('GetDealers', $parameters);

        return $this->dealersResponse;
    }

    /**
     * @return mixed
     */
    public function getVehicles()
    {
        $parameters = array(
            'context' => $this->context,
            'filter' => $this->filter,
            'paging' => $this->paging,
            'sort' => $this->sort,
        );

        $this->vehiclesResponse = $this->call('GetVehicles', $parameters);

        return $this->vehiclesResponse;
    }

    /**
     * @param $blockRayon
     * @param $decimal
     * @return array
     */
    public function getInfoPointDeVente($blockRayon, $decimal)
    {
        $this->getDealers();
        $this->checkDealersResponse();
        $dealersList = $this->dealersResponse->GetDealersResult->DealerType->GetDealerType;

        $aDealer = $dealersWithVehiclesList = array();
        foreach ($dealersList as $dealer) {
            $this->addFilter('DealerIdSiteGeo', $dealer->IDSiteGEO)
                ->addPaging('NumberElementByPage', $blockRayon) //pour afficher tous les vehicles du dealer
            ;
            $this->getVehicles();
            $this->checkVehiclesResponse();
            $vehiclesList = $this->vehiclesResponse->GetVehiclesResult->VehicleType->GetVehicleType;
            //$urlList = $this->vehiclesResponse->GetVehiclesResult->URLList;

            $aDealer = get_object_vars($dealer);
            $aDealer['Distance'] = round($aDealer['Distance'], $decimal);
            $aDealer['Latitude'] = $this->filter['Latitude'];
            $aDealer['Longitude'] = $this->filter['Longitude'];

            $dealersWithVehiclesList[$dealer->IDSiteGEO] = $aDealer;
            $dealersWithVehiclesList[$dealer->IDSiteGEO]['vehicles'] = $vehiclesList;
        }

        return $dealersWithVehiclesList;
    }

    /**
     * @param $decimal
     * @return array
     */
    public function getInfoVehicules($decimal)
    {
        $this->getVehicles();
        $this->checkVehiclesResponse();

        $vehiclesList = null;
        $countVehicle = $this->vehiclesResponse->GetVehiclesResult->CountVehicle;
        if ($countVehicle > 0) {
            $vehiclesList = $this->vehiclesResponse->GetVehiclesResult->VehicleType->GetVehicleType;

            $aDealer = array();
            foreach ($vehiclesList as $vehicle) {
                $this->addPaging('NumberElementByPage', 1); //Only one dealer needed
                $this->getDealers();
                $this->checkDealersResponse();
                $dealer = $this->dealersResponse->GetDealersResult->DealerType->GetDealerType;

                if (is_object($dealer[0])) {
                    $aDealer = get_object_vars($dealer[0]);
                    $aDealer['Distance'] = round($aDealer['Distance'], $decimal);
                    $vehicle->dealer = $aDealer;
                }
            }
        }

        return $vehiclesList;
    }

    /**
     * @return mixed
     */
    public function getMentionsLegales()
    {
        $mentionText = '';
        $parameters = array(
            'context' => $this->context,
            'filter' => $this->filter,
        );

        $this->legalMentions = $this->call('GetLegalMentions', $parameters);

        if (isset($this->legalMentions->GetLegalMentionsResult->MentionText)) {
            $mentionText = $this->legalMentions->GetLegalMentionsResult->MentionText;
        }

        return $mentionText;
    }

    /**
     * @throws \Exception
     */
    private function checkDealersResponse()
    {
        if ($this->dealersResponse == null) {
            throw new \Exception('Empty Dealers Response');
        }
        if ($this->dealersResponse->GetDealersResult->ResponseCode == self::RESPONSE_KO) {
            throw new \Exception('Dealers Response : '.$this->dealersResponse->GetDealersResult->ResponseCode);
        }
    }

    /**
     * @throws \Exception
     */
    private function checkVehiclesResponse()
    {
        if ($this->vehiclesResponse == null) {
            throw new \Exception('Empty Vehicles Response');
        }
        if ($this->vehiclesResponse->GetVehiclesResult->ResponseCode == self::RESPONSE_KO) {
            throw new \Exception('Vehicles Response : '.$this->vehiclesResponse->GetVehiclesResult->ResponseCode);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WS_WEBSTORE';
    }
}
