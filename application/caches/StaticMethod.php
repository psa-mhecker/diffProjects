<?php

/**
 * @package Cache
 * @subpackage General
 */

/**
 * Fichier de Pelican_Cache : Mise en Pelican_Cache du resultat d'une methode statique
 *
 * @package Cache
 * @subpackage General
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 30/06/2011
 */
class StaticMethod extends Pelican_Cache
{

    
    var $duration = UNLIMITED;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue()
    {
        
        
        $class = $this->params[1];
        $method = $this->params[2];
        // sans argument ! sinon la clé de Pelican_Cache serait changeante
        $params = $this->params[3];

        // on suppose que la classe est deja incluse (souvent l'appel de ce Pelican_Cache se faisant par la classe elle-m锚me);
        if (is_array($params)) {
            $result = call_user_func_array(array(
                $class,
                $method), $params);
        } else {
            $result = call_user_func(array(
                $class,
                $method), $params);
        }
        $this->value = $result;
    }
}
?>