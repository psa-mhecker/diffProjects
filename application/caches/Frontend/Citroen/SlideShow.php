<?php
/**
 * Fichier de Pelican_Cache : SlideShow
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_SlideShow extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet � mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':ZONE_TEMPLATE_ID'] = $this->params[0];
        $aBind[':SITE_ID'] = $this->params[1];
        $aBind[':LANGUE_ID'] = $this->params[2];
		if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }
        $sSQL = "
            SELECT 
				pzm.*,
				m.MEDIA_PATH as YOUTUBE
            FROM 
				#pref#_page_zone_multi pzm
            INNER JOIN #pref#_page p
				ON (p.PAGE_ID = pzm.PAGE_ID
					AND p.PAGE_".$type_version."_VERSION = pzm.PAGE_VERSION
				)
			LEFT JOIN #pref#_media m
				ON (m.MEDIA_ID = pzm.YOUTUBE_ID)
            WHERE p.SITE_ID = :SITE_ID
            AND p.LANGUE_ID = :LANGUE_ID
            and p.PAGE_STATUS = 1
            AND pzm.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
            ORDER BY PAGE_ZONE_MULTI_ID asc
        ";
        $aResults = $oConnection->queryTab($sSQL, $aBind);
		if(is_array($aResults) && count($aResults)>0){
			foreach($aResults as $key=>$result){
				for($i=1;$i<6;$i++){
					$aMedia = array();
					$iElement = ($i == 1) ? "" : $i;
					$aBind[':MEDIA_ID'] = $result['MEDIA_ID'.$iElement];
					
					$sSQL = "
							SELECT
								*
							FROM 
								#pref#_media
							WHERE 
								MEDIA_ID = :MEDIA_ID";
					$aMedia = $oConnection->queryRow($sSQL, $aBind);
					
					if(is_array($aMedia) && !empty($aMedia)){
						$aResults[$key]['MEDIA_PATH'.$iElement] = $aMedia['MEDIA_PATH'];
						$aResults[$key]['MEDIA_ALT'.$iElement] = $aMedia['MEDIA_ALT'];
					}
				}
			}
		}
        $this->value = $aResults;
    }
}