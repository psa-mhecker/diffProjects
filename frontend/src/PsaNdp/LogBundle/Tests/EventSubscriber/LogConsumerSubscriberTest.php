<?php

namespace PsaNdp\LogBundle\Tests\EventSubscriber;

use Itkg\Consumer\Event\ServiceEvents;
use Phake;
use PsaNdp\LogBundle\EventSubscriber\LogCommandSubscriber;
use PsaNdp\LogBundle\EventSubscriber\LogConsumerSubscriber;
use PsaNdp\LogBundle\Log\ConsumerLogger;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class LogConsumerSubscriberTest
 */
class LogConsumerSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LogConsumerSubscriber
     */
    protected $subscriber;

    /**
     * @var ConsumerLogger
     */
    protected $consumerLogger;

    /**
     * @var Logger
     */
    protected $logger;

    protected $serviceEvent;

    protected $service;

    /**
     * Set Up the test
     */
    public function setUp()
    {
        $this->logger = Phake::mock('Symfony\Bridge\Monolog\Logger');
        $this->consumerLogger = Phake::mock('PsaNdp\LogBundle\Log\ConsumerLogger');
        Phake::when($this->consumerLogger)->getLogger()->thenReturn($this->logger);

        $response = Phake::mock('Symfony\Component\HttpFoundation\Response');
        $request = Phake::mock('Symfony\Component\HttpFoundation\Request');
        $exception = Phake::mock('Exception');
        Phake::when($exception)->getTraceAsString()->thenReturn('trace Exception');
        $this->service = Phake::mock('PsaNdp\WebserviceConsumerBundle\Service\Service');
        Phake::when($this->service)->getRequest()->thenReturn($request);
        Phake::when($this->service)->getResponse()->thenReturn($response);
        Phake::when($this->service)->getException()->thenReturn($exception);
        $this->serviceEvent = Phake::mock('Itkg\Consumer\Event\ServiceEvent');
        Phake::when($this->serviceEvent)->getService()->thenReturn($this->service);

        $this->subscriber = new LogConsumerSubscriber($this->consumerLogger);
    }

    /**
     * @param $eventName
     *
     * @dataProvider provideSubscribedEvent
     */
    public function testEventSubscribed($eventName)
    {
        $this->assertArrayHasKey($eventName, $this->subscriber->getSubscribedEvents());
    }

    /**
     * @return array
     */
    public function provideSubscribedEvent()
    {
        return array(
            array(ServiceEvents::RESPONSE),
            array(ServiceEvents::EXCEPTION),
        );
    }

    /**
     * Test onServiceSuccess
     */
    public function testOnServiceSuccess()
    {
        $this->subscriber->onServiceSuccess($this->serviceEvent);

        Phake::verify($this->logger)->info(Phake::anyParameters());
    }

    /**
     * Test onServiceSuccess
     */
    public function testOnServiceException()
    {
        $this->subscriber->onServiceException($this->serviceEvent);

        Phake::verify($this->logger)->error(Phake::anyParameters());
    }
}
