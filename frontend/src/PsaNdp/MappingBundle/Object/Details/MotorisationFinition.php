<?php

namespace PsaNdp\MappingBundle\Object\Details;

;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class MotorisationFinition
 */
class MotorisationFinition extends Content
{
    protected $mapping = array();

    /**
     * @var bool
     */
    protected $textWhite;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var string
     */
    protected $label;

    /**
     * @return boolean
     */
    public function isTextWhite()
    {
        return $this->textWhite;
    }

    /**
     * @param boolean $textWhite
     *
     * @return MotorisationFinition
     */
    public function setTextWhite($textWhite)
    {
        $this->textWhite = $textWhite;

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
     *
     * @return MotorisationFinition
     */
    public function setColor($color)
    {
        $this->color = $color;

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
     * @return MotorisationFinition
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }


}
