<?php

namespace PsaNdp\MappingBundle\Entity\Vehicle;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * Class PsaColorTypeSite
 *
 * @ORM\Table(name="psa_type_couleur_site", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\Vehicle\PsaColorTypeSiteRepository")
 */
class PsaColorTypeSite
{
    /**
     * @var PsaColorType
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PsaNdp\MappingBundle\Entity\Vehicle\PsaColorType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID", referencedColumnName="ID", onDelete="CASCADE")
     * })
     */
    protected $colorType;

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
     * @var PsaSite
     * @ORM\Id
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
     */
    protected $labelLocal;

    /**
     * @var integer
     *
     * @ORM\Column(name="ORDER_TYPE", type="integer", nullable=true, options={"default":1})
     */
    protected $orderType;

    /**
     * @return PsaColorType
     */
    public function getColorType()
    {
        return $this->colorType;
    }

    /**
     * @param PsaColorType $colorType
     */
    public function setColorType($colorType)
    {
        $this->colorType = $colorType;
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
     * @return PsaColorTypesSite
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
     * @return PsaColorTypesSite
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
     * @return PsaColorTypesSite
     */
    public function setLabelLocal($labelLocal)
    {
        $this->labelLocal = $labelLocal;

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
     * @return PsaColorTypesSite
     */
    public function setOrderType($orderType)
    {
        $this->orderType = $orderType;

        return $this;
    }
}
