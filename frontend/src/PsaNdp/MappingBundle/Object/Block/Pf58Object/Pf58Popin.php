<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf58Object;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Media;

/**
 * Class Pf58Popin
 */
class Pf58Popin extends Content
{
    protected $mapping = array(
        'illus' => 'media'
    );

    /**
     * @var Media $media
     */
    protected $media;

    /**
     * @var Pf58Information $infos
     */
    protected $infos;

    /**
     * @var Collection $toggle
     */
    protected $toggle;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->infos = new Pf58Information();
        $this->mediaFactory = new MediaFactory();
        $this->media = $this->mediaFactory->createMedia();
        $this->toggle = new ArrayCollection();
    }

    /**
     * @return Pf58Information
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * @param Pf58Information $infos
     *
     * @return $this
     */
    public function setInfos(Pf58Information $infos)
    {
        $this->infos = $infos;

        return $this;
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
     *
     * @return $this
     */
    public function setMedia(Media $media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getToggle()
    {
        return $this->toggle;
    }

    /**
     * @param Collection $toggle
     *
     * @return $this
     */
    public function setToggle(Collection $toggle)
    {
        $this->toggle = $toggle;

        return $this;
    }
}
