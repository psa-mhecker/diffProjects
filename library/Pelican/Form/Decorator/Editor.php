<?php
/** Class Decorator de l'Element Editor
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 09/04/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Editor extends Pelican_Form_Decorator_Abstract {
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {
		$strName  	 = $oElement->getFullyQualifiedName();
		$aAttribs 	 = $oElement->getAttribs();
		$aProperties = $oElement->getProperties();
		$strValue 	 = $oElement->getValue();

		if ($aProperties['bPopup'] || $aProperties['bReadOnly']) {
			if (!$aProperties['bReadOnly']) {
				$aAttribsPic['width']  = 18;
				$aAttribsPic['height'] = 18;
				$aAttribsPic['border'] = 0;
				$aAttribsPic['align']  = 'middle';
				$strImg = $oView->myImg('/library/Pelican/Form/public/images/iframe.gif', $aAttribsPic);			
				$aAttribsLink['style'] 	 = "text-decoration:none";
				$aAttribsLink['onclick'] = "PopupVirtualKeyboard.hide();popupEditor2('" . $strName . "','" . $aProperties['strSubFolder'] . "', '" . $aProperties['limitedConf'] . "');";
				$strTag .= $oView->formLink($strImg.'&nbsp;'.t('FORM_BUTTON_EDITOR'), 'javascript://', $aAttribsLink) . '<br />';
			}
			$strTag .= $oView->formHidden($strName, str_replace("\"", "&quot;", $strValue));
		} else {
			$aAttribsTextArea['id']    = $strName."TagStripped";
			$aAttribsTextArea['rows']  = "20";  
			$aAttribsTextArea['cols']  = "80"; 
			$aAttribsTextArea['style'] = "display: none;";
			$strTag .= $oView->formTextarea($strName."TagStripped", Pelican_Text::htmlentities(preg_replace('@<script[^>]*?>.*?</script>@si', '', $strValue)), $aAttribsTextArea);
			unset($aAttribsTextArea);
			$aAttribsTextArea['id']    = $strName;
			$aAttribsTextArea['rows']  = "20";
			$aAttribsTextArea['cols']  = "80"; 
			$aAttribsTextArea['class'] = "mceEditor";
			$aAttribsTextArea['mce_editable'] = true;
			$strTag .= $oView->formTextarea($strName, Pelican_Text::htmlentities($strValue), $aAttribsTextArea);
		}
		//Iframe
		$aAttribsIframe['src']    = '/library/blank.html';
		$aAttribsIframe['id']     = 'iframeText'.$strName;
		$aAttribsIframe['name']   = 'iframeText'.$strName;
		$aAttribsIframe['width']  = $aAttribs['width'];
		$aAttribsIframe['height'] = $aAttribs['height'];
		$aAttribsIframe['style']  = 'border: 1px solid #ccc;';
		$aAttribsIframe['frameborder'] = 0;
		$strTag .= $oView->formIframe($aAttribsIframe, '');
		//Script
		$aAttribsScript['type'] = 'text/javascript';
		$strScriptContent .= "  var MEDIA_HTTP=\"" . str_replace("/", "\/", $aProperties['_sUploadHttpPath'] . "/") . "\";\n";
		$strScriptContent .= "  var MEDIA_VAR=\"" . str_replace("/", "\/", $aProperties['_sUploadVar'] . "/") . "\";\n";
		$strScriptContent .= "  var tempM=new RegExp(MEDIA_VAR , \"gi\");\n";
		$strScriptContent .= "var body = \"<html><head>";
		//$strScriptContent .= $meta ????
		if ($aProperties['_sEditorCss']) {
			$strScriptContent .= "<link rel='stylesheet' type='text/css' href='" . $aProperties['_sEditorCss']. "' />";
		}
		$strScriptContent .= "</head><body>\" + document.getElementById('" . $strName . "').value.replace(tempM,MEDIA_HTTP) + \"</body></html>\";\n";
        $strScriptContent .= "      iframeText" . $strName . ".document.open();\n";
        $strScriptContent .= "      iframeText" . $strName . ".document.write(body);\n";
        $strScriptContent .= "      iframeText" . $strName . ".document.close();\n";
        $strScriptContent .= "\n</script>\n";
		$strTag .= $oView->formScript($aAttribsScript, $strScriptContent);
		return $strTag;
	}
}