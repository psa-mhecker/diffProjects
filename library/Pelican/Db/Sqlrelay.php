<?php
/** Couche d'abstraction SQLRELAY
 *
 * @package Pelican
 * @subpackage Db
 */

/**
 * cette classe permet d'avoir un accès tout type de base den utilisant SQL RELAY.
 *
 *  Elle offre un certain nombre de fonctionnalités
 *               comme la création d'une connexion, l'exécution d'une requête
 *               devant retourner un champ, une ligne ou un ensemble de ligne ou
 *               encore l'affichage du temps d'exécution d'un requête, la récupération
 *               du dernier enregistrement inséré au cours de la session...
 *
 * @package Pelican
 * @subpackage Db
 * @version 2.0
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 22/01/2007
 */

class Pelican_Db_Sqlrelay extends Pelican_Db
{

    public $_nativeTypes = array('tinyint' => self::TINYINT , 'smallint' => self::SMALLINT , 'mediumint' => self::SMALLINT , 'int' => self::INTEGER , 'integer' => self::INTEGER , 'bigint' => self::BIGINT , 'int24' => self::BIGINT , 'real' => self::REAL , 'float' => self::FLOAT , 'decimal' => self::DECIMAL , 'numeric' => self::NUMERIC , 'double' => self::DOUBLE , 'char' => self::CHAR , 'varchar' => self::VARCHAR , 'date' => self::DATE , 'time' => self::TIME , 'year' => self::INTEGER , 'datetime' => self::TIMESTAMP , 'timestamp' => self::TIMESTAMP , 'tinyblob' => self::BINARY , 'blob' => self::BLOB , 'mediumblob' => self::BLOB , 'longblob' => self::BLOB , 'longtext' => self::CLOB , 'tinytext' => self::VARCHAR , 'mediumtext' => self::LONGVARCHAR , 'text' => self::LONGVARCHAR , 'enum' => self::CHAR , 'set' => self::CHAR);

    /**
     * Nom de la base de données cible (pour les messages d'erreur)
     * @var string $databaseTitle
     */
    public $databaseTitle = "sqlrelay";

    /**
     * Base de données autorisant ou non l'utilisation de variables BIND
     * @var boolean $allowBind
     */
    public $allowBind = true;

    /**
     * Commiter automatiquement ou non les requêtes
     * @var boolean $autoCommit
     */
    public $autoCommit = false;

    /**
     * @return database
     * @param  string   $databaseName le nom de la base de données
     * @param  string   $username     le nom de l'utilisateur servant à la connexion
     * @param  string   $password     le mot de passe de connexion
     * @param  string   $host         l'adresse IP du serveur (optionnel) : 127.0.0.1 par défaut
     * @param  string   $port         le port de connexion (optionnel) : 3306 par défaut
     * @desc Constructeur. Permet de se connecter à la base de donnée
     */
    public function __construct($databaseName, $username, $password, $host = "", $port = "", $bExit = true, $persistency = false, $type = "sqlrelay")
    {

        parent::__construct($databaseName, $username, $password, $host, $port, $bExit);
        $this->persistency = $persistency;
        $this->autocommit = false;
        $this->type = $type;

        $retrytime = 0;
        $tries = 1;

        $this->id = sqlrcon_alloc($this->host, $this->port, $this->databaseName, $this->user, $this->passwd, $retrytime, $tries) or $this->error($this->message("Impossible de connecter au serveur " . $this->databaseTitle), "sqlrcon_alloc");

        $this->databaseTitle = "SQLRELAY : " . sqlrcon_identify($this->id);
        $this->info = $this->getInfo();
    }

    /**
     * Récupération des données issues d'une requête (lignes, champs, types de champs, lignes affefctées)
     * @return void
     * @param  string $query chaine SQL
     */
    public function queryInit($getData = true, $getInfos = true)
    {

        //nb de lignes affectées
        $this->affectedRows = sqlrcur_affectedRows($this->result);

        if ($getInfos) {
            $this->rows = sqlrcur_rowCount($this->result); //sqlrcur_totalRows(int sqlrcurref)


            // sauvegarde du nombre de champs renvoyés par la requète
            $this->fields = sqlrcur_colCount($this->result);

            for ($i = 1; $i <= $this->fields; $i ++) {
                $this->type[] = sqlrcur_getColumnType($this->result, $i);
                $this->name[] = sqlrcur_getColumnName($this->result, $i);
                $this->len[] = sqlrcur_getColumnLength($this->result, $i);
            }

            if ($getData) {
                for ($i = 0; $i < $this->rows; $i ++) {
                    for ($j = 0; $j < $this->fields; $j ++) {
                        $this->data[sqlrcur_getColumnName($this->result, $j)][$i] = sqlrcur_getField($this->result, $i, $j);

                    }
                }
            }
        } else {
            $this->rows = 0;
            $this->data = array();
        }
    }

