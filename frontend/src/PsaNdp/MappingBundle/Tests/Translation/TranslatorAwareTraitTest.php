<?php

namespace PsaNdp\MappingBundle\Tests\Translation;

class TranslatorAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    protected $trait;

    public function setUp()
    {
        $translator = $this->getMockBuilder('\Symfony\Component\Translation\TranslatorInterface')
            ->getMock();

        $map = array(
            array('LABEL_1', [], 1, 'fr', 'LABEl_1_TRANSLATION'),
            array('LABEL_2', [], 1, 'fr', 'LABEl_2_TRANSLATION'),
            array('LABEL_MISSING', [], 1, 'fr', 'LABEL_MISSING'),
        );
        $translator->method('trans')
            ->will($this->returnValueMap($map));

        $this->trait = $this->getMockForTrait('\PsaNdp\MappingBundle\Translation\TranslatorAwareTrait');
        $this->trait->setTranslator($translator);
        $this->trait->setDomain(1);
        $this->trait->setLocale('fr');
    }

    public function testTrans()
    {
        $this->assertEquals('LABEl_1_TRANSLATION', $this->trait->trans('LABEL_1'));
        $this->assertEquals('', $this->trait->trans('LABEL_MISSING'));
    }
}
