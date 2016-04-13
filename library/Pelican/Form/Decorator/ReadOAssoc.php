<?php
/** Class Decorator des Elements Assoc en ReadOnly
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 26/05/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_ReadOAssoc extends Pelican_Form_Decorator_Abstract {	
	
	//Variable pour sql
	protected $_sTableSuffixeId = '_id';
	protected $_sTableSuffixeLabel = '_label';
	protected $_sTablePrefix = 'pel_';
	
	/**
     * Retourne les valeurs de la table $strTableName=>$aDataValues et les valeurs sélectionnées de la table $strRefTableName=>$aSelectedValues
     *
     * @return void
     * @param Pelican_Db $oConnection Objet connection è  la base
     * @param string $strTableName Nom de la table pour les valeurs sans $this->_sTablePrefix : "" par défaut
     * @param string $strRefTableName Nom de la table de jointure oè¹ trouver les valeurs sélectionnées : "" par défaut
     * @param string $iID id auquel sont associées les valeurs sélectionnées : "" par défaut
     * @param mixed $aDataValues Tableau de valeurs (id=>lib)
     * @param mixed $aSelectedValues Tableau des valeurs sélectionnées (liste des id)
     * @param string $strColRefTableName Nom de la colonne dans la table de référence correspondant è  $iID : "CONTENU_ID" par défaut
     */
	function _getValues (&$oConnection, $strTableName = "", $strRefTableName = "", $iID = "", &$aDataValues, &$aSelectedValues, $strColRefTableName = "contenu_id", $strOrderColName = '', $iSiteId = '')
    {
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
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement) {		
		$aProperties  = $oElement->getProperties();
		$aValueSrc	  = $oElement->getMultiOptions();
		$strName	  = $oElement->getFullyQualifiedName();
		
		//Recuperation des données pour le createAssoc
		if (!$aValueSrc && $aProperties['strTableName'] != "") {
			$aTmpSelectedValues = array();
			$oConnection = $oElement->getConnection();
			$this->_getValues($oConnection, $aProperties['strTableName'], $aProperties['strRefTableName'], $aProperties['iID'], $aValueSrc, $aTmpSelectedValues);
		}
		
		if (!is_array($aValueSrc)) {
			$aValueSrc = array($aValueSrc);
		}
		if (!is_array($aValueDst)) {
			$aValueDst = array($aValueDst);
		}
		
		//Hidden
		Pelican_Form::addHidden($strName.'_last_selected');
		
		//Création des resultats
		$strTag = '';
		foreach ($aValueDst as $ikey => $iSelected) {
			$strTag .= 	$aValueSrc[$iSelected] . '<br />';
			Pelican_Form::addHidden($strName.'[]', $ikey);
		}
		
		return $strTag;
	}
}
?>