<?php
/**
 * Gestion des listes avec tri, pagination et filtres automatiques
 *
 * @package Pelican
 * @subpackage List
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
pelican_import('List.Row');

/**
 * Cette classe est utilisée pour créer des tableaux HTML.
 *
 * Le contenu de ces tableaux peut être issu d'une requête SQL ou
 * d'un tableau de données (au même format que celui issu de la méthode
 * queryTab de la classe database).
 * Il est possible de gérer :
 * - les filtres (par champ texte ou combo)
 * - la pagination
 * - les tris
 * - les ordres
 * - les regroupements
 *
 * NB : dans le reste de la documentation le terme "Champ" indique un champ de
 * base de données, issue d'une table ou d'une requête
 *
 * @package Pelican
 * @subpackage List
 * @author Patrick Deroubaix <pderoubaix@businessdecision.com>
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @author Jean-Baptiste Ruscassie <jbruscassie@businessdecision.com>
 * @author Laurent Franchomme <lfranchomme@businessdecision.com>
 * @since 15/05/2003
 * @version 2.0
 */
class Pelican_List {
    
    /**
     * Tableau des couleurs de fonds à utiliser au format array(CHAMP,BGCOLOR)
     *
     * @access public
     * @var mixed
     */
    public $aRowBgcolor;
    
    /**
     * Tableau des classes CSS à utiliser pour l'alternance des lignes de tableau
     *
     * @access public
     * @var mixed
     */
    public $aRowCSS;
    
    /**
     * Structure du tableau à créer
     *
     * @access public
     * @var mixed
     */
    public $aTableStructure = array(array());
    
    /**
     * Valeurs des champs du tableau (tableau issu d'une requête ou utilisé tel quel)
     *
     * @access public
     * @var mixed
     */
    public $aTableValues;
    
    /**
     * Lignes rajoutées manuellement au tableau (avant ou après l'entête, avant ou
     * après le pied de page)
     *
     * @access public
     * @var mixed
     */
    public $aTableAddedRows;
    
    /**
     * Paramètres permettant la mise à jour générique des ordres de champ en base
     * de données
     *
     * @access public
     * @var mixed
     */
    public $aTableOrderParams;
    
    /**
     * Affichage ou non des entêtes du tableau
     *
     * @access public
     * @var bool
     */
    public $bTableHeaders;
    
    /**
     * Affichage ou non de la pagination du tableau (cf propriétés navMaxLinks et
     * navLimitRows)
     *
     * @access public
     * @var bool
     */
    public $bTablePages;
    
    /**
     * Affichage ou non de la pagination du tableau en haut
     *
     * @access public
     * @var bool
     */
    public $bTableHeaderPages;
    
    /**
     * Attribut BORDER du tableau
     *
     * @access public
     * @var int
     */
    public $iTableBorder;
    
    /**
     * Attribut CELLPADDING du tableau
     *
     * @access public
     * @var int
     */
    public $iTableCellpadding;
    
    /**
     * Attribut CELLSPACING du tableau
     *
     * @access public
     * @var int
     */
    public $iTableCellspacing;
    
    /**
     * Nombre de lignes du tableau
     *
     * @access public
     * @var int
     */
    public $iTableRows;
    
    /**
     * Attribut CLASS du tableau
     *
     * @access public
     * @var string
     */
    public $sTableClass;
    
    /**
     * Attribut ID du tableau
     *
     * @access public
     * @var string
     */
    public $sTableId;
    
    /**
     * Attribut STYLE du tableau
     *
     * @access public
     * @var string
     */
    public $sTableStyle;
    
    /**
     * Attribut WIDTH du tableau
     *
     * @access public
     * @var string
     */
    public $sTableWidth = 0;
    
    /**
     * Taille totale d'une ligne (cumul des propriétés width des colonnes)
     *
     * @access public
     * @var int
     */
    public $iRowWidth;
    
    /**
     * Nom du champ contenant la valeur à utiliser avec le tableau aRowBgcolor pour
     * déterminer la couleur de fond d'une ligne
     *
     * @access public
     * @var string
     */
    public $sBgColorField;
    
    /**
     * Nom du champ contenant la valeur à utiliser avec le tableau aRowCSS pour
     * déterminer la classe CSS d'une ligne
     *
     * @access public
     * @var string
     */
    public $sCssField;
    
    /**
     * Nom du(des) champ(s) contenant(s) la valeur à utiliser pour faire des
     * regroupements de valeurs dans les lignes
     *
     * @access public
     * @var mixed
     */
    public $aGroupeField;
    
    /**
     * Classe css des lignes de regroupements
     *
     * @access public
     * @var mixed
     */
    public $cssGroupe = "tblgroupe";
    
    /**
     * Tableau de définition des filtres automatiques
     *
     * @access public
     * @var mixed
     */
    public $aFilter;
    
    /**
     * Tableau de définition du code Pelican_Html généré pour chaque filtre
     * automatique
     *
     * @access public
     * @var mixed
     */
    public $aFilterHTML = array();
    
    /**
     * Indique si des filtres automatiques ont été générés
     *
     * @access public
     * @var bool
     */
    public $bFiltered = false;
    
    /**
     * Tableau récapitulatif des couples CHAMP<->VALEUR utilisés par les filtres
     * automatiques
     *
     * @access public
     * @var mixed
     */
    public $aFilterSummary = array();
    
    /**
     * Variable de pagination : nombre total de lignes
     *
     * @access public
     * @var string
     */
    public $navRows;
    
    /**
     * Variable de pagination : numéro de la page en cours de consultation
     *
     * @access public
     * @var string
     */
    public $navPage = 1;
    
    /**
     * Variable de pagination : nombre maximum de lignes à afficher par pages
     *
     * @access public
     * @var string
     */
    public $navLimitRows;
    
    /**
     * Variable de pagination : nombre maximum de numéros de pages à afficher par
     * pages
     *
     * @access public
     * @var string
     */
    public $navMaxLinks;
    
    /**
     * Variable de pagination : Numéro de plage de pages en cours d'affichage
     *
     * @access public
     * @var string
     */
    public $navFirstPage = 1;
    
    /**
     * Variable de pagination : Numéro de la première ligne en cours d'affichage
     *
     * @access public
     * @var string
     */
    public $navMinRow;
    
    /**
     * Variable de pagination : Numéro de la dernière ligne en cours d'affichage
     *
     * @access public
     * @var string
     */
    public $navMaxRow;
    
    /**
     * Variable de pagination : Préfixe à utiliser dans les variables $_GET de
     * pargination (issu de l'ID de l'objet). Cela permet de naviguer dans plusieurs
     * objets Pelican_List sur le même écran
     *
     * @access public
     * @var string
     */
    public $sNavPrefixe = "";
    
    /**
     * Variable de pagination : Class css de la Pelican_Index_Frontoffice_Zone de navigation
     *
     * @access public
     * @var string
     */
    public $sNavClass = "tblfooter";
    
    /**
     * Tableau d'agregation des différentes descriptions de chaque ligne (HTML,
     * groupe, etc...)
     *
     * @access public
     * @var mixed
     */
    public $aLines;
    
    /**
     * Indique si l'on utilise une classe spécifique d'affichage pour les entêtes des
     * colonnes triées (il faut donc qu'une classe $sHeaderClass."On" existe)
     *
     * @access public
     * @var bool
     */
    public $bOrderClass = true;
    
    /**
     * Répertoire des librairies issu de la variable Pelican::$config["LIB_PATH"]
     *
     * @access private
     * @var string
     */
    public $_sLibPath = "";
    
    /**
     * Tableau des événements javascript associés aux lignes au format
     * array(EVENT,FONCTION,ATTRIBUTS)
     *
     * @access public
     * @var mixed
     */
    public $aRowEvent = array();
    
    /**
     * Nombre des colonnes créées
     *
     * @access public
     * @var int
     */
    public $iNbColumn = 1;
    
    /**
     * Variable stockant la requête finale utilisée pour la génération de l'objet
     * après prise en compte des filtres et tris
     *
     * @access public
     * @var string
     */
    public $sUsedQuery = "";
    
    /**
     * Pour la génération de fichier Excel : Affichage ou non des filtres
     * automatiques dnas Excel (via du XML Office)
     *
     * @access public
     * @var bool
     */
    public $excelAutoFilter;
    
    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $currentFilterTab;
    
    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $aFilterTab = array();
    
    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $oFilterForm;
    
    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $aGroupBy = array();
    protected $sContextCssField;
    protected $css;
    protected $_sLibList;
    protected $filterDirect;
    protected $filterForm;
    protected $isOrder;
    
    /**
     * Constructeur
     *
     * Définition de l'id, de la largeur, des espacements de cellule, de la bordure
     * et de l'affichage ou non des entêtes et pieds de page
     * <code>
     * Pelican_Factory::getInstance('List',"id1", "", 0, 0, 0, "liste");
     * $table->setCSS(array("tblaltb", "confidentiel"), "tache_prive", $tabBgColor,
     * "etat_id");
     * $table->setValues($strSqlList, "table.field_id");
     * $table->addColumn("ID", "field_id", "1", "left", "", "tblheader");
     * $table->addInput("Consulter", "button", array("id" => "field_id", "" =>
     * "readO=true"), "center");
     * echo($table->getTable());
     * </code>
     *
     * @access public
     * @param string $sTableId (option) Attribut ID du tableau (obligatoire en cas de
     * tables multiples dans la page, pour différencier la navigation au sein des
     * tableaux) : "" par défaut
     * @param string $sTableWidth (option) Attribut WIDTH du tableau
     * @param int $iTableCellpadding (option) Attribut CELLPADDING du tableau
     * @param int $iTableCellspacing (option) Attribut CELLSPACING du tableau
     * @param string $iTableBorder (option) __DESC__
     * @param string $sTableClass (option) Attribut CLASS du tableau
     * @param string $sTableStyle (option) Attribut STYLE du tableau
     * @param bool $bTablePages (option) Affichage ou non de la pagination du tableau
     * (cf propriétés navMaxLinks et navLimitRows) : true par défaut
     * @param bool $bTableHeaders (option) Affichage ou non des entêtes du tableau
     * @param bool $bTableHeaderPages (option) Affichage ou non de la pagination du
     * tableau en haut
     * @return Pelican_List
     */
    public function Pelican_List($sTableId = "", $sTableWidth = "", $iTableCellpadding = 0, $iTableCellspacing = 0, $iTableBorder = 0, $sTableClass = "", $sTableStyle = "", $bTablePages = true, $bTableHeaders = true, $bTableHeaderPages = false) {
        global $nbTable;
        $this->sTableId = $sTableId; //($sTableId?$sTableId:"tbl".(++$nbTable));
        $this->sTableWidth = $sTableWidth;
        $this->iTableCellpadding = $iTableCellpadding;
        $this->iTableCellspacing = $iTableCellspacing;
        $this->iTableBorder = $iTableBorder;
        $this->sTableClass = $sTableClass;
        $this->sTableStyle = $sTableStyle;
        $this->bTableHeaders = $bTableHeaders;
        $this->bTablePages = $bTablePages;
        $this->bTableHeaderPages = $bTableHeaderPages;
        $this->_sLibPath = Pelican::$config["LIB_PATH"];
        $this->_sLibList = Pelican::$config['LIB_LIST'];
        $this->navLimitRows = Pelican::$config["LIST_LIMIT_ROWS"];
        $this->navMaxLinks = Pelican::$config["LIST_MAX_LINKS"];
        $this->bOrderClass = Pelican::$config["LIST_USE_ORDER_CLASS"];
        if ($this->sTableId) $this->sNavPrefixe = "_" . $this->sTableId;
    }
    
