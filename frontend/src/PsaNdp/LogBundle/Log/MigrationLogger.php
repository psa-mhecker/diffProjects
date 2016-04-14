<?php

namespace PsaNdp\LogBundle\Log;


/**
 * Class MigrationLogger
 */
class MigrationLogger extends AbstractLogger
{
    const APPLICATION_LOG_TYPE_MIGRATION_PROCESSING = 'data migration';
    const APPLICATION_LOG_TYPE_MIGRATION_REPORTING = 'data migration';

    /**
     * @param string $directory Path directory to save log. Ex /var/backend/log
     */
    public function __construct($directory)
    {
        parent::__construct($directory, self::APPLICATION_LOG_TYPE_MIGRATION_PROCESSING);
    }

    /**
     * @return int|string
     */
    protected function getSiteId()
    {
        return $_SESSION[APP]['SITE_ID'];
    }
}
