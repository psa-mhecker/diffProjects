<?php

/**
    * @package Cache
    * @subpackage General
    */

/**
 * Fichier de cache : Generateur de formulaire
 *
 * retour : id, lib
 *
 * @package Cache
 * @subpackage General
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 15/02/2014
 */
class BoForms extends Pelican_Cache
{

    /**
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $query = "SELECT *
                FROM
                #pref#_boforms ";
        if ($this->params[0]) {
            $query .= " WHERE BOFORMS_ID = " . $this->params[0];
        }
        $this->value = $oConnection->queryRow($query);
        if (! empty($this->value['BOFORMS_STRUCTURE'])) {
            $this->value['BOFORMS_STRUCTURE'] = rawurldecode($this->value['BOFORMS_STRUCTURE']);
        }
    }
}
