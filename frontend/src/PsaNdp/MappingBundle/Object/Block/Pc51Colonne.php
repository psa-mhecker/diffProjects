<?php

namespace PsaNdp\MappingBundle\Object\Block;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use PsaNdp\MappingBundle\Transformers\Pc51ColonneDataTransformer;

/**
 * Class Pc51Colonne.
 */
class Pc51Colonne extends Content
{
    const DESKTOP_FORMAT = 'NDP_MEDIA_CONTENT_ONE_COLUMN';
    const MOBILE_FORMAT = 'NDP_GENERIC_4_3_640';
    const VISUEL = 'VISUEL';
    const VIDEO = 'VIDEO';
    const HTML5 = 'HTML5';
    const NO_MEDIA = 'NDP_NO_MEDIA';

    /**
     * @var array
     */
    protected $overrideMapping = array('ctas' => 'ctaList');

    /**
     * @var array
     */
    protected $article;

    /**
     * @var string
     */
    protected $articleTitle;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $slideshow;

    /**
     * @var
     */
    protected $numberOfColumns;

    public function __construct(CtaFactory $ctaFactory, MediaFactory $mediaFactory)
    {
        $this->ctaFactory = $ctaFactory;
        $this->mediaFactory = $mediaFactory;
    }

    /**
     * @return array
     */
    public function getCtas()
    {
        if (empty($this->ctasList)) {
            if ($this->block instanceof ReadBlockInterface) {
                $this->initCtaListFromBlock($this->block);
            }
        }

        return $this->ctaList;
    }

    /**
     * @return string
     */
    public function getArticleTitle()
    {
        return $this->block->getZoneTitre3();
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->block->getZoneTitre();
    }

    /**
     * @return mixed
     */
    public function getSubtitle()
    {
        return $this->block->getZoneTitre2();
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->block->getZoneParameters();
    }

    /**
     * @param $slideshow
     *
     * @return $this
     */
    public function setSlideshow($slideshow)
    {
        $this->slideshow = $slideshow;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlideshow()
    {
        if (empty($this->slideshow)) {
            $this->initSlideShow();
        }

        return $this->slideshow;
    }

    /**
     * Get numberOfColumns.
     *
     * @return mixed
     */
    public function getNumberOfColumns()
    {
        $numberOfColumns = 0;

        if ($this->block->getZoneTool() === Pc51ColonneDataTransformer::ZONE_TOOL_1_COL) {
            $numberOfColumns = 1;
        }

        if ($this->block->getZoneTool() === Pc51ColonneDataTransformer::ZONE_TOOL_2_COL) {
            $numberOfColumns = 2;
        }

        return $numberOfColumns;
    }

    /***
     * @return string
     */
    public function getFirstColumn()
    {
        return $this->block->getZoneTexte();
    }

    /***
     * @return string
     */
    public function getSecondColumn()
    {
        return $this->block->getZoneTexte2();
    }

    /**
     * Init slideShow.
     */
    public function initSlideShow()
    {
        $this->ctaList = [];
        $slide = array();
        if ($this->block instanceof PsaPageZoneConfigurableInterface) {
            switch ($this->block->getZoneParameters()) {
                case PsaPageZoneMulti::PAGE_ZONE_MULTI_VALUE_SLIDE_IMAGE:
                    foreach ($this->block->getMultis() as $multi) {
                        $size = ['desktop' => self::DESKTOP_FORMAT,'mobile' => self::MOBILE_FORMAT];
                        $media = $this->mediaFactory->createFromMedia($multi->getMedia(), ['size' => $size, 'autoCrop' => true]);
                        $slide[] = $media;
                    }

                    break;

                case PsaPageZoneMulti::PAGE_ZONE_MULTI_VALUE_SLIDE_VIDEO:
                    $media = $this->mediaFactory->createFromMedia($this->block->getMedia());
                    $slide = $media;
                    break;

                case PsaPageZoneMulti::PAGE_ZONE_MULTI_VALUE_SLIDE_HTML5:
                    foreach ($this->block->getMultis() as $multi) {
                        $media = $this->mediaFactory->createFromMedia($multi->getMedia());
                        $slide[] = $media;
                    }
                    break;
            }
        }

        $this->slideshow = $slide;
    }
}
