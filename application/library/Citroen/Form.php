<?php
/**
 * Gestion des formulaires de saisie avec contrôles de saisie centralisée
 *
 * @package Pelican
 * @subpackage Form
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
require_once('Pelican/Form.php');

class Citroen_Form extends Pelican_Form
{

    protected $currentView;

    /**
     * Déclaration du formulaire
     *
     * @access public
     * @param string $strAction (option) Champ action du formulaire
     * @param string $strMethod (option) Champ method du formulaire
     * @param string $strName (option) Champ name du formulaire
     * @param bool $bUpload (option) Vrai si le formulaire est destiné à contenir un
     * upload de fichier
     * @param bool $bCheck (option) Envoi du formulaire soumis à vérification
     * @param string $sCheckFunction (option) Fonction a appeler pour vérification du
     * formulaire
     * @param string $sTarget (option) Target du formulaire
     * @param bool $bBlockSubmit (option) Permet d'empêcher les submit multiples
     * (Vrai par défaut)
     * @param bool $bVirtualKeyboard (option) __DESC__
     * @return string
     */
    public function open($strAction = "", $strMethod = "post", $strName = "fForm", $bUpload = false, $bCheck = true, $sCheckFunction = "CheckForm", $sTarget = "", $bBlockSubmit = true, $bVirtualKeyboard = true)
    {

        if (isset($_GET['tid']) && (intval($_GET['tid']) == Pelican::$config['TEMPLATE_ADMIN_CONTENT'])) {
            $bBlockSubmit = false;
        }

        $this->sFormName = $strName;
        $this->bBlockSubmit = $bBlockSubmit;
        $method = "post";
        $enctype = "";
        if ($bUpload) {
            $method = "post";
            $enctype = "multipart/form-data";
            $max = 1048576 * (int) ini_get("upload_max_filesize");
            $this->createHidden("MAX_FILE_SIZE", $max);
        } elseif ($strMethod) {
            $method = $strMethod;
            $enctype = "";
        }
        if ($bCheck) {
            $onsubmit = "return ".$sCheckFunction."(this);";
            $this->sCheckFunction = $sCheckFunction;
        }
        $strTmp = str_replace("</form>", "", Pelican_Html::form(array(name => $strName, id => $strName, action => $strAction, target => $sTarget, method => $method, enctype => $enctype, onsubmit => $onsubmit, style => "margin:0 0 0 0;", "class" => "fwForm")));
        $this->bVirtualKeyboard = $bVirtualKeyboard;
        return $this->output($strTmp);
    }

    /**
     * Génère un champ input de type Text
     *
     * @access public
     * @example Création d'un champ input présentant le nom d'un utilisateur :
     * @param  string   $strName    Nom du champ
     * @param  string   $strLib     Libellé du champ
     * @param  string   $iMaxLength (option) Nb de caractères maximum : 255 par défaut
     * @param  string   $strControl (option) Type de contrôle js utilisé : numerique ou
     *                              number, float, flottant, real ou reel, telephone, mail, date
     * @param  bool     $bRequired  (option) Champ obligatoire : false par défaut
     * @param  string   $strValue   (option) Valeur du champ
     * @param  bool     $bReadOnly  (option) Affiche uniquement la valeur et pas le champ
     *                              (créé un input hidden) : false par défaut
     * @param  string   $iSize      (option) Taille d'affichage du champ : 10 par défaut
     * @param  bool     $bFormOnly  (option) Génération du champ uniquement, sans libellé
     *                              : false par défaut
     * @param  string   $strEvent   (option) Handler d'événements sur le champ : "" par
     *                              défaut
     * @param  string   $strType    (option) Type de l'input ("text" par défaut)
     * @param  __TYPE__ $aSuggest   (option) __DESC__
     * @param  bool     $multiple   (option) __DESC__
     * @param  string   $sInfoBull  (option) champs permettant d'afficher une info-bulle
     * @return string
     */
    public function createInput($strName, $strLib, $iMaxLength = "255", $strControl = "", $bRequired = false, $strValue = "", $bReadOnly = false, $iSize = "10", $bFormOnly = false, $strEvent = "", $strType = "text", $aSuggest = array(), $multiple = false, $sInfoBull = "")
    {
        /** initialisation */
        $strValue = str_replace("\"", "&quot;", $strValue);
        $add = "";
        $strMessage = "";
        if ($bReadOnly) {
            $val = $strValue;
            if (!$bFormOnly && $strValue) {
                if ($strType == "file") {
                    $val = Pelican_Html::button(array(style => "cursor:pointer;", onclick => "window.open('".$strValue."');"), "Télécharger");
                } else {
                    switch ($strControl) {
                        case "color": {
                                $add = Pelican_Html::nbsp().Pelican_Html::span(array(id => "color".$strName, style => "border: 1px solid;background-color: ".$strValue.";"), Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp());
                                break;
                            }
                        case "mail": {
                                $add = Pelican_Html::nbsp().Pelican_Html::a(array(href => "mailto:".$strValue), Pelican_Html::img(array(src => $this->_sLibPath.$this->_sLibForm."/images/mail.gif", alt => "", border => "0", align => "bottom")));
                                break;
                            }
                    }
                }
            }
            $add.= $this->createHidden($strName, str_replace("\"", "&quot;", $strValue));
            $strTmp = $val.$add;
        } else {
            if (!$this->_sDefaultFocus) {
                $this->_sDefaultFocus = $strName;
            }

            /** Ajouts */
            if ($aSuggest) {
                $this->_aIncludes["suggest"] = true;
                $params[autocomplete] = "off";
                if (!is_array($aSuggest)) {
                    $aSuggest = array($aSuggest);
                }
                $this->suggest[$strName] = $aSuggest;
                $add = Pelican_Html::nbsp().Pelican_Html::img(array(src => $this->_sLibPath.$this->_sLibForm."/images/combo.gif", alt => "", border => "0", onclick => "showSuggest('".$strName."');", "class" => "combo_suggest"));
            }
            $class = "text";
            switch ($strControl) {
                case "numerique":
                case "number":
                case "flottant":
                case "float":
                case "real":
                case "reel": {
                        $params[style] = "text-align:right;";
                        break;
                    }
                case "mail": {
                        if ($strValue) {
                            $add.= Pelican_Html_Form::imgComment($this->_sLibPath.$this->_sLibForm."/images/mail.gif", "mailto:".$strValue);
                        }
                        break;
                    }
                case "internallink": {
                        $this->_aIncludes["popup"] = true;
                        $add.= Pelican_Html_Form::imgComment($this->_sLibPath.$this->_sLibForm."/images/internal_link.gif", "", "return popupInternalLink(document.".$this->sFormName.".".$strName.")", t('EDITOR_INTERNAL'));
                        break;
                    }
                case "shortdate":
                case "date":
                case "calendar": {
                        $class.= " datepicker";
                        ////$add .= Pelican_Html_Form::imgComment($this->_sLibPath . $this->_sLibForm . "/images/cal.gif", "javascript://", "popUpCalendar(this, " . $this->sFormName . "." . $strName . ")", "");
                        break;
                    }
                case "date_edition": {
                        $add.= Pelican_Html_Form::comment("(".t('DATE_FORMAT_LABEL_EDITION').")");
                        break;
                    }
                case "heure": {
                        $add.= Pelican_Html_Form::comment("(".t('HOUR_FORMAT_LABEL').")");
                        break;
                    }
                case "color": {
                        $class.= " colors";
                        $this->_aIncludes["color"] = true;
                        $this->setJquery('minicolors');
                        break;
                    }
            }
            $params[type] = $strType;
            $params["class"] = $class;
            $params[name] = $strName;
            $params[id] = $strName;
            $params[size] = $iSize;
            $params[maxlength] = $iMaxLength;
            $params[value] = $strValue;
            if ($multiple) {
                $params['multiple'] = 1;
                $params[name] = $strName.'[]';
            }
            if ($this->bVirtualKeyboard && $strType == "text") {
                $params['onfocus'] = "activeInput = this;PopupVirtualKeyboard.attachInput(this);";
                $this->_InputVK[] = $strName;
            }
            $this->countInputName($strName);
            $strTmp = Pelican_Html::input($params);
            if ($multiple) {
                $strTmp.= '<br />'.t('POPUP_MEDIA_LABEL_NEW_FILE_COMMENT');
            }
            $strTmp = Pelican_Html_Form::addInputEvent($strTmp, $strEvent);
            $strTmp.= $add;
            // Génération de la fonction js de vérification.
            if ($bRequired || ($strControl != "" && $strControl != "color" && $strControl != "internallink")) {
                /* if ($bRequired) {
                  $this->_aIncludes["text"] = true;
                  $this->_sJS .= "if ( isBlank(obj." . $strName . ".value) ) {\n";
                  $this->_sJS .= "alert(\"" . t ( 'FORM_MSG_VALUE_REQUIRE' ) . " " . "\\" . "\"" . strtolower ( str_replace ( "\"", "" . "\\" . "\"", $strLib ) ) . "" . "\\" . "\".\");\n";
                  $this->_sJS .= "fwFocus(obj." . $strName . ");\n";
                  $this->_sJS .= "return false;\n";
                  $this->_sJS .= "}\n";
                  } */
                $this->_aIncludes["text"] = true;

                $this->_sJS.= "if (typeof obj.".$strName." != 'undefined' && obj.".$strName." !=null ){ ";
                $this->_sJS.= "if ( ";
                if (!$bRequired) {
                    // Si le champ n'est pas requis, ne faire la vérification que si le champ n'est pas vide.
                    $this->_sJS.= "!isBlank(obj.".$strName.".value) ";
                }
                if ($strControl != "" && $strControl != "color" && $strControl != "internallink") {
                    if (!$bRequired) {
                        $this->_sJS.= "&& ";
                    }
                    switch ($strControl) {
                        case "alphanum": {
                                $this->_sJS.= "!isAlphaNum(obj.".$strName.".value)";
                                $this->_aIncludes["text"] = true;
                                $strMessage = t('FORM_MSG_ALPHANUM');
                                break;
                            }
                        case "numerique":
                        case "number": {
                                $this->_sJS.= "!isNumeric(obj.".$strName.".value)";
                                $this->_aIncludes["num"] = true;
                                $strMessage = t('FORM_MSG_NUMBER');
                                break;
                            }
                        case "float":
                        case "flottant":
                        case "real":
                        case "reel": {
                                $this->_sJS.= "!isFloat(obj.".$strName.".value)";
                                $this->_aIncludes["num"] = true;
                                $strMessage = t('FORM_MSG_REAL');
                                break;
                            }
                        case "telephone": {
                                $this->_sJS.= "!isTel(obj.".$strName.".value)";
                                $this->_aIncludes["num"] = true;
                                $strMessage = t('FORM_MSG_TELEPHONE');
                                break;
                            }
                        case "mail": {
                                $this->_sJS.= "!isMail(obj.".$strName.".value)";
                                $this->_aIncludes["text"] = true;
                                $strMessage = t('FORM_MSG_MAIL');
                                break;
                            }
                        case "URL": {
                                $this->_sJS.= "!isURL(obj.".$strName.".value)";
                                $this->_aIncludes["text"] = true;
                                $strMessage = t('FORM_MSG_URL');
                                break;
                            }
                        case "login": {
                                $this->_sJS.= "!isLogin(obj.".$strName.".value)";
                                $this->_aIncludes["text"] = true;
                                $strMessage = t('FORM_MSG_LOGIN');
                                break;
                            }
                        case "dateNF":
                        case "shortdate":
                        case "date":
                        case "calendar": {
                                $this->_sJS.= "!isDate(obj.".$strName.".value)";
                                $this->_aIncludes["date"] = true;
                                $strMessage = t('FORM_MSG_DATE');
                                break;
                            }
                        case "date_edition": {
                                $this->_sJS.= "!isDate_edition(obj.".$strName.".value)";
                                $this->_aIncludes["date"] = true;
                                $strMessage = t('FORM_MSG_DATE_EDITION');
                                break;
                            }
                        case "year": {
                                $this->_sJS.= "!isNumeric(obj.".$strName.".value && obj.".$strName.".value.length == 4)";
                                $this->_aIncludes["num"] = true;
                                $strMessage = t('FORM_MSG_YEAR');
                                break;
                            }
                        case "heure": {
                                $this->_sJS.= "!isHour(obj.".$strName.".value)";
                                $this->_aIncludes["date"] = true;
                                $strMessage = t('FORM_MSG_HEURE');
                                break;
                            }
                    }
                } else {
                    $this->_aIncludes["text"] = true;
                    // $this->_sJS.= "if (typeof obj." . $strName . " != 'undefined' && obj." . $strName . " !=null ){ ";
                    $this->_sJS.= "isBlank(obj.".$strName.".value) ";
                }

                $this->_sJS.= ") {\n";

                $this->_sJS.= "$('#'+obj.".$strName.".id+'').addClass('alert-blank');\n";
                $this->_sJS.= "$('#'+obj.".$strName.".id+'').parent().parent().addClass('alert-field-blank');\n";
                $this->_sJS.= "$('#'+obj.".$strName.".id+'').parents('table:first').parent().addClass('alert-blank');\n";
                $this->_sJS.= "$('#'+obj.".$strName.".id+'').parents('table:first').parent().parent().prev().find('td:first').addClass('alert-blank');\n";

                if ($strControl != "" && $strControl != "color" && $strControl != "internallink") {
                    $this->_sJS.= "alert(\"\\";
                    $this->_sJS.= " ".t('FORM_MSG_WITH')." ".$strMessage;
                    $this->_sJS.= ".\");\n";
                }



                $this->_sJS.= "$('#'+obj.".$strName.".id+'').keyup(function() {\n";
                $this->_sJS.= "$('#'+obj.".$strName.".id+'').removeClass('alert-blank');\n";
                $this->_sJS.= "$('#'+obj.".$strName.".id+'').parent().parent().removeClass('alert-field-blank');\n";
                $this->_sJS.= "$('#'+obj.".$strName.".id+'').parents('table:first').parent().removeClass('alert-blank');\n";
                $this->_sJS.= "$('#'+obj.".$strName.".id+'').parents('table:first').parent().parent().prev().find('td:first').removeClass('alert-blank');\n";
                $this->_sJS.= "$('#FIELD_BLANKS').val(0);\n";
                $this->_sJS.= "return false;\n";
                $this->_sJS.= "});\n";

                $this->_sJS.= "if($('#IS_PERSO').val()==1){\n";
                $this->_sJS.= "alert(\"".t('FORM_MSG_VALUE_REQUIRE')." \\"."\"".(strip_tags(str_replace("\"", "\\"."\"", $strLib)))."\\"."\"";
                $this->_sJS.= ".\");\n";
                $this->_sJS.= "fwFocus(o);\n";
                $this->_sJS.= "$('#FIELD_BLANKS').val(1);\n";
                $this->_sJS.= "return false;\n";
                $this->_sJS.= "}else{\n";
                $this->_sJS.= "$('#FIELD_BLANKS').val(1);\n";
                $this->_sJS.= "}\n";
                //$this->_sJS.= "$('#FIELD_BLANKS').val(1);\n";
                $this->_sJS.= "fwFocus(obj.".$strName.");\n";
                $this->_sJS.= "}\n";
                $this->_sJS.= "}\n";
            }
        }
        if ($sInfoBull) {
            $strLib = '<span title="'.$sInfoBull.'" style="cursor:help;">'.$strLib.'</span>';
        }
        if (!$bFormOnly) {
            // $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "", "", $this->_sFormDisposition);
            $strTmp = Pelican_Html_Form::get($strLib, $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "", "", $this->_sFormDisposition);
        }

        return $this->output($strTmp);
    }

    /**
     * Génère une association à partir de requêtes SQL
     *
     * @access public
     * @param  __TYPE__ $deprecated      __DESC__
     * @param  string   $strName         Nom du champ
     * @param  string   $strLib          Libellé du champ
     * @param  mixed    $strSQL          (option) Requête SQL des valeurs disponibles (id,lib) :
     *                                   "" par défaut
     * @param  mixed    $strSQLValues    (option) Requête SQL des valeurs sélectionnées
     *                                   (liste des id) : "" par défaut
     * @param  bool     $bRequired       (option) Champ obligatoire : false par défaut
     * @param  bool     $bDeleteOnAdd    (option) Supprimer les valeurs de la liste source
     *                                   après ajout à la liste destination : true par défaut
     * @param  bool     $bReadOnly       (option) Affiche uniquement la valeur et pas le champ
     *                                   (créé un input hidden) : false par défaut
     * @param  __TYPE__ $iSize           (option) __DESC__
     * @param  string   $iWidth          (option) Largeur du contrôle : 200 par défaut
     * @param  bool     $bFormOnly       (option) Génération du champ uniquement, sans libellé
     *                                   : false par défaut
     * @param  string   $arForeignKey    (option) Remplace la Pelican_Index_Frontoffice_Zone
     *                                   de recherche par une liste déroulante pour filtrer la sélection (nécessite
     *                                   bSearchEnabled à true) : 3 modes : - 1 : nom de table de référence de la clé
     *                                   étrangère (sans le préfixe) => la requête de liste et de recherche seront
     *                                   alors générique - 2 : array(nom de table de référence de la clé
     *                                   étrangère, SQL avec id et lib dans le select pour la liste déroulante) => la
     *                                   requête de recherche sera alors générique - 3 : array(nom de table de
     *                                   référence de la clé étrangère, SQL avec id et lib dans le select pour la
     *                                   liste déroulante, SQL avec id et lib dans le select pour la recherche et
     *                                   :RECHERCHE: dans la clause where)
     * @param  array    $arSearchFields  (option) Liste des champs sur lesquels effectuer
     *                                   une recherche par like
     * @param  __TYPE__ $aBind           (option) __DESC__
     * @param  string   $strOrderColName (option) __DESC__
     * @param  bool     $showAll         (option) __DESC__
     * @return string
     */
    public function createAssocFromSql($deprecated, $strName, $strLib, $strSQL = "", $strSQLValues = "", $bRequired = false, $bDeleteOnAdd = true, $bReadOnly = false, $iSize = "5", $iWidth = 200, $bFormOnly = false, $arForeignKey = "", $arSearchFields = "", $aBind = array(), $strOrderColName = '', $showAll = false, $bTrieAlphaValeurSelectionne = true)
    {
        $oConnection = Pelican_Db::getInstance();
        $bSearchEnabled = false;
        if ($arForeignKey || $arSearchFields) {
            $bSearchEnabled = true;
        }
        // available values
        $aDataValues = array();
        if ($strSQL) {
            if ($arSearchFields) {
                if (!is_array($arSearchFields)) {
                    $arSearchFields = array($arSearchFields);
                }
                $sFilter = "";
                while (list(, $val) = each($arSearchFields)) {
                    $sResearch = "";
                    if (strlen($sFilter) != 0) {
                        $sResearch.= " OR ";
                    }
                    $sResearch.= "UPPER(".$val.") like UPPER('%:RECHERCHE:%')";
                    $sFilter.= $sResearch;
                }
                $sFilter = "(".$sFilter.")";
                if (stristr($strSQL, "where ") && !stristr($strSQL, "union ")) {
                    $strSQL = preg_replace("/where /i", "where ".$sFilter." AND ", $strSQL);
                } elseif (stristr($strSQL, "group by ")) {
                    $strSQL = preg_replace("/group by /i", "where ".$sFilter." group by ", $strSQL);
                } elseif (stristr($strSQL, "order by ")) {
                    $strSQL = preg_replace("/order by /i", "where ".$sFilter." order by ", $strSQL);
                }
                if ($showAll) {
                    $this->_getValuesFromSQL($oConnection, str_replace(":RECHERCHE:", "%", $strSQL), $aDataValues);
                }
                $_SESSION["AssocFromSql_Search"][$this->sFormName."_".$strName] = $strSQL;
            } else {
                $this->_getValuesFromSQL($oConnection, $strSQL, $aDataValues);
            }
        }
        // selected values
        if (is_array($strSQLValues)) {
            $aSelectedValues = $strSQLValues;
        } else {
            $aSelectedValues = array();
            if ($strSQLValues) {
                $result = $oConnection->queryTab($strSQLValues, $aBind);
                if (isset($result)) {
                    foreach ($result as $valeur) {
                        $keys = array_keys($valeur);
                        if (in_array(0, $keys) && in_array(1, $keys)) {
                            $keys = array(0, 1);
                        }
                        if ($arForeignKey || $arSearchFields) {
                            $aSelectedValues[$valeur[$keys[0]]] = $valeur[$keys[1]];
                        } else {
                            $aSelectedValues[count($aSelectedValues)] = $valeur[$keys[0]];
                        }
                    }
                }
            }
        }

        return $this->_createAssoc($oConnection, $strName, $strLib, $aDataValues, "", "", "", $aSelectedValues, $bRequired, $bDeleteOnAdd, false, $bSearchEnabled, $bReadOnly, $iSize, $iWidth, "", $bFormOnly, $arForeignKey, false, false, $strOrderColName, $showAll, 0, $bTrieAlphaValeurSelectionne);
    }

    /**
     * Génère une association à partir d'un tableau de valeurs
     *
     * @access public
     * @param  Pelican_Db $oConnection     Objet connection à la base
     * @param  string     $strName         Nom du champ
     * @param  string     $strLib          Libellé du champ
     * @param  mixed      $aDataValues     (option) Tableau de valeurs (id=>lib) : "" par
     *                                     défaut
     * @param  mixed      $aSelectedValues (option) Tableau des valeurs sélectionnées
     *                                     (liste des id) : "" par défaut
     * @param  bool       $bRequired       (option) Champ obligatoire : false par défaut
     * @param  bool       $bDeleteOnAdd    (option) Supprimer les valeurs de la liste source
     *                                     après ajout à la liste destination : true par défaut
     * @param  bool       $bReadOnly       (option) Affiche uniquement la valeur et pas le champ
     *                                     (créé un input hidden) : false par défaut
     * @param  __TYPE__   $iSize           (option) __DESC__
     * @param  string     $iWidth          (option) Largeur du contrôle : 200 par défaut
     * @param  bool       $bFormOnly       (option) Génération du champ uniquement, sans libellé
     *                                     : false par défaut
     * @param  string     $arForeignKey    (option) Remplace la Pelican_Index_Frontoffice_Zone
     *                                     de recherche par une liste déroulante pour filtrer la sélection (nécessite
     *                                     bSearchEnabled à true) : 3 modes : - 1 : nom de table de référence de la clé
     *                                     étrangère (sans le préfixe) => la requête de liste et de recherche seront
     *                                     alors générique - 2 : array(nom de table de référence de la clé
     *                                     étrangère, SQL avec id et lib dans le select pour la liste déroulante) => la
     *                                     requête de recherche sera alors générique - 3 : array(nom de table de
     *                                     référence de la clé étrangère, SQL avec id et lib dans le select pour la
     *                                     liste déroulante, SQL avec id et lib dans le select pour la recherche et
     *                                     :RECHERCHE: dans la clause where)
     * @param  string     $strOrderColName (option) __DESC__
     * @return string
     */
    public function createAssocFromList($oConnection, $strName, $strLib, $aDataValues = "", $aSelectedValues = "", $bRequired = false, $bDeleteOnAdd = true, $bReadOnly = false, $iSize = "5", $iWidth = 200, $bFormOnly = false, $arForeignKey = "", $strOrderColName = '', $limit = 0, $bTrieAlphaValeurSelectionne = true)
    {
        $bSearchEnabled = ($arForeignKey ? true : false);

        return $this->_createAssoc($oConnection, $strName, $strLib, $aDataValues, "", "", "", $aSelectedValues, $bRequired, $bDeleteOnAdd, false, $bSearchEnabled, $bReadOnly, $iSize, $iWidth, "", $bFormOnly, $arForeignKey, false, false, $strOrderColName, false, $limit, $bTrieAlphaValeurSelectionne);
    }

    /**
     * Génère une association
     *
     * @access private
     * @param  Pelican_Db $oConnection                 Objet connection à la base
     * @param  string     $strName                     Nom du champ
     * @param  string     $strLib                      Libellé du champ
     * @param  mixed      $aDataValues                 Tableau de valeurs (id=>lib)
     * @param  string     $strTableName                __DESC__
     * @param  string     $strRefTableName             (option) Nom de la table de jointure où trouver
     *                                                 les valeurs sélectionnées : "" par défaut
     * @param  string     $iID                         (option) Id auquel sont associées les valeurs
     *                                                 sélectionnées : "" par défaut
     * @param  mixed      $aSelectedValues             (option) Tableau des valeurs sélectionnées
     *                                                 (liste des id) : "" par défaut
     * @param  bool       $bRequired                   (option) Champ obligatoire : false par défaut
     * @param  bool       $bDeleteOnAdd                (option) Supprimer les valeurs de la liste source
     *                                                 après ajout à la liste destination : true par défaut
     * @param  bool       $bEnableManagement           (option) Accès à la popup d'ajout dans la
     *                                                 table de référence : true par défaut
     * @param  bool       $bSearchEnabled              (option) La liste n'est pas remplie et un
     *                                                 formulaire de recherche est ajouté : false par défaut
     * @param  bool       $bReadOnly                   (option) Affiche uniquement la valeur et pas le champ
     *                                                 (créé un input hidden) : false par défaut
     * @param  __TYPE__   $iSize                       (option) __DESC__
     * @param  string     $iWidth                      (option) Largeur du contrôle : 200 par défaut
     * @param  string     $strColRefTableName          (option) Nom de la colonne dans la table de
     *                                                 référence correspondant à $iID : "CONTENU_ID" par défaut
     * @param  bool       $bFormOnly                   (option) Génération du champ uniquement, sans libellé
     *                                                 : false par défaut
     * @param  string     $arForeignKey                (option) Remplace la Pelican_Index_Frontoffice_Zone
     *                                                 de recherche par une liste déroulante pour filtrer la sélection (nécessite
     *                                                 bSearchEnabled à true) : 3 modes : - 1 : nom de table de référence de la clé
     *                                                 étrangère (sans le préfixe) => la requête de liste et de recherche seront
     *                                                 alors générique - 2 : array(nom de table de référence de la clé
     *                                                 étrangère, SQL avec id et lib dans le select pour la liste déroulante) => la
     *                                                 requête de recherche sera alors générique - 3 : array(nom de table de
     *                                                 référence de la clé étrangère, SQL avec id et lib dans le select pour la
     *                                                 liste déroulante, SQL avec id et lib dans le select pour la recherche et
     *                                                 :RECHERCHE: dans la clause where)
     * @param  bool       $bSingle                     (option) Génère un nom de champ sans[] : false par
     *                                                 défaut
     * @param  bool       $alternateId                 (option) __DESC__
     * @param  string     $strOrderColName             (option) __DESC__
     * @param  bool       $showAll                     (option) __DESC__
     * @param  bool       $bTrieAlphaValeurSelectionne (option) permet de faire un tri par ordre alphabétique
     * @return string
     */
    public function _createAssoc($oConnection, $strName, $strLib, $aDataValues, $strTableName, $strRefTableName = "", $iID = "", $aSelectedValues = "", $bRequired = false, $bDeleteOnAdd = true, $bEnableManagement = true, $bSearchEnabled = false, $bReadOnly = false, $iSize = "5", $iWidth = 200, $strColRefTableName = "contenu_id", $bFormOnly = false, $arForeignKey = "", $bSingle = false, $alternateId = false, $strOrderColName = '', $showAll = false, $limit = 0, $bTrieAlphaValeurSelectionne = true)
    {
        global $HTTP_SESSION_VARS;
        $strTmp = "";
        if (!$bReadOnly)
            $this->_aIncludes["list"] = true;
        if ($bSearchEnabled) {
            // Charge uniquement les valeurs sélectionnées.
            if ($iID != "") {
                $aSelectedValues = array();
                $strSQL = "select A.".$strTableName.$this->_sTableSuffixeId." as \"id\", A.".$strTableName.$this->_sTableSuffixeLabel." as \"lib\"";
                $strSQL.= " from ".$this->_sTablePrefix.$strTableName." A, ".$strRefTableName." B";
                if ($alternateId) {
                    $child = $strName;
                } else {
                    $child = $strTableName.$this->_sTableSuffixeId;
                }
                $strSQL.= " where A.".$strTableName.$this->_sTableSuffixeId." = B.".$child;
                $strSQL.= " and B.".$strColRefTableName." = ".$iID;
                $strSQL.= " order by ";
                if ($strOrderColName != "") {
                    $strSQL.= $strOrderColName;
                } else {
                    $strSQL.= "Lib";
                }
                $oConnection->Query($strSQL);
                if ($oConnection->rows > 0) {
                    while ($ligne = each($oConnection->data["id"])) {
                        $aSelectedValues[$ligne["value"]] = $oConnection->data["lib"][$ligne["key"]];
                    }
                }
            } else {
                if (!isset($aSelectedValues)) {
                    $aSelectedValues = array();
                }
            }
        } else {
            if ($strTableName != "") {
                $aTmpSelectedValues = array();
                $this->_getValues($oConnection, $strTableName, $strRefTableName, $iID, $aDataValues, $aTmpSelectedValues, $strColRefTableName, $strOrderColName);
            }
            if ($aSelectedValues == "") {
                $aSelectedValues = $aTmpSelectedValues;
            }
            if (!is_array($aSelectedValues)) {
                if ($aSelectedValues != "") {
                    $aSelectedValues = array($aSelectedValues);
                } else {
                    $aSelectedValues = array();
                }
            }
        }
        if ($aSelectedValues == "") {
            $aSelectedValues = array();
        }
        if ($bReadOnly) {
            if (is_array($aSelectedValues)) {
                while ($ligne = each($aSelectedValues)) {
                    $this->countInputName($strName.($bSingle ? "" : "[]"));
                    $strTmp.= Pelican_Html::input(array(type => "hidden", name => $strName.($bSingle ? "" : "[]"), value => str_replace("\"", "&quot;", $ligne["key"])));
                }
            }
        }
        // Génération du couple libellé/champ
        if ($bReadOnly) {
            if ($bSearchEnabled) {
                foreach ($aSelectedValues as $ligne) {
                    $strTmp.= "".$ligne.Pelican_Html::br();
                }
            } else {
                if (is_array($aSelectedValues)) {
                    foreach ($aSelectedValues as $ligne) {
                        $strTmp.= "".$aDataValues[$ligne].Pelican_Html::br();
                    }
                }
            }
            if (!$bFormOnly) {
                $this->countInputName($strName."_last_selected");
                $strTmp.= Pelican_Html::input(array(type => "hidden", name => $strName."_last_selected", id => $strName."_last_selected"));
                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
            }
        } else {
            $strTmp.= "<table class=\"".$this->sStyleVal."\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:".(2 * $iWidth + 30)."px;\" summary=\"Associative\">";
            if ($this->_bUseAssocLabel) {
                $strTmp.= Pelican_Html::tr(
                        Pelican_Html::td(array("class" => $this->sStyleVal), Pelican_Html::i(t('FORM_MSG_LIST_SELECTED')))
                        .($strOrderColName ? Pelican_Html::td(array("class" => $this->sStyleVal), Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp()) : "")
                        .Pelican_Html::td(array("class" => $this->sStyleVal), Pelican_Html::nbsp())
                        .Pelican_Html::td(array("class" => $this->sStyleVal), Pelican_Html::i(t('FORM_MSG_LIST_AVAILABLE')))
                    )."\n";
            }
            $strTmp.= "<tr>";
            // Valeurs choisies
            $this->countInputName($strName);
            $strTmp.= "<td class=\"".$this->sStyleVal."\">";
            $aOption = array();
            if ($aSelectedValues) {
                if ($bSearchEnabled) {
                    while ($ligne = each($aSelectedValues)) {
                        $aOption[] = Pelican_Html::option(array(value => $ligne["key"]), $ligne["value"]);
                    }
                } else {
                    if (is_array($aSelectedValues)) {
                        while ($ligne = each($aSelectedValues)) {
                            if ($aDataValues[$ligne["value"]]) {
                                $aOption[] = Pelican_Html::option(array(value => $ligne["value"]), $aDataValues[$ligne["value"]]);
                            }
                        }
                    }
                }
            }
            $strTmp.= Pelican_Html::select(array(id => $strName, name => $strName.($bSingle ? "" : "[]"), size => $iSize, multiple => "multiple", ondblclick => "assocDel(this".($bDeleteOnAdd ? ", true" : "").")", style => "width:".$iWidth."px;"), implode("", $aOption))."</td>";
            if ($strOrderColName != "") {
                $strTmp.= "<td class=\"".$this->strStyleVal."\">";
                $strTmp.= "<img src=\"".$this->_sLibPath.$this->_sLibForm."/images/top.gif\" width=\"13\" height=\"15\" ";
                $strTmp.= "onClick=\"MoveTop(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']);\"\">".Pelican_Html::br();
                $strTmp.= "<img src=\"".$this->_sLibPath.$this->_sLibForm."/images/up.gif\" width=\"13\" height=\"15\" ";
                $strTmp.= "onClick=\"MoveUp(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']);\"\">".Pelican_Html::br();
                $strTmp.= "<img src=\"".$this->_sLibPath.$this->_sLibForm."/images/down.gif\" width=\"13\" height=\"15\" ";
                $strTmp.= "onClick=\"MoveDown(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']);\"\">".Pelican_Html::br();
                $strTmp.= "<img src=\"".$this->_sLibPath.$this->_sLibForm."/images/bottom.gif\" width=\"13\" height=\"15\" ";
                $strTmp.= " onClick=\"MoveBottom(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']);\"\">";
                $strTmp.= "</td>";
                $this->_aIncludes["ordered_list"] = true;
            }
            $strTmp.= "<td valign=\"middle\" style=\"width:30px;\" align=\"center\">";
            $strTmp.= Pelican_Html::nbsp()."<a href=\"javascript://\" onclick=\"assocAdd".($bSingle ? "Single" : "")."(document.".$this->sFormName.".src".$strName;
            if ($bDeleteOnAdd) {
                $strTmp.= ", true";
            } else {
                $strTmp.= ", false";
            }
            if ($strOrderColName != "") {
                $strTmp.= ", true";
            } else {
                $strTmp.= ", false";
            }
            if ($limit) {
                $strTmp.= ", ".$limit;
            } else {
                $strTmp.= ", 0";
            }
            if ($bTrieAlphaValeurSelectionne) {
                $strTmp.= ", true";
            } else {
                $strTmp.= ", false";
            }
            $strTmp.= ");\"><img src=\"".$this->_sLibPath.$this->_sLibForm."/images/left.gif\" border=\"0\" width=\"7\" height=\"12\" /></a>".Pelican_Html::nbsp();
            $strTmp.= Pelican_Html::br();
            $strTmp.= Pelican_Html::nbsp()."<a href=\"javascript://\" onclick=\"assocDel(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']";
            if ($bDeleteOnAdd) {
                $strTmp.= ", true";
            } else {
                $strTmp.= ", false";
            }
            if ($strOrderColName != "") {
                $strTmp.= ", true";
            }
            $strTmp.= ");\"><img src=\"".$this->_sLibPath.$this->_sLibForm."/images/right.gif\" border=\"0\" width=\"7\" height=\"12\" /></a>".Pelican_Html::nbsp();
            $strTmp.= "</td>";
            // Valeurs disponibles
            $strTmp.= "<td class=\"".$this->sStyleVal."\">";
            // Recherche activée (par champ input ou par combo ($arForeignKey doit être renseigné)
            if ($bSearchEnabled) {
                $this->_aIncludes["popup"] = true;
                // cas de la recherche par combo
                if ($strTableName || $arForeignKey) {
                    if ($arForeignKey) {
                        // Si c'est un tableau, on défini le champ de recherche, la requête de la combo et la requête de recherche (sans clause where)
                        if (is_array($arForeignKey)) {
                            $champForeign = $arForeignKey[0];
                            // Si le second paramètre du tableau n'a pas été initialisé, on le défini avec une expressino générique (à partir du nom de la table)
                            if (!$arForeignKey[1]) {
                                $sqlForeign = "select ".$champForeign.$this->_sTableSuffixeId." \"id\",".$champForeign.$this->_sTableSuffixeLabel." \"lib\" from ".$this->_sTablePrefix.$champForeign." order by lib";
                            } else {
                                $sqlForeign = $arForeignKey[1];
                            }
                            $sqlSearch = $arForeignKey[2];
                        } else {
                            // sinon on prend juste le champ pour initialiser la procédure
                            $champForeign = $arForeignKey;
                            $sqlForeign = "select ".$champForeign.$this->_sTableSuffixeId." as \"id\",".$champForeign.$this->_sTableSuffixeLabel." \"lib\" from ".$this->_sTablePrefix.$champForeign." order by lib";
                        }
                        // Si la requête de recherche n'a pas été initialisée, on la définit de façon générique
                        if (!$sqlSearch) {
                            $sqlSearch = "select ".$strTableName.$this->_sTableSuffixeId." \"id\", ".$strTableName.$this->_sTableSuffixeLabel." \"lib\" from ".$this->_sTablePrefix.$strTableName;
                            $sqlSearch.= " WHERE ".$champForeign.$this->_sTableSuffixeId." = ':RECHERCHE:'";
                            $sqlSearch.= " order by lib";
                        }
                        // cas de la recherche par input
                    } else {
                        // Définition de la requête de recherche de façon générique pour la recherche par input
                        $sqlSearch = "select ".$strTableName.$this->_sTableSuffixeId." \"id\", ".$strTableName.$this->_sTableSuffixeLabel." \"lib\" from ".$this->_sTablePrefix.$strTableName;
                        $sqlSearch.= " WHERE ".$strTableName.$this->_sTableSuffixeLabel." LIKE ('%:RECHERCHE:%')";
                        $sqlSearch.= " order by lib";
                    }
                    $action = "searchIndexation('".$this->_sLibPath.$this->_sLibForm."/', 'src".$strName."', '".$strTableName."', eval('this.form.iSearchVal".$strName.".value'),'".base64_encode($sqlSearch)."',".($showAll ? 1 : 0).");";
                } else {
                    $action = "searchIndexation('".$this->_sLibPath.$this->_sLibForm."/', 'src".$strName."', '', eval('this.form.iSearchVal".$strName.".value'),'".base64_encode($this->sFormName."_".$strName)."',".($showAll ? 1 : 0).");";
                    //      $action = "\"searchIndexation('".$this->_sLibPath.$this->_sLibForm."/', 'src".$strName."', '".$strTableName."', eval('this.form.iSearchVal".$strName.".value'),'".base64_encode($sqlSearch)."');\"";
                }
                if ($arForeignKey) {
                    if (!$bReadOnly) {
                        $this->countInputName("iSearchVal".$strName);
                        $aOption = array();
                        $result_Foreign = $oConnection->queryTab($sqlForeign);
                        $aOption[] = Pelican_Html::option(array(value => ""), t('FORM_SELECT_CHOOSE'));
                        foreach ($result_Foreign as $ligne) {
                            $keys = array_keys($ligne);
                            if (in_array(0, $keys) && in_array(1, $keys)) {
                                $keys = array(0, 1);
                            }
                            $aOption[] = Pelican_Html::option(array(value => $ligne[$keys[0]]), $ligne[$keys[1]]);
                        }
                        $strTmp.= Pelican_Html::select(array(name => "iSearchVal".$strName, id => "iSearchVal".$strName, size => "1", style => "width:".$iWidth."px;", onchange => $action), implode("", $aOption))."\n";
                    }
                } else {
                    $this->countInputName("iSearchVal".$strName);
                    $strTmp.= Pelican_Html::input(array(type => "text", name => "iSearchVal".$strName, size => "14", onkeydown => "submitIndexation('".$this->_sLibPath.$this->_sLibForm."/', '".($strTableName ? $strTableName."','".base64_encode($sqlSearch) : "','".base64_encode($this->sFormName."_".$strName))."')"));
                    $this->countInputName("bSearch".$strName);
                    $strTmp.= "<input type=\"button\" class=\"button\" name=\"bSearch".$strName."\" value=\"".t('FORM_BUTTON_SEARCH')."\" onclick=\"".$action."\" />".Pelican_Html::br();
                }
            }
            $this->countInputName("src".$strName);
            if ($bSearchEnabled) {
                $size = $iSize - 1;
            } else {
                $size = $iSize;
            }
            $aOption = array();
            if (!$bSearchEnabled || ($bSearchEnabled && $showAll && $aDataValues)) {
                //reset($aDataValues);
                if (is_array($aDataValues)) {
                    reset($aDataValues);
                    while ($ligne = each($aDataValues)) {
                        if (!$bDeleteOnAdd || !in_array($ligne["key"], $aSelectedValues)) {
                            $aOption[] = Pelican_Html::option(array(value => ((substr($ligne["key"], 0, 7) == "delete_" ? "" : $ligne["key"]))), $ligne["value"]);
                        }
                    }
                }
            }

            $strTmp.= Pelican_Html::select(array(id => "src".$strName, name => "src".$strName, size => $size, multiple => "multiple", ondblclick => "assocAdd".($bSingle ? "Single" : "")."(this, ".($bDeleteOnAdd ? "true" : "false").($strOrderColName ? ", true" : ", false,").($limit ? ", ".$limit : ", 0").($bTrieAlphaValeurSelectionne ? ", true" : ", false").")", style => "width:".$iWidth."px;"), implode("", $aOption));
            // Lien vers popup de gestion de la table de référence
            if ($bEnableManagement) {
                $this->_aIncludes["popup"] = true;
                $strTmp.= "<td class=\"".$this->sStyleVal."\">";
                $strTmp.= "<a href=\"javascript://\" onclick=\"addRef('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."','".$strName."', '".$strTableName."', ";
                if ($bDeleteOnAdd)
                    $strTmp.= "1";
                else
                    $strTmp.= "0";
                $strTmp.= ");\">".t('FORM_BUTTON_ADD_VALUE')."</a>";
                $strTmp.= "</td>";
            }
            $strTmp.= "</tr>\n</table>\n";
            if (!$bFormOnly) {
                $this->countInputName($strName."_last_selected");
                $strTmp.= "<input type=\"hidden\"  name=\"".$strName."_last_selected\" id=\"".$strName."_last_selected\" />";
                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
            }
            // Génération de la fonction js de vérification.
            if ($bRequired) {
                $this->_sJS.= "var numbersString = new Array();\n";
                $this->_sJS.= "o = obj.elements[\"".$strName.($bSingle ? "" : "[]")."\"];\n";
                $this->_sJS.= "if ( o.length == 0 ) {\n";
                $this->_sJS.= "var isRequired =true;\n";
                $this->_sJS.= "$('#'+o.id+'').addClass('alert-blank');\n";
                $this->_sJS.= "$('#'+o.id+'').parents('table:first').parent().parent().addClass('alert-field-blank');\n";
                $this->_sJS.= "$('#'+o.id+'').parents('table:first').parent().parents('table:first').parent().addClass('alert-blank');\n";
                $this->_sJS.= "$('#'+o.id+'').parents('table:first').parents('table:first').parent().parent().prev().find('td:first').addClass('alert-blank');\n";
                $this->_sJS.= "$('#FIELD_BLANKS').val(1);\n";
                $this->_sJS.= "fwFocus(o);\n";

                $this->_sJS.= " var eMultiple = $('#'+o.id+'').parents('table:first').parents('table:first').parent().parent().prev();\n";
                $this->_sJS.= "if(eMultiple.find('td:not(:empty):first').find('img').attr('src') == '/library/public/images/toggle_zone_close.gif'){;\n";
                $this->_sJS.= "eMultiple.trigger('click');\n";
                $this->_sJS.= "};\n";

                $this->_sJS.= "};\n";
                $this->_sJS.= "if ( o.length > 0 ) {;\n";
                $this->_sJS.= "$('#'+o.id+'').removeClass('alert-blank');\n";
                $this->_sJS.= "$('#'+o.id+'').parents('table:first').parent().parent().removeClass('alert-field-blank');\n";
                $this->_sJS.= "$('#'+o.id+'').parents('table:first').parent().parents('table:first').removeClass('alert-blank');\n";
                $this->_sJS.= "$('#'+o.id+'').parents('table:first').parent().parent().prev().find('td:first').removeClass('alert-blank');\n";
                $this->_sJS.= "}\n";
            }

            // si la verif est activer (CheckBox mobile/ web)
            // helper backend getFormAffichage
            if (Pelican::$config['VERIF_JS'] == 1) {
                $this->_sJS.= "}\n";
                Pelican::$config['VERIF_JS'] = 0;
            }

            $this->_sJS.= "selectAll(document.".$this->sFormName.".elements[\"".$strName.($bSingle ? "" : "[]")."\"]);\n";
        }

        return $this->output($strTmp);
    }

    /**
     * Génère une Pelican_Index_Frontoffice_Zone de saisie texte
     *
     * Il est possible de passer un tableau en tant que valeur
     * => les données seront séparées par un retour chariot
     * => il faut ensuite utiliser la fonction splitTextArea pour retrouver un tableau
     * de données à la Soumission du formulaire
     *
     * @access public
     * @param  string $strName     Nom du champ
     * @param  string $strLib      Libellé du champ
     * @param  bool   $bRequired   (option) Champ obligatoire : false par défaut
     * @param  string $strValue    (option) Valeur du champ : "" par défaut
     * @param  string $iMaxLength  (option) Nb de caractères maximum : "" par défaut
     * @param  bool   $bReadOnly   (option) Affiche uniquement la valeur et pas le champ
     *                             (créé un input hidden) : false par défaut
     * @param  string $iRows       (option) Nombre de lignes : 5 par défaut
     * @param  string $iCols       (option) Nombre de colonnes : 30 par défaut
     * @param  bool   $bFormOnly   (option) Génération du champ uniquement, sans libellé
     *                             : false par défaut
     * @param  string $wrap        (option) Paramètre wrap du textarea
     * @param  bool   $bcountchars (option) Affiche le comptage des caractères tapés
     * @param  string $strEvent    (option) __DESC__
     * @param  string $sInfoBull   (option) champs permettant d'afficher une info-bulle
     * @return string
     */
    public function createTextArea($strName, $strLib, $bRequired = false, $strValue = "", $iMaxLength = "", $bReadOnly = false, $iRows = 5, $iCols = 30, $bFormOnly = false, $wrap = "", $bcountchars = true, $strEvent = "", $sInfoBull = "")
    {
        // Génération du couple libellé/champ
        $strTmp = "";
        if (is_array($strValue)) {
            $strValue = implode("\r\n", $strValue);
        }
        if ($sInfoBull) {
            $strLib = ('<span title="'.$sInfoBull.'" style="cursor:help;">'.$strLib.'</span>');
        }
        if ($bReadOnly) {
            $strTmp.= nl2br(Pelican_Text::htmlentities($strValue));
            if (!$bFormOnly) {
                $strTmp = Pelican_Html_Form::get($strLib, $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
            }
            $strTmp.= $this->createHidden($strName, str_replace("\"", "&quot;", $strValue));
        } else {
            if (!$this->_sDefaultFocus)
                $this->_sDefaultFocus = $strName;
            if ($wrap) {
                $txtWrap = ' wrap="'.$wrap.'"';
            } else {
                $txtWrap = '';
            }
            $this->countInputName($strName);
            $strTmp.= "<textarea name=\"".$strName."\" rows=\"".$iRows."\" cols=\"".$iCols."\"".$txtWrap;
            if ($strEvent) {
                $strTmp.= " ".$strEvent;
            }
            if ($this->bVirtualKeyboard) {
                $strTmp.= ' onfocus="activeInput = this;PopupVirtualKeyboard.attachInput(this);"';
                $this->_InputVK[] = $strName;
            }
            if ($bcountchars) {
                $strTmp.= ' onkeyup="countchars(this,'.($iMaxLength ? $iMaxLength : 0).');"';
            }
            $strTmp.= '>'.$strValue."</textarea>";
            if ($bcountchars) {
                $this->_aIncludes["text"] = true;
                $strTmp.= '<div class="countchars" style="width:'.($iCols * 6).'px;" id="cnt_'.$strName.'_div">'.strlen($strValue);
                if ($iMaxLength) {
                    $strTmp.= '/'.$iMaxLength.' '.t('CHARACTER').'s</div>';
                } else {
                    $strTmp.= ' '.t('CHARACTER').(strlen($strValue) > 1 ? 's' : '').'</div>';
                }
            }
            if (!$bFormOnly) {
                $strTmp = Pelican_Html_Form::get($strLib, $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
            }
            // Génération de la fonction js de vérification.
            if ($bRequired) {
                $this->_aIncludes["text"] = true;
                $this->_sJS.= "if ( isBlank(obj.".$strName.".value) ) {\n";


                $this->_sJS.= "$('textarea[name='+obj.".$strName.".name+']').addClass('alert-blank');\n";
                $this->_sJS.= "$('textarea[name='+obj.".$strName.".name+']').parent().parent().addClass('alert-field-blank');\n";
                $this->_sJS.= "$('textarea[name='+obj.".$strName.".name+']').parents('table:first').parent().addClass('alert-blank');\n";
                $this->_sJS.= "$('textarea[name='+obj.".$strName.".name+']').parents('table:first').parent().parent().prev().find('td:first').addClass('alert-blank');\n";
                $this->_sJS.= "$('#FIELD_BLANKS').val(1);\n";

                $this->_sJS.= "$('textarea[name='+obj.".$strName.".name+']').keyup(function() {\n";
                $this->_sJS.= "$('textarea[name='+obj.".$strName.".name+']').removeClass('alert-blank');\n";
                $this->_sJS.= "$('textarea[name='+obj.".$strName.".name+']').parent().parent().removeClass('alert-field-blank');\n";
                $this->_sJS.= "$('textarea[name='+obj.".$strName.".name+']').parents('table:first').parent().removeClass('alert-blank');\n";
                $this->_sJS.= "$('textarea[name='+obj.".$strName.".name+']').parents('table:first').parent().parent().prev().find('td:first').removeClass('alert-blank');\n";
                $this->_sJS.= "$('#FIELD_BLANKS').val(0);\n";
                $this->_sJS.= "return false;\n";
                $this->_sJS.= "});\n";
                $this->_sJS.= "fwFocus(obj.".$strName.");\n";
                $this->_sJS.= "}\n";
            }
            if ($iMaxLength != "") {
                $this->_aIncludes["text"] = true;
                $this->_sJS.= "if ( obj.".$strName.".value.length > ".$iMaxLength." ) {\n";
                $this->_sJS.= "alert(\"".t('FORM_MSG_LIMIT_1')." ".$iMaxLength." ".t('FORM_MSG_LIMIT_2')." "."\\"."\"".strip_tags(str_replace("\"", ""."\\"."\"", $strLib)).""."\\"."\".\");\n";
                $this->_sJS.= "fwFocus(obj.".$strName.");\n";
                $this->_sJS.= "return false;\n";
                $this->_sJS.= "}\n";
            }
        }

        return $this->output($strTmp);
    }

    /**
     * Génère un contrôle de type liste
     *
     * @access private
     * @param  string $strName        Nom du champ
     * @param  string $strLib         Libellé du champ
     * @param  mixed  $aDataValues    Tableau de valeurs (id=>lib)
     * @param  mixed  $aCheckedValues Liste des valeurs cochées (liste des id)
     * @param  bool   $bRequired      Champ obligatoire
     * @param  bool   $bReadOnly      Affiche uniquement la valeur et pas le champ (créé un
     *                                input hidden)
     * @param  string $cOrientation   Orientation h=horizontal, v=vertical
     * @param  string $strType        __DESC__
     * @param  bool   $bFormOnly      (option) Génération du champ uniquement, sans libellé
     *                                : false par défaut
     * @param  string $strEvent       (option) Handler d'événements sur le champ : "" par
     *                                défaut
     * @param  string $sInfoBull      (option) champs permettant d'afficher une info-bulle
     * @return string
     */
    public function _createBox($strName, $strLib, $aDataValues, $aCheckedValues, $bRequired, $bReadOnly, $cOrientation, $strType, $bFormOnly = false, $strEvent = "", $sInfoBull = "")
    {
        $strTmp = "";
        if ($sInfoBull) {
            $strLib = "<span title='".$sInfoBull."' style='cursor:help;'>".$strLib."</span>";
        }
        if (!is_array($aCheckedValues)) {
            $aCheckedValues = array(1 => $aCheckedValues);
        }
        if (!is_array($aDataValues)) {
            $aDataValues = array(1 => $aDataValues);
        }
        $strFieldName = $strName;
        if (($strType == "checkbox") && (count($aDataValues) > 1)) {
            $strFieldName.= "[]";
        }
        // Génération du couple libellé/champ
        if ($bReadOnly) {
            if ($aCheckedValues == "") {
                $strTmp.= $this->createHidden($strFieldName, "0");
            } else {
                while ($ligne = each($aCheckedValues)) {
                    $strTmp.= $this->createHidden($strFieldName, str_replace("\"", "&quot;", $ligne["value"]));
                }
            }
        }
        if (is_array($aDataValues)) {
            if ($bReadOnly) {
                while ($ligne = each($aDataValues)) {
                    if (in_array($ligne["key"], $aCheckedValues)) {
                        if ($ligne["value"] == "") {
                            $strTmp.= " ".t('FORM_MSG_YES')." ";
                        } else {
                            $strTmp.= $ligne["value"]." ";
                        }
                        if ($cOrientation == "v") {
                            $strTmp.= Pelican_Html::br();
                        }
                    }
                }
            } else {
                $this->countInputName($strFieldName);
                while ($ligne = each($aDataValues)) {
                    $params = array();
                    $params[type] = $strType;
                    $params[name] = $strFieldName;
                    $params[value] = str_replace("\"", "&quot;", $ligne["key"]);
                    if (in_array($ligne["key"], $aCheckedValues)) {
                        $params[checked] = "checked";
                    }
                    $strTmp.= Pelican_Html_Form::addInputEvent(Pelican_Html::input($params), $strEvent);
                    $strTmp.= Pelican_Html::nbsp().$ligne["value"];
                    if ($cOrientation == "v") {
                        $strTmp.= Pelican_Html::br();
                    }
                }
            }
        }
        if (!$bFormOnly) {
            $strTmp = Pelican_Html_Form::get($strLib, $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
        }
        // Génération de la fonction js de vérification.
        if (!$bReadOnly && is_array($aDataValues) && $bRequired) {
            if (($strType == "checkbox") && (count($aDataValues) > 1)) {
                $this->_sJS.= "o = obj.elements[\"".$strName."[]\"];\n";
            } else {
                $this->_sJS.= "o = obj.".$strName.";\n";
            }

            $this->_sJS.="if(typeof o != 'undefined'){ \n";

            if (count($aDataValues) > 1) {

                $this->_sJS.= "bChecked = false;\n";
                $this->_sJS.= "for (i=0; i < o.length; i++)\n";

                $this->_sJS.= "if ( o[i].checked )\n";

                $this->_sJS.= "bChecked = true;\n";
                $this->_sJS.= "if (!bChecked ) {\n";
                $this->_sJS.= "for (i=0; i < o.length; i++){\n";
                $this->_sJS.= "$('input[name='+o[i].name+']').parent().parent().addClass('alert-field-blank');\n";
                $this->_sJS.= "$('input[name='+o[i].name+']').parents('table:first').parent().parent().prev().find('td:first').addClass('alert-blank');\n";
                $this->_sJS.= " var eRadio= $('input[name='+o[i].name+']').parents('table:first').parent().parent().prev();\n";
                $this->_sJS.= "if(eRadio.find('td:not(:empty):first').find('img').attr('src') == '/library/public/images/toggle_zone_close.gif'){;\n";
                $this->_sJS.= "eRadio.trigger('click');\n";
                $this->_sJS.= "};\n";
                $this->_sJS.= "$('input:radio[name='+o[i].name+']').click(function() {\n";
                $this->_sJS.= "$(this).parent().parent().removeClass('alert-field-blank');\n";
                $this->_sJS.= "$(this).parents('table:first').parent().parent().prev().find('td:first').removeClass('alert-blank');\n";
                $this->_sJS.= "});\n";
                $this->_sJS.= "$('#FIELD_BLANKS').val(1);\n";
                $this->_sJS.= "}\n";
            } else {

                $this->_sJS.= "if (!o.checked ) {\n";
                $this->_sJS.= "$('input[name='+o.name+']').parent().parent().addClass('alert-field-blank');\n";
                $this->_sJS.= "$('input[name='+o.name+']').parents('table:first').parent().parent().prev().find('td:first').addClass('alert-blank');\n";
                $this->_sJS.= "$('#FIELD_BLANKS').val(1);\n";
                $this->_sJS.= " var eCheck = $('input[name='+o.name+']').parents('table:first').parent().parent().prev();\n";
                $this->_sJS.= "if(eCheck.find('td:not(:empty):first').find('img').attr('src') == '/library/public/images/toggle_zone_close.gif'){;\n";
                $this->_sJS.= "eCheck.trigger('click');\n";
                $this->_sJS.= "};\n";
                $this->_sJS.= "if(o.type == 'checkbox'){\n";
                $this->_sJS.= "$('input:checkbox[name='+o.name+']').click(function() {\n";
                $this->_sJS.= "if($(this).is(':checked')){\n";
                $this->_sJS.= "$(this).parent().parent().removeClass('alert-field-blank');\n";
                $this->_sJS.= "$(this).parents('table:first').parent().parent().prev().find('td:first').removeClass('alert-blank');\n";
                $this->_sJS.= " }else{\n";
                $this->_sJS.= "$(this).parent().parent().addClass('alert-field-blank');\n";
                $this->_sJS.= "}\n";
                $this->_sJS.= "});\n";
                $this->_sJS.= "}\n";
            }

            $this->_sJS.= "}}\n";
        }

        return $this->output($strTmp);
    }

    /**
     * Génère un éditeur DHTML
     *
     * @access public
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param bool $bRequired (option) Champ obligatoire : false par défaut
     * @param string $strValue (option) Valeur du champ : "" par défaut
     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
     * (créé un input hidden) : false par défaut
     * @param bool $bPopup (option) __DESC__
     * @param string $strSubFolder (option) Répertoire racine de la médiathèque
     * appelée du miniword
     * @param int $Width (option) __DESC__
     * @param int $Height (option) __DESC__
     * @param mixed $limitedConf (option) Identifiant du filtre à appliquer à la
     * confiration de l'éditeur (dans /application/configs/editor.ini.php, $_LIMITED)
     * @return string
     */
    public function createEditor($strName, $strLib, $bRequired = false, $strValue = "", $bReadOnly = false, $bPopup = true, $strSubFolder = "", $Width = "", $Height = "", $limitedConf = "")
    {
        global $_EDITOR;
        $this->tinymce = true;
        $strTmp.= "<tr>";
        $strTmp.= "      <td class=\"".$this->sStyleLib."\" valign=\"top\">".Pelican_Text::htmlentities($strLib);
        if ($bRequired && !$bReadOnly)
            $strTmp.= $this->_sRequiredIndicator;
        if (!$bReadOnly) {
            $strTmp .= "&nbsp;<a href=\"javascript://\" onclick=\"cleanEditor('".$this->sFormName.".".$strName."');\" style=\"text-decoration:none\"><img src=\"".$this->_sLibPath.$this->_sLibForm."/images/clean.gif\" width=\"23\" height=\"22\" border=\"0\" align=\"middle\" alt=\"Vider le contenu\" /></a>";
        }
        $strTmp.= "</td>";
        $strTmp.= $this->getDisposition();
        $strTmp.= "      <td class=\"".$this->sStyleVal."\">";
        if (!$Width) {
            $Width = $this->_sEditorWidth;
        }
        if (!$Height) {
            $Height = $this->_sEditorHeight;
        }
        if ($bPopup || $bReadOnly) {
            if (!$bReadOnly) {
                $this->_aIncludes['virtualkeyboard'] = true;
                $this->_aIncludes["popup"] = true;
                $strTmp.= "<a href=\"javascript://\" onclick=\"PopupVirtualKeyboard.hide();popupEditor2('".$strName."','".$strSubFolder."', '".$limitedConf."');\" style=\"text-decoration:none\"><img src=\"".$this->_sLibPath.$this->_sLibForm."/images/iframe.gif\" width=\"18\" height=\"18\" border=\"0\" align=\"middle\" alt=\"\" />&nbsp;".t('FORM_BUTTON_EDITOR')."</a><br />";
            }
            $strTmp.= $this->createHidden($strName, str_replace("\"", "&quot;", $strValue), true);
            $this->countInputName("iframeText".$strName);
            $strTmp.= "<iframe src=\"/library/blank.html\" id=\"iframeText".$strName."\" name=\"iframeText".$strName."\" width=\"".$Width."\" height=\"".$Height."\" style=\"border: 1px solid #ccc;\" frameborder=\"0\"></iframe>";
            $strTmp.= "<script type=\"text/javascript\">\n";
            $strTmp.= "  var MEDIA_HTTP=\"".str_replace("/", "\/", $this->_sUploadHttpPath."/")."\";\n";
            $strTmp.= "  var MEDIA_VAR=\"".str_replace("/", "\/", $this->_sUploadVar."/")."\";\n";
            $strTmp.= "  var tempM=new RegExp(MEDIA_VAR , \"gi\");\n";
            $strTmp.= "var body = \"<html><head>";
            $strTmp.= $meta;
            if ($this->_sEditorCss) {
                $strTmp.= "<link rel='stylesheet' type='text/css' href='".$this->_sEditorCss."' />";
            }
            $strTmp.= "</head><body>\" + document.getElementById('".$strName."').value.replace(tempM,MEDIA_HTTP) + \"</body></html>\";\n";
            $strTmp.= "      iframeText".$strName.".document.open();\n";
            $strTmp.= "      iframeText".$strName.".document.write(body);\n";
            $strTmp.= "      iframeText".$strName.".document.close();\n";
            $strTmp.= "\n</script>\n";
        } else {
            //nada
            $this->aEditor[] = $strName;
            $this->countInputName($strName);
            $strTmp.= Pelican_Html::textarea(array(id => $strName."TagStripped", name => $strName."TagStripped", rows => "20", cols => "80", "style" => "display: none;"), Pelican_Text::htmlentities(preg_replace('@<script[^>]*?>.*?</script>@si', '', $strValue)));
            $strTmp.= Pelican_Html::textarea(array(id => $strName, name => $strName, rows => "20", cols => "80", "class" => "mceEditor", "mce_editable" => "true"), Pelican_Text::htmlentities($strValue));
            $strTmp.= "<iframe src=\"/library/blank.html\" id=\"iframeText".$strName."\" name=\"iframeText".$strName."\" width=\"".$Width."\" height=\"".$Height."\" style=\"border: 1px solid #ccc;\" frameborder=\"0\"></iframe>";
            $strTmp.= "<script type=\"text/javascript\">\n";
            $strTmp.= "  var MEDIA_HTTP=\"".str_replace("/", "\/", $this->_sUploadHttpPath."/")."\";\n";
            $strTmp.= "  var MEDIA_VAR=\"".str_replace("/", "\/", $this->_sUploadVar."/")."\";\n";
            $strTmp.= "  var tempM=new RegExp(MEDIA_VAR , \"gi\");\n";
            $strTmp.= "var body = \"<html><head>";
            $strTmp.= $meta;
            if ($this->_sEditorCss) {
                $strTmp.= "<link rel='stylesheet' type='text/css' href='".$this->_sEditorCss."' />";
            }
            //			$strTmp .= "</head><body>\" + document.getElementById('".$strName."TagStripped').value.replace(temp,MEDIA_HTTP) + \"</body></html>\";\n";
            $strTmp.= "</head><body>\" + document.getElementById('".$strName."').value.replace(tempM,MEDIA_HTTP) + \"</body></html>\";\n";
            $strTmp.= "      iframeText".$strName.".document.open();\n";
            $strTmp.= "      iframeText".$strName.".document.write(body);\n";
            $strTmp.= "      iframeText".$strName.".document.close();\n";
            $strTmp.= "\n</script>\n";
            if (!$bReadOnly) {
                $this->_aIncludes["text"] = true;
            }
        }

        if ($bRequired) {

            //$this->_sJS.= "$('#iframeText" . $strName . "').parents('table:first').parent().parent().prev().find('td:first').removeClass('alert-blank');\n";

            $this->_sJS.= "if (isBlank(obj.".$strName.".value)) {\n";
            $this->_sJS.= "$('#iframeText".$strName."').parent().addClass('alert-blank');\n";
            $this->_sJS.= "$('#iframeText".$strName."').parent().parent().addClass('alert-field-blank');\n";
            //$this->_sJS.= "$('#iframeText" . $strName . "').parents('table:first').parent().addClass('alert-blank');\n"; 
            $this->_sJS.= "$('#iframeText".$strName."').parents('table:first').parent().parent().prev().find('td:first').addClass('alert-blank');\n";
            $this->_sJS.= "$('#FIELD_BLANKS').val(1);\n";
            $this->_sJS.= "}\n";



            $this->_sJS.= "if (isBlank(obj.".$strName.".value)) {\n";
            $this->_sJS.= " var eIframe = $('#iframeText".$strName."').parents('table:first').parent().parent().prev();\n";
            $this->_sJS.= "if(eIframe.find('td:not(:empty):first').find('img').attr('src') == '/library/public/images/toggle_zone_close.gif'){;\n";
            $this->_sJS.= "eIframe.trigger('click');\n";
            $this->_sJS.= "};\n";
            $this->_sJS.= "}\n";


            $this->_sJS.= "$('#iframeText".$strName."').parent().find('a:not(:empty):first').click(function() {\n";
            $this->_sJS.= "$('#iframeText".$strName."').parent().removeClass('alert-blank');\n";
            $this->_sJS.= "$('#iframeText".$strName."').parent().parent().removeClass('alert-field-blank');\n";
            $this->_sJS.= "$('#iframeText".$strName."').parents('table:first').parent().parent().prev().find('td:first').removeClass('alert-blank');\n";
            $this->_sJS.= "});\n";
        }

        $strTmp.= "</td></tr>\n";
        return $this->output($strTmp);
    }

    /**
     * Génère des checkbox à partir d'une série de valeurs
     *
     * @access public
     * @param  string $strName        Nom du champ
     * @param  string $strLib         Libellé du champ
     * @param  mixed  $aDataValues    (option) Tableau de valeurs (id=>lib) : "" par
     *                                défaut
     * @param  mixed  $aCheckedValues (option) Liste des valeurs cochées (liste des id)
     *                                : "" par défaut
     * @param  bool   $bRequired      (option) Champ obligatoire : false par défaut
     * @param  bool   $bReadOnly      (option) Affiche uniquement la valeur et pas le champ
     *                                (créé un input hidden) : false par défaut
     * @param  string $cOrientation   (option) Orientation h=horizontal, v=vertical : "h"
     *                                par défaut
     * @param  bool   $bFormOnly      (option) __DESC__
     * @param  string $strEvent       (option) __DESC__
     * @param  string $sInfoBull      (option) champs permettant d'afficher une info-bulle
     * @return string
     */
    public function createCheckBoxFromList($strName, $strLib, $aDataValues = "", $aCheckedValues = "", $bRequired = false, $bReadOnly = false, $cOrientation = "h", $bFormOnly = false, $strEvent = "", $sInfoBull = "")
    {
        return $this->_createBox($strName, $strLib, $aDataValues, $aCheckedValues, $bRequired, $bReadOnly, $cOrientation, "checkbox", $bFormOnly, $strEvent, $sInfoBull);
    }

    /**
     * Génère un contrôle de type combo
     *
     * @access private
     * @param  string   $strName           Nom du champ
     * @param  string   $strLib            Libellé du champ
     * @param  mixed    $aDataValues       Tableau de valeurs (id=>lib)
     * @param  mixed    $aSelectedValues   Tableau des valeurs sélectionnées (liste des
     *                                     id)
     * @param  bool     $bRequired         Champ obligatoire
     * @param  bool     $bReadOnly         Affiche uniquement la valeur et pas le champ (créé un
     *                                     input hidden)
     * @param  __TYPE__ $iSize             __DESC__
     * @param  bool     $bMultiple         Sélection multiple
     * @param  string   $iWidth            Largeur du contrôle
     * @param  bool     $bChoisissez       Affiche le message "->Choisissez" en début de liste
     * @param  bool     $bEnableManagement (option) Accès à la popup d'ajout dans la
     *                                     table de référence : false par défaut
     * @param  bool     $bFormOnly         (option) Génération du champ uniquement, sans libellé
     *                                     : false par défaut
     * @param  string   $strTableName      (option) Nom de la table pour les valeurs sans
     *                                     $this->_sTablePrefix : "" par défaut
     * @param  string   $strEvent          (option) événement et fonction javascript "" par
     *                                     défaut. ex : onChange="javascript:functionAExecuter();"
     * @param  string   $sSearchQueryName  (option) Nom de la variable de session
     *                                     contenant la requête pour filtrer la combo Dans ce cas, une
     *                                     Pelican_Index_Frontoffice_Zone de saisie avec bouton de recherche s'affiche à
     *                                     droite.
     * @param  bool     $bDelManagement    (option) __DESC__
     * @param  bool     $bUpdManagement    (option) __DESC__
     * @param  string   $sInfoBull         (option) champs permettant d'afficher une info-bulle
     * @return string
     */
    public function _createCombo($strName, $strLib, $aDataValues, $aSelectedValues, $bRequired, $bReadOnly, $iSize, $bMultiple, $iWidth, $bChoisissez, $bEnableManagement = false, $bFormOnly = false, $strTableName = "", $strEvent = "", $sSearchQueryName = "", $bDelManagement = false, $bUpdManagement = false, $sInfoBull = "")
    {
        $strTmp = "";
        if ($sInfoBull) {
            $strLib = "<span title='".$sInfoBull."' style='cursor:help;'>".$strLib."</span>";
        }
        if (!is_array($aSelectedValues)) {
            $aSelectedValues = array($aSelectedValues);
        }
        $strFieldName = $strName;
        if ($bMultiple) {
            $strFieldName.= "[]";
        }
        if ($bReadOnly) {
            $this->countInputName($strFieldName);
            while ($ligne = each($aSelectedValues)) {
                $params = array();
                $params[type] = "hidden";
                $params[name] = $strFieldName.($bMultiple ? "[]" : "");
                $params[value] = str_replace("\"", "&quot;", $ligne["value"]);
                $strTmp.= Pelican_Html::input($params);
            }
        }
        // Génération du couple libellé/champ
        if (!$bReadOnly) {
            $this->countInputName($strFieldName);
            $params = array();
            $params[name] = $strFieldName;
            $params[id] = $strFieldName;
            $params[size] = ($iSize ? $iSize : "1");
            if ($bMultiple) {
                $params[multiple] = "multiple";
            }
            if ($iWidth) {
                $params[style] = "width:".$iWidth."px;";
            }
            if ($bChoisissez && !$bMultiple) {
                if ($bChoisissez === true) {
                    $aOptions[] = Pelican_Html::option(array(value => ""), t('FORM_SELECT_CHOOSE'));
                } else {
                    $aOptions[] = Pelican_Html::option(array(value => ""), $bChoisissez);
                }
            }
            if (is_array($aDataValues)) {
                //
                foreach ($aDataValues as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $key2 => $value2) {
                            $selected = "";
                            if (in_array($key2, $aSelectedValues)) {
                                $selected = "selected";
                            }
                            $aOptions2[$key][] = Pelican_Html::option(array(value => $key2, selected => $selected), $value2);
                        }
                    } else {
                        $selected = "";
                        if (in_array($key, $aSelectedValues)) {
                            $selected = "selected";
                        }
                        $aOptions[] = Pelican_Html::option(array(value => $key, selected => $selected), $value);
                    }
                }
                if (isset($aOptions2)) {
                    foreach ($aOptions2 as $group => $options) {
                        $aOptions[] = Pelican_Html::optgroup(array(label => Pelican_Text::htmlentities($group)), implode("", $options));
                    }
                }
            }
            $strTmp.= Pelican_Html_Form::addInputEvent(Pelican_Html::select($params, @implode("", $aOptions)), $strEvent, "select");
            if ($sSearchQueryName) {
                // Elements pour filtre de la combo
                $strTmp.= Pelican_Html::input(array(type => "text", name => "iSearchVal".$strName, size => "14", onkeyDown => "submitIndexation('".$this->_sLibPath.$this->_sLibForm."/', '','".base64_encode($sSearchQueryName)."', true, ".($bChoisissez ? "true" : "false").");"));
                $strTmp.= Pelican_Html::input(array(type => "button", "class" => "button", name => "bSearch".$strName, value => t('FORM_BUTTON_SEARCH'), onclick => "searchIndexation('".$this->_sLibPath.$this->_sLibForm."/', '".$strName."', '', eval('this.form.iSearchVal".$strName.".value'),'".base64_encode($this->sFormName."_".$strName)."',".($showAll ? 1 : 0).");")).Pelican_Html::br();
            }
        } else {
            if (is_array($aDataValues)) {
                foreach ($aDataValues as $key1 => $group) {
                    if (is_array($group)) {
                        foreach ($group as $key => $value) {
                            if (in_array($key, $aSelectedValues)) {
                                $strTmp.= $value.Pelican_Html::br();
                            }
                        }
                    } else {
                        if (in_array($key1, $aSelectedValues)) {
                            $strTmp.= $group.Pelican_Html::br();
                        }
                    }
                }
            }
        }
        // Lien vers popup de gestion de la table de référence
        if ($bEnableManagement && !$bReadOnly) {
            $this->_aIncludes["popup"] = true;
            $this->_aIncludes["list"] = true;
            $strTmp.= " ".Pelican_Html::a(array(href => "javascript://", onclick => "addRef('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."','".$strName."', '".$strTableName."', 1, true, 'add');"), t('FORM_BUTTON_ADD_VALUE'));
            if ($bUpdManagement && !$bReadOnly) {
                $strTmp.= Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp();
                $strTmp.= " ".Pelican_Html::a(array(href => "javascript://", onclick => "addRef('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."','".$strName."', '".$strTableName."', 1, true, 'upd');"), 'Update a value');
            }
            if ($bDelManagement && !$bReadOnly) {
                $strTmp.= Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp();
                $strTmp.= " ".Pelican_Html::a(array(href => "javascript://", onclick => "addRef('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."','".$strName."', '".$strTableName."', 1, true, 'del');"), 'Del a value');
            }
        }
        if (!$bFormOnly) {
            $strTmp = Pelican_Html_Form::get($strLib, $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, ((!$bChoisissez || $bMultiple) && !$bReadOnly ? "top" : ""), "", $this->_sFormDisposition);
        }
        // Génération de la fonction js de vérification.
        if (!$bReadOnly && $bRequired) {
            if ($bMultiple)
                $this->_sJS.= "var o = obj.elements[\"".$strName."[]\"];\n";
            else
                $this->_sJS.= "var o = obj.".$strName.";\n";


            $this->_sJS.= "try {\n";
            $this->_sJS.= "if ( (o.selectedIndex == 0) && (o.options[o.selectedIndex].value == \"\") ) {\n";



            $this->_sJS.= "$('#'+obj.".$strName.".id+'').addClass('alert-blank');\n";
            $this->_sJS.= "$('#'+obj.".$strName.".id+'').parent().parent().addClass('alert-field-blank');\n";
            $this->_sJS.= "$('#'+obj.".$strName.".id+'').parents('table:first').parent().addClass('alert-blank');\n";
            $this->_sJS.= "$('#'+obj.".$strName.".id+'').parents('table:first').parent().parent().prev().find('td:first').addClass('alert-blank');\n";


            $this->_sJS.= "$('#'+obj.".$strName.".id+'').change(function() {\n";
            $this->_sJS.= "$('#'+obj.".$strName.".id+'').removeClass('alert-blank');\n";
            $this->_sJS.= "$('#'+obj.".$strName.".id+'').parent().parent().removeClass('alert-field-blank');\n";
            $this->_sJS.= "$('#'+obj.".$strName.".id+'').parents('table:first').parent().removeClass('alert-blank');\n";
            $this->_sJS.= "$('#'+obj.".$strName.".id+'').parents('table:first').parent().parent().prev().find('td:first').removeClass('alert-blank');\n";
            $this->_sJS.= "$('#FIELD_BLANKS').val(0);\n";
            $this->_sJS.= "return false;\n";

            $this->_sJS.= "});\n";

            $this->_sJS.= "console.log($('#IS_PERSO').val(),'isperso');\n";
            $this->_sJS.= "if($('#IS_PERSO').val()==1){\n";
            $this->_sJS.= "alert(\"".t('FORM_MSG_VALUE_CHOOSE')." "."\\"."\"".strip_tags(str_replace("\"", ""."\\"."\"", $strLib)).""."\\"."\"\");\n";
            $this->_sJS.= "fwFocus(o);\n";
            $this->_sJS.= "$('#FIELD_BLANKS').val(1);\n";
            $this->_sJS.= "return false;\n";
            $this->_sJS.= "}else{\n";
            $this->_sJS.= "$('#FIELD_BLANKS').val(1);\n";
            $this->_sJS.= "}\n";

            $this->_sJS.= "fwFocus(o);\n";

            $this->_sJS.= "}\n";
            $this->_sJS.= "} catch (ex) {console.log(ex);}\n";
        }

        return $this->output($strTmp);
    }

    /**
     * __DESC__
     *
     * @access public
     * @param string $strName __DESC__
     * @param string $strLib __DESC__
     * @param bool $bRequired (option) __DESC__
     * @param string $strValue (option) __DESC__
     * @param string $strLabel (option) __DESC__
     * @param string $strDivContent (option) __DESC__
     * @param bool $bReadOnly (option) __DESC__
     * @param bool $bFormOnly (option) __DESC__
     * @return __TYPE__
     */
    public function createDiv($strName, $strLib, $bRequired = false, $strValue = "", $strLabel = "&nbsp;", $strDivContent = "", $bReadOnly = false, $bFormOnly = false)
    {

        /** Sauvegarde de la valeur de bDirectOutput et désactivation temporaire */
        $directOutput = $this->bDirectOutput;
        $this->bDirectOutput = false;

        /** Création d'un hidden dont le nom correspond au champ updaté en base */
        $strTmp = $this->createHidden($strName, $strValue);
        if (!$bReadOnly) {
            $strTmp.= Pelican_Html::img(array(id => $strName."_IMG", src => $this->_sLibPath.$this->_sLibForm."/images/combo.gif", alt => "", border => "0", onclick => "showInputDiv('".$strName."');", style => "float:left;"));
        }
        $strTmp.= Pelican_Html::span(array(id => $strName."_LABEL", style => "float:left;font-weight:bold;margin-left:10px;"), $strLabel);
        if (!$bReadOnly) {
            $strTmp.= Pelican_Html::div(array(id => $strName."_DIV", "class" => "inputdiv", style => "z-index:150;display:none;"), $strDivContent);
        }
        if (!$bFormOnly) {
            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "", "", $this->_sFormDisposition);
        }
        if (!$bReadOnly && $bRequired) {
            $this->_aIncludes["text"] = true;
            $this->_sJS.= "if ( isBlank(obj.".$strName.".value) ) {\n";
            $this->_sJS.= "$('#FIELD_BLANKS').val(1);\n";
            $this->_sJS.= "$('#PAGE_ID_IMG').parent().parent().find('td:first').addClass('alert-field-blank');\n";
            $this->_sJS.= "$('#PAGE_ID_IMG').click(function() {\n";
            $this->_sJS.= "$('#PAGE_ID_IMG').parent().parent().find('td:first').removeClass('alert-field-blank');\n";
            $this->_sJS.= "});\n";
            //$this->_sJS.= "alert(\"" . t('FORM_MSG_VALUE_REQUIRE') . " " . "\\" . "\"" . strip_tags(str_replace("\"", "" . "\\" . "\"", $strLib)) . "" . "\\" . "\".\");\n";
            //$this->_sJS.= "return false;\n";
            $this->_sJS.= "}\n";
        }
        $this->bDirectOutput = $directOutput;
        return $this->output($strTmp);
    }

    /**
     * Génère une combo à partir d'une requête SQL
     *
     * @access public
     * @param Pelican_Db $oConnection     Objet connection à la base
     * @param string     $strName         Nom du champ
     * @param string     $strLib          Libellé du champ
     * @param mixed      $strSQL          (option) Requête SQL (id,lib)
     * @param string     $aSelectedValues (option) __DESC__
     * @param bool       $bRequired       (option) Champ obligatoire : false par défaut
     * @param bool       $bReadOnly       (option) Affiche uniquement la valeur et pas le champ
     *                                    (créé un input hidden)
     * @param __TYPE__   $iSize           (option) __DESC__
     * @param bool       $bMultiple       (option) Sélection multiple : false par défaut
     * @param string     $iWidth          (option) Largeur du contrôle : "" par défaut
     * @param bool       $bChoisissez     (option) Affiche le message "->Choisissez" en début
     *                                    de liste : true par défaut
     * @param bool       $bFormOnly       (option) Affiche uniquement les éléments du formulaire
     *
     * @param  string   $strEvent       (option) __DESC__
     * @param  array    $arSearchFields (option) Liste des champs sur lesquels effectuer
     *                                  une recherche par like 0 : nom complet du champ id dont la(les) valeur(s)
     *                                  sélectionnée(s) est(sont) dans $aSelectedValues suivants : champ(s) sur
     *                                  le(s)quel(s) doit s'effectuer la recherche Dans ce cas, la combo ne contient que
     *                                  la (les) valeur(s) sélectionnée(s) et une Pelican_Index_Frontoffice_Zone de
     *                                  saisie avec bouton de recherche s'affiche à droite.
     * @param  string   $sInfoBull      (option) champs permettant d'afficher une info-bulle
     * @param  __TYPE__ $aBind          (option) __DESC__
     * @return string
     */
    public function createComboFromSql($oConnection, $strName, $strLib, $strSQL = "", $aSelectedValues = "", $bRequired = false, $bReadOnly = false, $iSize = "1", $bMultiple = false, $iWidth = "", $bChoisissez = true, $bFormOnly = false, $strEvent = "", $arSearchFields = "", $aBind = array(), $sInfoBull = "")
    {
        global $HTTP_SESSION_VARS;
        $aDataValues = array();
        if (is_array($arSearchFields)) {
            $sFilter = "";
            if (!is_array($aSelectedValues)) {
                $aSelectedValues = array($aSelectedValues);
            }
            while (list(, $val) = each($aSelectedValues)) {
                if (strlen($sFilter) != 0) {
                    $sFilter.= ",";
                }
                $sFilter.= "'".str_replace("'", "''", $val)."'";
            }
            reset($aSelectedValues);
            $sFilter = $arSearchFields[0]." IN (".$sFilter.")";
            if (stristr($strSQL, "where ")) {
                $sFilter = preg_replace("/where /i", "where ".$sFilter." AND ", $strSQL);
            } elseif (stristr($strSQL, "group by ")) {
                $sFilter = preg_replace("/group by /i", "where ".$sFilter." group by ", $strSQL);
            } elseif (stristr($strSQL, "order by ")) {
                $sFilter = preg_replace("/order by /i", "where ".$sFilter." order by ", $strSQL);
            }
            $this->_getValuesFromSQL($oConnection, $sFilter, $aDataValues, $aBind);
            $sFilter = "";
            while (list(, $val) = each($arSearchFields)) {
                if (strlen($sFilter) != 0) {
                    $sFilter.= " OR ";
                }
                $sFilter.= "UPPER(".$val.") like UPPER('%:RECHERCHE:%')";
            }
            $sFilter = "(".$sFilter.")";
            if (stristr($strSQL, "where ")) {
                $strSQL = preg_replace("/where /i", "where ".$sFilter." AND ", $strSQL);
            } elseif (stristr($strSQL, "group by ")) {
                $strSQL = preg_replace("/group by /i", "where ".$sFilter." group by ", $strSQL);
            } elseif (stristr($strSQL, "order by ")) {
                $strSQL = preg_replace("/order by /i", "where ".$sFilter." order by ", $strSQL);
            }
            $_SESSION["AssocFromSql_Search"][$this->sFormName."_".$strName] = $strSQL;
            $arSearchFields = $this->sFormName."_".$strName;
        } else {
            $this->_getValuesFromSQL($oConnection, $strSQL, $aDataValues, $aBind);
        }

        return $this->_createCombo($strName, $strLib, $aDataValues, $aSelectedValues, $bRequired, $bReadOnly, $iSize, $bMultiple, $iWidth, $bChoisissez, false, $bFormOnly, "", $strEvent, $arSearchFields, false, false, $sInfoBull);
    }

    /**
     * Appel à une mediathèque ou à une popup d'upload (gestion de fichiersde type
     * "image", "file" ou "flash" avec gestion ou non en base de données et
     * génération ou non de vignettes à la volée.
     *
     * @access public
     * @param  string $strName      Nom du champ
     * @param  string $strLib       Libellé du champ
     * @param  bool   $bRequired    (option) Champ obligatoire : false par défaut
     * @param  string $strType      (option) Type de fichier (image, file ou flash) :
     *                              "image" par défaut
     * @param  string $strSubFolder (option) Sous-répertoire de départ (chemin
     *                              relatif par rapport au répertoire d'upload) : "" par défaut
     * @param  string $strValue     (option) Valeur du champ : "" par défaut
     * @param  bool   $bReadOnly    (option) Affiche uniquement la valeur et pas le champ
     *                              (créé un input hidden) : false par défaut
     * @param  bool   $bLibrary     (option) Utilisation de la Pelican_Media library (true)
     *                              ou d'une popup d'upload (false) : true par défaut
     * @param  bool   $bFormOnly    (option) Génération du champ uniquement, sans libellé
     *                              : false par défaut
     * @param  float  $ratio        (option) valeur du ratio
     *                              : false par défaut
     * @param  string $ratioHelp    : texte indiquant des précisions sur le format attendu (ex: dimensions d'une image)
     * @return string
     */
    public function createMedia($strName, $strLib, $bRequired = false, $strType = "image", $strSubFolder = "", $strValue = "", $bReadOnly = false, $bLibrary = true, $bFormOnly = false, $ratio = false, $ratioHelp = null, $generiquePerso = false, $strValueGenerique = '')
    {
        $aAllowedExtensions = getAllowedExtensions();
        // Génération du couple libellé/champ
        $strTmp = $this->createHidden($strName, $strValue);
        if (!$bReadOnly) {
            $strTmp.= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
            $strTmp.= "<tr>";
            $strTmp.= "<td width=\"2\" id=\"div".$strName."\" nowrap=\"nowrap\">";
        }
        // Récupération de la prévisualisation (vignette si l'option est choisie dans la config) et du chemin du fichier s'il existe
        $strPathValue = $strValue;

        $iYoutubeId = '';
        if ($strType == 'video' && $strValue) {
            $isYoutube = Pelican_Cache::fetch("Media/Detail", array($strValue));
            if ($isYoutube['YOUTUBE_ID'] != '') {
                //$strType = 'youtube';
                $iYoutubeId = $isYoutube['YOUTUBE_ID'];
            }
        }

        if ($strType == 'youtube' || ($strType == 'video' && $iYoutubeId != '')) {
            if ($iYoutubeId) {
                try {
                    $details = Pelican_Cache::fetch("Service/Youtube", array('id', $iYoutubeId, date("M-d-Y", mktime())));
                } catch (fkooman\OAuth\Client\ClientConfigException $ex) {
                    $GLOBALS['flash_message'][] = array('message' => t('ERROR_YOUTUBE_OAUTH_CONFIG'), 'type' => 'error');
                } catch (fkooman\OAuth\Client\Exception\ClientConfigException $ex) {
                    $GLOBALS['flash_message'][] = array('message' => t('ERROR_YOUTUBE_OAUTH_CONFIG'), 'type' => 'error');
                }
            }
            $_sThumbnailAbsPath = $details['path'];
            $strFile = $details['path'];
        } elseif (Pelican::$config["FW_MEDIA_TABLE_NAME"] && $strValue) {
            $strPathValue = Pelican_Media::getMediaPath($strValue);
        }
        if ($strPathValue) {
            // Nom du fichier
            $strFile = basename($strPathValue);
            // Infos du fichier
            $aPathInfo = pathinfo($strFile);
            // Chemin escapé
            $escapePath = str_replace($strFile, rawurlencode($strFile), $strPathValue);
            $_sThumbnailAbsPath = $escapePath;
        }
        // hauteur max d'affichage
        $height = " height=\"".$this->_iHeightThumbnail."\"";
        // type défini à partir du nom de fichier existant
        $strTypePrecis = $strType;
        if (isset($aPathInfo)) {
            if (isset($aAllowedExtensions["image"][$aPathInfo["extension"]])) {
                $strTypePrecis = "image";
            }
        }
        $url = $this->_sUploadHttpPath.$escapePath;
        if (isset($strFile)) {
            // Pour une image, on affiche une prévisualisation
            if ($strTypePrecis == "image") {
                $linkMedia = "<img src=\"".$this->_sUploadHttpPath.$_sThumbnailAbsPath."\" style=\"border : 1px solid #CCCCCC\" alt=\"".str_replace(" ", Pelican_Html::nbsp(), $strFile)."\" ".$height." />";
            } elseif ($strTypePrecis == "youtube" || ($strType == 'video' && $iYoutubeId != '')) {
                $linkMedia = "<img src=\"".$details['path']."\" style=\"border : 1px solid #CCCCCC\" alt=\"".str_replace(" ", Pelican_Html::nbsp(), $strFile)."\" ".$height." />";
                $url = $details['url'];
            } else {
                $linkMedia = str_replace(" ", Pelican_Html::nbsp(), $strFile);
            }
            $strTmp.= "<a id=\"imgdiv".$strName."\" href=\"".$url."\" target=\"_blank\">".$linkMedia."</a>".Pelican_Html::nbsp().Pelican_Html::nbsp();
        }
        if (!$bReadOnly) {
            $this->_aIncludes["popup"] = true;
            $strTmp.= "</td>";
            if (is_array($strType)) {
                $strTmp.= "<td>";
                foreach ($strType as $type) {
                    //C'est ici que sont crées les boutons add
                    $strTmp.= "<input type=\"button\" class=\"button\" value=\"".t('FORM_BUTTON_ADD')." ".($type == "image" ? "une " : "un ").$type."\"";

                    //cas ou le ratio est saisi alors on appel
                    if (!$ratio) {
                        $strTmp.= " onclick=\"popupMedia('".$type."', '".$this->_sLibPath.Pelican::$config['LIB_MEDIA']."/', this.form.elements['".$strName."'], 'div".$strName."', '";
                    } else {
                        $strTmp.= " onclick=\"popupMediaRatio('".$type."', '".$this->_sLibPath.Pelican::$config['LIB_MEDIA']."/', this.form.elements['".$strName."'], 'div".$strName."', '";
                    }


                    if ($strSubFolder != "") {
                        $strTmp.= $strSubFolder;
                    }
                    $strTmp.= "','".str_replace("/", "\/", $this->_sUploadHttpPath."/")."',''";
                    if ($bLibrary) {
                        $strTmp.= ",true";
                    }

                    //ajout du paramètre avec la valeur du ratio
                    if ($ratio) {
                        $strTmp.= ",'".Pelican:: $config['RECHERCHE_RATIO_DETAIL'][$ratio]['value']."'";
                    }
                    $strTmp.= ");\" />&nbsp;";
                }
            } else {
                $strTmp.= "<td style=\"vertical-align:top;\"><input type=\"button\" class=\"button\" value=\"".t('FORM_BUTTON_ADD')."\"";

                //cas ou le ratio est saisi alors on appel
                if (!$ratio) {
                    $strTmp.= " onclick=\"popupMedia('".$strType."', '".$this->_sLibPath.Pelican::$config['LIB_MEDIA']."/', this.form.elements['".$strName."'], 'div".$strName."', '";
                } else {
                    $strTmp.= " onclick=\"popupMediaRatio('".$strType."', '".$this->_sLibPath.Pelican::$config['LIB_MEDIA']."/', this.form.elements['".$strName."'], 'div".$strName."', '";
                }
                if ($strSubFolder != "") {
                    $strTmp.= $strSubFolder;
                }
                $strTmp.= "','".str_replace("/", "\/", $this->_sUploadHttpPath."/")."',''";
                if ($bLibrary) {
                    $strTmp.= ",true";
                }

                //ajout du paramètre avec la valeur du ratio
                if ($ratio) {
                    $strTmp.= ",'".Pelican:: $config['RECHERCHE_RATIO_DETAIL'][$ratio]['value']."'";
                }
                $strTmp.= ");\" />";
            }
            $strTmp.= Pelican_Html::nbsp()."<input type=\"button\" class=\"button\" value=\"".t('FORM_BUTTON_FILE_DELETE')."\"";
            $strTmp.= " onclick=\"if(confirm('".t('FORM_MSG_CONFIRM_DEL')."')) {this.form.elements['".$strName."'].value=''; document.getElementById('div".$strName."').innerHTML = '';}\" />";

            if ($generiquePerso) {
                $strTmp .= "<input type=\"checkbox\" value=\"1\"  name=\"".$strName."_GENERIQUE\" id=\"".$strName."_GENERIQUE\" ".($strValueGenerique == 1 ? "checked" : "")."/>".Pelican_Html::nbsp()."<label>".t('VISUELS_PREFERRED_PRODUCT')."</label>";
                //$strTmp .= $this->createCheckBoxFromList($strName.'_GENERIQUE', t('VISUEL_GENERIQUE'), array('1' => ""), $strValueGenerique, false, $bReadOnly, "h", false, "");
            }

            //ratio attendu
            if ($ratio) {
                $strTmp .= empty($ratioHelp) ? t('FORMAT_ATTENDU').Pelican:: $config['RECHERCHE_RATIO_DETAIL'][$ratio]['lib'] : $ratioHelp;
                if (!empty(Pelican:: $config['RECHERCHE_RATIO_DETAIL'][$ratio]['pixel'])) {
                    $strTmp .= ' | '.t('FORMAT_MIN').Pelican:: $config['RECHERCHE_RATIO_DETAIL'][$ratio]['pixel'];
                }
            }

            $strTmp.= "</td>";
            $strTmp.= "</tr>\n";
            $strTmp.= "</table>\n";
            // Génération de la fonction js de vérification.
            if ($bRequired) {
                $this->_aIncludes["text"] = true;
                if ($generiquePerso) {
                    $this->_sJS.= "if ( isBlank(obj.".$strName.".value) &&  !obj.".$strName."_GENERIQUE.checked ) {\n";
                } else {
                    $this->_sJS.= "if ( isBlank(document.getElementById(\"div".$strName."\").innerHTML)) {\n";
                }
                $this->_sJS.= "$('#div".$strName."').next('td').addClass('alert-blank');\n";
                $this->_sJS.= "$('#div".$strName."').parents('table:first').parent().parent().addClass('alert-field-blank');\n";
                $this->_sJS.= "$('#div".$strName."').parents('table:first').parent().parent().parents('table:first').parent().addClass('alert-blank');\n";
                $this->_sJS.= "$('#div".$strName."').parents('table:first').parent().parent().parents('table:first').parent().parent().prev().find('td:first').addClass('alert-blank');\n";
                $this->_sJS.= "$('#FIELD_BLANKS').val(1);\n";

                $this->_sJS.= "var eMedia = $('#div".$strName."').parents('table:first').parents('table:first').parent().parent().prev();\n";
                $this->_sJS.= "if(eMedia.find('td:not(:empty):first').find('img').attr('src') == '/library/public/images/toggle_zone_close.gif'){;\n";
                $this->_sJS.= "$('#div".$strName."').parents('table:first').parents('table:first').parent().parent().prev().trigger('click');\n";
                $this->_sJS.= "};\n";

                $this->_sJS.= "$('#div".$strName."').next('td').click(function() {\n";
                $this->_sJS.= "$(this).removeClass('alert-blank');\n";
                $this->_sJS.= "$(this).parents('table:first').parent().parent().removeClass('alert-field-blank');\n";
                $this->_sJS.= "$('#div".$strName."').parents('table:first').parent().parent().parents('table:first').parent().removeClass('alert-blank');\n";
                $this->_sJS.= "$('#div".$strName."').parents('table:first').parent().parent().parents('table:first').parent().parent().prev().find('td:first').removeClass('alert-blank');\n";
                $this->_sJS.= "});\n";
                $this->_sJS.= "};\n";
            }
        }
        if (!$bFormOnly) {
            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
        }

        return $this->output($strTmp);
    }

    /**
     * Manipulation des données issues d'un POST pour créer une entrée associée à
     * chaque instance de l'objet multiple
     *
     * Un tableau de type Array(PREFIXE_CHAMP1_1, PREFIXE_CHAMP1_2, PREFIXE_CHAMP2_2)
     * crée un tableaau du type Array(1=>CHAMP1,2=>(CHAMP1, CHAMP2))
     *
     * @access public
     * @param  string $strName    Identifiant de l'objet défini dans le createMulti
     * @param  string $strPrefixe (option) Préfixe urilisé pour les nom de champs de
     *                            l'objet multiple : "multi" par défaut
     * @return void
     */
    public static function readMulti($strName, $strPrefixe = "multi")
    {
        global $longueur;

        if ($strPrefixe) {
            if (isset($_POST['count_multi'.(Pelican_Db::$values['page'] - 1).'_'.$strPrefixe])) {
                Pelican_Db::$values['count_'.$strPrefixe] = $_POST['count_multi'.(Pelican_Db::$values['page'] - 1).'_'.$strPrefixe];
            }
            if (isset($_POST['count_multiZone'.Pelican_Db::$values['AREA_ID'].'_'.(Pelican_Db::$values['DB_INDEX']).'_'.$strPrefixe])) {
                Pelican_Db::$values['count_'.$strPrefixe] = $_POST['count_multiZone'.Pelican_Db::$values['AREA_ID'].'_'.(Pelican_Db::$values['DB_INDEX']).'_'.$strPrefixe];
            }
        }
        $DELETE = array();
        $longueur = strlen($strPrefixe);
        $count = (Pelican_Db::$values["count_".$strName] + 1);

        if ($count) {
            for ($j = 0; $j < $count + $supp; $j++) {
                if (isset(Pelican_Db::$values[$strPrefixe.$j.'_multi_display'])) {
                    if (!Pelican_Db::$values[$strPrefixe.$j.'_multi_display']) {
                        $supp++;
                    }
                }
            }
            foreach (Pelican_Db::$values as $key => $value) {
                $field = "";
                if (substr($key, 0, $longueur) == $strPrefixe) {
                    for ($j = 0; $j < $count + $supp; $j++) {
                        if (substr($key, 0, ($longueur + strlen($j) + 1)) == $strPrefixe.$j."_") {

                            $field = str_replace($strPrefixe.$j."_", "", $key);
                            if ($field == "multi_display" && !$value) {
                                $DELETE[$j] = true;
                                unset(Pelican_Db::$values[$strName][$j]);
                            }
                            if (!valueExists($DELETE, $j)) {
                                Pelican_Db::$values[$strName][$j][$field] = $value;
                            }
                        }
                        if (!valueExists($DELETE, $j)) {
                            Pelican_Db::$values[$strName][$j][Pelican_Db::$values["increment_".$strName]] = ($j + 1);
                        }
                    }
                }
            }
        }
    }

    /**
     * mise à true de la variable _bUseMulti
     */
    public function setMulti()
    {
        $this->_bUseMulti = true;
    }

    /**
     * retourne la valeur de la variable _bUseMulti
     */
    public function getUseMulti()
    {
        return $this->_bUseMulti;
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  string   $strName         Nom du champ
     * @param  string   $strLib          Libellé du champ
     * @param  bool     $bRequired       (option) Champ obligatoire : false par défaut
     * @param  string   $googleKey       (option) __DESC__
     * @param  string   $strAddressValue (option) __DESC__
     * @param  string   $strLatValue     (option) __DESC__
     * @param  string   $strLongValue    (option) __DESC__
     * @param  bool     $bReadOnly       (option) Affiche uniquement la valeur et pas le champ
     *                                   (créé un input hidden) : false par défaut
     * @param  bool     $bFormOnly       (option) Génération du champ uniquement, sans libellé
     *                                   : false par défaut
     * @param  string   $strEvent        (option) Handler d'événements sur le champ : "" par
     *                                   défaut
     * @param  __TYPE__ $width           (option) __DESC__
     * @param  __TYPE__ $height          (option) __DESC__
     * @return string
     */
    public function createMapPremium($strName, $strLib, $bRequired = false, $googleKey = "", $strAddressValue = "", $strLatValue = "", $strLongValue = "", $bReadOnly = false, $bFormOnly = false, $strEvent = "", $width = "470", $height = "200")
    {
        $directOutput = $this->bDirectOutput;
        $this->bDirectOutput = false;
        if ($googleKey) {
            //$strTmp.= Pelican_Html::script(array(src => "http://maps.google.com/maps?file=api&amp;v=3&amp;sensor=true&amp;key=" . $googleKey));
            $strTmp.= Pelican_Html::script(array(src => "https://maps.googleapis.com/maps/api/js?client=".$googleKey."&amp;sensor=true&amp;libraries=places"));
            $strTmp.= $this->createHidden($strName, $strValue);
            $strTmp.= $this->createHidden($strName."_ADDRESS_HIDDEN", $strAddressValue);
            $strTmp.= $this->createHidden($strName."_ADDRESS_HIDDEN_LAT", $strLatValue);
            $strTmp.= $this->createHidden($strName."_ADDRESS_HIDDEN_LONG", $strLongValue);
            $strTmp.= Pelican_Html::label("Latitude".($bRequired && !$bReadOnly ? " ".REQUIRED : "")." : ").$this->createInput($strName."_LATITUDE", "Latitude", 20, "float", $bRequired, $strLatValue, $bReadOnly, 20, true, $strEvent);
            $strTmp.= Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp();
            $strTmp.= Pelican_Html::label("Longitude".($bRequired && !$bReadOnly ? " ".REQUIRED : "")." : ").$this->createInput($strName."_LONGITUDE", "Longitude", 20, "float", $bRequired, $strLongValue, $bReadOnly, 20, true, $strEvent);
            $strTmp.= Pelican_Html::br();
            $divMap = Pelican_Html::div(array(id => $strName."_MAP", style => "width:".$width."px;height: ".$height."px;"));
            $divSearch = $this->createInput($strName."_ADDRESS", $strLib, 255, "", "", $strAddressValue, false, 35, true, $strEvent);
            $divSearch.= Pelican_Html::nbsp().$this->createbutton($strName."_ADDRESS_BTN_FIND", t('FORM_BUTTON_SEARCH'), "");
            $divSearch.= Pelican_Html::nbsp().$this->createbutton($strName."_ADDRESS_BTN_REST", "Réinitialiser", "javascript:void( null ); return false", true);
            //$divSearch .= Pelican_Html::nbsp().$this->createbutton( $strName . "_MYLOC", "Ma localisation", "javascript:void( null ); return false", true);
            $divSearch = Pelican_Html::div(array(style => "text-align:center;"), $divSearch);
            $strTmp.= Pelican_Html::div(array(style => "width:".$width."px;height: ".($height + 25)."px;border:#ccc 2px solid;background-color:#eee;margin-top:5px;"), $divMap.$divSearch);
        } else {
            $strTmp = Pelican_Html::div(array("class" => "erreur", style => "widht:70%"), "Veuillez insérer la clé Google fournie par le site  ".Pelican_Html::a(array(href => "http://code.google.com/intl/fr-FR/apis/maps/signup.html"), "Google Maps API"));
            $this->createHidden($strName."_LATITUDE", $strLatValue);
            $this->createHidden($strName."_LONGITUDE", $strLongValue);
        }
        if (!$bFormOnly) {
            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, false, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "", "", $this->_sFormDisposition);
        }
        $this->map[$strName] = $strName;
        $this->_aIncludes["mapv3"] = true;
        $this->bDirectOutput = $directOutput;

        return $this->output($strTmp);
    }

    /**
     * Création d'un objet Multiple : répétition à volonté d'un bout de formulaire
     * avec ses contrôles de saisie
     *
     * ATTENTION : inclure xt_mozilla_fonctions en tout premier (avant tout autre js)
     * pour pouvoir utiliser cette méthode avec Mozilla
     *
     * @access public
     * @param  string   $strName          Nom du champ
     * @param  string   $strLib           Libellé du champ
     * @param  __TYPE__ $call             __DESC__
     * @param  mixed    $tabValues        Tableau de données (de type queryTab)
     * @param  string   $incrementField   Nom du champ servant à incrémenter les
     *                                    instances de l'objet
     * @param  bool     $bReadOnly        (option) Affiche uniquement les valeurs et pas les
     *                                    champs : false par défaut
     * @param  int      $intMaxIterations (option) Nombre maximum d'itérations autorisé :
     *                                    "" par défaut
     * @param  bool     $bAllowDeletion   (option) Suppression d'instance autorisée ou non :
     *                                    true par défaut
     * @param  bool     $bAllowAdd        (option) Ajout d'instance autorisé ou non : true par
     *                                    défaut
     * @param  string   $strPrefixe       (option) Préfixe des noms de champ : "multi" par
     *                                    défaut
     * @param  string   $line             (option) Nom du tableau de données utilisé par le
     *                                    formulaire parent : "values" par défaut
     * @param  string   $strCss           (option) Classe CSS à utiliser : "multi" par défaut
     * @param  __TYPE__ $sColspan         (option) __DESC__
     * @param  string   $sButtonAddMulti  (option) Libellé du boutton ajouter du multi
     * @param  string   $complement       (option) __DESC__
     * @return string
     */
    public function createMultiHmvc($strName, $strLib, $call, $tabValues, $incrementField, $bReadOnly = false, $intMinMaxIterations = "", $bAllowDeletion = true, $bAllowAdd = true, $strPrefixe = "multi", $line = "values", $strCss = "multi", $sColspan = "2", $sButtonAddMulti = "", $complement = "", $perso = false, $extendedArgs = null)
    {
        global $_GET, $_POST, $_SERVER, $HTTP_SESSION_VARS;
        // Nécessite $multi, $values
        // ATTENTION : ajouter aux noms des champs
        // on annule temporairement le direct output s'il est défini
        // affichage d'un séparateur
        // affichage du bouton pour les ajouts multiples
        // $limit=limitFormTable("120", "520", false);
        // souvent utilisé : $readO

        $strTmp = '';
        $compteur = -1;
        $oForm = & $this;
        $readO = $bReadOnly;
        $bDirectOutput = $oForm->bDirectOutput;
        $oForm->bDirectOutput = false;

        if (!is_array($intMinMaxIterations)) {
            $intMaxIterations = $intMinMaxIterations;
            $intMinIterations = 0;
        } else {
            $intMaxIterations = $intMinMaxIterations[1];
            $intMinIterations = $intMinMaxIterations[0];
        }

        // ajout du controller
        if (!empty($call['path']) && file_exists($call['path'])) {
            include_once ($call['path']);
        }
        //Decoupe avec les _
        $strCut = explode("_", $strName);
        $strTmp .= $this->showSeparator("formsep", true, $sColspan);
        $strTmp .= $this->createLabel(t(end($strCut)), '');
        $strTmp.= '<tr><td id="'.$strName.'_td" colspan="'.$sColspan.'" width="100%">';

        //Sauvegarde des vérification js existante
        $saveJS = $this->_sJS;
        $this->_sJS = '';

        //Sauvegarde du name de chaque Multi
        if ($strName != "") {
            $this->_aMultiTrackNames[] = $strName;
        }

        // sauvegarde les hiddens existant et vide le tableau
        $saveHidden = $this->form_class_hidden;
        $this->form_class_hidden = array();

        // Construction de subForm qui va servir au clone
        $strTmp .= '<table id="'.$strName.'_subForm" class="'.$strName.'_subForm multi" style="display:none">';
        $strTmp .= $this->headMultiHmvc($strName.'__CPT__', '__CPT1__', $readO, $bAllowDeletion, '');
        $strTmp .= call_user_func_array(array($call['class'], $call['method']), array($oForm, array(), $bReadOnly, $strName."__CPT___", $perso, $extendedArgs));
        $strTmp .= $this->putHidden();
        $strTmp .= '</table>';

        // Remise en place des hidden
        $this->form_class_hidden = $saveHidden;

        // Remise en place des vérifications JS existante
        $strTmp.= $this->createHidden($strName."_subFormJS", $this->_sJS, true);
        $this->_sJS = $saveJS;
        if (is_array($tabValues) && !empty($tabValues)) {
            foreach ($tabValues as $line) {
                $compteur++;
                if ($compteur % 2) {
                    $strCss2 = "background-color=#F9FDF3;";
                    $color = "#F9FDF3";
                } else {
                    $strCss2 = "background-color=#FAEADA;";
                    $color = "#FAEADA";
                }

                //drag and drop au cas ou
                //$strTmp.= "<table draggable=\"true\" ondragstart=\"moveMulti('" . $strName . "')\" id=\"" . $strName . $compteur . "_subForm\"  bgcolor=\"" . $color . "\"cellspacing=\"0\" cellpadding=\"0\" style='" . $strCss2 . "' class=\"" . $strName . "_subForm " . $strCss . "\" width=\"100%\">";
                $strTmp.= "<table id=\"".$strName.$compteur."_subForm\"  bgcolor=\"".$color."\"cellspacing=\"0\" cellpadding=\"0\" style='".$strCss2."' class=\"".$strName."_subForm ".$strCss."\" width=\"100%\">";

                $strTmp.= $this->headMultiHmvc($strName.$compteur, $compteur, $readO, $bAllowDeletion, $line);
                // encadrement du js
                $this->_sJS.= "if (document.getElementById('".$strPrefixe.$compteur."_multi_display') != 'undefined' && document.getElementById('".$strPrefixe.$compteur."_multi_display')!= null) {\n if (document.getElementById('".$strPrefixe.$compteur."_multi_display').value) {\n";
                // retro compatibite
                //hmvc
                $strTmp.= call_user_func_array(array($call['class'], $call['method']), array($oForm, $line, $bReadOnly, $strName.$compteur.'_', $perso, $extendedArgs));
                // fin du js
                $this->_sJS.= "}\n}\n";
                $strTmp.= "</table>\n";
            }
        }

        $strTmp .= $this->createHidden("increment_".$strName, $incrementField);
        $strTmp .= $this->createHidden("count_".$strName, count($tabValues));

        // Gestion du minimum
        if ($intMinIterations) {
            $this->_sJS .= "var count = eval($('#count_".$strName."').val() || 0) + 1;\n";
            $this->_sJS .= "if (count < ".$intMinIterations.") {\n";
            $strMessage = t('MIN_ITERATION_1').$intMinIterations.t('MIN_ITERATION_1')." \\"."\"".(strip_tags(str_replace("\"", "\\"."\"", $strLib)))."\\"."\"";
            $this->_sJS .= "    alert(\"".$strMessage."\");\n";
            //$this->_sJS.= "alert(\"" . t('FORM_MSG_VALUE_REQUIRE') . " \\" . "\"" . (strip_tags(str_replace("\"", "\\" . "\"", $strLib))) . "\\" . "\"";
            //$this->_sJS .= "    return false;\n";
            $this->_sJS.= "$('#FIELD_BLANKS').val(1);\n";
            $this->_sJS .= "}\n";
        }
        if ($intMaxIterations) {
            $strTmp.= $this->createHidden("max_".$strName, $intMaxIterations);
        }

        $strTmp.= "</td></tr>\n";

        if (!$bReadOnly && $bAllowAdd) {
            $lib = Pelican_Html::input(array(name => $strName, type => "button", "class" => "buttonmulti", value => ($sButtonAddMulti ? $sButtonAddMulti : t('FORM_BUTTON_ADD_MULTI')." ".Pelican_Text::htmlentities($strLib)), style => "width:200px;", onclick => "addClone('".$strName."','".$intMaxIterations."')"));
            $strTmp.= Pelican_Html_Form::get($lib, "", false, false, $this->sStyleLib, $this->sStyleVal, "", "center", $this->_sFormDisposition);
        }

        $this->_aIncludes["multi"] = true;
        // il faut inclure tous les js pour les contrôles de saisie des champs ajoutés à la volée (on ne peut pas savoir ce dont on va avoir besoin à l'avance)
        $this->_aIncludes["num"] = true;
        $this->_aIncludes["text"] = true;
        $this->_aIncludes["date"] = true;
        $this->_aIncludes["list"] = true;
        $this->_aIncludes["popup"] = true;
        $this->_aIncludes["crosstab"] = true;
        $this->_bUseMulti = true;
        $this->bDirectOutput = $bDirectOutput;

        return $this->output($strTmp);
    }

    /**
     * __DESC__
     *
     * @access public
     * @param  __TYPE__ $multi          __DESC__
     * @param  __TYPE__ $compteur       __DESC__
     * @param  __TYPE__ $readO          __DESC__
     * @param  __TYPE__ $bAllowDeletion __DESC__
     * @return __TYPE__
     */
    public function headMultiHmvc($multi, $compteur, $readO, $bAllowDeletion, $values = array())
    {
        $return = '';
        if (!isset($readO)) {
            $readO = false;
        }
        if ($bAllowDeletion) {
            $compteur = is_int($compteur) ? ($compteur + 1) : $compteur;
            $return.= $this->createLabel(" n° ".$compteur, ($readO ? "" : "<input type=\"button\" id=\"".$multi."\" class=\"buttonmulti btn_delete_clone\" value=\"".t('FORM_BUTTON_FILE_DELETE')."\" onclick=\"delClone('".$multi."', ".$compteur.")\" />"));
        }
        // drag and drop au cas ou
        //$return.= $this->createHidden($multi . "_order", $compteur, true);
        $return .= $this->createInput($multi."_PAGE_ZONE_MULTI_ORDER", t('ORDER_MULTI'), 2, "number", false, $values['PAGE_ZONE_MULTI_ORDER'], $readO, 2);
        $return .= $this->createHidden($multi."_multi_display", "1", true);

        return $return;
    }

    /**
     * Génère un bouton
     *
     * @access public
     * @param string $strName Nom du champ
     * @param string $strLib (option) Libellé du champ : "" par défaut
     * @param string $strFunction (option) Fonction js à exécuter quand clic du
     * bouton : "" par défaut
     * @param bolean $bDisable (option) Bolean indiquant si le bouton à generer est
     * desactiver ou
     * @return string
     */
    public function createButton($strName, $strLib = "", $strFunction = "", $bDisable = false, $classCss = "", $moreAttr = "")
    {
        $this->countInputName($strName);
        $strTmp = "<input ".$moreAttr." class=\"button $classCss\" type=\"button\" name=\"".$strName."\" id=\"".$strName."\"";
        $strTmp.= " value=\"".Pelican_Text::htmlentities($strLib)."\"";
        $strTmp.= " onclick=\"";
        if ($strFunction == "close")
            $strTmp.= "javascript:self.close();";
        else
            $strTmp.= $strFunction;
        $strTmp.= "\" ";
        if ($bDisable) {
            $strTmp.= " disabled";
        }
        $strTmp.= " />";
        return $this->output($strTmp);
    }

    public function setView($view)
    {
        $this->currentView = $view;
    }

    /**
     * Ferme le formulaire, met les fonctions js de vérification
     *
     * @access public
     * @param string $_sJSPath (option) Chemin relatif à partir de la page en cous,
     * ou absolu, où trouver les fonctions javascript :
     * "/library/Pelican/Form/public/js/" par défaut
     * @return string
     */
    public function close($_sJSPath = "")
    {
        $head = false;
        if ($this->currentView) {
            $head = $this->currentView->getHead();
        }

        if (is_array($this->_aMultiTrackNames) && count($this->_aMultiTrackNames) > 0) {
            $sMultiTrackNames = implode(',', $this->_aMultiTrackNames);
            $strTmp.= $this->createHidden('TRACK_MULTINAMES', $sMultiTrackNames);
        }
        // csrf
        $strTmp.= $this->createCsrfInput();
        $sSiteCode = $this->getSiteCode();
        $strTmp.= $this->createHidden('FIELD_BLANKS', '0', false, false);
        $strTmp.= $this->createHidden('IS_PERSO', '0', false, false);
        $strTmp.= $this->createHidden('SITE_ID_NAME', $sSiteCode, false, false);
        $strTmp.= $this->createHidden('FOLDER_ALLOWED_ALL', Pelican::$config ["MEDIA_DIRECTORY_ALLCOUNTRIES"]['ALL'], false, false);
        $strTmp = $this->putHidden();
        $this->endJS = '';
        if (!$_sJSPath)
            $_sJSPath = $this->_sLibPath.$this->_sLibForm."/js/";
        $strTmp.= "</form>\n";
        /*         * *** init de tinyMCE ************** */
        if ($this->tinymce && $this->aEditor) {
            $this->showEditor = true;
            $strTmp.= Pelican_Html::script(array(src => Pelican::$config["LIB_PATH"].$this->sLibOther."/tiny_mce/tiny_mce.js"));
            $ed = $this->getTiny();
            $strTmp.= Pelican_Html::script($ed);
        }
        /*         * *** fin init tinyMCE ************* */

        /** virtual keyboard */
        if ($this->bVirtualKeyboard && $this->_InputVK && empty($_GET['readO'])) {
            $strTmp.= $this->createVirtualKeyboard($this->_InputVK[0], true);
        }
        if ($this->suggest) {
            $strTmp.= "<div style=\"top: 45px; left: 243px; width:202px;\" id=\"search_suggest\"></div>";
            $strTmp.= "<link rel=\"STYLESHEET\" type=\"text/css\" href=\"".$this->_sLibPath.$this->_sLibForm."/css/suggest.css\">";
        }
        if (!$head) {
            $strTmp.= Pelican_Html::script(array(src => $_sJSPath."ajax.js"));
        } else {
            $head->setJs($_sJSPath."ajax.js");
        }
        while ($ligne = each($this->_aIncludes)) {
            if ($ligne["value"]) {
                switch ($ligne["key"]) {
                    case "num": {
                            if (!$head) {
                                $strTmp.= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_num_controls.js\"></script>\n";
                            } else {
                                $head->setJs($_sJSPath."ajax.js");
                            }

                            break;
                        }
                    case "text": {
                            if (!$head) {

                                $strTmp.= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_text_controls.js\"></script>\n";
                            } else {
                                $head->setJs($_sJSPath."xt_text_controls.js");
                            }
                            break;
                        }
                    case "color": {
                            $strTmp.= "<script type=\"text/javascript\">
			  \$(document).ready( function() {
				\$(\".colors\").miniColors({
				});
			  });
			  </script>";
                            break;
                        }
                    case "date": {
                            if (!$head) {
                                $strTmp.= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_date_controls.js\"></script>\n";
                            } else {
                                $head->setJs($_sJSPath."xt_date_controls.js");
                            }

                            $strTmp.= "<script type=\"text/javascript\">
			  \$(function() {
						\$(\".datepicker\").datepicker({
						  showOn: 'button',
						  buttonImage: '".$this->_sLibPath.$this->_sLibForm."/images/cal.gif',
						  buttonImageOnly: true,
						  changeMonth: true,
			  changeYear: true,
			  duration: 'fast',
			  showAnim: 'fadeIn',
			  appendText: '&nbsp;".Pelican_Html_Form::comment("(".t('DATE_FORMAT_LABEL').")")."',
			  autoSize: true 
						});
					  });
					  </script>";
                            $strTmp.= "<script type=\"text/javascript\">var dateLanguageFormat='".t('DATE_FORMAT_DB')."';</script>\n";
                            break;
                        }
                    case "list": {
                            if (!$head) {
                                $strTmp.= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_list_fonctions.js\"></script>\n";
                            } else {
                                $head->setJs($_sJSPath."xt_list_fonctions.js");
                            }
                            break;
                        }
                    case 'ordered_list': {
                            if (!$head) {
                                $strTmp.= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_ordered_list_fonctions.js\"></script>\n";
                            } else {
                                $head->setJs($_sJSPath."xt_ordered_list_fonctions.js");
                            }
                            break;
                        }
                    case "popup": {
                            if (!$head) {
                                $strTmp.= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_mozilla_fonctions.js\"></script>\n";
                                $strTmp.= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_popup_fonctions.js\"></script>\n";
                            } else {
                                $head->setJs($_sJSPath."xt_mozilla_fonctions.js");
                                $head->setJs($_sJSPath."xt_popup_fonctions.js");
                            }
                            break;
                        }
                    case "crosstab": {
                            if (!$head) {
                                $strTmp.= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_crosstab_fonctions.js\"></script>\n";
                            } else {
                                $head->setJs($_sJSPath."xt_crosstab_fonctions.js");
                            }
                            break;
                        }
                    case "multi": {
                            if (!$head) {
                                $strTmp.= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_multi_fonctions.js\"></script>\n";
                            } else {
                                $head->setJs($_sJSPath."xt_multi_fonctions.js");
                            }
                            break;
                        }
                    case "sub": {
                            if (!$head) {
                                $strTmp.= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_sub_fonctions.js\"></script>\n";
                            } else {
                                $head->setJs($_sJSPath."xt_sub_fonctions.js");
                            }
                            break;
                        }
                    case "suggest": {
                            if (!$head) {
                                $strTmp.= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_suggest_fonctions.js\"></script>\n";
                            } else {
                                $head->setJs($_sJSPath."xt_suggest_fonctions.js");
                            }
                            break;
                        }
                    case "virtualkeyboard": {
                            if (!$head) {
                                $strTmp.= "<script type=\"text/javascript\" src=\"/library/External/tiny_mce/plugins/Jsvk/jscripts/vk_popup.js\"></script>\n";
                            } else {
                                $head->setJs($_sJSPath."/library/External/tiny_mce/plugins/Jsvk/jscripts/vk_popup.js");
                            }
                            break;
                        }
                    case "map": {
                            if (!$head) {
                                $strTmp.= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_map_fonctions.js\"></script>\n";
                            } else {
                                $head->setJs($_sJSPath."xt_map_fonctions.js");
                            }
                            break;
                        }
                    case "mapv3": {
                            if (!$head) {
                                $strTmp.= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_map_fonctions_v3.js\"></script>\n";
                            } else {
                                $head->setJs($_sJSPath."xt_map_fonctions_v3.js");
                            }
                            break;
                        }
                }
            }
        }
        $strTmp.= "<script type=\"text/javascript\">";
        if ($this->_bUseMulti) {
            $strTmp.= "var ".$this->sCheckFunction."_multi=new Function(\"obj\",\"return true\");\n";
        }
        $strTmp.= "var activeInput;\n";
        $strTmp.= "function ".$this->sCheckFunction." (obj) {\n";
        $strTmp.= $this->_sJS;
        /* $strTmp .= "
          try{
          console.log(obj);
          return false;
          }catch(e){};
          "; */
        if ($this->sCheckFunction) {
            if ($this->_bUseMulti) {
                $strTmp.= "return ".$this->sCheckFunction."_multi(obj);\n";
            } else {
                if ($this->bBlockSubmit) {
                    $strTmp.= $this->sCheckFunction." = blockSubmit;\nreturn true;\n";
                }
            }
        }
        $strTmp.= "}\n";
        // Mise en place du blockage de soumission multiple
        $strTmp.= "function blockSubmit(){\nreturn false;\n}\n";
        // Gestion du focus
        $strTmp.= "function fwFocus(obj){\nobj.focus();\n}\n";
        // Mise en place du focus par défaut
        if ($this->_sDefaultFocus && $_SERVER["SCRIPT_NAME"] != $this->_sLibPath.$this->_sLibForm."/popup_multi.php") {
            $strTmp.= "if (document.".$this->sFormName.".".$this->_sDefaultFocus.".style.display != \"none\") {\n";
            $strTmp.= "if (document.".$this->sFormName.".".$this->_sDefaultFocus.".disabled) {\n";
            $strTmp.= "fwFocus(document.".$this->sFormName.".".$this->_sDefaultFocus.");\n";
            $strTmp.= "}\n";
            $strTmp.= "}\n";
        }
        if ($this->displayTab) {
            $strTmp.= "
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
				document.getElementById(formTab + '_tab_' + id).style.display = styledisplay;
				document.getElementById(formTab + '_' + id + '_1').src=document.getElementById(formTab + '_' + id + '_1').src.replace(state1,state2);
				document.getElementById(formTab + '_' + id + '_2').style.backgroundImage=document.getElementById(formTab + '_' + id + '_2').style.backgroundImage.replace(state1,state2);
				document.getElementById(formTab + '_' + id + '_3').src=document.getElementById(formTab + '_' + id + '_3').src.replace(state1,state2);
				}
				function tabFocus(obj) {
				var ori = obj;
				if(obj ){
				while (obj != null && obj.tagName != \"DIV\"  && typeof(obj.id) != 'undefined' ) {
					if(obj.id.indexOf(formTab + '_tab_') == -1){
						if (!obj.parentElement) {
							obj = obj.parentNode;
						} else {
							obj = obj.parentElement;
						}
					}
				}
				if(typeof(obj.id) != 'undefined'){
				if (obj.id.indexOf(formTab + '_tab_') != -1) {
					id = obj.id.replace(formTab + '_tab_','');
					if (currentTab != id) {
						ongletFW(id);
					}
				}
				}
				ori.focus();
				}
				}
				fwFocus = tabFocus\r\n";
        }
        if ($this->suggest) {
            foreach ($this->suggest as $name => $val) {
                $this->endJS.= "buildSearch('".$name."',Array('".implode("','", str_replace("'", "\\'", $val))."'));\n";
            }
        }
        if ($this->map) {
            foreach ($this->map as $name => $val) {
                $initMap[] = "mapControl('".$name."');";
            }
            $this->endJS.= "if ( window.addEventListener ) {
					window.addEventListener('load', function(){ ".implode("\n", $initMap)." }, false);
					} else {
					if ( window.attachEvent ) {
					window.attachEvent('onload', function(){ ".implode("\n", $initMap)." } );
					}
					}
					";
        }
        $strTmp.= $this->endJS."</script>\n";
        $this->controlDuplicateInputName();
        if (Pelican::$config['HMVC']) {

            if (!$head) {
                $strTmp.= '<script src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/hmvc.js'.'" type="text/javascript"></script>';
            } else {
                $head->setJs(Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/hmvc.js');
            }
        }
        return $this->output($strTmp);
    }

      public function getSiteCode($sSeprator=null)
    {


        $aSiteAllowed = array(Pelican::$config["SITE_MASTER"]);

        if (is_array($_SESSION[APP]['navigation']['site'])) {
            foreach ($_SESSION[APP]['navigation']['site'] as $aSiteValues) {
                if (!in_array($aSiteValues['id'], $aSiteAllowed)) {
                    if (!empty($aSiteValues['id'])) {
                        $aSiteAllowed[] = $aSiteValues['id'];
                        $aSiteNameAllowed[] = explode(' ', $aSiteValues['name']);
                    }
                }
            }
        }

        foreach ($aSiteNameAllowed as $iKey => $aValue) {
            if (is_array($aValue) && sizeof($aValue) > 0) {
                if ($aValue[0] != '--') {
                    $aSiteCode[] = $aValue[0];
                }
            }
        }

        if($sSeprator!=null){
             $sSiteAllowed = implode($sSeprator, $aSiteCode);
        }else{
			$sSiteAllowed = implode('-', $aSiteCode);
        }


        return $sSiteAllowed;
    }

    public function createTitle($title, $class = "", $align = '', $valign = "h", $colspan = "2", $addon = "")
    {
        $title = Pelican_Html_Form::tr(Pelican_Html_Form::td(array('class' => $class.' form_title',
                    'style' => 'padding-top:15px;', 'valign' => $valign, 'align' => $align,
                    'colspan' => $colspan), Pelican_Html::h1($title.$addon)));

        return $this->output($title);
    }
}
