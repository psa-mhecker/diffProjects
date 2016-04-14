<?php

namespace Itkg\Migration\Transaction;

use Doctrine\ORM\EntityManager;
use Itkg\Manager\SignatureManager;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Reporting\DataMigrationReporting;
use Itkg\Transaction\TransactionManager;
use Itkg\Utils\FileManagerService;
use Itkg\Utils\ImageCompareUtils;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Media\PsaMediaDirectory;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Site\PsaSiteCode;
use PSA\MigrationBundle\Repository\PsaPageRepository;


/**
 * Class ShowroomTransactionManager
 */
class ShowroomTransactionManager extends TransactionManager
{
    /** @var FileManagerService */
    protected $fileManager;
    /** @var string */
    private $tmpDwdDirectory;

    /**
     * @var SignatureManager
     */
    private $signatureManager;


    /**
     * @param EntityManager $em
     * @param PsaShowroomEntityFactory $entityFactory
     * @param FileManagerService $fileManager
     * @param SignatureManager $signatureManager
     */
    public function __construct(
        EntityManager $em,
        PsaShowroomEntityFactory $entityFactory,
        FileManagerService $fileManager,
        SignatureManager $signatureManager)
    {
        parent::__construct($em, $entityFactory);
        $this->fileManager = $fileManager;
        $this->signatureManager = $signatureManager;
    }

    /**
     * @param PsaPageShowroomMetadata[] $multiLingualShowrooms
     * @param DataMigrationReporting    $reporting
     */
    public function saveShowrooms(array $multiLingualShowrooms, DataMigrationReporting $reporting)
    {
        $languageReferenceShowroom = null;

        foreach ($multiLingualShowrooms as $showroomPage) {
            /** PsaPageShowroomMetadata $showroomPage */
            if ($languageReferenceShowroom === null) {
                // Use first showroom as reference for created page id for other showroom in other language
                $languageReferenceShowroom = $showroomPage;
            } else {
                $this->copyPageIdReference($languageReferenceShowroom, $showroomPage);
            }

            // Create and dwd media files
            $this->saveShowroomMedias($showroomPage, $reporting);

            // Save pages and their slices
            $this->saveShowroomPages($showroomPage);

            // Post treatment after saving all object, update some information
            // Update CTA link
            $this->updateShowroomCtasWithMetadata($showroomPage);
        }
    }


    /**
     * Personalized saving for different slices post saving event result
     *
     * @param array $eventResult should contain at least 'block' key for the block calling the event
     *
     * @throws \Exception
     */
    public function saveSlicePostEvent($eventResult)
    {
        $block = $eventResult['block'];

        if ($block instanceof PsaPageMultiZone) {
            switch ($block->getZoneId()) {
                case $this->entityFactory->getBlockIdMapper()->getDynamicBlockZoneId('PN13'):
                    // Result from ShowroomXMLSlicePn13AnchorParser->postSavingCallableEvent
                    // Save slice data (without entity relational saving)
                    $this->saveSliceNoCascade($block, false);
                    // insert NEW multis and ctas created
                    $this->saveSliceMulti($block, true);
                    $this->saveCtaReferences($block, true);
                    break;
                case $this->entityFactory->getBlockIdMapper()->getDynamicBlockZoneId('PC23'):
                    // Result from ShowroomXMLSlicePc23MurMediaParser->postSavingCallableEvent
                    // Save slice data (without entity relational saving)
                    $this->saveSliceNoCascade($block, false);

                    // Save only new multis created
                    $this->saveSliceMultiWithPageIds(
                        $eventResult['newSlices'],
                        $block->getPageId(),
                        $block->getPageVersionNumber(),
                        true
                    );
                    break;
                default:
                    throw new \Exception(sprintf(
                        "No PostSavingEvent has been configured for dynamic block with zoneId: %s",
                        $block->getZoneId()
                    ));

            }
        } else if ($block instanceof PsaPageZone) {

                throw new \Exception(sprintf(
                    "No PostSavingEvent has been configured for static block with templateZoneId: %s",
                    $block->getZoneTemplateId()
                ));

        }
    }

