<?php

namespace PsaNdp\MappingBundle\Transformers;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface;
/**
 * Data transformer for Pn3ToggleAccordeon block
 */
class Pn3ToggleAccordeonDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const OPEN = 1;

    /**
     *  Fetching data slice Toggle Accordeon (pn3)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $result = [];

        foreach ($dataSource['block']->getMultis() as $multi) {
            /* @var $multi PsaPageZoneMultiConfigurableInterface */
            $item = [];
            $item['count'] = $multi->getPageZoneMultiValue();
            $item['title'] = $multi->getPageZoneMultiTitre();
            if ($multi->getPageZoneMultiMode() === self::OPEN) {
                $item['open'] = true ;
            }

            $result['faqSubCat'][] = $item;
        }



        return array('slicePN3'=>$result);
    }
}
