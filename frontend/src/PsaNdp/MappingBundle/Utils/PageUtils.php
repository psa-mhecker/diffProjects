<?php

namespace PsaNdp\MappingBundle\Utils;

use PSA\MigrationBundle\Repository\PsaPageRepository;
use PSA\MigrationBundle\Entity\Page\PsaPageVersion;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use Symfony\Component\HttpFoundation\Request;

/**
 * Usefull fonction to fetch Informations about a page
 *
 * @author sthibault
 */
class PageUtils
{

    /**
     *
     * @var PsaPageRepository
     */
    protected $psaPageRepository;

    /**
     *
     * @param PsaPageRepository $psaPageRepository
     */
    public function __construct(PsaPageRepository $psaPageRepository)
    {

        $this->psaPageRepository = $psaPageRepository;
    }


    /**
     *
     * @param PsaPage $page
     *
     * @return array
     */
    public function getBreadcumb(PsaPage $page)
    {
        $breadcrumb = ['parents'=>[],'current'=>[]];
        $libPath = strip_tags(html_entity_decode($page->getPageLibpath()));

        $parts = explode('#', $libPath);
        $current = explode('|', array_pop($parts));
        $breadcrumb['current'] = array('id'=>$current[0],'name'=>$current[1]);
        $parentIds = [];
        foreach ($parts as $part) {
            $infos = explode('|', $part);
            $parentIds[] = $infos[0];
            $breadcrumb['parents'][$infos[0]] = array('id'=>$infos[0],'name'=>$infos[1]);
        }

        $this->getLinks($page, $parentIds, $breadcrumb['parents']);

        return $breadcrumb;
    }

    protected function getLinks(PsaPage $page, array $pageIds,array &$informations)
    {
        $pages = $this->psaPageRepository->getPagesByNodeIds($pageIds, $page->getLangueId());
        foreach ($pages as $parentPage) {
            /* @var $parentPage PsaPage */
            $url = $parentPage->getVersion()->getPageClearUrl();
            $target = '_self';

            if ($parentPage->getVersion()->getPageUrlExterne()) {
                $url = $parentPage->getVersion()->getPageUrlExterne();
                $target = $parentPage->getVersion()->getPageUrlExterneModeOuverture();
            }
            $informations[$parentPage->getPageId()]['url'] = $url;
            $informations[$parentPage->getPageId()]['target'] = $target;
            if(!$parentPage->getPageStatus()) {
                unset($informations[$parentPage->getPageId()]);
            }
        }
    }
}
