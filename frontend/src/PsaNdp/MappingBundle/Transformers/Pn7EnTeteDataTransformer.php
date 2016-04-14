<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pn7EnTeteData;

/**
 * Data transformer for Pn7EnTete block
 */
class Pn7EnTeteDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Pn7EnTeteData
     */
    protected $Pn7EnTeteData;

    /**
     * @param Pn21UspFull $Pn7EnTete
     */
    public function __construct(Pn7EnTeteData $Pn7EnTeteData)
    {
        $this->Pn7EnTeteData = $Pn7EnTeteData;
    }

    /**
     *  Fetching data slice En Tete (pn7)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $dataSource['translate'] = array(
            'NDP_CLOSE' => $this->trans('NDP_CLOSE'),
        );

        $this->Pn7EnTeteData->setDataFromArray($dataSource);

        $this->Pn7EnTeteData->init();

        return array(
            'slicePN7' =>  $this->Pn7EnTeteData,
        );
    }


}
