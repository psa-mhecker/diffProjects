<?php
namespace Itkg\Tests\Utils;


use Itkg\Utils\ImageCompareUtils;

class ImageCompareUtilsTest extends \PHPUnit_Framework_TestCase
{
    private $compareUtils;

    public function setUp()
    {
        $this->compareUtils = new ImageCompareUtils();
    }

    public function testCompare()
    {
        $path = __DIR__.'/resources/chat.jpg';
        $path2 = __DIR__.'/resources/question.jpg';
        $ecart = $this->compareUtils->compare($path, $path2);
        $this->assertEquals(79, $ecart);

        $path2 = __DIR__.'/resources/toto.jpg';
        $ecart = $this->compareUtils->compare($path, $path2);
        $this->assertFalse($ecart);
    }

    public function testGetSignature()
    {
        $path = __DIR__.'/resources/chat.jpg';
        $signature = $this->compareUtils->getSignature($path);
        $this->assertInternalType('array', $signature);

        $path = __DIR__.'/resources/sample.png';
        $signature =$this->compareUtils->getSignature($path);
        $this->assertInternalType('array', $signature);

        $path = __DIR__.'/resources/toto.jpg';
        $signature =$this->compareUtils->getSignature($path);
        $this->assertFalse($signature);

        $path = __DIR__.'/resources/alien.gif';
        $signature =$this->compareUtils->getSignature($path);
        $this->assertFalse($signature);

    }

    public function testDimension()
    {
        $path = __DIR__.'/resources/chat.jpg';
        $this->compareUtils->getSignature($path);
        $this->assertEquals(300,$this->compareUtils->getWidth());
        $this->assertEquals(300,$this->compareUtils->getheight());

        $path = __DIR__.'/resources/alien.gif';
        $this->compareUtils->getSignature($path);
        $this->assertEquals(0,$this->compareUtils->getWidth());
        $this->assertEquals(0,$this->compareUtils->getheight());

    }
}

