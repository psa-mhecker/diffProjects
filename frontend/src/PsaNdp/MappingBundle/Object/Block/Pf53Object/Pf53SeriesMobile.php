<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf53Object;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Entity\PsaFinishingColor;
use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Object\Block\Pf5358FinitionsMotorisations;
use PsaNdp\MappingBundle\Object\Block\Pf58Object\Pf58Listing;
use PsaNdp\MappingBundle\Object\Block\Pf58Object\Pf58MobileDetails;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Media;
use PsaNdp\MappingBundle\Object\Popin\PopinFinancement;

/**
 * Class Pf53SeriesMobile
 */
class Pf53SeriesMobile extends Pf5358FinitionsMotorisations
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var Pf58Listing
     */
    protected $cash;

    /**
     * @var Pf58Listing
     */
    protected $monthly;

    /**
     * @var Collection
     */
    protected $gallerie;

    /**
     * @var array
     */
    protected $listPopin;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gallerie = new ArrayCollection();
    }

    /**
     * @return Pf58Listing
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * @param Pf58Listing $cash
     *
     * @return $this
     */
    public function setCash(Pf58Listing $cash)
    {
        $this->cash = $cash;

        return $this;
    }

    /**
     * @return Pf58Listing
     */
    public function getMonthly()
    {
        return $this->monthly;
    }

    /**
     * @param Pf58Listing $monthly
     *
     * @return $this
     */
    public function setMonthly(Pf58Listing $monthly)
    {
        $this->monthly = $monthly;
    }

    /**
     * @return Collection
     */
    public function getGallerie()
    {
        return $this->gallerie;
    }

    /**
     * @param Collection $gallerie
     */
    public function setGallerie($gallerie)
    {
        $this->gallerie = $gallerie;
    }

    /**
     * @param Media $gallerie
     */
    public function addGallerie(Media $gallerie)
    {
        $this->gallerie->add($gallerie);
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return array
     */
    public function getListPopin()
    {
        return $this->listPopin;
    }

    /**
     * @param array $listPopin
     *
     * @return $this
     */
    public function setListPopin($listPopin)
    {
        $this->listPopin = $listPopin;

        return $this;
    }

    /**
     * Initialize series
     */
    public function initSerie()
    {
        $this->setId($this->version->IdVersion->id);
        $this->setTitle($this->version->GrCommercialName->label);
        $this->setText('text ohohoho');

        $src = self::VEHICLE_V3D_BASE_URL.$this->lcdv16.'&width=210&height=97&ratio=1&format=jpg&quality=100&view='.$this->angleView;
        $alt = 'Peugeot'.$this->version->IdVersion->label;
        $text = 'avec le moteur '.$this->version->GrEngine->label.' '.$this->version->GrTransmissionType->label;
        $sticker = array('text' => $this->finishing->getFinition(), 'bgColor' => '');

        if ($this->finishing->getColor() instanceof PsaFinishingColor) {
            $sticker['bgColor'] = $this->finishing->getColor()->getColorCode();
        }

        $this->priceManager->setTranslator($this->translator, $this->domain, $this->locale);
        $this->priceManager->setVersion($this->version);
        $this->sfg = $this->priceManager->getSfg();

        $price = $this->priceManager->getCashPrice();

        $tableCash = array(
            'src' => $src,
            'alt' => $alt,
            'text' => $text,
            'label' => '',
            'price' => $price,
            'url' => '#',
            'mode' => 'cash',
            'sticker' => $sticker
        );

        $cash = new Pf58Listing();
        $cash->setDataFromArray($tableCash);
        $this->setCash($cash);

        $tableMonthly = array(
            'src' => $src,
            'alt' => $alt,
            'text' => $text,
            'label' => '',
            'price' => $this->priceManager->getPriceByMonth(),
            'by' => '',
            'mention' => $this->priceManager->getFirstAccountValue(),
            'link' => '#',
            'mode' => 'monthly',
            'sticker' => $sticker
        );

        $monthly = new Pf58Listing();
        $monthly->setDataFromArray($tableMonthly);
        $this->setMonthly($monthly);

        $this->mediaFactory = new MediaFactory();
        $this->addGallerie($this->mediaFactory->createFromArray(array('src' => $src, 'alt' => 'alt')));
        $this->addGallerie($this->mediaFactory->createFromArray(array('src' => $src, 'alt' => 'alt')));
        $this->addGallerie($this->mediaFactory->createFromArray(array('src' => $src, 'alt' => 'alt')));

        $this->setListPopin($this->initPopin());
    }

    /**
     * @return array
     */
    public function initPopin()
    {
        $financement = new PopinFinancement();

        $title = $this->version->Model->label.' '.$this->version->GrbodyStyle->label;

        $finance = array(
            'model' => $this->getSilhouetteTitle(),
            'sfg' => $this->sfg,
            'symbol' => $this->siteSettings['VEHICULE_PRICE_LEGAL_SYMBOL'],
            'img' => array(
                'src' => self::VEHICLE_V3D_BASE_URL.$this->lcdv16.'&width=210&height=97&ratio=1&format=jpg&quality=100&view='.$this->angleView,
                'alt' => 'Peugeot '.$title),
            'id' => $this->id,
            'title' => $title,
        );

        $financement->setDataFromArray($finance);

        $details = new Pf58MobileDetails();

        $lcdv4 = substr($this->lcdv16, 0, 4);
        $lcdv6 = substr($this->lcdv16, 4, 2);

        $detail = array(
            'id' => $this->id,
            'title' => $this->getSilhouetteTitle(),
            'subtitle' => $this->translate['engine'].' '.$this->version->GrEngine->label.' '.$this->version->GrTransmissionType->label,
            'img' => array(
                'src' => self::MOTOR_V3D_BASE_URL.$lcdv4.'/'.$lcdv6.'/OverlayNew/ov_'.$this->version->Engine->id.'.png',
                'alt' => 'Motor '.$this->version->GrEngine->label.' '.$this->version->GrTransmissionType->label
            ),
            'price' => array(),
            'credit' => $this->priceManager->getMentionLegalByMonth(),
            'mention' => $this->priceManager->getFinancementDetailsTexts(PriceManager::FINANCEMENT_DETAILS_TEXT_LEGAL_TEXT),
            'text' => '',
            'info' => $this->getMotorInfos($this->version),
        );

        $details->setDataFromArray($detail);

        return array('financement' => array($financement), 'details' => $details);
    }
}
