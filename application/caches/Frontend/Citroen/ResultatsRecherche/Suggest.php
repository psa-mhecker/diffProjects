<?php
/**
 * Fichier de Pelican_Cache : Retour WS GSA
 * @package Cache
 * @subpackage Pelican
 */
 
use Citroen\Recherche;
 
class Frontend_Citroen_ResultatsRecherche_Suggest extends Pelican_Cache {

    var $duration = DAY;
    
    /*
     * Valeur ou objet � mettre en Pelican_Cache
     */
    function getValue()
    {
		$sTerm = $this->params[0];
        $sSite = $this->params[1];

		$suggestJson = Recherche::suggest($sTerm, $sSite);
		
		$this->value = $suggestJson;
    }
}