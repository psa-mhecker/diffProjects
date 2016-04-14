<?php

namespace PsaNdp\MappingBundle\Object\Popin;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelConfig;
use PsaNdp\MappingBundle\Object\Block\Pf5358FinitionsMotorisations;

/**
 * Class Popin
 */
class Popin extends Pf5358FinitionsMotorisations
{
    protected $mapping = array();

    /**
     * @var PopinNew $new
     */
    protected $new;

    /**
     * @var Collection PopinMotor
     */
    protected $motor;

    /**
     * @var PopinEquipments $equipments
     */
    protected $equipments;

    /**
     * @var PopinFinancement $financement
     */
    protected $financement;

    /**
     * @var PopinFinition $finition
     */
    protected $finition;

    /**
     * @var Collection $financements
     */
    protected $financements;

    /**
     * @var array $version
     */
    protected $version;

    /**
     * @var string
     */
    protected $lcdv16;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->new = new PopinNew();
        $this->equipments = new PopinEquipments();
        $this->financement = new PopinFinancement();
        $this->finition = new PopinFinition();
        $this->motor = new ArrayCollection();
        $this->financements = new ArrayCollection();
    }

    /**
     * @return PopinFinancement
     */
    public function getFinancement()
    {
        return $this->financement;
    }

    /**
     * @param array $financement
     *
     * @return $this
     */
    public function setFinancement(array $financement)
    {
        $this->financement->setDataFromArray($financement);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getMotor()
    {
        return $this->motor;
    }

    /**
     * @param Collection $motors
     *
     * @return $this
     */
    public function setMotor(Collection $motors)
    {
        foreach ($motors as $motor) {
            $popinMotor = new PopinMotor();
            $popinMotor->setDataFromArray($motor);
            $this->addMotor($popinMotor);
        }

        return $this;
    }

    /**
     * @param PopinMotor $motor
     */
    public function addMotor(PopinMotor $motor)
    {
        $this->motor->add($motor);
    }

    /**
     * @return PopinNew
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * @param array $new
     *
     * @return $this
     */
    public function setNew(array $new)
    {
        return $this;
    }

    /**
     * @return PopinEquipments
     */
    public function getEquipments()
    {
        return $this->equipments;
    }

    /**
     * @param array $equipments
     *
     * @return $this
     */
    public function setEquipments(array $equipments)
    {
        $this->equipments->setDataFromArray($equipments);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getFinancements()
    {
        return $this->financements;
    }

    /**
     * @param Collection $financements
     *
     * @return $this
     */
    public function setFinancements(Collection $financements)
    {
        $this->financements = $financements;

        return $this;
    }

    /**
     * @param PopinFinancement $financement
     */
    public function addFinancement(PopinFinancement $financement)
    {
        $this->financements->add($financement);
    }

    /**
     * @return PopinFinition
     */
    public function getFinition()
    {
        return $this->finition;
    }

    /**
     * @param PopinFinition $finition
     *
     * @return $this
     */
    public function setFinition($finition)
    {
        $this->finition = $finition;

        return $this;
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
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
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
     *
     * @return $this
     */
    public function setLcdv16($lcdv16)
    {
        $this->lcdv16 = $lcdv16;

        return $this;
    }

    /**
     * init Popin New
     */
    public function initPopinNew()
    {
        $new = array(
            'title' => $this->getSilhouetteTitle(),
            'subtitle' => $this->translate['finish'].' '.$this->version->GrCommercialName->label, // Finition
            'visuels' => array('src' => self::VEHICLE_V3D_BASE_URL.$this->lcdv16.'&width=882&height=480&ratio=1&format=jpg&quality=100&view='.$this->angleView, 'alt' => $this->lcdv16)
        );

        if (!empty($this->segmentTitle)) {
            $new['subtitle'] = $this->segmentTitle.' '.$this->version->GrCommercialName->label;
        }

        $this->new->setDataFromArray($new);
    }

    /**
     * init Popin Equipments
     *
     * @param array $features
     */
    public function initPopinEquipments(array $features)
    {
        $equipments = array(
            'title' => $this->getSilhouetteTitle(),
        );
        $slides = array();

        foreach ($features as $feature) {
            if (isset($feature['info'])) {
                $slide = array(
                    'subtitle' => $feature['subtitle'],
                    'src' => $feature['src'],
                    'alt' => $feature['text'],
                    'srcDefault' => $feature['srcDefault'],
                );
                // description couper en deux si plus de 150 caractère
                if (isset($feature['description'])) {
                    if (strlen($feature['description']) > 150) {
                        $slide['textLeft'] = substr($feature['description'], 0, 150);
                        $slide['textRight'] = substr($feature['description'], 150, strlen($feature['description']));
                    } else {
                        $slide['textLeft'] = $feature['description'];
                        $slide['textRight'] = '';
                    }
                }
                $slides[] = $slide;
            }
        }

        $equipments['slides'] = $slides;

        $this->equipments->setDataFromArray($equipments);
    }

    /**
     * init Popin Motor
     *
     * @param array $motorisation
     * @param array $version
     * @param array $sfg
     */
    public function initPopinMotor($motorisation, $version, $sfg)
    {
        $this->priceManager->setVersion($version);

        $description = '';
        if (isset($version->GrTransmissionType->description)) {
            $description = $version->GrTransmissionType->description;
        }

        $motor = array(
            'title' => $this->getSilhouetteTitle(),
            'subtitle' => $this->translate['engine'].' '.$version->GrEngine->label.' '.$version->GrTransmissionType->label, // Motorisation
            'visu' => array('src' => $motorisation['src'], 'alt' => $version->Model->label),
            'id' => $version->GrEngine->id,
            'text' => $description,
            'price' => array(
                'sum' => $this->priceManager->getCashPrice(),
                'devise' => '',
                'indice' => '',
                'taxe' => ''
            ),
        );

        if (isset($this->siteSettings['VEHICULE_PRICE_DISPLAY']) && $this->siteSettings['VEHICULE_PRICE_DISPLAY'] && !empty($sfg)) {
            $this->priceManager->setSfg($sfg);
            $motor['pricebyMonth'] = array(
                'libelle' => array(
                    'text' => '',
                    'position' => false // position paramètre généraux du site,
                ),
                'price' => array(
                    'sum' => $this->priceManager->getPriceByMonth(),
                    'rent' => $this->getPriceManager()->getFirstAccountValue(),
                    'devise' => '',
                    'indice' => '',
                    'taxe' => '',
                ),
            );
            $motor['mention'] = $this->priceManager->getLegalNoticeByMonth();
        }

        if (isset($version->GrEngine->description)) {
            $motor['text'] = $version->GrEngine->description;
        }
        if (isset($this->translate['from'])) {
            // BO avant ou après
            $motor['libelle'] = '';
            $motor['libelleAfter'] = '';
        }

       $motor['info'] = $this->getMotorInfos($version);

        if ($this->configuration instanceof PsaModelConfig && $this->configuration->getShowCharacteristic()) {
            // si active en BO
            $motor['infosup'] = $this->getMotorInfosSup($version, true);
        }

        if (isset($version->EcoLabel) && !empty($version->EcoLabel)) {
            $motor['sticker'] = array('src' => self::ECOLABEL_V3D_BASE_URL.$version->EcoLabel.'_logo.png', 'alt' => 'EcoLabel'); // EcoLabel
        }

        $popinMotor = new PopinMotor();
        $popinMotor->setDataFromArray($motor);
        $this->motor->add($popinMotor);
    }

    /**
     * Init popin financement
     */
    public function initPopinfinancement()
    {

        $financement = array(
            'model' => $this->getSilhouetteTitle(),
            'sfg' => $this->sfg,
            'symbol' => $this->siteSettings['VEHICULE_PRICE_LEGAL_SYMBOL'],
        );

        $this->financement->setDataFromArray($financement);
    }
}
