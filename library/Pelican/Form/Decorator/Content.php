<?php
/** Class Decorator de Content
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 20/04/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Content extends Pelican_Form_Decorator_Abstract {	
	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {	
		$aProperties  = $oElement->getProperties();
		$aOptions	  = $oElement->getMultiOptions();
		$strName	  = $oElement->getFullyQualifiedName();
		$aAttribs 	  = $oElement->getAttribs();
		$aChecked	  = $oElement->getValue();
		$fhelper 	  = $oElement->helper;
		
		$strUrlImg = '/library/Pelican/Form/public/images/';
		$aAttribs['style'] =  'width:'.$aAttribs['width'].'px;';
		$aAttribs['ondblclick'] = 'assocDel(this, false);';
		unset($aAttribs['width']);
		//mise en place des valeurs
		if ($aOptions) {
			$aValues = $aOptions;
			unset($aOptions);
			if (is_array($aValues)) {
				foreach ($aValues as $strKey => $strValue) {
					if ($strValue) {
						$aOptions[$strKey] = $strValue;
					}
				}
			}
		}
		$strSelect = $oView->$fhelper($strName, $aChecked, $aAttribs, $aOptions);
		
		$strImgOrder = '';
		$strBoolOrder = '';
		if (isset($aProperties['bEnableOrder']) && $aProperties['bEnableOrder']) {
			$aPic['width'] = 13;
			$aPic['height'] = 15;
			$aPic['onClick'] = "MoveTop(document.".$aProperties['strFormName'].".elements('".$strName."[]'));";
			$strImgOrder = '<td>' . $oView->myImg($strUrlImg.'top.gif', $aPic) . '<br />';
			$aPic['onClick'] = "MoveUp(document.".$aProperties['strFormName'].".elements('".$strName."[]'));";
			$strImgOrder .= $oView->myImg($strUrlImg.'up.gif', $aPic) . '<br />';
			$aPic['onClick'] = "MoveDown(document.".$aProperties['strFormName'].".elements('".$strName."[]'));";
			$strImgOrder .= $oView->myImg($strUrlImg.'down.gif', $aPic) . '<br />';
			$aPic['onClick'] = "MoveBottom(document.".$aProperties['strFormName'].".elements('".$strName."[]'));";
			$strImgOrder .= $oView->myImg($strUrlImg.'bottom.gif', $aPic) . '</td>';
			$strBoolOrder = ", true";
		}
		
		$aAttribsSearch['class'] = 'button';
		$aAttribsSearch['onclick'] = 'searchContent("'.$aProperties['sLibPath'].$aProperties['sLibForm'].'/", "document.'.$aProperties['strFormName'].'", "'.$strName.'", "'.(((int) $aAttribs['size'] == 1) ? "single" : "multi").'", "'.$aProperties['sContentType'].'", "'.$aProperties['$siteExterne'].'", "'.base64_encode(session_id()).'");';
		$strButton = $oView->formButton("bSearch".$strName, t('FORM_BUTTON_SEARCH'), $aAttribsSearch);
		$aAttribsSearch['onclick'] = "assocDel(document.getElementById('".$strName."'), false".$strBoolOrder.");";
		$strButton .= $oView->formButton('', t('FORM_BUTTON_FILE_DELETE'), $aAttribsSearch);
		
		$strTag = '<table cellpadding="0" cellspacing="0" border="0" align="left" summary="'.t('Content').'"><tr><td>' . $strSelect . '</td>';
		$strTag .= $strImgOrder . '</tr></table>' . $strButton;
		return $strTag;
	}
}
?>