<?php
/** Class Decorator de l'Element SubForm
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 07/05/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_SubForm extends Pelican_Form_Decorator_Abstract {	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {	
		$aProperties  = $oElement->getProperties();
		$strName	  = $oElement->getFullyQualifiedName();
		$oConnection  = $oElement->getConnection();
		
		$strTag = '';
		if ($aProperties['strJsVar'] && $aProperties['strJsVar'] != 'subformjs') {
			Pelican_Form::addHidden("js_" . $strName, $aProperties['strJsVar']);
		}
		Pelican_Form::addHidden("file_" . $strName, $aProperties['fileName']);
		
		//Iframe
		$aAttribs['id'] 		  = "iframe_" . $strName;
		$aAttribs['name'] 		  = "iframe_" . $strName;
		$aAttribs['width'] 		  = 0;
		$aAttribs['height'] 	  = 0;
		$aAttribs['scrolling'] 	  = "no";
		$aAttribs['marginwidth']  = 0;
		$aAttribs['frameborder']  = 0;
		$aAttribs['marginheight'] = 0;
		$strTag .= $oView->formIframe($aAttribs);
		unset($aAttribs);
		
		//Variable concernant le subform		
		$oForm = $aProperties['instanceForm'];	
		$values	= $aProperties['tabValues'];
		$readO = $aProperties['bReadOnly'];		
        $strTmp = "";
		
		
		//Div
		$aAttribs['id'] = $strName;
		$aAttribs['name'] = $strName;
		$aAttribs['class'] = $aProperties['strCss'];
		if ($aProperties['bReadOnly']) 
			$aAttribs['class'] = $aProperties['strCss'];
		ob_start();
        include($aProperties['fileName']);
        $HTML_content .= ob_get_contents();
        ob_end_clean();
		$strTag .= $oView->formDiv($aAttribs, $HTML_content);
		if($aProperties['strJsVar'] && $aProperties['strJsVar'] != 'subformjs') {
			$strTag .= $oView->headScript("SCRIPT", $aProperties['strJsVar']);
		}
		return $strTag;
	}
}
?>