<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
/**
 * Classe d'administration de la tranche Dernière comparaison de Mon projet
 *
 * @package Page
 * @subpackage Citroen
 */
class Cms_Page_Citroen_MonProjet_DerniereComparaison extends Cms_Page_Citroen
{

	/**
	 * Affichage du formulaire
	 * @param Pelican_Controller $oController
	 */
	public static function render(Pelican_Controller $oController)
	{
		$return .= $oController->oForm->createLabel(t('NON_LOGUE'), "");
		$return .= $oController->oForm->createInput($oController->multi . "ZONE_TITRE", t('TITRE'), 255, "", true, $oController->zoneValues['ZONE_TITRE'], $oController->readO, 75);
		$return .= $oController->oForm->createEditor($oController->multi . "ZONE_TEXTE", t('TEXTE'), true, $oController->zoneValues['ZONE_TEXTE'], $oController->readO, true, "", 465, 200);
		$return .= $oController->oForm->createLabel(t('LOGUE'), "");
		$return .= $oController->oForm->createInput($oController->multi . "ZONE_TITRE2", t('TITRE'), 255, "", true, $oController->zoneValues['ZONE_TITRE2'], $oController->readO, 75);
		$return .= $oController->oForm->createEditor($oController->multi . "ZONE_TEXTE2", t('TEXTE'), true, $oController->zoneValues['ZONE_TEXTE2'], $oController->readO, true, "", 465, 200);
		return $return;
	}

}