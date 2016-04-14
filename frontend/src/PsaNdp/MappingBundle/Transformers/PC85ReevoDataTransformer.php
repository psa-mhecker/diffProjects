<?php
namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\PC85Reevo;

/**
 * Class PC85ReevoDataTransformer
 * Data transformer for PC85Reevo block
 * @package PsaNdp\MappingBundle\Transformers
 */
class PC85ReevoDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var PC85Reevo
     */
    protected $pC85Reevo;

    /**
     * @param PC85Reevo $pC85Reevo
     */
    public function __construct(PC85Reevo $pC85Reevo)
    {
        $this->pC85Reevo = $pC85Reevo;
    }

    /**
     *  Fetching data slice Reevo (PC85)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {

        $PC85 = $this->pC85Reevo->setDataFromArray($dataSource);

        return array(
            'slicePC85' => array(
                'datalayer' => '',
                'translate' => array(
                    'title' => 'Les avis client',
                    'linkread' => 'Read 356 reviews',
                    'linkowner' => 'Ask the owner',
                    'description' => 'Un bon plus concernant Peugeot, plutôt assez satisfait en tout cas concernant la finition globale pour la Peugeot 308 hdi 90ch 2010, conso très raisonnable pour cette voiture de 1300 kg.',
                    'job' => 'chauffeur'
                ),
                'city' => 'Lens',
                'firstname' => 'Kevin',
                'carmodel' => 'Nouvelle 308 5 portes',
                'note' => array( '8', '5' ),
                'sub' => '/10',
                'link' => array(
                    'href' => '#'
                ),
                'logoreevoo' => array(
                    'src' => '../../../img/logoreevoo.png',
                    'alt' => 'by reevoo'
                )
            )
        );

    }
}
