<?php
/** Class Decorator de l'Element Pelican_Media en read Only
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 15/04/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_ReadOMedia extends Pelican_Form_Decorator_Abstract {	
	//Variable pour les tests, peut etre a changer
	public $_sThumbnailAbsPath = '';
	protected $_iHeightThumbnail = 0;
	
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {	
		$strName	  		= $oElement->getFullyQualifiedName();
		$strValue	  		= $oElement->getValue();
		$aProperties  		= $oElement->getProperties();
		$aAllowedExtensions = getAllowedExtensions();
		
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
        	$strTag = $oView->formLink($linkMedia, $this->_sUploadHttpPath . $escapePath, $aAttribsLink) . '&nbsp;&nbsp;';
        }
		
		return $strTag;
	}
}
?>