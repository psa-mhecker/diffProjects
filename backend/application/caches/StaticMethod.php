<?php

/**
 */

/**
 * Fichier de Pelican_Cache : Mise en Pelican_Cache du resultat d'une methode statique.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 30/06/2011
 */
class StaticMethod extends Pelican_Cache
{
    public $duration = UNLIMITED;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $class = $this->params[1];
        $method = $this->params[2];
        // sans argument ! sinon la clé de Pelican_Cache serait changeante


        // on suppose que la classe est deja incluse (souvent l'appel de ce Pelican_Cache se faisant par la classe elle-même);
        $result = call_user_func(array(
            $class,
            $method, ));
        $this->value = $result;
    }
}
