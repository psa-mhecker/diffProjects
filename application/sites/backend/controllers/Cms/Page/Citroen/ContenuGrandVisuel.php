<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_ContenuGrandVisuel extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormGroupeReseauxSociaux($controller, "ZONE_LABEL2", "PUBLIC");
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_TITRE11", t('RECUPERATION_AUTOMATIQUE'), array('1' => ""), ($controller->zoneValues)?$controller->zoneValues['ZONE_TITRE11']:1, false, $controller->readO, "h", false, "onchange=\"changeRecuperationAutomatique()\"");
        $return .= $controller->oForm->createMedia($controller->multi."MEDIA_ID", t('VISUEL'), false, "image", "", $controller->zoneValues['MEDIA_ID'], $controller->readO, true, false, 'grand_visuel');
        $js = "<script type=\"text/javascript\">
                function changeRecuperationAutomatique() {
                    val = $('input[name=".$controller->multi."ZONE_TITRE11]:checked').val();
                    if (val==1) {
                        $('td#div".$controller->multi."MEDIA_ID').parent().parent().parent().parent().parent().hide();
                        
                    } else {
                        $('td#div".$controller->multi."MEDIA_ID').parent().parent().parent().parent().parent().show();
                    }
                }
                $(document).ready(function() {
                    changeRecuperationAutomatique();
                });
            </script>";
        $return .= $js;
        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        if (Pelican_Db::$values['ZONE_TITRE11'] == 1) {
            unset(Pelican_Db::$values['MEDIA_ID']);
        }
        parent::save();
    }

}