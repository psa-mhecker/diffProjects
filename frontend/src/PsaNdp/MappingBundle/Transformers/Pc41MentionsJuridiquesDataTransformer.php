<?php

namespace PsaNdp\MappingBundle\Transformers;
use PsaNdp\MappingBundle\Object\Block\Pc41MentionsJuridiques;

/**
 * Data transformer for Pc41MentionsJuridiques block
 */
class Pc41MentionsJuridiquesDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var Pc41MentionsJuridiques
     */
    protected $pc41;

    /**
     * @param Pc41MentionsJuridiques $pc41
     */
    public function __construct(Pc41MentionsJuridiques $pc41)
    {
        $this->pc41 = $pc41;
    }

    /**
     *  Fetching data slice Mentions Juridiques (pc41)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $pc41 = $this->pc41->setDataFromArray($dataSource);

        return array(
            'slicePC41' =>  $pc41
        );
    }
}
