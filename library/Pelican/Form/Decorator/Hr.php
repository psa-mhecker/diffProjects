<?php
/** Class Decorator de l'Element Hr
 * 
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 24/03/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Hr extends Pelican_Form_Decorator_Abstract {	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement) {	
		$aProperties  = $oElement->getProperties();
		
		$strTag = '<tr>';
		$strTag .= '<td class="'.$aProperties['sStyleLib'].'"><hr style="border: 1px solid ' . $aProperties['strColor1'] . '"></td>';
		if ($aProperties['sFormDisposition'] != 'vertical') {
			$strTag .= '<td ';
			if ($aProperties['colspan']) 
				$strTag .= 'colspan="'.$aProperties['colspan'].'"';
			$strTag .= 'class="'.$aProperties['sStyleVal'].'"><hr style="border: 1px solid ' . $aProperties['strColor2'] . '"></td>';
		}
		$strTag .= '</tr>';
		return $strTag;
	}
}
?>