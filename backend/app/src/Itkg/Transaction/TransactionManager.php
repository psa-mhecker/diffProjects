<?php

namespace Itkg\Transaction;

require_once \Pelican::$config['LIB_ROOT'] . '/Pelican/File/file.lib.php';


use Doctrine\ORM\EntityManager;
use Itkg\Migration\Transaction\PsaShowroomEntityFactory;
use PSA\MigrationBundle\Entity\Cta\PsaCtaCtaReferenceInterface;
use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceInterface;
use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceOwnerInterface;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Media\PsaMediaAltTranslation;
use PSA\MigrationBundle\Entity\Media\PsaMediaDirectory;
use PSA\MigrationBundle\Entity\Media\PsaMediaType;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageVersion;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface;
use PSA\MigrationBundle\Entity\PsaRewrite;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Object\AbstractDoctrinePelicanObject;
use PSA\MigrationBundle\Repository\PsaMediaDirectoryRepository;
use PSA\MigrationBundle\Repository\PsaMediaRepository;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class TransactionManager
 */
class TransactionManager
{
    const SHOWROOM_ROOT_MEDIA_DIRECTORY = 'Showroom';
    const SHOWROOM_HOMEPAGE_MEDIA_DIRECTORY = 'Homepage';

    /** @var EntityManager */
    protected $em;
    /** @var \Pelican_Db  */
    protected $connectionPelican;
    /** @var array */
    protected $config;
    /** @var PsaEntityFactory  */
    protected $entityFactory;
    /** @var Filesystem */
    protected $fileSystem;

    /**
     * @param EntityManager $em
     * @param PsaEntityFactory $entityFactory
     */
    public function __construct(EntityManager $em, PsaEntityFactory $entityFactory)
    {
        $this->em = $em;
        $this->entityFactory = $entityFactory;
        $this->connectionPelican = \Pelican_Db::getInstance();
        //Initialize Pelican variable for media
        $this->config["MEDIA_ROOT"] = \Pelican::$config["MEDIA_ROOT"];
        $this->config["MEDIA_VAR"] = \Pelican::$config["MEDIA_VAR"];
        $this->config["HTTP_MEDIA"] = \Pelican::$config["HTTP_MEDIA"];
        $this->fileSystem = new Filesystem();
    }

    /************************************************************************************
     *                  Basic transaction management
     ************************************************************************************/

    /**
     * @param AbstractDoctrinePelicanObject $entity
     * @param boolean $insert
     */
    public function insertOrUpdate(AbstractDoctrinePelicanObject $entity, $insert)
    {
        $tableName = $entity->getTableName();
        // Save value before query
        $initialValue = \Pelican_Db::$values;

        \Pelican_Db::$values = $entity;
        if ($insert) {
            $this->connectionPelican->insertQuery($tableName);
        } else {
            $this->connectionPelican->updateQuery($tableName);
        }
        $this->connectionPelican->commit();

        // Reset value to initial state
        \Pelican_Db::$values = $initialValue;
    }

    /************************************************************************************
     *    Page transaction management : psa_page / psa_page_version
     ************************************************************************************/

    /**
     * @param PsaPage $page
     * @param string $parentPath
     * @param string $parentLibPath
     */
    public function savePageAndVersion(PsaPage $page, $parentPath, $parentLibPath)
    {
        $pageVersion = $page->getDraftVersion();

        // Save Page
        $this->insertOrUpdate($page, true);

        if ($pageVersion !== null) {
            // Save PageVersion
            $pageVersion->setPageId($page->getId());
            $this->insertOrUpdate($pageVersion, true);
            // Generate and Save Page Path and LibPath using generated Id
            $this->updateAndSavePagePath($page, $parentPath, $parentLibPath);

            // Save slices
            $this->saveSlices($pageVersion);

        }
        $this->saveRewrites($page);

    }

