<?php

namespace PsaNdp\MappingBundle\Object\Block;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface;
use PsaNdp\MappingBundle\Object\Column;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Cta;

/**
 * Class Pc72Colonnes.
 */
class Pc72Colonnes extends Content
{
    const RATIO_VISUEL = 'NDP_GENERIC_4_3_640';

    protected $size = ['desktop' => self::RATIO_VISUEL, 'mobile' => self::RATIO_VISUEL];

    protected $mapping = array(
        'datalayer' => 'dataLayer',
    );

    /**
     * @var Collection
     */
    protected $columns;

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
            $item->setMediaFactory($this->mediaFactory);
            $item->setCtaFactory($this->ctaFactory);
            $item->setDataFromArray($column);
            $item->init();
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
     * @param PsaPageZoneMultiConfigurableInterface $multis
     */
    public function initColumn($multis)
    {
        if ($this->block instanceof PsaPageZoneConfigurableInterface) {
            $this->title = $this->block->getZoneTitre();

            $item = [];
            $column = 0;
            foreach ($multis as $multi) {
                /* @var $multi PsaPageZoneMultiConfigurableInterface */
                $col = [];
                $media = $multi->getMedia();
                if ($media) {
                    $col['mediaOptions'] = ['size' => $this->size, 'autoCrop' => true];
                    $col['media'] = $media;
                }
                if ($multi->getPageZoneMultiTitre2()) {
                    $col['title'] = $multi->getPageZoneMultiTitre2();
                }
                if ($multi->getPageZoneMultiText()) {
                    $col['text'] = $multi->getPageZoneMultiText();
                }

                $type = $multi->getPageZoneMultiType().'CTA';
                $col['ctaList'] = $this->ctaFactory->create($this->block->getCtaReferencesByType($type), array('inline' => true, 'dropDownId' => $column));
                $item[] = $col;
                ++$column;
            }

            $this->setColumns($item);
        }
    }
}
