<?php

namespace Itkg\Migration\Reporting;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use RuntimeException;

/**
 * Migration Result Reporting Handler
 *
 * Class DataMigrationReporting
 * @package Itkg\Migration
 */
class DataMigrationReporting implements AddReportingMessageInterface
{
    const MESSAGE_TYPE_INFOS = 'infos';
    const MESSAGE_TYPE_WARNING = 'warning';
    const MESSAGE_TYPE_ERROR = 'error';

    /** @var string */
    protected $reportingName = null;
    /** @var string */
    protected $directory = null;

    /** @var array of showroom url
     *       in the form ['fr' => 'http://...', 'en' => 'http://...']
     */
    protected $urls = [];
    /**
     * @var array of xml for the showroom data
     *      in the form ['fr' => 'http://...', 'en' => 'http://...']
     */
    protected $xmls = [];

    /** @var array list of .srt file url found during the XML parsing */
    private $srtUrls = [];
    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $exception;

    /**
     * @var array of messages for reporting
     *      in the form [ 'warning' => ['warning 1', 'warning 2'], 'error' => ['error 1', 'error2']
     */
    protected $messages = [];
    /** @var string */
    protected $typeShowroom;

    /**
     * Write a report in the file 'this->directory / $this->reportingName'
     *
     */
    public function writeReport()
    {
        if (null == $this->directory) {
            throw new RuntimeException('Directory has not been set. Process could not be locked. Use setter to set lock directory.');
        }
        if (null == $this->reportingName) {
            throw new RuntimeException('LockName has not been set. Process could not be locked. Use setter to set lock name.');
        }

        // log for with simple message format
        $output = "%message%\n";
        $streamHandler = new StreamHandler($this->directory . DIRECTORY_SEPARATOR . $this->reportingName);
        $streamHandler->setFormatter(new LineFormatter($output));

        $logSimpleMsg = new Logger($this->reportingName);
        $logSimpleMsg->pushHandler($streamHandler);

        // log for with Info, Warning, Error levels message format
        $output = "   [%level_name%] %message%\n";
        $streamHandler = new StreamHandler($this->directory . DIRECTORY_SEPARATOR . $this->reportingName);
        $streamHandler->setFormatter(new LineFormatter($output));

        $logLevelMsg = new Logger($this->reportingName);
        $logLevelMsg->pushHandler($streamHandler);

        // Write report
        $logSimpleMsg->addInfo("====================================================================");
        $logSimpleMsg->addInfo("     REPORT Generated on " . date('l jS \of F Y h:i:s A'));
        $logSimpleMsg->addInfo("====================================================================");
        $logSimpleMsg->addInfo("");
        $this->writeReportSummary($logSimpleMsg);
        $this->writeReportLog($logSimpleMsg, $logLevelMsg);
        $logSimpleMsg->addInfo("");
    }

    /**
     * Write in log file the summary of the migration
     *
     * @param Logger $logSimpleMsg
     */
    private function writeReportSummary(Logger $logSimpleMsg)
    {
        // Input data
        $logSimpleMsg->addInfo("INPUT:");
        $logSimpleMsg->addInfo("   Type of showroom url: $this->typeShowroom");
        foreach ($this->urls as $language => $url) {
            $logSimpleMsg->addInfo("   URL for language '$language' : $url");
        }
        $logSimpleMsg->addInfo("");

        // XML Generated
        $logSimpleMsg->addInfo('XML Data:');
        foreach ($this->xmls as $language => $url) {
            $logSimpleMsg->addInfo("   XML for language '$language' : $url");
        }
        $logSimpleMsg->addInfo("");

        // XML Generated
        if (count($this->getErrorMessages()) === 0) {
            $logSimpleMsg->addInfo('SRT Found:');
            if (count($this->srtUrls) === 0) {
                $logSimpleMsg->addInfo("   No SRT found in the XML.");
            }
            foreach ($this->srtUrls as $index => $url) {
                $logSimpleMsg->addInfo("   " . ($index + 1) . " : $url");
            }
            $logSimpleMsg->addInfo("");
        }

        // Result
        $logSimpleMsg->addInfo("3. RESULT : " . $this->resultMessage());
    }

    /**
     * Write in log file the détails logs of the migration
     *
     * @param Logger $logSimpleMsg
     * @param Logger $logLevelMsg
     */
    private function writeReportLog(Logger $logSimpleMsg, Logger $logLevelMsg)
    {
        //Log messages
        foreach ($this->getInfosMessages() as $msg) {
            $logLevelMsg->info($msg);
        }
        $logSimpleMsg->addInfo("");
        foreach ($this->getWarningMessages() as $msg) {
            $logLevelMsg->warning($msg);
        }
        $logSimpleMsg->addInfo("");
        foreach ($this->getErrorMessages() as $msg) {
            $logLevelMsg->error($msg);
        }
    }

