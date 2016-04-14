<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
/**
 * Classe d'administration de la tranche Gamme.
 *
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 *
 * @since 29/07/2013
 */
class Cms_Page_Citroen_Gamme extends Cms_Page_Citroen
{
    /**
     * Affichage du formulaire.
     *
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {
        $sControllerForm = '';
        /* Gestion des mode d'affichage Web ou Mobile (ici forcé à Web) */
        $sControllerForm .= Backoffice_Form_Helper::getFormAffichage($oController, true, false);
        /* Titre */
        $sControllerForm .= $oController->oForm->createInput($oController->multi.'ZONE_TITRE', t('TITRE'), 255, '', true, $oController->zoneValues['ZONE_TITRE'], $oController->readO, 75);

        /* Partie CTA */
        $sControllerForm .= $oController->oForm->createLabel(t('FORM_CTA'), '');
        $sControllerForm .= $oController->oForm->createInput($oController->multi.'ZONE_LABEL', t('LIBELLE'), 40, '', true, $oController->zoneValues['ZONE_LABEL'], $oController->readO, 75);
        $sControllerForm .= $oController->oForm->createInput($oController->multi.'ZONE_URL', t('URL_WEB'), 255, 'internallink', true, $oController->zoneValues['ZONE_URL'], $oController->readO, 75);

        /* Partie véhicule utilitaire */
        $sControllerForm .= $oController->oForm->createLabel(t('PUSH_VEHICULE_UTILITAIRE'), '');
        /* Libellé */
        $sControllerForm .= $oController->oForm->createInput($oController->multi.'ZONE_LABEL2', t('FORM_LABEL'), 255, '', false, $oController->zoneValues['ZONE_LABEL2'], $oController->readO, 75);
        /* Visuel */
        $sControllerForm .= $oController->oForm->createMedia($oController->multi.'MEDIA_ID2', t('FORM_VISUAL'), false, 'image', '', $oController->zoneValues['MEDIA_ID2'], $oController->readO, true, false, '16_9');
        /* URL */
        $sControllerForm .= $oController->oForm->createInput($oController->multi.'ZONE_URL2', t('URL'), 255, 'internallink', false, $oController->zoneValues['ZONE_URL2'], $oController->readO, 75);
        /* Mode d'ouverture */
        $sControllerForm .= $oController->oForm->createRadioFromList($oController->multi.'ZONE_PARAMETERS', t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $oController->zoneValues['ZONE_PARAMETERS'], false, $oController->readO);

        return $sControllerForm;
    }

    /**
     * Méthode publique statique d'enregistrement du bloc.
     */
    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}
