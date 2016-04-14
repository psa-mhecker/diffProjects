<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use Phake;
use PsaNdp\MappingBundle\Object\Block\Pc83ContenuAccessoires;

class Pc83ContenuAccessoiresTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pc83ContenuAccessoires
     */
    protected $pc83ContenuAccessoires;
    protected $ctaFactory;

    /**
     * @var Pc83ContenuAccessoires
     */
    protected $pc83ContenuAccessoiresWithValue;
    protected $block;
    protected $isMobile = true;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface');
        $this->ctaFactory = Phake::mock('PSA\MigrationBundle\Repository\PsaCtaRepository');
        $this->pc83ContenuAccessoires = new Pc83ContenuAccessoires($this->ctaFactory);
        $this->pc83ContenuAccessoiresWithValue = new Pc83ContenuAccessoires($this->ctaFactory);
        $this->pc83ContenuAccessoiresWithValue->setIsMobile($this->isMobile);
        $this->pc83ContenuAccessoiresWithValue->setBlock($this->block);
    }

    /**
     * Test setDataFromArray.
     */
    public function testSetDataFromArray()
    {
        $data = array('block' => $this->block);

        $this->pc83ContenuAccessoires->setDataFromArray($data);

        $this->assertSame($this->block, $this->pc83ContenuAccessoires->getBlock());
    }

    /**
     * Test GetBlock.
     */
    public function testGetBlock()
    {
        $this->assertNull($this->pc83ContenuAccessoires->getBlock());

        $pc83Value = $this->pc83ContenuAccessoiresWithValue->getBlock();

        $this->assertSame($this->block, $pc83Value);
    }

    /**
     * Test SetBlock.
     */
    public function testSetBlock()
    {
        $this->pc83ContenuAccessoires->setBlock($this->block);
        $this->assertSame($this->block, $this->pc83ContenuAccessoires->getBlock());
    }
}
