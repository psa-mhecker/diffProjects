<?php
/** Class Decorator pour les onglets
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 21/06/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_DrawTabDiv extends Pelican_Form_Decorator_Abstract {
	/**
	 * Render des Decorateurs
	 *
	 * @param string $Content
	 * @return string
	 */
	public function render($Content) {
		$oElement 	  = $this->getElement();
		$oView 	 	  = $oElement->getView();
		if (!($oView instanceof Zend_View_Interface)) {
			$oView = new Zend_View();
			$oElement->setView($oView);
		}
		$aAttribs	  = $oElement->getAttribs();
		
		//Set du path vers les View Helper
		$oView->addHelperPath('Pelican/View/Helper', 'Pelican_View_Helper');
		
		$strTmp = beginFormTable("0", "0", "form", false, $aAttribs['CurrentId']);
		$strTmp .= $Content;
		$strTmp .= endFormTable(false);
		$iCurrentId = $aAttribs['CurrentId'];
		unset($aAttribs['CurrentId']);
		unset($aAttribs['tabs']);
		$strTag = $oView->formDiv($aAttribs, $strTmp);
		
		return $strTag;		
	}
}
?>