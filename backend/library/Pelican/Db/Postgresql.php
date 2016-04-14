<?php

/** Couche d'abstraction Postgresql
 * @author __AUTHOR__
 */

/**
 * cette classe permet d'avoir un accès facilité à une base de
 * données PostgreSQL.
 * Elle offre un certain nombre de fonctionnalités
 * comme la création d'une connexion, l'exécution d'une requète
 * devant retourner un champ, une ligne ou un ensemble de ligne ou
 * encore l'affichage du temps d'exécution d'un requète, la récupération
 * du dernier enregistrement inséré au cours de la session...
 *
 * INFO : Pour utiliser pgpool :
 *
 * listen_addresses = 'localhost'
 * port = 5433
 * socket_dir = '/tmp'
 * backend_host_name = 'localhost'
 * backend_port = 5432
 * backend_socket_dir = '/var/run/postgresql'
 *
 * il faut utiliser pg_connect au lieu de pg_pconnect, et se connecter au port
 * 5433, plutôt que 5432.
 *
 * @version 1.0
 *
 * @author Laurent Franchomme <lfranchomme@businessdecision.com>
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 15/05/2003, 07/10/2008
 */
class Pelican_Db_Postgresql extends Pelican_Db
{
    /**
     * @static
     *
     * @access public
     *
     * @var __TYPE__ __DESC__
     */
    public static $nativeTypes = array(self::BINARY => 'BYTEA', self::BLOB => 'OID', self::BOOLEAN => 'BOOL', self::BIGINT => 'INT24', self::CHAR => 'CHAR', self::CLOB => 'TEXT', self::DECIMAL => 'DECIMAL', self::DATE => 'DATE', self::DATETIME => 'TIMESTAMP', self::DOUBLE => 'DOUBLE', self::FLOAT => 'FLOAT', self::IDENTITY => 'SERIAL', self::INTEGER => 'INT4', self::LONGVARBINARY => 'BYTEA', self::LONGVARCHAR => 'TEXT', self::NUMERIC => 'NUMERIC', self::REAL => 'REAL', self::SMALLINT => 'SMALLINT', self::TIME => 'TIME', self::TIMESTAMP => 'TIMESTAMP', self::TINYTEXT => 'VARCHAR', self::TINYINT => 'SMALLINT', self::VARBINARY => 'BYTEA', self::VARCHAR => 'VARCHAR' );

    /**
     * Nom de la base de données cible (pour les messages d'erreur).
     *
     * @var string
     */
    public $databaseTitle = "PostgreSQL";

    /**
     * Base de données autorisant ou non l'utilisation de variables BIND.
     *
     * @var boolean
     */
    public $allowBind = true;

    /**
     * Commiter automatiquement ou non les requêtes.
     *
     * @var boolean
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
     * Constructeur. Permet de se connecter à la base de donnée.
     *
     * @return Pelican_Db
     *
     * @param $databaseName string
     *                    le nom de la base de données
     * @param $username string
     *                    le nom de l'utilisateur servant à la connexion
     * @param $password string
     *                    le mot de passe de connexion
     * @param $host string
     *                    l'adresse IP du serveur (optionnel) : 127.0.0.1 par défaut
     * @param $port string
     *                    le port de connexion (optionnel) : 3306 par défaut
     */
    public function __construct($databaseName, $username, $password, $host = "", $port = "", $bExit = true, $persistency = false)
    {
        parent::__construct($databaseName, $username, $password, $host, $port, $bExit);
        $this->databaseName = $databaseName;
        $this->user = $username;
        $this->passwd = $password;
        $this->host = ($host ? $host : "127.0.0.1");
        $this->port = ($port ? $port : "5432");
        $this->persistency = $persistency;

        $this->info = $this->getInfo();
        // connexion à la base de données

        $str = "host='".$this->host."' port=".$this->port." sslmode='allow' user='".$this->user."' password='".$this->passwd."' dbname='".$this->databaseName."'";
        if ($this->persistency) {
            $this->id = pg_pconnect($str) or $this->error($this->message("Impossible de connecter au serveur ".$this->databaseTitle));
        } else {
            $this->id = pg_connect($str) or $this->error($this->message("Impossible de connecter au serveur ".$this->databaseTitle));
        }
    }

    public static function is_available()
    {
        return function_exists('pg_connect');
    }

    /**
     * @see Pelican_Db::beginTrans()
     */
    public function beginTrans()
    {
        if (! $this->autoCommit) {
            $this->result = pg_exec($this->id, 'begin;');
            $this->transaction = true;
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
            pg_close($this->id) or $this->error($this->message("Impossible de fermer la connexion !"));
        }
    }

