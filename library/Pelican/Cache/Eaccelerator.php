<?php
/**
 * Gestion de la mise en Pelican_Cache d'objets ou de variables PHP
 * Sous forme de fichiers sur le disque
 *
 * @package Pelican
 * @subpackage Cache
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * Gestion de la mise en Pelican_Cache d'objets ou de variables PHP
 * Sous forme de fichiers sur le disque
 *
 * @package Pelican
 * @subpackage Cache
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Cache_Eaccelerator implements Pelican_Cache_Interface
{
    /**
     * Constructeur
     *
     * @access public
     * @return Cache_File
     */
    public function Pelican_Cache_Eaccelerator()
    {
        return $this;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::isAlive()
     * @param  string   $path (option) __DESC__
     * @return __TYPE__
     */
    public function isAlive($path = "")
    {
        $exists = eaccelerator_get($path);

        return $exists;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::readCache()
     * @param  __TYPE__ $path __DESC__
     * @return __TYPE__
     */
    public function readCache($path)
    {
        $return = eaccelerator_get($path);

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::getPath()
     * @param  __TYPE__ $params __DESC__
     * @param  string   $object (option) __DESC__
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
     * @param  string   $script          (option) __DESC__
     * @param  __TYPE__ $params          (option) __DESC__
     * @param  bool     $binaryCache     (option) __DESC__
     * @param  string   $complementCache (option) __DESC__
     * @return __TYPE__
     */
    public function getName($script = "", $params = array(), $binaryCache = false, $complementCache = "")
    {
        $result = $script;
        if ($binaryCache) {
            $complementCache = "." . Pelican::$config["IM_EXT"];
        }
        if ($params) {
            $result.= Pelican_Cache::getHash($params);
        }
        $result.= $complementCache;

        return $result;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::storeValue()
     * @param  __TYPE__ $path    __DESC__
     * @param  string   $content (option) __DESC__
     * @param  string   $time    (option) __DESC__
     * @return __TYPE__
     */
    public function storeValue($path, $content = "", $time = 0)
    {
        if (@eaccelerator_put($path, $content, $time)) {
            $this->setKeyList($path);

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
     * @param  __TYPE__ $lifeTime __DESC__
     * @return __TYPE__
     */
    public function setLifeTime($lifeTime)
    {
        switch ($lifeTime) {
            case UNLIMITED: {
                        $time = 0;
                    break;
                }
            case WEEK: {
                    $time = 7 * 24 * 3600;
                    break;
                }
            case MONTH: {
                    $time = 30 * 24 * 3600;
                    break;
                }
            case YEAR: {
                    $time = 365 * 24 * 3600;
                    break;
                }
            case DAY:
            default: {
                    $time = 24 * 3600;
                    break;
                }
            }
            if ($time) {
                $lifeTime = $time;
            }

            return $lifeTime;
        }

        /**
         * __DESC__
         *
         * @access public
         * @see Pelican_Cache_Interface::remove()
         * @param string $name (option) __DESC__
         * @param string $dir (option) __DESC__
         * @param string $root (option) __DESC__
         * @param bool $defer (option) __DESC__
         * @param bool $log (option) __DESC__
         * @param bool $direct (option) __DESC__
         * @return __TYPE__
         */
        public static function remove($name = "", $dir = "", $root = "", $defer = false, $log = false, $direct = false)
        {
            $pattern = Pelican_Cache::getSearchPattern($name, $dir, $root);
            $object = new Pelican_Cache_Eaccelerator();
            $aPath = $object->findKeys($pattern);
            if ($aPath[0]) {
                foreach ($aPath[0] as $path) {
                    $path = trim($path);
                    eaccelerator_rm($path);
                    $object->cleanKeyList($path);
                }
            }
        }

        /**
         * __DESC__
         *
         * @access public
         * @see Pelican_Cache_Interface::setKeyList()
         * @param __TYPE__ $key __DESC__
         * @return __TYPE__
         */
        public function setKeyList($key)
        {
            $list = $this->getKeyList();
            $list[$key] = true;
            eaccelerator_put('keyList', $list);
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
            $list = eaccelerator_get('keyList');

            return $list;
        }

        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $key __DESC__
         * @return __TYPE__
         */
        public function cleanKeyList($key)
        {
            $list = $this->getKeyList();
            unset($list[$key]);
            eaccelerator_put('keyList', $list);
        }

        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $pattern __DESC__
         * @return __TYPE__
         */
        public function findKeys($pattern)
        {
            $list = implode("\r\n", array_keys(eaccelerator_get('keyList')));
            $pattern = "/" . str_replace("/*", "/.*", str_replace("/", "\/", $pattern)) . "/i";
            preg_match_all($pattern, $list, $out);

            return $out;
        }

        /**
         * __DESC__
         *
         * @access public
         * @return __TYPE__
         */
        public function clean()
        {
            eaccelerator_clean();
        }
    }
