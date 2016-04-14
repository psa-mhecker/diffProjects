<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Repository\PsaCtaRepository;
use PsaNdp\MappingBundle\Entity\PsaSitesEtWebservicesPsa;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelConfig;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;
use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Filters\Filters;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineCompareGrade;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineConfig;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineEngineCriteria;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Pf5358FinitionsMotorisations
 */
class Pf5358FinitionsMotorisations extends Content
{
    const VEHICLE_V3D_BASE_URL = 'http://visuel3d.peugeot.com/V3DImage.ashx?client=CFGAP3D&version=';
    const EQUIPMENT_V3D_BASE_URL = 'http://configurateur3d.peugeot.com/CFG3PSite/Images/V3DCentral/';
    const MOTOR_V3D_BASE_URL = 'http://configurateur3d.peugeot.com/CFG3PSite/Images/V3DCentral/';
    const ECOLABEL_V3D_BASE_URL = 'http://configurateur3d.peugeot.com/CFG3PSite/Images/V3DCentral/logo/peugeot_';
    const CTA_REF = 2;
    const CTA_NEW = 3;

    protected $mapping = array(
        'datalayer' => 'dataLayer',
        'ctaLink' => 'ctaList',
        'img' => 'image',
    );


    /**
     * @var Filters $filters
     */
    protected $filters;

    /**
     * @var array $comparator
     */
    protected $comparator;


    /**
     * @var PsaModelConfig $configuration
     */
    protected $configuration;

    /**
     * @var array $vehicleCategory
     */
    protected $vehicleCategory;

    /**
     * @var PsaModelSilhouetteSite $silhouette
     */
    protected $silhouette;

    /**
     * @var string
     */
    protected $silhouetteTitle;

    /**
     * @var array model
     */
    protected $model;

    /**
     * @var ConfigurationEngineCompareGrade
     */
    protected $compareGrade;

    /**
     * @var array
     */
    protected $version;

    /**
     * @var string
     */
    protected $lcdv16;

    /**
     * @var array
     */
    protected $finishing;

    /**
     * @var ConfigurationEngineConfig
     */
    protected $configurationConfig;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var string
     */
    protected $languageCode;

    /**
     * @var ConfigurationEngineSelect
     */
    protected $configurationSelect;

    /**
     * @var array
     */
    protected $siteSettings;

    /**
     * @var array
     */
    protected $sfg;

    /**
     * @var int
     */
    protected $angleView;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var mixed
     */
    protected $engine;

    /**
     * @var mixed
     */
    protected $trans;
    /**
     * @var string
     */
    protected $domain;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var array
     */
    protected $finishingReference;

    /**
     * @var array
     */
    protected $urlVp;

    /**
     * @var string
     */
    protected $category;

   /**
     * @var array $series
     */
    protected $series;

    /**
     * @var PsaCtaRepository
     */
    protected $ctaRepository;

    /**
     * @var string $legalMentions
     */
    protected $legalMentions;

    /**
     * @var string $furtherInfos
     */
    protected $furtherInfos;

    /**
     * @var string $diverseMentions
     */
    protected $diverseMentions;

    /**
     * @var  boolean
     */
    protected $typecash;
    /**
     * @var  boolean
     */
    protected $typemonthly;

    /**
     * @var string
     */
    protected $errorTxt;

    /**
     * @var string $id
     */
    protected $id;

    /**
     * @var string
     */
    protected $segmentTitle;

    /**
     * @var PriceManager
     */
    protected $priceManager;

    /**
     * @var ConfigurationEngineEngineCriteria
     */
    protected $engineCriteria;

    /**
     * @var PsaSitesEtWebservicesPsa
     */
    protected $siteAndWebservices;

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param array $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return array
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return PsaModelSilhouetteSite
     */
    public function getSilhouette()
    {
        return $this->silhouette;
    }

    /**
     * @param PsaModelSilhouetteSite $silhouette
     *
     * @return $this
     */
    public function setSilhouette(PsaModelSilhouetteSite $silhouette = null)
    {
       $this->silhouette = $silhouette;

        return $this;
    }

