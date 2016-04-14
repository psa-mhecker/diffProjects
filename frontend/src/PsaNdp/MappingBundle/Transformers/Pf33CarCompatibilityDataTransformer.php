<?php
namespace PsaNdp\MappingBundle\Transformers;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use \PsaNdp\MappingBundle\Object\Block\Pf33CarCompatibility;
use \PsaNdp\MappingBundle\Object\AbstractObject;

/**
 * Data transformer for Pf33CarCompatibility block
 */
class Pf33CarCompatibilityDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Pf33CarCompatibility
     */
    protected $pf33CarCompatibility;

    /**
     * @param Pf33CarCompatibility $pf33CarCompatibility
     */
    public function __construct(Pf33CarCompatibility $pf33CarCompatibility)
    {
        $this->pf33CarCompatibility = $pf33CarCompatibility;
    }

    /**
     *  Fetching data slice Car Compatibility (pf33)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        
          $this->pf33CarCompatibility->setTranslator($this->translator, $this->domain, $this->locale)
                            ->setDataFromArray($dataSource)
                            ->setLegend([
                                'deserie'=>$this->trans(Pf33CarCompatibility::NDP_SERIES),
                                'enoption'=>$this->trans(Pf33CarCompatibility::NDP_OPTIONAL),
                            ])
                            ->setTranslate(
                                array(
                                    'byfinition' => $this->trans(Pf33CarCompatibility::NDP_ACCORDING_FINISHING)
                                )
                            );

        
        return array(
            'slicePF33' => $this->pf33CarCompatibility,
        );

    }
}
