<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Itkg\ConsumerBundle\Model\ServiceConfig as BaseServiceConfig;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * PsaSiteWebservice
 *
 * @ORM\Table(name="psa_site_webservice")
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaSiteWebserviceRepository")
 */
class PsaSiteWebservice extends BaseServiceConfig
{
    /**
     * @var PsaSite
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Site\PsaSite")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SITE_ID", referencedColumnName="SITE_ID", nullable=false)
     * })
     */
    protected $site;

    /**
     * @var PsaWebservice
     *
     * @ORM\ManyToOne(targetEntity="PsaNdp\MappingBundle\Entity\PsaWebservice")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ws_id", referencedColumnName="ws_id", nullable=false)
     * })
     */
    protected $clientConfig;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="service_key", type="string", nullable=false)
     */
    protected $serviceKey;

    /**
     * @var string
     *
     * @ORM\Column(name="response_type", type="string", nullable=false)
     */
    protected $responseType;

    /**
     * @var string
     *
     * @ORM\Column(name="response_format", nullable=false)
     */
    protected $responseFormat;

    /**
     * @var string
     *
     * @ORM\Column(name="cache_ttl", type="integer", nullable=true)
     */
    protected $cacheTtl;

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return !$this->status;
    }

    /**
     * @param bool $disabled
     *
     * @return $this
     */
    public function setDisabled($disabled)
    {
        $this->status = !$disabled;

        return $this;
    }
}
