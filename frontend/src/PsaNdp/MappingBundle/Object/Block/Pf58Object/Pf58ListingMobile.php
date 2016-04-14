<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf58Object;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Block\Pf5358FinitionsMotorisations;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Popin\PopinFinancement;

/**
 * Class Pf58ListingMobile
 */
class Pf58ListingMobile extends Pf5358FinitionsMotorisations
{
    protected $mapping = array(
        'pricebyMonth' => 'toggleMonth',
        'price' => 'toggleCash',
    );

    /**
     * @var Collection $switch
     */
    protected $switch;

    /**
     * @var Collection $toggleMonth
     */
    protected $toggleMonth;

    /**
     * @var Collection $toggleCash
     */
    protected $toggleCash;

    /**
     * @var int
     */
    protected $nbMotor;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->toggleCash = new ArrayCollection();
        $this->toggleMonth = new ArrayCollection();
    }

    public function getTitle()
    {
        $title = $this->trans('NDP_DISCOVER_ONE_FINISH');
        if ($this->nbMotor > 1) {
            $title = $this->trans('NDP_DISCOVER_SEVERAL_MOTOR', array('%nbMotor%' => $this->nbMotor));//Découvrez les <span></span> moteurs disponibles
        }

        return $title;
    }

    /**
     * @return array
     */
    public function getSwitch()
    {
        return $this->switch;
    }

    /**
     * @param array $switch
     *
     * @return $this
     */
    public function setSwitch(array $switch)
    {
        $this->switch = $switch;

        return $this;
    }

    /**
     * @param Cta $cta
     */
    public function addSwitch(Cta $cta)
    {
        $this->switch->add($cta);
    }

    /**
     * @return Collection
     */
    public function getToggleCash()
    {
        return $this->toggleCash;
    }

    /**
     * @param array $toggleCash
     *
     * @return $this
     */
    public function setToggleCash(array $toggleCash)
    {
        foreach ($toggleCash as $toggle) {
            $pf58Toggle = new Pf58Toggle();
            $pf58Toggle->setDataFromArray($toggle);
            $this->addToggleCash($pf58Toggle);
        }

        return $this;
    }

    /**
     * @param Pf58Toggle $toggle
     */
    public function addToggleCash(Pf58Toggle $toggle)
    {
        $this->toggleCash->add($toggle);
    }

    /**
     * @return Collection
     */
    public function getToggleMonth()
    {
        return $this->toggleMonth;
    }

    /**
     * @param array $toggleMonth
     *
     * @return $this
     */
    public function setToggleMonth(array $toggleMonth)
    {
        foreach ($toggleMonth as $toggle) {
            $pf58Toggle = new Pf58Toggle();
            $pf58Toggle->setDataFromArray($toggle);
            $this->addToggleMonth($pf58Toggle);
        }

        return $this;
    }

    /**
     * @param Pf58Toggle $toggle
     */
    public function addToggleMonth(Pf58Toggle $toggle)
    {
        $this->toggleMonth->add($toggle);
    }

    /**
     * @param array $serie
     * @param $lcdv16
     *
     * @return array
     */
    public function initPriceByMonth(array $serie, $lcdv16)
    {
        $price = array(
            'libelle' => array(
                'text' => '',
                'position' => false
            ),
            'price' => array(
                'sum' => $this->priceManager->getPriceByMonth(),
                'devise' => '',
                'indice' => '',
                'taxe' => '',
                'mention' => ''
            ),
            'popin' => $this->initPopinFinancement($serie, $lcdv16)
        );

        return $price;
    }

    /**
     * @param bool  $byMonth
     *
     * @return array
     */
    public function initPrice($byMonth = false)
    {
        $price = array();

        foreach ($this->series as $key => $energy) {
            $toggle = array(
                'title' => $key,
                'class' => strtolower($key),
                'toggleCont' => array(),
            );

            foreach ($energy as $motors) {
                foreach ($motors as $serie) {
                    if (!$byMonth) {
                        $this->nbMotor = $this->nbMotor + 1;
                    }

                    $toggle['toggleCont'][] = $this->initMotor($serie, $byMonth);
                }
            }

            $price[] = $toggle;
        }

        if ($byMonth) {
            $this->setToggleMonth($price);
        } else {
            $this->setToggleCash($price);
            // Si mensuel activer et si sfg disponible
            if (isset($this->siteSettings['VEHICULE_PRICE_DISPLAY']) && $this->siteSettings['VEHICULE_PRICE_DISPLAY']) {
                $this->initPrice(true);
            }
        }
    }

    /**
     * @param array  $serie
     * @param string $lcdv16
     *
     * @return PopinFinancement
     */
    public function initPopinFinancement(array $serie, $lcdv16)
    {
        $title = $serie['version']->Model->label.' '.$serie['version']->GrbodyStyle->label;
        $silhouetteTitle = $title;
        if ($this->silhouette) {
            $silhouetteTitle = $this->silhouette->getCommercialLabel();
        }
        $financement = new PopinFinancement();
        $finance = array(
            'model' => $silhouetteTitle,
            'symbol' => $this->siteSettings['VEHICULE_PRICE_LEGAL_SYMBOL'],
            'img' => array(
                'src' => self::VEHICLE_V3D_BASE_URL.$lcdv16.'&width=210&height=97&ratio=1&format=jpg&quality=100&view='.$this->angleView,
                'alt' => 'Peugeot '.$title),
            'id' => $serie['version']->IdVersion->id,
            'title' => $title,
        );

        $sfg = $this->priceManager->getSfg();
        if (!empty($sfg)) {
            $finance['sfg'] = $sfg;
        }

        $financement->setDataFromArray($finance);

        return $financement;
    }

    /**
     * @param array $serie
     * @param bool  $byMonth
     *
     * @return array
     */
    public function initMotor(array $serie, $byMonth)
    {
        $lcdv16 = $serie['version']->IdVersion->id;
        $lcdv4 = substr($lcdv16, 0, 4);
        $lcdv6 = substr($lcdv16, 4, 2);

        $motor = array(
            'id' => $serie['id'],
            'title' => $serie['version']->GrEngine->label.' '.$serie['version']->GrTransmissionType->label,
            'typespeedmode' => 1, // Type de boite de vitesse en attente de mis à jour du WS
            'img' => array(
                'src' => self::MOTOR_V3D_BASE_URL.$lcdv4.'/'.$lcdv6.'/OverlayNew/ov_'.$serie['version']->Engine->id.'.png',
                'alt' => 'Motor '.$serie['version']->Engine->label
            ),
            'text' => array(
                'label1' => $serie['version']->Energy->label,
                'label2' => 'Manuelle', // En attente d'intégration du type de boite de vitesse dans le WS
            ),
            'info' => true,
            'link' => array(
                'style' => 'cta',
                'url' => '#', // Url pour ApiBundle
                'text' => $this->translate['configurer'],
                'target' => '_blank'
            ),
        );

        $this->priceManager->setTranslator($this->translator, $this->domain, $this->locale);
        $this->priceManager->setVersion($serie['version']);
        $sfg = $this->priceManager->getSfg();

        if ($byMonth) {
            if (!empty($sfg)) {
                $motor = array_merge($motor, $this->initPriceByMonth($serie, $lcdv16));
            }
        } else {
            $motor['libelle'] = array(
                'text' => '',
                'position' => false
            );
            $motor['price'] = array(
                'sum' => $this->priceManager->getCashPrice(),
                'devise' => '',
                'indice' => '',
                'taxe' => '',
            );
        }

        return $motor;
    }
}
