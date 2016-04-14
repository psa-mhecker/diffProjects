<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc19Slideshow;

/**
 * Data transformer for Pc19Slideshow block
 */
class Pc19SlideshowDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @param Pc19Slideshow $pc19Slideshow
     */
    public function __construct(Pc19Slideshow $pc19Slideshow)
    {
        $this->pc19Slideshow = $pc19Slideshow;
    }

    /**
     *  Fetching data slice slideshow (pc19)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pc19Slideshow->setDataFromArray($dataSource);
        $this->pc19Slideshow->getSlides();

        return array(
            'slicePC19' => $this->pc19Slideshow
        );
    }

}
