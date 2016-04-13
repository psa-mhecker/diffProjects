<?php
/**
 * * Gestion des listes avec tri, pagination et filtres automatiques
 *
 * @package Pelican
 * @subpackage Hierarchy
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */

/**
 * * Classe de génération de Pelican_Html
 */
pelican_import('List');
pelican_import('Hierarchy');

/**
 * * Cette classe est utilisée pour créer des tableaux Pelican_Html
 * Hiérarchiques.
 *
 * Le contenu "déplié" se paramètre comme une liste normalle
 *
 * la hiérarchie se crée grâce à la classe de gestion des hiérarchies en
 * prédéfinissant des paramètres standards : class, add, update, delete, open
 *
 * @package Pelican
 * @subpackage Hierarchy
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 11/01/2005
 * @version 1.0
 */
class Pelican_Hierarchy_List extends Pelican_Hierarchy
{
    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $table;

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $levelId = - 1;

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $stateId = true;

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $historyId = array();

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $nbButtons = 0;

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $maxLevel = 1;

    /**
     * __DESC__
     *
     * @access public
     * @var __TYPE__
     */
    public $currentLevel = 1;

    /**
     * Paramètres permettant la mise à jour générique des ordres de champ de la
     * hiérarchie en base de données
     *
     * @access public
     * @var mixed
     */
    public $aHierarchyOrderParams;

    /**
     * Tableau Pelican_Html Hiérarchisé
     *
     * @access public
     * @param  __TYPE__               $id        __DESC__
     * @param  __TYPE__               $idName    __DESC__
     * @param  __TYPE__               $pidName   __DESC__
     * @param  __TYPE__               $libName   __DESC__
     * @param  __TYPE__               $className __DESC__
     * @return Pelican_Hierarchy_List
     */
    public function Pelican_Hierarchy_List($id, $idName, $pidName, $libName, $className)
    {
        $this->levelId = $_GET["levelId"];
        if ($_GET["historyId"]) {
            $this->historyId = explode(",", $_GET["historyId"]);
        }

        /**
         * * Cas de la fermeture : on remonte d'un niveau
         */
        if (!$_GET["stateId"] && $this->levelId) {
            $temp = $this->historyId;
            array_pop($temp);
            $this->historyId = $temp;
            $this->levelId = array_pop($temp);
        }
        $this->currentLevel = count($this->historyId);
        $this->_sLibPath = Pelican::$config["LIB_PATH"];
        $this->_sLibList = Pelican::$config['LIB_LIST'];
        $this->idName = $idName;
        $this->pidName = $pidName;
        $this->libName = $libName;
        $this->className = $className;
        $this->pixel = Pelican_Html::img(array(src => $this->_sLibPath . '/public/images/pixel.gif', width => 1, height => 1, alt => ' ', border => '0'));
        parent::Pelican_Hierarchy($id, $idName, $pidName);
    }

    /**
     * DESC
     *
     * @access public
     * @param  __TYPE__ $property      __DESC__
     * @param  __TYPE__ $type          (option) __DESC__
     * @param  bool     $bNoRecordText (option) __DESC__
     * @param  __TYPE__ $aBind         (option) __DESC__
     * @return __TYPE__
     */
    public function getTable($property, $type = "ASC", $bNoRecordText = true, $aBind = array())
    {
        $this->setOrder($property, $type);
        $this->maxLevel = ($this->_countLevels - 2);

        /**
         * * définition de toutes les colonnes à préparer
         */
        for ($i = 0;$i < $this->maxLevel + 2 + $this->nbButtons;$i++) {
            if ($i == $this->currentLevel + 1) {
                $colspan = $this->maxLevel - $this->currentLevel;
                $width = "100%";
            } else {
                $colspan = "";
                $width = "";
            }
            $vide.= Pelican_Html::th(array(width => "10", height => "1", align => "left", colspan => $colspan, width => $width), $this->pixel);
        }
        $return = $this->getHierarchyList();
        $return = Pelican_Html::table(array(border => "0", cellspacing => "0", cellpadding => "1", width => "100%"), Pelican_Html::tr(array(), $vide) . $return);
        // Flèches de définition des ordres
        $_SESSION["listhierarchyorder"] = array();
        if ($this->aHierarchyOrderParams) {
            $_SESSION["listhierarchyorder"]["table"] = $this->aHierarchyOrderParams["table"];
            $_SESSION["listhierarchyorder"]["order"] = $this->aHierarchyOrderParams["order"];
            $_SESSION["listhierarchyorder"]["id"] = $this->aHierarchyOrderParams["id"];
            $_SESSION["listhierarchyorder"]["parent"] = $this->aHierarchyOrderParams["parent"];
            $_SESSION["listhierarchyorder"]["parentIsNeeded"] = true;
            $_SESSION["listhierarchyorder"]["complementWhere"] = $this->aHierarchyOrderParams["complementWhere"];
            $_SESSION["listhierarchyorder"]["retour"] = $_SERVER["REQUEST_URI"];
            $js.= Pelican_Html::jscript("function listHierarchyOrder(id,ordre) {
                    window.document.location.href='" . $this->_sLibPath . $this->_sLibList . "/order.php?id=' + id + '&sens=' + ordre + '&session=listhierarchyorder';
                    }");
        }

        return $return . $js;
    }