    /**
     * Permet d'exécuter une requête.
     *
     * Aucun résultat n'est renvoyé par cette fonction. Elle doit être utilisé pour effectuer
     * des insertions, des updates... Elle est de même utilisée par les
     * autres fonction de la classe comme queryItem() et queryTab().
     * En revanche la propriété data est mise à jour dans le cas des SELECT : c'est un tableau à 2 niveaux (champs, lignes)
     * @return void
     * @param  string $query    chaine SQL
     * @param  mixed  $param    variables bind de type array(":bind"=>"value")
     * @param  mixed  $paramLob variables bind des champs CLOB
     */
    public function query ($query, $param = array(), $paramLob = array(), $getData = true)
    {

        /** bind, le bind doit respecter l'ordre dans la requête */
        $this->prepareBind($query, $param, $paramLob);

        // execution de la requête
        $this->data = array();
        $this->type = array();
        $this->name = array();
        $this->len = array();
        $this->query = $query;
        $this->param = $param;
        if ($this->debug) {
            $this->initTimeQuery();
        }
        if (! $param) {    //echo "BINDER : ".$query;
        }

        $this->result = sqlrcur_alloc($this->id) or $this->error($this->message("Impossible d'ex&eacute;cuter la requ&egrave;te :", "sqlrcur_alloc"));

        //sqlrcur_setResultSetBufferSize($cursor,100);


        sqlrcur_prepareQuery($this->result, $query);

        if ($this->result) {
            if ($param) {
                sqlrcur_clearBinds($this->result);
                foreach ($param as $key => $val) {
                    if ($key) {
                        if (in_array($key, $paramLob)) {
                            sqlrcur_inputBindClob($this->result, str_replace(":", "", trim($key)), $val, sizeof($val));
                        } else {
                            sqlrcur_inputBind($this->result, str_replace(":", "", trim($key)), $val);
                        }
                    }
                }
            }

            /** optimisation */
            $getInfos = true;
            if (! $this->isSelect($query)) {
                sqlrcur_dontGetColumnInfo($this->result);
                $getInfos = false;
            }

            //sqlrcon_debugOn($this->id);


            if (! sqlrcur_executeQuery($this->result)) {
                $this->error($this->message("Impossible d'ex&eacute;cuter la requ&egrave;te :", $query), "sqlrcur_executeQuery");
            } else {
                sqlrcon_endSession($this->id);
                if ($this->result) {
                    $this->queryInit($getData, $getInfos);
                }
            }
        }

        if ($this->debug) {
            $this->appendTimeQuery($query, $param, $paramLob);
        }
    }

    /**
     * permet d'exécuter une requête devant renvoyer une valeur
     *
     * @return mixed
     * @param  string $query    chaine SQL
     * @param  mixed  $param    variables bind de type array(":bind"=>"value")
     * @param  mixed  $paramLob variables bind des champs CLOB
     */
    public function queryItem ($query, $param = array(), $paramLob = array())
    {
        $this->query($query, $param, $paramLob, false);
        $dataval = sqlrcur_getField($this->result, 0, 0);

        return ($dataval);
    }

    /**
     *
     * Permet d'exécuter une requête devant renvoyer une seule ligne de résultat.
     *
     * le tableau de résultat est à 2 niveaux (lignes, champs)
     *
     * @return mixed
     * @param  string $query    chaine SQL
     * @param  mixed  $param    variables bind de type array(":bind"=>"value")
     * @param  mixed  $paramLob variables bind des champs CLOB
     */
    public function queryRow ($query, $param = array(), $paramLob = array(), $light = false)
    {
        $this->query($query, $param, $paramLob, false);
        $return = array();
        if ($light) {
            $return = sqlrcur_getRowAssoc($this->result, 0);
        } else {
            $array1 = sqlrcur_getRowAssoc($this->result, 0);
            $array2 = sqlrcur_getRow($this->result, 0);
            $return = @array_merge($array1, $array2);
        }

        return $return;
    }

