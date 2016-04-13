<?php
/**
 * Classe de gestion centralisée des systèmes de log
 *
 * @package Pelican
 * @subpackage Log
 * @author Pierre Moiré <pierre.moire@businessdecision.com>
 */
require_once ('Zend/Log.php');

/**
 * Classe de gestion centralisée des systèmes de log
 *
 * @package Pelican
 * @subpackage Log
 * @author Pierre Moiré <pierre.moire@businessdecision.com>
 */
class Pelican_Log
{

    /**
     * Instance de logger, pour le singleton
     *
     * @static
     *
     *
     * @access private
     */
    public static $_instance = array();

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $channel;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $fireLogger;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $fireResponse;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $fireRequest;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $traceId;

    /**
     * Constructeur
     *
     * @access public
     * @param string $domain
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function __construct ($domain = '')
    {
        $file = str_replace('.', '-', $_SERVER['HTTP_HOST']) . '.log';
        $this->dir = str_replace('//', '/', Pelican::$config['LOG_ROOT'] . '/' . $domain . '/');
        $file = date("Ymd") . '-' . $file;
        if (! is_dir($this->dir)) {
            mkdir($this->dir, 0775, true);
        }
        $writer = new Zend_Log_Writer_Stream($this->dir . $file);
        $format = '%timestamp% - ' . $_ENV["HOSTNAME"] . ' - %priorityName% (%priority%) : %message%' . PHP_EOL;
        $formatter = new Zend_Log_Formatter_Simple($format);
        $writer->setFormatter($formatter);
        $this->logger = new Zend_Log($writer);
        $this->logger->setTimestampFormat("d-M-Y H:i:s");
        if (Pelican::$config['LOG_LEVEL']) {
            $filter = new Zend_Log_Filter_Priority(Pelican::$config['LOG_LEVEL'], '>=');
            $this->logger->addFilter($filter);
        }
        $this->traceId = rand(1000, 1000000);
        $this->env = strtolower(($_ENV["TYPE_ENVIRONNEMENT"] ? $_ENV["TYPE_ENVIRONNEMENT"] : Pelican::$config["TYPE_ENVIRONNEMENT"]));
        if ($this->env == 'dev' || $this->env == 'preprod') {
            // $this->setFireLogger ();
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function __destruct ()
    {
        // Envoi des donn�es d'historisation vers le navigateur
        /*
         * if ($this->env == 'dev' || $this->env == 'preprod') { $this->channel->flush (); $this->fireResponse->sendHeaders (); }
         */
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function setFireLogger ()
    {
        if ($this->env == 'dev' || $this->env == 'preprod') {
            $writer = new Zend_Log_Writer_Firebug();
            $this->fireLogger = new Zend_Log($writer);
            $this->fireRequest = new Zend_Controller_Request_Http();
            $this->fireResponse = new Zend_Controller_Response_Http();
            $this->channel = Zend_Wildfire_Channel_HttpHeaders::getInstance();
            $this->channel->setRequest($this->fireRequest);
            $this->channel->setResponse($this->fireResponse);
            // D�marrer l'output buffering
            ob_start();
        }
    }

    /**
     * Emergency: system is unusable
     *
     * @static
     *
     *
     * @access public
     * @param __TYPE__ $data
     *            __TYPE__ __DESC__
     * @param string $domain
     *            (option) __TYPE__ __DESC__
     * @return __TYPE__
     */
    public static function emerg ($data, $domain = '')
    {
        self::log($domain, $data, Zend_Log::EMERG);
    }

    /**
     * Alert: action must be taken immediately
     *
     * @static
     *
     *
     * @access public
     * @param __TYPE__ $data
     *            __TYPE__ __DESC__
     * @param string $domain
     *            (option) __TYPE__ __DESC__
     * @return __TYPE__
     */
    public static function alert ($data, $domain = '')
    {
        self::log($data, Zend_Log::ALERT, $domain);
    }

    /**
     * Critical: critical conditions
     *
     * @static
     *
     *
     * @access public
     * @param __TYPE__ $data
     *            __TYPE__ __DESC__
     * @param string $domain
     *            (option) __TYPE__ __DESC__
     * @return __TYPE__
     */
    public static function crit ($data, $domain = '')
    {
        self::log($data, Zend_Log::CRIT, $domain);
    }

    /**
     * Error: error conditions
     *
     * @static
     *
     *
     * @access public
     * @param __TYPE__ $data
     *            __TYPE__ __DESC__
     * @param string $domain
     *            (option) __TYPE__ __DESC__
     * @return __TYPE__
     */
    public static function error ($data, $domain = '')
    {
        self::log($data, Zend_Log::ERR, $domain);
    }

    /**
     * Warning: warning conditions
     *
     * @static
     *
     *
     * @access public
     * @param __TYPE__ $data
     *            __TYPE__ __DESC__
     * @param string $domain
     *            (option) __TYPE__ __DESC__
     * @return __TYPE__
     */
    public static function warning ($data, $domain = '')
    {
        self::log($data, Zend_Log::WARN, $domain);
    }

    /**
     * Notice: normal but significant condition
     *
     * @static
     *
     *
     * @access public
     * @param __TYPE__ $data
     *            __TYPE__ __DESC__
     * @param string $domain
     *            (option) __TYPE__ __DESC__
     * @return __TYPE__
     */
    public static function notice ($data, $domain = '')
    {
        self::log($data, Zend_Log::NOTICE, $domain);
    }

