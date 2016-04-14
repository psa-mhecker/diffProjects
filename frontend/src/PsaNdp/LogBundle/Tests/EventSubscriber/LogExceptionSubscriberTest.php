<?php

namespace PsaNdp\LogBundle\Tests\EventSubscriber;

use Phake;
use PsaNdp\LogBundle\Event\ExceptionEvent;
use PsaNdp\LogBundle\EventSubscriber\LogExceptionSubscriber;
use PsaNdp\LogBundle\ExceptionEvents;
use PsaNdp\LogBundle\Log\ExceptionLogger;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class LogExceptionSubscriberTest
 */
class LogExceptionSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LogExceptionSubscriber
     */
    protected $subscriber;

    /**
     * @var ExceptionLogger
     */
    protected $exceptionLogger;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var ExceptionEvent
     */
    protected $exceptionEvent;

    /**
     * Set Up the test
     */
    public function setUp()
    {
        $this->logger = Phake::mock('Symfony\Bridge\Monolog\Logger');
        $this->exceptionLogger = Phake::mock('PsaNdp\LogBundle\Log\ExceptionLogger');
        Phake::when($this->exceptionLogger)->getLogger()->thenReturn($this->logger);
        $this->exceptionEvent = Phake::mock('PsaNdp\LogBundle\Event\ExceptionEvent');

        $this->subscriber = new LogExceptionSubscriber($this->exceptionLogger);
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
            array(ExceptionEvents::LOG_EXCEPTION),
        );
    }

    /**
     * Test LogException
     */
    public function testLogException()
    {
        $this->subscriber->logException($this->exceptionEvent);

        Phake::verify($this->logger)->error(Phake::anyParameters());
    }
}
