<?php

namespace Itkg\Transaction;

use Doctrine\ORM\EntityManager;
use Itkg\Utils\PsaDatabaseBlockIdMapper;
use PSA\MigrationBundle\Entity\Area\PsaArea;
use PSA\MigrationBundle\Entity\Content\PsaContentVersion;
use PSA\MigrationBundle\Entity\Cta\PsaContentVersionCta;
use PSA\MigrationBundle\Entity\Cta\PsaContentVersionCtaCta;
use PSA\MigrationBundle\Entity\Cta\PsaCta;
use PSA\MigrationBundle\Entity\Cta\PsaCtaCtaReferenceInterface;
use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceInterface;
use PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceOwnerInterface;
use PSA\MigrationBundle\Entity\Cta\PsaPageMultiZoneCta;
use PSA\MigrationBundle\Entity\Cta\PsaPageMultiZoneCtaCta;
use PSA\MigrationBundle\Entity\Cta\PsaPageMultiZoneMultiCta;
use PSA\MigrationBundle\Entity\Cta\PsaPageMultiZoneMultiCtaCta;
use PSA\MigrationBundle\Entity\Cta\PsaPageZoneCta;
use PSA\MigrationBundle\Entity\Cta\PsaPageZoneCtaCta;
use PSA\MigrationBundle\Entity\Cta\PsaPageZoneMultiCta;
use PSA\MigrationBundle\Entity\Cta\PsaPageZoneMultiCtaCta;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Media\PsaMediaAltTranslation;
use PSA\MigrationBundle\Entity\Media\PsaMediaDirectory;
use PSA\MigrationBundle\Entity\Media\PsaMediaType;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZoneMulti;
use PSA\MigrationBundle\Entity\Page\PsaPageVersion;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;
use PSA\MigrationBundle\Entity\PsaRewrite;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Template\PsaTemplatePage;
use PSA\MigrationBundle\Entity\User\PsaState;
use PSA\MigrationBundle\Entity\User\PsaUser;
use PSA\MigrationBundle\Entity\Zone\PsaZoneTemplate;
use \RuntimeException;

/**
 * Class PsaEntityFactory
 * @package Itkg\Transaction
 */
class PsaEntityFactory
{
    //TODO centraliser ces ids si possible
    const TEMPLATE_PAGE_ID_GABARIT_SHOWROOM_G27 = 1533;
    const TEMPLATE_PAGE_ID_GABARIT_MASTER_PAGE_G02 = 366;
    const TEMPLATE_PAGE_ID_GABARIT_TECHNOLOGY_G36 = 1539;
    const MEDIA_TYPE_IMAGE = 'image';
    const MEDIA_TYPE_FILE = 'file';
    const MEDIA_TYPE_STREAMLIKE = 'streamlike';
    const CTA_REFERENCE_STATUS_NEW_CTA = 3;
    const CTA_REFERENCE_STATUS_DROPDOWN_LIST = 4;
    const CTA_STYLE_DROPDOWN_LIST = 'style_niveau5';
    const CTA_REF_TYPE_LEVEL1 = 'LEVEL1_CTA';
    const CTA_REF_TYPE_LEVELCTA = 'NDP_LEVELCTA';


    /** @var EntityManager */
    protected $em;

    /** @var PsaDatabaseBlockIdMapper */
    protected $blockIdMapper;


    /**
     * @param EntityManager            $em
     * @param PsaDatabaseBlockIdMapper $blockIdMapper
     */
    public function __construct(
        EntityManager $em,
        PsaDatabaseBlockIdMapper $blockIdMapper
    )
    {
        $this->em = $em;
        $this->blockIdMapper = $blockIdMapper;
    }


