<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;

/**
 * Class PsaModelSite
 *
 * @ORM\Table(name="psa_model_site")})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaModelSiteRepository")
 */
class PsaModelSite
{
    /**
     * @var PsaModel
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PsaNdp\MappingBundle\Entity\PsaModel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LCDV4", referencedColumnName="LCDV4", nullable=false)
     * })
     *
     */
    protected $model;

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
     *   @ORM\JoinColumn(name="LANGUE_ID", referencedColumnName="LANGUE_ID", nullable=false)
     * })
     */
    protected $language;

    /**
     * @var string
     * @ORM\Column(name="SLOGAN", type="string", length=255, nullable=true)
     *
     */
    protected $slogan;

    /**
     * @var string
     * @ORM\Column(name="FINISHING_ORDER", type="string", length=10, nullable=true)
     *
     */
    protected $finishingOrder;

    /**
     * @return mixed
     */
    public function getLcdv4()
    {
        return $this->getModel()->getLcdv4();
    }


    /**
     * @return PsaModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param PsaModel $model
     *
     * @return PsaModelSite
     */
    public function setModel(PsaModel $model)
    {
        $this->model = $model;

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
     * @return PsaModelSite
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
     * @return PsaModelSite
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlogan()
    {
        return $this->slogan;
    }

    /**
     * @param string $slogan
     *
     * @return PsaModelSite
     */
    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;

        return $this;
    }

    /**
     * @return string
     */
    public function getFinishingOrder()
    {
        return $this->finishingOrder;
    }

    /**
     * @param string $finishingOrder
     *
     * @return PsaModelSite
     */
    public function setFinishingOrder($finishingOrder)
    {
        $this->finishingOrder = $finishingOrder;

        return $this;
    }
}
