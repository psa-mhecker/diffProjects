<?php
/**
 * Fichier de Pelican_Cache : Actualités pager.
 */
class Frontend_Citroen_Actualites_PageClearUrlByActu extends Pelican_Cache
{
    public $duration = HOUR;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_PARENT_ID'] = $this->params[0];
        $aBind[':SITE_ID'] = $this->params[1];
        $aBind[':LANGUE_ID'] = $this->params[2];
        $aBind[':PAGE_STATUS'] = 1;
        $aBind[':CONTENT_STATUS'] = 1;
        $aBind[':STATE_ID'] = 4;
        if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }
        $sSQL = "
			SELECT
				pv.PAGE_CLEAR_URL,
				pz.CONTENT_ID
			FROM
				#pref#_page p
				INNER JOIN #pref#_page_version pv
					ON (p.PAGE_ID = pv.PAGE_ID
						AND p.PAGE_".$type_version."_VERSION = pv.PAGE_VERSION
						AND p.LANGUE_ID = pv.LANGUE_ID
					)
				INNER JOIN #pref#_page_zone pz
					ON (p.PAGE_ID = pz.PAGE_ID
						AND p.PAGE_".$type_version."_VERSION = pz.PAGE_VERSION
						AND p.LANGUE_ID = pz.LANGUE_ID
					)

			WHERE
				p.PAGE_PARENT_ID = :PAGE_PARENT_ID
			AND p.SITE_ID = :SITE_ID
			AND p.LANGUE_ID = :LANGUE_ID
			AND p.PAGE_STATUS = :PAGE_STATUS
			AND pv.STATE_ID = :STATE_ID";
        $aResults = $oConnection->queryTab($sSQL, $aBind);
        $aReturn = array();
        if (is_array($aResults) && count($aResults)>0) {
            foreach ($aResults as $key => $result) {
                $aReturn[$result['CONTENT_ID']] = $result['PAGE_CLEAR_URL'];
            }
        }
        $this->value = $aReturn;
    }
}
