<?php
/** Couche d'abstraction sqlite
 *
 */

/**
 * cette classe permet d'avoir un accès facilité à une base de
 * données sqlite. Elle offre un certain nombre de fonctionnalités
 * comme la création d'une connexion, l'exécution d'une requête
 * devant retourner un champ, une ligne ou un ensemble de ligne ou
 * encore l'affichage du temps d'exécution d'un requête, la récupération
 * du dernier enregistrement inséré au cours de la session...
 *
 * @version 2.0
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 13/10/2008
 */
class Pelican_Db_Sqlite extends Pelican_Db
{
    public $nativeTypes = array(self::BIGINT => 'BIGINT' , self::BINARY => 'TINYBLOB' , self::BLOB => 'BLOB' , self::CHAR => 'CHAR' , self::CLOB => 'LONGTEXT' , self::DATE => 'DATE' , self::DECIMAL => 'DECIMAL' , self::DOUBLE => 'DOUBLE' , self::FLOAT => 'FLOAT' , self::INTEGER => 'INTEGER' , self::LONGVARCHAR => 'TEXT' , self::NUMERIC => 'NUMERIC' , self::REAL => 'REAL' , self::SMALLINT => 'SMALLINT' , self::TIME => 'TIME' , self::TIMESTAMP => 'TIMESTAMP' , self::TINYINT => 'TINYINT' , self::VARCHAR => 'VARCHAR');

    /**
     * Nom de la base de données cible (pour les messages d'erreur).
     *
     * @var string
     */
    public $databaseTitle = "SQLite";

    /**
     * Base de données autorisant ou non l'utilisation de variables BIND.
     *
     * @var boolean
     */
    public $allowBind = false;

    /**
     * Commiter automatiquement ou non les requêtes.
     *
     * @var boolean
     */
    public $autoCommit = false;

    /**
     * @return database
     *
     * @param string $databaseName le nom de la base de données
     * @param string $username     le nom de l'utilisateur servant à la connexion
     * @param string $password     le mot de passe de connexion
     * @param string $host         l'adresse IP du serveur (optionnel) : 127.0.0.1 par défaut
     * @param string $port         le port de connexion (optionnel) : 3306 par défaut
     * @desc Constructeur. Permet de se connecter à la base de donnée
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
            $this->id = sqlite_pconnect($this->host.":".$this->port, $this->user, $this->passwd) or $this->error($this->message("Impossible de connecter au serveur ".$this->databaseTitle));
        } else {
            $this->id = sqlite_connect($this->host.":".$this->port, $this->user, $this->passwd) or $this->error($this->message("Impossible de connecter au serveur ".$this->databaseTitle));
        }
        sqlite_select_db($databaseName, $this->id) or $this->error($this->message("Impossible de s&eacute;lectionner la base ".$this->databaseTitle));

        if (!$this->autoCommit) {
            $this->result = sqlite_query($this->id, 'BEGIN');
        } else {    //   $this->result = sqlite_query($this->id);
        }
    }

    /**
     * @see Pelican_Db::is_available()
     */
    public static function is_available()
    {
        return function_exists('sqlite_connect');
    }

    /**
     * @see Pelican_Db::close()
     */
    public function close()
    {
        if (!connection_aborted()) {
            $this->commit();
        } else {
            //     $this->error($this->message("Connection interrompue"));
            $this->rollback();
        }
        sqlite_close($this->id) or $this->error($this->message("Impossible de fermer la connexion !"));
    }

