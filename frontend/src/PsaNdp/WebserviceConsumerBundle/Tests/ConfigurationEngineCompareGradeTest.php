<?php
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineCompareGrade;

class ConfigurationEngineCompareGradeTest extends \PHPUnit_Framework_TestCase {

    protected $service;
    protected $siteRepository;
    protected $wsRepository;
    protected $configurationEngine;


    public function setUp()
    {
        $this->service        = Phake::mock('Itkg\Consumer\Service\Service');
        $this->siteRepository = Phake::mock('PsaNdp\MappingBundle\Repository\PsaSiteWebserviceRepository');
        $this->wsRepository   = Phake::mock('PsaNdp\MappingBundle\Repository\PsaWebserviceRepository');

        Phake::when($this->service)->sendRequest(Phake::anyParameters())->thenReturn($this->service);
        Phake::when($this->service)->getResponse()->thenReturn(Phake::mock('Itkg\Consumer\Response'));
        $this->configurationEngine = new ConfigurationEngineCompareGrade($this->service, $this->siteRepository, $this->wsRepository);
    }

    public function testCompareGrades()
    {
        $this->configurationEngine->compareGrades('VP','1PIA','A3');
        Phake::verify($this->service)->sendRequest(Phake::anyParameters());
    }
}