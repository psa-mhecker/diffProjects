<?php
namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc73MegaBanniereDynamique;

/**
 * Data transformer for Pc73MegaBanniereDynamique block
 */
class Pc73MegaBanniereDynamiqueDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Pc73MegaBanniereDynamique
     */
    protected $pc73MegaBanniereDynamique;

    /**
     * @param Pc73MegaBanniereDynamique $pc73MegaBanniereDynamique
     */
    public function __construct(Pc73MegaBanniereDynamique $pc73MegaBanniereDynamique)
    {
        $this->pc73MegaBanniereDynamique = $pc73MegaBanniereDynamique;
    }

    /**
     *  Fetching data slice Mega Banniere Dynamique (pc73)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {

        $pc73 = $this->pc73MegaBanniereDynamique->setDataFromArray($dataSource);

        return array(
            'slicePC73' => array(
                'imgUrl' => '../../../img/308-white2.png',
                'imgAlt' => 'Alt',
                'title' => 'Ne ratez pas les offres du moment',
                'link' => array(
                    'url' => '',
                    'target' => '_self',
                    'name' => 'Voir toutes les offres'
                ),
                'linkMega' => array(
                    'url' => '#',
                    'target' => '_self'
                )
            )
        );

    }
}
