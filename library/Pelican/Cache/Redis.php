<?php

include_once('Pelican/Cache/Interface.php');

/**
 * Classe Pelican_Cache_Redis
 * Gestion de la mise en Pelican_Cache d'objets ou de variables PHP sur un
 * serveur Redis
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Pelican_Cache_Redis implements Pelican_Cache_Interface
{
    /**
     * Liste de clients redis
     * @var array de type [instance][type] = client
     */
    protected static $clients;

    /**
     * Clé pour la BDD de caches expirables
     * @var string
     */
    public static $expireDB = 'EXPIRABLE';

    /**
     * Clé pour la BDD de caches persistents
     * @var string
     */
    public static $persistentDB = 'PERSISTENT';

    /**
     * Clé pour la BDD de caches session
     * @var string
     */
    public static $sessionDB = 'SESSION';

    /**
     * Clé pour la BDD de caches utilisateur
     * @var string
     */
    public static $userDB = 'USER';

    /**
     * Clé pour determiner le host master (en write access)
     * @var string
     */
    public static $writer = 'WRITE';

    /**
     * Clé pour déterminer le host slave (en read only)
     * @var string
     */
    public static $reader = 'READ';

    /**
     * Default save instance
     *
     * @var string
     */
    public static $saveInstance = 'DEFAULT';

    /**
     * Default decache instance
     *
     * @var string
     */
    public static $decacheInstance = 'DEFAULT';

    const DEFAULT_INSTANCE = 'DEFAULT';
    /**
     * Cache content
     *
     * @var string
     */
    protected $content;

    /**
     * Constructeur
     *
     * @access public
     * @return Pelican_Cache_File
     */
    public function __construct()
    {

    }

    /**
     * Récupération de la liste des clés de Pelican_Cache générées
     *
     */
    public function getKeyList()
    {

    }

    /**
     * Création de la syntaxe d'un nom de fichier de Pelican_Cache en fonction de ses paramètres
     *
     * @param string $script Nom du script appelant (un "/" sera transformer en "_")
     * @param mixed $params Paramètres du Pelican_Cache
     * @param boolean $binaryCache Pelican_Cache de type binaire ou non
     * @param string $complementCache Complément du Pelican_Cache
     * @return string
     */
    public function getName($script = "", $params = array(), $binaryCache = false, $complementCache = "")
    {
        $result = str_replace('/', '_', $script);
        if (strtolower(Pelican::$config['TYPE_ENVIRONNEMENT']) == 'dev') {
            $prefix = Pelican::$config["USER_DEV"];
            $result = $prefix . $result;
        }
        if ($binaryCache) {
            $complementCache = "." . Pelican::$config["IM_EXT"];
        }
        if ($params) {
            $result .= Pelican_Cache::getHash($params);
        }
        $result .= $complementCache;

        return $result;
    }

    /*
     * @param mixed $params Paramètres du Pelican_Cache
     * @return string
     */
    public function getPath($params, $object = "")
    {
        return '';
    }

    /**
     * Vérification de l'existence d'un Pelican_Cache
     *
     * @param string $path Chemin physique
     * @param string $lifeTime durée de vie du cache
     * @return boolean
     */
    public function isAlive($path = "", $lifeTime = null)
    {
        $db = self::$persistentDB;
        if ($lifeTime != null) {
            $db = self::$expireDB;
        }

        if (Pelican_Cache::$currentInstance && self::$saveInstance == 'DEFAULT') {
            $instance = Pelican_Cache::$currentInstance;
        } else {
            $instance = self::$saveInstance;
        }

        $this->content = self::getClient(self::$reader, $db, $instance)->get($path);

        return ($this->content !== false);
    }

    /**
     * Lecture d'un Pelican_Cache
     *
     * @param string $key Clé de cache
     * @param string $lifeTime durée de vie du cache
     * @return mixed le contenu de la variable stockée
     */
    public function readCache($path, $lifeTime = null)
    {

        $db = self::$persistentDB;
        if ($lifeTime) {
            $db = self::$expireDB;
        }

        if (Pelican_Cache::$currentInstance && self::$saveInstance == 'DEFAULT') {
            $instance = Pelican_Cache::$currentInstance;
        } else {
            $instance = self::$saveInstance;
        }

        $client = self::getClient(self::$reader, $db, $instance);

        if ($this->content = $client->get($path)) {

            $this->size = strlen($this->content);
        }

        return $this->content;
    }

    /**
     * Supprime les application/caches répondant au pattern recherché
     *
     * @param string $name
     * @param string $dir
     * @param string $root
     * @param boolean $defer
     * @param boolean $log
     * @return null|int DB Pelican_Cache ID
     */
    public static function remove($name = "", $dir = "", $root = "", $defer = false, $log = false, $direct = false)
    {

        if (strtolower(Pelican::$config['TYPE_ENVIRONNEMENT']) == 'dev') {
            $prefix = Pelican::$config["USER_DEV"];
            $name   = $prefix . $name;
        }
        if ($name) {


            if (\Pelican::$config['BACKEND'] || self::$saveInstance == self::$decacheInstance) {

                $script = sprintf(
                    "redis.call('select', %s); return redis.call('DEL', unpack(redis.call('KEYS', ARGV[1] .. '*')))",
                    0
                );
                $client = self::getClient(self::$writer, null, self::$saveInstance);
                $client->eval($script, array($name));
                $script = sprintf(
                    "redis.call('select', %s); return redis.call('DEL', unpack(redis.call('KEYS', ARGV[1] .. '*')))",
                    1
                );
                $client = self::getClient(self::$writer, null, self::$saveInstance);
                $client->eval($script, array($name));

            }

            // Si les instances diffèrent
            if (self::$saveInstance != self::$decacheInstance) {
                /* A supprimer lorsque le batch de décache sera actif */
                $script = sprintf(
                    "redis.call('select', %s); return redis.call('DEL', unpack(redis.call('KEYS', ARGV[1] .. '*')))",
                    0
                );
                $client = self::getClient(self::$writer, null, self::$decacheInstance);
                $client->eval($script, array($name));
                $script = sprintf(
                    "redis.call('select', %s); return redis.call('DEL', unpack(redis.call('KEYS', ARGV[1] .. '*')))",
                    1
                );
                $client = self::getClient(self::$writer, null, self::$decacheInstance);
                $client->eval($script, array($name));
            }
        }
    }

    /**
     * Insertion dans la liste des clés de Pelican_Cache d'une entrée
     *
     * @param string $key
     */
    public function setKeyList($key)
    {
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Cache_Interface::setLifeTime()
     * @param __TYPE__ $lifeTime __DESC__
     * @return __TYPE__
     */
    public function setLifeTime($lifeTime)
    {
        $time = null;

        switch ($lifeTime) {
            case UNLIMITED:
                $time = mktime(0, 0, 0, 01, 01, 2020);
                break;
            case WEEK:
                $time = mktime(0, 0, 0, date('m'), date('d') + 7, date('Y'));
                break;
            case MONTH:
                $time = mktime(0, 0, 0, date('m') + 1, date('d'), date('Y'));
                break;
            case YEAR:
                $time = mktime(0, 0, 0, date('m'), date('d'), date('Y') + 1);
                break;
            case FIVEMIN:
                $time = mktime(date('H'), date('i') + 5, 0, date('m'), date('d'), date('Y'));
                break;
            case TENMIN:
                $time = mktime(date('H'), date('i') + 10, 0, date('m'), date('d'), date('Y'));
                break;
            case ONEMIN:
                $time = mktime(date('H'), date('i') + 1, 0, date('m'), date('d'), date('Y'));
                break;
            case FIFTEENMIN:
                $time = mktime(date('H'), date('i') + 15, 0, date('m'), date('d'), date('Y'));
                break;
            case ONEHOUR:
                $time = mktime(date('H') + 1, date('i'), 0, date('m'), date('d'), date('Y'));
                break;
            case SEVENHOURS:
                $time = mktime(date('H') + 7, date('i'), 0, date('m'), date('d'), date('Y'));
                break;
            case DAY:
                $time = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y'));
                break;
            default:
                if (is_int($lifeTime)) {
                    $time = $lifeTime;
                }
                break;
        }

        return $time;

    }

    /**
     * Stockage d'une entrée de Pelican_Cache sur Redis
     *
     * @param string $path Chemin physique du fichier + hash des parametres
     * @param string $content Contenu du fichier à écrire
     * @return boolean
     */
    public function storeValue($key, $content = "", $time = "")
    {
        if (Pelican_Cache::$currentInstance && self::$saveInstance == 'DEFAULT') {
            $instance = Pelican_Cache::$currentInstance;
        } else {
            $instance = self::$saveInstance;
        }

        if ($key) {
            if ($time != "") {
                $client = self::getClient(self::$writer, self::$expireDB, $instance);
                $client->set($key, $content);

                $client->expireAt($key, $time);
            } else {
                $client = self::getClient(self::$writer, self::$persistentDB, $instance);
                $client->set($key, $content);
                // Par défaut les clés expires à 4 jour (mais reste dans la DB persistente pour identifier les clés CMS
                $client->expireAt($key, time() + 4 * 60 * 60 * 24);
            }
        }
    }

    /**
     * Retourne un client Redis identifié par la clé passée en parametre
     * Si une dbKey est passée, la base de données correspondante sera
     * sélectionnée
     *
     * @param string $key
     * @param string $dbKey
     * @param string $instance
     * @param bool   $retry
     *
     * @return Redis Une instance redis
     */
    public static function getClient($key, $dbKey = '', $instance = self::DEFAULT_INSTANCE,  $retry = false)
    {
        // On récupère l'instance actuellement par Défaut
        if ($instance == self::DEFAULT_INSTANCE) {
            $instance = self::$saveInstance;
        }

        if (!isset(self::$clients[$instance][$key])) {
            try {
                $redis = new Redis();

                $redis->pconnect(
                    Pelican::$config['REDIS'][$instance][$key]['HOST'],
                    Pelican::$config['REDIS'][$instance][$key]['PORT']
                );

                self::$clients[$instance][$key] = $redis;

            } catch(\Exception $e) {

                if ($retry === true) {
                    throw new \Exception($e->getMessage()." - URL : http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
                }

                return self::getClient($key, $dbKey, $instance, true);
            }
        }

        try {
            self::$clients[$instance][$key]->select(Pelican::$config['REDIS']['DATABASES'][$dbKey]);
            return self::$clients[$instance][$key];

        } catch(\Exception $e) {

            if ($retry === true) {
                throw new \Exception($e->getMessage()." - URL : http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            }

            return self::getClient($key, $dbKey, $instance, true);
        }
    }

    public function deleteAll()
    {
        $instances = Pelican::$config['REDIS']['INSTANCES'];
        if (is_array($instances)) {
            foreach ($instances as $instance) {
                self::getClient(self::$writer, self::$persistentDB, $instance)->flushDB();
                self::getClient(self::$writer, self::$expireDB, $instance)->flushDB();
            }
        }
    }
}