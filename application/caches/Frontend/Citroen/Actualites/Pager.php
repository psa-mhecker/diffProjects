<?php
/**
 * Fichier de Pelican_Cache : Actualités pager
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Actualites_Pager extends Pelican_Cache {

    var $duration = HOUR;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
		$iCurrent =  $this->params[0];
        $aBind[':PAGE_PARENT_ID'] = $this->params[1];
        $aBind[':SITE_ID'] = $this->params[2];
        $aBind[':LANGUE_ID'] = $this->params[3];
        $aBind[':CONTENT_STATUS'] = 1;
        $aBind[':STATE_ID'] = 4;
        if ($this->params[4]) {
            $type_version = $this->params[4];
        } else {
            $type_version = "CURRENT";
        }
        
        // conditionner sur le PAGE_STATUS
        // sans contrôler si en mode prévisu
        if($type_version == 'CURRENT'){ // mode normal
            $cond_status = "p.PAGE_STATUS = 1";
        }else{ // mode prévisu
            $cond_status = "1 = 1";
        }
        
		$sSQL = "
			SELECT
				p.PAGE_ID,
				pz.CONTENT_ID,
				pv.PAGE_TITLE,
				pv.PAGE_CLEAR_URL
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
				INNER JOIN #pref#_content c
					ON (c.CONTENT_ID = pz.CONTENT_ID
					AND c.LANGUE_ID = :LANGUE_ID
					AND c.SITE_ID = :SITE_ID	
					)
				INNER JOIN #pref#_content_version cv
					ON (c.CONTENT_ID = cv.CONTENT_ID
					AND c.LANGUE_ID = cv.LANGUE_ID
					AND c.CONTENT_".$type_version."_VERSION = cv.CONTENT_VERSION
					)
			WHERE 
				p.PAGE_PARENT_ID = :PAGE_PARENT_ID
			AND p.SITE_ID = :SITE_ID
			AND p.LANGUE_ID = :LANGUE_ID
			AND $cond_status
			AND c.CONTENT_STATUS = :CONTENT_STATUS
			AND pv.STATE_ID = :STATE_ID
                        AND IF(cv.CONTENT_START_DATE IS NULL, FALSE, cv.CONTENT_START_DATE < now()) 
                        AND IF(cv.CONTENT_END_DATE IS NULL, TRUE, cv.CONTENT_END_DATE > now()) 
                        AND IF(pv.PAGE_START_DATE IS NULL, TRUE, pv.PAGE_START_DATE < now()) 
                        AND IF(pv.PAGE_END_DATE IS NULL, TRUE, pv.PAGE_END_DATE > now()) 
			ORDER BY cv.CONTENT_DATE desc";
		$aResults = $oConnection->queryTab($sSQL, $aBind);
		$aReturn = array();
		if(is_array($aResults) && count($aResults)>0){
			foreach($aResults as $key=>$result){
				if($result['PAGE_ID'] == $iCurrent && !empty($aResults[$key-1])){
					$aReturn['ACTU_PREC'] = $aResults[$key-1];
				}
				if($result['PAGE_ID'] == $iCurrent && !empty($aResults[$key+1])){
					$aReturn['ACTU_SUIV'] = $aResults[$key+1];
				}
			}
		}
		$this->value = $aReturn;
    }
}