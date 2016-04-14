<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * Class PsaServiceConnectFinition
 *
 * @ORM\Table(name="psa_services_connect_finition", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity
 */
class PsaServiceConnectFinition
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var PsaLanguage
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Language\PsaLanguage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LANGUE_ID", referencedColumnName="LANGUE_ID")
     * })
     */
    private $langue;

    /**
     * @var PsaSite
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID")
     * })
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="MODELE", type="string", length=45, nullable=false)
     */
    private $modele;

    /**
     * @var boolean
     *
     * @ORM\Column(name="COMPATIBILITE", type="boolean", length=1, nullable=false)
     */
    private $compatibility;

    /**
     * @var string
     *
     * @ORM\Column(name="SERVICES_CONNECTES", type="string", length=255, nullable=true)
     */
    private $servicesConnect;

    /**
     * @var string
     *
     * @ORM\Column(name="MENTIONS_LEGALES", type="string", length=255, nullable=true)
     */
    private $legalNotice;

        /**
     * @var integer
     *
     * @ORM\Column(name="CTA_SERVICE",  type="integer", length=1, nullable=false)
     */
    protected $ctaService;

    /**
     * @var integer
     *
     * @ORM\Column(name="CTA_SERVICE_ID",  type="integer", length=11, nullable=true)
     */
    protected $ctaServiceId;

    /**
     * @var string
     *
     * @ORM\Column(name="CTA_SERVICE_ACTION",  type="string", length=255, nullable=true)
     */
    protected $ctaServiceAction;

    /**
     * @var string
     *
     * @ORM\Column(name="CTA_SERVICE_TITLE",  type="string", length=255, nullable=true)
     */
    protected $ctaServiceTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="CTA_SERVICE_TARGET",  type="string", length=255, nullable=true)
     */
    protected $ctaServiceTarget;

    /**
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     *
     * @param int $id
     *
     * @return PsaServiceConnectFinition
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
     * @return PsaServiceConnectFinition
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
     * @return PsaServiceConnectFinition
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
    public function getModele()
    {

        return $this->modele;
    }

    /**
     *
     * @param string $modele
     *
     * @return PsaServiceConnectFinition
     */
    public function setModele($modele)
    {

        $this->modele = $modele;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getCtaService()
    {

        return $this->ctaService;
    }

    /**
     *
     * @return integer
     */
    public function getCtaServiceId()
    {

        return $this->ctaServiceId;
    }

    /**
     *
     * @return string
     */
    public function getCtaServiceAction()
    {

        return $this->ctaServiceAction;
    }

    /**
     *
     * @return string
     */
    public function getCtaServiceTarget()
    {

        return $this->ctaServiceTarget;
    }

    /**
     *
     * @param integer $ctaService
     *
     * @return PsaServiceConnectFinition
     */
    public function setCtaService($ctaService)
    {
        $this->ctaService = $ctaService;

        return $this;
    }

    /**
     *
     * @param integer $ctaServiceId
     *
     * @return PsaServiceConnectFinition
     */
    public function setCtaServiceId($ctaServiceId)
    {
        $this->ctaServiceId = $ctaServiceId;

        return $this;
    }

    /**
     *
     * @param string $ctaServiceAction
     *
     * @return PsaServiceConnectFinition
     */
    public function setCtaServiceAction($ctaServiceAction)
    {
        $this->ctaServiceAction = $ctaServiceAction;

        return $this;
    }

    /**
     *
     * @param string $ctaServiceTarget
     *
     * @return PsaServiceConnectFinition
     */
    public function setCtaServiceTarget($ctaServiceTarget)
    {
        $this->ctaServiceTarget = $ctaServiceTarget;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getCompatibility()
    {

        return $this->compatibility;
    }

    /**
     *
     * @return string
     */
    public function getServicesConnect()
    {

        return $this->servicesConnect;
    }

    /**
     *
     * @return string
     */
    public function getLegalNotice()
    {

        return $this->legalNotice;
    }

    /**
     *
     * @return string
     */
    public function getCtaServiceTitle()
    {

        return $this->ctaServiceTitle;
    }

    /**
     *
     * @param boolean $ctaServiceTarget
     *
     * @return PsaServiceConnectFinition
     */
    public function setCompatibility($compatibility)
    {

        $this->compatibility = $compatibility;
    }

    /**
     *
     * @param string $servicesConnect
     *
     * @return PsaServiceConnectFinition
     */
    public function setServicesConnect($servicesConnect)
    {
        $this->servicesConnect = $servicesConnect;
    }

    /**
     *
     * @param string $legalNotice
     *
     * @return PsaServiceConnectFinition
     */
    public function setLegalNotice($legalNotice)
    {
        $this->legalNotice = $legalNotice;
    }

    /**
     *
     * @param string $ctaServiceTitle
     *
     * @return PsaServiceConnectFinition
     */
    public function setCtaServiceTitle($ctaServiceTitle)
    {
        $this->ctaServiceTitle = $ctaServiceTitle;
    }
}
