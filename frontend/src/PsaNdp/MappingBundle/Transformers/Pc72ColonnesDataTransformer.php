<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc72Colonnes;

/**
 * Data transformer for Pc72Colonnes block
 */
class Pc72ColonnesDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const FORMAT_STANDARD = 52;
    const FORMAT_MOBILE   = 43;


    /**
     * @var Pc72Colonnes
     */
    protected $pc72Colonnes;

    /**
     * @param Pc72Colonnes $pc72Colonnes
     */
    public function __construct(Pc72Colonnes $pc72Colonnes)
    {
        $this->pc72Colonnes = $pc72Colonnes;
    }

    /**
     *  Fetching data slice 2 Colonnes (pc7)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $dataSource['datalayer'] = '';
        $block = $dataSource['block'];

        $this->pc72Colonnes->setDataFromArray($dataSource);
        $this->pc72Colonnes->initColumn($block->getMultis());

        return array('slicePC7' => $this->pc72Colonnes);
    }
}
