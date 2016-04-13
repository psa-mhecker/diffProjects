<?php
/** Class Decorator de l'Element Label
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 24/03/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Label extends Pelican_Form_Decorator_Abstract {
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {				
		$strName	  = $oElement->getFullyQualifiedName();
		$strValue	  = $oElement->getValue();
		$strLabel 	  = $oElement->getLabel();
		$strLabel2    = $oElement->getLabel2();
		$aProperties  = $oElement->getProperties();

		$id = "lbl" . $strLabel;
		$strSep = "</td><td class='".$aProperties['sStyleVal']."'>";
		if ($aProperties['sFormDisposition'] == "vertical") {
			$strSep = "</td></tr><tr><td class='".$aProperties['sStyleVal']."'>";
		}	
		
		$aAttribsPic['id'] 		= "Toggle" . $id ;	
		$aAttribsPic['alt'] 	= "";	
		$aAttribsPic['style'] 	= "float: right; cursor:pointer;";		
		$aAttribsPic['border']	= 0;		
		$aAttribsPic['height'] 	= 12;
		$aAttribsPic['width'] 	= 14;
		$aAttribsPic['onclick'] = "showHideModule('".$id."')";					
		$strImg = $oView->myImg("/library/public/images/toggle_close.gif", $aAttribsPic);		
		
		$strTag = "<tr><td class='".$aProperties['sStyleLib']."'>" . $strImg . $strLabel . $strSep . "&nbsp;" . $strLabel2 . "</td></tr>";
		$strTag .= '<tr id="DivToggle'.$id.'" style="display: none;"><td class="'.$aProperties['sStyleLib'].'">&nbsp;</td><td class="'.$aProperties['sStyleVal'].'">'.$strValue.'</td></tr>';
		return $strTag;
	}
}