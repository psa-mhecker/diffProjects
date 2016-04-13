<?php
/** Class Decorator de Print
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 25/06/2010
 * @package Pelican + Zend
 */

Class Pelican_Form_Decorator_Print extends Pelican_Form_Decorator_Abstract {
	/**
	 * Render de Print
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