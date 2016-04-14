<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use Phake;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PsaNdp\MappingBundle\Object\Block\Pc79MurMediaManuel;
use PsaNdp\MappingBundle\Utils\SocialLinksManager;
use SocialLinks\Page;

/**
 * Class Pc79MurMediaManuelTest.
 */
class Pc79MurMediaManuelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pc79MurMediaManuel
     */
    protected $pc79;

    /**
     * @var Pc79MurMediaManuel
     */
    protected $pc79WithValue;

    protected $block;
    protected $psaPageMultiZoneMulti;
    protected $streamLikeMedia;
    protected $ctaFactory;
    protected $mediaFactory;

    protected $blockTitle = 'block title';
    protected $blockTemplate = 1;
    protected $mediaServer = 'http://media.psa.test';
    protected $isMobile = false;
    protected $blockCtaList = array(
        array(
            'style' => 'cta',
            'class' => 'link',
            'url' => null,
            'title' => null,
        ),
    );
    protected $blockGallerie = null;
    protected $blockMedia;

    /**
     * @var SocialLinksManager
     */
    private $linkManager;

    /**
     * @var Page
     */
    private $page;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface');
        Phake::when($this->block)->getZoneTitre()->thenReturn($this->blockTitle);
        Phake::when($this->block)->getZoneAffichage()->thenReturn($this->blockTemplate);

        $this->streamLikeMedia = Phake::mock('PsaNdp\MappingBundle\Utils\StreamlikeMedia');
        $this->ctaFactory = Phake::mock('PsaNdp\MappingBundle\Object\Factory\CtaFactory');
        $this->mediaFactory = Phake::mock('PsaNdp\MappingBundle\Object\Factory\MediaFactory');

        $this->page = Phake::mock('SocialLinks\Page');
        $this->linkManager = Phake::mock('PsaNdp\MappingBundle\Utils\SocialLinksManager');
        Phake::when($this->block)->getSocialLinksForImage(Phake::anyParameters())->thenReturn($this->page);
        Phake::when($this->block)->getSocialLinksFormStreamlike(Phake::anyParameters())->thenReturn($this->page);

        $this->pc79 = new Pc79MurMediaManuel($this->ctaFactory, $this->linkManager, $this->mediaFactory);

        $this->blockMedia = new PsaMedia();
        $this->blockMedia->setMediaPath('/image/fakepath.jpg');

        $this->psaPageMultiZoneMulti = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneMultiConfigurableInterface');
        Phake::when($this->psaPageMultiZoneMulti)->getMedia()->thenReturn($this->blockMedia);

        $this->pc79WithValue = new Pc79MurMediaManuel($this->ctaFactory, $this->linkManager, $this->mediaFactory);
        $this->pc79WithValue->setTitle($this->blockTitle);
        $this->pc79WithValue->setCtaList($this->blockCtaList);
    }

    /**
     * Test setDataFromArray.
     */
    public function testSetDataFromArray()
    {
        $data = array('block' => $this->block);

        $this->pc79->setDataFromArray($data);

        $this->assertSame($this->block, $this->pc79->getBlock());
    }

    /**
     * @param string $key
     * @param string $expected
     * @param bool   $exception
     *
     * @dataProvider provideProperty
     *
     * @throws \Exception
     */
    public function testOffsetGet($key, $expected, $exception)
    {
        $this->initializeBloc($key, $exception);

        $this->setException($exception, $key);

        $result = $this->pc79->offsetGet($key);

        $this->assertSame($expected, $result);
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @param bool   $exception
     *
     * @dataProvider provideProperty
     *
     * @throws \Exception
     */
    public function testOffsetSet($key, $value, $exception)
    {
        $this->initializeBloc($key, $exception);

        $this->pc79->offsetSet($key, $value);

        if (!$exception) {
            $this->assertSame($value, $this->pc79->offsetGet($key));
        }
    }

    /**
     * Test getTitle.
     */
    public function testGetTitle()
    {
        $this->pc79->setBlock($this->block);

        $title = $this->pc79->getTitle();

        $this->assertSame($this->blockTitle, $title);
    }

    /**
     * Test getGallerie.
     */
    public function testGetGallerie()
    {
        $this->pc79->setBlock($this->block);

        $gallerie = $this->pc79->getGallery();

        $this->assertSame($this->blockGallerie, $gallerie);
    }

    /**
     * For Smarty isset() behavior, when property value is null, Smarty isset() should be false also.
     *
     * @param string $key
     * @dataProvider providePropertyExists
     */
    public function testOffsetIsNullNotExistsForSmartyIsset($key)
    {
        $result = $this->pc79->offsetExists($key);
        $this->assertTrue($result);
    }

    /**
     * @return array
     */
    public function provideProperty()
    {
        return array(
            array('title', $this->blockTitle, false),
        );
    }

    /**
     * @return array
     */
    public function providePropertyExists()
    {
        return array(
            array('block'),
            array('title'),
            array('close'),
            array('gallery'),
            array('translate'),
            array('ctaList'),
        );
    }

    /**
     * @param bool   $exception
     * @param string $key
     */
    public function setException($exception, $key)
    {
        if ($exception) {
            $this->setExpectedException(
                'Exception',
                'PsaNdp\MappingBundle\Object\Cta:'.$key.' property is not defined'
            );
        }
    }

    /**
     * @param string $key
     * @param bool   $exception
     */
    public function initializeBloc($key, $exception)
    {
        $this->pc79->setBlock($this->block);

        if ($exception) {
            $this->setExpectedException(
                'Exception',
                'PsaNdp\MappingBundle\Object\Block\Pc79MurMediaManuel:'.$key.' property is not defined'
            );
        }
    }
}
