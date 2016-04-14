<?php

namespace PsaNdp\MappingBundle\Transformers;

/**
 * Data transformer for Pt19Engagements block
 */
class Pt19EngagementsDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    protected $allMedia = true;

    /**
     *  Fetching data slice Engagements (pt19)
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
        $result = array(
            'slicePT19' => array(
                'commitmentsTitle' => $dataSource['block']->getZoneTitre(),
                'commitmentsPosts' => array()
            )
        );
        foreach ($dataSource['contents'] as $content) {
            if (!$content->getMedia()) {
                $this->allMedia = false;
                break;
            }
        }
        foreach ($dataSource['contents'] as $content) {
            $commitment  = array(
                'title' => $content->getContentTitle2(),
                'desc' => $content->getContentText(),
                'readMore' => array(
                    'title' => $this->trans('NDP_READ_MORE'),
                    'url' => $content->getContentUrl(),
                ),
            );

            if ($this->allMedia && $content->getMedia()) {
                $commitment['img'] = array(
                    'src' => $this->mediaServer . $content->getMedia()->getMediaPath(),
                    'alt' => $content->getMedia()->getMediaAlt(),
                );
            }
            $target = $content->getContentCode();

            if (!empty($target)) {
                $commitment['readMore']['target'] = $target;
            }

            $result['slicePT19']['commitmentsPosts'][] = $commitment;
        }

        return $result;
    }
}
