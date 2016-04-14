<?php

class Cms_Content_Common_Param extends Cms_Content_Module
{
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createTextArea("CONTENT_META_TITLE", t('Meta title'), false, $controller->values["CONTENT_META_TITLE"], 255, $controller->readO, 2, 100, false, "", false);
        $return .= $controller->oForm->createTextArea("CONTENT_META_KEYWORD", t('Meta keywords'), false, $controller->values["CONTENT_META_KEYWORD"], 255, $controller->readO, 2, 100, false, "", false);
        $return .= $controller->oForm->createTextArea("CONTENT_META_DESC", t('Meta description'), false, $controller->values["CONTENT_META_DESC"], 16000, $controller->readO, 5, 100, false, "", false);

        return $return;
    }
}