    /**
     *
     * Permet d'exécuter une requête devant renvoyer plusieurs lignes de résultat.
     * le tableau de résultat est à 2 niveaux (lignes, champs)
     *
     * @return mixed
     * @param  string $query    chaine SQL
     * @param  mixed  $param    variables bind de type array(":bind"=>"value")
     * @param  mixed  $paramLob variables bind des champs CLOB
     */
    public function queryTab ($query, $param = array(), $paramLob = array(), $light = false)
    {
        $this->query($query, $param, $paramLob, false);
        $temp = array();
        for ($i = 0; $i < $this->rows; $i ++) {
            if ($light) {
                $temp[$i] = sqlrcur_getRowAssoc($this->result, $i);
            } else {
                $array1 = sqlrcur_getRowAssoc($this->result, $i);
                $array2 = sqlrcur_getRow($this->result, $i);
                $temp[$i] = @array_merge($array1, $array2);
            }
        }
        $this->data = $temp;

        return ($this->data);
    }

    /**
     * @return void
     * @desc permet de fermer la connexion avec la base
     */
    public function close()
    {
        $this->displayQueriesDebug();
        if (! connection_aborted()) {
            $this->commit();
        } else {
            $this->rollback();
        }

        if (is_resource($this->result)) {
            sqlrcur_free($this->result);
        }
        if (is_resource($this->result)) {
            sqlrcon_free($this->id);
        }

        return true;
    }

    /**
     * @return void
     * @param boolean activation du commit (true)
     * @desc Définition du commit automatique ou non
     */
    public function getCommitMode()
    {
        if ($this->autoCommit) {
            return OCI_COMMIT_ON_SUCCESS;
        } else {
            return OCI_DEFAULT;
        }
    }

    public function setAutoCommit($bCommit = true)
    {
        $this->autoCommit = $bCommit;
        if ($bCommit == true) {
            sqlrcon_autoCommitOn($this->id);
        } else {
            sqlrcon_autoCommitOff($this->id);
        }
    }

    /**
     * @return boolean
     * @desc Commitdes requêtes exécutées
     */
    public function commit()
    {
        if (is_resource($this->id)) {
            return sqlrcon_commit($this->id);
        } else {
            return true;
        }
    }

    /**
     * @return boolean
     * @desc Rollback des requêtes exécutées
     */
    public function rollback()
    {
        if ($this->id) {
            return sqlrcon_rollback($this->id);
        } else {
            return true;
        }
    }

    /**
     * @return void
     * @param  string $query la requête
     * @desc permet de recuperer l'id du dernier objet inséré dans la base, si la requete est de type INSERT
     */
    public function getLastOid($query = "")
    {
        return true;
    }

    /**
     * @return string
     * @param  string $table Nom de la table
     * @desc permet de recuperer l'id du de la prochaine séquence associée à la table
     */
    public function getNextId($table, $field = "")
    {

        $return = - 2;

        $seq = "SEQ_" . str_replace(strtoupper(Pelican::$config['FW_PREFIXE_TABLE']), "", strtoupper($table));
        $exists = $this->queryItem("select count(*) from user_sequences where SEQUENCE_NAME='" . $seq . "'");
        if ($exists) {
            $sql = "SELECT " . $seq . ".NEXTVAL FROM DUAL";
            $return = $this->queryItem($sql);
        }

        return $return;
    }

    /**
     * @return mixed
     * @desc Retourne un tableau avec N° et message dela dernière erreur
     */
    public function getError()
    {
        if ($this->id) {
            if ($this->result) {
                $return = array("message" => sqlrcur_errorMessage($this->result));

                return $return;
            }
        }
    }

    /**
     * @return mixed
     * @desc Retrouve les informations côté client et côté serveur de la base de données
     */
    public function getInfo()
    {
        $return["type"] = $this->databaseTitle;
        $return["host"] = $_SERVER["SERVER_NAME"];
        $return["instance"] = $this->databaseName;

        return $return;
    }

