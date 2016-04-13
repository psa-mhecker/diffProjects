<?

/** Cette classe permet de générer des formulaires
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 16/03/2010
 * @package Pelican
 */
 
 /** Fichier de configuration */
require_once (pelican_path('Html.Form'));
require_once (pelican_path('Media'));

/** Autoloader */
require_once 'Zend/Loader/Autoloader.php';

class Pelican_Form2 extends Zend_Form
{
	/**
	 * Nom du formulaire
	 */
	public $sFormName;
	 
    /**
     * variable du Pelican_Form
     */
    static $_aHiddens;

    public $_aProperties;

    public $_iCountVirtualK = 0;

    public $_aJStoAdd = array();

    public $_gmapsName = array();

    public $_aSuggest = array();

    static $_strCheck = '';
    
    public $_sDefaultFocus = '';
    
    var $tinyMce = false;
	
	var $aEditor;
	
	var $bDirectOutput;
	
	var $bAddElement = true;
	
	//Variable pour le createMulti
	var $_bUseMulti = false;

	var $bMultiTrigger = false;

	var $aMulti = array();
	
	//Variable pour les onglets
	var $aTab;

	var $iCurrentTabId = null;

	var $iTabIsDraw = 0;

	var $iTabIsClosed = 0;
	
	var $aTabElement = array();

    //Variable pour sql
    protected $_sTableSuffixeId = '_id';

    protected $_sTableSuffixeLabel = '_label';

    protected $_sTablePrefix = 'pel_';

	/** Variable pour ne pas générer les tag table ou form **/
	protected $_aHideFormTab;
	
	/** Configuration spécifique du formulaire */	
	public $aFormTabTagArgs;
	
	
    /**
     * Constructeur de la class
     *
     * @param bool $bDirectOutput Choix entre retourner le code Pelican_Html ou directement l'afficher
     * @param string $sFormDisposition Disposition du libéllè par rapport à l'Element verticale/horizontal
     * @param string $sStyleLib Css pour le libellé
     * @param string $sStyleVal Css pour la valeur
     */
    function __construct($bDirectOutput = true, $sFormDisposition = "horizontal", $sStyleLib = "formlib", $sStyleVal = "formval")
    {
		/* Autoloader pour charger les element/decorateur/validateur Pelican_form */
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace("Pelican_Form_");
		$autoloader->registerNamespace("Pelican_Form_Decorator");
		$autoloader->registerNamespace("Pelican_Validate_");
	
		/* Appelle du constructeur parent */
        parent::__construct();
		
        //Mise du Formulaire en AutoView
        $oViewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $oView = $oViewRenderer->view;
        if (!$oViewRenderer->view instanceof Zend_View_Interface) {
            $oView = new Zend_View();
        }
        $this->setView($oView);
        
        //Set Path
        $this->addPrefixPath('Pelican_Form_Decorator', 'Pelican/Form/Decorator', Zend_Form_Element::DECORATOR);
        $this->addElementPrefixPath('Pelican_Validate', 'Pelican/Validate', Zend_Form_Element::VALIDATE);
        $this->addElementPrefixPath('Pelican_Form_Decorator', 'Pelican/Form/Decorator', Zend_Form_Element::DECORATOR);
        $this->addDisplayGroupPrefixPath('Pelican_Form_Decorator', 'Pelican/Form/Decorator', Zend_Form_Element::DECORATOR);
  
		//Init de la variable pour générer, ou non, le form ou le table
		$this->_aHideFormTab = array("hideTab" => false, "hideForm" => false, "createXhtml" => true);
        
        //Properties
        $this->_aProperties['sLibPath'] = "/library";
        $this->_aProperties['sLibForm'] = "/Pelican/Form/public";
        $this->_aProperties['sStyleLib'] = $sStyleLib;
        $this->_aProperties['sStyleVal'] = $sStyleVal;
        $this->_aProperties['sUploadHttpPath'] = "http://phpfactory.dev.media";
        $this->_aProperties['sFormDisposition'] = $sFormDisposition;
        $this->_aProperties['_sUploadHttpPath'] = "http://phpfactory.dev.media";
        $this->_aProperties['_sUploadVar'] = "#MEDIA_HTTP#";
        $this->_aProperties['_sEditorCss'] = "http://phpfactory.dev.media/design/pelican/css/editor.css";
		
        //mise a 0 du tableau des hiddens
        self::$_aHiddens = array();
		
    }

	public function hideFormTabTag($aFormConfig) {
		if (is_array($aFormConfig)) {
			/*$this->_aHideFormTab["hideTab"] = $aFormConfig["hideTab"];
			$this->_aHideFormTab["hideForm"] = $aFormConfig["hideForm"];*/
			$this->_aHideFormTab = array_merge($this->_aHideFormTab, $aFormConfig);
		}
	}
	
	public function output ($value) {
	}
	
    /**
     * Déclaration du formulaire
     *
     * @return string
     * @param string $strAction Champ action du formulaire
     * @param string $strMethod Champ method du formulaire
     * @param string $strName Champ name du formulaire
     * @param boolean $bUpload Vrai si le formulaire est destiné à contenir un upload de fichier
     * @param boolean $bCheck Envoi du formulaire soumis à vérification
     * @param string $sCheckFunction Fonction a appeler pour vérification du formulaire
     * @param string $sTarget Target du formulaire
     * @param boolean $bBlockSubmit Permet d'empêcher les submit multiples (Vrai par défaut)
     */
    function open($strAction = "", $strMethod = "post", $strName = "fForm", $bUpload = false, $bCheck = true, $sCheckFunction = "CheckForm", $sTarget = "", $bBlockSubmit = true, $bVirtualKeyboard = true)
    {   		
        //set par defaut
        $aForm = array('id' => $strName, 'class' => 'fwForm', 'style' => 'margin:0 0 0 0;', 'method' => $strMethod, 'action' => $strAction, 'name' => $strName, 'target' => $sTarget);
        if ($bCheck) {
            $aForm['onsubmit'] = 'return ' . $sCheckFunction . '(this);';
        }
        if ($bUpload) {
            $aForm['enctype'] = "multipart/form-data";
        }
        $this->addAttribs($aForm);
        
		//Nom du formulaire par défaut
		$this->sFormName = $strName;
		
        //Properties
        $this->_aProperties['strFormName'] = $strName;
        $this->_aProperties['bVirtualKeyboard'] = $bVirtualKeyboard;
        $this->_aProperties['sCheckFunction'] = $sCheckFunction;
        //JS
        array_push($this->_aJStoAdd, '/library/Pelican/Form/public/js/ajax.js');

        //récupération de la vue
        $oView = $this->getView();
        //Set du path vers les View Helper
        $oView->addHelperPath('Pelican/View/Helper', 'Pelican_View_Helper');
       	
       	//echo $oView->MyForm($aForm);
    }

    /**
     * @param  string $_sJSPath 
     */
    function close($_sJSPath = "") {
    	
    }

    /**
     * Rajoute l'hidden au tableau
     *
     * @param string $strName
     * @param string $strValue
     */
    public static function addHidden($strName, $strValue = null)
    {
		/* Désactivation de la verification: la class pelican de base n'a plus l'air de la faire
        if (isset(self::$_aHiddens[$strName]) && substr($strName, -2) != "[]") {
            var_dump('alert: ' . $strName);
        }*/
        if (substr($strName, -2) == '[]') {
            array_push(self::$_aHiddens, array($strName => $strValue));
        } else {
            self::$_aHiddens[$strName] = $strValue;
        }
    }

	/**
	 * Rajoute un tableau de hiddens
	 */
	public static function addHiddens($aHiddens) {
		if (is_array($aHiddens)) {
			foreach ($aHiddens as $sKey => $sValue) {
				self::addHidden($sKey, $sValue);
			}
		}
	}
	
	/*
	 * crée les input Hidden cumulés au fur et à mesure
     *
     * @return string
	 */
	public function putHidden() {
		//Récupére la vue
		$oView = $this->getView();
		
		$aHiddens = $this->getHiddens();
		
		if ($aHiddens != null) {
			foreach ($aHiddens as $strName => $Value) {
				if (is_array($Value)) {
					foreach ($Value as $strName => $strValue) {
						$strTmp .= $oView->formHidden($strName, $strValue);
					}
				} else {
					$strTmp .= $oView->formHidden($strName, $Value);
				}
			}
		}
		
		return $strTmp;
	}
	
	
    /**
     * Renvoie le tableau d'Hiddens
     *
     * @return array _aHiddens
     */
    public function getHiddens()
    {
        return self::$_aHiddens;
    }

    /**
     * Surcharge de addElement. 
     * Mise en place des properties dans les attributs
     * 
     * @param  string|Pelican_Form_Element $element
     * @param  string $name
     * @param  array|Zend_Config $options
     */
    public function addElement($oElement, $strName = null, $aOptions = null)
    {
		/* On rajoute un message d'erreur si le champ est obligatoire*/
		if ($oElement->isRequired()) {
			$oElement->addErrorMessage(t('FORM_MSG_VALUE_CHOOSE') . ' "' . strip_tags(str_replace("\"", "" . "\\" . "\"", $oElement->getLabel())).'".');
		}
		
        //Rajout des properties du Pelican_Form a ce de l'element
        $oElement->setProperties($this->_aProperties);
        if ($this->bAddElement) {
        	parent::addElement($oElement, $strName, $aOptions);
        }
        if ($this->iCurrentTabId != null) {
          	$this->aTabElement[$this->iCurrentTabId][] = $oElement->getFullyqualifiedName();
        }
        if ($this->bMultiTrigger == true) {
        	array_push($this->aMulti, $oElement->getFullyqualifiedName());
        }
		
		/** Dans le cas où le xhtml doit être retourné, on appelle les décorateurs pour générer les Xhtml */
		if ($this->_aHideFormTab["createXhtml"] == true) {
			if ($this->bDirectOutput == false) {
				foreach ($oElement->getDecorators() as $decorator) {
					$decorator->setElement($oElement);
					$content = $decorator->render($content);
				}
				return $content;
			}
		}
    }

    /**
     * Surcharge de la fonction isValid
     *
     * @param array $aData Valeur du formulaire. Si null, on le remplit avec $_POST
     * @return booleen
     */
    public function isValid($aData)
    {
		if ($aData == null) {
			$aData = $_POST;
		}
        $bValid = true;
		
        foreach ($this->getElements() as $strKey => $oElement) {
            if (array_key_exists($strKey, $_FILES)) {
                $bValid = $oElement->isValid($_FILES[$strKey], $aData) && $bValid;
            } else {
                if (!isset($aData[$strKey])) {
                    $bValid = $oElement->isValid(null, $aData) && $bValid;
                } else {
                    $bValid = $oElement->isValid($aData[$strKey], $aData) && $bValid;
                }
            }
            if (!$bValid) {
                return $bValid;
            }
        }
        return $bValid;
    }
		
	public function getFormValidation($aData = null) {
		if ($aData == null) {
			$aData = $_POST;
		}
	
		if ($this->isValid($aData) == false) {
			$json = $this->getMessages();	

			return $json;
		}
	}

    /**
     * Surcharge de __toString
     * Rajout des scripts JS et du virtual Keyboard
     *
     */
    function __toString()
    {    
        //Génére le formulaire, rajoute les hiddens et crée les balides Pelican_Form
	    $this->aFormTabTagArgs[0] = "FormElements";
		$this->aFormTabTagArgs[1] = array('HtmlTag', array('tag' => 'table', 'class' => 'form', 'cellspacing' => 0, 'cellpadding' => 0, 'border' => 0, 'summary' => 'Formulaire', 'id' => 'tableClassForm'));
		$this->aFormTabTagArgs[2] = "HiddenGroup";
		
		if ($this->_aHideFormTab["hideForm"] != true) {
			array_push($this->aFormTabTagArgs, 'MyForm');
		}
		if ($this->_aHideFormTab["hideTab"] == true) {
			unset($this->aFormTabTagArgs[1]);
		}		
        $this->setDecorators($this->aFormTabTagArgs);
				
        //récupération de la vue
        $oView = $this->getView();
        //Set du path vers les View Helper
        $oView->addHelperPath('Pelican/View/Helper', 'Pelican_View_Helper');
       	
        //Génération du code Xhtml des Elements
        $strTmp = parent::__tostring(); 
		
        $xhtml = '';
		//Editor
		if ($this->tinyMce && $this->aEditor) {
			$aScript['type'] = "text/javascript";
			$aScript['src'] = "/tiny_mce/tiny_mce.js";
			$xhtml .= $oView->formScript($aScript);
			$ed = $this->getTiny();
			unset($aScript['src']);
			$aScript['type'] = "text/javascript";
			$xhtml .= $oView->formScript($aScript, $ed);
		}
        //Rajout du Js et de la Div VirtualKeyboard
        if ($this->_aProperties['bVirtualKeyboard'] == true && $this->_iCountVirtualK != 0) {
            $this->addJS('/library/External/tiny_mce/plugins/Jsvk/jscripts/vk_popup.js');
            $aImg['style'] = 'cursor: pointer;';
            $aImg['onclick'] = 'PopupVirtualKeyboard.toggle((activeInput?activeInput:"this"),"td"); return false;';
            $strImg = $oView->myImg('/library/External/tiny_mce/plugins/Jsvk/img/jsvk.gif', $aImg);
            $aDiv['style'] = 'float: right; position: absolute; top: 0px; right: 0px; margin-right: 5px; margin-top: 5px;';
            $xhtml .= $oView->formDiv($aDiv, $strImg);
        }
        		
		//Rajout du fichier js hmvc
		$this->addJS('/library/Pelican/Form/public/js/hmvc.js');
		
        //Rajout des JS
        foreach ($this->_aJStoAdd as $JS) {
            $aScript['src'] = $JS;
            $aScript['type'] = "text/javascript";
            $xhtml .= $oView->formScript($aScript);
        }
        
        //Création de la fonction Check et Rajout des Selectall
        $strCheck = '';
		
		$_SESSION["INSTANCE_FORM"] = serialize(&$this);
		
		$ajaxFormChecker = "
			var url = '/_/CheckForm/checkForm';
			
			/* Données du formulaire */
			var data = jQuery('#".$this->_aProperties['strFormName']."').serialize();			
			
			/* Flag d'erreur */
			var IsError = 0;			
			jQuery.ajax({
			  type: 'POST',
			  url: url,
			  data: data,
			  success: function (aReturn) {
				if (aReturn != null) {
					$.each(aReturn, function(key, value) {
						if (IsError == 0 && value != null) {
							alert(value);
							$('#'+key).focus();
							IsError = 1;
						}
					});
				}
			  },
			  async:false,
			  dataType:'json'
			});
			
			if (IsError == 1) { return false; }
			";
					
        if ($this->_bUseMulti == true) {
        	$strCheck .= "var " . $this->_aProperties['sCheckFunction'] . "_multi=new Function(\"obj\",\"return true\");\n";
        }
        $strCheck .= "function " . $this->_aProperties['sCheckFunction'] . "(obj) {\n". self::$_strCheck  ."\n". $ajaxFormChecker . " return true;\n}\n";
        
        //Rajout du suggest de createInput
        $strSuggest = '';
        if ($this->_aSuggest) {
            $aDiv['style'] = 'top: 54px; left: 49px; width: 176px; display: none;';
            $aDiv['id'] = 'search_suggest';
            $xhtml .= $oView->formDiv($aDiv);
            $aCss["href"] = "/library/Pelican/Form/public/css/suggest.css";
            $aCss["type"] = "text/css";
            $aCss["rel"] = "STYLESHEET";
            $xhtml .= $oView->headLink($aCss);
            
            foreach ($this->_aSuggest as $strName => $strVal) {
                $strSuggest .= "\nbuildSearch('" . $strName . "',Array('" . implode("','", str_replace("'", "\\'", $strVal)) . "'));\n";
            }
        }
        
        //Initialisation de Google maps
        $strMap = '';
        if ($this->_gmapsName) {
            foreach ($this->_gmapsName as $gName) {
                $initMap[] = "mapControl('" . $gName . "');";
            }
            $strMap .= "\nif (window.addEventListener) {";
            $strMap .= "\n window.addEventListener('load', function(){ " . implode("\n", $initMap) . " }, false);";
            $strMap .= "\n} else {";
            $strMap .= "\n if ( window.attachEvent ) {";
            $strMap .= "\n  window.attachEvent('onload', function(){ " . implode("\n", $initMap) . " } );";
            $strMap .= "\n }";
            $strMap .= "\n}";
        }
        
		$strTab = '';
		if (isset($this->aTab) && !empty($this->aTab)) {
			$strTab = $this->getTabSwitch();
		}
		
        //Rajout du Javascript au code final
        $xhtml .= "<script type='text/javascript'>" . $strCheck . $strMap . $strTab . $strSuggest . "</script>";
        
        return $strTmp . $xhtml;
    }
    
