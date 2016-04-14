<?php

namespace PsaNdp\MappingBundle\Transformers;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Block\Pf44BecomeAgent;

/**
 * Data transformer for Pf44DevenirAgent block
 */
class Pf44DevenirAgentDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const NDP_PF44_LENGTH = 3;
    const NDP_PF44_BUSINESS_FOR_SALE = 1;
    const NDP_PF44_AVAILABLE_LOCATION = 2;
    const NDP_PF44_BUSINESS_FOR_SALE_VALUE = 'sale';
    const NDP_PF44_AVAILABLE_LOCATION_VALUE = 'agent';

    protected $pf44BecomeAgent;

    /**
     * @param Pf44BecomeAgent $agent
     */
    public function __construct(Pf44BecomeAgent $agent)
    {
        $this->pf44BecomeAgent = $agent;
    }

    /**
     *  Fetching data slice Devenir Agent (pf44)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $dataSource['or'] = $this->trans('NDP_OR');
        $dataSource['searchSubmit'] = $this->trans(Pf44BecomeAgent::NDP_OK);
        $dataSource['filterBy'] = $this->trans('NDP_FILTER_BY');
        $dataSource['length'] = self::NDP_PF44_LENGTH;
        $dataSource['placeholderInput'] = $this->trans('NDP_INDICATE_CITY_OR_POSTAL_CODE');
        $dataSource['btnAroundMe'] = $this->trans('NDP_AROUND_ME');
        $dataSource['mapParam'] = array(
            'picto' => 'http://www.hostingpics.net/thumbs/12/51/96/mini_125196pin.png',
            'pictoOn' => 'http://www.hostingpics.net/thumbs/58/37/85/mini_583785pinon.png',
            'pictoOff' => 'http://www.hostingpics.net/thumbs/72/43/39/mini_724339pinoff.png',
            'textLinkInfoWindow' => $this->trans('NDP_VIEW_DETAILED_SHEET')
        );
        $dataSource['translate'] = array(
            'seeMore' => $this->trans('NDP_VIEW_DETAILED_SHEET'),
            'loaderdatatxt' => $this->trans('NDP_GETTING_DATA'),
        );
        $dataSource['errorload'] = $this->trans('NDP_AJAX_LOADING_ISSUE');
        $dataSource['resultFound'] = $this->trans('NDP_RESULTS_FOUND');
        $dataSource['resultNotFound'] = $this->trans('NDP_NO_RESULT');
        $dataSource['visuMap'] = array(
            'src' => 'https://maps.googleapis.com/maps/api/staticmap?center=48.8727795,2.2988006&amp;zoom=17&amp;size=640x360',
            'alt' => $this->trans('NDP_PF44_GOOGLE_MAP')
        );
        $dataSource['pictoMap'] = 'http://www.hostingpics.net/thumbs/19/71/15/mini_197115pinlockm.png';
        $dataSource['beforeName'] = $this->trans('NDP_PF44_CONTACT');
        $dataSource['linkMoreInfo'] = array(
            'title' => $this->trans('NDP_READ_MORE'),
            'url' => '#'
        );
        $dataSource['linkMorInfo'] = array(
            'target' => ''
        );

        $this->pf44BecomeAgent->setDataFromArray($dataSource);
        /** @var PsaPageZoneConfigurableInterface $block */
        $block = $dataSource['block'];
        //FIXME Attente isobar confirmation des valeurs NDP_PF44_BUSINESS_FOR_SALE_VALUE et NDP_PF44_AVAILABLE_LOCATION_VALUE
        $this->pf44BecomeAgent->initListFilter(
            $block->getZoneParameters(),
            array(
                self::NDP_PF44_BUSINESS_FOR_SALE => self::NDP_PF44_BUSINESS_FOR_SALE_VALUE,
                self::NDP_PF44_AVAILABLE_LOCATION => self::NDP_PF44_AVAILABLE_LOCATION_VALUE
            ),
            array(
                self::NDP_PF44_BUSINESS_FOR_SALE => $this->trans('NDP_PF44_BUSINESS_FOR_SALE'),
                self::NDP_PF44_AVAILABLE_LOCATION => $this->trans('NDP_PF44_AVAILABLE_LOCATION')
            )
        );

        return array('slicePf44' => $this->pf44BecomeAgent);
    }
}