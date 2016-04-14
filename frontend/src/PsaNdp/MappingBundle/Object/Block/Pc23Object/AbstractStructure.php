<?php

namespace PsaNdp\MappingBundle\Object\Block\Pc23Object;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface;
use PsaNdp\MappingBundle\Object\Content;

abstract class AbstractStructure extends Content implements StructureInterface
{
    const NDP_MURMEDIA_SMALL_16_9 = 'NDP_MURMEDIA_SMALL_16_9';
    const NDP_MURMEDIA_BIG_16_9 = 'NDP_MURMEDIA_BIG_16_9';
    const NDP_MURMEDIA_SMALL_SQUARE = 'NDP_MURMEDIA_SMALL_SQUARE';
    const NDP_MURMEDIA_BIG_SQUARE = 'NDP_MURMEDIA_BIG_SQUARE';

    /**
     * @var array
     */
    protected $images;

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param array $images
     *
     * @return $this
     */
    public function setImages(array $images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return int
     */
    public function countImages()
    {
        return count($this->getFormats());
    }

    public function init(PsaPageZoneMultiConfigurableInterface $multi)
    {

        /* @var \PSA\MigrationBundle\Entity\Page\PsaPageMultiZoneMulti $multi */

        foreach ($this->getFormats() as $format) {
            $media = $multi->{$format['method']}();
            $image = $this->mediaFactory->createFromMedia($media, array('size' => $format['size'], 'autoCrop' => true));
            $this->images[] = $image;
        }
    }
}
