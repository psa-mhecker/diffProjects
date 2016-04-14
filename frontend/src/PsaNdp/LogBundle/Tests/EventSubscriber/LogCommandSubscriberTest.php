<?php

namespace PsaNdp\LogBundle\Tests\EventSubscriber;

use Phake;
use PsaNdp\LogBundle\CommandEvents;
use PsaNdp\LogBundle\EventSubscriber\LogCommandSubscriber;
use PsaNdp\LogBundle\Log\CommandLogger;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\ConsoleEvents;


/**
 * Class LogCommandSubscriberTest
 */
class LogCommandSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LogCommandSubscriber
     */
    protected $subscriber;

    /**
     * @var CommandLogger
     */
    protected $commandLogger;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Set Up the test
     */
    public function setUp()
    {
        $this->logger = Phake::mock('Symfony\Bridge\Monolog\Logger');
        $this->commandLogger = Phake::mock('PsaNdp\LogBundle\Log\CommandLogger');
        Phake::when($this->commandLogger)->getLogger()->thenReturn($this->logger);

        $this->subscriber = new LogCommandSubscriber($this->commandLogger);
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
            array(CommandEvents::COMMAND_SUCCESS),
            array(CommandEvents::COMMAND_ERROR),
            array(ConsoleEvents::COMMAND),
            array(ConsoleEvents::EXCEPTION),
            array(ConsoleEvents::TERMINATE),
        );
    }

    /**
     * Test onConsoleTerminate
     */
    public function testOnConsoleTerminate()
    {
        $command = Phake::mock('Symfony\Component\Console\Command\Command');
        Phake::when($command)->getName()->thenReturn('command name');
        $input = Phake::mock('Symfony\Component\Console\Input\Input');
        Phake::when($input)->getArguments()->thenReturn(array());
        Phake::when($input)->getOptions()->thenReturn(array());
        $consoleTerminate = Phake::mock('Symfony\Component\Console\Event\ConsoleTerminateEvent');
        Phake::when($consoleTerminate)->getCommand()->thenReturn($command);
        Phake::when($consoleTerminate)->getInput()->thenReturn($input);

        $this->subscriber->onConsoleTerminate($consoleTerminate);

        Phake::verify($this->logger)->info(Phake::anyParameters());
    }

    /**
     * Test onConsoleTerminate
     */
    public function testOnConsoleException()
    {
        $command = Phake::mock('Symfony\Component\Console\Command\Command');
        Phake::when($command)->getName()->thenReturn('command name');
        $input = Phake::mock('Symfony\Component\Console\Input\Input');
        Phake::when($input)->getArguments()->thenReturn(array());
        Phake::when($input)->getOptions()->thenReturn(array());
        $exception = Phake::mock('Exception');
        Phake::when($exception)->getTraceAsString()->thenReturn('trace Exception');
        $consoleException = Phake::mock('Symfony\Component\Console\Event\ConsoleExceptionEvent');
        Phake::when($consoleException)->getCommand()->thenReturn($command);
        Phake::when($consoleException)->getInput()->thenReturn($input);
        Phake::when($consoleException)->getException()->thenReturn($exception);

        $this->subscriber->onConsoleException($consoleException);

        Phake::verify($this->logger)->error(Phake::anyParameters());
    }
}
