<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_DisponibleSur extends Cms_Page_Citroen
{  
    public static function render(Pelican_Controller $controller)  
    {  
        
        $return = Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t('TITRE'), 255, "", true, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);        

        $sqlData = "
            select 
                VEHICULE_ID, 
                VEHICULE_LABEL 
            from #pref#_vehicule
            WHERE SITE_ID=".$_SESSION[APP]['SITE_ID']."
            AND LANGUE_ID=".$_SESSION[APP]['LANGUE_ID']."
            AND VEHICULE_ID IN (SELECT
                                    pz.ZONE_ATTRIBUT
                                FROM 
                                    #pref#_page p
                                    INNER JOIN #pref#_page_version pv
                                        ON (pv.PAGE_ID = p.PAGE_ID
                                            AND pv.LANGUE_ID = p.LANGUE_ID
                                            AND pv.PAGE_VERSION = p.PAGE_CURRENT_VERSION)
                                    INNER JOIN #pref#_zone_template zt
                                        ON (zt.TEMPLATE_PAGE_ID = pv.TEMPLATE_PAGE_ID)
                                    INNER JOIN #pref#_page_zone pz
                                        ON (pz.PAGE_ID = pv.PAGE_ID
                                            AND pz.LANGUE_ID = pv.LANGUE_ID
                                            AND pz.PAGE_VERSION = pv.PAGE_VERSION
                                            AND pz.ZONE_TEMPLATE_ID = zt.ZONE_TEMPLATE_ID)
                                    WHERE 
                                        zt.ZONE_ID = ".Pelican::$config['ZONE']['SELECTEUR_DE_TEINTE']."
                                    AND p.SITE_ID = ".$_SESSION[APP]['SITE_ID']."
                                    AND p.LANGUE_ID = ".$_SESSION[APP]['LANGUE_ID']."
                                    AND p.PAGE_STATUS = 1
                                    AND pv.STATE_ID = 4)
            order by VEHICULE_LABEL";
        
        if($controller->zoneValues['PAGE_ID'] != -2 && $controller->zoneValues['PAGE_ID'] != ''
                && $controller->zoneValues['ZONE_TEXTE'] != ''){
            $sqlSelected = " SELECT VEHICULE_ID, VEHICULE_LABEL 
            FROM #pref#_vehicule
            WHERE SITE_ID = ".$_SESSION[APP]['SITE_ID']."
            AND LANGUE_ID = ".$_SESSION[APP]['LANGUE_ID']."
            AND VEHICULE_ID in (" . str_replace('#', ', ', $controller->zoneValues['ZONE_TEXTE']) . ")
            order by field(VEHICULE_ID, " . str_replace('#', ', ', $controller->zoneValues['ZONE_TEXTE']) . " )";
        }
        else{
            $sqlSelected = "";
        }

        $return .= $controller->oForm->createAssocFromSql(Pelican_Db::getInstance(), $controller->multi . "ZONE_TEXTE", t("Selection Vehicules"), $sqlData, $sqlSelected, true, true, $controller->readO, 8, 200, false, "", "", $aBind, 'ordre');
        
        return $return;   
    }  
      
    public static function save()  
    {   
        Backoffice_Form_Helper::saveFormAffichage();
        if(Pelican_Db::$values['ZONE_TEXTE']){
            Pelican_Db::$values['ZONE_TEXTE'] = implode('#', Pelican_Db::$values['ZONE_TEXTE']);
        }
        parent::save(); 
        
        Pelican_Cache::clean("Frontend/Citroen/VehiculeDisponibleSur");
    }  
}  
?>
