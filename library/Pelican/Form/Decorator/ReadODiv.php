<?php
/** Class Decorator de l'Element Div en ReadOnly
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 06/05/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_ReadODiv extends Pelican_Form_Decorator_Abstract {	
		/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement) {
		$aProperties  = $oElement->getProperties();
		$Value	  	  = $oElement->getValue();
		$strName	  = $oElement->getFullyQualifiedName();

		Pelican_Form::addHidden($strName, $Value);
		$strLabel  = '<span style="float: left; font-weight: bold; margin-left: 10px;" id="createDiv_LABEL">'.$aProperties['strLabel'].'</span>';
		$strTag = $strLabel;
		return $strTag;
	}
}
?>