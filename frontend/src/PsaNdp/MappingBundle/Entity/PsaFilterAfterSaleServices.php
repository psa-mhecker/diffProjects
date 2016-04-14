<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * PsaFilterAfterSaleServices
 *
 * @ORM\Table(name="psa_filter_after_sale_services", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaFilterAfterSaleServicesRepository")
 */
class PsaFilterAfterSaleServices
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var PsaLanguage
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Language\PsaLanguage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LANGUE_ID", referencedColumnName="LANGUE_ID")
     * })
     * @ORM\Id
     */
    private $language;

    /**
     * @var PsaSite
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID")
     * })
     * @ORM\Id
     */
    private $site;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="PsaNdp\MappingBundle\Entity\PsaAfterSaleServices", mappedBy="filters")
     */
    private $afterSaleServices;

    /**
     * @var string
     *
     * @ORM\Column(name="LABEL", type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="FILTER_ORDER", type="integer", nullable=false)
     */
    private $order;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->afterSaleServices = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set language
     *
     * @param PsaLanguage $language
     *
     * @return PsaFilterAfterSaleServices
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return PsaLanguage
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set site
     *
     * @param PsaSite $site
     *
     * @return PsaFilterAfterSaleServices
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return PsaSite
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return PsaFilterAfterSaleServices
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAfterSaleServices()
    {
        return $this->afterSaleServices;
    }

    /**
     * @param Collection $afterSaleServices
     */
    public function setAfterSaleServices($afterSaleServices)
    {
        $this->afterSaleServices = $afterSaleServices;
    }

    /**
     * @param PsaAfterSaleServices $afterSaleService
     */
    public function addAfterSaleService(PsaAfterSaleServices $afterSaleService)
    {
        $this->afterSaleServices->add($afterSaleService);
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }
}

