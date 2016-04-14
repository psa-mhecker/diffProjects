<?php
/**
 *
 */
namespace PsaNdp\MappingBundle\Manager;

use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Repository\PsaPageMultiZoneRepository;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PSA\MigrationBundle\Repository\PsaPageZoneRepository;
use PSA\MigrationBundle\Repository\PsaTemplatePageRepository;
use PSA\MigrationBundle\Entity\Page\PsaPageType;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Repository\PsaLanguageRepository;
use PsaNdp\MappingBundle\Services\PageFinder;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;

class  BlockManager
{
    const PT3_ADMIN_BLOCK = 833;
    const BLOCK_DYNAMIC_AREA_ID = 150;

    /**
     * @var PsaPageRepository
     */
    private $pageRepository;

    /**
     * @var PsaPageZoneRepository
     */
    private $pageZoneRepository;

    /**
     * @var PsaLanguageRepository
     */
    private $languageRepository;

    /**
     * @var PsaPageMultiZoneRepository
     */
    private $psaPageMultiZoneRepository;

    /**
     * @var PageFinder
     */
    private $pageFinder;

    /**
     * @param PsaPageRepository $pageRepository
     * @param PsaPageZoneRepository $pageZoneRepository
     * @param PsaLanguageRepository $languageRepository
     * @param PsaPageMultiZoneRepository $psaPageMultiZoneRepository
     * @param PageFinder $pageFinder
     */
    public function __construct(
        PsaPageRepository $pageRepository,
        PsaPageZoneRepository $pageZoneRepository,
        PsaLanguageRepository $languageRepository,
        PsaPageMultiZoneRepository $psaPageMultiZoneRepository,
        PageFinder $pageFinder
    ) {
        $this->pageRepository = $pageRepository;
        $this->pageZoneRepository = $pageZoneRepository;
        $this->languageRepository = $languageRepository;
        $this->psaPageMultiZoneRepository = $psaPageMultiZoneRepository;
        $this->pageFinder = $pageFinder;
    }

    /**
     * @param Request $request
     * @param int     $zoneId
     *
     * @return PsaPageZone
     * @throws \Exception
     */
    public function getAdminBlock(Request $request, $zoneId)
    {
        $siteId = $request->get('siteId');
        $langId = $this->languageRepository->getIdByCode($request->get('language'));

        $pageGeneral = $this->pageFinder->getGeneralPage($siteId, $request->get('language'));

        if($pageGeneral){
            $block = $this->pageZoneRepository->findOneByPageIdAndZoneId($langId, $pageGeneral->getPageId(), $zoneId);
        }else{
            throw new \Exception('Block Not Found');
        }

        return $block;
    }

    /**
     * @param PsaPage $node
     * @param int $zoneId
     *
     * @return PsaPageZone
     * @throws \Exception
     */
    public function getAdminBlockByNodeAndZoneId(PsaPage $node, $zoneId)
    {
        $pageGeneral = $this->pageFinder->getGeneralPage($node->getSiteId(), $node->getLanguage());
        if($pageGeneral){
            $block = $this->pageZoneRepository->findOneByPageIdAndZoneId($node->getLangueId(), $pageGeneral->getPageId(), $zoneId);
        }else{
            throw new \Exception('Block Not Found');
        }

        return $block;
    }


    /**
     * @param PsaPageZoneConfigurableInterface $block
     * @param int $zoneId
     *
     * @return PsaPageZoneConfigurableInterface
     */
    public function getShowroomWelcomePageDynamicBlock(PsaPageZoneConfigurableInterface $block, $zoneId)
    {
        $welcomePage = $this->getShowroomWelcomePage($block);
        $pageId = $block->getPageId();
        if($welcomePage !== null) {
            $pageId = $welcomePage->getPageId();
        }

        return $this->psaPageMultiZoneRepository->getPageMultiZoneByPageIdLangueIdZoneIdAreaId($pageId, $block->getLangueId(), $zoneId, self::BLOCK_DYNAMIC_AREA_ID);
    }
    /**
     * @param PsaPageZoneConfigurableInterface $block
     * @param int $zoneId
     *
     * @return PsaPageZoneConfigurableInterface
     */
    public function getShowroomWelcomePageStaticBlock(PsaPageZoneConfigurableInterface $block, $zoneId)
    {
        $welcomePage = $this->getShowroomWelcomePage($block);
        $pageId = $block->getPageId();
        if($welcomePage !== null) {
            $pageId = $welcomePage->getPageId();
        }

        return $this->pageZoneRepository->findOneByPageIdAndZoneId($block->getLangueId(), $pageId, $zoneId);
    }

    /**
     * Retourne la page parent de showroom
     * @param PsaPageZoneConfigurableInterface $block
     *
     * @return mixed
     */
    private function getShowroomWelcomePage(PsaPageZoneConfigurableInterface $block)
    {

        return $this->pageRepository->findShowroomWelcomePageByPage($block->getPage());
    }

    /**
     * Retrieve a page zone from page id and zone id
     *
     * @param int $langId
     * @param int $pageId
     * @param int $zoneId
     *
     * @return PsaPageZone
     */
    public function findOneByPageIdAndZoneId($langId, $pageId, $zoneId)
    {
        return $this->pageZoneRepository->findOneByPageIdAndZoneId($langId, $pageId, $zoneId);
    }
}
