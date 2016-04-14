<?php

namespace PsaNdp\MappingBundle\Object\Popin;

use PsaNdp\MappingBundle\Object\Content;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Media;

/**
 * Class PopinEquipments
 */
class PopinEquipments extends Content
{
    protected $mapping = array();

    /**
     * @var Collection
     */
    protected $slides;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->slides = new ArrayCollection();
        $this->mediaFactory = new MediaFactory();
    }

    /**
     * @return Collection
     */
    public function getSlides()
    {
        return $this->slides;
    }

    /**
     * @param array $slides
     *
     * @return $this
     */
    public function setSlides($slides)
    {
        foreach ($slides as $slide) {
            $media = $this->mediaFactory->createFromArray($slide);
            $this->addSlide($media);
        }

        return $this;
    }

    /**
     * @param Media $media
     */
    public function addSlide(Media $media)
    {
        $this->slides->add($media);
    }
}
