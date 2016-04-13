<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
/**
 * Classe d'administration de la tranche Gestion newsletter de Mon projet
 *
 * @package Page
 * @subpackage Citroen
 */
class Cms_Page_Citroen_MonProjet_GestionNewsletter extends Cms_Page_Citroen
{

	/**
	 * Affichage du formulaire
	 * @param Pelican_Controller $oController
	 */
	public static function render(Pelican_Controller $oController)
	{
		$return .= $oController->oForm->createInput($oController->multi . "ZONE_TITRE", t('TITRE'), 255, "", true, $oController->zoneValues['ZONE_TITRE'], $oController->readO, 75);
		$return .= $oController->oForm->createEditor($oController->multi . "ZONE_TEXTE", t('TEXTE'), false, $oController->zoneValues['ZONE_TEXTE'], $oController->readO, true, "", 465, 200);
		$return .= $oController->oForm->createLabel(t('OPTIN_DEALER'), "");
		$return .= $oController->oForm->createCheckBoxFromList($oController->multi . "ZONE_TITRE2", t('ACTIVATION'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $oController->zoneValues['ZONE_TITRE2'], false, $oController->readO);
		$return .= $oController->oForm->createCheckBoxFromList($oController->multi . "ZONE_TITRE3", t('OBLIGATOIRE'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $oController->zoneValues['ZONE_TITRE3'], false, $oController->readO);
		$return .= $oController->oForm->createTextArea($oController->multi . "ZONE_TEXTE3", t('TEXTE'), false, $oController->zoneValues['ZONE_TEXTE3'], "", $oController->readO, 5, 72);
		$return .= $oController->oForm->createLabel(t('OPTIN_BRAND'), "");
		$return .= $oController->oForm->createCheckBoxFromList($oController->multi . "ZONE_TITRE6", t('ACTIVATION'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $oController->zoneValues['ZONE_TITRE6'], false, $oController->readO);
		$return .= $oController->oForm->createCheckBoxFromList($oController->multi . "ZONE_TITRE7", t('OBLIGATOIRE'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $oController->zoneValues['ZONE_TITRE7'], false, $oController->readO);
		$return .= $oController->oForm->createTextArea($oController->multi . "ZONE_TEXTE2", t('TEXTE'), false, $oController->zoneValues['ZONE_TEXTE2'], "", $oController->readO, 5, 72);
		$return .= $oController->oForm->createLabel(t('OPTIN_PARTNER'), "");
		$return .= $oController->oForm->createCheckBoxFromList($oController->multi . "ZONE_TITRE4", t('ACTIVATION'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $oController->zoneValues['ZONE_TITRE4'], false, $oController->readO);
		$return .= $oController->oForm->createCheckBoxFromList($oController->multi . "ZONE_TITRE5", t('OBLIGATOIRE'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $oController->zoneValues['ZONE_TITRE5'], false, $oController->readO);
		$return .= $oController->oForm->createTextArea($oController->multi . "ZONE_TEXTE4", t('TEXTE'), false, $oController->zoneValues['ZONE_TEXTE4'], "", $oController->readO, 5, 72);
		return $return;
	}

}