<?php

namespace PsaNdp\WebserviceConsumerBundle\Tests;

use Phake;
use \PsaNdp\WebserviceConsumerBundle\Webservices\Webstore;

/**
 * Class WebstoreTest
 */
class WebstoreTest extends \PHPUnit_Framework_TestCase
{
    protected $service;
    protected $siteRepository;
    protected $wsRepository;
    protected $webstore;

    public function setUp()
    {
        $this->service = Phake::mock('Itkg\Consumer\Service\Service');
        $this->siteRepository = Phake::mock('PsaNdp\MappingBundle\Repository\PsaSiteWebserviceRepository');
        $this->wsRepository   = Phake::mock('PsaNdp\MappingBundle\Repository\PsaWebserviceRepository');
        Phake::when($this->service)->sendRequest(Phake::anyParameters())->thenReturn($this->service);
        Phake::when($this->service)->getResponse()->thenReturn(Phake::mock('Itkg\Consumer\Response'));
        $this->webstore = new Webstore($this->service, $this->siteRepository, $this->wsRepository);
    }

    public function testGetDealers()
    {
        $this->webstore->getDealers();
        Phake::verify($this->service)->sendRequest(Phake::anyParameters());
    }

    public function testGetVehicles()
    {
        $this->webstore->getVehicles();
        Phake::verify($this->service)->sendRequest(Phake::anyParameters());
    }
}
