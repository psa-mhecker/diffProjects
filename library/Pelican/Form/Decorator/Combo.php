<?php
/** Class Decorator de Combo
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 24/03/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Combo extends Pelican_Form_Decorator_Abstract {	
	
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
		
		if (!is_array($aChecked)) {
			$aChecked = array($aChecked);
		}
		$aAttribs['style'] = 'width:'.$aAttribs['width'].'px;';
		unset($aAttribs['width']);
		if (isset($aProperties['bMultiple']) && $aProperties['bMultiple']) {
			$aAttribs['multiple'] = true;
		}
		if (isset($aProperties['sSearchQueryName']) && $aProperties['sSearchQueryName'] != "") {
			$aSearchAttribs['size'] = 14;
			$aSearchAttribs['onkeyDown'] = 'submitIndexation("'.$aProperties['sLibPath'].$aProperties['sLibForm'].'/", "","'.base64_encode($aProperties['sSearchQueryName']).'", true, '.($aProperties['bChoisissez'] ? "true" : "false").');';
			$strSearch = $oView->formText("iSearchVal".$strName, '', $aSearchAttribs);
			unset($aSearchAttribs);
			$aSearchAttribs['class'] = 'button';
			$aSearchAttribs['onclick'] = 'searchIndexation("'.$aProperties['sLibPath'].$aProperties['sLibForm'].'/", "'.$strName.'", "", eval("this.form.iSearchVal' . $strName . '.value"),"' . base64_encode($aProperties['sFormName'] . '_' . $strName) . '", 0);';
			$strSearch .= $oView->formButton("bSearch".$strName, t('FORM_BUTTON_SEARCH'), $aSearchAttribs);
		}
		
		//Premier ligne de la Combo		
		if ($aProperties['bChoisissez'] && !$aProperties['bMultiple']) {
			if ($aProperties['bChoisissez']) {
				$aRes = explode(' ', t('FORM_SELECT_CHOOSE'));
				$strTmp = '-> ' . $aRes[1];
				$aOptions = array('' => $strTmp) + $aOptions;
			} else {
				$aOptions = array('' => $aProperties['bChoisissez']) + $aOptions;
			}
		}
		
		//Popup de mannagement
		$strMannage = '';
		if (isset($aProperties['bEnableManagement']) && $aProperties['bEnableManagement']) {
			$aAttribsLink['onclick'] = "addRef('".$aProperties['sLibPath'].$aProperties['sLibForm']."/', 'document.".$aProperties['strFormName']."','".$strName."', '".$aProperties['strTableName']."', 1, true, 'add');";
			$strMannage .= $oView->formLink(t('FORM_BUTTON_ADD_VALUE'), "javascript://", $aAttribsLink);
			if (isset($aProperties['bUpdManagement']) && $aProperties['bUpdManagement']) {
				$aAttribsLink['onclick'] = "addRef('".$aProperties['sLibPath'].$aProperties['sLibForm']."/', 'document.".$aProperties['strFormName']."','".$strName."', '".$aProperties['strTableName']."', 1, true, 'upd');";
				$strMannage .= '&nbsp;&nbsp;&nbsp;' . $oView->formLink('Update a value', "javascript://", $aAttribsLink);
			}
			if (isset($aProperties['bDelManagement']) && $aProperties['bDelManagement']) {
				$aAttribsLink['onclick'] = "addRef('".$aProperties['sLibPath'].$aProperties['sLibForm']."/', 'document.".$aProperties['strFormName']."','".$strName."', '".$aProperties['strTableName']."', 1, true, 'del');";
				$strMannage .= '&nbsp;&nbsp;&nbsp;' . $oView->formLink('Del a value', "javascript://", $aAttribsLink);
			}
		}
		
		//Gestion de strEvent
		$aRes = explode('=', $aProperties['strEvent']);
		$aAttribs[$aRes[0]] = substr($aRes[1],1, -1);
		
		$strSelect = $oView->$fhelper($strName, $aChecked, $aAttribs, $aOptions);
		$strTag = $strSelect;
		if (isset($aProperties['sSearchQueryName']) && $aProperties['sSearchQueryName'] != "") {
			$strTag .= $strSearch . '<br />';
		}
		$strTag .= '&nbsp;' . $strMannage;	
		return $strTag;
	}
}	
?>