<?php
/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Db
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
ini_set('ingres.array_index_start', 1);
ini_set('ingres.describe', false);
ini_set('ingres.scrollable', false);

/** Couche d'abstraction Pelican_Db_Ingres
 *
 * @package Pelican
 * @subpackage Db
 */

/**
 * *
 * Cette classe permet d'avoir un accès facilité à une base de
 *
 * Données ingres. Elle offre un certain nombre de fonctionnalités
 *
 * Comme la création d'une connexion, l'exécution d'une requète
 * devant retourner un champ, une ligne ou un ensemble de ligne ou
 * encore l'affichage du temps d'exécution d'un requète, la
 * récupération
 *               du dernier enregistrement inséré au cours de la session...
 *
 * http://docs.ingres.com/Ingres/9.3/Db%20Administrator%20Guide/dbmssystemcatalogs.htm#o2581
 * http://commonity.ingres.com/wiki/PHP_Driver_Examples
 * http://commonity.ingres.com/wiki/PHP_Driver
 * http://commonity.ingres.com/wiki/Ingres_Cheatsheets
 *
 * @package Pelican
 * @subpackage Db
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 04/11/2009
 * @version 1.0
 */
class Pelican_Db_Ingres extends Pelican_Db
{
    /**
     * __DESC__
     *
     * @static
     * @access public
     * @var __TYPE__
     */
    public static $nativeTypes = array(self::BIGINT => 'INTEGER8', self::BINARY => 'BYTE', self::BLOB => 'LONG BYTE', self::BOOLEAN => 'TINYINT', self::CHAR => 'CHAR', self::CLOB => 'LONG VARCHAR', self::DATE => 'DATE', //ANSIDATE ?
    self::DATETIME => 'DATE', //ANSIDATE ?
    self::DECIMAL => 'DECIMAL', self::DOUBLE => 'FLOAT8', self::FLOAT => 'FLOAT', self::IDENTITY => 'INTEGER', self::INTEGER => 'INTEGER', self::LONGVARBINARY => 'LONG BYTE', self::LONGVARCHAR => 'LONG VARCHAR', self::NUMERIC => 'DECIMAL', self::REAL => 'FLOAT', self::SMALLINT => 'SMALLINT', self::TIME => 'DATE', self::TIMESTAMP => 'TIMESTAMP', self::TINYTEXT => 'INTEGER1', self::TINYINT => 'TINYINT', self::VARBINARY => 'BYTE VARYING', self::VARCHAR => 'VARCHAR');

    /**
     * Nom de la base de données cible (pour les messages d'erreur)
     *
     * @access public
     * @var string
     */
    public $databaseTitle = "Ingres";

    /**
     * Base de données autorisant ou non l'utilisation de variables BIND
     *
     * @access public
     * @var bool
     */
    public $allowBind = true;

    /**
     * Commiter automatiquement ou non les requêtes
     *
     * @access public
     * @var bool
     */
    public $autoCommit = false; //don't seems to work, ingres_autocommit_state returns always true after running "ingres_autocommit()"


    /**
     * Activation d'une transaction ou non
     *
     * @access public
     * @var bool
     */
    public $transaction = false;

    /**
     * Constructeur. Permet de se connecter à la base de donnée
     *
     * @access public
     * @param  string     $databaseName Le nom de la base de données
     * @param  string     $username     Le nom de l'utilisateur servant à la connexion
     * @param  string     $password     Le mot de passe de connexion
     * @param  string     $host         (option) L'adresse IP du serveur (optionnel) : 127.0.0.1
     *                                  par défaut
     * @param  string     $port         (option) Le port de connexion (optionnel) : 21064 par
     *                                  défaut
     * @param  bool       $bExit        (option) Sortie si une erreur apparait
     * @param  bool       $persistency  (option) Connection persistante ou non
     * @return Pelican_Db
     */
    public function __construct($databaseName, $username, $password, $host = "", $port = "", $bExit = true, $persistency = true)
    {
        parent::__construct($databaseName, $username, $password, $host, $port, $bExit);
        $this->databaseName = $databaseName;
        $this->user = $username;
        $this->passwd = $password;
        $this->host = ($host ? $host : "localhost");
        $this->port = ($port ? $port : "II");
        $this->persistency = $persistency;
        $this->option = array("blob_segment_length" => 8192, "date_format" => INGRES_DATE_FINNISH);
        $this->database = sprintf("@%s,tcp_ip,%s[%s,%s]::%s", $this->host, $this->port, $this->user, $this->passwd, $this->databaseName);
        //var_dump($this->database);

        /** Attention : définir les variables d'environnement II_SYSTEM pour chaque instance */
        $this->info = $this->getInfo();
        // connexion à la base de données
        if ($this->persistency) {
            $this->id = ingres_pconnect($this->host . "::" . $this->databaseName, $this->user, $this->passwd, $this->option) or $this->error($this->message("Impossible de connecter au serveur " . $this->databaseTitle));
            //$this->id = ingres_pconnect($this->database, "", "", $this->option) or $this->error($this->message("Impossible de connecter au serveur ".$this->databaseTitle));

        } else {
            $this->id = ingres_connect($this->host . "::" . $this->databaseName, $this->user, $this->passwd, $this->option) or $this->error($this->message("Impossible de connecter au serveur " . $this->databaseTitle));
            //$this->id = ingres_connect($this->database, "", "", $this->option) or $this->error($this->message("Impossible de connecter au serveur ".$this->databaseTitle));

        }
        if ($this->autoCommit && !ingres_autocommit_state($this->id)) {
            ingres_autocommit($this->id);
        } elseif (ingres_autocommit_state($this->id) && !$this->autoCommit) {
            ingres_autocommit($this->id);
        }
        //	ingres_query($this->id,"set lockmode session where readlock=nolock");
        //	ingres_query($this->id,"set session read write ,isolation level read uncommitted");

    }

