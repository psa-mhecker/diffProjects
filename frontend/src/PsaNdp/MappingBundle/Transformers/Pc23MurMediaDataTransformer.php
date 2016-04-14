<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc23MurMedia;

/**
 * Data transformer for Pc23MurMedia block
 */
class Pc23MurMediaDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var Pc23MurMedia
     */
    protected $pc23MurMedia;

    /**
     * @param Pc23MurMedia $pc23MurMedia
     */
    public function __construct(Pc23MurMedia $pc23MurMedia)
    {
        $this->pc23MurMedia = $pc23MurMedia;
    }
    /**
     *  Fetching data slice Media wall (pc23)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pc23MurMedia->setDataFromArray($dataSource);
        $this->pc23MurMedia->init();

        return array('slicePC23' =>$this->pc23MurMedia);
    }

}
