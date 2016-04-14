<?php

namespace Itkg\Migration\Transaction;

use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use Itkg\Transaction\PsaEntityFactory;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\User\PsaUser;

/**
 * Class PsaShowroomEntityFactory
 * @package Itkg\Migration\Transaction
 */
class PsaShowroomEntityFactory extends PsaEntityFactory
{


    /**
     * @param ShowroomUrlManager            $urlManager
     * @param AddReportingMessageInterface  $reporting
     * @param string                        $articleVideo
     * @param string                        $articleVideoSubtitle
     *
     * @return null|PsaMedia
     */
    public function createStreamLikeMedia(
        ShowroomUrlManager $urlManager,
        AddReportingMessageInterface $reporting,
        $articleVideo,
        $articleVideoSubtitle
    )
    {
        $media = null;

        // Media image or video
        if ($articleVideo !== '') {
            $media = $this->createMedia(PsaShowroomEntityFactory::MEDIA_TYPE_STREAMLIKE, $urlManager->getUser());
            $media->setMediaRemoteId($articleVideo);
            // set a default creation user
            $media->setMediaCreationUser("import");
        }

        // Subtitle
        if ($articleVideoSubtitle !== '') {
            $reporting->addSrtUrl($urlManager->generateMediaUrl($articleVideoSubtitle));
        }

        return $media;
    }
    /**
     * @param ShowroomUrlManager            $urlManager
     * @param string                        $articleMedia
     * @param string                        $articleAltMedia
     *
     * @return null|PsaMedia
     */
    public function createImageMedia(
        ShowroomUrlManager $urlManager,
        $articleMedia,
        $articleAltMedia
    )
    {
        $media = null;

        // Media image or video
        if ($articleMedia !== '') {
            $media = $this->createMedia(PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE, $urlManager->getUser());
            // Save the url to dwd temporaly in media Path
            $media->setMediaPath($urlManager->generateMediaUrl($articleMedia));
            $media->setMediaTranslation(
                $this->createMediaAltTranslation($media, $urlManager->getLanguage(), $articleAltMedia)
            );
            // set a default creation user
            $media->setMediaCreationUser("import");
        }

        return $media;
    }

    /**
     * @param PsaSite $site
     * @param PsaLanguage $language
     * @param PsaUser $user
     * @param int $gabaritId
     * @param string $pageType
     *
     * @return PsaPageShowroomMetadata
     */
    public function createDraftPageWithMetadata(PsaSite $site, PsaLanguage $language, PsaUser $user, $gabaritId, $pageType)
    {
        $pageWithMetadata = new PsaPageShowroomMetadata();
        $page = $this->createDraftPage($site, $language, $user, $gabaritId);
        $pageWithMetadata->setPage($page);
        $pageWithMetadata->setPageType($pageType);

        return $pageWithMetadata;
    }
}
