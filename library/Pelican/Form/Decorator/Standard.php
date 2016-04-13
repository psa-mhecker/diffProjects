<?php
/** Class Decorator Standard des Elements
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 25/03/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Standard extends Pelican_Form_Decorator_Abstract {
	/**
	 * Renvoie le message d'erreur
	 *
	 * @param Pelican_Element $oElement
	 * @return string
	 */
	public function getErrorMessage($oElement, $strLabel) {
		$aProperties = $oElement->getProperties();
		$strControl = $aProperties['strControl'];
		$strMessage = t('FORM_MSG_VALUE_REQUIRE') . ' ' . substr($strLabel, 0, -1);
		if ($strControl != "" && $strControl != "color" && $strControl != "internallink") {
			$strMessage .= ' ' . t('FORM_MSG_WITH') . " ";
			switch ($strControl) {
				 case "alphanum":
                            {
                                $strMessage .= t('FORM_MSG_ALPHANUM');
                                break;
                            }
                        case "numerique":
                        case "number":
                            {
                                $strMessage .= t('FORM_MSG_NUMBER');
                                break;
                            }
                        case "float":
                        case "flottant":
                        case "real":
                        case "reel":
                            {
                                $strMessage .= t('FORM_MSG_REAL');
                                break;
                            }
                        case "telephone":
                            {
                                $strMessage .= t('FORM_MSG_TELEPHONE');
                                break;
                            }
                        case "mail":
                            {
                                $strMessage .= t('FORM_MSG_MAIL');
                                break;
                            }
                        case "URL":
                            {
                                $strMessage .= t('FORM_MSG_URL');
                                break;
                            }
                        case "login":
                            {
                                $strMessage .= t('FORM_MSG_LOGIN');
                                break;
                            }
                        case "dateNF":
                        case "shortdate":
                        case "date":
                        case "calendar":
                            {
                                $strMessage .= t('FORM_MSG_DATE');
                                break;
                            }
                        case "date_edition":
                            {
                                $strMessage .= t('FORM_MSG_DATE_EDITION');
                                break;
                            }
                        case "year":
                            {
                                $strMessage .= t('FORM_MSG_YEAR');
                                break;
                            }
                        case "heure":
                            {
                                $strMessage .= t('FORM_MSG_HEURE');
                                break;
                            }
			}
		}
		return $strMessage;
	}
	
	public function getTag($oElement, $oView) {
		$helper   	 = $oElement->helper;
		$aAttribs 	 = $oElement->getAttribs();
		$aProperties = $oElement->getProperties();		
		$strValue 	 = $oElement->getValue();			
		$strName	 = $oElement->getFullyQualifiedName();
		//Clavier Virtuel
		if ($aProperties['bVirtualKeyboard'] && $helper == "formText") {
			$aAttribs['onfocus'] = "activeInput = this;PopupVirtualKeyboard.attachInput(this);";
		}
		
		//gestion de Control et de Suggest
		$strAdd = '';
		if (isset($aProperties['suggest'])) {
			$aAttribsPic['class'] = "combo_suggest";
			$aAttribsPic['border'] = 0;
			$aAttribsPic['onclick'] = 'showSuggest("'.$strName.'")';
			$strAdd .= $oView->myImg('/library/Pelican/Form/public/images/combo.gif', $aAttribsPic) . '&nbsp;&nbsp;';
			unset($aAttribsPic);
			$aAttribs['autocomplete'] = "off";
		}
		if (isset($aProperties['strControl'])) {
			$strAdd .= "&nbsp;&nbsp;";
			switch ($aProperties['strControl']) {
				case "numerique":
                case "number":
                case "flottant":
                case "float":
                case "real":
                case "reel":
                    {
                        $aAttribs['style'] = "text-align:right;";
                        break;
                    }
				case "mail" :
					{
						if ($strValue) {
							$aAttribsPic['border'] = 0;
							$aAttribsPic['style']  = 'vertical-align: middle; cursor: pointer;';
							$strImg = $oView->myImg('/library/Pelican/Form/public/images/mail.gif', $aAttribsPic);
							$strAdd .= $oView->formLink($strImg, 'mailto:' . $strValue);
						}
						break;
					}
				case "color" : 
					{
						$aAttribsColor['id'] 	= 'color' . $strName;
						$aAttribsColor['style'] = 'border: 1px solid; background-color: ' . $strValue . ';';
						$strAdd .= $oView->formSpan($aAttribsColor, '&nbsp;&nbsp;&nbsp');
						unset($aAttribsColor);
						$aAttribsColor['border']  = 0;
						$aAttribsColor['style']   = 'vertical-align: middle; cursor: pointer;';
						$aAttribsColor['alt'] 	  = 'Couleur de fond';
						$aAttribsColor['onclick'] = 'return popupColor(document.'.$aProperties['strFormName'].'.'.$strName.',document.getElementById("color'.$strName.'"))';
						$strAdd .= '&nbsp;&nbsp;' . $oView->myImg('/library/Pelican/Form/public/_work/editor/images/backcolor_form.gif', $aAttribsColor);
					 	if ($strValue) {
                           	$aAttribs['onchange'] = "document.getElementById('color" . $strName . "').style.backgroundColor=this.value";
                        }
						break;
					}
				case "internallink" :
					{
						$aAttribsLink['border'] = 0;
						$aAttribsLink['style'] = 'vertical-align: middle; cursor: pointer;';
						$aAttribsLink['onclick'] = 'return popupInternalLink(document.'.$aProperties['strFormName'].'.'.$strName.')';
						$aAttribsLink['alt'] = t('EDITOR_INTERNAL');
						$strAdd .= $oView->myImg('/library/Pelican/Form/public/_work/editor/images/internal_link.gif', $aAttribsLink);
						break;
					}
				case "calendar" :
					{
						$aAttribsPic['border'] = 0;
						$aAttribsPic['style'] =  'vertical-align: middle; cursor: pointer;';						
						$aAttribsPic['onclick'] =  'popUpCalendar(this, '.$aProperties['strFormName'].'.'.$strName.')';
						$strAdd .= $oView->myImg('/library/Pelican/Form/public/images/cal.gif', $aAttribsPic);
						break;
					}
				case "shortdate" :
					{
						$strAdd .= '&nbsp;&nbsp;' . $oView->formSpan(array('class' => 'formcomment'), '(' . t('DATE_FORMAT_LABEL') . ')');
						break;
					}
					
				case "date" :
					{
						$strAdd .= '&nbsp;' . $oView->formSpan(array('class' => 'formcomment'), '(' . t('DATE_FORMAT_LABEL') . ')');
						$aAttribsPic['border'] = 0;
						$aAttribsPic['style'] =  'vertical-align: middle; cursor: pointer;';						
						$aAttribsPic['onclick'] =  'popUpCalendar(this, '.$aProperties['strFormName'].'.'.$strName.')';
						$strAdd .= '&nbsp;&nbsp;' . $oView->myImg('/library/Pelican/Form/public/images/cal.gif', $aAttribsPic);
						break;
					}
				case "date_edition" :
					{
						$strAdd .= '&nbsp;' . $oView->formSpan(array('class' => 'formcomment'), '(' . t('DATE_FORMAT_LABEL_EDITION') . ')');
						break;
					}
				case "heure" :
					{
						$strAdd .= '&nbsp;' . $oView->formSpan(array('class' => 'formcomment'), '(' . t('HOUR_FORMAT_LABEL') . ')');
						break;
					}
			}
		}
		return $oElement->getView()->$helper(
				$oElement->getName(),
				$oElement->getValue(),
				$aAttribs,
				$oElement->options) . $strAdd;
	}
}
?>