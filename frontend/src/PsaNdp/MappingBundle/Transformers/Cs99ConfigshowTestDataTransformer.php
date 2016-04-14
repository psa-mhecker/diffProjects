<?php
namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Cs99ConfigshowTest;

/**
 * Class Cs99ConfigshowTestDataTransformer
 * Data transformer for Cs99ConfigshowTest block
 * @package PsaNdp\MappingBundle\Transformers
 */
class Cs99ConfigshowTestDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Cs99ConfigshowTest
     */
    protected $cs99ConfigshowTest;

    /**
     * @param Cs99ConfigshowTest $cs99ConfigshowTest
     */
    public function __construct(Cs99ConfigshowTest $cs99ConfigshowTest)
    {
        $this->cs99ConfigshowTest = $cs99ConfigshowTest;
    }

    /**
     *  Fetching data slice configshow test (cs99)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->cs99ConfigshowTest->setDataFromArray($dataSource);

        return array(
           'sliceCS99' =>  $this->cs99ConfigshowTest
        );
    }
}
