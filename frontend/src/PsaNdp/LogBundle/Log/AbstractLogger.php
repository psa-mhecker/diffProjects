<?php

namespace PsaNdp\LogBundle\Log;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\ProcessIdProcessor;
use PsaNdp\LogBundle\Formatter\NdpLineFormatter;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bridge\Monolog\Processor\WebProcessor;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class AbstractLogger
 */
abstract class AbstractLogger
{
    const LOG_FILE_NAME = 'application.log';

    /** @var string */
    protected $logType;
    /** @var string */
    protected $fileName;
    /** @var string */
    protected $directory;
    /**
     * @var string|int
     */
    protected $siteId;

    /** @var Logger */
    protected $logger;

    /**
     * @param string $directory     Path directory to save log. Ex /var/backend/log
     * @param string $logType       Type of log message
     * @param string $fileName
     */
    public function __construct($directory, $logType, $fileName = null)
    {
        $this->fileName = $fileName;

        if (empty($fileName)) {
            $this->fileName = self::LOG_FILE_NAME;
        }

        $this->directory = $directory;
        $this->logType = $logType;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            $formatter = $this->getFormatter();
            $directory = $this->getDirectory();

            $streamHandler = new RotatingFileHandler($directory.DIRECTORY_SEPARATOR.$this->fileName, Logger::WARNING);
            $streamHandler->setFormatter($formatter);

            // create a log channel
            $log = new Logger($this->logType);
            $log->pushHandler($streamHandler);
            $log->pushProcessor(new ProcessIdProcessor());
            $log->pushProcessor(new WebProcessor());

            $this->logger = $log;
        }

        return $this->logger;
    }

    /**
     * @return NdpLineFormatter
     */
    protected function getFormatter()
    {
        $dateFormat = "Y-m-d H:i:s,u";
        $output = "%channel%|%datetime%|%level_name%|%message%|%trace%|%application%|%extra.process_id%|%session_id%|%referer%|%brand_id%|%identifier%|%url%|%parameters%|%response_content%|%time%\n";
        $formatter = new NdpLineFormatter($output, $dateFormat, false, true);

        return $formatter;
    }

    /**
     * create directory if not exist
     *
     * @param string $directory
     */
    protected function createDirectory($directory)
    {
        if (!file_exists($directory)) {
            $fileSystem = new Filesystem();
            $fileSystem->mkdir($directory, 0755);
        }
    }

    /**
     * @return string
     */
    protected function getDirectory()
    {
        $directory = sprintf('%s/%s', $this->directory, $this->getSiteId());

        $this->createDirectory($directory);

        return $directory;
    }

    /**
     * @return int|string
     */
    protected function getSiteId()
    {
        if (empty($this->siteId)) {
            $this->siteId = 'emptySiteId';
        }

        return $this->siteId;
    }

    /**
     * @param $siteId
     *
     * @return $this
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * @param string $logType
     */
    public function setLogType($logType)
    {
        $this->logType = $logType;
    }

    /**
     * @return string
     */
    public function getLogType()
    {
        return $this->logType;
    }
}
