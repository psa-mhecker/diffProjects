<?php
/**
 * @package Pelican_Cache
 * @subpackage Pelican
 */

require_once(pelican_path('Media'));

/**
 * Fichier de Pelican_Cache : Données associées à un contenu
 *
 * @package Pelican_Cache
 * @subpackage Pelican
 * @author Rim Karray <rim.karray@businessdecision.com>
 * @since 05/05/2015
 */
class Frontend_Content_ContentInfo extends Pelican_Cache
{

    
    var $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue()
    {
        
        
        $oConnection = Pelican_Db::getInstance();
        
        $aBind[":CONTENT_ID"] = $this->params[0];
        $aBind[":SITE_ID"] = $this->params[1];
        $aBind[":LANGUE_ID"] = $this->params[2];
        if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }
         
        /** donnees globales */
        $strSql = "
				SELECT c.*,
				cv.*,
				cc.*,
				m.MEDIA_PATH,
				m.MEDIA_ALT,
				ct.CONTENT_TYPE_ID as CONTENT_TYPE_ID,
				" . $oConnection->dateSqlToString("cv.CONTENT_PUBLICATION_DATE ", false) . " as CONTENT_PUBLICATION_DATE,
				" . $oConnection->dateSqlToString("cv.CONTENT_DATE ", false) . " as CONTENT_DATE,
				" . $oConnection->dateSqlToString("cv.CONTENT_START_DATE ", false) . " as CONTENT_START_DATE,
				" . $oConnection->dateSqlToString("cv.CONTENT_END_DATE ", false) . " as CONTENT_END_DATE
				FROM #pref#_content c
				INNER JOIN #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.CONTENT_" . $type_version . "_VERSION=cv.CONTENT_VERSION AND c.LANGUE_ID=cv.LANGUE_ID)
				INNER JOIN #pref#_content_type ct on (c.CONTENT_TYPE_ID=ct.CONTENT_TYPE_ID)
				LEFT JOIN #pref#_content_category cc on (cv.CONTENT_CATEGORY_ID=cc.CONTENT_CATEGORY_ID)
				LEFT JOIN #pref#_media m on (cv.MEDIA_ID = m.MEDIA_ID)
				
				WHERE
				cv.CONTENT_ID = :CONTENT_ID
				AND c.SITE_ID = :SITE_ID
				AND c.LANGUE_ID = :LANGUE_ID
			
				";
        $result = $oConnection->queryRow($strSql, $aBind);
        
        $this->value = $result;
    }
}
?>