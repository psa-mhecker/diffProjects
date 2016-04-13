<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_Configuration extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
		
		$oConnection = Pelican_Db::getInstance();
        $return .= $controller->oForm->showSeparator();  
		$return .= $controller->oForm->createLabel('',t('PAYS'));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE24", t('PAYS'), 255, "", false, $controller->zoneValues['ZONE_TITRE24'], $controller->readO, 75); 
		$return .= $controller->oForm->createLabel('',t('LOGO'));
        $return .= $controller->oForm->createInput($controller->multi."MEDIA_PATH2", t('ALT_LOGO'), 255, "", false, $controller->zoneValues['MEDIA_PATH2'], $controller->readO, 75);                
		$return .= $controller->oForm->createLabel('',t('PICTO_HEADER_MOBILE'));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TEXTE7", t('URL_PICTO_HEADER_MOBILE'), 255, "internallink", false, $controller->zoneValues['ZONE_TEXTE7'], $controller->readO, 75);
		$return .= $controller->oForm->createMedia($controller->multi  . 'MEDIA_ID9', t ('PICTO'), false, 'image', '', $controller->zoneValues['MEDIA_ID9'], ($_SESSION[APP]["PROFIL_LABEL"]=="ADMINISTRATEUR" && $_SESSION[APP]["user"]["main"] == 1)?false:true, true, false);
		$return .= $controller->oForm->createRadioFromList($controller->multi ."ZONE_TOOL", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $controller->zoneValues['ZONE_TOOL'], false, $readO);		                
        $return .= $controller->oForm->showSeparator();
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('EMISSION'), 10, "", true, ($controller->zoneValues['ZONE_TITRE'])?$controller->zoneValues['ZONE_TITRE']:"g/km", $controller->readO, 10);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('DEVISE_PAYS'), 10, "", true, ($controller->zoneValues['ZONE_TITRE2'])?$controller->zoneValues['ZONE_TITRE2']:"€", $controller->readO, 10);
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE15", t('POSITION_DEVICE'), array('1' => t("BEFORE_PRICE"), '2' => t("AFTER_PRICE")), $controller->zoneValues['ZONE_TITRE15'], true, $controller->readO);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('CONSOMMATION'), 10, "", true, ($controller->zoneValues['ZONE_TITRE3'])?$controller->zoneValues['ZONE_TITRE3']:"l/100km", $controller->readO, 10);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE4", t('TAILLE'), 10, "", true, ($controller->zoneValues['ZONE_TITRE4'])?$controller->zoneValues['ZONE_TITRE4']:"mm", $controller->readO, 10);
        $aDataValues = array(0 => t('NE_PAS_ACTIVER'), 1 => t('ACTIVER_SUR_SHOWROOM'), 2 => t('ACTIVER_PARTOUT'));
        $return .= $controller->oForm->createComboFromList($controller->multi."ZONE_PARAMETERS", t('ACTIVATION_PRIX_CREDITS'), $aDataValues, $controller->zoneValues['ZONE_PARAMETERS'], false, $controller->readO, 1, false, '', false);

        $return .= $controller->oForm->showSeparator();
        
        $return .= $controller->oForm->createLabel('',t('FORMAT_URL_CONFIGURATEUR'));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE5", t('URL_CONFIGURATEUR'), 255, "internallink", false, $controller->zoneValues['ZONE_TITRE5'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE6", t('URL_CONFIGURATEUR_MOBILE'), 255, "internallink", false, $controller->zoneValues['ZONE_TITRE6'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE19", t('URL_REBOND_CFG_PRO'), 255, "internallink", false, $controller->zoneValues['ZONE_TITRE19'], $controller->readO, 75);
    
		$return .= $controller->oForm->showSeparator();
		$return .= $controller->oForm->createLabel('',t('MOTEUR DE CONFIG'));
		$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE25", t('DEVISE_PRINCIPALE'), array('0' => 'Euro', '1' => t("MONNAIE_LOCALE")), $controller->zoneValues['ZONE_TITRE25'], false, $controller->readO);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE26", t('DEVISE_LOCALE'), 10, '', false, $controller->zoneValues["ZONE_TITRE26"], $controller->readO, 10);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE27", t('SEPARATEUR_MILLIERS'), 3, '', false, $controller->zoneValues["ZONE_TITRE27"], $controller->readO, 3);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE28", t('SEPARATEUR_DECIMAL'), 3, '', false, $controller->zoneValues["ZONE_TITRE28"], $controller->readO, 3);
		$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE29", t('FORMAT_MONTANT'), array('0' => t("FORMAT_MONTANT_SANS_ZERO"), '1' => t("FORMAT_MONTANT_ZERO")), $controller->zoneValues['ZONE_TITRE29'], false, $controller->readO);
		$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE30", t('POSITION_DEVICE'), array('0' => t("BEFORE_PRICE"), '1' => t("AFTER_PRICE")), $controller->zoneValues['ZONE_TITRE30'], false, $controller->readO);
		$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE31", t('A_PARTIR_DE_PRICE'), array('0' => t("BEFORE_A_PARTIR_DE"), '1' => t("AFTER_A_PARTIR_DE")), $controller->zoneValues['ZONE_TITRE31'], false, $controller->readO);
		$return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE32", t('PRICE'), array('0' => t("PRICE_HT"), '1' => t("PRICE_TTC")), $controller->zoneValues['ZONE_TITRE32'], false, $controller->readO);

        $return .= $controller->oForm->showSeparator();
        
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE7", t('URL_DEMANDE_ESSAI'), 255, "internallink", false, $controller->zoneValues['ZONE_TITRE7'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE8", t('URL_DEMANDE_ESSAI_MOBILE'), 255, "internallink", false, $controller->zoneValues['ZONE_TITRE8'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE9", t('URL_DEMANDE_OFFRE_COMMERCIALE'), 255, "internallink", false, $controller->zoneValues['ZONE_TITRE9'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE10", t('URL_DEMANDE_OFFRE_COMMERCIALE_MOBILE'), 255, "internallink", false, $controller->zoneValues['ZONE_TITRE10'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE11", t('URL_DEMANDE_BROCHURE'), 255, "internallink", false, $controller->zoneValues['ZONE_TITRE11'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL", t('URL_DEMANDE_BROCHURE_MOBILE'), 255, "internallink", false, $controller->zoneValues['ZONE_URL'], $controller->readO, 75);
		$return .= $controller->oForm->createLabel('',t('FORMAT_URL_BOUTIQUE'));
        $return .= $controller->oForm->createComboFromList($controller->multi . "ZONE_TITRE13", t("MODE_BOUTIQUE_ACCESSOIRE"), array('CSA01'=>t("MODE_AOA"),'CFGAC'=>t("MODE_CFG")), $controller->zoneValues['ZONE_TITRE13'], true, $controller->readO);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL2", t('URL_BOUTIQUE_ACCESSOIRE'), 255, "internallink", false, $controller->zoneValues['ZONE_URL2'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_LABEL2", t('URL_BOUTIQUE_ACCESSOIRE_MOBILE'), 255, "internallink", false, $controller->zoneValues['ZONE_LABEL2'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE18", t('URL_SITE_ADVISOR'), 255, "internallink", false, $controller->zoneValues['ZONE_TITRE18'], $controller->readO, 75);
        
        $return .= $controller->oForm->showSeparator();
        
        $return .= $controller->oForm->createLabel('', t('URL_CAR_STORE_AIDE'));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE14", t('URL_CARSTORE'), 255, '', true, $controller->zoneValues["ZONE_TITRE14"], $controller->readO, 100);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE20", t('URL_CARSTORE_PRO'), 255, '', true, $controller->zoneValues['ZONE_TITRE20'], $controller->readO, 100);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE21", t('URL_REBOND_CARSTORE'), 255, "internallink", false, $controller->zoneValues['ZONE_TITRE21'], $controller->readO, 75);

        $return .= $controller->oForm->showSeparator();

        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE12", t('FIL_DARIANE_HOME'), 255, "", false, $controller->zoneValues['ZONE_TITRE12'], $controller->readO, 75);
        //$return .= $controller->oForm->createCheckBoxFromList($controller->multi.'ZONE_TITRE16', t('FORM_SHOW_SCROLL_TOP'), array(1 => ''), $controller->zoneValues['ZONE_TITRE16'], false, $controller->readO);
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE16", t('BUBBLE_SHOW_AFF'), array('0' => t("NE_PAS_AFFICHER"), '1' => t("MODE_COOKIE"), '2' => t("AFFICHER_PARTOUT")), $controller->zoneValues['ZONE_TITRE16'], true, $controller->readO);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_ATTRIBUT", t('ZOOM'), 2, "", true, $controller->zoneValues['ZONE_ATTRIBUT'], $controller->readO, 2);
        $aBind[':SITE_ID'] = $_SESSION [APP]['SITE_ID'];
        $sSQL = "SELECT 
                                sd.SITE_DNS,
                                spd.SITE_PARAMETER_ID,
                                spd.SITE_PARAMETER_VALUE,
                                spd.SITE_PARAMETER_PARAM

                        FROM 
                                #pref#_site_dns sd
                LEFT JOIN #pref#_site_parameter_dns as spd 
                                ON (sd.SITE_ID=spd.SITE_ID and sd.SITE_DNS=spd.SITE_DNS and SITE_PARAMETER_ID like 'map_google')
                WHERE 
                                sd.SITE_ID = :SITE_ID
                        ORDER BY sd.SITE_DNS";
        $aGMap = $oConnection->queryRow($sSQL,$aBind);
        

        $mapName = $controller->multi."ZONE_MAP";
        $return .= $controller->oForm->createMapPremium($mapName, t('MAP'), true, $aGMap['SITE_PARAMETER_VALUE'], "", $controller->zoneValues['ZONE_MAP_LATITUDE'], $controller->zoneValues['ZONE_MAP_LONGITUDE'], $controller->readO, false);
        
        // CPW-3267  - stickybar - choix entre titre court et titre long
        $return .= $controller->oForm->showSeparator();
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE17", t('STICKYBAR_LENGTH_TITLE'), array('0' => t("STICKYBAR_LONG_TITLE"), '1' => t("STICKYBAR_SHORT_TITLE")), $controller->zoneValues['ZONE_TITRE17'], true, $controller->readO);
        
        $return .= $controller->oForm->showSeparator();
        if($controller->zoneValues['ZONE_TITRE22'] == ""){
            $controller->zoneValues['ZONE_TITRE22'] = 0;        
        }
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE22", t('AJOUT_URL_FLUX'), array('0' => t("NON"), '1' => t("OUI")), $controller->zoneValues['ZONE_TITRE22'], true, $controller->readO);
        
        //application des nouvelles dimensions du slideshow
        $return .= $controller->oForm->showSeparator();
        if($controller->zoneValues['ZONE_TITRE23'] == ""){
        	$controller->zoneValues['ZONE_TITRE23'] = 0;
        }
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE23", t('REDUCTION_SLIDESHOW'), array('0' => t("NON"), '1' => t("OUI")), $controller->zoneValues['ZONE_TITRE23'], true, $controller->readO);
        
        // Définition de la fonction callback appellée au déploiement du bloc
        $controller->getView()->getHead()->setScript("
        function showHideZone_callback_".$controller->zoneValues["ZONE_TEMPLATE_ID"]."(info){
            // Taille de la map = taille de son div parent
            if( info.action == 'open' ){
                google.maps.event.trigger(document.getElementById('".$mapName."_MAP'), 'resize');
            }
        }");

        return $return; 
    }

    public static function save(Pelican_Controller $controller)
    {
        parent::save();
        Pelican_Cache::clean("Frontend/Page/Template");
        Pelican_Cache::clean("Frontend/Citroen/Configuration");
        Pelican_Cache::clean("Frontend/Page/ZoneTemplate");
    }

}