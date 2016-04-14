<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * PsaContentCategoryCategory
 *
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaDealerServiceRepository")
 * @ORM\Table(name="psa_pdv_service", options={"collate"="utf8_swedish_ci"}, indexes={@ORM\Index(name="PDV_SERVICE_LABEL", columns={"PDV_SERVICE_LABEL"})} )
 *
 */
class PsaDealerService
{

    /**
     * @var integer
     *
     * @ORM\Column(name="PDV_SERVICE_ID", type="integer", unique=true, nullable=false)
     */
    protected $id;

    /**
     * @var PsaSite
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID", nullable=false)
     * })
     */
    protected $site;

    /**
     * @var PsaLanguage
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="PSA\MigrationBundle\Entity\Language\PsaLanguage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LANGUE_ID", referencedColumnName="LANGUE_ID")
     * })
     */
    protected $langue;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="PDV_SERVICE_CODE", type="string", length=255, nullable=false)
     */
    protected $serviceCode;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_SERVICE_LABEL", type="string", length=255, nullable=false)
     */
    protected $serviceLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_SERVICE_LABEL_PERSO", type="string", length=255, nullable=false)
     */
    protected $serviceLabelCustom;

    /**
     * @var string
     *
     * @ORM\Column(name="PDV_SERVICE_TYPE", type="string", length=255, nullable=false)
     */
    protected $serviceLabelType;

    /**
     * @var integer
     *
     * @ORM\Column(name="PDV_SERVICE_ORDER", type="integer", nullable=true)
     */
    protected $serviceOrder;

    /**
     * @var PsaMedia
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Media\PsaMedia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MEDIA_ID", referencedColumnName="MEDIA_ID", nullable=true)
     * })
     */
    protected $media;

    /**
     * @var integer
     *
     * @ORM\Column(name="PDV_SERVICE_ACTIF", type="integer", nullable=false, options={"default":0})
     */
    protected $serviceActive = 0;

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
     * @return PsaDealerService
     */
    public function setId($id)
    {
        $this->id = $id;

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
     * @return PsaDealerService
     */
    public function setSite(PsaSite $site)
    {
        $this->site = $site;

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
     * @return PsaDealerService
     */
    public function setLangue(PsaLanguage $langue)
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     * @return string
     */
    public function getServiceCode()
    {
        return $this->serviceCode;
    }

    /**
     * @param string $serviceCode
     *
     * @return PsaDealerService
     */
    public function setServiceCode($serviceCode)
    {
        $this->serviceCode = $serviceCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getServiceLabel()
    {
        return $this->serviceLabel;
    }

    /**
     * @param string $serviceLabel
     *
     * @return PsaDealerService
     */
    public function setServiceLabel($serviceLabel)
    {
        $this->serviceLabel = $serviceLabel;

        return $this;
    }

    /**
     * @return string
     */
    public function getServiceLabelCustom()
    {
        return $this->serviceLabelCustom;
    }

    /**
     * @param string $serviceLabelCustom
     *
     * @return PsaDealerService
     */
    public function setServiceLabelCustom($serviceLabelCustom)
    {
        $this->serviceLabelCustom = $serviceLabelCustom;

        return $this;
    }

    /**
     * @return string
     */
    public function getServiceLabelType()
    {
        return $this->serviceLabelType;
    }

    /**
     * @param string $serviceLabelType
     *
     * @return PsaDealerService
     */
    public function setServiceLabelType($serviceLabelType)
    {
        $this->serviceLabelType = $serviceLabelType;

        return $this;
    }

    /**
     * @return int
     */
    public function getServiceOrder()
    {
        return $this->serviceOrder;
    }

    /**
     * @param int $serviceOrder
     *
     * @return PsaDealerService
     */
    public function setServiceOrder($serviceOrder)
    {
        $this->serviceOrder = $serviceOrder;

        return $this;
    }

    /**
     * @return PsaMedia
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param PsaMedia $media
     *
     * @return PsaDealerService
     */
    public function setMedia(PsaMedia $media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return int
     */
    public function getServiceActive()
    {
        return $this->serviceActive;
    }

    /**
     * @param int $serviceActive
     *
     * @return PsaDealerService
     */
    public function setServiceActive($serviceActive)
    {
        $this->serviceActive = $serviceActive;

        return $this;
    }

}
