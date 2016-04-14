<?php

namespace PsaNdp\MappingBundle\Object;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;

/**
 * Class Slideshow
 */
class Slideshow extends Content
{
    /**
     * @var string
     */
    protected $position = NULL;

    /**
     * @var string
     */
    protected $color = NULL;

    /**
     * @var string
     */
    protected $title = NULL;

    /**
     * @var string
     */
    protected $subTitle = NULL;

    /**
     * @var Media
     */
    protected $media = NULL;

    /**
     * @param CtaFactory   $ctaFactory
     * @param MediaFactory $mediaFactory
     */
    public function __construct(CtaFactory $ctaFactory, MediaFactory $mediaFactory)
    {
        $this->ctaFactory = $ctaFactory;
        $this->mediaFactory = $mediaFactory;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * @param string $subTitle
     */
    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;
    }

    /**
     * @return Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param Media $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    public function createSlideshowFromMulti($data, $options = array())
    {
        if ($data instanceof PsaPageZoneMultiConfigurableInterface)
        {
            $this->title    = $data->getPageZoneMultiTitre();
            $this->subTitle = $data->getPageZoneMultiTitre2();
            $this->position = $data->getPageZoneMultiValue();
            $this->color    = $data->getPageZoneMultiValue2();
            $this->media    = $this->mediaFactory->createFromMedia($data->getMedia(), $options);
            $this->ctaList  = $this->ctaFactory->create($data->getCtaReferences());

        }

        return $this;
    }

    public function createVideo($data, $options = array())
    {
        if ($data instanceof PsaPageZoneConfigurableInterface)
        {
            $this->title    = $data->getZoneTitre();
            $this->subTitle = $data->getZoneTitre2();
            $this->position = $data->getZoneTool();
            $this->color    = $data->getZoneTool2();
            $this->media    = $this->mediaFactory->createFromMedia($data->getMedia(), $options);
            $this->ctaList  = $this->ctaFactory->create($data->getCtaReferences());
        }

        return $this;
    }

}
