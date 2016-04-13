<?php
/**
 * Fichier de Pelican_Cache : Retour WS AOA
 * @package Cache
 * @subpackage Pelican
 */
 
use Citroen\Accessoires;
 
class Frontend_Citroen_Accessoires_CriteriaValues extends Pelican_Cache {

    var $duration = DAY;
    
    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
		$sCriterion = $this->params[0];
		$sLanguageCode = $this->params[1];
		$aUniverses = Accessoires::getCriteriaValues($sCriterion,$sLanguageCode);
		$this->value = $aUniverses;
    }
}