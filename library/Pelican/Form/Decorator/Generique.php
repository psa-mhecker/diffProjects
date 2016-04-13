<?php
/** Class Decorator Generique 
 * (rajoute du code autour d'un element form)
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 01/07/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Generique extends Pelican_Form_Decorator_Abstract {
	/**
	 * Render de Form
	 * 
	 * @param string $strContent
	 * @return string
	 */
	public function render($Content) {
		$aProperties = $this->getElement()->getProperties();
		$strTag = $aProperties['aTag']['before'] . $Content;
		if (isset($aProperties['aTag']['after']) && !empty($aProperties['aTag']['after'])) {
			$strTag .= $aProperties['aTag']['after'];
		}
		return $strTag;
	}
}