<?php

use PsaNdp\WebserviceConsumerBundle\Webservices\RangeManager;

class RangeManagerTest extends  \PHPUnit_Framework_TestCase
{

    protected $service;
    protected $siteRepository;
    protected $wsRepository;
    protected $rangeManager;

    public function setUp()
    {
        $this->service = Phake::mock('Itkg\Consumer\Service\Service');
        $this->siteRepository = Phake::mock('PsaNdp\MappingBundle\Repository\PsaSiteWebserviceRepository');
        $this->wsRepository   = Phake::mock('PsaNdp\MappingBundle\Repository\PsaWebserviceRepository');
        Phake::when($this->service)->sendRequest(Phake::anyParameters())->thenReturn($this->service);
        Phake::when($this->service)->getResponse()->thenReturn(Phake::mock('Itkg\Consumer\Response'));
        $this->rangeManager = new RangeManager($this->service, $this->siteRepository, $this->wsRepository);
    }

    public function testCriteria(){
        $this->rangeManager->criteria();
        Phake::verify($this->service)->sendRequest(Phake::anyParameters());
    }

    public function testSearch()
    {
        $this->rangeManager->search();
        Phake::verify($this->service)->sendRequest(Phake::anyParameters());
    }

    public function testCars()
    {
        $this->rangeManager->cars();
        Phake::verify($this->service)->sendRequest(Phake::anyParameters());
    }

    public function testBooklets()
    {
        $this->rangeManager->booklets();
        Phake::verify($this->service)->sendRequest(Phake::anyParameters());
    }
}
