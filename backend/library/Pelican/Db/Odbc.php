<?php
/** Couche d'abstraction ODBC
 *
 */

/**
 * cette classe permet d'avoir un accès facilité à une base de
 *               données via ODBC (MS Access, SQL Server, DB2 etc.). Elle offre un certain nombre de fonctionnalités
 *               comme la création d'une connexion, l'exécution d'une requète
 *               devant retourner un champ, une ligne ou un ensemble de ligne ou
 *               encore l'affichage du temps d'exécution d'un requète, la récupération
 *               du dernier enregistrement inséré au cours de la session...
 *
 * @version 1.0
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 17/05/2005
 */
class Pelican_Db_Odbc extends Pelican_Db
{
    public $_nativeTypes = array(self::BIGINT => 'BIGINT' , self::BINARY => 'TINYBLOB' , self::BLOB => 'BLOB' , self::CHAR => 'CHAR' , self::CLOB => 'LONGTEXT' , self::DATE => 'DATE' , self::DECIMAL => 'DECIMAL' , self::DOUBLE => 'DOUBLE' , self::FLOAT => 'FLOAT' , self::INTEGER => 'INTEGER' , self::LONGVARCHAR => 'TEXT' , self::NUMERIC => 'NUMERIC' , self::REAL => 'REAL' , self::SMALLINT => 'SMALLINT' , self::TIME => 'TIME' , self::TIMESTAMP => 'TIMESTAMP' , self::TINYINT => 'TINYINT' , self::VARCHAR => 'VARCHAR');

    /**
     * Nom de la base de données cible (pour les messages d'erreur).
     *
     * @var string
     */
    public $databaseTitle = "ODBC";

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
     * @return Pelican_Db
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
        $this->host = $host;
        $this->port = $port;
        $this->persistency = $persistency;
        $this->info = $this->getInfo();

