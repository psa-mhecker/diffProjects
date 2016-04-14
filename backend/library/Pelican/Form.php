<?php
/**
 * Gestion des formulaires de saisie avec contrôles de saisie centralisée.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */

/** Fichier de configuration */
require_once pelican_path('Html.Form');
require_once pelican_path('Media');

/**
 * Cette classe permet de générer de formulaires.
 *
 * Elle facilite leur création en automatisant le processus de gestion des
 * contrôles de saisie javascript et en centralisant les traitement et la mise en place d'objets de formulaires complexes comme des associtives avec recherche à distance dans la base de données, utilisation d'assistant de mise en page en ligne de remplissage automatique de liste ou d'objets répétés à volonté au sein du formulaire sans recharger la page
 *
 * @author Jean-Baptiste Ruscassie <jbruscassie@businessdecision.com>, Raphaël Carles <rcarles@businessdecision.com>, Laurent Franchomme <lfranchomme@businessdecision.com>
 *
 * @since 15/01/2002
 *
 * @version 3.0
 */
class Pelican_Form
{
    /**
     * @access public
     *
     * @var mixed 
     */
    public $bDirectOutput = true;

    /**
     * @access public
     *
     * @var mixed 
     */
    public $sCheckFunction = "";

    /**
     * Indicateur qui permet de bloquer le double click sur le bouton de submit.
     *
     * @access public
     *
     * @var bool
     */
    public $bBlockSubmit = true;

    /**
     * @access public
     *
     * @var mixed 
     */
    public $sFormName = "";

    /**
     * @access public
     *
     * @var mixed 
     */
    public $sStyleLib = "formlib";

    /**
     * @access public
     *
     * @var mixed 
     */
    public $sStyleVal = "formval";

    /**
     * @access private
     *
     * @var mixed 
     */
    public $_aIncludes = array("num" => false, "text" => false, "date" => false, "list" => false, "popup" => false, "multi" => false, "sub" => false, "crosstab" => false, "ordered_list" => false, "suggest" => false);

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_bUseAssocLabel = true;

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_bUseMulti = false;

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_iHeightThumbnail;

    /**
     * @access private
     *
     * @var mixed 
     */
    public $_sDefaultFocus = ""; // variable permettant de définir le focus sur le premier input disponible du formulaire


    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sEditorCss = "";

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sEditorHeight = "0";

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sEditorWidth = "0";

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sFormDisposition = "horizontal";

    /**
     * @access private
     *
     * @var mixed 
     */
    public $_sJS = "";

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sRootAbsPath = "";

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sTablePrefix = "";

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sTableSuffixeId = "";

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sTableSuffixeLabel = "";

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sThumbnailAbsPath = "";

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sUploadVar = "";

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sUploadHttpPath = "";

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sLibPath = "";

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sRequiredIndicator = "&nbsp;*";

    /**
     * @access private
     *
     * @var mixed 
     */
    public $_inputName = array();

    /**
     * @access public
     *
     * @var mixed 
     */
    public $suggest;

    /**
     * @access public
     *
     * @var mixed 
     */
    public $aEditor;

    /**
     * @access public
     *
     * @var mixed 
     */
    public $displayTab;

    /**
     * @access public
     *
     * @var mixed 
     */
    public $endJs;

    /**
     * @access public
     *
     * @var mixed 
     */
    public $currentTabId;

    /**
     * @access public
     *
     * @var mixed 
     */
    public $activatedTab;

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $_sLibForm;

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $sLibOther;

    /**
     * @access private
     *
     * @var mixed 
     */
    public $_sRootLibPath;

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $bVirtualKeyboard;

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $tinymce;

    /**
     * @access protected
     *
     * @var mixed 
     */
    protected $map;

    /**
     * @access private
     *
     * @var mixed 
     */
    public $_aMultiTrackNames = array();

    /**
     * Constructeur du formulaire.
     *
     * @access public
     *
     * @param bool   $bDirectOutput    (option) True pour un affichage direct, false pour
     *                                 que les méthodes retournent le code Pelican_Html sous forme de texte
     * @param string $sFormDisposition (option) Disposition des couples
     *                                 libellé<->valeur ("vertical" ou "horizontal")
     * @param string $sStyleLib        (option) Nom de la classe de feuille de style pour les
     *                                 libellés de formulaire
     * @param string $sStyleVal        (option) Nom de la classe de feuille de style pour les
     *                                 valeurs de formulaire
     *
     * @return Pelican_Form
     */
    public function __construct($bDirectOutput = true, $sFormDisposition = "horizontal", $sStyleLib = "formlib", $sStyleVal = "formval")
    {
        global $_EDITOR;
        include_once "editor.ini.php";
        $this->bDirectOutput = $bDirectOutput;
        $this->sStyleLib = $sStyleLib;
        $this->sStyleVal = $sStyleVal;
        $this->_sFormDisposition = $sFormDisposition;
        $this->_sRootAbsPath = Pelican::$config['DOCUMENT_ROOT'];
        $this->_sLibPath = Pelican::$config["LIB_PATH"];
        $this->_sLibForm = Pelican::$config['LIB_FORM'];
        $this->sLibOther = Pelican::$config['LIB_OTHER'];
        $this->_sRootLibPath = Pelican::$config['LIB_ROOT'];
        $this->_sTableSuffixeId = Pelican::$config["FW_SUFFIXE_ID"];
        $this->_sTableSuffixeLabel = Pelican::$config["FW_SUFFIXE_LIBELLE"];
        $this->_sTablePrefix = Pelican::$config['FW_PREFIXE_TABLE'];
        $this->_sUploadHttpPath = Pelican::$config["MEDIA_HTTP"];
        $this->_sUploadVar = Pelican::$config["MEDIA_VAR"];
        $this->_sThumbnailAbsPath = (!empty(Pelican::$config["THUMBNAIL_PATH"]) ? Pelican::$config["THUMBNAIL_PATH"] : '');
        $this->_sEditorCss = $_EDITOR["CSS"];
        $this->_iHeightThumbnail = (!empty(Pelican::$config["IMG_HEIGHT_THUMBNAIL"]) ? Pelican::$config["IMG_HEIGHT_THUMBNAIL"] : "50");
        $this->_sEditorWidth = (!empty(Pelican::$config["FW_EDITOR_WIDTH"]) ? Pelican::$config["FW_EDITOR_WIDTH"] : "600");
        $this->_sEditorHeight = (!empty(Pelican::$config["FW_EDITOR_HEIGHT"]) ? Pelican::$config["FW_EDITOR_HEIGHT"] : "400");
    }

    /**
     * .
     *
     * @access public
     *
     * @param mixed $value 
     *
     * @return mixed
     */
    public function output($value)
    {
        if ($this->bDirectOutput) {
            print($value);

            return "";
        } else {
            return $value;
        }
    }

    /**
     * Insertion un saut de ligne dans la formulaire si la disposition choisie est
     * "vertical".
     *
     * @access public
     *
     * @return string
     */
    public function getDisposition()
    {
        switch ($this->_sFormDisposition) {
            case "vertical": {
                        $strTmp = "</tr>\n<tr>";
                    break;
                }
            default: {
                    $strTmp = "";
                    break;
                }
            }

        return $strTmp;
    }

        /**
         * Déclaration du formulaire.
         *
         * @access public
         *
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
         * @param bool $bVirtualKeyboard (option) 
         *
         * @return string
         */
        public function open($strAction = "", $strMethod = "post", $strName = "fForm", $bUpload = false, $bCheck = true, $sCheckFunction = "CheckForm", $sTarget = "", $bBlockSubmit = true, $bVirtualKeyboard = true)
        {
            //debug(debug_backtrace());
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
         * Remplacement du echo
         * Rajout Charles Teinturier 26/07/2010.
         *
         * @access public
         *
         * @param string $strString 
         *
         * @return mixed
         */
        public function createFreeHtml($strString)
        {
            return $this->output($strString);
        }

        /**
         * Compatibilité avec Form2
         * fonction de vérification du formulaire.
         *
         * @access public
         *
         * @param string $aData (option) 
         *
         * @return mixed
         */
        public function isValid($aData = null)
        {
            return true;
        }

        /**
         * Génère un champ de saisie de mot de passe.
         *
         * @access public
         *
         * @param string $strName Nom du champ
         * @param string $strLib Libellé du champ
         * @param string $iMaxLength (option) Nb de caractères maximum : 255 par défaut
         * @param bool $bRequired (option) Champ obligatoire : false par défaut
         * @param string $strValue (option) Valeur du champ
         * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
         * (créé un input hidden) : false par défaut
         * @param string $iSize (option) Taille d'affichage du champ : 10 par défaut
         * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
         * : false par défaut
         * @param string $strEvent (option) Handler d'événements sur le champ : "" par
         * défaut
         *
         * @return string
         */
        public function createPassword($strName, $strLib, $iMaxLength = "255", $bRequired = false, $strValue = "", $bReadOnly = false, $iSize = "10", $bFormOnly = false, $strEvent = "")
        {
            return $this->createInput($strName, $strLib, $iMaxLength, "", $bRequired, $strValue, $bReadOnly, $iSize, $bFormOnly, $strEvent, "password");
        }

        /**
         * Génère un champ parcourir de type file.
         *
         * @access public
         *
         * @param string $strName Nom du champ
         * @param string $strLib Libellé du champ
         * @param string $iMaxLength (option) Nb de caractères maximum : 255 par défaut
         * @param bool $bRequired (option) Champ obligatoire : false par défaut
         * @param string $strValue (option) Valeur du champ
         * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
         * (créé un input hidden) : false par défaut
         * @param string $iSize (option) Taille d'affichage du champ : 10 par défaut
         * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
         * : false par défaut
         * @param string $strEvent (option) Handler d'événements sur le champ : "" par
         * défaut
         * @param bool $multiple (option) 
         *
         * @return string
         */
        public function createBrowse($strName, $strLib, $iMaxLength = "255", $bRequired = false, $strValue = "", $bReadOnly = false, $iSize = "10", $bFormOnly = false, $strEvent = "", $multiple = false)
        {
            return $this->createInput($strName, $strLib, $iMaxLength, "", $bRequired, $strValue, $bReadOnly, $iSize, $bFormOnly, $strEvent, "file", array(), $multiple);
        }

        /**
         * Génère un champ de saisie de mot de passe.
         *
         * @access public
         *
         * @param string $strName Nom du champ
         * @param string $strLib Libellé du champ
         * @param bool $bRequired (option) Champ obligatoire : false par défaut
         * @param string $strValue (option) Valeur du champ
         * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
         * (créé un input hidden) : false par défaut
         * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
         * : false par défaut
         * @param string $strEvent (option) Handler d'événements sur le champ : "" par
         * défaut
         *
         * @return string
         */
        public function createDateTime($strName, $strLib, $bRequired = false, $strValue = "", $bReadOnly = false, $bFormOnly = false, $strEvent = "")
        {

            /* Sauvegarde de la valeur de bDirectOutput et désactivation temporaire */
            $directOutput = $this->bDirectOutput;
            $this->bDirectOutput = false;
            $tabDateHeure = explode(" ", $strValue);
            if (is_array($tabDateHeure)) {
                $strValueDate = $tabDateHeure[0];
                $strValueHeure = substr($tabDateHeure[1], 0, 5);
            } else {
                $strValueDate = $tabDateHeure[0];
                $strValueHeure = "00:00";
            }

            /* Création d'un hidden dont le nom correspond au champ updaté en base */

            /* La valeur de ce hidden est mise à jour par javascript avant submit */
            $strTmp .= $this->createHidden($strName, $strValue);
            $strTmp .= $this->createInput($strName."_DATE", $strLib, 10, "date", $bRequired, $strValueDate, $bReadOnly, 10, true, $strEvent);
            $strTmp .= Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp();
            $strTmp .= $this->createInput($strName."_HEURE", $strLib, 5, "heure", $bRequired, $strValueHeure, $bReadOnly, 5, true, $strEvent);
            if (!$bFormOnly) {
                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "", "", $this->_sFormDisposition);
            }

            /* Ajout du traitement au moment du submit */
            $this->createJS(" if (obj.".$strName."_HEURE.value) {\r\n
				obj.".$strName.".value = obj.".$strName."_DATE.value + \" \" + obj.".$strName."_HEURE.value + ':00';
				} else {
				obj.".$strName.".value = obj.".$strName."_DATE.value + \" \" + '00:00:00';
				}
				if (obj.".$strName.".value == ' 00:00:00') {
				obj.".$strName.".value = '';
				}
				");
            $this->bDirectOutput = $directOutput;

            return $this->output($strTmp);
        }

        /**
         * .
         *
         * @access public
         *
         * @param string $strName 
         * @param string $strLib 
         * @param bool $bRequired (option) 
         * @param string $strValue (option) 
         * @param string $strLabel (option) 
         * @param string $strDivContent (option) 
         * @param bool $bReadOnly (option) 
         * @param bool $bFormOnly (option) 
         *
         * @return mixed
         */
        public function createDiv($strName, $strLib, $bRequired = false, $strValue = "", $strLabel = "&nbsp;", $strDivContent = "", $bReadOnly = false, $bFormOnly = false)
        {

            /* Sauvegarde de la valeur de bDirectOutput et désactivation temporaire */
            $directOutput = $this->bDirectOutput;
            $this->bDirectOutput = false;

            /* Création d'un hidden dont le nom correspond au champ updaté en base */
            $strTmp = $this->createHidden($strName, $strValue);
            if (!$bReadOnly) {
                $strTmp .= Pelican_Html::img(array(id => $strName."_IMG", src => $this->_sLibPath.$this->_sLibForm."/images/combo.gif", alt => "", border => "0", onclick => "showInputDiv('".$strName."');", style => "float:left;"));
            }
            $strTmp .= Pelican_Html::span(array(id => $strName."_LABEL", style => "float:left;font-weight:bold;margin-left:10px;"), $strLabel);
            if (!$bReadOnly) {
                $strTmp .= Pelican_Html::div(array(id => $strName."_DIV", "class" => "inputdiv", style => "z-index:150;display:none;"), $strDivContent);
            }
            if (!$bFormOnly) {
                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "", "", $this->_sFormDisposition);
            }
            if (!$bReadOnly && $bRequired) {
                $this->_aIncludes["text"] = true;
                $this->_sJS .= "if ( isBlank(obj.".$strName.".value) ) {\n";
                $this->_sJS .= "alert(\"".t('FORM_MSG_VALUE_REQUIRE')." "."\\"."\"".strip_tags(str_replace("\"", ""."\\"."\"", $strLib)).""."\\"."\".\");\n";
                $this->_sJS .= "return false;\n";
                $this->_sJS .= "}\n";
            }
            $this->bDirectOutput = $directOutput;

