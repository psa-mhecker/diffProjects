<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
/**
 * Classe d'administration de la tranche Récapitulatif du modèle utilisé dans 
 * le showroom accueil
 * 
 * @package Page
 * @subpackage Citroen
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 06/08/2013
 */
class Cms_Page_Citroen_RecapitulatifModele extends Cms_Page_Citroen
{
    /**
     * Affichage du formulaire
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {
        $sControllerForm = '';
        /* Gestion des mode d'affichage Web ou Mobile */
        $sControllerForm .= Backoffice_Form_Helper::getFormAffichage($oController, true, false);
        /* Gestion de l'affichage Ligne DS, Ligne C, neutre */
        $sControllerForm .= Backoffice_Form_Helper::getFormModeAffichage($oController);
        /* Sélection d'un véhicule associé */
        $sControllerForm .= Backoffice_Form_Helper::getVehicule($oController);
        /* Sélection d'outils pour la zone */
        $sControllerForm .= Backoffice_Form_Helper::getOutils($oController, true, false, null, 4, false);
		
		$aDataValues = Pelican::$config['TRANCHE_COL']["AFF_MODE_MENTION"];
		$sControllerForm .= $oController->oForm->createComboFromList($oController->multi . "ZONE_TITRE5", t("MENTIONS_LEGALES"), $aDataValues, $oController->zoneValues["ZONE_TITRE5"], false, $oController->readO);

        return $sControllerForm;
    }
    
    /**
     * Surcharge de la méthode de sauvegarde pour y inclure les enregistrements
     * des multis et tableaux associatif
     * 
     * @param Pelican_Controller $controller
     */
    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
        Backoffice_Form_Helper::saveOutils();
        parent::save();
    }
}