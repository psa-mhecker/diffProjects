<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf58Object;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Media;
use PsaNdp\MappingBundle\Object\PriceByMonth;

/**
 * Class Pf58MobileDetails
 */
class Pf58MobileDetails extends Content
{
    protected $mapping = array(
        'img' => 'image'
    );

    /**
     * @var Media $image
     */
    protected $image;

    /**
     * @var PriceByMonth $price
     */
    protected $price;

    /**
     * @var string $text
     */
    protected $credit;

    /**
     * @var string $text
     */
    protected $mention;

    /**
     * @var string $text
     */
    protected $text;

    /**
     * @var Media $sticker
     */
    protected $sticker;

    /**
     * @var Collection $infos
     */
    protected $infos;

    /**
     * @var Collection $priceList
     */
    protected $priceList;

    /**
     * @var Pf58Finitions $finitions
     */
    protected $finitions;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mediaFactory = new MediaFactory();
        $this->price = new PriceByMonth();
        $this->infos = new ArrayCollection();
        $this->priceList = new ArrayCollection();
        $this->finitions = new Pf58Finitions();
    }

    /**
     * @return string
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * @param string $credit
     *
     * @return $this
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * @return Pf58Finitions
     */
    public function getFinitions()
    {
        return $this->finitions;
    }

    /**
     * @param array $finitions
     *
     * @return $this
     */
    public function setFinitions(array $finitions)
    {
        $this->finitions->setDataFromArray($finitions);

        return $this;
    }

    /**
     * @return Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param array $image
     *
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $this->mediaFactory->createFromArray($image);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * @param array $infos
     *
     * @return $this
     */
    public function setInfos(array $infos)
    {
        foreach ($infos as $info) {
            $information = new Pf58Information();
            $information->setDataFromArray($info);
            $this->addInfo($information);
        }

        return $this;
    }

    /**
     * @param Pf58Information $information
     */
    public function addInfo(Pf58Information $information)
    {
        $this->infos->add($information);
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
     * @return PriceByMonth
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
     * @return Collection
     */
    public function getPriceList()
    {
        return $this->priceList;
    }

    /**
     * @param array $priceList
     *
     * @return $this
     */
    public function setPriceList(array $priceList)
    {
        foreach ($priceList as $price) {
            $conso = new Pf58Consommation();
            $conso->setDataFromArray($price);
            $this->addPrice($conso);
        }

        return $this;
    }

    /**
     * @param Pf58Consommation $consommation
     */
    public function addPrice(Pf58Consommation $consommation)
    {
        $this->priceList->add($consommation);
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
}
