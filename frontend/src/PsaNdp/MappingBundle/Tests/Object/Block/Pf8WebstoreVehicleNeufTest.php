<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use Phake;
use PsaNdp\MappingBundle\Object\Block\Pf8WebstoreVehicleNeuf;

/**
 * Class Pf23RangeBarTest.
 */
class Pf8WebstoreVehicleNeufTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pf8WebstoreVehicleNeuf
     */
    protected $pf8;

    /**
     * @var Pf8WebstoreVehicleNeuf
     */
    protected $pf8WithValue;

    protected $block;

    protected $ctaFactory;

    protected $title = 'Voir les véhicules en stock';

    protected $parcours = 1;

    protected $ctaUrl = '#';

    protected $ctaTitle = 'Voir les stocks disponibles';

    protected $translate = array(
        'noresult' => null,
        'errorload' => null,
        'searchTxt' => null,
        'hasNav_or_txt' => null,
        'cas1_title' => null,
        'cas1_btnAroundMe' => null,
        'cas1_searchType_label1' => null,
        'cas1_searchType_placeholder1' => null,
        'cas1_searchType_label2' => null,
        'cas1_searchType_placeholder2' => null,
        'hasNav_btnAroundMe' => null,
        'hasNav_pdvInput_label' => null,
        'distType' => null,
        'news' => null,
        'profit' => null,
        'ctaList' => null,
        'phone' => null,
        'trimming' => null,
        'consumption' => null,
        'emission' => null,
        '_or' => null,
        'currency' => null,
        'save' => null,
        'price_advice' => null,
        'legal_notices' => null,
    );

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface');

        $cta = Phake::mock('PsaNdp\MappingBundle\Object\Cta');
        $this->ctaFactory = Phake::mock('PsaNdp\MappingBundle\Object\Factory\CtaFactory');
        Phake::when($this->ctaFactory)->createFromArray(Phake::anyParameters())->thenReturn($cta);

        Phake::when($this->block)->getZoneTitre()->thenReturn($this->title);

        $this->pf8 = new Pf8WebstoreVehicleNeuf($this->ctaFactory);

        $this->pf8WithValue = new Pf8WebstoreVehicleNeuf($this->ctaFactory);
        $this->pf8WithValue->setBlock($this->block);
        $this->pf8WithValue->initializeParcours($this->parcours, $this->translate);
        $this->pf8WithValue->initializeCta($this->ctaUrl, $this->ctaTitle);
        $this->pf8WithValue->setTranslate($this->translate);
    }

    /**
     * Test setDataFromArray.
     */
    public function testSetDataFromArray()
    {
        $data = array('block' => $this->block);

        $this->pf8->setDataFromArray($data);

        $this->assertSame($this->block, $this->pf8->getBlock());
    }

    /**
     * Test GetBlock.
     */
    public function testGetBlock()
    {
        $this->assertNull($this->pf8->getBlock());

        $pf8WithValueBlock = $this->pf8WithValue->getBlock();

        $this->assertSame($this->block, $pf8WithValueBlock);
    }

    /**
     * Test SetBlock.
     */
    public function testSetBlock()
    {
        $this->pf8->setBlock($this->block);
        $this->assertSame($this->block, $this->pf8->getBlock());
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

        $result = $this->pf8->offsetGet($key);

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

        $this->pf8->offsetSet($key, $value);

        if (!$exception) {
            $this->assertSame($value, $this->pf8->offsetGet($key));
        }
    }

    /**
     * For Smarty isset() behavior, when property value is null, Smarty isset() should be false also.
     *
     * @param string $key
     * @dataProvider providePropertyExists
     */
    public function testOffsetIsNullNotExistsForSmartyIsset($key)
    {
        $result = $this->pf8->offsetExists($key);

        if (in_array($key, ['translate'])) {
            // For Property with default value existing
            $this->assertTrue($result);
        } else {
            $this->assertFalse($result);
        }
    }

    /**
     * @return array
     */
    public function provideProperty()
    {
        return array(
            array('translate', $this->translate, false),
        );
    }

    /**
     * @return array
     */
    public function providePropertyExists()
    {
        return array(
            array('translate'),
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
                'PsaNdp\MappingBundle\Object\Block\Pf8WebstoreVehicleNeuf:'.$key.' property is not defined'
            );
        }
    }

    /**
     * @param string $key
     * @param bool   $exception
     */
    public function initializeBloc($key, $exception)
    {
        $this->pf8->setBlock($this->block);
        $this->pf8->setTitle($this->title);
        $this->pf8->setTranslate($this->translate);

        if ($exception) {
            $this->setExpectedException(
                'Exception',
                'PsaNdp\MappingBundle\Object\Block\Pf8WebstoreVehicleNeuf:'.$key.' property is not defined'
            );
        }
    }
}
