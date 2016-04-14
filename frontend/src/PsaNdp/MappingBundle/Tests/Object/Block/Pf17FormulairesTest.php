<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use Doctrine\Common\Collections\ArrayCollection;
use Phake;
use PSA\MigrationBundle\Entity\Content\PsaContent;
use PSA\MigrationBundle\Entity\Content\PsaContentVersion;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Block\Pf17Formulaires;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Object\Factory\MediaFactory;

/**
 * Class Pf17FormulairesTest.
 */
class Pf17FormulairesTest extends \PHPUnit_Framework_TestCase
{
    protected $pf17;
    protected $block;
    protected $media;
    protected $content;
    protected $ctaFactory;
    protected $mediaFactory;
    protected $pf17WithValue;
    protected $contentVersion;
    protected $mediaAlt = 'mediaAlt';
    protected $mediaSource = 'mediaSource';

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->contentVersion = Phake::mock('PSA\MigrationBundle\Entity\Content\PsaContentVersion');
        $this->content = Phake::mock('PSA\MigrationBundle\Entity\Content\PsaContent');

        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface');

        $this->media = Phake::mock('PSA\MigrationBundle\Entity\Media\PsaMedia');
        Phake::when($this->media)->getMediaPath()->thenReturn($this->mediaSource);
        Phake::when($this->media)->getMediaAlt()->thenReturn($this->mediaAlt);

        $this->ctaFactory = Phake::mock('PsaNdp\MappingBundle\Object\Factory\CtaFactory');
        $this->mediaFactory = Phake::mock('PsaNdp\MappingBundle\Object\Factory\MediaFactory');
        $this->pf17 = new Pf17Formulaires($this->ctaFactory, $this->mediaFactory);

        $this->pf17WithValue = new Pf17Formulaires($this->ctaFactory, $this->mediaFactory);
        $this->pf17WithValue->setBlock($this->block);
    }

    public function testSetDataFromArray()
    {
        $data = array('block' => $this->block);
        $this->pf17->setDataFromArray($data);
        $this->assertSame($this->block, $this->pf17->getBlock());
    }

    public function testGetBlock()
    {
        $this->assertNull($this->pf17->getBlock());

        $pf17WithValueBlock = $this->pf17WithValue->getBlock();
        $this->assertSame($this->block, $pf17WithValueBlock);
    }

    public function testSetBlock()
    {
        $this->pf17->setBlock($this->block);
        $this->assertSame($this->block, $this->pf17->getBlock());
    }

    /**
     * @param string $code
     * @param string $title13
     * @param bool   $isMobile
     * @param string $expected
     *
     * @dataProvider provideInstance
     */
    public function testGetInstance($code, $title13, $isMobile, $expected)
    {
        $this->pf17WithValue->setIsMobile($isMobile);
        Phake::when($this->contentVersion)->getContentCode()->ThenReturn($code);
        Phake::when($this->contentVersion)->getContentTitle13()->ThenReturn($title13);
        Phake::when($this->content)->getCurrentVersion()->ThenReturn($this->contentVersion);
        $this->pf17WithValue->setContent($this->content);

        $this->assertSame($expected, $this->pf17WithValue->getInstance());
    }

    /**
     * @return array
     */
    public function provideInstance()
    {
        return array(
            array('code', null, false, 'code'),
            array(null, null, false, ''),
            array(null, null, true, ''),
            array(null, 'mobile', true, 'mobile'),
            array('', 'mobile', true, 'mobile'),
            array('code', null, true, ''),
        );
    }

    /**
     * @param $isMobile
     *
     * @dataProvider provideIsMobile
     */
    public function testGetBrandIdConnector($isMobile)
    {
        $this->pf17WithValue->setIsMobile($isMobile);
        $expected = 'pc';

        if ($isMobile) {
            $expected = 'mobile';
        }

        $this->assertSame($expected, $this->pf17WithValue->getBrandIdConnector());
    }

    /**
     * @param $isMobile
     *
     * @dataProvider provideIsMobile
     */
    public function testGetContext($isMobile)
    {
        $this->pf17WithValue->setIsMobile($isMobile);
        $expected = 'desktop';

        if ($isMobile) {
            $expected = 'mobile';
        }

        $this->assertSame($expected, $this->pf17WithValue->getContext());
    }

    /**
     * @return array
     */
    public function provideIsMobile()
    {
        return array(
            array(true),
            array(false),
        );
    }
}
