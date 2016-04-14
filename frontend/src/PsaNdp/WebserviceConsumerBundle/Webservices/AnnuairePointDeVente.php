<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;

use \InvalidArgumentException;
use Symfony\Component\HttpFoundation\RequestStack;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;

class AnnuairePointDeVente extends RestConsumer
{

    const CONSUMER = 'DCR.WEB.AC';
    const BRAND = 'AP';
    const DEFAULT_COUNTRY = 'FR';
    const DEFAULT_CULTURE = 'fr';
    const DISTANCE = 'distance';
    const SEARCH_TYPE_SPIRAL = 'spiral';
    const SEARCH_TYPE_STANDARD = 'standard';

    /**
     *
     */
    /**
     * @var array
     */
    protected $allowedArguments = array(
        'parameters' => array(
            'Country',
            'Culture',
            'Consumer',
            'Brand',
            'Latitude',
            'SiteGeo',
            'Longitude',
            'ResultMax',
            'RMax',
            'Criterias',
            'CriteriasExclude',
            'Department',
            'Region',
            'Sort',
            'Details',
            'Name',
            'Unit',
            'PageSize',
            'PageNumber',
            'ViewOnlyAgents',
            'ViewOnlyAgentsAP',
            'WithContract',
            'BrandActivity',
            'IndicatorsActivities',
            'Agent Exclude',
            'Agent Exclude AP',
            'ImporterCode',
            'ImporterName',
            //not in doc ?
            'SearchType',
            // used for search type spiral !!
            'MinPDV',
            'MinDVN',
        )
    );


    /**
     * @var array
     */
    protected $parameters = array();



    public function hasArgument($member, $name)
    {

        return isset($this->allowedArguments[$member]) && in_array($name, $this->allowedArguments[$member]);
    }


    /**
     * @param string $member
     * @param string $name
     * @param mixed  $value
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    private function addArgumentValue($member, $name, $value)
    {
        if (!$this->hasArgument($member, $name)) {
            throw new InvalidArgumentException(sprintf('The argument %s is not allowed', $name));
        }

        $this->{$member}[$name] = $value;

        return $this;
    }

    /**
     * set the Default required parameters for context
     *
     * @param SiteConfiguration $siteConfiguration
     * @param RequestStack      $requestStack
     *
     * @return  ConfigurationEngineSelect
     */
    public function setDefaultContext(SiteConfiguration $siteConfiguration, RequestStack $requestStack = null)
    {

        $this->addParameter('Consumer', self::CONSUMER);
        $this->addParameter('Brand', self::BRAND);
        $this->addParameter('Country', self::DEFAULT_COUNTRY);
        $this->addParameter('Culture', self::DEFAULT_CULTURE);
        $this->addParameter('Sort', self::DISTANCE);
        if(null !== $requestStack && (($request = $requestStack->getCurrentRequest()) !== null)) {
            $siteId = $request->attributes->get('siteId');
            $languageCode = $request->attributes->get('language');
            $this->siteConfiguration = $siteConfiguration;
            $this->siteConfiguration->setSiteId($siteId);
            $this->siteConfiguration->loadConfiguration();
            $site = $this->siteConfiguration->getSite();
            $this->addParameter('Country', $site->getCountryCode());
            if($this->hasArgument('Culture','Culture')) {
                $this->addParameter('Culture', $languageCode);
            }
        }

        return $this;
    }


    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return ConfigurationEngineConfig
     */
    public function addParameter($name, $value)
    {
        return $this->addArgumentValue('parameters', $name, $value);

    }


    public function getBusinessList(array $parameters)
    {


        $parameters = array(
            'parameters' => json_encode($parameters),
            '_' => time(),
        );

        return $this->call('/Services/DealerService.svc/rest/getBusinessList',  $parameters);
    }


    public function getDealerList()
    {
        $parameters = array(
            'parameters' => json_encode($this->parameters),
        );

        return $this->call('/Services/DealerService.svc/rest/GetDealerList',  $parameters);
    }



    public function getDealer()
    {
        $parameters = array(
            'parameters' => json_encode($this->parameters),
            '_' => time(),
        );

        return $this->call('/Services/DealerService.svc/rest/GetDealer',  $parameters);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WS_ANNUPDV';
    }
}
