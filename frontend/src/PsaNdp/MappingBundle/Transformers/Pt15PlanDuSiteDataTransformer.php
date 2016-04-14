<?php

namespace PsaNdp\MappingBundle\Transformers;
use PsaNdp\MappingBundle\Manager\TreeManager;
use PsaNdp\MappingBundle\Object\Block\Pt15SiteMap;

/**
 * Data transformer for Pt15PlanDuSite block
 */
class Pt15PlanDuSiteDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var Pt15SiteMap
     */
    protected $pt15SiteMap;

    /**
     * Constructor
     *
     * @param Pt15SiteMap $siteMap
     */
    public function __construct(Pt15SiteMap $siteMap)
    {
        $this->pt15SiteMap = $siteMap;
    }

    /**
     *  Fetching data slice Plan du Site (pt15)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pt15SiteMap->setDataFromArray($dataSource);

        return array('SlicePT15' => $this->pt15SiteMap);
    }
}
