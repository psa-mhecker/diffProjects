<?php

namespace PsaNdp\MappingBundle\Object;

use PSA\MigrationBundle\Entity\Media\PsaMedia;

/**
 * Class Column.
 */
class Column extends Content
{
    protected $mapping = array(
        'text' => 'subtitle',
    );

    /**
     * @var array
     */
    protected $mediaOptions;

    /**
     * @var Media
     */
    protected $media;

    /**
     * @param PsaMedia $media
     *
     * @return $this
     */
    public function setMedia(PsaMedia $media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return $this
     */
    public function init()
    {
        if ($this->media) {
            $this->media = $this->mediaFactory->createFromMedia($this->media, $this->mediaOptions);
        }

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
     * @return array
     */
    public function getMediaOptions()
    {
        return $this->mediaOptions;
    }

    /**
     * @param array $mediaOptions
     *
     * @return $this
     */
    public function setMediaOptions(array $mediaOptions)
    {
        $this->mediaOptions = $mediaOptions;

        return $this;
    }
}
