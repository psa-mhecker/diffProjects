<?php

namespace PsaNdp\MappingBundle\Utils;

use PSA\MigrationBundle\Repository\PsaMediaRepository;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PsaNdp\MappingBundle\Services\MediaServerInitializer;

class MurMediaMediaFinder
{
    /**
     * @var PsaMediaRepository
     */
    protected $mediaRepository;

    /**
     * @var array
     */
    protected $mediaList;

    /**
     * @var StreamlikeMedia
     */
    protected $streamLikeMedia;

    /**
     * @var MediaServerInitializer
     */
    protected $mediaServer;

    /**
     * @var array
     */
    protected $smallest;

    /**
     * MurMediaMediaFinder constructor.
     *
     * @param PsaMediaRepository     $mediaRepository
     * @param MediaServerInitializer $mediaServer
     * @param StreamlikeMedia        $streamLikeMedia
     */
    public function __construct(PsaMediaRepository $mediaRepository, MediaServerInitializer $mediaServer, StreamlikeMedia $streamLikeMedia)
    {
        $this->mediaRepository = $mediaRepository;
        $this->mediaServer = $mediaServer;
        $this->streamLikeMedia = $streamLikeMedia;
    }

    /**
     * @return array
     */
    public function getMediaList()
    {
        return $this->mediaList;
    }

    /**
     * @return array
     */
    public function getSmallest()
    {
        return $this->smallest;
    }

    /**
     * @param array $smallest
     */
    public function setSmallest($smallest)
    {
        $this->smallest = $smallest;
    }

    /**
     * @param array $blocks
     *
     * @return $this
     */
    public function addMedias(array $blocks)
    {
        foreach ($blocks as $block) {
            $this->testMediaOfBlock($block);
        }

        return $this;
    }
    /**
     * @param array $block
     *
     * @return $this
     */
    public function testMediaOfBlock(array $block)
    {
        $mediaIds = [];
        $mediaIds[] = $block['MEDIA_ID'];
        $mediaIds[] = $block['MEDIA_ID2'];
        $mediaIds[] = $block['MEDIA_ID3'];
        $mediaIds[] = $block['MEDIA_ID4'];
        $mediaIds[] = $block['MEDIA_ID5'];
        $mediaIds[] = $block['MEDIA_ID6'];
        $mediaIds[] = $block['MEDIA_ID7'];
        $mediaIds[] = $block['MEDIA_ID8'];
        $mediaIds[] = $block['MEDIA_ID9'];
        $mediaIds[] = $block['MEDIA_ID10'];
        $key = array_keys($mediaIds);
        $size = sizeOf($key);

        for ($i = 0; $i < $size; ++$i) {
            if (!in_array($mediaIds[$i], $this->mediaList) && !empty($mediaIds[$i])) {
                $this->mediaList[] = $mediaIds[$i];
            }
        }

        return $this;
    }

    private function filterSmallMedias($medias) {

        $smallest = $this->getSmallest();
        if (!empty($medias)) {
            foreach ($medias as $idx=>$media) {
                if( $media->getMediaTypeId() == PsaMedia::IMAGE && (
                    $media->getMediaWidth() < $smallest['MEDIA_FORMAT_WIDTH'] ||
                    $media->getMediaHeight() < $smallest['MEDIA_FORMAT_HEIGHT']
                    )
                ) {
                    unset($medias[$idx]);
                }
            }
        }

        return $medias;
    }
    /**
     * @return array
     */
    public function buildMedias()
    {


        if (empty($this->mediaList)) {
            return [];
        }

        $mediaCollection = $this->filterSmallMedias($this->mediaRepository->findByMediaIds($this->mediaList));
        $medias = [];
        $i = 0;

        /** @var PsaMedia  $media */
        foreach ($mediaCollection as $media) {
            $medias[$i] = [];
            $medias[$i]['MEDIA_ID'] = $media->getMediaId();
            // on ne traite pas les videos sur NDP
            if ($media->getMediaTypeId() != PsaMedia::VIDEO) {
                //set du path img
                $medias[$i]['MEDIA_PATH'] = $medias[$i]['MEDIA_URL'] = $this->mediaServer->getMediaServer().$media->getMediaPath();
                $medias[$i]['MEDIA_HEIGHT'] = $media->getMediaHeight();
                $medias[$i]['MEDIA_WIDTH'] = $media->getMediaWidth();
                //set du title et du alt
                $medias[$i]['MEDIA_TITLE'] = $media->getMediaTitle();
                $medias[$i]['MEDIA_ALT'] = $media->getMediaAlt();
                $medias[$i]['MEDIA_TYPE_ID'] = $media->getMediaTypeId();
                //si le media est de type streamlike on lui donne un pour la cover

                if ($media->getMediaTypeId() == PsaMedia::STREAMLIKE) {
                    $streamlikeData = $this->streamLikeMedia->get($media->getMediaRemoteId());
                    if (empty($streamlikeData['poster'])) {
                        $sql = 'SELECT m.MEDIA_PATH FROM #pref#_media m INNER JOIN #pref#_site s ON m.MEDIA_ID=s.STREAMLIKE_DEFAULT_COVER WHERE s.SITE_ID = '.$_SESSION[APP]['SITE_ID'];
                        $post  =\Pelican_Db::getInstance()->queryItem($sql,[]);
                        $streamlikeData['poster'] =  \Pelican::$config['MEDIA_HTTP'].$post;
                    }
                    $medias[$i]['MEDIA_TITLE'] = $streamlikeData['title'];
                    $medias[$i]['MEDIA_PATH'] = $streamlikeData['poster'];
                    $medias[$i]['MEDIA_URL'] = $streamlikeData['url'];
                }
                ++$i;
            }
        }

        return $medias;
    }
}
