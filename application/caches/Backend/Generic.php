<?php

/**
 * @package Cache
 * @subpackage General
 */

/**
 * Fichier de Pelican_Cache : Résultat de requête sur Pelican::$config['FW_PREFIXE_TABLE'].$this->params[0]
 *
 * retour : *, id, lib
 *
 * @package Cache
 * @subpackage General
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 20/06/2004
 */
class Backend_Generic extends Pelican_Cache
{

    
    var $duration = WEEK;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue()
    {
        
        $oConnection = Pelican_Db::getInstance();
        
        $name = $this->params[0];
        $order = '';
        $where = '';

        if (! empty($this->params[1])) {
            $order = $this->params[1];
        }
        if (! empty($this->params[2])) {
            $where = $this->params[2];
        }
        
        $query = "SELECT *
				FROM
				" . Pelican::$config['FW_PREFIXE_TABLE'] . $name;
        if ($where) {
            $query .= " WHERE " . $where;
        }
        $query .= " ORDER BY
				" . ($order ? $order : $name . "_LABEL");
        $result = $oConnection->queryTab($query);
        $this->value = $result;
    }
}

?>