    /**
     * @see Pelican_Db::queryInit()
     */
    public function queryInit($query, $param = array(), $paramLob = array(), $debug = false)
    {
        Pelican_Profiler::start($query, 'query');

        $this->lastError = "";
        Pelican_Log::control($query, 'sanscache');

        if (! Pelican_Db::isSelect($query) && ! $this->transaction) {
            $this->beginTrans();
        }

        $this->prepareBind($query, $param, $paramLob, "\$");

        // execution de la requête
        $this->data = array();
        $this->type = array();
        $this->name = array();
        $this->len = array();
        $this->query = self::replacePrefix($query);
        $this->param = $param;

        if ($this->param && $this->allowBind) {
            $this->result = pg_query_params($this->id, $this->query, $this->param);
        } else {
            $this->result = pg_query($this->id, $this->query);
        }
        if ($this->result) {
            // pg_exec ?
            // nb de lignes affectées
            $this->affectedRows = pg_affected_rows($this->result); // pg_cmdtuples
                                                                   // ?

            // sauvegarde du nombre de champs renvoyés par la requète
            $this->fields = pg_num_fields($this->result);

            // sauvegarde du nombre de lignes renvoyés par la requète
            $this->rows = pg_num_rows($this->result);

            for ($i = 0; $i < $this->fields; $i ++) {
                $this->type [$i] = pg_field_type($this->result, $i);
                $this->name [$i] = pg_field_name($this->result, $i);
                $this->len [$i] = pg_field_prtlen($this->result, $i);
            }
            Pelican_Profiler::stop($query, 'query');

            return true;
        } else {
            $this->lastError = pg_last_error($this->id);
            $this->error($this->message("Impossible d'ex&eacute;cuter la requ&egrave;te :", $this->query));
            Pelican_Profiler::stop($query, 'query');

            return false;
        }
    }

    /**
     * @see Pelican_Db::query()
     */
    public function query($query, $param = array(), $paramLob = array(), $debug = false)
    {
        // si on veut afficher le temps d'execution de la requete :
        // initialisation
        if ($this->debugTime) {
            $this->initTimeQuery();
        }

            // execution de la requête
        $temp = array();
        if ($this->queryInit($query, $param, $paramLob, $debug)) {
            for ($num_ligne = 0; $num_ligne < $this->rows; $num_ligne ++) {
                $temp = $this->fetch_array($this->result, $num_ligne) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
                while (list($key, $val) = each($temp)) {
                    $this->data [$key] [$num_ligne] = $val;
                }
            }
        }
    }

