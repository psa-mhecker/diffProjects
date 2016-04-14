<?php
/**
 */

/**
 * Fichier de Pelican_Cache : liste de Contenus.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 16/12/2004
 */
class Frontend_Content_Page extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":PAGE_ID"] = $this->params[0];
        $aBind[":SITE_ID"] = $this->params[1];
        $aBind[":LANGUE_ID"] = $this->params[2];
        $aBind[":MYDATE"] = $oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP')));

        if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }

        $home = $this->params[4];

        $contentFilter = "";
        if ($this->params[5]) {
            $sTypeContent = $this->params[5];
            $aBind[":CONTENT_TYPE_ID"] = $sTypeContent;
            $contentFilter = "AND po.PAGE_ORDER_TYPE=c.CONTENT_TYPE_ID";
        }
        if ($this->params[6]) {
            $limit = $this->params[6];
        }

        $strSql = "SELECT c.CONTENT_ID,
				cv.CONTENT_TITLE,
				cv.CONTENT_TITLE_BO,
				cv.CONTENT_SUBTITLE,
				cv.CONTENT_CLEAR_URL,
				CONTENT_SHORTTEXT,
				CONTENT_TEXT,
				MEDIA_PATH,
				MEDIA_ALT,
				CONTENT_DISPLAY_AUTHOR,
				CONTENT_DISPLAY_COMMENT,
				CONTENT_DISPLAY_DATE,
				CONTENT_DISPLAY_PDF,
				CONTENT_DISPLAY_PRINT,
				CONTENT_DISPLAY_SEND,
				CONTENT_DISPLAY_TAGS,
				".$oConnection->dateSqlToString("cv.CONTENT_PUBLICATION_DATE ", false)." as CONTENT_PUBLICATION_DATE,
				".$oConnection->dateSqlToString("cv.CONTENT_DATE ", false)." as CONTENT_DATE,
				".$oConnection->dateSqlToString("cv.CONTENT_START_DATE ", false)." as CONTENT_START_DATE,
				".$oConnection->dateSqlToString("cv.CONTENT_END_DATE ", false)." as CONTENT_END_DATE
				FROM #pref#_content c
				inner join #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.CONTENT_".$type_version."_VERSION=cv.CONTENT_VERSION AND c.LANGUE_ID=cv.LANGUE_ID)
				left join #pref#_page_order po on (po.PAGE_ID=:PAGE_ID ".$contentFilter." AND po.LANGUE_ID = c.LANGUE_ID AND po.PAGE_ORDER_ID=c.CONTENT_ID)
				left join #pref#_media m on (m.MEDIA_ID=cv.MEDIA_ID)";
        if (isset($sTypeContent)) {
            $strSql .= " inner join #pref#_content_type ct on (ct.CONTENT_TYPE_ID=c.CONTENT_TYPE_ID)";
        }
        $strSql .= "
				WHERE
				c.SITE_ID = :SITE_ID
				AND c.LANGUE_ID = :LANGUE_ID
				AND CONTENT_STATUS=1
				AND ".$oConnection->getNVLClause("cv.CONTENT_START_DATE", $oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'), mktime(0, 0, 0, 1, 1, 1970))))." <= :MYDATE
				AND ".$oConnection->getNVLClause("cv.CONTENT_END_DATE", $oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'), mktime(0, 0, 0, 1, 1, 2038))))." >= :MYDATE";

        if ($home) {
            $strSql .= " AND CONTENT_DIRECT_HOME=1 ";
        } else {
            $strSql .= " AND CONTENT_DIRECT_PAGE=1 AND cv.PAGE_ID = :PAGE_ID ";
        }

        if (isset($sTypeContent)) {
            $strSql .= " AND ct.CONTENT_TYPE_ID = :CONTENT_TYPE_ID";
        }
        $strSql .= "ORDER BY PAGE_ORDER, cv.CONTENT_PUBLICATION_DATE DESC, c.CONTENT_ID DESC";
        if (isset($limit)) {
            $strSql = $oConnection->getLimitedSql($strSql, 1, $limit, true, $aBind);
        }

        $result = $oConnection->getTab($strSql, $aBind);

        $this->value = $result;
    }
}
