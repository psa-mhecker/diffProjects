<?php

namespace PsaNdp\MappingBundle\Tests\Object;

use Doctrine\Common\Collections\ArrayCollection;
use Phake;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;

/**
 * Class CtaTest.
 */
class CtaFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CtaFactory
     */
    protected $ctaFactory;

    protected $ctaReference;
    protected $psaCta;
    protected $mediaServer;
    protected $shareObjectService;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->psaCta = Phake::mock('PSA\MigrationBundle\Entity\Cta\PsaCta');
        Phake::when($this->psaCta)->getTitle()->thenReturn('child title');
        Phake::when($this->psaCta)->getAction()->thenReturn('child action');
        Phake::when($this->psaCta)->getTitleMobile()->thenReturn('child title mobile');
        $this->shareObjectService = Phake::mock('PsaNdp\MappingBundle\Services\ShareServices\ShareObjectService');

        $this->ctaReference = Phake::mock('PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceInterface');
        Phake::when($this->ctaReference)->getCta()->thenReturn($this->psaCta);

        $this->mediaServer = Phake::mock('PsaNdp\MappingBundle\Services\MediaServerInitializer');
        $request = Phake::mock('Symfony\Component\HttpFoundation\Request');
        Phake::when($request)->get('blockPermanentId')->thenReturn('135.256.48');
        $stack = Phake::mock('Symfony\Component\HttpFoundation\RequestStack');
        Phake::when($stack)->getCurrentRequest()->thenReturn($request);
        Phake::when($this->mediaServer)->getMediaServer()->thenReturn('serverPath');

        $this->ctaFactory = new CtaFactory($this->mediaServer, $stack, $this->shareObjectService);
    }

    /**
     * @param string $style
     * @param string $target
     * @param string $expectedColor
     * @param bool   $isMobile
     *
     * @dataProvider provideCtaReferenceProperty
     */
    public function testInitFromReference($style, $target, $expectedColor, $isMobile)
    {
        Phake::when($this->ctaReference)->getReferenceStatus()->thenReturn(2);
        Phake::when($this->shareObjectService)->isMobile()->thenReturn($isMobile);

        $this->initializeStyleAndTargetCta($style, $target);

        $cta = $this->ctaFactory->createFromReference($this->ctaReference);

        if ($style === 'style_niveau4') {
            $this->assertSame('TYPE_SIMPLELINK', $cta->getType());
        } else {
            $this->assertSame('TYPE_BUTTON', $cta->getType());
        }

        if ($isMobile) {
            $this->assertSame('child title mobile', $cta->getTitle());
        } else {
            $this->assertSame('child title', $cta->getTitle());
        }
        $this->assertSame($expectedColor, $cta->getColor());
        $this->assertSame($target, $cta->getTarget());
    }

    /**
     * return array.
     */
    public function provideCtaReferenceProperty()
    {
        return array(
            array('style_niveau1', '_self', Cta::NDP_CTA_VERSION_DARK_BLUE, false),
            array('style_niveau2', '_self', Cta::NDP_CTA_VERSION_LIGHT_BLUE, true),
            array('style_niveau3', '_self', Cta::NDP_CTA_VERSION_GREY, false),
            array('style_niveau4', '_self', Cta::NDP_CTA_VERSION_DARK_BLUE, true),
        );
    }

    /**
     * @param string $style
     * @param string $target
     *
     * @dataProvider provideCtaReferenceProperty
     */
    public function testInitFromCtaReferenceWithCtaChild($style, $target)
    {
        $ctaReference2 = Phake::mock('PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceInterface');
        Phake::when($ctaReference2)->getCta()->thenReturn($this->psaCta);
        $children = new ArrayCollection(array($ctaReference2, $ctaReference2));

        Phake::when($this->ctaReference)->getReferenceStatus()->thenReturn(4);
        Phake::when($this->ctaReference)->getChildCtas()->thenReturn($children);

        $this->initializeStyleAndTargetCta($style, $target);

        $cta = $this->ctaFactory->createFromReference($this->ctaReference);

        Phake::verify($this->ctaReference)->getChildCtas();

        $this->assertSame('TYPE_DROPDOWNLIST', $cta->getType());

        $this->assertSame($target, $cta->getTarget());
        $this->assertSame(Cta::NDP_CTA_VERSION_DARK_BLUE, $cta->getColor());

        $listItems = $cta->getOptions();

        $this->assertCount(2, $listItems);

        foreach ($listItems as $item) {
            $this->assertSame('child title', $item->getTitle());
            $this->assertSame('child action', $item->getUrl());
        }
    }

    /**
     * @param string $style
     * @param string $target
     */
    public function initializeStyleAndTargetCta($style, $target)
    {
        Phake::when($this->ctaReference)->getStyle()->thenReturn($style);
        Phake::when($this->ctaReference)->getTarget()->thenReturn($target);
    }
}