    /**
     * @see Pelican_Db::queryInit()
     */
    public function queryInit($query, $param = array(), $paramLob = array(), $debug = false)
    {
        $this->err = array();

        if ($param) {
            $query = strtr($query, array_map("nvl", $param));
        } else {    //debug($query,"non bindé");
        }

        if ($debug) {
            debug($query);
        }

        // execution de la requête
        $this->data = array();
        $this->type = array();
        $this->name = array();
        $this->len = array();
        $this->query = $query;

        $this->result = sqlite_query($this->id, $query);

        if ($this->result) {
            //nb de lignes affectées
            $this->affectedRows = (int) sqlite_changes();

            if (is_resource($this->result)) {
                // sauvegarde du nombre de champs renvoyés par la requête
                $this->fields = sqlite_num_fields($this->result);

                // sauvegarde du nombre de lignes renvoyés par la requête
                $this->rows = sqlite_num_rows($this->result);

                for ($i = 0; $i < $this->fields; $i++) {
                    $this->type[] = sqlite_field_type($this->result, $i);
                    $this->name[] = sqlite_field_name($this->result, $i);
                    $this->len[] = sqlite_field_len($this->result, $i);
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

            return true;
        } else {
            $this->err['no'] = sqlite_errno($this->id);
            $this->err['error'] = sqlite_error($this->id);
            $this->error($this->message("Impossible d'ex&eacute;cuter la requ&egrave;te :", $query));

            return false;
        }
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
                for ($num_ligne = 0; $num_ligne < $this->rows; $num_ligne++) {
                    $temp = sqlite_fetch_array($this->result, sqlite_ASSOC) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
                    while (list($key, $val) = each($temp)) {
                        $data_temp[$key][$num_ligne] = $val;
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
        // suppression de l'ancien résultat
        unset($this->data);

        // execution de la requete
        if ($this->queryInit($query, $param, $paramLob, $debug) and $this->rows > 0) {
            // recuperation du résultat
            $tab_result = sqlite_fetch_array($this->result, ($light ? sqlite_NUM : sqlite_BOTH)) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            $keys = array_keys($tab_result);
            $this->data = $tab_result[$keys[0]];

            // liberation de la memoire
            sqlite_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
        }

        // renvoie du résultat
        if (!$this->data) {
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
            $this->data = sqlite_fetch_array($this->result, ($light ? sqlite_ASSOC : sqlite_BOTH)) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));

            // liberation de la memoire
            sqlite_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
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
            for ($num_ligne = 0; $num_ligne < $this->rows; $num_ligne++) {
                $this->data[$num_ligne] = sqlite_fetch_array($this->result, ($light ? sqlite_ASSOC : sqlite_BOTH)) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            }
            // liberation de la memoire
            sqlite_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
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
            for ($num_ligne = 0; $num_ligne < $this->rows; $num_ligne++) {
                $this->data[$num_ligne] = sqlite_fetch_object($this->result) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            }
            // liberation de la memoire
            sqlite_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
        }

        // renvoie du résultat
        return ($this->data);
    }

    /**
     * @see Pelican_Db::commit()
     */
    public function commit()
    {
        $this->result = sqlite_query($this->id, "COMMIT");

        return true;
    }

    /**
     * @see Pelican_Db::rollback()
     */
    public function rollback()
    {
        $this->result = sqlite_query($this->id, "ROLLBACK");

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
        if (($query == "") || preg_match("/^INSERT\s+INTO\s+([^\s]+)\s+.*/i", $query, $matches)) {
            // on recupere le dernier enregistrement
            $this->lastInsertedId = sqlite_last_insert_rowid($this->id);

            return $this->lastInsertedId;
        }
    }

    /**
     * @see Pelican_Db::getNextId()
     */
    public function getNextId($table)
    {
        /* A compléter, pour vérifier les champs obligatoires */
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
        if (!$this->err) {
            $this->err['no'] = sqlite_errno($this->id);
            $this->err['error'] = sqlite_error($this->id);
        }

        return array("code" => $this->err['no'] , "message" => $this->err['error']);
    }

    /**
     * @see Pelican_Db::getInfo()
     */
    public function getInfo()
    {
        $return["type"] = $this->databaseTitle;
        $return["host"] = $_SERVER["SERVER_NAME"];
        $return["instance"] = $this->databaseName;

        return $return;
    }

    /**
     * @see Pelican_Db::getDbInfo()
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
                    if (preg_match('/([0-9]+\.([0-9\.])+)/', $str, $arr)) {
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
                    $query = "SELECT name FROM sqlite_master WHERE type='table' UNION ALL SELECT name FROM sqlite_temp_master WHERE type='table' ORDER BY name";
                    $this->query($query);
                    $return = $this->data['name'];
                    break;
                }
            case 'views':
                {
                    return;
                    break;
                }
            case 'fields':
                {
                    //           $query = 'SHOW FIELDS FROM '.$name;
                    $FOREIGN_KEYS = $this->getDbInfo('foreign_keys', $name);
                    $query = 'PRAGMA table_info('.$name->name.')';
                    $result = $this->queryTab($query);
                    foreach ($result as $ligne) {
                        $return[$i] = array();
                        $return[$i]["field"] = $ligne["name"];
                        $fulltype = $row['type'];
                        if (preg_match('/^([^\(]+)\(\s*(\d+)\s*,\s*(\d+)\s*\)$/', $fulltype, $matches)) {
                            $return[$i]["type"] = $matches[1];
                            $return[$i]["precision"] = $matches[2];
                            $return[$i]["scale"] = $matches[3]; // aka precision
                        } elseif (preg_match('/^([^\(]+)\(\s*(\d+)\s*\)$/', $fulltype, $matches)) {
                            $return[$i]["type"] = $matches[1];
                            $return[$i]["size"] = $matches[2];
                        } else {
                            $return[$i]["type"] = $fulltype;
                        }
                        $return[$i]["null"] = ($row['notnull'] ? false : true);
                        $return[$i]["default"] = $row['dflt_value'];
                        $return[$i]["key"] = ((($row['pk'] == 1) || (strtolower($type) == 'integer primary key')) ? true : false);
                        $return[$i]["fkey"] = "";
                        $return[$i]["extra"] = "";
                        $return[$i]["increment"] = "";
                        $return[$i]["sequence"] = false;
                        $i++;
                    }
                    break;
                }
            case 'keys':
                {
                    break;
                }
            case 'foreign_keys':
                {
                    break;
                }
            case 'indexes':
                {
                    //           $query = 'SHOW INDEX FROM '.$name;
                    $query = 'SHOW KEYS FROM '.$name;
                    $result = $this->queryTab($query);
                    foreach ($result as $ligne) {
                        $return[$ligne["Key_name"]]["name"] = $ligne["Key_name"];
                        $return[$ligne["Key_name"]]["ordered"] = ($ligne["Collation"] == 'A' ? true : false);
                        $return[$ligne["Key_name"]]["fields"][$ligne["Seq_in_index"]]["name"] = $ligne["Column_name"];
                        $return[$ligne["Key_name"]]["fields"][$ligne["Seq_in_index"]]["cardinality"] = $ligne["Cardinality"];
                        $return[$ligne["Key_name"]]["fields"][$ligne["Seq_in_index"]]["full_text"] = str_replace('FULLTEXT', '', $ligne["Comment"]);
                        $return[$ligne["Key_name"]]["fields"][$ligne["Seq_in_index"]]["comment"] = ($ligne["Comment"] == 'FULLTEXT' ? true : false);
                    }
                    break;
                }
            case 'functions':
                {
                    return;
                    break;
                }
            case 'sequences':
                {
                    return;
                    break;
                }
            default:
                {
                    return;
                /* fin du case */
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
        $return = " DATEDIFF(".$date1.",".$date2.") ";

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
                        $temp_contenu_date = "'".$arr[2]."-".$arr[0]."-".$arr[1].$complement."'";
                        break;
                    }
                default: //DD/MM/YYYY
                    {
                        $temp_contenu_date = "'".$arr[2]."-".$arr[1]."-".$arr[0].$complement."'";
                        break;
                    }
            }
        } else {
            $temp_contenu_date = "'".$strChaine."'";
        }

        return ($temp_contenu_date);
    }

    /**
     * @see Pelican_Db::dateSqlToString()
     */
    public function dateSqlToString($dateField, $hour = false, $complement = "")
    {
        if ($hour) {
            $complement = " %H:%i";
        }
        if (!isset($this->dateFormat)) {
            $this->dateFormat = "DD/MM/YYYY";
        }

        switch ($this->dateFormat) {
            case "MM/DD/YYYY":
                {
                    $temp_date = "DATE_FORMAT(".$dateField.",'%m/%d/%Y".$complement."')";
                    break;
                }
            default: //DD/MM/YYYY
                {
                    $temp_date = "DATE_FORMAT(".$dateField.",'%d/%m/%Y".$complement."')";
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
        if (!isset($this->dateFormat)) {
            $this->dateFormat = "DD/MM/YYYY";
        }

        switch ($this->dateFormat) {
            case "MM/DD/YYYY":
                {
                    $temp_date = "DATE_FORMAT(".$dateField.",'%m/%d/%y')";
                    break;
                }
            default: //DD/MM/YYYY
                {
                    $temp_date = "DATE_FORMAT(".$dateField.",'%d/%m/%y')";
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
        $temp_date = "DATE_FORMAT(".$dateField.",'%Y')";

        return $temp_date;
    }

    /**
     * @see Pelican_Db::dateToMonth()
     */
    public function dateToMonth($dateField)
    {
        $temp_date = "DATE_FORMAT(".$dateField.",'%m')";

        return $temp_date;
    }

    /**
     * @see Pelican_Db::stringToSql()
     */
    public function stringToSql($value)
    {
        return sqlite_real_escape_string($value);
    }

    /**
     * @see Pelican_Db::getLimitedSQL()
     */
    public function getLimitedSQL($query, $min, $length)
    {
        if ($min > 0) {
            $return = " LIMIT ".$min.($length > 0 ? " OFFSET ".$length : "");
        } elseif ($length > 0) {
            $return = " LIMIT -1 OFFSET ".$length;
        }

        return $return;
    }

    /**
     * @see Pelican_Db::getCountSQL()
     */
    public function getCountSQL($query, $countFields)
    {
        $deb = strpos(strToLower($query), "from");
        $fin = $this->arrayMin(array(strpos(strToLower($query), "order by"), strpos(strToLower($query), "group by"), strpos(strToLower($query), "having")));
        if (!$fin) {
            $fin = strlen($query);
        }
        $sql = "select count(".($countFields ? "distinct ".$countFields : "*").") ".substr($query, $deb, ($fin - $deb));

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
        $return = " IFNULL(".$clause.",".$value.") ";

        return $return;
    }

    /**
     * @see Pelican_Db::getConcatClause()
     */
    public function getConcatClause($aValue)
    {
        $return = " CONCAT(".implode(",", $aValue).") ";

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
                $temp[] = " WHEN ".$key." THEN ".$value;
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
        $j = -1;
        foreach ($fieldSet as $field) {
            $j++;
            if ($field["increment"]) {
                $autoIncrement = $field["field"];
            }
            if ($field["field"] != $key) {
                $strSQL .= $field["field"].", ";
            } else {
                $key_type = $field["type"];
                $strSQL .= $this->formatField($newValue, $field["type"]).", ";
            }
        }
        $strSQL .= "FROM ".$tableTmp; //." WHERE ".$key."=".$this->formatField($oldValue,$key_type);
        $strSQL = str_replace(", FROM", " FROM", $strSQL);

        $this->query("DROP TABLE IF EXISTS ".$tableTmp);
        $this->query("CREATE TABLE ".$tableTmp." SELECT * FROM ".$table." WHERE ".$key."=".$this->formatField($oldValue, $key_type));
        $this->query($strSQL);
    }

    public static function getFieldTypeDDL($type, $length, $precision)
    {
        if (in_array($type, array(self::SMALLINT, self::TINYINT, self::INTEGER, self::BIGINT, self::IDENTITY))) {
            $length = null;
            $precision = null;
        }
        $complement = '';
        if (in_array($type, self::$TEXT_TYPES) && !in_array($type, self::$DATE_TYPES)) {
            $complement = ' collate utf8_swedish_ci';
        }
        if ($type == self::VARCHAR && !$length) {
            $type = self::TINYTEXT;
        }
        $return = self::nativeType($type).($length ? '('.$length.($precision ? ','.$precision : '').')' : '').$complement;

        return $return;
    }

    public static function getFieldNullDDL($null)
    {
        $return = '';
        if (!$null) {
            $return = 'NOT NULL';
        }

        return $return;
    }

    public static function getFieldDefaultDDL($default, $type, $null)
    {
        $return = '';
        if (isset($default)) {
            // NULL non pris en compte pour les textes
            /*if ($default == 'NULL' && in_array($type,self::$TEXT_TYPES) && !in_array($type,self::$DATE_TYPES)) {
            $return = '';
            } else*/
            if ($default == 'NULL' && !$null) {
                $return = '';
            } else {
                if (in_array($type, self::$TEXT_TYPES) && $default != 'NULL') {
                    $default = str_replace("''", "", "'".$default."'");
                }
                $return = trim('default '.$default);
            }
        }
        if ($return == 'default') {
            $return = '';
        }

        return $return;
    }

    public static function getFieldIncrementDDL($increment, $type = "")
    {
        $return = '';
        if ($increment || $type == self::IDENTITY) {
            $return = 'AUTO_INCREMENT';
        }

        return $return;
    }

    public static function getPrimaryKeyDDL($table, $name, $aFields)
    {
        $return = "PRIMARY KEY (".implode(',', $aFields).")";

        return $return;
    }

    public static function getIncludedKeysDDL($table, $aFields)
    {
        $return = '';

        return $return;
    }

    public static function getIndexDDL($table, $name, $aFields, $unique = false)
    {
        $return = "CREATE ".($unique ? "UNIQUE " : "")."INDEX ".$name." ON ".$table." (".implode(',', $aFields).");";

        return $return;
    }

    public static function getReferencesDDL($table, $name, $childField, $source)
    {
        $return = '';

        return $return;
    }

    public static function getSequenceDDL($name, $start = "", $increment = "")
    {
        $return = '';

        return trim($return);
    }

    public static function getUniqueKeyDDL($table, $name, $aField)
    {
        $return = 'ALTER TABLE {'.$table.'} ADD UNIQUE KEY '.$name.' ('.implode(',', $aField).')';

        return $return;
    }

    public static function getEndDDL($type)
    {
        switch ($type) {
            case 'Table':
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
     * @static
     * @access public
     *
     * @param __TYPE__ $type __DESC__
     *
     * @return __TYPE__
     */
    public static function nativeType($type)
    {
        $return = self::$nativeTypes[strtoupper($type)];

        return $return;
    }
}
