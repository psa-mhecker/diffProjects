<?php
/**
 * Fichier de Pelican_Cache : Vehicule par identifiant.
 */
class Frontend_Citroen_VehiculeById extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':VEHICULE_ID'] = $this->params[0];
        $aBind[':SITE_ID'] = $this->params[1];
        $aBind[':LANGUE_ID'] = $this->params[2];
        $sSQL = "
            SELECT
				v.*,
				m.MEDIA_PATH as VISUEL_VEHICULE
            FROM
				#pref#_vehicule v
			LEFT JOIN #pref#_media m
				ON (m.MEDIA_ID = v.VEHICULE_MEDIA_ID_THUMBNAIL)
            WHERE v.SITE_ID = :SITE_ID
            AND v.LANGUE_ID = :LANGUE_ID
			AND v.VEHICULE_ID = :VEHICULE_ID
        ";
        $sUrl = $oConnection->queryRow($sSQL, $aBind);
        $this->value = $sUrl;
    }
}
