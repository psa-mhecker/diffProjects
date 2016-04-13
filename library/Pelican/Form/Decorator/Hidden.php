<?php
/** Class Decorator de l'Element Hidden
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 24/03/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Hidden extends Pelican_Form_Decorator_Abstract {
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {	
		$strValue	  = $oElement->getValue();
		$strName	  = $oElement->getFullyQualifiedName();
		
		$strTag = $oView->formHidden($strName, $strValue);
		return $strTag;
	}
}