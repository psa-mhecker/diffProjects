<?php

namespace PsaNdp\LogBundle\EventSubscriber;

use Exception;
use Itkg\Migration\Exception\MigrationExceptionInterface;
use PsaNdp\LogBundle\Event\ExceptionEvent;
use PsaNdp\LogBundle\Event\MigrationExceptionEvent;
use PsaNdp\LogBundle\ExceptionEvents;
use PsaNdp\LogBundle\MigrationEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelExceptionSubscriber
 */
class KernelExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param EventDispatcherInterface   $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        /**
         * @var Exception $exception
         */
        $exception = $event->getException();
        if ($exception instanceof MigrationExceptionInterface) {

            $this->dispatcher->dispatch(MigrationEvents::MIGRATION_EXCEPTION, new MigrationExceptionEvent(
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getLine(),
                $exception->getTraceAsString(),
                $exception->getFile()
                )
            );
        } else {
            $this->dispatcher->dispatch(ExceptionEvents::LOG_EXCEPTION, new ExceptionEvent(
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getLine(),
                $exception->getTraceAsString(),
                $exception->getFile()
            ));
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }
}
