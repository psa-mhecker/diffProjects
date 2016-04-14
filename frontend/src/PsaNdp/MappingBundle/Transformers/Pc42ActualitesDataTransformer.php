<?php

namespace PsaNdp\MappingBundle\Transformers;

/**
 * Data transformer for Pc42Actualites block
 */
class Pc42ActualitesDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     *  Fetching data slice Actualites (pc42)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     *
     * @todo traduction
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $result['slicePC42'] = array(
            'newsTitle' => 'titre de la tranche',
            'newsItems' => $this->getNewsItemsData($dataSource)
        );

        return $result;
    }

    /**
     * Get News Items data
     *
     * @param array $newsItems
     *
     * @return array
     */
    public function getNewsItemsData(array $newsItems)
    {
        $data = [];

        foreach ($newsItems as $newsItem) {
            $data[] = array(
                'img' => array(
                    'src' => $this->mediaServer.'/desktop/'.$newsItem['desktopMediaPath'],
                    'title' => $newsItem['desktopMediaTitle'],
                    'url' => $newsItem['desktopMediaPath']
                ),
                'description' => array(
                    'title' => $newsItem['contentTitle'],
                    'text' => $newsItem['contentText']
                ),
                'readMore' => 'lire l’article'
            );
        }

        return $data;
    }
}
