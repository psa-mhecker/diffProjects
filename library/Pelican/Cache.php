<?php
/**
 * Cache system for PHP object
 *
 * @package Pelican
 * @subpackage Cache
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * Unlimited duration
 */
define('UNLIMITED', '0');

/**
 * 1 day duration
 */
define('DAY', '2');

/**
 * 1 month duration
 */
define('MONTH', '3');

/**
 * 1 year duration
 */
define('YEAR', '4');

/**
 * 1 week duration
 */
define('WEEK', '5');

pelican_import('Cache.Interface');

Pelican_Cache::defineDefaultType(Pelican::$config);

/**
 * Classe permettant de gérer le Pelican_Cache d'objets php
 *
 * Commande à lancer dans la crontab pour nettoyer les fichiers de Pelican_Cache :
 * 1/ crontab -e
 * 2/ editer le fichier
 * 3/ 00 00 * * 1-5 /bin/sh /projects/XX/library/Pelican/Cache/clean_cache.sh
 * /projects/XX/cache
 * pour un lancement tous les jours à minuit
 *
 * @package Pelican
 * @subpackage Cache
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 16/06/2004
 */
class Pelican_Cache
{

    /**
     *
     * @var unknown_type
     */
    public static $type;

    /**
     *
     * @var __TYPE__ __DESC__
     */
    public static $defaultType;

    /**
     *
     * @var string
     */
    const storage = '';

    /**
     *
     * @var number of hash levels
     */
    const nbSousNiveau = 3;

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $isCached = true;

    public $isPersistent = false;

    /**
     * Valeur à mettre en Pelican_Cache ou récupérée du Pelican_Cache
     *
     * @access public
     * @var mixed
     */
    public $value;

    /**
     * Tableau des paramètres du fichier de Pelican_Cache (paramètres variants)
     *
     * @access public
     * @var mixed
     */
    public $params;

    /**
     * Objet pile de décaches
     *
     * @access protected
     * @var object
     */
    protected static $decacheQueue;

    /**
     * __DESC__
     *
     * @access public
     * @var bool
     */
    public $deprecated = true;

    /**
     * Nom du fichier de Pelican_Cache
     *
     * @access public
     * @var string
     */
    public $name;

    /**
     * Détermine si le contenu du Pelican_Cache est binaire ou non
     *
     * @access public
     * @var bool
     */
    public $binaryCache = false;

    /**
     * Extension du fichier de Pelican_Cache
     *
     * @access public
     * @var string
     */
    public $extensionCache = ".php";

    /**
     * Création du fichier de Pelican_Cache ou non
     *
     * @access public
     * @var bool
     */
    public $notExists = false;

    /**
     * Valeur du fichier de Pelican_Cache vide
     *
     * @access public
     * @var string
     */
    public static $noValue = "no_value";

    /**
     * Activation de la compression ou non
     *
     * @access public
     * @var bool
     */
    public $compress = false;

    /**
     * Décache direct, sans *
     *
     * @access public
     * @var bool
     */
    public $direct;

    /**
     * Pelican_Cache erroné
     *
     * @access public
     * @var bool
     */
    public $badCache = false;

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $badCacheMsg = "[Bad cache] empty file => ";

    /**
     * Taille du Pelican_Cache
     *
     * @access public
     * @var int
     */
    public $size = 0;

    /**
     * Type de Pelican_Cache utilisé
     *
     * @access public
     * @var string
     */
    public $cacheType;

    /**
     * Durée de vie du Pelican_Cache
     *
     * @access public
     * @var int
     */
    public $duration;

    /**
     * Instance de Pelican_Cache
     *
     * @access public
     * @var object
     */
    public $instance;

    /**
     * Durée de vie
     *
     * @access public
     * @var int
     */
    public $lifeTime;

    public static $perso;

    /**
     * tableau de persistance des caches : nécessite que la propriété persistence de l'objet de cache soit à true
     */
    public static $persistence = array();

    public static $aSuiviDecache = array();

    /**
     * __DESC__
     *
     * @access public
     * @param mixed $config
     *            __DESC__
     * @return string
     *
     */
    public static function defineDefaultType($config)
    {
        // Pelican_Cache type to use
        if (extension_loaded('memcache') && ! empty($config["MEMCACHE_HOST"]) && $config["ENABLE_MEMCACHE_CACHE"]) {
            self::$defaultType = 'Memcached';
        } else {
            if (function_exists("output_cache_get") && ini_get('zend_accelerator.output_cache_enabled') && valueExists($config, "ENABLE_ZEND_CACHE")) {
                self::$defaultType = 'Zend';
            } else {
                self::$defaultType = 'File';
            }
        }
        
        self::$type = self::$defaultType;
    }

