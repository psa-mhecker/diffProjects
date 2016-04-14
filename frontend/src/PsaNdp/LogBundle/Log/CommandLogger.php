<?php

namespace PsaNdp\LogBundle\Log;

/**
 * Class CommandLogger
 */
class CommandLogger extends AbstractLogger
{
    const LOG_FILE_NAME = 'command.log';
    const APPLICATION_LOG_TYPE_COMMAND = 'command';

    /**
     * @param string       $directory Path directory to save log. Ex /var/frontend/log
     */
    public function __construct($directory)
    {
        parent::__construct($directory, self::APPLICATION_LOG_TYPE_COMMAND, self::LOG_FILE_NAME);
    }
}
