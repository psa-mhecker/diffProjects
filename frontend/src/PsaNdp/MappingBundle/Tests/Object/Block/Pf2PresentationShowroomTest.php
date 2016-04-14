<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use Phake;
use PsaNdp\MappingBundle\Object\Block\Pf2PresentationShowroom;

class Pf2PresentationShowroomTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pf2PresentationShowroom
     */
    protected $pf2PresentationShowroom;

    /**
     * @var Pf2PresentationShowroom
     */
    protected $pf2PresentationShowroomWithValue;
    protected $block;
    protected $isMobile = true;
    protected $priceManager;
    protected $streamLikeMedia;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface');
        $this->priceManager = Phake::mock('PsaNdp\MappingBundle\Manager\PriceManager');

        $this->pf2PresentationShowroom = new Pf2PresentationShowroom($this->priceManager);
        $this->pf2PresentationShowroomWithValue = new Pf2PresentationShowroom($this->priceManager);
        $this->pf2PresentationShowroomWithValue->setIsMobile($this->isMobile);
        $this->pf2PresentationShowroomWithValue->setBlock($this->block);
    }

    /**
     * Test setDataFromArray.
     */
    public function testSetDataFromArray()
    {
        $data = array('block' => $this->block);

        $this->pf2PresentationShowroom->setDataFromArray($data);

        $this->assertSame($this->block, $this->pf2PresentationShowroom->getBlock());
    }

    /**
     * Test GetBlock.
     */
    public function testGetBlock()
    {
        $this->assertNull($this->pf2PresentationShowroom->getBlock());

        $pc83Value = $this->pf2PresentationShowroomWithValue->getBlock();

        $this->assertSame($this->block, $pc83Value);
    }

    /**
     * Test SetBlock.
     */
    public function testSetBlock()
    {
        $this->pf2PresentationShowroom->setBlock($this->block);
        $this->assertSame($this->block, $this->pf2PresentationShowroom->getBlock());
    }

    /**
     * Test IsFull.
     */
    public function testIsFull()
    {
        $pageVersion = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageVersion');
        $page = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPage');
        Phake::when($pageVersion)->getGammeVehicule()->thenReturn(false);
        Phake::when($page)->getVersion()->thenReturn($pageVersion);
        Phake::when($this->block)->getPage()->thenReturn($page);
        Phake::when($this->block)->getZoneParameters()->thenReturn(Pf2PresentationShowroom::COMMERCIAL);
        $this->pf2PresentationShowroom->setBlock($this->block);
        $this->assertTrue($this->pf2PresentationShowroom->isFull());
        Phake::when($this->block)->getZoneParameters()->thenReturn('something else');
        $this->pf2PresentationShowroom->setBlock($this->block);
        $this->assertFalse($this->pf2PresentationShowroom->isFull());
    }

    public function testGetPosition()
    {
        Phake::when($this->block)->getZoneLabel2()->thenReturn('left');
        $this->pf2PresentationShowroom->setBlock($this->block);
        $this->assertEquals('left', $this->pf2PresentationShowroom->getPosition());
        Phake::when($this->block)->getZoneLabel2()->thenReturn('right');
        $this->pf2PresentationShowroom->setBlock($this->block);
        $this->assertEquals('right', $this->pf2PresentationShowroom->getPosition());
    }
}
