<?php

namespace PsaNdp\MappingBundle\Tests\Services\ShareServices;

use Phake;
use PsaNdp\MappingBundle\Services\ShareServices\ShareMyPeugeotService;

/**
 * Class ShareMyPeugeotServiceTest
 */
class ShareMyPeugeotServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShareMyPeugeotService
     */
    protected $myPeugeotService;
    /**
     * @var \PsaNdp\MappingBundle\Manager\BlockManager
     */
    protected $blockManager;

    /**
     * @var mixed
     */
    protected $block;

    /**
     * @var mixed
     */
    protected $node;

    protected $translator;

    /**
     * Set Up the test
     */
    public function setUp()
    {
        $this->translator = Phake::mock('Symfony\Component\Translation\TranslatorInterface');
        Phake::when($this->translator)->trans(Phake::anyParameters())->thenReturn('label');

        $this->block = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface');
        $this->node = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPage');
        $this->blockManager = Phake::mock('PsaNdp\MappingBundle\Manager\BlockManager');


        Phake::when($this->block)->getZoneUrl()->thenReturn('url/test');

        Phake::when($this->node)->getPageId()->thenReturn('36');
        Phake::when($this->node)->getLanguage()->thenReturn('fr');

        $this->myPeugeotService = new ShareMyPeugeotService($this->blockManager);
        $this->myPeugeotService->setTranslator($this->translator, 2, 'fr');
    }

    /**
     * @param int  $zoneMyPeugeot
     * @param bool $hasBlock
     *
     * @dataProvider provideGetMyPeugeot
     */
    public function testGetMyPeugeot($zoneMyPeugeot, $hasBlock)
    {
        Phake::when($this->block)->getZoneParameters()->thenReturn($zoneMyPeugeot);
        if ($hasBlock) {
            Phake::when($this->blockManager)->getAdminBlockByNodeAndZoneId(Phake::anyParameters())->thenReturn($this->block);

        }

        $result = $this->myPeugeotService->getMyPeugeot($this->node);

        if ($zoneMyPeugeot && $hasBlock) {
            $this->assertArrayHasKey('url', $result);
            $this->assertArrayHasKey('label', $result);
        } else {
            $this->assertSame(array(), $result);
        }
    }

    /**
     * @return array
     */
    public function provideGetMyPeugeot()
    {
        return array(
            array(0, true),
            array(0, false),
            array(1, true),
            array(1, false),
        );
    }
}