    /**
     * Constructeur
     *
     * @access public
     * @return Pelican_Cache
     */
    public function __construct()
    {
        global $script, $binary;
        if (empty($this->cacheType)) {
            $this->cacheType = "Cache/" . self::$type;
        }
        if ($binary) {
            $this->binaryCache = true;
        }
        require_once (dirname(__FILE__) . "/" . $this->cacheType . ".php");
        $rename = str_replace('Cache/', 'Pelican_Cache_', ($this->cacheType));
        $this->instance = new $rename();
        $this->instance->direct = ($this->direct ? true : false);
        
        /**
         * paramètres de Pelican_Cache (ils peuvent avoir été interceptés dans une classe fille)
         */
        if (! $this->params) {
            $args = func_get_arg(0);
            $this->params = $this->getParams($args);
        }
        
        /**
         * Nom du Pelican_Cache
         */
        $base = strtolower(($script ? $script : get_class($this)));
        $this->name = $this->instance->getPath($this->params, $base);
        $this->name .= $this->instance->getName($base, $this->params, $this->binaryCache, $this->extensionCache);
        
        /**
         * définition de la durée de vie du Pelican_Cache
         */
        if (isset($this->duration)) {
            $this->lifeTime = $this->duration;
        }
    }

    /**
     * Retourne le tableau des paramètres
     *
     * @access public
     * @param mixed $aParams
     *            Tableau de paramètres
     * @return array
     */
    public function getParams($aParams)
    {
        return (array) $aParams;
    }

    /**
     * Initialisation de la propriété $value : soit elle existe (déjà en cache),
     * soit elle est créée (méthode getValue())
     *
     * @access public
     * @return mixed
     */
    public function get()
    {
        $this->getCache();
        if (! isset($this->value) || $this->badCache || ! $this->deprecated || ($this->deprecated && $this->notExists)) {
            $this->isCached = false;
            if (self::$perso && method_exists($this, 'getValueProfiling')) {
                $this->getValueProfiling();
                self::$perso = '';
            } else {
                $this->getValue();
            }
            if (! $this->value) {
                $this->value = self::$noValue;
            }
        }
        // Ajout de la possibilité pour l'objet de cache à changer son propre type de stockage
        if (isset($this->storage) && $this->storage) {
            
            self::$type = ucFirst(strtolower($this->storage));
        }
        if ($this->value && $this->deprecated && ($this->notExists || $this->badCache)) {
            if (empty($_SESSION['PAGES_VIEW'])) {
                $_SESSION['PAGES_VIEW'] = 0;
            }
            if (empty(Pelican::$config["PAGEVIEWS_CACHE_LIMIT"])) {
                Pelican::$config["PAGEVIEWS_CACHE_LIMIT"] = 0;
            }
            if ((Pelican::$config["PAGEVIEWS_CACHE_LIMIT"] == 0) or (Pelican::$config["PAGEVIEWS_CACHE_LIMIT"] > 0 && $_SESSION['PAGES_VIEW'] <= Pelican::$config["PAGEVIEWS_CACHE_LIMIT"])) {
                $this->store();
            } else {
                // var_dump('no store');
            }
        }
        if ($this->value == self::$noValue) {
            $this->value = "";
        }
        
        return $this->value;
    }

    /**
     * Réécriture forcée du Pelican_Cache
     *
     * @access public
     * @return void
     */
    public function replace()
    {
        $this->delete();
        
        return $this->get();
    }

    /**
     * Lecture du fichier de Pelican_Cache s'il existe et intialisation de la
     * propriété
     *
     * $value
     *
     * @access public
     * @return void
     */
    public function getCache()
    {
        if ($this->instance->isAlive($this->name)) {
            $this->badCache = false;
            $this->notExists = false;
            $this->value = $this->restoreData($this->instance->readCache($this->name));
            if (! $this->value) {
                $this->badCache = true;
                // $this->deprecated = false;
            }
        } else {
            $this->notExists = true;
        }
    }

    /**
     * Singleton de la pile de décachage asynchrone
     *
     * @access public
     * @return __TYPE__
     */
    public static function getDecacheQueue()
    {
        if (! self::$decacheQueue) {
            pelican_import('Queue');
            $oDecacheQueue = new Pelican_Queue(Pelican::$config["CACHE_QUEUE_TYPE"], Pelican::$config["CACHE_QUEUE_OPTIONS"]);
            self::$decacheQueue = $oDecacheQueue;
        }
        
        return self::$decacheQueue;
    }

