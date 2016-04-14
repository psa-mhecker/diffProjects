<?php

namespace PsaNdp\MappingBundle\Entity\Vehicle;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;


/**
 * Class PsaSegmentationFinishesSite
 *
 * @ORM\Table(name="psa_segmentation_finition_site", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaSegmentationFinishesSiteRepository")
 */
class PsaSegmentationFinishesSite
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var PsaSegmentationFinishes
     * @ORM\ManyToOne(targetEntity="PsaNdp\MappingBundle\Entity\Vehicle\PsaSegmentationFinishes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CENTRAL", referencedColumnName="ID")
     * })
     */
    protected $segmentationFinishes;

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
     * @ORM\Column(name="LABEL_LOCAL", type="string", length=255, nullable=false)
     *
     */
    protected $labelLocal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ENABLE_UPSELLING", type="boolean", length=1, nullable=true)
     */
    protected $enableUpselling;

    /**
     * @var string
     *
     * @ORM\Column(name="MARKETING_CRITERION", type="string", length=255, nullable=true)
     */
    protected $marketingCriterion;

    /**
     * @var string
     *
     * @ORM\Column(name="CLIENTELE_DESIGN", type="string", length=255, nullable=true)
     */
    protected $clienteleDesign;

    /**
     * @var string
     *
     * @ORM\Column(name="CODE", type="string", length=255, nullable=true, unique=true)
     */
    protected $code;


    /**
     * @var integer
     *
     * @ORM\Column(name="ORDER_TYPE", type="integer", nullable=true)
     */
    protected $orderType;

    /**
     * @return PsaSegmentationFinishes
     */
    public function getSegmentationFinishes()
    {
        return $this->segmentationFinishes;
    }

    /**
     * @param PsaSegmentationFinishes $segmentationFinishes
     */
    public function setSegmentationFinishes($segmentationFinishes)
    {
        $this->segmentationFinishes = $segmentationFinishes;
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
     * @return PsaSegmentationFinishesSite
     */
    public function setLangue(PsaLanguage $langue)
    {
        $this->langue = $langue;

        return $this;
    }

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
     * @return PsaSegmentationFinishesSite
     */
    public function setSite(PsaSite $site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLabelLocal()
    {
        return $this->labelLocal;
    }

    /**
     *
     * @param string $labelLocal
     *
     * @return PsaSegmentationFinishesSite
     */
    public function setLabelLocal($labelLocal)
    {
        $this->labelLocal = $labelLocal;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     *
     * @param string $code
     *
     * @return PsaSegmentationFinishesSite
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getEnableUpselling()
    {
        return $this->enableUpselling;
    }

    /**
     *
     * @param boolean $enableUpselling
     *
     * @return PsaSegmentationFinishesSite
     */
    public function setEnableUpselling($enableUpselling)
    {
        $this->enableUpselling = $enableUpselling;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getMarketingCriterion()
    {
        return $this->marketingCriterion;
    }

    /**
     *
     * @param string $marketingCriterion
     *
     * @return PsaSegmentationFinishesSite
     */
    public function setMarketingCriterion($marketingCriterion)
    {
        $this->marketingCriterion = $marketingCriterion;

        return $this;
    }

        /**
     *
     * @return string
     */
    public function getClienteleDesign()
    {
        return $this->clienteleDesign;
    }

    /**
     *
     * @param string $clienteleDesign
     *
     * @return PsaSegmentationFinishesSite
     */
    public function setClienteleDesign($clienteleDesign)
    {
        $this->clienteleDesign = $clienteleDesign;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getOrderType()
    {
        return $this->orderType;
    }

    /**
     *
     * @param int $orderType
     *
     * @return PsaSegmentationFinishesSite
     */
    public function setOrderType($orderType)
    {
        $this->orderType = $orderType;

        return $this;
    }

    /**
     * @param $marketingCriterion
     *
     * @return bool
     */
    public function hasMarketingCriterion($marketingCriterion)
    {
        $marketingCriterions = explode('#',$this->marketingCriterion);

        return in_array($marketingCriterion,$marketingCriterions);
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
