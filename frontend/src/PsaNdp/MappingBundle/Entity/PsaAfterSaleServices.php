<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * PsaAfterSaleServices
 *
 * @ORM\Table(name="psa_after_sale_services", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaAfterSaleServicesRepository")
 */
class PsaAfterSaleServices
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var PsaLanguage
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Language\PsaLanguage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LANGUE_ID", referencedColumnName="LANGUE_ID", nullable=false)
     * })
     * @ORM\Id
     */
    private $language;

    /**
     * @var PsaSite
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID", nullable=false)
     * })
     * @ORM\Id
     */
    private $site;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="PsaNdp\MappingBundle\Entity\PsaFilterAfterSaleServices", inversedBy="afterSaleServices")
     * @ORM\JoinTable(name="psa_after_sale_services_filters_relation",
     *      joinColumns={
     *          @ORM\JoinColumn(name="AFTER_SALE_SERVICES_ID", referencedColumnName="ID", onDelete="CASCADE"),
     *          @ORM\JoinColumn(name="LANGUE_ID", referencedColumnName="LANGUE_ID", onDelete="CASCADE"),
     *          @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="FILTERS_ID", referencedColumnName="ID", onDelete="CASCADE"),
     *          @ORM\JoinColumn(name="LANGUE_ID", referencedColumnName="LANGUE_ID", onDelete="CASCADE"),
     *          @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID", onDelete="CASCADE")
     *      }
     * )
     */
    private $filters;

    /**
     * @var string
     *
     * @ORM\Column(name="LABEL", type="string", length=255, nullable = false)
     */
    private $title;

    /**
     * @var PsaMedia
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Media\PsaMedia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MEDIA_ID", referencedColumnName="MEDIA_ID", nullable=false)
     * })
     */
    private $media;

    /**
     * @var PsaMedia
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Media\PsaMedia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MEDIA_ID2", referencedColumnName="MEDIA_ID", nullable=false)
     * })
     */
    private $media2;

    /**
     * @var integer
     *
     * @ORM\Column(name="TYPE_LABEL_LINK", type="integer", nullable=false)
     */
    private $typeLabelLink;

    /**
     * @var string
     *
     * @ORM\Column(name="LABEL_LINK", type="string", length=255, nullable=false)
     */
    private $labelLink;

    /**
     * @var string
     *
     * @ORM\Column(name="URL", type="string", length=255)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="COLUMN_NUMBER", type="integer", nullable=false)
     */
    private $columnNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="LEGAL_NOTICE", type="string", length=10)
     */
    private $legalNotice;

    /**
     * @var integer
     *
     * @ORM\Column(name="PRICE_POSITION", type="integer", nullable=false)
     */
    private $pricePosition;

    /**
     * @var integer
     *
     * @ORM\Column(name="TYPE_LABEL_PRICE", type="integer", nullable=false)
     */
    private $typeLabelPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="PRICE_LABEL", type="string", length=255)
     */
    private $priceLabel;

    /**
     * @var float
     *
     * @ORM\Column(name="PRICE", type="float")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPTION", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="TYPE_LABEL_PRICE2", type="integer")
     */
    private $typeLabelPrice2;

    /**
     * @var string
     *
     * @ORM\Column(name="PRICE_LABEL2", type="string", length=255)
     */
    private $priceLabel2;

    /**
     * @var float
     *
     * @ORM\Column(name="PRICE2", type="float")
     */
    private $price2;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPTION2", type="string", length=255)
     */
    private $description2;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filters = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PsaLanguage
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param PsaLanguage $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return PsaSite
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param PsaSite $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return Collection
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param Collection $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @param PsaFilterAfterSaleServices $filter
     */
    public function addFilter(PsaFilterAfterSaleServices $filter)
    {
        $this->filters->add($filter);
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return PsaAfterSaleServices
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set media
     *
     * @param PsaMedia $media
     *
     * @return PsaAfterSaleServices
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return PsaMedia
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set media2
     *
     * @param PsaMedia $media2
     *
     * @return PsaAfterSaleServices
     */
    public function setMedia2($media2)
    {
        $this->media2 = $media2;

        return $this;
    }

    /**
     * Get media2
     *
     * @return PsaMedia
     */
    public function getMedia2()
    {
        return $this->media2;
    }

    /**
     * Set typeLabelLink
     *
     * @param integer $typeLabelLink
     *
     * @return PsaAfterSaleServices
     */
    public function setTypeLabelLink($typeLabelLink)
    {
        $this->typeLabelLink = $typeLabelLink;

        return $this;
    }

    /**
     * Get typeLabelLink
     *
     * @return integer
     */
    public function getTypeLabelLink()
    {
        return $this->typeLabelLink;
    }

    /**
     * Set labelLink
     *
     * @param string $labelLink
     *
     * @return PsaAfterSaleServices
     */
    public function setLabelLink($labelLink)
    {
        $this->labelLink = $labelLink;

        return $this;
    }

    /**
     * Get labelLink
     *
     * @return string
     */
    public function getLabelLink()
    {
        return $this->labelLink;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return PsaAfterSaleServices
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set columnNumber
     *
     * @param integer $columnNumber
     *
     * @return PsaAfterSaleServices
     */
    public function setColumnNumber($columnNumber)
    {
        $this->columnNumber = $columnNumber;

        return $this;
    }

    /**
     * Get columnNumber
     *
     * @return integer
     */
    public function getColumnNumber()
    {
        return $this->columnNumber;
    }

    /**
     * Set legalNotice
     *
     * @param string $legalNotice
     *
     * @return PsaAfterSaleServices
     */
    public function setLegalNotice($legalNotice)
    {
        $this->legalNotice = $legalNotice;

        return $this;
    }

    /**
     * Get legalNotice
     *
     * @return string
     */
    public function getLegalNotice()
    {
        return $this->legalNotice;
    }

    /**
     * Set pricePosition
     *
     * @param integer $pricePosition
     *
     * @return PsaAfterSaleServices
     */
    public function setPricePosition($pricePosition)
    {
        $this->pricePosition = $pricePosition;

        return $this;
    }

    /**
     * Get pricePosition
     *
     * @return integer
     */
    public function getPricePosition()
    {
        return $this->pricePosition;
    }

    /**
     * Set typeLabelPrice
     *
     * @param integer $typeLabelPrice
     *
     * @return PsaAfterSaleServices
     */
    public function setTypeLabelPrice($typeLabelPrice)
    {
        $this->typeLabelPrice = $typeLabelPrice;

        return $this;
    }

    /**
     * Get typeLabelPrice
     *
     * @return integer
     */
    public function getTypeLabelPrice()
    {
        return $this->typeLabelPrice;
    }

    /**
     * Set priceLabel
     *
     * @param string $priceLabel
     *
     * @return PsaAfterSaleServices
     */
    public function setPriceLabel($priceLabel)
    {
        $this->priceLabel = $priceLabel;

        return $this;
    }

    /**
     * Get priceLabel
     *
     * @return string
     */
    public function getPriceLabel()
    {
        return $this->priceLabel;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return PsaAfterSaleServices
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return PsaAfterSaleServices
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set typeLabelPrice2
     *
     * @param integer $typeLabelPrice2
     *
     * @return PsaAfterSaleServices
     */
    public function setTypeLabelPrice2($typeLabelPrice2)
    {
        $this->typeLabelPrice2 = $typeLabelPrice2;

        return $this;
    }

    /**
     * Get typePriceLabel2
     *
     * @return integer
     */
    public function getTypeLabelPrice2()
    {
        return $this->typeLabelPrice2;
    }

    /**
     * Set priceLabel2
     *
     * @param string $priceLabel2
     *
     * @return PsaAfterSaleServices
     */
    public function setPriceLabel2($priceLabel2)
    {
        $this->priceLabel2 = $priceLabel2;

        return $this;
    }

    /**
     * Get priceLabel2
     *
     * @return string
     */
    public function getPriceLabel2()
    {
        return $this->priceLabel2;
    }

    /**
     * Set price2
     *
     * @param float $price2
     *
     * @return PsaAfterSaleServices
     */
    public function setPrice2($price2)
    {
        $this->price2 = $price2;

        return $this;
    }

    /**
     * Get price2
     *
     * @return float
     */
    public function getPrice2()
    {
        return $this->price2;
    }

    /**
     * Set description2
     *
     * @param string $description2
     *
     * @return PsaAfterSaleServices
     */
    public function setDescription2($description2)
    {
        $this->description2 = $description2;

        return $this;
    }

    /**
     * Get description2
     *
     * @return string
     */
    public function getDescription2()
    {
        return $this->description2;
    }
}

