<?php
// +----------------------------------------------------------------------+
// | PHP FACTORY 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001-2012 Business&Decision Group / Interakting |
// +----------------------------------------------------------------------+
// | license: http://www.interakting.com/license/phpfactory |
// +----------------------------------------------------------------------+
// | link: http://www.interakting.com |
// +----------------------------------------------------------------------+
/**
 * Objet Requête, point d'entrée de la gestion du HMVC
 *
 * Adaptation de la classe Request du projet Kohana
 * (http://kohanaphp.com/license)
 *
 * @package Pelican
 * @subpackage Request
 */
/**
 * Librairies Pelican
 */
pelican_import('Observer_TrackEventSubscriber');
pelican_import('Observer_TrackEvent');
// use Observer\TrackEventSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Citroen\Event\PageEventSubscriber;
use Citroen\Perso\Score\IndicateurEventSubscriber;
include_once (Pelican::$config['LIB_ROOT'] . '/Pelican/Html.php');

pelican_import('Profiler');
pelican_import('Controller');
pelican_import('Route');
pelican_import('Exception');
define('REQUEST_INVOKE_MODE', 'call');
// define('REQUEST_INVOKE_MODE', 'reflection');

/**
 * Request object, base of HMVC
 * extends Zend_Controller_Request_Http
 *
 * Inspired by Pelican Request object (http://kohanaphp.com/license)
 *
 * @package Pelican
 * @subpackage Request
 * @author Raphael Carles <raphael.carles@interkating.com>
 */
class Pelican_Request extends Zend_Controller_Request_Http
{

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $key
     *            __DESC__
     * @param __TYPE__ $value
     *            __DESC__
     * @return __TYPE__
     */
    public function __set ($key, $value)
    {
        var_dump('A declarer en variable de classe');
        var_dump($key);
        var_dump(debug_backtrace());
        die();
    }

    /**
     * Singleton instance
     *
     * @access protected
     */
    protected static $_instance = null;

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public static $cacheUsed = false;

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public static $multidevice = false;

    public static $multidevice_template_switch = true;

    /**
     * List of Ajax response commands
     *
     * @access protected
     * @var array
     */
    protected static $_responseCommand = null;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected static $_count = 0;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected static $_level = 0;

    /**
     * Array of invocation parameters to use when instantiating action
     * controllers
     *
     * @access protected
     * @var array
     */
    protected $_invokeParams = array();

    /**
     * Protocol: http, https, ftp, cli, etc
     *
     * @access protected
     * @var string
     */
    protected static $_protocol = 'http';

    /**
     * Client user agent
     *
     * @access protected
     * @var string
     */
    protected static $_userAgent = '';

    /**
     * Route matched for this request
     *
     * @access public
     * @var object
     */
    public $route;

    /**
     * HTTP response code: 200, 404, 500, etc
     *
     * @access protected
     * @var int
     */
    protected $_status = 200;

    /**
     * Response body
     *
     * @access protected
     * @var string
     */
    protected $_response = '';

    /**
     * Headers to send with the response body
     *
     * @access protected
     * @var array
     */
    protected $_headers = array();

    /**
     * Controller directory
     *
     * @access public
     * @var string
     */
    public $root = '';

    /**
     * Controller directory
     *
     * @access public
     * @var string
     */
    public $directory = '';

    /**
     * Controller to be executed
     *
     * @access public
     * @var string
     */
    public $controller;

    /**
     * Action to be executed in the controller
     *
     * @access public
     * @var string
     */
    public $action;

    /**
     * __DESC__
     *
     * @access public
     * @var string
     */
    public $format;

    /**
     * The URI of the request
     *
     * @access public
     * @var string
     */
    public $uri;

    /**
     * __DESC__
     *
     * @access protected
     * @var array
     */
    protected $_params;

    /**
     * __DESC__
     *
     * @access public
     * @var array
     */
    static $trace = array();

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public static $userAgentFeatures;

    /**
     * __DESC__
     *
     * @access protected
     * @var bool
     */
    protected $_showPath;

    /**
     * __DESC__
     *
     * @access public
     * @var string
     */
    public $controllerName;

    /**
     * __DESC__
     *
     * @access public
     * @var string
     */
    public $controllerPath;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $cacheId;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $_dispatcher;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    public static $executed;

    /**
     * Retourne l'instance Pelican_Request racine (ou principale)
     *
     * @access public
     * @return Pelican_Request
     */
    public static function getMain ()
    {
        return self::getInstance();
    }

