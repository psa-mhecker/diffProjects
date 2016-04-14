<?php

namespace PsaNdp\MappingBundle\Transformers;

/**
 * Data transformer for Pc61VisuelBoussole block
 */
class Pc61VisuelBoussoleDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     *  Fetching data slice Visuel Boussole (pc61)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $result = array(
            'visuelBoussoleTitle' => 'Titre de la tranche',
            'visuelBoussoleBox' => $this->getCompassBoxesSmartyData($dataSource),
            //@TODO: trad
            'visuelBoussoleLinkPlus' => 'En savoir plus'
        );

        return $result;
    }

    /**
     * Data Transformer for Compass Boxes
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function getCompassBoxesSmartyData(array $dataSource)
    {
        $smartyBoxes = [];

        foreach ($dataSource['pageZoneMultis'] as $dataSourceCompassBox) {
            $box = array(
                'title' => $dataSourceCompassBox['pageZoneMultiTitre'],
                'subtitle' => $dataSourceCompassBox['pageZoneMultiTitre2']
            );

            if ($dataSourceCompassBox['mediaPath']) {
                $box['file'] = $dataSource['mediaServer'].'/desktop/'.$dataSourceCompassBox['mediaPath'];
                $box['alt'] = $dataSourceCompassBox['mediaAlt'];
            }

            if ($dataSourceCompassBox['action']) {
                $box['link'] = $dataSourceCompassBox['action'];
            }

            $smartyBoxes[] = $box;
        }

        return $smartyBoxes;
    }
}
