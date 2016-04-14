<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pn13Anchor;

/**
 * Data transformer for Pn13Ancres block
 */
class Pn13AncresDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var Pn13Anchor
     */
    protected $pn13Anchor;

    /**
     * @param Pn13Anchor $pn13Anchor
     */
    public function __construct(Pn13Anchor $pn13Anchor)
    {
        $this->pn13Anchor = $pn13Anchor;
    }

    /**
     *  Fetching data slice Ancres (pn13)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pn13Anchor->setDataFromArray($dataSource);

        return array('slicePN13' => $this->pn13Anchor);
    }
}
