<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc77DimensionVehicule;

/**
 * Data transformer for Pc77DimensionVehicule block
 */
class Pc77DimensionVehiculeDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Pc77DimensionVehicule
     */
    protected $pc77DimensionVehicule;

    /**
     * @param Pc77DimensionVehicule $pc77DimensionVehicule
     */
    public function __construct(Pc77DimensionVehicule $pc77DimensionVehicule)
    {
        $this->pc77DimensionVehicule = $pc77DimensionVehicule;
    }

    /**
     *  Fetching data slice Dimension Vehicule (pc77)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {

       $this->pc77DimensionVehicule->setDataFromArray($dataSource)->init();

        return array('slicePC77' =>$this->pc77DimensionVehicule);
    }
}