    /**
     * Création de la liste hiérarchique
     *
     * @access public
     * @param  string $id (option) Identifiant du niveau
     * @return string
     */
    public function getHierarchyList($id = "")
    {
        /**
         * * paramètres du niveau
         */

        /**
         * * on vérifie s'il est ouvert
         */
        if (!$id) {
            $child = $this->aParams[0]["child"];
            $open = true;
        } else {
            $child = $this->aParams[$id]["child"];
            $node = $this->aNodes[$this->aParams[$id]["record"]];
            $open = in_array($node->{$this->idName}, $this->historyId);

            /**
             * *  Préparation de la requête pour la liste
             */
            $this->table->reset();
            $aTableValues = $this->listValues[0];
            $sFieldId = $this->listValues[1];
            $aGroupeField = $this->listValues[2];
            $aBind = $this->listValues[3];
            if ($this->aBind) {
                foreach ($this->aBind as $key => $field) {
                    $aBind[$key] = $node->$field;
                }
            }
            $this->table->setValues($aTableValues, $sFieldId, $aGroupeField, $aBind);
            $content = $this->table->getTable(false, false, $aBind);
            $count = $this->table->iTableRows;
            $return = $this->generateLevel($node, $open, $count);

            /**
             * * on affiche le niveau ouvert
             */
            if ($open && $content) {
                $return.= Pelican_Html::tr(array(), Pelican_Html::td(array(colspan => ($node->level - 1)), Pelican_Html::img(array(src => $this->_sLibPath . $this->_sLibList . "/public/images/pixel.gif"))) . Pelican_Html::td(array(colspan => ($this->maxLevel + 3 + $this->nbButtons - $node->level)), $content));
            }
        }
        if ($open) {

            /**
             * * S'il a des enfants, on les parcours
             */
            if ($child) {
                foreach ($child as $val) {
                    $return.= $this->getHierarchyList($val);
                }
            }
        }

        return $return;
    }