    /**
     * @return string
     */
    public function getSilhouetteTitle()
    {
        $return =  $this->version->Model->label.' '.$this->version->GrbodyStyle->label;
        if($this->silhouette) {
            $return = $this->silhouette->getCommercialLabel();
        }

        return $return;
    }

    /**
     * @param string $silhouetteTitle
     *
     * @return $this
     */
    public function setSilhouetteTitle($silhouetteTitle)
    {
        $this->silhouetteTitle = $silhouetteTitle;

        return $this;
    }

    /**
     * @return array
     */
    public function getVehicleCategory()
    {
        return $this->vehicleCategory;
    }

    /**
     * @param array $vehicleCategory
     */
    public function setVehicleCategory($vehicleCategory)
    {
        $this->vehicleCategory = $vehicleCategory;
    }

    /**
     * @return ConfigurationEngineCompareGrade
     */
    public function getCompareGrade()
    {
        return $this->compareGrade;
    }

    /**
     * @param ConfigurationEngineCompareGrade $compareGrade
     */
    public function setCompareGrade($compareGrade)
    {
        $this->compareGrade = $compareGrade;
    }

    /**
     * @return array
     */
    public function getFinishing()
    {
        return $this->finishing;
    }

    /**
     * @param array $finishing
     */
    public function setFinishing($finishing)
    {
        $this->finishing = $finishing;
    }

    /**
     * @return string
     */
    public function getLcdv16()
    {
        return $this->lcdv16;
    }

    /**
     * @param string $lcdv16
     */
    public function setLcdv16($lcdv16)
    {
        $this->lcdv16 = $lcdv16;
    }

    /**
     * @return array
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param array $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return ConfigurationEngineConfig
     */
    public function getConfigurationConfig()
    {
        return $this->configurationConfig;
    }

    /**
     * @param ConfigurationEngineConfig $configurationConfig
     */
    public function setConfigurationConfig($configurationConfig)
    {
        $this->configurationConfig = $configurationConfig;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    /**
     * @param string $languageCode
     */
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;
    }

    /**
     * @return ConfigurationEngineSelect
     */
    public function getConfigurationSelect()
    {
        return $this->configurationSelect;
    }

