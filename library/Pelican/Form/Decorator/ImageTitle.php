<?php
/** Class Decorator de l'Element ImageTitle
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 15/04/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_ImageTitle extends Pelican_Form_Decorator_Abstract {
	
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
		
		$iMaxLength = (int)$aProperties['isize'] * 15;
        
		$aAttribsPic['id'] 		 = "imgTitle" . $strName;	
		$aAttribsPic['name'] 	 = "imgTitle" . $strName;	
		$aAttribsPic['align'] 	 = "center";	
		$aAttribsPic['border'] 	 = 1;		
		$aAttribsPic['height'] 	 = 19; 
		$strSrc = $aProperties['sLibPath'] . Pelican::$config['LIB_MEDIA'] . "/image_title.php?text=" . rawurlencode($strValue) . "&size=" . $aProperties['isize'] . "&preview=1";
		$strImg = $oView->myImg($strSrc, $aAttribsPic);
		
		$aAttribs['class'] 		= "text";
		$aAttribs['size'] 		= ($iMaxLength + 1);
		$aAttribs['maxlength'] 	= ($iMaxLength * 2);
		$aAttribs['onchange'] 	= "document.getElementById('imgTitle" . $strName . "').src='" . $aProperties['sLibPath'] . Pelican::$config['LIB_MEDIA'] . "/image_title.php?text=' + escape(document.getElementById('" . $strName . "').value).replace('+','%2b') + '&size=" . $aProperties['isize'] . "&preview=1'";
		$strText = $oView->formText($strName, (($strValue != "")?$strValue:""), $aAttribs);
		
		$strTag = $strText . "<br />" . $strImg;
		return $strTag;
	}
}
?>