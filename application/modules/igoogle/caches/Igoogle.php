<?php

/**
    * @package Cache
    * @subpackage Pelican
    */

/**
 * Fichier de Pelican_Cache : paramètres d'un gadget iGoogle
 *
 * @package Cache
 * @subpackage Pelican
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @since 23/04/2007
 */
class Igoogle extends Pelican_Cache
{

    /**
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue ()
    {
        $url = $this->params[0];
        
        $xml = $this->getUrl($url);
        
        if (! $xml) {
            $this->deprecated = false;
        }
        
        $this->value = $xml;
    }

    /**
     *
     *
     *
     * Appel CURL du web service
     *
     * @param string $url            
     */
    function getUrl ($url)
    {
        // $user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
        $parse = parse_url($url);
        $ch = curl_init(); // initialize curl handle
        
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_PORT, ($parse["scheme"] == "https" ? 443 : 80));
        curl_setopt($ch, CURLOPT_TIMEOUT, 15); // times out after 15s
                                               // curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        $text = curl_exec($ch);
        curl_close($ch);
        
        return $text;
    }
}
