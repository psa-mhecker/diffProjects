<?php

namespace PsaNdp\MappingBundle\Transformers;
use PsaNdp\MappingBundle\Object\Block\Pf42SelectionneurDeTeinte360;

/**
 * Data transformer for Pf42SelectionneurDeTeinte360 block
 */
class Pf42SelectionneurDeTeinte360DataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var Pf42SelectionneurDeTeinte360*
     */
    protected $pf42SelectionneurDeTeinte;

    /**
     * @param Pf42SelectionneurDeTeinte360 $pf42SelectionneurDeTeinte360
     */
    public function __construct(Pf42SelectionneurDeTeinte360 $pf42SelectionneurDeTeinte360)
    {
        $this->pf42SelectionneurDeTeinte = $pf42SelectionneurDeTeinte360;
    }

    /**
     *  Fetching data slice Selectionneur de Teinte 360 (pf42)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pf42SelectionneurDeTeinte->setDataFromArray($dataSource);

        //récupérer les mentions
        // NDP_PF42_LEGAL_MENTIONS
        $this->pf42SelectionneurDeTeinte->setMentions($this->trans('NDP_PF42_LEGAL_MENTIONS',array('%modelVersion%'=>$dataSource['modelVersion'])));

        $result = array('slicePF42' => $this->pf42SelectionneurDeTeinte);

        return $result;
    }
}
