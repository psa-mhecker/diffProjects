<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
/**
 * Classe d'administration de la tranche Aide au choix de produits de financement
 *
 * @package Page
 * @subpackage Citroen
 */
class Cms_Page_Citroen_AideChoixFinancement extends Cms_Page_Citroen
{

	/**
	 * Affichage du formulaire
	 * @param Pelican_Controller $oController
	 */
	public static function render(Pelican_Controller $oController)
	{
		$return .= Backoffice_Form_Helper::getFormAffichage($oController, true, false);
		$return .= Backoffice_Form_Helper::getFormModeAffichage($oController);
		$return .= $oController->oForm->createInput($oController->multi . "ZONE_TITRE", t('TITRE'), 255, "", true, $oController->zoneValues['ZONE_TITRE'], $oController->readO, 75);
		$return .= $oController->oForm->createInput($oController->multi . "ZONE_TITRE2", t('SOUS_TITRE'), 255, "", true, $oController->zoneValues['ZONE_TITRE2'], $oController->readO, 75);
		$return .= $oController->oForm->createEditor($oController->multi . "ZONE_TEXTE", t('TEXTE'), false, $oController->values['ZONE_TEXTE'], $oController->readO, true, "", 465, 200);
		$return .= Backoffice_Form_Helper::getMentionsLegales($oController, false, 'cinemascope');
		return $return;
	}

	public static function save(Pelican_Controller $oController)
	{
		Backoffice_Form_Helper::saveFormAffichage();
		parent::save();
	}

}