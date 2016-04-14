<?php

namespace PsaNdp\MappingBundle\Transformers;

/**
 * Data transformer for Pc43ApplicationsMobiles block
 */
class Pc43ApplicationsMobilesDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     *  Fetching data slice Applications Mobiles (pc43)
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
