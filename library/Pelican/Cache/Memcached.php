<?php

/**
    * Gestion de la mise en Pelican_Cache d'objets ou de variables PHP
    * Connecteur pour PECL's Memcache extension by Antony Dovgal.
    *
    * Lancement du démon : /usr/local/bin/memcached -d -u root -m 256 -l 127.0.0.1
    * -p 9500
    *
    * @package Pelican
    * @subpackage Cache
    * @copyright Copyright (c) 2001-2012 Business&Decision
    * @license http://www.interakting.com/license/phpfactory
    * @see http://tony2001.phpclub.net/
    * @link http://www.interakting.com
    */

/**
 * Gestion du Pelican_Cache stocké dans Memcache
 *
 * @package Pelican
 * @subpackage Cache
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Cache_Memcached implements Pelican_Cache_Interface
{

    /**
     * Constructeur
     *
     * @access public
     * @return Pelican_Cache_File
     */
    public function __construct()
    {
        register_shutdown_function(array(
            &$this,
            "close"
        ));
        $this->object = new Memcache();
        if (! Pelican::$config["MEMCACHE_HOST"]) {
            Pelican::$config["MEMCACHE_HOST"][0][0] = '127.0.0.1';
            Pelican::$config["MEMCACHE_HOST"][0][1] = '9500';
        }
        foreach (Pelican::$config["MEMCACHE_HOST"] as $server) {
            $this->object->addServer($server[0], $server[1]);
        }
        $this->object->setCompressThreshold(5000, 0.2);

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
        $exists = $this->object->get($path);

        return $exists;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::readCache()
     * @param  __TYPE__ $path
     *                        __DESC__
     * @return __TYPE__
     */
    public function readCache($path)
    {
        $return = $this->object->get($path);

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
        $dir = Pelican_Cache::hashDir($params);

        return Pelican::$config["CACHE_FW_ROOT"] . $dir . "/";
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
    public function getName ($script = "", $params = array(), $binaryCache = false, $complementCache = "")
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
    public function storeValue($path, $content = "", $time = 0)
    {
        if ($this->object->set($path, $content, 0, $time)) {
            $this->setKeyList($path, $time);

            return true;
        } else {
            return false;
        }
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
                    $time = 24 * 3600;
                    break;
                }
        }
        $lifeTime = $time;

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
        $object = new Pelican_Cache_Memcached();
        $pattern = Pelican_Cache::getSearchPattern($name, $dir, $root);
        $object->_removePattern($pattern);

        return null;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::setKeyList()
     * @param  __TYPE__ $key
     *                        __DESC__
     * @param  string   $time
     *                        (option) __DESC__
     * @return __TYPE__
     */
    public function setKeyList($key, $time = "")
    {
        $list = $this->getKeyList();
        $aDate = getDate();
        $obsoleteDay = $aDate["year"] . "." . $aDate["mon"] . "." . ($aDate["mday"] - 1);
        $currentDay = $aDate["year"] . "." . $aDate["mon"] . "." . $aDate["mday"];
        $currentHour = $aDate["hours"];
        $aExpired = getDate(mktime($aDate["hours"], $aDate["minutes"], $aDate["seconds"] + $time, $aDate["mon"], $aDate["mday"], $aDate["year"]));
        $expirationDay = $aExpired["year"] . "." . $aExpired["mon"] . "." . $aExpired["mday"];
        $expirationHour = $aExpired["hours"];
        if ($list[$obsoleteDay]) {

            /**
             * pas sûr encore pour éviter de faire trop de décache d'élements toujours en cours de validité
             * foreach ($list[$obsoleteDay] as $day) {
             * foreach ($day as $item) {
             * $this->object->delete($item);
             * }
             * }
             */
            unset($list[$obsoleteDay]);
        }
        if ($list[$currentDay][$currentHour - 2]) {
            foreach ($list[$currentDay][$currentHour - 2] as $item) {
                $this->object->delete($item);
            }
            unset($list[$currentDay][$currentHour - 2]);
        }
        $list[$expirationDay][$expirationHour][$key] = "";
        $this->object->set('keyList', @serialize($list));
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::getKeyList()
     * @return __TYPE__
     */
    public function getKeyList()
    {
        $list = @unserialize($this->object->get('keyList'));

        return $list;
    }

    /**
     * Nettoyage d'une liste de clés de Pelican_Cache
     *
     * @access public
     * @param  mixed    $aKeys
     *                         (option) __DESC__
     * @return __TYPE__
     */
    public function cleanKeyList ($aKeys = array())
    {
        $list = $this->getKeyList();
        foreach ($aKeys as $key) {
            unset($list[$key]);
        }
        $this->object->set('keyList', @serialize($list));
    }

    /**
     * Fermeture de la connexion à Memcache
     *
     * @access public
     * @return __TYPE__
     */
    public function close()
    {
        if ($this->object) {
            $this->object->close();
        }
    }

    /**
     * Nettoyage de toutes les clés de Pelican_Cache
     *
     * @access public
     * @return __TYPE__
     */
    public function clean()
    {
        $this->object->flush();
    }

    /**
     * Supprime chaque Pelican_Cache répondant à l'expression régulière
     *
     * @access private
     * @param  string   $pattern
     *                           __DESC__
     * @return __TYPE__
     */
    private function _removePattern($pattern)
    {
        $keys = $this->findKeys($pattern);
        foreach ($keys as $key) {
            $key = trim($key);
            $this->object->delete($key);
            $aRemoveKeys[] = $key;
        }
        $this->cleanKeyList((array) $aRemoveKeys);
    }

    /**
     * Retrouve les clés répondant à une expression régulière
     *
     * Retourne un tableau vide sinon
     *
     * @access public
     * @param  string $pattern
     *                         __DESC__
     * @return array
     */
    public function findKeys($pattern)
    {
        $list = $this->object->get('keyList');
        $value = str_replace(array(
            ";",
            ":",
            "\""
        ), array(
            "\n",
            "\n",
            ""
        ), $list);
        $out = array();
        if ($value) {
            // Replace file name style wildcards with Perl regular expression wildcards
            $pattern = preg_quote($pattern, '|');
            $pattern = '|' . str_replace(array(
                '\*',
                '\?'
            ), array(
                '.*',
                '.?'
            ), $pattern) . '|i';
            preg_match_all($pattern, $value, $out);
            $out = $out[0];
        }

        return $out;
    }
}
