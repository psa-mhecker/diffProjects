<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf58Object;

use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Object\Block\Pf5358FinitionsMotorisations;

/**
 * Class Pf58MotorisationMobile
 */
class Pf58MotorisationMobile extends Pf5358FinitionsMotorisations
{
    protected $overrideMapping = array(
        'speedmodechoice' => 'speedModeChoice'
    );

    /**
     * @var Pf58ListingMobile
     */
    protected $series;

    /**
     * @var array
     */
    protected $speedModeChoice;

    /**
     * @param PriceManager $priceManager
     */
    public function __construct(PriceManager $priceManager)
    {
        $this->priceManager = $priceManager;
    }

    /**
     * @return array
     */
    public function getSpeedModeChoice()
    {
        return $this->speedModeChoice;
    }

    /**
     * @param array $speedModeChoice
     */
    public function setSpeedModeChoice($speedModeChoice)
    {
        $this->speedModeChoice = $speedModeChoice;
    }

    /**
     * @return Pf58ListingMobile
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * @param array $series
     */
    public function setSeries(array $series)
    {
        $this->series = $this->groupByEnergyAndMotorisation($series);
    }

    /**
     * @param array $series
     *
     * @return array
     */
    public function groupByEnergyAndMotorisation(array $series)
    {
        $energies = array();

        // Regroupe par energy
        foreach ($series as $serie) {
            $key = $serie['version']->Energy->label;
            $energies[$key][] = $serie;
        }

        $return = array();
        foreach ($energies as $key => $ernergy) {
            $return[$key] = $this->groupByMotorisation($ernergy);
        }

        return $return;
    }

    /**
     * @param $switch
     */
    public function initSeries($switch)
    {
        $pf58Listing = new Pf58ListingMobile();
        $listing = array(
            'switch' => $switch,
            'series' => $this->series,
            'translate' => $this->translate
        );

        $pf58Listing->setTranslator($this->translator, $this->domain, $this->locale);
        $pf58Listing->setSiteSettings($this->siteSettings);
        $pf58Listing->setPriceManager($this->priceManager);

        $pf58Listing->setDataFromArray($listing);
        $pf58Listing->initPrice();

        $this->speedModeChoice = array(
            'title' => $this->trans('NDP_CHOOSE_TRANSMISION_TYPE'),
            'ctaList' => array(
                array(
                    'type' => 'list',
                    'title' => $this->trans('NDP_CHOICE_TRANSMISSION_TYPE'),
                    'listItems' => array(
                        array(
                            'txt' => $this->trans('NDP_CHOICE_TRANSMISSION_TYPE'),
                            'url' => ''
                        ),
                        array(
                            'txt' => $this->trans('NDP_NO_PREFERENCE'),
                            'url' => '0'
                        ),
                        array(
                            'txt' => $this->trans('NDP_MANUAL_TRANSMISSION'),
                            'url' => '1'
                        ),
                        array(
                            'txt' => $this->trans('NDP_AUTOMATED_TRANSMISSION'),
                            'url' => '2'
                        )
                    )
                )
            )
        );

        $this->series = $pf58Listing;
    }
}
