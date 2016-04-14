<?php

namespace PsaNdp\MappingBundle\Transformers;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Entity\PsaSitesEtWebservicesPsa;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelConfig;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;
use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Object\Block\Pf53Finitions;
use PsaNdp\MappingBundle\Object\Block\Pf53Object\Pf53FinitionsMobile;
use PsaNdp\MappingBundle\Object\Block\Pf58Object\Pf58MotorisationMobile;
use PsaNdp\MappingBundle\Object\Block\Pf58Object\Pf58Motorisations;

/**
 * Class Pf53FinitionsMotorisationDataTransformer
 * Data transformer for Pf53FinitionsMotorisation block
 */
class Pf53FinitionsMotorisationDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const DISPLAY_FINITIONS = 1;
    const DISPLAY_MOTORISATIONS = 2;
    const DISPLAY_FINITIONS_MOTORISATIONS = 3;
    const FIRST_MOTORISATIONS = 2;
    const MONTHLY = 'MENSUALISE';
    const VEHICLE_V3D_BASE_URL = 'http://visuel3d.peugeot.com/V3DImage.ashx?client=CFGAP3D&version=';

    /**
     * @var Pf53Finitions
     */
    protected $pf53Finitions;

    /**
     * @var Pf58Motorisations
     */
    protected $pf58Motorisations;

    /**
     * @var Pf58MotorisationMobile
     */
    protected $pf58MotorisationMobile;

    /**
     * @var Pf53FinitionsMobile
     */
    protected $pf53FinitionMobile;

    /**
     * @var PriceManager
     */
    protected $priceManager;

    /**
     * @param Pf53Finitions          $pf53Finitions
     * @param Pf58Motorisations      $motorisations
     * @param Pf53FinitionsMobile    $finitionsMobile
     * @param Pf58MotorisationMobile $motorisationMobile
     * @param PriceManager           $priceManager
     */
    public function __construct(
        Pf53Finitions $pf53Finitions,
        Pf58Motorisations $motorisations,
        Pf53FinitionsMobile $finitionsMobile,
        Pf58MotorisationMobile $motorisationMobile,
        PriceManager $priceManager
    )
    {
        $this->pf53Finitions = $pf53Finitions;
        $this->pf58Motorisations = $motorisations;
        $this->pf53FinitionMobile = $finitionsMobile;
        $this->pf58MotorisationMobile = $motorisationMobile;
        $this->priceManager = $priceManager;
    }

    /**
     *  Fetching data slice Finitions (pf53)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        /**
         * @var $block PsaPageZoneConfigurableInterface
         */
        $block = $dataSource['block'];
        $affichage = $block->getZoneAttribut();
        $comparatorTable = $block->getZoneAttribut2();

        // Affiché si activé dans la tranche et si au moin 2 version pour le model regroupement de silhouette
        if ($dataSource['configuration']->getShowComparisonChart() && $comparatorTable) {
            if (count($dataSource['series']) >= 2) {
                $dataSource['comparator'] = array('access' => $this->trans('NDP_COMPARATOR_TABLE_ACCESS')); //'Accédez au tableau comparateur'
            }
        }

        $typeCash = $this->getTypeCash($dataSource['siteSettings'], $dataSource['siteId']);
        $dataSource['typecash'] = $typeCash['typecash'];
        if (isset($typeCash['typemonthly'])) {
            $dataSource['typemonthly'] = $typeCash['typemonthly'];
        }

        $version = '';
        $title = '';
        if (isset($dataSource['cheapest']['version'])) {
            $version = $dataSource['cheapest']['version'];
            $title = $dataSource['cheapest']['version']->Model->label.' '.$dataSource['cheapest']['version']->GrbodyStyle->label;
        }

        $urlConfigure = '#';
        if (isset($dataSource['cheapest']['urlVp']['urlConfigure'])){
            $urlConfigure = $dataSource['cheapest']['urlVp']['urlConfigure'];
        }


        $dataSource['translate'] = array(
            'price' => $this->trans('NDP_PRICE'), // prix
            'cash' => $this->trans('NDP_CASH'), //comptant
            'monthly' => $this->trans('NDP_MONTHLY'), //mensuel
            'information' => $this->trans('i'), //i
            'close' => $this->trans('NDP_CLOSE'),
            'closed' => $this->trans('NDP_CLOSED'),
            'title' => $title,
            'finitionSelect' => $this->trans('NDP_SELECT_FINISH'), // Finition select
            'petrol' => $this->trans('NDP_GASOLINE'),//Essence
            'diesel' => $this->trans('NDP_DIESEL'),//Diesel
        );

        $this->getTransition($dataSource, $urlConfigure);

        if (isset($dataSource['silhouette']) && $dataSource['silhouette'] instanceof PsaModelSilhouetteSite) {
            $dataSource['translate']['title'] = $dataSource['silhouette']->getCommercialLabel();
        }

        if ($isMobile) {
            if (isset($dataSource['typecash']) && $dataSource['typemonthly']) {
                $dataSource['switch'] = array(
                    array('title' => $this->trans('NDP_CASH_PRICE'), 'url' => '#', 'selected' => $dataSource['typecash']),
                    array('title' => $this->trans('NDP_MONTHLY_PRICE'), 'url' => '#', 'selected' => $dataSource['typemonthly'])
                );
            }
        }

        if (empty($dataSource['series'])) {
            $dataSource['errorTxt'] = $this->trans('NDP_PF53-58_ERROR_TEXT');
            $dataSource['ctaError'] = $this->trans('NDP_CTA_ERREUR_TITLE');
        }

        $this->priceManager->setTranslator($this->translator, $this->domain, $this->locale);
        $this->priceManager->setVersion($version);
        $this->priceManager->getSfg();
        $this->getMentions($dataSource);

        $result = array();

        $initialize = 'initialize';
        $pf53 = 'Pf53';
        $pf58 = 'Pf58';
        $pf53MethodName = $initialize.$pf53;
        $pf58MethodName = $initialize.$pf58;
        if ($isMobile) {
            $pf53MethodName = $initialize.'Mobile'.$pf53;
            $pf58MethodName = $initialize.'Mobile'.$pf58;
        }
        $this->addTranslation($dataSource);

        $view = '';
        switch($affichage) {
            case self::DISPLAY_FINITIONS:
                $result[] = $this->$pf53MethodName($dataSource);
                $view = 'pf53';
                break;
            case self::DISPLAY_MOTORISATIONS:
                $result[] = $this->$pf58MethodName($dataSource);
                $view = 'pf58';
                break;
            case self::DISPLAY_FINITIONS_MOTORISATIONS:
                $this->removeMentions($dataSource);

                $first = $comparatorTable;
                if ($comparatorTable === 0) {
                    $first = $block->getZoneAttribut3();
                }

                if ($first && $first === self::FIRST_MOTORISATIONS) {
                    $result[0] = $this->$pf58MethodName($dataSource);
                    $dataSource = $this->getMentions($dataSource);
                    $result[1] = $this->$pf53MethodName($dataSource);
                    $view = 'pf58';
                } else {
                    $result[0] = $this->$pf53MethodName($dataSource);
                    $this->getMentions($dataSource);
                    $result[1] = $this->$pf58MethodName($dataSource);
                    $view = 'pf53';
                }
                break;
        }

        $return = array(
            'views' => $result,

        );
        if($comparatorTable != 0) {
            $return['comparatorTable']= $this->getComparatorTable($dataSource, $view);
        }

        return $return;
    }

    /**
     * @param array $dataSource
     *
     * @return array
     */
    protected function getMentions(array &$dataSource)
    {
        $sfg = $this->priceManager->getSfg();
        // affiché si le WS répond
        if (!empty($sfg)) {
            $dataSource['legalMentions'] = $this->priceManager->getMentionLegalByMonth();//$dataSource['cheapest']['sfg']['generalLegalText'];
            $dataSource['diverseMentions'] = $this->priceManager->getFinancementDetailsTexts(PriceManager::FINANCEMENT_DETAILS_TEXT_LEGAL_TEXT);//$dataSource['cheapest']['sfg']['financementDetails']['TEXT_LEGAL-TEXT'];
        }

        $dataSource['furtherInfos'] = $this->trans('NDP_PF53_FURTHER_INFOS');
    }

    /**
     * @param array $dataSource
     *
     * @return array
     */
    protected function removeMentions(array &$dataSource)
    {
        // affiché si le WS répond
        if (isset($dataSource['legalMentions'])) {
            unset($dataSource['legalMentions']);
        }
        if (isset($dataSource['diverseMentions'])) {
            unset($dataSource['diverseMentions']);
        }
        unset($dataSource['furtherInfos']);
    }

    /**
     * @param array  $dataSource
     * @param string $view
     *
     * @return array
     */
    protected function getComparatorTable(array $dataSource, $view)
    {
        $return = array();
        if (isset($dataSource['urlJson'])) {
            $return['urlJson'] = $dataSource['urlJson'];
            $return['view'] = $view;
            $return['errorload'] = $this->trans('NDP_COMPARATOR_ERROR_LOAD');
            if (isset($dataSource['translate']['title'])) {
                $return['title'] = $this->trans('NDP_COMPARATOR_TABLE_TITLE').' '.$dataSource['translate']['title'];//Tableau comparatif
            }
            $return['translate'] = array('finitionSelect' => 'Sélectionnez un moteur');

            if ($dataSource['configuration'] instanceof PsaModelConfig) {
                /**
                 * @var PsaModelconfig $configuration
                 */
                $configuration = $dataSource['configuration'];
                $nav = array();

                if ($configuration->getShowComparisonChartButtonOpen()) {
                    $nav['deployAll'] = $this->trans('NDP_DEPLOY_ALL');
                }
                if ($configuration->getShowComparisonChartButtonClose()) {
                    $nav['closeAll'] = $this->trans('NDP_CLOSE_ALL');
                }
                if ($configuration->getShowComparisonChartButtonDiff()) {
                    $nav['showDiff'] = $this->trans('NDP_SHOW_DIFF');
                }
                if ($configuration->getShowComparisonChartButtonPrint()) {
                    $nav['print'] = array(
                        'text' => 'NDP_PRINT',
                        'href' => '#'
                    );
                }

                $return['nav'] = $nav;
            }
        }

        return $return;
    }

    /**
     * @param array  $dataSource
     * @param string $urlConfigure
     */
    protected function getTransition(&$dataSource, $urlConfigure)
    {
        $delay = 0;
        if (isset($dataSource['siteSettings']['DELAY_POPIN'])) {
            $delay = $dataSource['siteSettings']['DELAY_POPIN'];
        }

        $popinConfirmationAvailable = false;
        $popinConfirmationUnavailable = false;
        if (isset($dataSource['siteAndWebservices']) && $dataSource['siteAndWebservices'] instanceof PsaSitesEtWebservicesPsa) {
            if ($dataSource['siteAndWebservices']->getZoneVpPopinConfirm() === 1) {
                $popinConfirmationAvailable = true;
            } elseif ($dataSource['siteAndWebservices']->getZoneVpPopin() === 1) {
                $popinConfirmationUnavailable = true;
            }

            // Que si activer dans référentiel gestion de véhicule
            if (isset($dataSource['configuration']) && $dataSource['configuration'] instanceof PsaModelConfig && $dataSource['siteAndWebservices']->getZoneVp() === 1) {
                /**
                 * @var PsaModelConfig $configure
                 */
                $configure = $dataSource['configuration'];
                if ($configure->getCtaConfigureDisplay()) {
                    $dataSource['translate']['configure'] = array(
                        array(
                            'title' => $this->trans('NDP_CONFIGURE'),
                            'style' => 'cta',
                            'url' => $urlConfigure,
                            'version' => '4'
                        )
                    );
                }
            }
        }

        //Popin transition
        $dataSource['translate']['transition'] = array(
            // visuel du véhicule le moins cher du modèle regroupement de silhouette
            'visu' => array(
                'src' => self::VEHICLE_V3D_BASE_URL.$dataSource['cheapest']['version']->IdVersion->id.'&view=001',
                'alt' => $dataSource['cheapest']['version']->IdVersion->label,
                'width' => '',
                'height' => ''
            ),
            'v1' => array(
                'active' => $popinConfirmationAvailable,
                'text1' => $this->trans('NDP_VEHICLE_CONFIGURATION_START'),//vous commencez la configuration de votre véhicule
            ),
            'v2' => array(
                'active' => $popinConfirmationUnavailable,
                'text1' => $this->trans('NDP_VEHICLE_CONFIGURATION_REDIRECT'), //Vous allez être redirigé vers la configuration de votre véhicule dans
                'text2' => $this->trans('NDP_NOT_REDIRECT'), //Si vous n\'êtes pas redirigé veuillez cliquer ici
                'second' => $this->trans('NDP_SECOND'),//seconde
                'seconds' => $this->trans('NDP_SECONDS'),//secondes
                'counter' => $delay,// récupérer de PsaSiteParameter
                'link' => array(
                    'label' => $this->trans('NDP_VEHICLE_CONFIGURATION_ACCESS'),//Accès à la configuration de votre véhicule
                    'target' => '_self',
                    'url' => $urlConfigure, // 'http://configurer.peugeot.fr/configurer/'.$this->version->Model->label.'/'.$this->version->GrbodyStyle->label; voir ISOBAR
                    'version' => 2,
                    'style' => 'STYLE_SIMPLELINK',
                )
            )
        );
    }

    /**
     * @param array $dataSource
     */
    protected function addTranslation(array &$dataSource)
    {
        //Gestion des traductions
        $dataSource['trans'] = array(
            'zoom' => $this->trans('NDP_ZOOM'),
            'addToComparator' => $this->trans('NDP_ADD_TO_COMPARATOR'),
            'addOtherToComparator' => $this->trans('NDP_OTHER_TO_COMPARATOR'),
            'allSelected' => $this->trans('NDP_ALL_SELECTED'),
            'more' => $this->trans('NDP_MORE_DETAILS'),
            'NDP_DISCOVER_ONE' => $this->trans('NDP_DISCOVER_ONE'),
            'NDP_ONE_FINISH' => $this->trans('NDP_ONE_FINISH'),
            'NDP_DISCOVER_SEVERAL' => $this->trans('NDP_DISCOVER_SEVERAL'),
            'NDP_DISCOVER_SEVERAL_FINISH' => $this->trans('NDP_DISCOVER_SEVERAL_FINISH'),
            'NDP_SEVERAL_FINISHES' => $this->trans('NDP_SEVERAL_FINISHES'),
            'NDP_ONE_MOTOR' => $this->trans('NDP_ONE_MOTOR'),
            'NDP_DISCOVER_ONE_FINISH' => $this->trans('NDP_DISCOVER_ONE_FINISH'),
            'NDP_SEVERAL_MOTORS' => $this->trans('NDP_SEVERAL_MOTORS'),

        );


        $dataSource['translate']['from'] = $this->trans('NDP_FROM'); // A partie de
        $dataSource['translate']['configurer'] = $this->trans('NDP_CONFIGURE'); // Configurer
        $dataSource['translate']['include'] = $this->trans('NDP_INCLUDE'); // inclus
        $dataSource['translate']['priceAndEngineAvailable'] = $this->trans('NDP_PRICE_AND_ENGINE_AVAILABLE'); // Prix & motorisations disponibles
        $dataSource['translate']['equipments'] = $this->trans('NDP_EQUIPMENTS'); // Equipements
        $dataSource['translate']['engine'] = $this->trans('NDP_ENGINE'); // Motorisation
        $dataSource['translate']['totalCO2'] = $this->trans('NDP_TOTAL_CO2'); // Total émission de CO2 (g/km)
        $dataSource['translate']['energyClass'] = $this->trans('NDP_ENERGY_CLASS'); // Classe energetique
        $dataSource['translate']['consumption'] = $this->trans('NDP_CONSUMPTION'); // Consommation (l/100km)
        $dataSource['translate']['power'] = $this->trans('NDP_POWER'); // Puissance (kw)
        $dataSource['translate']['finish'] = $this->trans('NDP_FINISH'); // Finition
        $dataSource['translate']['finitionsAvailable'] = $this->trans('NDP_FINITION_AVAILABLE'); // finitions disponible
        $dataSource['translate']['NDP_PRICE'] = $this->trans('NDP_PRICE');
        $dataSource['translate']['NDP_CASH'] = $this->trans('NDP_CASH');
        $dataSource['translate']['NDP_MONTHLY'] = $this->trans('NDP_MONTHLY');
        $dataSource['translate']['NDP_GEAR'] = $this->trans('NDP_GEAR'); //boite de vitesse
        $dataSource['translate']['NDP_ENERGY'] = $this->trans('NDP_ENERGY'); //energie
        $dataSource['translate']['equipmentsAdditional'] = $this->trans('NDP_ADDITIONAL_EQUIPMENTS'); // EQUIPEMENTS COMPLÉMENTAIRES
        $dataSource['translate']['furtherInfos'] = $this->trans('NDP_PF53_FURTHER_INFOS');
        $dataSource['translate']['NDP_BACK_TO_SHOWROOM'] = $this->trans('NDP_BACK_TO_SHOWROOM');
        $dataSource['translate']['NDP_CONTINUE'] = $this->trans('NDP_CONTINUE');



    }

    /**
     * @param array $siteSettings
     * @param int   $siteId
     *
     * @return array
     */
    private function getTypeCash(array $siteSettings, $siteId)
    {
        $cashType = array('typecash' => true);

        if ($this->priceManager->getSfgStatus($siteId)) {
            if ($siteSettings['VEHICULE_PRICE_MONTHLY_DISPLAY'] === '1') {
                $cashType = array('typemonthly' => false, 'typecash' => true);

                if (self::MONTHLY === $siteSettings['VEHICULE_PRICE_TYPE_PAYMENT_DEFAULT']) {
                    $cashType = array('typemonthly' => true, 'typecash' => false);
                }
                /**
                 * @todo : surcharger préférence utilisateur en cookie
                 */
            }
        }

        return $cashType;
    }

    /**
     * @param array $dataSource
     *
     * @return array
     */
    public function initializePf58(array $dataSource)
    {
        $this->pf58Motorisations->setTranslator($this->translator, $this->domain, $this->locale);
        $pf58 = $this->pf58Motorisations->setDataFromArray($dataSource);
        $pf58->initSeries();
        $slicePf58 = array(
            'slice' => $pf58,
            'templatename' => './pf58.html.smarty',
        );

        return $slicePf58;
    }

    /**
     * @param array $dataSource
     *
     * @return array
     */
    public function initializePf53(array $dataSource)
    {

        $this->pf53Finitions->setTranslator($this->translator, $this->domain, $this->locale);
        $pf53 = $this->pf53Finitions->setDataFromArray($dataSource);
        $pf53->initSeries();

        $slicePf53 = array(
            'slice' => $pf53,
            'templatename' => './pf53.html.smarty',
        );

        return $slicePf53;
    }

    /**
     * @param array $dataSource
     *
     * @return array
     */
    public function initializeMobilePf53(array $dataSource)
    {
        $this->pf53FinitionMobile->setTranslator($this->translator, $this->domain, $this->locale);
        $this->pf53FinitionMobile->setDataFromArray($dataSource);
        $this->pf53FinitionMobile->initSeries();

        $slicePf53 = array(
            'slice' => $this->pf53FinitionMobile,
            'templatename' => './pf53.html.smarty',
        );

        return $slicePf53;
    }

    /**
     * @param array $dataSource
     *
     * @return array
     */
    public function initializeMobilePf58(array $dataSource)
    {
        $this->pf58MotorisationMobile->setTranslator($this->translator, $this->domain, $this->locale);
        $this->pf58MotorisationMobile->setDataFromArray($dataSource);
        $this->pf58MotorisationMobile->initSeries($dataSource['switch']);
        $slicePf58 = array(
            'slice' => $this->pf58MotorisationMobile,
            'templatename' => './pf58.html.smarty',
        );

        return $slicePf58;
    }

}
