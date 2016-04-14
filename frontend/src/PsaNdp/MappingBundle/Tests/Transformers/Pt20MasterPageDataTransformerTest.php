<?php

namespace PsaNdp\MappingBundle\Tests\Transformers;

use Doctrine\Common\Collections\ArrayCollection;
use Phake;
use PsaNdp\MappingBundle\Transformers\Pt20MasterPageDataTransformer;
use PSA\MigrationBundle\Entity\Cta\PsaCta;

/**
 * Class Pt20MasterPageDataTransformerTest.
 */
class Pt20MasterPageDataTransformerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Pt20MasterPageDataTransformer */
    protected $transformer;
    protected $pageVersion;
    protected $pageZone;
    protected $page;
    protected $pt20;
    protected $translator;


    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->pt20 = Phake::mock('PsaNdp\MappingBundle\Object\Block\Pt20MasterPage');
        $this->transformer = new Pt20MasterPageDataTransformer($this->pt20);
        $this->translator = Phake::mock('Symfony\Component\Translation\TranslatorInterface');
        $this->transformer->setTranslator($this->translator);
    }

    /**
     *
     */
    public function testFetch()
    {
        $pages = new ArrayCollection();
        $pages->add($this->page);
        $pages->add($this->page);

        $source = array('pageZone' => $this->pageZone, 'subPages' => $pages);
        $result = $this->transformer->fetch($source, true);

        Phake::verify($this->pt20, Phake::times(1))->setDataFromArray(Phake::anyParameters());
        Phake::verify($this->pt20, Phake::times(1))->setTranslate(Phake::anyParameters());
        Phake::verify($this->pt20, Phake::times(1))->initData(Phake::anyParameters());

        $this->assertArrayHasKey('slicePT20', $result);

        $result = $this->transformer->fetch($source, false);

        Phake::verify($this->pt20, Phake::times(2))->setDataFromArray(Phake::anyParameters());
        Phake::verify($this->pt20, Phake::times(2))->setTranslate(Phake::anyParameters());
        Phake::verify($this->pt20, Phake::times(2))->initData(Phake::anyParameters());

        $this->assertArrayHasKey('slicePT20', $result);
    }


}