    /**
     * @param PsaSite     $site
     * @param PsaLanguage $language
     * @param PsaUser     $user
     * @param int         $gabaritId
     *
     * @return PsaPage
     */
    public function createDraftPage(PsaSite $site, PsaLanguage $language, PsaUser $user, $gabaritId)
    {
        $date = new \DateTime();
        $creationUser = '#' . $user->getUserLogin() . '#';

        $page = new PsaPage();
        $page->setSite($site);
        $page->setSiteId($site->getSiteId());
        $page->setLangue($language);
        $page->setLangueId($language->getLangueId());
        $page->setPageCreationDate($date);
        $page->setPageCreationUser($creationUser);


        // Create Default Configuration Data
        $stateRepository = $this->em->getRepository('PSA\MigrationBundle\Entity\User\PsaState');
        /** @var PsaState $state */
        $state = $stateRepository->find(PsaState::PSA_STATE_ID_DRAFT);
        $templatePageRepository = $this->em->getRepository('PSA\MigrationBundle\Entity\Template\PsaTemplatePage');
        /** @var PsaTemplatePage $templatePage */
        $templatePage = $templatePageRepository->find($gabaritId);

        if (null === $templatePage) {
            throw new RuntimeException(
                sprintf('Failed to create new Page entity. No Gabarit found for Template Page Id : %d.', $gabaritId)
            );
        }

        // Create a first page version
        $pageVersion = new PsaPageVersion();
        $pageVersion->setPage($page);
        $pageVersion->setPageVersion(1);
        $pageVersion->setPageUrlExterneModeOuverture(1);
        $pageVersion->setLangue($language);
        $pageVersion->setLangueId($language->getLangueId());
        $pageVersion->setState($state);
        $pageVersion->setPageVersionCreationDate($date);
        $pageVersion->setPageVersionUpdateDate($date);
        $pageVersion->setPageVersionCreationUser($user->getUserLogin());
        $pageVersion->setPageVersionUpdateUser($user->getUserLogin());
        $pageVersion->setPageDisplay(1);
        $pageVersion->setPageDisplayNav(1);
        $pageVersion->setPageDisplayPlan(1);
        $pageVersion->setTemplatePage($templatePage);

        // Initialize Page with a first version
        $page->addPageVersion($pageVersion);
        $page->setDraftVersion($pageVersion);

        return $page;
    }

    /**
     * @param PsaPage $page
     * @param int     $zoneTemplateId
     *
     * @return PsaPageZone
     */
    public function createStaticBlock(PsaPage $page, $zoneTemplateId)
    {
        $block = new PsaPageZone();

        $block->setPage($page);
        $block->setLangue($page->getLangue());
        $block->setPageVersion($page->getDraftVersion());
        $block->setZoneTemplateId($zoneTemplateId);
        $block->setZoneWeb(1);
        $block->setZoneMobile(1);

        return $block;
    }


    /**
     * @param PsaPage $page
     * @param string  $sliceId ex: PC7
     *
     * @return PsaPageZone
     */
    public function createStaticBlockForSlideId(PsaPage $page, $sliceId)
    {
        $zoneTemplateId = $this->blockIdMapper->getStaticBlockZoneTemplateIdForPageVersion(
            $page->getDraftVersion(),
            $sliceId
        );

        return $this->createStaticBlock($page, $zoneTemplateId);
    }

    /**
     * @param PsaPage $page
     * @param int     $zoneOrder
     * @param int     $zoneId
     *
     * @return PsaPageMultiZone
     */
    public function createDynamicBlock(PsaPage $page, $zoneOrder, $zoneId)
    {
        $block = new PsaPageMultiZone();
        $area = $this->findDynamicAreaForGabariById($page->getDraftVersion()->getTemplateId());

        $block->setPage($page);
        $block->setLangue($page->getLangue());
        $block->setPageVersion($page->getDraftVersion());
        $block->setZoneOrder($zoneOrder);
        $block->setAreaId($area->getId());
        $block->setUid((string)uniqid());
        $block->setZoneId($zoneId);
        $block->setZoneWeb(1);
        $block->setZoneMobile(1);

        return $block;
    }


