<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc8Content2ColumnText;

/**
 * Data transformer for Pc8Contenu2ColonnesTexte block
 */
class Pc8Contenu2ColonnesTexteDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     * @var Pc8Content2ColumnText
     */
    protected $columnText;

    /**
     * @param Pc8Content2ColumnText $columnText
     */
    public function __construct(Pc8Content2ColumnText $columnText)
    {
        $this->columnText = $columnText;
    }

    /**
     *  Fetching data slice Contenu 2 Colonnes Texte (pc8)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $dataSource['mediaServer'] = $this->mediaServer;
        
        $this->columnText->setDataFromArray($dataSource);
        $this->columnText->initTwoColumn();

        return array('slicePC8' => $this->columnText);
    }
}
