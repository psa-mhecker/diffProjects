<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc9ContentWithOneVisual;


/**
 * Data transformer for Pc9Contenu1Article1Visuel block
 */
class Pc9Contenu1Article1VisuelDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const SLIDE_LEFT = 'left';
    const SLIDE_RIGHT = 'right';

    const MOBILE_FORMAT = 43;
    const SIZE_684_FORMAT = 33;
    const SIZE_600_FORMAT = 35;
    const MULTI_TYPE_684 = "VISUELS_684";

    private $pc9;

    public function __construct(Pc9ContentWithOneVisual $pc9)
    {
        $this->pc9 = $pc9;
    }

    /**
     *  Fetching data slice Contenu 1 Article 1 Visuel (pc9)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pc9->setBlock($this->getBlock());
        $this->pc9->initSlideShow();

        return array('slicePC9' => $this->pc9);
    }

}
