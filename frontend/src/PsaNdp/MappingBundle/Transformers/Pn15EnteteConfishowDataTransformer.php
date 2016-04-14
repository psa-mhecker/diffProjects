<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pn15EnteteConfishow;

/**
 * Class Pn15EnteteConfishowDataTransformer
 * Data transformer for Pn15EnteteConfishow block.
 */
class Pn15EnteteConfishowDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var Pn15EnteteConfishow
     */
    protected $pn15EnteteConfishow;

    /**
     * @param Pn15EnteteConfishow $pn15EnteteConfishow
     */
    public function __construct(Pn15EnteteConfishow $pn15EnteteConfishow)
    {
        $this->pn15EnteteConfishow = $pn15EnteteConfishow;
    }

    /**
     *  Fetching data slice entete confishow (pn15).
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $dataSource['translate']['peugeot'] = $this->trans(Pn15EnteteConfishow::NDP_PEUGEOT);
        $dataSource['translate']['new'] = $this->trans(Pn15EnteteConfishow::NDP_NEW_CAR);
        $pn15 = $this->pn15EnteteConfishow->setDataFromArray($dataSource);
        $pn15->init();

        return array(
           'slicePN15' => $pn15,
        );
    }
}
