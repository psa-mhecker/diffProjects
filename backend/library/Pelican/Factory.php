<?php
/**
 * Classe Factory de Pelican.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 * @since 25/09/2009
 */

/**
 * Classe Factory de Pelican.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Factory
{
    /**
     * Chargement d'une instance de classe.
     *
     * Exemple : /library/XXX/YYY.php
     * le nom de classe est YYY
     * le nom d'application est XXX
     *
     * exemple : /library/XXX/V2/YYY.php
     * le nom de classe est YYY
     * le nom d'application est XXX/V2
     *
     * exemple : /library/XXX/V2/V2.1/YYY.php
     * le nom de classe est YYY
     * le nom d'application est XXX/V2/V2.1
     *
     * @static
     * @access public
     * @staticvar array $aInstance
     *
     * @param string $class __DESC__
     *
     * @return mixed
     */
    public static function &getInstance($class)
    {
        static $aInstance;
        $args = func_get_args();
        array_shift($args);
        if (!isset($aInstance[$class])) {
            //if (!is_object($aInstance[$class])) {
            pelican_import($class);
            $class = Pelican_Loader::files($class);
            $reflectionObj = new ReflectionClass($class);

            /*
             * si la classe possède une méthode getInstance => c'est un singleton : on l'utilise
             *
             * nomenclature : 'getInstance' pour un singleton
             */
            if (($reflectionObj->hasMethod('getInstance'))) {
                $instance = call_user_func_array(array($class, 'getInstance'), $args);
                $aInstance[$class] = $instance;
            } else {
                // un constructeur existe
                $instance = $reflectionObj->getConstructor() ? $reflectionObj->newInstanceArgs($args) : $reflectionObj->newInstance();
            }

            return $instance;
        } else {
            return $aInstance[$class];
        }
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param __TYPE__ $class __DESC__
     *
     * @return __TYPE__
     */
    public static function &newInstance($class)
    {
        $args = func_get_args();
        array_shift($args);

        /* les arguments doivent être dans un tableau */
        if (count($args) > 1 && !is_array($args[0])) {
            $args = array($args);
        }
        pelican_import($class);
        $class = Pelican_Loader::files($class);
        if ($args) {
            $instance = new $class($args);
        } else {
            $instance = new $class();
        }

        return $instance;
    }

    /**
     *
     *
     * @param string $class
     * @param string $method
     *
     * @return mixed
     * @throws ErrorException
     */
    public static function staticCall($class, $method)
    {
        $args = func_get_args();
        array_shift($args);
        array_shift($args);
        $class = Pelican_Loader::files($class);
        if ($class) {
            if (method_exists($class, $method)) {
                return call_user_func_array(array($class, $method), $args);
            } else {
                throw new ErrorException('Méthode inexistante dans la classe '.$class);
            }
        } else {
            throw new ErrorException('Classe inexistante');
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     * @staticvar xx $instance
     *
     * @param string   $file (option) __DESC__
     * @param __TYPE__ $type (option) __DESC__
     *
     * @return __TYPE__
     */
    public static function getConfig($file = null, $type = 'PHP')
    {
        static $instance;
        if (!is_object($instance)) {
            if ($file === null) {
                $file = dirname(__FILE__).DS.'config.php';
            }
            $instance = self::_createConfig($file, $type);
        }

        return $instance;
    }

    /**
     * __DESC__.
     *
     * @access public
     * @staticvar xx $instance
     *
     * @param __TYPE__ $options (option) __DESC__
     *
     * @return __TYPE__
     */
    public static function getSession($options = array())
    {
        static $instance;
        if (!is_object($instance)) {
            $instance = Pelican_Factory::_createSession($options);
        }

        return $instance;
    }

    /**
     * __DESC__.
     *
     * @access public
     * @staticvar xx $instance
     *
     * @return __TYPE__
     */
    public static function getLanguage()
    {
        static $instance;
        if (!is_object($instance)) {
        }

        return $instance;
    }

    /**
     * Factory de la classe User.
     *
     * @access public
     *
     * @param string $type (option) __DESC__
     *
     * @return object
     */
    public static function getUser($type = "")
    {
        $class = 'User';
        if ($type) {
            $class .= '.'.ucfirst($type);
        }

        return call_user_func(array(pelican_classname($class), 'getInstance'));
    }

    /**
     * Factory de la classe Pelican_Cache.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public static function getCache()
    {
    }

    /**
     * __DESC__.
     *
     * @access public
     * @staticvar xx $instance
     *
     * @return __TYPE__
     */
    public static function getACL()
    {
        static $instance;
        if (!is_object($instance)) {
        }

        return $instance;
    }

    /**
     * Factory de la classe Smarty.
     *
     * @access public
     *
     * @return Instance
     */
    public static function getView()
    {
        $return = Pelican_Factory::getInstance('View');

        return $return;
    }

    /**
     * Factory de la classe de BDD.
     *
     * @access public
     * @staticvar xx $instance
     *
     * @param string $app (option) __DESC__
     *
     * @return __TYPE__
     */
    public static function getConnection($app = "")
    {
        $return = Pelican_Factory::getInstance('Db', $app);

        return $return;
    }
}


/**
 * Factory de la classe Smarty (obsolete).
 *
 * @deprecated __DESC__
 *
 * @return __TYPE__
 */
function getSmarty()
{
    return Pelican_Factory::getView();
}

/**
 * __DESC__.
 *
 * @deprecated __DESC__
 *
 * @param string $strValue __DESC__
 * @param string $strSep (option) __DESC__
 *
 * @return __TYPE__
 */
function splitTextarea($strValue, $strSep = "\r\n")
{
    include_once pelican_path('Form');

    return Pelican_Factory::staticCall('Form', 'splitTextarea', $strValue, $strSep);
}

/**
 * __DESC__.
 *
 * @deprecated __DESC__
 *
 * @param __TYPE__ $oConnection __DESC__
 * @param string $strName __DESC__
 * @param __TYPE__ $iID __DESC__
 * @param string $strQueryColumn __DESC__
 * @param string $strQueryRow __DESC__
 * @param string $strAbsFieldName __DESC__
 * @param string $strOrdFieldName __DESC__
 * @param string $strTableName __DESC__
 * @param string $strIDFieldName __DESC__
 *
 * @return __TYPE__
 */
function recordTabCroiseGenerique($oConnection, $strName, $iID = "", $strQueryColumn, $strQueryRow, $strAbsFieldName, $strOrdFieldName, $strTableName, $strIDFieldName)
{
    include_once pelican_path('Form');

    return Pelican_Factory::staticCall('Form', 'recordTabCroiseGenerique', $oConnection, $strName, $iID, $strQueryColumn, $strQueryRow, $strAbsFieldName, $strOrdFieldName, $strTableName, $strIDFieldName);
}

/**
 * __DESC__.
 *
 * @deprecated __DESC__
 *
 * @param string $strName __DESC__
 * @param string $strPrefixe (option) __DESC__
 *
 * @return __TYPE__
 */
function readMulti($strName, $strPrefixe = "multi")
{
    include_once pelican_path('Form');

    return Pelican_Factory::staticCall('Form', 'readMulti', $strName, $strPrefixe);
}

/**
 * Conversion des caractères UTF8 en unicode.
 *
 * @param string $source Texte à convertir
 *
 * @return string
 */
function utf8ToUnicodeEntities($source)
{
    pelican_import('Text.Utf8');

    return Pelican_Factory::staticCall('Text.Utf8', 'utf8_to_unicode', $source);
}

/**
 * Gestion des valeurs "NULL".
 *
 * @param string $value (option) __DESC__
 *
 * @return string
 */
function nvl($value = "")
{
    pelican_import('Db');

    return Pelican_Db::nvl($value);
}

