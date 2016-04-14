<?php

namespace PsaNdp\MappingBundle\Object\Block\Pc23Object;

class StructureB extends AbstractStructure
{
    protected $file = './pc23/murmedia-b.tpl';

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
        return 'structure-b';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return '3-1';
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
        $formats[] = array('method' => 'getMedia', 'formatId' => self::NDP_MURMEDIA_SMALL_16_9, 'size' => $size);
        $formats[] = array('method' => 'getMediaId2', 'formatId' => self::NDP_MURMEDIA_SMALL_16_9, 'size' => $size);
        $formats[] = array('method' => 'getMediaId3', 'formatId' => self::NDP_MURMEDIA_SMALL_16_9, 'size' => $size);
        $size = ['desktop' => self::NDP_MURMEDIA_BIG_16_9,'mobile' => self::NDP_MURMEDIA_SMALL_16_9];
        $formats[] = array('method' => 'getMediaId4', 'formatId' => self::NDP_MURMEDIA_BIG_16_9, 'size' => $size);

        return $formats;
    }
}
