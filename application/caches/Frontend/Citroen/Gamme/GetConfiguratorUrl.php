<?php
/**
 * Fichier de Pelican_Cache : Retour WS PSA.CFG3D.VU.Services
 * @package Cache
 * @subpackage Pelican
 */

use Citroen\Gamme;

class Frontend_Citroen_Gamme_GetConfiguratorUrl extends Pelican_Cache
{

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
       
        $Pays = $this->params[0];
        $Locale = $this->params[1];

        try{
         $this->value = Gamme::getConfiguratorUrlList( $Pays, $Locale);
         }catch(Exception $e){
            
         }
    }
}