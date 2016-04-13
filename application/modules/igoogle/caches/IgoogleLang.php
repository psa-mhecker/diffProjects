<?php

/**
    * @package Cache
    * @subpackage Pelican
    */

/**
 * Fichier de Pelican_Cache : paramètres de langue d'un gadget iGoogle
 *
 * @package Cache
 * @subpackage Pelican
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 * @since 23/04/2007
 */
class IgoogleLang extends Pelican_Cache
{

    /**
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue ()
    {
        $url = $this->params[0];
        
        $temp = @file($url);
        if (is_array($temp)) {
            foreach ($temp as $line) {
                $xmlLang .= $line;
            }
        }
        if ($xmlLang) {
            preg_match_all('/<msg name="([^>]*)">([^>]*)<\/msg>/i', $xmlLang, $matches);
            if ($matches) {
                $count = count($matches[1]);
                for ($i = 0; $i < $count; $i ++) {
                    $traduction['__MSG_' . $matches[1][$i] . '__'] = htmlentities($matches[2][$i]);
                }
            }
        }
        
        $this->value = $traduction;
    }
}
