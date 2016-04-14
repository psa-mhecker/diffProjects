<?php

namespace PsaNdp\MappingBundle\Entity\Vehicle;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * Class PsaModelConfig
 *
 * @ORM\Table(name="psa_model_config", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\Vehicle\PsaModelConfigRepository")
 */
class PsaModelConfig
{

    const ORDER_AO = 1;
    const ORDER_PRICE_ASC = 2;
    const ORDER_PRICE_DESC = 3;
    const STRIP_NEW = 1;
    const STRIP_SPECIAL_OFFER = 2;
    const STRIP_SPECIAL_SERIES = 3;

    /**
     * @var PsaSite
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID")
     * })
     */
    protected $site;

    /**
     * @var PsaLanguage
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Language\PsaLanguage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LANGUE_ID", referencedColumnName="LANGUE_ID")
     * })
     */
    protected $langue;

    /**
     * @var int
     *
     * @ORM\Column(name="FINISHING_ORDER", type="integer", nullable=true)
     */
    protected $finishingOrder;

    /**
     * @var boolean
     *
     * @ORM\Column(name="UPSELLING", type="boolean", nullable=true)
     */
    protected $upselling;

    /**
     * @var int
     *
     * @ORM\Column(name="LOCAL_LABEL", type="integer", nullable=true)
     */
    protected $localLabel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="SHOW_CARAC", type="boolean", nullable=true)
     */
    protected $showCharacteristic;

    /**
     * @var boolean
     *
     * @ORM\Column(name="SHOW_COMPARISONCHART", type="boolean", nullable=true)
     */
    protected $showComparisonChart;

    /**
     * @var boolean
     *
     * @ORM\Column(name="SHOW_COMPARISONCHART_BUTTON_OPEN", type="boolean", nullable=true)
     */
    protected $showComparisonChartButtonOpen;

    /**
     * @var boolean
     *
     * @ORM\Column(name="SHOW_COMPARISONCHART_BUTTON_CLOSE", type="boolean", nullable=true)
     */
    protected $showComparisonChartButtonClose;

    /**
     * @var boolean
     *
     * @ORM\Column(name="SHOW_COMPARISONCHART_BUTTON_DIFF", type="boolean", nullable=true)
     */
    protected $showComparisonChartButtonDiff;

    /**
     * @var boolean
     *
     * @ORM\Column(name="SHOW_COMPARISONCHART_BUTTON_PRINT", type="boolean", nullable=true)
     */
    protected $showComparisonChartButtonPrint;

    /**
     * @var int
     *
     * @ORM\Column(name="CTA_DISCOVER_ORDER", type="integer", nullable=true)
     */
    protected $ctaDiscoverOrder;

    /**
     * @var boolean
     *
     * @ORM\Column(name="CTA_CONFIGURE_DISPLAY", type="boolean", nullable=true)
     */
    protected $ctaConfigureDisplay;

    /**
     * @var int
     *
     * @ORM\Column(name="CTA_CONFIGURE_ORDER", type="integer", nullable=true)
     */
    protected $ctaConfigureOrder;

    /**
     * @var boolean
     *
     * @ORM\Column(name="CTA_STOCK_DISPLAY", type="boolean", nullable=true)
     */
    protected $ctaStockDisplay;

    /**
     * @var int
     *
     * @ORM\Column(name="CTA_STOCK_ORDER", type="integer", nullable=true)
     */
    protected $ctaStockOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="STRIP_ORDER",  type="string", length=255, nullable=true)
     */
    protected $stripOrder;

    /**
     * @var integer
     *
     * @ORM\Column(name="CTA_ERREUR",  type="integer", length=1, nullable=false)
     */
    protected $ctaErreur;

    /**
     * @var integer
     *
     * @ORM\Column(name="CTA_ERREUR_ID",  type="integer", length=11, nullable=true)
     */
    protected $ctaErreurId;

    /**
     * @var string
     *
     * @ORM\Column(name="CTA_ERREUR_ACTION",  type="string", length=255, nullable=true)
     */
    protected $ctaErreurAction;

    /**
     * @var string
     *
     * @ORM\Column(name="CTA_ERREUR_STYLE",  type="string", length=255, nullable=true)
     */
    protected $ctaErreurStyle;

    /**
     * @var string
     *
     * @ORM\Column(name="CTA_ERREUR_TARGET",  type="string", length=255, nullable=true)
     */
    protected $ctaErreurTarget;

    /**
     *
     * @return PsaSite
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     *
     * @param PsaSite $site
     *
     * @return PsaModelConfig
     */
    public function setSite(PsaSite $site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     *
     * @return PsaLanguage
     */
    public function getLangue()
    {
        return $this->langue;
    }

    /**
     *
     * @param PsaLanguage $langue
     *
     * @return PsaModelConfig
     */
    public function setLangue(PsaLanguage $langue)
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getFinishingOrder()
    {
        return $this->finishingOrder;
    }

    /**
     *
     * @param int $finishingOrder
     *
     * @return PsaModelConfig
     */
    public function setFinishingOrder($finishingOrder)
    {
        $this->finishingOrder = $finishingOrder;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getCtaConfigureDisplay()
    {
        return $this->ctaConfigureDisplay;
    }

    /**
     *
     * @param boolean $ctaConfigureDisplay
     *
     * @return PsaModelConfig
     */
    public function setCtaConfigureDisplay($ctaConfigureDisplay)
    {
        $this->ctaConfigureDisplay = $ctaConfigureDisplay;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getCtaConfigureOrder()
    {
        return $this->ctaConfigureOrder;
    }

    /**
     *
     * @param int $ctaConfigureOrder
     *
     * @return PsaModelConfig
     */
    public function setCtaConfigureOrder($ctaConfigureOrder)
    {
        $this->ctaConfigureOrder = $ctaConfigureOrder;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getCtaDiscoverOrder()
    {
        return $this->ctaDiscoverOrder;
    }

    /**
     *
     * @param int $ctaDiscoverOrder
     *
     * @return PsaModelConfig
     */
    public function setCtaDiscoverOrder($ctaDiscoverOrder)
    {
        $this->ctaDiscoverOrder = $ctaDiscoverOrder;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isCtaStockDisplayed()
    {
        return $this->ctaStockDisplay;
    }

    /**
     *
     * @param boolean $ctaStockDisplay
     *
     * @return PsaModelConfig
     */
    public function setCtaStockDisplay($ctaStockDisplay)
    {
        $this->ctaStockDisplay = $ctaStockDisplay;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getCtaStockOrder()
    {
        return $this->ctaStockOrder;
    }

    /**
     *
     * @param int $ctaStockOrder
     *
     * @return PsaModelConfig
     */
    public function setCtaStockOrder($ctaStockOrder)
    {
        $this->ctaStockOrder = $ctaStockOrder;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getLocalLabel()
    {
        return $this->localLabel;
    }

    /**
     *
     * @param int $localLabel
     *
     * @return PsaModelConfig
     */
    public function setLocalLabel($localLabel)
    {
        $this->localLabel = $localLabel;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function upsellingIsActived()
    {
        return $this->upselling;
    }

    /**
     *
     * @param boolean $active
     *
     * @return PsaModelConfig
     */
    public function setUpselling($active)
    {
        $this->upselling = $active;

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getStripOrder()
    {
        return explode('#', $this->stripOrder);
    }

    /**
     *
     * @param string $stripOrder
     *
     * @return PsaModelConfig
     */
    public function setStripOrder($stripOrder)
    {
        $this->stripOrder = $stripOrder;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getShowCharacteristic()
    {
        return $this->showCharacteristic;
    }

    /**
     *
     * @param boolean $showCaracteristic
     *
     * @return PsaModelConfig
     */
    public function setShowCaracteristic($showCaracteristic)
    {
        $this->showCaracteristic = $showCaracteristic;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getShowComparisonChart()
    {
        return $this->showComparisonChart;
    }

    /**
     *
     * @param boolean $showComparisonChart
     *
     * @return PsaModelConfig
     */
    public function setShowComparisonChart($showComparisonChart)
    {
        $this->showComparisonChart = $showComparisonChart;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getShowComparisonChartButtonClose()
    {
        return $this->showComparisonChartButtonClose;
    }

    /**
     *
     * @param boolean $showComparisonChartButtonClose
     *
     * @return PsaModelConfig
     */
    public function setShowComparisonChartButtonClose($showComparisonChartButtonClose)
    {
        $this->showComparisonChartButtonClose = $showComparisonChartButtonClose;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getShowComparisonChartButtonDiff()
    {
        return $this->showComparisonChartButtonDiff;
    }

    /**
     *
     * @param boolean $showComparisonChartButtonDiff
     *
     * @return PsaModelConfig
     */
    public function setShowComparisonChartButtonDiff($showComparisonChartButtonDiff)
    {
        $this->showComparisonChartButtonDiff = $showComparisonChartButtonDiff;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getShowComparisonChartButtonOpen()
    {
        return $this->showComparisonChartButtonOpen;
    }

    /**
     *
     * @param boolean $showComparisonChartButtonOpen
     *
     * @return PsaModelConfig
     */
    public function setShowComparisonChartButtonOpen($showComparisonChartButtonOpen)
    {
        $this->showComparisonChartButtonOpen = $showComparisonChartButtonOpen;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getShowComparisonChartButtonPrint()
    {
        return $this->showComparisonChartButtonPrint;
    }

    /**
     *
     * @param boolean $showComparisonChartButtonPrint
     *
     * @return PsaModelConfig
     */
    public function setShowComparisonChartButtonPrint($showComparisonChartButtonPrint)
    {
        $this->showComparisonChartButtonPrint = $showComparisonChartButtonPrint;

        return $this;
    }
    
    /**
     * 
     * @return integer
     */
    public function getCtaErreur()
    {

        return $this->ctaErreur;
    }

    /**
     *
     * @return integer
     */
    public function getCtaErreurId()
    {

        return $this->ctaErreurId;
    }

    /**
     *
     * @return string
     */
    public function getCtaErreurAction()
    {

        return $this->ctaErreurAction;
    }

    /**
     *
     * @return string
     */
    public function getCtaErreurStyle()
    {

        return $this->ctaErreurStyle;
    }

    /**
     *
     * @return string
     */
    public function getCtaErreurTarget()
    {

        return $this->ctaErreurTarget;
    }

    /**
     *
     * @param integer $ctaErreur
     *
     * @return PsaModelConfig
     */
    public function setCtaErreur($ctaErreur)
    {
        $this->ctaErreur = $ctaErreur;

        return $this;
    }

    /**
     *
     * @param integer $ctaErreurId
     *
     * @return PsaModelConfig
     */
    public function setCtaErreurId($ctaErreurId)
    {
        $this->ctaErreurId = $ctaErreurId;

        return $this;
    }

    /**
     *
     * @param string $ctaErreurAction
     *
     * @return PsaModelConfig
     */
    public function setCtaErreurAction($ctaErreurAction)
    {
        $this->ctaErreurAction = $ctaErreurAction;

        return $this;
    }

    /**
     *
     * @param string $ctaErreurStyle
     *
     * @return PsaModelConfig
     */
    public function setCtaErreurStyle($ctaErreurStyle)
    {
        $this->ctaErreurStyle = $ctaErreurStyle;

        return $this;
    }

    /**
     *
     * @param string $ctaErreurTarget
     *
     * @return PsaModelConfig
     */
    public function setCtaErreurTarget($ctaErreurTarget)
    {
        $this->ctaErreurTarget = $ctaErreurTarget;

        return $this;
    }


}
