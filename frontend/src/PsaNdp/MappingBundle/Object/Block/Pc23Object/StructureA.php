<?php

namespace PsaNdp\MappingBundle\Object\Block\Pc23Object;

class StructureA extends AbstractStructure
{
    protected $file = './pc23/murmedia-a.tpl';

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
        return 'structure-a';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return '1-3';
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
        $size = ['desktop' => self::NDP_MURMEDIA_BIG_16_9,'mobile' => self::NDP_MURMEDIA_SMALL_16_9];
        $formats[] = array('method' => 'getMedia', 'formatId' => self::NDP_MURMEDIA_BIG_16_9, 'size' => $size);
        $size = ['default' => self::NDP_MURMEDIA_SMALL_16_9];
        $formats[] = array('method' => 'getMediaId2', 'formatId' => self::NDP_MURMEDIA_SMALL_16_9, 'size' => $size);
        $formats[] = array('method' => 'getMediaId3', 'formatId' => self::NDP_MURMEDIA_SMALL_16_9, 'size' => $size);
        $formats[] = array('method' => 'getMediaId4', 'formatId' => self::NDP_MURMEDIA_SMALL_16_9, 'size' => $size);

        return $formats;
    }
}
