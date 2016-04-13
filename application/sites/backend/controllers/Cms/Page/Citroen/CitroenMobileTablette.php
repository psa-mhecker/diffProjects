<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Module.php');
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_CitroenMobileTablette extends Cms_Page_Citroen
{
    public $i = 0;
    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);        
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t ( 'TITRE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);

        $sMultiName = $controller->multi .'ADDAPPLI';
        $return .= $controller->oForm->createMultiHmvc(
                $sMultiName,
                t('APPLI_FORM'),
                array(
                    'path' => __FILE__,
                    'class' => __CLASS__,
                    'method' => 'addAppli'
                 ),
                Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'APPLI'),
                $sMultiName, $controller->readO, '', true, true, $sMultiName
            );
        $return .= $controller->oForm->createJS("
            var i = 0;
            var j = 0;
            var flag = 0;

            while(flag == 0)
            {
                 if(document.getElementById('".$controller->multi."ADDAPPLI'+ i +'_PAGE_ZONE_MULTI_TITRE'))
                 {
                 i++;
                 }
                 else
                 {
                    flag = 1;
                 }
            }

            for(j = 0; j < i; j++)
            {
                if(document.getElementById('".$controller->multi."ADDAPPLI'+ j +'_PAGE_ZONE_MULTI_LABEL').value != '' && document.getElementById('".$controller->multi."ADDAPPLI'+ j +'_PAGE_ZONE_MULTI_LABEL2').value != '')
                {
                    alert('".t('ALERT_MSG_PLATEFORM', 'js')."');
                    fwFocus(eval('".$controller->multi."ADDAPPLI'+ j +'_PAGE_ZONE_MULTI_LABEL2'));

                    return false;
                }
                if(document.getElementById('".$controller->multi."ADDAPPLI'+ j +'_PAGE_ZONE_MULTI_LABEL').value != '' && document.getElementById('".$controller->multi."ADDAPPLI'+ j +'_PAGE_ZONE_MULTI_LABEL3').value != '')
                {
                    alert('".t('ALERT_MSG_PLATEFORM', 'js')."');
                    fwFocus(eval('".$controller->multi."ADDAPPLI'+ j +'_PAGE_ZONE_MULTI_LABEL3'));

                    return false;
                }
                  if(document.getElementById('".$controller->multi."ADDAPPLI'+ j +'_PAGE_ZONE_MULTI_LABEL').value != '' && document.getElementById('".$controller->multi."ADDAPPLI'+ j +'_PAGE_ZONE_MULTI_LABEL4').value != '')
                {
                    alert('".t('ALERT_MSG_PLATEFORM', 'js')."');
                    fwFocus(eval('".$controller->multi."ADDAPPLI'+ j +'_PAGE_ZONE_MULTI_LABEL4'));

                    return false;
                }

                if(document.getElementById('".$controller->multi."ADDAPPLI'+ (j+1) +'_PAGE_ZONE_MULTI_LABEL'))
                {
                    if(document.getElementById('".$controller->multi."ADDAPPLI'+ j +'_PAGE_ZONE_MULTI_LABEL').value != '' && document.getElementById('".$controller->multi."ADDAPPLI'+ (j+1) +'_PAGE_ZONE_MULTI_LABEL').value == '' || document.getElementById('".$controller->multi."ADDAPPLI'+ j +'_PAGE_ZONE_MULTI_LABEL').value == '' && document.getElementById('".$controller->multi."ADDAPPLI'+ (j+1) +'_PAGE_ZONE_MULTI_LABEL').value != '' )
                {
                    alert('".t('ALERT_MSG_MULTI', 'js')."');
                    fwFocus(eval('".$controller->multi."ADDAPPLI'+ (j+1) +'_PAGE_ZONE_MULTI_LABEL'));

                    return false;
                }


                }


            }


        ");

        return $return;
    }

    public static function addAppli($oForm, $values, $readO, $multi)
    {
        $aModeOUVERTURE = Pelican::$config['TRANCHE_COL']["BLANK_SELF"];

        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_TITRE", t ( 'TITRE' ), 100, "", true, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 50);
        $return .= $oForm->createMedia($multi."MEDIA_ID", t ('VISUEL'), true, "image", "", $values['MEDIA_ID'], $readO, true, false);
        $return .= $oForm->createTextArea($multi . "PAGE_ZONE_MULTI_TEXT", t('TEXTE_INITIAL'), false, $values["PAGE_ZONE_MULTI_TEXT"], 255, $readO, 5, 100, false, "", false);
$return .= $oForm->showSeparator("formSep");     
        $return .= $oForm->createLabel(t('LINK'),'');
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_LABEL", t ( 'LIBELLE' ), 255, "", false, $values["PAGE_ZONE_MULTI_LABEL"], $readO, 100);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_URL", t ( 'URL_WEB' ), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL"], $readO, 100);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_URL2", t ( 'URL_MOB' ), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL2"], $readO, 100);
        $return .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_VALUE", t("MODE_OUVERTURE"), $aModeOUVERTURE, $values["PAGE_ZONE_MULTI_VALUE"], true, $readO);
$return .= $oForm->showSeparator("formSep");        
        $return .= $oForm->createLabel(t('PLATFORM_1'),'');
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_LABEL2", t ( 'LIBELLE' ), 255, "", false, $values["PAGE_ZONE_MULTI_LABEL2"], $readO, 100);
        //$return .= $oForm->createMedia($multi."MEDIA_ID2", t ('VISUEL'), false, "image", "", $values['MEDIA_ID2'], $readO, true, false);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_URL3", t ( 'URL_WEB' ), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL3"], $readO, 100);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_URL4", t ( 'URL_MOB' ), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL4"], $readO, 100);
$return .= $oForm->showSeparator("formSep");
        $return .= $oForm->createLabel(t('PLATFORM_2'),'');
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_LABEL3", t ( 'LIBELLE' ), 255, "", false, $values["PAGE_ZONE_MULTI_LABEL3"], $readO, 100);
        //$return .= $oForm->createMedia($multi."MEDIA_ID3", t ('VISUEL'), false, "image", "", $values['MEDIA_ID3'], $readO, true, false);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_URL5", t ( 'URL_WEB' ), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL5"], $readO, 100);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_URL6", t ( 'URL_MOB' ), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL6"], $readO, 100);
$return .= $oForm->showSeparator("formSep");
        $return .= $oForm->createLabel(t('PLATFORM_3'),'');
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_LABEL4", t ( 'LIBELLE' ), 255, "", false, $values["PAGE_ZONE_MULTI_LABEL4"], $readO, 100);
        //$return .= $oForm->createMedia($multi."MEDIA_ID4", t ('VISUEL'), false, "image", "", $values['MEDIA_ID4'], $readO, true, false);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_URL7", t ( 'URL_WEB' ), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL7"], $readO, 100);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_URL8", t ( 'URL_MOB' ), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL8"], $readO, 100);

        return $return;
    }

    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
		parent::save();
        $i = 0;
        readMulti('ADDAPPLI','ADDAPPLI');
        $aSiteAddMulti = Pelican_Db::$values["ADDAPPLI"];
        if(is_array($aSiteAddMulti) && !empty($aSiteAddMulti)){
            foreach($aSiteAddMulti as $aSiteInfos){
                if($aSiteInfos['multi_display'] == 1){
                    $flag = false;

                    if(!empty($aSiteInfos['PAGE_ZONE_MULTI_LABEL']) && !empty($aSiteInfos['PAGE_ZONE_MULTI_LABEL2'])){
                        $flag = true;
                    }
                    if(!empty($aSiteInfos['PAGE_ZONE_MULTI_LABEL']) && !empty($aSiteInfos['PAGE_ZONE_MULTI_LABEL3'])){
                        $flag = true;
                    }
                    if(!empty($aSiteInfos['PAGE_ZONE_MULTI_LABEL']) && !empty($aSiteInfos['PAGE_ZONE_MULTI_LABEL4'])){
                        $flag = true;
                    }

                    if($flag){
                        $aSiteAddMulti[$i]['multi_display'] = 0;
                    }

                    $i++;
                }
            }

            $max = count($aSiteAddMulti);
            for($i = 0; $i <= $max; $i++){
                if(($i + 1 <= $max) && ($i + 2 <= $max)){
                    if(!empty($aSiteAddMulti[$i]['PAGE_ZONE_MULTI_LABEL']) && !empty($aSiteAddMulti[$i + 1]['PAGE_ZONE_MULTI_LABEL2']) && !empty($aSiteAddMulti[$i + 2]['PAGE_ZONE_MULTI_LABEL']))
                        $aSiteAddMulti[$i]['multi_display'] = 0;
                    elseif(!empty($aSiteAddMulti[$i]['PAGE_ZONE_MULTI_LABEL2']) && !empty($aSiteAddMulti[$i + 1]['PAGE_ZONE_MULTI_LABEL']) && !empty($aSiteAddMulti[$i + 2]['PAGE_ZONE_MULTI_LABEL2']))
                        $aSiteAddMulti[$i]['multi_display'] = 0;
                }
            }

            Pelican_Db::$values['ADD_APPLI'] = $aSiteAddMulti;
        }
        Backoffice_Form_Helper::savePageZoneMultiValues('ADDAPPLI', 'APPLI');
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }
}
?>