    /**
     * Génération du code Pelican_Html complet du tableau
     *
     * @access public
     * @param bool $groupby (option) Crée une combo GROUPBY
     * @param booblean $bNoRecordText (option) Force l'affichage du tableau même s'il
     * n'y a pas d'enregistrement
     * @param __TYPE__ $aBind (option) __DESC__
     * @return string
     */
    public function getTable($groupby = false, $bNoRecordText = true, $aBind = array()) {
        $html_body = "";
        $pixel = Pelican_Html::img(array(src => $this->_sLibPath . '/public/images/pixel.gif', width => 1, height => 1, alt => ' ', border => '0'));
        $oConnection = Pelican_Db::getInstance();
        if ($this->aTableValues || $bNoRecordText == false) {
            $html_css = "";
            $html = "";
            $tagHeader = "th";
            $bSomme = false;
            // ajout des lignes additionnelles 'top'
            if ($this->aTableAddedRows["top"]) {
                foreach($this->aTableAddedRows["top"] as $row) {
                    $html.= $row;
                }
            }
            // Création de la variable de définition du tableau
            $aRealTableStructure = array();
            //HEAD//
            // Pour chaque ligne définie (iRow) on construit les entêtes
            $thead = "";
            $countStructure = count($this->aTableStructure);
            if ($countStructure > 0) {
                for ($iRow = 0;$iRow < $countStructure;$iRow++) {
                    $theadtr = "";
                    foreach($this->aTableStructure[$iRow] as $header) {
                        // si la colonne doit être affichée (on exclu de la structure des colonnes les addHeader)
                        // pour l'affichage des colonnes on construit un tableau respectant l'ordre d'appel des colonnes (propriété iNumColumn)
                        if ($header->iNumColumn) {
                            $aRealTableStructure[$header->iNumColumn] = $header;
                        }
                        $label = array();
                        $label[] = $header->sHeaderLabel;
                        foreach($label as $entete) {
                            // si le libellé est un tableau ("image"=>,"label"=>) alors on remplace le libellé par l'image
                            if (is_array($entete)) {
                                $entete = Pelican_Html::img(array(src => $entete["image"], alt => $entete["label"], border => 0));
                            }
                            if ($this->bTableHeaders) {
                                // si le libellé est défini on met un tag d'en tête sinon c'est un td (à cause des feuilles de style)
                                if ($header->sHeaderLabel) {
                                    $typeTag = $tagHeader;
                                } else {
                                    $typeTag = "td";
                                }
                                $this->isOrder = false;
                                if ($header->sColumnType != "image") {
                                    $orderSign = $this->setOrder($entete, $header->sColumnOrderField);
                                } else {
                                    $orderSign = $entete;
                                }
                                $attr = array(colspan => $header->iColSpan, rowspan => $header->iRowSpan, 'class' => $header->sHeaderClass . ($this->isOrder ? "On" : ""), width => $this->setColWidth($header->sColumnWidth), 'more_attr' => $this->getExcelParams());
                                $args = $orderSign;
                                if ($typeTag == "td") {
                                    $theadtr.= Pelican_Html::td($attr, $args);
                                } else {
                                    $theadtr.= Pelican_Html::th($attr, $args);
                                }
                            } else {
                                $theadtr.= Pelican_Html::td(array('class' => 'tblEmptyHeader', colspan => (($header->iColSpan == 1) ? 0 : $header->iColSpan), rowspan => (($header->iRowSpan == 1) ? 0 : $header->iRowSpan), width => $this->setColWidth($header->sColumnWidth), height => 1), $pixel);
                            }
                        }
                    }
                    if ($this->aTableOrderParams) {
                        $theadtr.= Pelican_Html::td(array('class' => "tblOrder"), $pixel);
                        $theadtr.= Pelican_Html::td(array('class' => "tblEmptyHeader"), $pixel);
                    }
                    $thead.= Pelican_Html::tr($theadtr);
                }
            }
            ksort($aRealTableStructure);
            if ($this->navRows && $this->bTableHeaderPages) {
                $theadtr = Pelican_Html::td(array(valign => 'middle', colspan => count($aRealTableStructure)), $this->getPages());
                $thead = Pelican_Html::tr(array(), $theadtr) . $thead;
            }
            $html.= Pelican_Html::thead($thead);
            //FOOT//
            // Affichage des pieds de page
            // le tfoot est défini avant le tbody pour accélérer l'affichage (principe des thead, tfoot et tbody)
            if ($this->navRows && $this->bTablePages) {
                $tfoottr = Pelican_Html::td(array(valign => 'middle', colspan => count($aRealTableStructure)), $this->getPages("", "", "|", "", "", "", $groupby));
                $tfoot = Pelican_Html::tr(array(), $tfoottr);
                $html.= Pelican_Html::tfoot($tfoot);
            }
            //BODY//
            // ajout des lignes additionnelles 'afterHeader'
            if ($this->aTableAddedRows["afterHeader"]) {
                foreach($this->aTableAddedRows["afterHeader"] as $row) {
                    $html_body.= $row;
                }
            }
            // Pour chaque ligne on créee le Pelican_Html adéquat et on l'agrège au niveau des champs de regroupement (vide si pas défini)
            $lineId = 0;
            $idTr = 0;
            if ($this->aTableValues) {
                foreach($this->aTableValues as $values) {
                    $lineId++;
                    // Regroupements
                    if ($this->aGroupeField) {
                        $groupe = array();
                        $groupe[0] = (isset($values[$this->aGroupeField[0]]) ? $values[$this->aGroupeField[0]] : "");
                        if (isset($this->aGroupeField[1])) {
                            $groupe[1] = $values[$this->aGroupeField[1]];
                        } else {
                            $groupe[1] = "0";
                        }
                        $label = implode("#", $groupe);
                        $this->aLines[$groupe[0]]["label"] = $groupe[0];
                        $this->aLines[$groupe[0]][$groupe[1]]["label"] = $groupe[1];
                    } else {
                        $groupe[0] = "0";
                        $groupe[1] = "0";
                    }
                    if (!isset($this->aLines[$groupe[0]]["count"])) $this->aLines[$groupe[0]]["count"] = 0;
                    $this->aLines[$groupe[0]]["count"]++;
                    if (!isset($this->aLines[$groupe[0]][$groupe[1]]["count"])) $this->aLines[$groupe[0]][$groupe[1]]["count"] = 0;
                    $this->aLines[$groupe[0]][$groupe[1]]["count"]++;
                    if (!isset($values[$this->sCssField])) $values[$this->sCssField] = "";
                    if (!isset($values[$this->sContextCssField])) $values[$this->sContextCssField] = "";
                    $this->getCSS($this->aLines[$groupe[0]][$groupe[1]], $values[$this->sCssField], $values[$this->sContextCssField]);
                    // Ligne
                    $iSomme = 0;
                    $column_number = 0;
                    $tr_value = "";
                    foreach($aRealTableStructure as $column) {
                        $column_number++;
                        $id = $this->sTableId . "_" . $lineId . "_" . $column_number;
                        $bShow = self::showCol($column->aShow, $values);
                        // CELLULE
                        $td_value = "";
                        $widthImg = "";
                        $heightImg = "";
                        if (!isset($values[$column->sColumnField])) {
                            $values[$column->sColumnField] = '';
                        }
                        if ($bShow) {
                            switch ($column->sColumnType) {
                                case "input": {
                                            $td_value = self::makeInput($column, $values, $id);
                                        break;
                                    }
                                case "image": {
                                        $image = $this->setFormat($column->sColumnFormat, $values[$column->sColumnField]);
                                        if (!$image) {
                                            $image = $this->_sLibPath . "/public/images/pixel.gif";
                                        } else {
                                            if (is_array($column->aColumnAttributes)) {
                                                if ($column->aColumnAttributes["_function_"]) {
                                                    $sFunc = "";
                                                    reset($column->aColumnAttributes);
                                                    foreach($column->aColumnAttributes as $kFP => $vFP) {
                                                        $sFP = substr($kFP, 0, 22);
                                                        if ($sFP == "_function_param_field_") {
                                                            $sFunc.= (($sFunc) ? "," : "") . "\$values[\"" . $vFP . "\"]";
                                                        } elseif ($sFP == "_function_param_value_") {
                                                            $sFunc.= (($sFunc) ? "," : "") . $vFP;
                                                        }
                                                    }
                                                    $sFunc = "\$image = " . $column->aColumnAttributes["_function_"] . "(" . $sFunc . ");";
                                                    eval($sFunc);
                                                } elseif ($column->aColumnAttributes["_folder_"]) {
                                                    $image = $column->aColumnAttributes["_folder_"] . $image . "." . ($column->aColumnAttributes["_extension_"] ? $column->aColumnAttributes["_extension_"] : "gif");
                                                }
                                                $widthImg = (($column->aColumnAttributes["_width_"]) ? $column->aColumnAttributes["_width_"] : "");
                                                $heightImg = (($column->aColumnAttributes["_height_"]) ? $column->aColumnAttributes["_height_"] : "");
                                            } else {
                                                $image = $column->aColumnAttributes . $image . ".gif";
                                            }
                                        }
                                        $td_value = Pelican_Html::img(array(id => $id, src => $image, alt => $values[$column->sColumnOrderField], border => 0, align => "middle", width => $widthImg, height => $heightImg));
                                        break;
                                    }
                                case "multi": {
                                        $queryLimit = "";
                                        $limit = strpos(strToLower($column->aColumnAttributes), "limit ");
                                        if ($limit) {
                                            $queryLimit = $rest = substr($column->aColumnAttributes, $limit, strlen($column->aColumnAttributes));
                                            $column->aColumnAttributes = str_replace($queryLimit, "", $column->aColumnAttributes);
                                        }
                                        if (strpos(strToLower($column->aColumnAttributes), "where")) {
                                            $join = " AND ";
                                        } else {
                                            $join = " WHERE ";
                                        }
										if( is_numeric($values[$this->cleanOrder($column->sColumnField)])) {
                                        $query = $column->aColumnAttributes . $join . $column->sColumnField . "=" . $values[$this->cleanOrder($column->sColumnField) ] . " " . $queryLimit;
										} else {
											$query = $column->aColumnAttributes . $join . $column->sColumnField . "='" . $values[$this->cleanOrder($column->sColumnField) ] . "' " . $queryLimit;
                                        }
                                        $oConnection->query($query);
                                        $implode = "";
                                        if (valueExists($oConnection->data, "lib")) {
                                            if (strToLower($column->sColumnFormat) == "<br>" || strToLower($column->sColumnFormat) == "<br />") {
                                                $sep = Pelican_Html::br();
                                            } else {
                                                $sep = ", ";
                                            }
                                            $implode = implode($sep, $oConnection->data["lib"]);
                                        }
                                        $td_value = $implode;
                                        break;
                                    }
                                case "combo": {
                                        if ($column->sColumnFormat) {
                                            if (!$cmb) {
                                                $query = $column->aColumnAttributes["src"];
                                                $cmb = $oConnection->queryTab($query, $aBind);
                                            }
                                        } else {
                                            if (strpos(strToLower($column->aColumnAttributes["src"]), "where")) {
                                                $join = " AND ";
                                            } else {
                                                $join = " WHERE ";
                                            }
                                            $query = $column->aColumnAttributes["src"] . $join . $column->sColumnField . "=" . $values[$this->cleanOrder($column->sColumnField) ];
                                            $cmb = $oConnection->queryTab($query, $aBind);
                                        }
                                        if ($cmb) {
                                            $td_value = Pelican_Html::option(" ");
                                            foreach($cmb as $option) {
                                                $selected = false;
                                                if ($option["id"] == $values[$column->aColumnAttributes["selected"]]) {
                                                    $selected = true;
                                                }
                                                $td_value.= Pelican_Html::option(array(value => $option["id"], selected => $selected), Pelican_Text::htmlentities($option["lib"]));
                                            }
                                            $td_value = Pelican_Html::select(array(name => "combo" . $lineId, id => "combo" . $lineId, onchange => $column->aColumnAttributes["function"] . "(this,'" . $values[$this->cleanOrder($column->sColumnField) ] . "');"), $td_value);
                                        } else {
                                            $td_value = "&nbsp;";
                                        }
                                        break;
                                    }
                                default: {
                                        // Gestion du lien sur la valeur de la colonne
                                        if ($column->onClick != '') {
                                            // Transformation des ' en "
                                            $column->onClick = str_replace('\'', '"', $column->onClick);
                                            // Remplacement de la valeur du champ de la colonne
                                            $column->onClick = str_replace('[fieldValue]', $values[$column->sColumnField], $column->onClick);
                                            $td_value = trim($this->setFormat($column->sColumnFormat, '<a href=\'' . $column->onClick . '\'>' . $values[$column->sColumnField] . '</a>'));
                                        } else {
                                            $td_value = trim($this->setFormat($column->sColumnFormat, $values[$column->sColumnField]));
                                        }
                                        if ($column->sTooltip) {
                                            $td_value = Pelican_Html::a(array('class' => "tooltip", onmouseover => "return makeTrue(domTT_activate(this, event, 'content', '" . str_replace("'", "\'", htmlspecialchars(str_replace("\r\n", Pelican_Html::br(), "" . $values[$column->sTooltip] . ""))) . "', 'trail', true));"), $td_value);
                                        }
                                    }
                                }
                            } else {
                                $td_value = "&nbsp;";
                            }
                            $tr_value.= Pelican_Html::td(array(id => "td_" . $id, align => $column->sColumnAlign), $td_value);
                            //Somme
                            if (is_numeric($values[$column->sColumnField])) {
                                if (!isset($this->aLines[$groupe[0]][$groupe[1]]["somme"][$iSomme])) {
                                    $this->aLines[$groupe[0]][$groupe[1]]["somme"][$iSomme] = 0;
                                }
                                $this->aLines[$groupe[0]][$groupe[1]]["somme"][$iSomme]+= $values[$column->sColumnField] + 0;
                            }
                            if ($column->bColumnSum) {
                                $bSomme = true;
                                $this->aLines[$groupe[0]][$groupe[1]]["format"][$iSomme] = $column->sColumnFormat;
                                $this->aLines[$groupe[0]][$groupe[1]]["align"][$iSomme] = $column->sColumnAlign;
                            } else {
                                $this->aLines[$groupe[0]][$groupe[1]]["somme"][$iSomme] = "--";
                            }
                            $iSomme++;
                        }
                        if ($this->aTableOrderParams) {
                            $tr_value.= Pelican_Html::td(array('class' => "tblOrder"), Pelican_Html::img(array(src => $this->_sLibPath . $this->_sLibList . "/images/ordre_plus.gif", width => 12, height => 12, alt => "Descendre", border => 0, onclick => "listOrder('" . rawurlencode($values[$this->aTableOrderParams["id"]]) . "',1)", style => "cursor:pointer;")) . Pelican_Html::img(array(src => $this->_sLibPath . $this->_sLibList . "/images/ordre_moins.gif", width => 12, height => 12, alt => "Monter", border => 0, onclick => "listOrder('" . rawurlencode($values[$this->aTableOrderParams["id"]]) . "',-1)", style => "cursor:pointer;"))) . Pelican_Html::td(array('class' => "tblOrder"), $pixel);
                        }
                        // Evénement de ligne
                        $tr_event = "";
                        if ($this->aRowEvent) {
                            foreach($this->aRowEvent as $event) {
                                //        $event["attributes"]["_javascript_"] = $event["fonction"];
                                $fonction = $event["fonction"] . "(" . self::makeURL($event["attributes"], $values, "", true) . ")";
                                $tr_event.= $event["event"] . "=\"" . $fonction . ";\" ";
                                if ($event["style"]) {
                                    $tr_event.= " style=\"" . $event["style"] . "\"";
                                }
                            }
                        }
                        if (!isset($this->aLines[$groupe[0]][$groupe[1]]["html"])) $this->aLines[$groupe[0]][$groupe[1]]["html"] = "";
                        $idTr++;
                        $this->aLines[$groupe[0]][$groupe[1]]["html"].= Pelican_Html::tr(array(id => $this->sTableId . "tr_" . $idTr, 'class' => $this->aLines[$groupe[0]][$groupe[1]]["css"], 'more_attr' => $tr_event), $tr_value);
                        $this->aListTr[] = $this->sTableId . "_" . $idTr;
                    }
                }
                // affichage des lignes
                if ($this->aLines) {
                    reset($this->aLines);
                    $html_ligne = "";
                    foreach($this->aLines as $Groupe) {
                        if ($Groupe["label"] && count($Groupe) > 1) {
                            if (!isset($Groupe["bgcolor"])) $Groupe["bgcolor"] = "";
                            $html_td = Pelican_Html::td(array(valign => "middle", colspan => count($aRealTableStructure), bgcolor => $Groupe["bgcolor"]), Pelican_Html::nobr($Groupe["label"] . "&nbsp;&nbsp;[" . $Groupe["count"] . "]"));
                            if ($this->aTableOrderParams) {
                                $html_td.= Pelican_Html::td(array('class' => "tblOrder", colspan => 2), $pixel);
                            }
                            $html_ligne.= Pelican_Html::tr(array('class' => $this->cssGroupe), $html_td);
                        }
                        foreach($Groupe as $lines) {
                            if (count($lines) > 1) {
                                if ($lines["label"]) {
                                    $html_td = Pelican_Html::td(array(valign => "middle", colspan => count($aRealTableStructure), bgcolor => $Groupe["bgcolor"]), Pelican_Html::nobr("&nbsp;&nbsp;" . $lines["label"] . "&nbsp;&nbsp;[" . $lines["count"] . "]" . $moyenne));
                                    if ($this->aTableOrderParams) {
                                        $html_td.= Pelican_Html::td(array('class' => "tblOrder", colspan => 2), $pixel);
                                    }
                                    $html_ligne.= Pelican_Html::tr(array('class' => $this->cssGroupe), $html_td);
                                }
                                $html_ligne.= $lines["html"];
                                //affichage de la somme
                                $html_td = "";
                                if ($bSomme) {
                                    $countSomme = count($lines["somme"]);
                                    for ($i = 0;$i < $countSomme;$i++) {
                                        if ($lines["somme"][$i] != "--" || !$lines["somme"][$i]) {
                                            $html_td.= Pelican_Html::td(array('class' => "tblTotal", align => $lines["align"][$i]), $this->setFormat($lines["format"][$i], $lines["somme"][$i]));
                                        } else {
                                            $html_td.= Pelican_Html::td("&nbsp;");
                                        }
                                    }
                                    if ($this->aTableOrderParams) {
                                        $html_td.= Pelican_Html::td(array('class' => "tblOrder", colspan => 2), $pixel);
                                    }
                                    $html_ligne.= Pelican_Html::tr($html_td);
                                }
                            }
                        }
                    }
                    $html_body.= $html_ligne;
                }
                // ajout des lignes additionnelles 'beforeNavRows'
                if ($this->aTableAddedRows["beforeNavRows"]) {
                    foreach($this->aTableAddedRows["beforeNavRows"] as $row) {
                        $html_body.= $row;
                    }
                }
                // ajout des lignes additionnelles 'bottom'
                if ($this->aTableAddedRows["bottom"]) {
                    foreach($this->aTableAddedRows["bottom"] as $row) {
                        $html_body.= $row;
                    }
                }
                $html_body = Pelican_Html::tbody(array(id => $this->sTableId . "tbody"), $html_body);
                $html = $html_css . Pelican_Html::table(array(id => $this->sTableId, width => $this->sTableWidth, cellpadding => $this->iTableCellpadding, cellspacing => $this->iTableCellspacing, border => $this->iTableBorder, 'class' => $this->sTableClass, style => $this->sTableStyle, summary => "Données"), $html . $html_body);
            } else {
                if ($this->bTableHeaders) {
                    $html = Pelican_Html::br() . Pelican_Html::br();
                }
                $html = Pelican_Html::div(array('class' => "erreur"), t('TABLE_NO_RECORD'));
            }
            if ($this->bFiltered) {
                $html.= $this->getFilterForm();
            }
            // Flèches de définition des ordres
            $_SESSION["listorder"] = array();
            if ($this->aTableOrderParams) {
                $_SESSION["listorder"]["table"] = $this->aTableOrderParams["table"];
                $_SESSION["listorder"]["order"] = $this->aTableOrderParams["order"];
                $_SESSION["listorder"]["id"] = $this->aTableOrderParams["id"];
                $_SESSION["listorder"]["parent"] = $this->aTableOrderParams["parent"];
                $_SESSION["listorder"]["parentIsNeeded"] = $this->aTableOrderParams["parentIsNeeded"];
                $_SESSION["listorder"]["complementWhere"] = $this->aTableOrderParams["complementWhere"];
                $_SESSION["listorder"]["decache"] = $this->aTableOrderParams["decache"];
                $_SESSION["listorder"]["retour"] = $_SERVER["REQUEST_URI"];
                $html.= Pelican_Html::jscript("function listOrder(id,ordre) {
					window.document.location.href='" . $this->_sLibPath . $this->_sLibList . "/order.php?id=' + id + '&sens=' + ordre;
					}");
            }
            $html = str_replace(" colspan=\"0\"", "", $html);
            $html = str_replace(" rowspan=\"0\"", "", $html);
            if ($this->filterForm) {
                $html = $this->filterForm . '<br />' . $html;
            }
            return $html;
        }
        
