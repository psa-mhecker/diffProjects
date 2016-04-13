<?php

/**
 * Couche d'abstraction ORACLE
 *
 * @package Pelican
 * @subpackage Db
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * Cette classe permet d'avoir un accès facilité à une base de
 * données ORACLE. Elle offre un certain nombre de fonctionnalités
 *
 * Comme la création d'une connexion, l'exécution d'une requête
 * devant retourner un champ, une ligne ou un ensemble de ligne ou
 * encore l'affichage du temps d'exécution d'un requête, la
 * récupération du dernier enregistrement inséré au cours de la session...
 *
 * @package Pelican
 * @subpackage Db
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @author Jean-Baptiste Ruscassié <jbruscassie@businessdecision.com>
 * @since 22/01/2001
 * @version 2.0
 */
class Pelican_Db_Oracle extends Pelican_Db
{
    const ORACLE_INTERMEDIA = false;

    /**
     * @static
     * @access public
     * @var __TYPE__ __DESC__
     */
    public static $nativeTypes = array (
            self::BIGINT => 'INTEGER',
            self::BLOB => 'BLOB',
            self::CHAR => 'CHAR',
            self::CLOB => 'CLOB',
            self::DATE => 'DATE',
            self::DECIMAL => 'DECIMAL',
            self::DOUBLE => 'DOUBLE',
            self::FLOAT => 'FLOAT',
            self::IDENTITY => 'INTEGER',
            self::INTEGER => 'INTEGER',
            self::LONGVARCHAR => 'CLOB',
            self::TIMESTAMP => 'TIMESTAMP',
            self::VARCHAR => 'VARCHAR2' );

    /**
     * Nom de la base de données cible (pour les messages d'erreur)
     *
     * @access public
     * @var string
     */
    public $databaseTitle = "Oracle";

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
    public $autoCommit = false;

