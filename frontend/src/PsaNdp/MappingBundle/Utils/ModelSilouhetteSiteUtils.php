<?php


namespace PsaNdp\MappingBundle\Utils;

use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PsaNdp\MappingBundle\Entity\PsaFinishingSite;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelConfig;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;
use PsaNdp\MappingBundle\Repository\PsaFinishingSiteRepository;
use PsaNdp\MappingBundle\Repository\PsaSitesEtWebservicesPsaRepository;
use PsaNdp\MappingBundle\Repository\PsaWebserviceRepository;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelConfigRepository;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteAngleRepository;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaVehicleCategorySiteRepository;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;
use PsaNdp\WebserviceConsumerBundle\Webservices\RangeManager;

/**
 *
 * Usages:
 *
 * 1. Reset and set appropriate options before each call if needed
 * ================================================================
 *
 * $this->modelSilouhetteSiteUtils->setTranslator($this->translator, $this->domain, $this->locale);
 * $this->modelSilouhetteSiteUtils->resetOptions();
 * $this->modelSilouhetteSiteUtils->setOptions(
 *      ModelSilouhetteSiteUtils::OPTION_IMG_URL_PARAMETERS,
 *      ['width' => '218', 'height' => '224']
 * );
 * $this->modelSilouhetteSiteUtils->setOptions(ModelSilouhetteSiteUtils::OPTION_REEVOO, false);
 *
 *
 * 2. Get Data needed, 2 usage:
 * ============================
 *
 * 2.1 From a PsaModelSilouhette to generate Model Array
 *     In this case will generate a $modelSilouhette Array from a PsaModelSilhouetteSite (lcdv6-GrBodyStyle) with its cheapest available version
 *     The Model Array result can be used by the Object ModelSilouhette
 *     $result[] = $this->modelSilouhetteSiteUtils->generateModelSilouhetteData($modelsSilouhetteSite);
 *
 *
 * 2.2 Or directly by calling needed function with parameters :
 *      - generateImgUrl(...)
 *      - getFinishing(...)
 *      - getActions(...)
 *      - getCtaDiscover(...)
 *      - getCtaConfigurer(...)
 *      - getCommercialStrip(....)
 *      - getReeVoo(...)
 *
 *
 *
 * Class ModelUtils
 *
 * @package PsaNdp\MappingBundle\Utils
 */
class ModelSilouhetteSiteUtils extends ModelSiteUtils
{

    /** @var string */
    protected $lcdv6;

    /** @var array Cache by lcdv4/language/site for cheapest [lcdv6][groupingCode] */
    protected $cheapestVersionsByLcdv6AndGrCode = [];

    /** @var array Cache by language/site for PsaModelConfig */
    protected $modelConfigurations = [];

    /** @var PsaModelConfigRepository $modelConfigRepository */
    protected $modelConfigRepository;

    /** @var PsaVehicleCategorySiteRepository $localVehicleCategoryRepository */
    protected $localVehicleCategoryRepository;

    /** @var array handle options for generatin Model Array */
    protected $options;

    /** @var string GrBodyStyle or 'Code Regroupement de silouhette' */
    private $groupingCode;

    /**
     * @param PsaPageRepository $pageRepository
     * @param PsaModelSilhouetteAngleRepository $angleSilhouetteRepository
     * @param PsaFinishingSiteRepository $finishingRepository
     * @param PsaWebserviceRepository $webserviceRepository
     * @param PsaSitesEtWebservicesPsaRepository $sitesEtWebServicesRepository
     * @param PsaModelConfigRepository $modelConfigRepository
     * @param ConfigurationEngineSelect $configurationEngineSelect
     * @param RangeManager $rangeManager
     * @param PsaVehicleCategorySiteRepository $localVehicleCategoryRepository
     */
    public function __construct(
        PsaPageRepository $pageRepository,
        PsaModelSilhouetteAngleRepository $angleSilhouetteRepository,
        PsaFinishingSiteRepository $finishingRepository,
        PsaWebserviceRepository $webserviceRepository,
        PsaSitesEtWebservicesPsaRepository $sitesEtWebServicesRepository,
        PsaModelConfigRepository $modelConfigRepository,
        ConfigurationEngineSelect $configurationEngineSelect,
        RangeManager $rangeManager,
        PsaVehicleCategorySiteRepository $localVehicleCategoryRepository
    )
    {
        $this->pageRepository = $pageRepository;
        $this->finishingRepository = $finishingRepository;
        $this->angleSilhouetteRepository = $angleSilhouetteRepository;
        $this->webserviceRepository = $webserviceRepository;
        $this->sitesEtWebServicesRepository = $sitesEtWebServicesRepository;
        $this->modelConfigRepository = $modelConfigRepository;
        $this->configurationEngineSelect = $configurationEngineSelect;
        $this->rangeManager = $rangeManager;
        $this->localVehicleCategoryRepository = $localVehicleCategoryRepository;

        // Initialize defaults generation options
        $this->resetOptions();
    }


