<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Repository\PsaSitesEtWebservicesPsaRepository;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelConfigRepository;
use PsaNdp\MappingBundle\Repository\Accessories\PsaAccessoriesRepository;
use PsaNdp\MappingBundle\Repository\Accessories\PsaAccessoriesSiteRepository;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use PsaNdp\MappingBundle\Entity\Accessories\PsaAccessoriesSite;
use PsaNdp\MappingBundle\Entity\Accessories\PsaAccessories;
use Symfony\Component\HttpFoundation\Request;
use PsaNdp\WebserviceConsumerBundle\Webservices\AccessoiresAOA;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * Class Pc83ContenuAccessoiresDataSource
 * @package PsaNdp\MappingBundle\Sources
 */
class Pc83ContenuAccessoiresDataSource extends AbstractDataSource
{
    const LCDV6     = 0;
    const CLIENT_ID = 'CFGAP';

    /**
     * @var PsaModelConfigRepository
     */
    protected $modelConfigRepository;

    /**
     * @var SiteConfiguration
     */
    protected $siteConfiguration;

    /**
     * @var PsaSitesEtWebservicesPsaRepository
     */
    protected $siteAndWebservices;

    /**
     *
     * @var AccessoiresAOA
     */
    protected $accessoiresAOA;

    /**
     *
     * @var PsaAccessoriesRepository
     */
    protected $accessories;
    /**
     *
     * @var PsaAccessoriesSiteRepository
     */
    protected $accessoriesSite;

    /**
     * @param PsaModelConfigRepository              $configRepository
     * @param SiteConfiguration                     $siteConfiguration
     * @param PsaSitesEtWebservicesPsaRepository    $siteAndWebservices
     * @param AccessoiresAOA                        $accessoiresAOA
     */
    public function __construct(
        PsaModelConfigRepository $configRepository,
        SiteConfiguration $siteConfiguration,
        PsaSitesEtWebservicesPsaRepository $siteAndWebservices,
        AccessoiresAOA $accessoiresAOA,
        PsaAccessoriesRepository $accessories,
        PsaAccessoriesSiteRepository $accessoriesSite
    ) {
        $this->modelConfigRepository = $configRepository;
        $this->siteConfiguration = $siteConfiguration;
        $this->siteAndWebservices = $siteAndWebservices;
        $this->accessoiresAOA = $accessoiresAOA;
        $this->accessories = $accessories;
        $this->accessoriesSite = $accessoriesSite;
    }

    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /** @var PsaPageZoneConfigurableInterface $block */
        $data['block'] = $block;
        $gammeVehicle  = $block->getPage()->getVersion()->getGammeVehicule();
        $siteId        = $request->attributes->get('siteId');
        $languageCode  = $request->attributes->get('language');
        $configuration = $this->modelConfigRepository->findOneBySiteIdAndLanguageCode($siteId, $languageCode);
        if ($configuration) {
            $data['configuration'] = $configuration;
        }
        /** @var PsaAccessoriesSite $accessoriesSite */
        $accessoriesSite = $this->accessoriesSite->findOneBySiteIdAndLanguageCode($siteId, $languageCode);
        $data['accessoiresParams'] = ($accessoriesSite) ? $accessoriesSite : null;
        if($gammeVehicle) {
            $gammeVehicle  = explode('-', $gammeVehicle);
            $data['LCDV6'] = $gammeVehicle[self::LCDV6];
            $data['siteAndWebservices'] = $this->siteAndWebservices->findOneBySiteId($siteId);
            $this->siteConfiguration->setSiteId($siteId);
            $this->siteConfiguration->loadConfiguration();
            /** @var PsaSite $site */
            $site                       = $this->siteConfiguration->getSite();
            $data['countryCode']        = $site->getCountryCode();
            $data['languageCode']       = $languageCode;
            $data['siteSettings'] = $this->getSiteSettings($siteId);
            /** @var PsaAccessories $accessories */
            $accessories = $this->accessories->find(PsaSite::NDP_MASTER_SITE);
            $data['defaultVisual'] = ($accessories) ? $accessories->getMedia() : null;
            $data['wsAccessoires'] = $this->accessoiresAOA->getAccessories($this->getParametersForAccessories($data, $gammeVehicle));
        }
       
        return $data;
    }
    
    /**
     *
     * @param array $data
     * @param array $gammeVehicle
     *
     * @return array
     */
    private function getParametersForAccessories($data, $gammeVehicle)
    {
        $parameters = [];
        $parameters['accessoriesInput'] = [
            'valuedCriteria' => [
                'valuedCriterion' => [
                    'vehicle' => [
                        'bodyStyleCode' =>  substr($gammeVehicle[self::LCDV6], 4, 2),
                        'modelCode' => substr($gammeVehicle[self::LCDV6], 0, 4)
                        ]
                    ]
                ],
            'settings' => [
                'clientID' => self::CLIENT_ID,
                'locales' => [
                    'locale' => $data['languageCode'].'_'.$data['countryCode']
                    ]
                ]
        ];

        return $parameters;
    }
    
    /**
     * @param integer $siteId
     *
     * @return array
     */
    private function getSiteSettings($siteId)
    {
        $this->siteConfiguration->setSiteId($siteId);
        $this->siteConfiguration->loadConfiguration();

        $settings = array_merge(
            array(
                'VEHICULE_PRICE_DISPLAY' => (boolean) $this->siteConfiguration->getNationalParameter(
                    'VEHICULE_PRICE_DISPLAY'
                ),
            ),
            $this->siteConfiguration->getNationalParameter('CUSTOM'),
            array('OTHER_PRICE_TYPE' => $this->siteConfiguration->getNationalParameter('OTHER_PRICE_TYPE')),
            array(
                'OTHER_PRICE_FROM_POSITION' => intval(
                    $this->siteConfiguration->getNationalParameter('OTHER_PRICE_FROM_POSITION')
                ),
            ),
            array(
                'OTHER_PRICE_NB_DECIMAL' => intval(
                    $this->siteConfiguration->getNationalParameter('OTHER_PRICE_NB_DECIMAL')
                ),
            ),
            array('CURRENCY_CODE' => $this->siteConfiguration->getNationalParameter('CURRENCY_CODE')),
            array('CURRENCY_SYMBOL' => $this->siteConfiguration->getNationalParameter('CURRENCY_SYMBOL')),
            array('CURRENCY_POSITION' => intval($this->siteConfiguration->getNationalParameter('CURRENCY_POSITION'))),
            array(
                'CURRENCY_USE_LOCAL' => (boolean) $this->siteConfiguration->getNationalParameter(
                    'CURRENCY_USE_LOCAL'
                ),
            ),
            array('DELAY_POPIN' => intval($this->siteConfiguration->getParameter('DELAY_POPIN')))
        );

        return $settings;
    }
}
