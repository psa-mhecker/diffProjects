<?php
/**
 * @package Pelican_Cache
 * @subpackage Pelican
 */

/**
 * Fichier de Pelican_Cache : liste de Contenus
 *
 * @package Pelican_Cache
 * @subpackage Pelican
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 16/12/2004
 */
class Frontend_Content_Tag extends Pelican_Cache
{

    
    var $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue()
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        $aBind[":TERMS_NAME"] = $oConnection->strtoBind($this->params[0]);
        $aBind[":SITE_ID"] = $this->params[1];
        $aBind[":LANGUE_ID"] = $this->params[2];
        $aBind[":DATE"] = $oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP')));
        
        if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }
    
        if ($this->params[6]) {
            $limit = $this->params[6];
        }
        
        $sqlContent = "SELECT c.CONTENT_ID,
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
				" . $oConnection->dateSqlToString("cv.CONTENT_PUBLICATION_DATE ", false) . " as CONTENT_PUBLICATION_DATE,
				" . $oConnection->dateSqlToString("cv.CONTENT_DATE ", false) . " as CONTENT_DATE,
				" . $oConnection->dateSqlToString("cv.CONTENT_START_DATE ", false) . " as CONTENT_START_DATE,
				" . $oConnection->dateSqlToString("cv.CONTENT_END_DATE ", false) . " as CONTENT_END_DATE
				FROM #pref#_content c
				inner join #pref#_content_version as cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.CONTENT_" . $type_version . "_VERSION=cv.CONTENT_VERSION AND c.LANGUE_ID=cv.LANGUE_ID)
				inner join #pref#_terms as t on (t.TERMS_NAME=:TERMS_NAME)
				inner join #pref#_terms_relationships as tr on (tr.TERMS_ID=t.TERMS_ID and OBJECT_TYPE_ID=1 and tr.OBJECT_ID=c.CONTENT_ID)
				left join #pref#_media m on (m.MEDIA_ID=cv.MEDIA_ID)";
        $sqlContent .= "
				WHERE
				c.SITE_ID = :SITE_ID
				AND c.LANGUE_ID = :LANGUE_ID
				AND CONTENT_STATUS=1
				AND " . $oConnection->getNVLClause("cv.CONTENT_START_DATE", $oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'), mktime(0, 0, 0, 1, 1, 1970)))) . " <= :DATE
				AND " . $oConnection->getNVLClause("cv.CONTENT_END_DATE", $oConnection->dateStringToSql(date(t('DATE_FORMAT_PHP'), mktime(0, 0, 0, 1, 1, 2038)))) . " >= :DATE";
        $sqlContent .= "ORDER BY cv.CONTENT_PUBLICATION_DATE DESC, c.CONTENT_ID DESC";
        
        /*if (isset($limit)) {
            $strSql = $oConnection->getLimitedSql($strSql, 1, $limit, true, $aBind);
        }*/
        $strSql = 'select c.* from ('.$sqlContent.') c';

        $result = $oConnection->getTab($strSql, $aBind);
        
        $this->value = $result;
    }
}
?>