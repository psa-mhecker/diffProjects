<?php
/** Class Decorator de l'Element File
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 31/05/2010
 * @package Pelican + Zend
 */

Class Pelican_Form_Decorator_File extends Pelican_Form_Decorator_Abstract {
	
    /**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {
        $strName      = $oElement->getName();
        $strValue	  = $oElement->getValue();
		$aAttribs 	  = $oElement->getAttribs();
        
        //Création du hidden
        $iSize = 1048576 * (int) ini_get("upload_max_filesize");
		Pelican_Form::addHidden("MAX_FILE_SIZE", $iSize);
			
        $strTag = $oView->formFile($strName, $strValue, $aAttribs);
        return $strTag;
    }	
}
?>