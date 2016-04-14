<?php

namespace PsaNdp\ApiBundle\Object;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class ColorType
 */
class ColorType
{
    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var bool $default
     */
    protected $default = false;

    /**
     * @var string $version
     */
    protected $version;

    /**
     * @var integer
     */
    protected $siteId;

    /**
     * @var string $code
     */
    protected $code;

    /**
     * @var string $label
     */
    protected $label;

    /**
     * @var ArrayCollection $colors
     */
    protected $colors;

    public function __construct()
    {
        $this->colors = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * @param boolean $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return ArrayCollection
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * @param ArrayCollection $colors
     */
    public function setColors(ArrayCollection $colors)
    {
        $this->colors = $colors;
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
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @param Color $color
     */
    public function addColors(Color $color)
    {
        $this->colors->add($color);
    }

    /**
     * @return int
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param int $siteId
     *
     * @return ColorType
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * @return string
     */
    public function getLcdv6()
    {
        return  substr($this->version, 0, 6);
    }
}
