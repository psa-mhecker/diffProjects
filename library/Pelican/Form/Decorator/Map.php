<?php
/** Class Decorator de l'Element Map
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 19/04/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Map extends Pelican_Form_Decorator_Abstract {
	/**
	 * Renvoie le message d'erreur
	 * (Normalement surchargé dans les autre decorators)
	 * 
	 * @param Pelican_Element $oElement
	 * @param string $strLabel
	 * @return string (vide)
	 */
	public function getErrorMessage($oElement) {
		$aMessages = $oElement->getMessages();
		foreach ($aMessages as $strValue) {
			if ($strValue) {
				$strMessages = $strValue;
			}
		}
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
		$strValue	  = $oElement->getValue();
		$aAttribs 	  = $oElement->getAttribs();	
		$bRequired	  = $oElement->isRequired();
		$strName	  = $oElement->getFullyQualifiedName();
		$aProperties  = $oElement->getProperties();
		
		if ($aProperties['googleKey']) {
			//Appelle et création du script google maps
			$strSrc = "http://maps.google.com/maps?file=api&v=2&sensor=false&key=" . $aProperties['googleKey'];
			$strTag = $oView->headScript('FILE', $strSrc);
			
			//Hidden
			Pelican_Form::addHidden($strName, $strValue);
            Pelican_Form::addHidden($strName . "_ADDRESS_HIDDEN", $aProperties['strAddressValue']);
            Pelican_Form::addHidden($strName . "_ADDRESS_HIDDEN_LAT", $aProperties['strLatValue']);
            Pelican_Form::addHidden($strName . "_ADDRESS_HIDDEN_LONG", $aProperties['strLongValue']);
            
            $aAttribs['size'] = 20;
            $aAttribs['maxlength'] = 20;
            $aAttribs['class'] = "text";
            $aAttribs['style'] = "text-align: right;";
            if ($aProperties['bVirtualKeyboard'])
           		$aAttribs['onfocus'] = 'activeInput = this;PopupVirtualKeyboard.attachInput(this);';
            //Saisie de Latitude
            $strTag .= $oView->formLabel('', "Latitude" . (($bRequired && !$aProperties['bReadOnly']) ? " *" : "") . " : ");
            if ($aProperties['bReadOnly']) {
            	Pelican_Form::addHidden($strName . "_LATITUDE", $aProperties['strLatValue']);
            	$strTag .= 	$aProperties['strLatValue'];
            } else {
            	$strTag .= $oView->formText($strName . "_LATITUDE", $aProperties['strLatValue'], $aAttribs);
            }
            $strTag .= "&nbsp;&nbsp;&nbsp;";
             //Saisie de Longitude
            $strTag .= $oView->formLabel('', "Longitude" . (($bRequired && !$aProperties['bReadOnly']) ? " *" : "") . " : ");
             if ($aProperties['bReadOnly']) {
            	Pelican_Form::addHidden($strName . "_LONGITUDE", $aProperties['strLongValue']);
            	$strTag .= 	$aProperties['strLongValue'];
            } else {
            	$strTag .= $oView->formText($strName . "_LONGITUDE", $aProperties['strLongValue'], $aAttribs);
            }
             
            $strTag .= "<br />";
            
            //Div de la map
            $aAttribsDiv['id'] = $strName . "_MAP";
            $aAttribsDiv['style'] = "width:" . $aProperties['width'] . "px;height: " . $aProperties['height'] . "px;";
            $divMap = $oView->formDiv($aAttribsDiv);
            unset($aAttribsDiv['id']);
            
            //Div de la barre de recherche  
            unset($aAttribs['style']);
            $aAttribs['size'] = 35;
            $aAttribs['maxlength'] = 255;
       		$divSearch = $oView->formText($strName . "_ADDRESS", $aProperties['strAddressValue'], $aAttribs);
            $divSearch .= "&nbsp;" . $oView->formButton($strName . "_ADDRESS_BTN_FIND", t('FORM_BUTTON_SEARCH'));
            $aAttribsButton['javascript'] = "void( null ); return false";
            $divSearch .= "&nbsp;" . $oView->formButton($strName . "_ADDRESS_BTN_REST", "RÃ©initialiser", $aAttribsButton);
            $aAttribsDiv['style'] = "text-align:center;";
            $divSearch = $oView->formDiv($aAttribsDiv, $divSearch);
            
            $aAttribsDiv['style'] = "width:" . $aProperties['width'] . "px;border:#ccc 2px solid;background-color:#eee;margin-top:5px;";
            $strTag .= $oView->formDiv($aAttribsDiv, $divMap . $divSearch);
		} else {
			$strLink = $oView->formLink("Google Maps API", "http://code.google.com/intl/fr-FR/apis/maps/signup.html");
			$strText = "Veuillez ins&eacute;rer la cl&eacute; Google fournie par le site  " . $strLink;
			$aAttribsDiv['class'] = "erreur";
			$aAttribsDiv['style'] = "widht:70%";
			$strTag = $oView->formDiv($aAttribsDiv, $strText);
		}		
		return $strTag;
	}
}
?>