    /**
     * Ajout d'un code à la pile de verification
     *
     * @param string $strJs
     */
    public static function createJs($strJs) {
    	self::$_strCheck .= $strJs;
    }

    /**
     * Rajoute les JS dans la liste
     * si ils ni sont pas deja
     *
     * @param string $strJs
     */
    public function addJS($strJs)
    {
        if (!in_array($strJs, $this->_aJStoAdd)) {
            array_push($this->_aJStoAdd, $strJs);
        }
        return;
    }

    /**
     * Retourne les valeurs de la table $strTableName=>$aDataValues et les valeurs sélectionnées de la table $strRefTableName=>$aSelectedValues
     *
     * @return void
     * @param Pelican_Db $oConnection Objet connection de la base
     * @param string $strTableName Nom de la table pour les valeurs sans $this->_sTablePrefix : "" par défaut
     * @param string $strRefTableName Nom de la table de jointure oè¹ trouver les valeurs sélectionnées : "" par défaut
     * @param string $iID id auquel sont associées les valeurs sélectionnées : "" par défaut
     * @param mixed $aDataValues Tableau de valeurs (id=>lib)
     * @param mixed $aSelectedValues Tableau des valeurs sélectionnées (liste des id)
     * @param string $strColRefTableName Nom de la colonne dans la table de référence correspondant è  $iID : "CONTENU_ID" par défaut
     */
    function _getValues(&$oConnection, $strTableName = "", $strRefTableName = "", $iID = "", &$aDataValues, &$aSelectedValues, $strColRefTableName = "contenu_id", $strOrderColName = '', $iSiteId = '')
    {
		//Récuperation de l'instance de la BDD
		$oConnection = Pelican_Db::getInstance();
	
        $strSQL = "select " . $strTableName . $this->_sTableSuffixeId . " as \"id\", " . $strTableName . $this->_sTableSuffixeLabel . " as \"lib\" from " . $this->_sTablePrefix . $strTableName;
        if ($iSiteId != '') {
            $strSQL .= " where SITE_ID =" . $iSiteId;
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
            $strSQL = "select " . $strTableName . $this->_sTableSuffixeId . " as \"id\" from " . $strRefTableName . " where " . $strColRefTableName . " = " . $iID;
            if ($strOrderColName != "") {
                $strSQL .= " order by " . $strOrderColName;
            }
            $oConnection->Query($strSQL);
            if ($oConnection->data) {
                while ($ligne = each($oConnection->data["id"])) {
                    $aSelectedValues[count($aSelectedValues)] = $ligne["value"] . (($strTableName == "SECTEUR") ? " " : "");
                }
            }
        }
    }

