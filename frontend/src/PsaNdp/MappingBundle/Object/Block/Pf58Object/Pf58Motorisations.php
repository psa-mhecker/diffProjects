<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf58Object;


use PSA\MigrationBundle\Repository\PsaCtaRepository;
use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Object\Block\Pf5358FinitionsMotorisations;
use PsaNdp\MappingBundle\Object\Filters\Filters;

/**
 * Class Pf58Motorisations
 */
class Pf58Motorisations extends Pf5358FinitionsMotorisations
{
    /**
     * @param PriceManager $priceManager
     * @param PsaCtaRepository $ctaRepository
     */
    public function __construct(PriceManager $priceManager, PsaCtaRepository $ctaRepository)
    {
        $this->priceManager = $priceManager;
        $this->ctaRepository = $ctaRepository;
    }

    /**
     * @return array
     */
    public function getSeries()
    {
        return $this->series;
    }
    /**
     * @param array $series
     *
     * @return $this
     */
    public function setSeries(array $series)
    {
        $this->series = $this->groupByMotorisation($series);

        return $this;
    }

    /**
     * @return array|Filters
     */
    public function getFilters()
    {
        //Gestion des filtres
        $filters = [];

        if($this->isMensualPriceActive()) {
            $filters['price'] = $this->getPriceFilters();
        }
        $filters['energy'] = $this->getEnergyFilters();
        $filters['gear'] = $this->getGearFilters();

        return $filters;
    }


    /**
     * @return array
     */
    protected function getEnergyFilters()
    {
        $energies = array();
        foreach( $this->series as $serie )
        {
            $energy = $serie['version']->Energy->label;
            $energies[$energy] = ['disabled'=> false, 'label'=>$energy ];
        }

        /**
         * Affiché si une des versions a chacune des énergies
         */
        return array(
            'title' => $this->translate['NDP_ENERGY'],
            'label' => array_values($energies),
        );
    }

    /**
     * @return array
     */
    protected  function getGearFilters(){

        $gears = array();
        $exist = array();
        $i = 1;
        foreach($this->series as $serie )
        {
            $gear = $serie['version']->GrTransmissionType->label;
            if(!in_array($gear,$exist)) {
                $exist[] = $gear;
                $gears['checbox'.$i++] = ['label' => $gear, 'id' => $serie['version']->GrTransmissionType->id];
            }
        }
        /** @todo find right gear label */
        return array(
            'title' => $this->translate['NDP_GEAR'],
            'label2' => 'manuelle',
            'checkbox1' => array(
                'label' => 'automatique',
                'id' => 'automatic'
            ),
            'checkbox2' => array(
                'label' => 'manuelle',
                'id' => 'manual'
            )
        );
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        // Gestion du titre
        $nbMotor = $this->countMotor();
        $title = $this->trans('NDP_DISCOVER_ONE_FINISH');
        if ($nbMotor > 1) {
            $title = $this->trans('NDP_DISCOVER_SEVERAL_MOTOR', array('%nbMotor%' => $nbMotor));//Découvrez les <span></span> moteurs disponibles
        }

        return $title;
    }

    /**
     *
     * @return int
     */
    protected function countMotor()
    {
        return count($this->series);
    }

    /**
     * Initialize series
     */
    public function initSeries()
    {
        $return = array();
        $count = 0;

        foreach ($this->series as $segments) {
            foreach ($segments as $serie) {
                $serie = array_merge($serie,$this->trans);
                $pf58Series = new Pf58Series();

                if (!empty($serie['finishingReference'])) {
                    $pf58Series->setFinishingReference($serie['finishingReference']);
                }

                $pf58Series->setPriceManager($this->priceManager);
                $pf58Series->setTranslate($this->translate);
                $pf58Series->setTrans($this->trans);
                $pf58Series->setSiteAndWebservices($this->siteAndWebservices);
                $pf58Series->setAngleView($this->angleView);
                $pf58Series->setTranslate($this->translate);
                $pf58Series->setTranslator($this->translator, $this->domain, $this->locale);
                $pf58Series->setSiteSettings($this->siteSettings);
                $pf58Series->setCompareGrade($this->compareGrade);
                $pf58Series->setConfigurationConfig($this->configurationConfig);
                $pf58Series->setCountryCode($this->countryCode);
                $pf58Series->setLanguageCode($this->languageCode);
                if (isset($serie['urlVp'])) {
                    $pf58Series->setUrlVp($serie['urlVp']);
                }
                $pf58Series->setConfigurationSelect($this->configurationSelect);
                $pf58Series->setSilhouette($this->silhouette);
                $pf58Series->setConfiguration($this->configuration);
                $pf58Series->setDataFromArray($serie);
                $pf58Series->initPopin();
                $pf58Series->initDetails();
                $return[] = $pf58Series;
                $count++;
            }
        }

        $this->series = $return;
    }
}
