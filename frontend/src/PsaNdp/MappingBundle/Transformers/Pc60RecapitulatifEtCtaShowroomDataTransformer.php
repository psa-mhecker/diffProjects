<?php
namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc60RecapitulatifEtCtaShowroom;
use PsaNdp\MappingBundle\Object\Block\Pn15EnteteConfishow;

/**
 * Class Pc60RecapitulatifEtCtaShowroomDataTransformer
 * Data transformer for Pc60RecapitulatifEtCtaShowroom block
 */
class Pc60RecapitulatifEtCtaShowroomDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Pc60RecapitulatifEtCtaShowroom
     */
    protected $pc60RecapitulatifEtCtaShowroom;

    /**
     * @param Pc60RecapitulatifEtCtaShowroom $pc60RecapitulatifEtCtaShowroom
     */
    public function __construct(Pc60RecapitulatifEtCtaShowroom $pc60RecapitulatifEtCtaShowroom)
    {
        $this->pc60RecapitulatifEtCtaShowroom = $pc60RecapitulatifEtCtaShowroom;
    }

    /**
     *  Fetching data slice RecapitulatifEtCtaShowroom (pc60)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $dataSource['translate'] = array(
            'peugeot' => $this->trans(Pn15EnteteConfishow::NDP_PEUGEOT),
            'new' => $this->trans(Pn15EnteteConfishow::NDP_NEW_CAR),
        );
        $this->pc60RecapitulatifEtCtaShowroom->setDataFromArray($dataSource);

        return array(
           'slicePC60' => $this->pc60RecapitulatifEtCtaShowroom,
        );
    }
}
