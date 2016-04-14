<?php

namespace PsaNdp\MappingBundle\Transformers;

use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;
use PSA\MigrationBundle\Entity\Cta\PsaCta;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface;
use PsaNdp\MappingBundle\Object\Block\Pc51Colonne;

/**
 * Data transformer for Pc51Colonne block
 */
class Pc51ColonneDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const ZONE_TOOL_1_COL = '1_COL';
    const ZONE_TOOL_2_COL = '2_COL';

    private $pc51Colonne;

    public function __construct(Pc51Colonne $pc51Colonne)
    {
        $this->pc51Colonne = $pc51Colonne;
    }

    /**
     *  Fetching data slice 1 Colonne (pc5)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pc51Colonne->setDataFromArray($dataSource);
        $this->pc51Colonne->initSlideShow();

        return array(
            'slicePC5' => $this->pc51Colonne
        );
    }
}
