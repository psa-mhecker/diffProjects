<?php
namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc84CatalogueApplications;

/**
 * Class Pc84CatalogueApplicationsDataTransformer
 * Data transformer for Pc84CatalogueApplications block
 * @package PsaNdp\MappingBundle\Transformers
 */
class Pc84CatalogueApplicationsDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var Pc84CatalogueApplications
     */
    protected $pc84CatalogueApplications;

    /**
     * @param Pc84CatalogueApplications $pc84CatalogueApplications
     */
    public function __construct(Pc84CatalogueApplications $pc84CatalogueApplications)
    {
        $this->pc84CatalogueApplications = $pc84CatalogueApplications;
    }

    /**
     *  Fetching data slice catalogue applications (pc84)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $dataSource['mediaServer'] = $this->mediaServer;
        $pc84 = $this->pc84CatalogueApplications->setDataFromArray($dataSource);
        $pc84->setTranslate([
            Pc84CatalogueApplications::NDP_COMPATIBLE_VEHICLES => $this->trans(Pc84CatalogueApplications::NDP_COMPATIBLE_VEHICLES),
            Pc84CatalogueApplications::NDP_DOWNLOAD_APPLICATION => $this->trans(Pc84CatalogueApplications::NDP_DOWNLOAD_APPLICATION),
            Pc84CatalogueApplications::NDP_COMPATIBLE_EXCLUSIVELY_WITH_FOLLOWING_VEHICLES => $this->trans(Pc84CatalogueApplications::NDP_COMPATIBLE_EXCLUSIVELY_WITH_FOLLOWING_VEHICLES),
            Pc84CatalogueApplications::NDP_IS_MY_VEHICLE_COMPATIBLE_WITH_THIS_SERVICE => $this->trans(Pc84CatalogueApplications::NDP_IS_MY_VEHICLE_COMPATIBLE_WITH_THIS_SERVICE),
            Pc84CatalogueApplications::NDP_CLOSE => $this->trans(Pc84CatalogueApplications::NDP_CLOSE),
        ]);
        $pc84->populate();

        return array(
            'slicePC84' =>  $pc84
        );
    }
}