    /**
     * Informational: informational messages
     *
     * @static
     *
     *
     * @access public
     * @param __TYPE__ $data
     *            __TYPE__ __DESC__
     * @param string $domain
     *            (option) __TYPE__ __DESC__
     * @return __TYPE__
     */
    public static function info ($data, $domain = '')
    {
        self::log($data, Zend_Log::INFO, $domain);
    }

    /**
     * Debug: debug messages
     *
     * @static
     *
     *
     * @access public
     * @param __TYPE__ $data
     *            __TYPE__ __DESC__
     * @param string $domain
     *            (option) __TYPE__ __DESC__
     * @return __TYPE__
     */
    public static function debug ($data, $domain = '')
    {
        self::log($data, Zend_Log::DEBUG, $domain);
    }

    /**
     * __DESC__
     *
     * @static
     *
     *
     * @access public
     * @param __TYPE__ $data
     *            __DESC__
     * @param string $domain
     *            (option) __DESC__
     * @param __TYPE__ $pathLevel
     *            (option) __DESC__
     * @return __TYPE__
     */
    public static function control ($data, $domain = '', $pathLevel = 1)
    {
        $log = false;
        $source = true;
        if (! empty(Pelican::$config["PROFILING"])) {
            switch ($domain) {
                case 'connection':
                    {
                        $path = self::whereCalled(2);
                        if (! strpos(serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)), Pelican::$config["TEMPLATE_CACHE_ROOT"])) {
                            $level = Zend_Log::INFO;
                            $domain = 'control/' . $domain;
                            $log = true;
                        }
                        break;
                    }
                case 'sanscache':
                    {
                        $path = self::whereCalled(9);
                        if (! strpos(serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)), Pelican::$config["TEMPLATE_CACHE_ROOT"]) && Pelican_Db::isSelect($data)) {
                            $level = Zend_Log::INFO;
                            $domain = 'control/' . $domain;
                            $log = true;
                        }
                        break;
                    }
                case 'cache':
                    {
                        // if (! strpos ( serialize ( debug_backtrace () ), Pelican::$config ["TEMPLATE_CACHE_ROOT"] ) && Pelican_Db::isSelect ( $data )) {
                        $level = Zend_Log::INFO;
                        $domain = 'control/' . $domain;
                        $log = true;
                        // }
                        break;
                    }
                case 'security':
                    {
                        $path = self::whereCalled(0);
                        $level = Zend_Log::INFO;
                        $domain = 'control/' . $domain;
                        $log = true;
                        $source = false;
                        break;
                    }
                case 'generation':
                    {
                        $level = Zend_Log::INFO;
                        $domain = 'control/' . $domain;
                        $log = true;
                        $source = false;
                        break;
                    }
                case 'page':
                    {
                        $level = Zend_Log::INFO;
                        $domain = 'control/' . $domain;
                        $log = true;
                        $source = false;
                        break;
                    }
                case 'error':
                    {
                        $path = $_SERVER['HTTP_REFERER'].' -> '.$_SERVER['REQUEST_URI'];
                        $level = Zend_Log::INFO;
                        $domain = 'control/' . $domain;
                        $log = true;
                        break;
                    }
            }
            if ($log) {
                if ($source) {
                    self::log($path . ' -> ' . $data, $level, $domain);
                } else {
                    self::log($data, $level, $domain);
                }
            }
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $level
     *            (option) __DESC__
     * @return __TYPE__
     */
    function whereCalled ($level = 1)
    {
        $object = '';
        $trace = debug_backtrace();
        $file = $trace[$level]['file'];
        $line = $trace[$level]['line'];
        if (! empty($trace[$level]['object'])) {
            $object = $trace[$level]['object'];
        }
        if (is_object($object)) {
            $object = get_class($object);
        }
        return "ligne $line dans $file";
    }

    /**
     * Singleton : Renvoie une instance du Pelican_Log
     *
     * @static
     *
     *
     * @access public
     * @param __TYPE__ $message
     *            __DESC__
     * @param __TYPE__ $level
     *            __DESC__
     * @param string $domain
     *            (option) __DESC__
     * @return Pelican_Log
     */
    public static function log ($message, $level, $domain = '')
    {
        if (empty(self::$_instance[$domain])) {
            $log = new self($domain);
            self::$_instance[$domain] = $log->logger;
        }
        if (self::$_instance[$domain] instanceof Zend_Log) {
            self::$_instance[$domain]->log(self::clean($message), $level);
        } else {
            throw new Exception('Invalid type for logger');
        }
    }

    /**
     * __DESC__
     *
     * @static
     *
     *
     * @access public
     * @param __TYPE__ $message
     *            __DESC__
     * @return __TYPE__
     */
    public static function clean ($message)
    {
        $message = str_replace("\r", "", $message);
        $message = str_replace("\n", "", $message);
        $message = str_replace("\t", " ", $message);
        $message = str_replace("  ", " ", $message);
        $message = str_replace("  ", " ", $message);
        $message = str_replace("  ", " ", $message);
        return trim($message);
    }
}
?>
