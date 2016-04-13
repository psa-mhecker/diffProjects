<?php
/** Class Decorator de l'Element TextArea
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 13/04/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_TextArea extends Pelican_Form_Decorator_Abstract {	
	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {
		$strValue	  = $oElement->getValue();
		$strName	  = $oElement->getFullyQualifiedName();
		$aProperties  = $oElement->getProperties();
		$aAttribs 	  = $oElement->getAttribs();
				
		//Clavier virtuel
        if ($aProperties['bVirtualKeyboard']) {
        	$aAttribs["onfocus"] = "activeInput = this; PopupVirtualKeyboard.attachInput(this);";
        }
        
        //Wrap
        if ($aProperties['wrap'] != "") {
        		$aAttribs["wrap"] = $aProperties['wrap'];
        }
		//Création de la TextArea
        if ($aProperties['bcountchars']) {
			$aAttribs["onkeyup"] = "countchars(this," . ($aProperties['iMaxLength'] ? $aProperties['iMaxLength'] : 0) . ");";
        }
		$strTag = $oView->formTextarea($strName, $strValue, $aAttribs);
		
		//Création de la Div
		if ($aProperties['bcountchars']) {
			$aAttribsDiv["class"] = "countchars";
			$aAttribsDiv["style"] = "width: " . ($aProperties['iCols'] * 6) . "px;";
			$aAttribsDiv["id"] = "cnt_" . $strName . "_div";
		
			$strDiv = strlen($strValue) . ' ' . t('CHARACTER') . '' . (strlen($strValue) > 1 ? 's' : '');
			if ($aProperties['iMaxLength']) {
				$strDiv = strlen($strValue) . '/' . $aProperties['iMaxLength'] . ' ' . t('CHARACTER');
			}
			$strTag .= $oView->formDiv($aAttribsDiv, $strDiv);	
		}				
		return $strTag;
	}
}