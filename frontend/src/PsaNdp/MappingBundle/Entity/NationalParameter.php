<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\NationalParameterRepository")
 * @ORM\Table(name="psa_site_national_param")})
 */

class NationalParameter {

    /**
     * @var string
     *
     * @ORM\Column(name="NATIONAL_PARAMS", type="string", nullable=true)
     */
    protected $parameters;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="SITE_ID", type="integer", nullable=false)
     */
    protected $siteId;

    /**
     * @var PsaSite
     * @ORM\OneToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     *
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID")
     * })
     */
    protected $site;

    /**
     * Get parameters
     *
     * ${THROWS_DOC}
     *
     * @return string
     */
    public function getParameters()
    {
        return $this->parameters;
    }

}
