<?php

namespace PsaNdp\MappingBundle\Utils;

use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PsaNdp\MappingBundle\Entity\PsaModelSite;
use PsaNdp\MappingBundle\Repository\PsaFinishingSiteRepository;
use PsaNdp\MappingBundle\Repository\PsaSitesEtWebservicesPsaRepository;
use PsaNdp\MappingBundle\Repository\PsaWebserviceRepository;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelConfigRepository;
use PsaNdp\MappingBundle\Repository\PsaModelViewAngleRepository;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;
use PsaNdp\WebserviceConsumerBundle\Webservices\RangeManager;

/**
 * Generate a $modelSilouhette Array from a PsaModelSilhouetteSite (lcdv6-GrBodyStyle) with its cheapest available version
 * The Model Array result can be used by the Object ModelSilouhette
 *
 * Usage:
 *
 * // 1. Reset and set appropriate options before each call if needed
 * $this->modelSilouhetteSiteUtils->resetOptions();
 * $this->modelSilouhetteSiteUtils->setOptions(
 *      ModelSilouhetteSiteUtils::OPTION_IMG_URL_PARAMETERS,
 *      ['width' => '218', 'height' => '224']
 * );
 * $this->modelSilouhetteSiteUtils->setOptions(ModelSilouhetteSiteUtils::OPTION_REEVOO, false);
 *
 * // 2. Generate Model Array
 * $result[] = $this->modelSilouhetteSiteUtils->generateModelSilouhetteData($modelsSilouhetteSite);
 *
 *
 * Class ModelSiteUtils
 *
 * @package PsaNdp\MappingBundle\Utils
 */
class ModelSiteUtils {

    const SERVICE_KEY_BAIE_VISUELS_3D = 'WS_V3D';
    const REVOO_BASE_URL = 'http://mark.reevoo.com/partner/PMC/';
    // Check resetOptions() for options usage examples
    const OPTION_IMG = 'IMG';
    const OPTION_IMG_URL_PARAMETERS_DESKTOP = 'IMG_URL_PARAMETERS_DESKTOP';
    const OPTION_IMG_URL_PARAMETERS_MOBILE = 'IMG_URL_PARAMETERS_MOBILE';
    const OPTION_ANGLE_FINISHING = 'FINISHING';
    const OPTION_CATEGORIE_VEHICULE= 'CATEGORIE_VEHICULE';
    const OPTION_SHOWROOM_URL = 'SHOWROOM_URL';
    const OPTION_COMMERCIAL_STRIP = 'COMMERCIAL_STRIP'; // "Languette"
    const OPTION_CTA_DISCOVER = 'CTA_DISCOVER';
    const OPTION_CTA_CONFIGURER = 'CTA_CONFIGURER';
    const OPTION_REEVOO = 'REEVOO';
    // Slice Id for generating specific behavior if needed by slice
    // This slidce Id should notbe needed if the ISOBAR template are refactorize or the different slice
    const SLICE_DEFAULT = 'DEFAULT';
    const SLICE_PF23 = 'PF23';
    const SLICE_PF27 = 'PF27';
    const SLICE_PC95 = 'PC95';

    use TranslatorAwareTrait;

    /**
     * @var string
     */
    protected $lcdv4;

    /**
     *
     * @var PsaModelViewAngleRepository 
     */
    private $modelViewAngleRepository;

    /**
     * cheapest version of the vehicule from range manager
     * @var array
     */
    protected $cheapest;

    /**
     *  current version of the vehicule from config engine
     * @var  stdClass
     */
    protected $version;

    /**
     * @var ConfigurationEngineSelect
     */
    protected $configurationEngineSelect;

    /**
     * @var RangeManager
     */
    protected $rangeManager;

    /**
     * @var PsaSite
     */
    protected $site;

    /**
     * @var PsaLanguage
     */
    protected $language;

    /**
     * @var bool
     */
    protected $isMobile;

    /**
     * @param PsaPageRepository $pageRepository
     * @param PsaModelViewAngleRepository $modelViewAngleRepository
     * @param PsaWebserviceRepository $webserviceRepository
     * @param PsaSitesEtWebservicesPsaRepository $sitesEtWebServicesRepository
     * @param PsaModelConfigRepository $modelConfigRepository
     * @param ConfigurationEngineSelect $configurationEngineSelect
     * @param RangeManager $rangeManager
     */
    public function __construct(
        PsaPageRepository $pageRepository,
        PsaModelViewAngleRepository $modelViewAngleRepository,
        PsaWebserviceRepository $webserviceRepository,
        PsaSitesEtWebservicesPsaRepository $sitesEtWebServicesRepository,
        PsaModelConfigRepository $modelConfigRepository,
        ConfigurationEngineSelect $configurationEngineSelect,
        RangeManager $rangeManager
    )
    {
        $this->pageRepository = $pageRepository;
        $this->modelViewAngleRepository = $modelViewAngleRepository;
        $this->webserviceRepository = $webserviceRepository;
        $this->sitesEtWebServicesRepository = $sitesEtWebServicesRepository;
        $this->modelConfigRepository = $modelConfigRepository;
        $this->configurationEngineSelect = $configurationEngineSelect;
        $this->rangeManager = $rangeManager;

        // Initialize defaults generation options
        $this->resetOptions();
    }

    
   
