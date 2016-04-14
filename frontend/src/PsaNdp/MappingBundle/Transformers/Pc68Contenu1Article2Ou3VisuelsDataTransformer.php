<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc68Contenu1Article2Ou3Visuels;

/**
 * Data transformer for Pc68Contenu1Article2Ou3Visuels block
 */
class Pc68Contenu1Article2Ou3VisuelsDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const VISUELS_2 = '2_VISUELS';
    const VISUELS_3 = '3_VISUELS';
    const FORMAT_3VISUEL_STANDARD = 39;
    const FORMAT_2COLONE_STANDARD = 52;
    const FORMAT_3VISUEL_MOBILE = 38;
    const FORMAT_2COLONNE_MOBILE = 43;

    /**
     * @var Pc68Contenu1Article2Ou3Visuels
     */
    protected $pc68Contenu1Article2Ou3Visuels;

    /**
     * @param Pc68Contenu1Article2Ou3Visuels $pc68Contenu1Article2Ou3Visuels
     */
    public function __construct(Pc68Contenu1Article2Ou3Visuels $pc68Contenu1Article2Ou3Visuels)
    {
        $this->pc68Contenu1Article2Ou3Visuels = $pc68Contenu1Article2Ou3Visuels;
    }

    /**
     *  Fetching data slice Contenu 1 Article 2 ou 3 Visuels (pc68)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pc68Contenu1Article2Ou3Visuels->setDataFromArray($dataSource);
        $this->pc68Contenu1Article2Ou3Visuels->initArticleImg($isMobile);

        return array('slicePC68' => $this->pc68Contenu1Article2Ou3Visuels);
    }
}
