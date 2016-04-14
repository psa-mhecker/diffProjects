<?php
/**
 * Fichier de Pelican_Cache : Retour WS GSA.
 */
use Citroen\Recherche;

class Frontend_Citroen_ResultatsRecherche extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $sQuery = $this->params[0];
        $iStart = $this->params[1];
        $sSite = $this->params[2];

        $aResult = Recherche::search($sQuery, $iStart, $sSite);

        $this->value = $aResult;
    }
}
