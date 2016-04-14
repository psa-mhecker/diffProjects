<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pn18IFrame
 * @package PsaNdp\MappingBundle\Object\Block
 */
class Pn18IFrame extends Content
{
    /**
     *
     * @var int
     */
    protected $desktopHeightIframe;

    /**
     *
     * @var int
     */
    protected $mobileHeightIframe;

    /**
     * @var string
     */
    protected $desktopUrl;

    /**
     * @var string
     */
    protected $mobileUrl;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var bool
     */
    private $mobile;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->block->getZoneTitre();
    }

    /**
     * @return int
     */
    public function getDesktopHeightIframe()
    {
        return $this->block->getZoneAttribut();
    }

    /**
     * @return int
     */
    public function getMobileHeightIframe()
    {
        return $this->block->getZoneAttribut2();
    }

    /**
     * @return string
     */
    public function getDesktopUrl()
    {
        return $this->block->getZoneUrl();
    }

    /**
     * @return string
     */
    public function getMobileUrl()
    {
        return $this->block->getZoneUrl2();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->block->getZoneTexte();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = $this->getDesktopUrl();

        if($this->mobile && $this->getMobileUrl()){
            $url = $this->getMobileUrl();
        }

        return $url;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        $height = $this->getDesktopHeightIframe();

        if($this->mobile && $this->getMobileHeightIframe()){
            $height = $this->getMobileHeightIframe();
        }

        return $height;
    }

    /**
     * Get isMobile
     *
     * @return boolean
     */
    public function isMobile()
    {
        return $this->mobile;
    }

    /**
     * @param boolean $mobile
     *
     * @return Pn18IFrame
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }
}
