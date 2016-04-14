<?php

    include_once 'Pelican/Cache/Interface.php';

    /**
     * Classe Pelican_Cache_Redis
     * Gestion de la mise en Pelican_Cache d'objets ou de variables PHP sur un
     * serveur Redis.
     *
     * @author Pascal DENIS <pascal.denis@businessdecision.com>
     */
    class Pelican_Cache_Redis implements Pelican_Cache_Interface
    {
        /**
         * Liste de clients redis.
         *
         * @var array de type [instance][type] = client
         */
        protected static $clients;

        /**
         * Clé pour la BDD de caches expirables.
         *
         * @var string
         */
        public static $expireDB = 'EXPIRABLE';

        /**
         * Clé pour la BDD de caches persistents.
         *
         * @var string
         */
        public static $persistentDB = 'PERSISTENT';

        /**
         * Clé pour la BDD de caches session.
         *
         * @var string
         */
        public static $sessionDB = 'SESSION';

        /**
         * Clé pour determiner le host master (en write access).
         *
         * @var string
         */
        public static $writer = 'WRITE';

        /**
         * Clé pour déterminer le host slave (en read only).
         *
         * @var type
         */
        public static $reader = 'READ';

        public static $channel = 'CHANNEL_1';

        protected $saveInstance = 'DEFAULT';

        /**
         * Constructeur.
         *
         * @access public
         *
         * @return Pelican_Cache_File
         */
        public function __construct()
        {
        }

        /**
         * Récupération de la liste des clés de Pelican_Cache générées.
         */
        public function getKeyList()
        {
        }

        /**
         * Création de la syntaxe d'un nom de fichier de Pelican_Cache en fonction de ses paramètres.
         *
         * @param string $script Nom du script appelant (un "/" sera transformer en "_")
         * @param mixed $params Paramètres du Pelican_Cache
         * @param boolean $binaryCache Pelican_Cache de type binaire ou non
         * @param string $complementCache Complément du Pelican_Cache
         *
         * @return string
         */
        public function getName($script = "", $params = array(), $binaryCache = false, $complementCache = "")
        {
            $result = str_replace('/', '_', $script);

            if ($binaryCache) {
                $complementCache = ".".Pelican::$config["IM_EXT"];
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
            return Pelican_Cache::getHash($params);
        }

        /**
         * Vérification de l'existence d'un Pelican_Cache.
         *
         * @param string $path Chemin physique
         *
         * @return boolean
         */
        public function isAlive($path = "")
        {
            return (self::getClient(self::$writer)->ttl($path) > 0);
        }

        /**
         * Lecture d'un Pelican_Cache.
         *
         * @param string $key Clé de cache
         *
         * @return mixed le contenu de la variable stockée
         */
        public function readCache($path)
        {
            $sReturn = '';
            if ($this->duration) {
                $client = self::getClient(self::$reader, self::$expireDB, $this->saveInstance);
            } else {
                $client = self::getClient(self::$reader, self::$persistentDB, $this->saveInstance);
            }
            if ($sContent = $client->get($path)) {
                $this->size = strlen($sContent);
                $sReturn = $sContent;
            }

            return $sReturn;
        }

        /**
         * Supprime les application/caches répondant au pattern recherché.
         *
         * @param string $name
         * @param string $dir
         * @param string $root
         * @param boolean $defer
         * @param boolean $log
         *
         * @return null|int DB Pelican_Cache ID
         */
        public static function remove($name = "", $dir = "", $root = "", $defer = false, $log = false, $direct = false)
        {
            $client = self::getClient(self::$writer, self::$persistentDB, $this->saveInstance);
            if ($name) {
                $this->publish('redis-cli SELECT '.Pelican::$config['REDIS']['DATABASES'][self::$persistentDB].' | redis-cli KEYS "prefix:'.$name.'*" | xargs redis-cli DEL');
                // $client->delete($client->keys($name.'*'));
            }
        }

        /**
         * Insertion dans la liste des clés de Pelican_Cache d'une entrée.
         *
         * @param string $key
         */
        public function setKeyList($key)
        {
        }

        /**
         * __DESC__.
         *
         * @access public
         *
         * @see Pelican_Cache_Interface::setLifeTime()
         *
         * @param __TYPE__ $lifeTime __DESC__
         *
         * @return __TYPE__
         */
        public function setLifeTime($lifeTime)
        {
            switch ($lifeTime) {
                case UNLIMITED:
                {
                    $time = mktime(0, 0, 0, 01, 01, 2020);

                    //break;
                }
                case WEEK:
                {
                    $time = mktime(0, 0, 0, date("m"), date("d") + 7, date("Y"));

                    //break;
                }
                case MONTH:
                {
                    $time = mktime(0, 0, 0, date("m") + 1, date("d"), date("Y"));

                    //break;
                }
                case YEAR:
                {
                    $time = mktime(0, 0, 0, date("m"), date("d"), date("Y") + 1);

                    //break;
                }
                case DAY:
                default:
                {
                    $time = mktime(0, 0, 0, date("m"), date("d") - 2, date("Y"));
                    break;
                }
            }
            if ($time) {
                $lifeTime = $time;
            }

            return $lifeTime;
        }

        /**
         * Stockage d'une entrée de Pelican_Cache sur Redis.
         *
         * @param string $path Chemin physique du fichier + hash des parametres
         * @param string $content Contenu du fichier à écrire
         *
         * @return boolean
         */
        public function storeValue($key, $content = "", $time = "")
        {
            if ($content !== null && $key) {
                if ($time) {
                    $client = self::getClient(self::$writer, self::$expireDB, $this->saveInstance);
                    $client->set($key, $content);
                    $client->setTimeout($key, $this->setLifeTime($time));
                } else {
                    $client = self::getClient(self::$writer, self::$persistentDB, $this->saveInstance);
                    $client->set($key, $content);
                }
            }
        }

        /**
         * Retourne un client Redis identifié par la clé passée en parametre
         * Si une dbKey est passée, la base de données correspondante sera
         * sélectionnée.
         *
         * @param string $key
         * @param string $dbKey
         *
         * @return Redis Une instance redis
         */
        public static function getClient($key, $dbKey = '', $instance = 'DEFAULT')
        {
            if (!isset(self::$clients[$instance][$key])) {
                try {
                    $oRedis = new Redis();
                    $oRedis->connect(
                    Pelican::$config['REDIS'][$instance][$key]['HOST'],
                        Pelican::$config['REDIS'][$instance][$key]['PORT']);

                    self::$clients[$instance][$key] = $oRedis;
                } catch (\Exception $e) {
                    throw $e;
                }
            }
            if ($dbKey) {
                self::$clients[$instance][$key]->select(Pelican::$config['REDIS']['DATABASES'][$dbKey]);
            }

            return self::$clients[$instance][$key];
        }

        public function deleteAll()
        {
            $instances = array_keys(Pelican::$config['REDIS']);
            if (is_array($instances)) {
                foreach ($instances as $instance) {
                    $client = self::getClient(self::$master, self::$persistentDB, $instance);
                    $client->flushDB();
                    $client = self::getClient(self::$master, self::$expirableDB, $instance);
                    $client->flushDB();
                }
            }
        }

        public function publish($sCommand)
        {
            if ($sCommand) {
                $client = self::getClient(self::$writer);
                $client->publish(self::$channel, $sCommand);
            }
        }

        public function subscribe()
        {
        }
    }
