<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
/**
 * Classe d'administration de la tranche Comparateur
 * 
 * @package Page
 * @subpackage Citroen
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since 24/07/2013
 */
class Cms_Page_Citroen_Comparateur extends Cms_Page_Citroen
{
    /**
     * Affichage du formulaire
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {
        $sControllerForm = '';
        /* Gestion des mode d'affichage Web ou Mobile (ici forcé à Web) */
		if($oController->zoneValues['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['COMPARATEUR']){
			$sControllerForm .= Backoffice_Form_Helper::getFormAffichage($oController, true, false);
		}else{
			$sControllerForm .= Backoffice_Form_Helper::getFormAffichage($oController, true, true);
                        $sControllerForm .= Backoffice_Form_Helper::getFormModeAffichage($oController);			
		}
        /* Titre */
        $sControllerForm .= $oController->oForm->createInput($oController->multi . 'ZONE_TITRE', t('TITRE'), 255, '', true, $oController->zoneValues['ZONE_TITRE'], $oController->readO, 75);
        /* Introduction */
        $bPopup = false;
        $sControllerForm .= $oController->oForm->createEditor($oController->multi . 'ZONE_TEXTE', t('INTRO'), true, $oController->zoneValues['ZONE_TEXTE'], $oController->readO, true);
        /* Filtre VU-VP */

        $parameters = array();
        if(!empty($oController->zoneValues['ZONE_PARAMETERS'])){
            $parameters = json_decode($oController->zoneValues['ZONE_PARAMETERS']);
        }
        $sControllerForm .= $oController->oForm->createCheckBoxFromList($oController->multi.'ZONE_PARAMETERS', t('FILTER_COMPARATOR_VP_VU'), array(Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VP']=> t('VEHICULE_LABEL_GAMMEVP'),Pelican::$config['GAMME_VEHICULE_CONFIGURATEUR']['VU']=> t('VEHICULE_LABEL_GAMMEVU')), $parameters, false, $controller->readO);

        /* Mentions légales */
	   if($oController->zoneValues['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['COMPARATEUR']){
			$sControllerForm .= Backoffice_Form_Helper::getMentionsLegales($oController, false, 'cinemascope');
		}else{
			$sControllerForm .= $oController->oForm->createEditor($oController->multi . "ZONE_TEXTE4", t('TEXTE'), false, $oController->zoneValues["ZONE_TEXTE4"], $oController->readO, true, "", 650, 150);
		}
        $sControllerForm .= $oController->oForm->showSeparator("formSep");
        /* Titre sharer */
        $sControllerForm .= $oController->oForm->createInput($oController->multi . 'ZONE_TITRE2', t('SHARER_TITLE'), 255, '', true, $oController->zoneValues['ZONE_TITRE2'], $oController->readO, 75);
        /* Sélection du groupe de réseau social */
        $sControllerForm .= Backoffice_Form_Helper::getFormGroupeReseauxSociaux($oController, 'ZONE_CRITERIA_ID', 'PUBLIC');
        
        return $sControllerForm;
    }
	 public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        if(isset(Pelican_Db::$values['ZONE_PARAMETERS']) && is_array(Pelican_Db::$values['ZONE_PARAMETERS']) ){
            Pelican_Db::$values['ZONE_PARAMETERS'] = json_encode(Pelican_Db::$values['ZONE_PARAMETERS']);
        }
        parent::save();	
    }
}