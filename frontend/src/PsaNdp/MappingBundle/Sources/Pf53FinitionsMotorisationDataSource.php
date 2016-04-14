<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Entity\PsaSitesEtWebservicesPsa;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;
use PsaNdp\MappingBundle\Repository\PsaFinishingSiteRepository;
use PsaNdp\MappingBundle\Repository\PsaModelSiteRepository;
use PsaNdp\MappingBundle\Repository\PsaModelViewAngleRepository;
use PsaNdp\MappingBundle\Repository\PsaSegmentationFinishesSiteRepository;
use PsaNdp\MappingBundle\Repository\PsaSitesEtWebservicesPsaRepository;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelConfigRepository;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteAngleRepository;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteSiteRepository;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaVehicleCategorySiteRepository;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineCompareGrade;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineConfig;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineEngineCriteria;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;
use PsaNdp\WebserviceConsumerBundle\Webservices\FinancementSimulator;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class Pf53FinitionsMotorisationDataSource
 */
class Pf53FinitionsMotorisationDataSource extends AbstractDataSource
{
    /**
     * @var PsaModelConfigRepository
     */
    protected $modelConfigRepository;

    /**
     * @var PsaVehicleCategorySiteRepository
     */
    protected $vehicleCategoryRepository;

    /**
     * @var PsaModelSilhouetteSiteRepository
     */
    protected $modelSilhouetteSiteRepository;

    /**
     * @var PsaModelSiteRepository
     */
    protected $modelSiteRepository;

    /**
     * @var ConfigurationEngineConfig
     */
    protected $configurationEngine;

    /**
     * @var SiteConfiguration
     */
    protected $siteConfiguration;

    /**
     * @var ConfigurationEngineSelect
     */
    protected $configurationEngineSelect;

    /**
     * @var PsaFinishingSiteRepository
     */
    protected $finishingRepository;

    protected $segmentationFinishesSiteRepository;

    /**
     * @var ConfigurationEngineCompareGrade
     */
    protected $compareGrade;

    /**
     * @var ConfigurationEngineEngineCriteria
     */
    protected $engineCriteria;

    /**
     * @var FinancementSimulator
     */
    protected $sfg;

    /**
     * @var PsaModelSilhouetteAngleRepository
     */
    protected $angleSilhouetteRepository;

    /**
     * @var PsaModelViewAngleRepository
     */
    protected $angleModelRepository;

    /**
     * @var PsaSitesEtWebservicesPsaRepository
     */
    protected $siteAndWebservices;

    /**
     * @var RouterInterface
     */
    private $router;



