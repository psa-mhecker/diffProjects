<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;
use Symfony\Component\HttpFoundation\RequestStack;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;

/**
 * Class AccessoiresAOA
 * @package PsaNdp\WebserviceConsumerBundle\Webservices
 */
class AccessoiresAOA extends SoapConsumer
{


    protected $local = "fr_FR";

    /**
     * @return string
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * @param string $local
     *
     * @return AccessoiresAOA
     */
    public function setLocal($local)
    {
        $this->local = $local;

        return $this;
    }


    /**
     * set the Default required parameters for context
     *
     * @param SiteConfiguration $siteConfiguration
     * @param RequestStack      $requestStack
     *
     * @return  AccessoiresAOA
     */
    public function setDefaultContext(SiteConfiguration $siteConfiguration, RequestStack $requestStack = null)
    {
        if(null !== $requestStack && (($request = $requestStack->getCurrentRequest()) !== null)) {
            $request = $requestStack->getCurrentRequest();
            $this->local = $request->attributes->get('language');
            $site = $siteConfiguration->getSite();
            $this->local .= '_'.$site->getCountryCode();
        }

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getDefaultParametersForAccessories()
    {
        $parameters                     = [];

        $parameters['accessoriesInput'] = [
            'settings' => [
                'clientID' => 'CFGAP',
                'locales' => [
                    'locale' => $this->local
                ]
            ]
        ];

        return $parameters;
    }

    /**
     * 
     * @return array
     */
    public function getDefaultParametersForAccessoriesByVehicle()
    {
        $parameters                              = [];
        $parameters['accessoriesByVehicleInput'] = [
            'searchCriteriaByVehicle' => [
                'clientType' => 'CFGAP',
                'locale' => $this->local,
                'salesStatus' => '1',
                'vehicle' => [
                    'bodyStyleCode' => '1PB1B5',
                    'modelCode' => '1PB1',
                ]
            ]
        ];

        return $parameters;
    }

    /**
     * @return mixed
     */
    public function ping()
    {

        return $this->getAccessories();
    }

    /**
     *
     * @param array $params
     * 
     * @return mixed
     */
    public function getAccessories($params = [])
    {
        $parameters = array_merge($this->getDefaultParametersForAccessories(), $params);
        
        return $this->call('getAccessories', $parameters);
    }

    /**
     *
     * @param array $params
     *
     * @return mixed
     */
    public function getAccessoriesByVehicule($params = [])
    {
        $parameters = array_merge($this->getDefaultParametersForAccessories(), $params);

        return $this->call('getAccessoriesByVehicle', $parameters);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WS_ACCESSOIRES_AOA';
    }
}
