<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_DisponibleSurUrl extends Cms_Page_Citroen
{  
    public static function render(Pelican_Controller $controller)  
    {  
        
        $return = Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t('TITRE'), 255, "", true, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);        
        
        $sMultiName = $controller->multi . 'ADD_VEHICULE';
        $return .= $controller->oForm->createMultiHmvc(
                $sMultiName, t('VISUEL_TEXTE_FORM'), array(
                'path' => __FILE__,
                'class' => __CLASS__,
                'method' => 'addVehiculeForm'
                ), Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'VEHICULE'), $sMultiName, $controller->readO, array(2), true, true, $sMultiName
        );
        
        //evite les doublons de véhicules
        $return .= $controller->oForm->createJS("
            var cars = new Array();
            $(\"select[name^='".$controller->multi."ADD_VEHICULE']\").each(function(){
                var strAtt = $(this).attr('id').toString();
                if(strAtt.indexOf('CPT') == -1){
                    if(cars.indexOf($(this).val()) == -1){
                        cars.push($(this).val());
                    }else{
                        alert('".t('DOUBLON_VEHICULE', 'js')."');
                        $('#FIELD_BLANKS').val(1);
                    }
                }
            });
            
            var OrderMulti = new Array();            
            $(\"input[name^='".$controller->multi."ADD_VEHICULE'][name$='PAGE_ZONE_MULTI_ORDER']\").each(function(){
                if($(this).attr('id') != undefined){
                    var strAtt = $(this).attr('id').toString();
                    if(strAtt.indexOf('CPT') == -1){
                        if(OrderMulti.indexOf($(this).val()) == -1){
                            OrderMulti.push($(this).val());
                        }else{
                            alert('".t('PROBLEME_ORDER', 'js')."');
                            $('#FIELD_BLANKS').val(1);
                            return false;
                        }
                    }
                }
            });
        ");
        
        return $return;   
    }  
    
    public static function addVehiculeForm($oForm, $aValues, $mReadO, $sMultiLabel)
    {       
            $oConnection = Pelican_Db::getInstance();
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
            
            $sMultiForm .= $oForm->createComboFromSql($oConnection, $sMultiLabel . "PAGE_ZONE_MULTI_TITRE", t('SELECTION_VEHICULES'), $sqlData, $aValues['PAGE_ZONE_MULTI_TITRE'], true, $mReadO, "", false, 250, true);
            $sMultiForm .= $oForm->createInput($sMultiLabel . "PAGE_ZONE_MULTI_URL", t('URL'), 255, "internallink", true, $aValues['PAGE_ZONE_MULTI_URL'], $mReadO, 75);
            $sMultiForm .= $oForm->createInput($sMultiLabel . "PAGE_ZONE_MULTI_URL2", t('URL_MOB'), 255, "internallink", false, $aValues['PAGE_ZONE_MULTI_URL2'], $mReadO, 75);
            $sMultiForm .= $oForm->createRadioFromList($sMultiLabel . 'PAGE_ZONE_MULTI_TITRE2', t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $aValues['PAGE_ZONE_MULTI_TITRE2'], true, $mReadO);

            return $sMultiForm;
    }
      
    public static function save()  
    {   
        Backoffice_Form_Helper::saveFormAffichage();
        if(Pelican_Db::$values['ZONE_TEXTE']){
            Pelican_Db::$values['ZONE_TEXTE'] = implode('#', Pelican_Db::$values['ZONE_TEXTE']);
        }
        Backoffice_Form_Helper::savePageZoneMultiValues('ADD_VEHICULE', 'VEHICULE');
        parent::save(); 
        
        Pelican_Cache::clean("Frontend/Citroen/VehiculeDisponibleSur");
    }  
}  
?>
