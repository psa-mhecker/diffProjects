<?php
/**
 * Fichier de Pelican_Cache : Retour WS AnnuPDV
 * @package Cache
 * @subpackage Pelican
 */
 
use Citroen\Annuaire;
 
class Frontend_Citroen_Annuaire_Dealer extends Pelican_Cache 
{

    var $duration = DAY;
    
    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
		$id = $this->params[0];
		$sPays = $this->params[1];
		$sLocale = $this->params[2];

		$aDealer = Annuaire::getDealer($id, $sPays, $sLocale);
		
		$this->value = $aDealer;
    }
}