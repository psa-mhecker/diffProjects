<?php

namespace PsaNdp\MappingBundle\Transformers;

use Doctrine\Common\Collections\ArrayCollection;
use PsaNdp\MappingBundle\Object\Block\Pt21NavigationData;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Data transformer for Pt21Navigation block
 */
class Pt21NavigationDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var Pt21NavigationData
     */
    protected $pt21NavigationData;

    /**
     * @param Pt21NavigationData $pt21NavigationData
     */
    public function __construct(Pt21NavigationData $pt21NavigationData)
    {
        $this->pt21NavigationData = $pt21NavigationData;
    }

    /**
     *  Fetching Navigation Data (pt21)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $dataSource['title'] = $this->trans('NDP_PEUGEOT');

        $dataSource['search'] = $this->trans('NDP_SEARCH');

        $dataSource['translate'] = array(
            'NDP_ALL_PEUGEOT' => $this->trans('NDP_ALL_PEUGEOT'),
            'NDP_BACK_TOP_PAGE' => $this->trans('NDP_BACK_TOP_PAGE'),
            'NDP_CLOSE' => $this->trans('NDP_CLOSE'),
            'NDP_MENU' => $this->trans('NDP_MENU'),
            'NDP_OK' => $this->trans('NDP_OK'),
            'NDP_PC38_RECHERCHER_SUR_LE_SITE' => $this->trans('NDP_PC38_RECHERCHER_SUR_LE_SITE'),
            'NDP_SHOW_MORE' => $this->trans('NDP_SHOW_MORE'),
        );

        $pt21 = $this->pt21NavigationData->setDataFromArray($dataSource);

        $pt21->init($dataSource, $this->mediaServer, $isMobile);

        return array(
            'slicePT21' =>  $pt21,
        );
    }
}
