<?php  
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Module.php');
class Cms_Content_Citroen_ForfaitEntretien extends Cms_Content_Module  
{  
    public static $decacheBack = array(
        array('Frontend/Citroen/MenuForfait'),
        array('Frontend/Citroen/ListeForfait'),
        array('Frontend/Citroen/ZonesListeForfait')
        );
    
    public static $decachePublication = array(
        array('Frontend/Citroen/MenuForfait'),
        array('Frontend/Citroen/ListeForfait'),
        array('Frontend/Citroen/ZonesListeForfait')
        );
    
    public static function render(Pelican_Controller $controller)  
    {  
        $return = $controller->oForm->createJS("
            var galerie_visuel = document.getElementById('".$controller->multi."ADD_VISUEL0_multi_display');
            var video = document.getElementById('divMEDIA_ID');
            var vignette = document.getElementById('divMEDIA_ID8');

            if(video.innerHTML != '' && vignette.innerHTML == '')
            {
                alert('".t('VIGNETT_REQUISE', 'js')."');
                return false;
            }
            
            if(video.innerHTML == '' && vignette.innerHTML != '')
            {
                alert('".t('SELECTIONNER_VIDEO', 'js')."');
                return false;
            }
            
            ");
        $return .= $controller->oForm->createMedia("MEDIA_ID7", t('picto'), true, "image", "", $controller->values['MEDIA_ID7'], $readO, true, false, 'carre');
        $return .= $controller->oForm->createInput("CONTENT_SUBTITLE", t('SUBTITLE'), 255, "", false, $controller->values['CONTENT_SUBTITLE'], $controller->readO, 75);
        $return .= $controller->oForm->createMedia("MEDIA_ID", t('VIDEO'), false, "video", "", $controller->values['MEDIA_ID'], $controller->readO);
        
        // multi visuel
        $sMultiName = $controller->multi .'ADD_VISUEL'; 
        $return .= $controller->oForm->createMedia("MEDIA_ID8", t ( 'VIGNETTE' ), false, "image", "", $controller->values["MEDIA_ID8"], $controller->readO, true, false, "16_9");
        
        $return .= $controller->oForm->createMultiHmvc(
            $sMultiName, 
            t('IMAGE'), 
            array(
                'path' => __FILE__,
                'class' => __CLASS__,
                'method' => 'addVISUELFORFAITForm'
                ), 
            Backoffice_Form_Helper::getContentZoneMultiValues($controller, $controller->values['CONTENT_ID'], 'VISUELFORFAIT'), 
            $sMultiName, $controller->readO, '', true, true, $sMultiName
            );
        
        // multi Prix forfait
        $sMultiName = $controller->multi .'ADD_PRIXFORFAIT';
        $return .= $controller->oForm->createMultiHmvc(
            $sMultiName, 
            t('PRIX'), 
            array(
                'path' => __FILE__,
                'class' => __CLASS__,
                'method' => 'addPRIXFORFAITForm'
                ), 
            Backoffice_Form_Helper::getContentZoneMultiValues($controller, $controller->values['CONTENT_ID'], 'PRIXFORFAIT'), 
            $sMultiName, $controller->readO, '', true, true, $sMultiName
            );
        
        $return .= Backoffice_Form_Helper::getMentionsLegales($controller, true);
        $return .= Backoffice_Form_Helper::getPushMediaCommun($controller, true);
        
        // multi CTA forfait
        $sMultiName = $controller->multi .'ADD_CTA';
        $return .= $controller->oForm->createMultiHmvc(
            $sMultiName, 
            t('CTA'), 
            array(
                'path' => __FILE__,
                'class' => __CLASS__,
                'method' => 'addCTAFORFAITForm'
                ), 
            Backoffice_Form_Helper::getContentZoneMultiValues($controller, $controller->values['CONTENT_ID'], 'CTAFORFAIT'), 
            $sMultiName, $controller->readO, '', true, true, $sMultiName
            );
        
        return $return;    
    }
    
    public static function addVISUELFORFAITForm($oForm, $values, $readO, $multi) 
    {
        $return .= $oForm->createMedia($multi."MEDIA_ID", t('VISUEL'), true, "image", "", $values['MEDIA_ID'], $readO, true, false);
        
        return $return;
    }
    
    public static function addPRIXFORFAITForm ($oForm, $values, $readO, $multi) 
    {
        $return .= $oForm->createInput($multi."CONTENT_ZONE_MULTI_LABEL", t('PRIX_A_PARTIR1'), 100, "", false, $values['CONTENT_ZONE_MULTI_LABEL'], $readO, 75);
        $return .= $oForm->createInput($multi."CONTENT_ZONE_MULTI_LABEL2", t('PRIX_A_PARTIR2'), 100, "", false, $values['CONTENT_ZONE_MULTI_LABEL2'], $readO, 75);
        $return .= $oForm->createEditor($multi . "CONTENT_ZONE_MULTI_TEXT", t('TEXTE'), true, $values["CONTENT_ZONE_MULTI_TEXT"], $readO, true, "", 650, 150);        
        
        return $return;
    }
    
    public static function addCTAFORFAITForm($oForm, $values, $readO, $multi) 
    {
        $aDataValues = Pelican::$config['TRANCHE_COL']["BLANK_SELF"];

        $return .= $oForm->createInput ($multi . "CONTENT_ZONE_MULTI_LABEL3", t ( 'LIBELLE' ), 40, "", true, $values["CONTENT_ZONE_MULTI_LABEL3"], $readO, 100);
        $return .= $oForm->createInput ($multi . "CONTENT_ZONE_MULTI_URL", t ( 'URL_WEB' ), 255, "internallink", true, $values["CONTENT_ZONE_MULTI_URL"], $readO, 100);
        $return .= $oForm->createInput ($multi . "CONTENT_ZONE_MULTI_URL2", t ( 'URL_MOB' ), 255, "internallink", false, $values["CONTENT_ZONE_MULTI_URL2"], $readO, 100);
        $return .= $oForm->createComboFromList($multi . "CONTENT_ZONE_MULTI_VALUE", t("MODE_OUVERTURE"), $aDataValues, strtoupper($values["CONTENT_ZONE_MULTI_VALUE"]), true, $readO);

        return $return;
    } 
    
    public static function save(Pelican_Controller $controller)
    {
        parent::save($controller);
        Backoffice_Form_Helper::saveContentZoneMultiValues('ADD_VISUEL','VISUELFORFAIT');
        Backoffice_Form_Helper::saveContentZoneMultiValues('ADD_PRIXFORFAIT','PRIXFORFAIT');
        Backoffice_Form_Helper::saveContentZoneMultiValues('GALLERYFORM','GALLERYFORM');
        Backoffice_Form_Helper::saveContentZoneMultiValues('ADD_CTA','CTAFORFAIT');
        Pelican_Cache::clean('Frontend/Citroen/MenuForfait');
        Pelican_Cache::clean('Frontend/Citroen/ListeForfait');
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
        Pelican_Cache::clean('Frontend/Citroen/ZonesListeForfait');
        Pelican_Cache::clean("Frontend/Citroen/ZonesContentMulti");

    }
}
?>