    /**
     * Result sentence generated according to level of message existing
     *
     * @return string
     */
    public function resultMessage()
    {
        $result = "OK, done without error";

        if (count($this->getMessages(self::MESSAGE_TYPE_WARNING)) > 0) {
            $result = "OK, but with warning, check warning";
        }
        if (count($this->getMessages(self::MESSAGE_TYPE_ERROR)) > 0) {
            $result = "KO, check errors";
        }

        return $result;
    }

    /**
     * Result sentence generated according to level of message existing
     *
     * @return string
     */
    public function resultColor()
    {
        $result = "green";

        if (count($this->getMessages(self::MESSAGE_TYPE_WARNING)) > 0) {
            $result = "orange";
        }
        if (count($this->getMessages(self::MESSAGE_TYPE_ERROR)) > 0) {
            $result = "red";
        }

        return $result;
    }


    /**
     * Return infos messages
     *
     * @return array
     */
    public function getInfosMessages()
    {
        return $this->getMessages(self::MESSAGE_TYPE_INFOS);
    }

    /**
     * Return warning messages
     *
     * @return array
     */
    public function getWarningMessages()
    {
        return $this->getMessages(self::MESSAGE_TYPE_WARNING);
    }

    /**
     * Return warning messages
     *
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->getMessages(self::MESSAGE_TYPE_ERROR);
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function getMessages($type)
    {
        $result = [];
        if (!empty($this->messages[$type])) {
            $result = $this->messages[$type];
        }

        return $result;
    }


    /**
     * @param $message
     *
     * @return DataMigrationReporting
     */
    public function addInfoMessage($message)
    {
        $this->addMessage(self::MESSAGE_TYPE_INFOS, $message);

        return $this;
    }

    /**
     * @param $message
     *
     * @return DataMigrationReporting
     */
    public function addWarningMessage($message)
    {
        $this->addMessage(self::MESSAGE_TYPE_WARNING, $message);

        return $this;
    }

    /**
     * @param $message
     *
     * @return DataMigrationReporting
     */
    public function addErrorMessage($message)
    {
        $this->addMessage(self::MESSAGE_TYPE_ERROR, $message);

        return $this;
    }

    /**
     * @param string $type
     * @param array $message
     *
     * @return DataMigrationReporting
     */
    public function addMessage($type, $message)
    {
        if (empty($this->messages[$type])) {
            $this->messages[$type] = [];
        }
        $this->messages[$type][] = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getTypeShowroom()
    {
        return $this->typeShowroom;
    }

    /**
     * @param string $typeShowroom
     *
     * @return DataMigrationReporting
     */
    public function setTypeShowroom($typeShowroom)
    {
        $this->typeShowroom = $typeShowroom;

        return $this;
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * @param array $urls
     *
     * @return DataMigrationReporting
     */
    public function setUrls($urls)
    {
        $this->urls = $urls;

        return $this;
    }
    /**
     * @param PsaLanguage $language
     * @param $url
     *
     * @return DataMigrationReporting
     */
    public function addUrl(PsaLanguage $language, $url)
    {
        $this->urls[$language->getLangueCode()] = $url;

        return $this;
    }

    /**
     * @return array
     */
    public function getXmls()
    {
        return $this->xmls;
    }

    /**
     * @param array $xmls
     *
     * @return DataMigrationReporting
     */
    public function setXmls($xmls)
    {
        $this->xmls = $xmls;

        return $this;
    }

    /**
     * @param PsaLanguage $language
     * @param string $url
     *
     * @return DataMigrationReporting
     */
    public function addXml(PsaLanguage $language, $url)
    {
        $this->xmls[$language->getLangueCode()] = $url;

        return $this;
    }

    /**
     * @return array
     */
    public function getSrtUrls()
    {
        return $this->srtUrls;
    }

    /**
     * @param array $srtUrl
     *
     * @return DataMigrationReporting
     */
    public function addSrtUrl($srtUrl)
    {
        $this->srtUrls[] = $srtUrl;

        return $this;
    }


    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     *
     * @return DataMigrationReporting
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * @return string
     */
    public function getReportingName()
    {
        return $this->reportingName;
    }

    /**
     * @param string $reportingName
     *
     * @return DataMigrationReporting
     */
    public function setReportingName($reportingName)
    {
        $this->reportingName = $reportingName;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param string $exception
     */
    public function setException($exception)
    {
        $this->exception = $exception;
    }
}
