<?php
/**
 * Fichier de Pelican_Cache
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Perso_Lcdv6ByProduct extends Pelican_Cache {

    var $duration = DAY;
	
    public $isPersistent = true;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':SITE_ID'] = $this->params[0];

        $sSQL = "
            SELECT
               IFNULL(VEHICULE_LCDV6_CONFIG,VEHICULE_LCDV6_MANUAL) as LCDV6,
               PRODUCT_ID
            FROM
              #pref#_perso_product pp
            INNER JOIN #pref#_vehicule v ON (v.VEHICULE_ID = pp.VEHICULE_ID and v.SITE_ID = :SITE_ID)
            WHERE
              pp.SITE_ID = :SITE_ID

        ";
        $results = $oConnection->queryTab($sSQL, $aBind);

        $cars = array();
        if(is_array($results) && count($results)>0){
            foreach($results as $index=>$result){
                $cars[$result['PRODUCT_ID']] = $result['LCDV6'];
            }
        }

        $this->value = $cars;
    }
}