    /**
     * Ecriture du fichier de Pelican_Cache à partir de la propriété $value
     *
     * @access public
     * @return void
     */
    public function store()
    {
        // if ($this->value && $this->value!=self::$noValue) {
        if ($this->value) {
            $value = $this->prepareData($this->value);
            $filename = $this->name;
            if ($this->badCache) {
                $filenameGOOD = $filename;
                $filename .= rand() . ".temp";
            }
            $this->instance->storeValue($filename, $value, $this->instance->setLifeTime($this->lifeTime));
            if ($this->badCache) {
                $cmd = "mv -f " . $filename . " " . $filenameGOOD;
                $fp = @popen($cmd, "r");
                @pclose($fp);
                if (! empty($this->$badCacheMsg)) {
                    $this->logError($this->$badCacheMsg . $filename);
                }
            }
        }
    }

    /**
     * Autorise la compression du contnu du Pelican_Cache
     *
     * @access public
     * @return bool
     */
    public function enableCompression()
    {
        $this->compress = true;
    }

    /**
     * Désactive la compression du contenu du Pelican_Cache
     *
     * @access public
     * @return bool
     */
    public function disableCompression()
    {
        $this->compress = false;
    }

    /**
     * Serialize et/ou compresse le contenu du Pelican_Cache
     *
     * @access public
     * @param mixed $value
     *            Données du Pelican_Cache
     * @return string
     */
    public function prepareData($value)
    {
        if ($this->compress) {
            return gzcompress(serialize($value));
        } else {
            return serialize($value);
        }
    }

    /**
     * Déserialise le contenu du Pelican_Cache
     *
     * @access public
     * @param string $value
     *            Données du Pelican_Cache sérialisées
     * @return mixed
     */
    public function restoreData($value)
    {
        if ($this->compress) {
            return unserialize(gzuncompress($value));
        } else {
            return unserialize($value);
        }
    }

    /**
     * Appel à la méthode de suppression du Pelican_Cache (dépend de la couche
     * d'abstraction Utilisée)
     *
     * @access public
     * @param string $name
     *            (option) Nom de l'objet de Pelican_Cache
     * @param string $dir
     *            (option) Sous répertoire de stockage du Pelican_Cache
     * @param string $root
     *            (option) Répertoire racine du Pelican_Cache
     * @param string $defer
     *            (option) Décache différé
     * @param string $log
     *            (option) Décache par log en base de données
     * @param bool $direct
     *            (option) Suppression directe, sans wilcard (*)
     * @return mixed
     */
    public static function delete($name = "", $dir = "", $root = "", $defer = false, $log = false, $direct = false, $typeCache = '')
    {
        $cacheType = (! empty($typeCache)) ? "Cache/" . $typeCache : "Cache/" . self::$type;
        require_once (dirname(__FILE__) . "/" . $cacheType . ".php");
        
        return call_user_func_array(array(
            pelican_classname($cacheType),
            'remove'
        ), array(
            $name,
            $dir,
            $root,
            $defer,
            $log,
            $direct
        ));
    }
    
    /**
     * Nettoyage d'un cache ciblé sur un paramètre
     *
     * @access public
     * @param string $name
     *            (option) Nom de l'objet de Pelican_Cache
     * @param string $dir
     *            (option) Sous répertoire de stockage du Pelican_Cache
     * @param string $root
     *            (option) Répertoire racine du Pelican_Cache
     * @param string $defer
     *            (option) Décache différé
     * @param string $log
     *            (option) Décache par log en base de données
     * @return mixed
     */
    public static function extendedDelete($decacheMatrix, $root = null, $defer = false, $log = false, $typeCache = '')
    {
        if (empty($root)) {
            $root = Pelican::$config["CACHE_FW_ROOT"];
        }
        
        $cacheType = (! empty($typeCache)) ? "Cache/" . $typeCache : "Cache/" . self::$type;
        require_once (dirname(__FILE__) . "/" . $cacheType . ".php");
        
        return call_user_func_array(array(
            pelican_classname($cacheType),
            'extendedRemove'
        ), array(
            $decacheMatrix,
            $root,
            $defer,
            $log
        ));
    }

