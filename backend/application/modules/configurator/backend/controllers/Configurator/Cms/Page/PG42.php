<?php

include_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Cms/Page/Module.php';
class Configurator_Cms_Page_PG42 extends Cms_Page_Module
{
    /**
     * Affichage des controles de saisie du bloc.
     *
     * @param Pelican_Controller $controller
     */
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createHidden($controller->multi.'PLUGIN_ID', 'configurator');
        $return .= $controller->oForm->createDescription(t('SELECTIONNEUR'));
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi.'ZONE_WEB', t('AFFICHAGE_WEB'), array(1 => ''), $controller->zoneValues['ZONE_WEB'], $required, $controller->readO);

        $choices = array(
            1 => t('DESACTIVER'),
            2 => t('ACTIVER'),
        );

        $return .= $controller->oForm->createDescription(t('DESIGN EXTERIEUR'));

        $return .= $controller->oForm->createLabel(t('SELECTEUR_DE_COULEUR'), $controller->values['CONTENT_ID']);
        $return .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_CRITERIA_ID', t('AFFICHAGE_DU_SELECTEUR'), $choices, empty($controller->zoneValues['ZONE_CRITERIA_ID']) ? '1' : $controller->zoneValues['ZONE_CRITERIA_ID'], true, $controller->readO);

        $return .= $controller->oForm->createLabel(t('SELECTEUR_DE_JANTE'), $controller->values['CONTENT_ID']);
        $return .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_CRITERIA_ID2', t('AFFICHAGE_DU_SELECTEUR'), $choices, empty($controller->zoneValues['ZONE_CRITERIA_ID2']) ? '1' : $controller->zoneValues['ZONE_CRITERIA_ID2'], true, $controller->readO);

        $return .= $controller->oForm->createDescription(t('DESIGN INTERIEUR'));

        $return .= $controller->oForm->createLabel(t('SELECTEUR_DE_GARNISSAGE'), $controller->values['CONTENT_ID']);
        $return .= $controller->oForm->createRadioFromList($controller->multi.'ZONE_CRITERIA_ID3', t('AFFICHAGE_DU_SELECTEUR'), $choices, empty($controller->zoneValues['ZONE_CRITERIA_ID3']) ? '1' : $controller->zoneValues['ZONE_CRITERIA_ID3'], true, $controller->readO);

        return $return;
    }
    /**
     * Enregistrement des parametres du bloc.
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        parent::save($controller);
    }
}