    /**
     * Retourne un tableau de description d'un objet de base de données
     *
     * table :"field","type","null","default","key","extra","increment","sequence"
     * @return mixed
     * @param  string $type  Type d'objet
     * @param  string $table Nom de l'objet
     */
    public function getDbInfo($type, $name = "", $id = "_ID")
    {
        // on récupère le schéma
        if ($name) {
            $aTable = explode(".", $name);
            // dans le cas où le user est explicitement déclaré
            if (count($aTable) == 2) {
                $view = "all";
                $filtre_owner = " and upper(owner) = upper('" . $aTable[0] . "')";
                $index_owner = " and all_cons_columns.owner=all_constraints.owner and upper(all_cons_columns.owner) = upper('" . $aTable[0] . "')";
                $sequence_owner = " and upper(sequence_owner) = upper('" . $aTable[0] . "')";
                $table = $aTable[1];
            } else {
                $view = "user";
                $filtre_owner = "";
                $index_owner = "";
                $sequence_owner = "";
                $table = $aTable[0];
            }
        }

        switch ($type) {
            case 'infos':
                {
                    $return["compatibility"] = $this->queryItem('select value from sys.database_compatible_level');
                    $return["description"] = sqlrcon_identify($this->id);
                    if (preg_match('/([0-9]+\.([0-9\.])+)/', $str, $arr)) {
                        $return["version"] = $arr[1];
                    }
                    $return["type"] = $this->databaseTitle;
                    $return["host"] = $_SERVER["SERVER_NAME"];
                    $return["instance"] = $this->databaseName;
                    break;
                }
            case 'tables':
                {
                    /** A COMPLETER */
                    $query = 'SELECT TABLE_NAME FROM user_tables';
                    $result = $this->queryTab($query);
                    foreach ($result as $ligne) {
                        $return[] = $ligne["TABLE_NAME"];
                    }
                    break;
                }
            case 'views':
                {
                    /** A COMPLETER */
                    $query = 'SELECT view_name FROM user_views';
                    $return = $this->queryTab($query);
                    break;
                }
            case 'fields':
                {
                    $query = "select /*+ RULE */
                        column_name \"field\",
                        lower(data_type) \"type\",
                        decode(nullable,'Y',1,null) \"null\",
                        null    \"key\",
                        data_default  \"default\",
                        ''  \"extra\",
                        null    \"sequence\",
                        null    \"increment\",
                        data_length    \"length\",
                        data_precision    \"precision\"
                        from " . $view . "_tab_columns
                        where upper(table_name)=upper('" . $table . "')" . $filtre_owner . " ORDER BY COLUMN_ID";
                    $result = $this->queryTab($query);

                    $Keys = $this->getDbInfo('keys', $name, $id);
                    if ($Keys) {
                        for ($i = 0; $i < count($result); $i ++) {
                            $result[$i]["key"] = false;
                            $result[$i]["null"] = $ligne["null"];
                            if (in_array($result[$i]["field"], $Keys)) {
                                $result[$i]["key"] = true;
                                if ($this->queryItem("select count(*) from " . $view . "_sequences where upper(sequence_name)='SEQ_" . strToUpper(str_replace($id, "", $result[$i]["field"])) . "'" . $sequence_owner)) {
                                    $result[$i]["sequence"] = true;
                                }
                            }
                        }
                    }
                    $return = $result;
                    break;
                }
            case 'keys':
                {
                    $query = "select /*+ RULE */ column_name from " . $view . "_cons_columns, " . $view . "_constraints where " . $view . "_cons_columns.constraint_name=" . $view . "_constraints.constraint_name and " . $view . "_cons_columns.table_name=" . $view . "_constraints.table_name and upper(" . $view . "_cons_columns.table_name)=upper('" . $table . "') and constraint_type='P'" . $index_owner . " order by position";
                    $result = $this->queryTab($query);
                    if ($result) {
                        foreach ($result as $column) {
                            $return[] = $column[0];
                        }
                    }
                    break;
                }
            case 'foreign_keys':
                {
                    $query = "SELECT
                        CONSTRAINT_NAME,
                        R_OWNER,
                        R_CONSTRAINT_NAME
                        FROM " . $view . "_constraints
                        WHERE CONSTRAINT_TYPE = 'R'
                        AND UPPER(TABLE_NAME) = UPPER('" . $table . "')
                        " . $index_owner;
                    $result = $this->queryTab($query);
                    $return = false;
                    if ($result) {
                        foreach ($result as $ligne) {
                            $cons = $ligne[0];
                            $rowner = $ligne[1];
                            $rcons = $ligne[2];
                            $cols = $this->queryTab("select column_name from " . $view . "_cons_columns where constraint_name='" . $cons . "'" . $index_owner . " order by position");
                            $tabcol = $this->queryTab("select table_name, column_name from " . $view . "_cons_columns where owner='" . $rowner . "' and constraint_name='" . $rcons . "' order by position");

                            if ($cols && $tabcol) {
                                for ($i = 0, $max = sizeof($cols); $i < $max; $i ++) {
                                    //$return[$tabcol[$i][0]] = $cols[$i][0].'='.$tabcol[$i][1];
                                    $return[$tabcol[$i][0]]["child_field"] = $tabcol[$i][1];
                                    $return[$tabcol[$i][0]]["parent_field"] = $cols[$i][0];
                                    $return[$tabcol[$i][0]]["parent_table"] = $tabcol[$i][0];
                                }
                            }
                        }
                    }
                    break;
                }
            case 'indexes':
                {
                    /** A COMPLETER */

                    return null;
                    break;
                }
            case 'public functions':
                {
                    /** A COMPLETER */
                    $query = "SELECT
                        PACKAGE_NAME,
                        OBJECT_NAME,
                        LOWER(ARGUMENT_NAME) PARAMETER,
                        POSITION,
                        LOWER(DATA_TYPE) TYPE,
                        LOWER(IN_OUT) IN_OUT,
                        DATA_LENGTH,
                        DATA_PRECISION,
                        LOWER(TYPE_NAME) PERSONALISED_TYPE
                        FROM ALL_ARGUMENTS
                        WHERE UPPER(OWNER)='" . strToUpper($this->user) . "'
                        AND (UPPER(OBJECT_NAME)='" . strToUpper($name) . "'
                        OR
                        UPPER(PACKAGE_NAME||'.'||OBJECT_NAME)='" . strToUpper($name) . "')
                        ORDER BY PACKAGE_NAME, OBJECT_NAME, POSITION";
                    $result = $this->queryTab($query);

                    if ($result) {
                        foreach ($result as $ligne) {
                            $return[$ligne["package_name"]][$ligne["object_name"]][$ligne["position"]]["parameter"] = $ligne["parameter"];
                            $return[$ligne["package_name"]][$ligne["object_name"]][$ligne["position"]]["type"] = $ligne["type"];
                            $return[$ligne["package_name"]][$ligne["object_name"]][$ligne["position"]]["in_out"] = $ligne["in_out"];
                            $return[$ligne["package_name"]][$ligne["object_name"]][$ligne["position"]]["length"] = $ligne["data_length"];
                            $return[$ligne["package_name"]][$ligne["object_name"]][$ligne["position"]]["precision"] = $ligne["data_precision"];
                            //   $return[$ligne["package_name"]][$ligne["object_name"]]["call"]="BEGIN\nDECLARE\n \nEND";
                        }
                    }
                    break;
                }
            default:
                {
                    return null;
                /** fin du case */
                }
        }

        return $return;
    }