    /**
     * @param PsaPage $page
     * @param int     $zoneOrder
     * @param string  $sliceId ex: PC7
     *
     * @return PsaPageMultiZone
     */
    public function createDynamicBlockForSliceId(PsaPage $page, $zoneOrder, $sliceId)
    {
        $zoneId = $this->blockIdMapper->getDynamicBlockZoneId($sliceId);

        return $this->createDynamicBlock($page, $zoneOrder, $zoneId);
    }


    /**
     * @param PsaPageZoneConfigurableInterface $block
     * @param integer                          $multiId
     * @param string                           $multiType
     *
     * @return PsaPageZoneMulti|PsaPageMultiZoneMulti
     */
    public function createBlockMulti(PsaPageZoneConfigurableInterface $block, $multiId, $multiType = null)
    {
        switch (true) {
            case $block instanceof PsaPageZone:
                $blockMulti = new PsaPageZoneMulti();
                $zoneTemplate = $this->findZoneTemplateById($block->getZoneTemplateId());
                $blockMulti->setZoneTemplate($zoneTemplate);
                break;
            case $block instanceof PsaPageMultiZone:
                $blockMulti = new PsaPageMultiZoneMulti();
                $area = $this->findAreaById($block->getAreaId());
                $blockMulti->setZoneOrder($block->getZoneOrder());
                $blockMulti->setArea($area);
                break;
            default:
                throw new \RuntimeException(
                    sprintf( "Unknown block multi class to instantiate for block : %", get_class($block))
                );
        }

        $page = $block->getPage();
        $blockMulti->setPageId($page->getId());
        $blockMulti->setLangue($page->getLangue());
        $blockMulti->setPageVersion($page->getDraftVersion()->getPageVersion());
        $blockMulti->setPageZoneMultiType($multiType);
        $blockMulti->setPageZoneMultiId($multiId);

        return $blockMulti;
    }

    /**
     *
     * @param PsaPage $page
     * @param int     $zoneOrder
     * @param int     $multiId
     * @param string  $multiType
     *
     * @return PsaPageMultiZoneMulti
     */
    public function createDynamicBlockMulti(PsaPage $page, $zoneOrder, $multiId, $multiType = null)
    {
        $blockMulti = new PsaPageMultiZoneMulti();
        $area = $this->findDynamicAreaForGabariById($page->getDraftVersion()->getTemplateId());

        $blockMulti->setPageId($page->getId());
        $blockMulti->setLangue($page->getLangue());
        $blockMulti->setPageVersion($page->getDraftVersion()->getPageVersion());
        $blockMulti->setZoneOrder($zoneOrder);
        $blockMulti->setArea($area);
        $blockMulti->setPageZoneMultiType($multiType);
        $blockMulti->setPageZoneMultiId($multiId);

        return $blockMulti;
    }

    /**
     * @param int $areaId
     *
     * @return PsaArea
     */
    private function findAreaById($areaId)
    {
        $areaRepository = $this->em->getRepository('PSA\MigrationBundle\Entity\Area\PsaArea');
        /** @var PsaArea $area */
        $area = $areaRepository->find($areaId);

        if (null === $area) {
            throw new RuntimeException(
                sprintf('Failed to create new entity. No Area found for Area Id : %d.', $areaId)
            );
        }

        return $area;
    }

    /**
     * @param int $templatePageid
     *
     * @return PsaArea
     */
    private function findDynamicAreaForGabariById($templatePageid)
    {
        $repo = $this->em->getRepository('PSA\MigrationBundle\Entity\Area\PsaArea');
        /** @var \PSA\MigrationBundle\Entity\Area\PsaArea $area */
        $area = $repo->findOneDynamicAreaByTemplatePageId($templatePageid);

        if (null === $area) {
            throw new RuntimeException(
                sprintf('Failed to create new entity. No Dynamic Area found for Template Page Id : %d.', $templatePageid)
            );
        }


        return $area;
    }