    /**
     * @param PsaPage $page
     *
     * @return $this
     */
    public function saveRewrites(Psapage $page)
    {
        $rewrites = $page->getRewrite();
        if ( !empty($rewrites)) {
            /** @var \PSA\MigrationBundle\Entity\PsaRewrite $rewrite */
            foreach ($rewrites as $rewrite) {
                // updating relation field linked to page because pageId is null when there were set
                $rewrite->setPageId($page->getPageId());
                $rewrite->setRewriteId($page->getPageId());
                $this->insertOrUpdate($rewrite, true);
            }
        }

        return $this;
    }
    /**
     * @param PsaPage $page
     * @param string $parentPath
     * @param string $parentLibPath
     */
    protected function updateAndSavePagePath(PsaPage $page, $parentPath, $parentLibPath)
    {
        $pagePath = $parentPath . "#" . $page->getPageId();
        $pageLibPath = $parentLibPath . "#" . $page->getPageId() . "|" . $page->getDraftVersion()->getPageTitleBo();

        $page->setPagePath($pagePath);
        $page->setPageLibpath($pageLibPath);
        $this->insertOrUpdate($page, false);
    }




    /************************************************************************************
     *    Slice transaction management : psa_page_zone / psa_page_multi_zone
     ************************************************************************************/

    /**
     * @param PsaPageVersion $pageVersion
     * @param bool $insert
     */
    public function saveSlices(PsaPageVersion $pageVersion, $insert = true)
    {
        $page = $pageVersion->getPage();
        // Save fix slices
        foreach($pageVersion->getBlocks() as $index => $block) {
            /** @var PsaPageZone $block */
            $block->setPage($page);
            $block->setPageVersion($pageVersion);

            $this->saveSliceNoCascade($block, $insert);

            $this->saveSliceMulti($block, $insert);
            $this->saveCtaReferences($block, $page->getId(), $insert);
        }

        // Save dynamic slices
        foreach($pageVersion->getDynamicPageBlocks() as $index => $block) {
            /** @var PsaPageMultiZone $block */
            $block->setPage($page);
            $block->setPageVersion($pageVersion);

            $this->saveSliceNoCascade($block, $insert);

            $this->saveSliceMulti($block, $insert);
            $this->saveCtaReferences($block, $page->getId(), $insert);
        }
    }

    /**
     * Save entitu without cascade savind media, cta or multi. It shoul be done outside
     *
     * @param PsaPageZoneConfigurableInterface $block
     * @param bool $insert
     * @param bool $removeUnsaveMedia
     */
    public function saveSliceNoCascade(PsaPageZoneConfigurableInterface $block, $insert = true, $removeUnsaveMedia = true)
    {
        if ($removeUnsaveMedia) {
            // Force removal of unsaved media (if no file could be downloaded, media has not been saved)
            if ($block->getMedia() !== null && $block->getMedia()->getMediaId() === null) {
                $block->setMedia(null);
            }
            if ($block->getMedia2() !== null && $block->getMedia2()->getMediaId() === null) {
                $block->setMedia2(null);
            }
            if ($block->getMedia3() !== null && $block->getMedia3()->getMediaId() === null) {
                $block->setMedia3(null);
            }
            // no object relation set for media 4 to 6
        }

        // Case of media 1 and 2 to copy media path and id in block
        if ($block->getMedia() !== null) {
            $block->setMediaId($block->getMedia()->getMediaId());
            $block->setMediaPath($block->getMedia()->getMediaPath());
        }
        if ($block->getMedia2() !== null) {
            $block->setMediaPath2($block->getMedia2()->getMediaPath());
        }

        $this->insertOrUpdate($block, $insert);
    }

    /**
     * @param PsaPageZoneConfigurableInterface $block
     * @param bool $insert
     * @param bool $removeUnsaveMedia
     */
    public function saveSliceMulti(PsaPageZoneConfigurableInterface $block, $insert = true, $removeUnsaveMedia = true)
    {
        $pageId = $block->getPage()->getId();
        $pageVersionId = $block->getPageVersion()->getPageVersion();

        $this->saveSliceMultiWithPageIds($block->getMultis(), $pageId, $pageVersionId, $insert, $removeUnsaveMedia);
    }


