<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * Class PsaConnectServices
 *
 * @ORM\Table(name="psa_services_connect", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaConnectServicesRepository")
 */
class PsaConnectServices
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
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite" )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID")
     * })
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="LABEL", type="string", length=45, nullable=false)
     */
    private $label;
    
    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPTION", type="string", length=100, nullable=false)
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="URL", type="string", length=255, nullable=true)
     */
    private $url;
    
    /**
     * @var string
     *
     * @ORM\Column(name="BENEFICES", type="string", length=255, nullable=false)
     */
    private $benefices;
    
    /**
     * @var string
     *
     * @ORM\Column(name="MENTIONS_LEGALES", type="string", length=50, nullable=true)
     */
    private $legalNotice;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="VISUEL_APPLICATION", type="integer", length=11, nullable=true)
     */
    private $applicationVisual;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="VISUEL_SELECTEUR", type="integer", length=11, nullable=true)
     */
    private $selectorVisual;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="PRIX", type="integer", length=11, nullable=false)
     */
    private $price;
    
    /**
     * @var string
     *
     * @ORM\Column(name="A_PARTIR_DE", type="string", length=30, nullable=false)
     */
    private $from;
    
    /**
     * @var string
     *
     * @ORM\Column(name="AUTRE", type="string", length=30, nullable=false)
     */
    private $other;
    
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
     * @return PsaConnectServices
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
     * @return PsaConnectServices
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
     * @return PsaConnectServices
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
     * @return PsaConnectServices
     */
    public function setLabel($label)
    {
        $this->label = $label;
        
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getDescription()
    {
        
        return $this->description;
    }
    
    /**
     * 
     * @return string
     */
    public function getUrl()
    {
        
        return $this->url;
    }
    
    /**
     * 
     * @return string
     */
    public function getBenefices()
    {
        
        return $this->benefices;
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
     * @return int
     */
    public function getApplicationVisual()
    {
        
        return $this->applicationVisual;
    }
    
    /**
     * 
     * @return int
     */
    public function getSelectorVisual()
    {
        
        return $this->selectorVisual;
    }
    
    /**
     * 
     * @return int
     */
    public function getPrice()
    {
        
        return $this->price;
    }
    
    /**
     * 
     * @return string
     */
    public function getFrom()
    {
        
        return $this->from;
    }
    
    /**
     * 
     * @return string
     */
    public function getOther()
    {
        
        return $this->other;
    }
    
    /**
     * 
     * @param string $description
     * 
     * @return PsaConnectServices
     */
    public function setDescription($description)
    {
        $this->description = $description;
        
        return $this;
    }
    
    /**
     * 
     * @param string $url
     * 
     * @return PsaConnectServices
     */
    public function setUrl($url)
    {
        $this->url = $url;
        
        return $this;
    }
    
    /**
     * 
     * @param string $benefices
     * 
     * @return PsaConnectServices
     */
    public function setBenefices($benefices)
    {
        $this->benefices = $benefices;
        
        return $this;
    }
    
    /**
     * 
     * @param string $legalNotice
     * 
     * @return PsaConnectServices
     */
    public function setLegalNotice($legalNotice)
    {
        $this->legalNotice = $legalNotice;
        
        return $this;
    }
    
    /**
     * 
     * @param int $applicationVisual
     * 
     * @return PsaConnectServices
     */
    public function setApplicationVisual($applicationVisual)
    {
        $this->applicationVisual = $applicationVisual;
        
        return $this;
    }
    
    /**
     * 
     * @param int $selectorVisual
     * 
     * @return PsaConnectServices
     */
    public function setSelectorVisual($selectorVisual)
    {
        $this->selectorVisual = $selectorVisual;
    }
    
    /**
     * 
     * @param int $price
     * 
     * @return PsaConnectServices
     */
    public function setPrice($price)
    {
        $this->price = $price;
        
        return $this;
    }
    
    /**
     * 
     * @param string $from
     * 
     * @return PsaConnectServices
     */
    public function setFrom($from)
    {
        $this->from = $from;
        
        return $this;
    }
    
    /**
     * 
     * @param string $other
     * 
     * @return PsaConnectServices
     */
    public function setOther($other)
    {
        $this->other = $other;
        
        return $this;
    }
}
