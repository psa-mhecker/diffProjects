<?php

namespace PsaNdp\MappingBundle\Object\Block;

use Doctrine\Common\Collections\ArrayCollection;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Column;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Object\MediaInterface;
use PsaNdp\MappingBundle\Services\MediaServerInitializer;

/**
 * Class Pf6DragDrop.php.
 */
class Pf6DragDrop extends Content
{
    const DIRECTION_V = 'vertical';
    const DIRECTION_H = 'horizontal';
    const RATIO_VISUEL = 'NDP_MEDIA_CONTENT_ONE_COLUMN';
    const RATIO_VISUEL_MOBILE = 'NDP_GENERIC_4_3_640';

    /**
     * @var Collection
     */
    protected $columns;

    /**
     * @var MediaInterface
     */
    protected $before;

    /**
     * @var MediaInterface
     */
    protected $after;

    /**
     * @var String
     */
    protected $direction = self::DIRECTION_H;

    /**
     * @var MediaServerInitializer
     */
    protected $mediaServer;

    /**
     * @var string
     */
    protected $columnsTitle;

    public function __construct(CtaFactory $ctaFactory, MediaFactory $mediaFactory, MediaServerInitializer $mediaServer)
    {
        $this->ctaFactory = $ctaFactory;
        $this->mediaFactory = $mediaFactory;
        $this->mediaServer = $mediaServer;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->block->getZoneTitre();
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->block->getZoneTitre2();
    }

    /**
     * @return Collection
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function setColumns(array $columns)
    {
        $this->columns = new ArrayCollection();
        foreach ($columns as $column) {
            $item = new Column();
            $item->setDataFromArray($column);
            $this->addColumn($item);
        }

        return $this;
    }

    /**
     * @param Column $column
     */
    public function addColumn(Column $column)
    {
        $this->columns->add($column);
    }

    /**
     * @return string
     */
    public function getColumnsTitle()
    {
        return $this->block->getZoneTitre3();
    }

    /**
     * @param string $columnsTitle
     */
    public function setColumnsTitle($columnsTitle)
    {
        $this->columnsTitle = $columnsTitle;
    }

    /**
     *
     */
    public function initColumn()
    {
        if ($this->block instanceof PsaPageZoneConfigurableInterface) {
            $col[] = array('text' => $this->block->getZoneTexte());
            if ($this->block->getZoneTool() === '2_COL') {
                $col[] = array('text' => $this->block->getZoneTexte2());
            }

            $this->setColumns($col);
        }
    }

    /**
     * @return String
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param String $direction
     *
     * @return String
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this->direction;
    }

    /**
     * @return MediaInterface
     */
    public function getAfter()
    {
        return $this->after;
    }

    /**
     * @param MediaInterface $after
     */
    public function setAfter(MediaInterface $after)
    {
        $this->after = $after;
    }

    /**
     * @return MediaInterface
     */
    public function getBefore()
    {
        return $this->before;
    }

    /**
     * @param MediaInterface $before
     */
    public function setBefore(MediaInterface $before)
    {
        $this->before = $before;
    }

    /**
     * @return MediaServerInitializer
     */
    public function getMediaServer()
    {
        return $this->mediaServer;
    }

    public function init()
    {
        if (intval($this->block->getZonePos()) === 1) {
            $this->direction = self::DIRECTION_V;
        }

        $size = ['desktop' => self::RATIO_VISUEL,'mobile' => self::RATIO_VISUEL_MOBILE];
        if ($this->block->getMedia()) {
            $this->before = $this->mediaFactory->createFromMedia($this->block->getMedia(), ['size' => $size, 'autoCrop'=>true]);
        }

        if ($this->block->getMedia2()) {
            $this->after = $this->mediaFactory->createFromMedia($this->block->getMedia2(), ['size' => $size, 'autoCrop'=>true]);
        }

        $this->initColumn();

        $this->ctaList = $this->ctaFactory->create($this->block->getCtaReferences());
    }
}