        /**
         * __DESC__
         *
         * @access public
         * @return __TYPE__
         */
        public function reset() {
            $this->aLines = array();
            $this->iTableRows = 0;
            $this->aTableValues = array();
        }
        
        /**
         * Crée automatiquement les définition de colonnes associées aux champs
         * retournés par la requête passée en paramètre.
         *
         * Pour avoir des entêtes de colonnes avec des libellés clairs,
         * il faut que ces libellés soient les alias de colonnes dans la requête
         *
         * <code>
         * $strSqlList  = "select
         * CONCAT(machine_libelle,' (',role_machine_libelle,')') Machine,
         * site_virtuel \"Répertoire virtuel\"
         * from site
         * inner join machine on (machine.machine_id=site.machine_id)
         * inner join role_machine on
         * (role_machine.role_machine_id=machine.role_machine_id)";
         * $id='SITE_ID';
         * $groupe="Machine";
         * $table = Pelican_Factory::getInstance('List',"", "", 0, 0, 0, "liste");
         * $table->quickTable($strSqlList, $id, $groupe, array("tblalt1", "tblalt2"));
         * echo($table->getTable());
         * </code>
         *
         * @access public
         * @param mixed $aTableValues Chaine SQL SELECT ou tableau de données au format
         * du queryTab de la classe Pelican_Db
         * @param string $sFieldId (option) Champ ou expression GROUP BY à utiliser pour
         * le comptage des enregistrements
         * @param string $aGroupeField (option) Nom du champ contenant la valeur à
         * utiliser pour faire des regroupements de valeurs dans le tableau
         * @param mixed $aRowCSS (option) Tableau des classes CSS à utiliser pour
         * l'alternance des lignes de tableau
         * @param __TYPE__ $aStopList (option) __DESC__
         * @return void
         */
        public function quickTable($aTableValues, $sFieldId = "", $aGroupeField = "", $aRowCSS = "", $aStopList = array()) {
            $oConnection = Pelican_Db::getInstance();
            if ($aRowCSS) {
                $this->setCSS($aRowCSS);
            }
            $this->setValues($aTableValues, $sFieldId, $aGroupeField);
            if ($aTableValues) {
                foreach($aTableValues[0] as $field => $value) {
                    if ($field != $aGroupeField && !in_array($field, $aStopList)) {
                        //       $len = str_replace("255", "50", $oConnection->len[$key]);
                        $this->addColumn($field, $field, "10", "", "", "tblheader", $field);
                    }
                }
            }
            return true;
        }
        
        /**
         * Ajout d'un entête uniquement, positionné avec les paramètres $iLigne,
         * $iColSpan, $iRowSpan
         *
         * - Définition des paramètres de mise en page (header, width, align, class)
         * - Définition des rowspan et colspan des entêtes
         *
         * @access public
         * @param string $header Libellé de la colonne
         * @param string $width (option) Largeur (relative) de la colonne
         * @param string $align (option) Attribut ALIGN de l'entête de colonne
         * @param string $class (option) Attribut CLASS de l'entête de colonne
         * @param int $iLigne (option) Numero de ligne de l'entête. Chaque ligne
         * correspond a une ligne sur le tableau d'entête.
         * @param int $iColSpan (option) Colspan de l'entête. Une colonne avec un colspan
         * > 1 ne sera pas prise ne compte dans l'affichage de la liste (= C'est une
         * entête pour les colonnes en-dessous).
         * @param int $iRowSpan (option) Rowspan de l'entête
         * @return void
         */
        public function addHeader($header, $width = "", $align = "", $class = "tblheader", $iLigne = 0, $iColSpan = 1, $iRowSpan = 1) {
            //if ($width) $this->iRowWidth[$iLigne] += str_replace("px", "", $width);
            $this->aTableStructure[$iLigne][] = Pelican_Factory::getInstance('List.Row', $header, -2, $width, $align, "", $class, "", "", "", "", "", "", $iColSpan, $iRowSpan, $this->iNbColumn);
        }
        
        /**
         * Ajout d'une colonne au tableau :
         *
         * - Définition des paramètres de mise en page (header, width, align, class)
         * - Définition des éléments liés aux données (field, order, format)
         * - Définition de compléments (sum)
         * - Définition des rowspan et colspan des entêtes
         *
         * <code>
         * $table->addColumn("Montant", "field", "30", "left", " ", "tblheader",
         * "field", true, "");
         * </code>
         *
         * @access public
         * @param string $header Libellé de la colonne
         * @param string $field Champ du tableau de valeurs à utiliser pour le
         * remplissage des cellules
         * @param string $width (option) Largeur (relative) de la colonne
         * @param string $align (option) Attribut ALIGN de chaque cellule
         * @param string $format (option) Attribut de formattage de la valeur de la
         * cellule (cf setFormat) : effectue des formattage pour
         * "%","j->h","h->j","email","number", sinon est concaténé avec la valeur de la
         * cellule (exemple : " ")
         * @param string $class (option) Attribut CLASS de l'entête de colonne
         * @param string $order (option) Champ du tableau sur lequel effectuer un tri
         * (s'il n'est pas défini, le tri ne sera pas actif sur cette colonne)
         * @param bool $sum (option) Affichage ou non en bas de tableau de la somme de la
         * colonne si les données sont numériques
         * @param string $tooltip (option) Champ contenant un texte à afficher en bulle
         * d'aide
         * @param int $iLigne (option) Numero de ligne de l'entête. Chaque ligne
         * correspond a une ligne sur le tableau d'entête.
         * @param int $iColSpan (option) Colspan de l'entête. Une colonne avec un colspan
         * > 1 ne sera pas prise ne compte dans l'affichage de la liste (= C'est une
         * entête pour les colonnes en-dessous).
         * @param int $iRowSpan (option) Rowspan de l'entête
         * @return void
         */
        public function addColumn($header, $field, $width = "", $align = "", $format = "", $class = "tblheader", $order = "", $sum = false, $tooltip = "", $iLigne = 0, $iColSpan = 1, $iRowSpan = 1) {
            if ($width) {
                if (!isset($this->iRowWidth[$iLigne])) {
                    $this->iRowWidth[$iLigne] = 0;
                }
                $this->iRowWidth[$iLigne]+= str_replace("px", "", $width);
            }
            $this->aTableStructure[$iLigne][] = Pelican_Factory::getInstance('List.Row', $header, $field, $width, $align, $format, $class, $order, "text", "", "", $sum, $tooltip, $iColSpan, $iRowSpan, $this->iNbColumn);
            $this->iNbColumn++;
            $this->aGroupBy[($order ? $order : $field) ] = $header;
        }
        
        /**
         * Ajout en une seule fois de plusieurs colonnes ayant la même présentation au
         * tableau :
         *
         * - Définition des paramètres de mise en page (header, width, align, class)
         * - Définition des éléments liés aux données (aColumns, bUseOrder, format)
         * - Définition de compléments (sum)
         * - Définition des rowspan et colspan des entêtes
         *
         * <code>
         * $table->addMultiColumn($aColDef, "95", "left", "", "tblheader", true);
         * </code>
         *
         * pour un tableau croisé :
         * <code>
         * $tabCroise=$oConnection->queryPivot($strSqlList, "langue_libelle",
         * "etat_libelle");
         * $table->setValues($tabCroise["values"], "etat_id");
         * $table->addMultiColumn($tabCroise["columns"], "95", "left", "", "tblheader",
         * false);
         * </code>
         *
         * @access public
         * @param mixed $aColumns Tableau de couples LIBELLE DE COLONNE=>CHAMP equivalent
         * aux paramètres $header=>$field de addColumn
         * @param string $width (option) Largeur (relative) de la colonne
         * @param string $align (option) Attribut ALIGN de chaque cellule
         * @param string $format (option) Attribut de formattage de la valeur de la
         * cellule (cf setFormat) : effectue des formattage pour
         * "%","j->h","h->j","email","number", sinon est concaténé avec la valeur de la
         * cellule (exemple : " ")
         * @param string $class (option) Attribut CLASS de l'entête de colonne
         * @param string $bUseOrder (option) Utrilisation ou non des champs pour effectuer
         * des tris
         * @param bool $sum (option) Affichage ou non en bas de tableau de la somme de la
         * colonne si les données sont numériques
         * @param int $iLigne (option) Numero de ligne de l'entête. Chaque ligne
         * correspond a une ligne sur le tableau d'entête.
         * @param int $iColSpan (option) Colspan de l'entête. Une colonne avec un colspan
         * > 1 ne sera pas prise ne compte dans l'affichage de la liste (= C'est une
         * entête pour les colonnes en-dessous).
         * @param int $iRowSpan (option) Rowspan de l'entête
         * @return void
         */
        public function addMultiColumn($aColumns, $width = "", $align = "", $format = "", $class = "tblheader", $bUseOrder = true, $sum = false, $iLigne = 0, $iColSpan = 1, $iRowSpan = 1) {
            $order = "";
            if (is_array($aColumns)) {
                foreach($aColumns as $col) {
                    $header = $col;
                    $field = $col;
                    if ($bUseOrder) {
                        $order = $col;
                    }
                    if ($width) $this->iRowWidth[$iLigne]+= str_replace("px", "", $width);
                    $this->aTableStructure[$iLigne][] = Pelican_Factory::getInstance('List.Row', $header, $field, $width, $align, $format, $class, $order, "text", "", "", $sum, "", $iColSpan, $iRowSpan, $this->iNbColumn);
                    $this->iNbColumn++;
                    $this->aGroupBy[($order ? $order : $field) ] = $header;
                }
            }
        }
        
        /**
         * Ajoute une ligne au tableau
         *
         * Les positions possibles sont :
         * - "top"
         * - "afterHeader"
         * - "beforeNavRows"
         * - "bottom"
         *
         * @access public
         * @param string $sOuterTR Tag TR à rajouter
         * @param string $sPosition (option) Position de la ligne rajoutée
         * @return void
         */
        public function addRow($sOuterTR, $sPosition = 'top') {
            $this->aTableAddedRows[$sPosition][] = $sOuterTR;
        }
        
        /**
         * Ajout d'une colonne à valeurs multiples.
         *
         * - Définition des paramètres de mise en page (header, width, align, class)
         * - Définition des éléments liés aux données multiples ($fieldFilter,
         * $attributes, format, $order)
         * - Définition des rowspan et colspan des entêtes
         *
         * Une requête SQL est exécutée au niveau de chaque ligne et les résultats
         * sont agrégés suivant un paramètre de jonction ("," par défaut)
         *
         * <code>
         * $sSQLProfilValues = "select profil_id id, profil_libelle lib from user_profil
         * inner join profil on
         * (profil.profil_id=user_profil.profil_id)";
         * $table->addMulti("Profils", "user_id", "40", "left", "<br>", "tblheader", "",
         * $sSQLProfilValues);
         * </code>
         *
         * @access public
         * @param string $header Libellé de la colonne
         * @param string $fieldFilter Champ du tableau de valeurs à utiliser pour filtrer
         * la requête de la colonne multiple
         * @param string $width (option) Largeur (relative) de la colonne
         * @param string $align (option) Attribut ALIGN de chaque cellule
         * @param string $format (option) Chaine d'agregation "," par défaut entre les
         * valeurs retournées par la requête. une autre valeur possible est par exemple
         * "<br>"
         * @param string $class (option) Attribut CLASS de l'entête de colonne
         * @param string $order (option) Champ du tableau sur lequel effectuer un tri
         * (s'il n'est pas défini, le tri ne sera pas actif sur cette colonne)
         * @param mixed $attributes (option) Chaine SQL à exécuter à chaque ligne
         * @param int $iLigne (option) Numero de ligne de l'entête. Chaque ligne
         * correspond a une ligne sur le tableau d'entête.
         * @param int $iColSpan (option) Colspan de l'entête. Une colonne avec un colspan
         * > 1 ne sera pas prise ne compte dans l'affichage de la liste (= C'est une
         * entête pour les colonnes en-dessous).
         * @param int $iRowSpan (option) Rowspan de l'entête
         * @return void
         */
        public function addMulti($header, $fieldFilter, $width = "", $align = "", $format = "", $class = "tblheader", $order = "", $attributes = "", $iLigne = 0, $iColSpan = 1, $iRowSpan = 1) {
            if ($width) $this->iRowWidth[$iLigne]+= str_replace("px", "", $width);
            $this->aTableStructure[$iLigne][] = Pelican_Factory::getInstance('List.Row', $header, $fieldFilter, $width, $align, $format, $class, $order, "multi", $attributes, "", false, false, $iColSpan, $iRowSpan, $this->iNbColumn);
            $this->iNbColumn++;
        }
        
