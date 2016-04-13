<?php
/** Class Decorator de l'Element PageOrder
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 21/07/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_PageOrder extends Pelican_Form_Decorator_Abstract {	
	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {
		$aProperties = $oElement->getProperties();
		
		$aAttribs['alt'] 	  = "Ordre d'affichage";
		$aAttribs['width'] 	  = 17;
		$aAttribs['align'] 	  = "center";
		$aAttribs['style'] 	  = "cursor:pointer;";
		$aAttribs['border']   = 0;
		$aAttribs['height']   = 18;
		$aAttribs['hspace']   = 5;
		$aAttribs['onclick']  = "popupSort('".$aProperties['pageId']."','".$aProperties['typeId']."', '".$aProperties['id']."');";
		$strTag = $oView->myImg('/library/public/images/sort.gif', $aAttribs);
		return $strTag;
	}
}
?>