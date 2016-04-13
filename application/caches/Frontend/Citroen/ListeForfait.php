<?php
/**
 * Fichier de Pelican_Cache : ListeForfait
 * @package Cache
 * @param 0 LANGUE_ID       langue du site
 * @param 1 CONTENT_ZONE_MULTI_TYPE       Type du contenu 
 * @param 2 CONTENT_ID       ID du contenu 
 * @param 3 PAGE_ID         pid de la page 
 * @subpackage Pelican
 */
class Frontend_Citroen_ListeForfait extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet a mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        
        $aBind[':LANGUE_ID'] = $this->params[0];
        $aBind[':CONTENT_ZONE_MULTI_TYPE'] = $oConnection->strToBind($this->params[1]);
        $aBind[':CONTENT_ID'] = $this->params[2];
        $aBind[':PAGE_ID'] = $this->params[3];
        
        $sSQL = "
             SELECT 
                 cv.CONTENT_ID as ID,
                 cv.CONTENT_VERSION as LastVersion,               
                 cv.CONTENT_CLEAR_URL as CLEAN_URL,
                 cv.CONTENT_TITLE_BO as TITRE,
                 cv.CONTENT_TEXT,
                 cv.CONTENT_TITLE2,
                 cv.CONTENT_TITLE3,
                 cv.CONTENT_TITLE4,
                 cv.CONTENT_TITLE5,
                 cv.CONTENT_TITLE6,
                 cv.CONTENT_TITLE7,
                 cv.CONTENT_TITLE8,
                 cv.CONTENT_CODE,
                 cv.CONTENT_CODE2,
                 cv.CONTENT_CODE3,
                 cv.MEDIA_ID,
                 cv.MEDIA_ID2,
                 cv.MEDIA_ID3,
                 cv.MEDIA_ID4,
                 cv.MEDIA_ID6,
                 cv.MEDIA_ID5,
                 cv.MEDIA_ID7,
                 cv.MEDIA_ID8,
                 cv.CONTENT_SUBTITLE,
                 cv.MEDIA_ID as MEDIA_VIDEO
             FROM #pref#_content_version cv
             WHERE
             cv.CONTENT_ID = :CONTENT_ID 
             AND
             cv.LANGUE_ID = :LANGUE_ID
             AND
             cv.PAGE_ID = :PAGE_ID
             AND
             cv.CONTENT_VERSION = (SELECT MAX(CONTENT_VERSION) FROM #pref#_content_version WHERE CONTENT_ID = :CONTENT_ID)
             ";
        
        $this->value = $oConnection->queryTab($sSQL, $aBind);   
    }
}
?>