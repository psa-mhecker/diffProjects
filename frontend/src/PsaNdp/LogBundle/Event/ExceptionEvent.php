<?php

namespace PsaNdp\LogBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ExceptionEvent
 */
class ExceptionEvent extends Event
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
    protected $fileName;

    /**
     * @param string $message
     * @param int    $code
     * @param int    $line
     * @param string $trace
     * @param string $fileName
     */
    public function __construct($message, $code, $line, $trace, $fileName)
    {
        $this->message = $message;
        $this->code = $code;
        $this->line = $line;
        $this->trace = $trace;
        $this->fileName = $fileName;
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
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }
}
