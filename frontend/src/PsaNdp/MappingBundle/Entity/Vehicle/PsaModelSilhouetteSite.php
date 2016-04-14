<?php

namespace PsaNdp\MappingBundle\Entity\Vehicle;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use Doctrine\Common\Collections\ArrayCollection;
use PsaNdp\MappingBundle\Entity\PsaFinishingColor;

/**
 * Class PsaModelSilhouetteSite.
 *
 * @ORM\Table(name="psa_ws_gdg_model_silhouette_site", options={"collate"="utf8_swedish_ci"}, uniqueConstraints={@ORM\UniqueConstraint(name="model_code", columns={"LCDV6","SITE_ID","LANGUE_ID","GROUPING_CODE"})})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteSiteRepository")
 */
class PsaModelSilhouetteSite
{
    const STRIP_NEW = 'NDP_NEW';
    const STRIP_SPECIAL_OFFER = 'NDP_SPECIAL_OFFER';
    const STRIP_SPECIAL_SERIES = 'NDP_SPECIAL_SERIE';
    const STRIP_LIMITED_SERIES = 'NDP_LIMITED_SERIE';

    /**
     * @var int
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var PsaLanguage
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Language\PsaLanguage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LANGUE_ID", referencedColumnName="LANGUE_ID")
     * })
     */
    protected $langue;

    /**
     * @var PsaSite
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID")
     * })
     */
    protected $site;