    /**
     * @return string
     * @desc Retourne la fonction d'affichage de la date courante
     */
    public function getNow()
    {
        return "SYSDATE";
    }

    /**
     * @return string
     * @param  string $strChaine Date au format DD/MM/YYYY
     * @desc Formattage d'une date française au format de la base de donnée
     */
    public function dateStringToSql($strChaine, $hour = true)
    {
        if ($hour) {
            $complement = " HH24:MI:SS";
        }
        switch ($this->dateFormat) {
            case "MM/DD/YYYY":
                {
                    $temp_contenu_date = "TO_DATE('" . $strChaine . "','MM/DD/YYYY" . $complement . "')";
                    break;
                }
            default: //DD/MM/YYYY
                {
                    $temp_contenu_date = "TO_DATE('" . $strChaine . "','DD/MM/YYYY" . $complement . "')";
                    break;
                }
        }

        return ($temp_contenu_date);
    }

    /**
     * @return string
     * @param  string  $dateField Nom du champs date
     * @param  boolean $hour      Formttage de l'heure à inclure
     * @desc Formattage d'une date de la base de donnée au format français
     */
    public function dateSqlToString($dateField, $hour = false)
    {
        if ($hour) {
            $complement = " HH24:MI";
        }
        switch ($this->dateFormat) {
            case "MM/DD/YYYY":
                {
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
     * Retourne la valeur de l'année d'un champ date
     *
     * @param  string $dateField Champ date
     * @return string
     */
    public function dateToYear($dateField)
    {
        $temp_date = "TO_CHAR(" . $dateField . ",'YYYY')";

        return $temp_date;
    }

    /**
     * Retourne la valeur du mois d'un champ date
     *
     * @param  string $dateField Champ date
     * @return string
     */
    public function dateToMonth($dateField)
    {
        $temp_date = "TO_CHAR(" . $dateField . ",'MM')";

        return $temp_date;
    }

    /**
     * @return string
     * @param  string $value Chaîne de caractère
     * @desc Nettoyage des cotes pour les requêtes SQL
     */
    public function stringToSql($value)
    {
        return str_replace("\'", "''", $value);
    }

    /**
     * @return string
     * @param  string  $query  Chaîne SQL
     * @param  integer $min    Valeur Min
     * @param  integer $length Nombre de lignes
     * @param  bool    $bind   Bind Utilisation des variables Bind ou pas
     * @param  array   $aBind  tableau de variables bindées
     * @desc Transforme une requête pour qu'elle ne retourne que les lignes comprises entre la valeur $min et la valeur $max
     */
    public function getLimitedSQL($query, $min, $length, $bind = false, $aBind = "")
    {
        $bind = false;
        if ($bind) {
            $query = "SELECT sub_query.*, rownum AS num_ligne FROM ( $query ) sub_query where rownum <= :NOMBRE_LIGNES ";
            $return = "SELECT * FROM ( " . $query . " ) WHERE num_ligne >= :NUM_LIGNE ";
            $aBind[":NOMBRE_LIGNES"] = $length + $min - 1;
            $aBind[":NUM_LIGNE"] = $min;
        } else {
            $query = "SELECT sub_query.*, rownum AS num_ligne FROM ( $query ) sub_query where rownum <= " . ($length + $min - 1) . " ";
            $return = "SELECT * FROM ( " . $query . " ) WHERE num_ligne >= " . $min . " ";
        }

        return $return;
    }

    /**
     * @return string
     * @param  string $query       Chaine SQL originale
     * @param  string $countFields Liste des champs sur lesquels portent le comptage (clause group by)
     * @desc Transforme une requête pour qu'elle retourne le comptage des lignes.
     */
    public function getCountSQL($query, $countFields)
    {
        $deb = strpos(strToLower($query), "from");
        $fin = $this->arrayMin(array(strpos(strToLower($query), "order by") , strpos(strToLower($query), "group by") , strpos(strToLower($query), "having")));
        if (! $fin) {
            $fin = strlen($query);
        }
        $sql = "select count(" . ($countFields ? "distinct " . $countFields : "*") . ") " . substr($query, $deb, ($fin - $deb));

        return $sql;
    }

    /**
     * Formatage de l'expression de recherche
     * on utilise le CONTAINS d'intermedia sur le principe de '{$mot1}&{$mot2}&{$mot3}'
     *
     * $ étend l'analyse syntaxique de la recherche : "$guitars" va trouver "guitars" ou "guitar"
     * & est l'opérateur AND
     * {} permet l'utilisation de mots clés intermedia comme texte de recherche (exemple BTP, abbréviation de BATIMENT mais mot réservé Intermedia)
     * @return string
     * @param  string $field
     * @param  string $value
     */
    public function getSearchClause0($field, $value, $position = 0, $bindName = "", &$aBind, $join = "OR")
    {
        //   $request = "?{\$".str_replace("'", "''", str_replace(" ", "}".($join=="OR"?"|":"&")."?{\$", $value))."}";
        $request = "{\$" . str_replace("'", "''", str_replace(" ", "}" . ($join == "OR" ? "|" : "&") . "{\$", $value)) . "}";
        if ($bindName == "") {
            $return = " contains(" . $field . ", '" . $request . "'," . $position . ") > 0";
        } else {
            $return = " contains(" . $field . ", " . $bindName . "," . $position . ") > 0";
            $aBind[$bindName] = $request;
        }

        return $return;
    }

    public function getSearchClause($field, $value)
    {
        $return = " " . $field . " like  '%" . $value . "%' ";

        return $return;
    }

    /**
     * Formatage de l'expression de type NVL
     *
     * @return string
     * @param  string $clause
     * @param  string $value
     */
    public function getNVLClause($clause, $value)
    {
        /** Si la valeur est du texte et le champ un chiffre, ORACLE crée une erreur */
        if (! is_numeric($value)) {
            $clause = "TO_CHAR(" . $clause . ")";
        }
        $return = " NVL(" . $clause . "," . $value . ") ";

        return $return;
    }

    /**
     * Formatage de l'expression de type CONCAT
     *
     * @return string
     * @param  mixed  $aValue
     */
    public function getConcatClause($aValue)
    {
        $return = " " . implode("||", $aValue) . " ";

        return $return;
    }

    /**
     * Formatage de l'expression de type DECODE
     *
     * @return string
     * @param  string $clause
     * @param  string $value
     */
    public function getCaseClause($field, $aClause, $defaultValue)
    {
        IF ($field && $aClause) {
            $return = "DECODE(" . $field . ",";
            foreach ($aClause as $key => $value) {
                $temp[] = $key . "," . $value;
            }
            $return .= implode(",", $temp);
            $return .= "," . $defaultValue . ")";
        }

        return $return;
    }

    /**
     * @return void
     * @param  string $table    Nom de la table impactée
     * @param  string $key      Champ identifiant
     * @param  string $oldValue Valeur du champ identifiant de l'enregistrement à dupliquer
     * @param  string $newValue Valeur de remplacement pour le champ identifiant
     * @desc Duplique un enregistrement d'une table sur une clé.
     */
    public function duplicateRecord($table, $key, $oldValue, $newValue)
    {
        $fieldSet = $this->describeTable($table);
        $strSQL = "INSERT INTO " . $table . " SELECT ";
        $j = - 1;
        foreach ($fieldSet as $field) {
            $j ++;
            if ($field["increment"]) {
                $autoIncrement = $field["field"];
            }
            if ($field["field"] != $key) {
                $strSQL .= $field["field"] . ", ";
            } else {
                $key_type = $field["type"];
                $strSQL .= $this->formatField($newValue, $field["type"]) . ", ";
            }
        }
        $strSQL .= "FROM " . $table . " WHERE " . $key . "=" . $this->formatField($oldValue, $key_type);
        $strSQL = str_replace(", FROM", " FROM", $strSQL);
        $this->query($strSQL, "", "", false);
    }

    public function procQueryParam($procname, $aValues = "")
    {
        // Ajout PLa 20061130 - a revoir sans doute
        if ($aValues && ! is_array($aValues)) {
            $aValues = array(0 => $aValues);
            $bResetParam = true;
        }

        $query = $procname . "(";
        if ($aValues) {
            for ($i = 0; $i < count($aValues); $i ++) {
                if ($i) {
                    $query .= ",";
                }
                $query .= " :SR" . $i . " ";
                $param[":SR" . $i] = $aValues[$i];
            }
            $query .= ", :ErrMsg); end;";
        } else {
            $query .= ":ErrMsg); end;";
        }
        $query = "declare vResult varchar2(255); BEGIN vResult := " . $query;

        $this->result = sqlrcur_alloc($this->id) or $this->error($this->message("Impossible d'ex&eacute;cuter la requ&egrave;te :", "sqlrcur_alloc"));
        sqlrcur_prepareQuery($this->result, $query);
        foreach ($param as $key => $val) {
            if ($key) {
                sqlrcur_inputBind($this->result, str_replace(":", "", trim($key)), $val);
            }
        }

        sqlrcur_defineOutputBindString($this->result, "ErrMsg", 255);
        sqlrcur_executeQuery($this->result) or $this->error($this->message("Impossible d'ex&eacute;cuter la procédure stockée :", $query), "sqlrcur_executeQuery");

        $errorcode = sqlrcur_getOutputBindString($this->result, "ErrMsg");

        if ($bResetParam) {
            $aValues = $aValues[0];
        }

        return $errorcode;
    }

}
