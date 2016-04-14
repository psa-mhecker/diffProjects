<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use Doctrine\Common\Collections\ArrayCollection;
use Phake;
use PsaNdp\MappingBundle\Object\Block\Pf27CarPicker;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;

/**
 * Class Pf27CarPickerTest.
 */
class Pf27CarPickerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pf27CarPicker
     */
    protected $pf27;

    protected $cta;
    protected $block;
    protected $psaCta;
    protected $ctaInterface;

    protected $ctaStyle = 'style_niveau2';
    protected $ctaObjectStyle = null;
    protected $ctaObjectVersion = Cta::NDP_CTA_VERSION_LIGHT_BLUE;
    protected $ctaTitle = 'title';
    protected $ctaTarget = '_self';
    protected $ctaLink = 'google.com';
    protected $blockTitle = 'block title';
    protected $blockSubtitle = 'block subtitle';
    protected $mediaServer;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->cta = Phake::mock('PsaNdp\MappingBundle\Object\Cta');

        $this->psaCta = Phake::mock('PSA\MigrationBundle\Entity\Cta\PsaCta');
        Phake::when($this->psaCta)->getTitle()->thenReturn($this->ctaTitle);
        Phake::when($this->psaCta)->getAction()->thenReturn($this->ctaLink);

        $this->ctaInterface = Phake::mock('PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceInterface');
        Phake::when($this->ctaInterface)->getCta()->thenReturn($this->psaCta);
        Phake::when($this->ctaInterface)->getTarget()->thenReturn($this->ctaTarget);
        Phake::when($this->ctaInterface)->getStyle()->thenReturn($this->ctaStyle);
        $ctaReferences = new ArrayCollection(array($this->ctaInterface, $this->ctaInterface));

        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface');
        Phake::when($this->block)->getCtaReferences()->thenReturn($ctaReferences);
        Phake::when($this->block)->getZoneTitre()->thenReturn($this->blockTitle);
        Phake::when($this->block)->getZoneTitre2()->thenReturn($this->blockSubtitle);

        $this->mediaServer = Phake::mock('PsaNdp\MappingBundle\Services\MediaServerInitializer');
        $request = Phake::mock('Symfony\Component\HttpFoundation\Request');
        Phake::when($request)->get('blockPermanentId')->thenReturn('135.256.48');
        $stack = Phake::mock('Symfony\Component\HttpFoundation\RequestStack');
        Phake::when($stack)->getCurrentRequest()->thenReturn($request);
        Phake::when($this->mediaServer)->getMediaServer()->thenReturn('http://media.psa.test');

        $ctaFactory = Phake::mock('PsaNdp\MappingBundle\Object\Factory\CtaFactory');

        $priceManager = Phake::mock('PsaNdp\MappingBundle\Manager\PriceManager');
        $this->pf27 = new Pf27CarPicker($ctaFactory, $priceManager);
    }

    /**
     * Test getTitle.
     */
    public function testGetTitle()
    {
        $this->pf27->setBlock($this->block);

        $title = $this->pf27->getTitle();

        $this->assertSame($this->blockTitle, $title);
    }

    /**
     * @param string $key
     * @param mixed  $expected
     * @param bool   $exception
     *
     * @dataProvider provideProperty
     *
     * @throws \InvalidArgumentException
     */
    public function testOffsetGet($key, $expected, $exception)
    {
        $this->initializeBloc($key, $exception);

        $result = $this->pf27->offsetGet($key);

        $this->assertSame($expected, $result);
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @param bool   $exception
     *
     * @dataProvider provideProperty
     *
     * @throws \InvalidArgumentException
     */
    public function testOffsetSet($key, $value, $exception)
    {
        $this->initializeBloc($key, $exception);

        $this->pf27->offsetSet($key, $value);

        if (!$exception) {
            $this->assertSame($value, $this->pf27->offsetGet($key));
        }
    }

    /**
     * @return array
     */
    public function provideProperty()
    {
        return array(
            array('title', $this->blockTitle, false),
            array('toto', $this->blockSubtitle, true),
        );
    }

    /**
     * For Smarty isset() behavior, when property value is null, Smarty isset() should be false also.
     *
     * @param string $key
     *
     * @dataProvider providePropertyExists
     */
    public function testOffsetIsNullNotExistsForSmartyIsset($key)
    {
        $result = $this->pf27->offsetExists($key);
        $this->assertTrue($result);
    }

    /**
     * @return array
     */
    public function providePropertyExists()
    {
        return array(
            array('title'),
            array('subtitle'),
            array('translate'),
            array('block'),
        );
    }

    /**
     * @param string $key
     * @param bool   $exception
     */
    public function initializeBloc($key, $exception)
    {
        $this->pf27->setBlock($this->block);

        if ($exception) {
            $this->setExpectedException(
                'InvalidArgumentException',
                'PsaNdp\MappingBundle\Object\Block\Pf27CarPicker:'.$key.' property is not defined');
        }
    }
}
