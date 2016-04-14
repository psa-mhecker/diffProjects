<?php

namespace PsaNdp\MappingBundle\Object;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PSA\MigrationBundle\Entity\Cta\PsaCta;

/**
 * Class Cta
 */
class Cta extends Content
{
    const NDP_CTA_VERSION_DARK_BLUE = 'dark-blue'; // blue dark
    const NDP_CTA_VERSION_LIGHT_BLUE = 'light-blue'; // blue light
    const NDP_CTA_VERSION_GREY = 'grey'; // Grey

    //@todo vérifier la valeur à donner apres correction du tpl par NDP
    const NDP_CTA_TYPE_HIDDEN       = 'TYPE_HIDDEN'; // hidden button
    const NDP_CTA_TYPE_DROPDOWNLIST = 'TYPE_DROPDOWNLIST'; // dropdown list
    const NDP_CTA_TYPE_SIMPLELINK   = 'TYPE_SIMPLELINK'; // Simple Link
    const NDP_CTA_TYPE_BUTTON        = 'TYPE_BUTTON'; // Button
    const NDP_CTA_TYPE_IMAGE        = 'TYPE_IMG'; // Image
    const NDP_CTA_TYPE_ICON        = 'TYPE_ICON'; // Image


    /**
     * @var array $mapping
     */
    protected $mapping = array(
        'href' => 'url',
    );

    /**
     * @var string $label
     */
    protected $label;

    /**
     * @var string $type
     */
    protected $type;

    /**
     * @var string $class
     */
    protected $class;

    /**
     * @var Collection
     */
    protected $options;

    /**
     * @var string $image
     */
    protected $image;

    /**
     * @var string $popin
     */
    protected $popin;

    /**
     * @var string $alt
     */
    protected $alt;

    /**
     * @var string
     */
    protected $popinId = null;

    /**
     * @var bool
     */
    protected $inline = true;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var int
     */
    protected $dropDownId;

    /**
     * @var bool
     */
    protected $icon;

    /**
     * @var bool
     */
    protected $media;

    /**
     * @var string
     */
    protected $lcdv16;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->options = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $lcdv16 = $this->getLcdv16();
        $return = $this->url;
        if (!empty($lcdv16)) {
            $return = sprintf('%s?lcdv16=%s', $this->url, $lcdv16);
        }

        return $return;
    }

    /**
     * @return boolean
     */
    public function getInline()
    {
        return $this->inline;
    }

    /**
     * @param boolean $inline
     */
    public function setInline($inline)
    {
        $this->inline = $inline;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
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
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Collection $options
     */
    public function setOptions($options)
    {
        foreach ($options as $option) {
            $this->options->add($option);
        }
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getPopin()
    {
        return $this->popin;
    }

    /**
     * @param string $popin
     */
    public function setPopin($popin)
    {
        $this->popin = $popin;
    }

    /**
     * @param string $alt
     * @return Cta
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
        return $this->alt;
    }

    /**
     * @param string $label
     * @return Cta
     */
    public function setLabel($label)
    {
        $this->label = $label;

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
     * @return string
     */
    public function getPopinId()
    {
        return $this->popinId;
    }

    /**
     * @param string $popinId
     *
     * @return $this
     */
    public function setPopinId($popinId)
    {
        $this->popinId = $popinId;

        return $this;
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
     * @return int
     */
    public function getDropDownId()
    {
        return $this->dropDownId;
    }

    /**
     * @param int $dropDownId
     */
    public function setDropDownId($dropDownId)
    {
        $this->dropDownId = $dropDownId;
    }

    /**
     * @return boolean
     */
    public function isIcon()
    {
        return $this->icon;
    }

    /**
     * @param boolean $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return boolean
     */
    public function isMedia()
    {
        return $this->media;
    }

    /**
     * @param boolean $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return string
     */
    public function getLcdv16()
    {
        return $this->lcdv16;
    }

    /**
     * @param string $lcdv16
     */
    public function setLcdv16($lcdv16)
    {
        $this->lcdv16 = $lcdv16;
    }
}
