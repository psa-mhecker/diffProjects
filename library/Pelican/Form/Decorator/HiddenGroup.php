<?php
/** Class Decorator HiddenGroup
 *  Va rajouter les hiddens entre la fin du tableau et la fin
 *  du formulaire
 * 
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 12/05/2010
 * @package Pelican + Zend
 */

Class Pelican_Form_Decorator_HiddenGroup extends Pelican_Form_Decorator_Abstract {
	/**
	 * Render de AddHidden
	 * @param string $strContent
	 * @return string
	 */
	public function render($Content) {
		$oform = $this->getElement();
		$oView = $oform->getView();
		
		//Set du path vers les View Helper
		$oView->addHelperPath('Pelican/View/Helper', 'Pelican_View_Helper');
		$aHiddens = $oform->getHiddens();
		
		if ($aHiddens != null) {
			foreach ($aHiddens as $strName => $Value) {
				if (is_array($Value)) {
					foreach ($Value as $strName => $strValue) {
						$strTmp .= $oView->formHidden($strName, $strValue);
					}
				} else {
					$strTmp .= $oView->formHidden($strName, $Value);
				}
			}
		}
		$Content .= $strTmp;
		return $Content;
	}
}
?>