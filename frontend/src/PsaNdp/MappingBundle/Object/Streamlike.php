<?php

namespace PsaNdp\MappingBundle\Object;

/**
 * Class Streamlike
 */
class Streamlike extends Content implements MediaInterface
{
    /**
     * @var array
     */
    protected $overrideMapping = array(
        'media_id'=>'mediaId',
        'src'=>'cover' // pour que la video se comporte comme une image on renvoie la cover
    );

    /**
     * @var  string
     */
    protected $blank;

    /**
     * @var string
     */
    protected $mediaId;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var string
     */
    protected $poster;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @return string
     */
    public function getMediaId()
    {
        return $this->mediaId;
    }

    /**
     * @param string $mediaId
     * @return Video
     */
    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return Video
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return Video
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @inherit
     */
    public function getType()
    {
        return self::TYPE_VIDEO;
    }

    /**
     * @return string
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @param string $poster
     * @return Streamlike
     *
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * @return string
     */
    public function getBlank()
    {
        return $this->blank;
    }

    /**
     * @param string $blank
     */
    public function setBlank($blank)
    {
        $this->blank = $blank;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
}

