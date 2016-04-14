<?php

/**
 * Couche d'abstraction MYSQL.
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
 * @since 15/08/2001
 *
 * @version 2.0
 */
class Pelican_Db_Mysql extends Pelican_Db
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
    public $databaseTitle = "MySQL";

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

    public static $useMaster = false;

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
        parent::__construct($databaseName, $username, $password, $host, $port, $bExit);
        $this->databaseName = $databaseName;
        $this->user = $username;
        $this->passwd = $password;
        $this->host = ($host ? $host : "127.0.0.1");
        $this->port = ($port ? $port : "3306");
        $this->persistency = $persistency;
        $this->info = $this->getInfo();
        // connexion à la base de données
        if ($this->persistency) {
            $this->id = mysql_pconnect($this->host.":".$this->port, $this->user, $this->passwd) or $this->error($this->message("Impossible de connecter au serveur ".$this->databaseTitle));
        } else {
            $this->id = mysql_connect($this->host.":".$this->port, $this->user, $this->passwd) or $this->error($this->message("Impossible de connecter au serveur ".$this->databaseTitle));
        }
        mysql_select_db($this->databaseName, $this->id) or $this->error($this->message("Impossible de s&eacute;lectionner la base ".$this->databaseTitle));
        if (Pelican::$config["CHARSET"] == "UTF-8") {
            mysql_set_charset('utf8', $this->id);
        }
    }

    public function masterSlaveSwitch($query)
    {
        $host = '';
        $pref = '';

        // if queries are forced to master
        if (self::$useMaster) {
            $host = MYSQLND_MS_MASTER_SWITCH;
        } else {
            if (function_exists('mysqlnd_ms_query_is_select')) {
                if (mysqlnd_ms_query_is_select($query) == MYSQLND_MS_QUERY_USE_MASTER) {
                    $host = MYSQLND_MS_MASTER_SWITCH;
                    self::$useMaster = true; // to force all next queries to master
                }
            }
        }

        if ($host) {
            $pref = "/*".$host."*/";
        }

        $return = $pref.$query;

        /*
         * if (function_exists('mysqlnd_ms_is_select')) { $host = ''; $pref = ''; switch (mysqlnd_ms_query_is_select($query)) { case MYSQLND_MS_QUERY_USE_MASTER: $host = "master"; break; case MYSQLND_MS_QUERY_USE_SLAVE: $host = "slave"; break; case MYSQLND_MS_QUERY_USE_LAST_USED: $host = "last_used"; break; default: $host = ''; break; } }
         */

        return $return;
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
        return function_exists('mysql_connect');
    }

    /**
     * @see Pelican_Db::beginTrans()
     */
    public function beginTrans()
    {
        if (! $this->autoCommit) {
            $this->result = mysql_query('SET AUTOCOMMIT=0', $this->id);
            $this->result = mysql_query("START TRANSACTION");
            $this->transaction = true;
        } else {
            $this->result = mysql_query('SET AUTOCOMMIT=1', $this->id);
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
            mysql_close($this->id) or $this->error($this->message("Impossible de fermer la connexion !"));
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
        $this->result = mysql_query($this->query, $this->id);
        if ($this->result) {
            // nb de lignes affectées
            $this->affectedRows = mysql_affected_rows();
            if (is_resource($this->result)) {
                // sauvegarde du nombre de champs renvoyés par la requête
                $this->fields = mysql_num_fields($this->result);
                // sauvegarde du nombre de lignes renvoyés par la requête
                $this->rows = mysql_num_rows($this->result);
                for ($i = 0; $i < $this->fields; $i ++) {
                    $this->type[] = mysql_field_type($this->result, $i);
                    $this->name[] = mysql_field_name($this->result, $i);
                    $this->len[] = mysql_field_len($this->result, $i);
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
            $this->err['no'] = mysql_errno($this->id);
            $this->err['error'] = mysql_error($this->id);
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
            if (is_resource($this->result)) {
                for ($numLigne = 0; $numLigne < $this->rows; $numLigne ++) {
                    $temp = mysql_fetch_array($this->result, MYSQL_ASSOC) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
                    while (list($key, $val) = each($temp)) {
                        $dataTemp[$key][$numLigne] = $val;
                    }
                    $this->data = $dataTemp;
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
            $tabResult = mysql_fetch_array($this->result, ($light ? MYSQL_NUM : MYSQL_BOTH)) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            $keys = array_keys($tabResult);
            $this->data = $tabResult[$keys[0]];
            // liberation de la memoire
            mysql_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
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
        if ($this->queryInit($query, $param, $paramLob, $debug) and $this->rows > 0) {
            // recuperation du résultat
            $this->data = mysql_fetch_array($this->result, ($light ? MYSQL_ASSOC : MYSQL_BOTH)) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            // liberation de la memoire
            mysql_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
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
                $this->data[$numLigne] = mysql_fetch_array($this->result, ($light ? MYSQL_ASSOC : MYSQL_BOTH)) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            }
            // liberation de la memoire
            mysql_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
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
                $this->data[$numLigne] = mysql_fetch_object($this->result) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            }
            // liberation de la memoire
            mysql_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
        }
        // renvoie du résultat
        return ($this->data);
    }

    /**
     * @see Pelican_Db::commit()
     */
    public function commit()
    {
        $return = mysql_query("COMMIT");

        return $return;
    }

    /**
     * @see Pelican_Db::rollback()
     */
    public function rollback()
    {
        $return = mysql_query("ROLLBACK");

        return $return;
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
    public function getLastOid($query = "")
    {
        // on met la requete en minuscule sur une ligne
        $query = preg_replace("/[\s\n\r]+/", " ", trim($query));
        // on recupere le nom de la table dans le cas d'un INSERT
        $matches = array();
        if (($query == "") || preg_match("/^INSERT\s+INTO\s+([^\s]+)\s+.*/i", $query, $matches)) {
            // on recupere le dernier enregistrement
            $this->lastInsertedId = mysql_insert_id();
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
     * @param string $table
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
            $this->err['no'] = mysql_errno($this->id);
            $this->err['error'] = mysql_error($this->id);
        }

        return array(
            "code" => $this->err['no'],
            "message" => $this->err['error'],
        );
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getInfo()
     *
     * @return __TYPE__
     */
    public function getInfo()
    {
        $return["type"] = $this->databaseTitle;
        $return["host"] = $_SERVER["SERVER_NAME"];
        $return["instance"] = $this->databaseName;

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getDbInfo()
     *
     * @param __TYPE__ $type
     *                       __DESC__
     * @param string   $name
     *                       (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getDbInfo($type, $name = "")
    {
        $return = array();
        $i = 0;
        $aComposition = explode("_", $name);
        $shortName = str_replace($aComposition[0]."_", "", $name);
        switch ($type) {
            case 'infos':
                {
                    $return["description"] = $this->queryItem("select version()");
                    if (preg_match('/([0-9]+\.([0-9\.])+)/', $return["description"], $arr)) {
                        $return["version"] = $arr[1];
                    }
                    $return["type"] = $this->databaseTitle;
                    $return["host"] = $_SERVER["SERVER_NAME"];
                    $return["instance"] = $this->databaseName;
                    $query = 'SHOW VARIABLES';
                    $result = $this->queryTab($query);
                    foreach ($result as $ligne) {
                        $return[$ligne["Variable_name"]] = $ligne["Value"];
                    }
                    break;
                }
            case 'tables':
                {
                    $query = "show full tables FROM `" . $this->databaseName . "` where Table_type='BASE TABLE'";
                    $this->query($query);
                    $return = $this->data['Tables_in_'.$this->databaseName];
                    break;
                }
            case 'views':
                {
                    $query = "show full tables FROM `" . $this->databaseName . "` where Table_type='VIEW'";
                    $this->query($query);
                    $return = $this->data['Tables_in_'.$this->databaseName];
                    break;
                }
            case 'fields':
                {
                    $foreignKeys = $this->getDbInfo('foreign_keys', $name);
                    $query = 'SHOW COLUMNS FROM '.$name;
                    $result = $this->queryTab($query);
                    foreach ($result as $ligne) {
                        $size = null;
                        $precision = null;
                        $return[$i] = array();
                        $return[$i]["field"] = $ligne["Field"];
                        $matches = array();
                        if (preg_match('/^(\w+)[\(]?([\d,]*)[\)]?( |$)/', $ligne['Type'], $matches)) {
                            $nativeType = $matches[1];
                            if ($matches[2]) {
                                if (($cpos = strpos($matches[2], ',')) !== false) {
                                    $size = (int) substr($matches[2], 0, $cpos);
                                    $precision = (int) substr($matches[2], $cpos + 1);
                                } else {
                                    $size = (int) $matches[2];
                                }
                            }
                        } elseif (preg_match('/^(\w+)\(/', $ligne['Type'], $matches)) {
                            $nativeType = $matches[1];
                        } else {
                            $nativeType = $ligne['Type'];
                        }
                        $return[$i]["type"] = $nativeType;
                        $return[$i]["length"] = $size;
                        if ($return[$i]['type'] == "decimal" && $return[$i]["length"] == 38) {
                            $return[$i]['type'] = "int";
                        }
                        if ($return[$i]['type'] == "int") {
                            $return[$i]["length"] = null;
                        }
                        $return[$i]["precision"] = $precision;
                        $return[$i]["null"] = ($ligne["Null"] == "YES" ? true : false);
                        $return[$i]["default"] = (is_null($ligne["Default"]) ? 'NULL' : ($ligne["Default"] === '0' ? '0' : $ligne["Default"]));
                        if ($return[$i]["default"] == 'NULL' && ! $return[$i]["null"]) {
                            $return[$i]["default"] = "";
                        }
                        $return[$i]["key"] = ($ligne["Key"] == "PRI");
                        if (isset($foreignKeys[$return[$i]["field"]])) {
                            $return[$i]["fkey"] = $foreignKeys[$return[$i]["field"]]["parent_table"].'.'.$foreignKeys[$return[$i]["field"]]["parent_field"];
                        }
                        $return[$i]["extra"] = $ligne["Extra"];
                        $return[$i]["increment"] = ($ligne["Extra"] == "auto_increment");
                        $return[$i]["sequence"] = false;
                        $i ++;
                    }
                    break;
                }
            case 'keys':
                {

                    /*
                     * réordenancement des colonnes pour être en accord avec la structure de la table
                     */
                    $query = 'SHOW COLUMNS FROM '.$name;
                    $fields = $this->queryTab($query);
                    $i = 0;
                    foreach ($fields as $f) {
                        $orderField[strtoupper($f['Field'])] = $i ++;
                    }
                    $query = "SHOW KEYS FROM ".$name;
                    $result = $this->queryTab($query);
                    foreach ($result as $ligne) {
                        if ($ligne["Key_name"] == 'PRIMARY') {
                            $return[$orderField[strtoupper($ligne["Column_name"])]] = $ligne["Column_name"];
                        }
                    }
                    ksort($return);
                    break;
                }
            case 'foreign_keys':
                {

                    /*
                     * Analyse syntaxique des noms de champ
                     */
                    /*
                     * $query = "SELECT distinct COLUMN_NAME child_field, REFERENCED_TABLE_NAME parent_table, REFERENCED_COLUMN_NAME parent_field FROM information_schema.TABLE_CONSTRAINTS tc inner join information_schema.KEY_COLUMN_USAGE kcu on (tc.CONSTRAINT_NAME =kcu.CONSTRAINT_NAME and tc.TABLE_SCHEMA = kcu.TABLE_SCHEMA and tc.TABLE_NAME = kcu.TABLE_NAME and tc.CONSTRAINT_SCHEMA = kcu.CONSTRAINT_SCHEMA) WHERE tc.CONSTRAINT_SCHEMA = '" . $this->databaseName . "' and tc.TABLE_NAME = '" . $name . "' and CONSTRAINT_TYPE = 'FOREIGN KEY'";
                     */
                    $query = "SELECT DISTINCT k.`CONSTRAINT_NAME`, k.`COLUMN_NAME` as child_field, k.`REFERENCED_TABLE_NAME` as parent_table,
k.`REFERENCED_COLUMN_NAME` as parent_field
FROM information_schema.key_column_usage k  WHERE k.table_name = '".$name."'
 AND k.table_schema = '".$this->databaseName."'
 AND k.`REFERENCED_COLUMN_NAME` is not NULL";
                    $result = $this->queryTab($query);
                    foreach ($result as $ligne) {
                        $return[$ligne["child_field"]]["child_field"] = $ligne["child_field"];
                        $return[$ligne["child_field"]]["parent_field"] = $ligne["parent_field"];
                        $return[$ligne["child_field"]]["parent_table"] = $ligne["parent_table"];
                    }
                    break;
                }
            case 'indexes':
                {
                    // $query = 'SHOW INDEX FROM '.$name;
                    $query = "SHOW KEYS FROM ".$name." WHERE Key_name != 'PRIMARY'";
                    $result = $this->queryTab($query);
                    foreach ($result as $ligne) {
                        $return[$ligne["Key_name"]]["name"] = $ligne["Key_name"];
                        $return[$ligne["Key_name"]]["ordered"] = ($ligne["Collation"] == 'A' ? true : false);
                        $return[$ligne["Key_name"]]["cardinality"] = $ligne["Cardinality"];
                        $return[$ligne["Key_name"]]["type"] = $ligne['Index_type'];
                        $return[$ligne["Key_name"]]["fields"][$ligne["Seq_in_index"]] = $ligne["Column_name"];
                    }
                    break;
                }
            case 'functions':
                {
                    return;
                    break;
                }
            default:
                {
                    return;

                /*
                 * fin du case
                 */
                }
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getNow()
     *
     * @return __TYPE__
     */
    public function getNow()
    {
        return "now()";
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getDateDiffClause()
     *
     * @param __TYPE__ $date1
     *                        __DESC__
     * @param __TYPE__ $date2
     *                        __DESC__
     *
     * @return __TYPE__
     */
    public function getDateDiffClause($date1, $date2)
    {
        $return = " DATEDIFF(".$date1.",".$date2.") ";

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getDateAddClause()
     *
     * @param __TYPE__ $date
     *                           __DESC__
     * @param __TYPE__ $interval
     *                           __DESC__
     *
     * @return __TYPE__
     */
    public function getDateAddClause($date, $interval)
    {
        $return = " DATE_ADD(".$date.", INTERVAL ".$interval." DAY) ";

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::dateStringToSql()
     *
     * @param string $strChaine
     *                          __DESC__
     * @param bool   $hour
     *                          (option) __DESC__
     *
     * @return __TYPE__
     */
    public function dateStringToSql($strChaine, $hour = true)
    {
        $hour = array();
        $arr = explode("/", $strChaine);
        if (valueExists($arr, 2)) {
            $hour = explode(" ", $arr[2]);
            $arr[2] = $hour[0];
        }
        $complement = "";
        if (valueExists($hour, 1)) {
            $complement = " ".$hour[1];
        }
        if (count($arr) > 1) {
            switch ($this->dateFormat) {
                case "MM/DD/YYYY":
                    {
                        $tempDate = "'".$arr[2]."-".$arr[0]."-".$arr[1].$complement."'";
                        break;
                    }
                default:
                    {
                        $tempDate = "'".$arr[2]."-".$arr[1]."-".$arr[0].$complement."'";
                        break;
                    }
            }
        } else {
            $tempDate = "'".$strChaine."'";
        }

        return ($tempDate);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::dateSqlToString()
     *
     * @param __TYPE__ $dateField
     *                             __DESC__
     * @param bool     $hour
     *                             (option) __DESC__
     * @param string   $complement
     *                             (option) __DESC__
     *
     * @return __TYPE__
     */
    public function dateSqlToString($dateField, $hour = false, $complement = "")
    {
        if ($hour) {
            $complement = " %H:%i";
        }
        if (! isset($this->dateFormat)) {
            $this->dateFormat = "DD/MM/YYYY";
        }
        switch ($this->dateFormat) {
            case "MM/DD/YYYY":
                {
                    $tempDate = "DATE_FORMAT(".$dateField.",'%m/%d/%Y".$complement."')";
                    break;
                }
            default:
                {
                    $tempDate = "DATE_FORMAT(".$dateField.",'%d/%m/%Y".$complement."')";
                    break;
                }
        }

        return ($tempDate);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::dateSqlToStringShort()
     *
     * @param __TYPE__ $dateField
     *                            __DESC__
     *
     * @return __TYPE__
     */
    public function dateSqlToStringShort($dateField)
    {
        if (! isset($this->dateFormat)) {
            $this->dateFormat = "DD/MM/YYYY";
        }
        switch ($this->dateFormat) {
            case "MM/DD/YYYY":
                {
                    $tempDate = "DATE_FORMAT(".$dateField.",'%m/%d/%y')";
                    break;
                }
            default:
                {
                    $tempDate = "DATE_FORMAT(".$dateField.",'%d/%m/%y')";
                    break;
                }
        }

        return ($tempDate);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::dateToYear()
     *
     * @param __TYPE__ $dateField
     *                            __DESC__
     *
     * @return __TYPE__
     */
    public function dateToYear($dateField)
    {
        $tempDate = "DATE_FORMAT(".$dateField.",'%Y')";

        return $tempDate;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::dateToMonth()
     *
     * @param __TYPE__ $dateField
     *                            __DESC__
     *
     * @return __TYPE__
     */
    public function dateToMonth($dateField)
    {
        $tempDate = "DATE_FORMAT(".$dateField.",'%m')";

        return $tempDate;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::stringToSql()
     *
     * @param __TYPE__ $value
     *                        __DESC__
     *
     * @return __TYPE__
     */
    public function stringToSql($value)
    {
        return mysql_real_escape_string($value);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getLimitedSQL()
     *
     * @param __TYPE__ $query
     *                         __DESC__
     * @param __TYPE__ $min
     *                         __DESC__
     * @param __TYPE__ $length
     *                         __DESC__
     *
     * @return __TYPE__
     */
    public function getLimitedSQL($query, $min, $length)
    {
        $return = $query." LIMIT ".($min - 1).",".$length;

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getCountSQL()
     *
     * @param __TYPE__ $query
     *                              __DESC__
     * @param __TYPE__ $countFields
     *                              __DESC__
     *
     * @return __TYPE__
     */
    public function getCountSQL($query, $countFields)
    {
        $deb = strpos(strToLower($query), "from");
        $fin = $this->arrayMin(array(
            strpos(strToLower($query), "order by"),
            strpos(strToLower($query), "group by"),
            strpos(strToLower($query), "having"),
        ));
        if (! $fin) {
            $fin = strlen($query);
        }
        $sql = "select count(".($countFields ? "distinct ".$countFields : "*").") ".substr($query, $deb, ($fin - $deb));

        return $sql;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getSearchClause()
     *
     * @param __TYPE__ $field
     *                           __DESC__
     * @param __TYPE__ $value
     *                           __DESC__
     * @param __TYPE__ $position
     *                           __DESC__
     * @param __TYPE__ $bindName
     *                           __DESC__
     * @param __TYPE__ $aBind
     *                           __DESC__
     * @param __TYPE__ $join
     *                           (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getSearchClause($field, $value, $position = 0, $bindName = "", &$aBind, $join = "OR")
    {
        if ($bindName == "") {
            $return = " ".$field." like  '%".$value."%' ";
        } else {
            $request = "%".str_replace("'", "''", stripslashes(strtolower(html_entity_decode($value))))."%";
            $return = " ".$field."  like '%".$bindName."%' ";
            $aBind[$bindName] = $request;
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $field
     *                           __DESC__
     * @param __TYPE__ $value
     *                           __DESC__
     * @param string   $bindName
     *                           (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getScoreClause($field, $value, $bindName = "")
    {
        if ($bindName == "") {
            $return = " match(".$field.") against ('".$value."') AS SCORE";
        } else {
            $return = " match(".$field.") against (".$bindName.") AS SCORE";
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getSearchClauseLike()
     *
     * @param __TYPE__ $field
     *                           __DESC__
     * @param __TYPE__ $value
     *                           __DESC__
     * @param __TYPE__ $position
     *                           __DESC__
     * @param __TYPE__ $bindName
     *                           __DESC__
     * @param __TYPE__ $aBind
     *                           __DESC__
     * @param __TYPE__ $join
     *                           (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getSearchClauseLike($field, $value, $position = 0, $bindName = "", &$aBind, $join = "OR")
    {
        $return = " ".$field." like  '%".$value."%' ";

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getNVLClause()
     *
     * @param __TYPE__ $clause
     *                         __DESC__
     * @param __TYPE__ $value
     *                         __DESC__
     *
     * @return __TYPE__
     */
    public function getNVLClause($clause, $value)
    {
        $return = " IFNULL(".$clause.",".$value.") ";

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getConcatClause()
     *
     * @param __TYPE__ $aValue
     *                         __DESC__
     *
     * @return __TYPE__
     */
    public function getConcatClause($aValue)
    {
        $return = " CONCAT(".implode(",", $aValue).") ";

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getCaseClause()
     *
     * @param __TYPE__ $field
     *                               __DESC__
     * @param __TYPE__ $aClause
     *                               __DESC__
     * @param __TYPE__ $defaultValue
     *                               __DESC__
     *
     * @return __TYPE__
     */
    public function getCaseClause($field, $aClause, $defaultValue)
    {
        if ($field && $aClause) {
            $return = "CASE ".$field;
            foreach ($aClause as $key => $value) {
                $temp[] = " WHEN ".$key." THEN ".$value;
            }
            $return .= implode(" ", $temp);
            $return .= " ELSE ".$defaultValue." END ";
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::duplicateRecord()
     *
     * @param string $table
     *                           __DESC__
     * @param __TYPE__ $key
     *                           __DESC__
     * @param __TYPE__ $oldValue
     *                           __DESC__
     * @param __TYPE__ $newValue
     *                           __DESC__
     *
     * @return __TYPE__
     */
    public function duplicateRecord($table, $key, $oldValue, $newValue)
    {
        $tableTmp = "tmp";
        $fieldSet = $this->describeTable($table);
        $strSQL = "INSERT INTO ".$table." SELECT ";
        $j = - 1;
        foreach ($fieldSet as $field) {
            $j ++;
            if ($field["increment"]) {
                $autoIncrement = $field["field"];
            }
            if ($field["field"] != $key) {
                $strSQL .= $field["field"].", ";
            } else {
                $fieldType = $field["type"];
                $strSQL .= $this->formatField($newValue, $field["type"]).", ";
            }
        }
        $strSQL .= "FROM ".$tableTmp; // ." WHERE ".$key."=".$this->formatField($oldValue,$fieldType);
        $strSQL = str_replace(", FROM", " FROM", $strSQL);
        $this->query("DROP TABLE IF EXISTS ".$tableTmp);
        $this->query("CREATE TABLE ".$tableTmp." SELECT * FROM ".$table." WHERE ".$key."=".$this->formatField($oldValue, $fieldType));
        $this->query($strSQL);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getFieldTypeDDL
     *
     * @param __TYPE__ $type
     *                            __DESC__
     * @param __TYPE__ $length
     *                            __DESC__
     * @param __TYPE__ $precision
     *                            __DESC__
     *
     * @return __TYPE__
     */
    public function getFieldTypeDDL($type, $length, $precision)
    {
        if (in_array($type, array(
            self::SMALLINT,
            self::TINYINT,
            self::INTEGER,
            self::BIGINT,
            self::IDENTITY,
        ))) {
            $length = null;
            $precision = null;
        }
        $complement = '';
        if (in_array($type, self::$TEXT_TYPES) && ! in_array($type, self::$DATE_TYPES)) {
            $complement = ' character set utf8 collate utf8_swedish_ci';
        }
        if ($type == self::VARCHAR && ! $length) {
            $type = self::TINYTEXT;
        }
        $return = self::nativeType($type).($length ? '('.$length.($precision ? ','.$precision : '').')' : '').$complement;

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getFieldNullDDL
     *
     * @param __TYPE__ $null
     *                       __DESC__
     *
     * @return __TYPE__
     */
    public function getFieldNullDDL($null)
    {
        $return = '';
        if (! $null) {
            $return = 'NOT NULL';
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getFieldDefaultDDL
     *
     * @param __TYPE__ $default
     *                          __DESC__
     * @param __TYPE__ $type
     *                          __DESC__
     * @param __TYPE__ $null
     *                          __DESC__
     *
     * @return __TYPE__
     */
    public function getFieldDefaultDDL($default, $type, $null)
    {
        $return = '';
        $default = str_replace('CURRENT_TIMESTAMP', '', $default);
        if (isset($default)) {
            // NULL non pris en compte pour les textes
            if ($default == 'NULL' && ! $null) {
                $return = '';
            } else {
                if (in_array($type, self::$TEXT_TYPES) && $default != 'NULL') {
                    $default = str_replace("''", "'", "'".$default."'");
                    if ($default == "'") {
                        unset($default);
                    }
                }
                if (isset($default)) {
                    $return = trim('default '.$default);
                }
            }
        }
        if ($return == 'default' || $return == 'default NULL') {
            $return = '';
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getFieldIncrementDDL
     *
     * @param __TYPE__ $increment
     *                            __DESC__
     * @param string   $type
     *                            (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getFieldIncrementDDL($increment, $type = "")
    {
        $return = '';
        if ($increment || $type == self::IDENTITY) {
            $return = 'AUTO_INCREMENT';
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getPrimaryKeyDDL
     *
     * @param __TYPE__ $table
     *                          __DESC__
     * @param __TYPE__ $name
     *                          __DESC__
     * @param __TYPE__ $aFields
     *                          __DESC__
     *
     * @return __TYPE__
     */
    public function getPrimaryKeyDDL($table, $name, $aFields)
    {
        $return = "PRIMARY KEY (".implode(',', $aFields).")";

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getIncludedKeysDDL
     *
     * @param __TYPE__ $table
     *                          __DESC__
     * @param __TYPE__ $aFields
     *                          __DESC__
     *
     * @return __TYPE__
     */
    public function getIncludedKeysDDL($table, $aFields)
    {
        $return = '';

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getIndexDDL
     *
     * @param __TYPE__ $table
     *                          __DESC__
     * @param __TYPE__ $name
     *                          __DESC__
     * @param __TYPE__ $aFields
     *                          __DESC__
     * @param bool     $unique
     *                          (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getIndexDDL($table, $name, $aFields, $unique = false)
    {
        $return = "ALTER TABLE ".$table." ADD ".($unique ? "UNIQUE " : "")."INDEX ".$name." (".implode(',', $aFields).");";

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getReferencesDDL
     *
     * @param __TYPE__ $table
     *                             __DESC__
     * @param __TYPE__ $name
     *                             __DESC__
     * @param __TYPE__ $childField
     *                             __DESC__
     * @param __TYPE__ $source
     *                             __DESC__
     *
     * @return __TYPE__
     */
    public function getReferencesDDL($table, $name, $childField, $source)
    {
        $return = 'ALTER TABLE '.$table.' ADD CONSTRAINT '.$name.' FOREIGN KEY ('.$childField.') REFERENCES '.$source.';';

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getSequenceDDL
     *
     * @param __TYPE__ $name
     *                            __DESC__
     * @param string   $start
     *                            (option) __DESC__
     * @param string   $increment
     *                            (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getSequenceDDL($name, $start = "", $increment = "")
    {
        $return = '';

        return trim($return);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getUniqueKeyDDL
     *
     * @param __TYPE__ $table
     *                         __DESC__
     * @param __TYPE__ $name
     *                         __DESC__
     * @param __TYPE__ $aField
     *                         __DESC__
     *
     * @return __TYPE__
     */
    public function getUniqueKeyDDL($table, $name, $aField)
    {
        $return = 'ALTER TABLE {'.$table.'} ADD UNIQUE KEY '.$name.' ('.implode(',', $aField).')';

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getUpdateSequenceDDL
     *
     * @param __TYPE__ $table
     *                               __DESC__
     * @param __TYPE__ $field
     *                               __DESC__
     * @param __TYPE__ $sequenceName
     *                               __DESC__
     *
     * @return __TYPE__
     */
    public function getUpdateSequenceDDL($table, $field, $sequenceName)
    {
        $return = '';

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @see Pelican_Db::getEndDDL
     *
     * @param __TYPE__ $type
     *                       __DESC__
     *
     * @return __TYPE__
     */
    public function getEndDDL($type)
    {
        switch ($type) {
            case 'Table':
                {
                    $return = ' ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;';
                    break;
                }
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $type
     *                       __DESC__
     *
     * @return __TYPE__
     */
    public function nativeType($type)
    {
        $return = self::$nativeTypes[strtoupper($type)];

        return $return;
    }
}
