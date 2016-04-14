<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
/**
 * Classe d'administration de la tranche Menu principal de Mon projet.
 */
class Cms_Page_Citroen_MonProjet_MenuPrincipal extends Cms_Page_Citroen
{
    /**
     * Affichage du formulaire.
     *
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {
        $return .= $oController->oForm->createCheckBoxFromList($oController->multi."ZONE_PARAMETERS", t('ACTIVATION_BOUTON_CONSEIL'), array(1 => ""), ($oController->zoneValues['PAGE_ID'] == -2) ? 0 : $oController->zoneValues['ZONE_PARAMETERS'], false, $oController->readO);

        return $return;
    }
}
