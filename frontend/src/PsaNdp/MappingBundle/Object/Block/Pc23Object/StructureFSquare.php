<?php

namespace PsaNdp\MappingBundle\Object\Block\Pc23Object;

class StructureFSquare extends AbstractStructure
{
    protected $file = './pc23/murmedia-f.tpl';

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
        return 'structure-f-square';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return '2-2-1';
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
        $formats[] = array('method' => 'getMediaId3', 'formatId' => self::NDP_MURMEDIA_SMALL_SQUARE, 'size' => $size);
        $formats[] = array('method' => 'getMediaId4', 'formatId' => self::NDP_MURMEDIA_SMALL_SQUARE, 'size' => $size);
        $size = ['desktop' => self::NDP_MURMEDIA_BIG_SQUARE,'mobile' => self::NDP_MURMEDIA_SMALL_SQUARE];
        $formats[] = array('method' => 'getMediaId5', 'formatId' => self::NDP_MURMEDIA_SMALL_SQUARE, 'size' => $size);

        return $formats;
    }
}
