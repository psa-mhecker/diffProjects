<?php

class Cms_Content_Common_Zone extends Cms_Content_Module
{

    public static function render (Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();
        
        $aBind = array();
        $aBind[":CONTENT_ID"] = $controller->values["CONTENT_ID"];
        $aBind[":CONTENT_VERSION"] = $controller->values["CONTENT_VERSION"];
        $aBind[":LANGUE_ID"] = $controller->values['LANGUE_ID'];
        
        $controller->oForm->_aIncludes["suggest"] = true;
        $controller->oForm->suggest["void"] = array(
            "1"
        );
        $return .= $controller->oForm->createHidden("void", "");
        
        if ($controller->contentTypeId != Pelican::$config["CNT_TYPE_DOSSIER_PRESSE"]) {
            /** encadré haut */
            $return .= $controller->oForm->showSeparator();
            $return .= $controller->oForm->createLabel(" ", "<span class=\"content_bloc\">Image(s) / Encadré(s) haut</span>");
            $controller->multi = "haut";
            $aBind[":CONTENT_ZONE_TYPE"] = $oConnection->strToBind($controller->multi);
            $SQL = "
			SELECT *
			FROM #pref#_content_zone
			WHERE
			CONTENT_ID =:CONTENT_ID
			AND CONTENT_VERSION =:CONTENT_VERSION
			AND LANGUE_ID =:LANGUE_ID
			AND CONTENT_ZONE_TYPE = :CONTENT_ZONE_TYPE
			ORDER BY CONTENT_ZONE_ID";
            $controller->multiValues = $oConnection->queryTab($SQL, $aBind);
            $return .= $controller->oForm->createMultiHmvc($oConnection, "content_zone" . $controller->multi, "content_zone", Pelican::$config["CONTROLLERS_ROOT"] . "/Content/multi/multi_encadre.php", $controller->multiValues, "CONTENT_ZONE_ID", $controller->readO, 2, true, true, $controller->multi, "values", "multi", "4", "Ajouter un encadré");
        }
        
        return $return;
    }
}