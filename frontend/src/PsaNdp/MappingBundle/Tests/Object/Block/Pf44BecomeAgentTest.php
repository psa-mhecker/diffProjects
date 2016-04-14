<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use Phake;
use PsaNdp\MappingBundle\Object\Block\Pf44BecomeAgent;

/**
 * Class Pf44BecomeAgentTest.
 */
class Pf44BecomeAgentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pf44BecomeAgent
     */
    protected $pf44;
    /**
     * @var Pf44BecomeAgent
     */
    protected $pf44WithValue;

    protected $block;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface');
        Phake::when($this->block)->getZoneTexte()->thenReturn('text');

        $this->pf44 = new Pf44BecomeAgent();
        $this->pf44WithValue = new Pf44BecomeAgent();
        $this->pf44WithValue->setFormAction('');
        $this->pf44WithValue->setSearchSubmit('');
        $this->pf44WithValue->setAroundMe('');
        $this->pf44WithValue->setListFilter(
            '1#2',
            ['1' => 'val1', '2' => 'value2'],
            ['1' => 'translation1', '2' => 'translation2']
        );
        $this->pf44WithValue->setFilterBy('');
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
        $result = $this->pf44->offsetExists($key);
        $this->assertTrue($result);
    }

    /**
     * @param string $key
     *
     * @dataProvider providePropertyExists
     */
    public function testOffsetExist($key)
    {
        $this->assertTrue($this->pf44WithValue->offsetExists($key));
    }

    /**
     * @return array
     */
    public function providePropertyExists()
    {
        return array(
            array('searchSubmit'),
            array('aroundMe'),
            array('listFilter'),
            array('filterBy'),
            // Property with default value set
            array('formAction'),
            array('information'),
            array('moreInformation'),
        );
    }

    /**
     * Test getInformation.
     */
    public function testGetInformation()
    {
        $result = $this->pf44->getInformation()->getSubtitle();

        $this->assertNull($result);

        $this->pf44->setBlock($this->block);

        $result = $this->pf44->getInformation();

        $this->assertSame('text', $result->getSubtitle());
    }

    /**
     * Test setDataFromArray.
     */
    public function testSetDataFromArray()
    {
        $data = array('block' => $this->block);

        $this->pf44->setDataFromArray($data);

        $this->assertSame($this->block, $this->pf44->getBlock());
        $this->assertSame('text', $this->pf44->getInformation()->getSubtitle());
    }

    /**
     * @param string $value
     * @param mixed  $expected
     * @param mixed  $key
     *
     * @dataProvider provideProperty
     *
     * @throws \Exception
     */
    public function testOffsetGet($value, $expected, $key)
    {
        $this->pf44->setBlock($this->block);

        $result = $this->pf44->offsetGet($value);

        $this->assertSame($expected, $result[$key]);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @dataProvider providePropertyForOffsetSet
     *
     * @throws \Exception
     */
    public function testOffsetSet($key, $value)
    {
        $this->pf44->setBlock($this->block);

        $this->pf44->offsetSet($key, $value);

        $this->assertSame($value, $this->pf44->offsetGet($key));
    }

    /**
     * @return array
     */
    public function provideProperty()
    {
        return array(
            array('information', 'text', 'text'),
        );
    }

    /**
     * @return array
     */
    public function providePropertyForOffsetSet()
    {
        return array(
            array('titleInformation', 'title'),
        );
    }
}