    /**
     * @param int $zoneTemplateId
     *
     * @return PsaZoneTemplate
     */
    private function findZoneTemplateById($zoneTemplateId)
    {
        $zoneTemplateRepository = $this->em->getRepository('PSA\MigrationBundle\Entity\Zone\PsaZoneTemplate');
        /** @var PsaArea $area */
        $zoneTemplate = $zoneTemplateRepository->find($zoneTemplateId);

        if (null === $zoneTemplate) {
            throw new RuntimeException(
                sprintf('Failed to create new entity. No ZoneTemplate found for Zone Tempalate Id : %d.', $zoneTemplateId)
            );
        }

        return $zoneTemplate;
    }

    /**
     * @param string  $mediaTypeId
     * @param PsaUser $user
     *
     * @return PsaMedia
     */
    public function createMedia($mediaTypeId, PsaUser $user)
    {
        $date = new \DateTime();
        $media = new PsaMedia();
        $mediaTypeRepository = $this->em->getRepository('PSA\MigrationBundle\Entity\Media\PsaMediaType');
        /** @var PsaMediaType $mediaType */
        $mediaType = $mediaTypeRepository->find($mediaTypeId);

        if (null === $mediaType) {
            throw new RuntimeException(
                sprintf('Failed to create new Media entity. No Media Type found for id : %d.', $mediaTypeId)
            );
        }

        $media->setMediaType($mediaType);
        $media->setMediaCreationDate($date);
        $media->setMediaCreationUser($user->getUserLogin());

        return $media;
    }


    /**
     * @param PsaMedia    $media
     * @param PsaLanguage $language
     * @param string      $alt
     * @param string      $title
     *
     * @return PsaMediaAltTranslation
     *
     */
    public function createMediaAltTranslation(PsaMedia $media, PsaLanguage $language, $alt = '', $title = '')
    {
        $mediaAlt = new PsaMediaAltTranslation();

        $mediaAlt->setMediaId($media);
        $mediaAlt->setLanguage($language);
        $mediaAlt->setTitle($title);
        $mediaAlt->setAlt($alt);

        return $mediaAlt;
    }


    /**
     * @param PsaSite           $site
     * @param string            $label
     * @param PsaMediaDirectory $parent
     *
     * @return PsaMediaDirectory
     */
    public function createMediaDirectory(PsaSite $site, $label = null, PsaMediaDirectory $parent = null)
    {
        $directory = new PsaMediaDirectory();
        $directory->setSite($site);
        $directory->setMediaDirectoryLabel($label);
        $directory->setMediaDirectoryParent($parent);

        return $directory;
    }


    /**
     * @param PsaSite     $site
     * @param PsaLanguage $language
     * @param bool        $isRef
     *
     * @return PsaCta
     */
    public function createCta(PsaSite $site, PsaLanguage $language, $isRef = false)
    {
        $cta = new PsaCta();
        $cta->setSite($site);
        $cta->setLangue($language);
        $cta->setIsRef($isRef);
        $cta->setUsedCount(0);

        return $cta;
    }

    /**
     * @param PsaCtaReferenceOwnerInterface $referentOwner
     * @param PsaPage                       $page
     * @param PsaCta                        $cta
     * @param int                           $referenceId
     * @param string                        $referenceType
     * @param int                           $referenceOrder
     * @param int                           $referenceStatus
     * @param string                        $style
     *
     * @return PsaCtaReferenceInterface
     */
    public function createCtaReference(
        PsaCtaReferenceOwnerInterface $referentOwner, PsaPage $page, PsaCta $cta,
        $referenceId, $referenceOrder, $referenceStatus, $referenceType, $style
    )
    {
        $ctaReference = $this->createCtaReferenceWithReferentOwnerId($referentOwner, $page);

        $ctaReference->setCta($cta);
        $ctaReference->setReferenceId($referenceId);
        $ctaReference->setReferenceStatus($referenceStatus);
        $ctaReference->setReferenceType($referenceType);
        $ctaReference->setReferenceOrder($referenceOrder);
        $ctaReference->setStyle($style);

        return $ctaReference;
    }