        /**
         * Ajout d'une colonne avec listes déroulantes au tableau :
         *
         * - Définition des paramètres de mise en page (header, width, align, class)
         * - Définition des éléments liés aux données et à la liste déroulante
         * (fieldFilter, attributes, order, format)
         * - Définition de compléments (sum)
         * - Définition des rowspan et colspan des entêtes
         *
         * Les attributs possibles ($attributes=array()) :
         * - "src"=>"SQL" : Chaine SQL de remplissage de la liste
         * - "selected"=>"FIELD" : Champ contenant la valeur sélectionnée de la ligne
         * - "function"=>"FONCTION" : Nom de la fonction javascript à appeler sur
         * l'événement OnChange
         *
         * <code>
         * $table->addCombo("Délégation", "projet_id", "25", "left", "", "tblheader",
         * "delegation_id", array("src" => $strSQL, "selected" => "user_id", "function" =>
         * "changeUser"));
         * </code>
         *
         * @access public
         * @param string $header Libellé de la colonne
         * @param string $fieldFilter Champ à utiliser pour le filtrage de la Liste
         * déroulante au niveau de chaque ligne
         * @param string $width (option) Largeur (relative) de la colonne
         * @param string $align (option) Attribut ALIGN de chaque cellule
         * @param string $format (option) Attribut de formattage de la valeur de la
         * cellule (cf setFormat) : effectue des formattage pour
         * "%","j->h","h->j","email","number", sinon est concaténé avec la valeur de la
         * cellule (exemple : " ")
         * @param string $class (option) Attribut CLASS de l'entête de colonne
         * @param string $order (option) Champ du tableau sur lequel effectuer un tri
         * (s'il n'est pas défini, le tri ne sera pas actif sur cette colonne)
         * @param mixed $attributes (option) Attributs de la Liste déroulante
         * @param mixed $aShow (option) Tableau d'expressions du type "CHAMP=VALEUR" ou
         * "CHAMP!=VALEUR" qui doivent être remplies (si le paramètre est défini) pour
         * afficher un contenu dans la cellule
         * @param int $iLigne (option) Numero de ligne de l'entête. Chaque ligne
         * correspond a une ligne sur le tableau d'entête.
         * @param int $iColSpan (option) Colspan de l'entête. Une colonne avec un colspan
         * > 1 ne sera pas prise ne compte dans l'affichage de la liste (= C'est une
         * entête pour les colonnes en-dessous).
         * @param int $iRowSpan (option) Rowspan de l'entête
         * @return void
         */
        public function addCombo($header, $fieldFilter, $width = "", $align = "", $format = "", $class = "tblheader", $order = "", $attributes = "", $aShow = "", $iLigne = 0, $iColSpan = 1, $iRowSpan = 1) {
            if ($width) $this->iRowWidth[$iLigne]+= str_replace("px", "", $width);
            $this->aTableStructure[$iLigne][] = Pelican_Factory::getInstance('List.Row', $header, $fieldFilter, $width, $align, $format, $class, $order, "combo", $attributes, $aShow, false, false, $iColSpan, $iRowSpan, $this->iNbColumn);
            $this->iNbColumn++;
        }
        
        /**
         * Ajout d'une colonne avec un contrôle (tag INPUT) au tableau :
         *
         * - Définition des paramètres de mise en page (label, headerLabel, type, width,
         * align, class)
         * - Définition des éléments liés au contrôle (attributes, bUseOrder, aShow)
         * - Définition des rowspan et colspan des entêtes
         *
         * Attributs de base ($attributes=array()) => génère un href sur le contrôle
         * (type button) en utilisant l'url courante :
         * - "PARAM"=>"FIELD" : paramètre à ajouter ou remplace dans l'url avec la
         * valeur du champ de la ligne en cours
         * - "PARAM"=>"" : suppression, s'il existe, du paramètre de l'url
         * - ""=>"PARAM1=VALUE1&PARAM2=VALUE2" : ajout ou remplacement de paramètres
         * constants dans l'url
         *
         * Attributs possibles ($attributes=array()) :
         * - "_javascript_"=>"FONCTION" : fonction exécutée sur le onclick à la place
         * d'un href sur l'url. les paramètres passé à la fonctions respectent les
         * règles ci-dessus avec l'utilisation de "PARAM" en moins
         * - "_image_"=>"PATH" : Chemin d'une image à affiche à la place d'un boutton
         * (type button)
         * - "_target_"=>"TARGET" : attribut TARGET à utiliser sur l'action (type
         * button)
         * - "_span_"=>"SPAN" : Attribut CLASS qui indique qu'un tag SPAN doit être
         * utilisé à la place de tout autre input avec un attribut ONCLICK (même
         * comportement que ci-dessus)
         * - "_value_field_"=>"TARGET" : Champ contenant la valeur à attribuer au
         * contrôle (type checkbox)
         *
         * <code>
         * $table->addInput("Consultation", "button",
         * array("projet_id" => "projet_id", "_target_" => "_blank", "id" =>
         * "tache_id",
         * "" => "template=tache&popup=1", "param" => ""), "center");
         * </code>
         *
         * @access public
         * @param string $label Libellé du contrôle (d'un bouton par exemple)
         * @param string $type (option) Type du contrôle (button, text, checkbox, ...)
         * @param mixed $attributes (option) Tableau de paramétrage du contrôle
         * (éléments qui vont déterminer l'action qu'il egendre)
         * @param string $align (option) Attribut ALIGN de chaque cellule
         * @param mixed $aShow (option) Tableau d'expressions du type "CHAMP=VALEUR" ou
         * "CHAMP!=VALEUR" qui doivent être remplies (si le paramètre est défini) pour
         * afficher un contenu dans la cellule
         * @param string $class (option) Attribut CLASS de l'entête de colonne
         * @param int $iLigne (option) = 0 numero de ligne de l'entête. Chaque ligne
         * correspond a une ligne sur le tableau d'entête.
         * @param int $iColSpan (option) = 1 colspan de la case dans l'entête.
         * @param int $iRowSpan (option) = 1 rowspan de la case dans l'entête
         * @param string $headerLabel (option) Libellé de la colonne si nécessaire
         * (equivalent au $header de addColumn) : par défaut aucun entête n'est affiché
         * @return void
         */
        public function addInput($label, $type = "button", $attributes = "", $align = "", $aShow = "", $class = "tblheader", $iLigne = 0, $iColSpan = 1, $iRowSpan = 1, $headerLabel = "") {
            $this->aTableStructure[$iLigne][] = Pelican_Factory::getInstance('List.Row', $headerLabel, $label, "", $align, $type, $class, "", "input", $attributes, $aShow, false, false, $iColSpan, $iRowSpan, $this->iNbColumn);
            $this->iNbColumn++;
        }
        
        /**
         * Ajout d'une colonne avec des images.
         *
         * - Définition des paramètres de mise en page (header, width, align, class)
         * - Définition des éléments liés à l'image (attributes, alt, order)
         * - Définition des rowspan et colspan des entêtes
         *
         * Attributs supplémentaires possibles ($attributes=array()) :
         * - "_folder_"=>"PATH" : chemin absolu contenant les images à afficher
         * - "_function_"=>"FONCTION" : fonction exécutée sur le onclick
         * - "_function_param_field_X"=>"FIELD" : param X venant de la requête
         * - "_function_param_value_X"=>"VALUE" : param X constant
         * - "_width_"=>"VALUE" : largeur de l'image
         * - "_height_"=>"VALUE" : hauteur de l'image
         *
         * <code>
         * $table->addImage("Utilisateurs","/images/","profile",15,"center","","tblheader","profile");
         * </code>
         *
         * @access public
         * @param string $header Libellé de la colonne
         * @param mixed $attributes Tableau da paramétrage de l'image : si ce n'est pas
         * un tableau, par défaut c'est le chemin absolu contenant les images à afficher
         * @param string $alt Champ du tableau de valeurs à utiliser pour l'attribut ALT
         * de l'image
         * @param string $width (option) Largeur (relative) de la colonne
         * @param string $align (option) Attribut ALIGN de chaque cellule
         * @param string $format (option) Attribut de formattage de la valeur de la
         * cellule (cf setFormat) : effectue des formattage pour
         * "%","j->h","h->j","email","number", sinon est concaténé avec la valeur de la
         * cellule (exemple : " ")
         * @param string $class (option) Attribut CLASS de l'entête de colonne
         * @param string $order (option) Champ du tableau sur lequel effectuer un tri
         * (s'il n'est pas défini, le tri ne sera pas actif sur cette colonne)
         * @param int $iLigne (option) = 0 numero de ligne de l'entête. Chaque ligne
         * correspond a une ligne sur le tableau d'entête.
         * @param int $iColSpan (option) = 1 colspan de la case dans l'entête.
         * @param int $iRowSpan (option) = 1 rowspan de la case dans l'entête
         * @return void
         */
        public function addImage($header, $attributes = "/", $alt, $width = "", $align = "", $format = "", $class = "tblheader", $order = "", $iLigne = 0, $iColSpan = 1, $iRowSpan = 1) {
            if (!isset($this->iRowWidth[$iLigne])) {
                $this->iRowWidth[$iLigne] = 0;
            }
            if ($width) {
                $this->iRowWidth[$iLigne]+= str_replace("px", "", $width);
            }
            $this->aTableStructure[$iLigne][] = Pelican_Factory::getInstance('List.Row', $header, $alt, $width, $align, $format, $class, $order, "image", $attributes, "", false, false, $iColSpan, $iRowSpan, $this->iNbColumn);
            $this->iNbColumn++;
            $this->aGroupBy[$order] = $header;
        }
        
        /**
         * Création d'une propriété événementielle sur les lignes :
         *
         * Définition de l'évenement, de la fonction associée et des paramètres sur
         * le même principe que pour addInput
         *
         * Attributs de base ($attributes=array()) => génère un href sur le contrôle
         * (type button) en utilisant l'url courante :
         * - "PARAM"=>"FIELD" : paramètre à ajouter ou remplace dans l'url avec la
         * valeur du champ de la ligne en cours
         * - "PARAM"=>"" : suppression, s'il existe, du paramètre de l'url
         * - ""=>"PARAM1=VALUE1&PARAM2=VALUE2" : ajout ou remplacement de paramètres
         * constants dans l'url
         *
         * @access public
         * @param string $event Evenement javascript : onclick, onchange etc...
         * @param string $fonction Fonction à exécuter
         * @param mixed $attributes (option) Tableau de paramétrage du contrôle
         * (éléments qui vont déterminer l'action qu'il egendre)
         * @param string $style (option) __DESC__
         * @return void
         */
        public function addRowEvent($event, $fonction, $attributes = "", $style = "") {
            $this->aRowEvent[$event]["event"] = $event;
            $this->aRowEvent[$event]["fonction"] = $fonction;
            $this->aRowEvent[$event]["attributes"] = $attributes;
            $this->aRowEvent[$event]["style"] = $style;
        }
        
        /**
         * Retourne la classe CSS et/ou la couleur de fond pour une ligne donnée
         *
         * @access public
         * @param mixed $aValues Tableau de valeurs de l'objet List
         * @param string $sCssFieldValue (option) Indice du tableau des classes CSS à
         * utiliser
         * @param string $sBgColorFieldValue (option) Indice du tableau des couleurs de
         * fond à utiliser
         * @return void
         */
        public function getCSS(&$aValues, $sCssFieldValue = 0, $sBgColorFieldValue = 0) {
            $aValues["css"] = "";
            if ($this->sCssField) {
                $this->css = $this->aRowCSS[($sCssFieldValue ? 1 : 0) ];
            } else {
                $this->css = ($this->css == $this->aRowCSS[1] ? $this->aRowCSS[0] : $this->aRowCSS[1]);
            }
            $aValues["css"] = $this->css;
            if ($this->sBgColorField) {
                $aValues["bgcolor"] = $this->aRowBgcolor[$sBgColorFieldValue];
            } else {
                $aValues["bgcolor"] = "";
            }
        }
        
        /**
         * Définition du tableau des classes CSS à utiliser pourles lignes de l'objet
         * List
         *
         * @access public
         * @param mixed $aRowCSS Tableau des classes CSS à utiliser pour l'alternance des
         * lignes de tableau
         * @param string $sCssField (option) Nom du champ contenant la valeur à utiliser
         * avec le tableau aRowCSS pour déterminer la classe CSS d'une ligne
         * @param mixed $aRowBgcolor (option) Tableau des couleurs de fonds à utiliser au
         * format array(CHAMP,BGCOLOR)
         * @param string $sBgColorField (option) Nom du champ contenant la valeur à
         * utiliser avec le tableau aRowBgcolor pour déterminer la couleur de fond d'une
         * ligne
         * @return void
         */
        public function setCSS($aRowCSS, $sCssField = "", $aRowBgcolor = "", $sBgColorField = "") {
            $this->aRowCSS = $aRowCSS;
            $this->sCssField = $sCssField;
            $this->aRowBgcolor = $aRowBgcolor;
            $this->sBgColorField = $sBgColorField;
        }
        
        /**
         * Affichage d'éléments permettant de définir l'ordre des lignes entre elles en
         * base de données
         *
         * Des flèches Haut-Bas sont créées à droite du tableau. Le traitement se
         * fait en direct
         *
         * <code>
         * $table->setTableOrder("TABLE","CHAMP_ID","CHAMP_ORDRE");
         * </code>
         *
         * @access public
         * @param string $sTable Table à mettre à jour
         * @param string $sFieldId Champ identifiant (la valeur de ce champ pour chaque
         * ligne du tableau est utilisée en paramètre de la fonction de mise à jour de
         * l'ordre)
         * @param string $sFieldOrder Champ contenant l'information de classement
         * @param string $sFieldParent (option) Champ optionnel de limitation du tri (par
         * défaut l'ordre est recalculé pour tous les enregistrements, si ce paramètre
         * est défini il n'est recalculé que pour les enregistrement ayant la même
         * valeur dans ce champ "père")
         * @param string $sComplementWhere (option) Clause where complémentaire
         * @param mixed $aDecache (option) Tableau de fichiers à décacher si nécessaire
         *
         * @return void
         */
        public function setTableOrder($sTable, $sFieldId, $sFieldOrder, $sFieldParent = "", $sComplementWhere = "", $aDecache = "") {
            $this->aTableOrderParams = array("table" => $sTable, "id" => $sFieldId, "order" => $sFieldOrder, "parent" => $sFieldParent, "parentIsNeeded" => "", "complementWhere" => $sComplementWhere, "decache" => $aDecache);
        }
        
        /**
         * Création de l'attribut WIDTH d'une cellule
         *
         * 2 modes d'utilisation :
         * - le paramètres est numérique => défintion en %
         * - le paramètre est passé avec "px"
         *
         * @access public
         * @param string $width Largeur de la colonne (numérique ou avec "px")
         * @return string
         */
        public function setColWidth($width) {
            if ($width) {
                if (is_numeric($width) && $this->iRowWidth[0]) {
                    return (intval(($width / $this->iRowWidth[0]) * 100)) . "%";
                } else {
                    return str_replace("px", "", $width);
                }
            } else {
                return "";
            }
        }
        
