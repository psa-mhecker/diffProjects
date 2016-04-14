<?php
namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pf23RangeBar;

/**
 * Data transformer for Pf23RangeBar block
 */
class Pf23RangeBarDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Pf23RangeBar
     */
    protected $pf23RangeBar;

    /**
     * @param Pf23RangeBar $pf23RangeBar
     */
    public function __construct(Pf23RangeBar $pf23RangeBar)
    {
        $this->pf23RangeBar = $pf23RangeBar;
    }

    /**
     *  Fetching data slice Range Bar (pf23)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pf23RangeBar->setDataFromArray($dataSource);

        return array(
            'slicePF23' => $this->pf23RangeBar,
        );

    }
}