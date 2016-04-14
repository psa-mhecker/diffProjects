<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PsaNdp\MappingBundle\Entity\PsaFinishingColor;
use PsaNdp\MappingBundle\Object\Vehicle;
use PsaNdp\MappingBundle\Repository\PsaFinishingColorRepository;
use PsaNdp\MappingBundle\Services\ShareServices\ShareObjectService;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Pn14NavigationConfishowDataSource
 */
class Pn14NavigationConfishowDataSource extends AbstractDataSource
{

    const DEFAULT_COLOR = '#007EDB';
    /**
     * @var PsaPageRepository
     */
    private $pageRepository;

    /**
     * @var array
     */
    private $path;

    /**
     * @var int
     */
    private $pageId;

    /**
     * @var ShareObjectService
     */
    private $share;

    /**
     * @var PsaFinishingColorRepository
     */
    private $colorRepository;

    /**
     * @param PsaPageRepository $pageRepository
     * @param ShareObjectService $share
     * @param PsaFinishingColorRepository $colorRepository
     */
    public function __construct(PsaPageRepository $pageRepository, ShareObjectService $share, PsaFinishingColorRepository $colorRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->share = $share;
        $this->colorRepository = $colorRepository;
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
        /** @var PsaPageZoneConfigurableInterface $block */
        $data['block'] = $block;
        $this->path = explode('#',$block->getPage()->getPagePath());
        $this->pageId = $block->getPageId();
        // search root page of the showroom
        $rootPage = $this->findRootPage($block);
        $childPages = [];
        if($rootPage) {
            $childPages = $this->pageRepository->findSubPagesByPage($rootPage);
        }
        if (sizeof($childPages) > 0) {

            $data['menu'] = $this->buildMenu($childPages);
            $item['title'] = html_entity_decode($rootPage->getVersion()->getPageTitleBo());
            $item['url'] = $rootPage->getVersion()->getPageClearUrl();
            $item['isActive'] = $rootPage->getPageId() == $block->getPageId();
            // add welcome page as first item of the menu
            array_unshift($data['menu'], $item);
        }
        $data['color'] = $this->getColor();
        $data['modelSilhouette'] = $this->getModelSilhouette();

        return $data;
    }

    /**
     * @param array $childPages
     * @param bool $getChild
     * @param int $max
     * 
     * @return array
     */
    private function buildMenu(array $childPages, $getChild = true, $max = 6){
        $return = [];
        //RG_FO_PN14_07 : 5 items maximum dans le menu principale
        if (sizeof($childPages) >= $max) {
            $childPages = array_slice($childPages, 0, $max);
        }
        /** @var \PSA\MigrationBundle\Entity\Page\PsaPage $page */
        foreach ($childPages as $page) {
            $item = [];
            $item['title'] =html_entity_decode($page->getVersion()->getPageTitleBo());
            $item['url'] = $page->getVersion()->getPageClearUrl();
            $item['isActive'] = $page->getPageId() == $this->pageId;
            $item['isAncestor'] = $page->getPageId() != $this->pageId && in_array($page->getPageId(), $this->path);
            if($getChild)  {
                $childPages = $this->pageRepository->findSubPagesByPage($page);
                if (sizeof($childPages) > 0 ) {
                    $item['childs'] = $this->buildMenu($childPages, false, 7);
                }
            }
            $return[] = $item;
        }

        return $return;
    }

    private function getModelSilhouette() {

        $return = null;
        $vehicle = $this->share->getVehicle();
        if ($vehicle instanceof Vehicle) {
            $return = $vehicle->getLabel();
        }

        return $return;
    }

    /**
     * @return string
     */
    protected function getColor()
    {
        $colorCode = self::DEFAULT_COLOR;
        $vehicle = $this->share->getVehicle();

        if ($vehicle instanceof Vehicle) {
            /**  retourner le couleur en fonction du modele de vehicule pour le serie special  */
            $info = $vehicle->getModelSilhouetteInformation();

            if ($info && $info->hasCustomColor()) {
                if($info->getColor()) {
                    $colorCode = $info->getColor()->getColorCode();
                }
            }
        }

        return $colorCode;
    }

    /**
     * @param PsaPageZoneConfigurableInterface $block
     *
     * @return null|PsaPage
     */
    protected function findRootPage(PsaPageZoneConfigurableInterface $block)
    {
        return $this->pageRepository->findShowroomWelcomePageByPage($block->getPage(), $block->getPage()->getVersion()->getTemplatePage()->getPageType()->getPageTypeCode());
    }
}