    /**
     * Génération d'un niveau de la hiérarchie
     *
     * @access public
     * @param  Node   $node  Attributs de type Node (Hierarchie)
     * @param  bool   $state Ouvertaure OUI:NON
     * @param  int    $count Nombre d'enregistrements fils
     * @return string
     */
    public function generateLevel($node, $state, $count)
    {
        $val = $this->getNodeTab($node->{$this->idName});

        /**
         * * On regarde s'il y a des enfants et des données
         */
        if ($this->aParams[$node->{$this->idName}]["child"]) {
            $comp["child"] = count($this->aParams[$node->{$this->idName}]["child"]) . " niv.";
        }
        if ($count) {
            $comp["record"] = $count . " enr.";
        }
        $val["child"] = count($this->aParams[$node->{$this->idName}]["child"]) + $count;

        /**
         * * En fonction de l'état ouvert ou fermé, on change l'image
         */
        if ($state) {
            $img = $this->_sLibPath . $this->_sLibList . "/images/bouton_bas_rouge.gif";
            $stateId = 0;
        } else {
            if ($comp) {
                $img = $this->_sLibPath . $this->_sLibList . "/images/bouton_droit.gif";
            } else {
                $img = $this->_sLibPath . $this->_sLibList . "/images/bouton_droit_gris.gif";
            }
            $stateId = 1;
        }

        /**
         * * Le niveau du noeud détermine le décalage à afficher
         */
        if ($node->level > 2) {
            $decalage = Pelican_Html::td(array(colspan => ($node->level - 2), width => "15"), $this->pixel);
        }

        /**
         * * Création de la flèche avec l'url associée
         */
        $historyId = str_replace(" , ", ",", $this->getNodePath($node->{$this->idName}, $this->idName, ","));
        if ($comp) {
            $url = Pelican_List::makeURL(array("" => "levelId=" . $node->{$this->idName} . "&historyId=" . $historyId . "&stateId=" . $stateId));
        }
        if ($this->aHierarchyOrderParams) {
            $fleche_order = Pelican_Html::img(array(src => $this->_sLibPath . $this->_sLibList . "/images/ordre_plus.gif", width => 12, height => 12, alt => "Descendre", border => 0, onclick => "listHierarchyOrder('" . rawurlencode($val[$this->aHierarchyOrderParams["id"]]) . "',1)", style => "cursor:pointer;")) . Pelican_Html::img(array(src => $this->_sLibPath . $this->_sLibList . "/images/ordre_moins.gif", width => 12, height => 12, alt => "Monter", border => 0, onclick => "listHierarchyOrder('" . rawurlencode($val[$this->aHierarchyOrderParams["id"]]) . "',-1)", style => "cursor:pointer;"));
        }
        $fleche = Pelican_Html::td(array(align => "right", nobr => "nobr"), $fleche_order . Pelican_Html::a(array(name => $node->{$this->idName}, href => $url . "#" . $node->{$this->idName}), Pelican_Html::img(array(src => $img, width => "12", height => "12", hspace => "2", alt => "", border => "0"))));
        $colspan = ($this->maxLevel - $node->level + 2);
        $lib = Pelican_Html::td(array("class" => $this->className, colspan => $colspan, width => "100%", nowrap => "nowrap"), "&nbsp;" . $node->{$this->libName} . ($comp ? " [" . implode(", ", $comp) . "] " : ""));
        $vide = $this->maxLevel - $node->level + 2;
        $lib.= Pelican_Html::td(array("class" => $this->className, width => "10"), $this->pixel);
        if ($this->aInput) {
            foreach ($this->aInput as $label => $aButton) {

                /**
                 * * plusieurs boutons ont le même libellé
                 */
                foreach ($aButton as $button) {

                    /**
                     * * Ajout du niveau à l'url
                     */
                    $tmp = $button;
                    $param = explode("&", $tmp->aColumnAttributes[""]);
                    $param[] = "level=" . $node->level;
                    $tmp->aColumnAttributes[""] = implode("&", $param);

                    /**
                     * * Création des boutons
                     */
                    $bShow = Pelican_List::showCol($tmp->aShow, $val);
                    $fieldButton = $tmp->sColumnField;
                    if ($tmp->sHeaderLabel && $tmp->sColumnField != $tmp->sHeaderLabel) {
                        $tmp->sColumnField.= " " . $tmp->sHeaderLabel;
                    }
                    $input = Pelican_List::makeInput($tmp, $val);
                    if ($bShow) {
                        $listButtons[$fieldButton][] = $input;
                    } else {
                        $listButtons[$fieldButton][] = $this->pixel;
                    }
                    $tmp->sColumnField = str_replace(" " . $tmp->sHeaderLabel, "", $tmp->sColumnField);
                }
            }
            if ($listButtons) {
                foreach ($listButtons as $key => $listButton) {
                    if (count($listButton) > 1) {
                        $id = $key . "_" . $node->{$this->idName};
                        $input = Pelican_Html::button(array(type => "button", id => "button" . $id, onclick => "showButton(this.id)", 'class' => "button"), $key . "&nbsp;" . Pelican_Html::img(array(src => $this->_sLibPath . '/images/decroissant.gif', alt => ' ', border => '0')));
                        $divButton = "";
                        foreach ($listButton as $temp) {
                            if ($temp != $this->pixel) {
                                $divTemp[] = $temp;
                            }
                        }
                        if (count($divTemp) > 1) {
                            $div = implode(Pelican_Html::br(), $divTemp);
                            $divButton = Pelican_Html::div(array(id => $id, style => "position:absolute;border:#8B8B8B solid 1px;display:none;text-align:center;background-color:white;"), str_replace($key . " ", "", $div));
                            $divButton = str_replace("class=\"button\"", "class=\"buttonhierarchy\" onmouseover:\"this.style.color:red;\" onmouseout:\"this.style.color:black;\"", $divButton);
                            $buttons.= Pelican_Html::td(array("class" => $this->className, align => "right"), $input . Pelican_Html::br() . $divButton);
                        } else {
                            $buttons.= Pelican_Html::td(array("class" => $this->className, align => "right"), $divTemp[0]);
                        }
                    } else {
                        $buttons.= Pelican_Html::td(array("class" => $this->className, align => "right"), $listButton[0]);
                    }
                }
            }
        }
        $return = Pelican_Html::tr(array(), $decalage . $fleche . $lib . $buttons . $order);

        return $return;
    }

