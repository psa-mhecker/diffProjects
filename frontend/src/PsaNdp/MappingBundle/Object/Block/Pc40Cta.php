<?php

namespace PsaNdp\MappingBundle\Object\Block;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Image;

class Pc40Cta extends Content
{
    /**
     * @var Image
     */
    protected $desktopVisual = null;

    public function __construct(CtaFactory $ctaFactory, MediaFactory $mediaFactory)
    {
        $this->ctaFactory = $ctaFactory;
        $this->mediaFactory = $mediaFactory;
    }

    /**
     * @return array
     */
    public function getCtaList()
    {
        $hasMedia = false;

        if($this->getDisplayMode() === 1){
            $hasMedia = true;
        }

        if (empty($this->ctaList)) {

            if ($this->block instanceof ReadBlockInterface) {
                $this->initCtaListFromBlock($this->block, array('media' => $hasMedia));
            }
        }

        return $this->ctaList;
    }

    /**
     * @return Image|null
     */
    public function getDesktopVisual()
    {
        $desktopMedia = $this->block->getMedia();

        if ( ! empty($desktopMedia)) {
            $this->desktopVisual = $this->mediaFactory->createFromMedia($desktopMedia);
        }

        return $this->desktopVisual;
    }

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
    public function getDisplayMode()
    {
        return $this->block->getZoneAffichage();
    }
}
