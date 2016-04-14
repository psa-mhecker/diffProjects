<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Cs99ConfigshowTest
 * @package PsaNdp\MappingBundle\Object\Block
 */
class Pc77DimensionVehicule extends Content
{
    const RATIO_VISUEL = 'NDP_MEDIA_16_9';
    const RATIO_VISUEL_MOBILE = 'NDP_MURMEDIA_SMALL_16_9';
    const RATIO_VISUEL_MINIATURE = 'NDP_MEDIA_DIMENSION_THUMBNAIL';

    /**
     * @var string
     */
    protected $legalNotice;

    /**
     * @var array
     */
    protected $slideshow;

    /**
     * @return string
     */
    public function getLegalNotice()
    {
        return $this->legalNotice;
    }

    /**
     * @param string $legalNotice
     */
    public function setLegalNotice($legalNotice)
    {
        $this->legalNotice = $legalNotice;
    }

    /**
     * @return array
     */
    public function getSlideshow()
    {
        return $this->slideshow;
    }

    /**
     * @param array $slideshow
     */
    public function setSlideshow($slideshow)
    {
        $this->slideshow = $slideshow;
    }

    public function init()
    {
        $this->slideshow = [];

        /** @var \PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface $multi */
        foreach ($this->getBlock()->getMultis() as $multi) {
            $size = ['desktop'=>self::RATIO_VISUEL,'mobile'=>self::RATIO_VISUEL_MOBILE];
            $sizeThumbnail = ['default'=>self::RATIO_VISUEL_MINIATURE];
            /** @var \PsaNdp\MappingBundle\Object\Image $item */
            $item = $this->mediaFactory->createFromMedia(
                $multi->getMedia(),
                array('size' => $size, 'autoCrop' => true)
            );
            $item->setSubtitle($multi->getPageZoneMultiText());
            // definition du thumbnail desktop par defaut
            /** @var  \PsaNdp\MappingBundle\Object\Image $thumbnail */
            $thumbnail = $this->mediaFactory->createFromMedia(
                $multi->getMedia(),
                array('size' => $sizeThumbnail, 'autoCrop' => true)
            );
            $media2 = $multi->getMediaId2();
            if(!empty($media2)) {
                $thumbnail = $this->mediaFactory->createFromMedia(
                    $media2,
                    array('size' => $sizeThumbnail, 'autoCrop' => true)
                );
            }
            $thumbnail->setSubtitle($multi->getPageZoneMultiTitre());
            $item->addThumbnail($thumbnail,'thumbnail');
            $this->slideshow[] = $item;
        }

        return $this;
    }
}
