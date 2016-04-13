<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
/**
 * Classe d'administration de la tranche Simulateur de financement
 *
 * @package Page
 * @subpackage Citroen
 */
class Cms_Page_Citroen_SimulateurFinancement extends Cms_Page_Citroen
{

	/**
	 * Affichage du formulaire
	 * @param Pelican_Controller $oController
	 */
	public static function render(Pelican_Controller $oController)
	{
		$return .= Backoffice_Form_Helper::getFormAffichage($oController, true, false);
		$return .= Backoffice_Form_Helper::getFormModeAffichage($oController);
		
		//$oConnection->query('r');
		$return .= $oController->oForm->createInput($oController->multi . "ZONE_TITRE", t('TITRE'), 255, "", false, $oController->zoneValues['ZONE_TITRE'], $oController->readO, 75);
		$return .= $oController->oForm->createInput($oController->multi . "ZONE_TITRE2", t('SOUS_TITRE'), 255, "", false, $oController->zoneValues['ZONE_TITRE2'], $oController->readO, 75);
		$return .= $oController->oForm->createEditor($oController->multi . "ZONE_TEXTE",t('Chapeau'), false,$oController->zoneValues["ZONE_TEXTE"],$oController->readO,true,"",500, 150);
		

		$return .= $oController->oForm->createInput($oController->multi . "ZONE_TITRE3", t('TITRE'), 255, "", false, $oController->zoneValues['ZONE_TITRE3'], $oController->readO, 75);
		$return .= $oController->oForm->createEditor($oController->multi . "ZONE_TEXTE2", t('CHAPEAU'), false, $oController->zoneValues['ZONE_TEXTE2'], $oController->readO, true, "", 500, 150);
		        
		return $return;
	}

	public static function save(Pelican_Controller $oController)
	{
		$oConnection = Pelican_Db::getInstance();
		//$oConnection->query('r');
		Backoffice_Form_Helper::saveFormAffichage();
		parent::save();
	}

}