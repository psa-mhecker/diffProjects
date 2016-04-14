<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block\Pc23Object;

use PsaNdp\MappingBundle\Object\Block\Pc23Object\StructureA;
use \Phake;


class StructureATest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var StructureA
     */
    protected $structure;
    /**
     * @var \PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface
     */
    protected $multi;


    public function setup()
    {
        $this->structure = new StructureA();
        $image = Phake::mock('PsaNdp\MappingBundle\Object\MediaInterface');
        $factory = Phake::mock('PsaNdp\MappingBundle\Object\Factory\MediaFactory');
        Phake::when($factory)->createFromMedia(Phake::anyParameters())->thenReturn($image);
        $this->structure->setMediaFactory($factory);
        $media = Phake::mock('PSA\MigrationBundle\Entity\Media\PsaMedia');
        $this->multi = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface');
        Phake::when($this->multi)->getMedia()->thenReturn($media);
        Phake::when($this->multi)->getMediaId2()->thenReturn($media);
        Phake::when($this->multi)->getMediaId3()->thenReturn($media);
        Phake::when($this->multi)->getMediaId4()->thenReturn($media);

    }

    public function testGetFile()
    {
        $this->assertEquals('./pc23/murmedia-a.tpl', $this->structure->getFile());
    }


    public function testGetFormats()
    {
        $formats =  $this->structure->getFormats();
        $this->assertCount(4, $formats);
        $this->assertInternalType('array', $formats);
    }

    public function testInit()
    {
        $this->structure->init($this->multi);
        $images = $this->structure->getImages();
        $this->assertCount(4, $images);
        $this->assertInternalType('array', $images);
        $this->assertInstanceOf('PsaNdp\MappingBundle\Object\MediaInterface',$images[0] );

    }

}
