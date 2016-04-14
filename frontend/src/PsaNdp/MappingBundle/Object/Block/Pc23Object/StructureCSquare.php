<?php

namespace PsaNdp\MappingBundle\Object\Block\Pc23Object;

class StructureCSquare extends AbstractStructure
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
        return 'structure-c-square';
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
        return self::NDP_SQUARE;
    }

    public function getFormats()
    {
        $formats = [];
        $size = ['default' => self::NDP_MURMEDIA_SMALL_SQUARE];
        $formats[] = array('method' => 'getMedia', 'formatId' => self::NDP_MURMEDIA_SMALL_SQUARE, 'size' => $size);
        $formats[] = array('method' => 'getMediaId2', 'formatId' => self::NDP_MURMEDIA_SMALL_SQUARE, 'size' => $size);

        return $formats;
    }
}
