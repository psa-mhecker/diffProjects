<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_RemonteesReseauxSociaux extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createJs("
            var facebook = document.getElementById('".$controller->multi."ZONE_TITRE5');
            var youtube = document.getElementById('".$controller->multi."ZONE_TITRE6');
            var twitter = document.getElementById('".$controller->multi."ZONE_TITRE7');
            var instagram = document.getElementById('".$controller->multi."ZONE_TITRE8');

            if(facebook.value != '' && youtube.value != '' && twitter.value != '' && instagram.value != ''){
                alert('".t('MAX_3_RS', 'js')."');
                return false;
            }
        ");

        $return .= Backoffice_Form_Helper::getFormAffichage($controller, true, false);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE3"], $controller->readO, 100);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE4", t('SOUS_TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE4"], $controller->readO, 100);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE", t('CHAPEAU'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);

        $aListeReseauxSociaux[] = array('champ' => "ZONE_TITRE5", 'type' => "FACEBOOK");
        $aListeReseauxSociaux[] = array('champ' => "ZONE_TITRE6", 'type' => "YOUTUBE");
        $aListeReseauxSociaux[] = array('champ' => "ZONE_TITRE7", 'type' => "TWITTER");
        $aListeReseauxSociaux[] = array('champ' => "ZONE_TITRE8", 'type' => "INSTAGRAM");
        foreach ($aListeReseauxSociaux as $rs) {
            $return .= Backoffice_Form_Helper::getFormReseauSocial($controller, $rs['type'], $rs['champ']);
        }

        return $return;
    }

    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}
