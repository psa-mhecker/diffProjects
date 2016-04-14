<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Module.php';
/**
 * Classe d'administration de la tranche Gamme.
 *
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 *
 * @since 29/07/2013
 */
class Cms_Page_Citroen_PointsForts extends Cms_Page_Citroen
{
    /**
     * Affichage du formulaire.
     *
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {
        // drag and drop point fort
        $sControllerForm = '';

        /* Gestion des mode d'affichage Web ou Mobile */
        $sControllerForm .= Backoffice_Form_Helper::getFormAffichage($oController, true, true);
        /* Gestion de l'affichage Ligne DS, Ligne C, neutre */
        $sControllerForm .= Backoffice_Form_Helper::getFormModeAffichage($oController);
        /* Titre */
        $sControllerForm .= $oController->oForm->createInput($oController->multi.'ZONE_TITRE', t('TITRE'), 255, '', true, $oController->zoneValues['ZONE_TITRE'], $oController->readO, 75);
        /* Sous-Titre */
        $sControllerForm .= $oController->oForm->createInput($oController->multi.'ZONE_TITRE2', t('SOUS_TITRE'), 255, '', true, $oController->zoneValues['ZONE_TITRE2'], $oController->readO, 75);
        /* Introduction */
        $bPopup = false;
        $sControllerForm .= $oController->oForm->createEditor($oController->multi.'ZONE_TEXTE', t('TEXTE'), false, $oController->zoneValues['ZONE_TEXTE'], $oController->readO, true);
        /* Visuel */
        $sControllerForm .= $oController->oForm->createMedia($oController->multi.'MEDIA_ID', t('FORM_VISUAL'), true, 'image', '', $oController->zoneValues['MEDIA_ID'], $oController->readO, true, false, '16_9');
        /* Sous-Titre */
        $sControllerForm .= $oController->oForm->createInput($oController->multi.'ZONE_TITRE3', t('FORM_TITLE_LIST'), 255, '', true, $oController->zoneValues['ZONE_TITRE3'], $oController->readO, 75);
        /* Génération du multi pour les teintes du véhicules */
        $sMultiName = $oController->multi.'ADDPOINTFORT';
        $sControllerForm .= $oController->oForm->createMultiHmvc(
                $sMultiName,
                t('FORM_KEY_POINT'),
                array(
                    'path' => __FILE__,
                    'class' => __CLASS__,
                    'method' => 'addPointFortForm',
                 ),
                Backoffice_Form_Helper::getPageZoneMultiValues($oController, 'POINTS_FORTS'),
                $sMultiName, $oController->readO, '7', true, true, $sMultiName
                );

        return $sControllerForm;
    }

    /**
     * Méthode statique de création du formulaire multiple.
     *
     * @param object $oForm       Objet de la classe Form
     * @param array  $aValues     Tableau de données permettant de remplir les multi
     * @param mixed  $mReadO      Null ou false pour permettre la saisie dans le multi
     *                            true pas de saisie possible
     * @param string $sMultiLabel Préfixe des champs du multi
     *
     * @return string $sMultiForm     Formulaire généré
     */
    public static function addPointFortForm($oForm, $aValues, $mReadO, $sMultiLabel)
    {
        /* Libellé du point fort */
        $sMultiForm .= $oForm->createInput($sMultiLabel.'PAGE_ZONE_MULTI_TITRE', t('FORM_KEY_POINT'), 255, '', true, $aValues['PAGE_ZONE_MULTI_TITRE'], $mReadO, 75, false);

        return $sMultiForm;
    }

    /**
     * Méthode publique statique d'enregistrement du bloc.
     */
    public static function save()
    {
        /* Sauvegarde de l'affichage de la tranche (Web/mobile) */
        Backoffice_Form_Helper::saveFormAffichage();
     /* Sauvegarde des données du multi */
        Backoffice_Form_Helper::savePageZoneMultiValues('ADDPOINTFORT', 'POINTS_FORTS');
        parent::save();
    }
}
