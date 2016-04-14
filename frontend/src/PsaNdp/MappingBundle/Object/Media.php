<?php

namespace PsaNdp\MappingBundle\Object;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Media
 */
class Media extends Content
{

    /**
     * @var array $mapping
     */
    protected $mapping = array(
        'src'        => 'source',
        'srcDefault' => 'source',
        'media'      => 'social',
        'ctaList'    => 'social',
        'baseurl'    => 'baseUrl',
    );

    /**
     * @var string $type
     */
    protected $type = 'img';

    /**
     * @var string $source
     */
    protected $source;

    /**
     * @var string $thumbnail
     */
    protected $thumbnail;

    /**
     * @var string $poster
     */
    protected $poster;

    /**
     * @var int $size
     */
    protected $size;

    /**
     * @var string $remoteId
     */
    protected $id;

    /**
     * @var string $baseUrl
     */
    protected $baseUrl;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var int $width
     */
    protected $width;

    /**
     * @var int $height
     */
    protected $height;

    /**
     * @var string $alt
     */
    protected $alt;

    /**
     * @var array $social
     */
    protected $social;

    /**
     * @var Collection
     */
    protected $listItems;

    /**
     * @var string $textLeft
     */
    protected $textLeft;

    /**
     * @var string $textRight
     */
    protected $textRight;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->listItems = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getListItems()
    {
        return $this->listItems;
    }

    /**
     * @param Collection $listItems
     *
     * @return $this
     */
    public function setListItems($listItems)
    {
        foreach ($listItems as $item) {
            $this->listItems->add($item);
        }

        return $this;
    }

    /**
     * @param string $alt
     *
     * @return $this
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        $return = $this->alt;

        if (empty($return)) {
            $return = $this->getTitle();
        }

        return $return;
    }

    /**
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param int $height
     *
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;

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
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $source
     *
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param int $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

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
     * @param array $social
     *
     * @return $this
     */
    public function setSocial($social)
    {
        $this->social = $social;

        return $this;
    }

    /**
     * @return array
     */
    public function getSocial()
    {
        return $this->social;
    }

    /**
     * @param string $poster
     *
     * @return $this
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * @return string
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @param string $thumbnail
     *
     * @return $this
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @return string
     */
    public function getTextLeft()
    {
        return $this->textLeft;
    }

    /**
     * @param string $textLeft
     *
     * @return $this
     */
    public function setTextLeft($textLeft)
    {
        $this->textLeft = $textLeft;

        return $this;
    }

    /**
     * @return string
     */
    public function getTextRight()
    {
        return $this->textRight;
    }

    /**
     * @param string $textRight
     *
     * @return $this
     */
    public function setTextRight($textRight)
    {
        $this->textRight = $textRight;

        return $this;
    }
}
