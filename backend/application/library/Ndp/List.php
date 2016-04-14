<?php
/**
 * Gestion des pages.
 *
 * @copyright Copyright (c) 2001-2013 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
require_once "Pelican/List.php";

class Ndp_List extends Pelican_List
{

    /**
     * @var array
     */
    protected $tableAttributes;


    /**
     * @return array
     */
    public function getTableAttributes()
    {
        return $this->tableAttributes;
    }

    /**
     * @param array $tableAttributes
     *
     * @return $this
     */
    public function setTableAttributes($tableAttributes)
    {
        $this->tableAttributes = $tableAttributes;

        return $this;
    }


    public function addTextarea($label, $attributes = "", $align = "", $aShow = "", $class = "tblheader", $iLigne = 0, $iColSpan = 1, $iRowSpan = 1, $headerLabel = "")
    {
        $this->aTableStructure[$iLigne][] = Pelican_Factory::getInstance('List.Row', $headerLabel, $label, "", $align, "textarea", $class, "", "textarea", $attributes, $aShow, false, false, $iColSpan, $iRowSpan, $this->iNbColumn);
        $this->iNbColumn++;
    }

    /**
     * Création du formulaire caché utilisé par les filtres automatiques.
     *
     * @access public
     *
     * @return string
     */
    public function getFilterForm()
    {
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
                ".($this->filterDirect ? "filter_submit();" : "")."
                }
                function filter_submit() {";
        if (isset($this->oFilterForm)) {
            $filter_script .= " if ( CheckFormSaisieFilter(document.getElementById('".$this->oFilterForm->sFormName."')) )\n";
        }
        $filter_script .= "document.filter_form.submit();
                }
                function filter_KeyDown(event) {
                if ( event.keyCode == 13 ) {
                var target = event.target || event.srcElement;";

        if ($_SESSION[ENV]["LOCAL"]["NAVIGATOR"]["ie"] == '9') {
            $filter_script .= "event.preventDefault();";
        }

        $filter_script .= " 
                event.returnValue = false;
                filter_change(target);
                filter_submit();
                }
                }
                ";
        $filter_script .= "
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

        /* Formulaire de filtre */
        reset($_GET);
        $filter_form = "";
        foreach ($_GET as $name => $value) {
            if (substr($name, 0, 3) != "nav" && substr($name, 0, 7) != "filter_") {
                $filter_form .= Pelican_Html_Input::hidden($name, Pelican_Text::htmlentities(stripslashes($value)), array( 'id' => $name));
            }
        }
        foreach ($this->aFilter as $filterField) {
            $filter_form .= Pelican_Html_Input::hidden($filterField["name"], Pelican_Text::htmlentities(stripslashes($_GET[$filterField["name"]])),  array('id' => $filterField["name"]."2"));
        }
        $filter_form = Pelican_Html::form(array('name' => "filter_form", 'id' => "filter_form",'method' => "get", 'action' => (!empty($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : $_SERVER['SCRIPT_NAME'])), $filter_form);

        return $filter_script.$filter_form;
    }

    public function getTable($groupby = false, $bNoRecordText = true, $aBind = array())
    {
        $html_body = "";
        $pixel = Pelican_Html::img(array('src' => $this->_sLibPath.'/public/images/pixel.gif', 'width' => 1, 'height' => 1, 'alt' => ' ','border' => '0'));
        $oConnection = Pelican_Db::getInstance();
        if ($this->aTableValues || $bNoRecordText === false) {
            $html_css = "";
            $html = "";
            $tagHeader = "th";
            $bSomme = false;
            // ajout des lignes additionnelles 'top'
            if ($this->aTableAddedRows["top"]) {
                foreach ($this->aTableAddedRows["top"] as $row) {
                    $html .= $row;
                }
            }
            // Cr�ation de la variable de d�finition du tableau
            $aRealTableStructure = array();
            //HEAD//
            // Pour chaque ligne d�finie (iRow) on construit les ent�tes
            $thead = "";
            $countStructure = count($this->aTableStructure);
            if ($countStructure > 0) {
                for ($iRow = 0; $iRow < $countStructure; $iRow++) {
                    $theadtr = "";
                    foreach ($this->aTableStructure[$iRow] as $header) {
                        // si la colonne doit �tre affich�e (on exclu de la structure des colonnes les addHeader)
                        // pour l'affichage des colonnes on construit un tableau respectant l'ordre d'appel des colonnes (propri�t� iNumColumn)
                        if ($header->iNumColumn) {
                            $aRealTableStructure[$header->iNumColumn] = $header;
                        }
                        $label = array();
                        $label[] = $header->sHeaderLabel;
                        foreach ($label as $entete) {
                            // si le libell� est un tableau ("image"=>,"label"=>) alors on remplace le libell� par l'image
                            if (is_array($entete)) {
                                $entete = Pelican_Html::img(array('src' => $entete["image"], 'alt' => $entete["label"], 'border' => 0));
                            }
                            if ($this->bTableHeaders) {
                                // si le libell� est d�fini on met un tag d'en t�te sinon c'est un td (� cause des feuilles de style)
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
                                $attr = array('colspan' => $header->iColSpan, 'rowspan' => $header->iRowSpan, 'class' => $header->sHeaderClass.($this->isOrder ? "On" : ""), 'width' => $this->setColWidth($header->sColumnWidth), 'more_attr' => $this->getExcelParams());
                                $args = $orderSign;
                                if ($typeTag == "td") {
                                    $theadtr .= Pelican_Html::td($attr, $args);
                                } else {
                                    $theadtr .= Pelican_Html::th($attr, $args);
                                }
                            } else {
                                $theadtr .= Pelican_Html::td(array('class' => 'tblEmptyHeader', 'colspan' => (($header->iColSpan == 1) ? 0 : $header->iColSpan), 'rowspan' => (($header->iRowSpan == 1) ? 0 : $header->iRowSpan), 'width' => $this->setColWidth($header->sColumnWidth), 'height' => 1), $pixel);
                            }
                        }
                    }
                    if ($this->aTableOrderParams) {
                        $theadtr .= Pelican_Html::td(array('class' => "tblOrder"), $pixel);
                        $theadtr .= Pelican_Html::td(array('class' => "tblEmptyHeader"), $pixel);
                    }
                    $thead .= Pelican_Html::tr($theadtr);
                }
            }
            ksort($aRealTableStructure);
            if ($this->navRows && $this->bTableHeaderPages) {
                $theadtr = Pelican_Html::td(array('valign' => 'middle', 'colspan' => count($aRealTableStructure)), $this->getPages());
                $thead = Pelican_Html::tr(array(), $theadtr).$thead;
            }
            $html .= Pelican_Html::thead($thead);
            //FOOT//
            // Affichage des pieds de page
            // le tfoot est d�fini avant le tbody pour acc�l�rer l'affichage (principe des thead, tfoot et tbody)
            if ($this->navRows && $this->bTablePages) {
                $tfoottr = Pelican_Html::td(array('valign' => 'middle', 'colspan' => count($aRealTableStructure)), $this->getPages("", "", "|", "", "", "", $groupby));
                $tfoot = Pelican_Html::tr(array(), $tfoottr);
                $html .= Pelican_Html::tfoot($tfoot);
            }
            //BODY//
            // ajout des lignes additionnelles 'afterHeader'
            if ($this->aTableAddedRows["afterHeader"]) {
                foreach ($this->aTableAddedRows["afterHeader"] as $row) {
                    $html_body .= $row;
                }
            }
            // Pour chaque ligne on cr�ee le Pelican_Html ad�quat et on l'agr�ge au niveau des champs de regroupement (vide si pas d�fini)
            $lineId = 0;
            $idTr = 0;
            if ($this->aTableValues) {
                foreach ($this->aTableValues as $values) {
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
                    if (!isset($this->aLines[$groupe[0]]["count"])) {
                        $this->aLines[$groupe[0]]["count"] = 0;
                    }
                    $this->aLines[$groupe[0]]["count"]++;
                    if (!isset($this->aLines[$groupe[0]][$groupe[1]]["count"])) {
                        $this->aLines[$groupe[0]][$groupe[1]]["count"] = 0;
                    }
                    $this->aLines[$groupe[0]][$groupe[1]]["count"]++;
                    if (!isset($values[$this->sCssField])) {
                        $values[$this->sCssField] = "";
                    }
                    if (!isset($values[$this->sContextCssField])) {
                        $values[$this->sContextCssField] = "";
                    }
                    $this->getCSS($this->aLines[$groupe[0]][$groupe[1]], $values[$this->sCssField], $values[$this->sContextCssField]);
                    // Ligne
                    $iSomme = 0;
                    $column_number = 0;
                    $tr_value = "";
                    foreach ($aRealTableStructure as $column) {
                        $column_number++;
                        $id = $this->sTableId."_".$lineId."_".$column_number;

                        $bShow = self::showCol($column->aShow, $values);
                        // CELLULE
                        $td_value = "";
                        $widthImg = "";
                        $heightImg = "";
                        if (!isset($values[$column->sColumnField])) {
                            $values[$column->sColumnField] = '';
                        }
                        $name = $this->sTableId.'['.$values['ID'].']['.$column->sColumnField.']';

                        if ($bShow) {
                            switch ($column->sColumnType) {
                                case "input":
                                    $td_value = self::makeInput($column, $values, $id, $name);
                                    break;

                                case "textarea":
                                    $td_value = self::makeTextarea($column, $values, $id, $name);
                                    break;

                                case "image":
                                    $image = $values[$column->sColumnField];
                                    if ($column->sColumnFormat == 'extension') {
                                        $image = $this->setFormat($column->sColumnFormat, $values[$column->sColumnField]);
                                    }
                                    if (!$image) {
                                        $image = $this->_sLibPath."/public/images/pixel.gif";
                                    } else {
                                        if (is_array($column->aColumnAttributes)) {
                                            if ($column->aColumnAttributes["_function_"]) {
                                                $sFunc = "";
                                                reset($column->aColumnAttributes);
                                                foreach ($column->aColumnAttributes as $kFP => $vFP) {
                                                    $sFP = substr($kFP, 0, 22);
                                                    if ($sFP == "_function_param_field_") {
                                                        $sFunc .= (($sFunc) ? "," : "")."\$values[\"".$vFP."\"]";
                                                    } elseif ($sFP == "_function_param_value_") {
                                                        $sFunc .= (($sFunc) ? "," : "").$vFP;
                                                    }
                                                }
                                                $sFunc = "\$image = ".$column->aColumnAttributes["_function_"]."(".$sFunc.");";
                                                eval($sFunc);
                                            } elseif ($column->aColumnAttributes["_folder_"]) {
                                                $image = $column->aColumnAttributes["_folder_"].$image.".".($column->aColumnAttributes["_extension_"] ? $column->aColumnAttributes["_extension_"] : "gif");
                                            }
                                            $widthImg = (($column->aColumnAttributes["_width_"]) ? $column->aColumnAttributes["_width_"] : "");
                                            $heightImg = (($column->aColumnAttributes["_height_"]) ? $column->aColumnAttributes["_height_"] : "");
                                        } else {
                                            $image = $column->aColumnAttributes.$image.".gif";
                                        }
                                    }
                                    $td_value = Pelican_Html::img(array('id' => $id, 'src' => $image, 'alt' => $values[$column->sColumnOrderField], 'border' => 0, 'align' => "middle", 'width' => $widthImg, 'height' => $heightImg));

                                    break;

                                case "multi":
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
                                    if (is_numeric($values[$this->cleanOrder($column->sColumnField)])) {
                                        $query = $column->aColumnAttributes.$join.$column->sColumnField."=".$values[$this->cleanOrder($column->sColumnField)]." ".$queryLimit;
                                    } else {
                                        $query = $column->aColumnAttributes.$join.$column->sColumnField."=".$oConnection->strTobind($values[$this->cleanOrder($column->sColumnField)])." ".$queryLimit;
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

                                case "combo":
                                    if ($column->aColumnAttributes['isResultsArray'] == '1') {
                                        $cmb = $column->aColumnAttributes['src'];
                                    } else {
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
                                            $query = $column->aColumnAttributes["src"].$join.$column->sColumnField."=".$values[$this->cleanOrder($column->sColumnField)];
                                            $cmb = $oConnection->queryTab($query, $aBind);
                                        }
                                    }
                                    if ($cmb) {
                                        if (!isset($column->aColumnAttributes["empty"]) || $column->aColumnAttributes["empty"]) {
                                            $td_value = Pelican_Html::option("");
                                        }
                                        foreach ($cmb as $option) {
                                            $selected = false;

                                            if ($option["id"] == $values[$column->aColumnAttributes["selected"]]) {
                                                $selected = true;
                                            }
                                            $td_value .= Pelican_Html::option(array('value' => $option["id"], 'selected' => $selected), Pelican_Text::htmlentities($option["lib"]));
                                        }
                                        $td_value = Pelican_Html::select(array('name' => $name, 'id' => "combo".$lineId, 'onchange' => $column->aColumnAttributes["function"]."(this,'".$values[$this->cleanOrder($column->sColumnField)]."');"), $td_value);
                                    } else {
                                        $td_value = "&nbsp;";
                                    }
                                    break;

                                default:
                                    // Gestion du lien sur la valeur de la colonne
                                    if ($column->onClick != '') {
                                        // Transformation des ' en "
                                        $column->onClick = str_replace('\'', '"', $column->onClick);
                                        // Remplacement de la valeur du champ de la colonne
                                        $column->onClick = str_replace('[fieldValue]', $values[$column->sColumnField], $column->onClick);
                                        $td_value = trim($this->setFormat($column->sColumnFormat, '<a href=\''.$column->onClick.'\'>'.$values[$column->sColumnField].'</a>'));
                                    } else {
                                        $td_value = trim($this->setFormat($column->sColumnFormat, $values[$column->sColumnField]));
                                    }
                                    if ($column->sTooltip) {
                                        $td_value = Pelican_Html::a(array('class' => "tooltip", 'onmouseover' => "return makeTrue(domTT_activate(this, event, 'content', '".str_replace("'", "\'", htmlspecialchars(str_replace("\r\n", Pelican_Html::br(), "".$values[$column->sTooltip]."")))."', 'trail', true));"), $td_value);
                                    }
                            }
                        } else {
                            $td_value = "&nbsp;";
                        }
                        $tr_value .= Pelican_Html::td(array('id' => "td_".$id, 'align' => $column->sColumnAlign), $td_value);
                        //Somme
                        if (is_numeric($values[$column->sColumnField])) {
                            if (!isset($this->aLines[$groupe[0]][$groupe[1]]["somme"][$iSomme])) {
                                $this->aLines[$groupe[0]][$groupe[1]]["somme"][$iSomme] = 0;
                            }
                            $this->aLines[$groupe[0]][$groupe[1]]["somme"][$iSomme] += $values[$column->sColumnField] + 0;
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
                        $tr_value .= Pelican_Html::td(array('class' => "tblOrder"), Pelican_Html::img(array(src => $this->_sLibPath.$this->_sLibList."/images/ordre_plus.gif", width => 12, height => 12, alt => "Descendre", border => 0, onclick => "listOrder('".rawurlencode($values[$this->aTableOrderParams["id"]])."',1)", style => "cursor:pointer;")).Pelican_Html::img(array(src => $this->_sLibPath.$this->_sLibList."/images/ordre_moins.gif", width => 12, height => 12, alt => "Monter", border => 0, onclick => "listOrder('".rawurlencode($values[$this->aTableOrderParams["id"]])."',-1)", style => "cursor:pointer;"))).Pelican_Html::td(array('class' => "tblOrder"), $pixel);
                    }
                    // Ev�nement de ligne
                    $tr_event = "";
                    if ($this->aRowEvent) {
                        foreach ($this->aRowEvent as $event) {
                            //        $event["attributes"]["_javascript_"] = $event["fonction"];
                            $fonction = $event["fonction"]."(".self::makeURL($event["attributes"], $values, "", true).")";
                            $tr_event .= $event["event"]."=\"".$fonction.";\" ";
                            if ($event["style"]) {
                                $tr_event .= " style=\"".$event["style"]."\"";
                            }
                        }
                    }
                    if (!isset($this->aLines[$groupe[0]][$groupe[1]]["html"])) {
                        $this->aLines[$groupe[0]][$groupe[1]]["html"] = "";
                    }
                    $idTr++;
                    $this->aLines[$groupe[0]][$groupe[1]]["html"] .= Pelican_Html::tr(array('id' => $this->sTableId."tr_".$idTr, 'class' => $this->aLines[$groupe[0]][$groupe[1]]["css"], 'more_attr' => $tr_event), $tr_value);
                    $this->aListTr[] = $this->sTableId."_".$idTr;
                }
            }
            // affichage des lignes
            if ($this->aLines) {
                reset($this->aLines);
                $html_ligne = "";
                foreach ($this->aLines as $Groupe) {
                    if ($Groupe["label"] && count($Groupe) > 1) {
                        if (!isset($Groupe["bgcolor"])) {
                            $Groupe["bgcolor"] = "";
                        }
                        $html_td = Pelican_Html::td(array('valign' => "middle", 'colspan' => count($aRealTableStructure), 'bgcolor' => $Groupe["bgcolor"]), Pelican_Html::nobr($Groupe["label"]."&nbsp;&nbsp;[".$Groupe["count"]."]"));
                        if ($this->aTableOrderParams) {
                            $html_td .= Pelican_Html::td(array('class' => "tblOrder", 'colspan' => 2), $pixel);
                        }
                        $html_ligne .= Pelican_Html::tr(array('class' => $this->cssGroupe), $html_td);
                    }
                    foreach ($Groupe as $lines) {
                        if (count($lines) > 1) {
                            if ($lines["label"]) {
                                $html_td = Pelican_Html::td(array(valign => "middle", 'colspan' => count($aRealTableStructure), 'bgcolor' => $Groupe["bgcolor"]), Pelican_Html::nobr("&nbsp;&nbsp;".$lines["label"]."&nbsp;&nbsp;[".$lines["count"]."]"));
                                if ($this->aTableOrderParams) {
                                    $html_td .= Pelican_Html::td(array('class' => "tblOrder", 'colspan' => 2), $pixel);
                                }
                                $html_ligne .= Pelican_Html::tr(array('class' => $this->cssGroupe), $html_td);
                            }
                            $html_ligne .= $lines["html"];
                            //affichage de la somme
                            $html_td = "";
                            if ($bSomme) {
                                $countSomme = count($lines["somme"]);
                                for ($i = 0; $i < $countSomme; $i++) {
                                    if ($lines["somme"][$i] != "--" || !$lines["somme"][$i]) {
                                        $html_td .= Pelican_Html::td(array('class' => "tblTotal", 'align' => $lines["align"][$i]), $this->setFormat($lines["format"][$i], $lines["somme"][$i]));
                                    } else {
                                        $html_td .= Pelican_Html::td("&nbsp;");
                                    }
                                }
                                if ($this->aTableOrderParams) {
                                    $html_td .= Pelican_Html::td(array('class' => "tblOrder", 'colspan' => 2), $pixel);
                                }
                                $html_ligne .= Pelican_Html::tr($html_td);
                            }
                        }
                    }
                }
                $html_body .= $html_ligne;
            }
            // ajout des lignes additionnelles 'beforeNavRows'
            if ($this->aTableAddedRows["beforeNavRows"]) {
                foreach ($this->aTableAddedRows["beforeNavRows"] as $row) {
                    $html_body .= $row;
                }
            }
            // ajout des lignes additionnelles 'bottom'
            if ($this->aTableAddedRows["bottom"]) {
                foreach ($this->aTableAddedRows["bottom"] as $row) {
                    $html_body .= $row;
                }
            }
            $html_body = Pelican_Html::tbody(array('id' => $this->sTableId."tbody"), $html_body);
            $attributes = array('id' => $this->sTableId, 'width' => $this->sTableWidth, 'cellpadding' => $this->iTableCellpadding, 'cellspacing' => $this->iTableCellspacing, 'border' => $this->iTableBorder, 'class' => $this->sTableClass, 'style' => $this->sTableStyle, 'summary' => "Données");
            $attributes = array_merge($attributes, $this->tableAttributes);
            $html = $html_css.Pelican_Html::table($attributes, $html.$html_body);
        } else {
            $html = Pelican_Html::div(array('class' => "erreur"), t('TABLE_NO_RECORD'));
        }
        if ($this->bFiltered) {
            $html .= $this->getFilterForm();
        }
        // Fl�ches de d�finition des ordres
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
            $html .= Pelican_Html::jscript("function listOrder(id,ordre) {
					window.document.location.href='".$this->_sLibPath.$this->_sLibList."/order.php?id=' + id + '&sens=' + ordre;
					}");
        }
        $html = str_replace(" colspan=\"0\"", "", $html);
        $html = str_replace(" rowspan=\"0\"", "", $html);
        if ($this->filterForm) {
            $html = $this->filterForm.'<br />'.$html;
        }

        return $html;
    }

    public static function makeTextarea($column, $values, $id = "", $name = '')
    {
        $value = $values[$column->sColumnField];

        $input = Pelican_Html::textarea(array('name' => (empty($name) ? $id : $name), 'id' => $id, 'class' => $column->aColumnAttributes['class'], "onchange" => $column->aColumnAttributes["_onchange"]), $value);

        return $input;
    }

    public static function makeInput($column, $values, $id = "", $name = '')
    {
        // url
        $checked = false;
        if (isset($column->aColumnAttributes["_target_"])) {
            if ($column->aColumnAttributes["_target_"] == "_blank") {
                if (isset($column->aColumnAttributes["_target_option_"])) {
                    $url = "window.open('".self::makeURL($column->aColumnAttributes, $values)."','','".$column->aColumnAttributes["_target_option_"]."')";
                } else {
                    $url = "window.open('".self::makeURL($column->aColumnAttributes, $values)."')";
                }
            }
        } elseif (isset($column->aColumnAttributes["_javascript_"])) {
            $url = $column->aColumnAttributes["_javascript_"]."(".self::makeURL($column->aColumnAttributes, $values, "", true).")";
        } else {
            $url = "document.location.href='".self::makeURL($column->aColumnAttributes, $values)."'";
        }
        // td
        if (isset($column->aColumnAttributes["_image_"])) {
            $input = Pelican_Html::img(array(id => $id, src => $column->aColumnAttributes["_image_"], alt => $column->sColumnField, border => 0, align => "middle", onclick => $url, style => "cursor:pointer;"));
        } elseif (isset($column->aColumnAttributes["_span_"])) {
            $input = Pelican_Html::span(array('class' => $column->aColumnAttributes["_span_"], onclick => $url, style => "cursor:pointer;"), $column->sColumnField);
        } else {
            if (($column->sColumnFormat == "checkbox") || ($column->sColumnFormat == "radio")) {
                $id = $column->sColumnFormat.$id; // ajout du 28/09/2007
                $checked = false;
                if (isset($values[$column->sColumnField])) {
                    $checked = ($values[$column->sColumnField] == 1);
                }
                if ($column->aColumnAttributes["_value_field_"]) {
                    $value = $values[$column->aColumnAttributes["_value_field_"]];
                }
                if (empty($value) && $column->sColumnFormat == "checkbox") {
                    $value = '1';
                }
            } else {
                $value = $values[$column->sColumnField];
            }
            if ($column->sColumnFormat != "button") {
                $input = Pelican_Html::input(array('type' => $column->sColumnFormat, 'value' => $value, 'checked' => $checked, 'onclick' => $url, 'onchange' => $column->aColumnAttributes["_onchange"], 'name' => (empty($name) ? $id : $name), 'id' => $id, 'class' => $column->sColumnFormat));
            } else {
                $input = Pelican_Html::button(array('value' => $column->sColumnField, 'checked' => $checked, 'onclick' => $url, 'name' => $id, 'id' => $id, 'class' => $column->sColumnFormat), $column->sColumnField);
            }
        }

        return $input;
    }
}
