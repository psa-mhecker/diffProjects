<?php
/**
 * Classe de génération de scripts de base de données.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/**
 * Classe de génération de scripts de base de données.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Db_Generate
{
    /**
     * Constructeur.
     *
     * @access public
     *
     * @param string $type Type de base de données
     */
    public function __construct($type)
    {
        $this->type = ucfirst($type);
        pelican_import('Db.'.$this->type);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $DDLType        __DESC__
     * @param __TYPE__ $table          __DESC__
     * @param __TYPE__ $aFields        (option) __DESC__
     * @param __TYPE__ $aPrimaryKeys   (option) __DESC__
     * @param __TYPE__ $aKeys          (option) __DESC__
     * @param bool     $separateOutput (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getFullTableDDL($DDLType, $table, $aFields = array(), $aPrimaryKeys = array(), $aKeys = array(), $separateOutput = false)
    {
        $table = strtolower($table);
        $tableDDL = $this->getTableDDL($DDLType, $table, $aFields, $aPrimaryKeys, $aKeys);
        $indexDDL = $this->getTableIndexDDL($DDLType, $table, $aFields, $aKeys, $aPrimaryKeys);
        if ($separateOutput) {
            $return = array('table' => $tableDDL, 'index' => $indexDDL);
        } else {
            $return = $tableDDL."\n".$indexDDL;
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $DDLType      __DESC__
     * @param __TYPE__ $name         __DESC__
     * @param __TYPE__ $aFields      (option) __DESC__
     * @param __TYPE__ $aPrimaryKeys (option) __DESC__
     * @param __TYPE__ $aKeys        (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getTableDDL($DDLType, $name, $aFields = array(), $aPrimaryKeys = array(), $aKeys = array())
    {
        /* pour garantir la compatibilité entre les bases, minuscule pour les cases sensitives */
        $table = strToLower($name);
        if ($aFields) {
            foreach ($aFields as $field) {
                $temp[] = $this->getFieldDDL($DDLType, $field);
            }
            $fieldsDDL = "\n\t".implode(",\n\t", $temp);
        }

        /* DDL des clés primaires */
        if ($aPrimaryKeys) {
            $primaryKeysDDL = $this->getPrimaryKeyDDL($DDLType, $table, $aPrimaryKeys);
        }

        /* DDL des clés alternatives incluses dans la création de la table */
        if ($aKeys) {
            $includedKeysDDL = $this->getIncludedKeysDDL($DDLType, $table, $aKeys);
        }

        /* DDL de fin de génération de table */
        $tableEnd = $this->getEndDDL($DDLType, 'Table');

        /* génération de l'instruction DDL complète */
        $return = self::getCommentDDL("Table : ".$table);
        $return .= trim('CREATE TABLE '.$table.' ('.$fieldsDDL.($primaryKeysDDL ? ",\n\t".$primaryKeysDDL : "").($includedKeysDDL ? ",\n".$includedKeysDDL : "\n").')'.$tableEnd);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $DDLType __DESC__
     * @param __TYPE__ $field   __DESC__
     *
     * @return __TYPE__
     */
    public function getFieldDDL($DDLType, $field)
    {
        $name = $field['field'];
        $type = $field['primaryType'];
        $length = $field['length'];
        $precision = $field['precision'];
        $default = $field['default'];
        $null = $field['null'];
        $increment = $field['increment'];
        $this->fieldName = strtoupper($name);
        $this->fieldType = Pelican_Factory::staticCall('Db.'.$DDLType, 'getFieldTypeDDL', $type, $length, $precision);
        $this->null = Pelican_Factory::staticCall('Db.'.$DDLType, 'getFieldNullDDL', $null);
        $this->default = Pelican_Factory::staticCall('Db.'.$DDLType, 'getFieldDefaultDDL', $default, $type, $null);
        $this->increment = Pelican_Factory::staticCall('Db.'.$DDLType, 'getFieldIncrementDDL', $increment, $type);
        $return = $this->fieldName.' '.$this->fieldType.' '.$this->null.' '.$this->default.' '.$this->increment;

        return trim(str_replace('  ', ' ', $return));
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $DDLType __DESC__
     * @param __TYPE__ $table   __DESC__
     * @param __TYPE__ $aField  __DESC__
     *
     * @return __TYPE__
     */
    public function getPrimaryKeyDDL($DDLType, $table, $aField)
    {
        $name = "PK_".str_replace(strtoupper(Pelican::$config['FW_PREFIXE_TABLE']), '', strtoupper($table));
        $return = Pelican_Factory::staticCall('Db.'.$DDLType, 'getPrimaryKeyDDL', $table, $name, $aField);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $DDLType __DESC__
     * @param __TYPE__ $table   __DESC__
     * @param __TYPE__ $aKeys   __DESC__
     *
     * @return __TYPE__
     */
    public function getIncludedKeysDDL($DDLType, $table, $aKeys)
    {
        if ($aKeys) {
            $return = Pelican_Factory::staticCall('Db.'.$DDLType, 'getIncludedKeysDDL', $table, $aKeys);
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $DDLType      __DESC__
     * @param __TYPE__ $name         __DESC__
     * @param __TYPE__ $aFields      (option) __DESC__
     * @param __TYPE__ $aKeys        (option) __DESC__
     * @param __TYPE__ $aPrimaryKeys (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getTableIndexDDL($DDLType, $name, $aFields = array(), $aKeys = array(), $aPrimaryKeys = array())
    {
        $table = strToLower($name);

        /* génération des Pelican_Index */
        $indexDDL = $this->getIndexDDL($DDLType, $aKeys, $table);
        if ($indexDDL) {
            $return = self::getCommentDDL("Index : ".$table);
            $return .= $indexDDL;
        }

        /* génération des clés étrangères */
        if ($aFields) {
            foreach ($aFields as $field) {
                if ($field['fkey']) {
                    $FKeys[] = $field;
                }
            }
        }
        if ($FKeys) {
            foreach ($FKeys as $field) {
                $temp2[] = $this->getReferencesDDL($DDLType, $name, $field);
            }
            $references = implode("\n", $temp2);
            $return .= self::getCommentDDL("Contraints : ".$table);
            $return .= $references;
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     * @staticvar $countIndex
     *
     * @param __TYPE__ $DDLType __DESC__
     * @param __TYPE__ $aKeys   __DESC__
     * @param __TYPE__ $table   __DESC__
     *
     * @return __TYPE__
     */
    public function getIndexDDL($DDLType, $aKeys, $table)
    {
        static $countIndex;
        if (!$countIndex[$table]) {
            $countIndex[$table] = 9;
        }
        $control = array();
        $return = "";
        if ($aKeys) {
            foreach ($aKeys as $key) {
                $fields = implode(',', $key['fields']);
                // on donne à l'index le nom du champ
                if (count($key['fields']) == 1) {
                    $f = array_values($key['fields']);
                    $key['name'] = $f[0];
                }
                if (!$control[$fields]) {
                    $countIndex[$table]++;
                    $name = "I_".str_replace(strtoupper(Pelican::$config['FW_PREFIXE_TABLE']), '', strtoupper($table))."_".$countIndex[$table];
                    $index[] = Pelican_Factory::staticCall('Db.'.$DDLType, 'getIndexDDL', $table, $name, $key['fields']);
                    $control[$fields] = true;
                }
            }
            $return = implode("\n", $index);
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     * @staticvar $count
     *
     * @param __TYPE__ $DDLType __DESC__
     * @param __TYPE__ $table   __DESC__
     * @param __TYPE__ $field   __DESC__
     *
     * @return __TYPE__
     */
    public function getReferencesDDL($DDLType, $table, $field)
    {
        static $count;
        if (!$count[$table]) {
            $count[$table] = 9;
        }
        $count[$table]++;
        $name = strtoupper(str_replace(Pelican::$config['FW_PREFIXE_TABLE'], '', $table));
        $name = "FK_".$name."_".$count[$table]; //strotupper(($constraint?$constraint:'CONSTRAINT_'.$count));
        $childField = $field['field'];
        $source = str_replace('.', ' (', $field['fkey']).')';
        $return = Pelican_Factory::staticCall('Db.'.$DDLType, 'getReferencesDDL', $table, $name, $childField, $source);
        $temp = explode('(', trim($source, ')'));
        $return .= "\n-- SQL DE CONTROLE : SELECT * FROM ".$table." WHERE ".$childField." NOT IN (SELECT ".$temp[1]." FROM ".$temp[0].")";

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $DDLType __DESC__
     * @param __TYPE__ $type    __DESC__
     *
     * @return __TYPE__
     */
    public function getEndDDL($DDLType, $type)
    {
        $return = Pelican_Factory::staticCall('Db.'.$DDLType, 'getEndDDL', $type);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $DDLType __DESC__
     * @param __TYPE__ $field   __DESC__
     *
     * @return __TYPE__
     */
    public function getSequenceDDL($DDLType, $field)
    {
        $name = 'SEQ_'.str_replace('_ID', '', strtoupper($field));
        $return = Pelican_Factory::staticCall('Db.'.$DDLType, 'getSequenceDDL', $name, 1, 1);

        return trim($return);
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param __TYPE__ $DDLType __DESC__
     * @param __TYPE__ $table   __DESC__
     * @param __TYPE__ $aField  __DESC__
     *
     * @return __TYPE__
     */
    public static function getUniqueKeyDDL($DDLType, $table, $aField)
    {
        $name = "U_".str_replace(strtoupper(Pelican::$config['FW_PREFIXE_TABLE']), '', strtoupper($table));
        $return = Pelican_Factory::staticCall('Db.'.$DDLType, 'getUniqueKeyDDL', $table, $aField);
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param __TYPE__ $DDLType __DESC__
     * @param __TYPE__ $table   __DESC__
     * @param __TYPE__ $aField  __DESC__
     *
     * @return __TYPE__
     */
    public static function getUpdateSequenceDDL($DDLType, $table, $aField)
    {
        $return = array();
        foreach ($aField as $field) {
            if ($field['sequence']) {
                $return[] = Pelican_Factory::staticCall('Db.'.$DDLType, 'getUpdateSequenceDDL', $table, $field['field'], $field['sequence_name']);
            }
        }

        return implode("\n", $return);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string $comment (option) __DESC__
     *
     * @return __TYPE__
     */
    public static function getCommentDDL($comment = "")
    {
        $return = "\n-- --------------------------------------------------------\n";
        if ($comment) {
            $return .= "--
-- ".$comment."
--
-- --------------------------------------------------------\n";
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @static
     * @access public
     *
     * @param __TYPE__ $aTable            __DESC__
     * @param string   $targetDb          (option) __DESC__
     * @param string   $targetPrefixe     (option) __DESC__
     * @param string   $appGenerateValues (option) __DESC__
     *
     * @return __TYPE__
     */
    public static function databaseToDDL($aTable, $targetDb = "", $targetPrefixe = "", $appGenerateValues = "")
    {
        $oConnection = Pelican_Db::getInstance();
        if (!$targetDb) {
            $targetDb = Pelican::$config["DATABASE_TYPE"];
        }
        $DDLType = ucfirst($targetDb);
        $originalPrefixe = Pelican::$config['FW_PREFIXE_TABLE'];
        if (!$targetPrefixe) {
            $targetPrefixe = $originalPrefixe;
        }
        if (!is_array($aTable)) {
            $aTable = array($aTable);
        }
        foreach ($aTable as $table) {
            $aFields = $oConnection->describeTable($table);
            $aPKeys = $oConnection->getDbInfo('keys', $table);
            $aKeys = $oConnection->getDbInfo('indexes', $table);
            $oGenerate = Pelican_Factory::getInstance('Db.Generate', $targetDb);
            $temp = $oGenerate->getFullTableDDL($DDLType, $table, $aFields, $aPKeys, $aKeys, true);
            $return['table'][] = str_replace($originalPrefixe, $targetPrefixe, $temp['table']);
            $return['index'][] = str_replace($originalPrefixe, $targetPrefixe, $temp['index']);
        }

        /* pb de connexion => doit être joué après */
        if ($appGenerateValues) {
            foreach ($aTable as $table) {
                $return['data'][] = self::getValues($table, "INS", "", "", "", $appGenerateValues);
            }
        }
        if ($return) {
            if ($appGenerateValues) {
                $return = implode("\n", $return['table']).self::getCommentDDL('Datas7').implode("\n", $return['data']).self::getCommentDDL().implode("\n", $return['index']);
            } else {
                $return = implode("\n", $return['table']).self::getCommentDDL().implode("\n", $return['index']);
            }
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string $app (option) __DESC__
     *
     * @return __TYPE__
     */
    public static function getDbMap($app = "")
    {
        $oConnection = Pelican_Db::getInstance($app);
        $aTable = $oConnection->getDbInfo('tables');
        foreach ($aTable as $table) {

            /* Infos sur les tables */
            $aTable[$table]['fields'] = $oConnection->describeTable($table);

            /* infos sur les clés primaires */
            $aTable[$table]['keys'] = $oConnection->getDbInfo('keys', $table);

            /* infos sur les clés primaires */
            $aTable[$table]['indexes'] = $oConnection->getDbInfo('indexes', $table);

            /* infos sur les clés étrangères */
            $aTable[$table]['foreign_keys'] = $oConnection->getDbInfo('foreign_keys', $table);

            /* identification des champs ressemblant à des champs _id */
            if ($aTable[$table]['fields']) {
                foreach ($aTable[$table]['fields'] as $field) {
                    if (strtolower(substr($field['field'], -3)) == '_id') {
                        $aReturn['_id'][$field['field']][] = $table;
                    }
                }
            }

            /* synthèse des clés primaires */
            if ($aTable[$table]['keys']) {
                $aReturn['keys'][implode(",", $aTable[$table]['keys']) ] = $table;
            }

            /* synthèse des indes */
            if ($aTable[$table]['indexes']) {
                foreach ($aTable[$table]['indexes'] as $index) {
                    $aReturn['indexes'][implode(",", $index['fields']) ][] = $table;
                }
            }

            /* synthèse des clés étrangères */
            if ($aTable[$table]['foreign_keys']) {
                foreach ($aTable[$table]['foreign_keys'] as $index) {
                    $aReturn['foreign_keys'][$index['child_field']] = $table;
                    $aReturn['links'][$table.'.'.$index['child_field']] = $index['parent_table'].'.'.$index['parent_field'];
                }
            }
        }

        return $aReturn;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string $app      (option) __DESC__
     * @param string $targetDb (option) __DESC__
     *
     * @return __TYPE__
     */
    public static function getDbControl($app = "", $targetDb = "")
    {
        // cartographie de la base
        $aMap = self::getDbMap($app);
        // Type de base de données
        if (!$targetDb) {
            $targetDb = Pelican::$config["DATABASE_TYPE"];
        }
        $DDLType = ucfirst($targetDb);
        $oGenerate = Pelican_Factory::getInstance('Db.Generate', $targetDb);
        // contrôle des champs _ID pour identifier les éventuelles cl&és étrangères
        foreach ($aMap['_id'] as $field => $tables) {
            foreach ($tables as $table) {
                $aKeys = array();
                $FKeys = array();
                $temp2 = array();
                $value = '';
                $ddl = array();
                $debutTable = substr($table, 0, strlen(Pelican::$config['FW_PREFIXE_TABLE']));
                //$key = $aPK[$table];
                if ($debutTable != Pelican::$config['FW_PREFIXE_TABLE']) {
                    $bad['table'][$table] = Pelican::$config['FW_PREFIXE_TABLE'].$table;
                } else {
                    $len = strlen(Pelican::$config['FW_PREFIXE_TABLE']);
                    $prefixeTable = strtoupper(substr($table, $len, strlen($table) - $len));
                    $debutChamp = substr($field, 0, strlen($prefixeTable));
                    $corrige = substr($field, strlen($debutChamp) + 1, strlen($field) - strlen($debutChamp));
                    if (!$aMap['keys'][$field]) {
                        if ($prefixeTable == $debutChamp) {
                            if ($corrige != 'ID' && $corrige != 'PARENT_ID') {
                                //debug(array("table : ".$table, "field : ".$field, "PrefTable : ".$prefixeTable,"DebutChamp : ".$debutChamp));
                                //debug($corrige);
                                $bad['fk'][$table][$field] = $corrige;
                                //$aReturn['global'][] = $table.' : '.$field.' => '.$corrige.' <br />';
                                $aReturn['global'][] = "xxxx ALTER TABLE ".$table." RENAME COLUMN ".$field." TO ".$corrige.";\n";
                            }
                        }
                    } else { //debug(array("table : ".$table, "field : ".$field, "PrefTable : ".$prefixeTable,"DebutChamp : ".$debutChamp));
                    }
                }
                // clé primaire
                if ($aMap['keys'][$field] == $table) {
                    $type = 'pk';
                    $index = true;
                } elseif ($aMap['links'][$table.'.'.$field]) {
                    $type = 'fk';
                    $index = false;
                    if (is_array($aMap['indexes'][$field])) {
                        if (in_array($table, $aMap['indexes'][$field])) {
                            $index = true;
                        }
                    }
                } else {
                    if ($aMap['keys'][$field]) {
                        $type = 'missingfk';
                        $value = $aMap['keys'][$field].'.'.$aMap['keys'][$field];
                    } else {
                        $type = '';
                    }
                    // cas particulier des PARENT
                    if ($aMap['keys'][str_replace('_PARENT', '', $field) ]) {
                        $type = 'missingfk';
                        $value = $aMap['keys'][str_replace('_PARENT', '', $field) ].'.'.str_replace('_PARENT', '', $field);
                    }
                    $index = false;
                    if (is_array($aMap['indexes'][$field])) {
                        if (in_array($table, $aMap['indexes'][$field])) {
                            $index = true;
                        }
                    }
                }
                // tableau de controle
                if ($type && $index) {
                    $control[$table.'.'.$field]['type'] = $type;
                    $control[$table.'.'.$field]['index'] = $index;
                    if ($value) {
                        $control[$table.'.'.$field]['value'] = $value;
                    }
                }
                // cas  : foreign key ou supposée foreign key sans index => on crée l'index
                if (($type == 'fk' || $type == 'missingfk') && !$index) {
                    $aKeys[] = array('name' => $field, 'fields' => array($field));
                }
                // cas  : supposée foreign key => on crée la référence
                if ($type == 'missingfk') {
                    $FKeys[] = array('field' => $field, 'fkey' => $value);
                }

                /* génération des Pelican_Index manquants */
                if ($aKeys) {
                    //debug($aKeys);
                    $indexDDL = $oGenerate->getIndexDDL($DDLType, $aKeys, $table);
                    if ($indexDDL) {
                        $ddl['index'] = $indexDDL;
                    }
                }

                /* génération des clés étrangères manquantes */
                if ($FKeys) {
                    //debug($FKeys);
                    foreach ($FKeys as $f) {
                        $temp2[] = $oGenerate->getReferencesDDL($DDLType, $table, $f);
                    }
                    $referencesDDL = implode("\n", $temp2);
                    $ddl['link'] = $referencesDDL;
                }
                if ($ddl) {
                    if ($ddl['index']) {
                        if (!$aReturn['index_'.$table]) {
                            $aReturn['index_'.$table][0] = self::getCommentDDL("index de la table '".$table."' manquants");
                        }
                        $aReturn['index_'.$table][] = $ddl['index'];
                    }
                    if ($ddl['link']) {
                        if (!$aReturn['link_'.$table]) {
                            $aReturn['link_'.$table][0] = self::getCommentDDL("contraintes de la table '".$table."' manquantes");
                        }
                        $aReturn['link_'.$table][] = $ddl['link'];
                    }
                }
            }
        }
        if ($aReturn) {
            foreach ($aReturn as $key => $array) {
                $return .= implode("\n", $array)."\n\n";
            }
        }

        return $return;
    }

    /**
     * Génération XML du descriptif de la(les) table(s) passée(s) en paramètre.
     *
     * Inspiré du format apache ddlutils
     *
     * @access public
     *
     * @param micex $aTable (option) Table (string) ou liste de tables (array)
     *
     * @return string
     */
    public function describeToXml($aTable = "")
    {
        $oConnection = Pelican_Db::getInstance();
        if (!$aTable) {
            $aTable = $oConnection->getDbInfo('tables');
        }
        if (!is_array($aTable)) {
            $aTable = array($aTable);
        }
        //        ksort($aTable);
        $dom = new DOMDocument('1.0', 'UTF-8');
        $node = $dom->appendChild($dom->createElement('database'));
        $node->setAttributeNode(new DOMAttr('name', $oConnection->databaseName));
        foreach ($aTable as $table) {
            $nTable = $node->appendChild($dom->createElement('table'));
            $nTable->setAttributeNode(new DOMAttr('name', str_replace(Pelican::$config['FW_PREFIXE_TABLE'], '', $table)));
            $aField = $oConnection->describeTable($table);
            $aIndex = $oConnection->getDbInfo('indexes', $table);
            $aFK = array();
            if ($aField) {
                foreach ($aField as $field) {
                    $nField = $nTable->appendChild($dom->createElement('column'));
                    $nField->setAttributeNode(new DOMAttr('name', $field['field']));
                    $nField->setAttributeNode(new DOMAttr('primaryKey', ($field['key'] ? 'true' : 'false')));
                    $nField->setAttributeNode(new DOMAttr('required', ($field['null'] ? 'false' : 'true')));
                    $nField->setAttributeNode(new DOMAttr('type', $field['primaryType']));
                    $nField->setAttributeNode(new DOMAttr('size', $field['length']));
                    $nField->setAttributeNode(new DOMAttr('precision', $field['precision']));
                    $nField->setAttributeNode(new DOMAttr('default', $field['default']));
                    $nField->setAttributeNode(new DOMAttr('autoIncrement', ($field['increment'] || $field['sequence'] ? 'true' : 'false')));
                    if ($field["fkey"]) {
                        $temp = explode('.', $field["fkey"]);
                        $aFK[$temp[0]]['local'] = $field['field'];
                        $aFK[$temp[0]]['foreign'] = $temp[1];
                    }
                }
            }
            if ($aFK) {
                foreach ($aFK as $key => $value) {
                    $nFkey = $nTable->appendChild($dom->createElement('foreign-key'));
                    $nFkey->setAttributeNode(new DOMAttr('foreignTable', $key));
                    $nRef = $nFkey->appendChild($dom->createElement('reference'));
                    $nRef->setAttributeNode(new DOMAttr('local', $value['local']));
                    $nRef->setAttributeNode(new DOMAttr('foreign', $value['foreign']));
                }
            }
            if ($aIndex) {
                foreach ($aIndex as $key => $value) {
                    $aIndex[$table];
                    $nIndex = $nTable->appendChild($dom->createElement('index'));
                    $nIndex->setAttributeNode(new DOMAttr('name', $value['name']));
                    foreach ($value['fields'] as $f) {
                        $nIField = $nIndex->appendChild($dom->createElement('index-column'));
                        $nIField->setAttributeNode(new DOMAttr('name', $f));
                    }
                }
            }
        }

        return $dom->saveXML();
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $xml __DESC__
     *
     * @return __TYPE__
     */
    public function xmlToDescribe($xml)
    {
        $return = array();
        $oXml = simplexml_load_string($xml);
        /*
        case 'fields'
        case 'foreign_keys'
        case 'functions'
        case 'indexes'
        case 'keys'
        case 'tables'
        case 'views'
        */

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $table  __DESC__
     * @param __TYPE__ $action __DESC__
     * @param string   $field  (option) __DESC__
     * @param string   $where  (option) __DESC__
     * @param string   $order  (option) __DESC__
     * @param string   $app    (option) __DESC__
     *
     * @return __TYPE__
     */
    public function getValues($table, $action, $field = "", $where = "", $order = "", $app = "")
    {
        set_time_limit(300);
        $oConnection = Pelican_Db::getInstance();
        $oConnection->allowBind = false;
        $originalPrefixe = Pelican::$config['FW_PREFIXE_TABLE'];
        $targetPrefixe = $originalPrefixe;
        $where = str_replace("\\", "", Pelican_Text::unhtmlentities($where));
        if ($table) {
            $aBind = array();
            $aValues = $oConnection->selectQuery($table, $where, $order, $aBind, true, true, false, $field);
            //debug($aValues);
            if ($aValues) {
                if ($app) {
                    $oConnection2 = Pelican_Factory::getInstance('Db', $app);
                    $targetPrefixe = Pelican::$config['db'][$app]['FW_PREFIXE_TABLE'];
                } else {
                    $oConnection2 = & $oConnection;
                }
                if ($action == 'VIEW') {
                    $fields = $oConnection2->getDbInfo('fields', str_replace($originalPrefixe, $targetPrefixe, $table));
                    $tableHTML = Pelican_Factory::getInstance('List', "", "", 0, 0, 0, "liste");
                    $tableHTML->quickTable($aValues, "", "", array("tblalt1", "tblalt2"));
                    $return = $tableHTML->getTable();
                } else {
                    $aField = array();
                    if ($field) {
                        $aField = array_values(explode(',', $field));
                        $aField = array_map('trim', $aField);
                    }
                    $return = "--DEBUT ".str_replace($originalPrefixe, $targetPrefixe, $table)." ".$action."\n";
                    if (strtolower($oConnection2->databaseTitle) == 'oracle') {
                        $return .= "SET DEFINE OFF;\n";
                    }
                    foreach ($aValues as Pelican_Db::$values) {
                        foreach (Pelican_Db::$values as $key => $value) {
                            if (substr_count($value, "00/00/0000") || substr_count($value, "0000-00-00")) {
                                Pelican_Db::$values[$key] = self::cleanDate($value);
                            }
                        }
                        $oConnection2->useCacheDescribe = false;
                        $syntaxe = true;
                        $result = $oConnection2->updateTable($action, str_replace($originalPrefixe, $targetPrefixe, $table), "", "", $aField, $syntaxe);
                        if ($result) {
                            $return .= str_replace("'''", "''", str_replace("\n", "", str_replace(";###", ";<br />", str_replace("'NULL'", "NULL", implode(";###", $result))).";"))."\n";
                        }
                        $return = str_replace("'0000-00-00 00:00:00'", "now()", $return);
                        if ($syntaxe) { //$return = utf8_decode($return);
                        }
                    }
                    $return .= "--FIN ".str_replace($originalPrefixe, $targetPrefixe, $table)." ".$action."\n\n";
                }
            }
        }

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $value __DESC__
     *
     * @return __TYPE__
     */
    public function cleanDate($value)
    {
        $return = $value;
        if ($return) {
            $return = str_replace(array("00/00/0000 00:00:00", "00/00/0000 00:00", "00/00/0000"), ":DATE_COURANTE", $return);
        }

        return $return;
    }
}

/*
 * todo
 * :build-all : generate, populate
 * :build-all-load : + datas
 * :build-db : create db  from model
 * :build-sql
 * :data-dump
 * :data-load
 * :generate-migratio
 * :generate-migrations-db
 * :migrate
 */