    /**
     * Update cta url with pdf link to import in the 'mediathèque' and internal subpage link
     *
     * @param PsaPageShowroomMetadata $showroomHomepage
     */
    private function updateShowroomCtasWithMetadata(PsaPageShowroomMetadata $showroomHomepage)
    {
        // Update cta homepage
        $this->updateCtaActionWithMetadata($showroomHomepage, $showroomHomepage);

        foreach ($showroomHomepage->getSubPages() as $subPage) {
            $this->updateCtaActionWithMetadata($subPage, $showroomHomepage);
        }
    }

    /**
     * @param PsaPageShowroomMetadata $showroomPage
     * @param PsaPageShowroomMetadata $showroomHomepage
     */
    private function updateCtaActionWithMetadata(PsaPageShowroomMetadata $showroomPage, PsaPageShowroomMetadata $showroomHomepage)
    {
        // Update
        foreach($showroomPage->getCtasWithMetadata() as $ctaWithMetadata)
        {
            switch ($ctaWithMetadata->getXmLinkType()) {
                case PsaCtaReferentShowroomMetadata::LINK_TYPE_INTERNAL_PDF_MEDIA:
                    // Update link with uploaded PDF media url
                    $media = $ctaWithMetadata->getMedia();
                    if ($media && $media->getMediaId() !== null) {
                        $cta = $ctaWithMetadata->getCtaReferent()->getCta();
                        $cta->setAction($this->config["HTTP_MEDIA"] . $media->getMediaPath());
                        $this->insertOrUpdate($cta, false);
                    }
                    break;
                case PsaCtaReferentShowroomMetadata::LINK_TYPE_INTERNAL_PAGE:
                    // Update link with create url page and widget anchor
                    $targetPage = $showroomHomepage->getPageByXmlId($ctaWithMetadata->getXmlPageId());

                    if ($targetPage) {
                        $cta = $ctaWithMetadata->getCtaReferent()->getCta();
                        $url = $targetPage->getPage()->getDraftVersion()->getPageClearUrl();
                        $cta->setAction($url);
                        $this->insertOrUpdate($cta, false);
                    }
                    break;
                case PsaCtaReferentShowroomMetadata::LINK_TYPE_EXTERNAL:
                    // Do nothing
                    break;
            }
        }
    }

    /**
     * Save a new media in "Mediathèque" and dwd associated files when necessary
     *
     * @param PsaPageShowroomMetadata $showroomPage
     * @param DataMigrationReporting  $reporting
     */
    private function saveShowroomMedias(PsaPageShowroomMetadata $showroomPage, DataMigrationReporting $reporting)
    {
        // Recreate directories path and get showroom media directory for saving media
        $site = $showroomPage->getPage()->getSite();

        if ($site->getSiteId() === PsaSite::NDP_MASTER_SITE) {
            $showroomDirName = self::SHOWROOM_ROOT_MEDIA_DIRECTORY . ' Master';
        } else {
            $siteCodeRepository = $this->em->getRepository('PSA\MigrationBundle\Entity\Site\PsaSiteCode');
            /** @var PsaSiteCode $siteCode */
            $siteCode = $siteCodeRepository->find($site);
            $showroomDirName = self::SHOWROOM_ROOT_MEDIA_DIRECTORY . ' ' . $siteCode->getSiteCodePays();
        }
        $rootDir = $this->findMediaDirectoryRoot();
        $showroomRootMediaDir = $this->findOrCreateMediaDir($showroomDirName, $site, $rootDir);
        $showroomMediaDirName = $showroomPage->getShowroomId() . "-" . $showroomPage->getShowroomUrlKey();
        $showroomMediaDir = $this->findOrCreateMediaDir($showroomMediaDirName, $site, $showroomRootMediaDir);
        $reporting->addInfoMessage(
            sprintf(
                "Media has been saved in the Media library folder: '%s'.",
                $showroomMediaDir->getMediaDirectoryPath()
            )
        );

        $this->saveShowroomPageMediasRecursive($showroomPage, $site, $showroomMediaDir, $reporting);
    }

