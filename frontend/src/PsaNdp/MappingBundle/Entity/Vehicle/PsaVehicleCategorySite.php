<?php

namespace PsaNdp\MappingBundle\Entity\Vehicle;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * Class PsaVehicleCategorySite
 *
 * @ORM\Table(name="psa_vehicle_category_site", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\Vehicle\PsaVehicleCategorySiteRepository")
 */
class PsaVehicleCategorySite
{
    /**
     * @var PsaVehicleCategory
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PsaNdp\MappingBundle\Entity\Vehicle\PsaVehicleCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID", referencedColumnName="ID", onDelete="CASCADE")
     * })
     */
    private $category;

    /**
     * @var PsaLanguage
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Language\PsaLanguage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LANGUE_ID", referencedColumnName="LANGUE_ID", onDelete="CASCADE")
     * })
     */
    private $langue;

    /**
     * @var PsaSite
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID", onDelete="CASCADE")
     * })
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="LABEL", type="string", length=255, nullable=false)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="CRITERES_MARKETING", type="string", length=255, nullable=false)
     */
    private $marketingCriteria;

    /**
     * @var integer
     *
     * @ORM\Column(name="CATEGORY_ORDER", type="integer", nullable=true, options={"default":1})
     */
    private $categoryOrder;

    /**
     * @return PsaVehicleCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param PsaVehicleCategory $category
     */
    public function setCategory(PsaVehicleCategory $category)
    {
        $this->category = $category;

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
     * @return PsaVehicleCategorySite
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
     * @return PsaVehicleCategorySite
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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     *
     * @param string $label
     *
     * @return PsaVehicleCategorySite
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getMarketingCriteria()
    {

        return explode('#',$this->marketingCriteria);
    }

    /**
     * @param $marketingCriteria
     *
     * @return $this
     */
    public function setMarketingCriteria($marketingCriteria)
    {
        $this->marketingCriteria = $marketingCriteria;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getCategoryOrder()
    {
        return $this->categoryOrder;
    }

    /**
     *
     * @param int $categoryOrder
     *
     * @return PsaVehicleCategorySite
     */
    public function setCategoryOrder($categoryOrder)
    {
        $this->categoryOrder = $categoryOrder;

        return $this;
    }
}
