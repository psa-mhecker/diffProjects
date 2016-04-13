<?php
/** Class Element Xhtml
 * 
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 01/06/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Element_Xhtml extends Zend_Form_Element_Xhtml {
	/**
	 * Variable du Label
	 *
	 * @var string
	 */
	protected $_strLabel = '';
	
	/**
	 * Variable du Label2
	 * (pour CreateLabel)
	 *
	 * @var string
	 */
	protected $_strLabel2 = '';	
	
	/**
	 * Variable de la Value
	 *
	 * @var mixed
	 */
	protected $_Value = '';
	
	/**
	 * Variable d'options
	 *
	 * @var array
	 */
	protected $_aMultiOptions = null;
	
	/**
	 * Variable d'attribs
	 *
	 * @var array
	 */
	protected $_aAttrib = array();
    
    /**
     * Variable des properties Pelican
     *
     * @var array
     */
    protected  $_aProperties = array();
	
	/**
	 * Variable pour mettre un hidden
	 * ou non (dans le cas du create Label)
	 *
	 * @var unknown_type
	 */
	protected $_bPutHidden = true;
	
	/**
	 * Aideur de vue
	 *
	 * @var string
	 */
	public $helper = 'formText';
	
	/**
     * rendre ou non le password
     * @var bool
     */
    public $renderPassword = false;
    
    /**
     * Variable de conection sql
     *
     * @var unknown_type
     */
    public $oConnection = null;
    
    /**
     * Seter du nom de l'element
     * filterName=> allowBracket à true
     * 
     * @param  string $name
     * @return Zend_Form_Element
     */
    public function setName($strName)
    {
		/* Enlevé pour faire fonctionner le système de propagation pour les checkBox */
    	/*static $aIndexName = array();
    	if (substr($strName, -2) == '[]') {
    		$strTmpName = substr($strName, 0, -2);
    		if (!array_key_exists($strName, $aIndexName)) {
    			$aIndexName[$strName] = -1;    			
    		}    		
    		$aIndexName[$strName] += 1;
    		$strName = $strTmpName.'['.$aIndexName[$strName].']';
    	}*/
        
        $strName = $this->filterName($strName, true);
        if ('' === $strName) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Invalid name provided; must contain only valid variable characters and be non-empty');
        }
        $this->_name = $strName;
        return $this;
    }

	/**
	 * seter pour un label
	 *
	 * @param string $Value
	 * @return Pelican_Xhtml
	 */
	public function setLabel($Value) {
		$this->_strLabel = $Value;
		return $this;
	}

	/**
	 * geter pour un Label
	 *
	 * @return string
	 */
	public function getLabel() { 
		$value = $this->_strLabel;

        if (null === $value) {
            $value = $this->getName();
        }

        if (null !== ($translator = $this->getTranslator())) {
            return $translator->translate($value);
        }

        return $value;
	}

	/**
	 * seter pour Label2
	 *
	 * @param string $value
	 * @return Pelican_xhtml
	 */
	public function setLabel2($value) {
		$this->_strLabel2 = $value;
		return $this;
	}
	
	/**
	 * geter pour label2
	 *
	 * @return string
	 */
	public function getLabel2() {
		return $this->_strLabel2;
	}
	
	/**
	 * seter pour la Value
	 *
	 * @param mixed $Value
	 * @return Pelican_Xhtml
	 */
	public function setValue($Value) {
		$this->_Value = $Value;
		return $this;
	}
	
	/**
	 * geter de la Value
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->_Value;
	}
	
	/**
	 * add de aMultiOptions
	 *
	 * @param array $aValues
	 * @return Pelican_Element
	 */
	public function addMultiOptions($aValues) {
		$this->_aMultiOptions = $aValues;
		return $this;
	}
	
	/**
	 * geter de aMultiOptions
	 *
	 * @return array
	 */
	public function getMultiOptions() {
		return $this->_aMultiOptions;
	}
	
	/**
	 * seter d'un attrib
	 *
	 * @param string $strKey
	 * @param string $strValue
	 * @return Pelican_Element
	 */
	public function setAttrib($strKey, $strValue) {
		$this->_aAttrib[$strKey] = $strValue;
		return $this;
	}
	
	/**
	 * geter d'un attrib
	 *
	 * @param string $strKey
	 * @return string
	 */
	public function getAttrib($strKey) {
		return $this->_aAttrib[$strKey];
	}
	
	/**
	 * seter de attribs
	 *
	 * @param array $aValue
	 * @return Pelican_Element
	 */
	public function setAttribs($aValue) {
		if (is_array($aValue)) {
			foreach ($aValue as $strKey => $strValue) {
				$this->setAttrib($strKey, $strValue);
			}
		}
		return $this;
	}
	
	/**
	 * geter de attribs
	 *
	 * @return array
	 */
	public function getAttribs() {
		return $this->_aAttrib;
	}
	
	/**
	 * seter de _aProperties (1 index)
	 *
	 * @param string $strKey
	 * @param string $strValue
	 * @return Pelican_Element
	 */
	public function setPropertie($strKey, $strValue) {
		$this->_aProperties[$strKey] = $strValue;
		return $this;
	}
	
	/**
	 * geter de _aProperties (1 index)
	 *
	 * @param string $strKey
	 * @return string
	 */
	public function getPropertie($strKey) {
		return $this->_aProperties[$strKey];
	}
	
	/**
	 * geter de _aProperties (x index)
	 *
	 * @param array $aValue
	 * @return Pelican_Element
	 */
	public function setProperties($aValue) {
		if (is_array($aValue)) {
			foreach ($aValue as $strKey => $strValue) {
				$this->setPropertie($strKey, $strValue);
			}
		}
		return $this;	
	}
	
	/**
	 * geter de _aProperties (complet)
	 *
	 * @return array
	 */
	public function getProperties() {
		return $this->_aProperties;
	}
	
	/**
	 * seter de bPutHidden
	 *
	 * @param booleen $value
	 * @return Pelican_Element
	 */
	public function setPutHidden($value) {
		$this->_bPutHidden = $value;
		return $this;
	}
	
	/**
	 * geter de bPutHidden
	 *
	 * @return booleen
	 */
	public function getPutHidden() {
		return $this->_bPutHidden;
	}
	
	/**
	 * seter de l'helper
	 *
	 * @param string $Value
	 * @return Pelican_Element
	 */
	public function setHelper($Value) {
		$this->helper = $Value;
		return $this;	
	}

    /**
     * Seter de renderPassword
     * @param  bool $flag
     * @return Zend_Form_Element_Password
     */
    public function setRenderPassword($flag)
    {
        $this->renderPassword = (bool) $flag;
        return $this;
    }

    /**
     * Geter de rendrePassword
     *
     * @return bool
     */
    public function renderPassword()
    {
        return $this->renderPassword;
    }

    /**
     * est ce que c'est element
     * qui a submit le formulaire (button/Image)
     *
     * @return bool
     */
    public function isChecked()
    {
        $value = $this->getValue();
        if (empty($value)) {
            return false;
        }
        if ($value != $this->getLabel()) {
            return false;
        }
        return true;
    }
    
    /**
     * seter de connection 
     *
     * @param objet $Value
     * @return pelican_element
     */
    public function setConnection($Value) {
    	$this->oConnection = $Value;
    	return $this;
    }
    
    /**
     * geter de connection 
     *
     * @return objet
     */
    public function getConnection() {
    	return $this->oConnection;
    }
    
	/**
	 * Constructeur de la class
	 *
	 * @param string $spec
	 * @param options $options
	 */
	public function __construct($spec = '', $options = null) {
		$aName = array('createLabel', 'createHtmlComment', 
					   'createHR', 'showSeparator', 'DrawTab',
					   'createPrint', 'inputTaxonomy', 'getPageOrder',
						'getCellVal', 'getCellLib');
		static $index = 0;
		if (in_array($spec, $aName)) {
			$spec = $spec . $index;
			$index++;
		}
		parent::__construct($spec, $options);
	}
	
}
?>