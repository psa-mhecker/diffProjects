<?php
/** Class Decorator DrawTab
 *
 * @version 1.0
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @author Charles Teinturier <charles.teinturier@businessdecision.com>
 * @since 18/06/2010
 * @package Pelican + Zend
 */
Class Pelican_Form_Decorator_DrawTab extends Pelican_Form_Decorator_Abstract {
	/**
	 * Renvoie le Tag de l'element
	 *
	 * @param Pelican_Element $oElement
	 * @param Zend_View $oView
	 * @return string
	 */
	public function getTag($oElement, $oView) {
		$aProperties = $oElement->getProperties();
		$oView->addHelperPath('Pelican/View/Helper', 'Pelican_View_Helper');
		
		$strTag = '';
		pelican_import('Index.Tab');
		$oTab = Pelican_Factory::getInstance('Index.Tab',"tab" . $aProperties['strFormName'], Pelican::$frontController->skinPath);
		$bChoose = true;
		foreach ($aProperties['aTab'] as $tab) {
			$oTab->addTab($tab["label"], $aProperties['strFormName'] . "_" . $tab["id"], $bChoose, "", "ongletFW('" . $tab["id"] . "')", "", "petit");
			$bChoose = false;
		}
		//Script
		$aRes = array_shift($aProperties['aTab']);
		$strScript = "var currentTab='" . $aRes["id"] . "';\n";
		$strScript .= "var formTab = '" . $aProperties['strFormName'] . "';\n";
		$strScript .= "function ongletFW(id) {\n";
		$strScript .= "\t\tif (document.getElementById(formTab + '_tab_' + id)) {\n";
		$strScript .= "\t\t\ttabSwitch(currentTab, 'off'); /** l'ancien */\n";
		$strScript .= "\t\t\ttabSwitch(id, 'on'); /** le nouveau */\n";
		$strScript .= "\t\t\tcurrentTab = id;\n";
		$strScript .= "\t\t}\n";
		$strScript .= "}\n";
		$strScript = $oView->formScript(array('type' => 'text/javascript'), $strScript);
		//Div
		$aAttribs['class'] = 'petit_onglet_bas';
		$aAttribs['width'] = '100%';
		$strDiv = $oView->formDiv($aAttribs, $oTab->getTabs());
		
		//Rajout
		$strTag .=  $strScript . $strDiv . "<br class='after_tab'/>";
		return $strTag;
	}
}
?>