        /**
         * Définition des valeurs à utiliser pour l'affichage du tableau
         *
         * Cela peut-être une chaine SQL ou un tableau de valeurs
         *
         * @access public
         * @param mixed $aTableValues Chaine SQL SELECT ou tableau de données au format
         * du queryTab de la classe Pelican_Db
         * @param string $sFieldId (option) Champ (Attention aux alias de table) ou
         * expression GROUP BY à utiliser pour le comptage des enregistrements
         * @param string $aGroupeField (option) Nom du champ contenant la valeur à
         * utiliser pour faire des regroupements de valeurs dans le tableau
         * @param mixed $aBind (option) Paramètres Bind de la requête
         * @return void
         */
        public function setValues($aTableValues, $sFieldId = "", $aGroupeField = "", $aBind = array()) {
            $oConnection = Pelican_Db::getInstance();
            if (!$aGroupeField && valueExists($_GET, "groupby")) {
                $aGroupeField = $_GET["groupby"];
            }
            if (!is_array($aGroupeField)) {
                $aGroupeField = array($aGroupeField);
            }
            $this->aGroupeField = $aGroupeField;
            if (is_array($aTableValues)) {
                $this->iTableRows = count($aTableValues);
            } elseif ($aTableValues) {
                // ajout des clauses de filtre
                $aTableValues = $this->includeFilterClause($aTableValues);
                $this->sUsedQuery = str_replace("\r", "", str_replace("\n", "", $aTableValues));
                // création de la requête de comptage
                $strSQLCount = $oConnection->getCountSQL($aTableValues, $sFieldId);
                $this->iTableRows = $oConnection->queryItem($strSQLCount, $aBind);
            }
            $this->initPages();
            if (is_array($aTableValues)) {
                $this->iTableRows = count($aTableValues);
                if ($this->bTablePages || $this->bTableHeaderPages) {
                    $this->aTableValues = $this->getLimitedArray($aTableValues);
                } else {
                    $this->aTableValues = $aTableValues;
                }
            } elseif ($aTableValues) {
                if ($this->iTableRows > 0) {
                    if ($this->bTablePages || $this->bTableHeaderPages) {
                        if (sizeof($aBind) > 0) {
                            $query = $oConnection->getLimitedSQL($aTableValues, $this->navMinRow, $this->navLimitRows, true, $aBind);
                        } else {
                            $query = $oConnection->getLimitedSQL($aTableValues, $this->navMinRow, $this->navLimitRows, true, $aBind);
                        }
                    } else {
                        $query = $aTableValues;
                    }
                    $this->aTableValues = $oConnection->queryTab($query, $aBind);
                }
            }
        }
        
        /**
         * __DESC__
         *
         * @access public
         * @param __TYPE__ $aValues __DESC__
         * @return __TYPE__
         */
        public function getLimitedArray($aValues) {
            $min = $this->navMinRow - 1;
            $max = $this->navLimitRows;
            $return = array_slice($aValues, $min, $max);
            return $return;
        }
        
