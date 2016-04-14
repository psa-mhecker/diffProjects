<?php

class Cms_Page_Module_List extends Cms_Page_Module
{
    public static function render(Pelican_Controller $controller)
    {
        $val = array();
        $ids = $controller->zoneValues ["ZONE_PARAMETERS"];
        if ($ids) {
            $oConnection = Pelican_Db::getInstance();
            $content = $oConnection->queryTab('select c.CONTENT_ID, CONTENT_TITLE from #pref#_content c inner join #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID and c.CONTENT_CURRENT_VERSION=cv.CONTENT_VERSION and c.LANGUE_ID=cv.LANGUE_ID)
			where c.CONTENT_ID in ('.$ids.')');
            if ($content) {
                foreach ($content as $values) {
                    $val [$values ['CONTENT_ID']] = $values ['CONTENT_TITLE'];
                }
            }
            $ordre = explode(',', $ids);
            foreach ($ordre as $o) {
                $value[$o] = $val[$o];
            }
        }
        $return = $controller->oForm->createContentFromList($controller->multi."ZONE_PARAMETERS", "Contenus", $value, false, $controller->readO, "10", 300, false, false, "8", true);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();

        Pelican_Db::$values ['ZONE_PARAMETERS'] = implode(",", Pelican_Db::$values ['ZONE_PARAMETERS']);

        parent::save();
    }
}
