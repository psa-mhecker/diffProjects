<?php
/** Class Decorateur Abstract
 * 
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 02/06/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_Abstract extends Zend_Form_Decorator_Abstract {
	
	/**
	 * Renvoie le message d'erreur
	 * (Normalement surchargé dans les autre decorators)
	 * 
	 * @param Pelican_Element $oElement
	 * @param string $strLabel
	 * @return string $strMessages
	 */
	public function getErrorMessage($oElement, $strLabel) {
		$strMessages = t('FORM_MSG_VALUE_CHOOSE') . ' ' . strip_tags(str_replace("\"", "" . "\\" . "\"", $strLabel));
		return $strMessages;
	}
	
	/**
	 * Renvoie le Tag de l'element
	 * (Normalement surchargé dans les autre decorators)
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string (vide par defaut)
	 */
	//Erreur si getTag() n'est pas implementé
	public function getTag($oElement, $oView) {
		require_once 'Zend/Form/Decorator/Exception.php';
        throw new Zend_Form_Decorator_Exception('geTag() not implemented');
	}
	
	/**
	 * Création du message d'erreur
	 *
	 * @param Pelican_element $oElement
	 * @return string
	 */
	public function buildError($oElement) {
		$strReturns = '';
		$aMessages = $oElement->getMessages();
		if (!empty($aMessages)) {
			$strLabel = $oElement->getLabel();
			$strMsg = $this->getErrorMessage($oElement, $strLabel);
			$strReturns = '<script type="text/javascript">alert("'.$strMsg.'");</script>';
		}
		return $strReturns;
	}
	
	/**
	 * Render des Decorateurs
	 *
	 * @param string $Content
	 * @return string
	 */
	public function render($Content) {
		$oElement = $this->getElement();
		
		$oView = $oElement->getView();
		if (!($oView instanceof Zend_View_Interface)) {
			$oView = new Zend_View();
			$oElement->setView($oView);
		}
		
		if (($oElement instanceof Zend_Form_Element) && ($oView instanceof Zend_View_Interface)) {
			
			//Set du path vers les View Helper
			$oView->addHelperPath('Pelican/View/Helper', 'Pelican_View_Helper');
		
			$strSeparator = $this->getSeparator();
			$strPlacement = $this->getPlacement();
			
			//Gestion de strEvent
			$aProperties = $oElement->getProperties();
			if (isset($aProperties['strEvent']) && $aProperties['strEvent']) {
				$aRes = explode('=', $aProperties['strEvent']);
				$oElement->setAttrib($aRes[0], substr($aRes[1],1, -1));
			}
			$strTag = $this->getTag($oElement, $oView, $Content) . $this->buildError($oElement);
		    
			switch($strPlacement) {
				case ("PREPEND"): {
					$Content =  $strTag . $strSeparator . $Content;
				}
				case ("APPEND"): {				
					$Content = $Content . $strSeparator . $strTag;
				}
			}
		}
		return $Content;
	}
}
?>