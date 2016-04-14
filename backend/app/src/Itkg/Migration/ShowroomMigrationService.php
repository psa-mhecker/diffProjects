<?php

namespace Itkg\Migration;

use Itkg\Migration\Event\CallableEventsInterface;
use Itkg\Migration\Exception\MigrationLockException;
use Itkg\Migration\Lock\LockHandler;
use Itkg\Migration\Reporting\DataMigrationReporting;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\Transaction\ShowroomTransactionManager;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use Itkg\Migration\UrlManager\ShowroomUrlManagerFactory;
use Itkg\Migration\XML\ShowroomXMLParserService;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\User\PsaUser;
use PsaNdp\LogBundle\Event\MigrationEvent;
use PsaNdp\LogBundle\MigrationEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;


/**
 * Class ShowroomMigration
 * @package Itkg\Migration
 */
class ShowroomMigrationService
{
    /** @var DataMigrationReporting */
    private $reporting;
    /** @var ShowroomUrlManagerFactory */
    private $urlManagerFactory;
    /** @var LockHandler */
    private $lock;

    /** @var PsaSite */
    private $site = null;
    /** @var array of ShowroomUrlManager */
    private $urlManagers;

    /** @var ShowroomXMLParserService */
    private $xmlParser;
    /** @var ShowroomTransactionManager */
    private $transactionManager;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param Filesystem $filesystem
     * @param DataMigrationReporting $reporting
     * @param ShowroomUrlManagerFactory $urlManagerFactory
     * @param LockHandler $lock
     * @param ShowroomXMLParserService $xmlParser
     * @param ShowroomTransactionManager $transactionManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param array $directoryNamesConfiguration expected keys for 'root', 'lock', 'reporting', 'xml'
     */
    public function __construct(
        Filesystem $filesystem,
        DataMigrationReporting $reporting,
        ShowroomUrlManagerFactory $urlManagerFactory,
        LockHandler $lock,
        ShowroomXMLParserService $xmlParser,
        ShowroomTransactionManager $transactionManager,
        EventDispatcherInterface $eventDispatcher,
        array $directoryNamesConfiguration
    )
    {
        $this->reporting = $reporting;
        $this->urlManagerFactory = $urlManagerFactory;
        $this->lock = $lock;
        $this->eventDispatcher = $eventDispatcher;
        $this->xmlParser = $xmlParser;
        $this->transactionManager = $transactionManager;

        // Configure services
        $path = getenv('BACKEND_VAR_PATH') . DIRECTORY_SEPARATOR . $directoryNamesConfiguration['root'];
        $filesystem->mkdir($path, 0777);

        $lockDirectory = $path . DIRECTORY_SEPARATOR . $directoryNamesConfiguration['lock'];
        $filesystem->mkdir($lockDirectory, 0777);
        $lock->setLockDirectory($lockDirectory);

        $reportingDirectory = $path . DIRECTORY_SEPARATOR . $directoryNamesConfiguration['reporting'];
        $filesystem->mkdir($reportingDirectory, 0777);
        $this->reporting->setDirectory($reportingDirectory);

        $xmlTempDirectory = $path . DIRECTORY_SEPARATOR . $directoryNamesConfiguration['xml'];
        $filesystem->mkdir($xmlTempDirectory, 0777);
        $this->xmlParser->setXmlDirectory($xmlTempDirectory);

        $mediaTempDirectory = $path . DIRECTORY_SEPARATOR . $directoryNamesConfiguration['media'];
        $filesystem->mkdir($mediaTempDirectory, 0777);
        $this->transactionManager->setTmpDwdDirectory($mediaTempDirectory);
    }


    /**********************************************************************************
     *                  Migration Process
     **********************************************************************************/

