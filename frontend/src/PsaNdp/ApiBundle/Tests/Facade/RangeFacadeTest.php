<?php

namespace PsaNdp\ApiBundle\Tests\Facade;

use Phake;
use PsaNdp\ApiBundle\Facade\RangeFacade;

class RangeFacadeTest extends \PHPUnit_Framework_TestCase
{
    public function testAddRange()
    {
        $color = Phake::mock('PsaNdp\ApiBundle\Facade\ColorFacade');
        $range = new RangeFacade();
        $this->assertCount(0, $range->range);
        $range->addRange($color);
        $this->assertCount(1, $range->range);
        $range->addRange($color);
        $this->assertCount(2, $range->range);
        $this->assertInternalType('array', $range->range);
    }
}
