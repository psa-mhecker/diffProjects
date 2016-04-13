<?php
/**
 * Fichier de Pelican_Cache : Actualit�s pager
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Actualites_PageClearUrlByActu extends Pelican_Cache {

    var $duration = HOUR;

    /*
     * Valeur ou objet � mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':PAGE_PARENT_ID'] = $this->params[0];
        $aBind[':SITE_ID'] = $this->params[1];
        $aBind[':LANGUE_ID'] = $this->params[2];
        $aBind[':CONTENT_STATUS'] = 1;
        $aBind[':STATE_ID'] = 4;
        if ($this->params[3]) {
            $type_version = $this->params[3];
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
			AND $cond_status
			AND pv.STATE_ID = :STATE_ID";
		$aResults = $oConnection->queryTab($sSQL, $aBind);
		$aReturn = array();
		if(is_array($aResults) && count($aResults)>0){
			foreach($aResults as $key=>$result){
				$aReturn[$result['CONTENT_ID']] = $result['PAGE_CLEAR_URL'];
			}
		}
		$this->value = $aReturn;
    }
}