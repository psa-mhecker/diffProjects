<?php

namespace PsaNdp\LogBundle\Tests\EventSubscriber;

use Phake;
use PsaNdp\LogBundle\EventSubscriber\LogMigrationSubscriber;
use PsaNdp\LogBundle\Log\MigrationLogger;
use PsaNdp\LogBundle\MigrationEvents;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class LogMigrationSubscriberTest
 */
class LogMigrationSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LogMigrationSubscriber
     */
    protected $subscriber;

    /**
     * @var MigrationLogger
     */
    protected $migrationLogger;

    /**
     * @var Logger
     */
    protected $logger;

    protected $event;

    /**
     * Set Up the test
     */
    public function setUp()
    {
        $site = Phake::mock('PSA\MigrationBundle\Entity\Site\PsaSite');
        Phake::when($site)->getSiteId()->thenReturn(2);
        $user = Phake::mock('PSA\MigrationBundle\Entity\User\PsaUser');
        Phake::when($user)->getUserName()->thenReturn('Toto');
        $this->logger = Phake::mock('Symfony\Bridge\Monolog\Logger');
        $this->migrationLogger = Phake::mock('PsaNdp\LogBundle\Log\MigrationLogger');
        Phake::when($this->migrationLogger)->getLogger()->thenReturn($this->logger);
        $this->event = Phake::mock('PsaNdp\LogBundle\Event\MigrationEvent');
        Phake::when($this->event)->getSite()->thenReturn($site);
        Phake::when($this->event)->getUser()->thenReturn($user);

        $this->subscriber = new LogMigrationSubscriber($this->migrationLogger);
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
            array(MigrationEvents::MIGRATION_ERROR),
            array(MigrationEvents::MIGRATION_EXCEPTION),
            array(MigrationEvents::MIGRATION_SUCCESS),
        );
    }

    /**
     * Test MigrationError
     */
    public function testMigrationError()
    {
        $this->subscriber->migrationError($this->event);

        Phake::verify($this->logger)->error(Phake::anyParameters());
    }

    /**
     * Test MigrationSuccess
     */
    public function testMigrationSuccess()
    {
        $this->subscriber->migrationSuccess($this->event);

        Phake::verify($this->logger)->info(Phake::anyParameters());
    }

    /**
     * Test MigrationException
     */
    public function testMigrationException()
    {
        $migrationExceptionEvent = Phake::mock('PsaNdp\LogBundle\Event\MigrationExceptionEvent');
        $this->subscriber->migrationException($migrationExceptionEvent);

        Phake::verify($this->logger)->error(Phake::anyParameters());
    }
}
