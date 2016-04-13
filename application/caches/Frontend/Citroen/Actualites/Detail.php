<?php
/**
 * Fichier de Pelican_Cache : Actualités détail
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Actualites_Detail extends Pelican_Cache {

    var $duration = HOUR;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':CONTENT_ID'] = $this->params[0];
        $aBind[':SITE_ID'] = $this->params[1];
        $aBind[':LANGUE_ID'] = $this->params[2];
		if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }
        $aBind[':CONTENT_STATUS'] = 1;
        $aBind[':STATE_ID'] = 4;
		$sSQL = "
			SELECT
				c.*,
				cv.*,
				m.*,
				DATE_FORMAT(cv.CONTENT_DATE2, '%d/%m/%Y') as DATE_FR,
				DATE_FORMAT(cv.CONTENT_DATE2, '%m/%d/%Y') as DATE_UK,
				DATE_FORMAT(cv.CONTENT_DATE2, '%a-%d-%b-%Y') as DATE_LETTER,
				DATE_FORMAT(cv.CONTENT_DATE2, '%Y-%m-%d') as DATE_TIME_HTML
			FROM 
				#pref#_content c
				INNER JOIN #pref#_content_version cv
					ON (c.CONTENT_ID = cv.CONTENT_ID
						AND c.CONTENT_".$type_version."_VERSION = cv.CONTENT_VERSION
						AND c.LANGUE_ID = cv.LANGUE_ID
					)
				LEFT JOIN #pref#_media m
					ON (cv.MEDIA_ID = m.MEDIA_ID)
			WHERE 
				c.CONTENT_ID = :CONTENT_ID
				AND c.SITE_ID = :SITE_ID
				AND c.LANGUE_ID = :LANGUE_ID
				AND c.CONTENT_STATUS = :CONTENT_STATUS
				AND cv.CONTENT_DATE2 < now()
                                AND IF(cv.CONTENT_START_DATE IS NULL, FALSE, cv.CONTENT_START_DATE < now())
                                AND IF(cv.CONTENT_END_DATE IS NULL, TRUE, cv.CONTENT_END_DATE > now()) ";
		$aContent = $oConnection->queryRow($sSQL, $aBind);		
		$this->value = $aContent;
    }
}