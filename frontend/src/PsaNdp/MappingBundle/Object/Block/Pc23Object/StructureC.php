<?php

namespace PsaNdp\MappingBundle\Object\Block\Pc23Object;

class StructureC extends AbstractStructure
{
    protected $file = './pc23/murmedia-c.tpl';

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'structure-c';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return '1-1';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::NDP_WIDESCREEN;
    }

    public function getFormats()
    {
        $formats = [];
        $size = ['default' => self::NDP_MURMEDIA_SMALL_16_9];
        $formats[] = array('method' => 'getMedia','formatId' => self::NDP_MURMEDIA_SMALL_16_9, 'size' => $size);
        $formats[] = array('method' => 'getMediaId2', 'formatId' => self::NDP_MURMEDIA_SMALL_16_9, 'size' => $size);

        return $formats;
    }
}
