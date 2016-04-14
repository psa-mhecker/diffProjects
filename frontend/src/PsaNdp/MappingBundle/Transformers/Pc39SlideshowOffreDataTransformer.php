<?php

namespace PsaNdp\MappingBundle\Transformers;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface;

/**
 * Data transformer for Pc39SlideshowOffre block
 */
class Pc39SlideshowOffreDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    const FORMAT_CINE = 57;
    const FORMAT_3_VISUEL = 59;
    const FORMAT_MOBILE = 56;

    protected $format;

    /**
     *  Fetching data slice Slideshow Offre (pc39)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $data = [];
        $this->format = self::FORMAT_CINE;

        if ($isMobile) {
            $this->format = self::FORMAT_MOBILE;
            $data = $this->getMobileData($dataSource);
        }
        if (!$isMobile) {
            $data = $this->getDesktopData($dataSource);
        }

        $result = array(
            'slicePC39' => $data
        );

        return $result;
    }


    /**
     * @param array $dataSource
     *
     * @return array
     */
    public function getDesktopData(array $dataSource)
    {
        $data = [];
        $data['slides'] = [];

        if ($dataSource['slides3']) {
            $data['slides3'] = true;
            $this->format = self::FORMAT_3_VISUEL;
        }

        // Get data for each slides
        $data['slides'] = [];
        foreach ($dataSource['slides'] as $slide) {
            /** @var PsaPageZoneMultiConfigurableInterface $slide */

            $mediaArray = $this->getMediaDataInArray($slide);
            $slideData = array(
                'src' => $mediaArray['mediaPath'],
                'alt' => $mediaArray['mediaAlt'],
                'title' => $slide->getPageZoneMultiTitre(),
                'subTitle' => array(
                    'isGrey' => false,
                    'txt' => $slide->getPageZoneMultiTitre2(),
                ),
                'ctaList' => array(
                    array (
                        'style' => 'cta',
                        'class' => 'read-more',
                        'url' => $slide->getPageZoneMultiUrl(),
                        'target' => $slide->getPageZoneMultiValue(),
                        'title' => $slide->getPageZoneMultiLabel()
                    )
                )
            );

            $data['slides'][] = $slideData;
        }

        return $data;
    }

    /**
     * @param array $dataSource
     *
     * @return array
     */
    public function getMobileData(array $dataSource)
    {
        $data = [];
        $data['slideshowImgs'] = [];

        // Get data for each slides
        $data['slideshowImgs'] = [];
        foreach ($dataSource['slides'] as $slide) {
            /** @var PsaPageZoneMultiConfigurableInterface $slide */

            $mediaArray = $this->getMediaDataInArray($slide);
            $img = array (
                'title' => $slide->getPageZoneMultiTitre(),
                'subTitle' => array(
                    'isGrey' => false,
                    'txt' => $slide->getPageZoneMultiTitre2(),
                ),
                'src' => $mediaArray['mediaPath'],
                'alt' => $mediaArray['mediaAlt'],
                'url' => $slide->getPageZoneMultiUrl(),
                'target' => $slide->getPageZoneMultiValue(),
            );

            $data['slideshowImgs'][] = $img;
        }

        return $data;
    }

    /**
     * @param PsaPageZoneMultiConfigurableInterface $slide
     *
     * @return array
     */
    public function getMediaDataInArray(PsaPageZoneMultiConfigurableInterface $slide)
    {
        $result = [];
        $result['mediaPath'] = '';
        $result['mediaAlt'] = '';

        $media = $slide->getMedia();
        if ($media) {
            $result['mediaPath'] = $this->mediaServer . $media->getMediaPathWithFormat($this->format);
            $result['mediaAlt'] = $media->getMediaAlt();
        }

        return $result;
    }

}
