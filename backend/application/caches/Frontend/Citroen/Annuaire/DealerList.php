<?php
/**
 * Fichier de Pelican_Cache : Retour WS AnnuPDV.
 */
use Citroen\Annuaire;

class Frontend_Citroen_Annuaire_DealerList extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $iLat = $this->params[0];
        $iLon = $this->params[1];
        $sPays = $this->params[2];
        $sLocale = $this->params[3];
        $iRmax = $this->params[4];
        $aRequest = $this->params[5];
        $nbMaxPdv = $this->params[6];
        $pdv = $this->params[7];
        $dvn = $this->params[8];

        $aDealers = Annuaire::getDealerList($iLat, $iLon, $sPays, $sLocale, $iRmax, $aRequest, $nbMaxPdv, $pdv, $dvn);

        $this->value = $aDealers;
    }
}
