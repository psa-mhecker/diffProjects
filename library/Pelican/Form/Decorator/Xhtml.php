<?php
/** Class Decorator Xhtml
 * Rajout directement le xhtml passé en Value
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 01/07/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Xhtml extends Pelican_Form_Decorator_Abstract {
	/**
	 * Render de Xhtml
	 * 
	 * @param string $strContent
	 * @return string
	 */
	public function render($Content) {
		$oElement = $this->getElement();
		$strValue = $oElement->getValue();
		return $Content . $strValue;
	}
}
?>