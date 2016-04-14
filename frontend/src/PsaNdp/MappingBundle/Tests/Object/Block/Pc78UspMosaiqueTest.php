<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use Doctrine\Common\Collections\Collection;
use Phake;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Block\Pc78UspMosaique;

/**
 * Class Pc78UspMosaiqueTest.
 */
class Pc78UspMosaiqueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pc78UspMosaique
     */
    protected $pc78;

    /**
     * @var Pc78UspMosaique
     */
    protected $pc78WithValue;

    /**
     * @var PsaPageZoneConfigurableInterface
     */
    protected $block;

    /**
     * @var array
     */
    protected $collection = [];

    protected $blockTitle = 'block title';
    protected $close = 'close';
    protected $mediaServer = 'http://media.psa.test';
    protected $isMobile = false;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->pc78 = new Pc78UspMosaique();

        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface');
        Phake::when($this->block)->getZoneTitre()->thenReturn($this->blockTitle);

        $this->pc78WithValue = new Pc78UspMosaique();
        $this->pc78WithValue = $this->pc78WithValue->setBlock(Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface'));
        $this->pc78WithValue = $this->pc78WithValue->setTranslate(['close' => $this->close]);
        $this->pc78WithValue = $this->pc78WithValue->setArticles($this->collection, $this->mediaServer, $this->isMobile);
    }

    public function testSetDataFromArray()
    {
        $data = array('block' => $this->block);
        $this->pc78->setDataFromArray($data);
        $this->assertSame($this->block, $this->pc78->getBlock());
    }

    public function testGetBlock()
    {
        $this->assertNull($this->pc78->getBlock());
        $this->assertNull($this->pc78WithValue->getTitle());
    }

    public function testSetBlock()
    {
        $this->pc78 = $this->pc78->setBlock($this->block);
        $this->assertEquals($this->blockTitle, $this->pc78->getTitle());
    }

    public function testSetTranslate()
    {
        $closeTranslated = 'close translated';
        $this->pc78 = $this->pc78->setTranslate(['close' => $closeTranslated]);
        $this->assertEquals($closeTranslated, $this->pc78->getClose());
    }

    public function testSetClose()
    {
        $close = 'close';
        $this->pc78 = $this->pc78->setClose($close);
        $this->assertEquals($close, $this->pc78->getClose());
    }

    public function testGetClose()
    {
        $this->assertNull($this->pc78->getClose());
        $this->assertEquals($this->close, $this->pc78WithValue->getClose());
    }

    public function testSetArticles()
    {
        $pc78 = $this->pc78->setArticles($this->collection, $this->mediaServer, $this->isMobile);
        $articles = $pc78->getArticles();
        $this->assertTrue(is_array($articles));
        $this->assertEquals(1, count($articles));

        $article = reset($articles);
        $this->assertTrue(is_array($article));
        $this->assertEquals(2, count($article));

        $articleKeys = array_keys($article);
        $this->assertContains('unique', $articleKeys);
        $this->assertContains('cols', $articleKeys);

        $this->assertFalse($article['unique']);

        $cols = $article['cols'];
        $this->assertTrue(is_array($cols));
        $this->assertEquals(2, count($cols));

        $col1 = $cols[0];
        $col2 = $cols[1];
        $this->assertTrue(is_array($col1));
        $this->assertEquals(2, count($col1));
        $this->assertTrue(is_array($col2));
        $this->assertEquals(3, count($col2));

        foreach ($col1 as $colMedia) {
            $this->assertTrue(is_array($colMedia));
            $this->assertEmpty($colMedia);
        }

        foreach ($col2 as $colMedia) {
            $this->assertTrue(is_array($colMedia));
            $this->assertEmpty($colMedia);
        }
    }

    public function testGetArticles()
    {
        $this->assertNull($this->pc78->getArticles());

        $articles = $this->pc78WithValue->getArticles();
        $this->assertTrue(is_array($articles));
        $this->assertEquals(1, count($articles));

        $article = reset($articles);
        $this->assertTrue(is_array($article));
        $this->assertEquals(2, count($article));

        $articleKeys = array_keys($article);
        $this->assertContains('unique', $articleKeys);
        $this->assertContains('cols', $articleKeys);

        $this->assertFalse($article['unique']);

        $cols = $article['cols'];
        $this->assertTrue(is_array($cols));
        $this->assertEquals(2, count($cols));

        $col1 = $cols[0];
        $col2 = $cols[1];
        $this->assertTrue(is_array($col1));
        $this->assertEquals(2, count($col1));
        $this->assertTrue(is_array($col2));
        $this->assertEquals(3, count($col2));

        foreach ($col1 as $colMedia) {
            $this->assertTrue(is_array($colMedia));
            $this->assertEmpty($colMedia);
        }

        foreach ($col2 as $colMedia) {
            $this->assertTrue(is_array($colMedia));
            $this->assertEmpty($colMedia);
        }
    }
}
