<?php


namespace Itkg\Migration\Lock;

use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Lock a process using through .lock file
 *
 * Class LockHandler
 */
class LockHandler
{

    /** @var Filesystem */
    private $fileSystem;
    /** @var string */
    private $lockDirectory;

    /** @var string */
    private $lockName = null;
    /** @var string */
    private $userName;
    /** @var int migration start time */
    private $timeStampStart;

    /**
     * @param Filesystem $fileSystem
     */
    public function __construct(
        Filesystem $fileSystem
    )
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * Lock the migration for the current site
     * - use a lock file per site
     * - each lock file also contain lock information (start time, user...) in json format
     *
     * Preconditions: $this->site should have been set beforehand using init() function
     *
     * @throws RuntimeException
     */
    public function lock()
    {
        $lockFile = $this->getLockFilePath();

        $this->timeStampStart = time();
        $lockInfos['startDate'] = date('d-m-Y', $this->timeStampStart);
        $lockInfos['startHour'] = date('h:i:s A', $this->timeStampStart);
        $lockInfos['userName'] = $this->userName;
        $lockInfos['LockName'] = $this->lockName;

        file_put_contents($lockFile, json_encode($lockInfos) . "\n");
    }

    /**
     * Unlock the migration for the current site by deleting the lock file for current site
     *
     * Preconditions: $this->site should have been set beforehand using init() function
     *
     * @throws RuntimeException
     */
    public function unlock()
    {
        $lockFile = $this->getLockFilePath();
        $this->fileSystem->remove($lockFile);
    }

    /**
     * Check if there is already a migration on-going for the current country through a lock file
     * If yes, it return the lock file content
     * If no, it return null
     *
     * Preconditions: $this->site should have been set beforehand using init() function
     *
     * @return null|string
     *
     * @throws RuntimeException
     */
    public function getLockedInformation()
    {
        $lockFile = $this->getLockFilePath();
        $lockInfos = null;

        if ($this->fileSystem->exists($lockFile)) {
            $lockInfos = json_decode(file_get_contents($lockFile));
        }

        return $lockInfos;
    }

    /**
     * Is locked
     *
     * @return bool
     *
     */
    public function isLocked()
    {
        return (null !== $this->getLockedInformation());
    }

    /**
     * Return LockFile Path
     *
     * @return string
     *
     * @throws RuntimeException
     */
    private function getLockFilePath()
    {
        if (null == $this->lockDirectory) {
            throw new RuntimeException('Lock Directory has not been set. Process could not be locked. Use setter to set lock directory.');
        }
        if (null == $this->lockName) {
            throw new RuntimeException('Lock Name has not been set. Process could not be locked. Use setter to set lock name.');
        }

        return $this->lockDirectory . DIRECTORY_SEPARATOR . $this->lockName . '.lock';
    }

    /**
     * @return string
     */
    public function getLockDirectory()
    {
        return $this->lockDirectory;
    }

    /**
     * @param string $lockDirectory
     *
     * @return LockHandler
     */
    public function setLockDirectory($lockDirectory)
    {
        $this->lockDirectory = $lockDirectory;

        return $this;
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
    public function getTimeStampStart()
    {
        return $this->timeStampStart;
    }

    /**
     * @param string $userName
     *
     * @return LockHandler
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLockName()
    {
        return $this->lockName;
    }

    /**
     * @param string $lockName
     *
     * @return LockHandler
     */
    public function setLockName($lockName)
    {
        $this->lockName = $lockName;

        return $this;
    }

}
