<?php
/**
 */

/**
 * Fichier de Pelican_Cache : liste des navigations pour une zone id.
 *
 * @author Renaud Delcoigne <renaud.delcoigne@businessdecision.com>
 *
 * @since 01/08/2011
 */
class Frontend_NavigationByZoneId extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":SITE_ID"] = $this->params[0];
        $aBind[":PAGE_ID"] = $this->params[1];
        $aBind[":LANGUE_ID"] = $this->params[2];
        $aBind[":ZONE_ID"] = $this->params[3];

        if ($this->params[4]) {
            $type_version = $this->params[4];
        } else {
            $type_version = "CURRENT";
        }

        $strSql = "
			SELECT
				zt.ZONE_TEMPLATE_ID,
				pz.ZONE_TITRE,
				n.NAVIGATION_TITLE,
				n.NAVIGATION_URL,
				n.NAVIGATION_BOLD
				FROM #pref#_zone_template zt
				LEFT JOIN #pref#_navigation n on (zt.ZONE_TEMPLATE_ID = n.ZONE_TEMPLATE_ID)
				LEFT JOIN #pref#_page p ON (p.PAGE_ID = n.PAGE_ID AND p.PAGE_".$type_version."_VERSION = n.PAGE_VERSION)
				LEFT JOIN #pref#_page_zone pz ON (pz.PAGE_ID = n.PAGE_ID AND p.PAGE_".$type_version."_VERSION = pz.PAGE_VERSION AND pz.ZONE_TEMPLATE_ID = n.ZONE_TEMPLATE_ID)
				WHERE zt.ZONE_ID = :ZONE_ID
				AND p.SITE_ID = :SITE_ID
				AND n.LANGUE_ID = :LANGUE_ID
				AND n.PAGE_ID = :PAGE_ID
				ORDER BY ZONE_TEMPLATE_ORDER, NAVIGATION_ORDER";

        $result = $oConnection->queryTab($strSql, $aBind);

        $blocs = array();
        $navigations = array();

        if (is_array($result)) {
            $lastBloc = -1;

            $nav = array();

            foreach ($result as $content) {
                if ($lastBloc == -1) {
                    $lastBloc = array("ZONE_TEMPLATE_ID" => $content["ZONE_TEMPLATE_ID"], "ZONE_TITRE" => $content["ZONE_TITRE"]);
                }

                if ($lastBloc["ZONE_TEMPLATE_ID"] != $content["ZONE_TEMPLATE_ID"]) {
                    $blocs[] = $lastBloc;
                    $lastBloc = array("ZONE_TEMPLATE_ID" => $content["ZONE_TEMPLATE_ID"], "ZONE_TITRE" => $content["ZONE_TITRE"]);
                    $navigations[] = $nav;
                    $nav = array();
                }
                $nav[] = $content;
            }

            $blocs[] = $lastBloc;
            $navigations[] = $nav;
        }

        $this->value = array("blocs" => $blocs, "navigations" => $navigations);
    }
}
