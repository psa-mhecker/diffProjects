<?php

namespace PsaNdp\MappingBundle\Tests\Services\ShareServices;

use Phake;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PsaNdp\MappingBundle\Object\Vehicle;
use PsaNdp\MappingBundle\Services\ShareServices\ShareMyPeugeotService;
use PsaNdp\MappingBundle\Services\ShareServices\ShareObjectService;
use PsaNdp\MappingBundle\Services\ShareServices\ShareVehicleService;
use Symfony\Component\CssSelector\XPath\TranslatorInterface;

/**
 * Class ShareObjectServiceTest
 */
class ShareObjectServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShareObjectService
     */
    protected $shareObject;

    /**
     * @var Vehicle
     */
    protected $vehicle;

    /**
     * @var ShareMyPeugeotService
     */
    protected $shareMyPeugeot;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var PsaPage
     */
    protected $node;

    /**
     * Set Up the test
     */
    public function setUp()
    {
        $this->node = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPage');
        Phake::when($this->node)->getSiteId()->thenReturn(2);
        Phake::when($this->node)->getLanguage()->thenReturn('fr');
        $this->vehicle = Phake::mock('PsaNdp\MappingBundle\Object\Vehicle');
        $this->shareMyPeugeot = Phake::mock('PsaNdp\MappingBundle\Services\ShareServices\ShareMyPeugeotService');
        $this->translator = Phake::mock('Symfony\Component\Translation\TranslatorInterface');

        $this->shareObject = new ShareObjectService($this->vehicle, $this->shareMyPeugeot, $this->translator);
    }

    /**
     * Test setNode
     */
    public function testSetNode()
    {
        $this->shareObject->setNode($this->node);

        Phake::verify($this->node)->getSiteId();
        Phake::verify($this->node)->getLanguage();
        Phake::verify($this->node)->initBlockPosition();
    }

    /**
     * @param string $model
     * @param string $modelName
     *
     * @dataProvider provideVehicle
     */
    public function testGetVehicle($model, $modelName)
    {
        $version = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPageVersion');
        Phake::when($version)->getGammeVehicule()->thenReturn($model);
        Phake::when($this->node)->getVersion()->thenReturn($version);
        Phake::when($this->vehicle)->getModelname()->thenReturn($modelName);

        $this->shareObject->setNode($this->node);
        $result = $this->shareObject->getVehicle();

        if ($modelName === null || $model) {
            Phake::verify($this->vehicle)->initializeVehicle(Phake::anyParameters());
        } else {
            $this->assertNull($result);
        }
    }

    /**
     * @return array
     */
    public function provideVehicle()
    {
        return array(
            array('model', 'name'),
            array(null, 'name'),
            array('model', null),
        );
    }
}
