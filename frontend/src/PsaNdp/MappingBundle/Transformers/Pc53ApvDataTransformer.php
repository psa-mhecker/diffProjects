<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc53Apv;

/**
 * Data transformer for Pc53Apv block
 */
class Pc53ApvDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Pc53Apv
     */
    protected $pc53Apv;

    /**
     * @param Pc53Apv $pc53Apv
     */
    public function __construct(Pc53Apv $pc53Apv)
    {
        $this->pc53Apv = $pc53Apv;
    }

    /**
     *  Fetching data slice Apv (pc53)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {

        $this->pc53Apv->setDataFromArray($dataSource);

        $this->pc53Apv->setBlock($this->getBlock());

        return array(
            'slicePC53' => $this->pc53Apv
        );

    }

}