    /**
     * @var string
     *
     * @ORM\Column(name="GENDER", type="string", length=255, nullable=false)
     */
    protected $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="LCDV6", type="string", length=16, nullable=false)
     */
    protected $lcdv6;

    /**
     * @var string
     *
     * @ORM\Column(name="GROUPING_CODE", type="string", length=255, nullable=false)
     */
    protected $groupingCode;

    /**
     * @var string
     *
     * @ORM\Column(name="COMMERCIAL_LABEL", type="string", length=255, nullable=false)
     */
    protected $commercialLabel;

    /**
     * @var int
     *
     * @ORM\Column(name="SHOW_FINISHING", type="integer", nullable=true, options={"default":0})
     */
    protected $showFinishing;

    /**
     * @var bool
     *
     * @ORM\Column(name="NEW_COMMERCIAL_STRIP", type="boolean", nullable=false, options={"default":false})
     */
    protected $newCommercialStrip;

    /**
     * @var bool
     *
     * @ORM\Column(name="SPECIAL_OFFER_COMMERCIAL_STRIP", type="boolean",  nullable=false, options={"default":false})
     */
    protected $specialOfferCommercialStrip;

    /**
     * @var bool
     *
     * @ORM\Column(name="SPECIAL_SERIES_COMMERCIAL_STRIP", type="boolean",  nullable=false, options={"default":false})
     */
    protected $specialSeriesCommercialStrip;

    /**
     * @var bool
     *
     * @ORM\Column(name="LIMITED_SERIES_COMMERCIAL_STRIP", type="boolean",  nullable=false, options={"default":false})
     */
    protected $limitedSeriesCommercialStrip;

    /**
     * @var bool
     *
     * @ORM\Column(name="SHOW_IN_CONFIG", type="boolean", length=1, options={"default":false})
     */
    protected $showInConfigurator;

    /**
     * @var bool
     *
     * @ORM\Column(name="STOCK_WEBSTORE", type="boolean", nullable=true, options={"default":false})
     */
    protected $stockWebstore;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteUpselling",  mappedBy="modelSilhouette")
     */
    protected $upsellings;

    /**
     * @var bool
     *
     * @ORM\Column(name="DISPLAY_PRICE", type="boolean", nullable=false, options={"default":true})
     */
    protected $displayPrice;

    /**
     * @var PsaFinishingColor
     *
     * @ORM\ManyToOne(targetEntity="PsaNdp\MappingBundle\Entity\PsaFinishingColor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="COLOR_ID", referencedColumnName="ID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $color;

    /**
     * @var array
     */
    protected $strips;

    /**
     * @var array
     */
    protected $stripsOrder;

    public function __construct()
    {
        $this->upsellings = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return PsaModelSilhouetteSite
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return PsaLanguage
     */
    public function getLangue()
    {
        return $this->langue;
    }

    /**
     * @param PsaLanguage $langue
     *
     * @return PsaModelSilhouetteSite
     */
    public function setLangue(PsaLanguage $langue)
    {
        $this->langue = $langue;

        return $this;
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
     *
     * @return PsaModelSilhouetteSite
     */
    public function setSite(PsaSite $site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getLcdv6()
    {
        return $this->lcdv6;
    }

    /**
     * @return string
     */
    public function getGroupingCode()
    {
        return $this->groupingCode;
    }

    /**
     * @return string
     */
    public function getCommercialLabel()
    {
        return $this->commercialLabel;
    }

    /**
     * @return bool
     */
    public function getShowFinishing()
    {
        return $this->showFinishing;
    }

    /**
     * @return bool
     */
    public function getNewCommercialStrip()
    {
        return $this->newCommercialStrip;
    }

    /**
     * @return bool
     */
    public function getSpecialOfferCommercialStrip()
    {
        return $this->specialOfferCommercialStrip;
    }

    /**
     * @return bool
     */
    public function getSpecialSeriesCommercialStrip()
    {
        return $this->specialSeriesCommercialStrip;
    }

    /**
     * @return bool
     */
    public function getShowInConfigurator()
    {
        return $this->showInConfigurator;
    }

    /**
     * @return bool
     */
    public function getStockWebstore()
    {
        return $this->stockWebstore;
    }

    /**
     * @param string
     *
     * @return PsaModelSilhouetteSite
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @param string $lcdv6
     *
     * @return PsaModelSilhouetteSite
     */
    public function setLcdv6($lcdv6)
    {
        $this->lcdv6 = $lcdv6;

        return $this;
    }

    /**
     * @param string $groupingCode
     *
     * @return PsaModelSilhouetteSite
     */
    public function setGroupingCode($groupingCode)
    {
        $this->groupingCode = $groupingCode;

        return $this;
    }

    /**
     * @param string $commercialLabel
     *
     * @return PsaModelSilhouetteSite
     */
    public function setCommercialLabel($commercialLabel)
    {
        $this->commercialLabel = $commercialLabel;

        return $this;
    }

    /**
     * @param bool $showFinishing
     *
     * @return PsaModelSilhouetteSite
     */
    public function setShowFinishing($showFinishing)
    {
        $this->showFinishing = $showFinishing;

        return $this;
    }

    /**
     * @param bool $activate
     *
     * @return PsaModelSilhouetteSite
     */
    public function setNewCommercialStrip($activate)
    {
        $this->newCommercialStrip = $activate;

        return $this;
    }

    /**
     * @param bool $activate
     *
     * @return PsaModelSilhouetteSite
     */
    public function setSpecialOfferCommercialStrip($activate)
    {
        $this->specialOfferCommercialStrip = $activate;

        return $this;
    }

    /**
     * @param bool $activate
     *
     * @return PsaModelSilhouetteSite
     */
    public function setSpecialSeriesCommercialStrip($activate)
    {
        $this->specialSeriesCommercialStrip = $activate;

        return $this;
    }

    /**
     * @param bool $showInConfigurator
     *
     * @return PsaModelSilhouetteSite
     */
    public function setShowInConfigurator($showInConfigurator)
    {
        $this->showInConfigurator = $showInConfigurator;

        return $this;
    }

    /**
     * @param bool $stockWebstore
     *
     * @return PsaModelSilhouetteSite
     */
    public function setStockWebstore($stockWebstore)
    {
        $this->stockWebstore = $stockWebstore;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpsellings()
    {
        return $this->upsellings;
    }

    /**
     * @param mixed $upsellings
     *
     * @return PsaModelSilhouetteSite
     */
    public function setUpsellings($upsellings)
    {
        $this->upsellings = $upsellings;

        return $this;
    }

    public function getUpsellingByFinitionCode($finitionCode)
    {
        $upselling = null;
        foreach ($this->upsellings as $up) {
            if ($up->getFinishingCode() == $finitionCode) {
                $upselling = $up;
                break;
            }
        }

        return $upselling;
    }

    /**
     * @return bool
     */
    public function isLimitedSeriesCommercialStrip()
    {
        return $this->limitedSeriesCommercialStrip;
    }

    /**
     * @param bool $limitedSeriesCommercialStrip
     *
     * @return PsaModelSilhouetteSite
     */
    public function setLimitedSeriesCommercialStrip($limitedSeriesCommercialStrip)
    {
        $this->limitedSeriesCommercialStrip = $limitedSeriesCommercialStrip;

        return $this;
    }

    public function hasCustomColor()
    {
        return $this->limitedSeriesCommercialStrip || $this->specialSeriesCommercialStrip;
    }

    /**
     * @return bool
     */
    public function isDisplayPrice()
    {
        return $this->displayPrice;
    }

    /**
     * @param bool $displayPrice
     */
    public function setDisplayPrice($displayPrice)
    {
        $this->displayPrice = $displayPrice;
    }

    /**
     * @return array
     */
    public function getStripsOrder()
    {
        return $this->stripsOrder;
    }

    /**
     * @param array $stripsOrder
     *
     * @return PsaModelSilhouetteSite
     */
    public function setStripsOrder($stripsOrder)
    {
        $this->stripsOrder = $stripsOrder;
        $this->initStrips();

        return $this;
    }

    /**
     * @param $stripType
     *
     * @return bool $stripType
     */
    public function hasCommercialStrip($stripType)
    {
        if (!isset($this->strips)) {
            $this->initStrips();
        }

        return isset($this->strips[$stripType]);
    }

    /**
     * @param $stripType
     *
     * @return string
     */
    public function getCommercialStrip($stripType)
    {
        $commercialStrip = null;
        if ($this->hasCommercialStrip($stripType)) {
            $commercialStrip = $this->strips[$stripType];
        }

        return $commercialStrip;
    }

    private function initStrips()
    {
        $strips = array(
            self::STRIP_NEW => $this->getNewCommercialStrip(),
            self::STRIP_SPECIAL_OFFER => $this->getSpecialOfferCommercialStrip(),
            self::STRIP_SPECIAL_SERIES => $this->getSpecialSeriesCommercialStrip(),
            self::STRIP_LIMITED_SERIES => $this->isLimitedSeriesCommercialStrip(),
        );

        $orders = $this->getStripsOrder();
        $this->strips = [];
        foreach ($orders as $stripName) {
            if ($strips[$stripName]) {
                $this->strips[$stripName] = $stripName;
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstActiveStrip()
    {
        if (!isset($this->strips)) {
            $this->initStrips();
        }
        reset($this->strips);

        return key($this->strips);
    }

    /**
     * @return PsaFinishingColor
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param PsaFinishingColor $color
     *
     * @return $this
     */
    public function setColor(PsaFinishingColor $color)
    {
        $this->color = $color;

        return $this;
    }
}
