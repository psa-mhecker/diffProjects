<?php
include_once Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php";
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_Outil extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $aBind[":LANGUE_ID"] = $controller->zoneValues['LANGUE_ID'];

        $SQL = "
            SELECT VEHICULE_LABEL, VEHICULE_LCDV6_CONFIG, VEHICULE_LCDV6_MANUAL
            FROM #pref#_vehicule
            WHERE SITE_ID=:SITE_ID
            AND LANGUE_ID=:LANGUE_ID";

        $Values = $oConnection->queryTab($SQL, $aBind);
        $aVehicule = array();

        foreach ($Values as $OneValue) {
            // Récupération du code LCDV6. Si on dispose du code manuel, on l'utilise à la place du code auto
            $key = $OneValue['VEHICULE_LCDV6_CONFIG'];
            if (!empty($OneValue['VEHICULE_LCDV6_MANUAL'])) {
                $key = $OneValue['VEHICULE_LCDV6_MANUAL'];
            }

            $aVehicule[$key] = $OneValue['VEHICULE_LABEL'];
        }
        unset($Values);

        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);

        // Code couleur
        $input = $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('OUTIL_CODE_COULEUR_ON'), 255, "", false, $controller->zoneValues["ZONE_TITRE3"], $controller->readO, 10, true);
        $return .= '<tr style="display:none;" class="outil-code-couleur outil-code-couleur-on"><td class="formlib">'.t('OUTIL_CODE_COULEUR_ON').'</td><td class="formval">'.$input.' (ex: A6A6A6)</td></tr>';
        $input = $controller->oForm->createInput($controller->multi."ZONE_TITRE4", t('OUTIL_CODE_COULEUR_OFF'), 255, "", false, $controller->zoneValues["ZONE_TITRE4"], $controller->readO, 10, true);
        $return .= '<tr style="display:none;" class="outil-code-couleur outil-code-couleur-off"><td class="formlib">'.t('OUTIL_CODE_COULEUR_OFF').'</td><td class="formval">'.$input.' (ex: A6A6A6)</td></tr>';

        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        $return .= Backoffice_Form_Helper::getOutils($controller, true, true, 3, 5, false);

        $return .= $controller->oForm->createComboFromList($controller->multi."ZONE_TITRE2", t("VEHICULE"), $aVehicule, $controller->zoneValues["ZONE_TITRE2"], false, $controller->readO);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveOutils();
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        Pelican_Cache::clean("Frontend/Citroen/VehiculeOutil");
    }
}
