<?php
/**
 * Classe de gestion des vues de Pelican
 *
 * @package Pelican
 * @subpackage View
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @since 13/01/2006
 * @link http://www.interakting.com
 */

/** SMARTY */
require_once (pelican_path('External.Smarty'));

/**
 * Classe de gestion des vues de Pelican
 *
 * @package Pelican
 * @subpackage View
 * @author Raphaël Carles <rcarles@businessdecision.fr>
 */
class Pelican_View extends Smarty {
    
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $default;
    
    /**
     * Singleton instance
     *
     * @var Pelican_View
     */
    protected static $_instance = null;
    
    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $_head;
    
    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $_escape;
    
    /**
     * __DESC__
     *
     * @access private
     * @var __TYPE__
     */
    public $_current_file;
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $tpl_var __DESC__
     * @param string $value (option) __DESC__
     * @param bool $doEscape (option) __DESC__
     * @return __TYPE__
     */
    public function assign($tpl_var, $value = null, $doEscape = true) {
        if ($value && !is_array($value) && $doEscape && is_string($value)) {
            parent::assign($tpl_var, $this->escape($value, $tpl_var));
        } else {
            parent::assign($tpl_var, $value);
        }
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $var __DESC__
     * @param __TYPE__ $tpl_var __DESC__
     * @return __TYPE__
     */
    public function escape($var, $tpl_var) {
        if (empty($this->_escape)) {
            //XSS basic cleanup
            $return = Pelican_Security::escapeXSS($var);
            if ($return != $var) {
                echo ('Attention, javascript dans la variable "' . $tpl_var . '" : <br />' . htmlentities($var));
                var_dump('Desactiver la protection dans l\'appel à "assign" en mettant "false" en troisieme parametre');
                die();
            }
            return $return;
        } else {
            if (in_array($this->_escape, array('htmlspecialchars', 'htmlentities'))) {
                return call_user_func($this->_escape, $var, ENT_COMPAT, $this->_encoding);
            }
            if (1 == func_num_args()) {
                return call_user_func($this->_escape, $var);
            }
            $args = func_get_args();
            return call_user_func_array($this->_escape, $args);
        }
    }
    
    /**
     * Sets the _escape() callback.
     *
     * @access public
     * @param mixed $spec The callback for _escape() to use.
     * @return Zend_View_Abstract
     */
    public function setEscape($spec) {
        $this->_escape = $spec;
        return $this;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param field_type $head __DESC__
     * @return __TYPE__
     */
    public function setHead($head) {
        $this->_head = $head;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function __construct() {
        $this->_config['caching'] = false;
        $this->_config['compile_check'] = true;
        $this->_config['debugging'] = false;
        $this->_config['auto_literal'] = false;
        $this->_config['cache_lifetime'] = 0;
        $this->_config['template_dir'] = '';
        $this->_config['compile_dir'] = Pelican::$config["VAR_VIEW_COMPILES_ROOT"];
        $this->_config['config_dir'] = '';
        $this->_config['cache_dir'] = Pelican::$config["VAR_CACHE_VIEWS"];
        $this->_config['left_delimiter'] = '{';
        $this->_config['right_delimiter'] = '}';
        parent::__construct();
        //$this->compile_check = (Pelican::$config["TYPE_ENVIRONNEMENT"]?true:false);
        $this->caching = $this->_config['caching'];
        $this->cache_lifetime = $this->_config['cache_lifetime'];
        $this->template_dir = $this->_config['template_dir'];
        $this->compile_dir = $this->_config['compile_dir'];
        $this->config_dir = $this->_config['config_dir'];
        $this->cache_dir = $this->_config['cache_dir'];
        $this->left_delimiter = $this->_config['left_delimiter'];
        $this->right_delimiter = $this->_config['right_delimiter'];
        $this->auto_literal = $this->_config['auto_literal'];
        $this->compile_check = $this->_config['compile_check'];
        $this->debugging = $this->_config['debugging'];
        $this->plugins_dir[] = Pelican::$config['LIB_ROOT'] . '/Pelican/View/plugins';
        $this->use_sub_dirs = true;
        $this->force_compile = false;
        $this->config_fix_newlines = true; //?
        
        
        /** config */
        //$this->assign("pelican_config", &Pelican::$config);
        $reducedConst['CNT_EMPTY'] = Pelican::$config['CNT_EMPTY'];
        $reducedConst['DESIGN_HTTP'] = Pelican::$config['DESIGN_HTTP'];
        $reducedConst['IMAGE_FRONT_HTTP'] = Pelican::$config['IMAGE_FRONT_HTTP'];
        $reducedConst['MEDIA_HTTP'] = Pelican::$config['MEDIA_HTTP'];
        $reducedConst['VIEWS_ROOT'] = Pelican::$config['VIEWS_ROOT'];
        if (isset(Pelican::$config['SKIN_HTTP'])) {
           $reducedConst['SKIN_HTTP'] = Pelican::$config['SKIN_HTTP'];
        }

        if (isset(Pelican::$config['VIEW_PARAMS'])) {
            foreach(Pelican::$config['VIEW_PARAMS'] as $key) {
                if (isset(Pelican::$config[$key])) {
                    $reducedConst[$key] = Pelican::$config[$key];
                }
            }
        }

        $this->assign("pelican_config", $reducedConst);
        //var_dump($reducedConst);
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @return __TYPE__
     */
    public function getHead() {
        if (!$this->_head) {
            pelican_import('Index');
            $this->_head = Pelican_Factory::getInstance('Index');
        }
        return $this->_head;
    }
    
    /**
     * Singleton pattern implementation
     *
     * @static
     * @access public
     * @return Pelican_View
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    /**
     * Génération de l'id de cache de la vue
     *
     * @static
     * @access public
     * @param string $idcache (option) Id de Cache
     * @param __TYPE__ $addon (option) __DESC__
     * @return string
     */
    static function getCacheId($idcache = "", $addon = array()) {
        $return = $idcache;
        if (is_array($return)) {
            ksort($return);
            $return = implode("|", $return);
        }
        $return.= "|" . implode('|', $addon) . "|" . ($_GET ? serialize($_GET) : "");
        $return = str_replace(array("||", "{", "}", ";", ":", "\""), array("|", "", "", "", "", ""), $return);
        return $return;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param __TYPE__ $template __DESC__
     * @param string $cache_id (option) __DESC__
     * @param string $compile_id (option) __DESC__
     * @param string $parent (option) __DESC__
     * @return __TYPE__
     */
    public function is_cached($template, $cache_id = null, $compile_id = null, $parent = null) {
        return parent::isCached($template, $cache_id, $compile_id, $parent);
    }
}

/**
 * Fonction générique d'include d'un bloc utilisant un template Smarty (gestion
 * du cache ou non et des paramètres variants ou invariants permettant
 *
 * D'identifier le cache)
 *
 * @param string $fichier Chemin physique du script à inclure et à cacher si
 * nécessaire
 * @param string $activeCache (option) Mise en cache ou non
 * @param mixed $idcache (option) Paramètres du cache
 * @param string $data (option) Valeurs des champs associés à la zone
 * @param int $lifetime (option) Durée de vie du cache
 * @param bool $directOutput (option) affiche sur l'écran direct ou non
 * @return void
 */
function templateSmarty($fichier, $activeCache = false, $idcache = "", $data = "", $lifetime = 30, $directOutput = true) {
    
    /** récuperation des objets smarty et connection */
    $indice = $fichier;
    Pelican::$trace['time'][$indice] = getmicrotime();
    Pelican_Profiler::start($indice, 'bloc');
    $cacheUsed = true;
    $view = Pelican_Factory::getView();
    
    /**
     * parametrage de smarty pour le bloc
     * $caching true or false
     * $cache_lifetime durée en seconde du cache
     * -1 = Illimitée
     */
    $view->caching = ($activeCache ? 2 : 0);
    $view->cache_lifetime = $lifetime;
    
    /** recuperation du nom du template en fonction du nom du php */
    
    /** ajout plug in */
    if ($data["PLUGIN_ID"]) {
        $fichier = str_replace("//", "/", str_replace("/layout", "", $fichier));
        $template = Pelican::$config["PLUGIN_ROOT"] . "/" . str_replace(".php", ".tpl", $fichier);
        $fichier = Pelican::$config["PLUGIN_ROOT"] . "/" . $fichier;
    } elseif ($data["FORCED_ROOT"]) {
        $template = $data["FORCED_ROOT"] . "/views/scripts/" . str_replace(".php", ".tpl", $fichier);
        $fichier = $data["FORCED_ROOT"] . "/controllers/" . $fichier;
    } else {
        $template = Pelican::$config["VIEWS_ROOT"] . "/" . strtok($fichier, ".") . ".tpl";
        $fichier = Pelican::$config["CONTROLLERS_ROOT"] . "/" . $fichier;
    }
    
    /** traitement des param des application/caches // creation des groupes de cache
     * structure du tableau
     */
    $idcache = Pelican_View::getCacheId($idcache, array($_SESSION[APP]['LANGUE_ID']));
    if (Pelican::$config["SHOW_DEBUG"] && $_GET["template"]) {
        echo (Pelican_Html::pre(str_replace(array(Pelican::$config["CONTROLLERS_ROOT"], '//', '.php'), '', $fichier)));
    }
    
    /** appel du fichier php si le templates est pas en cache // avec test ou pas sur l'idcache */
    if ($idcache) {
        $idcache = md5($idcache);
        if (!$view->is_cached($template, $idcache)) {
            $cacheUsed = false;
            if (file_exists($fichier)) {
                include ($fichier);
            }
        }
    } else {
        if (!$view->is_cached($template)) {
            $cacheUsed = false;
            include ($fichier);
        }
    }
    
    /** renvois du contenus de la template et creation du fichier cache si smarty est a caching=true */
    if ($idcache) {
        $output = $view->display($template, $idcache);
    } else {
        $output = $view->display($template);
    }
    if ($directOutput) {
        echo $output;
    } else {
        return $output;
    }
    
    /** remise a false du cache pour le bloc suivant */
    $view->caching = false;
    Pelican::$trace['time'][$indice] = sprintf("%.4f", (getmicrotime() - Pelican::$trace['time'][$indice]));
    Pelican_Profiler::stop($indice, 'bloc');
    if ($activeCache) {
        if ($cacheUsed) {
            $msg = '[cache de Vue ' . $lifetime . ' sec. : OK]';
        } else {
            $msg = '[sans cache de Vue]';
        }
        Pelican_Profiler::rename($indice, '&nbsp;&nbsp;' . $msg . '&nbsp;&nbsp;' . $indice, 'bloc');
    }
}
?>