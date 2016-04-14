<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * Class PsaBenefice
 *
 * @ORM\Table(name="psa_benefice", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity
 */
class PsaBenefice
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
     * @ORM\Column(name="LABEL", type="string", length=45, nullable=false)
     */
    private $label;
    
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
     * @return PsaBenefice
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
     * @return PsaBenefice
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
     * @return PsaBenefice
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
     * @return PsaBenefice
     */
    public function setLabel($label)
    {
        
        $this->label = $label;
        
        return $this;
    }
}