    /**
     * @param $multis
     * @param $pageId
     * @param $pageVersionId
     * @param bool $insert
     * @param bool $removeUnsaveMedia
     */
    public function saveSliceMultiWithPageIds($multis, $pageId, $pageVersionId, $insert = true, $removeUnsaveMedia = true)
    {
        foreach($multis as $multi) {
            /** @var PsaPageZoneMultiConfigurableInterface $multi */
            $multi->setPageId($pageId);
            $multi->setPageVersion($pageVersionId);

            if ($removeUnsaveMedia) {
                // Force removal of unsaved media (if no file could be downloaded, media has not been saved)
                if ($multi->getMedia() !== null && $multi->getMedia()->getMediaId() === null) {
                    $multi->setMedia(null);
                }
                if ($multi->getMediaId2() !== null && $multi->getMediaId2()->getMediaId() === null) {
                    $multi->setMediaId2(null);
                }
                if ($multi->getMediaId3() !== null && $multi->getMediaId3()->getMediaId() === null) {
                    $multi->setMediaId3(null);
                }
                if ($multi->getMediaId4() !== null && $multi->getMediaId4()->getMediaId() === null) {
                    $multi->setMediaId4(null);
                }
                if ($multi->getMediaId5() !== null && $multi->getMediaId5()->getMediaId() === null) {
                    $multi->setMediaId5(null);
                }
                if ($multi->getMediaId6() !== null && $multi->getMediaId6()->getMediaId() === null) {
                    $multi->setMediaId6(null);
                }
            }

            $this->saveCtaReferences($multi, $pageId, $insert);

            $this->insertOrUpdate($multi, $insert);
        }
    }

    /************************************************************************************
     *                  CTA transaction management
     ************************************************************************************/

    /**
     * @param PsaCtaReferenceOwnerInterface $ctaOwner
     * @param $pageId
     * @param bool $insert
     */
    public function saveCtaReferences(PsaCtaReferenceOwnerInterface $ctaOwner, $pageId, $insert = true)
    {
        foreach($ctaOwner->getCtaReferences() as $ctaReference) {
            /** @var PsaCtaReferenceInterface $ctaReference */
            $cta = $ctaReference->getCta();
            $this->insertOrUpdate($cta, $insert);

            $ctaReference->setPageId($pageId);
            $ctaReference->setCta($cta);

            $this->insertOrUpdate($ctaReference, $insert);

            // Save cta child for dropdown list case
            $this->saveCtaChildReferences($ctaReference, $pageId, $insert);
        }
    }


    private function saveCtaChildReferences(PsaCtaReferenceInterface $ctaReference, $pageId, $insert)
    {
        foreach($ctaReference->getChildCtas() as $childCtaReference) {
            /** @var PsaCtaCtaReferenceInterface $childCtaReference */
            $ctaChild = $childCtaReference->getCta();
            $this->insertOrUpdate($ctaChild, $insert);

            $childCtaReference->setPageId($pageId);
            $childCtaReference->setCta($ctaChild);

            $this->insertOrUpdate($childCtaReference, $insert);
        }
    }


    /************************************************************************************
     *                  MEDIA transaction management
     ************************************************************************************/

    /**
     * @return PsaMediaDirectory
     */
    public function findMediaDirectoryRoot()
    {
        /** @var PsaMediaDirectoryRepository $mediaDirectoryRepo */
        $mediaDirectoryRepo = $this->em->getRepository('PSA\MigrationBundle\Entity\Media\PsaMediaDirectory');
        /** @var PsaMediaDirectory $rootDirectory */
        $rootDirectory = $mediaDirectoryRepo->find(1);

        if ($rootDirectory === null) {
            throw new \RuntimeException("No media root folder found. Media cannot be save in media library.");
        }

        return $rootDirectory;
    }

    /**
     * @param $label
     * @param PsaSite $site
     * @param PsaMediaDirectory $parent
     *
     * @return PsaMediaDirectory
     */
    public function findOrCreateMediaDir($label, PsaSite $site, PsaMediaDirectory $parent = null)
    {
        /** @var PsaMediaDirectoryRepository $mediaDirectoryRepo */
        $mediaDirectoryRepo = $this->em->getRepository('PSA\MigrationBundle\Entity\Media\PsaMediaDirectory');
        $directory = $mediaDirectoryRepo->findFirstByLabelAndSiteAndParent($label, $site, $parent);

        if ($directory === null) {
            //Create a 'Showroom' directory for current site
            $directory = $this->entityFactory->createMediaDirectory($site, $label, $parent);
            $this->saveMediaDirectory($directory);
        }

        return $directory;
    }

