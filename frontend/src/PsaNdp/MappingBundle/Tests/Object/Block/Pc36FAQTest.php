<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use Phake;
use PsaNdp\MappingBundle\Object\Block\Pc36FAQ;

class Pc36FAQTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pc36FAQ
     */
    protected $pc36;

    /**
     * @var Pc36FAQ
     */
    protected $pc36WithValue;

    protected $block;
    protected $psaPageZoneMulti;

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
    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface');
        Phake::when($this->block)->getZoneTitre()->thenReturn($this->blockTitle);
        Phake::when($this->block)->getZoneAffichage()->thenReturn($this->blockTemplate);

        $this->pc36 = new Pc36FAQ();

        $this->psaPageZoneMulti = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti');

        $this->pc36WithValue = new Pc36FAQ();

        $this->pc36WithValue->setTitle($this->blockTitle);
        $this->pc36WithValue->setCtaList($this->blockCtaList);
    }

    /**
     * Test setDataFromArray.
     */
    public function testSetDataFromArray()
    {
        $backUrl = '/test.html';
        $data = array('faqBackURL' => $backUrl);

        $this->pc36->setDataFromArray($data);

        $this->assertSame($backUrl, $this->pc36->getFaqBackURL());
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
        $result = $this->pc36->offsetExists($key);
        $this->assertTrue($result);
    }

    public function testGetFaqTitle()
    {
        $testMethod = 'faqTitle';
        $testData = 'testData';
        $data = array('faqTitle' => $testData);

        $this->pc36->setDataFromArray($data);

        $this->assertSame($testData, $this->pc36->offsetGet($testMethod));
    }

    /**
     * @return array
     */
    public function providePropertyExists()
    {
        return array(
            array('faqTitle'),
            array('faqSubTitle'),
            array('faqBackURL'),
            array('urlJson'),
        );
    }
}
