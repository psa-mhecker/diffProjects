<?php

namespace PsaNdp\MappingBundle\Object;

/**
 * Class Image.
 */
class Image extends Content implements MediaInterface
{
    /**
     * @var string
     */
    protected $blank;

    /**
     * @var string
     */
    protected $src;

    /**
     * @var string
     */
    protected $original;

    /**
     * @var string
     */
    protected $alt;

    /**
     * @var array
     */
    protected $thumbnails;

    /**
     * @var bool
     */
    protected $autoCrop;

    /**
     * @var array
     */
    protected $size;

    /**
     * @return string
     */
    public function getSrc()
    {
        $src = $this->src;
        if ($this->hasAutoCrop()) {
            $src = $this->src.'?autocrop=1';
        }

        return $src;
    }

    /**
     * @param string $src
     *
     * @return Image
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        $alt = $this->alt;

        if (empty($alt)) {
            $alt = $this->title;
        }

        return $alt;
    }

    /**
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    public function getType()
    {
        return self::TYPE_IMAGE;
    }

    /**
     * @param Image $thumbnail
     * @param int   $format
     *
     * @return $this
     */
    public function addThumbnail(Image $thumbnail, $format)
    {
        $this->thumbnails[$format] = $thumbnail;

        return $this;
    }

    /**
     * @param int $format
     *
     * @return Image
     */
    public function getThumbnail($format)
    {
        // si thumbnail n'existe pas dans le format demandé on retourne l'image d'origine
        $return = $this;
        if (isset($this->thumbnails[$format])) {
            $return = $this->thumbnails[$format];
        }

        return $return;
    }

    /**
     * @return bool
     */
    public function hasAutoCrop()
    {
        return $this->autoCrop;
    }

    /**
     * @param bool $autoCrop
     */
    public function setAutoCrop($autoCrop)
    {
        $this->autoCrop = $autoCrop;
    }

    /**
     * @return string
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * @param string $original
     *
     * @return Image
     */
    public function setOriginal($original)
    {
        $this->original = $original;

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
     * @return array
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param array $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }
}
