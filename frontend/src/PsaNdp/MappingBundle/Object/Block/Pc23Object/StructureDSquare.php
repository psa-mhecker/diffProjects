<?php

namespace PsaNdp\MappingBundle\Object\Block\Pc23Object;

class StructureDSquare extends AbstractStructure
{
    protected $file = './pc23/murmedia-d.tpl';

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
        return 'structure-d-square';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return '1-2-2';
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
        $size = ['desktop' => self::NDP_MURMEDIA_BIG_SQUARE,'mobile' => self::NDP_MURMEDIA_SMALL_SQUARE];
        $formats[] = array('method' => 'getMedia','formatId' => self::NDP_MURMEDIA_BIG_SQUARE, 'size' => $size);
        $size = ['default' => self::NDP_MURMEDIA_SMALL_SQUARE];
        $formats[] = array('method' => 'getMediaId2', 'formatId' => self::NDP_MURMEDIA_SMALL_SQUARE, 'size' => $size);
        $formats[] = array('method' => 'getMediaId3', 'formatId' => self::NDP_MURMEDIA_SMALL_SQUARE, 'size' => $size);
        $formats[] = array('method' => 'getMediaId4', 'formatId' => self::NDP_MURMEDIA_SMALL_SQUARE, 'size' => $size);
        $formats[] = array('method' => 'getMediaId5', 'formatId' => self::NDP_MURMEDIA_SMALL_SQUARE, 'size' => $size);

        return $formats;
    }
}
