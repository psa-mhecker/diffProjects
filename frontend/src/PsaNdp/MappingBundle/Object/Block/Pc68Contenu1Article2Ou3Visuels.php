<?php

namespace PsaNdp\MappingBundle\Object\Block;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class Pc68Contenu1Article2Ou3Visuels
 */
class Pc68Contenu1Article2Ou3Visuels extends Pc51Colonne
{
    const ONE_COLUMN = '1_COL';
    const TWO_COLUMN = '2_COL';
    const THREE_IMAGE = '3_VISUELS';
    const RATIO_VISUEL = 'NDP_MEDIA_ONE_ARTICLE_THREE_VISUAL';
    const RATIO_VISUEL_MOBILE = 'NDP_GENERIC_4_3_640';

    /**
     * @var array
     */
    protected $articleImg;

    /**
     * @return array
     */
    public function getArticleImg()
    {
        if (empty($this->articleImg)) {
            $this->initArticleImg();
        }

        return $this->articleImg;
    }

    /**
     * @param array $articleImg
     *
     * @return $this
     */
    public function setArticleImg(array $articleImg)
    {
        $this->articleImg = $articleImg;

        return $this;
    }

    /**
     * @return array
     */
    public function getCtaList()
    {
        if ($this->block instanceof ReadBlockInterface) {
            $this->initCtaListFromBlock($this->block);
        }

        return $this->ctaList;
    }

    /**
     * Get numberOfColumns
     *
     * @return integer
     */
    public function getNumberOfColumns()
    {
        $numberOfColumns = 0;

        if ($this->block->getZoneParameters()=== self::ONE_COLUMN) {
            $numberOfColumns =  1;
        }

        if ($this->block->getZoneParameters()=== self::TWO_COLUMN) {
            $numberOfColumns = 2;
        }

        return $numberOfColumns;
    }

    /**
     *
     * @return array
     */
    public function initArticleImg()
    {
        $result = array();
        $this->ctaList = array();
        if ($this->block instanceof PsaPageZoneConfigurableInterface) {

            $size = ['desktop'=>self::RATIO_VISUEL_MOBILE];

            $images[$this->block->getZoneAttribut()] = $this->block->getMedia();
            $images[$this->block->getZoneAttribut2()] = $this->block->getMedia2();

            if ($this->block->getZoneTool() === self::THREE_IMAGE) {
                $images[$this->block->getZoneAttribut3()] = $this->block->getMedia3();
                $size = ['desktop'=>self::RATIO_VISUEL, 'mobile'=>self::RATIO_VISUEL_MOBILE];
            }

            foreach ($images as $order => $media) {
                if (null !== $media && $media instanceof PsaMedia) {
                    $result[$order] = $this->mediaFactory->createFromMedia($media, array('size' => $size, 'autoCrop'=>true));
                }
            }

            ksort($result);
        }

        $this->setArticleImg($result);
    }
}
