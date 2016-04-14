<?php

namespace PsaNdp\MappingBundle\Object\Popin;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Block\Pf58Object\Pf58Information;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Media;
use PsaNdp\MappingBundle\Object\Price;
use PsaNdp\MappingBundle\Object\PriceByMonth;


/**
 * Class PopinMotor
 */
class PopinMotor extends PopinNew
{
    protected $mapping = array(
        'libelle' => 'label',
        'libelleAfter' => 'labelAfter',
        'infosup' => 'infoSup',
        'pricebyMonth' => 'priceByMonth',
        'visu' => 'media',
    );

    /**
     * @var string $text
     */
    protected $text;

    /**
     * @var string $label
     */
    protected $label;

    /**
     * @var string $labelAfter
     */
    protected $labelAfter;

    /**
     * @var Price $price
     */
    protected $price;

    /**
     * @var PriceByMonth $priceByMonth
     */
    protected $priceByMonth;

    /**
     * @var Media $sticker
     */
    protected $sticker;

    /**
     * @var Collection $info
     */
    protected $info;

    /**
     * @var Collection $infoSup
     */
    protected $infoSup;

    /**
     * @var string $mention
     */
    protected $mention;

    /**
     * @var string
     */
    protected $id;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->price = new Price();
        $this->priceByMonth = new PriceByMonth();
        $this->sticker = $this->mediaFactory->createMedia();
        $this->infoSup = new ArrayCollection();
        $this->info = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param Collection $info
     *
     * @return $this
     */
    public function setInfo(array $infos)
    {
        foreach ($infos as $info) {
            $pf58Info = new Pf58Information();
            $pf58Info->setDataFromArray($info);
            $this->addInfo($pf58Info);
        }

        return $this;
    }

    /**
     * @param Pf58Information $information
     */
    public function addInfo(Pf58Information $information)
    {
        $this->info->add($information);
    }

    /**
     * @return Collection
     */
    public function getInfoSup()
    {
        return $this->infoSup;
    }

    /**
     * @param array $infos
     *
     * @return $this
     */
    public function setInfoSup(array $infos)
    {
        foreach ($infos as $info) {
            $pf58Info = new Pf58Information();
            $pf58Info->setDataFromArray($info);
            $this->addInfoSup($pf58Info);
        }

        return $this;
    }

    /**
     * @param Pf58Information $information
     */
    public function addInfoSup(Pf58Information $information)
    {
        $this->infoSup->add($information);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabelAfter()
    {
        return $this->labelAfter;
    }

    /**
     * @param string $labelAfter
     *
     * @return $this
     */
    public function setLabelAfter($labelAfter)
    {
        $this->labelAfter = $labelAfter;

        return $this;
    }

    /**
     * @return string
     */
    public function getMention()
    {
        return $this->mention;
    }

    /**
     * @param string $mention
     *
     * @return $this
     */
    public function setMention($mention)
    {
        $this->mention = $mention;

        return $this;
    }

    /**
     * @return Price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param array $price
     *
     * @return $this
     */
    public function setPrice(array $price)
    {
        $this->price->setDataFromArray($price);

        return $this;
    }

    /**
     * @return PriceByMonth
     */
    public function getPriceByMonth()
    {
        return $this->priceByMonth;
    }

    /**
     * @param array $priceByMonth
     *
     * @return $this
     */
    public function setPriceByMonth(array $priceByMonth)
    {
        $this->priceByMonth->setDataFromArray($priceByMonth);

        return $this;
    }

    /**
     * @return Media
     */
    public function getSticker()
    {
        return $this->sticker;
    }

    /**
     * @param array $sticker
     *
     * @return $this
     */
    public function setSticker(array $sticker)
    {
        $this->sticker = $this->mediaFactory->createFromArray($sticker);

        return $this;
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
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
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
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