    /**
     * @param PsaCtaReferenceOwnerInterface $referentOwner
     * @param PsaPage                       $page
     *
     * @return PsaCtaReferenceInterface
     */
    private function createCtaReferenceWithReferentOwnerId(PsaCtaReferenceOwnerInterface $referentOwner, PsaPage $page)
    {
        switch (true) {
            case $referentOwner instanceof PsaPageZone:
                $ctaReference = new PsaPageZoneCta();
                $ctaReference->setPageVersionNumber($referentOwner->getPageVersionNumber());
                $ctaReference->setZoneTemplateId($referentOwner->getZoneTemplateId());
                break;
            case $referentOwner instanceof PsaPageZoneMulti:
                $ctaReference = new PsaPageZoneMultiCta();
                $ctaReference->setPageVersionNumber($referentOwner->getPageVersion());
                $ctaReference->setZoneTemplateId($referentOwner->getZoneTemplate()->getZoneTemplateId());
                $ctaReference->setPageZoneMultiId($referentOwner->getPageZoneMultiId());
                $ctaReference->setPageZoneMultiType($referentOwner->getPageZoneMultiType());
                break;
            case $referentOwner instanceof PsaPageMultiZone:
                $ctaReference = new PsaPageMultiZoneCta();
                $ctaReference->setPageVersionNumber($referentOwner->getPageVersionNumber());
                $ctaReference->setAreaId($referentOwner->getAreaId());
                $ctaReference->setZoneOrder($referentOwner->getZoneOrder());
                break;
            case $referentOwner instanceof PsaPageMultiZoneMulti:
                $ctaReference = new PsaPageMultiZoneMultiCta();
                $ctaReference->setPageVersionNumber($referentOwner->getPageVersion());
                $ctaReference->setArea($referentOwner->getArea());
                $ctaReference->setZoneOrder($referentOwner->getZoneOrder());
                $ctaReference->setPageZoneMultiId($referentOwner->getPageZoneMultiId());
                $ctaReference->setPageZoneMultiType($referentOwner->getPageZoneMultiType());
                $ctaReference->setPageZoneMultiOrder($referentOwner->getPageZoneMultiOrder());
                break;
            case $referentOwner instanceof PsaContentVersion:
                $ctaReference = new PsaContentVersionCta();
                $ctaReference->setContentId($referentOwner->getContentId());
                $ctaReference->setContentVersionNumber($referentOwner->getContentVersion());
                break;
            default:
                throw new \RuntimeException(
                    sprintf( "Unknown Cta Referent class to instantiate for referent : %", get_class($referentOwner))
                );
                break;
        }

        $ctaReference->setReferenceOwner($referentOwner);
        $ctaReference->setPage($page);
        $ctaReference->setLangueId($page->getLangueId());

        return $ctaReference;
    }


    /**
     * @param PsaCtaReferenceOwnerInterface $referentOwner
     * @param PsaCtaReferenceInterface      $referentParent
     * @param PsaPage                       $page
     * @param PsaCta                        $cta
     * @param int                           $referenceId
     * @param int                           $referenceOrder
     * @param int                           $referenceStatus
     * @param string                        $referenceType
     * @param string                        $style
     *
     * @return PsaCtaCtaReferenceInterface
     */
    public function createCtaChildReference(
        PsaCtaReferenceOwnerInterface $referentOwner, PsaCtaReferenceInterface $referentParent, PsaPage $page,
        PsaCta $cta, $referenceId, $referenceOrder, $referenceStatus, $referenceType, $style
    )
    {
        $ctaReference = $this->createCtaChildReferenceWithReferentOwnerId($referentOwner, $page);

        $ctaReference->setParentCta($referentParent);
        $ctaReference->setReferenceParentId($referentParent->getReferenceId());
        $ctaReference->setReferenceParentType($referentParent->getReferenceType());

        $ctaReference->setCta($cta);
        $ctaReference->setReferenceId($referenceId);
        $ctaReference->setReferenceStatus($referenceStatus);
        $ctaReference->setReferenceType($referenceType);
        $ctaReference->setReferenceOrder($referenceOrder);
        $ctaReference->setStyle($style);

        return $ctaReference;
    }

