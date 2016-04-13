<?php
/** Class Decorator de l'Element SubFormHmvc
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 14/10/2011
 * @package Pelican + Zend
 */
  
Class Pelican_Form_Decorator_SubFormHmvc extends Pelican_Form_Decorator_Abstract {
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {	
		global $_GET, $_POST, $_SERVER;	
		
		$aProperties  = $oElement->getProperties();
		$strName	  = $oElement->getFullyQualifiedName();
		$oConnection  = $oElement->getConnection();
		
		$strTag = '';
		if ($aProperties['strJsVar']) {
			Pelican_Form::addHidden("js_" . $strName, $aProperties['strJsVar']);
		}
		Pelican_Form::addHidden("file_" . $strName, $aProperties['call']['path'] . ',' . $aProperties['call']['class'] . ',' . $aProperties['call']['method']);
		
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
		$values	= $aProperties['tabValues'];
		$readO = $aProperties['bReadOnly'];				
		
		/* On Sauvegarde les hiddens car sinon ils vont être effacé par la nouvelle 
		instance de la class form (la variable est static) */
		$aTmpHiddens = Pelican_Form::getHiddens();
		
		//Configuration de la nouvelle class form
		$oForm = Pelican_Factory::getInstance('Form', true);
		$oForm->open(Pelican::$config["DB_PATH"]);
        beginFormTable();
		/* On ne rajoute pas le tag form */
		$oForm->hideFormTabTag(array("hideTab" => false, "hideForm" => true, "createXhtml" => true));

		//Div
		$aAttribs['id'] = $strName;
		$aAttribs['name'] = $strName;
		$aAttribs['class'] = $aProperties['strCss'];
		if ($aProperties['bReadOnly']) {
			$aAttribs['class'] = $aProperties['strCss'];
		}
			
		if (!empty($aProperties['call']['path'])) {			
			include_once($aProperties['call']['path']);			
		}

		$HTML_content = call_user_func_array(array($aProperties['call']['class'], $aProperties['call']['method']), array($oForm , $values , $readO));
		
		/* Referme le formulaire temporaire */
        endFormTable();
        $oForm->close();
		
		/* On replace les hiddens dans l'instance de form principale */
		Pelican_Form::addHiddens($aTmpHiddens);
		
		if($aProperties['strJsVar'] && $aProperties['strJsVar'] != 'subformjs') {
			$HTML_content .= $oView->headScript("SCRIPT", $aProperties['strJsVar']);
		}
		
		$strTag .= $oView->formDiv($aAttribs, $HTML_content);
		
		
		return $strTag;
	}
}