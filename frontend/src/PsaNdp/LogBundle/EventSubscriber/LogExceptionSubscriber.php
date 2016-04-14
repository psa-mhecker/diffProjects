<?php

namespace PsaNdp\LogBundle\EventSubscriber;

use PsaNdp\LogBundle\Event\ExceptionEvent;
use PsaNdp\LogBundle\ExceptionEvents;
use PsaNdp\LogBundle\Log\ExceptionLogger;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LogExceptionSubscriber
 */
class LogExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var ExceptionLogger
     */
    protected $exceptionLogger;

    /**
     * @param ExceptionLogger $exceptionLogger
     */
    public function __construct(ExceptionLogger $exceptionLogger)
    {
        $this->exceptionLogger = $exceptionLogger;
        $this->logger = $exceptionLogger->getLogger();
    }

    /**
     * @param ExceptionEvent $event
     */
    public function logException(ExceptionEvent $event)
    {
        $context = array(
            'trace' => $event->getTrace(),
            'application' => $this->getApplication(),
            'identifier' => '?',
            'url' => '?',
            'parameters' => '?',
            'response_content' => '?',
            'time' => '?'
        );

        if (null !== session_id()) {
            $context['session_id'] = session_id();
        }

        if (null !== $this->exceptionLogger->getRequest()) {
            $result['referer'] = $this->exceptionLogger->getRequest()->geturi();
        }

        $this->logger->error($event->getMessage(), $context);
    }

    /**
     * @return string
     */
    protected function getApplication()
    {
        $application = 'NDP/FO/'.ExceptionLogger::APPLICATION_LOG_TYPE_EXCEPTION;
        if ($this->exceptionLogger->isBackend()) {
            $application = 'NDP/BO/'.ExceptionLogger::APPLICATION_LOG_TYPE_EXCEPTION;
        }

        return $application;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            ExceptionEvents::LOG_EXCEPTION => 'logException'
        );
    }
}