            return $this->output($strTmp);
        }

        /**
         * Génère un champ input de type Text.
         *
         * @access public
         *
         * @example Création d'un champ input présentant le nom d'un utilisateur :
         *
         * @param string $strName Nom du champ
         * @param string $strLib Libellé du champ
         * @param string $iMaxLength (option) Nb de caractères maximum : 255 par défaut
         * @param string $strControl (option) Type de contrôle js utilisé : numerique ou
         * number, float, flottant, real ou reel, telephone, mail, date
         * @param bool $bRequired (option) Champ obligatoire : false par défaut
         * @param string $strValue (option) Valeur du champ
         * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
         * (créé un input hidden) : false par défaut
         * @param string $iSize (option) Taille d'affichage du champ : 10 par défaut
         * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
         * : false par défaut
         * @param string $strEvent (option) Handler d'événements sur le champ : "" par
         * défaut
         * @param string $strType (option) Type de l'input ("text" par défaut)
         * @param mixed $aSuggest (option) 
         * @param bool $multiple (option) 
         *
         * @return string
         */
        public function createInput($strName, $strLib, $iMaxLength = "255", $strControl = "", $bRequired = false, $strValue = "", $bReadOnly = false, $iSize = "10", $bFormOnly = false, $strEvent = "", $strType = "text", $aSuggest = array(), $multiple = false)
        {

            /* initialisation */
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
                $add .= $this->createHidden($strName, str_replace("\"", "&quot;", $strValue));
                $strTmp = $val.$add;
            } else {
                if (!$this->_sDefaultFocus) {
                    $this->_sDefaultFocus = $strName;
                }

                    /* Ajouts */
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
                                    $add .= Pelican_Html_Form::imgComment($this->_sLibPath.$this->_sLibForm."/images/mail.gif", "mailto:".$strValue);
                                }
                                break;
                            }
                        case "internallink": {
                                $this->_aIncludes["popup"] = true;
                                $add .= Pelican_Html_Form::imgComment($this->_sLibPath.$this->_sLibForm."/images/internal_link.gif", "", "return popupInternalLink(document.".$this->sFormName.".".$strName.")", t('EDITOR_INTERNAL'));
                                break;
                            }
                        case "shortdate":
                        case "date":
                        case "calendar": {
                                $class .= " datepicker";
                                ////$add .= Pelican_Html_Form::imgComment($this->_sLibPath . $this->_sLibForm . "/images/cal.gif", "javascript://", "popUpCalendar(this, " . $this->sFormName . "." . $strName . ")", "");
                                break;
                            }
                        case "date_edition": {
                                $add .= Pelican_Html_Form::comment("(".t('DATE_FORMAT_LABEL_EDITION').")");
                                break;
                            }
                        case "heure": {
                                $add .= Pelican_Html_Form::comment("(".t('HOUR_FORMAT_LABEL').")");
                                break;
                            }
                        case "color": {
                                $class .= " colors";
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
                    $strTmp .= '<br />'.t('POPUP_MEDIA_LABEL_NEW_FILE_COMMENT');
                }
                $strTmp = Pelican_Html_Form::addInputEvent($strTmp, $strEvent);
                $strTmp .= $add;
                        // Génération de la fonction js de vérification.
                        if ($bRequired || ($strControl != "" && $strControl != "color" && $strControl != "internallink")) {
                            /*if ($bRequired) {
                            $this->_aIncludes["text"] = true;
                            $this->_sJS .= "if ( isBlank(obj." . $strName . ".value) ) {\n";
                            $this->_sJS .= "alert(\"" . t ( 'FORM_MSG_VALUE_REQUIRE' ) . " " . "\\" . "\"" . strtolower ( str_replace ( "\"", "" . "\\" . "\"", $strLib ) ) . "" . "\\" . "\".\");\n";
                            $this->_sJS .= "fwFocus(obj." . $strName . ");\n";
                            $this->_sJS .= "return false;\n";
                            $this->_sJS .= "}\n";
                            }*/
                            $this->_aIncludes["text"] = true;
                            $this->_sJS .= "if ( ";
                            if (!$bRequired) {
                                // Si le champ n'est pas requis, ne faire la vérification que si le champ n'est pas vide.
                                $this->_sJS .= "!isBlank(obj.".$strName.".value) ";
                            }
                            if ($strControl != "" && $strControl != "color" && $strControl != "internallink") {
                                if (!$bRequired) {
                                    $this->_sJS .= "&& ";
                                }
                                switch ($strControl) {
                                    case "alphanum": {
                                                $this->_sJS .= "!isAlphaNum(obj.".$strName.".value)";
                                                $this->_aIncludes["text"] = true;
                                                $strMessage = t('FORM_MSG_ALPHANUM');
                                            break;
                                        }
                                    case "numerique":
                                    case "number": {
                                            $this->_sJS .= "!isNumeric(obj.".$strName.".value)";
                                            $this->_aIncludes["num"] = true;
                                            $strMessage = t('FORM_MSG_NUMBER');
                                            break;
                                        }
                                    case "float":
                                    case "flottant":
                                    case "real":
                                    case "reel": {
                                            $this->_sJS .= "!isFloat(obj.".$strName.".value)";
                                            $this->_aIncludes["num"] = true;
                                            $strMessage = t('FORM_MSG_REAL');
                                            break;
                                        }
                                    case "telephone": {
                                            $this->_sJS .= "!isTel(obj.".$strName.".value)";
                                            $this->_aIncludes["num"] = true;
                                            $strMessage = t('FORM_MSG_TELEPHONE');
                                            break;
                                        }
                                    case "mail": {
                                            $this->_sJS .= "!isMail(obj.".$strName.".value)";
                                            $this->_aIncludes["text"] = true;
                                            $strMessage = t('FORM_MSG_MAIL');
                                            break;
                                        }
                                    case "URL": {
                                            $this->_sJS .= "!isURL(obj.".$strName.".value)";
                                            $this->_aIncludes["text"] = true;
                                            $strMessage = t('FORM_MSG_URL');
                                            break;
                                        }
                                    case "login": {
                                            $this->_sJS .= "!isLogin(obj.".$strName.".value)";
                                            $this->_aIncludes["text"] = true;
                                            $strMessage = t('FORM_MSG_LOGIN');
                                            break;
                                        }
                                    case "dateNF":
                                    case "shortdate":
                                    case "date":
                                    case "calendar": {
                                            $this->_sJS .= "!isDate(obj.".$strName.".value)";
                                            $this->_aIncludes["date"] = true;
                                            $strMessage = t('FORM_MSG_DATE');
                                            break;
                                        }
                                    case "date_edition": {
                                            $this->_sJS .= "!isDate_edition(obj.".$strName.".value)";
                                            $this->_aIncludes["date"] = true;
                                            $strMessage = t('FORM_MSG_DATE_EDITION');
                                            break;
                                        }
                                    case "year": {
                                            $this->_sJS .= "!isNumeric(obj.".$strName.".value && obj.".$strName.".value.length == 4)";
                                            $this->_aIncludes["num"] = true;
                                            $strMessage = t('FORM_MSG_YEAR');
                                            break;
                                        }
                                    case "heure": {
                                            $this->_sJS .= "!isHour(obj.".$strName.".value)";
                                            $this->_aIncludes["date"] = true;
                                            $strMessage = t('FORM_MSG_HEURE');
                                            break;
                                        }
                                    }
                            } else {
                                $this->_aIncludes["text"] = true;
                                $this->_sJS .= "isBlank(obj.".$strName.".value) ";
                            }
                            $this->_sJS .= ") {\n";
                            $this->_sJS .= "alert(\"".t('FORM_MSG_VALUE_REQUIRE')." \\"."\"".(strip_tags(str_replace("\"", "\\"."\"", $strLib)))."\\"."\"";
                            if ($strControl != "" && $strControl != "color" && $strControl != "internallink") {
                                $this->_sJS .= " ".t('FORM_MSG_WITH')." ".$strMessage;
                            }
                            $this->_sJS .= ".\");\n";
                            $this->_sJS .= "fwFocus(obj.".$strName.");\n";
                            $this->_sJS .= "return false;\n";
                            $this->_sJS .= "}\n";
                        }
            }
            if (!$bFormOnly) {
                // $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "", "", $this->_sFormDisposition);
                            $strTmp = Pelican_Html_Form::get($strLib, $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "", "", $this->_sFormDisposition);
            }

            return $this->output($strTmp);
        }

                    /**
                     * Retourne les valeurs de la table $strTableName=>$aDataValues et les valeurs
                     * sélectionnées de la table $strRefTableName=>$aSelectedValues.
                     *
                     * @access private
                     *
                     * @param mixed $deprecated 
                     * @param string $strTableName Nom de la table pour les valeurs sans
                     * $this->_sTablePrefix : "" par défaut
                     * @param string $strRefTableName Nom de la table de jointure où trouver les
                     * valeurs sélectionnées : "" par défaut
                     * @param string $iID Id auquel sont associées les valeurs sélectionnées : ""
                     * par défaut
                     * @param mixed $aDataValues Tableau de valeurs (id=>lib)
                     * @param mixed $aSelectedValues Tableau des valeurs sélectionnées (liste des
                     * id)
                     * @param string $strColRefTableName (option) Nom de la colonne dans la table de
                     * référence correspondant à $iID : "CONTENU_ID" par défaut
                     * @param string $strOrderColName (option) 
                     * @param string $iSiteId (option) 
                     */
                    public function _getValues($deprecated, $strTableName = "", $strRefTableName = "", $iID = "", &$aDataValues, &$aSelectedValues, $strColRefTableName = "contenu_id", $strOrderColName = '', $iSiteId = '')
                    {
                        $oConnection = Pelican_Db::getInstance();
                        $strSQL = "select ".$strTableName.$this->_sTableSuffixeId." as \"id\", ".$strTableName.$this->_sTableSuffixeLabel." as \"lib\" from ".$this->_sTablePrefix.$strTableName;
                        if ($iSiteId != '') {
                            $strSQL .= " where SITE_ID =".$iSiteId;
                        }
                        $strSQL .= " order by \"lib\"";
                        $oConnection->Query($strSQL);
                        $aDataValues = array();
                        if ($oConnection->data) {
                            while ($ligne = each($oConnection->data["id"])) {
                                $aDataValues[$ligne["value"]] = $oConnection->data["lib"][$ligne["key"]];
                            }
                        }
                        $aSelectedValues = array();
                        if (($strRefTableName != "") && ($iID != "")) {
                            $strSQL = "select ".$strTableName.$this->_sTableSuffixeId." as \"id\" from ".$strRefTableName." where ".$strColRefTableName." = ".$iID;
                            if ($strOrderColName != "") {
                                $strSQL .= " order by ".$strOrderColName;
                            }
                            $oConnection->Query($strSQL);
                            if ($oConnection->data) {
                                while ($ligne = each($oConnection->data["id"])) {
                                    $aSelectedValues[count($aSelectedValues) ] = $ligne["value"].(($strTableName == "SECTEUR") ? " " : "");
                                }
                            }
                        }
                    }

                    /**
                     * Retourne les valeurs de la table $strTableName=>$aDataValues.
                     *
                     * @access private
                     *
                     * @param string $strSQL Chaine SQL
                     * @param mixed $aDataValues Tableau de valeurs (id=>lib)
                     * @param array $aBind (option)
                     */
                    public function _getValuesFromSQL($strSQL, &$aDataValues, $aBind = array())
                    {
                        $oConnection = Pelican_Db::getInstance();
                        $result = $oConnection->queryTab($strSQL, $aBind);
                        $aDataValues = array();
                        if (isset($result)) {
                            foreach ($result as $valeur) {
                                $keys = array_keys($valeur);
                                if (in_array(0, $keys) && in_array(1, $keys)) {
                                    $keys = array(0, 1);
                                }
                                $aDataValues[$valeur[$keys[0]]] = $valeur[$keys[1]];
                            }
                        }
                    }

                    /**
                     * Retourne les valeurs de la table $strTableName=>$aDataValues regroupées sur le
                     * tableau $GroupField.
                     *
                     * @access private
                     *
                     * @param Pelican_Db $oConnection Objet connection à la base
                     * @param string $strSQL Chaine SQL
                     * @param mixed $aDataValues Tableau de valeurs (id=>lib)
                     * @param mixed $GroupField Tableau de champs de regroupements
                     * @param string $strSep (option) Chaine de répétition pour marquer
                     * l'indentation des groupes
                     * @param mixed $aBind (option) 
                     */
                    public function _getGroupValuesFromSQL($oConnection, $strSQL, &$aDataValues, $GroupField, $strSep = "&nbsp;&nbsp;", $aBind = array())
                    {
                        if (!is_array($GroupField)) {
                            $GroupField = array("0" => $GroupField);
                        }
                        $old = array();
                        $aDataValues = array();
                        $result = $oConnection->queryTab($strSQL, $aBind);
                        if (isset($result)) {
                            foreach ($result as $valeur) {
                                $keys = array_keys($valeur);
                                if (in_array(0, $keys) && in_array(1, $keys)) {
                                    $keys = array(0, 1);
                                }
                                for ($i = 0;$i < count($GroupField);$i++) {
                                    if ($old[$i] != $valeur[$GroupField[$i]]) {
                                        $j++;
                                        $old[$i] = $valeur[$GroupField[$i]];
                                        $aDataValues["delete_".$j] = str_repeat($strSep, $i).$valeur[$GroupField[$i]];
                                    }
                                }
                                $aDataValues[$valeur[$keys[0]]] = str_repeat($strSep, count($GroupField)).$valeur[$keys[1]];
                            }
                        }
                    }
                    ////DOC EN COURS


                    /**
                     * Génère un contrôle de type liste.
                     *
                     * @access private
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param mixed $aDataValues Tableau de valeurs (id=>lib)
                     * @param mixed $aCheckedValues Liste des valeurs cochées (liste des id)
                     * @param bool $bRequired Champ obligatoire
                     * @param bool $bReadOnly Affiche uniquement la valeur et pas le champ (créé un
                     * input hidden)
                     * @param string $cOrientation Orientation h=horizontal, v=vertical
                     * @param string $strType 
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     * @param string $strEvent (option) Handler d'événements sur le champ : "" par
                     * défaut
                     *
                     * @return string
                     */
                    public function _createBox($strName, $strLib, $aDataValues, $aCheckedValues, $bRequired, $bReadOnly, $cOrientation, $strType, $bFormOnly = false, $strEvent = "")
                    {
                        $strTmp = "";
                        if (!is_array($aCheckedValues)) {
                            $aCheckedValues = array(1 => $aCheckedValues);
                        }
                        if (!is_array($aDataValues)) {
                            $aDataValues = array(1 => $aDataValues);
                        }
                        $strFieldName = $strName;
                        if (($strType == "checkbox") && (count($aDataValues) > 1)) {
                            $strFieldName .= "[]";
                        }
                        // Génération du couple libellé/champ
                        if ($bReadOnly) {
                            if ($aCheckedValues == "") {
                                $strTmp .= $this->createHidden($strFieldName, "0");
                            } else {
                                while ($ligne = each($aCheckedValues)) {
                                    $strTmp .= $this->createHidden($strFieldName, str_replace("\"", "&quot;", $ligne["value"]));
                                }
                            }
                        }
                        if (is_array($aDataValues)) {
                            if ($bReadOnly) {
                                while ($ligne = each($aDataValues)) {
                                    if (in_array($ligne["key"], $aCheckedValues)) {
                                        if ($ligne["value"] == "") {
                                            $strTmp .= " ".t('FORM_MSG_YES')." ";
                                        } else {
                                            $strTmp .= $ligne["value"]." ";
                                        }
                                        if ($cOrientation == "v") {
                                            $strTmp .= Pelican_Html::br();
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
                                    $strTmp .= Pelican_Html_Form::addInputEvent(Pelican_Html::input($params), $strEvent);
                                    $strTmp .= Pelican_Html::nbsp().$ligne["value"];
                                    if ($cOrientation == "v") {
                                        $strTmp .= Pelican_Html::br();
                                    }
                                }
                            }
                        }
                        if (!$bFormOnly) {
                            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
                        }
                        // Génération de la fonction js de vérification.
                        if (!$bReadOnly && is_array($aDataValues) && $bRequired) {
                            if (($strType == "checkbox") && (count($aDataValues) > 1)) {
                                $this->_sJS .= "o = obj.elements[\"".$strName."[]\"];\n";
                            } else {
                                $this->_sJS .= "o = obj.".$strName.";\n";
                            }
                            if (count($aDataValues) > 1) {
                                $this->_sJS .= "bChecked = false;\n";
                                $this->_sJS .= "for (i=0; i < o.length; i++)\n";
                                $this->_sJS .= "if ( o[i].checked )\n";
                                $this->_sJS .= "bChecked = true;\n";
                                $this->_sJS .= "if (!bChecked ) {\n";
                            } else {
                                $this->_sJS .= "if (!o.checked ) {\n";
                            }
                            $this->_sJS .= "alert(\"".t('FORM_MSG_VALUE_CHOOSE')." "."\\"."\"".strip_tags(str_replace("\"", ""."\\"."\"", $strLib)).""."\\"."\"\");\n";
                            $this->_sJS .= "return false;\n";
                            $this->_sJS .= "}\n";
                        }

                        return $this->output($strTmp);
                    }

                    /**
                     * Génère des checkbox à partir d'une série de valeurs.
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param mixed $aDataValues (option) Tableau de valeurs (id=>lib) : "" par
                     * défaut
                     * @param mixed $aCheckedValues (option) Liste des valeurs cochées (liste des id)
                     * : "" par défaut
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param string $cOrientation (option) Orientation h=horizontal, v=vertical : "h"
                     * par défaut
                     * @param bool $bFormOnly (option) 
                     * @param string $strEvent (option) 
                     *
                     * @return string
                     */
                    public function createCheckBoxFromList($strName, $strLib, $aDataValues = "", $aCheckedValues = "", $bRequired = false, $bReadOnly = false, $cOrientation = "h", $bFormOnly = false, $strEvent = "")
                    {
                        return $this->_createBox($strName, $strLib, $aDataValues, $aCheckedValues, $bRequired, $bReadOnly, $cOrientation, "checkbox", $bFormOnly, $strEvent);
                    }

                    /**
                     * Génère des checkbox à partir d'une table.
                     *
                     * @access public
                     *
                     * @param Pelican_Db $oConnection Objet connection à la base
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param string $strTableName 
                     * @param string $strRefTableName (option) Nom de la table de jointure où trouver
                     * les valeurs sélectionnées : "" par défaut
                     * @param string $iID (option) 
                     * @param mixed $aCheckedValues (option) Liste des valeurs cochées (liste des id)
                     * : "" par défaut
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param string $cOrientation (option) Orientation h=horizontal, v=vertical : "h"
                     * par défaut
                     * @param bool $bFormOnly (option) 
                     * @param string $strEvent (option) 
                     * @param string $strColRefTableName (option) 
                     *
                     * @return string
                     */
                    public function createCheckBox($oConnection, $strName, $strLib, $strTableName, $strRefTableName = "", $iID = 0, $aCheckedValues = "", $bRequired = false, $bReadOnly = false, $cOrientation = "h", $bFormOnly = false, $strEvent = "", $strColRefTableName = "contenu_id")
                    {
                        $aDataValues = array();
                        $aSelectedValues = array();
                        $this->_getValues($oConnection, $strTableName, $strRefTableName, $iID, $aDataValues, $aSelectedValues, $strColRefTableName);
                        if ($aCheckedValues != "") {
                            $aSelectedValues = $aCheckedValues;
                        }

                        return $this->_createBox($strName, $strLib, $aDataValues, $aSelectedValues, $bRequired, $bReadOnly, $cOrientation, "checkbox", $bFormOnly, $strEvent);
                    }

                    /**
                     * Génère des radio à partir d'une série de valeurs.
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param mixed $aDataValues (option) Tableau de valeurs (id=>lib) : "" par
                     * défaut
                     * @param string $aValue (option) Valeur cochée : "" par défaut
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param string $cOrientation (option) Orientation h=horizontal, v=vertical : "h"
                     * par défaut
                     * @param bool $bFormOnly (option) 
                     * @param string $strEvent (option) 
                     *
                     * @return string
                     */
                    public function createRadioFromList($strName, $strLib, $aDataValues = "", $aValue = "", $bRequired = false, $bReadOnly = false, $cOrientation = "h", $bFormOnly = false, $strEvent = "")
                    {
                        return $this->_createBox($strName, $strLib, $aDataValues, $aValue, $bRequired, $bReadOnly, $cOrientation, "radio", $bFormOnly, $strEvent);
                    }

                    /**
                     * Génère des radio à partir d'une table.
                     *
                     * @access public
                     *
                     * @param Pelican_Db $oConnection Objet connection à la base
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param string $strTableName Nom de la table pour les valeurs sans
                     * $this->_sTablePrefix
                     * @param mixed $aValue 
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param string $cOrientation (option) Orientation h=horizontal, v=vertical : "h"
                     * par défaut
                     * @param bool $bFormOnly (option) 
                     * @param string $strEvent (option) 
                     *
                     * @return string
                     */
                    public function createRadio($oConnection, $strName, $strLib, $strTableName, $aValue, $bRequired = false, $bReadOnly = false, $cOrientation = "h", $bFormOnly = false, $strEvent = "")
                    {
                        $aDataValues = array();
                        $NotUsed = array();
                        $this->_getValues($oConnection, $strTableName, "", "", $aDataValues, $NotUsed);

                        return $this->_createBox($strName, $strLib, $aDataValues, $aValue, $bRequired, $bReadOnly, $cOrientation, "radio", $bFormOnly, $strEvent);
                    }

                    /**
                     * Génère un contrôle de type combo.
                     *
                     * @access private
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param mixed $aDataValues Tableau de valeurs (id=>lib)
                     * @param mixed $aSelectedValues Tableau des valeurs sélectionnées (liste des
                     * id)
                     * @param bool $bRequired Champ obligatoire
                     * @param bool $bReadOnly Affiche uniquement la valeur et pas le champ (créé un
                     * input hidden)
                     * @param mixed $iSize 
                     * @param bool $bMultiple Sélection multiple
                     * @param string $iWidth Largeur du contrôle
                     * @param bool $bChoisissez Affiche le message "->Choisissez" en début de liste
                     * @param bool $bEnableManagement (option) Accès à la popup d'ajout dans la
                     * table de référence : false par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     * @param string $strTableName (option) Nom de la table pour les valeurs sans
                     * $this->_sTablePrefix : "" par défaut
                     * @param string $strEvent (option) événement et fonction javascript "" par
                     * défaut. ex : onChange="javascript:functionAExecuter();"
                     * @param string $sSearchQueryName (option) Nom de la variable de session
                     * contenant la requête pour filtrer la combo Dans ce cas, une
                     * Pelican_Index_Frontoffice_Zone de saisie avec bouton de recherche s'affiche à
                     * droite.
                     * @param bool $bDelManagement (option) 
                     * @param bool $bUpdManagement (option) 
                     *
                     * @return string
                     */
                    public function _createCombo($strName, $strLib, $aDataValues, $aSelectedValues, $bRequired, $bReadOnly, $iSize, $bMultiple, $iWidth, $bChoisissez, $bEnableManagement = false, $bFormOnly = false, $strTableName = "", $strEvent = "", $sSearchQueryName = "", $bDelManagement = false, $bUpdManagement = false)
                    {
                        $strTmp = "";
                        if (!is_array($aSelectedValues)) {
                            $aSelectedValues = array($aSelectedValues);
                        }
                        $strFieldName = $strName;
                        if ($bMultiple) {
                            $strFieldName .= "[]";
                        }
                        if ($bReadOnly) {
                            $this->countInputName($strFieldName);
                            while ($ligne = each($aSelectedValues)) {
                                $params = array();
                                $params[type] = "hidden";
                                $params[name] = $strFieldName.($bMultiple ? "[]" : "");
                                $params[value] = str_replace("\"", "&quot;", $ligne["value"]);
                                $strTmp .= Pelican_Html::input($params);
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
                            $strTmp .= Pelican_Html_Form::addInputEvent(Pelican_Html::select($params, @implode("", $aOptions)), $strEvent, "select");
                            if ($sSearchQueryName) {
                                // Elements pour filtre de la combo
                                $strTmp .= Pelican_Html::input(array(type => "text", name => "iSearchVal".$strName, size => "14", onkeyDown => "submitIndexation('".$this->_sLibPath.$this->_sLibForm."/', '','".base64_encode($sSearchQueryName)."', true, ".($bChoisissez ? "true" : "false").");"));
                                $strTmp .= Pelican_Html::input(array(type => "button", "class" => "button", name => "bSearch".$strName, value => t('FORM_BUTTON_SEARCH'), onclick => "searchIndexation('".$this->_sLibPath.$this->_sLibForm."/', '".$strName."', '', eval('this.form.iSearchVal".$strName.".value'),'".base64_encode($this->sFormName."_".$strName)."',".($showAll ? 1 : 0).");")).Pelican_Html::br();
                            }
                        } else {
                            if (is_array($aDataValues)) {
                                foreach ($aDataValues as $key1 => $group) {
                                    if (is_array($group)) {
                                        foreach ($group as $key => $value) {
                                            if (in_array($key, $aSelectedValues)) {
                                                $strTmp .= $value.Pelican_Html::br();
                                            }
                                        }
                                    } else {
                                        if (in_array($key1, $aSelectedValues)) {
                                            $strTmp .= $group.Pelican_Html::br();
                                        }
                                    }
                                }
                            }
                        }
                        // Lien vers popup de gestion de la table de référence
                        if ($bEnableManagement && !$bReadOnly) {
                            $this->_aIncludes["popup"] = true;
                            $this->_aIncludes["list"] = true;
                            $strTmp .= " ".Pelican_Html::a(array(href => "javascript://", onclick => "addRef('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."','".$strName."', '".$strTableName."', 1, true, 'add');"), t('FORM_BUTTON_ADD_VALUE'));
                            if ($bUpdManagement && !$bReadOnly) {
                                $strTmp .= Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp();
                                $strTmp .= " ".Pelican_Html::a(array(href => "javascript://", onclick => "addRef('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."','".$strName."', '".$strTableName."', 1, true, 'upd');"), 'Update a value');
                            }
                            if ($bDelManagement && !$bReadOnly) {
                                $strTmp .= Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp();
                                $strTmp .= " ".Pelican_Html::a(array(href => "javascript://", onclick => "addRef('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."','".$strName."', '".$strTableName."', 1, true, 'del');"), 'Del a value');
                            }
                        }
                        if (!$bFormOnly) {
                            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, ((!$bChoisissez || $bMultiple) && !$bReadOnly ? "top" : ""), "", $this->_sFormDisposition);
                        }
                        // Génération de la fonction js de vérification.
                        if (!$bReadOnly && $bRequired) {
                            if ($bMultiple) {
                                $this->_sJS .= "var o = obj.elements[\"".$strName."[]\"];\n";
                            } else {
                                $this->_sJS .= "var o = obj.".$strName.";\n";
                            }
                            $this->_sJS .= "if ( (o.selectedIndex == 0) && (o.options[o.selectedIndex].value == \"\") ) {\n";
                            $this->_sJS .= "alert(\"".t('FORM_MSG_VALUE_CHOOSE')." "."\\"."\"".strip_tags(str_replace("\"", ""."\\"."\"", $strLib)).""."\\"."\"\");\n";
                            $this->_sJS .= "fwFocus(o);\n";
                            $this->_sJS .= "return false;\n";
                            $this->_sJS .= "}\n";
                        }

                        return $this->output($strTmp);
                    }

                    /**
                     * Génère une combo à partir d'une liste.
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param mixed $aDataValues (option) Tableau de valeurs (id=>lib) : par défaut
                     * @param string $aSelectedValues (option) 
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden)
                     * @param mixed $iSize (option) 
                     * @param bool $bMultiple (option) Sélection multiple : false par défaut
                     * @param string $iWidth (option) Largeur du contrôle : "" par défaut
                     * @param bool $bChoisissez (option) Affiche le message "->Choisissez" en début
                     * de liste : true par défaut
                     * @param bool $bFormOnly (option) 
                     * @param string $strEvent (option) 
                     *
                     * @return string
                     */
                    public function createComboFromList($strName, $strLib, $aDataValues = "", $aSelectedValues = "", $bRequired = false, $bReadOnly = false, $iSize = "1", $bMultiple = false, $iWidth = "", $bChoisissez = true, $bFormOnly = false, $strEvent = "")
                    {
                        return $this->_createCombo($strName, $strLib, $aDataValues, $aSelectedValues, $bRequired, $bReadOnly, $iSize, $bMultiple, $iWidth, $bChoisissez, false, $bFormOnly, "", $strEvent);
                    }

                    /**
                     * Génère une combo à partir d'une requête SQL.
                     *
                     * @access public
                     *
                     * @param Pelican_Db $oConnection Objet connection à la base
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param mixed $strSQL (option) Requête SQL (id,lib)
                     * @param string $aSelectedValues (option) 
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden)
                     * @param mixed $iSize (option) 
                     * @param bool $bMultiple (option) Sélection multiple : false par défaut
                     * @param string $iWidth (option) Largeur du contrôle : "" par défaut
                     * @param bool $bChoisissez (option) Affiche le message "->Choisissez" en début
                     * de liste : true par défaut
                     * @param bool $bFormOnly (option) Affiche uniquement les éléments du formulaire
                     * @param string $strEvent (option) 
                     * @param array $arSearchFields (option) Liste des champs sur lesquels effectuer
                     * une recherche par like 0 : nom complet du champ id dont la(les) valeur(s)
                     * sélectionnée(s) est(sont) dans $aSelectedValues suivants : champ(s) sur
                     * le(s)quel(s) doit s'effectuer la recherche Dans ce cas, la combo ne contient que
                     * la (les) valeur(s) sélectionnée(s) et une Pelican_Index_Frontoffice_Zone de
                     * saisie avec bouton de recherche s'affiche à droite.
                     * @param mixed $aBind (option) 
                     *
                     * @return string
                     */
                    public function createComboFromSql($oConnection, $strName, $strLib, $strSQL = "", $aSelectedValues = "", $bRequired = false, $bReadOnly = false, $iSize = "1", $bMultiple = false, $iWidth = "", $bChoisissez = true, $bFormOnly = false, $strEvent = "", $arSearchFields = "", $aBind = array())
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
                                    $sFilter .= ",";
                                }
                                $sFilter .= "'".str_replace("'", "''", $val)."'";
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
                            $this->_getValuesFromSQL($sFilter, $aDataValues, $aBind);
                            $sFilter = "";
                            while (list(, $val) = each($arSearchFields)) {
                                if (strlen($sFilter) != 0) {
                                    $sFilter .= " OR ";
                                }
                                $sFilter .= "UPPER(".$val.") like UPPER('%:RECHERCHE:%')";
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
                            $this->_getValuesFromSQL($strSQL, $aDataValues, $aBind);
                        }

                        return $this->_createCombo($strName, $strLib, $aDataValues, $aSelectedValues, $bRequired, $bReadOnly, $iSize, $bMultiple, $iWidth, $bChoisissez, false, $bFormOnly, "", $strEvent, $arSearchFields);
                    }

                    /**
                     * Génère une combo à partir d'une table.
                     *
                     * @access public
                     *
                     * @param Pelican_Db $oConnection Objet connection à la base
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param string $strTableName Nom de la table pour les valeurs sans
                     * $this->_sTablePrefix
                     * @param string $strRefTableName (option) Nom de la table de jointure où trouver
                     * les valeurs sélectionnées : "" par défaut
                     * @param string $iID (option) Id auquel sont associées les valeurs
                     * sélectionnées : 0 par défaut
                     * @param mixed $aSelectedValues (option) Tableau des valeurs sélectionnées
                     * (liste des id) : "" par défaut
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param string $iSize (option) Taille d'affichage de la liste : 1 par défaut
                     * @param bool $bMultiple (option) Sélection multiple : false par défaut
                     * @param string $iWidth (option) Largeur du contrôle : "" par défaut
                     * @param bool $bChoisissez (option) Affiche le message "->Choisissez" en début
                     * de liste : true par défaut
                     * @param bool $bEnableManagement (option) Accès à la popup d'ajout dans la
                     * table de référence : false par défaut
                     * @param bool $bFormOnly (option) 
                     * @param string $strEvent (option) 
                     *
                     * @return string
                     */
                    public function createCombo($oConnection, $strName, $strLib, $strTableName, $strRefTableName = "", $iID = 0, $aSelectedValues = "", $bRequired = false, $bReadOnly = false, $iSize = "1", $bMultiple = false, $iWidth = "", $bChoisissez = true, $bEnableManagement = false, $bFormOnly = false, $strEvent = "")
                    {
                        $aDataValues = array();
                        $aTmpSelectedValues = array();
                        $this->_getValues($oConnection, $strTableName, $strRefTableName, $iID, $aDataValues, $aTmpSelectedValues);
                        if ($aSelectedValues == "") {
                            $aSelectedValues = $aTmpSelectedValues;
                        }

                        return $this->_createCombo($strName, $strLib, $aDataValues, $aSelectedValues, $bRequired, $bReadOnly, $iSize, $bMultiple, $iWidth, $bChoisissez, $bEnableManagement, $bFormOnly, $strTableName, $strEvent);
                    }

                    /**
                     * Idem que createCombo mais avec jointure sur une table associée.
                     *
                     * @access public
                     *
                     * @param Pelican_Db $oConnection Objet connection à la base
                     * @param string $strComplement Suffixe à utiliser en complément du nom de table
                     * et pour le champ de jointure pour la table complémentaire
                     * @param string $strComplementValue Valeur du champ de jointure
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param string $strTableName Nom de la table pour les valeurs sans
                     * $this->_sTablePrefix
                     * @param string $strRefTableName (option) Nom de la table de jointure où trouver
                     * les valeurs sélectionnées : "" par défaut
                     * @param string $iID (option) Id auquel sont associées les valeurs
                     * sélectionnées : 0 par défaut
                     * @param mixed $aSelectedValues (option) Tableau des valeurs sélectionnées
                     * (liste des id) : "" par défaut
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param string $iSize (option) Taille d'affichage de la liste : 1 par défaut
                     * @param bool $bMultiple (option) Sélection multiple : false par défaut
                     * @param string $iWidth (option) Largeur du contrôle : "" par défaut
                     * @param bool $bChoisissez (option) Affiche le message "->Choisissez" en début
                     * de liste : true par défaut
                     * @param bool $bEnableManagement (option) Accès à la popup d'ajout dans la
                     * table de référence : false par défaut
                     * @param mixed $aBind (option) 
                     *
                     * @return string
                     */
                    public function createComboJoin($oConnection, $strComplement, $strComplementValue, $strName, $strLib, $strTableName, $strRefTableName = "", $iID = 0, $aSelectedValues = "", $bRequired = false, $bReadOnly = false, $iSize = "1", $bMultiple = false, $iWidth = "", $bChoisissez = true, $bEnableManagement = false, $aBind = array())
                    {
                        global $HTTP_SESSION_VARS;
                        $sql = " SELECT
				".$this->_sTablePrefix.$strTableName.".".$strTableName.$this->_sTableSuffixeId." id,
				".$strTableName.$this->_sTableSuffixeLabel." lib
				FROM
				".$this->_sTablePrefix.$strTableName.",
				".$this->_sTablePrefix.$strTableName."_".$strComplement."
				where ".$strComplement.$this->_sTableSuffixeId."='".$strComplementValue."'
				and ".$this->_sTablePrefix.$strTableName.".".$strTableName.$this->_sTableSuffixeId."=".$this->_sTablePrefix.$strTableName."_".$strComplement.".".$strTableName.$this->_sTableSuffixeId."
				ORDER BY lib";
                        $aDataValues = array();
                        $this->_getValuesFromSQL($sql, $aDataValues, $aBind);

                        return $this->createComboFromList($strName, $strLib, $aDataValues, $aSelectedValues, $bRequired, $bReadOnly, $iSize, $bMultiple, $iWidth, $bChoisissez);
                    }

                    /**
                     * Génère une association à partir d'une table.
                     *
                     * @access public
                     *
                     * @param Pelican_Db $oConnection Objet connection à la base
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param string $strTableName Nom de la table pour les valeurs sans
                     * $this->_sTablePrefix
                     * @param string $strRefTableName (option) Nom de la table de jointure où trouver
                     * les valeurs sélectionnées : "" par défaut
                     * @param string $iID (option) Id auquel sont associées les valeurs
                     * sélectionnées : "" par défaut
                     * @param mixed $aSelectedValues (option) Tableau des valeurs sélectionnées
                     * (liste des id) : "" par défaut
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param bool $bDeleteOnAdd (option) Supprimer les valeurs de la liste source
                     * après ajout à la liste destination : true par défaut
                     * @param bool $bEnableManagement (option) Accès à la popup d'ajout dans la
                     * table de référence : true par défaut
                     * @param bool $bSearchEnabled (option) La liste n'est pas remplie et un
                     * formulaire de recherche est ajouté : false par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param mixed $iSize (option) 
                     * @param string $iWidth (option) Largeur du contrôle : 200 par défaut
                     * @param string $strColRefTableName (option) Nom de la colonne dans la table de
                     * référence correspondant à $iID : "CONTENU_ID" par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     * @param string $arForeignKey (option) Remplace la Pelican_Index_Frontoffice_Zone
                     * de recherche par une liste déroulante pour filtrer la sélection (nécessite
                     * bSearchEnabled à true) : 3 modes : - 1 : nom de table de référence de la clé
                     * étrangère (sans le préfixe) => la requête de liste et de recherche seront
                     * alors génériques - 2 : array(nom de table de référence de la clé
                     * étrangère, SQL avec id et lib dans le select pour la liste déroulante) => la
                     * requête de recherche sera alors générique - 3 : array(nom de table de
                     * référence de la clé étrangère, SQL avec id et lib dans le select pour la
                     * liste déroulante, SQL avec id et lib dans le select pour la recherche et
                     * :RECHERCHE: dans la clause where)
                     * @param bool $bSingle (option) Génère un nom de champ sans[] : false par
                     * défaut
                     * @param bool $alternateId (option) 
                     * @param string $strOrderColName (option) 
                     *
                     * @return string
                     */
                    public function createAssoc($oConnection, $strName, $strLib, $strTableName, $strRefTableName = "", $iID = "", $aSelectedValues = "", $bRequired = false, $bDeleteOnAdd = true, $bEnableManagement = true, $bSearchEnabled = false, $bReadOnly = false, $iSize = "5", $iWidth = 200, $strColRefTableName = "contenu_id", $bFormOnly = false, $arForeignKey = "", $bSingle = false, $alternateId = false, $strOrderColName = '')
                    {
                        return $this->_createAssoc($oConnection, $strName, $strLib, "", $strTableName, $strRefTableName, $iID, $aSelectedValues, $bRequired, $bDeleteOnAdd, $bEnableManagement, $bSearchEnabled, $bReadOnly, $iSize, $iWidth, $strColRefTableName, $bFormOnly, $arForeignKey, $bSingle, $alternateId, $strOrderColName);
                    }

                    /**
                     * Génère une association à partir d'un tableau de valeurs.
                     *
                     * @access public
                     *
                     * @param Pelican_Db $oConnection Objet connection à la base
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param mixed $aDataValues (option) Tableau de valeurs (id=>lib) : "" par
                     * défaut
                     * @param mixed $aSelectedValues (option) Tableau des valeurs sélectionnées
                     * (liste des id) : "" par défaut
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param bool $bDeleteOnAdd (option) Supprimer les valeurs de la liste source
                     * après ajout à la liste destination : true par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param mixed $iSize (option) 
                     * @param string $iWidth (option) Largeur du contrôle : 200 par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     * @param string $arForeignKey (option) Remplace la Pelican_Index_Frontoffice_Zone
                     * de recherche par une liste déroulante pour filtrer la sélection (nécessite
                     * bSearchEnabled à true) : 3 modes : - 1 : nom de table de référence de la clé
                     * étrangère (sans le préfixe) => la requête de liste et de recherche seront
                     * alors générique - 2 : array(nom de table de référence de la clé
                     * étrangère, SQL avec id et lib dans le select pour la liste déroulante) => la
                     * requête de recherche sera alors générique - 3 : array(nom de table de
                     * référence de la clé étrangère, SQL avec id et lib dans le select pour la
                     * liste déroulante, SQL avec id et lib dans le select pour la recherche et
                     * :RECHERCHE: dans la clause where)
                     * @param string $strOrderColName (option) 
                     *
                     * @return string
                     */
                    public function createAssocFromList($oConnection, $strName, $strLib, $aDataValues = "", $aSelectedValues = "", $bRequired = false, $bDeleteOnAdd = true, $bReadOnly = false, $iSize = "5", $iWidth = 200, $bFormOnly = false, $arForeignKey = "", $strOrderColName = '', $limit = 0)
                    {
                        $bSearchEnabled = ($arForeignKey ? true : false);

                        return $this->_createAssoc($oConnection, $strName, $strLib, $aDataValues, "", "", "", $aSelectedValues, $bRequired, $bDeleteOnAdd, false, $bSearchEnabled, $bReadOnly, $iSize, $iWidth, "", $bFormOnly, $arForeignKey, false, false, $strOrderColName, false, $limit);
                    }

                    /**
                     * Génère une association à partir de requêtes SQL.
                     *
                     * @access public
                     *
                     * @param mixed $deprecated 
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param mixed $strSQL (option) Requête SQL des valeurs disponibles (id,lib) :
                     * "" par défaut
                     * @param mixed $strSQLValues (option) Requête SQL des valeurs sélectionnées
                     * (liste des id) : "" par défaut
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param bool $bDeleteOnAdd (option) Supprimer les valeurs de la liste source
                     * après ajout à la liste destination : true par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param mixed $iSize (option) 
                     * @param string $iWidth (option) Largeur du contrôle : 200 par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     * @param string $arForeignKey (option) Remplace la Pelican_Index_Frontoffice_Zone
                     * de recherche par une liste déroulante pour filtrer la sélection (nécessite
                     * bSearchEnabled à true) : 3 modes : - 1 : nom de table de référence de la clé
                     * étrangère (sans le préfixe) => la requête de liste et de recherche seront
                     * alors générique - 2 : array(nom de table de référence de la clé
                     * étrangère, SQL avec id et lib dans le select pour la liste déroulante) => la
                     * requête de recherche sera alors générique - 3 : array(nom de table de
                     * référence de la clé étrangère, SQL avec id et lib dans le select pour la
                     * liste déroulante, SQL avec id et lib dans le select pour la recherche et
                     * :RECHERCHE: dans la clause where)
                     * @param array $arSearchFields (option) Liste des champs sur lesquels effectuer
                     * une recherche par like
                     * @param mixed $aBind (option) 
                     * @param string $strOrderColName (option) 
                     * @param bool $showAll (option) 
                     *
                     * @return string
                     */
                    public function createAssocFromSql($deprecated, $strName, $strLib, $strSQL = "", $strSQLValues = "", $bRequired = false, $bDeleteOnAdd = true, $bReadOnly = false, $iSize = "5", $iWidth = 200, $bFormOnly = false, $arForeignKey = "", $arSearchFields = "", $aBind = array(), $strOrderColName = '', $showAll = false)
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
                                        $sResearch .= " OR ";
                                    }
                                    $sResearch .= "UPPER(".$val.") like UPPER('%:RECHERCHE:%')";
                                    $sFilter .= $sResearch;
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
                                    $this->_getValuesFromSQL(str_replace(":RECHERCHE:", "%", $strSQL), $aDataValues);
                                }
                                $_SESSION["AssocFromSql_Search"][$this->sFormName."_".$strName] = $strSQL;
                            } else {
                                $this->_getValuesFromSQL($strSQL, $aDataValues);
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
                                            $aSelectedValues[count($aSelectedValues) ] = $valeur[$keys[0]];
                                        }
                                    }
                                }
                            }
                        }

                        return $this->_createAssoc($oConnection, $strName, $strLib, $aDataValues, "", "", "", $aSelectedValues, $bRequired, $bDeleteOnAdd, false, $bSearchEnabled, $bReadOnly, $iSize, $iWidth, "", $bFormOnly, $arForeignKey, false, false, $strOrderColName, $showAll);
                    }

                    /**
                     * Génère une association.
                     *
                     * @access private
                     *
                     * @param Pelican_Db $oConnection Objet connection à la base
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param mixed $aDataValues Tableau de valeurs (id=>lib)
                     * @param string $strTableName 
                     * @param string $strRefTableName (option) Nom de la table de jointure où trouver
                     * les valeurs sélectionnées : "" par défaut
                     * @param string $iID (option) Id auquel sont associées les valeurs
                     * sélectionnées : "" par défaut
                     * @param mixed $aSelectedValues (option) Tableau des valeurs sélectionnées
                     * (liste des id) : "" par défaut
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param bool $bDeleteOnAdd (option) Supprimer les valeurs de la liste source
                     * après ajout à la liste destination : true par défaut
                     * @param bool $bEnableManagement (option) Accès à la popup d'ajout dans la
                     * table de référence : true par défaut
                     * @param bool $bSearchEnabled (option) La liste n'est pas remplie et un
                     * formulaire de recherche est ajouté : false par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param mixed $iSize (option) 
                     * @param string $iWidth (option) Largeur du contrôle : 200 par défaut
                     * @param string $strColRefTableName (option) Nom de la colonne dans la table de
                     * référence correspondant à $iID : "CONTENU_ID" par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     * @param string $arForeignKey (option) Remplace la Pelican_Index_Frontoffice_Zone
                     * de recherche par une liste déroulante pour filtrer la sélection (nécessite
                     * bSearchEnabled à true) : 3 modes : - 1 : nom de table de référence de la clé
                     * étrangère (sans le préfixe) => la requête de liste et de recherche seront
                     * alors générique - 2 : array(nom de table de référence de la clé
                     * étrangère, SQL avec id et lib dans le select pour la liste déroulante) => la
                     * requête de recherche sera alors générique - 3 : array(nom de table de
                     * référence de la clé étrangère, SQL avec id et lib dans le select pour la
                     * liste déroulante, SQL avec id et lib dans le select pour la recherche et
                     * :RECHERCHE: dans la clause where)
                     * @param bool $bSingle (option) Génère un nom de champ sans[] : false par
                     * défaut
                     * @param bool $alternateId (option) 
                     * @param string $strOrderColName (option) 
                     * @param bool $showAll (option) 
                     *
                     * @return string
                     */
                    public function _createAssoc($oConnection, $strName, $strLib, $aDataValues, $strTableName, $strRefTableName = "", $iID = "", $aSelectedValues = "", $bRequired = false, $bDeleteOnAdd = true, $bEnableManagement = true, $bSearchEnabled = false, $bReadOnly = false, $iSize = "5", $iWidth = 200, $strColRefTableName = "contenu_id", $bFormOnly = false, $arForeignKey = "", $bSingle = false, $alternateId = false, $strOrderColName = '', $showAll = false, $limit = 0)
                    {
                        global $HTTP_SESSION_VARS;
                        $strTmp = "";
                        if (!$bReadOnly) {
                            $this->_aIncludes["list"] = true;
                        }
                        if ($bSearchEnabled) {
                            // Charge uniquement les valeurs sélectionnées.
                            if ($iID != "") {
                                $aSelectedValues = array();
                                $strSQL = "select A.".$strTableName.$this->_sTableSuffixeId." as \"id\", A.".$strTableName.$this->_sTableSuffixeLabel." as \"lib\"";
                                $strSQL .= " from ".$this->_sTablePrefix.$strTableName." A, ".$strRefTableName." B";
                                if ($alternateId) {
                                    $child = $strName;
                                } else {
                                    $child = $strTableName.$this->_sTableSuffixeId;
                                }
                                $strSQL .= " where A.".$strTableName.$this->_sTableSuffixeId." = B.".$child;
                                $strSQL .= " and B.".$strColRefTableName." = ".$iID;
                                $strSQL .= " order by ";
                                if ($strOrderColName != "") {
                                    $strSQL .= $strOrderColName;
                                } else {
                                    $strSQL .= "Lib";
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
                                    $strTmp .= Pelican_Html::input(array(type => "hidden", name => $strName.($bSingle ? "" : "[]"), value => str_replace("\"", "&quot;", $ligne["key"])));
                                }
                            }
                        }
                        // Génération du couple libellé/champ
                        if ($bReadOnly) {
                            if ($bSearchEnabled) {
                                foreach ($aSelectedValues as $ligne) {
                                    $strTmp .= "".$ligne.Pelican_Html::br();
                                }
                            } else {
                                if (is_array($aSelectedValues)) {
                                    foreach ($aSelectedValues as $ligne) {
                                        $strTmp .= "".$aDataValues[$ligne].Pelican_Html::br();
                                    }
                                }
                            }
                            if (!$bFormOnly) {
                                $this->countInputName($strName."_last_selected");
                                $strTmp .= Pelican_Html::input(array(type => "hidden", name => $strName."_last_selected", id => $strName."_last_selected"));
                                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
                            }
                        } else {
                            $strTmp .= "<table class=\"".$this->sStyleVal."\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:".(2 * $iWidth + 30)."px;\" summary=\"Associative\">";
                            if ($this->_bUseAssocLabel) {
                                $strTmp .= Pelican_Html::tr(
                                        Pelican_Html::td(array("class" => $this->sStyleVal), Pelican_Html::i(t('FORM_MSG_LIST_SELECTED')))
                                        .($strOrderColName ? Pelican_Html::td(array("class" => $this->sStyleVal), Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp()) : "")
                                        .Pelican_Html::td(array("class" => $this->sStyleVal), Pelican_Html::nbsp())
                                        .Pelican_Html::td(array("class" => $this->sStyleVal), Pelican_Html::i(t('FORM_MSG_LIST_AVAILABLE')))
                                   )."\n";
                            }
                            $strTmp .= "<tr>";
                            // Valeurs choisies
                            $this->countInputName($strName);
                            $strTmp .= "<td class=\"".$this->sStyleVal."\">";
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
                            $strTmp .= Pelican_Html::select(array(id => $strName, name => $strName.($bSingle ? "" : "[]"), size => $iSize, multiple => "multiple", ondblclick => "assocDel(this".($bDeleteOnAdd ? ", true" : "").")", style => "width:".$iWidth."px;"), implode("", $aOption))."</td>";
                            if ($strOrderColName != "") {
                                $strTmp .= "<td class=\"".$this->strStyleVal."\">";
                                $strTmp .= "<img src=\"".$this->_sLibPath.$this->_sLibForm."/images/top.gif\" width=\"13\" height=\"15\" ";
                                $strTmp .= "onClick=\"MoveTop(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']);\"\">".Pelican_Html::br();
                                $strTmp .= "<img src=\"".$this->_sLibPath.$this->_sLibForm."/images/up.gif\" width=\"13\" height=\"15\" ";
                                $strTmp .= "onClick=\"MoveUp(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']);\"\">".Pelican_Html::br();
                                $strTmp .= "<img src=\"".$this->_sLibPath.$this->_sLibForm."/images/down.gif\" width=\"13\" height=\"15\" ";
                                $strTmp .= "onClick=\"MoveDown(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']);\"\">".Pelican_Html::br();
                                $strTmp .= "<img src=\"".$this->_sLibPath.$this->_sLibForm."/images/bottom.gif\" width=\"13\" height=\"15\" ";
                                $strTmp .= " onClick=\"MoveBottom(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']);\"\">";
                                $strTmp .= "</td>";
                                $this->_aIncludes["ordered_list"] = true;
                            }
                            $strTmp .= "<td valign=\"middle\" style=\"width:30px;\" align=\"center\">";
                            $strTmp .= Pelican_Html::nbsp()."<a href=\"javascript://\" onclick=\"assocAdd".($bSingle ? "Single" : "")."(document.".$this->sFormName.".src".$strName;
                            if ($bDeleteOnAdd) {
                                $strTmp .= ", true";
                            } else {
                                $strTmp .= ", false";
                            }
                            if ($strOrderColName != "") {
                                $strTmp .= ", true";
                            } else {
                                $strTmp .= ", false";
                            }
                            if ($limit) {
                                $strTmp .= ", ".$limit;
                            }
                            $strTmp .= ");\"><img src=\"".$this->_sLibPath.$this->_sLibForm."/images/left.gif\" border=\"0\" width=\"7\" height=\"12\" /></a>".Pelican_Html::nbsp();
                            $strTmp .= Pelican_Html::br();
                            $strTmp .= Pelican_Html::nbsp()."<a href=\"javascript://\" onclick=\"assocDel(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']";
                            if ($bDeleteOnAdd) {
                                $strTmp .= ", true";
                            } else {
                                $strTmp .= ", false";
                            }
                            if ($strOrderColName != "") {
                                $strTmp .= ", true";
                            }
                            $strTmp .= ");\"><img src=\"".$this->_sLibPath.$this->_sLibForm."/images/right.gif\" border=\"0\" width=\"7\" height=\"12\" /></a>".Pelican_Html::nbsp();
                            $strTmp .= "</td>";
                            // Valeurs disponibles
                            $strTmp .= "<td class=\"".$this->sStyleVal."\">";
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
                                            $sqlSearch .= " WHERE ".$champForeign.$this->_sTableSuffixeId." = ':RECHERCHE:'";
                                            $sqlSearch .= " order by lib";
                                        }
                                        // cas de la recherche par input
                                    } else {
                                        // Définition de la requête de recherche de façon générique pour la recherche par input
                                        $sqlSearch = "select ".$strTableName.$this->_sTableSuffixeId." \"id\", ".$strTableName.$this->_sTableSuffixeLabel." \"lib\" from ".$this->_sTablePrefix.$strTableName;
                                        $sqlSearch .= " WHERE ".$strTableName.$this->_sTableSuffixeLabel." LIKE ('%:RECHERCHE:%')";
                                        $sqlSearch .= " order by lib";
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
                                        $strTmp .= Pelican_Html::select(array(name => "iSearchVal".$strName, id => "iSearchVal".$strName, size => "1", style => "width:".$iWidth."px;", onchange => $action), implode("", $aOption))."\n";
                                    }
                                } else {
                                    $this->countInputName("iSearchVal".$strName);
                                    $strTmp .= Pelican_Html::input(array(type => "text", name => "iSearchVal".$strName, size => "14", onkeydown => "submitIndexation('".$this->_sLibPath.$this->_sLibForm."/', '".($strTableName ? $strTableName."','".base64_encode($sqlSearch) : "','".base64_encode($this->sFormName."_".$strName))."')"));
                                    $this->countInputName("bSearch".$strName);
                                    $strTmp .= "<input type=\"button\" class=\"button\" name=\"bSearch".$strName."\" value=\"".t('FORM_BUTTON_SEARCH')."\" onclick=\"".$action."\" />".Pelican_Html::br();
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
                            $strTmp .= Pelican_Html::select(array(id => "src".$strName, name => "src".$strName, size => $size, multiple => "multiple", ondblclick => "assocAdd".($bSingle ? "Single" : "")."(this, ".($bDeleteOnAdd ? "true" : "false").($strOrderColName ? ", true" : ", false").($limit ? ", ".$limit : ", 0").")", style => "width:".$iWidth."px;"), implode("", $aOption));
                            // Lien vers popup de gestion de la table de référence
                            if ($bEnableManagement) {
                                $this->_aIncludes["popup"] = true;
                                $strTmp .= "<td class=\"".$this->sStyleVal."\">";
                                $strTmp .= "<a href=\"javascript://\" onclick=\"addRef('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."','".$strName."', '".$strTableName."', ";
                                if ($bDeleteOnAdd) {
                                    $strTmp .= "1";
                                } else {
                                    $strTmp .= "0";
                                }
                                $strTmp .= ");\">".t('FORM_BUTTON_ADD_VALUE')."</a>";
                                $strTmp .= "</td>";
                            }
                            $strTmp .= "</tr>\n</table>\n";
                            if (!$bFormOnly) {
                                $this->countInputName($strName."_last_selected");
                                $strTmp .= "<input type=\"hidden\"  name=\"".$strName."_last_selected\" id=\"".$strName."_last_selected\" />";
                                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
                            }
                            // Génération de la fonction js de vérification.
                            if ($bRequired) {
                                $this->_sJS .= "o = obj.elements[\"".$strName.($bSingle ? "" : "[]")."\"];\n";
                                $this->_sJS .= "if ( o.length == 0 ) {\n";
                                $this->_sJS .= "alert(\"".t('FORM_MSG_VALUE_CHOOSE')." "."\\"."\"".strip_tags(str_replace("\"", ""."\\"."\"", $strLib)).""."\\"."\"\");\n";
                                $this->_sJS .= "fwFocus(o);\n";
                                $this->_sJS .= "return false;\n";
                                $this->_sJS .= "}\n";
                            }
                            $this->_sJS .= "selectAll(document.".$this->sFormName.".elements[\"".$strName.($bSingle ? "" : "[]")."\"]);\n";
                        }

                        return $this->output($strTmp);
                    }

                    /**
                     * Génère un champ de sélection de Contenu editorial.
                     *
                     * 2 modes possibles : sélection simple (iSize =1) ou sélection multiple
                     * <code>
                     * $aSelectedValues = array("1"=>"test1","2"=>"test2");
                     * $oForm->createContentFromList("Contenu", "Contenu", $aSelectedValues, true,
                     * false, 5);
                     * </code>
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param mixed $aSelectedValues (option) Tableau des valeurs sélectionnées
                     * (id=>lib)
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param mixed $iSize (option) 
                     * @param string $iWidth (option) Largeur du contrôle : 200 par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     * @param bool $bSingle (option) Génère un nom de champ sans[] : false par
                     * défaut
                     * @param string $sContentType (option) Appliquer un filtre sur le type de contenu
                     * passé en paramètre (ce paramètre peut être un ensemble d'id séparés par
                     * des )
                     * @param bool $bEnableOrder (option) Affichage des fonctions de tri de la liste :
                     * false par défaut
                     * @param string $siteExterne (option) 
                     *
                     * @return string
                     */
                    public function createContentFromList($strName, $strLib, $aSelectedValues = "", $bRequired = false, $bReadOnly = false, $iSize = "5", $iWidth = 200, $bFormOnly = false, $bSingle = false, $sContentType = "", $bEnableOrder = false, $siteExterne = "")
                    {
                        global $HTTP_SESSION_VARS;
                        if (!$bReadOnly) {
                            $this->_aIncludes["list"] = true;
                        }
                        //$aTmpSelectedValues = array();
                        //$this->_getValues($oConnection, $strTableName, $strRefTableName, $iID, $aDataValues, $aTmpSelectedValues, $strColRefTableName);
                        //if ($aSelectedValues == "" ) {
                        //$aSelectedValues = $aTmpSelectedValues;
                        //}
                        if (!is_array($aSelectedValues)) {
                            if ($aSelectedValues != "") {
                                $aSelectedValues = array($aSelectedValues);
                            } else {
                                $aSelectedValues = array();
                            }
                        }
                        if ($bReadOnly) {
                            if (is_array($aSelectedValues)) {
                                while ($ligne = each($aSelectedValues)) {
                                    $this->countInputName($strName.($bSingle ? "" : "[]"));
                                    $strTmp .= "<input type=\"hidden\"  name=\"".$strName.($bSingle ? "" : "[]")."\" value=\"".str_replace("\"", "&quot;", $ligne["key"])."\" />";
                                }
                            }
                        }
                        // Génération du couple libellé/champ
                        if ($bReadOnly) {
                            if (is_array($aSelectedValues)) {
                                foreach ($aSelectedValues as $key => $ligne) {
                                    $strTmp .= "".$ligne.Pelican_Html::br();
                                }
                            }
                            if (!$bFormOnly) {
                                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
                            }
                        } else {
                            // Valeurs choisies
                            $this->countInputName($strName);
                            $strTmp .= '<table cellpadding="0" cellspacing="0" border="0" align="left" summary=\"Contenu\">';
                            $strTmp .= '<tr>';
                            $strTmp .= "<td>";
                            $aOption = array();
                            if ($aSelectedValues) {
                                if (is_array($aSelectedValues)) {
                                    foreach ($aSelectedValues as $key => $value) {
                                        if ($value) {
                                            $aOption[] = Pelican_Html::option(array(value => $key), $value);
                                        }
                                    }
                                }
                            }
                            $strTmp .= Pelican_Html::select(array(id => $strName, name => $strName.($bSingle ? "" : "[]"), size => (($iSize < 4 && $bEnableOrder) ? 4 : $iSize), multiple => "multiple", ondblclick => "assocDel(this, false);", style => "width:".$iWidth."px;"), implode("", $aOption));
                            if ($bEnableOrder) {
                                $strTmp .= "<td class=\"".$this->strStyleVal."\">";
                                $strTmp .= "<img src=\"".$this->_sLibPath.$this->_sLibForm."/images/top.gif\" width=\"13\" height=\"15\" ";
                                $strTmp .= "onClick=\"MoveTop(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']);\"\">".Pelican_Html::br();
                                $strTmp .= "<img src=\"".$this->_sLibPath.$this->_sLibForm."/images/up.gif\" width=\"13\" height=\"15\" ";
                                $strTmp .= "onClick=\"MoveUp(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']);\"\">".Pelican_Html::br();
                                $strTmp .= "<img src=\"".$this->_sLibPath.$this->_sLibForm."/images/down.gif\" width=\"13\" height=\"15\" ";
                                $strTmp .= "onClick=\"MoveDown(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']);\"\">".Pelican_Html::br();
                                $strTmp .= "<img src=\"".$this->_sLibPath.$this->_sLibForm."/images/bottom.gif\" width=\"13\" height=\"15\" ";
                                $strTmp .= " onClick=\"MoveBottom(document.".$this->sFormName.".elements['".$strName.($bSingle ? "" : "[]")."']);\"\">";
                                $strTmp .= "</td>";
                                $this->_aIncludes["ordered_list"] = true;
                            }
                            $strTmp .= '</tr></table>';
                            // Recherche activée (par champ input ou par combo ($arForeignKey doit être renseigné)
                            $this->_aIncludes["popup"] = true;
                            $action = "\"searchContent('".$this->_sLibPath.$this->_sLibForm."/', 'document.".$this->sFormName."', '".$strName."', '".(((int) $iSize == 1) ? "single" : "multi")."', '".$sContentType."','".$siteExterne."','".base64_encode(session_id())."');\"";
                            $strTmp .= "<input type=\"button\" class=\"button\" name=\"bSearch".$strName."\" value=\"".t('FORM_BUTTON_SEARCH')."\" onclick=".$action." />";
                            $strTmp .= Pelican_Html::nbsp()."<input type=\"button\" class=\"button\" value=\"".t('FORM_BUTTON_FILE_DELETE')."\"";
                            $strTmp .= " onclick=\"assocDel(document.getElementById('".$strName."'), false";
                            if ($bEnableOrder) {
                                $strTmp .= ", true";
                            }
                            $strTmp .= ");\" >";
                            if (!$bFormOnly) {
                                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
                            }
                            // Lien vers popup de gestion de la table de référence
                            // Génération de la fonction js de vérification.
                            if ($bRequired) {
                                $this->_sJS .= "o = obj.elements[\"".$strName.($bSingle ? "" : "[]")."\"];\n";
                                $this->_sJS .= "if ( o.length == 0 ) {\n";
                                $this->_sJS .= "alert(\"".t('FORM_MSG_VALUE_CHOOSE')." "."\\"."\"".strip_tags(str_replace("\"", ""."\\"."\"", $strLib)).""."\\"."\"\");\n";
                                $this->_sJS .= "fwFocus(o);\n";
                                $this->_sJS .= "return false;\n";
                                $this->_sJS .= "}\n";
                            }
                            $this->_sJS .= "selectAll(document.".$this->sFormName.".elements[\"".$strName.($bSingle ? "" : "[]")."\"]);\n";
                        }

                        return $this->output($strTmp);
                    }

    /**
     * 
     *
     * @access public
     * @param string $fieldName (option) 
     * @param bool $activation (option) 
     * @return mixed
     */
    public function createVirtualKeyboard($fieldName = "", $activation = false)
    {
        if ($activation) {
            $this->_aIncludes['virtualkeyboard'] = true;
            $return = "<img id='virtualkbd' style='position:absolute;top:2px;right:2px;cursor:pointer;' src='" . Pelican::$config["LIB_PATH"] . $this->sLibOther . "/tiny_mce/plugins/Jsvk/img/jsvk.gif' onclick=\"PopupVirtualKeyboard.toggle((activeInput?activeInput:" . ($fieldName ? "'" . $fieldName . "'" : "this") . "),'td'); return false;\"/>";
        } else {
            $return = false;
        }

        return $return;
    }

                    /**
                     * .
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param string $googleKey (option) 
                     * @param string $strAddressValue (option) 
                     * @param string $strLatValue (option) 
                     * @param string $strLongValue (option) 
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     * @param string $strEvent (option) Handler d'événements sur le champ : "" par
                     * défaut
                     * @param mixed $width (option) 
                     * @param mixed $height (option) 
                     *
                     * @return string
                     */
                    public function createMap($strName, $strLib, $bRequired = false, $googleKey = "", $strAddressValue = "", $strLatValue = "", $strLongValue = "", $bReadOnly = false, $bFormOnly = false, $strEvent = "", $width = "470", $height = "200")
                    {
                        $directOutput = $this->bDirectOutput;
                        $this->bDirectOutput = false;
                        if ($googleKey) {
                            $strTmp .= Pelican_Html::script(array(src => "http://maps.google.com/maps?file=api&amp;v=3&amp;sensor=true&amp;key=".$googleKey));
                            $strTmp .= $this->createHidden($strName, $strValue);
                            $strTmp .= $this->createHidden($strName."_ADDRESS_HIDDEN", $strAddressValue);
                            $strTmp .= $this->createHidden($strName."_ADDRESS_HIDDEN_LAT", $strLatValue);
                            $strTmp .= $this->createHidden($strName."_ADDRESS_HIDDEN_LONG", $strLongValue);
                            $strTmp .= Pelican_Html::label("Latitude".($bRequired && !$bReadOnly ? " ".REQUIRED : "")." : ").$this->createInput($strName."_LATITUDE", "Latitude", 20, "float", $bRequired, $strLatValue, $bReadOnly, 20, true, $strEvent);
                            $strTmp .= Pelican_Html::nbsp().Pelican_Html::nbsp().Pelican_Html::nbsp();
                            $strTmp .= Pelican_Html::label("Longitude".($bRequired && !$bReadOnly ? " ".REQUIRED : "")." : ").$this->createInput($strName."_LONGITUDE", "Longitude", 20, "float", $bRequired, $strLongValue, $bReadOnly, 20, true, $strEvent);
                            $strTmp .= Pelican_Html::br();
                            $divMap = Pelican_Html::div(array(id => $strName."_MAP", style => "width:".$width."px;height: ".$height."px;"));
                            $divSearch = $this->createInput($strName."_ADDRESS", $strLib, 255, "", "", $strAddressValue, false, 35, true, $strEvent);
                            $divSearch .= Pelican_Html::nbsp().$this->createbutton($strName."_ADDRESS_BTN_FIND", t('FORM_BUTTON_SEARCH'), "");
                            $divSearch .= Pelican_Html::nbsp().$this->createbutton($strName."_ADDRESS_BTN_REST", "Réinitialiser", "javascript:void( null ); return false", true);
                            //$divSearch .= Pelican_Html::nbsp().$this->createbutton( $strName . "_MYLOC", "Ma localisation", "javascript:void( null ); return false", true);
                            $divSearch = Pelican_Html::div(array(style => "text-align:center;"), $divSearch);
                            $strTmp .= Pelican_Html::div(array(style => "width:".$width."px;border:#ccc 2px solid;background-color:#eee;margin-top:5px;"), $divMap.$divSearch);
                        } else {
                            $strTmp = Pelican_Html::div(array("class" => "erreur", style => "widht:70%"), "Veuillez insérer la clé Google fournie par le site  ".Pelican_Html::a(array(href => "http://code.google.com/intl/fr-FR/apis/maps/signup.html"), "Google Maps API"));
                        }
                        if (!$bFormOnly) {
                            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, false, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "", "", $this->_sFormDisposition);
                        }
                        $this->map[$strName] = $strName;
                        $this->_aIncludes["map"] = true;
                        $this->bDirectOutput = $directOutput;

                        return $this->output($strTmp);
                    }

                    /**
                     * Génère un éditeur DHTML.
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param string $strValue (option) Valeur du champ : "" par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param bool $bPopup (option) 
                     * @param string $strSubFolder (option) Répertoire racine de la médiathèque
                     * appelée du miniword
                     * @param int $Width (option) 
                     * @param int $Height (option) 
                     * @param mixed $limitedConf (option) Identifiant du filtre à appliquer à la
                     * confiration de l'éditeur (dans /application/configs/editor.ini.php, $_LIMITED)
                     * @param array $options
                     *  available keys:
                     *      message: adds a help message beneath the text editor
                     *      maxCharacterNumber: adds a counter
                     *      Array infoBulle ( 'isIcon' => true, 'message' => t($message))
                     *
                     * @return string
                     */
                    public function createEditor($strName, $strLib, $bRequired = false, $strValue = "", $bReadOnly = false, $bPopup = true, $strSubFolder = "", $Width = "", $Height = "", $limitedConf = "", $options = array())
                    {
                        global $_EDITOR;
                        $this->tinymce = true;
                        $strTmp = "<tr>";
                        $strTmp .= "      <td class=\"".$this->sStyleLib."\" valign=\"top\">".Pelican_Text::htmlentities($strLib);

                        if ($bRequired && !$bReadOnly) {
                            $strTmp .= $this->_sRequiredIndicator;
                        }

                        if (is_array($options['infoBulle']) && !empty($options['infoBulle']['isIcon'])) {
                            $strTmp .= Backoffice_Tooltip_Helper::help($options['infoBulle']['message']);
                        }

                        if (!$bReadOnly) {
                            $strTmp .= "&nbsp;<a href=\"javascript://\" onclick=\"cleanEditor('".$this->sFormName.".".$strName."');\" style=\"text-decoration:none\"><img src=\"".$this->_sLibPath.$this->_sLibForm."/images/clean.gif\" width=\"23\" height=\"22\" border=\"0\" align=\"middle\" alt=\"Vider le contenu\" /></a>";
                        }

                        if(!empty($options['message'])){
                            $strTmp .= sprintf('<br><span>%s</span>',$options['message']);
                        }

                        $strTmp .= "</td>";
                        $strTmp .= $this->getDisposition();
                        $strTmp .= "      <td class=\"".$this->sStyleVal."\">";
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
                                $strTmp .= "<a href=\"javascript://\" onclick=\"PopupVirtualKeyboard.hide();popupEditor2('".$strName."','".$strSubFolder."', '".$limitedConf."');\" style=\"text-decoration:none\"><img src=\"".$this->_sLibPath.$this->_sLibForm."/images/iframe.gif\" width=\"18\" height=\"18\" border=\"0\" align=\"middle\" alt=\"\" />&nbsp;".t('FORM_BUTTON_EDITOR')."</a><br />";
                            }
                            $strTmp .= $this->createHidden($strName, str_replace("\"", "&quot;", $strValue), true);
                            $this->countInputName("iframeText".$strName);
                            $strTmp .= "<iframe  id=\"iframeText".$strName."\" name=\"iframeText".$strName."\" width=\"".$Width."\" height=\"".$Height."\" style=\"border: 1px solid #ccc;\" frameborder=\"0\"></iframe>";
                            if (isset($options['maxCharacterNumber'])) {
                                $strTmp .= "<span id=\"characterNumber-".$strName."\"></span><span> / ".$options['maxCharacterNumber']." ".t('NDP_CHARACTERS')."</span>";
                            }
                            $strTmp .= "<script type=\"text/javascript\">\n";
                            $strTmp .= "  var MEDIA_HTTP=\"".str_replace("/", "\/", $this->_sUploadHttpPath."/")."\";\n";
                            $strTmp .= "  var MEDIA_VAR=\"".str_replace("/", "\/", $this->_sUploadVar."/")."\";\n";
                            $strTmp .= "  var tempM=new RegExp(MEDIA_VAR , \"gi\");\n";
                            $strTmp .= "var body = \"<html><head>";
                            $strTmp .= $meta;
                            if ($this->_sEditorCss) {
                                $strTmp .= "<link rel='stylesheet' type='text/css' href='".$this->_sEditorCss."' />";
                            }
                            $strTmp .= "</head><body>\" + document.getElementById('".$strName."').value.replace(tempM,MEDIA_HTTP) + \"</body></html>\";\n";
                            $strTmp .= "      iframeText".$strName.".document.open();\n";
                            $strTmp .= "      iframeText".$strName.".document.write(body);\n";
                            $strTmp .= "      iframeText".$strName.".document.close();\n";
                            $strTmp .= "\n</script>\n";

                            if (isset($options['maxCharacterNumber'])) {
                                $strTmp .= "<script type=\"text/javascript\">\n";
                                $strTmp .= "  var charNumb = $('#".$strName."').val().length;\n";
                                $strTmp .= "  if (charNumb > 0) {\n";
                                $strTmp .= "      var number = countCharacterNumber($('#".$strName."').val());\n";
                                $strTmp .= "      $('#characterNumber-".$strName."').text(number);\n";
                                $strTmp .= "  }\n";
                                $strTmp .= "\n</script>\n";
                            }
                        } else {
                            //nada
                            $this->aEditor[] = $strName;
                            $this->countInputName($strName);
                            $strTmp .= Pelican_Html::textarea(array(id => $strName."TagStripped", name => $strName."TagStripped", rows => "20", cols => "80", "style" => "display: none;"), Pelican_Text::htmlentities(preg_replace('@<script[^>]*?>.*?</script>@si', '', $strValue)));
                            $strTmp .= Pelican_Html::textarea(array(id => $strName, name => $strName, rows => "20", cols => "80", "class" => "mceEditor", "mce_editable" => "true"), Pelican_Text::htmlentities($strValue));
                            $strTmp .= "<iframe  id=\"iframeText".$strName."\" name=\"iframeText".$strName."\" width=\"".$Width."\" height=\"".$Height."\" style=\"border: 1px solid #ccc;\" frameborder=\"0\"></iframe>";
                            $strTmp .= "<script type=\"text/javascript\">\n";
                            $strTmp .= "  var MEDIA_HTTP=\"".str_replace("/", "\/", $this->_sUploadHttpPath."/")."\";\n";
                            $strTmp .= "  var MEDIA_VAR=\"".str_replace("/", "\/", $this->_sUploadVar."/")."\";\n";
                            $strTmp .= "  var tempM=new RegExp(MEDIA_VAR , \"gi\");\n";
                            $strTmp .= "var body = \"<html><head>";
                            $strTmp .= $meta;
                            if ($this->_sEditorCss) {
                                $strTmp .= "<link rel='stylesheet' type='text/css' href='".$this->_sEditorCss."' />";
                            }
                            $strTmp .= "</head><body>\" + document.getElementById('".$strName."').value.replace(tempM,MEDIA_HTTP) + \"</body></html>\";\n";
                            $strTmp .= "      iframeText".$strName.".document.open();\n";
                            $strTmp .= "      iframeText".$strName.".document.write(body);\n";
                            $strTmp .= "      iframeText".$strName.".document.close();\n";
                            $strTmp .= "\n</script>\n";
                            if (!$bReadOnly) {
                                $this->_aIncludes["text"] = true;
                            }
                        }

                        if ($bRequired) {

                            $this->_sJS .= "if (!$(obj.".$strName.").parents(\"tbody\").hasClass(\"isNotRequired\") && isBlank(obj.".$strName.".value)) {\n";
                            $this->_sJS .= "            alert(\"".t('FORM_MSG_VALUE_CONTENT')." \\"."\"".strip_tags(str_replace("\"", "\\"."\"", $strLib))."\\"."\".\");\n";
                            $this->_sJS .= "            return false;\n";
                            $this->_sJS .= "}\n";
                        }
                        $strTmp .= "</td></tr>\n";

                        return $this->output($strTmp);
                    }

                    /**
                     * Affiche un couple Libellé/Valeur, non modifiable.
                     *
                     * @access public
                     *
                     * @param string $strLib Libellé du champ
                     * @param string $strValue Valeur du champ
                     * @param bool $bToggle (option) 
                     * @param string $strLib2 (option) 
                     *
                     * @return string
                     */
                    public function createLabel($strLib, $strValue, $bToggle = false, $strLib2 = "")
                    {
                        // Génération du couple libellé/champ
                        $strTmp = "";
                        if ($bToggle) {
                            $id = "lbl".$strLib;
                            $strTmp .= $this->showSeparator("formsep", false);
                            $lib = Pelican_Html::img(array(id => "Toggle".$id, src => $this->_sLibPath."/images/toggle_close.gif", alt => "", width => 14, height => 12, border => 0, style => "cursor:pointer;", onclick => "showHideModule('".$id."')", align => "right"));
                            $lib .= $strLib;
                            $strTmp .= Pelican_Html_Form::get($lib, Pelican_Html::nbsp().$strLib2, false, false, $this->sStyleLib, $this->sStyleVal, "", "", $this->_sFormDisposition);
                            $strTmp .= Pelican_Html_Form::get(Pelican_Html::nbsp(), $strValue, false, false, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition, array(id => "DivToggle".$id, style => "display:none;"));
                        } else {
                            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strValue, false, false, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
                        }

                        return $this->output($strTmp);
                    }

                    /**
                     * Génère un champ de type Hidden.
                     *
                     * ATTENTION : si le champ a déjà été créé avant, la commande est ignorée
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strValue (option) Valeur du champ : "" par défaut
                     * @param string $bGetHTML (option) Récupération du retour de la fonction
                     * (utilisation interne) : false par défaut
                     * @param string $bMultiple (option) Rajoute de "[]" pour les input multiples :
                     * true par défaut
                     *
                     * @return string
                     */
                    public function createHidden($strName, $strValue = "", $bGetHTML = false, $bMultiple = false)
                    {
                        //global $form_class_hidden;
                        $strTmp = "<input type=\"hidden\"";
                        if (!$bMultiple) {
                            $strTmp .= " id=\"".$strName."\"";
                            $this->countInputName($strName);
                        }
                        $strTmp .= " name=\"".$strName.($bMultiple ? "[]" : "")."\"";
                        if ($strValue != "") {
                            $strTmp .= " value=\"".str_replace("\"", "&quot;", $strValue)."\"";
                        }
                        $strTmp .= " />\n";
                        //if ($this->bDirectOutput) {
                        if (!$bGetHTML) {
                            $this->form_class_hidden[$strName] = $strTmp;
                        } else {
                            return $strTmp;
                        }
                    }

                    /**
                     * Génère un bouton.
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib (option) Libellé du champ : "" par défaut
                     * @param string $strFunction (option) Fonction js à exécuter quand clic du
                     * bouton : "" par défaut
                     * @param bolean $bDisable (option) Bolean indiquant si le bouton à generer est
                     * desactiver ou
                     *
                     * @return string
                     */
                    public function createButton($strName, $strLib = "", $strFunction = "", $bDisable = false)
                    {
                        $this->countInputName($strName);
                        $strTmp = "<input class=\"button\" type=\"button\" name=\"".$strName."\" id=\"".$strName."\"";
                        $strTmp .= " value=\"".Pelican_Text::htmlentities($strLib)."\"";
                        $strTmp .= " onclick=\"";
                        if ($strFunction == "close") {
                            $strTmp .= "javascript:self.close();";
                        } else {
                            $strTmp .= $strFunction;
                        }
                        $strTmp .= "\" ";
                        if ($bDisable) {
                            $strTmp .= " disabled";
                        }
                        $strTmp .= " />";

                        return $this->output($strTmp);
                    }

                    /**
                     * Génère un bouton de type Reset.
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib (option) Libellé du champ : "" par défaut
                     *
                     * @return string
                     */
                    public function createReset($strName, $strLib = "")
                    {
                        $this->countInputName($strName);
                        $strTmp .= "<input class=\"button\" type=\"reset\" name=\"".$strName."\"";
                        if ($strLib != "") {
                            $strTmp .= " value=\"".Pelican_Text::htmlentities($strLib)."\"";
                        }
                        $strTmp .= " />";

                        return $this->output($strTmp);
                    }

                    /**
                     * Génère un bouton de soumission du formulaire.
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib (option) Libellé du champ : "" par défaut
                     * @param string $strImage (option) Nom et chemin de l'image : "" par défaut
                     * @param string $iWidth (option) Largeur de l'image : "" par défaut
                     * @param string $iHeight (option) Hauteur de l'image : "" par défaut
                     * @param bool $bDisable (option) Booleen indiquant si le bouton est desactiv? ou
                     * pas : "false" par défaut
                     * @param string $strEvent (option) Js event
                     *
                     * @return string
                     */
                    public function createSubmit($strName, $strLib = "", $strImage = "", $iWidth = "", $iHeight = "", $bDisable = false, $strEvent = "")
                    {
                        $this->countInputName($strName);
                        $strTmp = "<input name=\"".$strName."\" ";
                        if ($strImage != "") {
                            $strTmp .= "type=\"Image\" src=\"".$strImage."\" alt=\"".Pelican_Text::htmlentities($strLib)."\"";
                            if ($iWidth != "") {
                                $strTmp .= "width=\"".$iWidth."\"";
                            }
                            if ($iHeight != "") {
                                $strTmp .= "height=\"".$iHeight."\"";
                            }
                            $strTmp .= "border=\"0\"";
                        } else {
                            $strTmp .= "type=\"submit\" class=\"button\" value=\"".Pelican_Text::htmlentities($strLib)."\" ".$strEvent." ";
                            if ($bDisable) {
                                $strTmp .= " disabled";
                            }
                        }
                        $strTmp .= " />";

                        return $this->output($strTmp);
                    }

                    /**
                     * Génère une Pelican_Index_Frontoffice_Zone de saisie texte.
                     *
                     * Il est possible de passer un tableau en tant que valeur
                     * => les données seront séparées par un retour chariot
                     * => il faut ensuite utiliser la fonction splitTextArea pour retrouver un tableau
                     * de données à la Soumission du formulaire
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param string $strValue (option) Valeur du champ : "" par défaut
                     * @param string $iMaxLength (option) Nb de caractères maximum : "" par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param string $iRows (option) Nombre de lignes : 5 par défaut
                     * @param string $iCols (option) Nombre de colonnes : 30 par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     * @param string $wrap (option) Paramètre wrap du textarea
                     * @param bool $bcountchars (option) Affiche le comptage des caractères tapés
                     * @param string $strEvent (option) 
                     *
                     * @return string
                     */
                    public function createTextArea($strName, $strLib, $bRequired = false, $strValue = "", $iMaxLength = "", $bReadOnly = false, $iRows = 5, $iCols = 30, $bFormOnly = false, $wrap = "", $bcountchars = true, $strEvent = "")
                    {
                        // Génération du couple libellé/champ
                        $strTmp = "";
                        if (is_array($strValue)) {
                            $strValue = implode("\r\n", $strValue);
                        }
                        if ($bReadOnly) {
                            $strTmp .= nl2br(Pelican_Text::htmlentities($strValue));
                            if (!$bFormOnly) {
                                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
                            }
                            $strTmp .= $this->createHidden($strName, str_replace("\"", "&quot;", $strValue));
                        } else {
                            if (!$this->_sDefaultFocus) {
                                $this->_sDefaultFocus = $strName;
                            }
                            if ($wrap) {
                                $txtWrap = ' wrap="'.$wrap.'"';
                            } else {
                                $txtWrap = '';
                            }
                            $this->countInputName($strName);
                            $strTmp .= "<textarea name=\"".$strName."\" rows=\"".$iRows."\" cols=\"".$iCols."\"".$txtWrap;
                            if ($strEvent) {
                                $strTmp .= " ".$strEvent;
                            }
                            if ($this->bVirtualKeyboard) {
                                $strTmp .= ' onfocus="activeInput = this;PopupVirtualKeyboard.attachInput(this);"';
                                $this->_InputVK[] = $strName;
                            }
                            if ($bcountchars) {
                                $strTmp .= ' onkeyup="countchars(this,'.($iMaxLength ? $iMaxLength : 0).');"';
                            }
                            $strTmp .= '>'.$strValue."</textarea>";
                            if ($bcountchars) {
                                $this->_aIncludes["text"] = true;
                                $strTmp .= '<div class="countchars" style="width:'.($iCols * 6).'px;" id="cnt_'.$strName.'_div">'.strlen($strValue);
                                if ($iMaxLength) {
                                    $strTmp .= '/'.$iMaxLength.' '.t('CHARACTER').'s</div>';
                                } else {
                                    $strTmp .= ' '.t('CHARACTER').(strlen($strValue) > 1 ? 's' : '').'</div>';
                                }
                            }
                            if (!$bFormOnly) {
                                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
                            }
                            // Génération de la fonction js de vérification.
                            if ($bRequired) {
                                $this->_aIncludes["text"] = true;
                                $this->_sJS .= "if ( isBlank(obj.".$strName.".value) ) {\n";
                                $this->_sJS .= "alert(\"".t('FORM_MSG_VALUE_REQUIRE')." "."\\"."\"".strip_tags(str_replace("\"", ""."\\"."\"", $strLib)).""."\\"."\".\");\n";
                                $this->_sJS .= "fwFocus(obj.".$strName.");\n";
                                $this->_sJS .= "return false;\n";
                                $this->_sJS .= "}\n";
                            }
                            if ($iMaxLength != "") {
                                $this->_aIncludes["text"] = true;
                                $this->_sJS .= "if ( obj.".$strName.".value.length > ".$iMaxLength." ) {\n";
                                $this->_sJS .= "alert(\"".t('FORM_MSG_LIMIT_1')." ".$iMaxLength." ".t('FORM_MSG_LIMIT_2')." "."\\"."\"".strip_tags(str_replace("\"", ""."\\"."\"", $strLib)).""."\\"."\".\");\n";
                                $this->_sJS .= "fwFocus(obj.".$strName.");\n";
                                $this->_sJS .= "return false;\n";
                                $this->_sJS .= "}\n";
                            }
                        }

                        return $this->output($strTmp);
                    }

                    /**
                     * Génère un champ de génération de texte en image.
                     *
                     * L'image est générée en Pelican_Cache pour la prévisualisation en direct
                     * mais sera générée définitivement au premier appel en front
                     * Cet appel se fait en définissant le src suivant :
                     * <code>
                     * <img
                     * src=Pelican::$config["MEDIA_LIB_PATH"]."/image_title.php?text=Texte%20généré&size=2">
                     * </code>
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param string $strValue (option) Valeur du champ
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param string $iSize (option) Taille de génaration de l'image : 1 par défaut
                     * (correspond à un pas de 195 pixels de largeur) => 4 pas pour du 800x600
                     * @param bool $bUpper (option) Mise en majuscule du texte : false par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     *
                     * @return string
                     */
                    public function createImageTitle($strName, $strLib, $bRequired = false, $strValue = "", $bReadOnly = false, $iSize = "1", $bUpper = false, $bFormOnly = false)
                    {
                        $iMaxLength = (int) $iSize * 15;
                        if ($bUpper) {
                            $strValue = strToUpper($strValue);
                        }
                        // Génération du couple libellé/champ
                        $img .= "<img id=\"imgTitle".$strName."\" name=\"imgTitle".$strName."\" src=\"".$this->_sLibPath.Pelican::$config['LIB_MEDIA']."/image_title.php?text=".rawurlencode($strValue)."&size=".$iSize."&preview=1\" height=\"19\" align=\"center\" border=\"1\">";
                        if ($bReadOnly) {
                            $strTmp .= $img;
                            if (!$bFormOnly) {
                                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
                            }
                            $strTmp .= $this->createHidden($strName, str_replace("\"", "&quot;", $strValue));
                        } else {
                            if (!$this->_sDefaultFocus) {
                                $this->_sDefaultFocus = $strName;
                            }
                            $this->countInputName($strName);
                            $strTmp .= "<input type=\"".$strType."\" class=\"text\" name=\"".$strName."\" id=\"".$strName."\" size=\"".($iMaxLength + 1)."\" maxlength=\"".($iMaxLength * 2)."\"";
                            $strTmp .= " onchange=\"document.getElementById('imgTitle".$strName."').src='".$this->_sLibPath.Pelican::$config['LIB_MEDIA']."/image_title.php?text=' + escape(document.getElementById('".$strName."').value).replace('+','%2b') + '&size=".$iSize."&preview=1'\"";
                            if ($strValue != "") {
                                $strTmp .= " value=\"".str_replace("\"", "&quot;", $strValue)."\"";
                            }
                            $strTmp .= " />";
                            $strTmp .= Pelican_Html::br().$img;
                            // img = window.open(libDir+Pelican::$config['LIB_MEDIA']."/image_title.php?text=" + escape(text) + "&size=" + size + "&preview=1", 'image_title', 'width=700,height=500,toolbar=no,status=no,resizable=yes,menubar=no,scrollbars=no');
                            if (!$bFormOnly) {
                                $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
                            }
                            // Génération de la fonction js de vérification.
                            if ($bRequired) {
                                $this->_aIncludes["text"] = true;
                                $this->_sJS .= "if ( ";
                                if (!$bRequired) {
                                    // Si le champ n'est pas requis, ne faire la vérification que si le champ n'est pas vide.
                                    $this->_sJS .= "!isBlank(obj.".$strName.".value) ";
                                }
                                $this->_aIncludes["text"] = true;
                                $this->_sJS .= "isBlank(obj.".$strName.".value) ";
                                $this->_sJS .= ") {\n";
                                $this->_sJS .= "alert(\"".t('FORM_FILL')." \\"."\"".(strip_tags(str_replace("\"", "\\"."\"", $strLib)))."\\"."\"";
                                $this->_sJS .= ".\");\n";
                                $this->_sJS .= "fwFocus(obj.".$strName.");\n";
                                $this->_sJS .= "return false;\n";
                                $this->_sJS .= "}\n";
                            }
                        }

                        return $this->output($strTmp);
                    }

                    /**
                     * Appel à une mediathèque ou à une popup d'upload (gestion de fichiersde type
                     * "image", "file" ou "flash" avec gestion ou non en base de données et
                     * génération ou non de vignettes à la volée.
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param string $strType (option) Type de fichier (image, file ou flash) :
                     * "image" par défaut
                     * @param string $strSubFolder (option) Sous-répertoire de départ (chemin
                     * relatif par rapport au répertoire d'upload) : "" par défaut
                     * @param string $strValue (option) Valeur du champ : "" par défaut
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param bool $bLibrary (option) Utilisation de la Pelican_Media library (true)
                     * ou d'une popup d'upload (false) : true par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     *
                     * @return string
                     */
                    public function createMedia($strName, $strLib, $bRequired = false, $strType = "image", $strSubFolder = "", $strValue = "", $bReadOnly = false, $bLibrary = true, $bFormOnly = false)
                    {
                        $aAllowedExtensions = Pelican_Media::getAllowedExtensions();
                        // Génération du couple libellé/champ
                        $strTmp = $this->createHidden($strName, $strValue);
                        if (!$bReadOnly) {
                            $strTmp .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
                            $strTmp .= "<tr>";
                            $strTmp .= "<td width=\"2\" id=\"div".$strName."\" nowrap=\"nowrap\">";
                        }
                        // Récupération de la prévisualisation (vignette si l'option est choisie dans la config) et du chemin du fichier s'il existe
                        $strPathValue = $strValue;
                        if ($strType == 'youtube') {
                            if ($strValue) {
                                $details = Pelican_Cache::fetch("Service/Youtube", array('id', $strValue, date("M-d-Y", mktime())));
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
                            } elseif ($strTypePrecis == "youtube") {
                                $linkMedia = "<img src=\"".$details['path']."\" style=\"border : 1px solid #CCCCCC\" alt=\"".str_replace(" ", Pelican_Html::nbsp(), $strFile)."\" ".$height." />";
                                $url = $details['url'];
                            } else {
                                $linkMedia = str_replace(" ", Pelican_Html::nbsp(), $strFile);
                            }
                            $strTmp .= "<a id=\"imgdiv".$strName."\" href=\"".$url."\" target=\"_blank\">".$linkMedia."</a>".Pelican_Html::nbsp().Pelican_Html::nbsp();
                        }
                        if (!$bReadOnly) {
                            $this->_aIncludes["popup"] = true;
                            $strTmp .= "</td>";
                            if (is_array($strType)) {
                                $strTmp .= "<td>";
                                foreach ($strType as $type) {
                                    //C'est ici que sont crées les boutons add
                                    $strTmp .= "<input type=\"button\" class=\"button\" value=\"".t('FORM_BUTTON_ADD')." ".($type == "image" ? "une " : "un ").$type."\"";
                                    $strTmp .= " onclick=\"popupMedia('".$type."', '".$this->_sLibPath.Pelican::$config['LIB_MEDIA']."/', this.form.elements['".$strName."'], 'div".$strName."', '";
                                    if ($strSubFolder != "") {
                                        $strTmp .= $strSubFolder;
                                    }
                                    $strTmp .= "','".str_replace("/", "\/", $this->_sUploadHttpPath."/")."',''";
                                    if ($bLibrary) {
                                        $strTmp .= ",true";
                                    }
                                    $strTmp .= ");\" />&nbsp;";
                                }
                            } else {
                                $strTmp .= "<td style=\"vertical-align:top;\"><input type=\"button\" class=\"button\" value=\"".t('FORM_BUTTON_ADD')."\"";
                                $strTmp .= " onclick=\"popupMedia('".$strType."', '".$this->_sLibPath.Pelican::$config['LIB_MEDIA']."/', this.form.elements['".$strName."'], 'div".$strName."', '";
                                if ($strSubFolder != "") {
                                    $strTmp .= $strSubFolder;
                                }
                                $strTmp .= "','".str_replace("/", "\/", $this->_sUploadHttpPath."/")."',''";
                                if ($bLibrary) {
                                    $strTmp .= ",true";
                                }
                                $strTmp .= ");\" />";
                            }
                            $strTmp .= Pelican_Html::nbsp()."<input type=\"button\" class=\"button\" value=\"".t('FORM_BUTTON_FILE_DELETE')."\"";
                            $strTmp .= " onclick=\"if(confirm('".t('FORM_MSG_CONFIRM_DEL')."')) {this.form.elements['".$strName."'].value=''; document.getElementById('div".$strName."').innerHTML = '';}\" />";
                            $strTmp .= "</td>";
                            $strTmp .= "</tr>\n";
                            $strTmp .= "</table>\n";
                            // Génération de la fonction js de vérification.
                            if ($bRequired) {
                                $this->_aIncludes["text"] = true;
                                $this->_sJS .= "if ( isBlank(obj.".$strName.".value) ) {\n";
                                $this->_sJS .= "alert(\"".t('FORM_MSG_VALUE_FILE')." "."\\"."\"".strip_tags(str_replace("\"", ""."\\"."\"", $strLib)).""."\\"."\".\");\n";
                                $this->_sJS .= "return false;\n";
                                $this->_sJS .= "}\n";
                            }
                        }
                        if (!$bFormOnly) {
                            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
                        }

                        return $this->output($strTmp);
                    }

                    /**
                     * Génère un contrôle de sélection de fichier joint de type fichier via la
                     * médiathèque : identique à un appel à createMedia avec le type "file".
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param string $strSubFolder (option) Chemin absolu du sous dossier à utiliser
                     * @param string $strValue (option) Valeur du champ
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     */
                    public function createFile($strName, $strLib, $bRequired = false, $strSubFolder = "", $strValue = "", $bReadOnly = false, $bFormOnly = false)
                    {
                        return $this->createMedia($strName, $strLib, $bRequired, "file", $strSubFolder, $strValue, $bReadOnly, true, $bFormOnly);
                    }

                    /**
                     * Génère un contrôle de sélection de fichier joint de type image via la
                     * médiathèque : identique à un appel à createMedia avec le type "image".
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param string $strSubFolder (option) Chemin absolu du sous dossier à utiliser
                     * @param string $strValue (option) Valeur du champ
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     */
                    public function createImage($strName, $strLib, $bRequired = false, $strSubFolder = "", $strValue = "", $bReadOnly = false, $bFormOnly = false)
                    {
                        return $this->createMedia($strName, $strLib, $bRequired, "image", $strSubFolder, $strValue, $bReadOnly, true, $bFormOnly);
                    }

                    /**
                     * Génère un contrôle de sélection de fichier joint de type flash via la
                     * médiathèque : identique à un appel à createMedia avec le type "flash".
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param string $strSubFolder (option) Chemin absolu du sous dossier à utiliser
                     * @param string $strValue (option) Valeur du champ
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     */
                    public function createFlash($strName, $strLib, $bRequired = false, $strSubFolder = "", $strValue = "", $bReadOnly = false, $bFormOnly = false)
                    {
                        return $this->createMedia($strName, $strLib, $bRequired, "flash", $strSubFolder, $strValue, $bReadOnly, true, $bFormOnly);
                    }

                    /**
                     * Génère un contrôle de sélection de fichier joint par upload direct, par
                     * défaut tout type de fichier est autorisé.
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param bool $bRequired (option) Champ obligatoire : false par défaut
                     * @param string $strType (option) Type de fichier : all, image, file, flash
                     * @param string $strSubFolder (option) Chemin absolu du sous dossier à utiliser
                     * @param string $strValue (option) Valeur du champ
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     */
                    public function createUpload($strName, $strLib, $bRequired = false, $strType = "all", $strSubFolder = "", $strValue = "", $bReadOnly = false, $bFormOnly = false)
                    {
                        return $this->createMedia($strName, $strLib, $bRequired, $strType, $strSubFolder, $strValue, $bReadOnly, false, $bFormOnly);
                    }

                    /**
                     * Génère un tableau croisé avec checkbox ou radio.
                     *
                     * @access public
                     *
                     * @param Pelican_Db $oConnection Objet connection à la base
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param string $strQueryColumn Requete de recuperation des abscisses
                     * @param string $strQueryRow Requete de recuperation des ordonnees
                     * @param string $strQueryData Requete de recuperation des valeurs selectionnes
                     * @param string $iFilterID (option) = ""
                     * @param string $strFilterColumn (option) = ""
                     * @param bool $bHelpButtons (option) Affiche les boutons pour cocher les cases
                     * automatiquement : true par défaut
                     * @param bool $bRadio (option) 
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     *
                     * @return string
                     */
                    public function createTabCroiseGenerique($oConnection, $strName, $strLib, $strQueryColumn, $strQueryRow, $strQueryData, $iFilterID = "", $strFilterColumn = "", $bHelpButtons = true, $bRadio = false, $bReadOnly = false, $bFormOnly = false)
                    {
                        $iColSpan = 1;
                        $strTmp = '';
                        $bRequired = false;
                        $strFiltre = '';
                        if ($bReadOnly) {
                            $bHelpButtons = false;
                            if ($strQueryData) {
                                $oConnection->Query($strQueryData);
                                $aFiltre = $oConnection->data["id_row"];
                            }
                            if (!is_array($aFiltre)) {
                                $aFiltre = array();
                            }
                        }
                        if ($bHelpButtons) {
                            $strBoutons = "<img src=\"".$this->_sLibPath.$this->_sLibForm."/images/check_all.gif\" width=\"20\" height=\"21\" align=\"absmiddle\" onmouseover=\"ChangeImg(this, 'check_all_over');\" onmouseout=\"ChangeImg(this, 'check_all');\" onMouseDown=\"ChangeImg(this, 'check_all_click');\" onclick=\"GlobalCheck(document.".$this->sFormName.", '".$strName."', '%%COL%%', '%%ROW%%', 'check', ".($bRadio ? "true" : "false").");\"\" />";
                            if (!$bRadio) {
                                $strBoutons .= Pelican_Html::nbsp().str_replace("check", "uncheck", $strBoutons);
                            }
                            $this->_aIncludes["crosstab"] = true;
                            if (!$bRadio) {
                                $iColSpan++;
                            }
                        }
                        // Abscisses
                        if (!is_array($strQueryColumn)) {
                            $oConnection->Query($strQueryColumn);
                            if ($oConnection->data) {
                                if ($bRadio) {
                                    $aAbscisses["0"] = t('NONE');
                                    $iColSpan += 2;
                                }
                                while (list($key, $value) = each($oConnection->data["id"])) {
                                    $aAbscisses[$value] = $oConnection->data["lib"][$key];
                                    $iColSpan += 2;
                                }
                            } else {
                                $aAbscisses = array();
                            }
                        } else {
                            $aAbscisses = $strQueryColumn;
                            $iColSpan += count($aAbscisses) * 2;
                        }
                        // Ordonnées
                        $oConnection->Query($strQueryRow);
                        if ($oConnection->data) {
                            while (list($key, $value) = each($oConnection->data["id"])) {
                                if (!$bReadOnly || ($bReadOnly && in_array($value, $aFiltre))) {
                                    $aOrdonnees[$value] = $oConnection->data["lib"][$key];
                                }
                            }
                        } else {
                            $aOrdonnees = array();
                        }
                        // Filtre
                        if (($strFilterColumn != "") && ($iFilterID != "")) {
                            $oConnection->Query("select ".str_replace($this->_sTableSuffixeId, $this->_sTableSuffixeLabel, $strFilterColumn)." as \"lib\" from ".$this->_sTablePrefix.str_replace($this->_sTableSuffixeId, "", $strFilterColumn)." where ".$strFilterColumn." = ".$iFilterID);
                            $strFiltre = $oConnection->data["lib"][0];
                            $strTmp .= $this->createHidden($strName."_Filter", $iFilterID);
                            $strTmp .= $this->createHidden($strName."_FilterC", $strFilterColumn);
                        }
                        // ?? $strTmp .= $this->createHidden($strName . $this->_sTableSuffixeId, $iID);
                        // ?? $strTmp .= $this->createHidden($strName . $this->_sTableSuffixeId . "C", $strIDColumn);
                        $strTmp .= $this->createHidden($strName."_is_radio", ($bRadio ? "1" : ""));
                        if (!is_array($strQueryData)) {
                            if (strlen($strQueryData) != 0) {
                                // Lecture des données
                                $strSQL = $strQueryData;
                                if (($strFilterColumn != "") && ($iFilterID != "")) {
                                    $strSQL .= " AND ".$strFilterColumn." = ".$iFilterID;
                                }
                                $oConnection->Query($strSQL);
                                $aData = array();
                                if ($oConnection->rows != 0) {
                                    while (list($key, $value) = each($oConnection->data["id_row"])) {
                                        $aData[$value][$oConnection->data["id_col"][$key]] = 1;
                                    }
                                }
                            } else {
                                $aData = array();
                            }
                        } else {
                            $aData = $strQueryData;
                        }
                        $columnWidth = (1 / (count($aAbscisses) + 2)) * 100;
                        $strTmp .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"100%\" class=\"tableaucroise\">";
                        // En-tête
                        $strTmp .= "<tr><td align=\"center\" class=\"croiselib\"";
                        if ($bHelpButtons) {
                            if (!$bRadio) {
                                $strTmp .= " colspan=\"2\"";
                            }
                            $strTmp .= " rowspan=\"2\"";
                        }
                        $strTmp .= " >".Pelican_Html::nbsp();
                        $strTmp .= $strFiltre;
                        if ($bHelpButtons && !$bRadio) {
                            $strTmp .= strtr($strBoutons, array("%%COL%%" => "", "%%ROW%%" => ""));
                        }
                        $strTmp .= Pelican_Html::nbsp()."</td>";
                        if ($bHelpButtons) {
                            $strTmp2 = "<tr>";
                        }
                        foreach ($aAbscisses as $key => $value) {
                            $strTmp .= "<td align=\"center\"  class=\"croiselib\">".$value."</td>";
                            $strTmp .= "<td style=\"width:1px\"></td>";
                            if ($bHelpButtons) {
                                $strTmp2 .= "<td align=\"center\" class=\"croiselib\">".strtr($strBoutons, array("%%COL%%" => $key, "%%ROW%%" => ""))."</td>";
                            }
                            $strTmp2 .= "<td style=\"width:1px\"></td>";
                        }
                        // Affichage des cases
                        if ($bHelpButtons) {
                            $strTmp .= $strTmp2."</tr>\n";
                        }
                        $strTmp .= "</tr>\n";
                        $strTmp .= "<tr><td colspan=\"".$iColSpan."\" style=\"height:1px;\"></td></tr>\n";
                        if ($aOrdonnees) {
                            foreach ($aOrdonnees as $iY => $strLibY) {
                                $strTmp .= "<tr>";
                                $strTmp .= "<td  class=\"croiselib\">".$strLibY."</td>";
                                if ($bHelpButtons && !$bRadio) {
                                    $strTmp .= "<td align=\"center\"  class=\"croiselib\">".strtr($strBoutons, array("%%COL%%" => "", "%%ROW%%" => $iY))."</td>";
                                }
                                reset($aAbscisses);
                                $this->countInputName($strName."_Y".$iY);
                                if (!$aData[$iY] && $bRadio) {
                                    $aData[$iY][0] = 1;
                                }
                                while (list($iX, $strLibX) = each($aAbscisses)) {
                                    $strTmp .= "<td align=\"center\"  class=\"croiseval\">";
                                    $checked = false;
                                    if (isset($aData[$iY][$iX])) {
                                        if ($aData[$iY][$iX] === 1) {
                                            $checked = true;
                                        }
                                    }
                                    if ($bReadOnly) {
                                        $strTmp .= ($checked ? "X" : "");
                                    } else {
                                        if ($bRadio) {
                                            $strTmp .= "<input type=\"Radio\" name=\"".$strName."_Y".$iY."\" value=\"".$iX."\"".($checked ? " checked=\"checked\"" : "")." />";
                                        } else {
                                            $strTmp .= "<input type=\"Checkbox\" name=\"".$strName."_Y".$iY."_X".$iX."\" id=\"".$strName."_Y".$iY."_X".$iX."\" value=\"1\"".($checked ? " checked=\"checked\"" : "")." />";
                                        }
                                    }
                                    $strTmp .= "</td>";
                                    $strTmp .= "<td style=\"width:1px;\"></td>";
                                }
                                $strTmp .= "</tr>\n";
                                $strTmp .= "<tr><td colspan=\"".$iColSpan."\" style=\"height:1px;\"></td></tr>\n";
                            }
                        }
                        $strTmp .= "</table>\n";
                        if (!$bFormOnly) {
                            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, $bRequired, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
                        }
                        if ((!$aData && !$bReadOnly) || $aData) {
                            return $this->output($strTmp);
                        } else {
                            return "";
                        }
                    }

                    /**
                     * Affiche une ligne avec un HR.
                     *
                     * @access public
                     *
                     * @param string $strColor1 (option) Couleur du HR du premier <TD>
                     * @param string $strColor2 (option) Couleur du HR du second <TD>
                     * @param string $colspan (option) 
                     *
                     * @return string
                     */
                    public function createHR($strColor1 = "", $strColor2 = "", $colspan = "")
                    {
                        /*
                        $lib = Pelican_Html::hr(array(style=>"border: 1px solid ".$strColor1));
                        $val = Pelican_Html::hr(array(style=>"border: 1px solid ".$strColor2));
                        $strTmp = Pelican_Html_Form::get($lib,$val,false, false, $this->sStyleLib, $this->sStyleVal, "top", "", $this->_sFormDisposition);
                        */
                        $strTmp = "<tr>";
                        $strTmp .= "<td class=\"".$this->sStyleLib."\"><hr style=\"height: 1px; border: 1px solid ".$strColor1."\"></td>";
                        if ($this->_sFormDisposition != "vertical") {
                            $strTmp .= "<td ";
                            if ($colspan) {
                                $strTmp .= " colspan=\"".$colspan."\" ";
                            }
                            $strTmp .= "class=\"".$this->sStyleVal."\"><hr style=\"height: 1px; border: 1px solid ".$strColor2."\"></td>";
                        }
                        $strTmp .= "</tr>\n";

                        return $this->output($strTmp);
                    }

                    /**
                     * Retourne une chaine en commentaire Pelican_Html.
                     *
                     * @access public
                     *
                     * @param string $txtComment Texte a mettre en commentaire Pelican_Html
                     *
                     * @return string
                     */
                    public function createHtmlComment($txtComment)
                    {
                        $strTmp = "<!-- ".$txtComment." -->";

                        return $this->output($strTmp);
                    }

                    /**
                     * Crée un séparateur dans le formulaire.
                     *
                     * @access public
                     *
                     * @param string $strClass (option) Classe CSS à utiliser. exemple
                     * {background-color: #CFD6E7; border: 0px solid; line-height: 0px; padding: 0px
                     * 0px 0px 0px;}
                     * @param bool $bDirectOutput (option) 
                     *
                     * @return string
                     */
                    public function showSeparator($strClass = "formsep", $bDirectOutput = true)
                    {
                        $colspan = "";
                        if ($this->_sFormDisposition != "vertical") {
                            $colspan = "colspan=\"2\"";
                        }
                        $strTmp = "<tr><td class=\"".$strClass."\" ".$colspan.">".Pelican_Html::nbsp()."</td></tr>\n";
                        if ($this->bDirectOutput && $bDirectOutput) {
                            print($strTmp);

                            return '';
                        } else {
                            return $strTmp;
                        }
                    }

                    /**
                     * Création d'un objet Multiple : répétition à volonté d'un bout de formulaire
                     * avec ses contrôles de saisie.
                     *
                     * ATTENTION : inclure xt_mozilla_fonctions en tout premier (avant tout autre js)
                     * pour pouvoir utiliser cette méthode avec Mozilla
                     *
                     * @access public
                     *
                     * @param Pelican_Db $oConnection Objet connection à la base
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param string $fileName CHemin d'accès au fichier de formulaire à multiplier
                     * @param mixed $tabValues Tableau de données (de type queryTab)
                     * @param string $incrementField Nom du champ servant à incrémenter les
                     * instances de l'objet
                     * @param bool $bReadOnly (option) Affiche uniquement les valeurs et pas les
                     * champs : false par défaut
                     * @param int $intMaxIterations (option) Nombre maximum d'itérations autorisé :
                     * "" par défaut
                     * @param bool $bAllowDeletion (option) Suppression d'instance autorisée ou non :
                     * true par défaut
                     * @param bool $bAllowAdd (option) Ajout d'instance autorisé ou non : true par
                     * défaut
                     * @param string $strPrefixe (option) Préfixe des noms de champ : "multi" par
                     * défaut
                     * @param string $line (option) Nom du tableau de données utilisé par le
                     * formulaire parent : "values" par défaut
                     * @param string $strCss (option) Classe CSS à utiliser : "multi" par défaut
                     * @param mixed $sColspan (option) 
                     * @param string $sButtonAddMulti (option) Libellé du boutton ajouter du multi
                     * @param string $complement (option) 
                     *
                     * @return string
                     */
                    public function createMulti($oConnection, $strName, $strLib, $fileName, $tabValues, $incrementField, $bReadOnly = false, $intMaxIterations = "", $bAllowDeletion = true, $bAllowAdd = true, $strPrefixe = "multi", $line = "values", $strCss = "multi", $sColspan = "2", $sButtonAddMulti = "", $complement = "")
                    {
                        global $_GET, $_POST, $_SERVER, $HTTP_SESSION_VARS;
                        // Nécessite $multi, $values
                        // ATTENTION : ajouter aux noms des champs
                        // on annule temporairement le direct output s'il est défini
                        // affichage d'un séparateur
                        // affichage du bouton pour les ajouts multiples
                        // $limit=limitFormTable("120", "520", false);
                        // souvent utilisé : $readO
                        $oForm = & $this;
                        $readO = $bReadOnly;
                        ob_start();
                        $this->showSeparator("formsep", true, $sColspan);
                        $sep = ob_get_contents();
                        ob_end_clean();
                        $strTmp = $sep;
                        $strTmp .= "<tr><td id=\"td_".$strName."\" colspan=\"".$sColspan."\" width=\"100%\">";
                        // boucle sur le formulaire multiple à partir du tableau de données
                        $compteur = - 1;
                        ob_start();
                        if (!is_array($tabValues)) {
                            $tabValues = array();
                        }
                        $strPrefixe2 = $strPrefixe;
                        foreach ($tabValues as $$line) {
                            $compteur++;
                            if ($compteur % 2) {
                                $strCss2 = "background-color=#F9FDF3;";
                            } else {
                                $strCss2 = "background-color=#FAEADA;";
                            }
                            $$strPrefixe = $strPrefixe2.$compteur."_";
                            echo("<table cellspacing=\"0\" cellpadding=\"0\" style='".$strCss2."' class=\"".$strCss."\" id=\"".$$strPrefixe."multi_table\" width=\"100%\">");
                            $multi = $$strPrefixe;
                            include $this->_sRootLibPath.$this->_sLibForm."/include_multi.php";
                            // encadrement du js
                            $this->_sJS .= "if (document.getElementById('".$$strPrefixe."multi_display')) {\n if (document.getElementById('".$$strPrefixe."multi_display').value) {\n";
                            include $fileName;
                            // fin du js
                            $this->_sJS .= "}\n}\n";
                            echo("</table>\n");
                        }
                        $HTML_content = ob_get_contents();
                        ob_end_clean();
                        $strTmp .= $HTML_content;
                        $strTmp .= $this->createHidden("prefixe_".$strName, $strPrefixe);
                        $strTmp .= $this->createHidden("increment_".$strName, $incrementField);
                        $strTmp .= $this->createHidden("count_".$strName, (count($tabValues) - 1));
                        if ($intMaxIterations) {
                            $strTmp .= $this->createHidden("max_".$strName, $intMaxIterations);
                        }
                        $this->countInputName($strName);
                        $strTmp .= "<iframe  name=\"iframe_".$strName."\" id=\"iframe_".$strName."\" width=\"0\" height=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" frameborder=\"0\"></iframe>";
                        $strTmp .= "</td></tr>\n";
                        if (!$bReadOnly && $bAllowAdd) {
                            $lib = Pelican_Html::input(array(name => $strName, type => "button", "class" => "buttonmulti", value => ($sButtonAddMulti ? $sButtonAddMulti : t('FORM_BUTTON_ADD_MULTI')." ".Pelican_Text::htmlentities($strLib)), style => "width:200px;", onclick => "addMulti(document.".$this->sFormName.", '".$strName."','".$fileName."','".$strPrefixe2."',document.".$this->sFormName.".count_".$strName.".value,'".$intMaxIterations."',".($bAllowDeletion ? "true" : "false").",'".$complement."')"));
                            $strTmp .= Pelican_Html_Form::get($lib, "", false, false, $this->sStyleLib, $this->sStyleVal, "", "center", $this->_sFormDisposition);
                            $strTmp .= $sep;
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

                        return $this->output($strTmp);
                    }

                    /**
                     * Création d'un objet Multiple : répétition à volonté d'un bout de formulaire
                     * avec ses contrôles de saisie.
                     *
                     * ATTENTION : inclure xt_mozilla_fonctions en tout premier (avant tout autre js)
                     * pour pouvoir utiliser cette méthode avec Mozilla
                     *
                     * @access public
                     *
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param mixed $call 
                     * @param mixed $tabValues Tableau de données (de type queryTab)
                     * @param string $incrementField Nom du champ servant à incrémenter les
                     * instances de l'objet
                     * @param bool $bReadOnly (option) Affiche uniquement les valeurs et pas les
                     * champs : false par défaut
                     * @param int $intMaxIterations (option) Nombre maximum d'itérations autorisé :
                     * "" par défaut
                     * @param bool $bAllowDeletion (option) Suppression d'instance autorisée ou non :
                     * true par défaut
                     * @param bool $bAllowAdd (option) Ajout d'instance autorisé ou non : true par
                     * défaut
                     * @param string $strPrefixe (option) Préfixe des noms de champ : "multi" par
                     * défaut
                     * @param string $line (option) Nom du tableau de données utilisé par le
                     * formulaire parent : "values" par défaut
                     * @param string $strCss (option) Classe CSS à utiliser : "multi" par défaut
                     * @param mixed $sColspan (option) 
                     * @param string $sButtonAddMulti (option) Libellé du boutton ajouter du multi
                     * @param string $complement (option) 
                     *
                     * @return string
                     */
                    public function createMultiHmvc_old($strName, $strLib, $call, $tabValues, $incrementField, $bReadOnly = false, $intMaxIterations = "", $bAllowDeletion = true, $bAllowAdd = true, $strPrefixe = "multi", $line = "values", $strCss = "multi", $sColspan = "2", $sButtonAddMulti = "", $complement = "")
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
                        $oForm = & $this;
                        $readO = $bReadOnly;
                        $bDirectOutput = $oForm->bDirectOutput;
                        $oForm->bDirectOutput = false;
                        $strTmp .= $this->showSeparator("formsep", true, $sColspan);
                        $strTmp .= "<tr><td id=\"td_".$strName."\" colspan=\"".$sColspan."\" width=\"100%\">";
                        // boucle sur le formulaire multiple à partir du tableau de données
                        $compteur = - 1;
                        if (!is_array($tabValues)) {
                            $tabValues = array();
                        }
                        $strPrefixe2 = $strPrefixe;
                        foreach ($tabValues as $line) {
                            $compteur++;
                            if ($compteur % 2) {
                                $strCss2 = "background-color=#F9FDF3;";
                                $color = "#F9FDF3";
                            } else {
                                $strCss2 = "background-color=#FAEADA;";
                                $color = "#FAEADA";
                            }
                            $$strPrefixe = $strPrefixe2.$compteur."_";
                            $strTmp .= "<table bgcolor=\"".$color."\"cellspacing=\"0\" cellpadding=\"0\" style='".$strCss2."' class=\"".$strCss."\" id=\"".$$strPrefixe."multi_table\" width=\"100%\">";
                            $multi = $$strPrefixe;
                            $strTmp .= self::headMulti($multi, $compteur, $readO, $bAllowDeletion);
                            // encadrement du js
                            $this->_sJS .= "if (document.getElementById('".$$strPrefixe."multi_display')) {\n if (document.getElementById('".$$strPrefixe."multi_display').value) {\n";
                            // retro compatibite
                            if (!empty($call['path'])) {
                                include_once $call['path'];
                            }
                            //hmvc
                            $strTmp .= call_user_func_array(array($call['class'], $call['method']), array($oForm, $line, $bReadOnly, $multi));
                            // fin du js
                            $this->_sJS .= "}\n}\n";
                            $strTmp .= "</table>\n";
                        }
                        $strTmp .= $this->createHidden("prefixe_".$strName, $strPrefixe);
                        $strTmp .= $this->createHidden("increment_".$strName, $incrementField);
                        $strTmp .= $this->createHidden("count_".$strName, (count($tabValues) - 1));
                        if ($intMaxIterations) {
                            $strTmp .= $this->createHidden("max_".$strName, $intMaxIterations);
                        }
                        $this->countInputName($strName);
                        $strTmp .= "<iframe  name=\"iframe_".$strName."\" id=\"iframe_".$strName."\" width=\"0\" height=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" frameborder=\"0\"></iframe>";
                        $strTmp .= "</td></tr>\n";
                        if (!$bReadOnly && $bAllowAdd) {
                            $lib = Pelican_Html::input(array(name => $strName, type => "button", "class" => "buttonmulti", value => ($sButtonAddMulti ? $sButtonAddMulti : t('FORM_BUTTON_ADD_MULTI')." ".Pelican_Text::htmlentities($strLib)), style => "width:200px;", onclick => "addMulti(document.".$this->sFormName.", '".$strName."','".$call['path'].",".$call['class'].",".$call['method']."','".$strPrefixe2."',document.".$this->sFormName.".count_".$strName.".value,'".$intMaxIterations."',".($bAllowDeletion ? "true" : "false").",'".$complement."')"));
                            $strTmp .= Pelican_Html_Form::get($lib, "", false, false, $this->sStyleLib, $this->sStyleVal, "", "center", $this->_sFormDisposition);
                            $strTmp .= $sep;
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

    public function createMultiHmvc($strName, $strLib, $call, $tabValues, $incrementField, $bReadOnly = false, $intMinMaxIterations = "", $bAllowDeletion = true, $bAllowAdd = true, $strPrefixe = "multi", $line = "values", $strCss = "multi", $sColspan = "2", $sButtonAddMulti = "", $complement = "")
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
                            include_once $call['path'];
                        }
                        //Decoupe avec les _
                        $strCut = explode("_", $strName);
        $strTmp .= $this->showSeparator("formsep", true, $sColspan);
        $strTmp .= $this->createLabel(t(end($strCut)), '');
        $strTmp .= '<tr><td id="'.$strName.'_td" colspan="'.$sColspan.'" width="100%">';

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
        $strTmp .= self::headMultiHmvc($strName.'__CPT__', '__CPT1__', $readO, $bAllowDeletion);
        $strTmp .= call_user_func_array(array($call['class'], $call['method']), array($oForm, array(), $bReadOnly, $strName."__CPT___"));
        $strTmp .= $this->putHidden();
        $strTmp .= '</table>';

                        // Remise en place des hidden
                        $this->form_class_hidden = $saveHidden;

                        // Remise en place des vérifications JS existante
                        $strTmp .= $this->createHidden($strName."_subFormJS", $this->_sJS, true);
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

                $strTmp .= "<table id=\"".$strName.$compteur."_subForm\"  bgcolor=\"".$color."\"cellspacing=\"0\" cellpadding=\"0\" style='".$strCss2."' class=\"".$strName."_subForm ".$strCss."\" width=\"100%\">";

                $strTmp .= self::headMultiHmvc($strName.$compteur, $compteur, $readO, $bAllowDeletion);
                                // encadrement du js
                                $this->_sJS .= "if (document.getElementById('".$strPrefixe.$compteur."_multi_display')) {\n if (document.getElementById('".$strPrefixe.$compteur."_multi_display').value) {\n";
                                // retro compatibite

                                //hmvc
                                $strTmp .= call_user_func_array(array($call['class'], $call['method']), array($oForm, $line, $bReadOnly, $strName.$compteur.'_'));
                                // fin du js
                                $this->_sJS .= "}\n}\n";
                $strTmp .= "</table>\n";
            }
        }

        $strTmp .= $this->createHidden("increment_".$strName, $incrementField);
        $strTmp .= $this->createHidden("count_".$strName, (count($tabValues) - 1));

                        // Gestion du minimum
                        if ($intMinIterations) {
                            $this->_sJS .= "var count = eval($('#count_".$strName."').val() || 0) + 1;\n";
                            $this->_sJS .= "if (count < ".$intMinIterations.") {\n";
                            $strMessage = t('MIN_ITERATION_1').$intMinIterations.t('MIN_ITERATION_1')." \\"."\"".(strip_tags(str_replace("\"", "\\"."\"", $strLib)))."\\"."\"";
                            $this->_sJS .= "    alert(\"".$strMessage."\")\n";
                            //$this->_sJS.= "alert(\"" . t('FORM_MSG_VALUE_REQUIRE') . " \\" . "\"" . (strip_tags(str_replace("\"", "\\" . "\"", $strLib))) . "\\" . "\"";
                            $this->_sJS .= "    return false;\n";
                            $this->_sJS .= "}\n";
                        }
        if ($intMaxIterations) {
            $strTmp .= $this->createHidden("max_".$strName, $intMaxIterations);
        }

        $strTmp .= "</td></tr>\n";

        if (!$bReadOnly && $bAllowAdd) {
            $lib = Pelican_Html::input(array(name => $strName, type => "button", "class" => "buttonmulti", value => ($sButtonAddMulti ? $sButtonAddMulti : t('FORM_BUTTON_ADD_MULTI')." ".Pelican_Text::htmlentities($strLib)), style => "width:200px;", onclick => "addClone('".$strName."','".$intMaxIterations."')"));
            $strTmp .= Pelican_Html_Form::get($lib, "", false, false, $this->sStyleLib, $this->sStyleVal, "", "center", $this->_sFormDisposition);
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
                     * .
                     *
                     * @access public
                     *
                     * @param mixed $multi 
                     * @param mixed $compteur 
                     * @param mixed $readO 
                     * @param mixed $bAllowDeletion 
                     *
                     * @return mixed
                     */
                    public function headMultiHmvc($multi, $compteur, $readO, $bAllowDeletion)
                    {
                        $return = '';
                        if (!isset($readO)) {
                            $readO = false;
                        }
                        if ($bAllowDeletion) {
                            $compteur = is_int($compteur) ? ($compteur + 1) : $compteur;
                            $return .= $this->createLabel(" n° ".$compteur, ($readO ? "" : "<input type=\"button\" class=\"buttonmulti\" value=\"".t('FORM_BUTTON_FILE_DELETE')."\" onclick=\"delClone('".$multi."', ".$compteur.")\" />"));
                        }
                        $return .= $this->createHidden($multi."_multi_display", "1", true);

                        return $return;
                    }

                    /**
                     * .
                     *
                     * @access public
                     *
                     * @param mixed $multi 
                     * @param mixed $compteur 
                     * @param mixed $readO 
                     * @param mixed $bAllowDeletion 
                     *
                     * @return mixed
                     */
                    public function headMulti($multi, $compteur, $readO, $bAllowDeletion)
                    {
                        $return = '';
                        if (!isset($readO)) {
                            $readO = false;
                        }
                        if ($bAllowDeletion) {
                            $return .= $this->createLabel(" n° ".($compteur + 1), ($readO ? "" : "<input type=\"button\" class=\"buttonmulti\" value=\"".t('FORM_BUTTON_FILE_DELETE')."\" onclick=\"delMulti('".$multi."')\" />"));
                        }
                        $return .= $this->createHidden($multi."multi_display", "1");

                        return $return;
                    }

                    /**
                     * Création d'un sous formulaire : utilisation autonome d'un bout de formulaire
                     * avec ses contrôles de saisie (permet un rechargement dynamique de ce bout de
                     * formulaire via comobo par exemple).
                     *
                     * @access public
                     *
                     * @param Pelican_Db $oConnection Objet connection à la base
                     * @param string $strName Nom du champ
                     * @param string $strLib Libellé du champ
                     * @param string $fileName CHemin d'accès au fichier de formulaire à multiplier
                     * @param mixed $tabValues (option) Tableau de données (de type queryTab)
                     * @param bool $bReadOnly (option) Affiche uniquement la valeur et pas le champ
                     * (créé un input hidden) : false par défaut
                     * @param bool $bFormOnly (option) Génération du champ uniquement, sans libellé
                     * : false par défaut
                     * @param string $strJsVar (option) Nom de la fonction js à exécuter au
                     * changement du formulaire
                     * @param string $strCss (option) Classe CSS à utiliser : "formsub" par défaut
                     *
                     * @return string
                     */
                    public function createSubForm($oConnection, $strName, $strLib, $fileName, $tabValues = "", $bReadOnly = false, $bFormOnly = false, $strJsVar = "subformjs", $strCss = "formsub")
                    {
                        global $_GET, $_POST, $_SERVER;
                        $oForm = & $this;
                        $strTmp = "";
                        $readO = $bReadOnly;
                        $values = $tabValues;
                        if ($strJsVar) {
                            $strTmp .= $this->createHidden("js_".$strName, $strJsVar);
                        }
                        $strTmp .= $this->createHidden("file_".$strName, $fileName);
                        $this->countInputName("iframe_".$strName);
                        $strTmp .= "<iframe name=\"iframe_".$strName."\" id=\"iframe_".$strName."\" width=\"0\" height=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" frameborder=\"0\"></iframe>";
                        $this->countInputName($strName);
                        $strTmp .= "<div id=\"".$strName."\" name=\"".$strName."\" ".($readO ? "" : "class=\"".$strCss."\"").">";
                        ob_start();
                        include $fileName;
                        $HTML_content .= ob_get_contents();
                        ob_end_clean();
                        $strTmp .= $HTML_content;
                        $strTmp .= "</div>";
                        if ($strJsVar) {
                            $strTmp .= Pelican_Html::script($$strJsVar);
                        }
                        if (!$bFormOnly) {
                            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, false, $bReadOnly, $this->sStyleLib, $this->sStyleVal, "", "", $this->_sFormDisposition);
                        }
                        $this->_aIncludes["sub"] = true;

                        return $this->output($strTmp);
                    }

                    /**
                     * .
                     *
                     * @access public
                     *
                     * @param string $strName 
                     * @param string $strLib 
                     * @param mixed $call 
                     * @param string $tabValues (option) 
                     * @param bool $bReadOnly (option) 
                     * @param bool $bFormOnly (option) 
                     * @param string $strJsVar (option) 
                     * @param string $strCss (option) 
                     *
                     * @return mixed
                     */
                    public function createSubFormHmvc($strName, $strLib, $call, $tabValues = "", $bReadOnly = false, $bFormOnly = false, $strJsVar = "subformjs", $strCss = "formsub")
                    {
                        global $_GET, $_POST, $_SERVER;
                        $oForm = & $this;
                        $strTmp = "";
                        $readO = $bReadOnly;
                        $values = $tabValues;
                        if ($strJsVar) {
                            $strTmp .= $this->createHidden("js_".$strName, $strJsVar);
                        }
                        $strTmp .= $this->createHidden("file_".$strName, $call['path'].','.$call['class'].','.$call['method']);
                        $this->countInputName("iframe_".$strName);
                        $strTmp .= "<iframe name=\"iframe_".$strName."\" id=\"iframe_".$strName."\" width=\"0\" height=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" frameborder=\"0\"></iframe>";
                        $this->countInputName($strName);
                        $strTmp .= "<div id=\"".$strName."\" name=\"".$strName."\" ".($readO ? "" : "class=\"".$strCss."\"").">";
                        if (!empty($call['path'])) {
                            include_once $call['path'];
                        }
                        $strTmp .= call_user_func_array(array($call['class'], $call['method']), array($oForm, $tabValues, $bReadOnly));
                        $strTmp .= "</div>";
                        if ($strJsVar) {
                            $strTmp .= Pelican_Html::script($$strJsVar);
                        }
                        if (!$bFormOnly) {
                            $strTmp = Pelican_Html_Form::get(Pelican_Text::htmlentities($strLib), $strTmp, false, $readO, $this->sStyleLib, $this->sStyleVal, "", "", $this->_sFormDisposition);
                        }
                        $this->_aIncludes["sub"] = true;

                        return $this->output($strTmp);
                    }

                    /**
                     * Ajout du code javascript à la pile de vérification.
                     *
                     * @access public
                     *
                     * @param string $strCode Code à ajouter (L'objet obj correspond au formulaire)
                     */
                    public function createJS($strCode)
                    {
                        $this->_sJS .= $strCode."\n";
                    }

    public function createTooltip($strName, $strTooltip, $readOnly = false) {
        if (!$readOnly) {
            $this->_aIncludes['tooltip'] = true;
            $this->_aTooltip[$strName] = $strTooltip;
        }
    }

                    /**
                     * Déclaration d'un onglet.
                     *
                     * <code>
                     * $oForm = Pelican_Factory::getInstance('Form',true);
                     * $oForm->setTab("1", t('Global parameters'));
                     * $oForm->setTab("2", t('Fonctionnalities'));
                     * $oForm->open(Pelican::$config["DB_PATH"]);
                     * $oForm->beginTab("1");
                     * $oForm->createInput("SITE_LABEL", t('FIRST_NAME'), 100, "", true,
                     * $values["SITE_LABEL"], $readO, 100);
                     * $oForm->createInput("SITE_TITLE", t('Titre des pages'), 100, "", true,
                     * $values["SITE_TITLE"], $readO, 100);
                     * $oForm->beginTab("2");
                     * $oForm->createCheckBoxFromList("SITE_PRESERVE_DNS", "Préserver le DNS",
                     * array("1" => ""), $values["SITE_PRESERVE_DNS"], false, $readO, "h");
                     * $oForm->endTab();
                     * beginFormTable();
                     * neginForm($oForm);
                     * endForm($oForm);
                     * endFormTable();
                     * $oForm->close();
                     * </code>
                     *
                     * @access public
                     *
                     * @param string $strId Identifiant de l'onglet
                     * @param string $strLabel Libellé de l'onglet
                     */
                    public function setTab($strId, $strLabel)
                    {
                        $this->aTab[$strId] = array("id" => $strId, "label" => $strLabel);
                    }

                    /**
                     * Affichage des onglets définis par la méthode setTab.
                     *
                     * @access public
                     *
                     * @return string
                     */
                    public function drawTab()
                    {
                        if ($this->aTab) {
                            $oTab = Pelican_Factory::getInstance('Form.Tab', "tab".$this->sFormName);
                            foreach ($this->aTab as $tab) {
                                if (!isset($this->activatedTab)) {
                                    $this->activatedTab = $tab["id"];
                                    $strScript = "<script type=\"text/javascript\">
						var currentTab='".$tab["id"]."';
						var formTab = '".$this->sFormName."';
						function ongletFW(id) {
							if (document.getElementById(formTab + '_tab_' + id)) {
								tabSwitch(currentTab, 'off'); /** l'ancien */
								tabSwitch(id, 'on'); /** le nouveau */
								currentTab = id;
							}
						}
						</script>";
                                }
                                $oTab->addTab($tab["label"], $this->sFormName."_".$tab["id"], ($this->activatedTab == $tab["id"]), "", "ongletFW('".$tab["id"]."')", "", "petit");
                            }
                            $tab = Pelican_Html::div(array("class" => "petit_onglet_bas", width => "100%"), $oTab->getTabs());
                            $strTmp = $strScript.$tab.Pelican_Html::br(array("class" => "after_tab"));
                            $this->displayTab = true;

                            return $this->output($strTmp);
                        }
                    }

                    /**
                     * Point de départ d'un onglet dans le formulaire.
                     *
                     * La première fois que la méthode est appelée, la totalité des onglets est
                     * affichée. La fermeture de l'onglet précédent est automatique
                     *
                     * @access public
                     *
                     * @param string $strId Identifiant de l'onglet
                     *
                     * @return string
                     */
                    public function beginTab($strId)
                    {
                        $strTmp = "";

                        /* Si aucun onglet n'a encore été affiché, on le fait */
                        if (!isset($this->displayTab)) {
                            $strTmp .= $this->drawTab();
                        }

                        /* si l'onglet précédent n'est pas fermé, on le ferme */
                        if (isset($this->currentTabId)) {
                            $strTmp .= $this->endTab();
                        }

                        /* définiton de l'onglet courant */
                        $this->currentTabId = $strId;
                        $strTmp .= "<div id=\"".$this->sFormName."_tab_".$strId."\" class=\"div_onglet\" style=\"";
                        if ($this->activatedTab == $strId) {
                            $strTmp .= "display:block;";
                        } else {
                            $strTmp .= "display:none;";
                        }
                        $strTmp .= "\">";
                        $strTmp .= beginFormTable("0", "0", "form", false, $strId);

                        return $this->output($strTmp);
                    }

                    /**
                     * Fermeture d'un onglet.
                     *
                     * @access public
                     *
                     * @return string
                     */
                    public function endTab()
                    {
                        $strTmp = "";
                        if ($this->currentTabId) {
                            $this->currentTabId = "";
                            $strTmp .= endFormTable(false);
                            $strTmp .= "</div>";
                        }

                        return $this->output($strTmp);
                    }

                    /**
                     * Crée les input Hidden cumulés au fur et à mesure.
                     *
                     * @access public
                     *
                     * @return string
                     */
                    public function putHidden()
                    {
                        //		global $form_class_hidden;
                        if (isset($this->aEditor)) {
                            if (!$this->countInputName("editorImageList[]")) {
                                $this->createHidden("editorImageList[]", implode("#", $this->aEditor));
                            }
                        }
                        if ($this->form_class_hidden) {
                            //ksort($form_class_hidden);
                            return $this->output(implode("", $this->form_class_hidden));
                        }
                    }

                    /**
                     * .
                     *
                     * @access public
                     *
                     * @param mixed $js 
                     *
                     * @return mixed
                     */
                    public function setJquery($js)
                    {
                        $head = Pelican_Factory::getView()->getHead();
                        $head->setJquery($js);
                    }

                    /**
                     * Ferme le formulaire, met les fonctions js de vérification.
                     *
                     * @access public
                     *
                     * @param string $_sJSPath (option) Chemin relatif à partir de la page en cous,
                     * ou absolu, où trouver les fonctions javascript :
                     * "/library/Pelican/Form/public/js/" par défaut
                     *
                     * @return string
                     */
                    public function close($_sJSPath = "")
                    {
                        if (is_array($this->_aMultiTrackNames) && count($this->_aMultiTrackNames) > 0) {
                            $sMultiTrackNames = implode(',', $this->_aMultiTrackNames);
                            $strTmp = $this->createHidden('TRACK_MULTINAMES', $sMultiTrackNames);
                        }
                        /** CSRF */
                        $strTmp.= $this->createCsrfInput();
                        $strTmp = $this->putHidden();
                        $this->endJS = '';
                        if (!$_sJSPath) {
                            $_sJSPath = $this->_sLibPath.$this->_sLibForm."/js/";
                        }
                        $strTmp .= "</form>\n";
                        /***** init de tinyMCE ***************/
                        if ($this->tinymce && $this->aEditor) {
                            $this->showEditor = true;
                            $strTmp .= Pelican_Html::script(array(src => Pelican::$config["LIB_PATH"].$this->sLibOther."/tiny_mce/tiny_mce.js"));
                            $ed = $this->getTiny();
                            $strTmp .= Pelican_Html::script($ed);
                        }
                        /***** fin init tinyMCE **************/

                        /* virtual keyboard */
                        if ($this->bVirtualKeyboard && $this->_InputVK && empty($_GET['readO'])) {
                            $strTmp .= $this->createVirtualKeyboard($this->_InputVK[0], true);
                        }
                        if ($this->suggest) {
                            $strTmp .= "<div style=\"top: 45px; left: 243px; width:202px;\" id=\"search_suggest\"></div>";
                            $strTmp .= "<link rel=\"STYLESHEET\" type=\"text/css\" href=\"".$this->_sLibPath.$this->_sLibForm."/css/suggest.css\">";
                        }
                        $strTmp .= Pelican_Html::script(array(src => $_sJSPath."ajax.js"));
                        while ($ligne = each($this->_aIncludes)) {
                            if ($ligne["value"]) {
                                switch ($ligne["key"]) {
                                    case "num": {
                                                $strTmp .= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_num_controls.js\"></script>\n";
                                            break;
                                        }
                                    case "text": {
                                            $strTmp .= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_text_controls.js\"></script>\n";
                                            break;
                                        }
                                    case "color": {
                                            $strTmp .= "<script type=\"text/javascript\">
                        	\$(document).ready( function() {
								\$(\".colors\").miniColors({
								});
							});
                        	</script>";
                                            break;
                                        }
                                    case "date": {
                                            $strTmp .= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_date_controls.js\"></script>\n";
                                            $strTmp .= "<script type=\"text/javascript\">
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
                                            $strTmp .= "<script type=\"text/javascript\">var dateLanguageFormat='".t('DATE_FORMAT_DB')."';</script>\n";
                                            break;
                                        }
                                    case "list": {
                                            $strTmp .= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_list_fonctions.js\"></script>\n";
                                            break;
                                        }
                                    case 'ordered_list': {
                                            $strTmp .= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_ordered_list_fonctions.js\"></script>\n";
                                            break;
                                        }
                                    case "popup": {
                                            $strTmp .= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_popup_fonctions.js\"></script>\n";
                                            break;
                                        }
                                    case "crosstab": {
                                            $strTmp .= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_crosstab_fonctions.js\"></script>\n";
                                            break;
                                        }
                                    case "multi": {
                                            $strTmp .= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_multi_fonctions.js\"></script>\n";
                                            break;
                                        }
                                    case "sub": {
                                            $strTmp .= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_sub_fonctions.js\"></script>\n";
                                            break;
                                        }
                                    case "suggest": {
                                            $strTmp .= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_suggest_fonctions.js\"></script>\n";
                                            break;
                                        }
                                    case "tooltip": {
                                        $strTmp .= "<script type=\"text/javascript\" src=\"/library/External/tipped/js/tipped/tipped.js\"></script>\n";
                                        $strTmp .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"/library/External/tipped/css/tipped/tipped.css\" />
\n";
                                        break;
                                    }
                    case "virtualkeyboard":
                    {
                        $strTmp .= "<script type=\"text/javascript\" src=\"/library/External/tiny_mce/plugins/Jsvk/jscripts/vk_popup.js?vk_skin=textual\"></script>\n";
                        break;
                    }
                                    case "map": {
                                            $strTmp .= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_map_fonctions.js\"></script>\n";
                                            break;
                                        }
                                    case "mapv3": {
                                            $strTmp .= "<script type=\"text/javascript\" src=\"".$_sJSPath."xt_map_fonctions_v3.js\"></script>\n";
                                            break;
                                        }
                                    }
                            }
                        }
                        $strTmp .= "<script type=\"text/javascript\">";
                        if ($this->_bUseMulti) {
                            $strTmp .= "var ".$this->sCheckFunction."_multi=new Function(\"obj\",\"return true\");\n";
                        }
                        $strTmp .= "var activeInput;\n";
                        $strTmp .= "function ".$this->sCheckFunction." (obj) {\n";
                        $strTmp .= $this->_sJS;
                            /*$strTmp .= "
                            try{
                            console.log(obj);
                            return false;
                            }catch(e){};
                            ";*/
                            if ($this->sCheckFunction) {
                                if ($this->_bUseMulti) {
                                    $strTmp .= "return ".$this->sCheckFunction."_multi(obj);\n";
                                } else {
                                    if ($this->bBlockSubmit) {
                                        $strTmp .= $this->sCheckFunction." = blockSubmit;\nreturn true;\n";
                                    }
                                }
                            }
                        $strTmp .= "}\n";
                            // Mise en place du blockage de soumission multiple
                            $strTmp .= "function blockSubmit(){\nreturn false;\n}\n";
                            // Gestion du focus
                            $strTmp .= "function fwFocus(obj){\nobj.focus();\n}\n";
                            // Mise en place du focus par défaut
                        if ($this->_sDefaultFocus && $_SERVER["SCRIPT_NAME"] != $this->_sLibPath.$this->_sLibForm."/popup_multi.php") {
                            $strTmp .= "if (document.".$this->sFormName."[\"".$this->_sDefaultFocus."\"].style.display != \"none\") {\n";
                            $strTmp .= "if (document.".$this->sFormName."[\"".$this->_sDefaultFocus."\"].disabled) {\n";
                            $strTmp .= "fwFocus(document.".$this->sFormName."[\"".$this->_sDefaultFocus."\"]);\n";
                            $strTmp .= "}\n";
                            $strTmp .= "}\n";
                        }
                        if ($this->displayTab) {
                            $strTmp .= $this->getJsTab();
                        }
                        if ($this->suggest) {
                            foreach ($this->suggest as $name => $val) {
                                $this->endJS .= "buildSearch('".$name."',Array('".implode("','", str_replace("'", "\\'", $val))."'));\n";
                            }
                        }
                        if ($this->map) {
                            foreach ($this->map as $name => $val) {
                                $initMap[] = "mapControl('".$name."');";
                            }
                            $this->endJS .= "if ( window.addEventListener ) {
                                window.addEventListener('load', function(){ ".implode("\n", $initMap)." }, false);
                            } else {
                                if ( window.attachEvent ) {
                                    window.attachEvent('onload', function(){ ".implode("\n", $initMap)." } );
                                }
                            }
                            ";
                        }
                        if (!empty($this->_aTooltip)) {
                            foreach ($this->_aTooltip as $name => $tooltip) {
                                $aTooltipJs[] = "Tipped.create('#".$name."', '".str_replace("'","\\'",$tooltip)."', { position: 'left' });";
                            }
                            $this->endJS .= "\$(document).ready( function() {\n".implode("\n",$aTooltipJs)."\n});\n";
                        }

                        $strTmp .= $this->endJS."</script>\n";
                        $this->controlDuplicateInputName();
                        if (Pelican::$config['HMVC']) {
                            $strTmp .= '<script src="'.Pelican::$config["LIB_PATH"].Pelican::$config['LIB_FORM'].'/js/hmvc.js'.'" type="text/javascript"></script>';
                        }

                        return $this->output($strTmp);
                    }

                        public function createCsrfInput() {
                            $return = $this->createHidden('csrf_name', $this->sFormName.md5($_SERVER['REQUEST_URI']));
                            $return .= $this->createHidden('csrf_token', Pelican_Security::getCsrfToken($this->sFormName.md5($_SERVER['REQUEST_URI'])));
                            return $return;
                        }

                            public function getJsTab()
                            {
                                return "
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
                                   // rechereche de l'onget parent
                                   var parent = $(obj).parents('.div_onglet');
                                   if (parent.length != 0) {
                                      id = parent.attr('id').replace(formTab + '_tab_','');
                                      if (currentTab != id) {
                                        ongletFW(id);
                                      }
                                    }
                                    ori.focus();
                                  }
                                  fwFocus = tabFocus\r\n";
                            }
                        /**
                         * .
                         *
                         * @access public
                         *
                         * @param string $strName 
                         *
                         * @return mixed
                         */
                        public function countInputName($strName)
                        {
                            if (!isset($this->_inputName[$strName])) {
                                $this->_inputName[$strName] = 0;
                            }
                            $this->_inputName[$strName]++;
                        }

                        /**
                         * Méthode permettant d'indiquer si des input portenet le même nom au sein du
                         * formulaire => affiche un message d'erreur si c'est le cas.
                         *
                         * @access public
                         *
                         * @return mixed
                         */
                        public function controlDuplicateInputName()
                        {
                            $errorInput = array();
                            foreach ($this->_inputName as $input => $count) {
                                if ($count > 1 && substr($input, -2) != "[]") {
                                    $errorInput[$input] = $count;
                                }
                            }
                            if ($errorInput) {
                                debug($errorInput, "ATTENTION : Des champs portent le même nom");
                            }
                        }

                        /**
                         * .
                         *
                         * @access public
                         *
                         * @param mixed $sValue 
                         * @param bool $bRequired (option) 
                         * @param bool $bReadOnly (option) 
                         *
                         * @return mixed
                         */
                        public function getCellLib($sValue, $bRequired = false, $bReadOnly = false)
                        {
                            $label = Pelican_Text::htmlentities($sValue).(($bRequired && !$bReadOnly) ? $this->_sRequiredIndicator : "");

                            return Pelican_Html::td(array("class" => $this->sStyleLib), $label);
                        }

                        /**
                         * .
                         *
                         * @access public
                         *
                         * @param mixed $sValue 
                         *
                         * @return mixed
                         */
                        public function getCellVal($sValue)
                        {
                            return Pelican_Html::td(array("class" => $this->sStyleVal), $sValue);
                        }

                        /**
                         * .
                         *
                         * @access public
                         *
                         * @param mixed $pageId 
                         * @param string $typeId (option) 
                         * @param string $id (option) 
                         *
                         * @return mixed
                         */
                        public function getPageOrder($pageId, $typeId = "", $id = "")
                        {
                            $return = Pelican_Html::img(array(onclick => "popupSort('".$pageId."','".$typeId."', '".$id."');", src => "/library/public/images/sort.gif", border => 0, alt => "Ordre d'affichage", width => 17, height => 18, align => "center", hspace => 5, style => "cursor:pointer;"));

                            return $return;
                        }

                        /**
                         * .
                         *
                         * @access public
                         *
                         * @return mixed
                         */
                        public function getTiny()
                        {
                            global $_EDITOR;
                            $return = "
createToggleButtons();
//création des boutons de switch on/off de l'editeur en mode page
function createToggleButtons()
{
  var Listelement = '".implode(',', $this->aEditor)."';
  var mySplitResult = Listelement.split(',');
		//on liste tout les editeurs présent dans le tableau
		for(i = 0; i < mySplitResult.length; i++){


				textareas = document.getElementById(mySplitResult[i]);

					var editorAction = document.createElement('a');
 					var ActionLabel = document.createTextNode('[".addslashes(Pelican_Text::unhtmlentities(t('text_editor_activate')))."]');
 					editorAction.appendChild(ActionLabel);
 					editorAction.id = mySplitResult[i]+'_BT';
 					editorAction.onclick = toggleTinyMCE;
 					editorAction.style.cursor = 'pointer';
				   textareas.parentNode.insertBefore(img_element, textareas);
				   textareas.style.display = 'none';


		}
}
// funcion de switch on/off
function toggleTinyMCE(event)
{
  //ie
  if(window.event) {
	var editor_button = window.event.srcElement;
  } else {
	var editor_button = event.target;
  }
  var editor_id = editor_button.nextSibling.id;

  textareas = document.getElementById(editor_id);
  textareas.style.display = 'inline';


  if(!tinyMCE.get(editor_id))
  {
		  tinyMCE.init({
			// General options
			theme : 'advanced',
			language : '".strtolower(getLangueCode($user->getFavoriteLanguage()))."',
			mode : 'exact',
			//elements : '".implode(',', $this->aEditor)."',
			elements : editor_id,
			entity_encoding : 'raw',
			fix_nesting : true,
			visual : true,
			CssPath : '".$_EDITOR["CSS"]."',
			MediaHttpPath : '".$_EDITOR["MEDIA_HTTP"]."',
			MediaVarPath : '".$_EDITOR["MEDIA_VAR"]."',
			MediaLibPath : '".$_EDITOR["MEDIA_LIB_PATH"]."',
			PalettePath : '".$_EDITOR["PALETTE_PATH"]."',
			PaletteColumns : '".$_EDITOR["PALETTE_COLUMNS"]."',
			PaletteList : '".$_EDITOR["PALETTE_FILES"]."',
			styleNames : '".$_EDITOR["FONTSTYLE"]["ID"]."',
			styleLibs : '".$_EDITOR["FONTSTYLE"]["LIB"]."',
			fontFormat : '".$_EDITOR["FONTFORMAT"]["LIB"]."',
			fontFormatNames : '".$_EDITOR["FONTFORMAT"]["ID"]."',
			fontFormatLibs : '".$_EDITOR["FONTFORMAT"]["LIB"]."',
			fontList : '".$_EDITOR["FONTNAME"]["LIB"]."',
			sizeList : '".$_EDITOR["FONTSIZE"]["ID"]."',
			sizeLibs : '".$_EDITOR["FONTSIZE"]["LIB"]."',
			plugins : 'bramus_cssextras,liststyle,betd_file,betd_orderedlist,betd_mailto,betd_flash,betd_mediadirect,betd_save,betd_media,betd_icons,betd_internallink,safari,style,table,inlinepopups,media,searchreplace,print,contextmenu,paste,visualchars,nonbreaking,xhtmlxtras,advimage,advlink',
			force_br_newlines : false,
        	forced_root_block : '', // Needed for 3.x,
			// Theme options
			".$buttontiny."
			extended_valid_elements : 'object[classid|codebase|width|height|align],param[name|value],embed[quality|type|pluginspage|width|height|src|align]',

			media_use_script : true,

			//content_css : '".$_EDITOR["CSS"]."',
			content_css : '".Pelican::$config["DESIGN_HTTP"]."/css/editeur.css',
			//config pour css bramus
			//bramus_cssextras_classesstring : 'li::ul[Carre,Cube,Sphere];li::ol[Decimal,A-B-C,I-II-III]',
			//bramus_cssextras_idsstring : ''
		});

	}

  if(tinyMCE.activeEditor && tinyMCE.activeEditor.getContainer().style.display != 'none')
  {
		Activetextareas = document.getElementById(tinyMCE.activeEditor.id);
		TinyContent= tinyMCE.activeEditor.getContent();

		var old_editor = tinyMCE.activeEditor.id;

		// on modifie le label du lien
		var editorAction = document.getElementById(tinyMCE.activeEditor.id+'_BT');
		var oldLabel = editorAction.firstChild;
		var ActionLabel = document.createTextNode('[".addslashes(Pelican_Text::unhtmlentities(t('text_editor_activate')))."]');
		editorAction.removeChild(oldLabel);
		editorAction.appendChild(ActionLabel);

		tinyMCE.activeEditor.hide();
		Activetextareas.style.display = 'none';
		editorIframe = document.getElementById('iframeText'+tinyMCE.activeEditor.id);
		editorIframe.style.display = 'inline';
		editorIframe.contentWindow.document.body.innerHTML=TinyContent;
  }
  else
  {
		editorIframe = document.getElementById('iframeText'+editor_id)
		editorIframe.style.display = 'none';
		// on modifie le label du lien
		var editorAction = document.getElementById(editor_id+'_BT');
		var oldLabel = editorAction.firstChild;
		var ActionLabel = document.createTextNode('[".addslashes(t('text_editor_deactivate'))."]');
		editorAction.removeChild(oldLabel);
		editorAction.appendChild(ActionLabel);
		if (tinyMCE.get(editor_id))
			tinyMCE.get(editor_id).show();
  }
}";

                            return $return;
                        }

                        /**
                         * .
                         *
                         * @access public
                         *
                         * @param string $strName 
                         * @param string $strLib 
                         * @param mixed $autocompleteDb 
                         * @param mixed $objectId 
                         * @param mixed $objectTypeId 
                         * @param string $groupId (option) 
                         *
                         * @return mixed
                         */
                        public function inputTaxonomy($strName = 'TAXONOMY', $strLib = "Taxonomie :", $autocompleteDb = '', $objectId, $objectTypeId, $groupId = "")
                        {
                            require_once 'Pelican/Taxonomy.php';
                            $oTaxonomy = Pelican_Factory::getInstance('Taxonomy');
                            $strTmp = $oTaxonomy->generateFormInput($strName, $strLib, $autocompleteDb, $objectId, $objectTypeId, $groupId);

                            return $this->output($strTmp);
                        }

                        /**
                         * Fonction pour faire un explode des valeurs d'un champ et le remplacer par son
                         * tableau de valeur
                         * Utilisé par exemple pour une saisie multiligne dans un textarea.
                         *
                         * @access public
                         *
                         * @param string $strValue 
                         * @param string $strSep (option) Caractère séparateur
                         */
                        public function splitTextarea($strValue, $strSep = "\r\n")
                        {

                            /* Si Le champ contient des valeurs, on fait le traitement */
                            if ($strValue) {
                                $aTemp = array_unique(explode($strSep, $strValue));
                                if ($aTemp) {
                                    $strValue = array();
                                    foreach ($aTemp as $value) {
                                        if (trim($value)) {
                                            $strValue[] = $value;
                                        }
                                    }
                                }
                            }

                            return $strValue;
                        }

                        /**
                         * Mise à jour des données liées à un objet de Tableau Croisé.
                         *
                         * @access public
                         *
                         * @param Pelican_Db $oConnection Objet connection à la base
                         * @param string $strName Nom du champ de formulaire
                         * @param int $iID Id de l'enregistrement
                         * @param string $strQueryColumn Requete de recup des id abscisse
                         * @param string $strQueryRow Requete de recup des id ordonnee
                         * @param string $strAbsFieldName Nom de la colonne id abscisse
                         * @param string $strOrdFieldName Nom de la colonne id ordonnee
                         * @param string $strTableName Nom de la table de liaison
                         * @param string $strIDFieldName Nom de la colonne id enregistrement
                         */
                        public function recordTabCroiseGenerique($oConnection, $strName, $iID = "", $strQueryColumn, $strQueryRow, $strAbsFieldName, $strOrdFieldName, $strTableName, $strIDFieldName)
                        {
                            if ($iID != "") {
                                // Abscisses
                                $oConnection->Query($strQueryColumn);
                                if ($oConnection->data) {
                                    $aAbscisses = $oConnection->data["id"];
                                } else {
                                    $aAbscisses = array();
                                }
                                // Ordonnées
                                $oConnection->Query($strQueryRow);
                                if ($oConnection->data) {
                                    $aOrdonnees = $oConnection->data["id"];
                                    $listeOrdonnees = str_replace("null", "0", implode(",", $aOrdonnees));
                                    if ($listeOrdonnees != "") {
                                        $strSQL = "delete from ".$strTableName." where ".$strIDFieldName." = '".$iID."' AND ".$strOrdFieldName." in (".$listeOrdonnees.")";
                                        $iFilter = Pelican_Db::$values[$strName."_Filter"];
                                        if ((Pelican_Db::$values[$strName."_FilterC"] != "") && ($iFilter != "")) {
                                            $strSQL .= " and ".Pelican_Db::$values[$strName."_FilterC"]." = ".$iFilter;
                                        }
                                        $oConnection->Query($strSQL);
                                    }
                                } else {
                                    $aOrdonnees = array();
                                }
                                Pelican_Db::$values[$strIDFieldName] = $iID;
                                if (Pelican_Db::$values[$strName."_is_radio"]) {
                                    foreach ($aOrdonnees as $iY) {
                                        if (isset(Pelican_Db::$values[$strName."_Y".$iY])) {
                                            Pelican_Db::$values[$strAbsFieldName] = Pelican_Db::$values[$strName."_Y".$iY];
                                            Pelican_Db::$values[$strOrdFieldName] = $iY;
                                            if (Pelican_Db::$values[$strName."_Y".$iY]) {
                                                $oConnection->insertQuery($strTableName);
                                            }
                                        }
                                    }
                                } else {
                                    foreach ($aOrdonnees as $iY) {
                                        foreach ($aAbscisses as $iX) {
                                            if (isset(Pelican_Db::$values[$strName."_Y".$iY."_X".$iX])) {
                                                Pelican_Db::$values[$strAbsFieldName] = $iX;
                                                Pelican_Db::$values[$strOrdFieldName] = $iY;
                                                $oConnection->insertQuery($strTableName);
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        /**
                         * Manipulation des données issues d'un POST pour créer une entrée associée à
                         * chaque instance de l'objet multiple.
                         *
                         * Un tableau de type Array(PREFIXE_CHAMP1_1, PREFIXE_CHAMP1_2, PREFIXE_CHAMP2_2)
                         * crée un tableaau du type Array(1=>CHAMP1,2=>(CHAMP1, CHAMP2))
                         *
                         * @access public
                         *
                         * @param string $strName Identifiant de l'objet défini dans le createMulti
                         * @param string $strPrefixe (option) Préfixe urilisé pour les nom de champs de
                         * l'objet multiple : "multi" par défaut
                         */
                        public static function readMulti($strName, $strPrefixe = "multi")
                        {
                            global $longueur;
                            if ($strPrefixe) {
                                if (isset($_POST['count_multi'.(Pelican_Db::$values['page'] - 1).'_'.$strPrefixe])) {
                                    Pelican_Db::$values['count_'.$strPrefixe] = $_POST['count_multi'.(Pelican_Db::$values['page'] - 1).'_'.$strPrefixe];
                                }
                            }
                            $DELETE = array();
                            $longueur = strlen($strPrefixe);
                            $count = (Pelican_Db::$values["count_".$strName] + 1);
                            if ($count) {
                                for ($j = 0;$j < $count + $supp;$j++) {
                                    if (isset(Pelican_Db::$values[$strPrefixe.$j.'_multi_display'])) {
                                        if (!Pelican_Db::$values[$strPrefixe.$j.'_multi_display']) {
                                            $supp++;
                                        }
                                    }
                                }
                                foreach (Pelican_Db::$values as $key => $value) {
                                    $field = "";
                                    if (substr($key, 0, $longueur) == $strPrefixe) {
                                        for ($j = 0;$j < $count + $supp;$j++) {
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
                         * ---------------------------------------
                         * Fonctions pour créer un tableau encadrant le formulaire.
                         *
                         * ---------------------------------------
                         *
                         * *
                         * Création du début d'un tag Table adapté à la classe Pelican_Form
                         *
                         * @access public
                         *
                         * @param string $cellpadding (option) Marg interne des cellules : "0" par défaut
                         * @param string $cellspacing (option) Espacement entre les cellules :"0" par
                         * défaut
                         * @param string $class (option) Classe css pour la table : "form" par défaut
                         * @param string $id (option) Identifiant du tag TABLE
                         *
                         * @return string
                         */
                        public function beginFormTable($cellpadding = "0", $cellspacing = "0", $class = "form", $id = "")
                        {
                            $strTmp = "<table border=\"0\" cellspacing=\"".$cellspacing."\" cellpadding=\"".$cellpadding."\" class=\"".$class."\" id=\"tableClassForm".$id."\" summary=\"Formulaire\">";
                            if ($this->bDirectOutput) {
                                echo($strTmp);

                                return true;
                            } else {
                                return $strTmp;
                            }
                        }

                        /**
                         * Création d'une ligne de tableau avec des images d'1 pixel de hauteur pour figer
                         * les dimensions du tableau d'affichage du formulaire.
                         *
                         * @access public
                         *
                         * @param string $Width1 (option) Largeur pour les libellés : "120" par défaut
                         * @param string $Width2 (option) Largeur pour les valeurs : "520" par défaut
                         *
                         * @return string
                         */
                        public function limitFormTable($Width1 = "120", $Width2 = "520")
                        {
                            $strTmp = "<tr><td height=\"1\"><img src=\"".Pelican::$config["LIB_PATH"]."/public/images/pixel.gif\" width=\"".$Width1."\" height=\"1\" alt=\"\" border=\"0\" /></td><td height=\"1\"><img src=\"".Pelican::$config["LIB_PATH"]."/public/images/pixel.gif\" width=\"".$Width2."\" height=\"1\" alt=\"\" border=\"0\" /></td></tr>\n";
                            if ($this->bDirectOutput) {
                                echo($strTmp);

                                return true;
                            } else {
                                return $strTmp;
                            }
                        }

                        /**
                         * Tag TABLE de fin de formulaire.
                         *
                         * @access public
                         *
                         * @return string
                         */
                        public function endFormTable()
                        {
                            $strTmp = "</table>\n";
                            if ($this->bDirectOutput) {
                                echo($strTmp);

                                return true;
                            } else {
                                return $strTmp;
                            }
                        }

    public static function getToken($nom = '')
    {
        $token = uniqid(rand(), true);
        $_SESSION['csrf'][$nom . '_token'] = $token;
        $_SESSION['csrf'][$nom . '_token_time'] = time();

        return $token;
    }


}

                    /*
                    ----------------------------------------
                    Fonctions pour créer une Pelican_Index_Frontoffice_Zone "toggle"
                    ---------------------------------------
                    */

                    /**
                     * Création d'un toggle.
                     *
                     * @param string $id Identifiant du toggle
                     * @param string $label Libellé du toggle
                     * @param string $content (option) Contenu du toggle : "" par défaut
                     * @param bool $closed (option) 
                     * @param bool $setCookie (option) 
                     * @param bool $bDirectOutput (option) 
                     */
                    function createToggle($id, $label, $content = "", $closed = true, $setCookie = false, $bDirectOutput = true)
                    {
                        $return = beginToggle($id, $label, $closed, $setCookie, false);
                        $return .= $content;
                        $return .= endToggle(false);
                        if ($bDirectOutput) {
                            echo($return);
                        } else {
                            return $return;
                        }
                    }

                    /**
                     * Tag de début d'un toggle.
                     *
                     * @param string $id Identifiant du toggle
                     * @param string $label Libellé du toggle
                     * @param bool $closed (option) 
                     * @param bool $setCookie (option) 
                     * @param bool $bDirectOutput (option) True pour un affichage direct, false pour
                     * que les méthodes retournent le code Pelican_Html sous forme de texte
                     *
                     * @return string
                     */
                    function beginToggle($id, $label, $closed = true, $setCookie = false, $bDirectOutput = true)
                    {
                        if ($closed) {
                            $state = "none";
                        }
                        if ($state == "none") {
                            $image = "close";
                            $alt = t('AFFICHER');
                        } else {
                            $image = "open";
                            $alt = t('MASQUER');
                        }
                        if ($setCookie) {
                            $strSetCookie = "true";
                        } else {
                            $strSetCookie = "false";
                        }
                        $strTemp = beginFormTable("0", "0", "formtoggle", false, "toggle");
                        $strTemp .= "<tr ondblclick=\"showHideModule('".$id."', ".$strSetCookie.")\">";
                        $strTemp .= "<td class=\"formtoggle\" width=\"14\" valign=\"middle\"><nobr>".$label;
                        $strTemp .= Pelican_Html::nbsp().Pelican_Html::nbsp();
                        $strTemp .= "<img id=\"Toggle".$id."\" src=\"".Pelican::$config['LIB_PATH']."/public/images/toggle_".$image.".gif\" alt=\"".$alt."\" hspace=\"3\" width=\"14\" height=\"12\" border=\"0\" style=\"cursor:pointer;\" onclick=\"showHideModule('".$id."', ".$strSetCookie.")\" /></td>";
                        $strTemp .= "</nobr></tr>";
                        $strTemp .= endFormTable(false);
                        $strTemp .= "<div id=\"DivToggle".$id."\" style=\"display:".$state."\">";
                        if ($bDirectOutput) {
                            echo($strTemp);
                        } else {
                            return $strTemp;
                        }
                    }

                    /**
                     * Tag de fin d'un toggle.
                     *
                     * @param bool $bDirectOutput (option) True pour un affichage direct, false pour
                     * que les méthodes retournent le code Pelican_Html sous forme de texte
                     *
                     * @return string
                     */
                    function endToggle($bDirectOutput = true)
                    {
                        $strTemp = ("</div>");
                        if ($bDirectOutput) {
                            echo($strTemp);
                        } else {
                            return $strTemp;
                        }
                    }

                    /**
                     * .
                     *
                     * @param string $strId 
                     * @param string $strLabel 
                     *
                     * @return mixed
                     */
                    function setTab($strId, $strLabel)
                    {
                        global $aTab;
                        $aTab[$strId] = array("id" => $strId, "label" => Pelican_Text::htmlentities($strLabel));
                    }

                    /**
                     * .
                     *
                     * @param string $strId 
                     * @param mixed $name 
                     *
                     * @return mixed
                     */
                    function beginTab($strId, $name)
                    {
                        global $currentTabId, $activatedTab;

                        /* définiton de l'onglet courant */
                        $currentTabId = $strId;
                        $strTmp = "<div id=\"fForm_tab_".$strId."\" style=\"";
                        if ($activatedTab[$name] == $strId) {
                            $strTmp .= "display:block;";
                        } else {
                            $strTmp .= "display:none;";
                        }
                        $strTmp .= "\">";
                        $strTmp .= beginFormTable("0", "0", "form", false, $strId);

                        return $strTmp;
                    }

                    /**
                     * Fermeture d'un onglet.
                     *
                     * @return string
                     */
                    function endTab()
                    {
                        global $currentTabId;
                        $strTmp = "";
                        if ($currentTabId) {
                            $currentTabId = "";
                            $strTmp .= endFormTable(false);
                            $strTmp .= "</div>";
                        }

                        return $strTmp;
                    }

                    /**
                     * .
                     *
                     * @param mixed $name 
                     *
                     * @return mixed
                     */
                    function drawTab($name)
                    {
                        global $aTab, $activatedTab;
                        if ($aTab) {
                            pelican_import('Index.Tab');
                            $oTab = Pelican_Factory::getInstance('Form.Tab', "tab".$name);
                            foreach ($aTab as $tab) {
                                if (!$activatedTab[$name]) {
                                    $activatedTab[$name] = $tab["id"];
                                    $strScript = "<script type=\"text/javascript\">
                    var currentTab".$name."='".$tab["id"]."';
                    var formTab = 'page_zone';
                    function ongletFW".$name."(id) {
                        if (document.getElementById(fFormTab + '_tab_' + id)) {
                            tabSwitch(currentTab".$name.", 'off'); /** l'ancien */
                            tabSwitch(id, 'on'); /** le nouveau */
                            currentTab".$name." = id;
                        }
                    }
                        </script>";
                                }
                                $oTab->addTab($tab["label"], "fForm_".$tab["id"], ($activatedTab[$name] == $tab["id"]), "", "ongletFW".$name."('".$tab["id"]."')", "", "petit");
                            }
                            $tab = Pelican_Html::div(array("class" => "petit_onglet_bas", width => "100%"), $oTab->getTabs());
                            $strTmp = $strScript.$tab."<br />";

                            return $strTmp;
                        }
                    }
