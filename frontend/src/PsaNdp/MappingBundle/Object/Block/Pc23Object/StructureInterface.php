<?php

namespace PsaNdp\MappingBundle\Object\Block\Pc23Object;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface;

interface StructureInterface
{
    const NDP_WIDESCREEN = 'NDP_WIDESCREEN';
    const NDP_SQUARE = 'NDP_SQUARE';

    /**
     * @return array
     */
    public function getFormats();

    /***
     * @return string
     */
    public function getName();

    /***
     * @return string
     */
    public function getLabel();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return int
     */
    public function countImages();

    /**
     * @return array
     */
    public function getImages();

    /**
     * @param array $images
     *
     * @return $this
     */
    public function setImages(array $images);

    /**
     * @param PsaPageZoneMultiConfigurableInterface $multi
     *
     * @return $this
     */
    public function init(PsaPageZoneMultiConfigurableInterface $multi);
}
