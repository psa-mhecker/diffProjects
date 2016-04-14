<?php
/**
 * Fichier de Pelican_Cache : Liste des energies vehicule.
 */
class Frontend_Citroen_EnergiesVehicule extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sSQL = "
            SELECT
				DISTINCT ENERGY_CATEGORY as ENERGY_LABEL
            FROM
				#pref#_ws_caracteristique_moteur
            WHERE SITE_ID = :SITE_ID
            AND LANGUE_ID = :LANGUE_ID
            ORDER BY ENERGY_CATEGORY ASC
        ";
        $aResult = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResult;
    }
}
