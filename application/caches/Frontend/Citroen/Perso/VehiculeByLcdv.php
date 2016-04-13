<?php
/**
 * Fichier de Pelican_Cache : Vehicule par identifiant
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Perso_VehiculeByLcdv extends Pelican_Cache {

    var $duration = DAY;
    
    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {

		$oConnection = Pelican_Db::getInstance ();        
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LCDV6'] = $oConnection->strToBind($this->params[1]);
        $sSQL = "
            SELECT 
				v.*
				
            FROM 
				#pref#_vehicule v 
            WHERE v.SITE_ID = :SITE_ID
			AND (v.VEHICULE_LCDV6_CONFIG = :LCDV6 OR v.VEHICULE_LCDV6_MANUAL = :LCDV6)
        ";
        $aVehicule = $oConnection->queryRow($sSQL, $aBind);
        $this->value = $aVehicule;
    }
}