    /**
     * Retourne les valeurs de la table $strTableName=>$aDataValues
     *
     * @return void
     * @param Pelican_Db $oConnection Objet connection è  la base
     * @param string $strSQL Chaine SQL
     * @param mixed $aDataValues Tableau de valeurs (id=>lib)
     */
    function _getValuesFromSQL(&$oConnection, $strSQL, &$aDataValues, $aBind = array())
    {
		//Récuperation de l'instance de la BDD
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
     * Retourne les valeurs de la table $strTableName=>$aDataValues regroupées sur le tableau $GroupField
     *
     * @return void
     * @param Pelican_Db $oConnection Objet connection è  la base
     * @param string $strSQL Chaine SQL
     * @param mixed $aDataValues Tableau de valeurs (id=>lib)
     * @param mixed $GroupField Tableau de champs de regroupements
     * @param string $strSep Chaine de répétition pour marquer l'indentation des groupes
     */
    function _getGroupValuesFromSQL(&$oConnection, $strSQL, &$aDataValues, $GroupField, $strSep = "&nbsp;&nbsp;", $aBind = array())
    {
		//Récuperation de l'instance de la BDD
		$oConnection = Pelican_Db::getInstance();
		
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
                for ($i = 0; $i < count($GroupField); $i ++) {
                    if ($old[$i] != $valeur[$GroupField[$i]]) {
                        $j ++;
                        $old[$i] = $valeur[$GroupField[$i]];
                        $aDataValues["delete_" . $j] = str_repeat($strSep, $i) . $valeur[$GroupField[$i]];
                    }
                }
                $aDataValues[$valeur[$keys[0]]] = str_repeat($strSep, count($GroupField)) . $valeur[$keys[1]];
            }
        }
    }

    /**
     * Remplacement du echo ou pour aider à la concaténé
     * 
     * @param string strString
     */
    function createFreeHtml($strString) {
    	$oElement = new Pelican_Form_Element_Xhtml('createPrint');
    	$oElement->setDecorators(array('Xhtml'));
    	$oElement->setValue($strString);
    	$sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }
    
    /**
     * Création d'une div
     *
     * @param string $strName
     * @param string $strLib
     * @param booleen $bRequired
     * @param string $strValue
     * @param string $strLabel
     * @param string $strDivContent
     * @param booleen $bReadOnly
     * @param booleen $bFormOnly
     */
    function createDiv($strName, $strLib, $bRequired = false, $strValue = "", $strLabel = "&nbsp;", $strDivContent = "", $bReadOnly = false, $bFormOnly = false)
    {
    	$oElement = new Pelican_Form_Element_Xhtml($strName);
    	$aDecorator = (!$bReadOnly) ? array('Div') : array('ReadODiv');
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Properties 
        $oElement->setPropertie('strLabel', $strLabel);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('strDivContent', $strDivContent);
        
        //Element
        $oElement->setDecorators($aDecorator);
        $oElement->setRequired($bRequired);
        $oElement->setLabel($strLib);
        $oElement->setValue($strValue);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un champ input de type Text
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param string $iMaxLength Nb de caractères maximum : 255 par défaut
     * @param string $strControl Type de contrèle js utilisé : numerique ou number, float, flottant, real ou reel, telephone, mail, date
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param string $strValue Valeur du champ
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string $iSize Taille d'affichage du champ : 10 par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     * @param string $strEvent Handler d'événements sur le champ : "" par défaut
     * @param string $strType Type de l'input ("text" par défaut)
     * @example Création d'un champ input présentant le nom d'un utilisateur :
     *  $oForm = Pelican_Factory::getInstance('Form',true);
     *  $oForm->createInput("USER_NAME", "Nom", 50, "", true, $values["USER_NAME"], $readO, 50, false, "", "text");
     */
    function createInput($strName, $strLib, $iMaxLength = "255", $strControl = "", $bRequired = false, $strValue = "", $bReadOnly = false, $iSize = "10", $bFormOnly = false, $strEvent = "", $strType = "text", $aSuggest = array())
    {
        //Instance de l'Element
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        
        //Gestion du ReadOnly
        if (!$bReadOnly) {
            $aDecorator = array('Standard');
            
            //JS
            $this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
            if ($aSuggest) {
                $this->addJS('/library/Pelican/Form/public/js/xt_suggest_fonctions.js');
                $oElement->setPropertie('suggest', true);
                if (!is_array($aSuggest)) {
                    $aSuggest = array($aSuggest);
                }
                $this->_aSuggest[$strName] = $aSuggest;
            }
            
            //Clavier Virtuel
            $this->_iCountVirtualK ++;
            			
            //Gestion des controles
            $oElement->setPropertie('strControl', $strControl);
            switch ($strControl) {
                case "heure":
                    {
                        $oElement->addValidator('Date', true, array('hh:mm'));
                        $this->addJS('/library/Pelican/Form/public/js/xt_date_controls.js');
                        $this->addJS('/library/Pelican/Form/public/js/xt_calendar_fonctions.js');
						$sControlErrorMessage = t('FORM_MSG_HEURE');
                        break;
                    }
                case "date":
                case "dateNF":
                case "shortdate":
                case "calendar":
                    {
                        $oElement->addValidator('regex', true, array('#(([012][0-9])|([3][0-1]))/(([0][1-9])|([1][0-2]))/([0-9][0-9][0-9][0-9])#'));
                        $this->addJS('/library/Pelican/Form/public/js/xt_date_controls.js');
                        $this->addJS('/library/Pelican/Form/public/js/xt_calendar_fonctions.js');
						$sControlErrorMessage = t('FORM_MSG_DATE');
                        break;
                    }
                case "date_edition":
                    {
                        if (strlen($strValue) == 10) {
                            $oElement->addValidator('regex', true, array('#(([012][0-9])|([3][0-1]))/(([0][1-9])|([1][0-2]))/([0-9][0-9][0-9][0-9])#'));
                        }
                        if (strlen($strValue) == 7) {
                            $oElement->addValidator('regex', true, array('#(([0][1-9])|([1][0-2]))/([0-9][0-9][0-9][0-9])#'));
                        }
                        if (strlen($strValue) == 4) {
                            $oElement->addValidator('regex', true, array('#([0-9][0-9][0-9][0-9])#'));
                        }
                        $this->addJS('/library/Pelican/Form/public/js/xt_date_controls.js');
                        $this->addJS('/library/Pelican/Form/public/js/xt_calendar_fonctions.js');
						$sControlErrorMessage = t('FORM_MSG_DATE_EDITION');
                        break;
                    }
                case "color":
                case "internallink":
                    {
                        $this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');
                        break;
                    }
                case "mail":
                    {
                        $oElement->addValidator('EmailAddress');
						$sControlErrorMessage = t('FORM_MSG_MAIL');
                        break;
                    }
                case "alphanum":
                    {
                        $oElement->addValidator('Alnum');						
						$sControlErrorMessage = t('FORM_MSG_ALPHANUM');
                        break;
                    }
                case "float":
                case "flottant":
                case "real":
                case "reel":
                    {
                        $oElement->addValidator('Float');		
						$sControlErrorMessage = t('FORM_MSG_REAL');						
                        break;
                    }
                case "numerique":
                case "number":
                    {
                        $oElement->addValidator('Digits');
						$sControlErrorMessage = t('FORM_MSG_NUMBER');
                        break;
                    }
                case "telephone":
                    {
                        $oElement->addValidator('regex', true, array('#^([0-9\\.\\-\\ ]*)$#'));
						$sControlErrorMessage = t('FORM_MSG_TELEPHONE');
                        break;
                    }
                case "URL":
                    {
                        $oElement->addValidator('regex', true, array('#^(http://|https://){0,1}[A-Za-z0-9][A-Za-z0-9\-\.]+[A-Za-z0-9]\.[A-Za-z]{2,}[\43-\176]*$#'));
						$sControlErrorMessage = t('FORM_MSG_URL');
                        break;
                    }
                case "login":
                    {
                        $oElement->addValidator('regex', true, array('#^[a-zA-Z0-9][a-zA-Z0-9\\&\\.\\_\\-]{1,}$#'));
						$sControlErrorMessage = t('FORM_MSG_LOGIN');
                        break;
                    }
                case "year":
                    {
                        $oElement->addValidator('Date', true, array('format' => 'YYYY'));
                        $oElement->addValidator('StringLength', true, array('min' => 4, 'max' => 4));
						$sControlErrorMessage = t('FORM_MSG_YEAR');
                        break;
                    }
            }
			
			/* On rajoute le message d'erreur */
			if (isset($strControl) && !empty($strControl)) {
				$sMessageErreur = t('FORM_MSG_VALUE_REQUIRE') . " \"" . (strip_tags(str_replace("\"", "\\" . "\"", $strLib))) ."\"";
				if (isset($strControl) && !empty($strControl)) {
					$sMessageErreur .= " " . t('FORM_MSG_WITH') . " " . $sControlErrorMessage;
				}
				$sMessageErreur .= ".";
				$oElement->addErrorMessage($sMessageErreur);
			}
        } else {
            $aDecorator = array('ReadO');
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('strEvent', $strEvent);
        
        //Element
        $oElement->setLabel($strLib);
        $oElement->setValue($strValue);
        $oElement->setRequired($bRequired);
        $oElement->setDecorators($aDecorator);
        $oElement->setAttribs(array('size' => $iSize, 'maxlength' => $iMaxLength, 'class' => 'text'));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un champ de saisie de mot de passe
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param string $iMaxLength Nb de caractères maximum : 255 par défaut
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param string $strValue Valeur du champ
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string $iSize Taille d'affichage du champ : 10 par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     * @param string $strEvent Handler d'événements sur le champ : "" par défaut
     */
    function createPassword($strName, $strLib, $iMaxLength = "255", $bRequired = false, $strValue = "", $bReadOnly = false, $iSize = "10", $bFormOnly = false, $strEvent = "")
    {
        //Instance de l'Element
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        
        //Gestion du ReadOnly
        if (!$bReadOnly) {
            $oElement->setHelper('formPassword');
            $aDecorator = array('Standard');
            //JS
            $this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
        } else {
            $aDecorator = array('ReadO');
        }
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        
        //Element		
        $oElement->setLabel($strLib);
        $oElement->setValue($strValue);
        $oElement->setRequired($bRequired);
        $oElement->setDecorators($aDecorator);
        $oElement->setAttribs(array('size' => $iSize, 'maxlength' => $iMaxLength, 'class' => 'text', 'renderPassword' => true));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un champ parcourir de type file
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param string $iMaxLength Nb de caractères maximum : 255 par défaut
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param string $strValue Valeur du champ
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string $iSize Taille d'affichage du champ : 10 par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     * @param string $strEvent Handler d'événements sur le champ : "" par défaut
     */
    function createBrowse($strName, $strLib, $iMaxLength = "255", $bRequired = false, $strValue = "", $bReadOnly = false, $iSize = "10", $bFormOnly = false, $strEvent = "")
    {
        //Instance de l'Element File
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        //Gestion du ReadOnly	
        if (!$bReadOnly) {
            $aDecorator = array('File');
            
            //JS
            $this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
            
            if ($bRequired) {
                $oElement->addValidator(new Pelican_Validate_File());
            }
        } else {
            $aDecorator = array('ReadOFile');
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        
        //Element	
        $oElement->setLabel($strLib);
        $oElement->setValue($strValue);
        $oElement->setRequired($bRequired);
        $oElement->setDecorators($aDecorator);
        $oElement->setAttribs(array('size' => $iSize, 'maxlength' => $iMaxLength, 'class' => 'text'));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un champ de saisie de date
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param string $strValue Valeur du champ
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     * @param string $strEvent Handler d'événements sur le champ : "" par défaut
     */
    function createDateTime($strName, $strLib, $bRequired = false, $strValue = "", $bReadOnly = false, $bFormOnly = false, $strEvent = "")
    {
        //Instance de l'Element
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        
        //Gestion du ReadOnly
        if (!$bReadOnly) {
            $aDecorator = array('DateTime');
            
            //JS
            $this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
            $this->addJS('/library/Pelican/Form/public/js/xt_date_controls.js');
            $this->addJS('/library/Pelican/Form/public/js/xt_calendar_fonctions.js');
            
            //reconstrui la valeur
            self::$_strCheck .= "if (obj." . $strName . "_HEURE.value) {\n";
            self::$_strCheck .= "	obj." . $strName . ".value = obj." . $strName . "_DATE.value + ' ' + obj." . $strName . "_HEURE.value + ':00';\n";
            self::$_strCheck .= "} else {\n";
            self::$_strCheck .= "	obj." . $strName . ".value = obj." . $strName . "_DATE.value + ' ' + '00:00:00';\n";
            self::$_strCheck .= "}\n";
            self::$_strCheck .= "if (obj." . $strName . ".value == ' 00:00:00') {\n";
            self::$_strCheck .= "	obj." . $strName . ".value = '';\n";
            self::$_strCheck .= "}\n";
            
            //VirtualKeyboard
            $this->_iCountVirtualK ++;
            
            //Validateur 
            if ($bRequired == true)
                $oElement->addValidator(new Pelican_Validate_DateTime());
        } else {
            $aDecorator = array('ReadODateTime');
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        
        //Element
        $oElement->setLabel($strLib);
        $oElement->setValue($strValue);
        $oElement->setRequired($bRequired);
        $oElement->setDecorators($aDecorator);        
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère une association à partir d'une table
     *
     * @return string
     * @param Pelican_Db $oConnection Objet connection à la base
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param string $strTableName Nom de la table pour les valeurs sans $this->_sTablePrefix
     * @param string $strRefTableName Nom de la table de jointure où trouver les valeurs sélectionnées : "" par défaut
     * @param string $iID id auquel sont associées les valeurs sélectionnées : "" par défaut
     * @param mixed $aSelectedValues Tableau des valeurs sélectionnées (liste des id) : "" par défaut
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param boolean $bDeleteOnAdd Supprimer les valeurs de la liste source après ajout à la liste destination : true par défaut
     * @param boolean $bEnableManagement Accès à la popup d'ajout dans la table de référence : true par défaut
     * @param boolean $bSearchEnabled La liste n'est pas remplie et un formulaire de recherche est ajouté : false par défaut
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string $iSize Taille d'affichage de la liste : 5 par défaut
     * @param string $iWidth Largeur du contrèle : 200 par défaut
     * @param string $strColRefTableName Nom de la colonne dans la table de référence correspondant à $iID : "CONTENU_ID" par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     * @param string $arForeignKey Remplace la Pelican_Index_Frontoffice_Zone de recherche par une liste déroulante pour filtrer la sélection (nécessite bSearchEnabled à true) :
     * 3 modes :
     * - 1 : nom de table de référence de la clé étrangère (sans le préfixe) => la requête de liste et de recherche seront alors génériques
     * - 2 : array(nom de table de référence de la clé étrangère, SQL avec id et lib dans le select pour la liste déroulante) => la requête de recherche sera alors générique
     * - 3 : array(nom de table de référence de la clé étrangère, SQL avec id et lib dans le select pour la liste déroulante, SQL avec id et lib dans le select pour la recherche et :RECHERCHE: dans la clause where)
     * @param boolean $bSingle Génère un nom de champ sans [] : false par défaut
     */
    function createAssoc(&$oConnection, $strName, $strLib, $strTableName, $strRefTableName = "", $iID = "", $aSelectedValues = "", $bRequired = false, $bDeleteOnAdd = true, $bEnableManagement = true, $bSearchEnabled = false, $bReadOnly = false, $iSize = "5", $iWidth = 200, $strColRefTableName = "contenu_id", $bFormOnly = false, $arForeignKey = "", $bSingle = false, $alternateId = false, $strOrderColName = '')
    {
        //Instance de l'Element
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        
        //Gestion du readOnly
        if (!$bReadOnly) {
            $aDecorator = array('Assoc');
            //JS
            self::$_strCheck .= "selectAll(document." . $this->_aProperties['strFormName'] . ".elements['" . $strName . "[]']);\n";
            $this->addJS('/library/Pelican/Form/public/js/xt_list_fonctions.js');
            if ($bEnableManagement)
                $this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');
                
            //Validateur
            if ($bRequired == true) {
                $oElement->addValidator(new Pelican_Validate_Assoc());
            }
        } else {
            $aDecorator = array('ReadOAssoc');
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('iID', $iID);
        $oElement->setPropertie('bSingle', $bSingle);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('alternateId', $alternateId);
        $oElement->setPropertie('strTableName', $strTableName);
        $oElement->setPropertie('bDeleteOnAdd', $bDeleteOnAdd);
        $oElement->setPropertie('arForeignKey', $arForeignKey);
        $oElement->setPropertie('bSearchEnabled', $bSearchEnabled);
        $oElement->setPropertie('strOrderColName', $strOrderColName);
        $oElement->setPropertie('strRefTableName', $strRefTableName);
        $oElement->setPropertie('bEnableManagement', $bEnableManagement);
        $oElement->setPropertie('strColRefTableName', $strColRefTableName);
        
        $oElement->setDecorators($aDecorator);
        $oElement->setConnection($oConnection);
        $oElement->setLabel($strLib);
        $oElement->setValue($aSelectedValues);
        $oElement->setRequired($bRequired);
        $oElement->setAttribs(array('size' => $iSize, 'width' => $iWidth));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère une association à partir d'un tableau de valeurs
     *
     * @return string
     * @param Pelican_Db $oConnection Objet connection à la base
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param mixed $aDataValues Tableau de valeurs (id=>lib) : "" par défaut
     * @param mixed $aSelectedValues Tableau des valeurs sélectionnées (liste des id) : "" par défaut
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param boolean $bDeleteOnAdd Supprimer les valeurs de la liste source après ajout à la liste destination : true par défaut
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string$iSize Taille d'affichage de la liste : 5 par défaut
     * @param string $iWidth Largeur du contrôle : 200 par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     * @param string $arForeignKey Remplace la Pelican_Index_Frontoffice_Zone de recherche par une liste déroulante pour filtrer la sélection (nécessite bSearchEnabled à true) :
     * 3 modes :
     * - 1 : nom de table de référence de la clé étrangère (sans le préfixe) => la requête de liste et de recherche seront alors générique
     * - 2 : array(nom de table de référence de la clé étrangère, SQL avec id et lib dans le select pour la liste déroulante) => la requête de recherche sera alors générique
     * - 3 : array(nom de table de référence de la clé étrangère, SQL avec id et lib dans le select pour la liste déroulante, SQL avec id et lib dans le select pour la recherche et :RECHERCHE: dans la clause where)
     */
    //Rajout de costa du 20070208
    function createAssocFromList(&$oConnection, $strName, $strLib, $aDataValues = "", $aSelectedValues = "", $bRequired = false, $bDeleteOnAdd = true, $bReadOnly = false, $iSize = "5", $iWidth = 200, $bFormOnly = false, $arForeignKey = "", $strOrderColName = '', $Management = false, $bNoOrderList = false)
    {
        $bSearchEnabled = ($arForeignKey ? true : false);
        
        //gestion strTableName (Venant de costa)
        if ($Management) {
            $strTableName = $Management['TABLE'];
            $bEnableManagement = true;
        } else {
            $bEnableManagement = false;
        }
        
        //Instance de l'Element
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        
        //Gestion du readOnly
        if (!$bReadOnly) {
            $aDecorator = array('Assoc');
            
            //JS
            self::$_strCheck .= "selectAll(document." . $this->_aProperties['strFormName'] . ".elements['" . $strName . "[]']);\n";
            $this->addJS('/library/Pelican/Form/public/js/xt_list_fonctions.js');
            
            if ($bEnableManagement)
                $this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');
                
            //Validateur 
            if ($bRequired == true) {
                $oElement->addValidator(new Pelican_Validate_Assoc());
            }
        } else {
            $aDecorator = array('ReadOAssoc');
        }
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('bDeleteOnAdd', $bDeleteOnAdd);
        $oElement->setPropertie('arForeignKey', $arForeignKey);
        $oElement->setPropertie('strOrderColName', $strOrderColName);
        $oElement->setPropertie('bSearchEnabled', $bSearchEnabled);
        $oElement->setPropertie('bEnableManagement', $bEnableManagement);
        $oElement->setPropertie('strTableName', $strTableName);
        
        $oElement->setDecorators($aDecorator);
        $oElement->setConnection($oConnection);
        $oElement->setLabel($strLib);
        $oElement->addMultiOptions($aDataValues);
        $oElement->setValue($aSelectedValues);
        $oElement->setRequired($bRequired);
        $oElement->setAttribs(array('size' => $iSize, 'width' => $iWidth));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère une association à  partir de requêtes SQL
     *
     * @return string
     * @param Pelican_Db $oConnection Objet connection à  la base
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param mixed $strSQL Requête SQL des valeurs disponibles (id,lib) : "" par défaut
     * @param mixed $strSQLValues Requête SQL des valeurs sélectionnées (liste des id) : "" par défaut
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param boolean $bDeleteOnAdd Supprimer les valeurs de la liste source après ajout à  la liste destination : true par défaut
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string$iSize Taille d'affichage de la liste : 5 par défaut
     * @param string $iWidth Largeur du contrôle : 200 par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     * @param string $arForeignKey Remplace la Pelican_Index_Frontoffice_Zone de recherche par une liste déroulante pour filtrer la sélection (nécessite bSearchEnabled à  true) :
     * 3 modes :
     * - 1 : nom de table de référence de la clé étrangère (sans le préfixe) => la requête de liste et de recherche seront alors générique
     * - 2 : array(nom de table de référence de la clé étrangère, SQL avec id et lib dans le select pour la liste déroulante) => la requête de recherche sera alors générique
     * - 3 : array(nom de table de référence de la clé étrangère, SQL avec id et lib dans le select pour la liste déroulante, SQL avec id et lib dans le select pour la recherche et :RECHERCHE: dans la clause where)
     * @param array $arSearchFields Liste des champs sur lesquels effectuer une recherche par like
     */
    function createAssocFromSql(&$oConnection, $strName, $strLib, $strSQL = "", $strSQLValues = "", $bRequired = false, $bDeleteOnAdd = true, $bReadOnly = false, $iSize = "5", $iWidth = 200, $bFormOnly = false, $arForeignKey = "", $arSearchFields = "", $aBind = array(), $strOrderColName = '', $showAll = false)
    {
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
                while (list (, $val) = each($arSearchFields)) {
                    $sResearch = "";
                    if (strlen($sFilter) != 0) {
                        $sResearch .= " OR ";
                    }
                    $sResearch .= "UPPER(" . $val . ") like UPPER('%:RECHERCHE:%')";
                    $sFilter .= $sResearch;
                }
                $sFilter = "(" . $sFilter . ")";
                if (stristr($strSQL, "where ") && !stristr($strSQL, "union ")) {
                    $strSQL = preg_replace("/where /i", "where " . $sFilter . " AND ", $strSQL);
                } elseif (stristr($strSQL, "group by ")) {
                    $strSQL = preg_replace("/group by /i", "where " . $sFilter . " group by ", $strSQL);
                } elseif (stristr($strSQL, "order by ")) {
                    $strSQL = preg_replace("/order by /i", "where " . $sFilter . " order by ", $strSQL);
                }
                if ($showAll) {
                    $this->_getValuesFromSQL($oConnection, str_replace(":RECHERCHE:", "%", $strSQL), $aDataValues);
                }
                $_SESSION["AssocFromSql_Search"][$this->sFormName . "_" . $strName] = $strSQL;
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
        
        //Instance de l'Element Assoc
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        
        //Gestion du readOnly
        if (!$bReadOnly) {
            $aDecorator = array('Assoc');
            
            //JS
            self::$_strCheck .= "selectAll(document." . $this->_aProperties['strFormName'] . ".elements['" . $strName . "[]']);\n";
            $this->addJS('/library/Pelican/Form/public/js/xt_list_fonctions.js');
            
            //Validateur 
            if ($bRequired == true) {
                $oElement->addValidator(new Pelican_Validate_Assoc());
            }
        } else {
            $aDecorator = array('ReadOAssoc');
        }
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('showAll', $showAll);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bDeleteOnAdd', $bDeleteOnAdd);
        $oElement->setPropertie('arForeignKey', $arForeignKey);
        $oElement->setPropertie('bSearchEnabled', $bSearchEnabled);
        $oElement->setPropertie('strOrderColName', $strOrderColName);
        
        $oElement->setDecorators($aDecorator);
        $oElement->setConnection($oConnection);
        $oElement->setLabel($strLib);
        $oElement->setValue($aSelectedValues);
        $oElement->addMultiOptions($aDataValues);
        $oElement->setRequired($bRequired);
        $oElement->setAttribs(array('size' => $iSize, 'width' => $iWidth));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère des checkbox à partir d'une série de valeurs
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param mixed $aDataValues Tableau de valeurs (id=>lib) : "" par défaut
     * @param mixed $aCheckedValues Liste des valeurs cochées (liste des id) : "" par défaut
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string $cOrientation orientation h=horizontal, v=vertical : "h" par défaut
     */
    function createCheckBoxFromList($strName, $strLib, $aDataValues = "", $aCheckedValues = "", $bRequired = false, $bReadOnly = false, $cOrientation = "h", $bFormOnly = false, $strEvent = "")
    {
        //Instance de l'Element
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        $aDecorator = ($bReadOnly) ? array('ReadOBox') : array('CheckBox');
		
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('cOrientation', $cOrientation);
        
        $oElement->setLabel($strLib);
        $oElement->setRequired($bRequired);
        $oElement->setValue($aCheckedValues);
        $oElement->setDecorators($aDecorator);
        $oElement->addMultiOptions($aDataValues);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère des checkbox à partir d'une table
     *
     * @return string
     * @param Pelican_Db $oConnection Objet connection à la base
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param string $strTableNameNom de la table pour les valeurs sans $this->_sTablePrefix
     * @param string $strRefTableName Nom de la table de jointure où trouver les valeurs sélectionnées : "" par défaut
     * @param string $iID id auquel sont associées les valeurs sélectionnées : 0 par défaut
     * @param mixed $aCheckedValues Liste des valeurs cochées (liste des id) : "" par défaut
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string $cOrientation Orientation h=horizontal, v=vertical : "h" par défaut
     */
    function createCheckBox(&$oConnection, $strName, $strLib, $strTableName, $strRefTableName = "", $iID = 0, $aCheckedValues = "", $bRequired = false, $bReadOnly = false, $cOrientation = "h", $bFormOnly = false, $strEvent = "", $strColRefTableName = "contenu_id")
    {
        $aDataValues = array();
        $aSelectedValues = array();
        $this->_getValues($oConnection, $strTableName, $strRefTableName, $iID, $aDataValues, $aSelectedValues, $strColRefTableName);
        if ($aCheckedValues != "") {
            $aSelectedValues = $aCheckedValues;
        }
        
        //Instance de l'Element
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        $aDecorator = ($bReadOnly) ? array('ReadOBox') : array('CheckBox');
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('cOrientation', $cOrientation);
        
        $oElement->setLabel($strLib);
        $oElement->setRequired($bRequired);
        $oElement->setValue($aSelectedValues);
        $oElement->setDecorators($aDecorator);
        $oElement->addMultiOptions($aDataValues);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
	
    }

    /**
     * Génère des radio à partir d'une série de valeurs
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param mixed $aDataValues Tableau de valeurs (id=>lib) : "" par défaut
     * @param string $aValue Valeur cochée : "" par défaut
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string $cOrientation orientation h=horizontal, v=vertical : "h" par défaut
     */
    function createRadioFromList($strName, $strLib, $aDataValues = "", $aValue = "", $bRequired = false, $bReadOnly = false, $cOrientation = "h", $bFormOnly = false, $strEvent = "")
    {
        //Instance de l'Element
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        $aDecorator = ($bReadOnly) ? array('ReadOBox') : array('Radio');
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('cOrientation', $cOrientation);
        
        //Element		
        $oElement->setValue($aValue);
        $oElement->setLabel($strLib);
        $oElement->setRequired($bRequired);
        $oElement->setDecorators($aDecorator);
        $oElement->addMultiOptions($aDataValues);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère des radio à partir d'une table
     *
     * @return string
     * @param Pelican_Db $oConnection Objet connection à la base
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param string $strTableName Nom de la table pour les valeurs sans $this->_sTablePrefix
     * @param string $aValue Valeur cochée
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string $cOrientation orientation h=horizontal, v=vertical : "h" par défaut
     */
    function createRadio(&$oConnection, $strName, $strLib, $strTableName, $aValue, $bRequired = false, $bReadOnly = false, $cOrientation = "h", $bFormOnly = false, $strEvent = "")
    {
        $aDataValues = array();
        $NotUsed = array();
        $this->_getValues($oConnection, $strTableName, "", "", $aDataValues, $NotUsed);
        
        //Instance de l'Element
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        $aDecorator = ($bReadOnly) ? array('ReadOBox') : array('Radio');
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
		
        //Rajout des proporties de Pelican
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('cOrientation', $cOrientation);
        
        //Element		
        $oElement->addMultiOptions($aDataValues);
        $oElement->setValue($aValue);
        $oElement->setDecorators($aDecorator);
        $oElement->setLabel($strLib);
        $oElement->setRequired($bRequired);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

     /**
     * Génère un contrôle de type combo
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param mixed $aDataValues Tableau de valeurs (id=>lib)
     * @param mixed $aSelectedValues Tableau des valeurs sélectionnées (liste des id)
     * @param boolean $bRequired Champ obligatoire
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden)
     * @param string$iSize Taille d'affichage du champ
     * @param boolean $bMultiple Sélection multiple
     * @param string $iWidth Largeur du contrôle
     * @param boolean $bChoisissez Affiche le message "->Choisissez" en début de liste
     * @param boolean $bEnableManagement Accès à  la popup d'ajout dans la table de référence : false par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     * @param string $strTableName Nom de la table pour les valeurs sans $this->_sTablePrefix : "" par défaut
     * @param string $sSearchQueryName Nom de la variable de session contenant la requête pour filtrer la combo Dans ce cas, une Pelican_Index_Frontoffice_Zone de saisie avec bouton de recherche s'affiche à  droite.
     * @param string $strEvent événement et fonction javascript "" par défaut. ex : onChange="javascript:functionAExecuter();"
     */
    function _createCombo($strName, $strLib, $aDataValues, $aSelectedValues, $bRequired, $bReadOnly, $iSize, $bMultiple, $iWidth, $bChoisissez, $bEnableManagement = false, $bFormOnly = false, $strTableName = "", $strEvent = "", $sSearchQueryName = "", $bDelManagement = false, $bUpdManagement = false) {
    	if (!$bReadOnly) {
            //Instance de l'Element Combo ou multiCombo
            $oElement = new Pelican_Form_Element_Select($strName);
            $aDecorator = array('Combo');
            $oElement->setRegisterInArrayValidator(false);
        } else {
            //Instance de l'Element ReadOnly
            $oElement = new Pelican_Form_Element_Xhtml($strName);
            $aDecorator = array('ReadOBox');
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Gestion d'erreur
        if ($bRequired == true)
            $oElement->addValidator(new Pelican_Validate_Combo());
            
        //Rajout des proporties de Pelican
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('bMultiple', $bMultiple);
        $oElement->setPropertie('bChoisissez', $bChoisissez);
        $oElement->setPropertie('bDelManagement', $bDelManagement);
        $oElement->setPropertie('sSearchQueryName', $sSearchQueryName);
        $oElement->setPropertie('bUpdManagement', $bUpdManagement);
        $oElement->setPropertie('bEnableManagement', $bEnableManagement);
        $oElement->setPropertie('strTableName', $strTableName);		
              
        //Element
        $oElement->addMultiOptions($aDataValues);
        $oElement->setValue($aSelectedValues);
        $oElement->setDecorators($aDecorator);
        $oElement->setLabel($strLib);
        $oElement->setRequired($bRequired);
        $oElement->setAttribs(array('size' => $iSize, 'width' => $iWidth));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }
    
    /**
     * Génère une combo à partir d'une liste
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param mixed $aDataValues Tableau de valeurs (id=>lib) : par défaut
     * @param mixed $aSelectedValues Tableau des valeurs sélectionnées (liste des id) : "" par défaut
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden)
     * @param string $iSize Taille d'affichage de la liste : 1 par défaut
     * @param boolean $bMultiple Sélection multiple : false par défaut
     * @param string $iWidth Largeur du contrôle : "" par défaut
     * @param boolean $bChoisissez Affiche le message "->Choisissez" en début de liste : true par défaut
     */
    function createComboFromList($strName, $strLib, $aDataValues = "", $aSelectedValues = "", $bRequired = false, $bReadOnly = false, $iSize = "1", $bMultiple = false, $iWidth = "", $bChoisissez = true, $bFormOnly = false, $strEvent = "")
    {
        if (!$bReadOnly) {
            //Instance de l'Element Combo ou multiCombo
            $oElement = new Pelican_Form_Element_Select($strName);
            $aDecorator = array('Combo');
            $oElement->setRegisterInArrayValidator(false);
        } else {
            //Instance de l'Element ReadOnly
            $oElement = new Pelican_Form_Element_Xhtml($strName);
            $aDecorator = array('ReadOBox');
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Gestion d'erreur
        if ($bRequired == true)
            $oElement->addValidator(new Pelican_Validate_Combo());
            
		//Rajout des proporties de Pelican
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('bMultiple', $bMultiple);
        $oElement->setPropertie('bChoisissez', $bChoisissez);
        
        //Element
        $oElement->addMultiOptions($aDataValues);
        $oElement->setValue($aSelectedValues);
        $oElement->setDecorators($aDecorator);
        $oElement->setLabel($strLib);
        $oElement->setRequired($bRequired);
        $oElement->setAttribs(array('size' => $iSize, 'width' => $iWidth));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère une combo à  partir d'une requâte SQL
     *
     * @return string
     * @param Pelican_Db $oConnection Objet connection à  la base
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param mixed $strSQL Requâte SQL (id,lib)
     * @param mixed $aSelectedValuesTableau des valeurs sélectionnées (liste des id) : "" par défaut
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden)
     * @param string $iSize Taille d'affichage de la liste : 1 par défaut
     * @param boolean $bMultiple Sélection multiple : false par défaut
     * @param string $iWidth Largeur du contrôle : "" par défaut
     * @param boolean $bChoisissez Affiche le message "->Choisissez" en début de liste : true par défaut
     * @param boolean $bFormOnly Affiche uniquement les éléments du formulaire
     * @param string $strEvent
     * @param array $arSearchFields Liste des champs sur lesquels effectuer une recherche par like
     *  0 : nom complet du champ id dont la(les) valeur(s) sélectionnée(s) est(sont) dans $aSelectedValues
     *  suivants : champ(s) sur le(s)quel(s) doit s'effectuer la recherche
     *  Dans ce cas, la combo ne contient que la (les) valeur(s) sélectionnée(s) et une Pelican_Index_Frontoffice_Zone de saisie avec
     *  bouton de recherche s'affiche à  droite.
     */
    function createComboFromSql(&$oConnection, $strName, $strLib, $strSQL = "", $aSelectedValues = "", $bRequired = false, $bReadOnly = false, $iSize = "1", $bMultiple = false, $iWidth = "", $bChoisissez = true, $bFormOnly = false, $strEvent = "", $arSearchFields = "", $aBind = array())
    {
        $aDataValues = array();
        if (is_array($arSearchFields)) {
            $sFilter = "";
            
            if (!is_array($aSelectedValues)) {
                $aSelectedValues = array($aSelectedValues);
            }
            
            while (list (, $val) = each($aSelectedValues)) {
                if (strlen($sFilter) != 0) {
                    $sFilter .= ",";
                }
                $sFilter .= "'" . str_replace("'", "''", $val) . "'";
            }
            
            reset($aSelectedValues);
            $sFilter = $arSearchFields[0] . " IN (" . $sFilter . ")";
            
            if (stristr($strSQL, "where ")) {
                $sFilter = preg_replace("/where /i", "where " . $sFilter . " AND ", $strSQL);
            } elseif (stristr($strSQL, "group by ")) {
                $sFilter = preg_replace("/group by /i", "where " . $sFilter . " group by ", $strSQL);
            } elseif (stristr($strSQL, "order by ")) {
                $sFilter = preg_replace("/order by /i", "where " . $sFilter . " order by ", $strSQL);
            }
            
            $this->_getValuesFromSQL($oConnection, $sFilter, $aDataValues, $aBind);
            
            $sFilter = "";
            
            while (list (, $val) = each($arSearchFields)) {
                if (strlen($sFilter) != 0) {
                    $sFilter .= " OR ";
                }
                $sFilter .= "UPPER(" . $val . ") like UPPER('%:RECHERCHE:%')";
            }
            
            $sFilter = "(" . $sFilter . ")";
            if (stristr($strSQL, "where ")) {
                $strSQL = preg_replace("/where /i", "where " . $sFilter . " AND ", $strSQL);
            } elseif (stristr($strSQL, "group by ")) {
                $strSQL = preg_replace("/group by /i", "where " . $sFilter . " group by ", $strSQL);
            } elseif (stristr($strSQL, "order by ")) {
                $strSQL = preg_replace("/order by /i", "where " . $sFilter . " order by ", $strSQL);
            }
            
            $_SESSION["AssocFromSql_Search"][$this->sFormName . "_" . $strName] = $strSQL;
            $arSearchFields = $this->sFormName . "_" . $strName;
        
        } else {
            $this->_getValuesFromSQL($oConnection, $strSQL, $aDataValues, $aBind);
        }
        
        if (!$bReadOnly) {
            //Instance de l'Element Combo ou multiCombo
            $oElement = new Pelican_Form_Element_Select($strName);
            $aDecorator = array('Combo');
            
			$oElement->setRegisterInArrayValidator(false);
            //Gestion d'erreur
            if ($bRequired == true)
                $oElement->addValidator(new Pelican_Validate_Combo());
								
        } else {
            //Instance de l'Element ReadOnly
            $oElement = new Pelican_Form_Element_Xhtml($strName);
            $aDecorator = array('ReadOBox');
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bMultiple', $bMultiple);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('bChoisissez', $bChoisissez);
        
        //Element		
        $oElement->addMultiOptions($aDataValues);
        $oElement->setValue($aSelectedValues);
        $oElement->setDecorators($aDecorator);
        $oElement->setLabel($strLib);
        $oElement->setRequired($bRequired);
        $oElement->setAttribs(array('size' => $iSize, 'width' => $iWidth));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère une combo à  partir d'une table
     *
     * @return string
     * @param Pelican_Db $oConnection Objet connection à  la base
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param string $strTableName Nom de la table pour les valeurs sans $this->_sTablePrefix
     * @param string $strRefTableName Nom de la table de jointure où trouver les valeurs sélectionnées : "" par défaut
     * @param string $iID id auquel sont associées les valeurs sélectionnées : 0 par défaut
     * @param mixed $aSelectedValues Tableau des valeurs sélectionnées (liste des id) : "" par défaut
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string $iSize Taille d'affichage de la liste : 1 par défaut
     * @param boolean $bMultiple Sélection multiple : false par défaut
     * @param string $iWidth Largeur du contrôle : "" par défaut
     * @param boolean $bChoisissez Affiche le message "->Choisissez" en début de liste : true par défaut
     * @param boolean $bEnableManagement Accès à  la popup d'ajout dans la table de référence : false par défaut
     */
    function createCombo(&$oConnection, $strName, $strLib, $strTableName, $strRefTableName = "", $iID = 0, $aSelectedValues = "", $bRequired = false, $bReadOnly = false, $iSize = "1", $bMultiple = false, $iWidth = "", $bChoisissez = true, $bEnableManagement = false, $bFormOnly = false, $strEvent = "")
    {
        $aDataValues = array();
        $aTmpSelectedValues = array();
        $this->_getValues($oConnection, $strTableName, $strRefTableName, $iID, $aDataValues, $aTmpSelectedValues);
        if ($aSelectedValues == "")
            $aSelectedValues = $aTmpSelectedValues;
        
        if (!$bReadOnly) {
            //Instance de l'Element Combo ou multiCombo
            $oElement = new Pelican_Form_Element_Select($strName);
            $aDecorator = array('Combo');
            
            $oElement->setRegisterInArrayValidator(false);
            //JS
            if ($bEnableManagement) {
                $this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');
                $this->addJS('/library/Pelican/Form/public/js/xt_list_fonctions.js');
            }
            
            //Gestion d'erreur
            if ($bRequired == true)
                $oElement->addValidator(new Pelican_Validate_Combo());
        } else {
            //Instance de l'Element ReadOnly
            $oElement = new Pelican_Form_Element_Xhtml($strName);
            $aDecorator = array('ReadOBox');
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bMultiple', $bMultiple);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('bChoisissez', $bChoisissez);
        $oElement->setPropertie('bEnableManagement', $bEnableManagement);
        $oElement->setPropertie('strTableName', $strTableName);
        
        //Element
        $oElement->addMultiOptions($aDataValues);
        $oElement->setValue($aSelectedValues);
        $oElement->setDecorators($aDecorator);
        $oElement->setLabel($strLib);
        $oElement->setRequired($bRequired);
        $oElement->setAttribs(array('size' => $iSize, 'width' => $iWidth));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * idem que createCombo mais avec jointure sur une table associée
     *
     * @return string
     * @param Pelican_Db $oConnection Objet connection à  la base
     * @param $strComplement Suffixe à  utiliser en complément du nom de table et pour le champ de jointure pour la table complémentaire
     * @param $strComplementValue Valeur du champ de jointure
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param string $strTableName Nom de la table pour les valeurs sans $this->_sTablePrefix
     * @param string $strRefTableName Nom de la table de jointure où trouver les valeurs sélectionnées : "" par défaut
     * @param string $iID id auquel sont associées les valeurs sélectionnées : 0 par défaut
     * @param mixed $aSelectedValues Tableau des valeurs sélectionnées (liste des id) : "" par défaut
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string $iSize Taille d'affichage de la liste : 1 par défaut
     * @param boolean $bMultiple Sélection multiple : false par défaut
     * @param string $iWidth Largeur du contrôle : "" par défaut
     * @param boolean $bChoisissez Affiche le message "->Choisissez" en début de liste : true par défaut
     * @param boolean $bEnableManagement Accès à  la popup d'ajout dans la table de référence : false par défaut
     */
    function createComboJoin(&$oConnection, $strComplement, $strComplementValue, $strName, $strLib, $strTableName, $strRefTableName = "", $iID = 0, $aSelectedValues = "", $bRequired = false, $bReadOnly = false, $iSize = "1", $bMultiple = false, $iWidth = "", $bChoisissez = true, $bEnableManagement = false, $aBind = array())
    {
        
        $sql = " SELECT
				" . $this->_sTablePrefix . $strTableName . "." . $strTableName . $this->_sTableSuffixeId . " id,
				" . $strTableName . $this->_sTableSuffixeLabel . " lib
				FROM
				" . $this->_sTablePrefix . $strTableName . ",
				" . $this->_sTablePrefix . $strTableName . "_" . $strComplement . "
				where " . $strComplement . $this->_sTableSuffixeId . "='" . $strComplementValue . "'
				and " . $this->_sTablePrefix . $strTableName . "." . $strTableName . $this->_sTableSuffixeId . "=" . $this->_sTablePrefix . $strTableName . "_" . $strComplement . "." . $strTableName . $this->_sTableSuffixeId . "
				ORDER BY lib";
        $aDataValues = array();
        $this->_getValuesFromSQL($oConnection, $sql, $aDataValues, $aBind);
        
        if (!$bReadOnly) {
            //Instance de l'Element Combo ou multiCombo
            $oElement = new Pelican_Form_Element_Select($strName);
            $aDecorator = array('Combo');
            
            $oElement->setRegisterInArrayValidator(false);
            //JS
            if ($bEnableManagement) {
                $this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');
                $this->addJS('/library/Pelican/Form/public/js/xt_list_fonctions.js');
                
                //Gestion d'erreur
                if ($bRequired == true)
                    $oElement->addValidator(new Pelican_Validate_Combo());
            }
        } else {
            //Instance de l'Element ReadOnly
            $oElement = new Pelican_Form_Element_Xhtml($strName);
            $aDecorator = array('ReadOBox');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('bMultiple', $bMultiple);
        $oElement->setPropertie('bChoisissez', $bChoisissez);
        $oElement->setPropertie('strTableName', $strTableName);
        
        //Element
        $oElement->addMultiOptions($aDataValues);
        $oElement->setValue($aSelectedValues);
        $oElement->setDecorators($aDecorator);
        $oElement->setLabel($strLib);
        $oElement->setRequired($bRequired);
        $oElement->setAttribs(array('size' => $iSize, 'width' => $iWidth));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un champ de sélection de Contenu editorial
     *
     * 2 modes possibles : sélection simple (iSize =1) ou sélection multiple
     * <code>
     * $aSelectedValues = array("1"=>"test1","2"=>"test2");
     * $oForm->createContentFromList("Contenu", "Contenu", $aSelectedValues, true, false, 5);
     * </code>
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param mixed $aSelectedValues Tableau des valeurs sélectionnées (id=>lib)
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string$iSize Taille d'affichage de la liste : 5 par défaut
     * @param string $iWidth Largeur du contrôle : 200 par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     * @param boolean $bSingle Génère un nom de champ sans [] : false par défaut
     * @param string $sContentType Appliquer un filtre sur le type de contenu passé en paramètre (ce paramètre peut être un ensemble d'id séparés par des )
     * @param boolean $bEnableOrder Affichage des fonctions de tri de la liste : false par défaut
     */
    function createContentFromList($strName, $strLib, $aSelectedValues = "", $bRequired = false, $bReadOnly = false, $iSize = "5", $iWidth = 200, $bFormOnly = false, $bSingle = false, $sContentType = "", $bEnableOrder = false, $siteExterne = "")
    {
        
        if (!is_array($aSelectedValues)) {
            if ($aSelectedValues != "") {
                $aSelectedValues = array($aSelectedValues);
            } else {
                $aSelectedValues = array();
            }
        }
        if (!$bReadOnly) {
            //Instance de l'Element Combo ou multiCombo
            $oElement = new Pelican_Form_Element_Select($strName);
            $aDecorator = array('Content');
            $oElement->addMultiOptions($aSelectedValues);
            $oElement->setRegisterInArrayValidator(false);
            if (!$bSingle) {
                $oElement->setAttrib('multiple', 'multiple');
            }
            if ($bRequired) {
                $oElement->addValidator(new Pelican_Validate_Content());
            }
            //JS
            self::$_strCheck .= "selectAll(document." . $this->_aProperties['strFormName'] . ".elements['" . $strName . "']);\n";
            $this->addJS('/library/Pelican/Form/public/js/xt_list_fonctions.js');
            $this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');
            if ($bEnableOrder)
                $this->addJS('/library/Pelican/Form/public/js/xt_ordered_list_fonctions.js');
        } else {
            //Instance de l'Element ReadOnly
            $oElement = new Pelican_Form_Element_Xhtml($strName);
            $aDecorator = array('ReadOContent');
            $oElement->setValue($aSelectedValues);
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout des proporties de Pelican
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('bEnableOrder', $bEnableOrder);
        $oElement->setPropertie('sContentType', $sContentType);
        $oElement->setPropertie('siteExterne', $siteExterne);
        //Element
        $oElement->setDecorators($aDecorator);
        $oElement->setLabel($strLib);
        $oElement->setRequired($bRequired);
        $oElement->setAttribs(array('size' => $iSize, 'width' => $iWidth));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un éditeur DHTML
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param string $strValue Valeur du champ : "" par défaut
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param boolean bPopup Affiche en popup ou dans la page : true par défaut
     * @param string $strSubFolder répertoire racine de la médiathèque appelée du miniword
     * @param integer $Width
     * @param integer $Height
     * @param mixed $limitedConf identifiant du filtre à appliquer à la confiration de l'éditeur (dans /application/configs/editor.ini.php, $_LIMITED)
     * @param integer $stepResizeEditor nb pixel utilisé pour flèches permettant de retailler la fenêtre d'édition en mode non popup. -1 par défaut (soit flèches non générées), si 0, prend la valeur définie en constante
     * @return string
     */
    function createEditor($strName, $strLib, $bRequired = false, $strValue = "", $bReadOnly = false, $bPopup = true, $strSubFolder = "", $iWidth = 600, $iHeight = 400, $limitedConf = "")
    {
    	$oElement = new Pelican_Form_Element_Xhtml($strName);
     	$this->tinyMce = true;
		$aDecorator = array('Editor', 'Default');
		
    	if ($bPopup || $bReadOnly) {
    		if (!$bReadOnly) {
    			$this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');
    			//Clavier Virtuel
            	$this->_iCountVirtualK ++;
    		}
		} else {
		    $this->aEditor[] = $strName;
			if (! $bReadOnly) {
				$this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
			}						
			if (isset($this->aEditor)) {
				self::addHidden("editorImageList[]", implode("#", $this->aEditor));
			}
		}
        		
        //Properties
        $oElement->setPropertie('bPopup', $bPopup);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('limitedConf', $limitedConf);
        $oElement->setPropertie('strSubFolder', $strSubFolder);
        
        //Element
        $oElement->setDecorators($aDecorator);
        $oElement->setLabel($strLib);		
		$oElement->setRequired($bRequired);
        $oElement->setValue($strValue);
        $oElement->setAttribs(array('width' => $iWidth, 'height' => $iHeight));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Affiche un couple Libellé/Valeur, non modifiable
     *
     * @return string
     * @param string $strLib Libellé du champ
     * @param string $strValue Valeur du champ
     */
    function createLabel($strLib, $strValue, $bToggle = false, $strLib2 = "")
    {
        //Instance de l'Element
        $oElement = new Pelican_Form_Element_Xhtml('createLabel');
		
        if ($bToggle) {
            $aDecorator = array('Separator', 'Label');
            $oElement->setLabel2($strLib2);          
        } else {
             $aDecorator = array('ReadO', 'Default');
            //pas de hidden
            $oElement->setPutHidden(false);
        }
       
        $oElement->setDecorators($aDecorator);
        $oElement->setLabel($strLib);
        $oElement->setValue($strValue);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un champ de type Hidden
     *
     * ATTENTION : si le champ a déja été créer avant, la commande est ignorée
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strValue Valeur du champ : "" par défaut
     * @param string $bGetHTML récupération du retour de la fonction (utilisation interne) : false par défaut
     * @param string $bMultiple rajoute de "[]" pour les input multiples : false par défaut
     */
    function createHidden($strName, $strValue = "", $bGetHTML = false, $bMultiple = false)
    {
        $strName = $strName . ($bMultiple ? "[]" : "");
		/* Bidouille pour faire fonctionner le tout */
		static $iIncrementByName = array();
		if (substr($strName, -2) == '[]') {
			if (!$iIncrementByName[$strName]) {
				$iIncrementByName[$strName] = 0;
			}
			$sOldName = $strName;
			$strName = str_replace('[]', '['.$iIncrementByName[$strName].']', $strName);
			$iIncrementByName[$sOldName]++;
		}
		
        if ($bGetHTML == true) {
            $oElement = new Pelican_Form_Element_Xhtml($strName);
            if ($strValue != "") {
                $oElement->setValue(str_replace("\"", "&quot;", $strValue));
            }
            $oElement->setDecorators(array('Hidden'));
            $sTmp = $this->addElement($oElement);
		
			if ($this->bDirectOutput == false) {
				return $sTmp;
			}
        } else {
            $this->addHidden($strName, $strValue);
        }
    }

    /**
     * Génère un bouton
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ : "" par défaut
     * @param string $strFunction Fonction js à exécuter quand clic du bouton : "" par défaut
     * @param bolean $bDisable bolean indiquant si le bouton à  generer est desactiver ou
     */
    function createButton($strName, $strLib = "", $strFunction = "", $bDisable = false)
    {
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        $oElement->setHelper('formButton');
        $oElement->setValue($strLib);
        if ($strFunction != "") {
            if ($strFunction == "close") {
                $strFunction = "javascript:self.close();";
            }
            $oElement->setAttrib('onclick', $strFunction);
        }
        if ($bDisable) {
            $oElement->setAttrib('disabled', 'disabled');
        }
        $aDecorator = array('Standard');

        $oElement->setAttrib('class', 'button');
        $oElement->setDecorators($aDecorator);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un bouton de type Reset
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ : "" par défaut
     */
    function createReset($strName, $strLib = "")
    {
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        $oElement->setHelper('formReset');
        $oElement->setValue($strLib);
        $oElement->setAttribs(array('class' => 'button'));
        $aDecorator = array('Standard');
       
        $oElement->setDecorators($aDecorator);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un bouton de soumission du formulaire
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ : "" par défaut
     * @param string $strImage Nom et chemin de l'image : "" par défaut
     * @param string $iWidth Largeur de l'image : "" par défaut
     * @param string $iHeight Hauteur de l'image : "" par défaut
     * @param boolean $bDisable  booleen indiquant si le bouton est desactiv? ou pas : "false" par défaut
     * @param string $strEvent js event
     */
    function createSubmit($strName, $strLib = "", $strImage = "", $iWidth = "", $iHeight = "", $bDisable = false, $strEvent = "")
    {
        //Instance de l'Element
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        if ($strImage != "") {
            $oElement->setHelper('formImage');
            $oElement->setAttribs(array("width" => $iWidth, "height" => $iHeight, "alt" => $strLib, "border" => 0));
            $oElement->setValue($strImage);
        } else {
            $oElement->setHelper('formSubmit');
            $oElement->setAttribs(array('class' => 'button'));
            $oElement->setValue($strLib);
            if ($bDisable)
                $oElement->setAttrib('disabled', 'disabled');
        }
        $aDecorator = array('Standard');
		 
        //Properties
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setDecorators($aDecorator);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère une Pelican_Index_Frontoffice_Zone de saisie texte
     *
     * Il est possible de passer un tableau en tant que valeur
     * => les données seront séparées par un retour chariot
     * => il faut ensuite utiliser la fonction splitTextArea pour retrouver un tableau de données à la Soumission du formulaire
     *
     * @return  string
     * @param  string  	$strName  Nom du champ
     * @param  string  	$strLib  Libellé du champ
     * @param  boolean 	$bRequired  Champ obligatoire : false par défaut
     * @param  string 	$strValue  Valeur du champ : "" par défaut
     * @param  string 	$iMaxLength Nb de caractéres maximum : "" par défaut
     * @param  boolean 	$bReadOnly  Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param  string 	$iRows  Nombre de lignes : 5 par défaut
     * @param  string 	$iCols   Nombre de colonnes : 30 par défaut
     * @param  boolean 	$bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     * @param  string 	$wrap Paramétre wrap du textarea
     * @param  boolean 	$bcountchars Affiche le comptage des caractéres tapés
     */
    function createTextArea($strName, $strLib, $bRequired = false, $strValue = "", $iMaxLength = "", $bReadOnly = false, $iRows = 5, $iCols = 30, $bFormOnly = false, $wrap = "", $bcountchars = true, $strEvent = "")
    {
        if (is_array($strValue)) {
            $strValue = implode("\r\n", $strValue);
        }
        
        //Instance de l'Element
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        if ($bReadOnly) {
            $aDecorator = array('ReadO');
        } else {
            $oElement->setAttribs(array('rows' => $iRows, 'cols' => $iCols));
            $aDecorator = array('TextArea');
            
            //JS
            $this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
            
            //VirtualKeyboard
            $this->_iCountVirtualK ++;
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Properties	
        $oElement->setPropertie('wrap', $wrap);
        $oElement->setPropertie('iCols', $iCols);
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('iMaxLength', $iMaxLength);
        $oElement->setPropertie('bcountchars', $bcountchars);
        
        //Element
        $oElement->setDecorators($aDecorator);
        $oElement->setLabel($strLib);
        $oElement->setValue($strValue);
        $oElement->setRequired($bRequired);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un champ de génération de texte en image
     *
     * L'image est générée en Pelican_Cache pour la prévisualisation en direct mais sera générée définitivement au premier appel en front
     * Cet appel se fait en définissant le src suivant :
     * <code>
     * <img src=Pelican::$config["MEDIA_LIB_PATH"]."/image_title.php?text=Texte%20généré&size=2">
     * </code>
     *
     * @return string
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param string $strValue Valeur du champ
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param string $iSize Taille de génaration de l'image : 1 par défaut (correspond à un pas de 195 pixels de largeur) => 4 pas pour du 800x600
     * @param boolean $bUpper Mise en majuscule du texte : false par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     */
    function createImageTitle($strName, $strLib, $bRequired = false, $strValue = "", $bReadOnly = false, $iSize = "1", $bUpper = false, $bFormOnly = false)
    {
        
        if ($bUpper) {
            $strValue = strToUpper($strValue);
        }
        
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        if (!$bReadOnly) {
            $aDecorator = array('ImageTitle');
        } else {
            $aDecorator = array('ReadOImageTitle');
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Properties
        $oElement->setPropertie('isize', $iSize);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        
        //Element
        $oElement->setDecorators($aDecorator);
        $oElement->setRequired($bRequired);
        $oElement->setValue($strValue);
        $oElement->setLabel($strLib);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Appel à une mediathèque ou à une popup d'upload (gestion de fichiersde type "image", "file" ou "flash" avec gestion ou non en base de données et génération ou non de vignettes à  la volée.
     *
     * @return  string
     * @param  string   $strName   Nom du champ
     * @param  string   $strLib   Libellé du champ
     * @param  boolean  $bRequired   Champ obligatoire : false par défaut
     * @param  string   $strType  Type de fichier (image, file ou flash) : "image" par défaut
     * @param  string   $strSubFolder  Sous-répertoire de départ (chemin relatif par rapport au répertoire d'upload) : "" par défaut
     * @param  string   $strValue   Valeur du champ : "" par défaut
     * @param  boolean  $bReadOnly   Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param  boolean  $bLibrary   Utilisation de la Pelican_Media library (true) ou d'une popup d'upload (false) : true par défaut
     * @param  boolean  $bFormOnly   Génération du champ uniquement, sans libellé : false par défaut
     */
    function createMedia($strName, $strLib, $bRequired = false, $strType = "image", $strSubFolder = "", $strValue = "", $bReadOnly = false, $bLibrary = true, $bFormOnly = false)
    {
        
        $oElement = new Pelican_Form_Element_Xhtml($strName);
		
        if (!$bReadOnly) {
             $aDecorator = array('Media');
			
            //JS
            $this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
            $this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');
        } else {		
			$aDecorator = array('ReadOMedia');
		}
		
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Properties
        $oElement->setPropertie('strType', $strType);
        $oElement->setPropertie('bLibrary', $bLibrary);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('strSubFolder', $strSubFolder);
        
        //Element
        $oElement->setDecorators($aDecorator);
        $oElement->setRequired($bRequired);
        $oElement->setValue($strValue);
        $oElement->setLabel($strLib);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un contrôle de sélection de fichier joint de type fichier via la médiathèque : identique à  un appel à  createMedia avec le type "file"
     *
     * @return void
     * @param  string   $strName   Nom du champ
     * @param string $strLib Libellé du champ
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param string $strSubFolder Chemin absolu du sous dossier à  utiliser
     * @param string $strValue Valeur du champ
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     */
    function createFile($strName, $strLib, $bRequired = false, $strSubFolder = "", $strValue = "", $bReadOnly = false, $bFormOnly = false)
    {
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        $aDecorator = array('ReadOMedia');
        if (!$bReadOnly) {
            array_push($aDecorator, 'Media');
            
            //JS
            $this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
            $this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Properties
        $oElement->setPropertie('strType', "file");
        $oElement->setPropertie('bLibrary', true);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('strSubFolder', $strSubFolder);
        
        //Element
        $oElement->setDecorators($aDecorator);
        $oElement->setRequired($bRequired);
        $oElement->setValue($strValue);
        $oElement->setLabel($strLib);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un contrôle de sélection de fichier joint de type image via la médiathèque : identique à  un appel à  createMedia avec le type "image"
     *
     * @param string $strName   Nom du champ
     * @param string $strLib Libellé du champ
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param string $strSubFolder Chemin absolu du sous dossier à  utiliser
     * @param string $strValue Valeur du champ
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     */
    function createImage($strName, $strLib, $bRequired = false, $strSubFolder = "", $strValue = "", $bReadOnly = false, $bFormOnly = false)
    {
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        $aDecorator = array('ReadOMedia');
        if (!$bReadOnly) {
            array_push($aDecorator, 'Media');
            
            //JS
            $this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
            $this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Properties
        $oElement->setPropertie('strType', "image");
        $oElement->setPropertie('bLibrary', true);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('strSubFolder', $strSubFolder);
        
        //Element
        $oElement->setDecorators($aDecorator);
        $oElement->setRequired($bRequired);
        $oElement->setValue($strValue);
        $oElement->setLabel($strLib);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un contrôle de sélection de fichier joint de type flash via la médiathèque : identique à  un appel à  createMedia avec le type "flash"
     *
     * @param string $strName   Nom du champ
     * @param string $strLib Libellé du champ
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param string $strSubFolder Chemin absolu du sous dossier à  utiliser
     * @param string $strValue Valeur du champ
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     */
    function createFlash($strName, $strLib, $bRequired = false, $strSubFolder = "", $strValue = "", $bReadOnly = false, $bFormOnly = false)
    {
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        $aDecorator = array('ReadOMedia');
        if (!$bReadOnly) {
            array_push($aDecorator, 'Media');
            
            //JS
            $this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
            $this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Properties
        $oElement->setPropertie('strType', "flash");
        $oElement->setPropertie('bLibrary', true);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('strSubFolder', $strSubFolder);
        
        //Element
        $oElement->setDecorators($aDecorator);
        $oElement->setRequired($bRequired);
        $oElement->setValue($strValue);
        $oElement->setLabel($strLib);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Génère un contrôle de sélection de fichier joint par upload direct, par défaut tout type de fichier est autorisé
     *
     * @return void
     * @param  string   $strName   Nom du champ
     * @param string $strLib Libellé du champ
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param string $strType Type de fichier : all, image, file, flash
     * @param string $strSubFolder Chemin absolu du sous dossier à utiliser
     * @param string $strValue Valeur du champ
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     */
    function createUpload($strName, $strLib, $bRequired = false, $strType = "all", $strSubFolder = "", $strValue = "", $bReadOnly = false, $bFormOnly = false)
    {
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        $aDecorator = array('ReadOMedia');
        if (!$bReadOnly) {
            array_push($aDecorator, 'Media');
            
            //JS
            $this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
            $this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');
        }
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Properties
        $oElement->setPropertie('strType', $strType);
        $oElement->setPropertie('bLibrary', false);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('strSubFolder', $strSubFolder);
        
        //Element
        $oElement->setDecorators($aDecorator);
        $oElement->setRequired($bRequired);
        $oElement->setValue($strValue);
        $oElement->setLabel($strLib);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

	/**
     * Génère un tableau croisé avec checkbox ou radio
     *
     * @return  string
     * @param  Pelican_Db  $oConnection   Objet connection à la base
     * @param  string   $strName    Nom du champ
     * @param string $strLib Libellé du champ
     * @param  string  $strQueryColumn  Requete de recuperation des abscisses
     * @param  string  $strQueryRow  Requete de recuperation des ordonnees
     * @param  string  $strQueryData  Requete de recuperation des valeurs selectionnes
     * @param  string   $iFilterID    = ""
     * @param  string   $strFilterColumn  = ""
     * @param  boolean  $bHelpButtons   Affiche les boutons pour cocher les cases automatiquement : true par défaut
     * @param  boolean  $bReadOnly    Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     */
    function createTabCroiseGenerique(&$oConnection, $strName, $strLib, $strQueryColumn, $strQueryRow, $strQueryData, $iFilterID = "", $strFilterColumn = "", $bHelpButtons = true, $bRadio = false, $bReadOnly = false, $bFormOnly = false) {
		
		
		if ($bHelpButtons) {
			$this->addJS('/library/Pelican/Form/public/js/xt_crosstab_fonctions.js');
		}
		
		$oElement = new Pelican_Form_Element_Xhtml($strName);
		$aDecorator = array('TabCroiseGenerique');
		
		//Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
		
        $oElement->setPropertie('bRadio', $bRadio);
        $oElement->setPropertie('iFilterID', $iFilterID);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('strQueryRow', $strQueryRow);
        $oElement->setPropertie('strQueryData', $strQueryData);
        $oElement->setPropertie('bHelpButtons', $bHelpButtons);
        $oElement->setPropertie('strQueryColumn', $strQueryColumn);
        $oElement->setPropertie('strFilterColumn', $strFilterColumn);
        
		$oElement->setDecorators($aDecorator);		
        $oElement->setConnection($oConnection);
		$oElement->setLabel($strLib);
		$sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
	}
	
	/**
     * Mise à  jour des données liées à  un objet de Tableau Croisé
     *
     * @return void
     * @param Pelican_Db  $oConnection   Objet connection à  la base
     * @param string  $strName   Nom du champ de formulaire
     * @param int   $iID     id de l'enregistrement
     * @param string  $strQueryColumn  Requete de recup des id abscisse
     * @param string  $strQueryRow  Requete de recup des id ordonnee
     * @param string  $strAbsFieldName Nom de la colonne id abscisse
     * @param string  $strOrdFieldName Nom de la colonne id ordonnee
     * @param string  $strTableName  Nom de la table de liaison
     * @param string  $strIDFieldName  Nom de la colonne id enregistrement
     */
    function recordTabCroiseGenerique(&$oConnection, $strName, $iID = "", $strQueryColumn, $strQueryRow, $strAbsFieldName, $strOrdFieldName, $strTableName, $strIDFieldName)
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
                    $strSQL = "delete from " . $strTableName . " where " . $strIDFieldName . " = '" . $iID . "' AND " . $strOrdFieldName . " in (" . $listeOrdonnees . ")";
                    $iFilter = Pelican_Db::$values[$strName . "_Filter"];
                    if ((Pelican_Db::$values[$strName . "_FilterC"] != "") && ($iFilter != "")) {
                        $strSQL .= " and " . Pelican_Db::$values[$strName . "_FilterC"] . " = " . $iFilter;
                    }
                    $oConnection->Query($strSQL);
                }
            
            } else {
                $aOrdonnees = array();
            }
            
            Pelican_Db::$values[$strIDFieldName] = $iID;
            if (Pelican_Db::$values[$strName . "_is_radio"]) {
                foreach ($aOrdonnees as $iY) {
                    if (isSet(Pelican_Db::$values[$strName . "_Y" . $iY])) {
                        Pelican_Db::$values[$strAbsFieldName] = Pelican_Db::$values[$strName . "_Y" . $iY];
                        Pelican_Db::$values[$strOrdFieldName] = $iY;
                        if (Pelican_Db::$values[$strName . "_Y" . $iY]) {
                            $oConnection->insertQuery($strTableName);
                        }
                    }
                }
            } else {
                foreach ($aOrdonnees as $iY) {
                    foreach ($aAbscisses as $iX) {
                        if (isSet(Pelican_Db::$values[$strName . "_Y" . $iY . "_X" . $iX])) {
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
     *
     * @param string $strName Nom du champ
     * @param string $strLib Libellé du champ
     * @param boolean $bRequired Champ obligatoire : false par défaut
     * @param string $strValue Valeur du champ
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     * @param string $strEvent Handler d'événements sur le champ : "" par défaut
     */
    function createMap($strName, $strLib, $bRequired = false, $googleKey = "", $strAddressValue = "", $strLatValue = "", $strLongValue = "", $bReadOnly = false, $bFormOnly = false, $strEvent = "", $width = "470", $height = "200")
    {
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        $aDecorator = array('Map');
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //Rajout du nom dans le tableau
        array_push($this->_gmapsName, $strName);
        
        if ($bRequired) {
            $oElement->addValidator(new Pelican_Validate_Map($strName));
        }
        //JS
        if (!$bReadOnly) {
            $this->addJS('/library/Pelican/Form/public/js/xt_num_controls.js');
            $this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
        }
        $this->addJS('/library/Pelican/Form/public/js/xt_map_fonctions.js');
        
        //Virtual keyboard
        $this->_iCountVirtualK ++;
        
        //Properties    	
        $oElement->setPropertie('width', $width);
        $oElement->setPropertie('height', $height);
        $oElement->setPropertie('strEvent', $strEvent);
        $oElement->setPropertie('googleKey', $googleKey);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('strLatValue', $strLatValue);
        $oElement->setPropertie('strLongValue', $strLongValue);
        $oElement->setPropertie('strAddressValue', $strAddressValue);
        
        //Element
        $oElement->setDecorators($aDecorator);
        $oElement->setRequired($bRequired);
        $oElement->setValue('NotUsed');
        $oElement->setLabel($strLib);
        $oElement->setAttribs(array('width' => $width, 'height' => $height));
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Retourne une chaine en commentaire Pelican_Html
     *
     * @param  string  $txtComment Texte a mettre en commentaire Pelican_Html
     */
    function createHtmlComment($txtComment)
    {
        $oElement = new Pelican_Form_Element_Xhtml('createHtmlComment');
        $oElement->setDecorators(array('HtmlComment'));
        
        $oElement->setValue($txtComment);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    /**
     * Affiche une ligne avec un HR
     *
     * @return  string
     * @param  string  $strColor1 Couleur du HR du premier <TD>
     * @param  string  $strColor2 Couleur du HR du second <TD>
     */
    function createHR($strColor1 = "", $strColor2 = "", $colspan = "")
    {
        $oElement = new Pelican_Form_Element_Xhtml('createHR');
        $oElement->setDecorators(array('Hr'));
        
        $oElement->setPropertie('colspan', $colspan);
        $oElement->setPropertie('strColor1', $strColor1);
        $oElement->setPropertie('strColor2', $strColor2);
        
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }
    
    /**
     * Création d'un sous formulaire : utilisation autonome d'un bout de formulaire avec ses contrôles de saisie (permet un rechargement dynamique de ce bout de formulaire via comobo par exemple)
     *
     * @param  Pelican_Db $oConnection  Objet connection à  la base
     * @param  string   $strName   Nom du champ
     * @param string $strLib Libellé du champ
     * @param  string  $fileName    CHemin d'accès au fichier de formulaire à  multiplier
     * @param  mixed  $tabValues   Tableau de données (de type queryTab)
     * @param boolean $bReadOnly Affiche uniquement la valeur et pas le champ (créé un input hidden) : false par défaut
     * @param boolean $bFormOnly Génération du champ uniquement, sans libellé : false par défaut
     * @param string $strJsVar Nom de la fonction js à  exécuter au changement du formulaire
     * @param  string  $strCss    Classe CSS à  utiliser : "formsub" par défaut
     */
    function createSubForm($oConnection, $strName, $strLib, $fileName, $tabValues = "", $bReadOnly = false, $bFormOnly = false, $strJsVar = "subformjs", $strCss = "formsub")
    {
        $oElement = new Pelican_Form_Element_Xhtml($strName);
        $aDecorator = array('SubForm');
        
        //Gestion du FormOnly
        if (!$bFormOnly) {
            array_push($aDecorator, 'Default');
        }
        
        //JS
        $this->addJS('/library/Pelican/Form/public/js/xt_sub_fonctions.js');
        
        //Properties
        $oElement->setPropertie('strCss', $strCss);
        $oElement->setPropertie('strJsVar', $strJsVar);
        $oElement->setPropertie('fileName', $fileName);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('tabValues', $tabValues);
        $oElement->setPropertie('instanceForm', $this);
        
        $oElement->setConnection($oConnection);
        $oElement->setDecorators($aDecorator);
        $oElement->setLabel($strLib);
         $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }
	
	/**
     * Création d'un sous formulaire : utilisation autonome d'un bout de formulaire avec ses contrôles de saisie (permet un rechargement dynamique de ce bout de formulaire via comobo par exemple)
     * En format HMVC
	 */
	function createSubFormHmvc ($strName, $strLib, $call, $tabValues = "", $bReadOnly = false, $bFormOnly = false, $strJsVar = "subformjs", $strCss = "formsub") {
		$oElement = new Pelican_Form_Element_Xhtml($strName);
		$aDecorator = array('SubFormHmvc');
		
		//Gestion du FormOnly
		if (!$bFormOnly) {
			array_push($aDecorator, 'Default');
		}
		
		//JS
        $this->addJS('/library/Pelican/Form/public/js/xt_sub_fonctions.js');
		
		//Properties
        $oElement->setPropertie('strCss', $strCss);
        $oElement->setPropertie('strJsVar', $strJsVar);
        $oElement->setPropertie('bFormOnly', $bFormOnly);
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('tabValues', $tabValues);		
		$oElement->setPropertie('call', $call);
        //$oElement->setPropertie('instanceForm', $this);
		
		//Seters des elements
		$oElement->setDecorators($aDecorator);
        $oElement->setLabel($strLib);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
	}
	
     /**
     * Création d'un objet Multiple : répétition à  volonté d'un bout de formulaire avec ses contrôles de saisie
     *
     * ATTENTION : inclure xt_mozilla_fonctions en tout premier (avant tout autre js) pour pouvoir utiliser cette méthode avec Mozilla
     *
     * @return  string
     * @param  Pelican_Db $oConnection  Objet connection à  la base
     * @param  string   $strName    Nom du champ
     * @param  string   $strLib    Libellé du champ
     * @param  string  $fileName    CHemin d'accès au fichier de formulaire à  multiplier
     * @param  mixed  $tabValues   Tableau de données (de type queryTab)
     * @param  string  $incrementField  Nom du champ servant à  incrémenter les instances de l'objet
     * @param  boolean  $bReadOnly    Affiche uniquement les valeurs et pas les champs : false par défaut
     * @param  integer  $intMaxIterations  Nombre maximum d'itérations autorisé : "" par défaut
     * @param  boolean  $bAllowDeletion  Suppression d'instance autorisée ou non : true par défaut
     * @param  boolean  $bAllowAdd  Ajout d'instance autorisé ou non : true par défaut
     * @param  string  $strPrefixe   Préfixe des noms de champ : "multi" par défaut
     * @param  string  $line     Nom du tableau de données utilisé par le formulaire parent : "values" par défaut
     * @param  string  $strCss    Classe CSS à  utiliser : "multi" par défaut
     * @param  string  $sButtonAddMulti Libellé du boutton ajouter du multi
     */
     function createMulti(&$oConnection, $strName, $strLib, $fileName, $tabValues, $incrementField, $bReadOnly = false, $intMaxIterations = "", $bAllowDeletion = true, $bAllowAdd = true, $strPrefixe = "multi", $line = "values", $strCss = "multi", $sColspan = "2", $sButtonAddMulti = "", $complement = "") {
    	 global $_GET, $_POST,  $_SERVER, $HTTP_SESSION_VARS;
        // NÃ©cessite $multi, $values
        // ATTENTION : ajouter aux noms des champs
        // on annule temporairement le direct output s'il est dÃ©fini
        // affichage d'un sÃ©parateur
        // affichage du bouton pour les ajouts multiples
        // $limit=limitFormTable("120", "520", false);
        // souvent utilisÃ© : $readO
        
		$oView = $this->getView();
    	
        $oForm = &$this;
        $readO = $bReadOnly;

        $this->showSeparator("formsep", true, $sColspan);
        
        //Debut du multi
        $strBegin .= "<tr><td id=\"td_" . $strName . "\" colspan=\"" . $sColspan . "\" width=\"100%\">";
        $strBegin .= '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>';
     	$strBegin .= '<script type="text/javascript">';
     	
     	$strBegin .= "
     	function zendAddMulti() {
     		try 
     		{
     			$.ajax({
     				url:'toto.php',
     				async: false,
					type: 'get',
					data: '',
					dataType: 'text',
					success: function(retour) {
	     				document.getElementById('td_".$strName."').innerHTML += retour;
     				}
     			});
     		}
     		catch (err)
     		{
     			alert('Error: ' + err.description);
     		}     	
     	}
     	</script>";
     	
     
        //Fin du multi        
        $strPrefixe2 = $strPrefixe;
        $this->createHidden("prefixe_" . $strName, $strPrefixe);
        $this->createHidden("increment_" . $strName, $incrementField);
        $this->createHidden("count_" . $strName, (count($tabValues) - 1));
        if ($intMaxIterations) {
            $this->createHidden("max_" . $strName, $intMaxIterations);
        }
        $strEnd .= "<iframe src=\"/library/blank.html\" name=\"iframe_" . $strName . "\" id=\"iframe_" . $strName . "\" width=\"0\" height=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" frameborder=\"0\"></iframe>";
		$strEnd .= "</td></tr>\n";
		if (!$bReadOnly && $bAllowAdd) {
			$aAttribs['id'] = 'buttonAdd';
			$aAttribs['class'] = 'buttonmulti';
			$aAttribs['style'] = 'width:200px;';
			//$aAttribs['onClick'] = "addMulti(document.".$this->_aProperties['strFormName'].", '".$strName."','".$fileName."','".$strPrefixe2."',document.".$this->_aProperties['strFormName'].".count_".$strName.".value,'".$intMaxIterations."'," . ($bAllowDeletion ? "true" : "false") . ",'" . $complement . "')";
			$aAttribs['onClick'] = "zendAddMulti(); return false;";
			$lib = $oView->formButton($strName, ($sButtonAddMulti ? $sButtonAddMulti : t('FORM_BUTTON_ADD_MULTI') . " " . Pelican_Text::htmlentities($strLib)), $aAttribs);
			
			$strEnd .= '<tr><td class="'.$this->_aProperties['sStyleLib'].'">'.$lib.'</td><td class="'.$this->_aProperties['sStyleVal'].'"></td></tr>';
		}
        
		$oElement = new Pelican_Form_Element_Xhtml($strName);
		$oElement->setDecorators(array('Xhtml'));
		$oElement->setValue($strBegin . $strEnd);
		$this->addElement($oElement);
        // boucle sur le formulaire multiple Ã  partir du tableau de donnÃ©es
       /* $compteur = - 1;
        ob_start();    
        if (! is_array($tabValues)) {
            $tabValues = array();
        }
        $this->bMultiTrigger = true;
        $iNumItems = count($tabValues);
        $count = 0;
        $first = true;
        foreach ($tabValues as $$line) {
            $compteur ++;
            
            if ($compteur % 2) {
                $strCss2 = "background-color=#F9FDF3;";
            } else {
                $strCss2 = "background-color=#FAEADA;";
            }
            $$strPrefixe = $strPrefixe2 . $compteur . "_";
            $multi = $$strPrefixe;
           	include(Pelican::$config['LIB_ROOT'] . Pelican::$config['LIB_FORM'] . "/include_multi.php");
            // encadrement du js            
            self::$_strCheck .= "if (document.getElementById('" . $$strPrefixe . "multi_display')) {\n if (document.getElementById('" . $$strPrefixe . "multi_display').value) {\n";
            self::$_strCheck .= "}\n}\n";
            
            include($fileName);
            
            $this->addDisplayGroup($this->aMulti, $$strPrefixe);
    		$oElement = $this->getDisplayGroup($$strPrefixe);
    		$oElement->setAttrib('style', $strCss);
    		$oElement->setAttrib('style2', $strCss2);
    		$oElement->setAttrib('begin', '');
    		$oElement->setAttrib('end', '');
    		if ($first) {
    			$oElement->setAttrib('begin', $strBegin);
    			$first = false;
    		} else if ($count == ($iNumItems - 1)) {
    			$oElement->setAttrib('end', $strEnd);
    		}    		
            $count++;
            
    		$oElement->setDecorators(array('FormElements', 'Multi'));
            $this->aMulti = array();
        }
        $this->bMultiTrigger = false;
        ob_end_clean();  */
       	$this->_bUseMulti = true;
        $this->showSeparator("formsep", true, $sColspan);
        
        $this->addJS('/library/Pelican/Form/public/js/xt_num_controls.js');
        $this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
        $this->addJS('/library/Pelican/Form/public/js/xt_date_controls.js');
        $this->addJS('/library/Pelican/Form/public/js/xt_list_fonctions.js');
        $this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');
		$this->addJS('/library/Pelican/Form/public/js/xt_multi_fonctions.js');
        $this->addJS('/library/Pelican/Form/public/js/xt_mozilla_fonctions.js');
        $this->addJS('/library/Pelican/Form/public/js/xt_crosstab_fonctions.js');
        
        self::$_strCheck .= "var " .$this->_aProperties['sCheckFunction']."_multi=new Function(\"obj\",\"return true\");\n";
	}
    
	public static function headMulti ($multi, $compteur, $readO, $bAllowDeletion, $aProperties = null)
    {
		if ($aProperties == null) {
			$aProperties["sStyleLib"] = "formlib";
			$aProperties["sStyleVal"] = "formval";
		}
		
        $return = '';
        if (! isset($readO)) {
            $readO = false;
        }
        if ($bAllowDeletion) {
			$return .= "<tr><td class=\"".$aProperties["sStyleLib"]."\"> n&deg; " . ($compteur + 1) ."</td><td class=\"".$aProperties["sStyleVal"]."\">".($readO ? "" : "<input type=\"button\" class=\"buttonmulti\" value=\"" . t('FORM_BUTTON_FILE_DELETE') . "\" onclick=\"delMulti_Pelican_Form('" . $multi . "')\" />")."</td></tr>";
        }
        $return .= Pelican_Form::addHidden($multi . "multi_display", "1");
		
        return $return;
    }
	
	public function createMultiHmvc ($strName, $strLib, $call, $tabValues, $incrementField, $bReadOnly = false, $intMaxIterations = "", $bAllowDeletion = true, $bAllowAdd = true, $strPrefixe = "multi", $line = "values", $strCss = "multi", $sColspan = "2", $sButtonAddMulti = "", $complement = "") {
		$oElement = new Pelican_Form_Element_Xhtml($strName);
		$aDecorator = array('MultiHmvc');		
		
		if (! is_array($tabValues)) {
            $tabValues = array();
        }
		
        $oElement->setPropertie('bReadOnly', $bReadOnly);
        $oElement->setPropertie('tabValues', $tabValues);		
		$oElement->setPropertie('incrementField', $incrementField);
		$oElement->setPropertie('intMaxIterations', $intMaxIterations);		
		$oElement->setPropertie('bAllowDeletion', $bAllowDeletion);
		$oElement->setPropertie('bAllowAdd', $bAllowAdd);
		$oElement->setPropertie('strPrefixe', $strPrefixe);
		$oElement->setPropertie('line', $line);
		$oElement->setPropertie('strCss', $strCss);
		$oElement->setPropertie('sColspan', $sColspan);		
		$oElement->setPropertie('sButtonAddMulti', $sButtonAddMulti);		
		$oElement->setPropertie('complement', $complement);
		$oElement->setPropertie('call', $call);
		
		$this->addJS('/library/Pelican/Form/public/js/xt_num_controls.js');
        $this->addJS('/library/Pelican/Form/public/js/xt_text_controls.js');
        $this->addJS('/library/Pelican/Form/public/js/xt_date_controls.js');
        $this->addJS('/library/Pelican/Form/public/js/xt_list_fonctions.js');
        $this->addJS('/library/Pelican/Form/public/js/xt_popup_fonctions.js');		
		$this->addJS('/library/Pelican/Form/public/js/xt_multi_fonctions.js');
        $this->addJS('/library/Pelican/Form/public/js/xt_crosstab_fonctions.js');
		$this->addJS('/library/Pelican/Form/public/js/pelican_form.js');
		
		//Seters des elements
		$oElement->setDecorators($aDecorator);
        $oElement->setLabel($strLib);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
	}
	
	/**
     * Manipulation des données issues d'un POST pour créer une entrée associée à chaque instance de l'objet multiple
     *
     * Un tableau de type Array(PREFIXE_CHAMP1_1, PREFIXE_CHAMP1_2, PREFIXE_CHAMP2_2) crée un tableaau du type Array(1=>CHAMP1,2=>(CHAMP1, CHAMP2))
     *
     * @return  void
     * @param  string $strName  Identifiant de l'objet défini dans le createMulti
     * @param  string $strPrefixe Préfixe urilisé pour les nom de champs de l'objet multiple : "multi" par défaut
     */
    function readMulti($strName, $strPrefixe = "multi")
    {
        global $longueur;
        
        if ($strPrefixe) {
            if (isset($_POST['count_multi' . (Pelican_Db::$values['page'] - 1) . '_' . $strPrefixe])) {
                Pelican_Db::$values['count_' . $strPrefixe] = $_POST['count_multi' . (Pelican_Db::$values['page'] - 1) . '_' . $strPrefixe];
            }
        }
        
        $DELETE = array();
        
        $longueur = strlen($strPrefixe);
        $count = (Pelican_Db::$values["count_" . $strName] + 1);
		
        if ($count) {
            
            for ($j = 0; $j < $count + $supp; $j ++) {
                if (isset(Pelican_Db::$values[$strPrefixe . $j . '_multi_display'])) {
                    if (! Pelican_Db::$values[$strPrefixe . $j . '_multi_display']) {
                        $supp ++;
                    }
                }
            }
            
            foreach (Pelican_Db::$values as $key => $value) {
                $field = "";
                if (substr($key, 0, $longueur) == $strPrefixe) {
                    for ($j = 0; $j < $count + $supp; $j ++) {
                        if (substr($key, 0, ($longueur + strlen($j) + 1)) == $strPrefixe . $j . "_") {
                            $field = str_replace($strPrefixe . $j . "_", "", $key);
                            if ($field == "multi_display" && ! $value) {
                                $DELETE[$j] = true;
                                unset(Pelican_Db::$values[$strName][$j]);
                            }
                            if (! valueExists($DELETE, $j)) {
                                Pelican_Db::$values[$strName][$j][$field] = $value;
                            }
                        }
                        if (! valueExists($DELETE, $j)) {
                            Pelican_Db::$values[$strName][$j][Pelican_Db::$values["increment_" . $strName]] = ($j + 1);
                        }
                    }
                }
            }
        }
    }
	
    /**
     * Crée un séparateur dans le formulaire
     *
     * @return  string
     * @param  string  $strClass  Classe CSS à utiliser. exemple {background-color: #CFD6E7; border: 0px solid; line-height: 0px; padding: 0px 0px 0px 0px;}
     */
    function showSeparator($strClass = "formsep", $bDirectOutput = true) {
		$strColspan = ($this->_aProperties['sFormDisposition'] != "vertical") ? "colspan='2'" : "";
		$oElement = new Pelican_Form_Element_Xhtml('showSeparator');
		$oElement->setDecorators(array('Separator'));
		
		$oElement->setPropertie('strClass', $strClass);
		$oElement->setPropertie('strColspan', $strColspan);
		$sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }
    
    /**
     * getPageOrder
     */
    function getPageOrder($pageId, $typeId = "", $id = "") {
    	$oElement = new Pelican_Form_Element_Xhtml('getPageOrder');
    	$oElement->setDecorators(array('PageOrder'));
    	
   		$oElement->setPropertie('pageId', $pageId);
   		$oElement->setPropertie('typeId', $typeId);
   		$oElement->setPropertie('id', $id);
   		
   		$sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }
    
    /**
     * Renvoie le Js de tinyMce
     *
     * @return string
     */
	function getTiny () {
        global $_EDITOR;
        $return = "
		createToggleButtons();
		//création des boutons de switch on/off de l'editeur en mode page	
		function createToggleButtons()
		{
			var Listelement = '" . implode(',', $this->aEditor) . "';
			var mySplitResult = Listelement.split(',');
			//on liste tout les editeurs présent dans le tableau 
			for(i = 0; i < mySplitResult.length; i++){
				textareas = document.getElementById(mySplitResult[i]);
				var editorAction = document.createElement('a');
 				var ActionLabel = document.createTextNode('[" . addslashes(Pelican_Text::unhtmlentities(t('text_editor_activate'))) . "]');
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
				theme : 'bnpp',
				language : '" . strtolower(getLangueCode($user->getFavoriteLanguage())) . "',
				mode : 'exact',
				//elements : '" . implode(',', $this->aEditor) . "',
				elements : editor_id,
				entity_encoding : 'raw',
				fix_nesting : true,
				visual : true,
				CssPath : '" . $_EDITOR["CSS"] . "',
				MediaHttpPath : '" . $_EDITOR["MEDIA_HTTP"] . "',
				MediaVarPath : '" . $_EDITOR["MEDIA_VAR"] . "',
				MediaLibPath : '" . $_EDITOR["MEDIA_LIB_PATH"] . "',
				PalettePath : '" . $_EDITOR["PALETTE_PATH"] . "',
				PaletteColumns : '" . $_EDITOR["PALETTE_COLUMNS"] . "',
				PaletteList : '" . $_EDITOR["PALETTE_FILES"] . "',
				styleNames : '" . $_EDITOR["FONTSTYLE"]["ID"] . "',
				styleLibs : '" . $_EDITOR["FONTSTYLE"]["LIB"] . "',
				fontFormat : '" . $_EDITOR["FONTFORMAT"]["LIB"] . "',
				fontFormatNames : '" . $_EDITOR["FONTFORMAT"]["ID"] . "',
				fontFormatLibs : '" . $_EDITOR["FONTFORMAT"]["LIB"] . "',
				fontList : '" . $_EDITOR["FONTNAME"]["LIB"] . "',
				sizeList : '" . $_EDITOR["FONTSIZE"]["ID"] . "',
				sizeLibs : '" . $_EDITOR["FONTSIZE"]["LIB"] . "',
				plugins : 'bramus_cssextras,liststyle,betd_file,betd_orderedlist,betd_mailto,betd_flash,betd_mediadirect,betd_save,betd_media,betd_icons,betd_internallink,safari,style,table,inlinepopups,media,searchreplace,print,contextmenu,paste,visualchars,nonbreaking,xhtmlxtras,advimage,advlink',
				force_br_newlines : false,
				forced_root_block : '', // Needed for 3.x,
				// Theme options
				" . $buttontiny . "
				extended_valid_elements : 'object[classid|codebase|width|height|align],param[name|value],embed[quality|type|pluginspage|width|height|src|align]',

				media_use_script : true,

				//content_css : '" . $_EDITOR["CSS"] . "',
				content_css : '" . Pelican::$config["DESIGN_HTTP"] . "/css/editeur.css.php',
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
				var ActionLabel = document.createTextNode('[" . addslashes(Pelican_Text::unhtmlentities(t('text_editor_activate'))) . "]');
				editorAction.removeChild(oldLabel);
				editorAction.appendChild(ActionLabel);
		
				tinyMCE.activeEditor.hide();
				Activetextareas.style.display = 'none';
				editorIframe = document.getElementById('iframeText'+tinyMCE.activeEditor.id);
				editorIframe.style.display = 'inline';
				editorIframe.contentWindow.document.body.innerHTML=TinyContent;
			} else {
				editorIframe = document.getElementById('iframeText'+editor_id) 
				editorIframe.style.display = 'none';
				// on modifie le label du lien
				var editorAction = document.getElementById(editor_id+'_BT');
				var oldLabel = editorAction.firstChild;
				var ActionLabel = document.createTextNode('[" . addslashes(t('text_editor_deactivate')) . "]');
				editorAction.removeChild(oldLabel);
				editorAction.appendChild(ActionLabel);
				if (tinyMCE.get(editor_id))
					tinyMCE.get(editor_id).show();
			}
		}";
        return $return;
    }
	
    /**
     * renvoie le javascipt utile au onglet
     */
	function getTabSwitch() {
		$strTmp = "function tabSwitch(id, state) {
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
						while (obj != null && obj.tagName != \"DIV\" && obj.id.indexOf(formTab + '_tab_') == -1) {
							if (!obj.parentElement) {
								obj = obj.parentNode;
							} else {
								obj = obj.parentElement;
							}
						}
						if (obj.id.indexOf(formTab + '_tab_') != -1) {
							id = obj.id.replace(formTab + '_tab_','');
							if (currentTab != id) {
								ongletFW(id);
							}
						}
						ori.focus();
					}
					fwFocus = tabFocus\r\n";
		return $strTmp;
	}
    
    function getDisposition()
    {
		switch ($this->_aProperties['sFormDisposition']) {
		   case "vertical":
                {
                    $strTmp = "</tr>\n<tr>";
                    break;
                }
            default:
                {
                    $strTmp = "";
                    break;
                }
        }
		$this->createFreeHtml($strTmp);
        return $strTmp;
    }
	
	function getCellLib($sValue, $bRequired = false, $bReadOnly = false)
    {
    	$oElement = new Pelican_Form_Element_Xhtml('getCellLib');
    	$oElement->setDecorators(array('GetCell'));
    	$oElement->setValue($sValue);
    	
    	$oElement->setPropertie('bRequired', $bRequired);
    	$oElement->setPropertie('bReadOnly', $bReadOnly);
    	$oElement->setPropertie('tag', 'tag');
    	
    	$sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }

    function getCellVal($sValue)
    {
        $oElement = new Pelican_Form_Element_Xhtml('getCellVal');
        $oElement->setDecorators(array('GetCell'));
        $oElement->setValue($sValue);
        $sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }
    
    /**
     * declaration d'un onglet
     *
     * @param string $strId
     * @param string $strLabel
     */
    function setTab ($strId, $strLabel) {    	
    	$this->aTab[$strId] = array("id" => $strId , "label" => $strLabel);
    }
		
	/**
	 * Debut de la Pelican_Index_Tab
	 *
	 * @param string $strId
	 */
    public function beginTab($strId) {
		if (isset($this->aTab[$strId]) && !empty($this->aTab[$strId])) {			
			$strTmp = "";
			if ($this->iTabIsDraw == 0) {
				$strTmp .= $this->drawTab();
			}
			if ($this->iTabIsClosed == 0) {
				$strTmp .= $this->endTab();
			}    
			if (array_key_exists($strId, $this->aTab)) {
				$this->iTabIsClosed = 0;
				$this->iCurrentTabId = $strId;
			} else {
				$this->bAddElement = false;
			}		
			
			static $bTabIsFirst =  true;
			if ($this->bDirectOutput == false) {
				
				//petit test
				$strTmp .= "<div id=\"" . $this->sFormName . "_tab_" . $strId . "\" class=\"div_onglet\" style=\"";
				if ($bTabIsFirst == true) {
					$strTmp .= "display:block;";
					$bTabIsFirst = false;
				} else if ($bTabIsFirst == false) {
					$strTmp .= "display:none;";
				}
				$strTmp .= "\">";
				$strTmp .= beginFormTable("0", "0", "form", false, $strId);	
				return $strTmp;
			}
		}
		return 0;
    }

	/**
	 * Fin de la Pelican_Index_Tab
	 */
    public function endTab() {
		static $bTabIsFirst =  true;
    	if ($this->iCurrentTabId != null && array_key_exists($this->iCurrentTabId, $this->aTab) && !empty($this->aTabElement[$this->iCurrentTabId])) {
    		$aElementsName = $this->aTabElement[$this->iCurrentTabId];
    		$this->addDisplayGroup($aElementsName, 'tab'.$this->iCurrentTabId);
    		$oElement = $this->getDisplayGroup('tab'.$this->iCurrentTabId);
			if ($bTabIsFirst == true) {
				$oElement->setAttrib('style', 'display: block;');
				$bTabIsFirst = false;
			} else if ($bTabIsFirst == false) {
				$oElement->setAttrib('style', 'display: none;');
			}
    		$oElement->setAttrib('tabs', $this->aTab);
    		$oElement->setAttrib('id', $this->_aProperties['strFormName'] . '_tab_'. $this->iCurrentTabId);
    		$oElement->setAttrib('CurrentId', $this->iCurrentTabId);
    		$oElement->setDecorators(array('FormElements', 'DrawTabDiv'));
           	
    	}
    	$this->bAddElement = true;
    	$this->iCurrentTabId = null;
    	$this->iTabIsClosed = 1;
		
		if ($this->bDirectOutput == false) {
			$strTmp = endFormTable(false);
            $strTmp .= "</div>";
			return $strTmp;
		}
    }

    /**
     * On crée les onglets
     *
     */
    public function drawTab() {
    	$oElement = new Pelican_Form_Element_Xhtml('DrawTab');
    	$oElement->setDecorators(array('DrawTab'));
    	$oElement->setPropertie('aTab', $this->aTab);
    	$this->iTabIsDraw = 1;
    	$sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {			
			$sTmp .= "<script type=\"text/javascript\">".$this->getTabSwitch()."</script>";
			return $sTmp;
		}
    }  
    
    /**
     * ??
     */
	public function inputTaxonomy($strName = 'TAXONOMY', $strLib = "Taxonomie :", $autocompleteDb = '/actions/taxonomy/taxonomy_db.php', $objectId, $objectTypeId, $groupId = "")
    {
        require_once('Pelican/Taxonomy.php');
        $oTaxonomy = Pelican_Factory::getInstance('Taxonomy');
        $strTmp = $oTaxonomy->generateFormInput($strName, $strLib, $autocompleteDb, $objectId, $objectTypeId, $groupId);
        
        $oElement = new Pelican_Form_Element_Xhtml('inputTaxonomy');
      	$oElement->setValue($strTmp);
      	$oElement->setDecorators(array('Xhtml'));
      	$sTmp = $this->addElement($oElement);
		
		if ($this->bDirectOutput == false) {
			return $sTmp;
		}
    }
    
	/**
     * Fonction pour faire un explode des valeurs d'un champ et le remplacer par son tableau de valeur
     * Utilisé par exemple pour une saisie multiligne dans un textarea
     *
     * @return  void
     * @param  string $strName  Valeur à  traiter
     * @param  string $strSep Caractère séparateur
     */
    function splitTextarea($strValue, $strSep = "\r\n")
    {        
        /** Si Le champ contient des valeurs, on fait le traitement */
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
	---------------------------------------
	Fonctions pour créer un tableau encadrant le formulaire
	---------------------------------------

     **
     * Création du début d'un tag Table adapté à la classe Pelican_Form
     *
     * @return  string
     * @param  string $cellpadding  Marg interne des cellules : "0" par défaut
     * @param  string $cellspacing  Espacement entre les cellules :"0" par défaut
     * @param  string $class    Classe css pour la table : "form" par défaut
     * @param  boolean $bDirectOutput  true pour un affichage direct, false pour que les méthodes retournent le code Pelican_Html sous forme de texte
     * @param string $id Identifiant du tag TABLE
     */
    public function beginFormTable ($cellpadding = "0", $cellspacing = "0", $class = "form", $id = "")
    {
		$this->hideFormTabTag(array("hideTab" => true));
        $strTmp = "<table border=\"0\" cellspacing=\"" . $cellspacing . "\" cellpadding=\"" . $cellpadding . "\" class=\"" . $class . "\" id=\"tableClassForm" . $id . "\" summary=\"Formulaire\">";
		$this->createFreeHtml($strTmp);
		
		if ($this->bDirectOutput) {
            echo ($strTmp);
            return true;
        } else {
            return $strTmp;
        }
    }

    /**
     * Création d'une ligne de tableau avec des images d'1 pixel de hauteur pour figer les dimensions du tableau d'affichage du formulaire
     *
     * @return  string
     * @param  string $Width1   Largeur pour les libellés : "120" par défaut
     * @param  string $Width2   Largeur pour les valeurs  : "520" par défaut
     * @param  boolean $bDirectOutput  true pour un affichage direct, false pour que les méthodes retournent le code Pelican_Html sous forme de texte
     */
    public function limitFormTable ($Width1 = "120", $Width2 = "520")
    {
        $strTmp = "<tr><td height=\"1\"><img src=\"" . Pelican::$config["LIB_PATH"] . "/public/images/pixel.gif\" width=\"" . $Width1 . "\" height=\"1\" alt=\"\" border=\"0\" /></td><td height=\"1\"><img src=\"" . Pelican::$config["LIB_PATH"] . "/public/images/pixel.gif\" width=\"" . $Width2 . "\" height=\"1\" alt=\"\" border=\"0\" /></td></tr>\n";
		$this->createFreeHtml($strTmp);
        if ($this->bDirectOutput) {
            echo ($strTmp);
            return true;
        } else {
            return $strTmp;
        }
    
    }

    /**
     * Tag TABLE de fin de formulaire
     *
     * @return string
     * @param string $id
     * @param  boolean $bDirectOutput  true pour un affichage direct, false pour que les méthodes retournent le code Pelican_Html sous forme de texte
     */
    public function endFormTable ()
    {
		$this->hideFormTabTag(array("hideTab" => true));
        $strTmp = "</table>\n";
		$this->createFreeHtml($strTmp);
        if ($this->bDirectOutput) {
            echo ($strTmp);
            return true;
        } else {
            return $strTmp;
        }
    }
}
 
require_once(pelican_path('Html.Form'));

/*
----------------------------------------
Fonctions pour créer une Pelican_Index_Frontoffice_Zone "toggle"
---------------------------------------
*/

/**
 * Création d'un toggle
 *
 * @return  void
 * @param  string  $id   Identifiant du toggle
 * @param  string  $label  Libellé du toggle
 * @param  string  $content  Contenu du toggle : "" par défaut
 * @param  string  $state   Etat du toggle ("" pour l'ouvrir, "none" pour le masquer) : "" par défaut
 * @param  string  $width   Largeur du toggle : "90%" par défaut
 */
function createToggle($id, $label, $content = "", $closed = true, $setCookie = false, $bDirectOutput = true)
{
    
    $return = beginToggle($id, $label, $closed, $setCookie, false);
    $return .= $content;
    $return .= endToggle(false);
    
    if ($bDirectOutput) {
        echo ($return);
    } else {
        return $return;
    }
}

/**
 * Tag de début d'un toggle
 *
 * @return string
 * @param  string  $id    Identifiant du toggle
 * @param  string  $label   Libellé du toggle
 * @param  string  $state    Etat du toggle ("" pour l'ouvrir, "none" pour le masquer) : "" par défaut
 * @param  string  $width    Largeur du toggle : "90%" par défaut
 * @param  boolean $bDirectOutput  true pour un affichage direct, false pour que les méthodes retournent le code Pelican_Html sous forme de texte
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
    $strTemp .= "<tr ondblclick=\"showHideModule('" . $id . "', " . $strSetCookie . ")\">";
    $strTemp .= "<td class=\"formtoggle\" width=\"14\" valign=\"middle\"><nobr>" . $label;
    $strTemp .= Pelican_Html::nbsp() . Pelican_Html::nbsp();
    $strTemp .= "<img id=\"Toggle" . $id . "\" src=\"" . Pelican::$config['LIB_PATH']."/public/images/toggle_" . $image . ".gif\" alt=\"" . $alt . "\" hspace=\"3\" width=\"14\" height=\"12\" border=\"0\" style=\"cursor:pointer;\" onclick=\"showHideModule('" . $id . "', " . $strSetCookie . ")\" /></td>";
    $strTemp .= "</nobr></tr>";
    $strTemp .= endFormTable(false);
    $strTemp .= "<div id=\"DivToggle" . $id . "\" style=\"display:" . $state . "\">";
    if ($bDirectOutput) {
        echo ($strTemp);
    } else {
        return $strTemp;
    }

}

/**
 * Tag de fin d'un toggle
 *
 * @return  string
 * @param  string   $id    Identifiant du Toggle
 * @param  boolean  $bDirectOutput  true pour un affichage direct, false pour que les méthodes retournent le code Pelican_Html sous forme de texte
 */
function endToggle($bDirectOutput = true)
{
    $strTemp = ("</div>");
    if ($bDirectOutput) {
        echo ($strTemp);
    } else {
        return $strTemp;
    }
}
