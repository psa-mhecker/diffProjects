<?php
/**
 * Routes are used to determine the controller and action for a requested URI.
 * Every route generates a regular expression which is used to match a URI
 *
 * And a route. Routes may also contain keys which can be used to set the
 * controller, action, and parameters.
 *
 * Each <key> will be translated to a regular expression using a default
 * regular expression pattern. You can override the default pattern by providing
 * a pattern for the key:
 *
 * // This route will only match when <id> is a digit
 * Pelican_Route::factory('user/edit/<id>', array('id' => '\d+'));
 *
 * // This route will match when <path> is anything
 * Pelican_Route::factory('<path>', array('path' => '.*'));
 *
 * It is also possible to create optional segments by using parentheses in
 * the URI definition:
 *
 * // This is the standard default route, and no keys are required
 * Pelican_Route::default('(<controller>(/<action>(/<id>)))');
 *
 * // This route only requires the :file key
 * Pelican_Route::factory('(<path>/)<file>(<format>)', array('path' => '.*',
 * 'format' => '\.\w+'));
 *
 * Routes also provide a way to generate URIs (called "reverse routing"), which
 * makes them an extremely powerful and flexible way to generate internal links.
 *
 * @package Pelican
 * @subpackage Route
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
class Pelican_Route {
    const REGEX_KEY = '<([a-zA-Z0-9_]++)>';
    const REGEX_SEGMENT = '[^/.,;?]++';
    const REGEX_ESCAPE = '[.\\+*?[^\\]${}=!|]';
    
    /**
     * Default action for all routes
     *
     * @static
     * @access public
     * @var string
     */
    public static $defaultAction = 'index';
    
    /** List of route objects */
    protected static $_routes = array();
    protected static $_passage = false;
    
    /**
     * Route URI string
     *
     * @access protected
     */
    protected $_uri = '';
    
    /**
     * Regular expressions for route keys
     *
     * @access protected
     */
    protected $_regex = array();
    
    /**
     * Default values for route keys
     *
     * @access protected
     */
    protected $_defaults = array('controller' => 'index', 'action' => 'index');
    
    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $_requestParams = array();
    
    /**
     * Compiled regex cache
     *
     * @access protected
     */
    protected $_route_regex;
    
    /**
     * Called when the object is re-constructed from the cache.
     *
     * @static
     * @access public
     * @param __TYPE__ $values __DESC__
     * @return Route
     */
    public static function __set_state(array $values) {
        // Reconstruct the route
        $route = new Pelican_Route();
        foreach($values as $key => $value) {
            // Set the route properties
            $route->$key = $value;
        }
        return $route;
    }
    
    /**
     * Stores a named route and returns it.
     *
     * @static
     * @access public
     * @param string $name Route name
     * @param string $uri URI pattern
     * @param Array $regex (option) regex patterns for route keys
     * @return Route
     */
    public static function set($name, $uri, array $regex = NULL) {
        return self::$_routes[$name] = new Pelican_Route($uri, $regex);
    }
    
    /**
     * Retrieves a named route.
     *
     * @static
     * @access public
     * @param string $name Route name
     * @return FALSE
     */
    public static function get($name) {
        if (!isset(self::$_routes[$name])) {
            throw new Pelican_Exception('The requested route does not exist: :route', array(':route' => $name));
        }
        return self::$_routes[$name];
    }
    
    /**
     * Retrieves all named routes.
     *
     * @static
     * @access public
     * @param bool $save (option) __DESC__
     * @return array
     */
    public static function init($save = true) {
        // pour limiter l'init des routes pour chaque appel MVC hierarchique
        if (!self::$_passage) {
            if ($save) {
                self::$_routes = Pelican_Cache::fetch('StaticMethod', array('route', 'Pelican_Route', 'cache', $_SERVER['HTTP_HOST']));
            } else {
                self::$_routes = Pelican_Route::cache();
            }
            
            /**
             * @static
             * @access protected
             * @var __TYPE__ __DESC__
             */
            self::$_passage = true;
        }
        return self::$_routes;
    }
    
    /**
     * __DESC__
     *
     * @static
     * @access public
     * @return __TYPE__
     */
    public static function cache() {
        if (empty(self::$_routes['index'])) {
            self::set('index', '')->defaults(array('action' => 'index'));
        }
        if (empty(self::$_routes['absolute'])) {
            self::set('absolute', '_/<absolute>(/<name>(/<controller>(/<action>(.<format>)(/<param>))))', array('absolute' => 'site|module', 'param' => '.*'))->defaults(array('action' => 'index', 'format' => 'html'));
        }
        if (empty(self::$_routes['relative'])) {
            self::set('relative', '_/(<controller>(/<action>(.<format>)(/<param>)))', array('param' => '.*'))->defaults(array('action' => 'index', 'format' => 'html'));
        }
        if (empty(self::$_routes['external'])) {
            self::set('external', '_/<action>/<host>(/<param>)', array('action' => 'soap|rest|xmlrpc|get|post|put|ftp|http|https', 'host' => '[^\/]*', 'param' => '.*'))->defaults(array('root' => 'library', 'directory' => 'Pelican/Controller', 'controller' => 'External'));
        }
        if (empty(self::$_routes['clearurl'])) {
            self::set('clearurl', '(<path>/)(pid<pid>)(-|.|/)(cid<cid>)(-|.|/)<title>.<format>', array('path' => '.*', 'title' => '.*', 'pid' => '[0-9]+', 'cid' => '[0-9]+', 'format' => 'html|htm|pdf|txt|doc|xls|wiki|chtml|xhtmlmp|html5|apple'))->pushRequestParams(array('get' => array('cid', 'pid')));
        }
        
        /**
         * __DESC__
         *
         * @static
         * @access protected
         */
        return self::$_routes;
    }
    
    /**
     * Creates a new route. Sets the URI and regular expressions for keys.
     *
     * @access public
     * @param string $uri (option) route URI pattern
     * @param Array $regex (option) key patterns
     * @return void
     */
    public function __construct($uri = NULL, array $regex = NULL) {
        if ($uri === NULL) {
            // Assume the route is from cache
            return;
        }
        if (!empty($regex)) {
            $this->_regex = $regex;
        }
        // Store the URI that this route will match
        $this->_uri = $uri;
        // Store the compiled regex locally
        $this->_route_regex = $this->_compile();
    }
    
    /**
     * Provides default values for keys when they are not present. The default
     * action will always be "index" unless it is overloaded here.
     *
     * $route->defaults(array('controller' => 'welcome', 'action' => 'index'));
     *
     * @access public
     * @param Array $defaults (option) key values
     * @return Route
     */
    public function defaults(array $defaults = NULL) {
        $this->_defaults = $defaults;
        return $this;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $params (option) __DESC__
     * @return __TYPE__
     */
    public function pushRequestParams(array $params = NULL) {
        $this->_requestParams = $params;
        return $this;
    }
    
    /**
     * Tests if the route matches a given URI. A successful match will return
     * all of the routed parameters as an array. A failed match will return
     *
     * Boolean FALSE.
     *
     * // This route will only match if the <controller>, <action>, and <id> exist
     * $params = self::factory('<controller>/<action>/<id>', array('id' => '\d+'))
     * ->matches('users/edit/10');
     * // The parameters are now: controller = users, action = edit, id = 10
     *
     * This method should almost always be used within an if/else block:
     *
     * if ($params = $route->matches($uri))
     * {
     * // Parse the parameters
     * }
     *
     * @access public
     * @param string $uri URI to match
     * @return FALSE
     */
    public function matches($uri) {
        $ok = preg_match($this->_route_regex, $uri, $matches);
        //var_dump($matches);
        if (!$ok) {
            return FALSE;
        }
        $params = array();
        
        /** identification de la structure */
        $search = '/public';
        $documentRoot = '/' . trim($_SERVER['DOCUMENT_ROOT'], '/');
        $public = strpos($documentRoot, $search, 0);
        foreach($matches as $key => $value) {
            if (is_int($key)) {
                // Skip all unnamed keys
                continue;
            }
            
            /** gestion du directory : pour le cas 'absolute' */
            if ($key == 'absolute') {
                
                /** cas particulier de library */
                $params['addroot'] = 'application/' . $value . 's/' . $matches['name'];
            }
            
            /** gestion des sous-repertoires : uniquement si controller contient des _ */
            if ($key == 'controller' && substr_count($value, '_')) {
                $temp = explode('_', $value);
                $value = array_pop($temp);
                $params['directory'] = implode('/', $temp);
            }
            // Set the value for all matched keys
            $params[$key] = $value;
        }
        foreach($this->_defaults as $key => $value) {
            if (!isset($params[$key]) or $params[$key] === '') {
                // Set default values for any key that was not matched
                $params[$key] = $value;
            }
        }
        foreach($this->_requestParams as $type => $keys) {
            if (is_array($keys)) {
                foreach($keys as $key) {
                    if (!empty($params[$key])) {
                        switch ($type) {
                            case 'get': {
                                        if (empty($_GET[$key])) {
                                            $_GET[$key] = $params[$key];
                                        }
                                    break;
                                }
                            case 'post': {
                                    if (empty($_POST[$key])) {
                                        $_POST[$key] = $params[$key];
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            
            /** cas par defaut */
            if (!isset($params['root'])) {
                if ($public) {
                    $init = trim(substr($documentRoot, $public + strlen($search), strlen($documentRoot)), '/');
                } else {
                    
                    /** on est directement dans le document root */
                    $init = '';
                }
                $params['root'] = trim('/' . $init, '/');
            }
            if (isset($params['addroot']) && $params['root']) {
                $params['root'] = $params['addroot'] . '/' . $params['root'];
            }
            return $params;
        }
        
        /**
         * Generates a URI for the current route based on the parameters given.
         *
         * @access public
         * @param Array $params (option) URI parameters
         * @return string
         */
        public function uri(array $params = NULL) {
            if ($params === NULL) {
                // Use the default parameters
                $params = $this->_defaults;
            } else {
                // Add the default parameters
                $params+= $this->_defaults;
            }
            // Start with the routed URI
            $uri = $this->_uri;
            if (strpos($uri, '<') === FALSE and strpos($uri, '(') === FALSE) {
                // This is a static route, no need to replace anything
                return $uri;
            }
            while (preg_match('#\([^()]++\)#', $uri, $match)) {
                // Search for the matched value
                $search = $match[0];
                // Remove the parenthesis from the match as the replace
                $replace = substr($match[0], 1, -1);
                while (preg_match('#' . self::REGEX_KEY . '#', $replace, $match)) {
                    list($key, $param) = $match;
                    if (!empty($params[$param])) {
                        // Replace the key with the parameter value
                        $replace = str_replace($key, $params[$param], $replace);
                    } else {
                        // This group has missing parameters
                        $replace = '';
                        break;
                    }
                }
                // Replace the group in the URI
                $uri = str_replace($search, $replace, $uri);
            }
            while (preg_match('#' . self::REGEX_KEY . '#', $uri, $match)) {
                list($key, $param) = $match;
                if (empty($params[$param])) {
                    // Ungrouped parameters are required
                    throw new Pelican_Exception('Required route parameter not passed: :param', array(':param' => $param));
                }
                $uri = str_replace($key, $params[$param], $uri);
            }
            // Trim all extra slashes from the URI
            $uri = preg_replace('#//+#', '/', rtrim($uri, '/'));
            return $uri;
        }
        
        /**
         * Returns the compiled regular expression for the route. This translates
         * keys and optional groups to a proper PCRE regular expression.
         *
         * @access protected
         * @return string
         */
        protected function _compile() {
            // The URI should be considered literal except for keys and optional parts
            // Escape everything preg_quote would escape except for : ( ) < >
            $regex = preg_replace('#' . self::REGEX_ESCAPE . '#', '\\\\$0', $this->_uri);
            if (strpos($regex, '(') !== FALSE) {
                // Make optional parts of the URI non-capturing and optional
                $regex = str_replace(array('(', ')'), array('(?:', ')?'), $regex);
            }
            // Insert default regex for keys
            $regex = str_replace(array('<', '>'), array('(?P<', '>' . self::REGEX_SEGMENT . ')'), $regex);
            if (!empty($this->_regex)) {
                $search = $replace = array();
                foreach($this->_regex as $key => $value) {
                    $search[] = "<$key>" . self::REGEX_SEGMENT;
                    $replace[] = "<$key>$value";
                }
                // Replace the default regex with the user-specified regex
                $regex = str_replace($search, $replace, $regex);
            }
            return '#^' . $regex . '$#';
        }
    }
    