    /**
     * Migrate showroom xml to new NDP page
     *
     * @param PsaSite $site
     * @param PsaUser $user
     * @param array $urls
     * @param string $urlType
     *
     * @return DataMigrationReporting
     *
     * @throws MigrationLockException
     * @throws \Exception
     */
    public function migrate(PsaSite $site, PsaUser $user, array $urls, $urlType)
    {
        $this->site = $site;

        $startTime = microtime(true);
        // Configure Lock and check if already locked
        $this->lock->setLockName($this->createLockName($site));
        $this->lock->setUserName($user->getUserName() . ' - ' . $user->getUserLogin());
        if ($this->lock->isLocked()) {
            $lockInfos = $this->lock->getLockedInformation();

            $messageException = sprintf('Lock has been set for site with id : %s with label %s', $site->getSiteId(), $site->getSiteLabel());
            $lockException = new MigrationLockException($messageException);
            $lockException
                ->setUserName($lockInfos->userName)
                ->setSiteId($site->getSiteId())
                ->setSiteLabel($site->getSiteLabel())
                ->setStartDate($lockInfos->startDate)
                ->setStartHour($lockInfos->startHour);

            $endTime = microtime(true);
            $time = sprintf('%d', ($endTime - $startTime)*1000);
            $this->eventDispatcher->dispatch(MigrationEvents::MIGRATION_ERROR, new MigrationEvent($site, $user, $urls, $urlType, $time, $messageException));

            throw $lockException;
        }
        // Lock migration process for current site
        $this->lock->lock();

        try {
            // Configure reporting and urls
            $this->reporting->setReportingName($this->createReportFileName($site));
            $this->reporting->setTypeShowroom($urlType);
            $this->urlManagers = $this->createUrlManagers($urls, $urlType, $site, $user);

            // Parse XML
            $multiLingualShowrooms = $this->xmlParser->parse($this->urlManagers, $this->reporting, $this->site);

            if (0 === count($this->reporting->getErrorMessages())) {
                // Save Pages with their Slices and Media
                $this->transactionManager->saveShowrooms($multiLingualShowrooms, $this->reporting);
                //Post treatment (for PC23 and PN13)
                $this->launchCallableEvent(PsaPageShowroomMetadata::CALLABLE_SLICE_POST_SAVING, $multiLingualShowrooms);
                $message = implode(' ', $this->reporting->getInfosMessages());

                $endTime = microtime(true);
                $time = sprintf('%d', ($endTime - $startTime)*1000);

                $this->eventDispatcher->dispatch(MigrationEvents::MIGRATION_SUCCESS, new MigrationEvent(
                    $site,
                    $user,
                    $urls,
                    $urlType,
                    $time,
                    $message,
                    $this->reporting
                ));
            } else {
                $endTime = microtime(true);
                $time = sprintf('%d', ($endTime - $startTime)*1000);
                $message = implode(' ', $this->reporting->getErrorMessages());
                $this->eventDispatcher->dispatch(MigrationEvents::MIGRATION_ERROR, new MigrationEvent(
                    $site,
                    $user,
                    $urls,
                    $urlType,
                    $time,
                    $message,
                    $this->reporting
                ));
            }
        } catch (\Exception $e) {
            // Unlock migration
            $this->lock->unlock();
            $endTime = microtime(true);
            $time = sprintf('%d', ($endTime - $startTime)*1000);
            $this->eventDispatcher->dispatch(MigrationEvents::MIGRATION_ERROR, new MigrationEvent($site, $user, $urls, $urlType, $time, $e->getMessage()));

            throw $e;
        }

        // Update Reporting Data
        $this->updateReportUrls();

        // Unlock migration
        $this->lock->unlock();

        return $this->reporting;
    }


    /**
     * Set up a new report for logging result
     *
     * @param array $showroomUrls ['fr' => 'http://...', ...]
     * @param string $urlType
     * @param PsaSite $site
     * @param PsaUser $user
     *
     * @return array of showroomUrlManager
     */
    private function createUrlManagers(array $showroomUrls, $urlType, PsaSite $site, PsaUser $user)
    {
        $urlManagers = [];

        foreach ($showroomUrls as $language => $showroomUrl) {
            // Create a urlManager for the showroom
            $urlManagers[] = $this->urlManagerFactory->create(
                $showroomUrl,
                $urlType,
                $language,
                $site,
                $user
            );
        }

        return $urlManagers;
    }

    /**
     * Update reporting input data showroomUrls, xmlUrls, urlType
     */
    private function updateReportUrls()
    {
        foreach ($this->urlManagers as $urlManager) {
            /** @var ShowroomUrlManager $urlManager */
            $this->reporting->addUrl($urlManager->getLanguage(), $urlManager->getWelcomePageUrl());
            $this->reporting->addXml($urlManager->getLanguage(), $urlManager->getXmlUrl());
        }
    }


    /**
     * @return LockHandler
     */
    public function getLock()
    {
        return $this->lock;
    }

    /**
     * @param LockHandler $lock
     *
     * @return ShowroomMigrationService
     */
    public function setLock($lock)
    {
        $this->lock = $lock;

        return $this;
    }

    /**
     * @param PsaSite $site
     *
     * @return string
     */
    private function createReportFileName(PsaSite $site)
    {
        $startTime = date('d-m-Y-h\hi\ms\s');
        $siteLabelCleanString = strtolower(trim(preg_replace('#\W+#', '_', $site->getSiteLabel()), '_'));

        return $startTime . '-' . $site->getSiteId() . '-' . $siteLabelCleanString . '.log';
    }

    /**
     * @param PsaSite $site
     *
     * @return string
     */
    public function createLockName(PsaSite $site)
    {
        return (string)$site->getSiteId();
    }

    /**
     * Launch callable function from slice parser for homepage and subpages
     *
     * @param string $callableType
     * @param array $multiLingualShowrooms
     */
    private function launchCallableEvent($callableType, $multiLingualShowrooms)
    {
        foreach ($multiLingualShowrooms as $showroomPage) {
            // Launch callable method
            /** @var CallableEventsInterface $showroomPage */
            $eventResults = $showroomPage->launchCallableEvent($callableType);

            // Save the event result
            foreach($eventResults as $eventResult) {
                $this->transactionManager->saveSlicePostEvent($eventResult);
            }
        }
    }

}
