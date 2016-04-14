<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * Class PsaModelSilhouetteMonteeGamme
 *
 * @ORM\Table(name="psa_services_connect_finition_grouping", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaServiceConnectFinitionGroupingRepository")
 */
class PsaServiceConnectFinitionGrouping
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="LCDV4", type="string", length=4, nullable=false)
     */
    protected $lcdv4;

    /**
     * @var PsaServiceConnectFinition
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PsaNdp\MappingBundle\Entity\PsaServiceConnectFinition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CONNECT_FINITION_ID", referencedColumnName="ID"),
     * })
     */
    protected $connectfinition;

    /**
     * @var PsaConnectServices
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PsaNdp\MappingBundle\Entity\PsaConnectServices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CONNECTED_SERVICE_ID", referencedColumnName="ID"),
     * })
     */
    protected $connectedService;

    /**
     * @var integer
     *
     * @ORM\Column(name="OPTIONS", type="integer", length=1, nullable=true)
     */
    protected $options;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="FINITION_GROUPING_ID", type="string", length=255, nullable=false)
     */
    protected $finitionGrouping;

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
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param integer $id
     *
     * @return PsaServiceConnectFinitionGrouping
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getOptions()
    {

        return $this->options;
    }

    /**
     *
     * @param integer $options
     *
     * @return PsaServiceConnectFinitionGrouping
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

        /**
     *
     * @return string
     */
    public function getFinitionGrouping()
    {

        return $this->finitionGrouping;
    }

    /**
     *
     * @param string $finitionGrouping
     *
     * @return PsaServiceConnectFinitionGrouping
     */
    public function setFinitionGrouping($finitionGrouping)
    {
        $this->finitionGrouping = $finitionGrouping;

        return $this;
    }

    /**
     *
     * @return PsaServiceConnectFinition
     */
    public function getConnectfinition()
    {

        return $this->connectfinition;
    }

    /**
     *
     * @param PsaServiceConnectFinition $connectfinition
     *
     * @return PsaServiceConnectFinitionGrouping
     */
    public function setConnectfinition(PsaServiceConnectFinition $connectfinition)
    {
        $this->connectfinition = $connectfinition;

        return $this;
    }

    /**
     *
     * @return PsaConnectServices
     */
    public function getConnectedService()
    {

        return $this->connectedService;
    }

    /**
     *
     * @param PsaConnectServices $connectedService
     *
     * @return PsaServiceConnectFinitionGrouping
     */
    public function setConnectedService($connectedService)
    {
        $this->connectedService = $connectedService;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLcdv4()
    {

        return $this->lcdv4;
    }

    /**
     *
     * @param string $lcdv4
     *
     * @return PsaServiceConnectFinitionGrouping
     */
    public function setLcdv4($lcdv4)
    {
        $this->lcdv4 = $lcdv4;

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
     * @return PsaServiceConnectFinitionGrouping
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
     * @return PsaServiceConnectFinitionGrouping
     */
    public function setSite(PsaSite $site)
    {
        $this->site = $site;

        return $this;
    }

}
