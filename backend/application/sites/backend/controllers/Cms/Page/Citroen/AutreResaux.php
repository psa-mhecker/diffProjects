<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_AutreResaux extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);

        $return .= $controller->oForm->createJS("
        var arx = document.getElementById('".$controller->multi."ZONE_PARAMETERS[]').value;
        if(arx == '')
        {
            alert('".t('SELECT_ARX', 'js')."');
            return false;
        }
            ");

        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('SOUS_TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 50);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE", t('CHAPEAU'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);
        $sSQL = "
            select
                RESEAU_SOCIAL_ID as id,
                RESEAU_SOCIAL_LABEL as lib
            from #pref#_reseau_social
            where SITE_ID = ".$_SESSION[APP]['SITE_ID']."
            and LANGUE_ID = ".$_SESSION[APP]['LANGUE_ID']."
            order by RESEAU_SOCIAL_ORDER asc";
        if (is_array($controller->zoneValues['ZONE_PARAMETERS'])) {
            $aSelectedValues  = $controller->zoneValues['ZONE_PARAMETERS'];
        } elseif (!empty($controller->zoneValues['ZONE_PARAMETERS'])) {
            $aSelectedValues  = explode('|', $controller->zoneValues['ZONE_PARAMETERS']);
        } else {
            $aSelectedValues = array();
        }
        $return .= $controller->oForm->createComboFromSql($oConnection, $controller->multi."ZONE_PARAMETERS", t('RESEAUX_SOCIAUX'), $sSQL, $aSelectedValues, true, $controller->readO, 10, true, 250, false);

        return $return;
    }

    public static function save()
    {
        if (!empty(Pelican_Db::$values['ZONE_PARAMETERS'])) {
            Pelican_Db::$values['ZONE_PARAMETERS'] = implode('|', Pelican_Db::$values['ZONE_PARAMETERS']);
        }
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        Pelican_Cache::clean("Frontend/Citroen/ReseauxSociaux");
    }
}
