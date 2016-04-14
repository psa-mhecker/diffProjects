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
class Frontend_Content_List extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":SITE_ID"] = $this->params[0];
        $aBind[":LANGUE_ID"] = $this->params[1];
        $aBind[":CONTENT_TYPE_ID"] = $this->params[2];

        if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }

        $full = false;
        if ($this->params[2] == 'all') {
            $full = true;
        }

        $strSql = "SELECT c.CONTENT_ID,
				cv.CONTENT_TITLE,
				cv.CONTENT_TITLE_BO,
				cv.CONTENT_SUBTITLE,
				cv.CONTENT_CLEAR_URL,
				CONTENT_SHORTTEXT,
				".$oConnection->dateSqlToString("cv.CONTENT_PUBLICATION_DATE ", false)." as CONTENT_PUBLICATION_DATE,
				".$oConnection->dateSqlToString("cv.CONTENT_DATE ", false)." as CONTENT_DATE_PRESSE
				FROM #pref#_content c
				inner join #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.CONTENT_".$type_version."_VERSION=cv.CONTENT_VERSION AND c.LANGUE_ID=cv.LANGUE_ID)";
        $strSql .= "
				WHERE ";
        if (!$full) {
            $strSql .= " c.CONTENT_TYPE_ID = :CONTENT_TYPE_ID
				AND ";
        }
        $strSql .= " c.LANGUE_ID = :LANGUE_ID
				AND c.SITE_ID = :SITE_ID
				AND c.CONTENT_STATUS = 1
				ORDER BY cv.CONTENT_PUBLICATION_DATE, c.CONTENT_ID DESC";

        $result = $oConnection->getTab($strSql, $aBind);

        $this->value = $result;
    }
}
