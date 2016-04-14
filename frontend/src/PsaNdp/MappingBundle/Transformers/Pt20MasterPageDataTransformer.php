<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pt20MasterPage;

/**
 * Data transformer for Pt20MasterPage block
 */
class Pt20MasterPageDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var
     */
    private $pt20;

    /**
     * Pt20MasterPageDataTransformer constructor.
     *
     * @param Pt20MasterPage $pt20
     */
    public function __construct(Pt20MasterPage $pt20)
    {
        $this->pt20 = $pt20;
    }

    /**
     * Fetching data for the pt20
     *
     * @param array $dataSource
     * @param bool  $isMobile
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pt20->setDataFromArray($dataSource);
        $this->pt20->setTranslate(['NDP_READ_MORE' => $this->trans('NDP_READ_MORE')]);
        $this->pt20->initData();

        return ['slicePT20' => $this->pt20];
    }

    /**
     * @return mixed
     */
    public function getPt20()
    {
        return $this->pt20;
    }
    
}
