<?php

namespace PsaNdp\MappingBundle\Transformers;

/**
 * Data transformer for Pf16AutresReseauxSociaux block
 */
class Pf16AutresReseauxSociauxDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     *  Fetching data slice Autres Reseaux Sociaux (pf16)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $result = [];

        $pageZone = $dataSource['pageZone'];
        $socialNetworks = $dataSource['socialNetworks'];

        $result['slicePF16'] = array(
            'otherSocialMediaTitle' => $pageZone->getZoneTitre(),
            'otherSocialMediaFirstParagraph' => $pageZone->getZoneTexte(),
            'otherSocialMediaSecondParagraph' => $pageZone->getZoneTexte2(),
            'otherSocialMediaItem' => $this->getListSocialNetworkData($socialNetworks)
        );

        return $result;
    }

    /**
     * Data Transformer for social networks list
     *
     * @param Collection $socialNetworks
     *
     * @return array
     */
    public function getListSocialNetworkData($socialNetworks)
    {
        $data = [];

        foreach ($socialNetworks as $socialNetwork) {
            $data[] = array(
                'title' => $socialNetwork['label'],
                'url' => $socialNetwork['urlWeb'],
                'src' => $this->mediaServer.$this->getMediaPathWithFormat($socialNetwork['mediaPath'], 85)
            );
        }

        return $data;
    }

    /**
     * @param string $path
     * @param integer $format
     *
     * @return string
     */
    public function getMediaPathWithFormat($path, $format)
    {
        $arrayPath = explode('.', $path);
        $extension = array_pop($arrayPath);
        $pathFormat = implode('.', $arrayPath) . '.' . $format . '.' . $extension;

        return $pathFormat;
    }
}