    /**
     * Création d'un nom de Pelican_Cache à partir du fichier d'origine
     *
     * @access public
     * @param string $object
     *            Nom de la classe d'origine du Pelican_Cache
     * @return string
     */
    public static function objectName($object)
    {
        return str_replace("/", "_", strtolower($object));
    }

    /**
     * Log des erreurs
     *
     * @access public
     * @param string $message
     *            Message d'erreurs
     * @return void
     */
    public function logError($message)
    {
        $log[] = $message;
        $log[] = "referer: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        if (is_array($log)) {
            error_log(implode(" ", $log), 0);
        }
    }

    /**
     * Défini l'identifiant de la tranche horaire suivant $refreshStep
     *
     * @access public
     * @param int $refreshStep
     *            (option) Durée d'une tranche horaire (en minute)
     * @param string $date
     *            (option) __DESC__
     * @return int
     */
    public function getTimeStep($refreshStep = "", $date = "")
    {
        $return = "";
        if ($refreshStep > 0) {
            $currentDate = ($date ? $date : getDate());
            $day = $currentDate['year'] . '-' . $currentDate['mon'] . '-' . $currentDate['mday'];
            $hour = $currentDate['hours'];
            $minute = $currentDate['minutes'];
            $current = $hour * 60 + $minute;
            $step = (int) ($current / $refreshStep);
            $return = $day . '-' . ((int) $step);
        }
        
        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $refreshStep
     *            (option) __DESC__
     * @param string $date
     *            (option) __DESC__
     * @return __TYPE__
     */
    public static function getSecondTimeStep($refreshStep = "", $date = "")
    {
        $return = "";
        if ($refreshStep > 0) {
            $currentDate = ($date ? $date : getDate());
            $day = $currentDate['year'] . '-' . $currentDate['mon'] . '-' . $currentDate['mday'];
            $hour = $currentDate['hours'];
            $minute = $currentDate['minutes'];
            $second = $currentDate['secondes'];
            $current = $hour * 60 * 60 + $minute * 60 + $second;
            $step = (int) ($current / $refreshStep);
            $return = $day . '-' . ((int) $step);
        }
        
        return $return;
    }

    /**
     * Fonction de lecture/écriture d'un fichier de Pelican_Cache (avec ses
     * paramètres si nécessaire)
     *
     * @access public
     * @param string $object
     *            Nom de l'objet de Pelican_Cache à utiliser (sans .php)
     * @param mixed $aParam
     *            (option) Tableau des paramètres d'identification du
     *            Pelican_Cache
     * @param string $type
     *            (option) "binary" ou non
     * @param bool $pluginId
     *            (option) Nom du Pelican_Plugin associé, pour utiliser
     *            les fichiers de application/caches associés
     * @return Valeur
     */
    public static function fetch($object, $aParam = "", $type = "", $pluginId = false)
    {
        global $script, $binary;
        
        $persistence_key = md5(serialize(func_get_args()));
        
        if (empty(Pelican_Cache::$persistence[$persistence_key])) {
            $indice = $object;
            Pelican_Profiler::start($indice, 'cache');
            $time0 = microtime(true);
            
            $binary = false;
            if ($type == "binary") {
                $binary = true;
            }
            $script = self::objectName($object);
            if ($aParam) {
                if (! is_array($aParam)) {
                    $aParam = array(
                        $aParam
                    );
                }
            } else {
                $aParam = array();
            }
            $cache_root = ($pluginId ? Pelican::$config["PLUGIN_ROOT"] . "/" . $pluginId . "/caches" : Pelican::$config["TEMPLATE_CACHE_ROOT"]);
            $sCacheClassFilePath = $cache_root . "/" . $object . ".php";
            if (is_file($sCacheClassFilePath)) {
                require_once ($sCacheClassFilePath);
            } else {
                error_log('[Pelican] [Cache.php] fichier de Pelican_Cache inexistant : ' . $sCacheClassFilePath);
                if (Pelican::$config["SHOW_DEBUG"]) {
                    echo (Pelican_Html::div(array(
                        style => "background-color:red"
                    ), "Cache inexistant : " . $object . ".php"));
                }
                
                return false;
            }
            /*
             * $classe = explode("/", $object); if (count($classe) > 1) { $classe = array_reverse($classe); $object = $classe[0]; }
             */
            $object = str_replace('/', '_', $object);
            
            // $storage = $object::storage;
            
            if (! empty($storage)) {
                self::$type = ucFirst(strtolower($storage));
            } else {
                self::$type = self::$defaultType;
            }
            $cache = new $object($aParam);
            $script = ""; /* !important, ne pas supprimer */
            
            if (isset($cache)) {
                /**
                 * dans le cas de la prévisu, pas de stockage du Pelican_Cache
                 */
                if (isset($_GET["preview"])) {
                    if ($_GET["preview"]) {
                        $cache->deprecated = false;
                    }
                }
                $varValues = $cache->get();
            }
            /*
             * if (!isset($_CACHE[$object])) { $_CACHE[$object] = array(); } $_CACHE[$object][] = $cache->size;
             */
            Pelican_Profiler::stop($indice, 'cache');
            $time = microtime(true);
            Pelican_Log::control(sprintf(PROFILE_FORMAT_TIME, ($time - $time0)) . ' : ' . $indice, 'cache');
            if ($cache->isCached) {
                Pelican_Profiler::rename($indice, '&nbsp;&nbsp;[cache actif]&nbsp;&nbsp;' . $indice, 'cache');
            }
            if (! $varValues) {
                $varValues = null;
            }
            if ($cache->isPersistent) {
                Pelican_Cache::$persistence[$persistence_key] = $varValues;
                if (empty(Pelican_Cache::$persistence[$persistence_key])) {
                    Pelican_Cache::$persistence[$persistence_key] = self::$noValue;
                }
            }
            unset($cache);
        } else {
            $varValues = null;
            if (! empty(Pelican_Cache::$persistence[$persistence_key])) {
                if (Pelican_Cache::$persistence[$persistence_key] != self::$noValue) {
                    $varValues = Pelican_Cache::$persistence[$persistence_key];
                }
            }
        }
        
        return $varValues;
    }

    /**
     * Destruction d'un fichier de Pelican_Cache : si aucun paramètre ou aucune
     * extension ne sont précisés, le nettoyage se fait sur tous les fichiers commencant par
     *
     * "$objet"
     *
     * @access public
     * @param string $object
     *            Nom de l'objet de Pelican_Cache à utiliser (sans .php)
     * @param mixed $params
     *            (option) Paramètres variants
     * @param string $extension
     *            (option) Extension du fichier de Pelican_Cache (du
     *            type "php")
     * @param bool $defer
     *            (option) Activer le décache asynchrone
     * @param bool $log
     *            (option) Loguer le décache
     * @param bool $direct
     *            (option) Décache direct (sans wildcard)
     * @return void
     */
    public static function clean($object, $params = array(), $extension = "", $defer = false, $log = false, $direct = false, $typeCache = '')
    {
        $complement = "";
        if ($params) {
            if (! is_array($params)) {
                $params = array(
                    $params
                );
            }
            if ($params[0]) {
                $complement = self::getHash($params);
            }
        }
        $extension = "*." . ($extension ? $extension : "*");
        $base = self::objectName($object) . $complement . $extension;
        $sub = self::hashDir($params, false, self::objectName($object));
        
        /**
         * si un décache a déjà été fait, on ne le refait pas
         */
        if (! valueExists(self::$aSuiviDecache, $sub . $base)) {
            self::delete($base, $sub, Pelican::$config["CACHE_FW_ROOT"], $defer, $log, false, $typeCache);
            self::$aSuiviDecache[$sub . $base] = true;
        }
    }

    /**
     * Hashage des paramètres du fichier de Pelican_Cache pour créer les
     * sous-répertoires (2 niveaux)
     *
     * @param mixed $value
     *            (option) Paramètres du Pelican_Cache
     * @param bool $forceDir
     *            (option) Forcer la création d'un répertoire s'il n'y a
     *            pas de valeur
     * @param string $object
     *            (option) Nom de l'objet
     * @return string
     */
    public static function hashDir($value = "", $forceDir = true, $object = "")
    {
        $return = "";
        $temp = array();
        $length = 6;
        
        if (! is_array($value) && $value) {
            $value = array(
                $value
            );
        } elseif (! $value) {
            $value = array();
        }
        
        if ($forceDir) {
            $max = Pelican_Cache::nbSousNiveau;
        } else {
            $max = count($value) - 1;
            if ($max > Pelican_Cache::nbSousNiveau) {
                $max = Pelican_Cache::nbSousNiveau;
            }
        }
        
        for ($i = 0; $i <= $max; $i ++) {
            
            if (empty($value[$i])) {
                $value[$i] = str_pad("0", $length, "0", STR_PAD_LEFT);
            }
            
            $temp[] = str_pad(substr(($value[$i] ? Pelican_Text::md5($value[$i]) : "0"), 0, $length), $length, "0", STR_PAD_LEFT);
        }
        
        if ($object) {
            $object = "/" . strtolower(basename($object));
        }
        
        $return = $object . '/' . implode('/', $temp);
        
        return $return;
        
        /*
         * if (! is_array ( $value )) { $value = array ($value ); } if ($forceDir) { if (! isset ( $value [0] )) { $value [0] = "0000"; } if (! isset ( $value [1] )) { $value [1] = "0000"; } } //avant if (isset($value[0]) && $value[0]) { if (isset ( $value [0] ) && $value [0]) { $temp = "/" . str_pad ( substr ( ($value [0] ? Pelican_Text::md5 ( $value [0] ) : "0"), 0, 10), 10, "0", STR_PAD_LEFT ); } //avant if (isset($value[1]) && $value[1]) { if (isset ( $value [1] ) && $value [1]) { $temp2 = "/" . str_pad ( substr ( ($value [1] ? Pelican_Text::md5 ( $value [1] ) : "0"), 0, 10 ), 10, "0", STR_PAD_LEFT ); } if ($object) { $object = "/" . strtolower(basename($object)); } $return = $object . $temp . $temp2; return $return;
         */
    }

    /**
     * Création de la chaine de recherche de type wildcard pour le décache
     *
     * @param string $name
     *            (option) Nom (ou début) de fichier
     * @param string $dir
     *            (option) Répertoire de départ
     * @param string $root
     *            (option) Répertoire racine du Pelican_Cache
     * @return string
     */
    public static function getSearchPattern($name = "", $dir = "", $root = "")
    {
        $sep = "/";
        $sep .= str_repeat("*/", (Pelican_Cache::nbSousNiveau + 2 - substr_count($dir, "/")));
        
        return $root . $dir . $sep . $name;
    }

    /**
     * Hashage des paramètres d'un Pelican_Cache
     *
     * @param mixed $params
     *            Paramètres de Pelican_Cache
     * @return string
     */
    public static function getHash($params)
    {
        if (! is_array($params)) {
            $params = array(
                $params
            );
        }
        
        return "_" . implode("_", array_map(array(
            'Pelican_Text',
            'md5'
        ), $params));
    }
}

