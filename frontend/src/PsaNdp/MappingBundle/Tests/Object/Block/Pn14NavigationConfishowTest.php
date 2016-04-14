<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use Phake;
use PsaNdp\MappingBundle\Object\Block\Pn14NavigationConfishow;

/**
 * Class Pn14NavigationConfishowTest.
 */
class Pn14NavigationConfishowTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pn14NavigationConfishow
     */
    protected $pn14;

    /**
     * @var Pn14NavigationConfishow
     */
    protected $pn14WithValue;

    protected $block;

    protected $page;

    protected $isMobile = false;

    protected $blockTitle = null;

    protected $blockIntroduction = null;

    protected $pageTitle = null;

    protected $pageUrlExterne = null;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface');
        Phake::when($this->block)->getZoneParameters()->thenReturn($this->blockTitle);
        $this->pn14 = new Pn14NavigationConfishow();
    }
    public function testToWrite()
    {
    }
}
