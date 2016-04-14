<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
/**
 * Classe d'administration de la tranche FAQ.
 *
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 *
 * @since 19/08/2013
 */
class Cms_Page_Citroen_Faq extends Cms_Page_Citroen
{
    /**
     * Affichage du formulaire.
     *
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {
        $sControllerForm = '';
        /* Gestion des mode d'affichage Web ou Mobile */
        $sControllerForm .= Backoffice_Form_Helper::getFormAffichage($oController);
        /* Gestion de l'affichage Ligne DS, Ligne C, neutre */
        $sControllerForm .= Backoffice_Form_Helper::getFormModeAffichage($oController);
        /* Titre de la zone */
        $sControllerForm .= $oController->oForm->createInput($oController->multi.'ZONE_TITRE', t('TITRE'), 255, '', false, $oController->zoneValues['ZONE_TITRE'], $oController->readO, 100);
        /* Association des rubriques de FAQ à la zone */
        $sControllerForm .= Backoffice_Form_Helper::getCreateAssocFromMultiValuesField($oController, $oController->multi.'FAQ_CAT', t('FORM_FAQ_CAT'), $_SESSION[APP]['SITE_ID'], $_SESSION[APP]['LANGUE_ID'], 'faq_rubrique', 'FAQ_RUBRIQUE_ID', 'FAQ_RUBRIQUE_LABEL', 'ZONE_PARAMETERS', '|', true, true, 15);
        /* Création d'un CTA pour mobile uniquement*/
        $aLinkOpenMode = Pelican::$config['TRANCHE_COL']["BLANK_SELF"];
        $sControllerForm .= $oController->oForm->createInput($oController->multi.'ZONE_LABEL2', t('LIBELLE'), 255, '', false, $oController->zoneValues['ZONE_LABEL2'], $oController->readO, 100);
        $sControllerForm .= $oController->oForm->createInput($oController->multi.'ZONE_URL2', t('URL_MOB'), 255, 'internallink', false, $oController->zoneValues['ZONE_URL2'], $oController->readO, 100);
        $sControllerForm .= $oController->oForm->createComboFromList($oController->multi.'ZONE_TITRE2', t("MODE_OUVERTURE"), $aLinkOpenMode, $oController->zoneValues['ZONE_TITRE2'], false, $oController->readO);

        return $sControllerForm;
    }

    /**
     * Surcharge de la méthode de sauvegarde pour y inclure les enregistrements
     * des multis et tableaux associatif.
     *
     * @param Pelican_Controller $controller
     */
    public static function save()
    {
        Backoffice_Form_Helper::saveMultiValuesField('FAQ_CAT', 'ZONE_PARAMETERS');
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}
