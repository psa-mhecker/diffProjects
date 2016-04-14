<?php
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;

class ConfigurationEngineSelectTest extends \PHPUnit_Framework_TestCase {

    protected $service;
    protected $siteRepository;
    protected $wsRepository;
    protected $configurationEngine;

    public function setUp()
    {
        $this->service = Phake::mock('Itkg\Consumer\Service\Service');
        $this->siteRepository = Phake::mock('PsaNdp\MappingBundle\Repository\PsaSiteWebserviceRepository');
        $this->wsRepository   = Phake::mock('PsaNdp\MappingBundle\Repository\PsaWebserviceRepository');
        Phake::when($this->service)->sendRequest(Phake::anyParameters())->thenReturn($this->service);
        Phake::when($this->service)->getResponse()->thenReturn(Phake::mock('Itkg\Consumer\Response'));
        $this->configurationEngine = new ConfigurationEngineSelect($this->service, $this->siteRepository, $this->wsRepository);
    }

    public function testSelect()
    {
        $this->configurationEngine->select();
        Phake::verify($this->service)->sendRequest(Phake::anyParameters());
    }
}