    /**
     * @param PsaCtaReferenceOwnerInterface $referentOwner
     * @param PsaPage                       $page
     *
     * @return PsaCtaCtaReferenceInterface
     */
    private function createCtaChildReferenceWithReferentOwnerId(PsaCtaReferenceOwnerInterface $referentOwner, PsaPage $page)
    {
        switch (true) {
            case $referentOwner instanceof PsaPageZone:
                $ctaReference = new PsaPageZoneCtaCta();
                $ctaReference->setPageVersionNumber($referentOwner->getPageVersionNumber());
                $ctaReference->setZoneTemplateId($referentOwner->getZoneTemplateId());
                break;
            case $referentOwner instanceof PsaPageZoneMulti:
                $ctaReference = new PsaPageZoneMultiCtaCta();
                $ctaReference->setPageVersionNumber($referentOwner->getPageVersion());
                $ctaReference->setZoneTemplateId($referentOwner->getZoneTemplate()->getZoneTemplateId());
                $ctaReference->setPageZoneMultiId($referentOwner->getPageZoneMultiId());
                $ctaReference->setPageZoneMultiType($referentOwner->getPageZoneMultiType());
                break;
            case $referentOwner instanceof PsaPageMultiZone:
                $ctaReference = new PsaPageMultiZoneCtaCta();
                $ctaReference->setPageVersionNumber($referentOwner->getPageVersionNumber());
                $ctaReference->setAreaId($referentOwner->getAreaId());
                $ctaReference->setZoneOrder($referentOwner->getZoneOrder());
                break;
            case $referentOwner instanceof PsaPageMultiZoneMulti:
                $ctaReference = new PsaPageMultiZoneMultiCtaCta();
                $ctaReference->setPageVersionNumber($referentOwner->getPageVersion());
                $ctaReference->setArea($referentOwner->getArea());
                $ctaReference->setZoneOrder($referentOwner->getZoneOrder());
                $ctaReference->setPageZoneMultiId($referentOwner->getPageZoneMultiId());
                $ctaReference->setPageZoneMultiType($referentOwner->getPageZoneMultiType());
                $ctaReference->setPageZoneMultiOrder($referentOwner->getPageZoneMultiOrder());
                break;
            case $referentOwner instanceof PsaContentVersion:
                $ctaReference = new PsaContentVersionCtaCta();
                $ctaReference->setContentId($referentOwner->getContentId());
                $ctaReference->setContentVersionNumber($referentOwner->getContentVersion());
                break;
            default:
                throw new \RuntimeException(
                    sprintf( "Unknown Cta Referent class to instantiate for referent : %", get_class($referentOwner))
                );
        }

        $ctaReference->setReferenceOwner($referentOwner);
        $ctaReference->setPage($page);
        $ctaReference->setLangueId($page->getLangueId());

        return $ctaReference;
    }

    /**
     * @return PsaDatabaseBlockIdMapper
     */
    public function getBlockIdMapper()
    {
        return $this->blockIdMapper;
    }

    /**
     * @param $url
     * @param PsaPage $page
     * @return PsaRewrite
     */
    public function createPageRewrite($url, PsaPage $page)
    {
        $date = new \DateTime();
        $rewrite = new PsaRewrite();
        $rewrite->setRewriteUrl($url)
            ->setSite($page->getSite())
            ->setLangue($page->getLangue())
            ->setRewriteOrder(1)
            ->setPage($page)
            ->setCreatedAt($date)
            ->setRewriteType('PAGE')
            ->setRewriteId($page->getPageId())
            ->setRewriteResponse('301');

        return $rewrite;



    }


}
