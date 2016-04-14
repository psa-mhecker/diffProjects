<?php
/**
 * Couche d'abstraction MYSQLi.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 * Cette classe permet d'avoir un accès facilité à une base de
 * données MySQL.
 * Elle offre un certain nombre de fonctionnalités.
 *
 * Comme la création d'une connexion, l'exécution d'une requête
 * devant retourner un champ, une ligne ou un ensemble de ligne ou
 * encore l'affichage du temps d'exécution d'un requête, la récupération
 * du dernier enregistrement inséré au cours de la session...
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 26/01/2006
 */
class Pelican_Db_Mysqli extends Pelican_Db_Mysql
{
    /**
     * __DESC__.
     *
     * @access public
     *
     * @var __TYPE__
     */
    public static $nativeTypes = array(
        self::BIGINT => 'BIGINT',
        self::BINARY => 'TINYBLOB',
        self::BLOB => 'BLOB',
        self::BOOLEAN => 'INTEGER',
        self::CHAR => 'CHAR',
        self::CLOB => 'LONGTEXT',
        self::DATE => 'DATE',
        self::DATETIME => 'DATETIME',
        self::DECIMAL => 'DECIMAL',
        self::DOUBLE => 'DOUBLE',
        self::FLOAT => 'FLOAT',
        self::IDENTITY => 'INTEGER',
        self::INTEGER => 'INTEGER',
        self::LONGVARCHAR => 'TEXT',
        self::NUMERIC => 'NUMERIC',
        self::REAL => 'REAL',
        self::SMALLINT => 'SMALLINT',
        self::TIME => 'TIME',
        self::TIMESTAMP => 'TIMESTAMP',
        self::TINYTEXT => 'TINYTEXT',
        self::TINYINT => 'TINYINT',
        self::VARCHAR => 'VARCHAR',
    );

    /**
     * Nom de la base de données cible (pour les messages d'erreur).
     *
     * @access public
     *
     * @var string
     */
    public $databaseTitle = "MySQLi";

    /**
     * Base de données autorisant ou non l'utilisation de variables BIND.
     *
     * @access public
     *
     * @var bool
     */
    public $allowBind = false;

    /**
     * Commiter automatiquement ou non les requêtes.
     *
     * @access public
     *
     * @var bool
     */
    public $autoCommit = false;

    /**
     * __DESC__.
     *
     * @access public
     *
     * @var __TYPE__
     */
    public $transaction = false;

