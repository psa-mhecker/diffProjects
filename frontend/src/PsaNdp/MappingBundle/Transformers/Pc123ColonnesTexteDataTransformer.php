<?php

namespace PsaNdp\MappingBundle\Transformers;

use Doctrine\Common\Collections\Collection;
use PSA\MigrationBundle\Entity\Cta\PsaCta;
use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceInterface;
use PsaNdp\MappingBundle\Object\Block\Pc123ColonnesTexte;

/**
 * Data transformer for Pc123Colonnes block
 */
class Pc123ColonnesTexteDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const IMAGE_FORMAT = 55;
    const IMAGE_FORMAT_MOBILE = 54;

    /**
     * @var Pc123ColonnesTexte
     */
    protected $pc123Colonnes;

    /**
     * @param Pc123ColonnesTexte $pc123ColonnesTexte
     */
    public function __construct(Pc123ColonnesTexte $pc123ColonnesTexte)
    {
        $this->pc123Colonnes = $pc123ColonnesTexte;
    }

    /**
     *  Fetching data slice 3 Colonnes (pc12)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $block = $dataSource['block'];

        $this->pc123Colonnes->setDataFromArray($dataSource);
        $this->pc123Colonnes->initColumn($block->getMultis());

        return array('slicePC12' => $this->pc123Colonnes);
    }
}
