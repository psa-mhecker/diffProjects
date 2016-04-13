<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');

class Cms_Page_Citroen_ContenusRecommandes extends Cms_Page_Citroen {

    public static function render(Pelican_Controller $controller) {
        $return = Backoffice_Form_Helper::getFormAffichage($controller, true, false);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t('TITRE'), 255, "", true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $sqlData = "
            select
                CONTENU_RECOMMANDE_ID as id,
                CONTENU_RECOMMANDE_TITRE_BO as lib
            from #pref#_contenu_recommande
            where SITE_ID = " . $_SESSION[APP]['SITE_ID'] . "
            and LANGUE_ID = " . $_SESSION[APP]['LANGUE_ID'] . "
            order by lib asc";
        $sqlSelected = "
            select
                cr.CONTENU_RECOMMANDE_ID as id,
                cr.CONTENU_RECOMMANDE_TITRE_BO as lib
            from #pref#_contenu_recommande cr
            where SITE_ID = " . $_SESSION[APP]['SITE_ID'] . "
            and LANGUE_ID = " . $_SESSION[APP]['LANGUE_ID'] . " ";
        if (is_array($controller->zoneValues['ZONE_LABEL2'])) {
            $sqlSelected .= "
                and CONTENU_RECOMMANDE_ID in (" . implode(',', $controller->zoneValues['ZONE_LABEL2']) . ")
                order by field(CONTENU_RECOMMANDE_ID ," . implode(',', $controller->zoneValues['ZONE_LABEL2']) . ")
            ";
        } else if ($controller->zoneValues['ZONE_LABEL2']) {
            $sqlSelected .= "
                and CONTENU_RECOMMANDE_ID in (" . str_replace('|', ',', $controller->zoneValues['ZONE_LABEL2']) . ")
                order by field(CONTENU_RECOMMANDE_ID ," . str_replace('|', ',', $controller->zoneValues['ZONE_LABEL2']) . ")
            ";
        } else {
            $sqlSelected .= "and 1!=1 ";
        }

        $return .= $controller->oForm->createAssocFromSql($oConnection, $controller->multi . "ZONE_LABEL2", t('CONTENUS_RECOMMANDES'), $sqlData, $sqlSelected, false, true, $controller->readO, 8, 200, false, "", "", $aBind, 'ordre', false, false);
        
        // evol CPW-3006
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_ATTRIBUT", t('DESACTIVER_LEADS'), array(1 => ""), $controller->zoneValues['ZONE_ATTRIBUT'], false, $controller->readO);
        $return .= Backoffice_Form_Helper::getOutils($controller, true, false, null, 5, false);        
        // fin evol
        
        $sJS = "
            if ($('select#" . $controller->multi . "CONTENUS option').length>9) {
                alert('" . t('CONTENUS_RECOMMANDES_MAX', 'js') . "');
                return false;
            }
        ";       
        $return .= $controller->oForm->createJS($sJS);
        return $return;
    }

    public static function save(Pelican_Controller $controller) {
        if (Pelican_Db::$values['ZONE_LABEL2']) {
            Pelican_Db::$values['ZONE_LABEL2'] = implode('|', Pelican_Db::$values['ZONE_LABEL2']);
        }
        Backoffice_Form_Helper::saveOutils();
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        Pelican_Cache::clean("Frontend/Citroen/VehiculeOutil");
        Pelican_Cache::clean("Frontend/Citroen/BarreOutils");
    }
}