<?php

/**
 * Tranche PN18 - Iframe
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Moaté David <david.moate@businessdecision>
 * @since 25/05/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
class Cms_Page_Ndp_Pn18IFrame extends Cms_Page_Ndp
{
    public static function render(Pelican_Controller $controller)
    {
        $form = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        
        $form .= $controller->oForm->createInput($controller->multi."ZONE_TITRE",
            t('TITLE'),
            60,
            "",
            true,
            $controller->zoneValues['ZONE_TITRE'],
            $controller->readO,
            75, false, '', 'text', [], false, '', "60 ".t('NDP_MAX_CAR'));
        
        $form .= $controller->oForm->createEditor($controller->multi.'ZONE_TEXTE',
            t('NDP_DESCRIPTION'),
            false,
            $controller->zoneValues['ZONE_TEXTE'],
            $controller->readO,
            true,
            "",
            650,
            150);
        
        $form .= $controller->oForm->createLabel('',
            t('NDP_MSG_IFRAME_DISPLAY_CONDITION'));
        
        $form .= $controller->oForm->showSeparator();

        $form .= $controller->oForm->createLabel('',
            t('NDP_IFRAME_WEB'));
        
        $form .= $controller->oForm->createInput($controller->multi.'ZONE_URL',
            t('URL'),
            255,
            "internallink",
            true,
            $controller->zoneValues['ZONE_URL'],
            $controller->readO,
            60);
        
        $optionsDesktop = ['isIcon' => true, 'message' => t('NDP_MSG_IFRAME_DESKTOP_HEIGHT')];
        $form .= $controller->oForm->createInput($controller->multi."ZONE_ATTRIBUT",
            t('NDP_FILTER_HEIGHT'),
            5,
            "",
            false,
            $controller->zoneValues['ZONE_ATTRIBUT'],
            $controller->readO,
            5, false, "", "text", array(), false, $optionsDesktop, self::getPx());
        $form .= $controller->oForm->showSeparator();
        
        $form .= $controller->oForm->createLabel('',
            t('NDP_IFRAME_MOBILE'));
        
        $form .= $controller->oForm->createInput($controller->multi.'ZONE_URL2',
            t('URL'),
            255,
            "internallink",
            false,
            $controller->zoneValues['ZONE_URL2'],
            $controller->readO,
            60);

        $optionsMobile = ['isIcon' => true, 'message' => t('NDP_MSG_IFRAME_MOBILE_HEIGHT')];
        $form .= $controller->oForm->createInput($controller->multi."ZONE_ATTRIBUT2",
            t('NDP_FILTER_HEIGHT'),
            5,
            "",
            false,
            $controller->zoneValues['ZONE_ATTRIBUT2'],
            $controller->readO,
            5, false, "", "text", array(), false, $optionsMobile, self::getPx());
        
        $form .= $controller->oForm->showSeparator();
        
        return $form;
    }

    /**
     *
     * @return array
     */
    public static function getPx()
    {
        
        return array('message' => 'px');
    }
}