/**
 * Fonction de lecture/écriture d'un fichier de Pelican_Cache (avec ses
 * paramètres si nécessaire) et création d'un tableau du type array($id => $lib) à utiliser
 *
 * Avec une combo
 *
 * @param string $object
 *            Nom de l'objet de Pelican_Cache à utiliser (sans .php)
 * @param mixed $aParam
 *            (option) Tableau des paramètres d'identification du
 *            Pelican_Cache
 * @param string $id
 *            (option) Nom de l'entrée de $value servant d'index du
 *            tableau de retour
 * @param string $lib
 *            (option) Nom de l'entrée de $value servant de valeur du
 *            tableau de retour
 * @param string $sort
 *            (option) Type de tri à appliquer au tableau de retour
 *            (constantes PHP : SORT_REGULAR, SORT_NUMERIC, SORT_STRING)
 * @param string $optgroup
 *            (option) Nom du champ servant pour l'optgroup
 * @return array
 */
function getComboValuesFromCache($object, $aParam = "", $id = "id", $lib = "lib", $sort = "", $optgroup = "optgroup")
{
    $varValues = Pelican_Cache::fetch($object, $aParam);
    
    return arrayToCombo($varValues, $id, $lib, $sort, $optgroup);
}

/**
 * Création d'un objet hierarchique directement à partir d'un fichier de
 * Pelican_Cache
 *
 * @param string $object
 *            Nom de l'objet de Pelican_Cache
 * @param mixed $aParam
 *            Paramètres de Pelican_Cache
 * @param string $id
 *            (option) Identifiant unique pour l'objet hiérarchique
 * @return Pelican_Hierarchy
 */
function getTreeValuesFromCache($object, $aParam, $id = "id")
{
    pelican_import('Hierarchy');
    if (! is_array($aParam)) {
        $aParam = array(
            $aParam
        );
    }
    foreach ($aParam as $param) {
        $aValues[] = Pelican_Cache::fetch($object, $param);
    }
    $oHierarchy = Pelican_Factory::getInstance('Hierarchy', "profiling" . $id, "id", "pid");
    foreach ($aValues as $varValues) {
        $oHierarchy->addTabNode($varValues);
    }
    $oHierarchy->setOrder("order", "ASC");
    
    return $oHierarchy;
}
