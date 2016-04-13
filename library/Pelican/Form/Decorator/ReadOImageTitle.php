<?php
/** Class Decorator de l'Element ReadOnly pour ImageTitle
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 24/03/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_ReadOImageTitle extends Pelican_Form_Decorator_Abstract {	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {
		$Value	  	  = $oElement->getValue();
		$strName	  = $oElement->getFullyQualifiedName();		
		$aProperties  = $oElement->getProperties();

		$aAttribsPic['id'] 		 = "imgTitle" . $strName;	
		$aAttribsPic['src'] 	 = $aProperties['isize'];
		$aAttribsPic['name'] 	 = "imgTitle" . $strName;	
		$aAttribsPic['align'] 	 = "center";
		$aAttribsPic['border'] 	 = 1;		
		$aAttribsPic['height'] 	 = 19; 
		$strSrc = "/library/image_title.php?text=" . rawurlencode($Value);
		$strImg = $oView->myImg($strSrc, $aAttribsPic);
		
		Pelican_Form::addHidden($strName, $Value);

		$strTag = $strImg;
		return $strTag;
	}
}
?>