    /**
     * Constructeur. Permet de se connecter à la base de donnée
     *
     * @access public
     * @param  string     $databaseName Le nom de la base de données
     * @param  string     $username     Le nom de l'utilisateur servant à la connexion
     * @param  string     $password     Le mot de passe de connexion
     * @param  string     $host         (option) L'adresse IP du serveur (optionnel) : 127.0.0.1
     *                                  par défaut
     * @param  string     $port         (option) Le port de connexion (optionnel) : 3306 par
     *                                  défaut
     * @param  bool       $bExit        (option) __DESC__
     * @param  bool       $persistency  (option) __DESC__
     * @return Pelican_Db
     */
    public function __construct($databaseName, $username, $password, $host = "", $port = "", $bExit = true, $persistency = true)
    {
        parent::__construct ( $databaseName, $username, $password, $host, $port, $bExit );
        if (!empty(Pelican::$config["persistency"])) {
            $persistency = true;
        }
        $this->databaseName = $databaseName;
        $this->user = $username;
        $this->passwd = $password;
        $this->host = ($host ? $host : "127.0.0.1");
        $this->port = ($port ? $port : "1521");
        $this->persistency = $persistency;
        $this->info = $this->getInfo ();
        $this->autocommit = false;

        /** connection à la base soit par tnsnames.ora soit par description directe (cas où $databaseName est un tableau avec IP, MODE, SERVICE_NAME, SID) */
        if (is_array ( $databaseName )) {
            if (! $databaseName ["SERVER"]) {
                $databaseName ["SERVER"] = "DEDICATED";
            }
            $tmp = "(DESCRIPTION =
                    (ADDRESS_LIST =
                    (ADDRESS = (PROTOCOL = TCP)(HOST = " . $databaseName ["IP"] . ")(PORT = " . $this->port . "))
                    )";
            if ($databaseName ["SERVICE_NAME"]) {
                $tmp .= "(CONNECT_DATA =
                        (SERVER = " . $databaseName ["SERVER"] . ")
                        (SERVICE_NAME = " . $databaseName ["SERVICE_NAME"] . ")
                        )";
            }
            if ($databaseName ["SID"]) {
                $tmp .= "(CONNECT_DATA =
                        (SERVER = " . $databaseName ["SERVER"] . ")
                        (SID = " . $databaseName ["SID"] . ")
                        )";
            }
            $tmp .= "
                    )";
            $databaseName = $tmp;
        }
        //debug($databaseName);
        $this->databaseName = $databaseName;
        if (isset ( Pelican::$config ['DATABASE_CHARSET'] )) {
            if (Pelican::$config ['DATABASE_CHARSET']) {
                $this->charset = Pelican::$config ['DATABASE_CHARSET'];
            }
        } elseif ($_ENV ['NLS_LANG']) {
            $this->charset = $_ENV ['NLS_LANG'];
        }
        // connexion à la base de données
        if ($this->persistency) {
            $this->id = oci_pconnect ( $this->user, $this->passwd, $this->databaseName, $this->charset ) or $this->error ( $this->message ( "Impossible de connecter au serveur " . $this->databaseTitle ), "oci_pconnect" );
        } else {
            $this->id = oci_connect ( $this->user, $this->passwd, $this->databaseName, $this->charset ) or $this->error ( $this->message ( "Impossible de connecter au serveur " . $this->databaseTitle ), "oci_connect" );
        }
    }

    /**
     * __DESC__
     *
     * @static
     * @access public
     * @return __TYPE__
     */
    public static function is_available()
    {
        return function_exists ( 'oci_connect' );
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
        if ($this->persistency) {
            if (! connection_aborted ()) {
                return $this->commit ();
            } else {
                //     $this->error($this->message("Connection interrompue"));
                return $this->rollback ();
            }
        } else {
            if (! connection_aborted ()) {
                $this->commit ();
            } else {
                //     $this->error($this->message("Connection interrompue"));
                $this->rollback ();
            }
        }

        return oci_close ( $this->id );
    }

    /**
     * ATTENTION : les autres paramètres ne sont pas utilisés ici
     *
     * @access public
     * @see Pelican_Db::queryInit()
     * @param  __TYPE__ $query    __DESC__
     * @param  __TYPE__ $param    (option) __DESC__
     * @param  __TYPE__ $paramLob (option) __DESC__
     * @param  bool     $debug    (option) __DESC__
     * @return __TYPE__
     */
    public function queryInit($query, $param = array(), $paramLob = array(), $debug = false)
    {
        //nb de lignes affectées
        $this->affectedRows = oci_num_rows ( $this->result );
        // sauvegarde du nombre de champs renvoyés par la requète
        $this->fields = oci_num_fields ( $this->result );
        for ($i = 1; $i <= $this->fields; $i ++) {
            $this->type [] = oci_field_type ( $this->result, $i );
            $this->name [] = oci_field_name ( $this->result, $i );
            $this->len [] = oci_field_size ( $this->result, $i );
        }
        // sauvegarde du nombre de lignes renvoyés par la requète
        if (oci_statement_type ( $this->result ) == "SELECT") {
            $this->rows = oci_fetch_all ( $this->result, $this->data );
        }
        $data_temp = array ();
        while ( list ( $key, $val ) = each ( $this->data ) ) {
            $data_temp [$key] = $val;
        }
        $this->data = $data_temp;
        if ($this->result != false) {
            oci_free_statement ( $this->result ) or $this->error ( $this->message ( "Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query ), "oci_free_statement" );
            if ($this->autocommit)
                $this->commit ();
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::query()
     * @param  __TYPE__ $query    __DESC__
     * @param  __TYPE__ $param    (option) __DESC__
     * @param  __TYPE__ $paramLob (option) __DESC__
     * @param  bool     $debug    (option) __DESC__
     * @return __TYPE__
     */
    public function query($query, $param = array(), $paramLob = array(), $debug = false)
    {
        // execution de la requête
        $this->data = array ();
        $this->type = array ();
        $this->name = array ();
        $this->len = array ();
        $this->query = self::replacePrefix ( $query );
        $this->param = $param;
        $this->debug = $debug;
        if ($this->debug) {
            $this->initTimeQuery ();
        }
        if (! $param) { //echo "BINDER : ".$this->query;

        }
        $this->result = oci_parse ( $this->id, $this->query ) or $this->error ( $this->message ( "Impossible d'ex&eacute;cuter la requ&egrave;te :", $this->query ) );
        if (isset ( $_GET ['show'] ) && preg_match ( '#sql#i', $_GET ['show'] )) {
            echo '<font color="orange">' . $this->databaseName . '</font><xmp>';
            print_r ( $this->query );
            echo '</xmp>Bind: ';
            print_r ( $param );
            echo '<br><br>';
        }

        /** echappement des caractères spéciaux de word */
        if ($paramLob) {
            foreach ($paramLob as $key => $null) {
                if ($param [$key]) {
                    $param [$key] = Pelican_Text::htmlencode ( $param [$key] );
                }
            }
        }
        if ($this->result) {
            $statement = oci_statement_type ( $this->result );
            // Bind
            if ($paramLob && $statement != "SELECT") {
                for ($i = 0; $i < sizeOf ( $paramLob ); $i ++) {
                    $nom_CLOB = "clob_" . $i;
                    $$nom_CLOB = oci_new_descriptor ( $this->id, OCI_D_LOB );
                }
            }
            if ($param) {
                foreach ($param as $key => $val) {
                    if (strpos ( $this->query, $key ) !== false) {
                        oci_bind_by_name ( $this->result, trim ( $key ), $param [$key], - 1 );
                    }
                }
            }
            //@OCISetPrefetch($this->result, $this->_nbPrefetchRows);
            oci_execute ( $this->result, $this->getCommitMode () ) or $this->error ( $this->message ( "Impossible d'ex&eacute;cuter la requ&egrave;te :", $this->query ), "oci_execute" );

            /** si tout s'est bien passé */
            $this->queryInit ( $this->query );
            // Si il y des LOBs et que la requète n'est pas un select, alors libération des descripteurs de LOB
            if ($paramLob && $statement != "SELECT") {
                for ($i = 0; $i < sizeOf ( $paramLob ); $i ++) {
                    $nomCLOB = "clob_" . $i;
                    @OCIFreeDesc ( $$nomCLOB );
                }
            }
        }
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::queryItem()
     * @param  __TYPE__ $query    __DESC__
     * @param  __TYPE__ $param    (option) __DESC__
     * @param  __TYPE__ $paramLob (option) __DESC__
     * @param  bool     $light    (option) __DESC__
     * @param  bool     $debug    (option) __DESC__
     * @return __TYPE__
     */
    public function queryItem($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
    {
        $this->query ( $query, $param, $paramLob );
        $keys = array_keys ( $this->data );
        //$dataval = $this->fetch(0, $light);
        $dataval = $this->data [$keys [0]];

        return ($dataval [0]);
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::queryRow()
     * @param  __TYPE__ $query    __DESC__
     * @param  __TYPE__ $param    (option) __DESC__
     * @param  __TYPE__ $paramLob (option) __DESC__
     * @param  bool     $light    (option) __DESC__
     * @param  bool     $debug    (option) __DESC__
     * @return __TYPE__
     */
    public function queryRow($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
    {
        $this->queryTab ( $query, $param, $paramLob, $light );
        $return = array ();
        if (isset ( $this->data [0] )) {
            $return = $this->data [0];
        }

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::queryTab()
     * @param  __TYPE__ $query    __DESC__
     * @param  __TYPE__ $param    (option) __DESC__
     * @param  __TYPE__ $paramLob (option) __DESC__
     * @param  bool     $light    (option) __DESC__
     * @param  bool     $debug    (option) __DESC__
     * @return __TYPE__
     */
    public function queryTab($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
    {
        $this->query ( $query, $param, $paramLob );
        $temp = array ();
        for ($i = 0; $i < $this->rows; $i ++) {
            $temp [$i] = $this->fetch ( $i, $light );
        }
        $this->data = $temp;

        return ($this->data);
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::queryObj()
     * @param  __TYPE__ $query    __DESC__
     * @param  __TYPE__ $param    (option) __DESC__
     * @param  __TYPE__ $paramLob (option) __DESC__
     * @param  bool     $light    (option) __DESC__
     * @param  bool     $debug    (option) __DESC__
     * @return __TYPE__
     */
    public function queryObj($query, $param = array(), $paramLob = array(), $light = true, $debug = false)
    {
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $procname __DESC__
     * @param  string   $aValues  (option) __DESC__
     * @return __TYPE__
     */
    public function procQueryParam($procname, $aValues = "")
    {
        if ($aValues && ! is_array ( $aValues )) {
            $aValues = array (
                    0 => $aValues );
            $bResetParam = true;
        }
        $query = "declare vReturn varchar2(255); BEGIN vReturn := " . $procname . "(";
        if ($aValues) {
            for ($i = 0; $i < count ( $aValues ); $i ++) {
                if ($i) {
                    $query .= ",";
                }
                $query .= ":" . $i;
            }
            $query .= "); end;";
        } else {
            $query .= "); end;";
        }
        $this->result = OCIParse ( $this->id, $query ) or $this->error ( $this->message ( "Impossible d'ex&eacute;cuter la procédure stockée :", $query ) );
        for ($i = 0; $i < count ( $aValues ); $i ++) {
            if (! (strpos ( $query, ":" . $i ) === FALSE)) {
                OCIBindByName ( $this->result, ":" . $i, $aValues [$i], - 1 );
            }
        }
        OCIBindByName ( $this->result, ":ErrMsg", $errorcode, 255 );
        OCIExecute ( $this->result ) or $this->error ( $this->message ( "Impossible d'ex&eacute;cuter la procédure stockée :", $query ), "OCIExecute" );
        if ($this->result != false) {
            OCIFreeStatement ( $this->result ) or $this->error ( $this->message ( "Erreur lors de la lib&eacute;ration de la m&eacute;moire occup&eacute;e par le r&eacute;sultat de la requ&egrave;te :", $query ), "OCIFreeStatement" );
        }
        if ($bResetParam) {
            $aValues = $aValues [0];
        }

        return $errorcode;
    }

    /**
     * Retourne la constante d'exécution d'une requête : commit immédiat ou non
     *
     * @access public
     * @return int
     */
    public function getCommitMode()
    {
        if ($this->autoCommit) {
            return OCI_COMMIT_ON_SUCCESS;
        } else {
            return OCI_DEFAULT;
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
        if ($this->id) {
            return oci_commit ( $this->id );
        } else {
            return true;
        }
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
        if ($this->id) {
            return oci_rollback ( $this->id );
        } else {
            return true;
        }
    }

    /**
     * INUTILE POUR ORACLE
     *
     * @access public
     * @see Pelican_Db::getLastOid()
     * @param  string   $query (option) __DESC__
     * @return __TYPE__
     */
    public function getLastOid($query = "")
    {
        return true;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::getNextId()
     * @param  __TYPE__ $table __DESC__
     * @return __TYPE__
     */
    public function getNextId($table)
    {
        $sql = "SELECT SEQ_" . str_replace ( strtoupper ( Pelican::$config ['FW_PREFIXE_TABLE'] ), "", strtoupper ( self::replacePrefix ( $table ) ) ) . ".NEXTVAL FROM DUAL";

        return $this->queryItem ( $sql );
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
        return $this->id ? oci_error ( $this->result ) : oci_error ();
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
        $return ["type"] = $this->databaseTitle;
        $return ["host"] = $_SERVER ["SERVER_NAME"];
        $return ["instance"] = $this->databaseName;

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::getDbInfo()
     * @param  __TYPE__ $type __DESC__
     * @param  string   $name (option) __DESC__
     * @return __TYPE__
     */
    public function getDbInfo($type, $name = "")
    {
        // on récupère le schéma
        $view = "user";
        $filtre_owner = "";
        $index_owner = "";
        $sequence_owner = "";
        $table = $name;
        if ($name) {
            $aTable = explode ( ".", $name );
            // dans le cas où le user est explicitement déclaré
            if (count ( $aTable ) == 2) {
                $view = "all";
                $filtre_owner = " and upper(owner) = upper('" . $aTable [0] . "')";
                $index_owner = " and all_cons_columns.owner=all_constraints.owner and upper(all_cons_columns.owner) = upper('" . $aTable [0] . "')";
                $sequence_owner = " and upper(sequence_owner) = upper('" . $aTable [0] . "')";
                $table = $aTable [1];
            }
        }
        switch ($type) {
            case 'infos' :
                {
                    $return ["compatibility"] = $this->queryItem ( 'select value from sys.database_compatible_level' );
                    $return ["description"] = oci_server_version ( $this->id );
                    if (preg_match ( '/([0-9]+\.([0-9\.])+)/', $str, $arr )) {
                        $return ["version"] = $arr [1];
                    }
                    $return ["type"] = $this->databaseTitle;
                    $return ["host"] = $_SERVER ["SERVER_NAME"];
                    $return ["instance"] = $this->databaseName;
                    break;
                }
            case 'tables' :
                {

                    /** A COMPLETER */
                    $query = "SELECT TABLE_NAME FROM " . $view . "_tables where TABLE_NAME NOT LIKE 'DR\$%' AND TABLE_NAME NOT LIKE 'BIN\$%' order by TABLE_NAME";
                    $result = $this->queryTab ( $query );
                    foreach ($result as $ligne) {
                        $return [] = $ligne ["TABLE_NAME"];
                    }
                    break;
                }
            case 'views' :
                {
                    $query = "SELECT VIEW_NAME FROM " . $view . "_views order by view_name";
                    $result = $this->queryTab ( $query );
                    if ($result) {
                        foreach ($result as $val) {
                            $return [] = $val ['VIEW_NAME'];
                        }
                    }
                    break;
                }
            case 'fields' :
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
                        data_precision    \"precision\",
                        data_scale     \"data_scale\"
                        from " . $view . "_tab_columns
                        where upper(table_name)=upper('" . $table . "')" . $filtre_owner . " ORDER BY COLUMN_ID";
                    $result = $this->queryTab ( $query );
                    $Keys = $this->getDbInfo ( 'keys', $name );
                    $FKeys = $this->getDbInfo ( 'foreign_keys', $name );
                    if ($Keys) {
                        for ($i = 0; $i < count ( $result ); $i ++) {
                            $sequence_name = "";
                            $result [$i] ["key"] = false;
                            $result [$i] ["null"] = ($result [$i] ["null"] ? true : false);
                            if ($result [$i] ["type"] == "DATE") {
                                $result [$i] ["length"] = null;
                            }
                            if ($result [$i] ['type'] == "NUMBER" && $result [$i] ['data_scale'] > 0) {
                                $result [$i] ['type'] = "DECIMAL";
                            }
                            if ($result [$i] ['type'] == "FLOAT" && $result [$i] ["precision"] == 126) {
                                $result [$i] ['type'] = "DOUBLE";
                            }
                            if ($result [$i] ['type'] == "DECIMAL" && $result [$i] ["length"] == 38) {
                                $result [$i] ['type'] = "INTEGER";
                            }
                            if (in_array ( $result [$i] ["field"], $Keys )) {
                                $result [$i] ["key"] = true;
                                $sequence_name = $this->queryItem ( "select SEQUENCE_NAME from " . $view . "_sequences where upper(sequence_name)='SEQ_" . str_replace ( '_ID', '', strToUpper ( $result [$i] ["field"] ) ) . "'" . $sequence_owner );
                                if ($sequence_name) {
                                    $result [$i] ["sequence"] = true;
                                    $result [$i] ["sequence_name"] = $sequence_name;
                                }
                            }
                            if (isset ( $FKeys [$result [$i] ["field"]] )) {
                                $result [$i] ["fkey"] = $FKeys [$result [$i] ["field"]] ["parent_table"] . '.' . $FKeys [$result [$i] ["field"]] ["parent_field"];
                            }
                        }
                    }
                    $return = $result;
                    break;
                }
            case 'keys' :
                {
                    $query = "select /*+ RULE */ column_name from " . $view . "_cons_columns, " . $view . "_constraints where " . $view . "_cons_columns.constraint_name=" . $view . "_constraints.constraint_name and " . $view . "_cons_columns.table_name=" . $view . "_constraints.table_name and upper(" . $view . "_cons_columns.table_name)=upper('" . $table . "') and constraint_type='P'" . $index_owner . " order by position";
                    $result = $this->queryTab ( $query );
                    if ($result) {
                        foreach ($result as $column) {
                            $return [] = $column ['COLUMN_NAME'];
                        }
                    }
                    break;
                }
            case 'foreign_keys' :
                {
                    $query = "SELECT
                        CONSTRAINT_NAME,
                        R_OWNER,
                        R_CONSTRAINT_NAME
                        FROM " . $view . "_constraints
                        WHERE CONSTRAINT_TYPE = 'R'
                        AND UPPER(TABLE_NAME) = UPPER('" . $table . "')
                        " . $index_owner;
                    $result = $this->queryTab ( $query );
                    $return = false;
                    if ($result) {
                        foreach ($result as $ligne) {
                            $cons = $ligne ['CONSTRAINT_NAME'];
                            $rowner = $ligne ['R_OWNER'];
                            $rcons = $ligne ['R_CONSTRAINT_NAME'];
                            $cols = $this->queryTab ( "select column_name from " . $view . "_cons_columns where constraint_name='" . $cons . "'" . $index_owner . " order by position" );
                            $tabcol = $this->queryTab ( "select table_name, column_name from " . $view . "_cons_columns where owner='" . $rowner . "' and constraint_name='" . $rcons . "' order by position" );
                            if ($cols && $tabcol) {
                                for ($i = 0, $max = sizeof ( $cols ); $i < $max; $i ++) {
                                    //$return[$tabcol[$i][0]] = $cols[$i][0].'='.$tabcol[$i][1];
                                    $return [$tabcol [$i] ['COLUMN_NAME']] ["child_field"] = $tabcol [$i] ['COLUMN_NAME'];
                                    $return [$tabcol [$i] ['COLUMN_NAME']] ["parent_field"] = $cols [$i] ['COLUMN_NAME'];
                                    $return [$tabcol [$i] ['COLUMN_NAME']] ["parent_table"] = $tabcol [$i] ['TABLE_NAME'];
                                }
                            }
                        }
                    }
                    break;
                }
            case 'indexes' :
                {
                    $query = "select TABLE_NAME, INDEX_NAME,  UNIQUENESS  from " . $view . "_INDEXES WHERE UPPER(TABLE_NAME) = UPPER('" . $table . "')
                AND INDEX_TYPE = 'NORMAL'";
                    $index = $this->queryTab ( $query );
                    if ($index) {
                        $i = 0;
                        foreach ($index as $vidx) {
                            $return [strtoupper ( $vidx ['INDEX_NAME'] )] ["type"] = $vidx ['UNIQUENESS'];
                            $return [strtoupper ( $vidx ['INDEX_NAME'] )] ["name"] = strtoupper ( $vidx ['INDEX_NAME'] );
                            $sql = "select COLUMN_NAME
                        from " . $view . "_IND_COLUMNS
                        where INDEX_NAME='" . $vidx ['INDEX_NAME'] . "'
                        AND UPPER(TABLE_NAME) = UPPER('" . $table . "')
                        ORDER BY COLUMN_POSITION";
                            $fields = $this->queryTab ( $sql );
                            foreach ($fields as $f) {
                                $return [strtoupper ( $vidx ['INDEX_NAME'] )] ["fields"] [$i] = strtoupper ( $f ['COLUMN_NAME'] );
                                $i ++;
                            }
                        }
                    }

                    return $return;
                    break;
                }
            case 'sequences' :
                {
                    $query = "select SEQUENCE_NAME from " . $view . "_sequences ";
                    $result = $this->queryTab ( $query );
                    if ($result) {
                        foreach ($result as $val) {
                            $return [] = $val ['SEQUENCE_NAME'];
                        }
                    }
                    break;
                }
            case 'functions' :
                {
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
                        FROM " . $view . "_ARGUMENTS
                        ORDER BY PACKAGE_NAME, OBJECT_NAME, POSITION";
                    debug ( $query );
                    $result = $this->queryTab ( $query );
                    if ($result) {
                        foreach ($result as $ligne) {
                            $return [$ligne ["PACKAGE_NAME"]] [$ligne ["OBJECT_NAME"]] [$ligne ["POSITION"]] ["PARAMETER"] = $ligne ["PARAMETER"];
                            $return [$ligne ["PACKAGE_NAME"]] [$ligne ["OBJECT_NAME"]] [$ligne ["POSITION"]] ["TYPE"] = $ligne ["TYPE"];
                            $return [$ligne ["PACKAGE_NAME"]] [$ligne ["OBJECT_NAME"]] [$ligne ["POSITION"]] ["IN_OUT"] = $ligne ["IN_OUT"];
                            $return [$ligne ["PACKAGE_NAME"]] [$ligne ["OBJECT_NAME"]] [$ligne ["POSITION"]] ["LENGTH"] = $ligne ["DATA_LENGTH"];
                            $return [$ligne ["PACKAGE_NAME"]] [$ligne ["OBJECT_NAME"]] [$ligne ["POSITION"]] ["PRECISION"] = $ligne ["DATA_PRECISION"];

        //   $return[$ligne["package_name"]][$ligne["object_name"]]["call"]="BEGIN\nDECLARE\n \nEND";

                        }
                    }
                    debug ( $return );
                    break;
                }
            default :
                {
                    return null;

                /** fin du case */
                }
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
        return "SYSDATE";
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::getDateDiffClause()
     * @param  __TYPE__ $date1 __DESC__
     * @param  __TYPE__ $date2 __DESC__
     * @return __TYPE__
     */
    public function getDateDiffClause($date1, $date2)
    {
        $return = " " . $date1 . " - " . $date2 . " ";

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::getDateAddClause()
     * @param  __TYPE__ $date     __DESC__
     * @param  __TYPE__ $interval __DESC__
     * @return __TYPE__
     */
    public function getDateAddClause($date, $interval)
    {
        $return = $date . " + " . $interval;

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::dateStringToSql()
     * @param  string   $strChaine __DESC__
     * @param  bool     $hour      (option) __DESC__
     * @return __TYPE__
     */
    public function dateStringToSql($strChaine, $hour = true)
    {
        $complement = "";
        if ($hour) {
            $complement = " HH24:MI:SS";
        }
        switch ($this->dateFormat) {
            case "MM/DD/YYYY" :
                {
                    $temp_contenu_date = "TO_DATE('" . $strChaine . "','MM/DD/YYYY" . $complement . "')";
                    break;
                }
            default : //DD/MM/YYYY
                {
                    $temp_contenu_date = "TO_DATE('" . $strChaine . "','DD/MM/YYYY" . $complement . "')";
                    break;
                }
        }

        return ($temp_contenu_date);
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::dateSqlToString()
     * @param  __TYPE__ $dateField  __DESC__
     * @param  bool     $hour       (option) __DESC__
     * @param  string   $complement (option) __DESC__
     * @return __TYPE__
     */
    public function dateSqlToString($dateField, $hour = false, $complement = "")
    {
        if ($hour) {
            $complement = " HH24:MI:SS";
        }
        switch ($this->dateFormat) {
            case "MM/DD/YYYY" :
                {
                    $temp_date = "TO_CHAR(" . $dateField . ",'MM/DD/YYYY" . $complement . "')";
                    break;
                }
            default : //DD/MM/YYYY
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
     * @param  __TYPE__ $dateField __DESC__
     * @return __TYPE__
     */
    public function dateSqlToStringShort($dateField)
    {
        if (! isset ( $this->dateFormat ))
            $this->dateFormat = "DD/MM/YYYY";
        switch ($this->dateFormat) {
            case "MM/DD/YYYY" :
                {
                    $temp_date = "TO_CHAR(" . $dateField . ",'MM/DD/YYYY')";
                    break;
                }
            default : //DD/MM/YYYY
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
     * @param  __TYPE__ $dateField __DESC__
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
     * @param  __TYPE__ $dateField __DESC__
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
     * @param  __TYPE__ $value __DESC__
     * @return __TYPE__
     */
    public function stringToSql($value)
    {
        return str_replace ( "\'", "''", $value );
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::getLimitedSQL()
     * @param  __TYPE__ $query  __DESC__
     * @param  __TYPE__ $min    __DESC__
     * @param  __TYPE__ $length __DESC__
     * @param  bool     $bind   (option) __DESC__
     * @return __TYPE__
     */
    public function getLimitedSQL($query, $min, $length, $bind = false)
    {
        global $bind;
        if ($bind) {
            $query = "SELECT sub_query.*, rownum AS num_ligne FROM ( $query ) sub_query where rownum <= :NOMBRE_LIGNES ";
            $return = "SELECT * FROM ( " . $query . " ) WHERE num_ligne >= :NUM_LIGNE ";
            $aBind [":NOMBRE_LIGNES"] = $length + $min - 1;
            $aBind [":NUM_LIGNE"] = $min;
        } else {
            $query = "SELECT sub_query.*, rownum AS num_ligne FROM ( $query ) sub_query where rownum <= " . ($length + $min - 1) . " ";
            $return = "SELECT * FROM ( " . $query . " ) WHERE num_ligne >= " . $min . " ";
        }

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::getCountSQL()
     * @param  __TYPE__ $query       __DESC__
     * @param  __TYPE__ $countFields __DESC__
     * @return __TYPE__
     */
    public function getCountSQL($query, $countFields)
    {
        $deb = strpos ( strToLower ( $query ), "from" );
        $fin = $this->arrayMin ( array (
                strpos ( strToLower ( $query ), "order by" ),
                strpos ( strToLower ( $query ), "group by" ),
                strpos ( strToLower ( $query ), "having" ) ) );
        if (! $fin) {
            $fin = strlen ( $query );
        }
        $sql = "select count(" . (is_numeric ( $countFields ) ? $countFields : ($countFields ? "distinct " . $countFields : "*")) . ") " . substr ( $query, $deb, ($fin - $deb) );

        return $sql;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::getSearchClause()
     * @param  __TYPE__ $field    __DESC__
     * @param  __TYPE__ $value    __DESC__
     * @param  __TYPE__ $position __DESC__
     * @param  __TYPE__ $bindName __DESC__
     * @param  __TYPE__ $aBind    __DESC__
     * @param  __TYPE__ $join     (option) __DESC__
     * @return __TYPE__
     */
    public function getSearchClause($field, $value, $position = 0, $bindName = "", &$aBind, $join = "OR")
    {
        if (Pelican::$config ["ORACLE_INTERMEDIA"]) {
            $request = "{\$" . str_replace ( "'", "''", str_replace ( " ", "}" . ($join == "OR" ? "|" : "&") . "{\$", $value ) ) . "}";
            if ($bindName == "") {
                $return = " contains(" . $field . ", '" . $request . "'," . $position . ") > 0";
            } else {
                $return = " contains(" . $field . ", " . $bindName . "," . $position . ") > 0";
                $aBind [$bindName] = $request;
            }
        } else {
            if ($bindName == "") {
                $return = " LOWER(" . $field . ") LIKE '%" . $value . "%'";
            } else {
                $request = "%" . str_replace ( "'", "''", stripslashes ( strtolower ( html_entity_decode ( $value ) ) ) ) . "%";
                $return = "LOWER(" . $field . ") LIKE " . $bindName;
                $aBind [$bindName] = $request;
            }
        }

        return $return;
    }

    /**
     * @see Pelican_Db::getSearchClauseLike()
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
     * @param  __TYPE__ $clause __DESC__
     * @param  __TYPE__ $value  __DESC__
     * @return __TYPE__
     */
    public function getNVLClause($clause, $value)
    {
        /** Si la valeur est du texte et le champ un chiffre, ORACLE crée une erreur */
        if (! is_numeric ( $value )) {
            $clause = "TO_CHAR(" . $clause . ")";
        }
        $return = " NVL(" . $clause . "," . $value . ") ";

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::getConcatClause()
     * @param  __TYPE__ $aValue __DESC__
     * @return __TYPE__
     */
    public function getConcatClause($aValue)
    {
        $return = " " . implode ( "||", $aValue ) . " ";

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::getCaseClause()
     * @param  __TYPE__ $field        __DESC__
     * @param  __TYPE__ $aClause      __DESC__
     * @param  __TYPE__ $defaultValue __DESC__
     * @return __TYPE__
     */
    public function getCaseClause($field, $aClause, $defaultValue)
    {
        IF ($field && $aClause) {
            $return = "DECODE(" . $field . ",";
            foreach ($aClause as $key => $value) {
                $temp [] = $key . "," . $value;
            }
            $return .= implode ( ",", $temp );
            $return .= "," . $defaultValue . ")";
        }

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @see Pelican_Db::duplicateRecord()
     * @param  __TYPE__ $table    __DESC__
     * @param  __TYPE__ $key      __DESC__
     * @param  __TYPE__ $oldValue __DESC__
     * @param  __TYPE__ $newValue __DESC__
     * @return __TYPE__
     */
    public function duplicateRecord($table, $key, $oldValue, $newValue)
    {
        $fieldSet = $this->describeTable ( $table );
        $strSQL = "INSERT INTO " . $table . " SELECT ";
        $j = - 1;
        foreach ($fieldSet as $field) {
            $j ++;
            if ($field ["increment"]) {
                $autoIncrement = $field ["field"];
            }
            if ($field ["field"] != $key) {
                $strSQL .= $field ["field"] . ", ";
            } else {
                $key_type = $field ["type"];
                $strSQL .= $this->formatField ( $newValue, $field ["type"] ) . ", ";
            }
        }
        $strSQL .= "FROM " . $table . " WHERE " . $key . "=" . $this->formatField ( $oldValue, $key_type );
        $strSQL = str_replace ( ", FROM", " FROM", $strSQL );
        $this->query ( $strSQL );
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $type      __DESC__
     * @param  __TYPE__ $length    __DESC__
     * @param  __TYPE__ $precision __DESC__
     * @return __TYPE__
     */
    public function getFieldTypeDDL($type, $length, $precision)
    {
        if (in_array ( $type, array (
                self::SMALLINT,
                self::TINYINT,
                self::INTEGER,
                self::BIGINT,
                self::IDENTITY ) )) {
            $length = null;
            $precision = null;
        }
        $complement = '';
        $return = self::nativeType ( $type ) . ($length ? '(' . $length . ($precision ? ',' . $precision : '') . ')' : '') . $complement;

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $null __DESC__
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
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $default __DESC__
     * @param  __TYPE__ $type    __DESC__
     * @param  __TYPE__ $null    __DESC__
     * @return __TYPE__
     */
    public function getFieldDefaultDDL($default, $type, $null)
    {
        $return = '';
        if (isset ( $default )) {
            // NULL non pris en compte pour les textes
            /*if ($default == 'NULL' && in_array($type,self::$TEXT_TYPES) && !in_array($type,self::$DATE_TYPES)) {
                            $return = '';
                            } else*/
            if ($default == 'NULL' && ! $null) {
                $return = '';
            } else {
                if (in_array ( $type, self::$TEXT_TYPES ) && $default != 'NULL') {
                    $default = str_replace ( "''", "", "'" . $default . "'" );
                }
                $return = trim ( 'default ' . $default );
            }
        }
        if ($return == 'default') {
            $return = '';
        }

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $increment __DESC__
     * @param  string   $type      (option) __DESC__
     * @return __TYPE__
     */
    public function getFieldIncrementDDL($increment, $type = "")
    {
        $return = '';

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $table   __DESC__
     * @param  __TYPE__ $name    __DESC__
     * @param  __TYPE__ $aFields __DESC__
     * @return __TYPE__
     */
    public function getPrimaryKeyDDL($table, $name, $aFields)
    {
        $return = 'CONSTRAINT ' . $name . ' PRIMARY KEY (' . implode ( ',', $aFields ) . ')';

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $table   __DESC__
     * @param  __TYPE__ $aFields __DESC__
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
     * @param  __TYPE__ $table   __DESC__
     * @param  __TYPE__ $name    __DESC__
     * @param  __TYPE__ $aFields __DESC__
     * @param  bool     $unique  (option) __DESC__
     * @return __TYPE__
     */
    public function getIndexDDL($table, $name, $aFields, $unique = false)
    {
        $return = "CREATE" . ($unique ? " UNIQUE" : "") . " INDEX " . $name . " ON " . $table . " (" . implode ( ',', $aFields ) . ");";

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $table      __DESC__
     * @param  __TYPE__ $name       __DESC__
     * @param  __TYPE__ $childField __DESC__
     * @param  __TYPE__ $source     __DESC__
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
     * @param  __TYPE__ $name      __DESC__
     * @param  __TYPE__ $start     (option) __DESC__
     * @param  __TYPE__ $increment (option) __DESC__
     * @return __TYPE__
     */
    public function getSequenceDDL($name, $start = "1", $increment = "1")
    {
        $return = 'CREATE SEQUENCE ' . $name . ' INCREMENT BY ' . $increment . ' START WITH ' . $start . ' NOMAXVALUE NOCYCLE NOCACHE ORDER;';

        return trim ( $return );
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $table  __DESC__
     * @param  __TYPE__ $name   __DESC__
     * @param  __TYPE__ $aField __DESC__
     * @return __TYPE__
     */
    public function getUniqueKeyDDL($table, $name, $aField)
    {
        $return = 'ALTER TABLE {' . $table . '} ADD CONSTRAINT ' . $name . ' UNIQUE (' . implode ( ',', $aField ) . ')';

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $table         __DESC__
     * @param  __TYPE__ $field         __DESC__
     * @param  __TYPE__ $sequence_name __DESC__
     * @return __TYPE__
     */
    public function getUpdateSequenceDDL($table, $field, $sequence_name)
    {
        $return = '';
        /**vérification de l'existance de la séquence */
        /*		$oConnection = Pelican_Db::getInstance();
                        $seq = "select count(1) from user_sequences where UPPER(SEQUENCE_NAME)='".strtoupper($sequence_name)."'";
                        $exists = $oConnection->queryItem($seq);
                        if ($exists) {*/
        $max = $oConnection->queryItem ( "SELECT MAX(" . $field . ") FROM " . $table );
        if (! $max)
            $max = 0;
        $max ++;
        $return = "DROP SEQUENCE " . $sequence_name . ";
                CREATE SEQUENCE " . $sequence_name . "
                START WITH " . $max . "
                MAXVALUE 999999999999999999999999999
                MINVALUE 1
                NOCYCLE
                NOCACHE
                NOORDER";
        /*		}*/

        return $return;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $type __DESC__
     * @return __TYPE__
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
     * __DESC__
     *
     * @
     *
     * @access public
     * @param  __TYPE__ $type __DESC__
     * @return __TYPE__
     */
    public function nativeType($type)
    {
        $return = self::$nativeTypes [strtoupper ( $type )];

        return $return;
    }
}
