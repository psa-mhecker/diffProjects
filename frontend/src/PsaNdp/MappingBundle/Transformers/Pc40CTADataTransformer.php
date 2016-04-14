<?php
namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pc40Cta;

/**
 * Data transformer for Pc40CTA block
 */
class Pc40CTADataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const FORMAT_STANDARD = 4;
    const FORMAT_MOBILE = 7;
    /**
     * @var Pc40Cta
     */
    private $pc40Cta;


    /**
     * @param Pc40Cta $pc40Cta
     */
    public function __construct(Pc40Cta $pc40Cta)
    {
        $this->pc40Cta = $pc40Cta;
    }

    /**
     * Fetching data slice CTA (pc40)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool $isMobile Indicate if is a mobile display
     *
     * @return array
     */

    public function fetch(array $dataSource, $isMobile)
    {
        $this->pc40Cta->setCtaList(array());
        $this->pc40Cta->setDataFromArray($dataSource);

        return array(
            'slicePC40' => $this->pc40Cta
        );
    }
}
