<?php

namespace PsaNdp\MappingBundle\Object\Popin;

use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\Media;

/**
 * Class PopinNew
 */
class PopinNew extends Content
{
    protected $mapping = array(
        'visuels' => 'media',
    );

    /**
     * @var Media $media
     */
    protected $media;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mediaFactory = new MediaFactory();
        $this->media = $this->mediaFactory->createMedia();
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
    public function setMedia(array $media)
    {
        $this->media = $this->mediaFactory->createFromArray($media);

        return $this;
    }
}