        /**
         * Modification de la chaine SQL initiale pour inclure les clauses WHERE de
         * filtrage en fonction des filtres automatiques (valeurs passées en $_GET)
         *
         * @access public
         * @param string $SQL Chaine SQL intiale du tableau
         * @return string
         */
        public function includeFilterClause($SQL) {
            $oConnection = Pelican_Db::getInstance();
            $return = $SQL;
            reset($_GET);
            foreach($_GET as $name => $value) {
                if ($value === '0') {
                    $value = 'FAUXZERO';
                }
                if (valueExists($this->aFilter, $name)) {
                    if ($value && $this->aFilter[$name]["use"]) {
                        $tmp = array();
                        if (valueExists($this->aFilter[$name], "operateur")) {
                            foreach($this->aFilter[$name]["fields"] as $field) {
                                switch ($this->aFilter[$name]["type"]) {
                                    case "date":
                                    case "calendar": {
                                                $tmp[] = "(" . $field . " " . $this->aFilter[$name]["operateur"] . " " . $oConnection->dateStringToSql($value) . ")";
                                        }
                                        break;
                                    case "checkbox": {
                                            $value = explode("#", str_replace("##", "#", $value));
                                            array_shift($value);
                                            array_pop($value);
                                            $tmp[] = "(" . $field . " in (" . implode(",", $value) . "))";
                                        }
                                        break;
                                    default: {
                                            $tmp[] = "(" . $field . " " . $this->aFilter[$name]["operateur"] . " '" . $value . "')";
                                        }
                                        break;
                                    }
                                }
                            } else {
                                $clauseEgal = ($this->aFilter[$name]["use_combo"] ? "=" : " like ");
                                $clauseLike = ($this->aFilter[$name]["use_combo"] ? "" : "%");
                                $aValue = explode(" ", trim($value));
                                foreach($this->aFilter[$name]["fields"] as $field) {
                                    $tmp_and = array();
                                    $countValues = count($aValue);
                                    for ($i = 0;$i < $countValues;$i++) {
                                        $value = trim($aValue[$i]);
                                        if (get_magic_quotes_gpc()) {
                                            $value = stripslashes($value);
                                        }
                                        //Doubler les simple cote
                                        $value = str_replace("'", "''", $value);
                                        if ($value) {
                                            if (substr($value, 0, 2) == "\\" . "\"") {
                                                //									if ($value{0}.$value{1} == "\\"."\"" ) {
                                                do {
                                                    $i++;
                                                    $value.= " " . $aValue[$i];
                                                }
                                                while (($i < $countValues) && (substr($aValue[$i], -2) != "\\" . "\""));
                                                $value = str_replace("\\" . "\"", "", $value);
                                            }
                                            if (trim($clauseEgal) != "like") {
                                                $tmp_and[] = $field . $clauseEgal . "'" . $clauseLike . trim($value) . $clauseLike . "' ";
                                            } else {
                                                if (!is_array($field)) {
                                                    $tmp_and[] = "UPPER(" . $field . ")" . $clauseEgal . "UPPER('" . $clauseLike . trim($value) . $clauseLike . "') ";
                                                } else {
                                                    if ($field[1] == 'string') {
                                                        $tmp_and[] = "UPPER(" . $field . ")" . $clauseEgal . "UPPER('" . $clauseLike . trim($value) . $clauseLike . "') ";
                                                    } elseif ($field[1] == 'integer' && $value = (int)$value) {
                                                        $tmp_and[] = "" . $field[0] . " = '" . trim($value) . "' ";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    //modif AND remplacer par OR patrick 10-01-2005
                                    if ($tmp_and) {
                                        $tmp[] = "(" . implode(" OR ", $tmp_and) . ")";
                                    }
                                }
                            }
                            if ($tmp) {
                                $filterClause = "(" . implode(" OR ", $tmp) . ")";
                                $filtre = (isset($filtre) ? $filtre . " and " : " ") . $filterClause;
                            }
                        }
                    }
                }
                if (isset($filtre)) {
                    $haystack = strToLower($SQL);
                    $needle = "where";
                    $deb = 0;
                    while (strpos($haystack, $needle) !== false) {
                        $deb = strpos($haystack, $needle);
                        $haystack = substr($haystack, ($deb + 1));
                    }
                    // recherche de la position du where
                    //$deb = strpos(strToLower($SQL), "where");
                    // recherche des autres clauses après la clause where
                    $fin = $deb + $oConnection->arrayMin(array(strpos($haystack, "order by"), strpos($haystack, "group by"), strpos($haystack, "having")));
                    if (!$fin) {
                        $fin = strlen($SQL);
                    }
                    $where = "";
                    if ($deb) {
                        $where = substr($SQL, $deb + 6, $fin - $deb - 6);
                        // pour prendre en compte les cas où il y a une clause OR
                        $where = " where (" . $where . ")";
                    } else {
                        $deb = 0;
                    }
                    $SQL1 = substr($SQL, 0, ($deb ? $deb : $fin));
                    $SQL2 = substr($SQL, $fin, strlen($SQL) - $fin);
                    $where = ($where ? $where . " and " : " where ") . $filtre;
                    $return = $SQL1 . $where . $SQL2;
                }
                $return = str_replace('FAUXZERO', '0', $return);
                return $return;
            }
            
            /**
             * Initialisation de la pagination
             *
             * @access public
             * @return void
             */
            public function initPages() {
                $this->navRows = $this->iTableRows;
                if ($this->bTablePages || $this->bTableHeaderPages) {
                    if (isset($_GET["navPage" . $this->sNavPrefixe])) {
                        $this->navPage = $_GET["navPage" . $this->sNavPrefixe]; // initialisation de la page courante
                        
                    }
                    if (isset($_GET["navLimitRows" . $this->sNavPrefixe])) {
                        $this->navLimitRows = $_GET["navLimitRows" . $this->sNavPrefixe]; // definition du nombre de resultats à afficher par pages
                        
                    }
                    if (isset($_GET["navMaxLinks" . $this->sNavPrefixe])) {
                        $this->navMaxLinks = $_GET["navMaxLinks" . $this->sNavPrefixe]; // definition du nombre de liens à afficher dans la liste
                        
                    }
                    if (isset($_GET["navFirstPage" . $this->sNavPrefixe])) {
                        $this->navFirstPage = $_GET["navFirstPage" . $this->sNavPrefixe]; // initialisation du numero de la premiere page dans la lise des liens
                        
                    }
                    if ($this->navRows > 0) {
                        $this->navMinRow = ($this->navPage - 1) * $this->navLimitRows + 1;
                        $this->navMaxRow = min($this->navMinRow + $this->navLimitRows - 1, intval($this->navRows));
                    } else {
                        $this->navMinRow = 0;
                        $this->navMaxRow = 0;
                    }
                }
            }
            
            /**
             * Creation des liens de pagination
             *
             * @access public
             * @param string $txt_bloc_prec (option) Le texte à afficher pour le lien vers le
             * bloc de pages précédentes
             * @param string $txt_prec (option) Le texte à afficher pour le lien vers la page
             * précédente
             * @param string $txt_separateur (option) Le texte à afficher pour le séparateur
             * : "|" par défaut
             * @param string $txt_suiv (option) Le texte à afficher pour le lien vers la page
             * suivante
             * @param string $txt_bloc_suiv (option) Le texte à afficher pour le lien vers le
             * bloc de pages suivantes
             * @param string $url_class (option) La class des url
             * @param bool $groupby (option) __DESC__
             * @return string
             */
            public function getPages($txt_bloc_prec = "", $txt_prec = "", $txt_separateur = "|", $txt_suiv = "", $txt_bloc_suiv = "", $url_class = "", $groupby = false) {
                //$txt_separateur = '';
                //$txt_prec = t('TABLE_PAGE_PREV');
                //$txt_suiv = t('TABLE_PAGE_NEXT');
                $liste_links = "";
                $nb_pages = 0;
                if (!$txt_prec > "") $txt_prec = "&lt;&lt; " . t('TABLE_PAGE_PREV');
                if (!$txt_suiv > "") $txt_suiv = t('TABLE_PAGE_NEXT') . " &gt;&gt;";
                if ($this->bTablePages || $this->bTableHeaderPages) {
                    $_GET["navRows" . $this->sNavPrefixe] = $this->navRows;
                    $_GET["navPage" . $this->sNavPrefixe] = $this->navPage;
                    $_GET["navLimitRows" . $this->sNavPrefixe] = $this->navLimitRows;
                    $_GET["navMaxLinks" . $this->sNavPrefixe] = $this->navMaxLinks;
                    $_GET["navFirstPage" . $this->sNavPrefixe] = $this->navFirstPage;
                    $_GET["navMinRow" . $this->sNavPrefixe] = $this->navMinRow;
                    $_GET["navMaxRow" . $this->sNavPrefixe] = $this->navMaxRow;
                    if ($this->navRows > $this->navLimitRows) {
                        $nb_pages = ceil($this->navRows / $this->navLimitRows); // calcul du nombre de pages
                        $add_url = ""; // initialisation des variables à inserer en GET dans les liens
                        // recuperation des variables contenues dans l'URL, autres
                        // que les variables utilisées dans cette librairie
                        $add_url.= self::makeURL("", "", array("navPage" . $this->sNavPrefixe, "navFirstPage" . $this->sNavPrefixe, "navMinRow" . $this->sNavPrefixe, "navMaxRow" . $this->sNavPrefixe, "navRows" . $this->sNavPrefixe, "navLimitRows" . $this->sNavPrefixe, "navMaxLinks" . $this->sNavPrefixe));
                        $add_url.= "&amp;navRows" . $this->sNavPrefixe . "=" . $this->navRows . "&navLimitRows" . $this->sNavPrefixe . "=" . $this->navLimitRows . "&amp;navMaxLinks" . $this->sNavPrefixe . "=" . $this->navMaxLinks;
                        // correction du bug sur la navigation apres rechercher dans un repertoire (cause l'encodage du nom du repertoire dans la varibale $path
                        $add_url = html_entity_decode(rawurldecode($add_url));
                        // ajout du lien "precedent" si necessaire
                        if ($this->navMinRow != ($this->navFirstPage - 1) * $this->navLimitRows + 1) {
                            $prec_min = $this->navMinRow - $this->navLimitRows;
                            $prec_max = $prec_min + $this->navLimitRows - 1;
                            $liste_links.= "&nbsp;" . Pelican_Html::span(array("class" => "prev"), $this->navURL($txt_prec, $add_url, $prec_min, $prec_max, $this->navFirstPage, intval($this->navPage - 1), $url_class)) . "&nbsp;$txt_separateur";
                        } else {
                            if ($this->navFirstPage > 1) {
                                $prec_min = $this->navLimitRows * ($this->navFirstPage - $this->navMaxLinks - 1) + 1;
                                $prec_max = $prec_min + $this->navLimitRows;
                                if ($txt_bloc_prec == "") {
                                    $txt_bloc_prec = "les " . $this->navMaxLinks . " pages pr&eacute;c&eacute;dentes";
                                }
                                $liste_links.= "&nbsp;" . Pelican_Html::span(array("class" => "prev"), $this->navURL($txt_prec, $add_url, $prec_min, $prec_max, intval($this->navFirstPage - $this->navMaxLinks), intval($this->navFirstPage - 1), $url_class)) . "&nbsp;$txt_separateur";
                            } else {
                                $liste_links.= "&nbsp;" . Pelican_Html::span(array("class" => "prev"), $txt_prec) . "&nbsp;$txt_separateur";
                            }
                        }
                        // construction de la liste de liens
                        $num_link = 0;
                        for ($navPage = $this->navFirstPage;$navPage <= $nb_pages and $num_link < $this->navMaxLinks;$navPage++) {
                            $limit_inf = ($navPage - 1) * $this->navLimitRows + 1;
                            $limit_sup = min($navPage * $this->navLimitRows, intval($this->navRows));
                            if ($limit_inf == $this->navMinRow) {
                                $liste_links.= "&nbsp;" . Pelican_Html::span(array("class" => "num"), $navPage) . "&nbsp;$txt_separateur";
                                $page_actuelle = true;
                            } else {
                                $liste_links.= "&nbsp;" . Pelican_Html::span(array("class" => "num"), $this->navURL($navPage, $add_url, $limit_inf, $limit_sup, $this->navFirstPage, $navPage, $url_class)) . "&nbsp;$txt_separateur";
                                $page_actuelle = false;
                            }
                            // incrementation du nombre de links dans la liste
                            $num_link++;
                        }
                        for ($i = $num_link;$i < min($this->navMaxLinks, $nb_pages);$i++) {
                            $liste_links.= "&nbsp;--&nbsp;$txt_separateur";
                        }
                        // ajout du lien "suivant" si necessaire
                        if (!$page_actuelle) {
                            $suiv_min = $this->navMinRow + $this->navLimitRows;
                            $suiv_max = min($this->navMaxRow + $this->navLimitRows, intval($this->navRows));
                            $liste_links.= "&nbsp;" . Pelican_Html::span(array("class" => "next active"), $this->navURL($txt_suiv, $add_url, $suiv_min, $suiv_max, $this->navFirstPage, intval($this->navPage + 1), $url_class));
                        } else {
                            if ($limit_sup != $this->navRows) {
                                $suiv_min = $this->navLimitRows * ($this->navFirstPage + $this->navMaxLinks - 1) + 1;
                                $suiv_max = min($suiv_min + $this->navLimitRows, intval($this->navRows));
                                $nb_pages_suivantes = min(intval($this->navMaxLinks), $nb_pages - ($this->navFirstPage + $this->navMaxLinks) + 1);
                                if ($txt_bloc_suiv == "") {
                                    $txt_bloc_suiv = "les $nb_pages_suivantes pages suivantes";
                                }
                                $liste_links.= "&nbsp;" . Pelican_Html::span(array("class" => "next active"), $this->navURL($txt_suiv, $add_url, $suiv_min, $suiv_max, intval($this->navFirstPage + $this->navMaxLinks), intval($this->navFirstPage + $this->navMaxLinks), $url_class));
                            } else {
                                $liste_links.= "&nbsp;" . Pelican_Html::span(array("class" => "next active"), $txt_suiv);
                            }
                        }
                    }
                }
                
                /** Group by */
                $group = "";
                if ($groupby && $this->aGroupBy) {
                    $group = $this->getGroupBy("", $this->aGroupBy);
                }
                $liste_links = Pelican_Html::table(array(cellspacing => 1, cellpadding => 0, width => "100%", border => 0, 'class' => $this->sNavClass), Pelican_Html::tr(array('class' => $this->sNavClass), Pelican_Html::td(array(width => "20%"), $this->navRows . " " . t('TABLE_RECORDS') . " " . $group), Pelican_Html::td(array(align => "center", width => "60%"), "&nbsp;" . $liste_links . "&nbsp;"), Pelican_Html::td(array(align => "right", width => "20%"), ($nb_pages > 1 ? $nb_pages . " " . t('pages') : "&nbsp;"))));
                // ajout du lien vers le groupe de pages suivantes si necessaire
                // renvoit de la liste
                return $liste_links;
            }
            
            /**
             * Création d'un filtre automatique sur le tableau (champ de saisie, combo, champ
             * date)
             *
             * Les champs de saisie vont appliquer une clause AND pour chaque mot séparé
             * par un espace.
             * L'encadrement par des " indique une expression exacte
             *
             * Configurations :
             * - pas d'attribut => Champ de saisie et recherche plein texte
             * - une chaine SQL => Affichage d'une combo (nécessite les alias id et lib)
             * - un tableau de données au format d'un queryTab de la class Pelican_Db (1er
             * champ pour l'id, le second pour le libellé) ou du type
             * array(array(id1,lib1),array(id2,lib2))
             * - un tableau avec les paramètres suivants :
             * - "_sql_"=>"SQL" ou "TABLEAU" : Eléments de remplissage d'une combo
             * - "_type_"=>"date" : Affiche un champ date avec un calendrier
             * - "_type_"=>"checkbox" : Affiche des cases à cocher (doit être défini avec
             * "_sql_" cf ci-dessus)
             * - "_type_"=>"radio" : Affiche des bouton radio (doit être défini avec
             * "_sql_" cf ci-dessus)
             * - "_type_"=>"radio_v" : Affiche des bouton radio vertical (doit être défini
             * avec "_sql_" cf ci-dessus)
             * - "_type_"=>"select" :  (par défaut si le paramètre est rempli) Affiche une
             * combo (doit être défini avec "_sql_" cf ci-dessus)
             * - "_operateur_"=>"OPERATEUR" : opérateur de comparaison (=,!=,<,<=,>,>=)
             *
             * Pour la définition des clauses WHERE, n'importe quelle expression SQL est
             * valable.
             * Un tableau d'expressions ou de champs
             * entrainera l'imbrication dans la clause WHERE d'opérateurs OR entre les
             * éléments du tableau $aField
             *
             * @access public
             * @param string $filterName (option) Nom du filtre (se retrouve dans le $_GET par
             * "filter_" + nom du filtre)
             * @param string $filterFieldLabel (option) Libellé du contrôle de filtre (peut
             * être du Pelican_Html pour la mise en page)
             * @param mixed $aField (option) Expression ou tableau d'expression à utiliser
             * pour le filtrage (champ, expression, tableau de champs ou d'expressions)
             * @param mixed $aAttributs (option) Tableau de paramétrage du filtre :
             * détermine s'il faut utilser un champ ou une combo.
             * @param mixed $aBind (option) Paramètres Bind de la requête
             * @param int $iColspan (option) Colspan à utiliser pour le filtre
             * @param bool $bUse (option) Filtre utilisé ou non de façon automatique :
             * permet de créer un objet de filtre mais de le traiter par la suite manuellement
             *
             * @param bool $wantAll (option) __DESC__
             * @param string $sLabelOnly (option) Si l'on veux générer juste un label.
             * @return void
             */
            public function setFilterField($filterName = "", $filterFieldLabel = "", $aField = "", $aAttributs = "", $aBind = array(), $iColspan = "1", $bUse = true, $wantAll = true, $sLabelOnly = "") {
                $oConnection = Pelican_Db::getInstance();
                $replaceClass = "";
                $filter_select = "";
                $initialClass = '';
                if (!isset($_GET["filter_" . $filterName])) {
                    $_GET["filter_" . $filterName] = "";
                }
                if ($filterName) {
                    if (!is_array($aField)) {
                        $aField = array($aField);
                    }
                    $this->aFilter["filter_" . $filterName] = array("name" => "filter_" . $filterName, "fields" => $aField, "use" => $bUse, "use_combo" => false, "tab" => $this->currentFilterTab);
                    if ($filterFieldLabel) {
                        $filter = $filterFieldLabel . " ";
                    }
                    $useCombo = false;
                    if (is_array($aAttributs)) {
                        // Tableau de valeurs pour la combo
                        if (valueExists($aAttributs, "_operateur_")) {
                            $this->aFilter["filter_" . $filterName]["operateur"] = $aAttributs["_operateur_"];
                        } else {
                            $aAttributs["_operateur_"] = "";
                        }
                        if (valueExists($aAttributs, "_sql_")) {
                            if (strpos(strToLower(" " . $aAttributs["_sql_"]), "select")) {
                                $result = $oConnection->QueryTab($aAttributs["_sql_"], $aBind);
                            } else {
                                $result = $aAttributs["_sql_"];
                            }
                            $useCombo = true;
                            if (!valueExists($aAttributs, "_type_")) {
                                $aAttributs["_type_"] = "select";
                            }
                        } else {
                            $result = $aAttributs;
                            $useCombo = true;
                            if (!valueExists($aAttributs, "_type_")) {
                                $aAttributs["_type_"] = "select";
                            }
                        }
                    } elseif (strpos(strToLower(" " . $aAttributs), "select")) {
                        $temp = $aAttributs;
                        // cas d'une requête SQL
                        $result = $oConnection->queryTab($aAttributs, $aBind);
                        $useCombo = true;
                        $aAttributs = array();
                        $aAttributs["_sql_"] = $temp;
                        $aAttributs["_type_"] = "select";
                    }
                    if (is_array($aAttributs)) {
                        // cas d'un tableau
                        if (isset($aAttributs["_type_"])) {
                            // Type de filtre plus complexe
                            switch ($aAttributs["_type_"]) {
                                case "date":
                                case "calendar": {
                                            $this->aFilter["filter_" . $filterName]["type"] = $aAttributs["_type_"];
                                            $this->aFilter["filter_" . $filterName]["operateur"] = $aAttributs["_operateur_"];
                                            $initialClass = "class=\"text\"";
                                            $replaceClass = "class=\"filtre\" style=\"width:50%\"";
                                            include_once (pelican_path('Form'));
                                            if (!isset($this->oFilterForm)) {
                                                $this->oFilterForm = Pelican_Factory::getInstance('Form', false);
                                                $this->sFilterFormCode = $this->oFilterForm->open("", "", "fFormSaisieFilter", false, true, "CheckFormSaisieFilter", "", true);
                                            }
                                            $filter.= $this->oFilterForm->createInput("filter_" . $filterName, $filterFieldLabel, 10, $aAttributs["_type_"], false, $_GET["filter_" . $filterName], false, 10, true, "onchange=\"filter_change(this)\"");
                                        break;
                                    }
                                case "number": {
                                        $this->aFilter["filter_" . $filterName]["type"] = $aAttributs["_type_"];
                                        $this->aFilter["filter_" . $filterName]["operateur"] = $aAttributs["_operateur_"];
                                        $initialClass = "class=\"text\"";
                                        include_once (pelican_path('Form'));
                                        if (!isSet($this->oFilterForm)) {
                                            $this->oFilterForm = Pelican_Factory::getInstance('Form', false);
                                            $this->sFilterFormCode = $this->oFilterForm->open("", "", "fFormSaisieFilter", false, true, "CheckFormSaisieFilter", "", true);
                                        }
                                        $filter.= $this->oFilterForm->createInput("filter_" . $filterName, $filterFieldLabel, 10, $aAttributs["_type_"], false, $_GET["filter_" . $filterName], false, 10, true, "onchange=\"filter_change(this)\"");
                                        break;
                                    }
                                case "checkbox": {
                                        $this->aFilter["filter_" . $filterName]["type"] = $aAttributs["_type_"];
                                        $this->aFilter["filter_" . $filterName]["operateur"] = "=";
                                        $initialClass = "class=\"text\"";
                                        include_once (pelican_path('Form'));
                                        if (!isSet($this->oFilterForm)) {
                                            $this->oFilterForm = Pelican_Factory::getInstance('Form', false);
                                            $this->sFilterFormCode = $this->oFilterForm->open("", "", "fFormSaisieFilter", false, true, "CheckFormSaisieFilter", "", true);
                                        }
                                        $aDataValues = array();
                                        if ($result) {
                                            foreach($result as $line) {
                                                $aDataValues[$line[0]] = $line[1];
                                            }
                                        }
                                        $chkValues = explode("#", str_replace("##", "#", $_GET["filter_" . $filterName]));
                                        $filter.= $this->oFilterForm->createCheckBoxFromList("filter_" . $filterName, $filterFieldLabel, $aDataValues, $chkValues, false, false, "h", true, "onclick=\"filter_change(this,'filter_" . $filterName . "')\"");
                                        break;
                                    }
                                case "radio_v": {
                                        $tmpOrientation = "v";
                                    }
                                case "radio": {
                                        if (!isset($tmpOrientation)) {
                                            $tmpOrientation = "h";
                                        }
                                        $this->aFilter["filter_" . $filterName]["type"] = $aAttributs["_type_"];
                                        $this->aFilter["filter_" . $filterName]["operateur"] = "=";
                                        $initialClass = "class=\"text\"";
                                        include_once (pelican_path('Form'));
                                        if (!isSet($this->oFilterForm)) {
                                            $this->oFilterForm = Pelican_Factory::getInstance('Form', false);
                                            $this->sFilterFormCode = $this->oFilterForm->open("", "", "fFormSaisieFilter", false, true, "CheckFormSaisieFilter", "", true);
                                        }
                                        $aDataValues = array();
                                        if (isset($result['_values_'])) {
                                            $aDataValues = $aAttributs["_values_"];
                                        } elseif ($result) {
                                            foreach($result as $line) {
                                                $aDataValues[$line[0]] = $line[1];
                                            }
                                        }
                                        //$chkValues = explode("#",str_replace("##","#",$_GET["filter_".$filterName]));
                                        $chkValues = $_GET["filter_" . $filterName];
                                        $filter.= $this->oFilterForm->createRadioFromList("filter_" . $filterName, $filterFieldLabel, $aDataValues, $chkValues, false, false, $tmpOrientation, true, "onclick=\"filter_change(this)\"");
                                        break;
                                    }
                                case "select": {
                                        if ($result) {
                                            if ($wantAll) {
                                                $filter_select.= "<option value=\"\">- " . (is_bool($wantAll) ? t('TABLE_FILTER_ALL') : $wantAll) . " -</option>";
                                            }
                                            if (is_array($result)) {
                                                foreach($result as $ligne) {
                                                    $aGroup[(isset($ligne["optgroup"]) ? $ligne["optgroup"] : "") ][] = $ligne;
                                                }
                                                foreach($aGroup as $group => $values) {
                                                    $filter_option = "";
                                                    foreach($values as $opt) {
                                                        if ($opt) {
                                                            $selected = false;
                                                            $keys = array_keys($opt);
                                                            if (in_array(0, $keys) && in_array(1, $keys)) {
                                                                $keys = array(0, 1);
                                                            }
                                                            $filter_get = "";
                                                            if (isset($_GET["filter_" . $filterName])) {
                                                                $filter_get = $_GET["filter_" . $filterName];
                                                            }
                                                            if ($filter_get == $opt[$keys[0]]) {
                                                                $selected = true;
                                                                $this->aFilterSummary[$filterFieldLabel] = $opt[$keys[1]];
                                                            }
                                                            $filter_option.= Pelican_Html::option(array(value => $opt[$keys[0]], selected => $selected), $opt[$keys[1]]);
                                                        }
                                                    }
                                                    if ($group) {
                                                        $filter_option = Pelican_Html::optgroup(array(label => Pelican_Text::htmlentities($group)), $filter_option);
                                                    }
                                                    $filter_select.= $filter_option;
                                                }
                                            }
                                            $filter.= Pelican_Html::select(array('class' => "filtre", name => "filter_" . $filterName, id => "filter_" . $filterName, style => "width:100%", onchange => "filter_change(this)"), $filter_select);
                                        } else {
                                            $filter.= "&nbsp;";
                                        }
                                        break;
                                    }
                                }
                            }
                        }
                        if (!is_array($aAttributs)) {
                            $onkeydown = "filter_KeyDown(event);\" onchange=\"filter_change(this)";
                            if (!$bUse) {
                                $onkeydown = "";
                            }
                            $filter.= Pelican_Html_Input::text(array('class' => "filtre", name => "filter_" . $filterName, id => "filter_" . $filterName, value => Pelican_Text::htmlentities(stripslashes($_GET["filter_" . $filterName])), style => "width:100%", onkeydown => $onkeydown));
                            if ($_GET["filter_" . $filterName]) {
                                $this->aFilterSummary[$filterFieldLabel] = Pelican_Text::htmlentities(stripslashes($_GET["filter_" . $filterName]));
                            }
                        } else {
                            if ($_GET["filter_" . $filterName]) {
                                $this->aFilterSummary[$filterFieldLabel] = Pelican_Text::htmlentities(stripslashes($_GET["filter_" . $filterName]));
                            }
                        }
                        //   $field[2]=$useCombo;
                        $this->aFilter["filter_" . $filterName]["use_combo"] = $useCombo;
                    } else {
                        $filter = "&nbsp;";
                    }
                    if ($sLabelOnly) {
                        $filter.= $sLabelOnly . Pelican_Html::br();
                    }
                    // cas des input de form.class
                    if (isset($replaceClass)) {
                        $filter = str_replace($initialClass, $replaceClass, $filter);
                    }
                    $this->aFilterHTML[] = array("html" => $filter, "colspan" => $iColspan, "tab" => $this->currentFilterTab);
                }
                
                /**
                 * __DESC__
                 *
                 * @access public
                 * @param string $html (option) __DESC__
                 * @param __TYPE__ $iColspan (option) __DESC__
                 * @param string $name (option) __DESC__
                 * @return __TYPE__
                 */
                public function setFilterHTML($html = "", $iColspan = 1, $name = '') {
                    $this->aFilterHTML[] = array("html" => $html, "colspan" => $iColspan, "tab" => '');
                    if ($name != '') {
                        $this->aFilter[$name]['name'] = $name;
                        $this->aFilterSummary[$name] = Pelican_Text::htmlentities(stripslashes($_GET[$name]));
                    }
                }
                
                /**
                 * __DESC__
                 *
                 * @access public
                 * @param string $strId __DESC__
                 * @param string $strLabel __DESC__
                 * @return __TYPE__
                 */
                public function setFilterTab($strId, $strLabel) {
                    $this->currentFilterTab = $strId;
                    $this->aFilterTab[$strId] = array("id" => $strId, "label" => Pelican_Text::htmlentities($strLabel));
                }
                
                /**
                 * Affichage des onglets définis par la méthode setTab
                 *
                 * @access public
                 * @return string
                 */
                public function drawFilterTab() {
                    if ($this->aFilterTab) {
                        pelican_import('Index.Tab');
                        $oTab = Pelican_Factory::getInstance('Index.Tab', "tab" . $this->sFormName, Pelican::$frontController->skinPath);
                        foreach($this->aFilterTab as $tab) {
                            if (!$this->activatedTab) {
                                $this->activatedTab = $tab["id"];
                                $strScript = "<script type=\"text/javascript\">
						var currentTab='" . $tab["id"] . "';
						function ongletFW(id) {
							if (document.getElementById('filetTab_' + id)) {
								tabSwitch(currentTab, 'off'); /** l'ancien */
								tabSwitch(id, 'on'); /** le nouveau */
								currentTab = id;
							}
						}
						function tabSwitch(id, state) {
						var state1;
						var state2;
						var styledisplay;
						if (state == 'on') {
							state1 = '_off_';
							state2 = '_on_';
							styledisplay = '';
						} else {
							state1 = '_on_';
							state2 = '_off_';
							styledisplay = 'none';
						}
						document.getElementById('filetTab_' + id).style.display = styledisplay;
						document.getElementById('filetTab_' + id + '_1').src=document.getElementById('filetTab_' + id + '_1').src.replace(state1,state2);
						document.getElementById('filetTab_' + id + '_2').style.backgroundImage=document.getElementById('filetTab_' + id + '_2').style.backgroundImage.replace(state1,state2);
						document.getElementById('filetTab_' + id + '_3').src=document.getElementById('filetTab_' + id + '_3').src.replace(state1,state2);
					}
					function tabFocus(obj) {
						var ori = obj;
						while (obj != null && obj.tagName != \"DIV\" && obj.id.indexOf('filetTab_') == -1) {
							if (!obj.parentElement) {
								obj = obj.parentNode;
							} else {
								obj = obj.parentElement;
							}
						}
						if (obj.id.indexOf('filetTab_') != -1) {
							id = obj.id.replace('filetTab_','');
							if (currentTab != id) {
								ongletFW(id);
							}
						}
						ori.focus();
					}
					</script>";
                            }
                            $oTab->addTab($tab["label"], "filetTab_" . $tab["id"], ($this->activatedTab == $tab["id"]), "", "ongletFW('" . $tab["id"] . "')", "", "petit");
                        }
                        $tab = Pelican_Html::div(array("class" => "petit_onglet_bas", width => "100%"), $oTab->getTabs());
                        $strTmp = $strScript . $tab . Pelican_Html::br();
                        $this->displayTab = true;
                        return $strTmp;
                    }
                }
                
                /**
                 * Création du Pelican_Html associé aux filtres automatiques définis.<br>
                 *
                 * La commande $table->setFilterField() permet de définir des filtres vides pour
                 * des question de mise en page
                 *
                 * ATTENTION : doit être appelé avant la méthode setValues de l'objet pour
                 * être pris en compte lors des filtrages
                 *
                 * <code>
                 * $table->setFilterField("client", "<b>Client :<br />", "client_libelle");
                 * $table->setFilterField();
                 * $table->getFilter(2);
                 * $table->setValues($strSqlList, "client_id");
                 * </code>
                 *
                 * @access public
                 * @param string $iCol (option) Nombre de colonnes de filtres, par défaut 2
                 * filtres par ligne. La commande $table->setFilterField() permet de définir des
                 * filtres vides pour des question de mise en page
                 * @param string $bDirect (option) Utilisation directe des filtres ou non
                 * (événement onChange). Sinon affichage d'un bouton de soumission des valeurs
                 * des filtres
                 * @param string $class (option) Classe CSS à utiliser (par défaut "filtre")
                 * @return void
                 */
                public function getFilter($iCol = "2", $bDirect = true, $class = "filtre") {
                    $return = "";
                    $this->filterDirect = $bDirect;
                    $filter_html = "";
                    $filter_onglet = "";
                    if ($this->aFilterHTML) {
                        $j = 0;
                        for ($i = 0;$i < count($this->aFilterHTML);$i++) {
                            $col = 0;
                            $k = 0;
                            for ($col = 0;$col < $iCol;$col++) {
                                if (isset($this->aFilterHTML[$i + $k])) {
                                    $temp[$this->aFilterHTML[$i + $k]["tab"]][$j][$k] = array("html" => $this->aFilterHTML[$i + $k]["html"], "colspan" => $this->aFilterHTML[$i + $k]["colspan"]);
                                    if (!$temp[$this->aFilterHTML[$i + $k]["tab"]][$j][$k]["html"]) $temp[$this->aFilterHTML[$i + $k]["tab"]][$j][$k] = array("html" => "&nbsp;", "colspan" => 1);
                                    if (isset($this->aFilterHTML[$i + $k]["colspan"])) {
                                        if ($this->aFilterHTML[$i + $k]["colspan"] > 1) $col+= $this->aFilterHTML[$i + $k]["colspan"] - 1;
                                    }
                                }
                                $k++;
                            }
                            $i+= ($iCol - 1);
                            $j++;
                        }
                        if (isset($this->oFilterForm)) {
                            $filter_html.= $this->sFilterFormCode;
                        }
                        
                        /** création des lignes */
                        foreach($temp as $tab => $temp2) {
                            $filter_html_tr = "";
                            foreach($temp2 as $line) {
                                $filter_html_td = "";
                                foreach($line as $field) {
                                    $filter_html_td.= Pelican_Html::td(array(colspan => ($field["colspan"] > 1 ? $field["colspan"] : ""), width => (intval((1 / $iCol) * 100)) . "%"), $field["html"]);
                                }
                                $filter_html_tr.= Pelican_Html::tr($filter_html_td);
                            }
                            if (!$this->filterDirect) {
                                $filter_html_tr.= Pelican_Html::tr(Pelican_Html::td(array(colspan => $iCol, align => "center"), Pelican_Html_Input::button(array(name => "button_submit", id => "button_submit", 'class' => "button", 'onclick' => "filter_submit()", value => t('FORM_BUTTON_SEARCH')))));
                            }
                            $filter_html2[$tab] = Pelican_Html::table(array('class' => $class, summary => "Filtres"), $filter_html_tr);
                            
                            /** création des onglets */
                        }
                        if ($this->aFilterTab) {
                            $filter_onglet = $this->drawFilterTab();
                            foreach($filter_html2 as $tab => $content) {
                                if (!$this->activatedTab) {
                                    $this->activatedTab = $tab;
                                }
                                if ($this->activatedTab == $tab) {
                                    $style = "display:block;";
                                } else {
                                    $style = "display:none;";
                                }
                                $filter_html2[$tab] = Pelican_Html::div(array(id => "filetTab_" . $tab, "class" => "div_onglet", style => $style), $content);
                            }
                        }
                        $filter_html.= implode("", $filter_html2);
                        if ($this->oFilterForm) {
                            $filter_html.= $this->oFilterForm->close();
                        }
                        $return = $filter_onglet . $filter_html;
                        $this->bFiltered = true;
                    }
                    $this->filterForm = $return;
                }
                
                /**
                 * Création du formulaire caché utilisé par les filtres automatiques
                 *
                 * @access public
                 * @return string
                 */
                public function getFilterForm() {
                    $filter_script = "function filter_change(obj, name) {
		if (!name) {
				objInput=eval('document.filter_form.' + obj.name);
				objInput.value=obj.value;
			} else {
			/** cas des checkbox */
				objInput=eval('document.filter_form.' + name);
				if (objInput.value && !obj.checked) {
					objInput.value = objInput.value.replace('#' + obj.value + '#','');
				}
				if (obj.checked) {
					objInput.value += '#' + obj.value + '#';
				}
			}
				" . ($this->filterDirect ? "filter_submit();" : "") . "
				}
				function filter_submit() {";
                    if (isset($this->oFilterForm)) {
                        $filter_script.= " if ( CheckFormSaisieFilter(document.getElementById('" . $this->oFilterForm->sFormName . "')) )\n";
                    }
                    $filter_script.= "document.filter_form.submit();
				}
				function filter_KeyDown(event) {
				if ( event.keyCode == 13 ) {
				event.returnValue = false;
				filter_change(event.srcElement);
				filter_submit();
				}
				}
				";
                    $filter_script.= "
		function save_current_filter(id, action) {
			var name = prompt('nom ?');
			if (name)  {
				var aVal = new Object();
				var result = '-aucun-| ';
				var custom = getCookie(id);
				if (custom) {
					var Pelican_Index_Tab = custom.split('#');
					for (var i = 0; i < tab.length; i++) {
						tmp = tab[i].split('|');
						if (tmp[0] != '-aucun-') {
							aVal[tmp[0]] = tmp[1];
						}
					}
				}
				switch (action) {
					case 'save': {
						aVal[escape(name)] = escape(document.getElementById('current_filter').value);
						break;
					}
					case 'del': {
						aVal[escape(name)] = '';
						break;
					}
				}
				for (key in aVal) {
					if (aVal[key]) {
						result += '#' + key + '|' + aVal[key];
					}
				}
				setCookie(id,result);
				document.location.href = document.location.href;
			}
		}
		";
                    $filter_script = Pelican_Html::jscript($filter_script);
                    
                    /** Formulaire de filtre */
                    reset($_GET);
                    $filter_form = "";
                    foreach($_GET as $name => $value) {
                        if (substr($name, 0, 3) != "nav" && substr($name, 0, 7) != "filter_") {
                            $filter_form.= Pelican_Html_Input::hidden(array(name => $name, id => $name, value => Pelican_Text::htmlentities(stripslashes($value))));
                        }
                    }
                    foreach($this->aFilter as $filterField) {
                        $filter_form.= Pelican_Html_Input::hidden(array(name => $filterField["name"], id => $filterField["name"] . "2", value => Pelican_Text::htmlentities(stripslashes($_GET[$filterField["name"]]))));
                    }
                    $filter_form = Pelican_Html::form(array(name => "filter_form", id => "filter_form", method => "get", action => (!empty($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : $_SERVER['SCRIPT_NAME'])), $filter_form);
                    return $filter_script . $filter_form;
                }
                
                /**
                 * __DESC__
                 *
                 * @access public
                 * @param __TYPE__ $field __DESC__
                 * @param __TYPE__ $label __DESC__
                 * @return __TYPE__
                 */
                public function addGroupby($field, $label) {
                    $this->aGroupBy[$field] = $label;
                }
                
                /**
                 * __DESC__
                 *
                 * @access public
                 * @param __TYPE__ $label __DESC__
                 * @param __TYPE__ $aGroup __DESC__
                 * @return __TYPE__
                 */
                public function getGroupBy($label, $aGroup) {
                    $aOption[] = Pelican_Html::option(array(value => ""), Pelican_Text::htmlentities("- Regroupement -"));
                    foreach($aGroup as $key => $option) {
                        $aOption[] = Pelican_Html::option(array(value => $key, selected => ($_GET["groupby"] == $key ? "selected" : "")), Pelican_Text::htmlentities($option));
                    }
                    $return = $label . " " . Pelican_Html::select(array(name => "groupby", onchange => "groupby_change(this.value);"), implode("", $aOption));
                    $return.= Pelican_Html::script(array(type => "text/javascript"), "function groupby_change(val) {document.location.href = '" . str_replace("&groupby=" . $_GET["groupby"], "", $_SERVER["REQUEST_URI"]) . "&groupby=' + val}");
                    return $return;
                }
                
                /**
                 * __DESC__
                 *
                 * @access public
                 * @return __TYPE__
                 */
                public function cleanQueryString() {
                    if (!isset($_GET["custom"])) $_GET["custom"] = "";
                    $params = array("&custom=" . rawurlencode($_GET["custom"]), "&order=" . rawurlencode($_GET["order"]), "&readO=" . $_GET["readO"]);
                    return rawurlencode(str_replace($params, "", $_SERVER['QUERY_STRING']));
                }
                
                /**
                 * Fonction de formattage des valeurs pour l'affichage dans les cellules du tableau
                 *
                 * Les paramètres prédéfinis sont :
                 * - "%" : Pourcentage ("0.5" devient "50 %")
                 * - "j->h" : Jour en heure (paramètre en heures : "8" devient "1 j.->8 h.")
                 * - "h->j" : Heure en jour (paramètre en heures : "8" devient "8 h.->1 j.")
                 * - "email" : création d'un lien cliquable "mailto"
                 * - "number" : conversion en nombre, affcihage de 0 si vide
                 * - "extension" : reduction d'un nom de fichier à ason extension
                 * - "strict" : laisse tel que
                 * - "decimal" : format decimal 2
                 *
                 * @access public
                 * @param string $format Nom du format à utiliser ou, s'il n'existe pas, chaine
                 * à concaténer
                 * @param string $value Valeur à formatter
                 * @return string
                 */
                public function setFormat($format, $value) {
                    if ($format == "decimal" && !$value) {
                        $value = "0";
                    }
                    if ($value != "") {
                        $return = $value;
                        if (intval($format)) {
                            if ($format == $value) {
                                $format = "last";
                            } else {
                                $format = "";
                            }
                        }
                        switch ($format) {
                            case "%": {
                                        if (is_numeric($return)) {
                                            $return = intval((float)$return * 100) . " %";
                                        }
                                    break;
                                }
                            case "j->h": {
                                    $return = ((float)$return * 8) . "&nbsp;" . t('TABLE_FORMAT_HOUR') . "->" . ((float)$return) . "&nbsp;" . t('TABLE_FORMAT_DAY');
                                    $return = Pelican_Html::nobr($return);
                                    break;
                                }
                            case "h->j": {
                                    $return = ((float)$return) . "&nbsp;" . t('TABLE_FORMAT_HOUR') . "->" . ((float)$return / 8) . "&nbsp;" . t('TABLE_FORMAT_DAY');
                                    $return = Pelican_Html::nobr($return);
                                    break;
                                }
                            case "email": {
                                    $return = Pelican_Html::a(array(href => "mailto:" . $return), $return);
                                    break;
                                }
                            case "URL": {
                                    $return = Pelican_Html::a(array(href => $return, target => "_blank"), $return);
                                    break;
                                }
                            case "number": {
                                    $return = ($return === "0" ? "0" : (float)$return);
                                    break;
                                }
                            case "extension": {
                                    $info = pathinfo($return);
                                    $return = strtolower($info["extension"]);
                                    break;
                                }
                            case "strict": {
                                    break;
                                }
                            case "decimal": {
                                    $return = number_format(str_replace(',', '.', $return), 2, '.', '');
                                    break;
                                }
                            case "bold": {
                                    $return = Pelican_Html::span(array(style => "font-weight:bold;"), $return);
                                    break;
                                }
                            case "error": {
                                    $return = Pelican_Html::span(array(style => "color:red;"), $return);
                                    break;
                                }
                            case "last": {
                                    $return = Pelican_Html::span(array(style => "font-weight:bold;text-decoration: underline;"), $return);
                                    break;
                                }
                            case "rawurldecode": {
                                    $return = Pelican_Text::rawurldecode($return);
                                    break;
                                }
                            case "boolean": {
                                    if ($return) {
                                        $return = Pelican_Html::img(array(src => "/images/ok.gif", alt => "ok")); //"X";
                                        
                                    } else {
                                        $return = "";
                                    }
                                    break;
                                }
                            case "page_path": {
                                    $temp1 = explode("#", $return);
                                    array_shift($temp1);
                                    foreach($temp1 as $temp2) {
                                        $temp3 = explode("|", $temp2);
                                        $path[] = $temp3[1];
                                    }
                                    $return = implode(" > ", $path);
                                    break;
                                }
                            default: {
                                    if (is_numeric($return)) {
                                        $return = (float)$return;
                                    }
                                    $return = trim($return . " " . $format);
                                    break;
                                }
                            }
                        } else {
                            switch ($format) {
                                case "number": {
                                            $return = "0";
                                        break;
                                    }
                                default: {
                                        $return = "&nbsp;";
                                        break;
                                    }
                                }
                            }
                            return $return;
                        }
                        
                        /**
                         * Suppression de paramètres de l'url courante
                         *
                         * @static
                         * @access public
                         * @param mixed $reset Tableau des paramètres à supprimer
                         * @return string
                         */
                        public static function resetURL($reset) {
                            return self::makeURL("", "", $reset);
                        }
                        
                        /**
                         * Création d'une URL de navigation
                         *
                         * @access public
                         * @param string $navText Libellé du lien
                         * @param string $begin Début de la chaine constituant le lien (souvent l'URL
                         * courante)
                         * @param string $navMinRow Numéro de la première ligne en cours d'affichage
                         * @param string $navMaxRow Numéro de la dernière ligne en cours d'affichage
                         * @param string $navFirstPage Numéro de plage de pages en cours d'affichage
                         * @param string $navPage (option) Numéro de la page en cours de consultation
                         * @param string $url_class (option) Classes CSS à appliquer
                         * @return __TYPE__
                         */
                        public function navURL($navText, $begin, $navMinRow, $navMaxRow, $navFirstPage, $navPage = "", $url_class = "") {
                            return Pelican_Html::a(array('class' => $url_class, href => str_replace(" DESC", "%20DESC", $begin . ($navPage ? "&amp;navPage" . $this->sNavPrefixe . "=" . $navPage : "") . "&amp;navMinRow" . $this->sNavPrefixe . "=" . $navMinRow . "&amp;navMaxRow" . $this->sNavPrefixe . "=" . $navMaxRow . "&amp;navFirstPage" . $this->sNavPrefixe . "=" . $navFirstPage)), $navText);
                        }
                        
                        /**
                         * Création des liens dans les entêtes pour faire les tris
                         *
                         * @access public
                         * @param string $text Libellé de l'entête
                         * @param string $field (option) Champ à utiliser pour effectuer le tri
                         * @return string
                         */
                        public function setOrder($text, $field = "") {
                            $order_param = "order";
                            if ($this->sTableId) {
                                $order_param.= "_" . $this->sTableId;
                            }
                            $return = "";
                            if ($field) {
                                if (!isset($_GET[$order_param])) {
                                    $_GET[$order_param] = "";
                                }
                                $order_field = explode(",", $_GET[$order_param]);
                                $order_field = array_map(array($this, 'cleanOrder'), $order_field);
                                $order = "";
                                $image = "pixel";
                                $haut = "";
                                $bas = "";
                                if (in_array($this->cleanOrder($field . " DESC"), $order_field)) {
                                    $image = "decroissant";
                                    $this->isOrder = $this->bOrderClass;
                                } elseif (in_array($this->cleanOrder($field), $order_field)) {
                                    $image = "croissant";
                                    $order = " DESC";
                                    $this->isOrder = $this->bOrderClass;
                                }
                                $url = self::resetURL($order_param);
                                $url_order = $url . "&amp;" . $order_param . "=" . rawurlencode($field . $order);
                                $return.= Pelican_Html::nobr(Pelican_Html::img(array(src => $this->_sLibPath . '/public/images/pixel.gif', border => 0, alt => ' ', align => 'middle', width => 9, height => 5)) . Pelican_Html::a(array(href => $url_order), $text) . NBSP . Pelican_Html::img(array(src => $this->_sLibPath . $this->_sLibList . '/images/' . $image . '.gif', border => 0, alt => ' ', align => 'middle', width => 9, height => 5)));
                            } else {
                                $return = $text;
                            }
                            return $return;
                        }
                        
                        /**
                         * Définition des paramètres utiles à l'affichage du tableau au format Excel
                         *
                         * @access public
                         * @param bool $autoFilter (option) Utilisation ou non des filtre automatiques
                         * Excel au niveau des entêtes
                         * @return void
                         */
                        public function setExcelParams($autoFilter = false) {
                            $this->excelAutoFilter = $autoFilter;
                            $this->navLimitRows = 10000;
                        }
                        
                        /**
                         * Retourne les paramètres de cellule pour un export Excel
                         *
                         * @access public
                         * @return string
                         */
                        public function getExcelParams() {
                            $temp = "";
                            if ($this->excelAutoFilter) {
                                $temp.= "x:autofilter=\"all\"";
                            }
                            return $temp;
                        }
                        
                        /**
                         * Affichage ou non d'un éléments dans la colonne
                         *
                         * @access public
                         * @param mixed $aParam Tableau des clauses de validation
                         * @param mixed $values Tableau des valeurs à utiliser
                         * @return bool
                         */
                        public function showCol($aParam, $values) {
                            $bShow = true;
                            if ($aParam) {
                                if (!is_array($aParam)) $aParam = array($aParam);
                                foreach($aParam as $tmp) {
                                    $different = explode("!=", $tmp);
                                    $inferieur = explode("<", $tmp);
                                    if (isset($different[1])) {
                                        if ($different[0] == "level") {
                                            $different[1]++;
                                        }
                                        if ($values[$different[0]] == $different[1]) {
                                            $bShow = false;
                                        }
                                    } elseif (isset($inferieur[1])) {
                                        if ($values[$inferieur[0]] > $values[$inferieur[1]]) {
                                            $bShow = false;
                                        }
                                    } else {
                                        $egal = explode("=", $tmp);
                                        if ($egal[0] == "level") {
                                            $egal[1]++;
                                        }
                                        if (isset($egal[1])) {
                                            if ($values[$egal[0]] != $egal[1]) {
                                                $bShow = false;
                                            }
                                        }
                                    }
                                }
                            }
                            return $bShow;
                        }
                        
                        /**
                         * Création d'un INPUT
                         *
                         * @static
                         * @access public
                         * @param Rowlist $column Paramètres de la colonne
                         * @param mixed $values Tableau des valeurs de la ligne courante
                         * @param string $id (option) __DESC__
                         * @return string
                         */
                        public static function makeInput($column, $values, $id = "") {
                            // url
                            $checked = false;
                            if (isset($column->aColumnAttributes["_target_"])) {
                                if ($column->aColumnAttributes["_target_"] == "_blank") {
                                    if (isset($column->aColumnAttributes["_target_option_"])) {
                                        $url = "window.open('" . self::makeURL($column->aColumnAttributes, $values) . "','','" . $column->aColumnAttributes["_target_option_"] . "')";
                                    } else {
                                        $url = "window.open('" . self::makeURL($column->aColumnAttributes, $values) . "')";
                                    }
                                }
                            } elseif (isset($column->aColumnAttributes["_javascript_"])) {
                                $url = $column->aColumnAttributes["_javascript_"] . "(" . self::makeURL($column->aColumnAttributes, $values, "", true) . ")";
                            } else {
                                $url = "document.location.href='" . self::makeURL($column->aColumnAttributes, $values) . "'";
                            }
                            // td
                            if (isset($column->aColumnAttributes["_image_"])) {
                                $input = Pelican_Html::img(array(id => $id, src => $column->aColumnAttributes["_image_"], alt => $column->sColumnField, border => 0, align => "middle", onclick => $url, style => "cursor:pointer;"));
                            } elseif (isset($column->aColumnAttributes["_span_"])) {
                                $input = Pelican_Html::span(array('class' => $column->aColumnAttributes["_span_"], onclick => $url, style => "cursor:pointer;"), $column->sColumnField);
                            } else {
                                if (($column->sColumnFormat == "checkbox") || ($column->sColumnFormat == "radio")) {
                                    $id = $column->sColumnFormat . $id; // ajout du 28/09/2007
                                    $checked = false;
                                    if (isset($values[$column->sColumnField])) {
                                        $checked = ($values[$column->sColumnField] == 1);
                                    }
                                    if ($column->aColumnAttributes["_value_field_"]) {
                                        $value = $values[$column->aColumnAttributes["_value_field_"]];
                                    }
                                } else {
                                    $value = $values[$column->sColumnField];
                                }
                                if ($column->sColumnFormat != "button") {
                                    $input = Pelican_Html::input(array(type => $column->sColumnFormat, value => $value, checked => $checked, onclick => $url, name => $id, id => $id, 'class' => $column->sColumnFormat));
                                } else {
                                    $input = Pelican_Html::button(array(value => $column->sColumnField, checked => $checked, onclick => $url, name => $id, id => $id, 'class' => $column->sColumnFormat), $column->sColumnField);
                                }
                            }
                            return $input;
                        }
                        
                        /**
                         * Création d'un URL par manipulation des paramètres (ajout, suppression,
                         * modification)
                         *
                         * Les attributs possibles sont :
                         * - "PARAM"=>"FIELD" : paramètre à ajouter ou remplace dans l'url avec la
                         * valeur du champ de la ligne en cours
                         * - "PARAM"=>"" : suppression, s'il existe, du paramètre de l'url
                         * - ""=>"PARAM1=VALUE1&PARAM2=VALUE2" : ajout ou remplacement de paramètres
                         * constants dans l'url
                         *
                         * Les autres attributs possibles sont ($attributes=array()) :
                         * - "_javascript_"=>"FONCTION" : fonction exécutée sur le onclick à la place
                         * d'un href sur l'url. les paramètres passé à la fonctions respectent les
                         * règles ci-dessus avec l'utilisation de "PARAM" en moins
                         * - "_image_"=>"PATH" : Chemin d'une image à affiche à la place d'un boutton
                         * (type button)
                         * - "_target_"=>"TARGET" : attribut TARGET à utiliser sur l'action (type
                         * button)
                         * - "_span_"=>"SPAN" : Attribut CLASS qui indique qu'un tag SPAN doit être
                         * utilisé à la place de tout autre input avec un attribut ONCLICK (même
                         * comportement que ci-dessus)
                         * - "_value_field_"=>"TARGET" : Champ contenant la valeur à attribuer au
                         * contrôle (type checkbox)
                         *
                         * @access public
                         * @param mixed $attributes (option) Tableau de paramètres à modifier dans l'url
                         * du type "PARAM"=>"FIELD"
                         * @param mixed $result (option) Tableau de valeurs FIELD=>VALUE d'une ligne du
                         * tableau (permet de faire l'association "PARAM"=>"VALUE")
                         * @param mixed $reset (option) Tableau listant les paramètres d'url à supprimer
                         *
                         * @param bool $isJavascript (option) L'utilisation de l'url courante est
                         * remplacé ou non par une fonction javascript (entrée "_javascript_" de
                         * $attributes)
                         * @return string
                         */
                        public static function makeURL($attributes = "", $result = "", $reset = "", $isJavascript = false) {
                            if (!is_array($reset)) {
                                $reset = array($reset);
                            }
                            reset($_GET);
                            $return = "";
                            $att = "";
                            $http = "";
                            if ($attributes) {
                                foreach($attributes as $name => $value) {
                                    if ($name != "") {
                                        // dynamique : "name"=>"field" => value=$result[field]
                                        // dynamique : "name"=>"" => suppression de name de l'url cf $reset
                                        if ($name == "_http_" && $value != "") {
                                            $http = "http://" . str_replace("//", "/", $_SERVER["HTTP_HOST"] . "/" . $value);
                                        } elseif ($name != "_target_") {
                                            if (!isset($result[$value])) {
                                                $result[$value] = "";
                                            }
                                            if ($result[$value] || ($result[$value] === 0 || $name == "this") || $result[$value] == "0") {
                                                if ($isJavascript) {
                                                    $javascript[] = ($name == "this" ? "this" : "'" . rawurlencode($result[$value]) . "'");
                                                } else {
                                                    $val = $name . "=" . rawurlencode($result[$value]);
                                                    $att.= "&amp;" . $val;
                                                }
                                            }
                                            $reset[] = $name;
                                        }
                                    } else {
                                        // non dynamique : ""=>"name=value"
                                        $params = explode("&amp;", $value);
                                        foreach($params as $temp) {
                                            $var = explode("=", $temp);
                                            $reset[] = $var[0];
                                            if ($isJavascript) {
                                                $javascript[] = "'" . rawurlencode($var[1]) . "'";
                                            }
                                        }
                                        $att.= "&amp;" . $value;
                                    }
                                }
                            }
                            if ($http) {
                                $url = str_replace("?&amp;", "?&", str_replace("?&", "?", $http . "?" . $att));
                            } else {
                                $http = $_SERVER["REDIRECT_URL"];
                                foreach($_GET as $name => $value) {
                                    if (!in_array($name, $reset)) {
                                        if (is_array($value)) {
                                            $return.= ($return ? "&amp;" : "") . $name . "%5B%5D=" . implode("&amp;" . $name . "%5B%5D=", array_map("rawurlencode", $value));
                                        } else {
                                            $return.= ($return ? "&amp;" : "") . $name . "=" . rawurlencode($value);
                                        }
                                    }
                                }
                                $url = $http . "?" . $return . $att;
                            }
                            if (isset($javascript)) {
                                $url = implode(",", $javascript);
                                $url = str_replace("'this'", "this", $url);
                            }
                            $return = str_replace(" DESC", "%20DESC", $url);
                            return $return;
                        }
                        
                        /**
                         * Nettoyage de la syntaxe des champs "table.champ" => "champ"
                         *
                         * @access public
                         * @param string $value Valeur du type "champ" ou "table.champ"
                         * @return stringht
                         */
                        public function cleanOrder($value) {
                            $pos = strpos($value, ".");
                            if ($pos) {
                                return trim(substr($value, $pos + 1));
                            } else {
                                return trim($value);
                            }
                        }
                    }
                    