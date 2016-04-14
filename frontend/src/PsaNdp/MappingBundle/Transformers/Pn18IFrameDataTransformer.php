<?php

namespace PsaNdp\MappingBundle\Transformers;
use PsaNdp\MappingBundle\Object\Block\Pn18IFrame;

/**
 * Data transformer for Pn18IFrame block
 */
class Pn18IFrameDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Pn18IFrame
     */
    protected $pn18IFrame;

    /**
     * @param Pn18IFrame $pn18IFrame
     */
    public function __construct(Pn18IFrame $pn18IFrame)
    {
        $this->pn18IFrame = $pn18IFrame;
    }

    /**
     *  Fetching data slice IFrame (pn18)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pn18IFrame->setDataFromArray($dataSource);
        $this->pn18IFrame->setMobile($isMobile);

        return array('slicePN18' => $this->pn18IFrame);
    }
}
