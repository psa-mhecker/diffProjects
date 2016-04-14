<?php

use PsaNdp\MappingBundle\Manager\TreeManager;
use PsaNdp\MappingBundle\Object\SiteMap;

/**
 * Class TreeManagerTest
 */
class TreeManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TreeManager
     */
    protected $treeManager;
    protected $psaPageZoneRepository;

    /**
     * Set Up the test
     */
    public function setUp()
    {
        $this->psaPageZoneRepository = Phake::mock('PSA\MigrationBundle\Repository\PsaPageZoneRepository');

        $this->treeManager = new TreeManager($this->psaPageZoneRepository);
    }

    /**
     * Test if siteMap has 7 col maximum
     */
    public function testCreateSiteMapTree()
    {
        $data = $this->getDataForSiteMap();

        $result = $this->treeManager->createSiteMapTree($data, false, false);

        $expected = array('col' => array());

        for ($i = 0; $i <= 6; $i++) {
            $expected['col'][]['rub'][] = SiteMap::createSiteMap();
        }

        $this->assertSameSize($expected['col'], $result['col']);
    }

    /**
     * @return array
     */
    protected function getDataForSiteMap()
    {
        $data[] = $this->createPage(null, 0);
        $data = array_merge_recursive($data, $this->createSiteMapData(1, 9, 0));

        return $data;
    }

    /**
     * @param int      $start
     * @param int      $length
     * @param int|null $parentId
     * @param int      $level
     *
     * @return array
     */
    protected function createSiteMapData($start, $length, $parentId, $level = 4)
    {
        $data = array();
        if ($level <= 0) {
            return $data;
        }

        for ($i = $start; $i < $length; $i++) {
            $page = $this->createPage($parentId, $i);

            $newStart = ($start+$i) * $length;
            $child = $this->createSiteMapData($newStart, $newStart + $level, $i, $level-1);

            $data[] = $page;
            $data = array_merge_recursive($data, $child);
        }

        return $data;
    }

    /**
     * @param $parentId
     * @param $pageId
     *
     * @return array
     */
    protected function createPage($parentId, $pageId)
    {
        return array(
            'pageParentId' => $parentId,
            'pageId' => $pageId,
            'currentVersion' => array(
                'pageTitleBo' => 'title',
                'templateId' => 'templateId',
                'pageOuvertureDirect' => true,
                'pageUrlExterneModeOuverture' => 2,
                'pageClearUrl' => 'url'
            )
        );
    }
}
