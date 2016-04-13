<?php
/**
 * Gestion des accès en base de données
 *
 * @package Pelican
 * @subpackage Db
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * Couche d'abstraction de base de données
 *
 * Dans cette classe sont définies les méthodes génériques d'accès aux
 * données.
 * Le fichier inclut offre un certain nombre de fonctionnalités spécifiques à
 * la base de données cible
 * comme la création d'une connexion, l'exécution d'une requête
 * devant retourner un champ, une ligne ou un ensemble de ligne ou
 * encore l'affichage du temps d'exécution d'un requête, la récupération
 * du dernier enregistrement inséré au cours de la session...
 *
 * @package Pelican
 * @subpackage Db
 * @author Guillaume Gageonnet <ggageonnet@businessdecision.com>
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @author Jean-Baptiste Ruscassié <jbruscassie@businessdecision.com>
 * @since 22/01/2001, 15/05/2003, 15/09/2003, 04/04/2004
 * @version 3.0
 */
abstract class Pelican_Db {
    
    /**
     * __DESC__
     *
     * @static
     * @access public
     * @var __TYPE__
     */
    static $values = array();
    const DATABASE_INSERT = "INS";
    const DATABASE_UPDATE = "UPD";
    const DATABASE_DELETE = "DEL";
    const DATABASE_INSERT_ID = "-2";
    
    /**
     * Constante d'identification d'une action d'insertion
     *
     * @access public
     * @var string
     */
    public $actionInsert = "INS";
    
    /**
     * Constante d'identification d'une action de modification
     *
     * @access public
     * @var string
     */
    public $actionUpdate = "UPD";
    
    /**
     * Constante d'identification d'une action de suppression
     *
     * @access public
     * @var string
     */
    public $actionDelete = "DEL";
    
    /**
     * Constante définissant la valeur par défaut des champs
     *
     * @access public
     * @var string
     */
    public $idInsert = "-2";
    
    /**
     * Affichage ou non des erreurs de base de données
     *
     * @access public
     * @var bool
     */
    public $showError = true;
    
    /**
     * Formattage des chaines de textes utilisées pour les conversions de
     *
     * @access public
     * @var string
     */
    public $dateFormat = "DD/MM/YYYY";
    
    /**
     * Formattage des chaines de textes utilisées pour les conversions de
     *
     * @access public
     * @var string
     */
    public $dateFormatPhp = "d/m/Y";
    
    /**
     * Affichage des requêtes exécutées, des variables associées et du
     *
     * @access public
     * @var bool
     */
    public $debug = false;
    
    /**
     * Arrêt du code en cas d'erreur de BDD
     *
     * @access public
     * @var bool
     */
    public $bExit = true;
    
    /**
     * Lever une exception PHP en cas d'erreur
     *
     * @access public
     * @var bool
     */
    public $bException = false;
    
    /**
     * Liste des champs exclus de la génération automatique de requête
     *
     * @access public
     * @var string
     */
    public $tableStopList = "";
    
    /**
     * Identifiant de l'objet de connexion
     *
     * @access public
     * @var string
     */
    public $id;
    
    /**
     * Adresse IP ou Host du serveur de base de données
     *
     * @access public
     * @var string
     */
    public $host;
    
    /**
     * Port à utiliser pour se connecter à la base
     *
     * @access public
     * @var string
     */
    public $port;
    
    /**
     * Nom de la base de donnée
     *
     * @access public
     * @var string
     */
    public $databaseName;
    
    /**
     * Nom de l'utilisateur de base de données
     *
     * @access public
     * @var string
     */
    public $user;
    
    /**
     * Mot de passe de l'utilisateur de base de données
     *
     * @access public
     * @var string
     */
    public $passwd;
    
    /**
     * Infos de version de la base en cours
     *
     * @access public
     * @var string
     */
    public $info;
    
    /**
     * Connexion à la base de données persistante ou non
     *
     * @access public
     * @var string
     */
    public $persistency;
    
    /**
     * Indique si ont doit afficher le temps d'exécution des requêtes SQL
     *
     * @access public
     * @var string
     */
    public $debugTime = false;
    
    /**
     * Variable marquant temporellement le début de la requête
     *
     * @access public
     * @var string
     */
    public $time_deb;
    
    /**
     * Variable contenant le temps d'exécution de la requête
     *
     * @access public
     * @var string
     */
    public $time_end;
    
    /**
     * Objet content le résulat de la dernière requête exécutée
     *
     * @access public
     * @var mixed
     */
    public $result;
    
    /**
     * Nombre de lignes renvoyées par la dernière requête
     *
     * @access public
     * @var int
     */
    public $rows;
    
    /**
     * Nombre de colonnes renvoyés par la dernière requête
     *
     * @access public
     * @var int
     */
    public $fields;
    
    /**
     * Tableau contenant le résultat de la dernière requête. Tableau à 2 niveaux
     * (champs, lignes)
     *
     * @access public
     * @var mixed
     */
    public $data;
    
    /**
     * Nombre de lignes affectées par la dernière requête
     *
     * @access public
     * @var int
     */
    public $affectedRows;
    
    /**
     * Nombre contenant l'id (champ de type AUTO_INCREMENT) du dernier enregistrement
     * inséré
     *
     * @access public
     * @var string
     */
    public $lastInsertedId;
    
    /**
     * Requête en cours
     *
     * @access public
     * @var string
     */
    public $query;
    
    /**
     * Message d'erreur issu de la base de données
     *
     * @access public
     * @var string
     */
    public $errorMsg;
    
    /**
     * Dernière erreur remontée par la base
     *
     * @access public
     * @var string
     */
    public $lastError;
    
    /**
     * Noms des champs du résultat
     *
     * @access public
     * @var mixed
     */
    public $name;

    /**
     * Largeur des champs du résultat
     *
     * @access public
     * @var mixed
     */
    public $len;
    
    /**
     * Tableau des variables bind
     *
     * @access public
     * @var mixed
     */
    public $param;
    
    /**
     * Liste des types de champs d'un résultat de requête
     *
     * @access public
     * @var mixed
     */
    public $type;
    
    /**
     * __DESC__
     *
     * @access public
     * @var mixed
     */
    public $useCacheDescribe = true;
    
    /**
     * Charset de la connexion
     *
     * @access public
     * @var string
     */
    public $charset;
    protected $err;
    const BIGINT = "BIGINT";
    const BINARY = "BINARY";
    const BLOB = "BLOB";
    const BOOLEAN = "BOOLEAN";
    const CHAR = "CHAR";
    const CLOB = "CLOB";
    const DATE = "DATE";
    const DATETIME = "DATETIME";
    const DECIMAL = "DECIMAL";
    const DOUBLE = "DOUBLE";
    const FLOAT = "FLOAT";
    const IDENTITY = "IDENTITY";
    const INTEGER = "INTEGER";
    const LONGVARBINARY = "LONGVARBINARY";
    const LONGVARCHAR = "LONGVARCHAR";
    const NUMERIC = "NUMERIC";
    const REAL = "REAL";
    const SMALLINT = "SMALLINT";
    const TIME = "TIME";
    const TIMESTAMP = "TIMESTAMP";
    const TINYINT = "TINYINT";
    const TINYTEXT = "TINYTEXT";
    const VARBINARY = "VARBINARY";
    const VARCHAR = "VARCHAR";

    static $TEXT_TYPES = array(
        self::CHAR , 
        self::VARCHAR , 
        self::LONGVARCHAR , 
        self::CLOB , 
        self::DATE , 
        self::TIME , 
        self::TIMESTAMP , 
        self::TINYTEXT
    );

    static $LOB_TYPES = array(
        self::VARBINARY , 
        self::LONGVARBINARY , 
        self::BLOB , 
        self::CLOB
    );

    static $DATE_TYPES = array(
        self::DATE , 
        self::DATETIME , 
        self::TIME , 
        self::TIMESTAMP
    );

    static $NUMERIC_TYPES = array(
        self::SMALLINT , 
        self::TINYINT , 
        self::INTEGER , 
        self::BIGINT , 
        self::FLOAT , 
        self::DOUBLE , 
        self::NUMERIC , 
        self::DECIMAL , 
        self::REAL , 
        self::IDENTITY
    );

    static $IDENTITY_TYPES = array(
        self::IDENTITY
    );

    static $fieldTypes = array(
        'ANSIDATE' => self::DATE , 
        '_INT4' => self::INTEGER , 
        'BIGINT' => self::BIGINT , 
        'BINARY' => self::BINARY , 
        'BIT' => self::BOOLEAN , 
        'BLOB' => self::BLOB , 
        'BOOL' => self::BOOLEAN , 
        'BOOLEAN' => self::BOOLEAN , 
        'BPCHAR' => self::CHAR , 
        'BYTE' => self::BINARY , 
        'BYTE VARYING' => self::VARBINARY , 
        'BYTEA' => self::BINARY , 
        'CHAR' => self::CHAR , 
        'CLOB' => self::CLOB , 
        'COUNTER' => self::IDENTITY , 
        'DATE' => self::DATE , 
        'DATETIME' => self::DATETIME , 
        'DEC' => self::DECIMAL , 
        'DECIMAL' => self::DECIMAL , 
        'DOUBLE' => self::DOUBLE , 
        'DOUBLE PRECISION' => self::DOUBLE , 
        'ENUM' => self::CHAR , 
        'FLOAT' => self::FLOAT , 
        'FLOAT4' => self::FLOAT , 
        'FLOAT8' => self::FLOAT , 
        'IMAGE' => self::BINARY , 
        'INGRESDATE' => self::DATETIME , 
        'INT' => self::INTEGER , 
        'INT2' => self::SMALLINT , 
        'INT24' => self::BIGINT , 
        'INT4' => self::INTEGER , 
        'INT8' => self::INTEGER , 
        'INTEGER' => self::INTEGER , 
        'INTEGER1' => self::TINYTEXT , 
        'INTEGER2' => self::DOUBLE , 
        'INTEGER4' => self::BIGINT , 
        'INTERVAL' => self::TIME , 
        'LONG' => self::LONGVARCHAR , 
        'LONG BYTE' => self::LONGVARBINARY , 
        'LONG VARCHAR' => self::LONGVARCHAR , 
        'LONGBLOB' => self::BLOB , 
        'LONGTEXT' => self::CLOB , 
        'LVARCHAR' => self::LONGVARCHAR , 
        'MEDIUMBLOB' => self::BLOB , 
        'MEDIUMINT' => self::SMALLINT , 
        'MEDIUMTEXT' => self::LONGVARCHAR , 
        'MONEY' => self::FLOAT , 
        'NCHAR' => self::CHAR , 
        'NCLOB' => self::CLOB , 
        'NUM' => self::NUMERIC , 
        'NUMBER' => self::BIGINT , 
        'NUMERIC' => self::NUMERIC , 
        'NVARCHAR' => self::VARCHAR , 
        'NVARCHAR2' => self::VARCHAR , 
        'OID' => self::BLOB , 
        'REAL' => self::REAL , 
        'SERIAL' => self::IDENTITY , 
        'SET' => self::CHAR , 
        'SMALLFLOAT' => self::FLOAT , 
        'SMALLINT' => self::SMALLINT , 
        'TEXT' => self::LONGVARCHAR , 
        'TIME' => self::TIME , 
        'TIMESTAMP' => self::TIMESTAMP , 
        'TIMESTAMPTZ' => self::TIMESTAMP , 
        'TIMETZ' => self::TIME , 
        'TINYBLOB' => self::BINARY , 
        'TINYINT' => self::TINYINT , 
        'TINYTEXT' => self::TINYTEXT , 
        'VARCHAR' => self::VARCHAR , 
        'VARCHAR2' => self::VARCHAR , 
        'YEAR' => self::INTEGER
    );
    
