<?php
/**
 * Class Minify_Logger.
 */

/**
 * Message logging class.
 *
 * @author Stephen Clay <steve@mrclay.org>
 */
class Minify_Logger
{
    /**
     * Set Pelican_Log object.
     *
     * The object should have a method "log" that accepts a value as 1st argument and
     * an optional string label as the 2nd.
     *
     * @param mixed $obj or a "falsey" value to disable
     */
    public static function setLogger($obj = null)
    {
        self::$_logger = $obj
            ? $obj
            : null;
    }

    /**
     * Pass a message to the Pelican_Log (if set).
     *
     * @param string $msg message to log
     */
    public static function log($msg, $label = 'Minify')
    {
        if (! self::$_logger) {
            return;
        }
        self::$_logger->log($msg, $label);
    }

    /**
     * @var mixed Pelican_Log object (like FirePHP) or null (i.e. no Pelican_Log available)
     */
    private static $_logger = null;
}
