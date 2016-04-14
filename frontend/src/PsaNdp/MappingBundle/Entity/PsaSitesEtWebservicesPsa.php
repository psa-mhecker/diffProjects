<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;

/**
 * Class PsaSitesEtWebservicesPsa
 *
 * @ORM\Table(name="psa_sites_et_webservices_psa", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaSitesEtWebservicesPsaRepository")
 */
class PsaSitesEtWebservicesPsa
{

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
     * @ORM\Column(name="SITE_DOMAIN_NAME", type="string", length=255, nullable=false)
     */
    private $siteDomainName;

    /**
     * @var integer
     *
     * @ORM\Column(name="ZONE_VP", type="integer", length=1, nullable=false)
     */
    private $zoneVp;

    /**
     * @var integer
     *
     * @ORM\Column(name="ZONE_VP_POPIN", type="integer", length=1, nullable=true)
     */
    private $zoneVpPopin;

    /**
     * @var integer
     *
     * @ORM\Column(name="ZONE_VP_POPIN_CONFIRM", type="integer", length=1, nullable=true)
     */
    private $zoneVpPopinConfirm;

    /**
     * @var integer
     *
     * @ORM\Column(name="ZONE_SHOWROOM", type="integer", length=1, nullable=false)
     */
    private $zoneBoutiqueShowroom;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="ZONE_MY_PEUGEOT", type="integer", length=1, nullable=false)
     */
    private $zoneMyPeugeot;

    /**
     * @var integer
     *
     * @ORM\Column(name="ZONE_WEBSTORE", type="integer", length=1, nullable=false)
     */
    private $zoneWebstore;

    /**
     * @var integer
     *
     * @ORM\Column(name="ZONE_WEBSTORE_POPIN", type="integer", length=1, nullable=true)
     */
    private $zoneWebstorePopin;

    /**
     * @var integer
     *
     * @ORM\Column(name="ZONE_WEBSTORE_POPIN_CONFIRM", type="integer", length=1, nullable=true)
     */
    private $zoneWebstorePopinConfirm;

    /**
     * @var integer
     *
     * @ORM\Column(name="ZONE_PARCOURS_WEBSTORE", type="integer", length=1, nullable=true)
     */
    private $zoneParcoursWebstore;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_WEB_VP", type="string", length=255, nullable=true)
     */
    private $zoneVpUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_WEB_FICHE_ACCESSOIRES", type="string", length=255, nullable=true)
     */
    private $zoneShowroomUrlWebFicheAccessoires;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_MOB_FICHE_ACCESSOIRES", type="string", length=255, nullable=true)
     */
    private $zoneShowroomUrlMobileFicheAccessoires;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_WEB_ACCUEIL", type="string", length=255, nullable=true)
     */
    private $zoneUrlWebAccueil;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_WEB_CONNEXION", type="string", length=255, nullable=true)
     */
    private $zoneUrlWebConnexion;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_MOB_ACCUEIL", type="string", length=255, nullable=true)
     */
    private $zoneUrlMobileAccueil;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_MOB_CONNEXION", type="string", length=255, nullable=true)
     */
    private $zoneUrlMobileConnexion;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_WEB_MOB_WEBSTORE", type="string", length=255, nullable=true)
     */
    private $zoneUrlWebMobileWebstore;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_WEB_MOB_WEBSTORE_PRODUITS", type="string", length=255, nullable=true)
     */
    private $zoneUrlWebMobileWebstoreProduits;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_PEUGEOT_SERVICE", type="string", length=255, nullable=true)
     */
    private $zoneUrlPeugeotService;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_PEUGEOT_ENVIRONNEMENT", type="string", length=255, nullable=true)
     */
    private $zoneUrlPeugeotEnvironnement;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_PEUGEOT_PRO", type="string", length=255, nullable=true)
     */
    private $zoneUrlPeugeotPro;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_PEUGEOT_WEBSTORE_PRO", type="string", length=255, nullable=true)
     */
    private $zoneUrlPeugeotWebstorePro;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_PRODUIT_DERIVES", type="string", length=255, nullable=true)
     */
    private $zoneUrlProduitDerives;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_PEUGEOT_SCOOTER", type="string", length=255, nullable=true)
     */
    private $zoneUrlPeugeotScooter;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_PEUGEOT_CYCLES", type="string", length=255, nullable=true)
     */
    private $zoneUrlPeugeotCycle;

    /**
     * @var string
     *
     * @ORM\Column(name="ZONE_URL_MU_BY_PEUGEOT", type="string", length=255, nullable=true)
     */
    private $zoneUrlMuByPeugeot;

    /**
     * @var string
     *
     * @ORM\Column(name="SITE_RANGE_MANAGER", type="string", length=255, nullable=false)
     */
    private $rangeManager;

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
     * @return PsaSitesEtWebservicesPsa
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
     * @return PsaSitesEtWebservicesPsa
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
     * @return PsaSitesEtWebservicesPsa
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
    public function getSiteDomainName()
    {
        
        return $this->siteDomainName;
    }
    
    /**
     * 
     * @param string $siteDomainName
     * 
     * @return PsaSitesEtWebservicesPsa
     */
    public function setSiteDomainName($siteDomainName)
    {
        
        $this->siteDomainName = $siteDomainName;
        
        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getZoneVp()
    {

        return $this->zoneVp;
    }

    /**
     *
     * @return integer
     */
    public function getZoneVpPopin()
    {

        return $this->zoneVpPopin;
    }

    /**
     *
     * @return integer
     */
    public function getZoneVpPopinConfirm()
    {

        return $this->zoneVpPopinConfirm;
    }

    /**
     *
     * @return integer
     */
    public function getZoneBoutiqueShowroom()
    {

        return $this->zoneBoutiqueShowroom;
    }

    /**
     *
     * @return integer
     */
    public function getZoneMyPeugeot()
    {

        return $this->zoneMyPeugeot;
    }

    /**
     *
     * @return integer
     */
    public function getZoneWebstore()
    {

        return $this->zoneWebstore;
    }

    /**
     *
     * @return integer
     */
    public function getZoneWebstorePopin()
    {

        return $this->zoneWebstorePopin;
    }

    /**
     *
     * @return integer
     */
    public function getZoneWebstorePopinConfirm()
    {

        return $this->zoneWebstorePopinConfirm;
    }

    /**
     *
     * @return integer
     */
    public function getZoneParcoursWebstore()
    {

        return $this->zoneParcoursWebstore;
    }

    public function getZoneVpUrl()
    {

        return $this->zoneVpUrl;
    }

    /**
     *
     * @return string
     */
    public function getZoneShowroomUrlWebFicheAccessoires()
    {

        return $this->zoneShowroomUrlWebFicheAccessoires;
    }

    /**
     *
     * @return string
     */
    public function getZoneShowroomUrlMobileFicheAccessoires()
    {

        return $this->zoneShowroomUrlMobileFicheAccessoires;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlWebAccueil()
    {

        return $this->zoneUrlWebAccueil;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlWebConnexion()
    {

        return $this->zoneUrlWebConnexion;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlMobileAccueil()
    {

        return $this->zoneUrlMobileAccueil;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlMobileConnexion()
    {

        return $this->zoneUrlMobileConnexion;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlWebMobileWebstore()
    {

        return $this->zoneUrlWebMobileWebstore;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlWebMobileWebstoreProduits()
    {

        return $this->zoneUrlWebMobileWebstoreProduits;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlPeugeotService()
    {

        return $this->zoneUrlPeugeotService;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlPeugeotEnvironnement()
    {

        return $this->zoneUrlPeugeotEnvironnement;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlPeugeotPro()
    {

        return $this->zoneUrlPeugeotPro;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlPeugeotWebstorePro()
    {

        return $this->zoneUrlPeugeotWebstorePro;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlProduitDerives()
    {

        return $this->zoneUrlProduitDerives;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlPeugeotScooter()
    {

        return $this->zoneUrlPeugeotScooter;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlPeugeotCycle()
    {

        return $this->zoneUrlPeugeotCycle;
    }

    /**
     *
     * @return string
     */
    public function getZoneUrlMuByPeugeot()
    {

        return $this->zoneUrlMuByPeugeot;
    }

    /**
     *
     * @param integer $zoneVp
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneVp($zoneVp)
    {
        $this->zoneVp = $zoneVp;

        return $this;
    }

    /**
     *
     * @param integer $zoneVpPopin
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneVpPopin($zoneVpPopin)
    {
        $this->zoneVpPopin = $zoneVpPopin;

        return $this;
    }

    /**
     *
     * @param integer $zoneVpPopinConfirm
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneVpPopinConfirm($zoneVpPopinConfirm)
    {
        $this->zoneVpPopinConfirm = $zoneVpPopinConfirm;

        return $this;
    }

    /**
     *
     * @param integer $zoneBoutiqueShowroom
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneBoutiqueShowroom($zoneBoutiqueShowroom)
    {
        $this->zoneBoutiqueShowroom = $zoneBoutiqueShowroom;

        return $this;
    }

    /**
     *
     * @param integer $zoneMyPeugeot
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneMyPeugeot($zoneMyPeugeot)
    {
        $this->zoneMyPeugeot = $zoneMyPeugeot;

        return $this;
    }

    /**
     *
     * @param integer $zoneWebstore
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneWebstore($zoneWebstore)
    {
        $this->zoneWebstore = $zoneWebstore;

        return $this;
    }

    /**
     *
     * @param integer $zoneWebstorePopin
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneWebstorePopin($zoneWebstorePopin)
    {
        $this->zoneWebstorePopin = $zoneWebstorePopin;

        return $this;
    }

    /**
     *
     * @param integer $zoneWebstorePopinConfirm
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneWebstorePopinConfirm($zoneWebstorePopinConfirm)
    {
        $this->zoneWebstorePopinConfirm = $zoneWebstorePopinConfirm;

        return $this;
    }

    /**
     *
     * @param integer $zoneParcoursWebstore
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneParcoursWebstore($zoneParcoursWebstore)
    {
        $this->zoneParcoursWebstore = $zoneParcoursWebstore;

        return $this;
    }

    /**
     *
     * @param string $zoneVpUrl
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneVpUrl($zoneVpUrl)
    {
        $this->zoneVpUrl = $zoneVpUrl;

        return $this;
    }

    /**
     *
     * @param string $zoneShowroomUrlWebFicheAccessoires
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneShowroomUrlWebFicheAccessoires($zoneShowroomUrlWebFicheAccessoires)
    {
        $this->zoneShowroomUrlWebFicheAccessoires = $zoneShowroomUrlWebFicheAccessoires;

        return $this;
    }

    /**
     *
     * @param string $zoneShowroomUrlMobileFicheAccessoires
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneShowroomUrlMobileFicheAccessoires($zoneShowroomUrlMobileFicheAccessoires)
    {
        $this->zoneShowroomUrlMobileFicheAccessoires = $zoneShowroomUrlMobileFicheAccessoires;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlWebAccueil
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlWebAccueil($zoneUrlWebAccueil)
    {
        $this->zoneUrlWebAccueil = $zoneUrlWebAccueil;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlWebConnexion
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlWebConnexion($zoneUrlWebConnexion)
    {
        $this->zoneUrlWebConnexion = $zoneUrlWebConnexion;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlMobileAccueil
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlMobileAccueil($zoneUrlMobileAccueil)
    {
        $this->zoneUrlMobileAccueil = $zoneUrlMobileAccueil;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlMobileConnexion
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlMobileConnexion($zoneUrlMobileConnexion)
    {
        $this->zoneUrlMobileConnexion = $zoneUrlMobileConnexion;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlWebMobileWebstore
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlWebMobileWebstore($zoneUrlWebMobileWebstore)
    {
        $this->zoneUrlWebMobileWebstore = $zoneUrlWebMobileWebstore;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlWebMobileWebstoreProduits
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlWebMobileWebstoreProduits($zoneUrlWebMobileWebstoreProduits)
    {
        $this->zoneUrlWebMobileWebstoreProduits = $zoneUrlWebMobileWebstoreProduits;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlPeugeotService
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlPeugeotService($zoneUrlPeugeotService)
    {
        $this->zoneUrlPeugeotService = $zoneUrlPeugeotService;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlPeugeotEnvironnement
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlPeugeotEnvironnement($zoneUrlPeugeotEnvironnement)
    {
        $this->zoneUrlPeugeotEnvironnement = $zoneUrlPeugeotEnvironnement;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlPeugeotPro
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlPeugeotPro($zoneUrlPeugeotPro)
    {
        $this->zoneUrlPeugeotPro = $zoneUrlPeugeotPro;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlPeugeotWebstorePro
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlPeugeotWebstorePro($zoneUrlPeugeotWebstorePro)
    {
        $this->zoneUrlPeugeotWebstorePro = $zoneUrlPeugeotWebstorePro;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlProduitDerives
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlProduitDerives($zoneUrlProduitDerives)
    {
        $this->zoneUrlProduitDerives = $zoneUrlProduitDerives;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlPeugeotScooter
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlPeugeotScooter($zoneUrlPeugeotScooter)
    {
        $this->zoneUrlPeugeotScooter = $zoneUrlPeugeotScooter;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlPeugeotCycle
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlPeugeotCycle($zoneUrlPeugeotCycle)
    {
        $this->zoneUrlPeugeotCycle = $zoneUrlPeugeotCycle;

        return $this;
    }

    /**
     *
     * @param string $zoneUrlMuByPeugeot
     *
     * @return PsaSitesEtWebservicesPsa
     */
    public function setZoneUrlMuByPeugeot($zoneUrlMuByPeugeot)
    {
        $this->zoneUrlMuByPeugeot = $zoneUrlMuByPeugeot;

        return $this;
    }
}
