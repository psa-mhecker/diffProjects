<?php

namespace PsaNdp\MappingBundle\Tests\Object;


use PsaNdp\MappingBundle\Object\MenuItem;

class MenuItemTest extends \PHPUnit_Framework_TestCase
{

    public function testSetChilds()
    {
        $menuItem = new MenuItem();
        $childs = [];
        $menuItem->setChilds($childs);
        $this->assertInternalType('array', $menuItem->getChilds());
        $this->assertCount(0, $menuItem->getChilds());
        $childs = [];
        $childs[] = ['url'=>'/test/url1'];
        $childs[] = ['url'=>'/test/url2'];
        $menuItem->setChilds($childs);
        $result = $menuItem->getChilds();
        $this->assertInternalType('array', $result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf('PsaNdp\MappingBundle\Object\MenuItem',$result[0]);
        $this->assertInstanceOf('PsaNdp\MappingBundle\Object\MenuItem',$result[1]);

    }

    public function testGetUrl()
    {
        $menuItem = new MenuItem();
        $menuItem->setUrl('/test/url/root');
        $childs = [];
        $menuItem->setChilds($childs);
        $this->assertEquals('/test/url/root', $menuItem->getUrl());
        $childs = [];
        $childs[] = ['url'=>'/test/url1'];
        $childs[] = ['url'=>'/test/url2'];
        $menuItem->setChilds($childs);
        $this->assertEquals('/test/url1', $menuItem->getUrl());

    }

}
