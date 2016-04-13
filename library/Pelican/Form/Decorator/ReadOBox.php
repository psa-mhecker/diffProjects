<?php
/** Class Decorator des Elements Box en ReadOnly
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 06/05/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_ReadOBox extends Pelican_Form_Decorator_Abstract {	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement) {
		$aProperties  = $oElement->getProperties();
		$checked  	  = $oElement->getValue();
		$aValue	  	  = $oElement->getMultiOptions();
		$strName	  = $oElement->getFullyQualifiedName();
		
		$strOrientation = ($aProperties['cOrientation'] == 'h') ? " " : "<br />";
		if (is_array($checked)) {
			if (count($checked) > 1) {
				$strName = $strName . '[]';				
			}
			$Value = '';
			foreach ($checked as $check) {
				$Value .= $aValue[$check] . $strOrientation;
				Pelican_Form::addHidden($strName, $check);
			}
		} else {
			$Value = $aValue[$checked];						
			Pelican_Form::addHidden($strName, $checked);
		}
		
		$strTag = $Value;
		return $strTag;
	}
}
?>