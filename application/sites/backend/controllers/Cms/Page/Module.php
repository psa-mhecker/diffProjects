<?php

class Cms_Page_Module
{

public static $decacheBack;
public static $decachePublication;

    public static function addCache(Pelican_Controller $controller)
    {
        if (isset(self::$decacheBack)) {
            if (!isset($controller->decacheBack)) {
                $controller->decacheBack = self::$decacheBack;
            } else {
                $controller->decacheBack = array_merge($controller->decacheBack, self::$decacheBack);
            }
        }
        if (isset(self::$decachePublication)) {
            if (!isset($controller->decachePublication)) {
                $controller->decachePublication = self::$decachePublication;
            } else {
                $controller->decachePublication = array_merge($controller->decachePublication, self::$decachePublication);
            }
        }
    }

    public static function render(Pelican_Controller $controller) {

    }

    public static function save(Pelican_Controller $controller = null)
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        /** Paramètres par défaut */
        if (!Pelican_Db::$values["MEDIA_FORMAT_ID"]) {
            Pelican_Db::$values["MEDIA_FORMAT_ID"] = 1;
        }
        
        if (is_array(Pelican_Db::$values["CONTENT_ID"])) {
            Pelican_Db::$values["CONTENT_ID"] = Pelican_Db::$values["CONTENT_ID"][0];
        }
        
        $DBVALUES_SAVE = Pelican_Db::$values;
        /** Mise à jour de page_zone */
        /* if (Pelican_Db::$values["MEDIA_ID".$i]) {
	Pelican_Db::$values["MEDIA_ID"] = Pelican_Db::$values["MEDIA_ID".$i];
	Pelican_Db::$values["PAGE_ZONE_MEDIA_LABEL"] = Pelican_Db::$values["MEDIA_LIB".$i];
	}*/
        /* minisite */
        Pelican_Db::$values['ZONE_MS_JOB_ID'] = Pelican_Db::$values['ZONE_ID'];
        /* minisite */
        Pelican_Db::$values["MEDIA_PATH"] = Pelican_Media::getMediaPath(Pelican_Db::$values["MEDIA_ID"]);
        if (Pelican_Db::$values["MEDIA_ID2"]) {
            Pelican_Db::$values["MEDIA_PATH2"] = Pelican_Media::getMediaPath(Pelican_Db::$values["MEDIA_ID2"]);
        }
        
        if (Pelican_Db::$values['ZONE_DYNAMIQUE']) {
            $oConnection->insertQuery("#pref#_page_multi_zone");
        } else {
            $oConnection->insertQuery("#pref#_page_zone");
        }
        
        /** Mise à jour de page_zone_content */
        Pelican_Db::$values = $DBVALUES_SAVE;
        for ($i = 0; $i < sizeOf(Pelican_Db::$values["CONTENU"]); $i ++) {
            Pelican_Db::$values["CONTENT_ID"] = Pelican_Db::$values["CONTENU"][$i];
            Pelican_Db::$values["PAGE_ID"] = Pelican_Db::$values["PAGE_ID"];
            Pelican_Db::$values["ZONE_TEMPLATE_ID"] = Pelican_Db::$values["ZONE_TEMPLATE_ID"];
            Pelican_Db::$values["PAGE_VERSION"] = Pelican_Db::$values["PAGE_VERSION"];
            Pelican_Db::$values['LANGUE_ID'] = Pelican_Db::$values['LANGUE_ID'];
            Pelican_Db::$values["CONTENT_ID"] = Pelican_Db::$values["CONTENU"][$i];
            Pelican_Db::$values["COMPTEUR"] = $i;
            if (Pelican_Db::$values['ZONE_DYNAMIQUE']) {
                $oConnection->insertQuery("#pref#_page_multi_zone_content");
            } else {
                $oConnection->insertQuery("#pref#_page_zone_content");
            }
        }
        
        /** Mise à jour de page_zone_media */
        Pelican_Db::$values = $DBVALUES_SAVE;
        for ($i = 0; $i < 3; $i ++) {
            if (Pelican_Db::$values["MEDIA_ID" . $i]) {
                Pelican_Db::$values["MEDIA_ID"] = Pelican_Db::$values["MEDIA_ID" . $i];
                Pelican_Db::$values["PAGE_ZONE_MEDIA_LABEL"] = Pelican_Db::$values["MEDIA_LIB" . $i];
                if (Pelican_Db::$values['ZONE_DYNAMIQUE']) {
                    $oConnection->insertQuery("#pref#_page_multi_zone_media");
                } else {
                    $oConnection->insertQuery("#pref#_page_zone_media");
                }
            }
        }
        
        Pelican_Db::$values = $DBVALUES_SAVE;
    }
}