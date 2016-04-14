<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
/**
 * Classe d'administration de la tranche Connexion de Mon projet.
 */
class Cms_Page_Citroen_MonProjet_Connexion extends Cms_Page_Citroen
{
    /**
     * Affichage du formulaire.
     *
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {
        $aMulti = array('NON_IDENTIFIE','INSCRIPTION', 'GESTION_ERREURS', 'FINALISATION_INSCRIPTION_CITROENID', 'FINALISATION_INSCRIPTION_RS', 'CONFIRMATION_INSCRIPTION', 'CONNECTE');
        foreach ($aMulti as $type) {
            $result = Backoffice_Form_Helper::getPageZoneMultiValues($oController, $type);
            $aMultiValues[$type] = $result[0];
        }
        $return = $oController->oForm->createComboFromList($oController->multi."ZONE_TITRE", t('DUREE_SESSION_CONNEXION'), Pelican::$config['DUREE_SESSION_CONNEXION'], $oController->zoneValues['ZONE_TITRE'], true, $oController->readO, 1, false, "200", true);
        $aSelectedValues = explode('|', $oController->zoneValues['ZONE_PARAMETERS']);
        $return .= $oController->oForm->createComboFromList($oController->multi."ZONE_PARAMETERS", t('RESEAUX_SOCIAUX'), Pelican::$config['CONNEXION_RESEAUX_SOCIAUX'], $aSelectedValues, false, $oController->readO, 3, true, "200", false);
        // Non identifié
        $return .= $oController->oForm->createLabel(t('NON_IDENTIFIE'), "");
        $return .= $oController->oForm->createInput($oController->multi."NON_IDENTIFIEPAGE_ZONE_MULTI_TITRE", t('TITRE_CONNEXION'), 255, "", true, $aMultiValues['NON_IDENTIFIE']['PAGE_ZONE_MULTI_TITRE'], $oController->readO, 75);
        $return .= $oController->oForm->createInput($oController->multi."NON_IDENTIFIEPAGE_ZONE_MULTI_TITRE2", t('SOUSTITRE_CONNEXION_CITROENID'), 255, "", true, $aMultiValues['NON_IDENTIFIE']['PAGE_ZONE_MULTI_TITRE2'], $oController->readO, 75);
        $return .= $oController->oForm->createTextArea($oController->multi."NON_IDENTIFIEPAGE_ZONE_MULTI_TEXT2", t('INFOBULLE_CITROENID'), true, $aMultiValues['NON_IDENTIFIE']['PAGE_ZONE_MULTI_TEXT2'], "", $oController->readO, 5, 72);
        $return .= $oController->oForm->createInput($oController->multi."NON_IDENTIFIEPAGE_ZONE_MULTI_TITRE3", t('SOUSTITRE_CONNEXION_RS'), 255, "", true, $aMultiValues['NON_IDENTIFIE']['PAGE_ZONE_MULTI_TITRE3'], $oController->readO, 75);
        $return .= $oController->oForm->createTextArea($oController->multi."NON_IDENTIFIEPAGE_ZONE_MULTI_TEXT3", t('INFOBULLE_RS'), true, $aMultiValues['NON_IDENTIFIE']['PAGE_ZONE_MULTI_TEXT3'], "", $oController->readO, 5, 72);
        $return .= $oController->oForm->createInput($oController->multi."NON_IDENTIFIEPAGE_ZONE_MULTI_TITRE4", t('TITRE_INSCRIPTION'), 255, "", true, $aMultiValues['NON_IDENTIFIE']['PAGE_ZONE_MULTI_TITRE4'], $oController->readO, 75);
        $return .= $oController->oForm->createInput($oController->multi."NON_IDENTIFIEPAGE_ZONE_MULTI_LABEL5", t('SOUSTITRE_INSCRIPTION'), 255, "", true, $aMultiValues['NON_IDENTIFIE']['PAGE_ZONE_MULTI_LABEL5'], $oController->readO, 75);
        // Inscription
        $return .= $oController->oForm->createLabel(t('INSCRIPTION'), "");
        $return .= $oController->oForm->createLabel(t('OPTIN_DEALER'), "");
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."INSCRIPTIONPAGE_ZONE_MULTI_TITRE", t('ACTIVATION'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $aMultiValues['INSCRIPTION']['PAGE_ZONE_MULTI_TITRE'], false, $oController->readO);
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."INSCRIPTIONPAGE_ZONE_MULTI_LABEL", t('OBLIGATOIRE'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $aMultiValues['INSCRIPTION']['PAGE_ZONE_MULTI_LABEL'], false, $oController->readO);
        $return .= $oController->oForm->createTextArea($oController->multi."INSCRIPTIONPAGE_ZONE_MULTI_TEXT", t('TEXTE'), false, $aMultiValues['INSCRIPTION']['PAGE_ZONE_MULTI_TEXT'], "", $oController->readO, 5, 72);
        $return .= $oController->oForm->createLabel(t('OPTIN_BRAND'), "");
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."INSCRIPTIONPAGE_ZONE_MULTI_TITRE4", t('ACTIVATION'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $aMultiValues['INSCRIPTION']['PAGE_ZONE_MULTI_TITRE4'], false, $oController->readO);
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."INSCRIPTIONPAGE_ZONE_MULTI_LABEL4", t('OBLIGATOIRE'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $aMultiValues['INSCRIPTION']['PAGE_ZONE_MULTI_LABEL4'], false, $oController->readO);
        $return .= $oController->oForm->createTextArea($oController->multi."INSCRIPTIONPAGE_ZONE_MULTI_TEXT4", t('TEXTE'), false, $aMultiValues['INSCRIPTION']['PAGE_ZONE_MULTI_TEXT4'], "", $oController->readO, 5, 72);
        $return .= $oController->oForm->createLabel(t('OPTIN_PARTNER'), "");
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."INSCRIPTIONPAGE_ZONE_MULTI_TITRE2", t('ACTIVATION'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $aMultiValues['INSCRIPTION']['PAGE_ZONE_MULTI_TITRE2'], false, $oController->readO);
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."INSCRIPTIONPAGE_ZONE_MULTI_LABEL2", t('OBLIGATOIRE'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $aMultiValues['INSCRIPTION']['PAGE_ZONE_MULTI_LABEL2'], false, $oController->readO);
        $return .= $oController->oForm->createTextArea($oController->multi."INSCRIPTIONPAGE_ZONE_MULTI_TEXT2", t('TEXTE'), false, $aMultiValues['INSCRIPTION']['PAGE_ZONE_MULTI_TEXT2'], "", $oController->readO, 5, 72);
        $return .= $oController->oForm->createLabel(t('OPTIN_CGU'), "");
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."INSCRIPTIONPAGE_ZONE_MULTI_TITRE3", t('ACTIVATION'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $aMultiValues['INSCRIPTION']['PAGE_ZONE_MULTI_TITRE3'], false, $oController->readO);
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."INSCRIPTIONPAGE_ZONE_MULTI_LABEL3", t('OBLIGATOIRE'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $aMultiValues['INSCRIPTION']['PAGE_ZONE_MULTI_LABEL3'], false, $oController->readO);
        $return .= $oController->oForm->createTextArea($oController->multi."INSCRIPTIONPAGE_ZONE_MULTI_TEXT3", t('TEXTE'), false, $aMultiValues['INSCRIPTION']['PAGE_ZONE_MULTI_TEXT3'], "", $oController->readO, 5, 72);
        $return .= Backoffice_Form_Helper::getMentionsLegales($oController, false, 'cinemascope');
        // Gestion des erreurs
        $return .= $oController->oForm->createLabel(t('GESTION_ERREURS'), "");
        $return .= $oController->oForm->createLabel(t('ERREUR_TECHNIQUE'), "");
        $return .= $oController->oForm->createInput($oController->multi."GESTION_ERREURSPAGE_ZONE_MULTI_TITRE", t('TITRE'), 255, "", true, $aMultiValues['GESTION_ERREURS']['PAGE_ZONE_MULTI_TITRE'], $oController->readO, 75);
        $return .= $oController->oForm->createEditor($oController->multi."GESTION_ERREURSPAGE_ZONE_MULTI_TEXT", t('MESSAGE'), true, $aMultiValues['GESTION_ERREURS']['PAGE_ZONE_MULTI_TEXT'], $oController->readO, true, "", 465, 200);
        $return .= $oController->oForm->createLabel(t('ERREUR_PERMISSION'), "");
        $return .= $oController->oForm->createInput($oController->multi."GESTION_ERREURSPAGE_ZONE_MULTI_TITRE2", t('TITRE'), 255, "", true, $aMultiValues['GESTION_ERREURS']['PAGE_ZONE_MULTI_TITRE2'], $oController->readO, 75);
        $return .= $oController->oForm->createEditor($oController->multi."GESTION_ERREURSPAGE_ZONE_MULTI_TEXT2", t('MESSAGE'), true, $aMultiValues['GESTION_ERREURS']['PAGE_ZONE_MULTI_TEXT2'], $oController->readO, true, "", 465, 200);
        // Finalisation inscription CitroënID
        $return .= $oController->oForm->createLabel(t('FINALISATION_INSCRIPTION_CITROENID'), "");
        $return .= $oController->oForm->createInput($oController->multi."FINALISATION_INSCRIPTION_CITROENIDPAGE_ZONE_MULTI_TITRE", t('TITRE'), 255, "", true, $aMultiValues['FINALISATION_INSCRIPTION_CITROENID']['PAGE_ZONE_MULTI_TITRE'], $oController->readO, 75);
        $return .= $oController->oForm->createEditor($oController->multi."FINALISATION_INSCRIPTION_CITROENIDPAGE_ZONE_MULTI_TEXT", t('TEXTE_ACCUEIL'), true, $aMultiValues['FINALISATION_INSCRIPTION_CITROENID']['PAGE_ZONE_MULTI_TEXT'], $oController->readO, true, "", 465, 200);
        // Finalisation inscription RS
        $return .= $oController->oForm->createLabel(t('FINALISATION_INSCRIPTION_RS'), "");
        $return .= $oController->oForm->createInput($oController->multi."FINALISATION_INSCRIPTION_RSPAGE_ZONE_MULTI_TITRE", t('TITRE'), 255, "", true, $aMultiValues['FINALISATION_INSCRIPTION_RS']['PAGE_ZONE_MULTI_TITRE'], $oController->readO, 75);
        $return .= $oController->oForm->createEditor($oController->multi."FINALISATION_INSCRIPTION_RSPAGE_ZONE_MULTI_TEXT", t('TEXTE_ACCUEIL'), true, $aMultiValues['FINALISATION_INSCRIPTION_RS']['PAGE_ZONE_MULTI_TEXT'], $oController->readO, true, "", 465, 200);
        // Confirmation d'inscription
        $return .= $oController->oForm->createLabel(t('CONFIRMATION_INSCRIPTION'), "");
        $return .= $oController->oForm->createLabel(t('VIA_CITROENID'), "");
        $return .= $oController->oForm->createInput($oController->multi."CONFIRMATION_INSCRIPTIONPAGE_ZONE_MULTI_TITRE", t('TITRE'), 255, "", true, $aMultiValues['CONFIRMATION_INSCRIPTION']['PAGE_ZONE_MULTI_TITRE'], $oController->readO, 75);
        $return .= $oController->oForm->createEditor($oController->multi."CONFIRMATION_INSCRIPTIONPAGE_ZONE_MULTI_TEXT", t('MESSAGE_CONFIRMATION'), true, $aMultiValues['CONFIRMATION_INSCRIPTION']['PAGE_ZONE_MULTI_TEXT'], $oController->readO, true, "", 465, 200);
        $return .= $oController->oForm->createLabel(t('VIA_RS'), "");
        $return .= $oController->oForm->createInput($oController->multi."CONFIRMATION_INSCRIPTIONPAGE_ZONE_MULTI_TITRE2", t('TITRE'), 255, "", true, $aMultiValues['CONFIRMATION_INSCRIPTION']['PAGE_ZONE_MULTI_TITRE2'], $oController->readO, 75);
        $return .= $oController->oForm->createEditor($oController->multi."CONFIRMATION_INSCRIPTIONPAGE_ZONE_MULTI_TEXT2", t('MESSAGE_CONFIRMATION'), true, $aMultiValues['CONFIRMATION_INSCRIPTION']['PAGE_ZONE_MULTI_TEXT2'], $oController->readO, true, "", 465, 200);
        // Connecté
        $return .= $oController->oForm->createLabel(t('CONNECTE'), "");
        $return .= $oController->oForm->createInput($oController->multi."CONNECTEPAGE_ZONE_MULTI_TITRE", t('TITRE_CONNEXION_FACILITE'), 255, "", true, $aMultiValues['CONNECTE']['PAGE_ZONE_MULTI_TITRE'], $oController->readO, 75);
        $return .= $oController->oForm->createEditor($oController->multi."CONNECTEPAGE_ZONE_MULTI_TEXT", t('MESSAGE_CONNEXION_FACILITE'), true, $aMultiValues['CONNECTE']['PAGE_ZONE_MULTI_TEXT'], $oController->readO, true, "", 465, 200);

        return $return;
    }

    public static function save(Pelican_Controller $oController)
    {
        if (Pelican_Db::$values['ZONE_PARAMETERS']) {
            Pelican_Db::$values['ZONE_PARAMETERS'] = implode('|', Pelican_Db::$values['ZONE_PARAMETERS']);
        }
        parent::save();
        // Suppression
        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_ID'] = Pelican_Db::$values['PAGE_ID'];
        $aBind[':LANGUE_ID'] = Pelican_Db::$values['LANGUE_ID'];
        $aBind[':PAGE_VERSION'] = Pelican_Db::$values['PAGE_VERSION'];
        $aBind[':ZONE_TEMPLATE_ID'] = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
        $sSQL = "
			delete from #pref#_page_zone_multi
			where PAGE_ID = :PAGE_ID
			and LANGUE_ID = :LANGUE_ID
			and PAGE_VERSION = :PAGE_VERSION
			and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID";
        $oConnection->query($sSQL, $aBind);
        // Création
        Pelican_Db::$values['PAGE_ZONE_MULTI_ID'] = 1;
        $TEMP = Pelican_Db::$values;
        // Traitement : Non identifié
        Pelican_Db::$values = $TEMP;
        Pelican_Db::$values['PAGE_ZONE_MULTI_TYPE'] = 'NON_IDENTIFIE';
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE'] = Pelican_Db::$values['NON_IDENTIFIEPAGE_ZONE_MULTI_TITRE'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE2'] = Pelican_Db::$values['NON_IDENTIFIEPAGE_ZONE_MULTI_TITRE2'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TEXT2'] = Pelican_Db::$values['NON_IDENTIFIEPAGE_ZONE_MULTI_TEXT2'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE3'] = Pelican_Db::$values['NON_IDENTIFIEPAGE_ZONE_MULTI_TITRE3'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TEXT3'] = Pelican_Db::$values['NON_IDENTIFIEPAGE_ZONE_MULTI_TEXT3'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE4'] = Pelican_Db::$values['NON_IDENTIFIEPAGE_ZONE_MULTI_TITRE4'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_LABEL5'] = Pelican_Db::$values['NON_IDENTIFIEPAGE_ZONE_MULTI_LABEL5'];
        $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_page_zone_multi");
        // Traitement : Inscription
        Pelican_Db::$values = $TEMP;
        Pelican_Db::$values['PAGE_ZONE_MULTI_TYPE'] = 'INSCRIPTION';
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE'] = Pelican_Db::$values['INSCRIPTIONPAGE_ZONE_MULTI_TITRE'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE2'] = Pelican_Db::$values['INSCRIPTIONPAGE_ZONE_MULTI_TITRE2'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE3'] = Pelican_Db::$values['INSCRIPTIONPAGE_ZONE_MULTI_TITRE3'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE4'] = Pelican_Db::$values['INSCRIPTIONPAGE_ZONE_MULTI_TITRE4'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_LABEL'] = Pelican_Db::$values['INSCRIPTIONPAGE_ZONE_MULTI_LABEL'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_LABEL2'] = Pelican_Db::$values['INSCRIPTIONPAGE_ZONE_MULTI_LABEL2'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_LABEL3'] = Pelican_Db::$values['INSCRIPTIONPAGE_ZONE_MULTI_LABEL3'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_LABEL4'] = Pelican_Db::$values['INSCRIPTIONPAGE_ZONE_MULTI_LABEL4'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TEXT'] = Pelican_Db::$values['INSCRIPTIONPAGE_ZONE_MULTI_TEXT'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TEXT2'] = Pelican_Db::$values['INSCRIPTIONPAGE_ZONE_MULTI_TEXT2'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TEXT3'] = Pelican_Db::$values['INSCRIPTIONPAGE_ZONE_MULTI_TEXT3'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TEXT4'] = Pelican_Db::$values['INSCRIPTIONPAGE_ZONE_MULTI_TEXT4'];
        $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_page_zone_multi");
        // Traitement : Gestion des erreurs
        Pelican_Db::$values = $TEMP;
        Pelican_Db::$values['PAGE_ZONE_MULTI_TYPE'] = 'GESTION_ERREURS';
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE'] = Pelican_Db::$values['GESTION_ERREURSPAGE_ZONE_MULTI_TITRE'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE2'] = Pelican_Db::$values['GESTION_ERREURSPAGE_ZONE_MULTI_TITRE2'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TEXT'] = Pelican_Db::$values['GESTION_ERREURSPAGE_ZONE_MULTI_TEXT'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TEXT2'] = Pelican_Db::$values['GESTION_ERREURSPAGE_ZONE_MULTI_TEXT2'];
        $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_page_zone_multi");
        // Traitement : Finalisation inscription CitroënID
        Pelican_Db::$values = $TEMP;
        Pelican_Db::$values['PAGE_ZONE_MULTI_TYPE'] = 'FINALISATION_INSCRIPTION_CITROENID';
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE'] = Pelican_Db::$values['FINALISATION_INSCRIPTION_CITROENIDPAGE_ZONE_MULTI_TITRE'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TEXT'] = Pelican_Db::$values['FINALISATION_INSCRIPTION_CITROENIDPAGE_ZONE_MULTI_TEXT'];
        $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_page_zone_multi");
        // Traitement : Finalisation inscription RS
        Pelican_Db::$values = $TEMP;
        Pelican_Db::$values['PAGE_ZONE_MULTI_TYPE'] = 'FINALISATION_INSCRIPTION_RS';
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE'] = Pelican_Db::$values['FINALISATION_INSCRIPTION_RSPAGE_ZONE_MULTI_TITRE'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TEXT'] = Pelican_Db::$values['FINALISATION_INSCRIPTION_RSPAGE_ZONE_MULTI_TEXT'];
        $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_page_zone_multi");
        // Traitement : Confirmation d'inscription
        Pelican_Db::$values = $TEMP;
        Pelican_Db::$values['PAGE_ZONE_MULTI_TYPE'] = 'CONFIRMATION_INSCRIPTION';
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE'] = Pelican_Db::$values['CONFIRMATION_INSCRIPTIONPAGE_ZONE_MULTI_TITRE'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE2'] = Pelican_Db::$values['CONFIRMATION_INSCRIPTIONPAGE_ZONE_MULTI_TITRE2'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TEXT'] = Pelican_Db::$values['CONFIRMATION_INSCRIPTIONPAGE_ZONE_MULTI_TEXT'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TEXT2'] = Pelican_Db::$values['CONFIRMATION_INSCRIPTIONPAGE_ZONE_MULTI_TEXT2'];
        $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_page_zone_multi");
        // Traitement : Confirmation d'inscription
        Pelican_Db::$values = $TEMP;
        Pelican_Db::$values['PAGE_ZONE_MULTI_TYPE'] = 'CONNECTE';
        Pelican_Db::$values['PAGE_ZONE_MULTI_TITRE'] = Pelican_Db::$values['CONNECTEPAGE_ZONE_MULTI_TITRE'];
        Pelican_Db::$values['PAGE_ZONE_MULTI_TEXT'] = Pelican_Db::$values['CONNECTEPAGE_ZONE_MULTI_TEXT'];
        $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_page_zone_multi");
    }
}
