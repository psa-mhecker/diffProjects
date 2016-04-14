<?php

namespace PsaNdp\LogBundle\EventSubscriber;

use PsaNdp\LogBundle\Log\MigrationLogger;
use Monolog\Logger;
use PsaNdp\LogBundle\Event\MigrationEvent;
use PsaNdp\LogBundle\Event\MigrationExceptionEvent;
use PsaNdp\LogBundle\MigrationEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LogMigrationSubscriber
 */
class LogMigrationSubscriber implements EventSubscriberInterface
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param MigrationLogger $migrationLogger
     */
    public function __construct(MigrationLogger $migrationLogger)
    {
        $this->logger = $migrationLogger->getLogger();
    }

    /**
     * @param MigrationEvent $event
     */
    public function migrationError(MigrationEvent $event)
    {
        $message = $event->getMessage();

        if (empty($message)) {
            $message = 'An error has occurred during showroom migration.';
        }

        $context = $this->abstractContextMigration();
        $context = array_merge($context, array(
            'url' => $event->getUrl(),
            'parameters' => array(
                'siteId' => $event->getSite()->getSiteId(),
                'userName' => $event->getUser()->getUserName(),
                'urls' => $event->getUrls(),
                'urlType' => $event->getUrlType()
            ),
            'time' => $event->getTime(),
            'trace' => $event->getException()
        ));


        $this->logger->error($message, $context);
    }

    /**
     * @param MigrationEvent $event
     */
    public function migrationSuccess(MigrationEvent $event)
    {
        $message = $event->getMessage();

        if (empty($message)) {
            $message = 'Showroom migration was a success.';
        }

        $context = $this->abstractContextMigration();
        $context = array_merge($context, array(
            'url' => $event->getUrl(),
            'parameters' => array(
                'siteId' => $event->getSite()->getSiteId(),
                'userName' => $event->getUser()->getUserName(),
                'urls' => $event->getUrls(),
                'urlType' => $event->getUrlType()
            ),
            'time' => $event->getTime()
        ));

        $this->logger->info($message, $context);
    }

    /**
     * @param MigrationExceptionEvent $event
     */
    public function migrationException(MigrationExceptionEvent $event)
    {
        $message = $event->getMessage();
        $context = array('trace' => $event->getTrace());
        $context = array_merge($context, $this->abstractContextMigration());

        $this->logger->error($message, $context);
    }

    /**
     * @return array
     */
    protected function abstractContextMigration()
    {
        $result = array(
            'application' => 'NDP/BO/'.MigrationLogger::APPLICATION_LOG_TYPE_MIGRATION_PROCESSING,
            'brand_id' => 'AP',
            'session_id' => '',
            'referer' => '',
            'identifier' => '?',
            'response_content' => '?',
        );

        if (null !== session_id()) {
            $result['session_id'] = session_id();
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            MigrationEvents::MIGRATION_ERROR => 'migrationError',
            MigrationEvents::MIGRATION_SUCCESS => 'migrationSuccess',
            MigrationEvents::MIGRATION_EXCEPTION => 'migrationException',
        );
    }
}
