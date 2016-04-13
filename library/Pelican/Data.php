<?php
/**
 * Classe décrivant un Bean n'ayant qu'un attribut : un tableau de données
 *
 * @package Pelican
 * @subpackage Data
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 */
require_once ('Zend/Exception.php');

/**
 * Classe décrivant un Bean n'ayant qu'un attribut : un tableau de données
 *
 * @package Pelican
 * @subpackage Data
 * @author Gilles Lenormand <gilles.lenormand@businessdecision.com>
 * @since 2008-12-01
 */
class Pelican_Data {
    
    /**
     * Code retourné par le setter si les données du champs sont modifiées
     *
     */
    const DATA_MODIFIED = 1;
    
    /**
     * Code retourné par le setter si les données du champs sont insérées
     *
     */
    const DATA_SET = 2;
    
    /**
     * Code retourné par le setter si les données du champs ne sont pas modifiées
     *
     */
    const DATA_NOT_MODIFIED = 0;
    
    /**
     * Tableau de données
     *
     * @access protected
     * @var array
     */
    protected $_aData;
    
    /**
     * Constructeur
     *
     * @access public
     * @param array $aData (option) __DESC__
     * @return __TYPE__
     */
    public function __construct($aData = "") {
        if (empty($aData)) {
            $this->_aData = array();
        } else {
            $this->_aData = $aData;
        }
    }
    
    /**
     * Retourne le tableau de données
     *
     * @access public
     * @return array
     */
    public function getData() {
        return $this->_aData;
    }
    
    /**
     * Modifie le tableau de données
     *
     * @access public
     * @param array $aData __DESC__
     * @return __TYPE__
     */
    public function setData($aData) {
        $this->_aData = $aData;
    }
    
    /**
     * Getter générique
     *
     * @access public
     * @param string $dataField __DESC__
     * @return mixed
     */
    public function get($dataField) {
        if (isset($this->_aData[$dataField])) {
            return $this->_aData[$dataField];
        } else { //throw new Zend_Exception('field undefined',0);
            
        }
    }
    
    /**
     * Setter générique
     *
     * @access public
     * @param string $dataField __DESC__
     * @param mixed $dataValue __DESC__
     * @return int
     */
    public function set($dataField, $dataValue) {
        if (isset($this->_aData[$dataField])) {
            if ($this->_aData[$dataField] !== $dataValue) {
                $this->_aData[$dataField] = $dataValue;
                return self::DATA_MODIFIED;
            } else {
                return self::DATA_NOT_MODIFIED;
            }
        } else {
            $this->_aData[$dataField] = $dataValue;
            return self::DATA_SET;
        }
    }
}
?>