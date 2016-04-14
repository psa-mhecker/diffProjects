<?php

namespace PsaNdp\ApiBundle\Tests\Facade;

use Phake;
use PsaNdp\ApiBundle\Facade\SequenceFacade;

class SequenceFacadeTest extends \PHPUnit_Framework_TestCase
{
    public function testAddMedia()
    {
        $media = Phake::mock('PsaNdp\ApiBundle\Facade\MediaFacade');
        $sequence = new SequenceFacade();
        $this->assertCount(0, $sequence->source);
        $sequence->addMedia($media);
        $this->assertCount(1, $sequence->source);
        $sequence->addMedia($media);
        $this->assertCount(2, $sequence->source);
        $this->assertInternalType('array', $sequence->source);
    }
}
