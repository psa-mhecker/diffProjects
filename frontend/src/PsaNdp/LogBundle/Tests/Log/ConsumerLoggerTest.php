<?php

namespace PsaNdp\LogBundle\Tests\Command;

use Phake;
use PsaNdp\LogBundle\Log\ConsumerLogger;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ConsumerLoggerTest
 */
class ConsumerLoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConsumerLogger
     */
    private $consumerLogger;

    /**
     * @var string
     */
    private $directory = '/tmp/logs/test';

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Set Up the test
     */
    public function setUp()
    {
        $this->requestStack = Phake::mock('Symfony\Component\HttpFoundation\RequestStack');

        $this->consumerLogger = new ConsumerLogger($this->directory, $this->requestStack);
    }

    /**
     * Test getLogger
     */
    public function testGetLogger()
    {
        $result = $this->consumerLogger->getLogger();

        $this->assertInstanceOf('Symfony\Bridge\Monolog\Logger', $result);

        $processor = $result->getProcessors();
        $this->assertCount(2, $processor);
        $this->assertInstanceOf('Monolog\Processor\ProcessIdProcessor', $processor[1]);

        $handlers = $result->getHandlers();
        $this->assertCount(1, $handlers);
        $this->assertInstanceOf('Monolog\Handler\RotatingFileHandler', $handlers[0]);

        $formatter = $handlers[0]->getFormatter();
        $this->assertInstanceOf('PsaNdp\LogBundle\Formatter\NdpLineFormatter', $formatter);

        $this->assertSame('web services', $result->getName());
    }
}