    /**
     * @param PsaModelSite $modelSite
     * @param bool $isMobile
     * @param null $options
     * @param null $sliceId
     *
     * @return array|null
     * @throws \Exception
     */
    public function generateModelData(PsaModelSite $modelSite, $isMobile = false, $options = null, $sliceId = null) {
        if ($this->translator === null) {
            throw new \Exception('You need to set the translator before generating a data for PsaModelSite');
        }
        $model = null;

        if ($options !== null) {
            $this->options = array_merge($this->options, $options);
        }
        if ($sliceId === null) {
            $sliceId = self::SLICE_DEFAULT;
        }

        $this->lcdv4 = $modelSite->getLcdv4();
        $this->site = $modelSite->getSite();
        $this->language = $modelSite->getLanguage();
        $this->isMobile = $isMobile;

        $cheapestVersion = $this->rangeManager->getCheapestByLcdv4($this->lcdv4, $this->site->getCountryCode(), $this->language->getLangueCode() );
        if ($cheapestVersion !== null) {

            $model = [
                'id'              => $cheapestVersion->IdVersion->id,
                'cheapest'        => $cheapestVersion,
                'version'         => $this->getConfigVersionFromCheapest($cheapestVersion['LCDV16']),
                'silhouetteTitle' => $modelSite->getModel()->getModel(),
                'sliceId'         => $sliceId,
                'img'             => $this->getImg($cheapestVersion, $this->lcdv4, $this->isMobile),
                'isMobile'        => $isMobile
            ];
        }

        return $model;
    }

    private function getConfigVersionFromCheapest($lcdv16){

      return  $this->configurationEngineSelect->getVersionByLCDV16($lcdv16);
    }


    /**
     * @param string $option should use one of the const OPTION_XXX defined
     * @param bool|array $value
     *
     * @return ModelSilouhetteSiteUtils
     */
    public function setOptions($option, $value) {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * Reset Options default values for generating model Array result
     *
     * @return $this
     */
    public function resetOptions()
    {
        $this->options = [
            self::OPTION_IMG => true,
            self::OPTION_IMG_URL_PARAMETERS_DESKTOP => [
                'width' => '350',
                'height' => '162'
            ],
            self::OPTION_IMG_URL_PARAMETERS_MOBILE => [
                'width' => '210',
                'height' => '97'
            ],
            self::OPTION_ANGLE_FINISHING => true,
            self::OPTION_CATEGORIE_VEHICULE => true,
            self::OPTION_SHOWROOM_URL => true,
            self::OPTION_COMMERCIAL_STRIP => true,
            self::OPTION_CTA_DISCOVER => true,
            self::OPTION_CTA_CONFIGURER => true,
            self::OPTION_REEVOO => true
        ];

        return $this;
    }

    /**
     * @param mixed $version should result from the WS ConfigurationEngineSelect call
     * @param string $lcdv  ex : '1PA9AF or 1PA9'
     * @param bool $isMobile
     *
     * @return null|string ex : http://visuel3d.peugeot.com/V3DImage.ashx?client=websimulator&format=png&version=1PB1A3ERC5B0A020&view=&width=350&height=162
     */
    protected function getImg($version, $lcdv, $isMobile)
    {
        $result = array(
            'src' => ''
        );

        if ($this->options[self::OPTION_IMG]) {
            $result = array(
                'src' => $this->generateImgUrl($version, $lcdv, $isMobile)
            );
        }

        return $result;
    }

    /**
     * @param mixed $version should result from the WS ConfigurationEngineSelect call
     * @param string $lcdv4  ex : '1PA9'
     * @param bool $isMobile
     *
     * @return string TOD where is it it for refacto
     */
    public function generateImgUrl($version, $lcdv4, $isMobile)
    {
        $modelAngle = $this->modelViewAngleRepository->findInitialAngleByLcdv4($lcdv4);

        $viewAngle = '001'; //par défaut
        if ($modelAngle) {
            $viewAngle = $modelAngle->getCode();
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
            'version' => $version->IdVersion->id,
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
     * 
     * @return bool
     */
    public function getIsMobile() {

        return $this->isMobile;
    }

    /**
     * 
     * @param bool $isMobile
     * @return ModelSilouhetteSiteUtils
     */
    public function setIsMobile($isMobile) {

        $this->isMobile = $isMobile;

        return $this;
    }

    public function getLcdv4()
    {
        return $this->lcdv4;
    }

    public function getSite()
    {
        return $this->site;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLcdv4($lcdv4)
    {
        $this->lcdv4 = $lcdv4;
    }

    public function setSite(PsaSite $site)
    {
        $this->site = $site;
    }

    public function setLanguage(PsaLanguage $language)
    {
        $this->language = $language;
    }

}
