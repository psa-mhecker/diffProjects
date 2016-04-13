<?php
    /**
    * @package Cache
    * @subpackage Config
    */

    /**
    * Fichier de Pelican_Cache : Résultat de requête sur ZONE_TYPE
    *
    * retour : id, lib
    *
    * @package Cache
    * @subpackage Config
    * @author Raphaël Carles <rcarles@businessdecision.com>
    * @since 20/06/2004
    */
    class Backend_ZoneType extends Pelican_Cache {


        var $duration = WEEK;

        /** Valeur ou objet à mettre en Pelican_Cache */
        function getValue() {

            $oConnection = Pelican_Db::getInstance();

            $query = "SELECT
                ZONE_TYPE_ID,
                ZONE_TYPE_LABEL
                FROM
                #pref#_zone_type
                WHERE ZONE_TYPE_ID > 0
                ORDER BY
                ZONE_TYPE_ID";
            $this->value = $oConnection->queryTab($query);
        }
    }
?>