    /**
     * @param mixed $version should result from the WS ConfigurationEngineSelect call
     * @param string $lcdv6  ex : '1PA9AF'
     * @param bool $isMobile
     *
     * @return string TOD where is it it for refacto
     */
    public function generateImgUrl($version, $lcdv6, $isMobile)
    {
        //TODO check difference between PsaModelViewAngle and PsaModelSilhouetteAngle
        $modelSilhouetteAngle = $this->angleSilhouetteRepository->findInitialAngleByLcdv6($lcdv6);

        $viewAngle = '001'; //Par défaut
        if ($modelSilhouetteAngle) {
            $viewAngle = $modelSilhouetteAngle->getCode();
        }

        $baieVisuel3D = $this->webserviceRepository->findOneByServiceKey(self::SERVICE_KEY_BAIE_VISUELS_3D);
        $baieVisuel3DUrl = '';

        if ($baieVisuel3D!== null) {
            $baieVisuel3DUrl = $baieVisuel3D->getBaseUrl();
        }
        // Default paramaters
        $parameters = [
            'client' => 'websimulator',
            'format' => 'png',
            'version' => $version['LCDV16'],
            'view' => $viewAngle
        ];
        // Add/Overrride parameters for url
        if ($isMobile) {
            $parameters = array_merge($parameters, $this->options[self::OPTION_IMG_URL_PARAMETERS_MOBILE]);
        } else {
            $parameters = array_merge($parameters, $this->options[self::OPTION_IMG_URL_PARAMETERS_DESKTOP]);
        }

        return $baieVisuel3DUrl . '/V3DImage.ashx?' . http_build_query($parameters);
    }


    /**
     * @param PsaModelSilhouetteSite $modelSilouhetteSite
     * @param bool $isMobile
     * @param null $options
     * @param null $sliceId
     *
     * @return array|null
     * @throws \Exception
     */
    public function generateModelSilouhetteData(PsaModelSilhouetteSite $modelSilouhetteSite, $isMobile = false, $options = null, $sliceId = null)
    {
        if ($this->translator === null) {
            throw new \Exception('You need to set the translator before generating a data for PsaModelSilhouetteSite');
        }
        $modelSilouhette = null;

        if ($options !== null) {
            $this->options = array_merge($this->options, $options);
        }
        if ($sliceId === null) {
            $sliceId = self::SLICE_DEFAULT;
        }

        $this->lcdv6 = $modelSilouhetteSite->getLcdv6();
        $this->groupingCode= $modelSilouhetteSite->getGroupingCode();
        $this->site = $modelSilouhetteSite->getSite();
        $this->language = $modelSilouhetteSite->getLangue();
        $this->isMobile = $isMobile;

        $cheapestVersion = $this->getCheapestVersion();
        if ($cheapestVersion !== null) {
            $modelSilouhette = [
                'id'              => $cheapestVersion->IdVersion->id,
                'sliceId'         => $sliceId,
                'model'           => $modelSilouhetteSite,
                'version'         => $cheapestVersion,
                'img'             => $this->getImg($cheapestVersion, $this->lcdv6, $this->isMobile),
                'url'             => $this->getWelcomePageShowRoomUrl($this->lcdv6, $this->groupingCode, $this->site, $this->language),
                'actions'         => $this->getActions($cheapestVersion, $this->lcdv6, $this->groupingCode, $this->site, $this->language),
                'finishing'       => $this->getFinishing($cheapestVersion, $this->site, $this->language),
                'categoryVehicule'=> $this->getCategoryVehicule($cheapestVersion, $this->site, $this->language),
                'reevoo'          => $this->getReeVoo($this->lcdv6),
                'isMobile'        => $isMobile
            ];
        }

        return $modelSilouhette;
    }

