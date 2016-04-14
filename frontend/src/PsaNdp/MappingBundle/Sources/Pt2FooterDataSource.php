<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PsaNdp\MappingBundle\Services\PageFinder;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Zone\PsaZone;
use PSA\MigrationBundle\Repository\PsaPageZoneRepository;
use PSA\MigrationBundle\Repository\PsaReseauSocialRepository;

/**
 * Data source for Pt2Footer block
 */
class Pt2FooterDataSource extends AbstractDataSource
{
    /**
     * @var PsaPageZoneRepository
     */
    private $pageZoneRepository;

    /**
     * @var PsaReseauSocialRepository
     */
    private $socialNetworkRepository;

    /**
     * @var PageFinder
     */
    private $pageFinder;

    /**
     * @param PsaPageZoneRepository     $pageZoneRepository
     * @param PsaReseauSocialRepository $socialNetworkRepository
     * @param PageFinder                $pageFinder
     */
    public function __construct(PsaPageZoneRepository $pageZoneRepository, PsaReseauSocialRepository $socialNetworkRepository, PageFinder $pageFinder)
    {
        /* @todo je pense qu'avec blocmanager on pourrait simplier ceci */
        $this->pageZoneRepository = $pageZoneRepository;
        $this->socialNetworkRepository = $socialNetworkRepository;
        $this->pageFinder = $pageFinder;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param ReadBlockInterface $block
     * @param Request            $request  Current url request displaying th block
     * @param bool               $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /**
         * @var PsaPageZone $block
         */
        $siteId = $request->get('siteId');

        $langId = $block->getPage()->getLangueId();

        $page = $this->pageFinder->getGeneralPage($siteId, $request->get('language'));

        if ($page) {
            $pageId = $page->getPageId();
            $data['helpSection'] = $this->pageZoneRepository->findOneByPageIdAndZoneId($langId, $pageId, PsaZone::PT2_BESOINAIDE);
            $data['contextualSection'] = $this->pageZoneRepository->findOneByPageIdAndZoneId($langId, $pageId, PsaZone::PT2_LAGAMME);
            $data['newsletterSection'] = $this->pageZoneRepository->findOneByPageIdAndZoneId($langId, $pageId, PsaZone::PT2_NEWSLETTER);
            $data['legalSection'] = $this->pageZoneRepository->findOneByPageIdAndZoneId($langId, $pageId, PsaZone::PT2_ELEMENTSLEGAUX);
            $data['contactSection'] = $this->pageZoneRepository->findOneByPageIdAndZoneId($langId, $pageId, PsaZone::PT2_SERVICECLIENT);
            $data['linksSection'] = $this->pageZoneRepository->findOneByPageIdAndZoneId($langId, $pageId, PsaZone::PT2_CTAFOOTER);
            $data['socialSection'] = $this->getSocialNetworksData($langId, $pageId);
            $data['siteMapSection'] = $this->getSiteMapData($siteId, $langId, $pageId, $request->get('language'));
        }

        $footerLanguages = $block->getPage()->getSite()->getLanguages();
        $data['footerLangages'] = null;
        if (sizeof($footerLanguages) >= 1) {
            $data['languages'] = array_slice($footerLanguages, 0, 3);
        }

        $data['currentLanguageId'] = $langId;

        $data['currentPageId'] = $request->get('nodeId');

        $currentPage = $this->pageFinder->getPage($data['currentPageId'], $siteId, $langId);

        $data['pageTypeCode'] = $currentPage->getTypeCode();
        $data['siteId'] = $siteId;
        $data['pageFinder'] = $this->pageFinder;

        return $data;
    }

    /**
     * Return footer social networks data
     *
     * @param int $langId
     * @param int $pageId
     *
     * @return array
     */
    public function getSocialNetworksData($langId, $pageId)
    {
        $socialNetworks = [];
        $pageZone = $this->pageZoneRepository->findOneByPageIdAndZoneId($langId, $pageId, PsaZone::PT2_RESEAUXSOCIAUX);

        if ($pageZone->getZoneAffichage()) {
            $ids = $pageZone->getParameters();
            $socialNetworks = $this->socialNetworkRepository->findByLangAndIds($langId, $ids);
        }

        return $socialNetworks;
    }

    /**
     * Return footer site map data
     *
     * @param int    $siteId
     * @param int    $langId
     * @param int    $pageId
     * @param string $languageCode
     *
     * @return array
     */
    public function getSiteMapData($siteId, $langId, $pageId, $languageCode)
    {
        $siteMapData = [];
        $siteMapData['pageZone'] = $this->pageZoneRepository->findOneByPageIdAndZoneId($langId, $pageId, PsaZone::PT2_PLANDUSITE);
        $siteMapData['siteMap'] = $this->pageFinder->getFooterSiteMap($siteId, $languageCode);
        $siteMapPage = $this->pageFinder->getSiteMapPage($siteId, $languageCode);

        if ($siteMapPage) {
            $siteMapData['fullMapLink'] = $siteMapPage->getUrl();
        }

        return $siteMapData;
    }
}
