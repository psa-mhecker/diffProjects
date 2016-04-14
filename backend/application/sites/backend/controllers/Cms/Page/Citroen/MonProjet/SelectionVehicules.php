<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
/**
 * Classe d'administration de la tranche Sélection de véhicules de Mon projet.
 */
class Cms_Page_Citroen_MonProjet_SelectionVehicules extends Cms_Page_Citroen
{
    /**
     * Affichage du formulaire.
     *
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {
        $return .= $oController->oForm->createTextArea(
            $oController->multi."ZONE_TEXTE",
             t('TEXTE_INTRO'),
              true,
               $oController->zoneValues['ZONE_TEXTE'],
                "",
                 $oController->readO,
                  5,
                   75
                   );

        return $return;
    }
}