    /**
     * Constructeur.
     *
     * @access public
     *
     * @param string $databaseName
     *                             Le nom de la base de données
     * @param string $username
     *                             Le nom de l'utilisateur servant à la connexion
     * @param string $password
     *                             Le mot de passe de connexion
     * @param string $host
     *                             (option) L'adresse IP du serveur (optionnel) : 127.0.0.1
     *                             par défaut
     * @param string $port
     *                             (option) Le port de connexion (optionnel) : 3306 par
     *                             défaut
     * @param bool   $bExit
     *                             (option) __DESC__
     * @param bool   $persistency
     *                             (option) __DESC__
     *
     * @return database
     */
    public function __construct($databaseName, $username, $password, $host = "", $port = "", $bExit = true, $persistency = false)
    {
        $this->databaseName = $databaseName;
        $this->user = $username;
        $this->passwd = $password;
        $this->host = ($host ? $host : "127.0.0.1");
        $this->port = ($port ? $port : "3306");
        $this->persistency = $persistency;
        $this->info = $this->getInfo();
        // connexion à la base de données
        $this->id = mysqli_connect($this->host, $this->user, $this->passwd, $this->databaseName, $this->port) or $this->error(mysqli_connect_error());
        if (Pelican::$config["CHARSET"] == "UTF-8") {
            mysqli_set_charset($this->id, "utf8");
        }
        $this->beginTrans();
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::is_available()
     *
     * @return __TYPE__
     */
    public static function is_available()
    {
        return function_exists('mysqli_connect');
    }

    /**
     * @see Pelican_Db::beginTrans()
     */
    public function beginTrans()
    {
        if (! $this->autoCommit) {
            mysqli_autocommit($this->id, false);
            $this->transaction = true;
        } else {
            mysqli_autocommit($this->id, true);
        }
    }

    /**
     * @see Pelican_Db::close()
     */
    public function close()
    {
        if ($this->transaction) {
            if (! connection_aborted()) {
                $this->commit();
            } else {
                // $this->error($this->message("Connection interrompue"));
                $this->rollback();
            }
        }
        if (is_resource($this->id)) {
            mysqli_close($this->id) or $this->error($this->message("Impossible de fermer la connexion !"));
        }
    }

    /**
     * @see Pelican_Db::queryInit()
     */
    public function queryInit($query, $param = array(), $paramLob = array(), $debug = false)
    {
        $this->err = array();
        $indice = $query;
        Pelican_Profiler::start($indice, 'sql');
        Pelican_Log::control($query, 'sanscache');
        if (! Pelican_Db::isSelect($query) && ! $this->transaction) {
            $this->beginTrans();
        }
        $this->prepareBind($query, $param, $paramLob);
        $query = $this->masterSlaveSwitch($query);

        if ($debug) {
            debug($query);
        }
        // execution de la requête
        $this->data = array();
        $this->type = array();
        $this->name = array();
        $this->len = array();
        $this->query = self::replacePrefix($query);
        $this->result = mysqli_query($this->id, $this->query);
        if ($this->result) {
            // nb de lignes affectées
            $this->affectedRows = mysqli_affected_rows($this->id);
            if ($this->result instanceof mysqli_result) {
                // sauvegarde du nombre de champs renvoyés par la requête
                $this->fields = mysqli_num_fields($this->result);
                // sauvegarde du nombre de lignes renvoyés par la requête
                $this->rows = mysqli_num_rows($this->result);
                $tab = mysqli_fetch_fields($this->result);
                foreach ($tab as $i => $val) {
                    if (! empty($val->type)) {
                        $this->type[] = $val->type;
                    }
                    if (! empty($val->name)) {
                        $this->name[] = $val->name;
                    }
                    if (! empty($val->length)) {
                        $this->len[] = $val->length;
                    }
                }
            } else {
                // sauvegarde du nombre de champs renvoyés par la requête
                $this->fields = 0;
                // sauvegarde du nombre de lignes renvoyés par la requête
                $this->rows = 0;
                $this->type = array();
                $this->name = array();
                $this->len = array();
            }
            $return = true;
        } else {
            $this->err['no'] = mysqli_errno($this->id);
            $this->err['error'] = mysqli_error($this->id);
            $this->error($this->message("Impossible d'ex&eacute;cuter la requ&egrave;te :", $this->query));
            $return = false;
        }
        Pelican_Profiler::stop($indice, 'sql');

        return $return;
    }

    /**
     * @see Pelican_Db::query()
     */
    public function query($query, $param = array(), $paramLob = array(), $debug = false)
    {
        // si on veut afficher le temps d'execution de la requete : initialisation
        if ($this->debugTime) {
            $this->initTimeQuery();
        }
            // execution de la requête
        $temp = array();
        if ($this->queryInit($query, $param, $paramLob, $debug)) {
            if ($this->result instanceof mysqli_result) {
                for ($numLigne = 0; $numLigne < $this->rows; $numLigne ++) {
                    $temp = mysqli_fetch_array($this->result, MYSQLI_ASSOC) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
                    while (list($key, $val) = each($temp)) {
                        $data_temp[$key][$numLigne] = $val;
                    }
                    $this->data = $data_temp;
                }
            }
        }
        if ($this->debugTime) {
            $this->displayTimeQuery($query);
        }
    }

    /**
     * @see Pelican_Db::queryItem()
     */
    public function queryItem($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
    {
        $return = "";
        // suppression de l'ancien résultat
        $this->data = array();
        // execution de la requete
        if ($this->queryInit($query, $param, $paramLob, $debug) and $this->rows > 0) {
            // recuperation du résultat
            $tabResult = mysqli_fetch_array($this->result, ($light ? MYSQLI_NUM : MYSQLI_BOTH)) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            $keys = array_keys($tabResult);
            $this->data = $tabResult[$keys[0]];
            // liberation de la memoire
            mysqli_free_result($this->result);
        }
        // renvoie du résultat
        if (isset($this->data)) {
            /*
             * a controler
             */
            if (is_array($this->data) && count($this->data) > 0) {
                $this->data = $this->data[0];
            } else {
                $return = $this->affectedRows;
            }

            /*
             * a controler
             */
            $return = $this->data;
        }

        return $return;
    }

    /**
     * @see Pelican_Db::queryRow()
     */
    public function queryRow($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
    {
        // suppression de l'ancien résultat
        $this->data = array();
        // execution de la requete
        if ($this->queryInit($query, $param, $paramLob) and $this->rows > 0) {
            // recuperation du résultat
            $this->data = mysqli_fetch_array($this->result, ($light ? MYSQLI_ASSOC : MYSQLI_BOTH)) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            // liberation de la memoire
            mysqli_free_result($this->result);
        }
        // renvoie du résultat
        return ($this->data);
    }

    /**
     * @see Pelican_Db::queryTab()
     */
    public function queryTab($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
    {
        // suppression de l'ancien résultat
        $this->data = array();
        // execution de la requete
        if ($this->queryInit($query, $param, $paramLob, $debug) and $this->rows > 0) {
            // recuperation du résultat
            for ($numLigne = 0; $numLigne < $this->rows; $numLigne ++) {
                $this->data[$numLigne] = mysqli_fetch_array($this->result, ($light ? MYSQLI_ASSOC : MYSQLI_BOTH)) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            }
            // liberation de la memoire
            mysqli_free_result($this->result);
        }
        // renvoie du résultat
        return ($this->data);
    }

    /**
     * @see Pelican_Db::queryObj()
     */
    public function queryObj($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
    {
        // suppression de l'ancien résultat
        $this->data = array();
        // execution de la requete
        if ($this->queryInit($query, $param, $paramLob, $debug) and $this->rows > 0) {
            // recuperation du résultat
            for ($numLigne = 0; $numLigne < $this->rows; $numLigne ++) {
                $this->data[$numLigne] = mysqli_fetch_object($this->result) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            }
            // liberation de la memoire
            mysqli_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
        }
        // renvoie du résultat
        return ($this->data);
    }

    /**
     * @see Pelican_Db::commit()
     */
    public function commit()
    {
        return mysqli_commit($this->id);
    }

    /**
     * @see Pelican_Db::rollback()
     */
    public function rollback()
    {
        return mysqli_rollback($this->id);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getLastOid()
     *
     * @param string $query
     *                      (option) __DESC__
     *
     * @return __TYPE__
     */
    public function setAutoCommit($state = true)
    {
        $this->autoCommit = state;
        mysqli_autocommit($this->id, $state);
    }

    /**
     * @see Pelican_Db::getLastOid()
     */
    public function getLastOid($query = "")
    {
        // on met la requete en minuscule sur une ligne
        $query = preg_replace("/[\s\n\r]+/", " ", trim($query));
        // on recupere le nom de la table dans le cas d'un INSERT
        $matches = array();
        if (($query == "") || preg_match("/^INSERT\s+INTO\s+([^\s]+)\s+.*/i", $query, $matches)) {
            // on recupere le dernier enregistrement
            $this->lastInsertedId = mysqli_insert_id($this->id);
            if ($this->lastInsertedId < 0) {
                $this->lastInsertedId = $this->queryRow('SELECT LAST_INSERT_ID()');
            }

            return $this->lastInsertedId;
        }
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getNextId()
     *
     * @param __TYPE__ $table
     *                        __DESC__
     *
     * @return __TYPE__
     */
    public function getNextId($table)
    {
        /*
         * A compléter, pour vérifier les champs obligatoires
         */
        $sql = "show table status like '".$table."'";
        $result = $this->queryRow($sql);
        $id = $result['Auto_increment'] + 1;
        $sql = "alter table ".$table." AUTO_INCREMENT=".$id;
        $this->query($sql);

        return $id;
    }

    /**
     * @see Pelican_Db::getError()
     */
    public function getError()
    {
        if (! $this->err) {
            $this->err['no'] = mysqli_errno($this->id);
            $this->err['error'] = mysqli_error($this->id);
        }

        return array(
            "code" => $this->err['no'],
            "message" => $this->err['error'],
        );
    }

    /**
     * @see Pelican_Db::stringToSql()
     */
    public function stringToSql($value)
    {
        return mysqli_real_escape_string($this->id, $value);
    }
}
