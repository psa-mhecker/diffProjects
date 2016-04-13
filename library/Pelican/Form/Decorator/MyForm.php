<?php
/** Class Decorator de form
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 30/03/2010
 * @package Pelican + Zend
 */

Class Pelican_Form_Decorator_MyForm extends Pelican_Form_Decorator_Abstract {
	/**
	 * Render de MyForm
	 * @param string $strContent
	 * @return string
	 */
	public function render($Content) {
		$oform = $this->getElement();
		$oView = $oform->getView();
		
		//Set du path vers les View Helper
		$oView->addHelperPath('Pelican/View/Helper', 'Pelican_View_Helper');
		$aAttribs = $oform->getAttribs();
		return $oView->myForm($aAttribs, $Content);
	}
}
?>