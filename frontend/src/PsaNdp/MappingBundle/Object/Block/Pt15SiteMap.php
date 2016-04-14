<?php

namespace PsaNdp\MappingBundle\Object\Block;

use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pt15SiteMap
 */
class Pt15SiteMap extends Content
{
    /**
     * @var array
     */
    protected $siteMap = array();

    /**
     * @return array
     */
    public function getSiteMap()
    {
        return $this->siteMap;
    }

    /**
     * @param array $siteMap
     *
     * @return $this
     */
    public function setSiteMap($siteMap)
    {
        $this->siteMap = $siteMap;
        if (!empty($siteMap['col'])) {
            $this->siteMap = $siteMap['col'];
        }

        return $this;
    }
}
