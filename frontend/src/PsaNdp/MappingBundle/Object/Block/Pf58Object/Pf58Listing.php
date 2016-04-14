<?php

namespace PsaNdp\MappingBundle\Object\Block\Pf58Object;

use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pf58Listing
 */
class Pf58Listing extends Content
{
    protected $mapping = array(
        'link' => 'url',
        'libelle' => 'label',
    );

    /**
     * @var string $id
     */
    protected $id;

    /**
     * @var string $src
     */
    protected $src;

    /**
     * @var string $alt
     */
    protected $alt;

    /**
     * @var string $text
     */
    protected $text;

    /**
     * @var string $label
     */
    protected $label;

    /**
     * @var string $mode
     */
    protected $mode;

    /**
     * @var array $sticker
     */
    protected $sticker = array();

    /**
     * @var string $mention
     */
    protected $mention;

    /**
     * @var string $by
     */
    protected $by;

    /**
     * @var string $price
     */
    protected $price;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->popin = new Pf58Popin();
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
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
    public function getBy()
    {
        return $this->by;
    }

    /**
     * @param string $by
     *
     * @return $this
     */
    public function setBy($by)
    {
        $this->by = $by;

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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     *
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return string
     */
    public function getMention()
    {
        return $this->mention;
    }

    /**
     * @param string $mention
     *
     * @return $this
     */
    public function setMention($mention)
    {
        $this->mention = $mention;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @param string $src
     *
     * @return $this
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * @return array
     */
    public function getSticker()
    {
        return $this->sticker;
    }

    /**
     * @param array $sticker
     *
     * @return $this
     */
    public function setSticker(array $sticker)
    {
        $this->sticker = $sticker;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }
}
