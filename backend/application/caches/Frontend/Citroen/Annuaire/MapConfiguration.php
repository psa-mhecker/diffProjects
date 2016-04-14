<?php

/**
 * Fichier de Pelican_Cache : Retour WS AnnuPDV.
 */
use Citroen\Annuaire;

class Frontend_Citroen_Annuaire_MapConfiguration extends Pelican_Cache
{
    public $duration = DAY;

    /**
     * Valeur ou objet à mettre en Pelican_Cache.
     */
    public function getValue()
    {
        $iZoom = $this->params[0];
        $iStep = $this->params[1];
        $iRadius = $this->params[2];
        $iPdv = $this->params[3];
        $iDvn = $this->params[4];
        $iLat = $this->params[5];
        $iLon = $this->params[6];
        $sPays = $this->params[7];
        $sLocale = $this->params[8];
        $iRmax = $this->params[9];

        $aConfig = Annuaire::getMapConfiguration($iZoom, $iStep, $iRadius, $iPdv, $iDvn, $iLat, $iLon, $sPays, $sLocale, $iRmax);

        $this->value = $aConfig;
    }
}
