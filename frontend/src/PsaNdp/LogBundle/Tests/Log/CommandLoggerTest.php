<?php

namespace PsaNdp\LogBundle\Tests\Command;

use PsaNdp\LogBundle\Log\CommandLogger;

/**
 * Class CommandLoggerTest
 */
class CommandLoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommandLogger
     */
    protected $commandLogger;

    protected $directory = '/tmp/logs/test';

    /**
     * Set Up the test
     */
    public function setUp()
    {
        $this->commandLogger = new CommandLogger($this->directory);
    }

    /**
     * Test getLogger
     */
    public function testGetLogger()
    {
        $result = $this->commandLogger->getLogger();

        $this->assertInstanceOf('Symfony\Bridge\Monolog\Logger', $result);

        $this->assertSame('command', $result->getName());
    }
}
