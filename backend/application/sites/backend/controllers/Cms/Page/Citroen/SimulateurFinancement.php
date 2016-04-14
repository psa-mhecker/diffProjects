<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
/**
 * Classe d'administration de la tranche Simulateur de financement.
 */
class Cms_Page_Citroen_SimulateurFinancement extends Cms_Page_Citroen
{
    /**
     * Affichage du formulaire.
     *
     * @param Pelican_Controller $oController
     */
    public static function render(Pelican_Controller $oController)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($oController, true, false);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($oController);

        //$oConnection->query('r');
        $return .= $oController->oForm->createInput($oController->multi."ZONE_TITRE", t('TITRE'), 255, "", false, $oController->zoneValues['ZONE_TITRE'], $oController->readO, 75);
        $return .= $oController->oForm->createInput($oController->multi."ZONE_TITRE2", t('SOUS_TITRE'), 255, "", false, $oController->zoneValues['ZONE_TITRE2'], $oController->readO, 75);
        $return .= $oController->oForm->createEditor($oController->multi."ZONE_TEXTE", t('Chapeau'), false, $oController->zoneValues["ZONE_TEXTE"], $oController->readO, true, "", 500, 150);

        $return .= $oController->oForm->createInput($oController->multi."ZONE_TITRE3", t('TITRE_ETAPE_1'), 255, "", false, $oController->zoneValues['ZONE_TITRE3'], $oController->readO, 75);
        $return .= $oController->oForm->createEditor($oController->multi."ZONE_TEXTE2", t('CHAPEAU_ETAPE_1'), false, $oController->zoneValues['ZONE_TEXTE2'], $oController->readO, true, "", 500, 150);
        $return .= $oController->oForm->createInput($oController->multi."ZONE_TITRE4", t('TITRE_ETAPE_2'), 255, "", false, $oController->zoneValues['ZONE_TITRE4'], $oController->readO, 75);

        $return .= $oController->oForm->createInput($oController->multi."ZONE_TEXTE5", t('HEIGHT_IFRAME'), 5, "", false, $oController->zoneValues['ZONE_TEXTE5'], $oController->readO, 75);
        $return .= $oController->oForm->createInput($oController->multi."ZONE_TEXTE6", t('WIDTH_IFRAME'), 5, "", false, $oController->zoneValues['ZONE_TEXTE6'], $oController->readO, 75);

        $return .= $oController->oForm->createComboFromList(
                        $oController->multi."ZONE_PARAMETERS",
                        t("UNIT_IFRAME"),
                        array(
                            'pixel' => t('PIXEL'),
                            'percent' => t('PERCENT'),
                            ),

                        $oController->zoneValues['ZONE_PARAMETERS'],
                        false,
                        $oController->readO
                        );

        return $return;
    }

    public static function save(Pelican_Controller $oController)
    {
        $oConnection = Pelican_Db::getInstance();
        //$oConnection->query('r');
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}
