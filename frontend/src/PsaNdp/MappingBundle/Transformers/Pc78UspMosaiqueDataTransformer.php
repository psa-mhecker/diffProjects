<?php
namespace PsaNdp\MappingBundle\Transformers;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Block\Pc78UspMosaique;

/**
 * Data transformer for Pc78UspMosaique block
 */
class Pc78UspMosaiqueDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    const MULTI_TYPE = 'USP_MOSAIQUE';

    /**
     * @var Pc78UspMosaique
     */
    protected $pc78UspMosaique;

    /**
     * @param Pc78UspMosaique $pc78UspMosaique
     */
    public function __construct(Pc78UspMosaique $pc78UspMosaique)
    {
        $this->pc78UspMosaique = $pc78UspMosaique;
    }

    /**
     *  Fetching data slice usp mosaique (pc78)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        /** @var PsaPageZoneConfigurableInterface $block */
        $block = $dataSource['block'];

        $this->pc78UspMosaique->setDataFromArray($dataSource);
        $this->pc78UspMosaique->setTranslate(
            array(
                'close' => $this->trans(Pc78UspMosaique::NDP_CLOSE),
            )
        );
        $this->pc78UspMosaique->setArticles(
            $block->getMultisbyType(self::MULTI_TYPE),
            $this->mediaServer,
            $isMobile
        );

        return array(
           'slicePC78' =>  $this->pc78UspMosaique
        );
    }
}
