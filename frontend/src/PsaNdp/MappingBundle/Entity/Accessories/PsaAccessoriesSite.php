<?php

namespace PsaNdp\MappingBundle\Entity\Accessories;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;

/**
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\Accessories\PsaAccessoriesSiteRepository")
 * @ORM\Table(name="psa_accessoires_site")})
 */

class PsaAccessoriesSite {

    /**
     * @var PsaSite
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID", nullable=false)
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
     * @var integer
     *
     * @ORM\Column(name="MAX_ACCESSOIRES",  type="integer", length=11, nullable=false)
     */
    protected $maxAccessories;

    /**
     * @var integer
     *
     * @ORM\Column(name="MAX_ACCESSOIRES_UNIVERS",  type="integer", length=11, nullable=false)
     */
    protected $maxAccessoriesByUnivers;

    /**
     * @var boolean
     *
     * @ORM\Column(name="PRODUITS_DERIVES", type="boolean", nullable=false)
     */
    protected $linkDerivaties;

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
     * @ORM\Column(name="CTA_ERREUR_TITLE",  type="string", length=255, nullable=true)
     */
    protected $ctaErreurTitle;

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
     * @return PsaMedia
     */
    public function getMedia()
    {
        return $this->media;
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
     * @return PsaAppliMobile
     */
    public function setLangue(PsaLanguage $langue)
    {
        $this->langue = $langue;

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

    /**
     *
     * @return integer
     */
    public function getMaxAccessories()
    {

        return $this->maxAccessories;
    }

    /**
     *
     * @return integer
     */
    public function getMaxAccessoriesByUnivers()
    {

        return $this->maxAccessoriesByUnivers;
    }

    /**
     *
     * @return bool
     */
    public function getLinkDerivaties()
    {

        return $this->linkDerivaties;
    }

    /**
     *
     * @return string
     */
    public function getCtaErreurTitle()
    {

        return $this->ctaErreurTitle;
    }

    /**
     *
     * @param integer $maxAccessories
     *
     * @return PsaAccessoriesSite
     */
    public function setMaxAccessories($maxAccessories)
    {
        $this->maxAccessories = $maxAccessories;

        return $this;
    }

    /**
     *
     * @param integer $maxAccessoriesByUnivers
     *
     * @return PsaAccessoriesSite
     */
    public function setMaxAccessoriesByUnivers($maxAccessoriesByUnivers)
    {
        $this->maxAccessoriesByUnivers = $maxAccessoriesByUnivers;

        return $this;
    }

    /**
     *
     * @param bool $linkDerivaties
     *
     * @return PsaAccessoriesSite
     */
    public function setLinkDerivaties($linkDerivaties)
    {
        $this->linkDerivaties = $linkDerivaties;

        return $this;
    }

    /**
     *
     * @param string $ctaErreurTitle
     *
     * @return PsaAccessoriesSite
     */
    public function setCtaErreurTitle($ctaErreurTitle)
    {
        $this->ctaErreurTitle = $ctaErreurTitle;

        return $this;
    }


}
