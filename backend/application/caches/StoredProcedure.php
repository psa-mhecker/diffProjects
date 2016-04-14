<?php
/**
 */

/**
 * Fichier de Pelican_Cache : appel générique d'une procédure stockée.
 *
 * retour : *
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 17/10/2008
 */
class StoredProcedure extends Pelican_Cache
{
    public $duration = WEEK;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $name = $this->params[0];
        $params = explode(':::', $this->params[1]);
        if ($this->params[2]) {
            $db = $this->params[2];
        }
        if ($this->params[4]) {
            $xml = true;
        }

        if ($db) {
            $conn = Pelican_Db::getInstance($db);
        } else {
            $conn = Pelican_Db::getInstance();
        }
        $this->value = $conn->queryStoredProcedure($name, $params, array(), false, false, $xml);
    }
}
