<?php

namespace PsaNdp\MappingBundle\Tests\Object;

use Tests\Object\ConcreteObject;

/**
 * AbstractObjectTest.
 */
class AbstractObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOffsetSetException()
    {
        $mock = $this->getMockBuilder('\PsaNdp\MappingBundle\Object\AbstractObject')
            ->setMethods(null)
            ->getMock();

        $mock->offsetSet('dummy', 'value');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOffsetGetWrongPropertyException()
    {
        $mock = $this->getMockBuilder('\PsaNdp\MappingBundle\Object\AbstractObject')
            ->setMethods(null)
            ->getMock();

        $mock['dummy'];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOffsetGetNoGetterException()
    {
        $object = new ConcreteObject();

        $object['text'];
    }

    public function testSetDataFromArrayWithoutSetter()
    {
        $data = array('anotherText' => 'value');
        $object = new ConcreteObject();

        $object->setDataFromArray($data);

        $this->assertEquals('value', $object['anotherText']);
    }

    public function testUnsetOffset()
    {
        $data = array(
            'anotherText' => 'value',

        );
        $object = new ConcreteObject();

        $object->setDataFromArray($data);

        unset($object['anotherText']);

        $this->assertNull($object['anotherText']);
    }

    public function testUnsetOffsetWithMappedValue()
    {
        $data = array(
            'anotherText' => 'value',

        );
        $object = new ConcreteObject();

        $object->setDataFromArray($data);

        unset($object['mappedText']);

        $this->assertNull($object['mappedText']);
    }

    public function testOffsetExists()
    {
        $object = new \Tests\Object\ConcreteObject();
    }
}

namespace Tests\Object;

class ConcreteObject extends \PsaNdp\MappingBundle\Object\AbstractObject
{
    protected $mapping = array(
        'mappedText' => 'anotherText',
    );
    protected $text;
    protected $anotherText;

    /**
     * Get anotherTest.
     *
     * @return mixed
     */
    public function getAnotherText()
    {
        return $this->anotherText;
    }
}
