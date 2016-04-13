<?php
/** Class Decorator de getCellLib/getCellVal
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 22/07/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_GetCell extends Pelican_Form_Decorator_Abstract {	
	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {
		$strValue	  = $oElement->getValue();
		$aProperties  = $oElement->getProperties();
		
		$strClass = $aProperties['sStyleVal'];
		if (isset($aProperties['bRequired']) && isset($aProperties['bReadOnly']) &&
				$aProperties['tag'] == 'tag') {
			$strClass = $aProperties['sStyleLib'];
			$strValue = Pelican_Text::htmlentities($strValue) . (($aProperties['bRequired'] && ! $aProperties['bReadOnly']) ? " *" : "");
		}
		$strTag = '<td class="'.$strClass.'>'.$strValue.'</td>';
		return $strTag;
	}
}