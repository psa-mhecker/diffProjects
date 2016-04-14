<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use PsaNdp\MappingBundle\Object\Block\Pn15EnteteConfishow;

class Pn15EnteteConfishowTest  extends \PHPUnit_Framework_TestCase
{
    protected $pn15;

    public function setUp()
    {
        $this->pn15 = new Pn15EnteteConfishow();
    }

    /**
     * @param array $data
     * @param int   $nbItem
     *
     * @dataProvider provideInitBreadcrumb
     */
    public function testInitBreadcrumb(array $data, $nbItem)
    {
        $this->pn15->setBreadcrumb($data);
        $this->pn15->init();
        $result = $this->pn15->getBreadcrumb();
        $this->assertCount($nbItem, $result);
        $this->assertInstanceOf('\PsaNdp\MappingBundle\Object\MenuItem', current($result));
    }

    /**
     * @return array
     */
    public function provideInitBreadcrumb()
    {
        return array(
            array(
                ['current' => ['name' => 'test'], 'parents' => []], 1,
            ),
            array(
                ['current' => ['name' => 'test'], 'parents' => [0 => [],1 => []]], 3,
            ),
        );
    }
}