    /**
     * @param ConfigurationEngineSelect $configurationSelect
     */
    public function setConfigurationSelect($configurationSelect)
    {
        $this->configurationSelect = $configurationSelect;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return array
     */
    public function getSiteSettings()
    {
        return $this->siteSettings;
    }

    /**
     * @param array $siteSettings
     */
    public function setSiteSettings($siteSettings)
    {
        if (isset($siteSettings['CURRENCY_SYMBOL'])) {
            $this->currency = $siteSettings['CURRENCY_SYMBOL'];
        }
        $this->siteSettings = $siteSettings;
    }

    /**
     * @return array
     */
    public function getSfg()
    {
        return $this->sfg;
    }

    /**
     * @param array $sfg
     *
     * @return $this
     */
    public function setSfg(array $sfg)
    {
        $this->sfg = $sfg;

        return $this;
    }

    /**
     * @return int
     */
    public function getAngleView()
    {
        return $this->angleView;
    }

    /**
     * @param int $angleView
     *
     * @return $this
     */
    public function setAngleView($angleView)
    {
        $this->angleView = $angleView;

        return $this;
    }

    /**
     * @return array
     */
    public function getFinishingReference()
    {
        return $this->finishingReference;
    }

    /**
     * @param array $finishingReference
     *
     * @return $this
     */
    public function setFinishingReference($finishingReference)
    {
        $this->finishingReference = $finishingReference;

        return $this;
    }

    /**
     * @return array
     */
    public function getUrlVp()
    {
        return $this->urlVp;
    }

    /**
     * @param array $urlVp
     *
     * @return $this
     */
    public function setUrlVp($urlVp)
    {
        $this->urlVp = $urlVp;

        return $this;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     *
     * @return Pf5358FinitionsMotorisations
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getTypecash()
    {
        return $this->typecash;
    }

    /**
     * @param boolean $typecash
     *
     * @return $this
     */
    public function setTypecash($typecash)
    {
        $this->typecash = $typecash;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getTypemonthly()
    {
        return $this->typemonthly;
    }

    /**
     * @param boolean $typemonthly
     *
     * @return $this
     */
    public function setTypemonthly($typemonthly)
    {
        $this->typemonthly = $typemonthly;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorTxt()
    {
        return $this->errorTxt;
    }

    /**
     * @param string $errorTxt
     */
    public function setErrorTxt($errorTxt)
    {
        $this->errorTxt = $errorTxt;
    }

    /**
     * @return string
     */
    public function getSegmentTitle()
    {
        $segment = '';
        if (!empty($this->segmentTitle)) {
            $segment = $this->segmentTitle.' ';
        }

        return $segment;
    }

    /**
     * @param string $segment
     *
     * @return $this
     */
    public function setSegmentTitle($segment)
    {
        $this->segmentTitle = $segment;

        return $this;
    }

    /**
     * @return PriceManager
     */
    public function getPriceManager()
    {
        return $this->priceManager;
    }

    /**
     * @param PriceManager $priceManager
     *
     * @return $this
     */
    public function setPriceManager($priceManager)
    {
        $this->priceManager = $priceManager;

        return $this;
    }

    /**
     * @return ConfigurationEngineEngineCriteria
     */
    public function getEngineCriteria()
    {
        return $this->engineCriteria;
    }

    /**
     * @param ConfigurationEngineEngineCriteria $engineCriteria
     */
    public function setEngineCriteria($engineCriteria)
    {
        $this->engineCriteria = $engineCriteria;
    }

    /**
     * @return PsaSitesEtWebservicesPsa
     */
    public function getSiteAndWebservices()
    {
        return $this->siteAndWebservices;
    }

    /**
     * @param PsaSitesEtWebservicesPsa $siteAndWebservices
     *
     * @return $this
     */
    public function setSiteAndWebservices($siteAndWebservices)
    {
        $this->siteAndWebservices = $siteAndWebservices;

        return $this;
    }

    /**
     * @param TranslatorInterface $translator
     * @param string              $domain
     * @param string              $locale
     */
    public function setTranslator($translator, $domain, $locale)
    {
        $this->domain = $domain;
        $this->locale = $locale;
        $this->translator = $translator;
    }

    /**
     * @param $id
     * @param array $parameters
     * @param null  $domain
     * @param null  $locale
     *
     * @return mixed
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {

        if ($domain == null) {
            $domain = $this->domain;
        }
        if ($locale == null) {
            $locale = $this->locale;
        }

        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @return mixed
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param mixed $engine
     *
     * @return Pf5358FinitionsMotorisations
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTrans()
    {
        return $this->trans;
    }

    /**
     * @param mixed $trans
     *
     * @return Pf5358FinitionsMotorisations
     */
    public function setTrans($trans)
    {
        $this->trans = $trans;

        return $this;
    }

    /**
     * @return Filters
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param Filters $filters
     *
     * @return $this
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;

        return $this;
    }


    /**
     * @return array
     */
    public function getComparator()
    {
        return $this->comparator;
    }

    /**
     * @param array $comparator
     *
     * @return $this
     */
    public function setComparator(array $comparator)
    {
        $this->comparator = $comparator;

        return $this;
    }

    /**
     * @return string
     */
    public function getFurtherInfos()
    {
        return $this->furtherInfos;
    }

    /**
     * @param string $furtherInfos
     *
     * @return $this
     */
    public function setFurtherInfos($furtherInfos)
    {
        $this->furtherInfos = $furtherInfos;

        return $this;
    }

    /**
     * @return string
     */
    public function getDiverseMentions()
    {
        return $this->diverseMentions;
    }

    /**
     * @param string $diverseMentions
     *
     * @return $this
     */
    public function setDiverseMentions($diverseMentions)
    {
        $this->diverseMentions = $diverseMentions;

        return $this;
    }

    /**
     * @return string
     */
    public function getLegalMentions()
    {
        return $this->legalMentions;
    }

    /**
     * @param string $legalMentions
     *
     * @return $this
     */
    public function setLegalMentions($legalMentions)
    {
        $this->legalMentions = $legalMentions;

        return $this;
    }

    /**
     *
     * @return bool
     */
    protected function isMensualPriceActive()
    {
        return ($this->siteSettings['VEHICULE_PRICE_MONTHLY_DISPLAY'] == 1);
    }

    /**
     * @return array
     */
    protected  function getPriceFilters(){

        return array(
            'title' =>  $this->translate['NDP_PRICE'],
            'radio1' => array(
                'label' =>  $this->translate['NDP_CASH'],
                'id' => 'cash'
            ),
            'radio2' => array(
                'label' =>  $this->translate['NDP_MONTHLY'],
                'id' => 'monthly'
            )
        );
    }

    /**
     * @param $version
     *
     * @return array
     */
    protected function getMotorInfos($version)
    {

        $infos = array();
        if (isset($version->Co2Rate)) {
            $infos[] = array('val' => $version->Co2Rate, 'label' => $this->translate['totalCO2']); // Total émission de CO2 (g/km)
        }
        if (isset($version->Co2Class)) {
            $infos[] = array('val' => $version->Co2Class, 'label' => $this->translate['energyClass']); // Classe energetique
        }
        if (isset($version->MixedConsumption)) {
            $infos[] = array('val' => $version->MixedConsumption, 'label' => $this->translate['consumption']); //Consommation (l/100km)
        }
        if (isset($version->Characteristic1)) {
            $infos[] = array('val' => $version->Characteristic1, 'label' => $this->translate['power']); // Puissance (kw)
        }

        return $infos;

    }

    /**
     * @param $version
     * @param $finition
     *
     * @return array
     */
    protected function getMotorInfosSup($version, $finition = false)
    {
        $engine = $version;
        if ($finition) {
            $engine = $this->engineCriteria->engineCriteria($version->IdVersion->id);
        }

        $infosSup = [];
        if (isset($engine->TechnicalCharacteristics->Category[2]->TechnicalCharacteristic[0])) {
           $infosSup[] = array(
               'val' => $engine->TechnicalCharacteristics->Category[2]->TechnicalCharacteristic[0]->value,
               'label' => $engine->TechnicalCharacteristics->Category[2]->TechnicalCharacteristic[0]->label,
           );
        }
        if (isset($engine->TechnicalCharacteristics->Category[2]->TechnicalCharacteristic[1])) {
           $infosSup[] = array(
               'val' => $engine->TechnicalCharacteristics->Category[2]->TechnicalCharacteristic[1]->value,
               'label' => $engine->TechnicalCharacteristics->Category[2]->TechnicalCharacteristic[1]->label,
           );
        }
        if (isset($engine->TechnicalCharacteristics->Category[2]->TechnicalCharacteristic[2])) {
           $infosSup[] = array(
               'val' => $engine->TechnicalCharacteristics->Category[2]->TechnicalCharacteristic[2]->value,
               'label' => $engine->TechnicalCharacteristics->Category[2]->TechnicalCharacteristic[2]->label,
           );
        }
        if (isset($engine->TechnicalCharacteristics->Category[2]->TechnicalCharacteristic[3])) {
           $infosSup[] = array(
               'val' => $engine->TechnicalCharacteristics->Category[2]->TechnicalCharacteristic[3]->value,
               'label' => $engine->TechnicalCharacteristics->Category[2]->TechnicalCharacteristic[3]->label,
           );
        }
        if (isset($engine->Co2Class)) {
           $infosSup[] = array('val' => $engine->Co2Class, 'label' => $this->translate['energyClass']);
        }


        return $infosSup;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setCtaError($title)
    {
        switch ($this->configuration->getCtaErreur())
        {
            case self::CTA_REF:
                $cta = $this->ctaRepository->find($this->configuration->getCtaErreurId());
                $this->setCtaList(array(
                    array(
                        'style' => 'STYLE_SIMPLELINK',
                        'url' => $cta->getAction(),
                        'url' => '/',
                        'title' => $title,
                        'target' => $this->configuration->getCtaErreurTarget(),
                        'class' => 'close back',
                    ),
                ));
                break;
            case self::CTA_NEW:
                $this->setCtaList(array(
                    array(
                        'style' => 'STYLE_SIMPLELINK',
                        'url' => $this->configuration->getCtaErreurAction(),
                        'title' => $title,
                        'target' => $this->configuration->getCtaErreurTarget(),
                        'class' => 'close back',
                    )
                ));
                break;
            default:
                //nothing
                break;
        }

        return $this;
    }

    /**
     * @param array $series
     *
     * @return array
     */
    protected function groupByMotorisation(array $series)
    {
        $result = [];
        $id = null;

        $series = $this->sortByAscendingOrder($series);

        foreach($series as $key => $serie){
            $serie['version']->finitions = [];

            if (empty($serie['finishingReference']) && $id !== null) {
                $serie['finishingReference'] = $series[$id]['version'];
            }
            $id = $key;

            $motor = $serie['version']->GrEngine->id.'-'.$serie['version']->TransmissionType->id;
            $serie['id'] = $motor;
            if (!isset($result[$motor])) {
                $result[$motor] = [];
            }
            if (array_key_exists($motor, $result) && array_key_exists(
                    $motor,
                    $result[$motor]
                )
            ) {
                $array = $result[$motor];
                if ($serie['version']->Price->netPrice < $array['version']->Price->netPrice) {
                    $serie['version']->finitions = $array['version']->finitions;
                    $result[$motor] = $serie;
                }
                $result[$motor]['version']->finitions[] = $serie['version'];
            } else {
                $serie['version']->finitions[] = $serie['version'];
                $result[$motor] = $serie;
            }
        }

        return $this->groupByEnergyAndPrice($result);
    }

    /**
     * @param array $series
     *
     * @return array
     */
    public function groupByEnergyAndPrice(array $series)
    {
        $energies = array();
        $return = array();

        // Ordonne par énergies
        foreach ($series as $serie) {
            $energyId = $serie['version']->Energy->id;
            $energies[$energyId][] = $serie;
        }

        if ($this->configuration instanceof PsaModelConfig && $this->configuration->getFinishingOrder() !== 0) {
            // Trie par prix
            foreach ($energies as $serie) {

                $this->sortByFinishingOrder($serie);

                $return[] = $serie;
            }
        } else {
            $return = $energies;
        }

        return $return;
    }

    /**
     * @param array $table
     *
     * @return mixed
     */
    public function sortByFinishingOrder(array $table)
    {
        // Ordonner par prix croissant
        if ($this->configuration instanceof PsaModelConfig && $this->configuration->getFinishingOrder() === 2) {
            $table = $this->sortByAscendingOrder($table);
        } //Ordonner par prix décroissant
        elseif ($this->configuration instanceof PsaModelConfig && $this->configuration->getFinishingOrder() === 3) {
            $table = $this->sortByDescendingOrder($table);
        }

        return $table;
    }

    /**
     * @param $table
     *
     * @return array
     */
    public function sortByAscendingOrder(array $table)
    {
        uasort(
            $table,
            function ($table1, $table2) {
                $price1 = $table1['version']->Price->netPrice;
                $price2 = $table2['version']->Price->netPrice;

                if ($price1 === $price2) {
                    return 0;
                }

                return ($price1 > $price2);
            }
        );

        return $table;
    }

    /**
     * @param array $table
     *
     * @return array
     */
    public function sortByDescendingOrder(array $table)
    {
        uasort(
            $table,
            function ($table1, $table2) {
                $price1 = $table1['version']->Price->netPrice;
                $price2 = $table2['version']->Price->netPrice;

                if ($price1 === $price2) {
                    return 0;
                }

                return ($price1 < $price2);
            }
        );

        return $table;
    }
}
