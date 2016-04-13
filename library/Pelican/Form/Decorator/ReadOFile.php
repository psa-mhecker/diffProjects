<?php
/** Class Decorator de l'Element File en ReadOnly
 *
 * @version 1.0
 * @author Rapha�l Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 06/05/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_ReadOFile extends Pelican_Form_Decorator_Abstract {	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {
		$Value	  	  = $oElement->getValue();
		$strName	  = $oElement->getFullyQualifiedName();
		
		Pelican_Form::addHidden($strName, $Value);

		$aAttribs['content'] = "Télécharger";
		$aAttribs['onclick'] = "window.open('".$Value."');";
		$aAttribs['style'] = "cursor: pointer;";
		$strButton = $oView->formButton(null, null, $aAttribs);
		$strTag = $strButton;
		return $strTag;
	}
}
?>