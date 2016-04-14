<?php

namespace PsaNdp\MappingBundle\Transformers;
use PSA\MigrationBundle\Entity\Cta\PsaCta;
use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;
use Doctrine\Common\Collections\Collection;
use PsaNdp\MappingBundle\Object\Block\Pc69Contenu2Colonnes;

/**
 * Data transformer for Pc69Contenu2Colonnes block
 */
class Pc69Contenu2ColonnesDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const IMAGE1_4 = 173;
    const IMAGE3_4 = 172;
    const SMALL_CLASS = 'small-bloc';
    const WIDE_CLASS = 'wide-bloc';
    const FORMAT_STANDARD = 46;
    const FORMAT_MINEUR_STANDARD = 50;
    const FORMAT_MOBILE = 43;
    const FORMAT_MINEUR_MOBILE = 49;

    /**
     * @var Pc69Contenu2Colonnes
     */
    protected $pc69Contenu;

    /**
     * @param Pc69Contenu2Colonnes $pc69Contenu2Colonnes
     */
    public function __construct(Pc69Contenu2Colonnes $pc69Contenu2Colonnes)
    {
        $this->pc69Contenu = $pc69Contenu2Colonnes;
    }

    /**
     *  Fetching data slice Contenu 2 Colonnes (pc69)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pc69Contenu->setDataFromArray($dataSource);
        $this->pc69Contenu->initColumns();

        return array('slicePC69' => $this->pc69Contenu);
    }
}
