<?php 

namespace PsaNdp\MappingBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PSA\MigrationBundle\Entity\Zone\PsaZone;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PSA\MigrationBundle\Repository\PsaPageZoneRepository;
use PsaNdp\MappingBundle\Object\SiteMap;

/**
 * Class TreeManager
 */
class TreeManager
{
    /** @var PsaPageZoneRepository */
    protected $pageZoneRepository;

    /**
     * @param PsaPageZoneRepository $pageZoneRepository
     */
    public function __construct(PsaPageZoneRepository $pageZoneRepository)
    {
        $this->pageZoneRepository = $pageZoneRepository;
    }

    /**
     * @param array $siteMapData
     * @param bool  $getQuickAccess
     * @param bool  $siteMap
     *
     * @return array
     */
    public function createSiteMapTree(array $siteMapData, $getQuickAccess = false, $siteMap = false)
    {
        $result = [];
        $tree = [];
        $colArray = [];

        // Create flat tree with 'root' as key for tree root
        $tree['root'] = [];
        foreach ($siteMapData as $item) {
            $key = ($item['pageParentId'] === null) ? 'root' : $item['pageParentId'];

            if (!isset($tree[$key])) {
                $tree[$key] = [];
            }
            $tree[$key][] = $item;
        }

        // Ignore root "Accueil" for Site Map
        $level1ItemList = null;
        if (isset($tree['root'][0])) {
            $level1ItemList = $tree['root'][0];
        }
        if (isset($tree[$level1ItemList['pageId']])) {
            $level1ItemList = $tree[$level1ItemList['pageId']];
        }

        $result = $this->createSiteMapTreeLevelOne($tree, $level1ItemList, $getQuickAccess, $siteMap);

        return $result;
    }

    /**
     * Generate Data for Site Map level 1
     *
     * @param $tree
     * @param $levelOneItemList
     * @param bool $getQuickAccess
     * @param bool $siteMap
     *
     * @return mixed
     */
    private function createSiteMapTreeLevelOne($tree, $levelOneItemList, $getQuickAccess = false, $siteMap = false)
    {
        $data = array();
        $max = 6;

        if ($siteMap) {
            $max = count($levelOneItemList) - 1;
        }

        for ($i = 0; $i <= $max; $i++) {
            $levelOne = SiteMap::createSiteMap();

            if ($levelOneItemList !== null && isset($levelOneItemList[$i])) {
                $levelOneItem = $levelOneItemList[$i];
                $levelOneId = $levelOneItem['pageId'];

                $levelOne->setTitle($levelOneItem['currentVersion']['pageTitleBo']);

                // Referencer le templateId pour les traitements concernants des pages spécifiques
                $levelOne->setTemplateId($levelOneItem['currentVersion']['templateId']);

                // Si le gabarit est une master page de niveau 1, l'activation de la case permet d'ouvrir directement la page à partir du menu sans l'expand.
                $levelOne->setDirectOpen($levelOneItem['currentVersion']['pageOuvertureDirect']);

                if ($levelOneItem['currentVersion']['pageOuvertureDirect'] || (substr($levelOneItem['currentVersion']['pageClearUrl'], 0, 1) == '/')) {
                    $levelOne->setTarget('_self');
                }
                if (!$levelOneItem['currentVersion']['pageOuvertureDirect'] && (substr($levelOneItem['currentVersion']['pageClearUrl'], 0, 1) != '/')) {
                    $levelOne->setTarget('_blank');
                }
                if($levelOneItem['currentVersion']['pageUrlExterneModeOuverture'] == 2) {
                    $levelOne->setTarget('_blank');
                }

                if (!empty($levelOneItem['currentVersion']['pageUrlExterne'])) {
                    $levelOne->setUrl($levelOneItem['currentVersion']['pageUrlExterne']);
                }
                if (empty($levelOneItem['currentVersion']['pageUrlExterne'])) {
                    $levelOne->setUrl($levelOneItem['currentVersion']['pageClearUrl']);
                }

                $levelOne->setChild($this->createSiteMapTreeChild($tree, $levelOneId));

                if ($getQuickAccess) {
                    $quickAccess = $this->pageZoneRepository
                        ->findOneByPageIdAndZoneId($levelOneItem['langueId'], $levelOneId, PsaZone::PT20_ADMIN);
                    $levelOne->setQuickAccess($quickAccess);
                }

            }


            $data['col'][]['rub'][] = $levelOne;
        }

        return $data;
    }

    /**
     * Generate Data for Site Map child
     *
     * @param $tree
     * @param $levelOneId
     *
     * @return Collection
     */
    private function createSiteMapTreeChild($tree, $levelOneId)
    {
        $result = new ArrayCollection();

        if (isset($tree[$levelOneId])) {
            foreach ($tree[$levelOneId] as $item) {
                $newLevel2 = SiteMap::createSiteMap();

                $level2Id = $item['pageId'];
                $newLevel2->setTitle($item['currentVersion']['pageTitleBo']);

                $newLevel2->setTemplateId($item['currentVersion']['templateId']);

                if ($item['currentVersion']['pageOuvertureDirect'] || (substr($item['currentVersion']['pageClearUrl'], 0, 1) == '/')) {
                    $newLevel2->setTarget('_self');
                }
                if (!$item['currentVersion']['pageOuvertureDirect'] && (substr($item['currentVersion']['pageClearUrl'], 0, 1) != '/')) {
                    $newLevel2->setTarget('_blank');
                }
                if($item['currentVersion']['pageUrlExterneModeOuverture'] == 2) {
                    $newLevel2->setTarget('_blank');
                }
                 if (!empty($item['currentVersion']['pageUrlExterne'])) {
                    $newLevel2->setUrl($item['currentVersion']['pageUrlExterne']);
                }
                if (empty($item['currentVersion']['pageUrlExterne'])) {
                    $newLevel2->setUrl($item['currentVersion']['pageClearUrl']);
                }
                $newLevel2->setChild($this->createSiteMapTreeChild($tree, $level2Id));

                $result->add($newLevel2);
            }
        }

        return $result;
    }
}
