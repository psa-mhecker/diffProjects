<?php
/** Class Decorator pour le createMulti
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 05/07/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Multi extends Pelican_Form_Decorator_Abstract {
	/**
	 * Render des Decorateurs
	 *
	 * @param string $Content
	 * @return string
	 */
	public function render($Content) {
		$oElement 	  = $this->getElement();
		$strName 	  = $oElement->getFullyQualifiedName();
		$oView 	 	  = $oElement->getView();
		$aAttribs	  = $oElement->getAttribs();
		
		$strCss = $aAttribs['style'];
		$strCss2 = $aAttribs['style2'];
		$strTag = '';
		if ($aAttribs['begin'] != '') {
			$strTag .= $aAttribs['begin'];
		}
		$strTag .= "<table cellspacing=\"0\" cellpadding=\"0\" style='" . $strCss2 . "' class=\"" . $strCss . "\" id=\"" . $strName . "multi_table\" width=\"100%\">";
		$strTag .= $Content;
		$strTag .= '</table>';
		if ($aAttribs['end'] != '') {
			$strTag .= $aAttribs['end'];
		}
		return $strTag;		
	}
}
?>