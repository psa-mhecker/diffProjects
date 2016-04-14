<?php

namespace PsaNdp\MappingBundle\Utils;

/**
 * Class AnchorUtils
 * @package PsaNdp\MappingBundle\Utils
 */
class AnchorUtils
{
    /**
     * Format the id to be generated as an anchor. Isobar js doesn't work with a '.' in the string.
     * Replace '.' with '_';
     *
     * @param string $permanentId ex : '2972.1.290.121.801.5.132456'
     *
     * @return string ex : '2972_1_290_121_801_5_132456'
     *
     */
    public function formatAnchorId($permanentId)
    {
        return str_replace(".", "_", $permanentId);
    }
}
