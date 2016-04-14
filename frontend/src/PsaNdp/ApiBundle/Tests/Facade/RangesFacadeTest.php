<?php

namespace PsaNdp\ApiBundle\Tests\Facade;

use Phake;
use PsaNdp\ApiBundle\Facade\RangesFacade;

class RangesFacadeTest extends \PHPUnit_Framework_TestCase
{
    public function testAddRanges()
    {
        $range = Phake::mock('PsaNdp\ApiBundle\Facade\RangeFacade');
        $ranges = new RangesFacade();
        $this->assertCount(0, $ranges->ranges);
        $ranges->addRanges($range);
        $this->assertCount(1, $ranges->ranges);
        $ranges->addRanges($range);
        $this->assertCount(2, $ranges->ranges);
        $this->assertInternalType('array', $ranges->ranges);
    }
}
