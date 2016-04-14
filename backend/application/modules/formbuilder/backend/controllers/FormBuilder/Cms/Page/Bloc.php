<?php

class FormBuilder_Cms_Page_Bloc extends Cms_Page_Module
{
    public static function render(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();
        $sql = "SELECT f.FORMBUILDER_ID as ID, ".$oConnection->getConcatClause(array(
            "f.FORMBUILDER_LABEL",
            "' ('",
            "langue_translate",
            "')'",
        ))." as LIB
            FROM #pref#_formbuilder f
            INNER JOIN #pref#_language l on (f.LANGUE_ID=l.LANGUE_ID)
            WHERE f.SITE_ID=".$_SESSION[APP]['SITE_ID']."
            ORDER BY f.FORMBUILDER_LABEL";

        $return = $controller->oForm->createComboFromSql('', $controller->multi.'ZONE_TEXTE', t('FORMBUILDER_FORMCHOICE'), $sql, $controller->zoneValues['ZONE_TEXTE'], true, $controller->readO);

        return $return;
    }
}
