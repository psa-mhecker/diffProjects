<?php
/**
 * Fichier de Pelican_Cache : Retour WS AnnuPDV.
 */
use Citroen\Annuaire;

class Frontend_Citroen_Annuaire_Dealer extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet � mettre en Pelican_Cache
     */
    public function getValue()
    {
        $id = $this->params[0];
        $sPays = $this->params[1];
        $sLocale = $this->params[2];

        $aDealer = Annuaire::getDealer($id, $sPays, $sLocale);

        $this->value = $aDealer;
    }
}
