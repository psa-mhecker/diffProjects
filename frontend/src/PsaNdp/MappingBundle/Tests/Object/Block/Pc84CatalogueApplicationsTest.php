<?php

namespace PsaNdp\MappingBundle\Tests\Object\Block;

use Phake;
use PsaNdp\MappingBundle\Object\Block\Pc84CatalogueApplications;

class Pc84CatalogueApplicationsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pc84CatalogueApplications
     */
    protected $pc84;

    /**
     * @var Pc84CatalogueApplications
     */
    protected $pc84WithValue;

    protected $ctaFactory;

    protected $mediaFactory;

    protected $title = 'title';
    protected $subtitle = 'subtitle';
    protected $datalayer = '';
    protected $items = array();

    protected $mediaServer = 'http://media.psa.test';
    protected $isMobile = false;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->ctaFactory = Phake::mock('PsaNdp\MappingBundle\Object\Factory\CtaFactory');
        $this->mediaFactory = Phake::mock('PsaNdp\MappingBundle\Object\Factory\MediaFactory');

        // Objet vide > initialisé à vide pour être utilisé dans les autres tests.
        $this->pc84 = new Pc84CatalogueApplications();
        $this->pc84->setCtaFactory($this->ctaFactory);
        $this->pc84->setMediaFactory($this->mediaFactory);

        $this->initSousObjets();
    }

    protected function initSousObjets()
    {
        $item = array(
            'visual' => array(
                'src' => $this->mediaServer.'/design/frontend/desktop/img/pc84-visu1.png',
                'alt' => 'title',
            ),
            'title' => 'MyPeugeot',
            'text' => 'Lorem ipsum* dolor sit <br> amet, consectetur <br> adipiscing',
            'note' => '*Compatible IOS5 et version ultérieur. Disponible également sur Android & Windows Phone',
            'cta' => array(
                array(
                    'url' => '#',
                    'style' => 'cta',
                    'version' => '4',
                    'title' => 'télécharger l’application',
                    'target' => '_self',
                ),
            ),
        );
        $this->items[] = $item;
    }

    public function testSetDataFromArray()
    {
        $data = array(
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'datalayer' => $this->datalayer,
            'items' => $this->items,
        );

        $this->pc84->setDataFromArray($data);

        $this->assertSame($this->title, $this->pc84->getTitle());
        $this->assertSame($this->subtitle, $this->pc84->getSubtitle());
        $this->assertSame($this->datalayer, $this->pc84->getDataLayer());
        $this->assertSame($this->items, $this->pc84->getItems());
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
        $this->pc84->offsetSet($key, $value);

        if (!$exception) {
            $this->assertSame($value, $this->pc84->offsetGet($key));
        }
    }

    /**
     * @return array
     */
    public function provideProperty()
    {
        return array(
            array('datalayer', $this->datalayer, false),
        );
    }
}
