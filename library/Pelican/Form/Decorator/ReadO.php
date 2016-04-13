<?php
/** Class Decorator de l'Element ReadOnly
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 24/03/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_ReadO extends Pelican_Form_Decorator_Abstract {	
	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement) {	
		$Value	  	  = $oElement->getValue();
		$bHidden	  = $oElement->getPutHidden();
		$strName	  = $oElement->getFullyQualifiedName();
		
		if ($bHidden) {
			Pelican_Form::addHidden($strName, $Value);
		}
		
		$strTag = $Value;
		return $strTag;
	}
}
?>