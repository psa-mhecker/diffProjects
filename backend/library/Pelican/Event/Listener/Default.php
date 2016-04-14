<?php
/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
require_once 'Pelican/Exception/Error.php';
require_once 'Pelican/Event/Interface.php';

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class Pelican_Event_Listener_Default implements Pelican_Event_Listener
{
    const ERROR_00 = 'logger not found';

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function __construct()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $message __DESC__
     *
     * @return __TYPE__
     */
    public function onEvent(Pelican_Event & $message)
    {
        require_once 'Pelican/Log/Factory.php';
        $logger = Pelican_Log_Factory::getLog(Pelican_Log_Factory::FrameworkInitialization);
        if ($logger != null && $logger instanceof Zend_Log_Writer_Abstract) {
            $logger->write($message->toString());
        } else {
            throw new Pelican_Exception_Error(self::ERROR_00);
        }
    }
}

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class Pelican_Event_Listener_Console implements Pelican_Event_Listener
{
    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function __construct()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $message __DESC__
     *
     * @return __TYPE__
     */
    public function onEvent(Pelican_Event & $message)
    {
        echo $message."\n";
    }
}

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class Pelican_Event_Listener_Queuing implements Pelican_Event_Listener
{
    const FILENAME = '%filename%';

    const FILE_ERROR = 'Impossible d\'ouvrir le fichier (%filename%)';

    const FILE_WRITE_ERROR = 'Impossible d\'�crire le fichier (%filename%)';

    // endroit ou se trouve le fichier d'ecriture des queues
    const ROOT = '/tmp/';

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function __construct()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $event __DESC__
     *
     * @return __TYPE__
     */
    public function onEvent(Pelican_Event & $event)
    {
        if ($event instanceof Pelican_Event_Sql) {
            $filename = Pelican_Queuing_Listener::ROOT.$this->getSessionID();
            $handle = null;
            if (!$handle = fopen($filename, 'a+')) {
                self::throwError(self::FILE_ERROR);
            }

            if (fwrite($handle, serialize($event)) === false) {
                self::throwError(self::FILE_WRITE_ERROR);
            }

            if (fwrite($handle, "\n") === v) {
                self::throwError(self::FILE_WRITE_ERROR);
            }

            fclose($handle);
        }
    }

    /**
     * __DESC__.
     *
     * @static
     * @access private
     *
     * @param __TYPE__ $error __DESC__
     *
     * @return __TYPE__
     */
    private static function throwError($error)
    {
        throw new Pelican_Exception_Error(str_replace(self::FILENAME, $filename, $error));
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function getSessionID()
    {
        $session_id = session_id();
        if ($session_id == null || strcmp($session_id, '')) {
            $session_id = md5(uniqid(microtime()));
        }

        return $session_id;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @return __TYPE__
     */
    public static function read()
    {
    }
}
