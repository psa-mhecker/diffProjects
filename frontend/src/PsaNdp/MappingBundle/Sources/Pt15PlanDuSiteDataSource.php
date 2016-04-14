<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PsaNdp\MappingBundle\Manager\TreeManager;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Repository\PsaPageRepository;

/**
 * Data source for Pt15PlanDuSite block
 */
class Pt15PlanDuSiteDataSource extends AbstractDataSource
{
    /**
     * @var PsaPageRepository
     */
    protected $pageRepository;

    /**
     * @var TreeManager
     */
    protected $treeManager;

    /**
     * @param PsaPageRepository $pageRepository
     * @param TreeManager       $treeManager
     */
    public function __construct(PsaPageRepository $pageRepository, TreeManager $treeManager)
    {
        $this->pageRepository = $pageRepository;
        $this->treeManager = $treeManager;
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
        $data['siteMap'] = $this->getContent($request->get('siteId'), $request->get('language'));
        $data['block'] = $block;

        return $data;
    }

    /**
     * Returns page tree for a given site and language
     *
     * @param  int    $siteId
     * @param  string $language
     * @return array
     *
     */
    public function getContent($siteId, $language)
    {
        $data = $this->pageRepository->getSiteMapPages($siteId,$language);

        return $this->treeManager->createSiteMapTree($data, false, true);
    }
}
