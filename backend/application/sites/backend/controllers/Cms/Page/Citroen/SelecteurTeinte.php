<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
/**
 * Classe d'administration de la tranche Sélecteur de teinte.
 *
 * @author Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 *
 * @since 30/07/2013
 */
class Cms_Page_Citroen_SelecteurTeinte extends Cms_Page_Citroen
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
        /* TITRE LIGHT*/
        $sControllerForm .= $oController->oForm->createInput($oController->multi."ZONE_TITRE", t('TITRE_LIGHT'), 255, "", false, $oController->zoneValues["ZONE_TITRE"], $oController->readO, 100);
         /* TITRE GRAS*/
        $sControllerForm .= $oController->oForm->createInput($oController->multi."ZONE_TITRE2", t('TITRE_GRAS'), 255, "", true, $oController->zoneValues["ZONE_TITRE2"], $oController->readO, 100);
        /* Sélection d'un véhicule associé */
        $sControllerForm .= Backoffice_Form_Helper::getVehicule($oController);
        /* Mentions légales (x3) */
        //$sControllerForm .= self::get3MentionsLegales($oController);
        $aDataValues = Pelican::$config['TRANCHE_COL']["AFF_MODE_MENTION"];
        $sControllerForm .= $oController->oForm->createComboFromList($oController->multi."ZONE_TITRE5", t("MENTIONS_LEGALES"), $aDataValues, $oController->zoneValues["ZONE_TITRE5"], false, $oController->readO);

        return $sControllerForm;
    }
    /**
     * Méthode statique permettant la générations des 3 mentions légales.
     *
     * @param Objet $oController Controller Pelican_Controller
     *
     * @return string Formulaire
     */
    public static function get3MentionsLegales($oController)
    {
        /* Initilisation des variables */
        $aModifiedPages = array();
        $sMultiType = 'MENTIONS_LEGALES';
        $iNbMLMax = 3;
        $sControllerForm = '';

        /* Liste des modes d'affichage */
        $aDisplayModes = Pelican::$config['TRANCHE_COL']['AFF_MODE_MENTION'];

        /* Récupération des pages dont le template_page est 'mentions légales */
        $aPages = getComboValuesFromCache('Backend/Page',
                                            array( $_SESSION[APP]['SITE_ID'],
                                                $_SESSION[APP]['LANGUE_ID'],
                                                '',
                                                Pelican::$config['MENTION_LEGAL_TEMPLATE'], )
                                            );
        /* Modification des pages retournées pour supprimer les chiffres */
        if (is_array($aPages) && !empty($aPages)) {
            foreach ($aPages as $key => $page) {
                $aModifiedPages[$key] = preg_replace("/[0-9]/", "", $page);
            }
        }
        /* Récupération des mentions légales pour le bloc */
        $aMentionsLegales = Backoffice_Form_Helper::getPageZoneMultiValues($oController, $sMultiType);

        /* Champ caché indiquant le nombre d'éléments à prendre en compte -1 */
        $sControllerForm .= $oController->oForm->createHidden($oController->multi.'count_'.$sMultiType, $iNbMLMax-1);

        /* Création de 3 blocs mentions légales */
        for ($i = 0; $i < $iNbMLMax; $i++) {
            /* Intégration des données dans une partie des mentions légales */
            if (is_array($aMentionsLegales) && isset($aMentionsLegales[$i])) {
                $aOneMLValues['PAGE_ZONE_MULTI_LABEL2'] = $aMentionsLegales[$i]['PAGE_ZONE_MULTI_LABEL2'];
                $aOneMLValues['PAGE_ZONE_MULTI_LABEL'] = $aMentionsLegales[$i]['PAGE_ZONE_MULTI_LABEL'];
                $aOneMLValues['PAGE_ZONE_MULTI_TEXT'] = $aMentionsLegales[$i]['PAGE_ZONE_MULTI_TEXT'];
                $aOneMLValues['MEDIA_ID'] = $aMentionsLegales[$i]['MEDIA_ID'];
                $aOneMLValues['PAGE_ZONE_MULTI_LABEL3'] = $aMentionsLegales[$i]['PAGE_ZONE_MULTI_LABEL3'];
            } else {
                $aOneMLValues['PAGE_ZONE_MULTI_LABEL2'] = '';
                $aOneMLValues['PAGE_ZONE_MULTI_LABEL'] = '';
                $aOneMLValues['PAGE_ZONE_MULTI_TEXT'] = '';
                $aOneMLValues['MEDIA_ID'] = '';
                $aOneMLValues['PAGE_ZONE_MULTI_LABEL3'] = '';
            }
            /* Réproduction du comportement du MultiHmvc */
            $sKeyField = $oController->multi.$sMultiType.$i.'_';
            $sControllerForm .= $oController->oForm->showSeparator('formSep');
            $sControllerForm .= $oController->oForm->createHidden($sKeyField.'multi_display', 1);
            $sControllerForm .= $oController->oForm->createLabel(t('MENTIONS_LEGALES'), '');
            $sControllerForm .= $oController->oForm->createComboFromList($sKeyField.'PAGE_ZONE_MULTI_LABEL2', t('GESTION_MODE'), $aDisplayModes, $aOneMLValues['PAGE_ZONE_MULTI_LABEL2'], false, $oController->readO);
            $sControllerForm .= $oController->oForm->createInput($sKeyField.'PAGE_ZONE_MULTI_LABEL', t('TITRE'), 255, '', false, $aOneMLValues['PAGE_ZONE_MULTI_LABEL'], $oController->readO, 100);
            $sControllerForm .= $oController->oForm->createEditor($sKeyField.'PAGE_ZONE_MULTI_TEXT', t('TEXTE'), false, $aOneMLValues['PAGE_ZONE_MULTI_TEXT'], $oController->readO, true, "", 650, 150);
            $sControllerForm .= $oController->oForm->createMedia($sKeyField.'MEDIA_ID', t('MEDIA'), false, 'image', '', $aOneMLValues['MEDIA_ID'], $oController->readO, true, false, 'cinemascope');
            $sControllerForm .= $oController->oForm->createComboFromList($sKeyField.'PAGE_ZONE_MULTI_LABEL3', t('RUBRIQUE'), $aModifiedPages, $aOneMLValues['PAGE_ZONE_MULTI_LABEL3'], false, $oController->readO);
        }

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
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        /* Sauvegarde des données 3 mentions Légales */
        Backoffice_Form_Helper::savePageZoneMultiValues('MENTIONS_LEGALES', 'MENTIONS_LEGALES');
    }
}
