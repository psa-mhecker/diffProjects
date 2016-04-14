<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PsaNdp\MappingBundle\Entity\PsaFinishingColor;
use PsaNdp\MappingBundle\Entity\PsaFinishingBagde;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;

/**
 * Class PsaFinishingSite
 *
 * @ORM\Table(name="psa_finishing_site")
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaFinishingSiteRepository")
 */
class PsaFinishingSite
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
     * @var PsaSite
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID", nullable=false)
     * })
     */
    protected $site;

    /**
     * @var PsaLanguage
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Language\PsaLanguage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LANGUE_ID", referencedColumnName="LANGUE_ID", nullable=false)
     * })
     */
    protected $language;

    /**
     * @var string
     * @ORM\Column(name="CODE", type="string", length=8, nullable=false)
     *
     */
    protected $code;

    /**
     * @var string
     * @ORM\Column(name="FINITION", type="string", length=255, nullable=false)
     *
     */
    protected $finition;

    /**
     * @var PsaFinishingColor
     * @ORM\ManyToOne(targetEntity="PsaNdp\MappingBundle\Entity\PsaFinishingColor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="COLOR_ID", referencedColumnName="ID", nullable=true)
     * })
     */
    protected $color;

    /**
     * @var PsaFinishingBagde
     * @ORM\ManyToOne(targetEntity="PsaNdp\MappingBundle\Entity\PsaFinishingBagde")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="BADGE_ID", referencedColumnName="ID", nullable=true, onDelete="SET NULL")
     * })
     */
    protected $badge;

    /**
     * @var string
     * @ORM\Column(name="VERSIONS_CRITERION", type="string", length=255, nullable=true)
     *
     */
    protected $versionsCriterion;

    /**
     * @var string
     * @ORM\Column(name="CUSTOMER_TYPE", type="string", length=255, nullable=true)
     *
     */
    protected $customerType;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
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
     * @return PsaFinishingSite
     */
    public function setSite(PsaSite $site)
    {
        $this->site = $site;

        return $this;
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
     *
     * @return PsaFinishingSite
     */
    public function setLanguage(PsaLanguage $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return PsaFinishingSite
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getFinition()
    {
        return $this->finition;
    }

    /**
     * @param string $finition
     *
     * @return PsaFinishingSite
     */
    public function setFinition($finition)
    {
        $this->finition = $finition;

        return $this;
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
     * @return PsaFinishingSite
     */
    public function setColor(PsaFinishingColor $color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return PsaFinishingBagde
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * @param PsaFinishingBagde $badge
     *
     * @return PsaFinishingSite
     */
    public function setBadge(PsaFinishingBagde $badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersionsCriterion()
    {
        
        return $this->versionsCriterion;
    }

    /**
     * @return string
     */
    public function getCustomerType()
    {

        return $this->customerType;
    }

    /**
     * @param string $versionsCriterion
     *
     * @return PsaFinishingSite
     */
    public function setVersionsCriterion($versionsCriterion)
    {
        $this->versionsCriterion = $versionsCriterion;

        return $this;
    }

    /**
     * @param string $customerType
     *
     * @return PsaFinishingSite
     */
    public function setCustomerType($customerType)
    {
        $this->customerType = $customerType;

        return $this;
    }




}
