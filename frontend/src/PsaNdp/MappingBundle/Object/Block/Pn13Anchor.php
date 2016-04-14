<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pn13Anchor
 */
class Pn13Anchor extends Content
{
    /**
     * @var array
     */
    protected $anchors = array();

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->block->getZoneTitre();
    }

    /**
     * @return array
     */
    public function getAnchors()
    {
        return $this->anchors;
    }

    /**
     * @param array $anchors
     *
     * @return $this
     */
    public function setAnchors(array $anchors)
    {
        $this->anchors = $anchors;

        return $this;
    }
}
