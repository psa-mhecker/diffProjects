<?php

/**
 * Tranche - Engagement
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Kevin Vignon <kevin.vignon@businessdecision.com>
 * @since 05/03/2015
 */
class Cms_Content_Ndp_Pt19Engagements extends Cms_Content_Module
{
    public static $decacheBackOrchestra = array (
    );
    
    public static $decachePublicationOrchestra = array (
    );
    
    public static function render(Pelican_Controller $controller)
    {

        $return = '';

        $return .= $controller->oForm->createMedia(
            'MEDIA_ID',
            t("NDP_VISUEL_16_9"),
            false,
            "image",
            "",
            $controller->values['MEDIA_ID'],
            (Cms_Page_Ndp::isTranslator() || $controller->readO),
            true,
            false,
            'NDP_RATIO_16_9:425x239'
        );

        $return .= $controller->oForm->createInput(
            "CONTENT_TITLE2",
            t('TITLE'),
            50,
            "",
            true,
            $controller->values["CONTENT_TITLE2"],
            $controller->read0,
            100
        );

        $return .= $controller->oForm->createTextArea(
            'CONTENT_TEXT',
            t('NDP_DESCRIPTION'),
            true,
            $controller->values['CONTENT_TEXT'],
            1000,
            $controller->readO,
            2,
            100,
            false,
            '',
            true
        );

        $return .= $controller->oForm->createInput(
            "CONTENT_URL",
            t('NDP_URL_CTA_AND_VISUEL'),
            "",
            "internallink",
            true,
            $controller->values["CONTENT_URL"],
            $controller->read0,
            100
        );

        $targetsModeOuverture = array(
            '_self' => t('NDP_SELF'),
            '_blank' => t('NDP_BLANK')
        );

        if (empty($controller->values['CONTENT_CODE']))
        {
            $controller->values['CONTENT_CODE'] = '_self';
        }
        $return .= $controller->oForm->createRadioFromList(
            'CONTENT_CODE',
            t('NDP_MODE_OUVERTURE'),
            $targetsModeOuverture,
            $controller->values['CONTENT_CODE'],
            true,
            false,
            'h'
        );

        return $return;
    }

}