    /**
     * @param PsaPageShowroomMetadata $showroomPage
     * @param PsaSite                 $site
     * @param PsaMediaDirectory       $showroomMediaDir
     * @param DataMigrationReporting  $reporting
     */
    private function saveShowroomPageMediasRecursive(
        PsaPageShowroomMetadata $showroomPage,
        PsaSite $site,
        PsaMediaDirectory $showroomMediaDir,
        DataMigrationReporting $reporting
    )
    {
        // Save homepage medias
        $this->saveShowroomPageMedias($showroomPage, $site, $showroomMediaDir, $reporting);

        // Save sub pages medias
        foreach($showroomPage->getSubPages() as $subPage) {
            /** @var PsaPageShowroomMetadata $subPage */
            $this->saveShowroomPageMediasRecursive($subPage, $site, $showroomMediaDir, $reporting);
        }
    }

    /**
     * @param PsaPageShowroomMetadata       $showroomPage
     * @param PsaSite                       $site
     * @param PsaMediaDirectory             $parentMediaDir
     * @param AddReportingMessageInterface  $reporting
     */
    private function saveShowroomPageMedias(
        PsaPageShowroomMetadata $showroomPage,
        PsaSite $site,
        PsaMediaDirectory $parentMediaDir,
        AddReportingMessageInterface $reporting
    )
    {
        $this->signatureManager->setSiteId($site->getSiteId());
        if (count($showroomPage->getMedias()) > 0) {
            $page = $showroomPage->getPage();
            // Create Directory where media will be uploaded
            $pageMediaDirName = $showroomPage->getPageMediaDirName();
            $pageMediaDir = $this->findOrCreateMediaDir($pageMediaDirName, $site, $parentMediaDir);
            $pdfMediaDir = $this->findOrCreateMediaDir(PsaPageShowroomMetadata::SHOWROOM_PDF_MEDIA_DIRECTORY, $site, $parentMediaDir);

            // save media to directory
            foreach($showroomPage->getMedias() as $media) {
                $mediaType = $media->getMediaType();
                $mediaTypeId = $mediaType->getMediaTypeId();
                switch ($mediaTypeId) {
                    case (PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE):
                    case (PsaShowroomEntityFactory::MEDIA_TYPE_FILE):
                        // Dwd File in temp location
                        $tempFilePath = $this->downloadMedia(
                            $media->getMediaPath(),
                            $page->getSiteId() . '-' . $pageMediaDirName . '-' . $mediaTypeId,
                            $reporting
                        );

                        // Create Media in "Mediathèque"
                        if ($tempFilePath !== null) {
                            $mediaDirectory = ($mediaTypeId === PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE) ? $pageMediaDir : $pdfMediaDir;

                            $this->saveMediaFromFilePath($media, $mediaDirectory, $mediaType, $tempFilePath);
                            if ($mediaTypeId === PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE) {

                                $this->signatureManager->generateImageSignature($media);
                            }
                        }
                        break;
                    case (PsaShowroomEntityFactory::MEDIA_TYPE_STREAMLIKE):
                        // Save media
                        $media->setMediaDirectory(null);
                        $this->saveMedia($media, null);
                        break;
                }
            }
        }
    }


    /**
     * @return string
     */
    public function getTmpDwdDirectory()
    {
        if ($this->tmpDwdDirectory !== null) {
            return $this->tmpDwdDirectory;
        } else {
            return sys_get_temp_dir();
        }
    }

    /**
     * @param string $tmpDwdDirectory
     *
     * @return ShowroomTransactionManager
     */
    public function setTmpDwdDirectory($tmpDwdDirectory)
    {
        $this->tmpDwdDirectory = $tmpDwdDirectory;

        return $this;
    }