    /**
     * @param PsaMediaDirectory $directory
     */
    public function saveMediaDirectory(PsaMediaDirectory $directory)
    {
        $directory->setMediaDirectoryPath($this->generateDirectoryPathRecursive($directory));

        $isNew = ($directory->getMediaDirectoryId() === null);
        $this->insertOrUpdate($directory, $isNew);
    }

    /**
     * @param PsaMediaDirectory $directory
     *
     * @return string
     */
    protected function generateDirectoryPathRecursive(PsaMediaDirectory $directory)
    {
        $result = $directory->getMediaDirectoryLabel();
        $parent = $directory->getMediaDirectoryParent();

        // Recursive call to generate path if have parent and parent is not the "Root" folder
        if ($parent !== null && $parent->getMediaDirectoryId() !== 1) {
            return $this->generateDirectoryPathRecursive($parent) . " > " . $result;
        } else {
            return $result;
        }

    }


    /**
     * @param PsaMedia $media
     * @param string|null $filename
     */
    public function saveMedia(PsaMedia $media, $filename = null)
    {
        $subfolder = $media->getMediaTypeId();
        $isNew = ($media->getMediaId() === null);

        // on enregistre une premiere fois le media avec un faux chemin  pour avoir son id
        if($isNew && (null !== $filename) && ($subfolder == PsaMedia::IMAGE)){
            $media->setMediaPath('fakepath');
            // Save Media
            $this->insertOrUpdate($media, $isNew);
            $isNew = false;
        }

        // Generate media path with right id
        $id = $media->getMediaId() ;
        if (null !== $filename) {
            $mediaPath = str_replace($this->config["MEDIA_VAR"],'',\Pelican_Media::fileName($filename, $id, $subfolder));
            $media->setMediaPath($mediaPath);
        }
        // Save Media
        $this->insertOrUpdate($media, $isNew);

        // Save alt/title translation
        $this->saveMediaAltTranslation($media->getMediaTranslation(), $isNew);
    }

    /**
     * @param PsaMediaAltTranslation $altTranslation
     * @param bool                   $isNew
     */
    public function saveMediaAltTranslation(PsaMediaAltTranslation $altTranslation = null, $isNew)
    {
        if ($altTranslation !== null &&
            $altTranslation->getMediaId() !== null &&
            $altTranslation->getMediaId()->getMediaId() !== null
        ) {
            $this->insertOrUpdate($altTranslation, $isNew);
        }
    }


    /**
     * @param PsaMedia $media
     * @param PsaMediaDirectory $mediaDirectory
     * @param PsaMediaType $mediaType
     * @param $sourceFilePath
     */
    public function saveMediaFromFilePath(PsaMedia $media, PsaMediaDirectory $mediaDirectory, PsaMediaType $mediaType, $sourceFilePath)
    {
        /** @var PsaMediaRepository $mediaRepository */
        $mediaRepository = $this->em->getRepository('PSA\MigrationBundle\Entity\Media\PsaMedia');
        $mediaTypeId = $mediaType->getMediaTypeId();

        // Check if media is already existing using md5 encoding on file
        $md5 = md5_file($sourceFilePath);
        $md5Media = $mediaRepository->findFirstByDirectoryAndTypeAndMd5($mediaDirectory, $mediaType, $md5);
        if ($md5Media !== null) {
            // Use existing media id
            $media->setMediaId($md5Media->getMediaId());
        }

        // Update media infos
        $mediaUrlInfos = parse_url($media->getMediaPath());
        $mediaFileName = basename($mediaUrlInfos["path"]);
        $media->setMediaMd5($md5);
        $media->setMediaTitle($mediaFileName);
        $media->setMediaDirectory($mediaDirectory);
        $media->setMediaWeight(filesize($sourceFilePath));
        if ($mediaTypeId === PsaShowroomEntityFactory::MEDIA_TYPE_IMAGE) {
            $imagesSize = getimagesize($sourceFilePath);
            if (false !== $imagesSize) {
                $media->setMediaWidth($imagesSize[0]);
                $media->setMediaHeight($imagesSize[1]);
            }
        }
        // Save media
        $this->saveMedia($media, $mediaFileName);

        // Copy dwd media to generated media path
        $destFile = $this->config["MEDIA_ROOT"] . $media->getMediaPath();
        $this->fileSystem->copy($sourceFilePath, $destFile, true);
    }

}
