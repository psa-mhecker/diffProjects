<?php

/**
 * Fichier de Pelican_Cache : Liste des Véhicules pour le transcodage des lcdv6.
 */
class Frontend_Citroen_Perso_VehiculesNamesInDebug extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind = array(
            ':SITE_ID' => $this->params[0], //$_SESSION[APP]['SITE_ID'],
            ':LANGUE_ID' => $this->params[1], // $_SESSION[APP]['LANGUE_ID'],
        );
        $sSQL = "SELECT VEHICULE_LABEL, VEHICULE_LCDV6_CONFIG , VEHICULE_LCDV6_MANUAL FROM psa_vehicule WHERE SITE_ID=:SITE_ID AND LANGUE_ID=:LANGUE_ID";
        $aVehicules = $oConnection->queryTab($sSQL, $aBind);

        if (count($aVehicules)) {
            $aVehiculesIndexed = array();
            foreach ($aVehicules as $aOneVehicule) {
                if ($aOneVehicule['VEHICULE_LCDV6_CONFIG'] != '') {
                    $aVehiculesIndexed[$aOneVehicule['VEHICULE_LCDV6_CONFIG']] = $aOneVehicule['VEHICULE_LABEL'];
                } elseif ($aOneVehicule['VEHICULE_LCDV6_MANUAL'] != '') {
                    $aVehiculesIndexed[$aOneVehicule['VEHICULE_LCDV6_MANUAL']] = $aOneVehicule['VEHICULE_LABEL'];
                }
            }
        }
        $this->value = $aVehiculesIndexed;
    }
}
