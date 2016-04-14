<?php
/**
 * Gestion de la mise en Pelican_Cache d'objets ou de variables PHP
 * Sous forme de fichiers sur le disque.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 * Require Zend_Cache.
 */
require_once 'Zend/Cache.php';

/**
 * Classe de Pelican_Cache utilisant l'API Zend_Cache.
 *
 * @author eric.c@zend.com
 *
 * @todo vérifier la gestion des lifetime
 */
class Pelican_Cache_Zend implements Pelican_Cache_Interface
{
    /**
     * Object Zend_Cache.
     *
     * @access protected
     *
     * @var Zend_Cache_Core
     */
    protected $_cache = null;

    /**
     * Constructeur.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function __construct()
    {
        $frontendOptions = array(
            'lifetime' => 3600, // dur�e de vie de Pelican_Cache de 10 secondes
            'automaticSerialization' => false, // Pelican_Cache le fait pour nous
            'caching' => true,
            'logging' => false,
            'write_control' => false,
            'automatic_cleaning_factor' => 0,
        );
        $backendOptions = array(
            'cacheDir' => Pelican::$config["CACHE_FW_ROOT"], // R�pertoire où stocker les fichiers de cache
            'file_locking' => false,
            'read_control' => false, // Pas de test d'int�grit�
            'read_control' => 'crc32',
            'hashed_directory_level' => 2,
            'hashed_directory_umask' => 0777,
            'file_name_prefix' => 'zc',
        );
        // obtenir un objet Zend_Cache_Core
        $this->_cache = Zend_Pelican_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Cache_Interface::isAlive()
     *
     * @param string $path
     *                     (option) __DESC__
     *
     * @return __TYPE__
     */
    public function isAlive($path = "")
    {
        $ret = $this->_cache->test($path);

        return $ret;
        // return @file_exists($path);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Cache_Interface::readCache()
     *
     * @param __TYPE__ $path
     *                       __DESC__
     *
     * @return __TYPE__
     */
    public function readCache($path)
    {
        return $this->_cache->load($path, false);
        // return file_get_contents($path);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Cache_Interface::getPath()
     *
     * @param __TYPE__ $params
     *                         __DESC__
     * @param string   $object
     *                         (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getPath($params, $object = "")
    {
        return '';
        // $dir = Pelican_Cache::hashDir($params, true, $object);
        return Pelican::$config["CACHE_FW_ROOT"].$dir."/";
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Cache_Interface::getName()
     *
     * @param string   $script
     *                                  (option) __DESC__
     * @param __TYPE__ $params
     *                                  (option) __DESC__
     * @param bool     $binaryCache
     *                                  (option) __DESC__
     * @param string   $complementCache
     *                                  (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getName($script = "", $params = array(), $binaryCache = false, $complementCache = "")
    {
        $result = $script;
        if ($binaryCache) {
            $complementCache = ".".Pelican::$config["IM_EXT"];
        }
        if ($params) {
            $result .= Pelican_Cache::getHash($params);
        }
        $result .= $complementCache;

        return strtr($result, './', '__');
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Cache_Interface::storeValue()
     *
     * @param __TYPE__ $path
     *                          __DESC__
     * @param string   $content
     *                          (option) __DESC__
     * @param string   $time
     *                          (option) __DESC__
     *
     * @return __TYPE__
     */
    public function storeValue($path, $content = "", $time = "")
    {
        return $this->_cache->save($content, $path, array(), $time ? $time : false);
        /*
         * if (output_cache_put($path, $content)) { $this->setKeyList($path, $time); return true; } else { return false; }
         */
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Cache_Interface::setLifeTime()
     *
     * @param __TYPE__ $lifeTime
     *                           __DESC__
     *
     * @return __TYPE__
     */
    public function setLifeTime($lifeTime)
    {
        switch ($lifeTime) {
            case UNLIMITED:
                {
                    $time = 0;
                    // break;
                }
            case WEEK:
                {
                    $time = 7 * 24 * 3600;
                    // break;
                }
            case MONTH:
                {
                    $time = 30 * 24 * 3600;
                    // break;
                }
            case YEAR:
                {
                    $time = 365 * 24 * 3600;
                    // break;
                }
            case DAY:
                {
                    $time = 24 * 3600;
                    // break;
                }
            default:
                {
                    $time = 3600;
                    break;
                }
        }
        $lifeTime = $time;

        return $lifeTime;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Cache_Interface::remove()
     *
     * @param string $name
     *                       (option) __DESC__
     * @param string $dir
     *                       (option) __DESC__
     * @param string $root
     *                       (option) __DESC__
     * @param bool   $defer
     *                       (option) __DESC__
     * @param bool   $log
     *                       (option) __DESC__
     * @param bool   $direct
     *                       (option) __DESC__
     *
     * @return __TYPE__
     */
    public static function remove($name = "", $dir = "", $root = "", $defer = false, $log = false, $direct = false)
    {
        $object = new Pelican_Cache_Zend();
        $pattern = Pelican_Cache::getSearchPattern($name, $dir, $root);
        // Clear local version
        $object->_removePattern($pattern);

        return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Cache_Interface::setKeyList()
     *
     * @param __TYPE__ $key
     *                      __DESC__
     *
     * @return __TYPE__
     */
    public function setKeyList($key)
    {
        // ZFR - Supprim� cause warning
        // eric.c - Fri Mar 14 18:04:16 CET 2008 18:04:16
        return;
        // /ZFR
        $list = $this->getKeyList();
        $aDate = getDate();
        $obsoleteDay = $aDate["year"].".".$aDate["mon"].".".($aDate["mday"] - 1);
        $currentDay = $aDate["year"].".".$aDate["mon"].".".$aDate["mday"];
        $currentHour = $aDate["hours"];
        $aExpired = getDate(mktime($aDate["hours"], $aDate["minutes"], $aDate["seconds"] + $time, $aDate["mon"], $aDate["mday"], $aDate["year"]));
        $expirationDay = $aExpired["year"].".".$aExpired["mon"].".".$aExpired["mday"];
        $expirationHour = $aExpired["hours"];
        unset($list[$obsoleteDay]);
        unset($list[$currentDay][$currentHour - 2]);
        $list[$expirationDay][$expirationHour][$key] = "";
        output_cache_remove_key('keyList');
        output_cache_put('keyList', @serialize($list));
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Cache_Interface::getKeyList()
     *
     * @return __TYPE__
     */
    public function getKeyList()
    {
        $time = 3600;
        $list = @unserialize(output_cache_get('keyList', $time));

        return $list;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $aKeys
     *                        (option) __DESC__
     *
     * @return __TYPE__
     */
    public function cleanKeyList($aKeys = array())
    {
        $list = $this->getKeyList();
        foreach ($aKeys as $key) {
            unset($list[$key]);
        }
        output_cache_remove_key('keyList');
        output_cache_put('keyList', @serialize($list));
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return __TYPE__
     */
    public function touchKeyList()
    {
        $time = 3600;
        @output_cache_get('keyList', $time);

        return true;
    }
}
