<?php

namespace PsaNdp\LogBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class MigrationExceptionEvent
 */
class MigrationExceptionEvent extends Event
{
    /**
     * @var string
     */
    protected $message;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var int
     */
    protected $line;

    /**
     * @var string
     */
    protected $trace;

    /**
     * @var string
     */
    protected $file;

    /**
     * @param string $message
     * @param int    $code
     * @param int    $line
     * @param string $trace
     * @param string $file
     */
    public function __construct($message, $code, $line, $trace, $file)
    {
        $this->message = $message;
        $this->code = $code;
        $this->line = $line;
        $this->trace = $trace;
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @return string
     */
    public function getTrace()
    {
        return $this->trace;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
}
