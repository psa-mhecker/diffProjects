<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PsaNdp\MappingBundle\Entity\PsaPageTypesCode;
use PsaNdp\MappingBundle\Manager\TreeManager;
use PsaNdp\MappingBundle\Services\ShareServices\ShareObjectService;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PSA\MigrationBundle\Repository\PsaPageZoneRepository;
use PSA\MigrationBundle\Repository\PsaLanguageRepository;

/**
 * Data source for Pt21Navigation block
 */
class Pt21NavigationDataSource extends AbstractDataSource
{
    /**
     * @var ShareObjectService
     */
    protected $share;
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
     * @var TreeManager
     */
    protected $treeManager;

    /**
     * @param PsaPageRepository $pageRepository
     * @param PsaPageZoneRepository $pageZoneRepository
     * @param PsaLanguageRepository $languageRepository
     * @param TreeManager $treeManager
     * @param ShareObjectService $share
     */
    public function __construct(PsaPageRepository $pageRepository, PsaPageZoneRepository $pageZoneRepository, PsaLanguageRepository $languageRepository, TreeManager $treeManager, ShareObjectService $share)
    {
        $this->pageRepository = $pageRepository;
        $this->pageZoneRepository = $pageZoneRepository;
        $this->languageRepository = $languageRepository;
        $this->treeManager = $treeManager;
        $this->share = $share;
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
        $pageId = $request->get('nodeId');
        $langId = $this->languageRepository->getIdByCode($this->locale);

        $page = $this->pageRepository->findOneBy(array(
            'pageId' => (int) $pageId,
            'langueId' => (int) $langId
        ));
        $siteId = $request->get('siteId');
        $pageId = $page->getPageId();
        $data['page'] = $page;

        $pageIds = explode('#', $page->getPagePath());
        if(is_array($pageIds)){
            $data['homepage'] = $this->pageRepository->findOneBy(array(
                'pageId' => (int) $pageIds[0],
                'langueId' => (int) $langId
            ));
        }

        $siteMap = $this->pageRepository->getSiteMapPages($siteId, $request->get('language'), false);

        $data['block'] = $block;
        $data['siteMapData'] = $this->treeManager->createSiteMapTree($siteMap, true);

        if ($isMobile) {
            //@TODO: zoneTemplateId à mettre en constante ou en conf
            $zoneTemplateId = 2;
            $data['footerNavigation'] = $this->pageZoneRepository
                ->findOnePageZoneWithCtaByLanguePageAndZone($langId, $pageId, $zoneTemplateId);
        }

        if ($block->inTemplate(PsaPageTypesCode::PAGE_TYPE_CODE_G27)) {
            $data['confishow'] = true;
        }
        $data['myPeugeot'] = $this->share->getMyPeugeot();

        return $data;
    }
}
