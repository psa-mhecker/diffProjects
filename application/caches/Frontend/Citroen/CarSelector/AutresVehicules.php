<?php
/**
 * Fichier de Pelican_Cache : Autres vehicules
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_CarSelector_AutresVehicules extends Pelican_Cache {

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
        
        // conditionner sur le PAGE_STATUS
        // sans contrôler si en mode prévisu
        if($type_version == 'CURRENT'){ // mode normal
            $cond_status = "p.PAGE_STATUS = 1";
        }else{ // mode prévisu
            $cond_status = "1 = 1";
        }
        
        $sSQL = "
            SELECT 
				pzm.*,
				m.MEDIA_PATH
            FROM 
				#pref#_page_zone_multi pzm
            INNER JOIN #pref#_page p
				ON (p.PAGE_ID = pzm.PAGE_ID AND p.PAGE_".$type_version."_VERSION = pzm.PAGE_VERSION)
			LEFT JOIN #pref#_media m
				ON (m.MEDIA_ID = pzm.MEDIA_ID)
            INNER JOIN #pref#_page_version pv
                ON (pv.PAGE_ID = p.PAGE_ID
                    AND pv.LANGUE_ID = p.LANGUE_ID
                    AND pv.PAGE_VERSION = p.PAGE_CURRENT_VERSION)    
            WHERE 
				p.SITE_ID = :SITE_ID
            AND p.LANGUE_ID = :LANGUE_ID
            AND pzm.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
            AND $cond_status
            AND pv.STATE_ID = 4
            ORDER BY PAGE_ZONE_MULTI_ID ASC
        ";
        $aResults = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResults;
    }
}