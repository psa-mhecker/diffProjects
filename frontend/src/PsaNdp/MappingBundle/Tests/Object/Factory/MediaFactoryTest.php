<?php

namespace PsaNdp\MappingBundle\Tests\Object;

use PsaNdp\MappingBundle\Object\Factory\MediaFactory;
use Phake;

class MediaFactoryTest  extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MediaFactory
     */
    protected $factory;

    /**
     * @var mixed
     */
    protected $streamlikeMedia;

    /**
     * initialize media factory.
     */
    public function setUp()
    {
        $request = Phake::mock('Symfony\Component\HttpFoundation\Request');
        $requestStack = Phake::mock('Symfony\Component\HttpFoundation\RequestStack');
        Phake::when($requestStack)->getCurrentRequest()->thenReturn($request);
        $streamlike = Phake::mock('PsaNdp\MappingBundle\Object\Streamlike');
        $this->streamlikeMedia = Phake::mock('PsaNdp\MappingBundle\Utils\StreamlikeMedia');
        Phake::when($this->streamlikeMedia)->get(Phake::anyParameters())->thenReturn($streamlike);
        $mediaServerInitializer = Phake::mock('PsaNdp\MappingBundle\Services\MediaServerInitializer');
        Phake::when($mediaServerInitializer)->getMediaServer()->thenReturn('http://media/');
        $repository = Phake::mock('PSA\MigrationBundle\Repository\PsaMediaFormatRepository');
        $format = Phake::mock('PSA\MigrationBundle\Entity\Media\PsaMediaFormat');
        $site = Phake::mock('PSA\MigrationBundle\Entity\Site\PsaSite');
        $media = Phake::mock('PSA\MigrationBundle\Entity\Media\PsaMedia');
        Phake::when($media)->getMediaPath()->thenReturn('http://example.org/dummy.jpg');
        Phake::when($site)->getStreamlikeDefaultCover()->thenReturn($media);
        $siteRepository = Phake::mock('PSA\MigrationBundle\Repository\PsaSiteRepository');
        Phake::when($format)->getMediaFormatLabel()->thenReturn('DUMMY_LABEL');
        Phake::when($format)->getMediaFormatId()->thenReturn(1);
        Phake::when($repository)->findAll()->thenReturn([$format]);
        Phake::when($siteRepository)->findOneBy(Phake::anyParameters())->thenReturn($site);
        $this->factory = new MediaFactory($this->streamlikeMedia, $mediaServerInitializer, $repository, $siteRepository, $requestStack);
    }

    /**
     * Test createFromMedia return a streamlike PsaMedia.
     *
     * @return mixed
     */
    private function getStreamlikeMedia()
    {
        $type = Phake::mock('PSA\MigrationBundle\Entity\Media\PsaMediaType');
        Phake::when($type)->getMediaTypeId()->thenReturn('streamlike');
        $media = Phake::mock('PSA\MigrationBundle\Entity\Media\PsaMedia');
        Phake::when($media)->getMediaType()->thenReturn($type);
        Phake::when($media)->getMediaRemoteId()->thenReturn('123456');

        return $media;
    }

    /**
     * Test createFromMedia return a image PsaMedia.
     *
     * @return mixed
     */
    private function getImageMedia()
    {
        $type = Phake::mock('PSA\MigrationBundle\Entity\Media\PsaMediaType');
        Phake::when($type)->getMediaTypeId()->thenReturn('image');
        $media = Phake::mock('PSA\MigrationBundle\Entity\Media\PsaMedia');
        Phake::when($media)->getMediaType()->thenReturn($type);

        return $media;
    }

    /**
     * Test createFromMedia return a unknow PsaMedia.
     *
     * @return mixed
     */
    private function getBadMedia()
    {
        $type = Phake::mock('PSA\MigrationBundle\Entity\Media\PsaMediaType');
        Phake::when($type)->getMediaTypeId()->thenReturn('dummy');
        $media = Phake::mock('PSA\MigrationBundle\Entity\Media\PsaMedia');
        Phake::when($media)->getMediaType()->thenReturn($type);

        return $media;
    }

    /**
     * Test method createFromMedia.
     */
    public function testCreateFromMedia()
    {
        /* test creation of streamlike */
        $media = $this->getStreamlikeMedia();
        $video = $this->factory->createFromMedia($media);
        Phake::verify($this->streamlikeMedia)->get('123456');
        Phake::verify($video)->setPoster(Phake::anyParameters());
        /* Test creation of image  */
        $media = $this->getImageMedia();
        $image = $this->factory->createFromMedia($media);
        $this->assertInstanceOf('PsaNdp\MappingBundle\Object\Image', $image);
        $image = $this->factory->createFromMedia($media, ['format' => 1]);
        $this->assertInstanceOf('PsaNdp\MappingBundle\Object\Image', $image);
        $image = $this->factory->createFromMedia($media, ['format' => 'DUMMY_LABEL']);
        $this->assertInstanceOf('PsaNdp\MappingBundle\Object\Image', $image);
        $image = $this->factory->createFromMedia($media, ['format' => 'DUMMY_LABEL','autoCrop'=>1]);
        $this->assertInstanceOf('PsaNdp\MappingBundle\Object\Image', $image);
        $this->assertContains('?autocrop=1', $image->getSrc());
    }
    /**
     * Test method createFromMedia.
     *
     * @
     */
    public function testCreateFromMediaBad()
    {
        $this->setExpectedException('\InvalidArgumentException');
        /* test creation of unknow media */
        $media = $this->getBadMedia();
        $this->factory->createFromMedia($media);
    }
}
