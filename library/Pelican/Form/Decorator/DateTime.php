<?php
/** Class Decorator de l'Element CreateDateTime
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 24/03/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_DateTime extends Pelican_Form_Decorator_Abstract {
	
	/**
	 * Renvoie le Message d'erreur
	 *
	 * @param Pelican_Element $oElement
	 * @return string
	 */
	public function getErrorMessage($oElement, $strLabel) {
		$strMessages = t('FORM_MSG_VALUE_REQUIRE') . ' ' . substr($strLabel, 0, -1) .' ' . t('FORM_MSG_WITH') . ' ' . t('FORM_MSG_DATE');
		return $strMessages;
	}
	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {		
		$aProperties  = $oElement->getProperties();
		$strValue	  = $oElement->getValue();
		$strName	  = $oElement->getFullyQualifiedName();
		$aAttribs     = $oElement->getAttribs();
		
		//explode de la value		
		$strValueDate = '';
		$strValueHeure = '';
		if ($strValue != '') {
			$tabDateHeure = explode(" ", $strValue);
			if ($tabDateHeure[1])
			{
				$strValueDate  = $tabDateHeure[0];
				$strValueHeure = substr($tabDateHeure[1], 0, 5);
			} else {
				$strValueDate  = $tabDateHeure[0];
				$strValueHeure = '00:00';
				$strValue = $strValueDate . ' ' . $strValueHeure;
			}
		}
		//creation du champ
		Pelican_Form::addHidden($strName, $strValue);
		if ($aProperties['bVirtualKeyboard'] == true)
			$aAttribs['onfocus'] = "activeInput = this;PopupVirtualKeyboard.attachInput(this);";
		$aAttribs['size'] = 10;
		$aAttribs['maxlength'] = 10;
		$strTextDate  = $oView->formText($strName.'_DATE', $strValueDate, $aAttribs);
		$aAttribSpan['class'] = 'formcomment';
		$strTextDate  .= $oView->formSpan($aAttribSpan, '(JJ/MM/AAAA)');
		$aAttribs['size'] = 5;
		$aAttribs['maxlength'] = 5;
		$strTextHeure = $oView->formText($strName.'_HEURE', $strValueHeure, $aAttribs);
		$aAttribSpan['class'] = 'formcomment';
		$strTextHeure  .= $oView->formSpan($aAttribSpan, '(hh:mm)');
		
		//creation du lien pour la popup calendar
		unset($aAttribs);
		$aAttribs['border'] = 0;
		$aAttribs['style'] = 'vertical-align: middle; cursor: pointer;';
		$aAttribs['onclick'] = 'popUpCalendar(this, '.$aProperties['strFormName'].'.'.$strName.'_DATE)';
		$strImg = $oView->myImg('/library/Pelican/Form/public/images/cal.gif', $aAttribs);
		$strCal = $oView->formLink($strImg, 'javascript://');
		
		$strTag = $strTextDate . ' ' . $strCal . ' ' . $strTextHeure;
		return $strTag;
	}
}
?>