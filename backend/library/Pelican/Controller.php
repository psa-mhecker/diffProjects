<?php

/**
 * Contrôleur central, prenant en compte l'approche HMVC : le contrôleur est
 * instancié par un objet de Requête.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Crée une nouvelle instance de contrôleur.
 * Chaque contrôleur doit être
 * construit avec l'objet de requête (Request.php) qui l'a créé.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Marc Malmaison <marc.malmaison@businessdecision.com>
 */
class Pelican_Controller
{
    /**
     * __DESC__.
     *
     * @access public
     *
     * @param mixed $key
     *                        __DESC__
     * @param mixed $value
     *                        __DESC__
     *
     * @return mixed
     */
    public function __set($key, $value)
    {
        var_dump('A declarer en variable de classe');
        var_dump($key);
        var_dump(debug_backtrace());
        die();
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @var mixed
     */
    protected $_controller;

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @var mixed
     */
    protected $_controllerPath;

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @var mixed
     */
    protected $_action;

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @var mixed
     */
    protected $_format;

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @var mixed
     */
    protected $_template;

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @var mixed
     */
    protected static $_frontController;

    /**
     * Array('format' => array('basename')
     * exemple : array('iphone',array('index')) pour index.iphone.
     *
     * @access protected
     *
     * @var mixed
     */
    protected $_alternativeTemplate = array();

    /**
     * __DESC__.
     *
     * @access protected
     */
    protected $_adaptiveDriver = array();

    /**
     * Objet Requête qui a créé le contrôleur.
     *
     * @access protected
     *
     * @var object
     */
    protected $_request;

    /**
     * Contrôleur par défaut.
     *
     * @static
     *
     * @access public
     *
     * @var string
     */
    protected static $_defaultController = 'index';

    /**
     * Action par défaut.
     *
     * @static
     *
     * @access public
     *
     * @var string
     */
    protected static $_defaultAction = 'index';


    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Pimple
     */
    protected $pimple;
    /**
     * __DESC__.
     *
     * @access public
     *
     * @return mixed
     */
    public function getParams()
    {
        return $this->getRequest()->getParams();
    }

    /**
     * __DESC__.
     *
     * @access public
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function getParam($key, $default = null)
    {
        return $this->getRequest()->getParam($key, $default);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param mixed $key
     *                        __DESC__
     * @param mixed $value
     *                        __DESC__
     *
     * @return mixed
     */
    public function setParam($key, $value)
    {
        return $this->getRequest()->setParam($key, $value);
    }

    /**
     * Crée une nouvelle instance de contrôleur.
     * Chaque contrôleur doit être
     * construit avec l'objet de requête (Request.php) qui l'a créé.
     *
     * @access public
     *
     * @param Pelican_Request $request
     *                                 Objet Requête qui crée le contrôleur
     */

    /**
     * Contrôleur par défaut.
     *
     * @static
     *
     * @access public
     *
     * @var string
     *
     * @return mixed
     */
    public static function getDefaultController()
    {
        return Pelican_Controller::$_defaultController;
    }

    /**
     * __DESC__.
     *
     * @static
     *
     * @access public
     *
     * @param string $_defaultController
     *                                   __DESC__
     *
     * @return mixed
     */
    public static function setDefaultController($_defaultController)
    {
        Pelican_Controller::$_defaultController = $_defaultController;
    }

    /**
     * Action par défaut.
     *
     * @static
     *
     * @access public
     *
     * @var string
     *
     * @return mixed
     */
    public static function getDefaultAction()
    {
        return Pelican_Controller::$_defaultAction;
    }

    /**
     * __DESC__.
     *
     * @static
     *
     * @access public
     *
     * @param string $_defaultAction
     *                               __DESC__
     *
     * @return mixed
     */
    public static function setDefaultAction($_defaultAction)
    {
        Pelican_Controller::$_defaultAction = $_defaultAction;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param mixed $request
     *                          __DESC__
     *
     * @return mixed
     */
    public function __construct(Pelican_Request $request)
    {
        // Assignation de l'objet de Requête par principe
        $this->setRequest($request);
        $this->container = $request->getContainer();
        $this->pimple = $request->getPimple();
        $this->_controller = $request->controller;
        $this->_controllerPath = $request->controllerPath;
        $this->_action = $request->action;
        $this->_format = $request->format;
        $this->setTemplate(self::getTemplatePath($this->_controllerPath, $this->_action));
        $this->setFrontController();
        $this->init();
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @return mixed
     */
    protected function init()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return mixed
     */
    public function setFrontController()
    {
        if (empty(self::$_frontController)) {
            self::$_frontController = $this;
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return mixed
     */
    public function getFrontController()
    {
        if (! empty(self::$_frontController)) {
            return self::$_frontController;
        }
    }

    /**
     * Setter de la propriété request.
     *
     * @access public
     *
     * @param Pelican_Request $request
     *                        __DESC__
     */
    public function setRequest(Pelican_Request $request)
    {
        $this->_request = $request;
    }

    /**
     * Getter de la propriété request.
     *
     * @access public
     *
     * @return Pelican_Request
     */
    public function getRequest()
    {
        return $this->_request;
    }


    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return Pimple
     */
    public function getPimple()
    {
        return $this->pimple;
    }

    /**
     * @param Pimple $pimple
     */
    public function setPimple($pimple)
    {
        $this->pimple = $pimple;
    }



    /**
     * Automatiquement lancé avant toute action.
     *
     * @access public
     */
    public function before()
    {
    }

    /**
     * Automatiquement lancé après toute action.
     *
     * @access public
     */
    public function after()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param mixed $code
     *                       __DESC__
     * @param mixed $uri
     *                       __DESC__
     *
     * @return mixed
     */
    public function sendError($code, $uri)
    {
        $this->getRequest()->sendError($code, $uri);
    }

    /**
     * Récupération de l'objet Vue.
     *
     * @access public
     *
     * @return Pelican_View
     */
    public function &getView()
    {
        if (! isset($this->view)) {
            $this->view = & Pelican_Factory::getInstance('View');
        }

        return $this->view;
    }

    /**
     * Assignation d'un message d'erreur à une variable de vue $error.
     *
     * @access protected
     */
    protected function error()
    {
        if ($this->isOnError()) {
            $this->assign('error', $this->exception);
        }
    }

    /**
     * Assignation de variable de vue.
     *
     * @access public
     *
     * @param string $name
     *                         String
     * @param string $value
     *                         (option) __DESC__
     * @param bool   $doEscape
     *                         (option) __DESC__
     */
    public function assign($name, $value = null, $doEscape = true)
    {
        $this->getView()->assign($name, $value, $doEscape);
    }

    /**
     * Génération de la vue et affectation à la propriété $response de l'objet
     * Requête+ :.
     *
     * - soit avec le paramètre $template passé à la méthode
     * - soit avec la propriété de classe $template définie auparavant ou par
     * défaut
     *
     * @access public
     *
     * @param string $template
     *                         (option) Chemin physique de la template de vue
     */
    public function fetch($template = '')
    {
        if (! empty($template)) {
            $this->setTemplate($template);
        }
        $this->beforeFetch();
        $this->setResponse(@$this->getView()
            ->fetch($this->getTemplate()));
        $this->afterFetch();
    }

    /**
     * Alias de fetch.
     *
     * @access public
     *
     * @param string $template
     *                         (option) __DESC__
     *
     * @return mixed
     */
    public function render($template = '')
    {
        $this->fetch($template);
    }

    /**
     * Interception de la vue en fonction de la propriete $this->format
     * Prend le dessus sur une eventuel post processing dans afterRender.
     *
     * @access public
     *
     * @return mixed
     */
    public function beforeFetch()
    {
        $template = $this->getTemplate();
        $baseName = str_replace('.tpl', '', basename($template));
        if (! empty($this->_alternativeTemplate)) {
            if (isset($this->_alternativeTemplate[$this->_format]) && $this->_format != 'html') {
                if (in_array($baseName, $this->_alternativeTemplate[$this->_format])) {
                    $template = str_replace($baseName.'.tpl', $baseName.'.'.$this->_format, $template);

                    /*
                     * Prend le dessus sur une eventuel post processing dans afterRender
                     */
                    if (isset($adaptiveDriver[$this->_format])) {
                        unset($adaptiveDriver[$this->_format]);
                    }
                }
            }
        }
        $this->setTemplate($template);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return mixed
     */
    public function afterFetch()
    {
        if (! empty($this->_adaptiveDriver)) {
            if (isset($this->_adaptiveDriver[$this->_format]) && $this->_format != 'html') {
                pelican_import('Response.Adapter.Factory');
                $adapter = Pelican_Factory::getInstance('Response.Adapter.Factory', $this->_adaptiveDriver[$this->_format]);
                $response = (string) $this->getResponse();
                $this->setResponse($adapter->process($response));
            }
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param mixed $response
     *                           __DESC__
     *
     * @return mixed
     */
    public function setResponse($response)
    {
        $this->getRequest()->setResponse($response);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param mixed $cmd
     *                         __DESC__
     * @param mixed $params
     *                         (option) __DESC__
     *
     * @return mixed
     */
    public function addResponseCommand($cmd, $params = array())
    {
        $this->getRequest()->addResponseCommand($cmd, $params);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->getRequest()->getResponse();
    }

    /**
     * Setter de la propriété $template.
     *
     * @access public
     *
     * @param string $template
     *                         Chemin physique de la template de vue
     */
    public function setTemplate($template)
    {
        $this->_template = $template;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param mixed $format
     *                         __DESC__
     *
     * @return mixed
     */
    public function formatSwitch($format)
    {
    }

    /**
     * Génération de la syntaxe d'une méthode d'action.
     *
     * @static
     *
     * @access public
     *
     * @param string $action
     *                       Action
     *
     * @return string
     */
    public static function getActionName($action)
    {
        return $action.'Action';
    }

    /**
     * Génération du chemin physique associé au nom de contrôleur passé en
     * paramètre.
     *
     * @static
     *
     * @access public
     *
     * @param string $controller
     *                           Nom du contrôleur
     * @param string $directory
     *                           (option) Sous répertoire de stockage
     * @param string $root
     *                           (option) __DESC__
     *
     * @return string
     */
    public static function getControllerPath($controller, $directory = '', $root = '')
    {
        $return = $controller;
        $controller_dir = 'controllers/';
        if (! empty(Pelican::$config['APPLICATION_CONTROLLERS'])) {
            $controller_dir = Pelican::$config['APPLICATION_CONTROLLERS'];
        }
        $controller_dir = str_replace('//', '/', '/'.trim($controller_dir.($directory ? '/'.$directory : ''), '/').'/');
        if ($root == 'library') {
            $init = str_replace('.php', '', __FILE__);
            $return = $init.'/'.self::formatName($controller).'.php';
        } elseif (strpos('/'.$root, Pelican::$config["PLUGIN_PATH"]) !== false) {
            $temp = explode('/', $root);
            $name = array_pop($temp);
            $root = implode('/', $temp).'/'.($name == 'backend' ? 'backend' : 'frontend');
            $return = Pelican::$config["DOCUMENT_INIT"].'/'.$root.'/controllers/'.$directory.'/'.$controller.'.php';
        } else {
            $return = $controller_dir.self::formatName($controller, $directory).'.php';
        }

        return $return;
    }

    /**
     * Génération de la syntaxe d'un contrôleur.
     *
     * @static
     *
     * @access public
     *
     * @param string $controller
     *                           Nom du contrôleur
     * @param string $directory
     *                           (option) Sous répertoire de stockage
     *
     * @return string
     */
    public static function getControllerName($controller, $directory = '')
    {
        $cont = str_replace('/', '_', $controller);
        $path = '';
        if (! empty($directory)) {
            $path = str_replace(' ', '_', self::formatName(str_replace(array(
                '\\',
                '/',
            ), ' ', trim($directory, '/')))).'_';
        }

        /*
         * cas particulier
         */
        if ($directory == 'Pelican/Controller') {
            $return = $path.self::formatName($cont);
        } else {
            $return = $path.self::formatName($cont).'_Controller';
        }

        return str_replace('/', '_', $return);
    }

    /**
     * Génération du chemin physique d'une vue associée à un contrôleur.
     *
     * @static
     *
     * @access public
     *
     * @param string $path
     *                       Chemin physique du contrôleur associé
     * @param string $action
     *                       (option) __DESC__
     *
     * @return string
     */
    public static function getTemplatePath($path, $action = '')
    {
        $actionName = ($action ? $action : self::$_defaultAction);

        $extension = '.tpl';
        if (self::isMobile()) {
            $extension = '.mobi';
        }

        $return = self::buildTemplatePath($path, $actionName, $extension);

        if (self::isMobile()) {
            if (!file_exists($return)) {
                $extension = '.tpl';
                $return = self::buildTemplatePath($path, $actionName, $extension);
            }
        }

        return $return;
    }

    public static function buildTemplatePath($path, $actionName, $extension)
    {
        return str_replace(array(
            'controllers',
            '.php',
        ), array(
            'views/scripts',
            '/'.$actionName.$extension,
        ), $path);
    }

    /**
     * Vérifie si le user agent est de type mobile.
     *
     * @static
     *
     * @access public
     *
     * @return bool
     */
    public static function isMobile()
    {
        if (Pelican_Request::$multidevice) {
            require_once 'Pelican/Http/UserAgent/Mobile.php';

            if ($size = self::getScreenSize()) {
                $return = ($size < 7) ? true : false;
            } else {
                $return = Pelican_Request::$userAgentFeatures['device']->device->getFeature('is_mobile');

                if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile')) {
                    $return = true;
                }
                if (Pelican_Request::$userAgentFeatures['device']->device->getFeature('is_tablet')) {
                    $return = false;
                }
            }

            return $return;
        } else {
            return false;
        }
    }

    /**
     * Obtenir la taille d'un ecran en pouce.
     *
     * @static
     *
     * @access public
     *
     * @return float diagonale de l'ecran en pouce ou false
     */
    public static function getScreenSize()
    {
        if (Pelican_Request::$multidevice) {
            $width = Pelican_Request::$userAgentFeatures['device']->device->getFeature('physical_screen_width');
            $height = Pelican_Request::$userAgentFeatures['device']->device->getFeature('physical_screen_height');

            if ($width != '' && $height != '') {
                return (
                        sqrt(
                            bcpow($width, 2) + bcpow($height, 2)
                        )
                    ) / 25.4;
            }
        }

        return false;
    }

    /**
     * Chemin physique de stockage initial des templates de vue.
     *
     * @static
     *
     * @access public
     *
     * @return string
     */
    public static function getTemplateRoot()
    {
        $return = '/'.trim($_SERVER['DOCUMENT_ROOT'], '/').'/views/scripts/';

        return $return;
    }

    /**
     * Replacement of the template path.
     *
     * @access public
     *
     * @param mixed $origin
     *                         __DESC__
     * @param mixed $target
     *                         __DESC__
     *
     * @return mixed
     */
    public function replaceTemplate($origin, $target)
    {
        $this->setTemplate(str_replace($origin.'.tpl', $target.'.tpl', $this->getTemplate()));
    }

    /**
     * Formattage des noms de contrôleur et action : première lettre en majuscule,
     * le reste en minuscule.
     *
     * @static
     *
     * @access public
     *
     * @param string $name
     *                     Nom à formatter
     *
     * @return string
     */
    public static function formatName($name)
    {
        $return = str_replace(' ', '/', ucwords(str_replace('/', ' ', $name)));
        // $return = str_replace(' ', '/', ucwords(str_replace('/', ' ', strtolower($name))));
        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param mixed $url
     *                      __DESC__
     *
     * @return mixed
     */
    public function redirect($url)
    {
        $this->getRequest()->redirect($url);
    }

    /**
     * __DESC__.
     *
     * @access protected
     *
     * @param mixed $action
     *                         __DESC__
     *
     * @return mixed
     */
    protected function _forward($action)
    {
        $this->setTemplate(self::getTemplatePath($this->_controllerPath, $action));
        call_user_func(array(
            $this,
            Pelican_Controller::getActionName($action),
        ));
        $this->setAction('');
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param mixed $action
     *                         __DESC__
     *
     * @return mixed
     */
    public function setAction($action)
    {
        $this->_action = '';
        $this->getRequest()->setAction($action);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param mixed $format
     *                         __DESC__
     *
     * @return mixed
     */
    public function setFormat($format)
    {
        $this->_format = '';
        $this->getRequest()->setFormat($format);
    }
}
