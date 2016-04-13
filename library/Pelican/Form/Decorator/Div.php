<?php
/** Class Decorator de l'Element Div
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 21/04/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Div extends Pelican_Form_Decorator_Abstract {	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {
		$aProperties = $oElement->getProperties();
		$strValue	  = $oElement->getValue();
		$strName	  = $oElement->getFullyQualifiedName();
		
		Pelican_Form::addHidden($strName, $strValue);
		$aAttribs['border'] = 0;
		$aAttribs['style'] = "float: left;";
		$aAttribs['onclick'] = "showInputDiv('".$strName."');";
		$aAttribs['id'] = $strName . "_IMG";
		$strTag .= $oView->myImg('/library/Pelican/Form/public/images/combo.gif', $aAttribs);
		unset($aAttribs);
		$aAttribs['id'] = $strName . "_LABEL";
		$aAttribs['style'] = "float: left; font-weight: bold; margin-left: 10px;";
		$strTag .= $oView->formSpan($aAttribs, $aProperties['strLabel']);
		unset($aAttribs);
		$aAttribs['id'] = $strName . "_DIV";
		$aAttribs['class'] = "inputdiv";
		$aAttribs['style'] = "z-index: 150; display: none;";
		$strTag .= $oView->formDiv($aAttribs, $aProperties['strDivContent']);
		
		return $strTag;
	}
}
?>