    /**
     * @see Pelican_Db::queryItem()
     */
    public function queryItem($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
    {
        // suppression de l'ancien résultat
        unset($this->data);

        // execution de la requete
        if ($this->queryInit($query, $param, $paramLob, $debug) and $this->rows > 0) {
            // recuperation du résultat
            $tab_result = $this->fetch_array($this->result, 0, $light) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            $keys = array_keys($tab_result);
            $this->data = $tab_result [$keys [0]];

            // liberation de la memoire
            pg_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
        }

        // renvoie du résultat
        if (! $this->data) {
            unset($this->data);
        }

        if (isset($this->data)) {
            return $this->data;
        }
    }

    /**
     * @see Pelican_Db::queryRow()
     */
    public function queryRow($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
    {
        // suppression de l'ancien résultat
        unset($this->data);

        // execution de la requete
        if ($this->queryInit($query, $param, $paramLob, $debug) and $this->rows > 0) {
            // recuperation du résultat
            $this->data = $this->fetch_array($this->result, 0, $light) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));

            // liberation de la memoire
            pg_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
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
        unset($this->data);

        // execution de la requete
        if ($this->queryInit($query, $param, $paramLob, $debug) and $this->rows > 0) {
            // recuperation du résultat
            for ($num_ligne = 0; $num_ligne < $this->rows; $num_ligne ++) {
                $val = $this->fetch_array($this->result, $num_ligne, $light) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
                if (isset($this->fetchOrderField) && $val) {
                    $index = $val [$this->fetchOrderField];
                } else {
                    $index = $num_ligne;
                }
                $this->data [$index] = $val;
            }
            if (isset($this->fetchOrderField) && $this->data) {
                if (isset($this->fetchOrderType)) {
                    if ($this->fetchOrderType == 'DESC') {
                        krsort($this->data);
                    }
                }
                $this->data = array_values($this->data);
            }

            // liberation de la memoire
            pg_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
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
        unset($this->data);

        // execution de la requete
        if ($this->queryInit($query, $param, $paramLob, $debug) and $this->rows > 0) {
            // recuperation du résultat
            for ($num_ligne = 0; $num_ligne < $this->rows; $num_ligne ++) {
                $this->data [$num_ligne] = mysql_fetch_object($this->result) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            }
            // liberation de la memoire
            mysql_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
        }

        // renvoie du résultat
        return ($this->data);
    }

    public function queryStoredProcedure($name, $param = array(), $paramLob = array(), $light = true, $debug = false, $xml = false)
    {
        $sql = "select * from ".$name."(".($param ? implode(",", $param) : "").")";
        if (! $xml) {
            // return $this->getTab($sql);
            return $this->queryTab($sql);
        } else {
            return $this->queryXML($sql);
        }
    }

    /**
     * @see Pelican_Db::commit()
     */
    public function commit()
    {
        if ($this->id) {
            $this->result = pg_exec($this->id, 'end;');
        }

        return true;
    }

    /**
     * @see Pelican_Db::rollback()
     */
    public function rollback()
    {
        if ($this->id) {
            $this->result = pg_exec($this->id, 'abort;');
        }

        return true;
    }

    /**
     * @see Pelican_Db::getLastOid()
     */
    public function getLastOid($query = "")
    {
        // on met la requete en minuscule sur une ligne
        $query = preg_replace("/[\s\n\r]+/", " ", trim($query));

        // on recupere le nom de la table dans le cas d'un INSERT
        if (preg_match("/^INSERT\s+INTO\s+([^\s]+)\s+.*/i", $query, $matches)) {
            // mise en forme de la requete
            // récupérer le cas du serial
            $query = "SELECT * FROM ".$matches [1]." WHERE oid = ".pg_last_oid($this->result);
            // on recupere le dernier enregistrement
            $this->lastInsertedId = $this->queryItem($query);

            return $this->lastInsertedId;
        }
    }

    /**
     * @see Pelican_Db::getNextId()
     */
    public function getNextId($table)
    {
        $return = "";

        $info = $this->describeTable($table);
        $count = count($info);
        if ($info) {
            for ($i = 0; $i < $count; $i ++) {
                if ($info [$i] ["sequence_name"]) {
                    $seq = $info [$i] ["sequence_name"];
                    if (! substr_count('nextval', $seq)) {
                        $seq = "nextval('".$seq."')";
                    }
                    $return = $this->queryItem("SELECT ".$seq);
                    $i = $count;
                }
            }
        }

        return $return;
    }

    /**
     * @see Pelican_Db::getError()
     */
    public function getError()
    {
        if ($this->id) {
            if ($this->lastError) {
                $erreur = $this->lastError;
            } else {
                $erreur = pg_last_error($this->id);
            }
        }

        return array("code" => "-1", "message" => $erreur );
    }

    /**
     * @see Pelican_Db::getInfo()
     */
    public function getInfo()
    {
        $return ["type"] = $this->databaseTitle;
        // $return["client_version"] = "TODO";
        // $return["server_version"] = "TODO";
        $return ["host"] = $_SERVER ["SERVER_NAME"];
        $return ["instance"] = $this->databaseName;

        return $return;
    }

    /**
     * @see Pelican_Db::getDbInfo()
     */
    public function getDbInfo($type, $name = "", $id = "_ID")
    {
        switch ($type) {
            case 'infos' :
                {
                    /*
                     * A COMPLETER
                     */
                    return;
                    break;
                }
            case 'tables' :
                {
                    $query = "SELECT c.oid,
                            case when n.nspname='public' then c.relname else n.nspname||'.'||c.relname end as relname
                            FROM pg_class c join pg_namespace n on (c.relnamespace=n.oid)
                            WHERE c.relkind = 'r'
                            AND n.nspname NOT IN ('information_schema','pg_catalog')
                            AND n.nspname NOT LIKE 'pg_temp%'
                            AND n.nspname NOT LIKE 'pg_toast%'
                            ORDER BY relname";
                    $this->query($query);
                    $return = $this->data ['relname'];
                    break;
                }
            case 'views' :
                {
                    /*
                     * A COMPLETER
                     */
                    $query = 'SELECT viewname FROM pg_views';
                    $result = $this->queryTab($query);
                    if ($result) {
                        foreach ($result as $val) {
                            $return [] = $val ['view_name'];
                        }
                    }

                    break;
                }
            case 'fields' :
                {
                    $query = "SELECT
                            a.attname AS     field,
                            t.typname AS     type,
                            a.attnotnull,
                            b.adsrc AS     default,
                            t.typlen,
                            atttypmod
                            FROM pg_class c,  pg_type t,
                            pg_attribute a left join pg_attrdef b on (a.attnum = b.adnum and  a.attrelid = b.adrelid)
                            WHERE c.relname = '".$name."'
                            AND c.relkind='r'
                            AND a.attnum > 0
                            AND a.attrelid = c.oid
                            AND a.atttypid = t.oid
                            AND a.attname not like '%.dropped%'
                            ORDER BY a.attnum";
                    $result = $this->queryTab($query);

                    $FKeys = $this->getDbInfo('foreign_keys', $name);

                    $i = 0;
                    foreach ($result as $ligne) {
                        $return [$i] ["field"] = strtoupper($ligne ["field"]);
                        $return [$i] ["type"] = trim($ligne ["type"]);
                        $return [$i] ["length"] = ($ligne ['atttypmod'] > 0 ? ($ligne ['atttypmod'] - 4) : $ligne ["typlen"]);
                        $return [$i] ["precision"] = null;
                        if ($return [$i] ["type"] == "date" || $return [$i] ["type"] == "timestamp") {
                            $return [$i] ["length"] = null;
                        }
                        if ($return [$i] ["length"] < 0) {
                            $return [$i] ["length"] = null;
                        }
                        if ($return [$i] ["type"] == "numeric" && $return [$i] ["length"] >= 650000) {
                            $return [$i] ['type'] = "int4";
                            $return [$i] ["length"] = null;
                        }
                        $return [$i] ["null"] = ($ligne ["attnotnull"] ? false : true);
                        $return [$i] ["default"] = (is_null($ligne ["default"]) ? 'NULL' : ($ligne ["default"] === '0' ? '0' : $ligne ["default"]));
                        if ($return [$i] ["default"]) {
                            if (substr_count("varying", $return [$i] ["default"])) {
                                $return [$i] ["default"] = str_replace('::character varying', '', $ligne ["default"]);
                            }
                        }
                        if ($return [$i] ["default"] == 'NULL' && ! $return [$i] ["null"]) {
                            $return [$i] ["default"] = "";
                        }
                        $return [$i] ["key"] = false;
                        $return [$i] ["extra"] = "";
                        $return [$i] ["increment"] = false;
                        $return [$i] ["sequence"] = (strpos(strToLower(" ".$ligne ["default"]), "nextval") > 0 ? true : false);
                        if ($return [$i] ["sequence"]) {
                            $return [$i] ["sequence_name"] = str_replace(array("nextval('", "'::regclass)" ), "", $return [$i] ["default"]);
                            $return [$i] ["default"] = "";
                        }
                        if (isset($FKeys [$return [$i] ["field"]])) {
                            $return [$i] ["fkey"] = $FKeys [$return [$i] ["field"]] ["parent_table"].'.'.$FKeys [$return [$i] ["field"]] ["parent_field"];
                        }
                        if ($return [$i] ["type"] == "int4" && $return [$i] ["sequence"]) {
                            $return [$i] ['type'] = "serial";
                            $return [$i] ["length"] = null;
                        }
                        if ($return [$i] ["type"] == "serial") {
                            $return [$i] ['null'] = false;
                        }
                        $i ++;
                    }

                    $Keys = $this->getDbInfo('keys', $name);

                    for ($i = 0; $i < count($return); $i ++) {
                        // $return[$i]["key"] = false;
                        // $return[$i]["null"] = $ligne["null"];
                        if (is_array($Keys)) {
                            if (in_array($return [$i] ["field"], $Keys)) {
                                $return [$i] ["key"] = true;
                            }
                        }
                    }
                    break;
                }
            case 'keys' :
                {
                    $indKeys = $this->queryItem("SELECT indKey
                            FROM pg_index, pg_class
                            WHERE pg_class.oid = pg_index.indrelid  AND
                            pg_index.indisprimary = 't' AND
                            pg_class.relname = '".$name."'");

                    if ($indKeys) {
                        $keysTemp = $this->queryTab("SELECT pg_attribute.attname, pg_attribute.attnum
                                FROM pg_attribute, pg_index, pg_class
                                WHERE pg_class.oid = pg_attribute.attrelid AND
                                pg_class.oid = pg_index.indrelid AND
                                pg_attribute.attnum in (".str_replace(" ", ",", $indKeys).")
                                AND pg_index.indisprimary = 't' AND
                                pg_class.relname = '".$name."'
                                order by pg_attribute.attnum");

                        foreach ($keysTemp as $column) {
                            $return [$column ['attnum']] = strtoupper($column ['attname']);
                        }
                    }
                    break;
                }
            case 'sequences' :
                {
                    $query = "SELECT c.oid,
                            case when n.nspname='public' then c.relname else n.nspname||'.'||c.relname end as relname
                            FROM pg_class c join pg_namespace n on (c.relnamespace=n.oid)
                            WHERE c.relkind = 'S'
                            AND n.nspname NOT IN ('information_schema','pg_catalog')
                            AND n.nspname NOT LIKE 'pg_temp%'
                            AND n.nspname NOT LIKE 'pg_toast%'";
                    $this->query($query);
                    $return = $this->data ['relname'];
                    break;
                }
            case 'indexes' :
                {
                    /*
                     * A COMPLETER
                     */
                    $sql = "SELECT c.oid
                            FROM pg_class c join pg_namespace n on (c.relnamespace=n.oid)
                            WHERE c.relkind = 'r'
                            AND n.nspname NOT IN ('information_schema','pg_catalog')
                            AND n.nspname NOT LIKE 'pg_temp%'
                            AND n.nspname NOT LIKE 'pg_toast%'
                            AND c.relname='".$name."'";
                    $oid = $this->queryItem($sql);

                    if ($oid) {
                        $sql = "SELECT
                    DISTINCT ON(cls.relname)
                    cls.relname as idxname,
                    indkey,
                    indisunique
                    FROM pg_index idx
                    JOIN pg_class cls ON cls.oid=indexrelid
                    WHERE indrelid = ".$oid." AND NOT indisprimary";
                        $index = $this->queryTab($sql);

                        if ($index) {
                            $i = 0;
                            foreach ($index as $vidx) {
                                $return [strtoupper($vidx ['idxname'])] ["ordered"] = "";
                                $return [strtoupper($vidx ['idxname'])] ["cardinality"] = "";
                                $return [strtoupper($vidx ['idxname'])] ["type"] = ($vidx ['indisunique'] ? 'UNIQUE' : '');
                                $return [strtoupper($vidx ['idxname'])] ["name"] = strtoupper($vidx ['idxname']);
                                $sql = "SELECT a.attname
                    FROM pg_catalog.pg_class c JOIN pg_catalog.pg_attribute a ON a.attrelid = c.oid
                    WHERE c.oid = ".$oid." AND a.attnum in (".str_replace(" ", ",", $vidx ['indkey']).") AND NOT a.attisdropped
                    ORDER BY a.attnum";
                                $fields = $this->queryTab($sql);
                                foreach ($fields as $f) {
                                    $return [strtoupper($vidx ['idxname'])] ["fields"] [$i] = strtoupper($f ['attname']);
                                    $i ++;
                                }
                            }
                        }
                    }

                    return $return;
                    break;
                }
            case 'foreign_keys' :
                {

                    /*
                     * pour les versions >= 8.2 A TESTER
                     * $sql="
                     * SELECT fum.ftblname AS lookup_table, split_part(fum.rf,
                     * ')'::text, 1) AS lookup_field,
                     * fum.ltable AS dep_table, split_part(fum.lf, ')'::text, 1)
                     * AS dep_field
                     * FROM (
                     * SELECT fee.ltable, fee.ftblname, fee.consrc,
                     * split_part(fee.consrc,'('::text, 2) AS lf,
                     * split_part(fee.consrc, '('::text, 3) AS rf
                     * FROM (
                     * SELECT foo.relname AS ltable, foo.ftblname,
                     * pg_get_constraintdef(foo.oid) AS consrc
                     * FROM (
                     * SELECT c.oid, c.conname AS name, t.relname, ft.relname AS
                     * ftblname
                     * FROM pg_constraint c
                     * JOIN pg_class t ON (t.oid = c.conrelid)
                     * JOIN pg_class ft ON (ft.oid = c.confrelid)
                     * JOIN pg_namespace nft ON (nft.oid = ft.relnamespace)
                     * LEFT JOIN pg_description ds ON (ds.objoid = c.oid)
                     * JOIN pg_namespace n ON (n.oid = t.relnamespace)
                     * WHERE c.contype = 'f'::\"char\"
                     * ORDER BY t.relname, n.nspname, c.conname, c.oid
                     * ) foo
                     * ) fee) fum
                     * WHERE fum.ltable='".strtolower($table)."'
                     * ORDER BY fum.ftblname, fum.ltable, split_part(fum.lf,
                     * ')'::text, 1)
                     * "
                     */
                    $query = "SELECT
                        conname,
                        confupdtype,
                        confdeltype,
                        cl.relname as fktab,
                        a2.attname as fkcol,
                        cr.relname as reftab,
                        a1.attname as refcol
                        FROM pg_constraint ct
                        JOIN pg_class cl ON cl.oid=conrelid
                        JOIN pg_class cr ON cr.oid=confrelid
                        LEFT JOIN pg_catalog.pg_attribute a1 ON a1.attrelid = ct.confrelid
                        LEFT JOIN pg_catalog.pg_attribute a2 ON a2.attrelid = ct.conrelid
                        WHERE
                        contype='f'
                        AND cl.relname = '".strtolower($name)."'
                        AND a2.attnum = ct.conkey[1]
                        AND a1.attnum = ct.confkey[1]
                        ORDER BY conname";

                    $result = $this->queryTab($query);

                    if ($result) {
                        foreach ($result as $value) {
                            $return [strtoupper($value ['fkcol'])] ["child_field"] = strtoupper($value ['fkcol']);
                            $return [strtoupper($value ['fkcol'])] ["parent_field"] = strtoupper($value ['refcol']);
                            $return [strtoupper($value ['fkcol'])] ["parent_table"] = $value ['reftab'];
                        }
                    }

                    break;
                }
            case 'functions' :
                {
                    /*
                     * A COMPLETER
                     */
                    $query = 'SELECT proname FROM pg_proc';
                    $return = $this->queryTab($query);
                    break;
                }
            default :
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
     * @see Pelican_Db::getNow()
     */
    public function getNow()
    {
        return "now()";
    }

    /**
     * @see Pelican_Db::getDateDiffClause()
     */
    public function getDateDiffClause($date1, $date2)
    {
        $return = " (DATE(".$date1.") - DATE(".$date2.")) ";

        return $return;
    }

    /**
     * @see Pelican_Db::getDateAddClause()
     */
    public function getDateAddClause($date, $interval)
    {
        $return = " DATE_ADD(".$date.", INTERVAL ".$interval." DAY) ";

        return $return;
    }

    /**
     * @see Pelican_Db::dateStringToSql()
     */
    public function dateStringToSql($strChaine, $hour = true)
    {
        $complement = "";
        $keyword = "TO_DATE";
        if ($hour) {
            $complement = " HH24:MI:SS";
            $keyword = "TO_TIMESTAMP";
        }
        switch ($this->dateFormat) {
            case "MM/DD/YYYY" :
                {
                    $temp_contenu_date = $keyword."('".$strChaine."','MM/DD/YYYY".$complement."')";
                    break;
                }
            default : // DD/MM/YYYY
                {
                    $temp_contenu_date = $keyword."('".$strChaine."','DD/MM/YYYY".$complement."')";
                    break;
                }
        }

        return ($temp_contenu_date);
    }

    /*
     * public function dateStringToSql($strChaine, $hour = true) { $hour =
     * array(); $arr = explode("/", $strChaine); if (valueExists($arr, 2)) {
     * $hour = explode(" ", $arr[2]); $arr[2] = $hour[0]; } $complement = ""; if
     * (valueExists($hour, 1)) { $complement = " ".$hour[1]; } if (count($arr) >
     * 1) { switch ($this->dateFormat) { case "MM/DD/YYYY": { $temp_contenu_date
     * = "'".$arr[2]."-".$arr[0]."-".$arr[1].$complement."'"; break; } default:
     * //DD/MM/YYYY { $temp_contenu_date =
     * "'".$arr[2]."-".$arr[1]."-".$arr[0].$complement."'"; break; } } } else {
     * $temp_contenu_date = "'".$strChaine."'"; } return($temp_contenu_date); }
     */

    /**
     * @see Pelican_Db::dateSqlToString()
     */
    public function dateSqlToString($dateField, $hour = false, $complement = "")
    {
        if ($hour) {
            $complement = " HH24:MI:SS";
        }
        if (! isset($this->dateFormat)) {
            $this->dateFormat = "DD/MM/YYYY";
        }

        switch ($this->dateFormat) {
            case "MM/DD/YYYY" :
                {
                    $temp_date = "TO_CHAR(".$dateField.",'MM/DD/YYYY".$complement."')";
                    break;
                }
            default : // DD/MM/YYYY
                {
                    $temp_date = "TO_CHAR(".$dateField.",'DD/MM/YYYY".$complement."')";
                    break;
                }
        }

        return ($temp_date);
    }

    /**
     * @see Pelican_Db::dateSqlToStringShort()
     */
    public function dateSqlToStringShort($dateField)
    {
        if (! isset($this->dateFormat)) {
            $this->dateFormat = "DD/MM/YYYY";
        }

        switch ($this->dateFormat) {
            case "MM/DD/YYYY" :
                {
                    $temp_date = "TO_CHAR(".$dateField.",'MM/DD/YYYY')";
                    break;
                }
            default : // DD/MM/YYYY
                {
                    $temp_date = "TO_CHAR(".$dateField.",'DD/MM/YYYY')";
                    break;
                }
        }

        return ($temp_date);
    }

    /**
     * @see Pelican_Db::dateToYear()
     */
    public function dateToYear($dateField)
    {
        $temp_date = "TO_CHAR(".$dateField.",'YYYY')";

        return $temp_date;
    }

    /**
     * @see Pelican_Db::dateToMonth()
     */
    public function dateToMonth($dateField)
    {
        $temp_date = "TO_CHAR(".$dateField.",'MM')";

        return $temp_date;
    }

    /**
     * @see Pelican_Db::stringToSql()
     */
    public function stringToSql($value)
    {
        return pg_escape_string($value);
    }

    /**
     * @see Pelican_Db::getLimitedSQL()
     */
    public function getLimitedSQL($query, $min, $length)
    {
        $return = $query." LIMIT ".$length." OFFSET ".max($min - 1, 0);

        return $return;
    }

    /**
     * @see Pelican_Db::getCountSQL()
     */
    public function getCountSQL($query, $countFields)
    {
        $deb = strpos(strToLower($query), "from");
        $nbOrderBy = substr_count(strToLower($query), "order by");
        if ($nbOrderBy >= 2) {
            $fin = $this->arrayMin(array(strripos(strToLower($query), "order by"), strpos(strToLower($query), "group by"), strpos(strToLower($query), "having") ));
        } else {
            $fin = $this->arrayMin(array(strpos(strToLower($query), "order by"), strpos(strToLower($query), "group by"), strpos(strToLower($query), "having") ));
        }

        if (! $fin) {
            $fin = strlen($query);
        }
        $sql = "select count(*) from (select ".($countFields ? $countFields : "*")." ".substr($query, $deb, ($fin - $deb));
        if ($countFields && $countFields != "*") {
            $sql .= " group by ".$countFields;
        }
        $sql .= ") AS A";

        return $sql;
    }

    /**
     * @see Pelican_Db::getSearchClause()
     */
    public function getSearchClause($field, $value, $position = 0, $bindName = "", &$aBind, $join = "OR")
    {
        $return = " ".$field." like  '%".$value."%' ";

        return $return;
    }

    /**
     * @see Pelican_Db::getSearchClauseLike()
     */
    public function getSearchClauseLike($field, $value, $position = 0, $bindName = "", &$aBind, $join = "OR")
    {
        $return = " ".$field." like  '%".$value."%' ";

        return $return;
    }

    /**
     * @see Pelican_Db::getNVLClause()
     */
    public function getNVLClause($clause, $value)
    {
        $return = " coalesce(".$clause.",".$value.") ";

        return $return;
    }

    /**
     * @see Pelican_Db::getConcatClause()
     */
    public function getConcatClause($aValue)
    {
        $return = " ".implode("||", $aValue)." ";

        return $return;
    }

    /**
     * @see Pelican_Db::getCaseClause()
     */
    public function getCaseClause($field, $aClause, $defaultValue)
    {
        if ($field && $aClause) {
            $return = "CASE ".$field;
            foreach ($aClause as $key => $value) {
                $temp [] = " WHEN ".$key." THEN ".$value;
            }
            $return .= implode(" ", $temp);
            $return .= " ELSE ".$defaultValue." END ";
        }

        return $return;
    }

    /**
     * @see Pelican_Db::duplicateRecord()
     */
    public function duplicateRecord($table, $key, $oldValue, $newValue)
    {
        $tableTmp = "tmp";
        $fieldSet = $this->describeTable($table);
        $strSQL = "INSERT INTO ".$table." SELECT ";
        $j = - 1;
        foreach ($fieldSet as $field) {
            $j ++;
            if ($field ["increment"]) {
                $autoIncrement = $field ["field"];
            }
            if ($field ["field"] != $key) {
                $strSQL .= $field ["field"].", ";
            } else {
                $key_type = $field ["type"];
                $strSQL .= $this->formatField($newValue, $field ["type"]).", ";
            }
        }
        $strSQL .= "FROM ".$table." WHERE ".$key."=".$this->formatField($oldValue, $key_type);
        $strSQL = str_replace(", FROM", " FROM", $strSQL);
        $this->query($strSQL);
    }

    /**
     * Enter description here...
     *
     * @param $result unknown_type
     * @param $num_ligne unknown_type
     *
     * @return unknown
     */
    public function fetch_array($result, $num_ligne)
    {
        $return = pg_fetch_array($result, $num_ligne, PGSQL_BOTH);
        for ($i = 0; $i < count($this->type); $i ++) {
            if ($this->type [$i] == "bool") {
                $return [$i] = (strToLower($return [$i]) == 't' ? true : false);
                $return [$this->name [$i]] = $return [$i];
            }
            /*
             * a ameliorer
             */
            $return [strtoupper($this->name [$i])] = $return [$this->name [$i]];
        }
        // $return = array_change_key_case($return, CASE_UPPER);
        return $return;
    }

    /**
     * DDL clauses.
     */

    /**
     * @see Pelican_Db::getFieldTypeDDL
     */
    public function getFieldTypeDDL($type, $length, $precision)
    {
        if (in_array($type, array(self::SMALLINT, self::TINYINT, self::INTEGER, self::BIGINT, self::IDENTITY ))) {
            $length = null;
            $precision = null;
        }
        $complement = '';
        $return = self::nativeType($type).($length ? '('.$length.($precision ? ','.$precision : '').')' : '').$complement;

        return $return;
    }

    /**
     * @see Pelican_Db::getFieldNullDDL
     */
    public function getFieldNullDDL($null)
    {
        $return = '';
        if (! $null) {
            $return = 'not null';
        }

        return $return;
    }

    /**
     * @see Pelican_Db::getFieldDefaultDDL
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
     * @see Pelican_Db::getFieldIncrementDDL
     */
    public function getFieldIncrementDDL($increment, $type = "")
    {
        $return = '';

        return $return;
    }

    /**
     * @see Pelican_Db::getPrimaryKeyDDL
     */
    public function getPrimaryKeyDDL($table, $name, $aFields)
    {
        $return = "CONSTRAINT ".$name." PRIMARY KEY (".implode(',', $aFields).")";
        // $return = "ALTER TABLE ".$table." ADD PRIMARY KEY
        // (".implode(',',$aFields).")";
        return $return;
    }

    /**
     * @see Pelican_Db::getIncludedKeysDDL
     */
    public function getIncludedKeysDDL($table, $aFields)
    {
        $return = '';

        return $return;
    }

    /**
     * @see Pelican_Db::getIndexDDL
     */
    public function getIndexDDL($table, $name, $aFields, $unique = false)
    {
        $return = "CREATE".($unique ? " UNIQUE" : "")." INDEX ".$name." ON ".$table." (".implode(',', $aFields).");";

        return $return;
    }

    /**
     * @see Pelican_Db::getReferencesDDL
     */
    public function getReferencesDDL($table, $name, $childField, $source)
    {
        $return = 'ALTER TABLE '.$table.' ADD CONSTRAINT '.$name.' FOREIGN KEY ('.$childField.') REFERENCES '.$source.';';

        return $return;
    }

    /**
     * @see Pelican_Db::getSequenceDDL
     */
    public function getSequenceDDL($name, $start = "", $increment = "")
    {
        $return = 'CREATE SEQUENCE '.$name;

        return trim($return);
    }

    /**
     * @see Pelican_Db::getUniqueKeyDDL
     */
    public function getUniqueKeyDDL($table, $name, $aField)
    {
        $return = 'ALTER TABLE {'.$table.'} ADD CONSTRAINT '.$name.' UNIQUE ('.implode(',', $aField).')';

        return $return;
    }

    /**
     * @see Pelican_Db::getUpdateSequenceDDL
     */
    public function getUpdateSequenceDDL($table, $field, $sequence_name)
    {
        $max = $oConnection->queryItem('select max('.$field.') from '.$table);
        if (! $max) {
            $max = 0;
        }
        $max ++;
        $return = "SELECT setval('".$sequence_name."', ".$max.");";

        return $return;
    }

    /**
     * @see Pelican_Db::getEndDDL
     */
    public function getEndDDL($type)
    {
        switch ($type) {
            case 'Table' :
                {
                    $return = ';';
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
     * @param $type __TYPE__
     *       	 __DESC__
     *
     * @return __TYPE__
     */
    public function nativeType($type)
    {
        $return = self::$nativeTypes [strtoupper($type)];

        return $return;
    }
}
// pg_send_query_params($dbconn, 'select count(*) from auteurs where ville =
// $1', array('Perth'));
