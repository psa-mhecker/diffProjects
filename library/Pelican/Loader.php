<?php
/**
 * Loader de Pelican
 *
 * @package Pelican
 * @subpackage Loader
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
define('LOADER_AUTOLOADER', true);
require_once "Zend/Loader/Autoloader.php";
$loader = Zend_Loader_Autoloader::getInstance();

/**
 * Loader de Pelican
 *
 * @package Pelican
 * @subpackage Loader
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @since 01/10/2009
 */
class Pelican_Loader
{

    /**
     * Charge et référence un fichier ou une classe
     *
     * @static
     * @access public
     * @staticvar Array $aPaths
     * @staticvar Array $aControl
     * @param string $item Entrée du fichier
     * @param string $app (option) Sous répertoire de lib et préfixe
     * @return array
     */
    static function import($item, $app = '')
    {
        static $aPaths, $aControl;
        if (empty($aControl[$item])) {
            if (!isset($aPaths)) {
                $aPaths = array();
            }
            $keyPath = self::getParams($item, $app);
            if (!isset($aPaths[$keyPath['path']])) {
                //if ($type == 'class') {
                $classes = self::register($item, $keyPath['class'], $keyPath['path']);
                $rs = isset($classes[$keyPath['class']]);
                //} else {
                //	$rs = include($keyPath['path']);
                //}
                $aPaths[$keyPath['path']] = $rs;
            }
            $aControl[$item] = true;
            return $aPaths[$keyPath['path']];
        }
    }

    /**
     * Ajoute une classe à l'autoload
     *
     * @access public
     * @staticvar Array $aClasses
     * @param string $item (option) Entrée du fichier
     * @param string $classname (option) Nom de classe
     * @param string $path (option) Chemin du fichier à inclure
     * @return array
     */
    public static function register($item = null, $classname = null, $path = null)
    {
        static $aClasses;
        if (!isset($aClasses)) {
            $aClasses = array();
        }
        if ($classname && is_file($path) && !substr_count($classname, '.php')) {
            if (!isset($aClasses[$classname]) && !class_exists($classname) && !LOADER_AUTOLOADER) {
                include($path);
            }
            $aClasses[$classname] = $path;
            self::files($item, $classname);
        }
        return $aClasses;
    }

    /**
     * Ajoute une classe à l'autoload
     *
     * @static
     * @access public
     * @staticvar Array $aFiles Array
     * @param string $item (option) Entrée du fichier
     * @param string $classname (option) Nom de classe
     * @return array
     */
    static function files($item = null, $classname = null)
    {
        static $aFiles;
        if (!isset($aFiles)) {
            $aFiles = array();
        }
        if ($item && $classname) {
            $aFiles[$item] = $classname;
        } elseif ($item) {
            // permet de retourner le chemin d'une entrée
            return $aFiles[$item];
        }
        return $aFiles;
    }

    /**
     * Charge le fichier d'une classe
     *
     * @access public
     * @param string $class Nom de classe
     * @return bool
     */
    public static function load($class)
    {
        if (class_exists($class)) {
            return;
        }
        $classes = self::register();
        if (array_key_exists($class, $classes)) {
            include($classes[$class]);
            return true;
        }
        return false;
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @staticvar xx $follow Array
     * @param string $item Entrée du fichier
     * @param string $app (option) Sous répertoire de lib et préfixe
     * @return array
     */
    static function getParams($item, $app = '')
    {
        global $_LOADER;
        static $follow;
        $item = str_replace('/', '.', $item);
        $item = str_replace('_', '.', $item);
        if (!isset($follow[$item])) {
            $follow[$item] = 0;
        }
        $follow[$item]++;
        if (isset($_LOADER[$item])) {
            $path = $_LOADER[$item][0];
            if (isset($_LOADER[$item][1])) {
                $class = $_LOADER[$item][1];
            }
        } else {
            /* par défaut */
            if (!$app) {
                $app = 'Pelican';
            }

            /** cas du séparateur '.' */
            $item = str_replace('.', '/', $item);
            $path = Pelican::$config['LIB_ROOT'] . '/' . $app . '/' . $item . '.php';
            $class = $app . '_' . str_replace('/', '_', $item);
        }
        //        echo 'xxx'.$path.'xxxr';
        return array('path' => $path, 'class' => $class); //, 'call' => $follow[$item]);

    }
}

/**
 * Classe autoload
 *
 * @param string $class Nom de classe
 * @return bool
 */
function pelican_autoload($class)
{
    if (Pelican_Loader::load($class)) {
        return true;
    }
    return false;
}

spl_autoload_register('pelican_autoload');

/**
 * Importeur de fichier
 *
 * @access public
 * @param string $item Entrée du fichier
 * @param string $app (option) Sous répertoire de lib et préfixe
 * @return array
 */
function pelican_import($item, $app = '')
{
    return Pelican_Loader::import($item, $app);
}

/**
 * Génère le chemin physique associée au paramètre $item
 *
 * @param string $item Entrée du fichier
 * @param string $app (option) Sous répertoire de lib et préfixe
 * @return string
 */
function pelican_path($item, $app = '')
{
    Pelican_Loader::import($item, $app);
    $return = Pelican_Loader::getParams($item, $app);
    return $return['path'];
}

/**
 * Génère le nom de classe associée au paramètre $item
 *
 * @param string $item Entrée du fichier
 * @param string $app (option) Sous répertoire de lib et préfixe
 * @return string
 */
function pelican_classname($item, $app = '')
{
    Pelican_Loader::import($item, $app);
    $return = Pelican_Loader::getParams($item, $app);
    return $return['class'];
}

?>