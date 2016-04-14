<?php

namespace PsaNdp\MappingBundle\Tests\Transformers;

use Phake;
use PsaNdp\MappingBundle\Transformers\Pf44DevenirAgentDataTransformer;

/**
 * Class Pf44DevenirAgentDataTransformerTest.
 */
class Pf44DevenirAgentDataTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pf44DevenirAgentDataTransformer
     */
    protected $transformer;

    protected $block;
    protected $translator;
    protected $becomeAgent;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface');

        $this->becomeAgent = Phake::mock('PsaNdp\MappingBundle\Object\Block\Pf44BecomeAgent');

        $this->translator = Phake::mock('Symfony\Bundle\FrameworkBundle\Translation\Translator');

        $this->transformer = new Pf44DevenirAgentDataTransformer($this->becomeAgent);
        $this->transformer->setTranslator($this->translator);
    }

    /**
     * Test fetch.
     */
    public function testFetch()
    {
        $data = array(
            'block' => $this->block,
            'urlJson' => null,
            'or' => null,
            'searchSubmit' => null,
            'moreFilter' => null,
            'filterBy' => null,
            'autocompletion' => false,
            'length' => 3,
            'placeholderInput' => null,
            'btnAroundMe' => null,
            'mapParam' => array('picto' => 'http://www.hostingpics.net/thumbs/12/51/96/mini_125196pin.png',
                                 'pictoOn' => 'http://www.hostingpics.net/thumbs/58/37/85/mini_583785pinon.png',
                                 'pictoOff' => 'http://www.hostingpics.net/thumbs/72/43/39/mini_724339pinoff.png',
                                 'textLinkInfoWindow' => null, ),
            'translate' => array('seeMore' => null,
                                  'loaderdatatxt' => null, ),
            'errorload' => null,
            'resultFound' => null,
            'resultNotFound' => null,
            'visuMap' => array('src' => 'https://maps.googleapis.com/maps/api/staticmap?center=48.8727795,2.2988006&amp;zoom=17&amp;size=640x360', 'alt' => null),
            'pictoMap' => 'http://www.hostingpics.net/thumbs/19/71/15/mini_197115pinlockm.png',
            'beforeName' => null,
            'linkMoreInfo' => array('title' => null, 'url' => '#'),
            'linkMorInfo' => array('target' => null),
            'translate' => array('seeMore' => null, 'loaderdatatxt' => null),
        );

        $this->transformer->fetch($data, false);

        Phake::verify($this->becomeAgent)->setDataFromArray($data);
    }
}
