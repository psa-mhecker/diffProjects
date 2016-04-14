<?php

namespace PsaNdp\MappingBundle\Tests\Sources;

use Phake;
use PsaNdp\MappingBundle\Sources\Pn7EnTeteDataSource;

/**
 * Class Pn7EnTeteDataSourceTest.
 */
class Pn7EnTeteDataSourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pn7EnTeteDataSource
     */
    protected $source;

    protected $streamLikeMedia;
    protected $page;
    protected $pageUtils;
    protected $request;
    protected $block;
    protected $media;
    protected $siteId = 2;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->streamLikeMedia = Phake::mock('PsaNdp\MappingBundle\Utils\StreamlikeMedia');
        $this->pageUtils = Phake::mock('PsaNdp\MappingBundle\Utils\PageUtils');

        $this->request = Phake::mock('Symfony\Component\HttpFoundation\Request');
        Phake::when($this->request)->get('siteId')->thenReturn($this->siteId);

        $this->media = Phake::mock('PSA\MigrationBundle\Entity\Media\PsaMedia');
        Phake::when($this->media)->getMediaRemoteId()->thenReturn(null);

        $this->page = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPage');

        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZone');
        Phake::when($this->block)->getMedia()->thenReturn($this->media);
        Phake::when($this->block)->getPage()->thenReturn($this->page);
        /** @var ShareObjectService share */
        $share = Phake::mock('PsaNdp\MappingBundle\Services\ShareServices\ShareObjectService');

        $this->source = new Pn7EnTeteDataSource($this->streamLikeMedia, $this->pageUtils, $share);
    }

    /**
     * Test fetch method.
     */
    public function testFetch()
    {
        Phake::when($this->media)->isStreamlike()->thenReturn(true);

        $this->source->fetch($this->block, $this->request, true);

        Phake::verify($this->block)->getMedia();
        Phake::verify($this->block)->getPage();
        Phake::verify($this->pageUtils)->getBreadcumb($this->page);
    }
}