    /**
     * @return mixed
     */
    private function getCheapestVersion()
    {
        $result = null;
        $lcdv4 = substr($this->lcdv6, 0 ,4);

        // Initialize cheapest versions array
        if (!isset($this->cheapestVersionsByLcdv6AndGrCode[$lcdv4])) {
            $this->cheapestVersionsByLcdv6AndGrCode[$lcdv4] = $this->configurationEngineSelect
                ->getCheapestVersionsForLcdv6AndGrBodyStyleCodesByLcdv4(
                    $lcdv4,
                    $this->site->getCountryCode(),
                    $this->language->getLangueCode()
                );
        }

        $cheapestVersions = $this->cheapestVersionsByLcdv6AndGrCode[$lcdv4];
        if (isset($cheapestVersions[$this->lcdv6])
            && isset($cheapestVersions[$this->lcdv6][$this->groupingCode])) {
            $result = $cheapestVersions[$this->lcdv6][$this->groupingCode];
        }

        return $result;
    }

    /**
     * @param mixed $version should result from the WS ConfigurationEngineSelect call
     * @param PsaSite $site
     * @param PsaLanguage $language
     *
     * @return null|string
     */
    public function getFinishing($version, PsaSite $site, PsaLanguage $language)
    {
        $result = null;

        if ($this->options[self::OPTION_ANGLE_FINISHING]) {
            $result = $version->GrCommercialName->label;

            if (isset($version->GrCommercialName->id)) {
                /** @var PsaFinishingSite $finishingSite */
                $finishingSite = $this->finishingRepository->findOneBySiteIdAndLanguageAndCode(
                    $site->getSiteId(),
                    $language->getLangueCode(),
                    $version->GrCommercialName->id
                );
                if ($finishingSite) {
                    $result = $finishingSite->getFinition();
                }
            }
        }

        return $result;
    }

    /**
     * @param mixed $version should result from the WS ConfigurationEngineSelect call
     * @param PsaSite $site
     * @param PsaLanguage $language
     *
     * @return null|string
     */
    public function getCategoryVehicule($version, PsaSite $site, PsaLanguage $language)
    {
        $result = null;

        if ($this->options[self::OPTION_CATEGORIE_VEHICULE]) {
            $versionCriterion = $version->VersionsCriterion->VersionCriterion;

            if (isset($versionCriterion)) {
                $result = $versionCriterion[0]->label;

                /** @var PsaVehicleCategorySiteRepository $category */
                $category = $this->localVehicleCategoryRepository->findOneBySiteIdAndLanguageAndCode(
                    $site->getSiteId(),
                    $language->getLangueCode(),
                    $versionCriterion[0]->id
                );

                if ($category) {
                    $result = $category->getLabel();
                }
            }
        }

        return $result;
    }

    /**
     * @param mixed $version should result from the WS ConfigurationEngineSelect call
     * @param string $lcdv6         ex : '1PA9AF'
     * @param string $groupingCode  ex : 'S0000066'
     * @param PsaSite $site
     * @param PsaLanguage $language
     *
     * @return array
     *      'ctaDiscover'   => [
     *          'url'      => '/decouvrir/nouvelle-308/5-portes/'
     *          'isActive' => true
     *          'order'    => 1 (From BO config)
     *       ],
     *      'ctaConfigurer' => [
     *          'url'      => 'http://configurer.peugeot.fr/configurer/model=108/body=3-portes/gcn=00000174/step=1/vs=1PB1A3ERC5B0A020/'
     *          'isActive' => true (if activate in BO config)
     *          'order'    => 2 (From BO config)
     *      ]
     *
     */
    public function getActions($version, $lcdv6, $groupingCode, PsaSite $site, PsaLanguage $language)
    {
        return array(
            'ctaDiscover'   => $this->getCtaDiscover($lcdv6, $groupingCode, $site, $language),
            'ctaConfigurer' => $this->getCtaConfigurer($version, $site, $language),
        );
    }

