<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use PsaNdp\MappingBundle\Object\Block\Pc41MentionsJuridiques;

/**
 * Class Pc41MentionsJuridiquesTest.
 */
class Pc41MentionsJuridiquesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pc41MentionsJuridiques
     */
    protected $pc41;

    /**
     * @var Pc41MentionsJuridiques
     */
    protected $pc41WithValue;

    /**
     * @var array
     */
    protected $mentions;

    /**
     * @var array
     */
    protected $mention = 'mention';

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->pc41 = new Pc41MentionsJuridiques();

        $this->pc41WithValue = new Pc41MentionsJuridiques();
        $this->mentions = [$this->mention];
        $this->pc41WithValue->setMentions($this->mentions);
    }

    /**
     * Test setDataFromArray.
     */
    public function testSetDataFromArray()
    {
        $data = array('mentions' => $this->mentions);
        $this->pc41->setDataFromArray($data);
        $this->verifyMentions($this->pc41->getMentions());
    }

    public function testSetMentions()
    {
        $this->pc41->setMentions($this->mentions);
        $this->verifyMentions($this->pc41->getMentions());
    }

    public function testGetMentions()
    {
        $this->assertNull($this->pc41->getMentions());
        $this->verifyMentions($this->pc41WithValue->getMentions());
    }

    private function verifyMentions($mentions)
    {
        $this->assertTrue(is_array($mentions));
        $this->assertEquals(1, count($mentions));
        $this->assertEquals($this->mention, reset($mentions));
    }
}
