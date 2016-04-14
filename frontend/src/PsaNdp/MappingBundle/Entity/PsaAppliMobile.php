<?php

namespace PsaNdp\MappingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Media\PsaMedia;

/**
 * Class PsaAppliMobile
 *
 * @ORM\Table(name="psa_appli_mobile", options={"collate"="utf8_swedish_ci"})
 * @ORM\Entity(repositoryClass="PsaNdp\MappingBundle\Repository\PsaAppliMobileRepository")
 */
class PsaAppliMobile
{
    /**
     * @var integer
     *
     * @ORM\Column(name="APPMOBILE_ID", type="integer", nullable=false)
     * @ORM\Id
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
     * @ORM\Column(name="APPMOBILE_LABEL_BO", type="string", length=255, nullable=false)
     */
    private $labelBo;

    /**
     * @var string
     *
     * @ORM\Column(name="APPMOBILE_LABEL", type="string", length=255, nullable=false)
     */
    private $label;

    /**
     * @var PsaMedia
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Media\PsaMedia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MEDIA_ID", referencedColumnName="MEDIA_ID", nullable=true)
     * })
     */
    private $media;

    /**
     * @var string
     *
     * @ORM\Column(name="APPMOBILE_URL_VISUEL", type="string", length=255, nullable=true)
     */
    private $urlVisuel;

    /**
     * @var string
     *
     * @ORM\Column(name="APPMOBILE_MODE_OUVERTURE", type="string", length=255, nullable=false)
     */
    private $modeOuverture;

    /**
     * @var string
     *
     * @ORM\Column(name="APPMOBILE_URL_GOOGLEPLAY", type="string", length=255, nullable=true)
     */
    private $urlGooglePlay;

    /**
     * @var PsaMedia
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Media\PsaMedia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MEDIA_GOOGLEPLAY", referencedColumnName="MEDIA_ID", nullable=true)
     * })
     */
    private $mediaGooglePlay;

    /**
     * @var string
     *
     * @ORM\Column(name="APPMOBILE_URL_APPLESTORE", type="string", length=255, nullable=true)
     */
    private $urlAppleStore;

    /**
     * @var PsaMedia
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Media\PsaMedia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MEDIA_APPLESTORE", referencedColumnName="MEDIA_ID", nullable=true)
     * })
     */
    private $mediaAppleStore;

    /**
     * @var string
     *
     * @ORM\Column(name="APPMOBILE_URL_WINDOWS", type="string", length=255, nullable=true)
     */
    private $urlWindows;

    /**
     * @var PsaMedia
     *
     * @ORM\ManyToOne(targetEntity="PSA\MigrationBundle\Entity\Media\PsaMedia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MEDIA_WINDOWS", referencedColumnName="MEDIA_ID", nullable=true)
     * })
     */
    private $mediaWindows;


    /**
     * @var string
     *
     * @ORM\Column(name="APPMOBILE_TEXTE", type="string", length=255, nullable=true)
     */
    private $description;


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
     * @return PsaAppliMobile
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
     * @return PsaAppliMobile
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
     * @return PsaAppliMobile
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
     * @param string $labelBo
     *
     * @return PsaAppliMobile
     */
    public function setLabelBo($labelBo)
    {

        $this->label = $labelBo;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLabelBo()
    {

        return $this->labelBo;
    }

    /**
     *
     * @param string $label
     *
     * @return PsaAppliMobile
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
    public function getUrlVisuel()
    {

        return $this->urlVisuel;
    }

    /**
     *
     * @param string $urlVisuel
     *
     * @return PsaAppliMobile
     */
    public function setUrlVisuel($urlVisuel)
    {

        $this->urlVisuel = $urlVisuel;

        return $this;
    }
    


    /**
     *
     * @return string
     */
    public function getModeOuverture()
    {

        return $this->modeOuverture;
    }

    /**
     *
     * @return string
     */
    public function getUrlGooglePlay()
    {

        return $this->urlGooglePlay;
    }

    /**
     *
     * @return string
     */
    public function getUrlAppleStore()
    {

        return $this->urlAppleStore;
    }

    /**
     *
     * @return string
     */
    public function getUrlWindows()
    {
        return $this->urlWindows;
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
     * @param string $modeOuverture
     *
     * @return PsaAppliMobile
     */
    public function setModeOuverture($modeOuverture)
    {
        $this->modeOuverture = $modeOuverture;

        return $this;
    }

    /**
     *
     * @param string $urlGooglePlay
     *
     * @return PsaAppliMobile
     */
    public function setUrlGooglePlay($urlGooglePlay)
    {
        $this->urlGooglePlay = $urlGooglePlay;

        return $this;
    }

    /**
     *
     * @param string $urlAppleStore
     *
     * @return PsaAppliMobile
     */
    public function setUrlAppleStore($urlAppleStore)
    {
        $this->urlAppleStore = $urlAppleStore;

        return $this;
    }

    /**
     *
     * @param string $urlWindows
     *
     * @return PsaAppliMobile
     */
    public function setUrlWindows($urlWindows)
    {
        $this->urlWindows = $urlWindows;

        return $this;
    }

    /**
     *
     * @param string $description
     *
     * @return PsaAppliMobile
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return PsaMedia
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param PsaMedia $media
     *
     * @return PsaAppliMobile
     */
    public function setMedia(PsaMedia $media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return PsaMedia
     */
    public function getMediaGooglePlay()
    {
        return $this->mediaGooglePlay;
    }

    /**
     * @param PsaMedia $mediaGooglePlay
     *
     * @return PsaAppliMobile
     */
    public function setMediaGooglePlay($mediaGooglePlay)
    {
        $this->mediaGooglePlay = $mediaGooglePlay;

        return $this;
    }

    /**
     * @return PsaMedia
     */
    public function getMediaAppleStore()
    {
        return $this->mediaAppleStore;
    }

    /**
     * @param PsaMedia $mediaAppleStore
     *
     * @return PsaAppliMobile
     */
    public function setMediaAppleStore($mediaAppleStore)
    {
        $this->mediaAppleStore = $mediaAppleStore;

        return $this;
    }

    /**
     * @return PsaMedia
     */
    public function getMediaWindows()
    {
        return $this->mediaWindows;
    }

    /**
     * @param PsaMedia $mediaWindows
     *
     * @return PsaAppliMobile
     */
    public function setMediaWindows($mediaWindows)
    {
        $this->mediaWindows = $mediaWindows;

        return $this;
    }


}
