<?php
/**
 * Fichier de Pelican_Cache : Actualités
 * @package Cache
 * @subpackage Pelican 
 */
class Frontend_Citroen_Actualites_Liste extends Pelican_Cache {

    var $duration = HOUR;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
		$aBind[':PAGE_ID'] = $this->params[0];
        $aBind[':SITE_ID'] = $this->params[1];
        $aBind[':LANGUE_ID'] = $this->params[2];
        $aBind[':CONTENT_TYPE_ID'] = Pelican::$config['CONTENT_TYPE_ID']['ACTUALITE'];
        $aBind[':CONTENT_STATUS'] = 1;
        $aBind[':STATE_ID'] = 4;
        $aBind[':CONTENT_CODE'] = $this->params[4];
        $iMin = $this->params[5];
        if ($this->params[6]) {
            $type_version = $this->params[6];
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
				pz.CONTENT_ID
			FROM 
				#pref#_page p
				INNER JOIN #pref#_page_version pv
					ON (p.PAGE_ID = pv.PAGE_ID
						AND p.PAGE_CURRENT_VERSION = pv.PAGE_VERSION
						AND p.LANGUE_ID = pv.LANGUE_ID
					)
				INNER JOIN #pref#_page_zone pz
					ON (p.PAGE_ID = pz.PAGE_ID
						AND p.PAGE_CURRENT_VERSION = pz.PAGE_VERSION
						AND p.LANGUE_ID = pz.LANGUE_ID
					)
			WHERE 
				p.PAGE_PARENT_ID = :PAGE_ID
			AND p.SITE_ID = :SITE_ID
			AND p.LANGUE_ID = :LANGUE_ID
			AND $cond_status
			AND pv.STATE_ID = :STATE_ID
                        AND IF(pv.PAGE_START_DATE IS NULL, TRUE, pv.PAGE_START_DATE < now()) 
                        AND IF(pv.PAGE_END_DATE IS NULL, TRUE, pv.PAGE_END_DATE > now()) ";
		$aContentChild = $oConnection->queryTab($sSQL, $aBind);

		if(is_array($aContentChild) && count($aContentChild)>0){
			//count
			if($this->params[3] == true){
				$sSQL = "
					SELECT
						count(*)
					FROM 
						#pref#_content c
						INNER JOIN #pref#_content_version cv
							ON (c.CONTENT_ID = cv.CONTENT_ID
								AND c.CONTENT_CURRENT_VERSION = cv.CONTENT_VERSION
								AND c.LANGUE_ID = cv.LANGUE_ID
							)
					WHERE 
						c.CONTENT_TYPE_ID = :CONTENT_TYPE_ID
					AND c.SITE_ID = :SITE_ID
					AND c.LANGUE_ID = :LANGUE_ID
					AND c.CONTENT_STATUS = :CONTENT_STATUS
					AND cv.CONTENT_DATE2 < now()
                                        AND IF(cv.CONTENT_START_DATE IS NULL, TRUE, cv.CONTENT_START_DATE < now())
                                        AND IF(cv.CONTENT_END_DATE IS NULL, TRUE, cv.CONTENT_END_DATE > now()) 
					AND c.CONTENT_ID IN (";
					foreach($aContentChild as $key=>$child){
						$aBind[':CHILD_'.$key] = $child['CONTENT_ID'];
						if($key != 0){
							$sSQL .= ",";
						}
						$sSQL .= " :CHILD_".$key;
					}
					$sSQL .= ")";
				$iCount = $oConnection->queryItem($sSQL, $aBind);
				$this->value = $iCount;
			}else{
				$sSQL = "
					SELECT
						c.*,
						cv.*,
						m.*,
						DATE_FORMAT(cv.CONTENT_DATE2, '%d/%m/%Y') as DATE_FR,
						DATE_FORMAT(cv.CONTENT_DATE2, '%m/%d/%Y') as DATE_UK,
						DATE_FORMAT(cv.CONTENT_DATE2, '%a-%d-%b-%Y') as DATE_LETTER,
						DATE_FORMAT(cv.CONTENT_DATE2, '%Y-%m-%d') as DATE_TIME_HTML,
						DATE_FORMAT(cv.CONTENT_START_DATE, '%Y-%m-%d') as DATE_TIME_START
					FROM 
						#pref#_content c
						INNER JOIN #pref#_content_version cv
							ON (c.CONTENT_ID = cv.CONTENT_ID
								AND c.CONTENT_CURRENT_VERSION = cv.CONTENT_VERSION
								AND c.LANGUE_ID = cv.LANGUE_ID
							)
						LEFT JOIN #pref#_media m
							ON (cv.MEDIA_ID = m.MEDIA_ID)
					WHERE 
						c.CONTENT_TYPE_ID = :CONTENT_TYPE_ID
					AND c.SITE_ID = :SITE_ID
					AND c.LANGUE_ID = :LANGUE_ID
					AND c.CONTENT_STATUS = :CONTENT_STATUS
					";

				if ($this->params[7]) {
		            $aBind[':CONTENT_DIRECT_PAGE'] = $this->params[7];  
		            $sSQL .= " and cv.CONTENT_DIRECT_PAGE = :CONTENT_DIRECT_PAGE ";
		        }


				$sSQL .= "
				    AND cv.CONTENT_DATE2 < now()
					AND c.CONTENT_ID IN (
		        ";


					foreach($aContentChild as $key=>$child){
						$aBind[':CHILD_'.$key] = $child['CONTENT_ID'];
						if($key != 0){
							$sSQL .= ",";
						}
						$sSQL .= " :CHILD_".$key;
					}
					$sSQL .= ") ";
				if($this->params[4] != ""){
					$sSQL .= " AND CONTENT_CODE = :CONTENT_CODE";
				}
				$sSQL .= " AND IF(cv.CONTENT_START_DATE IS NULL, TRUE, cv.CONTENT_START_DATE < now())
                                            AND IF(cv.CONTENT_END_DATE IS NULL, TRUE, cv.CONTENT_END_DATE > now()) ";
                                
				$sSQL .= " ORDER BY cv.CONTENT_DATE2 desc";
				$sSQL = $oConnection->getLimitedSQL($sSQL, $iMin, 10, true, $aBind);
				$aResults = $oConnection->queryTab($sSQL, $aBind);
				$this->value = $aResults;
			}
		}
    }
}