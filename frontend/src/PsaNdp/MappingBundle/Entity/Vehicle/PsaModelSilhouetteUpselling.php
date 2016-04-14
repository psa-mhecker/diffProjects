<?php

namespace PsaNdp\MappingBundle\Entity\Vehicle;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * Class PsaModelSilhouetteMonteeGamme
 *
 * @ORM\Table(name="psa_ws_gdg_model_silhouette_upselling", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaModelSilhouetteUpsellingRepository")
 */
class PsaModelSilhouetteUpselling
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
     * @var string
     *
     * @ORM\Column(name="LCDV16", type="string", length=16, nullable=false, unique=true)
     */
    protected $lcdv16;

    /**
     * @var string
     *
     * @ORM\Column(name="VEHICULE_USE", type="string", length=255, nullable=false)
     */
    protected $vehiculeUse;

    /**
     * @var PsaModelSilhouette
     * @ORM\ManyToOne(targetEntity="PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite", inversedBy="upsellings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MODEL_SILHOUETTE_ID", referencedColumnName="ID")
     * })
     */
    protected $modelSilhouette;

    /**
     * @var string
     *
     * @ORM\Column(name="FINISHING_CODE", type="string", length=8, nullable=false)
     */
    protected $finishingCode;

    /**
     * @var string
     *
     * @ORM\Column(name="FINISHING_LABEL", type="string", length=255, nullable=false)
     */
    protected $finishingLabel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="UPSELLING", type="boolean",  nullable=true, options={"default":true})
     */
    protected $upselling;

    /**
     * @var string
     *
     * @ORM\Column(name="FINISHING_REFERENCE", type="string", length=255, nullable=true)
     */
    protected $finishingReference;

    /**
     * @var float
     *
     * @ORM\Column(name="BASE_PRICE", type="float", nullable=false)
     */
    protected $basePrice;

    /**
     * @var PsaLanguage
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Language\PsaLanguage")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="LANGUE_ID", referencedColumnName="LANGUE_ID")
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
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return PsaModelSilhouette
     */
    public function getModelSilhouette()
    {
        return $this->modelSilhouette;
    }

    /**
     *
     * @return string
     */
    public function getFinishingCode()
    {
        return $this->finishingCode;
    }

    /**
     *
     * @return string
     */
    public function getFinishingLabel()
    {
        return $this->finishingLabel;
    }

    /**
     *
     * @return boolean
     */
    public function getUpselling()
    {
        return $this->upselling;
    }

    /**
     *
     * @return string
     */
    public function getFinishingReference()
    {
        return $this->finishingReference;
    }

    /**
     *
     * @param integer $id
     *
     * @return PsaModelSilhouetteUpselling
     */
    public function setId($id)
    {
        $this->id = $id;

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
     * @return PsaModelSilhouetteSite
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
     * @return PsaModelSilhouetteSite
     */
    public function setSite(PsaSite $site)
    {
        $this->site = $site;

        return $this;
    }
    /**
     *
     * @param PsaModelSilhouette $modelSilhouette
     *
     * @return PsaModelSilhouetteUpselling
     */
    public function setModelSilhouette(PsaModelSilhouette $modelSilhouette)
    {
        $this->modelSilhouette = $modelSilhouette;

        return $this;
    }

    /**
     *
     * @param string $finishingCode
     *
     * @return PsaModelSilhouetteUpselling
     */
    public function setFinishingCode($finishingCode)
    {
        $this->finishingCode = $finishingCode;

        return $this;
    }

    /**
     *
     * @param string $finishingLabel
     *
     * @return PsaModelSilhouetteUpselling
     */
    public function setFinishingLabel($finishingLabel)
    {
        $this->finishingLabel = $finishingLabel;

        return $this;
    }

    /**
     *
     * @param boolean $upselling
     *
     * @return PsaModelSilhouetteUpselling
     */
    public function setUpselling($upselling)
    {
        $this->upselling = $upselling;

        return $this;
    }

    /**
     *
     * @param string $finishingReference
     *
     * @return PsaModelSilhouetteUpselling
     */
    public function setFinishingReference($finishingReference)
    {
        $this->finishingReference = $finishingReference;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLcdv16()
    {

        return $this->lcdv16;
    }

    /**
     *
     * @return string
     */
    public function getVehiculeUse()
    {

        return $this->vehiculeUse;
    }

    /**
     *
     * @return float
     */
    public function getBasePrice()
    {

        return $this->basePrice;
    }

    /**
     *
     * @param string $lcdv16
     *
     * @return PsaModelSilhouetteUpselling
     */
    public function setLcdv16($lcdv16)
    {
        $this->lcdv16 = $lcdv16;

        return $this;
    }

    /**
     *
     * @param string $vehiculeUse
     *
     * @return PsaModelSilhouetteUpselling
     */
    public function setVehiculeUse($vehiculeUse)
    {
        $this->vehiculeUse = $vehiculeUse;

        return $this;
    }

    /**
     *
     * @param float $basePrice
     *
     * @return PsaModelSilhouetteUpselling
     */
    public function setBasePrice($basePrice)
    {
        $this->basePrice = $basePrice;

        return $this;
    }
}