    /**
     * Test d'existence du client PHP d'Ingres
     *
     * @static
     * @access public
     * @return __TYPE__
     */
    public static function is_available()
    {
        return function_exists('ingres_connect');
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::beginTrans()
     * @return __TYPE__
     */
    public function beginTrans()
    {
        if (!$this->autoCommit) {
            $this->transaction = true;
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::close()
     * @return __TYPE__
     */
    public function close()
    {
        if ($this->transaction) {
            if (!connection_aborted()) {
                $this->commit();
            } else {
                //     $this->error($this->message("Connection interrompue"));
                $this->rollback();
            }
        }
        if (is_resource($this->id)) {
            ingres_close($this->id) or $this->error($this->message("Impossible de fermer la connexion !"));
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::queryInit()
     * @param  __TYPE__ $query    __DESC__
     * @param  __TYPE__ $param    (option) __DESC__
     * @param  __TYPE__ $paramLob (option) __DESC__
     * @param  bool     $debug    (option) __DESC__
     * @param  string   $type     (option) __DESC__
     * @return __TYPE__
     */
    public function queryInit($query, $param = array(), $paramLob = array(), $debug = false, $type = "")
    {
        $this->lastError = "";
        if (!self::isSelect($query) && !$this->transaction) {
            $this->beginTrans();
        }
        /*
        $query = str_replace(' VALUES ',' OVERRIDING SYSTEM VALUE VALUES ', $query);
        debug($query);
        */
        $this->prepareBind($query, $param, $paramLob);
        if ($debug) {
            debug($query, "queryInit");
        }
        // execution de la requête
        $this->data = array();
        $this->type = array();
        $this->name = array();
        $this->len = array();
        $this->query = $query;
        $this->param = $param;
        if ($this->param) {
            $this->result = ingres_query($this->id, $this->query, $this->param);
        } else {
            $this->result = ingres_query($this->id, $this->query);
        }
        if ($this->result) {
            if ($type) {
                $this->fetch_results($this->result, $type);
            }
            // sauvegarde du nombre de champs renvoyés par la requête
            $this->fields = ingres_num_fields($this->result);
            // sauvegarde du nombre de lignes renvoyés par la requête
            $this->rows = $this->affectedRows;
            $start = ini_get('ingres.array_index_start');
            for ($i = $start;$i < $this->fields + $start;$i++) {
                $this->type[$i - $start] = ingres_field_type($this->result, $i);
                $this->name[$i - $start] = ingres_field_name($this->result, $i);
                $this->len[$i - $start] = ingres_field_length($this->result, $i);
            }

            return true;
        } else {
            if (ingres_errno($this->id)) {
                trigger_error("Error " . ingres_errno() . "-" . ingres_error(), E_USER_ERROR);
                $this->lastError = ingres_error($this->id);
                $this->error($this->message("Impossible d'ex&eacute;cuter la requ&egrave;te :", $query));

                return false;
            }

            return true;
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::query()
     * @param  __TYPE__ $result __DESC__
     * @param  string   $type   (option) __DESC__
     * @return __TYPE__
     */
    public function fetch_results($result, $type = "")
    {
        $count = 0;
        if ($type == "queryObj") {
            while ($return = ingres_fetch_object($result, INGRES_BOTH)) {
                $this->data[$count] = $return;;
            }
        } else {
            while ($return = ingres_fetch_array($result, INGRES_BOTH)) {
                switch ($type) {
                    case "query": {
                                if ($this->isselect($this->query)) {
                                    while (list($key, $val) = each($return)) {
                                        $this->data[$key][$count] = $val;
                                        $this->data[strtoupper($key) ][$count] = $val;
                                    }
                                }
                            break;
                        }
                    case "queryTab": {
                            while (list($key, $val) = each($return)) {
                                $this->data[$count][$key] = $val;
                                $this->data[$count][strtoupper($key) ] = $val;
                            }
                            break;
                        }
                    case "queryRow": {
                            while (list($key, $val) = each($return)) {
                                $this->data[$key] = $val;
                                $this->data[strtoupper($key) ] = $val;
                            }
                            $exit = true;
                            break;
                        }
                    case "queryItem": {
                            $keys = array_keys($return);
                            $this->data = $return[$keys[0]];
                            $exit = true;
                            break;
                        }
                    default: {
                            break;
                        }
                    }
                    $count++;
                    if (isset($exit) && $exit) {
                        break;
                    }
                }
            }
            $this->affectedRows = $count;
        }

        /**
         * __DESC__
         *
         * @access public
         * @see Pelican_Db::query()
         * @param __TYPE__ $query __DESC__
         * @param __TYPE__ $param (option) __DESC__
         * @param __TYPE__ $paramLob (option) __DESC__
         * @param bool $debug (option) __DESC__
         * @return __TYPE__
         */
        public function query($query, $param = array(), $paramLob = array(), $debug = false)
        {
            $this->queryInit($query, $param, $paramLob, $debug, "query");
        }

        /**
         * __DESC__
         *
         * @access public
         * @see Pelican_Db::queryItem()
         * @param __TYPE__ $query __DESC__
         * @param __TYPE__ $param (option) __DESC__
         * @param __TYPE__ $paramLob (option) __DESC__
         * @param bool $light (option) __DESC__
         * @param bool $debug (option) __DESC__
         * @return __TYPE__
         */
        public function queryItem($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
        {
            $this->queryInit($query, $param, $paramLob, $debug, "queryItem");

            return $this->data;
        }

        /**
         * __DESC__
         *
         * @access public
         * @see Pelican_Db::queryRow()
         * @param __TYPE__ $query __DESC__
         * @param __TYPE__ $param (option) __DESC__
         * @param __TYPE__ $paramLob (option) __DESC__
         * @param bool $light (option) __DESC__
         * @param bool $debug (option) __DESC__
         * @return __TYPE__
         */
        public function queryRow($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
        {
            $this->queryInit($query, $param, $paramLob, $debug, "queryRow");

            return ($this->data);
        }

        /**
         * __DESC__
         *
         * @access public
         * @see Pelican_Db::queryTab()
         * @param __TYPE__ $query __DESC__
         * @param __TYPE__ $param (option) __DESC__
         * @param __TYPE__ $paramLob (option) __DESC__
         * @param bool $light (option) __DESC__
         * @param bool $debug (option) __DESC__
         * @return __TYPE__
         */
        public function queryTab($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
        {
            $this->queryInit($query, $param, $paramLob, $debug, "queryTab");

            return ($this->data);
        }

        /**
         * __DESC__
         *
         * @access public
         * @see Pelican_Db::queryObj()
         * @param __TYPE__ $query __DESC__
         * @param __TYPE__ $param (option) __DESC__
         * @param __TYPE__ $paramLob (option) __DESC__
         * @param bool $light (option) __DESC__
         * @param bool $debug (option) __DESC__
         * @return __TYPE__
         */
        public function queryObj($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
        {
            $this->queryInit($query, $param, $paramLob, $debug, "queryObj");

            return ($this->data);
        }

        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $name __DESC__
         * @param __TYPE__ $param (option) __DESC__
         * @param __TYPE__ $paramLob (option) __DESC__
         * @param bool $light (option) __DESC__
         * @param bool $debug (option) __DESC__
         * @param bool $xml (option) __DESC__
         * @return __TYPE__
         */
        public function queryStoredProcedure($name, $param = array(), $paramLob = array(), $light = true, $debug = false, $xml = false)
        {
            $sql = "select * from " . $name . "(" . ($param ? implode(",", $param) : "") . ")";
            if (!$xml) {
                return $this->queryTab($sql);
            } else {
                return $this->queryXML($sql);
            }
        }

        /**
         * __DESC__
         *
         * @access public
         * @see Pelican_Db::commit()
         * @return __TYPE__
         */
        public function commit()
        {
            if ($this->id && !$this->autoCommit) {
                $this->result = ingres_commit($this->id);
            }

            return true;
        }

        /**
         * __DESC__
         *
         * @access public
         * @see Pelican_Db::rollback()
         * @return __TYPE__
         */
        public function rollback()
        {
            if ($this->id && !$this->autoCommit) {
                $this->result = @ingres_rollback($this->id);
            }

            return true;
        }

        /**
         * __DESC__
         *
         * @access public
         * @see Pelican_Db::getLastOid()
         * @param string $query (option) __DESC__
         * @return __TYPE__
         */
        public function getLastOid($query = "")
        {
            // on met la requete en minuscule sur une ligne
            $query = preg_replace("/[\s\n\r]+/", " ", trim($query));
            // on recupere le nom de la table dans le cas d'un INSERT
            if (preg_match("/^INSERT\s+INTO\s+([^\s]+)\s+.*/i", $query, $matches)) {
                // mise en forme de la requete
                // récupérer le cas du serial
                $query = "SELECT * FROM " . $matches[1] . " WHERE oid = " . ingres_last_oid($this->result);
                // on recupere le dernier enregistrement
                $this->lastInsertedId = $this->queryItem($query);

                return $this->lastInsertedId;
            }
        }

        /**
         * __DESC__
         *
         * @access public
         * @see Pelican_Db::getNextId()
         * @param __TYPE__ $table __DESC__
         * @return __TYPE__
         */
        public function getNextId($table)
        {
            $return = "";
            $info = $this->describeTable($table);
            $count = count($info);
            if ($info) {
                for ($i = 0;$i < $count;$i++) {
                    if ($info[$i]["sequence_name"]) {
                        $return = $this->queryItem("SELECT NEXT VALUE FOR \"" . $info[$i]["sequence_name"] . "\"");
                        $i = $count;
                    }
                }
            }

            return $return;
        }

        /**
         * __DESC__
         *
         * @access public
         * @see Pelican_Db::getError()
         * @return __TYPE__
         */
        public function getError()
        {
            if ($this->id) {
                $errno = ingres_errno($this->id);
                if ($this->lastError) {
                    $erreur = $this->lastError;
                } else {
                    $erreur = ingres_error($this->id);
                }
            }

            return array("code" => $errno, "message" => $erreur);
        }

        /**
         * __DESC__
         *
         * @access public
         * @see Pelican_Db::getInfo()
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
         * __DESC__
         *
         * @access public
         * @see Pelican_Db::getDbInfo()
         * @param __TYPE__ $type __DESC__
         * @param string $name (option) __DESC__
         * @param __TYPE__ $id (option) __DESC__
         * @return __TYPE__
         */
        public function getDbInfo($type, $name = "", $id = "_ID")
        {
            switch ($type) {
                case 'infos': {

                            /** A COMPLETER */

                            return null;
                        break;
                    }
                case 'tables': {
                        $query = "SELECT table_name FROM iitables where table_type='T'  and table_name not like 'iietab%' and table_owner='" . $this->user . "' order by table_name";
                        $this->query($query);
                        if ($this->data) {
                            foreach ($this->data['table_name'] as $table) {
                                $return[] = trim($table);
                            }
                        }
                        break;
                    }
                case 'views': {
                        $query = "SELECT table_name FROM iitables where table_type='V' and table_owner='" . $this->user . "' order by table_name";
                        $this->query($query);
                        if ($this->data) {
                            foreach ($this->data['table_name'] as $table) {
                                $return[] = trim($table);
                            }
                        }
                        break;
                    }
                case 'fields': {
                        $query = "select
                                column_name as field,
                                column_datatype as type,
                                column_length as length,
                                column_nulls as nulls,
                                column_default_val as default,
                                key_sequence key
                                from iicolumns
                                where table_owner='" . $this->user . "'
                                and table_name='" . $name . "'
                                order by column_sequence";
                        $result = $this->queryTab($query);
                        $i = 0;
                        foreach ($result as $ligne) {
                            $return[$i]["field"] = trim(strtoupper($ligne["field"]));
                            $return[$i]["type"] = trim(strtolower($ligne["type"]));
                            $return[$i]["null"] = (trim($ligne["nulls"]) == 'Y');
                            $return[$i]["default"] = trim($ligne["default"]);
                            $return[$i]["length"] = trim($ligne["length"]);
                            if (!$return[$i]["length"]) {
                                $return[$i]["length"] = null;
                            }
                            if ($return[$i]["type"] == 'INTEGER' || $return[$i]["type"] == 'FLOAT') {
                                $return[$i]["length"] = null;
                            }
                            $return[$i]["key"] = "";
                            $return[$i]["extra"] = "";
                            $return[$i]["increment"] = "";
                            $return[$i]["key"] = ($result[$i]["key"] ? true : false);
                            $return[$i]["sequence"] = (strpos(strToLower(" " . $ligne["default"]), "next value for") > 0 ? true : false);
                            if ($return[$i]["sequence"]) {
                                $return[$i]["sequence_name"] = str_replace(array("next value for ", "\""), "", $return[$i]["default"]);
                                $return[$i]["sequence_name"] = str_replace($this->user . '.', '', $return[$i]["sequence_name"]);
                                $return[$i]["default"] = "";
                                //$return[$i]["increment"] = true;

                            }
                            $i++;
                        }
                        $Keys = $this->getDbInfo('keys', $name);
                        for ($i = 0;$i < count($return);$i++) {
                            if (is_array($Keys)) {
                                if (in_array($return[$i]["field"], $Keys)) {
                                    $return[$i]["key"] = true;
                                }
                            }
                        }
                        break;
                    }
                case 'sequences': {
                        $query = "select
                                seq_name
                                from iisequence
                                where seq_owner ='" . $this->user . "'";
                        $this->query($query);
                        $return = $this->data['seq_name'];
                        break;
                    }
                case 'indexes': {
                        $sql = "select
                                table_name
                                from iitables
                                inner join iirelation on (iitables.table_name=iirelation.relid and table_owner=relowner)
                                where table_type='I'
                                and table_owner='" . $this->user . "'
                                and storage_structure='ISAM'
                                and reltid in (
                                select reltid from iirelation
                                where relid = '" . $name . "')
                                order by reltidx";
                        $indexes = $this->queryTab($sql);
                        if ($indexes) {
                            foreach ($indexes as $index) {
                                $index['table_name'] = trim($index['table_name']);
                                $return[strtoupper($index['table_name']) ]["type"] = "";
                                $return[strtoupper($index['table_name']) ]["name"] = strtoupper($index['table_name']);
                                $sql = "select
                                *
                                from iicolumns
                                where table_owner='" . $this->user . "'
                                and table_name='" . trim($index['table_name']) . "'
                                and column_name != 'tidp'
                                order by column_sequence";
                                $fields = $this->queryTab($sql);
                                foreach ($fields as $f) {
                                    $return[strtoupper($index['table_name']) ]["fields"][$i] = strtoupper(trim($f['column_name']));
                                    $i++;
                                }
                            }
                        }

                        return $return;
                        break;
                    }
                case 'keys': {
                        $query = "select
                                constraint_name,
                                table_name,
                                column_name,
                                key_position
                                from iikeys
                                inner join iischema on (iischema.schema_name=iikeys.schema_name)
                                where table_name ='" . $name . "'
                                and schema_owner='" . $this->user . "'
                                order by key_position";
                        $keysTemp = $this->queryTab($query);
                        foreach ($keysTemp as $column) {
                            $return[$column['key_position']] = trim(strtoupper($column['column_name']));
                        }
                        break;
                    }
                case 'foreign_keys': {

                        /** A COMPLETER */
                        //iiindex
                        return null;
                        break;
                    }
                case 'functions': {

                        /** A COMPLETER */
                        $return = null;
                        break;
                    }
                default: {
                        return null;

                        /** fin du case */
                    }
                }
                if (!isset($return)) {
                    return null;
                }

                return $return;
            }

            /**
             * __DESC__
             *
             * @access public
             * @see Pelican_Db::getNow()
             * @return __TYPE__
             */
            public function getNow()
            {
                return "now";
            }

            /**
             * __DESC__
             *
             * @access public
             * @see Pelican_Db::getDateDiffClause()
             * @param __TYPE__ $date1 __DESC__
             * @param __TYPE__ $date2 __DESC__
             * @return __TYPE__
             */
            public function getDateDiffClause($date1, $date2)
            {
                $return = " DATEDIFF(" . $date1 . "," . $date2 . ") ";

                return $return;
            }

            /**
             * __DESC__
             *
             * @access public
             * @see Pelican_Db::getDateAddClause()
             * @param __TYPE__ $date __DESC__
             * @param __TYPE__ $interval __DESC__
             * @return __TYPE__
             */
            public function getDateAddClause($date, $interval)
            {
                $return = " DATE_ADD(" . $date . ", INTERVAL " . $interval . " DAY) ";

                return $return;
            }

            /**
             * __DESC__
             *
             * @access public
             * @see Pelican_Db::dateStringToSql()
             * @param string $strChaine __DESC__
             * @param bool $hour (option) __DESC__
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
                    $complement = " " . $hour[1];
                }
                if (count($arr) > 1) {
                    switch ($this->dateFormat) {
                        case "MM/DD/YYYY": {
                                    $temp_contenu_date = "'" . $arr[2] . "-" . $arr[0] . "-" . $arr[1] . $complement . "'";
                                break;
                            }
                        default: //DD/MM/YYYY
                            {
                                $temp_contenu_date = "'" . $arr[2] . "-" . $arr[1] . "-" . $arr[0] . $complement . "'";
                                break;
                            }
                        }
                    } else {
                        $temp_contenu_date = "'" . $strChaine . "'";
                    }

                    return ($temp_contenu_date);
                }

                /**
                 * __DESC__
                 *
                 * @access public
                 * @see Pelican_Db::dateSqlToString()
                 * @param __TYPE__ $dateField __DESC__
                 * @param bool $hour (option) __DESC__
                 * @param string $complement (option) __DESC__
                 * @return __TYPE__
                 */
                public function dateSqlToString($dateField, $hour = false, $complement = "")
                {
                    if ($hour) {
                        $complement = " HH:mm";
                    }
                    if (!isset($this->dateFormat)) $this->dateFormat = "DD/MM/YYYY";
                    switch ($this->dateFormat) {
                        case "MM/DD/YYYY": {
                                    $temp_date = "TO_CHAR(" . $dateField . ",'MM/DD/YYYY" . $complement . "')";
                                break;
                            }
                        default: //DD/MM/YYYY
                            {
                                $temp_date = "TO_CHAR(" . $dateField . ",'DD/MM/YYYY" . $complement . "')";
                                break;
                            }
                        }

                        return ($temp_date);
                    }

                    /**
                     * __DESC__
                     *
                     * @access public
                     * @see Pelican_Db::dateSqlToStringShort()
                     * @param __TYPE__ $dateField __DESC__
                     * @return __TYPE__
                     */
                    public function dateSqlToStringShort($dateField)
                    {
                        if (!isset($this->dateFormat)) $this->dateFormat = "DD/MM/YYYY";
                        switch ($this->dateFormat) {
                            case "MM/DD/YYYY": {
                                        $temp_date = "TO_CHAR(" . $dateField . ",'MM/DD/YYYY')";
                                    break;
                                }
                            default: //DD/MM/YYYY
                                {
                                    $temp_date = "TO_CHAR(" . $dateField . ",'DD/MM/YYYY')";
                                    break;
                                }
                            }

                            return ($temp_date);
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::dateToYear()
                         * @param __TYPE__ $dateField __DESC__
                         * @return __TYPE__
                         */
                        public function dateToYear($dateField)
                        {
                            $temp_date = "TO_CHAR(" . $dateField . ",'YYYY')";

                            return $temp_date;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::dateToMonth()
                         * @param __TYPE__ $dateField __DESC__
                         * @return __TYPE__
                         */
                        public function dateToMonth($dateField)
                        {
                            $temp_date = "TO_CHAR(" . $dateField . ",'MM')";

                            return $temp_date;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::stringToSql()
                         * @param __TYPE__ $value __DESC__
                         * @return __TYPE__
                         */
                        public function stringToSql($value)
                        {
                            return ingres_escape_string($this->id, $value);
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getLimitedSQL()
                         * @param __TYPE__ $query __DESC__
                         * @param __TYPE__ $min __DESC__
                         * @param __TYPE__ $length __DESC__
                         * @return __TYPE__
                         */
                        public function getLimitedSQL($query, $min, $length)
                        {
                            $return = $query . " OFFSET " . max($min - 1, 0) . " FETCH FIRST " . $length . " ROWS ONLY";

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getCountSQL()
                         * @param __TYPE__ $query __DESC__
                         * @param __TYPE__ $countFields __DESC__
                         * @return __TYPE__
                         */
                        public function getCountSQL($query, $countFields)
                        {
                            $deb = strpos(strToLower($query), "from");
                            $fin = $this->arrayMin(array(strpos(strToLower($query), "order by"), strpos(strToLower($query), "group by"), strpos(strToLower($query), "having")));
                            if (!$fin) {
                                $fin = strlen($query);
                            }
                            $sql = "select count(*) from (select " . ($countFields ? $countFields : "*") . " " . substr($query, $deb, ($fin - $deb));
                            if ($countFields && $countFields != "*") $sql.= " group by " . $countFields;
                            $sql.= ") AS A";

                            return $sql;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getSearchClause()
                         * @param __TYPE__ $field __DESC__
                         * @param __TYPE__ $value __DESC__
                         * @param __TYPE__ $position __DESC__
                         * @param __TYPE__ $bindName __DESC__
                         * @param __TYPE__ $aBind __DESC__
                         * @param __TYPE__ $join (option) __DESC__
                         * @return __TYPE__
                         */
                        public function getSearchClause($field, $value, $position = 0, $bindName = "", &$aBind, $join = "OR")
                        {
                            $return = " " . $field . " like  '%" . $value . "%' ";

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getSearchClauseLike()
                         * @param __TYPE__ $field __DESC__
                         * @param __TYPE__ $value __DESC__
                         * @param __TYPE__ $position __DESC__
                         * @param __TYPE__ $bindName __DESC__
                         * @param __TYPE__ $aBind __DESC__
                         * @param __TYPE__ $join (option) __DESC__
                         * @return __TYPE__
                         */
                        public function getSearchClauseLike($field, $value, $position = 0, $bindName = "", &$aBind, $join = "OR")
                        {
                            $return = " " . $field . " like  '%" . $value . "%' ";

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getNVLClause()
                         * @param __TYPE__ $clause __DESC__
                         * @param __TYPE__ $value __DESC__
                         * @return __TYPE__
                         */
                        public function getNVLClause($clause, $value)
                        {
                            $return = " COALESCE(" . $clause . "," . $value . ") ";

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getConcatClause()
                         * @param __TYPE__ $aValue __DESC__
                         * @return __TYPE__
                         */
                        public function getConcatClause($aValue)
                        {
                            $return = " " . implode("||", $aValue) . " ";

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getCaseClause()
                         * @param __TYPE__ $field __DESC__
                         * @param __TYPE__ $aClause __DESC__
                         * @param __TYPE__ $defaultValue __DESC__
                         * @return __TYPE__
                         */
                        public function getCaseClause($field, $aClause, $defaultValue)
                        {
                            IF ($field && $aClause) {
                                $return = "CASE ";
                                foreach ($aClause as $key => $value) {
                                    $temp[] = " WHEN " . $field . ($key == "NULL" ? " IS " : "=") . $key . " THEN " . $value;
                                }
                                $return.= implode(" ", $temp);
                                $return.= " ELSE " . $defaultValue . " END ";
                            }

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::duplicateRecord()
                         * @param __TYPE__ $table __DESC__
                         * @param __TYPE__ $key __DESC__
                         * @param __TYPE__ $oldValue __DESC__
                         * @param __TYPE__ $newValue __DESC__
                         * @return __TYPE__
                         */
                        public function duplicateRecord($table, $key, $oldValue, $newValue)
                        {
                            $tableTmp = "tmp";
                            $fieldSet = $this->describeTable($table);
                            $strSQL = "INSERT INTO " . $table . " SELECT ";
                            $j = - 1;
                            foreach ($fieldSet as $field) {
                                $j++;
                                if ($field["increment"]) {
                                    $autoIncrement = $field["field"];
                                }
                                if ($field["field"] != $key) {
                                    $strSQL.= $field["field"] . ", ";
                                } else {
                                    $key_type = $field["type"];
                                    $strSQL.= $this->formatField($newValue, $field["type"]) . ", ";
                                }
                            }
                            $strSQL.= "FROM " . $table . " WHERE " . $key . "=" . $this->formatField($oldValue, $key_type);
                            $strSQL = str_replace(", FROM", " FROM", $strSQL);
                            $this->query($strSQL);
                        }

                        /** DDL clauses */

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getFieldTypeDDL
                         * @param __TYPE__ $type __DESC__
                         * @param __TYPE__ $length __DESC__
                         * @param __TYPE__ $precision __DESC__
                         * @return __TYPE__
                         */
                        public function getFieldTypeDDL($type, $length, $precision)
                        {
                            if (in_array($type, array(self::SMALLINT, self::TINYINT, self::INTEGER, self::BIGINT, self::IDENTITY))) {
                                $length = null;
                                $precision = null;
                            }
                            $complement = '';
                            $return = self::nativeType($type) . ($length ? '(' . $length . ($precision ? ',' . $precision : '') . ')' : '') . $complement;

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getFieldNullDDL
                         * @param __TYPE__ $null __DESC__
                         * @return __TYPE__
                         */
                        public function getFieldNullDDL($null)
                        {
                            $return = '';
                            if (!$null) {
                                $return = 'NOT NULL';
                            }

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getFieldDefaultDDL
                         * @param __TYPE__ $default __DESC__
                         * @param __TYPE__ $type __DESC__
                         * @param __TYPE__ $null __DESC__
                         * @return __TYPE__
                         */
                        public function getFieldDefaultDDL($default, $type, $null)
                        {
                            $return = '';
                            $default = str_replace('CURRENT_TIMESTAMP', '', $default);
                            if (isset($default)) {
                                // NULL non pris en compte pour les textes
                                if ($default == 'NULL' && !$null) {
                                    $return = '';
                                } else {
                                    if (in_array($type, self::$TEXT_TYPES) && $default != 'NULL') {
                                        $default = str_replace("''", "'", "'" . $default . "'");
                                        if ($default == "'") {
                                            unset($default);
                                        }
                                    }
                                    if (isset($default)) {
                                        $return = trim('default ' . $default);
                                    }
                                }
                            }
                            if ($return == 'default' || $return == 'default NULL') {
                                $return = '';
                            }

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getFieldIncrementDDL
                         * @param __TYPE__ $increment __DESC__
                         * @param string $type (option) __DESC__
                         * @return __TYPE__
                         */
                        public function getFieldIncrementDDL($increment, $type = "")
                        {
                            $return = '';
                            if ($increment || $type == self::IDENTITY) {
                                $return = ' GENERATED ALWAYS AS IDENTITY';
                            }

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getPrimaryKeyDDL
                         * @param __TYPE__ $table __DESC__
                         * @param __TYPE__ $name __DESC__
                         * @param __TYPE__ $aFields __DESC__
                         * @return __TYPE__
                         */
                        public function getPrimaryKeyDDL($table, $name, $aFields)
                        {
                            $return = "CONSTRAINT " . $name . " PRIMARY KEY (" . implode(',', $aFields) . ")";
                            //		$return = 'ALTER TABLE '.$table.' ADD CONSTRAINT '.$name.' PRIMARY KEY ('.implode(',',$aFields).');';
                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getIncludedKeysDDL
                         * @param __TYPE__ $table __DESC__
                         * @param __TYPE__ $aFields __DESC__
                         * @return __TYPE__
                         */
                        public function getIncludedKeysDDL($table, $aFields)
                        {
                            $return = '';

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getIndexDDL
                         * @param __TYPE__ $table __DESC__
                         * @param __TYPE__ $name __DESC__
                         * @param __TYPE__ $aFields __DESC__
                         * @param bool $unique (option) __DESC__
                         * @return __TYPE__
                         */
                        public function getIndexDDL($table, $name, $aFields, $unique = false)
                        {
                            $return = "CREATE" . ($unique ? " UNIQUE" : "") . " INDEX " . $name . " ON " . $table . " (" . implode(',', $aFields) . ");";

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getReferencesDDL
                         * @param __TYPE__ $table __DESC__
                         * @param __TYPE__ $name __DESC__
                         * @param __TYPE__ $childField __DESC__
                         * @param __TYPE__ $source __DESC__
                         * @return __TYPE__
                         */
                        public function getReferencesDDL($table, $name, $childField, $source)
                        {
                            $return = 'ALTER TABLE ' . $table . ' ADD CONSTRAINT ' . $name . ' FOREIGN KEY (' . $childField . ') REFERENCES ' . $source . ';';

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getSequenceDDL
                         * @param __TYPE__ $name __DESC__
                         * @param string $start (option) __DESC__
                         * @param string $increment (option) __DESC__
                         * @return __TYPE__
                         */
                        public function getSequenceDDL($name, $start = "", $increment = "")
                        {
                            $return = 'CREATE SEQUENCE ' . $name;

                            return trim($return);
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getUniqueKeyDDL
                         * @param __TYPE__ $table __DESC__
                         * @param __TYPE__ $name __DESC__
                         * @param __TYPE__ $aField __DESC__
                         * @return __TYPE__
                         */
                        public function getUniqueKeyDDL($table, $name, $aField)
                        {
                            $return = 'ALTER TABLE {' . $table . '} ADD CONSTRAINT ' . $name . ' UNIQUE (' . implode(',', $aField) . ')';

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getUpdateSequenceDDL
                         * @param __TYPE__ $table __DESC__
                         * @param __TYPE__ $field __DESC__
                         * @param __TYPE__ $sequence_name __DESC__
                         * @return __TYPE__
                         */
                        public function getUpdateSequenceDDL($table, $field, $sequence_name)
                        {
                            $max = $oConnection->queryItem('select max(' . $field . ') from ' . $table);
                            if (!$max) $max = 0;
                            $max++;
                            $return = "ALTER SEQUENCE " . $sequence_name . " RESTART WITH " . $max . ");";

                            return $return;
                        }

                        /**
                         * __DESC__
                         *
                         * @access public
                         * @see Pelican_Db::getEndDDL
                         * @param __TYPE__ $type __DESC__
                         * @return __TYPE__
                         */
                        public function getEndDDL($type)
                        {
                            switch ($type) {
                                case 'Table': {
                                            $return = ';';
                                        break;
                                    }
                                }

                                return $return;
                            }

                            /**
                             * __DESC__
                             *
                             * @static __DESC__
                             * @access public
                             * @param __TYPE__ $type __DESC__
                             * @return __TYPE__
                             */
                            public function nativeType($type)
                            {
                                $return = self::$nativeTypes[strtoupper($type) ];

                                return $return;
                            }
                        }
                        // ingres_ charset
                        // format de date
                        // TO_CHAR(   ,'DD/MM/YYYY')
                        // TO_DATE(  ,'DD/MM/YYYY')
                        // TO_DATE(  ,'MM')
                        // TO_DATE(  ,'YYYY')
                        // DATE_ADD
                        // DATEDIFF
