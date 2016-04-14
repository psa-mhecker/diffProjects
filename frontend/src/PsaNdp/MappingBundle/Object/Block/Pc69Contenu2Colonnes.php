<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface;
use PsaNdp\MappingBundle\Object\Column;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;

/**
 * Class Pc69Contenu2Colonnes
 */
class Pc69Contenu2Colonnes extends Content
{
    const COLUMN_1QUART_3QUART = '2';
    const COLUMN_3QUART_1QUART = '1';

    const RATIO_VISUEL_1_4 = 'NDP_CONTENT_1_3';
    const RATIO_VISUEL_3_4 = 'NDP_CONTENT_4_3';
    const RATIO_VISUEL_MOBILE = 'NDP_GENERIC_4_3_640';
    const MIN_DIMENSION = 'NDP_MIN_PC69';

    /**
     * @var array
     */
    protected $column14;

    /**
     * @var array
     */
    protected $column34;

    /**
     * @var Column
     */
    protected $columnLeft;

    /**
     * @var Column
     */
    protected $columnRight;

    /**
     * @var string
     */
    protected $order;

    /**
     * @param CtaFactory   $ctaFactory
     * @param MediaFactory $mediaFactory
     */
    public function __construct(CtaFactory $ctaFactory, MediaFactory $mediaFactory)
    {
        $this->ctaFactory = $ctaFactory;
        $this->mediaFactory = $mediaFactory;
    }

    /**
     * @return Column
     */
    public function getColumnLeft()
    {
        if (empty($this->columnLeft)) {
            $this->initColumns();
        }

        return $this->columnLeft;
    }

    /**
     * @return Column
     */
    public function getColumnRight()
    {
        if (empty($this->columnRight)) {
            $this->initColumns();
        }

        return $this->columnRight;
    }

    /**
     * @param array $column14
     */
    public function setColumn14($column14)
    {
        $column14['size'] = ['desktop'=>self::RATIO_VISUEL_1_4,'mobile'=>self::RATIO_VISUEL_MOBILE];
        $this->column14 = $column14;
    }

    /**
     * @param array $column34
     */
    public function setColumn34($column34)
    {
        $column34['size'] = ['desktop'=>self::RATIO_VISUEL_3_4,'mobile'=>self::RATIO_VISUEL_MOBILE];
        $this->column34 = $column34;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if ($this->block instanceof PsaPageZoneConfigurableInterface) {
            $this->title = $this->block->getZoneTitre();
        }

        return $this->title;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        if (empty($this->order) && $this->block instanceof PsaPageZoneConfigurableInterface) {
            if ($this->block->getZoneParameters() === self::COLUMN_3QUART_1QUART) {
                $this->order = 'right';
            } elseif ($this->block->getZoneParameters() === self::COLUMN_1QUART_3QUART) {
                $this->order = 'left';
            }
        }

        return $this->order;
    }

    /**
     * Initialize columns
     */
    public function initColumns()
    {
        $this->order = null;
        if ($this->getOrder() === 'right') {
            $this->columnLeft = $this->getColumnData($this->column34, true, 1);
            $this->columnRight = $this->getColumnData($this->column14, false, 2);
        } elseif ($this->getOrder() === 'left') {
            $this->columnLeft = $this->getColumnData($this->column14, false, 1);
            $this->columnRight = $this->getColumnData($this->column34, true, 2);
        }
    }

    /**
     * @param array  $column
     * @param bool   $inline
     * @param int    $columnNumber
     *
     * @return Column
     */
    protected function getColumnData(array $column, $inline, $columnNumber)
    {
        $data = array();


        if (isset($column['content']) && $column['content'] instanceof PsaPageZoneMultiConfigurableInterface) {
            /**
             * @var PsaPageZoneMultiConfigurableInterface $content
             */
            $content = $column['content'];

            $data['title'] = $content->getPageZoneMultiTitre();
            $data['text'] = $content->getPageZoneMultiText();

            $media = $content->getMedia();

            if ($media instanceof PsaMedia) {
                $data['mediaOptions'] = ['autoCrop'=>1,'size'=>$column['size']];
                $data['media'] = $media;
            }
        }

        $data['ctaList'] = array();
        if (isset($column['ctaList'])) {
            $data['ctaList'] = $this->ctaFactory->create($column['ctaList'], array('inline' => $inline, 'dropDownId' => $columnNumber));
        }

        $col = new column();
        $col->setMediaFactory($this->mediaFactory);
        $col->setDataFromArray($data);
        $col->init();

        return $col;
    }
}
