<?php
/** Class Decorator Default (formOnly à false)
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 25/03/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Default extends Pelican_Form_Decorator_Abstract {
	/**
	 * Render de Form
	 * 
	 * @param string $strContent
	 * @return string
	 */
	public function render($Content) {
		$oElement = $this->getElement();
		if ($oElement instanceof Zend_Form_Element) {
			$aProperties = $oElement->getProperties();
			$strLabel = $oElement->getLabel();
			
			if ($oElement->isRequired() && !$aProperties['bReadOnly']) {
				$strLabel .= ' *';
			}
		
			$strSeparator = ($aProperties['sFormDisposition'] == "vertical") ? "</tr><tr>" : '';
			$Content = '<tr><td class="'.$aProperties['sStyleLib'].'">' . $strLabel .
						'</td>'.$strSeparator.'<td class="'.$aProperties['sStyleVal'].'">' . $Content . '</td></tr>';
		}
		return $Content;
	}
}
?>