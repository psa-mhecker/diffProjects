<?php
/**
 * Fichier de Pelican_Cache : MenuForfait.
 *
 * @param 0 LANGUE_ID       langue du site
 * @param 1 PAGE_ID         pid de la page
 * @param 2 CONTENT_ZONE_MULTI_TYPE       Type du contenu
 */
class Frontend_Citroen_MenuForfait extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet a mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[':LANGUE_ID'] = $this->params[0];
        $aBind[':PAGE_ID'] = $this->params[1];

        $sSQL = "
            SELECT
                cv.CONTENT_ID,
                cv.MEDIA_ID7 as PICTO,
                cv.CONTENT_CLEAR_URL,
                cv.CONTENT_TITLE as CONTENT_TITLE_BO,
                cv.CONTENT_START_DATE,
                cv.CONTENT_END_DATE,
                cv.CONTENT_DIRECT_HOME,
                cv.CONTENT_DIRECT_PAGE
            FROM #pref#_content_version cv
            WHERE
            cv.LANGUE_ID = :LANGUE_ID
            AND
            cv.PAGE_ID = :PAGE_ID
            AND
            cv.CONTENT_VERSION = (SELECT MAX(CONTENT_VERSION) FROM #pref#_content_version WHERE CONTENT_ID = cv.CONTENT_ID)
            AND
            cv.STATE_ID	= 4
            GROUP BY cv.CONTENT_ID";

        $this->value = $oConnection->queryTab($sSQL, $aBind);
    }
}