        // connexion à la base de données
        if ($this->persistency) {
            $this->id = odbc_pconnect($this->host, $this->user, $this->passwd) or $this->error($this->message("Impossible de connecter au serveur ".$this->databaseTitle));
        } else {
            $this->id = odbc_connect($this->host, $this->user, $this->passwd) or $this->error($this->message("Impossible de connecter au serveur ".$this->databaseTitle));
        }
        //  $this->odbc_type=$this->getOdbcType();
    }

    public static function is_available()
    {
        return function_exists('odbc_connect');
    }

    /**
     * @see Pelican_Db::beginTrans()
     */
    public function beginTrans()
    {
        if (!$this->autoCommit) {
            odbc_autocommit($this->id, false);
        } else {
            odbc_autocommit($this->id, true);
        }
    }

    public function odbc_record_count($result, $query)
    {
        $numRecords = odbc_num_rows($result);
        if ($numRecords < 0) {
            $countQueryString = $this->getCountSQL($query);
            $temp = odbc_connect($this->host, $this->user, $this->passwd);
            $count = odbc_exec($temp, $countQueryString);
            $numRecords = odbc_result($count, "compte");
            odbc_close($temp);
        }

        return $numRecords;
    }

    /**
     * @see Pelican_Db::close()
     */
    public function close()
    {
        odbc_close($this->id);
    }

    /**
     * @see Pelican_Db::queryInit()
     */
    public function queryInit($query, $param = array(), $paramLob = array(), $debug = false)
    {
        $this->data = array();
        $this->type = array();
        $this->name = array();
        $this->len = array();
        $this->query = $query;

        if ($this->result = odbc_exec($this->id, $query)) {
            //nb de lignes affectées
            $this->affectedRows = $this->odbc_record_count($this->result, $query);

            // sauvegarde du nombre de champs renvoyés par la requète
            $this->fields = odbc_num_fields($this->result);

            // sauvegarde du nombre de lignes renvoyés par la requète
            $this->rows = $this->odbc_record_count($this->result, $query);

            for ($i = 1; $i <= $this->fields; $i++) {
                $this->type[] = strToLower(odbc_field_type($this->result, $i));
                $this->name[] = odbc_field_name($this->result, $i);
                $this->len[] = odbc_field_len($this->result, $i);
            }

            return true;
        } else {
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
            // execution de la requète
        $temp = array();
        if ($this->queryInit($query)) {
            if (strpos(" ".strToUpper($query), "SELECT") > 0) {
                // cas d'une requête select
                for ($num_ligne = 0; $num_ligne < $this->rows; $num_ligne++) {
                    $temp = $this->odbc_fetch_array($this->result) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
                    while (list($key, $val) = each($temp)) {
                        if (!is_numeric($key)) {
                            $this->data[$key][$num_ligne] = $val;
                        }
                    }
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
        if ($this->queryInit($query) and $this->rows > 0) {
            // recuperation du résultat
            $tab_result = $this->odbc_fetch_array($this->result) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
            $keys = array_keys($tab_result);
            $this->data = $tab_result[$keys[0]];
            // liberation de la memoire
            odbc_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
        }

        // renvoie du résultat
        return ($this->data);
    }

    /**
     * @see Pelican_Db::queryRow()
     */
    public function queryRow($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
    {
        // suppression de l'ancien résultat
        unset($this->data);

        // execution de la requete
        if ($this->queryInit($query) and $this->rows > 0) {
            // recuperation du résultat
            $this->data = $this->odbc_fetch_array($this->result) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));

            // liberation de la memoire
            odbc_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
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

        // si une limitation a été définie (getLimitedSql), on défini la ligne à partir de laquelle se fera l'affichage
        if ($this->min) {
            $begin = $this->min - 1;
            unset($this->min);
        } else {
            $begin = 0;
        }
        //initialisation obligatoire pour $this->limit
        if (!$this->limit) {
            $this->limit = 0;
        }
            // execution de la requete
        if ($this->queryInit($query) and $this->rows > 0) {
            if ($this->rows > ($this->limit + $begin) && ($this->limit + $begin)) {
                $this->rows = $this->limit + $begin;
            }
            $this->limit = 0;

            // recuperation du résultat
            for ($num_ligne = 0; $num_ligne < $this->rows; $num_ligne++) {
                $temp = $this->odbc_fetch_array($this->result) or $this->error($this->message("Erreur lors de la r&eacute;cup&eacute;ration des informations de la requ&egrave;te :", $query));
                if ($num_ligne >= $begin) {
                    $this->data[$num_ligne - $begin] = $temp;
                }
            }
            // liberation de la memoire
            odbc_free_result($this->result) or $this->error($this->message("Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query));
        }

        // renvoie du résultat
        return ($this->data);
    }

    /**
     * @see Pelican_Db::queryObj()
     */
    public function queryObj($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
    {
    }

    /**
     * @see Pelican_Db::commit()
     */
    public function commit()
    {
        return odbc_commit($this->id);
    }

    /**
     * @see Pelican_Db::rollback()
     */
    public function rollback()
    {
        return odbc_rollback($this->id);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $state
     */
    public function setAutoCommit($state)
    {
        $this->autoCommit = state;
        odbc_autocommit($this->id, $state);
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
            // on recupere le dernier enregistrement
            $this->lastInsertedId = $this->queryItem("SELECT @@IDENTITY");
            debug($this->lastInsertedId);
        }
    }

    /**
     * @see Pelican_Db::getNextId()
     */
    public function getNextId($table)
    {
    }

    /**
     * @see Pelican_Db::getError()
     */
    public function getError()
    {
        return array("code" => odbc_error() , "message" => odbc_errormsg());
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
        switch ($type) {
            case 'infos':
                {
                    /* A COMPLETER */
                    return;
                    break;
                }
            case 'tables':
                {
                    $result = odbc_tables($this->id);

                    if ($result) {
                        while (odbc_fetch_row($result)) {
                            $tablename = strtoupper(odbc_result($result, 'TABLE_NAME'));
                            $return[] = $tablename;
                        }
                        odbc_free_result($result);
                    }
                    break;
                }
            case 'views':
                {
                    /* A COMPLETER */
                    $query = "select name from sysobjects where type = 'V'";
                    $result = $this->queryTab($query);
                    if ($result) {
                        foreach ($result as $val) {
                            $return[] = $val['view_name'];
                        }
                    }

                    break;
                }
            case 'fields':
                {
                    if (odbc_columns($this->id, $this->databaseName, "%", $table)) {
                        // pour Adabas D, IBM DB2, iODBC, Solid, and Sybase SQL Anywhere.
                        $describe_result = odbc_columns($this->id, $this->databaseName, "%", $table);
                        $this->odbc_type = "unified_odbc";
                    } else {
                        // Access
                        $describe_result = odbc_columns($this->id);
                        $this->odbc_type = "msaccess";
                    }
                    //$a=$describe_result;
                    //odbc_result_all($a);
                    while (odbc_fetch_row($describe_result)) {
                        if (odbc_result($describe_result, "TABLE_NAME") == $table) {
                            $rs = array();
                            $this->base = odbc_result($describe_result, "TABLE_CAT");
                            $this->owner = odbc_result($describe_result, "TABLE_SCHEM");
                            $rs["field"] = odbc_result($describe_result, "COLUMN_NAME");
                            $rs["type"] = strToLower(odbc_result($describe_result, "TYPE_NAME"));
                            $rs["null"] = (odbc_result($describe_result, "IS_NULLABLE") == "YES");
                            $rs["default"] = (odbc_result($describe_result, "COLUMN_DEF"));
                            $rs["extra"] = "";
                            $rs["sequence"] = false;
                            $rs["increment"] = ($this->isIdentity($rs["type"]));
                            $rs["key"] = (!$rs["null"] && $rs["increment"]);
                            $describe[] = $rs;
                        }
                    }
                    $result = $describe;

                    if ($this->odbc_type = "msaccess") {    // faire une fonction ADO !!!!!!!
                    } else {
                        if ($this->odbc_type == "unified_odbc") {
                            $key_result = odbc_primarykeys($this->id, $this->base, $this->owner, $table);
                            while (odbc_fetch_row($key_result)) {
                                if (odbc_result($key_result, "TABLE_NAME") == $table) {
                                    $Keys[] = odbc_result($key_result, "COLUMN_NAME");
                                    //     $rs["key"]=strToLower(odbc_result($key_result, "KEY_SEQ"));
                                //     $rs["name"]=strToLower(odbc_result($key_result, "PK_NAME"));
                                }
                            }
                        }
                    }
                    if ($Keys) {
                        for ($i = 0; $i < count($result); $i++) {
                            if (in_array($result[$i]["field"], $Keys)) {
                                $result[$i]["key"] = true;
                            }
                        }
                    }

                    /*
                    $a = odbc_specialcolumns($this->id,1,$this->databaseName,$this->owner,$table,0,0);
                    odbc_result_all($a);
                    $a = odbc_statistics($this->id,$this->databaseName,$this->owner,$table,0,0);
                    odbc_result_all($a);
                    */
                    $return = $result;
                    break;
                }
            case 'indexes':
                {
                    /* A COMPLETER */
                    return;
                    break;
                }
            case 'functions':
                {
                    /* A COMPLETER */
                    return;
                    //odbc_procedurecolumns()
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
    }

    /**
     * @see Pelican_Db::getDateAddClause()
     */
    public function getDateAddClause($date, $interval)
    {
    }

    /**
     * @see Pelican_Db::dateStringToSql()
     */
    public function dateStringToSql($strChaine, $hour = true)
    {
        $temp_contenu_date = "#".$strChaine."#";

        return ($temp_contenu_date);
    }

    /**
     * @see Pelican_Db::dateSqlToString()
     */
    public function dateSqlToString($dateField, $hour = false, $complement = "")
    {
        switch ($this->date_format) {
            case "MM/DD/YYYY":
                {
                    $temp_date = "format(".$dateField.",'mm/dd/yyyy')";
                    break;
                }
            default: //DD/MM/YYYY
                {
                    $temp_date = "format(".$dateField.",'dd/mm/yyyy')";
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
    }

    /**
     * @see Pelican_Db::stringToSql()
     */
    public function stringToSql($value)
    {
        return str_replace("\'", "''", $value);
    }

    /**
     * @see Pelican_Db::getLimitedSQL()
     */
    public function getLimitedSQL($query, $min, $length)
    {
        $this->min = $min;
        $this->limit = $length;
        $return = str_replace("SELECT", "SELECT TOP ".($min + $length - 1), str_replace("select", "SELECT", $query));

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
        $sql = "select count(".($countFields ? $countFields : "*").") as compte ".substr($query, $deb, ($fin - $deb));

        return $sql;
    }

    /**
     * @see Pelican_Db::getSearchClause()
     */
    public function getSearchClause($field, $value, $position = 0, $bindName = "", &$aBind, $join = "OR")
    {
    }

    /**
     * @see Pelican_Db::getSearchClauseLike()
     */
    public function getSearchClauseLike($field, $value, $position = 0, $bindName = "", &$aBind, $join = "OR")
    {
    }

    /**
     * @see Pelican_Db::getNVLClause()
     */
    public function getNVLClause($clause, $value)
    {
    }

    /**
     * @see Pelican_Db::getConcatClause()
     */
    public function getConcatClause($aValue)
    {
    }

    /**
     * @see Pelican_Db::getCaseClause()
     */
    public function getCaseClause($field, $aClause, $defaultValue)
    {
    }

    /**
     * @see Pelican_Db::duplicateRecord()
     */
    public function duplicateRecord($table, $key, $oldValue, $newValue)
    {
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
        $strSQL .= "FROM ".$table." WHERE ".$key."=".$this->formatField($oldValue, $key_type);
        $strSQL = str_replace(", FROM", " FROM", $strSQL);
        $this->query($strSQL);
    }

    //spécialement pour ODBC
    public function odbc_fetch_array($result, $rownumber = -1)
    {
        if (PHP_VERSION > "4.1") {
            if ($rownumber < 0) {
                odbc_fetch_into($result, $rs);
            } else {
                odbc_fetch_into($result, $rs, $rownumber);
            }
        } else {
            odbc_fetch_into($result, $rownumber, $rs);
        }

        $rs_assoc = array();

        $i = 0;
        foreach ($rs as $key => $value) {
            $rs_assoc[$i] = $value;
            $rs_assoc[odbc_field_name($result, $key + 1)] = $value;
            $i++;
        }

        return $rs_assoc;
    }
} // fin de la declaration de la classe
