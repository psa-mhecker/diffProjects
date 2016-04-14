<?php

namespace PsaNdp\MappingBundle\Transformers;

/**
 * Data transformer for Pc63LienBoussole block
 */
class Pc63LienBoussoleDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     *  Fetching data slice Lien Boussole (pc63)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $result['slicePC63'] = $this->getLinkCompassColsData($dataSource);

        return $result;
    }

    /**
     * Data Transformer for Link Compass Cols
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function getLinkCompassColsData(array $dataSource)
    {
        $cols = [];
        $dataCols = [];

        foreach ($dataSource['pageZoneMultis'] as $dataSourceLinkCompassCol) {
            if (count($dataSourceLinkCompassCol['ctaReferences']) > 0) {
                $col = array(
                    'titleCol' => $dataSourceLinkCompassCol['pageZoneMultiTitre'],
                    'list' => $this->getLinkCompassCtasData($dataSourceLinkCompassCol['ctaReferences'])
                );

                $dataCols[] = $col;
            }
        }

        $key = 'cols';

        if ($dataSource['desktop']) {
            $key .= count($dataCols);
        }

        $cols[$key] = $dataCols;

        return $cols;
    }

    /**
     * Data Transformer for Link Compass Ctas
     *
     * @param array $dataSourceCtaReferences
     *
     * @return array
     */
    public function getLinkCompassCtasData(array $dataSourceCtaReferences)
    {
        $ctas = [];

        foreach ($dataSourceCtaReferences as $dataSourceCtaReference) {
            $ctas[] = array(
                'title' => $dataSourceCtaReference['cta']['title'],
                'url' => $dataSourceCtaReference['cta']['action'],
                'target' => $dataSourceCtaReference['target'],
                'subtitle' => $dataSourceCtaReference['description'],
            );
        }

        return $ctas;
    }
}
