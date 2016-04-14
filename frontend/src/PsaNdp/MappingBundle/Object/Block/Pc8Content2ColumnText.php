<?php

namespace PsaNdp\MappingBundle\Object\Block;

use Doctrine\Common\Collections\Collection;
use Exception;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;

/**
 * Class Pc8Content2ColumnText
 */
class Pc8Content2ColumnText extends Pc72Colonnes
{
    const RADIO_CTA = 1;
    const RADIO_PDF = 2;

    /**
     * @var Collection
     */
    protected $column1;

    /**
     * @var Collection
     */
    protected $column2;

    /**
     * @var string
     */
    protected $mediaServer;

    /**
     * @param CtaFactory $ctaFactory
     * @param MediaFactory $mediaFactory
     */
    public function __construct(CtaFactory $ctaFactory, MediaFactory $mediaFactory)
    {
        $this->ctaFactory = $ctaFactory;
        $this->mediaFactory = $mediaFactory;
    }

    /**
     * @return string
     *
     * @throws Exception
     */
    public function getTitle()
    {
        if (!$this->block instanceof PsaPageZoneConfigurableInterface) {
            throw new Exception();
        }

        return $this->block->getZoneTitre();
    }

    /**
     * @return string
     *
     * @throws Exception
     */
    public function getTitleMobile()
    {
        if (!$this->block instanceof PsaPageZoneConfigurableInterface) {
            throw new Exception();
        }

        return $this->block->getZoneTitre2();
    }

    /**
     * @param Collection $column1
     */
    public function setColumn1($column1)
    {
        $this->column1 = $column1;
    }

    /**
     * @param Collection $column2
     */
    public function setColumn2($column2)
    {
        $this->column2 = $column2;
    }

    /**
     * @param string $mediaServer
     */
    public function setMediaServer($mediaServer)
    {
        $this->mediaServer = $mediaServer;
    }

    /**
     * @return bool
     */
    public function getOpenAccordion()
    {
        return ($this->block->getZoneAttribut() === 1);
    }

    /**
     * initColumn
     */
    public function initTwoColumn()
    {
        $firstColumn = array(
            'title' => $this->block->getZoneTitre3(),
            'text' => $this->block->getZoneTexte(),
            'ctaList' => $this->ctaFactory->create($this->column1, array('type' => Cta::NDP_CTA_TYPE_SIMPLELINK, 'inline' => false)),
        );

        $secondColumn = array(
            'title' => $this->block->getZoneTitre4(),
            'text' => $this->block->getZoneTexte2(),
            'ctaList' => $this->ctaFactory->create($this->column2, array('type' => Cta::NDP_CTA_TYPE_SIMPLELINK, 'inline' => false)),
        );

        if ($this->block->getMedia()) {
            $firstColumn['media'] = $this->block->getMedia();
        }

        if ($this->block->getMedia2()) {
            $secondColumn['media'] = $this->block->getMedia2();
        }

        $this->setColumns(array($firstColumn, $secondColumn));
    }
}
