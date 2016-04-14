<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc59Tools;

/**
 * Data transformer for Pc59Tools block
 */
class Pc59ToolsDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var Pc59Tools
     */
    protected $tools;

    /**
     * @param Pc59Tools $tools
     */
    public function __construct(Pc59Tools $tools)
    {
        $this->tools = $tools;
    }

    /**
     *  Fetching data slice Tools (pc59)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->tools->setDataFromArray($dataSource);
        $this->tools->initCta();

        return array('slicePC59' => $this->tools);
    }
}
