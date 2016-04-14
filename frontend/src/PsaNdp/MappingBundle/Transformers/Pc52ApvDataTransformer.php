<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc52Apv;

/**
 * Data transformer for Pc52Apv block
 */
class Pc52ApvDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Pc52Apv
     */
    protected $pc52Apv;

    /**
     * @param Pc52Apv $pc52Apv
     */
    public function __construct(Pc52Apv $pc52Apv)
    {
        $this->pc52Apv = $pc52Apv;
    }

    /**
     *  Fetching data slice Apv (pc52)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pc52Apv->setDataFromArray($dataSource);

        return array(
            'slicePC52' => $this->pc52Apv
        );

    }

}
