<?php
/**
 * Fichier de Pelican_Cache : Vehicule par lcdv/gamme.
 */
class Frontend_Citroen_VehiculeByLCDVGamme extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':LCDV6'] = $oConnection->strToBind($this->params[0]);
        $aBind[':GAMME'] = $oConnection->strToBind($this->params[1]);
        $aBind[':GAMME_LCDV6'] = $oConnection->strToBind($this->params[0].'_'.$this->params[1]);
        $aBind[':SITE_ID'] = $this->params[2];
        $aBind[':LANGUE_ID'] = $this->params[3];
        $sSQL = "
            SELECT
				v.*,
				ws_vg.LCDV4,
				m.MEDIA_PATH as VISUEL_VEHICULE
            FROM
				#pref#_vehicule v
			LEFT JOIN #pref#_media m
				ON (m.MEDIA_ID = v.VEHICULE_MEDIA_ID_THUMBNAIL)
			LEFT JOIN #pref#_ws_vehicule_gamme ws_vg
				ON COALESCE(v.VEHICULE_LCDV6_CONFIG, v.VEHICULE_LCDV6_MANUAL) = ws_vg.LCDV6
            WHERE v.SITE_ID = :SITE_ID
            AND v.LANGUE_ID = :LANGUE_ID
			AND (
				(v.VEHICULE_LCDV6_CONFIG = :LCDV6)
			OR  (v.VEHICULE_LCDV6_MANUAL = :LCDV6)
				)
        ";
        $aVehicule = $oConnection->queryRow($sSQL, $aBind);
        $this->value = $aVehicule;
    }
}
