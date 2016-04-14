<?php

namespace PsaNdp\LogBundle\EventSubscriber;

use PsaNdp\LogBundle\CommandEvents;
use PsaNdp\LogBundle\Event\CommandEvent;
use PsaNdp\LogBundle\Log\CommandLogger;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleExceptionEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CommandSubscriber
 */
class LogCommandSubscriber implements EventSubscriberInterface
{
    /**
     * @var CommandLogger
     */
    protected $commandLogger;

    /**
     * @var float
     */
    protected $startTime;

    /**
     * @var CommandEvent
     */
    protected $commandEvent;


    /**
     * @param CommandLogger $commandLogger
     */
    public function __construct(CommandLogger $commandLogger)
    {
        $this->commandLogger = $commandLogger;
    }

    /**
     * @param CommandEvent $event
     */
    public function onCommandSuccess(CommandEvent $event)
    {
        $this->commandEvent = $event;
    }

    /**
     * @param CommandEvent $event
     */
    public function onCommandError(CommandEvent $event)
    {
        $this->commandEvent = $event;
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function onConsoleStart(ConsoleCommandEvent $event)
    {
        $this->startTime = microtime(true);
    }

    /**
     * @param ConsoleExceptionEvent $event
     */
    public function onConsoleException(ConsoleExceptionEvent $event)
    {
        $command = $event->getCommand();
        $exception = $event->getException();

        $logger = $this->getLogger($command->getName());
        $context = array(
            'trace' => $exception->getTraceAsString(),
            'parameters' => array_merge($event->getInput()->getArguments(), $event->getInput()->getOptions())
        );
        $context = array_merge($this->abstractCommandContext(), $context);

        $logger->error($exception->getMessage(), $context);
    }

    /**
     * @param ConsoleTerminateEvent $event
     */
    public function onConsoleTerminate(ConsoleTerminateEvent $event)
    {
        $command = $event->getCommand();

        $logger = $this->getLogger($command->getName());

        $context = array(
            'trace' => '',
            'parameters' => array_merge($event->getInput()->getArguments(), $event->getInput()->getOptions())
        );
        $context = array_merge($this->abstractCommandContext(), $context);

        $logger->info('Command success', $context);
    }

    /**
     * @return array
     */
    protected function abstractCommandContext()
    {
        $context =  array(
            'application' => 'NDP/FO/'.$this->commandLogger->getLogType(),
            'brand_id' => 'AP',
            'time' => $this->getTime()
        );

        if ($this->commandEvent instanceof CommandEvent) {
            $context['parameters'] = $this->commandEvent->getParameters();
        }

        return $context;
    }

    /**
     * @param string $name
     *
     * @return Logger
     */
    protected function getLogger($name)
    {
        if ($this->commandEvent instanceof CommandEvent) {
            $this->commandLogger->setSiteId($this->commandEvent->getSiteId());
        }

        $this->commandLogger->setLogType(sprintf('%s %s', $this->commandLogger->getLogType(), $name));

        return $this->commandLogger->getLogger();
    }

    /**
     * @return string
     */
    protected function getTime()
    {
        $endTime = microtime(true);
        $time = ($endTime - $this->startTime)*1000;

        return sprintf('%d', $time);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            CommandEvents::COMMAND_SUCCESS  => 'onCommandSuccess',
            CommandEvents::COMMAND_ERROR => 'onCommandError',
            ConsoleEvents::COMMAND => 'onConsoleStart',
            ConsoleEvents::TERMINATE => 'onConsoleTerminate',
            ConsoleEvents::EXCEPTION => 'onConsoleException',
        );
    }
}
