<?php
/**
 */

/**
 * Fichier de Pelican_Cache : Paramètres d'un bloc.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 12/12/2006
 */
class Zone extends Pelican_Cache
{
    public static $storage = 'file';

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":ZONE_ID"] = $this->params[0];

        $query = "SELECT
				z.*
				FROM
				#pref#_zone z";
        if ($this->params[0]) {
            $query .= " WHERE z.ZONE_ID = :ZONE_ID ";
        }
        $query .= " ORDER BY
				ZONE_LABEL";
        $result = $oConnection->queryTab($query, $aBind);
        $this->value = $result;
    }
}
