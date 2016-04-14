<?php

namespace PsaNdp\MappingBundle\Transformers;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;

/**
 * Data transformer for Pn2Onglet block
 */
class Pn2OngletDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     *  Fetching data slice Onglet (pn2)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $result = [];


        foreach ($dataSource['multi'] as $multi) {
            $title = $multi->getPageZoneMultiTitre2();
            $titleDesktop = $multi->getPageZoneMultiTitre();
            if(!$isMobile && !empty($titleDesktop)) {
              $title = $titleDesktop;
            }
            /* @var $multi PsaPageZoneMulti */
            $item = [];
            $item['count'] = $multi->getPageZoneMultiValue(); // Temporaire a *2 cause des spans avant les sections
            $item['title'] = $title;

            $result['items'][] = $item;

        }

        return array('slicePN2' => $result);
    }
}
