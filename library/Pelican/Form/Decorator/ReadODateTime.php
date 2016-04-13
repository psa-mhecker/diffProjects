<?php
/** Class Decorator de l'Element DateTime en ReadOnly
 * 
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 06/05/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_ReadODateTime extends Pelican_Form_Decorator_Abstract {	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement) {
		$strValue	  = $oElement->getValue();
		$strName	  = $oElement->getFullyQualifiedName();
		
		//explode de la value
		$strValueDate = '';
		$strValueHeure = '';
		if ($strValue != '') {
			$tabDateHeure = explode(" ", $strValue);
			if (is_array($tabDateHeure)) {
				$strValueDate = $tabDateHeure[0];
				$strValueHeure = substr($tabDateHeure[1], 0, 5);
			} else {
				$strValueDate = $tabDateHeure[0];
				$strValueHeure = "00:00";
			}	
		}
		
		Pelican_Form::addHidden($strName, $strValue);
		Pelican_Form::addHidden($strName . '_DATE', $strValueDate);
		Pelican_Form::addHidden($strName . '_HEURE', $strValueHeure);
		
		$strTag = $strValueDate . '&nbsp;&nbsp;&nbsp;' . $strValueHeure;
		return $strTag;
	}
}
?>