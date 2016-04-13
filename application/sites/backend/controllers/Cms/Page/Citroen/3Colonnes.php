<?php

include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');

class Cms_Page_Citroen_3Colonnes extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        //Vérification nb de colonne
        $return = $controller->oForm->createJS("
            var col_3_1 = document.getElementById('".$controller->multi."ADDCOLFORM0_multi_display');
            var col_3_2 = document.getElementById('".$controller->multi."ADDCOLFORM1_multi_display');
            var col_3_3 = document.getElementById('".$controller->multi."ADDCOLFORM2_multi_display');
            if((col_3_1 == null || col_3_1.value ==0) || (col_3_2 == null || col_3_2.value ==0) || (col_3_3 == null || col_3_3.value ==0)){
                alert('".t('MIN_THREE_COL', 'js')." ".   $controller->zoneValues['ZONE_TEMPLATE_LABEL'] ."');
				return false;
            }
        ");
        $return .= Backoffice_Form_Helper::getForm($controller->zoneValues["ZONE_BO_PATH"], $controller);

        return $return;
    }

    /*Enregistrement complémentaires multi
     */
    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE){
            Backoffice_Form_Helper::saveCta();
            Backoffice_Form_Helper::saveMultiColumn();
            Backoffice_Form_Helper::savePushGallery();
            Pelican_Cache::clean('Frontend/Citroen/ZoneMulti', 'SITE_ID');
        }
    }
}