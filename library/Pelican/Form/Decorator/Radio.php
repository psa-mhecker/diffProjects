<?php
/** Class Decorator de l'Element Radio
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 24/03/2010
 * @package Pelican + Zend
 */
Class Pelican_form_Decorator_Radio extends Pelican_Form_Decorator_Abstract {
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {
		$aProperties    = $oElement->getProperties();
		$aValue	  	    = $oElement->getMultiOptions();
		$strName	   	= $oElement->getFullyQualifiedName();
		$aChecked		= $oElement->getValue();
		$aAttribs		= $oElement->getAttribs();
		
		$strOrientation = ($aProperties['cOrientation'] == 'h') ? "" : "<br />";
		$strTag = $oView->formBox($strName, 'radio', $aChecked, $aValue, $aAttribs, $strOrientation);
		return $strTag;
	}
}
?>