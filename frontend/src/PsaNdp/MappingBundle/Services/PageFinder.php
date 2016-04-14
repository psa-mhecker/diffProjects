<?php

namespace PsaNdp\MappingBundle\Services;

use Doctrine\Common\Collections\Collection;
use OpenOrchestra\ModelInterface\Model\ReadNodeInterface;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Repository\PsaLanguageRepository;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PsaNdp\MappingBundle\Entity\PsaPageTypesCode;

/**
 * Class PageFinder
 */
class PageFinder
{
    /**
     * @var PsaPageRepository
     */
    protected $pageRepository;

    /**
     * @var PsaLanguageRepository
     */
    protected $languageRepository;

    /**
     * @param PsaPageRepository     $pageRepository
     * @param PsaLanguageRepository $languageRepository
     */
    public function __construct(
        PsaPageRepository $pageRepository,
        PsaLanguageRepository $languageRepository
    ) {
        $this->languageRepository = $languageRepository;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @param int    $siteId
     * @param string $language
     *
     * @return PsaPage|null
     */
    public function get404Page($siteId, $language)
    {
        return $this->getPageByPageCode(PsaPageTypesCode::PAGE_TYPE_CODE_G05, $siteId, $language);
    }

    /**
     * @param int    $siteId
     * @param string $language
     *
     * @return PsaPage|null
     */
    public function getPreHomePage($siteId, $language)
    {
        return $this->getPageByPageCode(PsaPageTypesCode::PAGE_TYPE_CODE_G03, $siteId, $language);
    }

    /**
     * @param $siteId
     * @param $language
     *
     * @return null|PsaPage
     */
    public function getHomePage($siteId, $language)
    {
        return $this->getPageByPageCode(PsaPageTypesCode::PAGE_TYPE_CODE_G01, $siteId, $language);
    }

    /**
     * @param $siteId
     * @param $language
     *
     *
     * @return null|PsaPage
     */
    public function getDealerLocator($siteId, $language)
    {

        return $this->getPageByPageCode(PsaPageTypesCode::PAGE_TYPE_CODE_DEALER_LOCATOR, $siteId, $language);
    }

    /**
     * @param $siteId
     * @param $language
     *
     * @return null|PsaPage
     */
    public function getGeneralPage($siteId, $language)
    {
        return $this->getPageByPageCode(PsaPageTypesCode::PAGE_TYPE_CODE_GENERAL, $siteId, $language);
    }

    /**
     * @param $siteId
     * @param $language
     *
     * @return null|PsaPage
     */
    public function getSiteMapPage($siteId, $language)
    {
        return $this->getPageByPageCode(PsaPageTypesCode::PAGE_TYPE_CODE_SITE_MAP, $siteId, $language);
    }

    /**
     * @param string $pageCode
     * @param int    $siteId
     * @param string $language
     *
     * @return PsaPage|null
     */
    private function getPageByPageCode($pageCode, $siteId, $language)
    {
        $language = $this->languageRepository->findByCode($language);
        $languageId = $language->getLangueId();
        $page = $this->pageRepository->findOneByPageTypeCodeLanguageIdAndSiteId($pageCode, $languageId, $siteId);

        if ($page instanceof PsaPage) {
            return $page;
        }

        return null;
    }

    /**
     * @param $pageId
     * @param $languageCode
     * @param $siteId
     *
     * @return ReadNodeInterface
     */
    public function getPageInDifferentLanguage($pageId, $languageCode, $siteId)
    {
        return $this->pageRepository->findOnePublishedByNodeIdAndLanguageAndSiteIdInLastVersion(
            $pageId,
            $languageCode,
            $siteId
        );
    }

    /**
     * @param $siteId
     * @param $languageCode
     *
     * @return mixed|null
     */
    public function getFirstLevelPages($siteId, $languageCode)
    {

        $firstLevelPages = null;
        $homePage = $this->getHomePage($siteId, $languageCode);
        if ($homePage) {

            $firstLevelPages = $this->pageRepository->getSiteMapQb($siteId, $languageCode, false ,$homePage->getId(),1)->getQuery()->execute();
        }

        return $firstLevelPages;
    }

    /**
     * @param $siteId
     * @param $languageCode
     *
     * @return Collection
     */
    public function getFooterSiteMap($siteId, $languageCode)
    {
        return $this->pageRepository->findFooterSiteMap($siteId, $languageCode);
    }

    /**
     * @param $pageId
     * @param $siteId
     * @param $languageId
     *
     * @return PsaPage
     */
    public function getPage($pageId, $siteId, $languageId)
    {
        return $this->pageRepository->findOneBy(array('pageId' => $pageId, 'siteId' => $siteId, 'langueId' => $languageId));
    }
}
