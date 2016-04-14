<?php
/**
 */
include_once pelican_path('Media');

/**
 * Fichier de Pelican_Cache : Données associées à une page.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 13/01/2006
 */
class Frontend_Citroen_PageByMultiZone extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[":SITE_ID"] = $this->params[0];
        $aBind[":LANGUE_ID"] = $this->params[1];
        if ($this->params[3]) {
            $type_version = $this->params[2];
        } else {
            $type_version = "CURRENT";
        }
        if ($type_version == "CURRENT") {
            $status = " AND PAGE_STATUS = 1 AND pv.STATE_ID = 4";
        }
        $aBind[":ZONE_ID"] = $this->params[3];
        $aBind[":TEMPLATE_PAGE_ID"] = $this->params[4];

        $sSQL = "
				SELECT
				p.PAGE_ID,
				p.LANGUE_ID,
				p.PAGE_PARENT_ID,
				p.SITE_ID,
				p.PAGE_ORDER,
				p.PAGE_PATH,
				p.PAGE_LIBPATH,
				pv.PAGE_VERSION,
				pv.TEMPLATE_PAGE_ID,
				pv.MEDIA_ID,
				pv.PAGE_TEXT,
				pv.PAGE_TITLE_BO,
				pv.PUB_ID,
				pv.PAGE_CLEAR_URL,
				pv.PAGE_PICTO_URL,
				pv.PAGE_META_TITLE,
				pv.PAGE_META_DESC,
				pv.PAGE_META_KEYWORD,
                pv.PAGE_META_ROBOTS,
				pv.PAGE_START_DATE,
				pv.PAGE_END_DATE,
				pv.PAGE_PUBLICATION_DATE,
				pv.PAGE_AUTHOR,
				pv.PAGE_VERSION_CREATION_DATE,
				pv.PAGE_VERSION_CREATION_USER,
				pv.PAGE_VERSION_UPDATE_DATE,
				pv.PAGE_VERSION_UPDATE_USER,
				pv.PAGE_KEYWORD,
				pv.PAGE_DISPLAY,
				pv.PAGE_DISPLAY_NAV,
				pv.PAGE_DISPLAY_SEARCH,
				pv.PAGE_TITLE,
                pv.PAGE_MODE_AFFICHAGE,
                pv.PAGE_URL_EXTERNE,
                pv.PAGE_URL_EXTERNE_MODE_OUVERTURE,
				tp.*,
				m.MEDIA_PATH,m.MEDIA_WIDTH,m.MEDIA_HEIGHT,m.MEDIA_ALT
				FROM #pref#_page p
				INNER JOIN #pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND p.PAGE_".$type_version."_VERSION=pv.PAGE_VERSION)
				INNER JOIN #pref#_template_page tp on (pv.TEMPLATE_PAGE_ID=tp.TEMPLATE_PAGE_ID)
				INNER JOIN #pref#_page_multi_zone pmz on (pmz.PAGE_ID=pv.PAGE_ID AND pmz.LANGUE_ID = pv.LANGUE_ID AND pmz.PAGE_VERSION=pv.PAGE_VERSION)
				LEFT JOIN #pref#_media m on (m.MEDIA_ID=pv.MEDIA_ID)
				WHERE
				p.SITE_ID = :SITE_ID
				AND pmz.ZONE_ID = :ZONE_ID
				AND tp.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
				AND p.LANGUE_ID = :LANGUE_ID".$status." ORDER BY IF(ISNULL(p.PAGE_ORDER),1,0),p.PAGE_ORDER;";

        $return = $oConnection->queryRow($sSQL, $aBind);
        $return["PAGE_CLEAR_URL"] = ($return["PAGE_CLEAR_URL"] ? $return["PAGE_CLEAR_URL"] : makeClearUrl($return["PAGE_ID"], "pid", $return["PAGE_TITLE_BO"]));
        $return["MEDIA_PATH_v"] = Pelican_Media::getFileNameMediaFormat($return["MEDIA_PATH"], 14);
        $return["MEDIA_WIDTH_v"] = 90;
        $return["MEDIA_PATH_h"] = Pelican_Media::getFileNameMediaFormat($return["MEDIA_PATH"], 15);
        $return["MEDIA_WIDTH_h"] = 150;
        $this->value = $return;
    }
}
