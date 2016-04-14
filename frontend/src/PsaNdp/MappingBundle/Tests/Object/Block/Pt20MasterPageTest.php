<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use Phake;
use PsaNdp\MappingBundle\Object\Block\Pt20MasterPage;
use Doctrine\Common\Collections\ArrayCollection;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Cta;

class Pt20MasterPageTest extends \PHPUnit_Framework_TestCase
{
    protected $pt20;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->pt20 = new Pt20MasterPage();
    }

    /**
     *
     */
    public function testInitData()
    {

        $this->pt20->setSubPage(new ArrayCollection());
        $this->pt20->initData();

        $childrenContents = $this->pt20->getChildrenContents();
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $childrenContents);

    }

}