    /**
     * @param string $lcdv6         ex : '1PA9AF'
     * @param string $groupingCode  ex : 'S0000066'
     * @param PsaSite $site
     * @param PsaLanguage $language
     *
     * @return array
     *    'url'      => '/decouvrir/nouvelle-308/5-portes/'
     *    'isActive' => true
     *    'order'    => 1
     */
    public function getCtaDiscover($lcdv6, $groupingCode, PsaSite $site, PsaLanguage $language)
    {
        $result = [
            'url' => '#',
            'isActive' => false,
            'order' => 1
        ];

        if ($this->options[self::OPTION_CTA_DISCOVER]) {
            $configuration = $this->getModelConfigurations($site->getSiteId(), $language->getLangueCode());
            $discoverOrder = ($configuration !== null) ? $configuration->getCtaDiscoverOrder() : 1;
            $url = $this->getWelcomePageShowRoomUrl($lcdv6, $groupingCode, $site, $language);

            $result = [
                'url' => $url,
                'isActive' => $url !== '',
                'order' => $discoverOrder
            ];
        }

        return $result;
    }

    /**
     * @param mixed $version should result from the WS ConfigurationEngineSelect call
     * @param PsaSite $site
     * @param PsaLanguage $language
     *
     * @return array
     *          'url'      => 'http://configurer.peugeot.fr/configurer/model=108/body=3-portes/gcn=00000174/step=1/vs=1PB1A3ERC5B0A020/'
     *          'isActive' => true (if activate in BO config)
     *          'order'    => 2
     */
    public function getCtaConfigurer($version, PsaSite $site, PsaLanguage $language)
    {
        $result = [
            'url' => '#',
            'isActive' => false,
            'order' => 2
        ];

        if ($this->options[self::OPTION_CTA_CONFIGURER]) {
            $configuration = $this->getModelConfigurations($site->getSiteId(), $language->getLangueCode());
            $configureDisplay = ($configuration !== null) ? $configuration->getCtaConfigureDisplay() : false;
            $configureOrder = ($configuration !== null) ? $configuration->getCtaConfigureOrder() : 2;

            if ($configureDisplay) {
                $result = [
                    'url' => $this->getCtaConfigurerUrl($version, $site),
                    'isActive' => $configureDisplay,
                    'order' => $configureOrder
                ];
            }
        }

        return $result;
    }

    /**
     * Get URL by Parsing BO configurator URL for $site and replace balise ##PARAM## with correct value from $version
     * Example :
     * http://configurer.peugeot.fr/configurer/model=##MODEL##/body=##GR_BODY_STYLE##/gcn=##GR_COMMERCIAL_NAME##/step=##STEP##/vs=##LCDV16##/
     * => 'http://configurer.peugeot.fr/configurer/model=108/body=3-portes/gcn=00000174/step=1/vs=1PB1A3ERC5B0A020/'
     *
     * @param mixed $version should result from the WS ConfigurationEngineSelect call
     * @param PsaSite $site
     *
     * @return string ex : 'http://configurer.peugeot.fr/configurer/model=108/body=3-portes/gcn=00000174/step=1/vs=1PB1A3ERC5B0A020/'
     */
    private function getCtaConfigurerUrl($version, PsaSite $site)
    {
        $url = '';

        // Get from Administration "Cta Configure" base url to use
        $urlConfigurer = null;
        $sitesEtWebservices = $this->sitesEtWebServicesRepository->findOneBySiteId($site->getSiteId());
        if ($sitesEtWebservices !== null && $sitesEtWebservices->getZoneVp()) {
            $urlConfigurer = $sitesEtWebservices->getZoneVpUrl();
        }

        if ($urlConfigurer !== null) {
            $modelLabel = str_replace(' ', '-', $version->Model->label);
            $bodyStyleLabel = str_replace(' ', '-', $version->GrbodyStyle->label);
            $lcdv16 = $version->IdVersion->id;
            $codeFinition = $version->GrCommercialName->id;

            $search = array('##MODEL##', '##GR_BODY_STYLE##', '##STEP##', '##LCDV16##', '##GR_COMMERCIAL_NAME##');
            $replace = array($modelLabel, $bodyStyleLabel, 1, $lcdv16, $codeFinition);
            $url = str_replace($search, $replace, $urlConfigurer);
        }

        return $url;
    }