    /**
     * @param string                        $url
     * @param string                        $filename
     * @param AddReportingMessageInterface  $reporting
     *
     * @return null|string
     */
    private function downloadMedia($url, $filename, AddReportingMessageInterface $reporting)
    {
        $tempFilePath = null;

        // Dwd XML
        try {
            $tempFilePath = $this->fileManager->download(
                $url,
                $this->tmpDwdDirectory,
                $filename
            );
        } catch (\Exception $e) {
            $reporting->addWarningMessage(
                sprintf(
                    "Media file could not be downloaded for url: %s. Media was not imported. (Error exception : %s)",
                    $url, $e->getMessage()
                )
            );
        }

        return $tempFilePath;
    }

    /**
     * @param PsaPageShowroomMetadata $showroomPage
     */
    private function saveShowroomPages(PsaPageShowroomMetadata $showroomPage)
    {
        //** Save homepage and its version
        /** @var PsaPageRepository $pageRepository */
        $pageRepository = $this->em->getRepository('PSA\MigrationBundle\Entity\Page\PsaPage');
        /** @var PsaPage $homepage */
        $homepage = $showroomPage->getPage();
        $language = $homepage->getLangue();
        $site = $homepage->getSite();
        //Set order and parent
        $siteRootPage = $this->findSiteRootPageBySite($site, $language);
        $order = (int) $pageRepository->selectMaxOrderByParentPageIdAndLanguageAndSite(
            $siteRootPage['pageId'], $language, $site, 1
        );
        $order++;
        $homepage->setPageParentId($siteRootPage['pageId']);
        $homepage->setPageOrder($order);

        //Save homepage and its version
        $this->savePageAndVersion($homepage, $siteRootPage['pagePath'], $siteRootPage['pageLibpath']);

        //** Save subpages and its version
        $this->saveSubPages($showroomPage);
    }

    /**
     * @param PsaPageShowroomMetadata $parentPageWithMetadata
     */
    private function saveSubPages(PsaPageShowroomMetadata $parentPageWithMetadata)
    {
        $parentPage = $parentPageWithMetadata->getPage();

        //** Save subpages and its version
        foreach($parentPageWithMetadata->getSubPages() as $subIndex => $subPageWithMetadata) {
            /** @var PsaPageShowroomMetadata $subPageWithMetadata */
            //Set order and parent
            $subPage = $subPageWithMetadata->getPage();
            $subPage->setPageOrder($subIndex);
            $subPage->setPageParentId($parentPage->getId());

            //Save subpage and its version
            $this->savePageAndVersion($subPage, $parentPage->getPagePath(), $parentPage->getPageLibpath());

            if (count($subPageWithMetadata->getSubPages()) > 0) {
                $this->saveSubPages($subPageWithMetadata);
            }
        }
    }

    /**
     * @param PsaSite       $site
     * @param PsaLanguage   $language
     *
     * @return array
     */
    private function findSiteRootPageBySite(PsaSite $site, PsaLanguage $language)
    {
        /** @var PsaPageRepository $pageRepository */
        $pageRepository = $this->em->getRepository('PSA\MigrationBundle\Entity\Page\PsaPage');
        $siteRootPagesArray = $pageRepository->findSiteRootPageBySiteAndLanguage($site, $language);

        if (count($siteRootPagesArray) === 0) {
            throw new \RuntimeException(
                sprintf(
                    "Could not find website root page for language id %s and site %s",
                    $language->getLangueId(), $site->getSiteId()
                )
            );
        }

        return $siteRootPagesArray[0];
    }

    /**
     * Copy pageId from $showroomSource to page in $showroomPage
     * For subpage, The order of the subPages array is use as rule to copy the $showroomSource to the $showroomDestination
     *
     * @param PsaPageShowroomMetadata $showroomSource
     * @param PsaPageShowroomMetadata $showroomDestination
     */
    private function copyPageIdReference(PsaPageShowroomMetadata $showroomSource, PsaPageShowroomMetadata $showroomDestination)
    {
        $showroomDestination->getPage()->setPageId($showroomSource->getPage()->getId());

        $subPages = $showroomDestination->getSubPages();
        foreach ($showroomSource->getSubPages() as $index => $referenceSubPage) {
            if (isset($subPages[$index])) {
                $subPage = $subPages[$index];
                $subPage->getPage()->setPageId($referenceSubPage->getPage()->getId());
            }
        }
    }

}
