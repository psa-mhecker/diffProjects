<?php
namespace PsaNdp\MappingBundle\Object\Block\Pf11;

use JMS\Serializer\SerializerInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PsaNdp\MappingBundle\Object\BlockTrait\AgentPointOfSaleSearchBasicConfigurationTrait;
use PsaNdp\MappingBundle\Object\Content;


/**
 * Class Pf11RecherchePointDeVente
 * @package PsaNdp\MappingBundle\Object\Block
 */
class Pf11RecherchePointDeVente extends Content
{
    protected $mapping = array(
        'datalayer' => 'dataLayer'
    );

    use AgentPointOfSaleSearchBasicConfigurationTrait;

    /**
     * @var Pf11RecherchePointDeVenteCas
     */
    protected $cas1;

    /**
     * @var Pf11RecherchePointDeVenteCas
     */
    protected $cas2;

    /**
     * @var string
     */
    protected $urlRedirection;

    /**
     * @var string
     */
    protected $dealorInitialList;

    /**
     * @var MapOptions
     */
    protected  $mapOptions;

    /**
     * @var string
     */
    protected  $mediaServer;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var PsaSite
     */
    protected $site;

    /**
    * @return PsaPageZoneConfigurableInterface
    */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param MapOptions          $mapOptions
     * @param SerializerInterface $serializer
     */
    public function __construct(MapOptions $mapOptions, SerializerInterface $serializer)
    {
        $this->mapOptions = $mapOptions;
        $this->serializer = $serializer;
    }



    /**
     * @param PsaPageZoneConfigurableInterface $block
     *
     * @return $this
    */
    public function setBlock(PsaPageZoneConfigurableInterface $block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * @return Pf11RecherchePointDeVenteCas
     */
    public function getCas1()
    {
        return $this->cas1;
    }

    /**
     * @param Pf11RecherchePointDeVenteCas $cas1
     *
     * @return Pf11RecherchePointDeVente
     */
    public function setCas1($cas1)
    {
        $this->cas1 = $cas1;

        return $this;
    }

    /**
     * @return Pf11RecherchePointDeVenteCas
     */
    public function getCas2()
    {
        return $this->cas2;
    }

    /**
     * @param Pf11RecherchePointDeVenteCas $cas2
     *
     * @return Pf11RecherchePointDeVente
     */
    public function setCas2($cas2)
    {
        $this->cas2 = $cas2;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlRedirection()
    {
        return $this->urlRedirection;
    }

    /**
     * @param string $urlRedirection
     *
     * @return Pf11RecherchePointDeVente
     */
    public function setUrlRedirection($urlRedirection)
    {
        $this->urlRedirection = $urlRedirection;

        return $this;
    }

    /**
     * @return string
     */
    public function getDealorInitialList()
    {
        return $this->dealorInitialList;
    }

    /**
     * @param string $DealorInitialList
     *
     * @return Pf11RecherchePointDeVente
     */
    public function setDealorInitialList($dealorInitialList)
    {
        $this->dealorInitialList = $dealorInitialList;

        return $this;
    }

    /**
     *
     */
    public function getMaxResults()
    {
       return $this->getBlock()->getZoneTitre2();
    }

    /**
     * @return MapOptions
     */
    public function getMapOptions()
    {
        return $this->serializer->serialize($this->mapOptions,'json');
    }

    /**
     *  initialize map options
     */
    public function initMapOptions()
    {
        $this->mapOptions->setMaxResults($this->getBlock()->getZoneTitre2())
                            ->setMode($this->getBlock()->getZoneCriteriaId())
                            ->setRadius($this->getBlock()->getZoneTitre3())
                            ->setAutocomplete($this->getBlock()->getZoneAttribut2())
                            ->setMarkerClustering($this->getBlock()->getZoneAttribut())
                            ->setMaxDvn($this->getBlock()->getZoneTitre5())
                            ->setMediaServer($this->mediaServer)
                            ->setGoogleChannel(sprintf('%s_KPP_DL_R', $this->getSite()->getCountryCode()))
                            ->setGoogleMapClientId($this->getSite()->getClientId())
                            ->setCountryCode($this->getSite()->getCountryCode())
                            ;
    }

    /**
     * Get mediaServer
     *
     * @return string
     */
    public function getMediaServer()
    {
        return $this->mediaServer;
    }

    /**
     * @param string $mediaServer
     *
     * @return Pf11RecherchePointDeVente
     */
    public function setMediaServer($mediaServer)
    {
        $this->mediaServer = $mediaServer;

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
     */
    public function setSite($site)
    {
        $this->site = $site;
    }
}
