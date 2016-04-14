<?php

namespace Itkg\Migration\Exception;

use Exception;

/**
 * Class MigrationException
 * @package Itkg\Migration
 */
class MigrationLockException extends Exception implements MigrationExceptionInterface
{
    /** @var string  */
    private $startDate;
    /** @var string  */
    private $startHour;
    /** @var string  */
    private $userName;
    /** @var int */
    private $siteId;
    /** @var string  */
    private $siteLabel;

    /**
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($message = "", $code = 0, $previous = null)
    {
        $defaultMessage = 'Migration is already locked. ' . $message;
        parent::__construct($defaultMessage, $code, $previous);
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return int
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @return string
     */
    public function getSiteLabel()
    {
        return $this->siteLabel;
    }

    /**
     * @return string
     */
    public function getStartHour()
    {
        return $this->startHour;
    }

    /**
     * @param string $startDate
     *
     * @return MigrationLockException
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @param string $startHour
     *
     * @return MigrationLockException
     */
    public function setStartHour($startHour)
    {
        $this->startHour = $startHour;

        return $this;
    }

    /**
     * @param string $userName
     *
     * @return MigrationLockException
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * @param int $siteId
     *
     * @return MigrationLockException
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * @param string $siteLabel
     *
     * @return MigrationLockException
     */
    public function setSiteLabel($siteLabel)
    {
        $this->siteLabel = $siteLabel;

        return $this;
    }


}
