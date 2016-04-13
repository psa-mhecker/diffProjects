<?php
/** Class Decorator de l'Element ReadOnly
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 20/04/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_ReadOContent extends Pelican_Form_Decorator_Abstract {
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement) {
		$aValue	  	  = $oElement->getValue();
		$strName	  = $oElement->getFullyQualifiedName();
		
		if (is_array($aValue)) {
			if (substr($strName, -2) != '[]') {
				$strName .= '[]';
			}
			while ($aLigne = each($aValue)) {
				Pelican_Form::addHidden($strName, $aLigne['key']);
			}
			$aValue = implode("<br />", $aValue);
		}
		return $aValue;
	}
}
?>