    /**
     * Ajout d'une colonne avec un contrôle (tag INPUT) à la hiérarchie :
     *
     * - Définition des paramètres de mise en page (label, secondLabel)
     * - Définition des éléments liés au contrôle (attributes, aLevel, aShow)
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
     *         array("projet_id" => "projet_id", "_target_" => "_blank", "id" =>
     * "tache_id",
     *         "" => "template=tache&popup=1", "param" => ""), "center");
     * </code>
     * Pour masquer un bouton "supprimer" par exemple, il faut mettre "child=0" dans
     * le paramètre "$aShow";
     *
     * @access public
     * @param string $label       Libellé du contrôle (d'un bouton par exemple)
     * @param string $secondLabel (option) Libellé secondaire pour les boutons ayant
     * le même nom
     * @param mixed $attributes (option) Tableau de paramétrage du contrôle
     * (éléments qui vont déterminer l'action qu'il egendre)
     * @param int   $iLevel    (option) Niveau exclusif d'affichage de l'INPUT
     * @param int   $iMaxLevel (option) Niveau maximal d'affichage de l'INPUT
     * @param mixed $aShow     (option) Tableau d'expressions du type "CHAMP=VALEUR" ou
     * "CHAMP!=VALEUR" qui doivent être remplies (si le paramètre est défini) pour
     * afficher un contenu dans la cellule
     * @return void
     */
    public function addInput($label, $secondLabel = "", $attributes = "", $iLevel = "", $iMaxLevel = "", $aShow = "")
    {
        /**
         * * Seul niveau autorisé
         */
        if (!is_array($aShow)) {
            $aShow = (array) $aShow;
        }
        if ($iLevel) {
            $aShow[] = "level=" . ($iLevel);
        } else {

            /**
             * * Niveau maximum autorisé
             */
            if ($iMaxLevel) {
                $aShow[] = "level!=" . ($iMaxLevel);
            }
        }
        if (!$secondLabel) {
            $secondLabel = $label;
        }
        $this->aInput[$label][] = Pelican_Factory::getInstance('List.Row', $secondLabel, $label, "", "", "button", "", "", "input", $attributes, $aShow);
        $this->nbButtons++;
    }

    /**
     * Objet de liste
     *
     * Définition de l'id, de la largeur, des espacements de cellule, de la bordure
     * et de l'affichage ou non des entêtes et pieds de page
     *
     * <code>
     * $oHierarchy->setList("", "", 0, 0, 0, "liste", "", false, false, false);
     * $oHierarchy->setBind(":FAQ", "THEME_FAQ_ID");
     * $oHierarchy->setValues($strSqlList, "c.CONTENT_ID", "", $aBind);
     * $oHierarchy->table->setCSS(array("tblalt1", "tblalt2"));
     * $oHierarchy->table->addColumn("id", "ID", "5", "center", "", "tblheader",
     * "c.CONTENT_ID");
     * $oHierarchy->table->addColumn(t('TITRE'), "LABEL", "70", "left", "",
     * "tblheader",
     * "TITLE");
     * $oHierarchy->table->addInput(t('FORM_BUTTON_EDIT'), "button", array("id" =>
     * "ID", "uid"
     * => "CONTENT_TYPE_ID", "" => "tid=".Pelican::$config["TPL_CONTENT"]
     * ."&rechercheContentType="), "center");
     * $oHierarchy->table->addInput(t('POPUP_LABEL_DEL'), "button", array("id" =>
     * "ID", "uid"
     * => "CONTENT_TYPE_ID", "" => "tid=".Pelican::$config["TPL_CONTENT"]
     * ."&rechercheContentType=&readO=true"), "center");
     * echo $oHierarchy->getTable("THEME_FAQ_ORDER", "ASC");
     * </code>
     *
     * @access public
     * @param string $sTableId (option) Attribut ID du tableau (obligatoire en cas de
     * tables multiples dans la page, pour différencier la navigation au sein des
     * tableaux) : "" par défaut
     * @param string $sTableWidth       (option) Attribut WIDTH du tableau
     * @param int    $iTableCellpadding (option) Attribut CELLPADDING du tableau
     * @param int    $iTableCellspacing (option) Attribut CELLSPACING du tableau
     * @param string $iTableBorder      (option) __DESC__
     * @param string $sTableClass       (option) Attribut CLASS du tableau
     * @param string $sTableStyle       (option) Attribut STYLE du tableau
     * @param bool   $bTablePages       (option) Affichage ou non de la pagination du tableau
     * (cf propriétés navMaxLinks et navLimitRows) : true par défaut
     * @param bool $bTableHeaders     (option) Affichage ou non des entêtes du tableau
     * @param bool $bTableHeaderPages (option) Affichage ou non de la pagination du
     * tableau en haut
     * @return Pelican_List
     */
    public function setList($sTableId = "", $sTableWidth = "", $iTableCellpadding = 0, $iTableCellspacing = 0, $iTableBorder = 0, $sTableClass = "", $sTableStyle = "", $bTablePages = true, $bTableHeaders = true, $bTableHeaderPages = false)
    {
        $this->table = Pelican_Factory::getInstance('List', $sTableId, $sTableWidth, $iTableCellpadding, $iTableCellspacing, $iTableBorder, $sTableClass, $sTableStyle, $bTablePages, $bTableHeaders, $bTableHeaderPages);
    }

