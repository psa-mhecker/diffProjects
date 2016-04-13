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
class FormBuilder extends Pelican_Cache
{

    /**
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $query = "SELECT *
                FROM
                #pref#_formbuilder ";
        if ($this->params[0]) {
            $query .= " WHERE FORMBUILDER_ID = " . $this->params[0];
        }
        $this->value = $oConnection->queryRow($query);
        if (! empty($this->value['FORMBUILDER_STRUCTURE'])) {
            $this->value['FORMBUILDER_STRUCTURE'] = rawurldecode($this->value['FORMBUILDER_STRUCTURE']);
        }
    }
}
