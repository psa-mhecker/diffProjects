<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pf27CarPicker;

/**
 * Data transformer for Pf27CarPicker block
 */
class Pf27CarPickerDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var Pf27CarPicker
     */
    protected $pf27CarPicker;

    /**
     * @param Pf27CarPicker $pf27CarPicker
     */
    public function __construct(Pf27CarPicker $pf27CarPicker)
    {
        $this->pf27CarPicker = $pf27CarPicker;
    }

    /**
     *  Fetching data slice Car Picker (pf27)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $dataSource['translate'] = array(
            'NDP_DISCOVER' => $this->trans('NDP_DISCOVER'),
        );

        $this->pf27CarPicker->setTranslator($this->translator , $this->domain, $this->locale);
        $this->pf27CarPicker->setDataFromArray($dataSource);

        return array(
            'slicePF27' =>  $this->pf27CarPicker
        );
    }
}
