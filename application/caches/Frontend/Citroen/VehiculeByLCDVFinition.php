<?php
/**
 * Fichier de Pelican_Cache : Vehicule par lcdv/finition
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_VehiculeByLCDVFinition extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
		$oConnection = Pelican_Db::getInstance ();
        $aBind[':LCDV6'] = $oConnection->strToBind($this->params[0]);
        $aBind[':FINITION'] = $oConnection->strToBind($this->params[1]);
        
        $aBind[':SITE_ID'] = $this->params[2];
        $aBind[':LANGUE_ID'] = $this->params[3];
        $sSQL = "
            SELECT 
				v.*,
                ws_fv.*,
				m.MEDIA_PATH as VISUEL_VEHICULE
            FROM 
				#pref#_vehicule v
			LEFT JOIN #pref#_media m
				ON (m.MEDIA_ID = v.VEHICULE_MEDIA_ID_THUMBNAIL)
			LEFT JOIN #pref#_ws_prix_finition_version ws_fv
				ON COALESCE(v.VEHICULE_LCDV6_CONFIG, v.VEHICULE_LCDV6_MANUAL) =ws_fv.LCDV6
            WHERE v.SITE_ID = :SITE_ID
            AND v.LANGUE_ID = :LANGUE_ID
            AND ws_fv.GR_COMMERCIAL_NAME_CODE = :FINITION
			AND (
				(v.VEHICULE_LCDV6_CONFIG = :LCDV6)
			OR  (v.VEHICULE_LCDV6_MANUAL = :LCDV6)
				)
        ";
        $aVehicule = $oConnection->queryRow($sSQL, $aBind);
        $this->value = $aVehicule;
    }
}