    /**
     * Constructeur : hérite de la classe parente spécifique à la base de données
     * cible offre toutes les méthodes "génériques" d'accès aux données
     *
     * <code>
     * $oConnection = Pelican_Factory::getInstance('Db',
     * Pelican::$config["DATABASE_NAME"],
     * Pelican::$config["DATABASE_USER"],
     * Pelican::$config["DATABASE_PASS"],
     * Pelican::$config["DATABASE_HOST"],
     * Pelican::$config["DATABASE_PORT"]);
     * </code>
     *
     * @access public
     * @param string $databaseName Le nom de la base de données
     * @param string $username Le nom de l'utilisateur servant à la connexion
     * @param string $password Le mot de passe de connexion
     * @param string $host (option) L'adresse IP du serveur (optionnel) : 127.0.0.1
     * par défaut
     * @param string $port (option) Le port de connexion du serveur (optionnel) : ""
     * par défaut
     * @param bool $bExit (option) __DESC__
     * @return database
     */
    public function __construct($databaseName, $username, $password, $host = "", $port = "", $bExit = true) {
        global $allowBind;
        //Pelican_Db::DATABASE_INSERT = (Pelican::$config["DATABASE_INSERT"] ? Pelican::$config["DATABASE_INSERT"] : "INS");
        //Pelican_Db::DATABASE_UPDATE = (Pelican::$config["DATABASE_UPDATE"] ? Pelican::$config["DATABASE_UPDATE"] : "UPD");
        //Pelican_Db::DATABASE_DELETE = (Pelican::$config["DATABASE_DELETE"] ? Pelican::$config["DATABASE_DELETE"] : "DEL");
        $this->idInsert = (Pelican::$config["DATABASE_INSERT_ID"] ? Pelican::$config["DATABASE_INSERT_ID"] : "-2");
        $this->dateFormat = (t('DATE_FORMAT_DB') ? t('DATE_FORMAT_DB') : "DD/MM/YYYY");
        $this->dateFormatPhp = t('DATE_FORMAT_PHP');
        $this->bExit = $bExit;
        $log = "";
        if (!empty(Pelican::$trace['time'])) {
            if (is_array(Pelican::$trace['time'])) {
                $log = end(array_keys(Pelican::$trace['time']));
            }
        }
        $this->setExitOnError($bExit);
        $allowBind = $this->allowBind;
        Pelican::$trace['connect'][] = ($log ? $log : "script non identifié");
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @return void
     */
    public function __destruct() {
        $this->close();
    }
    
    /**
     * Défini si une erreur de base de données arrête le code PHP ou non
     *
     * @access public
     * @param bool $bool (option) __DESC__
     * @return true
     */
    public function setExitOnError($bool = true) {
        $this->bExit = $bool;
        return true;
    }
    
    /**
     * Défini si une erreur de base de données crée une exception PHP ou non
     *
     * @access public
     * @param bool $bool (option) __DESC__
     * @return true
     */
    public function setExceptionOnError($bool = true) {
        $this->bException = $bool;
        return true;
    }
    
    /**
     * __DESC__
     *
     * @static
     * @access public
     * @param __TYPE__ $text __DESC__
     * @return __TYPE__
     */
    public static function replacePrefix($text) {
        $return = str_replace('#pref#_', Pelican::$config['FW_PREFIXE_TABLE'], $text);
        return $return;
    }
    
    /**
     * Création d'un tableau de valeur à partir d'une requête pour un formulaire :
     * tableau de la forme $values[CHAMP]=VALEUR;
     *
     * @access public
     * @param string $query Requête SQL
     * @param mixed $param (option) Variables bind de type array(":bind"=>"value")
     * @param mixed $paramLob (option) Variables bind des champs CLOB
     * @return mixed
     */
    public function queryForm($query, $param = array(), $paramLob = array()) {
        $values = $this->queryRow($query, $param, $paramLob, true);
        return $values;
    }
    
    /**
     * Même utilisation que QueryTab mais seulement avecf les noms de champ
     *
     * @access public
     * @param string $query __DESC__
     * @param mixed $param (option) __DESC__
     * @param mixed $paramLob (option) __DESC__
     * @return mixed
     */
    public function getTab($query, $param = array(), $paramLob = array()) {
        return $this->queryTab($query, $param, $paramLob, true);
    }
    
    /**
     * Même utilisation que QueryRow mais seulement avecf les noms de champ
     *
     * @access public
     * @param string $query __DESC__
     * @param mixed $param (option) __DESC__
     * @param mixed $paramLob (option) __DESC__
     * @return mixed
     */
    public function getRow($query, $param = array(), $paramLob = array()) {
        return $this->queryRow($query, $param, $paramLob, true);
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param string $query __DESC__
     * @param mixed $param (option) __DESC__
     * @param mixed $paramLob (option) __DESC__
     * @return string
     */
    function getItem($query, $param = array(), $paramLob = array()) {
        return $this->queryItem($query, $param, $paramLob);
    }
    
    /**
     * Execute une requête sans écraser les tableaux de résultat en cours
     * ($this->data)
     *
     * @access public
     * @param string $query Requête SQL
     * @param mixed $param (option) Variables bind de type array(":bind"=>"value")
     * @param mixed $paramLob (option) Variables bind des champs CLOB
     * @return mixed
     */
    public function queryThru($query, $param = array(), $paramLob = array()) {
        // Sauvegarde du contexte actuel
        $data_bkp = $this->data;
        $rows_bkp = $this->rows;
        $this->query($query, $param, $paramLob);
        $data1 = $this->data;
        $this->data = $data_bkp;
        $this->rows = $rows_bkp;
        return ($data1);
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param string $query __DESC__
     * @return string
     */
    public function queryXML($query) {
        // Execution de la requete
        $result = $this->queryTab($query);
        $xml = "<?xml version=\"1.0\" encoding=\"" . (Pelican::$config["CHARSET"] ? Pelican::$config["CHARSET"] : "ISO-8859-1") . "\" ?>\r\n";
        $xml.= "<" . Pelican::$config["DATABASE_TYPE"] . " rows=\"" . $this->rows . "\" fields=\"" . $this->fields . "\">\r\n";
        for ($i = 0;$i < $this->rows;$i++) {
            $xml.= "\t<RECORD line=\"" . ($i + 1) . "\">\r\n";
            for ($field = 0;$field < $this->fields;$field++) {
                $string = false;
                if ($this->type[$field] == "string" || $this->type[$field] == "blob") {
                    $string = true;
                }
                $name_field = strtoupper($this->name[$field]);
                $xml.= "\t\t<" . $name_field . " type=\"" . $this->type[$field] . "\" width=\"" . $this->len[$field] . "\">\r\n";
                $xml.= "" . htmlspecialchars($result[$i][$this->name[$field]]);
                $xml.= "\t\t</" . $name_field . ">\r\n";
            }
            $xml.= "\t</RECORD>\r\n";
        }
        $xml.= "</" . Pelican::$config["DATABASE_TYPE"] . ">\r\n";
        // renvoie du résultat
        return ($xml);
    }
    
    /**
     * Retourne le résultat d'une requête en XML
     *
     * @access public
     * @param string $query Chaine SQL
     * @param string $root (option) __DESC__
     * @param bool $full (option) __DESC__
     * @return string
     */
    public function queryXML2($query, $root = "", $full = false) {
        if (!$root) {
            if (Pelican::$config["DATABASE_XML_ROOT"]) {
                $root = Pelican::$config["DATABASE_XML_ROOT"];
            } else {
                $root = Pelican::$config["DATABASE_TYPE"];
            }
        }
        // Execution de la requete
        $result = $this->getTab($query);
        $dom = new DOMDocument('1.0', 'UTF-8');
        $node = $dom->appendChild($dom->createElement($root));
        if ($full) {
            $node->setAttributeNode(new DOMAttr('rows', $this->rows));
            $node->setAttributeNode(new DOMAttr('fields', $this->fields));
        }
        for ($i = 0;$i < $this->rows;$i++) {
            $record = $node->appendChild($dom->createElement('RECORD'));
            for ($field = 0;$field < $this->fields;$field++) {
                $string = false;
                if ($this->type[$field] == "string" || $this->type[$field] == "blob") {
                    $string = true;
                }
                $name_field = strtoupper($this->name[$field]);
                $value = str_replace(array('{', '}', '"'), '', utf8_encode(str_replace('&', '&amp;', $result[$i][$this->name[$field]])));
                $fieldXML = $record->appendChild($dom->createElement($name_field, $value));
                if ($full) {
                    $fieldXML->setAttributeNode(new DOMAttr('type', $this->type[$field]));
                    $fieldXML->setAttributeNode(new DOMAttr('width', $this->len[$field]));
                }
            }
        }
        /*
        PHP strings <-> xsd:string.
        
        PHP integers <-> xsd:int.
        
        PHP floats and doubles <-> xsd:float.
        
        PHP booleans <-> xsd:boolean.
        
        PHP arrays <-> soap-enc:Array.
        
        PHP object <-> xsd:struct.
        
        PHP class <-> tns:$className [21].
        
        PHP void <-> empty type.
        
        */
        //echo($this->getXSD($query, $root));
        return $dom->saveXML();
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param string $query (option) __DESC__
     * @param string $root (option) __DESC__
     * @return string
     */
    public function getXSD($query = "", $root = "") {
        if ($query) {
            $result = $this->getRow($query);
        }
        if (!$root) {
            $root = 'RECORD';
        }
        $this->xsd = '<?xml version="1.0" encoding="utf-8" ?>
<xs:schema attributeFormDefault="unqualified" elementFormDefault="qualified" xmlns:xs="http://www.w3.org/2001/XMLSchema">
  <xs:element name="' . $root . '">
    <xs:complexType>
      <xs:all>
        <xs:element minOccurs="1" maxOccurs="1" name="' . $root . '">
          <xs:complexType>
            <xs:all>
            ';
        for ($field = 0;$field < $this->fields;$field++) {
            $this->xsd.= '              <xs:element minOccurs="1" maxOccurs="1" name="' . strtoupper($this->name[$field]) . '" type="xs:' . $this->getXSDType($this->type[$field]) . '" />
			';
        }
        $this->xsd.= '</xs:all>
          </xs:complexType>
        </xs:element>
      </xs:all>
    </xs:complexType>
  </xs:element>
</xs:schema>';
        return $this->xsd;
    }
    
    /**
     * __DESC__
     *
     * @access public
     * @param string $type __DESC__
     * @return string
     */
    public function getXSDType($type) {
        switch ($type) {
            case 'int4': {
                        $return = 'integer';
                    break;
                }
            case 'varchar': {
                    $return = 'string';
                    break;
                }
            default: {
                    $return = $type;
                    break;
                }
            }
            return $return;
        }
        
        /**
         * Assimilable à un tableau croisé : Transpose les valeurs du champ pivot pour
         * en
         * faire des champs à part et réparti
         *
         * @access public
         * @param string $sql Chaine SQL retournant toutes les combinatoires de valeur
         * @param string $sPivot Champ pivot (chaque valeur de ce champ devient un champ
         * à part entière)
         * @param mixed $aVariant Un (string) ou plusieurs (array) champs contenant les
         * valeurs à associer aux valeurs du champ pivot
         * @param array $param (option) __DESC__
         * @param array $paramLob (option) __DESC__
         * @return mixed
         */
        public function queryPivot($sql, $sPivot, $aVariant, $param = array(), $paramLob = array()) {
            $this->query($sql, $param, $paramLob);
            $data = $this->data;
            $aPivot = array();
            $pivotSummary = array();
            $return = "";
            // si le champ variant est seul, on le transforme en tableau
            if (!is_array($aVariant)) $aVariant = array($aVariant);
            // récupération des valeurs du pivot et des valeurs des champs variants associés
            if ($data) {
                foreach($data[$sPivot] as $key => $col) {
                    foreach($aVariant as $value) {
                        $aPivot[$col][$key] = array($value => $data[$value][$key]);
                    }
                }
            }
            // construction du tableau de retour sur le format d'un queryTab
            if (is_array($aPivot)) {
                //si aucun résultat sur requete !
                foreach($aPivot as $pivotvalue => $values) {
                    $pivotSummary[] = $pivotvalue;
                    $index = 0;
                    foreach($values as $line => $variants) {
                        if (!$line) $line = 0;
                        foreach($this->name as $field) {
                            if ($field != $sPivot) {
                                if (in_array($field, $aVariant)) {
                                    $return[$index][$pivotvalue] = $values[$line][$field];
                                } else {
                                    $return[$index][$field] = $data[$field][$line];
                                }
                            }
                        }
                        $index++;
                    }
                }
            }
            return array("values" => $return, "columns" => $pivotSummary);
        }
        
        /**
         * Retourne les valeurs d'une ligne
         *
         * @access public
         * @param int $row Numéro de la ligne
         * @param bool $light (option) Ne retourne que le champ et non le résultat
         * associatif
         * @return mixed
         */
        public function fetch($row, $light = false) {
            $cnt = 0;
            if ($this->data) {
                reset($this->data);
                foreach($this->data as $key => $val) {
                    //while (list ($key, $val) = each($this->data)) {
                    $return[$key] = $val[$row];
                    if (!$light) {
                        $return[$cnt++] = $val[$row];
                    }
                }
            }
            return $return;
        }
        
        /**
         * Permet d'afficher le haut du tableau servant à afficher les informations
         * (erreur, résultat d'une requète...).
         *
         * @access private
         * @param string $titre Le titre du tableau
         * @param string $bgcolor (option) La couleur de fond du titre (optionnel) :
         * #B24609 par défaut
         * @return string
         */
        private function getHeader($titre, $bgcolor = "#B24609") {
            return "<br /><TABLE BORDER=\"1\" CELLSPACING=\"1\" CELLPADDING=\"4\">
				<TR BGCOLOR=\"$bgcolor\">
				<TD ALIGN=\"center\" COLSPAN=\"2\">
				<FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFFFF\" SIZE=\"1\"><B>$titre</B></FONT>
				</TD>
				</TR>";
        }
        
        /**
         * Permet d'afficher le bas du tableau servant à afficher les informations
         * (erreur, résultat d'une requête...).
         *
         * @access private
         * @return string
         */
        private function getFooter() {
            return "</TABLE><br />";
        }
        
        /**
         * Permet d'afficher un message d'erreur de la base
         *
         * @access public
         * @param string $msg Le message d'erreur
         * @param string $from (option) __DESC__
         * @return void
         */
        public function error($msg, $from = "") {
            global $_REQUEST, $db;
            $this->rollback();
            $message = $this->getError();
            $message["description"] = "";
            if ($this->errorMsg) {
                $message["description"] = Pelican_Text::unhtmlentities(implode(" ", $this->errorMsg));
            }
            $this->logError($message);
            if (!Pelican::$config["SHOW_DEBUG"]) {
                header("location: /_/Error/code500");
            } else {
                if ($this->showError) {
                    $this->mailError($message, $msg);
                    echo $this->getHeader("DATABASE ERROR");
                    echo "<TR>
						<TD BGCOLOR=\"#D16D31\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>Error</B></FONT></TD>
						<TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#893737\" SIZE=\"2\"><B>" . nl2br($msg) . "</B></FONT></TD>
						</TR> ";
                    if ($message["code"] > "") {
                        echo "<TR>
							<TD BGCOLOR=\"#D16D31\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>N&deg; " . $this->databaseTitle . "</B></FONT></TD>
							<TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#893737\" SIZE=\"2\">" . $message["code"] . "&nbsp;</FONT></TD>
							</TR> ";
                    }
                    if ($message["message"] > "") {
                        echo "<TR>
							<TD BGCOLOR=\"#D16D31\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>Message " . $this->databaseTitle . "</B></FONT></TD>
							<TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#893737\" SIZE=\"2\">" . $message["message"] . "&nbsp;</FONT></TD>
							</TR>";
                        if (($this->param)) {
                            echo "<TR>
								<TD BGCOLOR=\"#D16D31\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>BIND</B></FONT></TD>
								<TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#893737\" SIZE=\"2\">";
                            debug($this->param, "Bind", false, true, array("Db.php", "Oracle.php", "Mysql.php", "List.php"));
                            echo "</FONT></TD>
								</TR>";
                            echo "<TR>
								<TD BGCOLOR=\"#D16D31\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>db</B></FONT></TD>
								<TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#893737\" SIZE=\"2\">" . $db . "</FONT></TD>
								</TR>";
                        }
                    }
                    echo "<TR>
						<TD BGCOLOR=\"#D16D31\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>BackTrace</B></FONT></TD>
						<TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#893737\" SIZE=\"2\">" . Pelican_Debug::debug_trace() . "</FONT></TD>
						</TR>";
                    echo $this->getFooter();
                    echo "<br>";
                }
            }
            if ($this->bException) {
                throw new Exception($message["description"]);
            }
            if ($this->bExit) {
                echo "ARRET DU SCRIPT";
                exit();
            }
        }
        
        /**
         * Log des erreur dans les logs d'Apache
         *
         * @access private
         * @param mixed $message Tableau avec message, description, referer
         * @return void
         */
        private function logError($message) {
            $date_time = date('Y-m-d H:i:s');
            $this->lastError = array();
            if ($message["message"]) {
                $this->lastError["message"] = $message["message"];
            }
            if ($message["description"]) {
                $this->lastError["description"] = $message["description"];
            }
            //$this->lastError["backtrace"] = debug_trace();
            if (is_array($this->lastError)) {
                Pelican_Log::error($message["message"]. ' => '.str_replace("\n"," ",$message["description"]), 'DATABASE');
            }
        }
        
        /**
         * Interception d'une erreur de base de données. Envoi d'un mail récapitulatif
         * au
         * destinataire et masquage de l'erreur pour les internautes
         *
         * @access private
         * @param mixed $message Tableau contenant les messages d'erreur (avec les
         * entrées "code" et "message")
         * @param string $msg Message d'erreur
         * @param string $mail (option) Mail du destinataire
         * @return void
         */
        private function mailError(&$message, &$msg, $mail = "") {
            global $_REQUEST;
            if (Pelican::$config["DATABASE_ALERT"]) {
                $mail = Pelican::$config["DATABASE_ALERT"];
                if (!Pelican::$config["SHOW_DEBUG"] && $mail && $message["code"] != "12535" && $message["code"] != "12154" && $_SERVER["REQUEST_URI"] != "/index.php?pid=5&tpl=113&zid=3") {
                    $headers = "From: " . Pelican::$config["ENVIRONNEMENT"] . " " . APP . " <noreply@" . APP_DOMAIN . ">\r\n";
                    $headers.= "MIME-Version:1.0\r\n";
                    $headers.= "Content-type:text/html;\r\n";
                    $messageMail.= "<B>REFERER</B> : " . $_SERVER["HTTP_REFERER"] . "<br /><br />";
                    $messageMail.= "<B>URL</B> : http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . "<br /><br />";
                    $messageMail.= "<B>MSG</B> : " . $msg . "<br /><br />";
                    $messageMail.= "<B>CODE</B> : " . $message["code"] . "<br /><br />";
                    $messageMail.= "<B>ERREUR</B> : " . $message["message"] . "<br /><br />";
                    $messageMail.= "<B>BIND</B> : " . var_export($this->param, true) . "";
                    if (Pelican::$config["TYPE_ENVIRONNEMENT"] != "prod") {
                        mail($mail, "Erreur Base de données", str_replace("\'", "'", $messageMail), $headers);
                    }
                    $message = "";
                    $msg = "Le gestionnaire du site a été informé de l'erreur qui s'est produite.<br />Le correctif sera mis en place rapidement.<br /><br />Merci de votre compréhension."; //et lui commoniquer l'URL suivante :<br />".$_SERVER["REQUEST_URI"];
                    
                }
            }
        }
        
        /**
         * Affichage d'un message type
         *
         * @access public
         * @param string $message1 Titre du message
         * @param string $message2 (option) Corps du message
         * @return string
         */
        public function message($message1, $message2 = "") {
            $this->errorMsg = array($message1, $message2);
            $return = "<FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FF0000\" SIZE=\"2\"><B>" . $message1 . "</B></FONT>";
            if ($message2) {
                $return.= "<br />" . $message2;
            }
            return $return;
        }
        
        /**
         * Permet de fixer à true/false l'affichage du temps d'exécution des requètes
         * SQL.
         *
         * @access public
         * @param bool $etat True/false
         * @return void
         */
        public function setDebugTime($etat) {
            $this->debugTime = $etat;
        }
        
        /**
         * Point de départ temporel de l'exécution d'une requête (initialise la
         * propriété $this->time_deb)
         *
         * @access private
         * @return void
         */
        private function initTimeQuery() {
            $mtime = microtime();
            $mtime = explode(" ", $mtime);
            $this->time_deb = $mtime[1] + $mtime[0];
        }
        
        /**
         * Retourne le temps d'exécution d'une requête (déterminé par la propriété
         * $this->time_deb)
         *
         * @access private
         * @param string $query Chaine SQL
         * @return void
         */
        private function displayTimeQuery($query) {
            $mtime = microtime();
            $mtime = explode(" ", $mtime);
            $this->time_fin = $mtime[1] + $mtime[0];
            $duree = $this->time_fin - $this->time_deb;
            echo $this->getHeader("Information sur une requ&egrave;te", "#9999CC");
            echo "<TR>
				<TD BGCOLOR=\"#CCCCFF\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>Requ&egrave;te</B></FONT></TD>
				<TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#000000\" SIZE=\"2\"><B>$query</B></FONT></TD>
				</TR>
				<TR>
				<TD BGCOLOR=\"#CCCCFF\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#FFFD3E\" SIZE=\"2\"><B>Temps d'ex&eacute;cution</B></FONT></TD>
				<TD BGCOLOR=\"#CCCCCC\" ALIGN=\"left\"><FONT FACE=\"Verdana,Helvetica,Arial\" COLOR=\"#000000\" SIZE=\"2\"><B>$duree sec</B></FONT></TD>
				</TR>";
            echo $this->getFooter();
        }
        
        /**
         * Retourne le temps d'exécution d'une requête (déterminé par la propriété
         * $this->time_deb)
         *
         * @access private
         * @param string $query Chaine SQL
         * @param array $param __DESC__
         * @param array $paramLob __DESC__
         * @return void
         */
        private function appendTimeQuery($query, &$param, &$paramLob) {
            $mtime = microtime();
            $mtime = explode(" ", $mtime);
            $this->time_fin = $mtime[1] + $mtime[0];
            $duree = $this->time_fin - $this->time_deb;
            $this->aQueriesDebug[] = array('query' => $query, 'param' => $param, 'paramLob' => $paramLob, 'affected_rows' => $this->affected_rows, 'time' => $duree);
        }
        
        /**
         * Affiche les requêtes exécutées
         *
         * @access public
         * @return void
         */
        public function displayQueriesDebug() {
            for ($i = 0;$i < count($this->aQueriesDebug);$i++) {
                print ('<table cellpadding="4" cellspacing="0" border="1">');
                print ('<tr>');
                print ('<td valign="top" style="background-color:4682b4;color:ffffff;font-weight:bold;">Query</td>');
                print ('<td style="background-color:cdcdcd;color:000000;">' . nl2br($this->aQueriesDebug[$i]['query']) . '</td>');
                print ('</tr>');
                if ($this->aQueriesDebug[$i]['param']) {
                    print ('<tr>');
                    print ('<tr>');
                    print ('<td valign="top" style="background-color:4682b4;color:ffffff;font-weight:bold;">Parameters</td>');
                    print ('<td style="background-color:cdcdcd;color:000000;">' . debug($this->aQueriesDebug[$i]['param']) . '</td>');
                    print ('</tr>');
                }
                if ($this->aQueriesDebug[$i]['paramLob']) {
                    print ('<tr>');
                    print ('<tr>');
                    print ('<td valign="top" style="background-color:4682b4;color:ffffff;font-weight:bold;">Lob Parameters</td>');
                    print ('<td style="background-color:cdcdcd;color:000000;">' . debug($this->aQueriesDebug[$i]['paramLob']) . '</td>');
                    print ('</tr>');
                }
                print ('<tr>');
                print ('<td valign="top" style="background-color:4682b4;color:ffffff;font-weight:bold;">Affected&nbsp;rows</td>');
                print ('<td style="background-color:cdcdcd;color:000000;">' . nl2br($this->aQueriesDebug[$i]['affected_rows']) . '</td>');
                print ('</tr>');
                print ('<tr>');
                print ('<td valign="top" style="background-color:4682b4;color:ffffff;font-weight:bold;">Execution&nbsp;time</td>');
                print ('<td style="background-color:cdcdcd;color:000000;">' . round(1000 * $this->aQueriesDebug[$i]['time']) . '</td>');
                print ('</tr>');
                print ('</table><br />');
            }
        }
        
        /**
         * Génération d'une requête select avec prise en compte des champs LOB et date
         * (formattage en chaine de texte)
         *
         * @access public
         * @param string $table Nom de la table
         * @param string $where (option) Clause Where
         * @param string $order (option) Clause Order
         * @param mixed $aBind (option) Tableau des valeurs bindées
         * @param mixed $returnValues (option) Détermine si l'on execute la requete (on
         * retourne le résultat) ou seulement la requête générée
         * @param bool $toUpper (option) Mise en majuscule des noms de champ
         * @param bool $separateLob (option) Générer une requête séparée pour les lob
         * (pour éviter des consommations mémoires trop importantes)
         * @param string $useFilter (option) __DESC__
         * @return mixed
         */
        public function selectQuery($table, $where = "", $order = "", $aBind = array(), $returnValues = true, $toUpper = true, $separateLob = false, $useFilter = "") {
            if ($useFilter) {
                $aFieldFilter = $useFilter;
                if (!is_array($aFieldFilter)) {
                    $aFieldFilter = array($aFieldFilter);
                }
            }
            $aFields = $this->getDbInfo("fields", $table);
            $FIELD = array();
            foreach($aFields as $field) {
                $use = false;
                if ($aFieldFilter) {
                    if (in_array($field['field'], $aFieldFilter)) {
                        $use = true;
                    }
                } else {
                    $use = true;
                }
                if ($use) {
                    if ($toUpper) {
                        $field["field"] = strToUpper($field["field"]);
                    }
                    if ($this->isDate($field["type"])) {
                        $FIELD["SELECT"][] = $this->dateSqlToString($field["field"], true) . " as " . $field["field"];
                    } elseif ($this->isLob($field["type"])) {
                        if ($separateLob) {
                            $FIELD["LOB"][] = $field["field"];
                        } else {
                            $FIELD["SELECT"][] = $field["field"];
                        }
                    } else {
                        $FIELD["SELECT"][] = $field["field"];
                    }
                    /*
                    switch ($field["type"]) {
                    case "long":
                    case "clob": {
                    if ($separateLob) {
                    $FIELD["LOB"][] = $field["field"];
                    } else {
                    $FIELD["SELECT"][] = $field["field"];
                    }
                    break;
                    }
                    case "date":
                    case "datetime": {
                    $FIELD["SELECT"][] = $this->dateSqlToString($field["field"], true) . " " . $field["field"];
                    break;
                    }
                    default: {
                    $FIELD["SELECT"][] = $field["field"];
                    }
                    }*/
                }
            }
            if ($FIELD["SELECT"]) {
                $SQL = "select " . implode(",", $FIELD["SELECT"]) . " from " . $table . ($where ? " WHERE " . str_replace(";", "", str_replace("\\'", "'", $where)) . " " : "") . ($order ? " ORDER BY " . str_replace(";", "", $order) . " " : "");
            }
            if ($separateLob) {
                if ($FIELD["LOB"]) {
                    $SQL_LOB = "select " . implode(",", $FIELD["LOB"]) . " from " . $table;
                }
            }
            $return = "";
            if ($SQL) {
                if ($returnValues) {
                    if (!$separateLob) {
                        $return = $this->queryTab($SQL, $aBind);
                    } else {
                        $return['sql'] = $this->queryTab($SQL, $aBind);
                        $return['sqlLob'] = $this->queryTab($SQL_LOB, $aBind);
                    }
                } else {
                    if (!$separateLob) {
                        $return = $SQL;
                    } else {
                        $return['sql'] = $SQL;
                        $return['sqlLob'] = $SQL;
                    }
                }
            }
            return $return;
        }
        
        /**
         * Retourne un tableau avec
         * "field","type","null","default","key","extra","increment","sequence"
         *
         * @access public
         * @param string $table Nom de la table
         * @return mixed
         */
        public function describeTable($table) {
            if ($this->useCacheDescribe) {
                $return = Pelican_Cache::fetch("Database/Describe/Table", array("fields", $table, $this->host, $this->port));
            } else {
                $return = $this->getDbInfo("fields", $table);
            }
            return $return;
        }
        
        /**
         * Insertion d'un enregistrement dans une table
         *
         * @access public
         * @param string $table Nom de la table
         * @param mixed $aStopList (option) Tableau désignant les champs à ignorer pour
         * la description de la table
         * @param mixed $aUsedList (option) Tableau désignant les champs à utiliser pour
         * la description de la table (la stop list ayant priorité)
         * @return void
         */
        public function insertQuery($table, $aStopList = array(), $aUsedList = array()) {
            $this->updateTable(Pelican_Db::DATABASE_INSERT, $table, "", $aStopList, $aUsedList);
        }
        
        /**
         * Mise à jour d'un enregistrement d'une table
         *
         * @access public
         * @param string $table Nom de la table
         * @param mixed $aStopList (option) Tableau désignant les champs à ignorer pour
         * la description de la table
         * @param mixed $aUsedList (option) Tableau désignant les champs à utiliser pour
         * la description de la table (la stop list ayant priorité)
         * @return void
         */
        public function updateQuery($table, $aStopList = array(), $aUsedList = array()) {
            $this->updateTable(Pelican_Db::DATABASE_UPDATE, $table, "", $aStopList, $aUsedList);
        }
        
        /**
         * Suppresion d'un enregistrement d'une table
         *
         * @access public
         * @param string $table Nom de la table
         * @param mixed $aStopList (option) Tableau désignant les champs à ignorer pour
         * la description de la table
         * @param mixed $aUsedList (option) Tableau désignant les champs à utiliser pour
         * la description de la table (la stop list ayant priorité)
         * @return void
         */
        public function deleteQuery($table, $aStopList = array(), $aUsedList = array()) {
            $this->updateTable(Pelican_Db::DATABASE_DELETE, $table, "", $aStopList, $aUsedList);
        }
        
        /**
         * Mise à jour d'une associative
         *
         * @access public
         * @param string $table Nom de la table associative
         * @param string $assoc Champ "pivot" (clé de la table de référence associée)
         * @param mixed $aStopList (option) Tableau désignant les champs à ignorer pour
         * la description de la table
         * @param mixed $aUsedList (option) Tableau désignant les champs à utiliser pour
         * la description de la table (la stop list ayant priorité)
         * @return void
         */
        public function assocQuery($table, $assoc, $aStopList = array(), $aUsedList = array()) {
            $this->updateTable(Pelican_Db::DATABASE_INSERT, $table, $assoc, $aStopList, $aUsedList);
        }
        
        /**
         * Requête de remplacement : INSERT si le select sur la table répondant à la
         * clause Where ne remonte rien, sinon UPDATE
         *
         * @access public
         * @param string $table Nom de la table
         * @param string $key Clause Where
         * @param mixed $aStopList (option) "Stop liste" des champs à ne pas générer
         * dans la requête
         * @param mixed $aUsedList (option) "Used liste" des champs à utiliser dans la
         * requête
         * @param mixed $aBind (option) Tableau des valeurs bindées
         * @return string
         */
        public function replaceQuery($table, $key, $aStopList = array(), $aUsedList = array(), $aBind = array()) {
            $count = $this->queryItem("select count(*)  from " . $table . " where " . $key, $aBind);
            if ($count) {
                $action = "update";
                $this->updateQuery($table, $aStopList, $aUsedList);
            } else {
                $action = "insert";
                $this->insertQuery($table, $aStopList, $aUsedList);
            }
            return $action;
        }
        
        /**
         * Typage d'un evaleur bindée en chaine texte (inutile pour ORACLE qui gère ça
         * par ses propres moyens)
         *
         * @access public
         * @param string $value __DESC__
         * @return string
         */
        public function strToBind($value) {
            if ($this->allowBind) {
                $return = str_replace("\\'", "'", str_replace("\\" . "\"", "\"", $value));
                $return = str_replace("\\\\", "\\", $return);
            } else {
                // Pour éviter le paramétrage des gpc
                $value = stripslashes($value);
                $return = "'" . $this->stringToSql($value) . "'";
            }
            return $return;
        }
        
        /**
         * Typage d'un evaleur bindée en chaine numérique (inutile pour ORACLE qui gère
         * ça par ses propres moyens)
         *
         * @access public
         * @param string $value __DESC__
         * @return string
         */
    public function decimalToBind ($value)
    {
        /* // PLA20130201 : déplacement dans la classe Oracle.php car ne concerne que Oracle
		if (Pelican::$config["DATABASE_TYPE"] == "oracle" && isset($value) && $value != 0) { // PLA20130131 : on ne trasnforme pas 0 sinon il sera casté en chaîne avec le str_replace
                if (substr_count(getenv('NLS_LANG'), 'FRANCE')) {
                    $value = str_replace(".", ",", $value);
                } else {
                    $value = str_replace(",", ".", $value);
                }
            }
		*/
            return $value;
        }
        
        /**
         * Ajout d'une valeur au tableau des variables BIND
         *
         * @access public
         * @param mixed $bind Tableau de correspondance entre les variables BIND et les
         * valeurs (IN OUT)
         * @param string $level Indice de la valeur dans le tableau
         * @param mixed $value Valeur à ajouter au tableau
         * @param string $type (option) Type de la valeur (pour effectuer un formattage de
         * la valeur si la base de données ne gère pas les variables BIND comme Mysql,
         * sinon c'est la base de données qui se charge du formattage)
         * @param bool $hour (option) Formttage de l'heure à inclure ou non si le type
         * est "date"
         * @param string $default (option) __DESC__
         * @param bool $bNotFormat (option) __DESC__
         * @return mixed
         */
        public function setBindValue(&$bind, $level, $value, $type = "", $hour = true, $default = "", $bNotFormat = false) {
            global $FormatBind;
       
            if ($this->isNum($type)) {
                if (isset($value) && ($value === 0 || $value === "0") ) {
                    // PLA20130131 : sinon plante car la cha? "0" n'est pas num豩que (plante si une valeur par d襡ut pour le champ)
				$value = (integer) $value; 
			} elseif ($value !== "") { 
                $value = $this->decimalToBind($value);
            }
        }
		
            $bindIndex = ":" . $level;
            if ($this->allowBind) {
                $default = str_replace("NULL", "", $default);
            }
        if (isset($default) && $default !== "") {
            if ($default === "0" && $this->isNum($type)) { 
				$default = (integer) $default; 
			}
            $value = ($value || $value == "0" ? $value : $default);
            }
            if ($this->allowBind) {
                $value = $this->strToBind($value);
            }
            if (($type != "" && !$this->allowBind)) {
                $bind[$bindIndex] = ($bNotFormat ? $value : $this->formatField($value, $type)); // ATTENTION ou double bind
            } else {
                if ($value == ":DATE_COURANTE") {
                    $bindIndex = str_replace(":DATE_COURANTE", $this->getNow(), $value);
                } else {
                    $bind[$bindIndex] = $value;
                }
            }
            if ($this->allowBind) {
                if ($this->isDate($type) && $value != ":DATE_COURANTE") {
                    $FormatBind[] = $bindIndex;
                    if ($value) {
                        $bindIndex = str_replace("'BIND'", $bindIndex, $this->dateStringToSql("BIND", $hour));
                    } else {
                        $bindIndex = "NULL";
                    }
                }
            }
            return $bindIndex;
        }
        
        /**
         * Envoi des variables BIND
         *
         * @access public
         * @param string $query Requête SQL avec les variable BIND
         * @param mixed $param Tableau de correspondance entre les variables BIND et les
         * valeurs
         * @return string
         */
        public function putBind($query, $param) {
            $strSQL = strtr($query, $param);
            return $strSQL;
        }
        
        /**
         * Méthode permettant d'automatiser les traitements séquentiels de base de
         * données
         *
         * 3 types de paramètres sont utilisés dans le tableau :
         * - "TABLE" => mise à jour de la table "TABLE" suivant l'action
         * - array("TABLE","CHAMP") => mise à jour par ANNULE/REMPLACE de la table
         * "TABLE" en utilisant toutes les valeurs du champ "CHAMP"
         * - array("","CHEMIN") => utilisation d'un fichier externe de traitement (par
         * exemple associés aux createMulti de la classe Form)
         *
         * La séquence est exécutée dans l'ordre du tableau pour les insertions et les
         * modifications et dans l'ordre inverse pour les suppressions
         *
         * @access public
         * @param string $action Action de la commande (actionInsert, actionUpdate,
         * actionDelete)
         * @param mixed $processList Tableau de séquences de mises à jour
         * @param mixed $aTableStopList (option) Tableau désignant les champs à ignorer
         * (par table) pour la description de la table
         * @param mixed $aTableUsedList (option) Tableau désignant les champs à utiliser
         * (par table) pour la description de la table (la stop list ayant priorité)
         * @return void
         */
        public function updateForm($action, $processList, $aTableStopList = array(), $aTableUsedList = array()) {
            if ($processList) {
                if ($action == Pelican_Db::DATABASE_DELETE) {
                    $processList = array_reverse($processList);
                }
                for ($i = 0;$i < count($processList);$i++) {
                    if (is_array($processList[$i])) {
                        if ($processList[$i][0] == 'method') {
                            $call = explode('::', $processList[$i][1]);
                            call_user_func(array($call[0], $call[1]));
                        } elseif ($processList[$i][0]) {
                            $this->updateTable($action, $processList[$i][0], $processList[$i][1], (isset($aTableStopList[$processList[$i][0]]) ? $aTableStopList[$processList[$i][0]] : ""), (isset($aTableUsedList[$processList[$i][0]]) ? $aTableUsedList[$processList[$i][0]] : ""));
                        } else {
                            if (!$oConnection) {
                                $oConnection = $this;
                            }
                            include ($processList[$i][1]);
                        }
                    } else {
                        $this->updateTable($action, $processList[$i], "", (isset($aTableStopList[$processList[$i]]) ? $aTableStopList[$processList[$i]] : ""), (isset($aTableUsedList[$processList[$i]]) ? $aTableUsedList[$processList[$i]] : ""));
                    }
                }
            }
        }
        
        /**
         * Insert, Update ou Delete des données dans une table ou une associative.
         *
         * @access public
         * @param string $form_action Action : INS, UPD ou DEL
         * @param string $table Nom de la table à mettre à jour
         * @param string $assoc (option) Nom du champ pivot pour la mise à jour d'une
         * associative : "" par défaut
         * @param mixed $aStopList (option) Tableau désignant les champs à ignorer pour
         * la description de la table
         * @param mixed $aUsedList (option) Tableau désignant les champs à utiliser pour
         * la description de la table (la stop list ayant priorité)
         * @param string $getSQL (option) Retourne la requête générée sans l'exécuter
         *
         * @return void
         */
        public function updateTable($form_action, $table, $assoc = "", $aStopList = array(), $aUsedList = array(), $getSQL = false) {
            global $_REQUEST, $aBind, $aLob;
            $aLob = array();
            $aBind = array();
            $result = array();
            // Si le POST ou le GET n'a pas été récupéré
            if (!Pelican_Db::$values) Pelican_Db::$values = $_REQUEST;
            
            /** On vérifie que la table n'est pas rejetée : propriétés tableStopList */
            if (strtolower($table) != strtolower($this->tableStopList)) {
                $desc = $this->describeTable($table);
                if ($desc) {
                    foreach($desc as $value) {
                        
                        /** gestion de la stop list (=> A CONFIRMER : le champ ne doit pas être une clé primaire) */
                        if ($aStopList) {
                            if (in_array($value["field"], $aStopList)) {
                                // && !$value["key"]) {
                                $value = "";
                            }
                        }
                        
                        /** gestion de la used list */
                        if ($aUsedList && $value) {
                            if (!in_array($value["field"], $aUsedList)) {
                                //       if (!$value["key"]) {
                                $value = "";
                                //       }
                                
                            }
                        }
                        if ($value) {
                            $result[] = $value;
                        }
                    }
                }
                if ($result) {
                    $j = - 1;
                    foreach($result as $value) {
                        $j++;
                        $fieldSet[$j]["field"] = $value["field"];
                        $fieldSet[$j]["key"] = $value["key"];
                        $fieldSet[$j]["increment"] = $value["increment"];
                        $fieldSet[$j]["type"] = $value["type"];
                        $fieldSet[$j]["sequence"] = $value["sequence"];
                        $fieldSet[$j]["default"] = $value["default"];
                        // pour les cas ou on a une séquence, elle est initialisée
                        if ($value["key"] && $value["sequence"] && !$value["increment"] && Pelican_Db::$values[$value["field"]] == $this->idInsert && $form_action == Pelican_Db::DATABASE_INSERT) {
                            Pelican_Db::$values[$value["field"]] = $this->getNextId($table);
                        }
                        if ($assoc != "" && $value["field"] == $assoc) {
                            if (!isset(Pelican_Db::$values[$assoc])) {
                                Pelican_Db::$values[$assoc] = "";
                            }
                            if (!is_array(Pelican_Db::$values[$assoc])) {
                                Pelican_Db::$values[$assoc] = array(1 => Pelican_Db::$values[$assoc]);
                            }
                            foreach(Pelican_Db::$values[$assoc] as $fieldAssoc) {
                                if ($fieldAssoc) {
                                    $fieldSet[$j]["value"][] = $this->formatField($fieldAssoc, $value["type"]);
                                    $fieldSet[$j]["bind"] = $this->setBindValue($aBind, $j, $fieldAssoc, $value["type"], true, $value["default"]);
                                    if ($this->isLob($fieldSet[$j]["type"])) {
                                        $aLob[$fieldSet[$j]["bind"]] = $fieldAssoc;
                                    }
                                }
                            }
                        } else {
                            if (!isset(Pelican_Db::$values[$value["field"]])) {
                                Pelican_Db::$values[$value["field"]] = "";
                            }
                            $fieldSet[$j]["value"][0] = $this->formatField(Pelican_Db::$values[$value["field"]], $value["type"]);
                            $fieldSet[$j]["bind"] = $this->setBindValue($aBind, $j, Pelican_Db::$values[$value["field"]], $value["type"], true, $value["default"]);
                            if ($this->isLob($fieldSet[$j]["type"])) {
                                $aLob[$fieldSet[$j]["bind"]] = $fieldAssoc;
                            }
                        }
                    }
                    if ($assoc != "" && $form_action == Pelican_Db::DATABASE_UPDATE) {
                        $strSQL = $this->updateRecord(Pelican_Db::DATABASE_DELETE, $table, $fieldSet, $assoc, $getSQL);
                        $action = Pelican_Db::DATABASE_INSERT;
                    } else {
                        $action = $form_action;
                    }
                    $return = $this->updateRecord($action, $table, $fieldSet, $assoc, $getSQL);
                    return $return;
                }
            } else { //debug($this->tableStopList,"table rejetée");
                
            }
        }
        
        /**
         * Execute les chaines SQL passée en paramètre
         *
         * @access private
         * @param string $action Action : INS, UPD ou DEL
         * @param string $table Nom de la table à mettre à jour
         * @param string $fieldSet Tableau de définition des champs (nom, format, valeur
         * etc...)
         * @param string $assoc Nom du champ pivot pour la mise à jour d'une associative
         * @param string $getSQL (option) Retourne la requête générée sans l'exécuter
         *
         * @return void
         */
        private function updateRecord($action, $table, $fieldSet, $assoc, $getSQL = false) {
            global $aBind, $aLob;
            $usedBind = array();
            $bind = array();
            $strSQL = "";
            $strSQL1 = "";
            $strSQL2 = "";
            $strSQLBind = "";
            $strSQL1Bind = "";
            $strSQL2Bind = "";
            $autoIncrement = "";
            switch ($action) {
                case Pelican_Db::DATABASE_INSERT: {
                            $strSQL = "INSERT INTO " . $table . " (";
                            $strSQLBind = $strSQL;
                            $usedField = 0;
                            for ($j = 0;$j < count($fieldSet);$j++) {
                                if (!$fieldSet[$j]["increment"] || ($fieldSet[$j]["increment"] && $fieldSet[$j]["value"][0] != $this->idInsert)) {
                                    $usedField++;
                                    if (isset($fieldSet[$j]["bind"])) {
                                        $usedBind[] = $fieldSet[$j]["bind"];
                                        $strSQL1.= ($strSQL1 == "" ? "" : ", ") . $fieldSet[$j]["field"];
                                        if ($assoc != "" && $fieldSet[$j]["field"] == $assoc) {
                                            $strSQL2.= ($strSQL2 == "" ? "" : ", ") . $fieldSet[$j]["bind"];
                                        } else {
                                            $strSQL2.= ($strSQL2 == "" ? "" : ", ") . $fieldSet[$j]["value"][0];
                                        }
                                        $strSQL2Bind.= ($strSQL2Bind == "" ? "" : ", ") . $fieldSet[$j]["bind"];
                                    }
                                } else {
                                    $autoIncrement = $fieldSet[$j]["field"];
                                    if (!isset(Pelican_Db::$values[$autoIncrement])) {
                                        Pelican_Db::$values[$autoIncrement] = "";
                                    }
                                }
                            }
                            $strSQLBind.= $strSQL1 . ") VALUES (" . $strSQL2Bind . ")";
                            $strSQL.= $strSQL1 . ") VALUES (" . $strSQL2 . ")";
                        break;
                    }
                case Pelican_Db::DATABASE_UPDATE: {
                        $strSQL = "UPDATE " . $table . " SET ";
                        $strSQLBind = $strSQL;
                        $usedField = 0;
                        for ($j = 0;$j < count($fieldSet);$j++) {
                            $usedBind[] = $fieldSet[$j]["bind"];
                            if ($fieldSet[$j]["key"]) {
                                $strSQL2.= ($strSQL2 == "" ? " WHERE " : " AND ") . $fieldSet[$j]["field"] . "=" . $fieldSet[$j]["value"][0];
                                $strSQL2Bind.= ($strSQL2Bind == "" ? " WHERE " : " AND ") . $fieldSet[$j]["field"] . "=" . $fieldSet[$j]["bind"];
                            } else {
                                $usedField++;
                                $strSQL1.= ($strSQL1 == "" ? "" : ", ") . $fieldSet[$j]["field"] . "=" . $fieldSet[$j]["value"][0];
                                $strSQL1Bind.= ($strSQL1Bind == "" ? "" : ", ") . $fieldSet[$j]["field"] . "=" . $fieldSet[$j]["bind"];
                            }
                        }
                        $strSQL.= $strSQL1 . $strSQL2;
                        $strSQLBind.= $strSQL1Bind . $strSQL2Bind;
                        if (!$usedField) {
                            $strSQL = "";
                            $strSQLBind = "";
                        }
                        break;
                    }
                case Pelican_Db::DATABASE_DELETE: {
                        $strSQL = "DELETE FROM " . $table;
                        $strSQLBind = $strSQL;
                        for ($j = 0;$j < count($fieldSet);$j++) {
                            if ($fieldSet[$j]["key"] && $fieldSet[$j]["field"] != $assoc) {
                                $usedBind[] = $fieldSet[$j]["bind"];
                                $strSQL2.= ($strSQL2 == "" ? " WHERE " : " AND ") . $fieldSet[$j]["field"] . "=" . $fieldSet[$j]["value"][0];
                                $strSQL2Bind.= ($strSQL2Bind == "" ? " WHERE " : " AND ") . $fieldSet[$j]["field"] . "=" . $fieldSet[$j]["bind"];
                            }
                        }
                        $strSQL.= $strSQL2;
                        $strSQLBind.= $strSQL2Bind;
                        break;
                    }
                }
                if ($usedBind) {
                    foreach($usedBind as $key) {
                        $bind[$key] = $aBind[$key];
                    }
                }
                $bind = $aBind;
                if ($strSQL != "") {
                    if (!$assoc) {
                        if (!$getSQL) {
                            $this->query($strSQLBind, $bind, $aLob);
                        }
                        $aSQL[] = strtr($strSQLBind, $bind);
                        if ($autoIncrement) {
                            if (Pelican_Db::$values[$autoIncrement] == $this->idInsert || !Pelican_Db::$values[$autoIncrement]) {
                                $this->getLastOid($strSQL);
                                if (is_array($this->lastInsertedId)) {
                                    $increment = $this->lastInsertedId[$autoIncrement];
                                } else {
                                    $increment = $this->lastInsertedId;
                                }
                                Pelican_Db::$values[$autoIncrement] = $increment;
                                if (!isset(Pelican_Db::$values[$autoIncrement])) {
                                    Pelican_Db::$values[$autoIncrement] = "";
                                }
                            }
                        }
                    } else {
                        for ($j = 0;$j < count($fieldSet);$j++) {
                            if ($fieldSet[$j]["field"] == $assoc) {
                                if (!isset($fieldSet[$j]["value"])) {
                                    $fieldSet[$j]["value"] = "";
                                }
                                if (!is_array($fieldSet[$j]["value"])) {
                                    $fieldSet[$j]["value"] = array(1 => $fieldSet[$j]["value"]);
                                }
                                if ($action == Pelican_Db::DATABASE_DELETE) {
                                    if (!$getSQL) {
                                        $this->query($strSQLBind, $bind, $aLob);
                                    }
                                    $aSQL[] = strtr($strSQLBind, $bind);
                                } else {
                                    foreach($fieldSet[$j]["value"] as $value_assoc) {
                                        $bind2 = $bind;
                                        $this->setBindValue($bind2, $j, $value_assoc, $fieldSet[$j]["type"], true, $fieldSet[$j]["default"], true);
                                        if ($value_assoc != "") {
                                            if (!$getSQL) {
                                                $this->query($strSQLBind, $bind2, $aLob);
                                            }
                                            $aSQL[] = strtr($strSQLBind, $bind2);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if (!$getSQL) {
                    return true;
                } else {
                    return $aSQL;
                }
            }
            
            /**
             * Retourne la valeur d'un champ
             *
             * @access private
             * @param string $field Le nom du champ
             * @return string
             */
            private function getValue($field) {
                $value = str_replace("\\", "", $this->data[$field]);
                return $value;
            }
            
            /**
             * Vérifie si le type de champ défini est un type numérique
             *
             * @access public
             * @param string $type Type de champ
             * @return bool
             */
            public function isNum($type) {
                $primaryType = self::primaryType($type);
                if ($primaryType) {
                    $return = in_array($primaryType, self::$NUMERIC_TYPES);
                } else {
                    $type = " " . strToLower($type);
                    $return = false;
                    if (strpos($type, "float") > 0 || strpos($type, "serial") > 0 || strpos($type, "counter") > 0 || strpos($type, "int") > 0 || strpos($type, "num") > 0 || strpos($type, "double") > 0 || strpos($type, "decimal") > 0 || strpos($type, "real") > 0 || strpos($type, "timestamp") > 0 || strpos($type, "number") > 0) {
                        $return = true;
                    }
                }
                return $return;
            }
            
            /**
             * Vérifie si le type de champ défini est un type date
             *
             * @access public
             * @param string $type Type de champ
             * @return bool
             */
            public function isDate($type) {
                $primaryType = self::primaryType($type);
                if ($primaryType) {
                    $return = in_array($primaryType, self::$DATE_TYPES);
                } else {
                    $type = " " . strToLower($type);
                    $return = false;
                    if (strpos($type, "date") > 0 || strpos($type, "interval") > 0 || strpos($type, "datetime") > 0 || strpos($type, "time") > 0) {
                        $return = true;
                    }
                }
                return $return;
            }
            
            /**
             * Vérifie si le type de champ défini est un auto incrément
             *
             * @access public
             * @param string $type Type de champ
             * @return bool
             */
            public function isIdentity($type) {
                $primaryType = self::primaryType($type);
                if ($primaryType) {
                    $return = in_array($primaryType, self::$IDENTITY_TYPES);
                } else {
                    $type = " " . strToLower($type);
                    $return = false;
                    if (strpos($type, "counter") > 0 || strpos($type, "serial") > 0 || strpos($type, "identity") > 0) {
                        $return = true;
                    }
                }
                return $return;
            }
            
            /**
             * Vérifie si le type de champ défini est un type LOB
             *
             * Le type, issu de la base de donnée, peux varier suivant la base de données
             * cible :
             * ->LOB
             * - clob
             * - blob
             *
             * @access private
             * @param string $type Type de champ
             * @return bool
             */
            public function isLob($type) {
                $primaryType = self::primaryType($type);
                if ($primaryType) {
                    $return = in_array($primaryType, self::$LOB_TYPES);
                } else {
                    $type = " " . strToLower($type);
                    $return = false;
                    if (strpos($type, "clob") > 0 || strpos($type, "blob") > 0) {
                        $return = true;
                    }
                }
                return $return;
            }
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $type __DESC__
             * @return __TYPE__
             */
            public function primaryType($type) {
                $temp = explode('(', $type);
                $return = self::$fieldTypes[strtoupper($temp[0]) ];
                return $return;
            }
            
            /**
             * Formattage de la valeur d'un champ en fonction du type passé en paramètre
             *
             * Le type, issu de la base de donnée, peux varier suivant la base de données
             * cible :
             * ->Numérique
             * - timestamp
             * - serial
             * - real
             * - num
             * - int
             * - float
             * - double
             * - decimal
             * - counter
             * ->Date
             * - date
             * - interval
             * ->Auto-Incrément
             * - counter
             * - serial (PostGress)
             * - identity (SQL Server)
             *
             * @access private
             * @param string $value Valeur à formatter
             * @param string $type Type de champ associé à la valeur
             * @return string
             */
            private function formatField($value, $type) {
                if ($this->isNum($type)) {
                    if (empty($value)) {
                if ($value == "0") {
                        $return = "0";
                        $return = (integer) $value ; // PLA20130131 : cast du 0 en entier
                        } else {
                            $return = "null";
                        }
                    } else {
                        $return = str_replace(",", ".", $value);
                    }
                } elseif ($this->isDate($type)) {
                    if ($value == ":DATE_COURANTE") {
                        $return = str_replace(":DATE_COURANTE", $this->getNow(), $value);
                    } else {
                        if (!is_array($value)) {
                            if (empty($value) || $value == "" || $value == "NULL") {
                                $return = "null";
                            } else {
                                $return = $this->dateStringToSql($value);
                            }
                        }
                    }
                } else {
                    if (!is_array($value)) {
                        if (strtolower($value) != "null") {
                            $return = $this->strToBind($value);
                        } else {
                            $return = $value;
                        }
                    } else {
                        $return = $value;
                    }
                }
                return $return;
            }
            
            /**
             * Mise à jour de la valeur d'un champ ordre au sein d'une table par
             * incrémentation ou décrémentation
             *
             * (en redéfinissant en même temps tous les ordre de la table pour éviter des
             * discontinuités)
             *
             * @access public
             * @param string $StrNomTable Table à mettre à jour
             * @param string $StrChampOrdre Champ contenant l'information de classement
             * @param string $StrChampId Champ identifiant (la valeur de ce champ pour chaque
             * ligne du tableau est utilisée en paramètre de la fonction de mise à jour de
             * l'ordre)
             * @param string $id Valeur du cahmp identifiant
             * @param string $ordre (option) (optionnel) N° d'ordre courant de
             * l'enregistrement
             * @param string $strSens (option) Sens de "déplacement" : incrémenter l'ordre
             * (1) ou décrémenter l'ordre (-1)
             * @param string $strChampPivot (option) Permet de limiter l'ordre sur les items
             * répondant à un champ pivot
             * @param string $strChampPivotIsNeeded (option) Si true, n'exécute
             * l'ordonnancement que si le champ pivot est présent
             * @param string $strComplement (option) Complément de requête (clause where)
             * @return void
             */
            public function updateOrder($StrNomTable, $StrChampOrdre, $StrChampId, $id, $ordre = "", $strSens = "", $strChampPivot = "", $strChampPivotIsNeeded = false, $strComplement = "") {
                $strComplementWhere = "";
                $strComplementAnd = "";
                if ($strChampPivotIsNeeded and $strChampPivot == "") {
                    return false;
                } else {
                    //Rajouter la valeur de l'ordre par defaut
                    //Dans le cas de automatique il faut calculer la valeur de l'ordre, car la paramètre $ordre sera vide
                    if ($strChampPivot != "") {
                        $tmp = $this->queryRow("SELECT " . $strChampPivot . " FROM " . $StrNomTable . " WHERE " . $StrChampId . " = " . $id);
                        if ($tmp[$strChampPivot]) {
                            $strComplementWhere = " WHERE " . $strChampPivot . " = " . $tmp[$strChampPivot];
                            $strComplementAnd = " AND " . $strChampPivot . " = " . $tmp[$strChampPivot];
                        } elseif ($strChampPivotIsNeeded) {
                            $strComplementWhere = " WHERE " . $strChampPivot . " IS NULL";
                            $strComplementAnd = " AND " . $strChampPivot . " IS NULL";
                        } else {
                            return false;
                        }
                    }
                    if ($strComplement) {
                        $strComplementWhere.= ($strComplementWhere ? " AND " : " WHERE ") . $strComplement;
                        $strComplementAnd.= " AND " . $strComplement;
                    }
                    //Determiner la valeur  de l'ordre de l'element et rajouter 1
                    $ordre_max = $this->queryItem("SELECT count(DISTINCT " . $StrChampId . ") FROM " . $StrNomTable . $strComplementWhere);
                    $strSql = "SELECT " . $StrChampId . ", min(" . $StrChampOrdre . ") as MIN_CHAMP FROM " . $StrNomTable . $strComplementWhere . " GROUP BY " . $StrChampId . " ORDER BY MIN_CHAMP, " . $StrChampId;
                    $arrResult = $this->queryTab($strSql);
                    $counter = 1;
                    //Réintialisation des ordre dans la table
                    foreach($arrResult as $ligne) {
                        $strSql = "UPDATE " . $StrNomTable . " SET " . $StrChampOrdre . "=" . $counter . " WHERE " . $StrChampId . "=" . $ligne[$StrChampId] . " " . $strComplementAnd;
                        //récupération de la valeur de l'ordre initial
                        if ($ligne[$StrChampId] == $id) {
                            $i_ordreInitial = $counter;
                        }
                        $this->query($strSql);
                        $counter++;
                    }
                    if ($strSens == "1") {
                        if ($i_ordreInitial < $ordre_max) {
                            $ordre = ((int)$i_ordreInitial) + 1;
                        }
                    } elseif ($strSens == "-1") {
                        if ($i_ordreInitial > 1) {
                            $ordre = ((int)$i_ordreInitial) - 1;
                        }
                    }
                    if ($ordre) {
                        $strSql = "UPDATE " . $StrNomTable . " SET " . $StrChampOrdre . "=-1 WHERE " . $StrChampOrdre . " = " . $ordre . $strComplementAnd;
                        $this->query($strSql);
                        $strSql = "UPDATE " . $StrNomTable . " SET " . $StrChampOrdre . "=" . $ordre . " WHERE " . $StrChampOrdre . " = " . $i_ordreInitial . $strComplementAnd;
                        $this->query($strSql);
                        $strSql = "UPDATE " . $StrNomTable . " SET " . $StrChampOrdre . "=" . $i_ordreInitial . " WHERE " . $StrChampOrdre . " = -1" . $strComplementAnd;
                        $this->query($strSql);
                    }
                    return ($ordre ? true : false);
                }
            }
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $table __DESC__
             * @param __TYPE__ $cascadeDelete __DESC__
             * @return __TYPE__
             */
            public function cascadeDelete($table, $cascadeDelete) {
                $aBind[':ID'] = Pelican_Db::$values[strtoupper($table) . '_ID'];
                if (is_array($cascadeDelete)) {
                    foreach($cascadeDelete as $child) {
                        if (is_array($child)) {
                            $sql = 'delete from ' . $child[0] . ' where ' . $child[1] . '_id in (select ' . $child[1] . '_id from #pref#_' . $child[1] . ' where ' . $table . '_id = :ID)';
                        } else {
                            $sql = 'delete from ' . $child . ' where ' . $table . '_id = :ID';
                        }
                        $this->query($sql, $aBind);
                    }
                }
            }
            
            /**
             * Réinitialisation des Ordres des enregistrements d'une table
             *
             * @access private
             * @param string $StrNomTable Nom de la table
             * @param string $StrChampOrdre Nom du champ d'ordre
             * @param string $StrChampId Nom du champ identifiant
             * @return void
             */
            private function initOrder($StrNomTable, $StrChampOrdre, $StrChampId) {
                $strSql = "SELECT " . $StrChampId . " from " . $StrNomTable . " ORDER BY " . $StrChampOrdre . ", " . $StrChampId;
                $arrResult = $this->queryTab($strSql);
                $counter = 1;
                //Réintialisation des ordre dans la table
                foreach($arrResult as $ligne) {
                    //while ($ligne = each($arrResult)) {
                    $strSql = "UPDATE " . $StrNomTable . " SET " . $StrChampOrdre . "=" . $counter . " WHERE " . $StrChampId . "=" . $ligne["value"][$StrChampId];
                    $this->query($strSql);
                    $counter++;
                }
            }
            
            /**
             /**
             * Mise à jour de la valeur du champs parent prꤩsant l'order.
             *
             * @access public
             * @param string $StrNomTable Table ࡭ettre ࡪour
             * @param string $StrChampOrdre Champ contenant l'information de classement
             * @param __TYPE__ $order __DESC__
             * @param string $StrChampId Champ identifiant (la valeur de ce champ pour chaque ligne du tableau est utilisꥠen param鵲e de la fonction de mise ࡪour de l'ordre)
             * @param string $id Valeur du champ pivot
             * @param string $strChampPivot Permet de limiter l'ordre sur les items repondant a un champ pivot
             * @param __TYPE__ $pivotId __DESC__
             * @param string $strComplement (option) __DESC__
             * @return void
             */
            function updateParentReferenceWithOrder($StrNomTable, $StrChampOrdre, $order, $StrChampId, $id, $strChampPivot, $pivotId, $strComplement = "") {
                if ($strComplement != "") {
                    $strComplement = " AND " . $strComplement;
                }
                $sqlUpdate = "UPDATE " . $StrNomTable . "
						SET " . $strChampPivot . " = " . $pivotId . ", " . $StrChampOrdre . " = " . $order . "
						WHERE " . $StrChampId . " = " . $id . $strComplement;
                $this->query($sqlUpdate);
            }
            
            /**
             * Determine si la requete est une requete SELECT
             *
             * @static
             * @access public
             * @param string $sql __DESC__
             * @return bool
             */
            static function isSelect($sql) {
                $sql = trim($sql);
                return (stripos($sql, 'select') === 0 && stripos($sql, 'select into ') !== 0);
            }
            
            /**
             * Définition du commit automatique ou non
             *
             * @access public
             * @param bool $bCommit (option) __DESC__
             * @return void
             */
            function setAutoCommit($bCommit = true) {
                $this->autoCommit = $bCommit;
            }
            
            /**
             * Retourne le nombre d'enregistrements
             *
             * @access public
             * @return int
             */
            public function getRecordCount() {
                return (int)$this->rows;
            }
            
            /** abstract */
            
            /**
             * Début d'une transaction
             *
             * @access public
             * @return void
             */
            abstract function beginTrans();
            
            /**
             * Permet de fermer la connexion avec la base
             *
             * @access public
             * @return void
             */
            abstract function close();
            
            /**
             * Commit des requêtes exécutées
             *
             * @access public
             * @return void
             */
            abstract function commit();
            
            /**
             * Rollback des requêtes exécutées
             *
             * @access public
             * @return void
             */
            abstract function rollback();
            
            /** requêtes */
            
            /**
             * Permet d'exécuter une requête.
             *
             * Aucun résultat n'est renvoyé par cette fonction. Elle doit être utilisé
             * pour effectuer
             * des insertions, des updates... Elle est de même utilisée par les
             * autres fonction de la classe comme queryItem() et queryTab().
             * En revanche la propriété data est mise à jour dans le cas des SELECT :
             * c'est
             * un tableau à 2 niveaux (champs, lignes)
             *
             * @access public
             * @param string $query Chaine SQL
             * @param mixed $param (option) Variables bind de type array(":bind"=>"value")
             * @param mixed $paramLob (option) Variables bind des champs CLOB
             * @param bool $debug (option) __DESC__
             * @return void
             */
            abstract function query($query, $param = array(), $paramLob = array(), $debug = false);
            
            /**
             * Permet d'exécuter une requête devant renvoyer une seule valeur
             *
             * @access public
             * @param string $query Chaine SQL
             * @param mixed $param (option) Variables bind de type array(":bind"=>"value")
             * @param mixed $paramLob (option) Variables bind des champs CLOB
             * @param bool $light (option) True : ne renvoie que les noms de champs, sinon
             * renvoie un tableau associatif
             * @param bool $debug (option) __DESC__
             * @return string
             */
            abstract function queryItem($query, $param = array(), $paramLob = array(), $light = true, $debug = false);
            
            /**
             * Permet d'exécuter une requête devant renvoyer plusieurs lignes de résultat.
             * Le retour est un tableau d'objets
             *
             * @access public
             * @param string $query Chaine SQL
             * @param mixed $param (option) Variables bind de type array(":bind"=>"value")
             * @param mixed $paramLob (option) Variables bind des champs CLOB
             * @param bool $light (option) True : ne renvoie que les noms de champs, sinon
             * renvoie un tableau associatif
             * @param bool $debug (option) __DESC__
             * @return array
             */
            abstract function queryObj($query, $param = array(), $paramLob = array(), $light = true, $debug = false);
            
            /**
             * Permet d'exécuter une requête devant renvoyer une seule ligne de résultat.
             *
             * Le tableau de résultat est à 2 niveaux (lignes, champs)
             *
             * @access public
             * @param string $query Chaine SQL
             * @param mixed $param (option) Variables bind de type array(":bind"=>"value")
             * @param mixed $paramLob (option) Variables bind des champs CLOB
             * @param bool $light (option) True : ne renvoie que les noms de champs, sinon
             * renvoie un tableau associatif
             * @param bool $debug (option) __DESC__
             * @return array
             */
            abstract function queryRow($query, $param = array(), $paramLob = array(), $light = true, $debug = false);
            
            /**
             * Permet d'exécuter une requête devant renvoyer plusieurs lignes de résultat.
             * le tableau de résultat est à 2 niveaux (lignes, champs)
             *
             * @access public
             * @param string $query Chaine SQL
             * @param mixed $param (option) Variables bind de type array(":bind"=>"value")
             * @param mixed $paramLob (option) Variables bind des champs CLOB
             * @param bool $light (option) True : ne renvoie que les noms de champs, sinon
             * renvoie un tableau associatif
             * @param bool $debug (option) __DESC__
             * @return array
             */
            abstract function queryTab($query, $param = array(), $paramLob = array(), $light = true, $debug = false);
            
            /**
             * Duplique un enregistrement d'une table sur une clé.
             *
             * @access public
             * @param string $table Nom de la table impactée
             * @param string $key Champ identifiant
             * @param string $oldValue Valeur du champ identifiant de l'enregistrement à
             * dupliquer
             * @param string $newValue Valeur de remplacement pour le champ identifiant
             * @return void
             */
            abstract function duplicateRecord($table, $key, $oldValue, $newValue);
            
            /** fonctions SQL */
            
            /**
             * Formatage de l'expression de type Case
             *
             * @access public
             * @param string $field __DESC__
             * @param array $aClause __DESC__
             * @param string $defaultValue __DESC__
             * @return string
             */
            abstract function getCaseClause($field, $aClause, $defaultValue);
            
            /**
             * Formatage de l'expression de type CONCAT
             *
             * @access public
             * @param mixed $aValue __DESC__
             * @return string
             */
            abstract function getConcatClause($aValue);
            
            /**
             * Transforme une requête pour qu'elle retourne le comptage des lignes.
             *
             * @access public
             * @param string $query Chaine SQL originale
             * @param string $countFields Liste des champs sur lesquels portent le comptage
             * (clause group by)
             * @return string
             */
            abstract function getCountSQL($query, $countFields);
            
            /**
             * Retourne un tableau de description d'un objet de base de données
             *
             * Table :"field","type","null","default","key","extra","increment","sequence"
             *
             * @access public
             * @param string $type Type d'objet
             * @param string $name (option) __DESC__
             * @return array
             */
            abstract function getDbInfo($type, $name = "");
            
            /**
             * Formatage de l'expression Ajout de n jours à une date
             *
             * @access public
             * @param string $date __DESC__
             * @param int $interval __DESC__
             * @return string
             */
            abstract function getDateAddClause($date, $interval);
            
            /**
             * Formatage de l'expression différence entre deux dates
             *
             * @access public
             * @param string $date1 __DESC__
             * @param string $date2 __DESC__
             * @return string
             */
            abstract function getDateDiffClause($date1, $date2);
            
            /**
             * Retourne un tableau avec N° et message dela dernière erreur
             *
             * @access public
             * @return mixed
             */
            abstract function getError();
            
            /**
             * Retrouve les informations côté client et côté serveur de la base de données
             *
             * @access public
             * @return array
             */
            abstract function getInfo();
            
            /**
             * Permet de recuperer l'id du dernier objet inséré dans la base, si la requete
             * est de type INSERT
             *
             * @access public
             * @return string
             */
            abstract function getLastOid();
            
            /**
             * Transforme une requête pour qu'elle ne retourne que les lignes comprises entre
             * la valeur $min et la valeur $max
             *
             * @access public
             * @param string $query Chaîne SQL
             * @param int $min Valeur Min
             * @param int $length Nombre de lignes
             * @return string
             */
            abstract function getLimitedSQL($query, $min, $length);
            
            /**
             * Formatage de l'expression de type NVL
             *
             * @access public
             * @param string $clause __DESC__
             * @param string $value __DESC__
             * @return string
             */
            abstract function getNVLClause($clause, $value);
            
            /**
             * Permet de recuperer l'id du de la prochaine séquence associée à la table
             *
             * @access public
             * @param string $table Nom de la table
             * @return string
             */
            abstract function getNextId($table);
            
            /**
             * Retourne la fonction d'affichage de la date courante
             *
             * @access public
             * @return string
             */
            abstract function getNow();
            
            /**
             * Formatage de l'expression de recherche
             *
             * @access public
             * @param string $field Champ de recherche
             * @param string $value Valeur de recherche
             * @param string $position Pelican_Index lié à la pertinence (Intermedia par
             * exemple)
             * @param bool $bindName Utilisation du Bind ou non
             * @param mixed $aBind Tableau des valeurs bindées
             * @param string $join (option) Type de jointure de la recherche
             * @return string
             */
            abstract function getSearchClause($field, $value, $position = 0, $bindName = "", &$aBind, $join = "OR");
            
            /** méthode de gestion des dates */
            
            /**
             * Formattage d'une date de la base de donnée au format français
             *
             * @access public
             * @param string $dateField Nom du champs date
             * @param bool $hour (option) Formttage de l'heure à inclure
             * @param string $complement (option) Complement de la chaine de formattage de la
             * date
             * @return string
             */
            abstract function dateSqlToString($dateField, $hour = false, $complement = "");
            
            /**
             * Formattage d'une date de la base de donnée au format français au format
             * JJ/MM/AAAA
             *
             * @access public
             * @param string $dateField Nom du champs date
             * @return string
             */
            abstract function dateSqlToStringShort($dateField);
            
            /**
             * Formattage d'une date française au format de la base de donnée
             *
             * @access public
             * @param string $strChaine Date au format DD/MM/YYYY
             * @param bool $hour (option) __DESC__
             * @return string
             */
            abstract function dateStringToSql($strChaine, $hour = true);
            
            /**
             * Retourne la valeur du mois d'un champ date
             *
             * @access public
             * @param string $dateField Champ date
             * @return string
             */
            abstract function dateToMonth($dateField);
            
            /**
             * Retourne la valeur de l'année d'un champ date
             *
             * @access public
             * @param string $dateField Champ date
             * @return string
             */
            abstract function dateToYear($dateField);
            
            /**
             * Formattage d'une date française au format de la base de donnée
             *
             * @access public
             * @param string $value __DESC__
             * @return string
             */
            abstract function stringToSql($value);
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $type __DESC__
             * @return __TYPE__
             */
            abstract function nativeType($type);
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $default __DESC__
             * @param __TYPE__ $type __DESC__
             * @param __TYPE__ $null __DESC__
             * @return __TYPE__
             */
            abstract function getFieldDefaultDDL($default, $type, $null);
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $increment __DESC__
             * @param string $type (option) __DESC__
             * @return __TYPE__
             */
            abstract function getFieldIncrementDDL($increment, $type = "");
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $null __DESC__
             * @return __TYPE__
             */
            abstract function getFieldNullDDL($null);
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $type __DESC__
             * @param __TYPE__ $length __DESC__
             * @param __TYPE__ $precision __DESC__
             * @return __TYPE__
             */
            abstract function getFieldTypeDDL($type, $length, $precision);
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $table __DESC__
             * @param __TYPE__ $aFields __DESC__
             * @return __TYPE__
             */
            abstract function getIncludedKeysDDL($table, $aFields);
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $table __DESC__
             * @param __TYPE__ $name __DESC__
             * @param __TYPE__ $aFields __DESC__
             * @param bool $unique (option) __DESC__
             * @return __TYPE__
             */
            abstract function getIndexDDL($table, $name, $aFields, $unique = false);
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $table __DESC__
             * @param __TYPE__ $name __DESC__
             * @param __TYPE__ $aFields __DESC__
             * @return __TYPE__
             */
            abstract function getPrimaryKeyDDL($table, $name, $aFields);
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $table __DESC__
             * @param __TYPE__ $name __DESC__
             * @param __TYPE__ $childField __DESC__
             * @param __TYPE__ $source __DESC__
             * @return __TYPE__
             */
            abstract function getReferencesDDL($table, $name, $childField, $source);
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $name __DESC__
             * @param string $start (option) __DESC__
             * @param string $increment (option) __DESC__
             * @return __TYPE__
             */
            abstract function getSequenceDDL($name, $start = "", $increment = "");
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $table __DESC__
             * @param __TYPE__ $name __DESC__
             * @param __TYPE__ $aField __DESC__
             * @return __TYPE__
             */
            abstract function getUniqueKeyDDL($table, $name, $aField);
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $table __DESC__
             * @param __TYPE__ $field __DESC__
             * @param __TYPE__ $sequence_name __DESC__
             * @return __TYPE__
             */
            abstract function getUpdateSequenceDDL($table, $field, $sequence_name);
            
            /**
             * __DESC__
             *
             * @access public
             * @param __TYPE__ $type __DESC__
             * @return __TYPE__
             */
            abstract function getEndDDL($type);
            
            /**
             * Modifie l'ordre des variables Bind pour respecter celui défini dans la requête
             *
             * @access public
             * @param string $query __DESC__
             * @param mixed $param (option) __DESC__
             * @param mixed $paramLob (option) __DESC__
             * @param __TYPE__ $format (option) __DESC__
             * @return void
             */
            function prepareBind(&$query, &$param = array(), &$paramLob = array(), $format = "?") {
                //		debug($query);
                $esc = "___";
                if (!$this->allowBind) {
                    if ($param) {
                        //$query = strtr($query, $param);
                        $query = strtr($query, array_map("nvl", $param));
                    }
                } else {
                    $aBind = array();
                    $aOrder = array();
                    $aLob = array();
                    if ($param) {
                        $query = str_replace('javascript:', '##JAVASCIPT##', $query);
                        array_map("nvl", $param);
                        $reg = '/(\:[0-9a-zA-Z\_]+)+/';
                        preg_match_all($reg, $query, $arr);
                        if ($arr) {
                            // pour éviter les pb de str_replace par la suite
                            $query = preg_replace($reg, " " . $esc . "\\1" . $esc . " ", $query);
                            $k = 0;
                            foreach($arr[1] as $i => $key) {
                                if (key_exists($key, $param) && !$control[$key]) {
                                    $k++;
                                    $value = $param[$key];
                                    if ((empty($value) || $value == "") && !($value === "0")) {
                                        $value = null;
                                    }
                                    $param2[] = $value;
                                    $old[] = " " . $esc . $key . $esc . " ";
                                    switch ($format) {
                                        case "?": {
                                                    $new[] = "?";
                                                break;
                                            }
                                        case "\$": {
                                                $new[] = "\$" . $k;
                                                break;
                                            }
                                        }
                                        // contrôle pour éviter de donner des id différents pour le même bind
                                        $control[$key] = true;
                                    }
                                }
                                $query = str_replace("\t", " ", $query);
                                $query = str_replace($old, $new, $query);
                                if (self::isSelect($query)) {
                                    
                                    /** pb sur Pelican_Db_Ingres si le CRLF est juste après SELECT */
                                    $query = preg_replace("/^select/i", "SELECT ", $query);
                                }
                                $param = $param2;
                            }
                            $query = str_replace('##JAVASCIPT##', 'javascript:', $query);
                        }
                    }
                }
                /*function prepareBind(&$query, &$param = array(), &$paramLob = array()) {
                $aBind = array();
                $aOrder = array();
                $aLob = array();
                if ($param) {
                // préparation de la requête
                $keys = array_keys($param);
                foreach($keys as $i => $key) {
                if ($param[$key] == "null" || $param[$key] == "NULL" || $param[$key] == "") {
                $bindTmp[$key] = "NULL";
                } else {
                $bindTmp[$key] = "?" . ($i + 1) . "?";
                $aBind[$i + 1] = $param[$key];
                }
                if (in_array($key, $paramLob)) {
                $aLob[] = ":SR" . ($i + 1);
                
                // echappement des caractères spéciaux de word
                $aBind[$i + 1] = Pelican_Text::htmlencode($aBind[$i + 1]);
                }
                }
                $query = strtr($query, $bindTmp);
                // recherche et remplacement des variables bind
                preg_match_all("/\?([0-9]+)\?/i", $query, $aOrder);
                $query = preg_replace("/\?([0-9]+)\?/i", " :SR$1", $query);
                
                // tri des variables bind
                $param = array();
                $paramLob = array();
                foreach($aOrder[1] as $idBind) {
                $param[":SR" . $idBind] = $aBind[$idBind];
                $paramLob = $aLob;
                }
                }
                }*/
                
                /**
                 * __DESC__
                 *
                 * @static
                 * @access public
                 * @staticvar xx $database_connection
                 * @staticvar xx $lastConnection
                 * @param string $app (option) __DESC__
                 * @return __TYPE__
                 */
                static function getInstance($app = "") {
                    static $database_connection;
                    static $lastConnection;
                    Pelican_Log::control('Application :' . $app, 'connection');
                    if (!isset($database_connection) || $lastConnection != $app) {
                        $database_connection = self::useDb($app);
                        $lastConnection = $app;
                        //debug('init connection');
                        return $database_connection;
                    } else {
                        //debug('reuse connection');
                        return $database_connection;
                    }
                }
                
                /**
                 * Connection à une base de données dont les paramètres de connexion sont
                 * spécifiés dans la variable globale Pelican::$config['db'], sinon utilise les
                 * paramètres par
                 *
                 * Défaut définis dans local.ini.php
                 *
                 * @static
                 * @access public
                 * @param string $app (option) Base de connexion
                 * @return Pelican_Db
                 */
                static function useDb($app = "") {
                    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                    $file = $backtrace[3];
                    unset($backtrace);
                    $indice = ($app ? $app : 'Pelican') . ' : ' . $file["file"];
                    Pelican_Profiler::start($indice, 'connection');
                    if (isset(Pelican::$config['db'][$app])) {
                        $aParams = Pelican::$config['db'][$app];
                    } else {
                        $aParams = Pelican::$config;
                    }
                    if (!isset($aParams["DATABASE_HOST"])) $aParams["DATABASE_HOST"] = "";
                    if (!isset($aParams["DATABASE_PORT"])) $aParams["DATABASE_PORT"] = "";
                    $item = 'Db.' . ucfirst($aParams["DATABASE_TYPE"]);
                    if (basename(__FILE__) == 'Db.php') {
                        $type = ucfirst($aParams["DATABASE_TYPE"]);
                        $class = 'Pelican_Db_' . $type;
                    } else {
                        $type = $aParams["DATABASE_TYPE"];
                        $class = $type;
                    }
                    include_once (Pelican::$config['LIB_ROOT'] . Pelican::$config['LIB_DB'] . "/" . $type . ".php");
                    $conn = new $class($aParams["DATABASE_NAME"], $aParams["DATABASE_USER"], $aParams["DATABASE_PASS"], $aParams["DATABASE_HOST"], $aParams["DATABASE_PORT"], true, $aParams["DATABASE_PERSISTENTCONNECTION"]);
                    if (isset($aParams["DATABASE_CHARSET"])) {
                        $conn->charset = $aParams["DATABASE_CHARSET"];
                    }
                    Pelican_Profiler::stop($indice, 'connection');
                    return $conn;
                }
                
                /**
                 * Fonction qui teste si la  variables de configuration get_magic_quotes_gpc est
                 * activée ou pas.
                 *
                 * @static
                 * @access public
                 * @param string $chaine Chaine de caracère qui provient de (Get/Post/Cookie).
                 * Cette fonction sera utilisée dans les requêtes d'insert, select et update
                 * @return string
                 */
                static function addSlashesGPC($chaine) {
                    return (get_magic_quotes_gpc() == 1 ? $chaine : AddSlashes($chaine));
                }
                
                /**
                 * Fonction qui teste si la  variable de configuration get_magic_quotes_gpc est
                 * activée ou pas.
                 *
                 * @static
                 * @access public
                 * @param string $chaine Chaine de caracère qui provient de (Get/Post/Cookie)
                 * @return string
                 */
                static function stripSlashesGPC($chaine) {
                    return (get_magic_quotes_gpc() == 1 ? StripSlashes($chaine) : $chaine);
                }
                
                /**
                 * Fonction qui teste si la  variable de configuration get_magic_quotes_runtime
                 * est
                 * activée ou pas.
                 *
                 * Si la variable est activée, les chaine de caractère qui proviennent d'une
                 * requête Sql sera échappée par des antiSlash
                 *
                 * @static
                 * @access public
                 * @param string $chaine Chaine de caractère qui provient de requête SQL
                 * @return string
                 */
                static function stripSlashesSQL($chaine) {
                    return (get_magic_quotes_runtime() == 1 ? StripSlashes($chaine) : $chaine);
                }
                
                /**
                 * Retourne le minimum d'un tableau de valeurs
                 *
                 * @static
                 * @access public
                 * @param mixed $tab __DESC__
                 * @return string
                 */
                static function arrayMin($tab) {
                    $tabMin = "";
                    foreach($tab as $value) {
                        if ($value) {
                            if ($tabMin) {
                                $tabMin = min($tabMin, $value);
                            } else {
                                $tabMin = $value;
                            }
                        }
                    }
                    return $tabMin;
                }
                
                /**
                 * Gestion des valeurs "NULL"
                 *
                 * @access public
                 * @param string $value (option) __DESC__
                 * @return string
                 */
                public static function nvl($value = "") {
                    global $allowBind;
                    $return = $value;
                    if (!$return && !($return === '0') && !($return === 0)) {
                        $return = "null";
                    }
                    /* contrôle des champs numériques */
                    if ($return && !is_array($return)) {
                        if (!$allowBind && $return != "null" && substr($return, 0, 1) != "'" && !is_numeric($return)) {


                            $pattern = array(
                                "--" ,
                                "\n" ,
                                "\r" ,
                                "/*" ,
                                "*/" ,
                                "@" ,
                                "drop" ,
                                "select" ,
                                "into" ,
                                "union" ,
                                " or " ,
                                "#" ,
                                ";"
                            );
                            $return = str_replace($pattern, "", $return);
                            $return = str_replace($pattern, "", $return);
                            $return = trim($return); //$return = str_replace(" ", "", $return);
                            /*   if ($return != 'now()') {
                            debug($return, "attention");
                            }*/
                        }
                    }
                    return $return;
                }
                
                /**
                 * Crée un tableau de Bind à partir d'un tableau PHP
                 *
                 * @access public
                 * @param mixed $aDataValues Tableau des valeurs à binder
                 * @return mixed
                 */
                function arrayToBind($aDataValues) {
                    $aBind = array();
                    if (is_array($aDataValues)) {
                        foreach($aDataValues as $key => $value) {
                            $aBind[":" . $key] = $value;
                        }
                    }
                    return $aBind;
                }
                
                /**
                 * Log de l'erreur de BDD dans le fichier de log d'erreurs d'Apache
                 *
                 * @access public
                 * @todo utiliser la classe LOG
                 * @param string $host Host de connexion de la base
                 * @param string $content Contenu à enregistrer dans le fichier de log
                 * @return void
                 */
                function log500($host, $content) {
                    $path = Pelican::$config['MEDIA_ROOT'] . "/database." . $host . ".log";
                    if ($_GET['clean']) {
                        unlink($path);
                    }
                    $fp = fopen($path, "a");
                    if (fwrite($fp, $content)) {
                        fclose($fp);
                    }
                }
            }
            /*
            $fld->name
            $fld->has_default
            $fld->max_length
            $fld->scale
            $fld->zerofill
            $fld->default_value
            $fld->binary
            $fld->unsigned
            $fld->enums
            $fld->not_null
            $fld->primary_key
            $fld->auto_increment
            $fld->type
            */
?>