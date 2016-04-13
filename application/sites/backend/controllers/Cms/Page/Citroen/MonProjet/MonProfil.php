<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
/**
 * Classe d'administration de la tranche Mon profil de Mon projet
 *
 * @package Page
 * @subpackage Citroen
 */
class Cms_Page_Citroen_MonProjet_MonProfil extends Cms_Page_Citroen
{

	/**
	 * Affichage du formulaire
	 * @param Pelican_Controller $oController
	 */
	public static function render(Pelican_Controller $oController)
	{
		$return .= $oController->oForm->createInput($oController->multi . "ZONE_TITRE", t('TITRE'), 255, "", true, $oController->zoneValues['ZONE_TITRE'], $oController->readO, 75);
		$return .= $oController->oForm->createEditor($oController->multi . "ZONE_TEXTE", t('TEXTE'), false, $oController->zoneValues['ZONE_TEXTE'], $oController->readO, true, "", 465, 200);
		$return .= $oController->oForm->createTextArea($oController->multi . "ZONE_TEXTE2", t('MENTION_CHAMPS_OBLIGATOIRES'), true, $oController->zoneValues['ZONE_TEXTE2'], "", $oController->readO, 5, 72);
		return $return;
	}

}