    /**
     * @param string $lcdv6         ex : '1PA9AF'
     * @param string $groupingCode  ex : 'S0000066'
     * @param PsaSite $site
     * @param PsaLanguage $language
     *
     * @return string
     */
    private function getWelcomePageShowRoomUrl($lcdv6, $groupingCode, PsaSite $site, PsaLanguage $language)
    {
        $clearUrl = '';
        $showroomWelcomePage = $this->pageRepository->findFirstShowroomPublishedWelcomePageForLcdv6AndSilouhette(
            $lcdv6, $groupingCode, $site, $language
        );

        // Get First one
        if ($showroomWelcomePage !== null) {
            $clearUrl = $showroomWelcomePage->getRoutePattern();
        }

        return $clearUrl;
    }

    /**
     * @param string $lcdv6 ex : '1PA9AF'
     *
     * @return array|null
     */
    public function getReeVoo($lcdv6)
    {
        $result = null;

        if ($this->options[self::OPTION_CTA_CONFIGURER]) {
            $result = array(
                'url' => $this->generateReeVooUrl($lcdv6)
            );
        }

        return $result;
    }

    /**
     * @param string $lcdv6 ex : '1PA9AF'
     *
     * @return string ex : http://mark.reevoo.com/partner/PMC/1PB1A3
     */
    public function generateReeVooUrl($lcdv6)
    {
        return self::REVOO_BASE_URL . $lcdv6;
    }

    /**
     * @param PsaModelSilhouetteSite $modelSilouhetteSite
     * @param PsaSite $site
     * @param PsaLanguage $language
     *
     * @return null|string
     * @throws \Exception
     */
    public function getCommercialStrip(PsaModelSilhouetteSite $modelSilouhetteSite, PsaSite $site, PsaLanguage $language)
    {
        if ($this->translator === null) {
            throw new \Exception('You need to set the translator before generating commercial strip');
        }
        $result = null;

        if ($this->options[self::OPTION_COMMERCIAL_STRIP]) {
            $configuration = $this->getModelConfigurations($site->getSiteId(), $language->getLangueCode());

            $activeIdStrip = $configuration->getFirstActiveStripOrderFromModelSilouhette($modelSilouhetteSite);

            switch ($activeIdStrip) {
                case PsaModelConfig::STRIP_NEW:
                    $result = $this->trans('NDP_COMMERCIAL_STRIP_NEW');
                    break;
                case PsaModelConfig::STRIP_SPECIAL_OFFER:
                    $result = $this->trans('NDP_COMMERCIAL_STRIP_SPECIAL_OFFER');
                    break;
                case PsaModelConfig::STRIP_SPECIAL_SERIES:
                    $result = $this->trans('NDP_COMMERCIAL_STRIP_NEW_SPECIAL_SERIES');
                    break;
            }
        }

        return $result;
    }

    /**
     * @param string $siteId
     * @param string $languageCode
     *
     * @return PsaModelConfig|null
     */
    private function getModelConfigurations($siteId, $languageCode)
    {
        $key = $siteId.'-'.$languageCode;
        if (!isset($this->modelConfigurations[$key]) || $this->modelConfigurations[$key] === null) {
            /** @var PsaModelConfig $configuration */
            $configBySiteAndLang = $this->modelConfigRepository->findOneBySiteIdAndLanguageCode(
                $siteId,
                $languageCode
            );

            $this->modelConfigurations[$key] = $configBySiteAndLang;
        }

        return $this->modelConfigurations[$key];
    }
}