    /**
     * __DESC__
     *
     * @access public
     * @return Pelican_Request
     */
    public static function getTop ()
    {
        return self::getMain();
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function isMain ()
    {
        return ($this == self::$_instance);
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function isTop ()
    {
        return $this->isMain();
    }

    /**
     * Enforce singleton; disallow cloning
     *
     * @access protected
     * @return void
     */
    protected function __clone ()
    {}

    /**
     * Main request singleton instance.
     * If no URI is provided, the URI will
     * be automatically detected using PATH_INFO, REQUEST_URI, or PHP_SELF.
     *
     * @access public
     * @param bool $uri
     *            (option) __DESC__
     * @return Pelican_Request
     */
    public static function getInstance (&$uri = TRUE)
    {
        if (null === self::$_instance) {
            self::identifyUserAgent();
            /**
             * definition des attributs de l'objet Request
             */
            if (PHP_SAPI === 'cli') {
                // Get the command line options
                $options = CLI::options('uri', 'method', 'get', 'post');
                if (isset($options['uri'])) {
                    $uri = $options['uri'];
                }
                // Default protocol for command line is cli://
                self::setProtocol('cli');
                if (isset($options['method'])) {
                    // Use the specified method
                    self::setMethod(strtoupper($options['method']));
                }
                if (isset($options['get'])) {
                    // Overload the global GET data
                    parse_str($options['get'], $_GET);
                }
                if (isset($options['post'])) {
                    // Overload the global POST data
                    parse_str($options['post'], $_POST);
                }
            } else {
                if ($_SERVER['REQUEST_METHOD'] !== 'GET' and $_SERVER['REQUEST_METHOD'] !== 'POST') {
                    // Methods besides GET and POST do not properly parse the form-encoded
                    // query string into the $_POST array, so we overload it manually.
                    parse_str(file_get_contents('php://input'), $_POST);
                }
                if ($uri === TRUE) {
                    if (isset($_SERVER['PATH_INFO'])) {
                        // PATH_INFO does not contain the docroot or Pelican_Index
                        $uri = $_SERVER['PATH_INFO'];
                    } else {
                        // REQUEST_URI and PHP_SELF include the docroot and Pelican_Index
                        if (isset($_SERVER['REQUEST_URI'])) {
                            // REQUEST_URI includes the query string, remove it
                            $request = str_replace('/http://', '/_http_/', $_SERVER['REQUEST_URI']);
                            $uri = parse_url($request, PHP_URL_PATH);
                        } elseif (isset($_SERVER['PHP_SELF'])) {
                            $uri = $_SERVER['PHP_SELF'];
                        } else {
                            // If you ever see this error, please report an issue at and include a dump of $_SERVER
                            throw new Pelican_Exception('Unable to detect the URI using PATH_INFO, REQUEST_URI, or PHP_SELF');
                        }
                    }
                }
            }
            $uri = str_replace('benchmark.php', '', $uri);
            // Reduce multiple slashes to a single slash
            $uri = preg_replace('#//+#', '/', $uri);
            // Remove all dot-paths from the URI, they are not valid
            $uri = preg_replace('#\.[\s./]*/#', '', $uri);
            // Create the instance singleton
            self::$_instance = new self($uri);
        }
        return self::$_instance;
    }

    /**
     * Creates a new request object for the given URI.
     * Global GET and POST data
     * can be overloaded by setting "get" and "post" in the parameters.
     *
     * Throws an exception when no route can be found for the URI.
     *
     * @access public
     * @throws Pelican_Request_Exception
     * @param string $uri
     *            __DESC__
     * @param array $localParams
     *            (option) __DESC__
     * @param bool $routeCache
     *            (option) __DESC__
     * @return void
     */
    public function __construct ($uri, $localParams = array(), $routeCache = true)
    {
        Pelican_Profiler::start(($uri ? $uri : '/'), 'construct request');
        self::$_count ++;
        self::$_level ++;
        // Remove trailing slashes from the URI
        $uri = trim($uri, '/');
        // var_dump( Pelican_Observer_TrackEventSubscriber::getSubscribedEvents());
        
        if (Pelican::$config[APP]['enable_action_tracking']) {
            $this->_dispatcher = new EventDispatcher();
            $this->_dispatcher->addSubscriber(new Pelican_Observer_TrackEventSubscriber());
            $this->_dispatcher->addSubscriber(new PageEventSubscriber());
            $this->_dispatcher->addSubscriber(new IndicateurEventSubscriber());
        }
        
        // Load routes
        $routes = Pelican_Route::init($routeCache);
        if (isset($_GET['template'])) {
            $this->_showPath = $_GET['template'];
        }
        if (! isset(Pelican::$config['route_sequence'])) {
            Pelican::$config['route_sequence'] = array(
                'Mvc',
                'Clearurl',
                'Rewrite',
                'Sitemap'
            );
        }
        if (is_array(Pelican::$config['route_sequence'])) {
            foreach (Pelican::$config['route_sequence'] as $adapter) {
                // var_dump($adapter);
                pelican_import('Request.Route.' . $adapter);
                $r = Pelican_Factory::getInstance('Request.Route.' . $adapter, $uri, $routes);
                $match = $r->process();
                if ($match) {
                    if (! empty($match['route'])) {
                        $this->uri = $uri;
                        $this->route = $match['route'];
                        $this->setParams($localParams);
                        $this->_defaultParams($match['params']);
                    } else {
                        $redirect = $match;
                    }
                    break;
                }
            }
        }
        Pelican_Profiler::stop(($uri ? $uri : '/'), 'construct request');
        if ($this->route) {
            self::$trace[] = $this->uri;
            return;
        } elseif (! empty($redirect)) {
            if ($redirect['code'] >= 300 && $redirect['code'] < 400) {
                $this->redirect($redirect['url'], $redirect['code']);
            } else {
                $this->sendError($redirect['code'], $redirect['url']);
            }
        } else {
            $this->sendError(404);
        }
    }

    /**
     * Creates a new request object for the given URI.
     *
     * @access public
     * @param string $uri
     *            __DESC__
     * @param array $localParams
     *            (option) __DESC__
     * @return Pelican_Request
     */
    public static function factory ($uri, $localParams = array())
    {
        /**
         * doit toujours commencer par '_/' pour ne pas interferer avec mod_rewrite
         */
        $uri = '_/' . trim($uri, '_/');
        return new Pelican_Request($uri, $localParams);
    }

    /**
     * Returns the accepted content types.
     * If a specific type is defined,
     * the quality of that type will be returned.
     *
     * @access public
     * @staticvar xx $accepts
     * @param string $type
     *            (option) __DESC__
     * @return array
     */
    public static function acceptType ($type = NULL)
    {
        static $accepts;
        if ($accepts === NULL) {
            // Parse the HTTP_ACCEPT header
            $accepts = self::_parseAccept($_SERVER['HTTP_ACCEPT'], array(
                '*/*' => 1.0
            ));
        }
        if (isset($type)) {
            // Return the quality setting for this type
            return isset($accepts[$type]) ? $accepts[$type] : $accepts['*/*'];
        }
        return $accepts;
    }

    /**
     * Returns the accepted languages.
     * If a specific language is defined,
     * the quality of that language will be returned. If the language is not
     *
     * Accepted, FALSE will be returned.
     *
     * @access public
     * @staticvar xx $accepts
     * @param string $lang
     *            (option) __DESC__
     * @return array
     */
    public static function acceptLang ($lang = NULL)
    {
        static $accepts;
        if ($accepts === NULL) {
            // Parse the HTTP_ACCEPT_LANGUAGE header
            $accepts = self::_parseAccept($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        }
        if (isset($lang)) {
            // Return the quality setting for this lang
            return isset($accepts[$lang]) ? $accepts[$lang] : FALSE;
        }
        return $accepts;
    }

    /**
     * Returns the accepted encodings.
     * If a specific encoding is defined,
     * the quality of that encoding will be returned. If the encoding is not
     *
     * Accepted, FALSE will be returned.
     *
     * @access public
     * @staticvar xx $accepts
     * @param string $type
     *            (option) __DESC__
     * @return array
     */
    public static function acceptEncoding ($type = NULL)
    {
        static $accepts;
        if ($accepts === NULL) {
            // Parse the HTTP_ACCEPT_LANGUAGE header
            $accepts = self::_parseAccept($_SERVER['HTTP_ACCEPT_ENCODING']);
        }
        if (isset($type)) {
            // Return the quality setting for this type
            return isset($accepts[$type]) ? $accepts[$type] : FALSE;
        }
        return $accepts;
    }

    /**
     * Parses an accept header and returns an array (type => quality) of the
     * accepted types, ordered by quality.
     *
     * @access protected
     * @param string $header
     *            __DESC__
     * @param string $accepts
     *            (option) __DESC__
     * @return array
     */
    protected static function _parseAccept (&$header, array $accepts = NULL)
    {
        if (! empty($header)) {
            // Get all of the types
            $types = explode(',', $header);
            foreach ($types as $type) {
                // Split the type into parts
                $parts = explode(';', $type);
                // Make the type only the MIME
                $type = trim(array_shift($parts));
                // Default quality is 1.0
                $quality = 1.0;
                foreach ($parts as $part) {
                    // Prevent undefined $value notice below
                    if (strpos($part, '=') === FALSE) {
                        continue;
                    }
                    // Separate the key and value
                    list ($key, $value) = explode('=', trim($part));
                    if ($key === 'q') {
                        // There is a quality for this type
                        $quality = (float) trim($value);
                    }
                }
                // Add the accept type and quality
                $accepts[$type] = $quality;
            }
        }
        // Make sure that accepts is an array
        $accepts = (array) $accepts;
        // Order by quality
        arsort($accepts);
        return $accepts;
    }

    /**
     * Returns the response as the string representation of a request.
     *
     * @access public
     * @return string
     */
    public function __toString ()
    {
        return (string) $this->getResponse();
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $controller
     *            __DESC__
     * @param string $action
     *            __DESC__
     * @param array $params
     *            __DESC__
     * @param string $type
     *            (option) __DESC__
     * @return void
     */
    public function buildUri ($controller, $action, $params, $type = "")
    {}

    /**
     * If Request is ajax, returns true
     *
     * @access public
     * @return bool
     */
    public function isAjax ()
    {
        return $this->isXmlHttpRequest();
    }

    /**
     * Surcharge de Zend_Controller_Request_Http
     *
     * @access public
     * @see Zend_Controller_Request_Http::isXmlHttpRequest()
     * @return __TYPE__
     */
    public function isXmlHttpRequest ()
    {
        if (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sends the response status and all set headers.
     *
     * @access public
     * @return this
     */
    public function sendHeaders ()
    {
        if (! headers_sent()) {
            $headers = $this->getHeaders();
            if (! isset($headers['Content-Type'])) {
                // Add the default Content-Type header if it is not already defined
                $headers['Content-Type'] = 'text/html; charset=' . Pelican::$config['CHARSET'];
            }
            foreach ($headers as $name => $value) {
                if (is_string($name)) {
                    if ($value) {
                        // Combine the name and value to make a raw header
                        $value = "{$name}: " . $value;
                    } else {
                        $value = "{$name}";
                    }
                }
                // Send the raw header
                // @todo : verifier avec header("HTTP/1.0 304 Not Modified");
                if ($this->getStatus() != '200') {
                    header($value, true, $this->getStatus());
                } else {
                    header($value, true);
                }
            }
        }
        return $this;
    }

    /**
     * Redirects as the request response.
     *
     * @access public
     * @param string $url
     *            __DESC__
     * @param string $code
     *            (option) __DESC__
     * @return void
     */
    public function redirect ($url, $code = 302)
    {
        // Set the response status
        $this->setStatus($code);
        // Set the location header
        $this->setHeaders('Location', $url);
        // Send headers
        $this->sendHeaders();
        // Stop execution
        exit();
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $code
     *            __DESC__
     * @param string $uri
     *            (option) __DESC__
     * @param string $msg
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function sendError ($code, $uri = '', $msg = '')
    {

        Pelican_Log::control($code, 'error');
        if (! $uri) {
            $uri = $this->uri;
        }
        $this->setResponse(Pelican_Request::call('Error/code' . $code, array(
            'uri' => $uri,
            'msg' => $msg
        )));
        if (self::$_level <= 1) {
            // set Status
            $this->setStatus($code);
        }
        if (! empty($uri) && $code != 404) {
            // Set the location header
            $this->setHeaders('Location', $uri);
        }
        // Send headers
        $this->sendHeaders();
        // Stop execution
         echo $this->getResponse();
        exit();
    }

    /**
     * Send file download as the response.
     * All execution will be halted when
     * this method is called! Use TRUE for the filename to send the current
     *
     * Response as the file content.
     *
     * @access public
     * @param string $filename
     *            __DESC__
     * @param string $download
     *            (option) __DESC__
     * @param bool $resumable
     *            (option) __DESC__
     * @return void
     */
    public function sendFile ($filename, $download = NULL, $resumable = FALSE)
    {
        if ($filename === TRUE) {
            if (empty($download)) {
                throw new Pelican_Exception('Download name must be provided for streaming files');
            }
            // Get the content size
            $size = strlen($this->_response['body']);
            // Get the extension of the download
            $extension = strtolower(pathinfo($download, PATHINFO_EXTENSION));
            // Guess the mime using the file extension
            $mime = Pelican_File::config('mimes');
            $mime = $mime[$extension][0];
            // Create a temporary file to hold the current response
            $file = tmpfile();
            // Write the current response into the file
            fwrite($file, $this->_response['body']);
            // Prepare the file for reading
            fseek($file, 0);
        } else {
            // Get the complete file path
            $filename = realpath($filename);
            if (empty($download)) {
                // Use the file name as the download file name
                $download = pathinfo($filename, PATHINFO_BASENAME);
            }
            // Get the file size
            $size = filesize($filename);
            // Get the mime type
            $mime = Pelican_File::mime($filename);
            // Open the file for reading
            $file = fopen($filename, 'rb');
        }
        // Set the headers for a download
        $this->setHeaders('Content-Disposition', 'attachment; filename="' . $download . '"');
        $this->setHeaders('Content-Type', $mime);
        $this->setHeaders('Content-Length', $size);
        // Set the starting offset and length to send
        $ranges = NULL;
        if ($resumable === TRUE) {
            if (isset($_SERVER['HTTP_RANGE'])) { // @todo: ranged download processing
            }
            // Accept accepted range type
            $this->setHeaders('Accept-Ranges', 'bytes');
        }
        // Send all headers now
        $this->sendHeaders();
        while (ob_get_level()) {
            // Flush all output buffers
            ob_end_flush();
        }
        // Manually stop execution
        ignore_user_abort(TRUE);
        // Keep the script running forever
        set_time_limit(0);
        // Send data in 16kb blocks
        $block = 1024 * 16;
        while (! feof($file)) {
            if (connection_aborted()) {
                break;
            }
            // Output a block of the file
            echo fread($file, $block);
            // Send the data now
            flush();
        }
        // Close the file
        fclose($file);
        // Stop execution
        exit();
    }

    private function trackAction ($oPelicanRequest = null)
    {

        self::$executed = true;
        include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Cookie.php');
        
        if ($oPelicanRequest != null) {
            $aParams = $this->_params;
            $oUser = \Citroen\UserProvider::getUser();
            
            if (! isset($_SESSION[APP]['perso_sess']) || $_SESSION[APP]['perso_sess'] == '') {
                if (isset($_COOKIE['CPPV2_perso']) && $_COOKIE['CPPV2_perso'] != '') {
                    $_SESSION[APP]['perso_sess'] = $_COOKIE['CPPV2_perso'];
                } else {
                    $_SESSION[APP]['perso_sess'] = time() . session_id();
                    Backoffice_Cookie_Helper::setCookie('CPPV2_perso', $_SESSION[APP]['perso_sess']);
                }
            } elseif (isset($_SESSION[APP]['perso_sess']) && $_SESSION[APP]['perso_sess'] != '') {
                Backoffice_Cookie_Helper::setCookie('CPPV2_perso', $_SESSION[APP]['perso_sess']);
            }

            $aRefererParams = array();
            //récuprer les parametres du parent dans le cas d'une iframe
            if(isset($_SERVER['HTTP_REFERER'])&& !empty($_SERVER['HTTP_REFERER'])){
                $sRefererUrl = parse_url($_SERVER['HTTP_REFERER']);
                if(!empty($sRefererUrl['query'])){
                    parse_str($sRefererUrl['query'], $aRefererParams);
                }
            }

            $aRequestParams= array_merge($_REQUEST,array('referer_params'=>$aRefererParams));
            
            $oEvent = new Pelican_Observer_TrackEvent(array(
                
                'action' => Pelican_Controller::getActionName($this->action),
                'page_id' => $aParams['pid'],
                'uri' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/' . $oPelicanRequest->uri,
                'controller' => $oPelicanRequest->controllerName,
                'is_ajax' => $oPelicanRequest->isAjax(),
                'site_id' => $_SESSION[APP]['SITE_ID'],
                'langue_id' => $_SESSION[APP]['LANGUE_ID'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'ip' => $_SERVER['REMOTE_ADDR'],
                'session_id' => $_SESSION[APP]['perso_sess'],
                'time' => time(),
                'user' => $oUser,
                'params' => $aRequestParams
            ));

            $action =Pelican_Controller::getActionName($this->action);
            $this->_dispatcher->dispatch(Pelican_Observer_TrackEvent::TRACK, $oEvent);
        }
    }

    /**
     * Processes the request, executing the controller.
     * Before the routed action
     * is run, the before() method will be called, which allows the controller
     *
     * To overload the action based on the request parameters. After the action
     * is run, the after() method will be called, for post-processing.
     *
     * By default, the output from the controller is captured and returned, and
     * no headers are sent.
     *
     * @access public
     * @return this
     */
    public function execute ()
    {
        $aSite = Pelican_Cache::fetch("Frontend/Site", array(
            $_SESSION[APP]['SITE_ID']
        ));
        
        $profile = Pelican_Profiler::start('h' . self::$_level . ' : ' . $this->uri, 'request');
        $this->controllerName = Pelican_Controller::getControllerName($this->controller, $this->directory);
        $this->controllerPath = Pelican_Controller::getControllerPath($this->controller, $this->directory, $this->root);
        // Start _work/benchmarking
        try {
            if (! class_exists($this->controllerName)) {
                if (file_exists($this->controllerPath)) {
                    include ($this->controllerPath);
                } else {
                    // if ($_GET['template']) {
                    $this->setResponse(Pelican_Html::div(array(
                        style => 'background-color:red;padding:10px;'
                    ), 'Controller ' . $this->controllerName . ' (' . $this->controllerPath . ') introuvable'));
                    return $this;
                    // } else {
                    // $this->sendError(404, '', 'Controller ' . $this->controllerName . ' (' . $this->controllerPath . ') introuvable');
                    // }
                }
            }
            if (isset($aSite['SITE_PERSO_ACTIVATION']) && $aSite['SITE_PERSO_ACTIVATION'] == true) {
			
				//if (! self::$executed && $this->root != 'backend') {
                if (! self::$executed && substr($this->root,-7,7)!='backend') {
                    $this->trackAction($this);
                }
            }
            
            switch (REQUEST_INVOKE_MODE) {
                case 'call':
                    {
                        
                        // Create a new instance of the controller
                        $controller = new $this->controllerName($this);
                        
                        // Execute the "before action" method
                        $controller->before();
                        Pelican_Profiler::start($this->controllerName . '->' . $this->action, 'action');
                        if (! empty($this->action)) {
                            $action = $this->action;
                            // Execute the main action with the parameters
                            call_user_func_array(array(
                                $controller,
                                Pelican_Controller::getActionName($action)
                            ), $this->_params);
                        }
                        Pelican_Profiler::stop($this->controllerName . '->' . $this->action, 'action');
                        // Execute the "after action" method
                        $controller->after();
                        break;
                    }
                case 'reflection':
                    {
                        
                        // Load the controller using reflection
                        $class = new ReflectionClass($this->controllerName);
                        // Create a new instance of the controller
                        $controller = $class->newInstance($this);
                        // Execute the "before action" method
                        $class->getMethod('before')->invoke($controller);
                        Pelican_Profiler::start($this->controllerName . '->' . $this->action, 'action');
                        if (! empty($this->action)) {
                            $action = $this->action;
                            // Execute the main action with the parameters
                            $class->getMethod(Pelican_Controller::getActionName($action))->invokeArgs($controller, $this->_params);
                        }
                        Pelican_Profiler::stop($this->controllerName . '->' . $this->action, 'action');
                        // Execute the "after action" method
                        $class->getMethod('after')->invoke($controller);
                        break;
                    }
            }
            if ($this->_showPath) {
                $this->setResponse(Pelican_Html::div(array(
                    style => "border-width : 3px;border-style:solid;border-color:#297CB4;margin:5px;"
                ), Pelican_Html::div(array(
                    style => "-webkit-background-size: 100% 100%;
-moz-background-size: 100% 100%;
-o-background-size: 100% 100%;
background-size: 100% 100%;
background: -moz-linear-gradient(
 top,
 white,#50A3C8
 #72C6E4 4%,
 #0C5FA5
 );
background: -webkit-gradient(
 linear,
 left top, left bottom,
 from(white),
 to(#0C5FA5),
 color-stop(0.03, #72C6E4)
 );
-moz-text-shadow: -1px -1px 0 rgba(0, 0, 0, 0.2);
-webkit-text-shadow: -1px -1px 0 rgba(0, 0, 0, 0.2);
text-shadow: -1px -1px 0 rgba(0, 0, 0, 0.2);
padding: 0.278em 0.444em 0.389em;
border-color:  #297CB4 #083F6F;background-color:purple;border-bottom : 1px solid purple;font-weight:bold;color:white;"
                ), self::$_count . ' -> ' . ($this->uri ? $this->uri : '/') . ' (h' . self::$_level . ')') . $this->_response['body']));
            }
        } catch (Exception $e) {
            if (isset($profile)) {
                // Delete the _work/benchmark, it is invalid
                Pelican_Profiler::delete($this->uri, 'request');
            }
            // Re-throw the exception
            throw $e;
        }
        if (isset($profile)) {
            // Stop the _work/benchmark
            Pelican_Profiler::stop('h' . self::$_level . ' : ' . $this->uri, 'request');
        }
        
        return $this;
    }

    /**
     * Generate ETag
     * Generates an ETag from the response ready to be returned
     *
     * @access public
     * @throws Pelican_Request_Exception
     * @return String
     */
    public function generateEtag ()
    {
        if ($this->_response['body'] === NULL) {
            throw new Pelican_Request_Exception('No response yet associated with request - cannot auto generate resource ETag');
        }
        // Generate a unique hash for the response
        $return = '"' . sha1($this->_response['body']) . '"';
        return $return;
    }

    /**
     * Check Pelican_Cache
     * Checks the browser Pelican_Cache to see the response needs to be returned
     *
     * @access public
     * @throws Pelican_Request_Exception @chainable Array
     * @param string $etag
     *            (option) __DESC__
     * @return this
     */
    public function checkCache ($etag = null)
    {
        if (empty($etag)) {
            $etag = $this->generateEtag();
        }
        // Set the ETag header
        $this->setHeaders('ETag', $etag);
        // Add the Cache-Control header if it is not already set
        // This allows etags to be used with Max-Age, etc
        $this->setHeaders('Cache-Control', 'must-revalidate');
        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) and $_SERVER['HTTP_IF_NONE_MATCH'] === $etag) {
            // No need to send data again
            $this->setStatus(304);
            $this->sendHeaders();
            // Stop execution
            exit();
        }
        return $this;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $uri
     *            __DESC__
     * @param array $localParams
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function call ($uri, $localParams = array())
    {
        $return = self::factory($uri, $localParams)->execute()->getResponse();
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $uri
     *            __DESC__
     * @param array $localParams
     *            (option) __DESC__
     * @param bool $activeCache
     *            (option) __DESC__
     * @param int $lifetime
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function cachedCall ($uri, $localParams = array(), $activeCache = false, $lifetime = 30)
    {
        /**
         * parametrage de smarty pour le bloc
         * $caching true or false
         * $cache_lifetime durée en seconde du Pelican_Cache
         * -1 = Illimitée
         */
        Pelican_Profiler::start($uri, 'bloc');
        if ($activeCache) {
            self::$cacheUsed = true;
            $timestamp = Pelican_Cache::getSecondTimeStep($lifetime);
            $idcache = Pelican_View::getCacheId($idcache, array(
                $_SESSION[APP]['LANGUE_ID']
            ));
            $response = Pelican_Cache::fetch('Request', array(
                $uri,
                serialize($localParams),
                $idcache,
                $timestamp
            ));
        } else {
            $response = self::call($uri, $localParams);
        }
        /**
         * remise a false du cache pour le bloc suivant #peut etre inutile vus que le param de Pelican_Cache est par defaut false
         */
        Pelican_Profiler::stop($uri, 'bloc');
        if ($activeCache) {
            if (self::$cacheUsed) {
                $msg = '[cache de reponse ' . $lifetime . ' sec. : OK]';
            } else {
                $msg = '[sans cache de reponse]';
            }
            Pelican_Profiler::rename($uri, '&nbsp;&nbsp;' . $msg . '&nbsp;&nbsp;' . $uri, 'bloc');
        }
        return $response;
    }

    /**
     * Génération de l'id de cache de la vue
     *
     * @access public
     * @param string $idcache
     *            (option) Id de Cache
     * @param __TYPE__ $addon
     *            (option) __DESC__
     * @return string
     */
    static function getCacheId ($idcache = "", $addon = array())
    {
        $return = $idcache;
        if (is_array($return)) {
            ksort($return);
            $return = implode("|", $return);
        }
        $return .= "|" . implode('|', $addon) . "|" . ($_GET ? serialize($_GET) : "");
        $return = str_replace(array(
            "||",
            "{",
            "}",
            ";",
            ":",
            "\""
        ), array(
            "|",
            "",
            "",
            "",
            "",
            ""
        ), $return);
        return md5($return);
    }

    /**
     * __DESC__
     *
     * @access public
     * @return array
     */
    public static function getResponseCommand ()
    {
        return self::$_responseCommand;
    }

    /**
     * $this->addResponseCommand('assign', array('id'=>'...', 'attr'=>'...',
     * 'value'=>'...'));
     *
     * $this->addResponseCommand('append', array('id'=>'...', 'attr'=>'...',
     * 'value'=>'...'));
     *
     * $this->addResponseCommand('prepend', array('id'=>'...', 'attr'=>'...',
     * 'value'=>'...'));
     * $this->addResponseCommand('replace', array('id'=>'...', 'attr'=>'...',
     * 'search'=>'...', 'value'=>'...'));
     * $this->addResponseCommand('clear', array('id'=>'...', 'attr'=>'...'));
     * $this->addResponseCommand('remove', array('id'=>'...'));
     * $this->addResponseCommand('redirect', array('url'=>'...', 'delay'=>'...'));
     * $this->addResponseCommand('script', array('script'=>'...'));
     * $this->addResponseCommand('alert', array('value'=>'...'));
     * $this->addResponseCommand('debug', array('value'=>'...'));
     *
     * @access public
     * @param string $cmd
     *            __DESC__
     * @param array $params
     *            (option) __DESC__
     * @return void
     */
    public function addResponseCommand ($cmd, $params = array())
    {
        if (is_array($params)) {
            $temp = $params;
            $temp['cmd'] = $cmd;
            self::$_responseCommand[] = $temp;
        }
    }

    /**
     * ************** getters ***************
     */
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $key
     *            __DESC__
     * @param string $default
     *            (option) __DESC__
     * @return array
     */
    public function getParam ($key, $default = null)
    {
        $return = parent::getParam($key, $default);
        /* pour l'ajax */
        if (! $return) {
            $all = $this->getParams();
            if (is_int($key) && ! empty($all['route']) && $this->isAjax()) {
                $collect = false;
                foreach ($all as $k => $v) {
                    if ($collect) {
                        $p[] = $v;
                    }
                    if ($k == 'route') {
                        $collect = true;
                    }
                }
            }
            if (! empty($p[$key])) {
                $return = $p[$key];
            }
        }
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $feature
     *            (option) __DESC__
     * @return string
     */
    public function getUserAgentFeature ($feature = '')
    {
        $return = '';
        if (empty(self::$userAgentFeatures)) {
            $this->identifyUserAgent();
        }
        if (isset(self::$userAgentFeatures[$feature])) {
            return self::$userAgentFeatures[$feature];
        } else {
            $capability = self::$userAgentFeatures['device']->device->getFeature($feature);
            if (! empty($capability)) {
                $return = $capability;
            }
        }
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function identifyUserAgent ()
    {
        if (self::$multidevice) {
            if (empty(self::$userAgentFeatures)) {
                // userAgent identification
                Pelican_Profiler::start('identification', 'userAgent');
                self::$userAgentFeatures['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
                // config
                self::$userAgentFeatures['simulation'] = false;
                if (! empty($_SESSION['HTTP_USER_AGENT'])) {
                    if ($_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) {
                        $_SERVER['HTTP_USER_AGENT'] = $_SESSION['HTTP_USER_AGENT'];
                        self::$userAgentFeatures['simulation'] = true;
                    }
                }
                Pelican_Http_UserAgent::setConfig($config);
                // preview mode
                $previewMode = (isset($_GET['preview_browser_mode']) ? $_GET['preview_browser_mode'] : '');
                // Http_User_Agent
                self::$userAgentFeatures['device'] = new Pelican_Http_UserAgent($previewMode);
                // Browser Type
                self::$userAgentFeatures['type'] = self::$userAgentFeatures['device']->getType();
                // mobile
                if (self::$userAgentFeatures['type'] == 'mobile') {
                    self::$userAgentFeatures['mobileType'] = self::$userAgentFeatures['device']->mobileType;
                    // self::$userAgentFeatures['flash'] = (self::$userAgentFeatures['device']->device->getFeature('full_flash_support') != 'false');
                    self::$userAgentFeatures['flash'] = (self::$userAgentFeatures['device']->device->getFeature('fl_browser') != 'false');
                    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'iphone') !== false || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'ipod') !== false || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'ipad') !== false) {
                        self::$userAgentFeatures['flash'] = false;
                    }
                    // Google maps
                    if (self::$userAgentFeatures['device']->device->getFeature('ajax_xhr_type') !== 'none') {
                        self::$userAgentFeatures['map'] = '';
                    } else {
                        self::$userAgentFeatures['map'] = 'image';
                    }
                }
                Pelican_Profiler::stop('identification', 'userAgent');
            }
        } else {
            self::$userAgentFeatures['type'] = 'desktop';
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @staticvar xx $pass
     * @param __TYPE__ $buffer
     *            __DESC__
     * @param string $mode
     *            (option) __DESC__
     * @return __TYPE__
     */
    public function postRender ($buffer, $mode = "")
    {
        static $pass;
        $return = $buffer;
        if (! $pass) {
            $pass = true;
            require_once (pelican_path('Response.Adapter'));
            Pelican_Response_Adapter::$simulation = self::$userAgentFeatures['simulation'];
            if (! empty($_GET['markup'])) {
                $return = Pelican_Response_Adapter::processLink($buffer);
            }
            if (empty(self::$userAgentFeatures['markup'])) {
                self::$userAgentFeatures['markup'] = $mode;
            }
            if ($mode == 'mobile') {
                // WURFL options
                $options = self::$userAgentFeatures['device']->device->getAllFeatures();
                // default options
                $options['image_host'] = Pelican::$config['MEDIA_HTTP'];
                // preferred markup
                if (Pelican_Http_UserAgent_Mobile::getMarkupLanguage($options['preferred_markup'])) {
                    self::$userAgentFeatures['markup'] = Pelican_Http_UserAgent_Mobile::getMarkupLanguage($options['preferred_markup']);
                } else {
                    self::$userAgentFeatures['markup'] = 'xhtmlmp';
                }
                // webkit & iphone
                if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'ipad') !== false) {
                    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera') === false) {
                        self::$userAgentFeatures['markup'] = 'tablet';
                    }
                } elseif (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'iphone') !== false || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'ipod') !== false) {
                    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera') === false) {
                        self::$userAgentFeatures['markup'] = 'apple';
                    }
                } elseif (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'applewebkit') !== false) {
                    self::$userAgentFeatures['markup'] = 'html5';
                }
            }
            // response transformation
            if (self::$userAgentFeatures['markup'] && $mode != self::$userAgentFeatures['markup']) {
                if (self::$userAgentFeatures['markup'] == 'txt') {
                    self::$userAgentFeatures['markup'] = 'text';
                }
                $adapter = Pelican_Response_Adapter::getInstance(self::$userAgentFeatures['markup'], $options);
                if ($adapter) {
                    Pelican::$config["SHOW_DEBUG"] = false;
                    $adapter->process($return);
                    $return = $adapter->getOutput();
                }
            }
        }
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return string
     */
    public function getReferer ()
    {
        return $this->getHeader('REFERER');
    }

    /**
     * __DESC__
     *
     * @access public
     * @return string
     */
    public function getProtocol ()
    {
        return $this->getScheme();
    }

    /**
     * __DESC__
     *
     * @access public
     * @return string
     */
    public function getStatus ()
    {
        return $this->_status;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param bool $compress
     *            (option) __DESC__
     * @param bool $dropComments
     *            (option) __DESC__
     * @param bool $encodeEmail
     *            (option) __DESC__
     * @param string $highlight
     *            (option) __DESC__
     * @return string
     */
    public function getResponse ($compress = false, $dropComments = false, $encodeEmail = false, $highlight = '')
    {
        $return['body'] = $this->_response['body'];
        /*
         * if ($this->isAjax()) { $return['head'] = Pelican_Factory::getInstance('View')->getHead() ->getHeader(false, false, false); return Zend_Json::encode($return); } else {
         */
        self::$_level --;
        if ($this->isMain()) {
            if (Pelican_Request::$multidevice_template_switch) {
                $return['body'] = $this->postRender($return['body'], self::$userAgentFeatures['type']);
            }
            if ($dropComments) {
                $return['body'] = Pelican_Html_Util::dropComments($return['body']);
            }
            if ($compress) {
                $return['body'] = Pelican_Html_Util::compress($return['body']);
            }
            if ($encodeEmail) {
                $return['body'] = Pelican_Html_Util::encodeAllEmail($return['body']);
            }
            if ($highlight) {
                $return['body'] = Pelican_Html_Util::highlightWord($return['body'], $highlight, "yellow");
            }
            if (! empty(Pelican::$config['DNS_SHARDING'])) {
                $return['body'] = Pelican_Html_Util::dnsSharding($return['body'], Pelican::$config['DNS_SHARDING']);
            }
        }
        
        return $return['body'];
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $key
     *            (option) __DESC__
     * @return array
     */
    public function getHeaders ($key = '')
    {
        if (! empty($key)) {
            return $this->_headers[$key];
        } else {
            return $this->_headers;
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @return string
     */
    public function getAction ()
    {
        return $this->action;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return string
     */
    public function getFormat ()
    {
        return $this->format;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return string
     */
    public function getController ()
    {
        return $this->controller;
    }

    /**
     * __DESC__
     *
     * @access public
     * @return string
     */
    public function getDirectory ()
    {
        return $this->directory;
    }

    /**
     * ************** setters ***************
     */
    /**
     * __DESC__
     *
     * @access protected
     * @param array $params
     *            __DESC__
     * @return void
     */
    protected function _defaultParams ($params)
    {
        if (isset($params['root'])) {
            // Module ou site
            $this->root = $params['root'];
        }
        if (isset($params['directory'])) {
            // controllers are in a sub-directory
            $this->directory = $params['directory'];
        }
        // Store the controller
        $this->controller = (! empty($params['controller']) ? $params['controller'] : Pelican_Controller::getDefaultController());
        if (isset($params['action'])) {
            // Store the action
            $this->action = (! empty($params['action']) ? $params['action'] : Pelican_Controller::getDefaultController());
        }
        if (isset($params['format'])) {
            // Store the action
            $this->format = $params['format'];
        }
        // These are accessible as public vars and can be overloaded
        unset($params['controller'], $params['action'], $params['directory']);
        // Params cannot be changed once matched
        $this->setParams($params);
        // $this->setParams('get', $_GET);
        // $this->setParams('post', $_POST);
        // $this->setParams('file', $_FILES);
    }

    /**
     * Retrieve an array of parameters
     *
     * Retrieves a merged array of parameters, with precedence of userland
     * params (see {@link setParam()}), $_GET, $_POST (i.e., values in the
     * userland params will take precedence over all others).
     *
     * @access public
     * @return array
     */
    public function getParams ()
    {
        $return = array();
        if (! empty($this->_params)) {
            $return = $this->_params;
        }
        $paramSources = $this->getParamSources();
        if (in_array('_GET', $paramSources) && isset($_GET) && is_array($_GET)) {
            $return += $_GET;
        }
        if (in_array('_POST', $paramSources) && isset($_POST) && is_array($_POST)) {
            $return += $_POST;
        }
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $key
     *            __DESC__
     * @param __TYPE__ $value
     *            __DESC__
     * @return __TYPE__
     */
    public function setParam ($key, $value)
    {
        $key = (string) $key;
        if ((null === $value) && isset($this->_params[$key])) {
            unset($this->_params[$key]);
        } elseif (null !== $value) {
            $this->_params[$key] = $value;
        }
        return $this;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $userAgent
     *            The $userAgent to set
     * @return void
     */
    public function setUserAgent ($userAgent)
    {
        $_SERVER['HTTP_USER_AGENT'] = $userAgent;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $protocol
     *            The $protocol to set
     * @return void
     */
    public function setProtocol ($protocol)
    {
        self::$_protocol = $protocol;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $status
     *            The $status to set
     * @return void
     */
    public function setStatus ($status)
    {
        $this->_status = $status;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $response
     *            __DESC__
     * @return void
     */
    public function setResponse ($response)
    {
        $this->_response['body'] = $response;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $key
     *            __DESC__
     * @param string $content
     *            (option) __DESC__
     * @return void
     */
    public function setHeaders ($key, $content = '')
    {
        $this->_headers[$key] = $content;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $action
     *            The $action to set
     * @return void
     */
    public function setAction ($action)
    {
        $this->action = $action;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $controller
     *            The $controller to set
     * @return viod
     */
    public function setController ($controller)
    {
        $this->controller = $controller;
    }
}
    
