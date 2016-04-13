<?php

/**
    * Gestion de la mise en cache d'objets ou de variables PHP
    * Sous forme de fichiers sur le disque
    *
    * @package Pelican
    * @subpackage Cache
    * @copyright Copyright (c) 2001-2012 Business&Decision
    * @license http://www.interakting.com/license/phpfactory
    * @link http://www.interakting.com
    */

/**
 * if no delay defined :
 * - remove method is find + rm
 * - creation without touch
 * - isAlive true if exists
 * - physical deletion => (+) limit the disk space (-) risk of writing conflict
 * if delay is defined :
 * - remove method is find + touch (current time + delay)
 * - creation with touch in the ftur (one year)
 * - isAlive if filemtime > time
 * - non physical deletion => (+) change the date in the futur when regenerate (limit conflicts) (-) needs scheduled deletion
 */
//Pelican::$config['DECACHE_DELAY'] = 30;

/**
 * Gestion du cache stocké sur le fileSystem
 *
 * @package Pelican
 * @subpackage Cache
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Cache_File implements Pelican_Cache_Interface
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

    public static function getDecacheDelay()
    {
        $return = 0;
        if (! empty(Pelican::$config['DECACHE_DELAY'])) {
            $return = Pelican::$config['DECACHE_DELAY'];
        }

        return $return;
    }

    public static function getDecacheMode()
    {
        return (self::getDecacheDelay() > 0 ? 'touch' : 'rm');
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
        $decacheMode = self::getDecacheMode();

        switch ($decacheMode) {
            case 'rm':
                {
                    $return = file_exists($path);
                    break;
                }
            case 'touch':
                {
                    $expired = false;
                    $return = false;
                    $mtime = @filemtime($path);
                    if ($mtime) {
                        // file exists : isAlive by default
                        $return = true;
                        // date informations
                        $t = time();
                        $t2 = date("Ydmhis", $t);
                        $mt2 = date("Ydmhis", $mtime);
                        // backend case
                        if (empty(Pelican::$config["HTTP_BACKEND"])) {
                            Pelican::$config["HTTP_BACKEND"] = '';
                        }
                        // if the cache is used for the backend : no delay
                        if ($_SERVER['HTTP_HOST'] == Pelican::$config["HTTP_BACKEND"]) {
                            $expired = ($t2 - $mt2 + self::getDecacheDelay() >= 0);
                        } else {
                            $expired = ($t2 - $mt2 >= 0);
                        }
                        if ($expired) {
                            $this->badCache = true;
                            $this->badCacheMsg = ''; // mode silencieux
                            touch($path, time() + 3600); // deactivate the expiration date to allow to regenerate the cache for this process and avoid write conflicts
                            $return = false;
                        }
                    }
                    break;
                }
        }

        return $return;
    }

    /**
     * Important : file_exist, is_readable etc...
     * sont mis en Pelican_Cache par PHP et peuvent donner des informations fausses, surtout dans une environnement NFS
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
        $file = glob($path); // to avoid NFS cache issue
        if ($file[0]) {
			// __JFO improvement
            $content = @file_get_contents($path);
			if ($content === false)
			{
				usleep(250);
				$content = @file_get_contents($path);
			}
            $this->size = strlen($content);
            $return = $content;
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
        self::writeDir($dir);

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
        if (@file_put_contents($path, $content, LOCK_EX) === false) // JFO improvement
		{
			// cant write to file
		}
		else
		{
			if ($time) {
				@touch($path, $time);
			}

			// Permission 777 pour permettre la suppression du cache depuis un script appelé en CLI (CPW-2988)
			chmod($path, 0777);
		}

        return true;
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
        $decacheMode = self::getDecacheMode();

        if ($decacheMode == 'touch') {
            // in the futur to avoid expiration
            $lifeTime = mktime(0, 0, 0, date("m"), date("d")+2, date("Y"));
        } else {
            $lifeTime = '';
        }

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
        $decacheMode = self::getDecacheMode();

        //if (empty(Pelican::$config['CACHE_NOBACKGROUND'])) {
	        $background = " > /dev/null 2>/dev/null &"; 
        //}

        switch ($decacheMode) {
            case 'rm':
                {
                    $exec = 'rm -f';
                    break;
                }
            case 'touch':
                {

                    $delay = date("Ymdhi.s", time() + self::getDecacheDelay());
                    $exec = "touch -t " . $delay;
                    break;
                }
        }

        if ($name) {
            if ($direct) {
                /* direct decache */
                $pattern = Pelican_Cache::getSearchPattern($name, $dir);
                $key = keyFromPattern($pattern);
                $cmd = $exec . ' ' . Pelican::$config["CACHE_FW_ROOT"] . $key;
            } else {
                $cmd = "find " . $root . $dir . " -type f -name \"" . $name . "\" -exec " . $exec . " {} \; " . $background;
            }
            /* win */
            $cmdWin = str_replace("/", "\\", Pelican_Cache::getSearchPattern($name, $dir, $root));
        } else {
            $cmd = $this->name;
        }
        if ($defer) {
            /* ASYNC_CACHE */
            if (Pelican::$config["CACHE_QUEUE_ENABLED"]) {
                $oDecacheQueue = Palican_Cache::getDecacheQueue();
                $oDecacheQueue->send($cmd);
            } else {
                logDecache($cmd);
            }
            /* ASYNC_CACHE */
        } else {
            if ($name) {
                Pelican::runCommand($cmd, $cmdWin);
            } else {
                @unlink($cmd);
            }
        }
    }
    
    /**
     * Nettoyage d'un cache ciblé sur un paramètre
     *
     * @param array $decacheMatrix Liste des caches à nettoyer. Chaque élément du tableau est composé de 3 champs :
     *  - object (string) : Nom de l'objet de Pelican_Cache à nettoyer (ex: Frontend/Page)
     *  - param  (string) : Valeur du paramètre à decacher
     *  - order  (int)    : Ordre du paramètre dans l'appel du cache (1,2,3...)
     * @param string $root Chemin absolu du dossier racine du cache
     * @param bool $defer (option) Activer le décache asynchrone
     * @param bool $log (option) Loguer le décache
     */
    public static function extendedRemove($decacheMatrix, $root, $defer = false, $log = false)
    {
        // Check args
        if (empty($root) || !is_dir($root)) {
            trigger_error("Wrong cache root", E_USER_WARNING);
            return false;
        }
        
        // Commande de decache
        $decacheMode = self::getDecacheMode();
        switch ($decacheMode) {
            case 'rm':
                $exec = 'rm -f';
                break;
            case 'touch':
                $delay = date("Ymdhi.s", time() + self::getDecacheDelay());
                $exec = "touch -t " . $delay;
                break;
        }
        
        // Construction commande find pour chaque cache de $decacheMatrix
        $cmdList = array();
        foreach ($decacheMatrix as $key => $val) {
            // Check item
            if (!isset($val['object']) || !isset($val['param']) || !isset($val['order'])) {
                trigger_error("Invalid decache item", E_USER_WARNING);
                continue;
            }
            
            // Construction de la commande de suppression du cache courant
            $objectName = Pelican_Cache::objectName($val['object']); // Nom du dossier correspondant au cache du type courant
            $paramHash = Pelican_Text::md5($val['param']);           // Hash du paramètre sur lequel est basé le decache
            $cacheDirectory = $root.DIRECTORY_SEPARATOR.$objectName; // Dossier contenant le cache du type courant
            $pattern = '^'.quotemeta($cacheDirectory).'/.*/'.$objectName.'(_[0-9]+){'.($val['order']-1).'}_'.$paramHash.'(\.|_)';
            $cmd = 'find "'.addcslashes(quotemeta($cacheDirectory), '"').'" -type f | egrep "'.addcslashes($pattern, '"').'"';
            $cmd .= ' | xargs '.$exec;
            $cmd .= ' &';
            $cmdList[] = $cmd;
        }
        
        // Exécution de la commande de decache
        $cmd = implode("\n", $cmdList);
        if ($defer) {
            if (Pelican::$config["CACHE_QUEUE_ENABLED"]) {
                $oDecacheQueue = Palican_Cache::getDecacheQueue();
                $oDecacheQueue->send($cmd);
            } else {
                logDecache($cmd);
            }
        } else {
            Pelican::runCommand($cmd);
        }
    }

    public static function removeInvalidCache()
    {
        $decacheMode = self::getDecacheMode();

        $cmd = '';
        $background = " &";

        switch ($decacheMode) {
            case 'rm':
                {
                    $cmd = '';
                    break;
                }
            case 'touch':
                {
                    $cmd = "find " . Pelican::$config["CACHE_FW_ROOT"] . " -daystart -type f -mtime +1 -exec rm -f {} \; " . $background;
                    break;
                }
        }

        if (! empty($cmd)) {
            Pelican::runCommand($cmd);
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

    /**
     * Création d'un répertoire
     *
     * @param  string $path
     *                       Chemin physique du répertoire
     * @param  string $chmod
     *                       (option) Droits à appliquer au répertoire (chmod)
     * @return void
     */
    protected function writeDir($path, $chmod = "0777")
    {
        if (! is_dir(Pelican::$config["CACHE_FW_ROOT"] . $path)) {
            if (isset($_SERVER['WINDIR'])) {
                mkdir($path, $chmod, true);
            } else {
                $cmd = "mkdir -p -m 755 " . Pelican::$config["CACHE_FW_ROOT"] . $path;
                Pelican::runCommand($cmd);
            }
        }
    }
}
