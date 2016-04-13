<?php
/** Class Decorator de l'Element Pelican_Media en read Only
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 16/04/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Media extends Pelican_Form_Decorator_Abstract {
	
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
		$aProperties  = $oElement->getProperties();	
		
		//Création du lien
		Pelican_Form::addHidden($strName, $strValue);
		$strPathValue = $strValue;
		//rajouter le Pelican::$config dans le If
		if ($strValue) {
			$strPathValue = Pelican_Media::getMediaPath($strValue);
		}		
		if ($strPathValue) {
            // Nom du fichier
            $strFile = basename($strPathValue);
            // Infos du fichier
            $aPathInfo = pathinfo($strFile);
            // Chemin escapé
            $escapePath = str_replace($strFile, rawurlencode($strFile), $strPathValue);
            $_sThumbnailAbsPath = $escapePath;
        }		
        $strTypePrecis = $aProperties['strType'];
        if (isset($aPathInfo)) {
            if (isset($aAllowedExtensions["image"][$aPathInfo["extension"]])) {
                $strTypePrecis = "image";
            }
        }
        if (isset($strFile)) {
        	if ($strTypePrecis == "image") {
        		$aAttribsPic['style'] = "border : 1px solid #CCCCCC";
        		$aAttribsPic['alt'] = str_replace(" ", Pelican_Html::nbsp(), $strFile);
        		$aAttribsPic['height'] = $this->_iHeightThumbnail;
        		$linkMedia = $oView->myImg($this->_sUploadHttpPath . $_sThumbnailAbsPath, $aAttribsPic);
        	} else {
        		$linkMedia =  str_replace(" ", "&nbsp;", $strFile);
        	}
        	$aAttribsLink['id'] = "imgdiv" . $strName;
        	$aAttribsLink['target'] = "_blank";
        	$strLink = $oView->formLink($linkMedia, $this->_sUploadHttpPath . $escapePath, $aAttribsLink) . '&nbsp;&nbsp;';
        }
		
		//Création des boutons
		if (is_array($aProperties['strType'])) {
			foreach ($aProperties['strType'] as $type) {
				//Attributes du button
				$aAttribsButton['class'] = "button";
				$aAttribsButton['onclick'] = "popupMedia('" . $type . "', '/library/Pelican/Media/public', this.form.elements['" . $strName . "'], 'div" . $strName . "', '";
				if ($aProperties['strSubFolder'] != "") {
                    $aAttribsButton['onclick'] .= $aProperties['strSubFolder'];
                }
                $aAttribsButton['onclick'] .= "','" . str_replace("/", "\/", $aProperties['sUploadHttpPath'] . "/") . "',''";
                if ($aProperties['bLibrary']) {
                	$aAttribsButton['onclick'] .= ", true";
                }
                $aAttribsButton['onclick'] .= ");";
				//Value du button
				$strValue = t('FORM_BUTTON_ADD') . " " . ($type == "image" ? "an " : "a ") . $type;
				//Button
				$strTmp .= $oView->formButton('', $strValue, $aAttribsButton);
			}
		} else {
			//Attributes du button
			$aAttribsButton['class'] = "button";
			$aAttribsButton['onclick'] = "popupMedia('" . $aProperties['strType'] . "', '/library/Pelican/Media/public', this.form.elements['" . $strName . "'], 'div" . $strName . "', '";
			if ($aProperties['strSubFolder'] != "") {
            	$aAttribsButton['onclick'] .= $aProperties['strSubFolder'];
            }
            $aAttribsButton['onclick'] .= "','" . str_replace("/", "\/", $aProperties['sUploadHttpPath'] . "/") . "',''";
            if ($aProperties['bLibrary']) {
              	$aAttribsButton['onclick'] .= ", true";
            }
            $aAttribsButton['onclick'] .= ");";
            //Button
			//$strTmp = "<td style='vertical-align:top;'>";
			$strTmp = $oView->formButton('', t('FORM_BUTTON_ADD'), $aAttribsButton);
			unset($aAttribsButton);
		}
		//Bouton supprimer
		$aAttribsButton['class'] = "button";
		$aAttribsButton['onclick'] = "if(confirm('" . t('FORM_MSG_CONFIRM_DEL') . "')) {this.form.elements['" . $strName . "'].value=''; document.getElementById('div" . $strName . "').innerHTML = '';}";
		$strDelButton = $oView->formButton('', t('FORM_BUTTON_FILE_DELETE'), $aAttribsButton);

		$strTag = "<table cellpadding='0' cellspacing='0' border='0'>";
        $strTag .= "<tr>";
        $strTag .= "<td width='2' id='div" . $strName . "' nowrap='nowrap'>";
        $strTag .= $strLink . "</td>";
        $strTag .= "<td style='vertical-align:top;'>" . $strTmp . '&nbsp;' . $strDelButton;
        $strTag .= "</td></tr></table>";
		//$strTag = "test";
        return $strTag;		
	}
}
?>