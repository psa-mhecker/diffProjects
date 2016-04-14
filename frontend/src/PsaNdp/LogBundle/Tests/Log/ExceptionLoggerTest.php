<?php

namespace PsaNdp\LogBundle\Tests\Command;

use Phake;
use PsaNdp\LogBundle\Log\ExceptionLogger;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ExceptionLoggerTest
 */
class ExceptionLoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExceptionLogger
     */
    protected $exceptionLogger;

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

        $this->exceptionLogger = new ExceptionLogger($this->directory, $this->requestStack);
    }

    /**
     * Test getLogger
     */
    public function testGetLogger()
    {
        $result = $this->exceptionLogger->getLogger();

        $this->assertInstanceOf('Symfony\Bridge\Monolog\Logger', $result);

        $this->assertSame('exception', $result->getName());
    }
}
