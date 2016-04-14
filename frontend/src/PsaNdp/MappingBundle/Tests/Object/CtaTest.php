<?php

namespace PsaNdp\MappingBundle\Tests\Object;

use Phake;
use PsaNdp\MappingBundle\Object\Cta;

/**
 * Class CtaTest.
 */
class CtaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Cta
     */
    protected $cta;
    /**
     * @var Cta
     */
    protected $ctaWithValue;

    protected $ctaReference;
    protected $psaCta;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->psaCta = Phake::mock('PSA\MigrationBundle\Entity\Cta\PsaCta');
        Phake::when($this->psaCta)->getTitle()->thenReturn('child title');
        Phake::when($this->psaCta)->getAction()->thenReturn('child action');

        $this->ctaReference = Phake::mock('PSA\MigrationBundle\Entity\Cta\PsaCtaReferenceInterface');
        Phake::when($this->ctaReference)->getCta()->thenReturn($this->psaCta);

        $this->cta = new Cta();
        $this->ctaWithValue = new Cta();
        $this->ctaWithValue->setTitle('CTA Title');
        $this->ctaWithValue->setUrl('CTA url');
        $this->ctaWithValue->setTarget('CTA Target');
        $this->ctaWithValue->setType(Cta::NDP_CTA_TYPE_SIMPLELINK);
        $this->ctaWithValue->setImage('/image/fakepath.jpg');
        $this->ctaWithValue->setClass('');
        $this->ctaWithValue->setColor('1');
        // Note: Properties 'type' and 'version' has default value already
    }

    /**
     * @param string $key
     *
     * @dataProvider provideExistProperty
     */
    public function testOffsetExists($key)
    {
        $result = $this->ctaWithValue->offsetExists($key);

        $this->assertTrue($result);
    }

    /**
     * @return array
     */
    public function provideExistProperty()
    {
        return array(
            array('title'),
            array('target'),
            array('type'),
            array('url'),
            array('image'),
            array('class'),
            array('color'),
            array('url'),
        );
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
        $this->initializeCta();

        $this->setException($exception, $key);

        $result = $this->cta->offsetGet($key);

        $this->assertSame($expected, $result);
    }

    /**
     * @param string $key
     * @param string $value
     * @param bool   $exception
     *
     * @dataProvider provideProperty
     *
     * @throws \Exception
     */
    public function testOffsetSet($key, $value, $exception)
    {
        $this->initializeCta();

        $this->setException($exception, $key);

        $this->cta->offsetGet($key);

        $this->assertSame($value, $this->cta->offsetGet($key));
    }

    /**
     * return array.
     */
    public function provideProperty()
    {
        return array(
            array('title', 'title', false),
            array('target', '_self', false),
            array('bar', 'google.com', true),
        );
    }

    /**
     * Initialize cta.
     */
    public function initializeCta()
    {
        $data = array('title' => 'title', 'target' => '_self', 'style' => 'blue', 'action' => 'google.com');

        $this->cta->setDataFromArray($data);
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
                'PsaNdp\MappingBundle\Object\Cta:'.$key.' property is not defined');
        }
    }
}
