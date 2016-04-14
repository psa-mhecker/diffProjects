<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PsaNdp\MappingBundle\Entity\PsaAfterSaleServices;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;

/**
 * Class Pc53Apv
 * @package PsaNdp\MappingBundle\Object\Block
 */
class Pc53Apv extends Content
{
    /**
     * @var PsaAfterSaleServices
     */
    protected $afterSaleServices;

    /**
     * @var MediaFactory
     */
    protected $firstMedia;

    /**
     * @var MediaFactory
     */
    protected $secondMedia;

    /**
     * @var array
     */
    protected $ctaLevel1;
    /**
     * @var array
     */
    protected $ctaLevel2;

    /**
     * @var string
     */
    protected $apvTitle;

    /**
     * @var integer
     */
    protected $apvTypeLabelLink;

    /**
     * @var string
     */
    protected $apvLabelLink;

    /**
     * @var string
     */
    protected $apvUrl;

    /**
     * @var integer
     */
    protected $apvColumnNumber;

    /**
     * @var integer
     */
    protected $apvPricePosition;

    /**
     * @var integer
     */
    protected $apvTypeLabelPrice;

    /**
     * @var string
     */
    protected $apvPriceLabel;

    /**
     * @var float
     */
    protected $apvPrice;

    /**
     * @var string
     */
    protected $apvDescription;

    /**
     * @var integer
     */
    protected $apvTypeLabelPrice2;

    /**
     * @var string
     */
    protected $apvPriceLabel2;

    /**
     * @var float
     */
    protected $apvPrice2;

    /**
     * @var string
     */
    protected $apvDescription2;

    /**
     * @return PsaAfterSaleServices
     */
    public function getAfterSaleServices()
    {
        return $this->afterSaleServices;
    }

    /**
     * @param CtaFactory   $ctaFactory
     * @param MediaFactory $mediaFactory
     */
    public function __construct(CtaFactory $ctaFactory, MediaFactory $mediaFactory)
    {
        $this->ctaFactory = $ctaFactory;
        $this->mediaFactory = $mediaFactory;
    }

    /**
     * @return array
     */
    public function getCtaLevel1()
    {
        $this->ctaLevel1 = $this->ctaFactory->create($this->ctaLevel1['ctaList']);

        return $this->ctaLevel1;
    }

    /**
     * @return array
     */
    public function getCtaLevel2()
    {
        $this->ctaLevel2 = $this->ctaFactory->create($this->ctaLevel2['ctaList']);

        return $this->ctaLevel2;
    }

    /**
     * @return MediaFactory
     */
    public function getFirstMedia()
    {
        $this->firstMedia = $this->mediaFactory->createFromMedia($this->afterSaleServices->getMedia());

        return $this->firstMedia;
    }

    /**
     * @return MediaFactory
     */
    public function getSecondMedia()
    {
        $this->secondMedia = $this->mediaFactory->createFromMedia($this->afterSaleServices->getMedia2());

        return $this->secondMedia;
    }

    /**
     * @return string
     */
    public function getApvTitle()
    {
        $this->apvTitle = $this->afterSaleServices->getTitle();

        return $this->apvTitle;
    }

    /**
     * @return int
     */
    public function getApvTypeLabelLink()
    {
        $this->apvTypeLabelLink = $this->afterSaleServices->getTypeLabelLink();

        return $this->apvTypeLabelLink;
    }

    /**
     * @return string
     */
    public function getApvLabelLink()
    {
        $this->apvLabelLink = $this->afterSaleServices->getLabelLink();

        return $this->apvLabelLink;
    }

    /**
     * @return string
     */
    public function getApvUrl()
    {
        $this->apvUrl = $this->afterSaleServices->getUrl();

        return $this->apvUrl;
    }

    /**
     * @return int
     */
    public function getApvColumnNumber()
    {
        $this->apvColumnNumber = $this->afterSaleServices->getColumnNumber();

        return $this->apvColumnNumber;
    }

    /**
     * @return int getApvTitle
     */
    public function getApvPricePosition()
    {
        $this->apvPricePosition = $this->afterSaleServices->getPricePosition();

        return $this->apvPricePosition;
    }

    /**
     * @return int
     */
    public function getApvTypeLabelPrice()
    {
        $this->apvTypeLabelPrice = $this->afterSaleServices->getTypeLabelPrice();

        return $this->apvTypeLabelPrice;
    }

    /**
     * @return string
     */
    public function getApvPriceLabel()
    {
        $this->apvPriceLabel = $this->afterSaleServices->getPriceLabel();

        return $this->apvPriceLabel;
    }

    /**
     * @return float
     */
    public function getApvPrice()
    {
        $this->apvPrice = $this->afterSaleServices->getPrice();

        return $this->apvPrice;
    }
    /**
     * @return string
     */
    public function getApvDescription()
    {
        $this->apvDescription = $this->afterSaleServices->getDescription();

        return $this->apvDescription;
    }

    /**
     * @return int
     */
    public function getApvTypeLabelPrice2()
    {
        $this->apvTypeLabelPrice2 = $this->afterSaleServices->getTypeLabelPrice2();

        return $this->apvTypeLabelPrice2;
    }

    /**
     * @return string
     */
    public function getApvPriceLabel2()
    {
        $this->apvPriceLabel2 = $this->afterSaleServices->getPriceLabel2();

        return $this->apvPriceLabel2;
    }

    /**
     * @return float
     */
    public function getApvPrice2()
    {
        $this->apvPrice2 = $this->afterSaleServices->getPrice2();

        return $this->apvPrice2;
    }

    /**
     * @return string
     */
    public function getApvDescription2()
    {
        $this->apvDescription2 = $this->afterSaleServices->getDescription2();

        return $this->apvDescription2;
    }

}
