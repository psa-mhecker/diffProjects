<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * Class PsaApplicationConnectApps
 *
 * @ORM\Table(name="psa_application_connect_apps", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity
 */
class PsaApplicationConnectApps
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;
    
    /**
     * @var PsaLanguage
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Language\PsaLanguage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="LANGUE_ID", referencedColumnName="LANGUE_ID")
     * })
     */
    private $langue;

    /**
     * @var PsaSite
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID")
     * })
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="APPLICATION", type="string", length=30, nullable=false)
     */
    private $application;

    /**
     * @var string
     *
     * @ORM\Column(name="LABEL", type="string", length=50, nullable=false)
     */
    private $label;

    /**
     * @var integer
     *
     * @ORM\Column(name="MEDIA_ID", type="integer", length=11, nullable=false)
     */
    private $visual;

    /**
     * @var string
     *
     * @ORM\Column(name="INTRODUCTION", type="string", length=50, nullable=false)
     */
    private $introduction;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPTION", type="string", length=300, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="CARACTERISTIQUES", type="string", length=780, nullable=false)
     */
    private $caracteristiques;
    
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
     * @return PsaApplicationConnectApps
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
     * @return PsaApplicationConnectApps
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
     * @return PsaApplicationConnectApps
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
     * @return PsaApplicationConnectApps
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
    public function getApplication()
    {

        return $this->application;
    }

    /**
     *
     * @return int
     */
    public function getVisual()
    {

        return $this->visual;
    }

    /**
     *
     * @return string
     */
    public function getIntroduction()
    {

        return $this->introduction;
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
    public function getCaracteristiques()
    {
        
        return $this->caracteristiques;
    }

    /**
     *
     * @param string $application
     *
     * @return PsaApplicationConnectApps
     */
    public function setApplication($application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     *
     * @param int $visual
     *
     * @return PsaApplicationConnectApps
     */
    public function setVisual($visual)
    {
        $this->visual = $visual;

        return $this;
    }

    /**
     *
     * @param string $introduction
     *
     * @return PsaApplicationConnectApps
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     *
     * @param string $description
     *
     * @return PsaApplicationConnectApps
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     *
     * @param string $caracteristiques
     *
     * @return PsaApplicationConnectApps
     */
    public function setCaracteristiques($caracteristiques)
    {
        $this->caracteristiques = $caracteristiques;

        return $this;
    }
}
