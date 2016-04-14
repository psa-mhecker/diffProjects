<?php

namespace PsaNdp\MappingBundle\Transformers;

/**
 * Data transformer for Pf32FiltreAPV block
 */
class Pf32FiltreAPVDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     *  Fetching data slice Filtre APV (pf32)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $result = [];

        return $result;
    }
}