    /**
     * Définition de la variable Bind qui sert à filtrer les données au sein de la
     * hiérarchie
     *
     * <code>
     * $strSqlList .= " AND faq.THEME_FAQ_ID=:FAQ";
     * ---------------------------------------------
     * $oHierarchy->setBind(":FAQ", "THEME_FAQ_ID");
     * </code>
     *
     * @access public
     * @param  string $bind  Identifiant du Bind
     * @param  string $field Champ contenant la valeur du Bind
     * @return void
     */
    public function setBind($bind, $field)
    {
        $this->aBind[$bind] = $field;
    }

    /**
     * Définition des valeurs à utiliser pour l'affichage du tableau dans la
     * hiérarchie
     *
     * Cela peut-être une chaine SQL ou un tableau de valeurs
     * ATTENTION, elle doit contenir une variable "bind" et doit être précédée de
     * la méthode "setBind"
     *
     * @access public
     * @param mixed $aTableValues Chaine SQL SELECT ou tableau de données au format
     * du queryTab de la classe Pelican_Db
     * @param string $sFieldId (option) Champ (Attention aux alias de table) ou
     * expression GROUP BY à utiliser pour le comptage des enregistrements
     * @param string $aGroupeField (option) Nom du champ contenant la valeur à
     * utiliser pour faire des regroupements de valeurs dans le tableau
     * @param  mixed $aBind (option) Paramètres Bind de la requête
     * @return void
     */
    public function setValues($aTableValues, $sFieldId = "", $aGroupeField = "", $aBind = array())
    {
        $this->listValues = array($aTableValues, $sFieldId, $aGroupeField, $aBind);
    }

    /**
     * Affichage d'éléments permettant de définir l'ordre des éléments de la
     * hiérarchie entre eux (par niveau) en base de données
     *
     * Des flèches Haut-Bas sont créées à droite du tableau. Le traitement se
     * fait en direct
     *
     * <code>
     * $oHierarchy->setHierarchyOrder("#pref#_THEME_FAQ",
     * "THEME_FAQ_ID", "THEME_FAQ_ORDER", "THEME_FAQ_PARENT_ID");
     * </code>
     *
     * @access public
     * @param string $sTable   Table à mettre à jour
     * @param string $sFieldId Champ identifiant (la valeur de ce champ pour chaque
     * ligne du tableau est utilisée en paramètre de la fonction de mise à jour de
     * l'ordre)
     * @param string $sFieldOrder  Champ contenant l'information de classement
     * @param string $sFieldParent (option) Champ optionnel de limitation du tri (par
     * défaut l'ordre est recalculé pour tous les enregistrements, si ce paramètre
     * est défini il n'est recalculé que pour les enregistrement ayant la même
     * valeur dans ce champ "père")
     * @param  string $sComplementWhere (option) __DESC__
     * @return void
     */
    public function setHierarchyOrder($sTable, $sFieldId, $sFieldOrder, $sFieldParent = "", $sComplementWhere = "")
    {
        $this->aHierarchyOrderParams = array("table" => $sTable, "id" => $sFieldId, "order" => $sFieldOrder, "parent" => $sFieldParent, "complementWhere" => $sComplementWhere);
    }
}
/*
<script>
var activeButton;
function showButton(objName)
{
var Pelican_Index_Backoffice_Button = getButtonPosition(document.getElementById(objName));
var id = objName.replace("button","");
var obj = document.getElementById(id);
if (activeButton) {
if (activeButton != id) {
var objtemp = document.getElementById(activeButton);
objtemp.style.display = "none";
}
}
obj.style.display = (obj.style.display?"":"none");
obj.style.left = button.x - obj.offsetWidth + document.getElementById(objName).offsetWidth;
activeButton = ""
if (obj.style.display != "none") {
activeButton = id;
}
}

function getButtonPosition(elem)
{
var r = { x: elem.offsetLeft, y: elem.offsetTop };
if (elem.offsetParent) {
var tmp = getButtonPosition(elem.offsetParent);
r.x += tmp.x;
r.y += tmp.y;
}
return r;
}
</script>
*/
