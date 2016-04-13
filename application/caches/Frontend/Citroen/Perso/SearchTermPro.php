<?php
/**
 * Fichier de Pelican_Cache
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Perso_SearchTermPro extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':SITE_ID'] = $this->params[0];
        if(isset($this->params[1]) && !empty($this->params[1])){
            $aBind[':TERM'] = $oConnection->strToBind($this->params[1]);    
        }
        
        $aBind[':PRODUCT_TERM_PRO'] = 1;

        $sSQL = "
            SELECT
                PRODUCT_TERM_LABEL
            FROM
              #pref#_perso_product_term
            WHERE
              SITE_ID = :SITE_ID
            and
              PRODUCT_TERM_PRO = :PRODUCT_TERM_PRO
            ";       
            if($aBind[':TERM']){
             " AND PRODUCT_TERM_LABEL = :TERM";   
            }
            
        $results = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $results;
    }
}