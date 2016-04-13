<?php
/**
 * Fichier de Pelican_Cache : Perso Activation
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Perso_Activation extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $con = Pelican_Db::getInstance ();
        $bind = array (":SITE_ID" => $this->params[0]);
        $sql = "
            SELECT
                ZONE_ID
            FROM
              #pref#_site_personnalisation
            WHERE
            SITE_ID = :SITE_ID

        ";
        $zones = $con->queryTab($sql, $bind);
        $result = array();
        foreach($zones as $zone) {
           $result[] =  $zone['ZONE_ID'];
        }

        $this->value = $result;
    }
}