<?php

namespace PsaNdp\MappingBundle\Tests\Sources;

use Phake;
use PsaNdp\MappingBundle\Sources\Pt20MasterPageDataSource;

/**
 * Class Pt20MasterPageDataSourceTest.
 */
class Pt20MasterPageDataSourceTest extends \PHPUnit_Framework_TestCase
{
    /** @var  $source Pt20MasterPageDataSource */
    protected $source;

    protected $pageRepository;
    protected $request;
    protected $block;
    protected $page;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->page = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPage');

        $this->pageRepository = Phake::mock('PSA\MigrationBundle\Repository\PsaPageRepository');
        Phake::when($this->pageRepository)->findSubPagesByPage(Phake::anyParameters())->thenReturn(array($this->page, $this->page));

        $this->request = Phake::mock('Symfony\Component\HttpFoundation\Request');

        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZone');
        Phake::when($this->block)->getPage()->thenReturn($this->page);

        $this->source = new Pt20MasterPageDataSource($this->pageRepository);
    }

    /**
     * Test fetch method.
     */
    public function testFetch()
    {
        $result = $this->source->fetch($this->block, $this->request, false);

        Phake::verify($this->pageRepository)->findSubPagesByPage($this->page);

        $this->assertTrue(is_array($result));
        $this->assertTrue(isset($result['block']));
        $this->assertTrue(isset($result['subPages']));
    }
}