    /**
     * @param PsaModelConfigRepository              $configRepository
     * @param PsaVehicleCategorySiteRepository      $vehicleCategory
     * @param PsaModelSilhouetteSiteRepository      $silhouetteSite
     * @param PsaModelSiteRepository                $modelSite
     * @param ConfigurationEngineConfig             $config
     * @param SiteConfiguration                     $siteConfiguration
     * @param ConfigurationEngineSelect             $select
     * @param PsaFinishingSiteRepository            $finishing
     * @param ConfigurationEngineCompareGrade       $compareGrade
     * @param PsaSegmentationFinishesSiteRepository $segmentationFinishesSiteRepository
     * @param ConfigurationEngineEngineCriteria     $engineCriteria
     * @param FinancementSimulator                  $financementSimulator
     * @param PsaModelSilhouetteAngleRepository     $angleSilhouetteRepository
     * @param PsaModelViewAngleRepository           $angleModelRepository
     * @param PsaSitesEtWebservicesPsaRepository    $siteAndWebservices
     * @param RouterInterface                       $router
     */
    public function __construct(
        PsaModelConfigRepository $configRepository,
        PsaVehicleCategorySiteRepository $vehicleCategory,
        PsaModelSilhouetteSiteRepository $silhouetteSite,
        PsaModelSiteRepository $modelSite,
        ConfigurationEngineConfig $config,
        SiteConfiguration $siteConfiguration,
        ConfigurationEngineSelect $select,
        PsaFinishingSiteRepository $finishing,
        ConfigurationEngineCompareGrade $compareGrade,
        PsaSegmentationFinishesSiteRepository $segmentationFinishesSiteRepository,
    	ConfigurationEngineEngineCriteria $engineCriteria,
        FinancementSimulator $financementSimulator,
        PsaModelSilhouetteAngleRepository $angleSilhouetteRepository,
        PsaModelViewAngleRepository $angleModelRepository,
        PsaSitesEtWebservicesPsaRepository $siteAndWebservices,
        RouterInterface $router
    ) {
        $this->modelConfigRepository = $configRepository;
        $this->vehicleCategoryRepository = $vehicleCategory;
        $this->modelSilhouetteSiteRepository = $silhouetteSite;
        $this->modelSiteRepository = $modelSite;
        $this->configurationEngine = $config;
        $this->siteConfiguration = $siteConfiguration;
        $this->configurationEngineSelect = $select;
        $this->finishingRepository = $finishing;
        $this->compareGrade = $compareGrade;
        $this->segmentationFinishesSiteRepository = $segmentationFinishesSiteRepository;
        $this->engineCriteria = $engineCriteria;
        $this->sfg = $financementSimulator;
        $this->angleSilhouetteRepository = $angleSilhouetteRepository;
        $this->angleModelRepository = $angleModelRepository;
        $this->siteAndWebservices = $siteAndWebservices;
        $this->router = $router;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input
     * BlockInterface and current url Request
     *
     * @param ReadBlockInterface $block
     * @param Request            $request  Current url request displaying th block
     * @param bool               $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /** @var PsaPageZoneConfigurableInterface $block */
        $data['block'] = $block;

        $gammeVehicle =  $block->getPage()->getVersion()->getGammeVehicule();
        $gammeVehicle = explode('-', $gammeVehicle);

        $siteId = $request->attributes->get('siteId');
        $languageCode = $request->attributes->get('language');
        $configuration = $this->modelConfigRepository->findOneBySiteIdAndLanguageCode($siteId, $languageCode);
        if ($configuration) {
            $data['configuration'] = $configuration;
        }
        $data['vehicleCategory'] = $this->vehicleCategoryRepository->findBySiteIdAndLanguageCode(
            $siteId,
            $languageCode
        );
        $data['silhouette'] = $this->modelSilhouetteSiteRepository->findOneBySiteIdLanguageCodeLcdvAndGroupingCode(
            $siteId,
            $languageCode,
            $gammeVehicle[0],
            $gammeVehicle[1]
        );
        $data['urlJson'] = $this->getUrlJson($siteId, $languageCode, $block->getPage()->getVersion()->getGammeVehicule());

        try {
            $data['vehicleCategory'] = $this->vehicleCategoryRepository->findBySiteIdAndLanguageCode(
                $siteId,
                $languageCode
            );

            if ($this->compareGrade->getWebserviceStatus($siteId, $this->compareGrade->getName())) {
                $data['compareGrade'] = $this->compareGrade;
            }
            if ($this->configurationEngine->getWebserviceStatus($siteId, $this->configurationEngine->getName())) {
                $data['configurationConfig'] = $this->configurationEngine;
            }
            if ($this->engineCriteria->getWebserviceStatus($siteId, $this->engineCriteria->getName())) {
                $data['engineCriteria'] = $this->engineCriteria;
            }
            $data['siteAndWebservices'] = $this->siteAndWebservices->findOneBySiteId($siteId);
            $data['siteId'] = $siteId;

            $this->siteConfiguration->setSiteId($siteId);
            $this->siteConfiguration->loadConfiguration();
            $site = $this->siteConfiguration->getSite();
            $data['countryCode'] = $site->getCountryCode();
            $data['languageCode'] = $languageCode;

            //load site configuration
            $data['siteSettings'] = $this->getSiteSettings($siteId);

            $model = substr($gammeVehicle[0], 0, 4);

            $this->compareGrade->compareGrades('VP', $model, null, $gammeVehicle[1]);

            $angle = null;
            $resultSelect = array();
            if ($this->configurationEngineSelect->getWebserviceStatus($siteId, $this->configurationEngineSelect->getName())) {
                $data['configurationSelect'] = $this->configurationEngineSelect;
                // prendre modèle regroupement de silhouette BO et regarder si c'est par silhouette ou par regroupement
                if (!empty($data['silhouette']) && $data['silhouette']->getShowFinishing() === 2) {
                    $resultSelect = $this->configurationEngineSelect->getVersionsBySilhouette(
                        $model,
                        substr($gammeVehicle[0], 4, 2)
                    );
                    $angleView = $this->angleModelRepository->findInitialAngleByLcdv4($model);
                } else {
                    $resultSelect = $this->configurationEngineSelect->getVersionsByModeleRegroupementSilhouette(
                        $model,
                        $gammeVehicle[1]
                    );
                    $angleView = $this->angleSilhouetteRepository->findInitialAngleByLcdv6($gammeVehicle[0]);
                }
            }

            if (empty($angleView)) {
                $angleView = 1;
            } else {
                $angleView = $angleView->getCode();
            }

            $data['angleView'] = $angleView;

            $cheapest = null;
            $series = array();
            foreach ($resultSelect as $select) {
                $this->configurationEngine->addCriteria('Version', $select['lcdv16']);

                $this->configurationEngine->config();

                $version = $this->configurationEngine->getVersion();

                $finishingSite = null;
                if (isset($version->GrCommercialName->id)) {
                    $finishingSite = $this->finishingRepository->findOneBySiteIdAndLanguageAndCode(
                        $siteId,
                        $languageCode,
                        $version->GrCommercialName->id
                    );
                }

                $upselling = null;
                $finishingReference = '';

                if (isset($data['silhouette']) && $data['silhouette'] instanceof PsaModelSilhouetteSite) {
                    // récupérer les finitions de référence pour chaque finition
                    $upselling = $data['silhouette']->getUpsellingByFinitionCode($version->GrCommercialName->id);
                }

                if($upselling) {
                    $finishingReference = $this->configurationEngineSelect->getVersionByGrCommercialName(
                        $upselling->getFinishingReference()
                    );
                    $this->configurationEngine
                        ->addCriteria('Version', $finishingReference[0]->IdVersion->id)
                        ->config();
                    $finishingReference = $this->configurationEngine->getVersion();
                }
                $motorisation = $this->engineCriteria->engineCriteria($select['lcdv16']);

                $serie = array(
                    'version' => $version,
                    'finishing' => $finishingSite,
                    'lcdv16' => $select['lcdv16'],
                    'finishingReference' => $finishingReference,
                    'engine' => $motorisation,
                );

                if ($data['siteAndWebservices'] && $data['siteAndWebservices']->getZoneVp()) {
                    $urlConfigure = $this->getUrlConfiguratorVp($data['siteAndWebservices'], $version, 'engine');
                    $urlMotor = $this->getUrlConfiguratorVp($data['siteAndWebservices'], $version, 'interior');
                    $serie['urlVp'] = array(
                        'urlConfigure' => $urlConfigure,
                        'urlMotor' => $urlMotor,
                    );
                }

                if (empty($cheapest) || $version->Price->netPrice < $cheapest['price']) {
                    $cheapest = array('price' => $version->Price->netPrice, 'version' => $version);
                    if (isset($serie['urlVp'])) {
                        $cheapest['urlVp'] = $serie['urlVp'];
                    }
                }

                $series[] = $serie;
            }

            $data['cheapest'] = $cheapest;

            //get segments
            $data['segments']= $this->segmentationFinishesSiteRepository->findAllBySiteAndLanguage($siteId, $languageCode);
            $data['defaultSegment'] = $this->segmentationFinishesSiteRepository->findDefaultSegmentation(
                $siteId,
                $languageCode
            );

        } catch (Exception $e) {
            sprintf('Une erreur est survenue, le service ne répond pas');
        }

        $data['series'] = $series;

        return $data;
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

    /**
     * @param string $siteId
     * @param string $languageCode
     * @param mixed  $mrs
     *
     * @return string
     */
    protected function getUrlJson($siteId, $languageCode, $mrs)
    {
        // route /{siteId}/{langId}/{mrs}
        return $this->router->generate('psa_ndp_api_comparator',array(
            'siteId'=> $siteId,
            'languageCode'=> $languageCode,
            'mrs'=> $mrs,
        ));
    }

    /**
     * @param PsaSitesEtWebservicesPsa $siteAndWebservices
     * @param $version
     * @param $step
     *
     * @return string
     */
    protected function getUrlConfiguratorVp(PsaSitesEtWebservicesPsa $siteAndWebservices, $version, $step)
    {
        $urlConfigure = $siteAndWebservices->getZoneVpUrl();
        $modelLabel = str_replace(' ', '-', $version->Model->label);
        $bodyStyleLabel = str_replace(' ', '-', $version->GrbodyStyle->label);
        $lcdv16 = $version->IdVersion->id;
        $codeFinition = $version->GrCommercialName->id;

        $search = array('##MODEL##', '##GR_BODY_STYLE##', '##STEP##', '##LCDV16##', '##GR_COMMERCIAL_NAME##');
        $replace = array($modelLabel, $bodyStyleLabel, $step, $lcdv16, $codeFinition);
        $url = str_replace($search, $replace, $urlConfigure);

        return $url;
    }
}
