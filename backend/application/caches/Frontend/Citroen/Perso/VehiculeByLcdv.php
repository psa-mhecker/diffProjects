<?php
/**
 * Fichier de Pelican_Cache : Vehicule par identifiant.
 */
class Frontend_Citroen_Perso_VehiculeByLcdv extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LCDV6'] = $oConnection->strToBind($this->params[1]);
        $sSQL = "
            SELECT
				v.*

            FROM
				#pref#_vehicule v
            WHERE v.SITE_ID = :SITE_ID
			AND v.VEHICULE_LCDV6_CONFIG = :LCDV6
        ";
        $aVehicule = $oConnection->queryRow($sSQL, $aBind);
        $this->value = $aVehicule;
    }
}
