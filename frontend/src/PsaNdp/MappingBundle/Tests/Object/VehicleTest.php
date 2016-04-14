<?php

namespace PsaNdp\MappingBundle\Tests\Object;

use Phake;
use PsaNdp\MappingBundle\Object\Vehicle;

/**
 * Class VehicleTest
 */
class VehicleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Vehicle
     */
    protected $vehicle;

    protected $shareVehicle;

    protected $modelSilhouetteInformation;

    /**
     * Set Up the test
     */
    public function setUp()
    {
        $this->modelSilhouetteInformation = Phake::mock('PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite');
        $this->shareVehicle = Phake::mock('PsaNdp\MappingBundle\Services\ShareServices\ShareVehicleService');
        Phake::when($this->shareVehicle)->getModelSilhouetteInformation()->thenReturn($this->modelSilhouetteInformation);
        Phake::when($this->shareVehicle)->getModelSilhouette()->thenReturn(array('cheapest' => array('GrBodyStyle' => array('Code' => 's000254', 'Label' => '308 5 portes'), 'Price' => array('Display' => '24568 €')), 'version' => array(), 'imgSrc' => 'url/img'));

        $this->vehicle = new Vehicle($this->shareVehicle);
    }

    /**
     * Test initialize
     */
    public function testInitializeVehicle()
    {
        $node = Phake::mock('PSA\MigrationBundle\Entity\Page\PsaPage');
        $translator = Phake::mock('Symfony\Component\Translation\TranslatorInterface');

        $this->vehicle->initializeVehicle($node, $translator, false);

        Phake::verify($this->shareVehicle)->setNode($node);
        Phake::verify($this->shareVehicle)->setTranslator($translator);
        Phake::verify($this->shareVehicle)->setIsMobile(false);
    }
}
