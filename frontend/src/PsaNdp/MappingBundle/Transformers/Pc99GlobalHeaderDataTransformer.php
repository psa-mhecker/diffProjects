<?php

namespace PsaNdp\MappingBundle\Transformers;

/**
 * Data transformer for Pc99GlobalHeader block
 */
class Pc99GlobalHeaderDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     *  Fetching data slice Global Header (pc99)
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
