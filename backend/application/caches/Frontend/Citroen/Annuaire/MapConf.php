<?php

/**
 * Fichier de Pelican_Cache : Retour WS AnnuPDV.
 */
use Citroen\Annuaire;

class Frontend_Citroen_Annuaire_MapConf extends Pelican_Cache
{
    public $duration = DAY;

    /**
     * Valeur ou objet à mettre en Pelican_Cache.
     */
    public function getValue()
    {
        $aParams = explode('##', $this->params[0]);
        $iZoom = $aParams[0];
        $iStep = $aParams[1];
        $iRadius = $aParams[2];
        $iPdv = $aParams[3];
        $iDvn = $aParams[4];
        $iLat = $aParams[5];
        $iLon = $aParams[6];
        $sPays = $aParams[7];
        $sLocale = $aParams[8];
        $iRmax = $aParams[9];
        $bRegroupement = (boolean) $aParams[10];
        $bAutocompletion = (boolean) $aParams[11];
        $idPdv = $aParams[12];
        $nbMaxPdv = $aParams[13];

        $aConfig = Annuaire::getMapConfiguration($iZoom, $iStep, $iRadius, $iPdv, $iDvn, $iLat, $iLon, $sPays, $sLocale, $iRmax, $bRegroupement, $bAutocompletion, $idPdv, $nbMaxPdv);

        $this->value = $aConfig;
    }
}
