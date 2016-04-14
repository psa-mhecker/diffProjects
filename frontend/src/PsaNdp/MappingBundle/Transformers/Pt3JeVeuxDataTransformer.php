<?php

namespace PsaNdp\MappingBundle\Transformers;

use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Data transformer for Pt3JeVeux block
 */
class Pt3JeVeuxDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    public $format;
    /**
     *  Fetching data slice Je Veux (pt3)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
           $this->format= 90;
        if ($isMobile){
            $this->format= 7;
        }

        $colGroup = '3col';

        if (!empty($dataSource['col4'])) {
            $colGroup = '4col';
        }

        /* @var $pageZone PsaPageZoneConfigurableInterface */
        $pageZone = $dataSource['block'];

        $result = array(
            'slicePt3' => array(
                'MyPeugeot'   => $pageZone->getZoneTitre(),
                'toolsLinks'  => $pageZone->getZoneTitre(),
                'title'       => $pageZone->getZoneTitre2(),
                $colGroup     => $this->getColData($dataSource['cols'], $dataSource['col4'], $pageZone)
            )
        );

        return $result;
    }

    /**
     * Data Transformer for column
     *
     * @param array                            $cols
     * @param array                            $col4
     * @param PsaPageZoneConfigurableInterface $pageZone
     *
     * @return array
     */
    public function getColData(array $cols, $col4, $pageZone)
    {
        $result = [];

        $emptyMedia = $this->initiateMedia($cols);

        foreach ($cols as $col) {
            $col = $col->first();
            if (isset($col)) {
                $media = $col->getMedia();

                if ($emptyMedia) {
                    $media = null;
                }

                $result[] = $this->getCol(
                    $media,
                    $col->getPageZoneMultiTitre(),
                    $this->getColCtasData($pageZone->getCtaReferencesByType($col->getPageZoneMultiType() . '_CTA'))
                );
            }
        }

        $col4Media = $col4['media'];
        if (isset($col4Media) && $col4['quickAccess']) {
            $col4Media = $col4Media->first();

            $media = $col4Media->getMedia();

            if ($emptyMedia) {
                $media = null;
            }

            $result[] = $this->getCol(
                $media,
                $col4['quickAccess']->getZoneTitre2(),
                $this->getColCtasData($col4['quickAccess']->getCtaReferences())
            );
        }

        return $result;
    }

    /**
     * Data Transformer for column CTAs
     *
     * @param Collection $ctas
     *
     * @return array
     */
    public function getColCtasData($ctas)
    {
        $result = [];

        foreach ($ctas as $pageZoneCta) {
            $cta = $pageZoneCta->getCta();

            $data = array(
                'titleLink' => $cta->getTitle(),
                'target'    => $pageZoneCta->getTarget(),
                'url'       => $cta->getAction()
            );

            $result[] = $data;
        }

        return $result;
    }

    /**
     * Get column
     *
     * @param PsaMedia $media
     * @param $title
     * @param $listLinks
     * @return array
     */
    public function getCol($media, $title, $listLinks)
    {
        $mediaPath = '';
        $mediaAlt = '';

        if ($media) {
            $mediaPath = $this->mediaServer.$media->getMediaPathWithFormat($this->format);
            $mediaAlt = $media->getMediaAlt();
        }

        return array(
            'visu'       => array('src' => $mediaPath, 'alt' => $mediaAlt),
            'titleCol'  => $title,
            'listLinks' => $listLinks
        );
    }

    /**
     * initiate emptyMedia variable
     *
     * @param array $cols
     *
     * @return bool
     */
    public function initiateMedia($cols)
    {
        $emptyMedia = false;
        foreach ($cols as $col) {
            $col = $col->first();
            if (isset($col) && null == $col->getMedia()) {
                $emptyMedia = true;
            }
        }

        return $emptyMedia;
    }
}
