<?php

/**
    * Gestion de la mise en Pelican_Cache d'objets ou de variables PHP
    * Sous forme de sesion
    *
    * @package Pelican
    * @subpackage Cache
    * @copyright Copyright (c) 2001-2012 Business&Decision
    * @license http://www.interakting.com/license/phpfactory
    * @link http://www.interakting.com
    */

/**
 * Gestion du Pelican_Cache stocké sur le fileSystem
 *
 * @package Pelican
 * @subpackage Cache
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Cache_Session implements Pelican_Cache_Interface
{

    /**
     * Constructeur
     *
     * @access public
     * @return Pelican_Cache_File
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::isAlive()
     * @param  string   $path
     *                        (option) __DESC__
     * @return __TYPE__
     */
    public function isAlive($path = "")
    {
        return isset($_SESSION[__CLASS__][$path]);
    }

    /**
     * Important : file_exist, is_readable etc...
     * sont mis en Pelican_Cache par PHP et
     * peuvent
     * donner des informations fausses, surtout dans une environnement NFS
     *
     * Glob est utilisé pour vérifié l'existence du fichier
     *
     * @access public
     * @see Pelican_Cache_Interface::readCache()
     * @param  __TYPE__ $path
     *                        __DESC__
     * @return __TYPE__
     */
    public function readCache($path)
    {
        $return = "";
        if (isset($_SESSION[__CLASS__][$path])) {
            $return = $_SESSION[__CLASS__][$path];
            $this->size = strlen($_SESSION[__CLASS__][$path]);
        }

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::getPath()
     * @param  __TYPE__ $params
     *                          __DESC__
     * @param  string   $object
     *                          (option) __DESC__
     * @return __TYPE__
     */
    public function getPath($params, $object = "")
    {
        $dir = Pelican_Cache::hashDir($params, true, $object);

        return $dir . "/";
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::getName()
     * @param  string   $script
     *                                   (option) __DESC__
     * @param  __TYPE__ $params
     *                                   (option) __DESC__
     * @param  bool     $binaryCache
     *                                   (option) __DESC__
     * @param  string   $complementCache
     *                                   (option) __DESC__
     * @return __TYPE__
     */
    public function getName($script = "", $params = array(), $binaryCache = false, $complementCache = "")
    {
        $result = $script;
        if ($binaryCache) {
            $complementCache = "." . Pelican::$config["IM_EXT"];
        }
        if ($params) {
            $result .= Pelican_Cache::getHash($params);
        }
        $result .= $complementCache;

        return $result;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::storeValue()
     * @param  __TYPE__ $path
     *                           __DESC__
     * @param  string   $content
     *                           (option) __DESC__
     * @param  string   $time
     *                           (option) __DESC__
     * @return __TYPE__
     */
    public function storeValue($path, $content = "", $time = "")
    {
        if (! isset($_SESSION[__CLASS__])) {
            $_SESSION[__CLASS__] = array();
        }
        $return = $_SESSION[__CLASS__][$path] = $content;

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::setLifeTime()
     * @param  __TYPE__ $lifeTime
     *                            __DESC__
     * @return __TYPE__
     */
    public function setLifeTime($lifeTime)
    {
        return $lifeTime;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::remove()
     * @param  string   $name
     *                          (option) __DESC__
     * @param  string   $dir
     *                          (option) __DESC__
     * @param  string   $root
     *                          (option) __DESC__
     * @param  bool     $defer
     *                          (option) __DESC__
     * @param  bool     $log
     *                          (option) __DESC__
     * @param  bool     $direct
     *                          (option) __DESC__
     * @return __TYPE__
     */
    public static function remove($name = "", $dir = "", $root = "", $defer = false, $log = false, $direct = false)
    {
        if ($name) {
            $pattern = Pelican_Cache::getSearchPattern($name, $dir, $root);
            $key = keyFromPattern($pattern);
            if (isset($_SESSION[__CLASS__][$key])) {
                unset($_SESSION[__CLASS__][$key]);
            }
        } else {
            $cmd = $this->name;
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::setKeyList()
     * @param  __TYPE__ $key
     *                       __DESC__
     * @return __TYPE__
     */
    public function setKeyList($key)
    {}

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::getKeyList()
     * @return __TYPE__
     */
    public function getKeyList()
    {}
}
